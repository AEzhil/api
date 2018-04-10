<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->validate_token();
        $this->lang->load('dashboard');
        $this->load->model('SC_Dashboard', 'dashboard');
        $this->load->model('SC_Grading', 'grading');
        $this->load->model('SC_Usergroup', 'usergroup');
    }
    
    public function dashboard_post() {
     	$value = $this->post();
        //print_r($value); 	return;
      	$dashboardDetails = $this->dashboard->dashboardInfo($value);
    //	$dashboardDetails = $this->dashboard->dashboardInfo();
    	$response["DashboardInfo"] = $dashboardDetails;
        $this->set_response($response, REST_Controller::HTTP_OK);
    }
  
    public function getRatingVolunteerHours_get() {
    	$ratingDetails = $this->dashboard->getoverallRating($this->user->user_id);
    	$volunteerHoursDetails = $this->dashboard->getvolunteerHours($this->user->user_id);
    	 $response = array( "userRating" => $ratingDetails->userRating,
    	 		"volunteerHours" => $volunteerHoursDetails->volunteerHours);

    	$this->set_response($response, REST_Controller::HTTP_OK);
    }


    public function getoverallRating_get() {

    	$ratingDetails = $this->dashboard->getoverallRating($this->user->user_id);

    	$this->set_response($ratingDetails, REST_Controller::HTTP_OK);
    }

    public function getvolunteerHours_get() {

    	$volunteerHoursDetails = $this->dashboard->getvolunteerHours($this->user->user_id);

    	$this->set_response($volunteerHoursDetails, REST_Controller::HTTP_OK);
    }

   public function getQuestionNotify_get()
    {
    	$NotificationCount = $this->dashboard->getQuesNoti($this->user->user_id);
    	$scheduleNotification = $this->dashboard->getScheduleNotification($this->user->user_id);
    	$response = array( "NotificationCount" => $NotificationCount,
    			"scheduleNotification"=>$scheduleNotification
    	);
    	$this->set_response($response, REST_Controller::HTTP_OK);
    }

    public function Viewquestion_post() {
    	$questionId = $this->post('questionID');
    	if(is_null($questionId) || !is_numeric($questionId)) {
    		$this->set_response($this->lang->line('question_error'), REST_Controller::HTTP_EXPECTATION_FAILED);
    	} else {

    		$questionDetails = $this->dashboard->Viewquestion($questionId, $this->user->user_id);
    		$userDetails = $this->grading->getUserDetails($questionDetails->user_id);
   			$category = $this->grading->getSubjectById($questionDetails->subject_id);
   			$subcategory = $this->grading->getSubCategoryById($questionDetails->sub_subject_id);
   			$comments = $this->dashboard->getComments_ByQuestionID($questionId, NULL);
   			
    		for ($i=0; $i< count($comments); $i++)
   			{
   				$commentdetails = $comments[$i];
   				$commentuserDetails = $this->grading->getUserDetails($commentdetails->user_Id);
   				$commentdetails->user = $commentuserDetails;
   				$commentdetails->ratingShow = (($commentdetails->user_Id == $questionDetails->user_id) ? false : true);
   				$commentdetails->ratingDisabled = (($questionDetails->user_id == $this->user->user_id) ? false : true);
   				
   				
   				
   				$recomments = $this->dashboard->getComments_ByQuestionID($questionId, $commentdetails->id);
   				for ($j=0; $j< count($recomments); $j++)
   				{
   					$recommentdetails = $recomments[$j];
   					$recommentuserDetails = $this->grading->getUserDetails($recommentdetails->user_Id);
   					$recommentdetails->user = $recommentuserDetails;
   					$recommentdetails->ratingShow = (($recommentdetails->user_Id == $questionDetails->user_id) ? false : true);
   					$recommentdetails->ratingDisabled = (($questionDetails->user_id == $this->user->user_id) ? false : true);
   				}
   				$commentdetails->recomments = $recomments;
   			}
    		$response = array(
    				"id"=> $questionDetails->id,
    				"postedDate"=>$questionDetails->postedDate,
    				"user" => $userDetails,
    				"title"=> $questionDetails->title,
    				"description" => $questionDetails->description,
    				"category" => $category,
    				"subCategory"=> $subcategory,
    				"comments" => $comments
    				
    		);
    		$this->set_response($response, REST_Controller::HTTP_OK);
    		
    	}
    	 
    }
    
    public function addComment_put() {
    	// To authenticate user and return JWT token
    	$postData = $this->put();
    	//print_r($postData);return;
    	$this->form_validation->set_data($postData);
    	if ($this->form_validation->run('commentForm')) {
    		$postData["user_id"] = $this->user->user_id;
    		$insertId = $this->dashboard->addComment($postData);
    		if($insertId) {
    			$response ['status'] = "success";
    			$response ['message'] = $this->lang->line('comment_success');
    			 
    			$comments = $this->dashboard->getComments_ByCommentID($insertId);
    			$commentuserDetails = $this->grading->getUserDetails($comments->user_Id);
    			$comments->user = $commentuserDetails;
    			$comments->recomments = array();
    			$response ['comments'] = $comments;
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		} else {
    			$response ['status'] = "error";
    			$response ['message'] = $this->lang->line('comment_error');
    			 
    			$this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    		}

    	} else {
    		// form validation errors
    		$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_OK);
    	}
    	 
    	
    }
    
    public function ratingComment_put() {
    	$postData = $this->put();
    	
    	$this->form_validation->set_data($postData);
    	if ($this->form_validation->run('ratingForm')) {
    		$postData["user_id"] = $this->user->user_id;
    		$updateRating = $this->dashboard->ratingComment($postData);
	   		if($updateRating) {
    			$response ['status'] = "success";
    			$response ['message'] = $this->lang->line('rating_success');
    			$response ['rating'] = $updateRating;
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		} else {
    			$response ['status'] = "error";
    			$response ['message'] = $this->lang->line('rating_error');
    	
    			$this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    		} 
    	
    	} else {
    		// form validation errors
    		$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_OK);
    	}
    	 
    }

    public function getCategory_get() {
    	$categoryDetails = $this->grading->getCategory();
    	$this->set_response($categoryDetails, REST_Controller::HTTP_OK);
   	}
    
    public function getSubCategory_post() {

    	$categoryId = $this->post('categoryId');
   		$subcategoryDetails = $this->grading->getSubCategory($categoryId);
   		$this->set_response($subcategoryDetails, REST_Controller::HTTP_OK);
    }
    
    public function getAllUsersGroups_get()
    {
    	$usergroupDetails = $this->grading->getAllUsersGroups();
    	$this->set_response($usergroupDetails, REST_Controller::HTTP_OK);
    }
    
    public function getTutoruser_post() {
    	$postData = $this->post();
    	$postData["user_id"] = $this->user->user_id;
    	if(!isset($postData["subjects"]))
    	{
    		$postData["subjects"]="";
    		
    	}
    	if(!isset($postData["subsubjects"]))
    	{
    		$postData["subsubjects"]="";
    	
    	}
    	
    	$tutorDetails = $this->dashboard->gettutoruser($postData);
    	//echo count($tutorDetails);
    	//print_r($tutorDetails);
  	//return;
    	if(count($tutorDetails) > 0)
    	{
    		for ($i=0; $i< count($tutorDetails); $i++)
    		{
    			$commentdetails = $tutorDetails[$i];
    			$details = $this->dashboard->getUserimage($commentdetails->user_id);
    			$commentdetails -> Image = $details->image;
    		}
    	}
    	//$response['tutorDetails'] = $tutorDetails;
    	$this->set_response($tutorDetails, REST_Controller::HTTP_OK);
    	
    }
    
    public function Postquestion_put()
    {
    	// To post question
    	$postData = $this->put();
    	$this->form_validation->set_data($postData);
    	
    	if ($this->form_validation->run('postquestion')) {
    		$postData['user_id'] = $this->user->user_id;
	   		$insertId = $this->dashboard->Postquestion($postData);
    		if($insertId) {
    			$response ['status'] = "success";
    			$response ['message'] = $this->lang->line('postquestion_success');
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		} else {
    			$response ['status'] = "error";
    			$response ['message'] = $this->lang->line('postquestion_error');
    			$this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
    		}
    	} else {
    		// form validation errors
    		$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
    	}
    	
    	
    }
    //------------------------------Tutor Type--------------------- ------
  
    public function getTutortype_get() {
    	$Tutortype = $this->grading->getTutortype();
    	
    	array_unshift($Tutortype,array ("id" => "0","name"=>"All")); 
    	$this->set_response($Tutortype, REST_Controller::HTTP_OK);
    }
    //-----------------------------Resources------------------------------
    
     public function resources_get() {
    	$resourceDetails = $this->dashboard->resources();
          $this->set_response($resourceDetails, REST_Controller::HTTP_OK);
    }
    
}