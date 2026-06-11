<?php namespace App\Controllers;
/** 
 * Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
 * @Description: The code of the released Hospital software, does NOT lie under
 * GLP (General Public License) But it has proprietary copyrights. The purpose of the
 * Informing for public that, the Hospital web based mobile responsible application 
 * its associated
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

   /* @param: Render Upload Ward (csv) form
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	// public function browse_wards() {
    //     return view('Admin/ManageAssets/upload_wards');
    // }

   /* @param: Import Ward csv data into table
	* @desc: 
	* @use: Admin
	* @return:
	* @date: September 20th, 2025
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
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
	

	/*@params: Render Home page & fetch the required data for home page 
	* @desc: 
	* @retuns:
	* @author: Neoarks Team
	* @date: 23rd January, 2024
	* @modify:
	*/
	public function homemgt() {
		$data = [];
		if ($this->request->getMethod() != 'get') { //Load login form
			$data['error']  = 'Unexpected request method. Please report a bug.';
			return view('Admin/admin_home', $data);
		}
		$args = [ 'status'  => 'Active' ];
		$data['slider'] = $this->adminModel->get_image_by_args('slider_image', $args, 5);
		
		$args = [ 'status'  => 'Active' ];
		$data['why_choose'] = $this->adminModel->get_image_by_args('gallery_image', $args, 7);
		//$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		$data['patients'] = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		
		$args = [ 'status'  => 'Active' ];
		$data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
		
		$args = [ 'status'  => 'Publish' ];
		$data['blogs'] = $this->adminModel->get_image_by_args('news_blog', $args, 3);
		$args = [
			'status'  => 'Verified' //for showing vrified and un-verified reviews by admin
		];
		
		$data['patient_feeback'] = $this->adminModel->get_image_by_args('review_hospital', $args, 3);
		$data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
		return view('Admin/admin_home', $data);
	} //function - Closed


	/*@param: index function of Admin
	* @desc: Front page for admin
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 17th April, 2023
	* @modify
	*/
	public function index() {
		$data = [];
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$this->admin_uid = session()->get('admin_session_uid');
	        $this->admin_id = session()->get('admin_session_id');
	        if (!isset($this->admin_uid) || !isset($this->admin_id)) {
	            $this->session->setTempdata('error', 'Admin ID is missing !', 3);
	            return redirect()->to(base_url() . "/Login");
	        }
			$data['loggedin_usr'] = $this->adminModel->getLoggedInAdminData($this->admin_uid);
			if (!isset($data['loggedin_usr']->email) || $data['loggedin_usr']->email == false) {
				return redirect()->to(base_url() . "/Login");
			}
			//Checking Activation Account is Activate to Access Control or Not	
			$args = ['email'  => 
				$data[
					'loggedin_usr']->email,
					'is_del'	=> 0
				];
			$check_account = $this->adminModel->fetch_rec_by_args('admin_users', $args);
			
			$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
			$data['patients'] = $this->commonForAllModel->fetch_allrecords_bypage('patients');
			$args = ['is_del'=>0];
			//$data['today_appointment'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
			$data['all_appointments'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            //Total Patient Records - Dashboard Script Start
			$total_patients    = $this->commonForAllModel->fetch_allrecords_bypage('patients');  //Q? Why it required again?
			$total_appointment = $this->commonForAllModel->fetch_allrecords_bypage('booked_doctor_appointment');
			//Total Patient Records

			//Count Medical All Medical Expense
			$medical_income = $this->commonForAllModel->fetch_allrecords_bypage('sale_products');
			$total_income = 0;
			if ($medical_income) {
				count($medical_income);
				foreach ($medical_income as $count_inc) {
					$total_income += $count_inc->quantity *  $count_inc->rate;
				}
			} else {
				$total_income = 0;
			}
			$medical_earning =  json_encode($total_income);
			//Count Medical All Medical Expense

			//Count Patient Earning 
			$patient_income = $this->commonForAllModel->fetch_allrecords_bypage('patients');
			$total_patient_inc = 0;
			if ($patient_income) {
				count($patient_income);
				foreach ($patient_income as $count_inc) {
					$total_patient_inc += $count_inc->other_fee +  $count_inc->entry_fee;
				}
			} else {
				$total_patient_inc = 0;
			}
			$patient_earning =  json_encode($total_patient_inc);

			//Count Patient Earning 
			$data['chart_data']  = [
				'ch_medical_earning'     => $medical_earning,
				'ch_patient_earning'     => $patient_earning,
				'total_patients'         => $total_patients ? count($total_patients) : '0',
				'total_appointment'      => $total_appointment ? count($total_appointment) : '0'
			];
			//Dashboard Script Start 

			if ($data === false) {
				$this->session->setTempdata('error', 'Sorry ! Record not found', 3);
				return redirect()->to(base_url() . 'Admin/dashboard');
			} else if (is_array($data)) {
				return view('Admin/dashboard', $data);
			} else {
				$this->session->setTempdata('error', 'Unexpected result set', 3);
				return redirect()->to(base_url() . 'Admin/dashboard');
			}
		}
	} // Function - Closed


   /* @param: 
	* @desc: 
	* @use: Admin
	* @return:
	* @date: 
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function run_appointment_fee_form() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		return view('Admin/appointment_payment');
	}

   /* @param: Send email
	* @desc: Send email 
	* @return: Result set or false if not existing
	* @use:Admin, Doctor, 
	* @author: Neoarks Team
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

   /* @param: 
	* @desc: 
	* @use: Admin
	* @return:
	* @date: 
	* @modify
	* @author: Neoarks Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
	public function add_appointment_fee() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$this->admin_uid = session()->get('admin_session_uid');
	        $this->admin_id = session()->get('admin_session_id');
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

	/*@param: Logged-in user's `uid` used from session 
    * @desc: Upload Profile pic
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */

	public function upload_profile_pic() {
		if (!(session()->has('admin_session_uid')) && !(session()->has('admin_session_id'))) {
			$this->result_arr = [
	 				'status' => false,
					'error'  => true, //error: `true` whereever status is false with SQL err 
	 				'code' => 200,
	 				'data' => [],
					'message' => 'Session has expired. Please relogin again.'
	 			];
	 		//return json_encode($this->result_arr);
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid'); //Loggedin User uid
		$this->admin_id = session()->get('admin_session_id');
		
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
			$this->profile_pic = $this->request->getFile('uploaded_file');
			$this->file_name = $this->profile_pic->getName();
			if($this->file_name && $this->file_name != '') {
				$args = [ 'id'  => $this->admin_id ];
						
				$this->old_data = $this->adminModel->fetch_rec_by_args('admin_users', $args);
				
				if(isset($this->old_data[0]->profile_pic)) {

					if(file_exists(FCPATH . 'uploads/accounts/admin/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/accounts/admin/' . $this->old_data[0]->profile_pic);
					} //else - Not needed
				} //else - Not needed	

				$this->random_name = $this->profile_pic->getRandomName();
				if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
					$this->profile_pic->move(FCPATH . 'uploads/accounts/admin', $this->random_name);// Move the uploaded file to the destination folder
					
					$this->user_data_arr = [ // Prepare data to update in the database
						'profile_pic' =>  $this->random_name, //$this->file_name,
						'updated_at'  =>  date('Y-m-d H:i:s'),
						'updated_by'  =>  $this->admin_id,
					];
					$args = ['id' => $this->admin_id];// Specify conditions to update the database record
					$status = $this->adminModel->update_rec_by_args('admin_users', $args, $this->user_data_arr);// Update the database record
					if ($status === true) {// Check if the database update was successful
						$this->result_arr = [
							'status' => true,
							'code' => 200,
							'data' => '',
							'message' => 'Photo uploaded successfully'
						];
						return json_encode($this->result_arr);
					} 
					else {
						$this->result_arr = [
							'status' => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err
							'code' => 200,
							'data' => '',
							'message' => 'Failed to upload Photo'
						];
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
			}
			else {
				$this->result_arr = [
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err
					'code' => 200,
					'data' => '',
					'message' => 'Please choose profile picture'
				];
				return json_encode($this->result_arr);
			}
		} 
		else {
			$this->result_arr = [// If the request method is not POST, return an unexpected request method message
				'status' => false,
				'error'		=> true, //error: `true` whereever status is false with SQL err
				'code' => 200,
				'data' => [],
				'message' => 'Unexpected request method. Please try again'
			];
			return json_encode($this->result_arr);
		}
    } // Function - Closed

	/*@param: Logged-in user's `uid` used from session 
    * @desc: View user Profile
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	
	// public function view_profile(){   
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 		}
	// 		else {
	// 			$this->admin_uid = session()->get('admin_session_uid');
	// 			if ($this->request->getMethod() == 'get') {
	// 				$this->data['profile_record']  = $this->adminModel->getLoggedInAdminData($this->admin_uid);
	// 				if($this->data['profile_record'] === false) {
	// 					return view('Admin/view_profile', $this->data);
	// 					return redirect()->to(base_url() . 'Admin/view_profile', $this->data);
	// 					// echo "false value";die;}
	// 					$this->session->setTempdata('error', 'fasle value ', 3);
	// 					return redirect()->to(base_url() . 'Admin/view_profile', $this->data);
	// 				else {
	// 				return view('Admin/view_profile', $this->data);
	// 			}
	// 		}
	// 		else {
	// 			$this->session->setTempdata('error', 'Unexpected request method !', 3);
	// 			return view('Admin/view_profile', $this->data);
	// 		}
	// 		//echo "<pre>";print_r($this->data);die;
	// 	}
	// } //function - Closed

	/*@param: 
    * @desc: View user Profile
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	public function view_profile(){   
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		else {
			$this->admin_uid = session()->get('admin_session_uid');
			if ($this->request->getMethod() == 'get') {
				$this->data['profile_record']  = $this->adminModel->getLoggedInAdminData($this->admin_uid);
				if($this->data['profile_record'] === false) {
					return view('Admin/view_profile', $this->data);
					//echo "false value";die;
					$this->session->setTempdata('error', 'fasle value ', 3);
						return redirect()->to(base_url() . 'Admin/view_profile', $this->data);
					
				}
				else {
					return view('Admin/view_profile', $this->data);
				}
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Admin/view_profile', $this->data);
			}
		}
	} //function - Closed

	/*@param: 
    * @desc: Update user Profile
    * @use: Update Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	public function update_profile() {
        if (!(session()->has('admin_session_uid'))) {
            return redirect()->to(base_url() . "/Login");
        }
        else {
            $this->admin_uid = session()->get('admin_session_uid');
			$this->data = [];
			$this->data['validation'] = null;
			$rule = [
				//'full_name' => 'required|min_length[4]|max_length[20]',
				'full_name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'Doctor name is mandatory.',
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
					$this->args = [ 'uid'  => $this->admin_uid, ];
					$this->user_data_arr = [
						'full_name'		 => $this->request->getPost('full_name', FILTER_SANITIZE_STRING),
						'about_me'		 => $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
						'gender'     	=>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
						'country_code'  	=>  $this->request->getPost('selectedCountryCode', FILTER_SANITIZE_STRING),
						'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
						'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
						'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
						'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
						'address'       =>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
						'pinzip'        =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
						'email'         =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
						'updated_at'    => date('Y-m-d H:i:s'),
						'updated_by'    => $this->admin_uid,
					];
					//echo "<pre>"; print_r($this->user_data_arr);die;
					$this->data['profile_updt_status'] = $this->adminModel->update_rec_by_args('admin_users', $this->args, $this->user_data_arr);
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
							'error'		=> false, //error: `false` with status `true`
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

	
	/* @param: Add ward function  as Admin
	* @desc: Add details of ward
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function add_ward() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		$this->dept_args = [
			'is_del' 	=> 0,
			'status' 	=> 'Active',
		];
		$this->data = [];
		$this->order = [
				'column_name'  => 'department_name',
				'order'        => 'asc'
			];

		//$this->data['departments'] = $this->adminModel->fetch_rec_by_args('department', $this->dept_args);
		//$this->data['departments'] = $this->adminModel->filter_rec_by_args_with_status('department', $this->order, $this->dept_args);
		$this->data['departments'] = $this->commonForAllModel->filter_rec_by_args_with_status('department', $this->order, $this->dept_args);
		
		
		$this->data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'dept_name' 	=> 'required',
				'dept_id'		=> 'required',
				//'ward_name'     => 'required',
				'ward_name'  => [
					'rules'     => 'required|min_length[4]|max_length[30]',
					'errors'    => [
						'required' => 'Ward  name is mandatory',
						'min_length' => 'Ward name minimum required length is 4.',
						'max_length' => 'Ward name maximum required length is 30.'
					],
				],
			];
			
			if ($this->validate($rules)) { 
				//is ward exist- START
				$this->dept_id = $this->request->getPost('dept_id', FILTER_SANITIZE_STRING);
				$this->ward_name = $this->request->getPost('ward_name', FILTER_SANITIZE_STRING);
				$this->arg1 = ['dept_id'	=> $this->dept_id];
				$this->arg2 = ['ward_name'	=> $this->ward_name];
				$this->args_arr = [];
				array_push($this->args_arr, $this->arg1);
				array_push($this->args_arr, $this->arg2);
						
				if(!$this->adminModel->is_zero_where_multi_args('hospital_wards', $this->args_arr)) {
					$this->result_arr = array(
						'status'	=> false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'		=> 200,
						'message'	=> 'Sorry ! Ward name is already existing under selected department.',
						'data'		=> $this->data
					);
					return json_encode($this->result_arr);
				} //is ward exist- END
				$this->user_data_arr = [
					'ward_name'       =>  $this->ward_name,
					'ward_desc'       =>  $this->request->getPost('ward_desc'),
					'dept_id'         =>  $this->dept_id,
					'dept_name'       =>  $this->request->getPost('dept_name', FILTER_SANITIZE_STRING),
					'status'          => 'Active',
					'created_at'      =>  date('Y-m-d h:i:s'),
					'created_by'      =>  $this->admin_uid,
				];
				
				$status = $this->adminModel->Insertdata('hospital_wards', $this->user_data_arr);
				if ($status == true) {
					$this->result_arr = array(
						'status'	=> true,
						'error'		=> false, //error: `false` with status `true`
						'code'		=> 200,
						'message'	=> 'Congratulation ! ward added successfully !',
						'data'		=> $this->data,
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status'	=> false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'		=> 200,
						'message'	=> 'Sorry ! Unable to add  ward  try again!',
						'data'		=> $this->data,
					);
					return json_encode($this->result_arr);
				}
			} 
			else {  
				$this->data['validation'] = $this->validator; 
				$this->data = $this->data['validation']->getErrors(); //send validation array
				$this->result_arr = array(
					'status'	=> false,
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code'		=> 200,
					'message'	=> 'validation failed',
					'data'		=> $this->data,
				);
				return json_encode($this->result_arr);
			}
		} 
		return view('Admin/add_ward', $this->data); //Rendering form - Non-Ajax with 'GET' method
	} //function - Closed


	/* @param: Update Stock function  as Admin
	* @desc: Function used for updating stock through admin
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function update_stock($id) { 
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}else{
			$args = [ 'id'  => $id ];

			$data = [
				'med_stock' => $this->request->getVar('med_stock',FILTER_SANITIZE_STRING)
			];
			
			// $status = $this->adminModel->update_rec_by_args('medicines', $args, $data);
			$status = $this->adminModel->update_rec_by_args('medicines', $args, $data);
			
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! medicines  stock has updated successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to update medicine stock.  Please try again.', 3);
			} 
	        //return redirect()->to(base_url().'Admin/add_medicine_stock/'.$id); //old rediction
			return redirect()->to(base_url().'Admin/manage_medicine');
		}
	}

 	/* @param: Change admin password
    * @description: Admin can change there password
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */ 
	public function change_admin_password(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$this->admin_uid = session()->get('admin_session_uid');

		$data = [];
		$data['loggedin_usr'] = $this->adminModel->getLoggedInUserData($this->admin_uid, 'admin_users');
		if ($this->request->getMethod() == 'get') {
			return view('Admin/change_admin_password', $data);
		} 
		else if ($this->request->getMethod() == 'post') {
			$rules = [
				//'old_password' => 'required',
				// 'new_password' => 'required|min_length[6]|max_length[20]',

				'old_password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
						'required' 	=> 'Old password is mandatory.',
						'min_length' => 'Old password minimum required  length is 6.',
						'max_length' => 'Old password maximum required length is 20.'
					],
				],

				'new_password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
						'required' 	=> 'New password is mandatory.',
						'min_length' => 'New password minimum required length is 6.',
						'max_length' => 'New password maximum required length is 20.'
					],
				],

				//'confirm_password' => 'required|matches[new_password]',

				'confirm_password'  => [
					'rules'     => 'required|matches[new_password]|min_length[6]|max_length[20]',
					'errors'    => [
						'required' 		=> 'Confirm password is mandatory.',
						'matches' 		=> 'New password and confirm password is not matched.',
						'min_length' 	=> 'Confirm password length must at least 6.',
						'max_length' 	=> 'Confirm password length must at max 20.'
					],
				],
			];
			if ($this->validate($rules)) { 
				$opwd = $this->request->getVar('old_password');
				$npwd = password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT);
				if ($data['loggedin_usr'] === false) { 
					$this->session->setTempdata('error', 'Sorry! User record is not found!', 3);
					return view('Admin/change_admin_password', $data);
				} 
				else {
					if (!isset($data['loggedin_usr']->password) || $data['loggedin_usr']->password == '') {
                    	$this->session->setTempdata('error', 'password index is not in the result set!', 3);
						//return redirect()->to(current_url());
						return view('Admin/change_admin_password', $data);
                	} 
					else {
						if (password_verify($opwd, $data['loggedin_usr']->password)) { 
							$status = $this->adminModel->updatePassword('admin_users', $npwd, session()->get('admin_session_uid'));
							if ($status) { // Update the password // Increment session version in the database
								$this->adminModel->incrementSessionVersion('admin_users', session()->get('admin_session_uid'));
								$this->session->setTempdata('success', 'Congratulations! password has updated successfully!', 3);
								//return redirect()->to(current_url());
								return view('Admin/change_admin_password', $data);
								//session()->destroy();
							} 
							else {
								$this->session->setTempdata('error', 'Sorry Unable to update password. Please try again!', 3);
								//return redirect()->to(current_url());
								return view('Admin/change_admin_password', $data);
							}
						} 
						else {
							$this->session->setTempdata('error', 'Incorrect old password', 3);
							return view('Admin/change_admin_password', $data);
						}
					} //else -loop closed
				}//else -loop closed
			} 
			else { 
				//$this->session->setTempdata('error', 'Oops!, Validation failed for mandatory fields', 3);
				$data['validation'] = $this->validator;
				return view('Admin/change_admin_password', $data);
			}
		} 
		else {
			$this->session->setTempdata('error', 'Oops!, Unexpected request method', 3);
			return view('Admin/change_admin_password', $data);
		}
		//return view('Admin/change_admin_password', $data);
	} //function - Closed
	

	
	//Try -2
	// public function change_admin_password() {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} 
	
	// 	$this->admin_uid = session()->get('admin_session_uid');
	
	// 	$data = [];
	// 	$data['loggedin_usr'] = $this->adminModel->getLoggedInUserData($this->admin_uid, 'admin_users');
		
	// 	if ($this->request->getMethod() == 'get') {
	// 		return view('Admin/change_admin_password', $data);
	// 	}
	// 	else if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'old_password' => 'required',
	// 			'new_password' => 'required|min_length[6]|max_length[20]',
	// 			'confirm_password' => 'required|matches[new_password]',
	// 		];
	
	// 		if ($this->validate($rules)) {
	// 			$opwd = $this->request->getVar('old_password');
	// 			$npwd = password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT);
	
	// 			if ($data['loggedin_usr'] === false) {
	// 				$this->session->setTempdata('error', 'Sorry! User record is not found!', 3);
	// 			}
	//Try -2
	// public function change_admin_password() {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} 
	
	// 	$this->admin_uid = session()->get('admin_session_uid');
	
	// 	$data = [];
	// 	$data['loggedin_usr'] = $this->adminModel->getLoggedInUserData($this->admin_uid, 'admin_users');
		
	// 	if ($this->request->getMethod() == 'get') {
	// 		return view('Admin/change_admin_password', $data);
	// 	}
	// 	else if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'old_password' => 'required',
	// 			'new_password' => 'required|min_length[6]|max_length[20]',
	// 			'confirm_password' => 'required|matches[new_password]',
	// 		];
	
	// 			else if (!isset($data['loggedin_usr']->password) || $data['loggedin_usr']->password == '') {
	// 				$this->session->setTempdata('error', 'password index is not in the result set!', 3);
	// 			}
	// 			else if (password_verify($opwd, $data['loggedin_usr']->password)) {
	// 				// Update the session_token in the users table
	// 				$this->adminModel->updateSessionToken('admin_users', $this->admin_uid);
	
	// 				// Update the password
	// 				$status = $this->adminModel->updatePassword('admin_users', $npwd, $this->admin_uid);
	
	// 				if ($status) {
	// 					$this->session->setTempdata('success', 'Congratulation! Password Updated Successfully!', 3);
	// 					return redirect()->to(current_url());
	// 				} else {
	// 					$this->session->setTempdata('error', 'Sorry Unable to Update Password. Try Again!', 3);
	// 					return redirect()->to(current_url());
	// 				}
	// 			} 
	// 			else { 
	// 				$this->session->setTempdata('error', 'Incorrect login email or password', 3); 
	// 			}
	// 		} 
	// 		else { 
	// 			$this->session->setTempdata('error', 'Oops!, Validation failed for mandatory fields', 3);
	// 			$data['validation'] = $this->validator;
	// 		}
	// 	}
	// 	else {
	// 		$this->session->setTempdata('error', 'Oops!, Unexpected request method', 3);
	// 	}
	
	// 	return view('Admin/change_admin_password', $data);
	// }
	

	/* @param: Invalidate other sessions
	* @desc: Kill session from other devices on change password
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	private function invalidateOtherSessions($adminUserId){
		// Get the current session ID
		$currentSessionId = session_id();
	
		// Get the session database handler
		$sessionHandler = \Config\Services::session();
	
		// Get all sessions for the admin user from the database
		$adminUserSessions = $this->adminModel->getAdminUserSessions($adminUserId);
	
		foreach ($adminUserSessions as $session) {
			if ($session['id'] !== $currentSessionId) {
				// Destroy the session
				$sessionHandler->destroy($session['id']);
			}
		}
	} // Function - Closed
	
	
	

   /* @param: Change patient status for today discharge
	* @desc: Change status of today discharged patients in admin
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function change_today_discharged_patient_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$args = [ 'id'  => $id ];
		
		$admin_uid = session()->get('admin_session_uid');
		if (!isset($admin_uid) || $admin_uid == '') {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [
			'status'  => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $admin_uid,
		];
		//Custom - End
		$status = $this->adminModel->update_rec_by_args('patients', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! action done  successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to discharge. Please try again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_today_discharged_patient');
	}	// Function - Closed

	/* @param: Manage ward as Admin
	* @desc: Admin can see the list of department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function manage_ward	() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'is_del'  => 0, ];
		$order = [
				'column_name'  => 'ward_name',
				'order'        => 'asc'
			];
		$data = [
			//'ward' => $this->adminModel->fetch_rec_by_args_arr('hospital_wards', $args),
			'ward' => $this->commonForAllModel->fetch_rec_by_args_arr_dynamic_orderby('hospital_wards', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if(!isset($data['ward']) || $data['ward'] == '') { 
			$this->session->setTempdata('error', 'Sorry ! No ward found. Please add ward first', 3);
			return redirect()->to(base_url()."Admin/add_ward");
		}
		return view("Admin/manage_ward", $data);
	} // Function - Closed

	/* @param: Search ward as Admin
	* @desc: Admin can search the ward
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function search_ward() {
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('ward_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('hospital_wards', 'ward_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'ward' => $this->adminModel->fetch_rec_by_args_with_status('hospital_wards', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/manage_ward', $data);
	} // Function- Closed

	/* @param: Filter ward as Admin
	* @desc: Admin can ward  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function filter_ward($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_ward') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_ward') {
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
			// 'status'        => 'Discharged'
			'is_del' => '0',
		];
	$data = [
			
			'ward' => $this->adminModel->filter_rec_by_args_with_pagination('hospital_wards', $order,  $args),
			'pager'   => $this->adminModel->pager
		];
		return view('Admin/manage_ward', $data);
	} //Function - Closed


	// /* @param: Edit ward as Admin
	// * @desc: Admin can edit  ward
	// * @use: Admin
	// * @return:
	// * @author: Neoarks Team
	// * @date: 13th July,2023
	// * @modify
	// */
	public function edit_ward($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'status'  => 'Active' ];
		$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $args);

		$args = [ 'id'  => $id ];
		$data['ward'] = $this->adminModel->fetch_rec_by_args('hospital_wards', $args);
	//	echo "<pre>";print_r($data['departments']);die;
		return view('Admin/edit_ward', $data);
	} //Function - Closed

	// public function edit_ward($id) {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}

	// 	$this->ward_args = [ 'id' 	=> $id,];
	// 	$data['selected_ward'] = $this->adminModel->fetch_rec_by_args('hospital_wards', $this->ward_args);
	// 	if(isset($data['selected_ward']) == false) {
	// 		$this->session->setTempdata('error', 'Sorry ! Ward is not found?', 3);
	// 		return redirect()->to(base_url() . '/Admin/edit_ward');
	// 	}
		
	// 	$data['selected_ward'] = $data['selected_ward'];
		
	// 	//echo "<pre>";print_r($data['selected_ward']);die;
	// 	$this->dept_args = [
	// 		'is_del' 	=> 0,
	// 		'status' 	=> 'Active', 
	// 	];
	// 	$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $this->dept_args);

	// 	return view('Admin/edit_ward', $data);
	// }
	/* @param: Update department as Admin
	* @desc: Admin can update  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	//public function update_ward($id){
	public function update_ward() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 

		$this->data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'ward_id' => 'required',
				'dept_name' 	=> 'required',
				'dept_id'		=> 'required',
				//'ward_name'     => 'required',
				'ward_name'  => [
					'rules'     => 'required',
					'errors'    => [
					'required' => 'Ward  name is mandatory',
					//'is_unique' => 'Ward name is an already existing. Please add another ward',
					],
				],
			];
		if (!$this->validate($rules)) { 
			$this->data['validation'] = $this->validator; 
			$this->data = $this->data['validation']->getErrors(); //send validation array
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> false, //error: `false` showing validation error autoamtically
				'code'		=> 200,
				'message'	=> 'Sorry!, ward id is missing failed.',
				'data'		=> $this->data,
			);
			return json_encode($this->result_arr);
		}
		if ($this->request->getMethod() !== 'post') { 
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry!, unexpected request method.',
				'data'		=> array(),
			);
			return json_encode($this->result_arr);
		}
		$this->ward_id = $this->request->getVar('ward_id', FILTER_SANITIZE_STRING);
		if(!isset($this->ward_id) || $this->ward_id == '' || $this->ward_id == 0 ) {
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry!, Ward ID is missing or blank.',
				'data'		=> array(),
			);
			return json_encode($this->result_arr);
		}
		$args = [ 'id'  => $this->ward_id ];

		$data = [
			'ward_name'    =>  $this->request->getVar('ward_name', FILTER_SANITIZE_STRING),
			'ward_desc'    =>  $this->request->getVar('ward_desc'),
			'status'       => 'Active',
			'created_at'   =>  date('Y-m-d h:i:s')
		];

		$status = $this->adminModel->update_rec_by_args('hospital_wards', $args, $data);
		if ($status == true) {
			$this->result_arr = array(
				'status'	=> true,
				'error'		=> false, //error: `false` with status `true`
				'code'		=> 200,
				'message'	=> 'Congratulation ! Wards has updated successfully.',
				'data'		=> array(),
			);
			return json_encode($this->result_arr);
			// $this->session->setTempdata('success', 'Congratulation ! Wards has updated successfully.', 3);
			// return redirect()->to(base_url() . 'Admin/manage_ward');
		} 
		else {
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry!, Failed to update ward.',
				'data'		=> array(),
			);
			return json_encode($this->result_arr);
			// $this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
			// return redirect()->to(base_url() . 'Admin/edit_ward/'. $this->ward_id);
			}
		}
	} //function -closed



	// public function update_ward() {
	// 	$this->admin_uid = session()->get('admin_session_uid');
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$data = []; 				//Just for addressing notices
	// 	$data['validation'] = null; //Just for addressing notices
	// 	$this->ward_data_arr = []; //Just for addressing notices
		
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'dept_id'      => 'required',
	// 			'ward_name'    => 'required',
	// 			'ward_id'      => 'required',
	// 			'ward_name'    => 'required',
	// 			'ward_desc'    => 'required',

	// 		];
	// 		if ($this->validate($rules)) {
	// 			$this->bed_data_arr = [
	// 				'ward_desc'       	=>  $this->request->getPost('ward_desc', FILTER_SANITIZE_STRING),
	// 				'dept_id'           =>  $this->request->getPost('dept_id', FILTER_SANITIZE_STRING),
	// 				'ward_id'       =>  $this->request->getPost('ward_id'),
	// 				'ward_name'     =>  $this->request->getPost('ward_name'),
	// 				//'status'        => 'Available',
	// 				'created_at'    =>  date('Y-m-d h:i:s'),
	// 				'created_by'    =>  $this->admin_uid,
	// 			];

	// 			$this->ward_id = $this->request->getPost('ward_id');
	// 			if(!isset($this->ward_id) || $this->ward_id == '' || $this->ward_id == 0){
	// 				if ($status == false) {
	// 					$this->result_arr = array(
	// 						'status'	=> true,
	// 						'code'		=> 200,
	// 						'message'	=> 'Sorry ! Ward id not found. Please try again!',
	// 						'data'		=> $this->ward_data_arr,
	// 					);
	// 					return json_encode($this->result_arr);
	// 				} 
	// 			}
	// 			$args = [ 'id'  => $this->ward_id, ];
	// 			$status = $this->adminModel->update_rec_by_args('hospital_wards', $args, $this->ward_data_arr);
	// 			if ($status == true) {
	// 				$this->result_arr = array(
	// 					'status'	=> true,
	// 					'code'		=> 200,
	// 					'message'	=> 'Congratulation ! Ward updated Successfully !',
	// 					'data'		=> $this->ward_data_arr,
	// 				);
	// 				return json_encode($this->result_arr);
	// 			} 
	// 			else {
	// 				$this->result_arr = array(
	// 					'status'	=> false,
	// 					'code'		=> 200,
	// 					'message'	=> 'Sorry ! Unable to update  Ward. Please try again',
	// 					'data'		=> $this->ward_data_arr,
	// 				);
	// 				return json_encode($this->result_arr);
	// 			}
	// 		} 
	// 		else {
	// 			$this->result_arr = array(
	// 				'status'	=> false,
	// 				'code'		=> 200,
	// 				'message'	=> 'Sorry ! Validation failed. Please try again',
	// 				'data'		=> $this->ward_data_arr,
	// 			);
	// 			return json_encode($this->result_arr);
	// 		}
	// 	}
	// 	else { //return view('Admin/edit_bed', $this->bed_data_arr); //Render form - Non-Ajax call with 'GET' method
	// 		$this->result_arr = array(
	// 			'status'	=> false,
	// 			'code'		=> 200,
	// 			'message'	=> 'Sorry ! Unexpected request method. Please try again',
	// 			'data'		=> $this->ward_data_arr,
	// 		);
	// 		return json_encode($this->result_arr);
	// 	}
	// } //funciton - Closed

	/* @param: change ward status (Active/InActive)  as Admin
	* @desc: Admin can change ward status 
	* @use: Admin...
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function change_ward_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args  = [ 'id' => $id ];
		$data = [ 'status'  => $status ];
		$status = $this->adminModel->update_rec_by_args('hospital_wards',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation !, Record has updated successfully', 3);
		} 
		else { $this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);}
		return redirect()->to(base_url() . '/Admin/manage_ward');
	} // Function - Closed

	/* @param: Delete ward as Admin
	* @desc: Admin can soft Delete  ward
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function delete_ward($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
			'is_del'=> 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];


		$status = $this->adminModel->update_rec_by_args('hospital_wards',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Ward Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_ward');
	} // Functiion - Closed

	/* @params: Function for permanent delete ward
	* @desc: Admin can soft delete department also a soft deleted data can be permanently 
	* delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	
	public function permanent_del_ward($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('hospital_wards',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_ward');
	} // Function - Closed 
	

	/* @param: Upload ward function  as Admin
	* @desc: Upload details of ward
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	
	public function add_bed() {
		$this->admin_uid = session()->get('admin_session_uid');
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$data = [];
		$data['validation'] = null;
		$this->dept_args = [
			'is_del' 	=> 0,
			'status' 	=> 'Active',
		];
		$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $this->dept_args);
		if ($this->request->getMethod() == 'get') {
			return view('Admin/add_bed', $data); //Render form - Non-Ajax call with 'GET' method
		}
		else if ($this->request->getMethod() !== 'post') {
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry ! Unable to add  bed. Please try again',
				'data'		=> $data,
			);
			return json_encode($this->result_arr);
		}
		$this->data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
					'dept_id'        => 'required',
					'dept_name'      => 'required',
					'ward_id'        => 'required',
					'ward_name'      => 'required',
					//'bed_lable'      => 'required',

					// 'bed_lable'      => [
					// 	'rules'      => 'required|min_length[4]|max_length[20]|is_unique[hospital_beds.bed_lable]',
					// 	'errors'     => [
					// 	'required'   => 'Bed lable is mandatory.',
					// 	'min_length' => 'Minimum length is 4.',
					// 	'max_length' => 'Maximum length is 20.',
					// 	'is_unique'  => 'Bed lable is an already existing. Please add another bed lable'
					// 	],
					// ],

					'bed_lable'      => [
						'rules'      => 'required|min_length[4]|max_length[20]',
						'errors'     => [
						'required'   => 'Bed lable is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.',
						],
					],
					//'bed_number'     => 'required',
					'bed_number'  => [
						'rules'     => 'required|min_length[2]|max_length[20]',
						'errors'    => [
						'required' => 'Bed number is mandatory',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.',
						//'is_unique' => 'Bed number is an already existing. Please add another bed number'
						],
					],
			];
			if (!$this->validate($rules)) {
				$this->data['validation'] = $this->validator; 
				$this->data = $this->data['validation']->getErrors(); //send validation array
				$this->result_arr = array(
					'status'	=> false,
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code'		=> 200,
					'message'	=> 'Sorry ! Validation failed. Please try again',
					'data'		=> $this->data,
				);
				return json_encode($this->result_arr);
			}
		}
		$this->bed_lable = $this->request->getPost('bed_lable', FILTER_SANITIZE_STRING);
		$this->user_data_arr = [
			'bed_lable'       	=>  $this->bed_lable,
			'bed_number'        =>  $this->request->getPost('bed_number', FILTER_SANITIZE_STRING),
			'bed_desc'         	=>  $this->request->getPost('bed_desc', FILTER_SANITIZE_STRING),
			'dept_id'           =>  $this->request->getPost('dept_id', FILTER_SANITIZE_STRING),
			'department_name'   =>  $this->request->getPost('dept_name', FILTER_SANITIZE_STRING),
			'ward_id'      		=>  $this->request->getPost('ward_id', FILTER_SANITIZE_STRING),
			'ward_name'     	=>  $this->request->getPost('ward_name', FILTER_SANITIZE_STRING),
			'status'        	=> 'Free',
			'created_at'    	=>  date('Y-m-d h:i:s'),
			'created_by'    	=>  $this->admin_uid,
		];
		///////////////////////
		//is ward exist- START
		$this->dept_id = $this->request->getPost('dept_id', FILTER_SANITIZE_STRING);
		$this->ward_id = $this->request->getPost('ward_id', FILTER_SANITIZE_STRING);
		$this->arg1 = ['dept_id'	=> $this->dept_id];
		$this->arg2 = ['ward_id'	=> $this->ward_id];
		$this->arg3 = ['bed_lable'	=>$this->bed_lable];
		$this->args_arr = [];
		array_push($this->args_arr, $this->arg1);
		array_push($this->args_arr, $this->arg2);
		array_push($this->args_arr, $this->arg3);
		if(!$this->adminModel->is_zero_where_multi_args('hospital_beds', $this->args_arr)) {
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true,
				'code'		=> 200,
				'message'	=> 'Sorry ! Lable is used already for selected dpartment & ward',
				'data'		=> $this->data,
			);
			return json_encode($this->result_arr);
		} //is ward exist- END
		///////////////////
		$status = $this->adminModel->Insertdata('hospital_beds', $this->user_data_arr);
		if ($status == true) {
			$this->result_arr = array(
				'status'	=> true,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Congratulation ! Bed is added successfully !',
				'data'		=> $data,
			);
			return json_encode($this->result_arr);
		} 
		else {
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry ! Unable to add  bed. Please try again',
				'data'		=> $data,
			);
			return json_encode($this->result_arr);
		}
	} //funciton - Closed
	

	/* @param: Manage ward as Admin
	* @desc: Admin can see the list of department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function manage_bed	() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'is_del'  => 0, ];
		$order = [
				'column_name'  => 'bed_lable',
				'order'        => 'asc'
			];
		$data = [
			//'bed' => $this->adminModel->fetch_rec_by_args_arr('hospital_beds', $args),
			'bed' => $this->commonForAllModel->fetch_rec_by_args_arr_dynamic_orderby('hospital_beds', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if(!isset($data['bed']) || $data['bed'] == '') { 
			$this->session->setTempdata('error', 'Sorry ! No Bed found. Please add bed first', 3);
			return redirect()->to(base_url()."Admin/add_bed");
		}
		return view("Admin/manage_bed", $data);
	} // FUnction - Closed

	/* @param: Manage bed as Admin
	* @desc: Admin can see the list of bed
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov, 23
	* @modify
	*/
	public function search_bed() {
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('bed_lable', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('hospital_beds', 'bed_lable', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'bed' => $this->adminModel->fetch_rec_by_args_with_status('hospital_beds', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/manage_bed', $data);
	} // Function - Closed

	/* @param: BED $id
	* @desc: View bed to update bed by Admin
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	//public function edit_bed($id) {
	public function view_bed($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$this->bed_args = [ 'id' 	=> $id,];
		$data['selected_bed'] = $this->adminModel->fetch_rec_by_args('hospital_beds', $this->bed_args);
		if(isset($data['selected_bed']) == false) {
			$this->session->setTempdata('error', 'Sorry ! Bed is not found?', 3);
			return redirect()->to(base_url() . '/Admin/view_bed');
		}
		
		$data['selected_ward'] = $data['selected_bed'];		
		$this->dept_args = [
			'is_del' 	=> 0,
			'status' 	=> 'Active', 
		];
		$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $this->dept_args);

		return view('Admin/view_bed', $data);
	} // Function - Closed

	/* @param: Update/Edit bed by Admin
	* @desc: Admin can update  bed
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function update_bed() {
		$this->admin_uid = session()->get('admin_session_uid');
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = []; 				//Just for addressing notices
		$data['validation'] = null; //Just for addressing notices
		$this->bed_data_arr = []; //Just for addressing notices
		
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'dept_id'      => 'required',
				'dept_name'    => 'required',
				'ward_id'      => 'required',
				'ward_name'    => 'required',
				'bed_id'      => 'required',
				
				'bed_lable'      => [
					'rules'      => 'required|min_length[4]|max_length[20]',
					'errors'     => [
						'required'   => 'Bed lable is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.',
					],
				],
				'bed_number'  => [
					'rules'     => 'required|min_length[2]|max_length[20]',
					'errors'    => [
						'required' => 'Bed number is mandatory',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.',
					],
				],
			];
			if ($this->validate($rules)) {
				$this->bed_data_arr = [
					'bed_lable'       	=>  $this->request->getPost('bed_lable', FILTER_SANITIZE_STRING),
					'bed_number'        =>  $this->request->getPost('bed_number'),
					'bed_desc'         	=>  $this->request->getPost('bed_desc'),
					'dept_id'           =>  $this->request->getPost('dept_id', FILTER_SANITIZE_STRING),
					'department_name'   =>  $this->request->getPost('dept_name', FILTER_SANITIZE_STRING),
					'ward_id'       =>  $this->request->getPost('ward_id'),
					'ward_name'     =>  $this->request->getPost('ward_name'),
					//'status'       => 'Available',
					'updated_at'    =>  date('Y-m-d H:i:s'),
					'updated_by'    =>  $this->admin_uid,
				];

				$this->bed_id = $this->request->getPost('bed_id');
				if(!isset($this->bed_id) || $this->bed_id == '' || $this->bed_id == 0){
					if ($status == false) {
						$this->result_arr = array(
							'status'	=> false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							'code'		=> 200,
							'message'	=> 'Sorry ! Bed id not found. Please try again!',
							'data'		=> $this->bed_data_arr,
						);
						return json_encode($this->result_arr);
					} 
				}
				$args = [ 'id'  => $this->bed_id, ];
				$status = $this->adminModel->update_rec_by_args('hospital_beds', $args, $this->bed_data_arr);
				if ($status == true) {
					$this->result_arr = array(
						'status'	=> true,
						'error'		=> false, //error: `false` with status `true`
						'code'		=> 200,
						'message'	=> 'Congratulation ! Bed updated Successfully !',
						'data'		=> $this->bed_data_arr,
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status'	=> false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'		=> 200,
						'message'	=> 'Sorry ! Unable to update  bed. Please try again',
						'data'		=> $this->bed_data_arr,
					);
					return json_encode($this->result_arr);
				}
			} 
			else { //Validation - Failed
				$this->bed_data_arr['validation'] = $this->validator; 
				$this->bed_data_arr = $this->bed_data_arr['validation']->getErrors(); //send validation array
				$this->result_arr = array(
					'status'	=> false,
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code'		=> 200,
					'message'	=> 'Sorry ! Validation failed. Please try again',
					'data'		=> $this->bed_data_arr,
				);
				return json_encode($this->result_arr);
			}
		}
		else { //return view('Admin/edit_bed', $this->bed_data_arr); //Render form - Non-Ajax call with 'GET' method
			$this->result_arr = array(
				'status'	=> false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'		=> 200,
				'message'	=> 'Sorry ! Unexpected request method. Please try again',
				'data'		=> $this->bed_data_arr,
			);
			return json_encode($this->result_arr);
		}
	} //funciton - Closed


	// public function update_bed($id){
	// 	$args = [ 'id'  => $id ];
	// 	$data = [
	// 		'bed_lable'   =>  $this->request->getVar('bed_lable', FILTER_SANITIZE_STRING),
	// 		'bed_desc'    =>  $this->request->getVar('bed_desc'),
	// 		'status'      => 'Available',
	// 		'created_at'  => date('Y-m-d h:i:s')
	// 	];

	// 	$status = $this->adminModel->update_rec_by_args('hospital_beds', $args, $data);
	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! Bed Details Updated Successfully !', 3);
	// 	} else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
	// 	}
	// 	return redirect()->to(base_url() . 'Admin/manage_bed');
	// }  

	/* @param: change ward status (Active/InActive)  as Admin
	* @desc: Admin can change ward status 
	* @use: Admin...
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function change_bed_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args  = [ 'id' => $id ];
		// $data = [ 'is_free'  => $is_free ];
		$data = [ 'status'  => $status ];
		$status = $this->adminModel->update_rec_by_args('hospital_beds',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation !, Record has updated successfully', 3);
		} 
		else { $this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);}
		return redirect()->to(base_url() . '/Admin/manage_bed');
	} // Functio - Closed


	/* @param: Delete bed as Admin
	* @desc: Admin can soft Delete  bed
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function delete_bed($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
			'is_del'=> 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];


		$status = $this->adminModel->update_rec_by_args('hospital_beds',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! bed Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_bed');
	} // Function - Closed

	/* @params: Function for permanent delete bed
	* @desc: Admin can soft delete bed also a soft deleted data can be permanently 
	* delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	
	public function permanent_del_bed($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('hospital_beds',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_bed');
	} // Function - Closed

	/* @param: Filter bed as Admin
	* @desc: Admin can bed 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function filter_bed($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_bed') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_bed') {
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
			// 'status'        => 'Discharged'
			'is_del' => '0',
		];
		$data = [
			
			'bed' => $this->adminModel->filter_rec_by_args_with_pagination('hospital_beds', $order,  $args),
			'pager'   => $this->adminModel->pager
		];
		return view('Admin/manage_bed', $data);
	}  // Function - Closed


  	/* @param: Upload function  as Admin
	* @desc: Upload details of department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	//public function upload_department(){
	public function add_department() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null; 
		
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'department_name'  => [
					'rules'     => 'required|is_unique[department.department_name]',
					//'rules'     => 'required',
					'errors'    => [
					'required' => 'Department name is mandatory',
					'is_unique' => 'Department name is already existing. Please add another department.'
					],
				],
			];
			
			if ($this->validate($rules)) {
				$this->user_data_arr = [
					'department_name'       =>  $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
					'dep_desc'              =>  $this->request->getVar('dep_desc'),
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				$status = $this->adminModel->Insertdata('department', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Department Added Successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Add  Department  Try Again ?', 3);
				}
				return redirect()->to(base_url() . 'Admin/manage_department');
			} 
			else { 
				$data['validation'] = $this->validator; 
				return view('Admin/department/add_department', $data); //Return validation error
			}
		}
		else if ($this->request->getMethod() == 'get') {
			$data['validation'] = $this->validator;
			return view('Admin/department/add_department', $data); //Render form
		}
		else {
			$this->session->setTempdata('error', 'Unexpected request method', 3);
			return view('Admin/department/add_department', $data); //Render form
		}
	} // Function - Closed


	/* @param: Manage department as Admin
	* @desc: Admin can see the list of department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function manage_department() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'is_del'  => 0, ];
		$order = [
				'column_name'  => 'department_name',
				'order'        => 'asc'
			];
		$data = [
			//'department' => $this->adminModel->fetch_rec_by_args_arr('department', $args),
			'department' => $this->commonForAllModel->fetch_rec_by_args_arr_dynamic_orderby('department', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		//echo "<pre>";print_r($data);die;
		if(!isset($data['department']) || $data['department'] == '') { 
			$this->session->setTempdata('error', 'Sorry ! No department found. Please add department first', 3);
			return redirect()->to(base_url()."Admin/add_department");
		}
		return view("Admin/department/manage_department", $data);
	} // Function - Closed


   /* @param: Edit department as Admin
	* @desc: Admin can edit  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function edit_department($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data['department'] = $this->adminModel->fetch_rec_by_args('department', $args);
		return view('Admin/department/edit_department', $data);
	} // Function - Closed


   /* @param: Update department as Admin
	* @desc: Admin can update  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function update_department($id){
		$args = [ 'id'  => $id ];
		$data = [
			'department_name' =>  $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
			'dep_desc'        =>  $this->request->getVar('dep_desc'),
			'status'          => 'Active',
			'created_at'      =>  date('Y-m-d h:i:s')
		];

		$status = $this->adminModel->update_rec_by_args('department', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Department Details Updated Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_department');
	}   // Function - Closed


   /* @param: Delete department as Admin
	* @desc: Admin can soft Delete  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function delete_department($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
			'is_del'=> 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];


		$status = $this->adminModel->update_rec_by_args('department',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_department');
	} // Function - Closed

    /* @params: Function for permanent delete department
	* @desc: Admin can soft delete department also a soft deleted data can be permanently 
	* delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	
	public function permanent_del_department($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  =>  $id, ];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('department',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_department');
	} // Function - Closed

	/* @param: Search department as Admin
	* @desc: Admin can search  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	
	public function search_department() {
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('department_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('department', 'department_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'department' => $this->adminModel->fetch_rec_by_args_with_status('department', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/department/manage_department', $data);
	} // Function - Closed
	
   /* @param: Filter department as Admin
	* @desc: Admin can filter  department
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function filter_department($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_department') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_department') {
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
			// 'status'        => 'Discharged'
			'is_del' => '0',
		];
		$data = [
				'department' => $this->adminModel->filter_rec_by_args_with_pagination('department', $order,  $args),
				'pager'   => $this->adminModel->pager
			];
			return view('Admin/department/manage_department', $data);
	} // Function - Closed


	/* @param: change department status (Active/InActive)  as Admin
	* @desc: Admin can change department status 
	* @use: Admin...
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function change_department_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args  = [ 'id' => $id ];
		$data = [ 'status'  => $status ];
		$status = $this->adminModel->update_rec_by_args('department',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation !, Record has updated successfully', 3);
		} 
		else { $this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);}
		return redirect()->to(base_url() . '/Admin/manage_department');
	} // Function - Closed



	/* @params: Add doctors by Admin (Not in use for now)
	 * @desc: Function for adding doctor but we are using add_doctor
	 * @use: Admin...
	 * @return:
	 * @author: Neoarks Team
	 * @date: 13th July,2023
	 * @modify
	 */
	public function add_doctor() {
		if (!$this->session->has('admin_session_uid')) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [
				'status' => 'Active',
				'is_del' => 0
			];
			$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $args);
			if (empty($data['departments'])) { // Is dpeartment exists - Check
				$this->session->setFlashdata('error', 'No departments exist. Please add a department first.');
				return redirect()->to(base_url() . "/Admin/add_department");
			}
			return view('Admin/add_doctor', $data);
		}
	} //function - closed
	

	
	
   /* @params: Function for save doctor
	* @desc: Function for save doctor as an admin
	* @use: Admin...
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function save_doctor() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'department_name'  => [
                     'rules'       => 'required',
                     'errors'      => [
                     'required'    => 'Department name is mandatory.'
                     ],
                 ],

				 'doctor_name'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required'  => 'Doctor name is mandatory.',
					'min_length'=> 'Minimum length is 4.',
					'max_length'=> 'Maximum length is 20.'
					],
				],
				'doctor_phone'  => [
                     'rules'    => 'required|numeric|exact_length[10]',
                     'errors'   => [
                     'required' => 'Doctor phone is mandatory.'
                     ],
                 ],

				 'doctor_address' => [
					'rules'       => 'required|min_length[4]|max_length[100]',
					'errors'      => [
					'required'    => 'Doctor address is mandatory.',
					'min_length'  => 'Minimum length is 4.',
					'max_length'  => 'Maximum length is 100.'
					],
				],

				'doctor_email'   => [
                     'rules'     => 'required|valid_email|is_unique[doctor.doctor_email]',
                     'errors'    => [
                     'required'  => 'Doctor email is mandatory.'
                     ],
                 ],

				'dr_specialization'=> [
                     'rules'      => 'required',
                     'errors'     => [
                     'required'   => 'Doctor speciality is mandatory.'
                     ],
                 ],

				'gender'  => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor gender is mandatory.'
                     ],
                 ],

                 'education'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor education is mandatory.'
                     ],
                 ],

				'doctor_fee'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor fee is mandatory.'
                     ],
                 ],
				'profile_pic'   => [
					'rules'      => 'uploaded[profile_pic]|max_size[profile_pic,' . ALLOW_MAX_UPLOAD .']|is_image[profile_pic]|mime_in[profile_pic,image/jpeg,image/png, image/svg]|ext_in[profile_pic,png,jpg,jpeg, svg]',
					'errors'       => [
						'uploaded'   => 'Doctor Image is mandatory.',
						'max_size'   => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'    => 'The uploaded file must be a valid image format.',
						'ext_in'     => 'The file must have a valid extension (png, jpg, jpeg).'
					],
				],
			];
			// added new one to show their previous selected value and all values from datatbase
			$args = [
				'status' => 'Active',
				'is_del' => 0
			];
			$data['departments'] = $this->adminModel->fetch_rec_by_args('department', $args);
			
			if ($this->validate($rules)) {
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
				$img = $this->request->getFile('profile_pic');
				if ($img->isValid() &&  !$img->hasMoved()) {
					$this->rand_name = $img->getRandomName();
					$img->move(FCPATH . 'uploads/doctor', $this->rand_name);
					$doc_img = $img->getName();

					$doctor_tbl_dt = [
						'department_name'   =>  $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
						'doctor_name'       =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
						'doctor_fee'      	=>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
						'education'      	=>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
						'gender'      		=>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
						'doctor_email'      =>  $this->request->getVar('doctor_email'),
						'doctor_address'    =>  $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
						'dr_specialization'  =>  $this->request->getVar('dr_specialization', FILTER_SANITIZE_STRING),
						'doctor_other_info' =>  $this->request->getVar('doctor_other_info', FILTER_SANITIZE_STRING),
						'doctor_phone'      =>  $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
						'uid'               =>  $uid,
						'profile_pic'      =>   $this->rand_name, //$doc_img,
						'status'            => 'Active',
						'created_at'        =>  date('Y-m-d h:i:s')
					];
					$status = $this->adminModel->Insertdata('doctor', $doctor_tbl_dt);
					//$status = $this->adminModel->Insertdata('register_all_users', $doctor_tbl_dt);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! Doctor Added Successfully !', 3);
					} else {
						$this->session->setTempdata('error', 'Sorry ! Unable to Add  Doctor  Try Again ?', 3);
					}
					return redirect()->to(base_url() . 'Admin/manage_doctor');
				} 
				else {
					echo $img->getErrorString() . " " . $img->getError();
				}
			} else {
				$data['validation'] = $this->validator;
				return view('Admin/add_doctor', $data);
			}
		} else {
			$this->session->setTempdata('error', 'Request method is not supported', 3);
		}
		
		//echo "<pre>";print_r($doctor_tbl_dt);die;
		return view('Admin/add_doctor', $data);
	} //function - Closed


	/* @params: Function for manage doctor
	* @desc: Basically Admin can see the list of doctors 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function manage_doctor(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [ 'is_del'  => 0, ];
			
			$data = [
				'doctors' => $this->adminModel->fetch_rec_by_args_arr('doctor', $args),
				'pager'     => $this->adminModel->pager
			];
			//echo "<pre>";print_r($data['doctors']);die;
			return view("Admin/manage_doctor", $data);
		}
	} // Function - Closed

	/* @params: Function for edit doctor
	* @desc: Admin can see the edit the doctors 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function edit_doctor($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [ 'id'  => $id];
			$data['update_doctor'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
			$args = [ 'status' => 'Active'];
			$data['department'] = $this->adminModel->fetch_rec_by_args('department', $args);
			//echo "<pre>";print_r($data);die;
			return view('Admin/edit_doctor', $data);
		}
	} //function - Closed


   /* @params: Function for Edit/Update doctor details
	* @desc: Admin can update doctors 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	/* 
	public function update_doctor($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');		
		$this->dr_email = $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING);
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'department_name'  => [
                     'rules'       => 'required',
                     'errors'      => [
                     'required'    => 'Department name is mandatory.',
                     ],
                 ],

				'doctor_name'     => [
                     'rules'      => 'required|min_length[6]|max_length[20]',
                     'errors'     => [
                     'required'   => 'Doctor name is mandatory.',
					 'min_length' => 'Doctor name is allowed minimum 6 charectors.',
					 'max_length' => 'Doctor name is allowed maximum 20 charectors.'
                     ],
                 ],

				'doctor_phone'   => [
					'rules'      => 'required|numeric|exact_length[10]',
					'errors'     => [
					'required'   => 'Doctor mobile is mandatory.',
					'numeric'    => 'Doctor mobile is allowed number only.',
					 'exact_length' => 'Doctor mobile must exact 10 digit number.'
                     ],
                 ],

				'doctor_address'  => [
                     'rules'      => 'required|min_length[4]|max_length[100]',
                     'errors'     => [
                     'required'   => 'Doctor address is mandatory.',
					 'min_length' => 'Doctor address is allowed minimum 4 charectors.',
					 'max_length' => 'Doctor address is allowed maximum 100 charectors.'
                     ],
                 ],

				'dr_specialization'=> [
                     'rules'      => 'required',
                     'errors'     => [
                     'required'   => 'Doctor speciality is mandatory.'
                     ],
                 ],

				'gender'  => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor gender is mandatory.'
                     ],
                 ],

                 'education'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor education is mandatory.'
                     ],
                 ],

				'doctor_fee'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor fee is mandatory.'
                     ],
                 ],

			];
			$args = [ 'id'   => $id ];
			$update_doctor = $this->adminModel->fetch_rec_by_args('doctor', $args);
	
			$this->args1 = ['doctor_email'=>$this->dr_email];
			$this->dr_data_ar = $this->adminModel->fetch_rec_by_args('doctor', $this->args1);
		
			if(isset($this->dr_data_ar[0]->id)){
				if($this->dr_data_ar[0]->id !== $id){
					$this->session->setTempdata('error', 'Entered email is used by another user.  Please try with another email', 3);
					return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
				}
			}
			if ($this->validate($rules)) {
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
				$img = $this->request->getFile('profile_pic');
				$doc_img = $img->getName();
				$this->rand_name = $img->getRandomName();
				if($doc_img && $doc_img != '') {
					if(isset($update_doctor[0]->profile_pic)) {
						if(file_exists(FCPATH . 'uploads/doctor/' . $update_doctor[0]->profile_pic) && $update_doctor[0]->profile_pic != '') {
							//delete old  image
							@unlink(FCPATH . 'uploads/doctor/' . $update_doctor[0]->profile_pic);
						} //else - Not needed
					} //else - Not needed
					if ($img->isValid() &&  !$img->hasMoved()) {
						$img->move(FCPATH . 'uploads/doctor/', $this->rand_name);
						$data = [
							'department_name'       => $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
							'doctor_name'           => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
							'doctor_fee'         	=>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
							'education'      	    =>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
							'gender'      		=>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
							'doctor_phone'          => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
							'doctor_address'        => $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
							'doctor_email'          => $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING),
							'dr_specialization'      => $this->request->getVar('dr_specialization', FILTER_SANITIZE_STRING),
							'doctor_other_info'     => $this->request->getVar('doctor_other_info', FILTER_SANITIZE_STRING),
							'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
							'profile_pic'       	=> $this->rand_name,
							'updated_at'            => date('Y-m-d h:i:s'),
							'updated_by'            => $this->admin_uid,
						];
					}
					else { //file is not moved
						$this->session->setTempdata('error', 'Sorry ! Failed to move uploaded media.', 3);
						echo $img->getErrorString() . " " . $img->getError(); 
						return redirect()->to(base_url() . 'Admin/edit_doctor/' . $id);
					} 
				}
				else {
					$data = [
						'department_name'       => $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
						'doctor_name'           => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
						'doctor_fee'         	=>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
						'education'      	    =>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
						'gender'      		=>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
						'doctor_phone'          => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
						'doctor_address'        => $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
						'doctor_email'          => $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING),
						'dr_specialization'      => $this->request->getVar('dr_specialization', FILTER_SANITIZE_STRING),
						'doctor_other_info'     => $this->request->getVar('doctor_other_info', FILTER_SANITIZE_STRING),
						'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
						//'profile_pic'       	=> $this->rand_name,
						'updated_at'            => date('Y-m-d h:i:s'),
						'updated_by'            => $this->admin_uid,
					];
				}
				

				if(!isset($this->dr_email) || $this->dr_email == '') {
					$this->session->setTempdata('error', 'Email is madatory. Please enter email.', 3);
					return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
				}
				$status = $this->adminModel->update_rec_by_args('doctor', $args, $data);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Doctor details updated successfully !', 3);
				} 
				else {
					$this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3);
				}
			}	 
			else {
				$data['validation'] = $this->validator;
				$args = [ 'id'  => $id, ];
				$data['update_doctor'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
				$args = [ 'status' => 'Active' ];
				$data['department'] = $this->adminModel->fetch_rec_by_args('department', $args); 
				return view('Admin/edit_doctor', $data);
			}
		} 
		else { $this->session->setTempdata('error', 'Request method is not supported', 3); } 
		return redirect()->to(base_url() . 'Admin/manage_doctor/');
	} //function - Closed
	*/

	public function update_doctor($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');		
		$this->dr_email = $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING);
		$this->data_arr1 = []; 		//For addressing notices
		$data['validation'] = null;	//For addressing notices
		$this->data_arr1['profile_pic'] = ''; //For addressing notices

		if ($this->request->getMethod() == 'post') {
			$rules = [
				'department_name'  => [
                     'rules'       => 'required',
                     'errors'      => [
                     'required'    => 'Department name is mandatory.',
                     ],
                 ],

				'doctor_name'     => [
                     'rules'      => 'required|min_length[4]|max_length[20]',
                     'errors'     => [
                     'required'   => 'Doctor name is mandatory.',
					 'min_length' => 'Doctor name is allowed minimum 6 charectors.',
					 'max_length' => 'Doctor name is allowed maximum 20 charectors.'
                     ],
                 ],

				'doctor_phone'   => [
					'rules'      => 'required|numeric|exact_length[10]',
					'errors'     => [
					'required'   => 'Doctor mobile is mandatory.',
					'numeric'    => 'Doctor mobile is allowed number only.',
					 'exact_length' => 'Doctor mobile must exact 10 digit number.'
                     ],
                 ],

				'doctor_address'  => [
                     'rules'      => 'required|min_length[4]|max_length[100]',
                     'errors'     => [
                     'required'   => 'Doctor address is mandatory.',
					 'min_length' => 'Doctor address is allowed minimum 4 charectors.',
					 'max_length' => 'Doctor address is allowed maximum 100 charectors.'
                     ],
                 ],

				'dr_specialization'=> [
                     'rules'      => 'required',
                     'errors'     => [
                     'required'   => 'Doctor speciality is mandatory.'
                     ],
                 ],

				'gender'  => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor gender is mandatory.'
                     ],
                 ],

                 'education'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor education is mandatory.'
                     ],
                 ],

				'doctor_fee'     => [
                     'rules'     => 'required',
                     'errors'    => [
                     'required'  => 'Doctor fee is mandatory.'
                     ],
                 ],

			];
			$args = [ 'id'   => $id ];
			$update_doctor = $this->adminModel->fetch_rec_by_args('doctor', $args);
	
			$this->args1 = ['doctor_email' => $this->dr_email];
			$this->dr_data_ar = $this->adminModel->fetch_rec_by_args('doctor', $this->args1);
		
			if(isset($this->dr_data_ar[0]->id)){
				if($this->dr_data_ar[0]->id !== $id){
					$this->session->setTempdata('error', 'Entered email is used by another user.  Please try with another email', 3);
					return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
				}
			}
			if ($this->validate($rules)) {
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
				$this->data_arr1 = [ //Table1 data array
					'department_name' => $this->request->getVar('department_name', FILTER_SANITIZE_STRING),
					'doctor_name'     => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
					'doctor_fee'      =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
					'education'       =>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
					'gender'      	  =>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
					'doctor_phone'    => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
					'doctor_address'  => $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
					'doctor_email'    => $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING),
					'dr_specialization'=> $this->request->getVar('dr_specialization', FILTER_SANITIZE_STRING),
					'doctor_other_info'=> $this->request->getVar('doctor_other_info', FILTER_SANITIZE_STRING),
					'doctor_fee'       =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
					'updated_at'       => date('Y-m-d h:i:s'),
					'updated_by'       => $this->admin_uid,
				];
				$img = $this->request->getFile('profile_pic');
				$doc_img = $img->getName();
				$this->rand_name = $img->getRandomName();
				if($doc_img && $doc_img != '') {
					if(isset($update_doctor[0]->profile_pic)) {
						if(file_exists(FCPATH . 'uploads/doctor/' . $update_doctor[0]->profile_pic) && $update_doctor[0]->profile_pic != '') {
							//delete old  image
							@unlink(FCPATH . 'uploads/doctor/' . $update_doctor[0]->profile_pic);
						} //else - Not needed
					} //else - Not needed
					if ($img->isValid() &&  !$img->hasMoved()) {
						$img->move(FCPATH . 'uploads/doctor/', $this->rand_name);
						$this->data_arr1['profile_pic'] = $this->rand_name; //Added profile pic into data array
					}
					else { //file is not moved
						$this->session->setTempdata('error', 'Sorry ! Failed to move uploaded media.', 3);
						echo $img->getErrorString() . " " . $img->getError(); 
						return redirect()->to(base_url() . 'Admin/edit_doctor/' . $id);
					} 
				}
				
				if(!isset($this->dr_email) || $this->dr_email == '') {
					$this->session->setTempdata('error', 'Email is madatory. Please enter email.', 3);
					return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
				}
				//Table2 data array
				$this->data_arr2 = [
					'doctor_name'     => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
					'email'    		=> $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING),
					'gender'      	  =>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
					'address'  => $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
					'doctor_fee'       =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
					'education'       =>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
					'country_code'	=> $this->request->getVar('country_code', FILTER_SANITIZE_STRING),
					'mobile'    => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
					'updated_at'    => date('Y-m-d h:i:s'),
					'updated_by'      => $this->admin_uid,
				];

				$this->data_arr2['profile_pic'] = $this->rand_name; //Added profile pic into data array

				$status = $this->commonForAllModel->update_into_two_tables('doctor',  $args, $this->data_arr1, 'register_all_users',  $args, $this->data_arr2);

				//$status = $this->adminModel->update_rec_by_args('doctor', $args, $data);

				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Doctor details updated successfully !', 3);
				} 
				else {
					$this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3);
				}
			}	 
			else {
				$data['validation'] = $this->validator;
				$args = [ 'id'  => $id, ];
				$data['update_doctor'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
				$args = [ 'status' => 'Active' ];
				$data['department'] = $this->adminModel->fetch_rec_by_args('department', $args); 
				return view('Admin/edit_doctor', $data);
			}
		} 
		else { $this->session->setTempdata('error', 'Request method is not supported', 3); } 
		return redirect()->to(base_url() . 'Admin/manage_doctor/');
	} //function - Closed
	

	/* @params: Function for delete doctor
	* @desc: Admin can soft delete doctors 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function delete_doctor($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
			$args1 = ['id'  =>  $id];
			$args2 = [
				'ref_id'  =>  $id
		];
			$data = [
				'status' => 'Deleted', //0: Non deleted, 1: Deleted
				'updated_at' => date('Y-m-d h:i:s'),
				'updated_by' => $this->admin_uid
			];
			//$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);
			$status = $this->commonForAllModel->update_into_two_tables('doctor',  $args1, $data, 'register_all_users',  $args2, $data);
			if ($status) {
				$this->session->setTempdata('success', 'Congratulation ! Doctor deleted successfully !', 3);
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to delete records !', 3);
			}
			return redirect()->to(base_url() . '/Admin/manage_doctor');
	} // Function - Closed
	
	/* @params: Function for permanent delete doctor
	* @desc: Admin can soft delete doctors also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_doctor($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args1 = ['id'  =>  $id,
		    //    'is_del' =>  '1'      
		];
		$args2 = ['ref_id'  =>  $id];
		$data = [
			'is_del' =>  '1' ,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-m-d h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->commonForAllModel->update_into_two_tables('doctor',  $args1, $data, 'register_all_users',  $args2, $data);
		if ($status === true) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor deleted successfully !', 3);
		} else {
			$this->session->setTempdata('success', 'Congratulation ! Doctor deleted successfully !', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor');
	} // Function - closed

	/* @params: Function for change doctor status
	* @desc: Admin can change doctor status (Activ/InActive)
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function change_doctor_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ,
				// 'level'  => '3',
				// 'is_del' => 0	
							];
		$data = [ 'status' => $status ];

		//$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);

		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor Status Updated  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor');
	} // Function - Closed

	
	/* @params:
	* @desc:  Function for filter doctor 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function filter_doctor($filter) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_doctor') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_doctor') {
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
			'is_del' => 0
			
			// 'status'        => 'Discharged'
		];
		$data = [
			// 'doctors' => $this->adminModel->filter_rec_by_paginate('doctor', $order),
			// 'pager'   => $this->adminModel->pager
			'doctors' => $this->adminModel->filter_rec_by_args_with_pagination('doctor', $order, $args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/manage_doctor', $data);
	} // FUnction - Closed

	/* @params:
	* @desc:  Function for filter doctor 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function search_doctor(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
		
		$keyword = $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('doctor', 'doctor_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'doctors' => $this->adminModel->fetch_rec_by_args_with_status('doctor', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/manage_doctor', $data);
	} // Function - Closed
	
	/* @params:
	* @desc:  Function for add doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function add_doctor_fee() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$this->args = ['is_del'		=> 0, 
		                   // 'id' => $id
						 ];
			$data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $this->args);
			return view('Admin/add_doctor_fee', $data);
		}
	} // Function - Closed

	/* @params:
	* @desc:  Function for upload doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function upload_doctor_fee(){ //No longerrequired function - Replaced with update_doctor_fee()
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'doctor_name'      => 'required',
				'doctor_fee'      => 'required',
			];
			if ($this->validate($rules)) {
				$this->user_data_arr = [
					'doctor_name'          =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
					'doctor_fee'           =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
					'status'               => 'Active',
					'created_at'           =>  date('Y-m-d h:i:s')
				];
				// $status = $this->adminModel->Insertdata('doctor_fee', $this->user_data_arr);
				$status = $this->adminModel->Insertdata('doctor', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Doctor Fee Added Successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Add  Doctor Fee Try Again ?', 3);
				}
				//return redirect()->to(base_url().'/Admin/add_doctor_fee'); //Same page redirection
				return redirect()->to(base_url() . 'Admin/manage_doctor_fee');
			} else {
				$data['validation'] = $this->validator;
				return view('Admin/add_doctor_fee', $data);
			}
		}
		$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		return view('Admin/add_doctor_fee', $data);
	} // Function - Closed

	/* @params:
	* @desc:  Function for manage doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function manage_doctor_fee(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'is_del'  => 0,

			];
			$data = [
				'doctor_fee' => $this->adminModel->fetch_rec_by_args_arr('doctor', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view("Admin/manage_doctor_fee", $data);
		}
	} // Function - closed	

	/* @params:
	* @desc:  Function for edit doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function edit_doctor_fee($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		//$data['update_doctor_fee'] = $this->adminModel->fetch_rec_by_args('doctor_fee', $args);
		$data['update_doctor_fee'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
		$args = [
			'status'  => 'Active'
		];
		$data['doctors'] = $this->adminModel->fetch_rec_by_args('doctor', $args);
		return view('Admin/edit_doctor_fee', $data);
	} // Function - Closed

	/* @params: $id
	* @desc:  Function for Update doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function update_doctor_fee($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$args = ['id'   => $id];
		$data = [
			'doctor_fee'          => $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING)
		];

		$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor fee details updated successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to update fee, please try again.', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_doctor_fee');
	} //function - closed


	/* @params: $id
	* @desc:  Function for delete doctor fee
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 13th July,2023
	* @modify
	*/
	public function delete_doctor_fee($id){
		$this->patient_id = $id;
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		// $status = $this->adminModel->update_rec_by_args('doctor_fee',  $args, $data);
		$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
			//return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
			return redirect()->to(base_url() . 'Admin/manage_doctor_fee');
		} else {
			session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
			return redirect()->to(base_url() . 'Admin/manage_today_discharged_patient');
		}
	} // Function - Closed

	/* @params: Function for permanent delete doctor fee
	* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_doctor_fee($id){
		$this->patient_id = $id;
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'   => $id,
			// 'is_del' => 1
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		// $status = $this->adminModel->update_rec_by_args('doctor_fee',  $args, $data);
		$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Doctor Fee has deleted successfully', 2);
			//return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
			return redirect()->to(base_url() . 'Admin/manage_doctor_fee');
		} else {
			session()->setTempdata('error', 'Oops ! Doctor Fee is not deleted ', 2);
			return redirect()->to(base_url() . 'Admin/manage_today_discharged_patient');
		}
	} // Function- Closed

	/* @params: Function for Change doctor fee status
	* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function change_doctor_fee_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status' => $status
		];
		//$status = $this->adminModel->update_rec_by_args('doctor_fee',  $args, $data);
		$status = $this->adminModel->update_rec_by_args('doctor',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor Status Updated  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor_fee');
	}  // Function - Closed

	/* @params: 
	* @desc: Function for  search doctor fee
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function search_doctor_fee(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('doctor', 'doctor_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'doctor_fee' => $this->adminModel->fetch_rec_by_args_with_status('doctor', $args),
			'pager' => $this->adminModel->pager
		];
	
		return view('Admin/manage_doctor_fee', $data);
	} // Function - Closed
	
	/* @params: 
	* @desc: Function for  filter doctor fee
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function filter_doctor_fee($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_doctor') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_doctor') {
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
			//    'doctor_fee' => $this->adminModel->filter_rec_by_paginate('doctor_fee', $order),
			'doctor_fee' => $this->adminModel->filter_rec_by_args_with_pagination('doctor', $order, $args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/manage_doctor_fee', $data);
	} // Funtion - Closed

   /* @params: 
	* @desc: Function for  fetch patients details
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	//public function add_patients() {
	public function fetch_patients() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [ 'login_acc'	=> 1];
			$data = [
				'doctors' => $this->adminModel->fetch_rec_by_args('doctor', $args),
			];
			return view('Admin/add_patients', $data);
		}
	} //Function - Closed

	/* @params: 
	* @desc: Function for  get doctor  fee details
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function get_doctor_fee_details($id){
		if (!(session()->has('admin_session_uid'))) {
			// Return a JSON response with an error status and message
			$response = [
				'status' => 'error',
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code'	=> 200,
				'message' => 'Session expired or not authenticated'
			];
			return json_encode($response);
		}
	
		$args = [
			'id'  => $id
		];
	
		// $data = $this->adminModel->fetch_rec_by_args('doctor', $args);
		$data = $this->adminModel->fetch_rec_by_args('doctor', $args);
	
		if ($data === false) {
			// Return a JSON response with an error status and message
			$response = [
				'status' => 'error',
				'code'		=> 200,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'message' => 'Record not found'
			];
			return json_encode($response);
		} else if (is_array($data)) {
			// Return the data as a JSON response
			return json_encode($data);
		} else {
			// Return a JSON response with an error status and message
			$response = [
				'status' => 'error',
				'message' => 'Unexpected result set'
			];
			return json_encode($response);
		}
	} // Function - Closed
	

	/* @params: 
	* @desc: Function for  get highest today serial 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function get_highest_today_serial($tbl, $fld){
		$this->tbl_name = $tbl;
		$this->where_field = $fld;
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		//$args = [ 'registration_date'  => date('Y-m-d') ];
		$args = [$this->where_field  => date('Y-m-d')];

		$this->highest_pat_serial = $this->adminModel->today_highest_serial($this->tbl_name, $args);
		return $this->highest_pat_serial;
	} // Function - Closed

	/* @params: 
	* @desc: Function for  generate puid
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function generate_puid($new_sn) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->new_serial = $new_sn; //Serial Number
		$this->current_date = date('Y-m-d');

		$this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
		$this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
		return $this->puid;
	} // FUnction - Closed

	/* @params: 
	* @desc: Function for  generate serial
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function generate_serial($tbl, $fld) {
		$this->table = $tbl;
		$this->where_field = $fld;
		$this->new_serial = 0; //Just for addressing notices
		$this->highest_serial = $this->get_highest_today_serial($this->table, $this->where_field); //returns null if not record found

		if (!isset($this->highest_serial->serial) || $this->highest_serial == null) {
			if (isset($this->highest_serial->patient_id)) {
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => $this->highest_serial->patient_id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			} else if (isset($this->highest_serial->id)) {
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => $this->highest_serial->id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			} else if (!isset($this->highest_serial->patient_id) || $this->highest_serial->patient_id == null) {
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			} else if (!isset($this->highest_serial->id) || $this->highest_serial->id == null) {
				$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
			} else {
				$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			}
		} else if (isset($this->highest_serial->serial)) {
			if (isset($this->highest_serial->patient_id)) {
				$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->patient_id,
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			} else if (isset($this->highest_serial->id)) {
				$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->id,
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			} else {
				$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			}
		} else {
			//return false;
			$this->session->setTempdata('error', 'Unexpected serial number ', 3);
			return redirect()->to(base_url() . '/Admin/all_appointments');
		}
	} //Function - Closed

	
	/* @params: 
	* @desc: Function for  upload patients
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function upload_patients(){
		if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			} else {
				$this->admin_uid = session()->get('admin_session_uid');
				$data = [];
				$data['validation'] = null;
				$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');

				if ($this->request->getMethod() == 'post') {
					$rules = [
						// ... (existing validation rules)
						'patient_address'=> [
							'rules'      => 'required',
							'errors'     => [
							'required'   => 'Patient address is mandatory.'
							],
						],

						'patient_name'  => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Patient name is mandatory.'
							],
						],

						'patient_phone'  => [
							'rules'      => 'required',
							'errors'     => [
							'required'   => 'Patient phone is mandatory.'
							],
						],

						'patient_age'   => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Patient age is mandatory.'
							],
						],

						'patient_gender'=> [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Patient gender is mandatory.'
							],
						],

						'patient_issue' => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Patient issue is mandatory.'
							],
						],

						'ward_name'     => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Ward name is mandatory.'
							],
						],

						'patient_room'  => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Room number is mandatory.'
							],
						],

						'patient_address'=> [
							'rules'      => 'required',
							'errors'     => [
							'required'   => 'Patient address is mandatory.'
							],
						],

						'patient_zip'   => [
							'rules'     => 'required',
							'errors'    => [
							'required'  => 'Patient pincode is mandatory.'
							],
						],
						
						'patient_image' => [
							'rules' => 'uploaded[patient_image]|max_size[patient_image,' . ALLOW_MAX_UPLOAD . ']|is_image[patient_image]|mime_in[patient_image,image/jpeg,image/png,image/svg,image/gif]|ext_in[patient_image,png,jpg,jpeg,svg,gif]',
							'errors' => [
								'uploaded' => 'Patient Image is mandatory.',
								'max_size' => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only.',
								'mime_in'  => 'The uploaded file must be a valid image or SVG, GIF.',
								'ext_in'   => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
							],
						],
						
						
					];
					
					if ($this->validate($rules)) { 
						$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
						$img = $this->request->getFile('patient_image');

						//doctor_fee:
						//ward_name: General ward
						//patient_zip: 202224
						//entry_fee: 100
						//other_fee: 100

						$this->appmt_data_arr = [
							'patient_name'		=>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
							//'serial'			=>	$this->new_serial,
							//'pid'				=>  $this->patient_session_id, //patient auto increment ID
							'patient_email'     =>  $this->request->getVar('patient_email'),
							'country_code'    =>  $this->request->getVar('country_code', FILTER_SANITIZE_STRING),
							'patient_mobile'    =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
							'age'    			=>  $this->request->getVar('patient_age', FILTER_SANITIZE_STRING),
							'gender'    		=>  $this->request->getVar('patient_gender', FILTER_SANITIZE_STRING),
							'patient_room'    		=>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
							'puid'    			=> 0,// $this->puid, //Patient's ID
							'booking_date'      =>  date('Y-m-d'),
							'booking_time'      =>  date('h:i:s'),
							'disease_symptoms'  =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
							'description'       =>  $this->request->getVar('other_info', FILTER_SANITIZE_STRING),
							'address'       	=>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),

							'doctor_id'         =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING), //1st july, 2023
							'doctor_name'       =>  $this->request->getVar('doc_name', FILTER_SANITIZE_STRING),
							//'paid_doctor_fee'       =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
							//'status'            => 4, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
							'status'            => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
							'created_at'        =>  date('Y-m-d h:i:s'),
							'created_by'		=>	$this->admin_uid
						];


						if ($img && $img->isValid() && !$img->hasMoved()) {
							$this->rand_name = $img->getRandomName();
							$img->move(FCPATH . 'uploads/patients', $this->rand_name);
							$doc_img = $img->getName();

							// ... (existing code)

							$status = $this->adminModel->Insertdata('booked_doctor_appointment', $this->appmt_data_arr);
							if ($status === true) {
								$this->session->setTempdata('success', 'Congratulation! Patients Added Successfully!', 3);
							} else {
								$this->session->setTempdata('error', 'Sorry! Unable to Add Patients. Try Again?', 3);
							}
							return redirect()->to(base_url() . 'Admin/all_appointments');
							} else {
								// File upload error
								$this->session->setTempdata('error', $img->getErrorString(), 3);
								return redirect()->to(base_url() . '/Admin/fetch_patients');
							}
						} else {
							$data['validation'] = $this->validator;
							return view('Admin/add_patients', $data);
						}
					} else {
						$this->session->setTempdata('error', 'Sorry! Required POST method.', 3);
						return redirect()->to(base_url() . '/Admin/fetch_patients');
					}
			//$data['doctors'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor'); //written above
			return view('Admin/add_patients', $data);
		}
	} // Function - Closed



	/* @params: 
	* @desc: Function for  manage patients
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function manage_patients(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [ 'is_del'	=> 0 ];
			$data = [
				'patients' => $this->adminModel->fetch_rec_by_args_with_status('patients', $args),
				'pager' => $this->adminModel->pager
			];
			return view('Admin/manage_patients', $data);
		}
	} //Function - Closed

	/* @params: 
	* @desc: Function for  manage all discharge patients
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function manage_all_discharged_patient(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = ['status'  => 'Discharged',
					'is_del'  => 0];
			$data = [
				'patients' => $this->adminModel->fetch_rec_by_args_arr('patients', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view("Admin/manage_doc_dis_patient", $data);
		}
	} //function - Closed


	/* @params: 
	 * @desc: manage Patient discharged by admin for today date
	 * @use: Admin...
	 * @author: Neoarks Team
	 * @date: 23th July, 2023
	 * @modify:
	 */
	public function manage_today_discharged_patient() {
    	if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		else {
		$args = [
				'status'       	=> 'Discharged',
				//'registration_date'  => date('Y-m-d h:i:s')
			];
			$likeArgs = ['updated_at' => date('Y-m-d')];
			$data = [
				'patients' => $this->adminModel->fetch_rec_by_args_like('patients', $args, $likeArgs),
				'pager'         => $this->adminModel->pager
			];
			if(!isset($data['patients']) || $data['patients'] == '') { 
				$this->session->setTempdata('error', 'Sorry ! No discharged patient found. Please see patients here', 3);
				return redirect()->to(base_url()."/Admin/manage_disc_patient");
			}
			return view("Admin/manage_disc_patient",$data);
		}
    } // Function - Closed
	
	/* @params: Function for filter dischrage patients
	 * @desc: Function for filter dischrage patients
	 * @use
	 * @author: Neoarks Team
	 * @date: 
	 * @modify:
	 */

	public function filter_dischrage_pat($filter){ //Under development -- it was blank function
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		session()->setTempdata('error', 'Sorry.! Under development.. coming soon', 2);
		return redirect()->to(base_url() . 'Admin/filter_dischrage_pat');
	} // Function - Closed

	/* @params: Function for filter dischrage patients
	 * @desc: Function for filter dischrage patients
	 * @use
	 * @author: Neoarks Team
	 * @date: 
	 * @modify:
	 */
	public function search_doc_dis_patient(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'status' => 'Discharged',
			'is_del' => 0
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/manage_doc_dis_patient', $data);
	}
	

	public function search_discharge_patient(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
		'is_del' => 0,
			'status' => 'Discharged',
		// 'registration_date' => date('Y-m-d')
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   

		$data = [
			'patients' => $this->adminModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/manage_disc_patient', $data);
	}

	public function add_medicine_stock($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}else{
			$args = [
				'id'  => $id
				//'expiry_date>=' => date('Y-m-d ')
			];
			// $data['medicines'] = $this->adminModel->fetch_rec_by_args('medicines', $args);
			$data['medicines'] = $this->adminModel->fetch_rec_by_args('medicines', $args);
			return view('Admin/medicines/add_medicine_stock', $data);
		}
	}

	public function filter_dischrage_patient($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."Login");
		}
		if ($filter == 'new_patients') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}else if ($filter == 'old_patients') {
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
		//echo "<pre>";print_r($order);die;
		$args = [
			'status' => 'Discharged',
			'is_del' => 0
			];
			$likeArgs = ['updated_at' => date('Y-m-d')];
			$data = [
			'patients' => $this->adminModel->fetch_rec_by_args_filter_order_like('patients', $args, $likeArgs,$order),
			'pager' => $this->adminModel->pager
			];
			return view("Admin/manage_disc_patient",$data);
	}
	
	public function payment_dischrge_patient($pid){//echo "I'm here";
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		else{
			$this->patient_session_id = session()->get('patient_session_id');
		
		$args = [
			'pid'      => (int) $this->patient_session_id,
			//'status'           => 'Discharged'
		];
		
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] == false) {
			$this->session->setTempdata('error', 'Discharge patient info missing in patients_discharge table', 3);
			return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
		}
	}
		//echo "<pre>";print_r($data['payment_bill']);die;
		return view('Admin/payment_dischrge_patient', $data);
	}
	


	public function filter_doc_dis_patients($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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

		$args = ['status'        => 'Discharged'];
		$data = [
			'patients' => $this->adminModel->filter_rec_by_args_with_pagination('patients', $order, $args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/manage_doc_dis_patient', $data);
	}


	public function search_patient(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
		
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/manage_patients', $data);
	}
	
	public function search_all_appointments(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'all_appointments' => $this->adminModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/Account/all_appointments', $data);
	}
	
	public function search_today_appointments(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
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
			$this->result_arr = $this->adminModel->search_records('booked_doctor_appointment', $srch_field, $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;}   
		$data = [
			'today_appointment' => $this->adminModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->adminModel->pager
	  	];
		return view('Admin/Account/today_appointment', $data);
	}
	
	public function search_cancelled_appointments(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		} 
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
			$args = [
				//'status'  => 'Active'
				//'updated_at' => date('Y-d-m h:i:s'),
				'booking_date' => date('Y-m-d')
			];
			// if ($keyword) {  
			// 	 $this->result_arr = $this->adminModel->search_records('booked_doctor_appointment', 'patient_name', $keyword,  $args);
			// } 
			$srch_field = 'patient_name'; //Searching table field name
			if ($keyword) {
				$this->result_arr = $this->adminModel->search_records('booked_doctor_appointment',$srch_field, $keyword, $args);
			}  
			else{  
				 $this->result_arr = $this->adminModel;
			}   
			
			$data = [
				'all_appointments' => $this->adminModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
				'pager'     => $this->adminModel->pager
			];
			return view('Admin/manage_patients', $data);
		}//Function- Closed


	/* @param: Add ward as Admin
	* @desc: 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function discharge_summary(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		return view('Admin/discharge_summary');
	}

	
	/* @param: Upload ward function  as Admin
	* @desc: Upload details of ward
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function upload_summary(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'patient_name'      => 'required',
			];
			if ($this->validate($rules)) {
				$this->user_data_arr = [
					'patient_name'          =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
					'prescription_detail'   =>  $this->request->getVar('prescription_detail'),
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				$status = $this->adminModel->Insertdata('prescription_history', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Ward Added Successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Add  Ward  Try Again ?', 3);
				}
				return redirect()->to(base_url() . 'Admin/manage_patients');
			} else {
				$data['validation'] = $this->validator;
				return view('Admin/discharge_summary', $data);
			}
		}
		return view('Admin/discharge_summary', $data);
	}

	public function add_summary(){
		return view("Admin/add_summary");
	} //Function- Closed


	public function filter_patients($filter) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
			//'status'        => 'Discharged'
			'is_del'        => 0
		];
		$data = [
			'patients' => $this->adminModel->filter_rec_by_args_with_pagination('patients', $order, $args),
			'pager'    => $this->adminModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Admin/fetch_patients");
		}
		// echo"<pre>";print_r($data);die;
		return view("Admin/manage_patients", $data);
		// return view('Admin/manage_doctor', $data);
	} //Function- Closed


	public function filter_patients_dis_pat($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'status'        => 'Discharged',
			'is_del' 		=> 0
		];
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'    => $this->adminModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Admin/fetch_patients");}
		return view("Admin/manage_patients", $data);
	}

   /* @params: Function for filter discharge patients
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function filter_admitted_pat($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'status'        => 'Admit' ];
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'    => $this->adminModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Admin/fetch_patients");}
		return view("Admin/manage_patients", $data);
	}

	public function filter_deleted_pat($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'status' => 'Deleted',
			'is_del' => 0
		];
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'    => $this->adminModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Admin/fetch_patients");
		}
		// echo"<pre>";print_r($data);die;
		return view("Admin/manage_patients", $data);
		// return view('Admin/manage_doctor', $data);
	}



	/* @params: Function forget password
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function forget_password(){
		$data = [];
		if ($this->request->getMethod() == 'post') {
			$rules = [
				 	'email'           => [
                   // 'rules'     => 'required|valid_email|is_unique[register_all_users.email]',
				   'rules'        => 'required|valid_email',
                    'errors'      => [
                        'required'=> 'Please enter email',
                        'valid_email'  => 'Please enter valid email', //email format check
                       // 'is_unique'    => 'Email is already existing. Please try another email',
                    ],
                ],
			];

			if ($this->validate($rules)) {
				$this->email_to = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
				
				$this->token = bin2hex(random_bytes(16)); //Generate unique bin2hex number
				
				$this->is_unique = $this->commonForAllModel->is_single_record('admin_users', 'email', $this->email_to);
				if($this->is_unique === false) {
					$this->session->setTempdata('error', 'Sorry ! Email is not registered with us. Please try again', 3);
					return redirect()->to(current_url());
				}
				$this->updt_args = ['email' 		=> $this->email_to];
				$this->updt_data_arr = [
						'reset_pass_token'	=> $this->token,
						'updated_at'		=> date('Y-m-d H:i:s')
					];
				//$this->save_status = $this->doctorModel->update_rec_by_args('admin_users', $this->updt_args, $this->updt_data_arr );
				$this->save_status = $this->adminModel->update_rec_by_args('admin_users', $this->updt_args, $this->updt_data_arr );
				if ($this->save_status) {
					//$email_to = $this->email;
					$subject  = 'Reset Password Link';
					$message  = 'Hi,'
								. '<br><br>'
								.'Your Reset Password request has been Received. Please Click '
								.'the below Link to reset your Password.<br><br>'
								.'<a href="'.base_url().'/Admin/reset_password/'.$this->token.'">Click Here to Reset Password</a>'
								.'<br>Thanks <br> '
								. DEV_TEAM . '<br>'
								.DEV_AUTHOR . ' at ' . WEBSITE . '<br>';

					echo $message; die; // Need to commented out on production server
					$this->email->setTo($this->email_to);
					$this->email->setFrom('ADMIN_EMAIL', 'Software Developer & Blogger');
					$this->email->setSubject($subject);
					$this->email->setMessage($message);
					if ($this->email->send()) {
						$this->session->setTempdata('success', 'Reset Password link has sent to your registered email. Please check & verify with in 15 minutes', 3);
						//echo $message; die; //need to commented-in it
						return redirect()->to(current_url());
					}
					else { 
						// $data = $this->email->printDebugger(['headers']);
						// print_r($data);
						$hrd_data = $this->email->printDebugger(['headers']);
						print_r($hrd_data);
					} 
				}
				else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
					return redirect()->to(current_url());
				}
			}
			else {
				$data['validation'] = $this->validator;
				return view('Admin/forget_password');
			}
		}
		return view('Admin/forget_password');
	} //function - Closed


	/* @params: $time: current time
	* @desc: Check is sent reset password token ie an unique (bin2hex) number has expired or not
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function checkExpiry_time($time){
		$update_time   = strtotime($time);
		$current_time  = time();
		$timeDiff      = ($current_time - $update_time)/60;
		if ($timeDiff < 900) { return true; }
		else { return false; }
	}

	/* @params: Function for filter discharge patients
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	
	public function reset_password($token = null) {
		$this->token = $token;
		$data = [];
		$data['validation'] = null;
		if (!empty($this->token)) {
			$userdata = $this->commonForAllModel->get_records_for_token('admin_users', $this->token);
			if (!empty($userdata)) {
				$check_exp_time = $this->checkExpiry_time($userdata['updated_at']);
				//$data['error'] = 'Unable to Find User Account';
				if ($check_exp_time) {
					if($this->request->getMethod() == 'get') { //To reset password page
						return view('Admin/reset_acc_password', $data);
					}
					else if($this->request->getMethod() == 'post') { //Reset Password 
					 	$rules = [
							'new_password' => [
								'label'  => 'Password',
								'rules'  => 'required|min_length[6]|max_length[20]',
								'errors'    => [
									'required' 	=> 'New password is mandatory.',
									'min_length' => 'New password minimum required length is 6.',
									'max_length' => 'New password maximum required length is 20.'
								]
							],
							'confirm_password' => [
								'label'  => 'Confirm Password',
								'rules'  => 'required|min_length[6]|max_length[20]|matches[new_password]',
								'errors' => [
									'required' => 'Confirm password is mandatory.',
									'matches' => 'New password and confirm password is not matched.',
									'min_length' => 'Confirm password length must at least 6.',
									'max_length' => 'Confirm password length must at max 20.'
								]
							]
						];
						if ($this->validate($rules)) {
							$password = password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT);
							$this->updt_args = [
								'reset_pass_token'	=> $this->token //So that once used reset password token may not use again	
							];
							$this->updt_data_arr = [
								'password'		=> $password,
								'updated_at'	=> date('Y-m-d H:i:s'),
								'reset_pass_token'	=> '' //So that once used reset password token may not use again
							];
							$update_pass = $this->adminModel->update_rec_by_args('admin_users', $this->updt_args, $this->updt_data_arr );
							if ($update_pass) {
								$this->session->setTempdata('success', 'Password updated successfully',3);
								return redirect()->to(base_url().'/Login');
							}
							else {
								$this->session->setTempdata('error', 'Sorry, Unable to update password. Please try again !',3);
								return redirect()->to(current_url());
							}
						}
						else { 
							//$data['validation'] = $this->validator;
							$this->session->setTempdata('error','Validation failed', 3);
							return view('Admin/reset_acc_password', $data);
						}
					}
					else { //Unexpected request method
						$this->session->setTempdata('error', 'Unexpected request method.', 3);
						return view('Admin/forget_password', $data);
					}
				}
				else { //$data['error']  = 'Reset password link has expired';
					$this->session->setTempdata('error', 'Unexpected link or link has expired. Please try again.', 3);
					return view('Admin/forget_password', $data);
				}
			} 
			else {
				$this->session->setTempdata('error', 'Unable to find user account ! Please try again',3);
				return view('Admin/forget_password', $data);
			}
		}
		else {
			//$data['error']  = 'Unauthorized or Reset password token has expired';
			$this->session->setTempdata('error', 'Unauthorized or Reset password token has expired !',3);
			return view('Admin/forget_password', $data);
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
	public function change_patients_status($id, $pid, $status) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$this->admin_uid = session()->get('admin_session_uid');
			if (!isset($this->admin_uid) || $this->admin_uid == '') {
				$this->session->setTempdata('error', 'Admin UID is missing !', 3);
				return redirect()->to(base_url() . "/Login");
			}
		}
		$args = [ 'id' => $id,]; 
		$data = [
				'status'  => $status,
				'updated_at' => date('Y-d-m H:i:s'),
				'updated_by' => $this->admin_uid,
			];
		$status = $this->patient_model->update($id, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
			return redirect()->to(base_url() . '/Admin/manage_patients');
		}
	} //funtion - Closed

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
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->patient_id = (int) $patient_id; 
			$this->pid = (int) $pid; //Patient ID
			$this->apmt_id = (int) $apmt_id; //appointment ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 
			//var_dump($this->patient_id);die;
			$data = [];
			$data['validation'] = null;
				
			// $data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function
			// $data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices
			$dr_args = [ 'id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $this->apmt_id ]; 
			$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Admin/payments/add_admission_fee', $data); // Pass the data to the view
	} //function - Closed

	/* @params: Function for get_dept_for_admission
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/

	///public function get_dept_for_admission() {
		public function get_dept_for_admission($patient_id, $pid, $puid, $apmt_id, $serial, $dr_id) {   
			if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");}
			$this->data['patient_id'] = $patient_id;
			$this->data['pid'] = $pid;
			$this->data['puid'] = $puid;
			$this->data['apmt_id'] = $apmt_id;
			$this->data['dr_id'] = $dr_id;
			//$this->data['serial'] = $serial;
			$this->dr_id = session()->get('doctor_session_id');
			$this->dept_args = [
				'is_del' 	=> 0,
				'status' 	=> 'Active',
			];
			//$this->data['patient_id']
			$this->data['departments'] = $this->doctorModel->fetch_rec_by_args('department', $this->dept_args);
			//echo "<pre>";print_r($this->data);die;
			if($this->request->getMethod() == 'get') {
				return view('Admin/admission_process', $this->data);
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Admin/admission_process', $this->data);
			}
		} //function - closed


		public function get_dept_for_rev_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
			if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			}
			$this->data['patient_id'] = $patient_id;
			$this->data['pid'] = $pid;
			$this->data['puid'] = $puid;
			$this->data['apmt_id'] = $apmt_id;
			$this->data['dr_id'] = $dr_id;
			//$this->data['patient_id'] = $patient_id;
			$this->dr_id = session()->get('doctor_session_id');
			$this->dept_args = [
				'is_del' 	=> 0,
				'status' 	=> 'Active',
			];
			//$this->data['patient_id']
			$this->data['departments'] = $this->doctorModel->fetch_rec_by_args('department', $this->dept_args);
			//echo "<pre>";print_r($this->data);die;
			if($this->request->getMethod() == 'get') {
				return view('Admin/revisit_addmission_process', $this->data);
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Admin/revisit_addmission_process', $this->data);
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
			if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			}
	
			$this->doctor_id = session()->get('doctor_session_id');
			$this->data['wards'] = [];
			if($this->request->getMethod() == 'post') {
				$this->dept_id  = $dept_id;
				$this->ward_args = ['dept_id' 		=> $this->dept_id];
				$this->data['wards'] = $this->adminModel->fetch_rec_by_args('hospital_wards', $this->ward_args);
				if($this->data['wards'] === false) {
					$this->result_arr = array(
						'status'	=> false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'		=> 200,
						'message'	=> 'No ward found. Please talk admin.',
						'data'		=> array(),
					);
					return json_encode($this->result_arr);
				}
				else {
					$this->result_arr = array(
						'status'	=> true,
						'error'		=> false, //error: `false` with status `true`
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
			if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			}
			$this->doctor_id = session()->get('doctor_session_id');
			if($this->request->getMethod() == 'post') {
				$this->ward_id  = $ward_id;
				$this->bed_args = [
					'ward_id' 		=> $this->ward_id,
					//'is_free' 	=> 1,
					'status'		=>'Free'
					];
				$this->data['beds'] = $this->adminModel->fetch_rec_by_args('hospital_beds', $this->bed_args);
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
						'error'		=> true, //error: `true` whereever status is false with SQL err 
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
	

	  /* @params: Function for filter discharge patients
		* @desc: Admission Processed 
		* @use: Admin....
		* @return:
		* @author: Neoarks Team
		* @date: 18th August,2023
		* @modify
		*/

		public function admission_process() { 
			$this->insrtData = []; //Just for addressing notices
			if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			}
			$this->doctor_uid = session()->get('admin_session_uid');
			$data  = [];
			// $data['validation'] = null;
			if($this->request->getMethod() == 'post') { 
				$this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
				$this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
				if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
					$this->result_arr = array(
						'status' => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code'	=> 200,
						'message' => 'Patient ID may not blank',
						'data' => $this->insrtData
					);
					return json_encode($this->result_arr);
				}
				$this->updt_data_arr = [
						'department_name'  => $this->request->getPost('department_name',FILTER_SANITIZE_STRING),
						'ward_name'        => $this->request->getPost('wardname',FILTER_SANITIZE_STRING),
						'bed_lable'        => $this->request->getPost('bed_lable',FILTER_SANITIZE_STRING),
						'other_info'       => $this->request->getPost('other_info',FILTER_SANITIZE_STRING),   //Need to mention name in HTML
						'patient_id'       => $this->patient_id,
						// 'is_free'	   => 0,
						'status'		   =>'Occupied',
						'pid'       	   => $this->request->getPost('pid',FILTER_SANITIZE_STRING),
						'puid'       	   => $this->request->getPost('puid',FILTER_SANITIZE_STRING),
						'apmt_id'          => $this->request->getPost('apmt_id',FILTER_SANITIZE_STRING),
						'dr_id'       	   => $this->request->getPost('dr_id',FILTER_SANITIZE_STRING),
						'updated_at' 	   => date('Y-m-d h:i:s'),
						'updated_by'       => $this->doctor_uid,
				];
				//echo "<pre>"; print_r($this->updt_data_arr);die;
				$this->updt_args = [ 'id' 	=> $this->bed_id ];  
				$status = $this->adminModel->update_rec_by_args('hospital_beds', $this->updt_args, $this->updt_data_arr );  
				if ($status === true) {
					$this->updt_data_arr1 = [
						'status'       	=> 'Admission Processed',
						'updated_at' 	=> date('Y-m-d h:i:s'),
						'updated_by'    => $this->doctor_uid,
					];
					$this->updt_args1 = [ 'id' 	=> $this->patient_id];
					$this->updt_data_arr2 = [
						'status'       	=> 'Admission Processed',
						'updated_at' 	=> date('Y-m-d h:i:s'),
						'updated_by'    => $this->doctor_uid,
					];
					$this->updt_args2 = [ 'id' 	=> $this->patient_id];
					$status = $this->commonForAllModel->update_into_two_tables('patients',  $this->updt_args1, $this->updt_data_arr1, 'revisit_patients',  $this->updt_args2, $this->updt_data_arr2);
					if ($status === true) {
						$this->result_arr = array(
							'status' => true,
							'error'	 => false, //error: `false` with status `true`
							'code'	 => 200,
							'message'=> 'Record updated successfully',
							'data'   => $this->updt_data_arr
						);
						return json_encode($this->result_arr);
					} 
					else {
						$this->result_arr = array(
							'status' => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							'code'	 => 200,
							'message'=> 'Failed! to update record',
							'data'   => $this->updt_data_arr
						);
						return json_encode($this->result_arr);
					} //else - loop closed 
				}
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	 => 200,
						'message'=> 'Failed! to update record',
						'data'   => $this->updt_data_arr
					);
					return json_encode($this->result_arr);
				} //else - loop closed
			}
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	 => 200,
					'message'=> 'Unexpected request method',
					'data'   => $this->updt_data_arr
				);
				return json_encode($this->result_arr);
			} //else - loop closed
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
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
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
					'payment_note'	=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'           =>  $this->pid,
					'puid'          =>  $this->puid,
					'patients_id'   =>  $this->patient_id,
					'apmt_id'    	=>  $this->apmt_id,
					'pay_date'      =>  date('Y-m-d'),
					'created_at'	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->admin_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					session()->setTempdata('error','Oops ! Unable to update payments', 2);
					return redirect()->to(base_url().'Admin/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
				}
			} 
			else { 
				$data['validation'] = $this->validator;
				return view('Admin/payments/add_admission_fee', $data);
				$this->session->setTempdata('error', 'Failed to validate mandatory fields', 3);
			}
		}
		else { 
			$this->session->setTempdata('error', 'Request method is not supported', 3); 
		}
		$this->pcnt_args = ['id' 	=> $this->apmt_id];
		$data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

		$this->dr_args = ['id' 	=> $this->apmt_id]; //NEED $doctor_id here
		$data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $this->dr_args);
		return view('Admin/payments/add_admission_fee', $data);
	} //function - Closed


	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	public function generate_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		//$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = false; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Admin/add_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		//var_dump($this->patient_id);die;
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
			$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Admit', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->admin_uid,
				];
			$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		//var_dump($this->updt_status);die;
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Admin/payments/generate_admission_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Admin/payments/add_admission_fee', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Admin/payments/generate_admission_bill', $data);
		}
	} //function - Closed
	/****************************** Admimission Fee - END ******************************/



	

	public function delete_patients($id){
		$this->patient_id = $id;
        if (!(session()->has('admin_session_uid'))) {
            return redirect()->to(base_url()."/Login");
        }
        $this->admin_uid = session()->get('admin_session_uid');
        if(!isset($this->admin_uid) || $this->admin_uid == '') {
            $this->session->setTempdata('error', 'Admin UID is missing !', 3);
            return redirect()->to(base_url()."/Login");
        }
        $args = [ 'id'   => $id ,
		          'is_del'=> 0
				];
        $data = [
            'status' => 'Deleted', 
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->admin_uid
        ];
        $status = $this->adminModel->update_rec_by_args('patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success','Congratulation ! Patients has deleted successfully', 2);
            //return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
            return redirect()->to(base_url().'Admin/manage_patients');
        }
        else {
            session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
            return redirect()->to(base_url().'Admin/manage_patients');
        }
    } //Function - Closed

	/* @params: Function for permanent delete doctor fee
	* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	
	public function permanent_del_patients($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  =>  $id, ];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/manage_patients');
	} //function - Closed


	public function delete_all_dis_patients($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$args = [
			'id' => $id,
            'is_del' => 0,            
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('patients', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_all_discharged_patient');
	}


	public function delete_all_appointments($id){
		$this->patient_id = $id;
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'   => $id ];
		$data = [
			'status' => 1, //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		// $status = $this->adminModel->update_rec_by_args('doctor_fee',  $args, $data);
		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Appointment has deleted successfully', 2);
			//return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
			return redirect()->to(base_url() . 'Admin/all_appointments');
		} else {
			session()->setTempdata('error', 'Oops ! Appointment is not deleted ', 2);
			return redirect()->to(base_url() . 'Admin/all_appointments');
		}
	}

   /* @params: Function for permanent delete all discharged patients
	* @desc: Admin can soft all discharged also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_all_dis_pat($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$args = [
			'id' => $id,
			 
		];
		$data = [
			'is_del'     => 1,
			'status'     => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('patients', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_all_discharged_patient');
	}


	public function delete_today_dis_patients($id){
		$this->patient_id = $id;
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		// $status = $this->adminModel->update_rec_by_args('patients',  $args, $data);
		$status = $this->adminModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);

			return redirect()->to(base_url() . 'Admin/manage_today_discharged_patient');
		} else {
			// session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
			return redirect()->to(base_url() . 'Admin/manage_today_discharged_patient');
		}
	}


	


	public function edit_patients($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}$args = [
			'id'  => $id ];
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		return view('Admin/edit_patients', $data);
	}


	public function update_patients($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'patient_name'           =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
			'patient_phone'          =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
			'patient_address'        =>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),
			'patient_zip'            =>  $this->request->getVar('patient_zip', FILTER_SANITIZE_STRING),
			'doctor_name'            =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
			'doctor_fee'             =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
			'entry_fee'              =>  $this->request->getVar('entry_fee', FILTER_SANITIZE_STRING),
			'patient_issue'          =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
			'other_fee'              =>  $this->request->getVar('other_fee', FILTER_SANITIZE_STRING),
			'patient_room'           =>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
			'patient_email'          =>  $this->request->getVar('patient_email')
		];
		$status = $this->adminModel->update_rec_by_args('patients', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
		}
		//return redirect()->to(base_url().'/Admin/edit_patients/'.$id);
		return redirect()->to(base_url() . 'Admin/manage_patients');
	}

	public function print_slip($id , $pid){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		//echo "<pre>";print_r($id. '/' . $pid);die;
		$args = [
			'pid'  => $pid
		];
		$data['patient_slip'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		//echo "<pre>";print_r($data['patient_slip']);die;
		return view('Admin/print_slip', $data);
	}


	public function discharge_patients($id){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Admin/discharge_patients', $data);
	}
	


	//public function discharge_apmnt_patient($id){
	public function discharge_apmnt_patient($patient_id, $payid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');

		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		
		$args = [ 'id'  => $this->patient_id ];
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Admin/discharge_appointment_pat', $data);
	} //function - closed


	/*** Clear Dues, Add Payment, Add Expenses Generate Bill- START *****/
	

 
	/* @param: Function for Book patient/ self appointment 
     * @description: search_patient details from the list of the patients.
     * @date: 21st June, 2023
     * @modify: March 11th, 2025
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
	public function add_received_payment($id, $pid, $apmtid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		
		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
			return redirect()->to(base_url() . 'Admin/manage_patients');
		}
		$data = []; //for addressing notices
		
		
		$data['validation'] = null;
		$data['payments'] = $this->get_patient_final_payments_neo($id, $pid, $apmtid, $puid);
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if ($this->request->getMethod() == 'get') { //Render payment form - with paid payment and hospital expence
			
			return view('Admin/add_patient_payment', $data);
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
					'created_by'            => $this->admin_uid
				];
					
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Admin/generate_receive_payment_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_receive_payment_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Mandatory validation failed', 2);
				$data['validation'] = $this->validator; 
				return view('Admin/add_patient_payment', $data); 
			}
		}
		else {
		 	$this->session->setTempdata('error', 'Unexpected request method', 2);
		 	$data['validation'] = $this->validator;
			return redirect()->to(base_url().'Admin/manage_patients');
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
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate_apment_patient_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate_apment_patient_bill', $data);
		}
	} //function - Closed
	
	
	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Generate pdf format bill for patient provided payments 
	* @retuns: Internally used function
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_receive_payment_bill($patient_id, $payid, $apmtid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate_receved_payment', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate_receved_payment', $data);
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
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		$data = [
				'id' 		=> $id,
				'pid' 		=> $pid,
				'apmt_id'	=> $amptid,
				'puid'		=> $puid,
			];
		return view('Admin/add_hospital_expenses', $data); 
	} //function - Closed


	/*@params: Ajax call
	* @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function save_hospital_expenses() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if ($this->request->getMethod() == 'post') {
			//$data['id'] = $id;
			$rules = [
					'medical_item_name' => 'required',
					'unit_price'    => 'required',
					//'quantity' => 'required',
					'tax'      => 'required',
					//'taxCalculation' => 'required',
					//'date'     => 'required'
				];
				//var_dump($this->request->getPost('unit_price'));die;
			if ($this->validate($rules)) {
				
				$insdata = [
					'medical_item'        =>$this->request->getPost('medical_item_name'),
					'medical_code'        =>  $this->request->getPost('item_code'),
					'unit_price'          =>  $this->request->getPost('unit_price'),
					'total_Price'         =>  $this->request->getPost('totalPrice'),	
					'units'               =>  $this->request->getPost('quantity'),
					'tax_percentage'      =>  $this->request->getPost('tax'),
					'tax_amount'    	  =>  $this->request->getPost('taxCalculation'),
					'discount_percentage' =>  $this->request->getPost('discount'),
					'discount_amount'     =>  $this->request->getPost('discount'),
					'patients_id'         =>  $this->request->getPost('patients_id'),
					'pid'        		  =>  $this->request->getPost('pid'),
					'apmt_id'        	  =>  $this->request->getPost('apmt_id'),
					'puid'        		  =>  $this->request->getPost('puid'),
					'created_at'          =>  date('Y-m-d h:i:s'),
					'created_by'          =>  $this->admin_uid,
				];

				$status = $this->adminModel->Insertdata('treatment_expenses_history', $insdata);
				if ($status === true) {
					$this->result_arr = array(
						'status'    => true,
						'error'		=> false, //error: `false` with status `true`
						'code'  	=> 200,
						'message'   => 'Expenses added successfully',
						'data'      => $insdata
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status'   => false,
						'error'	   => true, //error: `true` whereever status is false with SQL err 
						'code'	   => 200,
						'message'  => 'Failed! to add expenses',
						'data'     => $insdata
					);
					return json_encode($this->result_arr);
				}
			} 
			else { 
				$insdata['validation'] = $this->validator;
				$insdata = $insdata['validation']->getErrors();
				
				$this->result_arr = array(
					'status' => false,
					'error'	 => false, //error: `false` showing validation error autoamtically
					'code'	 => 200,
					'message'=> 'Validation failed',
					'data'   => $insdata
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
				'data'   => $array(),
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
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
		$data['patients'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
		
		$args_apmt = [ 'id'  => $apmtid ];
        $data['get_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		return view('Admin/show_patient_final_payments', $data);
	} //function - Closed

	// public function show_patient_final_payments($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} 
	// 	$rules = [ // Define validation rules for the form
	// 		'total_hospital_expenses'    => 'required|numeric',
	// 		'total_patient_paid_amount'  => 'required|numeric',
	// 		'final_adjusted_amount'      => 'required|numeric',
	// 		'remark'                     => 'required'
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
    //     //echo "<pre>"; print_r($data);die;
	// 	return view('Admin/show_patient_final_payments', $data); // Pass the data to the view
	// } //function - Closed


	public function get_patient_final_payments_neo($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
        $this->data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);

		$args = [ 'id'  => $this->patient_id ];
		$this->data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		//echo "<pre>";print_r($this->data);die;
		return $this->data;
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
	public function clear_final_dues($patient_id, $pid, $apmtid, $puid, $status) {//pid is patient_login tbl id
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
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
					return redirect()->to(base_url() . 'Admin/show_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
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
					'created_by'	=> $this->admin_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Admin/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				}
			} 
			else { $data['validation'] = $this->validator;
				return view('Admin/show_patient_final_payments', $data);
			 }
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Admin/discharge_appointment_pat', $data);
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid, $status) { //Ref generate_patient_bill()
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		$this->status = $status;

		if($this->status == 'Admit') { $this->status = 1; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
		else if($this->status != 'Admit') { $this->status = 2; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient

		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Admin/show_patient_final_payments/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
			'status'  => $this->status,
			'created_at'  => date('Y-m-d H:i:s'),
			'created_by'  => $this->admin_uid,
		];
		$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate_clear_dues_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate_clear_dues_bill', $data);
		}
	} //function - Closed
	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/

   /* @params: Function for update view profile
	* @desc: Admin can update view profile 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 23rd Oct,2023
	* @modify
	*/
	public function update_admin($id = '') {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'   => $id ];
		$data = [
			'username'       => $this->request->getVar('username', FILTER_SANITIZE_STRING),
			'email'          => $this->request->getVar('email', FILTER_SANITIZE_STRING),
			'phone'          => $this->request->getVar('phone', FILTER_SANITIZE_STRING),
			'age'            => $this->request->getVar('age', FILTER_SANITIZE_STRING),
			'address'        => $this->request->getVar('address', FILTER_SANITIZE_STRING),
			'state'          => $this->request->getVar('state', FILTER_SANITIZE_STRING),
			'zip'            => $this->request->getVar('zip', FILTER_SANITIZE_STRING),
			// 'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
			// // 'profile_pic'       => $doc_img
			'updated_at'             => date('Y-m-d h:i:s')
		];

		$status = $this->adminModel->update_rec_by_args('admin_users', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Admin details updated successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3);
		}
		//return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
		return redirect()->to(base_url() . 'Admin/view_profile/');
	}
	
	public function edit_admin_profile($id = '') {   
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			//echo 'Route has been Added';
		return view('Admin/edit_admin_profile');
		}
	}

	/***************************** Prescritpion Section - START  *****************************/
	/* @param: 
    * @desc: Render Prescription Form: Step - Zero
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	function add_prescription($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		else {
			$data = [
				'res'  => $this->adminModel->getPatientRecordWithAppointment($id),
				'pager'   => $this->adminModel->pager
			];
			$args = [ 'patient_id'	=>		$id ]; //Patient ID
			$this->report = $this->adminModel->fetch_rec_by_args('patient_reports', $args);
			$this->reportArr = [];
			$this->reportAtchMentArr = [];
			if($this->report) {
				foreach($this->report as $val){
					$this->reportArr[$val->id] = $val->report_name;
					$this->reportAtchMentArr[$val->id] = $val->report_attachment;
				}

			} //else loop - Not requred here because no report has assigned to patient in this case
			$data['report'] = $this->reportArr; 
			$data['reporatach'] = $this->reportAtchMentArr; 
			return view('Admin/add_patient_prescription', $data);
		}
	} //function - Closed



   /* @param: 
    * @desc: Add Prescription: Step - 1
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	function upload_prescription() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}  
		else {
			$this->return_dt_arr = []; //Just for addressing notices
			$this->admin_uid = session()->get('admin_session_uid'); //Loggedin User uid
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr_arr = array(
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				); 
				return json_encode($this->result_arr);
			}
			$rules = [ 
				'prescription'  => 'required',
				'doctor_name'  	=> 'required',
				'patient_id'  	=> 'required',
				'patient_name'  => 'required',
				'patient_puid'  => 'required',
				];	
			if ($this->validate($rules)) {
				$prescription      = $this->request->getPost('prescription');
				$patient_name      = $this->request->getPost('patient_name');
				$patient_id 	   = $this->request->getPost('patient_id');
				$doctor_id 	       = $this->request->getPost('doctor_id');
				$patient_age       = $this->request->getPost('patient_age');
				$patient_mobile    = $this->request->getPost('patient_mobile');
				$patient_gender    = $this->request->getPost('patient_gender');
				$patient_puid      = $this->request->getPost('patient_puid');
				$doctor_name       = $this->request->getPost('doctor_name');
				$education         = $this->request->getPost('education');
				$dr_specialization  = $this->request->getPost('dr_specialization');
				$doc_gender        = $this->request->getPost('doc_gender');
				$apmt_id 	       = $this->request->getPost('apmt_id');
				$pid 	           = $this->request->getPost('pid');
				$ref_by 	       = $this->request->getPost('ref_by');
				$patient_mobile    = $this->request->getPost('patient_mobile');
				$patient_email 	   = $this->request->getPost('patient_email');
				$disease_symptoms  = $this->request->getPost('disease_symptoms');
				$patient_type 	   = $this->request->getPost('patient_type');
				$status 	   	   = $this->request->getPost('status');
				
				if($status != "Admit") { $status = 'Prescribed'; }
				$this->prescription_data_arr = [
					'prescription'    	  =>  $prescription,//
					'prescription_detail' =>  $prescription, //
					'patient_name'        =>  $patient_name,
					'patient_id'          =>  $patient_id,
					'doctor_id'           =>  $doctor_id,
					'status'              =>  $status, 
					'age'          		  =>  $patient_age,
					'gender'          	  =>  $patient_gender,
					'puid'          	  =>  $patient_puid,
					'doctor_name'         =>  $doctor_name,
					'apmt_id'        	  =>  $apmt_id,
					'pid'        		  =>  $pid,
				    'ref_by'    		  =>  $ref_by,
					'patient_mobile'      =>  $patient_mobile,
					'patient_email'       =>  $patient_email,
					'disease_symptoms'    =>  $disease_symptoms,
					'patient_type'        =>  $patient_type,
					'prescription_date'   =>  date('Y-m-d H:i:s'),
					'created_at'      	  =>  date('Y-m-d H:i:s'),
					'created_by'      	  =>  $this->admin_uid, //Harcoded for now
				];
				//echo "<pre>";print_r($this->prescription_data_arr);die;
				//$status = $this->adminModel->Insertdata('prescription_history', $this->prescription_data_arr);
				//$last_insrt_id = $this->commonForAllModel->Insertdata_return_id('prescription_history', $this->prescription_data_arr);

				$this->pat_args = ['id' 	=> $patient_id];
				$this->updt_patient_arr = [
					'status'		=> $status,
					'updated_by'	=> $this->admin_uid,
					'updated_at'	=> date('Y-m-d H:i:s')

				];
				$this->fld = '';
				$this->fldval = '';
				$last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'patients', $this->pat_args, $this->updt_patient_arr);
				//var_dump($last_insrt_id);die;
				if ((int) $last_insrt_id > 0 ) {
					$this->return_dt_arr = ['prescription_id' => $last_insrt_id];
					//$this->data['prescription'] = array(array());
					 $this->result_arr = array(
						'status' => true,
						'error'		=> false, //error: `false` with status `true`
						'code' => 200,
						'message' => 'Prescription added successfully',
						'data'		=> $this->return_dt_arr,
					); 
					return json_encode($this->result_arr); 
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code' => 200,
						'message' => 'Failed to add prescripton. Please try again.',
						'data'		=> $this->return_dt_arr,
					); 
					return json_encode($this->result_arr);
				}
			}
			else{
				$this->result_arr = array(
					'status' => false,
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code' => 200,
					'message' => 'Validation failed. Please try again',
					'data'		=> $this->return_dt_arr,
				); 
				return json_encode($this->result_arr); 
			}
		} 
	} //function - Closed

   /* @param: 
    * @desc: List or Add New Reports: Step - 2
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	
	public function add_prescription_report($pid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session('admin_session_uid');
		$selected_report=$this->request->getPost('selected_report');
		$selected_report_arr=explode(',',$selected_report);
		
		if($this->request->getMethod() == 'post') {
			if(count($selected_report_arr)>0) {
				foreach($selected_report_arr as $val) {
					$this->report_data_arr = [
						'patient_id'	=>	$pid,
						'report_name'    =>  $val,
						'report_brief'   =>  $val, //
						'report_detail'  =>  $val,
						'report_attachment' =>  '',//$doc_img,
						'created_at'      =>  date('Y-m-d H:i:s'),
						'created_by'      =>  $this->admin_uid, //Hardcoded for now
					];
					$status = $this->doctorModel->Insertdata('patient_reports', $this->report_data_arr);
				}
			}
			if ($status === true) {
				$this->result_arr = array(
					'status' => true,
					'error'		=> false, //error: `false` with status `true`
					'code' => 200,
					'data' > '',
					'message' => 'Reports saved successfully'
				);
				return json_encode($this->result_arr);
			} 
			else {
				$this->result_arr = array(
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > '',
					'message' => 'Failed to save reports'
				);
				return json_encode($this->result_arr);
			}
		}
		else {
			$this->result_arr = array(
				'status' => false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Sorry ! Required GET method'
			);
			return json_encode($this->result_arr);
		}
	} //Function - Closed

	/* @param: 
    * @desc: Upload Reports: Step - 3
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	
	public function upload_prescription_report($pid, $rid,$file_id, $prescription_id) { //$pid: patient_id, $rid : Report ID
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid'); //Loggedin User uid
		 $data = [];
		 $data['validation'] = null;
			if ($this->request->getMethod() == 'post') {		
			$rpt = $this->request->getFile($file_id);
				$allowedFormats = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/gif'];
				if (!in_array($rpt->getMimeType(), $allowedFormats)) {
					$this->result_arr = [
						'status' => false,
						'error'  => true,
						'code' => 200,
						'data' => [],
						'message' => 'Invalid upload format. Upload media format is jpg, jpeg, png, svg, gif only.'
					];
					return json_encode($this->result_arr);
				}
			
				if ($rpt->getSize() > ALLOW_MAX_UPLOAD * 1024) {
					$this->result_arr = [
						'status' => false,
						'error' => true,
						'code' => 200,
						'data' => [],
						'message' => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
					];
					return json_encode($this->result_arr);
				}
				//if ($rpt->isValid() &&  !$rpt->hasMoved()) { //check usebility of isValid()
			if(!$rpt->hasMoved()) {
				$this->rand_name = $rpt->getRandomName();
				$rpt->move(FCPATH . 'uploads/patient_reports', $this->rand_name);
				$doc_img = $rpt->getName();
				
				$this->report_data_arr = [
					//'report_name'     =>  $doc_img,
					'pid'			    => $pid,
					'report_attachment' =>  $doc_img,
					'prescription_id'   =>  $prescription_id,
					//'ref_id'            =>  $ref_id,
					'updated_at'      =>  date('Y-m-d H:i:s'),
					'updated_by'      =>  $this->admin_uid,
				];
				$args = ['id'	=> $rid];
				//Need update model function in place of Insertdata - 
				$status = $this->adminModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
				if ($status === true) {
					$this->result_arr = array(
						'status' => true,
						'error'		=> false, //error: `false` with status `true`
						'code' => 200,
						'data' > '',
						'message' => 'Reports uploaded successfully'
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code' => 200,
						'data' > '',
						'message' => 'Failed to upload reports'
					);
					return json_encode($this->result_arr);
				}
				//return redirect()->to(current_url());
			} 
			else {
				// echo $image->getErrorString() . " " . $image->getError();
				// $this->session->setTempdata('error', 'Please Select any Image File', 3);
				// return redirect()->to(base_url() . '/Admin/add_prescription');
				$this->result_arr = array(
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > array(),
					'message' => 'Unable to move the uploaded report'
				);
				return json_encode($this->result_arr);
			}
		} 
		else {
			// $this->session->setTempdata('error', 'Sorry ! Required POST method.!', 3);
			// return redirect()->to(current_url());
			$this->result_arr = array(
				'status' => false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Unexpected request method. Please try again'
			);
			return json_encode($this->result_arr);
		}
		return redirect()->to(current_url());
	} //Function - Closed


   /* @param: 
    * @desc: Add Doctor Advice: Step - 4
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function save_advice() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}  
		else {
			$this->admin_uid = session('admin_session_uid');
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr = array(
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				); 
				return json_encode($this->result_arr);
			}
			$rules = [
				'advice'  => 'required',
			 ];	
			if ($this->validate($rules)) {
				$advice = $this->request->getPost('advice');
				$patient_id = $this->request->getPost('patient_id');
				
				$this->advice_data_arr = [
					'advice'    	=>  $advice,
					'updated_at'    =>  date('Y-m-d H:i:s'),
					'status'      	=> 'Prescribed',
					'updated_by'    =>  $this->admin_uid, //Harcoded for now
				];
				//echo"<pre>";print_r($this->advice_data_arr);die;
				$args = [ 'patient_id'   => $patient_id ];
				$fld_name = 'id';
				$this->maxid = $this->adminModel->get_max_val('prescription_history', $fld_name);
				if($this->maxid === false || (!isset($this->maxid['0']->id))) {
					$this->data['advice'] = array(array());
					 $this->result_arr = array(
						"status" => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						"code" => '200',
						"message" => 'ID is missing. Please talk to admin'
					); 
					return json_encode($this->result_arr);
				}
				else if($this->maxid['0']->id > 0 ) {
					$args = [ 'id'   => $this->maxid['0']->id ];
					$status = $this->adminModel->update_rec_by_args('prescription_history', $args, $this->advice_data_arr);
					if ($status === true) {
						$this->data['advice'] = array(array());
						$this->result_arr = array(
							"status" => true,
							'error'		=> false, //error: `false` with status `true`
							"code" => '200',
							"message" => 'Advice added successfully'
						); 
						return json_encode($this->result_arr); 
					} 
					else {
						$this->result_arr = array(
							"status" => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							"code" => '200',
							"message" => 'Failed to add advice'
						); 
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->data['advice'] = array(array());
						$this->result_arr = array(
							"status" => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							"code" => '200',
							"message" => 'Unexpected max ID. Please talk to admin'
						); 
					return json_encode($this->result_arr); 
				}
			}
			else {
				$this->data['advice'] = array(array());
				$this->result_arr = array(
					"status" => true,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'Validation failed.'
				); 
				return json_encode($this->result_arr); 
			}
		} //else - loop Closed
	} //Function - Closed

/* @param: 
    * @desc: Add Doctor Recommendation: Step -5
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function save_message() {
		if (!session()->has('admin_session_uid')) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$this->admin_uid = session('admin_session_uid');
			$this->request_method = $this->request->getMethod();
	
			if ($this->request_method != 'post') {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				]);
			}
	
			$rules = [
				'msg_frm_doc' => 'required',
				'slct_refr_usr' => 'required|numeric|greater_than[0]'
			];
	
			if (!$this->validate($rules)) {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'Validation failed.'
				]);
			}
	
			$msg_frm_doc = $this->request->getPost('msg_frm_doc');
			$patient_id = $this->request->getPost('patient_id');
			$assigned_to = $this->request->getPost('slct_refr_usr');
	
			$message_data = [
				'assigned_by' => $this->admin_uid,
				'assigned_to' => $assigned_to,
				'status' 	=> 'Prescribed',
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->admin_uid,
				'recommendation' => $msg_frm_doc,
			];
	
			$prescription_id = $this->adminModel->get_max_val('prescription_history', 'id');
	
			if ($prescription_id === false || empty($prescription_id[0]->id)) {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'ID is missing. Please talk to admin'
				]);
			}
	
			$status_history = $this->adminModel->update_rec_by_args('prescription_history', ['id' => $prescription_id[0]->id], $message_data);
	
			$status_patient = $this->adminModel->update_rec_by_args('patients', ['id' => $patient_id], ['status' => 'Prescribed']);
	
			if ($status_history === true && $status_patient === true) {
				return json_encode([
					"status" => true,
					'error'		=> false, //error: `false` with status `true`
					"code" => '200',
					"message" => 'Recommendation added successfully'
				]);
			} else {
				// Handle database errors
				$error_message = 'Failed to add Recommendation. Check the error logs for details.';
				log_message('error', $error_message);
	
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => $error_message
				]);
			}
		}
	}  // Function - Closed
	

	/***************************** Prescritpion Section - END  *****************************/

	
	

	public function add_patient_charge($id){		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
				$this->user_data_arr = [
					'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
					'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
					'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
					'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
					'patients_id'           =>  $args,
					'status'                =>  'Discharged',
					'pay_date'             =>  date('Y-m-d h:i:s')
				];
				$status = $this->adminModel->Insertdata('patients_pay_charges', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! charges added successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to add  patients charges', 3);
				}
				return redirect()->to(base_url() . 'Admin/generate_patient_bill/' . $id);
			} else {
				$data['validation'] = $this->validator;
			}
		}
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Admin/discharge_patients', $data);
	}


	

	

	

	public function update_revisit_patient($pid){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = ['pid'  => $pid];
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$data = [
			'patient_name'          =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
			'patient_phone'         =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
			'patient_address'       =>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),
			'pin_zip_code'          =>  $this->request->getVar('patient_zip', FILTER_SANITIZE_STRING),
			'doctor_name'           =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
			'doctor_fee'            =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
			'entry_fee'             =>  $this->request->getVar('entry_fee', FILTER_SANITIZE_STRING),
			'disease_symptoms'      =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
			'other_fee'             =>  $this->request->getVar('other_fee', FILTER_SANITIZE_STRING),
			'patient_room'          =>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
			'patient_email'         =>  $this->request->getVar('patient_email'),
			/*'next_action'			=> 	$this->request->getVar('next_action'),
			'assigned_by'			=> 	$this->request->getVar('assigned_by'),
			'assigned_to'			=> 	$this->request->getVar('assigned_to	'),
			'status'				=> 	$this->request->getVar('status'),
			'created_by'			=> 	$this->request->getVar('created_by'),
			*/
			'updated_at'            =>  date('Y-m-d H:i:s'),
			'updated_by'                => $this->admin_uid

		];
		$status = $this->adminModel->update_rec_by_args('revisit_patients',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Patients Revisit  Successfully', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
		}
		echo "<pre>";print_r($status);die;
		return redirect()->to(base_url() . '/Admin/manage_revisited_patients');
	} //Function - Closed



	


	
	

	
	//Patients Record Section Start with AutoModel Pagination Feature, Developed By Neoarks Team	

	//-----medicines Sction Query Start 
	public function med_category(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			return view('Admin/medicines/med_category');
		}
	}

	// public function add_med_category(){
		
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			'company_name'      => 'required|min_length[4]|max_length[50]'
	// 		];
	// 		if ($this->validate($rules)) {
	// 			$img = $this->request->getFile('category_image');
	// 			if ($img->isValid() &&  !$img->hasMoved()) {
	// 				$this->rand_name = $img->getRandomName();
	// 				#$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name); 
	// 				$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name);

	// 				$doc_img = $img->getName();
	// 				$this->user_data_arr = [
	// 					'company_name'          =>  $this->request->getVar('company_name', FILTER_SANITIZE_STRING),
	// 					'category_image'        =>  $doc_img,
	// 					'status'                => 'Active',
	// 					'created_at'            =>  date('Y-m-d h:i:s')
	// 				];
	// 				$status = $this->adminModel->Insertdata('medicine_category', $this->user_data_arr);
	// 				if ($status == true) {
	// 					$this->session->setTempdata('success', 'Congratulation ! Medicines Category Uploaded Successfully !', 3);
	// 				} else {
	// 					$this->session->setTempdata('error', 'Sorry ! Unable to Add Medicines Category Try Again ?', 3);
	// 				}
	// 				return redirect()->to(base_url() . 'Admin/manage_med_category');
	// 			} else {
	// 				//echo $img->getErrorString() . " " . $img->getError();
	// 				$this->session->setTempdata('error', 'No file is uploaded. Please browse a file', 3);
	// 				return view('Admin/medicines/med_category', $data);
	// 			}
	// 		} else {
	// 			$data['validation'] = $this->validator;
	// 		}
	// 	}
	// 	return view('Admin/medicines/med_category', $data);
	// }

	public function add_med_category(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
	
		$data = [];
		$data['validation'] = null;
	
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'company_name'      => 'required|min_length[4]|max_length[50]',
				'category_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Company name is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],

				//'category_image'    => 'uploaded[category_image]|max_size[category_image,1024]|is_image[category_image]|ext_in[category_image,png,jpg,jpeg, svg, gif]'
				'category_image' => [
					'rules'     => 'uploaded[category_image]|max_size[category_image,' . ALLOW_MAX_UPLOAD .']|is_image[category_image]|mime_in[category_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[category_image,png,jpg,jpeg, svg, gif]',
					'errors' => [
						'uploaded'  => 'Medicines Image is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
					],
				],
			];
	
			if ($this->validate($rules)) {
				$img = $this->request->getFile('category_image');
	
				if ($img->isValid() && !$img->hasMoved()) {
					$this->rand_name = $img->getRandomName();
					$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name);
	
					$doc_img = $img->getName();
					$this->user_data_arr = [
						'category_name'  => $this->request->getVar('category_name', FILTER_SANITIZE_STRING),
						'category_image' => $doc_img,
						'status'        => 'Active',
						'created_at'    => date('Y-m-d h:i:s')
					];
	
					$status = $this->adminModel->Insertdata('medicine_category', $this->user_data_arr);
	
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation! Medicines Category Uploaded Successfully!', 3);
					} else {
						$this->session->setTempdata('error', 'Sorry! Unable to Add Medicines Category. Try Again?', 3);
					}
					return redirect()->to(base_url() . 'Admin/manage_med_category');
				} 
				else {
					$this->session->setTempdata('error', 'No file is uploaded. Please browse a file', 3);
					return view('Admin/medicines/med_category', $data);
				}
			} 
			else {
				$data['validation'] = $this->validator;
				return view('Admin/medicines/med_category', $data);
			}
		}
		return view('Admin/medicines/med_category', $data);
	} //function closed
	

	public function manage_med_category(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'is_del'  => 0,

			];
			$data = [
				'med_category' => $this->adminModel->fetch_rec_by_args_arr('medicine_category', $args),
				'pager'     => $this->adminModel->pager
			];
			//echo "<pre>";print_r($data['med_category']);die;
			return view("Admin/medicines/manage_med_category", $data);
		}
	}

	public function add_med_company(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
	
		$data = [];
		$data['validation'] = null;
	
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'company_name'      => 'required|min_length[4]|max_length[50]',
				'company_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
					'required' => 'Company name is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 50.'
					],
				],

				
				'category_image' => [
					'rules'     => 'uploaded[category_image]|max_size[category_image,' . ALLOW_MAX_UPLOAD .']|is_image[category_image]|mime_in[category_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[category_image,png,jpg,jpeg, svg, gif]',
					'errors' => [
						'uploaded'  => 'Medicines Image is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
					],
				],
			];
	
			if ($this->validate($rules)) {
				$img = $this->request->getFile('category_image');
	
				if ($img->isValid() && !$img->hasMoved()) {
					$this->rand_name = $img->getRandomName();
					$img->move(FCPATH . 'uploads/medicine_company', $this->rand_name);
	
					$doc_img = $img->getName();
					$this->user_data_arr = [
						'company_name'  => $this->request->getVar('company_name', FILTER_SANITIZE_STRING),
						//'company_desc' 	=> $this->request->getVar('company_desc', FILTER_SANITIZE_STRING),
						'company_image' => $doc_img,
						'status'        => 'Active',
						'created_at'    => date('Y-m-d h:i:s')
					];
	
					$status = $this->adminModel->Insertdata('medicine_company', $this->user_data_arr);
	
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation! Medicines company Uploaded Successfully!', 3);
					} else {
						$this->session->setTempdata('error', 'Sorry! Unable to Add Medicines company. Try Again?', 3);
					}
	
					return redirect()->to(base_url() . 'Admin/manage_med_company');
				} else {
					$this->session->setTempdata('error', 'No file is uploaded. Please browse a file', 3);
					return view('Admin/medicines/add_med_company', $data);
				}
			} else {
				$data['validation'] = $this->validator;
			}
		}
		return view('Admin/medicines/add_med_company', $data);
	}


	public function manage_med_company(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'is_del'  => 0,

			];
			$data = [
				'med_category' => $this->adminModel->fetch_rec_by_args_arr('medicine_company', $args),
				'pager'     => $this->adminModel->pager
			];
			return view("Admin/medicines/manage_med_company", $data);
		}
	}

	///// searchig for the manage medicine company //
	public function search_medicines_company() {
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
	
		$keyword = $this->request->getVar('medicine_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
	
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('medicine_company', 'company_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}
	
		$data = [
			'med_category' => $this->adminModel->fetch_rec_by_args_with_status('medicine_company', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/medicines/manage_med_company', $data);
	} //function - Closed


	////fillter for the manage med company/// 
	public function filter_medicine_com($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_com') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_com') {
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
			//'status'        => 'Discharged'
			'is_del'        => 0
		];
		$data = [
			'med_category' => $this->adminModel->filter_rec_by_args_with_pagination('medicine_company', $order, $args),
			'pager'     => $this->adminModel->pager
		];
		
		return view("Admin/medicines/manage_med_company", $data);
	} // function closed


	/// status function for the medicine company /// 
	public function change_med_com_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status' => $status
		];

		$status = $this->adminModel->update_rec_by_args('medicine_company',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines company Status Change Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Update company Status', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_company');
	}  //function closed


	public function delete_med_com($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicine_company', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines company Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted company ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_company');
	}


	public function permanent_del_med_com($id) {
		if (!(session()->has('admin_session_uid'))) {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		
		$args = [ 'id' => $id ];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicine_company', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines company Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted company ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_company');
	}


	public function edit_med_company($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];
		$data['med_cam'] = $this->adminModel->fetch_rec_by_args('medicine_company', $args);

		// if($data['med_cat'] === false ) {
		// 	$this->session->setTempdata('error', 'Failed to update categorory!', 3);
		// 	return redirect()->to(base_url().'Admin/manage_med_category' );
		// }else { 
		return view('Admin/medicines/edit_med_company', $data);
		//} 

	}//function closed

	public function update_med_company($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];

		$data = [];
		$data['validation'] = null;
	
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'company_name'      => 'required|min_length[4]|max_length[50]',
				'company_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Company name is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],
			];
			$old_data = $this->adminModel->fetch_rec_by_args('medicine_company', $args);
			$img = $this->request->getFile('company_image');
			$doc_img = $img->getName();
			if($doc_img && $doc_img != '') {
				if(isset($old_data [0]->company_image)) {
					if(file_exists(FCPATH . 'uploads/medicine_company/' . $old_data[0]->company_image) && $old_data[0]->company_image != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/medicine_company/' . $old_data[0]->company_image);
					} //else - Not needed
				} //else - Not needed
			if ($img->isValid() &&  !$img->hasMoved()) {
				$this->rand_name = $img->getRandomName();
				#$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name); 
				$img->move(FCPATH . 'uploads/medicine_company', $this->rand_name);
				$doc_img = $img->getName();
				$this->user_data_arr = [
					'company_name'          =>  $this->request->getVar('company_name', FILTER_SANITIZE_STRING),
					'company_image'        =>  $doc_img,
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
			}
				else { //file is not moved
					$this->session->setTempdata('error', 'Sorry ! Failed to move uploaded media.', 3);
					echo $img->getErrorString() . " " . $img->getError(); 
					return redirect()->to(base_url() . 'Admin/edit_med_company/' . $id);
				} 
		} else{
				$this->user_data_arr = [
					'company_name'          =>  $this->request->getVar('company_name', FILTER_SANITIZE_STRING),
					//'category_image'        =>  $doc_img,
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
			}
				$status = $this->adminModel->update_rec_by_args('medicine_company', $args, $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulations, medicines category updated successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update medicines category. Please try again.', 3);
				}
		}
			else {
				$data['validation'] = $this->validator;
				return view('Admin/medicines/edit_med_company', $data);
			}
		
			//return redirect()->to(base_url().'/Admin/edit_med_cat/'.$id);
			return redirect()->to(base_url() . 'Admin/manage_med_company/');
	}

	public function filter_medicine_cat($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_cat') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_cat') {
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
			//'status'        => 'Discharged'
			'is_del'        => 0
		];
		$data = [
			'med_category' => $this->adminModel->filter_rec_by_args_with_pagination('medicine_category', $order, $args),
			'pager'     => $this->adminModel->pager
		];
		
		return view("Admin/medicines/manage_med_category", $data);
	}


	public function search_medicines() {
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
	
		$keyword = $this->request->getVar('medicine_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
	
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('medicine_category', 'category_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}
	
		$data = [
			'med_category' => $this->adminModel->fetch_rec_by_args_with_status('medicine_category', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/medicines/manage_med_category', $data);
	} //function - Closed

	
	public function change_manage_all_discharged_patient_status($id, $status){
		$admin_uid = session()->get('admin_session_uid');
		if (!isset($admin_uid) || $admin_uid == '') {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];

		$data = [
			'status'  => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $admin_uid,
		];
		//Custom - End
		$status = $this->adminModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! action done  successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to discharge. Please try again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_all_discharged_patient');
	}


	public function change_med_cat_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status' => $status
		];

		$status = $this->adminModel->update_rec_by_args('medicine_category',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines Category Status Change Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Update Category Status', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_category');
	}

	public function delete_med_cat($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicine_category', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines Category Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Category ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_category');
	}

	/* @params: Function for permanent delete medicine company name
	* @desc: Admin can soft delete medicine company name also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_med_cat($id) {
		if (!(session()->has('admin_session_uid'))) {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		
		$args = [ 'id' => $id ];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicine_category', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines Category Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Category ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_med_category');
	}

	public function edit_med_cat($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];
		$data['med_cat'] = $this->adminModel->fetch_rec_by_args('medicine_category', $args);

		// if($data['med_cat'] === false ) {
		// 	$this->session->setTempdata('error', 'Failed to update categorory!', 3);
		// 	return redirect()->to(base_url().'Admin/manage_med_category' );
		// }else { 
		return view('Admin/medicines/edit_med_cat', $data);
		//} 

	}

	public function update_med_category($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];

		$data = [];
		$data['validation'] = null;
	
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'company_name'      => 'required|min_length[4]|max_length[50]',
				'category_name'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Company name is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],

				//'category_image'    => 'uploaded[category_image]|max_size[category_image,1024]|is_image[category_image]|ext_in[category_image,png,jpg,jpeg, svg, gif]'
				// 'category_image' => [
				// 	'rules'     => 'uploaded[category_image]|max_size[category_image,' . ALLOW_MAX_UPLOAD .']|is_image[category_image]|mime_in[category_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[category_image,png,jpg,jpeg, svg, gif]',
				// 	'errors' => [
				// 		'uploaded'  => 'Medicines Image is mandatory.',
				// 		'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
				// 		'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
				// 		'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
				// 	],
				// ],
			];
			$old_data = $this->adminModel->fetch_rec_by_args('medicine_category', $args);
			$img = $this->request->getFile('category_image');
			$doc_img = $img->getName();
			if($doc_img && $doc_img != '') {
				if(isset($old_data [0]->category_image)) {
					if(file_exists(FCPATH . 'uploads/medicine_category/' . $old_data[0]->category_image) && $old_data[0]->category_image != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/medicine_category/' . $old_data[0]->category_image);
					} //else - Not needed
				} //else - Not needed
			if ($img->isValid() &&  !$img->hasMoved()) {
				$this->rand_name = $img->getRandomName();
				#$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name); 
				$img->move(FCPATH . 'uploads/medicine_category', $this->rand_name);
				$doc_img = $img->getName();
				$this->user_data_arr = [
					'category_name'          =>  $this->request->getVar('category_name', FILTER_SANITIZE_STRING),
					'category_image'        =>  $doc_img,
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
			}
				else { //file is not moved
					$this->session->setTempdata('error', 'Sorry ! Failed to move uploaded media.', 3);
					echo $img->getErrorString() . " " . $img->getError(); 
					return redirect()->to(base_url() . 'Admin/edit_med_cat/' . $id);
				} 
			} else{
				$this->user_data_arr = [
					'category_name'          =>  $this->request->getVar('category_name', FILTER_SANITIZE_STRING),
					//'category_image'        =>  $doc_img,
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
			}
				$status = $this->adminModel->update_rec_by_args('medicine_category', $args, $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulations, medicines category updated successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update medicines category. Please try again.', 3);
				}
			}
			else {
				$data['validation'] = $this->validator;
				return view('Admin/medicines/edit_med_cat', $data);
			}
		
			//return redirect()->to(base_url().'/Admin/edit_med_cat/'.$id);
			return redirect()->to(base_url() . 'Admin/manage_med_category/');
	}
	



	public function add_medicine(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = ['is_del' => 0];
		//$data['medicines'] = $this->commonForAllModel->fetch_allrecords_bypage_by_active('medicine_category');
		$data['medicine_category'] = $this->commonForAllModel->fetch_rec_by_args('medicine_category',$args);
		$data['medicine_company'] = $this->commonForAllModel->fetch_rec_by_args('medicine_company',$args);
		if($data['medicine_category'] === false) {
			$this->session->setTempdata('error', 'Sorry ! No medicine category is found.', 3);
		}
		if($data['medicine_company'] === false) {
			$this->session->setTempdata('error', 'Sorry ! No medicine company is found.', 3);
		}
		return view('Admin/medicines/add_medicine', $data);
	}


	public function upload_medicine(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null;

		//Fetch Medicine Category - START //used at end of function
		$data['medicines'] = $this->adminModel->fetch_all_records_by_active('medicine_category');
		if($data['medicines'] === false) {
			$this->session->setTempdata('error', 'Sorry ! No medicine category is found.', 3);
			return view("Admin/medicines/add_medicine", $data);
		} //Fetch Medicine Category - END

		if ($this->request->getMethod() == 'post') {
			$rules = [
				'med_company'      => 'required',
				'med_category'      => 'required',
				'med_price'        => 'required',
				'med_name'         => 'required',
				'med_exp_date' 	   => 'required',
				'batch_number'     =>  'required',
				'med_image' => [
					'rules'     => 'uploaded[med_image]|max_size[med_image,' . ALLOW_MAX_UPLOAD .']|is_image[med_image]|mime_in[med_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[med_image,png,jpg,jpeg, svg, gif]',
					'errors' => [
						'uploaded'  => 'Medicine Image is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
					],
				],
            ];
			if ($this->validate($rules)) {
				$img = $this->request->getFile('med_image');
				if ($img->isValid() &&  !$img->hasMoved()) {
					$this->rand_name = $img->getRandomName();
					$img->move(FCPATH . 'uploads/medicine_image', $this->rand_name);
					$med_img = $img->getName();	

					$this->user_data_arr = [
						'med_company'           =>  $this->request->getVar('med_company', FILTER_SANITIZE_STRING),
						'med_category'           => $this->request->getVar('med_category', FILTER_SANITIZE_STRING),
						'med_price'             =>  $this->request->getVar('med_price', FILTER_SANITIZE_STRING),
						'med_d_price'           =>  $this->request->getVar('med_d_price', FILTER_SANITIZE_STRING),
						'med_name'              =>  $this->request->getVar('med_name', FILTER_SANITIZE_STRING),
						'med_desc'             	=>  $this->request->getVar('med_dis', FILTER_SANITIZE_STRING),
						'med_stock'             =>  $this->request->getVar('med_stock', FILTER_SANITIZE_STRING),
						'batch_number'         	=>  $this->request->getVar('batch_number', FILTER_SANITIZE_STRING),
						'other_detail'          =>  $this->request->getVar('other_detail', FILTER_SANITIZE_STRING),
						'expiry_date'           =>  $this->request->getVar('med_exp_date', FILTER_SANITIZE_STRING),
						'med_image'             =>  $med_img,
						'status'                => 'Active',
						'created_at'            =>  date('Y-m-d h:i:s')
					];
					
					$status = $this->adminModel->Insertdata('medicines', $this->user_data_arr);
					if ($status === true) {
						$this->session->setTempdata('success', 'Congratulation! medicine added successfully!', 3);
						//return view("Admin/medicines/manage_medicine", $data);
						return redirect()->to(base_url() . 'Admin/manage_medicine');
					} 
					else { echo "Failed to add medicine";die;//Admin/add_medicine
						$this->session->setTempdata('error', 'Sorry! Failed to add medicine. Please try again.', 3);
						return redirect()->to(base_url() . 'Admin/add_medicine');
					}
				}
				else {// Failed to move the uploaded media
					$this->session->setTempdata('error', 'Failed to move the uploaded media into directory', 3);
					return redirect()->to(base_url() . 'Admin/add_medicine');
				}
			}
			else { 
				$data['validation'] = $this->validator; 
				return view("Admin/medicines/add_medicine", $data);
			}
		}
		else { 
			$this->session->setTempdata('error', 'Sorry ! Unexpected request method.', 3);
			return view("Admin/medicines/add_medicine", $data);
		}
	} //function - Closed


	public function manage_medicine() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'is_del'=> '0' ];
		$data = [
			'medicines'  => $this->adminModel->fetch_rec_by_args_with_status('medicines', $args),
			'pager'   => $this->adminModel->pager
		];
		
		if (!isset($data['medicines']) || $data['medicines'] == '') { //If no doctor found
			$this->session->setTempdata('error', 'Sorry ! No medicine found. Please add new medince first', 3);
			return redirect()->to(base_url() . "Admin/add_medicine");
		}
		//echo "<pre>";print_r($data);die;
		return view("Admin/medicines/manage_medicine", $data);
	} //function - Closed


	public function edit_medicine($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data['medicines'] = $this->adminModel->fetch_rec_by_args('medicines', $args);
		$data['medicine'] = $this->adminModel->fetch_all_records_by_active('medicine_category');
		return view('Admin/medicines/edit_medicine', $data);
	}


	public function update_medicines($id){
		if (!(session()->has('admin_session_uid'))) {
			 		return redirect()->to(base_url() . "/Login");
				}
		else{
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
	        
	        // $data['medicine'] = $this->medicine_model->fetch_all_records_by_active('medicine_category');
			$data['medicine'] = $this->adminModel->fetch_all_records_by_active('medicine_category');
			return redirect()->to(base_url().'Admin/manage_medicine');
			}
		}
		

	public function delete_medicine($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0,
                       
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicines', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines  Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Medicines ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medicine');
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
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id
		];
		$data = [
			'is_del'=> '1',
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('medicines', $args, $data);

		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines  Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Medicines ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medicine');
	}

	public function search_medicine(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
		
		$keyword = $this->request->getVar('medicine_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for medicine records with spaces removed
			$this->result_arr = $this->adminModel->search_records('medicines', 'med_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'medicines' => $this->adminModel->fetch_rec_by_args_with_status('medicines', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/medicines/manage_medicine', $data);
	}
	
	public function filter_medicine($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
			'is_del'=>0
		];
		$data = [
			'medicines' => $this->adminModel->filter_rec_by_args_with_pagination('medicines', $order ,$args),
			'pager'   => $this->adminModel->pager
		];
		return view("Admin/medicines/manage_medicine", $data);
	}


	public function change_medicine_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];

		$status = $this->adminModel->update_rec_by_args('medicines',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Medicines  Status Updated Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To update  Medicines Status ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medicine');
	}




	//---Account Section Script Start
	public function manage_doctor_account() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [
				'level'  => '3',
				'is_del' => 0
            ];
			$data = [
				'doctor' => $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args),
				'pager'     => $this->adminModel->pager
			];
			return view('Admin/Account/manage_doctor_account', $data);
		}
	} //function - Closed

	public function change_doctor_acc_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id,
			'is_del' => 0
		];

		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor Account Status Updated Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To update Doctor Account Status ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor_account');
	}

	public function delete_doctor_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);

		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Doctor Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor_account');
	}

/* @params: Function for permanent delete doctor fee
* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_doc_acc($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);

		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Doctor Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Doctor Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_doctor_account');
	}

	public function search_doctor_account(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		} 
		
		$keyword = $this->request->getVar('search_doctor', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'level' => 3,
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('register_all_users', 'username', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
		
		$data = [
			'doctor' => $this->adminModel->fetch_rec_by_args_with_status('register_all_users', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/Account/manage_doctor_account', $data);
	}
	
	public function filter_doctor_account($filter){
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	if ($filter == 'new_doctor') {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	} else if ($filter == 'old_doctor') {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'asc'
	// 		];
	// 	} else {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	}
	// 	$data = [
	// 		'doctor' => $this->adminModel->filter_rec_by_paginate('register_all_users', $order),
	// 		'pager'   => $this->adminModel->pager
	// 	];
	// 	return view('Admin/Account/manage_doctor_account', $data);
	// }

	if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
	}
	if ($filter == 'new_doctor') {
		$order = [
			'column_name'  => 'id',
			'order'        => 'desc'
		];
	} else if ($filter == 'old_doctor') {
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
	          'level'  => '3'
];
	$data = [
		'doctor' => $this->adminModel->filter_rec_by_args_with_pagination('register_all_users', $order ,$args),
		'pager'   => $this->adminModel->pager
	];
	
	return view('Admin/Account/manage_doctor_account', $data);
}
	public function manage_medical_acc(){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'level'  => '2',
				'is_del' => 0

			];
			$data = [
				'medical' => $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view('Admin/Account/manage_medical_acc', $data);
		}
	}
	public function change_medical_acc_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];

		$status = $this->adminModel->update_rec_by_args('register_all_users',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Status Updated Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Update Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medical_acc');
	}

	public function change_blood_acc_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];

		$status = $this->adminModel->update_rec_by_args('blood_bank_users',  $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Blood bank Status Updated Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Update Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_blood_acc');
	}

	public function delete_acc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0,
                        
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medical_acc');
	}
/* @params: Function for permanent delete doctor account
* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_acc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_medical_acc');
	}

	public function delete_bld_acc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
            'is_del' => 0,
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			//'is_del' => 1, 
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('blood_bank_users', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_blood_acc');
	}

/* @params: Function for permanent delete blood bank accountant account
* @desc: Admin can soft delete blood bank accountant account also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_bld_acc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('blood_bank_users', $args, $data);
		// $status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_blood_acc');
	}

	public function search_medical_account(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
	
		$keyword = $this->request->getVar('search_med_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
	
		$args = [
			'level' => '2'
			// 'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('register_all_users', 'username', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'medical' => $this->adminModel->fetch_rec_by_args_with_status('register_all_users', $args),
			'pager' => $this->adminModel->pager
		];
	
		return view('Admin/Account/manage_medical_acc', $data);
	}
	

	public function search_blood_account(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
		}  
		
		$keyword = $this->request->getVar('search_med_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'is_del' => 0
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('blood_bank_users', 'username', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'blood_accounts' => $this->adminModel->fetch_rec_by_args_with_status('blood_bank_users', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/Account/manage_blood_acc', $data);
	}
	
	



	public function filter_med_account($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_med_acc') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_med_acc') {
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
			//'status'  => 'InActive',
			'level'  => '2',
			'is_del' => 0

		];
		$data = [
			'medical' => $this->adminModel->filter_rec_by_args_with_pagination('register_all_users', $order ,$args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/Account/manage_medical_acc', $data);
	}

	

	public function filter_bld_account($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_med_acc') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_med_acc') {
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
			'blood_accounts' => $this->adminModel->filter_rec_by_args_with_pagination('blood_bank_users', $order ,$args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/Account/manage_blood_acc', $data);
}

	//---Account Section Script End 

	//Accountetn Verification Section Script Start 
	public function accountant_verification(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'status'  => 'InActive',
				'level'   => '2', //2 for Accountant
			];
			//$data['bld_bnk_admin'] = $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args);
			$data = [
				'accountant' => $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view("Admin/Account/accountant_verification", $data);
		}
	}

	public function frontdesk_verification(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				//'status'  => 'InActive',
				'level'   => '5', //5 for Frontdesk
			];
			//$data['bld_bnk_admin'] = $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args);
			$data = [
				'frontdesk' => $this->adminModel->fetch_rec_by_args_arr('blood_bank_users', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view("Admin/Account/frontdesk_verification", $data);
		}
	}

	public function change_accountant_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];
		$status = $this->accountant_model->update($args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Verify  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/accountant_verification');
	}

	// public function change_frontdesk_status($id, $status){
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$args = [
	// 		'id'  => $id
	// 	];
	// 	//echo "<pre>";print_r($status);die;
	// 	$data = [
	// 		'status'  => $status
	// 	];
	// 	$status = $this->accountant_model->update($args, $data);
	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! Frontdesk Account Verify  Successfully !', 3);
	// 	} else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to Updated Try Again ?', 3);
	// 	}
	// 	return redirect()->to(base_url() . '/Admin/frontdesk_verification');
	// }

	public function change_frontdesk_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('blood_bank_users',$args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Frontdesk Account Verify  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Updated Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/frontdesk_verification');
	}


	public function filter_accountant_verification($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_accountant') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_accountant') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		} else {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
			//	echo "<pre>";print_r($order);die;
		}
		$args = [
			'status'        => 'InActive',
			'level'         => '2'

		];
		$data = [
			'accountant' => $this->adminModel->filter_rec_by_args_with_pagination('register_all_users', $order, $args),
			'pager'     => $this->adminModel->pager
		];
		
		return view("Admin/Account/accountant_verification", $data);
	}

	public function filter_frontdesk_verification($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			//'status'        => 'InActive',
			'level'         => '5'

		];

		if ($filter == 'new_frontdesk') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} 
		else if ($filter == 'old_frontdesk') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}
		else if ($filter == 'InActive') {
			$order = [
				'column_name'  => 'InActive',
				'order'        => 'asc'
			];
		}
		else if ($filter == 'Active') {
			$order = [
				'column_name'  => 'InActive',
				'order'        => 'asc'
			];

			$args = [
				'status'        => 'Active',
				'level'         => '5'

			];
		} 
		else {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];

			$args = [
				//'status'        => 'Active',
				'level'         => '5'
			];
		}
		
		
		$data = [
			'frontdesk' => $this->commonForAllModel->filter_rec_by_args_with_pagination('blood_bank_users', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		
		//return view("Admin/Frontdesk/frontdesk_verification", $data); //This file doesn't exist
		return view("Admin/Account/frontdesk_verification", $data);
	}

	public function delete_accountant_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
            'is_del' => 0,
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Bank Admin Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/accountant_verification');
	}

	
	public function delete_frontdesk_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0,
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('blood_bank_users', $args, $data);
		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Frontdesk  Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/frontdesk_verification');
	}

	//Doctor Account Verification
	public function doctor_email_register(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$data['request_email'] = $this->commonForAllModel->fetch_allrecords_bypage('doc_req_email');
			return view('Admin/Account/doctor_email_register', $data);
		}
	}
	

	public function doctor_verification(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [
				'status'  => 'InActive',
				'level'   => '3', //3 for Doctor
			];
			$data = [
				'doctors' => $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args),
				'pager'     => $this->adminModel->pager
			];
			return view("Admin/Account/doctor_verification", $data);
		}
	} //fuction - Closed


   /* @params:
	* @desc: Registered Doctor verification or approval by Admin
	* @return:
	* @author: Neoarks Team 
	* @date:
	* @modify:
	*/
	public function change_doc_acc_status($dr_id, $status) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		
		$this->doctor_id  = $dr_id; 
		$this->updt_dt_arr = ['status'  => $status];
		if ($this->request->getMethod() == 'get') {

			$args = ['id'  => $this->doctor_id];
			
			$status = false; //Just for addressing notices

			$this->data['doctor_info'] = $this->adminModel->fetch_rec_by_args('register_all_users', $args);	
			if($this->data['doctor_info'] === false) {
				$this->session->setTempdata('error', 'Doctor is not found.', 3);
				return redirect()->to(base_url() . '/Admin/doctor_verification');
			}
			if(!isset($this->data['doctor_info'][0]->email) || $this->data['doctor_info'][0]->email == '') {
				$this->session->setTempdata('error', 'Doctor email does not exist', 3);
				return redirect()->to(base_url() . '/Admin/doctor_verification');
			}
			//Verify - is doctor already existing 
			$this->is_zero_record = $this->commonForAllModel->is_zero_record('doctor', 'doctor_email', $this->data['doctor_info'][0]->email);
			if($this->is_zero_record === false) {
				$this->session->setTempdata('error', 'Record is already existing.', 3);
				return redirect()->to(base_url().'/Admin/doctor_verification');
			}
			else {
				$this->session->remove('error');
				$this->insrt_dt_arr = [
					'ref_id'		=> $this->doctor_id,
					'uid'			=> $this->data['doctor_info'][0]->uid,
					'gender'		=> $this->data['doctor_info'][0]->gender,
					'doctor_name'	=> $this->data['doctor_info'][0]->username,
					'doctor_phone'	=> $this->data['doctor_info'][0]->mobile,
					'doctor_email'	=> $this->data['doctor_info'][0]->email,
					'dr_source'		=> "Self Registerd",
					'login_acc'		=> 1, //1: Looged in account exist, 0: NOT logged-in account exist
					'status'		=> "Active",
					'is_del'		=> 0,
					'profile_pic'	=> $this->data['doctor_info'][0]->profile_pic,
					'created_at'	=> date('Y-m-d H:i:s'),
					'created_by'	=> $this->admin_uid,
				];
				$this->last_inst_id = $this->commonForAllModel->return_inserted_id_n_update_for_refid('doctor', $this->insrt_dt_arr, 'register_all_users', $args, $this->updt_dt_arr);
				if ((int) $this->last_inst_id > 0 ) {
					$this->session->setTempdata('success', 'Congratulation ! Doctor account verify  successfully !', 3);
				} 
				else if ($this->last_inst_id === false) {
					$this->session->setTempdata('error', 'Sorry ! Failed to insert or update records.', 3);
				}
				else {
					$this->session->setTempdata('error', 'Unexpected last inserted ID', 3);
				}
			} //else - loop closed
			// return redirect()->to(base_url() . '/Admin/manage_doctor');
			return redirect()->to(base_url() . '/Admin/doctor_verification');
		}
		else {
			$this->session->setTempdata('error', 'Unexpected request method.', 3);
			return redirect()->to(current_url() . '/Admin/doctor_verification');
		}
	}//function - Closed



	public function delete_doc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0,
		];
		$data = [
			'status' => '1', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Account Deleted  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/doctor_verification');
	}

/* @params: Function for permanent delete doctor account
* @desc: Admin can soft delete doctor's account also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_doc_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
		];
		$data = [
			'is_del' => 1,
			'status' => '1', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Account Deleted  Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/doctor_verification');
	}


	public function filter_doctor_verification($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_doc_account') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_doc_account') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		} else {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
			//echo "<pre>";print_r($order);die;
		}
		$args = [
			'status'        => 'InActive',
			'level'         => '3'     //For Doctor

		];
		$data = [
			'doctors' => $this->adminModel->filter_rec_by_args_with_pagination('register_all_users', $order, $args),
			'pager'     => $this->adminModel->pager
		];
		
		return view("Admin/Account/doctor_verification", $data);
	}

	/*@param: 
	* @desc: Get all patients list having status: `Appointment`, `Active` & `Discharged`
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: May 12th, 2023
	* @modify:
	* @reference: Admin/today_appointments()
	* @copyrights: Neoark Software Team
	*/
	public function all_appointments() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else { $args = [ 'is_del' => 0 ];
		$data = [
			'all_appointments' => $this->adminModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager'     => $this->adminModel->pager
		];
		return view("Admin/Account/all_appointments", $data);
		}
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
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$args = [
			//'status' => 1,
			'is_del' => 0,
			//'doctor_id' => $doctor_id,
			//1: Appointment
			'booking_date' => date('Y-m-d')
		];
		$data = [
			'today_appointment'  => $this->adminModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager' => $this->adminModel->pager
		];
		//echo "<pre>";print_r($data);die;
		return view('Admin/Account/today_appointment', $data);
	} //Function - Closed


	/*@param: Delete Dr. appointment 
	* @desc: 
	* @use: 
	* @author: Neoark Team
	* @date: 
	* @modify:
	* @reference: 
	* @copyrights: Neoark Software Team
	*/
	public function delete_today_appointment($id){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$status = $this->adminModel->delete_records('booked_doctor_appointment', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Delete Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/today_appointments');
	}

	public function delete_cancelled_appointment($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Accountant Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Fail Unable To Deleted Account  ', 3);
		}
		return redirect()->to(base_url() . '/Admin/canceled_appointments');
	}

	/*@param: Delete Dr. appointment from Dashboard
	* @desc: Since it need to redirect on dashboard, therefore written another function wrt delete_appointment
	* @use: 
	* @author: Neoark Team
	* @date: May 12th, 2023
	* @modify:
	* @reference: Admin/delete_appointment function
	* @copyrights: Neoark Software Team
	*/
	public function delete_appointment_dsbrd($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$status = $this->adminModel->delete_records('booked_doctor_appointment', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Delete Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/home');
	}

	/* @params: Function for permanent delete all appointment
	* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	// public function permanent_del_all_apmnt($id) {
	// 	$this->admin_uid = session()->get('admin_session_uid');
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url()."/Admin");
	// 	}
	// 	$args = [
	// 		'id'  =>  $id,
	// 		// 'is_del' => 1
	// 	];
	// 	$data = [
	// 		'is_del' => 1, //0: Not Del, 1: Permanent Deleted
	// 		'status' => 2, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other	
	// 		'updated_at' => date('Y-d-m h:i:s'),
	// 		'updated_by' => $this->admin_uid
	// 	];

	// 	
	// 	// $status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
	// 	$status = $this->adminModel->test_update_rec_by_args('booked_doctor_appointment',  $args, $data);
	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! Record Deleted Successfully !', 3);
	// 	} else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
	// 	}
	// 	
	// 	return redirect()->to(base_url() . 'Admin/all_appointments');
	// } //function - Closed


	public function permanent_del_all_apmnt($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 1, //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/all_appointments');
	}

			

	/*@param: 
	* @desc:
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: 
	* @modify:
	* @reference: Admin/today_appointments()
	* @copyrights: Neoark Software Team
	*/

	//*******************Attend Patient - START ***********************
	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	public function add_fee($apmt_id, $status, $pid, $puid, $serial, $dr_id) {
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->apmt_id = (int) $apmt_id; //appointment ID
			$this->status = (int) $status; //Status
			$this->pid = (int) $pid; //Patient ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 
			$data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function

			$data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices

			$dr_args = [ 'ref_id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $this->apmt_id ]; 
			$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);

			$apmt_pay_args = [ 'apmt_id'  => $this->apmt_id ]; 
			$data['apmt_paymnt'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $apmt_pay_args);
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
				return view('Admin/payments/add_fee', $data); // Pass the data to the view
			}
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Admin/payments/add_fee', $data); // Pass the data to the view
	} //function - Closed


	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	
	public function save_fee($patient_id, $pid, $apmtid, $puid, $serial, $payid) {
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}

		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //Patient ID
		$this->apmt_id 	  = (int) $apmtid; //appointment ID
		$this->pid 	= (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		$this->pay_chrg_id =  (int) $payid;
		
		$this->data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$this->apmt_args = ['id' 	=> $this->apmt_id];
		$this->data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->apmt_args);
         
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

		if(isset($this->data['apmt_patient'][0]->doctor_id) && $this->data['apmt_patient'][0]->doctor_id != '') { 
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
		$this->data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $this->dr_args);

		if ($this->request->getMethod() == 'get') {
			return view('Admin/payments/add_fee', $this->data); //Reunder Fee form
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
					'updated_by' => $this->admin_uid,
				];
				$this->appointment_fee = $this->request->getVar('appointment_fee', FILTER_SANITIZE_STRING);

					$this->doctor_fee = $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING);
					//var_dump($this->doctor_id);die;
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
						'created_by'	=>  $this->admin_uid,
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
						'created_by'	=> $this->admin_uid,
					]; 

					$this->apmt_args = ['id' =>	$this->apmt_id];
					$this->last_inst_id = $this->commonForAllModel->insert_two_n_update_one_tbl('treatment_expenses_history', $this->expnc_chrg_arr, 'patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
					
					$this->data['apmt_paymnt_payid'] = $this->last_inst_id; //used in next call - perhapse
					if ($this->last_inst_id === 2) { //Failed to update appointment table
						return redirect()->to(base_url().'Admin/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					}
					else if ($this->last_inst_id === 3) { //Failed to insert into patient pay charges table
						return redirect()->to(base_url().'Admin/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else if ($this->last_inst_id === 4) { //Failed to insert treatment expences table 
						return redirect()->to(base_url().'Admin/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else { //success:
						return redirect()->to(base_url() . 'Admin/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = true; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Admin/add_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
		$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Fee Paid', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->admin_uid,
				];
			$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			if(!isset($data['apmt_patient'][0]->doctor_id)) {
				$this->session->setTempdata('error', 'Doctor id is not found in appointment table', 3);
				return view('Admin/payments/generate_admission_bill', $data);

			}
			if($data['apmt_patient'][0]->doctor_id == 0 || $data['apmt_patient'][0]->doctor_id == '') {
				$this->session->setTempdata('error', 'Doctor id is blank or zero in appointment table', 3);
				return view('Admin/payments/generate_admission_bill', $data);
			}
			//$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
			$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Admin/payments/generate_initial_fee_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			//var_dump($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);die;
			//$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
			$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Admin/payments/generate_initial_fee_bill', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Admin/payments/generate_initial_fee_bill', $data);
		}
	} //function - Closed

	
	//public function change_all_appointments_status($apmtid, $status, $pid, $puid, $serial) {
	public function change_all_appointments_status($id, $status, $pid, $puid, $serial, $dr_id){
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->pid = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		$this->dr_id = $dr_id; //Doctor ID
		
		if (!(session()->has('admin_session_uid'))) { 
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		} 

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table
		$this->data = [
				'status' => $status,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->admin_uid,
			];

		if ($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				// $this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				} 
				else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->pid > 0) { //pid: patient_login tbl `id`
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//var_dump($this->pid);die;
				//$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) { //var_dump($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);die;
				/***** Get Patient detail thru `Appointment ID` - START *****/
				//$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Unexpected or Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			}
		} 
		else { //Update appointments only
			$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			//$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed

	// function patient_details_with_puid($data, $args, $admin_uid, $pid, $puid, $apmt, $serial){
		function patient_details_with_puid($data, $args, $admin_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->admin_uid = $admin_uid;
		$this->patient_id = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = (int) $serial;
		$this->dr_id = $dr_id; //Doctor ID

		$this->args_neo = ['puid'  => $this->puid];
		$this->patient_rslt = $this->adminModel->fetch_rec_by_args('patients', $this->args_neo);
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
				'created_by'		=> $this->admin_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			// echo "<pre>"; print_r($this->insrt_data_arr);die;
			return $this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/*****  Get Patient detail thru `paitient ID` - START *****/
		else if ($this->patient_id > 0) { //Case - Take FIRST appointment - by loggedin Patient 
			$this->args_neo = ['pid'  => $this->patient_id]; //Patient loggedin-ID
			$this->patient_rslt = $this->adminModel->fetch_rec_by_args('patients', $this->args_neo);
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
					'doctor_id'			=> $this->dr_id,
					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
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
					'created_by'		=> $this->admin_uid, //need uid
					//'patient_image'	=> $this->patient_rslt['0']->id
				);
				// echo "second";
				// echo "<pre>"; print_r($this->insrt_data_arr);die;
				return $this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
			} 
			else if ($this->apmt_id != ''  && $this->apmt_id > 0) { //MOI?? //FIRST time Appointment - Loggedin Patient
				$this->args_neo = ['id' => $this->apmt_id];
				$this->patient_rslt = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
				if ($this->patient_rslt != false) { //Generate PUID
					//Generate puid
					$this->puid = 0; //Just for addressing notices
					if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
						$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
						return redirect()->to(base_url() . '/Admin/all_appointments');
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
						'created_by'		=> $this->admin_uid, //need uid
						//'deleted_at'		=> 
					);
					// 	echo "2nd last";
					// echo "<pre>";print_r($this->insrt_data_arr);die;
					return $this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
				} else {
					$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
					$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous!, patient info is not found', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
			}
		}
		/***** Get Patient detail thru `paitient ID` - END *****/
		else {
			$this->session->setTempdata('error', 'Puid! must exist patients table', 3);
			return redirect()->to(base_url() . '/Admin/all_appointments');
		}
	} //function - Closed


	//function patient_details_with_pid($data, $args, $admin_uid, $pid, $puid, $apmt, $serial){
		function patient_details_with_pid($data, $args, $admin_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->admin_uid = $admin_uid;
		$this->pid = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->args_neo = ['pid' => $this->pid];

		$this->patient_rslt = $this->adminModel->fetch_rec_by_args('patients', $this->args_neo);
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
				'created_by'		=> $this->admin_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			return $this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/* Get Patient detail thru `Appointment ID` - START 
		 * @desc: Loggedin - NEW Patient appointment Addeded by Admin & Admin
		 * 
		 */ else if ($this->apmt_id != ''  && $this->apmt_id > 0) {
			$this->args_neo = ['id' => $this->apmt_id];
			$this->patient_rslt = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
			if ($this->patient_rslt != false) { //Generate PUID
				//Generate puid
				$this->puid = 0; //Just for addressing notices
				if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
					$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
					return redirect()->to(base_url() . '/Admin/all_appointments');
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
					'created_by'		=> $this->admin_uid, //need uid
					//'deleted_at'		=> 
				);
				// 	echo "2nd last";
				// echo "<pre>";print_r($this->insrt_data_arr);die;
				return $this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
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
	//function patient_details_with_apmt_id($data, $args, $admin_uid, $pid, $puid, $apmt, $serial) {
		function patient_details_with_apmt_id($data, $args, $admin_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->updt_data_arr = $data;
		$this->args = $args; //$this->args['id'] = $this->apmt_id;
		$this->admin_uid = $admin_uid;
		$this->puid = $puid;
		$this->apmt_id = $apmt;
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->patient_rslt = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->args);
		if ($this->patient_rslt != false) {
			//Generate puid
			$this->puid = 0; //Just for addressing notices
			if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
				return redirect()->to(base_url() . '/Admin/all_appointments');
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
				'doctor_id'			=> $this->patient_rslt['0']->doctor_id, //$this->dr_id,
				'patient_address'	=> $this->patient_rslt['0']->address,
				'doctor_id'			=> $this->patient_rslt['0']->doctor_id,
				'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
				'apmt_id'			=> $this->patient_rslt['0']->id,			
				'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,			
				'patient_room'		=> $this->patient_rslt['0']->department, //room ?
				'patient_email'		=> $this->patient_rslt['0']->patient_email,
				'status'			=> 'Attended',
				'level'				=>	3, //3: Doctor - MOI for admin level?
				'created_at'		=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->admin_uid,			
			);
			//Non- loggedin Patient appointment Addeded by Admin & Admin
			//echo "<pre>";print_r($this->insrt_data_arr);die;
			$this->updt_rslt = $this->adminModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
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
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;
			case '':
				$this->session->setTempdata('error', 'Blank response, failed to udpate appointment', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;
			case 'true':
				$this->session->setTempdata('success', 'Congratulation ! Appointment Status Change Successfully !', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;

			case 2:
				$this->session->setTempdata('error', 'Anonymus user, Patient record is not found in db', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;

			case 3:
				$this->session->setTempdata('error', 'Revisited Patients info insertion failed', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;

			case 4:
				$this->session->setTempdata('error', 'Failed to book doctor appointment', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;

			default:
				$this->session->setTempdata('error', 'Sorry ! Unable to Change Try Again ?', 3);
				return redirect()->to(base_url() . 'Admin/all_appointments');
				break;
		}
	} //function - Closed
	//*******************Attend Patient - END ***********************

	public function change_cancelled_appointments_status($id, $status, $pid, $puid, $serial){
		$this->apmt_id = (int) $id; //appointment ID
		$this->status  = (int) $status; //Status
		$this->patient_id = (int)$pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 

		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$this->admin_uid = session()->get('admin_session_uid');

		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Admin");
		} //else { $this->admin_uid =  $this->admin_uid_arr['uid']; }

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

		$this->data = [
			'status'  => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->admin_uid,
		];

		if ($this->status === 0) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->patient_id > 0) {
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->args['id']  = $this->patient_id;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation @! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) {
				/***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation, Appointment status has changed successfully', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/canceled_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Admin/canceled_appointments');
			}
		} else { //Update appointments only
			$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation 2! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Admin/canceled_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Admin/canceled_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed
	/*@param: 
	* @desc:
	* @use: For Admin section for showing all patients status
	* @author: Neoark Team
	* @date: 
	* @modify:
	* @reference: Admin/today_appointments()
	* @copyrights: Neoark Software Team
	*/
	public function change_Appointment_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $args,  $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Appointment Status Change Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Change Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/today_appointments');
	}


	/*@param: Change Patient status from Dashboard
	* @desc: Since it need to redirect on dashboard, therefore written another function wrt existing function change_Appointment_status
	* @use: 
	* @author: Neoark Team
	* @date: May 12th, 2023
	* @modify:
	* @reference: Admin/change_Appointment_status function
	* @copyrights: Neoark Software Team
	*/
	// public function change_appointment_status_dsbrd($id, $status){
	//if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url()."Login");
	// 	}
	// 	$args = [
	// 		'id'  => $id
	// 	];
	// 	$data = [
	// 		'status'  => $status
	// 	];
	// 	$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $args, $data);
	// 	if ($status == true) {
	// 		$this->session->setTempdata('success', 'Congratulation ! appointment has changed successfully !', 3);
	// 	}else{
	// 		$this->session->setTempdata('error', 'Sorry ! unable to change appointment. Please try again.', 3);
	// 	}
	// 	return redirect()->to(base_url().'Admin/home');
	// }

	public function change_appointment_status_dsbrd($id, $status, $pid, $puid, $serial){
		//$this->change_all_appointments_status($id, $status, $pid, $puid, $serial);
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->patient_id = (int)$pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 

		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$this->admin_uid = session()->get('admin_session_uid');

		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Admin");
		} //else { $this->admin_uid =  $this->admin_uid_arr['uid']; }

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

		$this->data = [
			'status'  => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->admin_uid,
		];

		if ($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation 1! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/home');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/home');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->patient_id > 0) {
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->args['id']  = $this->patient_id;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation 2! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/home');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/home');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) {
				/***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation 3!, Appointment status has changed successfully', 3);
					return redirect()->to(base_url() . '/Admin/home');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/home');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Admin/home');
			}
		} else { //Update appointments only
			$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Admin/home');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Admin/home');
			}
		} //else loop - closed
	} //Funtion - Closed
	
/* @params: Function for permanent delete doctor fee
* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/

public function permanent_del_all_Apmnt_dsbd($id){
// 	$this->admin_uid = session()->get('admin_session_uid');
// 	if (!isset($this->admin_uid) || $this->admin_uid == '') {
// 		$this->session->setTempdata('error', 'Admin UID is missing !', 3);
// 		return redirect()->to(base_url() . "/Login");
// 	}
// 	$args = [
// 		'id'  =>  $id
// 	];
// 	$data = [
// 		'is_del' => 1,
// 		'status' => 1, //0: Non deleted, 1: Deleted
// 		'updated_at' => date('Y-d-m h:i:s'),
// 		'updated_by' => $this->admin_uid
// 	];
// 	$status = $this->adminModel->update_rec_by_args('department',  $args, $data);
// 	if ($status == true) {
// 		$this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
// 	} else {
// 		$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
// 	}
// 	return redirect()->to(base_url() . 'Admin/home');
// }
$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 1, //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/home');
	}


	// public function filter_today_appointment($filter){
	// 	
	//if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url()."/Login");
	// 	}
	// 	if ($filter == 'new_appointment') {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	}else if ($filter == 'old_appointment') {
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'asc'
	// 		];
	// 	}else{
	// 		$order = [
	// 			'column_name'  => 'id',
	// 			'order'        => 'desc'
	// 		];
	// 	}
	// 	$data['today_apmnt'] = $this->adminModel->filter_rec_by_args('booked_doctor_appointment', $order);
	// 	return view('Admin/Account/today_appointment', $data);
	// } 
	public function filter_today_appointment($filter){
		if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
		}
	if ($filter == 'new_patient') {
		$order = [
			'column_name' => 'id',
			'order' => 'desc'
		];
	} else if ($filter == 'old_patient') {
		$order = [
			'column_name' => 'id',
			'order' => 'asc'
		];
	} else {
		$order = [
			'column_name' => 'id',
			'order' => 'desc'
		];
	}
	$args = [
		'is_del'=>0,
		//'status' => 1,
		//0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
		'booking_date' => date('Y-m-d')
	];
	$data = [
		'today_appointment' => $this->adminModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
		'pager' => $this->adminModel->pager
	];
	
	return view('Admin/Account/today_appointment', $data);
} //Function - Closed

	public function filter_appointment($filter){

	if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
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
		'all_appointments' => $this->adminModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order ,$args),
		'pager'   => $this->adminModel->pager
	];
	
	return view('Admin/Account/all_appointments', $data);
}

	public function doctor_discharge_appointments(){ //seems longer needed
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'status'      => 3 //0: Cancelled, 1: Appointment, 2: Deleted, 3: Waiting, 4: Attended, 5: Patient Not Reached, 6: Other
		];
		$data['disc_apmnt'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if ($data['disc_apmnt'] == false) {
			$this->session->setTempdata('error', 'No record found!', 3);
		}
		return view('Admin/Account/discharge_appointments', $data);
	}

	public function canceled_appointments(){
	if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
	} else {
		$args = [
			'status'  => 0,//0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
			'is_del'  => 0,

		];
		$data = [
			'cancelled_apmt' => $this->adminModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager'     => $this->adminModel->pager
		];
		return view("Admin/Account/cancelled_appointments", $data);}
	}

	
	//Appointment Sction Script End	
	public function search_canceled_appointments(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url() . "/Login");
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
			$this->result_arr = $this->adminModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
	
		$data = [
			'cancelled_apmt' => $this->adminModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->adminModel->pager
		];
		
		return view('Admin/Account/cancelled_appointments', $data);
	}
	


	public function filter_del_appointments($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
			'cancelled_apmt' => $this->adminModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
			'pager'   => $this->adminModel->pager
		];
		
		return view('Admin/Account/cancelled_appointments', $data);
	}

	public function filter_appointment_patient($filter){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
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

		$args = [
			'status'        => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
		];

		$data = [
			//'disc_apmnt' => $this->adminModel->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
			'disc_apmnt' => $this->commonForAllModel->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
			
		];
		if ($data['disc_apmnt'] == false) {
			$this->session->setTempdata('error', 'No record found!', 3);
			return redirect()->to(base_url() . "Admin/doctor_discharge_appointments");
		}
		//return view('Doctor/today_appointments', $data);
		return view('Admin/Account/discharge_appointments', $data);
	}


	//Medical Section Script Start
	public function today_medical_cus_report(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				'order_date'   => date('Y-m-d h:i:s')
			];
			$data['sales'] = $this->adminModel->fetch_rec_by_args('order_product', $args);
			return view('Admin/medicines/todays_sale_records', $data);
		}
	}

	public function todays_sale_records(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}else{
			$args = [
				'is_del' => '0',
				'order_date'   => date('Y-m-d')
			];
			//$data['sales'] = $this->adminModel->fetch_rec_by_args('order_product',$args);
			$data = [
				// 'sales'  => $this->commonForAllModel->fetch_allrecords_bypage('order_product'),
				'sales'  => $this->adminModel->fetch_rec_by_status_with_pagination('order_product', $args),
				'pager'   => $this->adminModel->pager
		];
			return view('Admin/medicines/todays_sale_records', $data);
		}
	}

	public function search_sales(){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				'order_date>='  => $this->request->getVar('start_date', FILTER_SANITIZE_STRING),
				'order_date<='  => $this->request->getVar('last_date', FILTER_SANITIZE_STRING),
			];
			$data['sales'] = $this->adminModel->fetch_rec_by_args('order_product', $args);
			return view('Admin/medicines/search_sales', $data);
		}
	}

	public function all_sale_reports(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [];
		$data = [
			'sales'  => $this->adminModel->fetch_rec_by_args_with_status('order_product', $args),
			'pager'   => $this->adminModel->pager
		];
		// if (!isset($data['sales']) || $data['sales'] == '') { //If no doctor found
		// 	$this->session->setTempdata('error', 'Sorry ! No medicine found. Please add new medince first', 3);
		// 	return redirect()->to(base_url() . "Admin/home");
		// }
		return view('Admin/medicines/all_sale_reports', $data);
		//Medical Section Script End
	}

	//Front page Section Query 
	public function add_slider_image() {	
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else { return view('Admin/Account/add_slider_image'); }
	}
	
	public function publish_slider_image() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->data = []; //Just for addressing notices
		$this->data['validation'] = null; //Just for addressing notices
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'image_title'  => [
					'rules'     => 'required|min_length[4]|max_length[50]',
					'errors'    => [
						'required' => 'Image title is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
					],
				],

					'image_gallery' => [
					'rules'     => 'uploaded[image_gallery]|max_size[image_gallery,' . ALLOW_MAX_UPLOAD .']|is_image[image_gallery]|mime_in[image_gallery,image/jpeg,image/png,image/svg, image/gif)]|ext_in[image_gallery,png,jpg,jpeg,svg]',
					'errors' => [
						'uploaded'  => 'Image is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image .',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg).'
					],
				],
			];
        	if ($this->validate($rules)) {
				$img = $this->request->getFile('image_gallery');
				$allowedFormats = ['png', 'jpg', 'jpeg', 'svg', 'gif']; // Define the allowed file formats
				// Check if the uploaded file is valid
				if ($img->isValid() && !$img->hasMoved()) { 
					// Get the file extension
					$fileExtension = $img->getClientExtension();
					
					// Check if the file extension is in the allowed formats
					if (in_array(strtolower($fileExtension), $allowedFormats)) {
						$this->rand_name = $img->getRandomName();
						
						// Move the uploaded file to the destination folder
						$img->move(FCPATH . 'uploads/frontend/slider', $this->rand_name);

						$doc_img = $img->getName();

						$this->user_data_arr = [
							'image_title'        => $this->request->getVar('image_title', FILTER_SANITIZE_STRING),
							'website_link'       => $this->request->getVar('website_link', FILTER_SANITIZE_STRING),
							'image_discription'  => $this->request->getVar('image_discription', FILTER_SANITIZE_STRING),
							'image_gallery'      => $doc_img,
							'status'             => 'Active',
							'created_at'         => date('Y-m-d h:i:s'),
						];

						$status = $this->adminModel->Insertdata('slider_image', $this->user_data_arr);

						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation! Slider Image Uploaded Successfully!', 3);
						} else {
							$this->session->setTempdata('error', 'Sorry! Unable to Upload. Try Again?', 3);
						}

						return redirect()->to(base_url() . '/Admin/add_slider_image');
					} else {
						// Display an error message if the file format is not allowed
						echo 'Invalid file format. Allowed formats are: ' . implode(', ', $allowedFormats);
					}
				} else {
					// Display an error message if the file is not valid
					echo $img->getErrorString() . " " . $img->getError();
				}
			}
			else {
				$this->data['validation'] = $this->validator; //for showing error on view
				return view("Admin/Account/add_slider_image", $this->data); 
			}
		}
		else {
			return view("Admin/Account/add_slider_image", $this->data); 
		}
	} //function - Closed

	
	public function manage_slider(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'  => 'InActive',
				'is_del' => 0
			];
			$data = [
				'slider' => $this->adminModel->fetch_rec_by_args_arr('slider_image', $args),
				'pager'     => $this->adminModel->pager
			];
			
			return view("Admin/Account/manage_slider", $data);
		}
	}

	public function edit_slider_image($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];
		$data['slider']  = $this->adminModel->fetch_rec_by_args('slider_image', $args);
		return view('Admin/Account/edit_slider_image', $data);
	} //Function - Closed


	public function update_slider_image($id) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		$this->data = []; //Just for addressing notices
		$this->data['validation'] = null; //Just for addressing notices
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'image_title'  => [
						'rules'     => 'required',
						'errors'    => [
							'required' => 'Image title is mandatory.'
						],
					],
				// 'image_gallery' => [
				// 	'rules'     => 'uploaded[image_gallery]|max_size[image_gallery,' . ALLOW_MAX_UPLOAD .']|is_image[image_gallery]|mime_in[image_gallery,image/jpeg,image/png,image/svg, image/gif)]|ext_in[image_gallery,png,jpg,jpeg,svg]',
				// 	'errors' => [
				// 		'uploaded'  => 'Image is mandatory.',
				// 		'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
				// 		'mime_in'   => 'The uploaded file must be a valid image .',
				// 		'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg).'
				// 	],
				// ],
			];
			if ($this->validate($rules)) {
				$args = [ 'id'  => $id ];
				$old_data = $this->adminModel->fetch_rec_by_args('slider_image', $args);
				$img = $this->request->getFile('image_gallery');
				$doc_img = $img->getName();
				if($doc_img) {
					//Delete old  image
					if(isset($old_data[0]->image_gallery)) {
						if($old_data[0]->image_gallery != '' && file_exists(FCPATH . 'uploads/frontend/slider/' . $old_data[0]->image_gallery)) {
							@unlink(FCPATH . 'uploads/frontend/slider/' . $old_data[0]->image_gallery);
						} //else - NOT needed
					} //else - NOT needed
					if ($img->isValid() &&  !$img->hasMoved()) {
						$this->rand_name = $img->getRandomName();
						$img->move(FCPATH . 'uploads/frontend/slider/', $this->rand_name);
						$data = [
							'image_title'          	=>  $this->request->getVar('image_title', FILTER_SANITIZE_STRING),
							'website_link'          =>  $this->request->getVar('website_link', FILTER_SANITIZE_STRING),
							'image_discription'		=>  $this->request->getVar('image_discription', FILTER_SANITIZE_STRING),
							'image_gallery'        	=>  $this->rand_name,
							'status'                => 'Active',
							'created_at'            =>  date('Y-m-d h:i:s')
						];
					}
					else {
						$this->session->setTempdata('error', 'Sorry !, Failed to move uploaded media', 3);
					}
				}
				else {
					$data = [
						'image_title'          	=>  $this->request->getVar('image_title', FILTER_SANITIZE_STRING),
						'website_link'          =>  $this->request->getVar('website_link', FILTER_SANITIZE_STRING),
						'image_discription'		=>  $this->request->getVar('image_discription', FILTER_SANITIZE_STRING),
						//'image_gallery'        	=>  $this->rand_name,
						'status'                => 'Active',
						'updated_at'            =>  date('Y-m-d h:i:s'),
						'updated_by'            =>  date('Y-m-d h:i:s'),
					];
				} 	
				$status = $this->adminModel->update_rec_by_args('slider_image', $args,  $data);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Slider Image Updated Successfully !', 3);
				} 
				else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Update Image Try Again ?', 3);
				}
			}
			else {
				$this->data['validation'] = $this->validator;
				$args = [ 'id'  => $id, ];
				$this->data['slider'] = $this->adminModel->fetch_rec_by_args('slider_image', $args);
				// $args = [
				// 	'status' => 'Active'
				// ];
				return view("Admin/Account/edit_slider_image" , $this->data); 
			}
			return redirect()->to(base_url() . '/Admin/edit_slider_image/' . $id);
		}
		else {
			$this->session->setTempdata('error', 'Sorry ! Unexpected request method', 3);
		}
	} //function - Closed



	// public function change_today_appointments_status($id, $status, $pid, $puid, $serial){
	// 	$this->apmt_id = (int) $id; //appointment ID
	// 	$this->status = (int) $status; //Status
	// 	$this->patient_id = (int)$pid; //Patient ID
	// 	$this->puid = $puid; //Hospital assigned patient unique ID
	// 	$this->appt_serial = (int) $serial; //Appointment serial 

	// 	
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}

	// 	$this->admin_uid = session()->get('admin_session_uid');

	// 	if (!isset($this->admin_uid) || $this->admin_uid == '') {
	// 		$this->session->setTempdata('error', 'Admin UID is missing !', 3);
	// 		return redirect()->to(base_url() . "/Admin");
	// 	} //else { $this->admin_uid =  $this->admin_uid_arr['uid']; }

	// 	$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

	// 	$this->data = [
	// 		'is_del'=> '1',
	// 		'status'  => $status,
	// 		'updated_at' => date('Y-m-d H:i:s'),
	// 		'updated_by' => $this->admin_uid,
	// 	];

	// 	if ($this->status === 4) { //Update appointments & insert patient info 
	// 		/***** Get Patient detail thru `puid` - START *****/
	// 		if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
	// 			$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
	// 			if ($this->updt_rslt === true) {
	// 				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			} else {
	// 				$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			}
	// 		}
	// 		/***** Get Patient detail thru `puid` - END *****/
	// 		else if ($this->patient_id > 0) {
	// 			/*****  Get Patient detail thru `paitient ID` - START *****/
	// 			//$this->args['id']  = $this->patient_id;
	// 			$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
	// 			if ($this->updt_rslt === true) {
	// 				$this->session->setTempdata('success', 'Congratulation 2! Appointment status change successfully', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			} else {
	// 				$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			}
	// 		}
	// 		/***** Get Patient detail thru `paitient ID` - END *****/
	// 		else if ($this->apmt_id != '' && $this->apmt_id > 0) {
	// 			/***** Get Patient detail thru `Appointment ID` - START *****/
	// 			$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
	// 			if ($this->updt_rslt === true) {
	// 				$this->session->setTempdata('success', 'Congratulation, Appointment status has changed successfully', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			} else {
	// 				$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
	// 				return redirect()->to(base_url() . '/Admin/today_appointments');
	// 			}
	// 		}
	// 		/***** Get Patient detail thru `Appointment ID` - END *****/
	// 		else {
	// 			$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
	// 			return redirect()->to(base_url() . '/Admin/today_appointments');
	// 		}
	// 	} else { //Update appointments only
	// 		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
	// 		if ($status === true) {
	// 			$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
	// 			return redirect()->to(base_url() . '/Admin/today_appointments');
	// 		} else {
	// 			$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
	// 			return redirect()->to(base_url() . '/Admin/today_appointments');
	// 		}
	// 	} //else loop - closed
	// } //Funtion - Closed
	public function change_today_appointments_status($id, $status, $pid, $puid, $serial){
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->patient_id = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 

		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$this->admin_uid = session()->get('admin_session_uid');

		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		} //else { $this->doctor_uid =  $this->doctor_uid_arr['uid']; }

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

		$this->data = [
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->admin_uid,
		];

		if ($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->patient_id > 0) {
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->args['id']  = $this->patient_id;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) {
				/***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->admin_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Admin/today_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Admin/today_appointments');
			}
		} else { //Update appointments only
			// $status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Admin/today_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Admin/today_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed


/* @params: Function for permanent delete today appointments
* @desc: Admin can soft delete all appointments also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_today_apmnt($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  =>  $id,
			//'booking_date' => date('Y-m-d'),
		];
		$data = [
			'is_del' => 1,
			'status' => 1, //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
	
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Admin/today_appointments');
	}

	public function delete_slider($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('slider_image', $args, $data);
		//$status = $this->adminModel->delete_records('slider_image', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Slider Image Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Image Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_slider');
	}

/* @params: Function for permanent delete slider
* @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_manage_slider($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id
			
		];
		$data = [
			'is_del' =>1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('slider_image', $args, $data);
		//$status = $this->adminModel->delete_records('slider_image', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Slider Image Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Image Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_slider');
	}
	public function change_slider_image_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$args = [
			'id'  => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('slider_image', $args,  $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Slider Image Status Changed Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Update Image Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_slider');
	}

	// public function image_gallery(){
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} else {
	// 		$data = [];
	// 		if ($this->request->getMethod() == 'post') {
	// 			$files = $this->request->getFiles();
	// 			foreach ($files['gallery_image'] as $img) {
	// 				if ($img->isValid() &&  !$img->hasMoved()) {
	// 					$new_image = $img->getRandomName();
	// 					if ($img->move(FCPATH . 'uploads/frontend/image_gallery/', $new_image)) {
	// 						$doc_img = $img->getName();
	// 						$this->user_data_arr = [
	// 							'image_title'           =>  $this->request->getVar('image_title', FILTER_SANITIZE_STRING),
	// 							'gallery_image'         =>  $doc_img,
	// 							'status'                => 'Active',
	// 							'created_at'            =>  date('Y-m-d h:i:s')
	// 						];
	// 						$status = $this->adminModel->Insertdata('gallery_image', $this->user_data_arr);
	// 					} else {
	// 						echo "<p>" . $img->getErrorString() . "</p>";
	// 					}
	// 				}
	// 			}
	// 			$this->session->setTempdata('success', 'Congratulation ! gallery Image Uploaded Successfully !', 3);
	// 			return redirect()->to(base_url() . '/Admin/image_gallery');
	// 		}
	// 		return view('Admin/frontend/image_gallery');
	// 	}
	// }

	public function image_gallery(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$data = [];

			if ($this->request->getMethod() == 'post') {
				$files = $this->request->getFiles(); //For multiple file uploads
				$img = $this->request->getFile('gallery_image');
				$allowedExtensions = ['png', 'jpg', 'jpeg']; // Add more if needed
				
				//echo "<pre>";print_r($files['gallery_image']);die;
				//foreach($selected_report_arr as $val) {
				foreach ($files as $img) {
					if ($img->isValid() && !$img->hasMoved()) { 
						$extension = pathinfo($img->getClientName(), PATHINFO_EXTENSION);
						
						//if ($img->getSize() <= (1024 * 5 * 1024) && in_array(strtolower($extension), $allowedExtensions)) {
						if ($img->getSize() <= (ALLOW_MAX_UPLOAD * 1024) && in_array(strtolower($extension), $allowedExtensions)) {
							$new_image = $img->getRandomName();

							if ($img->move(FCPATH . 'uploads/frontend/image_gallery/', $new_image)) {
								$doc_img = $img->getName();

								$this->user_data_arr = [
									'image_title'   => $this->request->getVar('image_title', FILTER_SANITIZE_STRING),
									'gallery_image' => $doc_img,
									'status'        => 'Active',
									'created_at'    => date('Y-m-d h:i:s')
								];

								$status = $this->adminModel->Insertdata('gallery_image', $this->user_data_arr);
								if($status) {
									$this->session->setTempdata('success', 'Congratulation! Gallery image uploaded successfully!', 3);
								}
								else { 
									$this->session->setTempdata('error', 'Failed! Upload image gallery!', 3);
								}
								return view('Admin/frontend/image_gallery');
							} 
							else {
								echo "<p>" . $img->getErrorString() . "</p>";
								$this->session->setTempdata('error', 'Failed! to move gallary image!', 3);
							}
						} 
						else { // Invalid file format or size
							echo "<p>Invalid file format or size for " . $img->getClientName() . "</p>";
							$this->session->setTempdata('error', 'Invalid file format or file size!', 3);
						}
					}
					else {
						$this->session->setTempdata('error', 'Failed! Validate file !', 3);
					}
					return view('Admin/frontend/image_gallery');
				}
			}
			else {
				return view('Admin/frontend/image_gallery');
			}
		} //else - loop closed
	} //function - clsoed
	

	public function manage_image_gallery() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [ 'is_del'  => '0', ];
			$data = [
				'gallery' => $this->adminModel->fetch_rec_by_args_arr('gallery_image', $args),
				'pager'     => $this->adminModel->pager
			];
			return view("Admin/frontend/manage_gallery", $data);
		}
	}


	public function delete_gallery_image($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('gallery_image', $args, $data);
		//$status  = $this->adminModel->delete_records('gallery_image', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! gallery Image Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Image Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_image_gallery');
	}

/* @params: Function for permanent delete gallery image
* @desc: Admin can soft delete gallery image also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 18th August,2023
* @modify
*/
	public function permanent_del_gallery_image($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id
		];
		$data = [ 
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];

		$status = $this->adminModel->update_rec_by_args('gallery_image', $args, $data);
		//$status  = $this->adminModel->delete_records('gallery_image', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! gallery Image Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Image Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_image_gallery');
	}

	public function change_gallery_img_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];
		
		$status = $this->adminModel->update_rec_by_args('gallery_image', $args,  $data);
		//var_dump($status); exit;
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! gallery Image Status Changed Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Changed Image Status Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/manage_image_gallery');
	}


	public function change_privacy_policy_status($id, $status){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'status'  => $status
		];
		
		$status = $this->adminModel->update_rec_by_args('privacy_terms', $args,  $data);
		//var_dump($status); exit;
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Staus of Privacy Policy Changed Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Changed Image Status Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/policy');
	}

	public function filter_gallery($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_gallery') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_gallery') {
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
			'is_del' => 0
		];
		$data = [
			'gallery' => $this->adminModel->filter_rec_by_args_with_pagination('gallery_image', $order,$args),
			'pager'     => $this->adminModel->pager
		];

		return view('Admin/frontend/manage_gallery', $data);
	}

	public function manage_blood_acc(){
		if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			} else {
				$args = [
					//'status'  => 'InActive', 
					'is_del' => 0

				];
				$data = [
					'blood_accounts' => $this->adminModel->fetch_rec_by_args_arr('blood_bank_users', $args),
					'pager'     => $this->adminModel->pager
				];
				
				return view("Admin/Account/manage_blood_acc", $data);
		}
	}




	//BlOGS ssection 
	public function manage_blogs(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			//'status'   => 'Active'
			'is_del' => 0
		];
		$data = [
			'blogs_content' => $this->adminModel->fetch_rec_by_args_with_status('news_blog', $args),
			'pager'     => $this->adminModel->pager
		];
		return view("Admin/frontend/manage_blogs", $data);
	}


	/* @param: add_blog ie add news blog
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function add_newsblog() { 
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		else { return view('Admin/add_newsblog'); }
	}

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function save_newsblog() {
		$this->admin_uid = session()->get('admin_session_uid');
		$this->admin_id = session()->get('admin_session_id');
		if (!isset($this->admin_uid) || !isset($this->admin_id)) {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'blog_title' => [
					'rules'  => 'required|min_length[4]|max_length[150]',
					'errors' => [
					'required'=> 'Blog title is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 150.'
					],
				],
				// 'blog_content' => 'required|min_length[10]|max_length[10000]',
				'blog_content'  => [
					'rules'     => 'required|min_length[10]|max_length[10000]',
					'errors'    => [
					'required' => 'Blog content is  mandatory.',
					'min_length' => 'Minimum length is 10.',
					'max_length' => 'Maximum length is 10000.'
					],
				],

				'blog_image' => [
					'rules'     => 'uploaded[blog_image]|max_size[blog_image,' . ALLOW_MAX_UPLOAD .']|is_image[blog_image]|mime_in[blog_image,image/jpeg,image/png,image/svg, image/gif)]|ext_in[blog_image,png,jpg,jpeg, svg, gif]',
					'errors' => [
						'uploaded'  => 'Blog Image is mandatory.',
						'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
						'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
						'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
					],
				],
			];
			if ($this->validate($rules)) {
				$img = $this->request->getFile('blog_image');

				if ($img->isValid() && !$img->hasMoved()) {
					$newName = $img->getRandomName();
					// $this->blog_newspic = $image->getName();
					#$img->move(FCPATH . 'uploads/frontend/blog_image', $newName); 
					$img->move(FCPATH . 'uploads/frontend/blog_image', $newName);
					// $path = $this->request->getFile('profile_pic')->store();
					$this->blog_newspic = $img->getName();

					$args = [
						'id' => $this->admin_id,
						'is_del' => 0
					];
					$this->admin_dtls_arr = $this->adminModel->fetch_rec_by_args('admin_users', $args);
					//Get Admin Details 
					$this->newsblog_data_arr = [
						'blog_title' => $this->request->getVar('blog_title', FILTER_SANITIZE_STRING),
						'blog_content' => $this->request->getVar('blog_content', FILTER_SANITIZE_STRING),
						'blog_image' => $this->blog_newspic,
						'doctor_name' => $this->admin_dtls_arr[0]->full_name,
						'doctor_id' => $this->admin_dtls_arr[0]->id,
						//'department_name' => $this->admin_dtls_arr[0]->department_name,
						//'dr_specialization' => $this->admin_dtls_arr[0]->dr_specialization,
						'status' => 'Preview',
						'created_date' => date('d'),
						'created_month' => date('M'),
						'created_year' => date('Y'),
						'created_at'	=> date('Y-d-m h:i:s'),
						'created_by'	=> $this->admin_uid
					];
					$status = $this->doctorModel->Insertdata('news_blog', $this->newsblog_data_arr);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! News Blog uploaded successfully !', 3);
					} 
					else {
						$this->session->setTempdata('error', 'Sorry ! Unable to Uploaded blog Try Again.', 3);
					}
					return redirect()->to(base_url() . 'Admin/manage_blogs');
				} 
				else {
					echo $image->getErrorString() . " " . $image->getError();
				}
			} 
			else {
				$data['validation'] = $this->validator;
			}
		}
		return view('Admin/add_newsblog', $data);
	} //function - Closed



	//Change Blog
	public function change_blog_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('news_blog', $args,  $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Blog Publish Successfully', 2);
			return redirect()->to(base_url() . '/Admin/manage_blogs');
		}
	}

	public function delete_blogs($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('news_blog', $args, $data);
		//$status =$this->adminModel->delete_records('news_blog', $args);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Blog Deleted Successfully', 2);
			return redirect()->to(base_url() . '/Admin/manage_blogs');
		}
	}

/* @params: Function for permanent delete blogs
* @desc: Admin can soft delete blogs also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
	public function permanent_del_blogs($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('news_blog', $args, $data);
		//$status =$this->adminModel->delete_records('news_blog', $args);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Blog Deleted Successfully', 2);
			return redirect()->to(base_url() . '/Admin/manage_blogs');
		}
	}


	public function filter_blogs($filter){
	
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_blogs') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_blogs') {
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
		$args = ['is_del'=>0];

		$data = [

			'blogs_content' => $this->adminModel->filter_rec_by_args_with_pagination('news_blog', $order, $args),
			'pager'   => $this->adminModel->pager
		];

		return view("Admin/frontend/manage_blogs", $data);
	}


	public function view_blog($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = ['id' => $id];
		$data['blogs'] = $this->adminModel->fetch_rec_by_args('news_blog', $args);
		return view('Admin/frontend/view_blog', $data);
	}

	public function patients_review(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
					//'status'  => 'Verify',
					'is_del' => 0
				];
			//$data['bld_bnk_admin'] = $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args);
			$data = [
				'review_patient' => $this->adminModel->fetch_rec_by_args_arr('review_hospital', $args),
				'pager'     => $this->adminModel->pager
			];
			//echo "<pre>";print_r($data);die;
			return view("Admin/frontend/patients_review", $data);
		}
	}

public function change_feedback_status($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->adminModel->update_rec_by_args('review_hospital', $args,  $data);
		if ($status == true) {
			// $this->session->setTempdata('success', 'Congratulation ! Feedback is Verify Successfully !', 3);
			$this->session->setTempdata('success', 'Congratulation ! Action Performed Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Changed Status Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/patients_review');
	}


	public function delete_review($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = ['id' => $id ]; //Review ID
		$data = [
			'status' => 'Deleted',
			'is_del' => 1, //Mark as DELETED
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('review_hospital', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Feedback is Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Delete Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/patients_review');
	}

	

	// public function permanent_del_patients_review($id) {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} 
	// 	$this->admin_uid = session()->get('admin_session_uid');
	// 	if (!isset($this->admin_uid) || $this->admin_uid == '') {
	// 		$this->session->setTempdata('error', 'Admin UID is missing !', 3);
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$args = [ 'id'  =>  $id ];
	// 	$data = [
	// 		'is_del' => 1, //0: Non deleted, 1: Deleted 
	// 		'status' => 'Deleted',
	// 		'updated_at' => date('Y-d-m h:i:s'),
	// 		'updated_by' => $this->admin_uid
	// 	];

	// 	$status = $this->adminModel->update_rec_by_args('review_hospital',  $args, $data);
	// 	if ($status == true) { 
	// 		$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
	// 	} 
	// 	else {
	// 		$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
	// 	}
	// 	return redirect()->to(base_url() . 'Admin/patients_review');
	// } //function - Closed



	public function filter_feedback($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($filter == 'new_feedback') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
			//echo "<pre>";print_r($order);die;
		} else if ($filter == 'old_feedback') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
			//echo "<pre>";print_r($order);die;
		} else {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
			//echo "<pre>";print_r($order);die;
		}
		$args = [
			//'status'        => 'InActive',
			//'level'         => '2'

		];
		$data = [
			'review_patient' => $this->adminModel->filter_rec_by_args_with_pagination('review_hospital', $order, $args),
			'pager'     => $this->adminModel->pager
		];

		return view("Admin/frontend/patients_review", $data);
	}

	public function check_login_activity(){
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'login_date'  => date('Y-m-d h:i:s')
		];
		$data['login_activity'] = $this->adminModel->fetch_rec_by_args('login_activity', $args);
		return view('Admin/department/check_login_activity', $data);
	}//function - Closed

	public function contact_us(){	
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [
			'contact_us' => $this->commonForAllModel->fetch_allrecords_bypage('contact_us'),
			'pager'     => $this->adminModel->pager
		];
		return view('Admin/frontend/contact_us', $data);
	}//function - Closed


	public function blood_bank_accountant(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			return  view('Admin/Account/blood_bank_accountant');
		}
	} //function - Closed

	/* @param: Function for edit_term & condition
     * @description: edit terms and condition
	 * Remark: It is usd to edit term & condition through admin.
     * @author: Frontend team.
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 2th aug, 2023
     */

	public function add_term_condition(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$data = [];

			if ($this->request->getMethod() == 'post') {
				$files = $this->request->getFiles(); //For multiple file uploads
				$img = $this->request->getFile('image');
				$allowedExtensions = ['png', 'jpg', 'jpeg']; // Add more if needed
				$doc_img ="";
				//==========
				//echo "<pre>";print_r($files['gallery_image']);die;
				//foreach($selected_report_arr as $val) {
				//foreach ($files as $img) {
					if (!is_null($img) && $img !="") { 
						$extension = pathinfo($img->getClientName(), PATHINFO_EXTENSION);
						
						//if ($img->getSize() <= (1024 * 5 * 1024) && in_array(strtolower($extension), $allowedExtensions)) {
						if ($img->getSize() <= (ALLOW_MAX_UPLOAD * 1024) && in_array(strtolower($extension), $allowedExtensions)) {
							$new_image = $img->getRandomName();

							if ($img->move(FCPATH . 'uploads/frontend/image_gallery/', $new_image)) {
								$doc_img = $img->getName();
							}
							//else {
							//	echo "<p>" . $img->getErrorString() . "</p>";
							//	$this->session->setTempdata('error', 'Failed! to move gallary image!', 3);
							//}
						}
					}
						//else { // Invalid file format or size
							//echo "<p>Invalid file format or size for " . $img->getClientName() . "</p>";
						//	$this->session->setTempdata('error', 'Invalid file format or file size!', 3);
						//}
						$this->user_data_arr = [
							'title'   => $this->request->getVar('title', FILTER_SANITIZE_STRING),
							'image' => $doc_img,
							'long_desc'   => $this->request->getVar('description', FILTER_SANITIZE_STRING),
							'type'   =>   1,    //1: Terms & Conditions, 2: Privacy Policy
							'status'        => 0, //0: Drafted, 1: Published,  2:Unpublished, 3: Deleted, 4: Other
							'created_at'    => date('Y-m-d h:i:s')
						];

						$status = $this->adminModel->Insertdata('privacy_terms', $this->user_data_arr);
						if($status) {
							$this->session->setTempdata('success', 'Congratulation! Data saved successfully!', 3);
						}
						else { 
							$this->session->setTempdata('error', 'Failed! Upload image gallery!', 3);
						}
						return view('Admin/add_term_condition');
					
						
					//}
					//else {
					//	$this->session->setTempdata('error', 'Failed! Validate file !', 3);
					//}
					return view('Admin/add_term_condition');
				//}
			}
			else {
				return view('Admin/add_term_condition');
			}
		} //else - loop closed
	} //function - clsoed



	public function terms_conditions_edit(){
		if (!(session()->has('admin_session_uid'))) {
				return redirect()->to(base_url() . "/Login");
			}
			$args = [ 'is_del'  => 0, ];
			$data = [
				'terms_conditions_edit' => $this->adminModel->fetch_rec_by_args_arr('privacy_terms', $args),
				'pager'     => $this->adminModel->pager
			];
			if(!isset($data['terms_conditions_edit']) || $data['terms_conditions_edit'] == '') { 
				$this->session->setTempdata('error', 'Sorry ! No terms conditions edit found.', 3);
				return redirect()->to(base_url()."Admin/add_term_condition");
			}
			//echo "<pre>";print_r($data);die;
			return view("Admin/terms_conditions_edit", $data);
	} //function - Closed


	// public function manage_image_gallery() {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	} else {
	// 		$args = [ 'is_del'  => '0', ];
	// 		$data = [
	// 			'terms_conditions_edit' => $this->adminModel->fetch_rec_by_args_arr('privacy_terms', $args),
	// 			'pager'     => $this->adminModel->pager
	// 		];
	// 		return view("Admin/frontend/manage_gallery", $data);
	// 	}
	// }

	public function Upload_terms_condition(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'title' => 'required',
				// 'blog_content' => 'required|min_length[10]|max_length[10000]',
				'blog_content'  => [
					'rules'     => 'required|min_length[10]|max_length[10000]',
					'errors'    => [
					'required' => 'Blog content  is mandatory.',
					'min_length' => 'Minimum length is 10.',
					'max_length' => 'Maximum length is 10000.'
					],
				],
				'blog_image' => 'uploaded[blog_image]|max_size[blog_image,1024]|ext_in[blog_image,png,jpg,jpeg, svg, gif]',
			];

			if ($this->validate($rules)) {
				$img = $this->request->getFile('blog_image');

				if ($img->isValid() && !$img->hasMoved()) {
					$this->rand_name = $img->getRandomName();
					$img->move(FCPATH . 'uploads/frontend/blog_image', $this->rand_name);
					$doc_img = $img->getName();

					$doctor_id = session()->get('admin_session_id');
					$args = ['id' => $doctor_id];
					$doctor = $this->doctorModel->fetch_rec_by_args('privacy_terms', $args);

					$userdata = [
						'title' => $this->request->getVar('title', FILTER_SANITIZE_STRING),
						'blog_content' => $this->request->getVar('blog_content', FILTER_SANITIZE_STRING),
						'blog_image' => $doc_img,
						'doctor_name' => $doctor[0]->doctor_name,
						'doctor_id' => $doctor[0]->id,
						'department_name' => $doctor[0]->department_name,
						'dr_specialization' => $doctor[0]->dr_specialization,
						'status' => 'Preview',
						'created_date' => date('d'),
						'created_month' => date('M'),
						'created_year' => date('Y')
					];
					$status = $this->doctorModel->Insertdata('privacy_terms', $userdata);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation! terms condition uploaded successfully!', 3);
					} else {
						$this->session->setTempdata('error', 'Sorry! Unable to Uploaded terms condition. Try Again?', 3);
					}
					return redirect()->to(base_url() . '/Admin/terms_conditions_edit');
				} else {
					echo $img->getErrorString() . " " . $img->getError();
				}
			} else {
				$data['validation'] = $this->validator;
			}
		}
		return view('Admin/terms_conditions_edit', $data);
	}

	public function policy(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$data = [
				'gallery' 	=> $this->commonForAllModel->fetch_allrecords_bypage('privacy_terms'),
				'pager'     => $this->adminModel->pager
			];
			//echo "<pre>";print_r($data);die;
			return view('Admin/policy', $data);
		}
	} //function - Closed


	public function blood_bank_admin(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				'status'  => 'InActive',
				'level'   => '4'  //4: for Blood Bank Account
			];
			$data = [
				'bld_bnk_admin' => $this->adminModel->fetch_rec_by_args_arr('register_all_users', $args),
				'pager'     => $this->adminModel->pager
			];
			return view('Admin/Account/blood_bank_admin_verify', $data);
		}
	}

	public function change_bld_admin_acc($id, $status){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [ 'id'  => $id ];
		$data  = [ 'status'  => $status ];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args,  $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Bank Admin Verify Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Verify Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/blood_bank_admin');
	}

	public function delete_bld_admin_account($id){
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id' => $id,
                        'is_del' => 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->admin_uid
		];
		$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $data);
		//$status = $this->adminModel->delete_records('register_all_users', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Bank Admin Account Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . '/Admin/blood_bank_admin');
	}


	//BlOGS ssection 
	// 
	//Front page Section Query 	




	/*********** Admin Dashboard - JS/Script called Functions - START ****/
	/* @param: Function called through JS/Script
     * @description: MOI of their use - show error under console if not use them
	 * Remark: Used in admin Dashboard.
     * @author: Frontend team.
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 2th Feb, 2025
     */
	public function count_patients($type = 'all') {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($type == 'all') {
			$patients = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		} else if ($type == 'today') {
			$args = [
				'created_at'  => date('Y-m-d h:i:s')
			];
			$patients = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else if ($type == 'yesterday') {
			$args = [
				'created_at'  => date('Y-m-d', strtotime("-1 day"))
			];
			$patients = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else if ($type == 'last_30_days') {
			$args = [
				'created_at>='  => date('Y-m-d', strtotime("-30 day")),
				'created_at<='   => date('Y-m-d h:i:s')
			];
			$patients = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else {
			$patients = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		}
		echo count($patients);
	}

	/* @param: Function called through JS/Script
     * @description: MOI of their use - show error under console if not use them
	 * Remark: Used in admin Dashboard.
     * @author: Frontend team.
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 2th Feb, 2025
     */
	public function count_income($type = 'all') {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		if ($type == 'all') {
			$income = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		} else if ($type == 'today') {
			$args = [
				'created_at'  => date('Y-m-d h:i:s')
			];
			$income = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else if ($type == 'yesterday') {
			$args = [
				'created_at'  => date('Y-m-d', strtotime("-1 day"))
			];
			$income = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else if ($type == 'last_30_days') {
			$args = [
				'created_at>='  => date('Y-m-d', strtotime("-30 day")),
				'created_at<='  => date('Y-m-d h:i:s')
			];
			$income = $this->adminModel->fetch_rec_by_args_with_date('patients', $args);
		} else {
			$income = $this->commonForAllModel->fetch_allrecords_bypage('patients');
		}
		$total_income = 0;
		if (count($income)) {
			foreach ($income as $count_inc) {
				// $total_income += $inc->entry_fee;

				$total_income += $count_inc->other_fee +  $count_inc->entry_fee;
			}
		} else {
			$total_income = 0;
			// number_format($total_income);
		}
		echo json_encode($total_income);
	}


	/* @param: Function called through JS/Script
     * @description: Show income on admin dashboard based on: all, today, yesterday
     * last 30 days
	 * Remark: Used in admin Dashboard.
     * @author: Frontend team.
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 2th Feb, 2025
     */
	public function count_medical_income($type = 'all') {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}

		$income = []; 		//For addressing notices
		$total_income = 0;	//For addressing notices
		if ($type == 'all') { 
			$income = $this->commonForAllModel->fetch_allrecords_bypage('sale_products');
		} 
		else if ($type == 'today') {
			$args = [ 'date'  => date('Y-m-d h:i:s') ];
			$income = $this->adminModel->fetch_rec_by_args_with_date('sale_products', $args);
		} 
		else if ($type == 'yesterday') {
			$args = [ 'date'  => date('Y-m-d', strtotime("-1 day")) ];
			$income = $this->adminModel->fetch_rec_by_args_with_date('sale_products', $args);
		} 
		else if ($type == 'last_30_days') {
			$args = [
				'date>='  => date('Y-m-d', strtotime("-30 day")),
				'date<='  => date('Y-m-d h:i:s')
			];
			$income = $this->adminModel->fetch_rec_by_args_with_date('sale_products', $args);
		} 
		else { $income = $this->commonForAllModel->fetch_allrecords_bypage('sale_products'); }
		
		if (isset($income) && is_array($income)) { //blank table - return false
			count($income);
			foreach ($income as $count_inc) {
				$total_income += $count_inc->quantity *  $count_inc->rate;
			}
		} 
		else { $total_income = 0; }
		echo json_encode($total_income);
	}
	/*********** Admin Dashboard - JS/Script called Functions - END ****/


	//******* Revisit patients Functions - START ********//
	/* @params: this Admissiion process function is for revisited patients
	* @desc: Admission Processed 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 19th january,2024
	* @modify
	*/
	public function revisit_admission_process() { 
		$this->insrtData = []; //Just for addressing notices
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->doctor_uid = session()->get('admin_session_uid');
		$data  = [];
		
		// $data['validation'] = null;
		if($this->request->getMethod() == 'post') { 
			$this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
			$this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
			if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
				$this->result_arr = array(
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message' => 'Patient ID may not blank',
					'data' => $this->insrtData
				);
				return json_encode($this->result_arr);
			}
			$this->updt_bed_arr = [
				'department_name'  => $this->request->getPost('department_name',FILTER_SANITIZE_STRING),
				'ward_name'        => $this->request->getPost('wardname',FILTER_SANITIZE_STRING),
				'bed_lable'        => $this->request->getPost('bed_lable',FILTER_SANITIZE_STRING),
				'other_info'       => $this->request->getPost('other_info',FILTER_SANITIZE_STRING),   //Need to mention name in HTML
				'patient_id'       => $this->patient_id,
				// 'is_free'	   => 0,
				'status'		   =>'Occupied',
				'pid'       	   => $this->request->getPost('pid',FILTER_SANITIZE_STRING),
				'puid'       	   => $this->request->getPost('puid',FILTER_SANITIZE_STRING),
				'apmt_id'          => $this->request->getPost('apmt_id',FILTER_SANITIZE_STRING),
				'dr_id'       	   => $this->request->getPost('dr_id',FILTER_SANITIZE_STRING),
				'updated_at' 	   => date('Y-m-d h:i:s'),
				'updated_by'       => $this->doctor_uid,
			];
			//echo "<pre>"; print_r($this->updt_data_arr);die;
			$this->updt_bed_args = [ 'id' 	=> $this->bed_id ];  

			$this->updt_rvsit_arr = [ 
				'status'       	=> 'Admission Processed',
				'updated_at' 	=> date('Y-m-d h:i:s'),
				'updated_by'    => $this->doctor_uid,
			];
			$this->updt_rev_args = [ 'id' 	=> $this->patient_id];
			$status = $this->commonForAllModel->update_into_two_tables('hospital_beds', $this->updt_bed_args, $this->updt_bed_arr, 'revisit_patients', $this->updt_rev_args, $this->updt_rvsit_arr);
			if ($status === true) {
				$this->result_arr = array(
					'status' => true,
					'error'	 => false, //error: `false` with status `true`
					'code'	 => 200,
					'message'=> 'Record updated successfully',
					'data'   => $this->updt_bed_arr
				);
				return json_encode($this->result_arr);
			} 
			else {
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	 => 200,
					'message'=> 'Failed! to update record',
					'data'   => $this->updt_bed_arr
				);
				return json_encode($this->result_arr);
			} //else - loop closed 
		}
		else {
			$this->result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message'=> 'Unexpected request method',
				'data'   => $this->updt_bed_arr
			);
			return json_encode($this->result_arr);
		} //else - loop closed
	} //function - Closed

	/* @params: 
	* @desc: Render Admission form for adding admimission fee and other charges
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 9th November,2023
	* @modify
	*/
	public function admit_revisit_patient($patient_id, $pid, $apmt_id, $puid, $serial, $dr_id) {
		//echo "<pre>";print_r($patient_id .'/'. $pid.'/'. $apmt_id.'/'. $puid.'/'.$serial.'/'. $dr_id);die;
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->patient_id = (int) $patient_id; 
			$this->pid = (int) $pid; //Patient ID
			$this->apmt_id = (int) $apmt_id; //appointment ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 
			//var_dump($this->patient_id);die;
			$data = [];
			$data['validation'] = null;
			// $data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function
			// $data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices
			$dr_args = [ 'id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $this->apmt_id ]; 
			$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Admin/payments/add_revisit_admission_fee', $data); // Pass the data to the view
	} //function - Closed


	// public function change_revisit_patients_status($id, $status) {
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$this->admin_uid = session()->get('admin_session_uid');
	// 	if (!isset($this->admin_uid) || $this->admin_uid == '') {
	// 		$this->session->setTempdata('error', 'Admin UID is missing !', 3);
	// 		return redirect()->to(base_url() . "/Login");
	// 	}
	// 	$args = [ 'id'   => $id ];
	// 	$data = [
	// 		'status'  => $status,
	// 		'updated_at' => date('Y-m-d H:i:s'),
	// 		'updated_by' => $this->admin_uid
	// 	];

	// 	$status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $data);
	// 	if ($status == true) {
	// 		session()->setTempdata('success', 'Congratulation ! Patients Status Updated Successfully', 2);
	// 		return redirect()->to(base_url() . '/Admin/manage_revisited_patients');
	// 	}
	// }
	public function change_revisit_patients_status($id, $pid, $status) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$this->admin_uid = session()->get('admin_session_uid');
			if (!isset($this->admin_uid) || $this->admin_uid == '') {
				$this->session->setTempdata('error', 'Admin UID is missing !', 3);
				return redirect()->to(base_url() . "/Login");
			}
		}
		$args = [ 'id' => $id,]; 
		$data = [
				'status'  => $status,
				'updated_at' => date('Y-d-m H:i:s'),
				'updated_by' => $this->admin_uid,
			];
		$status = $this->patient_model->update($id, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
			return redirect()->to(base_url() . '/Admin/manage_revisited_patients');
		}
	} //funtion - Closed



	public function delete_revisit_patients($id){
		$this->patient_id = $id;
		
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if (!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'status'     => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->admin_uid
		];

		//$status = $this->adminModel->delete_rec_by_args('revisit_patients', $data, $args);
		$status = $this->adminModel->update_rec_by_args('revisit_patients',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
			//return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
			return redirect()->to(base_url() . 'Admin/manage_revisited_patients');
		} else {
			session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
			// return redirect()->to(base_url().'Admin/manage_revisit_patient');
			return view('Admin/manage_revisited_patients', $data);
		}
	}

/* @params: Function for permanent delete revisit patients
* @desc: Admin can soft revisit patients also a soft deleted data can be permanently delete by this function
* @use: Admin....
* @return:
* @author: Neoarks Team
* @date: 16th August,2023
* @modify
*/
public function permanent_del_revisit_patients($id){
	$this->patient_id = $id;
	
	if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
	}
	$this->admin_uid = session()->get('admin_session_uid');
	if (!isset($this->admin_uid) || $this->admin_uid == '') {
		$this->session->setTempdata('error', 'Admin UID is missing !', 3);
		return redirect()->to(base_url() . "/Login");
	}
	$args = [
		'id'   => $id,
		// 'is_del'   => '1'
	];
	$data = [
		'is_del'     => '1',
		'status'     => 'Deleted', //0: Non deleted, 1: Deleted
		'updated_at' => date('Y-m-d H:i:s'),
		'updated_by' => $this->admin_uid
	];

	//$status = $this->adminModel->delete_rec_by_args('revisit_patients', $data, $args);
	$status = $this->adminModel->update_rec_by_args('revisit_patients',  $args, $data);
	if ($status == true) {
		session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
		//return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
		return redirect()->to(base_url() . 'Admin/manage_revisited_patients');
	} else {
		session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
		// return redirect()->to(base_url().'Admin/manage_revisit_patient');
		return view('Admin/manage_revisited_patients', $data);
	}
}


public function discharge_revisit_patients($id){
	if (!(session()->has('admin_session_uid'))) {
		return redirect()->to(base_url() . "/Login");
	}
	$args = [
		'id'  => $id
	];
	$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
	return view('Admin/manage_revisited_patients', $data);
}

// public function manage_revisit_patient() {
// 	if (!(session()->has('admin_session_uid'))) {
// 		return redirect()->to(base_url() . "/Login");
// 	} else {
// 		$args = [ 'status'  => 'Discharged' ];
// 		$data = [
// 			'revisited_patients'  => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
// 			'pager'   => $this->adminModel->pager
// 		];
// 		return view('Admin/manage_revisit_patient', $data);
// 	}
// }

	/*@params: 
	* @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
	* @retuns: Array format
	* @author: Neoarks Team
	* @date: 10th July, 2023
	* @modify:
	*/
	public function manage_revisited_patients(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$args = [
				//'status'       	=> 'Admission Processed',
				'is_del'  => 0
			];
			$data = [
				'revisited_patients'  => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
				'pager'   => $this->adminModel->pager
			];
			if (!isset($data['revisited_patients']) || $data['revisited_patients'] == '') {
				$this->session->setTempdata('error', 'Sorry ! No revisited patient found. Please see all patients here', 3);
				return redirect()->to(base_url() . "Admin/manage_patients");
			}
			//echo "<pre>";print_r($data);die;
			return view('Admin/manage_revisit_patient', $data);
		}
	}
	
	public function revisit_patients($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'id'  => $id
		];
		$data['patients'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
		$data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		if ($data['doctors'] == false) {
			$this->session->setTempdata('error', 'Sorry ! No record found ', 3);
			return redirect()->to(base_url() . 'Admin/manage_revisit_patient');
		}
		return view('Admin/revisit_patients', $data);
	}


	

	public function search_results(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");}  
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		$args = [
			//'status' => 'Discharged'
		];
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('revisit_patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		} 
		$data = [
			'revisited_patients' => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/manage_revisit_patient', $data);
	}
	

	public function filter_revisit_patient($filter){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
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
			//'status'        => 'Discharged'
			'is_del'        => 0
		];

		$data = [
			'revisited_patients' => $this->adminModel->filter_rec_by_args_with_pagination('revisit_patients', $order, $args),
			'pager'     => $this->adminModel->pager
		];

		if ($data['revisited_patients'] == false) {
			$this->session->setTempdata('error', 'Sorry ! No record found!', 3);
			return redirect()->to(base_url() . 'Admin/manage_revisited_patients');
		}
		return view('Admin/manage_revisit_patient', $data);
	}


	public function number_of_visit_patients($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args =  [
			'patient_id'  => $id
		];
		$data['pat_visiter'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
		if ($data['pat_visiter'] == false) {
			$this->session->setTempdata('error', 'Sorry ! No revisited patient found ', 3);
			return redirect()->to(base_url() . "Admin/manage_revisited_patients");
		}
		//echo "<pre>";print_r($data['pat_visiter']);die;   //data are coming from backend side
		return view('Admin/number_of_visiting_pat', $data);
	}

	public function search_revisit_patient(){
		if (!(session()->has('admin_session_uid'))) {  
			return redirect()->to(base_url()."/Login");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$this->result_arr = $this->adminModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$this->result_arr = $this->adminModel;
		}   
		
		$data = [
			'patients' => $this->adminModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->adminModel->pager
		];
		return view('Admin/manage_revisit_patient', $data);
	}


	public function fetch_revisit_patients() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		else {
			$args = [ 'login_acc'	=> 1];
			$data = [
				'doctors' => $this->adminModel->fetch_rec_by_args('doctor', $args),
			];
			return view('Admin/add_patients', $data);
		}
	} //Function - Closed

	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- START ******************/
	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Render Payment form and save payement into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: 
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function add_revisit_patient_payment($id, $pid, $apmtid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		
		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
			return redirect()->to(base_url() . 'Admin/manage_revisit_patient');
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'get') { //Render payment form
			$args = [ 'id'  => $this->apmt_id ];
			$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
			return view('Admin/add_revisit_patient_payment', $data);
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
					'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
					'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
					'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
					'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
					'paid_amount'           => $total_patient_paid_amount,
					'pid'                   =>  $pid,
					'patients_id'	        => $id,
					'puid'			        => $this->puid, 
					'apmt_id'		        => $this->apmt_id,
					//'status'              =>  'Dues Cleared',
					'pay_date'              =>  date('Y-m-d'),
					'created_at'            =>  date('Y-m-d h:i:s'),
					'created_by'            => $this->admin_uid,
				];
				
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Admin/generate_revisit_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_revisit_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Mandatory validation failed', 2);
				$data['validation'] = $this->validator; 
				return view('Admin/add_revisit_patient_payment', $data); 
			}
		}
		else {
		 	$this->session->setTempdata('error', 'Unexpected request method', 2);
		 	$data['validation'] = $this->validator;
			return redirect()->to(base_url().'Admin/manage_revisit_patient');
	 	}
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Generate pdf format bill for patient provided payments 
	* @retuns: Internally used function
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate__revisit_patient_bill($patient_id, $payid, $apmtid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate__revisit_apment_patient_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate__revisit_apment_patient_bill', $data);
		}
	} //function - Closed
	

	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Show patient treatment expenses, done by hospital for patient
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function add_revisit_hospital_expenses($id, $pid, $amptid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		$data = [
				'id' 		=> $id,
				'pid' 		=> $pid,
				'apmt_id'	=> $amptid,
				'puid'		=> $puid,
			];
		return view('Admin/add_revisit_hosp_expense', $data); 
	} //function - Closed


	/*@params: Ajax call
	* @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function save_revisit_hospital_expenses() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
		if ($this->request->getMethod() == 'post') {
			//$data['id'] = $id;
			$rules = [
					'medical_item_name' => 'required',
					'unit_price'    => 'required',
					//'quantity' => 'required',
					'tax'      => 'required',
					//'taxCalculation' => 'required',
					//'date'     => 'required'
				];
				//var_dump($this->request->getPost('unit_price'));die;
			if ($this->validate($rules)) {
				
				$insdata = [
					'medical_item'        =>$this->request->getPost('medical_item_name'),
					'medical_code'        =>  $this->request->getPost('item_code'),
					'unit_price'          =>  $this->request->getPost('unit_price'),
					'total_Price'         =>  $this->request->getPost('totalPrice'),	
					'units'               =>  $this->request->getPost('quantity'),
					'tax_percentage'      =>  $this->request->getPost('tax'),
					'tax_amount'    	  =>  $this->request->getPost('taxCalculation'),
					'discount_percentage' =>  $this->request->getPost('discount'),
					'discount_amount'     =>  $this->request->getPost('discount'),
					'patients_id'         =>  $this->request->getPost('patients_id'),
					'pid'        		  =>  $this->request->getPost('pid'),
					'apmt_id'        	  =>  $this->request->getPost('apmt_id'),
					'puid'        		  =>  $this->request->getPost('puid'),
					'created_at'          =>  date('Y-m-d h:i:s'),
					'created_by'          =>  $this->admin_uid,
				];

				$status = $this->adminModel->Insertdata('treatment_expenses_history', $insdata);
				if ($status === true) {
					$this->result_arr = array(
						'status'    => true,
						'error'		=> false, //error: `false` with status `true`
						'code'  	=> 200,
						'message'   => 'Expenses added successfully',
						'data'      => $insdata
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status'   => false,
						'error'	   => true, //error: `true` whereever status is false with SQL err 
						'code'	   => 200,
						'message'  => 'Failed! to add expenses',
						'data'     => $insdata
					);
					return json_encode($this->result_arr);
				}
			} 
			else { 
				$insdata['validation'] = $this->validator;
				$insdata = $insdata['validation']->getErrors();
				
				$this->result_arr = array(
					'status' => false,
					'error'	 => false, //error: `false` showing validation error autoamtically
					'code'	 => 200,
					'message'=> 'Validation failed',
					'data'   => $insdata
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
				'data'   => $array(),
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
	public function show_revisit_patient_final_payments($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$rules = [ // Define validation rules for the form
			'total_hospital_expenses'    => 'required|numeric',
			'total_patient_paid_amount'  => 'required|numeric',
			'final_adjusted_amount'      => 'required|numeric',
			'remark'                     => 'required'
		];
	
		// Check if the request method is POST (form submission)
		//if ($this->request->getMethod() == 'post') {
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
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }

		//$args = [ 'patients_id'  => $patient_id ]; //where condition arguments
		$args = [ 'apmt_id'  => $apmtid ]; //where condition arguments
		
		$fld = 'paid_amount'; //table field name
		$data['total_pay_amt'] = $this->adminModel->fetch_sum_by_args('patients_pay_charges', $fld, $args);
		if( $data['total_pay_amt'] === false ) { $data['total_pay_amt'] = 0; }

		$fld = 'paid_amount'; //table field name
        $data['total_pay_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
        if( $data['total_pay_amt'] === false ) { $data['total_pay_amt'] = 0; }

        $fld = 'registration_fee'; //table field name
        $data['total_regis_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
        if( $data['total_regis_amt'] === false ) { $data['total_regis_amt'] = 0; }
        if(!isset($data['total_pay_amt'][0]->paid_amount) || (!isset($data['total_regis_amt'][0]->registration_fee))) {
            $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
        }
        $fld = 'admission_fee'; //table field name
        $data['total_admission_amt'] = $this->medicine_model->fetch_sum_by_args('patients_pay_charges', $fld, $args);
        if( $data['total_admission_amt'] === false ) { $data['total_admission_amt'] = 0; }
        if(!isset($data['total_pay_amt'][0]->paid_amount) || (!isset($data['total_regis_amt'][0]->registration_fee)) || (!isset($data['total_admission_amt'][0]->admission_fee))) {
            $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
        }
        //Total Patient paid amount - Total registration fee
        $data['total_payable'] = ($data['total_pay_amt'][0]->paid_amount - $data['total_regis_amt'][0]->registration_fee - $data['total_admission_amt'][0]->admission_fee);
		$fld = 'total_price'; //table field name
		$data['total_expnc_amt'] = $this->adminModel->fetch_sum_by_args('treatment_expenses_history', $fld, $args);
		if( $data['total_expnc_amt'] === false ) { $data['total_expnc_amt'] = 0; }
		
		$args = [ 'pid'  => $pid ];
		$data['revisit_patient'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
		$args_apmt = [ 'id'  => $apmtid ];
        $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);
		//4 march 2025 added
		//start: added new code to handle if there is no data comes from database 
		if($data['get_patient']){
			return view('Admin/show_revisit_patient_final_payments', $data);
		}
		else{
			$this->session->setTempdata('error', 'There is no payment to clear', 2);
			return  redirect()->to(base_url().'Admin/manage_revisited_patients ');
		}
		//end
		//return view('Admin/show_revisit_patient_final_payments', $data); // Pass the data to the view
	} //function - Closed



   /* @params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function clear_revisit_final_dues($patient_id, $pid, $apmtid, $puid) {//pid is patient_login tbl id
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
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
					return redirect()->to(base_url() . 'Admin/show_revisit_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
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
					'created_by'	=> $this->admin_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Admin/generate_revisit_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_revisit_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { $data['validation'] = $this->validator;
				return view('Admin/show_revisit_patient_final_payments', $data);
			 }
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		return view('Admin/discharge_appointment_pat', $data);
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_revisit_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid) { //Ref generate_patient_bill()
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID

		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Admin/show_revisit_patient_final_payments/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
					'status'  => 'Dues Cleared', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $update_dt_arr);
		
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate_revisit_clear_dues_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate_revisit_clear_dues_bill', $data);
		}
	} //function - Closed

	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/

	 /* @params: 
	* @desc: Save Admission fee 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 9th November,2023
	* @modify
	*/
	public function save_revisit_admission_fee($patient_id, $pid, $apmtid, $puid, $serial) {
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->admin_uid = session()->get('admin_session_uid');
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
					'payment_note'	=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'           =>  $this->pid,
					'puid'          =>  $this->puid,
					'patients_id'   =>  $this->patient_id,
					'apmt_id'    	=>  $this->apmt_id,
					'pay_date'      =>  date('Y-m-d'),
					'created_at'	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->admin_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					session()->setTempdata('error','Oops ! Unable to update payments', 2);
					return redirect()->to(base_url().'Admin/generate_revisit_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Admin/generate_revisit_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
				}
			} 
			else { 
				$data['validation'] = $this->validator;
				return view('Admin/payments/add_revisit_admission_fee', $data);
				$this->session->setTempdata('error', 'Failed to validate mandatory fields', 3);
			}
		}
		else { 
			$this->session->setTempdata('error', 'Request method is not supported', 3); 
		}
		$this->pcnt_args = ['id' 	=> $this->apmt_id];
		$data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

		$this->dr_args = ['id' 	=> $this->apmt_id]; //NEED $doctor_id here
		$data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $this->dr_args);
		return view('Admin/payments/add_revisit_admission_fee', $data);
	} //function - Closed
//----------------Number of function related to revisit patients end here-----------------//
/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	public function generate_revisit_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		//$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = false; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Admin/add_revisit_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		//var_dump($this->patient_id);die;
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
			$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Admit', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->admin_uid,
				];
			$this->updt_status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $update_dt_arr);
		}
		//var_dump($this->updt_status);die;
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Admin/payments/generate_admission_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Admin/payments/add_admission_fee', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Admin/payments/generate_admission_bill', $data);
		}
	} //function - Closed

	

   /**************** Prescritpion Section For Revisit Patient - START  **************/
   /* @param: 
    * @desc: Render Prescription Form: Step - Zero
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	function add_revisit_prescription($pid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		else {
			$data = [
				'res'  => $this->adminModel->getRevisitPatientRecordWithAppointment($pid),
				'pager'   => $this->adminModel->pager
			];
			$args = [ 'pid'	=>		$pid ]; //Patient ID
			$this->report = $this->adminModel->fetch_rec_by_args('patient_reports', $args);
			$this->reportArr = [];
			$this->reportAtchMentArr = [];
			if($this->report) {
				foreach($this->report as $val){
					$this->reportArr[$val->id] = $val->report_name;
					$this->reportAtchMentArr[$val->id] = $val->report_attachment;
				}

			} //else loop - Not requred here because no report has assigned to patient in this case
			$data['report'] = $this->reportArr; 
			$data['reporatach'] = $this->reportAtchMentArr; 
			return view('Admin/add_revisit_patient_prescription', $data);
		}
	} //function - Closed



   /* @param: 
    * @desc: Add Prescription: Step - 1
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	function upload_revisit_prescription() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}  
		else {
			$this->return_dt_arr = []; //Just for addressing notices
			$this->admin_uid = session()->get('admin_session_uid'); //Loggedin User uid
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr_arr = array(
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				); 
				return json_encode($this->result_arr);
			}
			$rules = [ 
				'prescription'  => 'required',
				'doctor_name'  	=> 'required',
				'patient_id'  	=> 'required',
				'patient_name'  => 'required',
				'patient_puid'  => 'required',
				];	
			if ($this->validate($rules)) {
				$prescription      = $this->request->getPost('prescription');
				$patient_name      = $this->request->getPost('patient_name');
				$patient_id 	   = $this->request->getPost('patient_id');
				$doctor_id 	       = $this->request->getPost('doctor_id');
				$patient_age       = $this->request->getPost('patient_age');
				$patient_mobile    = $this->request->getPost('patient_mobile');
				$patient_gender    = $this->request->getPost('patient_gender');
				$patient_puid      = $this->request->getPost('patient_puid');
				$doctor_name       = $this->request->getPost('doctor_name');
				$education         = $this->request->getPost('education');
				$dr_specialization  = $this->request->getPost('dr_specialization');
				$doc_gender        = $this->request->getPost('doc_gender');
				$apmt_id 	       = $this->request->getPost('apmt_id');
				$pid 	           = $this->request->getPost('pid');
				$ref_by 	       = $this->request->getPost('ref_by');
				$patient_mobile    = $this->request->getPost('patient_mobile');
				$patient_email 	   = $this->request->getPost('patient_email');
				$disease_symptoms  = $this->request->getPost('disease_symptoms');
				$patient_type 	   = $this->request->getPost('patient_type');
				$status 	   	   = $this->request->getPost('status');
				
				if($status != "Admit") { $status = 'Prescribed'; }
				$this->prescription_data_arr = [
					'prescription'    	  =>  $prescription,//
					'prescription_detail' =>  $prescription, //
					'patient_name'        =>  $patient_name,
					'patient_id'          =>  $patient_id,
					'doctor_id'           =>  $doctor_id,
					'status'              =>  $status, 
					'age'          		  =>  $patient_age,
					'gender'          	  =>  $patient_gender,
					'puid'          	  =>  $patient_puid,
					'doctor_name'         =>  $doctor_name,
					'apmt_id'        	  =>  $apmt_id,
					'pid'        		  =>  $pid,
				    'ref_by'    		  =>  $ref_by,
					'patient_mobile'      =>  $patient_mobile,
					'patient_email'       =>  $patient_email,
					'disease_symptoms'    =>  $disease_symptoms,
					'patient_type'        =>  $patient_type,
					'prescription_date'   =>  date('Y-m-d H:i:s'),
					'created_at'      	  =>  date('Y-m-d H:i:s'),
					'created_by'      	  =>  $this->admin_uid, //Harcoded for now
				];
				//echo "<pre>";print_r($this->prescription_data_arr);die;
				//$status = $this->adminModel->Insertdata('prescription_history', $this->prescription_data_arr);
				//$last_insrt_id = $this->commonForAllModel->Insertdata_return_id('prescription_history', $this->prescription_data_arr);

				$this->rev_pat_args = ['id' 	=> $patient_id];
				$this->updt_revisit_patient_arr = [
					'status'		=> $status,
					'updated_by'	=> $this->admin_uid,
					'updated_at'	=> date('Y-m-d H:i:s')

				];
				$this->fld = '';
				$this->fldval = '';
				$last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'revisit_patients', $this->rev_pat_args, $this->updt_revisit_patient_arr);
				//var_dump($last_insrt_id);die;
				if ((int) $last_insrt_id > 0 ) {
					$this->return_dt_arr = ['prescription_id' => $last_insrt_id];
					//$this->data['prescription'] = array(array());
					 $this->result_arr = array(
						'status' => true,
						'error'		=> false, //error: `false` with status `true`
						'code' => 200,
						'message' => 'Prescription added successfully',
						'data'		=> $this->return_dt_arr,
					); 
					return json_encode($this->result_arr); 
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code' => 200,
						'message' => 'Failed to add prescripton. Please try again.',
						'data'		=> $this->return_dt_arr,
					); 
					return json_encode($this->result_arr);
				}
			}
			else{
				$this->result_arr = array(
					'status' => false,
					'error'		=> false, //error: `false` showing validation error autoamtically
					'code' => 200,
					'message' => 'Validation failed. Please try again',
					'data'		=> $this->return_dt_arr,
				); 
				return json_encode($this->result_arr); 
			}
		} 
	} //function - Closed

   /* @param: 
    * @desc: List or Add New Reports: Step - 2
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	
	public function add_rev_prescription_report($pid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session('admin_session_uid');
		$selected_report=$this->request->getPost('selected_report');
		$selected_report_arr=explode(',',$selected_report);
		
		if($this->request->getMethod() == 'post') {
			if(count($selected_report_arr)>0) {
				foreach($selected_report_arr as $val) {
					$this->report_data_arr = [
						'patient_id'	=>	$pid,
						'report_name'    =>  $val,
						'report_brief'   =>  $val, //
						'report_detail'  =>  $val,
						'report_attachment' =>  '',//$doc_img,
						'created_at'      =>  date('Y-m-d H:i:s'),
						'created_by'      =>  $this->admin_uid, //Hardcoded for now
					];
					$status = $this->doctorModel->Insertdata('patient_reports', $this->report_data_arr);
				}
			}
			if ($status === true) {
				$this->result_arr = array(
					'status' => true,
					'error'		=> false, //error: `false` with status `true`
					'code' => 200,
					'data' > '',
					'message' => 'Reports saved successfully'
				);
				return json_encode($this->result_arr);
			} 
			else {
				$this->result_arr = array(
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > '',
					'message' => 'Failed to save reports'
				);
				return json_encode($this->result_arr);
			}
		}
		else {
			$this->result_arr = array(
				'status' => false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Sorry ! Required GET method'
			);
			return json_encode($this->result_arr);
		}
	} //Function - Closed

// 	/* @param: 
//     * @desc: Upload Reports: Step - 3
//     * @use:
//     * @remark:
//     * @author: Neoarks Team
//     * @date:
//     * @modify:
//     */
	
	public function upload_rev_prescription_report($pid, $rid,$file_id, $prescription_id) { //$pid: patient_id, $rid : Report ID
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		$this->admin_uid = session()->get('admin_session_uid'); //Loggedin User uid
		 $data = [];
		 $data['validation'] = null;
			if ($this->request->getMethod() == 'post') {		
			$rpt = $this->request->getFile($file_id);
				$allowedFormats = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/gif'];
				if (!in_array($rpt->getMimeType(), $allowedFormats)) {
					$this->result_arr = [
						'status' => false,
						'error'  => true,
						'code' => 200,
						'data' => [],
						'message' => 'Invalid upload format. Upload media format is jpg, jpeg, png, svg, gif only.'
					];
					return json_encode($this->result_arr);
				}
			
				if ($rpt->getSize() > ALLOW_MAX_UPLOAD * 1024) {
					$this->result_arr = [
						'status' => false,
						'error' => true,
						'code' => 200,
						'data' => [],
						'message' => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
					];
					return json_encode($this->result_arr);
				}
				//if ($rpt->isValid() &&  !$rpt->hasMoved()) { //check usebility of isValid()
			if(!$rpt->hasMoved()) {
				$this->rand_name = $rpt->getRandomName();
				$rpt->move(FCPATH . 'uploads/patient_reports', $this->rand_name);
				$doc_img = $rpt->getName();
				
				$this->report_data_arr = [
					//'report_name'     =>  $doc_img,
					'pid'			    => $pid,
					'report_attachment' =>  $doc_img,
					'prescription_id'   =>  $prescription_id,
					//'ref_id'            =>  $ref_id,
					'updated_at'      =>  date('Y-m-d H:i:s'),
					'updated_by'      =>  $this->admin_uid,
				];
				$args = ['id'	=> $rid];
				//Need update model function in place of Insertdata - 
				$status = $this->adminModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
				if ($status === true) {
					$this->result_arr = array(
						'status' => true,
						'error'		=> false, //error: `false` with status `true`
						'code' => 200,
						'data' > '',
						'message' => 'Reports uploaded successfully'
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						'code' => 200,
						'data' > '',
						'message' => 'Failed to upload reports'
					);
					return json_encode($this->result_arr);
				}
				//return redirect()->to(current_url());
			} 
			else {
				// echo $image->getErrorString() . " " . $image->getError();
				// $this->session->setTempdata('error', 'Please Select any Image File', 3);
				// return redirect()->to(base_url() . '/Admin/add_prescription');
				$this->result_arr = array(
					'status' => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'data' > array(),
					'message' => 'Unable to move the uploaded report'
				);
				return json_encode($this->result_arr);
			}
		} 
		else {
			// $this->session->setTempdata('error', 'Sorry ! Required POST method.!', 3);
			// return redirect()->to(current_url());
			$this->result_arr = array(
				'status' => false,
				'error'		=> true, //error: `true` whereever status is false with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Unexpected request method. Please try again'
			);
			return json_encode($this->result_arr);
		}
		return redirect()->to(current_url());
	} //Function - Closed


//    /* @param: 
//     * @desc: Add Doctor Advice: Step - 4
//     * @use:
//     * @remark:
//     * @author: Neoarks Team
//     * @date:
//     * @modify:
//     */
	public function save_revisit_pat_advice() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}  
		else {
			$this->admin_uid = session('admin_session_uid');
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr = array(
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				); 
				return json_encode($this->result_arr);
			}
			$rules = [
				'advice'  => 'required',
			 ];	
			if ($this->validate($rules)) {
				$advice = $this->request->getPost('advice');
				$patient_id = $this->request->getPost('patient_id');
				
				$this->advice_data_arr = [
					'advice'    	=>  $advice,
					'updated_at'    =>  date('Y-m-d H:i:s'),
					'status'      	=> 'Prescribed',
					'updated_by'    =>  $this->admin_uid, //Harcoded for now
				];
				//echo"<pre>";print_r($this->advice_data_arr);die;
				$args = [ 'patient_id'   => $patient_id ];
				$fld_name = 'id';
				$this->maxid = $this->adminModel->get_max_val('prescription_history', $fld_name);
				if($this->maxid === false || (!isset($this->maxid['0']->id))) {
					$this->data['advice'] = array(array());
					 $this->result_arr = array(
						"status" => false,
						'error'		=> true, //error: `true` whereever status is false with SQL err 
						"code" => '200',
						"message" => 'ID is missing. Please talk to admin'
					); 
					return json_encode($this->result_arr);
				}
				else if($this->maxid['0']->id > 0 ) {
					$args = [ 'id'   => $this->maxid['0']->id ];
					$status = $this->adminModel->update_rec_by_args('prescription_history', $args, $this->advice_data_arr);
					if ($status === true) {
						$this->data['advice'] = array(array());
						$this->result_arr = array(
							"status" => true,
							'error'		=> false, //error: `false` with status `true`
							"code" => '200',
							"message" => 'Advice added successfully'
						); 
						return json_encode($this->result_arr); 
					} 
					else {
						$this->result_arr = array(
							"status" => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							"code" => '200',
							"message" => 'Failed to add advice'
						); 
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->data['advice'] = array(array());
						$this->result_arr = array(
							"status" => false,
							'error'		=> true, //error: `true` whereever status is false with SQL err 
							"code" => '200',
							"message" => 'Unexpected max ID. Please talk to admin'
						); 
					return json_encode($this->result_arr); 
				}
			}
			else {
				$this->data['advice'] = array(array());
				$this->result_arr = array(
					"status" => true,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'Validation failed.'
				); 
				return json_encode($this->result_arr); 
			}
		} //else - loop Closed
	} //Function - Closed

// /* @param: 
//     * @desc: Add Doctor Recommendation: Step -5
//     * @use:
//     * @remark:
//     * @author: Neoarks Team
//     * @date:
//     * @modify:
//     */
	public function save_revisit_pat_message() {
		if (!session()->has('admin_session_uid')) {
			return redirect()->to(base_url() . "/Login");
		} else {
			$this->admin_uid = session('admin_session_uid');
			$this->request_method = $this->request->getMethod();
	
			if ($this->request_method != 'post') {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => 200,
					"message" => 'Invalid request method'
				]);
			}
	
			$rules = [
				'msg_frm_doc' => 'required',
				'slct_refr_usr' => 'required|numeric|greater_than[0]'
			];
	
			if (!$this->validate($rules)) {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'Validation failed.'
				]);
			}
	
			$msg_frm_doc = $this->request->getPost('msg_frm_doc');
			$patient_id = $this->request->getPost('patient_id');
			$assigned_to = $this->request->getPost('slct_refr_usr');
	
			$message_data = [
				'assigned_by' => $this->admin_uid,
				'assigned_to' => $assigned_to,
				'status' 	=> 'Prescribed',
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $this->admin_uid,
				'recommendation' => $msg_frm_doc,
			];
	
			$prescription_id = $this->adminModel->get_max_val('prescription_history', 'id');
	
			if ($prescription_id === false || empty($prescription_id[0]->id)) {
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => 'ID is missing. Please talk to admin'
				]);
			}
	
			$status_history = $this->adminModel->update_rec_by_args('prescription_history', ['id' => $prescription_id[0]->id], $message_data);
	
			$status_patient = $this->adminModel->update_rec_by_args('patients', ['id' => $patient_id], ['status' => 'Prescribed']);
	
			if ($status_history === true && $status_patient === true) {
				return json_encode([
					"status" => true,
					'error'		=> false, //error: `false` with status `true`
					"code" => '200',
					"message" => 'Recommendation added successfully'
				]);
			} else {
				// Handle database errors
				$error_message = 'Failed to add Recommendation. Check the error logs for details.';
				log_message('error', $error_message);
	
				return json_encode([
					"status" => false,
					'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code" => '200',
					"message" => $error_message
				]);
			}
		}
	}  // Function - Closed
	// public function edit_patients($id){
	// 	if (!(session()->has('admin_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Login");
	// 	}$args = [
	// 		'id'  => $id ];
	// 	$data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
	// 	$data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
	// 	return view('Admin/edit_patients', $data);
	// }

	/***************************** Prescritpion Section For Revisit Patient- END  *****************************/

	public function edit_revisit_patients($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}$args = [
			'id'  => $id ];
		$data['revisit_patients'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
		$data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		//echo "<pre>";print_r($data['doctors']);die;
		return view('Admin/edit_revisit_patients', $data);
	}

	public function update_revisit_patients($pid){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		}
		$args = [
			'pid'  => $pid
		];

		$data = [
			'patient_name'           =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
			'patient_phone'          =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
			'patient_address'        =>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),
			'pin_zip_code'            =>  $this->request->getVar('pin_zip_code', FILTER_SANITIZE_STRING),
			'doctor_name'            =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
			'doctor_fee'             =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
			'entry_fee'              =>  $this->request->getVar('entry_fee', FILTER_SANITIZE_STRING),
			'disease_symptoms'          =>  $this->request->getVar('disease_symptoms', FILTER_SANITIZE_STRING),
			'other_fee'              =>  $this->request->getVar('other_fee', FILTER_SANITIZE_STRING),
			'patient_room'           =>  $this->request->getVar('patient_room', FILTER_SANITIZE_STRING),
			'patient_email'          =>  $this->request->getVar('patient_email'),
			'status'                 => 'Active'
		];
		$status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $data);
		if ($status) {
			$this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
		}
		//echo "<pre>";print_r($status);die;
		return redirect()->to(base_url() . 'Admin/manage_revisited_patients');
	}


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Generate pdf format bill for patient provided payments 
	* @retuns: Internally used function
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_revisit_patient_bill($patient_id, $payid, $apmtid, $puid) {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url() . "/Login");
		} 
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('admin_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
		
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
				//	'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->admin_uid,
				];
		$this->updt_status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		//$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
		$data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Admin/generate_revisit_apmt_pat_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Admin/generate_revisit_apmt_pat_bill', $data);
		}
	} //function - Closed



	public function doctors_available_slots($pid){
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->data['patient_id'] = (int) $pid; //0: for non-logged-in Patient or Patient ID
		$this->args_dr = [
			'login_acc' => 1,
			'status' => 'Active' // OR Verified
		];
		$this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
		$this->args = [
			'dr_available' => 1,
			'appointment_date' => date('Y-m-d') //Current date
		];
		$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args_order_by('doctor_slots', $this->args); 
		//echo "<pre>"; print_r($this->data);die;
		return view('Admin/show_dr_available_slots', $this->data);
	} // Function - Closed




   /* @params: Get every Dr. available slots set by the Doctors
	* @desc: Non-Ajax Called
	* @retuns: 
	* @copyrights: Neoark Software Pvt Ltd
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/

	public function pick_slots() {
		if (!(session()->has('admin_session_uid'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		$this->data['dr_name'] = $this->request->getGet('dr_name');
		$this->data['dr_id'] = $this->request->getGet('dr_id');		
		$this->data['dt'] = $this->request->getGet('dt');
		$this->data['pick_slt'] = $this->request->getGet('pick_slt');
		$this->data['slot_id'] = $this->request->getGet('slot_id');

		if(!isset($this->data['dr_id']) || $this->data['dr_id'] == '') {
			$this->session->setTempdata('error', 'Doctor ID is missing.!', 3);
			$this->data['dr_slots'] = array(); //Just for addressing notice about missing $dr_slots array
			$this->data['doctors'] = array();  //Just for addressing notice about missing $doctors array
			return view('Admin/show_dr_available_slots', $this->data);
		}
		else { //Get doctors & Departments for appointment page - //Ref doctor_appointment($id)
			$this->args = [ 'ref_id' => $this->data['dr_id'] ]; //Doctor ID
			$this->data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args);
			//$this->data['department'] = $this->commonForAllModel->fetch_allrecords_bypage('department');
			//$this->session->setTempdata('success', 'Congratullations!, Doctor slots are availble. ', 3);
			return view('Admin/doctor_appointment', $this->data);
		}
	} //Funciton - Closed



	// public function book_appointment() {
	// 	$this->patient_session_id = session()->get('patient_session_id');
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			// 'name'              => 'required|min_length[4]|max_length[20]',
	// 			'name'  => [
	// 				'rules'     => 'required|min_length[4]|max_length[20]',
	// 				'errors'    => [
	// 				'required' => 'Name is mandatory.',
	// 				'min_length' => 'Minimum length is 4.',
	// 				'max_length' => 'Maximum length is 20.'
	// 				],
	// 			],
	// 			'mobile'            => 'required|numeric|exact_length[10]',
	// 			//'symptoms'        => 'required',
	// 			'age'          		=> 'required',
	// 			'gender'          	=> 'required',
	// 			//'email'           => 'required|valid_email',
	// 			'appointment_date'  => 'required',
	// 			'appointment_time'  => 'required',
				
	// 		];
	// 		if(!isset($this->patient_session_id['id']) || $this->patient_session_id['id'] == '') {
	// 			$this->patient_session_id = array();
	// 			$this->patient_session_id['id'] = 0;
	// 			//$this->patient_session_id = new \stdClass; //stdClass object
	// 			//$this->patient_session_id['id']->id = 0;
	// 		}
			
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
	// 				$this->session->setTempdata('error', 'Unexpected serial data', 3);
	// 				return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 		} //Generate Serial - END
	// 		$this->puid = $this->request->getVar('puid',FILTER_SANITIZE_STRING);
	// 		if(!isset($this->puid) || $this->puid == '') { $this->puid = 0; }
	// 		if ($this->validate($rules)) {
	// 			$userdata = [
	// 				'patient_name'		=>  $this->request->getVar('name',FILTER_SANITIZE_STRING),
	// 				'serial'			=>	$this->new_serial,
	// 				'pid'				=>  $this->patient_session_id['id'], //patient auto increment ID
	// 				'patient_email'     =>  $this->request->getVar('email'),
	// 				'country_code'    	=>  $this->request->getPost('country_code',FILTER_SANITIZE_STRING),
	// 				'patient_mobile'    =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
	// 				'age'    			=>  $this->request->getVar('age',FILTER_SANITIZE_STRING),
	// 				'gender'    		=>  $this->request->getVar('gender',FILTER_SANITIZE_STRING),
	// 				'puid'    			=>  $this->puid, //$this->request->getVar('puid',FILTER_SANITIZE_STRING), //Search patient ID
	// 				//'patient_login_id'	=> 	$this->patient_session_id['id'],
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
	// 			if ($status == true) {
	// 				$args = ['id'   => $this->request->getVar('slot_id',FILTER_SANITIZE_STRING)];
	// 				$update_dtarr = array(
	// 					'booked'  		=> 1, //1: Patient booked, 0: Not booked	
	// 					'dr_available'  => 0, //1: Yes, 0: No
	// 					'serial'		=> $this->new_serial,
	// 					'patient_id'	=> $this->patient_session_id['id'],
	// 					'updated_by'	=> $this->patient_session_id['id'] 
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
	// 						return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 					}
	// 					else{
	// 						$this->session->setTempdata('success', 'Appointment has booked successfully !, however, unable to email to Doctor', 3);
	// 						//return view('Home/payment_for_appointment');
	// 						return view('Home/appointment_payment');
	// 						//return redirect()->to(base_url().'Doctor/available_selected_doctor_slots');
	// 					}
	// 				}
	// 				else{
	// 					$this->session->setTempdata('error', 'Unable to book appointment. Please try again', 3);
	// 					return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 				}
	// 			}
	// 			else{
	// 				$this->session->setTempdata('error', 'Sorry ! Unable to book appointment. Please try again', 3);
	// 				return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 			}  
	// 			// Add New Patient - END
	// 		}	
	// 		else { 
	// 			$this->session->setTempdata('error', 'Failed due to missing mandatory fields.!', 3);
	// 			return redirect()->to(base_url().'Admin/available_selected_doctor_slots');
	// 			// return redirect()->to(base_url().'Home/index');
	// 		}
	// 	}
	// }  // Function - Closed


	// /*********************** Appointent START ************************/

	/* @param: Function for Book patient/ self appointment 
     * @description: search_patient details from the list of the patients.
     * @date: 21st June, 2023
     * @modify: March 11th, 2025
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
	public function book_appointment() {
		if(!(session()->has('admin_session_uid')) || !(session()->has('admin_session_id'))) { 
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Login");
		}
		
		$this->admin_session_uid = session()->get('admin_session_uid');
		$this->admin_session_id = session()->get('admin_session_id');

		$this->is_new_patient = 1; //For addressing notices -New Patient
		$userdata = array(); //Just for addressing notices

		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			
			$rules = [
				'patient_name'  => [
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
					'rules'     => 'required|min_length[4]|max_length[50]',
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
			$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date'); 
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
					'patient_name'		=>  $this->request->getPost('patient_name',FILTER_SANITIZE_STRING),
					'serial'			=>	$this->new_serial,
					'pid'				=>  $this->admin_session_id, //patient auto increment ID
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
					'created_by'        =>  $this->admin_session_uid,
				];
				
				//echo "<pre>";print_r($userdata);die;
				$last_insrt_apmt_id = $this->commonForAllModel->Insertdata_return_id('booked_doctor_appointment', $userdata);

				if((int) $last_insrt_apmt_id > 0) { 
					$userdata['last_insrt_apmt_id'] = $last_insrt_apmt_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$userdata['appointment_date'] 	= $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING); //For sending email

					$userdata['appointment_time'] 	= date('h:i:s'); //For sending email 
					$userdata['slot_id'] 	= $this->slot_id; //Updating `booked_doctor_appointemnt` during receiving appointemnt fee
					$userdata['uid'] 	= $this->admin_session_uid; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
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


   /* @param: 
	* @desc: Get patient details based on every 3 entered charaters
	* @param: Used to populate and filling book appoint form already visited patients 
	* @date: 
	* @modify: March 11th, 2025 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	*/
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

	/* @params: Show Doctor availability slots based on selected Doctor and Date
	 * @desc: Available slots may be booked/take appointments by patients 
	 * (and even Doctors, Frontdesk admin and Administrator as well)
	 * @use: Book Appointment @Home page
	 * @author: Neoarks Team
	 * @date: May 30th, 2023
	 * @modify:NOV/07/2023
	 */
	public function available_selected_doctor_slots() { //get slots based on selected Doctor and Date
		$this->tot_dr = 0; //Just for addressing notices
		$this->data['doctors'] = array(); //Just for addressing notices
		
		$this->args = [
			'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
			'doctor_id' => $this->request->getGet('dr_id'),
			'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
		];
		$this->data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
		$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);	
		if($this->data['dr_slots'] === false ) { //Need to apply it	
		}
		return view('Admin/show_dr_available_slots', $this->data);
	}// Function - Closed



	/* @params: Show Doctor availability slots based on selected Doctor and Date
	 * @desc: Available slots may be booked/take appointments by patients 
	 * (and even Doctors, Frontdesk admin and Administrator as well)
	 * @use: Book Appointment @Home page
	 * @author: Neoarks Team
	 * @date: March 1at, 2025
	 * @modify:
	 */
	public function available_selected_doctor_slots_neo() { //get slots for selected Doctor and Date
		$this->tot_dr = 0; //Just for addressing notices
		$this->data['doctors'] = array(); //Just for addressing notices
		
		$this->args_dr = [
			'login_acc' => 1,
			'status' => 'Active' // OR Verified
		];
		$this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
		//$this->data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
		$this->selected_date = $this->request->getGet('selected_date');
		if(!$this->request->getGet('selected_date')) {
			$this->selected_date = date('Y-m-d'); //Current Date
		}
		$this->args = [
			'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
			'doctor_id' => $this->request->getGet('dr_id'),
			'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
		];
		
		$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);	
		if($this->data['dr_slots'] === false ) { //Need to apply it	
		}
		return view('Admin/show_dr_available_slots', $this->data);
	}// Function - Closed
	


	//Grouped slots based on doctor IDs
    // foreach($this->data['dr_slots_arr'] as $this->dr_arr) {
    //     $this->doctor_id = $this->dr_arr->doctor_id;
    //     $this->start_end_slot = $this->dr_arr->start_end_slot;

    //     // If doctor_id doesn't exist in the grouped array, create an entry
    //     if (!isset($this->data['dr_slots'][$this->doctor_id])) {
    //         $this->data['dr_slots'][$this->doctor_id] = [
    //             'doctor_name' => $this->dr_arr->doctor_name,
    //             'education' => $this->dr_arr->education,
    //             'dr_specialization' => $this->dr_arr->dr_specialization,
    //             'profile_pic' => $this->dr_arr->profile_pic,
    //             'gender' => $this->dr_arr->gender,
    //             'doctor_fee' => $this->dr_arr->doctor_fee,
    //             'doctor_id' => $this->dr_arr->doctor_id,
    //             'id' => $this->dr_arr->id,
    //             'slots' => []
    //         ];

    //     }

    //     // Add the current slot to the doctor group
    //     $this->data['dr_slots'][$this->doctor_id]['slots'][] = [
    //         'start_end_slot'=> $this->start_end_slot,
    //         'slot_id'       => $this->dr_arr->id,
    //         'doctor_id'     => $this->dr_arr->doctor_id
    //     ];
    // }


    //Output the result
    // echo '<pre>';
    // print_r($this->data['dr_slots']);
    // echo '</pre>';
} //Class - Closed 