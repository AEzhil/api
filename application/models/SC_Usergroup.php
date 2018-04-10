<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Usergroup extends MY_Model {
	public function __construct() {
		parent::__construct ();
		
		// Model intilizations
		$this->_table = 'group_master';
		$this->load->library ( 'subquery' );
		// $this->validate = $this->config->item('sdf');
	}
	public function creatGroup($userData, $user_Id) {
		$this->_table = 'group_master';
		$result = $this->db->select ( 'id' )->from ( $this->_table )->where ( 'group_name', $userData ['groupName'] )->get ()->row ();
		if (empty ( $result )) {
			
			$user = array (
					'group_name' => $userData ['groupName'],
					'created_by' => $user_Id,
					'imagepath' => 'uploadImage/group_default.png' 
			);
			
			$groupId = $this->insert ( $user );
			
			$user1 = array (
					'group_id' => $groupId,
					'user_id' => $user_Id,
					'add_by' => $user_Id,
					'status' => "Active" 
			);
			$this->_table = 'group_member';
			$insertgroupid = $this->insert ( $user1 );
			return $groupId;
		} else {
			return;
		}
	}
	public function addGroupMember($userData, $user_Id) {
		$this->_table = 'group_master';
		$this->db->select ( 'id' )->from ( $this->_table )->where ( 'id', $userData ["groupId"] );
		$result = $this->db->get ()->row ();
		if ($result != NULL) {
			$this->db->select ( 'id' )->from ( "group_member" )->where ( 'group_id', $userData ["groupId"] )->where ( 'user_id ', $userData ["selectedUser"] );
			$result1 = $this->db->get ()->row ();
			if ($result1 == NULL) {
				$user1 = array (
						'group_id' => $userData ["groupId"],
						'user_id' => $userData ["selectedUser"],
						'add_by' => $user_Id,
						'status' => "Active" 
				);
				$this->_table = 'group_member';
				$insertid = $this->insert ( $user1 );
				
				return $insertid;
			} 

			else {
				return;
			}
		} else {
			return;
		}
	}
	public function getUserGroup($user_Id) {
		$this->_table = 'group_master';
		$this->db->select ( 'id,group_name,imagepath as image' )->from ( $this->_table );
		$sub = $this->subquery->start_subquery ( 'where_in' );
		$sub->select ( 'group_id' )->from ( 'group_member' );
		$sub->where ( 'user_id', $user_Id );
		$this->subquery->end_subquery ( 'id', TRUE );
		$result = $this->db->get ()->result ();
		// $result = $this->db->last_query();
		return $result;
	}
	public function getGroupMember($group_Id) {
		$this->_table = 'group_member';
		$this->db->select ( 'id,user_id' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'imagepath as image' )->from ( 'user_master' );
		$sub->where ( $this->_table . '.user_id = user_master.user_id' );
		$this->subquery->end_subquery ( 'image' );
		$this->db->from ( $this->_table );
		$this->db->where ( $this->_table . '.group_id', $group_Id );
		$result = $this->db->get ()->result ();
		return $result;
	}
	public function getGroupById($group_Id) {
		$this->_table = 'group_master';
		// print_r($group_Id);return ;
		$result = $this->db->select ( 'id,group_name, imagepath as image' )->from ( $this->_table )->where ( 'id', $group_Id )->get ()->row ();
		return $result;
	}
	public function getUserDetails($userID) {
		$this->_table = "user_master";
		$result = $this->db->select ( 'id, user_id, imagepath as image' )->from ( $this->_table )->where ( 'user_id', $userID )->get ()->row ();
		return $result;
	}
	public function groupMessage($message, $userId) {
		$this->_table = "group_chat";
		// print_r($userId);return;
		$user1 = array (
				'chat_description' => $message ["groupMessage"],
				'group_id' => $message ["groupID"],
				'timestamp' => date ( "Y-m-d H:i:s", strtotime ( "now" ) ),
				'active' => "Y",
				'duration' => '0',
				'sent_id' => $userId,
				'status' => "Unread" 
		);
		
		$insertgroupid = $this->insert ( $user1 );
		// print_r($insertgroupid);return;
		if ($insertgroupid != null) {
			$this->_table = "group_chat";
			$result = $this->db->SELECT ( 'chat_id, chat_description, date_format(timestamp,"%d-%m-%Y %r") as cdt,sent_id' )
			->from ( $this->_table )
			->where ( 'status', "Unread" )
			->where ( 'chat_id', $insertgroupid )
			->get ()
			->row ();
			// $result = $this->db->last_query();
			return $result;
		} else {
			return;
		}
	}
	public function getGroupMessage($userdata) {
		$this->_table = "group_chat";
		// print_r($userdata);return;
		$result = $this->db->SELECT ( 'chat_id, chat_description, date_format(timestamp,"%d-%m-%Y %r") as cdt,sent_id' )
		->from ( $this->_table )
		->where ( 'group_id', $userdata )
		->get ()
		->result ();
		// $result = $this->db->last_query();
		
		if ($result != null) {
			return $result;
		} else {
			return;
		}
	}
	
	
	public function groupProfile($userdata) {
		
		$this->_table = 'group_master';
		$this->db->select ( 'id,group_name as name,imagepath as image' );
		$sub = $this->subquery->start_subquery ( 'select' );
		$sub->select ( 'count(group_id) ' )->from ( 'group_member' );
		$sub->where ( $this->_table . '.id = group_member.group_id' );
		$this->subquery->end_subquery ( 'memberCount' );
		$this->db->from ( $this->_table );
		$this->db->where ( $this->_table . '.id', $userdata );
		$result = $this->db->get ()->row ();
		return $result;
		
		
	}
}
	
	
