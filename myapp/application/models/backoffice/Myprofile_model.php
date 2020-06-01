<?php
class Myprofile_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();      
    }            
    public function getEditUserDetail($UserID)
    {
        return $this->db->get_where('users',array('UserID'=>$UserID))->first_row();
    }
    public function updateUserModel($UserData,$UserID)
    {        
        $this->db->where('UserID',$UserID);
        $this->db->update('users',$UserData);            
        return $this->db->affected_rows();
    }
    public function CheckExists($Email,$UserID=NULL)
    {
        $this->db->where('Email',$Email);
        $this->db->where('UserID !=',$UserID);
        return $this->db->get('users')->num_rows();        
    }
}
?>