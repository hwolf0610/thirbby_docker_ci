<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Email_template extends CI_Controller { 
    public $module_name = 'Email Template';
    public $controller_name = 'email_template';
    public $prefix = '_email';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/email_template_model');
    }
    public function view() {
        $data['meta_title'] = $this->lang->line('titleadmin_email_template').' | '.$this->lang->line('site_title');        
        $this->load->view(ADMIN_URL.'/email_template',$data);
    }
    public function add() {
        $data['meta_title'] = $this->lang->line('titleadmin_email_template_add').' | '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {
                $add_data = array(
                  'title'=>$this->input->post('title'),
                  'email_slug'=>slugify($this->input->post('title'),'email_template','email_slug'),
                  'subject'=>$this->input->post('subject'),
                  'message'=>$this->input->post('message'),                  
                  'status'=>1
                );                    
                $this->email_template_model->addData($add_data);
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/email_template/view');                     
            }
        }
        $this->load->view(ADMIN_URL.'/email_template_add',$data);
    }
    public function edit() {
        $data['meta_title'] = $this->lang->line('titleadmin_email_template_edit').' | '.$this->lang->line('site_title');
        //check add role form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {
                $edit_data = array(
                   'title'=>$this->input->post('title'),
                   'subject'=>$this->input->post('subject'),
                   'message'=>$this->input->post('message'),                          
                );                    
                $this->email_template_model->editDetail($edit_data,$this->input->post('entity_id'));
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                redirect(base_url().ADMIN_URL.'/email_template/view'); 
               
            }
        }
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->email_template_model->getEditDetail($entity_id);
        $this->load->view(ADMIN_URL.'/email_template_add',$data);
    }
   // call for ajax data
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'title',2=>'subject',3=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->email_template_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->title,
                $val->subject,
                ($val->status)?'Active':'Deactive',
                '<a class="btn btn-sm danger-btn margin-bottom blue-btn" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> Edit</a> <button onclick="deleteDetail('.$val->entity_id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button> <button onclick="disableDetail('.$val->entity_id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>'
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    // method to change status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->email_template_model->UpdatedStatus('email_template',$entity_id,$this->input->post('status'));
        }
    }
    // method for delete
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->email_template_model->ajaxDelete('email_template',$entity_id);
    }
    public function templateVariables(){
        $data['MetaTitle'] = $this->lang->line('titleadmin_email_template').' | '.$this->lang->line('site_title');        
        $this->load->view(ADMIN_URL.'/email_template_variable',$data);        
    }

}
