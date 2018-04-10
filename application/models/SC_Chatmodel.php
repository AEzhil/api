<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Chatmodel extends MY_Model {
	public function __construct() {
		parent::__construct ();

		// Model intilizations
		$this->_table = 'group_master';
		$this->load->library ( 'subquery' );
		// $this->validate = $this->config->item('sdf');
	}
	

	public function getUserdetails($userID) {
		$this->_table = "user_master";
		$result = $this->db->select ( 'id, user_id, imagepath as image' )->from ( $this->_table )->where_not_in ( 'user_id', $userID )->order_by("id", "asc")->get ()->result();
		//$result = $this->db->last_query();
		return $result;
		
	}
	public function getUserdetailsByid($userID) {
		$this->_table = "user_master";
		$result = $this->db->select ( 'id, user_id, imagepath as image' )->from ( $this->_table )->where ( 'user_id', $userID )->order_by("id", "asc")->get ()->row();
		//$result = $this->db->last_query();
		return $result;
	
	}
	public function sentchatMessage($message, $userId) {
		$this->_table = "chat";
		// print_r($userId);return;
		$user1 = array (
				'chat_description' => $message ["message"],
				'user_id' => $message ["userId"],
				'timestamp' => date ( "Y-m-d H:i:s", strtotime ( "now" ) ),
				'active' => "Y",
				'duration' => '0',
				'sent_id' => $userId,
				'status' => "Unread"
		);

		$insertgroupid = $this->insert($user1 );
		// print_r($insertgroupid);return;
		if ($insertgroupid != null) {
			$this->_table = "chat";
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
	public function getChatMessage($chatId,$user_id) {
		//print_r($user_id);return;
		//$sql = "SELECT chat_id, chat_description,user_id,sent_id, date_format(timestamp,'%d-%m-%Y %r') as cdt, (select image from user_master where user_id=A.sent_id) AS image,  if((sent_id = '$user_id'), 'right', 'left') AS Position from chat AS A where (A.user_id='$user_id' and A.sent_id='$sent_id') OR (A.user_id='$sent_id' and A.sent_id='$user_id') order by A.chat_id desc  limit 20";
		
		$this->_table = "chat";
		$result = $this->db->SELECT ( 'chat_id, chat_description, date_format(timestamp,"%d-%m-%Y %r") as cdt,sent_id, if((sent_id = "'.$user_id.'"), "right", "left") AS Position' )
		->from ( $this->_table )
		->where ('(user_id="'.$user_id.'" and sent_id="'.$chatId.'") OR (user_id="'.$chatId.'" and sent_id="'.$user_id.'")')
		->order_by('chat_id', 'asc')
		->get ()
		->result ();
	 //$result = $this->db->last_query();

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
?>