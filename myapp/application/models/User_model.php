<?php
class User_model extends CI_Model {
    function __construct()
    {
        parent::__construct();        
    }
    public function forgotpassowrdVerify($verificationCode){
        return $this->db->get_where('users',array('ActiveCode'=>$verificationCode))->first_row();
    }
    //Update password
    public function updatePassword($updatePassword,$verificationCode)
    {
        $this->db->where('ActiveCode',$verificationCode);
        $this->db->update('users',$updatePassword);
        
        $this->db->select('users.Password,users.Email');
        $this->db->where('ActiveCode',$verificationCode);
        return $this->db->get('users')->first_row();
    }
}