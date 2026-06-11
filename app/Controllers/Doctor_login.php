<?php 
namespace App\Controllers; //Namespace must at very first/top line

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


	
class Doctor_login extends BaseController
{
	public $loginModel;
	public $doctorModel;
	
	public $session; 
	public $commonForAllModel;
	
	public $registerModel;
	public function __construct(){
		helper(['form','date']);
		$this->loginModel = new Login_model();
		$this->doctorModel = new DoctorModel();  //Customized  
		$this->session   = session();
		$this->email = \Config\Services::email();
		$this->registerModel = new Register_model();
		$this->commonForAllModel = new CommonForAllModel(); //Custom
	}

	public function  index(){ 
		return view('Account/doctor_register'); 
	}

	/* @params: $time
	* @desc: Function check expiry time
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
	} //Function - Closed


	/* @params:
	 * @desc: Render email input form: with "get" request method & Send a link over email: with "post" request method
	 * @use: Doctor forget password
 	 * @return:
	 * @author: Neoarks Team
	 * @date: 18th August,2023
	 * @modify
	*/
	public function forget_password(){
		$data = [];
		if ($this->request->getMethod() == 'get') {
			return view('Doctor/forget_password', $data); //render email input form
		}
		else if ($this->request->getMethod() == 'post') {
			$rules = [
				'email'         => [
                   // 'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
				   'rules'      => 'required|valid_email',
                    'errors'     => [
                        'required'     => 'Please enter email',
                        'valid_email'  => 'Please enter valid email', //email format check
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
				$this->save_status = $this->doctorModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
				if ($this->save_status) {
					//$email_to = $this->email;
					$subject  = 'Reset Password Link';
					$message  = 'Hi,'
								. '<br><br>'
								.'Your Reset Password request has been Received. Please Click '
								.'the below Link to reset your Password.<br><br>'
								.'<a href="'.base_url().'/Doctor_login/reset_password/'.$this->token.'">Click Here to Reset Password</a>'
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
		else {
			$this->session->setTempdata('error', 'Unexpected request method', 3);
			return redirect()->to(current_url());
		}
	} //function - Closed

   /* @params: $token = null
	* @desc: Function reset password
	* @use: Doctor login....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function reset_password($token = null) {
		$this->token = $token;
		$data = [];
		if (!empty($this->token)) {
			//$userdata = $this->doctorModel->get_records_for_token('register_all_users', $this->token);
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
							$update_pass = $this->doctorModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
							if ($update_pass) {
								$this->session->setTempdata('success', 'Password updated successfully',3);
								return redirect()->to(base_url().'/Doctor_login/doctor_login');
							}
							else {
								$this->session->setTempdata('error', 'Sorry, Unable to update password. Please try again !',3);
								return redirect()->to(current_url());
							}
						}
						else {
							$data['validation'] = $this->validator;
							$this->session->setTempdata('error', 'Validation failed ! Please try again',3);
						}
					}
					else { //Redirect to reset password page
						return view('Doctor/reset_acc_password', $data);
					}
				}
				else {
					//$data['error']  = 'Reset password link has expired';
					$this->session->setTempdata('error', 'Reset password link has expired.', 3);
				}
			} else {
				//$data['error'] = 'Unable to find user account';
				$this->session->setTempdata('error', 'Unable to find user account ! Please try again',3);
			}
		}
		else {
			//$data['error']  = 'Sorry ! Unauthorized user access';
			$this->session->setTempdata('error', 'Unauthorized or Reset password link has expired !',3);
		}
		return view('Doctor/forget_password', $data);
	} //function - closed


   /* @params: 
	* @desc: Function get user agent
	* @use: Doctor login....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function getUserAgent(){
		$agent = $this->request->getUserAgent(); //predefine method
		if ($agent->isBrowser()) { $currentAgent  = $agent->getBrowser(); }
		else if ($agent->isRobot()) { $currentAgent  = $this->agent->robot(); }
		else if ($agent->isMobile()) { $currentAgent  = $agent->getMobile(); }
		else { $currentAgent  = 'Unidentified User Agent'; }
		return $currentAgent;
	} // Function - Closed



   /* @param: 
	* @desc: For Doctor Login
	* @use:
	* @author: Neoark Team
	* @date: May 11th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/
	public function doctor_login() {
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
					} 
					elseif (password_verify($password, $this->data['userdata']['password'])) {
						if ($this->data['userdata']['status'] == 'InActive') {
							$this->data['error'] = 'Admin verification is pending or account has blocked temporarily.';
						} 
						elseif ($this->data['userdata']['is_del'] == 1) {
							$this->data['error'] = 'Sorry! Your account has been deleted!';
						} 
						elseif ($this->data['userdata']['status'] == 'Active' && $this->data['userdata']['is_del'] == 0) {
							if ($this->data['userdata']['level'] === '3') {
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

								$this->session->set('doctor_session_uid', $this->data['userdata']['uid']);
								$this->session->set('doctor_session_id', $this->data['userdata']['id']);
								$this->session->set('doctor_session_name', $this->data['userdata']['username']);
								//$this->session->set('profile_pic', $this->data['userdata']['profile_pic']);
								$this->session->set('doctor_session_pic', $this->data['userdata']['profile_pic']);
								// Redirect to Doctor page
								return redirect()->to(base_url().'/Doctor');
							} 
							elseif (in_array($this->data['userdata']->level, $userroles)) {
								// $userroles: defined in Config/Constants.php
								$this->data['error'] = 'You are not authorized to access the account.';
							} 
							else { $this->data['error'] = 'Unexpected user level. Please talk to admin';}
						}
					} 
					else { $this->data['error'] = 'Email or Password is not matched. Please try again.'; }
				} 
				else { $this->data['error'] = 'AChieved Max No. login attempts. Please try later';}
			} 
			else {
				$this->session->setTempdata('error', 'Validation failed', 3);
				$this->data['validation'] = $this->validator;
			}
		}
		return view('Doctor/doctor_login', $this->data);
	} //function - Closed


	/* @param: Function for create docor login account
	* @desc: When doctor create there account
	* @use:
	* @author: Neoark Team
	* @date: May 11th, 2023
	* @modify:
	* @copyrights: Neoark Software Team
	*/
	public function create_doc_account() {
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'username'          => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[6]|max_length[20]|is_unique[register_all_users.username]',
					'errors'    => [
					'required' => 'Username  is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.',
					'is_unique'    => 'Name is already existing. Please try another name',
					],
				],
				'gender'            => 'required',
				'email'  		=> [ //Unique/Not existin Email validation check - for `register_all_users` tbl
					'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
					'errors'     => [
						'required'     => 'Email is required',
						'valid_email'  => 'Please enter valid email', //email format check
						'is_unique'    => 'Email is already existing. Please try another emails',
					],
				],

				'education'  => [
						'rules'     => 'required',
						'errors'    => [
						'required' => 'Doctor education is mandatory.'
					],
				],

			   'doctor_fee'  => [
						'rules'     => 'required',
						'errors'    => [
						'required' => 'Doctor fee is mandatory.'
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
				// 'password'          => 'required|min_length[6]|max_length[20]',
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required'  => 'Username  is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
				'confirm_password'  => 'required|matches[password]',
			];
			$dr_tbl_rules = [ //Unique/Not existin Email validation check - for `doctor` tbl
				'email'  		=> [
					'rules'      => 'required|valid_email|is_unique[doctor.doctor_email]',
					'errors'     => [
						'required'     => 'Email is required',
						'valid_email'  => 'Please enter valid email', //email format check
						'is_unique'    => 'Email is already existing. Please try another email.',
					],
				],
			];

			if($this->validate($rules) && $this->validate($dr_tbl_rules) ){
				$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					$userdata = [
						'username'  => $this->request->getVar('username',FILTER_SANITIZE_STRING),
						'doctor_fee'      	=>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
						'education'      	=>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
						'gender'    => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
						'email'     => $this->request->getVar('email'),
						'password'      => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'mobile'		=> $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'uid'           => $uid,
						//'photo' 		=> 'dr.default-pic.jpg',
						'level'         => '3', //3: Doctors, 2: Accountants, 4: Blood bank & Patients, 5:Donors
						'status'        => 'InActive',
						'created_date'  => date('Y-m-d h:i:s')
					];
					$insert_data = $this->doctorModel->Insertdata('register_all_users', $userdata);
					//$insert_data = $this->registerModel->add_doc_by_admin('register_all_users',$userdata);
					if ($insert_data) {
						$to        = $this->request->getVar('email');
						$subject   = 'Account Activation Link  - Hospital Management System';
						$message   = 'Hi ' .$this->request->getVar('username',FILTER_SANITIZE_STRING).",<br><br>Thanks Your Account Created Successfully, Please Click the below Link to Activate your Account <br><br>"
						   ."<a href='".base_url()."/Doctor_login/Activate_account/".$uid."' target='_blank'>Activate  Now</a><br><br>Thanks<br> " 
						   . DEV_TEAM; 
						
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL', DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Account created successfully. Please Activate Your Account within an hour');
							//return redirect()->to(current_url()); //redirect current/same page url
							return redirect()->to(base_url().'Doctor_login/doctor_login');
						}else{
							$data = $this->email->printDebugger(['headers']);
							$this->session->setTempdata('success', 'Account created successfully, However unable to send activation link. '. ISU_SUPPORT . DEV_AUTHOR);
							//return redirect()->to(current_url()); //redirect current/same page url
							return redirect()->to(base_url().'Doctor_login/doctor_login');
						}   
					}else{
						$this->session->setTempdata('error', 'Sorry unable to create an account, Please try again',3);
						//return redirect()->to(current_url());
						return redirect()->to(base_url().'Doctor_login/doctor_login');
					}
			}
			else {
				$data['validation'] =  $this->validator;
			}
		}
		return  view('Account/doctor_register', $data); //Render form
 	} //function - Closed

	 /* @param: Function for doctor logout
	 * @desc: When doctor wants to logging out 
	 * @use:
	 * @author: Neoark Team
	 * @date: May 11th, 2023
	 * @modify:
	 * @copyrights: Neoark Software Team
	 */
	public function Logout_doctor() { //Referenced: Accountant_login/Logout_accountant
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		return redirect()->to(base_url()."/Doctor_login/doctor_login");
	} // Function - Closed

} //Class - Closed