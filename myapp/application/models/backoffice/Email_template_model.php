<?php
class email_template_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }
    function addData($addEmaildata)
    {
        $this->db->insert('email_template',$addEmaildata);
        return $this->db->insert_id();
    }
     // method for getting all
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('title') != ''){
            $this->db->like('title', $this->input->post('title'));
        }
        if($this->input->post('subject') != ''){
            $this->db->like('subject', $this->input->post('subject'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        $result['total'] = $this->db->count_all_results('email_template');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('title') != ''){
            $this->db->like('title', $this->input->post('title'));
        }
        if($this->input->post('subject') != ''){
            $this->db->like('subject', $this->input->post('subject'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);    
        $result['data'] = $this->db->get('email_template')->result();
        return $result;
    }   
    function getEditDetail($entity_id)
    {
        return $this->db->get_where('email_template',array('entity_id'=>$entity_id))->first_row();
    }
    function editDetail($editData,$entity_id)
    {
        $this->db->where('entity_id',$entity_id);
        $this->db->update('email_template',$editData);
        return $this->db->affected_rows();        
    }
    function UpdatedStatus($tblname,$entity_id,$Status){
        if($Status==0){
            $emailData = array('status' => 1);
        } else {
            $emailData = array('status' => 0);
        }        
        $this->db->where('entity_id',$entity_id);
        $this->db->update($tblname,$emailData);
        return $this->db->affected_rows();
    }
    function ajaxDelete($tblname,$entity_id){        
        $this->db->where('entity_id',$entity_id);
        $this->db->delete($tblname);        
    }
}
?>