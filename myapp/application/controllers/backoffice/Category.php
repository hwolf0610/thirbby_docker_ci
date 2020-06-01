<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Category extends CI_Controller { 
    public $module_name = 'Category';
    public $controller_name = 'category';
    public $prefix = '_cg';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/category_model');
    }
    //view data
    public function view() {
        $data['meta_title'] = $this->lang->line('title_category').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/category',$data);
    }
    //add data
    public function add() {
        $data['meta_title'] = $this->lang->line('title_category_add').' | '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
            if ($this->form_validation->run())
            {
                $add_data = array(                   
                    'name'=>$this->input->post('name'),
                    'status'=>1,
                    'created_by' => $this->session->userdata('UserID')
                ); 
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/category';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/category')) {
                      @mkdir('./uploads/category', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $add_data['image'] = "category/".$img['file_name'];    
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if(empty($data['Error'])){
                    $this->category_model->addData('category',$add_data); 
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');  
                }         
            }
        }
        $this->load->view(ADMIN_URL.'/category_add',$data);
    }
    //edit data
    public function edit() {
        $data['meta_title'] = $this->lang->line('title_category_edit').' | '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
            if ($this->form_validation->run())
            {
                $updateData = array(                   
                    'name'=>$this->input->post('name'),
                    'updated_date'=>date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('UserID')
                ); 
                
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/category';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/category')) {
                      @mkdir('./uploads/category', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $updateData['image'] = "category/".$img['file_name'];   
                      if($this->input->post('uploaded_image')){
                        @unlink(FCPATH.'uploads/'.$this->input->post('uploaded_image'));
                      }  
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if(empty($data['Error'])){
                    $this->category_model->updateData($updateData,'category','entity_id',$this->input->post('entity_id'));
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
                }
                  
            }
        }        
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->category_model->getEditDetail($entity_id);
        $this->load->view(ADMIN_URL.'/category_add',$data);
    }
    //ajax view
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'name','2'=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->category_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->name,
                ($val->status)?'Active':'Deactive',
                '<a class="btn btn-sm danger-btn margin-bottom blue-btn" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> Edit</a> <button onclick="deleteDetail('.$val->entity_id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button>'
            );
            $nCount++;
            /*<button onclick="disableDetail('.$val->entity_id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>*/
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    // method to change category status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->category_model->UpdatedStatus('category',$entity_id,$this->input->post('status'));
        }
    }
    // method for deleting a category
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->category_model->ajaxDelete('category',$entity_id);
    }
}