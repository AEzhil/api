<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Employeectl extends MY_Controller {
	function __construct() {
		// Construct the parent class
		parent::__construct ();
		
		// Controller intializations
		//$this->validate_token ();
		// $this->lang->load('dashboard');
		$this->load->model ( 'SC_Employeemdl', 'employee' );
		//$this->load->model ( 'SC_Grading', 'grading' );
	}
	public function login_post()
	{
         $postData = $this->post();
         if($postData)
         {
         $response = $this->employee->logintest($postData);
            }
            else
            {
                $response["Error"]="Not found";
            }
              $this->set_response($response, REST_Controller::HTTP_OK);
	}

	public function event_get()
	{
       //  $postData = $this->post();
      //   if($postData)
         
         $data = $this->employee->eventget();
        // 0print_r($data[0]); return;
         for($i=0; $i<count($data); $i++)
         {
         	//print_r($data[$i]); 
         	$response[$data[$i]->new][]=$data[$i];
         	
         }
           
              $this->set_response($response, REST_Controller::HTTP_OK);
	}

}
?>