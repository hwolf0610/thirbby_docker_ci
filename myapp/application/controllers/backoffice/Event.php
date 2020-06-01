<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');

class Event extends CI_Controller {

    public $module_name = 'Event';

    public $controller_name = 'event';

    public $prefix = '_event'; 

    public function __construct() {

        parent::__construct();

        if (!$this->session->userdata('is_admin_login')) {

            redirect(ADMIN_URL.'/home');

        }

        $this->load->library('form_validation');

        $this->load->model(ADMIN_URL.'/event_model');

    }

    // view event

    public function view(){

    	$data['meta_title'] = $this->lang->line('title_admin_event').' | '.$this->lang->line('site_title');

        $this->load->view(ADMIN_URL.'/event',$data);

    }

    // add event

    public function add(){

        $data['meta_title'] = $this->lang->line('title_admin_eventadd').' | '.$this->lang->line('site_title');

    	if($this->input->post('submit_page') == "Submit")

        {

            $this->form_validation->set_rules('name', 'Name', 'trim|required');

            $this->form_validation->set_rules('no_of_people', 'No of People', 'trim|required');

            $this->form_validation->set_rules('booking_date', 'Booking Date', 'trim|required');

            $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');

            $this->form_validation->set_rules('restaurant_id', 'Restaurant', 'trim|required');

            $this->form_validation->set_rules('user_id', 'User', 'trim|required');

            if ($this->form_validation->run())

            {

                $add_data = array(                   

                    'name'=>$this->input->post('name'),

                    'no_of_people'=>$this->input->post('no_of_people'),

                    'booking_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('booking_date'))),

                    'end_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),

                    'restaurant_id'=>$this->input->post('restaurant_id'),

                    'user_id'=>$this->input->post('user_id'),

                    'status'=>1,

                    'created_by' => $this->session->userdata('UserID')

                ); 

                $this->event_model->addData('event',$add_data); 

                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));

                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');           

            }

        }

        $data['restaurant'] = $this->event_model->getListData('restaurant');

        $data['user'] = $this->event_model->getListData('users');

        $data['booked_date'] = $this->event_model->getBookedDate();

    	$this->load->view(ADMIN_URL.'/event_add',$data);

    }

    // edit event

    public function edit(){

    	$data['meta_title'] = $this->lang->line('title_admin_eventedit').' | '.$this->lang->line('site_title');

        // check if form is submitted 

        if($this->input->post('submit_page') == "Submit")

        {

            $this->form_validation->set_rules('name', 'Name', 'trim|required');

            $this->form_validation->set_rules('no_of_people', 'No of People', 'trim|required');

            $this->form_validation->set_rules('booking_date', 'Booking Date', 'trim|required');

            $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');

            $this->form_validation->set_rules('restaurant_id', 'Restaurant', 'trim|required');

            $this->form_validation->set_rules('user_id', 'User', 'trim|required');

            if ($this->form_validation->run())

            {

                $updateData = array(     

                    'name'=>$this->input->post('name'),

                    'no_of_people'=>$this->input->post('no_of_people'),

                    'booking_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('booking_date'))),

                    'end_date'=>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),

                    'restaurant_id'=>$this->input->post('restaurant_id'),

                    'user_id'=>$this->input->post('user_id'),       

                    'updated_date'=>date('Y-m-d H:i:s'),

                    'updated_by' => $this->session->userdata('UserID')

                ); 

                $this->event_model->updateData($updateData,'event','entity_id',$this->input->post('entity_id')); 

                $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));

                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');           

            }

        }        

        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');

        $data['edit_records'] = $this->event_model->getEditDetail($entity_id);

        $data['restaurant'] = $this->event_model->getListData('restaurant');

        $data['user'] = $this->event_model->getListData('users');

        $this->load->view(ADMIN_URL.'/event_add',$data);

    }

    //ajax view

    public function ajaxview() {

        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';

        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';

        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';

        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';

        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';

        

        $sortfields = array(1=>'u.first_name','2'=>'name','3'=>'res.name','4'=>'status','5'=>'end_date','6'=>'booking_date');

        $sortFieldName = '';

        if(array_key_exists($sortCol, $sortfields))

        {

            $sortFieldName = $sortfields[$sortCol];

        }

        //Get Recored from model

        $grid_data = $this->event_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);

        $totalRecords = $grid_data['total'];        

        $records = array();

        $records["aaData"] = array(); 

        $nCount = ($displayStart != '')?$displayStart+1:1;

        foreach ($grid_data['data'] as $key => $val) {

            $coupon_type = ($val->coupon_type)?"'".$val->coupon_type."'":'0';

            $tax_rate = ($val->tax_rate)?$val->tax_rate:0;

            $tax_type = ($val->tax_type)? "'".$val->tax_type."'":0;

            $coupon_amount = ($val->coupon_amount)?$val->coupon_amount:'0';

            $entId = $val->entity_id;

            $records["aaData"][] = array(

                $nCount,

                $val->fname.' '.$val->lname,

                $val->rname,

                $val->no_of_people,

                date('Y-m-d H:i',strtotime($val->booking_date)),

                ($val->amount)?$val->amount:'-',

                ($val->event_status)?$val->event_status:'-',

                ($val->status)?'Active':'Deactive',

                '<button onclick="deleteDetail('.$val->entity_id.')"  title="Click here for delete" class="delete btn btn-sm danger-btn margin-bottom red-btn"><i class="fa fa-times"></i> Delete</button> <button title="Add Amount" onclick="addAmount('.$entId.','.$tax_rate.','.$coupon_amount.','.$tax_type.','.$coupon_type.')" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-dollar"></i> Add Amount</button><button onclick="getInvoice('.$val->entity_id.')"  title="Click here for Download Invoice" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> Invoice</button>'

            );

            /*<a class="btn btn-sm danger-btn margin-bottom" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> Edit</a>*/

            /*<button onclick="disableDetail('.$val->entity_id.','.$val->status.')"  title="Click here for '.($val->status?'Deactivate':'Activate').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?'Deactivate':'Activate').'</button>*/

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

            $this->event_model->UpdatedStatus('event',$entity_id,$this->input->post('status'));

        }

    }

    // method for deleting

    public function ajaxDelete(){

        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';

        $this->event_model->ajaxDelete('event',$entity_id);

    }

    //get restaurant

    public function getRestuarantDetail(){

        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';

        $result = $this->event_model->getRestuarantDetail($entity_id);

        echo json_encode($result);

    }

    //add amount

    public function addAmount(){

        $this->form_validation->set_rules('amount','Amount', 'trim|required');

        $this->form_validation->set_rules('event_status','Status', 'trim|required');

        if($this->form_validation->run())

        {

            $add_data = array(

                'subtotal'     =>$this->input->post('subtotal'),

                'amount'       =>$this->input->post('amount'),

                'event_status' =>$this->input->post('event_status'),

            );

            $data = $this->event_model->updateData($add_data,'event','entity_id',$this->input->post('entity_id')); 

            echo json_encode($data);

        }  

    }

    //create invoice

    public function getInvoice(){

        $entity_id = ($this->input->post('entity_id'))?$this->input->post('entity_id'):'';

        $data['event_records'] = $this->event_model->getEditDetail($entity_id);

        $html = $this->load->view('backoffice/event_invoice',$data,true);

        if (!@is_dir('uploads/event')) {

          @mkdir('./uploads/event', 0777, TRUE);

        } 
        $verificationCode = random_string('alnum',8);
        $filepath = 'uploads/event/'.$verificationCode.'.pdf';

        $this->load->library('M_pdf'); 

        $mpdf=new mPDF('','Letter'); 

        $mpdf->SetHTMLHeader('');

        $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://eatance.evincedev.com/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');

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

}





 ?>