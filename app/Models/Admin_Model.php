<?php
namespace App\models;


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

class Admin_Model extends Model {


	public function __construct(){
      parent::__construct();
      $this->commonForAllModel = new CommonForAllModel(); //Called Custom model
  }

 /* @param: table name, arg-1, arg-12
	* @desc: Return true if record NOT EXIST or false if records exists
	* @return: Result set or false if not existing
	* @use:
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/

	public function import_wards(string $tablename, string $csvPath){
	    $this->table = $tablename;
	    $builder = $this->db->table($this->table);
	    
	    $csvFile = fopen($csvPath, 'r');
	    if (!$csvFile) { return false; }

	    $header = fgetcsv($csvFile); //Read the first row as header
	    $data   = [];

	    while (($row = fgetcsv($csvFile)) !== false) {
	        $data[] = array_combine($header, $row);
	    }
	    fclose($csvFile);

	    if (!empty($data)) {
	        $builder->insertBatch($data);
	        return true;
	    }

	    return false;
	}//function - Closed



	public function is_zero_where_multi_args($tablename, $arg_arr) { 
		$this->table = $tablename;
		$this->tot_args = count($arg_arr);
		$builder = $this->db->table($this->table);
		for ($i = 0; $i < $this->tot_args; $i++) {
			$builder->where($arg_arr[$i]);
		}
		return $builder->countAllResults() === 0;
	} //function - Closed

	// public function is_zero_where_multi_args($tablename, $arg1, $arg2, $arg3) { 
	// 	$this->table = $tablename;
	// 	if(isset($arg3) || $arg3 == '') {
	// 		return $this->db->table($this->table)
	// 			->where($arg1)
	// 			->where($arg2)
	// 			->countAllResults() === 0;
	// 	}
	// 	else {
	// 		return $this->db->table($this->table)
	// 			->where($arg1)
	// 			->where($arg2)
	// 			->where($arg3)
	// 			->countAllResults() === 0;
	// 	}
	// } //function - Closed

	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
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
	
	// public function verify_existance($tablename, $uniq_fld, $uniq_fld_val) { //Replaced with CommonForAllModel/is_single_record()
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

	public function update_password($tablename, $token,$pass) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid', $token);
		$builder->update(['password' => $pass]);
		if ($this->db->affectedRows() == 1) { return true; }
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
				//echo "<pre>";print_r($this->db->getLastQuery()->getQuery());die;
        return $query->getRow();
    }  //Funciton - Closed


	// return $query->getRow();

	

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
	

	//Get Logged In User data
	public function getLoggedInUserData($uid, $tablename) {
		$this->table = $tablename; //admin_users tbl
		$builder = $this->db->table($this->table);
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}
	
	// public function getFrontdeskUserData($tablename, $id) {
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->where('uid', $id);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) == 1) { return $result->getRow(); }
	// 	else { return false; }
	// }
	public function getFrontdeskUserData($uid){
		$builder = $this->db->table('blood_bank_users');
		$builder->where('uid', $uid);
		$result = $builder->get();
		if (count($result->getResultArray()) == 1) { return $result->getRow(); }
		else { return false; }
	}

	public function getLoggedInDonor_data($uid) { //Mater of investigation of use of the function 
		$builder = $this->db->table('blood_bank_users');
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

	
	// public function fetch_all_records($tablename) { //replace with commonModel function
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$this->paginate(10);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray())> 0) { return $result->getResult(); }
	// 	else { return false; }	
	// } //function - Closed

	public function fetch_all_records_by_active($tablename){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select("*");
		$builder->orderBy('id', 'DESC');
		$result = $builder->where('status', 'Active');
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }	
	} //function - Closed


	public function fetch_all_records_by_level_with_args($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select("*");
		$builder->orderBy('id', 'DESC');
		$result = $builder->where('level', $args);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return false;
		}
	}

	/*@params:$tablename, $args
	* @desc: Fetch table records with status 
	* @returns: 
	* @author: Neoarks Team
	* @date: Aug 18, 2023
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
			//echo "<pre>";print_r($result);die;
	}
	//Account Verification Query with Status Pagination


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

	/*@params: $tablename, $fld, $key, $args
	* @desc: 
	* @returns: Search records
	* @author: Neoarks Team
	* @date: Aug 18, 2023
	* @modify:
	*/
	public function search_records($tablename, $fld, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield = $fld;	//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;
		return $this->table($this->table)->like($this->srchfield, $this->srchKey)->where($this->whereArgs);
	}

	/*@params: $tablename, $fld, $key, $args
	* @desc: Search by one dynamic keyfield, name, mobile and emal
	* @returns: Search records
	* @author: Neoarks Team
	* @date: October 22nd, 2023
	* @modify:
	*/
	public function search_by_keyfld_arr($tablename, $fld_arr, $key, $args) {   
		$this->table = $tablename;	//table name
		$this->srchfield_arr = $fld_arr;//Search field name - eg: search by patient_name
		$this->srchKey = $key;		//Value of field value
		$this->whereArgs = $args;

		$this->qrystring = '';
		if(is_array($this->srchfield_arr)) { 
			$this->tot_srchfield = count($this->srchfield_arr); 
			$this->arrow_like = 'orLike(';
			for($this->i = 0; $this->i < $this->tot_srchfield; $this->i++) {
				$this->qrystring = $this->qrystring->$this->arrow_like ."'".$this->srchfield_arr[$this->i] ."'" .',' . $this->srchKey . ')';
			}
		}
		else { $this->tot_srchfield = 0; }
		
		//$this->table($this->table)->like($this->srchfield_arr, $this->srchKey)->where($this->whereArgs);
		if(isset($this->qrystring) && $this->qrystring !== '' ) {
			return $this->table($this->table)->like($this->srchfield_arr, $this->srchKey)
			->$this->qrystring->where($this->whereArgs);
		}
		else { 
			$this->table($this->table)->like($this->srchfield_arr, $this->srchKey)->where($this->whereArgs); 
		}
	} //function - Closed


	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_sum_by_args($tablename, $fld, $args) { 
	    $this->table = $tablename;
	    $builder = $this->db->table($this->table);
	    $builder->selectSum($fld);
	    $builder->where($args);
	    
    	//echo "SQL Query: " . $builder->getCompiledSelect(); // Print the SQL query
	    $query = $builder->get();
	    $row = $query->getRowArray(); 
	    return ($row && isset($row[$fld])) ? $row[$fld] : 0.00;
	} //function - Closed

	// public function fetch_sum_by_args($tablename,$fld, $args) { 
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->selectSum($fld);
	// 	$builder->orderBy('id', 'DESC'); //not working
	// 	$result = $builder->where($args);
	// 	$result = $builder->get();
		
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// } //Function - Closed



	public function get_max_val($tablename, $fld_name) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->selectMax($fld_name);
		
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else { return false; }
	} //Function - Closed


	public function fetch_rec_by_args($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else{ return false; }
	}

	
	public function fetch_rec_by_args_for_rev($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC');
		$result = $builder->getWhere($args);
		// Check if there are any rows returned
		if ($result->getResult()) {
			return $result->getResult(); // Return the result (array of objects)
		} else {
			return false; // No rows found, return false
		}
	}

		// return $this
		// ->table($this->table)
		// ->select('*')
		// ->orderBy('id', 'DESC') //->orderBy($order_format['column_name'], $order_format['order'])
		// ->where($args)
		// //->like($likeArgs)
		// ->paginate(10);
	//} //Function - Closed

	public function fetch_single_rec_by_args($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getRow(); }
		else{ return false; }
	} 

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


	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		return $this->table($this->table)
			->orderBy($order_format['column_name'],$order_format['order'])
			->where($args)
			->paginate(10);
	}
	

	public function test_filter_rec_by_args_with_pagination($tablename, $order_format, $args) {
		$this->table = $tablename;
		extract($order_format);
	
		// Create the base query
		$query = $this->table($this->table)
			->orderBy($order_format['column_name'], $order_format['order']);
	
		// Add conditions based on filter criteria in $args
		if (isset($args['status'])) {
			$query->where('status', $args['status']);
		}
	
		if (isset($args['discharged'])) {
			$query->where('discharged', $args['discharged']);
		}
	
		if (isset($args['deleted'])) {
			$query->where('deleted', $args['deleted']);
		}
		// Perform pagination
		return $query->paginate(10);
	}
	

	public function filter_rec_by_args_with_pagination_fr_apnmt($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		return $this->table($this->table)
            ->orderBy($order_format['column_name'],$order_format['order'])
            ->where($args)
            ->paginate(10);
	}
	
	/*@params: $tablename, $args, $likeArgs, $orlikeArgs
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @retuns: In Array format
	* @author: Neoarks Team
	* @date: 7th July, 2023
	* @modify:
	*/
	public function fetch_rec_by_args_filter_order_like($tablename, $args, $likeArgs, $order_format){ 
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

	/*@params: $tablename, $order_format, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	// public function filter_rec_by_args_with_status($tablename, $order_format, $args){ //Moved to: commonForAllModel
	// 	$this->table = $tablename;
	// 	extract($order_format);
	// 	$builder = $this->db->table($this->table);
	// 	$builder->orderBy($order_format['column_name'],$order_format['order']);
	// 	$builder->where($args);
	// 	$result = $builder->get();
		
	// 	if (count($result->getResultArray())> 0) { return $result->getResult(); }
	// 	else { return $result->getResult(); }
	// }


	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/	
	public function fetch_rec_by_args_with_date($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$result = $builder->where($args)
                  ->get();
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else { return $result->getResult(); }
	}

	/*@params: $insrtTbl, $insrtData, $updtTbl, $updtData, $updtArgs
	* @desc: Doctor registration - Inser records into`register_all_users` and update 
	* `doctor` doctor `login_acc` field as 1 (Dr. login account created)
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/

	public function Insert_with_update($insrtTbl, $insrtData, $updtTbl, $updtData, $updtArgs) {
		$this->insrtDataArr = $insrtData; 	//Insertion data arr
		$this->updtDataArr 	= $updtData; 	//update data arr
		$this->updtArgsArr 	= $updtArgs; 	//Update condition array

		$this->db->transStart(false); // Disable auto-commit
		$this->table = $insrtTbl; //'register_all_users' table
		
		$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $this->insrtDataArr);
		if($this->last_inst_id > 0) {
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

	public function updatedAt($tablename, $uid){
		$builder = $this->db->table($tablename);
		$builder->where('uid', $uid);
		$builder->update(['updated_at'=> date('Y-m-d h:i:s')]);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

	public function update_rec_by_args($tablename, $args, $data) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo"<pre>";print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
	}

	public function test_update_rec_by_args($tablename, $args, $data) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->update($data);
		//echo"<pre>";print_r($builder);die;
		if ($this->db->affectedRows() == 1) {  return true; }
		else { return false; }
}

	/*@params: $tablename, $args, $data, $inst_tbl, $tbldata
	* @desc: 
	* @use: Delete Doctor (update `register_all`, and `doctor` tables)
	* @author: Neoarks Team
	* @date: 19th August, 2023
	* @modify:
	*/
	public function update_into_two_tables($tbl1, $args1, $data_arr1, $tbl2, $args2, $data_arr2) { //used function update_rec_by_args()
		$this->table = $tbl1;
		$this->updt_data_arr = $data_arr1;
		$this->db->transOff();
		$this->db->transStart();
		if($this->update_rec_by_args($this->table, $args1, $data_arr1)) {
			$this->table = $tbl2; 
			if($this->update_rec_by_args($this->table, $args2, $data_arr2)) {
				$this->db->transCommit(); // Commit the transaction
				return true;
			}
			else {
				$this->db->transRollback(); // Rollback the transaction on exception
				return false; //3: update failed in table2
			}
		}
		else {
			$this->db->transRollback(); // Rollback the transaction on exception
			return false; //4: update failed in table1
		}
	} //function - Closed


	/*@params: $tablename, $args, $data, 
	* @description:Function HARD DELETE revisit patients
	* @author: Neoarks Team
	* @date: 5th August, 2023
	* @modify:
	*/
	public function delete_rec_by_args($tablename, $args) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->delete();
		if ($this->db->affectedRows() > 0) { return true; } 
		else { return false; }
	}
	
	/*@params: $adminUserId
	* @description:Function for get admin user session
	* @author: Neoarks Team
	* @date: 12th December, 2023
	* @modify:
	*/
	public function getAdminUserSessions($adminUserId){
		return $this->db->table('admin_users')
			->where('uid', $adminUserId)
			->get()
			->getResultArray();
	}

	public function updateSessionToken($table, $adminUserId){
		$sessionToken = bin2hex(random_bytes(16)); // Generate a new session token
		$this->db->table($table)
			->where('id', $adminUserId)
			->update(['session_token' => $sessionToken]);
	}

	
	/*@params: $tablename, $args, $data, $inst_tbl, $tbldata
	* @desc: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
	public function insert_into_multple_tables($tbl1, $data_arr1, $tbl2, $data_arr2) {
		$this->table = $tbl1; //`patients` table
		$this->db->transOff();
		$this->db->transStart();

		$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $data_arr1);
		if($this->last_inst_id !== false) {
			$data_arr2['pid'] = $this->last_inst_id;
			$this->table = $tbl2;
			$this->inst_rslt = $this->Insertdata($this->table, $data_arr2);
			if($this->inst_rslt === true) {
				$this->db->transCommit(); // Commit the transaction
				return true;
			}
			else { 
				$this->db->transRollback(); // Rollback the transaction on exception
				return false; //3: Revisited Patients info insertion failed
			}
		}
		else {
			$this->db->transRollback(); // Rollback the transaction on exception
			return false; //4: Failed to book doctor appointment. 
		}
	} //function - Closed


	/*@params: $tablename, $args, $data, $inst_tbl, $tbldata
	* @desc: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/

	// public function insert_into_multple_tables($tablename_arr, $data_arr) {
	// 	$this->tottbl = count($tablename_arr); 
	// 	$this->db->transStart(false); // Disable auto-commit
	// 	for($this->k = 0; $this->k < $this->tottbl; $this->k++ ) {
	// 		$this->table = $tablename_arr[$this->k]; //`booked_doctor_appointment` table
	// 		$this->insrt_data_arr = $data_arr[$this->k];

	// 		$inst_rslt = $this->Insertdata($this->table, $this->insrt_data_arr);
	// 		//$last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $this->insrt_data_arr);
	// 		if($inst_rslt === true) {
	// 			$this->r = $this->k+1;
	// 			if($this->r < $this->tottbl) {
	// 				continue;
	// 			}
	// 			else if($this->r == $this->tottbl) {
	// 				$this->db->transCommit(); // Commit the transaction
	// 				return true;
	// 			}
	// 			else { 
	// 				$this->db->transRollback(); // Rollback the transaction on exception
	// 				return false; //3: Revisited Patients info insertion failed
	// 			}
	// 		}
	// 		else {
	// 			$this->db->transRollback(); // Rollback the transaction on exception
	// 			return false; //4: Failed to book doctor appointment. 
	// 		}
	// 		//$this->r++;
	// 	} //for loop - Closed
	// } //function - Closed



	
	// public function update_appintment_inst_patient_info($tablename, $args, $data, $inst_tbl, $tbldata) {
	// 	$this->table = $tablename; //`booked_doctor_appointment` table
	// 	$this->args = $args;
	// 	$this->updt_data_arr = $data;
	// 	$this->new_patient_arr = $tbldata;
 		
	// 	$this->db->transStart(false); // Disable auto-commit
	// 	$builder = $this->db->table($this->table);
	// 	$builder->where($this->args);
	// 	$builder->update($this->updt_data_arr);
		
	// 	if ($this->db->affectedRows() == 1) {
	// 		$this->table = $inst_tbl; //`patients` table
	// 		$this->inst_result = $this->Insertdata($this->table, $this->new_patient_arr);
	// 		if($this->inst_result === true) { 
	// 			$this->db->transCommit(); // Commit the transaction
	// 			return true;
	// 		}
	// 		else { 
	// 			$this->db->transRollback(); // Rollback the transaction on exception
	// 			return 3; //3: Revisited Patients info insertion failed
	// 		}
	// 	} // `booked_doctor_appointment` update - Closed
	// 	else {
	// 		$this->db->transRollback(); // Rollback the transaction on exception
	// 		return 4; //4: Failed to book doctor appointment. 
	// 	}
	// } //function - Closed


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
	


	public function delete_records($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->delete();
		if ($this->db->affectedRows() == 1) { return true; }
		else{ return false; }
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

	/*@params: $tablename, $args
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
	public function fetch_rec_by_args_by_like($tablename, $args) {
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->like($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->get();
		if (count($result->getResultArray())> 0) { return $result->getResult(); }
		else { return $result->getResult(); }
	}

	/*@params: 
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 2nd August, 2023
	* @modify:
	*/
	public function fetch_doc_fee_rec_by_args_by_like($tablename, $args){
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

	/*@params: $tablename, $args
	* @desc: Search patient detail (based on entered every 3 characters)
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function fetch_rec_by_args_by_orlike($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->orlike($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) { return $result->getResult(); }
		else{ return false; }
	}


	public function fetch_rec_by_args_by_like_with_level($tablename, $args, $level){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->like($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->where('level', $level);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	public function fetch_rec_by_args_by_like_with_status($tablename, $args, $status){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->like($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->where('status', $status);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else {
			return $result->getResult();
		}
	}

	/*@params: $tablename, $email
	* @desc: 
	* @returns: 
	* @author: Neoarks Team
	* @date: 
	* @modify:
	*/
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


	public function filter_rec_by_args_with_level($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		$builder = $this->db->table($this->table);
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$result = $builder->where('level', $args);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	public function filter_rec_by_args_with_date($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		$builder = $this->db->table($this->table);
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$result = $builder->where('created_at', $args);
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	public function updatePassword($tablename, $npwd, $id){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid',$id);
		$builder->update(['password'=> $npwd]);
		if ($this->db->affectedRows() > 0) { return true; }
		else { return false; }
	}


	//Front Page Query Section Start
	public function get_image_by_args($tablename, $args, $limit) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where($args);
		$builder->orderBy('id', 'DESC');
		$result = $builder->limit($limit);
		$result = $builder->get();
		if (count($result->getResultArray()) > 0) {
			return $result->getResult();
		}else{
			return false;
		}
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

	public function getPatientRecordWithAppointment($id) {
		$db      = \Config\Database::connect();
		$builder = $db->query('select t1.*,t2.gender as doc_gender,t2.dr_specialization,t2.education from patients t1 left join doctor t2 on t1.doctor_id=t2.id   where t1.id='.$id.'');
		$result=$builder->getRow();
		return $result;
    } 

	public function getRevisitPatientRecordWithAppointment($pid) {
		$db      = \Config\Database::connect();
		$builder = $db->query('select t1.*,t2.gender as doc_gender,t2.dr_specialization,t2.education from revisit_patients t1 left join doctor t2 on t1.doctor_id=t2.id   where t1.id='.$pid.'');
		$result=$builder->getRow();
		// echo '<pre>';
		// print_r($result);die;
		return $result;
    } 

	public function get_image_by_args_as_order($tablename, $args, $limit){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->where($args);
		$builder->orderBy('id', 'ASC');
		$result = $builder->limit($limit);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return false;
		}
	} //Front Page Query Section End	

 	/**
     * 
     * @param 
     * @param 
     * @return 
     */
	/*@params: string $table, int $userId
	 * @desc: Increment the session version for a user.
	 * @returns: Returns output in the bool
	 * @author: Neoarks Team
	 * @date: 13th December, 2023
	 * @modify:
	 */
    public function incrementSessionVersion($table, $userId)
    {
        $builder = $this->db->table($table);
        $builder->set('uid', 'uid + 1', false);
        $builder->where('id', $userId);

        return $builder->update();
    }
} //Classs Closed
