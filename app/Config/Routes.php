<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes( );

//✅ A. Apply authCheck for login/register
//$routes->get('login', 'AuthController::login', ['filter' => 'authCheck']);
//$routes->get('register', 'AuthController::register', ['filter' => 'authCheck']);




/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false );
$routes->set404Override( );

// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.

$routes->setAutoRoute(false );
/* Blood_bank_donor/blood_donor_login
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
#$routes->get('Home/display_blank_page', 'Home::display_blank_page');
$routes->get('home/display_sample_page', 'Home::display_sample_page');

# ✅ Admin - Public routes — no filter
$routes->get('/Login', 'Login::index');    // show login form
$routes->post('/Login', 'Login::index');   // handle login submission

$routes->get('/mgt/', 'Admin::homemgt');
$routes->get('/', 'Home::index');
#Admin Logout Section
$routes->get('/Login/Logout', 'Login::Logout'); //Admin Logout


// ✅ ImportData - login must routings
// $routes->group('ImportData', ['filter' => 'adminAuth'], function($routes) {
//     $routes->get('importCSV/browse_wards_csv', 'ImportData::browse_wards');
//     $routes->post('importCSV/import_wards_csv', 'ImportData::import_wards');

// });

#Forget Password - rountes START
$routes->get('Admin/forget_password', 'Admin::forget_password'); //Render email input form
$routes->post('Admin/forget_password', 'Admin::forget_password'); //Send link to email
$routes->get('Admin/reset_password/(:any)', 'Admin::reset_password/$1'); //Render new/confirm pass form
$routes->post('Admin/reset_password/(:any)', 'Admin::reset_password/$1'); //Forget password
#Forget Password - rountes END

#$routes->get('Admin/edit_bed/(:any)', 'Admin::edit_bed/$1');
#$routes->post('Admin/update_bed/(:any)', 'Admin::update_bed/$1');
#$routes->post('Admin/revist_admission_process/(:any)', 'Admin::revist_admission_process/$1');
#$routes->get('Admin/change_cancelled_appointments_status', 'Admin::change_cancelled_appointments_status');
#$routes->get('Admin/filter_today_appointments/(:any)', 'Admin::filter_today_appointments/$1');
#$routes->get('Admin/render_payment_form/(:any)', 'Admin::render_payment_form/$1'); 
#$routes->get('Admin/permanent_del_patients_review/(:any)', 'Admin::permanent_del_patients_review/$1');
#$routes->get('Admin/add_patient_payment/(:any)', 'Admin::add_patient_payment/$1');
#$routes->post('Admin/add_patient_payment/(:any)', 'Admin::add_patient_payment/$1');
#$routes->post('/Admin/upload_department', 'Admin::upload_department');
#$routes->get('/Admin/add_doctor_by_admin', 'Admin::add_doctor_by_admin');
#$routes->get('Admin/index', 'Admin::index');
#Admin/Department/Manage_Department/Action/Delete-Section
#Admin/Department/Manage_Department/Action/InActive-Section
#Admin/Department/Manage_Department/Search_Section
#$routes->get('/Admin/search_department', 'Admin::search_department');
#Admin/Department/Manage_Department/Filter_Department/New_Department
#routes->get('/Admin/filter_department/new_department', 'Admin::filter_department::new_department'); 
#Admin/Department/Manage_Department/Filter_Department/Old_Department
#$routes->get('/Admin/filter_department/old_department', 'Admin::filter_department::old_department');
#$routes->get('/Admin/filter_department/(:any)', 'Admin::filter_department/$1');
#$routes->get('/Admin/manage_doctor_discharge_patients', 'Admin::manage_doctor_discharge_patients');
#$routes->get('Admin/filter_appointment/new_appointment', 'Admin::filter_appointment::new_appointment');
#$routes->get('Admin/filter_appointment/new_appointment', 'Admin::filter_appointment::new_appointment');

#Admin/Appointment/Today_Appointment/Filter_Appointment/old_appointment
#$routes->get('Admin/filter_appointment/old_appointment', 'Admin::filter_appointment::old_appointment');
#$routes->get('Admin/doctor_discharge_appointments', 'Admin::doctor_discharge_appointments');
#$routes->get('Admin/generate_patient_bill/(:any)', 'Admin::generate_patient_bill/$1');
#$routes->get('Admin/filter_doctor_account/new_doctor', 'Admin::filter_doctor_account::new_doctor');
#Admin/Accounts/Manage_Doctor_Account/Filter_Doctor_Section/Old_Doctor
#Can't find a route for 'get: Admin/change_doc_acc_status/21/InActive'.
#Admin/Accounts/Blood_Accountants/Create_Blood_Bank_Accounts
#$routes->get('Admin/filter_doctor_verification/(:any)', 'Admin::filter_doctor_verification/$1');
#Admin/Verification/Veiw_Accountant_Verification/Filter_Account/New_Account
#$routes->get('Admin/filter_accountant_verification/new_accountant', 'Admin::filter_accountant_verification::new_accountant');
#$routes->get('/Admin/all_sale_reports', 'Admin::all_sale_reports');
#$routes->get('/Medical_Accountant/add_customer_name', 'Admin::add_customer_name');
#$routes->get('Medical_Accountant/add_medicine_stock', 'Medical_Accountant::add_medicine_stock');
#$routes->get('Admin/discharge_summary/(:any)', 'Admin::discharge_summary/$1'); 
#$routes->post('Admin/edit_med_cat/(:any)', 'Admin::edit_med_cat/$1');
#$routes->post('Admin/update_edit_med_cat/(:any)', 'Admin::update_edit_med_cat/$1');
#Admin/Medical/Manage_Medicine_Category/Delete-Section
#Admin/Medical/Manage_Medicine_Category/Edit-Section
#$routes->get('Admin/edit_med_cat/(:any)', 'Admin::edit_med_cat/$1');

// ✅ Admin - login must routings
$routes->group('Admin', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/', 'Admin::index');         //Admin
    $routes->get('dashboard', 'Admin::index'); // /Admin/dashboard

    $routes->get('browse_wards_csv', 'ImportData::browse_wards');
    $routes->post('import_wards_csv', 'ImportData::import_wards');
    $routes->get('browse_beds_csv', 'ImportData::browse_beds');
    $routes->post('import_beds_csv', 'ImportData::import_beds');

    $routes->get('filter_frontdesk_verification/(:any)','Admin::filter_frontdesk_verification/$1');
    $routes->get('change_blog_status/(:any)', 'Admin::change_blog_status/$1');
    $routes->get('view_blog/(:any)', 'Admin::view_blog/$1');
    $routes->get('view/(:any)', 'Admin::view/$1');
    $routes->post('add_appointment_fee', 'Admin::add_appointment_fee');
    $routes->get('run_appointment_fee_form', 'Admin::run_appointment_fee_form');
    $routes->get('run_appointment_fee_form/(:any)', 'Admin::run_appointment_fee_form/$1');
    $routes->get('count_income/all', 'Admin::count_income/all'); 
    $routes->get('count_patients/all', 'Admin::count_patients/all');
    $routes->get('count_medical_income/all', 'Admin::count_medical_income/all');
    $routes->get('permanent_del_med_com/(:any)', 'Admin::permanent_del_med_com/$1');
    $routes->get('change_med_com_status/(:any)', 'Admin::change_med_com_status/$1'); 
    $routes->get('delete_med_com/(:any)', 'Admin::delete_med_com/$1'); 
    $routes->get('filter_medicine_com/(:any)', 'Admin::filter_medicine_com/$1');
    $routes->post('upload_profile_pic', 'Admin::upload_profile_pic');
    $routes->post('change_admin_password', 'Admin::change_admin_password'); 
    $routes->get('add_ward', 'Admin::add_ward'); 

    $routes->post('add_ward', 'Admin::add_ward');  
    $routes->get('manage_ward', 'Admin::manage_ward');
    $routes->post('manage_ward', 'Admin::manage_ward');
    $routes->post('search_ward', 'Admin::search_ward'); 
    $routes->get('search_ward', 'Admin::search_ward');
    $routes->get('filter_ward/(:any)', 'Admin::filter_ward/$1');
    $routes->get('edit_ward/(:any)', 'Admin::edit_ward/$1');
    $routes->post('update_ward/(:any)', 'Admin::update_ward/$1');
    $routes->post('update_ward', 'Admin::update_ward');
    $routes->get('add_bed', 'Admin::add_bed');    //For Rendering form 
    $routes->post('add_bed', 'Admin::add_bed');    //For Adding bed
    $routes->post('upload_bed', 'Admin::upload_bed'); 
    $routes->get('manage_bed', 'Admin::manage_bed'); 
    $routes->get('change_ward_status/(:any)', 'Admin::change_ward_status/$1');
    $routes->get('delete_ward/(:any)', 'Admin::delete_ward/$1');
    $routes->get('permanent_del_ward/(:any)', 'Admin::permanent_del_ward/$1');

    $routes->get('delete_frontdesk_account/(:any)', 'Admin::delete_frontdesk_account/$1');
    $routes->post('search_bed', 'Admin::search_bed');
    $routes->get('search_bed', 'Admin::search_bed');
    $routes->get('view_bed/(:any)', 'Admin::view_bed/$1');
    $routes->post('update_bed', 'Admin::update_bed');
    $routes->get('change_bed_status/(:any)', 'Admin::change_bed_status/$1');
    $routes->get('delete_bed/(:any)', 'Admin::delete_bed/$1');
    $routes->get('permanent_del_bed/(:any)', 'Admin::permanent_del_bed/$1');
    $routes->get('filter_bed/(:any)', 'Admin::filter_bed/$1');

    $routes->post('upload_summary', 'Admin::upload_summary'); 
    $routes->post('upload_summary/(:any)', 'Admin::upload_summary/$1');
    $routes->get('generate_initial_pay_bill/(:any)', 'Admin::generate_initial_pay_bill/$1');
    $routes->get('save_fee/(:any)', 'Admin::save_fee/$1');
    $routes->get('add_fee/(:any)', 'Admin::add_fee/$1');
    $routes->post('save_fee/(:any)', 'Admin::save_fee/$1');
    $routes->get('revisit_get_dept_for_admission/(:any)', 'Admin::revisit_get_dept_for_admission/$1');
    $routes->get('get_dept_for_rev_admission/(:any)', 'Admin::get_dept_for_rev_admission/$1');
    
    $routes->post('revisit_admission_process', 'Admin::revisit_admission_process');
    $routes->post('get_wards_for_admission/(:any)', 'Admin::get_wards_for_admission/$1');
    $routes->get('get_dept_for_admission', 'Admin::get_dept_for_admission');
    $routes->get('get_dept_for_admission/(:any)', 'Admin::get_dept_for_admission/$1');
    $routes->post('get_beds_for_admission/(:any)', 'Admin::get_beds_for_admission/$1');
    $routes->get('change_cancelled_appointments_status/(:any)', 'Admin::change_cancelled_appointments_status/$1'); 
    $routes->get('change_cancelled_appointments_status/(:any)/(:any)', 'Admin::change_cancelled_appointments_status/$1/$2'); 
    $routes->get('manage_revisited_patients', 'Admin::manage_revisited_patients');
    $routes->get('manage_revisited_patients/(:any)', 'Admin::manage_revisited_patients/$1'); 
    $routes->get('show_patient_final_payments/(:any)', 'Admin::show_patient_final_payments/$1'); 
    $routes->post('show_patient_final_payments/(:any)', 'Admin::show_patient_final_payments/$1'); 
    $routes->get('show_revisit_patient_final_payments/(:any)', 'Admin::show_revisit_patient_final_payments/$1'); 
    $routes->post('show_revisit_patient_final_payments/(:any)', 'Admin::show_revisit_patient_final_payments/$1'); 

    $routes->post('clear_final_dues/(:any)', 'Admin::clear_final_dues/$1'); 
    $routes->get('handle_final_payment/(:any)', 'Admin::handle_final_payment/$1'); 
    $routes->get('generate_clear_dues_bill/(:any)', 'Admin::generate_clear_dues_bill/$1');
    $routes->post('clear_revisit_final_dues/(:any)', 'Admin::clear_revisit_final_dues/$1');  
    $routes->get('generate_revisit_clear_dues_bill/(:any)', 'Admin::generate_revisit_clear_dues_bill/$1'); 
    $routes->get('edit_admin_profile/(:any)', 'Admin::edit_admin_profile/$1'); 
    $routes->get('view_profile', 'Admin::view_profile'); 
    $routes->post('update_profile', 'Admin::update_profile'); 
    $routes->get('delete_blogs/(:any)', 'Admin::delete_blogs/$1');
    $routes->get('delete_revisit_patients/(:any)', 'Admin::delete_revisit_patients/$1');
    $routes->get('delete_review/(:any)', 'Admin::delete_review/$1');
    $routes->post('save_message', 'Admin::save_message');

    $routes->post('save_revisit_pat_message', 'Admin::save_revisit_pat_message');
    $routes->post('Login/get_doctor_data/(:any)', 'Login::get_doctor_data/$1');
    $routes->get('change_manage_all_discharged_patient_status/(:any)', 'Admin::change_manage_all_discharged_patient_status/$1');
    $routes->post('search_blood_account/(:any)', 'Admin::search_blood_account/$1');
    $routes->post('search_blood_account', 'Admin::search_blood_account');
    $routes->get('search_blood_account', 'Admin::search_blood_account');
    $routes->get('change_revisit_patients_status/(:any)', 'Admin::change_revisit_patients_status/$1');

    $routes->get('Change_cancelled_appointment_status/(:any)', 'Admin::Change_cancelled_appointment_status/$1'); 
    $routes->get('change_blood_acc_status/(:any)', 'Admin::change_blood_acc_status/$1'); 
    $routes->post('search_all_appointments', 'Admin::search_all_appointments');
    $routes->get('search_all_appointments', 'Admin::search_all_appointments');
    $routes->post('search_today_appointments', 'Admin::search_today_appointments');
    $routes->get('search_today_appointments', 'Admin::search_today_appointments');
    $routes->post('search_canceled_appointments', 'Admin::search_canceled_appointments');
    $routes->get('search_canceled_appointments', 'Admin::search_canceled_appointments');
    $routes->get('home', 'Admin::homemgt');
    $routes->get('manage_doctor', 'Admin::manage_doctor');
    $routes->get('manage_doc_dis_patient', 'Admin::manage_doc_dis_patient');
    $routes->get('delete_medicine/(:any)', 'Admin::delete_medicine/$1');
    $routes->get('delete_bld_admin_account/(:any)', 'Admin::delete_bld_admin_account/$1');
    $routes->get('delete_bld_acc_account/(:any)', 'Admin::delete_bld_acc_account/$1');
    $routes->get('manage_department', 'Admin::manage_department');
    $routes->get('manage_patients', 'Admin::manage_patients');

    $routes->post('save_doctor', 'Admin::save_doctor');
    $routes->get('add_doctor', 'Admin::add_doctor');
    $routes->get('add_doctor_fee', 'Admin::add_doctor_fee');
    $routes->get('add_hospital_expenses/(:any)', 'Admin::add_hospital_expenses/$1');
    $routes->post('save_hospital_expenses', 'Admin::save_hospital_expenses'); //Ajax call
    $routes->post('upload_doctor_fee', 'Admin::upload_doctor_fee');
    $routes->get('upload_doctor_fee', 'Admin::upload_doctor_fee');
    $routes->get('manage_doctor_fee', 'Admin::manage_doctor_fee');
    $routes->post('manage_doctor_fee', 'Admin::manage_doctor_fee');
    $routes->get('add_department', 'Admin::add_department');
    $routes->post('add_department', 'Admin::add_department');

    $routes->get('manage_slider', 'Admin::manage_slider');
    $routes->get('edit_slider_image/6', 'Admin::edit_slider_image/6');
    $routes->post('update_slider_image', 'Admin::update_slider_image');
    $routes->post('update_stock/(:any)', 'Admin::update_stock/$1');
    $routes->post('update_medicines/(:any)', 'Admin::update_medicines/$1');
    $routes->get('change_accountant_status/(:any)', 'Admin::change_accountant_status/$1');
    $routes->get('change_feedback_status/(:any)', 'Admin::change_feedback_status/$1');
    $routes->get('change_bld_admin_acc/(:any)', 'Admin::change_bld_admin_acc/$1');
    $routes->get('change_frontdesk_status/(:any)', 'Admin::change_frontdesk_status/$1');

    $routes->get('change_medical_acc_status/(:any)', 'Admin::change_medical_acc_status/$1');
    $routes->get('discharge_apmnt_patient/(:any)', 'Admin::discharge_apmnt_patient/$1');
    $routes->get('add_received_payment/(:any)', 'Admin::add_received_payment/$1'); 
    $routes->post('add_received_payment/(:any)', 'Admin::add_received_payment/$1');
    $routes->get('add_revisit_patient_payment/(:any)', 'Admin::add_revisit_patient_payment/$1');
    $routes->post('add_revisit_patient_payment/(:any)', 'Admin::add_revisit_patient_payment/$1');

    $routes->get('terms_conditions_list', 'Admin::terms_conditions_list');
    $routes->get('terms_conditions_edit', 'Admin::terms_conditions_edit'); 
    $routes->get('add_term_condition',    'Admin::add_term_condition');
    $routes->post('add_term_condition',    'Admin::add_term_condition'); 
    $routes->post('Upload_terms_condition',    'Admin::Upload_terms_condition');
    $routes->get('delete_acc_account/(:any)', 'Admin::delete_acc_account/$1');
    $routes->get('filter_gallery/(:any)', 'Admin::filter_gallery/$1');
    $routes->get('filter_today_appointment/(:any)', 'Admin::filter_today_appointment/$1');

    $routes->post('search_medical_account', 'Admin::search_medical_account');
    $routes->get('search_medical_account', 'Admin::search_medical_account');
    $routes->get('search_medical_account', 'Admin::search_medical_account');
    $routes->post('search_results', 'Admin::search_results'); 
    $routes->get('search_results', 'Admin::search_results'); 
    $routes->post('get_doctor_fee_details/(:any)', 'Admin::get_doctor_fee_details/$1');
    $routes->get('add_prescription/(:any)', 'Admin::add_prescription/$1');
    #$routes->post('add_prescription/(:any)', 'Admin::add_prescription/$1');
    $routes->post('upload_prescription/(:any)', 'Admin::upload_prescription/$1');
    $routes->post('upload_prescription', 'Admin::upload_prescription'); 

    $routes->post('add_appointment_pat_charge/(:any)', 'Admin::add_appointment_pat_charge/$1');
    $routes->get('add_appointment_pat_charge/(:any)', 'Admin::add_appointment_pat_charge/$1');
    $routes->post('add_prescription_report/(:any)', 'Admin::add_prescription_report/$1');
    $routes->post('add_prescription_report', 'Admin::add_prescription_report'); 
    $routes->post('upload_prescription_report/(:any)', 'Admin::upload_prescription_report/$1');
    $routes->post('add_appointment_pat_charge/(:any)', 'Admin::add_appointment_pat_charge/$1');
    $routes->get('add_revisit_prescription/(:any)', 'Admin::add_revisit_prescription/$1');
    $routes->post('upload_revisit_prescription/(:any)', 'Admin::upload_revisit_prescription/$1');
    $routes->post('upload_revisit_prescription', 'Admin::upload_revisit_prescription'); 
    $routes->post('add_rev_prescription_report/(:any)', 'Admin::add_rev_prescription_report/$1');
    $routes->post('add_rev_prescription_report', 'Admin::add_rev_prescription_report'); 
    $routes->post('upload_rev_prescription_report/(:any)', 'Admin::upload_rev_prescription_report/$1');
    $routes->get('delete_today_dis_patients/(:any)', 'Admin::delete_today_dis_patients/$1');

    $routes->get('filter_med_account/(:any)', 'Admin::filter_med_account/$1');
    $routes->get('all_appointments', 'Admin::all_appointments/');
    $routes->get('change_all_appointments_status/(:any)/(:any)', 'Admin::change_all_appointments_status/$1/$2');
    $routes->get('filter_bld_account/(:any)', 'Admin::filter_bld_account/$1');
    $routes->get('doctors_available_slots_neo/(:any)', 'Admin::doctors_available_slots_neo/$1');
    $routes->get('doctors_available_slots/(:any)', 'Admin::doctors_available_slots/$1');
    $routes->get('pick_slots', 'Admin::pick_slots');  // GET request
    #$routes->post('pick_slots', 'Admin::pick_slots'); // Book Appointment - Admin: POST request
    #$routes->post('pick_slots/(:any)', 'Admin::pick_slots/$1'); // POST with param

    $routes->post('patients_search_lookup', 'Admin::patients_search_lookup');
    $routes->post('book_appointment', 'Admin::book_appointment');
    $routes->get('available_selected_doctor_slots', 'Admin::available_selected_doctor_slots'); 
    $routes->get('payment_dischrge_all_patient/(:any)', 'Admin::payment_dischrge_all_patient/$1'); 
    $routes->get('policy', 'Admin::policy'); 
    $routes->get('permanent_del_department/(:any)', 'Admin::permanent_del_department/$1'); 

    $routes->get('permanent_del_doctor/(:any)', 'Admin::permanent_del_doctor/$1'); 
    $routes->get('permanent_del_doctor_fee/(:any)', 'Admin::permanent_del_doctor_fee/$1'); 
    $routes->get('permanent_del_patients/(:any)', 'Admin::permanent_del_patients/$1'); 
    $routes->get('permanent_del_med_cat/(:any)', 'Admin::permanent_del_med_cat/$1'); 
    $routes->get('permanent_del_revisit_patients/(:any)', 'Admin::permanent_del_revisit_patients/$1'); 
    $routes->get('permanent_del_med_cat/(:any)', 'Admin::permanent_del_med_cat/$1'); 
    $routes->get('permanent_del_medicine/(:any)', 'Admin::permanent_del_medicine/$1'); 
    $routes->get('permanent_del_doc_acc/(:any)', 'Admin::permanent_del_doc_acc/$1'); 
    $routes->get('permanent_del_acc_account/(:any)', 'Admin::permanent_del_acc_account/$1'); 
    $routes->get('permanent_del_bld_acc_account/(:any)', 'Admin::permanent_del_bld_acc_account/$1'); 
    $routes->get('permanent_del_blogs/(:any)', 'Admin::permanent_del_blogs/$1'); 


    $routes->get('permanent_del_all_apmnt/(:any)', 'Admin::permanent_del_all_apmnt/$1'); 
    $routes->get('permanent_del_today_apmnt/(:any)', 'Admin::permanent_del_today_apmnt/$1'); 
    $routes->get('permanent_del_today_patients/(:any)', 'Admin::permanent_del_today_patients/$1'); 
    $routes->get('permanent_del_all_Apmnt_dsbd/(:any)', 'Admin::permanent_del_all_Apmnt_dsbd/$1'); 
    $routes->get('permanent_del_manage_slider/(:any)', 'Admin::permanent_del_manage_slider/$1'); 
    $routes->get('permanent_del_gallery_image/(:any)', 'Admin::permanent_del_gallery_image/$1'); 
    $routes->get('filter_admitted_pat/(:any)', 'Admin::filter_admitted_pat/$1'); 
    $routes->get('filter_admitted_pat/(:any)', 'Admin::filter_admitted_pat/$1'); 

    $routes->get('edit_department/(:any)', 'Admin::edit_department/$1');
    $routes->post('update_department/(:any)', 'Admin::update_department/$1');
    $routes->get('delete_department/(:any)', 'Admin::delete_department/$1');
    $routes->get('change_department_status/(:any)', 'Admin::change_department_status/$1');
    $routes->post('search_department', 'Admin::search_department');
    $routes->get('search_department', 'Admin::search_department');
    $routes->get('change_admin_password', 'Admin::change_admin_password');
    $routes->get('filter_department/(:any)', 'Admin::filter_department/$1');
    $routes->get('filter_deleted_pat/(:any)', 'Admin::filter_deleted_pat/$1'); 

    $routes->get('edit_doctor/(:any)', 'Admin::edit_doctor/$1');
    $routes->post('search_doctor', 'Admin::search_doctor');
    $routes->get('search_doctor', 'Admin::search_doctor');
    $routes->get('edit_doctor/(:any)', 'Admin::edit_doctor/$1');
    $routes->post('edit_doctor/(:any)', 'Admin::edit_doctor/$1');
    $routes->post('update_doctor/(:any)', 'Admin::update_doctor/$1');
    $routes->get('update_doctor/(:any)', 'Admin::update_doctor/$1');

    $routes->get('delete_doctor/(:any)', 'Admin::delete_doctor/$1');
    $routes->get('change_doctor_status/(:any)', 'Admin::change_doctor_status/$1');
    $routes->get('doctor', 'Admin::doctor');
    $routes->get('filter_doctor/(:any)', 'Admin::filter_doctor/$1');
    $routes->post('search_doctor_fee', 'Admin::search_doctor_fee');
    $routes->get('search_doctor_fee', 'Admin::search_doctor_fee');
    $routes->get('filter_doctor_fee/(:any)', 'Admin::filter_doctor_fee/$1');
    $routes->get('admission_process/(:any)', 'Admin::admission_process/$1');
    $routes->post('admission_process/(:any)', 'Admin::admission_process/$1');
    $routes->post('admission_process', 'Admin::admission_process'); 
    $routes->post('save_advice', 'Admin::save_advice');
    $routes->post('save_revisit_pat_advice', 'Admin::save_revisit_pat_advice');
    $routes->get('edit_doctor_fee/(:any)', 'Admin::edit_doctor_fee/$1');
    $routes->post('edit_doctor_fee/(:any)', 'Admin::edit_doctor_fee/$1');
    $routes->post('update_doctor_fee/(:any)', 'Admin::update_doctor_fee/$1');
    $routes->get('delete_doctor_fee/(:any)', 'Admin::delete_doctor_fee/$1');
    $routes->get('change_doctor_fee_status/(:any)', 'Admin::change_doctor_fee_status/$1');

    $routes->post('fetch_patients', 'Admin::fetch_patients');
    $routes->get('fetch_patients', 'Admin::fetch_patients');
    $routes->post('upload_patients', 'Admin::upload_patients');
    $routes->get('upload_patients', 'Admin::upload_patients');
    $routes->post('search_patient', 'Admin::search_patient');
    $routes->get('search_patient', 'Admin::search_patient'); 
    $routes->get('edit_patients/(:any)', 'Admin::edit_patients/$1');
    $routes->post('edit_patients/(:any)', 'Admin::edit_patients/$1');
    $routes->get('edit_revisit_patients/(:any)', 'Admin::edit_revisit_patients/$1');
    $routes->post('edit_revisit_patients/(:any)', 'Admin::edit_revisit_patients/$1');

    $routes->post('update_patients/(:any)', 'Admin::update_patients/$1');
    $routes->post('update_revisit_patients/(:any)', 'Admin::update_revisit_patients/$1');
    $routes->get('delete_patients/(:any)', 'Admin::delete_patients/$1');
    $routes->get('change_patients_status/(:any)', 'Admin::change_patients_status/$1');
    $routes->get('admit_patient/(:any)', 'Admin::admit_patient/$1'); //Render form
    $routes->get('admit_revisit_patient/(:any)', 'Admin::admit_revisit_patient/$1');
    $routes->post('save_admission_fee/(:any)', 'Admin::save_admission_fee/$1');
    $routes->post('save_revisit_admission_fee/(:any)', 'Admin::save_revisit_admission_fee/$1');
    $routes->get('generate_admission_bill/(:any)', 'Admin::generate_admission_bill/$1');
    $routes->get('generate_revisit_admission_bill/(:any)', 'Admin::generate_revisit_admission_bill/$1');
    $routes->get('add_revisit_hospital_expenses/(:any)', 'Admin::add_revisit_hospital_expenses/$1');

    $routes->get('print_slip/(:any)', 'Admin::print_slip/$1');
    $routes->get('revisit_patients/(:any)', 'Admin::revisit_patients/$1');
    $routes->post('update_revisit_patient/(:any)', 'Admin::update_revisit_patient/$1');
    $routes->get('number_of_visit_patients/(:any)', 'Admin::number_of_visit_patients/$1');
    $routes->get('change_manage_today_discharged_patient_status/(:any)', 'Admin::change_manage_today_discharged_patient_status/$1');
    $routes->get('accountant_verification', 'Admin::accountant_verification');
    $routes->get('frontdesk_verification', 'Admin::frontdesk_verification');
    $routes->get('patient_verification', 'Admin::patient_verification');
    $routes->get('filter_accountant_verification/(:any)','Admin::filter_accountant_verification/$1');
    $routes->get('filter_accountant_verification/(:any)','Admin::filter_accountant_verification/$1');
    $routes->get('filter_accountant_verification/(:any)','Admin::filter_accountant_verification/$1');
    $routes->get('filter_doctor_verification/(:any)', 'Admin::filter_doctor_verification/$1');  
    $routes->get('delete_all_dis_patients/(:any)', 'Admin::delete_all_dis_patients/$1'); 
    $routes->get('delete_all_appointments/(:any)', 'Admin::delete_all_appointments/$1');
    $routes->get('delete_accountant_account/(:any)', 'Admin::delete_accountant_account/$1');
    $routes->get('delete_doc_account/(:any)', 'Admin::delete_doc_account/$1');
    $routes->get('doctor_email_register', 'Admin::doctor_email_register');
    $routes->get('patients_review', 'Admin::patients_review');
    $routes->get('filter_feedback/(:any)', 'Admin::filter_feedback/$1');
    $routes->get('doctor_verification', 'Admin::doctor_verification');
    $routes->get('blood_bank_admin', 'Admin::blood_bank_admin');

    $routes->get('today_appointments', 'Admin::today_appointments');
    $routes->get('change_today_appointments_status/(:any)', 'Admin::change_today_appointments_status/$1');
    $routes->get('change_today_discharged_patient_status/(:any)', 'Admin::change_today_discharged_patient_status/$1');

    $routes->get('today_appointment', 'Admin::today_appointment');
    $routes->get('change_Appointment_status/(:any)', 'Admin::change_Appointment_status/$1');
    $routes->get('change_appointment_status_dsbrd/(:any)', 'Admin::change_appointment_status_dsbrd/$1');
    $routes->get('delete_appointment/(:any)', 'Admin::delete_appointment/$1');
    $routes->get('delete_cancelled_appointment/(:any)', 'Admin::delete_cancelled_appointment/$1');
    $routes->get('delete_appointment_dsbrd/(:any)', 'Admin::delete_appointment_dsbrd/$1'); 
    $routes->get('filter_appointment/(:any)', 'Admin::filter_appointment/$1');
    $routes->get('filter_appointment/(:any)', 'Admin::filter_appointment/$1');
    $routes->get('canceled_appointments', 'Admin::canceled_appointments');
    $routes->get('filter_appointment_patient/(:any)', 'Admin::filter_appointment_patient/$1');
    $routes->get('filter_del_appointments/(:any)', 'Admin::filter_del_appointments/$1');
       
    $routes->get('manage_doctor_account', 'Admin::manage_doctor_account');    
    $routes->post('search_doctor_account', 'Admin::search_doctor_account');    
    $routes->get('filter_doctor_account/(:any)', 'Admin::filter_doctor_account/$1');
    $routes->get('filter_doctor_account/old_doctor', 'Admin::filter_doctor_account::old_doctor');
    $routes->get('delete_doctor_account/(:any)', 'Admin::delete_doctor_account/$1');
    $routes->get('change_doc_acc_status/(:any)', 'Admin::change_doc_acc_status/$1');
    $routes->get('change_doctor_acc_status/(:any)', 'Admin::change_doctor_acc_status/$1');
    $routes->get('manage_medical_acc', 'Admin::manage_medical_acc'); 
    $routes->get('blood_bank_accountant', 'Admin::blood_bank_accountant');
    $routes->get('manage_blood_acc', 'Admin::manage_blood_acc');
    $routes->get('check_login_activity', 'Admin::check_login_activity');
    $routes->get('manage_blood_accc', 'Admin::manage_blood_accc');

    $routes->get('add_slider_image', 'Admin::add_slider_image'); 
    $routes->post('publish_slider_image', 'Admin::publish_slider_image'); 
    $routes->get('edit_slider_image/(:any)', 'Admin::edit_slider_image/$1');
    $routes->post('update_slider_image/(:any)', 'Admin::update_slider_image/$1');
    $routes->get('delete_slider/(:any)', 'Admin::delete_slider/$1');

    $routes->get('change_slider_image_status/(:any)', 'Admin::change_slider_image_status/$1');
    $routes->get('image_gallery', 'Admin::image_gallery');
    $routes->post('image_gallery', 'Admin::image_gallery');
    $routes->get('manage_image_gallery', 'Admin::manage_image_gallery');
    $routes->get('delete_gallery_image/(:any)', 'Admin::delete_gallery_image/$1');
    $routes->get('change_gallery_img_status/(:any)', 'Admin::change_gallery_img_status/$1');
    $routes->get('change_privacy_policy_status/(:any)', 'Admin::change_privacy_policy_status/$1');
    $routes->get('manage_blogs', 'Admin::manage_blogs'); 
    $routes->get('add_blogs', 'Admin::add_newsblog'); 
    $routes->post('save_blogs', 'Admin::save_newsblog');
    $routes->get('filter_blogs/(:any)', 'Admin::filter_blogs/$1');
    $routes->get('filter_blogs/(:any)', 'Admin::filter_blogs::old_blogs');  
    $routes->get('contact_us', 'Admin::contact_us');
    $routes->get('all_sale_reports', 'Admin::all_sale_reports');
    $routes->get('today_medical_cus_report', 'Admin::today_medical_cus_report');
    $routes->get('add_summary', 'Admin::add_summary');
    $routes->get('todays_sale_records', 'Admin::todays_sale_records');
    $routes->post('search_sales', 'Admin::search_sales');
    $routes->get('all_sale_reports', 'Admin::all_sale_reports');
    $routes->get('filter_patients/(:any)', 'Admin::filter_patients/$1');
    $routes->get('filter_patients_dis_pat/(:any)', 'Admin::filter_patients_dis_pat/$1'); 
    $routes->get('manage_all_discharged_patient', 'Admin::manage_all_discharged_patient');
    $routes->get('manage_today_discharged_patient', 'Admin::manage_today_discharged_patient');
    $routes->get('manage_today_discharged_patient/(:any)', 'Admin::manage_today_discharged_patient/$1');

    $routes->post('search_doc_dis_patient', 'Admin::search_doc_dis_patient');
    $routes->get('search_doc_dis_patient', 'Admin::search_doc_dis_patient');
    $routes->get('discharge_revisit_patients/(:any)', 'Admin::discharge_revisit_patients/$1');
    $routes->get('filter_doc_dis_patients/(:any)', 'Admin::filter_doc_dis_patients/$1');    
    $routes->get('manage_discharge_patient', 'Admin::manage_discharge_patient');
    $routes->post('search_discharge_patient', 'Admin::search_discharge_patient');
    $routes->get('search_discharge_patient', 'Admin::search_discharge_patient');
    $routes->get('filter_dischrage_patient/(:any)', 'Admin::filter_dischrage_patient/$1');
    $routes->get('filter_dischrage_pat/(:any)', 'Admin::filter_dischrage_pat/$1');
    $routes->get('filter_dischrage_pat', 'Admin::filter_dischrage_pat');
    $routes->get('manage_revisit_patient', 'Admin::manage_revisit_patient');
    $routes->post('search_revisit_patient', 'Admin::search_revisit_patient');
    $routes->get('filter_revisit_patient/(:any)', 'Admin::filter_revisit_patient/$1');

    
    $routes->get('add_med_company', 'Admin::add_med_company');
    $routes->post('add_med_company', 'Admin::add_med_company');
    $routes->get('manage_med_company', 'Admin::manage_med_company');

    $routes->get('med_category', 'Admin::med_category');
    $routes->post('add_med_category', 'Admin::add_med_category');
    $routes->get('add_med_category', 'Admin::add_med_category');
    $routes->get('manage_med_category', 'Admin::manage_med_category');
    $routes->get('manage_med_category/(:any)', 'Admin::manage_med_category/$1');
    $routes->post('search_medicines_company', 'Admin::search_medicines_company');   //Custom

    $routes->post('search_medicines', 'Admin::search_medicines');
    $routes->get('search_medicines', 'Admin::search_medicines');
    $routes->get('search_medicine', 'Admin::search_medicine');
    $routes->post('search_medicine', 'Admin::search_medicine');
    $routes->get('filter_medicine_cat/(:any)', 'Admin::filter_medicine_cat/$1');

    $routes->get('edit_med_company/(:any)', 'Admin::edit_med_company/$1');
    $routes->post('update_med_company/(:any)', 'Admin::update_med_company/$1');
    $routes->get('edit_med_cat/(:any)', 'Admin::edit_med_cat/$1');
    $routes->post('update_med_category/(:any)', 'Admin::update_med_category/$1');
    $routes->get('search_doctor_account', 'Admin::search_doctor_account'); 
    $routes->get('edit_medicine/(:any)', 'Admin::edit_medicine/$1');

    $routes->get('delete_med_cat/(:any)', 'Admin::delete_med_cat/$1');
    

    $routes->get('change_med_cat_status/(:any)', 'Admin::change_med_cat_status/$1');
    $routes->get('change_medicine_status/(:any)', 'Admin::change_medicine_status/$1');  
    $routes->get('add_medicine', 'Admin::add_medicine');
    $routes->get('add_medicine_stock/(:any)', 'Admin::add_medicine_stock/$1');
    $routes->post('upload_medicine', 'Admin::upload_medicine');
    $routes->get('upload_medicine', 'Admin::upload_medicine');    
    $routes->get('manage_medicine', 'Admin::manage_medicine');
    $routes->get('discharge_patients/(:any)', 'Admin::discharge_patients/$1'); 
    $routes->post('add_patient_charge/(:any)', 'Admin::add_patient_charge/$1'); 
    $routes->get('generate_patient_bill/(:any)', 'Admin::generate_patient_bill/$1'); 
    $routes->get('generate_receive_payment_bill/(:any)', 'Admin::generate_receive_payment_bill/$1'); 
    $routes->get('generate_revisit_patient_bill/(:any)', 'Admin::generate_revisit_patient_bill/$1'); 
    $routes->post('save_revisit_hospital_expenses', 'Admin::save_revisit_hospital_expenses');
    $routes->get('payment_dischrge_patient/(:any)', 'Admin::payment_dischrge_patient/$1'); 
    $routes->get('discharge_summary', 'Admin::discharge_summary');
    $routes->get('filter_medicine/(:any)', 'Admin::filter_medicine/$1');
    $routes->get('manage_medicine/(:any)', 'Admin::manage_medicine/$1');
    
    
}); #********************* Admin Routes - END ************************************



# 🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺 Doctor - PUBLIC routes — no filter 🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺
$routes->get('Doctor_login/doctor_login', 'Doctor_login::doctor_login');
$routes->post('Doctor_login/doctor_login', 'Doctor_login::doctor_login');
$routes->get('/Doctor_login/create_doc_account', 'Doctor_login::create_doc_account');
$routes->get('Doctor_login/login_account', 'Doctor_login::login_account');
$routes->post('/Doctor_login/create_doc_account', 'Doctor_login::create_doc_account');

$routes->get('/Doctor_login', 'Doctor_login::index');
$routes->get('Doctor_login/index', 'Doctor_login::index');
$routes->get('Doctor_login/Logout_doctor', 'Doctor_login::Logout_doctor'); 

#Forget Password - rountes START
$routes->get('Doctor_login/forget_password', 'Doctor_login::forget_password'); //Render email input form
$routes->post('Doctor_login/forget_password', 'Doctor_login::forget_password'); //Send link to email
$routes->get('Doctor_login/reset_password/(:any)', 'Doctor_login::reset_password/$1'); //Render new/confirm pass form
$routes->post('Doctor_login/reset_password/(:any)', 'Doctor_login::reset_password/$1'); //Forget password
#Forget Password - rountes END

$routes->get('Doctor/doctors_available_slots/(:any)', 'Doctor::doctors_available_slots/$1');  
$routes->post('Doctor/doctors_available_slots/(:any)', 'Doctor::doctors_available_slots/$1');

$routes->get('Doctor/available_selected_doctor_slots', 'Doctor::available_selected_doctor_slots'); 

#$routes->get('Doctor/pick_slots', 'Doctor::pick_slots'); ized - Pick Dr. Availabilities
#$routes->post('Doctor/pick_slots', 'Doctor::pick_slots'); ized - Pick Dr. Availabilities
#On Slot Selection save by ajax
#$routes->post('Doctor/SaveSlotAjaxNeo/(:any)', 'Doctor::SaveSlotAjaxNeo/$1');
#On Slot Selection save by ajax
#$routes->post('Doctor/SaveSlotAjax/(:any)', 'Doctor::SaveSlotAjax/$1'); 
#$routes->post('Doctor/DelSlotAjax/(:any)', 'Doctor::DelSlotAjax/$1'); //On Slot De-selection Del-ajax
#On Slot De-selection Del-ajax
#$routes->post('Doctor/DelSlotAjaxNeo/(:any)', 'Doctor::DelSlotAjaxNeo/$1'); 
#$routes->post('Doctor/save_slots', 'Doctor::save_slots'); ized - Set Dr. Availabilities
#$routes->get('Doctor/add_newsblog', 'Doctor::add_newsblog');
#$routes->post('Doctor/Upload_blog', 'Doctor::Upload_blog');
#$routes->post('Doctor/change_appointment_status', 'Doctor::change_appointment_status');
#$routes->get('Doctor/show_dr_available_slots', 'Doctor::show_dr_available_slots');
#$routes->get('Doctor/show_dr_available_slots/(:any)', 'Doctor::show_dr_available_slots/$1'); 
#$routes->get('/Doctor_login/create_doc_account', 'Doctor_login::create_doc_account');



# 🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺 Doctor - login must routings 🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺🩺
$routes->group('Doctor', ['filter' => 'doctorAuth'], function($routes) {
    $routes->get('/', 'Doctor::index');
    $routes->get('all_appointments', 'Doctor::all_appointments');
    $routes->post('SaveSlotAjaxNeo', 'Doctor::SaveSlotAjaxNeo'); //On Slot Selection save by ajax
    $routes->post('DelSlotAjaxNeo', 'Doctor::DelSlotAjaxNeo'); //On Slot De-selection Del-ajax
    $routes->post('Doctor_login/reset_password/(:any)', 'Doctor_login::reset_password/$1');
    $routes->get('Doctor_login/reset_password/(:any)', 'Doctor_login::reset_password/$1');
    
    $routes->post('/Doctor_login/create_doc_account', 'Doctor_login::create_doc_account');
    $routes->get('permanent_del_blogs/(:any)', 'Doctor::permanent_del_blogs/$1');
    $routes->get('admission_process/(:any)', 'Doctor::admission_process/$1');
    $routes->post('admission_process/(:any)', 'Doctor::admission_process/$1');
    $routes->post('admission_process', 'Doctor::admission_process'); 


    $routes->get('add_fee/(:any)', 'Doctor::add_fee/$1');
    $routes->post('add_prescription_report/(:any)', 'Doctor::add_prescription_report/$1');
    $routes->post('add_prescription_report', 'Doctor::add_prescription_report'); 
    $routes->post('add_today_pat_prescription_report/(:any)', 'Doctor::add_today_pat_prescription_report/$1');
    $routes->post('add_today_pat_prescription_report', 'Doctor::add_today_pat_prescription_report'); 
    $routes->get('generate_admission_bill/(:any)', 'Doctor::generate_admission_bill/$1'); //Custom 
    $routes->post('save_admission_fee/(:any)', 'Doctor::save_admission_fee/$1'); 
    $routes->post('save_today_admission_fee/(:any)', 'Doctor::save_today_admission_fee/$1'); 
    $routes->get('admit_patient/(:any)', 'Doctor::admit_patient/$1'); 
    
    $routes->get('change_all_patients_status/(:any)', 'Doctor::change_all_patients_status/$1');
    $routes->get('change_today_patients_status/(:any)', 'Doctor::change_today_patients_status/$1');
    $routes->get('today_patients', 'Doctor::today_patients');
    $routes->get('all_patients', 'Doctor::all_patients');
    $routes->get('today_appointments', 'Doctor::today_appointments');
    $routes->get('total_discharge_patient/(:any)', 'Doctor::total_discharge_patient/$1');
    $routes->get('all_appointments', 'Doctor::all_appointments');
    $routes->get('today_discharge_patient', 'Doctor::today_discharge_patient');
    $routes->get('total_discharge_patient', 'Doctor::total_discharge_patient');

    $routes->get('add_blogs', 'Doctor::add_newsblog'); 
    $routes->post('save_blogs', 'Doctor::save_newsblog');
    $routes->get('manage_blogs', 'Doctor::manage_blogs');
    $routes->get('change_doctor_password', 'Doctor::change_doctor_password');
    $routes->post('change_doctor_password', 'Doctor::change_doctor_password');
    $routes->get('change_doctor', 'Doctor::change_doctor');

    $routes->get('add_prescription/(:any)', 'Doctor::add_prescription/$1');
    $routes->get('add_hospital_expenses/(:any)', 'Doctor::add_hospital_expenses/$1'); 
    $routes->get('add_today_hospital_expenses/(:any)', 'Doctor::add_today_hospital_expenses/$1'); 
    $routes->get('Medical/add_hospital_expenses/(:any)', 'Medical::add_hospital_expenses/$1'); 
    $routes->get('add_patient_payment/(:any)', 'Doctor::add_patient_payment/$1');
    $routes->post('add_patient_payment/(:any)', 'Doctor::add_patient_payment/$1'); 
    $routes->get('add_today_patient_payment/(:any)', 'Doctor::add_today_patient_payment/$1');
    $routes->post('add_today_patient_payment/(:any)', 'Doctor::add_today_patient_payment/$1');  
    $routes->get('add_prescription/(:any)', 'Doctor::add_prescription/$1');
    $routes->get('add_today_pat_prescription/(:any)', 'Doctor::add_today_pat_prescription/$1');
    $routes->post('add_today_pat_prescription/(:any)', 'Doctor::add_today_pat_prescription/$1');
    $routes->get('change_today_appointment_status/(:any)', 'Doctor::change_today_appointment_status/$1');
    $routes->get('change_patients_status', 'Doctor::change_patients_status');
    $routes->get('change_patients_status/(:any)', 'Doctor::change_patients_status/$1'); 
    $routes->get('change_today_patients_status', 'Doctor::change_today_patients_status');
    $routes->get('change_today_patients_status/(:any)', 'Doctor::change_today_patients_status/$1');
    $routes->get('manage_patients', 'Doctor::manage_patients'); 
    $routes->get('add_appointment_pat_charge/(:any)', 'Doctor::add_appointment_pat_charge/$1'); 
    $routes->post('add_appointment_pat_charge/(:any)', 'Doctor::add_appointment_pat_charge/$1'); 
    $routes->get('generate_apment_patient_bill/(:any)', 'Doctor::generate_apment_patient_bill/$1'); 
    $routes->get('generate_patient_bill/(:any)', 'Doctor::generate_patient_bill/$1'); 
    $routes->get('generate_today_patient_bill/(:any)', 'Doctor::generate_today_patient_bill/$1'); 

    $routes->get('change_today_appointments_status/(:any)', 'Doctor::change_today_appointments_status/$1');
    $routes->get('change_all_appointments_status/(:any)', 'Doctor::change_all_appointments_status/$1');
    $routes->get('discharge_summary/(:any)', 'Doctor::discharge_summary/$1'); 
    $routes->post('upload_summary', 'Doctor::upload_summary');
    $routes->get('generate_initial_pay_bill/(:any)', 'Doctor::generate_initial_pay_bill/$1');
    $routes->get('generate_clear_dues_bill/(:any)', 'Doctor::generate_clear_dues_bill/$1');
    $routes->post('save_fee/(:any)', 'Doctor::save_fee/$1');
    $routes->get('get_dept_for_admission', 'Doctor::get_dept_for_admission');
    $routes->get('get_dept_tday_for_admission/(:any)', 'Doctor::get_dept_tday_for_admission/$1');
    $routes->get('admit_today_patient/(:any)', 'Doctor::admit_today_patient/$1');
    $routes->post('revisit_admission_process', 'Doctor::revisit_admission_process');
    $routes->get('get_dept_for_rev_admission/(:any)', 'Doctor::get_dept_for_rev_admission/$1');
    $routes->get('get_dept_for_admission/(:any)', 'Doctor::get_dept_for_admission/$1');
    $routes->post('get_wards_for_admission/(:any)', 'Doctor::get_wards_for_admission/$1');
    $routes->post('get_beds_for_admission/(:any)', 'Doctor::get_beds_for_admission/$1');
    $routes->post('get_admission_detail/(:any)', 'Doctor::get_admission_detail/$1');
    $routes->get('show_revisit_patient_final_payments/(:any)', 'Doctor::show_revisit_patient_final_payments/$1'); 
    $routes->post('show_revisit_patient_final_payments/(:any)', 'Doctor::show_revisit_patient_final_payments/$1'); 
    $routes->post('clear_revisit_final_dues/(:any)', 'Doctor::clear_revisit_final_dues/$1'); 
    $routes->get('change_dshbrd_patients_status/(:any)', 'Doctor::change_dshbrd_patients_status/$1');
    $routes->get('generate_revisit_clear_dues_bill/(:any)','Doctor::generate_revisit_clear_dues_bill/$1');
    $routes->get('view_profile', 'Doctor::view_profile');
    $routes->post('update_profile', 'Doctor::update_profile');
    $routes->post('upload_profile_pic', 'Doctor::upload_profile_pic');
    $routes->post('save_message', 'Doctor::save_message');
    $routes->post('save_today_pat_message', 'Doctor::save_today_pat_message');
    $routes->post('save_revisit_pat_message', 'Doctor::save_revisit_pat_message');

    $routes->get('change_revisit_patients_status/(:any)', 'Doctor::change_revisit_patients_status/$1');
    $routes->get('add_revisit_patient_payment/(:any)', 'Doctor::add_revisit_patient_payment/$1');
    $routes->post('add_revisit_patient_payment/(:any)', 'Doctor::add_revisit_patient_payment/$1');
    $routes->post('upload_prescription', 'Doctor::upload_prescription'); 
    $routes->post('upload_today_pat_prescription', 'Doctor::upload_today_pat_prescription'); 
    $routes->post('upload_prescription_report/(:any)', 'Doctor::upload_prescription_report/$1');
    $routes->get('add_revisit_prescription/(:any)', 'Doctor::add_revisit_prescription/$1');
    $routes->post('upload_revisit_prescription/(:any)', 'Doctor::upload_revisit_prescription/$1');
    $routes->post('upload_revisit_prescription', 'Doctor::upload_revisit_prescription'); 
    $routes->post('add_rev_prescription_report/(:any)', 'Doctor::add_rev_prescription_report/$1');
    $routes->post('add_rev_prescription_report', 'Doctor::add_rev_prescription_report'); 
    $routes->get('permanent_del_all_patients/(:any)', 'Doctor::permanent_del_all_patients/$1'); 
    $routes->get('permanent_del_all_dsbd_patients/(:any)','Doctor::permanent_del_all_dsbd_patients/$1');
    $routes->get('permanent_del_all_apmnt/(:any)', 'Doctor::permanent_del_all_apmnt/$1'); 
    $routes->get('permanent_del_today_patients/(:any)', 'Doctor::permanent_del_today_patients/$1'); 
    $routes->get('permanent_del_today_apmnt/(:any)', 'Doctor::permanent_del_today_apmnt/$1'); 
    $routes->get('permanent_del_today_patients/(:any)', 'Doctor::permanent_del_today_patients/$1'); 
    $routes->get('show_patient_final_payments/(:any)', 'Doctor::show_patient_final_payments/$1'); 
    $routes->get('show_today_pat_final_pay/(:any)', 'Doctor::show_today_pat_final_pay/$1');
    $routes->post('clear_final_dues/(:any)', 'Doctor::clear_final_dues/$1'); 
    $routes->post('clear_today_pat_final_dues/(:any)', 'Doctor::clear_today_pat_final_dues/$1'); 
    $routes->post('save_revisit_pat_advice', 'Doctor::save_revisit_pat_advice');
    $routes->post('save_advice', 'Doctor::save_advice');
    $routes->post('save_today_pat_advice', 'Doctor::save_today_pat_advice');
    $routes->get('edit_revisit_patients/(:any)', 'Doctor::edit_revisit_patients/$1');
    $routes->post('edit_revisit_patients/(:any)', 'Doctor::edit_revisit_patients/$1');
    $routes->post('update_patients/(:any)', 'Doctor::update_patients/$1');
    $routes->post('update_revisit_patients/(:any)', 'Doctor::update_revisit_patients/$1');
    $routes->get('admit_revisit_patient/(:any)', 'Doctor::admit_revisit_patient/$1');
    $routes->post('save_revisit_admission_fee/(:any)', 'Doctor::save_revisit_admission_fee/$1');
    $routes->get('add_revisit_hospital_expenses/(:any)', 'Doctor ::add_revisit_hospital_expenses/$1');
    $routes->get('generate_revisit_admission_bill/(:any)','Doctor::generate_revisit_admission_bill/$1');

    $routes->post('save_revisit_hospital_expenses', 'Doctor::save_revisit_hospital_expenses');
    $routes->get('edit_today_patients/(:any)', 'Doctor::edit_today_patients/$1');
    $routes->post('edit_today_patients/(:any)', 'Doctor::edit_today_patients/$1');
    $routes->get('edit_patients/(:any)', 'Doctor::edit_patients/$1');
    $routes->get('edit_patients/(:any)', 'Doctor::edit_patients/$1');
    $routes->get('filter_revisit_patient/(:any)', 'Doctor::filter_revisit_patient/$1');
    $routes->get('filter_today_appointments/(:any)', 'Doctor::filter_today_appointments/$1');
    $routes->get('filter_all_appointments/(:any)', 'Doctor::filter_all_appointments/$1');
    $routes->get('request_activate_email', 'Doctor::request_activate_email');
    $routes->post('request_activate_email', 'Doctor::request_activate_email');
    $routes->get('filter_doc_dis_patients/(:any)', 'Doctor::filter_doc_dis_patients/$1');
    $routes->get('manage_revisit_patient', 'Doctor::manage_revisit_patient');

    $routes->get('filter_all_patients/(:any)', 'Doctor::filter_all_patients/$1');
    $routes->get('filter_today_patients/(:any)', 'Doctor::filter_today_patients/$1');
    $routes->get('filter_today_discharge_patient/(:any)', 'Doctor::filter_today_discharge_patient/$1'); 
    $routes->get('filter_all_discharge_patient/(:any)', 'Doctor::filter_all_discharge_patient/$1');
    $routes->get('filter_blogs/(:any)', 'Doctor::filter_blogs/$1');   
    $routes->get('change_blog_status/(:any)', 'Doctor::change_blog_status/$1');
    $routes->get('delete_blogs/(:any)', 'Doctor::delete_blogs/$1');
    $routes->get('delete_patients/(:any)', 'Doctor::delete_patients/$1');
    $routes->get('delete_today_dis_patients/(:any)', 'Doctor::delete_today_dis_patients/$1');
    $routes->get('delete_today_patients/(:any)', 'Doctor::delete_today_patients/$1');
    $routes->get('delete_all_dis_patients/(:any)', 'Doctor::delete_all_dis_patients/$1');
    
    $routes->get('delete_dsbd_patients/(:any)', 'Doctor::delete_dsbd_patients/$1');
    $routes->get('search_today_appointments', 'Doctor::search_today_appointments'); 
    $routes->post('search_today_appointments', 'Doctor::search_today_appointments'); 
    $routes->post('search_today_patient', 'Doctor::search_today_patient'); 
    $routes->get('search_today_patient', 'Doctor::search_today_patient'); 
    $routes->post('search_today_discharge_patient', 'Doctor::search_today_discharge_patient'); 
    $routes->get('search_today_discharge_patient', 'Doctor::search_today_discharge_patient'); 
    $routes->get('doctors_available_slots_neo/(:any)', 'Doctor::doctors_available_slots_neo/$1');  
    //$routes->get('doctors_available_slots/(:any)', 'Doctor::doctors_available_slots/$1');
    //$routes->post('doctors_available_slots/(:any)', 'Doctor::doctors_available_slots/$1');
    //$routes->get('available_selected_doctor_slots', 'Doctor::available_selected_doctor_slots'); 

    $routes->post('search_all_appointments', 'Doctor::search_all_appointments'); 
    $routes->get('search_all_appointments', 'Doctor::search_all_appointments'); 
    $routes->post('search_all_patient', 'Doctor::search_all_patient'); 
    $routes->get('search_all_patient', 'Doctor::search_all_patient'); 
    $routes->post('search_all_dis_patient', 'Doctor::search_all_dis_patient'); 
    $routes->get('search_all_dis_patient', 'Doctor::search_all_dis_patient'); 

    ####################
    $routes->get('save_slots', 'Doctor::save_slots');  
    $routes->post('save_slots/(:any)', 'Doctor::save_slots/$1'); 
    $routes->post('save_hospital_expenses', 'Doctor::save_hospital_expenses'); 
    $routes->post('save_today_hospital_expenses', 'Doctor::save_today_hospital_expenses'); 
    $routes->get('getset_dr_slots', 'Doctor::getset_dr_slots');
    $routes->get('getset_dr_slots_neo', 'Doctor::getset_dr_slots_neo');  
    $routes->post('getset_dr_slots_ajax', 'Doctor::getset_dr_slots_ajax'); 
    $routes->post('getset_dr_slots_ajax_neo', 'Doctor::getset_dr_slots_ajax_neo'); 
    $routes->get('get_doctor_fee_details', 'Doctor::get_doctor_fee_details'); 
    $routes->get('get_dr_slots', 'Doctor::get_dr_slots');  
    $routes->get('get_dr_slots/(:any)', 'Doctor::get_dr_slots/$1'); 
    $routes->get('print_slip/(:any)', 'Doctor::print_slip/$1'); 
    $routes->get('generate_receive_payment_bill/(:any)', 'Doctor::generate_receive_payment_bill/$1'); 
    $routes->get('save_proceed_addmission', 'Doctor::save_proceed_addmission');

}); #*********************************************************************



#🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾 Frontdesk_loign - Public routes — no filter 🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾

$routes->get('Frontdesk_login/index', 'Frontdesk_login::index'); //just for testing
$routes->get('Frontdesk_login/getUserBrowserInfo', 'Frontdesk_login::getUserBrowserInfo');
$routes->get('Frontdesk_login/login_account', 'Frontdesk_login::login_account');
$routes->post('Frontdesk_login/login_account', 'Frontdesk_login::login_account');
$routes->get('Frontdesk_login/doctor_login', 'Frontdesk_login::doctor_login');
$routes->post('Frontdesk_login/create_frnd_account', 'Frontdesk_login::create_frnd_account');

$routes->get('Frontdesk', 'Frontdesk::index');
#$routes->get('Logout_account', 'Frontdesk::Logout_account');
$routes->get('Frontdesk/Logout_account', 'Frontdesk::Logout_account');

#Forget Password - rountes START
$routes->get('Frontdesk_login/forget_password', 'Frontdesk_login::forget_password'); //Render email input form
$routes->post('Frontdesk_login/forget_password', 'Frontdesk_login::forget_password'); //Send link to email
$routes->get('Frontdesk_login/reset_password/(:any)', 'Frontdesk_login::reset_password/$1');//Render new/confirm pass form
$routes->post('Frontdesk_login/reset_password/(:any)', 'Frontdesk_login::reset_password/$1');//Forget password
#Forget Password - rountes END

#🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾 Frontdesk - Public routes — no filter 🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾
#$routes->get('checkin', 'Frontdesk::checkInPatient');
#$routes->get('Frontdesk/generate_apment_patient_bill', 'Frontdesk::generate_apment_patient_bill');
#$routes->get('Frontdesk/get_available_selected_doctor_slots', 'Frontdesk::get_available_selected_doctor_slots'); 
#$routes->get('Frontdesk_login/frontdesk_login', 'Frontdesk_login::frontdesk_login');
#$routes->post('Frontdesk_login/frontdesk_login', 'Frontdesk_login::frontdesk_login');
#$routes->get('Frontdesk/change_all_appointment_status/(:any)', 'Frontdesk::change_all_appointment_status/$1');
#$routes->get('Frontdesk/manage_patients_by_fd', 'Frontdesk::manage_patients_by_fd');
#$routes->post('Frontdesk/save_hospital_expenses/(:any)', 'Frontdesk::save_hospital_expenses/$1'); 
#$routes->post('Frontdesk/add_patient', 'Frontdesk::add_patient');


#🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾 Frontdesk - login must routings 🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾🧾
$routes->group('Frontdesk', ['filter' => 'frontdeskAuth'], function($routes) {
    #$routes->get('/', 'Frontdesk::dashboard');

    $routes->get('add_fee/(:any)', 'Frontdesk::add_fee/$1');
    $routes->get('admit_patient/(:any)', 'Frontdesk::admit_patient/$1');
    $routes->post('save_fee/(:any)', 'Frontdesk::save_fee/$1');
    $routes->post('save_admission_fee/(:any)', 'Frontdesk::save_admission_fee/$1');
    $routes->post('admission_process', 'Frontdesk::admission_process');
    $routes->get('run_appointment_fee_form', 'Frontdesk::run_appointment_fee_form');
    $routes->get('run_appointment_fee_form/(:any)', 'Frontdesk::run_appointment_fee_form/$1');
    $routes->get('generate_initial_pay_bill/(:any)', 'Frontdesk::generate_initial_pay_bill/$1');
    $routes->get('generate_admission_bill/(:any)', 'Frontdesk::generate_admission_bill/$1');

    $routes->post('get_beds_for_admission/(:any)', 'Frontdesk::get_beds_for_admission/$1');
    $routes->post('get_wards_for_admission/(:any)', 'Frontdesk::get_wards_for_admission/$1');
    $routes->post('search_all_patient', 'Frontdesk::search_all_patient'); 
    $routes->get('search_all_patient', 'Frontdesk::search_all_patient'); 
    $routes->get('generate_apment_patient_bill/(:any)', 'Frontdesk::generate_apment_patient_bill/$1');
    $routes->post('search_discharge_patient', 'Frontdesk::search_discharge_patient'); 
    $routes->get('search_discharge_patient', 'Frontdesk::search_discharge_patient'); 
    $routes->get('filter_dischrage_patient/(:any)', 'Frontdesk::filter_dischrage_patient/$1'); 
    $routes->get('payment_dischrge_patient/(:any)', 'Frontdesk::payment_dischrge_patient/$1'); 
    $routes->get('manage_today_discharged_patient', 'Frontdesk::manage_today_discharged_patient'); 
    
    $routes->get('filter_department/(:any)', 'Frontdesk::filter_department/$1');
    $routes->get('filter_department', 'Frontdesk::filter_department');  
    $routes->get('filter_doctor/(:any)', 'Frontdesk::filter_doctor/$1'); 

    $routes->get('change_dshbrd_patients_status/(:any)','Frontdesk::change_dshbrd_patients_status/$1'); 
    $routes->get('search_donor', 'Frontdesk::search_donor'); 
    $routes->post('search_donor_details', 'Frontdesk::search_donor_details'); 
    $routes->get('search_donor_details', 'Frontdesk::search_donor_details'); 
    $routes->get('blood_request/(:any)', 'Frontdesk::blood_request/$1');
    $routes->get('search_donor_details/(:any)', 'Frontdesk::search_donor_details/$1');
    $routes->get('blood_bank', 'Frontdesk::blood_bank'); 
    $routes->post('search_hos_bld_user', 'Frontdesk::search_hos_bld_user'); 
    $routes->get('available_selected_doctor_slots', 'Frontdesk::available_selected_doctor_slots');
    $routes->get('all_appointments_from_to_date', 'Frontdesk::all_appointments_from_to_date'); 
    $routes->post('book_appointment', 'Frontdesk::book_appointment'); 
    $routes->get('view_profile', 'Frontdesk::view_profile'); 
    $routes->post('update_profile', 'Frontdesk::update_profile');
    $routes->post('upload_profile_pic', 'Frontdesk::upload_profile_pic');
    $routes->post('Blood_bank/upload_profile_pic', 'Blood_bank::upload_profile_pic');

    $routes->get('filterByDoctor', 'Frontdesk::filterByDoctor'); 
    $routes->get('filterByDateRange', 'Frontdesk::filterByDateRange'); 
    $routes->get('filterByDepartment', 'Frontdesk::filterByDepartment'); 
    $routes->get('search_department', 'Frontdesk::search_department'); 
    $routes->post('search_department', 'Frontdesk::search_department'); 
    $routes->get('edit_department/(:any)', 'Frontdesk::edit_department/$1'); 
    $routes->post('update_department/(:any)', 'Frontdesk::update_department/$1'); 
    $routes->get('manage_department', 'Frontdesk::manage_department'); 
    $routes->get('change_department_status/(:any)', 'Frontdesk::change_department_status/$1'); 
    $routes->get('clear_final_payment/(:any)', 'Frontdesk::clear_final_payment/$1'); 
    $routes->post('clear_final_dues/(:any)', 'Frontdesk::clear_final_dues/$1'); 
    $routes->get('show_patient_final_payments/(:any)', 'Frontdesk::show_patient_final_payments/$1'); 
    $routes->post('show_patient_final_payments/(:any)', 'Frontdesk::show_patient_final_payments/$1'); 
    $routes->get('change_dshbrd_patients_status/(:any)', 'Frontdesk::change_dshbrd_patients_status/$1'); 
    $routes->get('delete_department/(:any)', 'Frontdesk::delete_department/$1'); 
    $routes->get('discharge_summary/(:any)', 'Frontdesk::discharge_summary/$1'); 
    $routes->get('filter_all_patients/(:any)', 'Frontdesk::filter_all_patients/$1'); 
    $routes->get('filter_appointment/(:any)', 'Frontdesk::filter_appointment/$1'); 
    $routes->post('filter_appointment/(:any)', 'Frontdesk::filter_appointment/$1'); 
    $routes->get('filter_appointment','Frontdesk::filter_appointment'); 
    $routes->get('filter_appointment_patient/(:any)', 'Frontdesk::filter_appointment_patient/$1'); 
    $routes->get('all_patients','Frontdesk::all_patients'); 
    $routes->get('total_discharge_patient','Frontdesk::total_discharge_patient'); 
    $routes->get('delete_all_dis_patients/(:any)', 'Frontdesk::delete_all_dis_patients/$1'); 
    $routes->get('change_frontdesk_password','Frontdesk::change_frontdesk_password'); 
    $routes->post('change_frontdesk_password','Frontdesk::change_frontdesk_password'); 

    $routes->get('filter_all_discharge_patient/(:any)', 'Frontdesk::filter_all_discharge_patient/$1'); 
    $routes->post('save_slots', 'Frontdesk::save_slots');  
    $routes->get('save_slots', 'Frontdesk::save_slots');  
    $routes->get('login_account', 'Frontdesk::login_account');  
    $routes->post('login_account', 'Frontdesk::login_account'); 
    $routes->post('search_doctor', 'Frontdesk::search_doctor');
    $routes->get('search_doctor', 'Frontdesk::search_doctor'); 
    $routes->get('view_doctor', 'Frontdesk::view_doctor');
    $routes->post('search_results', 'Frontdesk::search_results'); 
    $routes->get('doctors_available_slots_neo/(:any)', 'Frontdesk::doctors_available_slots_neo/$1');
    $routes->get('doctors_available_slots/(:any)', 'Frontdesk::doctors_available_slots/$1'); 
    $routes->get('doctors_available_slots', 'Frontdesk::doctors_available_slots'); 

    $routes->get('patient_registration', 'Frontdesk::patient_registration'); 
    $routes->get('edit_patients/(:any)', 'Frontdesk::edit_patients/$1');
    $routes->post('update_patients/(:any)', 'Frontdesk::update_patients/$1');
    $routes->get('edit_patients', 'Frontdesk::edit_patients');  
    $routes->get('change_appointment_status_dsbrd/(:any)', 'Frontdesk::change_appointment_status_dsbrd/$1');
    $routes->get('manage_discharge_patients', 'Frontdesk::manage_discharge_patients');
    $routes->get('Frontend/manage_patients', 'Frontdesk::Frontend::manage_patients');
    $routes->get('all_appointments', 'Frontdesk::all_appointments');
    $routes->post('all_appointments', 'Frontdesk::all_appointments');
    $routes->get('today_appointment', 'Frontdesk::today_appointment');
    $routes->get('today_patients', 'Frontdesk::today_patients');

    $routes->get('filter_all_appointments/(:any)', 'Frontdesk::filter_all_appointments/$1');
    $routes->get('filter_today_appointment/(:any)', 'Frontdesk::filter_today_appointment/$1');
    $routes->get('delete_dis_patients/(:any)', 'Frontdesk::delete_dis_patients/$1');
    $routes->get('manage_disc_patients', 'Frontdesk::manage_disc_patients');
    $routes->get('filter_today_appointments/(:any)', 'Frontdesk::filter_today_appointments/$1');
    $routes->get('today_appointments', 'Frontdesk::today_appointments');
    $routes->get('doctor_discharge_appointments', 'Frontdesk::doctor_discharge_appointments');
    $routes->get('change_all_appointments_status/(:any)', 'Frontdesk::change_all_appointments_status/$1');
    $routes->get('getset_dr_slots_neo', 'Frontdesk::getset_dr_slots_neo');
    $routes->get('getset_dr_slots', 'Frontdesk::getset_dr_slots');
    $routes->get('change_all_patients_status/(:any)', 'Frontdesk::change_all_patients_status/$1');
    $routes->get('change_today_appointments_status/(:any)', 'Frontdesk::change_today_appointments_status/$1');
    $routes->get('get_dept_for_admission', 'Frontdesk::get_dept_for_admission');
    $routes->get('get_dept_for_admission/(:any)', 'Frontdesk::get_dept_for_admission/$1');

    $routes->get('add_patient_payment/(:any)', 'Frontdesk::add_patient_payment/$1'); 
    $routes->get('generate_patient_bill/(:any)', 'Frontdesk::generate_patient_bill/$1'); 
    $routes->post('add_patient_payment/(:any)', 'Frontdesk::add_patient_payment/$1'); 
    $routes->get('generate_clear_dues_bill/(:any)', 'Frontdesk::generate_clear_dues_bill/$1'); 
    $routes->get('add_hospital_expenses/(:any)', 'Frontdesk::add_hospital_expenses/$1'); 
    $routes->get('save_hospital_expenses/(:any)', 'Frontdesk::save_hospital_expenses/$1'); 
    $routes->post('save_hospital_expenses', 'Frontdesk::save_hospital_expenses'); 
    $routes->get('add_prescription/(:any)', 'Frontdesk::add_prescription/$1');
    $routes->post('add_prescription/(:any)', 'Frontdesk::add_prescription/$1');
    $routes->get('search_patient', 'Frontdesk::search_patient');
    $routes->get('add_appointment_pat_charge/(:any)', 'Frontdesk::add_appointment_pat_charge/$1');
    $routes->post('add_appointment_pat_charge/(:any)', 'Frontdesk::add_appointment_pat_charge/$1'); 
    $routes->post('add_patient', 'Frontdesk::add_patient');
    $routes->post('upload_patients', 'Frontdesk::upload_patients');
    $routes->post('upload_summary', 'Frontdesk::upload_summary');

    $routes->get('manage_patients', 'Frontdesk::manage_patients');
    $routes->post('search_patient', 'Frontdesk::search_patient');
    $routes->post('search_all_appointments', 'Frontdesk::search_all_appointments');
    $routes->get('search_all_appointments', 'Frontdesk::search_all_appointments');
    $routes->get('delete_patients/(:any)', 'Frontdesk::delete_patients/$1');
    $routes->get('change_patients_status/(:any)', 'Frontdesk::change_patients_status/$1');
    $routes->get('print_slip/(:any)', 'Frontdesk::print_slip/$1');
    $routes->get('add_patient', 'Frontdesk::add_patient');
    $routes->post('add_fd_patient', 'Frontdesk::add_fd_patient');
    $routes->get('dashboard', 'Frontdesk::dashboard');
    
    $routes->get('delete_appointment_dsbrd/(:any)', 'Frontdesk::delete_appointment_dsbrd/$1');
    $routes->get('filter_patients/(:any)', 'Frontdesk::filter_patients/$1');
    $routes->get('filter_patients_dis_pat/(:any)', 'Frontdesk::filter_patients_dis_pat/$1'); 
    $routes->get('filter_deleted_pat/(:any)', 'Frontdesk::filter_deleted_pat/$1'); 
    $routes->get('filter_admitted_pat/(:any)', 'Frontdesk::filter_admitted_pat/$1'); 

    $routes->get('add_patients', 'Frontdesk::add_patients');
    $routes->get('generate_receive_payment_bill/(:any)', 'Frontdesk::generate_receive_payment_bill/$1');
    $routes->post('save_message', 'Frontdesk::save_message');
    $routes->post('upload_prescription_report/(:any)', 'Frontdesk::upload_prescription_report/$1');
    $routes->get('permanent_del_all_apmt/(:any)', 'Frontdesk::permanent_del_all_apmt/$1'); 
    $routes->get('permanent_del_all_apmnt/(:any)', 'Frontdesk::permanent_del_all_apmnt/$1'); 
    $routes->get('permanent_del_today_apmnt/(:any)', 'Frontdesk::permanent_del_today_apmnt/$1'); 
    $routes->get('permanent_del_patients/(:any)', 'Frontdesk::permanent_del_patients/$1'); 
    $routes->get('forget_password', 'Frontdesk::forget_password'); 
    $routes->post('forget_password', 'Frontdesk::forget_password'); 
    $routes->post('reset_password/(:any)', 'Frontdesk::reset_password/$1');
    $routes->get('reset_password/(:any)', 'Frontdesk::reset_password/$1');
    $routes->get('pick_slots', 'Frontdesk::pick_slots'); 
    $routes->post('save_advice', 'Frontdesk::save_advice');
    $routes->post('upload_prescription/(:any)', 'Frontdesk::upload_prescription/$1');
    $routes->post('upload_prescription', 'Frontdesk::upload_prescription'); 
    $routes->post('add_prescription_report/(:any)', 'Frontdesk::add_prescription_report/$1');
    $routes->post('add_prescription_report', 'Frontdesk::add_prescription_report'); 
    $routes->get('admit_revisit_patient/(:any)', 'Frontdesk::admit_revisit_patient/$1');
    $routes->post('patients_search_lookup', 'Frontdesk::patients_search_lookup');
    $routes->get('donor_registration', 'Frontdesk::donor_registration');
    $routes->post('donor_registered', 'Frontdesk::donor_registered');
    $routes->get('manage_donor', 'Frontdesk::manage_donor');
    $routes->post('search_doc_appointment', 'Frontdesk::search_doc_appointment');
    $routes->get('search_doc_appointment', 'Frontdesk::search_doc_appointment');
    $routes->post('search_today_appointments', 'Frontdesk::search_today_appointments');
    $routes->get('search_today_appointments', 'Frontdesk::search_today_appointments');

}); #*********************************************************************



# 🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸 Blood Bank - Public routes — no filter 🩸🩸🩸🩸🩸🩸🩸🩸🩸
$routes->get('Blood_bank/blood_bank_login', 'Blood_bank::blood_banklogin');  //Different Custom Routing
$routes->post('Blood_bank/blood_bank_login', 'Blood_bank::blood_banklogin'); //Different Custom Routing
$routes->get('Blood_bank_registration/blood_bank_user_registration', 'Blood_bank_registration::blood_bank_user_registration');
$routes->post('Blood_bank_registration/blood_bank_user_registration', 'Blood_bank_registration::blood_bank_user_registration');
$routes->get('Blood_bank/index', 'Blood_bank::index');
$routes->get('Blood_bank/Logout_blood_bank', 'Blood_bank::Logout_bloodbank');  //Blood Bank Logout 

#Forget Password - rountes START
$routes->get('Blood_bank/forget_password', 'Blood_bank::forget_password');//Render email input form
$routes->post('Blood_bank/forget_password', 'Blood_bank::forget_password');//Send link to email
$routes->get('Blood_bank/reset_password/(:any)', 'Blood_bank::reset_password/$1'); //Render new/confirm pass form
$routes->post('Blood_bank/reset_password/(:any)', 'Blood_bank::reset_password/$1');//Forget password
#Forget Password - rountes END

#$routes->get('/', 'BloodBank::dashboard'); 
#$routes->get('inventory', 'BloodBank::inventory');

# 🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸 Blood Bank - login must routings 🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸🩸
$routes->group('Blood_bank', ['filter' => 'bloodBankAuth'], function($routes) {
    $routes->get('view_enquiry', 'Blood_bank::view_enquiry');
    $routes->post('change_Blood_bank_password', 'Blood_bank::change_Blood_bank_password');
    $routes->get('change_Blood_bank_password', 'Blood_bank::change_Blood_bank_password'); 
    $routes->get('view_profile', 'Blood_bank::view_profile');
    $routes->get('/Blood_bank_registration/login_account', 'Blood_bank_registration::login_account');
    $routes->post('Blood_bank_registration/login_account', 'Blood_bank_registration::login_account');
    $routes->post('search_sale_bld_stock', 'Blood_bank::search_sale_bld_stock');
    $routes->get('add_blood', 'Blood_bank::add_blood');
    $routes->post('add_blood_group', 'Blood_bank::add_blood_group');
    $routes->get('manage_blood', 'Blood_bank::manage_blood');
    $routes->get('change_blood_status/(:any)', 'Blood_bank::change_blood_status/$1');
    $routes->get('delete_blood_group/(:any)', 'Blood_bank::delete_blood_group/$1');
    $routes->get('total_blood_stock', 'Blood_bank::total_blood_stock'); 
    $routes->get('blood_response_data/(:any)', 'Blood_bank::blood_response_data/$1');
    
    #$routes->get('blood_bank_login', 'Blood_bank::blood_bank_login');
    
    $routes->get('blood_bank_trans', 'Blood_bank::blood_bank_trans'); 
    $routes->get('add_blood_stock', 'Blood_bank::add_blood_stock'); 
    $routes->post('add__total_blood_stock', 'Blood_bank::add__total_blood_stock'); 

    $routes->get('blood_transition', 'Blood_bank::blood_transition');
    $routes->post('upload_blood_transition', 'Blood_bank::upload_blood_transition');
    $routes->get('manage_blood_transition', 'Blood_bank::manage_blood_transition'); 
    $routes->get('donor_details', 'Blood_bank::donor_details');
    $routes->get('donor_details/(:any)', 'Blood_bank::donor_details/$1');

    $routes->post('search_donor_details', 'Blood_bank::search_donor_details');
    $routes->get('filter_blood_donors/(:any)', 'Blood_bank::filter_blood_donors/$1');
    $routes->get('buy_donor_blood', 'Blood_bank::buy_donor_blood');
    $routes->post('buy_blood_donor', 'Blood_bank::buy_blood_donor');
    $routes->get('manage_donor_blood', 'Blood_bank::manage_donor_blood');
    $routes->post('search_donor_blood', 'Blood_bank::search_donor_blood');
    $routes->get('filter_donor_blood/(:any)', 'Blood_bank::filter_donor_blood/$1');
    $routes->get('filter_hos_sale_bld/(:any)', 'Blood_bank::filter_hos_sale_bld/$1');
    $routes->get('blood_selling_slip/(:any)', 'Blood_bank::blood_selling_slip/$1');
    $routes->post('search_hos_bld_user', 'Blood_bank::search_hos_bld_user');
    $routes->get('add_blood_selling_price/(:any)', 'Blood_bank::add_blood_selling_price/$1');
    $routes->post('blood_selling_price/(:any)', 'Blood_bank::blood_selling_price/$1');
    $routes->get('change_donor_blood/(:any)', 'Blood_bank::change_donor_blood/$1');
    $routes->get('manage_donor_blood/(:any)', 'Blood_bank::manage_donor_blood/$1');
    $routes->get('delete_donor_details/(:any)', 'Blood_bank::delete_donor_details/$1');
    $routes->get('donor_blood_trans', 'Blood_bank::donor_blood_trans');
    $routes->post('donor_blood_transition', 'Blood_bank::donor_blood_transition');
    $routes->get('manage_donor_blood_trans', 'Blood_bank::manage_donor_blood_trans');
    $routes->get('filter_sale_records/(:any)', 'Blood_bank::filter_sale_records/$1');
    $routes->get('filter_sale_records/(:any)', 'Blood_bank::filter_sale_records/$1');
    $routes->get('filter_blood_needed_users/(:any)', 'Blood_bank::filter_blood_needed_users/$1');
    $routes->get('view_enquirygoogleusers', 'Blood_bank::view_enquirygoogleusers');
    $routes->get('filter_google_user_nedded/(:any)', 'Blood_bank::filter_google_user_nedded/$1');
    $routes->get('filter_google_user_nedded/(:any)', 'Blood_bank::filter_google_user_nedded/$1');
    $routes->get('permanent_del_bld_group/(:any)', 'Blood_bank::permanent_del_bld_group/$1');
    $routes->post('update_profile', 'Blood_bank::update_profile'); 

    #$routes->get('Blood_bank/filter_donor_blood/new_donors', 'Blood_bank::filter_donor_blood::new_donors');
    #$routes->get('Blood_bank/filter_donor_blood/old_donors', 'Blood_bank::filter_donor_blood::old_donors');
    #$routes->get('Blood_bank/filter_blood_needed_users/new_user', 'Blood_bank::filter_blood_needed_users::new_user');
    #$routes->get('Blood_bank/filter_blood_needed_users/old_user', 'Blood_bank::filter_blood_needed_users::old_user'); 

});


$routes->group('bloodbank', ['filter' => 'bloodBankAuth'], function($routes) {
    #Blood Bank Donor SECTION START-
    $routes->get('Blood_bank_donor/find_donors', 'Blood_bank_donor::find_donors');
    $routes->post('Blood_bank_donor/search_hos_bld_user', 'Blood_bank_donor::search_hos_bld_user');
    $routes->post('Blood_bank_donor/search_donor_details', 'Blood_bank_donor::search_donor_details');
    $routes->get('Blood_bank_donor/search_donor_details/(:any)', 'Blood_bank_donor::search_donor_details/$1');
    $routes->get('Blood_bank_donor/search_donor_details', 'Blood_bank_donor::search_donor_details');
    $routes->get('Blood_bank_donor/blood_request/(:any)', 'Blood_bank_donor::blood_request/$1');
    $routes->get('/Blood_bank_donor/manage_donor', 'Blood_bank_donor::manage_donor');
    $routes->get('Blood_bank_donor/donor_registration', 'Blood_bank_donor::donor_registration');
    $routes->get('Blood_bank_donor/blood_bank', 'Blood_bank_donor::blood_bank');
    $routes->get('Blood_bank_donor/search_donor', 'Blood_bank_donor::search_donor');
    $routes->get('Blood_bank_donor', 'Blood_bank_donor');
    
    $routes->post('Blood_bank_registration/registration', 'Blood_bank_registration::registration');
    $routes->get('Blood_bank_registration/registration', 'Blood_bank_registration::registration');

    
    
    
    #$routes->post('Blood_bank_registration/admin_registerd', 'Blood_bank_registration::admin_registerd');
    #$routes->get('Blood_bank_registration/admin_registerd', 'Blood_bank_registration::admin_registerd');
    
    $routes->get('Blood_bank_registration/Logout_account', 'Blood_bank_registration::Logout_account');

    
});#*******************************************************************************


#Medical Accountant Login/Company Section/Manage_Company/Filter/Old_Company
#$routes->get('Medical_Accountant/filter_medicine_cpmpany/(:any)', 'Medical_Accountant::filter_medicine_cpmpany/$1');
#Medical Accountant Login/Company Section/Manage_Company/Actions/Edit
#Medical Accountant Login/Product_Section/Add_Medicine
#Medical Accountant Login/Product_Section/Manage_Medicine   
#Medical Accountant Login/Expired Products/View_Expired_Products


# 💰💰💰💰💰💰💰💰💰💰💰💰 Medical Accountant - Public routes — no filter 💰💰💰💰💰💰
#Accountant Routing - START
$routes->get('Accountant_login/accountant_login', 'Accountant_login::accountant_login');
$routes->post('Accountant_login/accountant_login', 'Accountant_login::accountant_login');
$routes->get('Accountant_login', 'Accountant_login::index');
#$routes->get('Accountant_login', 'Accountant_login::index');
$routes->get('Accountant_login/Logout_accountant', 'Accountant_login::Logout_accountant');

$routes->post('Accountant_login/create_acc_account', 'Accountant_login::create_acc_account');
$routes->get('Accountant_login/create_acc_account', 'Accountant_login::create_acc_account');

// $routes->group('Accountant_login', ['filter' => 'medicalAccountantAuth'], function($routes) {
// });

#Forget Password - rountes START
$routes->get('Accountant_login/forget_password', 'Accountant_login::forget_password'); //Render email input form
$routes->post('Accountant_login/forget_password', 'Accountant_login::forget_password'); //Send link to email
$routes->get('Accountant_login/reset_password/(:any)', 'Accountant_login::reset_password/$1'); //Render new/confirm pass form
$routes->post('Accountant_login/reset_password/(:any)', 'Accountant_login::reset_password/$1'); //Forget password
#Forget Password - rountes END


$routes->get('Medical_Accountant', 'Medical_Accountant::index');



# 💰💰💰💰💰💰💰💰💰💰💰💰 Medical Accountant - - login must routings 💰💰💰💰💰💰
//$routes->group('accountant', ['filter' => 'medicalAccountantAuth'], function($routes) {
$routes->group('Medical_Accountant', ['filter' => 'medicalAccountantAuth'], function($routes) {
    
    $routes->get('get_dept_for_admission', 'Medical_Accountant::get_dept_for_admission');
    $routes->get('get_dept_for_admission/(:any)', 'Medical_Accountant::get_dept_for_admission/$1');
    $routes->get('view_profile', 'Medical_Accountant::view_profile'); 
    $routes->post('update_profile', 'Medical_Accountant::update_profile'); 
    $routes->post('upload_profile_pic', 'Medical_Accountant::upload_profile_pic');
    $routes->get('manage_patients', 'Medical_Accountant::manage_patients');
    $routes->post('search_canceled_appointments', 'Medical_Accountant::search_canceled_appointments');
    $routes->get('search_canceled_appointments', 'Medical_Accountant::search_canceled_appointments');
    $routes->get('filter_exp_medicine/(:any)', 'Medical_Accountant::filter_exp_medicine/$1');
    $routes->get('filter_appointment/(:any)', 'Medical_Accountant::filter_appointment/$1');

    $routes->get('filter_del_appointments/(:any)', 'Medical_Accountant::filter_del_appointments/$1');
    $routes->get('change_cancelled_appointments_status/(:any)', 'Medical_Accountant::change_cancelled_appointments_status/$1');
    $routes->post('search_exp_medicine', 'Medical_Accountant::search_exp_medicine');
    $routes->get('search_exp_medicine', 'Medical_Accountant::search_exp_medicine'); 
    $routes->get('delete_expiry_medicine/(:any)', 'Medical_Accountant::delete_expiry_medicine/$1');
    $routes->get('change_exp_medicine_status/(:any)/(:any)', 'Medical_Accountant::change_exp_medicine_status/$1/$2');
    $routes->get('change_today_appointments_status/(:any)', 'Medical_Accountant::change_today_appointments_status/$1');
    $routes->get('canceled_appointments', 'Medical_Accountant::canceled_appointments');
    $routes->get('change_all_appointments_status/(:any)', 'Medical_Accountant::change_all_appointments_status/$1'); 
    $routes->get('add_fee/(:any)', 'Medical_Accountant::add_fee/$1'); 
    $routes->get('check_out_products/(:any)', 'Medical_Accountant::check_out_products/$1'); 
    $routes->get('delete_cart_product/(:any)', 'Medical_Accountant::delete_cart_product/$1'); 
    $routes->post('search_patient', 'Medical_Accountant::search_patient'); 
    $routes->get('search_patient', 'Medical_Accountant::search_patient'); 
    $routes->get('add_appointment_pat_charge', 'Medical_Accountant::add_appointment_pat_charge'); 
    $routes->get('add_hospital_expenses/(:any)', 'Medical_Accountant::add_hospital_expenses/$1'); 
    $routes->post('save_hospital_expenses', 'Medical_Accountant::save_hospital_expenses'); 
    $routes->post('save_fee/(:any)', 'Medical_Accountant::save_fee/$1'); 

    $routes->get('permanent_del_patients/(:any)', 'Medical_Accountant::permanent_del_patients/$1');

    $routes->get('permanent_del_today_apmnt/(:any)', 'Medical_Accountant::permanent_del_today_apmnt/$1');
    $routes->post('clear_final_dues/(:any)', 'Medical_Accountant::clear_final_dues/$1');
    $routes->get('clear_final_dues/(:any)', 'Medical_Accountant::clear_final_dues/$1');  
    $routes->get('add_patients', 'Medical_Accountant::add_patients'); 
    $routes->post('upload_patients', 'Medical_Accountant::upload_patients'); 
    $routes->get('discharge_summary/(:any)','Medical_Accountant::discharge_summary/$1'); 
    $routes->get('add_prescription/(:any)', 'Medical_Accountant::add_prescription/$1'); 
    $routes->get('add_appointment_pat_charge/(:any)', 'Medical_Accountant::add_appointment_pat_charge/$1'); 
    $routes->post('add_appointment_pat_charge/(:any)', 'Medical_Accountant::add_appointment_pat_charge/$1'); 
    $routes->get('generate_apment_patient_bill/(:any)', 'Medical_Accountant::generate_apment_patient_bill/$1'); 
    $routes->post('generate_apment_patient_bill/(:any)', 'Medical_Accountant::generate_apment_patient_bill/$1'); 
    $routes->post('generate_apment_final_patient_bill/(:any)', 'Medical_Accountant::generate_apment_final_patient_bill/$1'); 
    $routes->get('add_patient_payment/(:any)', 'Medical_Accountant::add_patient_payment/$1'); 
    $routes->post('add_patient_payment/(:any)', 'Medical_Accountant::add_patient_payment/$1'); 
    $routes->get('generate_patient_bill/(:any)', 'Medical_Accountant::generate_patient_bill/$1'); 
    $routes->get('generate_initial_pay_bill/(:any)', 'Medical_Accountant::generate_initial_pay_bill/$1'); 

    $routes->get('show_patient_final_payments/(:any)', 'Medical_Accountant::show_patient_final_payments/$1'); 
    $routes->post('show_patient_final_payments/(:any)', 'Medical_Accountant::show_patient_final_payments/$1'); 
    $routes->get('generate_receive_payment_bill/(:any)', 'Medical_Accountant::generate_receive_payment_bill/$1');
    $routes->post('save_admission_fee/(:any)', 'Medical_Accountant::save_admission_fee/$1'); 
    $routes->get('generate_admission_bill/(:any)', 'Medical_Accountant::generate_admission_bill/$1'); 
    $routes->get('admit_patient/(:any)', 'Medical_Accountant::admit_patient/$1'); //Admit Patient - Admission Fee
    $routes->post('search_medicine', 'Medical_Accountant::search_medicine');
    $routes->get('search_medicine', 'Medical_Accountant::search_medicine'); 
    $routes->get('print_slip/(:any)', 'Medical_Accountant::print_slip/$1'); 
    $routes->get('change_patients_status/(:any)', 'Medical_Accountant::change_patients_status/$1'); 
    $routes->get('delete_patients/(:any)', 'Medical_Accountant::delete_patients/$1');
    $routes->get('filter_medicine/(:any)', 'Medical_Accountant::filter_medicine/$1');
    $routes->get('filter_patients/(:any)', 'Medical_Accountant::filter_patients/$1');
    $routes->get('filter_patients_dis_pat/(:any)', 'Medical_Accountant::filter_patients_dis_pat/$1');
    $routes->get('add_medicine_stock/(:any)', 'Medical_Accountant::add_medicine_stock/$1');
    $routes->get('edit_medicine/(:any)', 'Medical_Accountant::edit_medicine/$1');
    $routes->post('update_medicines/(:any)', 'Medical_Accountant::update_medicines/$1');
    $routes->get('delete_medicine/(:any)', 'Medical_Accountant::delete_medicine/$1');
    $routes->get('change_medicine_status/(:any)/(:any)', 'Medical_Accountant::change_medicine_status/$1/$2');

    $routes->get('update_stock/(:any)', 'Medical_Accountant::update_stock/$1');
    $routes->post('update_stock/(:any)', 'Medical_Accountant::update_stock/$1');
    $routes->post('add_to_Cart', 'Medical_Accountant::add_to_Cart');
    $routes->get('generate_clear_dues_bill/(:any)', 'Medical_Accountant::generate_clear_dues_bill/$1');

    $routes->post('search_sales', 'Medical_Accountant::search_sales');
    $routes->get('search_sales', 'Medical_Accountant::search_sales');
    $routes->get('product_sale', 'Medical_Accountant::product_sale');
    $routes->post('product_sale', 'Medical_Accountant::product_sale');
    $routes->get('add_customer_bill_slip','Medical_Accountant::add_customer_bill_slip');
    $routes->post('add_customer_bill_slip','Medical_Accountant::add_customer_bill_slip');    
    $routes->get('todays_sale_records', 'Medical_Accountant::todays_sale_records');
    $routes->get('today_appointments', 'Medical_Accountant::today_appointments');
    $routes->get('search_today_appointments', 'Medical_Accountant::search_today_appointments');
    $routes->post('search_today_appointments', 'Medical_Accountant::search_today_appointments');
    $routes->get('filter_today_appointment/(:any)', 'Medical_Accountant::filter_today_appointment/$1');
    $routes->get('index', 'Medical_Accountant::index');
    $routes->get('add_company', 'Medical_Accountant::add_company');
    $routes->get('Medical_Accountant', 'Medical_Accountant::index');
    $routes->get('add_customer_name', 'Medical_Accountant::add_customer_name');
    $routes->get('all_sale_reports', 'Medical_Accountant::all_sale_reports');
    $routes->get('add_medicine', 'Medical_Accountant::add_medicine');
    $routes->post('upload_company', 'Medical_Accountant::upload_company');
    $routes->post('upload_medicine', 'Medical_Accountant::upload_medicine');
    $routes->get('all_patients', 'Medical_Accountant::all_patients');
    $routes->get('all_appointments', 'Medical_Accountant::all_appointments');
    $routes->get('manage_company', 'Medical_Accountant::manage_company');
    $routes->get('manage_company/(:any)', 'Medical_Accountant::manage_company/$1');
    $routes->post('search_company', 'Medical_Accountant::search_company');
    $routes->post('search_all_appointments', 'Medical_Accountant::search_all_appointments');
    $routes->get('search_all_appointments', 'Medical_Accountant::search_all_appointments');
    $routes->get('search_company', 'Medical_Accountant::search_company');
    
    $routes->get('filter_medicine_cpmpany/(:any)', 'Medical_Accountant::filter_medicine_cpmpany/$1');
    $routes->get('filter_deleted_pat/(:any)', 'Medical_Accountant::filter_deleted_pat/$1');
    $routes->get('filter_admitted_pat/(:any)', 'Medical_Accountant::filter_admitted_pat/$1');
    $routes->get('edit_company_name/(:any)', 'Medical_Accountant::edit_company_name/$1');
    $routes->post('update_company/(:any)', 'Medical_Accountant::update_company/$1');
    $routes->get('change_company_status/(:any)', 'Medical_Accountant::change_company_status/$1');
    $routes->get('delete_company/(:any)', 'Medical_Accountant::delete_company/$1');
    $routes->get('manage_medicine', 'Medical_Accountant::manage_medicine');
    $routes->get('expiry_products', 'Medical_Accountant::expiry_products');
    $routes->get('change_password', 'Medical_Accountant::change_password');
    $routes->post('change_password', 'Medical_Accountant::change_password');
    $routes->get('show_products/(:any)', 'Medical_Accountant::show_products/$1');
    
    #Medical Accountant Login/Settings_Section/Forget_Password
    #Medical Accountant Login/Company Section/Manage_Company/Filter/New_Company
    #$routes->get('/', 'Accountant::dashboard');
    #$routes->get('invoices', 'Accountant::invoices');
});#***********************************************************************************


#👤👤👤👤👤👤👤👤👤👤👤👤👤 Patient - Public routes — no filter 👤👤👤👤👤👤👤👤👤👤👤👤👤

#Patient Login  SECTION START-

$routes->get('Patients_login', 'Patients_login::index');
$routes->get('Patients_login/login', 'Patients_login::patient_login');
$routes->post('Patients_login/login', 'Patients_login::patient_login');

$routes->get('Patients_login/register', 'Patients_login::signup');
 $routes->get('Patient_logins/login_patients', 'Patient_logins::login_patients');
$routes->post('Patients_login/create_patients_account', 'Patients_login::create_patients_account');
$routes->get('Patients_login/create_patients_account', 'Patients_login::create_patients_account');
$routes->get('Patients_login/Logout', 'Patients_login::Logout');

$routes->get('Patients/change_appointment_status/(:any)', 'Patients::change_appointment_status/$1');

#Patient Login/Patients Registration/I_have_Already_An_Account
#Patient Login/Patients Registration/Signin/Acccount Access Control/Create_an_Account
#$routes->get('Patients_login/index', 'Patients_login::index');
#$routes->get('Patients/review_hosp_activity', 'Patients::review_hosp_activity');  
#$routes->get('Patients/forget_password', 'Patients::forget_password');
#$routes->post('Patients/forget_password', 'Patients::forget_password');
#$routes->post('Patients/reset_password/(:any)', 'Patients::reset_password/$1');
#$routes->get('Patients/reset_password/(:any)', 'Patients::reset_password/$1');
#$routes->get('Patients/filter_appointment_patient/(:any)', 'Patients::filter_appointment_patient/$1');
#$routes->post('Patients/show_patient_final_payments', 'Patients::show_patient_final_payments');


#Forget Password - rountes START
$routes->get('Patients_login/forget_password', 'Patients_login::forget_password');//Render email input form
$routes->post('Patients_login/forget_password', 'Patients_login::forget_password');//Send link to email
$routes->get('Patients_login/reset_password/(:any)', 'Patients_login::reset_password/$1');//Render new/confirm pass form
$routes->post('Patients_login/reset_password/(:any)', 'Patients_login::reset_password/$1');//Forget password
#Forget Password - rountes END

#$routes->get('Patients/cropImage', 'Patients::cropImage'); //Upload profile pic
#$routes->post('Patients/cropImage', 'Patients::cropImage'); //Upload profile pic

#$routes->get('reports', 'Patient::reports');


// 👤👤👤👤👤👤👤👤👤👤👤👤 Patient - login must routings 👤👤👤👤👤👤👤👤👤👤👤👤👤
$routes->group('Patients', ['filter' => 'patientAuth'], function($routes) {
    $routes->get('view_doctor', 'Patients::view_doctor');
    $routes->post('update_profile', 'Patients::update_profile');
    $routes->get('change_Patients_password', 'Patients::change_Patients_password');
    $routes->get('permanent_del_all_apmnt/(:any)', 'Patients::permanent_del_all_apmnt/$1'); 

    $routes->post('add_appointment_fee', 'Patients::add_appointment_fee');
    $routes->get('add_appointment_fee', 'Patients::add_appointment_fee');
    $routes->get('run_appointment_fee_form', 'Patients::run_appointment_fee_form');
    $routes->get('run_appointment_fee_form/(:any)', 'Patients::run_appointment_fee_form/$1');
   
    $routes->get('index', 'Patients::index');   
    $routes->post('patients_search_lookup', 'Patients::patients_search_lookup');  //Custom 
    $routes->get('all_appointments/(:any)', 'Patients::all_appointments/$1'); 
    $routes->get('all_appointments', 'Patients::all_appointments');

    $routes->post('change_Patients_password', 'Patients::change_Patients_password');
    $routes->get('pick_slots', 'Patients::pick_slots');
    $routes->post('book_appointment', 'Patients::book_appointment');
    $routes->get('view_profile', 'Patients::view_profile');
    
    $routes->post('booked_appointment_dash', 'Patients::booked_appointment_dash');
    $routes->get('booked_appointment_dash', 'Patients::booked_appointment_dash');
    $routes->get('success', 'Patients::success');
    $routes->get('view_receipt', 'Patients::view_receipt');
    $routes->get('del_pay_receipt/(:any)', 'Patients::del_pay_receipt/$1');

    $routes->get('discharge_report/(:any)', 'Patients::discharge_report/$1');
    $routes->get('discharge_report', 'Patients::discharge_report');
    $routes->get('print_slip/(:any)', 'Patients::print_slip/$1');
    
    #$routes->get('view_doctor', 'Patients::view_doctor');  
    $routes->get('booked_doctor/(:any)', 'Patients::booked_doctor/$1');
    $routes->post('booked_doctor_appointment', 'Patients::booked_doctor_appointment');  
    $routes->get('review_hospital', 'Patients::review_hospital');  
    $routes->post('review_hosp_activity', 'Patients::review_hosp_activity');  

    $routes->get('doctors_available_slots_neo/(:any)', 'Patients::doctors_available_slots_neo/$1');
    $routes->get('doctors_available_slots/(:any)', 'Patients::doctors_available_slots/$1'); 
    $routes->get('available_selected_doctor_slots', 'Patients::available_selected_doctor_slots'); 
    $routes->get('filter_appointment_patients/(:any)', 'Patients::filter_appointment_patients/$1');
    $routes->get('filter_all_appointments/(:any)', 'Patients::filter_all_appointments/$1');
    $routes->get('change_all_appointment_status/(:any)', 'Patients::change_all_appointment_status/$1');
    $routes->get('getset_dr_slots_neo', 'Patients::getset_dr_slots_neo');
    $routes->get('getset_dr_slots', 'Patients::getset_dr_slots');
    $routes->get('patient_login', 'Patients::patient_login');
    $routes->get('filter_today_appointments/(:any)', 'Patients::filter_today_appointments/$1');
    $routes->post('clear_final_dues/(:any)', 'Patients::clear_final_dues/$1'); 
    $routes->get('show_patient_final_payments', 'Patients::show_patient_final_payments'); 
    $routes->post('upload_profile_pic', 'Patients::upload_profile_pic');
    $routes->get('change_today_appointment_status/(:any)', 'Patients::change_today_appointment_status/$1'); 
    $routes->post('search_all_appointments/(:any)', 'Patients::search_all_appointments/$1'); 
    $routes->get('search_all_appointments','Patients::search_all_appointments');
    $routes->post('search_all_appointments','Patients::search_all_appointments');
    $routes->post('save_review_hosp_activity','Patients::save_review_hosp_activity');

    

});#************************************************************************************



$routes->get('Home/generateEncryptedNumber', 'Home::generateEncryptedNumber');
$routes->get('Home/doctor_appointment', 'Home::doctor_appointment');
$routes->get('Home/pick_slots', 'Home::pick_slots');
$routes->get('Home/doctor_appointment/(:any)', 'Home::doctor_appointment/$1');
$routes->get('Home/alldoctors', 'Home::view_doctor');

#Medical Accountant Login  SECTION START-
$routes->get('Login/create_blood_acc', 'Login::create_blood_acc');
$routes->post('Login/create_blood_acc', 'Login::create_blood_acc');
$routes->get('Login/create_med_account', 'Login::create_med_account');
$routes->post('Login/create_medical_acc_account', 'Login::create_medical_acc_account');
$routes->get('Login/create_doctor', 'Login::create_doctor');    
$routes->post('Login/Create_doctor_account', 'Login::Create_doctor_account');
$routes->get('Login/Create_doctor_account', 'Login::Create_doctor_account'); 

#News From Blog Section
$routes->get('/Home/view_blog/(:any)', 'Home::view_blog/$1'); 
#Meet Our Doctors Section
$routes->post('/Home/book_appointment', 'Home::book_appointment');
$routes->post('/Home/add_appointment_fee', 'Home::add_appointment_fee');
$routes->get('/Home/add_appointment_fee', 'Home::add_appointment_fee');
$routes->get('Home/run_appointment_fee_form', 'Home::run_appointment_fee_form');

#Home Page Section:
$routes->get('/Home/index', 'Home::index');
$routes->get('/Home/about_us', 'Home::about_us');
$routes->get('/Blood_bank_registration/index', 'Blood_bank_registration::index');
//$routes->get('/Home/gallery', 'Home::gallery');
$routes->get('/Home/gallery', 'Home::gallery');
$routes->get('/Home/blog_archive', 'Home::blog_archive');
$routes->get('/Home/contact_us', 'Home::contact_us');
$routes->get('/Home/terms_condition', 'Home::terms_condition');

#Contact Us Form Section:
$routes->post('/Home/contact_us', 'Home::contact_us');
$routes->get('/Home/success', 'Home::success');

#Book Appointment Section:
$routes->post('/Home/book_appointment_section', 'Home::book_appointment_section');
$routes->get('/Home/book_appointment_section', 'Home::book_appointment_section');
$routes->get('/Home/privacy_policy', 'Home::privacy_policy');

/*
#Book An Appointment 
$routes->get('/book-doctor-appointment', 'BookDoctorAppointment::index');
$routes->post('/book-doctor-appointment/submit-appointment','BookDoctorAppointment::submitAppointment');
$routes->get('/Book_Doctor_Appointment/submitAppointment','Book_Doctor_Appointment::submitAppointment');
$routes->get('/appointment/success', 'appointment::success');
$routes->get('/AppointmentsController/book', 'AppointmentsController::book');
$routes->get('SlotsController/book', 'SlotsController::book');
$routes->get('DoctorController/schedule', 'DoctorController::schedule');
$routes->get('DoctorController/schedule/(:any)', 'DoctorController::schedule/$1');
$routes->get('DoctorController/doctor', 'DoctorController::doctor');

$routes->get('DoctorController/success', 'DoctorController::success');
#Save Selected Slots
$routes->post('DoctorController/save-slots', 'DoctorController::saveSlots');
$routes->get('DoctorController/slots', 'DoctorController::slots');
$routes->get('Login/blood_bank_accountant', 'Login::blood_bank_accountant');
*/

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}