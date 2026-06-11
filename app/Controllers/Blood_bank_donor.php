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
use \App\Models\AutoModel;
use \App\Models\Register_model;
use \App\Models\Admin_Model;
use \App\Models\CommonForAllModel; //Custom

class Blood_bank_donor extends BaseController {
	public $loginModel;
	public $session;
	public $AutoModel;
	public $registerModel;
	public $adminModel;

	public function __construct() {
		helper(['form','date', 'Patient']);
		$this->loginModel = new Login_model();
		$this->AutoModel   = new AutoModel();
		$this->session   = session();
		$this->adminModel  = new Admin_Model();
		$this->email = \Config\Services::email();
		$this->registerModel = new Register_model();
		$this->commonForAllModel = new commonForAllModel(); //Custom
	}


   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 11th July, 2023
	*/
	public function index() { //Blood_bank_donor/blood_donor_login
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) { 
			return redirect()->to(base_url()."Blood_bank_registration/login_account"); }	
			else{
				$uniid = session()->get('loggedin_user');
				$data['loggedin_usr'] = $this->adminModel->getLoggedInDonor_data($uniid);
				$data['all_donors'] = $this->commonForAllModel->fetch_allrecords_bypage('blood_donor');
				$args = [
					'status' => 'Active'
						];
			$data['blood_bank'] = $this->adminModel->fetch_rec_by_args('blood_group', $args);
			return view('Blood_bank/Donor/dashboard', $data);
		}
	} //Function - Closed


   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function donor_registration(){ // Replaced with patient_registration function
		return view('Blood_bank/Donor/donor_registration');
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function patient_registration(){
		return view('Blood_bank/Donor/donor_registration');
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function donor_registered() {
		$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'blood_group'      => 'required',
					'contact_number'     => 'required|numeric|exact_length[10]',
					//'address'   => 'required|min_length[4]|max_length[50]',
					'address'  => [
							'rules'     => 'required|min_length[4]|max_length[50]',
							'errors'    => [
							'required' => 'Donor address is mandatory.',
							'min_length' => 'Minimum length is 3.',
							'max_length' => 'Maximum length is 20.'
							],
						],
					'donor_name'  => [
							'rules'     => 'required|min_length[3]|max_length[20]',
							'errors'    => [
							'required' => 'Donor name is mandatory.',
							'min_length' => 'Minimum length is 3.',
							'max_length' => 'Maximum length is 20.'
							],
						],
				];
				if ($this->validate($rules)) {
						$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz'.time()));
						$img = $this->request->getFile('donor_photo');
					
						if ($img->isValid() &&  !$img->hasMoved()) {
							$newName = $img->getRandomName();
						$img->move(FCPATH . 'uploads/donar_users', $newName); 
						$doc_img = $img->getName();
						
						$userdata = [
							'donor_name'     =>  $this->request->getVar('donor_name',FILTER_SANITIZE_STRING),
							'blood_group'    =>  $this->request->getVar('blood_group',FILTER_SANITIZE_STRING),
							'contact_number' =>  $this->request->getVar('contact_number'),
							'address'        =>  $this->request->getVar('address',FILTER_SANITIZE_STRING),
							
							'uid'            =>  $uid,
							'donor_image'    =>  $doc_img,
							'status'         => 'Active', 
							'created_at'     =>  date('Y-m-d h:i:s')
						];

						$status = $this->adminModel->Insertdata('blood_donor', $userdata);
						if ($status == true) {
							$this->session->setTempdata('success', 'Congratulation ! donor requested successfully !', 3);
						}
						else{
							$this->session->setTempdata('error', 'Sorry ! Unable to add  donor. Please try again', 3);
						}  
						//return redirect()->to(base_url().'/Blood_bank_donor/donor_registration');
						return redirect()->to(base_url().'Blood_bank_donor/manage_donor');
						}else{
						echo $image->getErrorString(). " " .$image->getError();
						}
				}else{
						$data['validation'] = $this->validator;
				}
			}
		return view('Blood_bank/Donor/donor_registration', $data);
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/


	public function search_donor(){
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) { 
				return redirect()->to(base_url()."Blood_bank_registration/login_account");
				}else{
				$uniid = session()->get('loggedin_user');
			// 		$data['loggedin_usr'] = $this->adminModel->getLoggedInDonor_data($uniid);
				$args=[];
				$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'pager'     => $this->AutoModel->pager
					];
			}
			return view('Blood_bank/Donor/search_donor', $data);
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function manage_donor(){
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) { 
			return redirect()->to(base_url()."Blood_bank_registration/login_account");
			}else{
			$uniid = session()->get('loggedin_user');
			$args=[];

			$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'pager'     => $this->AutoModel->pager
			];
		}
			return view('Blood_bank/Donor/search_donor', $data);
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function search_donor_details() {
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) {
			return redirect()->to(base_url()."Blood_bank_registration/login_account");
		}
		$keyword = $this->request->getVar('blood_group', FILTER_SANITIZE_STRING);
		$args = [ ];//'status'  => 'Active'
		$srch_field = 'blood_group'; //Searching table field name
		if ($keyword) {
			$result = $this->AutoModel->search_records('blood_donor', $srch_field, $keyword, $args);
		}
		$data = [
			'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
			'pager'     => $this->AutoModel->pager
		];
		return view('Blood_bank/Donor/search_donor', $data);
	} //Function - Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function blood_req_google_users($id){
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) {
			return redirect()->to(base_url()."Blood_bank_registration/login_account");
		}else{
			$uinfo = session()->get('google_user');
			$data = [
				'blood_donor_id'        => $id,
				'request_user'          => $uinfo['first_name'],
				'last_name'             => $uinfo['last_name'],
				'request_user_email'    => $uinfo['email'],
				'profile_pic'           => $uinfo['profile_pic'],
				'status'                => 'Active',
				'request_date'          => date('Y-m-d h:i:s')
			];
				$status = $this->adminModel->Insertdata('blood_request_google_user', $data);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation.! The blood request raised successfully', 3);
				}else{
					$this->session->setTempdata('error', 'Sorry ! Unable to Send  Blood Request  Try Again ?', 3);
				}  
				return redirect()->to(base_url().'Blood_bank_donor/search_donor_details/'.$id);
			}
	} //Function - Closed

   
   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function blood_request($id){
		if (!(session()->has('bldbnk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		else { 
			$this->usr_session_id = session()->get('frontdesk_session_
			id');
			if(!isset($this->usr_session_id) || $this->usr_session_id == '' || $this->usr_session_id == 0) {
				$this->session->setTempdata('error', 'Sorry ! Session has expired, please login again', 3);
			}
			$this->usr_uniid = session()->get('bldbnk_session_uid');
			$data = [
				'blood_donor_id'   	=> $id,
				'request_user_id'  	=> $this->usr_session_id,
				'status'           	=>'Active',
				//'is_del'			=> 0, //:0 (default) Non-deleted, 1: Deleted
				'request_date'     	=> date('Y-m-d h:i:s'),
				'created_by'		=> $this->usr_uniid,
				'created_at'		=> date('Y-m-d h:i:s')
				
			];
			$status = $this->adminModel->Insertdata('blood_requested_user', $data);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation.! The blood request raised successfully', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to send  blood Request try Again ?', 3);
			}  
			//return redirect()->to(base_url().'/Blood_bank_donor/search_donor_details/'.$id);
			return redirect()->to(base_url().'/Blood_bank/donor_details');
		}
	} //Function - Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function blood_bank(){
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) {
			return redirect()->to(base_url()."Blood_bank_registration/login_account");
		}else{
			$args = [
				'status' => 'Active'
			];
			$data['blood_bank'] = $this->adminModel->fetch_rec_by_args('blood_group', $args);
			return view('Blood_bank/Donor/blood_bank', $data);
		}
	} //Function - Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function search_hos_bld_user(){
		if (!(session()->has('loggedin_user') || session()->has('google_user'))) {
			return redirect()->to(base_url()."Blood_bank_registration/login_account");
		}
		$args = [
			'blood_group'   => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING),
			'status' => 'Active'
		];
		
		//$data['blood_bank'] = $this->adminModel->fetch_rec_by_args_by_like('blood_group',$args);
		$data['blood_bank'] = $this->commonForAllModel->fetch_rec_by_args_by_like('blood_group',$args);
		return view('Blood_bank/Donor/blood_bank', $data);
	} //Function- Closed

}//Class- Closed