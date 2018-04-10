<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SC_Employeemdl extends MY_Model {
	public function __construct() {
		parent::__construct ();
		
		// Model intilizations
		$this->_table = 'table1';
		// $this->validate = $this->config->item('sdf');
		//$this->load->library ( 'subquery' );
	}
	
	
	public function logintest($userData) {
		
		// print_r($userData);return;
	
		$result = $this->db->select ( 'id' )
		                   ->from ($this->_table)
		                   ->where ('userName', $userData ["userName"] )
		                   ->where ('password', $userData ["password"] )
		                   ->get ()->row ();
		// print_r($result);return;
		if ($result) {
			return true;
		}
		else
		{
			return false;
		}
	}



	public function eventget() {
		
		// print_r($userData);return;
	
		$result = $this->db->select ( "id,name,cur_date,start_time,end_time,if(cur_date < CURRENT_DATE,'post_id','pre') as new" )
		                   ->from ('table2')
		                  ->get ()->result ();

		                  return $result;
		// print_r($result);return;
		/*if ($result) {
			return true;
		}
		else
		{
			return false;
		}*/
	}
			
			
}
?>