<?php namespace App\models;


use CodeIgniter\Model;
use \App\Models\CommonForAllModel; //Custom

class Login_model extends Model {
	
	public function __construct(){
    	parent::__construct();
    	$this->commonForAllModel = new CommonForAllModel(); //Called Custom model
  	}
  	


	/*@params: $tablename, $args
	* @desc: 
	* @returns: Returns output in the array & object format (eg. Array[0]->['id'] )
	* @author: Neoarks Team
	* @date: August 21, 2023
	* @modify:
	*/	
	public function fetch_rec_by_args($tablename, $args) { 
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$builder->select('*');
		$builder->orderBy('id', 'DESC'); //not working
		$result = $builder->where($args);
		$result = $builder->get();
		
		if ($this->db->affectedRows() > 0) { 
			return $result->getResult(); 
		}
		else{ return false; }

	} //Function - Closed
	public function Insertdata($tablename,$data){
		$this->table = $tablename;
		$builder = $this->db->table($this->table);
		$res = $builder->insert($data);
		if ($this->db->affectedRows() == 1) { return true; }
		else { return false; }
	}

} //Class - Closed