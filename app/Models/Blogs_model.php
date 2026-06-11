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

class Blogs_model extends Model {
	protected $table      = 'news_blog';
    protected $primaryKey = 'id';

    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;
    protected $allowedFields     = ['blog_title','blog_content','blog_image','doctor_id', 'doctor_name','department_name','dr_specialization','status','created_date','created_month','created_year'];
    protected $useTimestamps     = true;
    protected $createdField      = 'created_date';
    protected $returnType        = 'array';

    // $model->setValidationRule($fieldName, $fieldRules);

    public function search($key) {   
    	return $this->table('patients')->like('patient_name',$key);
        // $builder = $this->table('patients');
        // $builder->like('patient_name',$keys);
        // // $query   = $builder->paginate(10);
        // return $query;
    }

    public function filter_rec_by_args($tablename, $order_format){
		extract($order_format);
		$builder = $this->db->table('patients');
		$builder->orderBy($order_format['column_name'],$order_format['order']);
		$result = $builder->get();
		if (count($result->getResultArray())> 0) {
			return $result->getResult();
		}else{
			return $result->getResult();
		}
	}

	public function fetch_rec_by_status_with_pagination($tablename, $args){
		return $this->table($tablename)
                ->select('*')
                ->where($args)
                ->paginate(10);
	}

	public function search_patients_name($key, $args)
    {   
		return $this->table('patients')->like('patient_name',$key)->where($args);
    }


	// public function fetch_all_records($tablename){ //replace with commonModel function
	// 	$builder = $this->db->table($tablename);
	// 	$builder->select("*");
	// 	$builder->orderBy('id', 'DESC');
	// 	$result = $builder->get();
	// 	if (count($result->getResultArray())> 0) { return $result->getResult();
	// 	}
	// 	else { return false; }	
	// }

	// public function fetch_rec_by_args_with_status($tablename, $args){
	// 	$this->table = $tablename;
	//     return $this
    //         ->table($this->table)
    //         ->select('*')
    //         ->where($args)
    //         ->paginate(8);
	// }
	public function fetch_rec_by_args_with_status($tablename, $args){
		$this->table = $tablename;
		 return $this->table($this->table)
				->select('*')
				->where($args)
				->orderBy('id', 'DESC')
				->paginate(10);
		}
	
	public function filter_rec_by_args_with_pagination($tablename, $order_format, $args){
		extract($order_format);
		return $this->table($tablename)
                ->orderBy($order_format['column_name'],$order_format['order'])
                ->where($args)
                ->paginate(10);
	}

} //class - Closed
?>