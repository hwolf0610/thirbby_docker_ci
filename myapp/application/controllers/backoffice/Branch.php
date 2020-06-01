<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Branch extends CI_Controller {
    public $module_name = 'Branch';
    public $controller_name = 'branch';
    public $prefix = '_br';  
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/branch_model');
    }
    // view branch
    public function view(){
    	$data['meta_title'] = $this->lang->line('title_admin_branch').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/branch',$data);
    }
    // add branch
    public function add(){
        $data['meta_title'] = $this->lang->line('title_admin_branchadd').' | '.$this->lang->line('site_title');
    	if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Branch Name', 'trim|required');
            $this->form_validation->set_rules('branch_entity_id', 'Restaurant', 'trim|required');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
            $this->form_validation->set_rules('email','Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('capacity','Capacity', 'trim|required');
            $this->form_validation->set_rules('no_of_table','No of table', 'trim|required');
            $this->form_validation->set_rules('address','Address', 'trim|required');
            $this->form_validation->set_rules('landmark','Landmark', 'trim|required');
            $this->form_validation->set_rules('latitude','Latitude', 'trim|required');
            $this->form_validation->set_rules('longitude','Longitude', 'trim|required');
            $this->form_validation->set_rules('state','State', 'trim|required');
            $this->form_validation->set_rules('country','Country','trim|required');
            $this->form_validation->set_rules('city','City', 'trim|required');
            $this->form_validation->set_rules('zipcode','Zipcode', 'trim|required');
            $this->form_validation->set_rules('amount_type','Amount Type', 'trim|required');
            $this->form_validation->set_rules('amount','Amount', 'trim|required');
            $this->form_validation->set_rules('enable_hours','Enable Hours', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $add_data = array(                  
                    'name'=>$this->input->post('name'),
                    'branch_entity_id'=>$this->input->post('branch_entity_id'),
                    'phone_number' =>$this->input->post('phone_number'),
                    'email' =>$this->input->post('email'),
                    'capacity' =>$this->input->post('capacity'),
                    'no_of_table' =>$this->input->post('no_of_table'),
                    'no_of_hall' =>$this->input->post('no_of_hall'),
                    'hall_capacity' =>$this->input->post('hall_capacity'),
                    'amount_type'=>$this->input->post("amount_type"),
                    'amount'=>$this->input->post("amount"),
                    'enable_hours'=>$this->input->post("enable_hours"),
                    'status'=>1,
                    'created_by' => $this->session->userdata('UserID')
                );   
                if(!empty($this->input->post('timings'))){
                    $timingsArr = $this->input->post('timings');
                    $newTimingArr = array();
                    foreach($timingsArr as $key=>$value) {
                        if(isset($value['off'])) {
                            $newTimingArr[$key]['open'] = '';
                            $newTimingArr[$key]['close'] = '';
                            $newTimingArr[$key]['off'] = '0';
                        } else {
                            if(!empty($value['open']) && !empty($value['close'])) {
                                $newTimingArr[$key]['open'] = $value['open'];
                                $newTimingArr[$key]['close'] = $value['close'];
                                $newTimingArr[$key]['off'] = '1';
                            } else {
                                $newTimingArr[$key]['open'] = '';
                                $newTimingArr[$key]['close'] = '';
                                $newTimingArr[$key]['off'] = '0';
                            }
                        }
                    }
                    $add_data['timings'] = serialize($newTimingArr); 
                }                                        
                
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/restaurant';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/restaurant')) {
                      @mkdir('./uploads/restaurant', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $add_data['image'] = "restaurant/".$img['file_name'];    
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                $entity_id = '';
                if(empty($data['Error'])){
                    $entity_id = $this->branch_model->addData('restaurant',$add_data);
                    //for address
                    $add_data = array(
                        'resto_entity_id'=>$entity_id,
                        'address' =>$this->input->post('address'),
                        'landmark' =>$this->input->post('landmark'),
                        'latitude' =>$this->input->post('latitude'),
                        'longitude'=>$this->input->post("longitude"),
                        'state'=>$this->input->post("state"),
                        'country'=>$this->input->post("country"),
                        'city'=>$this->input->post("city"),
                        'zipcode'=>$this->input->post("zipcode"),
                    );
                    $this->branch_model->addData('restaurant_address',$add_data);
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');                 
                }   
            }
        }
        $data['restaurant'] = $this->branch_model->getListData('restaurant'); 
    	$this->load->view(ADMIN_URL.'/branch_add',$data);
    }
    // edit branch
    public function edit(){
    	$data['meta_title'] = $this->lang->line('title_admin_branchedit').' | '.$this->lang->line('site_title');
        // check if form is submitted 
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Branch Name', 'trim|required');
            $this->form_validation->set_rules('branch_entity_id', 'Restaurant', 'trim|required');
            $this->form_validation->set_rules('email','Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('capacity','Capacity', 'trim|required|numeric');
            $this->form_validation->set_rules('no_of_table','No of table', 'trim|required|numeric');
            $this->form_validation->set_rules('address','Address', 'trim|required');
            $this->form_validation->set_rules('landmark','Landmark', 'trim|required');
            $this->form_validation->set_rules('latitude','Latitude', 'trim|required');
            $this->form_validation->set_rules('longitude','Longitude', 'trim|required');
            $this->form_validation->set_rules('state','State', 'trim|required');
            $this->form_validation->set_rules('country','Country','trim|required');
            $this->form_validation->set_rules('city','City', 'trim|required');
            $this->form_validation->set_rules('zipcode','Zipcode', 'trim|required');
            $this->form_validation->set_rules('amount_type','Amount Type', 'trim|required');
            $this->form_validation->set_rules('amount','Amount', 'trim|required');
            $this->form_validation->set_rules('enable_hours','Enable Hours', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $edit_data = array( 
                    'branch_entity_id'=>$this->input->post('branch_entity_id'),                 
                    'name'=>$this->input->post('name'),
                    'phone_number' =>$this->input->post('phone_number'),
                    'email' =>$this->input->post('email'),
                    'capacity' =>$this->input->post('capacity'),
                    'no_of_table' =>$this->input->post('no_of_table'),
                    'no_of_hall' =>$this->input->post('no_of_hall'),
                    'hall_capacity' =>$this->input->post('hall_capacity'),
                    'amount_type'=>$this->input->post("amount_type"),
                    'amount'=>$this->input->post("amount"),
                    'enable_hours'=>$this->input->post("enable_hours"),
                    'status'=>1,
                    'created_by' => $this->session->userdata('UserID')
                );   
                if(!empty($this->input->post('timings'))){
                    $timingsArr = $this->input->post('timings');
                    $newTimingArr = array();
                    foreach($timingsArr as $key=>$value) {
                        if(isset($value['off'])) {
                            $newTimingArr[$key]['open'] = '';
                            $newTimingArr[$key]['close'] = '';
                            $newTimingArr[$key]['off'] = '0';
                        } else {
                            if(!empty($value['open']) && !empty($value['close'])) {
                                $newTimingArr[$key]['open'] = $value['open'];
                                $newTimingArr[$key]['close'] = $value['close'];
                                $newTimingArr[$key]['off'] = '1';
                            } else {
                                $newTimingArr[$key]['open'] = '';
                                $newTimingArr[$key]['close'] = '';
                                $newTimingArr[$key]['off'] = '0';
                            }
                        }
                    }
                    $edit_data['timings'] = serialize($newTimingArr); 
                } 

                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/restaurant';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/restaurant')) {
                      @mkdir('./uploads/restaurant', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $edit_data['image'] = "restaurant/".$img['file_name'];   
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
                    $this->branch_model->updateData($edit_data,'restaurant','entity_id',$this->input->post('entity_id'));
                     //for address
                    $edit_data = array(
                        'resto_entity_id'=>$this->input->post('entity_id'),
                        'address' =>$this->input->post('address'),
                        'landmark' =>$this->input->post('landmark'),
                        'latitude' =>$this->input->post('latitude'),
                        'longitude'=>$this->input->post("longitude"),
                        'state'=>$this->input->post("state"),
                        'country'=>$this->input->post("country"),
                        'city'=>$this->input->post("city"),
                        'zipcode'=>$this->input->post("zipcode"),
                    );
                    $this->branch_model->updateData($edit_data,'restaurant_address','resto_entity_id',$this->input->post('entity_id'));
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view'); 
                }
                               
            }
        }    
        $data['restaurant'] = $this->branch_model->getListData('restaurant'); 
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->branch_model->getEditDetail('restaurant',$entity_id);
        $this->load->view(ADMIN_URL.'/branch_add',$data);
    }
    // method to change status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->branch_model->UpdatedStatus('restaurant',$entity_id,$this->input->post('status'));
        }
    }
    // method for delete
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->branch_model->ajaxDelete('restaurant',$entity_id);
    }
    // call for ajax data
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'res.name',2=>'resta.name',3=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->branch_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->rname,
                $val->name,
                ($val->status)?'Active':'Deactive',
                '<a class="btn btn-sm danger-btn margin-bottom blue-btn" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->id)).'"><i class="fa fa-edit"></i> Edit</a> <button onclick="deleteDetail('.$val->id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button>'
            );
            $nCount++;
             /*<button onclick="disableDetail('.$val->id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>*/
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
}


 ?>