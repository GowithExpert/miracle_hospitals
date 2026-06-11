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

class BloodBankModel extends Model {

    /* @param: table name, unique token
	* @desc: Fetch Records based on passed token
	* @return: Result set or false if not existing
	* @use:
	* @author: Neoarks Team
	* @date: December 1st, 2023
	* @modify:
	*/
	// public function get_records_for_token($tablename, $token){
	// 	$this->table = $tablename;
	// 	$builder = $this->db->table($this->table);
	// 	$builder->select('*');
	// 	$builder->where('reset_pass_token', $token);
	// 	$result = $builder->get();
	// 	if ($this->db->affectedRows() == 1) { return $result->getRowArray(); }
	// 	else { return false; }
	// }

} //class - Closed
?>