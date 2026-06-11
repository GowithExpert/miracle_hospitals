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

    class AutoModel extends Model {
		protected $table      = 'patients';
	    protected $primaryKey = 'id';

	    //protected $returnType     = 'array';
	    //protected $useSoftDeletes = true;
	    protected $allowedFields = ['patient_name','serial', 'puid', 'registration_date', 'patient_phone','patient_address','patient_zip', 'doctor_id', 'doctor_name','doctor_fee','entry_fee','patient_issue','patient_room','patient_email','uid','patient_image','status','created_at','other_fee'];
	    protected $useTimestamps = true;
	    protected $createdField  = 'created_at';
	    protected $updatedField  = 'updated_at'; 
		//protected $updatedbyField  = 'updated_by'; //Customize
		//protected $createdbyField  = 'created_by'; //Customize
	    protected $deletedField  = 'deleted_at';
	    protected $returnType    = 'array';

	    protected $validationRules = [
	    	'patient_name'       => 'required|alpha_numeric_space|min_length[3]',
	    	'patient_zip'        => 'required',
	    	'patient_phone'      => 'required|exact_length[10]|numeric',
	    	'patient_address'    => 'required',
	    ];



	 public function updatePassword($tablename, $npwd, $id){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid',$id);
		$builder->update(['password'=> $npwd]);
		if ($this->db->affectedRows() > 0) { return true; }
		else { return false; }
	}
	
	
	//Get Logged In User data
	//public function getLoggedInUserData($uid) {
	public function getLoggedInUserData($uid, $tablename) {
		$this->table = $tablename; //patients_login tbl
		$builder = $this->db->table($this->table);
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	public function getLoggedInAdminData($uid){
		$builder = $this->db->table('admin_users');
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}
	//Get Logged In User data
	public function update_rec_by_args($tablename, $args, $data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo "<pre>"; print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
	}
	/*@params: $tablename, $args
	* @desc: Get highest sereal for particular day or date
	* @author: Neoarks Team
	* @date: May 23, 2023
	* @modify:
	*/
	public function today_highest_serial($tablename, $args) {
		$this->table = $tablename;
		$query = $this->table($this->table)
					->select('*')
					->where($args)
					->orderBy('id', 'DESC')
					->limit(1)
					->get();
        return $query->getRow();
    }  //Funciton - Closed

	/*@params: $tablename, $order_format, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	// public function filter_rec_by_args_with_status($tablename, $order_format, $args){ //moved to: commonForAllModel
	// 	$this->table = $tablename;
	// 	extract($order_format);
	// 	$builder = $this->db->table($this->table);
	// 	$builder->orderBy($order_format['column_name'],$order_format['order']);
	// 	$builder->where($args);
	// 	$result = $builder->get();
		
	// 	if (count($result->getResultArray())> 0) {
	// 		return $result->getResult();
	// 	}else{
	// 		return $result->getResult();
	// 	}
	// }

	/*@params: $tablename, $args
	* @desc: Get logged in patient data in patient section
	* @returns: Returns output in  object format 
	* @author: Neoarks Team
	* @date: Aug 22, 2023
	* @modify:
	*/	
	public function getLoggedInPatientsData($id){
		$builder = $this->db->table('patients_login');
		$builder->where('uid', $id);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) {
			return $result->getRow();
		}else{
			return false;
		}
	}
	
	/* @param: 
	* @desc: Return false if record exists except the logged-in user 
	* @use: 
	* @author: Neoark Team
	* @date: May 11th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/
	
	// public function unique_except_logged_in($tablename, $uniq_fld, $uniq_fld_val, $uid) { //Moved to CommonForAllModel.php
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
	}

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the two dimensional array format (eg. Array[0]['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_rec_by_orargs_arr($tablename, $args, $orArgs) { 
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC')
			->where($args)
			->orwhere($orArgs)
			->paginate(10);
	}
	
	public function fetch_rec_by_orargs_order_arr($tablename, $args, $orArgs,$order) { 
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC')
			->where($args)
			->orwhere($orArgs)
			->paginate(10);
			
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
		$result = $builder->where($args);
		$result = $builder->get();
		//echo $this->db->getLastQuery();
		if(count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	}

	// public function fetch_rec_by_args_datewise($tablename, $args, $start_date,  $end_date) {
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select('*');
	// 	$result = $builder->where($args);
	// 	//$result = $builder->like($args);
	// 	$result = $builder->where('appointment_date >=', $start_date);
	// 	$result = $builder->where('appointment_date <=', $end_date);
	// 	$result = $builder->get();
	// 	if(count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// }

	/*@params: $tablename, $args, $slot_start, $slot_end
	* @desc: Model function for fetching data form datewise 
	* @returns:
	* @author: Neoarks Team
	* @date: 6th Dec, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args_datewise($tablename, $args, $slot_start, $slot_end)
{
    $this->table = $tablename;
    $builder = $this->db->table($this->table);
    $builder->select('*');
	
    $builder->where($args)
            ->where('appointment_date >=', $slot_start)
            ->where('appointment_date <=', $slot_end);

    $result = $builder->get();

    return ($result->getNumRows() > 0) ? $result->getResult() : false;
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
	public function update_password($tablename, $token,$pass) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) { return true; }
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

	public function verifyEmail_with_args($tablename, $email){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('id, uid,status,username,password,email,level');
		$builder->where('email', $email);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
	}
	
	/*@params: $tablename, $order_format
	* @desc: used for Filteration 
	* @returns: Returns output in the two dimensional array format (eg. Array[0]['id'] )
	* @uses: Manage_medicine, and ...
	* @author: Neoarks Team
	* @date: August 9, 2023
	* @modify:
	*/
	public function filter_rec_by_paginate($tablename, $order_format){
		$this->table = $tablename;
		extract($order_format);
		return $this->table($this->table)
                ->orderBy($order_format['column_name'],$order_format['order'])
                ->paginate(10);
	}
	
	/*@params: $tablename, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
	public function fetch_rec_by_args_by_orlike($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->orlike($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->get();
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else{ return $result->getResult(); }
	}
	// /*@params: $tablename, $args
	// * @desc: 
	// * @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	// * @author: Neoarks Team
	// * @date: May 3, 2023
	// * @modify:
	// */	
	// public function fetch_rec_by_args($tablename, $args) { 
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select('*');
	// 	$builder->orderBy('id', 'DESC'); //not working
	// 	$result = $builder->where($args);
	// 	$result = $builder->get();
	// 	//echo "<pre>";print_r($result->getResult());die;
	// 	if (count($result->getResultArray()) > 0) { 
	// 		return $result->getResult(); 
	// 	}
	// 	else{ return false; }
	// }
	
	/*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @author: Neoarks Team
	* @date: 7th July, 2023
	* @modify:
	*/
	public function fetch_rec_by_args_like_orlike($tablename, $args, $likeArgs, $orlikeArgs){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->like($likeArgs);
		$builder->orlike($orlikeArgs);
		$builder->orderBy('id', 'DESC');
		$result = $builder->get();
		
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else{ return $result->getResult(); }
	}
	// /*@params: $tablename, $args, $data
	// * @desc: 
	// * @retuns: 
	// * @author: Neoarks Team
	// * @date: 12th August, 2023
	// * @modify:
	// */
	// public function fetch_rec_by_args($tablename, $args, $data){
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->where($args);
	// 	$builder->update($data);
	// 	//echo "<pre>"; print_r($builder);die;
	// 	if ($this->db->affectedRows() == 1) {  return true; }
	// 	else { return false; }
	// }
	
	/*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @retuns: In Array format
	* @author: Neoarks Team
	* @date: 7th July, 2023
	* @modify:
	*/

	public function fetch_rec_by_args_filter_order_like($tablename, $args, $likeArgs, $order_format) { 
		extract($order_format);
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->where($args)
			->like($likeArgs)
			->orderBy($order_format['column_name'], $order_format['order']) //->orderBy('id', 'DESC')
			->paginate(10);
	}

	public function fetch_rec_by_args_like($tablename, $args, $likeArgs) { 
		$this->table = $tablename;
		 return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC') //->orderBy($order_format['column_name'], $order_format['order'])
			->where($args)
			->like($likeArgs)
			->paginate(10);
	}

	/*@params: $tablename, $args
	* @desc: Fetch records based on passed `tablename`, where conditions $args,
	* @retuns: Array format
	* @author: Neoarks Team
	* @date: 7th July, 2023
	* @modify:
	*/
	public function fetch_rec_by_args_with_status($tablename, $args) { 
		$this->table = $tablename;
		 return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC')
			->where($args)
			->paginate(10);		
	}


	public function fetch_rec_by_args_like_order($tablename, $args, $likeArgs, $order_format){
		$this->table = $tablename;
		extract($order_format);
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->like($likeArgs);
		$builder->orderBy($order_format['column_name'], $order_format['order']);
		//$this->paginate(10);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) { 
			//echo "<pre>"; print_r($result->getResult());die;
			return $result->getResult();
		 }
		else{ return $result->getResult(); }
	}

				

	//$model->setValidationRule($fieldName, $fieldRules);
	public function search($key) {
		return $this->table('patients')->like('patient_name',$key);
		// $builder = $this->table('patients');
		// $builder->like('patient_name',$keys);
		// // $query   = $builder->paginate(10);
		// return $query;
	}

    public function filter_rec_by_args($tablename, $order_format){
		$this->table = $tablename;
		extract($order_format);
		//$builder = $this->db->table('patients');
		$builder = $this->db->table($this->table);
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$result = $builder->get();
		if(count($result->getResultArray())> 0) { return $result->getResult(); }
		else{ return $result->getResult(); }
	}

	public function fetch_rec_by_status_with_pagination($tablename, $args) {
		$this->table = $tablename;
		return $this->table($this->table)
			->select('*')
			->where($args)
			->paginate(10);
	}

	/*@params: $tablename, $args
	* @desc: Search recrods from `patients` table based on keyword for 'patient_name`
	* @retuns: 
	* @use: MOI (Matter of investigation) of it use
	* @author: Neoarks Team
	* @date: 7th July, 2023
	* @modify:
	*/
	public function search_patients_name($key, $args) { //Ref, alternate & dynamic commonForAllModel/search_records
		return $this->table('patients')->like('patient_name',$key)->where($args);
    }

	/*@params: $key, $args
	* @desc: Search department name by searching
	* @author: Neoarks Team
	* @date: 11 August, 2023
	* @modify:
	* @Remark: Custom function for searching in manage department, However it is not working yet.... (MOI)
	*/

	public function search_records($tablename, $fld, $key, $args) {  //Ref to Common 
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->table($this->table)->like($this->srchfield, $this->srchKey)->where($this->whereArgs);
	}

	/*@params: $key, $args
	* @desc: Search doctor name by searching
	* @author: Neoarks Team
	* @date: 11 August, 2023
	* @modify:
	* @Remark: Custom function for searching in manage doctor, However values are not coming still.... (MOI)
	*/
	public function search_doctor_name($key, $args) {   
		return $this->table('doctor')->like('doctor_name',$key)->where($args);
    }
	// public function search_records($tablename, $srchKeylike, $args) { 
	//	$this->table = $tablename; 
	// 	//print_r($this->db->get($this->table));die;
	// 	 $this->table($this->table)
	// 		//->like('patient_name',$key)
	// 		->like($srchKeylike)
	// 		->where($args);
	// 	echo "<pre>"; print_r($this);die;
			
    // }

	// public function fetch_all_records($tablename) { //replaced same function in commonForAllModel
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray())> 0) { return $result->getResult(); }
	// 	else{ return false; }	
	// }

	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}
		

	// public function filter_rec_by_args_with_pagination($tablename, $order_format){
	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		extract($order_format);
		$this->table = $tablename;
		return $this->table($this->table)
			->orderBy($order_format['column_name'],$order_format['order'])
			 ->where($args)
			->paginate(10);
		//echo "<pre>"; print_r($this);
	}

	// Inside Patient_model.php

	/*@params: $doctorId, $startDate, $endDate
	* @desc: Available doctor slots in between date range
	* @author: Neoarks Team
	* @date: 5th december, 2023
	* @modify:
	* @Remark: Custom function for searching doctor available slots 
	*/
	public function get_available_slots($doctorId, $startDate, $endDate) {
		return $this->db->table('doctor_slots')
			->select('appointment_date', 'start_time', 'end_time')
			->where('dr_available', 1)
			->where('doctor_id', $doctorId)
        ->where('appointment_date >=', $startDate)
        ->where('appointment_date <=', $endDate)
        ->get()
        ->getResultArray();
	}

	
// Inside your patient_model
public function getNumberOfPayments($args)
{
    // Adjust the query based on your database structure
    $result = $this->db->table('patients_pay_charges')->where($args)->get()->getResultArray();
    return $result;
}


} //class - Closed

?>