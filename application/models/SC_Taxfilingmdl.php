<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SC_Taxfilingmdl extends MY_Model {
    
      
    public function __construct() {
        parent::__construct();
      
        // Model intilizations
        $this->_table = 'user_master';
        //$this->validate = $this->config->item('sdf');
        $this->load->library('subquery');
       $this->currentyear = date('Y');
       $this->year = $this->currentyear -1 ;
  

    }

    public function getCountryName($id) {
    	$this->_table = 'country_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where('isActive', 1)
    	->where('id', $id)
    	->get()
    	->row();
    }
    public function getStateName($id) {
    	$this->_table = 'state_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where('isActive', 1)
    	->where('id', $id)
    	->get()
    	->row();
    }
  	public function uploadDocument($document_id, $document_type, $files, $user_id) {

		if($document_type == "Taxpayer")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/general_information/';
			$filepath = '/assets/document/general_information/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'General Information';
		}
		else if($document_type == "SourceList")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/source_list/';
			$filepath = '/assets/document/source_list/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Source List';
		}
		else if($document_type == "Citizenship")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/citizenship/';
			$filepath = '/assets/document/citizenship/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Citizenship';
		}
 	        else if($document_type == "Income")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/Income/';
			$filepath = '/assets/document/Income/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Income';
		}
		else if($document_type == "Sale Assets")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/sale_assets/';
			$filepath = '/assets/document/sale_assets/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Sale Assets';
		}
		else if($document_type == "Itemized Deduction")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/itemized_deduction/';
			$filepath = '/assets/document/itemized_deduction/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Itemized Deduction';
		}
		else if($document_type == "Moving Dep")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/moving_dep/';
			$filepath = '/assets/document/moving_dep/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Moving Dep';
		}




		$result = $this->db->select ( 'user_id' )->from ( $this->_table )->where ( 'id', $document_id )->get ()->row ();
		// print_r($result);return;
		$userData = "error";
		if ($result) {
			
			if (! empty ( $files )) {
				for ($i=0; $i < count($files ['name']); $i++ ) {
					$filename = $files['name'][$i];
					$filetype = $files['type'][$i];
					if($filetype == "application/pdf")
					{
						$filetype= ".pdf";
					}
					//$filename .= $filetype;
					$path .= $filename;
					$filepath .= $filename;
					$tmp_name = $files['tmp_name'][$i];
					
					if(move_uploaded_file($tmp_name, $path))
					{
						$wpfile = array (
								// 'usermaster_id' => $userData ['usermaster_id'],
								'year' => $this->year,
								'user_id' => $user_id,
								'file_id' => $document_id,
								'filepath' => $filepath,
								'filename' => $filename,
								'upload_page' =>  $upload_page
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
//---------------------------------------------------Tax Provider  details  -------------------------------------


    
     public function getTaxproviderDetails() {
        $this->_table = 'taxprovider_master';
        return $this->db->select('id, name,contact,price,address,description')
                        ->from($this->_table)
                        ->where('isActive', 1)
                        ->get()
                        ->result();
    }

    public function getTaxproviderDetailsById($userData) {
        $this->_table = 'taxprovider_master';
        return $this->db->select('id, name,contact,price,address,description')
                        ->from($this->_table)
                        ->where('isActive', 1)
                        ->where('id',$userData["id"])
                        ->get()
                        ->row();
    }

  public function getTaxproviderIdDetails($userData) {
    //print_r($userData);return;
    // $taxpayer_id = $this->gettaxpayerByid($userData ["user_id"])->taxprovider_id;

   // print_r($taxpayer_id); return;
        $this->_table = 'taxprovider_master';
        return $this->db->select('id, name,contact,price,address,description')
                        ->from($this->_table)
                       // ->where('isActive', 1)getTaxproviderIdDetails
                        ->where('id',$userData)
                        ->get()
                        ->row();
    }



     public function gettaxpayerByid($user_id) {
                 /* $year = date('Y');
               $year = $year -1;*/
                  $result = $this->db->select ( 'taxprovider_id as id, status' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $user_id["user_id"] )->get ()->row ();
                  return $result;

         }


    
    public function updateTaxprovider($userData) {
                                       // print_r($userData) ;return;
        // $taxpayer_id = $this->gettaxpayerid($userData ["user_id"])->id;
/*
      $year = date('Y');
       $year = $year -1;*/
       $this->_table = 'taxpayer_tbl';
    
          $user = array (
        'taxprovider_id' => $userData ['taxprovider_id'],
          );
     // $this->db->update ( $userData ['id'], $user );
      $this->db->where('user_id',$userData['user_id']);
      $result= $this->db->update($this->_table, $user);

    if($result)
    {
    return  $user ;
  }
  else
  {
    return false ;
  }
  }
   
    

      public function updateTaxproviderDetails($userData) {
        $this->_table = "taxprovider_master";
        $result = $this->db->select ( 'id' )->from ( 'taxprovider_master' )->where ( 'name', $userData ["name"] )->get ()->row ();
        if($result)
        {

           $msg = "Already Exists";
          return $msg; 
   
        }
        else
        {

          $user = array (
        'name' => $userData ['name'],
        'contact' => $userData ['contact'],
        'price' => $userData ['price'],
        'address' => $userData ['address'],
        'description' => $userData ['description']
    );
     // $this->db->update ( $userData ['id'], $user );
      $this->db->where('id',$userData['id']);
      $this->db->update($this->_table, $user);
    
    return  $user ;
   
  }
    }    

    //----------------------------------------Sourcelist Details------------------------------------

         public function gettaxpayerid($user_id) {
          
                  $year = date('Y');
               $year = $year -1;
                  $result = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $user_id["user_id"] )->get ()->row ();
                  return $result;

         }



     public function getSourcelistDetails() {
        $this->_table = 'sourcelist_tbl';
        return $this->db->select('id, consent_auth_letter_signed,copy_passport_visa_people_reported,travel_history,lastyear_federal_state_return, india_return,w2_all_income_docs_stat_1099,hsa_distribute_doc_1099_sa,receive_rent_from_real_estate_other,foreign_income_earned,proof_usa_property_sold,income_spouse,rent_paid_stay_usa,user_id,taxpayer_id,status')
                        ->from($this->_table)
                        ->get()
                        ->result();
    }



     public function getSourcelistDetailsById($userData) {

        // $taxpayer_id = $this->gettaxpayerid($userData ["user_id"])->id;

        $this->_table = 'sourcelist_tbl';
        $result = $this->db->select('id, consent_auth_letter_signed,copy_passport_visa_people_reported,travel_history,lastyear_federal_state_return, india_return,w2_all_income_docs_stat_1099,hsa_distribute_doc_1099_sa,receive_rent_from_real_estate_other,foreign_income_earned,proof_usa_property_sold,income_spouse,rent_paid_stay_usa,user_id,taxpayer_id,status')
                        ->from($this->_table)
                        ->where ( 'taxpayer_id', $userData)
                        ->where ( 'year', $this->year)
                         ->get()
                        ->row();
                        
                      //  $query = $this->db->last_query();
    		//echo ($query);
    		return $result;
    }

  public function addSourcelistDetails($userData , $files)
  {

//print_r($userData); return;

    $year = date('Y');
       $year = $year -1;
      //$this->_table = 'taxpayer_tbl';

        $id = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
       // echo $id->id;
            // print_r($id); return;

        if($id)
        {
            
      
               if(isset($userData['id']))
               {
                //Update
              $this->_table = 'sourcelist_tbl';

          $user = array(
                //'user_id' => $userData['user_id'],
                'consent_auth_letter_signed' => $userData['consent_auth_letter_signed'],
                'copy_passport_visa_people_reported' => $userData['copy_passport_visa_people_reported'],
                'travel_history' => $userData['travel_history'],
                'lastyear_federal_state_return' => $userData['lastyear_federal_state_return'],
              
                'india_return' => $userData['india_return'],
                'w2_all_income_docs_stat_1099' => $userData['w2_all_income_docs_stat_1099'],
                'hsa_distribute_doc_1099_sa' => $userData['hsa_distribute_doc_1099_sa'],
                'receive_rent_from_real_estate_other' => $userData['receive_rent_from_real_estate_other'],

                'foreign_income_earned' => $userData['foreign_income_earned'],
                'proof_usa_property_sold' => $userData['proof_usa_property_sold'],
                'income_spouse' => $userData['income_spouse'],
                'rent_paid_stay_usa' => $userData['rent_paid_stay_usa'],
                'status' => '1'
               );               

            $this->db->where('id', $userData['id']);
          $update=  $this->db->update($this->_table, $user);
                          if ($update) {
                             if (! empty ( $files )) {
                                $upload_id = $this->uploadDocument($id->id, "SourceList", $files, $userData ['user_id']);

		                 }
                                            return "updated";
                               } else {
                                            return FALSE;
                                         }

               }
               else
               {
                 //insert


                   
              $this->_table = 'sourcelist_tbl';

          $user = array(
                'year' => $this->year,
                //'user_id' => $userData['user_id'],
                'consent_auth_letter_signed' => $userData['consent_auth_letter_signed'],
                'copy_passport_visa_people_reported' => $userData['copy_passport_visa_people_reported'],
                'travel_history' => $userData['travel_history'],
                'lastyear_federal_state_return' => $userData['lastyear_federal_state_return'],
              
                'india_return' => $userData['india_return'],
                'w2_all_income_docs_stat_1099' => $userData['w2_all_income_docs_stat_1099'],
                'hsa_distribute_doc_1099_sa' => $userData['hsa_distribute_doc_1099_sa'],
                'receive_rent_from_real_estate_other' => $userData['receive_rent_from_real_estate_other'],

                'foreign_income_earned' => $userData['foreign_income_earned'],
                'proof_usa_property_sold' => $userData['proof_usa_property_sold'],
                'income_spouse' => $userData['income_spouse'],
                'rent_paid_stay_usa' => $userData['rent_paid_stay_usa'],
                'user_id' => $userData['user_id'],
                'taxpayer_id' => $id->id,
                'status' => '1'
               ); 
                 $this->db->insert($this->_table, $user);
                  $insert_id = $this->db->insert_id();
                 if ($insert_id) {
                        $upload_id = $this->uploadDocument($insert_id, "SourceList", $files, $userData ['user_id']);

		 }
                 return $insert_id;
               }

        }
        else
        {
          $msg= "Not found";
          return $msg;

        }

  }





//------------------------------------------Add Tax Information ---------------------------------------
    public function addtaxfile($userData, $files)
    {
        
      if(isset($userData['id']) && $userData['id'] != null)
      {
        //update


         $user = array(
                //'user_id' => $userData['user_id'],
                'f_name' => $userData['f_name'],
                'l_name' => $userData['l_name'],
                'gender' => $userData['gender'],
                'pan_no' => $userData['pan_no'],
              
                'ssn_itin_no' => $userData['ssn_itin_no'],
                'dob' => $userData['dob'],
                'designation' => $userData['designation'],
                'father_name' => $userData['father_name'],

                'marital_status' => $userData['marital_status'],
                'filing_status' => $userData['filing_status'],
                'permanent_home' => $userData['permanent_home'],
                'email_official' => $userData['email_official'],
              
                'email_personal' => $userData['email_personal'],
                'contact_india' => $userData['contact_india'],
                'contact_usa' => $userData['contact_usa'],
                'address_india' => $userData['address_india'],

                'address_usa' => $userData['address_usa'],
                'perferred_country' => $userData['perferred_country'],
                'bankname_usa' => $userData['bankname_usa'],
                'acctype_usa' => $userData['acctype_usa'],

                'accno_usa' => $userData['accno_usa'],
                'ifsc_usa' => $userData['ifsc_usa'],
                'bankname_india' => $userData['bankname_india'],
                'acctype_india' => $userData['acctype_india'],
                 
                'accno_india' => $userData['accno_india'],
                'ifsc_india' => $userData['ifsc_india'],
                'prev_employment' => $userData['prev_employment'],
                'payroll_type' => $userData['payroll_type'],

                'payroll_date' => $userData['payroll_date'],
                //'taxprovider_id' => $userData['taxprovider_id'],
               // 'current_year'=>$year,
                'status' => '1'
               

        );

          $dependents = array();
                 $spouseData =  $userData['spouse'];

           $spouse = array(

                'f_name' => $spouseData['f_name'],
                'l_name' => $spouseData['l_name'],
                'gender' => $spouseData['gender'],
                'pan_no' => $spouseData['pan_no'],
              
                'ssn_itin_no' => $spouseData['ssn_itin_no'],
                'dob' => $spouseData['dob'],
                'designation' => $spouseData['designation'],
                'email_official' => $spouseData['email_official'],

                'email_personal' => $spouseData['email_personal'],
                'contact_india' => $spouseData['contact_india'],
                'contact_usa' => $spouseData['contact_usa'],
                'relation_type'=> 'Spouse',
                //'user_id' => $userData['user_id'],
               // 'taxpayer_id'=>  $insert_id
        );

       $this->db->trans_begin(); 
       $this->_table = 'taxpayer_tbl';
       

        $this->update ( $userData ['id'], $user );
       // print_r( $userData['spouse']['id']); return;
         /*if($this->update ( $userData ['id'], $user ))
      {*/
        // $insert_id = $this->db->insert_id();
              if (! empty ( $files )) {
                        $upload_id = $this->uploadDocument($userData ['id'], "Taxpayer", $files, $userData ['user_id']);

     }


      $this->_table = "depantant_tbl";
      $this->db->where('id',$spouseData['id']);
      $this->db->update($this->_table, $spouse);



       // $this->update($spouseData['id'], $spouse );
        $dependentsData =  $userData['dependents'];
       if(count($dependentsData) > 0)
        {
           $this->_table = "depantant_tbl";
          for($i=0; $i< count($dependentsData); $i++)
          {
            if(isset($dependentsData[$i]['id']))
            {
               $dependents= array(

                      'f_name' => $dependentsData[$i]['f_name'],
                      'l_name' => $dependentsData[$i]['l_name'],
                      'gender' => $dependentsData[$i]['gender'],
                      'pan_no' => $dependentsData[$i]['pan_no'],
      
                      'ssn_itin_no' => $dependentsData[$i]['ssn_itin_no'],
                      'dob' => $dependentsData[$i]['dob'],
                    // 'designation' => $dependentsData[$i]['designation'],
      
                      'relation_type'=> 'Dependents',
                      'user_id' => $userData['user_id']
               
                    //  'taxpayer_id'=>  $insert_id
              );
                $this->db->where('id',$dependentsData[$i]['id']);
                $this->db->update($this->_table, $dependents);
              }
              else
              {
                       $dependents= array(

                      'f_name' => $dependentsData[$i]['f_name'],
                      'l_name' => $dependentsData[$i]['l_name'],
                      'gender' => $dependentsData[$i]['gender'],
                      'pan_no' => $dependentsData[$i]['pan_no'],
      
                      'ssn_itin_no' => $dependentsData[$i]['ssn_itin_no'],
                      'dob' => $dependentsData[$i]['dob'],
//designation' => $dependentsData[$i]['designation'],
      
                      'relation_type'=> 'Dependents',
                      'user_id' => $userData['user_id'],
               
                      'taxpayer_id'=> $userData['id']
              );
                
                $this->db->insert($this->_table, $dependents);
              }

          }
        }


       // $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
           return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();

             $msg = "updated";
              return $msg;
           // return TRUE;
        }
        
        //$this->db->insert_batch($this->_table, $dependents);

         
     /* }
      else
      {
        $msg = "Error";
        return $msg ;
      }*/
              
      
      }
      else
      {
        //insert
         $this->db->trans_start();
        $user = array(
                'user_id' => $userData['user_id'],
                'f_name' => $userData['f_name'],
                'l_name' => $userData['l_name'],
                'gender' => $userData['gender'],
                'pan_no' => $userData['pan_no'],
              
                'ssn_itin_no' => $userData['ssn_itin_no'],
                'dob' => $userData['dob'],
                'designation' => $userData['designation'],
                'father_name' => $userData['father_name'],

                'marital_status' => $userData['marital_status'],
                'filing_status' => $userData['filing_status'],
                'permanent_home' => $userData['permanent_home'],
                'email_official' => $userData['email_official'],
              
                'email_personal' => $userData['email_personal'],
                'contact_india' => $userData['contact_india'],
                'contact_usa' => $userData['contact_usa'],
                'address_india' => $userData['address_india'],

                'address_usa' => $userData['address_usa'],
                'perferred_country' => $userData['perferred_country'],
                'bankname_usa' => $userData['bankname_usa'],
                'acctype_usa' => $userData['acctype_usa'],

                'accno_usa' => $userData['accno_usa'],
                'ifsc_usa' => $userData['ifsc_usa'],
                'bankname_india' => $userData['bankname_india'],
                'acctype_india' => $userData['acctype_india'],
                 
                'accno_india' => $userData['accno_india'],
                'ifsc_india' => $userData['ifsc_india'],
                'prev_employment' => $userData['prev_employment'],
                'payroll_type' => $userData['payroll_type'],

                'payroll_date' => $userData['payroll_date'],
                //'taxprovider_id' => $userData['taxprovider_id'],
                //'current_year'=>$year,
                'status' => '1'
               

        );
        
         if( $this->insert($user))
      {
         $insert_id = $this->db->insert_id();

                  if ($insert_id) {
                        $upload_id = $this->uploadDocument($insert_id, "Taxpayer", $files, $userData ['user_id']);

     }

               $this->_table = "depantant_tbl";
                  $dependents = array();
                 $spouseData =  $userData['spouse'];

           $spouse = array(

                'f_name' => $spouseData['f_name'],
                'l_name' => $spouseData['l_name'],
                'gender' => $spouseData['gender'],
                'pan_no' => $spouseData['pan_no'],
              
                'ssn_itin_no' => $spouseData['ssn_itin_no'],
                'dob' => $spouseData['dob'],
                'designation' => $spouseData['designation'],
                'email_official' => $spouseData['email_official'],

                'email_personal' => $spouseData['email_personal'],
                'contact_india' => $spouseData['contact_india'],
                'contact_usa' => $spouseData['contact_usa'],
                'relation_type'=> 'Spouse',
                'user_id' => $userData['user_id'],
                'taxpayer_id'=>  $insert_id,
                'year' => $this->year
        );

        $this->insert($spouse);
        $dependentsData =  $userData['dependents'];
        if(count($dependentsData) > 0)
        {
          for($i=0; $i< count($dependentsData); $i++)
          {
               $dependents[] = array(

                      'f_name' => $dependentsData[$i]['f_name'],
                      'l_name' => $dependentsData[$i]['l_name'],
                      'gender' => $dependentsData[$i]['gender'],
                      'pan_no' => $dependentsData[$i]['pan_no'],
      
                      'ssn_itin_no' => $dependentsData[$i]['ssn_itin_no'],
                      'dob' => $dependentsData[$i]['dob'],
                     // 'designation' => $dependentsData[$i]['designation'],
      
                      'relation_type'=> 'Dependents',
                      'user_id' => $userData['user_id'],
                      'taxpayer_id'=>  $insert_id,
                      'year' => $year
              );
          }
        }

         $this->db->insert_batch($this->_table, $dependents);

        // return $insert_id;


       $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
           return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();

              return "added";
           // return TRUE;
        }

      }
      else
      {
        return;
      }




      }





       
}

//----------------------------------Get Tax information-----------------------------


 public function gettaxfileInfo($userData) {
        $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

        $result = $this->db->select ('id,user_id,f_name,l_name,gender,pan_no,  
                ssn_itin_no,dob,designation,father_name,marital_status,filing_status,permanent_home,email_official,email_personal,contact_india,contact_usa,address_india,address_usa,perferred_country,bankname_usa,acctype_usa,accno_usa,ifsc_usa,bankname_india,acctype_india,accno_india,ifsc_india,prev_employment,payroll_type,payroll_date,status' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
        return $result;

    }


     public function gettaxfileInfoBypost($userData) {
        $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

        $result = $this->db->select ('id,user_id,f_name,l_name,gender,pan_no,  
                ssn_itin_no,dob,designation,father_name,marital_status,filing_status,permanent_home,email_official,email_personal,contact_india,contact_usa,address_india,address_usa,perferred_country,bankname_usa,acctype_usa,accno_usa,ifsc_usa,bankname_india,acctype_india,accno_india,ifsc_india,prev_employment,payroll_type,payroll_date,status' )->from ( 'taxpayer_tbl' )->where ( 'id', $userData ["id"] )->get ()->row ();
        return $result;

    }

     public function getdepententInfo($userData) {
        
      $this->_table = 'depantant_tbl';
         $Dependents ="Dependents";
        $result = $this->db->select ('id ,f_name ,l_name,gender,pan_no,ssn_itin_no,dob,relation_type' )->from ( 'depantant_tbl' )->where ( 'taxpayer_id', $userData )->where ( 'relation_type', $Dependents )->get ()->result();
        return $result;

    }
    
       public function getspouseInfo($userData) {  
          $Spouse = "Spouse";
        $result = $this->db->select ('id ,f_name,l_name,gender,pan_no,ssn_itin_no,dob,designation,email_official,email_personal,contact_india,contact_usa,relation_type')->from ( $this->_table = 'depantant_tbl' )-> where ( 'taxpayer_id', $userData )->where ( 'relation_type', $Spouse )->get ()->row();
        return $result;

    }

public function getFileListById($id, $doc_type, $upload_page)
	     {
		$subject =  $_SERVER['SCRIPT_NAME'];
		$subArr = explode("/", $subject);
		//array_pop($subArr);
		$subArr = array_slice($subArr, 0, -2);
		$path = implode("/",$subArr);
		$path = "http://".$_SERVER['SERVER_NAME'].$path."";
		

		if($doc_type == "Taxpayer")
		{
  		 $this->_table = "taxpayerfile_tbl";
                }
		$result = $this->db->select ( 'id,user_id,CONCAT("'.$path.'", filepath) as filepath,filename' )
                ->from ( $this->_table )
                ->where ( 'file_id', $id )
                 ->where ( 'year', $this->year )
                ->where('upload_page', $upload_page)
                ->get ()->result ();
		
		return $result;
	}

    //-------------------------------------------Get Taxpayer Common Information -----------------------------------



 // public function gettaxfileIdInfo($userData) {
        
 //        $this->_table = 'taxpayer_tbl';

 //        $result = $this->db->select ('id')->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
 //       return $result;


 //    }

    public function gettaxfileCommonInfo($userData) {
       //print_r($userData);return;
      $this->_table = 'citizenship_tbl';

       $result = $this->db->select ('id, greenCard_holder, aadharcard, visa, visaType, issuedDate, passport_no, expiry_date, status');
        $sub = $this->subquery->start_subquery ( 'select' );
        $sub->select ( 'f_name' )->from ( 'taxpayer_tbl' );
        $sub->where ( $this->_table . '.taxpayer_id = taxpayer_tbl.id' );
        $this->subquery->end_subquery ( 'name' );
        
        $this->db->from ( $this->_table );
        $this->db->where ( 'taxpayer_id', $userData["id"] );
        $this->db->where ( 'current_year', $this->year );
        return $this->db->get ()->row ();
       
        //$query =  $this->db->last_query();
        //print_r($query);return;
        
    }
    

     public function gettaxfileyearInfo($userData,$year) {
          $this->_table = 'citizenship_tbl';

        $result = $this->db->select ('id, no_of_days,entry_date,exit_date')
          ->from ( $this->_table  )
          ->where ( 'user_id', $userData ["user_id"] )
          ->where ( 'current_year', $year )
          ->get ()->row ();
        return $result;

    }

    public function gettaxfilestateInfo($id) {
          $this->_table = 'taxayer_stateentry_tbl';
             $result = $this->db->select ('id, state_id,citizenship_id, taxpayer_id,entry_date,exit_date')
             ->from ( $this->_table )
             ->where ('citizenship_id', $id )
             ->get ()->result ();
        return $result;
        //return $this->db->query ( "(SELECT id, state_id, entry_date,exit_date FROM `taxayer_stateentry_tbl` where citizenship_id ='" . $id . "')" )->result ();

    }

     public function getstateByidInfo($id) {
          $this->_table = 'state_master';

        $result = $this->db->select ('id,name')->from ( 'state_master' )->where ('id', $id )->get ()->row ();
        return $result;

    }


     public function getspouseCommonInfo($taxpayer_id) {  

          $Spouse = "Spouse";
          $this->_table = 'citizenship_depantant_tbl';
        /*$result = $this->db->select ('id, "" as name,aadharcard,visa,visaType,issuedDate,passport_no,expiry_date')
        ->from ( $this->_table )
        -> where ( 'taxpayer_id', $userData )
        ->where ( 'relation_type', $Spouse )
        ->get ()->row();
*/
/*          $result = $this->db->select ('id, aadharcard, visa, visaType, issuedDate, passport_no, expiry_date');
        $sub = $this->subquery->start_subquery ( 'select' );
        $sub->select ( 'f_name' )->from ( 'taxpayer_tbl' );
        $sub->where ( $this->_table . '.depantant_id = depantant_tbl.id' );
        $this->subquery->end_subquery ( 'name' );
        
        $this->db->from ( $this->_table );
        $this->db->where ( 'taxpayer_id', $taxpayer_id);
        $this->db->where ( 'current_year', $this->year );
        $this->db->get ()->row ();
*/        
        $result = $this->db->query("SELECT A.id, B.f_name as name, A.aadharcard, A.visa, A.visaType, A.issuedDate, A.passport_no, A.expiry_date, B.relation_type FROM citizenship_depantant_tbl AS A, depantant_tbl As B WHERE A.depantant_id = B.id AND A.taxpayer_id = '".$taxpayer_id."' AND B.relation_type = 'Spouse' AND `current_year` = ".$this->year)->row ();

        //$query =  $this->db->last_query();
        //print_r($query);return;
        

        return $result;

    }

    public function getspouseCommonyearInfo($userData,$year) {
          $Spouse = "Spouse";
        $this->_table  = 'citizenship_depantant_tbl';
        $result = $this->db->select ('id, no_of_days,entry_date,exit_date')
        ->from ( $this->_table )
        ->where ( 'depantant_id', $userData  )
        ->where ('current_year',$year)
        //->where ( 'relation_type', $Spouse )
        ->get ()->row ();
        return $result;

    }

    public function getCommonstateInfo($id) {
          $this->_table = 'depantant_stateentry_tbl';

return $this->db->query ( "(SELECT id, citizenship_id,depantant_id,state_id,entry_date,exit_date FROM depantant_stateentry_tbl where citizenship_id ='" . $id . "')" )->result ();

    }


     public function getdepententCommonInfo($taxpayer_id) {
        
      $this->_table = 'citizenship_depantant_tbl';
         $Dependents ="Dependents";
         //name inner join query written
        /*$result = $this->db->select ('id, "" as name,aadharcard,visa,visaType,issuedDate,passport_no,expiry_date' )->from ( $this->_table )->where ( 'taxpayer_id', $userData )->where ( 'relation_type', $Dependents )->get ()->result();
        return $result;*/

           $result = $this->db->query("SELECT A.id, B.f_name as name,  A.aadharcard, A.visa, A.visaType, A.issuedDate, A.passport_no, A.expiry_date, B.relation_type, B.id as depentant_id FROM citizenship_depantant_tbl AS A, depantant_tbl As B WHERE A.depantant_id = B.id AND A.taxpayer_id = '".$taxpayer_id."' AND B.relation_type = 'Dependents' AND `current_year` = ".$this->year)->result ();
           //$query =  $this->db->last_query();
         //print_r($query);return;

          return $result;
    }


     public function getdepententyearInfo($userData,$year) {
         $Dependents ="Dependents";

        $result = $this->db->select ('id, no_of_days,entry_date,exit_date')
        ->from ( 'citizenship_depantant_tbl' )
        ->where ( 'depantant_id', $userData)
        ->where ('current_year',$year)
        //->where ( 'relation_type', $Dependents )
        ->get ()->row ();
        // $query =  $this->db->last_query();
         //print_r($query);return;

        return $result;

    }

    

    
      




//-------------------------------------------------Get Income information--------------------------------------

public function getTaxpayer_ID($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

        $result = $this->db->select ('id, f_name' )
        ->from ( 'taxpayer_tbl' )
        ->where ( 'user_id', $userData ["user_id"] )
       // ->where ( 'current_year', $year )
        ->get ()
        ->row ();
        return $result;
}



public function getIncomeInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

      $taxpayer_ID =$userData;
      
      $this->_table = 'income_usintereset';

      $USInteresetIncome = $this->db->select ('id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
         ->where ( 'year', $this->year )
        ->get ()
        ->row ();
        if($USInteresetIncome->status)
        {
             $status = $USInteresetIncome->status;
        }
        else if($USInteresetIncome->status == "")
        {
        $ForeignInteresetIncome = $this->db->select ('id,  status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->row ();
        $status = $ForeignInteresetIncome->status;
         }
         elseif($ForeignInteresetIncome->status == "")
         {
           $USDividendIncome = $this->db->select ('id,  status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->row ();
          $status = $USDividendIncome->status;
         }
         else
         {
          $ForeignDividendIncome = $this->db->select ('id, company_name, received_dividend, tax_deducted, country_id, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->row ();
          $status = $ForeignDividendIncome->status;
         }
        //print_r($USInteresetIncome->status);return;

       $USInteresetIncome = $this->db->select ('id, bank_name, received_interest, tax_held, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
         ->where ( 'year', $this->year )
        ->get ()
        ->result ();
          
        $this->_table = 'income_foreignintereset';

        $ForeignInteresetIncome = $this->db->select ('id, bank_name, received_interest, tax_deducted, country_id, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->result ();

        for($i=0;$i<count($ForeignInteresetIncome);$i++)
        {
               $ForeignIntereset = $ForeignInteresetIncome[$i];
                $ForeignIntereset->country = $this->getCountryName($ForeignIntereset->country_id);
        }
         $this->_table = 'income_usdividend';

        $USDividendIncome = $this->db->select ('id, payer_name,	ordinary_dividend, qualified_dividend, capital_gains, federal_incometax, foreign_taxpaid, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->result ();
        
         $this->_table = 'income_foreigndividend';

        $ForeignDividendIncome = $this->db->select ('id, company_name, received_dividend, tax_deducted, country_id, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
          ->where ( 'year', $this->year )
        ->get ()
        ->result ();
        
        for($i=0;$i<count($ForeignDividendIncome);$i++)
        {
               $ForeignDividend = $ForeignDividendIncome[$i];
                $ForeignDividend->country = $this->getCountryName($ForeignDividend->country_id);
        }
        
        $fileList = $this->getFileListById ( $taxpayer_ID, "Taxpayer", "Income" );

        $response['USInteresetIncome'] = $USInteresetIncome;
        $response['ForeignInteresetIncome'] = $ForeignInteresetIncome;
         $response['USDividendIncome'] = $USDividendIncome;
        $response['ForeignDividendIncome'] = $ForeignDividendIncome;
        $response['fileList'] = $fileList;
       $response['status'] = $status;
        return $response;
}

public function addIncomeInfo($userData, $files)
{
//  print_r($userData);  return;
    $year = date('Y');
       $year = $year -1;
       //$taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      $this->_table = 'taxpayer_tbl';

      $result = $this->db->select ( 'id, user_id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
      if($result)
      {
           $taxpayer_ID = $result->id;

        $USInteresetIncome =  $userData['USInteresetIncome'];
       if(count($USInteresetIncome) > 0)
        {
           $this->_table = 'income_usintereset';;
          for($i=0; $i< count($USInteresetIncome); $i++)
          {
            if(isset($USInteresetIncome[$i]['id']))
            {
               $USIntereset= array(
                      'bank_name' => $USInteresetIncome[$i]['bank_name'],
                      'received_interest' => $USInteresetIncome[$i]['received_interest'],
                      'tax_held' => $USInteresetIncome[$i]['tax_held']
               );
                $this->db->where('id',$USInteresetIncome[$i]['id']);
                $this->db->update($this->_table, $USIntereset);
              }
              else
              {
                      $USIntereset= array(
                      'year' => $this->year, 
                      'bank_name' => $USInteresetIncome[$i]['bank_name'],
                      'received_interest' => $USInteresetIncome[$i]['received_interest'],
                      'tax_held' => $USInteresetIncome[$i]['tax_held'],
                         'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $USIntereset);
              }

          }
        }
        
        
         $ForeignInteresetIncome =  $userData['ForeignInteresetIncome'];
       if(count($ForeignInteresetIncome) > 0)
        {
           $this->_table = 'income_foreignintereset';;
          for($i=0; $i< count($ForeignInteresetIncome); $i++)
          {
            if(isset($ForeignInteresetIncome[$i]['id']))
            {
               $ForeignIntereset= array(
                      'bank_name' => $ForeignInteresetIncome[$i]['bank_name'],
                      'received_interest' => $ForeignInteresetIncome[$i]['received_interest'],
                      'tax_deducted' => $ForeignInteresetIncome[$i]['tax_deducted']
               );
                $this->db->where('id',$ForeignInteresetIncome[$i]['id']);
                $this->db->update($this->_table, $ForeignIntereset);
              }
              else
              {
                      $ForeignIntereset= array(
                                            'year' => $this->year,
                      'bank_name' => $ForeignInteresetIncome[$i]['bank_name'],
                      'received_interest' => $ForeignInteresetIncome[$i]['received_interest'],
                      'tax_deducted' => $ForeignInteresetIncome[$i]['tax_deducted'],
                      'country_id' => $ForeignInteresetIncome[$i]['country']['id'],
                         'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $ForeignIntereset);
              }

          }
        }
        
        $USDividendIncome =  $userData['USDividendIncome'];
       if(count($USDividendIncome) > 0)
        {
           $this->_table = 'income_usdividend';;
          for($i=0; $i< count($USDividendIncome); $i++)
          {
            if(isset($USDividendIncome[$i]['id']))
            {
               $USDividend= array(
                      'payer_name' => $USDividendIncome[$i]['payer_name'],
                      'ordinary_dividend' => $USDividendIncome[$i]['ordinary_dividend'],
                      'qualified_dividend' => $USDividendIncome[$i]['qualified_dividend'],
                       'capital_gains' => $USDividendIncome[$i]['capital_gains'],
                        'federal_incometax' => $USDividendIncome[$i]['federal_incometax'],
                         'foreign_taxpaid' => $USDividendIncome[$i]['foreign_taxpaid']
               );
                $this->db->where('id',$USDividendIncome[$i]['id']);
                $this->db->update($this->_table, $USDividend);
              }
              else
              {
                      $USDividend= array(
                                            'year' => $this->year,
                      'payer_name' => $USDividendIncome[$i]['payer_name'],
                      'ordinary_dividend' => $USDividendIncome[$i]['ordinary_dividend'],
                      'qualified_dividend' => $USDividendIncome[$i]['qualified_dividend'],
                       'capital_gains' => $USDividendIncome[$i]['capital_gains'],
                        'federal_incometax' => $USDividendIncome[$i]['federal_incometax'],
                         'foreign_taxpaid' => $USDividendIncome[$i]['foreign_taxpaid'],
                         'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $USDividend);
              }

          }
        }

        $ForeignDividendIncome =  $userData['ForeignDividendIncome'];
       if(count($ForeignDividendIncome) > 0)
        {
           $this->_table = 'income_foreigndividend';;
          for($i=0; $i< count($ForeignDividendIncome); $i++)
          {
            if(isset($ForeignDividendIncome[$i]['id']))
            {
               $ForeignDividend= array(
                      'company_name' => $ForeignDividendIncome[$i]['company_name'],
                      'received_dividend' => $ForeignDividendIncome[$i]['received_dividend'],
                      'tax_deducted' => $ForeignDividendIncome[$i]['tax_deducted'],
                       'country_id' => $ForeignDividendIncome[$i]['country']['id'],

               );
                $this->db->where('id',$ForeignDividendIncome[$i]['id']);
                $this->db->update($this->_table, $ForeignDividend);
              }
              else
              {
                      $ForeignDividend= array(
                                            'year' => $this->year,
                       'company_name' => $ForeignDividendIncome[$i]['company_name'],
                      'received_dividend' => $ForeignDividendIncome[$i]['received_dividend'],
                      'tax_deducted' => $ForeignDividendIncome[$i]['tax_deducted'],
                       'country_id' => $ForeignDividendIncome[$i]['country']['id'],
                         'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $ForeignDividend);
              }

          }
        }
        
        if (! empty ( $files )) {
                                $upload_id = $this->uploadDocument($taxpayer_ID, "Income", $files, $userData ['user_id']);

		                 }

          $msg = "Updated Successfuly";
              return $msg;
      }
      else
      {
        $msg = "Error";
        return $msg ;
      }

}
     //-------------------------------------------------Get Income information--------------------------------------



public function getAssestsInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

      $taxpayer_ID = $userData;
      
      $this->_table = 'assets_tbl';

        $USAssets = $this->db->select ('id, description, purchased_date, sold_date, sales_price, purchase_price, state_id, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
         ->where ( 'type', 'USAssets' )
        ->where ( 'year', $this->year )
        ->get ()
        ->result ();
        
         for($i=0;$i<count($USAssets);$i++)
        {
               $USAsset = $USAssets[$i];
                $USAsset->state = $this->getStateName($USAsset->state_id);
        }
        $this->_table = 'assets_tbl';

        $ForeignAssets = $this->db->select ('id, description, purchased_date, sold_date, sales_price, purchase_price, country_id, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->where ( 'type', 'ForeignAssets' )
        ->where ( 'year', $this->year )
        ->get ()
        ->result ();

        for($i=0;$i<count($ForeignAssets);$i++)
        {
               $FAsset = $ForeignAssets[$i];
                $FAsset->country = $this->getCountryName($FAsset->country_id);
        }



        $fileList = $this->getFileListById ( $taxpayer_ID, "Taxpayer", "Sale Assets" );
        
            $this->_table = 'assets_tbl';
             $statusData = $this->db->select ('status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->where ( 'year', $this->year )
        ->get ()
        ->row ();

        $response['USAssets'] = $USAssets;
        $response['ForeignAssets'] = $ForeignAssets;
        $response['fileList'] = $fileList;
        $response['status'] = $statusData->status;
        return $response;
}

public function addAssetsInfo($userData, $files)
{
//  print_r($userData);  return;
    $year = date('Y');
       $year = $year -1;
       //$taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      $this->_table = 'taxpayer_tbl';

      $result = $this->db->select ( 'id, user_id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
      if($result)
      {
           $taxpayer_ID = $result->id;

        $USAssets =  $userData['USAssets'];
       if(count($USAssets) > 0)
        {
           $this->_table = 'assets_tbl';;
          for($i=0; $i< count($USAssets); $i++)
          {
            if(isset($USAssets[$i]['id']))
            {

               $USIntereset= array(
                      'description' => $USAssets[$i]['description'],
                      'purchased_date' => $USAssets[$i]['purchased_date'],
                      'sold_date' => $USAssets[$i]['sold_date'],
                        'sales_price' => $USAssets[$i]['sales_price'],
                          'purchase_price' => $USAssets[$i]['purchase_price'],
                            'state_id' => $USAssets[$i]['state']['id'],
                            'taxpayer_id' => $taxpayer_ID

               );
                $this->db->where('id',$USAssets[$i]['id']);
                $this->db->update($this->_table, $USIntereset);
              }
              else
              {
                      $USIntereset= array(
                      'year' => $this->year,
                      'type' => 'USAssets',
                       'description' => $USAssets[$i]['description'],
                      'purchased_date' => $USAssets[$i]['purchased_date'],
                      'sold_date' => $USAssets[$i]['sold_date'],
                        'sales_price' => $USAssets[$i]['sales_price'],
                          'purchase_price' => $USAssets[$i]['purchase_price'],
                            'state_id' => $USAssets[$i]['state']['id'],
                            'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $USIntereset);
              }

          }
        }
        
        
         $ForeignAssets =  $userData['ForeignAssets'];
       if(count($ForeignAssets) > 0)
        {
           $this->_table = 'assets_tbl';;
          for($i=0; $i< count($ForeignAssets); $i++)
          {
            if(isset($ForeignAssets[$i]['id']))
            {
               $ForeignIntereset= array(
                        'description' => $ForeignAssets[$i]['description'],
                      'purchased_date' => $ForeignAssets[$i]['purchased_date'],
                      'sold_date' => $ForeignAssets[$i]['sold_date'],
                        'sales_price' => $ForeignAssets[$i]['sales_price'],
                          'purchase_price' => $ForeignAssets[$i]['purchase_price'],
                            'country_id' => $ForeignAssets[$i]['country']['id'],
                            'taxpayer_id' => $taxpayer_ID
               );
                $this->db->where('id',$ForeignAssets[$i]['id']);
                $this->db->update($this->_table, $ForeignIntereset);
              }
              else
              {
                      $ForeignIntereset= array(
                                            'year' => $this->year,
                      'type' => 'ForeignAssets',
                       'description' => $ForeignAssets[$i]['description'],
                      'purchased_date' => $ForeignAssets[$i]['purchased_date'],
                      'sold_date' => $ForeignAssets[$i]['sold_date'],
                        'sales_price' => $ForeignAssets[$i]['sales_price'],
                          'purchase_price' => $ForeignAssets[$i]['purchase_price'],
                            'country_id' => $ForeignAssets[$i]['country']['id'],
                            'taxpayer_id' => $taxpayer_ID
                       );
                
                $this->db->insert($this->_table, $ForeignIntereset);
              }

          }
        }
        

        
        if (! empty ( $files )) {
                                $upload_id = $this->uploadDocument($taxpayer_ID, "Sale Assets", $files, $userData ['user_id']);

		                 }

          $msg = "Updated Successfuly";
              return $msg;
      }
      else
      {
        $msg = "Error";
        return $msg ;
      }

}
    //-------------------------------------------------Get Itemized Dedection information--------------------------------------



public function getItemizedInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

      $taxpayer_ID = $userData;
      
      $this->_table = 'itemized_deduction';

         $ItemizedInfo = $this->db->select ('id, usmedical_year, health_insurance, longterm_care, medical_miles, hospital, contacts_glasses, laser_eye_surgery, prescriptions, co_pays, physician_dentist_chiropractor, lab_fees, state_local_income_taxes, sales_tax, real_estate_taxes, property_vehicle_excise_taxes, other_taxes, home_loan_india, homeloan_interest, investment_interest, other_interest, charity_year, gifts_cash, charitable_miles, other_cash, parking_fees, travel_expense, lodging_airplane_car_rental, other_expenses, internet, phone_calls, stationary_postage, education_expenses, entertainment_expenses, safe_deposit, investment_expense, margin_investment, taxpayer_id,status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
         ->where ( 'year', $this->year )
        ->get ()
        ->row ();
        
        $fileList = $this->getFileListById ( $taxpayer_ID, "Taxpayer", "Itemized Deduction" );

//        $response['itemizedInfo'] = $ItemizedInfo;
        $ItemizedInfo->fileList = $fileList;
        return $ItemizedInfo;
}

  public function addItemizedInfo($userData, $files)
{
//  print_r($userData);  return;
    $year = date('Y');
       $year = $year -1;
       //$taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      $this->_table = 'taxpayer_tbl';

      $result = $this->db->select ( 'id, user_id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
      if($result)
      {
           $taxpayer_ID = $result->id;
                     $this->_table = 'itemized_deduction';

            if(isset($userData['id']))
            {

               $ItemizedInfo= array(

                      'usmedical_year' => $userData['usmedical_year'],
                      'health_insurance' => $userData['health_insurance'],
                      'longterm_care' => $userData['longterm_care'],
                        'medical_miles' => $userData['medical_miles'],
                          'hospital' => $userData['hospital'],
                            'contacts_glasses' => $userData['contacts_glasses'],
                            
                            'laser_eye_surgery' => $userData['laser_eye_surgery'],
                      'prescriptions' => $userData['prescriptions'],
                      'co_pays' => $userData['co_pays'],
                        'physician_dentist_chiropractor' => $userData['physician_dentist_chiropractor'],
                          'lab_fees' => $userData['lab_fees'],
                            'state_local_income_taxes' => $userData['state_local_income_taxes'],
                            
                            'sales_tax' => $userData['sales_tax'],
                      'real_estate_taxes' => $userData['real_estate_taxes'],
                      'property_vehicle_excise_taxes' => $userData['property_vehicle_excise_taxes'],
                        'other_taxes' => $userData['other_taxes'],
                          'home_loan_india' => $userData['home_loan_india'],
                            'homeloan_interest' => $userData['homeloan_interest'],
                            
                            'investment_interest' => $userData['investment_interest'],
                      'other_interest' => $userData['other_interest'],
                      'charity_year' => $userData['charity_year'],
                        'gifts_cash' => $userData['gifts_cash'],
                          'charitable_miles' => $userData['charitable_miles'],
                            'other_cash' => $userData['other_cash'],
                            

                            'parking_fees' => $userData['parking_fees'],
                      'travel_expense' => $userData['travel_expense'],
                      'lodging_airplane_car_rental' => $userData['lodging_airplane_car_rental'],
                        'other_expenses' => $userData['other_expenses'],
                          'internet' => $userData['internet'],
                            'phone_calls' => $userData['phone_calls'],
                            
                            'parking_fees' => $userData['parking_fees'],
                      'travel_expense' => $userData['travel_expense'],
                      'lodging_airplane_car_rental' => $userData['lodging_airplane_car_rental'],
                        'other_expenses' => $userData['other_expenses'],
                          'internet' => $userData['internet'],
                            'phone_calls' => $userData['phone_calls'],
                            
                            'stationary_postage' => $userData['stationary_postage'],
                      'education_expenses' => $userData['education_expenses'],
                      'entertainment_expenses' => $userData['entertainment_expenses'],
                        'safe_deposit' => $userData['safe_deposit'],
                          'investment_expense' => $userData['investment_expense'],
                            'margin_investment' => $userData['margin_investment'],
                            'taxpayer_id' => $taxpayer_ID,
                            'status' => 1
                            

               );
                $this->db->where('id',$userData['id']);
                $this->db->update($this->_table, $ItemizedInfo);
              }
              else
              {
                      $ItemizedInfo= array(
                       'year' => $this->year,
                      'usmedical_year' => $userData['usmedical_year'],
                      'health_insurance' => $userData['health_insurance'],
                      'longterm_care' => $userData['longterm_care'],
                        'medical_miles' => $userData['medical_miles'],
                          'hospital' => $userData['hospital'],
                            'contacts_glasses' => $userData['contacts_glasses'],
                            
                            'laser_eye_surgery' => $userData['laser_eye_surgery'],
                      'prescriptions' => $userData['prescriptions'],
                      'co_pays' => $userData['co_pays'],
                        'physician_dentist_chiropractor' => $userData['physician_dentist_chiropractor'],
                          'lab_fees' => $userData['lab_fees'],
                            'state_local_income_taxes' => $userData['state_local_income_taxes'],
                            
                            'sales_tax' => $userData['sales_tax'],
                      'real_estate_taxes' => $userData['real_estate_taxes'],
                      'property_vehicle_excise_taxes' => $userData['property_vehicle_excise_taxes'],
                        'other_taxes' => $userData['other_taxes'],
                          'home_loan_india' => $userData['home_loan_india'],
                            'homeloan_interest' => $userData['homeloan_interest'],
                            
                            'investment_interest' => $userData['investment_interest'],
                      'other_interest' => $userData['other_interest'],
                      'charity_year' => $userData['charity_year'],
                        'gifts_cash' => $userData['gifts_cash'],
                          'charitable_miles' => $userData['charitable_miles'],
                            'other_cash' => $userData['other_cash'],
                            

                            'parking_fees' => $userData['parking_fees'],
                      'travel_expense' => $userData['travel_expense'],
                      'lodging_airplane_car_rental' => $userData['lodging_airplane_car_rental'],
                        'other_expenses' => $userData['other_expenses'],
                          'internet' => $userData['internet'],
                            'phone_calls' => $userData['phone_calls'],
                            
                            'parking_fees' => $userData['parking_fees'],
                      'travel_expense' => $userData['travel_expense'],
                      'lodging_airplane_car_rental' => $userData['lodging_airplane_car_rental'],
                        'other_expenses' => $userData['other_expenses'],
                          'internet' => $userData['internet'],
                            'phone_calls' => $userData['phone_calls'],
                            
                            'stationary_postage' => $userData['stationary_postage'],
                      'education_expenses' => $userData['education_expenses'],
                      'entertainment_expenses' => $userData['entertainment_expenses'],
                        'safe_deposit' => $userData['safe_deposit'],
                          'investment_expense' => $userData['investment_expense'],
                            'margin_investment' => $userData['margin_investment'],
                            'taxpayer_id' => $taxpayer_ID,
                        'status' => 1

               );
                
                $this->db->insert($this->_table, $ItemizedInfo);
              }

          }


        
        if (! empty ( $files )) {
                                $upload_id = $this->uploadDocument($taxpayer_ID, "Itemized Deduction", $files, $userData ['user_id']);

		                 }

          $msg = "Updated Successfuly";
              return $msg;


}

   //-------------------------------------------------Get Moving & Dep care information--------------------------------------

      public function getdepententDetails($taxpayer_id) {
        
      $this->_table = 'depantant_tbl';
         $Dependents ="Dependents";
        $result = $this->db->select ('id ,f_name as name' )
        ->from ( 'depantant_tbl' )
        ->where ( 'taxpayer_id', $taxpayer_id )
        ->where ( 'relation_type', $Dependents )
        ->get ()
        ->result();
        return $result;

    }

public function getMovingDepInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

      $taxpayer_ID = $userData;
      
      $this->_table = 'moving_dep_tbl';

         $movingDepInfo = $this->db->select ('id, property_description, acquired_date, incident_date, insurance_claimed, fair_market_before, fair_market_after, no_miles_oldhome_new, no_miles_oldhome_old, transport_cost, travel_expenses, reimbursed_employer, provider_ssn, provider_name, provider_address, provider_paid_amount, pay_rent, rent_paid, landlord_name, landlord_address, landlord_telephone, rented_property_address, taxpayer_id, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->get ()
        ->row ();
        
        $fileList = $this->getFileListById ( $taxpayer_ID, "Taxpayer", "Moving Dep" );

          $movingDepInfo->dependents = $this->getdepententDetails($taxpayer_ID);;
        $movingDepInfo->fileList = $fileList;
        return $movingDepInfo;
}

  public function addMovingDepInfo($userData, $files)
{
//  print_r($userData);  return;
    $year = date('Y');
       $year = $year -1;
       //$taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      $this->_table = 'taxpayer_tbl';

      $result = $this->db->select ( 'id, user_id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
      if($result)
      {
           $taxpayer_ID = $result->id;
                     $this->_table = 'moving_dep_tbl';

            if(isset($userData['id']))
            {

               $MovingDepInfo= array(
                      'property_description' => $userData['property_description'],
                      'acquired_date' => $userData['acquired_date'],
                      'incident_date' => $userData['incident_date'],
                        'insurance_claimed' => $userData['insurance_claimed'],
                          'fair_market_before' => $userData['fair_market_before'],
                            'fair_market_after' => $userData['fair_market_after'],
                            
                            'no_miles_oldhome_new' => $userData['no_miles_oldhome_new'],
                      'no_miles_oldhome_old' => $userData['no_miles_oldhome_old'],
                      'travel_expenses' => $userData['travel_expenses'],
                        'reimbursed_employer' => $userData['reimbursed_employer'],
                          'provider_ssn' => $userData['provider_ssn'],
                            'provider_name' => $userData['provider_name'],

                            'provider_address' => $userData['provider_address'],
                      'provider_paid_amount' => $userData['provider_paid_amount'],
                      'pay_rent' => $userData['pay_rent'],
                        'rent_paid' => $userData['rent_paid'],
                          'landlord_name' => $userData['landlord_name'],
                            'landlord_address' => $userData['landlord_address'],
                            
                            'landlord_telephone' => $userData['landlord_telephone'],
                      'rented_property_address' => $userData['rented_property_address'],
                       'taxpayer_id' => $taxpayer_ID,
                       'status'=> 1

               );
                $this->db->where('id',$userData['id']);
                $this->db->update($this->_table, $MovingDepInfo);
              }
              else
              {
                      $MovingDepInfo= array(
                      'property_description' => $userData['property_description'],
                      'acquired_date' => $userData['acquired_date'],
                      'incident_date' => $userData['incident_date'],
                        'insurance_claimed' => $userData['insurance_claimed'],
                          'fair_market_before' => $userData['fair_market_before'],
                            'fair_market_after' => $userData['fair_market_after'],
                            
                            'no_miles_oldhome_new' => $userData['no_miles_oldhome_new'],
                      'no_miles_oldhome_old' => $userData['no_miles_oldhome_old'],
                      'travel_expenses' => $userData['travel_expenses'],
                        'reimbursed_employer' => $userData['reimbursed_employer'],
                          'provider_ssn' => $userData['provider_ssn'],
                            'provider_name' => $userData['provider_name'],
                                  							
                            'provider_address' => $userData['provider_address'],
                      'provider_paid_amount' => $userData['provider_paid_amount'],
                      'pay_rent' => $userData['pay_rent'],
                        'rent_paid' => $userData['rent_paid'],
                          'landlord_name' => $userData['landlord_name'],
                            'landlord_address' => $userData['landlord_address'],
                            
                            'landlord_telephone' => $userData['landlord_telephone'],
                      'rented_property_address' => $userData['rented_property_address'],
                       'taxpayer_id' => $taxpayer_ID,
                         'status'=> 1
               );
                
                $this->db->insert($this->_table, $MovingDepInfo);
              }

          }


        
        if (! empty ( $files )) {
                                $upload_id = $this->uploadDocument($taxpayer_ID, "Moving Dep", $files, $userData ['user_id']);

		                 }

          $msg = "Updated Successfuly";
              return $msg;


}


//---------------------------------------------FBar-----------------------------------------------------------------------------

    public function addFbarInfo($userData)
    {
     // print_r($userData);return;
      

       // $result = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
        //print_r($result->id);
        if(isset($userData['id']))
        {
          $this->_table = 'citizenship_tbl';
          $FBardata = array(
            'bank_account_nonUS' => $userData["bank_account_nonUS"] ,
            'cumulative_balances' => $userData["cumulative_balances"] ,
            'country_id' => $userData["country_id"] 
             );
             $this->db->where('id',$userData['id']);
            $this->db->where('current_year',$this->year);
            $this->db->update($this->_table, $FBardata);
             $msg = "Updated Successfuly";
              return $msg;
        }
        else
        {
               $msg = "Error";
              return $msg;

        }
}



public function getFbarInfo($userData)
{
     
   $this->_table = 'citizenship_tbl';
    $result = $this->db->select ('id,bank_account_nonUS,cumulative_balances,country_id, status' )
        ->from ( $this->_table )
        ->where ( 'id', $userData )
        ->where ( 'current_year', $this->year )
        ->get ()
        ->row();
        return $result;


}



//--------------------------------------------Form 8938 -------------------------------


public function addForm8938Info($userData)
    {
     // print_r($userData);return;
       $year = date('Y');
       $year = $year -1;
      $this->_table = 'citizenship_tbl';

        //$result = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
        //print_r($result->id);
        if(isset($userData['id']))
        {
          $form8938data = array(
            'form8938_applicable' => $userData["form8938_applicable"] 
            
             );
            $this->db->where('id',$userData['id']);
            $this->db->update($this->_table, $form8938data);
             $msg = "Updated Successfuly";
              return $msg;
        }
        else
        {
               $msg = "Error";
              return $msg;

        }
}



public function getForm8938Info($userData)
{
    
   $this->_table = 'citizenship_tbl';
    $result = $this->db->select ('id,form8938_applicable, status' )
        ->from ($this->_table )
         ->where ( 'id', $userData )
        ->where ( 'current_year', $this->year )
        ->get ()
        ->row();
        return $result;


}

//--------------------------------------------Submit Form------------------------------------------------------


public function  SubmitformallInfo($userData)
    {
      
     // $this->_table = 'taxpayer_tbl';
     //print_r($userData);return;
       $taxpayerid = $this->getTaxpayer_ID($userData)->id;
       //print_r($taxpayerid);return;
        if($taxpayerid)
        {
           $this->db->trans_begin();
          $Submitformdata = array(
              'selfemployed_activemember' => $userData["selfemployed_activemember"],
             'additional_information' => $userData["additional_information"],
                'status' => "2"
             );
            $this->db->where('id',$userData['id']);
             $this->db->update('citizenship_tbl', $Submitformdata);

           $statusdata = array(
                   'status' => "2"
           );
          //scourelist
             $this->db->where('taxpayer_id',$taxpayerid);
                $this->db->where("year",$this->year);
            $this->db->update('sourcelist_tbl', $statusdata);
            

            //income
            $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where("year",$this->year);
            $this->db->update('income_foreigndividend', $statusdata);

            $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where("year",$this->year);
            $this->db->update('income_foreignintereset', $statusdata);

             
            $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where("year",$this->year);
            $this->db->update('income_usdividend', $statusdata);

            $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where("year",$this->year);
            $this->db->update('income_usintereset', $statusdata);
            
            //Rental Income
             $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where("year",$this->year);
            $this->db->update('rentalincome_tbl', $statusdata);
            
            //business income
             $this->db->where('taxpayer_id',$taxpayerid);
              $this->db->where("year",$this->year);
           $this->db->update('businessincome_tbl', $statusdata);
           
            //Sale of Asset
            $this->db->where("taxpayer_id",$taxpayerid);
             $this->db->where("year",$this->year);
           $this->db->update('assets_tbl', $statusdata);

           //itemized deduction
             $this->db->where('taxpayer_id',$taxpayerid);
              $this->db->where("year",$this->year);
            $this->db->update('itemized_deduction', $statusdata);
            
            //moving dep
            $this->db->where('taxpayer_id',$taxpayerid);
            $this->db->where("year",$this->year);
            $this->db->update('moving_dep_tbl', $statusdata);
            
              $this->db->where('id',$taxpayerid);
            $this->db->update('taxpayer_tbl', $statusdata);
    //         $query =  $this->db->last_query();
    //         print_r($query);return;

   /*
            $this->db->where('taxpayer_id',$taxpayerid);
            $this->db->update('depantant_tbl', $statusdata);

            $this->db->where('taxpayer_id',$taxpayerid);
            $this->db->update('taxayer_stateentry_tbl', $statusdata);

*/
             $casestatusdata = array(
                    'taxpayer_id'=>$taxpayerid,
                    'year'=> $this->year,
                    'description'=>"Document submited",
                   'status' => "1"       
                                );                    
    
            $this->db->insert('casestatus_tbl', $casestatusdata);



            
               
           $this->db->trans_complete();
        //check if transaction status TRUE or FALSE
        if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
        return FALSE;
        } else {
            //if everything went right, commit the data to the database
            $this->db->trans_commit();
            return TRUE;
        }

            }
             
        
        else
        {
               $msg = "Error: Data not found";
              return $msg;

        }
}

public function addSubmitformInfo($userData)
{


       $taxpayerid = $this->getTaxpayer_ID($userData)->id;

        /*$result = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();*/
        //print_r($result->id);
        if($taxpayerid)
        {

            $this->_table = 'citizenship_tbl';
          $Submitformdata = array(
            'selfemployed_activemember' => $userData["selfemployed_activemember"],
             'additional_information' => $userData["additional_information"]
               
             );
            $this->db->where('taxpayer_id',$taxpayerid);
             $this->db->where('current_year',$this->year);
             $this->db->update($this->_table, $Submitformdata);
              }
         else
              {
               $msg = "Error";
              return $msg;

               }


}


public function getSubmitformInfo($userData)
{
   $this->_table = 'citizenship_tbl';
    $result = $this->db->select ('id,taxprovider_id,selfemployed_activemember,additional_information, status' )
        ->from ($this->_table )
         ->where ( 'id', $userData )
        ->where ( 'current_year', $this->year )
        ->get ()
        ->row();
        return $result;


}
//--------------------------------------------Rental Form----------------------------------------------------


public function addRentalincomeInfo($userData)
    {
      // print_r($userData);return;
      if(isset($userData['id']) && $userData['id'] != null)
      {
         $this->_table = 'rentalincome_tbl';
        //update

           $improvements =json_encode($userData["improvements"]);
 
         $rentalincomedata = array(
            'address' => $userData["address"],
             'actively_participate' => $userData["actively_participate"],
              'sell_rented_property' => $userData["sell_rented_property"],
             'description_property' => $userData["description_property"],
              'no_of_days_rented' => $userData["no_of_days_rented"],
             'no_of_days_personal' => $userData["no_of_days_personal"],
             'no_of_vacant' => $userData["no_of_vacant"],
              'gross_rents_received' => $userData["gross_rents_received"],
             'advertising' => $userData["advertising"],
              'cleaning' => $userData["cleaning"],
             'insurance' => $userData["insurance"],
              'legal_professional_fees' => $userData["legal_professional_fees"],
             'management_fee' => $userData["management_fee"],
              'mortgage_interest' => $userData["mortgage_interest"],
             'other_interest' => $userData["other_interest"],
              'repairs_maintenance' => $userData["repairs_maintenance"],
             'real_estate_taxes' => $userData["real_estate_taxes"],
              'utilities' => $userData["utilities"],
             'rental_miles' => $userData["rental_miles"],
              'others' => $userData["others"],
             'cost_land' => $userData["cost_land"],
              'cost_building' => $userData["cost_building"],
              'constructed_date' => $userData["constructed_date"],
             'rentedout_date' => $userData["rentedout_date"],
             'improvements'  => $improvements          

             );
            $this->db->where('id',$userData['id']);
            $this->db->update($this->_table, $rentalincomedata);
             $msg = "Updated Successfuly";
              return $msg;
      }
      else
      {
        //insert
          
          $taxpayer = $this->db->select ( 'id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->get () ->row ();
        
        //  Print_r($result_5);return;
         if($taxpayer)
         {
         $this->_table = 'rentalincome_tbl';

           $improvements =json_encode($userData["improvements"]);

             $rentalincomedata = array(
              'taxpayer_id' =>$taxpayer->id,
              'year' => $this->year,
              'address' => $userData["address"],
             'actively_participate' => $userData["actively_participate"],
              'sell_rented_property' => $userData["sell_rented_property"],
             'description_property' => $userData["description_property"],
              'no_of_days_rented' => $userData["no_of_days_rented"],
             'no_of_days_personal' => $userData["no_of_days_personal"],
             'no_of_vacant' => $userData["no_of_vacant"],
              'gross_rents_received' => $userData["gross_rents_received"],
             'advertising' => $userData["advertising"],
              'cleaning' => $userData["cleaning"],
             'insurance' => $userData["insurance"],
              'legal_professional_fees' => $userData["legal_professional_fees"],
             'management_fee' => $userData["management_fee"],
              'mortgage_interest' => $userData["mortgage_interest"],
             'other_interest' => $userData["other_interest"],
              'repairs_maintenance' => $userData["repairs_maintenance"],
             'real_estate_taxes' => $userData["real_estate_taxes"],
              'utilities' => $userData["utilities"],
             'rental_miles' => $userData["rental_miles"],
              'others' => $userData["others"],
             'cost_land' => $userData["cost_land"],
              'cost_building' => $userData["cost_building"],
              'constructed_date' => $userData["constructed_date"],
             'rentedout_date' => $userData["rentedout_date"] ,        
              'improvements'  => $improvements
             );
           
            $this->db->insert($this->_table, $rentalincomedata);
            return $this->db->insert_id();
        }
      

      }
           
     
}



    public function getRentalincomeInfo($taxpayer_id)
{
       
         if($taxpayer_id)
         {
         $this->_table = 'rentalincome_tbl';
         $result = $this->db->select ('id,taxpayer_id,year,address,actively_participate,sell_rented_property,description_property,no_of_days_rented,no_of_days_personal,no_of_vacant,gross_rents_received,advertising,cleaning,insurance,legal_professional_fees,management_fee,mortgage_interest,other_interest,repairs_maintenance,real_estate_taxes,utilities,rental_miles,others,cost_land,cost_building,constructed_date,rentedout_date, improvements, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id',$taxpayer_id)
         ->where ( 'year',$this->year)
        ->get ()
        ->result();
        

        return $result;
                }
                else
                {
                  $msg = "Error";
              return $msg;
                }


}

 public function getRentalincomestatus($taxpayer_id)
{
       
         if($taxpayer_id)
         {
         $this->_table = 'rentalincome_tbl';
         $result = $this->db->select ('status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id',$taxpayer_id)
         ->where ( 'year',$this->year)
        ->get ()
        ->row();
        

        return $result;
                }
                else
                {
                  $msg = "Error";
              return $msg;
                }


}

public function getRentaldeleteInfo($id)
{

$Rentalid = $this->db->select ( 'id' )->from ( 'rentalincome_tbl' )->where ( 'id',$id)->get () ->row ();
if($Rentalid)
{
  $this->db->where('id', $id);
        $id = $this->db->delete('rentalincome_tbl');
        return $id;
}
else
{
  return "Data Not found";
}
        
}

//-----------------------------------------------Business Income Module ------------------------------------------
public function addBusinessincomeInfo($userData)
    {

 $this->_table = 'businessincome_tbl';

    if(isset($userData['id']) && $userData['id'] != null)
      {
        //update

         $fixedAssets =json_encode($userData["fixedAssets"]);

        $businessincomedata = array(
            'proprietor_name' => $userData["proprietor_name"],
             'business_nature' => $userData["business_nature"],
              'business_code' => $userData["business_code"],
             'business_name' => $userData["business_name"],
              'business_address' => $userData["business_address"],
             'accounting_method' => $userData["accounting_method"],
             'materially_participate' => $userData["materially_participate"],
              'business_started' => $userData["business_started"],
             'forms1099_payments' => $userData["forms1099_payments"],
              'forms1099_required' => $userData["forms1099_required"],
             'state_purposes' => $userData["state_purposes"],
              'gross_receipts_sales' => $userData["gross_receipts_sales"],
             'advertising' => $userData["advertising"],
              'car_truck_expenses' => $userData["car_truck_expenses"],
             'contract_labor' => $userData["contract_labor"],
              'legal_professional_fees' => $userData["legal_professional_fees"],
             'office_expenses' => $userData["office_expenses"],
              'rent_lease' => $userData["rent_lease"],
             'repairs_maintenance' => $userData["repairs_maintenance"],
              'others' => $userData["others"],
             'meals_entertainment' => $userData["meals_entertainment"],
              'utilities' => $userData["utilities"],
              'wages' => $userData["wages"],
             'taxes_licenses' => $userData["taxes_licenses"],
             'business_travel_miles' => $userData["business_travel_miles"],
             'fixedAssets'=> $fixedAssets
                   

             );
            $this->db->where('id',$userData['id']);
            $this->db->update($this->_table, $businessincomedata);
             $msg = "Updated Successfuly";
              return $msg;

      }
      else
      {
        //insert
       $fixedAssets =json_encode($userData["fixedAssets"]);

          $taxpayer = $this->getTaxpayer_ID($userData)->id;
        if($taxpayer)
         {
        $businessincomedata = array(
              'taxpayer_id' =>$taxpayer,
              'year' => $this->year,
              'proprietor_name' => $userData["proprietor_name"],
             'business_nature' => $userData["business_nature"],
              'business_code' => $userData["business_code"],
             'business_name' => $userData["business_name"],
              'business_address' => $userData["business_address"],
             'accounting_method' => $userData["accounting_method"],
             'materially_participate' => $userData["materially_participate"],
              'business_started' => $userData["business_started"],
             'forms1099_payments' => $userData["forms1099_payments"],
              'forms1099_required' => $userData["forms1099_required"],
             'state_purposes' => $userData["state_purposes"],
              'gross_receipts_sales' => $userData["gross_receipts_sales"],
             'advertising' => $userData["advertising"],
              'car_truck_expenses' => $userData["car_truck_expenses"],
             'contract_labor' => $userData["contract_labor"],
              'legal_professional_fees' => $userData["legal_professional_fees"],
             'office_expenses' => $userData["office_expenses"],
              'rent_lease' => $userData["rent_lease"],
             'meals_entertainment' => $userData["meals_entertainment"],
              'others' => $userData["others"],
             'repairs_maintenance' => $userData["repairs_maintenance"],
              'taxes_licenses' => $userData["taxes_licenses"],
              'utilities' => $userData["utilities"],
             'wages' => $userData["wages"],
             'business_travel_miles' => $userData["business_travel_miles"],
             'fixedAssets' => $fixedAssets
                     

             );
           
            $this->db->insert($this->_table, $businessincomedata);
            return $this->db->insert_id();

      }
    }
         
}



public function getBusinessincomeInfo($taxpayer_id)
{
         if($taxpayer_id)
         {
   $this->_table = 'businessincome_tbl';
    $result = $this->db->select ('id,taxpayer_id,year,proprietor_name,business_nature,business_code,business_name,business_address,accounting_method,materially_participate,business_started,forms1099_payments,forms1099_required,state_purposes,gross_receipts_sales,advertising,car_truck_expenses,contract_labor,legal_professional_fees,office_expenses,rent_lease,meals_entertainment,repairs_maintenance,repairs_maintenance,taxes_licenses,utilities,wages,business_travel_miles,others,fixedAssets, status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id',$taxpayer_id)
        ->where ( 'year',$this->year)
       
        ->get ()
        ->result();
        return $result;
                }
                else
                {
                  $msg = "Error";
              return $msg;
                }


}



public function getBusinessincomestatus($taxpayer_id)
{
         if($taxpayer_id)
         {
   $this->_table = 'businessincome_tbl';
    $result = $this->db->select ('status' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id',$taxpayer_id)
        ->where ( 'year',$this->year)
       
        ->get ()
        ->row();
        return $result;
                }
                else
                {
                  $msg = "Error";
              return $msg;
                }


}

public function deleteBusinessInfo($id)
{

$Businessid = $this->db->select ( 'id' )->from ( 'businessincome_tbl' )->where ( 'id',$id)->get () ->row ();
if($Businessid)
{
  $this->db->where('id', $id);
        $id = $this->db->delete('businessincome_tbl');
        return $id;
}
else
{
  return "Data Not found";
}
        
}



//--------------------------------------Get all taxpayer Details-----------------------------------------



public function addTaxproviderDetails($userData) {
        $this->_table = "taxprovider_master";
        $result = $this->db->select ( 'id' )->from ( 'taxprovider_master' )->where ( 'user_id', $userData ["user_id"] )->get ()->row ();
        if($result)
        {

   $user = array (
        'name' => $userData ['name'],
        'contact' => $userData ['contact'],
        'price' => $userData ['price'],
        'address' => $userData ['address'],
        'description' => $userData ['description'] 
        
    );

            $this->db->where('id',$userData['id']);
                $this->db->update($this->_table, $user);
          $msg = "Updated Successfuly";
          return $msg; 
        }
        else
        {
    $user = array (
        'name' => $userData ['name'],
        'contact' => $userData ['contact'],
        'price' => $userData ['price'],
        'address' => $userData ['address'],
        'description' => $userData ['description'],
        'user_id'=>$userData ['user_id']
    );
    
    return $this->insert ( $user );
  }
}

 public function getTaxproviderGenInfo($userData) {
        $this->_table = 'taxprovider_master';
        return $this->db->select('id, name,contact,price,address,description,isActive,user_id')
                        ->from($this->_table)
                        ->where('isActive', 1)
                        ->where('user_id',$userData["user_id"])
                        ->get()
                        ->row();
    }


   public function getClientInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxprovider_master';

       $result = $this->db->select ('id' )
        ->from ( $this->_table )
        ->where ( 'user_id', $userData ["user_id"] )
        
        ->get ()
        ->row ();
        $taxprovider_id = $result->id;
        $this->_table = 'taxpayer_tbl';


        $result = $this->db->select ('id, f_name as name' )
        ->from ( 'taxpayer_tbl' )
        ->where ( 'taxprovider_id', $taxprovider_id )
       // ->where ( 'status', 2 )
       // ->where ( 'current_year', $year)
        ->get ()
        ->result ();
        return $result;
}
//--------------------------Update case status-----------------------------------------

   
public function UpdatereviewCasestatus($userData) {
        $this->_table = "casestatus_tbl";
        $result = $this->db->select ('id')->from ($this->_table)->where ( 'taxpayer_id', $userData ["id"] )->where ( 'year', $this->year )->where ( 'description', "Reivewed by Taxprovider" )->get ()->row ();
        if($result)
        {

   $user = array (
     'status' => "1" 
        
        
    );

            $this->db->where('id',$userData['id']);
                $this->db->update($this->_table, $user);
          $msg = "Updated Successfuly";
          return $msg; 
        }
        else
        {
    $casestatusdata = array(
                    'taxpayer_id'=>$userData ["id"],
                    'year'=> $this->year,
                    'description'=>"Reivewed by Taxprovider",
                   'status' => "1"       
                                );                    
    
           return $this->db->insert('casestatus_tbl', $casestatusdata);
  }
}



}