<?php
class Order_model extends CI_Model {
	function __construct()
	{
		parent::__construct();		        
	}	
	// method for getting all
	public function getGridList($sortFieldName = '', $sortOrder = 'DESC', $displayStart = 0, $displayLength = 10,$order_status)
	{
		if($this->input->post('page_title') != ''){
			$this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('page_title')."%'");
		}
		if($this->input->post('order') != ''){
			$this->db->like('o.entity_id', $this->input->post('order'));
		}
		if($this->input->post('driver') != ''){
			$this->db->where("CONCAT(driver.first_name,' ',driver.last_name) like '%".$this->input->post('driver')."%'");
		}
		if($this->input->post('Status') != ''){
			$this->db->like('o.status', $this->input->post('Status'));
		}
		if($this->input->post('restaurant') != ''){
			$this->db->like('restaurant.name', $this->input->post('restaurant'));
            $name = $this->input->post('restaurant');
            $where = "(order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*')";
            $this->db->where($where);
        }
		if($this->input->post('order_total') != ''){
			$this->db->like('o.total_rate', $this->input->post('order_total'));
		}
		if($this->input->post('order_status') != ''){
			$this->db->like('o.order_status', $this->input->post('order_status'));
		}
		if($this->input->post('order_date') != ''){
			$this->db->like('o.created_date', $this->input->post('order_date'));
		}
		$this->db->select('o.total_rate as rate,o.order_status as ostatus,o.status,o.entity_id as entity_id,o.created_date,o.restaurant_id,u.first_name as fname,u.last_name as lname,u.entity_id as user_id,order_status.order_status as orderStatus,driver.first_name,driver.last_name,order_detail.restaurant_detail,restaurant.name');
		$this->db->join('users as u','o.user_id = u.entity_id','left');
		$this->db->join('restaurant','o.restaurant_id = restaurant.entity_id','left');
		$this->db->join('order_status','o.entity_id = order_status.order_id','left');
		$this->db->join('order_driver_map','o.entity_id = order_driver_map.order_id AND order_driver_map.is_accept = 1','left');
		$this->db->join('order_detail','o.entity_id = order_detail.order_id','left'); 
		$this->db->join('users as driver','order_driver_map.driver_id = driver.entity_id','left');
		if($this->session->userdata('UserType') == 'Admin'){
			$this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
		}
		if($order_status){
			$this->db->where('o.order_status',$order_status);
		}
		$this->db->group_by('o.entity_id');
		$result['total'] = $this->db->count_all_results('order_master as o');

		if($sortFieldName != '')
			$this->db->order_by($sortFieldName, $sortOrder);
		if($this->input->post('page_title') != ''){
			$this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('page_title')."%'");
		}
		if($this->input->post('driver') != ''){
			$this->db->where("CONCAT(driver.first_name,' ',driver.last_name) like '%".$this->input->post('driver')."%'");
		}
		if($this->input->post('Status') != ''){
			$this->db->like('o.status', $this->input->post('Status'));
		}
		if($this->input->post('restaurant') != ''){
			$this->db->like('restaurant.name', $this->input->post('restaurant'));
            $name = $this->input->post('restaurant');
            $where = "(order_detail.restaurant_detail REGEXP '.*".'"'."name".'"'.";s:[0-9]+:".'"'."$name".'"'.".*')";
            $this->db->where($where);
        }
		if($this->input->post('order_total') != ''){
			$this->db->like('o.total_rate', $this->input->post('order_total'));
		}
		if($this->input->post('order_status') != ''){
			$this->db->like('o.order_status', $this->input->post('order_status'));
		}
		if($this->input->post('order') != ''){
			$this->db->like('o.entity_id', $this->input->post('order'));
		}
		if($this->input->post('order_date') != ''){
			$this->db->like('o.created_date', $this->input->post('order_date'));
		}
		if($displayLength>1)
			$this->db->limit($displayLength,$displayStart);  
		$this->db->select('o.total_rate as rate,o.order_status as ostatus,o.status,o.restaurant_id,o.created_date,o.entity_id as entity_id,o.user_id,u.first_name as fname,u.last_name as lname,u.entity_id as user_id,order_status.order_status as orderStatus,driver.first_name,driver.last_name,order_detail.restaurant_detail,restaurant.name');
		$this->db->join('users as u','o.user_id = u.entity_id','left');   
		$this->db->join('order_detail','o.entity_id = order_detail.order_id','left'); 
		$this->db->join('order_status','o.entity_id = order_status.order_id','left');
		$this->db->join('restaurant','o.restaurant_id = restaurant.entity_id','left');
		$this->db->join('order_driver_map','o.entity_id = order_driver_map.order_id AND order_driver_map.is_accept = 1','left');
		$this->db->join('users as driver','order_driver_map.driver_id = driver.entity_id','left');
		if($order_status){
			$this->db->where('o.order_status',$order_status);
		}  
		if($this->session->userdata('UserType') == 'Admin'){
			$this->db->where('restaurant.created_by',$this->session->userdata('UserID'));
		}
		$this->db->group_by('o.entity_id');
		$result['data'] = $this->db->get('order_master as o')->result();     
		return $result;
	}		
	// method for adding 
	public function addData($tblName,$Data)
	{   
		$this->db->insert($tblName,$Data);            
		return $this->db->insert_id();
	} 
	// method for adding 
	public function addBatch($tblName,$Data)
	{   
		$this->db->insert_batch($tblName,$Data);            
		return $this->db->insert_id();
	}
	// method to get details by id
	public function getEditDetail($entity_id)
	{
		$this->db->select('order.*,res.name, address.address,address.landmark,address.city,address.zipcode,u.first_name,u.last_name,uaddress.address as uaddress,uaddress.landmark as ulandmark,uaddress.city as ucity,uaddress.zipcode as uzipcode');
		$this->db->join('restaurant as res','order.restaurant_id = res.entity_id','left');
		$this->db->join('restaurant_address as address','res.entity_id = address.resto_entity_id','left');
		$this->db->join('users as u','order.user_id = u.entity_id','left');
		$this->db->join('user_address as uaddress','u.entity_id = uaddress.user_entity_id','left');
		return  $this->db->get_where('order_master as order',array('order.entity_id'=>$entity_id))->first_row();
	}
	// update data common function
	public function updateData($Data,$tblName,$fieldName,$ID)
	{        
			$this->db->where($fieldName,$ID);
			$this->db->update($tblName,$Data);            
			return $this->db->affected_rows();
	}
	 // updating status and send request to driver
	public function UpdatedStatus($tblname,$entity_id,$restaurant_id,$order_id){
		$this->db->set('status',1)->where('entity_id',$order_id)->update('order_master');
		//send notification to user
		$this->db->select('users.entity_id,users.device_id,order_delivery');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.entity_id',$order_id);
        $device = $this->db->get('order_master')->first_row();
        
        if($device->device_id){  
            #prep the bundle
            $fields = array();            
            $message = 'Your order is accepted';
            $fields['to'] = $device->device_id; // only one user to send push notification
            $fields['notification'] = array ('body'  => $message,'sound'=>'default');
            $fields['data'] = array ('screenType'=>'order');
           
            $headers = array (
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);
            curl_close($ch);  
        } 
        //send notification to driver
	    if($device->order_delivery == 'Delivery'){    
	        $this->db->select('users.entity_id');
	        $this->db->where('user_type','Driver');
	        $driver = $this->db->get('users')->result_array();
	          
	        $this->db->select('driver_traking_map.latitude,driver_traking_map.longitude,driver_traking_map.driver_id,users.device_id');
	        $this->db->join('users','driver_traking_map.driver_id = users.entity_id','left');
	        $this->db->where('users.status',1);
	        if(!empty($driver)){
	        	$this->db->where_in('driver_id',array_column($driver, 'entity_id'));
	        }
	        $this->db->where('driver_traking_map.created_date = (SELECT
		        driver_traking_map.created_date
		    FROM
		        driver_traking_map
		    WHERE
		        driver_traking_map.driver_id = users.entity_id
		    ORDER BY
		        driver_traking_map.created_date desc
		    LIMIT 1)');
	        $detail = $this->db->get('driver_traking_map')->result();
	        $flag = false;
	        if(!empty($detail)){
	            foreach ($detail as $key => $value) {
	                $longitude = $value->longitude;
	                $latitude = $value->latitude;
	                $this->db->select("(6371 * acos ( cos ( radians($latitude) ) * cos( radians(address.latitude ) ) * cos( radians( address.longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( address.latitude )))) as distance");
	                $this->db->join('restaurant_address as address','restaurant.entity_id = address.resto_entity_id','left');
	                $this->db->where('restaurant.entity_id',$restaurant_id);
	                $this->db->having('distance <',NEAR_KM);
	                $result = $this->db->get('restaurant')->result();
	                
	                if(!empty($result)){
	                    if($value->device_id){ 
	                        $flag = true;   
	                        $array = array(
	                            'order_id'=>$order_id,
	                            'driver_id'=>$value->driver_id,
	                            'date'=>date('Y-m-d H:i:s'),
	                            'distance'=>$result[0]->distance
	                        );
	                        $id = $this->addData('order_driver_map',$array);
	                        #prep the bundle
	                        $fields = array();            
	                        $message = 'You have new order, please accept.';
	                        $fields['to'] = $value->device_id; // only one user to send push notification
	                        $fields['notification'] = array ('body'  => $message,'sound'=>'default');
	                        $fields['data'] = array ('screenType'=>'order');
	                       
	                        $headers = array (
	                            'Authorization: key=' . FCM_KEY,
	                            'Content-Type: application/json'
	                        );
	                        #Send Reponse To FireBase Server    
	                        $ch = curl_init();
	                        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	                        curl_setopt( $ch,CURLOPT_POST, true );
	                        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	                        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	                        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	                        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	                        $result = curl_exec($ch);
	                        curl_close($ch);            
	                    } 
	                }
	            }
	        }
	    }

	}
	// delete
	public function ajaxDelete($tblname,$entity_id)
	{
		$this->db->delete($tblname,array('entity_id'=>$entity_id));  
	}
	//get list
	public function getListData($tblname){
		if($tblname == 'users'){
			$this->db->select('first_name,last_name,entity_id');
			$this->db->where('status',1);
			$this->db->where('user_type !=','MasterAdmin');
			if($this->session->userdata('UserType') == 'Admin'){
				$this->db->where('created_by',$this->session->userdata('UserID'));  
			}        
			return $this->db->get($tblname)->result();
		}else if($tblname == 'restaurant'){
			$this->db->select('name,entity_id,amount_type,amount');
			$this->db->where('status',1);
			if($this->session->userdata('UserType') == 'Admin'){
				$this->db->where('created_by',$this->session->userdata('UserID'));  
			}
			return $this->db->get($tblname)->result();
		}else{
		    $this->db->select('name,entity_id,amount_type,amount');
			$this->db->where('status',1);
			return $this->db->get($tblname)->result();
		}
	}
	//get items
	public function getItem($entity_id){
		$this->db->select('entity_id,name,price');
		$this->db->where('restaurant_id',$entity_id);
		$this->db->where('status',1);
		return $this->db->get('restaurant_menu_item')->result();
	}
	//get address
	public function getAddress($entity_id){
		$this->db->where('user_entity_id',$entity_id);
		return $this->db->get('user_address')->result();
	}
	//get invoice data
	public function getInvoiceMenuItem($entity_id){
		$this->db->where('order_id',$entity_id);
		return $this->db->get('order_detail')->first_row();
	}
	//get user data
	public function getUserDate($entity_id){
		$this->db->select('device_id');
		$this->db->where('entity_id',$entity_id);
		return $this->db->get('users')->first_row();
	}
	//delete multiple order
	public function deleteMultiOrder($order_id){
		$this->db->where_in('entity_id',$order_id);
		$this->db->delete('order_master');
		return $this->db->affected_rows();
	}
	//get item name
	public function getItemName($item_id){
		$this->db->where('entity_id',$item_id);
		return $this->db->get('restaurant_menu_item')->first_row();
	}
	//get order status history
	public function statusHistory($order_id){
		$this->db->where('order_id',$order_id);
		return $this->db->get('order_status')->result();
	}
    //get rest detail
	public function getRestaurantDetail($entity_id){
        $this->db->select('restaurant.name,restaurant.image,restaurant.phone_number,restaurant.email,restaurant.amount_type,restaurant.amount,restaurant_address.address,restaurant_address.landmark,restaurant_address.zipcode,restaurant_address.city');
        $this->db->join('restaurant_address','restaurant.entity_id = restaurant_address.resto_entity_id','left');
        $this->db->where('restaurant.entity_id',$entity_id);
        return $this->db->get('restaurant')->first_row();
	}
}
?>