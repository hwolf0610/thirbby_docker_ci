<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Order extends CI_Controller { 
    public $module_name = 'Order';
    public $controller_name = 'order';
    public $prefix = '_order';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/order_model');
    }
    // view order
    public function view(){
    	$data['meta_title'] = $this->lang->line('title_admin_order').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/order',$data);
    }
    // add order
    public function add(){
        $data['meta_title'] = $this->lang->line('title_admin_orderadd').' | '.$this->lang->line('site_title');
    	if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('user_id', 'User', 'trim|required');
            $this->form_validation->set_rules('restaurant_id', 'Restaurant', 'trim|required');
            $this->form_validation->set_rules('address_id','Address', 'trim|required');
            $this->form_validation->set_rules('order_status','Order Status', 'trim|required');
            $this->form_validation->set_rules('order_date','Date Of Order', 'trim|required');
            $this->form_validation->set_rules('total_rate','Total', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $add_data = array(              
                    'user_id'=>$this->input->post('user_id'),
                    'restaurant_id' =>$this->input->post('restaurant_id'),
                    'address_id' =>$this->input->post('address_id'),
                    'coupon_id' =>$this->input->post('coupon_id'),
                    'order_status' =>$this->input->post('order_status'),
                    'order_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('order_date'))),
                    'subtotal' =>$this->input->post('subtotal'),
                    'tax_rate'=>$this->input->post('tax_rate'),
                    'tax_type'=>$this->input->post('tax_type'),
                    'total_rate' =>$this->input->post('total_rate'),
                    'coupon_type'=>$this->input->post('coupon_type'),
                    'coupon_amount'=>$this->input->post('coupon_amount'),
                    'created_by'=>$this->session->userdata("UserID"),
                    'status'=>1
                );                                           
                $order_id = $this->order_model->addData('order_master',$add_data);
                //add items
                $items = $this->input->post('item_id');
                $add_item = array();
                if(!empty($items)){
                    foreach ($items as $key => $value) {
                        $itemName = $this->order_model->getItemName($this->input->post('item_id')[$key]);
                        $add_item[] = array(
                            "item_id"=>$this->input->post('item_id')[$key],
                            "item_name"=> $itemName->name,
                            "qty_no"=>$this->input->post('qty_no')[$key],
                            "rate"=>$this->input->post('rate')[$key],
                            "order_id"=>$order_id
                        );
                    }
                }
                //$this->order_model->addBatch('order_item',$add_item);
                $order_detail = array(
                    'order_id'=>$order_id,
                    'item_detail' => serialize($add_item),
                ); 
                $this->order_model->addData('order_detail',$order_detail);
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');                 
            }
        }
        $data['restaurant'] = $this->order_model->getListData('restaurant');
        $data['user'] = $this->order_model->getListData('users');
        $data['coupon'] = $this->order_model->getListData('coupon');
    	$this->load->view(ADMIN_URL.'/order_add',$data);
    }
    // edit order
    public function edit(){
    	$data['meta_title'] = $this->lang->line('title_admin_orderedit').' | '.$this->lang->line('site_title');
        // check if form is submitted 
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('user_id', 'User', 'trim|required');
            $this->form_validation->set_rules('restaurant_id', 'Restaurant', 'trim|required');
            $this->form_validation->set_rules('address_id','Address', 'trim|required');
            $this->form_validation->set_rules('order_status','Order Status', 'trim|required');
            $this->form_validation->set_rules('order_date','Date Of Order', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {  
                $edit_data = array(              
                    'user_id'=>$this->input->post('user_id'),
                    'restaurant_id' =>$this->input->post('restaurant_id'),
                    'address_id' =>$this->input->post('address_id'),
                    'coupon_id' =>$this->input->post('coupon_id'),
                    'order_status' =>$this->input->post('order_status'),
                    'order_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('order_date'))),
                    'subtotal' =>$this->input->post('subtotal'),
                    'total_rate' =>$this->input->post('total_rate'),
                    'tax_rate'=>$this->input->post('tax_rate'),
                    'created_by'=>$this->session->userdata("UserID")
                );                                           
                $this->order_model->updateData($edit_data,'order_master','entity_id',$this->input->post('entity_id')); 
                //add ites
                $items = $this->input->post('item_id');
                $add_item = array();
                if(!empty($items)){ 
                    foreach ($items as $key => $value) {
                        $add_item[] = array(
                            "item_id"=>$this->input->post('item_id')[$key],
                            "qty_no"=>$this->input->post('qty_no')[$key],
                            "rate"=>$this->input->post('rate')[$key],
                            "order_id"=>$this->input->post('entity_id')
                        );
                    }
                }
                $this->order_model->updateData($add_item,'order_item','entity_id',$this->input->post('entity_id'));
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');                 
            }
        }  
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->order_model->getEditDetail($entity_id);
        $data['menu_item'] = $this->order_model->getMenuItem($entity_id);
        $data['restaurant'] = $this->order_model->getListData('restaurant');
        $data['user'] = $this->order_model->getListData('users');
        $data['coupon'] = $this->order_model->getListData('coupon');
        $this->load->view(ADMIN_URL.'/order_add',$data);
    }
     //ajax view
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        $order_status = ($this->uri->segment('4'))?$this->uri->segment('4'):''; 
        $sortfields = array(1=>'u.first_name','2'=>'o.total_rate','3'=>'o.order_status','4'=>'o.status','5'=>'o.entity_id','6'=>'driver.first_name');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->order_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength,$order_status);
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $disabled = ($val->ostatus == 'delivered' || $val->ostatus == 'cancel')?'disabled':'';
            $ostatus = ($val->ostatus)?"'".$val->ostatus."'":'';
            $restaurant = unserialize($val->restaurant_detail);
            $records["aaData"][] = array(
                '<input type="checkbox" name="ids[]" value="'.$val->entity_id.'">',
                $val->entity_id,
                ($restaurant)?$restaurant->name:$val->name,
                ($val->fname || $val->lname)?$val->fname.' '.$val->lname:'Order by Restaurant',
                $val->rate,
                $val->first_name.' '.$val->last_name,
                ucfirst($val->ostatus),
                ($val->status)?'Active':'Deactive',
                ' <button onclick="deleteDetail('.$val->entity_id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button> <button onclick="getInvoice('.$val->entity_id.')"  title="Click here for Download Invoice" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> Invoice</button>
                    <button onclick="updateStatus('.$val->entity_id.','.$ostatus.','.$val->user_id.')" '.$disabled.' title="Click here for update status" class="delete btn btn-sm danger-btn margin-bottom blue-btn"><i class="fa fa-edit"></i> Change Status</button>'
            );
           /* <a class="btn btn-sm danger-btn margin-bottom" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> Edit</a>
           <button onclick="disableDetail('.$val->entity_id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>*/
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    // method to change status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->order_model->UpdatedStatus('order_master',$entity_id,$this->input->post('status'));
        }
    }
    // method for deleting
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->order_model->ajaxDelete('order_master',$entity_id);
    }
    //get item of restro
    public function getItem(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id){
           $result =  $this->order_model->getItem($entity_id);
                $html = '<option value="">Select Menu item</option>';
           foreach ($result as $key => $value) {
                $html .= '<option value="'.$value->entity_id.'" data-id="'.$value->price.'">'.$value->name.'</option>';
           }
        }
        echo $html;
    }
    //get address
    public function getAddress(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id){
           $result =  $this->order_model->getAddress($entity_id);
                $html = '<option value="">Select Address</option>';
           foreach ($result as $key => $value) {
                $html .= '<option value="'.$value->entity_id.'">'.$value->address.' , '.$value->city.' , '.$value->state.' , '.$value->country.' '.$value->zipcode.'</option>';
           }
        }
        echo $html;
    }
    //pending
    public function pending(){
        $data['meta_title'] = $this->lang->line('title_admin_pending').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/pending_order',$data);
    }
    //delivered
    public function delivered(){
        $data['meta_title'] = $this->lang->line('title_admin_delivered').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/delivered_order',$data);
    }
    //on going
    public function on_going(){
        $data['meta_title'] = $this->lang->line('title_admin_ongoing').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/ongoing_order',$data);
    }
    //cancel
    public function cancel(){
        $data['meta_title'] = $this->lang->line('title_admin_cancel').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/cancel_order',$data);
    }
    //create invoice
    public function getInvoice(){
        $entity_id = ($this->input->post('entity_id'))?$this->input->post('entity_id'):'';
        $data['order_records'] = $this->order_model->getEditDetail($entity_id);
        $data['menu_item'] = $this->order_model->getInvoiceMenuItem($entity_id);
        $html = $this->load->view('backoffice/order_invoice',$data,true);
        if (!@is_dir('uploads/invoice')) {
          @mkdir('./uploads/invoice', 0777, TRUE);
        } 
        $filepath = 'uploads/invoice/'.$entity_id.'.pdf';
        $this->load->library('M_pdf'); 
        $mpdf=new mPDF('','Letter'); 
        $mpdf->SetHTMLHeader('');
        $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
        $mpdf->AddPage('', // L - landscape, P - portrait 
            '', '', '', '',
            0, // margin_left
            0, // margin right
            10, // margin top
            23, // margin bottom
            0, // margin header
            0 //margin footer
        );
        $mpdf->WriteHTML($html);
        $mpdf->output($filepath,'F');
        echo $filepath;
          
    }
    //add status
    public function updateOrderStatus(){
        $entity_id = ($this->input->post('entity_id'))?$this->input->post('entity_id'):''; 
        $user_id = ($this->input->post('user_id'))?$this->input->post('user_id'):'';
        if($entity_id){
            $this->db->set('order_status',$this->input->post('order_status'))->where('entity_id',$entity_id)->update('order_master');
            $addData = array(
                'order_id'=>$entity_id,
                'order_status'=>$this->input->post('order_status'),
                'time'=>date('Y-m-d H:i:s')
            );
            $order_id = $this->order_model->addData('order_status',$addData);
            $userdata = $this->order_model->getUserDate($user_id);
            $message = "Order status is now ".$this->input->post('order_status')."";
            $device_id = $userdata->device_id;
            $this->sendFCMRegistration($device_id,$message);
        }
    }
    // Send notification
    function sendFCMRegistration($registrationIds,$message) {   
        if($registrationIds){        
            #prep the bundle
            $fields = array();            
           
            $fields['to'] = $registrationIds; // only one user to send push notification
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
    public function deleteMultiOrder(){
        $orderId = ($this->input->post('arrayData'))?$this->input->post('arrayData'):"";
        if($orderId){
            $order_id = explode(',', $orderId);
            $data = $this->order_model->deleteMultiOrder($order_id);
            echo json_encode($data);
        }
    }
}
?>