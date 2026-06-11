<?php 
namespace App\Controllers;


use CodeIgniter\Controller;	
use \App\Models\Login_model;
use \App\Models\Admin_Model;
use \App\Models\CommonForAllModel;
	
class Login extends BaseController {	

		public $loginModel;
		public $session;
		public $adminModel;
		public $commonForAllModel;
		
		public function __construct(){
			helper('form');
			$this->loginModel = new Login_model();
			$this->session   = session();
			$this->email = \Config\Services::email();
			$this->adminModel = new Admin_Model();
			$this->commonForAllModel = new CommonForAllModel();
		}

} //class - Closed