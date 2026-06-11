<?php namespace App\Models;

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

class DoctorModel extends Model {

    protected $table = 'doctor_slots';
    protected $primaryKey = 'id';
    protected $allowedFields = ['doctor_id', 'appointment_date','slot_start','slot_end','	booked','dr_available','patient_id','status','created_at', 'updated_at'];

    public function __construct(){
    	parent::__construct();
    	$this->commonForAllModel = new CommonForAllModel(); //Called Custom model
  	}

	public function verifyToken($tablename, $token){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('uid,username,updated_at');
		$builder->where('uid', $token);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
	}

   


   /* @param: table name, unique token
	* @desc: Fetch Records based on passed token
	* @return: Result set or false if not existing
	* @use:
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	// public function get_records_for_token($tablename, $token){ //Moved to CommonForAllModel.php
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select('*');
	// 	$builder->where('reset_pass_token', $token);
	// 	$result = $builder->get();
	// 	if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
	// 	else { return false; }
	// }

	public function update_password($tablename, $token, $pass) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}
	public function saveLoginInfo($data){
		$builder = $this->db->table('login_activity');
		$builder->insert($data);
		if ($this->db->affectedRows() == 1) { return $this->db->insertID(); }
		else { return false; }
	}


	public function getLoggedInDoctorData($uid){
		$builder = $this->db->table('doctor');
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}
	//Get Logged In User data

	// public function verify_existance($tablename, $uniq_fld, $uniq_fld_val) { //Replaced with backend validation
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

	
	public function getPatientRecordWithAppointment($id) {
		$db      = \Config\Database::connect();
		$builder = $db->query('select t1.*,t2.gender as doc_gender,t2.dr_specialization,t2.education from patients t1 left join doctor t2 on t1.doctor_id=t2.id   where t1.id='.$id.'');
		$result=$builder->getRow();
		// echo '<pre>';
		// print_r($result);die;
		return $result;
    } 

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	
	public function fetch_sum_by_args($tablename,$fld, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->selectSum($fld);
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	} //Function - Closed

	
	// public function fetch_all_records($tablename) { //replace with commonModel function
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray())> 0) { return $result->getResult(); }
	// 	else{ return false; }	
	// }
	
    public function fetch_rec_by_args($tablename, $args) {
		$this->table = $tablename;
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $result = $builder->where($args);
        $result = $builder->get();
		if(count($result->getResultArray()) > 0) { return $result->getResult(); }
        else { return false; }
    }

	public function fetch_rec_by_args_order_by($tablename, $args) {
		$this->table = $tablename;
        $builder = $this->db->table($this->table);
        $builder->select('*');
		$builder->orderBy('slot_order', 'asc');
        $result = $builder->where($args);
        $result = $builder->get();
		if(count($result->getResultArray()) > 0) { return $result->getResult(); }
        else { return false; }
    }

	// public function fetch_rec_by_args_group_by($tablename, $grp_by_fld, $args) {
	// 	$this->table = $tablename;
    //     $builder = $this->db->table($this->table);
    //     $builder->select('*');
	// 	$builder->groupBy($grp_by_fld);
    //     $result = $builder->where($args);
    //     $result = $builder->get();
		
	// 	if(count($result->getResultArray()) > 0) { return $result->getResult(); }
    //     else { return false; }
    // }
	
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
	
   /* @params: $tablename, $args
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

	/*@params: $tablename, $args, $data, $inst_tbl, $tbldata
	* @desc: 
	* @use: Admin, Doctor, Frontdesk
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
	public function update_appintment_inst_patient_info($tablename, $args, $data, $inst_tbl, $tbldata) {
		$this->args = $args;
		$this->updt_data_arr = $data;
		$this->new_patient_arr = $tbldata;
 		
		$this->db->transStart(false); // Disable auto-commit
		$this->table = $inst_tbl; //`patients` or `revisit_patients table
		$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $this->new_patient_arr);
		if($this->last_inst_id !== false) {
			$this->table = $tablename; //`booked_doctor_appointment` table
			if(!isset($this->new_patient_arr['patient_id'])) { // Non-loggedin patient has not patient_id
				$this->updt_data_arr['patients_id'] = $this->last_inst_id; //Updated patient_id
			}
			$builder = $this->db->table($this->table);
			$builder->where($this->args);
			$builder->update($this->updt_data_arr);
			if ($this->db->affectedRows() == 1) {
				$this->db->transCommit(); // Commit the transaction
				return true;
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return 3; //3: Revisited Patients info insertion failed
			}
		}
		else {
			$this->db->transRollback(); // Rollback the transaction on exception
			return 4; //3: Revisited Patients info insertion failed
		}
	} //function - Closed

	
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
			echo "<pre>";print_r($this->table);die;
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


    public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
        $this->table = $tablename;
        extract($order_format);
        return $this->table($this->table)
                ->orderBy($order_format['column_name'],$order_format['order'])
                ->where($args)
                ->paginate(10);
    }

    /*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @retuns: In Array->Object format
	* @refence: Function get_user_data_in_arr()
	* @Uses: Change Password for: doctor, 
	* @author: Neoarks Team
	* @date: 15th July, 2023
	* @modify:
	*/
	public function getLoggedInUserData($uid, $tablename) {
		$this->table = $tablename; //patients_login tbl
		$builder = $this->db->table($this->table);
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	/*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @retuns: In Array format by using - return (array) $result->getRow();
	* @refence: Function getLoggedInUserData()
	* @Uses: Verifying registered doctor email
	* @author: Neoarks Team
	* @date: 15th November, 2023
	* @modify:
	*/
	public function get_user_data_in_arr($uid, $tablename) { //Ref: getLoggedInUserData
		$this->table = $tablename; //patients_login tbl
		$builder = $this->db->table($this->table);
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return (array) $result->getRow(); }
		else { return false; }
	}
	

	public function verifyAccountantEmail($tablename, $email){ 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where('email', $email);
		$result = $builder->get();
		if ($this->db->affectedRows() > 0) { return $result->getRowArray(); }
		else { return false; }
	}

    public function fetch_rec_by_args_with_status($tablename, $args){
		$this->table = $tablename;

	     return $this->table($this->table)
                ->select('*')
                ->where($args)
				->orderBy('id', 'DESC')
                ->paginate(10);
				//echo "<pre>";print_r($this);die;
		}
    public function search_records($tablename, $fld, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->table($this->table)->like($this->srchfield, $this->srchKey)->where($this->whereArgs);
	}

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
   
	public function get_max_val($tablename, $fld_name) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->selectMax($fld_name);
		
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	} //Function - Closed

	public function update_rec_by_args($tablename, $args, $data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo "<pre>"; print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
	}

    public function Insertdata($tablename, $data) {
        $builder = $this->db->table($tablename);
        $res = $builder->insert($data);
        if($this->db->affectedRows() == 1) { return true; }
        else { return false; }
    }
    public function updatePassword($tablename,$npwd, $id){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid',$id);
		$builder->update(['password'=> $npwd]);
		if ($this->db->affectedRows() > 0) {
			return true;
		}else{
			return false;
		}
	}

	// public function deleteDrSlotsData($tablename, $where){
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->whereIn('id',$where);
	// 	$builder->delete();
	// 	if ($this->db->affectedRows() > 0) { return true; }
	// 	else { return false; }
	// }

	public function deleteDrSlotsData($tablename, $wherearg){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($wherearg);
		$builder->delete();
		if ($this->db->affectedRows() > 0) { return true; }
		else { return false; }
	}


    public function getDrFreeSlots($tablename,$where){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($where);
		$query=$builder->get();
		if (count($query->getResult()) > 0) { return $query->getResult(); }
		else { return false; }	
	}

    public function checkalreadyDrSlotsData($tablename, $where){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($where);
		$query = $builder->get();
		if (count($query->getResult()) > 0) { return true; }
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
	// public function unique_except_logged_in($tablename, $uniq_fld, $uniq_fld_val, $uid) { //Moved to CommonForAllModel
	// 	$this->table = $tablename;
	// 	$this->uniq_fld = $uniq_fld;
	// 	$this->uniq_fld_val = $uniq_fld_val;
	// 	$this->uid = $uid;
		
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$result = $builder->where($this->uniq_fld, $this->uniq_fld_val);
	// 	$result = $builder->where("uid !=", $this->uid);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// } //function - Closed
	
    
} //Class - Closed