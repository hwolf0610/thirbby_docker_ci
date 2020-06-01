<?php
class Common_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }
    //get notification count 
    public function getNotificationCount(){
        $this->db->select('order_count');
        $this->db->where('admin_id',$this->session->userdata('UserID'));
        return $this->db->get('order_notification')->first_row();
    }	
    //get country
    public function getSelectedCountry(){
        $this->db->where('OptionSlug','country');
        return $this->db->get('system_option')->first_row();
    }
    //get country
    public function getSelectedPhoneCode(){
        $this->db->where('OptionSlug','phone_code');
        return $this->db->get('system_option')->first_row();
    }
}
?>