<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller { 
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('user_model');                
        $this->load->library('form_validation');
    }
    public function reset($verificationCode=NULL)
    {
	    if($this->input->post('submit') == "Submit"){
	        $this->form_validation->set_rules('password','Password','trim|required');
	        $this->form_validation->set_rules('confirm_pass','confirm password','trim|required|matches[password]');
	        if($this->form_validation->run())
	        {
	          $salt = '5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
	          $enc_pass  = md5($salt.$this->input->post('password'));
	          $updatePassword = array(
	              'Password' => $enc_pass 
	          );
	          $Detail = $this->user_model->updatePassword($updatePassword,$this->input->post('verificationCode'));
	          $this->session->set_flashdata('PasswordChange', $this->lang->line('success_password_change'));        
	          redirect(base_url()."user/thankYou");
	          exit();
	        }
	    }
	    if($verificationCode){
		    $chkverify = $this->user_model->forgotpassowrdVerify($verificationCode); 
		    if(!empty($chkverify)){
		        $data['verificationCode'] = $verificationCode;
		        $data['MetaTitle'] = $this->lang->line('title_newpassword').' | '.$this->lang->line('site_title');
		        $this->load->view('reset_password',$data); 
		    }else{
		        $this->session->set_flashdata('verifyerr', $this->lang->line('invalid_url_verify'));
		        redirect(base_url().'user/reset'); 
		    }
		}
	}
	//cron job for expiry date
	public function expireAccout(){
		$where = date('Y-m-d');
        $this->db->select('entity_id');
        $this->db->where('end_date <= ',$where);
        $arrData =  $this->db->get('coupon')->result();
        if(!empty($arrData)){
        	foreach ($arrData as $key => $value) {
        		$this->db->set('status',0)->where('entity_id',$value->entity_id)->update('coupon');
        	}
        }
	}
	public function thankYou(){
		$data['MetaTitle'] = 'Thank You | '.$this->lang->line('site_title');
		$this->load->view('thank_you',$data); 
	}
}