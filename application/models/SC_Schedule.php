<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Schedule extends MY_Model {
	public function __construct() {
		parent::__construct ();
		                          date_default_timezone_set('America/Los_Angeles');
		// Model intilizations
		$this->_table = 'tutor_appointment';
		// $this->validate = $this->config->item('sdf');
		$this->load->library ( 'subquery' );
	}
	
	public function getRequestSchedule($userid, $status) {
		$this->_table = 'tutor_appointment';
		$this->db->select ( 'id,tutor_userid, title, DATE_FORMAT(schdule_date,  "%d-%b-%y") AS schdule_date, TIME_FORMAT(start_time,  "%H:%i") As start_time, TIME_FORMAT(end_time,  "%H:%i") As end_time, user_id, appiont_type, location, status' );
		$this->db->from ( $this->_table );
		$this->db->where("user_id", $userid);
		//$this->db->where("status", $status);
		return $this->db->get ()->result ();
	}
	
	public function approveAppointment($userData) {
		$this->_table = 'tutor_appointment';
		
	
		$user = array(
				'status' => $userData['status'],
				'accepted_date' => date("y-m-d H:i:s" , strtotime("now"))
		);
		//return  $userdata;
		if($this->update($userData["id"], $user)) {
			return $user;
		} else {
			return FALSE;
		}
		
	}
	
	public function getScheduleAllEvent($tutor_userid, $status) {
		$this->_table = 'tutor_appointment';
		$this->db->select ( 'id, title, DATE_FORMAT(schdule_date,  "%d-%b-%y") AS schdule_date, TIME_FORMAT(start_time,  "%H:%i") As start_time, TIME_FORMAT(end_time,  "%H:%i") As end_time, user_id, appiont_type, location, status' );
		$this->db->from ( $this->_table );
	//	$where_user = "(user_id = '".$tutor_userid."' OR tutor_userid = '".$tutor_userid."')";
		$this->db->where('tutor_userid',$tutor_userid);
		//	$this->db->or_where('user_id',$tutor_userid);
		//	$query = $this->db->last_query();
			//	print_r($query);
		//$this->db->where("status", $status);
		return $this->db->get ()->result ();
	}
	
	
		public function getScheduleAllrequestEven($tutor_userid, $status)
		{
		$this->_table = 'tutor_appointment';
		$this->db->select ( 'id, title, DATE_FORMAT(schdule_date,  "%d-%b-%y") AS schdule_date, TIME_FORMAT(start_time,  "%H:%i") As start_time, TIME_FORMAT(end_time,  "%H:%i") As end_time, user_id, appiont_type, location, status' );
		$this->db->from ( $this->_table );
		$this->db->where('user_id',$tutor_userid);
		//	$query = $this->db->last_query();
			//	print_r($query);
		//$this->db->where("status", $status);
		return $this->db->get ()->result ();
	       }
	
	
	public function getScheduleEvent($tutor_userid, $status) {
          

		$this->_table = 'tutor_appointment';
		$this->db->select ( 'title, CONCAT(schdule_date, " ", start_time) AS start, CONCAT(schdule_date, " ", end_time) AS end,id, if(tutor_userid ="'.$tutor_userid.'", "blue", "orange") as color' );
		//$this->db->select ( 'title, schdule_date, CONVERT_TZ(CONCAT(schdule_date, " ", start_time),"+00:00","+11:30") AS start, CONCAT(schdule_date, " ", end_time) AS end,id, if(tutor_userid ="'.$tutor_userid.'", "blue", "orange") as color' );
		$this->db->from ( $this->_table );
		$this->db->group_start();
		$this->db->where("tutor_userid", $tutor_userid);
		if($status == "Accepted")
		{
		    $this->db->or_where('user_id',$tutor_userid);
		}
		$this->db->group_end();
		$this->db->where("status", $status);
		
		$result = $this->db->get ()->result ();
		//$query = $this->db->last_query();
		//print_r($query);
        return $result;
	}
	
	
	public function submitSchedule($userData, $user_id) {
		$this->_table = "tutor_appointment";
		//print_r($userData);return;
		for($i=0; $i<count($userData); $i++) 
		{
		    if(!isset( $userData[$i]['locaiton']))
			{
				$userData[$i] ['locaiton'] = "";
			}
			
			$where_user = "((user_id = '".$user_id."' OR user_id = '".$userData[$i] ['tutor_name']."') OR (tutor_userid = '".$user_id."' OR tutor_userid = '".$userData[$i] ['tutor_name']."'))";


            $current_time = $userData[$i]['start_time'];
		    $this->db->select ( 'id' );
	     	$this->db->from ($this->_table );
	    	$this->db->where ( 'schdule_date', date('Y-m-d',strtotime($userData[$i] ['req_date'])));
			$this->db->where ( "'$current_time ' BETWEEN start_time and end_time");
			$this->db->where ($where_user);
			$this->db->where ('status != "Cancelled"' );
			$result = $this->db->get ()->result ();
			//$query = $this->db->last_query();
			//	print_r($query);
			//$this->db->trans_begin();
			//print_r($result);return;
			if (count($result)> 0) {
				$message = "Appoinment Already Fixed";
				$stat = 0;
				break;
			} else {
		
/*		 $dt_obj = new DateTime($userData[$i] ['req_date']." ".$userData[$i]['start_time'] ." GMT+5:00");
            $dt_obj->setTimezone(new DateTimeZone('America/Los_Angeles'));
             echo $formatted_date_long=date_format($dt_obj, 'Y-m-d H:i:s');
                                  return;
        */
    			$data[$i] = array (
    					'tutor_userid' => $userData[$i] ['tutor_name'],
    					'title' => $userData[$i]['title'],
    					'schdule_date' => date('Y-m-d',strtotime($userData[$i] ['req_date'])),
    					'user_id' => $user_id,
    					'start_time' => $userData[$i]['start_time'],
    					'end_time' => $userData[$i] ['end_time'] ,
    					'appiont_type' => $userData[$i] ['appointment'] ,
    					'location' => $userData[$i] ['locaiton'],
    					'req_date' => date("y-m-d H:i:s" , strtotime("now")),
    					'status' => "Pending"
    			);
    								
			
                 $stat = 1;
        	}
	
		  
	    }
	      if ($stat==0)
            {
                return  $stat;
            }   
            else
            {
                 $this->db->insert_batch($this->_table, $data);
                 return  $stat;
            }
	}
	
		public function updatestatus($userData,$id) {
		    
		$this->_table = 'tutor_appointment';
		if($userData == 1)
		{
	
		$user = array(
				'status' => "Cancelled",
				'accepted_date' => date("y-m-d H:i:s" , strtotime("now"))
		);
		//return  $userdata;
		if($this->update($id, $user)) {
			return $user;
		} else {
			return FALSE;
		}
		}
		elseif($userData == 0)
		{
		$where_array = array(
                'id'=>$id
            );


		if($this->db->delete('tutor_appointment', $where_array)) {
			return $where_array;
		} else {
			return FALSE;
		}	}
		
	}

	
	
}