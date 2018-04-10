<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'error_prefix' => '',
    'error_suffix' => '',
    'loginForm' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required'
        )
    ),
    'passwordForm' => array(
       array(
            'field' => 'userName',
            'label' => 'Username',
            'rules' => 'trim'
        ),
    		array(
    				'field' => 'email',
    				'label' => 'Email address',
    				'rules' => 'trim'
    		)
    ),
    'emailUnique' => array(
        array(
            'field' => 'emailAddress',
            'label' => 'Email address',
            'rules' => 'trim|required|is_unique[user.emailAddress]'
        )
    ),
    'usernameUnique' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|is_unique[user.username]'
        )
    ),
    
		
		'registerForm' => array(
				array(
						'field' => 'f_name',
						'label' => 'First Name',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'l_name',
						'label' => 'Last name',
						'rules' => 'trim|required'
				),
		
				
				array(
						'field' => 'email_id',
						'label' => 'Email address',
						'rules' => 'trim|required'
				),
						
				array(
						'field' => 'mobile',
						'label' => 'Telephone Number',
						'rules' => 'trim'
				)
				
		
		),
		
		
		'updateUserInfo' => array(
				array(
						'field' => 'f_name',
						'label' => 'First Name',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'm_name',
						'label' => 'Middle Name',
						'rules' => 'trim'
				),
				array(
						'field' => 'l_name',
						'label' => 'Last Name',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'surname',
						'label' => 'Surname',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'number',
						'label' => 'Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'email_id',
						'label' => 'Email Address',
						'rules' => 'trim'
				),

				array(
						'field' => 'age',
						'label' => 'Age',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'dob',
						'label' => 'Date Of Birth',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'gender',
						'label' => 'Gender',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'address1',
						'label' => 'Address1',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'address2',
						'label' => 'Address2',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'state_id',
						'label' => 'State_id',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'country_id',
						'label' => 'Country_id',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'nationality',
						'label' => 'Nationality',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'mobile',
						'label' => 'Mobile',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'telephone',
						'label' => 'Telephone',
						'rules' => 'trim'
				)
		),

		'addWorkpermit' => array(
				array(
						'field' => 'number',
						'label' => 'Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issue_date',
						'label' => 'Issue_Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'validity_FD',
						'label' => 'Validity From Date',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'validity_TD',
						'label' => 'Validity To Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'place_issue',
						'label' => 'Place of issue',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'visa_type',
						'label' => 'Visa Type',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'entries',
						'label' => 'Entries',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issuing_city',
						'label' => 'Issuing City',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'visa_number',
						'label' => 'Visa Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'employer',
						'label' => 'Employer',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'receipt_number',
						'label' => 'Receipt Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'case_type',
						'label' => 'Case Type',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'receipt_date',
						'label' => 'Receipt Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'notice_date',
						'label' => 'Notice Date',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'petitioner',
						'label' => 'Petitioner',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'beneficiary',
						'label' => 'Beneficiary',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'notice_type',
						'label' => 'Notice Type',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'description',
						'label' => 'Description',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'workpermit_type',
						'label' => 'Workpermit Type',
						'rules' => 'trim|required'
				)
		),

      
		'updateWorkpermit' => array(
				array(
						'field' => 'number',
						'label' => 'Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issue_date',
						'label' => 'Issue_Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'validity_FD',
						'label' => 'Validity From Date',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'validity_TD',
						'label' => 'Validity To Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'place_issue',
						'label' => 'Place of issue',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'visa_type',
						'label' => 'Visa Type',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'entries',
						'label' => 'Entries',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issuing_city',
						'label' => 'Issuing City',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'visa_number',
						'label' => 'Control Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'employer',
						'label' => 'Employer',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'receipt_number',
						'label' => 'Receipt Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'case_type',
						'label' => 'Case Type',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'receipt_date',
						'label' => 'Receipt Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'notice_date',
						'label' => 'Notice Date',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'petitioner',
						'label' => 'Petitioner',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'beneficiary',
						'label' => 'Beneficiary',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'notice_type',
						'label' => 'Notice Type',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'description',
						'label' => 'Description',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'workpermit_type',
						'label' => 'Workpermit Type',
						'rules' => 'trim|required'
				)
		),



		'resetpassword' => array(
				array(
						'field' => 'userId',
						'label' => 'Password',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'newpassword',
						'label' => 'newPassword',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'confirmpassword',
						'label' => 'confirmPassword',
						'rules' => 'trim|required'
				)
				),
		'addPassportdetails' => array(
				array(
						'field' => 'number',
						'label' => 'Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issue_date',
						'label' => 'Issue_Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'validity_FD',
						'label' => 'Validity From Date',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'validity_TD',
						'label' => 'Validity To Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'place_issue',
						'label' => 'Place of issue',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'country_issue',
						'label' => 'Issuing Country',
						'rules' => 'trim|required'
				),
				/*array(
						'field' => 'barcode',
						'label' => 'Barcode',
						'rules' => 'trim|required'
				),*/
				array(
						'field' => 'other',
						'label' => 'Others',
						'rules' => 'trim'
				)
			),
		'updatePassportdetails' => array(
				array(
						'field' => 'number',
						'label' => 'Number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issue_date',
						'label' => 'Issue_Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'validity_FD',
						'label' => 'Validity From Date',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'validity_TD',
						'label' => 'Validity To Date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'place_issue',
						'label' => 'Place of issue',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'country_issue',
						'label' => 'Issuing Country',
						'rules' => 'trim|required'
				),
			/*	array(
						'field' => 'barcode',
						'label' => 'Barcode',
						'rules' => 'trim|required'
				), */
				array(
						'field' => 'other',
						'label' => 'Others',
						'rules' => 'trim'
				)
			),
		'addI94Details' => array(
				array(
						'field' => 'record_number',
						'label' => 'Record number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'admit_date',
						'label' => 'Admit date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'family_name',
						'label' => 'Family name',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'f_name',
						'label' => 'First name',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'dob',
						'label' => 'Date of Birth',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'passport_number',
						'label' => 'Passport number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issued_country',
						'label' => 'Issued country',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'entry_date',
						'label' => 'Entry date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'class_admission',
						'label' => 'Class Admission',
						'rules' => 'trim|required'
				)
			),
		'updateI94Details' => array(
				array(
						'field' => 'record_number',
						'label' => 'Record number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'admit_date',
						'label' => 'Admit date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'family_name',
						'label' => 'Family name',
						'rules' => 'trim|required'
				),

				array(
						'field' => 'f_name',
						'label' => 'First name',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'dob',
						'label' => 'Date of Birth',
						'rules' => 'trim|required'
				),
				
				array(
						'field' => 'passport_number',
						'label' => 'Passport number',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'issued_country',
						'label' => 'Issued country',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'entry_date',
						'label' => 'Entry date',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'class_admission',
						'label' => 'Class Admission',
						'rules' => 'trim|required'
				)
			),
		'addtaxFile' => array(
				array(
						'field' => 'f_name',
						'label' => 'First Name',
						'rules' => 'trim'
				),
				array(
						'field' => 'l_name',
						'label' => 'Last Name',
						'rules' => 'trim'
				),
				array(
						'field' => 'gender',
						'label' => 'Gender',
						'rules' => 'trim'
				),

				array(
						'field' => 'pan_no',
						'label' => 'Pan No',
						'rules' => 'trim'
				),
				array(
						'field' => 'ssn_itin_no',
						'label' => 'ssn_itin_no',
						'rules' => 'trim'
				),
				array(
						'field' => 'dob',
						'label' => 'Date of birth',
						'rules' => 'trim'
				),

				array(
						'field' => 'designation',
						'label' => 'Designation',
						'rules' => 'trim'
				),
				array(
						'field' => 'father_name',
						'label' => 'Father Name',
						'rules' => 'trim'
				),
				array(
						'field' => 'marital_status',
						'label' => 'Marital Status',
						'rules' => 'trim'
				),
				array(
						'field' => 'filing_status',
						'label' => 'Filing status',
						'rules' => 'trim'
				),
				array(
						'field' => 'permanent_home',
						'label' => 'Permanent Home',
						'rules' => 'trim'
				),
				array(
						'field' => 'email_official',
						'label' => 'Email official',
						'rules' => 'trim'
				),
				array(
						'field' => 'email_personal',
						'label' => 'Email personal',
						'rules' => 'trim'
				),
				array(
						'field' => 'contact_india',
						'label' => 'contact india',
						'rules' => 'trim'
				),
				
				array(
						'field' => 'contact_usa',
						'label' => 'Contact usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'address_india',
						'label' => 'Address india',
						'rules' => 'trim'
				),
				array(
						'field' => 'address_usa',
						'label' => 'Address usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'perferred_country',
						'label' => 'Perferred country',
						'rules' => 'trim'
				),
				
				array(
						'field' => 'bankname_usa',
						'label' => 'Bankname Usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'acctype_usa',
						'label' => 'Acctype usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'accno_usa',
						'label' => 'Accno usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'ifsc_usa',
						'label' => 'Ifsc usa',
						'rules' => 'trim'
				),
				array(
						'field' => 'bankname_india',
						'label' => 'Bankname india',
						'rules' => 'trim'
				),
				
				array(
						'field' => 'acctype_india',
						'label' => 'Acctype india',
						'rules' => 'trim'
				),
				array(
						'field' => 'accno_india',
						'label' => 'Accno india',
						'rules' => 'trim'
				),
				array(
						'field' => 'ifsc_india',
						'label' => 'Ifsc india',
						'rules' => 'trim'
				),
				array(
						'field' => 'prev_employment',
						'label' => 'prev_employment',
						'rules' => 'trim'
				),
				array(
						'field' => 'payroll_type',
						'label' => 'Payroll type',
						'rules' => 'trim'
				),
				array(
						'field' => 'payroll_date',
						'label' => 'Payroll date',
						'rules' => 'trim'
				),
				array(
						'field' => 'taxprovider_id',
						'label' => 'taxprovider_id',
						'rules' => 'trim'
				)
		),
		
		'addsourcelistForm' => array(
				array(
						'field' => 'consent_auth_letter_signed',
						'label' => 'consent_auth_letter_signed',
						'rules' => 'trim'
				),
				
				array(
						'field' => 'copy_passport_visa_people_reported',
						'label' => 'copy_passport_visa_people_reported',
						'rules' => 'trim'
				),
		
				
				array(
						'field' => 'travel_history',
						'label' => 'travel_history',
						'rules' => 'trim'
				),
						
				array(
						'field' => 'lastyear_federal_state_return',
						'label' => 'lastyear_federal_state_return',
						'rules' => 'trim'
				),
				array(
						'field' => 'india_return',
						'label' => 'india_return',
						'rules' => 'trim'
				),
				array(
						'field' => 'w2_all_income_docs_stat_1099',
						'label' => 'w2_all_income_docs_stat_1099',
						'rules' => 'trim'
				),
						
				array(
						'field' => 'hsa_distribute_doc_1099_sa',
						'label' => 'hsa_distribute_doc_1099_sa',
						'rules' => 'trim'
				),
				array(
						'field' => 'receive_rent_from_real_estate_other',
						'label' => 'receive_rent_from_real_estate_other',
						'rules' => 'trim'
				),
				array(
						'field' => 'foreign_income_earned',
						'label' => 'foreign_income_earned',
						'rules' => 'trim'
				),
				array(
						'field' => 'proof_usa_property_sold',
						'label' => 'proof_usa_property_sold',
						'rules' => 'trim'
				),
						
				array(
						'field' => 'income_spouse',
						'label' => 'income_spouse',
						'rules' => 'trim'
				),
				array(
						'field' => 'rent_paid_stay_usa',
						'label' => 'rent_paid_stay_usa',
						'rules' => 'trim'
				)
				
				
		
		),

		'submitformFile' => array(
       array(
            'field' => 'selfemployed_activemember',
            'label' => 'Selfemployed Activemember',
            'rules' => 'trim|required'
        ),
    		array(
    				'field' => 'additional_information',
    				'label' => 'Additional Information',
    				'rules' => 'trim|required'
    		)
    )



		
);

