<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SC_Profile extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        
        // Model intilizations
        $this->_table = 'user_master';
        //$this->validate = $this->config->item('sdf');
        $this->load->library('subquery');
    }
    
    public function getUserDetails($userID)
    {
    	$this->_table = "user_master";
    	$result = $this->db->select('id, user_id, CONCAT(f_name, " " ,l_name) AS name, imagepath as image')
    	->from($this->_table) 
    	->where('user_id', $userID)
    	->get()
    	->row();

/*    	if($result->image != null)
    	{
    	$result->image = "data:image/jpeg;base64," . base64_encode ( $result->image );
    	}
    	else
    	{
    		$result->image = "dist/img/user.png";
    	}
    	*/
    	return $result;
    }




    public function getProfile($userID) {
    	$this->_table = "user_master";
//    	$result = $this->db->select('id, user_id, CONCAT(f_name, " " ,l_name) AS name, f_name, m_name, l_name, email_1 AS email, email_2 as altEmail, mobile_num AS mobile, telephone,  screen_name, school_id,tutor_type')
//    	->from($this->_table)
//    	->where('user_id', $userID)
//    	->get()
//    	->row();

$subject =  $_SERVER['SCRIPT_NAME'];
$subArr = explode("/", $subject);
//array_pop($subArr);
$subArr = array_slice($subArr, 0, -2);
  $path = implode("/",$subArr);
          $path = "http://".$_SERVER['SERVER_ADDR'].$path."/";
    		$this->db->select ( 'id, user_id, CONCAT(f_name, " " ,l_name) AS name, f_name, m_name, l_name, email_id ,age, dob, gender, address1, address2, nationality, state_id, country_id, mobile, telephone,  surname, number, CONCAT("'.$path.'", imagepath) as imagepath');
		$this->db->from ( $this->_table );
		$this->db->where ( $this->_table . '.user_id', $userID );
		$result = $this->db->get ()->row ();
		return $result;

    	//$result->image = "data:image/jpeg;base64," . base64_encode ( $result->image );

    	return $result;

    }
    
    public function getSchoolDetails($school_id)
    {
    	//$query1 = "SELECT *,(select City_name FROM cities where id=city_id) As city, (select county_name FROM county where id=county_id) As county FROM school1 WHERE school_id='$school_id'";
    	$this->_table = "school";
    	$result = $this->db->select('country_id, state_id, district_id, id as school_id, city_id, county_id')
    	->from($this->_table)
    	->where('id', $school_id)
    	->get()
    	->row();
    	 
    	return $result;
    }
    
    public function getEducationDetails($user_id)
    {
    	$this->_table = "user_subjects";
    	$result = $this->db->select('subject_id, grade_id')
    	->from($this->_table)
    	->where('user_id', $user_id)
    	->get()
    	->result();
    	
    	$total_len = count($result);
    	if($total_len<5)
    	{
	    	for($i=$total_len;$i<5;$i++)
	    	{
	    		$result[$i] = array('subject_id'=>null, 'grade_id'=>null);
	    	}
    	}
    	
    	return $result;
    }
    
     public function updateUserInfo($userdata)
    {
    	$this->_table = 'user_master';
    	$result = $this->db->select ( 'f_name' )->from ('user_master' )->where ( 'user_id', $userdata ['user_id'] )->get ()->row ();
    	
   if($result)
   {
    	$user = array(
    
    		'f_name' => $userdata['f_name'],
    		'm_name' => $userdata['m_name'],
    		'l_name' => $userdata['l_name'],
                'surname' => $userdata['surname'],
                'number' => $userdata['number'],
                'email_id' => $userdata['email_id'],
                'age' => $userdata['age'],
                'dob' => $userdata['dob'],
                'gender' => $userdata['gender'],
                'address1' => $userdata['address1'],
                'address2' => $userdata['address2'],
                'state_id' => $userdata['state_id'],
                'country_id' => $userdata['country_id'],
                'nationality' => $userdata['nationality'],
               	'telephone' => $userdata['telephone'],
    		'mobile' => $userdata['mobile']
    			    
    	);
//return  $userdata;
    	if($this->update($userdata["id"], $user)) {
    		return $userdata;
    	} else {
    		return FALSE;
    	}
    }
    else
    {
        $userdata = "No Data Found";
        return $userdata;
    }
    
    }
    
    public function updateSchoolInfo($userdata)
    {
    	$this->_table = 'user_master';
    
    	$user = array(
    			'school_id' => $userdata['school_id'],
    	);
    	//return  $userdata;
    	if($this->update($userdata["id"], $user)) {
			return $user;
    	} else {
    		return FALSE;
    	}
    
    }
    
    
    public function saveEduInfo($user_id,$data)
    {
    	
    	//print_r($data);return;
    	$this->_table = 'user_subjects';
    
    	//$subject_id = $data;
    	foreach ($data as $key => $value) {
    			
    		$subject_id = $value["id"];
    		$grade_id= $value["selectedGrade"];
    		$result = $this->db->select('grade_id')
    		->from($this->_table)
    		->where('(user_id="' . $user_id . '")')
    		->where('(subject_id="' . $subject_id . '")')
    		//->where('(grade_id="' . $grade_id . '")')
    		->get()
    		->row();
    		//$query = $this->db->last_query();
    		//echo ($query);

    		if (count($result)) {
    			$update = $this->db->set('grade_id', $grade_id)
    			->where('(user_id="' . $user_id . '")')
    			->where('(subject_id="' . $subject_id . '")')
    			->update($this->_table);
    		}
    		// INSERT new record in database with data
    		else {
    			if($grade_id != "" || $grade_id != null)
    			{
    				$data = array(
    						'user_id' => $user_id,
    						'subject_id' => $subject_id,
    						'grade_id' => $grade_id
    				
    				);
    				$insert = $this->db->insert($this->_table, $data);
    			}
    		}
    	}
    }
    
    public function getcategory() {
    	$this->_table = 'category_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where('isActive', 1)
    	->get()
    	->result();
    }
    
    public function getgradeById($subject_Id,$userId) {
    	$this->_table = 'subcategory';
    	return $this->db->select('id, name, (Select grade_id from user_subjects where subject_id = subcategory.id and user_id="'.$userId.'") as selectedGrade')
    	->from($this->_table)
    	->where('category_id', $subject_Id)
    	->get()
    	->result();
    }
    
    public function getGrades() {
    	$this->_table = 'grades';
    	return $this->db->select('id, grade as name')
    	->from($this->_table)
    	->where('isActive', 1)
    	->get()
    	->result();
    }
    
    public function saveGrades($user_id,$data)
    {
    	$this->_table = 'user_subjects';
    
    	$subject_id = $data;
    	foreach ($data as $key => $value) {
    			
    		$subject_id = $value["id"];
    		$grade_id= $value["selectedGrade"];
    		$result = $this->db->select('grade_id')
    		->from($this->_table)
    		->where('(user_id="' . $user_id . '")')
    		->where('(subject_id="' . $subject_id . '")')
    		//->where('(grade_id="' . $grade_id . '")')
    		->get()
    		->row();
    
    		if (count($result)) {
    			$update = $this->db->set('grade_id', $grade_id)
    			->where('(user_id="' . $user_id . '")')
    			->where('(subject_id="' . $subject_id . '")')
    			->update($this->_table);
    		}
    
    		// INSERT new record in database with data
    		else {
    			$data = array(
    					'user_id' => $user_id,
    					'subject_id' => $subject_id,
    					'grade_id' => $grade_id
    						
    			);
    			$insert = $this->db->insert($this->_table, $data);
    		}
    	}
    }
    
    
    
}