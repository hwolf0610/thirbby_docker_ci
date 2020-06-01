<?php
class Review_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
    // method for getting all users
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->input->post('review') != ''){
            $this->db->like('review', $this->input->post('review'));
        }
        if($this->input->post('rating') != ''){
            $this->db->like('rating', $this->input->post('rating'));
        }
        $this->db->select('review.*,res.name as rname');
        $this->db->join('restaurant as res','review.restaurant_id = res.entity_id','left'); 
        $this->db->where('review.restaurant_id !=', '');     
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('res.created_by',$this->session->userdata('UserID'));      
        }
        $result['total'] = $this->db->count_all_results('review');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->input->post('review') != ''){
            $this->db->like('review', $this->input->post('review'));
        }
        if($this->input->post('rating') != ''){
            $this->db->like('rating', $this->input->post('rating'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart); 
        $this->db->select('review.*,res.name as rname');
        $this->db->join('restaurant as res','review.restaurant_id = res.entity_id','left');      
        $this->db->where('review.restaurant_id !=', '');     
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('res.created_by',$this->session->userdata('UserID'));  
        }
        $result['data'] = $this->db->get('review')->result();        
        return $result;
    }		
    // method for adding users
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }
     //get single data
    public function getEditDetail($entity_id)
    {
        return $this->db->get_where('review',array('entity_id'=>$entity_id))->first_row();
    }
     // updating the changed
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
    // delete
    public function ajaxDelete($tblname,$entity_id)
    {
        $this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
}
?>