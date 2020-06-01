<?php
class Restaurant_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
    // method for getting all
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->session->userdata('UserType') == 'Admin'){     
            $this->db->where('created_by',$this->session->userdata('UserID'));
        }
        $result['total'] = $this->db->count_all_results('restaurant');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);   
        if($this->session->userdata('UserType') == 'Admin'){     
            $this->db->where('created_by',$this->session->userdata('UserID'));
        }
        $result['data'] = $this->db->get('restaurant')->result();        
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
        $this->db->select('res.*,res_add.address,res_add.landmark,res_add.zipcode,res_add.country,res_add.state,res_add.city,res_add.latitude,res_add.longitude');
        $this->db->join('restaurant_address as res_add','res.entity_id = res_add.resto_entity_id','left');
        $this->db->where('res.entity_id',$entity_id);
        return $this->db->get(''.$tblname.' as res')->first_row();
    }
    // delete user
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
    //get list
    public function getListData($tblname){
        $this->db->select('name,entity_id');
        $this->db->where('status',1);
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));  
        }  
        return $this->db->get($tblname)->result();
    }
    //menu grid
    public function getMenuGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){
        if($this->input->post('page_title') != ''){
            $this->db->like('menu.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('menu.status', $this->input->post('Status'));
        }
        $this->db->select('menu.name as mname,res.name as rname,menu.entity_id,menu.status');
        $this->db->join('restaurant as res','menu.restaurant_id = res.entity_id','left');
        if($this->session->userdata('UserType') == 'Admin'){     
        	$this->db->where('res.created_by',$this->session->userdata('UserID'));
        }
        $result['total'] = $this->db->count_all_results('restaurant_menu_item as menu');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('menu.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('menu.status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);  
        $this->db->select('menu.name as mname,res.name as rname,menu.entity_id,menu.status');
        $this->db->join('restaurant as res','menu.restaurant_id = res.entity_id','left');   
        if($this->session->userdata('UserType') == 'Admin'){     
        	$this->db->where('res.created_by',$this->session->userdata('UserID'));   
        }
        $result['data'] = $this->db->get('restaurant_menu_item as menu')->result();        
        return $result; 
    }
    //package grid
    public function getPackageGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10){
        if($this->input->post('page_title') != ''){
            $this->db->like('package.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('package.status', $this->input->post('Status'));
        }
        $this->db->select('package.name as mname,res.name as rname,package.entity_id,package.status');
        $this->db->join('restaurant as res','package.restaurant_id = res.entity_id','left');
        if($this->session->userdata('UserType') == 'Admin'){     
        	$this->db->where('res.created_by',$this->session->userdata('UserID'));
        }
        $result['total'] = $this->db->count_all_results('restaurant_package as package');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('package.name', $this->input->post('page_title'));
        }
        if($this->input->post('restaurant') != ''){
            $this->db->like('res.name', $this->input->post('restaurant'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('package.status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);  
        $this->db->select('package.name as mname,res.name as rname,package.entity_id,package.status');
        $this->db->join('restaurant as res','package.restaurant_id = res.entity_id','left'); 
        if($this->session->userdata('UserType') == 'Admin'){     
        	$this->db->where('res.created_by',$this->session->userdata('UserID')); 
        }    
        $result['data'] = $this->db->get('restaurant_package as package')->result();        
        return $result; 
    }
}
?>