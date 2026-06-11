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
use \App\Models\AutoModel;
use \App\Models\Medicine_model;
use \App\Models\Login_model;
use \App\Models\DoctorModel;
use \App\Models\PatientAutoModel;
use \App\Models\Blogs_model;
//use \App\Models\CommonModel; //Custom
use \App\Models\CommonForAllModel; //Custom
//use Config\App; //For using: APP_TIMEZONE from App.php 

class Doctor extends BaseController {
    public $loginModel;
    public $adminModel;
    public $AutoModel;
    public $session;
    public $blogmodel;
    //public $commonModel; //Custom
    public $medicine_model;
    public $patient_model;
    public $commonForAllModel;

    //public $config; //For using: APP_TIMEZONE from App.php 

    public function __construct() {
        helper(['form', 'Patient', 'text']);
        $this->adminModel = new Admin_Model();
        $this->session = \Config\Services::session();
        $this->medicine_model = new Medicine_model();
        //$this->commonModel = new CommonModel(); //Custom
        $this->AutoModel = new AutoModel();
        $this->patient_model = new AutoModel();
        $this->blogmodel = new Blogs_model();
        $this->loginModel = new Login_model();
        $this->doctorModel = new DoctorModel(); //Customized 
        $this->commonForAllModel = new CommonForAllModel(); //Customized 

    }//Function- Closed
    

    public function checkExpiry_time($time){
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $update_time   = strtotime($time);
        $current_time  = time();
        $timeDiff      = ($current_time - $update_time)/60;
        if ($timeDiff < 900) { return true; }
        else { return false; }
    }
    

    /************ Admimission Fee - START ************/
   /* @params: 
    * @desc: Render Admission form for adding admimission fee and other charges
    * @use: Admin
    * @return:
    * @author: Neoarks Team
    * @date: 9th November,2023
    * @modify
    */
    public function admit_patient($patient_id, $pid, $apmt_id, $puid, $serial, $dr_id) {
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            //$dr_args = [ 'id'  => $this->dr_id  ];
            $dr_args = [ 'ref_id'  => $this->dr_id  ]; //@raaj
            $data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $dr_args); 

            $apmt_args = [ 'id'  => $this->apmt_id ]; 
            $data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        return view('Doctor/payments/add_admission_fee', $data); // Pass the data to the view
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
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_session_id = session()->get('doctor_session_id');
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
                    'registration_fee'  => $this->request->getVar('other_charges', FILTER_SANITIZE_STRING),
                    'admission_fee' => $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
                    'payment_note'          => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'          =>  $this->puid,
                    'patients_id'   =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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
        $this->pcnt_args = ['id'    => $this->apmt_id];
        $data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
        $data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

        //$this->dr_args = ['id'    => $this->apmt_id]; //NEED $this->dr_sess_id here
        $this->dr_args = ['ref_id'  => $this->doctor_session_id]; //@raaj
        $data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->dr_args);
        return view('Doctor/add_admission_fee', $data);
    } //function - Closed


    /*@params:
    * @desc: Add fee form load
    * @retuns:
    * @author: Neoarks Team
    * @date: 4th November, 2023
    * @modify:
    */
    public function generate_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            return redirect()->to(base_url() . 'Doctor/admit_patient/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
        }
        if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
            $args = [ 'id'  => $this->patient_id ]; 
            $update_dt_arr = [
                'status'  => 'Admit', 
                'updated_at'  => date('Y-m-d H:i:s'),
                'updated_by'  => $this->doctor_uid,
            ];
            $this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
        }
        $apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
        $data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
        if($this->updt_status === true) { //When attended the loggedin patient's appointment
            if(!isset($data['apmt_patient'][0]->doctor_id)) {
                $this->session->setTempdata('error', 'Doctor id is not found in appointment table', 3);
                return view('Doctor/payments/generate_admission_bill', $data);

            }
            if($data['apmt_patient'][0]->doctor_id == 0 || $data['apmt_patient'][0]->doctor_id == '') {
                $this->session->setTempdata('error', 'Doctor id is blank or zero in appointment table', 3);
                return view('Doctor/payments/generate_admission_bill', $data);
            }
            
            //$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
            $this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
            $this->session->setTempdata('success', 'Bill generated successfull', 3);
            return view('Doctor/payments/generate_admission_bill', $data);
        }
        else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
            //$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
            $this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
            $this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
            return view('Doctor/payments/generate_admission_bill', $data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected result', 3);
            return view('Doctor/payments/generate_admission_bill', $data);
        }
    } //function - Closed


    /****************** Clear Dues, Add Payment, Add Expenses Generate Bill- START ******************/
    /*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
    * @desc: Render Payment form and save payement into `patients_pay_charges` tbl and Generate pdf format bill
    * @retuns: 
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function add_patient_payment_old($id, $pid, $apmtid, $puid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        
        $this->puid = $puid; 
        $this->apmt_id = $apmtid; //Appointment ID
        $args = [ 'id'  => $id ]; //patients tbl id
        if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
            $this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
            return redirect()->to(base_url() . 'Doctor/manage_patients');
        }
        $data = [];
        $data['validation'] = null;
        if ($this->request->getMethod() == 'get') { //Render payment form
            $args = [ 'id'  => $this->apmt_id ];
            $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            return view('Doctor/add_patient_payment', $data);
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
                    'paid_amount'  => $total_patient_paid_amount,
                    'pid'           =>  $pid,
                    'patients_id'   => $id,
                    'puid'          => $this->puid, 
                    'apmt_id'       => $this->apmt_id,
                    //'status'        =>  'Dues Cleared',
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    =>  date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ];
                
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
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
            return redirect()->to(base_url().'Doctor/manage_patients');
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }

        $this->doctor_uid = session()->get('doctor_session_uid');

        $this->puid = $puid; 
        $this->apmt_id = $apmtid; //Appointment ID
        $args = [ 'id'  => $id ]; //patients tbl id
        if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
            $this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
                return redirect()->to(base_url() . 'Doctor/manage_patients');
        }
        $data = []; //for addressing notices
        
        
        $data['validation'] = null;
        $data['payments'] = $this->get_patient_final_payments_neo($id, $pid, $apmtid, $puid);;
        // ($data['payments']); exit;
        $args = [ 'id'  => $this->apmt_id ];
        $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if ($this->request->getMethod() == 'get') { //Render payment form - with paid payment and hospital expence
                return view('Doctor/add_patient_payment', $data);

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
                    'registration_fee'      =>  $this->registration_fee,
                    'admission_fee'         =>  $this->admission_fee,
                    'paid_amount'           =>  $this->receive_payment,
                    //'total_patient_paid_amount'   => $this->total_paid_amount,
                    'pay_mode'              =>  $this->request->getVar('pay_mode', FILTER_SANITIZE_STRING),
                    'transaction_id'        =>  $this->request->getVar('transaction_id', FILTER_SANITIZE_STRING),
                    'pay_date'              =>  $this->request->getVar('payment_date', FILTER_SANITIZE_STRING),
                    'payment_note'          =>  $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'                   =>  $pid,
                    'patients_id'           => $id,
                    'puid'                  => $this->puid, 
                    'apmt_id'               => $this->apmt_id,
                    //'status'              =>  'Dues Cleared',
                    'pay_date'              =>  date('Y-m-d'),
                    'created_at'            =>  date('Y-m-d h:i:s'),
                    'created_by'            => $this->doctor_uid
                ];
                    
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                        return redirect()->to(base_url().'Doctor/generate_receive_payment_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
                } 
                else { //success case
                        return redirect()->to(base_url() . 'Doctor/generate_receive_payment_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
                }
            } 
            else { 
                $this->session->setTempdata('error', 'Mandatory validation failed', 2);
                $data['validation'] = $this->validator; 
                    return view('Doctor/add_patient_payment', $data); 
                }
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method', 2);
            $data['validation'] = $this->validator;
                return redirect()->to(base_url().'Doctor/manage_patients');
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        //echo "<pre>";print_r($this->data);die;
        return $this->data;
    } //function - Closed
    
    /*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
    * @desc: Generate pdf format bill for patient provided payments 
    * @retuns: Internally used function
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function generate_receive_payment_bill($patient_id, $payid, $apmtid, $puid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        
        // $this->admin_uid = session()->get('admin_session_uid');
        $this->admin_uid = session()->get('doctor_session_uid');
        $this->patient_id = $patient_id; //patients tbl id 
        $this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = $apmtid;
        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
        
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
                //  'status'  => 'Discharged', 
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => $this->admin_uid,
                ];
        $this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
        $args = [ 'id'  => $this->apmt_id ];
        //$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate_receved_payment', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate_receved_payment', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        // $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = $patient_id; //patients tbl id 
        $this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = $apmtid;
        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->doctorModel->fetch_rec_by_args('patients_pay_charges', $args);
        
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
                //  'status'  => 'Discharged', 
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => $this->doctor_uid,
                ];
        $this->updt_status = $this->doctorModel->update_rec_by_args('patients', $args, $update_dt_arr);
        $args = [ 'id'  => $this->apmt_id ];
        //$data['get_patient'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
        $data['get_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate_apment_patient_bill', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate_apment_patient_bill', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $data = [
                'id'        => $id,
                'pid'       => $pid,
                'apmt_id'   => $amptid,
                'puid'      => $puid,
            ];
        return view('Doctor/add_hospital_expenses', $data); 
    } //function - Closed

    /*@params: Ajax call
    * @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
    * @retuns:
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function save_hospital_expenses() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
                    'tax_amount'        =>  $this->request->getPost('taxCalculation'),
                    'discount_percentage' =>  $this->request->getPost('discount'),
                    'discount_amount'   =>  $this->request->getPost('discount'),
                    'patients_id'       =>  $this->request->getPost('patients_id'),
                    'pid'               =>  $this->request->getPost('pid'),
                    'apmt_id'           =>  $this->request->getPost('apmt_id'),
                    'puid'              =>  $this->request->getPost('puid'),
                    'created_at'        =>  date('Y-m-d h:i:s'),
                    'created_by'        =>  $this->doctor_uid,
                ];

                $status = $this->doctorModel->Insertdata('treatment_expenses_history', $insdata);
                if ($status === true) {
                    $result_arr = array(
                        'status' => true,
                        'error'  => false, //error: `false` whereever status is true with SQL err 
                        'code'  => 200,
                        'message' => 'Expenses added successfully',
                        'data' => $insdata
                    );
                    return json_encode($result_arr);
                } 
                else {
                    $result_arr = array(
                        'status' => false,
                        'error' => true, //error: `true` whereever status is false with SQL err 
                        'code'  => 200,
                        'message' => 'Failed! to add expenses',
                        'data' => $insdata
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
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code'  => 200,
                    'message' => 'Validation failed',
                    'data' => $insdata
                );
                return json_encode($result_arr);
            }
        } 
        else {
            $result_arr = array(
                'status' => false,
                'error'  => true, //error: `true` whereever status is false with SQL err 
                'code'  => 200,
                'message' => 'Unexpected request method',
                'data' => $array(),
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        return view('Doctor/show_patient_final_payments', $data);
    } //function - Closed


    /*@params: $id (patients tbl id), $pid (patient_login tbl id), 
    * $apmtid (appointment ID), $puid
    * @desc: Clear patient final dues/payments into `patients_pay_charges` 
    * tbl and Generate 
    * pdf format bill
    * @retuns: Also call function `generate_clear_dues_bill(), for 
    * generating bill in pdf format
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    
    //public function clear_final_dues($patient_id, $pid, $apmtid, $puid) { //pid ie patient_login table id
    public function clear_final_dues($patient_id, $pid, $apmtid, $puid, $status) { //pid ie patient_login table id
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');

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
                    return redirect()->to(base_url() . 'Doctor/show_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
                }           
                $this->user_data_arr = [
                    'total_hospital_expenses'   => $this->request->getVar('total_hospital_expenses', FILTER_SANITIZE_STRING),
                    'total_patient_paid_amount' => $this->request->getVar('total_paid_amount', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING),
                    'payment_note'  => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'           =>  $this->puid,
                    'patients_id'    =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
                }
            } 
            else { $data['validation'] = $this->validator; }
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        $data['patients'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
        return view('Doctor/discharge_appointment_pat', $data);
    } //function - Closed


    /*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
    * @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
    * @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    //public function generate_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid) { //Ref generate_patient_bill()
    public function generate_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid, $status) { //Ref generate_patient_bill()
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = (int) $patient_id; //patients tbl id 
        $this->pid = (int)$pid;
        $this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = (int) $apmtid; //Appointment ID
        $this->status = $status; //status

        if($this->status == 'Admit') { $this->status = 1; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
        else if($this->status != 'Admit') { $this->status = 2; } //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient

        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->commonForAllModel->fetch_rec_by_args('patients_pay_charges', $args);
        if($data['payment_bill'] === false) {
            $this->session->setTempdata('error', 'Payments bill is not found ', 3);
            return redirect()->to(base_url() . 'Doctor/show_patient_final_payments/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
        }
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
            'status'  => $this->status, 
            'created_at'  => date('Y-m-d H:i:s'),
            'created_by'  => $this->doctor_uid,
        ];
        $this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
        
        $args = [ 'id'  => $this->apmt_id ];
        $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate_clear_dues_bill', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate_clear_dues_bill', $data);
        }
    } //function - Closed
    /****************** Clear Dues, Add Payment, Add Expenses Generate Bill- END ******************/

    /* @params: get_highest_today_serial
     * @desc: 
     * @use: 
     * @author: Neoarks Team
     * @date: 10th July, 2023
     * @modify:
     */
    public function get_highest_today_serial($tbl, $fld){
        $this->tbl_name = $tbl;
        $this->where_field = $fld;
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [$this->where_field => date('Y-m-d')];
        $this->highest_pat_serial = $this->doctorModel->today_highest_serial($this->tbl_name, $args);
        return $this->highest_pat_serial;
    }//Function- Closed


    /* @params: get_highest_today_serial
     * @desc: 
     * @use: 
     * @author: Neoarks Team
     * @date: 10th July, 2023
     * @modify:
     */
    public function generate_puid($new_sn){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->new_serial = $new_sn; //Serial Number
        $this->current_date = date('Y-m-d');
        $this->cur_date_num_form = str_replace(array('-', '/'), '', $this->current_date); //Current date numer format: By removing hyphens and slashes from current date
        $this->puid = $this->cur_date_num_form . SLOGAN . $this->new_serial; //Current date (number format) + SLOGAN + new_serial
        return $this->puid;
    }//Function- Closed


    /* @params: get_highest_today_serial
     * @desc: 
     * @use: 
     * @author: Neoarks Team
     * @date: 10th July, 2023
     * @modify:
     */
    public function index() { //returns array format
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->dr_sess_id = session()->get('doctor_session_id');
        //$data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
        $data['loggedin_usr'] = $this->doctorModel->get_user_data_in_arr($this->doctor_uid, 'register_all_users');
        
        if (!isset($data['loggedin_usr']['email']) || $data['loggedin_usr']['email'] == false) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = ['doctor_email' => $data['loggedin_usr']['email']];
        $check_account = $this->doctorModel->fetch_rec_by_args('doctor', $args);
        
        if ($check_account) { //Store Doctor Account in session
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Doctor ID is missing !', 3);
                return redirect()->to(base_url() . "/Doctor_login/doctor_login");
            }

            //Dashboard Section Query
            //Today Patient Under You - START
            $args = [ 'doctor_id' => $this->dr_sess_id, ];
            $data['p_under_u'] = $this->patient_model->where($args)->findAll();

            //Today Patient Under You - END
            //Discharge Patient Under You - START
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Discharged'
            ];
            $data['t_d_patient'] = $this->patient_model->where($args)->findAll();

            //Discharged Patients under Dr.
            //Discharge Patient Under You - END
            //Doctor Appointment Section Script
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'created_at' => date('Y-m-d h:i:s')
            ];

            //  $data['appointment'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            $data['appointment'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);

            //Doctor Appointment Section Script 
            $args = [ 'doctor_id' => $this->dr_sess_id ];
            // $all_appointment = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            $all_appointment = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Active'
            ];
            // $all_patients = $this->doctorModel->fetch_rec_by_args('patients', $args);
            $all_patients = $this->doctorModel->fetch_rec_by_args('patients', $args);
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Discharged',
                'is_del' => 0
            ];
            // $all_discharge_pat = $this->doctorModel->fetch_rec_by_args('patients', $args);
            $all_discharge_pat = $this->doctorModel->fetch_rec_by_args('patients', $args);
            //Chart Sction Query Start
            $data['chart_data'] = [
                'tot_appointments' => $all_appointment ? count($all_appointment) : '0',
                'total_patients' => $all_patients ? count($all_patients) : '0',
                'all_discharge_pat' => $all_discharge_pat ? count($all_discharge_pat) : '0'
            ];
            //Chart Sction Query End
            //Dashboard Section Query
            return view('Doctor/dashboard', $data);
            //Checking Activation Account is Activate to Access Control or Not      
        } 
        else {
            $this->session->setTempdata('error', 'Email confirmed though awaited for admin approval', 3);
            return view('Doctor/doctor_req_active', $data); 
        }
    } //function - Closed

   /******************** Prescritpion Section - START  ********************/
   /* @param: 
    * @desc: Render Prescription Form: Step - Zero
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
    function add_prescription($id) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        else {
            $data = [
                'res'  => $this->adminModel->getPatientRecordWithAppointment($id),
                'pager'   => $this->adminModel->pager
            ];
            $args = [ 'patient_id'  =>      $id ]; //Patient ID
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
            return view('Doctor/add_patient_prescription', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->return_dt_arr = []; //Just for addressing notices
            $this->doctor_uid = session()->get('doctor_session_uid'); //Loggedin User uid
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    "message" => 'Invalid request method'
                ); 
                return json_encode($this->result_arr);
            }
            $rules = [ 
                'prescription'  => 'required',
                'doctor_name'   => 'required',
                'patient_id'    => 'required',
                'patient_name'  => 'required',
                'patient_puid'  => 'required',
                ];  
            if ($this->validate($rules)) {
                $prescription      = $this->request->getPost('prescription');
                $patient_name      = $this->request->getPost('patient_name');
                $patient_id        = $this->request->getPost('patient_id');
                $doctor_id         = $this->request->getPost('doctor_id');
                $patient_age       = $this->request->getPost('patient_age');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_gender    = $this->request->getPost('patient_gender');
                $patient_puid      = $this->request->getPost('patient_puid');
                $doctor_name       = $this->request->getPost('doctor_name');
                $education         = $this->request->getPost('education');
                $dr_specialization  = $this->request->getPost('dr_specialization');
                $doc_gender        = $this->request->getPost('doc_gender');
                $apmt_id           = $this->request->getPost('apmt_id');
                $pid               = $this->request->getPost('pid');
                $ref_by            = $this->request->getPost('ref_by');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_email     = $this->request->getPost('patient_email');
                $disease_symptoms  = $this->request->getPost('disease_symptoms');
                $patient_type      = $this->request->getPost('patient_type');
                $status            = $this->request->getPost('status');
                
                if($status != "Admit") { $status = 'Prescribed'; }
                $this->prescription_data_arr = [
                    'prescription'        =>  $prescription,//
                    'prescription_detail' =>  $prescription, //
                    'patient_name'        =>  $patient_name,
                    'patient_id'          =>  $patient_id,
                    'doctor_id'           =>  $doctor_id,
                    'status'              =>  $status, 
                    'age'                 =>  $patient_age,
                    'gender'              =>  $patient_gender,
                    'puid'                =>  $patient_puid,
                    'doctor_name'         =>  $doctor_name,
                    'apmt_id'             =>  $apmt_id,
                    'pid'                 =>  $pid,
                    'ref_by'              =>  $ref_by,
                    'patient_mobile'      =>  $patient_mobile,
                    'patient_email'       =>  $patient_email,
                    'disease_symptoms'    =>  $disease_symptoms,
                    'patient_type'        =>  $patient_type,
                    'prescription_date'   =>  date('Y-m-d H:i:s'),
                    'created_at'          =>  date('Y-m-d H:i:s'),
                    'created_by'          =>  $this->doctor_uid, //Harcoded for now
                ];
                //echo "<pre>";print_r($this->prescription_data_arr);die;
                //$status = $this->adminModel->Insertdata('prescription_history', $this->prescription_data_arr);
                //$last_insrt_id = $this->commonForAllModel->Insertdata_return_id('prescription_history', $this->prescription_data_arr);

                $this->pat_args = ['id'     => $patient_id];
                $this->updt_patient_arr = [
                    'status'        => $status,
                    'updated_by'    => $this->doctor_uid,
                    'updated_at'    => date('Y-m-d H:i:s')

                ];
                $this->fld = '';
                $this->fldval = '';
                $last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'patients', $this->pat_args, $this->updt_patient_arr);
                
                if ((int) $last_insrt_id > 0 ) {
                    $this->return_dt_arr = ['prescription_id' => $last_insrt_id];
                    //$this->data['prescription'] = array(array());
                     $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'message' => 'Prescription added successfully',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr); 
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
                        'code' => 200,
                        'message' => 'Failed to add prescripton. Please try again123.',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr);
                }
            }
            else{
                $this->result_arr = array(
                    'status' => false,
                    'error'     => false, //error: `false` showing validation error autoamtically
                    'code' => 200,
                    'message' => 'Validation failed. Please try again',
                    'data'      => $this->return_dt_arr,
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session('doctor_session_uid');
        $selected_report=$this->request->getPost('selected_report');
        $selected_report_arr=explode(',',$selected_report);
        
        if($this->request->getMethod() == 'post') {
            if(count($selected_report_arr)>0) {
                foreach($selected_report_arr as $val) {
                    $this->report_data_arr = [
                        'patient_id'    =>  $pid,
                        'report_name'    =>  $val,
                        'report_brief'   =>  $val, //
                        'report_detail'  =>  $val,
                        'report_attachment' =>  '',//$doc_img,
                        'created_at'      =>  date('Y-m-d H:i:s'),
                        'created_by'      =>  $this->doctor_uid, //Hardcoded for now
                    ];
                    $status = $this->doctorModel->Insertdata('patient_reports', $this->report_data_arr);
                }
            }
            if ($status === true) {
                $this->result_arr = array(
                    'status' => true,
                    'error'     => false, //error: `false` with status `true`
                    'code' => 200,
                    'data' > '',
                    'message' => 'Reports saved successfully'
                );
                return json_encode($this->result_arr);
            } 
            else {
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'error'     => true, //error: `true` whereever status is false with SQL err 
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
    
    public function upload_prescription_report($pid, $rid,$file_id, $prescription_id) { //$pid: patient_id, $rid : Report ID
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid'); //Loggedin User uid
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
                $rpt->move(FCPATH . 'uploads/patienHome>> patient login >>t_reports', $this->rand_name);
                $doc_img = $rpt->getName();
                
                $this->report_data_arr = [
                    //'report_name'     =>  $doc_img,
                    'pid'               => $pid,
                    'report_attachment' =>  $doc_img,
                    'prescription_id'   =>  $prescription_id,
                    //'ref_id'            =>  $ref_id,
                    'updated_at'      =>  date('Y-m-d H:i:s'),
                    'updated_by'      =>  $this->doctor_uid,
                ];
                $args = ['id'   => $rid];
                //Need update model function in place of Insertdata - 
                $status = $this->adminModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
                if ($status === true) {
                    $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'data' > '',
                        'message' => 'Reports uploaded successfully'
                    );
                    return json_encode($this->result_arr);
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                // return redirect()->to(base_url() . '/Doctor/add_prescription');
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'error'     => true, //error: `true` whereever status is false with SQL err 
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->doctor_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'advice'        =>  $advice,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                    'status'        => 'Prescribed',
                    'updated_by'    =>  $this->doctor_uid, //Harcoded for now
                ];
                //echo"<pre>";print_r($this->advice_data_arr);die;
                $args = [ 'patient_id'   => $patient_id ];
                $fld_name = 'id';
                $this->maxid = $this->adminModel->get_max_val('prescription_history', $fld_name);
                if($this->maxid === false || (!isset($this->maxid['0']->id))) {
                    $this->data['advice'] = array(array());
                     $this->result_arr = array(
                        "status" => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => false, //error: `false` with status `true`
                            "code" => '200',
                            "message" => 'Advice added successfully'
                        ); 
                        return json_encode($this->result_arr); 
                    } 
                    else {
                        $this->result_arr = array(
                            "status" => false,
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'Validation failed.'
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        } else {
            $this->doctor_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
    
            if ($this->request_method != 'post') {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'Validation failed.'
                ]);
            }
    
            $msg_frm_doc = $this->request->getPost('msg_frm_doc');
            $patient_id = $this->request->getPost('patient_id');
            $assigned_to = $this->request->getPost('slct_refr_usr');
    
            $message_data = [
                'assigned_by' => $this->doctor_uid,
                'assigned_to' => $assigned_to,
                'status'    => 'Prescribed',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->doctor_uid,
                'recommendation' => $msg_frm_doc,
            ];
    
            $prescription_id = $this->adminModel->get_max_val('prescription_history', 'id');
    
            if ($prescription_id === false || empty($prescription_id[0]->id)) {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'ID is missing. Please talk to admin'
                ]);
            }
    
            $status_history = $this->adminModel->update_rec_by_args('prescription_history', ['id' => $prescription_id[0]->id], $message_data);
    
            $status_patient = $this->adminModel->update_rec_by_args('patients', ['id' => $patient_id], ['status' => 'Prescribed']);
    
            if ($status_history === true && $status_patient === true) {
                return json_encode([
                    "status" => true,
                    'error'     => false, //error: `false` with status `true`
                    "code" => '200',
                    "message" => 'Recommendation added successfully'
                ]);
            } else {
                // Handle database errors
                $error_message = 'Failed to add Recommendation. Check the error logs for details.';
                log_message('error', $error_message);
    
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => $error_message
                ]);
            }
        }
    }  // Function - Closed
    private function mapAssignedUser($selectedUser) {
        // Map 'Front Desk' to the corresponding user ID or handle other cases accordingly
        if ($selectedUser === 'Front Desk') {
            return 1; // Replace 1 with the actual user ID for 'Front Desk'
        } elseif ($selectedUser === 'Other Users') {
            // Handle 'Other Users' case if needed
        }
    
        // Default value or additional handling
        return 0;
    }

    /***************************** Prescritpion Section - END  *****************************/


    /* @param: Add ward as Admin
    * @desc: 
    * @use: Admin
    * @return:
    * @author: Neoarks Team
    * @date: 11th Nov,2023
    * @modify
    */
    public function discharge_summary(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");}
        return view('Doctor/discharge_summary');
    }

    /* @param: Upload ward function  as Doctor
    * @desc: Upload details of ward
    * @use: Admin,Doctor
    * @return:
    * @author: Neoarks Team
    * @date: 11th Nov,2023
    * @modify
    */
    public function upload_summary(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
                $status = $this->doctorModel->Insertdata('prescription_history', $this->user_data_arr);
                if ($status == true) {
                    $this->session->setTempdata('success', 'Congratulation ! Discharge summary added Successfully !', 3);
                } else {
                    $this->session->setTempdata('error', 'Sorry ! Unable to Add  Discharge  Try Again ?', 3);
                }
                return redirect()->to(base_url() . 'Doctor/all_patients');
            } else {
                $data['validation'] = $this->validator;
            }
        }
        return view('Doctor/discharge_summary', $data);
    }


    public function add_summary(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }  
        else {
            $this->prescription = $prescription;
            $data = [];
            $data['validation'] = null;
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    "message" => 'Invalid request method'
                ); 
                return json_encode($result_arr);
            }
                
            $this->prescription_data_arr = [
                'prescription'    =>  $this->prescription,
                'prescription_detail'    =>  $this->prescription,
                'serial'          =>  '',
                'apmt_id'         =>  '',
                'created_at'      =>  date('Y-m-d H:i:s'),
                'created_by'      =>  1, //Harcoded for now
            ];
            
            //return json_encode($result_arr);
            $status = $this->doctorModel->Insertdata('prescription_history', $this->prescription_data_arr);
            if ($status === true) {
                //$this->session->setTempdata('success', 'Congratulation ! Doctor Added Successfully !', 3);
                $this->data['prescription'] = array(array());
                $result_arr = array(
                    "status" => true,
                    'error'  => false, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    "message" => 'Prescripton addes successfully'
                ); 
                return json_encode($result_arr);
            } 
            else {
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    "message" => 'Failed to add prescripton'
                ); 
                return json_encode($result_arr);
            }
        } //else loop - Closed
        //return view('Doctor/add_patient_prescription', $data);
    } //function - Closed


    public function search_today_patient(){
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
        
            $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
            // Remove spaces from the user input
            ////$keyword = str_replace(' ', '', $keyword);
    
            $args = [
                //'status'  => 'Active' 
                'is_del' => 0,
                'doctor_id' => $this->dr_sess_id,
                'registration_date' => date('Y-m-d')
            ]; 
    
            if ($keyword) {  
                // Search for data with spaces removed
                $result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword,  $args);
            } else {  
                $result = $this->commonForAllModel;
            }   
    
            $data = [
                'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
                'today_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->commonForAllModel->pager
            ];
    
            return view('Doctor/today_patients', $data);
        }
    }
    
    /* @params: Search today appointments
     * @desc: Search appointments from today appointments
     * @use:
     * @author: Neoarks Team
     * @date: 6th July, 2023
     * @modify:
     */
    public function search_today_appointments(){
        $this->dr_sess_id = session()->get('doctor_session_id');
        
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        
        $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
        
        // Remove spaces from the user input
        ////$keyword = str_replace(' ', '', $keyword);
        
        $args = [
            'is_del' => 0,
            'doctor_id' => $this->dr_sess_id,
            'booking_date' => date('Y-m-d')
        ];
        
        if ($keyword) {
            // Search for data with spaces removed
            $result = $this->doctorModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
        } else {
            $result = $this->doctorModel;
        }
        
        $data = [
            'today_appointments' => $this->doctorModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
            'pager' => $this->doctorModel->pager
        ];
        
        return view('Doctor/today_appointments', $data);
    } // Function - Closed

    public function today_appointments(){
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }

        $args = [
            //'status' => 1,
            'is_del' => 0,
            'doctor_id' => $this->dr_sess_id,
            //1: Appointment
            'booking_date' => date('Y-m-d')
        ];
        $data = [
            'today_appointments'  => $this->adminModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
            'pager' => $this->adminModel->pager
        ];
        //echo "<pre>";print_r($data);die;
        return view('Doctor/today_appointments', $data);
    } //Function - Closed
    
    /* @params: patient info
     * @desc: Update old patient info into the `revisit_patients` table
     * @use: When Patient is marked as `Attend` by the Doctor and Admin
     * @author: Neoarks Team
     * @date: 10th July, 2023
     * @modify:
     */
    public function revisit_patient_update($patient_info){
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $patient_details = $patient_info;
        $userdata = [
            'patient_name' => $patient_details['patient_name'],
            'patient_id' => $patient_details['puid'],
            'patient_phone' => $patient_details['patient_mobile'],
            'patient_email' => $patient_details['patient_email'], //??
            'patient_address' => $patient_details['patient_email'], //??
            'pin_zip_code' => $patient_details['patient_email'], //??
            'doctor_name' => $patient_details['puid'],
            'disease_symptoms' => $patient_details['patient_issue'],
            'booking_date' => $patient_details['booking_date'],
            'booking_time' => $patient_details['booking_time'],
            'puid' => $patient_details['puid'],
            'doctor_id' => $patient_details['doctor_id'],
            'doctor_name' => $patient_details['doctor_name'],
            'next_action' => $patient_details['puid'],
            'assigned_by' => $patient_details['puid'],
            'assigned_to' => $patient_details['puid'],
            'status' => 1, //0: Cancelled,  2: Deleted, 3: Awaited, 4: Attended, 5: Absent, 6: Admitted, 7: Other
            'created_at' => date('Y-m-d h:i:s')
        ];
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function all_patients(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
            $this->dr_sess_id = session()->get('doctor_session_id');
            $args = [
            'is_del'=> 0,
            'doctor_id' => $this->dr_sess_id
        ];
        $data = [
            'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
            'all_patients' => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
            'pager' => $this->patient_model->pager
        ];
        return view('Doctor/all_patients', $data);
    } //Function - Closed


    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function today_patients(){
        if (!(session()->has('doctor_session_id'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            
            $this->dr_sess_id = session()->get('doctor_session_id');
            $args = [
                //'status'  => 'Active' 
                'is_del'=> 0,
                'doctor_id' => $this->dr_sess_id,
                'registration_date' => date('Y-m-d')
            ];
            $data = [
                'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
                'today_patients' => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->patient_model->pager
            ];
            return view('Doctor/today_patients', $data);
            // return view("Doctor/manage_patients",$data);
        }
    } //Function - Closed

    /* @param: Function for change_patients_status
     * @description: change_patients_status details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
    public function change_patients_status($id, $status){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
        }
        $args = [
            'id' => $id,
            'is_del'=>0
        ];
        $data = [
            'status' => $status
        ];
        $status = $this->patient_model->update($id, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
            // return redirect()->to(base_url().'/Frontdesk/manage_patients');
            return redirect()->to(base_url() . '/Doctor/all_patients');
        }
    } //funtion - Closed

    /* @param: Function for manage_patients
     * @description: Manage patient details
     * Remark: It provide a list of patients that have added previously.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 20th June, 2023
     */
    public function manage_patients(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $args = [
                //'status'  => 'Active' 
            ];
            $data = [
                'patients' => $this->patient_model->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->patient_model->pager
            ];
            return view("Doctor/manage_patients", $data);
            // return view("Doctor/manage_patients",$data);
        }
    } //Function - Closed

    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    public function manage_revisit_patient() {
    //  //$this->dr_sess_id = session()->get('doctor_session_id');
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } else {
    //       $this->dr_sess_id = session()->get('doctor_session_id');
    //      if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
    //          $this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
    //          return redirect()->to('Doctor_login/doctor_login');
    //      }
    //      $args = [
    //          'doctor_id'  => $this->dr_sess_id,
    //          'is_del'        => 0,
    
    //          'revisit_date' => date('Y-m-d')
    //      ];
    //      $data = [
    //          'revisited_patients'  => $this->adminModel->fetch_rec_by_args_for_rev('revisit_patients', $args),
    //          'pager'   => $this->adminModel->pager
    //      ];
    //        // echo "<pre>";print_r($data);die;
    //      if (!isset($data['revisited_patients']) || $data['revisited_patients'] == '') {
    //          $this->session->setTempdata('error', 'Sorry ! No revisited patient found. Please see all patients here', 3);
    //          return redirect()->to(base_url() . "Doctor/all_patients");
    //      }
            
    //      return view('Doctor/manage_revisit_patient', $data);
    //  }
    // } //Function - Closed
    if (!(session()->has('doctor_session_uid'))) {
        return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    } else {
        $this->dr_sess_id = session()->get('doctor_session_id');
        $args = [
            //'status'          => 'Admission Processed',
            'is_del'  => 0,
            'doctor_id'  => $this->dr_sess_id,
               // 'revisit_date' => date('Y-m-d')
        ];
        $data = [
            'revisited_patients'  => $this->adminModel->fetch_rec_by_args_for_rev('revisit_patients', $args),
            'pager'   => $this->adminModel->pager
        ];
        if (!isset($data['revisited_patients']) || $data['revisited_patients'] == '') {
            $this->session->setTempdata('error', 'Sorry ! No revisited patient found. Please see all patients here', 3);
            return redirect()->to(base_url() . "Doctor/all_patients");
        }
        //echo "<pre>";print_r($data);die;
        return view('Doctor/manage_revisit_patient', $data);
    }
}
    
    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    // public function delete_revisit_patients($id){
    //  $this->patient_id = $id;
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $this->doctor_uid = session()->get('doctor_session_uid');
    //  if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
    //      $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [
    //      'id'   => $id
    //  ];
    //  $data = [
    //      'status' => 'Deleted', //0: Non deleted, 1: Deleted
    //      'updated_at' => date('Y-m-d H:i:s'),
    //      'updated_by' => $this->doctor_uid
    //  ];

    //  //$status = $this->doctorModel->delete_rec_by_args('revisit_patients', $data, $args);
    //  $status = $this->doctorModel->update_rec_by_args('revisit_patients',  $args, $data);
    //  if ($status == true) {
    //      session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
    //      //return redirect()->to(base_url().'Doctor/manage_today_discharged_patient');
    //      return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    //  } else {
    //      session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
    //      // return redirect()->to(base_url().'Doctor/manage_revisit_patient');
    //      return view('Doctor/manage_revisited_patients', $data);
    //  }
    // } //Function - Closed

    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    // public function search_results(){
    //  if (!(session()->has('doctor_session_uid'))) {  
    //      return redirect()->to(base_url()."/Doctor_login/doctor_login");
    //  }  
        
    //  $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
        
    //  // Remove spaces from the user input
    //  //$keyword = str_replace(' ', '', $keyword);
        
    //  $args = [
    //      //'status' => 'Discharged'
    //  ];
        
    //  if ($keyword) {  
    //      // Search for data with spaces removed
    //      $this->result_arr = $this->doctorModel->search_records('revisit_patients', 'patient_name', $keyword, $args);
    //  } else {  
    //      $this->result_arr = $this->doctorModel;
    //  }   
    
    //  $data = [
    //      'revisited_patients' => $this->doctorModel->fetch_rec_by_args_with_status('revisit_patients', $args),
    //      'pager' => $this->doctorModel->pager
    //  ];
        
    //  return view('Doctor/manage_revisit_patient', $data);
    // } //Function - Closed

    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    public function fetch_patients() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        else {
            $args = [ 'login_acc'   => 1];
            $data = [
                'doctors' => $this->doctorModel->fetch_rec_by_args('doctor', $args),
            ];
            return view('Doctor/add_patients', $data);
        }
    } //Function - Closed

    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    // public function filter_revisit_patient($filter){
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  if ($filter == 'new_patients') {
    //      $order = [
    //          'column_name'  => 'id',
    //          'order'        => 'desc'
    //      ];
    //  } else if ($filter == 'old_patients') {
    //      $order = [
    //          'column_name'  => 'id',
    //          'order'        => 'asc'
    //      ];
    //  } else {
    //      $order = [
    //          'column_name'  => 'id',
    //          'order'        => 'desc'
    //      ];
    //  }
    //  $args = [
    //      //'status'        => 'Discharged'
    //      'is_del'        => 0
    //  ];

    //  $data = [
    //      'revisited_patients' => $this->doctorModel->filter_rec_by_args_with_pagination('revisit_patients', $order, $args),
    //      'pager'     => $this->doctorModel->pager
    //  ];

    //  if ($data['revisited_patients'] == false) {
    //      $this->session->setTempdata('error', 'Sorry ! No record found!', 3);
    //      return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    //  }
    //  return view('Doctor/manage_revisit_patient', $data);
    // } // Function - Closed
    
    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    // public function revisit_patients($id){
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [
    //      'id'  => $id
    //  ];
    //  $data['patients'] = $this->doctorModel->fetch_rec_by_args('revisit_patients', $args);
    //  $data['doctors']  = $this->commonForAllModel->fetch_all_records('doctor');
    //  if ($data['doctors'] == false) {
    //      $this->session->setTempdata('error', 'Sorry ! No record found ', 3);
    //      return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    //  }
    //  return view('Doctor/revisit_patients', $data);
    // } //Function - Closed

    /* @param: Revisit Patients Under Doctor Section
    * @description:  Doctor can number of revisited patients
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 27/Nov/2023
    */
    // public function number_of_visit_patients($id){
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args =  [
    //      'patient_id'  => $id
    //  ];
    //  $data['pat_visiter'] = $this->doctorModel->fetch_rec_by_args('revisit_patients', $args);
    //  if ($data['pat_visiter'] == false) {
    //      $this->session->setTempdata('error', 'Sorry ! No revisited patient found ', 3);
    //      return redirect()->to(base_url() . "Doctor/manage_revisited_patients");
    //  }
    //  //echo "<pre>";print_r($data['pat_visiter']);die;   //data are coming from backend side
    //  return view('Doctor/number_of_visiting_pat', $data);
    // } //Function - Closed

    // /* @param: Revisit Patients Under Doctor Section
    // * @description:  Doctor can number of revisited patients
    // * @Remark: 
    // * @author: Neoark's Team
    // * @copyrights: Neoark Software Pvt Ltd
    // * @date: 27/Nov/2023
    // */
    // public function change_revisit_patients_status($id, $status) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $this->doctor_uid = session()->get('doctor_session_uid');
    //  if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
    //      $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [ 'id'   => $id ];
    //  $data = [
    //      'status'  => $status,
    //      'updated_at' => date('Y-m-d H:i:s'),
    //      'updated_by' => $this->doctor_uid
    //  ];

    //  $status = $this->doctorModel->update_rec_by_args('revisit_patients', $args, $data);
    //  if ($status == true) {
    //      session()->setTempdata('success', 'Congratulation ! Patients Status Updated Successfully', 2);
    //      return redirect()->to(base_url() . '/Doctor/manage_revisited_patients');
    //  }
    // } // Function - Closed

    // public function manage_revisited_patients(){
    //      // echo 'Im here';die;
    //  $this->doctor_uid = session()->get('doctor_session_uid');
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } else {
    //      $args = [
    //          //'status'  => 'Active'
    //          'is_del'  => 0
    //      ];
    //      $data = [
    //          'revisited_patients'  => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
    //          'pager'   => $this->adminModel->pager
    //      ];
    //      return view('Doctor/manage_revisit_patient', $data);
    //  }
    // } //Function - Closed

    /* @params: Function for permanent delete revisit patients
    * @desc: Admin can soft revisit patients also a soft deleted data can be permanently delete by this function
    * @use: Admin....
    * @return:
    * @author: Neoarks Team
    * @date: 16th August,2023
    * @modify
    */
    // public function permanent_del_revisit_patients($id){
    //  $this->patient_id = $id;
        
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $this->doctor_uid = session()->get('doctor_session_uid');
    //  if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
    //      $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [
    //      'id'   => $id,
    //      // 'is_del'   => '1'
    //  ];
    //  $data = [
    //      'is_del'   => '1',
    //      'status' => 'Deleted', //0: Non deleted, 1: Deleted
    //      'updated_at' => date('Y-m-d H:i:s'),
    //      'updated_by' => $this->doctor_uid
    //  ];

    //  //$status = $this->doctorModel->delete_rec_by_args('revisit_patients', $data, $args);
    //  $status = $this->doctorModel->update_rec_by_args('revisit_patients',  $args, $data);
    //  if ($status == true) {
    //      session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
    //      //return redirect()->to(base_url().'Doctor/manage_today_discharged_patient');
    //      return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    //  } else {
    //      session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
    //      // return redirect()->to(base_url().'Doctor/manage_revisit_patient');
    //      return view('Doctor/manage_revisited_patients', $data);
    //  }
    // } // Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function change_all_patients_status($id, $status){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }

            $args = ['id' => $id];
            $data = [
                'status' => $status,
                //'updated_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d'),
                'updated_by' => $this->dr_sess_id,
            ];
            // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
            $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
            if ($status == true) {
                $this->session->setTempdata('success', 'Congratulation ! action done  successfully !', 3);
            } else {
                $this->session->setTempdata('error', 'Sorry ! Unable to discharge. Please try again ?', 3);
            }
            return redirect()->to(base_url() . 'Doctor/all_patients');
            //return redirect()->to(base_url().'Doctor'); //Today's patient dischage on doctor dashboard section, should be redirect to the dashboard
        } //else loop - Closed
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    public function filter_doc_dis_patients($filter){
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
            return redirect()->to('Doctor_login/doctor_login');
        }
        if ($filter == 'new_patient') {

            $order = [
                'column_name' => 'id',
                'order' => 'asc'
            ];
        } else if ($filter == 'old_patient') {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        } else {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        }
        
        $args = [
            'doctor_id' => $this->dr_sess_id,
            //'status'      => 'Discharged'
        ];

        $likeArgs = ['updated_at' => date('Y-m-d h:i:s')];
        $data = [
            'today_discharge_patients' => $this->patient_model->filter_rec_by_args_with_pagination('patients', $order, $args)
        ];
        return view('Doctor/today_discharge_patient', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function change_dshbrd_patients_status($id, $status){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'status' => '$status',
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients',  $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->AutoModel->update($id, $data);
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients Status Updated Successfully', 2);
            return redirect()->to(base_url() . '/Doctor');
        }
    } //Function - Closed

    

    
   /* @params: Function for get_dept_for_admission
    * @desc: 
    * @use: Admin....
    * @return:
    * @author: Neoarks Team
    * @date: 18th August,2023
    * @modify
    */
    public function get_dept_for_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->data['patient_id'] = $patient_id;
        $this->data['pid'] = $pid;
        $this->data['puid'] = $puid;
        $this->data['apmt_id'] = $apmt_id;
        $this->data['dr_id'] = $dr_id;
        //$this->data['patient_id'] = $patient_id;
        $this->doctor_id = session()->get('doctor_session_id');
        $this->dept_args = [
            'is_del'    => 0,
            'status'    => 'Active',
        ];
        //$this->data['patient_id']
        $this->data['departments'] = $this->doctorModel->fetch_rec_by_args('department', $this->dept_args);
        //echo "<pre>";print_r($this->data);die;
        if($this->request->getMethod() == 'get') {
            return view('Doctor/admission_process', $this->data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method !', 3);
            return view('Doctor/admission_process', $this->data);
        }
    } //function - closed

    public function get_dept_tday_for_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->data['patient_id'] = $patient_id;
        $this->data['pid'] = $pid;
        $this->data['puid'] = $puid;
        $this->data['apmt_id'] = $apmt_id;
        $this->data['dr_id'] = $dr_id;
        //$this->data['patient_id'] = $patient_id;
        $this->doctor_id = session()->get('doctor_session_id');
        $this->dept_args = [
            'is_del'    => 0,
            'status'    => 'Active',
        ];
        //$this->data['patient_id']
        $this->data['departments'] = $this->doctorModel->fetch_rec_by_args('department', $this->dept_args);
        //echo "<pre>";print_r($this->data);die;
        if($this->request->getMethod() == 'get') {
            return view('Doctor/today_admission_process', $this->data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method !', 3);
            return view('Doctor/today_admission_process', $this->data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }

        $this->doctor_id = session()->get('doctor_session_id');
        $this->data['wards'] = [];
        if($this->request->getMethod() == 'post') {
            $this->dept_id  = $dept_id;
            $this->ward_args = ['dept_id'       => $this->dept_id];
            $this->data['wards'] = $this->doctorModel->fetch_rec_by_args('hospital_wards', $this->ward_args);
            if($this->data['wards'] === false) {
                $this->result_arr = array(
                    'status'    => 'false',
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    'code'      => 200,
                    'message'   => 'No ward found. Please talk admin.',
                    'data'      => array(),
                );
                return json_encode($this->result_arr);
            }
            else {
                $this->result_arr = array(
                    'status'    => 'true',
                    'error'     => false, //error: `false` whereever status is true with SQL err 
                    'code'      => 200,
                    'message'   => 'Ward fetched successfully',
                    'data'      => $this->data['wards'],
                );
                return json_encode($this->result_arr);
            }
        }
        else {
            // $this->session->setTempdata('error', 'Unexpected request method !', 3);
            // return view('Doctor/admission_process', $data);
            $this->result_arr = array(
                'status'    => 'false',
                'error'     => true, //error: `true` whereever status is false with SQL err 
                'code'      => 200,
                'message'   => 'Unexpected request method !',
                'data'      => $this->data['wards'],
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->doctor_id = session()->get('doctor_session_id');
        if($this->request->getMethod() == 'post') {
            $this->ward_id  = $ward_id;
            $this->bed_args = [
                'ward_id'       => $this->ward_id,
                'is_free'       => 1,
                ];
            $this->data['beds'] = $this->doctorModel->fetch_rec_by_args('hospital_beds', $this->bed_args);
            
            if($this->data['beds'] === false) {
                $this->result_arr = array(
                    'status'    => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    'code'      => 200,
                    'message'   => 'No bed found. Please talk admin.',
                    'data'      => $this->data['beds'],
                );
                return json_encode($this->result_arr);
            }
            else {
                $this->result_arr = array(
                    'status'    => 'true',
                    'error'     => false, //error: `false` whereever status is true with SQL err 
                    'code'      => 200,
                    'message'   => 'Bed fetched successfully',
                    'data'      => $this->data['beds'],
                );
                return json_encode($this->result_arr);
            }
        }
        else {
            // $this->session->setTempdata('error', 'Unexpected request method !', 3);
            // return view('Doctor/admission_process', $data);
            $this->result_arr = array(
                'status'    => 'false',
                'error'     => true, //error: `true` whereever status is false with SQL err 
                'code'      => 200,
                'message'   => 'Unexpected request method',
                'data'      => array(),
            );
            return json_encode($this->result_arr);
        }
    } //function - closed

    public function admission_process() { 
        $this->insrtData = []; //Just for addressing notices
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $data  = [];
        // $data['validation'] = null;
        if($this->request->getMethod() == 'post') { 
            $this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
            $this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
            if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
                $result_arr = array(
                    'status' => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code'  => 200,
                    'message' => 'Patient ID may not blank',
                    'data' => $this->insrtData
                );
                return json_encode($result_arr);
            }
            $this->updt_data_arr = [
                    'department_name'  => $this->request->getPost('department_name',FILTER_SANITIZE_STRING),
                    'ward_name'         => $this->request->getPost('wardname',FILTER_SANITIZE_STRING),
                    'bed_lable'        => $this->request->getPost('bed_lable',FILTER_SANITIZE_STRING),
                    'other_info'       => $this->request->getPost('other_info',FILTER_SANITIZE_STRING),   //Need to mention name in HTML
                    'patient_id'       => $this->patient_id,
                    'is_free'       => 0,
                    'pid'           => $this->request->getPost('pid',FILTER_SANITIZE_STRING),
                    'puid'          => $this->request->getPost('puid',FILTER_SANITIZE_STRING),
                    'apmt_id'       => $this->request->getPost('apmt_id',FILTER_SANITIZE_STRING),
                    'dr_id'         => $this->request->getPost('dr_id',FILTER_SANITIZE_STRING),
                    'updated_at'    => date('Y-m-d h:i:s'),
                    'updated_by'    => $this->doctor_uid,
            ];
            //echo "<pre>"; print_r($this->updt_data_arr);die;
            $this->updt_args = [ 'id'   => $this->bed_id ];  
            $status = $this->doctorModel->update_rec_by_args('hospital_beds', $this->updt_args, $this->updt_data_arr );  
            if ($status === true) {
                $this->updt_data_arr = [
                    'status'        => 'Admission Processed',
                    'updated_at'    => date('Y-m-d h:i:s'),
                    'updated_by'    => $this->doctor_uid,
                ];
                $this->updt_args = [ 'id'   => $this->patient_id];
                $status = $this->doctorModel->update_rec_by_args('patients', $this->updt_args, $this->updt_data_arr);  
                if ($status === true) {
                    $result_arr = array(
                        'status' => true,
                        'error'  => false, //error: `false` whereever status is true with SQL err 
                        'code'  => 200,
                        'message' => 'Record updated successfully',
                        'data' => $this->updt_data_arr
                    );
                    return json_encode($result_arr);
                } 
                else {
                    $result_arr = array(
                        'status' => false,
                        'error'  => true, //error: `true` whereever status is false with SQL err 
                        'code'  => 200,
                        'message' => 'Failed! to update record',
                        'data' => $this->updt_data_arr
                    );
                    return json_encode($result_arr);
                } //else - loop closed 
            }
            else {
                $result_arr = array(
                    'status' => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code'  => 200,
                    'message' => 'Failed! to update record',
                    'data' => $this->updt_data_arr
                );
                return json_encode($result_arr);
            } //else - loop closed
        }
        else {
            $result_arr = array(
                'status' => false,
                'error'  => true, //error: `true` whereever status is false with SQL err 
                'code'  => 200,
                'message' => 'Unexpected request method',
                'data' => $this->updt_data_arr
            );
            return json_encode($result_arr);
        } //else - loop closed
    } //function - Closed
    
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    public function add_appointment_pat_charge($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
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
                $this->user_data_arr = [
                    'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
                    'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
                    'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
                    'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
                    'patients_id'           =>  $args,
                    'status'                =>  'Discharged',
                    'pay_date'        =>  date('Y-m-d h:i:s')
                ];
                $status = $this->doctorModel->Insertdata('patients_pay_charges', $this->user_data_arr);
                if ($status == true) {
                    $this->session->setTempdata('success', 'Congratulations ! Patient charges added successfully ', 3);
                    //return redirect()->to(base_url().'Doctor/discharge_apmnt_patient/'.$id);
                } else {
                    $this->session->setTempdata('error', 'Sorry ! Failed to add Patient charges ', 3);
                    return redirect()->to(base_url() . 'Doctor/discharge_apmnt_patient/' . $id);
                }
                return redirect()->to(base_url() . 'Doctor/generate_apment_patient_bill/' . $id);
            } else {
                $data['validation'] = $this->validator;
            }
        }
        $data['patients'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        return view('Doctor/discharge_appointment_pat', $data);
    } //Function - Closed

    public function generate_apment_patient_bill($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $args = [ 'patients_id'  => $id ];
            $data['payment_bill'] = $this->doctorModel->fetch_rec_by_args('patients_pay_charges', $args);
            $args = [ 'id'  => $id ];
            $value = [ 'status'  => 'Discharged' ];
            $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $args, $value);
            return view('Doctor/generate_apment_patient_bill', $data);
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function filter_today_patients($filter){
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
            //echo "<pre>"; print_r($order);die;
        } else {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        }
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
            return redirect()->to('Doctor_login/doctor_login');
        }
        $args = [
            'doctor_id'  => $this->dr_sess_id,
            'is_del'        => 0,
            'registration_date' => date('Y-m-d')
        ];

        $likeArgs = [
            'updated_at' => date('Y-m-d')
        ];
        $data = [
            'today_patients' => $this->doctorModel->filter_rec_by_args_with_pagination('patients', $order, $args),
            'pager' => $this->doctorModel->pager
        ];
        return view('Doctor/today_patients', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function filter_all_patients($filter){
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
            //echo "<pre>"; print_r($order);die;
        } else {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        }
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
            return redirect()->to('Doctor_login/doctor_login');
        }
        $args = [
            'doctor_id'  => $this->dr_sess_id,
            'is_del'        => 0,
        ];

        $likeArgs = [
            'updated_at' => date('Y-m-d')
        ];
        $data = [
            'all_patients' => $this->doctorModel->filter_rec_by_args_with_pagination('patients', $order, $args),
            'pager' => $this->doctorModel->pager
        ];

        return view('Doctor/all_patients', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    public function filter_today_discharge_patient($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        if ($filter == 'new_patients') {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        } else if ($filter == 'old_patients') {
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
        $this->dr_sess_id = session()->get('doctor_session_id');

        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Doctor sessin ID is missing!', 3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            // 'doctor_id' => $this->dr_sess_id,
            // //'status' => 1,
            // //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
            // 'registration_date' => date('Y-m-d')
            'doctor_id' => $this->dr_sess_id,
            'status' => 'Discharged',
            'registration_date' => date('Y-m-d')
        ];
        $data = [
            'today_discharge_patients' => $this->doctorModel->filter_rec_by_args_with_pagination('patients', $order, $args),
            'pager' => $this->doctorModel->pager
        ];
        
        return view("Doctor/today_discharge_patient", $data);
    }//Function- Closed
    
    /* @params: 
     * @desc: 
     * @use
     * @author: Neoarks Team
     * @date: 
     * @modify:
     */
    public function filter_all_discharge_patient($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
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
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Sorry missing doctor ID', 3);
            return redirect()->to('Doctor_login/doctor_login');
        }
        $args = [
            'doctor_id' => $this->dr_sess_id,
            'status' => 'Discharged'
        ];

        $likeArgs = []; //'updated_at' => date('Y-m-d h:i:s')


        $data = [
            // 'all_patients' => $this->patient_model->fetch_rec_by_args_filter_order_like('patients', $args, $likeArgs, $order),
            'all_patients' => $this->doctorModel->fetch_rec_by_args_filter_order_like('patients', $args, $likeArgs, $order),
            'pager' => $this->doctorModel->pager
        ];
        return view('Doctor/total_discharge_patient', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function change_doctor_password(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data = [];
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            //echo "<pre>"; print_r($data['loggedin_usr']);die;
            if ($this->request->getMethod() == 'post') {
                $rules = [
                    'old_password' => 'required',
                    // 'new_password' => 'required|min_length[6]|max_length[20]',
                    'new_password'  => [
                        'rules'     => 'required|min_length[6]|max_length[20]',
                        'errors'    => [
                            'required' => 'New password mandatory.',
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
                        $status = $this->doctorModel->updatePassword('register_all_users', $npwd, $this->doctor_uid);
                        if ($status) {
                            $this->session->setTempdata('success', 'Congratulation ! Password has updated successfully!', 3);
                            //return redirect()->to(current_url());
                            return view('Doctor/change_doctor_password', $data);
                        } 
                        else {
                            $this->session->setTempdata('error', 'Sorry Unable to update password. Please try again!', 3);
                            //return redirect()->to(current_url());
                            return view('Doctor/change_doctor_password', $data);
                        }
                    } 
                    else {
                        $this->session->setTempdata('error', 'Incorrect login email or password', 3);
                        return view('Doctor/change_doctor_password', $data);
                    }
                } 
                else {
                    $data['validation'] = $this->validator;
                    return view('Doctor/change_doctor_password', $data);
                }
            }
            else {
                $this->session->setTempdata('error', 'Sorry Unepxected request method', 3);
                return view('Doctor/change_doctor_password', $data);
            }
        }
        return view('Doctor/change_doctor_password', $data);
    }//Function- Closed

    /*@params: Set Doctor availability slots
     * @desc: Free slots may be booked/take appointments by patients (and even Doctors, Frontdesk admin and Administrator as well)
     * @author: Neoarks Team
     * @date: May 24th, 2023
     * @modify:
     */
    public function set_dr_availability(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {

            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            if (is_array($this->dr_sess_id) && isset($this->dr_sess_id)) {
                $this->args = [
                    'doctor_id' => $this->dr_sess_id //Logged-in Dr. ID
                ];
            } else {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);

            return view('Doctor/doctor_availability', $this->data);
        } //else - Closed
    } //Funciton - Closed


   /* @params: get Doctor availability slots
    * @desc: Free slots may be booked/take appointments by patients (and even Doctors, 
    * Frontdesk admin and Administrator as well)
    * @author: Neoarks Team
    * @date: May 24th, 2023
    * @modify:
    */
    public function getset_dr_slots(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $this->selected_date = date("Y-m-d");

            $this->args = [
                'doctor_id' => $this->dr_sess_id, //Logged-in Dr. ID
                'appointment_date' => $this->selected_date
            ];

            $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
             
            
            if ($this->data['dr_slots'] === false) {
                $this->data['dr_slots'] = array(array());
                return view('Doctor/doctor_availability', $data);
            } 
            else { 
                //echo "<pre>";print_r($data);die;
                return view('Doctor/doctor_availability', $data);
            }
        } //else - Closed
    } //Funciton - Closed


   /* @params: get Doctor availability slots
    * @desc: Free slots may be booked/take appointments by patients (and even Doctors, 
    * Frontdesk admin and Administrator as well)
    * @author: Neoarks Team
    * @date: May 24th, 2023
    * @modify:
    */
    public function getset_dr_slots_ajax(){
        //$data['dr_name']='prashant';
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } 
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $this->selected_date = $this->request->getVar('date',FILTER_SANITIZE_STRING);
            //$this->selected_date = 
            $this->args = [
                'doctor_id' => $this->dr_sess_id,
                'appointment_date' => $this->selected_date
            ];
            //echo "<pre>";print_r($this->args);die;    
            $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
            if ($this->data['dr_slots'] === false) {
                $this->data['dr_slots'] = array(array());
                //return view('Doctor/doctor_availability', $data);
                $this->data['dr_slots'] = array(array());
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    'data'  => $this->data['dr_slots'],
                    "message" => 'No slot available'
                ); 
                return json_encode($result_arr);
            } 
            else {
                //return view('Doctor/doctor_availability', $data);
                $result_arr = array(
                    "status" => true,
                    'error'  => false, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    'data'  => $this->data['dr_slots'],
                    "message" => 'Doctor slots are available'
                ); 
                return json_encode($result_arr); 
            }
        } //else - Closed
    } //Funciton - Closed



    //******* NEO Slots functions - START

    /* @params: get Doctor availability slots
    * @desc: Free slots may be booked/take appointments by patients (and even Doctors, 
    * Frontdesk admin and Administrator as well)
    * @author: Neoarks Team
    * @date: May 24th, 2023
    * @modify:
    */
    public function getset_dr_slots_neo() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } 
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $this->selected_date = date("Y-m-d");//current date: NOT selected by default

            $this->args = [
                'doctor_id' => $this->dr_sess_id, //Logged-in Dr. ID
                'appointment_date' => $this->selected_date
            ];

            $this->data['dr_slots'] =$this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
            //echo "<pre>"; print_r($this->data['dr_slots']);die;
            if($this->data['dr_slots'] === false ) { 
                $this->session->setTempdata('error', 'No slot available.', 3);
                //return redirect()->to(base_url() . "Home/index");
                return view('Doctor/doctor_availability_neo', $this->data);
            }
            else { 
                return view('Doctor/doctor_availability_neo', $this->data);
            }
        } //else - Closed
    } //Funciton - Closed


   /* @params: get Doctor availability slots
    * @desc: Free slots may be booked/take appointments by patients, 
    * Frontdesk and Administrator
    * @author: Neoarks Team
    * @date: May 24th, 2023
    * @modify:
    */
    public function getset_dr_slots_ajax_neo(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } 
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $this->data = []; //For addressing notices
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            //$this->selected_date = $this->request->getVar('date',FILTER_SANITIZE_STRING);
            $this->selected_date = $this->request->getPost('selected_date',FILTER_SANITIZE_STRING);
            if(!$this->selected_date || $this->selected_date == '') {
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true`, status is false with SQL err 
                    "code" => 200,
                    'data'  => $this->data,
                    "message" => 'Date is missing. Please select date'
                ); 
                return json_encode($result_arr);
            }

            $this->args = [
                'doctor_id' => $this->dr_sess_id, //Logged-in Dr. ID
                'appointment_date' => $this->selected_date
            ];
            //echo "<pre>";print_r($this->args);die;    
            $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
            //echo "<pre>";var_dump($this->data['dr_slots']);die;       
            if ($this->data['dr_slots'] === false) {
                $this->data['dr_slots'] = array(array());
                //return view('Doctor/doctor_availability', $data);
                $this->data['dr_slots'] = array(array());
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    'data'  => $this->data['dr_slots'],
                    "message" => 'No slot available'
                ); 
                return json_encode($result_arr);
            } 
            else {
                //return view('Doctor/doctor_availability', $data);
                $result_arr = array(
                    "status" => true,
                    'error'  => false, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    'data'  => $this->data['dr_slots'],
                    "message" => 'Doctor slots are available'
                ); 
                return json_encode($result_arr); 
            }
        } //else - Closed
    } //Funciton - Closed
    //******* NEO Slots functions - END


   /* @params:
    * @desc: Add fee form load
    * @retuns:
    * @author: Neoarks Team
    * @date: 4th November, 2023
    * @modify:
    */
    public function add_fee($apmt_id, $status, $pid, $puid, $serial, $dr_id) {
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if ($this->request->getMethod() == 'get') {
            $this->apmt_id = (int) $apmt_id; //appointment ID
            $this->status = (int) $status; //Status
            $this->pid = (int) $pid; //Patient ID
            $this->puid = $puid; //Hospital assigned patient unique ID
            $this->appt_serial = (int) $serial; //Appointment serial 
            $this->dr_id = (int) $dr_id; //Appointment serial 
            $data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function

            $data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices

            //$dr_args = [ 'id'  => $this->dr_id  ]; @raaj
            $dr_args = [ 'ref_id'  => $this->dr_id  ];
            $data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $dr_args); 

            $apmt_args = [ 'id'  => $this->apmt_id ]; 
            $data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);

            $apmt_pay_args = [ 'apmt_id'  => $this->apmt_id ]; 
            $data['apmt_paymnt'] = $this->doctorModel->fetch_rec_by_args('patients_pay_charges', $apmt_pay_args);
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
                return view('Doctor/payments/add_fee', $data); // Pass the data to the view
            }
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        return view('Doctor/payments/add_fee', $data); // Pass the data to the view
    } //function - Closed

    /*@params:
    * @desc: Add fee form load
    * @retuns:
    * @author: Neoarks Team
    * @date: 4th November, 2023
    * @modify:
    */
    
    public function save_fee($patient_id, $pid, $apmtid, $puid, $serial, $payid) {
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }

        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = (int) $patient_id; //Patient ID
        $this->apmt_id    = (int) $apmtid; //appointment ID
        $this->pid  = (int) $pid; //Patient ID
        $this->puid = $puid; //Hospital assigned patient unique ID
        $this->appt_serial = (int) $serial; //Appointment serial 
        $this->pay_chrg_id =  (int) $payid;
        
        $this->data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
        $this->apmt_args = ['id'    => $this->apmt_id];
        $this->data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->apmt_args);

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
        

        $this->dr_args = ['id'  => $this->doctor_id]; 
        $this->data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->dr_args);

        if ($this->request->getMethod() == 'get') {
            return view('Doctor/add_fee', $this->data); //Reunder Fee form
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
                    'paid_apmt_fee'     => 1, //1: paid
                    'paid_doctor_fee'   => 1, //1: paid
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->doctor_uid,
                ];
                $this->appointment_fee = $this->request->getVar('appointment_fee', FILTER_SANITIZE_STRING);
                $this->doctor_fee = $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING);
                    $this->expnc_chrg_arr = [
                        'patient_name'  => $this->patient_name, 
                        'doctor_id'     => $this->doctor_id,
                        'doctor_name'   => $this->doctor_name, 
                        'medical_item'  => 'Doctor Fee',
                        'medical_item_desc'=> 'Added Doctor Fee',
                        'unit_price'        => $this->doctor_fee,
                        'units'             => 1, //One time attended
                        'price'             => $this->doctor_fee,
                        'extra_charges'     => '0.00',
                        'tax_percentage'    => '0',
                        'tax_amount'        => '0.00',
                        'total_Price'   => $this->doctor_fee,
                        'expns_ref'     =>  'Patient ref by ' . $this->ref_by,
                        'pid'           =>  $this->pid,
                        'puid'          =>  $this->puid,
                        'patients_id'   =>  $this->patient_id,
                        'discount_percentage' =>  '0.00',
                        'discount_amount'   =>  '0.00',
                        'apmt_id'       =>  $this->apmt_id,
                        'created_at'    =>  date('Y-m-d h:i:s'),
                        'created_by'    =>  $this->doctor_uid,
                    ];

                    $this->user_pay_chrg_arr = [
                        'registration_fee'  => $this->request->getVar('appointment_fee', FILTER_SANITIZE_STRING),
                        'doc_fee'       => $this->doctor_fee,
                        'paid_amount'   => $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
                        'payment_note'  => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                        'pid'           =>  $this->pid,
                        'puid'          =>  $this->puid,
                        'patients_id'   =>  $this->patient_id,
                        'apmt_id'       =>  $this->apmt_id,
                        'patient_name'  => $this->patient_name, 
                        'country_code'  => $this->country_code, 
                        'patient_phone' => $this->patient_mobile,   
                        'ref_by'        => $this->ref_by,
                        'gender'        => $this->gender,
                        'age'           => $this->age,
                        'doctor_id'     => $this->doctor_id,
                        'doctor_name'   => $this->doctor_name,
                        'pay_date'      => date('Y-m-d'),
                        'created_at'    => date('Y-m-d h:i:s'),
                        'created_by'    => $this->doctor_uid,
                    ]; 

                    $this->apmt_args = ['id' => $this->apmt_id];
                    
                    //$this->last_inst_id = $this->commonForAllModel->return_inserted_id_n_update('patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
                    $this->last_inst_id = $this->commonForAllModel->insert_two_n_update_one_tbl('treatment_expenses_history', $this->expnc_chrg_arr, 'patients_pay_charges', $this->user_pay_chrg_arr, 'booked_doctor_appointment', $this->apmt_args, $this->updt_apmt_arr);
                    
                    $this->data['apmt_paymnt_payid'] = $this->last_inst_id; //used in next call - perhapse
                    if ($this->last_inst_id === 2) { //Failed to update appointment table
                        return redirect()->to(base_url().'Doctor/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                    }
                    else if ($this->last_inst_id === 3) { //Failed to insert into patient pay charges table
                        return redirect()->to(base_url().'Doctor/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                    } 
                    else if ($this->last_inst_id === 4) { //Failed to insert treatment expences table 
                        return redirect()->to(base_url().'Doctor/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                    } 
                    else { //success:
                        return redirect()->to(base_url() . 'Doctor/generate_initial_pay_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            return redirect()->to(base_url() . 'Doctor/add_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
        }
        if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
        $args = [ 'id'  => $this->patient_id ]; 
            $update_dt_arr = [
                    'status'  => 'Fee Paid', 
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'updated_by'  => $this->doctor_uid,
                ];
            $this->updt_status = $this->commonForAllModel->update_rec_by_args('patients', $args, $update_dt_arr);
        }
        $apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
        $data['apmt_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
            if($this->updt_status === true) { //When attended the loggedin patient's appointment
                if(!isset($data['apmt_patient'][0]->doctor_id)) {
                    $this->session->setTempdata('error', 'Doctor id is not found in appointment table', 3);
                    return view('Doctor/payments/generate_admission_bill', $data);
    
                }
                if($data['apmt_patient'][0]->doctor_id == 0 || $data['apmt_patient'][0]->doctor_id == '') {
                    $this->session->setTempdata('error', 'Doctor id is blank or zero in appointment table', 3);
                    return view('Doctor/payments/generate_admission_bill', $data);
                }
            //$this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial);
            $this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
            $this->session->setTempdata('success', 'Bill generated successfull', 3);
            return view('Doctor/payments/generate_initial_fee_bill', $data);
        }
        else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
            $this->change_all_appointments_status($this->apmt_id, $this->status, $this->pid, $this->puid, $this->appt_serial, $data['apmt_patient'][0]->doctor_id);
            $this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
            return view('Doctor/payments/generate_initial_fee_bill', $data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected result', 3);
            return view('Doctor/payments/generate_initial_fee_bill', $data);
        }
    } //function - Closed


    /* @params: Show Doctor availability slots
     * @desc: Available slots may be booked/take appointments by patients (and even Doctors, 
     * Frontdesk admin as well)
     * @author: Neoarks Team
     * @date: May 30th, 2023
     * @modify: UPDATED CODE ///AT NOV/07/2023
     */
    public function get_dr_slots($dt){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
            $this->selected_date = $dt; //$this->request->getJSON();
            if (!isset($this->selected_date) || $this->selected_date == '') {
                $this->selected_date = date("Y-m-d");
            }
            if (is_array($this->dr_sess_id) && isset($this->dr_sess_id)) {
                $this->args = [
                    'doctor_id' => $this->dr_sess_id,
                    //Logged-in Dr. ID
                    'appointment_date' => $this->selected_date,
                    'dr_available' => 1
                ];
            } else {
                $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);
            //echo "<pre>"; print_r($this->data['dr_slots']);die;

            if ($this->data['dr_slots'] === false) {
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    'data' => $this->data['dr_slots'],
                    "message" => 'No slot available'
                );
                return json_encode($result_arr);

                $this->data['dr_slots'] = array(array());
                return $this->data['dr_slots'];
            } else if (is_array($this->data)) {
                //return view('Doctor/doctor_availability', $this->data);                   
                $result_arr = array(
                    "status" => true,
                    'error'  => false, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    'data' => $this->data['dr_slots'],
                    "message" => 'Following slots are available'
                );
                //return $result = json_encode($this->data['dr_slots']);
                return $result = json_encode($result_arr);
                //echo "<pre>"; print_r($this->data['dr_slots']);
                //return $this->data['dr_slots'];
            } else {
                $result_arr = array(
                    "status" => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    'data' => $this->data['dr_slots'],
                    "message" => 'No slot available'
                );
                return json_encode($result_arr);
            }
        } //else - Closed
    } //Funciton - Closed


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
            $this->data['doctors'][0]->id = 0;          //Used in view
            $this->data['doctors'][0]->doctor_name = '';//Used in view
            $this->data['doctors'][0]->education = '';  //Used in view
            $this->data['doctors'][0]->dr_specialization = ''; //Used in view

            $this->session->setTempdata('error', 'No doctor is registered yet!', 3);
            return redirect()->to(base_url() . "Home/index");
        }
        
        if($seldate == '') { $appointmntdate = date('Y-m-d'); }
        else { $appointmntdate = $seldate; }
        
        if ($this->dr_id == 0 || $this->dr_id == "") {
            $this->args = [
                'doctor_slots.dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
                'doctor_slots.appointment_date' => $appointmntdate
            ];
        }
        else { 
            $this->args = [
                'doctor_slots.dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
                'doctor_slots.doctor_id' => $this->dr_id,
                'doctor_slots.appointment_date' => $appointmntdate
            ];
        }
        $this->left_tbl = 'doctor_slots';
        $this->right_tbl = 'doctor';
        $this->join_terms =  'doctor_slots.doctor_id = doctor.ref_id';
        
        $this->data['dr_slots'] = $this->commonForAllModel->fetch_rec_by_args_leftjoin($this->left_tbl, $this->right_tbl, $this->join_terms, $this->args);

        if($this->data['dr_slots'] == false ) {
            $this->session->setTempdata('error', 'Sorry, No doctor slot is available !', 3);
            return view('Home/section/show_dr_available_slots_neo', $this->data);
        }
        return view('Home/section/show_dr_available_slots_neo', $this->data);
    }// Function - Closed


    /* @params: Show Doctor availability slots
     * @desc: Available slots may be booked/take appointments by patients (and even Doctors, 
     * Frontdesk admin as well)
     * @author: Neoarks Team
     * @date: May 30th, 2023
     * @modify: NOV/07/2023
     */
    public function doctors_available_slots($pid, $seldate = '', $seldocid = '') { 
        $this->dr_id = $seldocid;
        $this->args_dr = [
            'login_acc' => 1,   //1: Dr. a/c available  0: Dr.Login NOT available
            'status' => 'Active'// Active ie Admin Verified
        ];
        
        $this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->args_dr);
        
        if($this->data['doctors'] === false ) { 
            $this->data['doctors'][0] = new \stdClass; //stdClass object
            $this->data['doctors'][0]->id = 0;          //Used in view
            $this->data['doctors'][0]->doctor_name = '';//Used in view
            $this->data['doctors'][0]->education = '';  //Used in view
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
        //echo "<pre>"; print_r($this->args);die;
        $this->left_tbl = 'doctor_slots';
        $this->right_tbl = 'doctor';
        $this->join_terms =  'doctor_slots.doctor_id = doctor.ref_id';
        
        $this->data['dr_slots'] = $this->commonForAllModel->fetch_rec_by_args_leftjoin($this->left_tbl, $this->right_tbl, $this->join_terms, $this->args);
        if($this->data['dr_slots'] == false ) {
            return view('Home/section/show_dr_available_slots', $this->data);
        }
        return view('Home/section/show_dr_available_slots', $this->data);
    }// Function - Closed


    /* @params: Show Doctor availability slots based on selected Doctor and Date
     * @desc: Available slots may be booked/take appointments by patients 
     * (and even Doctors, Frontdesk admin and Administrator as well)
     * @use: Book Appointment @Home page
     * @author: Neoarks Team
     * @date: May 30th, 2023
     * @modify:
     */
    // public function available_selected_doctor_slots() { //get slots based on selected Doctor and Date
    //  $this->tot_dr = 0; //Just for addressing notices
    //  $this->data['doctors'] = array(); //Just for addressing notices
    //  $this->dr_args = [
    //      'login_acc' => 1,  //1: Existing Dr. login account
    //      'status' => 'Active',
    //  ];
    //  $this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->dr_args);
    //  $this->dr_id = $this->request->getGet('dr_id'); 
    //  if ($this->dr_id == 0 || $this->dr_id == "") {
    //      $this->args = [
    //          'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
    //          //'doctor_id' => $this->dr_id,
    //          'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
    //      ];
    //  }
    //  else {
    //      $this->args = [
    //          'dr_available' => 1,  //1: Dr. Available  0: Dr. NOT available
    //          'doctor_id' => $this->dr_id,
    //          'appointment_date' => $this->request->getGet('selected_date')  //Selected Date
    //      ];
    //  }
        
    //  $this->data['dr_slots'] = $this->doctorModel->fetch_rec_by_args('doctor_slots', $this->args);   
    //  if($this->data['dr_slots'] === false ) { //Need to apply it 
    //      $this->result_arr = array(
    //          'status'  => false,
    //          'error'  => true, //error: `true` whereever status is false with SQL err 
    //          'code'    => 200,
    //          'data'    =>  array(),
    //          'message' => 'No slot is available, select another date or another doctor.'
    //      );
    //      return json_encode($this->result_arr);
    //  } 
    //  else {
    //      $this->result_arr = array(
    //          'status' => true,
    //          'error'  => false, //error: `false` whereever status is true with SQL err 
    //          'code' => 200,
    //          'data' => $this->data,
    //          'message' => 'Please select to book an available slot'
    //      );
    //      return json_encode($this->result_arr);
    //  }
    //  //return view('Home/section/show_dr_available_slots', $this->data);
    // }// Function - Closed


    public function available_selected_doctor_slots() { 
        $this->dr_id = $this->request->getGet('dr_id'); //Selected Dr.

        $this->selected_apmt_date = $this->request->getGet('selected_date');

        if(!($this->selected_apmt_date) || ($this->selected_apmt_date == '')) {
            $this->selected_apmt_date = date('Y-m-d');
        }
        $this->tot_dr = 0; //Just for addressing notices
        $this->data['doctors'] = array(); //Just for addressing notices
        
        $this->dr_args = [
            'login_acc' => 1,  //1: Existing Dr. login account
            'status' => 'Active',
        ];
        $this->data['doctors'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->dr_args);
        //echo "<pre>";print_r($this->data['doctors']);die;
        if($this->data['doctors'] === false ) { 
            $this->data['doctors'][0] = new \stdClass; //stdClass object
            //$this->data['doctors'][0]->id = 0;        //Used in view
            $this->data['doctors'][0]->ref_id = 0;      //Used in view
            $this->data['doctors'][0]->doctor_name = '';//Used in view
            $this->data['doctors'][0]->education = '';  //Used in view
            $this->data['doctors'][0]->dr_specialization = ''; //Used in view

            $this->session->setTempdata('error', 'No doctor is registered yet!', 3);
            return redirect()->to(base_url() . "Home/index"); //NOT SUPPORTED IN AJAX
        }
        if (!$this->dr_id || !$this->selected_apmt_date) {
            $this->args = [
                'doctor_slots.dr_available' => 1,//1: Dr. Available  0: NOT available
                'doctor_slots.appointment_date' => $this->selected_apmt_date
            ];
        }
        else { 
            $this->args = [
                'doctor_slots.dr_available' => 1,//1: Dr. Available  0: NOT available
                'doctor_slots.doctor_id' => $this->dr_id,
                'doctor_slots.appointment_date' => $this->selected_apmt_date
            ];
        }

        //****************************************
        $this->left_tbl = 'doctor_slots';
        $this->right_tbl = 'doctor';
        $this->join_terms =  'doctor_slots.doctor_id = doctor.ref_id';
        
        $this->data['doctor_slots'] = $this->commonForAllModel->fetch_rec_by_args_leftjoin($this->left_tbl, $this->right_tbl, $this->join_terms, $this->args);
        //echo "<pre>"; print_r($this->data['dr_slots']);die;
        //****************************************
        if($this->data['doctor_slots'] === false ) { //Need to apply it 
            $this->result_arr = array(
                'status'  => false,
                'error'  => true, //error: `true` whereever status with SQL err 
                'code'    => 200,
                'data'    =>  array(),
                'message' => 'No slot is available, select another date or another doctor.'
            );
            return json_encode($this->result_arr);
        } 
        // //******************
        else { //******* Grouped slots - based on doctor IDs - START *******//
            //$this->data['doctor_slots'] = $this->data['dr_slots'];
            foreach($this->data['doctor_slots'] as $this->dr_arr) {
                $this->doctor_id = $this->dr_arr->doctor_id;
                $this->start_end_slot = $this->dr_arr->start_end_slot;

                // If doctor_id doesn't exist in the grouped array, create an entry
                if (!isset($this->data['doc_slots'][$this->doctor_id])) {
                    $this->data['doc_slots'][$this->doctor_id] = [
                        'doctor_name' => $this->dr_arr->doctor_name,
                        'education' => $this->dr_arr->education,
                        'dr_specialization' => $this->dr_arr->dr_specialization,
                        'profile_pic' => $this->dr_arr->profile_pic,
                        'gender' => $this->dr_arr->gender,
                        'doctor_fee' => $this->dr_arr->doctor_fee,
                        'doctor_id' => $this->dr_arr->doctor_id,
                        'appointment_date' => $this->dr_arr->appointment_date,
                        'slots' => []
                    ];
                }
                // Add the current slot to the doctor group
                $this->data['doc_slots'][$this->doctor_id]['slots'][] = [
                    'start_end_slot'=> $this->start_end_slot,
                    'slot_id'       => $this->dr_arr->id,
                    'doctor_id'     => $this->dr_arr->doctor_id
                ];
            } //foreach loop - Closed

            $this->result_arr = array(
                'status' => true,
                'error'  => false, //error: `false` whereever status is true with SQL err 
                'code' => 200,
                'data' => $this->data,
                'message' => 'Please select to book an available slot'
            );
            return json_encode($this->result_arr);
        } //******* Grouped slots - based on doctor IDs - END *******//
    }// Function - Closed


    /* @params: Save Doctor availability slots
     * @desc: Saving slots from toatal available slots.
     * @author: Neoarks Team
     * @date: May 25th, 2023
     * @modify:
     */
    // public function save_slots($dt){ //$new_slt_arr, $old_slt_arr
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
    //  else {
    //      $this->dr_sess_id = session()->get('doctor_session_id'); //Get doctor ID from session
    //      $doctor_name = session()->get('doctor_session_name');

    //      $this->del_slot_data = $this->request->getJSON();
    //      //$this->selected_date = '';
    //      $this->selected_date = $dt;
    //      if (!isset($this->selected_date) || $this->selected_date == '') {
    //          $this->selected_date = date('Y-m-d');
    //      }
    //      $this->new_slots_arr = array(
    //          'appointment_date' => $this->selected_date,
    //          //date("Y-m-d"),    //Need to get from availabitlity slot page
    //          'selected_slot' => $this->del_slot_data, //Expected from form  
    //      );

    //      $this->frm_start_end_slot_arr=$this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot']; //Start_end_slot
    //      $this->tot_slot = count($this->frm_start_end_slot_arr);
    //      $this->loop = 0; //Just for addressing notices
    //      if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) { //See for loop below
    //          $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
    //          return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //      }
    //      $args = [
    //          'doctor_id' => $this->dr_sess_id,
    //          'appointment_date' => $dt,
    //          'booked'=>'0'
    //      ];
    //      $this->db_selected_slots = $this->doctorModel->getDrFreeSlots('doctor_slots', $args);
    //      if($this->db_selected_slots) {
    //      $this->free_slot_id_arr = array();
    //      foreach ($this->db_selected_slots as $this->db_slots) {
    //          $id = $this->db_slots->id;
    //          $this->dr_sess_id = $this->db_slots->doctor_id;
    //          $appointment_date = $this->db_slots->appointment_date;
    //          $slot_start = $this->db_slots->slot_start;
    //          $this->slice_db_slot_start = substr($slot_start, 0, -3);
    //          $slot_end = $this->db_slots->slot_end;
    //          $this->slice_db_slot_end = substr($slot_end, 0, -3);
    //          $this->db_start_end_slot = $this->slice_db_slot_start.'--'.$this->slice_db_slot_end;
    //          if(in_array($this->db_start_end_slot, $this->frm_start_end_slot_arr)){
    //           //??????????
    //          } 
    //          else { $this->free_slot_id_arr[] = $id; }
    //      }
    //      if(count($this->free_slot_id_arr)>0){
    //          $this->delSlotRslt=$this->doctorModel->deleteDrSlotsData('doctor_slots', $this->free_slot_id_arr);
    //      }
    //  } //else - loop Closed
    //  $this->tot_slots = count($this->frm_start_end_slot_arr);
    //  if( $this->tot_slots > 0) {
    //      $this->loop = 0;
    //      for ($s = 0; $s < $this->tot_slot; $s++) {
    //          $this->splt_slot_arr = explode('--', $this->frm_start_end_slot_arr[$s]);
    //          $checkstslot = $this->start_slot = $this->splt_slot_arr[0]; //start slot
    //          $checkendslot = $this->end_slot = $this->splt_slot_arr[1]; //end slot
    //          $where = [
    //              'doctor_id'         => $this->dr_sess_id,
    //              'appointment_date' => $dt,
    //              'slot_start'    =>  $checkstslot.':00',
    //              'slot_end'      =>  $checkendslot.':00'
    //          ];
    //          $checkalready = $this->doctorModel->checkalreadyDrSlotsData('doctor_slots', $where);
    //          if(!$checkalready){
    //              $this->neo_sots_arr = array(
    //                  'doctor_id' => $this->dr_sess_id,
    //                  'doctor_name' => $doctor_name,
    //                  'appointment_date' => $this->new_slots_arr['appointment_date'],
    //                  'slot_start' => $this->start_slot,
    //                  'slot_end' => $this->end_slot,
    //                  'created_at' => date("Y-m-d H:i:s"),
    //                  'created_by' => $this->dr_sess_id
    //              );
    //              if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
    //                  $this->loop += 1;
    //              }
    //          }
    //      } //for loop - Closed
    //      if($this->loop === $this->tot_slots) {
    //          $result = array(
    //              "status" => true,
    //              'error'  => false, //error: `false` whereever status is true with SQL err 
    //              "code" => 200,
    //              "message" => 'Slots saved successfully'
    //          );
    //          return json_encode($result);
    //      }
    //      else {
    //          $result = array(
    //              "status" => false,
    //              'error'  => true, //error: `false` whereever status is true with SQL err 
    //              "code" => 200,
    //              "message" => 'Failed to save slots'
    //          );
    //          return json_encode($result);
    //      }
    //  }
    //  $result = array(
    //      "status" => false,
    //      'error'  => true, //error: `false` whereever status is true with SQL err 
    //      "code" => 200,
    //      "message" => 'Please select slots first'
    //  );
    //  return json_encode($result);
    //  } //else - Closed
    // } //Funciton - Closed


    public function save_slots($slt_date) { //$new_slt_arr, $old_slt_arr
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        
        $this->delSlotRslt = false; //For addressing notices
        $this->dr_sess_id = session()->get('doctor_session_id');
        $doctor_name = session()->get('doctor_session_name');

        $this->selected_slot = $this->request->getJSON(); //
        
        $this->selected_date = $slt_date;
        if (!isset($this->selected_date) || $this->selected_date == '') {
            $this->selected_date = date('Y-m-d');
        }
        $this->new_slots_arr = array(
            'appointment_date' => $this->selected_date,
            //date("Y-m-d"),    //Need to get from availabitlity slot page
            'selected_slot' => $this->selected_slot, //Expected from form  
        );

        $this->frm_start_end_slot_arr = $this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot']; //Start_end_slot
        $this->tot_slot = count($this->frm_start_end_slot_arr);
        $this->loop = 0; //Just for addressing notices
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) { //See for loop below
            $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'doctor_id' => $this->dr_sess_id,
            'appointment_date' => $slt_date,
            'booked'=>'0'
        ];
        //Get Dr. free slots - if existing any
        $this->db_selected_slots = $this->doctorModel->getDrFreeSlots('doctor_slots', $args); 

        if($this->db_selected_slots) {
            $this->free_slot_id_arr = array();
            foreach ($this->db_selected_slots as $this->db_slots) {
                $id = $this->db_slots->id;
                $this->dr_sess_id = $this->db_slots->doctor_id;
                $appointment_date = $this->db_slots->appointment_date;
                
                //$this->slice_db_slot_start = substr($this->db_slots->slot_start, 0, -3);
                //$this->slice_db_slot_end = substr($this->db_slots->slot_end, 0, -3);
                //$this->db_start_end_slot = $this->slice_db_slot_start.'--'.$this->slice_db_slot_end;

                $this->db_start_end_slot = $this->db_slots->start_end_slot;
                if(!in_array($this->db_start_end_slot, $this->frm_start_end_slot_arr)) { $this->free_slot_id_arr[] = $id; }
            }
            if(count($this->free_slot_id_arr) > 0) { //Del Dr. free slots
                $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $this->free_slot_id_arr);
            }
            /*if($this->delSlotRslt) {//NO LONGER REQUIRED, BECOZ IT STOP FURTHER EXECUSTION
                $result = array(
                    "status" => true,
                    'error'  => false,
                    "code" => 200,
                    "message" => 'Slots Freed successfully'
                );
                return json_encode($result);
            }
            else {
                $result = array(
                    "status" => false,
                    'error'  => true,
                    "code" => 200,
                    "message" => 'Failed to delete free slots'
                );
                return json_encode($result);
            }*/
        }

        $this->tot_slots = count($this->frm_start_end_slot_arr);
        if( $this->tot_slots > 0) {
            $this->loop = 0;
            for ($s = 0; $s < $this->tot_slot; $s++) {
                $this->splt_slot_arr = explode('--', $this->frm_start_end_slot_arr[$s]);
                $checkstslot = $this->start_slot = $this->splt_slot_arr[0]; //start slot
                $checkendslot = $this->end_slot = $this->splt_slot_arr[1]; //end slot
                $where = [
                    'doctor_id'     => $this->dr_sess_id,
                    'appointment_date' => $slt_date,
                    //'slot_start'  =>  $checkstslot.':00',
                    //'slot_end'        =>  $checkendslot.':00'
                    'start_end_slot'    => $this->frm_start_end_slot_arr[$s],
                ];
                $checkalready = $this->doctorModel->checkalreadyDrSlotsData('doctor_slots', $where);
                if(!$checkalready) {
                    $this->neo_sots_arr = array(
                        'doctor_id'     => $this->dr_sess_id,
                        'doctor_name'   => $doctor_name,
                        'appointment_date' => $this->new_slots_arr['appointment_date'],
                        //'slot_start'  => $this->start_slot,
                        //'slot_end'        => $this->end_slot,
                        'start_end_slot'    => $this->frm_start_end_slot_arr[$s],
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $this->dr_sess_id
                    );
                    if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
                        $this->loop += 1;
                    }
                }
            } //for loop - Closed
            if($this->loop === $this->tot_slots) {
                $result = array(
                    "status" => true,
                    'error'  => false, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    "message" => 'Slots saved successfully'
                );
                return json_encode($result);
            }
            else { 
                $result = array(
                    "status" => false,
                    'error'  => true, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    "message" => 'Failed to save slots'
                );
                return json_encode($result);
            }
            if($this->loop > 0 ) {
                $result = array(
                    "status" => true,
                    'error'  => false,
                    "code" => 200,
                    "message" => 'Slots saved successfully'
                );
                return json_encode($result);
            }
            else {
                    $result = array(
                    "status" => false,
                    'error'  => true, //error: `false` whereever status is true with SQL err 
                    "code" => 200,
                    "message" => 'Failed to save slots'
                );
                return json_encode($result);
            }
        }
        $result = array(
            "status" => false,
            'error'  => true, //error: `false` whereever status is true with SQL err 
            "code" => 200,
            "message" => 'Please select slots first'
        );
        return json_encode($result);
    } //Funciton - Closed


   /* @param: On Selection Slot: Save Dr. Availablity Slot
    * @description:  
    * @use:
    * @date: February 13th, 2025
    * @modify:
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    */

    // public function SaveSlotAjaxNeo($slt_date) { 
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
        
    //  $this->delSlotRslt = false; //For addressing notices
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  $this->dr_sess_uid = session()->get('doctor_session_uid');
    //  $doctor_name = session()->get('doctor_session_name');
    //  if (!isset($this->dr_sess_id) || !isset($this->dr_sess_uid)) {
    //      $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID',3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }

    //  $this->selected_slot = $this->request->getJSON(); //request or form slots data
    //  $this->selected_date = $slt_date; 
    //  if (!isset($this->selected_date) || $this->selected_date == '') {
    //      $this->selected_date = date('Y-m-d'); //current date
    //  }
        
    //  $this->neo_sots_arr = array(
    //      'doctor_id'     => $this->dr_sess_id,
    //      //'doctor_name'     => $doctor_name,
    //      'appointment_date' => $this->selected_date,
    //      'start_end_slot'    => $this->selected_slot,
    //      'created_at' => date("Y-m-d H:i:s"),
    //      'created_by' => $this->dr_sess_uid
    //  );
    //  if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
    //      $result = array(
    //          "status" => true,
    //          'error'  => false,
    //          "code" => 200,
    //          "message" => 'Slots saved successfully'
    //      );
    //      return json_encode($result);
    //  }
    //  else {
    //          $result = array(
    //          "status" => false,
    //          'error'  => true, //error: `false` whereever status is true with SQL err 
    //          "code" => 200,
    //          "message" => 'Failed to save slots'
    //      );
    //      return json_encode($result);
    //  }
    // } //Funciton - Closed


    /* @param: On Selection Slot: Save Dr. Availablity Slot
    * @description:  
    * @use:
    * @date: February 13th, 2025
    * @modify: February 25th, 2025
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    */
    public function SaveSlotAjaxNeo() { 
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        
        $this->delSlotRslt = false; //For addressing notices
        $this->dr_sess_id = session()->get('doctor_session_id');
        $this->dr_sess_uid = session()->get('doctor_session_uid');
        $doctor_name = session()->get('doctor_session_name');
        if (!isset($this->dr_sess_id) || !isset($this->dr_sess_uid)) {
            $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID',3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }

        //$this->selected_slot = $this->request->getJSON(); //request or form slots data
        //$this->selected_date = $slt_date; 
        $this->slot_data = $this->request->getJSON(); //request or form slots data
        $this->selected_slot = $this->slot_data->selected_slot;
        $this->apmt_date = $this->slot_data->apmt_date;
        $this->slot_order = $this->slot_data->slot_order;
        if (!isset($this->apmt_date) || $this->apmt_date == '') {
            $this->apmt_date = date('Y-m-d'); //current date
        }
        
        $this->neo_sots_arr = array(
            'doctor_id'     => $this->dr_sess_id,
            //'doctor_name'     => $doctor_name,
            'appointment_date' => $this->apmt_date,
            'start_end_slot'    => $this->selected_slot,
            'slot_order'    => $this->slot_order,
            'created_at' => date("Y-m-d H:i:s"),
            'created_by' => $this->dr_sess_uid
        );
        if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
            $result = array(
                "status" => true,
                'error'  => false,
                "code" => 200,
                "message" => 'Slots saved successfully'
            );
            return json_encode($result);
        }
        else {
                $result = array(
                "status" => false,
                'error'  => true, //error: `false` whereever status is true with SQL err 
                "code" => 200,
                "message" => 'Failed to save slots'
            );
            return json_encode($result);
        }
    } //Funciton - Closed




   /* @param: On Selection Slot: Save Dr. Availablity Slot
    * @description:  
    * @use:
    * @date: February 8th, 2025
    * @modify:
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    */
    // public function SaveSlotAjax($slt_date) { 
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
        
    //  $this->delSlotRslt = false; //For addressing notices
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  $doctor_name = session()->get('doctor_session_name');

    //  $this->selected_slot = $this->request->getJSON(); //
        
    //  $this->apmt_date = $slt_date;
    //  if (!isset($this->selected_date) || $this->selected_date == '') {
    //      $this->selected_date = date('Y-m-d');
    //  }
    //  $this->new_slots_arr = array(
    //      'appointment_date' => $this->selected_date,
    //      //date("Y-m-d"),    //Need to get from availabitlity slot page
    //      'selected_slot' => $this->selected_slot, //Expected from form  
    //  );

    //  $this->frm_start_end_slot_arr = $this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot']; 
    //  $this->tot_slot = count($this->frm_start_end_slot_arr);
    //  $this->loop = 0; //Just for addressing notices
    //  if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) { //See for loop below
    //      $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }
    //  //echo "<pre>";print_r($this->frm_start_end_slot_arr);die;
    //  $this->tot_slots = count($this->frm_start_end_slot_arr);
    //  if( $this->tot_slots > 0) {
    //      $this->loop = 0;
    //      for ($s = 0; $s < $this->tot_slot; $s++) {
    //          $this->splt_slot_arr = explode('--', $this->frm_start_end_slot_arr[$s]);
    //          $checkstslot = $this->start_slot = $this->splt_slot_arr[0]; //start slot
    //          $checkendslot = $this->end_slot = $this->splt_slot_arr[1]; //end slot
    //          $where = [
    //              'doctor_id'     => $this->dr_sess_id,
    //              'appointment_date' => $slt_date,
    //              'slot_start'    =>  $checkstslot.':00',
    //              'slot_end'      =>  $checkendslot.':00'
    //          ];
    //          $checkalready = $this->doctorModel->checkalreadyDrSlotsData('doctor_slots', $where);
    //          if(!$checkalready) {
    //              $this->neo_sots_arr = array(
    //                  'doctor_id'     => $this->dr_sess_id,
    //                  'doctor_name'   => $doctor_name,
    //                  'appointment_date' => $this->new_slots_arr['appointment_date'],
    //                  'slot_start'    => $this->start_slot,
    //                  'slot_end'      => $this->end_slot,
    //                  'created_at' => date("Y-m-d H:i:s"),
    //                  'created_by' => $this->dr_sess_id
    //              );
    //              if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
    //                  $this->loop += 1;
    //              }
    //          }
    //      } //for loop - Closed
    //      if($this->loop > 0 ) {
    //          $result = array(
    //              "status" => true,
    //              'error'  => false,
    //              "code" => 200,
    //              "message" => 'Slots saved successfully'
    //          );
    //          return json_encode($result);
    //      }
    //      else {
    //              $result = array(
    //              "status" => false,
    //              'error'  => true, //error: `false` whereever status is true with SQL err 
    //              "code" => 200,
    //              "message" => 'Failed to save slots'
    //          );
    //          return json_encode($result);
    //      }
    //  }
    //  else { 
    //      $result = array(
    //          "status" => false,
    //          'error'  => true, //error: `false` whereever status is true with SQL err 
    //          "code" => 200,
    //          "message" => 'Please select slots first'
    //      );
    //      return json_encode($result);
    //  }
    // } //Funciton - Closed


    /* @param: On De-Selection Slot: Delete already set Dr. Availablity
    * @description:  
    * @use:
    * @date: February 13th, 2025
    * @modify:
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    */
    public function DelSlotAjaxNeo() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->dr_sess_id = session()->get('doctor_session_id');
        $this->dr_sess_uid = session()->get('doctor_session_uid');
        if (!isset($this->dr_sess_id) || !isset($this->dr_sess_uid)) {
            $this->session->setTempdata('error','Missing doctor session ID or UID', 3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }

        $this->delSlotRslt = false; //For addressing notices
        $doctor_name = session()->get('doctor_session_name');

        $this->form_req_data_arr = $this->request->getJSON();
        $this->apmt_date     = $this->form_req_data_arr->apmt_date;
        $this->selected_slot     = $this->form_req_data_arr->selected_slot;
        
        if (!isset($this->apmt_date) || $this->apmt_date == '') {
            $this->apmt_date = date('Y-m-d');
        }
        
        $args = [
            'doctor_id' => $this->dr_sess_id,
            'appointment_date' => $this->apmt_date,
            'start_end_slot'    => $this->selected_slot,
            'booked'    =>  '0'
        ];
        $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $args);
        if($this->delSlotRslt) {
            $result = array(
                "status" => true,
                'error'  => false,
                "code" => 200,
                "message" => 'Slots deleted successfully'
            );
            return json_encode($result);
        }
        else {
            $result = array(
                "status" => false,
                'error'  => true,
                "code" => 200,
                "message" => 'Failed to delete free slots'
            );
            return json_encode($result);
        }
    } //Funciton - Closed


    // public function DelSlotAjaxNeo($slt_date) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) {
    //      $this->session->setTempdata('error','Unexpected format or failed to get doctor session ID', 3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }

    //  $this->delSlotRslt = false; //For addressing notices
    //  $this->db_start_end_slot_arr = [];
    //  $doctor_name = session()->get('doctor_session_name');

    //  $this->form_req_data_arr = $this->request->getJSON();
    //  $this->selected_date     = $this->form_req_data_arr->date;
    //  $this->deselected_slot   = $this->form_req_data_arr->deselected_slot;
        
    //  if (!isset($this->selected_date) || $this->selected_date == '') {
    //      $this->selected_date = date('Y-m-d');
    //  }
    //  $this->new_slots_arr = array(
    //      'appointment_date' => $this->selected_date,
    //      'selected_slot' => $this->form_req_data_arr, //Expected from form  
    //  );

    //  $this->frm_start_end_slot_arr = $this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot'];

        
    //  $this->exploded_deselected_slot = explode('--', $this->deselected_slot);
    //  if(!isset($this->exploded_deselected_slot[0]) || (!isset($this->exploded_deselected_slot[0]))) {
    //      $result = array(
    //          "status" => false,
    //          'error'  => true,
    //          "code" => 200,
    //          "message" => 'Missing start slot or end slot'
    //      );
    //      return json_encode($result);
    //  }
        
    //  $args = [
    //      'doctor_id' => $this->dr_sess_id,
    //      'appointment_date' => $this->selected_date,
    //      'slot_start'=>  $this->exploded_deselected_slot[0].':00', 
    //      'slot_end'  =>  $this->exploded_deselected_slot[1].':00',
    //      'start_end_slot'    => 
    //      'booked'    =>  '0'
    //  ];
    //  //print_r($args); exit;
    //  $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $args);
    //  if($this->delSlotRslt) {
    //      $result = array(
    //          "status" => true,
    //          'error'  => false,
    //          "code" => 200,
    //          "message" => 'Slots deleted successfully'
    //      );
    //      return json_encode($result);
    //  }
    //  else {
    //      $result = array(
    //          "status" => false,
    //          'error'  => true,
    //          "code" => 200,
    //          "message" => 'Failed to delete free slots'
    //      );
    //      return json_encode($result);
    //  }
    // } //Funciton - Closed

    // public function DelSlotAjaxNeo($slt_date) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  $this->dr_sess_uid = session()->get('doctor_session_uid');
    //  if (!isset($this->dr_sess_id) || !isset($this->dr_sess_uid)) {
    //      $this->session->setTempdata('error','Missing doctor session ID or UID', 3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }

    //  $this->delSlotRslt = false; //For addressing notices
    //  $doctor_name = session()->get('doctor_session_name');

    //  $this->form_req_data_arr = $this->request->getJSON();
    //  $this->selected_date     = $this->form_req_data_arr->date;
    //  $this->deselected_slot   = $this->form_req_data_arr->deselected_slot;
        
    //  if (!isset($this->selected_date) || $this->selected_date == '') {
    //      $this->selected_date = date('Y-m-d');
    //  }
        
    //  $args = [
    //      'doctor_id' => $this->dr_sess_id,
    //      'appointment_date' => $this->selected_date,
    //      'start_end_slot'    => $this->deselected_slot,
    //      'booked'    =>  '0'
    //  ];
    //  $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $args);
    //  if($this->delSlotRslt) {
    //      $result = array(
    //          "status" => true,
    //          'error'  => false,
    //          "code" => 200,
    //          "message" => 'Slots deleted successfully'
    //      );
    //      return json_encode($result);
    //  }
    //  else {
    //      $result = array(
    //          "status" => false,
    //          'error'  => true,
    //          "code" => 200,
    //          "message" => 'Failed to delete free slots'
    //      );
    //      return json_encode($result);
    //  }
    // } //Funciton - Closed



   /* @param: On De-Selection Slot: Delete already set Dr. Availablity
    * @description:  
    * @use:
    * @date: February 8th, 2025
    * @modify:
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    */

    // public function DelSlotAjax($slt_date) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) {
    //      $this->session->setTempdata('error','Unexpected format or failed to get doctor session ID', 3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }

    //  $this->delSlotRslt = false; //For addressing notices
    //  $this->db_start_end_slot_arr = [];
    //  $doctor_name = session()->get('doctor_session_name');

    //  $this->form_req_data_arr = $this->request->getJSON();
    //  $this->selected_date     = $this->form_req_data_arr->date;
    //  $this->deselected_slot   = $this->form_req_data_arr->deselected_slot;
        
    //  if (!isset($this->selected_date) || $this->selected_date == '') {
    //      $this->selected_date = date('Y-m-d');
    //  }
    //  $this->new_slots_arr = array(
    //      'appointment_date' => $this->selected_date,
    //      'selected_slot' => $this->form_req_data_arr, //Expected from form  
    //  );

    //  $this->frm_start_end_slot_arr = $this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot'];

        
    //  $this->exploded_deselected_slot = explode('--', $this->deselected_slot);
    //  if(!isset($this->exploded_deselected_slot[0]) || (!isset($this->exploded_deselected_slot[0]))) {
    //      $result = array(
    //          "status" => false,
    //          'error'  => true,
    //          "code" => 200,
    //          "message" => 'Missing start slot or end slot'
    //      );
    //      return json_encode($result);
    //  }
        
    //  $args = [
    //      'doctor_id' => $this->dr_sess_id,
    //      'appointment_date' => $this->selected_date,
    //      'slot_start'=>  $this->exploded_deselected_slot[0].':00', 
    //      'slot_end'  =>  $this->exploded_deselected_slot[1].':00',
    //      'booked'    =>  '0'
    //  ];

    //  $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $args);
    //  if($this->delSlotRslt) {
    //      $result = array(
    //          "status" => true,
    //          'error'  => false,
    //          "code" => 200,
    //          "message" => 'Slots deleted successfully'
    //      );
    //      return json_encode($result);
    //  }
    //  else {
    //      $result = array(
    //          "status" => false,
    //          'error'  => true,
    //          "code" => 200,
    //          "message" => 'Failed to delete free slots'
    //      );
    //      return json_encode($result);
    //  }
    // } //Funciton - Closed



    // public function DelSlotAjax_v1($on_date) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  } 
        
    //  $this->delSlotRslt = false; //For addressing notices
    //  $this->free_slot_id_arr = []; //For addressing notices
    //  $this->dr_sess_id = session()->get('doctor_session_id');
    //  $doctor_name = session()->get('doctor_session_name');

    //  $this->del_slot_data = $this->request->getJSON();
    //  $this->slot_on_date = $on_date;
    //  if (!isset($this->slot_on_date) || $this->slot_on_date == '') {
    //      $this->slot_on_date = date('Y-m-d');
    //  }
    //  $this->new_slots_arr = array(
    //      'appointment_date' => $this->slot_on_date,
    //      'selected_slot' => $this->del_slot_data, //Expected from form  
    //  );

    //  $this->frm_start_end_slot_arr = $this->frm_start_end_slot_arr = $this->new_slots_arr['selected_slot']; 
    //  $this->tot_slot = count($this->frm_start_end_slot_arr);
    //  ////////////////////////
    //  if( $this->tot_slots > 0) {
    //      $this->loop = 0;
    //      for ($s = 0; $s < $this->tot_slot; $s++) {
    //          $this->splt_slot_arr = explode('--', $this->frm_start_end_slot_arr[$s]);
    //          $checkstslot = $this->start_slot = $this->splt_slot_arr[0]; //start slot
    //          $checkendslot = $this->end_slot = $this->splt_slot_arr[1]; //end slot
    //          $where = [
    //              'doctor_id'     => $this->dr_sess_id,
    //              'appointment_date' => $slt_date,
    //              'slot_start'    =>  $checkstslot.':00',
    //              'slot_end'      =>  $checkendslot.':00'
    //          ];
    //          $checkalready = $this->doctorModel->checkalreadyDrSlotsData('doctor_slots', $where);
    //          if(!$checkalready) {
    //              $this->neo_sots_arr = array(
    //                  'doctor_id'     => $this->dr_sess_id,
    //                  'doctor_name'   => $doctor_name,
    //                  'appointment_date' => $this->new_slots_arr['appointment_date'],
    //                  'slot_start'    => $this->start_slot,
    //                  'slot_end'      => $this->end_slot,
    //                  'created_at' => date("Y-m-d H:i:s"),
    //                  'created_by' => $this->dr_sess_id
    //              );
    //              if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
    //                  $this->loop += 1;
    //              }
    //          }
    //      } //for loop - Closed
    //      if($this->loop > 0 ) {
    //          $result = array(
    //              "status" => true,
    //              'error'  => false,
    //              "code" => 200,
    //              "message" => 'Slots saved successfully'
    //          );
    //          return json_encode($result);
    //      }
    //      else {
    //              $result = array(
    //              "status" => false,
    //              'error'  => true, //error: `false` whereever status is true with SQL err 
    //              "code" => 200,
    //              "message" => 'Failed to save slots'
    //          );
    //          return json_encode($result);
    //      }
    //  }
    //  ////////////////////////
    //  $this->loop = 0; //Just for addressing notices
    //  if (!isset($this->dr_sess_id) || $this->dr_sess_id == '' || $this->dr_sess_id == 0) {
    //      $this->session->setTempdata('error', 'Unexpected format or failed to get doctor session ID', 3);
    //      return redirect()->to(base_url() . "Doctor_login/doctor_login");
    //  }
    //  $args = [
    //      'doctor_id' => $this->dr_sess_id,
    //      'appointment_date' => $on_date,
    //      'booked'=>'0'
    //  ];
    //  //Get Dr. free slots - if existing any
    //  $this->db_selected_slots = $this->doctorModel->getDrFreeSlots('doctor_slots', $args); 
    //  if($this->db_selected_slots) {
    //      foreach ($this->db_selected_slots as $this->db_slots) {
    //          $this->free_slot_id_arr[] = $this->db_slots->id;
    //      }
    //      if(count($this->free_slot_id_arr) > 0) { //Del Dr. free slots
    //          $this->delSlotRslt = $this->doctorModel->deleteDrSlotsData('doctor_slots', $this->free_slot_id_arr);
    //          if($this->delSlotRslt) {
    //              $result = array(
    //                  "status" => true,
    //                  'error'  => false,
    //                  "code" => 200,
    //                  "message" => 'Slots deleted successfully'
    //              );
    //              return json_encode($result);
    //          }
    //          else {
    //              $result = array(
    //                  "status" => false,
    //                  'error'  => true, //error: `false` whereever status is true with SQL err 
    //                  "code" => 200,
    //                  "message" => 'Failed to delete slot'
    //              );
    //              return json_encode($result);
    //          }
    //      }
    //      else {
    //          $result = array(
    //              "status" => false,
    //              'error'  => true, //error: `false` whereever status is true with SQL err 
    //              "code" => 200,
    //              "message" => 'Deselect at least one slot to delete'
    //          );
    //          return json_encode($result);
    //      }
    //  }
    //  //$this->tot_slots = count($this->frm_start_end_slot_arr);
    //  //var_dump($this->frm_start_end_slot_arr);die;
    //  // if( $this->tot_slots > 0) {
    //  //  $this->loop = 0;
    //  //  for ($s = 0; $s < $this->tot_slot; $s++) {
    //  //      $this->splt_slot_arr = explode('--', $this->frm_start_end_slot_arr[$s]);
    //  //      $checkstslot = $this->start_slot = $this->splt_slot_arr[0]; //start slot
    //  //      $checkendslot = $this->end_slot = $this->splt_slot_arr[1]; //end slot
    //  //      $where = [
    //  //          'doctor_id'     => $this->dr_sess_id,
    //  //          'appointment_date' => $on_date,
    //  //          'slot_start'    =>  $checkstslot.':00',
    //  //          'slot_end'      =>  $checkendslot.':00'
    //  //      ];
    //  //      $checkalready = $this->doctorModel->checkalreadyDrSlotsData('doctor_slots', $where);
    //  //      if(!$checkalready) {
    //  //          $this->neo_sots_arr = array(
    //  //              'doctor_id'     => $this->dr_sess_id,
    //  //              'doctor_name'   => $doctor_name,
    //  //              'appointment_date' => $this->new_slots_arr['appointment_date'],
    //  //              'slot_start'    => $this->start_slot,
    //  //              'slot_end'      => $this->end_slot,
    //  //              'created_at' => date("Y-m-d H:i:s"),
    //  //              'created_by' => $this->dr_sess_id
    //  //          );
    //  //          if ($this->doctorModel->Insertdata('doctor_slots', $this->neo_sots_arr)) {
    //  //              $this->loop += 1;
    //  //          }
    //  //      }
    //  //  } //for loop - Closed
            
    //  //  if($this->loop > 0 ) {
    //  //      $result = array(
    //  //          "status" => true,
    //  //          'error'  => false,
    //  //          "code" => 200,
    //  //          "message" => 'Slots deleted successfully'
    //  //      );
    //  //      return json_encode($result);
    //  //  }
    //  //  else {
    //  //          $result = array(
    //  //          "status" => false,
    //  //          'error'  => true, //error: `false` whereever status is true with SQL err 
    //  //          "code" => 200,
    //  //          "message" => 'Failed to delete slot'
    //  //      );
    //  //      return json_encode($result);
    //  //  }
    //  // }
    //  // else {
    //  //  $result = array(
    //  //      "status" => false,
    //  //      'error'  => true, //error: `false` whereever status is true with SQL err 
    //  //      "code" => 200,
    //  //      "message" => 'Please de-select slots first'
    //  //  );
    //  //  return json_encode($result);
    //  // }
    // } //Funciton - Closed


    /* @param: Check is, json string or not?
     * @description:  
     * @use:
     * @date: 
     * @modify:
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
    public function isJSON($str){ //Return true for JSON else return `false`
        return is_string($str) && is_array(json_decode($str, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }//Function- Closed

    //    /* @params: Require, "Slot", "Doctor ID", "Doctor Name" (optional) and patient desired appointment "Date" 
    //  * @desc: Pick the Doctor availabile slot for getting doctor appointment by the Patient
    //  * @use: By patient for booking Doctor available slots
    //  * @author: Neoarks Team
    //  * @date: June 15th,  2023
    //  * @modify:
    //  */ 
    public function total_discharge_patient() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Doctor ID is missing !', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Discharged'
            ];

            $data = [
                // 'all_patients' => $this->doctorModel->fetch_rec_by_args_with_status('patients', $args),
                'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
                'all_patients' => $this->doctorModel->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->doctorModel->pager
            ];
            return view('Doctor/total_discharge_patient', $data);
        }
    }//Function- Closed

    /* @params: 
     * @desc: 
     * @use
     * @author: Neoarks Team
     * @date: 
     * @modify:
     */
    public function today_discharge_patient(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $data['loggedin_usr'] = $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users');
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Doctor ID is missing !', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            $args = [
                'is_del'=>0,
                'doctor_id'          => $this->dr_sess_id,
                'status'             => 'Discharged',
                'registration_date'  => date('Y-m-d')
            ];
            $likeArgs = ['updated_at' => date('Y-m-d')];
            $data = [
                // 'today_discharge_patients' => $this->patient_model->fetch_rec_by_args_like('patients', $args, $likeArgs),
                 'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
                'today_discharge_patients' => $this->doctorModel->fetch_rec_by_args_like('patients', $args, $likeArgs),
                'pager' => $this->doctorModel->pager
            ];
            //echo "<pre>"; print_r($data);die;
            return view('Doctor/today_discharge_patient', $data);
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function request_activate_email(){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        
        $data = [];
        $data['validation'] = null;
        if ($this->request->getMethod() == 'post') {
            $rules = [
                // 'doctor_name' => 'required|min_length[4]|max_length[20]',
                'doctor_name'  => [
                    'rules'     => 'required|min_length[4]|max_length[20]',
                    'errors'    => [
                    'required' => 'Doctor name is  mandatory.',
                    'min_length' => 'Minimum length is 4.',
                    'max_length' => 'Maximum length is 20.'
                    ],
                ],
                'doctor_phone' => 'required|numeric|exact_length[10]',
                'doctor_email' => 'required|valid_email|is_unique[doc_req_email.doctor_email]'
            ];
            if ($this->validate($rules)) {
                $data = [
                    'doctor_name' => $this->request->getVar('doctor_name', FILTER_SANITIZE_STRING),
                    'doctor_phone' => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
                    'doctor_email' => $this->request->getVar('doctor_email', FILTER_SANITIZE_STRING),
                    'status' => 'Active',
                    'created_at' => date('Y-m-d h:i:s'),
                    'created_by' => $this->doctor_uid,
                ];

                $status = $this->doctorModel->Insertdata('doc_req_email', $data);
                if ($status) {
                    $this->session->setTempdata('success', 'Congratulation ! email verification sent successfully. Please wait for some time !', 3);
                } else {
                    $this->session->setTempdata('error', 'Failed ! Unable to sent !', 3);
                }
                return redirect()->to(base_url() . '/Doctor/request_activate_email', $data);
            } 
            else {
                $this->session->setTempdata('error', 'Validation failed!', 3);
                $data['validation'] = $this->validator;
            }
        }
        return view('Doctor/doctor_req_active', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function all_appointments(){
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            return view('Doctor/doctor_login');
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->dr_sess_id = session()->get('doctor_session_id');
        $args = [
            'is_del'=> 0,
            //'status'  => 'InActive',
            'doctor_id' => $this->dr_sess_id,
        ];
        $data = [
            'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
            'all_appointments' => $this->doctorModel->fetch_rec_by_args_arr('booked_doctor_appointment', $args),
            'pager' => $this->doctorModel->pager
        ];
        //echo "<pre>";print_r($data);die;
        return view('Doctor/all_appointments', $data);
    } //Function - Closed


    /* @params: Function for permanent delete all appointment
    * @desc: Admin can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
    * @use: Admin....
    * @return:
    * @author: Neoarks Team
    * @date: 16th August,2023
    * @modify
    */
    public function permanent_del_all_apmnt($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        }
        $args = [
            'id'  =>  $id,
        
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Deleted', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
    
    
        $status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
    
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
        }
        return redirect()->to(base_url() . 'Doctor/all_appointments');
    }

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function filter_today_appointments($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        $this->dr_sess_id = session()->get('doctor_session_id');

        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Doctor sessin ID is missing!', 3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'doctor_id' => $this->dr_sess_id,
            'is_del'=>0,
            //'status' => 1,
            //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
            'booking_date' => date('Y-m-d')
        ];
        $data = [
            'today_appointments' => $this->doctorModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
            'pager' => $this->doctorModel->pager
        ];
        
        return view('Doctor/today_appointments', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function filter_all_appointments($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        $this->dr_sess_id = session()->get('doctor_session_id');
        if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
            $this->session->setTempdata('error', 'Doctor sessin ID is missing!', 3);
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'doctor_id' => $this->dr_sess_id,
            'is_del'=>0
        ];
        $data = [
            'all_appointments' => $this->doctorModel->filter_rec_by_args_with_pagination('booked_doctor_appointment', $order, $args),
            'pager' => $this->doctorModel->pager
        ];
        
        return view('Doctor/all_appointments', $data);
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function change_today_appointments_status($id, $status, $pid, $puid, $serial){
        $this->apmt_id = (int) $id; //appointment ID
        $this->status = (int) $status; //Status
        $this->patient_id = (int) $pid; //Patient ID
        $this->puid = $puid; //Hospital assigned patient unique ID
        $this->appt_serial = (int) $serial; //Appointment serial 

        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        }

        $this->doctor_uid = session()->get('doctor_session_uid');

        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        } //else { $this->doctor_uid =  $this->doctor_uid_arr['uid']; }

        $this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table

        $this->data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->doctor_uid,
        ];

        if ($this->status === 4) { //Update appointments & insert patient info 
            /***** Get Patient detail thru `puid` - START *****/
            if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
                $this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->doctor_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                } else {
                    $this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                }
            }
            /***** Get Patient detail thru `puid` - END *****/
            else if ($this->patient_id > 0) {
                /*****  Get Patient detail thru `paitient ID` - START *****/
                //$this->args['id']  = $this->patient_id;
                $this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->doctor_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                } else {
                    $this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                }
            }
            /***** Get Patient detail thru `paitient ID` - END *****/
            else if ($this->apmt_id != '' && $this->apmt_id > 0) {
                /***** Get Patient detail thru `Appointment ID` - START *****/
                $this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->doctor_uid, $this->patient_id, $this->puid, $this->apmt_id, $this->appt_serial);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                } else {
                    $this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/today_appointments');
                }
            }
            /***** Get Patient detail thru `Appointment ID` - END *****/
            else {
                $this->session->setTempdata('error', 'Anonymous user, patient info is not found in the database', 3);
                return redirect()->to(base_url() . '/Doctor/today_appointments');
            }
        } else { //Update appointments only
            // $status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
            $status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
            if ($status === true) {
                $this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
                return redirect()->to(base_url() . '/Doctor/today_appointments');
            } else {
                $this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
                return redirect()->to(base_url() . '/Doctor/today_appointments');
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
    public function permanent_del_today_apmnt($id) {
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        }
        $args = [
            'id'  =>  $id,
            // 'is_del' => 1
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Deleted', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
    
    
        $status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment',  $args, $data);
    
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Department Deleted Successfully !', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to Deleted Try Again ?', 3);
        }
        return redirect()->to(base_url() . 'Doctor/all_appointments');
    }


    //*******************Attend Patient - START ***********************
    //public function change_all_appointments_status($apmtid, $status, $pid, $puid, $serial) {
    public function change_all_appointments_status($apmtid, $status, $pid, $puid, $serial, $dr_id) {
        $this->apmt_id = (int) $apmtid; //appointment ID
        $this->status = (int) $status; //Status
        $this->pid = (int) $pid; //Patient ID
        $this->puid = $puid; //Hospital assigned patient unique ID
        $this->appt_serial = (int) $serial; //Appointment serial 
        $this->dr_id = $dr_id; //Not used from session becasue same function in frontdesk etc 
        
        if (!(session()->has('doctor_session_uid'))) { 
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        } 

        $this->args['id'] = $this->apmt_id; //for updating `booked_doctor_appointment` table
        $this->data = [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->doctor_uid,
            ];

        if ($this->status === 4) { //Update appointments & insert patient info 
            /***** Get Patient detail thru `puid` - START *****/
            if (isset($this->puid) && $this->puid != '' && $this->puid != 0) {
                //$this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
                $this->updt_rslt = $this->patient_details_with_puid($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                } 
                else {
                    $this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                }
            }
            /***** Get Patient detail thru `puid` - END *****/
            else if ($this->pid > 0) {//pid: patient_login tbl `id`
                /*****  Get Patient detail thru `paitient ID` - START *****/
                
                //$this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
                $this->updt_rslt = $this->patient_details_with_pid($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation !! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                } else {
                    $this->session->setTempdata('error', 'Sorry !! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                }
            }
            /***** Get Patient detail thru `paitient ID` - END *****/
            else if ($this->apmt_id != '' && $this->apmt_id > 0) {
                /***** Get Patient detail thru `Appointment ID` - START *****/
                //$this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial);
                $this->updt_rslt = $this->patient_details_with_apmt_id($this->data, $this->args, $this->doctor_uid, $this->pid, $this->puid, $this->apmt_id, $this->appt_serial, $this->dr_id);
                if ($this->updt_rslt === true) {
                    $this->session->setTempdata('success', 'Congratulation! Appointment status change successfully', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                } else {
                    $this->session->setTempdata('error', 'Sorry ! unable to update Appointment status', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                }
            }
            /***** Get Patient detail thru `Appointment ID` - END *****/
            else {
                $this->session->setTempdata('error', 'Unexpected or Anonymous user, patient info is not found in the database', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
            }
        } 
        else { //Update appointments only
            $status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
            //$status = $this->doctorModel->update_rec_by_args('booked_doctor_appointment', $this->args, $this->data);
            if ($status === true) {
                $this->session->setTempdata('success', 'Congratulation ! Appointment status change successfully', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
            } else {
                $this->session->setTempdata('error', 'Sorry ! Unable to change. Please try again', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
            }
        } //else loop - closed
    } //Funtion - Closed


    //function patient_details_with_puid($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial){
    function patient_details_with_puid($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial, $dr_id){
        if (!(session()->has('doctor_session_uid')) && !(session()->has('doctor_session_id'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');

        $this->updt_data_arr = $data;
        $this->args = $args;
        $this->doctor_uid = $doctor_uid;
        $this->patient_id = $pid;
        $this->puid = $puid;
        $this->apmt_id = $apmt; //Appointment ID
        $this->new_serial = $serial;
        $this->dr_id = $dr_id;

        $this->args_neo = ['puid' => $this->puid];
        //$this->patient_rslt = $this->doctorModel->fetch_rec_by_args('patients', $this->args_neo);
        $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('patients', $this->args_neo);
        if ($this->patient_rslt != false) {
            $this->updt_data_arr['pid'] = $this->patient_rslt['0']->pid;
            $this->updt_data_arr['ref_by'] = $this->patient_rslt['0']->ref_by;

            $this->insrt_data_arr = array(
                'puid' => $this->puid,
                'serial' => $this->new_serial,
                //$this->patient_rslt['0']->serial,
                'revisit_date' => date('Y-m-d'),
                'apmt_id'       => $this->apmt_id,
                'patient_name'  => $this->patient_rslt['0']->patient_name,
                'pid'           => (int) $this->patient_rslt['0']->pid,
                'patient_id'    => $this->patient_rslt['0']->id,
                'patient_phone' => $this->patient_rslt['0']->patient_phone,
                'patient_address' => $this->patient_rslt['0']->patient_address,
                'pin_zip_code'  => $this->patient_rslt['0']->patient_zip,
                'doctor_name'   => $this->patient_rslt['0']->doctor_name,
                'doctor_id'     => $this->dr_id,
                'doctor_fee'    => $this->patient_rslt['0']->doctor_fee,
                'entry_fee'     => $this->patient_rslt['0']->entry_fee,
                'disease_symptoms' => $this->patient_rslt['0']->patient_issue,
                'other_fee'     => $this->patient_rslt['0']->other_fee,
                'patient_room'  => $this->patient_rslt['0']->patient_room,
                'patient_email' => $this->patient_rslt['0']->patient_email,
                /*'next_action'     => $this->patient_rslt['0']->id,
                            'assigned_by'       => $this->patient_rslt['0']->id,
                            'assigned_to'       => $this->patient_rslt['0']->id, */
                'status' => 'Attended',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->doctor_uid, //need uid
                //'patient_image'       => $this->patient_rslt['0']->id
            );

            //return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
            return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
        }
        /*****  Get Patient detail thru `paitient ID` - START *****/
        else if ($this->patient_id > 0) {
            $this->args_neo = ['id' => $this->patient_id];
            // $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('patients', $this->args_neo);
            $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('patients', $this->args_neo);
            if ($this->patient_rslt != false) {
                $this->updt_data_arr['pid'] = $this->patient_rslt['0']->pid;
                $this->updt_data_arr['ref_by'] = $this->patient_rslt['0']->ref_by;
                $this->insrt_data_arr = array(
                    'puid' => $this->patient_rslt['0']->puid,
                    'patient_name' => $this->patient_rslt['0']->patient_name,
                    'pid' => (int) $this->patient_rslt['0']->pid,
                    'patient_id' => $this->patient_rslt['0']->id,
                    'serial' => $this->new_serial,
                    'revisit_date' => date('Y-m-d'),
                    //Current date
                    'patient_phone' => $this->patient_rslt['0']->patient_phone,
                    'patient_address' => $this->patient_rslt['0']->patient_address,
                    'pin_zip_code' => $this->patient_rslt['0']->patient_zip,
                    'doctor_name' => $this->patient_rslt['0']->doctor_name,
                    'doctor_id'     => $this->dr_id,
                    'doctor_fee' => $this->patient_rslt['0']->doctor_fee,
                    'entry_fee' => $this->patient_rslt['0']->entry_fee,
                    'disease_symptoms' => $this->patient_rslt['0']->patient_issue,
                    'other_fee' => $this->patient_rslt['0']->other_fee,
                    'patient_room' => $this->patient_rslt['0']->patient_room,
                    'patient_email' => $this->patient_rslt['0']->patient_email,
                    /*'next_action'     => $this->patient_rslt['0']->id,
                                      'assigned_by'     => $this->patient_rslt['0']->id,
                                      'assigned_to'     => $this->patient_rslt['0']->id, */
                    'status' => 'Attended',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->doctor_uid, //need uid
                    //'patient_image'   => $this->patient_rslt['0']->id
                );
                // return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
                return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
            } else if ($this->apmt_id != '' && $this->apmt_id > 0) {
                $this->args_neo = ['id' => $this->apmt_id];
                // $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
                $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
                if ($this->patient_rslt != false) { //Generate PUID
                    //Generate puid
                    $this->puid = 0; //Just for addressing notices
                    if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
                        $this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
                        return redirect()->to(base_url() . '/Doctor/all_appointments');
                    } else {
                        $this->puid = $this->generate_puid($this->new_serial);
                    }
                    $this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

                    $this->insrt_data_arr = array(
                        'puid' => $this->puid,
                        'serial' => $this->new_serial,
                        'registration_date' => date('Y-m-d'),
                        'patient_name' => $this->patient_rslt['0']->patient_name,
                        'pid' => (int) $this->patient_rslt['0']->pid,
                        'age' => $this->patient_rslt['0']->age,
                        'gender' => $this->patient_rslt['0']->gender,
                        'patient_phone' => $this->patient_rslt['0']->patient_mobile,
                        'patient_address' => $this->patient_rslt['0']->address,
                        'doctor_id' => $this->patient_rslt['0']->doctor_id,
                        'doctor_name' => $this->patient_rslt['0']->doctor_name,
                        'apmt_id' => $this->patient_rslt['0']->id,
                        'patient_issue' => $this->patient_rslt['0']->disease_symptoms,
                        'patient_room' => $this->patient_rslt['0']->department, //room??
                        'patient_email' => $this->patient_rslt['0']->patient_email,
                        'status' => 'Attended',
                        'level' => 3, //3: Doctor - MOI for admin level?
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->doctor_uid, //need uid
                    );
                    //  echo "2nd last";
                    // echo "<pre>";print_r($this->insrt_data_arr);die;
                    // return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr); 
                    return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
                } else {
                    $this->updt_rslt = 2; //Anonymous user, patient info is not found in the database. 
                    $this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
                }
            }
            /***** Get Patient detail thru `Appointment ID` - END *****/
            else {
                $this->session->setTempdata('error', 'Anonymous!, patient info is not found', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
            }
        }
        /***** Get Patient detail thru `paitient ID` - END *****/
        else {
            $this->session->setTempdata('error', 'Puid! must exist patients table', 3);
            return redirect()->to(base_url() . '/Doctor/all_appointments');
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    //function patient_details_with_pid($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial){
    function patient_details_with_pid($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial, $dr_id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->updt_data_arr = $data;
        $this->args = $args;
        $this->doctor_uid = $doctor_uid;
        $this->pid = $pid;
        $this->puid = $puid;
        $this->apmt_id = $apmt; //Appointment ID
        $this->new_serial = $serial;
        $this->dr_id = $dr_id;

        $this->args_neo = ['pid' => $this->pid];

        $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('patients', $this->args_neo);
        if ($this->patient_rslt != false) { //Patient exists into `patients` tbl
            if (isset($this->patient_rslt['0']->puid)) { $this->puid = $this->patient_rslt['0']->puid; }
            $this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

            $this->insrt_data_arr = array(
                'puid'              => $this->puid,
                'patient_name'      => $this->patient_rslt['0']->patient_name,
                'patient_id'        => $this->patient_rslt['0']->id,
                'serial'            => $this->new_serial,
                'revisit_date'      => date('Y-m-d'),
                'apmt_id'           => $this->apmt_id,
                'pid'               => (int)$this->patient_rslt['0']->pid,
                'ref_by'            => (int)$this->patient_rslt['0']->ref_by,
                'patient_phone'     => $this->patient_rslt['0']->patient_phone,
                'patient_address'   => $this->patient_rslt['0']->patient_address,
                'pin_zip_code'      => $this->patient_rslt['0']->patient_zip,
                'doctor_name'       => $this->patient_rslt['0']->doctor_name,
                'doctor_id'         => $dr_id,
                'doctor_fee'        => $this->patient_rslt['0']->doctor_fee,
                'entry_fee'         => $this->patient_rslt['0']->entry_fee,
                'disease_symptoms'  => $this->patient_rslt['0']->patient_issue,
                'other_fee'         => $this->patient_rslt['0']->other_fee,
                'patient_room'      => $this->patient_rslt['0']->patient_room,
                'patient_email'     => $this->patient_rslt['0']->patient_email,
                /*'next_action'     => $this->patient_rslt['0']->id,
                'assigned_by'       => $this->patient_rslt['0']->id,
                'assigned_to'       => $this->patient_rslt['0']->id, */
                'status'            => 'Attended',
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => $this->doctor_uid, //need uid
                //'patient_image'       => $this->patient_rslt['0']->id
            );
            return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'revisit_patients', $this->insrt_data_arr);
        }
        /* Get Patient detail thru `Appointment ID` - START 
         * @desc: Loggedin - NEW Patient appointment Addeded by Admin & Admin
         * 
         */ else if ($this->apmt_id != ''  && $this->apmt_id > 0) {
            $this->args_neo = ['id' => $this->apmt_id];
            $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->args_neo);
            if ($this->patient_rslt != false) { //Generate PUID
                //Generate puid
                $this->puid = 0; //Just for addressing notices
                if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
                    $this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
                    return redirect()->to(base_url() . '/Doctor/all_appointments');
                } 
                else { $this->puid = $this->generate_puid($this->new_serial); }
                $this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 

                $this->insrt_data_arr = array(
                    'puid'              => $this->puid,
                    'serial'            => $this->new_serial,
                    'registration_date' => date('Y-m-d'),
                    'patient_name'      => $this->patient_rslt['0']->patient_name,
                    'pid'               => (int)$this->patient_rslt['0']->pid,
                    'ref_by'            => (int)$this->patient_rslt['0']->ref_by,
                    'age'               => $this->patient_rslt['0']->age,
                    'gender'            => $this->patient_rslt['0']->gender,
                    'patient_phone'     => $this->patient_rslt['0']->patient_mobile,
                    'patient_address'   => $this->patient_rslt['0']->address,
                    //'patient_zip'     => $this->patient_rslt['0']->doctor_id,
                    'doctor_id'         => $this->patient_rslt['0']->doctor_id, //$this->dr_id
                    'doctor_name'       => $this->patient_rslt['0']->doctor_name,
                    'apmt_id'           => $this->patient_rslt['0']->id,
                    'patient_issue'     => $this->patient_rslt['0']->disease_symptoms,
                    'patient_room'      => $this->patient_rslt['0']->department, //room ?
                    'patient_email'     => $this->patient_rslt['0']->patient_email,
                    'status'            => 'Attended',
                    'level'             =>  3, //3: Doctor - MOI for admin level?
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => $this->doctor_uid, //need uid
                    //'deleted_at'      => 
                );
                //  echo "2nd last";
                // echo "<pre>";print_r($this->insrt_data_arr);die;
                return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
            } else {
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
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    //function patient_details_with_apmt_id($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial) {
    function patient_details_with_apmt_id($data, $args, $doctor_uid, $pid, $puid, $apmt, $serial, $dr_id) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $this->updt_data_arr = $data;
        $this->args = $args;
        $this->doctor_uid = $doctor_uid;
        $this->puid = $puid;
        $this->apmt_id = $apmt;
        $this->new_serial = $serial;
        $this->dr_id = $dr_id;

        // $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->args);
        $this->patient_rslt = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->args);
        if ($this->patient_rslt != false) {
            $this->puid = 0; //Just for addressing notices
            if ($this->new_serial == 0 || $this->new_serial == false || (!is_numeric($this->new_serial))) {
                $this->session->setTempdata('error', 'Unexpected serial format, expected integer format', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
            } 
            else { $this->puid = $this->generate_puid($this->new_serial); }
            $this->updt_data_arr['puid'] = $this->puid; //For updating puid in appoitment list 
            $this->updt_data_arr['patients_id'] = 0; //Because patient record is not in patients table yet 
            $this->insrt_data_arr = array(
                'puid'              => $this->puid,
                'serial'            => $this->new_serial,
                'registration_date' => date('Y-m-d'),
                'patient_name'      => $this->patient_rslt['0']->patient_name,
                'pid'               => (int)$this->patient_rslt['0']->pid,
                'ref_by'            => (int)$this->patient_rslt['0']->ref_by,
                'age'               => $this->patient_rslt['0']->age,
                'gender'            => $this->patient_rslt['0']->gender,
                'patient_phone'     => $this->patient_rslt['0']->patient_mobile,
                'patient_address'   => $this->patient_rslt['0']->address,
                'doctor_id'         => $this->patient_rslt['0']->doctor_id, //$this->dr_id,
                'doctor_name'       => $this->patient_rslt['0']->doctor_name,
                'apmt_id'           => $this->patient_rslt['0']->id,            
                'patient_issue'     => $this->patient_rslt['0']->disease_symptoms,          
                'patient_room'      => $this->patient_rslt['0']->department, //room ?
                'patient_email'     => $this->patient_rslt['0']->patient_email,
                'status'            => 'Attended',
                'level'             =>  3, //3: Doctor - MOI for admin level?
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => $this->doctor_uid,           
            );
            // return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr); 
            return $this->updt_rslt = $this->doctorModel->update_appintment_inst_patient_info('booked_doctor_appointment', $this->args, $this->updt_data_arr, 'patients', $this->insrt_data_arr);
        } 
        else {
            $this->updt_rslt = 2; //Anonymus user, Patient record is not found in db
            $this->err_chk_update_appintment_inst_patient_info($this->updt_rslt);
        }
    } //Function - Closed


    public function get_doctor_fee_details($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [ 'id'  => $id ];

        //$data = $this->doctorModel->fetch_rec_by_args('doctor_fee', $args);
        $data = $this->doctorModel->fetch_rec_by_args('doctor', $args);
        
        echo json_encode($data);
    }

    // public function print_slip($id){
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [ 'id'  => $id ];
    //  $data['patient_slip'] = $this->doctorModel->fetch_rec_by_args('patients_pay_charges', $args);
    //  return view('Doctor/print_slip', $data);
    // }
    public function print_slip($id , $pid){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        //echo "<pre>";print_r($id. '/' . $pid);die;
        $args = [
            'pid'  => $pid
        ];
        $data['patient_slip'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        //echo "<pre>";print_r($data['patient_slip']);die;
        return view('Doctor/print_slip', $data);
    }

    public function edit_today_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }$args = [
            'id'  => $id ];
        $data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        $data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
        return view('Doctor/edit_today_patients', $data);
    }

    public function edit_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }$args = [
            'id'  => $id ];
        $data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        $data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
        return view('Doctor/edit_patients', $data);
    }

    public function update_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
            'patient_email'          =>  $this->request->getVar('patient_email'),
            'status'                 => 'Active'
        ];
        $status = $this->adminModel->update_rec_by_args('patients', $args, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
        }
        //return redirect()->to(base_url().'/Doctor/edit_patients/'.$id);
        return redirect()->to(base_url() . 'Doctor/all_patients');
    }

    public function update_today_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
            'patient_email'          =>  $this->request->getVar('patient_email'),
            'status'                 => 'Active'
        ];
        $status = $this->adminModel->update_rec_by_args('patients', $args, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
        }
        //return redirect()->to(base_url().'/Doctor/edit_patients/'.$id);
        return redirect()->to(base_url() . 'Doctor/today_patients');
    }

    
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    function err_chk_update_appintment_inst_patient_info($err_type){
        $this->type_of_err = $err_type;

        switch ($this->type_of_err) {
            case 'false':
                $this->session->setTempdata('error', 'False response, failed to update appointment', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;
            case '':
                $this->session->setTempdata('error', 'Blank response, failed to udpate appointment', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;
            case 'true':
                $this->session->setTempdata('success', 'Congratulation ! Appointment Status Change Successfully !', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;

            case 2:
                $this->session->setTempdata('error', 'Anonymus user, Patient record is not found in db', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;

            case 3:
                $this->session->setTempdata('error', 'Revisited Patients info insertion failed', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;

            case 4:
                $this->session->setTempdata('error', 'Failed to book doctor appointment', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;

            default:
                $this->session->setTempdata('error', 'Sorry ! Unable to Change Try Again ?', 3);
                return redirect()->to(base_url() . '/Doctor/all_appointments');
                break;
        }
    } //function - Closed
    //*******************Attend Patient - END ***********************

    //*******************Attend Patient - END ***********************

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function add_newsblog(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
        }else{
            return view('Doctor/add_newsblog'); 
        }
    }


    public function upload_profile_pic() { 
        if (!(session()->has('doctor_session_uid')) && !(session()->has('doctor_session_id'))) {
            $this->result_arr = [
                    'status' => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code' => 200,
                    'data' => [],
                    'message' => 'Session has expired. Please relogin again.'
                ];
            //return json_encode($this->result_arr);
             return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid'); //`register_all_users` tbl uid
        $this->doctor_id = session()->get('doctor_session_id'); //`doctor` tbl dr. id
        $this->doctor_session_id = session()->get('doctor_session_id'); //`register_all_users` tbl dr. id
        
        $data = [];
        $data['validation'] = null;
        if ($this->request->getMethod() == 'post') {    
            $rules = [
                'uploaded_file' => [
                    'rules'     => 'uploaded[uploaded_file]|max_size[uploaded_file,' . ALLOW_MAX_UPLOAD .']|is_image[uploaded_file]|mime_in[uploaded_file,image/jpeg,image/png,image/gif,image/svg+xml]|ext_in[uploaded_file,png,jpg,jpeg,svg, gif]',
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
            // Validate file size (adjust the limit according to your needs)
        
            // Validate file format - End
            //if(!$this->profile_pic->hasMoved()) {
                $this->profile_pic = $this->request->getFile('uploaded_file');
                $this->file_name = $this->profile_pic->getName();
                if($this->file_name && $this->file_name != '') {
                    $args = [ 'id'  => $this->doctor_id ];

                $this->old_data = $this->doctorModel->fetch_rec_by_args('register_all_users', $args);   
    
                if(isset($this->old_data[0]->profile_pic)) {
                if(file_exists(FCPATH . 'uploads/doctor/' . $this->old_data[0]->profile_pic) && $this->old_data[0]->profile_pic != '') {
                        //delete old  image
                        @unlink(FCPATH . 'uploads/doctor/' . $this->old_data[0]->profile_pic);
                    } //else - Not needed
                } //else - Not needed
                
                $this->random_name = $this->profile_pic->getRandomName();
                if ($this->profile_pic->isValid() &&  !$this->profile_pic->hasMoved()) {
                    $this->profile_pic->move(FCPATH . 'uploads/doctor', $this->random_name);
                    $this->user_data_arr = [
                        'profile_pic'     =>  $this->random_name,//$this->file_name,
                        'updated_at'      =>  date('Y-m-d H:i:s'),
                        'updated_by'      =>  $this->doctor_id,
                    ];
                    $args = ['id'   => $this->doctor_session_id ]; //Need update model function in place of Insertdata -            
                    $status = $this->doctorModel->update_rec_by_args('register_all_users', $args, $this->user_data_arr);
                    if ($status === true) {
                        $args = ['id'   =>  $this->doctor_id]; //Need update model function in place of Insertdata - 
                        $this->user_data_arr = [
                            'profile_pic'    =>  $this->random_name,
                            'updated_at'      =>  date('Y-m-d H:i:s'),
                            'updated_by'      =>  $this->doctor_uid,
                        ];
                        
                        $status = $this->doctorModel->update_rec_by_args('doctor', $args, $this->user_data_arr);
                        if ($status === true) {
                            $this->result_arr = array(
                                'status' => true,
                                'error'  => false, //error: `false` whereever status is true with SQL err 
                                'code' => 200,
                                'data' => '',
                                'message' => 'Photo has uploaded successfully.'
                            );
                            return json_encode($this->result_arr);
                        }
                        else {
                            $this->result_arr = array(
                                'status' => false,
                                'error' => true,
                                'code' => 200,
                                'data' => '',
                                'message' => 'Failed to upload profile pic'
                            );
                            return json_encode($this->result_arr);  
                        }
                    }
                    else {
                        $this->result_arr = array(
                            'status' => false,
                            'error' => true,
                            'code' => 200,
                            'data' => '',
                            'message' => 'Failed to upload profile pic in register all table'
                        );
                        return json_encode($this->result_arr);  
                    }
                }
                else {
                        $this->result_arr = array(
                            'status' => false,
                            'error'  => true, //error: `true` whereever status is false with SQL err 
                            'code' => 200,
                            'data' => '',
                            'message' => 'File is not validated or has moved already'
                        );
                        return json_encode($this->result_arr);
                    }
                    //return redirect()->to(current_url());
                } 
                else {
                $this->result_arr = array(
                    'status' => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code' => 200,
                    'data' => array(),
                    'message' => 'Please choose profile picture'
                );
                return json_encode($this->result_arr);
            }
        } 
        else {
            $this->result_arr = array(
                'status' => true,
                'error'  => false, //error: `false` whereever status is true with SQL err 
                'code' => 200,
                'data' => '',
                'message' => 'Unexpected request method. Please try again'
            );
            return json_encode($this->result_arr);
        }
        //return redirect()->to(current_url());
    } //Function - Closed
    

   /* @params: View logged-in user profile
    * @desc: Admin can view profile 
    * @use: Doctor
    * @return:
    * @author: Neoarks Team
    * @date: 23rd Oct,2023
    * @modify
    */
    public function view_profile() {   
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_id = session()->get('doctor_session_id');
        if ($this->request->getMethod() == 'get') {
            $this->data = []; //For addressing notices
            //$this->args = ['uid' => $this->doctor_uid];
            $this->args = ['id' => $this->doctor_id];
            $this->data['profile_record']  = $this->commonForAllModel->fetch_rec_by_uid('register_all_users', $this->args);
            if($this->data['profile_record'] === false) { //Failure - case
                $this->session->setTempdata('error', 'No record found', 3);
                return view('/Doctor', $this->data);
            }
            else { //Success - case
                //$this->session->setTempdata('success', 'Records found', 3);
                return view('Doctor/view_profile', $this->data);
            }
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method !', 3);
            return view('/Doctor', $this->data);
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
    /* old function */
    public function update_profile_old() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $this->data = [];
            $this->data['validation'] = null;
            $rule = [
                // 'fullname' => 'required|min_length[4]|max_length[20]',
                'fullname'  => [
                    'rules'     => 'required|min_length[4]|max_length[20]',
                    'errors'    => [
                    'required' => 'Full name is  mandatory.',
                    'min_length' => 'Minimum length is 4.',
                    'max_length' => 'Maximum length is 20.'
                    ],
                ],

                'doctor_phone'            => 'required|numeric|exact_length[10]',
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
                    $this->args = [ 'uid'  => $this->doctor_uid, ];
                    $this->user_data_arr = [
                        'username'      =>  $this->request->getPost('fullname', FILTER_SANITIZE_STRING),
                        'about_me'      =>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
                        'gender'        =>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
                        'country_code'      =>  $this->request->getPost('selectedCountryCode', FILTER_SANITIZE_STRING),
                        'mobile'        =>  $this->request->getPost('doctor_phone', FILTER_SANITIZE_STRING),
                        'country'       =>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
                        'state'         =>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
                        'city'          =>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
                        'address'     =>  $this->request->getPost('doctor_address', FILTER_SANITIZE_STRING),
                        'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
                        'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
                        'updated_at'    => date('Y-m-d H:i:s'),
                        'updated_by'    => $this->doctor_uid,
                    ];
                    //echo "<pre>"; print_r($this->user_data_arr);die;
                    $this->data['profile_updt_status'] = $this->commonForAllModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
                    if($this->data['profile_updt_status'] === false) {
                        $this->result_arr = array(
                            'status'    => false,
                            'error'     => true, //error: `true` whereever status is false with SQL err 
                            'code'      => 200,
                            'message'   => 'Unable to update profle!. Please try again',
                            'data'      => $this->data,
                        );
                        return json_encode($this->result_arr);
                    }
                    else {
                        $this->result_arr = array(
                            'status'    => true,
                            'error'      => false, //error: `false` whereever status is true with SQL err 
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
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => false, //error: `false` whereever status is true with SQL err 
                    'code'      => 200,
                    'message'   => 'Oops.!, Mandatory validation failed.!',
                    'data'      => $this->data,
                );
                return json_encode($this->result_arr);
            } 
        } //else -loop closed
    } //function -  closed
    
    


    public function update_profile() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $this->data = [];
            $this->data['validation'] = null;
            $rule = [
                // 'fullname' => 'required|min_length[4]|max_length[20]',
                'fullname'  => [
                    'rules'     => 'required|min_length[4]|max_length[20]',
                    'errors'    => [
                    'required' => 'Full name is  mandatory.',
                    'min_length' => 'Minimum length is 4.',
                    'max_length' => 'Maximum length is 20.'
                    ],
                ],

                'doctor_phone'            => 'required|numeric|exact_length[10]',
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
                    $this->args = [ 'uid'  => $this->doctor_uid ];
                    $this->user_data_arr = [
                        'username'      =>  $this->request->getPost('fullname', FILTER_SANITIZE_STRING),
                        'about_me'      =>  $this->request->getPost('about_me', FILTER_SANITIZE_STRING),
                        'gender'        =>  $this->request->getPost('gender', FILTER_SANITIZE_STRING),
                        'doctor_fee'        =>  $this->request->getPost('doctor_fee', FILTER_SANITIZE_STRING),
                        'education'         =>  $this->request->getPost('education', FILTER_SANITIZE_STRING),
                        'country_code'      =>  $this->request->getPost('selectedCountryCode', FILTER_SANITIZE_STRING),
                        'mobile'        =>  $this->request->getPost('doctor_phone', FILTER_SANITIZE_STRING),
                        'country'       =>  $this->request->getPost('country', FILTER_SANITIZE_STRING),
                        'state'         =>  $this->request->getPost('state', FILTER_SANITIZE_STRING),
                        'city'          =>  $this->request->getPost('city', FILTER_SANITIZE_STRING),
                        'address'     =>  $this->request->getPost('doctor_address', FILTER_SANITIZE_STRING),
                        'pinzip'       =>  $this->request->getPost('pinzip', FILTER_SANITIZE_STRING),
                        'email'       =>  $this->request->getPost('email', FILTER_SANITIZE_STRING),
                        'updated_at'    => date('Y-m-d H:i:s'),
                        'updated_by'    => $this->doctor_uid,
                    ];
                    
                    //echo "<pre>"; print_r($this->user_data_arr);die;
                    //$this->data['profile_updt_status'] = $this->commonForAllModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
                    
                    $this->data_arr2 = [
                        'doctor_name'     => $this->request->getVar('fullname', FILTER_SANITIZE_STRING),
                        'doctor_email'          => $this->request->getVar('email', FILTER_SANITIZE_STRING),
                        'gender'          =>  $this->request->getVar('gender', FILTER_SANITIZE_STRING),
                        'doctor_address'  => $this->request->getVar('doctor_address', FILTER_SANITIZE_STRING),
                        'doctor_fee'       =>  $this->request->getVar('doctor_fee', FILTER_SANITIZE_STRING),
                        'education'       =>  $this->request->getVar('education', FILTER_SANITIZE_STRING),
                        'country_code'  => $this->request->getVar('selectedCountryCode', FILTER_SANITIZE_STRING),
                        'doctor_phone'    => $this->request->getVar('doctor_phone', FILTER_SANITIZE_STRING),
                        'updated_at'    => date('Y-m-d h:i:s'),
                        'updated_by'      => $this->doctor_uid,
                    ];
    
                    
                    //$this->data['profile_updt_status'] = $this->commonForAllModel->update_rec_by_args('register_all_users', $this->args, $this->user_data_arr);
                    $this->data['profile_updt_status'] = $this->commonForAllModel->update_into_two_tables('register_all_users',  $this->args, $this->user_data_arr, 'doctor',  $this->args, $this->data_arr2);

                    if($this->data['profile_updt_status'] === false) {
                        $this->result_arr = array(
                            'status'    => false,
                            'error'     => true, //error: `true` whereever status is false with SQL err 
                            'code'      => 200,
                            'message'   => 'Unable to update profle!. Please try again',
                            'data'      => $this->data,
                        );
                        $this->session->setTempdata('error', 'Sorry ! unable to update, please try again.', 3); 

                        return json_encode($this->result_arr);
                    }
                    else {
                        $this->result_arr = array(
                            'status'    => true,
                            'error'      => false, //error: `false` whereever status is true with SQL err 
                            'code'      => 200,
                            'message'   => 'Congratulation!, Profile has updated successfully !',
                            'data'      => $this->data,
                        );
                        $this->session->setTempdata('success', ' Congratulation!, Profile has updated successfully !', 3);
                
                        return json_encode($this->result_arr);
                    }
                }
                
                else {
                    $this->result_arr = array(
                        'status'    => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => false, //error: `false` whereever status is true with SQL err 
                    'code'      => 200,
                    'message'   => 'Oops.!, Mandatory validation failed.!',
                    'data'      => $this->data,
                );
                return json_encode($this->result_arr);
            } 
        } //else -loop closed
    } //function -  closed


    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    #public function save_blogs(){
    public function save_newsblog() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
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
                    // $doc_img = $image->getName();
                    #$img->move(FCPATH . 'uploads/frontend/blog_image', $newName); 
                    $img->move(FCPATH . 'uploads/frontend/blog_image', $newName);
                    // $path = $this->request->getFile('profile_pic')->store();
                    $doc_img = $img->getName();

                    //Get Doctor Details 
                    $this->dr_sess_id = session()->get('doctor_session_id');
                    $args = [
                        'id' => $this->dr_sess_id,
                        'is_del' => 0
                    ];
                    // $doctor = $this->doctorModel->fetch_rec_by_args('doctor', $args);
                    $doctor = $this->doctorModel->fetch_rec_by_args('doctor', $args);
                    
                    //Get Doctor Details 
                    $userdata = [
                        'blog_title' => $this->request->getVar('blog_title', FILTER_SANITIZE_STRING),
                        'blog_content' => $this->request->getVar('blog_content', FILTER_SANITIZE_STRING),
                        'blog_image' => $doc_img,
                        'doctor_name' => $doctor[0]->doctor_name,
                        'doctor_id' => $doctor[0]->id,
                        'department_name' => $doctor[0]->department_name,
                        'doctor_specility' => $doctor[0]->dr_specialization,
                        'status' => 'Preview',
                        'created_date' => date('d'),
                        'created_month' => date('M'),
                        'created_year' => date('Y')
                    ];
                    // $status = $this->doctorModel->Insertdata('news_blog', $userdata);
                    $status = $this->doctorModel->Insertdata('news_blog', $userdata);
                    if ($status == true) {
                        $this->session->setTempdata('success', 'Congratulation ! News Blog uploaded successfully !', 3);
                    } else {
                        $this->session->setTempdata('error', 'Sorry ! Unable to upload news blog try again.', 3);
                    }
                    return redirect()->to(base_url() . '/Doctor/manage_blogs');
                } else {
                    echo $image->getErrorString() . " " . $image->getError();
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('Doctor/add_newsblog', $data);
    } //Function - Closed

    public function manage_blogs() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
            } 
            else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            $this->dr_sess_id = session()->get('doctor_session_id');
            
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'is_del' => 0,
                //'status'   => 'Verified',
            ];
            $data = [
                'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
                'blogs_content' => $this->doctorModel->fetch_rec_by_args_arr('news_blog', $args),
                'pager' => $this->doctorModel->pager
            ];
            
            return view('Doctor/manage_blogs', $data);
        }  
    } //Function- Closed


    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function delete_blogs($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        //$status = $this->doctorModel->delete_records('news_blog', $args);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Blog deleted Successfully !', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to delete blog Try Again ?', 3);
        }
        return redirect()->to(base_url() . '/Doctor/manage_blogs');
    } //Function - Closed

    /* @params: Function for permanent delete blogs
    * @desc: Admin can soft delete delete blogs also a soft deleted data can be permanently delete by this function
    * @use: Admin....
    * @return:
    * @author: Neoarks Team
    * @date: 16th August,2023
    * @modify
    */
    public function permanent_del_blogs($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        //$status = $this->doctorModel->delete_records('news_blog', $args);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Blog deleted Successfully !', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to delete blog Try Again ?', 3);
        }
        return redirect()->to(base_url() . '/Doctor/manage_blogs');
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */
    public function change_blog_status($id, $status){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'status' => $status
        ];

        // $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('news_blog', $args, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Blog status change successfully !', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to change status blog try again ?', 3);
        }
        return redirect()->to(base_url() . '/Doctor/manage_blogs');
    }//Function- Closed

    /* @param: Function for delete_patients
     * @description: delete_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
    public function delete_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor/all_patients');
        }
    } //Function - Closed

    /* @param: Function for delete_patients
     * @description: delete_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 21st June, 2023
     */
    public function delete_dsbd_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id,
            'is_del'=> 0
        ];
        $data = [
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor');
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
    public function permanent_del_all_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor/all_patients');
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
    public function permanent_del_all_dsbd_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Deleted',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor');
        }
    } //Function - Closed

    public function search_all_patient(){
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
        } else {
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
    
            $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
    
            // Remove spaces from the user input
            ////$keyword = str_replace(' ', '', $keyword);
    
            $args = [
                //'status' => 'Active'
                'is_del' => 0,
                'doctor_id' => $this->dr_sess_id
            ];
    
            if ($keyword) {  
                // Search for data with spaces removed
                $result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
            } else {  
                $result = $this->commonForAllModel;
            }
    
            $data = [
                'all_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->commonForAllModel->pager
            ];
    
            return view('Doctor/all_patients', $data);
        }
    }
    

    public function search_today_discharge_patient(){
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
        }  
        else {
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Doctor ID is missing!', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
            
            $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
            
            // Remove spaces from the user input
            ////$keyword = str_replace(' ', '', $keyword);
            
            $args = [
                'is_del' => 0,
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Discharged',
                'registration_date' => date('Y-m-d')
            ];
            
            if ($keyword) {  
                // Search for data with spaces removed
                $result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
            } else {  
                $result = $this->commonForAllModel;
            }   
            
            $data = [
                'today_discharge_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->commonForAllModel->pager
            ];
            
            return view('Doctor/today_discharge_patient', $data);
        }
    }
    
    public function search_all_dis_patient() {
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } else {
            $this->dr_sess_id = session()->get('doctor_session_id');
            if (!isset($this->dr_sess_id) || $this->dr_sess_id == '') {
                $this->session->setTempdata('error', 'Doctor ID is missing !', 3);
                return redirect()->to(base_url() . "Doctor_login/doctor_login");
            }
    
            $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
    
            // Remove spaces from the user input
            ////$keyword = str_replace(' ', '', $keyword);
    
            $args = [
                'doctor_id' => $this->dr_sess_id,
                'status' => 'Discharged'
            ];
    
            if ($keyword) {  
                // Search for data with spaces removed
                $result = $this->commonForAllModel->search_records('patients', 'patient_name', $keyword, $args);
            } else {  
                $result = $this->commonForAllModel;
            }   
    
            $data = [
                'all_patients' => $this->commonForAllModel->fetch_rec_by_args_with_status('patients', $args),
                'pager' => $this->commonForAllModel->pager
            ];
    
            return view('Doctor/total_discharge_patient', $data);
        }
    }
    
    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    public function search_all_appointments(){
        $this->dr_sess_id = session()->get('doctor_session_id');
        
        if (!(session()->has('doctor_session_uid'))) { 
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        
        $keyword = $this->request->getVar('patient_name', FILTER_SANITIZE_STRING);
        
        // Remove spaces from the user input
        ////$keyword = str_replace(' ', '', $keyword);
        
        $args = [
            'is_del' => 0,
            'doctor_id' => $this->dr_sess_id,
        ];
        
        if ($keyword) {
            // Search for data with spaces removed
            $result = $this->doctorModel->search_records('booked_doctor_appointment', 'patient_name', $keyword, $args);
        } else {
            $result = $this->doctorModel;
        }
        
        $data = [
            'all_appointments' => $this->doctorModel->fetch_rec_by_args_with_status('booked_doctor_appointment', $args),
            'pager' => $this->doctorModel->pager
        ];
        
        return view('Doctor/all_appointments', $data);
    }
    



    /* @param: Function for delete today's patient.
     * @description: delete_patients details from the list of the patients.
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     * @date: 8th August, 2023
     */
    public function delete_today_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'status' => 'Delete',
            //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor/today_patients');
        }
    } //Function - Closed

    /* @params: Function for permanent delete today patients
    * @desc: Doctor  can soft delete doctor's fee also a soft deleted data can be permanently delete by this function
    * @use: Doctor....
    * @return:
    * @author: Neoarks Team
    * @date: 16th August,2023
    * @modify
    */
    public function permanent_del_today_patients($id){
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        }
        $args = [
            'id' => $id
        ];
        $data = [
            'is_del' => 1,
            'status' => 'Delete', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];
        // $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients', $args, $data);
        //$status = $this->patient_model->where('id',$id)->delete();
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients deleted successfully', 2);
            return redirect()->to(base_url() . '/Doctor/today_patients');
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */




    public function delete_today_dis_patients($id){
    $this->patient_id = $id;
        
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id'   => $id
        ];
        $data = [
            'status' => 'Deleted', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];

        // $status = $this->doctorModel->update_rec_by_args('patients',  $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);

            return redirect()->to(base_url() . 'Doctor/today_discharge_patient');
        } else {
            // session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
            return redirect()->to(base_url() . 'Doctor/today_discharge_patient');
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */

    public function delete_all_dis_patients($id){
        $this->patient_id = $id;
        
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id'   => $id
        ];
        $data = [
            'status' => 'Deleted', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-d-m h:i:s'),
            'updated_by' => $this->doctor_uid
        ];

        // $status = $this->doctorModel->update_rec_by_args('patients',  $args, $data);
        $status = $this->doctorModel->update_rec_by_args('patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);

            return redirect()->to(base_url() . 'Doctor/total_discharge_patient');
        } else {
            // session()->setTempdata('error','Oops ! Patients is not deleted ', 2);
            return redirect()->to(base_url() . 'Doctor/total_discharge_patient');
        }
    } //Function - Closed

    /* @param: 
    * @description: 
    * @Remark: 
    * @author: Neoark's Team
    * @copyrights: Neoark Software Pvt Ltd
    * @date: 
    */




    public function filter_blogs($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        if ($filter == 'new_blogs') {
            $order = [
                'column_name' => 'id',
                'order' => 'desc'
            ];
        } else if ($filter == 'old_blogs') {
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
        $this->doctor_uid = session()->get('doctor_session_uid');
            $this->dr_sess_id = session()->get('doctor_session_id');
        
        $args = [
            'doctor_id' => $this->dr_sess_id,
            //'status'   => 'Active'
            'is_del' => 0
        ];
        $data = [
            'userdata' => $this->doctorModel->getLoggedInUserData($this->doctor_uid, 'register_all_users'),
            'blogs_content' => $this->doctorModel->filter_rec_by_args_with_pagination('news_blog', $order, $args),
            'pager' => $this->doctorModel->pager
        ];
         
        return view('Doctor/manage_blogs', $data);
    }  //Function- Closed

    public function admit_today_patient($patient_id, $pid, $apmt_id, $puid, $serial, $dr_id) {
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if ($this->request->getMethod() == 'get') {
            $this->patient_id = (int) $patient_id; 
            $this->pid = (int) $pid; //Patient ID
            $this->apmt_id = (int) $apmt_id; //appointment ID
            $this->puid = $puid; //Hospital assigned patient unique ID
            $this->appt_serial = (int) $serial; //Appointment serial 
            $this->dr_id = (int) $dr_id; //Doctor ID

            $data = [];
            $data['validation'] = null;
                
            // $data['apmt_paymnt_payid'] = 0; // Just for notification & payid is must for next function
            // $data['appointment_fee'] = APMT_REGIS_FEE; //Just for addressing notices
            //$dr_args = [ 'id'  => $this->dr_id  ];
            $dr_args = [ 'ref_id'  => $this->dr_id  ]; //@raaj
            $data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $dr_args); 

            $apmt_args = [ 'id'  => $this->apmt_id ]; 
            $data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        return view('Doctor/payments/add_today_admission_fee', $data); // Pass the data to the view
    } //function - Closed

     /* @params: 
    * @desc: Save Admission fee 
    * @use: Admin
    * @return:
    * @author: Neoarks Team
    * @date: 9th November,2023
    * @modify
    */
    public function save_today_admission_fee($patient_id, $pid, $apmtid, $puid, $serial) {
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
                    'registration_fee'  => $this->request->getVar('other_charges', FILTER_SANITIZE_STRING),
                    'admission_fee' => $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
                    'payment_note'          => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'          =>  $this->puid,
                    'patients_id'   =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
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
        $this->pcnt_args = ['id'    => $this->apmt_id];
        $data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
        $data['apmt_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

        $this->dr_args = ['id'  => $this->apmt_id]; //NEED $this->dr_sess_id here
        $data['doctor_info'] = $this->doctorModel->fetch_rec_by_args('doctor', $this->dr_args);
        return view('Doctor/add_today_admission_fee', $data);
    } //function - Closed






    public function add_today_patient_payment($id, $pid, $apmtid, $puid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        
        $this->puid = $puid; 
        $this->apmt_id = $apmtid; //Appointment ID
        $args = [ 'id'  => $id ]; //patients tbl id
        if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
            $this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
            return redirect()->to(base_url() . 'Doctor/manage_patients');
        }
        $data = [];
        $data['validation'] = null;
        if ($this->request->getMethod() == 'get') { //Render payment form
            $args = [ 'id'  => $this->apmt_id ];
            $data['get_patient'] = $this->commonForAllModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            return view('Doctor/add_today_patient_payment', $data);
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
                /*  'puid'          => '',
                    'apmt_id'       => '',
                    'serial'        => '',
                    'ref_by'        => '',
                    'patient_name'  => '',
                    'age'           => '',
                    'ward'          => '',
                    'bed'           => '',
                    'pay_mode'      => '',
                    'transaction_id'=> '',
                    'payee_id'      => '',
                    'payee_name'    => '',
                    'doctor_id'     => '', */

                    'room_charge'           =>  $this->request->getVar('room_charge', FILTER_SANITIZE_STRING),
                    'doc_fee'               =>  $this->request->getVar('doc_fee', FILTER_SANITIZE_STRING),
                    'med_cost'              =>  $this->request->getVar('med_cost', FILTER_SANITIZE_STRING),
                    'other_cost'            =>  $this->request->getVar('other_cost', FILTER_SANITIZE_STRING),
                    'paid_amount'  => $total_patient_paid_amount,
                    'pid'           =>  $pid,
                    'patients_id'   => $id,
                    'puid'          => $this->puid, 
                    'apmt_id'       => $this->apmt_id,
                    //'status'        =>  'Dues Cleared',
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    =>  date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ];
                
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_today_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_today_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
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
            return redirect()->to(base_url().'Doctor/manage_patients');
        }
    } //function - Closed
    public function generate_today_patient_bill($patient_id, $payid, $apmtid, $puid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        // $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = $patient_id; //patients tbl id 
        $this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = $apmtid;
        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->doctorModel->fetch_rec_by_args('patients_pay_charges', $args);
        
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
                //  'status'  => 'Discharged', 
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => $this->doctor_uid,
                ];
        $this->updt_status = $this->doctorModel->update_rec_by_args('patients', $args, $update_dt_arr);
        $args = [ 'id'  => $this->apmt_id ];
        //$data['get_patient'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
        $data['get_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate_today_apment_patient_bill', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate_today_apment_patient_bill', $data);
        }
    } //function - Closed

    public function add_today_hospital_expenses($id, $pid, $amptid, $puid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $data = [
                'id'        => $id,
                'pid'       => $pid,
                'apmt_id'   => $amptid,
                'puid'      => $puid,
            ];
        return view('Doctor/add_today_hospital_expenses', $data); 
    } //function - Closed
    /*@params: Ajax call
    * @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
    * @retuns:
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function save_today_hospital_expenses() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
                    'tax_amount'        =>  $this->request->getPost('taxCalculation'),
                    'discount_percentage' =>  $this->request->getPost('discount'),
                    'discount_amount'   =>  $this->request->getPost('discount'),
                    'patients_id'       =>  $this->request->getPost('patients_id'),
                    'pid'               =>  $this->request->getPost('pid'),
                    'apmt_id'           =>  $this->request->getPost('apmt_id'),
                    'puid'              =>  $this->request->getPost('puid'),
                    'created_at'        =>  date('Y-m-d h:i:s'),
                    'created_by'        =>  $this->doctor_uid,
                ];

                $status = $this->doctorModel->Insertdata('treatment_expenses_history', $insdata);
                if ($status === true) {
                    $result_arr = array(
                        'status' => true,
                        'error'  => false, //error: `false` whereever status is true with SQL err 
                        'code'  => 200,
                        'message' => 'Expenses added successfully',
                        'data' => $insdata
                    );
                    return json_encode($result_arr);
                } 
                else {
                    $result_arr = array(
                        'status' => false,
                        'error' => true, //error: `true` whereever status is false with SQL err 
                        'code'  => 200,
                        'message' => 'Failed! to add expenses',
                        'data' => $insdata
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
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code'  => 200,
                    'message' => 'Validation failed',
                    'data' => $insdata
                );
                return json_encode($result_arr);
            }
        } 
        else {
            $result_arr = array(
                'status' => false,
                'error'  => true, //error: `true` whereever status is false with SQL err 
                'code'  => 200,
                'message' => 'Unexpected request method',
                'data' => $array(),
            );
            return json_encode($result_arr);
        }
    } //function - Closed

    public function show_today_pat_final_pay($patient_id, $pid, $apmtid, $puid){ // Check if $id is valid and represents a patient or a unique identifier.
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $rules = [ // Define validation rules for the form
            'total_hospital_expenses'    => 'required|numeric',
            'total_patient_paid_amount' => 'required|numeric',
            'final_adjusted_amount'     => 'required|numeric',
            'remark'                    => 'required'
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
        $data['total_expnc_amt'] = $this->doctorModel->fetch_sum_by_args('treatment_expenses_history', $fld, $args);
        if( $data['total_expnc_amt'] === false ) { $data['total_expnc_amt'] = 0; }
        
        $args = [ 'id'  => $patient_id ];
        $data['patients'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
        $args_apmt = [ 'id'  => $apmtid ];
        $data['get_patient'] = $this->doctorModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);
        return view('Doctor/show_today_pat_final_pay', $data); // Pass the data to the view
    } //function - Closed
       
    public function clear_today_pat_final_dues($patient_id, $pid, $apmtid, $puid, $status) { //pid ie patient_login table id
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');

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
                    return redirect()->to(base_url() . 'Doctor/show_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
                }           
                $this->user_data_arr = [
                    'total_hospital_expenses'   => $this->request->getVar('total_hospital_expenses', FILTER_SANITIZE_STRING),
                    'total_patient_paid_amount' => $this->request->getVar('total_paid_amount', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING),
                    'payment_note'  => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'           =>  $this->puid,
                    'patients_id'    =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/'. $this->status);
                }
            } 
            else { $data['validation'] = $this->validator; }
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        $data['patients'] = $this->doctorModel->fetch_rec_by_args('patients', $args);
        return view('Doctor/discharge_today_appointment_pat', $data);
    } //function - Closed

    public function change_today_patients_status($id, $status){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "Doctor_login/doctor_login");
        } else {
        }
        $args = [
            'id' => $id,
            'is_del'=>0
        ];
        $data = [
            'status' => $status
        ];
        $status = $this->patient_model->update($id, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
            // return redirect()->to(base_url().'/Frontdesk/manage_patients');
            return redirect()->to(base_url() . '/Doctor/today_patients');
        }
    } //funtion - Closed

    function add_today_pat_prescription($id) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        else {
            $data = [
                'res'  => $this->adminModel->getPatientRecordWithAppointment($id),
                'pager'   => $this->adminModel->pager
            ];
            $args = [ 'patient_id'  =>      $id ]; //Patient ID
            $this->report = $this->adminModel->fetch_rec_by_args('patient_reports', $args);
            $this->reportArr = [];
            $this->reportAtchMentArr = [];
            if($this->report){
                foreach($this->report as $val){
                    $this->reportArr[$val->id] = $val->report_name;
                    $this->reportAtchMentArr[$val->id] = $val->report_attachment;
                }

            } //else loop - Not requred here because no report has assigned to patient in this case
            $data['report'] = $this->reportArr; 
            $data['reporatach'] = $this->reportAtchMentArr; 
            return view('Doctor/add_today_patient_prescription', $data);
        }
    } //function - Closed

    public function add_today_pat_prescription_report($pid) {
        //public function add_prescription_report($pid) {
            if (!(session()->has('doctor_session_uid'))) {
                return redirect()->to(base_url() . "/Doctor_login/doctor_login");
            } 
            $this->doctor_uid = session('doctor_session_uid');
            $selected_report=$this->request->getPost('selected_report');
            $selected_report_arr=explode(',',$selected_report);
            
            if($this->request->getMethod() == 'post') {
                if(count($selected_report_arr)>0) {
                    foreach($selected_report_arr as $val) {
                        $this->report_data_arr = [
                            'patient_id'    =>  $pid,
                            'report_name'    =>  $val,
                            'report_brief'   =>  $val, //
                            'report_detail'  =>  $val,
                            'report_attachment' =>  '',//$doc_img,
                            'created_at'      =>  date('Y-m-d H:i:s'),
                            'created_by'      =>  $this->doctor_uid, //Hardcoded for now
                        ];
                        $status = $this->doctorModel->Insertdata('patient_reports', $this->report_data_arr);
                    }
                }
                if ($status === true) {
                    $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'data' > '',
                        'message' => 'Reports saved successfully'
                    );
                    return json_encode($this->result_arr);
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    'code' => 200,
                    'data' > '',
                    'message' => 'Sorry ! Required GET method'
                );
                return json_encode($this->result_arr);
            }
        } //Function - Closed
    
    function upload_today_pat_prescription() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->return_dt_arr = []; //Just for addressing notices
            $this->doctor_uid = session()->get('doctor_session_uid'); //Loggedin User uid
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    "message" => 'Invalid request method'
                ); 
                return json_encode($this->result_arr);
            }
            $rules = [ 
                'prescription'  => 'required',
                'doctor_name'   => 'required',
                'patient_id'    => 'required',
                'patient_name'  => 'required',
                'patient_puid'  => 'required',
                ];  
            if ($this->validate($rules)) {
                $prescription      = $this->request->getPost('prescription');
                $patient_name      = $this->request->getPost('patient_name');
                $patient_id        = $this->request->getPost('patient_id');
                $doctor_id         = $this->request->getPost('doctor_id');
                $patient_age       = $this->request->getPost('patient_age');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_gender    = $this->request->getPost('patient_gender');
                $patient_puid      = $this->request->getPost('patient_puid');
                $doctor_name       = $this->request->getPost('doctor_name');
                $education         = $this->request->getPost('education');
                $dr_specialization  = $this->request->getPost('dr_specialization');
                $doc_gender        = $this->request->getPost('doc_gender');
                $apmt_id           = $this->request->getPost('apmt_id');
                $pid               = $this->request->getPost('pid');
                $ref_by            = $this->request->getPost('ref_by');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_email     = $this->request->getPost('patient_email');
                $disease_symptoms  = $this->request->getPost('disease_symptoms');
                $patient_type      = $this->request->getPost('patient_type');
                $status            = $this->request->getPost('status');
                
                if($status != "Admit") { $status = 'Prescribed'; }
                $this->prescription_data_arr = [
                    'prescription'        =>  $prescription,//
                    'prescription_detail' =>  $prescription, //
                    'patient_name'        =>  $patient_name,
                    'patient_id'          =>  $patient_id,
                    'doctor_id'           =>  $doctor_id,
                    'status'              =>  $status, 
                    'age'                 =>  $patient_age,
                    'gender'              =>  $patient_gender,
                    'puid'                =>  $patient_puid,
                    'doctor_name'         =>  $doctor_name,
                    'apmt_id'             =>  $apmt_id,
                    'pid'                 =>  $pid,
                    'ref_by'              =>  $ref_by,
                    'patient_mobile'      =>  $patient_mobile,
                    'patient_email'       =>  $patient_email,
                    'disease_symptoms'    =>  $disease_symptoms,
                    'patient_type'        =>  $patient_type,
                    'prescription_date'   =>  date('Y-m-d H:i:s'),
                    'created_at'          =>  date('Y-m-d H:i:s'),
                    'created_by'          =>  $this->doctor_uid, //Harcoded for now
                ];
                //echo "<pre>";print_r($this->prescription_data_arr);die;
                //$status = $this->adminModel->Insertdata('prescription_history', $this->prescription_data_arr);
                //$last_insrt_id = $this->commonForAllModel->Insertdata_return_id('prescription_history', $this->prescription_data_arr);

                $this->pat_args = ['id'     => $patient_id];
                $this->updt_patient_arr = [
                    'status'        => $status,
                    'updated_by'    => $this->doctor_uid,
                    'updated_at'    => date('Y-m-d H:i:s')

                ];
                $this->fld = '';
                $this->fldval = '';
                $last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'patients', $this->pat_args, $this->updt_patient_arr);
                
                if ((int) $last_insrt_id > 0 ) {
                    $this->return_dt_arr = ['prescription_id' => $last_insrt_id];
                    //$this->data['prescription'] = array(array());
                     $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'message' => 'Prescription added successfully',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr); 
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
                        'code' => 200,
                        'message' => 'Failed to add prescripton. Please try again123.',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr);
                }
            }
            else{
                $this->result_arr = array(
                    'status' => false,
                    'error'     => false, //error: `false` showing validation error autoamtically
                    'code' => 200,
                    'message' => 'Validation failed. Please try again',
                    'data'      => $this->return_dt_arr,
                ); 
                return json_encode($this->result_arr); 
            }
        } 
    } //function - Closed

    public function upload_today_pat_prescription_report($pid, $rid,$file_id, $prescription_id) { //$pid: patient_id, $rid : Report ID
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid'); //Loggedin User uid
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
                    'pid'               => $pid,
                    'report_attachment' =>  $doc_img,
                    'prescription_id'   =>  $prescription_id,
                    //'ref_id'            =>  $ref_id,
                    'updated_at'      =>  date('Y-m-d H:i:s'),
                    'updated_by'      =>  $this->doctor_uid,
                ];
                $args = ['id'   => $rid];
                //Need update model function in place of Insertdata - 
                $status = $this->adminModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
                if ($status === true) {
                    $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'data' > '',
                        'message' => 'Reports uploaded successfully'
                    );
                    return json_encode($this->result_arr);
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                // return redirect()->to(base_url() . '/Doctor/add_prescription');
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'error'     => true, //error: `true` whereever status is false with SQL err 
                'code' => 200,
                'data' > '',
                'message' => 'Unexpected request method. Please try again'
            );
            return json_encode($this->result_arr);
        }
        return redirect()->to(current_url());
    } //Function - Closed

    public function save_today_pat_advice() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->doctor_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'advice'        =>  $advice,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                    'status'        => 'Prescribed',
                    'updated_by'    =>  $this->doctor_uid, //Harcoded for now
                ];
                //echo"<pre>";print_r($this->advice_data_arr);die;
                $args = [ 'patient_id'   => $patient_id ];
                $fld_name = 'id';
                $this->maxid = $this->adminModel->get_max_val('prescription_history', $fld_name);
                if($this->maxid === false || (!isset($this->maxid['0']->id))) {
                    $this->data['advice'] = array(array());
                     $this->result_arr = array(
                        "status" => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => false, //error: `false` with status `true`
                            "code" => '200',
                            "message" => 'Advice added successfully'
                        ); 
                        return json_encode($this->result_arr); 
                    } 
                    else {
                        $this->result_arr = array(
                            "status" => false,
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'Validation failed.'
                ); 
                return json_encode($this->result_arr); 
            }
        } //else - loop Closed
    } //Function - Closed

    public function save_today_pat_message() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor/doctor_login");
        } else {
            $this->doctor_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
    
            if ($this->request_method != 'post') {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'Validation failed.'
                ]);
            }
    
            $msg_frm_doc = $this->request->getPost('msg_frm_doc');
            $patient_id = $this->request->getPost('patient_id');
            $assigned_to = $this->request->getPost('slct_refr_usr');
    
            $message_data = [
                'assigned_by' => $this->doctor_uid,
                'assigned_to' => $assigned_to,
                'status'    => 'Prescribed',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->doctor_uid,
                'recommendation' => $msg_frm_doc,
            ];
    
            $prescription_id = $this->adminModel->get_max_val('prescription_history', 'id');
    
            if ($prescription_id === false || empty($prescription_id[0]->id)) {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'ID is missing. Please talk to admin'
                ]);
            }
    
            $status_history = $this->adminModel->update_rec_by_args('prescription_history', ['id' => $prescription_id[0]->id], $message_data);
    
            $status_patient = $this->adminModel->update_rec_by_args('patients', ['id' => $patient_id], ['status' => 'Prescribed']);
    
            if ($status_history === true && $status_patient === true) {
                return json_encode([
                    "status" => true,
                    'error'     => false, //error: `false` with status `true`
                    "code" => '200',
                    "message" => 'Recommendation added successfully'
                ]);
            } else {
                // Handle database errors
                $error_message = 'Failed to add Recommendation. Check the error logs for details.';
                log_message('error', $error_message);
    
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => $error_message
                ]);
            }
        }
    }  // Function - Closed


    //------------------Number of function related to revisit patients start from here-------------------------//
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $data  = [];
        
        // $data['validation'] = null;
        if($this->request->getMethod() == 'post') { 
            $this->bed_id = $this->request->getPost('bed_id',FILTER_SANITIZE_STRING);
            $this->patient_id = (int) $this->request->getPost('patient_id',FILTER_SANITIZE_STRING);
            if((!isset($this->patient_id) || $this->patient_id == '') || $this->patient_id === 0 ) {
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    'code'  => 200,
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
                // 'is_free'       => 0,
                'status'           =>'Occupied',
                'pid'              => $this->request->getPost('pid',FILTER_SANITIZE_STRING),
                'puid'             => $this->request->getPost('puid',FILTER_SANITIZE_STRING),
                'apmt_id'          => $this->request->getPost('apmt_id',FILTER_SANITIZE_STRING),
                'dr_id'            => $this->request->getPost('dr_id',FILTER_SANITIZE_STRING),
                'updated_at'       => date('Y-m-d h:i:s'),
                'updated_by'       => $this->doctor_uid,
            ];
            //echo "<pre>"; print_r($this->updt_data_arr);die;
            $this->updt_bed_args = [ 'id'   => $this->bed_id ];  

            $this->updt_rvsit_arr = [ 
                'status'        => 'Admission Processed',
                'updated_at'    => date('Y-m-d h:i:s'),
                'updated_by'    => $this->doctor_uid,
            ];
            $this->updt_rev_args = [ 'id'   => $this->patient_id];
            $status = $this->commonForAllModel->update_into_two_tables('hospital_beds', $this->updt_bed_args, $this->updt_bed_arr, 'revisit_patients', $this->updt_rev_args, $this->updt_rvsit_arr);
            if ($status === true) {
                $this->result_arr = array(
                    'status' => true,
                    'error'  => false, //error: `false` with status `true`
                    'code'   => 200,
                    'message'=> 'Record updated successfully',
                    'data'   => $this->updt_bed_arr
                );
                return json_encode($this->result_arr);
            } 
            else {
                $this->result_arr = array(
                    'status' => false,
                    'error'  => true, //error: `true` whereever status is false with SQL err 
                    'code'   => 200,
                    'message'=> 'Failed! to update record',
                    'data'   => $this->updt_bed_arr
                );
                return json_encode($this->result_arr);
            } //else - loop closed 
        }
        else {
            $this->result_arr = array(
                'status' => false,
                'error'  => true, //error: `true` whereever status is false with SQL err 
                'code'   => 200,
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
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            //$dr_args = [ 'id'  => $this->dr_id  ];
            $dr_args = [ 'ref_d'  => $this->dr_id  ]; //@raaj
            $data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $dr_args); 

            $apmt_args = [ 'id'  => $this->apmt_id ]; 
            $data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        return view('Doctor/payments/add_revisit_admission_fee', $data); // Pass the data to the view
    } //function - Closed


    // public function change_revisit_patients_status($id, $status) {
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $this->doctor_uid = session()->get('doctor_session_uid');
    //  if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
    //      $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }
    //  $args = [ 'id'   => $id ];
    //  $data = [
    //      'status'  => $status,
    //      'updated_at' => date('Y-m-d H:i:s'),
    //      'updated_by' => $this->doctor_uid
    //  ];

    //  $status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $data);
    //  if ($status == true) {
    //      session()->setTempdata('success', 'Congratulation ! Patients Status Updated Successfully', 2);
    //      return redirect()->to(base_url() . '/Doctor/manage_revisited_patients');
    //  }
    // }
    public function change_revisit_patients_status($id, $pid, $status) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        else {
            $this->doctor_uid = session()->get('doctor_session_uid');
            if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
                $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
                return redirect()->to(base_url() . "/Doctor_login/doctor_login");
            }
        }
        $args = [ 'id' => $id,]; 
        $data = [
                'status'  => $status,
                'updated_at' => date('Y-d-m H:i:s'),
                'updated_by' => $this->doctor_uid,
            ];
        $status = $this->patient_model->update($id, $data);
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! patients status has updated successfully', 2);
            return redirect()->to(base_url() . '/Doctor/manage_revisit_patient');
        }
    } //funtion - Closed



    public function delete_revisit_patients($id){
        $this->patient_id = $id;
        
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id'   => $id
        ];
        $data = [
            'status'     => 'Deleted', //0: Non deleted, 1: Deleted
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->doctor_uid
        ];

        //$status = $this->adminModel->delete_rec_by_args('revisit_patients', $data, $args);
        $status = $this->adminModel->update_rec_by_args('revisit_patients',  $args, $data);
        if ($status == true) {
            session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
            //return redirect()->to(base_url().'Doctor/manage_today_discharged_patient');
            return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
        } else {
            session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
            // return redirect()->to(base_url().'Doctor/manage_revisit_patient');
            return view('Doctor/manage_revisit_patient', $data);
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
    
    if (!(session()->has('doctor_session_uid'))) {
        return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    }
    $this->doctor_uid = session()->get('doctor_session_uid');
    if (!isset($this->doctor_uid) || $this->doctor_uid == '') {
        $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
        return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    }
    $args = [
        'id'   => $id,
        // 'is_del'   => '1'
    ];
    $data = [
        'is_del'     => '1',
        'status'     => 'Deleted', //0: Non deleted, 1: Deleted
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $this->doctor_uid
    ];

    //$status = $this->adminModel->delete_rec_by_args('revisit_patients', $data, $args);
    $status = $this->adminModel->update_rec_by_args('revisit_patients',  $args, $data);
    if ($status == true) {
        session()->setTempdata('success', 'Congratulation ! Patients has deleted successfully', 2);
        //return redirect()->to(base_url().'Doctor/manage_today_discharged_patient');
        return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    } else {
        session()->setTempdata('error', 'Oops ! Patients is not deleted ', 2);
        // return redirect()->to(base_url().'Doctor/manage_revisit_patient');
        return view('Doctor/manage_revisit_patient', $data);
    }
}


public function discharge_revisit_patients($id){
    if (!(session()->has('doctor_session_uid'))) {
        return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    }
    $args = [
        'id'  => $id
    ];
    $data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
    return view('Doctor/manage_revisit_patient', $data);
}

// public function manage_revisit_patient() {
//  if (!(session()->has('doctor_session_uid'))) {
//      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
//  } else {
//      $args = [ 'status'  => 'Discharged' ];
//      $data = [
//          'revisited_patients'  => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
//          'pager'   => $this->adminModel->pager
//      ];
//      return view('Doctor/manage_revisit_patient', $data);
//  }
// }

    /*@params: 
    * @desc: Fetch records based on passed `tablename`, where conditions $args, $likeArgs & $orlikeArgs array 
    * @retuns: Array format
    * @author: Neoarks Team
    * @date: 10th July, 2023
    * @modify:
    */
    public function manage_revisited_patients(){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } else {
            $args = [
                //'status'          => 'Admission Processed',
                'is_del'  => 0
            ];
            $data = [
                'revisited_patients'  => $this->adminModel->fetch_rec_by_args_with_status('revisit_patients', $args),
                'pager'   => $this->adminModel->pager
            ];
            if (!isset($data['revisited_patients']) || $data['revisited_patients'] == '') {
                $this->session->setTempdata('error', 'Sorry ! No revisited patient found. Please see all patients here', 3);
                return redirect()->to(base_url() . "Doctor/manage_patients");
            }
            //echo "<pre>";print_r($data);die;
            return view('Doctor/manage_revisit_patient', $data);
        }
    }
    
    public function revisit_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args = [
            'id'  => $id
        ];
        $data['patients'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
        $data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
        if ($data['doctors'] == false) {
            $this->session->setTempdata('error', 'Sorry ! No record found ', 3);
            return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
        }
        return view('Doctor/revisit_patients', $data);
    }


    

    public function search_results(){
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url()."/Doctor_login/doctor_login");}  
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
        return view('Doctor/manage_revisit_patient', $data);
    }
    

    public function filter_revisit_patient($filter){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
            return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
        }
        return view('Doctor/manage_revisit_patient', $data);
    }


    public function number_of_visit_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $args =  [
            'patient_id'  => $id
        ];
        $data['pat_visiter'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
        if ($data['pat_visiter'] == false) {
            $this->session->setTempdata('error', 'Sorry ! No revisited patient found ', 3);
            return redirect()->to(base_url() . "Doctor/manage_revisited_patients");
        }
        //echo "<pre>";print_r($data['pat_visiter']);die;   //data are coming from backend side
        return view('Doctor/number_of_visiting_pat', $data);
    }

    public function search_revisit_patient(){
        if (!(session()->has('doctor_session_uid'))) {  
            return redirect()->to(base_url()."/Doctor_login/doctor_login");
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
        
        return view('Doctor/manage_revisit_patient', $data);
    }
    public function fetch_revisit_patients() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        else {
            $args = [ 'login_acc'   => 1];
            $data = [
                'doctors' => $this->adminModel->fetch_rec_by_args('doctor', $args),
            ];
            return view('Doctor/add_patients', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        
        $this->puid = $puid; 
        $this->apmt_id = $apmtid; //Appointment ID
        $args = [ 'id'  => $id ]; //patients tbl id
        if(!isset($this->apmt_id) || $this->apmt_id == 0 ) {
            $this->session->setTempdata('error', 'Appointment ID be non-zero number only', 3);
            return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
        }
        $data = [];
        $data['validation'] = null;
        if ($this->request->getMethod() == 'get') { //Render payment form
            $args = [ 'id'  => $this->apmt_id ];
            $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
            return view('Doctor/add_revisit_patient_payment', $data);
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
                    'patients_id'           => $id,
                    'puid'                  => $this->puid, 
                    'apmt_id'               => $this->apmt_id,
                    //'status'              =>  'Dues Cleared',
                    'pay_date'              =>  date('Y-m-d'),
                    'created_at'            =>  date('Y-m-d h:i:s'),
                    'created_by'            => $this->doctor_uid,
                ];
                
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_revisit_patient_bill/' . $id . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/' . $this->puid);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_revisit_patient_bill/' . $id. '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
                }
            } 
            else { 
                $this->session->setTempdata('error', 'Mandatory validation failed', 2);
                $data['validation'] = $this->validator; 
                return view('Doctor/add_revisit_patient_payment', $data); 
            }
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method', 2);
            $data['validation'] = $this->validator;
            return redirect()->to(base_url().'Doctor/manage_revisit_patient');
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        // $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = $patient_id; //patients tbl id 
        $this->pay_chrg_id = $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = $apmtid;
        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
        
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
                //  'status'  => 'Discharged', 
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => $this->doctor_uid,
                ];
        $this->updt_status = $this->adminModel->update_rec_by_args('patients', $args, $update_dt_arr);
        $args = [ 'id'  => $this->apmt_id ];
        //$data['get_patient'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate__revisit_apment_patient_bill', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate__revisit_apment_patient_bill', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $data = [
                'id'        => $id,
                'pid'       => $pid,
                'apmt_id'   => $amptid,
                'puid'      => $puid,
            ];
        return view('Doctor/add_revisit_hosp_expense', $data); 
    } //function - Closed


    /*@params: Ajax call
    * @desc: Save patient treatment expenses, done by hospital, into `treatment_expenses_history` tbl
    * @retuns:
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function save_revisit_hospital_expenses() {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            if ($this->validate($rules)) {
                
                $insdata = [
                    'medical_item'        =>$this->request->getPost('medical_item_name'),
                    'medical_code'        =>  $this->request->getPost('item_code'),
                    'unit_price'          =>  $this->request->getPost('unit_price'),
                    'total_Price'         =>  $this->request->getPost('totalPrice'),    
                    'units'               =>  $this->request->getPost('quantity'),
                    'tax_percentage'      =>  $this->request->getPost('tax'),
                    'tax_amount'          =>  $this->request->getPost('taxCalculation'),
                    'discount_percentage' =>  $this->request->getPost('discount'),
                    'discount_amount'     =>  $this->request->getPost('discount'),
                    'patients_id'         =>  $this->request->getPost('patients_id'),
                    'pid'                 =>  $this->request->getPost('pid'),
                    'apmt_id'             =>  $this->request->getPost('apmt_id'),
                    'puid'                =>  $this->request->getPost('puid'),
                    'created_at'          =>  date('Y-m-d h:i:s'),
                    'created_by'          =>  $this->doctor_uid,
                ];

                $status = $this->adminModel->Insertdata('treatment_expenses_history', $insdata);
                if ($status === true) {
                    $this->result_arr = array(
                        'status'    => true,
                        'error'     => false, //error: `false` with status `true`
                        'code'      => 200,
                        'message'   => 'Expenses added successfully',
                        'data'      => $insdata
                    );
                    return json_encode($this->result_arr);
                } 
                else {
                    $this->result_arr = array(
                        'status'   => false,
                        'error'    => true, //error: `true` whereever status is false with SQL err 
                        'code'     => 200,
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
                    'error'  => false, //error: `false` showing validation error autoamtically
                    'code'   => 200,
                    'message'=> 'Validation failed',
                    'data'   => $insdata
                );
                return json_encode($this->result_arr);
            }
        } 
        else {
            $this->result_arr = array(
                'status' => false,
                'error'  => true, //error: `true` whereever status is false with SQL err 
                'code'   => 200,
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        //$fld = 'total_patient_paid_amount'; //table field name
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
        //[total_pay_amt] => 214310.00
        //[total_regis_amt] => 5300.00
        //[total_admission_amt] => 202000
        //echo "<pre>";print_r($data);die;

        // if(!isset($data['total_pay_amt'][0]->paid_amount) || (!isset($data['total_regis_amt'][0]->registration_fee)) || (!isset($data['total_admission_amt'][0]->admission_fee))) {
        //     $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
        // }
        // //Total Patient paid amount - Total registration fee
        // $data['total_payable'] = ($data['total_pay_amt'][0]->paid_amount - $data['total_regis_amt'][0]->registration_fee - $data['total_admission_amt'][0]->admission_fee);

        if(!isset($data['total_pay_amt']) || (!isset($data['total_regis_amt'])) || (!isset($data['total_admission_amt']))) {
            $this->session->setTempdata('error', 'Missing registration_fee or paid_amount indices', 3);
        }
        //Total Patient paid amount - Total registration fee
        $data['total_payable'] = ($data['total_pay_amt'] - $data['total_regis_amt'] - $data['total_admission_amt']);
        $fld = 'total_price'; //table field name
        $data['total_expnc_amt'] = $this->adminModel->fetch_sum_by_args('treatment_expenses_history', $fld, $args);
        if( $data['total_expnc_amt'] === false ) { $data['total_expnc_amt'] = 0; }
        
        $args = [ 'pid'  => $pid ];
        $data['revisit_patient'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
        $args_apmt = [ 'id'  => $apmtid ];
        $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args_apmt);
        return view('Doctor/show_revisit_patient_final_payments', $data); // Pass the data to the view
    } //function - Closed



   /* @params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
    * @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
    * @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function clear_revisit_final_dues($patient_id, $pid, $apmtid, $puid) {//pid is patient_login tbl id
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
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
                    return redirect()->to(base_url() . 'Doctor/show_revisit_patient_final_payments/' . $this->patient_id. '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
                }
                $this->user_data_arr = [
                    'total_hospital_expenses'   => $this->request->getVar('total_hospital_expenses', FILTER_SANITIZE_STRING),
                    'total_patient_paid_amount' => $this->request->getVar('total_paid_amount', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('dues_amount', FILTER_SANITIZE_STRING),
                    'payment_note'  => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'          =>  $this->puid,
                    'patients_id'   =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    return redirect()->to(base_url().'Doctor/generate_revisit_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_revisit_clear_dues_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid);
                }
            } 
            else { $data['validation'] = $this->validator;
                return view('Doctor/show_revisit_patient_final_payments', $data);
             }
        }
        else { $this->session->setTempdata('error', 'Unsupported request method', 3); }
        $data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
        return view('Doctor/discharge_appointment_pat', $data);
    } //function - Closed


    /*@params: $id (patients tbl id), $pid (patient_login tbl id), $apmtid (appointment ID), $puid
    * @desc: Clear patient final dues/payments into `patients_pay_charges` tbl and Generate pdf format bill
    * @retuns: Also call function `generate_clear_dues_bill(), for generating bill in pdf format
    * @author: Neoarks Team
    * @date: 18th October, 2023
    * @modify:
    */
    public function generate_revisit_clear_dues_bill($patient_id, $pid, $payid, $apmtid, $puid) { //Ref generate_patient_bill()
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->patient_id = (int) $patient_id; //patients tbl id 
        $this->pid = (int)$pid;
        $this->pay_chrg_id = (int) $payid; //patient_pay_charges tbl id 
        $this->puid = $puid;
        $this->apmt_id = (int) $apmtid; //Appointment ID

        $args = ['id'  => $this->pay_chrg_id ];
        $data['payment_bill'] = $this->adminModel->fetch_rec_by_args('patients_pay_charges', $args);
        if($data['payment_bill'] === false) {
            $this->session->setTempdata('error', 'Payments bill is not found ', 3);
            return redirect()->to(base_url() . 'Doctor/show_revisit_patient_final_payments/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
        }
        $args = [ 'id'  => $this->patient_id ];
        $update_dt_arr = [ 
                    'status'  => 'Dues Cleared', 
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => $this->doctor_uid,
                ];
        $this->updt_status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $update_dt_arr);
        
        $args = [ 'id'  => $this->apmt_id ];
        $data['get_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $args);
        if($this->updt_status === true) {
            $this->session->setTempdata('success', 'Bill generated successfully', 3);
            return view('Doctor/generate_revisit_clear_dues_bill', $data);
        }
        else {
            $this->session->setTempdata('Failed !', 'to generate bill', 3);
            return view('Doctor/generate_revisit_clear_dues_bill', $data);
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
        if (!(session()->has('doctor_session_uid'))) { 
            $this->session->setTempdata('error', 'Doctor UID is missing !', 3);
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->doctor_uid = session()->get('doctor_session_uid');
        $this->doctor_session_id = session()->get('doctor_session_id');
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
                    'registration_fee'  => $this->request->getVar('other_charges', FILTER_SANITIZE_STRING),
                    'admission_fee' => $this->request->getVar('admission_fee', FILTER_SANITIZE_STRING),
                    'paid_amount'   => $this->request->getVar('total_payable', FILTER_SANITIZE_STRING),
                    'payment_note'  => $this->request->getVar('payment_note', FILTER_SANITIZE_STRING),
                    'pid'           =>  $this->pid,
                    'puid'          =>  $this->puid,
                    'patients_id'   =>  $this->patient_id,
                    'apmt_id'       =>  $this->apmt_id,
                    'pay_date'      =>  date('Y-m-d'),
                    'created_at'    => date('Y-m-d h:i:s'),
                    'created_by'    => $this->doctor_uid,
                ]; 
                $this->last_inst_id = $this->commonForAllModel->Insertdata_return_id('patients_pay_charges', $this->user_data_arr);
                if ($this->last_inst_id === false) { //failure case
                    session()->setTempdata('error','Oops ! Unable to update payments', 2);
                    return redirect()->to(base_url().'Doctor/generate_revisit_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid . '/' . $this->appt_serial);
                } 
                else { //success case
                    return redirect()->to(base_url() . 'Doctor/generate_revisit_admission_bill/' . $this->patient_id . '/'. $this->pid . '/'. $this->last_inst_id . '/'. $this->apmt_id . '/'. $this->puid. '/' . $this->appt_serial);
                }
            } 
            else { 
                $data['validation'] = $this->validator;
                return view('Doctor/payments/add_revisit_admission_fee', $data);
                $this->session->setTempdata('error', 'Failed to validate mandatory fields', 3);
            }
        }
        else { 
            $this->session->setTempdata('error', 'Request method is not supported', 3); 
        }
        $this->pcnt_args = ['id'    => $this->apmt_id];
        $data['apmt_paymnt_payid'] = 0; //Need to chage with exact id or so
        $data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $this->pcnt_args);

        //$this->dr_args = ['id'    => $this->apmt_id]; //NEED $doctor_id here
        $this->dr_args = ['ref_id'  => $this->doctor_session_id]; //@raaj
        $data['doctor_info'] = $this->adminModel->fetch_rec_by_args('doctor', $this->dr_args);
        return view('Doctor/payments/add_revisit_admission_fee', $data);
    } //function - Closed

    public function get_dept_for_rev_admission($patient_id, $pid, $puid, $apmt_id, $dr_id) {   
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        $this->data['patient_id'] = $patient_id;
        $this->data['pid'] = $pid;
        $this->data['puid'] = $puid;
        $this->data['apmt_id'] = $apmt_id;
        $this->data['dr_id'] = $dr_id;
        //$this->data['patient_id'] = $patient_id;
        $this->dr_id = session()->get('doctor_session_id');
        $this->dept_args = [
            'is_del'    => 0,
            'status'    => 'Active',
        ];
        //$this->data['patient_id']
        $this->data['departments'] = $this->doctorModel->fetch_rec_by_args('department', $this->dept_args);
        //echo "<pre>";print_r($this->data);die;
        if($this->request->getMethod() == 'get') {
            return view('Doctor/revisit_addmission_process', $this->data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected request method !', 3);
            return view('Doctor/revisit_addmission_process', $this->data);
        }
    } //function - closed

    public function generate_revisit_admission_bill($patient_id, $pid, $payid, $apmtid, $puid, $serial) { //Ref generate_patient_bill()
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->doctor_uid = session()->get('doctor_session_uid');
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
            return redirect()->to(base_url() . 'Doctor/add_revisit_admission_fee/' . $this->patient_id . '/'. $this->pid . '/'. $this->apmt_id . '/'. $this->puid);
        }
        if($this->patient_id > 0 ) { //0: When attended the non-loggedin patient's appointment
            $args = [ 'id'  => $this->patient_id ]; 
            $update_dt_arr = [
                    'status'  => 'Admit', 
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'updated_by'  => $this->doctor_uid,
                ];
            $this->updt_status = $this->adminModel->update_rec_by_args('revisit_patients', $args, $update_dt_arr);
        }
        $apmt_args = [ 'id'  => $this->apmt_id ]; //`id` of `booked_doctor_appointment` table
        $data['apmt_patient'] = $this->adminModel->fetch_rec_by_args('booked_doctor_appointment', $apmt_args);
        if($this->updt_status === true) { //When attended the loggedin patient's appointment
            $this->session->setTempdata('success', 'Bill generated successfull', 3);
            return view('Doctor/payments/generate_admission_bill', $data);
        }
        else if($this->updt_status === false) { //When attended the non-loggedin patient's appointment
            $this->session->setTempdata('success', 'Bill generated successfull, however unable to update patients table', 3);
            return view('Doctor/payments/add_admission_fee', $data);
        }
        else {
            $this->session->setTempdata('error', 'Unexpected result', 3);
            return view('Doctor/payments/generate_admission_bill', $data);
        }
    } //function - Closed

    /***************************** Prescritpion Section For Revisit Patient - START  *****************************/
    /* @param: 
    * @desc: Render Prescription Form: Step - Zero
    * @use:
    * @remark:
    * @author: Neoarks Team
    * @date:
    * @modify:
    */
    function add_revisit_prescription($pid) {
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }
        else {
            $data = [
                'res'  => $this->adminModel->getRevisitPatientRecordWithAppointment($pid),
                'pager'   => $this->adminModel->pager
            ];
            $args = [ 'pid' =>      $pid ]; //Patient ID
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
            return view('Doctor/add_revisit_patient_prescription', $data);
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->return_dt_arr = []; //Just for addressing notices
            $this->admin_uid = session()->get('doctor_session_uid'); //Loggedin User uid
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => 200,
                    "message" => 'Invalid request method'
                ); 
                return json_encode($this->result_arr);
            }
            $rules = [ 
                'prescription'  => 'required',
                'doctor_name'   => 'required',
                'patient_id'    => 'required',
                'patient_name'  => 'required',
                'patient_puid'  => 'required',
                ];  
            if ($this->validate($rules)) {
                $prescription      = $this->request->getPost('prescription');
                $patient_name      = $this->request->getPost('patient_name');
                $patient_id        = $this->request->getPost('patient_id');
                $doctor_id         = $this->request->getPost('doctor_id');
                $patient_age       = $this->request->getPost('patient_age');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_gender    = $this->request->getPost('patient_gender');
                $patient_puid      = $this->request->getPost('patient_puid');
                $doctor_name       = $this->request->getPost('doctor_name');
                $education         = $this->request->getPost('education');
                $dr_specialization  = $this->request->getPost('dr_specialization');
                $doc_gender        = $this->request->getPost('doc_gender');
                $apmt_id           = $this->request->getPost('apmt_id');
                $pid               = $this->request->getPost('pid');
                $ref_by            = $this->request->getPost('ref_by');
                $patient_mobile    = $this->request->getPost('patient_mobile');
                $patient_email     = $this->request->getPost('patient_email');
                $disease_symptoms  = $this->request->getPost('disease_symptoms');
                $patient_type      = $this->request->getPost('patient_type');
                $status            = $this->request->getPost('status');
                
                if($status != "Admit") { $status = 'Prescribed'; }
                $this->prescription_data_arr = [
                    'prescription'        =>  $prescription,//
                    'prescription_detail' =>  $prescription, //
                    'patient_name'        =>  $patient_name,
                    'patient_id'          =>  $patient_id,
                    'doctor_id'           =>  $doctor_id,
                    'status'              =>  $status, 
                    'age'                 =>  $patient_age,
                    'gender'              =>  $patient_gender,
                    'puid'                =>  $patient_puid,
                    'doctor_name'         =>  $doctor_name,
                    'apmt_id'             =>  $apmt_id,
                    'pid'                 =>  $pid,
                    'ref_by'              =>  $ref_by,
                    'patient_mobile'      =>  $patient_mobile,
                    'patient_email'       =>  $patient_email,
                    'disease_symptoms'    =>  $disease_symptoms,
                    'patient_type'        =>  $patient_type,
                    'prescription_date'   =>  date('Y-m-d H:i:s'),
                    'created_at'          =>  date('Y-m-d H:i:s'),
                    'created_by'          =>  $this->admin_uid, //Harcoded for now
                ];
                //echo "<pre>";print_r($this->prescription_data_arr);die;
                //$status = $this->adminModel->Insertdata('prescription_history', $this->prescription_data_arr);
                //$last_insrt_id = $this->commonForAllModel->Insertdata_return_id('prescription_history', $this->prescription_data_arr);

                $this->rev_pat_args = ['id'     => $patient_id];
                $this->updt_revisit_patient_arr = [
                    'status'        => $status,
                    'updated_by'    => $this->admin_uid,
                    'updated_at'    => date('Y-m-d H:i:s')

                ];
                $this->fld = '';
                $this->fldval = '';
                $last_insrt_id = $this->commonForAllModel->return_inserted_id_n_update('prescription_history', $this->prescription_data_arr, 'revisit_patients', $this->rev_pat_args, $this->updt_revisit_patient_arr);
                
                if ((int) $last_insrt_id > 0 ) {
                    $this->return_dt_arr = ['prescription_id' => $last_insrt_id];
                    //$this->data['prescription'] = array(array());
                     $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'message' => 'Prescription added successfully',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr); 
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
                        'code' => 200,
                        'message' => 'Failed to add prescripton. Please try again.',
                        'data'      => $this->return_dt_arr,
                    ); 
                    return json_encode($this->result_arr);
                }
            }
            else{
                $this->result_arr = array(
                    'status' => false,
                    'error'     => false, //error: `false` showing validation error autoamtically
                    'code' => 200,
                    'message' => 'Validation failed. Please try again',
                    'data'      => $this->return_dt_arr,
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->admin_uid = session('doctor_session_uid');
        $selected_report=$this->request->getPost('selected_report');
        $selected_report_arr=explode(',',$selected_report);
        
        if($this->request->getMethod() == 'post') {
            if(count($selected_report_arr)>0) {
                foreach($selected_report_arr as $val) {
                    $this->report_data_arr = [
                        'patient_id'    =>  $pid,
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
                    'error'     => false, //error: `false` with status `true`
                    'code' => 200,
                    'data' > '',
                    'message' => 'Reports saved successfully'
                );
                return json_encode($this->result_arr);
            } 
            else {
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'error'     => true, //error: `true` whereever status is false with SQL err 
                'code' => 200,
                'data' > '',
                'message' => 'Sorry ! Required GET method'
            );
            return json_encode($this->result_arr);
        }
    } //Function - Closed

//  /* @param: 
//     * @desc: Upload Reports: Step - 3
//     * @use:
//     * @remark:
//     * @author: Neoarks Team
//     * @date:
//     * @modify:
//     */
    
    public function upload_rev_prescription_report($pid, $rid,$file_id, $prescription_id) { //$pid: patient_id, $rid : Report ID
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } 
        $this->admin_uid = session()->get('doctor_session_uid'); //Loggedin User uid
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
                    'pid'               => $pid,
                    'report_attachment' =>  $doc_img,
                    'prescription_id'   =>  $prescription_id,
                    //'ref_id'            =>  $ref_id,
                    'updated_at'      =>  date('Y-m-d H:i:s'),
                    'updated_by'      =>  $this->admin_uid,
                ];
                $args = ['id'   => $rid];
                //Need update model function in place of Insertdata - 
                $status = $this->adminModel->update_rec_by_args('patient_reports', $args, $this->report_data_arr);
                if ($status === true) {
                    $this->result_arr = array(
                        'status' => true,
                        'error'     => false, //error: `false` with status `true`
                        'code' => 200,
                        'data' > '',
                        'message' => 'Reports uploaded successfully'
                    );
                    return json_encode($this->result_arr);
                } 
                else {
                    $this->result_arr = array(
                        'status' => false,
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                // return redirect()->to(base_url() . '/Doctor/add_prescription');
                $this->result_arr = array(
                    'status' => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'error'     => true, //error: `true` whereever status is false with SQL err 
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
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }  
        else {
            $this->admin_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
            if(!isset($this->request_method) || $this->request_method != 'post') { 
                $this->result_arr = array(
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'advice'        =>  $advice,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                    'status'        => 'Prescribed',
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
                        'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => false, //error: `false` with status `true`
                            "code" => '200',
                            "message" => 'Advice added successfully'
                        ); 
                        return json_encode($this->result_arr); 
                    } 
                    else {
                        $this->result_arr = array(
                            "status" => false,
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                            'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
        if (!session()->has('doctor_session_uid')) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        } else {
            $this->admin_uid = session('doctor_session_uid');
            $this->request_method = $this->request->getMethod();
    
            if ($this->request_method != 'post') {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                    'error'     => true, //error: `true` whereever status is false with SQL err 
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
                'status'    => 'Prescribed',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->admin_uid,
                'recommendation' => $msg_frm_doc,
            ];
    
            $prescription_id = $this->adminModel->get_max_val('prescription_history', 'id');
    
            if ($prescription_id === false || empty($prescription_id[0]->id)) {
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => 'ID is missing. Please talk to admin'
                ]);
            }
    
            $status_history = $this->adminModel->update_rec_by_args('prescription_history', ['id' => $prescription_id[0]->id], $message_data);
    
            $status_patient = $this->adminModel->update_rec_by_args('patients', ['id' => $patient_id], ['status' => 'Prescribed']);
    
            if ($status_history === true && $status_patient === true) {
                return json_encode([
                    "status" => true,
                    'error'     => false, //error: `false` with status `true`
                    "code" => '200',
                    "message" => 'Recommendation added successfully'
                ]);
            } else {
                // Handle database errors
                $error_message = 'Failed to add Recommendation. Check the error logs for details.';
                log_message('error', $error_message);
    
                return json_encode([
                    "status" => false,
                    'error'     => true, //error: `true` whereever status is false with SQL err 
                    "code" => '200',
                    "message" => $error_message
                ]);
            }
        }
    }  // Function - Closed
    // public function edit_patients($id){
    //  if (!(session()->has('doctor_session_uid'))) {
    //      return redirect()->to(base_url() . "/Doctor_login/doctor_login");
    //  }$args = [
    //      'id'  => $id ];
    //  $data['patients'] = $this->adminModel->fetch_rec_by_args('patients', $args);
    //  $data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
    //  return view('Doctor/edit_patients', $data);
    // }

    /***************************** Prescritpion Section For Revisit Patient- END  *****************************/
    public function edit_revisit_patients($id){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
        }$args = [
            'id'  => $id ];
        $data['revisit_patients'] = $this->adminModel->fetch_rec_by_args('revisit_patients', $args);
        $data['doctors']  = $this->commonForAllModel->fetch_allrecords_bypage('doctor');
        //echo "<pre>";print_r($data['doctors']);die;
        return view('Doctor/edit_revisit_patients', $data);
    }

    public function update_revisit_patients($pid){
        if (!(session()->has('doctor_session_uid'))) {
            return redirect()->to(base_url() . "/Doctor_login/doctor_login");
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
        if ($status == true) {
            $this->session->setTempdata('success', 'Congratulation ! Patients Updated Successfully', 3);
        } else {
            $this->session->setTempdata('error', 'Sorry ! Unable to update  Patients  Try Again ?', 3);
        }
        //echo "<pre>";print_r($status);die;
        return redirect()->to(base_url() . 'Doctor/manage_revisit_patient');
    }


//----------------Number of function related to revisit patients end here-----------------//
} //Class- Closed 