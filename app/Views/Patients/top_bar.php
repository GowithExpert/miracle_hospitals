<!-- Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
@Description: The code of the released Hospital software, does NOT lie under
GLP (General Public License) But it has proprietary copyrights. The purpose of the
Informing for public that, the Hospital web based mobile responsible application its associated
different roles are protected by the mentioned copyrights. *
@Version: Miracle Hospital - 1.0
@Author: Neoark Software
@Address: Plot #8, Street #1, Ganga Sahay Colony (Near Govt Senior Secondary
School), Mandoli (Industrial Area) North East Delhi - 110093 (India)
@Email: sales@neoarksoftware.com | support@neoarksoftware.com
@website: www.neoarks.com
@Phone: +91-880-090-0164
Date: 21st August, 2023 -->

<head>
<?= helper('Form'); ?> <!--Add helper in app/Config/Autoload.php or manully in all required files -->
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('patient_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'patients_login', session()->get('patient_session_id'));
    //echo "<pre>"; print_r($pic);die;
  } ?>

<?php
  $name[0] = new \stdClass; //stdClass object
  $name[0]->username = '';
  if(session()->has('patient_session_id')) {
    $name = get_pic_by_id('username', 'patients_login', session()->get('patient_session_id')); 
  } ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Patients/top_bar_css_file.php'); ?>
</head>
<div class="navbar-fixed">
  <nav class="">
    <div class="container-fluid">
      <div class="nav-wrapper">
        <a href="<?= base_url('Patients/index'); ?>" class="brand-logo">  
          <img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="brand-logo1">
        </a>
        
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span>  Menu</a>
        <ul class="right hide-on-med-and-down">
          
          <li>
            <a href="<?= base_url('Patients/index'); ?>" class="dropdown-trigger" data-target="appointment_dropdown"><span class="fa fa-calendar"></span>   Appointments</a>
          </li>
          <li>
            <a href="<?= base_url('Patients/view_receipt'); ?>"><span class="fas fa-receipt"></span>   View Receipt</a>
          </li>
          <li>
    <?php
    // Assuming $patientId is the variable that contains the patient's ID
    $patientId = 0; // Replace with the actual patient ID or fetch it from your data source
    ?>
    <a href="<?= base_url('Patients/discharge_report'); ?>" target="_blank"><span class="fa fa-file-text"></span> Discharge Report</a>
    </li>
          <li>
            <a target="_blank" href="<?= base_url('Patients/view_doctor'); ?>"><span class="fa fa-user-md"></span>   All Doctors</a>
          </li>
          <li>
            <a href="<?= base_url('Patients/review_hospital'); ?>"><span class="fas fa-tasks"></span>   Ratings & Reviews</a>
          </li>
          <li>
            
            <a href="<?= base_url('Patients/index'); ?>" class="dropdown-trigger bg_hver" data-target="settings_dropdown">  
            <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
                  <?php if(is_file('public/uploads/patients/' . $pic[0]->profile_pic)) { ?>
                  <img src="<?= base_url('public/uploads/patients/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
                  <?php } else  { ?>
                  <img src="<?= base_url('public/assets/images/patient_default.svg'); ?>" class="prfle_pic_div">
                  <?php } 
              }
              else {?> <img src="<?= base_url('public/assets/images/patient_default.svg'); ?>" class="prfle_pic_div">
              <?php } ?>
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
  <li><a class="fcus_hver" href="<?= base_url('Patients/index'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li>
    <div class="collapsible-header"><i class="fa fa-calendar"></i> Appointments</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <?php $patient_session = session()->get('patient_session_id');
          if (!isset($patient_session) || $patient_session == '') {
            $patient_session = 0;
          } ?>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Patients/doctors_available_slots/' . $patient_session); ?>"><span class="fa fa-calendar"></span> Book New Appointment</a>
        </li>
        <li>
          <a class="brdr_dash" href="<?= base_url('Patients/all_appointments/') . session()->get('patient_session_id'); ?>"><span class="fa fa-calendar"></span> All Appointments</a>
        </li>
      </ul>
    </div>
  </li>

  <li>
    <div class="collapsible-header"><i class='fas fa-receipt'></i> View Receipt</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="brdr_dash" href="<?= base_url('Patients/view_receipt'); ?>"><span class="fas fa-receipt"></span> View Recept</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-file-text"></i> Discharge Report</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="brdr_dash" href="<?= base_url('Patients/discharge_report'); ?>" target="_blank"><span class="fa fa-file-text"></span> Discharge Report </a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class='fas fa-user-md'></i> All Doctors</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="brdr_dash" target="_blank" href="<?= base_url('Patients/view_doctor'); ?>"><span class="fas fa-user-md"></span> All Doctors </a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fas fa-tasks"></i> Ratings & Reviews</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="brdr_dash" href="<?= base_url('Patients/review_hospital'); ?>"><span class="fas fa-tasks"></span>   Ratings & Reviews </a>
        </li>
      </ul>
    </div>
  </li>

  <li>
    <div class="collapsible-header"><i class="fas fa-gear"></i> Settings</div>
    <div class="collapsible-body">
      <ul>
      <li>
        <a class="fcus_hver brdr_dash" href="<?= base_url('Patients/view_profile'); ?>"><span class="fas fa-user-edit"></span> Edit Profile</a>
      </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Patients/change_Patients_password'); ?>"><span class="fas fa-unlock-alt"></span> Change Password</a>
        </li>
        <li>
          <a class="brdr_dash" href="<?= base_url('Patients_login/Logout'); ?>"><span class="fa fa-sign-out"></span> Logout</a>
        </li>
      </ul>
    </div>
  </li>


</ul>
<!---Side menu Section End --->

<!---Announcement Dropdown  --->

<!---Products Dropdown --->
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <?php $patient_session = session()->get('patient_session_id');
    if (!isset($patient_session) || $patient_session == '') {
      $patient_session = 0;
    } ?>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Patients/doctors_available_slots/' . $patient_session); ?>"><span class="fa fa-calendar col_blu"></span> Book New Appointment</a>
  </li>
 
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Patients/all_appointments'); ?>"><span class="fa fa-calendar col_blu"></span> All Appointments</a>
  </li>

</ul>
<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name col_gry"><span class="fas fa-wheelchair fa_icon col_blu"></span>
     <?php if (isset($userdata->username)) { echo $userdata->username; } else { echo "Patient"; } ?>
  </li>
  
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Patients/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span>   Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Patients/change_Patients_password'); ?>"><span class="fas fa-unlock-alt col_blu"></span>   Change Password</a>
  </li>
  <li>
    <a class="brdr_dash col_gry" href="<?= base_url('Patients_login/Logout'); ?>"><span class="fa fa-sign-out col_blu"></span> Logout</a>
  </li>

</ul>
<!---Products Dropdown --->

<!---Php Meassge Show --->
<div class="stus_msg">
  <?php if (session()->getTempdata('success')) : ?>
    <div class="card success-message cutom_messge_styl">
      <div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getTempdata('error')) : ?>
    <div class="card error-message cutom_messge_styl">
      <div class="card-content" id="error_message"><?= session()->getTempdata('error'); ?></div>
    </div>
  <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  // Add JavaScript to hide messages with the specified classes after 5 seconds
  $(document).ready(function() {
  setTimeout(function() {
    $('.success-message').hide();
    $('.error-message').hide();
  }, 5000);
});
 // 5000 milliseconds = 5 seconds
</script>



<!---Php Meassge Show --->