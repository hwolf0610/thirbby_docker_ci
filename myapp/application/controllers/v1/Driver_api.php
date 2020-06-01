<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(1);
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Driver_api extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('v1/driver_api_model');                
        $this->load->library('form_validation');
    }
    // Login API
    public function login_post()
    {
        $login = $this->driver_api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        if(!empty($login)){
            $data = array('device_id'=>$this->post('firebase_token'));
           /* if($this->post('firebase_token')!=""){*/
                if($login->status==1)
                {
                    // update device 
                    $image = ($login->image)?image_url.$login->image:'';  
                   
                    $traking_data = array(
                        'latitude'=>$this->post('latitude'),
                        'longitude'=>$this->post('longitude'),
                        'driver_id'=>$login->entity_id,
                        
                    );  
                    $this->driver_api_model->addRecord('driver_traking_map',$traking_data);
                    
                    $this->driver_api_model->updateUser('users',$data,'entity_id',$login->entity_id);
                    $login_detail = array('FirstName'=>$login->first_name,'image'=>$image,'PhoneNumber'=>$login->mobile_number,'UserID'=>$login->entity_id,'phone_code'=>$login->phone_code);
                    $this->response(['login' => $login_detail,'status'=>1,'message' =>$this->lang->line('login_success') ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else if ($login->status==0){
                    $adminEmail = $this->driver_api_model->getSystemOptoin('Admin_Email_Address');
                    $this->response(['status' => 2,'message' => $this->lang->line('login_deactive'),'email'=>$adminEmail->OptionValue], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
           /* } else {
                $this->response(['status' => 0,'message' => $this->lang->line('login_empty_token')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }*/
             
        }        
        else
        {
            $emailexist = $this->driver_api_model->getRecord('users','mobile_number',$this->post('PhoneNumber'));
            if($emailexist){
                $this->response([
                    'status' => 0,
                    'message' =>$this->lang->line('pass_validation')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }        
    }
    // Forgot Password
    public function forgotpassword_post()
    {
        $checkRecord = $this->driver_api_model->getRecordMultipleWhere('users', array('mobile_number'=>$this->post('mobile_number'),'status'=>1));        
        if(!empty($checkRecord))
        {
            $activecode = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
            $password = random_string('alnum', 8);
            $data = array('active_code'=>$activecode,'password'=>md5(SALT.$password));                        
            $this->driver_api_model->updateUser('users',$data,'mobile_number',$this->post('mobile_number'));
            $this->response(['status' => 1,'password'=>$password,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('user_not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    //add review
    public function addReview_post(){
        if($this->post('rating') != '' && $this->post('review') != ''){
            $add_data = array(
                'rating'=>trim($this->post('rating')),
                'review'=>trim($this->post('review')),
                'order_user_id'=>$this->post('order_user_id'),
                'user_id'=>$this->post('user_id'),
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s')                
            );
            $this->driver_api_model->addRecord('review', $add_data);
            $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    public function editProfile_post(){
        $token = $this->post('token');
        $user_id =$this->post('user_id');
        $tokenusr = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenusr){
            $add_data =array(
                'first_name'=>$this->post('first_name'),
            );
            if (!empty($_FILES['image']['name']))
            {
                  $this->load->library('upload');
                  $config['upload_path'] = './uploads/profile';
                  $config['allowed_types'] = 'jpg|png|jpeg';
                  $config['encrypt_name'] = TRUE; 
                  // create directory if not exists
                  if (!@is_dir('uploads/profile')) {
                    @mkdir('./uploads/profile', 0777, TRUE);
                  }
                  $this->upload->initialize($config);                  
                  if ($this->upload->do_upload('image'))
                  {  
                    $img = $this->upload->data();
                    $add_data['image'] = "profile/".$img['file_name']; 
                  }
                  else
                  {
                    $data['Error'] = $this->upload->display_errors(); 
                    $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                  }  
            } 
            $this->driver_api_model->updateUser('users',$add_data,'entity_id',$this->post('user_id'));
            $token = $this->driver_api_model->checkToken($token, $user_id);
            $image = ($token->image)?image_url.$token->image:''; 
            $login_detail = array('FirstName'=>$token->first_name,'image'=> $image ,'PhoneNumber'=>$token->mobile_number,'UserID'=>$token->entity_id,'phone_code'=>$token->phone_code);
            $this->response(['profile'=>$login_detail,'status'=>1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //change address
    public function changePassword_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            if(md5(SALT.$this->post('old_password')) == $tokenres->password){
                if($this->post('confirm_password') == $this->post('password')){
                    $this->db->set('password',md5(SALT.$this->post('password')));
                    $this->db->where('entity_id',$user_id);
                    $this->db->update('users');
                    $this->response(['status'=>1,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK  
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('confirm_password')], REST_Controller::HTTP_OK); // OK  
                }
            }else{
                $this->response(['status'=>0,'message' => $this->lang->line('old_password')], REST_Controller::HTTP_OK); // OK  
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //accept order
    public function acceptOrder_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $order_id = $this->post('order_id');
            $driver_map_id = $this->post('driver_map_id');
            if($order_id){
                $details = $this->driver_api_model->getRecordMultipleWhere('order_driver_map',array('driver_map_id'=>$driver_map_id));
                if(!empty($details)){
                    if($this->post('order_status') == 'cancel'){
                        if($this->post('cancel_reason') != ''){
                            $add_data = array('cancel_reason'=>$this->post('cancel_reason'));
                            $this->driver_api_model->updateUser('order_driver_map',$add_data,'driver_map_id',$driver_map_id);
                            
                            $data = array('order_id'=>$order_id,'order_status'=>'cancel','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
                            $this->driver_api_model->addRecord('order_status',$data);

                            $this->db->set('order_status','cancel')->where('entity_id', $order_id)->update('order_master');
                            //get user of order
                            $userData = $this->driver_api_model->getUserofOrder($order_id);
                            if(!empty($userData) && $userData->device_id){
                                #prep the bundle
                                $fields = array();            
                                $message = 'Order has been canceled';
                                $fields['to'] = $userData->device_id; // only one user to send push notification
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
                        }else{
                            $this->driver_api_model->deleteRecord('order_driver_map','driver_map_id',$driver_map_id); 
                        }
                        $this->response(['status'=>1,'message' => 'Order canceled sucessfully'], REST_Controller::HTTP_OK); // OK */
                    }else{
                        $add_data = array('order_id'=>$order_id,'order_status'=>'preparing','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
                        $this->driver_api_model->addRecord('order_status',$add_data);
                        $detail = $this->driver_api_model->acceptOrder($order_id,$driver_map_id,$user_id);
                        $this->response(['user_detail'=>$detail,'status'=>1,'message' => $this->lang->line('order_accept')], REST_Controller::HTTP_OK); // OK */
                    }
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('order_accepted')], REST_Controller::HTTP_OK); // OK */
                } 
            }else{
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    //get order of driver
    public function getallOrder_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $detail = $this->driver_api_model->getallOrder($user_id);
            $this->response(['order_list'=>$detail,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    //change status after delivery
    public function deliveredOrder_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $order_id = $this->post('order_id');
            $status = $this->post('status');
            $subtotal = $this->post('subtotal');
            $add_data = array('order_id'=>$order_id,'order_status'=>$status,'time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
            $this->driver_api_model->addRecord('order_status',$add_data);
            $detail = $this->driver_api_model->deliveredOrder($order_id,$status,$subtotal);
            $this->response(['order_detail'=>$detail,'status'=>1,'message' => 'Order '.$status.''], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    //get order commission
    public function getCommissionList_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $detail = $this->driver_api_model->getCommissionList($user_id);
            $this->response(['CommissionList'=>$detail,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
            $this->response(['status'=>1,'message'=> $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //track driver location
    public function driverTracking_post(){
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id); 
        if($tokenres){
            if($this->post('latitude') && $this->post('longitude')){
                //$data = array('latitude'=>$this->post('latitude'),'longitude'=>$this->post('longitude'));
               // $this->driver_api_model->updateUser('driver_traking_map',$data,'driver_id',$user_id);
                $traking_data = array(
                    'latitude'=>$this->post('latitude'),
                    'longitude'=>$this->post('longitude'),
                    'driver_id'=>$user_id,
                );  
                $this->driver_api_model->addRecord('driver_traking_map',$traking_data);
                $this->response(['status'=>1,'message'=> $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
            }else{
                $this->response([
                    'status' => 0,
                    'message' =>  $this->lang->line('validation')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }

    //Logout USER
    public function logout_post()
    {
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenres = $this->driver_api_model->getRecord('users', 'entity_id',$userid);
        if($tokenres){
            $data = array('device_id'=>"");            
            $this->driver_api_model->updateUser('users',$data,'entity_id',$tokenres->entity_id);
            $this->response(['status' => 1,'message' => $this->lang->line('user_logout')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //change firebase token
    public function changeToken_post()
    {
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenusr = $this->driver_api_model->checkToken($token, $userid);
        if($tokenusr){
            $data = array('device_id'=>$this->post('firebase_token'));
            $this->driver_api_model->updateUser('users',$data,'entity_id',$userid);  
            $this->response(['status' => 1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //country code
    public function getCountryPhoneCode_get(){
        $data = $this->driver_api_model->getRecordMultipleWhere('system_option',array('OptionSlug'=>'phone_code'));
        $country = $this->driver_api_model->getRecordMultipleWhere('system_option',array('OptionSlug'=>'country'));
        if(!empty($data)){
            $data = array('phone_code'=>$data->OptionValue,'country'=>$country->OptionValue);
            $this->response(['data'=>$data,'status' => 1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
        
    }
}
