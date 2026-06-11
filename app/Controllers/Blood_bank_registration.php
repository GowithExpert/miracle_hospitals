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

class Blood_bank_registration extends BaseController
{
	public $loginModel;
	public $session;
	public $registerModel;
	public $adminModel;
	public function __construct(){
		helper(['form','date', 'Patient']);
		$this->loginModel = new Login_model();
		$this->session   = session();
		$this->adminModel  = new Admin_Model();
		$this->email = \Config\Services::email();
		$this->registerModel = new Register_model();
	}

	public function index(){
		// return view('Blood_bank/index');
		return view('Blood_bank/index_login');
	}

	public function registration(){ //Donor's registration and...
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				// 'username'          => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Username is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.',
					],
				],
				'email'             => 'required|valid_email|is_unique[patients_login.email]',
				'gender'            => 'required',
				'mobile'            => 'required|numeric|exact_length[10]',
				// 'password'          => 'required|min_length[6]|max_length[20]',
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
				$img = $this->request->getFile('donor_photo');
				 if ($img->isValid() &&  !$img->hasMoved()) {
					 	 $newName = $img->getRandomName();
	                	$img->move(FCPATH . 'uploads/donor_image', $newName); 
	                	$doc_img = $img->getName();
						$userdata = [
							'username'         => $this->request->getVar('username',FILTER_SANITIZE_STRING),
							'email'            => $this->request->getVar('email'),
							'password'         => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
							'mobile'           => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
							'gender'           => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
							'uid'              => $uid,
							'image'            => $doc_img,
							'level'            => '5', //5: Donors, 4: Blood Bank, 3: Doctors, 2: Accountants
							'status'           => 'Active',
							'created_date'     => date('Y-m-d h:i:s')
						];
					$insert_data = $this->registerModel->Insertdata('blood_bank_users',$userdata);
					if ($insert_data) {
						$this->session->setTempdata('success', 'Account Created Successfully ?',3);
						return redirect()->to(base_url().'/Frontdesk_login/login_account');   
					}else{
						$this->session->setTempdata('error', 'Sorry Unable to Create an Account, Try Again ?',3);
						return redirect()->to(current_url());
					}

				}else{
					//echo "I am here";die;
					echo $img->getErrorString(). " " .$img->getError();
				}

			}else{
				$data['validation'] =  $this->validator;
			}
		}
		return view('Blood_bank/index', $data);
	}

	// //Blood Bank Admin Registration Section  Start
	// public function admin_registration(){
	// 	return view('Blood_bank/Admin/admin_registration');
	// }

	//public function admin_registerd(){
	public function blood_bank_user_registration(){ //renamed - on 15 nov, 2023
		$data  = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				//'username'          => 'required|min_length[4]|max_length[20]',
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
				// 'email'             => 'required|valid_email|is_unique[register_all_users.email]',
				'email'         => [
                    'rules'      => 'required|valid_email|is_unique[register_all_users.email]',
                    'errors'     => [
                        'required'     => 'Email is required',
                        'valid_email'  => 'Please enter valid email', //email format check
                        'is_unique'    => 'Email is already existing. Please try another email',
                    ],
                ],
				// 'mobile'            => 'required|numeric|exact_length[10]',
				// 'password'          => 'required|min_length[6]|max_length[20]',
				'mobile'    =>	[
					'rules'      => 'required|numeric|exact_length[10]|is_unique[register_all_users.mobile]',
					'errors'     => [
						'required'     => 'Mobile is required',
						'exact_length'  => 'Mobile length must 10 digits only', 
						'is_unique'    => 'Mobile is already existing. Please try another mobile',
					],
				],
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
						'email'     => $this->request->getVar('email'),
						'password'     => password_hash($this->request->getVar('password',FILTER_SANITIZE_STRING),PASSWORD_DEFAULT),
						'mobile'          => $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
						'gender'          => $this->request->getVar('gender',FILTER_SANITIZE_STRING),
						'uid'             => $uid,
						'level'           => '4',
						'status'          => 'InActive',
						'created_date'   => date('Y-m-d h:i:s')
					];
					$insert_data = $this->registerModel->Insertdata('register_all_users',$userdata);
					if ($insert_data) {
						$to        = $this->request->getVar('email');
						$subject   = 'Account Activation Link  - Hospital Management System';
						$message   = 'Hi ' .$this->request->getVar('username',FILTER_SANITIZE_STRING).",<br><br>Thanks Your Account Created Successfully, Please Click the below Link to Activate your Account <br><br>"
						   ."<a href='".base_url()."/Accountant_login/Activate_account/".$uid."' target='_blank'>Activate  Now</a><br><br>Thanks<br> "
						   . DEV_TEAM;
						
						$this->email->setTo($to);
						$this->email->setFrom('ADMIN_EMAIL', DEV_TEAM );
						$this->email->setSubject($subject);
						$this->email->setMessage($message);
						$filepath = 'public/assets/images/logo3.png';
						$this->email->attach($filepath);
						if ($this->email->send()) {
							$this->session->setTempdata('success', 'Account created successfully, Please Activate Your Account with in 1 hours' );
							//return redirect()->to(current_url()); //redict current/same page
							return redirect()->to(base_url() . "Blood_bank/blood_bank_login");
						}else{
							$data = $this->email->printDebugger(['headers']);
							$this->session->setTempdata('success', 'Account created successfully, However, unable to send activation Llink. '. ISU_SUPPORT . DEV_AUTHOR);
							//return redirect()->to(current_url()); //redict current/same page
							return redirect()->to(base_url() . "Blood_bank/blood_bank_login");
						}   
					}else{
						$this->session->setTempdata('error', 'Sorry Unable to Create an Account, Try Again ?',3);
						return redirect()->to(current_url());
					}
			}else{
				$data['validation'] =  $this->validator;
			}
		}
		return view('Blood_bank/Admin/blood_bank_user_registration', $data);
	}
	//Blood Bank Admin Registration Section  End




	public function login_account() { //Donor's login & ...
		$data = [];
		if($this->request->getMethod() !== 'post') {
			return view('Blood_bank/login_account', $data);
		}
		//site login 
		elseif ($this->request->getMethod() == 'post') { 
			$rules = [
				'email'      => 'required|valid_email',
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
				$password  = $this->request->getVar('password');
					
				$throttler = \Config\Services::throttler();
				$allow     = $throttler->check("test", 4, MINUTE);
				if ($allow) {
					$userdata = $this->loginModel->verifyAccountantEmail('blood_bank_users',$email);
					if ($userdata) {
						$pass_verify = password_verify($password, $userdata['password']);
						if ($pass_verify) {
							if ($userdata['status'] == 'Active') {
								if ($userdata['level'] === '5') { 
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
										$this->session->set('bldbnk_session_uid', $userdata['uid']);
										$this->session->set('bldbnk_session_id', $userdata['id']);
										$this->session->set('bldbnk_session_name', $userdata['username']); //Loggedin- user name

									}
									$this->session->set('bldbnk_session_uid', $userdata['uid']);
									return redirect()->to(base_url().'Frontdesk');
								}
								else{
									$data['error']  = 'Email & password do Not Matched  Invalid';
								}
							}else{
								$data['error']  = 'Your Account is Not Activated by Admin Please Contect Admin other wise wait Some time !';
							}	
						}else{
							$data['error']  = 'Sorry Wrong password entered for that Email';
						}
					}else{
						$data['error']  = 'Email & password does not Exists';
					}
				}else{
						$data['error']  = 'Max No. of failed Login Attempt, Try Again a Few Minutes';
				}
			}else{
				$data['validation']  = $this->validator;
			}
		} //if loop - Closed


			
			//Google Singin - START: //Google Gmail Login Query Software Developer Neoarks Team
			// include_once APPPATH . "Libraries/vendor/autoload.php";
			// $google_client = new \Google_Client();
			// $google_client->setClientId(GGL_CLNT_ID);			//Google Client ID - for Google sign-in
			// $google_client->setClientSecret(GGL_CLNT_SECRET);	//Google Client Secret - for Google sign-in
			// $google_client->setRedirectUri(base_url(). '/Blood_bank_registration/login_account');
			// $google_client->addScope('email');
			// $google_client->addScope('profile');
			
			// if ($this->request->getVar('code')) {
			// 	$token = $google_client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
			// 	if (!isset($token['error'])) {
			// 		$google_client->setAccessToken($token['access_token']);
			// 		$this->session->set('access_token',$token['access_token']);
			// 		//to get the profile data
			// 		$google_service = new \Google_Service_Oauth2($google_client);
			// 		$gdata = $google_service->userinfo->get();
			// 		if ($this->loginModel->google_user_exists($gdata['id'])) {
			// 			# update
			// 			$userdata = [
			// 				'first_name'  => $gdata['given_name'],
			// 				'last_name'   => $gdata['family_name'],
			// 				'email'       => $gdata['email'],
			// 				'profile_pic' => $gdata['picture']
			// 			];
			// 			$this->loginModel->updateGoogleUser($userdata, $gdata['id']);
			// 			$this->session->set('google_user', $userdata);
			// 			return redirect()->to(base_url() . "/Blood_bank_donor");


			// 		}else{
			// 			//insert
			// 			$userdata = [
			// 				'oauth_id'    => $gdata['id'],
			// 				'first_name'  => $gdata['given_name'],
			// 				'last_name'   => $gdata['family_name'],
			// 				'email'       => $gdata['email'],
			// 				'profile_pic' => $gdata['picture']
			// 			];
			// 			$this->loginModel->createGoogleUser($userdata);
			// 			$this->session->set('google_user', $userdata);
			// 			return redirect()->to(base_url() . "/Blood_bank_donor");

			// 		}
			// 	}
			// }
			// if (!$this->session->get('access_token')) {
			// 	$data['loginButton'] = $google_client->createAuthUrl(); 
			// }
			//Google Singin - END 
		//return view('Blood_bank/login_account', $data);
		//return view('Blood_bank/Donor/blood_donor_login');
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


	// public function checkExpiry_time($time){
	// 	$update_time   = strtotime($time);
	// 	$current_time  = time();
	// 	$timeDiff      = ($current_time - $update_time)/60;
	// 	if ($timeDiff < 900) {
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}
	// }

	public function Logout_account(){ //Donor Logout
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		//return redirect()->to(base_url()."Blood_bank_donor/blood_donor_login"); //Custom - Donor login page
		return redirect()->to(base_url()."Blood_bank_registration/login_account"); //Default - Donor login page
	} //function - Closed

} //class - Closed

