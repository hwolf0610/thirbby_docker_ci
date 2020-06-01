<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public $module_name = 'Dashboard';
    public $controller_name = 'dashboard';    
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->model(ADMIN_URL.'/dashboard_model');
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
    }
    public function index() {
        $arr['meta_title'] = $this->lang->line('title_admin_dashboard').' | '.$this->lang->line('site_title');   
        if($this->input->post('submit_page') == "Submit")
        {            
            $this->form_validation->set_rules('user_id[]', 'Users', 'trim|required');
            $this->form_validation->set_rules('template_id', 'Template', 'trim|required');
            //check form validation using codeigniter
            if ($this->form_validation->run())
            {
                $user_id = $this->input->post('user_id');
                 //get System Option Data
                $this->db->select('OptionValue');
                $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

                $this->db->select('OptionValue');
                $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();

                $email_template = $this->db->get_where('email_template',array('entity_id'=>$this->input->post('template_id')))->first_row();        
                if(!empty($user_id)){
                    foreach ($user_id as $key => $value) {
                        $userDetail = $this->dashboard_model->getUserEmail($value);
                       
                        $this->load->library('email');  
                        $config['charset'] = "utf-8";
                        $config['mailtype'] = "html";
                        $config['newline'] = "\r\n";      
                        $this->email->initialize($config);  
                        $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
                        $this->email->to(trim($userDetail->email));      
                        $this->email->subject($email_template->subject);  
                        $this->email->message($email_template->message);            
                        $this->email->send();            
                    }
                    redirect(base_url().ADMIN_URL.'/dashboard'); 
                }
            }
        }
        $arr['restaurantCount'] = $this->dashboard_model->getRestaurantCount(); 
        $arr['user'] = $this->dashboard_model->gettotalAccount(); 
        $arr['totalOrder'] = $this->dashboard_model->getOrderCount();
        $arr['restaurant'] = $this->dashboard_model->restaurant();
        $arr['orders'] = $this->dashboard_model->getLastOrders();
        $arr['count'] = $this->dashboard_model->getNotificationCount();
        $arr['template'] = $this->dashboard_model->getEmailTempate(); 
        $this->load->view(ADMIN_URL.'/dashboard',$arr);
    }
    public function ajaxNotification(){
        $count = $this->dashboard_model->ajaxNotification();
        echo json_encode($count);   
    }
    public function changeViewStatus(){
        $this->dashboard_model->changeViewStatus();
    }
}