<head>
<?= helper('Form'); ?>
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('accountant_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'register_all_users', session()->get('accountant_session_id'));
  } ?>

<?php
  $name[0] = new \stdClass; //stdClass object
  $name[0]->username = ''; //Just for addressing notices
  if(session()->has('accountant_session_id')) {
    $name = get_pic_by_id('username', 'register_all_users', session()->get('accountant_session_id'));
  } ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Medical/top_bar_css_file.php'); ?>
</head>
<div class="navbar-fixed">
  <nav class="">
    <div class="container-fluid">
      <div class="nav-wrapper">
        
        <a href="<?= base_url('Medical_Accountant/index'); ?>" class="brand-logo">  
          <img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="brand-logo1">
        </a>
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span>  Menu</a>
        <ul class="right hide-on-med-and-down">
         
          <li>
            <a href="#!" class="dropdown-trigger" data-target="patient_dropdown"><span class="fa fa-wheelchair"></span>   Patients</a>
          </li>

          <li>
            <a href="#!" class="dropdown-trigger" data-target="appointment_dropdown"><span class="fa fa-wheelchair"></span>   Appointment </a>
          </li>

          <li>
            <a href="#!" class="dropdown-trigger" data-target="sale_dropdown"><span class="fas fa-poll"></span>   Sale</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="medicines_dropdoen"><span class="fa fa-stethoscope"></span>   Medical</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="expired_dropdown"><span class="fa fa-product-hunt"></span>   Expired Products </a>
          </li>
          <li>
            <a href="<?= base_url('Medical_Accountant/index'); ?> #!" class="dropdown-trigger bg_hver" data-target="settings_dropdown">  
              <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
              <img src="<?= base_url('public/uploads/accounts/accountants/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
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
  <li><a href="<?= base_url('Home/index'); ?>" target="_blank"><i class="fa fa-home"></i>Home</a></li>
  <li>
    <a class="text_colour" href="<?= base_url('Medical_Accountant#!'); ?>"><span class="fa fa-tachometer"></span>  Dashboard</a>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-wheelchair"></i>Patients</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/all_patients'); ?>"><span class="fas fa-bed"></span> All patients</a>
        </li>
      </ul>

      <li>
    <div class="collapsible-header"><i class="fa fa-wheelchair"></i>Appointment</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/all_appointment'); ?>"><span class="fas fa-bed"></span> All Appointments</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/today_appointments'); ?>"><span class="fas fa-bed"></span> Today Appointments</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fas fa-poll"></i>Sale</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_customer_name'); ?>"><span class="fa fa-plus"></span> Add Customer Name</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/product_sale'); ?>"><span class="fa fa-rupee"></span>   Sales</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/todays_sale_records'); ?>"><span class="fas fa-poll"></span>   Today Sales Report</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/all_sale_reports'); ?>"><span class="fas fa-poll"></span>   Manage Sales Report</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-stethoscope"></i> Medical</div>
    <div class="collapsible-body">
      <ul>
  </li>
  <li>
    <a class="brdr_dash" href="<?= base_url('Medical_Accountant/add_company'); ?>"><span class="fa fa-plus"></span>   Add Medicine Company</a>
  </li>
  <li>
    <a class="brdr_dash" href="<?= base_url('Medical_Accountant/manage_company'); ?>"><span class="fas fa-hospital-alt"></span>   Manage Medicine Comapny</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_medicine'); ?>"><span class="fas fa-first-aid"></span>   Add Medicines</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/manage_medicine'); ?>"><span class="fas fa-pills"></span>   Manage Medicines</a>
  </li>
</ul>
</div>
</li>


<li>
  <div class="collapsible-header"><i class="fa fa-product-hunt"></i> Expired Products</div>
  <div class="collapsible-body">
    <ul>
      <li>
        <a href="<?= base_url('Medical_Accountant/expiry_products'); ?>"><span class="fa fa-eye"></span>   View Expired Products</a>
      </li>

    </ul>
  </div>
</li>

<li>
  <div class="collapsible-header"><i class="fa fa-gear"></i> Settings</div>
  <div class="collapsible-body">
    <ul>
      <li>
        <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/view_profile'); ?>"><span class="fas fa-user-edit"></span> Edit Profile</a>
      </li>
      <li>
        <a class="brdr_dash" href="<?= base_url('Medical_Accountant/change_password'); ?>"><span class="fas fa-unlock"></span> Change Password</a>
      </li>
      <li>
        <a class="brdr_dash" href="<?= base_url('Accountant_login/Logout_accountant'); ?>"><span class="fa fa-sign-out"></span>   Logout</a>
      </li>
    </ul>
  </div>
</li>


</ul>
<!---Side menu Section End --->

<!---Announcement Dropdown  --->


<!---medicines Dropdown --->
<ul class="dropdown-content" id="medicines_dropdoen">

  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_company'); ?>"><span class="fa fa-plus col_blu"></span> Add Medicine Company</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/manage_company'); ?>"><span class="fas fa-hospital-alt col_blu"></span> Manage Medicine Company</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_medicine'); ?>"><span class="fas fa-first-aid col_blu"></span>   Add Medicines</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/manage_medicine'); ?>"><span class="fas fa-pills col_blu"></span>   Manage Medicines</a>
  </li>
</ul>
<!---medicines Dropdown --->

<!---Products Dropdown --->
<ul class="dropdown-content" id="products_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_medicine'); ?>"><span class="fa fa-trophy col_blu"></span>Add Medicines</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/manage_medicine'); ?>"><span class="fa fa-file col_blu"></span> Manage Medicines</a>
  </li>

</ul>
<!---Products Dropdown --->

<!---Patients Dropdown --->
<ul class="dropdown-content" id="patient_dropdown">
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Medical_Accountant/all_patients'); ?>"><span class="fas fa-bed col_blu"></span> All patients</a>
  </li>
  
</ul>
<!---Patients Dropdown --->

<!---Appointments Dropdown --->
<ul class="dropdown-content" id="appointment_dropdown">
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Medical_Accountant/all_appointments'); ?>"><span class="fas fa-bed col_blu"></span> All Appointments</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Medical_Accountant/today_appointments'); ?>"><span class="fas fa-bed col_blu"></span> Today's Appointments</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Medical_Accountant/canceled_appointments'); ?>"><span class="fas fa-bed col_blu"></span> Cancelled Appointments</a>
  </li>
</ul>
<!---Appointments Dropdown --->

<!---Sale Dropdown --->
<ul class="dropdown-content" id="sale_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/add_customer_name'); ?>"><span class="fa fa-plus col_blu"></span> Add Customer Name</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/product_sale'); ?>"><span class="	fa fa-rupee col_blu"></span> Sale</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/todays_sale_records'); ?>"><span class="fas fa-poll col_blu"></span> Today Sale Report</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/all_sale_reports'); ?>"><span class="fas fa-poll col_blu"></span>   All Sale Report</a>
  </li>

</ul>
<!---Sale Dropdown --->

<!---Expired Products Dropdown ---->
<ul class="dropdown-content" id="expired_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/expiry_products'); ?>"><span class="fa fa-eye col_blu"></span> View Expired Products</a>
  </li>
</ul>
<!---Expired Products Dropdown ---->

<!---Settings Dropdown Script --->
<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name col_gry"><span class="fas fa-user-alt fa_icon col_blu"></span>
  <?php
      if(isset($name[0]->username) && $name[0]->username != '') { 
        echo $name[0]->username;
      } else{
        echo "Accountant";
      }
      ?>
    <!-- <//?php if (isset($userdata->username)) {
      echo $userdata->username;
    } else {
      echo "Accountant";
    }
    ?> -->
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span>   Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Medical_Accountant/change_password'); ?>"><span class="fas fa-unlock col_blu"></span> Change Password</a>
  </li>
  <li>
    <a class="fcus_hver" href="<?= base_url('Accountant_login/Logout_accountant'); ?>"><span class="fa fa-sign-out col_blu"></span>   Logout</a>
  </li>

</ul>
<!---Settings Dropdown Script --->

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