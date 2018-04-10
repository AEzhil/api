<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SC_Taxfilingmdl_bala extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        
        // Model intilizations
        $this->_table = 'user_master';
        //$this->validate = $this->config->item('sdf');
        $this->load->library('subquery');
         $this->currentyear = date('Y');
       $this->year = $this->currentyear -1 ;
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
		else if($document_type == "Income")
		{
			$this->_table = 'taxpayer_tbl';
			$path = '../assets/document/Income/';
			$filepath = '/assets/document/Income/';
			$fileTable = "taxpayerfile_tbl";
			$filename = "gi_";
			$upload_page = 'Income';
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



                                  

//-------------------------------------------------Get Income information--------------------------------------

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
        ->where ( 'current_year', $year)
        ->get ()
        ->result ();
        return $result;
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

public function getIncomeInfo($userData)
{
       $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

      $taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      
      $this->_table = 'income_usintereset';

        $USInteresetIncome = $this->db->select ('id, bank_name, received_interest, tax_held, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->get ()
        ->result ();
        
        $this->_table = 'income_foreignintereset';

        $ForeignInteresetIncome = $this->db->select ('id, bank_name, received_interest, tax_deducted, country_id, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->get ()
        ->result ();

        for($i=0;$i<count($ForeignInteresetIncome);$i++)
        {
               $ForeignIntereset = $ForeignInteresetIncome[$i];
                $ForeignIntereset->country = $this->getCountryName($ForeignIntereset->country_id);
        }
         $this->_table = 'income_usdividend';

        $USDividendIncome = $this->db->select ('id, payer_name,	ordinary_dividend, qualified_dividend, capital_gains, federal_incometax, foreign_taxpaid, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
        ->get ()
        ->result ();
        
         $this->_table = 'income_foreigndividend';

        $ForeignDividendIncome = $this->db->select ('id, company_name, received_dividend, tax_deducted, country_id, taxpayer_id' )
        ->from ( $this->_table )
        ->where ( 'taxpayer_id', $taxpayer_ID )
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
        return $response;
}

public function addIncomeInfo($userData, $files)
{
//  print_r($userData);  return;
    $year = date('Y');
       $year = $year -1;
       //$taxpayer_ID = $this->getTaxpayer_ID($userData)->id;
      $this->_table = 'taxpayer_tbl';

      $result = $this->db->select ( 'id, user_id' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->where ( 'current_year', $year )->get ()->row ();
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
//-------------------------------------------------Get Tax information--------------------------------------


 public function gettaxfileInfo($userData) {
        $year = date('Y');
       $year = $year -1 ;
      $this->_table = 'taxpayer_tbl';

        $result = $this->db->select ('id,user_id,f_name,l_name,gender,pan_no,  
                ssn_itin_no,dob,designation,father_name,marital_status,filing_status,permanent_home,email_official,email_personal,contact_india,contact_usa,address_india,address_usa,perferred_country,bankname_usa,acctype_usa,accno_usa,ifsc_usa,bankname_india,acctype_india,accno_india,ifsc_india,prev_employment,payroll_type,payroll_date' )->from ( 'taxpayer_tbl' )->where ( 'user_id', $userData ["user_id"] )->where ( 'current_year', $year )->get ()->row ();
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
		$result = $this->db->select ( 'id,user_id,CONCAT("'.$path.'", filepath) as filepath,filename' )->from ( $this->_table )->where ( 'file_id', $id )->where('upload_page', $upload_page)->get ()->result ();
		
		return $result;
	}



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



//------------------CITIZENSHIPSTATUS--------------------------------------------
public function updateCommonCitizenshipInfo($commonDetails, $citizenship_id)
{
     $this->_table = 'citizenship_tbl';
     //insert year base common details for citizenshi[]
     /* $common = array(
                'greenCard_holder' => $commonDetails['greenCard_holder'],
                'aadharcard' => $commonDetails['aadharcard'],
                'visa' => $commonDetails['visa'],
                'visaType' => $v['visaType'],              
                'issuedDate' => $commonDetails['issuedDate'],
                'passport_no' => $commonDetails['passport_no'],
                'expiry_date' => $commonDetails['expiry_date'],
               
        );*/
            $this->db->where('id',$citizenship_id);
          $this->db->update($this->_table, $commonDetails);
}

public function insertCitizenshipInfo_Year($userData, $year, $taxpayerid)
{
     $this->_table = 'citizenship_tbl';
     //insert year base common details for citizenshi[]
      $yearData = array(
                 'current_year'=> $year,
                 'taxpayer_id'=>$taxpayerid,
                'no_of_days' => $userData['no_of_days'],
                'entry_date' => $userData['entry_date'],
                'exit_date' => $userData['exit_date']
       );
      $insert_id = $this->db->insert($this->_table, $yearData);
      return $insert_id;
}

public function updateCitizenshipInfo_Year($citizenship_id, $userData)
{
     $this->_table = 'citizenship_tbl';

     $result = $this->db->select ('id')
        ->from ( $this->_table )
        ->where ( 'id', $citizenship_id)
        ->get ()->row ();
     if($result)
     {
      //update year base year details for citizenshi[]
       $yearData = array(
                'no_of_days' => $userData['no_of_days'],
                'entry_date' => $userData['entry_date'],
                'exit_date' => $userData['exit_date']
        );
          $this->db->where('id',$citizenship_id);
          $this->db->update($this->_table, $yearData);
          return true;
     }
     else{
      return "data not found";
     }
}


public function addCitizenshipInfo_Year($userData, $year, $taxpayerid)
{
     $this->_table = 'citizenship_tbl';

     $result = $this->db->select ('id')
        ->from ( $this->_table )
        ->where('current_year',$year)
        ->where('taxpayer_id',$taxpayerid)
        ->get ()->row ();
     if($result)
     {
      //update year base year details for citizenshi[]
       $yearData = array(
                'no_of_days' => $userData['no_of_days'],
                'entry_date' => $userData['entry_date'],
                'exit_date' => $userData['exit_date']
        );
          $this->db->where('current_year',$year);
          $this->db->where('taxpayer_id',$taxpayerid);
          $this->db->update($this->_table, $yearData);
          return true;
     }
     else{
       $this->_table = 'citizenship_tbl';
     //insert year base common details for citizenshi[]
      $yearData = array(
                 'current_year'=> $year,
                 'taxpayer_id'=>$taxpayerid,
                'no_of_days' => $userData['no_of_days'],
                'entry_date' => $userData['entry_date'],
                'exit_date' => $userData['exit_date']
       );
      $insert_id = $this->db->insert($this->_table, $yearData);
      return $insert_id;
     }
}

public function addStateEntry_CitizenshipInfo($stateinfo, $citizenship_id, $taxpayerid)
{
   if(count($stateinfo) > 0)
        {

          for($i=0; $i< count($stateinfo); $i++)
          {
            $this->_table ='taxayer_stateentry_tbl';
            if(isset($stateinfo[$i]['id']))
            {
               $stateEntry= array(
                     // 'taxpayer_id' => $userData['id'],
                      'citizenship_id' => $stateinfo[$i]['citizenship_id'],
                      'state_id' => $stateinfo[$i]['state']['id'],
                      'entry_date' => $stateinfo[$i]['entry_date'],
                      'exit_date' => $stateinfo[$i]['exit_date'],
              );
               $this->db->where('id',$stateinfo[$i]['id']);
                $this->db->update($this->_table, $stateEntry);
            }
            else
            {
               $stateEntry= array(
                       
                      'taxpayer_id' => $taxpayerid,
                      'citizenship_id' => $citizenship_id,
                      'state_id' => $stateinfo[$i]['state']['id'],
                      'entry_date' => $stateinfo[$i]['entry_date'],
                      'exit_date' => $stateinfo[$i]['exit_date'],
              );
              $this->db->insert($this->_table, $stateEntry);
            }
          }

        }
}
//----spouse details
public function updateSpouseCitizenshipInfo_Year($spouse_id, $userData)
{
     $this->_table = 'citizenship_depantant_tbl';

     $result = $this->db->select ('id')
        ->from ( $this->_table )
        ->where ( 'id', $spouse_id)
        ->get ()->row ();
     if($result)
     {
      //update year base year details for citizenshi[]
       $yearData = array(
                'no_of_days' => $userData['no_of_days'],
                'entry_date' => $userData['entry_date'],
                'exit_date' => $userData['exit_date']
        );
          $this->db->where('id',$spouse_id);
          $this->db->update($this->_table, $yearData);
          return true;
     }
     else{
      return "data not found";
     }
}


public function addSpouseCitizenshipInfo_Year($userData, $year, $taxpayerid, $spouse_id)
{
     $this->_table = 'citizenship_depantant_tbl';

     $result = $this->db->select ('id')
        ->from ( $this->_table )
       //->where('current_year',$year)
        // ->where('taxpayer_id',$taxpayerid)
       // ->where('depantant_id',$spouse_id)
        ->where('id',$userData['id'])
        ->get ()->row ();
     if($result)
     {
      //update year base year details for citizenshi[]
       $yearData = array(
                'no_of_days' => ($userData['no_of_days'] == ""? NULL : $userData['no_of_days']),
                'entry_date' => ($userData['entry_date'] == ""? NULL : $userData['entry_date']),
                'exit_date' => ($userData['exit_date'] == ""? NULL : $userData['exit_date'])
           );
          //$this->db->where('current_year',$year);
          //$this->db->where('taxpayer_id',$taxpayerid);
          $this->db->where('id',$userData['id']);
          $this->db->update($this->_table, $yearData);
          return true;
     }
     else{
       $this->_table = 'citizenship_depantant_tbl';
     //insert year base common details for citizenshi[]
      $yearData = array(
                 'current_year'=> $year,
                 'taxpayer_id'=>$taxpayerid,
                 'depantant_id'=> $spouse_id,

                  'no_of_days' => ($userData['no_of_days'] == ""? NULL : $userData['no_of_days']),
                'entry_date' => ($userData['entry_date'] == ""? NULL : $userData['entry_date']),
                'exit_date' => ($userData['exit_date'] == ""? NULL : $userData['exit_date'])
       );
      $insert_id = $this->db->insert($this->_table, $yearData);
      return $insert_id;
     }
}

public function updateCommonSpouseCitizenshipInfo($commonDetails, $spouse_id)
{
     $this->_table = 'citizenship_depantant_tbl';

            $this->db->where('id',$spouse_id);
          $this->db->update($this->_table, $commonDetails);
}


public function addSpouseStateEntry_CitizenshipInfo($stateinfo, $citizenship_id, $depentant_id)
{
   if(count($stateinfo) > 0)
        {

          for($i=0; $i< count($stateinfo); $i++)
          {
            $this->_table ='depantant_stateentry_tbl';
            if(isset($stateinfo[$i]['id']))
            {
               $stateEntry= array(
                     // 'taxpayer_id' => $userData['id'],
                      'citizenship_id' => $stateinfo[$i]['citizenship_id'],
                      'depantant_id' => $stateinfo[$i]['depantant_id'],
                      'state_id' => $stateinfo[$i]['state']['id'],
                      'entry_date' => $stateinfo[$i]['entry_date'],
                      'exit_date' => $stateinfo[$i]['exit_date'],
              );
               $this->db->where('id',$stateinfo[$i]['id']);
                $this->db->update($this->_table, $stateEntry);
            }
            else
            {
               $stateEntry= array(
                       
                      //'taxpayer_id' => $taxpayerid,
                      'citizenship_id' => $citizenship_id,
                       'depantant_id' => $depentant_id,
                      'state_id' => $stateinfo[$i]['state']['id'],
                      'entry_date' => $stateinfo[$i]['entry_date'],
                      'exit_date' => $stateinfo[$i]['exit_date'],
              );
              $this->db->insert($this->_table, $stateEntry);
            }
          }

        }
}

public function getDepentantByType($taxpayerid, $relation_type)
{
    return $this->db->select ('id')
    ->from ( 'depantant_tbl' )
    ->where ( 'taxpayer_id', $taxpayerid)
    ->where ( 'relation_type', $relation_type)
    ->get ()->row ();
}

public function addCitizenshipInfo($userData, $files)
{
      //print_r($userData); return;
      $taxpayerid = $this->getTaxpayer_ID($userData)->id;
          if(isset($userData['id']))
          {
              //update
              $citizenship_id = $userData['id'];

               $user_id = $userData['user_id'];

              if($citizenship_id)
              {
                $year_1 =  $userData['year_1'];
                  $updateCI_Y = $this->updateCitizenshipInfo_Year($citizenship_id, $year_1);
                  $common = array(
                    'greenCard_holder' => $userData['greenCard_holder'],
                    'aadharcard' => $userData['aadharcard'],
                    'visa' => $userData['visa'],
                    'visaType' => $userData['visaType'],              
                    'issuedDate' => $userData['issuedDate'],
                    'passport_no' => $userData['passport_no'],
                    'expiry_date' => $userData['expiry_date'],
                  );  
                  $this->updateCommonCitizenshipInfo($common, $citizenship_id);

                  $stateEntryData =  $userData['stateEntrydetails'];
                  $this->addStateEntry_CitizenshipInfo($stateEntryData, $citizenship_id, $taxpayerid);
              }

               $year_2 =  $userData['year_2'];
              $this->addCitizenshipInfo_Year($year_2, $this->year-1, $taxpayerid);

              $year_3 =  $userData['year_3'];
              $this->addCitizenshipInfo_Year($year_3, $this->year-2, $taxpayerid);


               $year_4 =  $userData['year_4'];
              $this->addCitizenshipInfo_Year($year_4, $this->year-3, $taxpayerid);


              //Spouse
               $spouse =  $userData['spouse'];

               $spouse_id = $spouse['id'];
             //$spouse_id = $this->getDepentantByType($taxpayerid, "Spouse")->id;
              if($spouse_id)
              {
                $s_year_1 =  $spouse['year_1'];
                $this->updateSpouseCitizenshipInfo_Year($spouse_id, $s_year_1);
                
                $common = array(
                    'aadharcard' => $spouse['aadharcard'],
                    'visa' => $spouse['visa'],
                    'visaType' => $spouse['visaType'],
                    'issuedDate' => $spouse['issuedDate'],
                    'passport_no' => $spouse['passport_no'],
                    'expiry_date' => $spouse['expiry_date'],
                  );  
                  $this->updateCommonSpouseCitizenshipInfo($common, $spouse_id);

                  $s_year_2 =  $spouse['year_2'];
                  $this->addSpouseCitizenshipInfo_Year($s_year_2, $this->year-1, $taxpayerid, $spouse_id);
                  
                  $s_year_3 =  $spouse['year_3'];
                  $this->addSpouseCitizenshipInfo_Year($s_year_3, $this->year-2, $taxpayerid, $spouse_id);
                  
                  $s_year_4 =  $spouse['year_4'];
                  $this->addSpouseCitizenshipInfo_Year($s_year_4, $this->year-3, $taxpayerid, $spouse_id);

                  $citizenship_depentant = $this->db->select ('id')
                                  ->from ( 'citizenship_depantant_tbl' )
                                  ->where ( 'depantant_id', $spouse_id)
                                  ->where ( 'current_year', $this->year)
                                  ->get ()->row ();
                  $stateEntryData =  $spouse['stateEntrydetails'];
                  $this->addSpouseStateEntry_CitizenshipInfo($stateEntryData, $citizenship_depentant->id, $spouse_id);
              }
              

                //dependents
               $dependents =  $userData['dependents'];
               for($i=0;$i<count($dependents); $i++)
               {
                  $depentant_id = $dependents[$i]['id'];
                  //print_r($dependents[$i]);
             //$spouse_id = $this->getDepentantByType($taxpayerid, "Spouse")->id;
              if($depentant_id)
              {
                $d_year_1 =  $dependents[$i]['year_1'];
                $this->updateSpouseCitizenshipInfo_Year($depentant_id, $d_year_1);
                
                $common = array(
                    'aadharcard' => $dependents[$i]['aadharcard'],
                    'visa' => $dependents[$i]['visa'],
                    'visaType' => $dependents[$i]['visaType'],
                    'issuedDate' => $dependents[$i]['issuedDate'],
                    'passport_no' => $dependents[$i]['passport_no'],
                    'expiry_date' => $dependents[$i]['expiry_date'],
                  );  
                  $this->updateCommonSpouseCitizenshipInfo($common, $depentant_id);

                  $d_year_2 =  $dependents[$i]['year_2'];
                  $this->addSpouseCitizenshipInfo_Year($d_year_2, $this->year-1, $taxpayerid, $depentant_id);

                  $d_year_3 = $dependents[$i]['year_3'];
                  $this->addSpouseCitizenshipInfo_Year($d_year_3, $this->year-2, $taxpayerid, $depentant_id);

                  $d_year_4 = $dependents[$i]['year_4'];
                  $this->addSpouseCitizenshipInfo_Year($d_year_4, $this->year-3, $taxpayerid, $depentant_id);

                  $citizenship_depentant = $this->db->select ('id')
                                  ->from ( 'citizenship_depantant_tbl' )
                                  ->where ( 'depantant_id', $depentant_id)
                                  ->where ( 'current_year', $this->year)
                                  ->get ()->row ();
                  $stateEntryData =  $dependents[$i]['stateEntrydetails'];
                  $this->addSpouseStateEntry_CitizenshipInfo($stateEntryData, $citizenship_depentant->id, $depentant_id);
              }
               }


          }
          else
          {
              //insert
               //insert year base year details for citizenship
              $year_1 =  $userData['year_1'];
              $citizenship_id = $this->insertCitizenshipInfo_Year($year_1, $this->year, $taxpayerid);
              if($citizenship_id)
              {
                  $common = array(
                    'greenCard_holder' => $userData['greenCard_holder'],
                    'aadharcard' => $userData['aadharcard'],
                    'visa' => $userData['visa'],
                    'visaType' => $userData['visaType'],              
                    'issuedDate' => $userData['issuedDate'],
                    'passport_no' => $userData['passport_no'],
                    'expiry_date' => $userData['expiry_date'],
                  );  
                  $this->updateCommonCitizenshipInfo($common, $citizenship_id);

                  $stateEntryData =  $userData['stateEntrydetails'];
                  $this->addStateEntry_CitizenshipInfo($stateEntryData, $citizenship_id, $taxpayerid);
              }

              $year_2 =  $userData['year_2'];
              $this->insertCitizenshipInfo_Year($year_2, $this->year-1, $taxpayerid);

              $year_3 =  $userData['year_3'];
              $this->insertCitizenshipInfo_Year($year_3, $this->year-2, $taxpayerid);


               $year_4 =  $userData['year_4'];
              $this->insertCitizenshipInfo_Year($year_4, $this->year-3, $taxpayerid);

              //spouse
               $spouse_id = $this->getDepentantByType($taxpayerid, "Spouse")->id;

          }
      // return $result;

     



}

}