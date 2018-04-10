<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Taxfilingctl_bala extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->validate_token();
        $this->lang->load('profile');
        $this->load->model('SC_Taxfilingmdl_bala', 'taxfile');
       
    }




 public function getClientInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;


        $ClientInfo = $this->taxfile->getClientInfo($postData);
        if($ClientInfo)
        {
         $response['clientInfo'] = $ClientInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }
    
     public function addIncomeInfo_post()
    {
      $postData = json_decode ( $this->post ( 'incomeInfo' ), true );
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;
               $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }

        $IncomeInfo = $this->taxfile->addIncomeInfo($postData, $files);
        if($IncomeInfo)
        {
          $response ['status'] = "success";
            $response ['message'] = "Updateded successfuly";
         $response['incomeInfo'] = $IncomeInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }

    //--------------------------------CITIZENSHIP STATUS---------------------------------------------------------


    public function addCitizenshipInfo_post() {
       $postData = json_decode ( $this->post ( 'CitizenshipInfo' ), true );
       //print_r($postData);return;

        //$this->form_validation->set_data($postData);
        if ($postData) {
          // $postData["id"]= $this->user->id;
           $postData["user_id"]= $this->user->user_id;
            //print_r($postData);return;
                  $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }
            $commonInfo = $this->taxfile->addCitizenshipInfo($postData,$files);
            if($commonInfo == "update")
            {
            $response ['status'] = "success";
            $response ['message'] = "Updated successfuly";
            $response['commonInfo'] = $commonInfo;
          }
          else
          {
             $response ['status'] = "error";
            $response ['message'] = "error";
            $response['commonInfo'] = $commonInfo;
          }

            $this->set_response($response, REST_Controller::HTTP_OK);
        }
        
        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
}

}