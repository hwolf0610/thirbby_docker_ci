<?php
class Notification_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }       
    public function getNotificationList($searchTitleName = '', $sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('notification_title') != ''){
            $this->db->like('notification_title', $this->input->post('notification_title'));
        }
        $result['total'] = $this->db->count_all_results('notifications');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('notification_title') != ''){
            $this->db->like('notification_title', $this->input->post('notification_title'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);        
        $result['data'] = $this->db->get('notifications')->result();        
        return $result;
    }  
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    public function getEditNotificationDetail($entity_id)
    {
        return $this->db->get_where('notifications',array('entity_id'=>$entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }  
    public function deleteRecord($entity_id){          
        $this->db->where('entity_id',$entity_id);
        $this->db->delete('notifications');
        return $this->db->affected_rows();
    }

    // Get user for notification
    public function getUserNotification()
    {
        $this->db->select('entity_id, first_name, last_name, device_id');
        return $this->db->get_where('users',array('active'=>1,'status'=>1,'user_type'=>'User','notification'=>1))->result();
    }
    // Get DeviceID
    public function getUserDevices($userids)
    {
        $this->db->select('device_id');
        $this->db->where_in('entity_id',$userids);
        $this->db->where('users.status',1); // ACTIVE
        $this->db->where('users.notification',1);
        return $this->db->get('users')->result_array();
    }
    public function addRecordBatch($table,$data)
    {
        return $this->db->insert_batch($table, $data);
    }
}
?>