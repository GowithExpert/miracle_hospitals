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
use \App\Models\AutoModel;
use \App\Models\Medicine_model;
//use \App\Models\CommonModel; //Custom
use \App\Models\CommonForAllModel; //Custom

class Medical_Accountant extends BaseController
{
	public $adminModel;
	public $session;
	public $AutoModel;
	//public $commonModel; //Custom
	public $medicine_model;
	public $commonForAllModel;

	
	public function __construct(){
		helper(['form','Patient']);
		$this->adminModel = new Admin_Model();
		$this->session    = \Config\Services::session();
		$this->patient_model   = new AutoModel();
		//$this->commonModel = new CommonModel(); //Custom
		$this->medicine_model   = new Medicine_model();
		$this->commonForAllModel   = new CommonForAllModel();
	}


	public function index(){
		$data = [];
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$this->acctnt_uid = session()->get('accountant_session_uid');
			$data['loggedin_usr'] = $this->medicine_model->getLoggedInUserData($this->acctnt_uid, 'register_all_users');
			$data['total_company'] = $this->commonForAllModel->fetch_all_records('medicine_category');
			$data['total_products'] = $this->commonForAllModel->fetch_all_records('medicines');

			//Dashboard Section Script Start

			$args  = [ 'order_date'   => date('Y-m-d ') ];
			$today_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [
				'order_date'   => date('Y-m-d', strtotime("-1 day"))
			];
			$yesterday_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [ 'order_date'   => date('Y-m-d', strtotime("-2 day")) ];
			
			$last_3days_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [
				'order_date'   => date('Y-m-d', strtotime("-3 day"))
			];
			$last_4days_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [
				'order_date'   => date('Y-m-d', strtotime("-4 day"))
			];
			
			$last_5days_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [
				'order_date'   => date('Y-m-d', strtotime("-5 day"))
			];
			
			$last_6days_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$args  = [
				'order_date'   => date('Y-m-d', strtotime("-6 day"))
			];
			
			$last_7days_customer  = $this->medicine_model->fetch_rec_by_args('order_product', $args);
			$data['chart_data']  = [
				'ch_today_order'        => $today_customer ? count($today_customer): "0",
				'ch_yesterday_order'    => $yesterday_customer ? count($yesterday_customer) : "0",
				'ch_last_3_days_order'  => $last_3days_customer ? count($last_3days_customer) : "0",
				'ch_last_4_days_order'  => $last_4days_customer ? count($last_4days_customer) : "0",
				'ch_last_5_days_order'  => $last_5days_customer ? count($last_5days_customer) : "0",
				'ch_last_6_days_order'  => $last_6days_customer ? count($last_6days_customer) : "0",
				'ch_last_7_days_order'  => $last_7days_customer ? count($last_7days_customer) :"0"
			]; 
			//Dashboard Section Script End
			return view('Medical/dashboard', $data);
		}
	}
	

	/*@param: 
	* @desc: Get all patients list having status: `Appointment`, `Active` & `Discharged`
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: May 12th, 2023
	* @modify:
	* @reference: Medical_Accountant/today_appointments()
	* @copyrights: Neoark Software Team
	*/
	public function all_appointments() {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		else { $args = [ 'is_del' => 0 ];
		$data = [
			'all_appointments' => $this->medicine_model->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager'     => $this->medicine_model->pager
		];
		return view("Medical/all_appointments", $data);
		}
	}

public function search_canceled_appointments(){
		if (!(session()->has('accountant_session_uid'))) {  
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
	
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
	
		$args = [
			'status' => 0, // 0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
		    'is_del' => 0
		];
	
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->medicine_model->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->medicine_model;
		}   
	
		$data = [
			'cancelled_apmt' => $this->medicine_model->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->medicine_model->pager
		];
		
		return view('Medical/cancelled_appointments', $data);
	}
		/*@param: 
	* @desc: Get today's appointed patients list having status: `Appointment`
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: 
	* @modify:
	* @reference: Admin/today_appointments()
	* @copyrights: Neoark Software Team
	*/
	
	public function today_appointments(){
		//$doctor_id = session()->get('doctor_session_id');
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			//'status' => 1,
			'is_del' => 0,
			//'doctor_id' => $doctor_id,
			//1: Appointment
			'booking_date' => date('Y-m-d')
		];
		$data = [
			'today_apmnt'  => $this->medicine_model->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager' => $this->medicine_model->pager
		];
	    //echo "<pre>";print_r($data);die;
		return view('Medical/today_appointment', $data);
	} //Function - Closed

/* @params: Function for permanent delete today appointments
* @desc: Admin can soft delete all appointments also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
public function permanent_del_today_apmnt($id){
$this->acctnt_uid = session()->get('accountant_session_uid');
		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 1, //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->acctnt_uid
		];

		$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
	}
// public function permanent_del_all_apmnt($id){
// 		$this->acctnt_uid = session()->get('accountant_session_uid');
// 		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
// 			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
// 			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
// 		}
// 		$args = [
// 			'id'  =>  $id,
// 		];
// 		$data = [
// 			'is_del' => 1,
// 			'status' => 1, //0: Non deleted, 1: Deleted
// 			'updated_at' => date('Y-d-m h:i:s'),
// 			'updated_by' => $this->acctnt_uid
// 		];

// 		$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
// 		if ($status == true) {
// 			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
// 		} 
// 		else {
// 			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
// 		}
// 		return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
// 	}


public function edit_patients($id){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	}$args = [
		'id'  => $id ];
	$data['patients'] = $this->medicine_model->fetch_rec_by_args('patients', $args);
	$data['doctors']  = $this->commonForAllModel->fetch_all_records('doctor');
	return view('Medical/edit_patients', $data);
}

public function update_patients($id){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	}
	$args = [
		'id'  => $id
	];

	$data = [
		'patient_name'           =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
		'patient_phone'          =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
		'patient_address'        =>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),
		'patient_zip'            =>  $this->request->getVar('patient_zip', FILTER_SANITIZE_STRING),
		'doctor_name'           =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
		'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
		'entry_fee'             =>  $this->request->getVar('entry_fee', FILTER_SANITIZE_STRING),
		'patient_issue'          =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
		'other_fee'             =>  $this->request->getVar('other_fee', FILTER_SANITIZE_STRING),
		'patient_room'           =>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
		'patient_email'          =>  $this->request->getVar('patient_email'),
		'status'                => 'Active'
	];
	$status = $this->commonForAllModel->update_rec_by_args('patients', $args, $data);
	if ($status == true) {
		$this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
	} else {
		$this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
	}
	//return redirect()->to(base_url().'/Medical_Accountant/edit_patients/'.$id);
	return redirect()->to(base_url() . 'Medical_Accountant/manage_patients');
}
	/*@param: 
	* @desc: Get all patients list having status: `Appointment`, `Active` & `Discharged`
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: May 12th, 2023
	* @modify:
	* @reference: Medical_Accountant/today_appointments()
	* @copyrights: Neoark Software Team
	*/
	public function search_all_appointments(){
		if (!(session()->has('accountant_session_uid'))) {  
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->medicine_model->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->medicine_model;
		}   
	
		$data = [
			'all_appointments' => $this->medicine_model->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->medicine_model->pager
		];
		
		return view('Medical/all_appointments', $data);
	}


	public function filter_appointment($filter){

		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
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
	
		$args = ['is_del'        => 0];
		$data = [
			'all_appointments' => $this->medicine_model->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order ,$args),
			'pager'   => $this->medicine_model->pager
		];
		
		return view('Medical/all_appointments', $data);
	}
	

	// public function add_fee($apmt_id, $status, $pid, $puid, $serial, $dr_id) {
	// 	if (!(session()->has('accountant_session_uid'))) { 
	// 		$this->session->setTempdata('error', 'Medical UID is missing !', 3);
	// 		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	// 	}
	// 	$this->acctnt_uid = session()->get('accountant_session_uid');
	// 	if ($this->request->getMethod() == 'get') {
	// 		$this->apmt_id = (int) $apmt_id; //appointment ID
	// 		$this->status = (int) $status; //Status
	// 		$this->pid = (int) $pid; //Patient ID
	// 		$this->puid = $puid; //Hospital assigned patient unique ID
	// 		$this->appt_serial = (int) $serial; //Appointment serial 
	// 		$this->dr_id = (int) $dr_id; //Appointment serial 
	// 		$data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function

	// 		$data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices

	// 		$dr_args = [ 'id'  => $this->dr_id  ];
	// 		$data['doctor_info'] = $this->medicine_model->fetch_rec_by_args('doctor', $dr_args); 

	// 		$apmt_args = [ 'id'  => $this->apmt_id ]; 
	// 		$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);

	// 		$apmt_pay_args = [ 'apmt_id'  => $this->apmt_id ]; 
	// 		$data['apmt_paymnt'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $apmt_pay_args);
	// 		if($data['apmt_paymnt'] === false) { 
	// 			$data['appointment_fee'] = APMT_REGIS_FEE; 
	// 			$data['apmt_paymnt_payid'] = 0; //Becoz payid is passed in next called function
	// 		} 
	// 		else if(isset($data['apmt_paymnt'][0]->registration_fee)) { 
	// 			$data['appointment_fee'] = (APMT_REGIS_FEE - $data['apmt_paymnt'][0]->registration_fee); 
	// 			$data['apmt_paymnt_payid'] = $data['apmt_paymnt'][0]->id; //becoz payid is passed in next called function
	// 		}
	// 		else { 
	// 			$this->session->setTempdata('error', 'Unsupported appointment fee', 3); 
	// 			return view('Medical/payments/add_fee', $data); // Pass the data to the view
	// 		}
	// 	}
	// 	else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
	// 	return view('Medical/payments/add_fee', $data); // Pass the data to the view
	// } //function - Closed
	public function add_fee($id, $status, $pid, $puid, $serial, $dr_id) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->apmt_id = (int) $id; //appointment ID
			$this->status = (int) $status; //Status
			$this->pid = (int) $pid; //Patient ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 
			$data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function

			$data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices

			$dr_args = [ 'id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->medicine_model->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $id ]; 
			$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);

			$apmt_pay_args = [ 'apmt_id'  => $this->apmt_id ]; 
			$data['apmt_paymnt'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $apmt_pay_args);
			if($data['apmt_paymnt'] === false) { 
				$data['appointment_fee'] = APMT_REGIS_FEE; 
				$data['apmt_paymnt_payid'] = 0; //Becoz payid is passed in next called function
			} 
			else if(isset($data['apmt_paymnt'][0]->registration_fee)) { 
				$data['appointment_fee'] = (APMT_REGIS_FEE - $data['apmt_paymnt'][0]->registration_fee); 
				$data['apmt_paymnt_payid'] = $data['apmt_paymnt'][0]->id; //becoz payid is passed in next called function
			}
			else { 
				$this->session->setTempdata('error', 'Unsupported appointment fee', 3); 
				return view('Medical/payments/add_fee', $data); // Pass the data to the view
			}
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Medical/payments/add_fee', $data); // Pass the data to the view
	} //function - Closed


	
	
	/*@params:
	* @desc: Add Appointment Fee
	* @retuns:
	* @author: Neoarks Team
	* @date: 16th Dec, 2023
	* @modify:
	*/
	public function save_fee($patient_id, $pid, $apmtid, $puid, $serial, $payid) {
		if (!(session()->has('accountant_session_uid'))) { 
			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}

		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int) $patient_id; //Patient ID
		$this->apmt_id 	  = (int) $apmtid; //appointment ID
		$this->pid 	= (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		$this->pay_chrg_id =  (int) $payid;
		
		$this->data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$this->apmt_args = ['id' 	=> $this->apmt_id];
		$this->data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $this->apmt_args);

		if(isset($this->data['apmt_patient'][0]->ref_by)) { 
			$this->ref_by = $this->data['apmt_patient'][0]->ref_by;
		} else { $this->ref_by = ''; }
		if(isset($this->data['apmt_patient'][0]->patient_name)) {
			$this->patient_name = $this->data['apmt_patient'][0]->patient_name;
		} else { $this->patient_name = 'Unkown'; }
		
		if(isset($this->data['apmt_patient'][0]->country_code)) { 
			$this->country_code = $this->data['apmt_patient'][0]->country_code;
		} else { $this->country_code = ''; }
		
		if(isset($this->data['apmt_patient'][0]->mobile)) { 
			$this->patient_mobile = $this->data['apmt_patient'][0]->patient_mobile;
		} else { $this->patient_mobile = ''; }

		if(isset($this->data['apmt_patient'][0]->doctor_id)) { 
			$this->doctor_id = $this->data['apmt_patient'][0]->doctor_id;
		} else { $this->doctor_id = 0; }
		if(isset($this->data['apmt_patient'][0]->doctor_name)) { 
			$this->doctor_name = $this->data['apmt_patient'][0]->doctor_name;
		} else { $this->doctor_name = ''; }
		if(isset($this->data['apmt_patient'][0]->age)) { 
			$this->age = $this->data['apmt_patient'][0]->age;
		} else { $this->age = ''; }
		if(isset($this->data['apmt_patient'][0]->gender)) { 
			$this->gender = $this->data['apmt_patient'][0]->gender;
		} else { $this->gender = ''; }
		

		$this->dr_args = ['id' 	=> $this->doctor_id]; 
		$this->data['doctor_info'] = $this->medicine_model->fetch_rec_by_args('doctor', $this->dr_args);

		if ($this->request->getMethod() == 'get') {
			return view('Medical/payments/add_fee', $this->data); //Reunder Fee form
		}
		//Add Fee - START
		else if ($this->request->getMethod() == 'post') {									
			$rules = [
				'appointment_fee'      => 'required',
				'doctor_fee'           => 'required',
				'total_payable'        => 'required',
			];
			if ($this->validate($rules)) {
				$this->pcnt_args = ['id' => $this->apmt_id];
				$this->updt_apmt_arr = [
					'status' => 6,  //Appointment Registration + Doctor fee have received
					'paid_apmt_fee' 	=> 1, //1: paid
					'paid_doctor_fee'	=> 1, //1: paid
					'updated_at' => date('Y-m-d H:i:s'),
					'updated_by' => $this->acctnt_uid,
				];
				$this->appointment_fee = $this->request->getVar('appointment_fee', FILTER_SANITIZE_STRING);
					$this->doctor_fee = $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING);
					
					$this->expnc_chrg_arr = [
						'patient_name' 	=> $this->patient_name, 
						'doctor_id'		=> $this->doctor_id,
						'doctor_name' 	=> $this->doctor_name, 
						'medical_item'	=> 'Doctor Fee',
						'medical_item_desc'=> 'Added Doctor Fee',
						'unit_price'		=> $this->doctor_fee,
						'units'				=> 1, //One time attended
						'price'				=> $this->doctor_fee,
						'extra_charges'		=> '0.00',
						'tax_percentage'	=> '0',
						'tax_amount'    	=> '0.00',
						'total_Price'	=> $this->doctor_fee,
						'expns_ref'		=> 	'Patient ref by ' . $this->ref_by,
						'pid' 	        =>  $this->pid,
						'puid'          =>  $this->puid,
						'patients_id'   =>  $this->patient_id,
						'discount_percentage' =>  '0.00',
						'discount_amount'   =>  '0.00',
						'apmt_id'    	=>  $this->apmt_id,
						'created_at'	=>  date('Y-m-d h:i:s'),
						'created_by'	=>  $this->acctnt_uid,
					];

					$this->user_pay_chrg_arr = [
						'registration_fee'	=> $this->request->getVar('appointment_fee', FILTER_SANITIZE_STRING),
						'doc_fee' 		=> $this->doctor_fee,
						'paid_amount' 	=> $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
						'payment_note'	=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
						'pid'           =>  $this->pid,
						'puid'          =>  $this->puid,
						'patients_id'   =>  $this->patient_id,
						'apmt_id'    	=>  $this->apmt_id,
						'patient_name'	=> $this->patient_name, 
						'country_code'	=> $this->country_code, 
						'patient_phone'	=> $this->patient_mobile,	
						'ref_by'		=> $this->ref_by,
						'gender'		=> $this->gender,
						'age'			=> $this->age,
						'doctor_id'		=> $this->doctor_id,
						'doctor_name'	=> $this->doctor_name,
						'pay_date'      => date('Y-m-d'),
						'created_at'	=> date('Y-m-d h:i:s'),
						'created_by'	=> $this->acctnt_uid,
					]; 

					$this->apmt_args = ['id' =>	$this->apmt_id];
					
					//$this->last_inst_id = $this->commonForAllModel->return_inserted_id_n_update('patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
					$this->last_inst_id = $this->commonForAllModel->insert_two_n_update_one_tbl('treatment_expenses_history', $this->expnc_chrg_arr, 'patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
					
					$this->data['apmt_paymnt_payid'] = $this->last_inst_id; //used in next call - perhapse
					if ($this->last_inst_id === 2) { //Failed to update appointment table
						return redirect()->to(base_url().'Medical_Accountant/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					}
					else if ($this->last_inst_id === 3) { //Failed to insert into patient pay charges table
						return redirect()->to(base_url().'Medical_Accountant/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else if ($this->last_inst_id === 4) { //Failed to insert treatment expences table 
						return redirect()->to(base_url().'Medical_Accountant/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else { //success:
						return redirect()->to(base_url() . 'Medical_Accountant/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
					}
				//} //else - loop Closed
			} 
			else { 
				$this->session->setTempdata('error', 'Failed to validate mandatory fields', 3);
				$this->data['validation'] = $this->validator; 
			}
		} //Add Fee - END
		else { $this->session->setTempdata('error', 'Request method is not supported', 3); }
	} //function - Closed

	

	public function generate_initial_pay_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		//var_dump($patient_id, $pid, $payid, $apmtid, $puid, $serial);die;
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = true; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Medical_Accountant/add_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
		$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Fee Paid', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->acctnt_uid,
				];
			$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			if(!isset($data['apmt_patient'][0]->doctor_id)) {
				$this->session->setTempdata('error', 'Doctor id is not found in appointment table', 3);
				return view('Frontdesk/payments/generate_admission_bill', $data);

			}
			if($data['apmt_patient'][0]->doctor_id == 0 || $data['apmt_patient'][0]->doctor_id == '') {
				$this->session->setTempdata('error', 'Doctor id is blank or zero in appointment table', 3);
				return view('Frontdesk/payments/generate_admission_bill', $data);
			}
			//$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
			$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Medical/payments/generate_initial_fee_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			//var_dump($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);die;
			//$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
			$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Medical/payments/generate_initial_fee_bill', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Medical/payments/generate_initial_fee_bill', $data);
		}
	} //function - Closed

	/****************************** Change All Appointment Status - START ****************/
	//public function change_all_appointments_status($apmtid, $status, $pid, $puid, $serial) {
	public function change_all_appointments_status($id, $status, $pid, $puid, $serial, $dr_id){
			$this->apmt_id = (int) $id; //appointment ID
			$this->status = (int) $status; //Status
			$this->pid = (int) $pid; //Patient ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = $dr_id; //Doctor ID
			
		if (!(session()->has('accountant_session_uid'))) { 
			 return redirect()->to(base_url() . "/Accountant_login/accountant_login"); 
			}
		
		$this->acctnt_uid = session()->get('accountant_session_uid');
		
		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Medical Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table
		$this->data = [
				'status' => $status,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->acctnt_uid,
			];

		if ($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
			//	$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->acctnt_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
			$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->acctnt_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				} 
				else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->pid > 0) { //pid: patient_login tbl `id`
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//var_dump($this->pid);die;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->acctnt_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) { //var_dump($this->data, $this->args, $this->acctnt_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);die;
				/***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->acctnt_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Unexpected or Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
			}
		} 
		else { //Update appointments only
			$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			//$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed

	//function patient_details_with_puid($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial){
		function patient_details_with_puid($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->acctnt_uid = $acctnt_uid;
		$this->patient_id = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = (int) $serial;
		$this->dr_id = $dr_id; //Doctor ID

		$this->args_neo = ['puid'  => $this->puid];
		$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('patients', $this->args_neo);
		if ($this->patient_rslt != false) {
			$this->updt_data_arr['pid'] = $this->patient_rslt['0']->pid;
			$this->updt_data_arr['ref_by'] = $this->patient_rslt['0']->ref_by;

			$this->insrt_data_arr = array(
				'puid'				=> $this->puid,
				'serial'			=> $this->new_serial, //$this->patient_rslt['0']->serial,
				'revisit_date'		=> date('Y-m-d'),
				'apmt_id'			=> $this->apmt_id,
				'patient_name'		=> $this->patient_rslt['0']->patient_name,
				'pid'				=> (int)$this->patient_rslt['0']->pid,
				'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
				'patient_id'		=> $this->patient_rslt['0']->id,
				'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
				'patient_address'	=> $this->patient_rslt['0']->patient_address,
				'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
				'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
				'doctor_id'			=> $this->dr_id,
				'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
				'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
				'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
				'other_fee'			=> $this->patient_rslt['0']->other_fee,
				'patient_room'		=> $this->patient_rslt['0']->patient_room,
				'patient_email'		=> $this->patient_rslt['0']->patient_email,
				/*'next_action'		=> $this->patient_rslt['0']->id,
				'assigned_by'		=> $this->patient_rslt['0']->id,
				'assigned_to'		=> $this->patient_rslt['0']->id, */
				'status'			=> 'Attended',
				'created_at'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->acctnt_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			// echo "<pre>"; print_r($this->insrt_data_arr);die;
			return $this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/*****  Get Patient detail thru `paitient ID` - START *****/
		else if ($this->patient_id > 0) { //Case - Take FIRST appointment - by loggedin Patient 
			$this->args_neo = ['pid'  => $this->patient_id]; //Patient loggedin-ID
			$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('patients', $this->args_neo);
			if ($this->patient_rslt != false) {
				$this->updt_data_arr['pid'] = $this->patient_rslt['0']->pid;
				$this->updt_data_arr['ref_by'] = $this->patient_rslt['0']->ref_by;
				$this->insrt_data_arr = array(
					'puid'				=> $this->patient_rslt['0']->puid,
					'patient_name'		=> $this->patient_rslt['0']->patient_name,
					'pid'				=> (int)$this->patient_rslt['0']->pid,
					'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
					'patient_id'		=> $this->patient_rslt['0']->id,
					'serial'			=> $this->new_serial,
					'revisit_date'		=> date('Y-m-d'), //Current date
					'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
					'patient_address'	=> $this->patient_rslt['0']->patient_address,
					'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
					'doctor_id'			=> $this->dr_id,
					'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
					'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
					'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
					'other_fee'			=> $this->patient_rslt['0']->other_fee,
					'patient_room'		=> $this->patient_rslt['0']->patient_room,
					'patient_email'		=> $this->patient_rslt['0']->patient_email,
					/*'next_action'		=> $this->patient_rslt['0']->id,
					'assigned_by'		=> $this->patient_rslt['0']->id,
					'assigned_to'		=> $this->patient_rslt['0']->id, */
					'status'			=> 'Attended',
					'created_at'		=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->acctnt_uid, //need uid
					//'patient_image'	=> $this->patient_rslt['0']->id
				);
				// echo "second";
				// echo "<pre>"; print_r($this->insrt_data_arr);die;
				return $this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
			} 
			else if ($this->apmt_id != ''  && $this->apmt_id > 0) { //MOI?? //FIRST time Appointment - Loggedin Patient
				$this->args_neo = ['id' => $this->apmt_id];
				$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
				if ($this->patient_rslt != false) { //Generate PUID
					//Generate puid
					$this->puid = 0; //Just for addressing notices
					if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
						$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
						return redirect()->to(base_url() . 'Medical/all_appointments');
					} else {
						$this->puid = $this->generate_puid($this->new_serial);
					}
					$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

					$this->insrt_data_arr = array(
						'puid'				=> $this->puid,
						'serial'			=> $this->new_serial,
						'registration_date'	=> date('Y-m-d'),
						'patient_name'		=> $this->patient_rslt['0']->patient_name,
						'pid'				=> (int)$this->patient_rslt['0']->pid,
						'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
						'age'				=> $this->patient_rslt['0']->age,
						'gender'			=> $this->patient_rslt['0']->gender,
						'patient_phone'		=> $this->patient_rslt['0']->patient_mobile,
						'patient_address'	=> $this->patient_rslt['0']->address,
						//'patient_zip'		=> $this->patient_rslt['0']->doctor_id,
						'doctor_id'			=> $this->patient_rslt['0']->doctor_id,
						'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
						'apmt_id'			=> $this->patient_rslt['0']->id,
						//'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
						//'entry_fee'		=> $this->patient_rslt['0']->entry_fee,
						'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
						//'other_fee'		=> $this->patient_rslt['0']->other_fee,
						'patient_room'		=> $this->patient_rslt['0']->department, //room ?
						'patient_email'		=> $this->patient_rslt['0']->patient_email,
						//'uid'				=>
						//'patient_image'		=> $this->patient_rslt['0']->id
						'status'			=> 'Attended',
						'level'				=>	3, //3: Doctor - MOI for admin level?
						'created_at'		=> date('Y-m-d H:i:s'),
						'created_by'		=> $this->acctnt_uid, //need uid
						//'deleted_at'		=> 
					);
					// 	echo "2nd last";
					// echo "<pre>";print_r($this->insrt_data_arr);die;
					return $this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
				} else {
					$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
					$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous!, patient info is not found', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
			}
		}
		/***** Get Patient detail thru `paitient ID` - END *****/
		else {
			$this->session->setTempdata('error', 'Puid! must exist patients table', 3);
			return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
		}
	} //function - Closed


	//function patient_details_with_pid($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial){
		function patient_details_with_pid($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->acctnt_uid = $acctnt_uid;
		$this->pid = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->args_neo = ['pid' => $this->pid];

		$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('patients', $this->args_neo);
		if ($this->patient_rslt != false) {
			if (isset($this->patient_rslt['0']->puid)) { $this->puid = $this->patient_rslt['0']->puid; }
			$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

			$this->insrt_data_arr = array(
				'puid'				=> $this->puid,
				'patient_name'		=> $this->patient_rslt['0']->patient_name,
				'patient_id'		=> $this->patient_rslt['0']->id,
				'serial'			=> $this->new_serial,
				'revisit_date'		=> date('Y-m-d'),
				'apmt_id'			=> $this->apmt_id,
				'pid'				=> (int)$this->patient_rslt['0']->pid,
				'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
				'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
				'patient_address'	=> $this->patient_rslt['0']->patient_address,
				'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
				'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
				'doctor_id'			=> $dr_id, 
				'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
				'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
				'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
				'other_fee'			=> $this->patient_rslt['0']->other_fee,
				'patient_room'		=> $this->patient_rslt['0']->patient_room,
				'patient_email'		=> $this->patient_rslt['0']->patient_email,
				/*'next_action'		=> $this->patient_rslt['0']->id,
				'assigned_by'		=> $this->patient_rslt['0']->id,
				'assigned_to'		=> $this->patient_rslt['0']->id, */
				'status'			=> 'Attended',
				'created_at'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->acctnt_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			return $this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/* Get Patient detail thru `Appointment ID` - START 
		 * @desc: Loggedin - NEW Patient appointment Addeded by Admin & Admin
		 * 
		 */ else if ($this->apmt_id != ''  && $this->apmt_id > 0) {
			$this->args_neo = ['id' => $this->apmt_id];
			$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
			if ($this->patient_rslt != false) { //Generate PUID
				//Generate puid
				$this->puid = 0; //Just for addressing notices
				if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
					$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
				} 
				else { $this->puid = $this->generate_puid($this->new_serial); }
				$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 
				$this->updt_data_arr['patients_id'] = $this->pid; //For updating patients_id in appoitment list 
				$this->insrt_data_arr = array(
					'puid'				=> $this->puid,
					'serial'			=> $this->new_serial,
					'registration_date'	=> date('Y-m-d'),
					'patient_name'		=> $this->patient_rslt['0']->patient_name,
					'pid'				=> (int)$this->patient_rslt['0']->pid,
					'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
					'age'				=> $this->patient_rslt['0']->age,
					'gender'			=> $this->patient_rslt['0']->gender,
					'patient_phone'		=> $this->patient_rslt['0']->patient_mobile,
					'patient_address'	=> $this->patient_rslt['0']->address,
					//'patient_zip'		=> $this->patient_rslt['0']->doctor_id,
					'doctor_id'			=> $dr_id, 
					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
					'apmt_id'			=> $this->patient_rslt['0']->id,
					//'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
					//'entry_fee'		=> $this->patient_rslt['0']->entry_fee,
					'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
					//'other_fee'		=> $this->patient_rslt['0']->other_fee,
					'patient_room'		=> $this->patient_rslt['0']->department, //room ?
					'patient_email'		=> $this->patient_rslt['0']->patient_email,
					//'uid'				=>
					//'patient_image'		=> $this->patient_rslt['0']->id
					'status'			=> 'Attended',
					'level'				=>	3, //3: Doctor - MOI for admin level?
					'created_at'		=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->acctnt_uid, //need uid
					//'deleted_at'		=> 
				);
				// 	echo "2nd last";
				// echo "<pre>";print_r($this->insrt_data_arr);die;
				return $this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
			} 
			else {
				$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
				$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
			}
		}
		/***** Get Patient detail thru `Appointment ID` - END *****/
		else {
			$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
			$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
		}
	} //Function - Closed


	/* @desc: Non- loggedin Patient appointment Addeded by Admin & Admin
	 * Non loggedin Patient Attended
	 * Get Patient detail thru `Appointment ID` 
	 * 
	 */
	//function patient_details_with_apmt_id($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial) {
		function patient_details_with_apmt_id($data, $args, $acctnt_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->updt_data_arr = $data;
		$this->args = $args; //$this->args['id'] = $this->apmt_id;
		$this->acctnt_uid = $acctnt_uid;
		$this->puid = $puid;
		$this->apmt_id = $apmt;
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->patient_rslt = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $this->args);
		if ($this->patient_rslt != false) {
			//Generate puid
			$this->puid = 0; //Just for addressing notices
			if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/all_appointments');
			} 
			else { $this->puid = $this->generate_puid($this->new_serial); }
			$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 
			$this->updt_data_arr['patients_id'] = 0; //Because patient record is not in patients table yet 
			$this->insrt_data_arr = array(
				'puid'				=> $this->puid,
				'serial'			=> $this->new_serial,
				'registration_date'	=> date('Y-m-d'),
				'patient_name'		=> $this->patient_rslt['0']->patient_name,
				'pid'				=> (int)$this->patient_rslt['0']->pid,
				'ref_by'			=> (int)$this->patient_rslt['0']->ref_by,
				'age'				=> $this->patient_rslt['0']->age,
				'gender'			=> $this->patient_rslt['0']->gender,
				'patient_phone'		=> $this->patient_rslt['0']->patient_mobile,
				'patient_address'	=> $this->patient_rslt['0']->address,
			   'doctor_id'			=> $this->patient_rslt['0']->doctor_id, //$this->dr_id,
				'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
				'apmt_id'			=> $this->patient_rslt['0']->id,			
				'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,			
				'patient_room'		=> $this->patient_rslt['0']->department, //room ?
				'patient_email'		=> $this->patient_rslt['0']->patient_email,
				'status'			=> 'Attended',
				'level'				=>	3, //3: Doctor - MOI for admin level?
				'created_at'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->acctnt_uid,			
			);
			//Non- loggedin Patient appointment Addeded by Admin & Admin
			//echo "<pre>";print_r($this->insrt_data_arr);die;
			$this->updt_rslt = $this->medicine_model->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
			return $this->updt_rslt; 
		} else {
			$this->updt_rslt = 2; //Anonymus user, Patient record is not found in db
			$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
		}
	} //function - Closed


	function err_chk_update_appintment_inst_patient_info($err_type){
		$this->type_of_err = $err_type;

		switch ($this->type_of_err) {
			case 'false':
				$this->session->setTempdata('error', 'False response, failed to update appointment', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;
			case '':
				$this->session->setTempdata('error', 'Blank response, failed to udpate appointment', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;
			case 'true':
				$this->session->setTempdata('success', 'Congratulation ! Appointment Status Change Successfully !', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;

			case 2:
				$this->session->setTempdata('error', 'Anonymus user, Patient record is not found in db', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;

			case 3:
				$this->session->setTempdata('error', 'Revisited Patients info insertion failed', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;

			case 4:
				$this->session->setTempdata('error', 'Failed to book doctor appointment', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;

			default:
				$this->session->setTempdata('error', 'Sorry ! Unable to Change Try Again ?', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/all_appointments');
				break;
		}
	} //function - Closed
	//*******************Attend Patient - END ***********************


	/****************************** Chnage All Appointment Status - END *****************/


	/****************************** Admimission Fee - START ******************************/
   /* @params: 
	* @desc: Render Admission form for adding admimission fee and other charges
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 9th November,2023
	* @modify
	*/
	public function admit_patient($patient_id, $pid, $apmt_id, $puid, $serial, $dr_id) {
		if (!(session()->has('accountant_session_uid'))) { 
			$this->session->setTempdata('error', 'Medical Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->patient_id = (int) $patient_id; 
			$this->pid = (int) $pid; //Patient ID
			$this->apmt_id = (int) $apmt_id; //appointment ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 

			$data = [];
			$data['validation'] = null;
				
			// $data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function
			// $data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices
			$dr_args = [ 'id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->medicine_model->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $this->apmt_id ]; 
			$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Medical/payments/add_admission_fee', $data); // Pass the data to the view
	} //function - Closed


   /* @params: 
	* @desc: Save Admission fee 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 9th November,2023
	* @modify
	*/
	public function save_admission_fee($patient_id, $pid, $apmtid, $puid, $serial) {
		if (!(session()->has('accountant_session_uid'))) { 
			$this->session->setTempdata('error', 'Medical Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int) $patient_id; //Patient ID
		$this->apmt_id = (int) $apmtid; //appointment ID
		$this->pid = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		//$this->pay_chrg_id =  (int) $payid;

		if ($this->request->getMethod() == 'post') {
			$data = [];
			$data['validation'] = null;
		
			$rules = [ //Validation array
					'admission_fee'   => 'required',
					'other_charges'   => 'required',
					'total_payable'   => 'required',
				];
			if ($this->validate($rules)) {
				$this->admission_fee = $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING);
				//NOT paid registration fee
				$this->user_data_arr = [
					'registration_fee'	=> $this->request->getVar('other_charges', FILTER_SANITIZE_STRING),
					'admission_fee' => $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING),
					'paid_amount' 	=> $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
					'payment_note'			=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'           =>  $this->pid,
					'puid'          =>  $this->puid,
					'patients_id'   =>  $this->patient_id,
					'apmt_id'    	=>  $this->apmt_id,
					'pay_date'      =>  date('Y-m-d'),
					'created_at'	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->acctnt_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Medical_Accountant/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Medical_Accountant/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
				}
			} 
			else { 
				$data['validation'] = $this->validator;
				$this->session->setTempdata('error', 'Failed to validate mandatory fields', 3);
			}
		}
		else { 
			$this->session->setTempdata('error', 'Request method is not supported', 3); 
		}
		$this->pcnt_args = ['id' 	=> $this->apmt_id];
		$data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

		$this->dr_args = ['id' 	=> $this->apmt_id]; //NEED $doctor_id here
		$data['doctor_info'] = $this->medicine_model->fetch_rec_by_args('doctor', $this->dr_args);
		return view('Medical/payments/add_admission_fee', $data);
	} //function - Closed


	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	public function generate_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		//$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = false; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Medical_Accountant/add_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		//var_dump($this->patient_id);die;
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
			$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Admit', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->acctnt_uid,
				];
			$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		//var_dump($this->updt_status);die;
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Medical/payments/generate_admission_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Medical/payments/add_admission_fee', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Medical/payments/generate_admission_bill', $data);
		}
	} //function - Closed
	/****************************** Admimission Fee - END ******************************/

	// /****************** Clear Dues, Add Payment, Add Expenses Generate Bill- START ******************/
	// /****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/
	
	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- START ******************/
	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Render Payment form and save payement into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: 
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function add_patient_payment_old($id, $pid, $apmtid, $puid) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		
		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
			return redirect()->to(base_url() . 'Medical_Accountant/manage_patients');
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'get') { //Render payment form
			$args = [ 'id'  => $this->apmt_id ];
			$data['get_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
			//1 march 2025 added
			//start: added new code to handle if there is no data comes from database 
			if($data['get_patient']){
				return view('Medical/add_patient_payment', $data);
			}
			else{
				$this->session->setTempdata('error', 'There is no payment to receive this patient', 2);
				return  redirect()->to(base_url().'Medical_Accountant/all_patients');
			}
			//end
			//return view('Medical/add_patient_payment', $data);
		}
		else if ($this->request->getMethod() == 'post') { //Save Payment
			$rules = [ //Validation array
				'room_charge'      => 'required',
				'doc_fee'          => 'required',
				'med_cost'         => 'required',
				'other_cost'       => 'required'
			];
			if ($this->validate($rules)) { 
				$total_patient_paid_amount = $this->request->getVar('room_charge', FILTER_SANITIZE_STRING) + $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING) +$this->request->getVar('med_cost', FILTER_SANITIZE_STRING)+$this->request->getVar('other_cost', FILTER_SANITIZE_STRING);
				$this->user_data_arr = [
				/*	'puid'			=> '',
					'apmt_id'		=> '',
					'serial'		=> '',
					'ref_by'		=> '',
					'patient_name'	=> '',
					'age'			=> '',
					'ward'			=> '',
					'bed'			=> '',
					'pay_mode'		=> '',
					'transaction_id'=> '',
					'payee_id'		=> '',
					'payee_name'	=> '',
					'doctor_id'		=> '', */

					'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
					'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
					'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
					'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
					'paid_amount'  => $total_patient_paid_amount,
					'pid'           =>  $pid,
					'patients_id'	=> $id,
					'puid'			=> $this->puid, 
					'apmt_id'		=> $this->apmt_id,
					//'status'        =>  'Dues Cleared',
					'pay_date'      =>  date('Y-m-d'),
					'created_at'    =>  date('Y-m-d h:i:s'),
					'created_by'    => $this->acctnt_uid,
				];
				
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Medical_Accountant/generate_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Medical_Accountant/generate_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Mandatory validation failed', 2);
				$data['validation'] = $this->validator; 
			}
		}
		else {
		 	$this->session->setTempdata('error', 'Unexpected request method', 2);
		 	$data['validation'] = $this->validator;
			return redirect()->to(base_url().'Medical_Accountant/manage_patients');
	 	}
	} //function - Closed

	//== new code 17-03-2025
	/* @param: Function for Book patient/ self appointment 
     * @description: search_patient details from the list of the patients.
     * @date: 21st June, 2023
     * @modify: March 11th, 2025
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
	public function add_patient_payment($id, $pid, $apmtid, $puid) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		
		$this->acctnt_uid = session()->get('accountant_session_uid');

		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
				return redirect()->to(base_url() . 'Medical_Accountant/manage_patients');
		}
		$data = []; //for addressing notices
		
		
		$data['validation'] = null;
		$data['payments'] = $this->get_patient_final_payments_neo($id, $pid, $apmtid, $puid);;
		// ($data['payments']); exit;
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if ($this->request->getMethod() == 'get') { //Render payment form - with paid payment and hospital expence
				return view('Medical/add_patient_payment', $data);

		}
		else if ($this->request->getMethod() == 'post') { //Add /Receive payment
			$rules = [ //Validation array
				'receive_payment'       => 'required'
			];
			if ($this->validate($rules)) { 
				$this->room_charge = $this->request->getVar('room_charge', FILTER_SANITIZE_STRING) ?? '0.00';
				
				$this->doc_fee = $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING) ?? '0.00';
				$this->med_cost = $this->request->getVar('med_cost', FILTER_SANITIZE_STRING) ?? '0.00';
				$this->registration_fee = $this->request->getVar('registration_fee', FILTER_SANITIZE_STRING)??'0.00';
				$this->admission_fee = $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING) ?? '0.00';
				$this->receive_payment = $this->request->getVar('receive_payment', FILTER_SANITIZE_STRING) ?? '0.00';
				$this->other_cost = $this->request->getVar('other_cost', FILTER_SANITIZE_STRING) ?? '0.00';

				$this->total_paid_amount = $this->room_charge + $this->doc_fee + $this->med_cost + $this->registration_fee + $this->receive_payment + $this->admission_fee + $this->other_cost;
				
				$this->user_data_arr = [
					'room_charge'           =>  $this->room_charge,
					'doc_fee'               =>  $this->doc_fee,
					'med_cost'              =>  $this->med_cost,
					'other_cost'            =>  $this->other_cost,
					'registration_fee'		=>	$this->registration_fee,
					'admission_fee' 		=> 	$this->admission_fee,
					'paid_amount'           =>  $this->receive_payment,
					//'total_patient_paid_amount'	=> $this->total_paid_amount,
					'pay_mode'              =>  $this->request->getVar('pay_mode', FILTER_SANITIZE_STRING),
					'transaction_id'        =>  $this->request->getVar('transaction_id', FILTER_SANITIZE_STRING),
					'pay_date'              =>  $this->request->getVar('payment_date', FILTER_SANITIZE_STRING),
					'payment_note'          =>  $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'                   =>  $pid,
					'patients_id'	        => $id,
					'puid'			        => $this->puid, 
					'apmt_id'		        => $this->apmt_id,
					//'status'              =>  'Dues Cleared',
					'pay_date'              =>  date('Y-m-d'),
					'created_at'            =>  date('Y-m-d h:i:s'),
					'created_by'            => $this->acctnt_uid
				];
					
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
						return redirect()->to(base_url().'Medical_Accountant/generate_receive_payment_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
						return redirect()->to(base_url() . 'Medical_Accountant/generate_receive_payment_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Mandatory validation failed', 2);
				$data['validation'] = $this->validator; 
					return view('Medical/add_patient_payment', $data); 
				}
		}
		else {
		 	$this->session->setTempdata('error', 'Unexpected request method', 2);
		 	$data['validation'] = $this->validator;
				return redirect()->to(base_url().'Medical/manage_patients');
	 	}
	} //function - Closed

	/* @params: $id (patients tbl id), $pid (patient_login tbl id), 
   	* $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` 
	* tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for 
	* generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function get_patient_final_payments_neo($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 	
		$this->data = []; //for addressing notices
		$this->patient_id = $patient_id;
		$this->pid = $pid;
		$this->apmtid = $apmtid;
		$this->puid = $puid;

		if(!isset($this->apmtid) || $this->apmtid === 0 ) { return false; }		
		$args_apmt = [ 'apmt_id'  => $this->apmtid ];
		
		$this->pay_sums_qry = 'SUM(paid_amount) AS total_pay_amt, SUM(registration_fee) AS total_regis_amt, SUM(admission_fee) AS total_admission_amt'; //SQL Query
        $this->data['pay_sums'] = $this->medicine_model->fetch_pay_charges_sum_by_args('patients_pay_charges', $this->pay_sums_qry, $args_apmt);
        
        $this->xpnc_sums_qry = 'SUM(total_price) AS total_expnc_amt'; //SQL Query
        $this->data['xpnc_sums'] = $this->medicine_model->fetch_pay_charges_sum_by_args('treatment_expenses_history', $this->xpnc_sums_qry, $args_apmt);

        $this->data['pay_sums']['total_payable'] = (float) ($this->data['pay_sums']['total_pay_amt'] - $this->data['pay_sums']['total_regis_amt'] - $this->data['pay_sums']['total_admission_amt']);
        $this->data['pay_sums']['total_payable'] = number_format($this->data['pay_sums']['total_payable'], 2, '.', ''); //Convert into float value 
        
        $this->data['pay_sums']['pending_dues'] = $this->data['xpnc_sums']['total_expnc_amt'] - $this->data['pay_sums']['total_payable'];
        //Convert into float value
        $this->data['pay_sums']['pending_dues']= number_format($this->data['pay_sums']['pending_dues'], 2, '.', '');

        $args_apmt = [ 'id'  => $this->apmtid ];
        $this->data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);

		$args = [ 'id'  => $this->patient_id ];
		$this->data['patients'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		return $this->data;
	} //function - Closed
	

	/*@params: $id (patients tbl id), $pid (patient_login tbl id), 
	* $apmtid (appointment ID), $puid
	* @desc: Generate pdf format bill for patient provided payments 
	* @retuns: Internally used function
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_receive_payment_bill($patient_id, $payid, $apmtid, $puid) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('accountant_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Medical/generate_receved_payment', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Medical/generate_receved_payment', $data);
		}
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Generate pdf format bill for patient provided payments 
	* @retuns: Internally used function
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_patient_bill($patient_id, $payid, $apmtid, $puid) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		// $this->acctnt_uid = session()->get('accountant_session_uid');
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->acctnt_uid,
				];
		$this->updt_status = $this->medicine_model->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->medicine_model->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Medical/generate_apment_patient_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Medical/generate_apment_patient_bill', $data);
		}
	} //function - Closed
	

	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Show patient treatment expenses, done by hospital for patient
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function add_hospital_expenses($id, $pid, $amptid, $puid) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$data = [
				'id' 		=> $id,
				'pid' 		=> $pid,
				'apmt_id'	=> $amptid,
				'puid'		=> $puid,
			];
		return view('Medical/add_hospital_expenses', $data); 
	} //function - Closed


	/*@params: Ajax call
	* @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function save_hospital_expenses() {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		if ($this->request->getMethod() == 'post') {
			//$data['id'] = $id;
			$rules = [
					'medical_item_name' => 'required',
					'unit_price'    => 'required',
					//'quantity' => 'required',
					'tax'      => 'required',
					//'taxCalculation' => 'required',
					'date'     => 'required'
				];
				
			if ($this->validate($rules)) {
				$insdata = [
					'medical_item'      =>$this->request->getPost('medical_item_name'),
					'medical_code'      =>  $this->request->getPost('item_code'),
					'unit_price'        =>  $this->request->getPost('unit_price'),
					'total_Price'       =>  $this->request->getPost('totalPrice'),	
					'units'             =>  $this->request->getPost('quantity'),
					'tax_percentage'    =>  $this->request->getPost('tax'),
					'tax_amount'    	=>  $this->request->getPost('taxCalculation'),
					'discount_percentage' =>  $this->request->getPost('discount'),
					'discount_amount'   =>  $this->request->getPost('discount'),
					'patients_id'       =>  $this->request->getPost('patients_id'),
					'pid'        		=>  $this->request->getPost('pid'),
					'apmt_id'        	=>  $this->request->getPost('apmt_id'),
					'puid'        		=>  $this->request->getPost('puid'),
					'created_at'        =>  date('Y-m-d h:i:s'),
					'created_by'        =>  $this->acctnt_uid,
				];

				$status = $this->medicine_model->Insertdata('treatment_expenses_history', $insdata);
				if ($status === true) {
					$this->result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` with status `true`
						'code'	=> 200,
						'message' => 'Expenses added successfully',
						'data' => $insdata
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	=> true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message' => 'Failed! to add expenses',
						'data' => $insdata
					);
					return json_encode($this->result_arr);
				}
			} 
			else { 
				//$data['validation'] = $this->validator;
				$insdata['validation'] = $this->validator;
				$insdata = $insdata['validation']->getErrors();
				$this->result_arr = array(
					'status' => false,
					'error'	 => false, //error: `false` showing validation error autoamtically
					'code'	=> 200,
					'message' => 'Validation failed',
					'data' => $insdata
				);
				return json_encode($this->result_arr);
			}
		} 
		else {
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message'=> 'Unexpected request method',
				'data' 	 => $array(),
			);
			return json_encode($this->result_arr);
		}
	} //function - Closed

	/*@params: Ajax call
	* @desc: Show patient final (advance or pending) payments
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/

	public function show_patient_final_payments($patient_id, $pid, $apmtid, $puid) { // Check if $id is valid and represents a patient or a unique identifier.
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		$rules = [ // Define validation rules for the form
			'total_hospital_expenses'    => 'required|numeric',
			'total_patient_paid_amount' => 'required|numeric',
			'final_adjusted_amount'     => 'required|numeric',
			'remark'                    => 'required'
		];
	
		// Check if the request method is POST (form submission)
		if ($this->request->getMethod() == 'get') { 
			if ($this->validate($rules)) { // Validation successful -  Gather form input data
				$total_hospital_expenses = $this->request->getPost('total_hospital_expenses');
				$total_patient_paid_amount = $this->request->getPost('total_patient_paid_amount');
				$final_adjusted_amount = $this->request->getPost('final_adjusted_amount');
				$remark = $this->request->getPost('remark');

				// Calculate any necessary adjustments or refunds here
				// Adjust the final payment or perform other relevant actions
				// Redirect or display a success message
			} 
			else { // Validation failed, return to the form with validation errors
				$data['validation'] = $this->validator;
			}
		} 
		else { 
			$this->session->setTempdata('error', 'Unsupported request method', 3); 
		}

		//New code start to calculate sum
		$args = [ 'apmt_id'  => $apmtid ]; //where condition arguments
		
        $this->total_pay_amt_qry = 'SUM(paid_amount) AS total_pay_amt, SUM(registration_fee) AS total_regis_amt, SUM(admission_fee) AS total_admission_amt'; //SQL Query
        $this->data['total_pay_amt_data'] = $this->medicine_model->fetch_pay_charges_sum_by_args('patients_pay_charges', $this->total_pay_amt_qry, $args);
        $data['total_pay_amt'] = $this->data['total_pay_amt_data']['total_pay_amt'];

        $data['total_regis_amt'] = $this->data['total_pay_amt_data']['total_regis_amt'];

        $data['total_admission_amt'] = $this->data['total_pay_amt_data']['total_admission_amt'];
        //Total Patient paid amount - Total registration fee
        $data['total_payable'] = ($data['total_pay_amt'] - $data['total_regis_amt'] - $data['total_admission_amt']);
		
		$this->xpnc_qry = 'SUM(total_price) AS total_expnc_amt'; //SQL Query
        $data['total_expnc_amt'] = $this->medicine_model->fetch_pay_charges_sum_by_args('treatment_expenses_history', $this->xpnc_qry, $args);
		
		$args = [ 'id'  => $patient_id ];
		$data['patients'] = $this->medicine_model->fetch_rec_by_args('patients', $args);
		
		$args_apmt = [ 'id'  => $apmtid ];
        $data['get_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
		return view('Medical/show_patient_final_payments', $data);
	} //function - Closed

	// public function show_patient_final_payments($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
	// 	if (!(session()->has('accountant_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	// 	} 
	// 	$rules = [ // Define validation rules for the form
	// 		'total_hospital_expenses'    => 'required|numeric',
	// 		'total_patient_paid_amount' => 'required|numeric',
	// 		'final_adjusted_amount'     => 'required|numeric',
	// 		'remark'                    => 'required'
	// 	];
	
	// 	// Check if the request method is POST (form submission)
	// 	//if ($this->request->getMethod() == 'post') {
	// 	if ($this->request->getMethod() == 'get') {
	// 		if ($this->validate($rules)) { // Validation successful -  Gather form input data
	// 			$total_hospital_expenses = $this->request->getPost('total_hospital_expenses');
	// 			$total_patient_paid_amount = $this->request->getPost('total_patient_paid_amount');
	// 			$final_adjusted_amount = $this->request->getPost('final_adjusted_amount');
	// 			$remark = $this->request->getPost('remark');
	
	// 			// Calculate any necessary adjustments or refunds here
	// 			// Adjust the final payment or perform other relevant actions
	
	// 			// Redirect or display a success message
	// 		} 
			
	// 		else { // Validation failed, return to the form with validation errors
	// 			$data['validation'] = $this->validator;
	// 		}
	// 	} 
	// 	else { $this->session->setTempdata('error', 'Unsupported request method', 3); }

	// 	//$args = [ 'patients_id'  => $patient_id ]; //where condition arguments
	// 	$args = [ 'apmt_id'  => $apmtid ]; //where condition arguments
	// 	//$fld = 'total_patient_paid_amount'; //table field name
	// 	$fld = 'paid_amount'; //table field name
	// 	$data['total_pay_amt'] = $this->adminModel->fetch_sum_by_args('patients_pay_charges', $fld, $args);
	// 	if( $data['total_pay_amt'] === false ) { $data['total_pay_amt'] = 0; }

	// 	$fld = 'paid_amount'; //table field name
    //     $data['total_pay_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
    //     if( $data['total_pay_amt'] === false ) { $data['total_pay_amt'] = 0; }
    //     $fld = 'registration_fee'; //table field name
    //     $data['total_regis_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
    //     if( $data['total_regis_amt'] === false ) { $data['total_regis_amt'] = 0; }
    //     if(!isset($data['total_pay_amt'][0]->paid_amount) || (!isset($data['total_regis_amt'][0]->registration_fee))) {
    //         $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
    //     }
    //     $fld = 'admission_fee'; //table field name
    //     $data['total_admission_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
    //     if( $data['total_admission_amt'] === false ) { $data['total_admission_amt'] = 0; }
    //     if(!isset($data['total_pay_amt'][0]->paid_amount) || (!isset($data['total_regis_amt'][0]->registration_fee)) || (!isset($data['total_admission_amt'][0]->admission_fee))) {
    //         $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
    //     }
    //     //Total Patient paid amount - Total registration fee
    //     $data['total_payable'] = ($data['total_pay_amt'][0]->paid_amount - $data['total_regis_amt'][0]->registration_fee - $data['total_admission_amt'][0]->admission_fee);
	// 	$fld = 'total_price'; //table field name
	// 	$data['total_expnc_amt'] = $this->adminModel->fetch_sum_by_args('treatment_expenses_history', $fld, $args);
	// 	if( $data['total_expnc_amt'] === false ) { $data['total_expnc_amt'] = 0; }
		
	// 	$args = [ 'id'  => $patient_id ];
	// 	$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
	// 	$args_apmt = [ 'id'  => $apmtid ];
    //     $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);
	// 	return view('Medical/show_patient_final_payments', $data); // Pass the data to the view
	// 	//echo "<pre>";print_r($data['get_patient']);die;
	// } //function - Closed

	

   /* @params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	//public function clear_final_dues($patient_id, $pid, $apmtid, $puid) {//pid is patient_login tbl id
	public function clear_final_dues($patient_id, $pid, $apmtid, $puid, $status) {//pid is patient_login tbl id
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int)$patient_id; //patients tbl id
		$this->pid = (int)$pid; 
		$this->apmt_id = (int)$apmtid;
		$this->puid = $puid; 
		$this->status = $status;

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
					return redirect()->to(base_url() . 'Medical_Accountant/show_patient_final_payments/'  . $this->patient_id . '/'. $this->pid  . '/'. $this->apmt_id . '/'. $this->puid);
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
					'created_by'	=> $this->acctnt_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Medical_Accountant/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Medical_Accountant/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				}
			} 
			else { $data['validation'] = $this->validator; }
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Medical/discharge_appointment_pat', $data);
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	//public function generate_clear_dues_bill($patient_id, $pid,$last_inst_id, $payid, $apmtid, $puid) { //Ref generate_patient_bill()
	public function generate_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid, $status) { //Ref generate_patient_bill()
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$this->patient_id = (int)$patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		$this->status = $status;

		if($this->status == 'Admit') { $this->status = 1; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
		else if($this->status != 'Admit') { $this->status = 2; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient

		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {	
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Medical_Accountant/show_patient_final_payments/'  . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
		}
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
			'status'  => $this->status, //'Dues Cleared', 
			'created_at'  => date('Y-m-d H:i:s'),
			'created_by'  => $this->acctnt_uid,
		];
		
		$this->updt_status = $this->medicine_model->update_rec_by_args('patients', $args, $update_dt_arr);
		
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Medical/generate_clear_dues_bill', $data);
		}
		else if($this->updt_status === false) {
			$data['get_patient'] = [];
			$data['get_patient'][0] = new \stdClass; //stdClass object
			$data['get_patient'][0]->patient_name = '';
			$data['get_patient'][0]->mobile = '';
			$this->session->setTempdata('error', 'Failed ! to generate bill', 3);
			return view('Medical/generate_clear_dues_bill', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected patient update status', 3);
			return view('Medical/generate_clear_dues_bill', $data);
		}
	} //function - Closed

	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/
	
	public function add_company(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			return view('Medical/add_company');	
		}
	}

	public function add_appointment_pat_charge($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [ 'id'  => $id ];

		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'room_charge'      => 'required',
				'doc_fee'          => 'required',
				'med_cost'         => 'required',
				'other_cost'       => 'required'
			];
			if ($this->validate($rules)) {
				$total_patient_paid_amount = $this->request->getVar('room_charge', FILTER_SANITIZE_STRING) + $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING) +$this->request->getVar('med_cost', FILTER_SANITIZE_STRING)+$this->request->getVar('other_cost', FILTER_SANITIZE_STRING);
				$this->user_data_arr = [
					'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
					'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
					'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
					'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
					'total_patient_paid_amount'  => $total_patient_paid_amount,
					
					'pid'           =>  $id,
					'status'                =>  'Discharged',
					'pay_date'        =>  date('Y-m-d h:i:s')
				];
				$status = $this->medicine_model->Insertdata('patients_pay_charges', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulations ! Patient charges added successfully ', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Failed to add Patient charges ', 3);
					return redirect()->to(base_url() . 'Medical_Accountant/discharge_apmnt_patient/' . $id);
				}
				return redirect()->to(base_url() . 'Medical_Accountant/generate_apment_patient_bill/' . $id);
			} else {
				$data['validation'] = $this->validator;
			}
		}
		$data['patients'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
		return view('Medical/discharge_appointment_pat', $data);
	}

	
	public function discharge_apmnt_patient($id){
		//if (!(session()->has('accountant_session_uid'))) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  => $id
		];
		$data['patients'] = $this->medicine_model->fetch_rec_by_args('booked_doctor_appointment', $args);
		return view('Medical/discharge_appointment_pat', $data);
	}
	public function generate_apment_patient_bill($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$args = [
				'pid'  => $id
			];
			$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
			$args = [
				'id'  => $id
			];
			$value = [
				'status'  => 'Discharged'
			];
			$this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $args, $value);
			return view('Medical/generate_apment_patient_bill', $data);
		}
	}
	public function generate_apment_final_patient_bill($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$args = [
				'patients_id'  => $id
			];
			$data['payment_bill'] = $this->medicine_model->fetch_rec_by_args('patients_pay_charges', $args);
			$args = [
				'id'  => $id
			];
			$value = [
				'status'  => 'Discharged'
			];
			$this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $args, $value);
			return view('Medical/generate_apment_final_patient_bill', $data);
		}
	}

	public function discharge_summary($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		return view("Medical/discharge_summary");
	} //Function- Closed

	public function upload_company(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					//'company_name'      => 'required',
					'company_name'  => [
						'rules'     => 'required|min_length[4]|max_length[20]',
						'errors'    => [
						'required' => 'Company name is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
						],
					],
					//'company_c_num'     => 'numeric|exact_length[10]'
					'company_c_num'  => [
						'rules'     => 'required|min_length[4]|max_length[10]',
						'errors'    => [
						'required' => 'Company number is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 10.'
						],
					],
					'com_address'  => [
						'rules'     => 'required|min_length[4]|max_length[20]',
						'errors'    => [
						'required' => 'Company address is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
						],
					],
				];
				if ($this->validate($rules)) {
					$userdata = [
						'company_name'          =>  $this->request->getVar('company_name',FILTER_SANITIZE_STRING),
						'company_c_num'         =>  $this->request->getVar('company_c_num',FILTER_SANITIZE_STRING),
						'com_address'           =>  $this->request->getVar('com_address',FILTER_SANITIZE_STRING),
						//'category_image'        =>  '',
						'status'                =>  'Active', 
						'created_at'            =>   date('Y-m-d h:i:s')
					];
					// $status = $this->medicine_model->Insertdata('medicine_category', $userdata);
					$status = $this->medicine_model->Insertdata('medicine_company', $userdata);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! Company Added Successfully !', 3);
					}else{
						$this->session->setTempdata('error', 'Sorry ! Unable to Add  Company  Try Again ?', 3);
					}  
					return redirect()->to(base_url().'/Medical_Accountant/manage_company');	
				}else{
					$data['validation'] = $this->validator;
				}
			}
		}
		return view('Medical/add_company', $data);
	}

	public function manage_company() {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
			} else {
			$args = [
				'is_del' => 0,
				//'status'  => 'InActive',
			];
			$data = [
				'medicine_company' => $this->medicine_model->fetch_rec_by_args_arr('medicine_company', $args),
				'pager'     => $this->medicine_model->pager
			];
			if(!isset($data['medicine_company']) || $data['medicine_company'] == '') { 
				$this->session->setTempdata('error', 'Sorry ! No Company found. Please add company name', 3);
				return redirect()->to(base_url()."Medical_Accountant/add_company");
			}
			
			return view('Medical/manage_company', $data);
			}
	}
	
	public function filter_medicine_cpmpany($filter){
		if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		if ($filter == 'new_medicine') {

			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_medicine') {
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
		$args = [
			//'status'        => 'Active'
				'is_del' => 0
		];
		$data = [
			'medicine_company' => $this->medicine_model->filter_rec_by_args_with_pagination('medicine_company', $order, $args),
			'pager'     => $this->medicine_model->pager
		];
		if (!isset($data['medicine_company']) || $data['medicine_company'] == '') {
			session()->setTempdata('error', 'No company name found, please add company first', 2);
			return redirect()->to(base_url() . "Medical_Accountant/manage_company");
		}
		// echo"<pre>";print_r($data);die;
		return view('Medical/manage_company', $data);
		// return view('Medical/manage_doctor', $data);
	}


	public function delete_company_old($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  => $id
		];
		// $status = $this->medicine_model->delete_records('medicine_category', $args);
		$status = $this->medicine_model->delete_records('medicine_company', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Company Deleted Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted  Company  Try Again ?', 3);
		}
		return redirect()->to(base_url().'/Medical_Accountant/manage_company');
	}

	/* @params: Function for permanent delete company
	* @desc: Admin can soft delete company also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 27th Feb,2025
	* @modify
	*/

	public function delete_company($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			//'updated_by' => $this->acctnt_uid
		];


		$status = $this->commonForAllModel->update_rec_by_args('medicine_company',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Company Deleted Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted  Company  Try Again ?', 3);
		}
		return redirect()->to(base_url().'/Medical_Accountant/manage_company');
	}

	
	/* @params: Function for permanent delete company
	* @desc: Admin can soft delete company also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_company($id){
		$this->acctnt_uid = session()->get('accountant_session_uid');
		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Medical Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  =>  $id
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->acctnt_uid
		];


		$status = $this->commonForAllModel->update_rec_by_args('department',  $args, $data);

		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Medical_Accountant/manage_department');
	}

	public function search_company(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
	
		$keyword = $this->request->getVar('company_name', FILTER_SANITIZE_STRING);
	
		// Remove spaces from the user input
		$keyword = str_replace(' ', '', $keyword);
	
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
	
		if ($keyword) {
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('medicine_category', 'company_name', $keyword, $args);
		} else {
			$result = $this->commonForAllModel;
		}
	
		$data = [
			'medicine_company' => $this->commonForAllModel->fetch_rec_by_args_with_status('medicine_category', $args),
			'pager' => $this->commonForAllModel->pager
		];
	
		return view("Medical/manage_company", $data);
	}
	
	public function change_company_status($id, $status){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status' => $status
		];

		// $status = $this->commonForAllModel->update_rec_by_args('medicine_category', $args, $data);
		$status = $this->commonForAllModel->update_rec_by_args('medicine_company', $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Company Status Updated  Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated  Try Again ?', 3);
		}
		return redirect()->to(base_url().'/Medical_Accountant/manage_company');
	}


	public function edit_company_name($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'  => $id
		];
		// $data['edit_company'] = $this->medicine_model->fetch_rec_by_args('medicine_category', $args);
		$data['edit_company'] = $this->medicine_model->fetch_rec_by_args('medicine_company', $args);
		return view('Medical/edit_company_name', $data);
	}

	public function update_company($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args  = [
			'id'  => $id
		];

		$data = [
			'company_name'          =>  $this->request->getVar('company_name',FILTER_SANITIZE_STRING),
			'company_c_num'         =>  $this->request->getVar('company_c_num',FILTER_SANITIZE_STRING),
			'com_address'           =>  $this->request->getVar('com_address',FILTER_SANITIZE_STRING),
			'status'                =>  'Active', 
			'created_at'            =>   date('Y-m-d h:i:s')
		];

		// $status = $this->commonForAllModel->update_rec_by_args('medicine_category', $args, $data);
		$status = $this->commonForAllModel->update_rec_by_args('medicine_company', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Company Records Updated  Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated  Try Again ?', 3);
		}
		return redirect()->to(base_url().'/Medical_Accountant/manage_company/'.$id);
	}

	/*
	public function add_medicine(){ 
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			// $data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			//$data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			$data['medicines']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			//echo "<pre>";print_r($data['company']);die;
			return view('Medical/add_medicine', $data);
		}
	}
	*/

	public function add_medicine(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		$args = ['is_del' => 0];
		$data['medicine_category'] = $this->commonForAllModel->fetch_rec_by_args('medicine_category',$args);
		$data['medicine_company'] = $this->commonForAllModel->fetch_rec_by_args('medicine_company',$args);
		if($data['medicine_category'] === false) {
			$this->session->setTempdata('error', 'Sorry ! No medicine category is found.', 3);
		}
		if($data['medicine_company'] === false) {
			$this->session->setTempdata('error', 'Sorry ! No medicine company is found.', 3);
		}
		return view('Medical/add_medicine', $data);
	}

	

	// public function upload_medicine(){
	// 	if (!(session()->has('accountant_session_uid'))) {
	// 		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	// 	}
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'med_company'      => 'required',
	// 			'med_price'          => 'required',
	// 			'med_name'           => 'required'
				
	// 		];
	// 		if ($this->validate($rules)) {
	// 			 $img = $this->request->getFile('med_image');
	// 			if ($img->isValid() &&  !$img->hasMoved()) {
	// 			 	 $newName = $img->getRandomName();
    //             	#$img->move(FCPATH . 'uploads/medicine_image', $newName); 
    //             	$img->move(FCPATH . 'uploads/medicine_image', $newName); 
                	
    //             	$med_img = $img->getName();
	// 				$this->user_data_arr = [
	// 					'med_company'           =>  $this->request->getVar('med_company',FILTER_SANITIZE_STRING),
	// 					'med_price'             =>  $this->request->getVar('med_price',FILTER_SANITIZE_STRING),
	// 					'med_d_price'           =>  $this->request->getVar('med_d_price',FILTER_SANITIZE_STRING),
	// 					'med_name'              =>  $this->request->getVar('med_name',FILTER_SANITIZE_STRING),
	// 					'med_stock'              =>  $this->request->getVar('med_dis',FILTER_SANITIZE_STRING),
	// 					'med_image'             =>  $med_img,
	// 					'status'                => 'Active', 
	// 					'created_at'            =>  date('Y-m-d h:i:s')
	// 				];
	// 				// $status = $this->medicine_model->Insertdata('medicines', $this->user_data_arr);
	// 				$status = $this->medicine_model->Insertdata('medicines', $this->user_data_arr);
	// 				if ($status == true) {
	// 					$this->session->setTempdata('success', 'Congratulation ! Medicine  added successfully', 3);
	// 				}else{
	// 					$this->session->setTempdata('error', 'Sorry ! Unable to add Medicine. Please  try again.', 3);
	// 				}  
	// 				return redirect()->to(base_url().'Medical_Accountant/manage_medicine');

	// 			}else{
	// 			 	echo $image->getErrorString(). " " .$image->getError();
	// 			}
				
	// 		}else{
	// 			$data['validation'] = $this->validator;
	// 		}
	// 	}

	// 	// $data['medicines'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
	// 	$data['medicines'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
	// 	return view('Medical/manage_medicine',$data);
	// }

// public function upload_medicine(){
// 		if (!(session()->has('accountant_session_uid'))) {
// 			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
// 		}
// 		$data = [];
// 		$data['validation'] = null;
// 		if ($this->request->getMethod() == 'post') {
// 			$rules = [
// 				'med_company'      => 'required',
// 				'med_price'          => 'required',
// 				'med_name'           => 'required'
// 				];
// 			if ($this->validate($rules)) {
// 				$img = $this->request->getFile('med_image');
// 				if ($img->isValid() &&  !$img->hasMoved()) {
// 					$newName = $img->getRandomName();
// 					#$img->move(FCPATH . 'uploads/medicine_image', $newName); 
// 					$img->move(FCPATH . 'uploads/medicine_image', $newName);

// 					$med_img = $img->getName();
// 					$this->user_data_arr = [
// 						'med_company'           =>  $this->request->getVar('med_company', FILTER_SANITIZE_STRING),
// 						'med_price'             =>  $this->request->getVar('med_price', FILTER_SANITIZE_STRING),
// 						'med_d_price'           =>  $this->request->getVar('med_d_price', FILTER_SANITIZE_STRING),
// 						'med_name'              =>  $this->request->getVar('med_name', FILTER_SANITIZE_STRING),
// 						'med_stock'             =>  $this->request->getVar('med_dis', FILTER_SANITIZE_STRING),
// 						'expiry_date'           =>  $this->request->getVar('med_exp_date', FILTER_SANITIZE_STRING),
// 						'med_image'             =>  $med_img,
// 						'status'                => 'Active',
// 						'created_at'            =>  date('Y-m-d h:i:s')
// 					];
// 					//echo "<pre>";print_r($this->user_data_arr);die;
// 					$status = $this->medicine_model->Insertdata('medicines', $this->user_data_arr);
// 					if ($status == true) {
// 						$this->session->setTempdata('success', 'Congratulation ! Medicine  added successfully', 3);
// 					} else {
// 						$this->session->setTempdata('error', 'Sorry ! Unable to add Medicine. Please  try again.', 3);
// 					}
// 					//return redirect()->to(base_url().'/Admin/add_medicine'); //Redirect current/same page
// 					return redirect()->to(base_url() . 'Medical_Accountant/manage_medicine');
// 				} else {
// 					echo $image->getErrorString() . " " . $image->getError();
// 				}
// 			} else {
// 				$data['validation'] = $this->validator;
// 			}
// 		}
// 		$data['medicines'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
// 		return view('Medical/add_medicine', $data);
// 	}


// 	public function upload_products(){
// 		if (!(session()->has('accountant_session_uid'))) {
// 			return redirect()->to(base_url()."/Accountant_login/accountant_login");
// 		}else{
// 			$data = [];
// 			$data['validation'] = null;
// 			if ($this->request->getMethod() == 'post') {
// 				$rules = [
// 					'product_name'      => 'required',
// 					'company_name'  => 'required',
// 					'unit_price'    => 'required',
// 					'stock'         => 'required',
// 					'expiry_date'   => 'required',
// 					'batch_number'  => 'required'
// 				];
// 				if ($this->validate($rules)) {
// 					$userdata = [
// 						'med_name'           =>  $this->request->getVar('product_name',FILTER_SANITIZE_STRING),
// 						'med_company'        =>  $this->request->getVar('company_name',FILTER_SANITIZE_STRING),
// 						'med_price'          =>  $this->request->getVar('unit_price',FILTER_SANITIZE_STRING),
// 						'med_stock'            =>  $this->request->getVar('stock',FILTER_SANITIZE_STRING),
// 						'med_d_price'        =>  $this->request->getVar('med_dis',FILTER_SANITIZE_STRING),
// 						'expiry_date'        =>  $this->request->getVar('expiry_date',FILTER_SANITIZE_STRING),
// 						'batch_number'       =>  $this->request->getVar('batch_number',FILTER_SANITIZE_STRING),
// 						'med_image'          =>  '',
// 						'status'             =>  'Active', 
// 						'created_at'         =>   date('Y-m-d h:i:s')
// 					];
// 					// $status = $this->medicine_model->Insertdata('medicines', $userdata);
// 					$status = $this->medicine_model->Insertdata('medicines', $userdata);
// 					if ($status == true) {
// 						$this->session->setTempdata('success', 'Congratulation ! Product Added Successfully !', 3);
// 					}else{
// 						$this->session->setTempdata('error', 'Sorry ! Unable to Add  Product  Try Again ?', 3);
// 					}  
// 					return redirect()->to(base_url().'/Medical_Accountant/add_medicine');	
// 				}else{
// 					$data['validation'] = $this->validator;
// 				}
// 			}
// 		}
		
// 		// $data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
// 		$data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
// 		return view('Medical/add_medicine', $data);
// 	}


// 	public function manage_medicine(){
// 	if (!(session()->has('accountant_session_uid'))) {
// 		return redirect()->to(base_url()."/Accountant_login/accountant_login");
// 	}
	
// 	$args = [
// 		'is_del' => 0
// 	];
// 	$data = [
		
// 		// 'medicines'  => $this->medicine_model->fetch_rec_by_args_with_status('medicines', $args),
// 		'medicines'  => $this->medicine_model->fetch_rec_by_args_with_status('medicines', $args),
// 		'pager'   => $this->medicine_model->pager
// 	];
// 	//echo "<pre>"; print_r($data);die;
// 	if(!isset($data['medicines']) || $data['medicines'] == '') { //If no doctor found
// 		$this->session->setTempdata('error', 'Sorry ! No medicine found. Please add new medince first', 3);
// 		return redirect()->to(base_url()."Medical_Accountant/add_medicine");
// 	}
	
// 	return view("Medical/manage_medicine", $data);
// }
public function upload_medicine(){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	}
	$data = [];
	$data['validation'] = null;
	if ($this->request->getMethod() == 'post') {
		$rules = [
			//'med_company'      => 'required',
			 'med_company'  => [
			 	'rules'     => 'required|min_length[4]|max_length[20]',
			 	'errors'    => [
			 	'required' => 'Medicine Company name is mandatory.',
			 	'min_length' => 'Minimum length is 4.',
			 	'max_length' => 'Maximum length is 20.'
			 	],
			 ],
			
			 'med_category'  => [
			 	'rules'     => 'required|min_length[4]|max_length[20]',
			 	'errors'    => [
			 	'required' => 'Medicine Category name is mandatory.',
			 	'min_length' => 'Minimum length is 4.',
			 	'max_length' => 'Maximum length is 20.'
			 	],
			 ],
			
			//'med_price'          => 'required',
			'med_price'  => [
				'rules'     => 'required',
				'errors'    => [
				'required' => 'Medicine price is mandatory.',
				],
			],
			//'med_name'           => 'required'
			'med_name'  => [
				'rules'     => 'required|min_length[4]|max_length[50]',
				'errors'    => [
				'required' => 'Medicine name  is mandatory.',
				'min_length' => 'Minimum length is 4.',
				'max_length' => 'Maximum length is 50.'
				],
			],

			'med_exp_date'  => [
				'rules'     => 'required',
				'errors'    => [
				'required' => ' Expiry Date	is mandatory.',
				
				],
			],

			'med_image' => [
				'rules'     => 'uploaded[med_image]|max_size[med_image,' . ALLOW_MAX_UPLOAD .']|is_image[med_image]|mime_in[med_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[med_image,png,jpg,jpeg, svg, gif]',
				'errors' => [
					'uploaded'  => 'Medicine Image is mandatory.',
					'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
					'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
					'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
				],
			],

			'batch_number'  => [
				'rules'     => 'required|min_length[4]|max_length[50]',
				'errors'    => [
				'required' => 'Batch number  is mandatory.',
				'min_length' => 'Minimum length is 4.',
				'max_length' => 'Maximum length is 50.'
				],
			],

			'med_stock'  => [
				'rules'     => 'required',
				'errors'    => [
				'required' => 'Medical stock is mandatory.',
				
				],
			],
			
		];
		if ($this->validate($rules)) {
			$img = $this->request->getFile('med_image');
			if ($img->isValid() &&  !$img->hasMoved()) {
				$newName = $img->getRandomName();
				#$img->move(FCPATH . 'uploads/medicine_image', $newName); 
				$img->move(FCPATH . 'uploads/medicine_image', $newName);

				$med_img = $img->getName();
				$this->user_data_arr = [
					'med_company'           =>  $this->request->getVar('med_company', FILTER_SANITIZE_STRING),
					'med_category'           =>  $this->request->getVar('med_category', FILTER_SANITIZE_STRING),
					'med_price'             =>  $this->request->getVar('med_price', FILTER_SANITIZE_STRING),
					'med_d_price'           =>  $this->request->getVar('med_d_price', FILTER_SANITIZE_STRING),
					'med_name'              =>  $this->request->getVar('med_name', FILTER_SANITIZE_STRING),
					'med_stock'             =>  $this->request->getVar('med_dis', FILTER_SANITIZE_STRING),
					'expiry_date'           =>  $this->request->getVar('med_exp_date', FILTER_SANITIZE_STRING),
					'other_detail'          =>  $this->request->getVar('other_detail', FILTER_SANITIZE_STRING),
					'med_image'             =>  $med_img,
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				//echo "<pre>";print_r($this->user_data_arr);die;
				$status = $this->medicine_model->Insertdata('medicines', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Medicine  added successfully', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to add Medicine. Please  try again.', 3);
				}
				//return redirect()->to(base_url().'/Admin/add_medicine'); //Redirect current/same page
				return redirect()->to(base_url() . 'Medical_Accountant/manage_medicine');
			} else {
				echo $image->getErrorString() . " " . $image->getError();
			}
		} else {
			$data['validation'] = $this->validator;
		}
	}
	$data['medicines'] = $this->medicine_model->fetch_all_records_by_active('medicines');
	return view('Medical/add_medicine', $data);
}


public function upload_products(){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	}else{
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'product_name'      => 'required',
				'company_name'  => 'required',
				'unit_price'    => 'required',
				'stock'         => 'required',
				'expiry_date'   => 'required',
				'batch_number'  => 'required'
			];
			if ($this->validate($rules)) {
				$userdata = [
					'med_name'           =>  $this->request->getVar('product_name',FILTER_SANITIZE_STRING),
					'med_company'        =>  $this->request->getVar('company_name',FILTER_SANITIZE_STRING),
					'med_price'          =>  $this->request->getVar('unit_price',FILTER_SANITIZE_STRING),
					'med_stock'            =>  $this->request->getVar('stock',FILTER_SANITIZE_STRING),
					'med_d_price'        =>  $this->request->getVar('med_dis',FILTER_SANITIZE_STRING),
					'expiry_date'        =>  $this->request->getVar('expiry_date',FILTER_SANITIZE_STRING),
					'batch_number'       =>  $this->request->getVar('batch_number',FILTER_SANITIZE_STRING),
					'med_image'          =>  '',
					'status'             =>  'Active', 
					'created_at'         =>   date('Y-m-d h:i:s')
				];
				// $status = $this->medicine_model->Insertdata('medicines', $userdata);
				$status = $this->medicine_model->Insertdata('medicines', $userdata);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Product Added Successfully !', 3);
				}else{
					$this->session->setTempdata('error', 'Sorry ! Unable to Add  Product  Try Again ?', 3);
				}  
				return redirect()->to(base_url().'/Medical_Accountant/add_medicine');	
			}else{
				$data['validation'] = $this->validator;
			}
		}
	}
	
	// $data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
	$data['company']  = $this->medicine_model->fetch_all_records_by_active('medicine_category');
	return view('Medical/add_medicine', $data);
}


	public function manage_medicine(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");}
		$args = [
			'is_del' => 0
		];
		$data = [
			// 'medicines'  => $this->medicine_model->fetch_rec_by_args_with_status('medicines', $args),
			'medicines'  => $this->medicine_model->fetch_rec_by_args_with_status('medicines', $args),
			'pager'   => $this->medicine_model->pager
		];
		//echo "<pre>"; print_r($data);die;
		if(!isset($data['medicines']) || $data['medicines'] == '') { //If no doctor found
			$this->session->setTempdata('error', 'Sorry ! No medicine found. Please add new medince first', 3);
			return redirect()->to(base_url()."Medical_Accountant/add_medicine");}
		return view("Medical/manage_medicine", $data);
	}


	public function show_products($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'med_company'  => $id,
				'expiry_date>='  => date('Y-m-d '),
				'status'       => 'Active'
			];
			$data = [
	            'medicines' => $this->medicine_model->fetch_rec_by_args_with_status('medicines', $args),
	            'pager'     => $this->medicine_model->pager
	        ];
			return view("Medical/manage_medicine", $data);
		}
	}

	public function search_medicine() {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$keyword = $this->request->getVar('medicine_name', FILTER_SANITIZE_STRING);
			
			// Remove spaces from the user input
			$keyword = str_replace(' ', '', $keyword);
	
			$args = [
				// 'expiry_date>='  => date('Y-m-d h:i:s'), // Expiry is not MUST for search result
			];
	
			if ($keyword) {
				// Search for medicines with spaces removed
				$result = $this->medicine_model->search_med_name($keyword, $args);
			} else {
				$result = $this->medicine_model;
			}
	
			$data = [
				'medicines' => $this->medicine_model->fetch_rec_by_expiry_medicine('medicines', $args),
				'pager'     => $this->medicine_model->pager
			];
	
			return view("Medical/manage_medicine", $data);
		}
	}
	
	public function filter_medicine($filter) { 
	// 	if ($filter == 'new_medicine') {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'asc'
				
	// 		];
	// 	}else if ($filter == 'old_medicine') {
	// 		$order = [
				
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	}else{
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	}
	// 	$args = [
	// 		//'expiry_date>=' => date('Y-m-d h:i:s'), 
	// 		'status'        => 'Active'

	// 	];
	// 	$data = [
	// 		'medicines' => $this->medicine_model->filter_rec_by_args_with_pagination('medicines', $order, $args),
	// 		'pager'     => $this->medicine_model->pager
	// 	];
	// 	//echo "<pre>"; print_r($data);die;
	// 	//return view("Medical/manage_medicine", $data);
	// 	return view("Medical/manage_medicine", $data);
	// }
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	}
	if ($filter == 'new_medicine') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
		
	}
	else if ($filter == 'old_medicine') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'asc'
		];
	}
	else{
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
	}
	
	
	$data = [
		   'medicines' => $this->medicine_model->filter_rec_by_paginate('medicines', $order),
		'pager'   => $this->medicine_model->pager
	];
	
	return view("Medical/manage_medicine", $data);
}


	public function edit_medicine($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id
			];
			//$data['medicines'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			$data['medicines'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			$data['medicine'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			return view('Medical/edit_medicine', $data);
			//return view('Medical/manage_medicine', $data); //Generating error
		}
	}

	public function update_medicines($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id
			];
			 $userdata = [
				'med_company'          =>  $this->request->getVar('med_company',FILTER_SANITIZE_STRING),
				'med_price'            =>  $this->request->getVar('med_price',FILTER_SANITIZE_STRING),
				'med_d_price'          =>  $this->request->getVar('med_d_price',FILTER_SANITIZE_STRING),
				'med_stock'            =>  $this->request->getVar('med_dis',FILTER_SANITIZE_STRING),
				'expiry_date'          =>  $this->request->getVar('expiry_date',FILTER_SANITIZE_STRING),
				'batch_number'         =>  $this->request->getVar('batch_number',FILTER_SANITIZE_STRING),
				'med_name'             =>  $this->request->getVar('med_name',FILTER_SANITIZE_STRING),
				'status'               => 'Active', 
				'created_at'           =>  date('Y-m-d h:i:s')
			];
			// $status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $userdata);
			$status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $userdata);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Medicines  Updated Successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to Update Medicines  Try Again ?', 3);
			} 
			$data['medicine'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			return redirect()->to(base_url().'Medical_Accountant/manage_medicine');
		}
	}

	public function add_medicine_stock($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id
				//'expiry_date>=' => date('Y-m-d ')
			];
			// $data['medicines'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			$data['medicines'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			return view('Medical/add_medicine_stock', $data);
		}
	}

	public function filter_patients_dis_pat($filter){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'status'        => 'Discharged'
		];
		$data = [
			'patients' => $this->medicine_model->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->medicine_model->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Medical_Accountant/add_patients");}
		return view("Medical/all_patients", $data);
	} //Function-Closed

	public function filter_deleted_pat($filter){
		//echo "<pre>";print_r($filter);die;
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'status'        => 'Deleted'
		];
		$data = [
			'patients' => $this->medicine_model->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->medicine_model->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Medical_Accountant/add_patients");
		}
		 //echo"<pre>";print_r($data);die;
		return view("Medical/all_patients", $data);
	}

	public function filter_admitted_pat($filter){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'status'        => 'Admit'
		];
		$data = [
			'patients' => $this->medicine_model->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->medicine_model->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Medical_Accountant/add_patients");}
		return view("Medical/all_patients", $data);
	}

	public function delete_medicine($id){	//Added later
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
			$args = [
			'id'  =>  $id
		];
		// $status = $this->medicine_model->delete_records('medicines', $args);
		$status = $this->medicine_model->delete_records('medicines', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! medicine Deleted Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted try again', 3);
		}
		return redirect()->to(base_url().'Medical_Accountant/manage_medicine/');
	}
/* @params: Function for permanent delete medicine
* @desc: Admin can soft delete medicine also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
public function permanent_del_medicine($id){
	$this->acctnt_uid = session()->get('accountant_session_uid');
	if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
		$this->session->setTempdata('error', 'Medical Accountant UID is missing !', 3);
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	}
	$args = [
		'id'  =>  $id
	];
	$data = [
		'is_del' => 1,
		'status' => 'Deleted', //0: Non deleted, 1: Deleted
		'updated_at' => date('Y-d-m h:i:s'),
		'updated_by' => $this->acctnt_uid
	];


	$status = $this->commonForAllModel->update_rec_by_args('department',  $args, $data);

	if ($status == true) {
		$this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
	} else {
		$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
	}
	return redirect()->to(base_url() . 'Medical_Accountant/manage_medicine');
}
	public function change_medicine_status($id, $status){	//Added later
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args  = [
			'id' => $id
		];
		$data = [
			'status'  => $status
		];
		// $status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
		$status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Medicine has  changed successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to update, please try again', 3);
		}
		return redirect()->to(base_url().'Medical_Accountant/manage_medicine/');
	}
	
	
	public function update_stock($id) { 
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [ 'id'  => $id ];

			$data = [
				'med_stock' => $this->request->getVar('med_stock',FILTER_SANITIZE_STRING)
			];
			
			// $status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
			$status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
			
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! medicines  stock has updated successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to update medicine stock.  Please try again.', 3);
			} 
			return redirect()->to(base_url().'Medical_Accountant/manage_medicine');
		}
	}

	public function expiry_products(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'expiry_date <='  => date('Y-m-d'),	
				'is_del'=> 0
			];
			$data = [
	            'medicines' => $this->medicine_model->fetch_rec_by_expiry_medicine('medicines', $args),
	            'pager'     => $this->medicine_model->pager
	        ];
			return view('Medical/expiry_products', $data);
		}
	}

	public function search_today_appointments(){
		if (!(session()->has('accountant_session_uid'))) {  
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} 
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		$args = [
			//'status'  => 'Active'
			//'updated_at' => date('Y-d-m h:i:s'),
			'booking_date' => date('Y-m-d'),
			'is_del' => 0
		];
		// Define the field to search
		$srch_field = 'patient_name'; // Searching table field name
		if ($keyword) {
			// Search for data with spaces removed
			$this->result_arr = $this->medicine_model->search_records('booked_doctor_appointment', $srch_field, $keyword, $args);
		} else {  
			$this->result_arr = $this->medicine_model;}   
		$data = [
			'today_apmnt' => $this->medicine_model->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->medicine_model->pager
	  	];
		return view('Medical/today_appointment', $data);
	}

	//public function filter_today_appointment($filter){
// 		if (!(session()->has('accountant_session_uid'))) {
// 		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
// 		}
// 	if ($filter == 'new_patient') {
// 		$order = [
// 			'column_name' => 'id',
// 			'order' => 'desc'
// 		];
// 	} else if ($filter == 'old_patient') {
// 		$order = [
// 			'column_name' => 'id',
// 			'order' => 'asc'
// 		];
// 	} else {
// 		$order = [
// 			'column_name' => 'id',
// 			'order' => 'desc'
// 		];
// 	}
// 	$args = [
// 		'is_del'=>0,
// 		//'status' => 1,//0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
// 		'booking_date' => date('Y-m-d')
// 	];
// 	$data = [
// 		'today_apmnt' => $this->medicine_model->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
// 		'pager' => $this->medicine_model->pager
// 	];
	
// 	return view('Medical/today_appointment', $data);
// } //Function - Closed
public function filter_today_appointment($filter){

	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	}
	//echo "<pre>";print_r($filter);die;
	if ($filter == 'new_patient') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
	} else if ($filter == 'old_patient') {
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

	$args = ['is_del'        => 0,
			'booking_date' => date('Y-m-d')];
	$data = [
		'today_apmnt' => $this->medicine_model->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order ,$args),
		'pager'   => $this->medicine_model->pager
	];
	
	return view('Medical/today_appointment', $data);
}
	public function delete_expiry_medicine($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id
			];
			// $status = $this->medicine_model->delete_records('medicines', $args);
			$status = $this->medicine_model->delete_records('medicines', $args);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Expiry Medicines Products Deleted Successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to delete() Medicines  Try Again ?', 3);
			} 
	        
	        return redirect()->to(base_url().'/Medical_Accountant/expiry_products');
		}
	}

	public function change_today_appointments_status($id, $status, $pid, $puid, $serial){
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->patient_id = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 

		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}

		$this->acctnt_uid = session()->get('accountant_session_uid');

		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} //else { $this->doctor_uid =  $this->doctor_uid_arr['uid']; }

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

		$this->data = [
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->acctnt_uid,
		];

		if ($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->patient_id > 0) {
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->args['id']  = $this->patient_id;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) {
				/***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
			}
		} else { //Update appointments only
			// $status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/today_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed

	public function canceled_appointments(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$args = [
				'status'  => 0,//0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
				'is_del'  => 0,
	
			];
			$data = [
				'cancelled_apmt' => $this->medicine_model->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
				'pager'     => $this->medicine_model->pager
			];
			return view("Medical/cancelled_appointments", $data);}
		}

		public function filter_del_appointments($filter){
		
			if (!(session()->has('accountant_session_uid'))) {
				return redirect()->to(base_url() . "/Accountant_login/accountant_login");
			}
			$args = [
				'status'      => 0, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
				'is_del'=>0
			];
			if ($filter == 'new_patient') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			} else if ($filter == 'old_patient') {
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
			
			$data = [
				'cancelled_apmt' => $this->medicine_model->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
				'pager'   => $this->medicine_model->pager
			];
			
			return view('Medical/cancelled_appointments', $data);
		}

		public function change_cancelled_appointments_status($id, $status, $pid, $puid, $serial){
			$this->apmt_id = (int) $id; //appointment ID
			$this->status  = (int) $status; //Status
			$this->patient_id = (int)$pid; //Patient ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
	
			
			if (!(session()->has('accountant_session_uid'))) {
				return redirect()->to(base_url() . "/Accountant_login/accountant_login");
			}
	
			$this->acctnt_uid = session()->get('accountant_session_uid');
	
			if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
				$this->session->setTempdata('error',  'Accountant UID is missing !', 3);
				return redirect()->to(base_url() . "/Admin");
			} //else { $this->acctnt_uid =  $this->acctnt_uid_arr['uid']; }
	
			$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table
	
			$this->data = [
				'status'  => $status,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->acctnt_uid,
			];
	
			if ($this->status === 0) { //Update appointments & insert patient info 
				/***** Get Patient detail thru `puid` - START *****/
				if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
					$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
					if ($this->updt_rslt === true) {
						$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					} else {
						$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					}
				}
				/***** Get Patient detail thru `puid` - END *****/
				else if ($this->patient_id > 0) {
					/*****  Get Patient detail thru `paitient ID` - START *****/
					//$this->args['id']  = $this->patient_id;
					$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
					if ($this->updt_rslt === true) {
						$this->session->setTempdata('success', 'Congratulation @! Appointment status change successfully', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					} else {
						$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					}
				}
				/***** Get Patient detail thru `paitient ID` - END *****/
				else if ($this->apmt_id != '' && $this->apmt_id > 0) {
					/***** Get Patient detail thru `Appointment ID` - START *****/
					$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->acctnt_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
					if ($this->updt_rslt === true) {
						$this->session->setTempdata('success', 'Congratulation, Appointment status has changed successfully', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					} else {
						$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
					}
				}
				/***** Get Patient detail thru `Appointment ID` - END *****/
				else {
					$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
				}
			} else { //Update appointments only
				$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
				if ($status === true) {
					$this->session->setTempdata('success', 'Congratulation 2! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
					return redirect()->to(base_url() . '/Medical_Accountant/canceled_appointments');
				}
			} //else loop - closed
		} //Funtion - Closed

	public function change_exp_medicine_status($id, $status){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id
			];

			$data = [
				'status' => $status
			];

			// $status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
			$status = $this->commonForAllModel->update_rec_by_args('medicines', $args, $data);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Expiry Status Change  Successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to Updated  Try Again ?', 3);
			}
			return redirect()->to(base_url().'/Medical_Accountant/expiry_products');
		}
	}

	public function search_exp_medicine() {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$keyword = $this->request->getVar('medicine_name', FILTER_SANITIZE_STRING);
	
			// Remove spaces from the user input
			$keyword = str_replace(' ', '', $keyword);
	
			$args = [
				'expiry_date<=' => date('Y-m-d '),
				'status' => 'Active'
			];
	
			if ($keyword) {
				// Search for medicines with spaces removed
				$result = $this->medicine_model->search_med_name($keyword, $args);
			} else {
				$result = $this->medicine_model;
			}
	
			$data = [
				'medicines' => $this->medicine_model->fetch_rec_by_expiry_medicine('medicines', $args),
				'pager' => $this->medicine_model->pager
			];
	
			return view('Medical/expiry_products', $data);
		}
	}
	

	public function filter_exp_medicine($filter){
	// 	if (!(session()->has('accountant_session_uid'))) {
	// 		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	// 	}else{
	// 		if ($filter == 'new_medicine') {
	// 			$order = [
	// 				'column_name'  => 'id',
	// 				'order'        => 'desc'
	// 			];
	// 		}else if ($filter == 'old_medicine') {
	// 			$order = [
	// 				'column_name'  => 'id',
	// 				'order'        => 'asc'
	// 			];
	// 		}else{
	// 			$order = [
	// 				'column_name'  => 'id',
	// 				'order'        => 'desc'
	// 			];
	// 		}
	// 		$args = [
	// 			'expiry_date<=' => date('Y-m-d '),
	// 			'status'        => 'Active'

	// 		];
	// 		$data = [
	// 			'medicines' => $this->medicine_model->filter_rec_by_args_with_pagination('medicines', $order, $args),
	// 			'pager'     => $this->medicine_model->pager
	// 		];
	// 		return view("Medical/expiry_products", $data);
	// 	}	
	// }
	if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
			}
	if ($filter == 'new_medicine') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
		
	}
	else if ($filter == 'old_medicine') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'asc'
		];
	}
	else{
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
	}
	$args = [
					'expiry_date<=' => date('Y-m-d '),
				//'status'        => 'Active'
	];
	$data = [
		   'medicines' => $this->medicine_model->filter_rec_by_args_with_pagination('medicines', $order, $args),
		'pager'   => $this->medicine_model->pager
	];
	
	
	return view("Medical/expiry_products", $data);
}
///Medicine Section Script Start With Pagination


//Product Sale Query Start
	public function add_customer_name(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		return view('Medical/add_customer_name');
	}

	public function add_customer_bill_slip(){ 
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}

		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'company_name'      => 'required|min_length[4]|max_length[50]',
				'cus_name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Coustmer name is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],

				'cus_address'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Coustmer name is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],


				'cus_number'  => [
					'rules'     => 'required|min_length[4]|max_length[10]',
					'errors'    => [
					'required' => 'Coustmer mobile is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 10.'
					],
				],

				'doc_name'  => [
					'rules'     => 'required|min_length[4]|max_length[10]',
					'errors'    => [
					'required' => 'Doctor mobile is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 10.'
					],
				],
				//'category_image'    => 'uploaded[category_image]|max_size[category_image,1024]|is_image[category_image]|ext_in[category_image,png,jpg,jpeg, svg, gif]'
				
			];
			
			$data = [
				'customer_name'   => $this->request->getVar('cus_name',FILTER_SANITIZE_STRING),
				'customer_add'    => $this->request->getVar('cus_address',FILTER_SANITIZE_STRING),
				'cus_mobile'      => $this->request->getVar('cus_number',FILTER_SANITIZE_STRING),
				'doctor_name'     => $this->request->getVar('doc_name',FILTER_SANITIZE_STRING),
				'date'            => date('Y-m-d h:i:s')
			];

			// $status = $this->commonForAllModel->Insertdata_return_id('sale_cus_record', $data);
			$status = $this->commonForAllModel->Insertdata_return_id('sale_cus_record', $data);
			if ($status == true) {
				$customer_session = [
					'id'    =>  $status
				];
			$this->session->set('accountant_session_id',$customer_session);
			$this->session->setTempdata('success', 'Congratulation ! Customer Added Successfully !', 3);
				return redirect()->to(base_url().'/Medical_Accountant/product_sale');
			}
			else{
				$this->session->setTempdata('error', 'Sorry ! Unable to Add  Try Again ?', 3);
			}
			return redirect()->to(base_url().'/Medical_Accountant/add_customer_name');

		}else{ 
			$data['validation'] = $this->validator;
			return view('/Medical_Accountant/add_customer_name/', $data);
		}
		return view('/Medical_Accountant/add_customer_name/', $data); 
	} //function -Closed

	public function product_sale(){
		if (!(session()->has('accountant_session_id'))) {
			$this->session->setTempdata('error', 'Please Add Customer Name First', 3);
			return redirect()->to(base_url()."/Medical_Accountant/add_customer_name");
		}
		$args = [
			//'expiry_date>=' => date('Y-m-d'),
			//'status'        => 'Active'
		];
		// $data['product_name'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);
		$data['product_name'] = $this->medicine_model->fetch_rec_by_args('medicines', $args);

		$args = [
			'session_id'  => session()->get('accountant_session_id')
		];
		
		$data['carts'] = $this->medicine_model->fetch_rec_by_args('carts', $args);
		return view('Medical/product_sale', $data);
	}



	public function add_to_Cart(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		$this->accountant_session_id = session()->get('accountant_session_id');
		//var_dump($this->accountant_session_id);die;
		if(!isset($this->accountant_session_id) || $this->accountant_session_id == '') {
			$this->session->setTempdata('error', 'Customer seesion is missing !', 3);
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		} 
		else {
			$this->product_id = $this->request->getVar('product_id', FILTER_SANITIZE_STRING);
			$this->quantity = $this->request->getVar('quantity', FILTER_SANITIZE_STRING);
			// $this->sale_products_arr = [
		// 	$this->request->getVar('product_id', FILTER_SANITIZE_STRING),
		// 	$this->request->getVar('quantity', FILTER_SANITIZE_STRING),
		// 	$this->request->getVar('expiry_date', FILTER_SANITIZE_STRING),
		// 	$this->request->getVar('batch_number', FILTER_SANITIZE_STRING)

		// ];
			$args = [ 'id'   => $this->product_id ];
			// $product_details = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			$product_details = $this->medicine_model->fetch_rec_by_args('medicines', $args);
			if($product_details === false) {
				$this->session->setTempdata('error', 'No medicine found. Please add medicine first !', 3);
				//return redirect()->to(base_url()."/Accountant_login/accountant_login");
				return redirect()->to(base_url()."/Medical_Accountant/product_sale");
			}
			//$args = [ 'product_id'  => $this->request->getVar('product_id', FILTER_SANITIZE_STRING) ];

			$check_product = $this->medicine_model->fetch_rec_by_args('carts', $args);
			if ($check_product) {
				count($check_product);
				$old_qty = $check_product[0]->quantity;
				$new_qty = $old_qty + $this->quantity;
				$args = [ 'id'  => $check_product[0]->id ];
				$data = [ 'quantity'   => $new_qty ];
				
				$result = $this->commonForAllModel->update_rec_by_args('carts', $args, $data);
				if ($result === true) {
					$this->session->setTempdata('success', 'Congratulations!, Cart updated successfully', 3);
					return redirect()->to(base_url()."/Medical_Accountant/product_sale");
				}
				else if($result === false){	
					$this->session->setTempdata('error', 'Sorry!!, Failed to update cart', 3);
					return redirect()->to(base_url()."/Medical_Accountant/product_sale");
				}
				else {
					$this->session->setTempdata('error', 'Unexpected cart update result. Please try again.', 3);
					return redirect()->to(base_url()."/Medical_Accountant/product_sale");
				}
				//Add one id data  already added or not check and quantity increment script by Neoarks Teams
			}
			else{
				$data = array(); //Just for addressing notices
				if(is_array($product_details)) {
					$data = [
						'product_id'     => $product_details[0]->id,
						'product_name'   => $product_details[0]->med_name,
						'session_id'     => $this->accountant_session_id,
						'quantity'       => $this->quantity,
						'rate'           => $product_details[0]->med_price,
						'stock'          => $product_details[0]->med_stock,
						'date'           => date('Y-m-d'),
						'created_at'     => date('Y-m-d h:i:s'),
						'created_by'     => date('Y-m-d')
					];
				}
				$result = $this->medicine_model->Insertdata('carts', $data);
				if ($result === true) {
					$this->session->setTempdata('success', 'Congratulations!, Cart updated successfully', 3);
				}
				else if ($result === false) {
					$this->session->setTempdata('error', 'Sorry ! Unable to update cart.  Please try again ?', 3);
				}
				return redirect()->to(base_url().'/Medical_Accountant/product_sale');
			} //else loop - Closed
		} //else loop - Closed
	} //Function - Closed


	public function delete_cart_product($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [
				'id'  => $id,
				'session_id'  => session()->get('accountant_session_id')
			];
			$status = $this->medicine_model->delete_records('carts', $args);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Product Remove to Cart Successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to Add  Try Again ?', 3);
			}
			return redirect()->to(base_url().'/Medical_Accountant/product_sale');
		}
	}

	public function check_out_products($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		$this->accountant_session_id = session()->get('accountant_session_id');
		if(!isset($this->accountant_session_id) || $this->accountant_session_id == '') {
			$this->session->setTempdata('error', 'Session has expired. Please try again.', 3);
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		else {
			$args = [ 'session_id'   => $id ];
			$products = $this->medicine_model->fetch_rec_by_args('carts', $args);
			//Get Shipping Address
			$args =[ 'id'   => $id ];
			$tem_address = $this->medicine_model->fetch_rec_by_args('sale_cus_record', $args);
			//Get Shipping Address

			//Get Products
			$total_quantity = 0;
			$total_amount = 0;
			if ($products) {
				count($products);
				foreach ($products as $pro) {
					$total_quantity   += $pro->quantity;
					$total_amount     += ($pro->quantity * $pro->rate);
				}
			}
			else { $total_quantity  = 0; }
			//Get Products

			$data = [
				'customer_id'     => $id,
				'customer_name'   => $tem_address[0]->customer_name,
				'customer_add'    => $tem_address[0]->customer_add,
				'total_quantity'  => $total_quantity,
				'total_amount'    => $total_amount,
				'order_date'      => date('Y-m-d'),
				'created_at'      => date('Y-m-d h:i:s'),
				'created_by'      => $this->accountant_session_id,
			];
			//insert order products
			$order_id = $this->commonForAllModel->Insertdata_return_id('order_product', $data);
			$this->order_number = time(). '#' . $order_id;
			$this->updt_dta = [ 'order_number' 	=> $this->order_number ];
			$this->args = ['id' 	=> $order_id ]; 
			////////////////////////
			$this->rdr_result = $this->commonForAllModel->update_rec_by_args('order_product', $this->args, $this->updt_dta);
			if ($this->rdr_result === false) {
				$this->session->setTempdata('error', 'Sorry!, Failed to update order number', 3);
				return redirect()->to(base_url()."/Medical_Accountant/product_sale");
			}
			/////////////////////

			if ($products) {
				count($products);
				foreach ($products as $pro) {
					$data =  [
						'customer_id'   => $tem_address[0]->id,
						'order_id'      => $order_id,
						'order_number'  => $this->order_number,
						'customer_name' => $tem_address[0]->customer_name,
						'customer_add' => $tem_address[0]->customer_add,
						'product_id'   => $pro->product_id,
						'product_name' => $pro->product_name,
						'quantity'     => $pro->quantity,
						'rate'         => $pro->rate,
						'date'         => date('Y-m-d'),
						'created_at'   => date('Y-m-d h:i:s'),
						'created_by'   => $this->accountant_session_id,
					];
					$result = $this->medicine_model->Insertdata('sale_products', $data);
					if($result === true) {
						$this->session->setTempdata('success', 'Congratulations!, Checkout successful.', 3);
					}
					else if($result === false) {
						$this->session->setTempdata('error', 'Sorry!, Checkout failed. Please try again.', 3);
					}
					else {
						$this->session->setTempdata('error', 'Unexpected sales product result. Please try again.', 3);
					}
				} //foreach - Closed
				return redirect()->to(base_url()."/Medical_Accountant/product_sale");
			}
			else {
				$this->session->setTempdata('error', 'Sorry! Failed to generate order. Please try again.', 3);
				return redirect()->to(base_url()."/Accountant_login/accountant_login");
			}
			// session()->destroy('accountant_session_id');

			//Generate_bill
			$args = [ 'customer_id'  => $id ];	
			$data['generate_bill'] = $this->medicine_model->fetch_rec_by_args('sale_products', $args);
			if($data['generate_bill'] === false) {
				$this->session->setTempdata('error', 'Sorry! Failed to generate bill. Please try again.', 3);
				return redirect()->to(base_url()."/Accountant_login/accountant_login");
			}
			return view('Medical/generate_payment_bill', $data);	
		}//else loop - Closed
	} //function - Closed


	// public function print_slip($pid){
	// 	//if (!(session()->has('accountant_session_uid'))) {
	// 	if (!(session()->has('accountant_session_uid'))) {
	// 		return redirect()->to(base_url() . "Accountant_login/accountant_login");
	// 	}
	// 	$args = [
	// 		'pid'  => $pid
	// 	];
	// 	$data['patient_slip'] = $this->medicine_model->fetch_rec_by_args('patients', $args);
	// 	//echo "<pre>";print_r($data['patient_slip']);die;
	// 	return view('Medical/print_slip', $data);
	// }
	public function print_slip($id , $pid){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "Accountant_login/accountant_login");
		}
		//echo "<pre>";print_r($id. '/' . $pid);die;
		$args = [
			'pid'  => $pid
		];
		$data['patient_slip'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		//echo "<pre>";print_r($data['patient_slip']);die;
		return view('Medical/print_slip', $data);
	}

	public function todays_sale_records(){
	// 	if (!(session()->has('accountant_session_uid'))) {
	// 		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	// 	}else{
	// 		$args = [
	// 			'order_date'   => date('Y-m-d')
	// 		];
	// 		//$data['sales'] = $this->medicine_model->fetch_rec_by_args('order_product',$args);
	// 		$data = [
	// 			// 'sales'  => $this->commonForAllModel->fetch_all_records('order_product'),
	// 			'sales'  => $this->medicine_model->fetch_rec_by_status_with_pagination('order_product', $args),
	// 			'pager'   => $this->medicine_model->pager
	// 	   ];
		   
	// 		return view('Medical/todays_sale_records', $data);
	// 	}
	// }
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	} else {
		$args = [
			'order_date'   => date('Y-m-d h:i:s')
		];
		$data['sales'] = $this->medicine_model->fetch_rec_by_args('order_product', $args);
		//echo "<pre>";print_r($data);die;
		return view('Medical/todays_sale_records', $data);
	}
}

	public function search_sales(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$args = [	
				'order_date>='  => $this->request->getVar('start_date',FILTER_SANITIZE_STRING),
				'order_date<='  => $this->request->getVar('last_date',FILTER_SANITIZE_STRING),
			];
			$data['sales'] = $this->medicine_model->fetch_rec_by_args('order_product',$args);
			return view('Medical/search_sales', $data);
		}
	}

	public function all_sale_reports(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			//$data['sales'] = $this->commonForAllModel->fetch_all_records('order_product');
			$data = [
				 			// 'sales'  => $this->commonForAllModel->fetch_all_records('order_product'),
							 'sales'  => $this->commonForAllModel->fetch_all_records('order_product'),
							'pager'   => $this->medicine_model->pager
						];
			return view('Medical/all_sale_reports', $data);
		}
	}
		
	//Change Password Script Start 
	public function change_password(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}else{
			$data = [];
			// $data['loggedin_usr'] = $this->medicine_model->getLoggedInUserData(session()->get('accountant_session_uid'));
			$data['loggedin_usr'] = $this->medicine_model->getLoggedInUserData(session()->get('accountant_session_uid'), 'register_all_users');
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'old_password'   => 'required',
					// 'new_password'   => 'required|min_length[6]|max_length[20]',
					'new_password'  => [
						'rules'     => 'required|min_length[6]|max_length[20]',
						'errors'    => [
						'required' => 'New password  is mandatory.',
						'min_length' => 'Minimum length is 6.',
						'max_length' => 'Maximum length is 20.'
						],
					],
					'confirm_password'  => 'required|matches[new_password]',
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
						$status = $this->medicine_model->updatePassword('register_all_users',$npwd,session()->get('accountant_session_uid'));
						if ($status) {
							$this->session->setTempdata('success', 'Congratulation ! Password Updated Successfully!', 3);
							return redirect()->to(current_url());
						}else{
							$this->session->setTempdata('error', 'Sorry Unable to Update Password Try Again!', 3);
							return redirect()->to(current_url());
						}
					}else{
						$this->session->setTempdata('error', 'Incorrect login email or password', 3);
					}
				}else{
					$data['validation']  = $this->validator;
				}
			}	
		}
		
		return view('Medical/change_password', $data);
	} 

	
//Change Password Script Start End

//Dashboard Section Script Start
	public function count_customers($type = 'all'){
		if ($type == 'all') {
			$customer = $this->commonForAllModel->fetch_all_records('order_product');
				
		}else if ($type == 'today') {
			$args = [
				'order_date'  => date('Y-m-d ')
			];
			$customer = $this->medicine_model->fetch_rec_by_args_with_date('order_product', $args);
			// var_dump($customer);
			// exit();
		}else if ($type == 'yesterday') {
			$args = [
				'order_date'  => date('Y-m-d',strtotime("-1 day"))
			];
			$customer = $this->medicine_model->fetch_rec_by_args_with_date('order_product', $args);
		}else if ($type == 'last_30_days') {
			$args = [
				'order_date>='  => date('Y-m-d',strtotime("-30 day")),
				'order_date<='   => date('Y-m-d ') 
			];
			$customer = $this->medicine_model->fetch_rec_by_args_with_date('order_product', $args);
		}else{
			$customer = $this->commonForAllModel->fetch_all_records('order_product');
		}
		echo count($customer);
	} 

	public function count_income($type = 'all'){
		if ($type == 'all') {
			$income = $this->commonForAllModel->fetch_all_records('sale_products');
				
		}else if ($type == 'today') {
			$args = [
				'date'  => date('Y-m-d ')
			];
			$income = $this->medicine_model->fetch_rec_by_args_with_date('sale_products', $args);
			 // var_dump($income);
			 // exit();
		}else if ($type == 'yesterday') {
			$args = [
				'date'  => date('Y-m-d',strtotime("-1 day"))
			];
			$income = $this->medicine_model->fetch_rec_by_args_with_date('sale_products', $args);

		}else if ($type == 'last_30_days') {
			$args = [
				'date>='  => date('Y-m-d',strtotime("-30 day")),
				'date<='  => date('Y-m-d ') 
			];
			$income = $this->medicine_model->fetch_rec_by_args_with_date('sale_products', $args);
			// var_dump($income);
			// exit();
		}else{
			$income = $this->commonForAllModel->fetch_all_records('sale_products');
			// echo "<pre>";
			// var_dump($income);
			// exit();
		}
		$total_income = 0;
		if(count($income)){
			foreach($income as $count_inc){
				// $total_income += $inc->entry_fee;
				
				$total_income += $count_inc->quantity *  $count_inc->rate;
				// var_dump($total_income);
				// exit();
			}
		}
		else{
			$total_income = 0;
			// number_format($total_income);
		}
		echo json_encode($total_income);
	}
//Dashboard Section Script End
public function all_patients() {
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	} else {
		$args = [
			//'status'  => 'Active' 
			'is_del'=> 0,
		];
		$data = [
			'patients' => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->patient_model->pager
		];
		return view('Medical/all_patients', $data);
		// return view("Medical/manage_patients",$data);
	}
} //Function - Closed
// public function manage_patients(){
// 	if (!(session()->has('accountant_session_uid'))) {
// 		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
// 	} 
// 	else {
// 		$args = [ 'is_del'	=> 0 ];
// 		$data = [
// 			'patients' => $this->medicine_model->fetch_rec_by_args_with_status('patients', $args),
// 			'pager' => $this->medicine_model->pager
// 		];
// 		return view('Admin/manage_patients', $data);
// 	}
// } //Function - Closed

public function search_patient(){
  //  if (!(session()->has('accountant_session_uid'))) {
//         return redirect()->to(base_url()."/Accountant_login/accountant_login");
//     }

//     $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
    
//     // Remove spaces from the user input
//     $keyword = str_replace(' ', '', $keyword);
    
//     $args = [
//         //'status' => 'Discharged'
//     ];

//     if ($keyword) {
//         // Search for data with spaces removed
//         $result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
//     } else {
//         $result = $this->commonForAllModel;
//     }

//     $data = [
//         'patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
//         'pager' => $this->commonForAllModel->pager
//     ];

//     return view("Medical/all_patients", $data);
// }
    	if (!(session()->has('accountant_session_uid'))) {  
			return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
			'is_del'        => 0
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->medicine_model->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->medicine_model;
		}   
		
		$data = [
			'patients' => $this->medicine_model->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->medicine_model->pager
		];
		
		return view('Medical/all_patients', $data);
	}

	public function filter_patients($filter){
		//if (!(session()->has('accountant_session_uid'))) {
		if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url()."/Accountant_login/accountant_login");
		}
		if ($filter == 'new_patients') {

			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_patients') {
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
		$args = [
			//'status'        => 'Active'
			'is_del'        => 0
		];
		$data = [
			'patients' => $this->medicine_model->filter_rec_by_args_with_pagination('patients', $order, $args),
			'pager'     => $this->medicine_model->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Medical_Accountant/add_patients");
		}
		// echo"<pre>";print_r($data);die;
		return view("Medical/all_patients", $data);
		// return view('Medical/manage_doctor', $data);
	}
	public function change_patients_status($id, $pid, $status){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	} else {
		$this->acctnt_uid = session()->get('accountant_session_uid');
			if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
				}
			}
				$args = [
				'id' => $id,
				'is_del' =>	0
			]; 
			$data = [
					'status'  => $status,
					'updated_at' => date('Y-d-m H:i:s'),
					'updated_by' => $this->acctnt_uid,
					
				];
				// echo "<pre>";print_r($data);die;
		$status = $this->patient_model->update($id, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
		return redirect()->to(base_url() . '/Medical_Accountant/all_patients');
	}
	} //funtion - Closed

	public function add_patients(){
		//if (!(session()->has('accountant_session_uid'))) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$args = [
				'status'  => 'Active'
			];
			$data['doctors'] = $this->medicine_model->fetch_rec_by_args('doctor', $args);
			//$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor_fee');
			return view('Medical/add_patients', $data);
		}
	}

	//********************************  Generate Serial For Appointment ***********************/
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
				return redirect()->to(base_url().'/Medical_Accountant/all_appointments');
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
				return redirect()->to(base_url().'/Medical_Accountant/all_appointments');
			}
		}
		else {
			//return false;
			$this->session->setTempdata('error', 'Unexpected serial number ', 3);
			return redirect()->to(base_url().'/Medical_Accountant/all_appointments');
		}
	} //Function - Closed

	public function generate_puid($new_sn){
		//if (!(session()->has('accountant_session_uid'))) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->new_serial = $new_sn; //Serial Number
		$this->current_date = date('Y-m-d');

		$this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
		$this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
		return $this->puid;
	}


	public function upload_patients(){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			$this->acctnt_uid = session()->get('accountant_session_uid'); //Loggedin User uid
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					// 'patient_name'      => 'required|min_length[4]|max_length[20]',
					'patient_name'  => [
						'rules'     => 'required|min_length[4]|max_length[20]',
						'errors'    => [
						'required' => 'Patient name  is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
						],
					],
   
					'patient_phone'     => 'required|numeric|exact_length[10]',
					// 'patient_address'   => 'required|min_length[4]|max_length[50]',
					'patient_address'  => [
						'rules'     => 'required|min_length[4]|max_length[50]',
						'errors'    => [
						'required' => 'Patient address  is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
						],
					],
   
					'doctor_name'      => 'required',
					'patient_issue'     => 'required'
				];

				if ($this->validate($rules)) {
					$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
					$img = $this->request->getFile('patient_image');
					if ($img->isValid() &&  !$img->hasMoved()) {
						$newName = $img->getRandomName();
						$img->move(FCPATH . 'uploads/patients', $newName);
						$doc_img = $img->getName();
						//Generate Serial - START
						//$this->new_serial_arr = $this->generate_serial('patients', 'registration_date');
						$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date');
						if (is_array($this->new_serial_arr) && count($this->new_serial_arr) > 0) {
							if (isset($this->new_serial_arr['serial'])) {
								$this->new_serial = $this->new_serial_arr['serial'];
							} else {
								$this->session->setTempdata('error', 'Serial index is missing', 3);
								return redirect()->to(base_url() . '/Medical_Accountant/add_patients');
							}
						} else {
							$this->session->setTempdata('error', 'Unexpected serial format', 3);
							return redirect()->to(base_url() . '/Medical_Accountant/add_patients');
						} //Generate Serial - END
						//Generate Puid - START
						$this->puid = 0; //Just for addressing notices
						if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
							$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
							return redirect()->to(base_url() . '/Medical_Accountant/add_patients');
						} else {
							$this->puid = $this->generate_puid($this->new_serial);
							$this->current_date_time = date('Y-m-d H:i:s'); //current Date and time
						} //Generate Puid - END

						$this->patient_data_arr = [
							'patient_name'          =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
							'patient_phone'         =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
							'patient_address'       =>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),
							'age'					=> 	$this->request->getVar('patient_age', FILTER_SANITIZE_STRING),
							'gender'				=> 	$this->request->getVar('patient_gender', FILTER_SANITIZE_STRING),
							'patient_zip'           =>  $this->request->getVar('patient_zip', FILTER_SANITIZE_STRING),
							'doctor_id'           	=>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING), //1st july, 2023
							'doctor_name'			=>  $this->request->getVar('doc_name', FILTER_SANITIZE_STRING),
							'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
							'entry_fee'             =>  $this->request->getVar('entry_fee', FILTER_SANITIZE_STRING),
							'patient_issue'         =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
							'other_fee'             =>  $this->request->getVar('other_fee', FILTER_SANITIZE_STRING),
							'patient_room'          =>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
							'patient_email'         =>  $this->request->getVar('patient_email'),
							'uid'                   =>  $uid,
							'puid'					=> $this->puid, //Patient's ID
							'serial'				=> $this->new_serial, //Patient's Serial number
							'registration_date'     =>  date('Y-m-d'),
							'patient_image'         =>  $doc_img,
							'status'                => 'Active',
							'level'                 => '1',  //0: Frontdesk or Donor, 1: Admin, 2: Accountant, 3:Doctor, 4: Patient	
							'created_at'            =>  $this->current_date_time,
							'created_by'            =>  $this->acctnt_uid //Loggedin User uid
						];

						$this->appmt_data_arr = [
							'patient_name'		=>  $this->patient_data_arr['patient_name'],
							'serial'			=>	$this->new_serial,
							//'pid'				=>  $this->patient_session_id, //patient auto increment ID
							'patient_email'     =>  $this->patient_data_arr['patient_email'],
							'patient_mobile'    =>  $this->patient_data_arr['patient_phone'],
							'age'    			=>  $this->patient_data_arr['age'],
							'gender'    		=>  $this->patient_data_arr['gender'],
							'puid'    			=>  $this->puid, //Patient's ID
							'booking_date'      =>  date('Y-m-d'),
							'booking_time'      =>  date('h:i:s'),
							'disease_symptoms'  =>  $this->patient_data_arr['patient_issue'],
							'description'       =>  $this->request->getVar('other_info', FILTER_SANITIZE_STRING),
							'address'       	=>  $this->patient_data_arr['patient_address'],

							'doctor_id'         =>  $this->patient_data_arr['doctor_id'],
							'doctor_name'       =>  $this->patient_data_arr['doctor_name'],
							'status'            => 4, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
							'created_at'        =>  date('Y-m-d h:i:s'),
							'created_by'		=>	$this->acctnt_uid
						];


						//$status = $this->medicine_model->Insertdata('patients', $this->patient_data_arr);
						//$this->tbl_arr = array('patients', 'booked_doctor_appointment');

						$status = $this->medicine_model->insert_into_multple_tables('patients', $this->patient_data_arr, 'booked_doctor_appointment', $this->appmt_data_arr);
						if ($status === true) {
							$this->session->setTempdata('success', 'Congratulation ! Patients Added Successfully !', 3);
						} else {
							$this->session->setTempdata('error', 'Sorry ! Unable to Add  Patients  Try Again ?', 3);
						}
						return redirect()->to(base_url() . 'Medical_Accountant/manage_patients');
					} else {
						echo $image->getErrorString() . " " . $image->getError();
						$this->session->setTempdata('error', 'Please Select any Image File', 3);
						return redirect()->to(base_url() . '/Medical_Accountant/add_patients');
					}
				} else {
					$data['validation'] = $this->validator;
				}
			} else {
				$this->session->setTempdata('error', 'Sorry ! Required POST method.!', 3);
				return redirect()->to(base_url() . '/Medical_Accountant/add_patients');
			}
			// $data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor_fee');
			$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
			return view('Medical/add_patients', $data);
		} //else loop - Closed
	} //Function - Closed

	public function delete_patients($id){
		$this->patient_id = $id;
        if (!(session()->has('accountant_session_uid'))) {
            return redirect()->to(base_url()."/Accountant_login/accountant_login");
        }
        $this->acctnt_uid = session()->get('accountant_session_uid');
        if(!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
            $this->session->setTempdata('error', 'Accountant UID is missing !', 3);
            return redirect()->to(base_url()."/Accountant_login/accountant_login");
        }
        $args = [ 'id'   => $id ,
		          'is_del'=> 0
				];
        $data = [
            'status' => 'Deleted', 
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->acctnt_uid
        ];
        $status = $this->commonForAllModel->update_rec_by_args('patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success','Congratulation ! Patients has deleted successfully', 2);
            //return redirect()->to(base_url().'Medical_Accountant/manage_today_discharged_patient');
            return redirect()->to(base_url().'Medical_Accountant/all_patients');
        }
        else {
            session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
            return redirect()->to(base_url().'Medical_Accountant/all_patients');
        }
    } //Function - Closed

public function manage_patients(){
	if (!(session()->has('accountant_session_uid'))) {
		return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	} 
	else {
		$args = [ 'is_del'	=> 0 ];
		$data = [
			'patients' => $this->medicine_model->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->medicine_model->pager
		];
		return view('Medical/manage_patients', $data);
	}
} //Function - Closed

/*@param: Logged-in user's `uid` used from session 
* @desc: Upload Profile pic
* @use: View Profile
* @return:
* @author: Neoarks Team
* @date: 24th November, 2023
* @modify
*/
public function upload_profile_pic() { 
	if (!(session()->has('accountant_session_uid')) && !(session()->has('accountant_session_id'))) {
		$this->result_arr = [
				 'status' => false,
				'error'  => true, //error: `true` whereever status is false with SQL err 
				 'code' => 200,
				 'data' => [],
				'message' => 'Session has expired. Please relogin again.'
			 ];
		 //return json_encode($this->result_arr);
		 return redirect()->to(base_url() . "/Accountant_login/accountant_login");
	} 
	$this->acctnt_uid = session()->get('accountant_session_uid'); //Loggedin User uid
	$this->acctnt_id = session()->get('accountant_session_id'); //Loggedin User id


	$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {	
			$rules = [
				'uploaded_file' => [
					'rules'     => 'uploaded[uploaded_file]|max_size[uploaded_file,' . ALLOW_MAX_UPLOAD .']|is_image[uploaded_file]|mime_in[uploaded_file,image/jpeg,image/png,image/gif,image/svg+xml]|ext_in[uploaded_file,png,jpg,jpeg,svg,gif]',
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
	   // Validate file format - End
		$this->profile_pic = $this->request->getFile('uploaded_file');
		$this->file_name = $this->profile_pic->getName();
		if($this->file_name && $this->file_name != '') {
			$args = [ 'id'  => $this->acctnt_id ];
					
			$this->old_data = $this->adminModel->fetch_rec_by_args('register_all_users', $args);
			
			if(isset($this->old_data[0]->profile_pic)) {
				
				if(file_exists(FCPATH . 'uploads/accounts/accountants/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
					//delete old  image
					@unlink(FCPATH . 'uploads/accounts/accountants/' . $this->old_data[0]->profile_pic);
				} //else - Not needed
			} //else - Not needed	

			$this->random_name = $this->profile_pic->getRandomName();
				if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
					$this->profile_pic->move(FCPATH . 'uploads/accounts/accountants', $this->random_name);
			
			$this->user_data_arr = [
				'profile_pic'     =>  $this->random_name,//$this->file_name,
				'updated_at'      =>  date('Y-m-d H:i:s'),
				'updated_by'      =>  $this->acctnt_id,
			];
			$args = ['uid'	=> $this->acctnt_uid]; //Need update model function in place of Insertdata - 
			$status = $this->commonForAllModel->update_rec_by_args('register_all_users', $args, $this->user_data_arr);
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
			//return redirect()->to(current_url());
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
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > '',
					'message' => 'Unexpected request method. Please try again'
				);
				return json_encode($this->result_arr);
			}
			//return redirect()->to(current_url());
		} //Function - Closed

	/*@param: Logged-in user's `uid` used from session 
    * @desc: View user Profile
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	public function view_profile(){   
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		else {
			$this->acctnt_uid = session()->get('accountant_session_uid');
			if ($this->request->getMethod() == 'get') {
				// $this->data['profile_record']  = $this->medicine_model->getLoggedInAccountantData($this->acctnt_uid);
				$this->args = ['uid' => $this->acctnt_uid];
				$this->data['profile_record']  = $this->commonForAllModel->fetch_rec_by_uid('register_all_users', $this->args);
				if($this->data['profile_record'] === false) {
					return view('Medical/view_profile', $this->data);
					//echo "false value";die;
					$this->session->setTempdata('error', 'fasle value ', 3);
						return redirect()->to(base_url() . 'Medical/view_profile', $this->data);
					
				}
				else {
					return view('Medical/view_profile', $this->data);
				}
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Medical/view_profile', $this->data);
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
    //     if (!(session()->has('accountant_session_uid'))) {
    //         return redirect()->to(base_url() . "/Accountant_login/accountant_login");
    //     }
    //     else {
    //         $this->acctnt_uid = session()->get('accountant_session_uid');
	// 		$this->data = [];
	// 		$this->data['validation'] = null;
	// 		$rules = [
	// 			'mobile'   => 'required',
	// 			'email'    => 'required|min_length[5]|max_length[40]',
	// 			'username' => 'required',
	// 		];
	// 		if ($this->validate($rules)) {
	// 			if ($this->request->getMethod() == 'post') {
	// 				$this->args = [ 'uid'  => $this->acctnt_uid, ];
	// 				$this->updt_email = $this->request->getPost('email', FILTER_SANITIZE_STRING);
	// 				//email - Unique check - START
	// 				$this->is_unique = $this->commonForAllModel->unique_except_logged_in('register_all_users', 'email', $this->updt_email, $this->acctnt_uid);
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
	// 				$this->is_unique = $this->commonForAllModel->unique_except_logged_in('register_all_users', 'mobile', $this->updt_mobile, $this->acctnt_uid);
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
	// 					'country_code'  	=>  $this->request->getPost('country_code', FILTER_SANITIZE_STRING),
	// 					'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
	// 					'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
	// 					'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
	// 					'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
	// 					'address'     =>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
	// 					'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
	// 					'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
	// 					'updated_at'    => date('Y-m-d H:i:s'),
	// 					'updated_by'    => $this->acctnt_uid,
	// 				];
					
	// 				$this->data['profile_updt_status'] = $this->commonForAllModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
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
	// 					'message'   => 'Oops.!, Mandatory validation failed.!',
	// 					'data'      => $this->data,
	// 				);
	// 				return json_encode($this->result_arr);
	// 			}
	// 		}
	// 		else {
	// 			$this->result_arr = array(
	// 				'status'    => false,
	// 				'code'      => 200,
	// 				'message'   => 'Oops.!, Unexpected request method.!',
	// 				'data'      => $this->data,
	// 			);
	// 			return json_encode($this->result_arr);
	// 		} 
    //     } //else -loop closed
    // } //function -  closed
	public function update_profile() {
        if (!(session()->has('accountant_session_uid'))) {
            return redirect()->to(base_url() . "/Accountant_login/accountant_login");
        }
        else {
            $this->acctnt_uid = session()->get('accountant_session_uid');
			$this->data = [];
			$this->data['validation'] = null;
			$rule = [
                // 'full_name'          => 'required|min_length[4]|max_length[20]',
				// 'username' => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'User name is mandatory.',
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
					$this->args = [ 'uid'  => $this->acctnt_uid, ];
					$this->user_data_arr = [
						'username'    	=>  $this->request->getPost('username', FILTER_SANITIZE_STRING),
						'about_me'    	=>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
						'gender'     	=>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
						'country_code'  	=>  $this->request->getPost('country_code', FILTER_SANITIZE_STRING),
						'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
						'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
						'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
						'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
						'address'     =>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
						'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
						'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
						'updated_at'    => date('Y-m-d H:i:s'),
						'updated_by'    => $this->acctnt_uid,
					];
					//echo "<pre>"; print_r($this->user_data_arr);die;
					$this->data['profile_updt_status'] = $this->commonForAllModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
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
							'error'		=> false, //error: `fasle` whereever status is true with SQL err
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
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code'      => 200,
					'message'   => 'Oops.!, Mandatory validation failed.!',
					'data'      => $this->data,
				);
				return json_encode($this->result_arr);
			} 
        } //else -loop closed
    } //function -  closed

	public function update_medical($id = ''){
		if (!(session()->has('accountant_session_uid'))) {
				return redirect()->to(base_url() . "/Accountant_login/accountant_login");
			}
			$args = [
				'id'   => $id
			];
	
			$data = [
				'username'       => $this->request->getVar('username', FILTER_SANITIZE_STRING),
				'email'           => $this->request->getVar('email', FILTER_SANITIZE_STRING),
				'phone'          => $this->request->getVar('phone', FILTER_SANITIZE_STRING),
				'age'        => $this->request->getVar('age', FILTER_SANITIZE_STRING),
				'address'          => $this->request->getVar('address', FILTER_SANITIZE_STRING),
				'state'      => $this->request->getVar('state', FILTER_SANITIZE_STRING),
				'zip'     => $this->request->getVar('zip', FILTER_SANITIZE_STRING),
				// 'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
				// // 'profile_pic'       => $doc_img
				'updated_at'             => date('Y-m-d h:i:s')
			];
	
			$status = $this->commonForAllModel->update_rec_by_args('register_all_users', $args, $data);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Accountant details updated successfully !', 3);
			} else {
				$this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3);
			}
			//return redirect()->to(base_url().'/Medical/edit_doctor/'.$id);
			return redirect()->to(base_url() . 'Medical_Accountant/view_profile/');
		}
	public function edit_admin_profile($id = '') {   
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		} else {
			//echo 'Route has been Added';
		return view('Medical/edit_admin_profile');
		}
	}
/* @params: Function for update view profile
	* @desc: Admin can update view profile 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 23rd Oct,2023
	* @modify
	*/
	public function update_view_profile($id){
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			//'id'   => $id
		];
		$data = [];
		$status = $this->commonForAllModel->update_rec_by_args('doctor', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Details updated successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3);
		}
		return redirect()->to(base_url() . 'Medical_Accountant/view_profile/');
	}

/* @params: Function for permanent delete patients
* @desc: Admin can soft delete patients also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_patients($id){
		$this->patient_id = $id;
		//if (!(session()->has('accountant_session_uid'))) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		if (!isset($this->acctnt_uid) || $this->acctnt_uid == '') {
			$this->session->setTempdata('error', 'Accountant UID is missing !', 3);
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->acctnt_uid
		];
		$status = $this->commonForAllModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
			return redirect()->to(base_url() . 'Medical_Accountant/all_patients');
		} else {
			session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
			return redirect()->to(base_url() . 'Medical_Accountant/all_patients');
		}
	}
	public function get_dept_for_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->data['patient_id'] = $patient_id;
		$this->data['pid'] = $pid;
		$this->data['puid'] = $puid;
		$this->data['apmt_id'] = $apmt_id;
		$this->data['dr_id'] = $dr_id;
		//$this->data['patient_id'] = $patient_id;
		$this->doctor_id = session()->get('doctor_session_id');
		$this->dept_args = [
			'is_del' 	=> 0,
			'status' 	=> 'Active',
		];
		//$this->data['patient_id']
		$this->data['departments'] = $this->medicine_model->fetch_rec_by_args('department', $this->dept_args);
		//echo "<pre>";print_r($this->data);die;
		if($this->request->getMethod() == 'get') {
			return view('Doctor/admission_process', $this->data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected request method !', 3);
			return view('Doctor/admission_process', $this->data);
		}
	} //function - closed

	
   /* @params: Function for filter discharge patients
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function get_wards_for_admission($dept_id) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}

		$this->doctor_id = session()->get('doctor_session_id');
		$this->data['wards'] = [];
		if($this->request->getMethod() == 'post') {
			$this->dept_id  = $dept_id;
			$this->ward_args = ['dept_id' 		=> $this->dept_id];
			$this->data['wards'] = $this->medicine_model->fetch_rec_by_args('hospital_wards', $this->ward_args);
			if($this->data['wards'] === false) {
				$this->result_arr = array(
					'status'	=> 'false',
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code'		=> 200,
					'message'	=> 'No ward found. Please talk admin.',
					'data'		=> array(),
				);
				return json_encode($this->result_arr);
			}
			else {
				$this->result_arr = array(
					'status'	=> 'true',
					'error'		=> false, //error: `false` whereever status is true with SQL err 
					'code'		=> 200,
					'message'	=> 'Ward fetched successfully',
					'data'		=> $this->data['wards'],
				);
				return json_encode($this->result_arr);
			}
		}
		else {
			// $this->session->setTempdata('error', 'Unexpected request method !', 3);
			// return view('Doctor/admission_process', $data);
			$this->result_arr = array(
				'status'	=> 'false',
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Unexpected request method !',
				'data'		=> $this->data['wards'],
			);
			return json_encode($this->result_arr);
		}
	} //function - closed


	/* @params: Function for filter discharge patients
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function get_beds_for_admission($ward_id) {
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->doctor_id = session()->get('doctor_session_id');
		if($this->request->getMethod() == 'post') {
			$this->ward_id  = $ward_id;
			$this->bed_args = [
				'ward_id' 		=> $this->ward_id,
				'is_free' 		=> 1,
				];
			$this->data['beds'] = $this->medicine_model->fetch_rec_by_args('hospital_beds', $this->bed_args);
			//var_dump($this->data['beds']);die;
			if($this->data['beds'] === false) {
				$this->result_arr = array(
					'status'	=> false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code'		=> 200,
					'message'	=> 'No bed found. Please talk admin.',
					'data'		=> $this->data['beds'],
				);
				return json_encode($this->result_arr);
			}
			else {
				$this->result_arr = array(
					'status'	=> 'true',
					'error'		=> false, //error: `false` whereever status is true with SQL err 
					'code'		=> 200,
					'message'	=> 'Bed fetched successfully',
					'data'		=> $this->data['beds'],
				);
				return json_encode($this->result_arr);
			}
		}
		else {
			// $this->session->setTempdata('error', 'Unexpected request method !', 3);
			// return view('Doctor/admission_process', $data);
			$this->result_arr = array(
				'status'	=> 'false',
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Unexpected request method',
				'data'		=> array(),
			);
			return json_encode($this->result_arr);
		}
	} //function - closed

	public function admission_process() { 
		$this->insrtData = []; //Just for addressing notices
		if (!(session()->has('accountant_session_uid'))) {
			return redirect()->to(base_url() . "/Accountant_login/accountant_login");
		}
		$this->acctnt_uid = session()->get('accountant_session_uid');
		$data  = [];
		// $data['validation'] = null;
		if($this->request->getMethod() == 'post') { 
			$this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
			$this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
			if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
				$result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message' => 'Patient ID may not blank',
					'data' => $this->insrtData
				);
				return json_encode($result_arr);
			}
			$this->updt_data_arr = [
					'department_name'  => $this->request->getPost('department_name',FILTER_SANITIZE_STRING),
					'ward_name'         => $this->request->getPost('wardname',FILTER_SANITIZE_STRING),
					'bed_lable'        => $this->request->getPost('bed_lable',FILTER_SANITIZE_STRING),
					'other_info'       => $this->request->getPost('other_info',FILTER_SANITIZE_STRING),   //Need to mention name in HTML
					'patient_id'       => $this->patient_id,
					'is_free'		=> 0,
					'pid'       	=> $this->request->getPost('pid',FILTER_SANITIZE_STRING),
					'puid'       	=> $this->request->getPost('puid',FILTER_SANITIZE_STRING),
					'apmt_id'       => $this->request->getPost('apmt_id',FILTER_SANITIZE_STRING),
					'dr_id'       	=> $this->request->getPost('dr_id',FILTER_SANITIZE_STRING),
					'updated_at' 	=> date('Y-m-d h:i:s'),
					'updated_by'    => $this->acctnt_uid,
			];
			//echo "<pre>"; print_r($this->updt_data_arr);die;
			$this->updt_args = [ 'id' 	=> $this->bed_id ];  
			$status = $this->commonForAllModel->update_rec_by_args('hospital_beds', $this->updt_args, $this->updt_data_arr );  
			if ($status === true) {
				$this->updt_data_arr = [
					'status'       	=> 'Admission Processed',
					'updated_at' 	=> date('Y-m-d h:i:s'),
					'updated_by'    => $this->acctnt_uid,
				];
				$this->updt_args = [ 'id' 	=> $this->patient_id];
				$status = $this->commonForAllModel->update_rec_by_args('patients', $this->updt_args, $this->updt_data_arr);  
				if ($status === true) {
					$result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` whereever status is true with SQL err 
						'code'	 => 200,
						'message'=> 'Record updated successfully',
						'data'   => $this->updt_data_arr
					);
					return json_encode($result_arr);
				} 
				else {
					$result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message' => 'Failed! to update record',
						'data' => $this->updt_data_arr
					);
					return json_encode($result_arr);
				} //else - loop closed 
			}
			else {
				$result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message' => 'Failed! to update record',
					'data' => $this->updt_data_arr
				);
				return json_encode($result_arr);
			} //else - loop closed
		}
		else {
			$result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message' => 'Unexpected request method',
				'data' => $this->updt_data_arr
			);
			return json_encode($result_arr);
		} //else - loop closed
	} //function - Closed
	

}