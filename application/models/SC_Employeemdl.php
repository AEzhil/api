<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Employeemdl extends MY_Model {
	public function __construct() {
		parent::__construct ();
		
		// Model intilizations
		$this->_table = 'postquestion';
		// $this->validate = $this->config->item('sdf');
		$this->load->library ( 'subquery' );
	}
	public static function AlphaNumeric($length)
	{
		$chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$clen   = strlen( $chars )-1;
		$id  = '';
	
		for ($i = 0; $i < $length ; $i++) {
			$id .= $chars[mt_rand(0,$clen)];
		}
		return ($id);
	}
	
		public function getFileListById($userdata, $doc_type)
	{
		$subject =  $_SERVER['SCRIPT_NAME'];
		$subArr = explode("/", $subject);
		//array_pop($subArr);
		$subArr = array_slice($subArr, 0, -2);
		$path = implode("/",$subArr);
		$path = "http://".$_SERVER['SERVER_ADDR'].$path."";
		
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		if($doc_type == "Workpermit")
		{
  		 $this->_table = "workpermitfile_tbl";
                }
                else if($doc_type == "I94")
                {
  		    $this->_table = "i94file_tbl";
                }
                else if($doc_type == "Passport")
                {
  		    $this->_table = "passportfile_tbl";
                }
		$result = $this->db->select ( 'id,user_id,CONCAT("'.$path.'", filepath) as filepath,filename' )->from ( $this->_table )->where ( 'file_id', $userdata ["id"] )->get ()->result ();
		
		return $result;
	}
	public function uploadDocument($document_id, $document_type, $files, $user_id) {

		if($document_type == "Workpermit")
		{
			$this->_table = 'workpermit_tbl';
			$path = '../assets/document/workprimit/';
			$filepath = '/assets/document/workprimit/';
			$fileTable = "workpermitfile_tbl";
			$filename = "wp_";
		}
		else if($document_type == "I94")
		{
			$this->_table = 'i94details_tbl';
			$path = '../assets/document/I94files/';
			$filepath = '/assets/document/I94files/';
			$fileTable = "i94file_tbl";
			$filename = "i94_";
		}
		else if($document_type == "Passport")
		{
			$this->_table = 'passport_tbl';
			$path = '../assets/document/passport/';
			$filepath = '/assets/document/passport/';
			$fileTable = "passportfile_tbl";
			$filename = "pp_";
		}

		$result = $this->db->select ( 'user_id' )->from ( $this->_table )->where ( 'id', $document_id )->get ()->row ();
		// print_r($result);return;
		$userData = "error";
		if ($result) {
			
			if (! empty ( $files )) {
				for ($i=0; $i < count($files ['name']); $i++ ) {
					//$imagename = $files['name'][$i];
					$filetype = $files['type'][$i];
					if($filetype == "application/pdf")
					{
						$filetype= ".pdf";
					}
					$filename .= $this->AlphaNumeric(8).$filetype;
					$path .= $filename;
					$filepath .= $filename;
					$tmp_name = $files['tmp_name'][$i];
					
					if(move_uploaded_file($tmp_name, $path))
					{
						$wpfile = array (
								// 'usermaster_id' => $userData ['usermaster_id'],
								'user_id' => $user_id,
								'file_id' => $document_id,
								'filepath' => $filepath,
								'filename' => $filename
						);
						$this->db->insert ( $fileTable, $wpfile );
						$userData = 'success';
						
					}
					else{
						$userData = "error";
					}
			
				}
			}
			else {
			$userData = "error";
		      }

		} else {
			$userData = "error";
			
		}
		return $userData;
		
	}

	public function addworkpermitInfo($userData, $files) {
		$this->_table = "workpermit_tbl";
		$user = array (
				// 'usermaster_id' => $userData ['usermaster_id'],
				'user_id' => $userData ['user_id'],
				'number' => $userData ['number'],
				'issue_date' => $userData ['issue_date'],
				'validity_FD' => $userData ['validity_FD'],
				'validity_TD' => $userData ['validity_TD'],
				'place_issue' => $userData ['place_issue'],
				'visa_type' => $userData ['visa_type'],
				'entries' => $userData ['entries'],
				'issuing_city' => $userData ['issuing_city'],
				'visa_number' => $userData ['visa_number'],
				'employer' => $userData ['employer'],
				'receipt_number' => $userData ['receipt_number'],
				'case_type' => $userData ["case_type"],
				'receipt_date' => $userData ['receipt_date'],
				'notice_date' => $userData ['notice_date'],
				'petitioner' => $userData ['petitioner'],
				'beneficiary' => $userData ['beneficiary'],
				'notice_type' => $userData ['notice_type'],
				'description' => $userData ['description'],
				// 'filepath' => $userData ['filepath'],
				'workpermit_type' => $userData ["workpermit_type"] 
		)
		;
		
		$this->db->insert ( 'workpermit_tbl', $user );
		
		$last_id = $this->db->insert_id ();


           if ($last_id) {
                        $upload_id = $this->uploadDocument($last_id, "Workpermit", $files, $userData ['user_id']);
                       
                        if($upload_id == "success")
                        {
                        	$response  = array('status' => 'success', 'message' => 'Details added successfully');	
                        }	
                        else if($upload_id == "error")
                        {
                        	$response  = array('status' => 'error', 'message' => 'Document upload failed.');	
                        }	
			return $response;
		
		
		}


		
		
	}
	

	
	public function viewWorkpermitInfo($userdata) {
		// print_r($userdata["user_id"]); return;
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "workpermit_tbl";
		$result = $this->db->select ( 'id,user_id,number,issue_date,validity_FD,validity_TD,place_issue,visa_type,entries,issuing_city,visa_number, employer,receipt_number,notice_date,petitioner,beneficiary,notice_type,description,filepath,workpermit_type' )->from ( $this->_table )->where ( 'user_id', $userdata ["user_id"] )->get ()->result ();
		
		return $result;
	}
	public function viewWorkpermitByid($userdata) {
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "workpermit_tbl";
		$result = $this->db->select ( 'id,user_id,number,issue_date,validity_FD,validity_TD,place_issue,visa_type,entries,issuing_city,visa_number, employer,receipt_date,receipt_number,notice_date,petitioner,beneficiary,notice_type,description,filepath,workpermit_type,case_type' )->from ( $this->_table )->where ( 'id', $userdata ["id"] )->get ()->row ();
		
		return $result;
	}
	public function updateWorkpermit($userData) {
		
		// print_r($userData);return;
		$this->_table = 'workpermit_tbl';
		$result = $this->db->select ( 'user_id' )->from ( 'workpermit_tbl' )->where ( 'id', $userData ["id"] )->get ()->row ();
		// print_r($result);return;
		if ($result) {
			$user = array (
					
					'number' => $userData ['number'],
					'issue_date' => $userData ['issue_date'],
					'validity_FD' => $userData ['validity_FD'],
					'validity_TD' => $userData ['validity_TD'],
					'place_issue' => $userData ['place_issue'],
					'visa_type' => $userData ['visa_type'],
					'entries' => $userData ['entries'],
					'issuing_city' => $userData ['issuing_city'],
					'visa_number' => $userData ['visa_number'],
					'employer' => $userData ['employer'],
					'receipt_number' => $userData ['receipt_number'],
					'case_type' => $userData ["case_type"],
					'receipt_date' => $userData ['receipt_date'],
					'notice_date' => $userData ['notice_date'],
					'petitioner' => $userData ['petitioner'],
					'beneficiary' => $userData ['beneficiary'],
					'notice_type' => $userData ['notice_type'],
					'description' => $userData ['description'],
					// 'filepath' => $userData['filepath'],
					'workpermit_type' => $userData ["workpermit_type"] 
			)
			;
			// return $userdata;
			if ($this->update ( $userData ["id"], $user )) {
				return $user;
			} else {
				return FALSE;
			}
		} else {
			$userdata = "No Data Found";
			return $userdata;
		}
	}
	
	// -------------------------------------Passport Process-----------------------------------------
	public function addPassportdetails($userData, $files) {
		//print_r ( $userData );
		//return;
		$this->_table = "passport_tbl";
		
		$result = $this->db->select ( 'user_id' )->from ( 'passport_tbl' )->where ( "number", $userData ["number"] )->get ()->row ();
		if ($result) {
			$response  = array('status' => 'error', 'message' => 'Passport Details Allready Exists');
			return $response;
			//return "Passport Details Allready Exists";
		} else {
			$user = array (
					// print_r($userData);return;
					// 'usermaster_id' => $userData ['usermaster_id'],
					'user_id' => $userData ["user_id"],
					'number' => $userData ["number"],
					'issue_date' => $userData ["issue_date"],
					'validity_FD' => $userData ["validity_FD"],
					'validity_TD' => $userData ["validity_TD"],
					'place_issue' => $userData ["place_issue"],
					'country_issue' => $userData ["country_issue"] 
			)
			// 'barcode' => $userData ["barcode"],
			// 'other' => $userData ["other"]
			
			;
			
		

			$this->db->insert ( 'passport_tbl', $user );
		
		$last_id = $this->db->insert_id ();



		if ($last_id) {
                        $upload_id = $this->uploadDocument($last_id, "Passport", $files, $userData ['user_id']);
                       
                        if($upload_id == "success")
                        {
                        	$response  = array('status' => 'success', 'message' => 'Details added successfully');	
                        }	
                        else if($upload_id == "error")
                        {
                        	$response  = array('status' => 'error', 'message' => 'Passport document upload failed.');	
                        }	
			return $response;
		
		
		}
	}
	}
	public function viewPassportdetails($userdata) {
		// print_r($userdata["user_id"]); return;
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "passport_tbl";
		$result = $this->db->select ( 'id,user_id,number,issue_date,validity_FD,validity_TD,place_issue,country_issue,barcode,other' )->from ( $this->_table )->where ( 'user_id', $userdata ["user_id"] )->get ()->result ();
		
		return $result;
	}
	public function viewPassportdetailsByid($userdata) {
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "passport_tbl";
		$result = $this->db->select ( 'id,user_id,number,issue_date,validity_FD,validity_TD,place_issue,country_issue,barcode,other' )->from ( $this->_table )->where ( 'id', $userdata ["id"] )->get ()->row ();
		
		return $result;
	}
	public function updatePassportdetails($userData) {
		
		// print_r($userData);return;
		$this->_table = 'passport_tbl';
		$result = $this->db->select ( 'user_id' )->from ( 'passport_tbl' )->where ( 'id', $userData ["id"] )->get ()->row ();
		// print_r($result);return;
		if ($result) {
			$result1 = $this->db->select ( 'user_id' )->from ( 'passport_tbl' )->where ( 'number', $userData ["number"] )->where_not_in ( 'id', $userData ["id"] )->get ()->row ();
			if ($result1) {
				$userdata = "Passport Number Allready Exists";
				return $userdata;
			} else {
				$user = array (
						
						'number' => $userData ['number'],
						'issue_date' => $userData ['issue_date'],
						'validity_FD' => $userData ['validity_FD'],
						'validity_TD' => $userData ['validity_TD'],
						'place_issue' => $userData ['place_issue'],
						'country_issue' => $userData ['country_issue'] 
				)
				// 'barcode' => $userData['barcode'],
				// 'other' => $userData['other']
				
				;
				// return $userdata;
				if ($this->update ( $userData ["id"], $user )) {
					return 1;
				} else {
					return FALSE;
				}
			}
		} else {
			$userdata = "No Data Found";
			return $userdata;
		}
	}
	
	// -----------------------------------I94 Details------------------------------------------------
	public function viewI94Details($userdata) {
		// print_r($userdata["user_id"]); return;
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "i94details_tbl";
		$result = $this->db->select ( 'id,user_id,record_number,admit_date,family_name, f_name,dob, passport_number,issued_country, entry_date,class_admission,filepath' )->from ( $this->_table )->where ( 'user_id', $userdata ["user_id"] )->get ()->result ();
		
		return $result;
	}
	public function viewI94DetailsByid($userdata) {
		// $query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
		$this->_table = "i94details_tbl";
		$result = $this->db->select ( 'id,user_id,record_number,admit_date,family_name, f_name,dob, passport_number,issued_country, entry_date,class_admission,filepath' )->from ( $this->_table )->where ( 'id', $userdata ["id"] )->get ()->row ();
		
		return $result;
	}

	public function addI94Details($userData, $files) {
		// print_r($userData);
		$this->_table = "i94details_tbl";
		$response  = array('status' => '', 'message' => '');
		$result = $this->db->select ( 'user_id' )->from ( 'i94details_tbl' )->where ( "record_number", $userData ["record_number"] )->get ()->row ();
		if ($result) {
			$response  = array('status' => 'error', 'message' => 'I94file Details Allready Exists');
			return $response;
		} else {
			$user = array (
					// print_r($userData);return;
					// 'usermaster_id' => $userData ['usermaster_id'],
					'user_id' => $userData ["user_id"],
					'record_number' => $userData ["record_number"],
					'admit_date' => $userData ["admit_date"],
					'family_name' => $userData ["family_name"],
					'f_name' => $userData ["f_name"],
					'dob' => $userData ["dob"],
					'passport_number' => $userData ["passport_number"],
					'issued_country' => $userData ["issued_country"],
					'entry_date' => $userData ["entry_date"],
					'class_admission' => $userData ["class_admission"] 
			)
			;
			
			$this->db->insert ( 'i94details_tbl', $user );
		
		$last_id = $this->db->insert_id ();
		
		if ($last_id) {
                        $upload_id = $this->uploadDocument($last_id, "I94", $files, $userData ['user_id']);
                       
                        if($upload_id == "success")
                        {
                        	$response  = array('status' => 'success', 'message' => 'Details added successfully');	
                        }	
                        else if($upload_id == "error")
                        {
                        	$response  = array('status' => 'error', 'message' => 'I94file document upload failed.');	
                        }	
			return $response;
			/*$filepath = array();
			if (! empty ( $files )) {
				for ($i=0; $i < count($files ['name']); $i++ ) {
					//$imagename = $files['name'][$i];
					$filetype = $files['type'][$i];
					if($filetype == "application/pdf")
					{
						$filetype= ".pdf";
					}
					$filename = "I94_".$this->AlphaNumeric(8).$filetype;
					$path = '../assets/document/I94files/' . $filename;
					$filepath = '/assets/document/I94files/' . $filename;
					$tmp_name = $files['tmp_name'][$i];
					$this->_table = "i94file_tbl";
					if(move_uploaded_file($tmp_name, $path))
					{
						$wpfile = array (
								// 'usermaster_id' => $userData ['usermaster_id'],
								'user_id' => $userData ['user_id'],
								'i94file_id' => $last_id,
								'filepath' => $filepath,
								'filename' => $filename
						);
						$this->db->insert ( 'i94file_tbl', $wpfile );
						return $last_id;
					}
					else{
						return "uploaderror";
					}
						
				}
			}  */
		}
		
	}
}
	public function updateI94Details($userData) {
		
		// print_r($userData);return;
		$this->_table = 'i94details_tbl';
		$result = $this->db->select ( 'user_id' )->from ( 'i94details_tbl' )->where ( 'id', $userData ["id"] )->get ()->row ();
		// print_r($result);return;
		if ($result) {
			$result1 = $this->db->select ( 'user_id' )->from ( 'i94details_tbl' )->where ( 'record_number', $userData ["record_number"] )->where_not_in ( 'id', $userData ["id"] )->get ()->row ();
			if ($result1) {
				$userdata = "I94 Number Allready Exists";
				return $userdata;
			} else {
				$user = array (
						
						'record_number' => $userData ["record_number"],
						'admit_date' => $userData ["admit_date"],
						'family_name' => $userData ["family_name"],
						'f_name' => $userData ["f_name"],
						'dob' => $userData ["dob"],
						'passport_number' => $userData ["passport_number"],
						'issued_country' => $userData ["issued_country"],
						'entry_date' => $userData ["entry_date"],
						'class_admission' => $userData ["class_admission"] 
				)
				;
				// return $userdata;
				if ($this->update ( $userData ["id"], $user )) {
					return 1;
				} else {
					return FALSE;
				}
			}
		} else {
			$userdata = "No Data Found";
			return $userdata;
		}
	}
}