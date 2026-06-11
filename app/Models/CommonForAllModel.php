<?php 
namespace App\models; //Namespace must at very first/top line

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

class CommonForAllModel extends Model {


	//****** functions from CommonModel - START ********//

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the two dimensional array format based on passed orderby key value 
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args_arr_dynamic_orderby($tablename, $order_format, $args) { 
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->orderBy($order_format['column_name'],$order_format['order'])
			->where($args)
			->paginate(10);
			//echo "<pre>";print_r($this->table);die;
	}

	/*@params: $tablename, $args
	* @desc: Fetch table records with pagination 
	* @returns: Returns output in two dimentional array format (eg. Array[0]['id'] )
	* @author: Neoarks Team
	* @date: 
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

	public function verifyEmail_with_args($tablename, $email){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('id, uid,status,username,password,email,level');
		$builder->where('email', $email);
		$result = $builder->get();
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
	}

	public function updatedAt($tablename, $uid){
		$builder = $this->db->table($tablename);
		$builder->where('uid', $uid);
		$builder->update(['updated_at'=> date('Y-m-d h:i:s')]);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
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


	public function update_password($tablename, $token,$pass) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}
	

	public function getLoggedInFrontdeskData($uid){
		$builder = $this->db->table('blood_bank_users');
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
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

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	// public function fetch_rec_by_args($tablename, $args) { 
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select('*');
	// 	$builder->orderBy('id', 'DESC');
	// 	$result = $builder->where($args);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// }

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
		if (count($result->getResultArray()) > 0) { 
			return $result->getResult(); 
		}
		else{ return false; }
	} //Function - Closed


	public function fetch_all_records($tablename) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select("*");
		$builder->orderBy('id', 'DESC');
		$this->paginate(10);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else { return false; }	
	} //function - Closed


	//public function fetch_all_records($tablename) { //same as commonModel function, except, pagination
	public function fetch_allrecords_bypage($tablename) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select("*");
		$builder->orderBy('id', 'DESC');
		$this->paginate(10);
		$result = $builder->get();
		//echo $this->db->getLastQuery;die;
		if (count($result->getResultArray())> 0) { return $result->getResult();
		}
		else { return false; }	
	} //function - Closed


	public function delete_records($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->delete();
		if ($this->db->affectedRows() == 1) { return true; }
		else{ return false; }
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
		if ($this->db->affectedRows() == 1) { return $this->db->insertID(); }
		else { return false; }
	}

   /* @params: $tablename, $args, $data, $inst_tbl, $tbldata
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
		$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->new_patient_arr);
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



	public function getLoggedInDonor_data($id){
		$builder = $this->db->table('blood_bank_users');
		$builder->where('uid', $id);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		extract($order_format);
		$this->table = $tablename;
		return $this->table($this->table)
			->orderBy($order_format['column_name'],$order_format['order'])
			 ->where($args)
			->paginate(10);
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
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else { return $result->getResult(); }
	}


	/*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, 
	* $likeArgs & $orlikeArgs array 
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

	/*@params: $tablename, $args
	* @desc: fetch record by status with pagination
	* @retuns: 
	* @use: MOI (Matter of investigation) of it use
	* @author: Neoarks Team
	* @date: Aug 18, 2023
	* @modify:
	*/
	public function fetch_rec_by_status_with_pagination($tablename, $args) {
		$this->table = $tablename;
		return $this->table($this->table)
			->select('*')
			->where($args)
			->paginate(10);
	}

	/*@params: $tablename, $args
	* @desc: fetch record by status with pagination
	* @retuns: 
	* @use: MOI (Matter of investigation) of it use
	* @author: Neoarks Team
	* @date: Aug 18, 2023
	* @modify:
	*/
	public function fetch_rec_by_duration_pagination($tablename, $args, $from, $to) {
		$this->table = $tablename;
		return $this->table($this->table)
			->select('*')
			->where($args)
			->where('booking_date >=', $from)
			->where('booking_date <=', $to)
			->paginate(10);
		
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
		
		if (count($result->getResultArray()) > 0) {  return $result->getResult();  }
		else{  return false;  }
	} //Function - Closed

	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
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

   /* @params: $tablename, $order_format, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function filter_rec_by_args_with_status($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		$builder = $this->db->table($this->table);
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$builder->where($args);
		$result = $builder->get();
		
		if (count($result->getResultArray())> 0) { return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	public function get_max_val($tablename, $fld_name) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->selectMax($fld_name);
		
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	} //Function - Closed

	public function getPatientRecordWithAppointment($id) {
		
		$db      = \Config\Database::connect();
		$builder = $db->query('select t1.*,t2.gender as doc_gender,t2.dr_specialization,t2.education from patients t1 left join doctor t2 on t1.doctor_id=t2.id   where t1.id='.$id.'');
		$result=$builder->getRow();
		//echo '<pre>';
		//print_r($result);
		return $result;
    } 

	// public function update_rec_by_args($tablename, $args, $data){
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->where($args);
	// 	$builder->update($data);
	// 	//echo "<pre>"; print_r($builder);die;
	// 	if ($this->db->affectedRows() == 1) {  return true; }
	// 	else { return false; }
	// }

	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the two dimensional array format (eg. Array[0]['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args_arr($tablename, $args) { //MOI use of the function
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
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return $result->getResult(); }
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
		//echo "<pre>";print_r($this);die;
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
	
	public function search_records($tablename, $fld, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->table($this->table)->like($this->srchfield, $this->srchKey)->where($this->whereArgs);
	}

	//Get Logged In User data
	public function getFrontdeskUserData($tablename, $id) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $id);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) {
			return $result->getRow();
		}else{
			return false;
		}
	}

	public function search_doc($tablename, $fld, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->table($this->table)->like($this->srchfield, $this->srchKey)->where($this->whereArgs);
	}

	public function getAllDoctors($tablename, $fld, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->findAll();
	}

	//****** functions from CommonModel - END ********//

	/** Get doctor_slots and doctor details for showing doctor available slots on doctor section
	 * Fetch Records based on passed token
	 * @param string $leftTbl   Main (left) table name.
	 * @param string $rightTbl  Joined (right) table name.
	 * @param string $joinOn    Join condition (e.g., 'a.id = b.foreign_id').
	 * @param array  $conditions Optional where conditions.
	 * @author: Neoarks Team
	 * @date: February 7th, 2024
	 * @copyrights: Neoark Software Pvt Ltd
	 * @return array|false      Result set or false if none found.
	 */
	public function fetch_rec_by_args_leftjoin(string $leftTbl, string $rightTbl, string $joinOn, array $conditions = []) {
	    try {
	        // Build query
	        $builder = $this->db->table($leftTbl);
	        $builder->join($rightTbl, $joinOn, 'left');

	        // Define select columns (explicit is better than dynamic strings)
	        $selectFields = [
	            "$rightTbl.doctor_name", "$rightTbl.profile_pic", "$rightTbl.education",
	            "$rightTbl.gender", "$rightTbl.dr_specialization", "$rightTbl.doctor_fee",
	            "$leftTbl.dr_available", "$leftTbl.slot_start", "$leftTbl.slot_end",
	            "$leftTbl.start_end_slot", "$leftTbl.appointment_date",
	            "$leftTbl.doctor_id", "$leftTbl.id", "$leftTbl.id as slot_id"
	        ];

	        $builder->select(implode(', ', $selectFields));

	        if (!empty($conditions)) { $builder->where($conditions); } #Where condition
	        $builder->orderBy("$leftTbl.slot_order", 'asc'); #OrderedBy results
	        $query = $builder->get(); #Execute query
	        $results = $query->getResult();
	        return !empty($results) ? $results : false;

	    } 
	    catch (\Exception $e) { // Optional: log error or handle gracefully
	        // log_message('error', 'DB Error in fetch_rec_by_args_leftjoin: ' . $e->getMessage());
	        return false;
	    }
	} //function - Closed

	
   /* @param: Get records group by
	* @desc: Fetch Records based on passed token
	* @return: Result set or false if not existing
	* @use:
	* @author: Neoarks Team
	* @date: February 7th, 2023
	* @modify:
	*/
	public function fetch_rec_by_args_group_by($tablename, $grp_by_fld, $args) {
	    $this->table = $tablename;
	    $builder = $this->db->table($this->table);
	    $builder->select($grp_by_fld); // Select the group-by field
	    if (!empty($args)) { $builder->where($args); } // Apply the passed filters
	    $builder->groupBy($grp_by_fld); // Group by the field specified
	    $result = $builder->get(); // Get the query result

	    // Check if there are results
	    if(count($result->getResultArray()) > 0) { return $result->getResult(); }  //Return result 
	    else { return false; } // Return false if no records match
	} //function - closed


	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function get_pic_by_id($fld, $tablename, $id) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select($fld);
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where('id', $id);
		$result = $builder->get();
		//echo "<pre>";print_r($result->getResult());die;
		if (count($result->getResultArray()) > 0) { 
			return $result->getResult(); 
		}
		else{ return false; }
	} //Function - Closed

	public function fetch_rec_by_uid ($tablename, $args){ //w.r.t Medicine_model getLoggedInAccountantData
		//$builder = $this->db->table('register_all_users');
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}
	

	/* @param: $pay_tbl, $paid_amt_fee_arr, $apmt_tbl, $apmt_args, $apmt_updt_arr, $slots_tbl, $slot_args, $slot_updt_arr
	* @desc: Save appointment fee: Insert into table `patients_pay_charges` & update `booked_doctor_appointment` & `doctor_slots`
	* @return: true or error number 
	* @use: Controller: Home, Patients, 
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify: 20th Dec, 2023
	*/
	public function insert_two_n_update_one_tbl($insrt_tbl1, $insrt_data_arr1, $insrt_tbl2, $insrt_data_arr2, $uptd_tbl, $updt_args, $updt_data_arr) {
		$this->insrt_tbl1 = $insrt_tbl1; 
		$this->insrt_data_arr1 = $insrt_data_arr1; 

		$this->insrt_tbl2 = $insrt_tbl2; 
		$this->insrt_data_arr2 = $insrt_data_arr2; 
		$this->uptd_tbl = $uptd_tbl; 
		//$this->apmt_updt_arr = $apmt_updt_arr; 
		$this->updt_args = $updt_args; 
		$this->updt_data_arr = $updt_data_arr; 

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $this->insrt_tbl1; //`treatment_expenses_history` tbl

		$this->last_inst_id1 = $this->Insertdata_return_id($this->table, $this->insrt_data_arr1);
		if((int) $this->last_inst_id1 > 0) {
			$this->insrt_data_arr2['payment_note'] 	= 'w.r.t `treatment_expenses_history tbl id`: '.$this->last_inst_id1;
			$this->table = $this->insrt_tbl2; //`patients_pay_charges` tbl
			$this->last_inst_id2 = $this->Insertdata_return_id($this->table, $this->insrt_data_arr2);
			if((int) $this->last_inst_id2 > 0) {
				$this->table = $this->uptd_tbl;  // `booked_doctor_appointment` tbl
				if($this->update_rec_by_args($this->table, $this->updt_args, $this->updt_data_arr)) {
					$this->db->transCommit(); // Commit the transaction
					return $this->last_inst_id2;
				}
				else { 
					$this->db->transRollback(); // Rollback the transaction on exception
					return 2; //Failed to update appointment table 
				}
			}
			else {
				$this->db->transRollback(); // Rollback the transaction on exception
				return 3; //Failed to insert into patient pay charges table 
			}
		}
		else { 
			$this->db->transRollback(); // Rollback the transaction on exception
			return 4; //Failed to insert treatment expences table 
		}
	} //function - Closed
	

	/* @param: $insrt_tbl, $insrt_data_arr, $uptd_tbl, $updt_args, $updt_data_arr
	* @desc: Insert into ONE table & update one table by using the last inserted ID
	* @return: last inserted_id OR `false`
	* @use: Controller: Home, Patients,Doctor, Admin
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify: 22th Jan, 2024
	*/
	public function return_inserted_id_n_update($insrt_tbl, $insrt_data_arr, $uptd_tbl, $updt_args, $updt_data_arr) {
		$this->insrt_tbl = $insrt_tbl; 
		$this->insrt_data_arr = $insrt_data_arr; 
		$this->uptd_tbl = $uptd_tbl; 
		$this->updt_args = $updt_args; 
		$this->updt_data_arr = $updt_data_arr; 

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $this->insrt_tbl;

		$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->insrt_data_arr);
		if((int) $this->last_inst_id > 0) {
			$this->table = $this->uptd_tbl; 
			if($this->update_rec_by_args($this->table, $this->updt_args, $this->updt_data_arr)) {
				$this->db->transCommit(); // Commit the transaction
				return $this->last_inst_id;
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return false; //Failed to update records into table 
			}
		}
		else { 
			$this->db->transRollback(); // Rollback the transaction on exception
			return false; //Failed to insert data table 
		}
	} //function - Close

	/* @param: $insrt_tbl, $insrt_data_arr, $uptd_tbl, $updt_args, $updt_data_arr
	* @desc: Insert into ONE table & update one table by using the last inserted ID
	* @return: last inserted_id OR `false`
	* @use: Controller: Home, Patients,Doctor, Admin
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify: 22th Jan, 2024
	*/
	public function return_inserted_id_n_update_for_refid($insrt_tbl, $insrt_data_arr, $uptd_tbl, $updt_args, $updt_data_arr) {
		$this->insrt_tbl = $insrt_tbl; 
		$this->insrt_data_arr = $insrt_data_arr; 
		$this->uptd_tbl = $uptd_tbl; 
		$this->updt_args = $updt_args; 
		$this->updt_data_arr = $updt_data_arr; 

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $this->insrt_tbl;

		$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->insrt_data_arr);
		if((int) $this->last_inst_id > 0) {
			$this->updt_data_arr['ref_id'] = $this->last_inst_id;
			$this->table = $this->uptd_tbl; 
			if($this->update_rec_by_args($this->table, $this->updt_args, $this->updt_data_arr)) {
				$this->db->transCommit(); // Commit the transaction
				return $this->last_inst_id;
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return false; //Failed to update records into table 
			}
		}
		else { 
			$this->db->transRollback(); // Rollback the transaction on exception
			return false; //Failed to insert data table 
		}
	} //function - Close


	/*@params: $tablename, $args, $data, $inst_tbl, $tbldata
	* @desc: 
	* @use: Delete Doctor (update `register_all`, and `doctor` tables)
	* @author: Neoarks Team
	* @date: 19th August, 2023
	* @modify:
	*/
	public function update_into_two_tables($tbl1, $args1, $data_arr1, $tbl2, $args2, $data_arr2) { //used function update_rec_by_args()
		$this->table = $tbl1;
		$this->updt_data_arr1 = $data_arr1;
		$this->updt_data_arr2 = $data_arr2;
		$this->db->transOff();
		$this->db->transStart();
		if($this->update_rec_by_args($this->table, $args1, $this->updt_data_arr1)) {
			$this->table = $tbl2; 
			if($this->update_rec_by_args($this->table, $args2, $this->updt_data_arr2)) {
				$this->db->transCommit(); // Commit the transaction
				return true;
			}
			else {
				$this->db->transRollback(); // Rollback the transaction on exception
				return 2; //2: Failed to update second (eg. patient paid charges) table
			}
		}
		else {
			$this->db->transRollback(); // Rollback the transaction on exception
			return 3; //3: Failed to update 1st (booked appointments) table.
		}
	} //function - Closed


	public function update_rec_by_args($tablename, $args, $data) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo"<pre>";print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
	}

	/* @param: $pay_tbl, $paid_amt_fee_arr, $apmt_tbl, $apmt_args, $apmt_updt_arr, $slots_tbl, $slot_args, $slot_updt_arr
	* @desc: Save appointment fee: Insert into table `patients_pay_charges` & update `booked_doctor_appointment` & `doctor_slots`
	* @return: true or error number 
	* @use: Controller: Home, Patients, 
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	// public function save_appointment_fee($xpnc_tbl, $xpnc_arr, $pay_tbl, $paid_amt_fee_arr, $apmt_tbl, $apmt_args, $apmt_updt_arr, $slots_tbl, $slot_args, $slot_updt_arr) {
	// 	$this->paid_amt_fee_arr = $paid_amt_fee_arr; 
	// 	$this->apmt_tbl = $apmt_tbl; 
	// 	$this->apmt_args = $apmt_args; 
	// 	$this->apmt_updt_arr = $apmt_updt_arr; 
	// 	$this->slots_tbl = $slots_tbl; 
	// 	$this->slot_args = $slot_args; 
	// 	$this->slot_updt_arr = $slot_updt_arr;
	// 	$this->hospital_xpnc_arr = $xpnc_arr;

	// 	$this->db->transStart(false); // Disable auto-commit
	// 	$this->table = $xpnc_tbl; //`treatment_expenses_history` Table
	// 	$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->hospital_xpnc_arr);
	// 	if((int) $this->last_inst_id > 0) {
	// 		$this->hospital_xpnc_arr = ['expns_ref' 	=> $this->last_inst_id, ];
	// 		$this->table = $pay_tbl; //`patients_pay_charges` Table
	// 		$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->paid_amt_fee_arr);
	// 		if((int) $this->last_inst_id > 0) {
	// 			$this->table = $apmt_tbl; //`booked_doctor_appointment` Table
	// 			if($this->update_rec_by_args($this->table, $this->apmt_args, $this->apmt_updt_arr)) {
	// 				$this->table = $slots_tbl; //`doctor_slots` Table
	// 				if($this->update_rec_by_args($this->table, $this->slot_args, $this->slot_updt_arr)) {
	// 					$this->db->transCommit(); // Commit the transaction
	// 					return true; 
	// 				}
	// 				else { 
	// 					$this->db->transRollback(); // Rollback the transaction on exception
	// 					return 2; //Failed to update doctor slot table
	// 				}
	// 			}
	// 			else { 
	// 				$this->db->transRollback(); // Rollback the transaction on exception
	// 				return 3; //Failed to update appointment table 
	// 			}
	// 		}
	// 		else {
	// 			$this->db->transRollback(); // Rollback the transaction on exception
	// 			return 4; //Failed to update appointment table 
	// 		}
	// 	}
	// 	else { 
	// 		$this->db->transRollback(); // Rollback the transaction on exception
	// 		return 5; //Failed to insert pay charges table 
	// 	}
	// } //function - Close

	public function save_appointment_fee($pay_tbl, $paid_amt_fee_arr, $apmt_tbl, $apmt_args, $apmt_updt_arr, $slots_tbl, $slot_args, $slot_updt_arr) {
		$this->paid_amt_fee_arr = $paid_amt_fee_arr; 
		$this->apmt_tbl = $apmt_tbl; 
		$this->apmt_args = $apmt_args; 
		$this->apmt_updt_arr = $apmt_updt_arr; 
		$this->slots_tbl = $slots_tbl; 
		$this->slot_args = $slot_args; 
		$this->slot_updt_arr = $slot_updt_arr;

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $pay_tbl; //`patients_pay_charges` Table

		$this->last_inst_id = $this->Insertdata_return_id($this->table, $this->paid_amt_fee_arr);
		if((int) $this->last_inst_id > 0) {
			$this->table = $apmt_tbl; //`booked_doctor_appointment` Table
			if($this->update_rec_by_args($this->table, $this->apmt_args, $this->apmt_updt_arr)) {
				$this->table = $slots_tbl; //`doctor_slots` Table
				if($this->update_rec_by_args($this->table, $this->slot_args, $this->slot_updt_arr)) {
					$this->db->transCommit(); // Commit the transaction
					return true; 
				}
				else { 
					$this->db->transRollback(); // Rollback the transaction on exception
					return 2; //Failed to update doctor slot table
				}
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return 3; //Failed to update appointment table 
			}
		}
		else { 
			$this->db->transRollback(); // Rollback the transaction on exception
			return 4; //Failed to insert pay charges table 
		}
	} //function - Close
	

	/*@params: tbl `register_all_users` and data array
	* @desc: Doctor registration - Inser records into `doctor` & `register_all_users` tables
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/
	public function Insertdata_return_id($tablename, $data){
		$this->table = $tablename;
	 	$builder = $this->db->table($this->table);
	 	$res = $builder->insert($data);
	 	//echo "<pre>"; print_r($builder);die;
	 	if ($this->db->affectedRows() == 1) { return $this->primaryKey = $this->db->insertID(); }
		else { return false; }
	}


    /* @param: table name, unique token
	* @desc: Fetch Records based on passed token
	* @return: Result set or false if not existing
	* @use:Admin, Doctor, 
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	public function get_records_for_token($tablename, $token) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where('reset_pass_token', $token);
		$result = $builder->get();
		//echo "<pre>";print_r($builder);die;
		if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
		else { return false; }
	}

	
	
	/* @param: table name, table field name, table field value
	* @desc: Return true if record NOT EXIST or false if records exists
	* @return: Result set or false if not existing
	* @use:
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	public function is_zero_record($tablename, $uniq_fld, $uniq_fld_val) { 
		$this->table = $tablename;
		$this->uniq_fld = $uniq_fld;
		$this->uniq_fld_val = $uniq_fld_val;
		return $this->db->table($this->table)
			->where($this->uniq_fld, $this->uniq_fld_val)
			->countAllResults() === 0;
	} //function - Closed


	/* @param: table name, table field name, table field value
	* @desc: Return true for SINGLE record else return false
	* @return: Return true for SINGLE record else return false
	* @use: Controller: Blood_bank, Admin, Doctor_login
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	public function is_single_record($tablename, $uniq_fld, $uniq_fld_val) { 
		$this->table = $tablename;
		$this->uniq_fld = $uniq_fld;
		$this->uniq_fld_val = $uniq_fld_val;
		
		return $this->db->table($this->table)
			->where($this->uniq_fld, $this->uniq_fld_val)
			->countAllResults() === 1;
		//echo "<pre>"; print_r($this);die;
	} //function - Closed



	/* @param: $tablename, $uniq_fld, $uniq_fld_val, $uid 
	* @desc: Return false if record exists except the logged-in user 
	* @use: Blood Bank, Admin, Doctor, 
	* @author: Neoark Team
	* @date: May 11th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/
	public function unique_except_logged_in($tablename, $uniq_fld, $uniq_fld_val, $uid) { 
		$this->table = $tablename;
		$this->uniq_fld = $uniq_fld;
		$this->uniq_fld_val = $uniq_fld_val;
		$this->uid = $uid;
		
		$builder = $this->db->table($this->table);
		$builder->select("*");
		$result = $builder->where($this->uniq_fld, $this->uniq_fld_val);
		$result = $builder->where('uid !=', $this->uid);
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	} //function - Closed
	
} //class - Closed