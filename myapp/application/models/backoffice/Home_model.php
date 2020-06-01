<?php
class Home_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }	
    public function checkemailExist($email){   
        $this->db->where('email',$email); 	    	
        $customWhere = "(user_type='Admin' OR user_type='MasterAdmin')";
        $this->db->where($customWhere);            
    	return $this->db->get_where('users')->first_row();
    } 
    public function updatePassword($emailaddress,$updpsw)  
    {  
        $salt = '5&JDDlwz%Rwh!t2Yg-Igae@QxPzFTSId';
        $enc_pass  = md5($salt.$updpsw);    	    	    	
        $this->db->set('password', $enc_pass)  
            ->where('email', $emailaddress)
            ->update('users');  
        return $this->db->affected_rows();  
    } 	
    public function updateVerificationCode($emailaddress,$verificationCode,$UserID)  
    {  
        $this->db->set('email_verification_code', $verificationCode)  
            ->where('email', $emailaddress)
            ->where('entity_id',$UserID)
            ->update('users');
        return $this->db->affected_rows();  
    } 
    public function forgotEmailVerify($verificationCode)  
    {       
        return $this->db->get_where('users',array('email_verification_code'=>$verificationCode))->first_row();
    }
    public function updateData($data,$table,$where){
        $this->db->where('email_verification_code',$where);
        $this->db->update($table, $data);
    }
    public function verifyEmailAddress($verificationCode)
    {
        $this->db->set(array('status'=> 1,'email_verification_code'=>''))  
        ->where('email_verification_code', $verificationCode)  
        ->update('users');  
        return $this->db->affected_rows();  
    }
}   
?>