<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Coupon extends CI_Controller { 
    public $module_name = 'Coupon';
    public $controller_name = 'coupon';
    public $prefix = '_cpn';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/coupon_model');
    }
    // view coupon
    public function view(){
    	$data['meta_title'] = $this->lang->line('title_admin_coupon').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/coupon',$data);
    }
    // add coupon
    public function add(){
        $data['meta_title'] = $this->lang->line('title_admin_couponadd').' | '.$this->lang->line('site_title');
    	if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Coupon Name', 'trim|callback_checkExist');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('amount_type','Amount type', 'trim|required');
            $this->form_validation->set_rules('amount','Amount', 'trim|required');
            $this->form_validation->set_rules('max_amount','Max Amount', 'trim|required');
            $this->form_validation->set_rules('start_date','Start Date', 'trim|required');
            $this->form_validation->set_rules('end_date','End Date', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $add_data = array(  
                    'restaurant_id' => $this->input->post('restaurant_id'), 
                	'name' => $this->input->post('name'),                 
                    'description'=>$this->input->post('description'),
                    'amount_type' =>$this->input->post('amount_type'),
                    'amount' =>$this->input->post('amount'),
                    'max_amount' =>$this->input->post('max_amount'),
                    'start_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))),
                    'end_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),
                    'status' =>1,
                    'created_by'=>$this->session->userdata("UserID")
                );  

                $this->coupon_model->addData('coupon',$add_data);
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');                 
            }
        }
        $data['restaurant'] = $this->coupon_model->getListData('restaurant');
    	$this->load->view(ADMIN_URL.'/coupon_add',$data);
    }
    // edit coupon
    public function edit(){
    	$data['meta_title'] = $this->lang->line('title_admin_couponedit').' | '.$this->lang->line('site_title');
        // check if form is submitted 
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Coupon Name', 'trim|callback_checkExist');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('amount_type','Amount type', 'trim|required');
            $this->form_validation->set_rules('amount','Amount', 'trim|required');
            $this->form_validation->set_rules('max_amount','Max Amount', 'trim|required');
            $this->form_validation->set_rules('start_date','Start Date', 'trim|required');
            $this->form_validation->set_rules('end_date','End Date', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $edit_data = array(  
                    'restaurant_id' => $this->input->post('restaurant_id'), 
                    'name' => $this->input->post('name'),                 
                    'description'=>$this->input->post('description'),
                    'amount_type' =>$this->input->post('amount_type'),
                    'amount' =>$this->input->post('amount'),
                    'max_amount' =>$this->input->post('max_amount'),
                    'start_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))),
                    'end_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),
                    'updated_date'=>date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('UserID')
                );                                             
                $this->coupon_model->updateData($edit_data,'coupon','entity_id',$this->input->post('entity_id')); 
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');                 
            }
        }      
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->coupon_model->getEditDetail($entity_id);
        $data['restaurant'] = $this->coupon_model->getListData('restaurant');
        $this->load->view(ADMIN_URL.'/coupon_add',$data);
    }
   
    //ajax view
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'name',2=>'amount',3=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->coupon_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->name,
                $val->amount,
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
    // method to change coupon status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->coupon_model->UpdatedStatus('coupon',$entity_id,$this->input->post('status'));
        }
    }
    // method for deleting a coupon
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->coupon_model->deleteUser('coupon',$entity_id);
    }
    public function checkExist(){
        $coupon = ($this->input->post('coupon') != '')?$this->input->post('coupon'):'';
        if($this->input->post('amount')){
            if($coupon != ''){
                $check = $this->coupon_model->checkExist($coupon,$this->input->post('entity_id'));
                if($check > 0){
                    $this->form_validation->set_message('checkExist', 'Coupon is already exist!');
                    return false;
                }
            } 
        }else{
            if($coupon != ''){
                $check = $this->coupon_model->checkExist($coupon,$this->input->post('entity_id'));
                echo $check;
            } 
        }
       
    }
}


 ?>