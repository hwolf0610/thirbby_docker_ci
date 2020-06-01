<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Review extends CI_Controller { 
    public $module_name = 'Review';
    public $controller_name = 'review';
    public $prefix = '_rw'; 
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/review_model');
    }
    // view review
    public function view(){
    	$data['meta_title'] = $this->lang->line('title_admin_review').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/review',$data);
    }
    // add review
    public function add(){
        $data['meta_title'] = $this->lang->line('title_admin_reviewadd').' | '.$this->lang->line('site_title');
    	/*if($this->input->post('submitUSERPage') == "Submit")
        {
            $this->form_validation->set_rules('FirstName', 'FirstName', 'trim|required|alpha');
            $this->form_validation->set_rules('LastName', 'LastName', 'trim|required|alpha');
            $this->form_validation->set_rules('Email','Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('Phone','Phone', 'trim|required|exact_length[10]|numeric');
            $this->form_validation->set_rules('AddressLine1','AddressLine1', 'trim|required');
            $this->form_validation->set_rules('AddressLine2','AddressLine2', 'trim|required');
            $this->form_validation->set_rules('Landmark','Landmark', 'trim|required');
            $this->form_validation->set_rules('ZipCode','ZipCode', 'trim|required');
            $this->form_validation->set_rules('CountryID','CountryID', 'required');
            $this->form_validation->set_rules('StateID','StateID', 'required');
            $this->form_validation->set_rules('CityID','CityID', 'required');
            $this->form_validation->set_rules('EmergencyContactName','EmergencyContactName', 'trim|required|alpha');
            $this->form_validation->set_rules('EmergencyContactNumber','EmergencyContactNumber', 'trim|required|exact_length[10]|numeric');
            $this->form_validation->set_rules('BloodGroup','BloodGroup', 'trim|required');
            $this->form_validation->set_rules('Password','Password', 'trim|required');
            $this->form_validation->set_rules('ConfirmPassword','ConfirmPassword', 'trim|required|matches[Password]');
            $this->form_validation->set_rules('User_Image','User_Image', 'trim|xss_clean');

            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
        		$default_status = "Active";
        		$userType = "User";
        		$Email = $this->input->post('Email');
                $addUSERData = array(  
                	'UserID' => $this->input->post('UserID'),                 
                    'FirstName'=>$this->input->post('FirstName'),
                    'LastName' =>$this->input->post('LastName'),
                    'Email' =>$this->input->post('Email'),
                    'Phone' =>$this->input->post('Phone'),
                    'UserType' =>$userType,
                    'Status' =>$default_status,
                    'Password' =>$this->input->post('Password'),
                    'EmergencyContactName' =>$this->input->post('EmergencyContactName'),
                    'EmergencyContactNumber' =>$this->input->post('EmergencyContactNumber'),
                    'BloodGroup' =>$this->input->post('BloodGroup'),
                    'CreatedBy'=>$this->session->userdata("adminID")
                );                                           
                $this->review_model->addData('review',$addUSERData);
                $result = $this->review_model->readUserDetail($Email);
                
                $userAddress = array(
        			'UserID' => $result[0]->UserID,
        			'AddressLine1' => $this->input->post('AddressLine1'),
        			'AddressLine2' => $this->input->post('AddressLine2'),
        			'Landmark' => $this->input->post('Landmark'),
        			'ZipCode' => $this->input->post('ZipCode'),
        			'CountryID' => $this->input->post('CountryID'),
        			'StateID' => $this->input->post('StateID'),
        			'CityID' => $this->input->post('CityID'),
        			'CreatedBy'=>$this->session->userdata("adminID")
        		);
                $this->review_model->addData('user_addresses',$userAddress);
                $this->session->set_flashdata('userPageMSG', $this->lang->line('success_add'));
                redirect(base_url().'admin/review/view');                 
            }
        }*/
    	$this->load->view(ADMIN_URL.'/review_add',$data);
    }
    // edit review
    public function edit(){
    	$data['meta_title'] = $this->lang->line('title_admin_reviewedit').' | '.$this->lang->line('site_title');
        // check if form is submitted 
       /* if($this->input->post('submitUSERPage') == "Submit")
        {
            $this->form_validation->set_rules('FirstName', 'FirstName', 'trim|required|alpha');
            $this->form_validation->set_rules('LastName', 'LastName', 'trim|required|alpha');
            $this->form_validation->set_rules('Email','Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('Phone','Phone', 'trim|required|exact_length[10]|numeric');
            $this->form_validation->set_rules('AddressLine1','AddressLine1', 'trim|required');
            $this->form_validation->set_rules('AddressLine2','AddressLine2', 'trim|required');
            $this->form_validation->set_rules('Landmark','Landmark', 'trim|required');
            $this->form_validation->set_rules('ZipCode','ZipCode', 'trim|required');
            $this->form_validation->set_rules('CountryID','CountryID', 'required');
            $this->form_validation->set_rules('StateID','StateID', 'required');
            $this->form_validation->set_rules('CityID','CityID', 'required');
            $this->form_validation->set_rules('EmergencyContactName','EmergencyContactName', 'trim|required|alpha');
            $this->form_validation->set_rules('EmergencyContactNumber','EmergencyContactNumber', 'trim|required|exact_length[10]|numeric');
            $this->form_validation->set_rules('BloodGroup','BloodGroup', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {	
            	$Email = $this->input->post('Email');
                $editUSERData = array(
                  	'UserID' => $this->input->post('UserID'),                 
	                'FirstName'=>$this->input->post('FirstName'),
	                'LastName' =>$this->input->post('LastName'),
	                'Email' =>$this->input->post('Email'),
	                'Phone' =>$this->input->post('Phone'),
	                'Password' =>$this->input->post('Password'),
	                'EmergencyContactName' =>$this->input->post('EmergencyContactName'),
	                'EmergencyContactNumber' =>$this->input->post('EmergencyContactNumber'),
	                'BloodGroup' =>$this->input->post('BloodGroup'),
                 	'UpdatedBy'=>$this->session->userdata("adminID"),
                  	'UpdatedDate'=>date('Y-m-d h:i:s')
                );
                $this->review_model->updateData($editUSERData,'review','UserID',$this->input->post('UserID'));
                $result = $this->review_model->readUserDetail($Email);
                $editUSERadd = array(
        			'UserID' => $result[0]->UserID,
        			'AddressLine1' => $this->input->post('AddressLine1'),
        			'AddressLine2' => $this->input->post('AddressLine2'),
        			'Landmark' => $this->input->post('Landmark'),
        			'ZipCode' => $this->input->post('ZipCode'),
        			'CountryID' => $this->input->post('CountryID'),
        			'StateID' => $this->input->post('StateID'),
        			'CityID' => $this->input->post('CityID'),
        			'UpdatedBy'=>$this->session->userdata("adminID"),
                  	'UpdatedDate'=>date('Y-m-d h:i:s')
        		);
                $this->review_model->updateData($editUSERadd,'user_addresses','UserID',$this->input->post('UserID'));
                $this->session->set_flashdata('userPageMSG', $this->lang->line('success_update'));
                redirect(base_url().'admin/review/view');                 
            }
        }        
        $UserID = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('UserID');
        $data['editUSERDetail'] = $this->review_model->view_user_details($UserID);*/
        $this->load->view(ADMIN_URL.'/review_add',$data);
    }
   //ajax view
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'res.name','2'=>'review',3=>'rating',4=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->review_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->rname,
                $val->review,
                $val->rating,
                ($val->status)?'Active':'Deactive',
                '-'
                /*'<button onclick="deleteDetail('.$val->entity_id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> Delete</button> <button onclick="disableDetail('.$val->entity_id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>'*/
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
            $this->review_model->UpdatedStatus('review',$entity_id,$this->input->post('status'));
        }
    }
    // method for deleting
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->review_model->ajaxDelete('review',$entity_id);
    }
}


 ?>