<head>
<?= helper('Form'); ?>
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('frontdesk_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'blood_bank_users', session()->get('frontdesk_session_id'));
  } ?>

<?php
  $name[0] = new \stdClass; //stdClass object
  $name[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('frontdesk_session_id')) {
    $name = get_pic_by_id('username', 'blood_bank_users', session()->get('frontdesk_session_id'));
  } ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Blood_bank/Donor/top_bar_css_file.php'); ?>
</head>

<div class="navbar-fixed">
  <nav class="">
    <div>
      <div class="nav-wrapper">
        <a href="<?= base_url('Frontdesk/index'); ?>" class="brand-logo">  
          <img class="brand-logo1" src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>">
         
        </a>
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span>  Menu</a>
        <ul class="right hide-on-med-and-down">
          <!-- <li>
            <a href="<//?= base_url('Frontdesk') . '#!' ?>"><span class="fa fa-dashboard"></span>  Dashboard</a>
          </li> -->
          <li>
            <a href="<?= base_url('Frontdesk') . '#!' ?>" class="dropdown-trigger nav_color" data-target="appointment_dropdown"><span class="fa fa-calendar"></span>   Appointments</a>
          </li>
          <li>
            <a href="<?= base_url('Frontdesk/view_doctor'); ?>"><span class="fa fa-user-md"></span>   View Doctor</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="services_dropdown"><span class="fa fa-wheelchair"></span>  Patients</a>
          </li>

          <!-- <li>
              <a href="<//?= base_url('Frontdesk/manage_patients'); ?>"><span class="fa fa-user"></span>  MP</a>
            </li> -->
          <li>
            <a href="#!" class="dropdown-trigger" data-target="donor_dropdown"><span class="fas fa-hand-holding-heart"></span>  Donors</a>
          </li>
          <!-- <li>
              <a href="<//?= base_url('Frontdesk/manage_discharge_patients'); ?>"><span class="fa fa-user"></span>  Manage Desc Patients</a>
            </li> -->

          <li>
            <!-- <a href="<//?= base_url('Blood_bank_donor/blood_bank'); ?>"><span class="fas fa-dna"></span>   Blood Bank</a> -->
            <a href="<?= base_url('Frontdesk/blood_bank'); ?>"><span class="fas fa-dna"></span>   Blood Bank</a>
          </li>

          <li>
            <!-- <a href="<//?= base_url('Blood_bank_donor/search_donor'); ?>"><span class="fa fa-search" ></span>    Search Donor</a> -->
            <a href="<?= base_url('Frontdesk/search_donor'); ?>"><span class="fa fa-search"></span>   Search Donor</a>
          </li>

          <li>
           
            <a href="<?= base_url('Frontdesk/index'); ?> #!" class="dropdown-trigger bg_hver" data-target="settings_dropdown">  
            <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
            
              <img src="<?= base_url('public/uploads/accounts/frontdesk/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
              <?php } else  { ?>
                <img src="<?= base_url('public/assets/images/frontdesk_default_pic.svg'); ?>" class="prfle_pic_div">
              <?php } if (isset($userdata->username)) {
                $userdata->username;
              }
              ?>
              <!-- <img src="<//?= base_url('public/assets/images/dr.default_pic.svg'); ?>" class="prfle_pic_div">
              <//?php if (isset($userdata->username)) {
                $userdata->username;
              } ?> -->
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<!-- Side menu Section Start  -->
<ul class="sidenav collapsible" id="side_menu">
  <li><a href="<?= base_url('Home/index'); ?>" target="_blank"><i class="fa fa-home"></i> Home</a></li>
  <!-- <li><a href="<//?= base_url('Patients/index'); ?>">Dashboard</a></li> -->
  <li>
    <div class="collapsible-header"><i class="fa fa-dashboard"></i> Dashboard</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="btn_hver" href="<?= base_url('Frontdesk') . '#!' ?>"><span class="fa fa-dashboard"></span>  Dashboard</a>
        </li>
      </ul>
    </div>
  </li>
  <!------------------ Appointments Dropdown --------------------->
  <li>
    <div class="collapsible-header"><i class="fa fa-calendar"></i> Appointments</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <?php
          $this->patient_session_id = session()->get('patient_session_id');
          if (!isset($this->patient_session_id) || $this->patient_session_id == '') {
            $this->patient_session_id = 0;
          }
          ?>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/doctors_available_slots/' . $this->patient_session_id); ?>"><span class="fa fa-calendar"></span>   Book New Appointment</a>
        </li>
        <li>
          <a class="brdr_dash" href="<?= base_url('Frontdesk/today_appointments'); ?>"><span class="fa fa-calendar"></span> Today Appointment</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/all_appointments'); ?>"><span class="fa fa-calendar"></span> All Appointments</a>
        </li>
      </ul>
    </div>
  </li>
  <!---------------------- View Doctor ----------------------->
  <li>
    <div class="collapsible-header"><i class='fas fa-user-md'></i> View Doctor</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="btn_hver" href="<?= base_url('Frontdesk/view_doctor'); ?>"><span class="fas fa-user-md"></span>   View Doctor</a>
        </li>
      </ul>
    </div>
  </li>
  <!---------------------- Patient Dropdown ------------------->
  <li>
    <div class="collapsible-header"><i class="fa fa-wheelchair"></i>Patients</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/manage_patients'); ?>"><span class="fas fa-procedures"></span> Manage Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/manage_discharge_patients'); ?>"><span class="fas fa-procedures"></span>Manage Discharge Patients</a>
        </li>
      </ul>
    </div>
  </li>
  <!------------------------Products Dropdown ------------------->
  <li>
    <div class="collapsible-header"><i class="fas fa-hand-holding-heart"></i> Donor</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/donor_registration'); ?>"><span class="fa fa-plus"></span>   Donor Registration</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/manage_donor'); ?>"><span class="fas fa-hand-holding-heart"></span> Manage Donor</a>
        </li>
      </ul>
    </div>

  </li>
  <!--------------------------- Blood Bank Dropdown----------------->
  <li>
    <div class="collapsible-header"><i class="fas fa-dna"></i> Blood Bank</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank_donor/blood_bank'); ?>"><span class="fas fa-dna"></span>   Blood Bank</a>
        </li>
      </ul>
    </div>
  </li>
  <li>

    <!--------------------------- Search Donor Dropdown----------------->
    <div class="collapsible-header"><i class="fa fa-search"></i> Search Donor</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank_donor/search_donor'); ?>"><span class="fa fa-search"></span>   Search Donor </a>
        </li>
      </ul>
    </div>
  </li>
  <li>

    <!--------------------------- Logout Dropdown------------------------>
    <div class="collapsible-header"><i class="fa fa-gear"></i> Settings</div>
    <div class="collapsible-body">
      <ul>
      <li>
        <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/view_profile'); ?>"><span class="fas fa-user-edit"></span>   Edit Profile</a>
      </li>
      <li>
        <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/change_frontdesk_password'); ?>"><span class="fas fa-unlock-alt"></span>   Change Password</a>
      </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/Logout_account'); ?>"><span class="fa fa-sign-out"></span>   Logout</a>
        </li>
      </ul>
    </div>
  </li>
</ul>
<!---Side menu Section End --->

<!-- -Appointments Dropdown - -->
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <?php $patient_session = session()->get('patient_session_id');
    if (!isset($patient_session) || $patient_session == '') {
      $patient_session = 0;
    } ?>
    <a class="brdr_dash" href="<?= base_url('Frontdesk/doctors_available_slots/' . $patient_session); ?>"><span class="fa fa-plus dropdown_color col_blu"></span>   Book New Appointment</a>
  </li>
  <li>
    <a class="brdr_dash" href="<?= base_url('Frontdesk/today_appointments'); ?>"><span class="fa fa-calendar dropdown_color col_blu"></span>   Today Appointment</a>
  </li>
  <li>
    <a class="brdr_dash" href="<?= base_url('Frontdesk/all_appointments'); ?>"><span class="fa fa-calendar dropdown_color col_blu"></span>   All Appointments</a>
  </li>
</ul>
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/doctors_available_slots'); ?>"><span class="fa fa-plus col_blu"></span>   Book New Appointments</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/today_appointments'); ?>"><span class="fa fa-trophy col_blu"></span>   Today Appointments</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Frontdesk/all_appointments'); ?>"><span class="fa fa-trophy col_blu"></span>   All Appointments</a>
  </li>
</ul>

<!---Patient Dropdown Section ---->
<ul class="dropdown-content" id="services_dropdown">
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/manage_patients'); ?>"><span class="fa fa-wheelchair col_blu"></span> Manage Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/manage_discharge_patients'); ?>"><span class="fas fa-procedures col_blu"></span> Manage Discharge Patients</a>
  </li>
</ul>
<!---Patient Dropdown Section END ---->

<!---Donors Dropdown Section ---->
<ul class="dropdown-content" id="donor_dropdown">
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/donor_registration'); ?>"><span class="fa fa-plus col_blu"></span> Donor Registration</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/manage_donor'); ?>"><span class="fas fa-hand-holding-heart col_blu"></span> Manage Donor</a>
  </li>
</ul>
<!---Donors Dropdown Section END---->

<!---User Name Dropdown Section ---->
<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name"><span class= "fas fa-user-alt fa_icon col_blu"></span>
  <?php
    if(isset($name[0]->username) && $name[0]->username != '') { 
      echo $name[0]->username;
  }else{
    echo "Frontdesk";
  }
   ?>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span> Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Frontdesk/change_frontdesk_password'); ?>"><span class="fas fa-unlock-alt col_blu"></span> Change Password</a>
  </li>
  <li>
    <a class="brdr_dash col_gry" href="<?= base_url('Frontdesk/Logout_account'); ?>"><span class="fa fa-sign-out col_blu"></span> Logout</a>
  </li>

</ul>
<!---User Name Dropdown Section END ---->

<!---Php Meassge Show --->
<div class="stus_msg">
  <?php if (session()->getTempdata('success')) : ?>
    <div class="card success-message cutom_messge_styl">
      <div class="card-content" id="popup_message">
        <span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
      </div>
    </div>
  <?php endif; ?>
  <?php if (session()->getTempdata('error')) : ?>
    <div class="card error-message cutom_messge_styl">
      <div class="card-content" id="popup_message">
        <span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
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