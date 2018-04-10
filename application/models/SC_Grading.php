<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SC_Grading extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    


    public function getCountryList() {
        $this->_table = 'country_master';
        return $this->db->select('id, name')
                        ->from($this->_table)
                        ->where('isActive', 1)
                        ->get()
                        ->result();
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
    
    
    public function getStatesList($country_id){
    	
    	if($country_id == "All")
    	{
    		$where = array('isActive' => 1, "country_id IS NOT NULL");
    	}
    	else {
    		$where = array('isActive' => 1, 'country_id' => $country_id);
    	}
    	
    	$this->_table = 'state_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where($where)
    	->get()
    	->result();
    }
    
    public function getStateName($id){
    	 
    	$this->_table = 'state_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where('isActive', 1)
    	->where('id', $id)
    	 ->get()
    	->row();
    }

    



    
    public function getUserDetails($userID)
    {
    	$this->_table = "user_master";
    	$result = $this->db->select('id, user_id, CONCAT(f_name, " " ,l_name) AS name, email_1 AS email, mobile_num AS mobile, telephone as phone, imagepath as image')
    	->from($this->_table)
    	->where('user_id', $userID)
    	->get()
    	->row();
    	//$result->image = "data:image/jpeg;base64," . base64_encode ( $result->image );

    	return $result;
    }
    
    public function getimage($userID){
    	$query2 = $this->db->query("SELECT image FROM user_master WHERE user_id = '$userID'")->row();
    	return "data:image/jpeg;base64," . base64_encode ( $query2->image );
    }
    

    public function getCategory() {
        $this->_table = 'category_master';
        return $this->db->select('id, name')
                        ->from($this->_table)
                        ->where('isActive', 1)
                        ->get()
                        ->result();
    }
    
    public function getSubCategory($categoryId) {
    	$this->_table = 'subcategory_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where(array('isActive' => 1, 'category_id' => $categoryId))
    	->get()
    	->result();
    }
    

    public function getSubjectById($subjectId) {
        $this->_table = 'category';
        return $this->db->select('id, name')
                        ->from($this->_table)
                        ->where('id', $subjectId)
                        ->get()
                        ->row();
    }
    
    public function getSubCategoryById($subjectId) {
    	$this->_table = 'subcategory_master';
    	return $this->db->select('id, name')
    	->from($this->_table)
    	->where('id', $subjectId)
    	->get()
    	->row();
    }
    



}