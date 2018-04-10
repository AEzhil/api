<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Taxfilingctl extends MY_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // Controller intializations
        $this->validate_token();
        $this->lang->load('profile');
        $this->load->model('SC_Taxfilingmdl', 'taxfile');
       
    }


     //-----------------------------Tax Provider  details ----------------------------------------------------
    
     public function getTaxproviderDetails_get() {
    	$sourceList = $this->taxfile->getTaxproviderDetails();
    	$this->set_response($sourceList, REST_Controller::HTTP_OK);
   	}



     public function getTaxproviderDetailsById_post() {
        $Data = $this->post();
        $sourceList = $this->taxfile->getTaxproviderDetailsById( $Data);
        $this->set_response($sourceList, REST_Controller::HTTP_OK);
    }



     public function getTaxproviderId_get() {
        $postData["user_id"]= $this->user->user_id;
        $taxprovider = array( );
        $taxprovider_id = $this->taxfile->gettaxpayerByid($postData);
        //print_r($taxprovider_id);return;
        if($taxprovider_id)
        {
           $taxprovider = $this->taxfile->getTaxproviderIdDetails($taxprovider_id->id);
          $taxprovider->status =    $taxprovider_id->status;
         //print_r($taxprovider);return;
        }
       
        $response["taxprovider"] =  $taxprovider ;

        $this->set_response($response, REST_Controller::HTTP_OK);
    }

    public function getTaxproviderIdById_post() {
        $postData = $this->post();
        //print_r($postData);return;
        $taxpayer_id = $postData["id"];
        $taxprovider = $this->taxfile->getTaxproviderIdDetails($taxpayer_id);
        $response["taxprovider"] =  $taxprovider ;
        $this->set_response($response, REST_Controller::HTTP_OK);
    }




    public function updateTaxprovider_post() {
             $postData = $this->post();

      //  $this->form_validation->set_data($postData);
        if ($postData) {
                   $postData["user_id"]= $this->user->user_id;
       // print_r($year);return;
      // print_r($postData) ;return;
            $taxprovider = $this->taxfile->updateTaxprovider($postData);
         
            if($taxprovider)
            {
            $response ['status'] = "success";
            $response ['message'] = "Updateded successfuly";
            $response['taxfile'] = $taxprovider;
        }
        else
        {
            $response ['status'] = "error";
            $response ['message'] =$taxprovider;
           //$response['taxfile'] = $taxfile;
        }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
        
        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
       
       
    }



     public function updateTaxproviderDetails_post() {

         $postData = $this->post();
        //print_r($postData);return;
        $this->form_validation->set_data($postData);
        if ($this->form_validation->run('addProviderForm')) {
                  
       // print_r($year);return;
          
            $taxprovider = $this->taxfile->updateTaxproviderDetails($postData);
         
            if($taxprovider != "Already Exists")
            {
            $response ['status'] = "success";
            $response ['message'] = "Updated successfuly";
            $response['taxfile'] = $taxprovider;
        }
        else
        {
             $response ['status'] = "error";
            $response ['message'] =$taxprovider;
           //$response['taxfile'] = $taxfile;
        }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
        
        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
       
       
    }




 //----------------------------- Sourcelist Details ----------------------------------------------------
     public function getSourcelistDetails_get() {
    	$taxProvider = $this->taxfile->getSourcelistDetails();
    	$this->set_response($taxProvider, REST_Controller::HTTP_OK);
   	}

     public function getSourcelistDetailsById_get() {
        $postData["user_id"]= $this->user->user_id;
        $sourceList =  array();
        $taxpayer = $this->taxfile->gettaxpayerid($postData);
        if($taxpayer)
        {
          $taxpayer_id = $taxpayer->id;
           $sourceList = $this->taxfile->getSourcelistDetailsById($taxpayer_id);
          $sourceList->fileList = $this->taxfile->getFileListById ( $sourceList->taxpayer_id, "Taxpayer", "Source List" );
        }
        //$response['sourceList']  = $sourceList;
        $this->set_response($sourceList, REST_Controller::HTTP_OK);
    }


      public function getSourcelistByIdpost_post() {
        $postData= $this->post();
         $taxpayer_id = $postData["id"];
         // print_r($postData);return;
       // $taxpayer = $this->taxfile->gettaxpayerid($taxpayer_id)->id;
       // print_r($taxpayer_id);return;
        $sourceList = $this->taxfile->getSourcelistDetailsById($taxpayer_id);
          $sourceList->fileList = $this->taxfile->getFileListById ( $sourceList->taxpayer_id, "Taxpayer", "Source List" );
        $this->set_response($sourceList, REST_Controller::HTTP_OK);
    }





    public function addSourcelistDetails_post() {
                 $postData = json_decode ( $this->post ( 'sourceInfo' ), true );
      //        print_r($_FILES);
     // print_r(gettype($postData));return;
         $this->form_validation->set_data($postData);
        if ($this->form_validation->run('addsourcelistForm')) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
           // print_r($postData);return;
            $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }
            $Sourcedata = $this->taxfile->addSourcelistDetails($postData, $files);
         //   echo is_numeric($Sourcedata);
           //print_r($Sourcedata);return;
            if(is_numeric($Sourcedata))
            {
            $response ['status'] = "success";
            $response ['message'] = "Added successfuly";
            $response['Sourcedata'] = $Sourcedata;
        }
        else if ($Sourcedata == "Not found") {
             $response ['status'] = "error";
            $response ['message'] =$Sourcedata;
            # code...
        }
        else if ($Sourcedata == "updated")

        {
             $response ['status'] = "success";
            $response ['message'] ="Updated successfuly";
           //$response['taxfile'] = $taxfile;
        }
        else
        {
             $response ['status'] = "error";
            $response ['message'] =$Sourcedata;
            # code...
        }

            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }



   //----------------------------- Add Tax Information ----------------------------------------------------
    public function addTaxpayerInfo_post() {
        $postData = json_decode ( $this->post ( 'generalInfo' ), true );
       // print_r ( $_FILES );
        //print_r($postData);return;
       // $this->form_validation->set_data($postData);
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
           $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }
            //print_r($postData);return;
            $taxfile = $this->taxfile->addtaxfile($postData, $files);
            //print_r(gettype($taxfile));
            if($taxfile == "added")
            {
                $response ['status'] = "success";
                $response ['message'] = "Added successfuly";
                $response['taxfile'] = $taxfile;
                 $this->set_response($response, REST_Controller::HTTP_OK);
            }
            else if($taxfile == "updated")
            {
                 $response ['status'] = "success";
                $response ['message'] = "Updated successfuly";
               //$response['taxfile'] = $taxfile;
                 $this->set_response($response, REST_Controller::HTTP_OK);
            }
            else
            {
                 $response ['status'] = "error";
                $response ['message'] = "Something when to wrong.";
               //$response['taxfile'] = $taxfile;
                $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
            }
           
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }


//---------------------------------Get User Information -----------------------------------------------------

 public function getTaxpayerInfo_get()
    {
        // To get sub Category list
       // $postData = $this->post();
        $postData["user_id"]= $this->user->user_id;
        $taxfileinfo = $this->taxfile->gettaxfileInfo($postData);
        
      if($taxfileinfo)
      {

        $taxfileinfo->fileList = $this->taxfile->getFileListById ( $taxfileinfo->id, "Taxpayer", "General Information" );
      // print_r($taxfileinfo->id);
       $Depentent = $this->taxfile->getdepententInfo($taxfileinfo->id);
       $Spouse = $this->taxfile->getspouseInfo($taxfileinfo->id);
         $taxfileinfo->dependents = $Depentent;
        $taxfileinfo->spouse = $Spouse;
      }
         $response['Taxfileinfo'] = $taxfileinfo;

        $this->set_response($response, REST_Controller::HTTP_OK);
      /*}
      else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }*/



    }


    public function getTaxpayerInfoBypost_post()
    {
        // To get sub Category list
       // $postData = $this->post();
        $postData= $this->post();

        $taxfileinfo = $this->taxfile->gettaxfileInfoBypost($postData);
        $taxfileinfo->fileList = $this->taxfile->getFileListById ( $taxfileinfo->id, "Taxpayer", "General Information" );
      // print_r($taxfileinfo->id);
       $Depentent = $this->taxfile->getdepententInfo($taxfileinfo->id);
       $Spouse = $this->taxfile->getspouseInfo($taxfileinfo->id);
         $taxfileinfo->dependents = $Depentent;
        $taxfileinfo->spouse = $Spouse;
      if($taxfileinfo)
      {
         $response['Taxfileinfo'] = $taxfileinfo;

        $this->set_response($response, REST_Controller::HTTP_OK);
      }
      else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }



    }




//----------------------------------Updated User Information -------------------------------------------------


 public function updateTaxpayerInfo_post() {
        $postData = $this->post();
        //print_r($postData);return;
        $this->form_validation->set_data($postData);
        if ($this->form_validation->run('updataxFile')) {
          // $postData["id"]= $this->user->id;
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


//------------------------------------Get CITIZENSHIP Information------------------------------------------------



 public function getCitizenshipInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
           $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
          // print_r($taxfileinfo->id);return;
          $TaxfileCommoninfo = array();
          if($taxpayer)
          {
              $taxpayer_id = $taxpayer->id;
              // print_r($taxfileinfo_id);return;
              $TaxfileCommoninfo = $this-> taxfileinfofunction($taxpayer_id);
          }
           $response['TaxfileCommoninfo'] = $TaxfileCommoninfo;

        $this->set_response($response, REST_Controller::HTTP_OK);
        }






public function getCitizenshippost_post()
    {
        $postData= $this->post();
         $taxpayer_id = $postData['id'];
           $TaxfileCommoninfo = array();
          if($taxpayer_id)
          {
              $TaxfileCommoninfo = $this-> taxfileinfofunction($taxpayer_id);
          }
           $response['TaxfileCommoninfo'] = $TaxfileCommoninfo;

        $this->set_response($response, REST_Controller::HTTP_OK);
        }

   public function taxfileinfofunction($data)
   {
    //print_r($data);return;
      $postData["id"] = $data;
      $postData["user_id"]= $this->user->user_id;
       $year = date('Y');
       $year_1 = $year -1 ;
       $year_2 = $year -2 ;
       $year_3 = $year -3 ;
       $year_4 = $year -4;
        $yearData = array("no_of_days"=>"", "entry_date" => "",  "exit_date" => "");
        $taxfileinfo = $this->taxfile->gettaxfileCommonInfo($postData);

       // $taxyearinfo = $this->taxfile->gettaxfileyearInfo();
        $yearData1 = $this->taxfile->gettaxfileyearInfo($postData,$year_1);
       if($yearData1 != null)
        {
              $taxfileinfo->year_1 = $yearData1;
              $taxfileinfo->stateEntrydetails = $this->taxfile->gettaxfilestateInfo($taxfileinfo->year_1->id);
              for($i=0;$i<count($taxfileinfo->stateEntrydetails);$i++)
              {
                  $stateentry = $taxfileinfo->stateEntrydetails[$i];
                  //$stateentry->state= array("id"=>"", "name"=>"");
                  //print_r( $stateentry->id); echo "------";
                  $stateentry->state = $this->taxfile->getstateByidInfo($stateentry->state_id);
                  //   print_r( $state); echo "------";
                  
              }
        }
       else
              $taxfileinfo->year_1 = $yearData;

               $yearData2 = $this->taxfile->gettaxfileyearInfo($postData,$year_2);
       if($yearData2 != null)
              $taxfileinfo->year_2 = $yearData2;
       else
              $taxfileinfo->year_2 = $yearData;

               $yearData3 = $this->taxfile->gettaxfileyearInfo($postData,$year_3);
       if($yearData3 != null)
              $taxfileinfo->year_3 = $yearData3;
       else
              $taxfileinfo->year_3 = $yearData;

               $yearData4 = $this->taxfile->gettaxfileyearInfo($postData,$year_4);
       if($yearData4 != null)
              $taxfileinfo->year_4 = $yearData4;
       else
              $taxfileinfo->year_4 = $yearData;

    $taxfileinfo->fileList = $this->taxfile->getFileListById ( $taxfileinfo->id, "Taxpayer", "Citizenship" );

    
    // print_r( $taxfileinfo->stateEntrydetails->id); return;

       $Spouse = $this->taxfile->getspouseCommonInfo($taxfileinfo->id);

       $yearData1 = $this->taxfile->getspouseCommonyearInfo($Spouse->id,$year_1);
       if($yearData1 != null)
       {
            $Spouse->year_1 = $yearData1;
            $Spouse->stateEntrydetails =  $this->taxfile->getCommonstateInfo($Spouse->year_1->id);
            for($i=0;$i<count($Spouse->stateEntrydetails);$i++)
            {
                $stateentry = $Spouse->stateEntrydetails[$i];
                //print_r( $stateentry->id); echo "------";return;
                $state = $this->taxfile->getstateByidInfo($stateentry->state_id);
                $stateentry->state= $state;

            }
        }
       else
              $Spouse->year_1 = $yearData;
              
       $yearData2 = $this->taxfile->getspouseCommonyearInfo($Spouse->id,$year_2);
       if($yearData2 != null)
              $Spouse->year_2 = $yearData2;
       else
              $Spouse->year_2 = $yearData;

              $yearData3 = $this->taxfile->getspouseCommonyearInfo($Spouse->id,$year_3);
       if($yearData3 != null)
              $Spouse->year_3 = $yearData3;
       else
              $Spouse->year_3 = $yearData;
              
              $yearData4 = $this->taxfile->getspouseCommonyearInfo($Spouse->id,$year_4);
       if($yearData4 != null)
              $Spouse->year_4 = $yearData4;
       else
              $Spouse->year_4 = $yearData;


       
        $taxfileinfo->spouse  =  $Spouse;

         $Depantant = $this->taxfile->getdepententCommonInfo($taxfileinfo->id);
         for($i=0;$i<count($Depantant);$i++)
        {
            $depant = $Depantant[$i];
              $depantyearData1 = $this->taxfile->getdepententyearInfo($depant->depentant_id,$year_1);
              if($depantyearData1 != null)
              {
                $depant->year_1 = $depantyearData1;

                $depant->stateEntrydetails = $this->taxfile->getCommonstateInfo($depant->year_1->id);
                for($j=0;$j<count($depant->stateEntrydetails);$j++)
                {
                   $stateentry = $depant->stateEntrydetails[$j];
                 //print_r( $stateentry->id); echo "------";return;
                  $state = $this->taxfile->getstateByidInfo($stateentry->state_id);
                  $stateentry->state= $state;
                }
              }
               else
              $depant->year_1 = $yearData;
              
              $depantyearData2 = $this->taxfile->getdepententyearInfo($depant->depentant_id,$year_2);
              if($depantyearData2 != null)
              $depant->year_2 = $depantyearData2;
               else
              $depant->year_2 = $yearData;
              
              $depantyearData3 = $this->taxfile->getdepententyearInfo($depant->depentant_id,$year_3);
              if($depantyearData3 != null)
              $depant->year_3 = $depantyearData3;
               else
              $depant->year_3 = $yearData;
              
              $depantyearData4 = $this->taxfile->getdepententyearInfo($depant->depentant_id,$year_4);
              if($depantyearData4 != null)
              $depant->year_4 = $depantyearData4;
               else
              $depant->year_4 = $yearData;
           
         }

         $taxfileinfo->dependents = $Depantant;
             return $taxfileinfo;
        // $response['TaxfileCommoninfo'] = $taxfileinfo;

        //$this->set_response($response, REST_Controller::HTTP_OK);

     /* if($taxfileinfo)
      {
         $response['TaxfileCommoninfo'] = $taxfileinfo;

        $this->set_response($response, REST_Controller::HTTP_OK);
      }
      else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }*/



    }




//----------------------Income Information--------------------------------------------

public function getIncomeInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
         $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
         $IncomeInfo = array();
         if($taxpayer)
         {
          $taxpayer_id = $taxpayer->id ;
             $IncomeInfo = $this->taxfile->getIncomeInfo($taxpayer_id);
         }
          $response['incomeInfo'] = $IncomeInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
    }


public function getIncomeInfopost_post()
    {
        $postData= $this->post();
        
         $taxpayer_id = $postData["id"];
        $IncomeInfo = $this->taxfile->getIncomeInfo($taxpayer_id);
        if($IncomeInfo)
        {
         $response['incomeInfo'] = $IncomeInfo;
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



   //----------------------Sale of Assets Information--------------------------------------------

public function getAssestsInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
         $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
         $assetsInfo = array();
         if($taxpayer)
        {
          $taxpayer_id = $taxpayer->id;
          $assetsInfo = $this->taxfile->getAssestsInfo($taxpayer_id);
        }
         $response['assetsInfo'] = $assetsInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
    }



  public function getAssestsInfopost_post()
    {
       
  
        $postData= $this->post();
        
         $taxpayer_id = $postData["id"];
       

        $assetsInfo = $this->taxfile->getAssestsInfo($taxpayer_id);
        if($assetsInfo)
        {
         $response['assetsInfo'] = $assetsInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }




    public function addAssetsInfo_post()
    {
      $postData = json_decode ( $this->post ( 'assetsInfo' ), true );
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;
               $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }

        $assetsInfo = $this->taxfile->addAssetsInfo($postData, $files);
        if($assetsInfo)
        {
          $response ['status'] = "success";
            $response ['message'] = "Updateded successfuly";
         $response['assetsInfo'] = $assetsInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }


    //----------------------Itemized Dedution--------------------------------------------

public function getItemizedInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
      $itemizedInfo = array();

        $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
         if($taxpayer)
        {
          $taxpayer_id = $taxpayer->id;
          $itemizedInfo = $this->taxfile->getItemizedInfo($taxpayer_id);
          
          }
          $response['itemizedInfo'] = $itemizedInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
    }


    public function getItemizedInfopost_post()
    {
        $postData= $this->post();

         $taxpayer_id = $postData["id"];
       
        $itemizedInfo = $this->taxfile->getItemizedInfo($taxpayer_id);
        if($itemizedInfo)
        {
         $response['itemizedInfo'] = $itemizedInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }


    public function addItemizedInfo_post()
    {
      $postData = json_decode ( $this->post ( 'itemizedInfo' ), true );
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;
               $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }

        $itemizedInfo = $this->taxfile->addItemizedInfo($postData, $files);
        if($itemizedInfo)
        {
          $response ['status'] = "success";
            $response ['message'] = "Updateded successfuly";
         $response['itemizedInfo'] = $itemizedInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }

      //----------------------Moving & Dep Information--------------------------------------------

public function getMovingDepInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
        $movingDepInfo = array();
          $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
          if($taxpayer)
        {
          $taxpayer_id = $taxpayer->id;
          $movingDepInfo = $this->taxfile->getMovingDepInfo($taxpayer);
        }

         $response['movingDepInfo'] = $movingDepInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
    }

 public function getMovingDepInfopost_post()
    {
        $postData= $this->post();
          
         $taxpayer_id = $postData["id"];
        $movingDepInfo = $this->taxfile->getMovingDepInfo($taxpayer_id);
        if($movingDepInfo)
        {
          
         $response['movingDepInfo'] = $movingDepInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }



    public function addMovingDepInfo_post()
    {
      $postData = json_decode ( $this->post ( 'movingDepInfo' ), true );
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;
               $files = "";
           if(! empty ( $_FILES ))
           {
             $files = $_FILES ['file'];
           }

        $movingDepInfo = $this->taxfile->addMovingDepInfo($postData, $files);
        if($movingDepInfo)
        {
          $response ['status'] = "success";
            $response ['message'] = "Updateded successfuly";
         $response['movingDepInfo'] = $movingDepInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        

    }
    


//-------------------------------FBAR-------------------------------------------------------------------------

 public function addFbarInfo_post() {
        $postData = $this->post();
     
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
          
            //print_r($postData);return;

             if($postData["country_id"]== "")
              $postData["country_id"] = null;


            $FbarInfo = $this->taxfile->addFbarInfo($postData);

            if( $FbarInfo != "Error")
            {
                $response ['status'] = "success";
              //  $response ['message'] = "Added successfuly";
                $response['message'] = $FbarInfo;
            }
            else
            {
                 $response ['status'] = "success";
                $response ['message'] =$FbarInfo;
               //$response['taxfile'] = $taxfile;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }



  public function getFbarInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;
      $FbarrInfo = array();
         $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
         if($taxpayer)
        {

               $taxpayer_id = $taxpayer->id;
               $FbarrInfo = $this->taxfile->getFbarInfo($taxpayer_id);
               $FbarrInfo ->country = $this->taxfile->getCountryName($FbarrInfo->country_id);
        }

         //$response['FbarrInfo'] = $FbarrInfo;
         $this->set_response($FbarrInfo, REST_Controller::HTTP_OK);

    }


public function getFbarInfopost_post()
    {
        $postData= $this->post();
       //$year = date('Y');
       //$year_1 = $year -1 ;
        $taxpayer_id = $postData["id"];
        $FbarrInfo = $this->taxfile->getFbarInfo($taxpayer_id);
          $FbarrInfo ->country = $this->taxfile->getCountryName($FbarrInfo->country_id);
        
        if($FbarrInfo)
        {
        // $response['FbarrInfo'] = $FbarrInfo;
         $this->set_response($FbarrInfo, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }


    //-----------------------------------------Form 8938--------------------------------------------------


    public function addForm8938Info_post() {
        $postData = $this->post();
     
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
          
            //print_r($postData);return;
            $Form8938Info = $this->taxfile->addForm8938Info($postData);

            if( $Form8938Info != "Error")
            {
                $response ['status'] = "success";
              //  $response ['message'] = "Added successfuly";
                $response['message'] = $Form8938Info;
            }
            else
            {
                 $response ['status'] = "success";
                $response ['message'] =$Form8938Info;
               //$response['taxfile'] = $taxfile;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }



  public function getForm8938Info_get()
    {
        $postData["user_id"]= $this->user->user_id;
       $Form8938Info = array();
         $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
        if($taxpayer)
        {
            $taxpayer_id = $taxpayer->id;
            $Form8938Info = $this->taxfile->getForm8938Info($taxpayer_id);
        
        }

         //$response['Form8938'] = $Form8938Info;
         $this->set_response($Form8938Info, REST_Controller::HTTP_OK);
    }


     public function getForm8938Infopost_post()
    {
        $postData= $this->post();
       //$year = date('Y');
       //$year_1 = $year -1 ;

          $taxpayer_id = $postData["id"];
        $Form8938Info = $this->taxfile->getForm8938Info($taxpayer_id);
        
        
        if($Form8938Info)
        {
         //$response['Form8938'] = $Form8938Info;
         $this->set_response($Form8938Info, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }




    //------------------------------------------Submit Form-----------------------------------------------------

 public function addSubmitFormInfo_post() {
        $postData = $this->post();
     
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
          
            //print_r($postData);return;
            $SubmitFormInfo = $this->taxfile->addSubmitformInfo($postData);

            if( $SubmitFormInfo != "Error")
            {
                $response ['status'] = "success";
                $response ['message'] = "Updated successfuly";
               // $response['message'] = $SubmitFormInfo;
            }
            else if($SubmitFormInfo == "Error")
            {
                 $response ['status'] = "error";
                $response ['message'] =$SubmitFormInfo;
               //$response['taxfile'] = $taxfile;
            }
            else 
            {
                 $response ['status'] = "error";
                $response ['message'] ="Error";
               //$response['taxfile'] = $taxfile;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }


    public function SubmitformallInfo_post() {
        $postData = $this->post();

        // print_r($postData);
         $this->form_validation->set_data($postData);
        if ($this->form_validation->run('submitformFile')) {
           //print_r($postData);return;
          // $postData["id"]= $this->user->id;
     
          $postData["user_id"]= $this->user->user_id;
          
            //print_r($postData);return;
            $SubmitFormInfo = $this->taxfile->SubmitformallInfo($postData);

          // print_r(var_dump($SubmitFormInfo));return;
            if( $SubmitFormInfo == true)
            {
                $response ['status'] = "success";
               $response ['message'] = "Successfuly Submited your From";
                $response['SubmitFormInfo'] = $SubmitFormInfo;
            }
            else if($SubmitFormInfo == false)
            {
                 $response ['status'] = "error";
                $response ['message'] =$SubmitFormInfo;
               //$response['taxfile'] = $taxfile;
            }
            else 
            {
                 $response ['status'] = "error";
                $response ['message'] ="Error";
               //$response['taxfile'] = $taxfile;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        
              }
               else
               {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
            }

       
    }


  public function getSubmitFormInfo_get()    {
        $postData["user_id"]= $this->user->user_id;
      $SubmitFormInfo = array();
         $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
        if($taxpayer)
        {
            $taxpayer_id = $taxpayer->id;

        $SubmitFormInfo = $this->taxfile->getSubmitFormInfo($SubmitFormInfo_id);
        
        }
         //$response['Form8938'] = $Form8938Info;
         $this->set_response($SubmitFormInfo, REST_Controller::HTTP_OK);
    }


    public function getSubmitFormInfopost_post()    {
        $postData= $this->post();
       $taxpayer_id = $postData["id"];
        $SubmitFormInfo = $this->taxfile->getSubmitFormInfo($taxpayer_id);
        
        
        if($SubmitFormInfo)
        {
         //$response['Form8938'] = $Form8938Info;
         $this->set_response($SubmitFormInfo, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }

//------------------------------------------Rental Income Form-----------------------------------------------------

 public function addRentalincomeInfo_post() 
 {
        $postData = $this->post();
     

  // print_r($postData);return;
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;

             
            $RentalincomeInfo = $this->taxfile->addRentalincomeInfo($postData);

            if( is_numeric($RentalincomeInfo))
            {
                $response ['status'] = "success";
               $response ['message'] = "Added successfuly";
                $response['RentalincomeInfo'] = $RentalincomeInfo;
            }
            else if($RentalincomeInfo != "Error")
            {
                 $response ['status'] = "success";
                $response ['message'] =$RentalincomeInfo;
               //$response['taxfile'] = $taxfile;
            }
            else
            {
                $response ['status'] = "error";
                $response ['message'] =$RentalincomeInfo;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }

 public function getRentalincomeInfo_get()    {
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;
        // $taxpayer_id = $postData["id"];
      $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
      $RentalincomeInfo = array();
      $status = "";
      if($taxpayer)
      {
        $taxpayer_id = $taxpayer->id;
        $RentalincomeInfo = $this->taxfile->getRentalincomeInfo($taxpayer_id);
        for ($i=0;$i<count($RentalincomeInfo); $i++) {
                     
                     $imp = $RentalincomeInfo[$i];
                     $res = json_decode($imp->improvements);
                   $imp->improvements = $res;
          }
            $statusData = $this->taxfile->getRentalincomestatus($taxpayer_id);
            $status = $statusData->status;
      }
            $response['RentalincomeInfo'] = $RentalincomeInfo;
            $response['status'] = $status;

         $this->set_response($response, REST_Controller::HTTP_OK);

       
    }



  public function getRentalincomeInfopost_post()    {
        $postData= $this->post();
       //$year = date('Y');
       //$year_1 = $year -1 ;
         $taxpayer_id = $postData["id"];
        // $RentalincomeInfo_id = $this->taxfile->getTaxpayer_ID($postData)->id;
        $RentalincomeInfo = $this->taxfile->getRentalincomeInfo($taxpayer_id);
        
        for ($i=0;$i<count($RentalincomeInfo); $i++) {
                     
                     $imp = $RentalincomeInfo[$i];
                     $res = json_decode($imp->improvements);
                   $imp->improvements = $res;
          }
        if($RentalincomeInfo)
        {

         
         $this->set_response($RentalincomeInfo, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }


    public function deleteRentalInfo_post()
    {
       $postData= $this->post();
      // print_r($postData);return;
      
      if($postData["id"] != null)
      {    
         $RentalincomeInfo = $this->taxfile->getRentaldeleteInfo($postData["id"]);        
      
          # code...
        
        if($RentalincomeInfo == true )
        {
         //$response['Form8938'] = $Form8938Info;
              $response ['status'] = "success";
               $response ['message'] = "Deleted successfuly";
                $response['RentalincomeInfo'] = $RentalincomeInfo;
         
         }
         elseif($RentalincomeInfo == "Data Not found")
         {
           $response ['status'] = "error";
                $response ['message'] ="Error or Data Not found";
         }
         $this->set_response($response, REST_Controller::HTTP_OK);
       }
         else
              {
                 $response ['status'] = "error";
                $response ['message'] ="Please select item for delete.";
         $this->set_response($response, REST_Controller::HTTP_EXPECTATION_FAILED);
      

     }
   }

//--------------------------------------------Business Income Module ---------------------------------------



    public function addBusinessincomeInfo_post() 
 {
        $postData = $this->post();
     
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
           $postData["user_id"]= $this->user->user_id;
          
            //print_r($postData);return;
            $BusinessincomeInfo = $this->taxfile->addBusinessincomeInfo($postData);

            if( is_numeric($BusinessincomeInfo))
            {
                $response ['status'] = "success";
               $response ['message'] = "Added successfuly";
                $response['BusinessincomeInfo'] = $BusinessincomeInfo;
            }
            else if($BusinessincomeInfo != "Error")
            {
                 $response ['status'] = "success";
                $response ['message'] =$BusinessincomeInfo;
               //$response['taxfile'] = $taxfile;
            }
            else
            {
                $response ['status'] = "error";
                $response ['message'] =$BusinessincomeInfo;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }



  public function getBusinessincomeInfo_get()    {
        $postData["user_id"]= $this->user->user_id;
       //$year = date('Y');
       //$year_1 = $year -1 ;

      $taxpayer = $this->taxfile->getTaxpayer_ID($postData);
      $status = "";
        $BusinessincomeInfo = array();
      if($taxpayer)
      {
                   $taxpayer_id = $taxpayer->id;
        $BusinessincomeInfo = $this->taxfile->getBusinessincomeInfo($taxpayer_id);

        for ($i=0;$i<count($BusinessincomeInfo); $i++) {
                     
                     $fix = $BusinessincomeInfo[$i];
                     $res = json_decode($fix->fixedAssets);
                   $fix->fixedAssets = $res;
          }
          
           $statusData = $this->taxfile->getBusinessincomestatus($taxpayer_id);
           $status = $statusData->status;;
      }
            $response['BusinessincomeInfo'] = $BusinessincomeInfo;
            $response['status'] = $status;

          
        $this->set_response($response, REST_Controller::HTTP_OK);
           
    }


     public function getBusinessincomeInfopost_post()    {
        $postData= $this->post();
       //$year = date('Y');
       //$year_1 = $year -1 ;

         $taxpayer_id = $postData["id"];
        $BusinessincomeInfo = $this->taxfile->getBusinessincomeInfo($taxpayer_id);
         for ($i=0;$i<count($BusinessincomeInfo); $i++) {
                     
                     $fix = $BusinessincomeInfo[$i];
                     $res = json_decode($fix->fixedAssets);
                   $fix->fixedAssets = $res;
          }
        
        
        if($BusinessincomeInfo)
        {
         //$response['Form8938'] = $Form8938Info;
         $this->set_response($BusinessincomeInfo, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }


    public function deleteBusinessInfo_post()
    {
       $postData= $this->post();
      // print_r($postData);return;
      
      if($postData["id"] != null)
      {    
         $BusinessincomeInfo = $this->taxfile->deleteBusinessInfo($postData["id"]);  
        // print_r(var_dump($BusinessincomeInfo));return;      
      
          # code...
        
        if($BusinessincomeInfo == true)
        {
         //$response['Form8938'] = $Form8938Info;
              $response ['status'] = "success";
               $response ['message'] = "Deleted successfuly";
                $response['BusinessincomeInfo'] = $BusinessincomeInfo;
         
         }
         else if($BusinessincomeInfo == "Data Not found")
             {
           $response ['status'] = "error";
                $response ['message'] ="Error or Data Not found ";
         }
         $this->set_response($response, REST_Controller::HTTP_OK);
       }
         else
              {
                 $response ['status'] = "error";
                $response ['message'] ="Please select item for delete.";
         $this->set_response($response, REST_Controller::HTTP_EXPECTATION_FAILED);
      

     }
   }



  //----------------------------------Get all taxpayer Details-------------------------------------------------

      public function getTaxproviderGenInfo_get()  
        {
       
        $postData["user_id"]= $this->user->user_id;
       
        $TaxprovidererGenInfo = $this->taxfile->getTaxproviderGenInfo($postData);

         
        
        if($TaxprovidererGenInfo)
        {
         //$response['Form8938'] = $Form8938Info;
         $this->set_response($TaxprovidererGenInfo, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }

        
       
    }



 public function saveTaxproviderGenInfo_post() {
        $postData = $this->post();
       // print_r ( $_FILES );
      //  print_r($postData);return;
       // $this->form_validation->set_data($postData);
        if ($postData) {
          // $postData["id"]= $this->user->id;

       // print_r($year);return;
                     //print_r($postData);return;
            $TaxproviderDetails = $this->taxfile->addTaxproviderDetails($postData);

            if(is_numeric ($TaxproviderDetails))
            {
                $response ['status'] = "success";
                $response ['message'] = "Added successfuly";
                $response['TaxproviderDetails'] = $TaxproviderDetails;
            }
            elseif($TaxproviderDetails == "Updated Successfuly")
            {
              $response ['status'] = "success";
                $response ['message'] =$TaxproviderDetails;
            }
            else
            {
                 $response ['status'] = "error";
                $response ['message'] =$TaxproviderDetails;
               //$response['TaxproviderDetails'] = $TaxproviderDetails;
            }
            $this->set_response($response, REST_Controller::HTTP_OK);
        }

        else
        {
            $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
        }
    }



        
       
    

public function getClientInfo_get()
    {
        $postData["user_id"]= $this->user->user_id;

        $ClientInfo = $this->taxfile->getClientInfo($postData);
        $response['clientInfo'] = $ClientInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
        /*if($ClientInfo)
        {
         $response['clientInfo'] = $ClientInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }
         */
    


  }


  //-------------------------------update Case status Information----------------------------------


public function UpdatereviewCasestatus_post()
    {
       $postData = $this->post();
       // $postData["user_id"]= $this->user->user_id;

        $Casestatus = $this->taxfile->UpdatereviewCasestatus($postData);
        $response['Casestatus'] = $Casestatus;
         $this->set_response($response, REST_Controller::HTTP_OK);
        /*if($ClientInfo)
        {
         $response['clientInfo'] = $ClientInfo;
         $this->set_response($response, REST_Controller::HTTP_OK);
         }
         else
      {
         $this->set_response(getErrorMessages(validation_errors()), REST_Controller::HTTP_EXPECTATION_FAILED);
      }
         */
    


  }









}
