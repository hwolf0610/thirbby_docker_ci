<?php
class Dashboard_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }
    public function getRestaurantCount()
    {
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
        }
    	return $this->db->get('restaurant')->num_rows();
    }
    //get restaurant
    public function restaurant(){
        $this->db->select('entity_id, name,phone_number,email');
        $this->db->order_by('entity_id','desc');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID')); 
        }
        $this->db->limit(5, 0);
        return $this->db->get('restaurant')->result(); 
    }	
    //get total user account
    public function gettotalAccount()
    {
        //get user list
        $this->db->select('entity_id, first_name, last_name, device_id');
        $data['users'] = $this->db->get_where('users',array('status'=>1,'user_type'=>'User'))->result();
        //get count
        $this->db->where('user_type !=','MasterAdmin');
    	$data['user_count'] =  $this->db->get('users')->num_rows();
        return $data;
    }	
    //get order count
    public function getOrderCount()
    {
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->select('o.total_rate as rate,o.order_date,o.order_status as ostatus,o.status,o.entity_id as entity_id,u.first_name as fname,u.last_name as lname');
            $this->db->join('users as u','o.user_id = u.entity_id','left');
            $this->db->join('restaurant','o.restaurant_id = restaurant.entity_id');
            $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
            return $this->db->get('order_master as o')->num_rows();
        }else{
            return $this->db->get('order_master')->num_rows();
        }
    }
    //get last orders
    public function getLastOrders(){
        $this->db->select('o.total_rate as rate,o.order_date,o.order_status as ostatus,o.status,o.entity_id as entity_id,u.first_name as fname,u.last_name as lname');
        $this->db->join('users as u','o.user_id = u.entity_id','left');
        $this->db->join('restaurant','o.restaurant_id = restaurant.entity_id');
        $this->db->order_by('o.entity_id','desc');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
        } 
        $this->db->limit(5);
        return $this->db->get('order_master as o')->result(); 
    }	
    //get notification
    public function ajaxNotification(){
        //get last orders
        $this->db->select('order_master.entity_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
        }
        $this->db->where('DATE(order_master.created_date)',date('Y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('order_master.entity_id','desc');
        $count = $this->db->get('order_master')->first_row();
        //get notification count
        $this->db->select('last_order_id,order_count,view_status,date');
        $this->db->where('admin_id',$this->session->userdata('UserID'));
        $last_order = $this->db->get('order_notification')->first_row();

        $date = date('Y-m-d');
        if(!empty($count) && (!empty($last_order) && $last_order->last_order_id == 0)){
            $data = array('last_order_id'=>$count->entity_id,'order_count'=>count($count),'date'=>$date);
            $this->db->where('admin_id',$this->session->userdata('UserID'));
            $this->db->update('order_notification',$data);
        }else if(!empty($count) && empty($last_order)){
            $this->db->select('order_master.entity_id as order_count');
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
            if($this->session->userdata('UserType') == 'Admin'){
                $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
            }
            $this->db->where('DATE(order_master.created_date)',date('Y-m-d'));
            $arrayData = $this->db->get('order_master')->num_rows();
            
            $data = array(
                'last_order_id'=>$count->entity_id,
                'order_count'=>$arrayData,
                'date'=>$date,
                'view_status'=>0,
                'admin_id'=>$this->session->userdata('UserID')
            );
            $this->db->insert('order_notification',$data);
            $this->db->insert_id();
        }
        if(!empty($count) && !empty($last_order)){
            $order_count = 0;
            $this->db->select('order_master.entity_id as order_count');
            $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
            if($this->session->userdata('UserType') == 'Admin'){
                $this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
            }
            $this->db->where('order_master.entity_id >', $last_order->last_order_id);
            $this->db->where('order_master.entity_id <=', $count->entity_id);
            $this->db->where('DATE(order_master.created_date)',date('Y-m-d'));
            $arrayData = $this->db->get('order_master')->num_rows();
            if(!empty($arrayData)){
                $order_count = $arrayData;
            }
            if($count->entity_id != $last_order->last_order_id && $last_order->last_order_id != 0){
                if($last_order->view_status == 0){
                    $order_count = $order_count + $last_order->order_count;
                    $date = ($last_order->date == date('Y-m-d'))?$last_order->date:date('Y-m-d');
                    $data = array('order_count'=>$order_count,'view_status'=>0,'date'=>$date,'last_order_id'=>$count->entity_id);
                }else if($last_order->view_status == 1){
                    $date = ($last_order->date == date('Y-m-d'))?$last_order->date:date('Y-m-d');
                    $data = array('order_count'=>$order_count,'view_status'=>0,'date'=>$date,'last_order_id'=>$count->entity_id);
                }
                $this->db->where('admin_id',$this->session->userdata('UserID'));
                $this->db->update('order_notification',$data);
            }
        }
        $this->db->select('order_count');
        $this->db->where('date',date('Y-m-d'));
        $this->db->where('admin_id',$this->session->userdata('UserID'));
        return $this->db->get('order_notification')->first_row();
    }
    //change view status
    public function changeViewStatus(){
        $data = array('order_count'=>0,'view_status'=>1);
        $this->db->where('admin_id',$this->session->userdata('UserID'));
        $this->db->update('order_notification',$data);
    }	
    //get notification count 
    public function getNotificationCount(){
        $this->db->select('order_count');
        $this->db->where('admin_id',$this->session->userdata('UserID'));
        return $this->db->get('order_notification')->first_row();
    }	
    //get user detail
    public function getUserEmail($user_id){
        $this->db->select('email');
        $this->db->where('entity_id',$user_id);
        return $this->db->get('users')->first_row();
    }
    //get email template
    public function getEmailTempate(){
        $this->db->select('entity_id,title');
        $this->db->where('status',1);
        return $this->db->get('email_template')->result();
    }
}
?>