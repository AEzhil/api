<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chatcontroller extends MY_Controller {

	function __construct()
	{
		// Construct the parent class
		parent::__construct();

		// Controller intializations
		$this->validate_token();
		$this->lang->load('account');
		$this->load->library('email');
		//$this->user->user_id = "bala";
		//  $this->load->model('SC_User', 'user');
		$this->load->model('SC_Chatmodel', 'chatmodel');
	}
	
	
	public function getContacts_get()
	{
		
		// To get user group list
		$Latest = $this->chatmodel->getUserdetails($this->user->user_id);
	
		$this->set_response($Latest, REST_Controller::HTTP_OK);
	
	}
	
	public function sentchatMessage_post()
	{
		//To post message into group
		$postData = $this->post();
			
		$this->form_validation->set_data($postData);
		if ($this->form_validation->run('postMessage')) {
			$insertchatpmessage = $this->chatmodel->sentchatMessage($postData,$this->user->user_id);
			if(!empty ($insertchatpmessage)) {
				$userdetails = $this->chatmodel->getUserdetailsByid($this->user->user_id);
				$insertchatpmessage -> user = $userdetails;
				$response= array (
						'status' => "success",
						'recMessage' =>$insertchatpmessage
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
	


	public function getChatMessage_post()
	{
		$chatId= $this->post("chatuserId");
		$user_id = $this->user->user_id;
		//print_r($user_id);return;
		if(!empty($chatId))
		{
			$response ['sent_id'] = $chatId;
			$Chatmessage = $this->chatmodel->getChatMessage($chatId,$user_id);
			
			if(!empty($Chatmessage))
			{
				 
				for ($i=0; $i< count($Chatmessage); $i++)
				{
					$commentdetails = $Chatmessage[$i];
					$details = $this->chatmodel->getUserdetailsByid($commentdetails->sent_id);
					$commentdetails->user = $details;
				}
			}
			$response ['status'] = "success";
			$response ['recMessage'] = $Chatmessage;
			
			$this->set_response($response, REST_Controller::HTTP_OK);
				
			 
		}
		else
		{
			$this->set_response($this->lang->line('word_requried'), REST_Controller::HTTP_EXPECTATION_FAILED);
		}
	}

	

	 
	 
}



?>