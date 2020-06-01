<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class System_option extends CI_Controller {	 
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL.'/home');
        }
        $this->load->model(ADMIN_URL.'/systemoption_model');
    }
    public function view() {
        $data['meta_title'] = $this->lang->line('titleadmin_systemoptions').' | '.$this->lang->line('site_title');

        if($this->input->post('SubmitSystemSetting') == "Submit")
        {
                $systemOptionCount = count($_POST['OptionValue']);
                $systemOptionData = array();
                for ($nCount = 0; $nCount < $systemOptionCount; $nCount++) 
                {
                      $systemOptionData[] = array(
                          'SystemOptionID'  => $_POST['SystemOptionID'][$nCount],
                          'OptionValue'  => $_POST['OptionValue'][$nCount],
                          'UpdatedBy'    => $this->session->userdata("adminID"),
                          'UpdatedDate'  => date('Y-m-d h:i:s')
                      );
                }
                $this->systemoption_model->upateSystemOption($systemOptionData);
                $this->session->set_flashdata('SystemOptionMSG', $this->lang->line('success_update'));
        }
        $data['SystemOptionList'] = $this->systemoption_model->getSystemOptionList();
        $this->load->view(ADMIN_URL.'/system_option',$data);
    }
}