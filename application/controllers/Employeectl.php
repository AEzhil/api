<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Employeectl extends MY_Controller {
	function __construct() {
		// Construct the parent class
		parent::__construct ();
		
		// Controller intializations
		$this->validate_token ();
		// $this->lang->load('dashboard');
		$this->load->model ( 'SC_Employeemdl', 'employee' );
		$this->load->model ( 'SC_Grading', 'grading' );
	}
	public function uploadDocument_post() {
		$postData =  $this->post ();
        	$document_type = $postData['document_type'];
		$document_id = $postData['document_id'];
		if (! empty ( $_FILES )) {
			$postData ["id"] = $this->user->id;
			$user_id = $this->user->user_id;
			// $today = date("Y_m_d_H_i_s");
			$files = $_FILES ['file'];
			$Details = $this->employee->uploadDocument ( $document_id, $document_type, $files, $user_id );
			if ($Details) {
				$response ["message"] = "Document added successfully ";
				$response ['status'] = "success";
				$this->set_response ( $response, REST_Controller::HTTP_OK );
			} else {
				$response ['status'] = "error";
				$response ["message"] = "Something went to wrong while upload files....";
				$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
			}
	
	
		}
	}

	public function viewWorkpermit_post() {
		$postData ["user_id"] = $this->user->user_id;
		// $value = $this->post();
		// print_r($postData); return;
		$workpermitDetails = $this->employee->viewWorkpermitInfo ( $postData );
		for($i = 0; $i < count ( $workpermitDetails ); $i ++) {
			$wpDetails = $workpermitDetails [$i];
			$wpDetails->wpType = $this->grading->getSubCategoryById ( $wpDetails->workpermit_type )->name;
			
			
		}
		// $dashboardDetails = $this->dashboard->dashboardInfo();
		$response ["WorkpermitInfo"] = $workpermitDetails;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function viewWorkpermitByid_post() {
		$value = $this->post ();
		// $value["user_id"]= $this->user->user_id;
		// print_r($value); return;
		$workpermitDetails = $this->employee->viewWorkpermitByid ( $value );
		$workpermitDetails->fileList = $this->employee->getFileListById ( $value, "Workpermit" );
		// $categoryDetails = $this->grading->getCategory();
		// $visaTypeList = $this->grading->getSubCategory(2); //2 for visa
		
		// $workpermitDetails->category = $categoryDetails;
		// $workpermitDetails->visaTypeList = $visaTypeList;
		// $dashboardDetails = $this->dashboard->dashboardInfo();
		$response ["WorkpermitInfo"] = $workpermitDetails;
		// $response["visaTypeList"] = $visaTypeList;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function addWorkpermit_post() {
		//$user_id = $this->user->id;
		//$user_name = $this->user->user_id;
		$postData = json_decode ( $this->post ( 'WorkpermitInfo' ), true );
		$this->form_validation->set_data ( $postData );
		if ($this->form_validation->run ( 'addWorkpermit' ) && ! empty ( $_FILES )) {
			$postData ["id"] = $this->user->id;
			$postData ["user_id"] = $this->user->user_id;
			// $today = date("Y_m_d_H_i_s");
			$files = $_FILES ['file'];
			$response = $this->employee->addworkpermitInfo ( $postData, $files );
			
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		
		}
		 else {
                                              $response ['status'] = "error";
				$response ['message'] =  "Please select at least one document for upload.";

				$this->set_response ($response, REST_Controller::HTTP_OK );
			}
	}

	public function updateWorkpermit_post() {
		$postData = $this->post ();
		// print_r($postData);return;
		$this->form_validation->set_data ( $postData );
		if ($this->form_validation->run ( 'updateWorkpermit' )) {
			// $postData["id"]= $this->user->id;
			// $postData["user_id"]= $this->user->user_id;
			// print_r($postData);return;
			$WorkpermitDetails = $this->employee->updateWorkpermit ( $postData );
			if ($WorkpermitDetails) {
				
				$response ['status'] = "success";
				$response ['message'] = "Updated successfully";
				$response ['WorkpermitDetails'] = $WorkpermitDetails;
			} else {
				$response ['status'] = "error";
				$response ['message'] = "Error";
				$response ['WorkpermitDetails'] = $WorkpermitDetails;
			}
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		} else {
			$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
		}
	}
	
	
	
	// ---------------------------------------Passport Details ---------------------------------------------------
	public function viewPassportdetails_post() {
		$postData ["user_id"] = $this->user->user_id;
		// $value = $this->post();
		// print_r($postData); return;
		$passportDetails = $this->employee->viewPassportdetails ( $postData );
		// $dashboardDetails = $this->dashboard->dashboardInfo();
		$response ["PassportInfo"] = $passportDetails;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function viewPassportdetailsByid_post() {
		$value = $this->post ();
		// $value["user_id"]= $this->user->user_id;
		// print_r($value); return;
		$passportDetails = $this->employee->viewPassportdetailsByid ( $value );
		$passportDetails->fileList = $this->employee->getFileListById ( $value, "Passport" );
		// $dashboardDetails = $this->dashboard->dashboardInfo();
		$response ["PassportDetails"] = $passportDetails;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function addPassportdetails_post() {
		$postData = json_decode ( $this->post ( 'passportInfo' ), true );
		//print_r ( $postData );
		//print_r ( $_FILES );
		//return;
		$this->form_validation->set_data ( $postData );
		// print_r($postData);return;
		if ($this->form_validation->run ( 'addPassportdetails' ) && ! empty ( $_FILES )) {
			$postData ["id"] = $this->user->id;
			$postData ["user_id"] = $this->user->user_id;
			$files = $_FILES ['file'];
			$response = $this->employee->addPassportdetails ( $postData , $files);
			
			
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		} else {
			$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
		}
	}
	public function updatePassportdetails_post() {
		$postData = $this->post ();
		// print_r($postData);return;
		$this->form_validation->set_data ( $postData );
		if ($this->form_validation->run ( 'updatePassportdetails' )) {
			// $postData["id"]= $this->user->id;
			// $postData["user_id"]= $this->user->user_id;
			// print_r($postData);return;
			$passportDetails = $this->employee->updatePassportdetails ( $postData );
			if (is_numeric ( $passportDetails )) {
				
				$response ['status'] = "success";
				$response ['message'] = "Successfuly Updated";
				$response ['Passport Details'] = $passportDetails;
			} else {
				$response ['status'] = "error";
				$response ['message'] = $passportDetails;
			}
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		} else {
			$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
		}
	}
	
	// ---------------------------------------------I94 Details ------------------------------------------------
	public function viewI94Details_post() {
		$postData ["user_id"] = $this->user->user_id;
		// $value = $this->post();
		// print_r($postData); return;
		$viewI94Details = $this->employee->viewI94Details ( $postData );
		
		$response ["I94Info"] = $viewI94Details;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function viewI94DetailsByid_post() {
		$value = $this->post ();
		// $value["user_id"]= $this->user->user_id;
		// print_r($value); return;
		$viewI94Details = $this->employee->viewI94DetailsByid ( $value );
		$viewI94Details->fileList = $this->employee->getFileListById ( $value, "I94" );
		// $dashboardDetails = $this->dashboard->dashboardInfo();
		$response ["I94Info"] = $viewI94Details;
		$this->set_response ( $response, REST_Controller::HTTP_OK );
	}
	public function addI94Details_post() {
		$postData = json_decode ( $this->post ( 'I94Info' ), true );
		//print_r ( $postData );
		//print_r ( $_FILES );
		//return;
		// print_r($postData);return;
		$this->form_validation->set_data ( $postData );
		// print_r($postData);return;
		if ($this->form_validation->run ( 'addI94Details' ) && ! empty ( $_FILES )) {
			$postData ["id"] = $this->user->id;
			$postData ["user_id"] = $this->user->user_id;
			$files = $_FILES ['file'];
			$response = $this->employee->addI94Details ( $postData , $files);
			
			/*if ($I94Details->status == 'success') {
				$response ["message"] = "Details added successfully ";
				$response ['status'] = "success";
			} else if ($I94Details == 'error') {
				$response ['status'] = "error";
				$response ["message"] = "Error";
			}*/
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		} else {
			$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
		}
	}
	public function updateI94Details_post() {
		$postData = $this->post ();
		// print_r($postData);return;
		$this->form_validation->set_data ( $postData );
		if ($this->form_validation->run ( 'updateI94Details' )) {
			// $postData["id"]= $this->user->id;
			// $postData["user_id"]= $this->user->user_id;
			// print_r($postData);return;
			$I94Details = $this->employee->updateI94Details ( $postData );
			if (is_numeric ( $I94Details )) {
				
				$response ['status'] = "success";
				$response ['message'] = "Successfuly Updated";
				$response ['I94 Details'] = $I94Details;
			} else {
				$response ['status'] = "error";
				$response ['message'] = $I94Details;
			}
			$this->set_response ( $response, REST_Controller::HTTP_OK );
		} else {
			$this->set_response ( getErrorMessages ( validation_errors () ), REST_Controller::HTTP_EXPECTATION_FAILED );
		}
	}
}