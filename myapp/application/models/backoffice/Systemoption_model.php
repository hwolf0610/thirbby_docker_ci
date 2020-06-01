<?php
class systemoption_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }
    function getSystemOptionList()
    {
        return $this->db->get('system_option')->result();
    }
    function upateSystemOption($systemOptionData)
    {
        $this->db->update_batch('system_option', $systemOptionData, 'SystemOptionID');
    }

}
?>