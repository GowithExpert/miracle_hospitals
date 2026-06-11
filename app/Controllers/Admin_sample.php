<?php namespace App\Controllers;

use \App\Models\Admin_Model;
use \App\Models\AutoModel;
use \App\Models\Medicine_model;
use \App\Models\AccountAutoModel;
use \App\Models\Blogs_model;

use \App\Models\DoctorModel; //Customized 
use \App\Models\CommonForAllModel; //Custom

class Admin extends BaseController {
	public $session;
	public $email;
	public $adminModel;
	public $doctorModel;
	public $AutoModel;
	public $patient_model;
	
	public $pager;
	public $medicine_model;
	public $accountant_model;
	public $blogmodel;
	public $commonForAllModel;

	
	public function __construct(){
		$throttler = \Config\Services::throttler();
		helper(['form', 'Patient', 'array', 'text']);
		$this->session     = \Config\Services::session();
		$this->doctorModel = new DoctorModel();  //Customized  
		$this->email       = \Config\Services::email();

		$this->adminModel  = new Admin_Model();
		$this->medicine_model   = new Medicine_model();
		$this->patient_model    = new AutoModel();
		$this->accountant_model = new AccountAutoModel();
		$this->blogmodel   = new Blogs_model();
		
		$this->pager       = \Config\Services::pager();
		$this->commonForAllModel = new CommonForAllModel();
	} //constructor - Closed


   /**************** Import Data: START **********************/


   /* @param: Import Ward csv data into table
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Raj
	* @copyrights: Self
	*/
	public function import_wards() {
	    $this->csv_file = $this->request->getFile('csv_file');

	    if (!$this->csv_file->isValid()) {
	        return redirect()->back()->with('error', 'Invalid file');
	    }

	    if ($this->csv_file->getExtension() !== 'csv') {
	        return redirect()->back()->with('error', 'Only CSV files are allowed');
	    }

	    // Move uploaded CSV to writable/uploads
	    $this->new_name_csvfile = $this->csv_file->getRandomName();
	    $this->csv_file->move(WRITEPATH . 'uploads', $this->new_name_csvfile);
	    $this->csv_file_path = WRITEPATH . 'uploads/' . $this->new_name_csvfile;

	    // Call model method
	    //$wardModel = new \App\Models\WardModel();
	    $this->result = $this->adminModel->import_wards('hospital_wards', $this->csv_file_path);

	    if ($this->result) {
	        return redirect()->back()->with('success', 'CSV data imported successfully!');
	    } 
	    else {
	        return redirect()->back()->with('error', 'CSV import failed.');
	    }
	} //function - Closed

    //**************** Import Data: END**********************//
	

	

   /* @param: Send email
	* @desc: Send email 
	* @return: Result set or false if not existing
	* @use:Admin, Doctor, 
	* @author: Raj
	* @date: March 11th, 2025
	* @modify:
	*/
	public function send_an_email($to_email, $subject, $message, $attachment) {
		$this->email->setTo($to_email);
		$this->email->setFrom('ADMIN_EMAIL', 'Info');
		$this->email->setSubject($subject);
		$this->email->setMessage($message);
		$filepath = 'public/assets/images/logo3.png';
		$this->email->attach($attachment);
		if ($this->email->send()) { return true; }
		else { return false; }
	} //Function - Closed

   
} //Class - Closed 