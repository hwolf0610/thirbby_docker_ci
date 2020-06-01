<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Myprofile extends CI_Controller {    	
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/myprofile_model');
    }        
    public function getUserProfile() {
        $data['meta_title'] = $this->lang->line('title_admin_myprofile').' | '.$this->lang->line('site_title');
        if($this->input->post('submitEditUser') == "Submit")
        {   
          $this->form_validation->set_rules('FirstName', 'First Name', 'trim|required');
          $this->form_validation->set_rules('LastName', 'Last Name', 'trim|required');                        
          $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email');            
          //check form validation using codeigniter
          if ($this->form_validation->run())
          {
              $updateUserData = array(                  
                'FirstName' =>$this->input->post('FirstName'),
                'LastName' =>$this->input->post('LastName'),                  
                'Email' =>$this->input->post('Email'),                  
                'Phone' =>$this->input->post('Phone'),
                'Address' =>$this->input->post('Address'),                  
                'UpdatedBy'=>$this->session->userdata("adminID"),
                'UpdatedDate'=>date('Y-m-d H:i:s')
              );                                 
              $this->myprofile_model->updateUserModel($updateUserData,$this->input->post('UserID'));                 
              $this->session->set_flashdata('myProfileMSG', $this->lang->line('success_update'));                  
              redirect(base_url().ADMIN_URL."/myprofile/getUserProfile");                  
          }            
        }
        if($this->input->post('ChangePassword') == "Submit")
        {
            $data['selected_tab'] = "ChangePassword";
            $this->form_validation->set_rules('Newpass', 'New Password', 'trim|required|min_length[8]');
            $this->form_validation->set_rules('confirmPass', 'Confirm Password', 'trim|required|min_length[8]|matches[Newpass]');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
              $salt = '5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
              $newEncryptPass  = md5($salt.$this->input->post('Newpass'));
              $updateUserPassData = array(
                'Password' =>$newEncryptPass,
                'UpdatedBy'=>$this->session->userdata("adminID"),
                'UpdatedDate'=>date('Y-m-d H:i:s')
              );
              $this->myprofile_model->updateUserModel($updateUserPassData,$this->input->post('UserID'));                
              $this->session->set_flashdata('myProfileMSG', $this->lang->line('success_update'));
              redirect(base_url().ADMIN_URL."/myprofile/getUserProfile"); 
            }
        }        
        $UserID = ($this->session->userdata("adminID"))?$this->session->userdata("adminID"):$this->input->post('UserID');                        
        $data['editUserDetail'] = $this->myprofile_model->getEditUserDetail($UserID);
        $this->load->view(ADMIN_URL.'/myprofile_edit',$data);
    }
    public function checkEmailExist()
    { 
      $chkEmail = $this->myprofile_model->CheckExists($this->input->post('email'),$this->input->post('UserID'));
      echo $chkEmail;
    }            
}