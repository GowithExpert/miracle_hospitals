<?php namespace App\models;

/** 
 * Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
 * @Description: The code of the released Hospital software, does NOT lie under
 * GLP (General Public License) But it has proprietary copyrights. The purpose of the
 * Informing for public that, the Hospital web based mobile responsible application its associated
 * different roles are protected by the mentioned copyrights. *
 * @Version: Miracle Hospital - 1.0
 * @Author: Neoark Software
 * @Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
 * School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
 * @Email: sales@neoarksoftware.com | support@neoarksoftware.com
 * @website: www.neoarks.com
 * @Phone: +91-880-090-0164
 * Date: 21st August, 2023 
 */

use CodeIgniter\Model;
use \App\Models\CommonForAllModel; //Custom

class Login_model extends Model {
	
	public function __construct(){
    	parent::__construct();
    	$this->commonForAllModel = new CommonForAllModel(); //Called Custom model
  	}
  	
	// public function verify_existance($tablename, $uniq_fld, $uniq_fld_val) {  //Replaced with backend validation
	// 	$this->table = $tablename;
	// 	$this->uniq_fld = $uniq_fld;
	// 	$this->uniq_fld_val = $uniq_fld_val;
		
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$result = $builder->where($this->uniq_fld, $this->uniq_fld_val);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// } //function - Closed


	/* @param: 
	* @desc: Return false if record exists except the logged-in user 
	* @use: 
	* @author: Neoark Team
	* @date: May 11th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/
	// public function unique_except_logged_in($tablename, $uniq_fld, $uniq_fld_val, $uid) { // Moved to CommonForAllModel
	// 	$this->table = $tablename;
	// 	$this->uniq_fld = $uniq_fld;
	// 	$this->uniq_fld_val = $uniq_fld_val;
	// 	$this->uid = $uid;
		
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$result = $builder->where($this->uniq_fld, $this->uniq_fld_val);
	// 	$result = $builder->where('uid !=', $this->uid);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// } //function - Closed

	
	//public function verifyEmail($email, $password){
	public function verifyEmail($email, $password, $tablename) {
		$this->table = $tablename;
		if(password_verify($password, password_hash($password, PASSWORD_DEFAULT))) {
			$builder = $this->db->table($this->table);
			$builder->select('uid, id, status, username, password, profile_pic, email, level');
			$builder->where('email', $email);
			$result = $builder->get();
			if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
			else { return false; }
		}
		else { return false; }
	}

	// public function fetch_all_records($tablename) { //Replaced with CommonForAllModel 
	//same as commonModel function, except, pagination
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$this->paginate(10);
	// 	$result = $builder->get();
	// 	if ($this->db->affectedRows()> 0) { return $result->getResult();
	// 	}
	// 	else { return false; }	
	// }

	public function Insert_with_update($insrtTbl, $insrtData, $updtTbl, $updtData, $updtArgs) {
		$this->insrtDataArr = $insrtData; 	//Insertion data arr
		$this->updtDataArr 	= $updtData; 	//update data arr
		$this->updtArgsArr 	= $updtArgs; 	//Update condition array

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $insrtTbl; //'register_all_users' table
		//$builder = $this->db->table($this->table);
		$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $this->insrtDataArr);
		if($this->last_inst_id > 0) {
		//if ($this->db->affectedRows() == 1) { 
			$this->updtDataArr['ref_id']	= $this->last_inst_id;
			$this->table = $updtTbl; //'doctor' table
			if($this->update_rec_by_args($this->table, $this->updtArgsArr, $this->updtDataArr)) {
				$this->db->transCommit(); // Commit the transaction
				return true; 
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return false; 
			}
		}
		else { 
			$this->db->transRollback(); // Rollback the transaction on exception
			return false; 
		}
	} //Function -Closed

	public function verifyEmail_with_args($tablename, $email){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('id, uid,status,username,password,email,level');
		$builder->where('email', $email);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
	}

	public function getLoggedInPatientsData($id){
		$builder = $this->db->table('patients_login');
		$builder->where('uid', $id);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRow(); }
		else { return false; }
	}


	public function saveLoginInfo($data){
		$builder = $this->db->table('login_activity');
		$builder->insert($data);
		if ($this->db->affectedRows() == 1) { return $this->db->insertID(); }
		else { return false; }
	}

	public function google_user_exists($id){
		$builder = $this->db->table('social_login');
		$builder->where('oauth_id',$id);
		if ($builder->countAllResults() == 1) {
			return true;
		}else{
			return false;
		}
	}

	public function updateGoogleUser($data,$id){
		$builder = $this->db->table('social_login');
		$builder->where('oauth_id', $id);
		$builder->update($data);
		if ($this->db->affectedRows() == 1) {
			return true;
		}else{
			return false;
		}
	}

	public function createGoogleUser($data){
		$builder = $this->db->table('social_login');
		$res     = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

	public function updateLogoutTime($id){
		$builder = $this->db->table('login_activity');
		$builder->where('id',$id);
		$builder->update(['logout_time'=> date('Y-m-d h:i:s')]);
		if ($this->db->affectedRows() > 0) { return true; }
		else { return false; }
	}

	public function verifyAccountantEmail($tablename, $email){ 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where('email', $email);
		$result = $builder->get();
		//echo"<pre>";print_r($builder);die;
		//if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		if ($this->db->affectedRows() > 0) { return $result->getRowArray(); }
		else { return false; }
	}

	public function updatedAt($tablename, $uid){
		$builder = $this->db->table($tablename);
		$builder->where('uid', $uid);
		$builder->update(['updated_at'=> date('Y-m-d h:i:s')]);
		if ($this->db->affectedRows() == 1) {
			return true;
		}else{
			return false;
		}
	}

	public function verifyToken($tablename, $token){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('uid,username,updated_at');
		$builder->where('uid', $token);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) {
			return $result->getRowArray();
		}else{
			return false;
		}
	}


	public function update_password($tablename, $token,$pass){
		$builder = $this->db->table($tablename);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) {
			return true;
		}else{
			return false;
		}
	}

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: August 21, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		
		if ($this->db->affectedRows() > 0) { 
			return $result->getResult(); 
		}
		else{ return false; }

	} //Function - Closed
	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

} //Class - Closed