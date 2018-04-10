<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Dashboard extends MY_Model {
	public function __construct() {
		parent::__construct ();
		
		// Model intilizations
		$this->_table = 'postquestion';
		// $this->validate = $this->config->item('sdf');
		$this->load->library ( 'subquery' );
	}
/*	public function dashboardInfo() {
		$this->db->select ( 'post_Id, title, user_id, date_format(datetime, "%d-%b-%y ") AS postedDate' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'category' );
		$sub->where ( $this->_table . '.subject_id = category.id' );
		$this->subquery->end_subquery ( 'category' );
		
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'subcategory' );
		$sub->where ( $this->_table . '.sub_subject_id = subcategory.id' );
		$this->subquery->end_subquery ( 'subcategory' );
		
		$this->db->from ( $this->_table );
		$this->db->order_by ( 'post_Id', 'Desc' );
		return $this->db->get ()->result ();
	}*/
		public function dashboardInfo($data) {
		    
	   if($data['type'] == "")
	   {
		$this->db->select ( 'post_Id, title, user_id, date_format(datetime, "%d-%b-%y ") AS postedDate' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'category' );
		$sub->where ( $this->_table . '.subject_id = category.id' );
		$this->subquery->end_subquery ( 'category' );
		
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'subcategory' );
		$sub->where ( $this->_table . '.sub_subject_id = subcategory.id' );
		$this->subquery->end_subquery ( 'subcategory' );
		
		$this->db->from ( $this->_table );
		$this->db->order_by ( 'post_Id', 'Desc' );
		return $this->db->get ()->result ();
	   }
	   else if($data['type'] == "category")
	   {
	   $categoryid = 	$this->db->select ( 'id' )
	                ->from ("category")
	                ->where ('name',$data['type_val'])
	            	->get ()->row();
	   
	   	$this->db->select ( 'post_Id, title, user_id, date_format(datetime, "%d-%b-%y ") AS postedDate' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'category' );
		$sub->where ( $this->_table . '.subject_id = category.id' );
		$this->subquery->end_subquery ( 'category' );
		
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'subcategory' );
		$sub->where ( $this->_table . '.sub_subject_id = subcategory.id' );
		$this->subquery->end_subquery ( 'subcategory' );
		
		$this->db->from ( $this->_table );
		$this->db->where ( $this->_table . '.subject_id', $categoryid->id );
		$this->db->order_by ( 'post_Id', 'Desc' );
	
		return $this->db->get ()->result ();
	   }
	   else if($data['type'] == "subcategory")
	   {
	   $subcategoryid = 	$this->db->select ( 'id' )
	                ->from ("subcategory")
	                ->where ('name',$data['type_val'])
	            	->get ()->row();
	   
	   	$this->db->select ( 'post_Id, title, user_id, date_format(datetime, "%d-%b-%y ") AS postedDate' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'category' );
		$sub->where ( $this->_table . '.subject_id = category.id' );
		$this->subquery->end_subquery ( 'category' );
		
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'name' )->from ( 'subcategory' );
		$sub->where ( $this->_table . '.sub_subject_id = subcategory.id' );
		$this->subquery->end_subquery ( 'subcategory' );
		
		$this->db->from ( $this->_table );
		$this->db->where ( $this->_table . '.sub_subject_id', $subcategoryid->id );
		$this->db->order_by ( 'post_Id', 'Desc' );
	
		return $this->db->get ()->result ();
	   }
	   
	   
	}
	public function Viewquestion($questionID, $userID) {
		$this->_table = "postquestion";
		$result = $this->db->select ( 'post_id AS id, date_format(datetime, "%d/%m/%y %H:%i") AS postedDate, user_id, title , content AS description, subject_id, sub_subject_id' )->from ( $this->_table )->where ( 'post_id', $questionID )->get ()->row ();
		
		$this->_table = "question_notification";
		$notifyResult = $this->db->select ( 'post_id' )
				->from ( $this->_table )
				->where ( array ('post_id' => $questionID,	'user_id' => $userID) )
				->get ()
				->row ();
		
		// $query = $this->db->last_query();
		// echo ($query);
		
		if (count ( $notifyResult ) == 0) {
			$user = array (
					'post_id' => $questionID,
					'user_id' => $userID 
			);
			
			$this->insert ( $user );
		}
		return $result;
	}
	public function getComments_ByQuestionID($questionID, $parent_comment_id) {
		$this->_table = "postquestion_comment";
		return $this->db->select ( 'id, date_format(datetime, "%d/%m/%y %H:%i") AS postedDate, comment , user_Id, rating' )->from ( $this->_table )->where ( array (
				'post_id' => $questionID,
				'parent_comment_id' => $parent_comment_id 
		) )->get ()->result ();
	}
	public function getComments_ByCommentID($comment_id) {
		$this->_table = "postquestion_comment";
		return $this->db->select ( 'id, date_format(datetime, "%d/%m/%y %H:%i") AS postedDate, comment , user_Id, rating' )->from ( $this->_table )->where ( array (
				'id' => $comment_id 
		) )->get ()->row ();
	}
	public function getoverallRating($userid) {
		$this->_table = "postquestion_comment";
		
		$result = $this->db->query ( "(SELECT round(sum(rating) / count(id)) as userRating FROM `postquestion_comment` where user_Id = '" . $userid . "' and post_Id NOT IN(select post_Id from postquestion where user_Id ='" . $userid . "'))" )->ROW ();
		return $result;
	}
	public function getvolunteerHours($userid) {
		$this->_table = "postquestion_comment";
		return $this->db->select ( 'CONCAT(FLOOR(SUM(volunteerHours)/60),":",MOD(SUM(volunteerHours),60),"min(s)") AS volunteerHours' )->from ( $this->_table )->where ( array (
				'user_Id' => $userid 
		) )->get ()->row ();
	}
	
	public function getScheduleNotification($userID) {
		$this->_table = "tutor_appointment";
		
		$this->db->select ( 'id, title, user_id, date_format(schdule_date, "%d-%b-%y ") AS postedDate' );
		$this->db->from ( $this->_table );
		$this->db->where ( "tutor_userid", $userID);
		$this->db->where ( "status", "Pending" );
		$result = $this->db->get ()->result ();
		// $query = $this->db->last_query();
		// echo ($query);
		return $result;
	}
	
	public function getQuesNoti($userID) {
		$this->_table = "postquestion";
		// return $this->db->query ("( SELECT post_Id, title, datetime, subject_id, sub_subject_id FROM `postquestion` where sub_subject_id in (SELECT a.subject_id from user_subjects a, grades c where a.grade_id = c.grades and c.percentage_from >= 80 and a.user_id='".$userID."') and user_id !='".$userID."')")
		// ->result ();
		
		$this->db->select ( 'post_Id as id, title, user_id, date_format(datetime, "%d-%b-%y ") AS postedDate' );
			$sub = $this->subquery->start_subquery ( 'select' );
			$sub->select ( 'name' )->from ( 'category' );
			$sub->where ( $this->_table . '.subject_id = category.id' );
			$this->subquery->end_subquery ( 'category' );
		
			$sub = $this->subquery->start_subquery ( 'select' );
			$sub->select ( 'name' )->from ( 'subcategory' );
			$sub->where ( $this->_table . '.sub_subject_id = subcategory.id' );
			$this->subquery->end_subquery ( 'subCategory' );
		
		$this->db->from ( $this->_table )->where ( "user_Id !='" . $userID . "'" );

		$sub = $this->subquery->start_subquery ( 'where_in' );
		
		$sub->select ( 'a.subject_id' )->from ( 'user_subjects AS a' )->join ( 'grades AS b', 'a.grade_id = b.id' );
		$sub->where ( "b.percentage_from >= 80 and a.user_id='" . $userID . "'" );
		$this->subquery->end_subquery ( 'sub_subject_id', True );
		
		$sub1 = $this->subquery->start_subquery ( 'where_in' );
		
		$sub1->select ( 'post_id' )->from ( 'question_notification' );
		$sub1->where ( "user_id='" . $userID . "'" );
		$this->subquery->end_subquery ( 'post_Id', false );
		
		
		$result = $this->db->get ()->result ();
		// $query = $this->db->last_query();
		// echo ($query);
		return $result;
	}
	//---------------------------------------------Get Tutor Type --------------------------------------------------
	public function gettutoruser($userData) {
	$this->_table = "user_master";
		
		if ($userData ["tutorType"] != "0")
		{
		$value = $this->db->select ( 'id' )->from ( 'tutor_master' )->where ( "id ='" . $userData ["tutorType"] . "'" )->get ()->row ();
			$userData ["tutorType"] = $value->id; 
		
		}
		else 
		{
			$userData ["tutorType"] = "All";
		}
		
		
		if ($userData ["tutorType"] == "All" && $userData ["username"] == "" && $userData ["subjects"] == "" && $userData ["subsubjects"] == "") {
			
			return $this->db->query('select x.user_id, x.tutorType ,GROUP_CONCAT(x.name SEPARATOR ",\n") as category from (select a.user_id,c.name,f.tutorType from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id = c.id and a.user_id = d.user_id and d.tutor_id = f.id group by a.user_id ,b.category_id order by b.category_id) as x WHERE x.user_id != "'. $userData ["user_id"] .'" group by x.user_id')->result ();
		
			//return $this->db->query ( "SELECT id, user_id, (SELECT tutorType FROM tutor_master WHERE user_master.tutor_id = tutor_master.id) AS tutorType FROM user_master WHERE (tutor_id is not null) or (tutor_id is not null and user_id = '". $userData ["username"] ."' )" )->result ();
		}
		else if ($userData ["tutorType"] == "All" && $userData ["username"] != "" && $userData ["subjects"] == "" && $userData ["subsubjects"] == "") {
			
			return $this->db->query ( "(select x.user_id ,x.tutorType,GROUP_CONCAT(x.name ) as category from (select a.user_id,c.name,f.tutorType from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id = c.id and a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id = f.id group by a.user_id ,b.category_id order by b.category_id) as x WHERE x.user_id != '". $userData ["user_id"] ."' group by x.user_id)" )->result ();
		
		} else if ($userData ["tutorType"] == "All"  && $userData ["username"] == "" && $userData ["subjects"] != "" && $userData ["subsubjects"] == "") {
			
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id= c.id and c.id ='" . $userData ["subjects"] . "' and a.user_id = d.user_id and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		
		} else if ($userData ["tutorType"] == "All"  && $userData ["username"] != "" && $userData ["subjects"] != "" && $userData ["subsubjects"] == "") {
				
			return $this->db->query ( "(select a.user_id,f.tutorType ,c.name as category from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id= c.id and c.id ='" . $userData ["subjects"] . "' and  a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		} else if ($userData ["tutorType"] == "All"  && $userData ["username"] == "" && $userData ["subjects"] != "" && $userData ["subsubjects"] != "") {
				
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category,b.name as subcategory from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id ='" . $userData ["subsubjects"] . "' and a.subject_id = b.id and b.category_id= c.id and a.user_id = d.user_id and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		
		} else if ($userData ["tutorType"] == "All"  && $userData ["username"] != "" && $userData ["subjects"] != "" && $userData ["subsubjects"] != "") {
		
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category,b.name as subcategory from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id ='" . $userData ["subsubjects"] . "' and a.subject_id = b.id and b.category_id= c.id and a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		}
		
		else if ($userData ["tutorType"] != "" && $userData ["username"] == "" && $userData ["subjects"] == "" && $userData ["subsubjects"] == "") {
		
			return $this->db->query ( "(select x.user_id,x.tutorType,GROUP_CONCAT(x.name) as category from (select a.user_id,c.name,f.tutorType,d.tutor_id from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id = c.id and a.user_id = d.user_id and d.tutor_id = f.id group by a.user_id , b.category_id) as x where x.tutor_id = '" . $userData ["tutorType"] . "' and x.user_id != '". $userData ["user_id"] ."' group by x.user_id)" )->result ();
			
		} else if ($userData ["tutorType"] != "" && $userData ["username"] != "" && $userData ["subjects"] == "" && $userData ["subsubjects"] == "") {
		
			return $this->db->query ( "(select x.user_id,GROUP_CONCAT(x.name) as category,x.tutorType from (select a.user_id,c.name,f.tutorType,d.tutor_id from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id = c.id and a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id = f.id group by a.user_id , b.category_id) as x where x.tutor_id = '" . $userData ["tutorType"] . "' and x.user_id != '". $userData ["user_id"] ."' group by x.user_id)" )->result ();
			
		}
		else if ($userData ["tutorType"] != "All" && $userData ["username"] == "" && $userData ["subjects"] != "" && $userData ["subsubjects"] == "") {
		
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id= c.id and c.id ='" . $userData ["subjects"] . "' and a.user_id = d.user_id and d.tutor_id= '" . $userData ["tutorType"] . "' and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
				
		}
		
		else if ($userData ["tutorType"] != "All" && $userData ["username"] != "" && $userData ["subjects"] != "" && $userData ["subsubjects"] == "") {
		
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id = b.id and b.category_id= c.id and c.id ='" . $userData ["subjects"] . "' and  a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id= '" . $userData ["tutorType"] . "' and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		}
		else if ($userData ["tutorType"] != "All" && $userData ["username"] == "" && $userData ["subjects"] != "" && $userData ["subsubjects"] != "") {
		
			return $this->db->query ( "(select a.user_id,f.tutorType,c.name as category,b.name as subcategory from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id ='" . $userData ["subsubjects"] . "'  and a.subject_id = b.id and b.category_id= c.id and a.user_id = d.user_id and d.tutor_id = '" . $userData ["tutorType"] . "' and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		}
		else if ($userData ["tutorType"] != "All" && $userData ["username"] != "" && $userData ["subjects"] != "" && $userData ["subsubjects"] != "") {
		
			return $this->db->query ( "(select a.user_id,f.tutorType ,c.name as category,b.name as subcategory from user_subjects as a, subcategory as b, category as c,user_master as d,tutor_master as f where a.subject_id ='" . $userData ["subsubjects"] . "'  and a.subject_id = b.id and b.category_id= c.id and  a.user_id = '" . $userData ["name"] . "' and a.user_id = d.user_id and d.tutor_id = '" . $userData ["tutorType"] . "' and d.tutor_id = f.id and a.user_id != '". $userData ["user_id"] ."' group by a.user_id , b.category_id order by b.category_id)" )->result ();
		
		}
	}
	public function getUserimage($userID) {
		$this->_table = "user_master";
		$result = $this->db->select ( ' imagepath as image' )->from ( $this->_table )->where ( 'user_id', $userID )->get ()->row ();
		return $result;
	}
	public function addComment($userData) {
		$this->_table = "postquestion_comment";
		$user = array (
				'post_Id' => $userData ['questionID'],
				'comment' => $userData ['comment'],
				'user_Id' => $userData ['user_id'],
				'datetime' => date ( "Y-m-d H:i:s", strtotime ( "now" ) ),
				'parent_comment_id' => $userData ['parent_comment_id'] 
		);
		
		return $this->insert ( $user );
	}
	public function ratingComment($userData) {
		$this->_table = "postquestion_comment";
		$rating = $userData ['rating'];
		
		if ($rating == 1 || $rating == 2)
			$volunteerHours = 0;
		else if ($rating == 3)
			$volunteerHours = 5;
		else if ($rating == 4)
			$volunteerHours = 10;
		else if ($rating == 5)
			$volunteerHours = 15;
		
		$user = array (
				'rating' => $rating,
				'volunteerHours' => $volunteerHours 
		);
		
		if ($this->update ( $userData ['commentID'], $user )) {
			return $rating;
		} else {
			return FALSE;
		}
	}
	public function Postquestion($userData) {
		$this->_table = "postquestion";
		$user = array (
				'title' => $userData ['title'],
				'content' => $userData ['description'],
				'datetime' => date ( "Y-m-d H:i:s", strtotime ( "now" ) ),
				'subject_id' => $userData ['Category'],
				'sub_subject_id' => $userData ['SubCategory'],
				'user_id' => $userData ["user_id"],
				'sent_to' => $userData ['selectedUser'] ['user_id'],
				'usergroup' => $userData ['selectedUser'] ['User'] 
		);
		
		return $this->insert ( $user );
	}
	//-------------------------------------Resources-----------------------------------------
	
	public function resources() {
	    $this->_table = "school_link";
		$this->db->select ( 'id, name,link,description' );
		$this->db->from ( $this->_table );
		return $this->db->get ()->result ();
	}
	
	
	
}