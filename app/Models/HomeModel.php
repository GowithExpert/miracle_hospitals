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

class HomeModel extends Model {

	public function __construct(){
    	parent::__construct();
    	$this->commonForAllModel = new CommonForAllModel(); //Called Custom model
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

		$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id($this->table, $this->paid_amt_fee_arr);
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

} //Function - Closed