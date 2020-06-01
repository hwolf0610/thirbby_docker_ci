<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class Home extends CI_Controller {   
  public function __construct() {
    parent::__construct();      
    $this->load->library('form_validation');
    $this->load->model(ADMIN_URL.'/home_model');    
  }
  public function index() {
    
    if ($this->session->userdata('is_admin_login')) {
      redirect(base_url().ADMIN_URL.'/dashboard');
    } else {
      $this->load->view(ADMIN_URL.'/login');
    }
  }  
  public function do_login() {
    if ($this->session->userdata('is_admin_login')) {     
      redirect(ADMIN_URL.'/dashboard');
    } else {
      if($this->session->userdata('UserID') != "")
      {
        $this->session->set_flashdata('loginError', 'Merchant already login in this browser');
        redirect(base_url().ADMIN_URL.'/home'); exit;                
      }
      else
      {          
        $user = $this->input->post('username');
        $password = $this->input->post('password');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
          $this->load->view(ADMIN_URL.'/login');
        } 
        else 
        {
          $salt = '5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
          $enc_pass  = md5($salt.$password); 
          $this->db->where('email',$user);
          $this->db->where('password',$enc_pass);
          $this->db->where("(user_type='Admin' OR user_type='MasterAdmin')");
          $val = $this->db->get('users')->first_row();  
          if(!empty($val))
          {       
            if($val->status!='0' && $val->email==$user) 
            {
              $this->session->set_userdata(
                array(
                  'UserID' => $val->entity_id,
                  'adminFirstname' => $val->first_name,                            
                  'adminLastname' => $val->last_name,                            
                  'adminemail' => $val->email,                            
                  'is_admin_login' => true,
                  'UserType' => $val->user_type,
                )
              );
                // remember ME
              $cookie_name = "adminAuth";
              if($this->input->post('rememberMe')==1){                    
                $this->input->set_cookie($cookie_name, 'usr='.$user.'&hash='.$password, 60*60*24*5); // 5 days
              } else {
                delete_cookie($cookie_name);
              }                
              redirect(base_url().ADMIN_URL.'/dashboard');
            } 
            else if($val->status=='0' && $val->email==$user)
            {                
              $data['loginError'] = $this->lang->line('login_deactivate');
              $this->load->view(ADMIN_URL.'/login', $data);
            } 
            else 
            {
              $data['loginError'] = $this->lang->line('login_error');
              $this->load->view(ADMIN_URL.'/login', $data);
            }
          }else{
            $data['loginError'] = $this->lang->line('login_error');
            $this->load->view(ADMIN_URL.'/login', $data);
          }
        }
      }
    }
  }
  public function forgotpassword(){
  // when click submit button
    if($this->input->post('Submit')=="Submit"){ 
      $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email');          
      if($this->form_validation->run()){
        $checkEx = $this->home_model->checkemailExist($this->input->post('email_address'));          
        if(!empty($checkEx))
        {
          // confirmation link
          $verificationCode = random_string('alnum', 20).$checkEx->UserID.random_string('alnum', 5);
          $confirmationLink = base_url().ADMIN_URL.'/home/newpassword/'.$verificationCode;               
          // email message body
          $forgot_password_emailBody = '<html>
          <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic" rel="stylesheet" type="text/css">
          <body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="-webkit-font-smoothing: antialiased;width:100% !important;background:#fff;-webkit-text-size-adjust:none;font-family: PT Sans, sans-serif;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff"><tr>
          <td padding-bottom:30px; border:1px solid #deecf3; width:100%; height:100%;" >
          <table width="533" cellpadding="0" cellspacing="0" border="0" align="center" class="table">
          <tr>
          <td width="533">        
          <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table">
          <tr>
          <td width="100%" class="logocell">                
          <img src="'.base_url().'assets/front/images/logo.png" alt="'.$this->lang->line('site_title').' Logo" style="-ms-interpolation-mode:bicubic; width:220px; padding-bottom:10px;"><br>              
          </td>
          </tr>
          </table>
          <table width="533" cellpadding="25" cellspacing="0" border="0">
          <tr>
          <td style="border: 1px solid #E6CD66; padding:40px 30px; box-shadow:0 2px 3px #cccccc; border-radius: 4px; background-color:#fff;">
          <p style="font-size:14px; color:#343434; margin-bottom:10px;">Hi '.$checkEx->FirstName.',</p>              
          <p style="font-size:14px; color:#343434; margin-bottom:10px;">We received a request to reset the password associated with this email address. If you made this request, please follow the instructions below.
          Click the link below to reset your password using our secure server:</p>
          <a class="account-style" style="color:#064D81; font-size:14px; text-decoration:none; margin-top: 2px; display: inline-block;" href="'.$confirmationLink.'" target="blank">'.$confirmationLink.'</a>
          <p style="font-size:14px; color:#343434; margin-bottom:0px;">If you did not request to have your password reset you can safely ignore this email. Rest assured your account is safe.</p>';
          "</td>                         
          </tr>
          </table>            
          </table>
          </body>
          </html>";          
         //get System Option Data
          $this->db->select('OptionValue');
          $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

          $this->db->select('OptionValue');
          $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();

          $subject = sprintf($this->lang->line('forgotpassword_email_subject'),$this->lang->line('site_title'));          
          $this->load->library('email');  
          $config['charset'] = "utf-8";
          $config['mailtype'] = "html";
          $config['newline'] = "\r\n";      
          $this->email->initialize($config);  
          $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
          $this->email->to($this->input->post('email_address'));      
          $this->email->subject($subject);  
          $this->email->message($forgot_password_emailBody);            
          $this->email->send();          
          // update verification code
          $this->home_model->updateVerificationCode($this->input->post('email_address'),$verificationCode,$checkEx->entity_id);
          redirect(base_url().ADMIN_URL.'/home/forgotpasswordsent');
          exit();
        }else{
          $this->session->set_flashdata('emailNotExist', $this->lang->line('email_not_exist'));
          redirect(base_url().ADMIN_URL.'/home/forgotpassword'); 
        }
      }
    }
    
            
    $arr['meta_title'] = $this->lang->line('title_merchant_fogottPass').' | '.$this->lang->line('site_title');
    $arr['captchaData'] = '';
    $this->load->view(ADMIN_URL.'/forgot_password',$arr);  
  }

// verify (unique code) when reach from mail
  public function newPassword($verificationCode=NULL,$whichone = NULL) {  

// when click submit button
    if($this->input->post('submit')=="Submit"){
      $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[8]');  
      $this->form_validation->set_rules('confirm_pass', 'Confirm Password', 'trim|required|min_length[8]|matches[password]');   
      $salt = '5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
      $enc_pass  = md5($salt.$this->input->post('password'));
      if ($this->form_validation->run())
      {
          $changePswData = array(        
           'password'  => $enc_pass
         );
        $this->home_model->updateData($changePswData,'users',$this->input->post('verificationCode')); // data,table,where
        $this->session->set_flashdata('PasswordChange', $this->lang->line('success_password_change'));        
        redirect(base_url().ADMIN_URL);
        exit();
      }
    }
    $chkverify = $this->home_model->forgotEmailVerify($verificationCode);
    if(!empty($chkverify)){
      $arr['verificationCode'] = $verificationCode;
      $arr['meta_title'] = $this->lang->line('title_newpassword').' | '.$this->lang->line('site_title');
      $this->load->view(ADMIN_URL.'/reset_password',$arr); 
    }else{
      $this->session->set_flashdata('verifyerr', $this->lang->line('invalid_url_verify'));
      redirect(base_url().ADMIN_URL.'/not_found'); 
    }
  }
  public function forgotpasswordsent(){
    $this->load->view(ADMIN_URL.'/forgot_passwordsent'); 
  }  
  public function logout() {
    $this->session->unset_userdata('UserID');
    $this->session->unset_userdata('adminFirstname');
    $this->session->unset_userdata('adminLastname');
    $this->session->unset_userdata('adminemail');
    $this->session->unset_userdata('is_admin_login');  
    $this->session->unset_userdata('UserType');  
    $this->session->sess_destroy();
    $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
    $this->output->set_header("Pragma: no-cache");
    redirect(base_url().ADMIN_URL.'/home', 'refresh');
  }
  public function not_found()
  {
    $this->load->view('error_404');
  }
// verify account
  public function accountactivate($verificationText=NULL) {     
    $noOfRecords = $this->home_model->verifyEmailAddress($verificationText);
    if($noOfRecords > 0){
      $this->session->set_flashdata('UserVerifySuccess', $this->lang->line('account_activated'));             
      redirect(base_url().ADMIN_URL);
    }else{
      $this->session->set_flashdata('UserVerifyError', $this->lang->line('invalid_url_verify'));          
      redirect(base_url().ADMIN_URL);
    }
  }
}