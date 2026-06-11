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
use \App\Models\DoctorModel; //Customized
//use \App\Models\CommonModel; //Custom
use \App\Models\BloodBankModel; //Custom
use \App\Models\CommonForAllModel; //Custom

class Blood_bank extends BaseController {
	
	public $loginModel;
	public $session;
	public $registerModel;
	public $adminModel;
	//public $commonModel; //Custom
	public $bloodBankModel; //Custom
	public $commonForAllModel;

	public function __construct(){
		helper(['form','date', 'Patient','text']);
		$this->doctorModel = new DoctorModel();  //Customized
		$this->loginModel = new Login_model();
		$this->session   = session();
		$this->adminModel  = new Admin_Model();
		$this->email = \Config\Services::email();
		//$this->commonModel = new CommonModel(); //Custom
		$this->registerModel = new Register_model();
		$this->bloodBankModel = new BloodBankModel();
		$this->commonForAllModel = new CommonForAllModel();
	}

	/* @params: 
	* @desc: Function for index
	* @use: blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
		public function index() {
			if (!(session()->has('bldbnk_session_uid'))) {
				return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
			}else{
				$this->bldbnk_uid = session()->get('bldbnk_session_uid');
				$data['loggedin_usr'] = $this->registerModel->getLoggedInUserData($this->bldbnk_uid, 'blood_bank_users');
				
				$args = [ 'status'   => 'Active' ];
				$data['blood_available'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
				$data['donors'] = $this->commonForAllModel->fetch_allrecords_bypage('buy_donor_blood');
				$data['donor_blood_sale'] = $this->commonForAllModel->fetch_allrecords_bypage('donor_blood_sale');
				$data['has_blood_sale'] = $this->commonForAllModel->fetch_allrecords_bypage('hospital_blood_sale');
			
				$data['donor_details'] = $this->registerModel->get_image_by_args('blood_donor', $args, 5);
				return view('Blood_bank/Admin/dashboard', $data);
			}
		} // FUnction Closed

	/*@param: Logged-in user's `uid` used from session 
    * @desc: Upload Profile pic
    * @use: View Profile
    * @return:
    * @author: Neoarks Team
    * @date: 24th November, 2023
    * @modify
    */
	//public function upload_profile_pic($file_id) { 
		public function upload_profile_pic() { 
			// if (!(session()->has('bldbnk_session_uid'))) {
			// 	return redirect()->to(base_url() . "/Login");
			// } 

			if (!(session()->has('bldbnk_session_uid')) && !(session()->has('bldbnk_session_id'))) {
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
			$this->bldbnk_uid = session()->get('bldbnk_session_uid'); //Loggedin User uid
			$this->bldbnk_id = session()->get('bldbnk_session_id'); //Loggedin User uid

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
				$args = [ 'id'  => $this->bldbnk_id ];


				$this->old_data = $this->adminModel->fetch_rec_by_args('register_all_users', $args);
				
				if(isset($this->old_data[0]->profile_pic)) {

					if(file_exists(FCPATH . 'uploads/accounts/bloodbank/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/accounts/bloodbank/' . $this->old_data[0]->profile_pic);
					} //else - Not needed
				} //else - Not needed	
	
				$this->random_name = $this->profile_pic->getRandomName();
				if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
					$this->profile_pic->move(FCPATH . 'uploads/accounts/bloodbank', $this->random_name);// Move the uploaded file to the destination folder
				
					
					$this->user_data_arr = [
						'profile_pic' 	  =>  $this->random_name,//$this->file_name,
						'updated_at'      =>  date('Y-m-d H:i:s'),
						'updated_by'      =>  $this->bldbnk_id,
					];
					$args = ['id'	=> $this->bldbnk_id]; //Need update model function in place of Insertdata - 
					$status = $this->adminModel->update_rec_by_args('register_all_users', $args, $this->user_data_arr);
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
						'message' => 'Unable to move the uploaded Photo'
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
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url() . "/Blood_bank/blood_bank_login");
		}
		else {
			$this->bldbnk_uid = session()->get('bldbnk_session_uid');
			if ($this->request->getMethod() == 'get') {
				$this->data['profile_record']  = $this->registerModel->getLoggedInBloodBankData($this->bldbnk_uid);
				if($this->data['profile_record'] === false) {
				$this->session->setTempdata('error', 'No Record Found', 3); 
				return redirect()->to(base_url() . 'Blood_bank/view_profile' , $this->data);}
				else {return view('Blood_bank/view_profile', $this->data);}
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Blood_bank/view_profile', $this->data);
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
	public function update_profile() {
        if (!(session()->has('bldbnk_session_uid'))) {
            return redirect()->to(base_url() . "/Blood_bank/blood_bank_login");
        }
        else {
            $this->bldbnk_uid = session()->get('bldbnk_session_uid');
			$this->data = [];
			$this->data['validation'] = null;
			$rule = [
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
					$this->args = [ 'uid'  => $this->bldbnk_uid, ];
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
						'updated_by'    => $this->bldbnk_uid,
					];
					//echo "<pre>"; print_r($this->user_data_arr);die;
					$this->data['profile_updt_status'] = $this->adminModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
					if($this->data['profile_updt_status'] === false) {
						$this->result_arr = array(
							'status'    => false,
							'error'	 	=> true, //error: `true` whereever status is false with SQL err 
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
						'error'	 	=> true, //error: `true` whereever status is false with SQL err 
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
					'error'	 	=> false, //error: `false` whereever status is false with SQL err 
					'code'      => 200,
					'message'   => 'Oops.!, Mandatory validation failed.!',
					'data'      => $this->data,
				);
				return json_encode($this->result_arr);
			} 
        } //else -loop closed
    } //function -  closed

	/* @params: 
	* @desc: Function for get user agent
	* @use: Blood bank....
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
	} // FUnction - Closed
	

	/* @params: 
	* @desc: Function for logout from blood bank
	* @use: Blood Bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function Logout_blood_bank() {
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			// $this->loginModel->updateLogoutTime($login_activity_id);
			$this->registerModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		return redirect()->to(base_url()."/Blood_bank/blood_banklogin");
	} // Function - Closed

	/* @params: 
	* @desc: Function for Login in Blood bank
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function blood_banklogin() {
		$this->data = [];
	
		// Site login
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'email'    => 'required|valid_email',
				// 'password' => 'required|min_length[6]|max_length[20]'
				'password'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'Password is  mandatory.',
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
					} elseif (password_verify($password, $this->data['userdata']['password'])) {
						if ($this->data['userdata']['status'] == 'InActive') {
							$this->data['error'] = 'Admin verification is pending or account has blocked temporarily.';
						} elseif ($this->data['userdata']['is_del'] == 1) {
							$this->data['error'] = 'Sorry! Your account has been deleted!';
						} elseif ($this->data['userdata']['status'] == 'Active' && $this->data['userdata']['is_del'] == 0) {
							if ($this->data['userdata']['level'] === '4') {
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
	
								$this->session->set('bldbnk_session_uid', $this->data['userdata']['uid']);
								$this->session->set('bldbnk_session_id', $this->data['userdata']['id']);
								$this->session->set('bldbnk_session_name', $this->data['userdata']['username']);
								$this->session->set('bldbnk_session_pic', $this->data['userdata']['username']);
								// Redirect to Doctor page
								return redirect()->to(base_url().'/Blood_bank/index');
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
	
		return view('Blood_bank/blood_bank_login', $this->data);
	} // Function - Closed


	/* @params: 
	* @desc: Function for add blood 
	* @use: Blood Bank ....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function add_blood(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			return view('Blood_bank/Admin/add_blood');
		}
	} // Function - Closed 


	/* @params: 
	* @desc: Function for add blood group 
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function add_blood_group(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_group'      => 'required',
					'blood_unit'      => 'required',
					'blood_price'      => 'required',
				];
				if ($this->validate($rules)) {
						$userdata = [
							'blood_group'          =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'blood_unit'           =>  $this->request->getVar('blood_unit',FILTER_SANITIZE_STRING),
							'blood_price'           =>  $this->request->getVar('blood_price',FILTER_SANITIZE_STRING),
							'total_blood_price'           =>  $this->request->getVar('total_blood_price',FILTER_SANITIZE_STRING),
							'status'               => 'Active', 
							'created_at'           =>  date('Y-m-d h:i:s')
						];
						// $status = $this->registerModel->Insertdata('blood_group', $userdata);
						$status = $this->registerModel->Insertdata('blood_group', $userdata);
						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation ! Blood Group Added Successfully !', 3);
						}else{
							$this->session->setTempdata('error', 'Sorry ! Unable to Add  Blood Group Try Again ?', 3);
						}  
						return redirect()->to(base_url().'/Blood_bank/manage_blood');
				}else{
					$data['validation'] = $this->validator;
				}
			}
			return view('Blood_bank/Admin/add_blood', $data);
		}
	} // Function - closed

	/* @params: 
	* @desc: Function for manage blood
	* @use: Blood bank ....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function manage_blood(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		else {
			$args = [
				//'status'  => 'InActive',
				'is_del'  => '0',
	
			];
		// $data['blood_group'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_group');
		$data = [
			'blood_group' => $this->registerModel->fetch_rec_by_args_arr('blood_group', $args),
			'pager'     => $this->registerModel->pager
		];
		return view('Blood_bank/Admin/manage_blood', $data);
		}
	}// Function - Closed

	/* @params: 
	* @desc: Function for change blood status
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function change_blood_status($id, $status){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$args = [
				'id'  => $id
			];
			$data = [
				'status'  => $status
			];
			// $status = $this->registerModel->update_rec_by_args('blood_group', $args, $data);
			$status = $this->registerModel->update_rec_by_args('blood_group', $args, $data);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation ! Blood Group status Change Successfully !', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to Change Blood Group Try Again ?', 3);
			}  
			return redirect()->to(base_url().'/Blood_bank/manage_blood');
		}
	} // Function - closed

	/* @params: 
	* @desc: Function for delete blood group
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function delete_blood_group($id){
		$this->bldbnk_uid = session()->get('bldbnk_session_uid');
		if (!isset($this->bldbnk_uid) || $this->bldbnk_uid == '') {
			$this->session->setTempdata('error', 'Blood bank UID is missing !', 3);
			return redirect()->to(base_url() . "/Blood_bank/blood_bank_login");
		}
		$args = [
			'id'  =>  $id,
			'is_del'=> 0
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->bldbnk_uid
		];
		$status = $this->registerModel->update_rec_by_args('blood_group',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Group Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Blood_bank/manage_blood');
	} // Function  - Closed

	/* @params: $id
	* @desc: Function for permanent delete blood group
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function permanent_del_bld_group($id){
		$this->bldbnk_uid = session()->get('bldbnk_session_uid');
		if (!isset($this->bldbnk_uid) || $this->bldbnk_uid == '') {
			$this->session->setTempdata('error', 'Admin UID is missing !', 3);
			return redirect()->to(base_url() . "/Blood_bank/blood_bank_login");
		}
		$args = [
			'id'  =>  $id,
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->bldbnk_uid
		];

		$status = $this->registerModel->update_rec_by_args('blood_group',  $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! record has deleted successfully !', 3);
		} 
		else {
			$this->session->setTempdata('error', 'Sorry ! Unable to deleted. Please try again ?', 3);
		}
		return redirect()->to(base_url() . 'Blood_bank/manage_blood');
	} // Function - Closed


	/* @params: 
	* @desc: Function for donor details
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function donor_details(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$args = [];
		$data = [
			// 'donor_details'  => $this->registerModel->fetch_rec_by_args_with_status('blood_donor', $args),
			'donor_details'  => $this->registerModel->fetch_rec_by_args_with_status('blood_donor', $args),
			'pager'   => $this->registerModel->pager
		];
		if(!isset($data['donor_details']) || $data['donor_details'] == '') { //If no doctor found
			$this->session->setTempdata('error', 'Sorry ! No medicine found. Please add new medince first', 3);
			return redirect()->to(base_url()."Blood_bank/blood_bank_login");
		}
		
		return view("Blood_bank/Admin/donor_details", $data);
	} // FUnction - Closed

	/* @params: 
	* @desc: Function for search donor details
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function search_donor_details(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$args = [
			'blood_group'   => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING)
		];

		// $data['donor_details'] = $this->registerModel->fetch_rec_by_args_by_like('blood_donor',$args);
		$data['donor_details'] = $this->registerModel->fetch_rec_by_args_by_like('blood_donor',$args);
		return view('Blood_bank/Admin/donor_details', $data);
	} // FUnction - Closed


	/* @params: 
	* @desc: Function for filter blood donors
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function filter_blood_donors($filter){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			if ($filter == 'new_donors') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			}else if ($filter == 'old_donors') {
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
			// $data['donor_details'] = $this->registerModel->filter_rec_by_args('blood_donor', $order);
			$data['donor_details'] = $this->registerModel->filter_rec_by_args('blood_donor', $order);
			return view('Blood_bank/Admin/donor_details', $data);
		}
	} // Function - Closed

	/* @params: $id
	* @desc: Function for blood response data
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
  
	//Send Message in Mobile Phone message API KEY : WI3ARZJBQ7I-DUkCeXTniz6ZrPmKYi4FIFUKlbwkS0
	public function blood_response_data($id) {
		$args = [ 'id'  => $id ];
		// $user = $this->registerModel->fetch_rec_by_args('blood_donor', $args);
		$user = $this->registerModel->fetch_rec_by_args('blood_donor', $args);
		
		//Mobile Phone Api Section Start
		//Account details
		$apiKey = urlencode(SMS_API_KEY); //Refer - Contacts.php
		$donor_name = ''; //Just for addressing notices
		$donor_contact = ''; //Just for addressing notices
		$dnr_blood_group = ''; //Just for addressing notices
		if(is_array($user) && isset($user[0]->{'contact_number'})) { $donor_contact = $user[0]->{'contact_number'}; }
		if(is_array($user) && isset($user[0]->{'donor_name'})) { $donor_name = $user[0]->{'contact_number'}; }
		if(is_array($user) && isset($user[0]->{'blood_group'})) { $dnr_blood_group = $user[0]->{'blood_group'}; }

		$message = 'Hey '. $donor_name .', I hope you will be fine. Please be infomed that patients need blood group, '
					. $dnr_blood_group .', in the '. HOSPITAL_NAME .'. Voluentiers are requested to donate blood & contact us at '
					. CONTACT_AT .'for more details. <br/><br/> 
					Thanks from '. HOSPITAL_NAME .' team, if blood donated already.'; //defined in app/Config/Contatants.php

		// Message details
		$donor_name = array(91, $donor_contact); 
		$sender = urlencode(SMS_API_CODE); 	//defined in app/Config/Contatants.php
		$message = rawurlencode($message);
	 
		$donor_name = implode(',', $donor_name);
	 
		//Prepare data for POST request
		$data = array('apikey' => $apiKey, 'numbers' => $donor_name, "sender" => $sender, "message" => $message);
	 
		//Send the POST request with cURL
		$ch = curl_init(SMS_API_URL); //defined in app/Config/Contatants.php
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		
		// Process your response here
		$response_arr = json_decode($response, true);
		if(is_array($response_arr) && isset($response_arr['status'])) {
			if($response_arr['status']) {
				$this->session->setTempdata('success', 'Congratulation.! The blood request raised successfully', 3);
				//return redirect()->to(base_url().'/Blood_bank/donor_details/'.$id);
			}
			else {
				$this->session->setTempdata('error', 'Sorry ! Unable to send  blood Request try Again.', 3);
				//return redirect()->to(base_url().'/Blood_bank/donor_details/'.$id);
			}
		}
		else {
			$this->session->setTempdata('error', 'Unexpected response message status', 3);
			//return redirect()->to(base_url().'/Blood_bank/donor_details/'.$id);
		} 
		return redirect()->to(base_url().'/Blood_bank/donor_details/'.$id);
		//return redirect()->to(base_url().'/Frontdesk/search_donor');
		//Mobile Phone Api Section - Closed
	} //Function - Clsoed 

	
	/* @params: $id
	* @desc: Function for view enquiry
	* @use: Blood bank....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	//Send Message in Mobile Phone message API KEY : WI3ARZJBQ7I-DUkCeXTniz6ZrPmKYi4FIFUKlbwkS0
	public function view_enquiry(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else {
			// $data['req_users'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_requested_user');
			$data['req_users'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_requested_user');
			return view('Blood_bank/Admin/view_enquiry', $data);
		}
	} // Function - Closed 

	public function filter_blood_needed_users($filter){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			if ($filter == 'new_user') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			}else if ($filter == 'old_user') {
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
			// $data['req_users'] = $this->registerModel->filter_rec_by_args('blood_requested_user', $order);
			$data['req_users'] = $this->registerModel->filter_rec_by_args('blood_requested_user', $order);
			return view('Blood_bank/Admin/view_enquiry', $data);
		}
	}

	public function view_enquirygoogleusers(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$args = [
				//'status'  => 'InActive',
				'is_del'  => '0',

			];
			// $data['req_users'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_request_google_user');
			$data = [
				'req_users' => $this->registerModel->fetch_rec_by_args_arr('blood_request_google_user', $args),
				'pager'     => $this->registerModel->pager
			];
			return view('Blood_bank/Admin/view_enquirygoogleusers', $data);
		}
	}

	public function filter_google_user_nedded($filter){
		if ($filter == 'new_user') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			}else if ($filter == 'old_user') {
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
			// $data['req_users'] = $this->registerModel->filter_rec_by_args('blood_request_google_user', $order);
			$data['req_users'] = $this->registerModel->filter_rec_by_args('blood_request_google_user', $order);
			return view('Blood_bank/Admin/view_enquirygoogleusers', $data);
	}

	public function buy_donor_blood(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			// $data['blood_donor'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_donor');
			$data['blood_donor'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_donor');
			return view('Blood_bank/Admin/buy_donor_blood', $data);
		}
	}

	public function get_donor_blood_group($id){
		$args = [ 'id'  => $id ];
		// $data = $this->registerModel->fetch_rec_by_args('blood_donor', $args);
		$data = $this->registerModel->fetch_rec_by_args('blood_donor', $args);
		echo json_encode($data);
	}

	public function buy_blood_donor(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_donors'    => 'required',
					'blood_unit'      => 'required',
					'blood_price'     => 'required',
				];
				if ($this->validate($rules)) {
						$userdata = [
							'blood_donor_id'          =>  $this->request->getVar('blood_donors',FILTER_SANITIZE_STRING),
							'blood_unit'           =>  $this->request->getVar('blood_unit',FILTER_SANITIZE_STRING),
							'blood_price'           =>  $this->request->getVar('blood_price',FILTER_SANITIZE_STRING),
							'blood_group'           =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'selling_price'        => '0', 
							'status'               => 'Active', 
							'created_at'           =>  date('Y-m-d h:i:s'),
							'created_by'           =>  date('Y-m-d h:i:s')
						];
						// $status = $this->registerModel->Insertdata('buy_donor_blood', $userdata);
						$status = $this->registerModel->Insertdata('buy_donor_blood', $userdata);
						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation ! Blood Buy Successfully !', 3);
						}else{
							$this->session->setTempdata('error', 'Sorry ! Unable to Add  Buy Try Again ?', 3);
						}  
						return redirect()->to(base_url().'/Blood_bank/buy_donor_blood');
				}else{
					$data['validation'] = $this->validator;
				}
			}
			// $data['blood_donor'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_donor');
			$data['blood_donor'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_donor');
			return view('Blood_bank/Admin/buy_donor_blood', $data);
		}
	}

	public function manage_donor_blood(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			// $data['donor_blood'] = $this->commonForAllModel->fetch_allrecords_bypage('buy_donor_blood');
			$data['donor_blood'] = $this->commonForAllModel->fetch_allrecords_bypage('buy_donor_blood');
			return view('Blood_bank/Admin/manage_donor_blood', $data);
		}
	}

	public function search_donor_blood(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$args = [
			'blood_group'   => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING)
		];

		// $data['donor_blood'] = $this->registerModel->fetch_rec_by_args_by_like('buy_donor_blood',$args);
		$data['donor_blood'] = $this->registerModel->fetch_rec_by_args_by_like('buy_donor_blood',$args);
		return view('Blood_bank/Admin/manage_donor_blood', $data);
	}

	public function filter_donor_blood($filter) {
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			if ($filter == 'new_donors') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			}else if ($filter == 'old_donors') {
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
			// $data['donor_blood'] = $this->registerModel->filter_rec_by_args('buy_donor_blood', $order);
			$data['donor_blood'] = $this->registerModel->filter_rec_by_args('buy_donor_blood', $order);
			return view('Blood_bank/Admin/manage_donor_blood', $data);
		}
	}

	public function delete_donor_details($id){
		$args = [
			'id'  => $id
		];
		// $status = $this->registerModel->delete_records('buy_donor_blood', $args);
		$status = $this->registerModel->delete_records('buy_donor_blood', $args);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Donor Records Deleted Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Add  Deleted Try Again ?', 3);
		}  
		return redirect()->to(base_url().'/Blood_bank/manage_donor_blood/'.$id);
	}

	public function change_donor_blood($id, $status){
		$args = [ 'id'  => $id ];
		$data = [ 'status'  => $status ];

		// $status = $this->registerModel->update_rec_by_args('buy_donor_blood', $args, $data);
		$status = $this->registerModel->update_rec_by_args('buy_donor_blood', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Donor Records Change Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Change Donor Try Again ?', 3);
		}  
		return redirect()->to(base_url().'/Blood_bank/manage_donor_blood/'.$id);
	}

	public function add_blood_selling_price($id){
		$args = [
			'id'  => $id
		];
		// $data['donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
		$data['donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
		return view('Blood_bank/Admin/add_blood_selling_price', $data);
	}

	public function blood_selling_price($id){
		$args = [
			'id'  => $id
		];
		$data = [
			'selling_price'  => $this->request->getVar('selling_price',FILTER_SANITIZE_STRING)
		];
		// $status = $this->registerModel->update_rec_by_args('buy_donor_blood', $args, $data);
		$status = $this->registerModel->update_rec_by_args('buy_donor_blood', $args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Blood Selling Price Added Successfully !', 3);
		}else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Add Blood Selling Price Try Again ?', 3);
		}  
		//return redirect()->to(base_url().'/Blood_bank/add_blood_selling_price/'.$id);
		return redirect()->to(base_url().'/Blood_bank/manage_donor_blood');
	}

	public function donor_blood_trans(){
		$args = [
			'status'  => 'Active'
		];
		$data['donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
		$data['blood_groups'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
		return view('Blood_bank/Admin/donor_blood_trans', $data);
	}

	public function get_blood_price($id){
		$args = [
			'id'  => $id
		];

		// $data = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
		$data = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
		echo json_encode($data);
	}

	public function donor_blood_transition(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$this->bldbnk_uid = session()->get('bldbnk_session_uid');
		$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_group'    => 'required',
					'blood_price'    => 'required',
					'blood_unit'    => 'required',
					'blood_group_sale'    => 'required',
					// 'username'       => 'required|min_length[3]|max_length[20]',
					'username'  => [
						'rules'     => 'required|min_length[3]|max_length[20]',
						'errors'    => [
						'required' => 'Username is  mandatory.',
						'min_length' => 'Minimum length is 3.',
						'max_length' => 'Maximum length is 20.'
						],
					],
					'mobile'         => 'required|numeric|exact_length[10]',
					// 'address'        => 'required|min_length[4]|max_length[50]',
					'address'  => [
						'rules'     => 'required|min_length[4]|max_length[50]',
						'errors'    => [
						'required' => 'Address is  mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
						],
					],	

					'photo' => [
						'rules'     => 'uploaded[photo]|max_size[photo,' . ALLOW_MAX_UPLOAD .']|is_image[photo]|mime_in[photo,image/jpeg,image/png,image/svg, image/gif)]|ext_in[photo,png,jpg,jpeg, svg, gif]',
						'errors' => [
							'uploaded'  => 'Donor Image is mandatory.',
							'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
							'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
							'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
						],
					],
				];
				if ($this->validate($rules)) {
					 $uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					 $img = $this->request->getFile('photo');
					
					 if ($img->isValid() &&  !$img->hasMoved()) {
					 	 $newName = $img->getRandomName();
					 	 // $doc_img = $image->getName();
	                	$img->move(FCPATH . 'uploads/blood_buy_cus', $newName); 
	                	// $path = $this->request->getFile('profile_pic')->store();
	                	$doc_img = $img->getName();
	                	// var_dump($doc_img);
	                	// exit();
						$userdata = [
							'blood_id'        =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'blood_price'  =>  $this->request->getVar('blood_price',FILTER_SANITIZE_STRING),
							'blood_unit'  =>  $this->request->getVar('blood_unit',FILTER_SANITIZE_STRING),
							'blood_group_sale'  =>  $this->request->getVar('blood_group_sale',FILTER_SANITIZE_STRING),
							'username'        =>  $this->request->getVar('username',FILTER_SANITIZE_STRING),
							'mobile'          =>  $this->request->getVar('mobile'),
							'address'         =>  $this->request->getVar('address',FILTER_SANITIZE_STRING),
							'email'           =>  $this->request->getVar('email',FILTER_SANITIZE_STRING),
							'uid'             =>  $uid,
							'photo'           =>  $doc_img,
							'status'          => 'Active', 
							'created_at'      =>  date('Y-m-d h:i:s'),
							'created_by'      =>  $this->bldbnk_uid
						];
						// $status = $this->registerModel->Insertdata('donor_blood_sale', $userdata);
						$status = $this->registerModel->Insertdata('donor_blood_sale', $userdata);
						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation ! Blood Sale Successfully !', 3);
						}else{
							$this->session->setTempdata('error', 'Sorry ! Unable to Sale Blood  Try Again ?', 3);
						}  
						return redirect()->to(base_url().'/Blood_bank/donor_blood_trans');

					 }else{
					 	echo $image->getErrorString(). " " .$image->getError();
					 }
				}else{
					$data['validation'] = $this->validator;
				}
			}
			$args = [
				'status'  => 'Active'
			];
			// $data['donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
			$data['donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
			$data['blood_groups'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
			return view('Blood_bank/Admin/donor_blood_trans', $data);
	}

	public function manage_donor_blood_trans(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$args = [
			//'status'  => 'InActive',
			'is_del'  => '0',

		];
			// $data['blood_sale_rec'] = $this->commonForAllModel->fetch_allrecords_bypage('donor_blood_sale');
			
			$data = [
				'blood_sale_rec' => $this->registerModel->fetch_rec_by_args_arr('donor_blood_sale', $args),
				'pager'     => $this->registerModel->pager
			];
			return view('Blood_bank/Admin/manage_donor_blood_trans', $data);
		}
	}

	
	public function change_Blood_bank_password(){ 
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		else {
			$this->bldbnk_uid = session()->get('bldbnk_session_uid');
			$data = [];
			$data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->bldbnk_uid, 'register_all_users');
			//var_dump($data['loggedin_usr']);die;
			if ($this->request->getMethod() == 'post') {
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
						$status = $this->doctorModel->updatePassword('register_all_users', $npwd, session()->get('bldbnk_session_uid'));
						if ($status) {
							$this->session->setTempdata('success', 'Congratulation ! Password Updated Successfully!', 3);
							return redirect()->to(current_url());
						} else {
							$this->session->setTempdata('error', 'Sorry Unable to Update Password Try Again!', 3);
							return redirect()->to(current_url());
						}
					} else {
						$this->session->setTempdata('error', 'Incorrect login email or password', 3);
					}
				} 
				else {
					$data['validation'] = $this->validator;
				}
			}
		}
		return view('Blood_bank/Admin/change_blood_bank_password', $data);
	}//Function- Closed


	public function filter_sale_records($filter){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			if ($filter == 'new_sale') {
				$order = [
					'column_name'  => 'id',
					'order'        => 'desc'
				];
			}else if ($filter == 'old_sale') {
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
			// $data['blood_sale_rec'] = $this->registerModel->filter_rec_by_args('donor_blood_sale', $order);
			$data['blood_sale_rec'] = $this->registerModel->filter_rec_by_args('donor_blood_sale', $order);
			return view('Blood_bank/Admin/manage_donor_blood_trans', $data);
		}
	}

	public function blood_transition(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$args = [
				//'status'   => 'Active'
			];
			//$data['blood_transition'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
			$data['buy_donor_blood'] = $this->registerModel->fetch_rec_by_args('buy_donor_blood', $args);
			$args = [
				'status'   => 'Active'
			];
			$data['blood_transition'] = $this->commonForAllModel->fetch_rec_by_args('blood_group', $args);
			
			return view('Blood_bank/Admin/blood_transition', $data);
		}
	}

	public function get_blood_price_one_unit($id){
		$args = [
			'id'  => $id
		];

		// $data = $this->registerModel->fetch_rec_by_args('blood_group', $args);
		$data = $this->registerModel->fetch_rec_by_args('blood_group', $args);
		echo json_encode($data);
	}

	public function upload_blood_transition(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_group'    => 'required',
					'blood_unit'     => 'required',
					// 'username'       => 'required|min_length[3]|max_length[20]',
					'username'  => [
						'rules'     => 'required|min_length[3]|max_length[20]',
						'errors'    => [
						'required' => 'Username is  mandatory.',
						'min_length' => 'Minimum length is 3.',
						'max_length' => 'Maximum length is 20.'
						],
					],
					//'mobile'         => 'required|numeric|exact_length[10]',
					'mobile'  => [
						'rules'     => 'required|numeric|exact_length[10]',
						'errors'    => [
						'required' => 'Phone number is mandatory.'
						],
					],

					// 'address'        => 'required|min_length[4]|max_length[50]',	
					'address'  => [
						'rules'     => 'required|min_length[4]|max_length[50]',
						'errors'    => [
						'required' => 'Address is  mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
						],
					],

					'photo' => [
						'rules'     => 'uploaded[photo]|max_size[photo,' . ALLOW_MAX_UPLOAD .']|is_image[photo]|mime_in[photo,image/jpeg,image/png,image/svg, image/gif)]|ext_in[photo,png,jpg,jpeg, svg, gif]',
						'errors' => [
							'uploaded'  => 'User image is mandatory.',
							'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
							'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
							'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
						],
					],
				];
				if ($this->validate($rules)) {
					 $uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
					 $img = $this->request->getFile('photo');
					
					 if ($img->isValid() &&  !$img->hasMoved()) {
					 	 $newName = $img->getRandomName();
					 	 // $doc_img = $image->getName();
	                	$img->move(FCPATH . 'uploads/hospitalblood_cus', $newName); 
	                	// $path = $this->request->getFile('profile_pic')->store();
	                	$doc_img = $img->getName();
	                	// var_dump($doc_img);
	                	// exit();
						$userdata = [
							'blood_id'        =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							//'blood_group'        =>  $this->request->getVar('sale_blood_group',FILTER_SANITIZE_STRING),
							'blood_group'        =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'blood_price'     =>  $this->request->getVar('blood_price',FILTER_SANITIZE_STRING),
							'blood_unit'      =>  $this->request->getVar('blood_unit',FILTER_SANITIZE_STRING),
							'username'        =>  $this->request->getVar('username',FILTER_SANITIZE_STRING),
							'mobile'          =>  $this->request->getVar('mobile',FILTER_SANITIZE_STRING),
							'address'         =>  $this->request->getVar('address',FILTER_SANITIZE_STRING),
							'email'           =>  $this->request->getVar('email',FILTER_SANITIZE_STRING),
							'uid'             =>  $uid,
							'photo'           =>  $doc_img,
							'status'          => 'Active', 
							'created_at'      =>  date('Y-m-d h:i:s')
						];
						// $status = $this->registerModel->Insertdata('hospital_blood_sale', $userdata);
						$status = $this->registerModel->Insertdata('hospital_blood_sale', $userdata);
						if ($status) {
							$this->session->setTempdata('success', 'Congratulation ! Blood Sale Saved Successfully !', 3);
						}else{
							$this->session->setTempdata('error', 'Sorry ! Unable to Sale Blood  Try Again ?', 3);
							return redirect()->to(base_url().'/Blood_bank/blood_transition');
						}  
						return redirect()->to(base_url().'/Blood_bank/manage_blood_transition');

					 }else{
					 	echo $image->getErrorString(). " " .$image->getError();
					 }
				}else{
					$data['validation'] = $this->validator;
				}
			}
			$args = [
				'status'   => 'Active'
			];
			// $data['blood_transition'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
			$data['blood_transition'] = $this->registerModel->fetch_rec_by_args('blood_group', $args);
			return view('Blood_bank/Admin/blood_transition', $data);;
	}

	public function manage_blood_transition(){
		// echo "I'm here"; die;
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$args = [
			//'status'  => 'InActive',
			'is_del'  => '0',

		];
			// echo "I'm here"; die;
			// $data['blood_rec'] = $this->commonForAllModel->fetch_allrecords_bypage('hospital_blood_sale');
			$data = [
				'blood_rec' => $this->registerModel->fetch_rec_by_args_arr('hospital_blood_sale', $args),
				'pager'     => $this->registerModel->pager
			];
			return view('Blood_bank/Admin/manage_blood_transition',$data);
		}	
	}

	public function blood_selling_slip($id){
		$args = [
			'id'  => $id
		];
		// $data['blood_slip'] = $this->registerModel->fetch_rec_by_args('hospital_blood_sale',$args);
		$data['blood_slip'] = $this->registerModel->fetch_rec_by_args('hospital_blood_sale',$args);
		return view('Blood_bank/Admin/blood_selling_slip', $data);
	}

	public function search_hos_bld_user(){
		if (!(session()->has('bldbnk_session_uid'))) {  
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}  
		
		$keyword = $this->request->getVar('username', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('hospital_blood_sale', 'username', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}   
		
		$data = [
			'blood_rec' => $this->commonForAllModel->fetch_rec_by_args_with_status('hospital_blood_sale', $args),
			'pager' => $this->commonForAllModel->pager
		];
		
		return view('Blood_bank/Admin/manage_blood_transition', $data);
	}

	public function filter_hos_sale_bld($filter) {
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url() . "/Blood_bank/blood_bank_login");
		}
		if ($filter == 'new_blood') {

			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} else if ($filter == 'old_blood') {
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
			'blood_rec' => $this->registerModel->filter_rec_by_args_with_pagination('hospital_blood_sale', $order, $args),
			'pager'     => $this->registerModel->pager
		];
		if (!isset($data['blood_rec']) || $data['blood_rec'] == '') {
			session()->setTempdata('error', 'No patient found, please add Blood first', 2);
			return redirect()->to(base_url() . "Blood_bank/blood_transition");
		}
		// echo"<pre>";print_r($data);die;
		return view("Blood_bank/Admin/manage_blood_transition", $data);
		// return view('Admin/manage_doctor', $data);
	} //Function- Closed


	public function total_blood_stock(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
				$data['blood_stock'] = $this->commonForAllModel->fetch_allrecords_bypage('hospital_blood_sale');
				return view('Blood_bank/Admin/total_blood_stock', $data);
		}
	}
	public function add_blood_stock(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			return view('Blood_bank/Admin/add_blood_stock');
		}
	}
	public function add__total_blood_stock(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}else{
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_group'      => 'required',
					'blood_unit'      => 'required',
					'blood_price'      => 'required',
				];
				if ($this->validate($rules)) {
						$userdata = [
							'blood_group'          =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'blood_unit'           =>  $this->request->getVar('total_blood_unit',FILTER_SANITIZE_STRING),
							'blood_price'           =>  $this->request->getVar('blood_price',FILTER_SANITIZE_STRING),
							'total_blood_price'           =>  $this->request->getVar('total_blood_price',FILTER_SANITIZE_STRING),
							// 'status'               => 'Active', 
							// 'created_at'           =>  date('Y-m-d h:i:s')
						];
						// $status = $this->registerModel->Insertdata('blood_group', $userdata);
						 $status = $this->registerModel->Insertdata('hospital_blood_sale', $userdata);
						
						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation ! Blood Group Added Successfully !', 3);
						}else{
							$this->session->setTempdata('error', 'Sorry ! Unable to Add  Blood Group Try Again ?', 3);
						}  
						return redirect()->to(base_url().'/Blood_bank/add_blood_stock');
				}else{
					$data['validation'] = $this->validator;
				}
			}
			return view('Blood_bank/Admin/add_blood_stock', $data);
		}
	}

	public function search_sale_bld_stock(){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
		}
		$args = [
			'blood_group'   => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING)
		];

		// $data['blood_stock'] = $this->registerModel->fetch_rec_by_args_by_like('hospital_blood_sale',$args);
		$data['blood_stock'] = $this->registerModel->fetch_rec_by_args_by_like('hospital_blood_sale',$args);
		return view('Blood_bank/Admin/total_blood_stock', $data);
	}

	public function Logout_bloodbank(){
		if (session()->has('loggedin_info')) {
			$login_activity_id = session()->get('loggedin_info');
			$this->loginModel->updateLogoutTime($login_activity_id);
		}
		session()->remove('google_user');
		session()->destroy();
		// return redirect()->to(base_url()."/Accountant_login/accountant_login");
		return redirect()->to(base_url()."/Blood_bank/blood_bank_login");
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
				$this->save_status = $this->adminModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
				if ($this->save_status) {
					//$email_to = $this->email;
					$subject  = 'Reset Password Link';
					$message  = 'Hi,'
								. '<br><br>'
								.'Your Reset Password request has been Received. Please Click '
								.'the below Link to reset your Password.<br><br>'
								.'<a href="'.base_url().'/Blood_bank/reset_password/'.$this->token.'">Click Here to Reset Password</a>'
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
		return view('Blood_bank/forget_password', $data);
	} //function - Closed


	public function checkExpiry_time($time){
		$update_time   = strtotime($time);
		$current_time  = time();
		$timeDiff      = ($current_time - $update_time)/60;
		if ($timeDiff < 900) { return true; }
		else { return false; }
	}
	

	
	/* @params: Function reset password
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
							//$update_pass = $this->adminModel->update_password('admin_users', $this->token, $password);
							$this->updt_args = [
								'reset_pass_token'	=> $this->token //So that once used reset password token may not use again	
							];
							$this->updt_data_arr = [
								'password'		=> $password,
								'updated_at'	=> date('Y-m-d H:i:s'),
								'reset_pass_token'	=> '' //So that once used reset password token may not use again
							];
							$update_pass = $this->adminModel->update_rec_by_args('register_all_users', $this->updt_args, $this->updt_data_arr );
							if ($update_pass) {
								$this->session->setTempdata('success', 'Password updated successfully',3);
								return redirect()->to(base_url().'Blood_bank/blood_bank_login');
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
						return view('Blood_bank/reset_acc_password', $data);
					}
				}
				else {
					//$data['error']  = 'Reset Password Link was Expired';
					$this->session->setTempdata('error', 'Reset password link has expired.', 3);
					//return view('Blood_bank/forget_password', $data);
				}
			} 
			else {
				//$data['error'] = 'Unable to Find User Account';
				$this->session->setTempdata('error', 'Unauthorized or Reset password link has expired !',3);
				//return view('Blood_bank/forget_password', $data);
			}
		}
		else {
			//$data['error']  = 'Sorry ! Unauthorized token access';
			$this->session->setTempdata('error', 'Sorry ! Unauthorized token access!',3);
		}
		return redirect()->to(base_url() . 'Blood_bank/forget_password');
	} //function - closed


} //class - Closed