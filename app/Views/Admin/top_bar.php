<head>
<?= helper('Form'); ?>
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('admin_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'admin_users', session()->get('admin_session_id'));
  } ?>

<?php
  $name[0] = new \stdClass; //stdClass object
  $name[0]->full_name = '';
  if(session()->has('admin_session_id')) {
    $name = get_pic_by_id('full_name', 'admin_users', session()->get('admin_session_id')); 
  } ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Admin/top_bar_css_file.php'); ?>
</head>
<div class="navbar-fixed">
  <nav class="">
    <div>
      <div class="nav-wrapper">
        <?php if(session()->has('admin_session_id')) { ?> <!--Redirect to Admin Dashboard -->
          <a href="<?= base_url('/Admin'); ?>" class="brand-logo"><img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="img_div"></a> 
        <?php } else { ?>
        <!--Redirect to Admin Home Page -->
        <a href="<?= base_url('/mgt'); ?>" class="brand-logo"><img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="img_div"></a>
      <?php } ?>
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span> Menu</a>
        <ul class="right hide-on-med-and-down">
          <li>
            <a href="#!" class="dropdown-trigger" data-target="department_dropdown"><span class="fa fa-tasks"></span> Manage Assets</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="doctor_dropdown"><span class="fa fa-user-md"></span> Doctors</a>
          </li>

          <li>
            <a href="#!" class="dropdown-trigger" data-target="services_dropdown"><span class="fa fa-wheelchair"></span> Patients</a>
          </li>

          <li>
            <a href="#!" class="dropdown-trigger" data-target="medicines_dropdoen"><span class="fa fa-plus-square"></span> Medical</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="verification_dropdown"><span class="fa fa-check"></span> Verification</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="appointment_dropdown"><span class="fa fa-calendar"></span> Appointment </a>
          </li>

          <li>
            <a href="#!" class="dropdown-trigger" data-target="account_dropdown"><span class="fa fa-user-circle-o"></span> Accounts</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="frontend_settings_dropdown"><span class="fa fa-gear"></span> Settings</a>
          </li>
          <li>
            <a href="<?= base_url('Admin/home'); ?> #!" class="dropdown-trigger bg_hver" data-target="settings_dropdown">
            <!-- <img src="<//?= base_url('public/assets/images/dr.default_pic.svg'); ?>" class="prfle_pic_div">
              <//?php if (isset($userdata->username)) {
                $userdata->username;
              } -->
              <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
              <img src="<?= base_url('public/uploads/accounts/admin/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
              <?php } else  { ?>
                <img src="<?= base_url('public/uploads/accounts/profile_pic.png'); ?>" class="prfle_pic_div">
              <?php } if (isset($userdata->username)) {
                $userdata->username;
              }
              ?>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<!---Side menu Section Start --->
<ul class="sidenav collapsible" id="side_menu">
  <li><a class="fcus_hver" href="<?= base_url('Home/index'); ?>" target="_blank"><i class="fa fa-home"></i> Home</a></li>
  <li><a class="fcus_hver" href="<?= base_url('Admin/home'); ?>"><i class="fa fa-dashboard fcus_hver1"></i> Dashboard</a></li>
  <li>
    <div class="collapsible-header"><i class="fa fa-tasks fnt_size"></i> Manage Assets</div>
    <div class="collapsible-body">
<ul>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_department'); ?>"><span class="fa fa-sitemap"></span> Add Department</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_department'); ?>"><span class="fa fa-tasks"></span> Manage Department</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_ward'); ?>"><span class="fa fa-sitemap"></span> Add Ward</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_ward'); ?>"><span class="fa fa-sitemap"></span> Manage Wards</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/browse_wards_csv'); ?>"><span class="fa fa-sitemap"></span> Import Wards</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_bed'); ?>"><span class="fa fa-sitemap"></span> Add Beds</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_bed'); ?>"><span class="fa fa-sitemap"></span> Manage Beds</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/browse_beds_csv'); ?>"><span class="fa fa-sitemap"></span> Import Beds</a>
  </li>
      </ul>
    </div>
  </li>


  <li>
    <div class="collapsible-header"><i class='fas fa-user-md'></i>Doctors</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_doctor'); ?>"><span class="fa fa-user-md"></span> Add Doctor</a>
        </li>

        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor'); ?>"><span class="fas fa-tasks"></span> Manage Doctors</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor_fee'); ?>"><span class="fas fa-calculator"></span> Manage Doctors Fee</a>
        </li>
      </ul>
    </div>
  </li>

  <li>
    <div class="collapsible-header"><i class="fa fa-wheelchair"></i>Patients</div>
    <div class="collapsible-body">
      <ul>

        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_patients'); ?>"><span class="fas fa-hand-holding-medical"></span> Manage Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor_discharge_patients'); ?>"><span class="fas fa-procedures"></span> Doctor Dischrage Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_discharge_patient'); ?>"><span class="fas fa-procedures"></span> Dischrage Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_revisit_patient'); ?>"><span class="fas fa-prescription"></span> Revisit Patients</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-plus-square"></i> Medical</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_med_company'); ?>"><span class="fa  fa-building"></span> Add Medicines Company</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_med_company'); ?>"><span class="fas fa-briefcase-medical"></span> Manage Medicines Company</a>
        </li>
        
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/med_category'); ?>"><span class="fa  fa-building"></span> Add Medicines Category</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_med_category'); ?>"><span class="fas fa-briefcase-medical"></span> Manage Medicines Category</a>
        </li>

        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_medicine'); ?>"><span class="fas fa-first-aid"></span> Add Medicines</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_medicine'); ?>"><span class="fas fa-briefcase-medical"></span> Manage Medicines</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/today_medical_cus_report'); ?>"><span class="fas fa-chalkboard-teacher"></span> Today Customer Report</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/all_sale_reports'); ?>"><span class="fas fa-male"></span> All Customer Report</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-check"></i>Verification</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/accountant_verification'); ?>"><span class="fa fa-check"></span> Accountant Verification</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/doctor_verification'); ?>"><span class="fa fa-check"></span> Doctor Verification</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/doctor_email_register'); ?>"><span class="fa fa-envelope"></span> Doctor Email Register</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/patients_review'); ?>"><span class="fa fa-share"></span> Patients Review</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/blood_bank_admin'); ?>"><span class="fa fa-check"></span> View Blood Bank Admin Verification
          </a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-calendar"></i> Appointment</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <!--
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/fetch_patients'); ?>"><span class="fa fa-plus-square"></span> Add Appointments
          </a>-->
            <a class="brdr_dash" href="<?= base_url('admin/doctors_available_slots/0'); ?>"><span class="fa fa-plus dropdown_color col_blu"></span>   Add  Appointment</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/all_appointment'); ?>"><span class="fa fa-calendar"></span> All Appointments</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/today_appointments'); ?>"><span class="fa fa-calendar"></span> Today's Appointment</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/canceled_appointments'); ?>"><span class="fas fa-calendar-times"></span> Cancelled Appointments</a>
        </li>

      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-user-circle-o"></i>Accounts</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Login/create_doctor'); ?>"><span class="fa fa-plus"></span> Create Doctor Login Account</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor_account'); ?>"><span class="fa fa-hourglass-start"></span> Manage Doctor's Account</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Login/create_med_account'); ?>"><span class="fa fa-plus"></span> Medical Account</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_medical_acc'); ?>"><span class="fa fa-hourglass-start"></span> Manage Medical Account</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/blood_bank_accountant'); ?>"><span class="fa fa-users"></span> Blood Accountant</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_blood_accc'); ?>"><span class="fa fa-hourglass-start"></span> Manage Blood Accountant</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/check_login_activity'); ?>"><span class="fa fa-hourglass-start"></span>Check Login Activity</a>
        </li>

      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-gear"></i> Settings</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_slider_image'); ?>"><span class="fa fa-image"></span> Upload Slider Image</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_slider'); ?>"><span class="fa fa-hourglass-start"></span> Manage Slider Image</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/image_gallery'); ?>"><span class="fa fa-image"></span> Image Gallery</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_image_gallery'); ?>"><span class="fa fa-tasks"></span> Manage Image Gallery</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_blogs'); ?>"><span class="fa fa-tasks"></span> Add Blog</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_blogs'); ?>"><span class="fa fa-tasks"></span> Manage Blogs</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/contact_us'); ?>"><span class="fa fa-share"></span> Contact us</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_term_condition'); ?>"><span class="fa fa-edit"></span> Add Terms & Conditions</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/terms_conditions_edit'); ?>"><span class="fa fa-edit"></span> Manage Terms & Conditions</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/policy'); ?>"><span class="fas fa-user-shield"></span> Manage Policy</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-sign-out"></i>Logout</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a href="<?= base_url('Login/Logout'); ?>"><span class="fa fa-sign-out"></span> Logout</a>
        </li>
      </ul>
    </div>
  </li>
</ul>
<!---Side menu Section End --->

<!---Department Dropdown Section ---->
<ul class="dropdown-content" id="department_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_department'); ?>"><span class="fa fa-sitemap col_blu"></span> Add Department</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_department'); ?>"><span class="fa fa-tasks col_blu"></span> Manage Department</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_ward'); ?>"><span class="fa fa-sitemap col_blu"></span> Add Ward</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_ward'); ?>"><span class="fa fa-sitemap col_blu"></span> Manage Wards</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/browse_wards_csv'); ?>"><span class="fa fa-sitemap col_blu"></span> Import Wards</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_bed'); ?>"><span class="fa fa-sitemap col_blu"></span> Add Beds</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_bed'); ?>"><span class="fa fa-sitemap col_blu"></span> Manage Beds</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/browse_beds_csv'); ?>"><span class="fa fa-sitemap col_blu"></span> Import Beds</a>
  </li>
</ul>
<!---Department Dropdown Section ---->


<!---Announcement Dropdown  --->
<ul class="dropdown-content" id="doctor_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_doctor'); ?>"><span class="fa fa-user-md col_blu"></span> Add Doctor</a>
  </li>

  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor'); ?>"><span class="fas fa-tasks col_blu"></span> Manage Doctors
    </a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor_fee'); ?>"><span class="fas fa-calculator col_blu"></span> Manage Doctors Fee</a>
  </li>

</ul>
<!---Announcement Dropdown  --->
<!---Services Dropdown --->
<ul class="dropdown-content" id="services_dropdown">


  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_patients'); ?>"><span class="fas fa-hand-holding-medical col_blu"></span> Manage Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_today_discharged_patient'); ?>"><span class="fas fa-procedures col_blu"></span> Manage Today's Discharged Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_all_discharged_patient'); ?>"><span class="fas fa-procedures col_blu"></span> Manage All Discharged Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_revisited_patients'); ?>"><span class="fas fa-prescription col_blu"></span> Revisit Patients</a>
  </li>
</ul>
<!---Services Dropdown --->

<!---medicines Dropdown --->
<ul class="dropdown-content" id="medicines_dropdoen">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_med_company'); ?>"><span class="fa fa-building col_blu"></span> Add Medicines Company</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_med_company'); ?>"><span class="fas fa-briefcase-medical col_blu"></span> Manage Medicines Company</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/med_category'); ?>"><span class="fa fa-building col_blu"></span> Add Medicines Category</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_med_category'); ?>"><span class="fas fa-briefcase-medical col_blu"></span> Manage Medicines Category</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_medicine'); ?>"><span class="fas fa-first-aid col_blu"></span> Add Medicines</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_medicine'); ?>"><span class="fas fa-briefcase-medical col_blu"></span> Manage Medicines</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/todays_sale_records'); ?>"><span class="fas fa-chalkboard-teacher col_blu"></span> Today Customer Report</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/all_sale_reports'); ?>"><span class="fas fa-male col_blu"></span> All Customer Report</a>
  </li>
</ul>
<!---medicines Dropdown --->

<!---Settings Dropdown Script --->
<ul class="dropdown-content" id="account_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Login/create_doctor'); ?>"><span class="fa fa-plus col_blu"></span> Create Doctor Login Account</a>
  </li>

  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_doctor_account'); ?>"><span class="fa fa-user-md col_blu"></span> Manage Doctor's Account</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Login/create_med_account'); ?>"><span class="fa fa-plus col_blu"></span> Create Medical Account</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_medical_acc'); ?>"><span class="fa fa-hourglass-start col_blu"></span> Manage Medical Account</a>
  </li>

  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Login/blood_bank_accountant'); ?>"><span class="fa fa-users col_blu"></span> Create Blood Accountant</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_blood_acc'); ?>"><span class="fa fa-hourglass-start col_blu"></span> Manage Blood Accountant</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/check_login_activity'); ?>"><span class="fa fa-hourglass-start col_blu"></span> Check Login Activity</a>
  </li>
</ul>
<!---Settings Dropdown Script --->

<!---Verification Dropdown  START ---->
<ul class="dropdown-content" id="verification_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/accountant_verification'); ?>"><span class="fa fa-check col_blu"></span> View Accountant Verification</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/frontdesk_verification'); ?>"><span class="fa fa-check col_blu"></span> View Frontdesk Verification</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/doctor_verification'); ?>"><span class="fa fa-check col_blu"></span> View Doctor Verification</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/blood_bank_admin'); ?>"><span class="fa fa-check col_blu"></span> View Blood Bank Admin Verification</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/doctor_email_register'); ?>"><span class="fa fa-envelope col_blu"></span> Doctor Email Register</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/patients_review'); ?>"><span class="fa fa-share col_blu"></span> Patients Review</a>
  </li>
</ul>
<!---Verification Dropdown END---->

<!----Appointment Dropdown ---->
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <!--
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/fetch_patients'); ?>"><span class="fa fa-plus-square col_blu"></span> Add Appointments</a>
    -->
     <a class="brdr_dash" href="<?= base_url('Admin/doctors_available_slots/0'); ?>"><span class="fa fa-plus dropdown_color col_blu"></span>  Book Appointment</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/all_appointments'); ?>"><span class="fa fa-calendar col_blu"></span> All Appointments</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/today_appointments'); ?>"><span class="fa fa-calendar col_blu"></span> Today Appointment</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/canceled_appointments'); ?>"><span class="fas fa-calendar-times col_blu"></span> Cancelled Appointments</a>
  </li>
</ul>
<!----Appointment Dropdown ---->

<!---Settings Dropdown ----->
<ul class="dropdown-content" id="frontend_settings_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_slider_image'); ?>"><span class="fa fa-plus col_blu"></span> Add Slider Image</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_slider'); ?>"><span class="fa fa-tasks col_blu"></span> Manage Slider</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/image_gallery'); ?>"><span class="fa fa-upload col_blu"></span> Upload Image Gallery</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_image_gallery'); ?>"><span class="fa fa-image col_blu"></span> Manage Image Gallery</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_blogs'); ?>"><span class="fa fa-image col_blu"></span> Add Blog</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/manage_blogs'); ?>"><span class="fa fa-tasks col_blu"></span> Manage Blogs</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/contact_us'); ?>"><span class="fa fa-share col_blu"></span> Contact us</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/add_term_condition'); ?>"><span class="fa fa-edit col_blu"></span> Add Terms & Conditions</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/terms_conditions_edit'); ?>"><span class="fa fa-edit col_blu"></span> Manage Terms & Conditions</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Admin/policy'); ?>"><span class="fas fa-user-shield col_blu"></span> Manage Policy</a>
  </li>
</ul>

<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name brdr_dash col_gry"><span class="fas fa-user-tie fa_icon col_blu"></span>
    <?php
      if(isset($name[0]->full_name) && $name[0]->full_name != '') { 
        echo $name[0]->full_name;
    }else{
      echo "Admin";
    }
    ?>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Admin/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span> Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Admin/change_admin_password'); ?>"><span class="fas fa-unlock-alt col_blu"></span> Change Password</a>
  </li>
  <li> 
    <a class="col_gry" href="<?= base_url('Login/Logout'); ?>"><span class="fa fa-sign-out col_blu"></span> Logout</a>
  </li>

</ul>
<!---Settings Dropdown ----->

<!---Php Meassge Show --->
<div class="stus_msg">
  <?php if (session()->getTempdata('success')) : ?>
    <div class="card success-message cutom_messge_styl">
      <div class="card-content" id="popup_message">
        <span class="fa fa-check"></span> <?= session()->getTempdata('success'); ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if (session()->getTempdata('error')) : ?>
    <div class="card error-message cutom_messge_styl">
      <div class="card-content" id="popup_error_message">
        <span class="fa fa-times"></span> <?= session()->getTempdata('error'); ?>
      </div>
    </div>
  <?php endif; ?>
</div>


<script>
  // Add JavaScript to hide messages with the specified classes after 5 seconds
  setTimeout(function() {
    var sucesMsgs = document.querySelectorAll('.success-message');
    var errMsg = document.querySelectorAll('.error-message');

    sucesMsgs.forEach(function(message) {
      message.style.display = 'none';
    });

    errMsg.forEach(function(message) {
      message.style.display = 'none';
    });
  }, 5000); // 5000 milliseconds = 5 seconds
</script>
<!---Php Meassge Show --->