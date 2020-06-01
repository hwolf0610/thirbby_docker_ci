<?php
class Branch_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
    // method for getting all
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('res.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('resta.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('res.status', $this->input->post('Status'));
        }
        $this->db->select('res.*,resta.name as rname,resta.entity_id as id');
        $this->db->join('restaurant as resta','res.entity_id = resta.branch_entity_id','left');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('res.created_by',$this->session->userdata('UserID'));
        }
        $result['total'] = $this->db->count_all_results('restaurant as res');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
         if($this->input->post('page_title') != ''){
            $this->db->like('res.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('resta.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('res.status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);    
        $this->db->select('res.*,resta.name as rname,resta.entity_id as id');
        $this->db->join('restaurant as resta','res.entity_id = resta.branch_entity_id AND resta.branch_entity_id != 0'); 
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('res.created_by',$this->session->userdata('UserID'));
        }
        $result['data'] = $this->db->get('restaurant as res')->result();
        return $result;
    }   	
    // method for adding
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    // method to get details by id
    public function getEditDetail($tblname,$entity_id)
    {
        $this->db->select('res.*,res_add.*');
        $this->db->join('restaurant_address as res_add','res.entity_id = res_add.resto_entity_id','left');
        $this->db->where('res.entity_id',$entity_id);
        return $this->db->get(''.$tblname.' as res')->first_row();
    }
    // delete
    public function ajaxDelete($tblname,$entity_id)
    {
        $this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }
     //get list
    public function getListData($tblname){
        $this->db->select('name,entity_id');
        $this->db->where('status',1);
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));  
        } 
        return $this->db->get($tblname)->result();
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
}
?>