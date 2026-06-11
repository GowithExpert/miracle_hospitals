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
use \App\Models\Register_model;
use \App\Models\DoctorModel; //Customized 
use \App\Models\CommonForAllModel; //Customized 
	
class Patients_login extends BaseController
{
	public $loginModel;
	public $session;
	public $doctorModel;
	public $registerModel;
	public $commonForAllModel; //Custom


	public function __construct(){
		helper(['form','date']);
		$this->loginModel = new Login_model();
		$this->session   = session();
		$this->email = \Config\Services::email();
		$this->doctorModel = new DoctorModel();  //Customized  
		$this->registerModel = new Register_model();
		$this->commonForAllModel = new CommonForAllModel(); //Custom
	}


   /* @params: Render patient login form
	* @desc: 
	* @use: 
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	#public function index() { 
	public function signup() {
		$data = [];
		if ($this->request->getMethod() != 'get') { //Load login form 
			$data['error']  = 'Unexpected request method. Please report a bug.';
			return view('Home/index', $data);
		}
		return view('Patients/patient_signup'); 
	} //function closed


   /* @params: Patient Login
	* @desc: 
	* @use: 
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function patient_login(){
		$data = [];
		if ($this->request->getMethod() == 'get') { //Load login form 
			return view('Patients/patient_login', $data);
		}
		else if ($this->request->getMethod() == 'post') { //Patient login form submission -- START
			$rules = [
				'email'      => 'required|valid_email',
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
			];
			if ($this->validate($rules)) {
				$email     = $this->request->getVar('email');
				$password  = $this->request->getVar('password');
					
				$throttler = \Config\Services::throttler();
				//$allow     = $throttler->check("test", 4, MINUTE);
				$allow     = $throttler->check("best", 4, MINUTE);
				if ($allow) {
					$userdata = $this->loginModel->verifyAccountantEmail('patients_login', $email);
					if($userdata === false) {
						$data['error']  = 'Account does not exist. Please register your account.';
					}
					else if(!isset($userdata['password']) || $userdata['password'] == '') {
						$data['error']  = 'Password index is not found';
					}
					else if(password_verify($password, $userdata['password'])) {
						if ($userdata['status'] == 'Active') {
							if ($userdata['level'] === '4') { //4 for Patient & Blood Bank,3: Doctor, 2: Accountant, 5: Donor 
								$loginInfo  = [
									'uniid'       => $userdata['uid'],
									'browser'     => $this->getUserBrowserInfo(),
									'ip'          => $this->request->getIPAddress(),
									'login_date'  => date('Y-m-d'),
									'login_time'  => date('Y-m-d h:i:s')
								];
								$login_activity_id = $this->loginModel->saveLoginInfo($loginInfo);
								
								if ($login_activity_id) {
									$this->session->set('loggedin_info', $login_activity_id);
									
									$this->session->set('patient_session_uid', $userdata['uid']);
									$this->session->set('patient_session_id', $userdata['id']); //Store Patient ID In Session
									$this->session->set('patient_session_name', $userdata['username']);
								}
								$this->session->set('patient_session_pic', $userdata['profile_pic']);
								return redirect()->to(base_url().'Patients/index'); //Originally called
							}
							else {
								$data['error']  = 'Email & password do Not Matched  Invalid';
							}
						} else {
							$data['error']  = 'Admin verification is pending. Please contact admin or try later';
						}	
					} 
					else {
						$data['error']  = 'Email or Password is not matched. Please try again.';
					}
					return view('Patients/patient_login', $data);
				} 
				else {
						$data['error']  = 'Max No. of failed Login Attempt, Try Again a Few Minutes';
						return view('Patients/patient_login', $data);
				}
			} else {
				$data['validation']  = $this->validator;
				$data = $data['validation']->getErrors();
			}
		}
		else { //Expected 'get' nor 'post' method only
			$data['error']  = 'Unexpected request method. Please report a bug.';
			return view('Patients/patient_login', $data);
		}
	} //function - Closed


   /* @params: Patient Login
	* @desc: 
	* @use: 
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function create_patients_account_old() {
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'username'          => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
					'errors'    => [
					'required' => 'User name is mandatory.',
					'min_length' => 'Minimum length is 4.',
					'max_length' => 'Maximum length is 20.'
					],
				],

				// 'password'          => 'required|min_length[6]|max_length[20]',
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'confirm_password'  => 'required|matches[password]',
				'email'  		=> [
					'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
					'errors'     => [
						'required'     => 'Email is required',
						'valid_email'  => 'Please enter valid email', //email format check
						'is_unique'    => 'Email is already existing. Please try another email',
					],
				],
				'mobile'    =>	[
					'rules'      => 'required|numeric|exact_length[10]|is_unique[register_all_users.mobile]',
					'errors'     => [
						'required'     => 'Mobile is required',
						'exact_length'  => 'Mobile length must 10 digits only', 
						'is_unique'    => 'Mobile is already existing. Please try another mobile',
					],
				],
			];
			if($this->validate($rules)){
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'         => $this->request->getVar('username',FILTER_SANITIZE_STRING),
						'email'            => $this->request->getVar('email'),
						'password'         => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'mobile'           => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'uid'              => $uid,
						'gender'           => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
					   'country_code'     => $this->request->getVar('country_code',FILTER_SANITIZE_STRING), 
						'level'            => '4', //4: Role is Patient
						//'status'           => 'InActive',
						'status'           => 'Active',
						'created_date'     => date('Y-m-d h:i:s')
					];
					$insert_data = $this->registerModel->Insertdata('patients_login',$userdata);
					if ($insert_data) {
						$to        = $this->request->getVar('email');
						$subject   = 'Account Activation Link  - Hospital Management System';
						$message   = 'Hi ' .$this->request->getVar('username',FILTER_SANITIZE_STRING).",<br><br>Thanks Your Account Created Successfully, Please Click the below Link to Activate your Account <br><br>"
						   ."<a href='".base_url()."/Patients_login/Activate_account/".$uid."' target='_blank'>Activate  Now</a><br><br>Thanks<br> "
						   . DEV_TEAM;

						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL',  DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Account Created Successfully!' );
							//return redirect()->to(current_url());
							return redirect()->to(base_url().'/Patients_login/login');
						}else{
							$data = $this->email->printDebugger(['headers']);
							$this->session->setTempdata('success', 'Account created successfully, However, unable to send activation link. '. ISU_SUPPORT . DEV_AUTHOR);
							//return redirect()->to(current_url());
							return redirect()->to(base_url().'/Patients_login/login');
						}   
					}else{
						$this->session->setTempdata('error', 'Sorry Unable to Create an Account, Try Again ?',3);
						return redirect()->to(current_url());
					}
			}else{
				$data['validation'] =  $this->validator;
			}
		}
		return view('Patients/patients_register', $data);
	}

	public function create_patients_account() {
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'username'          => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[6]|max_length[20]|is_unique[register_all_users.username]',
					'errors'    => [
					'required' => 'User name is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.',
					'is_unique'    => 'Name is already existing. Please try another name',
					],
				],

				// 'password'          => 'required|min_length[6]|max_length[20]',
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'confirm_password'  => 'required|matches[password]',
				'email'  		=> [
					'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
					'errors'     => [
						'required'     => 'Email is required',
						'valid_email'  => 'Please enter valid email', //email format check
						'is_unique'    => 'Email is already existing. Please try another email',
					],
				],
				'mobile'    =>	[
					'rules'      => 'required|numeric|exact_length[10]|is_unique[register_all_users.mobile]',
					'errors'     => [
						'required'     => 'Mobile is required',
						'exact_length'  => 'Mobile length must 10 digits only', 
						'is_unique'    => 'Mobile is already existing. Please try another mobile',
					],
				],
			];
			if($this->validate($rules)){
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'         => $this->request->getVar('username',FILTER_SANITIZE_STRING),
						'email'            => $this->request->getVar('email'),
						'password'         => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'mobile'           => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'uid'              => $uid,
						'gender'           => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
					   'country_code'     => $this->request->getVar('country_code',FILTER_SANITIZE_STRING), 
						'level'            => '4', //4: Role is Patient
						'status'           => 'Active',
						'created_date'     => date('Y-m-d h:i:s'),
						'created_at'     => date('Y-m-d h:i:s')
					];
					//$insert_data = $this->registerModel->Insertdata('patients_login',$userdata);
					$insert_data = $this->commonForAllModel->Insertdata_return_id('patients_login', $userdata);
					$user_id = ['id' => $insert_data];
					$get_uid = $this->commonForAllModel->fetch_rec_by_uid('patients_login', $user_id);
					
					//created_by = uid need to update here
					$update_args = ['id'  => $insert_data];
					$update_data = ['created_by'     => $get_uid->uid];
					$updated_data = $this->commonForAllModel->update_rec_by_args('patients_login', $update_args, $update_data);
					if ($insert_data && $updated_data) {
						$to        = $this->request->getVar('email');
						$subject   = 'Account Activation Link  - Hospital Management System';
						$message   = 'Hi ' .$this->request->getVar('username',FILTER_SANITIZE_STRING).",<br><br>Thanks Your Account Created Successfully, Please Click the below Link to Activate your Account <br><br>"
						   ."<a href='".base_url()."/Patients_login/Activate_account/".$uid."' target='_blank'>Activate  Now</a><br><br>Thanks<br> "
						   . DEV_TEAM;

						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL',  DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Account Created Successfully!' );
							//return redirect()->to(current_url());
							return redirect()->to(base_url().'/Patients_login/login');
						}else{
							$data = $this->email->printDebugger(['headers']);
							$this->session->setTempdata('success', 'Account created successfully, However, unable to send activation link. '. ISU_SUPPORT . DEV_AUTHOR);
							//return redirect()->to(current_url());
							return redirect()->to(base_url().'/Patients_login/login');
						}   
					}else{
						$this->session->setTempdata('error', 'Sorry Unable to Create an Account, Try Again ?',3);
						return redirect()->to(current_url());
					}
			}else{
				$data['validation'] =  $this->validator;
			}
		}
		//return view('Patients/patients_register', $data);
		return view('Patients/patient_signup', $data);
	} //function - Closed
	

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
				'email'         => [
                   // 'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
				   'rules'      => 'required|valid_email',
                    'errors'     => [
                        'required'     => 'Please enter email',
                        'valid_email'  => 'Please enter valid email', //email format check
                       // 'is_unique'    => 'Email is already existing. Please try another email',
                    ],
                ],
			];

			if ($this->validate($rules)) {
				$this->email_to = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);

				$this->token = bin2hex(random_bytes(16)); //Generate unique bin2hex number
				
				$this->is_unique = $this->commonForAllModel->is_single_record('patients_login', 'email', $this->email_to);
				if($this->is_unique === false) {
					$this->session->setTempdata('error', 'Sorry ! Email is not registered with us. Please try again', 3);
					return redirect()->to(current_url());
				}
				$this->updt_args = ['email' 		=> $this->email_to];
				$this->updt_data_arr = [
						'reset_pass_token'	=> $this->token,
						'updated_at'		=> date('Y-m-d H:i:s')
					];
				$this->save_status = $this->registerModel->update_rec_by_args('patients_login', $this->updt_args, $this->updt_data_arr );
				if ($this->save_status) {
					//$email_to = $this->email;
					$subject  = 'Reset Password Link';
					$message  = 'Hi,'
								. '<br><br>'
								.'Your Reset Password request has been Received. Please Click '
								.'the below Link to reset your Password.<br><br>'
								.'<a href="'.base_url().'/Patients_login/reset_password/'.$this->token.'">Click Here to Reset Password</a>'
								.'<br>Thanks <br> '
								. DEV_TEAM . '<br>'
								.DEV_AUTHOR . ' at ' . WEBSITE . '<br>';

					echo $message; die; //need to commented-in it
					$this->email->setTo($this->email_to);
					$this->email->setFrom('ADMIN_EMAIL', 'Software Developer & Blogger');
					$this->email->setSubject($subject);
					$this->email->setMessage($message);
					if ($this->email->send()) {
						$this->session->setTempdata('success', 'Reset Password link has sent to your registered email. Please check & verify with in 15 minutes', 3);
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
					$this->session->setTempdata('error', 'Sorry ! Unable to update. Please try again', 3);
					return redirect()->to(current_url());
				}
			}
			else {
				$this->session->setTempdata('error', 'Mandatory validation failed', 3);
				$data['validation'] = $this->validator;
			}
		}
		return view('Patients/forget_password', $data);
	} //function - Closed
	

	/* @params: Function for reset password
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
		if (!empty($this->token)) {
			$userdata = $this->commonForAllModel->get_records_for_token('patients_login', $this->token);
			if (!empty($userdata)) { 
				$check_exp_time = $this->checkExpiry_time($userdata['updated_at']);
				//$data['error'] = 'Unable to Find User Account';
				if ($check_exp_time) { 
					if ($this->request->getMethod() == 'post') { 
					 	$rules = [
							'new_password' => [
								'label'  => 'Password',
								'rules'  => 'required|min_length[6] |max_length[20]',
							],
							'confirm_password' => [
								'label'  => 'Confirm Password',
								'rules'  => 'required|matches[new_password]'
							],
						];
						if ($this->validate($rules)) {
							$password   = password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT);
							//$update_pass = $this->adminModel->update_password('admin_users', $this->token, $password);
							$this->updt_args = [
								'reset_pass_token'	=> $this->token //So that once used reset password token may not use again	
							];
							$this->updt_data_arr = [
								'password'		=> $password,
								'updated_at'	=> date('Y-m-d H:i:s'),
								'reset_pass_token'	=> '' //So that once used reset password token may not use again
							];
							$update_pass = $this->registerModel->update_rec_by_args('patients_login', $this->updt_args, $this->updt_data_arr );
							if ($update_pass) {
								$this->session->setTempdata('success', 'Password updated successfully',3);
								return redirect()->to(base_url().'Patients_login/login');
							}
							else {
								$this->session->setTempdata('error', 'Sorry Unable to update password. Please try again !',3);
								//return redirect()->to(current_url());
							}
						}
						else {
							$data['validation'] = $this->validator;
							$this->session->setTempdata('error', 'Validation failed !',3);
						}
					}
					else { //Render to reset password page
						return view('Patients/reset_acc_password', $data);
					}
				}
				else {
					//$data['error']  = 'Reset Password Link was Expired';
					$this->session->setTempdata('error', 'Reset password link has expired.', 3);
					//return view('Patients_login/forget_password', $data);
				}
			} 
			else {
				//$data['error'] = 'Unable to Find User Account';
				$this->session->setTempdata('error', 'Unauthorized or Reset password link has expired !',3);
				//return view('Patients_login/forget_password', $data);
			}
		}
		else {
			//$data['error']  = 'Sorry ! Unauthorized token access';
			$this->session->setTempdata('error', 'Unauthorized or Reset password link has expired !',3);
		}
		return redirect()->to(base_url() . 'Patients_login/forget_password');
	} //function - closed


	public function checkExpiry_time($time){
		$update_time   = strtotime($time);
		$current_time  = time();
		$timeDiff      = ($current_time - $update_time)/60;
		if ($timeDiff < 900) { return true; }
		else { return false; }
	} //function - Closed

   
   /* @param: Function for patient login
	* @desc: Login function for patients
	* @use:
	* @author: Neoark Team
	* @date: July 15th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/

	public function verify_expiry_time($regTime){
		$ourTime = now();//load time helper he will get current time stamp
		$regTime = strtotime($regTime);
		$diffTime =  $regTime - $ourTime;
		if (3600 > $diffTime) {
			return true;
		}else{
			return false;
		}
	} //function - Closed


	public function getUserBrowserInfo(){
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

	public function Logout(){
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		return redirect()->to(base_url()."Patients_login/login");
	} //function - closed

} //class - closed