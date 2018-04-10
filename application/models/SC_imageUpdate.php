<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_imageUpdate extends MY_Model {
	public function __construct() {
		parent::__construct ();
		
		// Model intilizations
		$this->_table = 'user_master';
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
	
	public function profileImage($userdata, $id) {
		$filepath = "assets/profileImage/" . $userdata;
		

			$this->_table = "user_master";

		
		//select imagepath from table where id= id
		
		$prevImagepath = $this->db->select('imagepath')
								->from($this->_table)
							->where('id', $id)
							->get()
							->row();
		
		//print_r($prevImagepath);
							
		//echo $_SERVER['DOCUMENT_ROOT'].'/'.$prevImagepath->imagepath;
		if($prevImagepath->imagepath != "uploadImage/default.png")					
		{
			unlink('../'.$prevImagepath->imagepath);
		}
		
		
		$user = array (
				'imagepath' => $filepath 
		);
		if ($this->update ( $id, $user )) {
			//$result = $this->db->last_query ();
			//return $result;
			 return $filepath;
		} else {
			return FALSE;
		}
	}
	
	public function uploadDocument($userData, $files) {
		$document_type = $userData['document_type'];
		$document_id = $userData['document_id'];
		if($document_type == "Workpermit")
		{
			$this->_table = 'workpermit_tbl';
			$path = '../assets/document/workprimit/';
			$filepath = '/assets/document/workprimit/';
			$fileTable = "workpermitfile_tbl";
			$filename = "wp_";
		}
		$result = $this->db->select ( 'user_id' )->from ( $this->_table )->where ( 'id', $userData ["document_id"] )->get ()->row ();
		// print_r($result);return;
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
								'user_id' => $userData ['user_id'],
								'file_id' => $document_id,
								'filepath' => $filepath,
								'filename' => $filename
						);
						$this->db->insert ( $fileTable, $wpfile );
						
					}
					else{
						return "uploaderror";
					}
			
				}
			}
			 return $userData;
			
		} else {
			$userdata = "No Data Found";
			return $userdata;
		}
	
		
	}
}

?>