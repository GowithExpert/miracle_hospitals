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

class FrontDeskModel extends Model {

    /*@params: $tablename, $args, $slot_start, $slot_end
	* @desc: Model function for fetching data form datewise 
	* @returns:
	* @author: Neoarks Team
	* @date: 6th Dec, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args_datewise($tablename, $args, $apmt_st_dt, $apmt_end_dt) {
		$this->table = $tablename;
        $this->apmt_start_dt = $apmt_st_dt;
        $this->apmt_end_dt = $apmt_end_dt;
        if(( $this->apmt_start_dt != '') && ($this->apmt_end_dt != '')) {
            $builder = $this->db->table($this->table);
            $builder->select('*');
            
            $builder->where($args)
                    ->where('booking_date >=', $this->apmt_start_dt)  //Booking start date
                    ->where('booking_date <=', $this->apmt_end_dt); //Booking end date
        }
        else {
            $builder = $this->db->table($this->table);
            $builder->select('*');
            $builder->where($args);
        }
		$result = $builder->get();
        //echo "<pre>"; print_r( $builder);die;
		return ($result->getNumRows() > 0) ? $result->getResult() : false; //ternary operator based condition
	}

} //class - Closed