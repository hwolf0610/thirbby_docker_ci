<?php
class Slider_image_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		
    }   
    // method for adding data
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
    // updating the changed status of user
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
    // delete slider image
    public function ajaxDelete($tblname,$entity_id)
    {
        $this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
    //method to get Slider Images details by id
    public function getEditDetail($entity_id)
    {
        return $this->db->get_where('slider_image',array('entity_id'=>$entity_id))->first_row();
    }
    // method for getting all Slider Images
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        $result['total'] = $this->db->count_all_results('slider_image');

        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);

        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }     
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);        
        $result['data'] = $this->db->get('slider_image')->result();        
        return $result;
    }    
}
?>