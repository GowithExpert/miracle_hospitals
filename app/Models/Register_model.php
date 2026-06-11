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

class Register_model extends Model {


	public function Insertdata($tablename,$data){
		$builder = $this->db->table($tablename);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

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


	public function getLoggedInBloodBankData($uid){
		$builder = $this->db->table('register_all_users');
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	//Get Logged In User data
	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the two dimensional array format (eg. Array[0]['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args_arr($tablename, $args) { 
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC')
			->where($args)
			->paginate(10);
			//echo "<pre>";print_r($this->table);die;
	}

	/*@params: tbl `register_all_users` and data array
	* @desc: Doctor registration - Inser records into `register_all_users` & `doctor` table
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/
	public function add_doc_by_admin($tablename, $data) { 
		if($this->Insertdata($tablename, $data)) { // $tablename = `register_all_users`
			
			$this->doc_data = array(
				'uid' 				=> $data['uid'],
				'doctor_name' 		=> $data['username'],	//Need value?
				'department_name' 	=> $data['uid'],		//Need value?
				'doctor_phone' 		=> $data['mobile'],
				'doctor_address' 	=> $data['uid'], 		//Need value?
				'doctor_email' 		=> $data['email'],
				
				//'username' 			=> $data['username'],
				//'password' 			=> $data['password'],
				'gender' 			=> $data['gender'],
				'education' 		=> $data['uid'],		//need value

				'dr_specialization' 	=> $data['uid'],		//need value
				'doctor_other_info' => $data['uid'],		//need value
				'profile_pic' 	=> $data['photo'],			//need value
				'created_at' 	=> $data['created_date'],
				'status' 		=> $data['status']
			);
			if($this->Insertdata('doctor', $this->doc_data)) { return true; } // Insert records into `doctor` table
			else{ return false; }
		}
		else{ return false; }
	} //Function Closed	


	public function verifyUnid($id){
		$builder = $this->db->table('register_all_users');
		$builder->select('created_date,uid,status');
		$builder->where('uid', $id);
		$result =  $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow();} 
		else { return false; }
	}

	public function verify_patients_Unid($id){
		$builder = $this->db->table('patients_login');
		$builder->select('created_date,uid,status');
		$builder->where('uid', $id);
		$result =  $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	public function updateStatus($id){
		$builder  = $this->db->table('register_all_users');
		$builder->where('uid',$id);
		$builder->update(['status'=>'InActive']);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

 	
 	//Get Logged In User data
	//public function getLoggedInUserData($uid) {
	public function getLoggedInUserData($uid, $tablename) {
		$this->table = $tablename; //
		$builder = $this->db->table($this->table);
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}
	

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		//echo "<pre>";print_r($result->getResult());die;
		if (count($result->getResultArray()) > 0) {  return $result->getResult(); }
		else{ return false; }
	} //Function - Closed

	// public function fetch_all_records($tablename) { //same as commonModel function, except, pagination
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$this->paginate(10);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray())> 0) { return $result->getResult();
	// 	}
	// 	else { return false; }	
	// } //function - Closed

	public function delete_records($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->delete();
		if ($this->db->affectedRows() == 1) { return true; }
		else{ return false; }
	}

	/*@params: $tablename, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
	public function fetch_rec_by_args_by_like($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->like($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->get();
		
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	/*@params: $tablename, $order_format
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/	
	public function filter_rec_by_args($tablename, $order_format){
		$this->table = $tablename;
		extract($order_format);
		$builder = $this->db->table($this->table);
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	//Account Verification Query with Status Pagination
	public function fetch_rec_by_args_with_status($tablename, $args){
		$this->table = $tablename;

		 return $this->table($this->table)
			->select('*')
			->where($args)
			->orderBy('id', 'DESC')
			->paginate(10);
			//echo "<pre>";print_r($this);die;
	}

	//Account Verification Query with Status Pagination
	public function update_rec_by_args($tablename, $args, $data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo "<pre>"; print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
	}

	public function get_image_by_args($tablename, $args, $limit) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->limit($limit);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return false;
		}
	} 	
	public function updateLogoutTime($id){
		$builder = $this->db->table('login_activity');
		$builder->where('id',$id);
		$builder->update(['logout_time'=> date('Y-m-d h:i:s')]);
		if ($this->db->affectedRows() > 0) {
			return true;
		}else{
			return false;
		}
	}

	public function verifyAccountantEmail($tablename, $email){ 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where('email', $email);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRowArray(); }
		else { return false; }
	}


	public function saveLoginInfo($data){
		$builder = $this->db->table('login_activity');
		$builder->insert($data);
		if ($this->db->affectedRows() == 1) {
			return $this->db->insertID();
		}else{
			return false;
		}
	}


	public function update_patients_Status($id){
		$builder  = $this->db->table('patients_login');
		$builder->where('uid',$id);
		$builder->update(['status'=>'Active']);
		if ($this->db->affectedRows() == 1) {
			return true;
		}else{
			return false;
		}
	}

	public function verifyEmail_with_args($tablename, $email){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('id, uid,status,username,password,email,level');
		$builder->where('email', $email);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
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
	public function update_password($tablename, $token,$pass) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}



}

?>