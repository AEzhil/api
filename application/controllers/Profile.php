<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->validate_token();
        $this->lang->load('profile');
        $this->load->model('SC_Profile', 'profile');
        $this->load->model('SC_Grading', 'grading');
        $this->load->model('SC_User', 'usermodel');
    }
    
    public function logout_post()
    {
    	//To get Logout user profile information

    	session_start();
    	//$_SESSION["userid"] = $this->user->id;
    	//print_r($_SESSION);
        
    	if(session_destroy())
    	{
    		$response = array(
    			"status" => "info",
    			"message" => "Logout Successfully."
    		);

    	}
    	else {
    		$response = array(
    				"status" => "error",
    				"message" => "Somthing Wrong"
    		);

    	}
        
        //unset($_SESSION["userid"]);

    	$this->set_response($response, REST_Controller::HTTP_OK);

    }
    public function profile_post() {

    //	$user_id = $this->post("user_id");
    	$loginuserID = $this->user->user_id;
    	$profileDetails = $this->profile->getUserDetails($loginuserID);
    	$userInfo = $this->profile->getProfile($loginuserID);
    	if($userInfo->country_id != null)
    	{
         	$userInfo->country = $this->grading->getCountryName($userInfo->country_id)->name;
    	}
    	else
    	{
          $userInfo->country = array();
       }
       
        if($userInfo->state_id != null)
        {
            $userInfo->state = $this->grading->getStateName($userInfo->state_id)->name;
        }
        else
    	{
          $userInfo->state = array();
       }


    //	$EduInfo = $this->profile->getEducationDetails($user_id);
      //  $tutortypeInfo = $this->grading->getTutortype();

    	$countryInfo = $this->grading->getCountryList();
    	$statesInfo = $this->grading->getStatesList(224);
    	//$districtInfo = $this->grading->getDistrictList(5);
    	//$schoolNameList = $this->grading->getSchoolList("All");

    	$subjectInfo = $this->grading->getCategory();
  //  	$gradeInfo = $this->grading->getGrades();


  //  	$grade = $this->profile->getGrades();
	$Category1 = $this->profile->getcategory();

/*	for ($i=0; $i< count($Category1); $i++)
	{
	    	$subcategory = $Category1[$i];
	   	$subject = $this->profile->getgradeById($subcategory->id,$this->user->user_id);
	    	$subcategory->subjects= $subject;
	}
        		    $educationInfo = array(

		    		"grades"=> $grade,
		    		"categories"=>$Category1

		    );
     */

    //	$response["profileInfo"] = $profileDetails;
    //	$response["tutortypeInfo"] = $tutortypeInfo;
    	$response["userInfo"] = $userInfo;
    //	$response ['schoolInfo'] = $schoolInfo;
    	//$response ['schoolInfo'] = $schoolDetails;
    	$response ['countryInfo'] = $countryInfo;
    	$response ['statesInfo'] = $statesInfo;
    //	$response ['districtInfo'] = $districtInfo;
    //	$response ['schoolNameList'] = $schoolNameList;
    //	$response ['subjectInfo'] = $subjectInfo;
   // 	$response ['gradeInfo'] = $gradeInfo;
    //	$response ['EduInfo'] = $EduInfo;
    //	$response ['educationInfo'] = $educationInfo;


    /*
    	if($user_id == $loginuserID)
    	{
    		$response["hideSave"] = true;
    	}
    	else
    	{
    		$response["hideSave"] = false;
    	}
    	*/
        $this->set_response($response, REST_Controller::HTTP_OK);
    }

//----------------------------------Updated User Informatiom -------------------------------------------------



      public function updateProfile_post() {
        $postData = $this->post();
        //print_r($postData);return;
        $this->form_validation->set_data($postData);
        if ($this->form_validation->run('updateUserInfo')) {
           $postData["id"]= $this->user->id;
           $postData["user_id"]= $this->user->user_id;
            //print_r($postData);return;
            $profile = $this->profile->updateUserInfo($postData);
            $response ['status'] = "success";
            $response ['message'] = $this->lang->line('userinfo_success');
            $response['profile'] = $profile;
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
}


    public function getCountryList_get() {
    	$CountryDetails = $this->grading->getCountryList();
    	$this->set_response($CountryDetails, REST_Controller::HTTP_OK);
   	}

    public function getStatesList_post() {

    	$country_id = $this->post('country_id');
   		$Details = $this->grading->getStatesList($country_id);
   		$this->set_response($Details, REST_Controller::HTTP_OK);
    }

    public function getDistrictList_post() {

    	$state_id = $this->post('state_id');
    	$Details = $this->grading->getDistrictList($state_id);
    	$this->set_response($Details, REST_Controller::HTTP_OK);
    }

    public function getSchoolList_post() {

    	$district_id = $this->post('district_id');
    	$Details = $this->grading->getSchoolList($district_id);
    	$this->set_response($Details, REST_Controller::HTTP_OK);
    }

    public function getCityCountyName_post() {

    	$school_id = $this->post('school_id');
    	//$Details = $this->grading->getCityCountyName($school_id);
    	$schoolDetails = $this->profile->getSchoolDetails($school_id);
    	$Details = array(
    			"city" => $this->grading->getCityName($schoolDetails->city_id),
    			"county" =>$this->grading->getCountyName($schoolDetails->county_id)
    	);


    	$this->set_response($Details, REST_Controller::HTTP_OK);
    }

    public function getAllUsersGroups_get()
    {
    	$usergroupDetails = $this->grading->getAllUsersGroups();
    	$this->set_response($usergroupDetails, REST_Controller::HTTP_OK);
    }

    public function saveUserInfo_put()
    {
    	$postData = $this->put();
    	//print_r($postData);return;
    	$this->form_validation->set_data($postData);
    	if ($this->form_validation->run('updateUserInfo')) {

    		$profile = $this->profile->updateUserInfo($postData);
    		$response ['status'] = "success";
    		$response ['message'] = $this->lang->line('userinfo_success');
    		$response['profile'] = $profile;
    		$this->set_response($response, REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
    	}
    }

    public function saveSchoolInfo_put()
    {
    	$postData = $this->put();
    	$userInfo = $this->profile->updateSchoolInfo($postData);
    	if($userInfo['school_id'] != 0)
    	{
    		$schoolDetails = $this->profile->getSchoolDetails($userInfo['school_id']);
    		$schoolInfo = array(
    				"id"=> $postData['id'],
    				"country" => $this->grading->getCountryName($schoolDetails->country_id),
    				"state" => $this->grading->getStateName($schoolDetails->state_id),
    				"district" => $this->grading->getDistrictName($schoolDetails->district_id),
    				"school" => $this->grading->getSchoolName($schoolDetails->school_id),
    				//"city" => $schoolDetails->city_id,
    				"city" => $this->grading->getCityName($schoolDetails->city_id),
    				"county" =>$this->grading->getCountyName($schoolDetails->county_id)

    		);
    		$response ['status'] = "success";
    		$response ['message'] = $this->lang->line('schoolinfo_success');
    		$response ['schoolInfo'] = $schoolInfo;
    		$this->set_response($response, REST_Controller::HTTP_OK);

    	}


    }

    public function saveEduInfo_post() {
    	// To add User Grades
    	$post_data = $this->post("eduInfo");
    	//print_r($post_data);return;
    	//$post_data['id']= $this->user->id;
    	$result = $this->profile->saveEduInfo($this->user->user_id, $post_data);
    	$response ['status'] = "success";
    	$response ['message'] = $this->lang->line('eduinfo_success');
    	$this->set_response($response, REST_Controller::HTTP_OK);
    }

    public function subjects_get()
    {
    	// To get sub Category list
    	$Category1 = $this->profile->getcategory();
    	for ($i=0; $i< count($Category1); $i++)
    	{
    		$subcategory = $Category1[$i];
    		$subject = $this->profile->getgradeById($subcategory->id,$this->user->user_id);
    		$subcategory->subjects= $subject;

    	}
    	$response = array(
    			"categories"=>$Category1

    	);
    	$this->set_response($Category1, REST_Controller::HTTP_OK);
    }

    public function subjectGrades_get()
        { // To get grade, Category details
		   // $grade = $this->profile->getGrades();
		    $Category1 = $this->profile->getcategory();


		    for ($i=0; $i< count($Category1); $i++)
		    {
		    	$subcategory = $Category1[$i];
		    	$subject = $this->profile->getgradeById($subcategory->id,$this->user->user_id);
		    	$subcategory->subjects= $subject;
		    }
		    $response = array(

		    		"grades"=> $grade,
		    		"categories"=>$Category1

		    );
		    $this->set_response($response, REST_Controller::HTTP_OK);
		    }

	  public function saveGrades_post() {
		    	// To add User Grades
		    	$post_data = $this->post();
		    	//$post_data['id']= $this->user->id;
		    	$result = $this->profile->saveGrades($this->user->user_id, $post_data);
		    		
		    	$this->set_response($this->lang->line('grades_saved'), REST_Controller::HTTP_OK);
		    }
		    
    
	  public function changePassword_post()
		    {
		    	$postData = $this->post();
		    	$postData["userId"]= $this->user->user_id;
		    	 
		    	if($postData)
		    	{
		    		$password = $this->usermodel->changePassword($postData);
		    /* echo $password; return ; */
		    	if($password == "Passwordchanged")
		    	{
		    		$response ['status'] = "success";
		    		$response ['message'] = $this->lang->line($password);
		    	}
		    	else {
		    		
		    		$response ['status'] = "error";
		    		$response ['message'] = $this->lang->line($password);
		    	}
		    			$this->set_response($response, REST_Controller::HTTP_OK);
		    	
		    
		    	}
		    	else {
		    		$this->set_response("Please Enter Old Password.", REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
		    	}
		    	 
		    }
    
    /* public function Postquestion_put()
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
    	
    	
    } */
    
}