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
use CodeIgniter\I18n\Time;
use \App\Models\CommonForAllModel; //Custom

class Medicine_model extends Model {

	public function __construct(){
    	parent::__construct();
    	$this->commonForAllModel = new CommonForAllModel(); //Called Custom model
  	}
  	
	protected $table      = 'medicines';
	protected $primaryKey = 'id';

	// protected $returnType     = 'array';
	// protected $useSoftDeletes = true;
	protected $allowedFields     = ['med_name','med_company','med_price','med_d_price', 'med_dis','expiry_date', 'batch_number', 'med_image','status'];
	protected $useTimestamps     = true;
	protected $createdField      = 'created_at';
	// protected $updatedField      = 'updated_at';
	// protected $deletedField      = 'deleted_at';
	protected $returnType        = 'array';

	protected $validationRules    = [
		'med_name'       => 'required',
		'med_company'    => 'required',
		'med_price'      => 'required'
	];
	// $model->setValidationRule($fieldName, $fieldRules);


	public function search($key) {   
		//$this->table = $tablename;
		return $this->table('medicines')->like('med_company',$key);
	}
	    
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

	// public function getLoggedInAccountantData($uid){ //Replaced with fetch_rec_by_uid in CommonForAllModel
	// 	$builder = $this->db->table('register_all_users');
	// 	$builder->where('uid', $uid);
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray()) == 1) { return $result->getRow(); }
	// 	else { return false; }
	// }

	//Get Logged In User data
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
	

    public function search_med_name($key, $args) {  
		//$this->table = $tablename; 
		return $this->table('medicines')->like('med_name',$key)->where($args);
		//return $this->table($this->table)->like('med_name',$key)->where($args);
    }
	public function search_department($key, $args) {  
		//$this->table = $tablename; 
		return $this->table('department')->like('department_name',$key)->where($args);
		//return $this->table($this->table)->like('med_name',$key)->where($args);
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
		if (count($result->getResultArray()) > 0) { 
			return $result->getResult(); 
		}
		else{ return false; }
	} //Function - Closed

	public function fetch_rec_by_args_with_status($tablename, $args) { 
		$this->table = $tablename;
		return $this
			->table($this->table)
			->select('*')
			->orderBy('id', 'DESC')
			->where($args)
			->paginate(10);
	}


	public function updatePassword($tablename,$npwd, $id){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where('uid',$id);
		$builder->update(['password'=> $npwd]);
		if ($this->db->affectedRows() > 0) { return true; }
		else { return false; }
	}
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

	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}
	
	public function delete_records($tablename, $args){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->where($args);
		$builder->delete();
		if ($this->db->affectedRows() == 1) { return true; }
		else{ return false; }
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

		public function update_rec_by_args($tablename, $args, $data){
			$this->table = $tablename;
			$builder = $this->db->table($this->table);
			$builder->where($args);
			$builder->update($data);
			//echo "<pre>"; print_r($builder);die;
			if ($this->db->affectedRows() == 1) {  return true; }
			else { return false; }
		}

		// public function fetch_all_records($tablename) { //replace with commonModel function
		// 	$this->table = $tablename;
		// 	$builder = $this->db->table($this->table);
		// 	$builder->select("*");
		// 	$builder->orderBy('id', 'DESC');
		// 	$result = $builder->get();
		// 	if (count($result->getResultArray())> 0) { return $result->getResult(); }
		// 	else { return false; }	
		// } //function - closed 
		
		public function fetch_all_records_by_active($tablename){
			$this->table = $tablename;
			$builder = $this->db->table($this->table);
			$builder->select("*");
			$builder->orderBy('id', 'DESC');
			$result = $builder->where('status', 'Active');
			$result = $builder->get();
			if (count($result->getResultArray()) > 0) { return $result->getResult();}
			else { return false; }	
		}


		public function fetch_rec_by_expiry_medicine($tablename, $args){
			$this->table = $tablename;
			return $this->table($this->table)
				->select('*')
				->where($args)
				->paginate(10);
		}

		/*@params: $tablename, $order_format
		* @desc: Filteration for: 
		* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
		* @author: Neoarks Team
		* @date: May 3, 2023
		* @modify:
		*/
	    public function filter_rec_by_args($tablename, $order_format){
			$this->table = $tablename;
			extract($order_format);
		    $builder = $this->db->table($this->table);
			$builder->orderBy($order_format['column_name'],$order_format['order']);
			$result = $builder->get();
			//echo "<pre>"; print_r($builder);die;
			if (count($result->getResultArray()) > 0) { return $result->getResult(); }
			else{ return $result->getResult(); }
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
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: May 3, 2023
	* @modify:
	*/	
	public function fetch_pay_charges_sum_by_args($tablename, $sqlqry, $args) { 
	    $this->table = $tablename;
	    $builder = $this->db->table($this->table);
	    
	    // Get sum of multiple columns
	    $builder->select($sqlqry);
	    $builder->where($args);

	    // Debugging: Print SQL query (remove in production)
	    //echo "SQL Query: " . $builder->getCompiledSelect(); exit();

	    $query = $builder->get();
	    $row = $query->getRowArray(); 
	    return $row ?? 0.00; // Return row or 0.00 if no data found
	} // Function - Closed


	// public function fetch_sum_by_args($tablename, $fld, $args) { 
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->selectSum($fld);
	// 	$builder->orderBy('id', 'DESC'); //not working
	// 	$result = $builder->where($args);
	// 	$result = $builder->get();
		
	// 	if (count($result->getResultArray()) > 0) { return $result->getResult(); }
	// 	else { return false; }
	// } //Function - Closed


	public function fetch_sum_by_args($tablename, $fld, $args) { 
	    $this->table = $tablename;
	    $builder = $this->db->table($this->table);
	    $builder->selectSum($fld);
	    $builder->where($args);
	    $query = $builder->get();
	    $row = $query->getRowArray(); 

	    return ($row && isset($row[$fld])) ? $row[$fld] : 0.00;
	} //function - Closed


	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		$this->table = $tablename;
		extract($order_format);
		return $this->table($this->table)
				->orderBy($order_format['column_name'],$order_format['order'])
				->where($args)
				->paginate(10);
	} //function - Closed

} //class - Closed