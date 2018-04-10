<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SC_User extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        
        // Model intilizations
        $this->_table = 'user_master';
        //$this->validate = $this->config->item('sdf');
    }
    
    public function authorize($userData) {
        $result = $this->db->select('id, user_id, f_name, l_name, CONCAT(f_name, " " ,l_name) AS name, email_id AS email, isFirstLogin AS returningUser, imagepath As image, user_type')
                        ->from($this->_table)
                        ->where('(user_id="' . $userData['username'] . '" OR email_id="' . $userData['username'] . '")')
                        ->where('auth_string', $this->_generatePassword($userData['password']))
                        ->get()
                        ->row();
        
		if(count($result) > 0) {
	        $user = array(
	          		'isFirstLogin' => 0
			);
	                        
			$this->update($result->id, $user);
        }                       
                        
       	return $result;
    }
    
    public function UserActivation($userData) {
		$result = $this->db->select ( 'id,user_id, status' )
		        ->from ( $this->_table )
		        ->where ( 'user_id="' . $userData ['userID'] . '"' )
		        ->get ()
		        ->row ();
		if (count ( $result ) > 0) {
			
			if ($result->status == "In Active") {
				
				$user = array (
						'status' => 'Active' 
				);
				
				if ($this->update ( $result->id, $user )) {
					
					return "status updated";
				}
			} 

			else {
				return "status not updated";
			}
		} else {
			return "user not found";
		}
    }
    
    public function doesUserExist($userData) {
        return $this->db->select('id, user_id AS userName, email_1 as emailAddress')
                        ->from($this->_table)
                        ->where('(user_id="' . $userData['userName'] . '" OR email_1="' . $userData['email'] . '")')
                        ->get()
                        ->row();
    }
    
    public function updatePassword($userData) {
    
    	$user = array(
    			'isForgotPassword' => 1
    	);
    
    	if($this->update($userData-> id, $user)) {
    		return $user;
    	} else {
    		return FALSE;
    	}
    }
    
    public function resetPassword($userdata) {
    	$result = $this->db->select('id,auth_string')
    	->from($this->_table)
    	->where('user_id="'.$userdata['userId'].'"')
    	->get()
    	->row();
    	
    	$user = array(
    			'auth_string' => $this->_generatePassword($userdata['newpassword']),
    			'isForgotPassword' => 0
    			    	);
    
    	if($this->update($result -> id, $user)) {
    		return $user;
    	} else {
    		return FALSE;
    	}
    }
    
    
    public function changePassword($userdata) {
    	//print_r($userdata) ;
    	$oldpassword = $this->_generatePassword($userdata['oldpassword']);
    	$result = $this->db->select('id,auth_string')
    	->from($this->_table)
    	->where('user_id="'.$userdata['userId'].'"')
    	->get()
    	->row();
   
    	if($oldpassword == $result -> auth_string) {
    		
    		$newpassword = $this->_generatePassword($userdata['newpassword']);
    		if($oldpassword != $newpassword)
    		{
	    		$user = array(
	    				'auth_string' => $newpassword ,
	    				'isForgotPassword' => 0
	    		);
	    		//print_r($user) ;return; 
	    		if($this->update( $result -> id, $user)) {
	    			return "Passwordchanged";
	    		} else {
	    			return FALSE;
	    		}
    		}
    		else {
    			return "SamePassword";
    		}
    		
    	} else {
    		return  "Worngcredentials";
    	}
    }
    public function forgotpasswordlinkcheck($userData) {
    	
    	//print_r($userData);return;
    	$result = $this->db->select('id,isForgotPassword')
    	->from($this->_table)
    	->where('user_id="'.$userData['username'].'"')
    	->get()
    	->row();
    	//$result = $this->db->last_query();
    	// print_r($result -> status);return;
    	if($result -> isForgotPassword == 1)
    	{
    		 
    		return "Not Expried";
    	}
    	 
    	else {
    		return "Expried";
    	}
    	 
    	 
    }


    public static function AlphaNumeric($length)
      {
          $chars = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
          $clen   = strlen( $chars )-1;
          $id  = '';

          for ($i = 0; $i < $length ; $i++) {
                  $id .= $chars[mt_rand(0,$clen)];
          }
          return ($id);
      }

       public static function Username($length)
      {
          $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          $clen   = strlen( $chars )-1;
          $id  = '';

          for ($i = 0; $i < $length ; $i++) {
                  $id .= $chars[mt_rand(0,$clen)];
          }
          return ($id);
      }
      public function getPlans()
      {
        $this->_table = "plan_master";
    	$result = $this->db->select('id,title, price, color, cls')
    	->from($this->_table)
    	->where('status', 1)
    	->get()
    	->result();
    	return $result;
      }

    public function register($userData,$subId) {
     //  print_r($userData); return;
        $clientDetails =$this->db->select('client_id')->from('subscription_tbl')->where('id',$subId)->get()->row();
         //print_r($clientDetails->client_id); return;
          $password = $this-> AlphaNumeric(8);
          $user_id = $this-> Username(6);
        
    			 $this->_table = 'user_master';
    	$user = array(
    			'user_id' => $user_id,
    			'f_name' => $userData['f_name'],
    			'l_name' => $userData['l_name'],
    			'email_id' => $userData['email_id'],
    			'auth_string' => $this->_generatePassword($password),
    			'mobile' => $userData['mobile'],
    			'client_id' => $clientDetails->client_id,
    			'imagepath' => "uploadImage/default.png",
    			'status' => "Active"
    	);
    
    	if( $this->insert($user))
      {
        $user['auth_string'] = $password;
        $user['price'] = $userData['price'];
        return $user;

      }
      else
      {
        return;
      }
    }

     public function subscription($userData) {
        $this->_table = 'subscription_tbl';
        // print_r($userData);return;
       $result = $this->db->select('id')->order_by('id','desc')->limit(1)->get('subscription_tbl')->row('id');
       // print_r($result);return;
        if($result)
        {
         $count =  $result +1;
        }
        else
        {
           $count = 1;
        }
        // print_r($count);return;
          $clientid = "Test00"."".$count;
          // print_r( $clientid);return;
      $user = array(
        
          'client_id' => $clientid,
          'plan_id' => $userData,
         // 'status' => "In Active"
         
      );
    
      return $this->insert($user);
    }
    
    private function _generatePassword($password) {
        //return md5($password . base64_decode($this->config->item('password_hash')));
    	return md5($password);
    }
}