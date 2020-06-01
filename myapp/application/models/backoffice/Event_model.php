<?php

class Event_model extends CI_Model {

    function __construct()

    {

        parent::__construct();		        

    }	

   //ajax view      

    public function getGridList($sortFieldName = '', $sortOrder = '', $displayStart = 0, $displayLength = 10)

    {

        if($this->input->post('restaurant') != ''){

            $this->db->like('res.name', $this->input->post('restaurant'));

        }

        if($this->input->post('name') != ''){

            $this->db->like('name', $this->input->post('name'));

        }

        if($this->input->post('user_name') != ''){

            $this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('user_name')."%'");

        }

        if($this->input->post('booking_date') != ''){

            $this->db->like('booking_date', $this->input->post('booking_date'));

        }

        if($this->input->post('end_date') != ''){

            $this->db->like('end_date', $this->input->post('end_date'));

        }

        if($this->input->post('amount') != ''){

            $this->db->like('event.amount', $this->input->post('amount'));

        }

        if($this->input->post('event_status') != ''){

            $this->db->like('event_status', $this->input->post('event_status'));

        }

        if($this->input->post('Status') != ''){

            $this->db->like('status', $this->input->post('Status'));

        }

        $this->db->select('event.*,res.name as rname,u.first_name as fname,u.last_name as lname');

        $this->db->join('restaurant as res','event.restaurant_id = res.entity_id','left');

        $this->db->join('users as u','event.user_id = u.entity_id','left'); 

        if($this->session->userdata('UserType') == 'Admin'){

            $this->db->where('res.created_by',$this->session->userdata('UserID'));  

        }        

        $result['total'] = $this->db->count_all_results('event');

        if($sortFieldName != '')

            $this->db->order_by($sortFieldName, $sortOrder);

        

        if($this->input->post('restaurant') != ''){

            $this->db->like('res.name', $this->input->post('restaurant'));

        }

        if($this->input->post('name') != ''){

            $this->db->like('name', $this->input->post('name'));

        }

        if($this->input->post('user_name') != ''){

            $this->db->where("CONCAT(u.first_name,' ',u.last_name) like '%".$this->input->post('user_name')."%'");

        }

        if($this->input->post('booking_date') != ''){

            $this->db->like('booking_date', $this->input->post('booking_date'));

        }

        if($this->input->post('end_date') != ''){

            $this->db->like('end_date', $this->input->post('end_date'));

        }

        if($this->input->post('amount') != ''){

            $this->db->like('event.amount', $this->input->post('amount'));

        }

        if($this->input->post('event_status') != ''){

            $this->db->like('event_status', $this->input->post('event_status'));

        }

        if($this->input->post('Status') != ''){

            $this->db->like('status', $this->input->post('Status'));

        }

        if($displayLength>1)

            $this->db->limit($displayLength,$displayStart); 

        $this->db->select('event.*,res.name as rname,u.first_name as fname,u.last_name as lname');

        $this->db->join('restaurant as res','event.restaurant_id = res.entity_id','left'); 

        $this->db->join('users as u','event.user_id = u.entity_id','left'); 

        if($this->session->userdata('UserType') == 'Admin'){

            $this->db->where('res.created_by',$this->session->userdata('UserID'));

        }     

        $result['data'] = $this->db->get('event')->result();        

        return $result;

    }  

    // method for adding

    public function addData($tblName,$Data)

    {   

        $this->db->insert($tblName,$Data);            

        return $this->db->insert_id();

    } 

    //get single data

    public function getEditDetail($entity_id)

    {

        $this->db->select('event.*,event_detail.package_detail,event_detail.restaurant_detail,event_detail.user_detail');

        $this->db->join('event_detail','event.entity_id = event_detail.event_id','left');

        return $this->db->get_where('event',array('event.entity_id'=>$entity_id))->first_row();

    }

    // update data common function

    public function updateData($Data,$tblName,$fieldName,$ID)

    {        

        $this->db->where($fieldName,$ID);

        $this->db->update($tblName,$Data);            

        return $this->db->affected_rows();

    }

    // updating the changed

    public function UpdatedStatus($tblname,$entity_id,$status){

        if($status==0){

            $userData = array('status' => 1);

        } else {

            $userData = array('status' => 0);

        }        

        $this->db->where('entity_id',$entity_id);

        $this->db->update($tblname,$userData);

        return $this->db->affected_rows();

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

            return $this->db->get($tblname)->result();

        }else{

            $this->db->select('name,entity_id,amount_type,amount,capacity,timings');

            $this->db->where('status',1);

            $result = $this->db->get($tblname)->result();

            foreach ($result as $key => $value) {

                $timing = $value->timings;

                //print_r($value->timings);

                if($timing){

                   $timing =  unserialize(html_entity_decode($timing));

                   $newTimingArr = array();

                    $day = date("l");

                    foreach($timing as $keys=>$values) {

                        $day = date("l");

                        if($keys == strtolower($day)){

                            $newTimingArr[strtolower($day)]['open'] = $values['open'];

                            $newTimingArr[strtolower($day)]['close'] = $values['close'];

                            $newTimingArr[strtolower($day)]['off'] = (!empty($values['open']) && !empty($values['close']))?'open':'close';

                        }

                    }

                }

                $value->timings = $newTimingArr[strtolower($day)];

            }

            return $result;

        }

    }

    public function getBookedDate(){

        $this->db->select('booking_date');

        $this->db->where('booking_date >=',date('Y-m-d H:i:s'));

        return $this->db->get('event')->result();

    }

    //get restaurant detail

    public function getRestuarantDetail($entity_id){

        $this->db->select('capacity');

        $this->db->where('entity_id',$entity_id);

        return $this->db->get('restaurant')->first_row();

    }

}

?>