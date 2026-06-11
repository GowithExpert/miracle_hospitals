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

use CodeIgniter\Controller;	
use \App\Models\Login_model;
use \App\Models\Register_model;
use \App\Models\Admin_Model;
use \App\Models\HomeModel;
use \App\Models\CommonForAllModel;

class Home extends BaseController
{
	// private $puid;
	// private $new_serial;
	public $loginModel;
	public $session;
	public $registerModel;
	public $adminModel;
	public $homeModel;
	public $commonForAllModel;

	public function __construct(){
		helper(['form','date', 'Patient','text']);
		//helper(['form','Patient','text']);
		$this->loginModel = new Login_model();
		$this->session   = session();
		$this->adminModel  = new Admin_Model();
		$this->email = \Config\Services::email();
		$this->registerModel = new Register_model();
		//$this->encryption = \Config\Services::encryption(); // Load the Encryption library -- NOT USED
		$this->homeModel = new HomeModel();
		$this->commonForAllModel = new CommonForAllModel();
	}
	
	public function display_sample_page() {
		return view('Home/a_sample_page'); 
	} //function - Closed



   /* @param: List all doctor's list
	* @desc: 
	* @return: 
	* @use: Home (Similar function is used inside Patient/view_doctor) 
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: Aug 1st, 2025
	* @modify:
	*/
	public function view_doctor(){ //get_allregis_doctors
		/*if (!(session()->has('patient_session_uid'))) {
			return redirect()->to(base_url()."Patients_login/login");
		}*/
		$data['view_doctor'] = $this->commonForAllModel->fetch_all_records('doctor');
		return view('Home/view_alldoctors',$data);
	}
	
   
   /* @param: Render `Book Dorctor` ie `Doctor Appointment` page
	* @desc: 
	* @return: 
	* @use: Home page, 
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: December 1st, 2023
	* @modify:
	*/
	public function booked_doctor($id){
		$args = [
			'id'  => $id
		];
		$data['doctor_id'] = $this->patient_model->fetch_rec_by_args('doctor', $args);
		return view('Patients/booked_doctor', $data);
	}

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


	/*@params: Render Home page & fetch the required data for home page 
	* @desc: 
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/

	public function index() {	
		$args = [ 'status'  => 'Active' ];
		$data['slider'] = $this->adminModel->get_image_by_args('slider_image', $args, 5);
		
		$args = [ 'status'  => 'Active' ];
		$data['why_choose'] = $this->adminModel->get_image_by_args('gallery_image', $args, 7);
		$args = [ 'is_del'  => 0 ];
		$data['hospital_wards'] = $this->adminModel->fetch_rec_by_args('hospital_wards', $args);
		$data['hospital_beds'] = $this->commonForAllModel->fetch_allrecords_bypage('hospital_beds');
		$data['patients'] = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		
		$args = [ 'status'  => 'Active' ];
		$data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
		
		$args = [ 'status'  => 'Publish' ];
		$data['blogs'] = $this->adminModel->get_image_by_args('news_blog', $args, 3);
		$args = [
			'status'  => 'Verified', //Vrified, UnVerified, Deleted
			'is_del'  => 0 	//0: Non-Deleted, 1: Deleted
		];
		
		$data['patient_feeback'] = $this->adminModel->get_image_by_args('review_hospital', $args, 3);
		$data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
		return view('Home/index', $data);
	} //function - Closed


   /* @params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid 
	* (appointment ID), $puid
	* @desc: Show patient treatment expenses, done by hospital for patient
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function display_blank_page() {
		return view('Home/blank_page_with_header_footer'); 
	} //function - Closed


	//public function gallery() {
	public function gallery() {
		$args = [ 'status'  => 'Active' ];
		//$data['gallery'] = $this->adminModel->get_image_by_args('slider_image', $args, 8);
		$data['slider_image'] = $this->adminModel->get_image_by_args('slider_image', $args, 8);
		//$data['secound_gallery'] = $data['gallery'];
		
		// $args = [ 'status'  => 'Active' ];
		//$data['secound_gallery'] = $this->adminModel->get_image_by_args_as_order('gallery_image', $args, 8);
		$data['gallery_image'] = $this->adminModel->get_image_by_args_as_order('gallery_image', $args, 8);
		return view('Home/section/gallery', $data);
	}


   /* @params: Require, "Slot", "Doctor ID", "Doctor Name" (optional) and patient desired appointment "Date" 
	* @desc: Pick the Doctor availabile slot for getting doctor appointment by the Patient
	* @use: By patient for booking Doctor available slots
	* @author: Neoarks Team
	* @date: June 15th,  2023
	* @modify:
	*/ 
	public function pick_slots() {
		$this->data['dr_name'] 	= $this->request->getGet('dr_name');
		$this->data['dr_id'] 	= $this->request->getGet('dr_id');		
		$this->data['dt'] 		= $this->request->getGet('dt');
		$this->data['pick_slt'] = $this->request->getGet('pick_slt');
		$this->data['slot_id'] 	= $this->request->getGet('slot_id');
		
		if(!isset($this->data['dr_id']) || $this->data['dr_id'] == '') {
			$this->session->setTempdata('error', 'Doctor ID is missing.!', 3);
			$this->data['dr_slots'] = array(); //Just for addressing notice
			$this->data['doctors'] = array();  //Just for addressing notice
			return view('Home/section/show_dr_available_slots', $this->data);
		}
		else { //Get doctors & Departments for appointment page - //Ref doctor_appointment($id)
			//echo "<pre>";print_r($this->data);die;
			$this->args = [ 'ref_id' => $this->data['dr_id'] ]; //Doctor ID
			$this->data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $this->args);
			$this->data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
			//$this->session->setTempdata('success', 'Congratullations!, Doctor slots are availble. ', 3);
			return view('Home/section/doctor_appointment', $this->data);
		}
	} //Funciton - Closed

	
	public function doctor_appointment($id) {
		$args = [ 'id' => $id ]; //Doctor ID
		$data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
		$data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
		// return view('Home/section/doctor_appointment', $data);
		return view('Doctor/show_dr_available_slots', $data);
	}

	

	/*@params: Function for terms and condition 
	* @desc: Function for terms and condition
	* @author: Neoarks Team
	* @date: July 20th, 2023
	* @modify:
	*/
	public function terms_condition() {
		$args = [
			'status'  => 'Active'
		];
		$data['gallery'] = $this->adminModel->get_image_by_args('gallery_image', $args, 8);
		$args = [
			'status'  => 'Active'
		];
		$data['secound_gallery'] = $this->adminModel->get_image_by_args_as_order('gallery_image', $args, 8);
		return view('Home/terms_condition', $data);
	}

	/*@params: Function for privacy and policy
	* @desc: Function for privacy and policy
	* @author: Neoarks Team
	* @date: July 28th, 2023
	* @modify:
	*/
	public function privacy_policy() {
		$args = [
			'status'  => 'Active'
		];
		$data['gallery'] = $this->adminModel->get_image_by_args('gallery_image', $args, 8);
		$args = [
			'status'  => 'Active'
		];
		$data['secound_gallery'] = $this->adminModel->get_image_by_args_as_order('gallery_image', $args, 8);
		return view('Home/privacy_policy', $data);
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
				return redirect()->to(base_url().'/Admin/all_appointments');
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
				return redirect()->to(base_url().'/Admin/all_appointments');
			}
		}
		else {
			//return false;
			$this->session->setTempdata('error', 'Unexpected serial number ', 3);
			return redirect()->to(base_url().'/Admin/all_appointments');
		}
	} //Function - Closed


	/*@params: $tbl, $fld (table field for where condition)
	* @desc: Get already stored highest sereal on particular day or date for Generating patient visit number 
	* @author: Neoarks Team
	* @date: July 20, 2023
	* @modify:
	*/
	public function get_highest_today_serial($tbl, $fld) { 
		$this->tbl_name = $tbl;  	//booked_doctor_appointment - tbl 
		$this->where_field = $fld;	//booking_date - field

		$args = [ $this->where_field  => date('Y-m-d') ];
		$this->highest_pat_serial = $this->adminModel->today_highest_serial($this->tbl_name, $args);
		return $this->highest_pat_serial;
	}

	
	public function generate_puid($new_sn) { 
		$this->new_serial = $new_sn; //Serial Number
		$this->current_date = date('Y-m-d');
		
		$this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
		$this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
		return $this->puid;
	}
	//********************************  Generate Serial For Appointment ***********************/


	// public function book_appointment() {
	// 	$this->patient_session_id = session()->get('patient_session_id');
	// 	if ((!isset($this->patient_session_id)) || ($this->patient_session_id == '')) {
	// 		$this->patient_session_id  = 0; // Set a default value
	// 	}
		
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'name'              => 'required|min_length[4]|max_length[20]',
	// 			'mobile'            => 'required|numeric|exact_length[10]',
	// 			//'symptoms'        => 'required',
	// 			'age'          		=> 'required',
	// 			'gender'          	=> 'required',
	// 			//'email'           => 'required|valid_email',
	// 			'appointment_date'  => 'required',
	// 			'appointment_time'  => 'required',
	// 		];
			
	// 		//Generate Serial - START
	// 		$this->new_serial = 0;
	// 		$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date');
	// 		if(is_array($this->new_serial_arr) && count($this->new_serial_arr) > 0 ) {
	// 			// if(isset($this->new_serial_arr['pid'])) {
	// 			// 	$this->patient_id = $this->new_serial_arr['pid'];
	// 			// }
	// 			$this->new_serial = (int) $this->new_serial_arr['serial'];
	// 			if(isset($this->new_serial) && $this->new_serial !== 0 && $this->new_serial !== false && (is_numeric($this->new_serial))) {
	// 				$this->new_serial = $this->new_serial_arr['serial'];
	// 			}
	// 			else {
	// 				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
	// 				return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 			}
	// 		}
	// 		else {
	// 			$this->session->setTempdata('error', 'Unexpected serial data', 3);
	// 			return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 		} //Generate Serial - END
	// 		$this->puid = $this->request->getVar('puid',FILTER_SANITIZE_STRING);
	// 		if(!isset($this->puid) || $this->puid == '') { $this->puid = 0; }
	// 		if ($this->validate($rules)) {
	// 			$userdata = [
	// 				'patient_name'		=>  $this->request->getVar('name',FILTER_SANITIZE_STRING),
	// 				'serial'			=>	$this->new_serial,
	// 				'pid'				=>  $this->patient_session_id, //patient auto increment ID
	// 				'patient_email'     =>  $this->request->getVar('email'),
	// 				'patient_mobile'    =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
	// 				'age'    			=>  $this->request->getVar('age',FILTER_SANITIZE_STRING),
	// 				'gender'    		=>  $this->request->getVar('gender',FILTER_SANITIZE_STRING),
	// 				'puid'    			=>  $this->puid, //$this->request->getVar('puid',FILTER_SANITIZE_STRING), //Search patient ID
	// 				//'patient_login_id'	=> 	$this->patient_session_id,
	// 				'booking_date'      =>  $this->request->getVar('appointment_date',FILTER_SANITIZE_STRING),
	// 				'booking_time'      =>  $this->request->getVar('appointment_time',FILTER_SANITIZE_STRING),
	// 				'disease_symptoms'  =>  $this->request->getVar('symptoms',FILTER_SANITIZE_STRING),
	// 				'description'       =>  $this->request->getVar('desc',FILTER_SANITIZE_STRING),
	// 				'address'       	=>  $this->request->getVar('address',FILTER_SANITIZE_STRING),
					
	// 				'doctor_id'         =>  $this->request->getVar('doctor_id',FILTER_SANITIZE_STRING),
	// 				'doctor_name'       =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING), //Custom
	// 				'status'            => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
	// 				'created_at'        =>  date('Y-m-d h:i:s')
	// 			];
	// 			$status = false; //Just for addressing notices
	// 			if(isset($userdata['puid']) && $userdata['puid'] != '' ) { //Update Revisited Patient - START
	// 				$userdata['is_new'] = 0; //0: For old patients 1: New Patients
	// 			} //Update Revisited - START

	// 			$status = $this->adminModel->Insertdata('booked_doctor_appointment', $userdata);
	// 			//var_dump($status); // Check the value and type
	// 			if ($status == true) {
	// 				$args = ['id'   => $this->request->getVar('slot_id',FILTER_SANITIZE_STRING)];
	// 				$update_dtarr = array(
	// 					'booked'  		=> 1, //1: Patient booked, 0: Not booked	
	// 					'dr_available'  => 0, //1: Yes, 0: No
	// 					'serial'		=> $this->new_serial,
	// 					'patient_id'	=> $this->patient_session_id,
	// 					'updated_by'	=> $this->patient_session_uid 
	// 				);
	// 				$dr_slots_updt_status = $this->adminModel->update_rec_by_args('doctor_slots', $args, $update_dtarr);
	// 				if($dr_slots_updt_status === true) {
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
	// 						//return view('Home/payment_for_appointment');
	// 						//return view('Home/appointment_payment');
	// 						return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 					}
	// 					else {
	// 						$this->session->setTempdata('success', 'Appointment has booked successfully !, however, unable to email to Doctor', 3);
	// 						//return redirect()->to(base_url().'Doctor/doctors_available_slots/0');
	// 						//return view('Home/appointment_payment');
	// 						return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 					}
	// 				}
	// 				else {
	// 					$this->session->setTempdata('error', 'Unable to book appointment. Please try again', 3);
	// 					return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 				}
	// 			}
	// 			else {
	// 				$this->session->setTempdata('error', 'Sorry ! Unable to book appointment. Please try again', 3);
	// 				return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 			}  
	// 		} // Add New Patient - END
	// 		else { 
	// 			$this->session->setTempdata('error', 'Failed due to missing mandatory fields.!', 3);
	// 			return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 			// return redirect()->to(base_url().'Home/index');
	// 		}
	// 	}
	// 	return view('Home/appointment_payment');
	// }//Function - Closed

	/*********************** Appointent START ************************/
	public function book_appointment() {
		if (!(session()->has('patient_session_uid'))) { 
			$this->patient_session_uid = session()->get('patient_session_uid');	//`null` for non-loggedin-user
		}
		
		if (!(session()->has('patient_session_id'))) {  
			$this->patient_session_id = session()->get('patient_session_id'); //`null` for non-loggedin-user
		}
		if(!isset($this->patient_session_uid) || $this->patient_session_uid == '' || !isset($this->patient_session_id) || $this->patient_session_id == '') {
			$this->patient_session_uid = 0;	//For non-logged-in user
			$this->patient_session_id = 0;	//For non-logged-in user
		}

		$this->is_new_patient = 1; //For addressing notices -New Patient
		$userdata = array(); //Just for addressing notices

		if (!isset($this->patient_session_id) || ($this->patient_session_id == '') || (!isset($this->patient_session_uid)) || ($this->patient_session_uid == '')) {
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message'=>	'Unexpected patient ID or UID. Please talk to admin',
				'data'   => $userdata,
			);
			return json_encode($this->result_arr);
		}

		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'full_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Patient name  is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],
				'gender'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
						'required' => 'Gender is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
					],
				],
				'age'  => [
					'rules'     => 'required',//|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Age is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],
				'country_code'  => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Country Code is mandatory.',
					],
				],
				'mobile'  => [
					'rules'     => 'required|numeric|exact_length[10]',
					'errors'    => [
						'required' => 'Mobile is mandatory.',
						'min_length' => 'Minimum length is 10.',
						'max_length' => 'Maximum length is 15.'
					],
				],
				'appointment_date'  => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Appointment date is mandatory.'
						],
				],
				'appointment_time'  => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Appointment time is mandatory.',
					],
				],
				'doctor_id'  => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Doctor ID is mandatory.',
					],
				],
				'slot_id'  => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Slot ID is mandatory.',
					],
				],
			]; //validation Rules - Closed
			

			//Generate Serial - START
			$this->new_serial = 0;
			$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date'); //tbl, fld
			//var_dump($this->new_serial_arr);die;
			if(is_array($this->new_serial_arr) || is_object($this->new_serial_arr)) {
				if(count($this->new_serial_arr) > 0 ) {
					$this->new_serial = (int) $this->new_serial_arr['serial'];
					if(isset($this->new_serial) && $this->new_serial !== 0 && $this->new_serial !== false && (is_numeric($this->new_serial))) {
						$this->new_serial = $this->new_serial_arr['serial'];
					}
					else {
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	=> 200,
							'message'=>	'Sorry!, Failed to generate new serial',
							'data' => $userdata,
						);
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message'=>	'Appointment number expected greater than zero!',
						'data' => $userdata,
					);
					return json_encode($this->result_arr);
				}
			}
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'=>	'Unexpected serial format!',
					'data' => $userdata,
				);
				return json_encode($this->result_arr);
			} //Generate Serial - END

			$this->puid = $this->request->getPost('puid',FILTER_SANITIZE_STRING);
			if(!isset($this->puid) || $this->puid == '') { $this->puid = 0; }
			//Revisited Patient Check?
			// if(isset($userdata['puid']) && $userdata['puid'] != '' ) {
			// 	$this->is_new_patient = 0; //0: For old patients 1: New/First time visited Patients
			// } 

			//Revisited Patient Check?
			if(isset($userdata['puid']) && $userdata['puid'] != '' && $userdata['puid'] != 0 ) { 
				$this->is_new_patient = 0; //0: For old patients 1: New/First time visited Patients
			}

			$this->slot_id = $this->request->getPost('slot_id',FILTER_SANITIZE_STRING);
			if(!isset($this->slot_id) && $this->slot_id == '') {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'=>	'Slot is missing!',
					'data' => $userdata,
				);
				return json_encode($this->result_arr);
			}
			
			if ($this->validate($rules)) { 
				$userdata = [
					'patient_name'		=>  $this->request->getPost('full_name',FILTER_SANITIZE_STRING),
					'serial'			=>	$this->new_serial,
					'pid'				=>  $this->patient_session_id, //patient auto increment ID
					'patient_email'     =>  $this->request->getPost('email'),
					'national_id'       =>  $this->request->getPost('national_id',FILTER_SANITIZE_STRING),
					'country_code'    	=>  $this->request->getPost('country_code',FILTER_SANITIZE_STRING),
					'patient_mobile'    =>  $this->request->getPost('mobile',FILTER_SANITIZE_STRING),
					'age'    			=>  $this->request->getPost('age',FILTER_SANITIZE_STRING),
					'gender'    		=>  $this->request->getPost('gender',FILTER_SANITIZE_STRING),
					'puid'    			=>  $this->puid, //$this->request->getPost('puid',FILTER_SANITIZE_STRING), //Search patient ID
					'is_new'			=> 	$this->is_new_patient,
					'paid_apmt_fee'		=>	0, //Fee is not paid
					'slot_id'			=> 	$this->slot_id,
					'booking_date'      =>  $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING),
					'booking_time'      =>  $this->request->getPost('appointment_time',FILTER_SANITIZE_STRING),
					'disease_symptoms'  =>  $this->request->getPost('symptoms',FILTER_SANITIZE_STRING),
					
					'address'       	=>  $this->request->getPost('address',FILTER_SANITIZE_STRING),
					'status'			=>	1, //Appointment Initiated,
					'doctor_id'         =>  $this->request->getPost('doctor_id',FILTER_SANITIZE_STRING),
					'doctor_name'       =>  $this->request->getPost('doctor_name',FILTER_SANITIZE_STRING), //Custom
					'status'            =>  1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
					'created_at'        =>  date('Y-m-d h:i:s'),
					'created_by'        =>  $this->patient_session_uid,
				];
				
				$last_insrt_apmt_id = $this->commonForAllModel->Insertdata_return_id('booked_doctor_appointment', $userdata);

				if((int) $last_insrt_apmt_id > 0) { 
					$userdata['last_insrt_apmt_id'] = $last_insrt_apmt_id; 
					//For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$userdata['appointment_date'] 	= $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING); //For sending email
					$userdata['appointment_time'] 	= date('h:i:s'); //For sending email 
					$userdata['slot_id'] 	= $this->slot_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$userdata['uid'] 	= $this->patient_session_uid; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$this->result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` whereever status is true with SQL err 
						'code'	=> 200,
						'message'=>	'Appointment booked successfully without appointment fee',
						'data' => $userdata,
					);
					return json_encode($this->result_arr);
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message'=>	'Sorry!, Failed to book an appointment',
						'data' => $userdata,
					);
					return json_encode($this->result_arr);
				}
			} // Add New Patient - END
			else { 
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'=>	'Failed due to missing mandatory fields!',
					'data' => $userdata,
				);
				return json_encode($this->result_arr);
			}
		}
		else { 
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message'=>	'Unexpected request method.!',
				'data' => $userdata,
			);
			return json_encode($this->result_arr); 
			//return view('Home/appointment_payment', $userdata); //Render - Payment form
		}
	}//Function - Closed


	public function run_appointment_fee_form() {
		return view('Home/appointment_payment');
	}


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
		$this->is_new_patient = 1; //For addressing notices -New Patient
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
				'patient_name'	=> [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Patient name  is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],
				'doctor_name'	=> [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Doctor name  is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],
				'appointment_date'	=> [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Appointment date is mandatory.',
					],
				],
				'appointment_time'	=> [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Appointment slot is mandatory.',
					],
				],
				'pay_option'        => [
					'rules'     => 'required',
					'errors'    => [
						'required' => 'Payment option is mandatory.',
					],
				],
			];

			if (!$this->validate($rules)) {
				$this->result_arr = array(
					'status' => false,
					'error'	 => false, //error: get the validation automatically
					'code'	=> 200,
					'message' => 'Sorry!, Mandatory fileds validation failed.',
					'data' => $this->result_dt_arr,
				);
				return json_encode($this->result_arr);
			}
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
					'doctor_id'	=> $this->doctor_id,
					'doctor_name' 	=> $this->doctor_name, 
					'country_code' 	=> $this->country_code,
					'patient_phone' => $this->patient_mobile,
					'paid_amount'	=> $this->apmt_regis_fee, //should add Tax Amount or use Total Amount`
					'payment_note'	=> 'Paid registration fee',
					'serial'		=> 	$this->serial,
					'pid' 	        =>  $this->patient_session_id, //$this->pid,
					'puid'          =>  $this->puid,
					'patients_id'   =>  0, //$this->patient_session_id,
					'pay_mode'		=>  $this->pay_method,
					'apmt_id'    	=>  $this->last_insrt_apmt_id,
					'pay_date'      =>  $this->appointment_date, //date('Y-m-d'),
					'created_at'	=>  date('Y-m-d h:i:s'),
					'created_by'	=>  $this->patient_session_id,
				];

				//echo "<pre>"; print_r($this->paid_amt_fee_arr);die;
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
					if(!isset($this->patient_email) || $this->patient_email == '' ) {
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
						'message' => 'Failed to insert pay charges table.',
						'data' => $this->result_dt_arr,
					);
					return json_encode($this->result_arr);
				}
			} //Appointment Fee ONLINE mode - END
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
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
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message' => 'Sorry!, Failed due to unxpected request method',
				'data' => $this->result_dt_arr,
			);
			return json_encode($this->result_arr);
		}
	}//Function - Closed	

	/*********************** Appointent END ************************/

	public function blog_archive(){
		$args = [ 'status'  => 'Publish' ];
		$data['blogs'] = $this->adminModel->get_image_by_args('news_blog', $args, 7);
		$args = [ 'status'  => 'Publish' ];
		$data['secound_blogs'] = $this->adminModel->get_image_by_args_as_order('news_blog', $args, 8);
		$data['most_view_blog'] = $this->commonForAllModel->fetch_allrecords_bypage('most_viewed_blog');
		return view('Home/section/blog_archive', $data);
	}

	public function view_blog($id){
		$blog = [
			'blog_id'     => $id,
			'browser'     => $this->getUserAgent(),
			'ip'          => $this->request->getIPAddress(),
			'blog_time'   => date('Y-m-d h:i:s'),
			'vist_date'   => date('Y-m-d h:i:s')
		];
		$this->adminModel->Insertdata('most_viewed_blog', $blog);
		$args = [ 'status'  => 'Publish' ];
		$data['blogs'] = $this->adminModel->get_image_by_args('news_blog', $args, 7);
		$args = [ 'id'  => $id ];
		$data['view_blog'] = $this->adminModel->fetch_rec_by_args('news_blog', $args);
		return view('Home/section/view_blog', $data);
	}

	public function success() { return view('Home/section/success'); }

	public function features(){
		$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		$data['patients'] = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		$args = [ 'status'  => 'Active' ];
		$data['why_choose'] = $this->adminModel->get_image_by_args('gallery_image', $args, 7);
		return view('Home/section/features', $data);
	}

	public function about_us(){
		// $data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');  		//Fetch `Active` & `InActive` doctors
		// $data['patients'] = $this->commonForAllModel->fetch_allrecords_bypage('patients');	//Fetch `Active` & `InActive` patients

		$data['doctors'] = $this->adminModel->fetch_all_records_by_active('doctor');	//Fetch `Active` & `ACTIVE` doctors
		$data['patients'] = $this->adminModel->fetch_all_records_by_active('patients'); //Fetch `Active` & `ACTIVE` patients
		return view('Home/section/about_us', $data);
	}

	public function contact_us(){
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				//'name'       => 'required',
				'name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' =>   'Name  is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'subject'    => 'required',
				'mobile'    => 'required',
				'email'      => 'required|valid_email',
			];
			if ($this->validate($rules)) {
					$userdata = [
						'name'          =>  $this->request->getVar('name',FILTER_SANITIZE_STRING),
						'subject'		=>  $this->request->getVar('subject',FILTER_SANITIZE_STRING),
						'email'         =>  $this->request->getVar('email',FILTER_SANITIZE_STRING),
						'message'       =>  $this->request->getVar('message',FILTER_SANITIZE_STRING),
						'mobile'        =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'status'        => 'Active', 
						'created_at'    =>  date('Y-m-d h:i:s')
					];
					$status = $this->adminModel->Insertdata('contact_us', $userdata);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! Your Message Send Successfully !', 3);
					}else{
						$this->session->setTempdata('error', 'Sorry ! Unable to Send Your Message Try Again ?', 3);
					}  
					return redirect()->to(base_url().'/Home/success');
			}else{
				$data['validation'] = $this->validator;
			}
		}
		return view('Home/section/contact_us', $data);
	}


	public function getUserAgent(){
		$agent = $this->request->getUserAgent(); //predefine method
		if ($agent->isBrowser()) {
			$currentAgent  = $agent->getBrowser();
		}else if ($agent->isRobot()) {
			$currentAgent  = $this->agent->robot();
		}else if ($agent->isMobile()) {
			$currentAgent  = $agent->getMobile();
		}else{
			$currentAgent  = 'Unidentified User Agent';
		}
		return $currentAgent;
	}

	public function book_appointment_section(){
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'name'              => 'required|min_length[4]|max_length[20]',
				'name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Name  is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],

				'mobile'            => 'required|numeric|exact_length[10]',
				'Symptoms'          => 'required',
				'email'             => 'required|valid_email',
				'appointment_date'  => 'required',
				'department'        => 'required',
				'appointment_time'  => 'required',
			];
			if ($this->validate($rules)) {
				$userdata = [
					'patient_name'		=>  $this->request->getVar('name',FILTER_SANITIZE_STRING),
					'patient_email'     =>  $this->request->getVar('email'),
					'patient_mobile'    =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
					'booking_date'       =>  $this->request->getVar('appointment_date',FILTER_SANITIZE_STRING),
					'booking_time'       =>  $this->request->getVar('appointment_time',FILTER_SANITIZE_STRING),
					'patient_issue'     =>  $this->request->getVar('Symptoms',FILTER_SANITIZE_STRING),
					'description'       =>  $this->request->getVar('description',FILTER_SANITIZE_STRING),
					'department'        =>  $this->request->getVar('department',FILTER_SANITIZE_STRING),
					'doctor_id'         =>  $this->request->getVar('doctor_id',FILTER_SANITIZE_STRING),
					'doctor_name'       =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING),
					'status'            => 'Appointment', 
					'created_at'        =>  date('Y-m-d h:i:s')
				];
				$status = $this->adminModel->Insertdata('booked_doctor_appointment', $userdata);
				if($status == true) {// Need to use unique ID/primary ID here
					$to        = $this->request->getVar('email');
					$subject   = 'Booking Appointment  - Hospital Management System';
					$message   = 'Hi ' .$this->request->getVar('name',FILTER_SANITIZE_STRING).",
						<br>Thanks You are Booked Appointment to Dr. ".$this->request->getVar('doctor_name',FILTER_SANITIZE_STRING). "<br><br> Thanks For Your Booking <br> Your Booking date is :"
						.$this->request->getVar('appointment_date',FILTER_SANITIZE_STRING)."<br> Booking Time is:<b>".$this->request->getVar('appointment_time',FILTER_SANITIZE_STRING)."</b>";
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL', 'Info');
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Appointment Booked Successfully !',3 );
						}else{
							//$this->session->setTempdata('error', ' Sorry Unable to Send Booked Doctors. '. ISU_SUPPORT . DEV_AUTHOR);
							$this->session->setTempdata('success', 'Appointment Booked successfully !, however, unable to inform Dr.', 3);
						}
						return redirect()->to(base_url().'/Home/success');
				}
				else {
					$this->session->setTempdata('success', 'Appointment booked successfully !, however, inactive slot', 3);
					return redirect()->to(base_url().'/Home/success');
				} 
				return redirect()->to(base_url().'/Home/success');
			}else{
				$data['validation'] = $this->validator;
			}
			$data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
			return view('Home/index', $data);
		}
	}



}

	

	//Client ID = 597704537216-cacjdto2hchrfsfd8qng9pk4g0376gp6.apps.googleusercontent.com
	//secret key = 5yRtZTTu0el4y74t5RXmMDI1

	//--------------------------------------------------------------------


