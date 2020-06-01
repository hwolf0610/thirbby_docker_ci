<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Notification extends CI_Controller { 
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/notification_model');
    }
    public function view() {
        $data['meta_title'] = $this->lang->line('title_admin_notification').' | '.$this->lang->line('site_title');        
        $this->load->view(ADMIN_URL.'/notification',$data);
    }
    public function add() {
        $data['meta_title'] = $this->lang->line('title_admin_notificationadd').' | '.$this->lang->line('site_title');
        if($this->input->post('submitNotification') == "Submit")
        {
            $this->form_validation->set_rules('notification_title', 'Notification Title', 'trim|required');
            if ($this->form_validation->run())
            {
                $addNotificationData = array(                   
                    'notification_title'=>$this->input->post('notification_title'),                    
                    'notification_description' =>$this->input->post('notification_description'),
                    'created_by'=>$this->session->userdata("UserID")
                );                                            
                $NotificationID = $this->notification_model->addData('notifications',$addNotificationData);
                
                $UserIds = $this->input->post('user_id');
                $NotificationDetail = array();
                if($this->input->post('save') == 1){
                    for ($u=0; $u < count($UserIds); $u++) { 
                        $NotificationDetail[] = array('notification_id' => $NotificationID, 'user_id'=>$UserIds[$u]);
                    }                
                    $this->notification_model->addRecordBatch('notifications_users',$NotificationDetail);
                }
                // START Push Notification
                $DeviceIds = $this->notification_model->getUserDevices($UserIds);               
                $registrationIds = array_column($DeviceIds, 'device_id');
                $return = array_chunk($registrationIds,800);    
                foreach ($return as $key => $registrationId) {
                    #prep the bundle
                    $fields = array();            
                    if(is_array($registrationId) && count($registrationId) > 1){
                        $fields['registration_ids'] = $registrationId; // multiple user to send push notification
                    }else{
                        $fields['to'] = $registrationId[0]; // only one user to send push notification
                    }          
                    $fields['notification']['body'] = $this->input->post('notification_title');
                    $fields['notification']['description'] = $this->input->post('notification_description');
                    $fields['notification']['sound'] = 'default';
                    if($this->input->post('save') == 1){
                        $fields['data'] = array ('screenType'=>'noti');
                    }else{
                        $fields['data'] = array ('screenType'=>'admin');
                    }
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
               
                // END Push Notification
                $this->session->set_flashdata('NotificationMSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/notification/view');                 
            }
        }
        $data['users'] =  $this->notification_model->getUserNotification();
        $this->load->view(ADMIN_URL.'/notification_add',$data);
    }
    public function ajaxview() {
        $searchTitleName = ($this->input->post('pageTitle') != '')?$this->input->post('pageTitle'):'';
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'notification_title',2=>'Status',3=>'CreatedDate');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $NotificationData = $this->notification_model->getNotificationList($searchTitleName,$sortFieldName,$sortOrder,$displayStart,$displayLength);
        $totalRecords = $NotificationData['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($NotificationData['data'] as $key => $notificationDetails) {
            $records["aaData"][] = array(
                $nCount,
                $notificationDetails->notification_title,                
                '<button onclick="deleteNotification('.$notificationDetails->entity_id.')"  title="Click here for Delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button>'
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    public function ajaxdeleteNotification() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->notification_model->deleteRecord($entity_id);
        }
    }
}