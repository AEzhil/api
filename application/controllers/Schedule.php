<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
       ini_set('date.timezone', 'America/Los_Angeles');

        // Controller intializations
        $this->validate_token();
        $this->lang->load('dashboard');
        $this->load->model('SC_Schedule', 'schedule');
    
    }
    
 
    
    public function submitSchedule_post() {
    	// To authenticate user and return JWT token
    	$postData = $this->post();
    	//print_r($postData);return;
   		//$postData["user_id"] = $this->user->user_id;
   		$insertId = $this->schedule->submitSchedule($postData, $this->user->user_id);
   		//print_r($insertId);return;
    		if($insertId) {
    			$response ['status'] = "success";
    			$response ['message'] = "Schedule submitted successfully.";
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		} else {
    			$response ['status'] = "error";
    			$response ['message'] = "Appointment already scheduled or something went wrong";
    			 
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		}

    	
    	
    }
    
   
    public function getScheduleEvent_post() {
        // date_default_timezone_set("America/Los_Angeles");
        //$time =  Date('Y-m-d h:i:s');
       // print_r($time);return;
		$tutor_userid = $this->post("tutor_userid");
    	//print_r($tutor_userid);return;
   		$events = $this->schedule->getScheduleEvent($tutor_userid, "Accepted");
	/* 	$schdule_events = array();
		$i=0;
		foreach($events as $sss)
		{
			$schdule_date = $sss->schdule_date;
			$start_time = $sss->start_time;
			$end_time = $sss->end_time;
			
			$start = date("Y-m-d h:i", strtotime($schdule_date." ".$start_time));
			$end = date("Y-m-d h:i", strtotime($schdule_date." ".$end_time));
			
			

			$schdule_events[$i]["title"] = $sss->title;
			$schdule_events[$i]["start"] = $start;
			$schdule_events[$i]["end"] = $end;
			$i++;
		} */
		$this->set_response($events, REST_Controller::HTTP_OK);
    }
    
    public function getScheduleEventByUser_get() {
    	$tutor_userid = $this->user->user_id;
    	$acceptevents = $this->schedule->getScheduleEvent($tutor_userid, "Accepted");
    	$pendingevents = $this->schedule->getScheduleAllEvent($tutor_userid, "Pending");
    	$schdule_events = array("Accepted" => $acceptevents,
    			"Pending" => $pendingevents
    	);
    	
    	$this->set_response($schdule_events, REST_Controller::HTTP_OK);
    }
    
    public function approveAppointment_post() {
    	$postdata = $this->post();
    	$tutor_userid = $this->user->user_id;
    	$appointmentstatus = $this->schedule->approveAppointment($postdata);
    	//print_r($appointmentstatus);return;
    	if($appointmentstatus) {
    		$response ['status'] = "success";
    		$pendingevents = $this->schedule->getScheduleAllEvent($tutor_userid, "Pending");
    		$response["Pending"] = $pendingevents;
    		if($appointmentstatus["status"]=="Accepted")
    		{
    			$response ['message'] = "Schedule request accepted successfully.";
    		}
    		else if($appointmentstatus["status"]=="Rejected")
    		{
    			$response ['message'] = "Schedule request rejected successfully.";
    		}
    		$this->set_response($response, REST_Controller::HTTP_OK);
    	} else {
    		$response ['status'] = "error";
    		$response ['message'] = "something went wrong";
    	
    		$this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    	}
    }
    
    
    public function getRequestSchedule_get() {
    	$userid = $this->user->user_id;
    	$acceptevents = $this->schedule->getRequestSchedule($userid, "Accepted");
    	$pendingevents = $this->schedule->getRequestSchedule($userid, "Pending");
    	$schdule_events = array("Accepted" => $acceptevents,
    			"Pending" => $pendingevents
    	);
    	 
    	$this->set_response($schdule_events, REST_Controller::HTTP_OK);
    }
    
    
       public function updatestatus_post() {
		$value = $this->post("value");
		$id = $this->post("id");
    	$events1 = $this->schedule->updatestatus($value,$id);
		$this->set_response($events1, REST_Controller::HTTP_OK);
    }
 
    
    
    
    
}