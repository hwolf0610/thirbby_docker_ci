<?php
class Users_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
    // method for getting all users
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10,$user_type)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('first_name', $this->input->post('page_title'));
        }
        if($this->input->post('phone') != ''){
            $this->db->like('mobile_number', $this->input->post('phone'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->input->post('restaurant_name') != ''){
            $this->db->like('restaurant.name', $this->input->post('restaurant_name'));
        }
        $this->db->where('user_type !=','MasterAdmin');
        if($user_type){
            $this->db->select('users.*,restaurant.name');
            $this->db->join('restaurant','users.created_by = restaurant.created_by','left');
            $this->db->where('user_type','Driver');
            $this->db->group_by('users.entity_id');
        }else{
            $this->db->where('user_type !=','Driver');
        }
        $result['total'] = $this->db->count_all_results('users');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('first_name', $this->input->post('page_title'));
        }
        if($this->input->post('phone') != ''){
            $this->db->like('mobile_number', $this->input->post('phone'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->input->post('restaurant_name') != ''){
            $this->db->like('restaurant.name', $this->input->post('restaurant_name'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart); 
        $this->db->where('user_type !=','MasterAdmin');  
        if($user_type){
            $this->db->select('users.*,restaurant.name');
            $this->db->join('restaurant','users.created_by = restaurant.created_by','left');
            $this->db->group_by('users.entity_id');
            $this->db->where('user_type','Driver');
        }else{
            $this->db->where('user_type !=','Driver');
        }     
        $result['data'] = $this->db->get('users')->result();        
        return $result;
    }		
    // method for adding users
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    // method to get user details by id
    public function getEditDetail($tblname,$entity_id)
    {
        return $this->db->get_where($tblname,array('entity_id'=>$entity_id))->first_row();
    }
    // delete user
    public function deleteUser($tblname,$entity_id)
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
	public function UpdatedStatus($entity_id,$status){
        if($status==0){
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }        
        $this->db->where('entity_id',$entity_id);
        $this->db->update('users',$userData);
        return $this->db->affected_rows();
    }
    //get users
    public function getUsers(){
        $this->db->select('first_name,last_name,entity_id');
        $this->db->where('user_type !=','MasterAdmin');       
        return $this->db->get('users')->result();
    }
    //address grid
    public function getAddressGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('u.first_name', $this->input->post('page_title'));
        }
        if($this->input->post('page_title') != ''){
            $this->db->like('u.last_name', $this->input->post('page_title'));
        }
        if($this->input->post('address') != ''){
            $this->db->like('address.address', $this->input->post('address'));
        }
        $this->db->select('address.entity_id,address.address,u.first_name,u.last_name');
        $this->db->join('users as u','address.user_entity_id = u.entity_id','left');
        $this->db->where('u.user_type !=','MasterAdmin');
        $result['total'] = $this->db->count_all_results('user_address as address');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('u.first_name', $this->input->post('page_title'));
        }
        if($this->input->post('page_title') != ''){
            $this->db->like('u.last_name', $this->input->post('page_title'));
        }
        if($this->input->post('address') != ''){
            $this->db->like('address.address', $this->input->post('address'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $this->db->select('address.entity_id,address.address,u.first_name,u.last_name');
        $this->db->join('users as u','address.user_entity_id = u.entity_id','left'); 
        $this->db->where('u.user_type !=','MasterAdmin');       
        $result['data'] = $this->db->get('user_address as address')->result();        
        return $result;
    }   
    public function checkExist($mobile_number,$entity_id){
        $this->db->where('mobile_number',$mobile_number);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('users')->num_rows();
    }
    public function checkEmailExist($email,$entity_id){
        $this->db->where('email',$email);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('users')->num_rows();
    }
    //get commission
    public function getCommissionDetail($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10,$user_id){
        if($this->input->post('name') != ''){
            $this->db->like("CONCAT(first_name,' ',last_name) like '%".$this->input->post('name')."%'");
        }
        if($this->input->post('commission_rate') != ''){
           $this->db->like('order_driver_map.driver_commission',$this->input->post('commission_rate'));
        }
        if($this->input->post('restaurant') != ''){
            $name = $this->input->post('restaurant');
            $where = "(order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*')";
            $this->db->where($where);
        }
        if($this->input->post('commission') != ''){
            $this->db->like('order_driver_map.commission', $this->input->post('commission'));
        }
        if($this->input->post('date') != ''){
            $this->db->like('order_driver_map.date', date('Y-m-d',strtotime($this->input->post('date'))));
        }
        $this->db->select('order_master.order_status,users.first_name,users.last_name,order_driver_map.commission,order_driver_map.driver_commission,order_detail.restaurant_detail,order_driver_map.commission_status,order_driver_map.driver_map_id');
        $this->db->join('users','order_driver_map.driver_id = users.entity_id','left');
        $this->db->join('order_detail','order_driver_map.order_id = order_detail.order_id','left');
        $this->db->join('order_master','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        if($this->session->userdata('UserType') == 'Admin'){     
            $this->db->where('users.entity_id',$this->session->userdata('UserID'));
        }
        $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
        $result['total'] = $this->db->count_all_results('order_driver_map');
        
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('name') != ''){
            $this->db->like("CONCAT(first_name,' ',last_name) like '%".$this->input->post('name')."%'");
        }
        if($this->input->post('commission_rate') != ''){
           $this->db->like('order_driver_map.driver_commission',$this->input->post('commission_rate'));
        }
        if($this->input->post('restaurant') != ''){
            $name = $this->input->post('restaurant');
            $where = "(order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*')";
            $this->db->where($where);
        }
        if($this->input->post('commission') != ''){
            $this->db->like('order_driver_map.commission', $this->input->post('commission'));
        }
        if($this->input->post('date') != ''){
            $this->db->like('order_driver_map.date', date('Y-m-d',strtotime($this->input->post('date'))));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $this->db->select('order_master.order_status,users.first_name,users.last_name,order_driver_map.commission,order_driver_map.driver_commission,order_driver_map.date,order_detail.restaurant_detail,order_driver_map.commission_status,order_driver_map.driver_map_id');
        $this->db->join('users','order_driver_map.driver_id = users.entity_id','left');
        $this->db->join('order_detail','order_driver_map.order_id = order_detail.order_id','left');
        $this->db->join('order_master','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        if($this->session->userdata('UserType') == 'Admin'){     
            $this->db->where('users.entity_id',$this->session->userdata('UserID'));
        }
        $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
        $result['data'] = $this->db->get('order_driver_map')->result();
        return $result;
    }
    //get commission
    public function getDriverReviewDetail($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10,$user_id){
        if($this->input->post('name') != ''){
            $this->db->like("CONCAT(first_name,' ',last_name) like '%".$this->input->post('name')."%'");
        }
        if($this->input->post('review') != ''){
            $this->db->like('review', $this->input->post('review'));
        }
        if($this->input->post('rating') != ''){
            $this->db->like('rating', $this->input->post('rating'));
        }
        if($this->input->post('date') != ''){
            $this->db->like('review.created_date', date('Y-m-d',strtotime($this->input->post('date'))));
        }
        $this->db->select('review.*');
        $this->db->join('users','review.user_id = users.entity_id','left');
        $this->db->where('review.user_id',$user_id);
        $result['total'] = $this->db->count_all_results('review');
        
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('name') != ''){
            $this->db->like("CONCAT(first_name,' ',last_name) like '%".$this->input->post('name')."%'");
        }
        if($this->input->post('review') != ''){
            $this->db->like('review', $this->input->post('review'));
        }
        if($this->input->post('rating') != ''){
            $this->db->like('rating', $this->input->post('rating'));
        }
        if($this->input->post('date') != ''){
            $this->db->like('review.created_date', date('Y-m-d',strtotime($this->input->post('date'))));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);
        $this->db->select('review.*,users.first_name,users.last_name');
        $this->db->join('users','review.user_id = users.entity_id','left');
        $this->db->where('review.user_id',$user_id);
        $result['data'] = $this->db->get('review')->result();
        return $result;
    }
    public function payCommision($driver_map_id){
        $data = array('commission_status'=>"Paid");
        $this->db->where_in('driver_map_id',$driver_map_id);
        $this->db->update('order_driver_map',$data);
        return $this->db->affected_rows();
    }
}
?>