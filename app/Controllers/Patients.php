<?php namespace App\Controllers;

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

use \App\Models\Admin_Model;
use \App\Models\Login_model;
use \App\Models\Medicine_model;
use \App\Models\AutoModel;
use \App\Models\DoctorModel; //Customized 
//use \App\Models\CommonModel; //Custom
use \App\Models\CommonForAllModel; //Customized 

class Patients extends BaseController
{
	protected $updt_data_arr;
	public $adminModel;
	public $session;
	//public $commonModel; //Custom
	public $patient_model;
	public $loginModel;
	public $commonForAllModel; //Custom
	public $doctorModel;

	public function __construct(){
		helper(['form','Patient','text']);
		$this->adminModel = new Admin_Model();
		$this->email = \Config\Services::email();
		$this->session    = \Config\Services::session();
		//$this->commonModel = new CommonModel(); //Custom
		$this->medicine_model   = new Medicine_model();
		$this->loginModel = new Login_model();
		$this->patient_model   = new AutoModel();
		$this->doctorModel = new DoctorModel();  //Custom 
		$this->commonForAllModel = new CommonForAllModel(); //Custom
	}


	/* @param: 
     * @description: 
     * @Remark: 
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 
     */
	public function index() {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		}
		else {
			$uniid = session()->get('patient_session_uid');
			// $data['loggedin_usr'] = $this->loginModel->getLoggedInPatientsData($uniid);
			$data['loggedin_usr'] = $this->patient_model->getLoggedInPatientsData($uniid);
			
			
			//Checking Patients Email is Given or Not to Access Patients Admit details 	
			//$args = [ 'patient_email'  => $data['loggedin_usr']->email];
			$args = [];
			if (is_object($data['loggedin_usr']) && property_exists($data['loggedin_usr'], 'email')) {
			$args['email'] = $data['loggedin_usr']->email;
			}
			
			//Checking Patients Email is Given or Not to Access Patients Admit details 
			$check_account = $this->patient_model->fetch_rec_by_args('patients_login', $args);
			if ($check_account) { //Store Patient ID In Session
				
				$patient_session = $check_account[0]->id;
				$this->patient_session_id = session()->get('patient_session_id');
				if(!isset($this->patient_session_id) || $this->patient_session_id == '') {
					return redirect()->to(base_url()."/Patients_login/login");
				}
				//Fetch Doctor Name
				$args = ['id'  => (int) $this->patient_session_id ];

				
				//$patient_details = $this->patient_model->fetch_rec_by_args('patients', $args);
				$patient_details = $this->patient_model->fetch_rec_by_args('patients_login', $args);
				
				$data['name'][0] = new \stdClass; //stdClass object
				$data['userdata'] = new \stdClass; //stdClass object
				$pic[0] = new \stdClass; //stdClass object

				if(isset($patient_details[0]->username)) {
					$data['name'][0]->username = $patient_details[0]->username;
				}
                if(isset($patient_details[0]->username)) {
                	$data['userdata']->username = $patient_details[0]->username;    //Used in view	
                }
				if(isset($check_account[0]->profile_pic)) {
					$pic[0]->profile_pic = $check_account[0]->profile_pic;
				}
                
				if ($patient_details) {
					$args = [ 'id'  => (int) $this->patient_session_id ];
					$data['doctor_details'] = $this->patient_model->fetch_rec_by_args('doctor', $args);
				}

				//Dashboard Query Start 
				return view('Patients/dashboard', $data);
			}
			else{
				$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
				return view('Patients/book_appointment_dash', $data);	
			}
		}
	} //function - Closed


	//***********  Generate Serial For Appointment -START ***************/

	/* @param: 
     * @description: 
     * @Remark: 
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 
     */
	public function generate_puid($new_sn) {
		if (!(session()->has('patient_session_uid'))) {//if-START
			return redirect()->to(base_url()."/Patients_login/Patients_login/login_accoun");
		}//if-CLOSED
		$this->new_serial = $new_sn; //Serial Number
		$this->current_date = date('Y-m-d');
		
		$this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
		$this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
		return $this->puid;
	}

	/*@params: $tbl, $fld (table field for where condition)
	* @desc: Generate patient visit number or highest sereal on particular day or date
	* @author: Neoarks Team
	* @date: July 20th, 2023
	* @modify:
	*/
	public function generate_serial($tbl, $fld) {
		$this->table = $tbl; 
		$this->where_field = $fld;
		$this->new_serial = 0; //Just for addressing notices
		$this->highest_serial = $this->get_highest_today_serial($this->table, $this->where_field);
		
		if(!isset($this->highest_serial->serial) || $this->highest_serial == null) { 
			if(isset($this->highest_serial->patient_id)) { 
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => $this->highest_serial->patient_id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			}
			else if(isset($this->highest_serial->id)) {
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => $this->highest_serial->id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			}
			else if(!isset($this->highest_serial->patient_id) || $this->highest_serial->patient_id == null){
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			}
			else if(!isset($this->highest_serial->id) || $this->highest_serial->id == null){ 
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
			}
			else {
				$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url().'/patients/all_appointments');
			}
		} 
		else if(isset($this->highest_serial->serial)) { 
			if(isset($this->highest_serial->patient_id)) { 
				$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->patient_id,				
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			}
			else if(isset($this->highest_serial->id)) {
				$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->id,				
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			}
			else {
				$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url().'/Patients/all_appointments');
			}
		}
		else {
			//return false;
			$this->session->setTempdata('error', 'Unexpected serial number ', 3);
			return redirect()->to(base_url().'/Patients/all_appointments');
		}
	} //Function - Closed

	/*@params: $tbl, $fld (table field for where condition)
	* @desc: Get already stored highest sereal on particular day or date for Generating patient visit number 
	* @author: Neoarks Team
	* @date: July 20, 2023
	* @modify:
	*/
	public function get_highest_today_serial($tbl, $fld) { 
		$this->tbl_name = $tbl; 
		$this->where_field = $fld;
		
		$args = [ $this->where_field  => date('Y-m-d') ];
		$this->highest_pat_serial = $this->patient_model->today_highest_serial($this->tbl_name, $args);
		return $this->highest_pat_serial;
	}
	//********************************  Generate Serial For Appointment ***********************/
	
	public function change_appointment_status($id, $status) {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		}
		else{
			$this->patient_session_id = session()->get('patient_session_id');
			if(!isset($this->patient_session_id) && $this->patient_session_id == '') {
				return redirect()->to(base_url()."/Patients_login/login");
			}
			$args = [ 'id'  => $id ]; //appointment ID
			$current_time  = time();
			$datetime      = date('Y-m-d h:i:s').$current_time;
			$data = [ 'status'  => $status, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
					'updated_by'  => (int) $this->patient_session_id,
					'updated_at'  => $datetime
					]; 
			$status = $this->patient_model->update_rec_by_args('booked_doctor_appointment', $args, $data);
			if ($status) {
				$this->session->setTempdata('success', 'Congratulation ! Patients discharge successfully !', 3);
			}
			else{
				$this->session->setTempdata('error', 'Failed.! unable to sent', 3);
			}
			return redirect()->to(base_url().'Patients/all_appointments/'.$this->patient_session_id);
		} // else loop  - Closed
    }

	public function change_all_appointment_status($id, $status) {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."Patient_login/doctor_login");
		}
		else { 
			$this->patient_session_id = session()->get('patient_session_id');
			if(!isset($this->patient_session_id) && $this->patient_session_id == '') {
				return redirect()->to(base_url()."Patient_login/doctor_login");
			}
			$args = [ 'id'  => $id ];
			$data = [ 'status'  => $status ];
			$status = $this->patient_model->update_rec_by_args('booked_doctor_appointment', $args, $data);
			if ($status) {
				$this->session->setTempdata('success', 'Congratulation ! Patients discharge successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Failed.! unable to sent', 3);
			}
			return redirect()->to(base_url().'Patients/all_appointments');
		} //else loop - Closed

	}// Function - Closed


	// public function permanent_del_all_apmnt($id){
	// 	$this->patient_uid = session()->get('patient_session_uid');
	// 	if (!isset($this->patient_uid) || $this->patient_uid == '') {
	// 		$this->session->setTempdata('error', 'Patient UID is missing !', 3);
	// 		return redirect()->to(base_url()."/Patients_login/login");
	// 	}
	// 	$args = [ 'id'  =>  $id ];
	// 	$data = [
	// 		'is_del' => 1,
	// 		'status' => 1, //0: Non deleted, 1: Deleted
	// 		'updated_at' => date('Y-d-m h:i:s'),
	// 		'updated_by' => $this->patient_uid
	// 	];
	// 	$status = $this->patient_model->update_rec_by_args('booked_doctor_appointment',  $args, $data);

	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
	// 	} else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
	// 	}
	// 	return redirect()->to(base_url() . 'Patients/all_appointments');
	// }

	// public function permanent_del_all_apmnt($id){
	// 	$this->patient_uid = session()->get('patient_session_uid');
	// 	if (!isset($this->patient_uid) || $this->patient_uid == '') {
	// 		$this->session->setTempdata('error', 'Patient UID is missing !', 3);
	// 		return redirect()->to(base_url() . "/Patients_login/login");
	// 	}
	// 	$args = [
	// 		'pid'  =>  $id,
	// 	];
	// 	$data = [
	// 		'is_del' => 1,
	// 		'status' => 1, //0: Non deleted, 1: Deleted
	// 		'updated_at' => date('Y-d-m h:i:s'),
	// 		'updated_by' => $this->patient_uid
	// 	];
    //     $status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
	// 	} 
	// 	else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
	// 	}
	// 	return redirect()->to(base_url() . 'Patients/all_appointments');
	// }


	public function permanent_del_all_apmnt($id){
		$this->patient_uid = session()->get('patient_session_uid');
		if (!isset($this->patient_uid) || $this->patient_uid == '') {
			$this->session->setTempdata('error', 'Patient UID is missing !', 3);
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		$args = [ 'id'  =>  $id, ];
		$data = [
			'is_del' => 1,
			'status' => 7, //2: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->patient_uid
		];
		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Patients/all_appointments');
	}

	public function del_pay_receipt($id){
		$this->patient_uid = session()->get('patient_session_uid');
		if (!isset($this->patient_uid) || $this->patient_uid == '') {
			$this->session->setTempdata('error', 'Patient UID is missing !', 3);
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		$args = [ 'id'  =>  $id, ];
		$data = [
			'is_del' => 1,
			//'status' => 7, //2: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->patient_uid
		];
		$status = $this->adminModel->update_rec_by_args('patients_pay_charges',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Record has deleted successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Patients/view_receipt');
	}

	
	public function getset_dr_slots() {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."Patients/patient_login");
		}
		else {
			$doctor_id = session()->get('doctor_session_id'); //Get doctor ID from session
			/*if(isset($dt) && $dt != '') {
				$this->selected_date = $dt;
			} else {  */
				$this->selected_date = date("Y-m-d"); 
			//}
			if(is_array($doctor_id) && isset($doctor_id)) {
				$this->args = [
					'doctor_id' => $doctor_id,  //Logged-in Dr. ID
					'appointment_date' => $this->selected_date
				];
			}
			else {
				$this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
				return redirect()->to(base_url()."Patients/patient_login");
			}
			$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
			if($this->data['dr_slots'] === false ) {
				$this->data['dr_slots'] = array(array());
			}
			return view('Patients/doctor_availability', $this->data); 
		} //else - Closed
	} //Funciton - Closed


	public function filter_today_appointments($filter) {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."Patients_login/login");
		}
		if ($filter == 'new_patient') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}else if ($filter == 'old_patient') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}else{
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		$this->patient_session_id = session()->get('patient_session_id');
		if(!isset($this->patient_session_id) || $this->patient_session_id == '') {
			return redirect()->to(base_url()."Patients/all_appointments");
		}
		$args = [
			'pid'  => (int) $this->patient_session_id,
			//'status'        => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
			'booking_date' => date('Y-m-d')
		];
		
		$data = [
			//'today_appointments' => $this->patient_model->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
			'today_appointments' => $this->commonForAllModel->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
		];
		if($data['today_appointments'] == false) {
			$this->session->setTempdata('error', 'No record found!', 3);
			return redirect()->to(base_url()."Patients/today_appointments");
		}
		return view('Patients/today_appointments', $data);
	}

	public function filter_all_appointments($filter){
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		if ($filter == 'new_appointment') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_appointment') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		} else {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
			$this->patient_session_id = session()->get('patient_session_id');
			$args = [
				'pid'  => (int) $this->patient_session_id, //Logged-in Patient ID
				'is_del' => 0
			];
			$orArgs = [
				'ref_by'  => (int) $this->patient_session_id,  //Logged-in Patient ID
			];
	
			$data = [
					'all_appointments' => $this->patient_model->fetch_rec_by_orargs_arr('booked_doctor_appointment', $args, $orArgs),
					'pager'     => $this->patient_model->pager
					];
		return view('Patients/all_appointments', $data);
	}

	
	public function search_all_appointments(){
		if (!(session()->has('patient_session_uid'))) {  
			return redirect()->to(base_url()."/Patients_login/login");
		}
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		$this->patient_session_id = session()->get('patient_session_id');
		$args = [
			'pid' => (int) $this->patient_session_id, // Logged-in Patient ID
			'is_del' => 0
			// 'status' => 'Discharged'
		];
	    $orArgs = [
			'ref_by' => (int) $this->patient_session_id, // Logged-in Patient ID
		];
	
		if ($keyword) {  
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}
	
		$data = [
			'all_appointments' => $this->commonForAllModel->fetch_rec_by_orargs_arr('booked_doctor_appointment', $args, $orArgs),
			'pager' => $this->commonForAllModel->pager
		];
	
		return view('Patients/all_appointments', $data);
	}
	

	public function all_appointments() { 
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		else{
			$this->patient_session_id = session()->get('patient_session_id');
			$args = [
				'pid'  => (int) $this->patient_session_id, //Logged-in Patient ID
				'is_del' => 0
			];
			$orArgs = [
				'ref_by'  => (int) $this->patient_session_id,  //Logged-in Patient ID
			];
	
			$data = [
					'all_appointments' => $this->patient_model->fetch_rec_by_orargs_arr('booked_doctor_appointment', $args, $orArgs),
					'pager'     => $this->patient_model->pager
					];
		return view('Patients/all_appointments', $data);
		}
	}


	// public function patients_search_lookup() {
	// 	$this->srchkey = $this->request->getPost('srchkey');
	// 	$this->srchkey_args = [
	// 		'patient_phone' =>  $this->srchkey,
	// 		'puid'  		=>  $this->srchkey,
	// 		'patient_email' =>  $this->srchkey,
	// 		'patient_name'  =>  $this->srchkey
	// 	];
		
	// 	$patient_details = $this->patient_model->fetch_rec_by_args_by_orlike('patients', $this->srchkey_args);
	// 	$result_arr = array(
	// 		"status" => false,
	// 		"code" => 200,
	// 		'data'	=> $patient_details,
	// 		"message" => 'Patient records'
	// 	); 
	// 	return json_encode($result_arr);	
	// }

	public function patients_search_lookup() {
		$this->srchkey = $this->request->getPost('srchkey');
		$this->srchkey_args = [
			'patient_phone' =>  $this->srchkey,
			'puid'  		=>  $this->srchkey,
			'patient_email' =>  $this->srchkey,
			'patient_name'  =>  $this->srchkey
		];
		
		$patient_details = $this->patient_model->fetch_rec_by_args_by_orlike('patients', $this->srchkey_args);
		if(!$patient_details) {
			$result_arr = array(
				"status" => false,
				"code" => 200,
				"error"	=> true,
				'data'	=> $patient_details,
				"message" => 'No patient record found'
			); 
			return json_encode($result_arr);	
		}
		else {
			$result_arr = array(
				"status" => true,
				"code" => 200,
				"error"	=> false,
				'data'	=> $patient_details,
				"message" => 'Patient records found'
			); 
			return json_encode($result_arr);	
		}
	} //function - Closed


   /* @params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid 
	* (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` 
	* tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating 
	* bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function clear_final_dues($patient_id, $pid, $apmtid, $puid) {//pid is patient_login tbl id
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		$this->patient_uid = session()->get('patient_session_uid');

		$this->patient_id = (int)$patient_id; //patients tbl id
		$this->pid = (int)$pid; 
		$this->apmt_id = (int)$apmtid;
		$this->puid = $puid; 
		

		$args = [ 'id'  => $this->patient_id ];
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'total_hospital_expenses'      => 'required',
				'total_paid_amount'    => 'required',
				'dues_amount'         => 'required',
				//'other_cost'        => 'required'

			];
			if ($this->validate($rules)) {	
				$this->payable = $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING);
				if($this->payable == '0.00' || $this->payable == '0') {
					$this->session->setTempdata('error', 'Payable amount is zero', 3);
					return redirect()->to(base_url() . 'Patients/show_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
				}
				$this->user_data_arr = [
					'total_hospital_expenses'	=> $this->request->getVar('total_hospital_expenses', FILTER_SANITIZE_STRING),
					'total_patient_paid_amount' => $this->request->getVar('total_paid_amount', FILTER_SANITIZE_STRING),
					'paid_amount' 	=> $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING),
					'payment_note'	=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'           =>  $this->pid,
					'puid'          =>  $this->puid,
					'patients_id'   =>  $this->patient_id,
					'apmt_id'    	=>  $this->apmt_id,
					'pay_date'      =>  date('Y-m-d'),
					'created_at'	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->patient_uid,
				]; 
				//$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Patients/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Patients/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { $data['validation'] = $this->validator; }
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Patients/discharge_appointment_pat', $data);
	} //function - Closed

	//************************** SLOTS SECTION START ******************************/

	/*@params: Show Doctor availability slots - //Ref Doctor/doctors_available_slots($puid)
	* @desc: Available slots may be booked/take appointments by patients (and even Doctors, Frontdesk admin and Administrator as well)
	* @author: Neoarks Team
	* @date: June 28th, 2023
	* @modify:
	*/

	public function doctors_available_slots($pid, $seldate = '', $seldocid = '') { 
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		}
		else{
			$this->patient_session_id = session()->get('patient_session_id');
			if(!isset($this->patient_session_id) && $this->patient_session_id == '') {
				return redirect()->to(base_url()."/Patients_login/login");
			}
			$this->data['patient_id'] = (int) $pid; //0: for non-logged-in Patient else Patient ID 

			$this->args_dr = [
				'login_acc' => 1,  //1: Dr. Login account is Available  0: Dr.Login NOT available
				'status' => 'Active' // Active ie Admin Verified
			];
			$this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
			if($seldate == '') { $appointmntdate=date('Y-m-d'); }
			else { $appointmntdate=$seldate; }
			
			if(empty($seldocid) || $seldocid == '') {
				$this->args = [
					#'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
					'appointment_date' => $appointmntdate //Current date
				];
			}
			else {
				$this->args = [
					#'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
					'appointment_date' => $appointmntdate, //Current date
					'doctor_id' => $seldocid, //Current date
				];
			}
			$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots',$this->args);
			if($this->data['dr_slots'] == false ) {
				$this->session->setTempdata('error', 'Sorry, No doctor slot is available!', 3);
				return redirect()->to(base_url().'Patients/index');
			}
			return view('Patients/show_dr_available_slots', $this->data);
		}
	}// Function - Closed


	/*@params: Show Doctor availability slots based on selected Doctor and Date
	* @desc: Available slots may be booked/take appointments by patients (and even Doctors, Frontdesk admin and Administrator as well)
	* @author: Neoarks Team
	* @date: May 30th, 2023
	* @modify:
	*/
	//public function show_dr_available_slots_new() { //get slots based on selected Doctor and Date
		// public function available_selected_doctor_slots() { //get slots based on selected Doctor and Date
		// 	$this->tot_dr = 0; //Just for addressing notices
		// 	$this->data['doctors'] = array(); //Just for addressing notices
			
		// 	$this->args = [
		// 		'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
		// 		'doctor_id' => $this->request->getGet('dr_id'),
		// 		'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
		// 	];
		// 	//echo "<pre>"; print_r($this->args);die;
		// 	$this->data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
		// 	// $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);	
		// 	$this->data['dr_slots'] = $this->patient_model->fetch_rec_by_args('doctor_slots', $this->args);	
		// 	if($this->data['dr_slots'] === false ) { //Need to apply it
				
		// 	}
		// 	//echo "<pre>"; var_dump($this->data);die;
		// 	return view('Patients/show_dr_available_slots', $this->data);
		// }// Function - Closed
		
		public function available_selected_doctor_slots() { //get slots based on selected Doctor and Date
			$this->tot_dr = 0; //Just for addressing notices
			$this->data['doctors'] = array(); //Just for addressing notices
			
			$this->args = [
				'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
				'doctor_id' => $this->request->getGet('dr_id'),
				'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
			];
			$this->data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');	
			$this->data['dr_slots'] = $this->patient_model->fetch_rec_by_args('doctor_slots', $this->args);	
			if($this->data['dr_slots'] === false ) { 
				$this->session->setTempdata('error', 'Sorry, No doctor slot is available!', 3);
				return redirect()->to(base_url().'Patients/index');
			}
			
				 return view('Patients/show_dr_available_slots', $this->data);
		}// Function - Closed


   /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
		
	public function change_Patients_password() {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		} 

		$data = [];
		$data['loggedin_usr'] = $this->patient_model->getLoggedInUserData(session()->get('patient_session_uid'), 'patients_login');
		if ($this->request->getMethod() == 'get') {
			return view('Patients/change_patients_password', $data);
		}
		else if ($this->request->getMethod() == 'post') {
			$rules = [
				'old_password' => 'required',
				// 'new_password' => 'required|min_length[6]|max_length[20]',
				'new_password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'New password is  mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'confirm_password' => 'required|matches[new_password]',
			];
			if ($this->validate($rules)) {
				$opwd = $this->request->getVar('old_password');
				$npwd = password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT);
				if($data['loggedin_usr'] === false) {
					$this->session->setTempdata('error', 'Sorry! User record is not found!', 3);
				}
				else if (!isset($data['loggedin_usr']->password) || $data['loggedin_usr']->password == '') {
					$this->session->setTempdata('error', 'password index is not in result set!', 3);
				}
				else if(password_verify($opwd, $data['loggedin_usr']->password)) {
					$status = $this->patient_model->updatePassword('patients_login', $npwd, session()->get('patient_session_uid'));
					if ($status) {
						$this->session->setTempdata('success', 'Congratulation ! Password Updated Successfully!', 3);
						return redirect()->to(current_url());
					} else {
						$this->session->setTempdata('error', 'Sorry Unable to Update Password Try Again!', 3);
						return redirect()->to(current_url());
					}
				} 
				else { 
					$this->session->setTempdata('error', 'Incorrect login email or password', 3); 
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Oops!, Validation failed of mandatory fields', 3);
				$data['validation'] = $this->validator;
			}
		}
		else {
			$this->session->setTempdata('error', 'Oops!, Unexpected request method', 3);
		}
		return view('Patients/change_patients_password', $data);
	}//Function- Closed



   /* @params: Require, "Slot", "Doctor ID", "Doctor Name" (optional) and patient desired appointment "Date" 
	* @desc: Pick the Doctor availabile slot for getting doctor appointment by the Patient
	* @use: By patient for booking Doctor available slots
	* @author: Neoarks Team
	* @date: June 15th,  2023
	* @modify:
	*/ 

	public function pick_slots() {
		$this->data['dr_name'] = $this->request->getGet('dr_name');
		$this->data['dr_id'] = $this->request->getGet('dr_id');		
		$this->data['dt'] = $this->request->getGet('dt');
		$this->data['pick_slt'] = $this->request->getGet('pick_slt');
		$this->data['slot_id'] = $this->request->getGet('slot_id');

		if(!isset($this->data['dr_id']) || $this->data['dr_id'] == '') {
			$this->session->setTempdata('error', 'Doctor ID is missing.!', 3);
			$this->data['dr_slots'] = array(); //Just for addressing notice about missing $dr_slots array
			$this->data['doctors'] = array();  //Just for addressing notice about missing $doctors array
			return view('Patients/show_dr_available_slots', $this->data);
		}
		else { //Get doctors & Departments for appointment page - //Ref doctor_appointment($id)

			$this->pid = session()->get('patient_session_id');
			if(isset($this->pid) && $this->pid != '') { 
				$this->args_neo = ['id' 	=> $this->pid ];
				$this->data['patient_dtl'] = $this->loginModel->fetch_rec_by_args('patients_login', $this->args_neo);
			}
			else {
				$this->data['patient_dtl'][0]->username = "";
				$this->data['patient_dtl'][0]->first_name = "";
				$this->data['patient_dtl'][0]->last_name = "";
				$this->data['patient_dtl'][0]->age = "";
				$this->data['patient_dtl'][0]->gender = "";
				$this->data['patient_dtl'][0]->country_code = "";
				$this->data['patient_dtl'][0]->mobile = "";
				$this->data['patient_dtl'][0]->email = "";
				//$this->data['patient_dtl'][0]->username = "";
			}
			$this->args = [ 'ref_id' => $this->data['dr_id'] ]; //Doctor ID
			$this->data['doctors'] = $this->patient_model->fetch_rec_by_args('doctor', $this->args);
			$this->data['department'] = $this->commonForAllModel->fetch_all_records('department');
			//echo "<pre>";print_r($this->data);die;
			return view('Patients/doctor_appointment', $this->data);
		} //else loop - Closed
	} //Funciton - Closed
	

	// public function book_appointment() {
	// 	if (!(session()->has('patient_session_uid')) && !(session()->has('patient_session_id'))) {
	// 		return redirect()->to(base_url()."/Patients_login/login");
	// 	} 
	// 	$this->patient_session_uid = session()->get('patient_session_uid'); //Patient session UID
	// 	//$this->patient_session_id = session()->get('patient_session_id'); //Patient ID

	// 	$this->pid = (int) session()->get('patient_session_id');
	// 	if(!isset($this->pid) || $this->pid == '') { $this->pid = 0; } //Why? for login patient
	// 	$this->is_new = 1; //For addressing notices
	// 	$this->sess_pid = $this->pid;
		
	// 	$this->data['patient_id'] = $this->pid; //for rendered form @bottom
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'name'              => 'required|min_length[4]|max_length[20]',
	// 			'mobile'            => 'required|numeric|exact_length[10]',
	// 			//'symptoms'          => 'required',
	// 			'age'          		=> 'required',
	// 			'gender'          	=> 'required',
	// 			//'email'             => 'required|valid_email',
	// 			'appointment_date'  => 'required',
	// 			'appointment_time'  => 'required',
	// 		];
			
	// 		//Generate Serial - START
	// 		$this->new_serial = 0;
	// 		$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date');
	// 		if(is_array($this->new_serial_arr) && count($this->new_serial_arr) > 0 ) {
	// 			if(isset($this->new_serial_arr['pid'])) {
	// 				$this->patient_id = $this->new_serial_arr['pid'];
	// 			}
	// 			if(isset($this->new_serial_arr['serial'])) {
	// 				$this->new_serial = (int) $this->new_serial_arr['serial'];
	// 			}
	// 			if(isset($this->new_serial) && $this->new_serial !== 0 && $this->new_serial !== false && (is_numeric($this->new_serial))) {
	// 				$this->new_serial = $this->new_serial_arr['serial'];
	// 			}
	// 			else {
	// 				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
	// 				return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 			}
	// 		}
	// 		else {
	// 			$this->session->setTempdata('error', 'Unexpected serial data', 3);
	// 			return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 		} //Generate Serial - END

	// 		$this->puid = $this->request->getVar('puid', FILTER_SANITIZE_STRING); //Searched PUID
	// 		$this->patient_name = trim($this->request->getVar('name',FILTER_SANITIZE_STRING));
	// 		$this->patient_mob = $this->request->getVar('mobile',FILTER_SANITIZE_STRING);
	// 		$this->gender = $this->request->getVar('gender',FILTER_SANITIZE_STRING);
	// 		$this->patient_email = $this->request->getVar('email');
	// 		$this->referenced_by = 0;
			
	// 		if(!isset($this->puid) || $this->puid == '') { 
	// 			if($this->pid > 0 ) {
	// 				$this->args = ['pid' => $this->pid ];
	// 				$this->patient_rslt = $this->patient_model->fetch_rec_by_args('patients', $this->args);
	// 				if($this->patient_rslt === false) { 
	// 					//$this->aptmt_id = ['id' => $this->sess_pid ];
	// 					$this->pid_args = ['pid' => $this->sess_pid ];
	// 					$this->patient_rslt = $this->patient_model->fetch_rec_by_args('booked_doctor_appointment', $this->pid_args);
	// 					if($this->patient_rslt === false) {
	// 						//Self appointment again
	// 						$this->puid = 0;
	// 						$this->referenced_by = $this->sess_pid;
	// 						$this->pid = $this->sess_pid; //become new patient
	// 						$this->is_new = 1;
	// 						// $this->session->setTempdata('error', 'Unexpected user, neither existing in appointment nor in patient tables', 3);
	// 						// return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 					}
	// 					else if(isset($this->patient_rslt['0']->gender) && isset($this->patient_rslt['0']->patient_name) && isset($this->patient_rslt['0']->patient_mobile)) {
	// 						if($this->gender != $this->patient_rslt['0']->gender || $this->patient_name != $this->patient_rslt['0']->patient_name || $this->patient_mob != $this->patient_rslt['0']->patient_mobile) { 	
	// 							//References or Family member appointments
	// 							$this->puid = 0;
	// 							$this->referenced_by = $this->sess_pid;
	// 							$this->pid = 0; //become new patient
	// 							$this->is_new = 1;
	// 						}
	// 						else { //Self appointment again 
	// 							$this->puid = 0;
	// 							$this->referenced_by = $this->sess_pid;
	// 							$this->pid = $this->sess_pid; //become new patient
	// 							$this->is_new = 0;
	// 						}
	// 					}
	// 					else {
	// 						$this->session->setTempdata('error', 'Unexpected patient details. Please check appointment records', 3);
	// 						return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 					}
	// 				}
	// 				else if(isset($this->patient_rslt['0']->puid) && isset($this->patient_rslt['0']->patient_name) && isset($this->patient_rslt['0']->patient_phone)) {
	// 					if($this->gender != $this->patient_rslt['0']->gender || $this->patient_name != $this->patient_rslt['0']->patient_name || $this->patient_mob != $this->patient_rslt['0']->patient_phone) {
	// 						$this->puid = 0;//$this->generate_puid($this->new_serial);
	// 						$this->referenced_by = $this->sess_pid;
	// 						$this->pid = 0; //become new patient
	// 						$this->is_new = 0;
	// 					}
	// 					else if($this->patient_email == $this->patient_rslt['0']->patient_email || $this->patient_mob == $this->patient_rslt['0']->patient_phone) {
	// 						$this->puid = $this->patient_rslt['0']->puid;
	// 						$this->pid = $this->sess_pid;
	// 						$this->referenced_by = $this->sess_pid;
	// 						$this->is_new = 0;
	// 					}
	// 					else {  
	// 						$this->session->setTempdata('error', 'Unexpected patient details. Please check patient records', 3);
	// 						return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 					}
	// 				}
	// 				else {  
	// 					$this->session->setTempdata('error', 'Unexpected patient details. Please contact to support team', 3);
	// 					return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 				}
	// 			}
	// 			else { 
	// 				$this->session->setTempdata('error', 'Unexpected patient ID format', 3);
	// 				return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 			}
	// 		}
			
	// 		if ($this->validate($rules)) {
	// 			$userdata = [
	// 				'patient_name'		=>  $this->request->getVar('name',FILTER_SANITIZE_STRING),
	// 				'pid'				=>  (int) $this->pid, //patient auto increment ID
	// 				'ref_by'			=>  (int) $this->referenced_by, //patient auto increment ID
	// 				'serial'			=>  $this->new_serial,
	// 				'patient_email'     =>  $this->request->getVar('email'),
	// 				'patient_mobile'    =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
	// 				'age'    			=>  $this->request->getVar('age',FILTER_SANITIZE_STRING),
	// 				'gender'    		=>  $this->request->getVar('gender',FILTER_SANITIZE_STRING),
	// 				'puid'    			=>  $this->puid,
	// 				'booking_date'      =>  $this->request->getVar('appointment_date',FILTER_SANITIZE_STRING),
	// 				'booking_time'      =>  $this->request->getVar('appointment_time',FILTER_SANITIZE_STRING),
	// 				'disease_symptoms'  =>  $this->request->getVar('symptoms',FILTER_SANITIZE_STRING),
	// 				'description'       =>  $this->request->getVar('desc',FILTER_SANITIZE_STRING),
	// 				'address'       	=>  $this->request->getVar('address',FILTER_SANITIZE_STRING),
	// 				'is_new'			=>  $this->is_new,
	// 				'doctor_id'         =>  $this->request->getVar('doctor_id',FILTER_SANITIZE_STRING),
	// 				'doctor_name'       =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING), //Custom
	// 				'status'            => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
	// 				'created_at'        =>  date('Y-m-d h:i:s')
	// 			];
				
	// 			// $status = false; //Just for addressing notices
	// 			if(isset($userdata['puid']) && $userdata['puid'] != '' ) { //Update Revisited Patient - START
	// 				$userdata['is_new'] = 0; //0: For old patients 1: for New Patients
	// 			} //Update Revisited - START
				
	// 			//echo "<pre>"; print_r($userdata);die;
	// 			// $status = $this->patient_model->Insertdata('booked_doctor_appointment', $userdata);
	// 			$status = $this->patient_model->Insertdata('booked_doctor_appointment', $userdata);
	// 			//}
	// 			if ($status == true) {
	// 				$args = ['id'   => $this->request->getVar('slot_id',FILTER_SANITIZE_STRING)];
	// 				$update_dtarr = array(
	// 					'booked'  		=> 1, //1: Patient booked, 0: Not booked	
	// 					'dr_available'  => 0, //1: Yes, 0: No
	// 					'patient_id'	=> (int) $this->pid,
	// 					'updated_by'	=> (int) $this->pid 
	// 				);
	// 				$dr_slots_updt_status = $this->patient_model->update_rec_by_args('doctor_slots', $args, $update_dtarr);
	// 				if($dr_slots_updt_status) {
	// 					$to        = $this->request->getVar('email');
	// 					$subject   = 'Booking Appointment  - Hospital Management System';
	// 					$message   = 'Hi ' .$this->request->getVar('name',FILTER_SANITIZE_STRING).",
	// 					<br>Thanks You are Booked Appointment to Dr. ".$this->request->getVar('doctor_name',FILTER_SANITIZE_STRING). "<br><br> Thanks For Your Booking <br> Your Booking date is :"
	// 					.$this->request->getVar('appointment_date',FILTER_SANITIZE_STRING)."<br> Booking Time is:<b>".$this->request->getVar('appointment_time',FILTER_SANITIZE_STRING)."</b>";
	// 					$this->email->setTo($to);
	// 					$this->email->setFrom('ADMIN_EMAIL', 'Info');
	// 					$this->email->setSubject($subject);
	// 					$this->email->setMessage($message);
	// 					$filepath = 'public/assets/images/logo3.png';
	// 					$this->email->attach($filepath);
	// 					if ($this->email->send()) {
	// 						$this->session->setTempdata('success', 'Appointment has booked successfully!',3 );
	// 					}
	// 					else{
	// 						$this->session->setTempdata('success', 'Appointment has booked successfully !, however, unable to email to Doctor', 3);
	// 					}
	// 					//return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 					return view('Patients/appointment_payment');
	// 				}
	// 				else{
	// 					$this->session->setTempdata('error', 'Unable to book appointment. Please try again', 3);
	// 					return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 				}
	// 			}
	// 			else{
	// 				$this->session->setTempdata('error', 'Sorry ! Unable to book appointment. Please try again', 3);
	// 				return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 			}  
	// 			$this->session->setTempdata('error', 'Sorry ! something went wrong', 3);
	// 			return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 			// Add New Patient - END
	// 		} //validation - Closed
	// 		else { 
	// 			$this->session->setTempdata('error', 'Failed due to missing mandatory fields.!', 3);
	// 			return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 		}
	// 	}
	// 	else {
	// 		// $this->session->setTempdata('error', 'Expected POST method.!', 3);
	// 		// $data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
	// 		// return redirect()->to(base_url().'Patients/doctors_available_slots/'.$this->sess_pid);
	// 		//return view('Patients/appointment_payment');
	// 	}
	// } //Function - Closed

	//************************** SLOTS SECTION END ******************************/

	
	/*********************** Appointent START ************************/
	public function book_appointment() {
		if (!(session()->has('patient_session_uid')) && !(session()->has('patient_session_id'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		} 
		$this->patient_session_uid = session()->get('patient_session_uid');
		$this->patient_session_id = session()->get('patient_session_id'); //Patient ID
		
		$this->is_new = 1; //For addressing notices -New Patient
		$this->userdata = array(); //Just for addressing notices

		if (!isset($this->patient_session_id) || ($this->patient_session_id == '') || (!isset($this->patient_session_uid)) || ($this->patient_session_uid == '')) {
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,'Unexpected patient ID or UID. Please talk to admin',
				'data' => $this->userdata,
			);
			return json_encode($this->result_arr);
		}

		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'patient_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Patient name is  mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
					],
				],
				'gender'          	=> 'required',
				'age'          		=> 'required',
				'mobile'            => 'required|numeric|exact_length[10]',
				'appointment_date'  => 'required',
				'appointment_time'  => 'required',
				'doctor_id'        	=> 'required',
				'doctor_name'       => 'required',
				'slot_id'        	=> 'required',
				'country_code'  	=> 'required',
			];
			
			//Generate Serial - START
			$this->new_serial = 0;
			$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date');
			if(is_array($this->new_serial_arr) || is_object($this->new_serial_arr)) {
				if(count($this->new_serial_arr) > 0 ) {
					$this->new_serial = (int) $this->new_serial_arr['serial'];
					if(isset($this->new_serial) && $this->new_serial !== 0 && $this->new_serial !== false && (is_numeric($this->new_serial))) {
						$this->new_serial = $this->new_serial_arr['serial'];
					}
					else {
						$this->result_arr = array(
							'status' 	=> false,
							'error'	 	=> true, //error: `true` whereever status is false with SQL err 
							'code'		=> 200,
							'message'	=> 'Sorry!, Failed to generate new serial',
							'data' 		=> $this->userdata,
						);
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	=> true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message'	=> 'Appointment number expected greater than zero!',
						'data' => $this->userdata,
					);
					return json_encode($this->result_arr);
				}
			}
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `ture` whereever status is false with SQL err 
					'code'	=> 200,
					'message'	=> 'Unexpected serial format!',
					'data' => $this->userdata,
				);
				return json_encode($this->result_arr);
			} //Generate Serial - END

			////////////////////////
			$this->puid 	= $this->request->getPost('puid', FILTER_SANITIZE_STRING); //Searched PUID
			$this->age 		= trim($this->request->getPost('age',FILTER_SANITIZE_STRING));
			$this->patient_name = $this->request->getPost('patient_name', FILTER_SANITIZE_STRING);
			$this->country_code 	= $this->request->getPost('country_code',FILTER_SANITIZE_STRING);
			$this->patient_mob 	= $this->request->getPost('mobile', FILTER_SANITIZE_STRING);
			$this->gender 	= $this->request->getPost('gender', FILTER_SANITIZE_STRING);
			$this->patient_email = $this->request->getPost('email'); 

			
			$this->appointment_date = $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING);
			$this->appointment_time = $this->request->getPost('appointment_time',FILTER_SANITIZE_STRING);
			$this->symptoms = $this->request->getPost('symptoms',FILTER_SANITIZE_STRING);
			$this->desc 	= $this->request->getPost('desc',FILTER_SANITIZE_STRING);
			$this->address 	= $this->request->getPost('address',FILTER_SANITIZE_STRING);
			$this->doctor_id 	= $this->request->getPost('doctor_id',FILTER_SANITIZE_STRING);
			$this->doctor_name 	= $this->request->getPost('doctor_name',FILTER_SANITIZE_STRING);
			$this->slot_id = $this->request->getPost('slot_id',FILTER_SANITIZE_STRING);
			$this->referenced_by = 0;
			
			if(!isset($this->puid)) { 
				if($this->puid == '') { 
					if($this->patient_session_id > 0 ) {
						$this->args = ['pid' => $this->patient_session_id ];
						$this->patient_rslt = $this->patient_model->fetch_rec_by_args('patients', $this->args);
						if($this->patient_rslt === false) { 
							$this->pid_args = ['pid' => $this->patient_session_id ];
							$this->patient_rslt = $this->patient_model->fetch_rec_by_args('booked_doctor_appointment', $this->pid_args);
							if($this->patient_rslt === false) {
								//Self appointment again
								$this->puid = 0;
								$this->referenced_by = $this->patient_session_id;
								$this->patient_session_id = $this->patient_session_id; //become new patient
								$this->is_new = 1;
							}
							else if(isset($this->patient_rslt['0']->gender) && isset($this->patient_rslt['0']->patient_name) && isset($this->patient_rslt['0']->patient_mobile)) {
								if($this->gender != $this->patient_rslt['0']->gender || $this->patient_name != $this->patient_rslt['0']->patient_name || $this->patient_mob != $this->patient_rslt['0']->patient_mobile) { 	
									//References or Family member appointments
									$this->puid = 0;
									$this->referenced_by = $this->patient_session_id;
									$this->patient_session_id = 0; //become new patient
									$this->is_new = 1;
								}
								else { //Self appointment - First appointment OR not paid fee yet of previous appointments 
									$this->puid = 0;
									$this->referenced_by = $this->patient_session_id;
									$this->patient_session_id = $this->patient_session_id; //become new patient
									$this->is_new = 1; //0;
								}
							}
							else {
								$this->result_arr = array(
									'status' => false,
									'error'	 => true, //error: `true` whereever status is false with SQL err 
									'code'	=> 200,
									'message'	=> 'Unexpected patient details. Please check appointment records',
									'data' => $this->userdata,
								);
								return json_encode($this->result_arr);
							}
						}
						else if(isset($this->patient_rslt['0']->puid) && isset($this->patient_rslt['0']->patient_name) && isset($this->patient_rslt['0']->patient_phone)) {
							if($this->gender != $this->patient_rslt['0']->gender || $this->patient_name != $this->patient_rslt['0']->patient_name || $this->patient_mob != $this->patient_rslt['0']->patient_phone) {
								$this->puid = 0;//$this->generate_puid($this->new_serial);
								$this->referenced_by = $this->patient_session_id;
								$this->patient_session_id = 0; //become new patient
								$this->is_new = 1; //0;
								//echo "form & apmt tble info mismatched";die;
							}
							else if($this->patient_email == $this->patient_rslt['0']->patient_email || $this->patient_mob == $this->patient_rslt['0']->patient_phone) {
								$this->puid = $this->patient_rslt['0']->puid;
								$this->patient_session_id = $this->patient_session_id;
								$this->referenced_by = $this->patient_session_id;
								$this->is_new = 0;
								//echo "form & apmt tble info mached";die;
							}
							else {
								$this->result_arr = array(
									'status' => false,
									'error'	 => true, //error: `true` whereever status is false with SQL err 
									'code'	=> 200,
									'message'	=> 'Unexpected patient details. Please check patient records',
									'data' => $this->userdata,
								);
								return json_encode($this->result_arr);
							}
						}
						else {
							$this->result_arr = array(
								'status' => false,
								'error'	 => true, //error: `true` whereever status is false with SQL err 
								'code'	=> 200,
								'message'	=> 'Unexpected patient details. Please contact to support team',
								'data' => $this->userdata,
							);
							return json_encode($this->result_arr);
						}
					}
					else {
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	=> 200,
							'message'	=> 'Unexpected patient ID format',
							'data' => $this->userdata,
						);
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->puid = $this->puid;
					$this->patient_session_id = $this->patient_session_id;
					$this->referenced_by = $this->patient_session_id;
					$this->is_new = 0;
				}
			}
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'	=> 'Missing PUID. Pleae contact admin',
					'data' => $this->userdata,
				);
				return json_encode($this->result_arr);
			} ///////////////////////
			if ($this->validate($rules)) { 
				$this->userdata = [
					'patient_name'		=>  $this->patient_name,
					'serial'			=>	$this->new_serial,
					'pid'				=>  $this->patient_session_id, //patient auto increment ID
					'patient_email'     =>  $this->patient_email,
					'country_code'    	=>  $this->country_code,
					'patient_mobile'    =>  $this->patient_mob,
					'ref_by'			=>	$this->referenced_by,
					'age'    			=>  $this->age,
					'gender'    		=>  $this->gender,
					'puid'    			=>  $this->puid, //$this->request->getPost('puid',FILTER_SANITIZE_STRING), //Search patient ID
					'is_new'			=> 	$this->is_new,
					'paid_apmt_fee'		=>	0, //Fee is not paid
					'slot_id'			=> 	$this->slot_id,
					'booking_date'      =>  $this->appointment_date,
					'booking_time'      =>  $this->appointment_time,
					'disease_symptoms'  =>  $this->symptoms,
					'description'       =>  $this->desc,
					'address'       	=>  $this->address,
					'status'			=>	1, //Appointment Initiated,
					'doctor_id'         =>  $this->doctor_id,
					'doctor_name'       =>  $this->doctor_name, //Custom
					'status'            =>  1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
					'created_at'        =>  date('Y-m-d h:i:s'),
					'created_by'        =>  $this->patient_session_uid,
				];
				//echo "<pre>"; print_r($this->userdata);die;
				$last_insrt_apmt_id = $this->commonForAllModel->Insertdata_return_id('booked_doctor_appointment', $this->userdata);
				if((int) $last_insrt_apmt_id > 0) { 
					$this->userdata['last_insrt_apmt_id'] = $last_insrt_apmt_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$this->userdata['appointment_date'] 	= $this->appointment_date; //For sending email
					$this->userdata['appointment_time'] 	= date('h:i:s'); //For sending email 
					$this->userdata['slot_id'] 	= $this->slot_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$this->userdata['uid'] 	= $this->patient_session_uid; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					
					$this->result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` whereever status is true with SQL err 
						'code'	=> 200,
						'message'	=> 'Appointment booked successfully without appointment fee',
						'data' => $this->userdata,
					);
					return json_encode($this->result_arr);
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message'	=> 'Sorry!, Failed to book an appointment',
						'data' => $this->userdata,
					);
					return json_encode($this->result_arr);
				}
			} // Add New Patient - END
			else { 
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'	=> 'Failed due to missing mandatory fields!',
					'data' => $this->userdata,
				);
				return json_encode($this->result_arr);
			}
		}
		else { 
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message'	=> 'Unexpected request method.!',
				'data' => $this->userdata,
			);
			return json_encode($this->result_arr); 
			//return view('Home/appointment_payment', $this->userdata); //Render - Payment form
		}
	}//Function - Closed


	public function run_appointment_fee_form() {
		return view('Patients/appointment_payment'); //render appointment fee form
	}


	// public function add_appointment_fee() {
	// 	$this->patient_session_id = session()->get('patient_session_id');
	// 	if (!(session()->has('patient_session_uid'))) {
	// 		return redirect()->to(base_url()."/Patients_login/login");
	// 	} $this->patient_session_uid = session()->get('patient_session_uid'); //Patient session UID
		
	// 	if (!(session()->has('patient_session_id'))) {
	// 		return redirect()->to(base_url()."/Patients_login/login");
	// 	} $this->patient_session_id = session()->get('patient_session_id'); //Patient ID
		
	// 	$status = false; //Just for addressing notices
	// 	//$this->paid_apmt_fee  = false; //Just for addressing notices
	// 	$this->is_new = 1; //For addressing notices -New Patient
	// 	$this->apmt_fee_paid  = 0; 	//0: NOT Paid, 1: Paid - //Just for addressing notices
	// 	$this->booked_slot    = 0; 	//1: 0: NOT booked Doctor Appointment slot, 1: booked Doctor Appointment slot - //Just for addressing notices
	// 	$this->dr_available   = 1; 	//1: Dr. slot Available, 0: Dr. slot NOT available  - //Just for addressing notices
	// 	$userdata = array(); //Just for addressing notices
		
	// 	$this->result_dt_arr = [
	// 		'appointment_date' 	=> '',
	// 		'appointment_time' 	=> ''
	// 	];
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'patient_name'	=> 'required',
	// 			'doctor_name'	=> 'required',
	// 			'appointment_date'	=> 'required',
	// 			'appointment_time'	=> 'required',
	// 			//'patient_email'		=> 'required',
	// 			'serial'        	=> 'required',
	// 			'slot_id'        	=> 'required',
	// 			'pay_option'        => 'required',
	// 		];
			
	// 		if ($this->validate($rules)) {
	// 			$this->slot_id 	= $this->request->getPost('slot_id',FILTER_SANITIZE_STRING);
	// 			$this->serial 	= $this->request->getPost('serial',FILTER_SANITIZE_STRING);
	// 			$this->last_insrt_apmt_id = $this->request->getPost('last_insrt_apmt_id',FILTER_SANITIZE_STRING);
	// 			//$this->paymentOption = $this->request->getPost('paymentOption',FILTER_SANITIZE_STRING);
	// 			$this->patient_name = $this->request->getPost('patient_name',FILTER_SANITIZE_STRING);

	// 			$this->doctor_name 	= $this->request->getPost('doctor_name',FILTER_SANITIZE_STRING);
	// 			$this->doctor_id 	= $this->request->getPost('doctor_id',FILTER_SANITIZE_STRING);
	// 			$this->appointment_date = $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING);
	// 			$this->appointment_time = $this->request->getPost('appointment_time',FILTER_SANITIZE_STRING);
	// 			$this->patient_email 	= $this->request->getPost('patient_email',FILTER_SANITIZE_STRING);

	// 			$this->apmt_regis_fee 	= $this->request->getPost('apmt_regis_fee',FILTER_SANITIZE_STRING);
	// 			$this->pay_method 		= $this->request->getPost('pay_option',FILTER_SANITIZE_STRING);
	// 			$this->country_code 	= $this->request->getPost('country_code', FILTER_SANITIZE_STRING);
	// 			$this->patient_mobile 	= $this->request->getPost('mobile', FILTER_SANITIZE_STRING);
	// 			$this->puid 	= $this->request->getPost('puid', FILTER_SANITIZE_STRING);
				
	// 			//Pay Registration FEE - START
	// 			$this->result_dt_arr = [
	// 				'appointment_date' 	=> $this->appointment_date,
	// 				'appointment_time' 	=> $this->appointment_time
	// 			];
	// 			//Appointment Fee OFFLINE mode - START
	// 			if($this->pay_method == 'offline') {
	// 				if(!isset($this->patient_email) || $this->patient_email == false ) {
	// 					$this->result_arr = array(
	// 						'status' => true,
	// 						'code'	=> 200,
	// 						'message'	=> "Temporary appointment booked successfully, however, slot can't reserve without payment.",
	// 						'data' => $this->result_dt_arr,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 				else { 
	// 					$this->subject = "Temporary appointment booked successfully, however, slot can't reserve without payment.";
	// 					$this->filepath = '';
						
	// 					$this->message = 'Dear ' .$this->patient_name . ','
	// 					.", <br>Thank You, your appointment with Dr. "
	// 					. $this->doctor_name. " booked. <br><br> Thanks For Your Booking <br> Your Booking date is :"
	// 					. $this->appointment_date ."<br> Booking Time is:<b>" . $this->appointment_time . "</b>";
						
	// 					$this->email_status = $this->send_an_email($this->patient_email, $this->subject, $this->message, $this->filepath);
	// 					if($this->email_status) {
	// 						$this->result_arr = array(
	// 							'status' => true,
	// 							'code'	=> 200,
	// 							'message' => "Temporary appointment booked successfully, however, slot can't reserve without payment.",
	// 							'data' => $this->result_dt_arr,
	// 						);
	// 						return json_encode($this->result_arr);
	// 					}
	// 					else {
	// 						$this->result_arr = array(
	// 							'status' => true,
	// 							'code'	=> 200,
	// 							'message' => "Temporary appointment booked successfully, however, slot can't reserve without payment.",
	// 							'data' => $this->result_dt_arr,
	// 						);
	// 						return json_encode($this->result_arr);
	// 					}
	// 				} //else - loop close
	// 			} //Appointment Fee OFFLINE mode - END
	// 			//Appointment Fee ONLINE mode - START
	// 			else if($this->pay_method !== 'offline') {

	// 				$this->expnc_arr = [
	// 					'patient_name' 	=> $this->patient_name, 
	// 					'doctor_id'		=> $this->doctor_id,
	// 					'doctor_name' 	=> $this->doctor_name, 
	// 					'medical_item'	=> 'Doctor Fee',
	// 					'medical_item_desc'=> 'Doctor fee when added patient w.r.t appointment',
	// 					'unit_price'		=> $this->apmt_regis_fee,
	// 					'units'				=> 1, //One time attended
	// 					'price'				=> $this->apmt_regis_fee,
	// 					'extra_charges'		=> '0.00',
	// 					'tax_percentage'	=> '0',
	// 					'tax_amount'    	=> '0.00',
	// 					'total_Price'	=> $this->apmt_regis_fee,
	// 					'serial'		=> 	$this->serial,
	// 					'pid' 	        =>  $this->patient_session_id, //$this->pid,
	// 					'puid'          =>  $this->puid,
	// 					'patients_id'   =>  0, //$this->patient_session_id,
	// 					'discount_percentage' =>  '0.00',
	// 					'discount_amount'   =>  '0.00',
	// 					'apmt_id'    	=>  $this->last_insrt_apmt_id,
	// 					'created_at'	=>  date('Y-m-d h:i:s'),
	// 					'created_by'	=>  $this->patient_session_id,
	// 				];

	// 				$this->paid_amt_fee_arr = [
	// 					'registration_fee'	=> $this->apmt_regis_fee,
	// 					'patient_name' 	=> $this->patient_name, 
	// 					'doctor_session_id'	=> $this->doctor_id,
	// 					'doctor_name' 	=> $this->doctor_name, 
	// 					'country_code' 	=> $this->country_code,
	// 					'patient_phone' => $this->patient_mobile,
	// 					'payment_note'	=> 'Paid appointment registration fee',
	// 					'serial'		=> 	$this->serial,
	// 					'pid' 	        =>  $this->patient_session_id, //$this->pid,
	// 					'puid'          =>  $this->puid,
	// 					'patients_id'   =>  0, //$this->patient_session_id,
	// 					'pay_mode'		=>  $this->pay_method,
	// 					'apmt_id'    	=>  $this->last_insrt_apmt_id,
	// 					'pay_date'      =>  $this->appointment_date, //date('Y-m-d'),
	// 					'created_at'	=>  date('Y-m-d h:i:s'),
	// 					'created_by'	=>  $this->patient_session_uid,
	// 				];

	// 				$this->apmt_args = ['id'   => $this->last_insrt_apmt_id];
	// 				$this->apmt_updt_arr = [
	// 					'paid_apmt_fee'    => 1, //0: NOT Paid, 1: Paid
	// 					'updated_at'       => date('Y-m-d h:i:s'),
	// 					'updated_by'       => $this->patient_session_uid,
	// 				];

	// 				$this->slot_args = ['id'   => $this->slot_id];
	// 				$this->slot_updt_arr = array(
	// 					'booked'  		=> 1, //1: Doctor Appointment booked, 0: NOT booked	
	// 					'dr_available'  => 0, //1: Dr. slot Available, 0: Dr. slot NOT available 
	// 					'serial'		=> $this->serial,
	// 					'patient_id'	=> $this->patient_session_id,
	// 					'updated_at'	=> date('Y-d-m H:i;s'),
	// 					'updated_by'	=> $this->patient_session_uid 
	// 				);

	// 				//$this->status = $this->commonForAllModel->save_appointment_fee('patients_pay_charges', $this->paid_amt_fee_arr, 'booked_doctor_appointment', $this->apmt_args, $this->apmt_updt_arr, 'doctor_slots', $this->slot_args, $this->slot_updt_arr);
	// 				$this->status = $this->commonForAllModel->save_appointment_fee('treatment_expenses_history', $this->expnc_arr, 'patients_pay_charges', $this->paid_amt_fee_arr, 'booked_doctor_appointment', $this->apmt_args, $this->apmt_updt_arr, 'doctor_slots', $this->slot_args, $this->slot_updt_arr);
	// 				if($this->status === true ) { 
	// 					if(!isset($this->patient_email) || $this->patient_email == false ) {
	// 						$this->result_arr = array(
	// 							'status' => true,
	// 							'code'	=> 200,
	// 							'message'	=> 'Appointment has booked successfully, however, could not send mail in absence of email.',
	// 							'data' => $this->result_dt_arr,
	// 						);
	// 						return json_encode($this->result_arr);
	// 					}
	// 					else {
	// 						//email body - START
	// 						$this->subject = 'Appointment Booked Successfully';
	// 						$this->filepath = '';
	// 						$this->message = 'Dear ' .$this->patient_name . ','
	// 						.", <br>Thank You, your appointment with Dr. "
	// 						. $this->doctor_name. " booked. <br><br> Thanks For Your Booking <br> Your Booking date is :"
	// 						. $this->appointment_date ."<br> Booking Time is:<b>" . $this->appointment_time . "</b>";
	// 						//email body - END 

	// 						//send email - START
	// 						$this->email_status = $this->send_an_email($this->patient_email, $this->subject, $this->message, $this->filepath);
	// 						if($this->email_status) {
	// 							$this->result_arr = array(
	// 								'status' => true,
	// 								'code'	=> 200,
	// 								'message' => 'Contratulations!, Appointment has booked successfully.',
	// 								'data' => $this->result_dt_arr,
	// 							);
	// 							return json_encode($this->result_arr);
	// 						}
	// 						else {
	// 							$this->result_arr = array(
	// 								'status' => true,
	// 								'code'	=> 200,
	// 								'message' => 'Appointment has booked successfully, however, email could not send',
	// 								'data' => $this->result_dt_arr,
	// 							);
	// 							return json_encode($this->result_arr);
	// 						} //send email - END	
	// 					} //else -loop closed
	// 				}
	// 				else if((int) $this->status === 2 ) { //Failed to update doctor slot table
	// 					$this->result_arr = array(
	// 						'status' => false,
	// 						'code'	=> 200,
	// 						'message' => 'Failed to update doctor slot table.',
	// 						'data' => $this->result_dt_arr,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 				else if((int) $this->status === 3 ) { //Failed to update appointment table 
	// 					$this->result_arr = array(
	// 						'status' => false,
	// 						'code'	=> 200,
	// 						'message' => 'Failed to update appointment table.',
	// 						'data' => $this->result_dt_arr,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 				else if((int) $this->status === 4 ) { //Failed to update doctor slot table
	// 					$this->result_arr = array(
	// 						'status' => false,
	// 						'code'	=> 200,
	// 						'message' => 'Failed to insert pay charges table.',
	// 						'data' => $this->result_dt_arr,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 			} //Appointment Fee ONLINE mode - END
	// 			else {
	// 				$this->result_arr = array(
	// 					'status' => false,
	// 					'code'	=> 200,
	// 					'message' => 'Unexpected payment mode. Please try again.',
	// 					'data' => $this->result_dt_arr,
	// 				);
	// 				return json_encode($this->result_arr);
	// 			}
	// 		}
	// 		else {
	// 			$this->result_arr = array(
	// 				'status' => false,
	// 				'code'	=> 200,
	// 				'message' =>  'Sorry!, Mandatory fileds validation failed.',
	// 				'data' => $this->result_dt_arr,
	// 			);
	// 			return json_encode($this->result_arr);
	// 		}
	// 	}
	// 	else {
	// 		$this->result_arr = array(
	// 			'status' => false,
	// 			'code'	=> 200,
	// 			'message' => 'Sorry!, Failed due to unxpected request method',
	// 			'data' => $this->result_dt_arr,
	// 		);
	// 		return json_encode($this->result_arr);
	// 	}
	// }//Function - Closed


	public function add_appointment_fee() {
		$this->patient_session_id = session()->get('patient_session_id');
		if ((!isset($this->patient_session_id)) || ($this->patient_session_id == '')) {
			$this->patient_session_id = 0; // Set a default value
		}
		$this->patient_session_uid = session()->get('patient_session_uid');
		if ((!isset($this->patient_session_uid)) || ($this->patient_session_uid == '')) {
			$this->patient_session_uid = 0; // Set a default value
		}
		
		$status = false; //Just for addressing notices
		//$this->paid_apmt_fee  = false; //Just for addressing notices
		$this->is_new = 1; //For addressing notices -New Patient
		$this->apmt_fee_paid  = 0; 	//0: NOT Paid, 1: Paid - //Just for addressing notices
		$this->booked_slot    = 0; 	//1: 0: NOT booked Doctor Appointment slot, 1: booked Doctor Appointment slot - //Just for addressing notices
		$this->dr_available   = 1; 	//1: Dr. slot Available, 0: Dr. slot NOT available  - //Just for addressing notices
		$userdata = array(); //Just for addressing notices
		
		$this->result_dt_arr = [
			'appointment_date' 	=> '',
			'appointment_time' 	=> ''
		];
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'patient_name'	=> 'required',
				'doctor_name'	=> 'required',
				'appointment_date'	=> 'required',
				'appointment_time'	=> 'required',
				//'patient_email'		=> 'required',
				'serial'        	=> 'required',
				'slot_id'        	=> 'required',
				'pay_option'        => 'required',
			];
			
			if ($this->validate($rules)) {
				$this->slot_id = $this->request->getPost('slot_id',FILTER_SANITIZE_STRING);
				$this->serial = $this->request->getPost('serial',FILTER_SANITIZE_STRING);
				$this->last_insrt_apmt_id = $this->request->getPost('last_insrt_apmt_id',FILTER_SANITIZE_STRING);
				//$this->paymentOption = $this->request->getPost('paymentOption',FILTER_SANITIZE_STRING);
				$this->patient_name = $this->request->getPost('patient_name',FILTER_SANITIZE_STRING);

				$this->doctor_name = $this->request->getPost('doctor_name',FILTER_SANITIZE_STRING);
				$this->doctor_id = $this->request->getPost('doctor_id',FILTER_SANITIZE_STRING);
				$this->appointment_date = $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING);
				$this->appointment_time = $this->request->getPost('appointment_time',FILTER_SANITIZE_STRING);
				$this->patient_email = $this->request->getPost('patient_email',FILTER_SANITIZE_STRING);

				$this->apmt_regis_fee = $this->request->getPost('apmt_regis_fee',FILTER_SANITIZE_STRING);
				$this->pay_method = $this->request->getPost('pay_option',FILTER_SANITIZE_STRING);
				$this->country_code = $this->request->getPost('country_code', FILTER_SANITIZE_STRING);
				$this->patient_mobile = $this->request->getPost('mobile', FILTER_SANITIZE_STRING);
				$this->puid = $this->request->getPost('puid', FILTER_SANITIZE_STRING);
				//Pay Registration FEE - START
				if(!isset($this->puid) || $this->puid == '') { $this->puid = 0; }
				$this->result_dt_arr = [
					'appointment_date' 	=> $this->appointment_date,
					'appointment_time' 	=> $this->appointment_time
				];
				// //Appointment Fee OFFLINE mode - START
				if($this->pay_method == 'offline') { 
					if(!isset($this->patient_email) || $this->patient_email == false ) {
						$this->result_arr = array(
							'status' => true,
							'error'	 => false, //error: `false` whereever status is true with SQL err 
							'code'	=> 200,
							'message'	=> "Temporary appointment booked successfully, however, slot can't reserve without payment.",
							'data' => $this->result_dt_arr,
						);
						return json_encode($this->result_arr);
					}
					else { 
						$this->subject = "Temporary appointment booked successfully, however, slot can't reserve without payment.";
						$this->filepath = '';
						
						$this->message = 'Dear ' .$this->patient_name . ','
						.", <br>Thank You, your appointment with Dr. "
						. $this->doctor_name. " booked. <br><br> Thanks For Your Booking <br> Your Booking date is :"
						. $this->appointment_date ."<br> Booking Time is:<b>" . $this->appointment_time . "</b>";
						
						$this->email_status = $this->send_an_email($this->patient_email, $this->subject, $this->message, $this->filepath);
						if($this->email_status) {
							$this->result_arr = array(
								'status' => true,
								'error'	 => false, //error: `false` whereever status is true with SQL err 
								'code'	=> 200,
								'message' => "Temporary appointment booked successfully, however, slot can't reserve without payment.",
								'data' => $this->result_dt_arr,
							);
							return json_encode($this->result_arr);
						}
						else {
							$this->result_arr = array(
								'status' => true,
								'error'	 => false, //error: `false` whereever status is true with SQL err 
								'code'	=> 200,
								'message' => "Temporary appointment booked successfully, however, slot can't reserve without payment.",
								'data' => $this->result_dt_arr,
							);
							return json_encode($this->result_arr);
						}
					} //else - loop close
				} //Appointment Fee OFFLINE mode - END
				//Appointment Fee ONLINE mode - START
				else if($this->pay_method !== 'offline') { 
					$this->paid_amt_fee_arr = [
						'registration_fee'	=> $this->apmt_regis_fee,
						'patient_name' 	=> $this->patient_name, 
						'doctor_id'		=> $this->doctor_id,
						'doctor_name' 	=> $this->doctor_name, 
						'country_code' 	=> $this->country_code,
						'patient_phone' => $this->patient_mobile,
						'paid_amount'	=> $this->apmt_regis_fee, //should add Tax Amount or use Total Amount`
						'payment_note'	=> 'Paid registration fee',
						'serial'		=> 	$this->serial,
						'pid' 	        =>  $this->patient_session_id, //$this->pid,
						'puid'          =>  $this->puid,
						'patients_id'   =>  0, //patients table id,
						'pay_mode'		=>  $this->pay_method,
						'apmt_id'    	=>  $this->last_insrt_apmt_id,
						'pay_date'      =>  $this->appointment_date, //date('Y-m-d'),
						'created_at'	=>  date('Y-m-d h:i:s'),
						'created_by'	=>  $this->patient_session_id,
					];

					$this->apmt_args = ['id'   => $this->last_insrt_apmt_id];
					$this->apmt_updt_arr = [
						'paid_apmt_fee'    => 1, //0: NOT Paid, 1: Paid
						'updated_at'       => date('Y-m-d h:i:s'),
						'updated_by'       => $this->patient_session_uid,
					];

					$this->slot_args = ['id'   => $this->slot_id];
					$this->slot_updt_arr = array(
						'booked'  		=> 1, //1: Doctor Appointment booked, 0: NOT booked	
						'dr_available'  => 0, //1: Dr. slot Available, 0: Dr. slot NOT available 
						'serial'		=> $this->serial,
						'patient_id'	=> $this->patient_session_id,
						'updated_at'	=> date('Y-d-m H:i;s'),
						'updated_by'	=> $this->patient_session_uid 
					);

					$this->status = $this->commonForAllModel->save_appointment_fee('patients_pay_charges', $this->paid_amt_fee_arr, 'booked_doctor_appointment', $this->apmt_args, $this->apmt_updt_arr, 'doctor_slots', $this->slot_args, $this->slot_updt_arr);
					if($this->status === true ) { 
						if(!isset($this->patient_email) || $this->patient_email == false ) {
							$this->result_arr = array(
								'status' => true,
								'error'	 => false, //error: `false` whereever status is true with SQL err 
								'code'	=> 200,
								'message'	=> 'Appointment has booked successfully, however, could not send mail in absence of email.',
								'data' => $this->result_dt_arr,
							);
							return json_encode($this->result_arr);
						}
						else {
							//email body - START
							$this->subject = 'Appointment Booked Successfully';
							$this->filepath = '';
							$this->message = 'Dear ' .$this->patient_name . ','
							.", <br>Thank You, your appointment with Dr. "
							. $this->doctor_name. " booked. <br><br> Thanks For Your Booking <br> Your Booking date is :"
							. $this->appointment_date ."<br> Booking Time is:<b>" . $this->appointment_time . "</b>";
							//email body - END 

							//send email - START
							$this->email_status = $this->send_an_email($this->patient_email, $this->subject, $this->message, $this->filepath);
							if($this->email_status) {
								$this->result_arr = array(
									'status' => true,
									'error'	 => false, //error: `false` whereever status is true with SQL err 
									'code'	=> 200,
									'message' => 'Contratulations!, Appointment has booked successfully.',
									'data' => $this->result_dt_arr,
								);
								return json_encode($this->result_arr);
							}
							else {
								$this->result_arr = array(
									'status' => true,
									'error'	 => false, //error: `false` whereever status is true with SQL err 
									'code'	=> 200,
									'message' => 'Appointment has booked successfully, however, email could not send',
									'data' => $this->result_dt_arr,
								);
								return json_encode($this->result_arr);
							} //send email - END	
						} //else - loop closed
					}
					else if((int) $this->status === 2 ) { //Failed to update doctor slot table
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	=> 200,
							'message' => 'Failed to update doctor slot table.',
							'data' => $this->result_dt_arr,
						);
						return json_encode($this->result_arr);
					}
					else if((int) $this->status === 3 ) { //Failed to update appointment table 
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	=> 200,
							'message' => 'Failed to update appointment table.',
							'data' => $this->result_dt_arr,
						);
						return json_encode($this->result_arr);
					}
					else if((int) $this->status === 4 ) { //Failed to update doctor slot table
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	=> 200,
							'message' => 'Failed to insert records into pay charges table.',
							'data' => $this->result_dt_arr,
						);
						return json_encode($this->result_arr);
					}
				} //Appointment Fee ONLINE mode - END
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	=> true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message' => 'Unexpected payment mode. Please try again.',
						'data' => $this->result_dt_arr,
					);
					return json_encode($this->result_arr);
				}
			}
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is fals with SQL err 
					'code'	=> 200,
					'message' => 'Sorry!, Mandatory fileds validation failed.',
					'data' => $this->result_dt_arr,
				);
				return json_encode($this->result_arr);
			}
		}
		else {
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message' => 'Sorry!, Failed due to unxpected request method',
				'data' => $this->result_dt_arr,
			);
			return json_encode($this->result_arr);
		}
	}//Function - Closed	


   /* @param: Send email
	* @desc: Send email 
	* @return: Result set or false if not existing
	* @use:Admin, Doctor, 
	* @author: Neoarks Team
	* @date: December 1st, 2023
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

	/*********************** Appointent END ************************/

	// public function view_receipt(){
	// 	if (!(session()->has('patient_session_uid'))) {
	// 		return redirect()->to(base_url()."/Patients_login/login");
	// 	}
	// 	else{
	// 		$this->patient_session_id = session()->get('patient_session_id');
	// 		$args = [
	// 			'pid'      => (int) $this->patient_session_id,
	// 			'is_del' => 0
	// 		];
			
	// 		$data['patients'] = $this->patient_model->fetch_rec_by_args('patients', $args);
	// 		//echo "<pre>";print_r($data);die;
	// 		return view('Patients/view_receipt', $data);
	// 	}
	// }

	/*@param: Function for view patient recipt
    * @desc: View patient recipt report
    * @use: Patient ....
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
	*/
	public function view_receipt(){
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		} else {
			$this->patient_session_id = session()->get('patient_session_id');
			$args = [
				'pid'      => (int) $this->patient_session_id,
				'is_del'   => 0
			];
			$orArgs = [
				'ref_by'  => (int) $this->patient_session_id,  // Logged-in Patient ID
			];
	
			$data = [
				'payments' => $this->patient_model->fetch_rec_by_orargs_arr('patients_pay_charges', $args, $orArgs),
				'pager'    => $this->patient_model->pager
			];
			//echo"<pre>";print_r($data);die;
			return view('Patients/view_receipt', $data);
		}
	}
	
	/*@param: Function for print_slip
    * @desc: View print_slip
    * @use: Patient ....
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
	*/
	public function print_slip($id) {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");} 
		$args = [ 'id'  => $id ];
		$data['patient_slip'] = $this->patient_model->fetch_rec_by_args('patients', $args);
		if($data['patient_slip'] == false) {
			$this->session->setTempdata('error', 'Patient payment details are not found!', 3);
			return redirect()->to(base_url() . "/Patients/view_receipt");
		}
		return view('Patients/print_slip', $data);
	}

	/*@param: Function for view patient discharge report
    * @desc: View patient discharge report
    * @use: Patient ....
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
	*/
	public function discharge_report(){
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		else {
			$this->patient_session_id = session()->get('patient_session_id');
			$args = [ 'pid'      => (int) $this->patient_session_id];
			$data['patients'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
			return view('Patients/discharge_patient_report', $data);
		}
	}

	/*@param: Logged-in user's `uid` used from session 
    * @desc: View user Profile
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */

	// public function view_profile(){   
	// 	if (!(session()->has('patient_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Patients_login/login");
	// 	}
	// 	else {
	// 		$this->patient_session_id = session()->get('patient_session_uid');
	// 		if ($this->request->getMethod() == 'get') {
	// 			$this->data['profile_record']  = $this->patient_model->getLoggedInPatientsData($this->patient_session_id);
	// 			if($this->data['profile_record'] === false) {
	// 				//return view('Patients/view_profile', $this->data);
	// 				//return redirect()->to(base_url() . 'Patients/view_profile', $this->data);
	// 				//echo "false value";die;
	// 			}
	// 			else {
	// 				return view('Patients/view_profile', $this->data);
	// 			}
	// 		}
	// 		else {
	// 			$this->session->setTempdata('error', 'Unexpected request method !', 3);
	// 			return view('Patients/view_profile', $this->data);
	// 		}
	// 	}
	// } //function - Closed


	/*@param: Logged-in user's `uid` used from session 
    * @desc: Upload Profile pic
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	public function upload_profile_pic() { 
		if (!(session()->has('patient_session_uid')) && !(session()->has('patient_session_id'))) {
			$this->result_arr = [
	 				'status' => false,
					'error'  => true, //error: `true` whereever status is false with SQL err 
	 				'code' => 200,
	 				'data' => [],
					'message' => 'Session has expired. Please relogin again.'
	 			];
	 		//return json_encode($this->result_arr);
			return redirect()->to(base_url() . "/Patients_login/login");
		} 
		$this->patient_uid = session()->get('patient_session_uid'); //Loggedin User uid
		$this->patient_id = session()->get('patient_session_id');
		
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {	
			$rules = [
				'uploaded_file' => [
					'rules'     => 'uploaded[uploaded_file]|max_size[uploaded_file,' . ALLOW_MAX_UPLOAD .']|is_image[uploaded_file]|mime_in[uploaded_file,image/jpeg,image/png]|ext_in[uploaded_file,png,jpg,jpeg,svg]',
					'errors' => [
						'uploaded'  => 'Profile picture is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image.',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
					],
				],

			];
			if (!$this->validate($rules)) {
				$this->result_arr = [
					'status' => false,
				   'error'  => true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' => [],
				   'message' => 'Oops!, Validatation failed. Please try gain.'
				];
				return json_encode($this->result_arr);
			}
			
			$this->profile_pic = $this->request->getFile('uploaded_file');
			$this->file_name = $this->profile_pic->getName();
			if($this->file_name && $this->file_name != '') {
				$args = [ 'id'  => $this->patient_id ];
				
				$this->old_data = $this->patient_model->fetch_rec_by_args('patients_login', $args);
				
				if(isset($this->old_data[0]->profile_pic)) {
					if(file_exists(FCPATH . 'uploads/patients/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/patients/' . $this->old_data[0]->profile_pic);
					} //else - Not needed
				} //else - Not needed	
					
				$this->random_name = $this->profile_pic->getRandomName();
				if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
					$this->profile_pic->move(FCPATH . 'uploads/patients', $this->random_name);
					
					$this->user_data_arr = [
						'profile_pic' 	  =>  $this->random_name,
						'updated_at'      =>  date('Y-m-d H:i:s'),
						'updated_by'      =>  $this->patient_id,
					];
					$args = ['id'	=> $this->patient_id]; //Need update model function in place of Insertdata - 
					$status = $this->patient_model->update_rec_by_args('patients_login', $args, $this->user_data_arr);
					if ($status === true) {
						$this->result_arr = array(
							'status' => true,
							'error'	 => false, //error: `false` whereever status is true with SQL err 
							'code' => 200,
							'data' > '',
							'message' => 'Photo uploaded successfully'
						);
						return json_encode($this->result_arr);
					} 
					else {
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code' => 200,
							'data' > '',
							'message' => 'Failed to upload Photo'
						);
						return json_encode($this->result_arr);
					}
					//return redirect()->to(current_url());
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code' => 200,
						'data' > '',
						'message' => 'File is not validated or has moved already'
					);
					return json_encode($this->result_arr);
				}
			} 
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > array(),
					'message' => 'Please choose profile picture'
				);
				return json_encode($this->result_arr);
			}
		} 
		else {
			$this->result_arr = array(
				'status' => true,
				'error'	 => false, //error: `false` whereever status is true with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Unexpected request method. Please try again'
			);
			return json_encode($this->result_arr);
		}
		//return redirect()->to(current_url());
	} //Function - Closed



	public function view_profile() {   
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url() . "/Patients_login/login");
		}
		else {
			$this->patient_uid = session()->get('patient_session_uid');
			if ($this->request->getMethod() == 'get') {
				$this->data['profile_record']  = $this->patient_model->getLoggedInPatientsData($this->patient_uid);
				if($this->data['profile_record'] === false) {
				$this->session->setTempdata('error', 'No Record Found', 3); 
				return redirect()->to(base_url() . 'Patients/view_profile' , $this->data);}
				else {return view('Patients/view_profile', $this->data);}
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Patients/view_profile', $this->data);
			}
		}
	} //function - Closed

	

	/*@param: Logged-in user's `uid` used from session 
    * @desc: View user Profile
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */

	// public function update_profile() {
    //     if (!(session()->has('patient_session_uid'))) {
    //         return redirect()->to(base_url() . "/Patients_login/login");
    //     }
    //     else {
    //         $this->patient_uid = session()->get('patient_session_uid');
	// 		$this->data = [];
	// 		$this->data['validation'] = null;
	// 		$rules = [
	// 			'mobile' => 'required',
	// 			'email' => 'required|min_length[5]|max_length[40]',
	// 			'username' => 'required',
	// 		];
	// 		if ($this->validate($rules)) {
	// 			if ($this->request->getMethod() == 'post') {
	// 				$this->args = [ 'uid'  => $this->patient_uid, ];
	// 				$this->updt_email = $this->request->getPost('email', FILTER_SANITIZE_STRING);
	// 				//email - Unique check - START
	// 				$this->is_unique = $this->commonForAllModel->unique_except_logged_in('patients_login', 'email', $this->updt_email, $this->patient_uid);
	// 				if($this->is_unique !== false ) {
	// 					$this->result_arr = array(
	// 						'status'    => false,
	// 						'code'      => 200,
	// 						'message'   => 'Email is already existing',
	// 						'data'      => $this->data,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				} //email - Unique check - END

	// 				$this->updt_mobile = $this->request->getPost('mobile', FILTER_SANITIZE_STRING);
	// 				//mobile - Unique check - START
	// 				$this->is_unique = $this->commonForAllModel->unique_except_logged_in('patients_login', 'mobile', $this->updt_mobile, $this->patient_uid);
	// 				if($this->is_unique !== false ) {
	// 					$this->result_arr = array(
	// 						'status'    => false,
	// 						'code'      => 200,
	// 						'message'   => 'Email is already existing',
	// 						'data'      => $this->data,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				} //email - Unique check - END

	// 				$this->user_data_arr = [
	// 					'username'    	=>  $this->request->getPost('username', FILTER_SANITIZE_STRING),
	// 					'about_me'    	=>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
	// 					'gender'     	=>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
	// 					'country_code'  =>  $this->request->getPost('country_code', FILTER_SANITIZE_STRING),
	// 					'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
	// 					'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
	// 					'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
	// 					'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
	// 					'address'     =>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
	// 					'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
	// 					'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
	// 					'updated_at'    => date('Y-m-d H:i:s'),
	// 					'updated_by'    => $this->patient_uid,
	// 				];
	// 				//echo "<pre>"; print_r($this->user_data_arr);die;
	// 				$this->data['profile_updt_status'] = $this->patient_model->update_rec_by_args('patients_login', $this->args, $this->user_data_arr);
	// 				if($this->data['profile_updt_status'] === false) {
	// 					$this->result_arr = array(
	// 						'status'    => false,
	// 						'code'      => 200,
	// 						'message'   => 'Unable to update profle!. Please try again',
	// 						'data'      => $this->data,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 				else {
	// 					$this->result_arr = array(
	// 						'status'    => true,
	// 						'code'      => 200,
	// 						'message'   => 'Congratulation!, Profile has updated successfully !',
	// 						'data'      => $this->data,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				}
	// 			}
	// 			else {
	// 				$this->result_arr = array(
	// 					'status'    => false,
	// 					'code'      => 200,
	// 					'message'   => 'Oops.!, Unexpected request method.!',
	// 					'data'      => $this->data,
	// 				);
	// 				return json_encode($this->result_arr);
	// 			}
	// 		}
	// 		else {
	// 			$this->result_arr = array(
	// 				'status'    => false,
	// 				'code'      => 200,
	// 				'message'   => 'Oops.!, Mandatory validation failed.!',
	// 				'data'      => $this->data,
	// 			);
	// 			return json_encode($this->result_arr);
	// 		} 
    //     } //else -loop closed
    // } //function -  closed


	public function update_profile() {
        if (!(session()->has('patient_session_uid'))) {
            return redirect()->to(base_url() . "/Patients_login/login");
        }
        else {
            $this->patient_uid = session()->get('patient_session_uid');
			$this->data = [];
			$this->data['validation'] = null;
			$rule = [
                // 'full_name'          => 'required|min_length[4]|max_length[20]',
				// 'username' => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'User name is  mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],

                'mobile'            => 'required|numeric|exact_length[10]',
                'email'         => [
                    'rules'      => 'required|valid_email|is_unique[blood_bank_users.email]',  
                    'errors'     => [
                        'required'     => 'Email is required',
                        'valid_email'  => 'Please enter valid email', //email format check
                        'is_unique'    => 'Email is already existing. Please try another email',
                    ]
                ]
            ];
			if ($this->validate($rule)) {
				if ($this->request->getMethod() == 'post') {
					$this->args = [ 'uid'  => $this->patient_uid, ];
					$this->user_data_arr = [
						'username'    	=>  $this->request->getPost('username', FILTER_SANITIZE_STRING),
						'about_me'    	=>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
						'gender'     	=>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
						'unique_id_name'=>  $this->request->getPost('unique_id_name', FILTER_SANITIZE_STRING),
						'unique_id'     =>  $this->request->getPost('unique_id', FILTER_SANITIZE_STRING),
						'country_code'  =>  $this->request->getPost('country_code', FILTER_SANITIZE_STRING),
						'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
						'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
						'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
						'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
						'address'     	=>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
						'pinzip'       	=>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
						'email'       	=>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
						'updated_at'    => date('Y-m-d H:i:s'),
						'updated_by'    => $this->patient_uid,
					];
					//echo "<pre>"; print_r($this->user_data_arr);die;
					$this->data['profile_updt_status'] = $this->adminModel->update_rec_by_args('patients_login', $this->args, $this->user_data_arr);
					if($this->data['profile_updt_status'] === false) {
						$this->result_arr = array(
							'status'    => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							'code'      => 200,
							'message'   => 'Unable to update profle!. Please try again',
							'data'      => $this->data,
						);
						return json_encode($this->result_arr);
					}
					else {
						$this->result_arr = array(
							'status'    => true,
							'error'		=> false, //error: `false` whereever status is true with SQL err 
							'code'      => 200,
							'message'   => 'Congratulation!, Profile has updated successfully !',
							'data'      => $this->data,
						);
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->result_arr = array(
						'status'    => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'      => 200,
						'message'   => 'Oops.!, Unexpected request method.!',
						'data'      => $this->data,
					);
					return json_encode($this->result_arr);
				}
			}
			else {
				$this->result_arr = array(
					'status'    => false,
					'error'		=> false, //get the automaticallt error when the validtion is failed
					'code'      => 200,
					'message'   => 'Oops.!, Mandatory validation failed.!',
					'data'      => $this->data,
				);
				return json_encode($this->result_arr);
			} 
        } //else -loop closed
    } //function -  closed
	

   /* @param: List all doctor's list
	* @desc: 
	* @return: 
	* @use: Home (Similar function is used inside Patient/view_doctor) 
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: Aug 1st, 2023
	* @modify:
	*/
	public function view_doctor(){
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."Patients_login/login");
		}
		$data['view_doctor'] = $this->commonForAllModel->fetch_all_records('doctor');
		return view('Patients/view_doctor',$data);
	}
	
	public function booked_doctor($id){
		$args = [
			'id'  => $id
		];
		$data['doctor_id'] = $this->patient_model->fetch_rec_by_args('doctor', $args);
		return view('Patients/booked_doctor', $data);
	}

		
	public function booked_doctor_appointment(){
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'patient_name'      => 'required|min_length[4]|max_length[20]',
				'patient_name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Patient name is  mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'patient_mobile'    => 'required|numeric|exact_length[10]',
				'patient_issue'     => 'required',
				'patient_email'     => 'required|valid_email',
				'booking_date'       => 'required',
				'booking_time'        => 'required',
			];
			if ($this->validate($rules)) {
				$userdata = [
					'patient_name'          =>  $this->request->getVar('patient_name',FILTER_SANITIZE_STRING),
					'patient_email'         =>  $this->request->getVar('patient_email'),
					'patient_mobile'        =>  $this->request->getVar('patient_mobile',FILTER_SANITIZE_STRING),
					'booking_date'           =>  $this->request->getVar('booking_date',FILTER_SANITIZE_STRING),
					'booking_time'           =>  $this->request->getVar('booking_time',FILTER_SANITIZE_STRING),
					'patient_issue'         =>  $this->request->getVar('patient_issue',FILTER_SANITIZE_STRING),
					'doctor_id'             =>  $this->request->getVar('doctor_id',FILTER_SANITIZE_STRING),
					'doctor_name'           =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING),
					'status'                => 'Appointment', 
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				$status = $this->patient_model->Insertdata('booked_doctor_appointment', $userdata);
				if ($status == true) {
					$to        = $this->request->getVar('patient_email');
					$subject   = 'Booking Appointment  - Hospital Management System';
					$message   = 'Hi ' .$this->request->getVar('patient_name',FILTER_SANITIZE_STRING).",
						<br>Thanks You are Booked Appointment to Dr. ".$this->request->getVar('doctor_name',FILTER_SANITIZE_STRING). "<br><br> Thanks For Your Booking <br> Your Booking date is :"
						.$this->request->getVar('booking_date',FILTER_SANITIZE_STRING)."<br> Booking Time is:<b>".$this->request->getVar('booking_time',FILTER_SANITIZE_STRING)."</b>";
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL', DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Appointment booked successfully !',3 );
						}else{
							$this->session->setTempdata('success', 'Appointment booked however failed to send email to Doctor!',3);
						}
						return redirect()->to(base_url().'/Patients/view_doctor');
				}else{
					$this->session->setTempdata('error', 'Sorry ! Unable to Booked  Try Again ?', 3);
				}  
				return redirect()->to(base_url().'/Patients/view_doctor');

			}else{
				$data['validation'] = $this->validator;
			}
			return view('Patients/booked_doctor', $data);

		}
	}


	public function booked_appointment_dash(){
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'patient_name'      => 'required|min_length[4]|max_length[20]',
				'patient_name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Patient name is  mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'patient_mobile'    => 'required|numeric|exact_length[10]',
				'patient_issue'     => 'required',
				'patient_email'     => 'required|valid_email',
				'doctor_name'       => 'required',
				'booking_date'       => 'required',
				'booking_time'        => 'required',
			];
			if ($this->validate($rules)) {
				$userdata = [
					'patient_name'          =>  $this->request->getVar('patient_name',FILTER_SANITIZE_STRING),
					'patient_email'         =>  $this->request->getVar('patient_email'),
					'patient_mobile'        =>  $this->request->getVar('patient_mobile',FILTER_SANITIZE_STRING),
					'booking_date'           =>  $this->request->getVar('booking_date',FILTER_SANITIZE_STRING),
					'booking_time'           =>  $this->request->getVar('booking_time',FILTER_SANITIZE_STRING),
					'patient_issue'         =>  $this->request->getVar('patient_issue',FILTER_SANITIZE_STRING),
					'doctor_id'             =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING),
					'doctor_name'           =>  'Doctors',
					'status'                => 'Appointment', 
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				$status = $this->patient_model->Insertdata('booked_doctor_appointment', $userdata);
				if ($status == true) {
					$args  = [
						'id'  => $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING)
					];
					$doctor = $this->patient_model->fetch_rec_by_args('doctor', $args);

					$to        = $this->request->getVar('patient_email');
					$subject   = 'Booking Appointment  - Hospital Management System';
					$message   = 'Hi Your are booking to Dr. ' .$doctor[0]->doctor_name.",
						<br>Welcome : ".$this->request->getVar('patient_name',FILTER_SANITIZE_STRING). "<br><br> Thanks For Your Booking <br> Your Booking date is :"
						.$this->request->getVar('booking_date',FILTER_SANITIZE_STRING)."<br> Booking Time is:<b>".$this->request->getVar('booking_time',FILTER_SANITIZE_STRING)."</b>";
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL', DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Appointment booked successfully',3 );
						}else{
							$this->session->setTempdata('success', 'Appointment booked, however, failed to email!',3 );
						}
						return redirect()->to(base_url().'/Patients/success');
				}else{
					$this->session->setTempdata('error', 'Sorry ! Unable to Booked  Try Again ?', 3);
				}  
				return redirect()->to(base_url().'/Patients/booked_appointment_dash');

			}else{
				$data['validation'] = $this->validator;
			}
			$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
			return view('Patients/book_appointment_dash', $data);
		}
	}

	public function success() { return view('Patients/success_view'); }


	/** 
	 * @parm:
	 * @desc: Hospital reviews & ratings by the patient.
	 * @use: Home page
	 * @author: Neoarks Team
	 * @date: 
	 * @modify: 27th Dec, 2023 
	*/
	public function review_hospital() {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		}
		return view('Patients/review_hosp_activity');
	} //function - Clsoed

	/** 
	 * @parm:
	 * @desc: Hospital reviews & ratings by the patient.
	 * @use: Home page
	 * @author: Neoarks Team
	 * @date: 
	 * @modify: 
	*/
    public function review_hosp_activity() {
		if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."/Patients_login/login");
		}
		$this->patient_session_uid = session()->get('patient_session_uid');
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() !== 'post') {
			$result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message' => 'Non-supported request method',
				'data' => array(),
			);
			return json_encode($result_arr);
		}
		$rules = [
			'review_title'   => 'required',
			'review_content' => 'required',

			// 'review_image' => [
			// 	'rules'     => 'uploaded[review_image]|max_size[review_image,' . ALLOW_MAX_UPLOAD .']|is_image[review_image]|mime_in[review_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[review_image,png,jpg,jpeg,svg, svg, gif]',
			// 	'errors' => [
			// 		'uploaded'  => 'Review Image is mandatory.',
			// 		'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
			// 		'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
			// 		'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
			// 	],
			// ],

		];
		$img = $this->request->getFile('review_image');
		$newName = ""; //for addressing notices

		if(!$this->validate($rules)) { 
			$data['validation'] = $this->validator; 
			$data = $data['validation']->getErrors(); //send validation array
			$result_arr = array(
				'status' => false,
				'error'	 => false, //get the validation automatically
				'code'	=> 200,
				'message' => 'Form validation failed',
				'data' => $data,
			);
			return json_encode($result_arr);
		}
		if($img) {
			if ($img->isValid() &&  !$img->hasMoved()) {
				$newName = $img->getRandomName(); 
				$this->file_mov_path = './uploads/frontend/review_image/'. $newName;
				if(!$this->crop_image($img, $this->file_mov_path)) {
					$result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message' => 'Sorry!, Failed to crop & move uploaded image',
						'data' => array(),
					);
					return json_encode($result_arr, true);
				}
			}
			else { 
				$result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message' => 'Failed to move file',
					'data' => array()
				);
				return json_encode($result_arr);
			}

		}
		$userdata = [
			'review_content' =>$this->request->getVar('review_content',FILTER_SANITIZE_STRING),
			'review_image'   =>  $newName,
			'review_title'   =>  $this->request->getVar('review_title',FILTER_SANITIZE_STRING),
			'star_rating'  =>  $this->request->getVar('star_rating', FILTER_SANITIZE_STRING),
			'status'       =>  'UnVerified', 
			'created_at'   =>  date('Y-m-d h:i:s'),
			'created_by'   =>  $this->patient_session_uid
		];
		
		$status = $this->patient_model->Insertdata('review_hospital', $userdata);
		if ($status === true) {
			$result_arr = array(
				'status' => true,
				'error'	 => false, //error: `false` whereever status is true with SQL err 
				'code'	=> 200,
				'message' => 'Review added successfully',
				'data' => array(),
			);
			return json_encode($result_arr, true);
		}
		else {
			$result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is faltruese SQL err 
				'code'	=> 200,
				'message' => 'Failed to add reviews',
				'data' => array(),
			);
			return json_encode($result_arr);
		}
	} //function closed

 

	// public function cropImage(){
	// 	if ($this->request->getMethod() !== 'post') { 
	// 		return view('Patients/crop_img');
	// 	}
    //     // Get the cropped image data from the POST request
    //     $croppedImageData = $this->request->getPost('croppedImage');
	// 	var_dump($croppedImageData);die;
    //     // Save the cropped image (example: save to a file)
    //     $newImagePath = WRITEPATH . './uploads/frontend/review_image/';
    //     file_put_contents($newImagePath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImageData)));

    //     // Additional logic (e.g., database update) can be added here
    //     return redirect()->to('/'); // Redirect to the desired page after cropping
    // }

	/**
	 * @param: image file objec 
	 * @param: file path with image name
	 * @desc: return true at moved CRPOED image
	 * 
	 */
	public function crop_image($img_file_obj, $img_path) { // or 'imagemaick must install on server'
		$this->img_obj = $img_file_obj;
		$this->img_path_with_filename = $img_path;
		$handler = 'gd';  
		$imageService = \Config\Services::image($handler);
		$imageService->withFile($this->img_obj);
		$imageService->crop(150, 150, 50, 50); // Example values for cropping

		// Perform other image manipulations if needed
		$newName = $this->img_obj->getRandomName(); 
		// Save or output the manipulated image
		if($imageService->save($this->img_path_with_filename)) { //return true @success
			return true;
		}
		else { return false; }
	} //function closed


} //class closed