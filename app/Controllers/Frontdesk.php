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
use \App\Models\Medicine_model;
use \App\Controllers\Admin;
use \App\Models\AutoModel;

use \App\Models\DoctorModel;
use \App\Models\Login_model;
use \App\Models\AccountAutoModel;
use \App\Models\Blogs_model;
//use \App\Models\CommonModel; //Custom
use \App\Models\FrontDeskModel; //Custom
use \App\Models\CommonForAllModel; //Custom
class Frontdesk extends BaseController {	
    public $adminModel;
	public $patient_model;
	public $pager; 	
	public $medicine_model;
	private $tot_slot;	
	public $doctorModel;
	public $AutoModel;
	//public $commonModel; //Custom
	public $accountant_model;
	public $adminContObj;
	public $loginModel;
	public $blogmodel;
	public $frontDeskModel; //Custom
	public $commonForAllModel;


    public function __construct() {
		helper(['form','Patient','text']); 
		$this->session     = \Config\Services::session();
		$this->email       = \Config\Services::email();
		$this->adminModel  = new Admin_Model();
        $this->adminContObj = new Admin();
		$this->AutoModel   = new AutoModel();
		$this->patient_model   = new AutoModel();
		$this->medicine_model   = new Medicine_model();
		$this->doctorModel   = new DoctorModel();
		//$this->commonModel = new CommonModel(); //Custom
		$this->accountant_model   = new AccountAutoModel();
		$this->blogmodel   = new Blogs_model();
		$this->loginModel = new Login_model();
		$this->tot_slot = 0;
		$this->pager       = \Config\Services::pager(); //for pagination etc
		$this->frontDeskModel   = new FrontDeskModel(); //Custom
		$this->commonForAllModel   = new CommonForAllModel();
		// $pager = \Config\Services::pager();
	}

   /* @param: 
    * @desc: Render Prescription Form: Step - Zero
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	function add_prescription($id) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}  
		else {
			$data = [
				'res'  => $this->commonForAllModel->getPatientRecordWithAppointment($id),
				'pager'   => $this->commonForAllModel->pager
			];
			$args = [ 'patient_id'	=>		$id ];
			$this->report = $this->commonForAllModel->fetch_rec_by_args('patient_reports', $args);
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
			return view('Frontdesk/add_patient_prescription', $data);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}  
		else {
			$this->frontdesk_uid = session()->get('frontdesk_session_uid'); //Loggedin User uid
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr_arr = array(
					"status" => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					"code"   => 200,
					"message"=> 'Invalid request method'
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
				$prescription = $this->request->getPost('prescription');
				$patient_name = $this->request->getPost('patient_name');
				$patient_id 	= $this->request->getPost('patient_id');
				$patient_age = $this->request->getPost('patient_age');
				$patient_gender = $this->request->getPost('patient_gender');
				$patient_puid = $this->request->getPost('patient_puid');
				$doctor_name = $this->request->getPost('doctor_name');
				$education = $this->request->getPost('education');
				$dr_specialization = $this->request->getPost('dr_specialization');
				$doc_gender = $this->request->getPost('doc_gender');
				$apmt_id 	= $this->request->getPost('apmt_id');
				$pid 	= $this->request->getPost('pid');
				$ref_by 	= $this->request->getPost('ref_by');
				$patient_mobile 	= $this->request->getPost('patient_mobile');
				$patient_email 	= $this->request->getPost('patient_email');
				$disease_symptoms 	= $this->request->getPost('disease_symptoms');
				$patient_type 	= $this->request->getPost('patient_type');
				$status 	   	   = $this->request->getPost('status');
				
				if($status != "Admit") { $status = 'Prescribed'; }
				$this->prescription_data_arr = [
					'prescription'    	=>  $prescription,//
					'prescription_detail'   =>  $prescription, //
					'patient_name'      =>  $patient_name,
					'patient_id'        =>  $patient_id,
					'status'              =>  $status, 
					'age'          		=>  $patient_age,
					'gender'          	=>  $patient_gender,
					'puid'          	=>  $patient_puid,
					'doctor_name'       =>  $doctor_name,
					'apmt_id'        	=>  $apmt_id,
					'pid'        		=>  $pid,
					'ref_by'    		=>  $ref_by,
					'patient_mobile'    =>  $patient_mobile,
					'patient_email'     =>  $patient_email,
					'disease_symptoms'  =>  $disease_symptoms,
					'patient_type'      =>  $patient_type,
					'prescription_date' =>  date('Y-m-d H:i:s'),
					'created_at'      	=>  date('Y-m-d H:i:s'),
					'created_by'      	=>  $this->frontdesk_uid, //Harcoded for now
				];
				//$status = $this->commonForAllModel->Insertdata('prescription_history', $this->prescription_data_arr);
				$this->pat_args = ['id' 	=> $patient_id];
				$this->updt_patient_arr = [
					'status'		=> $status,
					'updated_by'	=> $this->frontdesk_uid,
					'updated_at'	=> date('Y-m-d H:i:s')
				];

				$last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'patients', $this->pat_args, $this->updt_patient_arr);
				if ((int)$last_insrt_id > 0 ) {
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session('frontdesk_session_uid');
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
						'created_by'      =>  $this->frontdesk_uid, //Hardcoded for now
					];
					$status = $this->commonForAllModel->Insertdata('patient_reports', $this->report_data_arr);
				}
			}
			if ($status === true) {
				$this->result_arr = array(
					'status' => true,
					'error'	 => false, //error: `false` whereever status is true with SQL err 
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
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Sorry ! Required GET method'
			);
			return json_encode($this->result_arr);
		}
	} //Function - Closed

	/* @param: 
    * @desc: Upload Reports: Step - 2
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function upload_prescription_report($pid, $rid, $file_id) { //$pid: patient_id, $rid: Report ID
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session()->get('frontdesk_session_uid'); //Loggedin User uid
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
				$newName = $rpt->getRandomName();
				$rpt->move(FCPATH . 'uploads/patient_reports', $newName);
				$doc_img = $rpt->getName();
				
				$this->report_data_arr = [
					//'report_name'    =>  $doc_img,
					'pid'			 => $pid,
					'report_attachment' =>  $doc_img,
					'updated_at'      =>  date('Y-m-d H:i:s'),
					'updated_by'      =>  $this->frontdesk_uid,
				];
				$args = ['id'	=> $rid];
				//Need update model function in place of Insertdata - 
				$status = $this->commonForAllModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
				if ($status === true) {
					$this->result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` with status `true`
						'code' => 200,
						'data' > '',
						'message' => 'Reports uploaded successfully'
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` with status `false`
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
					'error'	 => true, //error: `true` whereever status is false with SQL err 
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
				'status' => true,
				'error'	=> false, //error: `false` whereever status is true with SQL err 
				'code' => 200,
				'data' > '',
				'message' => 'Unexpected request method. Please try again'
			);
			return json_encode($this->result_arr);
		}
		return redirect()->to(current_url());
	} //Function - Closed


    /* @param: 
    * @desc: Add Doctor Advice: Step - 3
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function save_advice() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}  
		else {
			$this->frontdesk_uid = session('frontdesk_session_uid');
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr = array(
					"status" => false,
					'error'  => true, //error: `true` whereever status is false with SQL err 
					"code"   => 200,
					"message"=> 'Invalid request method'
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
					'advice'    =>  $advice,
					'updated_at'      =>  date('Y-m-d H:i:s'),
					'updated_by'      =>  $this->frontdesk_uid, //Harcoded for now
				];
				
				$args = [ 'patient_id'   => $patient_id ];
				$fld_name = 'id';
				$this->maxid = $this->commonForAllModel->get_max_val('prescription_history', $fld_name);
				if($this->maxid === false || (!isset($this->maxid['0']->id))) {
					$this->data['advice'] = array(array());
					 $this->result_arr = array(
						"status" => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						"code" => '200',
						"message" => 'ID is missing. Please talk to admin'
					); 
					return json_encode($this->result_arr);
				}
				else if($this->maxid['0']->id > 0 ) {
					$args = [ 'id'   => $this->maxid['0']->id ];
					$status = $this->commonForAllModel->update_rec_by_args('prescription_history', $args, $this->advice_data_arr);
					if ($status === true) {
						$this->data['advice'] = array(array());
						$this->result_arr = array(
							"status" => true,
							'error'	 => false, //error: `false` with status `true`
							"code" 	 => '200',
							"message" => 'Advice added successfully'
						); 
						return json_encode($this->result_arr); 
					} 
					else {
						$this->result_arr = array(
							"status" => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
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
							'error'	 => true, //error: `true` whereever status is false with SQL err 
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
					'error'	 => true, //error: `true` whereever status is false with SQL err 'error'		=> true, //error: `true` whereever status is false with SQL err 
					"code"   => '200',
					"message"=> 'Validation failed.'
				); 
				return json_encode($this->result_arr); 
			}
		} //else - loop Closed
	} //Function - Closed

	/* @param: 
    * @desc: Add Doctor Recommendation: Step -4
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function save_message() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() ."/Frontdesk_login/login_account");
		}  
		else {
			$this->frontdesk_uid = session('frontdesk_session_uid');
			$this->request_method = $this->request->getMethod();
			if(!isset($this->request_method) || $this->request_method != 'post') { 
				$this->result_arr = array(
					"status" => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err
					"code"   => 200,
					"message"=> 'Invalid request method'
				); 
				return json_encode($this->result_arr);
			}
			$rules = [
				'msg_frm_doc' => 'required',
				//'slct_refr_usr' => 'required|numeric|greater_than[0]'
			 ];	
			if ($this->validate($rules)) {
				$msg_frm_doc = $this->request->getPost('msg_frm_doc');
				$patient_id = $this->request->getPost('patient_id');
				
				$this->message_data_arr = [
					'assigned_by'      => $this->frontdesk_uid,
					'assigned_to'      => $this->request->getPost('slct_refr_usr'),
					'status'           => 'Prescribed',
					'updated_at'       => date('Y-m-d H:i:s'),
					'updated_by'       => $this->frontdesk_uid,
					'recommendation'   => $this->request->getPost('msg_frm_doc'),
				];
				$args = [ 'patient_id'   => $patient_id ];
				$fld_name = 'id';
				$this->maxid = $this->adminModel->get_max_val('prescription_history', $fld_name);
				if($this->maxid === false || (!isset($this->maxid['0']->id))) {
					$this->data['advice'] = array(array());
					 $this->result_arr = array(
						"status" => false,
						'error'	 => true, //error: `true` whereever status is false with SQL error
						"code" => '200',
						"message" => 'ID is missing. Please talk to admin'
					); 
					return json_encode($this->result_arr);
				}
				else if($this->maxid['0']->id > 0 ) {
					$args = [ 'id'   => $this->maxid['0']->id ];
					$status = $this->adminModel->update_rec_by_args('prescription_history', $args, $this->message_data_arr);
					if ($status === true) {
						$this->data['recommendation'] = array(array());
						$this->result_arr = array(
							"status" => true,
							'error'	 => false, //error: `false` whereever status is true with SQL error
							"code" => '200',
							"message" => 'Recommendation  added successfully'
						); 
						return json_encode($this->result_arr); 
					} 
					else {
						$this->result_arr = array(
							"status" => false,
							'error'	 => true, //error: `true` whereever status is false with SQL err 
							"code"   => '200',
							"message"=> 'Failed to add Recommendation'
						); 
						return json_encode($this->result_arr);
					}
				}
				else {
					$this->data['recommendation'] = array(array());
						$this->result_arr = array(
							"status" => true,
							'error'	 => false, //error: `false` whereever status is true with SQL error
							'error'	 => false, //error: `false` with status `true`
							"code"   => '200',
							"message"=> 'Unexpected max ID. Please talk to admin'
						); 
					return json_encode($this->result_arr); 
				}
			}
			else {
				$this->data['recommendation'] = array(array());
				$this->result_arr = array(
					"status"=> true,
					'error'	=> false, //error: `false` with status `true`
					"code"  => '200',
					"message"=> 'Validation failed.'
				); 
				return json_encode($this->result_arr); 
			}
		} //else - loop Closed
	} //Function - Closed
	/***************************** Prescritpion Section - END  *****************************/

	
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
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
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
			$data['doctor_info'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $this->apmt_id ]; 
			$data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Frontdesk/payments/add_admission_fee', $data); // Pass the data to the view
	} //function - Closed

	/* @params: Function for get_dept_for_admission
	* @desc: 
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function get_dept_for_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
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
		$this->data['departments'] = $this->commonForAllModel->fetch_rec_by_args('department', $this->dept_args);
		if($this->request->getMethod() == 'get') {
			return view('Frontdesk/admission_process', $this->data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected request method !', 3);
			return view('Frontdesk/admission_process', $this->data);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}

		$this->doctor_id = session()->get('doctor_session_id');
		$this->data['wards'] = [];
		if($this->request->getMethod() == 'post') {
			$this->dept_id  = $dept_id;
			$this->ward_args = ['dept_id' 		=> $this->dept_id];
			$this->data['wards'] = $this->commonForAllModel->fetch_rec_by_args('hospital_wards', $this->ward_args);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->doctor_id = session()->get('doctor_session_id');
		if($this->request->getMethod() == 'post') {
			$this->ward_id  = $ward_id;
			$this->bed_args = [
				'ward_id' 		=> $this->ward_id,
				'is_free' 		=> 1,
				];
			$this->data['beds'] = $this->commonForAllModel->fetch_rec_by_args('hospital_beds', $this->bed_args);
			
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

	/* @param: 
    * @desc: Internal function in Admin>>>Patients>>Manage Patients>>>admission_process
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function admission_process() { 
		$this->insrtData = []; //Just for addressing notices
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->doctor_uid = session()->get('frontdesk_session_uid');
		$data  = [];
		// $data['validation'] = null;
		if($this->request->getMethod() == 'post') { 
			$this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
			$this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
			if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
				$result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	 => 200,
					'message'=> 'Patient ID may not blank',
					'data'   => $this->insrtData
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
					'updated_by'    => $this->doctor_uid,
			];
			
			$this->updt_args = [ 'id' 	=> $this->bed_id ];  
			$status = $this->doctorModel->update_rec_by_args('hospital_beds', $this->updt_args, $this->updt_data_arr );  
			if ($status === true) {
				$this->updt_data_arr = [
					'status'       	=> 'Admission Processed',
					'updated_at' 	=> date('Y-m-d h:i:s'),
					'updated_by'    => $this->doctor_uid,
				];
				$this->updt_args = [ 'id' 	=> $this->patient_id];
				$status = $this->doctorModel->update_rec_by_args('patients', $this->updt_args, $this->updt_data_arr);  
				if ($status === true) {
					$result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` with status `true`
						'code'   => 200,
						'message'=> 'Record updated successfully',
						'data'   => $this->updt_data_arr
					);
					return json_encode($result_arr);
				} 
				else {
					$result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	 => 200,
						'message'=> 'Failed! to update record',
						'data'   => $this->updt_data_arr
					);
					return json_encode($result_arr);
				} //else - loop closed 
			}
			else {
				$result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	 => 200,
					'message'=> 'Failed! to update record',
					'data'   => $this->updt_data_arr
				);
				return json_encode($result_arr);
			} //else - loop closed
		}
		else {
			$result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message'=> 'Unexpected request method',
				'data'   => $this->updt_data_arr
			);
			return json_encode($result_arr);
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
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
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
					'created_by'	=> $this->frontdesk_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Frontdesk/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Frontdesk/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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
		$data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

		$this->dr_args = ['id' 	=> $this->apmt_id]; //NEED $doctor_id here
		$data['doctor_info'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->dr_args);
		return view('Frontdesk/payments/add_admission_fee', $data);
	} //function - Closed


	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	// public function generate_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
	// 	if (!(session()->has('frontdesk_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Frontdesk_login/login_account");
	// 	} 
	// 	$this->frontdesk_uid = session()->get('frontdesk_session_uid');
	// 	$this->patient_id = (int) $patient_id; //patients tbl id 
	// 	$this->pid = (int)$pid;
	// 	$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
	// 	$this->puid = $puid;
	// 	$this->apmt_id = (int) $apmtid; //Appointment ID
	// 	$this->status = 4; // 4: Attended Patient
	// 	$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
	// 	$this->updt_status = true; //Just for addressing notices
		
	// 	$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
	// 	$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
	// 	if($data['payment_bill'] === false) {
	// 		$this->session->setTempdata('error', 'Payments bill is not found ', 3);
	// 		return redirect()->to(base_url() . 'Frontdesk/add_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
	// 	}
	// 	if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
	// 	$args = [ 'id'  => $this->patient_id ]; 
	// 		$update_dt_arr = [
	// 				'status'  => 'Admit', 
	// 				'updated_at'  => date('Y-m-d H:i:s'),
	// 				'updated_by'  => $this->frontdesk_uid,
	// 			];
	// 		$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
	// 	}
	// 	$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
	// 	$data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
	// 	if($this->updt_status === true) { //When attended the loggedin patient's appointment
	// 		//var_dump($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);die;
	// 		$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
	// 		$this->session->setTempdata('success', 'Bill generated successfull', 3);
	// 		return view('Frontdesk/payments/generate_admission_bill', $data);
	// 	}
	// 	else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
	// 		$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
	// 		$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
	// 		return view('Frontdesk/payments/generate_admission_bill', $data);
	// 	}
	// 	else {
	// 		$this->session->setTempdata('error', 'Unexpected result', 3);
	// 		return view('Frontdesk/payments/generate_admission_bill', $data);
	// 	}
	// } //function - Closed

	/* @param: 
    * @desc: Function generate_admission_bill
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
	public function generate_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
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
			return redirect()->to(base_url() . 'Frontdesk/add_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
			$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Admit', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->frontdesk_uid,
				];
			$this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
		if($this->updt_status === true) { //When attended the loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull', 3);
			return view('Frontdesk/payments/generate_admission_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Frontdesk/payments/add_admission_fee', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Frontdesk/payments/generate_admission_bill', $data);
		}
	} //function - Closed
	/****************************** Admimission Fee - END ******************************/


	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- START ******************/
	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Render Payment form and save payement into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: 
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function add_patient_payment_old($id, $pid, $apmtid, $puid) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		
		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
			return redirect()->to(base_url() . 'Frontdesk/manage_patients');
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'get') { //Render payment form
			$args = [ 'id'  => $this->apmt_id ];
			$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
			//1 march 2025 added
			//start: added new code to handle if there is no data comes from database 
			if($data['get_patient']){
				return view('Frontdesk/add_patient_payment', $data);
			}
			else{
				$this->session->setTempdata('error', 'There is no payment to clear this patient', 2);
				return  redirect()->to(base_url().'Frontdesk/manage_patients');
			}
			//end
			
			//return view('Frontdesk/add_patient_payment', $data);
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
					'created_by'    => $this->frontdesk_uid,
				];
				
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Frontdesk/generate_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Frontdesk/generate_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
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
			return redirect()->to(base_url().'Frontdesk/manage_patients');
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}

		$this->frontdesk_uid = session()->get('frontdesk_session_uid');

		$this->puid = $puid; 
		$this->apmt_id = $apmtid; //Appointment ID
		$args = [ 'id'  => $id ]; //patients tbl id
		if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
			$this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
				return redirect()->to(base_url() . 'Frontdesk/manage_patients');
		}
		$data = []; //for addressing notices
		
		
		$data['validation'] = null;
		$data['payments'] = $this->get_patient_final_payments_neo($id, $pid, $apmtid, $puid);;
		// ($data['payments']); exit;
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if ($this->request->getMethod() == 'get') { //Render payment form - with paid payment and hospital expence
				return view('Frontdesk/add_patient_payment', $data);

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
					'created_by'            => $this->frontdesk_uid
				];
					
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
						return redirect()->to(base_url().'Frontdesk/generate_receive_payment_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
				} 
				else { //success case
						return redirect()->to(base_url() . 'Frontdesk/generate_receive_payment_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
				}
			} 
			else { 
				$this->session->setTempdata('error', 'Mandatory validation failed', 2);
				$data['validation'] = $this->validator; 
					return view('Frontdesk/add_patient_payment', $data); 
				}
		}
		else {
		 	$this->session->setTempdata('error', 'Unexpected request method', 2);
		 	$data['validation'] = $this->validator;
				return redirect()->to(base_url().'Frontdesk/manage_patients');
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		
		// $this->admin_uid = session()->get('admin_session_uid');
		$this->admin_uid = session()->get('frontdesk_session_uid');
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
			return view('Frontdesk/generate_receved_payment', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Frontdesk/generate_receved_payment', $data);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = $apmtid;

		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
		$args = [ 'id'  => $this->patient_id ];
		$update_dt_arr = [ 
					//'status'  => 'Discharged', 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->frontdesk_uid,
				];
		$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		$args = [ 'id'  => $this->apmt_id ];
		
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Frontdesk/generate_apment_patient_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Frontdesk/generate_apment_patient_bill', $data);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$data = [
				'id' 		=> $id,
				'pid' 		=> $pid,
				'apmt_id'	=> $amptid,
				'puid'		=> $puid,
			];
		return view('Frontdesk/add_hospital_expenses', $data); 
	} //function - Closed


	/*@params: Ajax call
	* @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
	* @retuns:
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function save_hospital_expenses() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
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
					'created_by'        =>  $this->frontdesk_uid,
				];

				$status = $this->commonForAllModel->Insertdata('treatment_expenses_history', $insdata);
				if ($status === true) {
					$result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` with status `true`
						'code'	 => 200,
						'message'=> 'Expenses added successfully',
						'data'   => $insdata
					);
					return json_encode($result_arr);
				} 
				else {
					$result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL err 
						'code'	 => 200,
						'message'=> 'Failed! to add expenses',
						'data'   => $insdata
					);
					return json_encode($result_arr);
				}
			} 
			else { 
				//$data['validation'] = $this->validator;
				$insdata['validation'] = $this->validator;
				$insdata = $insdata['validation']->getErrors();
				$result_arr = array(
					'status' => false,
					'error'	 => false, //error: `false` showing validation error autoamtically
					'code'	 => 200,
					'message'=> 'Validation failed',
					'data'   => $insdata
				);
				return json_encode($result_arr);
			}
		} 
		else {
			$result_arr = array(
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'	 => 200,
				'message'=> 'Unexpected request method',
				'data'   => $array(),
			);
			return json_encode($result_arr);
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
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
		return view('Frontdesk/show_patient_final_payments', $data);
	} //function - Closed


	// public function show_patient_final_payments($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
	// 	if (!(session()->has('frontdesk_session_uid'))) {
	// 		return redirect()->to(base_url() . "/Frontdesk_login/login_account");
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
	// 	$data['total_expnc_amt'] = $this->commonForAllModel->fetch_sum_by_args('treatment_expenses_history', $fld, $args);
	// 	if( $data['total_expnc_amt'] === false ) { $data['total_expnc_amt'] = 0; }
		
	// 	$args = [ 'id'  => $patient_id ];
	// 	$data['patients'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
	// 	$args_apmt = [ 'id'  => $apmtid ];
    //     $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);
	// 	//1 march 2025 added
	// 	//start: added new code to handle if there is no data comes from database 
	// 	if($data['get_patient']){
	// 		return view('Frontdesk/show_patient_final_payments', $data); 
	// 	}
	// 	else{
	// 		$this->session->setTempdata('error', 'There is no to clear final payment this patient', 2);
	// 		return  redirect()->to(base_url().'Frontdesk/manage_patients');
	// 	}
	// 	//end
	// 	//return view('Frontdesk/show_patient_final_payments', $data); // Pass the data to the view
	// } //function - Closed

	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	//public function clear_final_dues($patient_id, $pid, $apmtid, $puid) {//pid is patient_login tbl id
		public function clear_final_dues($patient_id, $pid, $apmtid, $puid, $status) {//pid is patient_login tbl id
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');

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
					return redirect()->to(base_url() . 'Frontdesk/show_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
				}
				$this->user_data_arr = [
					'total_hospital_expenses'	=> $this->request->getVar('total_hospital_expenses', FILTER_SANITIZE_STRING),
					'total_patient_paid_amount' => $this->request->getVar('total_paid_amount', FILTER_SANITIZE_STRING),
					'paid_amount' 	=> $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING),
					'payment_note'			=> $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
					'pid'           =>  $this->pid,
					'puid'           =>  $this->puid,
					'patients_id'    =>  $this->patient_id,
					'apmt_id'    	=>  $this->apmt_id,
					'pay_date'      =>  date('Y-m-d'),
					'created_at'	=> date('Y-m-d h:i:s'),
					'created_by'	=> $this->frontdesk_uid,
				]; 
				$this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
				if ($this->last_inst_id === false) { //failure case
					return redirect()->to(base_url().'Frontdesk/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				} 
				else { //success case
					return redirect()->to(base_url() . 'Frontdesk/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
				}
			} 
			else { $data['validation'] = $this->validator; }
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
			$data['patients'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		return view('Frontdesk/discharge_appointment_pat', $data);
	} //function - Closed


	/*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
	* @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
	* @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
	public function generate_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid, $status) { //Ref generate_patient_bill()
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$this->patient_id = $patient_id; //patients tbl id 
		$this->pid 		= $pid;
		$this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
		$this->puid 	= $puid;
		$this->apmt_id 	= $apmtid; //Appointment ID
		$this->status 	= $status;

		if($this->status == 'Admit') { $this->status = 1; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
		else if($this->status != 'Admit') { $this->status = 2; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
		$args = ['id'  => $this->pay_chrg_id ];
		$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Frontdesk/show_patient_final_payments/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		$args = [ 'id'  => $this->patient_id ];
		
		$update_dt_arr = [ 
					'status'  	=> $this->status, 
					'created_at'  => date('Y-m-d H:i:s'),
					'created_by'  => $this->frontdesk_uid,
				];
		$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		
		$args = [ 'id'  => $this->apmt_id ];
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		if($this->updt_status === true) {
			$this->session->setTempdata('success', 'Bill generated successfully', 3);
			return view('Frontdesk/generate_clear_dues_bill', $data);
		}
		else {
			$this->session->setTempdata('Failed !', 'to generate bill', 3);
			return view('Frontdesk/generate_clear_dues_bill', $data);
		}
	} //function - Closed
	/****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/

	
	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function generate_serial($tbl, $fld) {
		$this->table = $tbl; 
		$this->where_field = $fld;
		$this->new_serial = 0; //Just for addressing notices
		$this->highest_serial = $this->get_highest_today_serial($this->table, $this->where_field); //returns null if not record found
	
		if(!isset($this->highest_serial->serial) || $this->highest_serial == null) { 
			if(isset($this->highest_serial->patient_id)) { 
				$this->new_serial_arr = array(
						'serial'  => $this->new_serial += 1,
						'patient_id'  => $this->highest_serial->patient_id,
					);
					return $this->new_serial_arr; //return $this->new_serial += 1;
				}
			else if(isset($this->highest_serial->id)) {//else if- START
			$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => $this->highest_serial->id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			}//else if- CLOSED
			else if(!isset($this->highest_serial->patient_id) || $this->highest_serial->patient_id == null){//else if - START
			$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
				return $this->new_serial_arr; //return $this->new_serial += 1;
			}//else if - CLOSED
			else if(!isset($this->highest_serial->id) || $this->highest_serial->id == null){ //else if- START
			$this->new_serial_arr = array(
					'serial'  => $this->new_serial += 1,
					'patient_id'  => 0, //$this->highest_serial->patient_id,
				);
			}//else if-CLOSED
			else {                                                   //else - START
			$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url().'/Frontdesk/all_appointments');
			}//else - CLOSED
		} 
		else if(isset($this->highest_serial->serial)) { //else if- START
			if(isset($this->highest_serial->patient_id)) { //if- START
			$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->patient_id,				
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			}//if- CLOSED
			else if(isset($this->highest_serial->id)) {//else-if START
			$this->new_serial_arr = array(
					'serial'  => (int)($this->highest_serial->serial + 1),
					'patient_id'  => $this->highest_serial->id,				
				);
				//return $this->new_serial = (int) $this->highest_serial->serial + 1; 
				return $this->new_serial_arr;
			} //else if- CLOSED
			else {  
			$this->session->setTempdata('error', 'Unexpected patient ID', 3);
				return redirect()->to(base_url().'/Frontdesk/all_appointments');
			}  //else- CLOSED
		}//else if- CLOSED
		else { 
			//return false;
			$this->session->setTempdata('error', 'Unexpected serial number ', 3);
			return redirect()->to(base_url().'/Frontdesk/all_appointments');
		}//else- CLOSED
	} //Function - Closed

	
	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function get_highest_today_serial($tbl, $fld) {
		$this->tbl_name = $tbl; 
		$this->where_field = $fld;
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		//$args = [ 'registration_date'  => date('Y-m-d') ];
		$args = [ $this->where_field  => date('Y-m-d') ];
		
		// $this->highest_pat_serial = $this->AutoModel->today_highest_serial($this->tbl_name, $args);
		$this->highest_pat_serial = $this->commonForAllModel->today_highest_serial($this->tbl_name, $args);
		return $this->highest_pat_serial;
	} //Function - Closed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function generate_puid($new_sn) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$this->new_serial = $new_sn; //Serial Number
		$this->current_date = date('Y-m-d');
		
		$this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
		$this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
		return $this->puid;
	} // Function - Closed


	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	/*public function search_donor() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		else{
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$args = ['status'=> 'Active' ]; 

			$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'blood_groups' => $this->commonForAllModel->fetch_rec_by_args_group_by('blood_donor', 'blood_group', $args),
				'pager'     => $this->AutoModel->pager
			];
		}
		//echo "<pre>";
		//print_r($data); exit;
		return view('Frontdesk/search_donor', $data);
	} //Function - Closed*/


	public function search_donor() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		else{
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$args = ['status'=> 'Active' ]; 

			$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'blood_groups' => $this->commonForAllModel->fetch_rec_by_args_group_by('blood_donor', 'blood_group', $args),
				'pager'     => $this->AutoModel->pager
			];
		}
		//echo "<pre>";
		//print_r($data); exit;
		return view('Frontdesk/search_donor', $data);
	} //Function - Closed


	public function find_donors() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		else{
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$args = ['status'=> 'Active' ]; 

			$data = [
				'donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'pager'     => $this->AutoModel->pager
			];
		}
		//echo "<pre>";
		//print_r($data); exit;
		return view('Frontdesk/search_donor', $data);
	} //Function - Closed



	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function blood_req_google_users($id){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
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
				$status = $this->commonForAllModel->Insertdata('blood_request_google_user', $data);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation.! The blood request raised successfully', 3);
				}else{
					$this->session->setTempdata('error', 'Sorry ! Unable to Send  Blood Request  Try Again ?', 3);
				}  
				return redirect()->to(base_url().'Frontdesk/search_donor_details/'.$id);
			}
	} //Function - Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function blood_request($id) { 
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}else{
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$data['loggedin_usr'] = $this->commonForAllModel->getLoggedInDonor_data($frontdesk_uid);
			//Checking User & Store Id in Session Variable
			$user_session = [
				'id'    =>  $data['loggedin_usr']->id
			];
			$this->session->set('frontdesk_session_id',$user_session);
			//Checking User & Store Id in Session Variable
			$user_id = session()->get('frontdesk_session_id');
			$data = [
				'blood_donor_id'   => $id,
				'request_user_id'  => $user_id,
				'status'           =>'Active',
				'request_date'     => date('Y-m-d h:i:s'),
                'created_by'		=> $frontdesk_uid,
				'created_at'		=> date('Y-m-d h:i:s')
			];

            
			$status = $this->commonForAllModel->Insertdata('blood_requested_user', $data);
			if ($status == true) {
				$this->session->setTempdata('success', 'Congratulation.! The blood request raised successfully', 3);
			}else{
				$this->session->setTempdata('error', 'Sorry ! Unable to send  blood Request try Again.', 3);
			}  
			//return redirect()->to(base_url().'/Frontdesk/search_donor_details/'.$id);
            return redirect()->to(base_url().'/Frontdesk/search_donor');
		}
	} //Function - Closed

	/*@params: Logout_account
	* @desc: Logout_account 
	* @retuns: 
	* @author: Neoarks Team
	* @date: 18th October, 2023
	* @modify:
	*/
		public function Logout_account(){ //Donor Logout
			if (session()->has('loggedin_info')) {
				$login_activity_id = session()->get('loggedin_info');
				$this->loginModel->updateLogoutTime($login_activity_id);
			}
			session()->remove('google_user');
			session()->destroy();
			//return redirect()->to(base_url()."Blood_bank_donor/blood_donor_login"); //Custom - Donor login page
			return redirect()->to(base_url()."Frontdesk_login/login_account"); //Default - Donor login page
		}

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function search_hos_bld_user(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$args = [
			'blood_group'   => $this->request->getVar('blood_group', FILTER_SANITIZE_STRING),
			'status' => 'Active'
		];

		$data['blood_bank'] = $this->commonForAllModel->fetch_rec_by_args_by_like('blood_group',$args);
		return view('Frontdesk/blood_bank', $data);
	} //Function- Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/

	public function blood_bank(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		else{
			$args = [ 'status' => 'Active' ];
			$data['blood_bank'] = $this->commonForAllModel->fetch_rec_by_args('blood_group', $args);
			return view('Frontdesk/blood_bank', $data);
		}
	} //Function - Closed
	

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function get_doctor_fee_details($id){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			    'id'  => $id
		        ];
		$data = $this->commonForAllModel->fetch_rec_by_args('doctor', $args);
		echo json_encode($data);
	} //Function - Closed

    /* @param: Function for Dashboard
	* @description: Dashboard details
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */

	public function index() { 
    	if (!(session()->has('frontdesk_session_uid'))) {     
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}       
		else{     
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$data['loggedin_usr'] = $this->commonForAllModel->getFrontdeskUserData('blood_bank_users', $frontdesk_uid);
			if(!isset($data['loggedin_usr']->email) || $data['loggedin_usr']->email == false) {
				return redirect()->to(base_url()."Frontdesk_login/login_account");
			}
        }
			//Checking Activation Account is Activate to Access Control or Not	
			$args = [
				//'doctor_email'  => $data['loggedin_usr']->email
			];
				$check_account = $this->commonForAllModel->fetch_rec_by_args('blood_bank_users', $args);
			if ($check_account) { //Store Doctor Account in session     
				//$frontdesk_session = $check_account[0]->id;
				//$this->session->set('doctor_session_id', $doctor_session);
				//$this->session->set('frontdesk_session_id', $frontdesk_session);
				//Store Doctor Account in session
				
				//Dashboard Section Query
				//Today Patient Under You - START
				$args = [
					//'doctor_id'  => $doctor_id,
				];
				$data['all_patients'] = $this->patient_model->where($args)->findAll();
				//Today Patient Under You - END
				
				//Discharge Patient Under You - START
				$args = [
					'status'       => 'Discharged'
				];
				$data['t_d_patient'] = $this->patient_model->where($args)->findAll(); //Discharged Patients under Dr.
				//Discharge Patient Under You - END

				//Doctor Appointment Section Script
				 $args = [
				 	'created_at'  => date('Y-m-d h:i:s')
				 ];
				$data['appointment'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
				//Doctor Appointment Section Script 
				 $args = [
				 	//'doctor_id'   => $doctor_id
				 ];
				 $args = [];
				$this->data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $args);
				if (is_array($this->data['doctors']) || is_object($this->data['doctors'])) {
					$this->tot_dr = count($this->data['doctors']); //Total doctors
				}
					$all_appointment = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);

				$args = [
				 	//'doctor_id'   => $doctor_id,
				 	'status'        => 'Active'
				];
				$all_patients = $this->commonForAllModel->fetch_rec_by_args('patients', $args);

				$args = [
				 	//'doctor_id'   => $doctor_id,
				 	'status'        => 'Discharged'
				];
				$all_discharge_pat = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
				 //Chart Sction Query Start
				$data['chart_data']  = [
					'tot_appointments'      => $all_appointment ? count($all_appointment): '0',
					'total_patients'        => $all_patients ? count($all_patients): '0',
					'all_discharge_pat'     => $all_discharge_pat ? count($all_discharge_pat): '0'
				]; 
				
				 //Chart Sction Query End
				
				//Dashboard Section Query
				return view('Frontdesk/dashboard', $data);
				//Checking Activation Account is Activate to Access Control or Not		
			}
			else{
				return view('Frontdesk/doctor_req_active', $data);
			}
		
    } //function - Closed
     
    /* @param: Function for Filter by department 
	* @description: Function for Filter by department in the search area of frondesk which is located on all appointments on frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
	public function filter_department($filter){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		} 
		if ($filter == 'new_department') { 
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
        else if ($filter == 'old_department') { //else if- START
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}//else if- CLOSED
        else{ 
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		$data['department'] = $this->commonForAllModel->filter_rec_by_args('department', $order);
		return view('Frontdesk/all_appointments', $data);
	} //Function - Closed

    /* @param: Function for search department
	* @description: Function for search department.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
    public function search_department(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		} 
		$args = [
			'department_name'   => $this->request->getVar('department_name', FILTER_SANITIZE_STRING)
		];
		$data['department'] = $this->commonForAllModel->fetch_rec_by_args_by_like('department',$args);
		
		if(is_array($data)) { 
			return view('Frontdesk/department/manage_department', $data);                   
		}   
		else {  
			$this->session->setTempdata('error', 'Unexpected result set', 3);    
			return redirect()->to(base_url().'Frontdesk/department/manage_department');  
		}   
    }//Function - Closed

    /* @param: Function for all patients on dashboard
    * @description: Function for all patients on dashboard
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 21th July, 2023
    */
    public function all_patients() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}    
		else {   
			$args = [ 
				//'status'  => 'Active' 
			];
			$data = [
				'patients'  => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'pager'   => $this->commonForAllModel->pager
			];
			return view("Frontdesk/all_patients",$data);
		}   
    }//Function - Closed


    /* @param: Function for Edit department
	* @description: Function for Edit department for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 18th July, 2023
    */

    public function edit_department($id){
		if (!(session()->has('frontdesk_session_uid'))) {    
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}     
		$args = [
			'id'  => $id
		];
		$data['department'] = $this->commonForAllModel->fetch_rec_by_args('department', $args);
		return view('Frontdesk/department/edit_department', $data);
    }//Function - Closed

    /* @param: Function for update_department
	* @description: Function for update_department for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
    public function update_department($id){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'id'  => $id
		];

		$data = [
			'department_name'           =>  $this->request->getVar('department_name',FILTER_SANITIZE_STRING),
			'dep_desc'                  =>  $this->request->getVar('dep_desc'),
			'status'                    => 'Active', 
			'created_at'                =>  date('Y-m-d h:i:s')
		];
		$status = $this->commonForAllModel->update_rec_by_args('department',$args, $data);
		if ($status == true) {   
			$this->session->setTempdata('success', 'Congratulation ! Department Details Updated Successfully !', 3);
		}
		else{
			$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
			}
		//return redirect()->to(base_url().'Frontdesk/edit_department/'.$id); //same page rediction
		return redirect()->to(base_url().'Frontdesk/manage_department');
    }//Function - Closed

    /* @param: Function for manage_department
	* @description: Function for manage_department for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
    public function manage_department(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Login");
		}  
		$data['department'] = $this->commonForAllModel->fetch_all_records('department');
		if(!isset($data['department']) || $data['department'] == '') { //If no department found   
			$this->session->setTempdata('error', 'Sorry ! No department found. Please add department first', 3);
			return redirect()->to(base_url()."Frontdesk/add_department");
		}
		return view('Frontdesk/department/manage_department', $data);
    }//Function - Closed

    /* @param: Function for change_department_status
	* @description: Function for change_department_status for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
    public function change_department_status($id, $status){
		if (!(session()->has('frontdesk_session_uid'))) {    
			return redirect()->to(base_url()."Login");
		}
		$args  = [ 'id' => $id ];
		$data = [ 'status'  => $status ];
		$status = $this->commonForAllModel->update_rec_by_args('department', $args, $data);
		if ($status == true) {  
			$this->session->setTempdata('success', 'Congratulation ! Department Status Changed Successfully !', 3);
		}// If-CLosed
	    else{   
			$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
		} //Else- CLOSED
	   return redirect()->to(base_url().'/Frontdesk/manage_department');
    }//Function - Closed


   /* @param: Function for delete_department
	* @description: Function for delete_department for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
    public function delete_department($id){
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Login");
		}    
		$args = [ 'id'  =>  $id ];
		$status = $this->commonForAllModel->delete_records('department', $args);
		if ($status == true) { 
			$this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
		}
	    else{  
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
	   return redirect()->to(base_url().'Frontdesk/manage_department');
    }//Function - Closed

    /* @param: Function for login_account
	* @description: Function for login_account for frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 17th June, 2023
    */
	public function login_account() { //Donor's login & ...
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$data = [];
		
		//site login 
		if ($this->request->getMethod() == 'post') { 
			$rules = [
				'email'      => 'required|valid_email',
				//'password'   => 'required|min_length[6]|max_length[20]'
				'password'  => [
					'rules'     => 'required|min_length[4]|max_length[20]',
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
				$allow     = $throttler->check("test", 4, MINUTE);
				if ($allow) {
					// $userdata = $this->loginModel->verifyAccountantEmail('blood_bank_users',$email);
					$userdata = $this->commonForAllModel->verifyAccountantEmail('blood_bank_users',$email);
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
									// $login_activity_id = $this->loginModel->saveLoginInfo($loginInfo);
									$login_activity_id = $this->commonForAllModel->saveLoginInfo($loginInfo);
									if ($login_activity_id) {      
										$this->session->set('loggedin_info', $login_activity_id);
										$this->session->set('frontdesk_session_uid', $userdata['uid']);
										$this->session->set('loggedin_usr_name', $userdata['username']); //Loggedin- user name
										$this->session->set('frontdesk_session_id', $userdata['id']);
									}
									$this->session->set('frontdesk_session_uid', $userdata['uid']);
									return redirect()->to(base_url().'Frontdesk');
								}
								else{
									$data['error']  = 'Email & password do Not Matched  Invalid';
								}
							}
                            else{
								$data['error']  = 'Your Account is Not Activated by Admin Please Contect Admin other wise wait Some time !';
							}	
						}
                        else{ 
							$data['error']  = 'Sorry Wrong password entered for that Email';
						}
					}
                    else{
						$data['error']  = 'Email & password does not Exists';
					}
				}  
                else{ 
						$data['error']  = 'Max No. of failed Login Attempt, Try Again a Few Minutes';
				}  
			} 
            else{ 
				$data['validation']  = $this->validator;
			}
		} 

	}//Function - Closed

    /*@param: Delete Dr. appointment from Dashboard
	* @desc: Since it need to redirect on dashboard, therefore written another function wrt delete_appointment
	* @use: 
	* @author: Neoark Team
	* @date: June 21, 2023
	* @modify:
	* @reference: Admin/delete_appointment function
	* @copyrights: Neoark Software Team
    */
	public function delete_appointment_dsbrd($id) {
		if (!(session()->has('frontdesk_session_uid'))) { //if- START

		return redirect()->to(base_url("Frontdesk_login/login_account"));
		} 
		else { //else loop-START
			$args = [
				'id'  => $id
			];
			$status = $this->commonForAllModel->delete_records('booked_doctor_appointment', $args);
			if ($status == true) { 
				$this->session->setTempdata('success', 'Congratulation ! Appointment Deleted Successfully !', 3);
			}
            else{ 
				$this->session->setTempdata('error', 'Sorry ! Unable to Delete Try Again ?', 3);
			}
			return redirect()->to(base_url().'Frontdesk/index');
		} //else loop - Closed
	} //Function - Closed

    /* @param: Function for Adding patient or patient_registration 
	* @description: Add patient details
	* @Remark: Patients Record Section Start with AutoModel Pagination Feature, Developed By Neoarks Team
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function patient_registration() {
		if (!(session()->has('frontdesk_session_uid'))) {   
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}//if-CLOSE
		else {    
			$args = [ 'status'  => 'Active' ];
			$data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $args);
			return view('Frontdesk/patient_registration', $data);
		} //else loop - Closed
	}//Function - Closed


    /* @param: Function for add_patients.
	* @description: Add patient from manage patients.
	* @Remark: Patients Record Section Start with AutoModel Pagination Feature, Developed By Neoarks Team
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	
	public function add_patients(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} else {
			$args = [
			//	'status'  => 'Active'
			];
			$data = [
				'doctors' => $this->commonForAllModel->fetch_rec_by_args('doctor', $args),
			];
			return view('Frontdesk/add_patients', $data);
		}
	}

    /* @param: Function for Manage discharge patients.
	* @description: Add patient from manage patients.
	* @Remark: Patients Record Section Start with AutoModel Pagination Feature, Developed By Neoarks Team
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function manage_discharge_patients(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
        else{ //ellse-START
			$args = [
				'status'  => 'Discharged',
				'is_del' => 0
			];
			$data = [
				// 'patients'  => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
				'patients'  => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'pager'   => $this->commonForAllModel->pager
			];
			if($data['patients'] == false) {

			}
        	return view("Frontdesk/manage_disc_patients",$data);
		}
	}//Function - Closed

	/* @param: Function for Manage discharge patients.
	* @description: Add patient from manage patients.
	* @Remark: Patients Record Section Start with AutoModel Pagination Feature, Developed By Neoarks Team
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function today_patients(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "Frontdesk_login/login_account");
		} else {
			$doctor_id = session()->get('doctor_session_id');
			if (!isset($doctor_id) || $doctor_id == '') {
				return redirect()->to(base_url() . "Frontdesk_login/login_account");
			}
			$args = [
				'doctor_id'  	=> $doctor_id,
				//'status'       	=> 'Active',
				'registration_date' => date('Y-m-d')
			];
			$data = [
				// 'today_patients'  => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
				'today_patients' => $this->doctorModel->fetch_rec_by_args_with_status('patients', $args),
				'pager' => $this->doctorModel->pager
			];
			return view("Frontdesk/today_patient", $data);
			// return view("Admin/manage_patients",$data);
		}
	}//Function- Closed


   /* @param: Function for View doctor.
	* @description: View doctor from frontdesk.
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 15th July, 2023
    */
	public function view_doctor(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$data['view_doctor'] = $this->commonForAllModel->fetch_all_records('doctor');
		return view('Frontdesk/view_doctor',$data);
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
		if (!(session()->has('frontdesk_session_uid')) && !(session()->has('frontdesk_session_id'))) {
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
		$this->frontdesk_uid = session()->get('frontdesk_session_uid'); //Loggedin User uid
		$this->frontdesk_id = session()->get('frontdesk_session_id'); //Loggedin User uid
		$data = []; //Just for addressing notices
		$data['validation'] = null;	//Just for addressing notices

		if ($this->request->getMethod() == 'post') {	
			$rules = [
				'uploaded_file' => [
					'rules'     => 'required|uploaded[uploaded_file]|max_size[uploaded_file,' . ALLOW_MAX_UPLOAD .']|is_image[uploaded_file]|mime_in[uploaded_file,image/jpeg,image/png,image/gif,image/svg+xml]|ext_in[uploaded_file,png,jpg,jpeg,svg,gif]',
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
				$args = [ 'id'  => $this->frontdesk_id ];
						
				$this->old_data = $this->commonForAllModel->fetch_rec_by_args('blood_bank_users', $args);
				
				if(isset($this->old_data[0]->profile_pic)) {
					if(file_exists(FCPATH . 'uploads/accounts/frontdesk/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
					//(file_exists(FCPATH . 'uploads/accounts/frontdesk/' . $this->old_data[0]->profile_pic) || $this->old_data[0]->profile_pic != '') {
						//delete old  image
						@unlink(FCPATH . 'uploads/accounts/frontdesk/' . $this->old_data[0]->profile_pic);
					} //else - Not needed
				} //else - Not needed	
				$this->random_name = $this->profile_pic->getRandomName();
			if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
				$this->profile_pic->move(FCPATH . 'uploads/accounts/frontdesk', $this->random_name);// Move the uploaded file to the destina
				
				$this->user_data_arr = [
					'profile_pic'     =>  $this->random_name, //$this->file_name,
					'updated_at'      =>  date('Y-m-d H:i:s'),
					'updated_by'      =>  $this->frontdesk_id,
				];
				$args = ['uid'	=> $this->frontdesk_uid]; //Need update model function in place of Insertdata - 
				$status = $this->commonForAllModel->update_rec_by_args('blood_bank_users', $args, $this->user_data_arr);
				if ($status === true) {
					$this->result_arr = array(
						'status' => true,
						'error'	 => false, //error: `false` whereever status is true with SQL error
						'code' => 200,
						'data' > '',
						'message' => 'Photo uploaded successfully'
					);
					return json_encode($this->result_arr);
				} 
				else {
					$this->result_arr = array(
						'status' => false,
						'error'	 => true, //error: `true` whereever status is false with SQL error
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
					'error'	 => true, //error: `true` whereever status is false with SQL error
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
				'error'	 => true, //error: `true` whereever status is false with SQL error
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
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		else {
			$this->frontdesk_uid = session()->get('frontdesk_session_uid');
			if ($this->request->getMethod() == 'get') {
				$this->data['profile_record']  = $this->commonForAllModel->getLoggedInFrontdeskData($this->frontdesk_uid);
				if($this->data['profile_record'] === false) {
				$this->session->setTempdata('error', 'No Record Found', 3); 
				return redirect()->to(base_url() . 'Frontdesk/view_profile' , $this->data);}
				else {return view('Frontdesk/view_profile', $this->data);}
			}
			else {
				$this->session->setTempdata('error', 'Unexpected request method !', 3);
				return view('Frontdesk/view_profile', $this->data);
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
        if (!(session()->has('frontdesk_session_uid'))) {
            return redirect()->to(base_url() . "/Frontdesk_login/login_account");
        }
        else {
            $this->frontdesk_uid = session()->get('frontdesk_session_uid');
			$this->data = [];
			$this->data['validation'] = null;
			$rule = [
				// 'username' => 'required|min_length[4]|max_length[20]',
				'username'  => [
					'rules'     => 'required|min_length[6]|max_length[20]',
					'errors'    => [
					'required' => 'User name is mandatory.',
					'min_length' => 'Minimum length is 6.',
					'max_length' => 'Maximum length is 20.'
					],
				],
                'mobile'            => 'required|numeric|exact_length[10]',
                'email'         => [
                    'rules'      => 'required|valid_email|is_unique[admin_users.email]',  
                    'errors'     => [
                        'required'     => 'Email is required',
                        'valid_email'  => 'Please enter valid email', //email format check
                        'is_unique'    => 'Email is already existing. Please try another email',
                    ]
                ]
            ];
			if ($this->validate($rule)) {
				if ($this->request->getMethod() == 'post') {
					$this->args = [ 'uid'  => $this->frontdesk_uid, ];
					$this->user_data_arr = [
						'username'    	=>  $this->request->getPost('username', FILTER_SANITIZE_STRING),
						'about_me'    	=>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
						'gender'     	=>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
						'country_code'  =>  $this->request->getPost('selectedCountryCode', FILTER_SANITIZE_STRING),
						'mobile'     	=>  $this->request->getPost('mobile', FILTER_SANITIZE_STRING),
						'country'     	=>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
						'state'     	=>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
						'city'     		=>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
						'address'     =>  $this->request->getPost('address', FILTER_SANITIZE_STRING),
						'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
						'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
						'updated_at'    => date('Y-m-d H:i:s'),
						'updated_by'    => $this->frontdesk_uid,
					];
					
					$this->data['profile_updt_status'] = $this->adminModel->update_rec_by_args('blood_bank_users', $this->args, $this->user_data_arr);
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
					'error'		=> false, //error: `false` whereever status is false with SQL err
					'code'      => 200,
					'message'   => 'Oops.!, Mandatory validation failed.!',
					'data'      => $this->data,
				);
				return json_encode($this->result_arr);
			} 
        } //else -loop closed
    } //function -  closed
	
	

	/* @params: Function for update view profile
	* @desc: Admin can update view profile 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 23rd Oct,2023
	* @modify
	*/
	public function update_view_profile($id){
		if (!(session()->has('frontdesk_session_uid'))) {
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
		//return redirect()->to(base_url().'/Admin/edit_doctor/'.$id);
		return redirect()->to(base_url() . 'Frontdesk/view_profile/');
	} //Function - Closed

	/* @params: Function for update view profile
	* @desc: Admin can update view profile 
	* @use: Admin
	* @return:
	* @author: Neoarks Team
	* @date: 23rd Oct,2023
	* @modify
	*/
	public function add_appointment_pat_charge($id){
		//if (!(session()->has('frontdesk_session_uid'))) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'id'  => $id
		];
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
				$status = $this->commonForAllModel->Insertdata('patients_pay_charges', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulations ! Patient charges added successfully ', 3);
					//return redirect()->to(base_url().'Frontdesk/discharge_apmnt_patient/'.$id);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Failed to add Patient charges ', 3);
					return redirect()->to(base_url() . 'Frontdesk/discharge_apmnt_patient/' . $id);
				}
				return redirect()->to(base_url() . 'Frontdesk/generate_apment_patient_bill/' . $id);
			} else {
				$data['validation'] = $this->validator;
			}
		}
		$data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		//1 march 2025 added
		//start: added new code to handle if there is no data comes from database 
		if($data['get_patient']){
			return view('Frontdesk/discharge_appointment_pat', $data);
		}
		else{
			$this->session->setTempdata('error', 'There is no payment to receive this patient', 2);
			return  redirect()->to(base_url().'Frontdesk/manage_discharge_patients');
		}
		//end
		//return view('Frontdesk/discharge_appointment_pat', $data);

	} //Function - Closed


   /* @params: Show Doctor availability slots - //Ref Doctor/doctors_available_slots($puid)
	* @desc: Available slots may be booked/take appointments by patients (and even 
	* Doctors, Frontdesk admin and Administrator as well)
	* @author: Neoarks Team
	* @date: June 28th, 2023
	* @modify:
    */
	public function doctors_available_slots($pid){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->data['patient_id'] = (int) $pid; //0: for non-logged-in Patient or Patient ID
		$this->args_dr = [
			'login_acc' => 1,
			'status' => 'Active' // OR Verified
		];
		$this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
		
		$this->args = [
			//'dr_available' => 1,
			'appointment_date' => date('Y-m-d') //Current date
		];
		$this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args); 
		return view('Frontdesk/show_dr_available_slots', $this->data);
	} // Function - Closed


	/* @param: Function for delete_patients
     * @description: delete_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function all_appointments() { 
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		else{
			$args = [ 'is_del'	=>	0 ];
			$data = [
					'all_appointments' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
					'pager'     => $this->commonForAllModel->pager
				];
			
			$this->args_dr = [
				'login_acc' => 1,  //1: Dr. Login account exist, 0: Login account NOT exist
				'status' => 'Active' // OR Verified
			];
			$data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr);
			return view('Frontdesk/all_appointments', $data);
		}
	} // Function - Closed

	/* @params: Show Doctor availability slots based on selected Doctor and Date
	 * @desc: Available slots may be booked/take appointments by patients 
	 * (and even Doctors, Frontdesk admin and Administrator as well)
	 * @use: Book Appointment @Home page
	 * @author: Neoarks Team
	 * @date: May 30th, 2023
	 * @modify:
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
		// return view('Patients/show_dr_available_slots', $this->data);
		 	return view('Frontdesk/show_dr_available_slots', $this->data);
	}// Function - Closed

	
	/* @params: Show Doctor availability slots based on selected Doctor and Date
	 * @desc: Available slots may be booked/take appointments by patients (and even Doctors, Frontdesk admin and Administrator as well)
	 * @use: Book Appointment @Home page
	 * @author: Neoarks Team
	 * @date: May 30th, 2023
	 * @modify:
	 */
	public function pick_slots() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->data['dr_name'] = $this->request->getGet('dr_name');
		$this->data['dr_id'] = $this->request->getGet('dr_id');		
		$this->data['dt'] = $this->request->getGet('dt');
		$this->data['pick_slt'] = $this->request->getGet('pick_slt');
		$this->data['slot_id'] = $this->request->getGet('slot_id');

		if(!isset($this->data['dr_id']) || $this->data['dr_id'] == '') {
			$this->session->setTempdata('error', 'Doctor ID is missing.!', 3);
			$this->data['dr_slots'] = array(); //Just for addressing notice
			$this->data['doctors'] = array();  //Just for addressing notice 
			return view('Frontdesk/show_dr_available_slots', $this->data);
		}
		else { //Get doctors & Departments for appointment page 
			$this->args = [ 'ref_id' => $this->data['dr_id'] ]; //Doctor ID
			$this->data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args);
			//echo "<pre>";var_dump($this->data['doctors']);die;
			//$this->data['department'] = $this->commonForAllModel->fetch_all_records('department');
			//$this->session->setTempdata('success', 'Congratullations!, Doctor slots are availble. ', 3);
			return view('Frontdesk/doctor_appointment', $this->data);
		}
	} //Funciton - Closed




	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function upload_patients(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} else {
			$frontdesk_uid = session()->get('frontdesk_session_uid'); //Loggedin User uid
			$data = [];
			$data['validation'] = null;
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'patient_name'  => [
						'rules'     => 'required|min_length[4]|max_length[20]',
						'errors'    => [
						'required' => 'Patient name is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 20.'
						],
					],

					 'patient_phone'  => [
						'rules'     => 'required|numeric|exact_length[10]',
						'errors'    => [
							'required' => 'Mob number is mandatory'
					 	],
					 ],
					 'patient_address'  => [
						'rules'     => 'required|min_length[6]|max_length[50]',
						'errors'    => [
						'required' => 'Patient Address is mandatory.',
						'min_length' => 'Minimum length is 6.',
						'max_length' => 'Maximum length is 50.'
						],
					],

					 'doctor_name'  => [
						'rules'     => 'required',
						'errors'    => [
							'required' => 'Doctor name is mandatory'
					 	],
					 ],

					 'patient_issue'  => [
						'rules'     => 'required',
						'errors'    => [
							'required' => 'Patient issue is mandatory'
					 	],
					 ],
				];

				if ($this->validate($rules)) {
					$uid = md5(str_shuffle('abcdefghizklmnopqrstuvwxyz' . time()));
					$img = $this->request->getFile('patient_image');
					if ($img->isValid() &&  !$img->hasMoved()) {
						$newName = $img->getRandomName();
						$img->move(FCPATH . 'uploads/patients', $newName);
						$doc_img = $img->getName();
						//Generate Serial - START
						$this->new_serial_arr = $this->generate_serial('booked_doctor_appointment', 'booking_date');
						if (is_array($this->new_serial_arr) && count($this->new_serial_arr) > 0) {
							if (isset($this->new_serial_arr['serial'])) {
								$this->new_serial = $this->new_serial_arr['serial'];
							} 
							else {
								$this->session->setTempdata('error', 'Serial index is missing', 3);
								return redirect()->to(base_url() . 'Frontdesk/add_patients');
							}
						} 
						else {
							$this->session->setTempdata('error', 'Unexpected serial format', 3);
							return redirect()->to(base_url() . 'Frontdesk/add_patients');
						} //Generate Serial - END

						//Generate Puid - START
						$this->puid = 0; //Just for addressing notices
						$this->appmt_data_arr = [
							'patient_name'		=>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
							'serial'			=>	$this->new_serial,
							//'pid'				=>  $this->patient_session_id, //patient auto increment ID
							'patient_email'     =>  $this->request->getVar('patient_email'),
							'patient_mobile'    =>  $this->request->getVar('patient_phone', FILTER_SANITIZE_STRING),
							'age'    			=>  $this->request->getVar('patient_age', FILTER_SANITIZE_STRING),
							'gender'    		=>  $this->request->getVar('patient_gender', FILTER_SANITIZE_STRING),
							'puid'    			=>  $this->puid, //Patient's ID
							'booking_date'      =>  date('Y-m-d'),
							'booking_time'      =>  date('h:i:s'),
							'disease_symptoms'  =>  $this->request->getVar('patient_issue', FILTER_SANITIZE_STRING),
							'description'       =>  $this->request->getVar('other_info', FILTER_SANITIZE_STRING),
							'address'       	=>  $this->request->getVar('patient_address', FILTER_SANITIZE_STRING),

							'doctor_id'         =>  $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING), //1st july, 2023
							'doctor_name'       =>  $this->request->getVar('doc_name', FILTER_SANITIZE_STRING),
							//'status'            => 4, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
							'status'            => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
							'created_at'        =>  date('Y-m-d h:i:s'),
							'created_by'		=>	$frontdesk_uid
						];


						$status = $this->commonForAllModel->Insertdata('booked_doctor_appointment', $this->appmt_data_arr);
						if ($status === true) {
							$this->session->setTempdata('success', 'Congratulation ! Patients Added Successfully !', 3);
						} else {
							$this->session->setTempdata('error', 'Sorry ! Unable to Add  Patients  Try Again ?', 3);
						}
						return redirect()->to(base_url() . 'Frontdesk/manage_patients');
						
					} 
					else {
						echo $image->getErrorString() . " " . $image->getError();
						$this->session->setTempdata('error', 'Please Select any Image File', 3);
						return redirect()->to(base_url() . 'Frontdesk/add_patients');
					}
				} else {
					$data['validation'] = $this->validator;
				}
			} else {
				$this->session->setTempdata('error', 'Sorry ! Required POST method.!', 3);
				return redirect()->to(base_url() . 'Frontdesk/add_patients');
			}
			// $data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor_fee');
			$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
			return view('Frontdesk/add_patients', $data);
		} //else loop - Closed
	} //Function - Closed
 
	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/

	public function add_fee($id, $status, $pid, $puid, $serial, $dr_id) {
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		if ($this->request->getMethod() == 'get') {
			$this->apmt_id = (int) $id; //appointment ID
			$this->status = (int) $status; //Status
			$this->pid = (int) $pid; //Patient ID
			$this->puid = $puid; //Hospital assigned patient unique ID
			$this->appt_serial = (int) $serial; //Appointment serial 
			$this->dr_id = (int) $dr_id; //Appointment serial 
			//echo $this->dr_id; die;
			$data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function

			$data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices

			//$dr_args = [ 'id'  => $this->dr_id  ];
			$dr_args = [ 'ref_id'  => $this->dr_id  ];
			$data['doctor_info'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $dr_args); 

			$apmt_args = [ 'id'  => $id ]; 
			$data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);

			$apmt_pay_args = [ 'apmt_id'  => $this->apmt_id ]; 
			$data['apmt_paymnt'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $apmt_pay_args);
			if($data['apmt_paymnt'] === false) { 
				$data['appointment_fee'] = APMT_REGIS_FEE; 
				$data['apmt_paymnt_payid'] = 0; //Becoz payid is passed in next called function
			} 
			else if(isset($data['apmt_paymnt'][0]->registration_fee)) { 
				$data['appointment_fee'] = (APMT_REGIS_FEE - $data['apmt_paymnt'][0]->registration_fee); 
				$data['apmt_paymnt_payid'] = $data['apmt_paymnt'][0]->id; //becoz payid is passed in next called function
			}
			else if(!isset($data['doctor_info'])) {
				$this->session->setTempdata('error', 'Doctor detail are missing', 3); 
				return view('Frontdesk/payments/add_fee', $data); // Pass the data to the view
			}
			else { 
				$this->session->setTempdata('error', 'Unsupported appointment fee', 3); 
				return view('Frontdesk/payments/add_fee', $data); // Pass the data to the view
			}
		}
		else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
		return view('Frontdesk/payments/add_fee', $data); // Pass the data to the view
	} //function - Closed

	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	
	public function save_fee($patient_id, $pid, $apmtid, $puid, $serial, $payid) {
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}

		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$this->patient_id = (int) $patient_id; //Patient ID
		$this->apmt_id 	  = (int) $apmtid; //appointment ID
		$this->pid 	= (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		$this->pay_chrg_id =  (int) $payid;
		
		$this->data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
		$this->apmt_args = ['id' 	=> $this->apmt_id];
		$this->data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->apmt_args);

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
		$this->data['doctor_info'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->dr_args);

		if ($this->request->getMethod() == 'get') {
			return view('Frontdesk/add_fee', $this->data); //Reunder Fee form
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
					'updated_by' => $this->frontdesk_uid,
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
						'created_by'	=>  $this->frontdesk_uid,
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
						'created_by'	=> $this->frontdesk_uid,
					]; 

					$this->apmt_args = ['id' =>	$this->apmt_id];
					
					//$this->last_inst_id = $this->commonForAllModel->return_inserted_id_n_update('patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
					$this->last_inst_id = $this->commonForAllModel->insert_two_n_update_one_tbl('treatment_expenses_history', $this->expnc_chrg_arr, 'patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
					
					$this->data['apmt_paymnt_payid'] = $this->last_inst_id; //used in next call - perhapse
					if ($this->last_inst_id === 2) { //Failed to update appointment table
						return redirect()->to(base_url().'Frontdesk/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					}
					else if ($this->last_inst_id === 3) { //Failed to insert into patient pay charges table
						return redirect()->to(base_url().'Frontdesk/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else if ($this->last_inst_id === 4) { //Failed to insert treatment expences table 
						return redirect()->to(base_url().'Frontdesk/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
					} 
					else { //success:
						return redirect()->to(base_url() . 'Frontdesk/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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

	/*@params:
	* @desc: Add fee form load
	* @retuns:
	* @author: Neoarks Team
	* @date: 4th November, 2023
	* @modify:
	*/
	public function generate_initial_pay_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} 
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$this->patient_id = (int) $patient_id; //patients tbl id 
		$this->pid = (int)$pid;
		$this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
		$this->puid = $puid;
		$this->apmt_id = (int) $apmtid; //Appointment ID
		$this->status = 4; // 4: Attended Patient
		$this->appt_serial = (int) $serial; //Hard coded - need to replace with dynamic serail
		$this->updt_status = true; //Just for addressing notices
		
		$args = ['id'  => $this->pay_chrg_id ]; //Last inserted Pay charges ID
		$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
		if($data['payment_bill'] === false) {
			$this->session->setTempdata('error', 'Payments bill is not found ', 3);
			return redirect()->to(base_url() . 'Frontdesk/add_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
		}
		if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
		$args = [ 'id'  => $this->patient_id ]; 
			$update_dt_arr = [
					'status'  => 'Fee Paid', 
					'updated_at'  => date('Y-m-d H:i:s'),
					'updated_by'  => $this->frontdesk_uid,
				];
			$this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
		}
		$apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
		$data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
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
			return view('Frontdesk/payments/generate_initial_fee_bill', $data);
		}
		else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
			//$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
			$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
			$this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
			return view('Frontdesk/payments/generate_initial_fee_bill', $data);
		}
		else {
			$this->session->setTempdata('error', 'Unexpected result', 3);
			return view('Frontdesk/payments/generate_initial_fee_bill', $data);
		}
	} //function - Closed


	/* @param: Function for filter_dischrage_patient
	* @description: filter_dischrage_patient details
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
	*/
	public function filter_dischrage_patient($filter){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		if ($filter == 'new_patients') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
        else if ($filter == 'old_patients') {//else if-START
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}//else if-CLOSED
        else{
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		$args = [
			'status'        => 'Discharged'
		];
		$data = [
			//'patients' => $this->patient_model->filter_rec_by_args_with_pagination('patients', $order, $args),
			'patients' => $this->commonForAllModel->filter_rec_by_args_with_pagination('patients', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		return view("Frontdesk/manage_disc_patients",$data);
	} //Function - Closed

    /*@param: Function for payment_dischrge_patient
	* @description: payment_dischrge_patient details
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function payment_dischrge_patient($id){ //echo "I'm here";
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'patients_id'      => $id,
			'status'           => 'Discharged'
		];

		$data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
		if ($data['payment_bill'] == false) {
			$this->session->setTempdata('error', 'Discharge patients payment info is missing.', 3);
			return redirect()->to(base_url() . 'Frontdesk/manage_discharge_patients');
		}
		return view('Frontdesk/payment_dischrge_patient', $data);
	} //Function - Closed

	/* @param: Add ward as Frontdesk
	* @desc: 
	* @use: Admin,Frontdesk
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function discharge_summary(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");}
		return view('Frontdesk/discharge_summary');
	} //Function - Closed

	/* @param: Upload ward function  as Doctor
	* @desc: Upload details of ward
	* @use: Admin,Doctor
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function upload_summary(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$data = [];
		$data['validation'] = null;
		if ($this->request->getMethod() == 'post') {
			$rules = [
				'patient_name'      => 'required',
			];
			if ($this->validate($rules)) {
				$this->user_data_arr = [
					'patient_name'           =>  $this->request->getVar('patient_name', FILTER_SANITIZE_STRING),
					'prescription_detail'              =>  $this->request->getVar('prescription_detail'),
					'status'                => 'Active',
					'created_at'            =>  date('Y-m-d h:i:s')
				];
				$status = $this->commonForAllModel->Insertdata('prescription_history', $this->user_data_arr);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! Discharge summary added Successfully !', 3);
				} else {
					$this->session->setTempdata('error', 'Sorry ! Unable to Add  Discharge  Try Again ?', 3);
				}
				return redirect()->to(base_url() . 'Frontdesk/all_patients');
			} else {
				$data['validation'] = $this->validator;
			}
		}
		return view('Frontdesk/discharge_summary', $data);
	} //Function - Closed

	/* @param: clear_final_payment
	* @desc: clear_final_payment
	* @use: Admin,Doctor
	* @return:
	* @author: Neoarks Team
	* @date: 11th Nov,2023
	* @modify
	*/
	public function clear_final_payment($id){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
	
		// Check if $id is valid and represents a patient or a unique identifier.
	
		// Define validation rules for the form
		$rules = [
			'total_hospital_expenses'    => 'required|numeric',
			'total_patient_paid_amount' => 'required|numeric',
			'final_adjusted_amount'     => 'required|numeric',
			'remark'                    => 'required'
		];
	
		// Check if the request method is POST (form submission)
		if ($this->request->getMethod() == 'post') {
			if ($this->validate($rules)) {
				// Validation successful
	
				// Gather form input data
				$total_hospital_expenses = $this->request->getPost('total_hospital_expenses');
				$total_patient_paid_amount = $this->request->getPost('total_patient_paid_amount');
				$final_adjusted_amount = $this->request->getPost('final_adjusted_amount');
				$remark = $this->request->getPost('remark');
	
				// Calculate any necessary adjustments or refunds here
				// Adjust the final payment or perform other relevant actions
	
				// Redirect or display a success message
			} else {
				// Validation failed, return to the form with validation errors
				$data['validation'] = $this->validator;
			}
		} else {
			$this->session->setTempdata('error', 'Unsupported request method', 3);
		}
	
		// Fetch patient data or any other required data
		$args = [
			'patients_id'  => $id,
			// 'status' => 'Paid', 
			// 'payment_date' => date('Y-m-d H:i:s')
		];
	
		$fld = 'total_patient_paid_amount';
		$data['total_pay_amt'] = $this->commonForAllModel->fetch_sum_by_args('patients_pay_charges',$fld, $args);

		$fld = 'total_price';
		$data['total_expnc_amt'] = $this->commonForAllModel->fetch_sum_by_args('treatment_expenses_history',$fld, $args);
		$args = [ 'id'  => $id ];

		$data['patients'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
		// Pass the data to the view
		return view('Frontdesk/clear_final_payment', $data);
	} //Function - Closed
	
	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function change_frontdesk_password(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		} else {
			$this->frontdesk_uid = session()->get('frontdesk_session_uid');
			$data = [];
			$data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->frontdesk_uid, 'blood_bank_users');
			if ($this->request->getMethod() == 'post') { 
				$rules = [
					'old_password' => 'required',
					// 'new_password' => 'required|min_length[6]|max_length[20]',
					'new_password'  => [
						'rules'     => 'required|min_length[6]|max_length[20]',
						'errors'    => [
						'required' => 'New password is mandatory.',
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
						$status = $this->doctorModel->updatePassword('blood_bank_users', $npwd, session()->get('frontdesk_session_uid'));
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
				} else {
					$data['validation'] = $this->validator;
				}
			}
		}
		return view('Frontdesk/change_frontdesk_password', $data);
	}//Function- Closed

	
    /*@param: Function for manage_today_discharged_patient
	* @description: manage_today_discharged_patient details
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function manage_today_discharged_patient() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
        else{
			$args = [
				'status'  => 'Discharged',
				'registration_date'  => date('Y-m-d h:i:s')
			];
			$data = [
				// 'patients'  => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
				'patients'  => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'pager'   => $this->commonForAllModel->pager
			];
        	return view("Frontdesk/manage_disc_patients",$data);
		}
	}//Function - Closed
    
	
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function search_doctor() {
		if (!(session()->has('frontdesk_session_uid'))) {
		return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$args = [
			'doctor_name'   => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING)
		];
		// $data['doctors'] = $this->commonForAllModel->fetch_rec_by_args_by_like('doctor',$args);
		$data['doctors'] = $this->commonForAllModel->fetch_rec_by_args_by_like('doctor',$args);
		return view('Frontdesk/manage_doctor', $data);
	}//function-close

    /* @param: Function for search_discharge_patient
	* @description: search_discharge_patient  details
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function search_discharge_patient(){
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}   
		
		$data = [
			'patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->commonForAllModel->pager
		];
		return view('Frontdesk/manage_disc_patients', $data);
	} //Function - CLosed
	
 	/* @param: Function for forget_password
	* @description: forget_password  
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 21st June, 2023
    */
	public function forget_password(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$data = [];
			if ($this->request->getMethod() == 'post') {
				$rules = [
					'email' => [
						'label'  => 'Email',
						'rules'   => 'required|valid_email',
						'errors'  => [
							'required'    => ' Email is required',
							'valid_email' => 'Please Enter valid Email required'
						]
					],
				];

					if ($this->validate($rules)) {
						$email = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
						$userdata = $this->loginModel->verifyEmail_with_args('register_all_users',$email);
						if (!empty($userdata)) {
							$update = $this->loginModel->updatedAt('register_all_users',$userdata['uid']);
							if ($update) {
								$to = $email;
								$subject  = 'Reset Password Link';
								$token    = $userdata['uid'];
								$message  = 'Hi ' .$userdata['username']. '<br><br>'
												.'Your Reset Password request has been Received. Please Click'
												.'the below Link to reset your Password.<br><br>'
												.'<a href="'.base_url().'/Frontdesk/reset_password/'.$token.'">Click Here to Reset Password</a>'
												.'<br>Thanks <br> '
												. DEV_TEAM . '<br>'
												.DEV_AUTHOR . ' at ' . WEBSITE . '<br>';

								$this->email->setTo($to);
								$this->email->setFrom('ADMIN_EMAIL', 'Software Developer & Blogger');
								$this->email->setSubject($subject);
								$this->email->setMessage($message);
								if ($this->email->send()) {
									$this->session->setTempdata('success', 'Reset Password Link Send to Your Email Please Check and Verify, with in 15min',3);
									return redirect()->to(current_url());
								}else{
									$data = $this->email->printDebugger(['headers']);
									print_r($data);
								} 

								
							}else{
								$this->session->setTempdata('error', 'Sorry ! Unable to Update Try Again ?', 3);
								return redirect()->to(current_url());
							}
							
						}else{
							$this->session->setTempdata('error', 'Sorry Email Does Not Exists Try Again valid Email?', 3);
							return redirect()->to(current_url());
						}
					}else{
						$data['validation'] = $this->validator;
					}
				}
		return view('Frontdesk/forget_password', $data);
	} //Function - Closed

	/* @param: Function for manage_patients
     * @description: Manage patient details
	 * Remark: It provide a list of patients that have added previously.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 20th June, 2023
     */
	public function manage_patients(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		else {
			$args = [ 'is_del'  => 0, ];
			$data = [
				'patients' => $this->commonForAllModel->fetch_rec_by_args_arr('patients', $args),
				'pager'     => $this->commonForAllModel->pager
			];
			if(!isset($data['patients']) || $data['patients'] == '' ||  $data['patients'] == false) { 
				$this->session->setTempdata('error', 'Sorry ! No patient found. Please add patient first', 3);
				return redirect()->to(base_url()."Frontdesk/add_patients");
			}
			return view("Frontdesk/manage_patients", $data);
		}
	} //function - Closed

  	/* @param: Function for edit_patients
     * @description: edit_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function edit_patients($id) { 
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'id'  => $id
		];
		// $data['patients'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		$data['patients'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		// $data['doctors']  = $this->commonForAllModel->fetch_all_records('doctor');
		$data['doctors']  = $this->commonForAllModel->fetch_all_records('doctor');
		return view('Frontdesk/edit_patients',$data);
	} // Function - closed

    //Custom

	/* @param: Function for search_patient
     * @description: search_patient details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function search_patient(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}   
		
		$data = [
			'patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
			'pager' => $this->commonForAllModel->pager
		];
		
		return view("Frontdesk/manage_patients", $data);
	} //FUnction - Closed
	

	// /*********************** Appointent START ************************/
	/* @param: Function for search_patient
     * @description: search_patient details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	// public function book_appointment() {
	// 	$this->patient_session_id = session()->get('patient_session_id');
	// 	$data = [];
	// 	$data['validation'] = null;
	// 	if ($this->request->getMethod() == 'post') {
	// 		$rules = [
	// 			// 'name'              => 'required|min_length[4]|max_length[20]',
	// 			'name'  => [
	// 				'rules'     => 'required|min_length[4]|max_length[50]',
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
	// 				return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
	// 			}
	// 		}
	// 		else {
	// 			$this->session->setTempdata('error', 'Unexpected serial data', 3);
	// 			return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
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
				
	// 			//echo "<pre>"; print_r($userdata);die;
	// 			$status = $this->adminModel->Insertdata('booked_doctor_appointment', $userdata);
	// 			if ($status) {
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
	// 						return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
	// 					}
	// 					else{
	// 						$this->session->setTempdata('success', 'Appointment has booked successfully !, however, unable to email to Doctor', 3);
	// 						return view('Frontdesk/appointment_payment');
	// 					}
	// 				}
	// 				else{
	// 					$this->session->setTempdata('error', 'Unable to book appointment. Please try again', 3);
	// 					return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
	// 				}
	// 			}
	// 			else{
	// 				$this->session->setTempdata('error', 'Sorry ! Unable to book appointment. Please try again', 3);
	// 				return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
	// 			}  
	// 			// Add New Patient - END
	// 		}	
	// 		else { 
	// 			$this->session->setTempdata('error', 'Failed due to missing mandatory fields.!', 3);
	// 			return redirect()->to(base_url().'Frontdesk/available_selected_doctor_slots');
	// 			// return redirect()->to(base_url().'Home/index');
	// 		}
	// 	}
	// }  // Function - Closed

	/* @param: Function for Book patient/ self appointment 
     * @description: search_patient details from the list of the patients.
     * @date: 21st June, 2023
     * @modify: March 11th, 2025
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */

	public function book_appointment() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_session_uid = session()->get('frontdesk_session_uid');
		if (!(session()->has('patient_session_id'))) {  
			$this->patient_session_id = session()->get('patient_session_id'); //`null` for non-loggedin-user
		}
		if(!isset($this->frontdesk_session_uid) || $this->frontdesk_session_uid == '' || !isset($this->patient_session_id) || $this->patient_session_id == '') {
			$this->frontdesk_session_uid = 0;	//For non-logged-in user
			$this->patient_session_id = 0;	//For non-logged-in user
		}

		$this->is_new_patient = 1; //For addressing notices -New Patient
		$userdata = array(); //Just for addressing notices

		if (!isset($this->patient_session_id) || ($this->patient_session_id == '') || (!isset($this->frontdesk_session_uid)) || ($this->frontdesk_session_uid == '')) {
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
					'rules'     => 'required|max_length[5]',
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

			$this->slot_id = $this->request->getPost('slot_id',FILTER_SANITIZE_STRING);
			if(isset($userdata['puid']) && $userdata['puid'] != '' ) { //Check, "Is Revisited Patient?"
				$this->is_new_patient = 0; //0: For old patients 1: New/First time visited Patients
			} 
			
			if (!$this->validate($rules)) { 
				$this->result_arr = array(
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'	=> 200,
					'message'=>	'Failed due to missing mandatory fields!',
					'data' => $userdata,
				);
				return json_encode($this->result_arr);
			}
			$userdata = [
				'patient_name'		=>  $this->request->getPost('patient_name',FILTER_SANITIZE_STRING),
				'serial'			=>	$this->new_serial,
				'pid'				=>  $this->patient_session_id, //patient auto increment ID
				'patient_email'     =>  $this->request->getPost('email'),
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
				'description'       =>  $this->request->getPost('desc',FILTER_SANITIZE_STRING),
				'address'       	=>  $this->request->getPost('address',FILTER_SANITIZE_STRING),
				'status'			=>	1, //Appointment Initiated,
				'doctor_id'         =>  $this->request->getPost('doctor_id',FILTER_SANITIZE_STRING),
				'doctor_name'       =>  $this->request->getPost('doctor_name',FILTER_SANITIZE_STRING), //Custom
				'status'            =>  1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
				'created_at'        =>  date('Y-m-d h:i:s'),
				'created_by'        =>  $this->frontdesk_session_uid,
			];
			
			$last_insrt_apmt_id = $this->commonForAllModel->Insertdata_return_id('booked_doctor_appointment', $userdata);

			if((int) $last_insrt_apmt_id > 0) { 
				$userdata['last_insrt_apmt_id'] = $last_insrt_apmt_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
				$userdata['appointment_date'] 	= $this->request->getPost('appointment_date',FILTER_SANITIZE_STRING); //For sending email
				
				$userdata['appointment_time'] 	= date('h:i:s'); //For sending email 
				$userdata['slot_id'] 	= $this->slot_id; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee
				$userdata['uid'] 	= $this->frontdesk_session_uid; //For updating `booked_doctor_appointemnt` during receiving appointemnt fee

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

	
	/* @params: Render appointment payment page
	 * @desc:  
	 * @date: February 13th, 2025
	 * @modify:
	 * @author: Neoarks Team
	 * @copyrights: Neoark Software Pvt Ltd
	 */
	public function run_appointment_fee_form() {
		return view('Frontdesk/appointment_payment');
	}

	/* @params: Show Doctor availability slots
	 * @desc: Available slots may be booked/take appointments by 
	 * patients (and even Doctors, 
	 * Frontdesk admin as well)
	 * @author: Neoarks Team
	 * @date: February 13th, 2025
	 * @modify:
	 */
	public function doctors_available_slots_neo($pid, $seldate = '', $seldocid = '') { 
		$this->dr_id = $seldocid;
		$this->args_dr = [
			'login_acc' => 1,  //1: Dr. Login account is Available  0: Dr.Login NOT available
			'status' => 'Active' // Active ie Admin Verified
		];
		$this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
		
		if($this->data['doctors'] === false ) { 
			$this->data['doctors'][0] = new \stdClass; //stdClass object
			$this->data['doctors'][0]->id = 0;  		//Used in view
			$this->data['doctors'][0]->doctor_name = '';//Used in view
			$this->data['doctors'][0]->education = '';	//Used in view
			$this->data['doctors'][0]->dr_specialization = ''; //Used in view

			$this->session->setTempdata('error', 'No doctor is registered yet!', 3);
			return redirect()->to(base_url() . "Home/index");
		}
		
		if($seldate == '') { $appointmntdate = date('Y-m-d'); }
		else { $appointmntdate = $seldate; }
		
		if ($this->dr_id == 0 || $this->dr_id == "") {
			$this->args = [
				#'doctor_slots.dr_available' => 1,//1: Dr. Available  0: Dr. unavailable
                'doctor_slots.appointment_date' => $appointmntdate,
			];
		}
		else { 
			$this->args = [
				#'doctor_slots.dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
                'doctor_slots.doctor_id' => $this->dr_id,
                'doctor_slots.appointment_date' => $appointmntdate,
			];
		}
		$this->left_tbl = 'doctor_slots';
		$this->right_tbl = 'doctor';
		$this->join_terms =  'doctor_slots.doctor_id = doctor.ref_id';
		
		$this->data['dr_slots'] = $this->commonForAllModel->fetch_rec_by_args_leftjoin($this->left_tbl, $this->right_tbl, $this->join_terms, $this->args);

		if($this->data['dr_slots'] == false ) {
			$this->session->setTempdata('error', 'Sorry, No doctor slot is available !', 3);
			return view('Frontdesk/show_dr_available_slots', $this->data);
		}
		return view('Frontdesk/show_dr_available_slots', $this->data);
	}// Function - Closed

// /*********************** Appointent END ************************/
	/* @param: Function for search_all_patient
     * @description: search_all_patient details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function search_all_patient(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."/Frontdesk_login/login_account");}
			$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
			$args = [
				//'status'  => 'Discharged'
			];
			if ($keyword) {  
				$result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword,  $args);
			}   
			else{  
				$result = $this->commonForAllModel;
			}   
			
			$data = [
				'patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'pager'     => $this->commonForAllModel->pager
			];
		return view("Frontdesk/all_patients", $data);
	}//Function- Closed

	/* @param: Function for change_all_patients_status
     * @description: change_all_patients_status
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st July, 2023
     */
	// public function change_all_patients_status($id, $status){ 
	// 	if (!(session()->has('frontdesk_session_uid'))) {
	// 		return redirect()->to(base_url()."/Frontdesk_login/login_account");
	// 	}
	// 	else{
	// 		$frontdesk_uid = session()->get('frontdesk_session_id');
	// 		if(!isset($frontdesk_uid['id']) || $frontdesk_uid['id'] == '') { 
	// 			return redirect()->to(base_url()."Frontdesk_login/login_account");
	// 		}

	// 		$args = [ 'id'  => $id ];
	// 		$data = [ 
	// 			'status'  => $status,
	// 			'updated_at' => date('Y-m-d H:i:s'),
	// 			'updated_by' => $frontdesk_uid['id'],
	// 		];
	// 		$status = $this->commonForAllModel->update_rec_by_args('patients', $args, $data);
	// 		if ($status == true) {
	// 			$this->session->setTempdata('success', 'Congratulation ! action done  successfully !', 3);
	// 		}else{
	// 			$this->session->setTempdata('error', 'Sorry ! Unable to discharge. Please try again ?', 3);
	// 		}
	// 		return redirect()->to(base_url().'Frontdesk');
	// 	} //else loop - Closed
	// }

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function change_all_patients_status($id, $status){ 
		if (!(session()->has('frontdesk_session_uid'))) {     
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}    
		else{    
			 $doctor_id = session()->get('doctor_session_id');
			if(!isset($doctor_id) || $doctor_id == '') {  
				return redirect()->to(base_url()."Frontdesk");
			} 

			$args = [ 'id'  => $id ];
			$data = [ 
				'status'  => $status,
				'updated_at' => date('Y-m-d H:i:s'),
				'updated_by' => $doctor_id,
			];
			$status = $this->commonForAllModel->update_rec_by_args('patients', $args, $data);
			if ($status == true) {  
				$this->session->setTempdata('success', 'Congratulation ! action done  successfully !', 3);
			} 
			else{ 
				$this->session->setTempdata('error', 'Sorry ! Unable to discharge. Please try again ?', 3);
			}
			return view("Frontdesk", $data);
		} //else loop - Closed
    
	} //Function - Closed

    /* @param: Function for delete_patients
     * @description: delete_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	// public function all_appointments() { 
	// 	if (!(session()->has('frontdesk_session_uid'))) {
	// 		return redirect()->to(base_url()."/Frontdesk_login/login_account");
	// 	}
	// 	else{
	// 		$args = [ 'is_del'=>0 ];
	// 		$data = [
	// 				'all_appointments' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
	// 				'pager'     => $this->commonForAllModel->pager
	// 			];
			
	// 		$this->args_dr = [
	// 			'login_acc' => 1,  //1: Dr. Login account exist, 0: Login account NOT exist
	// 			'status' => 'Active' // OR Verified
	// 		];
	// 		$data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr);
	// 		//echo "<pre>";print_r($data['doctors']);die;
	// 		return view('Frontdesk/all_appointments', $data);
	// 	}
	// }

	/*@params: Show Doctor availability slots based on selected Doctor and Date
	* @desc: Available slots may be booked/take appointments by patients (and even Doctors, Frontdesk admin and Administrator as well)
	* @author: Neoarks Team
	* @date: May 30th, 2023
	* @modify:
	*/
	//public function get_available_selected_doctor_slots() {
	public function all_appointments_from_to_date() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
        $this->data['doctors'] = []; //Just for addressing notices
		$this->data['all_appointments'] = []; //Just for addressing notices
		
		if ($this->request->getMethod() == 'get') {
			$this->dr_id = (int) $this->request->getGet('doctorId');
			$this->apmt_start_dt = $this->request->getGet('startDate'); //Slot start date
			$this->apmt_end_dt = $this->request->getGet('endDate'); //Slot end date
			
			if (!isset($this->dr_id) || $this->dr_id == '') {
				$this->result_arr = [
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code' => 200,
					'message' => 'Doctor ID is missing',
					'data' => array(array()),
				];
				return json_encode($this->result_arr);
			}

			$this->args = [
				'is_del' => 0,
				'doctor_id'	=> $this->dr_id,
			];
			$this->args_dr = [
				'login_acc' => 1,  //1: Dr. Login account exist, 0: Login account NOT exist
				'status' => 'Active', // OR Verified
			];


			$this->data = [
				//'all_appointments' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $this->args),
				'all_appointments' => $this->frontDeskModel->fetch_rec_by_args_datewise('booked_doctor_appointment', $this->args, $this->apmt_start_dt, $this->apmt_end_dt),
				'doctors' 		=> $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr),
				'pager'     => $this->commonForAllModel->pager
			];
		
			if ($this->data['doctors'] === false || $this->data['all_appointments'] === false) {
				$this->result_arr = [
					'status' => false,
					'error'	 => true, //error: `true` whereever status is false with SQL err 
					'code'   => 200,
					'message'=> 'Sorry! No Appointment is availble between selected dates',
					'data'   => $this->data,
				];
				return json_encode($this->result_arr);
			}
			else {
				$this->result_arr = [
					'status' => true,
					'error'	 => false, //error: `false` whereever status is true with SQL err 
					'code'   => 200,
					'message'=> 'Appointments are available between selected dates',
					'data'   => $this->data,
				];
				return json_encode($this->result_arr);
			}
		} 
		else {
			$this->result_arr = [
				'status' => false,
				'error'	 => true, //error: `true` whereever status is false with SQL err 
				'code'   => 200,
				'message'=> 'Invalid request method',
				'data'   => $this->data,
			];
			return json_encode($this->result_arr);
		}
	} //Function - End

	// public function all_appointments_ajax() { 
	// 	if (!(session()->has('frontdesk_session_uid'))) {
	// 		//return redirect()->to(base_url()."/Frontdesk_login/login_account");
	// 		$this->result_arr = array(
	// 			'status' => false,
	// 			'code' => 200,
	// 			'message' => 'Session has expired. Try by relogin again.',
	// 			'data' => array(array())

	// 		); 
	// 		return json_encode($this->result_arr); 
	// 	}
	// 	else{
	// 		$args = [
	// 			//'status'  => 'InActive',
	// 			'is_del'=>0
	// 		];
	// 		$this->args_dr = [
	// 			'login_acc' => 1,  //1: Dr. Login account exist, 0: Login account NOT exist
	// 			'status' => 'Active' // OR Verified
	// 		];
	// 		$this->data = [
	// 				'all_appointments' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
	// 				'doctors' =>  $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr),
	// 				'pager'     => $this->commonForAllModel->pager
	// 			];
	// 		//$this->data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr);
	
	// 		if ($this->data['doctors'] === false) {
	// 			//$this->data['doctors'] = array(array());
	// 			 $this->result_arr = array(
	// 				'status' => false,
	// 				'code' => 200,
	// 				'message' => 'No appointments found.',
	// 				'data' => array(array())

	// 			); 
	// 			return json_encode($this->result_arr); 
	// 		} 
	// 		else {
	// 			$this->result_arr = array(
	// 				'status' => true,
	// 				'code' => 200,
	// 				'message' => 'Appointment fetched successfully.',
	// 				'data' => $this->data
	// 			); 
	// 			return json_encode($this->result_arr);
	// 		}
	// 		//return view('Frontdesk/all_appointments', $data);
	// 	}
	// }


	/* @params: Function for permanent delete all appointment
	* @desc: Frontdesk can soft delete all appointment also a soft deleted data can be permanently delete by this function
	* @use: Admin....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_all_apmnt($id){
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		$args = [
			'id'  =>  $id,
			// 'is_del' => 1
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->frontdesk_uid
		];


		$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);

		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Record Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Frontdesk/all_appointments');
	} // Function - Closed
	
	
	/* @param: Function for search_results
     * @description: search_results details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function search_all_appointments(){
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'Discharged'
		];
		
		if ($keyword) {  
			// Search for data with spaces removed
			$result = $this->commonForAllModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}   
			
		$data = [
			'all_appointments' => $this->commonForAllModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
			'pager' => $this->commonForAllModel->pager
		];
		
		return view('Frontdesk/all_appointments', $data);
	}  // Function - Closed
	
	public function search_today_appointments(){
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}  
		
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
		
		// Remove spaces from the user input
		//$keyword = str_replace(' ', '', $keyword);
		
		$args = [
			//'status' => 'InActive',
			'is_del' => 0,
			'booking_date' => date('Y-m-d')
		];
		
		if ($keyword) {  
			// Search for data with spaces removed from the user's input in the 'patient_name' column
			$result = $this->commonForAllModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
		} else {  
			$result = $this->commonForAllModel;
		}   
		
		$data = [
			'today_apmnt' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
			'pager' => $this->commonForAllModel->pager
		];
		
		return view('Frontdesk/today_appointments', $data);
	} //Function - Closed
	


    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function search_doc_appointment() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$doctor_id = $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING);
		$datefrom = $this->request->getVar('datefrom', FILTER_SANITIZE_STRING);// format Aug 25, 2023
		$dateto = $this->request->getVar('dateto', FILTER_SANITIZE_STRING); // format Aug 25, 2023
		
        $datefrom_obj = new \DateTime($datefrom);  // Create a DateTime object from the input date
		$dateto_obj = new \DateTime($dateto);  // Create a DateTime object from the input date

        $fromdate_Ymd = $datefrom_obj->format('Y-m-d');// Format the date as "Y-m-d" (2023-08-22)
        $dateto_Ymd = $dateto_obj->format('Y-m-d');// Format the date as "Y-m-d" (2023-08-22)
		
		$args = [ 'doctor_id' => $doctor_id, ];
		$this->args_dr = [
			'login_acc' => 1,  //1: Dr. Login account exist, 0: Login account NOT exist
			'status' => 'Active' // OR Verified
		];
		
		$data['doctors'] = $this->commonForAllModel->fetch_rec_by_args('doctor', $this->args_dr);

		$data = [
			'all_appointments' => $this->commonForAllModel->fetch_rec_by_duration_pagination('booked_doctor_appointment', $args, $fromdate_Ymd, $dateto_Ymd),
			'pager'     => $this->commonForAllModel->pager
		];
		return view("/Frontdesk/all_appointments", $data);
	} // Function - closed

	/* @param: Function for search_results
     * @description: search_results details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function search_results(){
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		$keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
			$args = [
				//'status'  => 'Discharged'
			];
			if ($keyword) {  
			 	$result = $this->commonForAllModel->search_records('revisit_patients', 'patient_name', $keyword,  $args);
			}   
			else{  $result = $this->commonForAllModel; }   
			
			$data = [
	            'revisited_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('revisit_patients', $args),
	            'pager'     => $this->commonForAllModel->pager
	        ];
		
		//$data['revisited_patients']  = $this->commonForAllModel->fetch_all_records('doctor');
		if($data['revisited_patients'] == false) {  
			$this->session->setTempdata('error', 'Sorry ! No record found!', 3);
			return redirect()->to(base_url().'Frontdesk/manage_revisit_patient');
		}  
		return view('Frontdesk/manage_revisit_patient', $data);
	}//Function- Closed


	/* @param: Function for delete_patients
	* @description: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function delete_dis_patients($id){
			$this->patient_id = $id;
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		if(!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		$args = [
			'id'   => $id
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->frontdesk_uid
		];
		$status = $this->commonForAllModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success','Congratulation ! Patients has deleted successfully', 2);
			//return redirect()->to(base_url().'Frontdesk/manage_today_discharged_patient');
			return redirect()->to(base_url().'Frontdesk/manage_discharge_patients');
		}
		else {
			session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
			return redirect()->to(base_url().'Frontdesk/manage_discharge_patients');
		}
	} // Function - Closed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function total_discharge_patient() {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}else{
			// $frontdesk_uid = session()->get('frontdesk_session_uid');
			// if(!isset($frontdesk_uid['id']) || $frontdesk_uid['id'] == '') { 
			// 	$this->session->setTempdata('error', 'Frontdesk ID is missing !', 3);
			// 	return redirect()->to(base_url()."/Frontdesk_login/login_account");
			// }
			$args = [
			//	'frontdesk_uid' => $frontdesk_uid['id'],
				'status'       => 'Discharged'
			];
			
			$data = [
				// 'all_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'all_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
				'pager'         => $this->commonForAllModel->pager
			];
			return view('Frontdesk/total_discharge_patient',$data);
		}
	} //Function - Closed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function filter_all_discharge_patient($filter){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		if ($filter == 'new_patient') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}else if ($filter == 'old_patient') {
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
		//$frontdesk_uid = session()->get('frontdesk_session_uid');
		// if(!isset($frontdesk_uid['id']) || $frontdesk_uid['id'] == '') {
		// 	$this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
		// 	return redirect()->to('Frontdesk_login/login_account');
		// }
		$args = [
			//'frontdesk_uid'  => $frontdesk_uid['id'],
			'status'      => 'Discharged'
		];

		$likeArgs = [];//'updated_at' => date('Y-m-d h:i:s')
				
		
		$data = [
			// 'all_patients' => $this->patient_model->fetch_rec_by_args_filter_order_like('patients', $args, $likeArgs, $order),
			'all_patients' => $this->commonForAllModel->fetch_rec_by_args_filter_order_like('patients', $args, $likeArgs, $order),
			'pager'         => $this->commonForAllModel->pager
		];
		return view('Frontdesk/total_discharge_patient',$data);
    } // Function - CLosed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function delete_all_dis_patients($id){
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		if(!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
		return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		$args = [
			'id' => $id
		];
		$data = [
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->frontdesk_uid
		];
		$status = $this->commonForAllModel->update_rec_by_args('patients_pay_charges', $args, $data);

		//$status = $this->patient_model->where('id',$id)->delete();
		if ($status == true) {
			session()->setTempdata('success','Congratulation ! Patients has deleted successfully', 2);
			return redirect()->to(base_url().'Frontdesk/total_discharge_patient');
		}
		else {
			session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
			return view('Frontdesk/total_discharge_patient',$data);
		}
	} // Function - Closed


    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function delete_patients($id){
		$this->patient_id = $id;
        if (!(session()->has('frontdesk_session_uid'))) {
            return redirect()->to(base_url()."/Frontdesk_login/login_account");
        }
        $this->frontdesk_uid = session()->get('frontdesk_session_uid');
        if(!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
            $this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
            return redirect()->to(base_url()."/Frontdesk_login/login_account");
        }
        $args = [ 'id'   => $id ,
		          'is_del'=> 0,
				  //'level'=> 0       //0: Frontdesk & Donor, 1: Admin, 2: Accountant, 3:Doctor, 4: Patient
				];
				
        $data = [
            'status' => 'Deleted', 
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->frontdesk_uid
        ];
        $status = $this->commonForAllModel->update_rec_by_args('patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success','Congratulation ! Patients has deleted successfully', 2);
            //return redirect()->to(base_url().'Admin/manage_today_discharged_patient');
            return redirect()->to(base_url().'Frontdesk/manage_patients');
        }
        else {
            session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
            return redirect()->to(base_url().'Frontdesk/manage_patients');
        }
    } //Function - Closed


    /*@param: change_appointment_status_dsbrd from Dashboard
	* @desc: Since it need to redirect on dashboard, therefore written another function wrt existing function change_Appointment_status
	* @use: 
	* @author: Neoark Team
	* @date: June 21st, 2023
	* @modify:
	* @reference: Admin/change_Appointment_status function
	* @copyrights: Neoark Software Team
    */
	public function change_appointment_status_dsbrd($id, $status){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'id'  => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $args, $data);
		if ($status == true) {  
			$this->session->setTempdata('success', 'Congratulation ! appointment has changed successfully !', 3);
		} 
		else{  
			$this->session->setTempdata('error', 'Sorry ! unable to change appointment. Please try again.', 3);
		}  
		return redirect()->to(base_url().'Frontdesk/index');
	}//Function- Closed

	
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function today_appointments() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
				}else{
		$args = [
			//'status'  => 'InActive',
			'is_del' => 0,
			'booking_date' => date('Y-m-d')
		];
		$data = [
				// 'today_apmnt' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
				'today_apmnt' => $this->commonForAllModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
				'pager'     => $this->commonForAllModel->pager
			    ];
				
				return view('Frontdesk/today_appointments', $data);
		}
	} // Function - Closed

	/* @params: Function for permanent delete today appointments
	* @desc: Admin can soft delete all appointments also a soft deleted data can be permanently delete by this function
	* @use: Frontdesk....
	* @return:
	* @author: Neoarks Team
	* @date: 16th August,2023
	* @modify
	*/
	public function permanent_del_today_apmnt($id){
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		if (!(session()->has('frontdesk_session_uid'))) { 
					return redirect()->to(base_url()."Frontdesk_login/login_account");
				}
		$args = [
			'id'  =>  $id,
			// 'is_del' => 1
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->frontdesk_uid
		];
		$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);

		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Record Deleted Successfully !', 3);
		} else {
			$this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
		}
		return redirect()->to(base_url() . 'Frontdesk/all_appointments');
	} // Function - Closed

	/* @params: Function for permanent delete patients
	* @desc: Frontdesk can soft delete patients also a soft deleted data can be permanently delete by this function
	* @use: Frontdesk....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function permanent_del_patients($id){
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');
		$this->patient_id = $id;
		//if (!(session()->has('frontdesk_session_uid'))) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		// $this->frontdesk_uid = session()->get('frontdesk_session_uid');
		// if (!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
		// 	$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
		// 	return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		// }
		$args = [
			'id'   => $id,
			//'is_del' => 1
		];
		$data = [
			'is_del' => 1,
			'status' => 'Deleted', //0: Non deleted, 1: Deleted
			'updated_at' => date('Y-d-m h:i:s'),
			'updated_by' => $this->frontdesk_uid
		];
		$status = $this->commonForAllModel->update_rec_by_args('patients',  $args, $data);
		if ($status == true) {
			session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
			//return redirect()->to(base_url().'Frontdesk/manage_today_discharged_patient');
			return redirect()->to(base_url() . 'Frontdesk/manage_patients');
		} else {
			session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
			return redirect()->to(base_url() . 'Frontdesk/manage_patients');
		}
	} // Function - Closed

	/* @param: Function for filter_today_appointments
     * @description: filter_today_appointments from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function filter_today_appointment($filter) {
		if (!(session()->has('frontdesk_session_uid'))) {
		return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		if ($filter == 'new_appointment') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
			
		}
		else if ($filter == 'old_appointment') {
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
					'is_del'=>0,
					'booking_date' => date('Y-m-d')
			];
		
		$data = [
			//    'today_apmnt' => $this->commonForAllModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order,$args),
			'today_apmnt' => $this->commonForAllModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order,$args),
			'pager'   => $this->commonForAllModel->pager
		];
		
		return view('Frontdesk/today_appointments', $data);
	} // Function - Closed
	
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function filter_patients($filter){
		if (!(session()->has('frontdesk_session_uid'))) { 
				return redirect()->to(base_url()."Frontdesk_login/login_account");
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
			'patients' => $this->commonForAllModel->filter_rec_by_args_with_pagination('patients', $order, $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Frontdesk/add_patients");
		}
		return view("Frontdesk/manage_patients", $data);
		// return view('Frontdesk/manage_doctor', $data);
	} // Function - Closed

	/* @params: Save Doctor availability slots
	* @desc: Saving slots from toatal available slots.
	* @author: Neoarks Team
	* @date: May 25th, 2023
	* @modify:
	*/
	public function save_slots() { //$new_slt_arr, $old_slt_arr
		
		if (!(session()->has('frontdesk_session_uid'))) {  
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}   
		else{    
			$doctor_id = session()->get('frontdesk_session_id'); 				//Get doctor ID from session
			$doctor_name = session()->get('frontdesk_session_id'); 
			
			$this->selected_slot = $this->request->getJSON();
			//$this->selected_date ='2023-06-11';
			if(!isset($this->selected_date) || $this->selected_date == '') {   
				$this->selected_date = date('Y-m-d');
			}  
			$this->new_slots_arr = array(
				'appointment_date' => $this->selected_date, //date("Y-m-d"),  	//Need to get from availabitlity slot page
				'selected_slot' => $this->selected_slot, //Expected from form  
			);
			
			$this->start_end_slot_arr = $this->new_slots_arr['selected_slot']; //Start_end_slot
			if (is_array($this->start_end_slot_arr)) {   
			$this->tot_slot = count($this->start_end_slot_arr);
			$this->loop = 0; //Just for addressing notices
			if( !isset($frontdesk_uid)) { //See for loop below     
				$this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
				return redirect()->to(base_url()."Frontdesk_login/login_account");
			} 
		} 
			for($s = 0; $s < $this->tot_slot; $s++) {  //for loop - START
				$this->splt_slot_arr = explode('--', $this->start_end_slot_arr[$s]);
				$this->start_slot = $this->splt_slot_arr[0];  //start slot
				$this->end_slot = $this->splt_slot_arr[1];   //end slot

				$this->neo_sots_arr = array(
					'frontdesk_id' => $frontdesk_uid,
					'doctor_name' => $doctor_name,
					'appointment_date' => $this->new_slots_arr['appointment_date'],
					'slot_start' => $this->start_slot,
					'slot_end' => $this->end_slot,
					'created_at' => date("Y-m-d H:i:s"),
					'created_by' => $doctor_id 
				);
				
				if($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) { $this->loop += 1; }
			} //for loop - Closed

			$result = array(
				"status" => true,
				'error'	 => false, //error: `false` whereever status is true with SQL err 
				"code" => 200,
				"message" => 'Slots saved successfully'
			);
			
			return json_encode($result);
		} //else - Closed
	} //Funciton - Closed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function filter_patients_dis_pat($filter){
		//if (!(session()->has('frontdesk_session_uid'))) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "Login");
		}
		$args = [
			'status'        => 'Discharged'
		];
		$data = [
			'patients' => $this->commonForAllModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Frontdesk/add_patients");}
		return view("Frontdesk/manage_patients", $data);
	} // Function - Closed

	/* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
	public function filter_deleted_pat($filter){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [
			'status'        => 'Deleted'
		];
		$data = [
			'patients' => $this->commonForAllModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Frontdesk/add_patients");
		}
		return view("Frontdesk/manage_patients", $data);
		// return view('Frontdesk/manage_doctor', $data);
	} // Function - closed


	/* @params: Function for filter discharge patients
	* @desc: 
	* @use: Frontdesk....
	* @return:
	* @author: Neoarks Team
	* @date: 18th August,2023
	* @modify
	*/
	public function filter_admitted_pat($filter){
		//if (!(session()->has('frontdesk_session_uid'))) {
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url() . "Login");
		}
		$args = [
			'status'        => 'Admit'
		];
		$data = [
			'patients' => $this->commonForAllModel->fetch_rec_by_status_with_pagination('patients', $args),
			'pager'     => $this->commonForAllModel->pager
		];
		if (!isset($data['patients']) || $data['patients'] == '') {
			session()->setTempdata('error', 'No patient found, please add patient first', 2);
			return redirect()->to(base_url() . "Frontdesk/add_patients");}
		return view("Frontdesk/manage_patients", $data);
	} // Function - Closed

	/* @param: Function for change_patients_status
     * @description: change_patients_status details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */

	public function change_patients_status($id, $pid, $status){
			if (!(session()->has('frontdesk_session_uid'))) {
				return redirect()->to(base_url() . "/Frontdesk_login/login_account");
			} else {
				$this->frontdesk_uid = session()->get('frontdesk_session_uid');
					if (!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
					$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
					return redirect()->to(base_url() . "/Frontdesk_login/login_account");
						}
					}
						$args = [
						'id' => $id,
						'is_del' =>	0
					]; 
					$data = [
							'status'  => $status,
							'updated_at' => date('Y-d-m H:i:s'),
							'updated_by' => $this->frontdesk_uid,
							
						];
				$status = $this->patient_model->update($id, $data);
				if ($status == true) {
					$this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
					// return redirect()->to(base_url().'/Frontdesk/manage_patients');
				return redirect()->to(base_url() . '/Frontdesk/manage_patients');
			}
	} //funtion - Closed

	/* @param: Function for change_patients_status
     * @description: change_patients_status details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function change_dshbrd_patients_status($id, $status){
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		} 
		else{  
			}
		$args = [
			'id'   => $id
		];
		$data = [
			'status'  => $status
		];
		$status = $this->patient_model->update($id,$data);
		if ($status == true) {  
			session()->setTempdata('success','Congratulation ! patients status has updated successfully',2);
			// return redirect()->to(base_url().'/Frontdesk/manage_patients');
			return redirect()->to(base_url().'/Frontdesk');
		} 
	} //funtion - Closed
	

	/* @param: Function for print_slip
     * @description: print_slip details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */

	public function print_slip($id) {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."Frontdesk_login/login_account");
		}   
		else{  
			$frontdesk_uid = session()->get('frontdesk_session_uid');
			$data['loggedin_usr'] = $this->commonForAllModel->getFrontdeskUserData('blood_bank_users', $frontdesk_uid);
			if(!isset($data['loggedin_usr']->email) || $data['loggedin_usr']->email == false) {  
				return redirect()->to(base_url()."Frontdesk_login/login_account");
			} 
		$args = [
			'id'  => $id
		];
		// $data['patient_slip'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		$data['patient_slip'] = $this->commonForAllModel->fetch_rec_by_args('patients', $args);
		return view('Frontdesk/print_slip', $data);
		}  
	}//function -Closed

    /* @param: Function for change_today_appointments_status
    * @description: change_today_appointments_status  
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 21st July, 2023
    */
    public function change_today_appointments_status($id, $status, $pid, $puid, $serial) {
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->patient_id = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 

		if (!(session()->has('frontdesk_session_uid'))) { 
		return redirect()->to(base_url()."Frontdesk_login/login_account");
		}  
		
		$this->frontdesk_uid = session()->get('frontdesk_session_id');
		
		// if(!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
		// 	$this->session->setTempdata('error', 'Doctor UID is missing !', 3);
		// 	return redirect()->to(base_url()."/Frontdesk/doctor_login");
		// } //else { $this->doctor_uid =  $this->doctor_uid_arr['uid']; }

				$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

				$this->data =   [
								'status'  => $status,
								'updated_at' => date('Y-m-d H:i:s'),
								'updated_by' => $this->frontdesk_uid,
								];
		
		if($this->status === 4) { //Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if(isset($this->puid) && $this->puid != '' && $this->puid != 0) {  
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->frontdesk_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if($this->updt_rslt === true) { 
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				}
				else { 
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if($this->patient_id > 0) {//else if-START  /*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->args['id']  = $this->patient_id;
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->frontdesk_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if($this->updt_rslt === true) { 
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				}
				else { 
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				}
			}//else if-CLOSED
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if($this->apmt_id != '' && $this->apmt_id > 0) { //else if- START /***** Get Patient detail thru `Appointment ID` - START *****/
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->frontdesk_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
				if($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				} 
				else { 
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url().'/Frontdesk/today_appointments');
				}
			}//else if- CLOSED
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else  {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url().'/Frontdesk/today_appointments');
			}
		}//if- CLOSED
		else { //Update appointments only 
			$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url().'/Frontdesk/today_appointments');
			}
			else {//else - START
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url().'/Frontdesk/today_appointments');
			}
		} //else loop - closed
    } //Funtion - Closed


	/* @param: Function for filter_all_appointments
     * @description: filter_all_appointments  from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 10th July, 2023
     */

	public function filter_all_appointment($filter) {
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		
    	if ($filter == 'new_patient') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		} 
		else if ($filter == 'old_patient') { //else if-START
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}//else if-CLOSED
		else{  
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		
		$args = [
			//'doctor_id'  => $doctor_id,
			//'status'        => 1, //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
		];
		
		$data = [
			// 'today_apmt' => $this->commonForAllModel->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
			'today_apmt' => $this->commonForAllModel->filter_rec_by_args_with_status('booked_doctor_appointment', $order, $args)
		];
		if($data['today_apmt'] == false) { 
			$this->session->setTempdata('error', 'No record found!', 3);
			return redirect()->to(base_url()."Frontdesk/all_appointments");
		}
		//return view('Frontdesk/today_appointments', $data);
		return view('Frontdesk/all_appointments', $data);
    }//Function-Closed

	/* @param: Function for filter_doctor
     * @description: filter_all_appointments  from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 10th July, 2023
     */	
	public function filter_doctor($filter){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		if ($filter == 'new_doctor') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		else if ($filter == 'old_doctor') {//else if-START
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}//else if- CLOSED
		else{
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		// $data['doctors'] = $this->commonForAllModel->filter_rec_by_args('doctor', $order);
		$data['doctors'] = $this->commonForAllModel->filter_rec_by_args('doctor', $order);
		return view('Frontdesk/all_appointments', $data);
	} //Function-Closed

	/* @param: Function for filter_all_appointments
     * @description: filter_all_appointments  from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 10th July, 2023
     */
	public function manage_doctor(){
		if (!(session()->has('frontdesk_session_uid'))) {
			return redirect()->to(base_url()."/Frontdesk_login/login_account");
		}
		else{
			// $data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
			$data['doctors'] = $this->commonForAllModel->fetch_all_records('doctor');
			if(!isset($data['doctors']) || $data['doctors'] == '') { 
				$this->session->setTempdata('error', 'Sorry ! No doctor found. Please add doctor first', 3);
				return redirect()->to(base_url()."Frontdesk/doctor");
			}
			return view('Frontdesk/manage_doctor', $data);
		}
	}//Function-Closed

	/* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	public function filter_appointment($filter){ 
		if (!(session()->has('frontdesk_session_uid'))) {
	    return redirect()->to(base_url()."Frontdesk_login/login_account");
		}
		if ($filter == 'new_appointment') {
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		else if ($filter == 'old_appointment') {//else if- START
			$order = [
				'column_name'  => 'id',
				'order'        => 'asc'
			];
		}//else if- CLOSED
		else{
			$order = [
				'column_name'  => 'id',
				'order'        => 'desc'
			];
		}
		$args = [
			'is_del'=>0
		];
		//$data['all_appointments'] = $this->AutoModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args);
		$data = [
			//'all_appointments'  => $this->AutoModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
			'all_appointments'  => $this->commonForAllModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
			'pager'   => $this->commonForAllModel->pager
		];
		return view('Frontdesk/all_appointments', $data);
	}//Function-Closed

	
	
    /* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	//public function change_all_appointments_status($id, $status, $pid, $puid, $serial){
	public function change_all_appointments_status($id, $status, $pid, $puid, $serial, $dr_id){
		$this->apmt_id = (int) $id; //appointment ID
		$this->status = (int) $status; //Status
		$this->pid = (int) $pid; //Patient ID
		$this->puid = $puid; //Hospital assigned patient unique ID
		$this->appt_serial = (int) $serial; //Appointment serial 
		$this->dr_id = $dr_id; //Doctor ID
		
		if (!(session()->has('frontdesk_session_uid'))) { 
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->frontdesk_uid = session()->get('frontdesk_session_uid');

		if (!isset($this->frontdesk_uid) || $this->frontdesk_uid == '') {
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		} //else { $this->doctor_uid =  $this->doctor_uid_arr['uid']; }

		$this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

		$this->data = [
			'status' => $status,
			'updated_at' => date('Y-m-d H:i:s'),
			'updated_by' => $this->frontdesk_uid,
		];

		if ($this->status === 4) { //Add Dr. Fee/Attend Patient: Update appointments & insert patient info 
			/***** Get Patient detail thru `puid` - START *****/
			if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
				//$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				}
			}
			/***** Get Patient detail thru `puid` - END *****/
			else if ($this->pid > 0) { //pid: patient_login tbl `id`
				/*****  Get Patient detail thru `paitient ID` - START *****/
				//$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				}
			}
			/***** Get Patient detail thru `paitient ID` - END *****/
			else if ($this->apmt_id != '' && $this->apmt_id > 0) {
				/***** Get Patient detail thru `Appointment ID` - START *****/
				//$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
				$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->frontdesk_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
				if ($this->updt_rslt === true) {
					$this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				} else {
					$this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
				return redirect()->to(base_url() . '/Frontdesk/all_appointments');
			}
		} 
		else { //Update appointments only
			$status = $this->commonForAllModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			//$status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
			if ($status === true) {
				$this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
				return redirect()->to(base_url() . '/Frontdesk/all_appointments');
			} else {
				$this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
				return redirect()->to(base_url() . '/Frontdesk/all_appointments');
			}
		} //else loop - closed
	} //Funtion - Closed

	//*******************Attend Patient - START ***********************
    /* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	// function patient_details_with_puid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial) { 
	// 	$this->updt_data_arr = $data;
	// 	$this->args = $args;
	// 	$this->frontdesk_uid = $frontdesk_uid;
	// 	$this->patient_id = $pid;
	// 	$this->puid = $puid;
	// 	$this->apmt_id = $apmt; //Appointment ID
	// 	$this->new_serial = $serial;

	// 	$this->args_neo = ['puid'  => $this->puid];
	// 	// $this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 	$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 	if($this->patient_rslt != false) { 
	// 		$this->insrt_data_arr = array (
	// 			'puid'				=> $this->puid,
	// 			'serial'			=> $this->new_serial, //$this->patient_rslt['0']->serial,
	// 			'revisit_date'		=> date('Y-m-d'), 
	// 			'apmt_id'			=> $this->apmt_id,
	// 			'pid'				=> (int)$this->patient_rslt['0']->pid,
	// 			'patient_name'		=> $this->patient_rslt['0']->patient_name,
	// 			'patient_id'		=> $this->patient_rslt['0']->id,
	// 			'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
	// 			'patient_address'	=> $this->patient_rslt['0']->patient_address,
	// 			'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
	// 			'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
	// 			'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
	// 			'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
	// 			'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
	// 			'other_fee'			=> $this->patient_rslt['0']->other_fee,
	// 			'patient_room'		=> $this->patient_rslt['0']->patient_room,
	// 			'patient_email'		=> $this->patient_rslt['0']->patient_email,
	// 			/*'next_action'		=> $this->patient_rslt['0']->id,
	// 			'assigned_by'		=> $this->patient_rslt['0']->id,
	// 			'assigned_to'		=> $this->patient_rslt['0']->id, */
	// 			'status'			=> 'Attended', 
	// 			'created_at'		=> date('Y-m-d H:i:s'),
	// 			'created_by'		=> $this->frontdesk_uid, //need uid
	// 			//'patient_image'		=> $this->patient_rslt['0']->id
	// 		);
			
	// 		return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);

	// 	}  /*****  Get Patient detail thru `paitient ID` - START *****/
	// 	else if($this->patient_id > 0) { //else if-START
	// 		$this->args_neo = [ 'id'  => $this->patient_id ];
	// 		// $this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 		if($this->patient_rslt != false) {

	// 			$this->insrt_data_arr = array (
	// 					'puid'				=> $this->patient_rslt['0']->puid,
	// 					'patient_name'		=> $this->patient_rslt['0']->patient_name,
	// 					'patient_id'		=> $this->patient_rslt['0']->id,
	// 					'pid'				=> (int)$this->patient_rslt['0']->pid,
	// 					'serial'			=> $this->new_serial,
	// 					'revisit_date'		=> date('Y-m-d'), //Current date
	// 					'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
	// 					'patient_address'	=> $this->patient_rslt['0']->patient_address,
	// 					'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
	// 					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
	// 					'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
	// 					'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
	// 					'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
	// 					'other_fee'			=> $this->patient_rslt['0']->other_fee,
	// 					'patient_room'		=> $this->patient_rslt['0']->patient_room,
	// 					'patient_email'		=> $this->patient_rslt['0']->patient_email,
	// 					/*'next_action'		=> $this->patient_rslt['0']->id,
	// 					'assigned_by'		=> $this->patient_rslt['0']->id,
	// 					'assigned_to'		=> $this->patient_rslt['0']->id, */
	// 					'status'			=> 'Attended', 
	// 					'created_at'		=> date('Y-m-d H:i:s'),
	// 					'created_by'		=> $this->frontdesk_uid, //need uid
	// 					//'patient_image'	=> $this->patient_rslt['0']->id
	// 				);
	// 			return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
	// 		}
	// 		else if($this->apmt_id != ''  && $this->apmt_id > 0) {
	// 			$this->args_neo = ['id' => $this->apmt_id];
	// 			//$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
	// 			$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
	// 			if($this->patient_rslt != false) { //Generate PUID
	// 				//Generate puid
	// 				$this->puid = 0; //Just for addressing notices
	// 				if($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
	// 					$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
	// 					return redirect()->to(base_url().'/Admin/all_appointments');
	// 				}
	// 				else { $this->puid = $this->generate_puid($this->new_serial);}
	// 				$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 
	
	// 				$this->insrt_data_arr = array (
	// 						'puid'				=> $this->puid,
	// 						'serial'			=> $this->new_serial,
	// 						'registration_date'	=> date('Y-m-d'),
	// 						'patient_name'		=> $this->patient_rslt['0']->patient_name,
	// 						'pid'				=> (int)$this->patient_rslt['0']->pid,
	// 						'age'				=> $this->patient_rslt['0']->age,
	// 						'gender'			=> $this->patient_rslt['0']->gender,
	// 						'patient_phone'		=> $this->patient_rslt['0']->patient_mobile,
	// 						'patient_address'	=> $this->patient_rslt['0']->address,
	// 						//'patient_zip'		=> $this->patient_rslt['0']->doctor_id,
	// 						'doctor_id'			=> $this->patient_rslt['0']->doctor_id,
	// 						'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
	// 						'apmt_id'			=> $this->patient_rslt['0']->id,
	// 						//'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
	// 						//'entry_fee'		=> $this->patient_rslt['0']->entry_fee,
	// 						'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
	// 						//'other_fee'		=> $this->patient_rslt['0']->other_fee,
	// 						'patient_room'		=> $this->patient_rslt['0']->department, //room ?
	// 						'patient_email'		=> $this->patient_rslt['0']->patient_email,
	// 						//'uid'				=>
	// 						//'patient_image'		=> $this->patient_rslt['0']->id
	// 						'status'			=> 'Attended', 
	// 						'level'				=>	3, //3: Doctor - MOI for admin level?
	// 						'created_at'		=> date('Y-m-d H:i:s'),
	// 						'created_by'		=> $this->frontdesk_uid, //need uid
	// 						//'deleted_at'		=> 
	// 					);
	// 				// 	echo "2nd last";
	// 				// echo "<pre>";print_r($this->insrt_data_arr);die;
	// 				return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr); 
	// 			}
	// 			else {
	// 				$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
	// 				$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
	// 			}
	// 		}/***** Get Patient detail thru `Appointment ID` - END *****/
	// 		else {
	// 			$this->session->setTempdata('error', 'Anonymous!, patient info is not found', 3);
	// 			return redirect()->to(base_url().'/Frontdesk/all_appointments');
	// 		}
	// 	} /***** Get Patient detail thru `paitient ID` - END *****/
	// } //function - Closed

	 /* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	//function patient_details_with_puid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial){
	function patient_details_with_puid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->frontdesk_uid = $frontdesk_uid;
		$this->patient_id = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = (int) $serial;
		$this->dr_id = $dr_id; //Doctor ID

		$this->args_neo = ['puid'  => $this->puid];
		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
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
				'created_by'		=> $this->frontdesk_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			// echo "frist";
			return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/*****  Get Patient detail thru `paitient ID` - START *****/
		else if ($this->patient_id > 0) { //Case - Take FIRST appointment - by loggedin Patient 
			$this->args_neo = ['pid'  => $this->patient_id]; //Patient loggedin-ID
			$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
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
					'created_by'		=> $this->frontdesk_uid, //need uid
					//'patient_image'	=> $this->patient_rslt['0']->id
				);
				// echo "second";
				return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
			} 
			else if ($this->apmt_id != ''  && $this->apmt_id > 0) { //MOI?? //FIRST time Appointment - Loggedin Patient
				$this->args_neo = ['id' => $this->apmt_id];
				$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
			 	if ($this->patient_rslt != false) { //Generate PUID
					//Generate puid
					$this->puid = 0; //Just for addressing notices
					if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
						$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
						return redirect()->to(base_url() . '/Frontdesk/all_appointments');
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
						'doctor_id'			=> $this->patient_rslt['0']->doctor_id, //$this->dr_id, 
						'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
						'apmt_id'			=> $this->patient_rslt['0']->id,
						'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
						'patient_room'		=> $this->patient_rslt['0']->department, //room ?
						'patient_email'		=> $this->patient_rslt['0']->patient_email,
						'status'			=> 'Attended',
						'level'				=>	3, //3: Doctor - MOI for admin level?
						'created_at'		=> date('Y-m-d H:i:s'),
						'created_by'		=> $this->frontdesk_uid, //need uid
						//'deleted_at'		=> 
					);
					// 	echo "2nd last";
					return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
				} else {
					$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
					$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
				}
			}
			/***** Get Patient detail thru `Appointment ID` - END *****/
			else {
				$this->session->setTempdata('error', 'Anonymous!, patient info is not found', 3);
				return redirect()->to(base_url() . '/Frontdesk/all_appointments');
			}
		}
		/***** Get Patient detail thru `paitient ID` - END *****/
		else {
			$this->session->setTempdata('error', 'Puid! must exist patients table', 3);
			return redirect()->to(base_url() . '/Frontdesk/all_appointments');
		}
	} //function - Closed


    /* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	// function patient_details_with_pid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial) { 
    //         $this->updt_data_arr = $data;
    //         $this->args = $args;
    //         $this->frontdesk_uid = $frontdesk_uid;
    //         // $this->patient_id = $pid;
	// 		$this->pid = $pid;
    //         $this->puid = $puid;
    //         $this->apmt_id = $apmt; //Appointment ID
    //         $this->new_serial = $serial;

    //         $this->args_neo= ['id' => $this->patient_id];

    //         // $this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
	// 	if($this->patient_rslt != false) {
	// 		if(!isset($this->patient_rslt['0']->puid) || $this->patient_rslt['0']->puid == '') {				
	// 			//Generate puid
	// 			$this->puid = 0;
	// 			if($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
	// 				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
	// 				return redirect()->to(base_url().'/Frontdesk/all_appointments');
	// 			}
	// 			else {  
    //                 $this->puid =  $this->generate_puid($this->new_serial); 
    //              }
	// 		}
	// 		else {//esle-START
    //              $this->puid = $this->patient_rslt['0']->puid;
    //              }
	// 		$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

	// 		$this->insrt_data_arr = array (
	// 				'puid'				=> $this->puid,
	// 				'patient_name'		=> $this->patient_rslt['0']->patient_name,
	// 				'patient_id'		=> $this->patient_rslt['0']->id,
	// 				'serial'			=> $this->new_serial,
	// 				'revisit_date'		=> date('Y-m-d'), 
	// 				'apmt_id'			=> $this->apmt_id,
	// 				'pid'				=> (int)$this->patient_rslt['0']->pid,
	// 				'patient_phone'		=> $this->patient_rslt['0']->patient_phone,
	// 				'patient_address'	=> $this->patient_rslt['0']->patient_address,
	// 				'pin_zip_code'		=> $this->patient_rslt['0']->patient_zip,
	// 				'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
	// 				'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
	// 				'entry_fee'			=> $this->patient_rslt['0']->entry_fee,
	// 				'disease_symptoms'	=> $this->patient_rslt['0']->patient_issue,
	// 				'other_fee'			=> $this->patient_rslt['0']->other_fee,
	// 				'patient_room'		=> $this->patient_rslt['0']->patient_room,
	// 				'patient_email'		=> $this->patient_rslt['0']->patient_email,
	// 				/*'next_action'		=> $this->patient_rslt['0']->id,
	// 				'assigned_by'		=> $this->patient_rslt['0']->id,
	// 				'assigned_to'		=> $this->patient_rslt['0']->id, */
	// 				'status'			=> 'Attended', 
	// 				'created_at'		=> date('Y-m-d H:i:s'),
	// 				'created_by'		=> $this->frontdesk_uid, //need uid
	// 				//'patient_image'		=> $this->patient_rslt['0']->id
	// 			);
			
	// 		return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);

	// 	}
    //      /***** Get Patient detail thru `Appointment ID` - START *****/
	// 	else if($this->apmt_id != ''  && $this->apmt_id > 0) { //else if-START

	// 		$this->args_neo = ['id' => $this->apmt_id];
	// 		// $this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
	// 		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
	// 		if($this->patient_rslt != false) { //Generate PUID 
	// 			//Generate puid
	// 			$this->puid = 0; //Just for addressing notices
	// 			if($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
	// 				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
	// 				return redirect()->to(base_url().'/Frontdesk/all_appointments');
	// 			}
	// 			else {
    //                  $this->puid = $this->generate_puid($this->new_serial);
    //                 }
	// 			$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

	// 			$this->insrt_data_arr = array (
	// 					'puid'				=> $this->puid,
	// 					'serial'			=> $this->new_serial,
	// 					'registration_date'	=> date('Y-m-d'),
	// 					'patient_name'		=> $this->patient_rslt['0']->patient_name,
	// 					'pid'				=> (int)$this->patient_rslt['0']->pid,
	// 					'age'				=> $this->patient_rslt['0']->age,
	// 					'gender'			=> $this->patient_rslt['0']->gender,
	// 					'patient_phone'		=> $this->patient_rslt['0']->patient_mobile,
	// 					'patient_address'	=> $this->patient_rslt['0']->address,
	// 					//'patient_zip'		=> $this->patient_rslt['0']->doctor_id,
	// 					'doctor_id'			=> $this->patient_rslt['0']->doctor_id,
	// 					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
	// 					'apmt_id'			=> $this->patient_rslt['0']->id,
	// 					//'doctor_fee'		=> $this->patient_rslt['0']->doctor_fee,
	// 					//'entry_fee'		=> $this->patient_rslt['0']->entry_fee,
	// 					'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
	// 					//'other_fee'		=> $this->patient_rslt['0']->other_fee,
	// 					'patient_room'		=> $this->patient_rslt['0']->department, //room ?
	// 					'patient_email'		=> $this->patient_rslt['0']->patient_email,
	// 					//'uid'				=>
	// 					//'patient_image'		=> $this->patient_rslt['0']->id
	// 					'status'			=> 'Attended', 
	// 					'level'				=>	3, //3: Doctor - MOI for admin level?
	// 					'created_at'		=> date('Y-m-d H:i:s'),
	// 					'created_by'		=> $this->frontdesk_uid, //need uid
	// 					//'deleted_at'		=> 
	// 				);
	// 			//echo "<pre>";print_r($this->insrt_data_arr);	
	// 			return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr); 
	// 		}
	// 		else {
	// 			$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
	// 			$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
	// 		}
	// 	}
    //     /***** Get Patient detail thru `Appointment ID` - END *****/
	// 	else { 
	// 		$this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
	// 		$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
	// 	}
	// } //Function - Closed

	//function patient_details_with_pid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial){
	function patient_details_with_pid($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->frontdesk_uid = $frontdesk_uid;
		$this->pid = $pid;
		$this->puid = $puid;
		$this->apmt_id = $apmt; //Appointment ID
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->args_neo = ['pid' => $this->pid];

		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('patients', $this->args_neo);
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
				'created_by'		=> $this->frontdesk_uid, //need uid
				//'patient_image'		=> $this->patient_rslt['0']->id
			);
			return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
		}
		/* Get Patient detail thru `Appointment ID` - START 
		 * @desc: Loggedin - NEW Patient appointment Addeded by Admin & Admin
		 */ 
		else if ($this->apmt_id != ''  && $this->apmt_id > 0) {
			$this->args_neo = ['id' => $this->apmt_id];
			$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
			if ($this->patient_rslt != false) { //Generate PUID
				//Generate puid
				$this->puid = 0; //Just for addressing notices
				if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
					$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
					return redirect()->to(base_url() . '/Frontdesk/all_appointments');
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
					
					'doctor_id'			=> $this->patient_rslt['0']->doctor_id,
					'doctor_name'		=> $this->patient_rslt['0']->doctor_name,
					'doctor_id'			=> $this->dr_id,
					'apmt_id'			=> $this->patient_rslt['0']->id,
					'patient_issue'		=> $this->patient_rslt['0']->disease_symptoms,
					'patient_room'		=> $this->patient_rslt['0']->department, //room ?
					'patient_email'		=> $this->patient_rslt['0']->patient_email,
					'status'			=> 'Attended',
					'level'				=>	3, //3: Doctor - MOI for admin level?
					'created_at'		=> date('Y-m-d H:i:s'),
					'created_by'		=> $this->frontdesk_uid, //need uid
					//'deleted_at'		=> 
				);
				return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
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

	/* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	//function patient_details_with_apmt_id($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial){
	function patient_details_with_apmt_id($data, $args, $frontdesk_uid, $pid, $puid, $apmt, $serial, $dr_id){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$this->updt_data_arr = $data;
		$this->args = $args;
		$this->frontdesk_uid = $frontdesk_uid;
		$this->puid = $puid;
		$this->apmt_id = $apmt;
		$this->new_serial = $serial;
		$this->dr_id = $dr_id;

		$this->patient_rslt = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $this->args);
		if ($this->patient_rslt != false) {
			//Generate puid
			$this->puid = 0; //Just for addressing notices
			if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
				$this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
				return redirect()->to(base_url() . '/Frontdesk/all_appointments');
			} 
			else { $this->puid = $this->generate_puid($this->new_serial); }
			$this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 
			//$this->updt_data_arr['patients_id'] = $this->pid; //For updating patients_id in appoitment list 
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
				'created_by'		=> $this->frontdesk_uid,
				//'deleted_at'		=> 
			);
			//Non- loggedin Patient appointment Addeded by Admin & Admin
			return $this->updt_rslt = $this->commonForAllModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
		} else {
			$this->updt_rslt = 2; //Anonymus user, Patient record is not found in db
			$this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
		}
	} //function - Closed

    /* @param: 
     * @description: 
     * @author: 
     * @copyrights: 
     * @date:
     */
	function err_chk_update_appintment_inst_patient_info($err_type) {
		    $this->type_of_err = $err_type;
		
		switch ($this->type_of_err) { //SWITCH STATEMENT-START
			case 'false':
				$this->session->setTempdata('error', 'False response, failed to update appointment', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;
			case '':
				$this->session->setTempdata('error', 'Blank response, failed to udpate appointment', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;
			case 'true':
				$this->session->setTempdata('success', 'Congratulation ! Appointment Status Change Successfully !', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;

			case 2:
				$this->session->setTempdata('error', 'Anonymus user, Patient record is not found in db', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;

			case 3:
				$this->session->setTempdata('error', 'Revisited Patients info insertion failed', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;

			case 4:
				$this->session->setTempdata('error', 'Failed to book doctor appointment', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;

			default:			 
				$this->session->setTempdata('error', 'Sorry ! Unable to Change Try Again ?', 3);
				return redirect()->to(base_url().'Frontdesk/all_appointments');
			break;
		} //SWITCH STATEMENT END
	} //function - Closed

	//*******************Attend Patient - END ***********************


	/* @param: Function for update_patients
     * @description: update_patients details from the list of the patients after edit.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
	public function update_patients($id ){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		$args = [ 'id'  => $id ];

		$data = [
			'patient_name'           =>  $this->request->getVar('patient_name',FILTER_SANITIZE_STRING),
			'patient_phone'          =>  $this->request->getVar('patient_phone',FILTER_SANITIZE_STRING),
			'patient_address'        =>  $this->request->getVar('patient_address',FILTER_SANITIZE_STRING),
			'patient_zip'            =>  $this->request->getVar('patient_zip',FILTER_SANITIZE_STRING),
			'doctor_name'           =>  $this->request->getVar('doctor_name',FILTER_SANITIZE_STRING),
			'doctor_fee'            =>  $this->request->getVar('doctor_fee',FILTER_SANITIZE_STRING),
			'entry_fee'             =>  $this->request->getVar('entry_fee',FILTER_SANITIZE_STRING),
			'patient_issue'          =>  $this->request->getVar('patient_issue',FILTER_SANITIZE_STRING),
			'other_fee'             =>  $this->request->getVar('other_fee',FILTER_SANITIZE_STRING),
			'patient_room'           =>  $this->request->getVar('patient_room',FILTER_SANITIZE_STRING),
			'patient_email'          =>  $this->request->getVar('patient_email'),
			'status'                => 'Active'
		];
		$status = $this->commonForAllModel->update_rec_by_args('patients',$args, $data);
		if ($status == true) {
			$this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
		
		}
        else{
			$this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
		}
		
		return redirect()->to(base_url().'Frontdesk/manage_patients/');
	
    }//Function-Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function donor_registration(){ // Replaced with patient_registration function
		return view('Frontdesk/Donor/donor_registration');
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
					// 'donor_name'      => 'required|min_length[3]|max_length[20]',
					'donor_name'  => [
						'rules'     => 'required|min_length[3]|max_length[20]',
						'errors'    => [
						'required' => 'Donor name is mandatory.',
						'min_length' => 'Minimum length is 3.',
						'max_length' => 'Maximum length is 20.'
						],
					],
					'blood_group'      => 'required',

					//'contact_number'     => 'required|numeric|exact_length[10]',
					'contact_number'  => [
						'rules'     => 'required|min_length[4]|max_length[10]',
						'errors'    => [
						'required' => 'Contact number is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 10.'
						],
					],

					'donor_photo' => [
						'rules'     => 'uploaded[donor_photo]|max_size[donor_photo,' . ALLOW_MAX_UPLOAD .']|is_image[donor_photo]|mime_in[donor_photo,image/jpeg,image/png,image/svg, image/gif)]|ext_in[donor_photo,png,jpg,jpeg, svg, gif]',
						'errors' => [
							'uploaded'  => 'Donor Image is mandatory.',
							'max_size'  => 'Allowed max upload size ' . ALLOW_MAX_UPLOAD . 'KB only',
							'mime_in'   => 'The uploaded file must be a valid image or svg, gif,.',
							'ext_in'    => 'The file must have a valid extension (png, jpg, jpeg, svg, gif).'
						],
					],
					// 'address'   => 'required|min_length[4]|max_length[50]',
					'address'  => [
						'rules'     => 'required|min_length[4]|max_length[50]',
						'errors'    => [
						'required' => 'Address is mandatory.',
						'min_length' => 'Minimum length is 4.',
						'max_length' => 'Maximum length is 50.'
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
							//'donor_photo' 	 =>  $newName,
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
						return redirect()->to(base_url().'Frontdesk/manage_donor');
						}else{
						echo $image->getErrorString(). " " .$image->getError();
						}
				}else{
						$data['validation'] = $this->validator;
				}
			}
		return view('Frontdesk/Donor/donor_registration', $data);
	} //Function - Closed

	/* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function manage_donor(){
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}else{
			$uniid = session()->get('frontdesk_session_uid');
			$args=['status'=>'Active'];
			
			$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'blood_groups' => $this->commonForAllModel->fetch_rec_by_args_arr('blood_group', $args),
				'pager'     => $this->AutoModel->pager
			];
		}
			return view('Frontdesk/Donor/search_donor', $data);
	} //Function - Closed

   /* @param: 
	* @description: 
	* @Remark: 
	* @author: Neoark's Team
	* @copyrights: Neoark Software Pvt Ltd
	* @date: 
	*/
	public function search_donor_details() {
		if (!(session()->has('frontdesk_session_uid'))) { 
			$this->session->setTempdata('error', 'Frontdesk UID is missing !', 3);
			return redirect()->to(base_url() . "/Frontdesk_login/login_account");
		}
		else {
			$keyword = $this->request->getVar('blood_group', FILTER_SANITIZE_STRING);
			$args = [];
			$srch_field = 'blood_group'; //Searching table field name
			if($keyword) {
				$result = $this->AutoModel->search_records('blood_donor',$srch_field, $keyword, $args);
			}
			$data = [
				'all_donors' => $this->AutoModel->fetch_rec_by_status_with_pagination('blood_donor', $args),
				'blood_groups' => $this->commonForAllModel->fetch_rec_by_args_arr('blood_group', $args),
				'pager'     => $this->AutoModel->pager
			];
			return view('Frontdesk/Donor/search_donor', $data);
		}
	} //Function - Closed

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


} //Class - closed 