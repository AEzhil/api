<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroup extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->lang->load('account');
        $this->load->library('email');
        $this->validate_token();
      //  $this->load->model('SC_User', 'user');
        $this->load->model('SC_Usergroup', 'usergroup');
    }
    
    public function creatGroup_post() {
        // To create groupName
        $postData = $this->post();
        //print_r($postData);return;
        $this->form_validation->set_data($postData);
        if ($this->form_validation->run('creatGroup')) {
        	$insertgroupid = $this->usergroup->creatGroup($postData,$this->user->user_id);
            //print_r($insertgroupid);return;
            if(!empty ($insertgroupid)) {
            	
            	$response ['status'] = "success";
            	$response ['message'] = "Group created successfully.";
            	$newGroup=$this->usergroup->getGroupById($insertgroupid);
            	$response["newGroup"] = $newGroup;
            	   $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
               
            	$response ['status'] = "error";
            	$response ['message'] = $this->lang->line('invalid_group');            	
                $this->set_response($response, REST_Controller::HTTP_OK);
            }
        } else {
            // form validation errors
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }
    
    public  function addGroupMember_post()
    {
    	$postData = $this->post();
    	$this->form_validation->set_data($postData);
    	if($this->form_validation->run('addmember'))
    	{
    		$userData = $this->usergroup->addGroupMember($postData,$this->user->user_id);
    		if(!empty ($userData))
    		{
    			$response ['status'] = "success";
    			$response ['message'] = "Group member added successfully.";
    			$newGroup=$this->usergroup->getUserDetails($postData["selectedUser"]);
    			$response["newMember"] = $newGroup;    			 
    			$this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    		} else {
    			
    			$response ['status'] = "error";
    			$response ['message'] = $this->lang->line('invalid_groupmember');
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		}
    		} else {
    			// form validation errors
    			$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
    		}
    	}
    	 
    
    
    public function getUserGroup_get()
    {
    	// To get user group list
    	$Latest = $this->usergroup->getUserGroup($this->user->user_id);
    
    	$this->set_response($Latest, REST_Controller::HTTP_OK);
    
    }
    
    public function getGroupMember_post()
    {
    	// To get group member list
    	$groupId = $this->post('groupID');
    	
    	$Latest = $this->usergroup->getGroupMember($groupId);
    
    	$this->set_response($Latest, REST_Controller::HTTP_OK);
    
    }
    
    public function sentGroupMessage_post()
    {
    	//To post message into group
    	$postData = $this->post();
    	
    	$this->form_validation->set_data($postData);
    	if ($this->form_validation->run('groupMessage')) {
    		$insertgroupmessage = $this->usergroup->groupMessage($postData,$this->user->user_id);
        		if(!empty ($insertgroupmessage)) {
    		    $userdetails = $this->usergroup->getUserDetails($this->user->user_id);
    		    $insertgroupmessage -> user = $userdetails;
     			$response= array (
     					'status' => "success",
     					'recMessage' =>$insertgroupmessage
/*      					"userName" => $userdetails->user_id,
  					    "image" => $userdetails->image,
     					"Message"=>$insertgroupmessage->chat_description,
    					"Time" => $insertgroupmessage->cdt
 */     			);
    			
    			
    			$this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    		} else {
    			 
    			$response ['status'] = "error";
    			$response ['message'] = $this->lang->line('invalid_group');
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		}
    	} else {
    		
    		$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
    	}
    }
    
    public function getGroupMessage_post()
    {
    	$groupId= $this->post('groupId');
    	
    	if(!empty($groupId))
    	{
    	$Groupmessage = $this->usergroup->getGroupMessage($groupId);
    	
    	if(!empty($Groupmessage))
    	{
    	
    		for ($i=0; $i< count($Groupmessage); $i++)
    		{
    			$commentdetails = $Groupmessage[$i];
    			$details = $this->usergroup->getUserDetails($commentdetails->sent_id);
    			$commentdetails->user = $details;
    		}
    	
    			$this->set_response($Groupmessage, REST_Controller::HTTP_OK);
    	}
    	
    	
    }
    else
    {
    	$this->set_response($this->lang->line('word_requried'), REST_Controller::HTTP_EXPECTATION_FAILED);
    }
    }
    
    public function groupProfile_post()
    {
    	$groupId= $this->post('groupId');
    	if(!empty($groupId))
    	{
    		$Groupinfo = $this->usergroup->groupProfile($groupId);
    		 
    		   			 
    			$this->set_response($Groupinfo, REST_Controller::HTTP_OK);
    	
    		 
    		 
    	}
    	else
    	{
    		$this->set_response($this->lang->line('word_requried'), REST_Controller::HTTP_EXPECTATION_FAILED);
    	}
    }
    
     
   
}
?>
