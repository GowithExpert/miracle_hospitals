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

    class PatientAutoModel extends Model
	{
		protected $table      = 'patients_login';
	    protected $primaryKey = 'id';

	    // protected $returnType     = 'array';
	    // protected $useSoftDeletes = true;
	    protected $allowedFields     = ['uid','username','email','password', 'mobile','level','status'];
	    protected $useTimestamps     = true;
	    protected $createdField      = 'created_date';
	    protected $updatedField      = 'updated_at';
	    protected $deletedField      = 'deleted_at';
	    protected $returnType        = 'array';

	    // $model->setValidationRule($fieldName, $fieldRules);

	 public function fetch_rec_by_args_with_status($tablename, $args){
	    return $this
            ->table($tablename)
            ->select('*')
            ->where($args)
            ->paginate(10);
	}

	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		extract($order_format);
		//echo "<pre>";print_r($order_format);die;
		return $this->table($tablename)
                ->orderBy($order_format['column_name'],$order_format['order'])
                ->where($args)
                ->paginate(10);
	}
		
}

?>