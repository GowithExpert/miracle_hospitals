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
use \App\Models\CommonForAllModel; //Custom


class Accountant_login extends BaseController
{
	public $loginModel;
	public $session;
	public $registerModel;
	public $doctorModel;
	public $commonForAllModel;

	public function __construct(){
		helper(['form','date', 'Patient']);
		$this->loginModel = new Login_model();
		$this->doctorModel = new DoctorModel();  //Customized
		$this->session   = session();
		$this->email = \Config\Services::email();
		$this->registerModel = new Register_model();
		$this->commonForAllModel = new CommonForAllModel();
	}

	/* @params: 
	* @desc: Index function of accountant login (Medical Accuntant)
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function index() {
		return view('Account/accountant_account');
		// return view('Blood_bank/blood_bank_login');
	}// Function Closed
 
	/* @params: 
	* @desc: Function for create an account for Accountant
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function create_acc_account(){
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'username'       => [
					'rules'      => 'required|min_length[6]|max_length[20]|is_unique[register_all_users.username]',
                    'errors'     => [
                        'required'     => 'username is required',
                        'is_unique'    => 'username is already existing. Please try another username',
						'min_length'    => 'Minimum length should be 6.',
						'max_length'    => 'Maximum length should be 20.',
                    ],
                ],
				'gender'            => 'required',
			 	//'email'             => 'required|valid_email|is_unique[register_all_users.email]',
			 	'email'         => [
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
				//'password'          => 'required|min_length[6]|max_length[20]',
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password  is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'confirm_password'  => 'required|matches[password]',
			];

			if($this->validate($rules)){
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'  => $this->request->getVar('username',FILTER_SANITIZE_STRING),
						'gender'    => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
						'email'     => $this->request->getVar('email'),
						'password'     => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'mobile'          => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'uid'             => $uid,
						'level'           => '2',
						'status'          => 'InActive',
						'created_date'   => date('Y-m-d h:i:s')
					];
					$insert_data = $this->registerModel->Insertdata('register_all_users', $userdata);
					if ($insert_data) {
						$to        = $this->request->getVar('email');
						$subject   = 'Account Activation Link  - Hospital Management System';
						$message   = 'Hi ' .$this->request->getVar('username',FILTER_SANITIZE_STRING).",<br><br>Thanks Your Account Created Successfully, Please Click the below Link to Activate your Account <br><br>"
						   ."<a href='".base_url()."/Accountant_login/Activate_account/".$uid."' target='_blank'>Activate  Now</a><br><br>Thanks<br> " 
						   . DEV_TEAM; 
						
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL',  DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/flexionSoftware.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Account Created Successfully, Please Activate Your Account with in 1 hours' );
							return redirect()->to(base_url().'/Accountant_login/accountant_login');
						}else{
							$data = $this->email->printDebugger(['headers']);
							$this->session->setTempdata('success', 'Account created successfully. However, unable to send activation Link. '. ISU_SUPPORT . DEV_AUTHOR);
							return redirect()->to(base_url().'/Accountant_login/accountant_login');
						}   
					}else{
						$this->session->setTempdata('error', 'Sorry.! unable to create an account. Please try again', 3);
						return redirect()->to(current_url());
					}
			}else{
				$data['validation'] =  $this->validator;
			}
		}
		return  view('Account/accountant_account', $data);
	} // Function - Closed

	/* @params: 
	* @desc: Function for Activate Account
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function Activate_account($unid = null){
		$data = [];
		if(!empty($unid)){
			$userdata = $this->registerModel->verifyUnid($unid);
			if ($userdata) {
				$expiry_time = $this->verify_expiry_time($userdata->created_date);
				if ($expiry_time) {
					if ($userdata->status === 'InActive') {
						$status = $this->registerModel->updateStatus($unid);
						if ($status == true) {
							$data['success'] = 'Account Activated Successfully';
						}
						$this->session->setTempdata('success', 'Account Activated Successfully Login Your Account');
						return redirect()->to(base_url().'/Accountant_login/accountant_login');
					}else{
						$data['success'] = 'Your Account is Already Activated';
					}
				}else{
					$data['error'] = 'Sorry Activation Link was Expired Try Again!';
				}
			}else{
				$data['error'] = 'Sorry Unable to Process Activate Your Account Request ?';
			}
		}else{
			$data['error'] = 'Sorry Unable to Process Your Request Your Not Elegible here Sorry';
		}
		return view('Account/Activate_acc_account', $data);
	} // Function - closed

	/* @params: 
	* @desc: Function for verify expiry time
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function verify_expiry_time($regTime){
		$added_time = strtotime($regTime);
		$diffTime = verify_db_detatime_to_current_time_stamp($added_time);
		if ($diffTime < 25) {
			return true;
		}else{
			return false;
		}
	} // FUnction - closed

	

	/* @params: 
	* @desc: Function for Login in accountant
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/

	//Accountant Account Login Script
	public function accountant_login() { //site login 
	$this->data = [];
	// Site login
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'email'    => 'required|valid_email',
				// 'password' => 'required|min_length[6]|max_length[20]'
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
				$email    = $this->request->getVar('email');
				$password = $this->request->getVar('password');
	
				$throttler = \Config\Services::throttler();
				$allow     = $throttler->check("test", 4, MINUTE);
	
				if ($allow) {
					$this->data['userdata'] = $this->doctorModel->verifyAccountantEmail('register_all_users', $email);
	
					if ($this->data['userdata'] === false) {
						$this->session->setTempdata('error', 'Login account does not exist or invalid password.', 3);
						return redirect()->to(current_url());
					}
	
					if (!isset($this->data['userdata']['password']) || $this->data['userdata']['password'] == '') {
						$this->data['error'] = 'Password index is blank or missing';
					} //Accontant email verification - for accountant login
					elseif (password_verify($password, $this->data['userdata']['password'])) {
						if ($this->data['userdata']['status'] == 'InActive') {
							$this->data['error'] = 'Admin verification is pending or account has blocked temporarily.';
						} elseif ($this->data['userdata']['is_del'] == 1) {
							$this->data['error'] = 'Sorry! Your account has been deleted!';
						} elseif ($this->data['userdata']['status'] == 'Active' && $this->data['userdata']['is_del'] == 0) {
							if ($this->data['userdata']['level'] === '2') {
								// Level: 3 For Doctor login: 2: Accountants, 4: Patients & Blood Bank, 5: Donors
								$loginInfo = [
									'uniid'      => $this->data['userdata']['uid'],
									'level'      => $this->data['userdata']['level'],
									'browser'    => $this->getUserAgent(),
									'ip'         => $this->request->getIPAddress(),
									'login_time' => date('Y-m-d h:i:s'),
									'login_date' => date('Y-m-d h:i:s')
								];
	
								$login_activity_id = $this->doctorModel->saveLoginInfo($loginInfo);
								if ($login_activity_id) {
									$this->session->set('loggedin_info', $login_activity_id);
								}
								
								$this->session->set('accountant_session_uid', $this->data['userdata']['uid']);
								$this->session->set('accountant_session_id', $this->data['userdata']['id']);
								$this->session->set('accountant_session_name', $this->data['userdata']['username']);
								$this->session->set('accountant_session_pic', $this->data['userdata']['profile_pic']);
								// Redirect to Doctor page
								return redirect()->to(base_url().'/Medical_Accountant');
							} elseif (in_array($this->data['userdata']->level, $userroles)) {
								// $userroles: defined in Config/Constants.php
								$this->data['error'] = 'You are not authorized to access the account.';
							} else {
								$this->data['error'] = 'Unexpected user level. Please talk to admin';
							}
						}
					} else {
						$this->data['error'] = 'Email or Password is not matched. Please try again.';
					}
				} else {
					$this->data['error'] = 'Max No. of failed Login Attempt, Try Again a Few Minutes';
				}
			} else {
				$this->session->setTempdata('error', 'Validation failed', 3);
				$this->data['validation'] = $this->validator;
			}
		}
	
		return view('Account/accountant_login', $this->data);
	} // Function - Closed
	


	/* @params: 
	* @desc: Function get user agent
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
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
	} // Function - Closed

	//Accountant Account Login Script End	


	/* @params: 
	* @desc: Render email input form: with "get" request method & Send a link over email: with "post" request method
	* @use: Accountant
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
				
				$this->is_unique = $this->commonForAllModel->is_single_record('register_all_users', 'email', $this->email_to);
				if($this->is_unique === false) {
					$this->session->setTempdata('error', 'Sorry ! Email is not registered with us. Please try again', 3);
					return redirect()->to(current_url());
				}
				$this->updt_args = ['email' 		=> $this->email_to];
				$this->updt_data_arr = [
						'reset_pass_token'	=> $this->token,
						'updated_at'		=> date('Y-m-d H:i:s')
					];
				
				$this->save_status = $this->registerModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
				if ($this->save_status) {
					$subject  = 'Reset Password Link';
					$message  = 'Hi,'
								. '<br><br>'
								.'Your Reset Password request has been Received. Please Click '
								.'the below Link to reset your Password.<br><br>'
								.'<a href="'.base_url().'/Accountant_login/reset_password/'.$this->token.'">Click Here to Reset Password</a>'
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
			}
		}
		return view('Account/forget_password');
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
	} // Function - Closed


	/* @params: 
	* @desc: Forget/Reset Accountat password
	* @use: Accoutant >> Forget password
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function reset_password($token = null) {
		$this->token = $token;
		
		$data = [];
		if (!empty($this->token)) { 
			$userdata = $this->commonForAllModel->get_records_for_token('register_all_users', $this->token);
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
							$this->updt_args = [
								'reset_pass_token'	=> $this->token //So that once used reset password token may not use again	
							];
							$this->updt_data_arr = [
								'password'		=> $password,
								'updated_at'	=> date('Y-m-d H:i:s'),
								'reset_pass_token'	=> '' //So that once used reset password token may not use again
							]; 
							$update_pass = $this->registerModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
							if ($update_pass) {
								$this->session->setTempdata('success', 'Password updated successfully',3);
								return redirect()->to(base_url().'/Accountant_login/accountant_login');
							}
							else {
								$this->session->setTempdata('error', 'Sorry, Unable to update password. Please try again !',3);
								return redirect()->to(current_url());
							}
						}
						else {
							$data['validation'] = $this->validator;
							$this->session->setTempdata('error', 'Validation failed !',3);
						}
					}
					else { //Redirect to reset password page
						return view('Account/reset_acc_password', $data);
					}
				}
				else {
					//$data['error']  = 'Reset password link has expired';
					$this->session->setTempdata('error', 'Reset password link has expired.', 3);
				}
			} else {
				//$data['error'] = 'Unable to find user account !';
				$this->session->setTempdata('error', 'Unable to find user account ! Please try again',3);
			}
		}
		else {
			//$data['error']  = 'Sorry ! Unauthorized access';
			$this->session->setTempdata('error', 'Unauthorized or Reset password link has expired !',3);
		}
		return view('Accountant_login/forget_password', $data);
	} //function - closed


	/* @params: 
	* @desc: Function for logout from accountant
	* @use: Accountant....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function Logout_accountant() { //Accountant Logout
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		return redirect()->to(base_url()."/Accountant_login/accountant_login");
	} // Function - CLosed
}
