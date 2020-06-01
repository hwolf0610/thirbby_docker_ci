<?php
class Coupon_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
      //ajax view      
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        }
        if($this->input->post('amount') != ''){
            $this->db->like('amount', $this->input->post('amount'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));
        } 
        $result['total'] = $this->db->count_all_results('coupon');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        }
        if($this->input->post('amount') != ''){
            $this->db->like('amount', $this->input->post('amount'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);     
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));  
        }  
        $result['data'] = $this->db->get('coupon')->result();        
        return $result;
    }  
    //add to db
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    //get single data
    public function getEditDetail($entity_id)
    {
        $this->db->select('c.*');
        //$this->db->join('restaurant as res','c.restaurant_id = res.entity_id','left');
        return $this->db->get_where('coupon as c',array('c.entity_id'=>$entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }
    // updating the changed status
    public function UpdatedStatus($tblname,$entity_id,$status){
        if($status==0){
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }        
        $this->db->where('entity_id',$entity_id);
        $this->db->update($tblname,$userData);
        return $this->db->affected_rows();
    }
    // delete user
    public function deleteUser($tblname,$entity_id)
    {
        $this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
    //get list
    public function getListData($tblname){
        $this->db->select('name,entity_id');
        $this->db->where('status',1);
        return $this->db->get($tblname)->result();
    }
    public function checkExist($coupon,$entity_id){
        $this->db->where('name',$coupon);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('coupon')->num_rows();
    }
}
?>