<?php 
namespace App\Controllers;

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


	public function index() { //Admin Login - START
		$data = [];
		if ($this->request->getMethod() == 'get') { //Render - Login Form
			return view('Login/index', $data);
		}
		if ($this->request->getMethod() == 'post') { //Login authorization - START
			$rules = [
				'email'  => 'required|valid_email',
				// 'password'   => 'required|min_length[6]|max_length[20]'
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password  is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
			];
			if ($this->validate($rules)) {
				$email     = $this->request->getVar('email');
				$this->passwd  = $this->request->getVar('password');
				
				$throttler = \Config\Services::throttler();
				$allow     = $throttler->check("login", 4, MINUTE);
				if ($allow) {
					$userdata = $this->loginModel->verifyEmail($email, $this->passwd, 'admin_users');
					if($userdata === false) {
						$data['error']  = 'Account does not exist. Please register your account.';
					}
					else if(!isset($userdata['password']) || $userdata['password'] == '') {
						$data['error']  = 'Password index is not found';
					}
					else if(password_verify($this->passwd, $userdata['password'])) {
						if(isset($userdata['status'])) {
							if ($userdata['status'] == 'Active') {
								if ($userdata['level'] === '1') { //1: for "admin" role
									$loginInfo  = [
										'uniid'       => $userdata['uid'],
										'level'       => $userdata['level'],
										'browser'     => $this->getUserAgent(),
										'ip'          => $this->request->getIPAddress(),
										'login_time'  => date('Y-m-d h:i:s'),
										'login_date'  => date('Y-m-d h:i:s')   
									];
									$login_activity_id = $this->loginModel->saveLoginInfo($loginInfo);
									if ($login_activity_id) {
										$this->session->set('loggedin_info', $login_activity_id);
										$this->session->set('admin_session_uid', $userdata['uid']);
										$this->session->set('admin_session_id', $userdata['id']);
										$this->session->set('loggedin_usr_name', $userdata['username']);
										$this->session->set('loggedin_usr_id', $userdata['id']);
										
										//$this->session->set('admin_session_uid', $userdata['uid']);
										
										$this->session->set('admin_usr_name', $userdata['username']);
										$this->session->set('admin_session_pic', $userdata['profile_pic']); 
									}
									else {
										$data['error']  = 'Session could not set..!';
									}
									$this->session->set('admin_session_uid', $userdata['uid']);
									return redirect()->to(base_url().'/Admin');
								}
								else {
									$data['error']  = 'Unauthorized user access level ';
								}
							} 
							else{
								$data['error']  = 'Your Account is not Active. Please Contect to Admin';
							}
						}
						else{
							$data['error']  = 'Account does not exist or unexpected status. Please try again';
						}
					}
					else { 
						$data['error']  = 'Email or Password is not matched. Please try again.';
						return view('Login/index', $data);
					}
				}
				else {
					$data['error']  = 'Max No. of failed Login Attempt, Try Again a Few Minutes';
					return view('Login/index', $data);
				}
			}
			else{
				$this->session->setTempdata('error', 'Validation failed', 3);
				$data['validation']  = $this->validator;
				return view('Login/index', $data);
			}
		} //Google Gmail Login Query Software Developer by Neoark Team
		else { // new added
			return view('Login/index', $data);
		}
	} //function - Closed



	public function blood_bank_accountant() {
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		return  view('Admin/Account/blood_bank_accountant');
	} //function - Close



	public function create_blood_acc(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		$data  = [];
			$data['validation'] = null;
			if($this->request->getMethod() == 'post'){
				$rules = 
				$rules = [
					'accountant_name'  => [
						'rules'     => 'required',
						'errors'    => [
							'required' => 'Blood Bank Accountent Name is mandatory'
						],
					],
					'email'  => [
						'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
						'errors'     => [
							'required'     => 'Blood Bank Email is Required',
							'valid_email'  => 'Enter Valid Email',
							'is_unique'    => 'Email is already existing,Please register with another email.',
						],
					],
					'mobile' =>[
						'rules'  =>  'required|numeric|exact_length[10]',
						'errors' => [
							'required'     => 'Mobile Number is Required',
							'exact_length' => 'Exact Length Should be 10 Digit',
						],
					],
					'gender' =>[
						'rules'  =>  'required',
						'errors' => [
							'required'     => 'Gender is Required'
						],
					],

					'password'    => [
						'rules'   => 'required|min_length[6]|max_length[20]',
						'errors'  => [
							'required'  => 'Password is Required',
							'min_length'  => 'Minimum length is 6',
							'max_length'  => 'maximum length is 20',
						],
					],
					'confirm_password'  => [
						'rules'      => 'required|matches[password]',
						'errors'     => [
							'required'  => 'Confirm Password is Required',
						],
					],
				];
				if($this->validate($rules)) {
					$uid  = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'       => $this->request->getVar('accountant_name',FILTER_SANITIZE_STRING),
						'mobile'        => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'gender'        => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
						'email'           => $this->request->getVar('email'),
						'password'        => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'uid'             => $uid,
						'level'           => '2',
						'status'          => 'Active',
						'created_date'    => date('Y-m-d h:i:s')
					];
				
					// $status = $this->adminModel->Insertdata('register_all_users',$userdata);
					$status = $this->loginModel->Insertdata('blood_bank_users',$userdata);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! blood bank account has created  successfully !', 3);
					}else{
						$this->session->setTempdata('error', 'Sorry ! Unable to create account. Please try again?', 3);
					}  
					return redirect()->to(base_url().'/Admin/manage_blood_acc');
				}
				else{
					$data['validation'] = $this->validator;
				}
			}
		return view('Admin/Account/blood_bank_accountant', $data);
	}
	

	public function create_doctor(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}else{
			$args = [ 'login_acc' => 0 ];	 //	0: Login Acc NOT yet, 1: Login Acc YES		
			$data['doctor'] = $this->loginModel->fetch_rec_by_args('doctor', $args);
			if($data['doctor'] === false) {
				$this->session->setTempdata('error', 'No record found. Please add new doctor first.', 3);
				return redirect()->to(base_url().'Admin/add_doctor');
			} 
			else if(is_array($data)) { return view('Admin/Account/create_doctor', $data); }
			else {
				$this->session->setTempdata('error', 'Unexpected result set', 3);
				return redirect()->to(base_url().'Admin/manage_doctor');
			}
		}
	}

	public function get_doctor_data($id){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		$args = [
			'id'  => $id
		];
		// $data = $this->adminModel->fetch_rec_by_args('doctor', $args);
		$data = $this->loginModel->fetch_rec_by_args('doctor', $args);
		
		if($data === false) {
			$this->session->setTempdata('error', 'Sorry ! Record not found', 3);
			return redirect()->to(base_url().'Login/get_doctor_data');
		} 
		else if(is_array($data)) { echo json_encode($data); }
		else {
			$this->session->setTempdata('error', 'Unexpected result set', 3);
			return redirect()->to(base_url().'Login/get_doctor_data');
		}
	}

	public function Create_doctor_account() { 
		$this->admin_uid = session()->get('admin_session_uid');
		if(!isset($this->admin_uid) || $this->admin_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url()."/Login");
		}
		$data  = [];
		$data['validation'] = null;
		if($this->request->getMethod() == 'post') { 
			$rules = [
				'doctor_name'  => [
					'rules'  => 'required',
					'errors'    => [
					'required' => 'Doctor Name is mandatory'
					],
				],
				'doctor_email'  => [
					'rules'   => 'required|valid_email|is_unique[register_all_users.email]',
					'rules'   => 'required',
					'errors'  => [
					'required'     => 'Doctor unique email is required',
					'valid_email'  => 'Enter Valid Email',
					'is_unique'    => 'Email is Already tacken Advice go to Login Other wise Pickup Another Email',
					],
				],
				'gender'  => [
					'rules'   => 'required',
					'errors'  => [
						'required'     => 'Doctor Gender is Required',
					],
				],
				'mobile' =>[
					'rules'  =>  'required|numeric|exact_length[10]',
					'errors' => [
						'required'     => 'Mobile Number is Required',
						'exact_length' => 'Exact Length Should be 10 Digit',
						//'is_unique'    => 'Mobile is already existing. Try with another mobile',
					],
				],

				'password'  => [
					'rules'   => 'required|min_length[6]|max_length[20]',
					'errors'  => [
						'required'  => 'Password is Required',
						'min_length'  => 'Minimum length is 6.',
						'max_length'  => 'Maximum length  is 20.',
					],
				],
				'conf_password'  => [
					'rules'   => 'required|matches[password]',
					'errors'  => [
						'required'  => 'Confirm Password is Required',
					],
				],
			];
			
			if($this->validate($rules)) {
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
				$this->ref_id = $this->request->getVar('selected_name',FILTER_SANITIZE_STRING);
				$this->dr_email = $this->request->getVar('doctor_email');
				$this->insrtData = [
					'username'  => $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING),
					'mobile'    => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
					'gender'    => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
					'email'     => $this->dr_email,
					'password'  => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
					'ref_id'    => $this->ref_id,
					'uid'       => $uid,
					'level'     => '3', //3: Doctors, 2: Accountants, 4: Blood Bank, 5: Donors
					'status'    => 'Active',
					'created_date' 	=> date('Y-m-d'),
					'created_at' 	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->admin_uid,
				];
				//echo "<pre>"; print_r($this->insrtData);die;
				$this->updtArgs = [ 'doctor_email' 	=> $this->insrtData['email'] ];  //Don't use $uid It's value become differ here
				$this->updtData = [ 
					'login_acc' => 1, // 0: Login Acc NO, 1: Login Acc YES
					'updated_at'  => date('Y-m-d h:i:s'),
					'updated_by'	=> $this->admin_uid,
				]; 
				//if (issest($this->is_unique) == true)
				//$this->is_unique() = $this->adminModel->verifyAccountantEmail('register_all_users', $this->dr_email);
				
				$this->is_unique = $this->adminModel->verifyAccountantEmail('register_all_users', $this->dr_email);
				if($this->is_unique === false) { //Unique email (not existing)
					$status = $this->adminModel->Insert_with_update('register_all_users', $this->insrtData, 'doctor', $this->updtData, $this->updtArgs);
					if ($status == true) { //Customization - START
						$this->session->setTempdata('success', 'Congratulation ! account created  successfully !', 3);
						return redirect()->to(base_url().'Admin/manage_doctor');
					}
					else {
						$this->session->setTempdata('error', 'Sorry ! Unable to create Doctor account. Please try again', 3);
						return redirect()->to(base_url().'Admin/add_doctor');
					}
				}
				else {
					$this->session->setTempdata('error', 'Sorry ! doctor email is already existing', 3);
					return redirect()->to(base_url().'Login/create_doctor');
				}
			}
			else { $data['validation'] = $this->validator; 
				// $this->session->setTempdata('error', 'Validation failed ! Pleaes talk to admin', 3);
				// return redirect()->to(base_url().'Login/create_doctor'); 
			}
		}
		else { 
			$data['validation'] = $this->validator; 
			// $this->session->setTempdata('error', 'Un-supported request method', 3);
			// return redirect()->to(base_url().'Admin/doctor');
		}
		
		$data['doctor'] = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
		if($data['doctor'] === false) {
			$this->session->setTempdata('error', 'Sorry ! Record not found. Please new doctor first', 3);
			//return redirect()->to(base_url().'Login/Create_doctor_account'); //????
			return redirect()->to(base_url().'Admin/doctor');
		} 
		else if(is_array($data)) { 
			return view('Admin/Account/create_doctor', $data); //Redirect current/same page
			//return view('Admin/manage_doctor', $data); 
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result set', 3);
			//return redirect()->to(base_url().'Login/Create_doctor_account');  //????
			return redirect()->to(base_url().'Admin/manage_doctor');
		}
	} //Function - Closed



	public function create_med_account(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		return view('Admin/Account/create_med_account');
	}

	public function create_medical_acc_account(){
		if (!(session()->has('admin_session_uid'))) {
			return redirect()->to(base_url()."/Login");
		}
		$data  = [];
			$data['validation'] = null;
			if($this->request->getMethod() == 'post'){
				$rules = [
					'med_acc_name'  => [
						'rules'     => 'required|min_length[2]|max_length[20]',
						'errors'    => [
						'required' => 'Accountant Name is mandatory',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.',
						//'is_unique' => 'Bed number is an already existing. Please add another ward'
						],
					],

					'med_acc_email'  => [
						'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
						'errors'     => [
							'required'     => 'Accountant Email is Required',
							'valid_email'  => 'Enter Valid Email',
							'is_unique'    => 'Email is already existing. Please register with another email.',
						],
					],
					'mobile' =>[
						'rules'  =>  'required|numeric|exact_length[10]',
						'errors' => [
							'required'     => 'Mobile Number is Required',
							'exact_length' => 'Exact Length Should be 10 Digit',
						],
					],
					'gender' =>[
						'rules'  =>  'required',
						'errors' => [
							'required'     => 'Gender is Required'
							
						],
					],

					'password'    => [
						'rules'   => 'required|min_length[6]|max_length[20]',
						'errors'  => [
							'required'  => 'Password is Required',
							'min_length'  => 'Minimum length is 6.',
							'max_length'  => 'Maximum length is 20.',
						],
					],
					'conf_password'  => [
						'rules'      => 'required|matches[password]',
						'errors'     => [
							'required'  => 'Confirm Password is Required',
						],
					],
				];
				if($this->validate($rules)){
					$uid  = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'      => $this->request->getVar('med_acc_name',FILTER_SANITIZE_STRING),
						'mobile'        => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'gender'        => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
						'email'           => $this->request->getVar('med_acc_email'),
						'password'        => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'uid'             => $uid,
						'level'           => '2',
						'status'          => 'Active',
						'created_date'    => date('Y-m-d h:i:s')
					];
					// $status = $this->adminModel->Insertdata('register_all_users',$userdata);
					$status = $this->loginModel->Insertdata('register_all_users',$userdata);
					if ($status == true) {
						$this->session->setTempdata('success', 'Congratulation ! Accountant Account Created  Successfully !', 3);
					}else{
						$this->session->setTempdata('error', 'Sorry ! Unable to Create Account  Try Again ?', 3);
					}  
					return redirect()->to(base_url().'/Admin/manage_medical_acc');
				}else{
					$data['validation'] = $this->validator;
				}
			}
		return view('Admin/Account/create_med_account', $data);
	}


	


    public function getUserAgent(){
		$agent = $this->request->getUserAgent(); //predefine method
		if ($agent->isBrowser()) { $currentAgent  = $agent->getBrowser(); }
		else if ($agent->isRobot()) { $currentAgent  = $this->agent->robot(); }
		else if ($agent->isMobile()) { $currentAgent  = $agent->getMobile(); }
		else { $currentAgent  = 'Unidentified User Agent'; }
		return $currentAgent;
	} //function - Closed


	public function Logout() {
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		return redirect()->to(base_url()."/Login");
	}

} //class - Closed