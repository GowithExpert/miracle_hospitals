<head>
<?= helper('Form'); ?>
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('doctor_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'register_all_users', session()->get('doctor_session_id'));  
    $name = get_pic_by_id('username', 'register_all_users', session()->get('doctor_session_id')); 
  } ?>

  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Doctor/top_bar_css_file.php'); ?>
</head>
<div class="" id="errorMessage"></div>
<div class="navbar-fixed">
  <nav class="">
    <div class="container-fluid pdn">
      <div class="nav-wrapper">
        <a href="<?= base_url('Doctor') . '#!' ?>" class="brand-logo"> <img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="img_div"></a>
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span>  Menu</a>
        <ul class="right hide-on-med-and-down">
          <li>
            <a href="<?= base_url('Doctor') . '#!' ?>" class="dropdown-trigger" data-target="Patient_dropdown"><span class="fa fa-wheelchair fnt_wght"></span> Patients</a>
          </li>
          <li>
            <a href="<?= base_url('Doctor') . '#!' ?>" class="dropdown-trigger" data-target="appointment_dropdown"><span class="fa fa-calendar"></span>   Appointments</a>
          </li>
          <li>
            <a href="<?= base_url('Doctor'); ?>#!" class="dropdown-trigger" data-target="discharge_patient"><span class="fas fa-procedures"></span>   Discharge Patient</a>
          </li>
          <li>
            <a href="<?= base_url('Doctor'); ?>#!" class="dropdown-trigger" data-target="add_blogs"><span class="fa fa-newspaper-o"></span>   News Blog</a>
          </li>
          <li>
            <a href="<?= base_url('Doctor'); ?> #!" class="dropdown-trigger bg_hver" data-target="settings_dropdown">  
            <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
              <img src="<?= base_url('public/uploads/doctor/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
              <?php } else  { ?>
                <img src="<?= base_url('public/assets/images/dr.default_pic.svg'); ?>" class="prfle_pic_div">
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
  <li><a href="<?= base_url('Home/index'); ?>" target="_blank"><i class="fa fa-home"></i>Home</a></li>
  <li><a href="<?= base_url('Doctor'); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
  <li>
    <div class="collapsible-header"><i class="fa fa-wheelchair"></i> Patients</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/today_patients') ?>"><span class="fa fa-wheelchair"></span> Today's Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/all_patients') ?>"><span class="fa fa-wheelchair"></span> All Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/manage_revisit_patient'); ?>"><span class="fa fa-wheelchair"></span> Revisit Patients</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-calendar"></i> Appointment</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/getset_dr_slots_neo/'); ?>"><span class="fa fa-calendar col_blu"></span>   Set Dr. Availability V2</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="#!"><span class="fa fa-calendar col_blu"></span> Today Appointment</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="#!"><span class="fa fa-calendar col_blu"></span> All Appointments</a>
        </li>
      </ul>
    </div>
  </li>


  <li>
    <div class="collapsible-header"><i class='fas fa-procedures'></i> Discharge Patients</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/today_discharge_patient'); ?>"><span class="fas fa-procedures"></span> Today Discharge Patients</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/total_discharge_patient') ?>"><span class="fas fa-procedures"></span> All Discharge Patients</a>
        </li>
      </ul>
    </div>
  </li>

  <li>
    <div class="collapsible-header"><i class="fa fa-newspaper-o"></i> News from Blog</div>
    <div class="collapsible-body">
      <ul>
        <li>Add News Blog
    </div>
  </li>

  <li>
    <div class="collapsible-header"><i class="fa fa-gear"></i> Settings</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <?php if (isset($userdata->username)) { $userdata->username; } else { echo "Patient"; } ?>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/view_profile'); ?>"><span class="fas fa-user-edit"></span> Edit Profile</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/change_doctor_password'); ?>"><span class="fas fa-unlock"></span> Change Passwords</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor_login/Logout_doctor'); ?>"><span class="fa fa-sign-out"></span> Logout</a>
        </li>
      </ul>
    </div>
  </li>


</ul>
<!---Side menu Section End --->

<!---Announcement Dropdown  --->

<!--News from Blog Section ---->
<ul class="dropdown-content" id="add_blogs">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/add_blogs'); ?>"><span class="fa fa-tasks col_blu"></span> Add News Blog</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/manage_blogs'); ?>"><span class="fa fa-tasks col_blu"></span> Manage Blogs</a>
  </li>
</ul>
<!--News from Blog Section ---->


<!---medicines Dropdown --->
<ul class="dropdown-content" id="Patient_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/today_patients'); ?>"><span class="fa fa-wheelchair col_blu fnt_wght"></span> Today's Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/all_patients'); ?>"><span class="fa fa-wheelchair col_blu fnt_wght"></span> All Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/manage_revisit_patient'); ?>"><span class="fa fa-wheelchair col_blu fnt_wght"></span> Revisit Patients</a>
  </li>
</ul>
<!---medicines Dropdown --->

<!---Products Dropdown --->
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/getset_dr_slots_neo/'); ?>"><span class="fa fa-calendar col_blu"></span> Set Dr. Availability V2</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/today_appointments'); ?>"><span class="fa fa-calendar col_blu"></span> Today Appointment</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/all_appointments'); ?>"><span class="fas fa-calendar-times col_blu"></span> All Appointments</a>
  </li>

</ul>
<!---Products Dropdown --->
<!---Sale Dropdown --->
<ul class="dropdown-content" id="discharge_patient">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/today_discharge_patient'); ?>"><span class="fas fa-procedures col_blu"></span> Today Discharge Patients</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/total_discharge_patient') ?>"><span class="fas fa-procedures col_blu"></span> All Discharge Patients</a>
  </li>
</ul>
<!---Sale Dropdown --->



<!---Settings Dropdown Script --->
<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name"><span class="fa fa-user-md fa_icon col_blu"></span>
  <?php
    if(isset($name[0]->username) && $name[0]->username != '') { 
      echo $name[0]->username;
  }else{
    echo "Doctor";
  }
   ?>
  </li>
  
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span> Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor/change_doctor_password'); ?>"><span class="fas fa-unlock-alt col_blu"></span> Change Password</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Doctor_login/Logout_doctor'); ?>"><span class="fa fa-sign-out col_blu"></span> Logout</a>
  </li>

</ul>
<!---Settings Dropdown Script --->

<!---Php Meassge Show --->
<div class="stus_msg">
  <?php if (session()->getTempdata('success')) : ?>
    <div class="card success-message cutom_messge_styl">
      <div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
    </div>
  <?php endif; ?>
  <?php if (session()->getTempdata('error')) : ?>
    <div class="card error-message cutom_messge_styl">
      <div class="card-content" id="popup_message"><?= session()->getTempdata('error'); ?></div>
    </div>
  <?php endif; ?>
</div>
<script>
  $(document).ready(function() {
    // Add JavaScript to hide messages with the specified classes after 5 seconds
    // setTimeout(function() {
    //   var sucesMsgs = document.querySelectorAll('.success-message');
    //   var errMsg = document.querySelectorAll('.error-message');

    //   sucesMsgs.forEach(function(message) {
    //     message.style.display = 'none';
    //   });

    //   errMsg.forEach(function(message) {
    //     message.style.display = 'none';
    //   });
    // }, 50); // 5000 milliseconds = 5 seconds

    function underMaintenanceMsg() {
      console.log("Testing my fuction here");
        var message = document.getElementById('errorMessage');
        message.style.display = 'This section is under maintenance'; 
        console.log("This is not working");
    }
  }); //Document Ready - Jquery - END
</script>
<!---Php Meassge Show --->