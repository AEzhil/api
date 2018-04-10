<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class uploadimage extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->validate_token();
        $this->lang->load('profile');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('SC_imageUpdate', 'image');
       // $this->load->model('SC_Grading', 'grading');
    }
    
    	public function profileImage_post()
    	{
    		//$postData = $_FILES;
    		$postData = $this->post();
    		//print_r($postData);return;
    		//$file_element_name = 'imagePath';
    		
    		$this->form_validation->set_data($postData);
    		if ($this->form_validation->run('updateUserImage')) {
    			$config['upload_path']   =   "uploads/";
    			
    			$config['allowed_types'] =   "gif|jpg|jpeg|png";
    			
    			$config['max_size']      =   "5000";
    			
    			$config['max_width']     =   "1907";
    			
    			$config['max_height']    =   "1280";
    			
    			$config['overwrite']        = FALSE;
    			
    			$rename =  $this->user->user_id;    			
    			$config['file_name'] = $rename;    
    			//print_r($config);return;
    			
    			$this->load->library('upload',$config);    			
    			$this->upload->initialize($config);    	
    			
    			
    			if(!$this->upload->do_upload())    			
    			{    			
    				//echo "bala";
    				echo $this->upload->display_errors();
    			
    			}
    			else {
    				
    	            $filename = $this->upload->data('field_name');
    	            $postData['Imagepath'] = $filename;
    	            print_r($postData['Imagepath']);return;
    			}
    			//print_r($filename); return;
    			//$profile = $this->image->profileImage($postData,$rename);
    			$response ['status'] = "success";
    			$response ['message'] = $this->lang->line('userinfo_success');
    			//$response['profile'] = $profile;
    			$this->set_response($response, REST_Controller::HTTP_OK);
    		}
    		else
    		{
    			$this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
    		}
    	}
    
   
    
}
?>