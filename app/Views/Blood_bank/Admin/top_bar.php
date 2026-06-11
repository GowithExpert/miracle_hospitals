<head>
<?= helper('Form'); ?>
<?php
  $pic[0] = new \stdClass; //stdClass object
  $pic[0]->profile_pic = ''; //Just for addressing notices
  if(session()->has('bldbnk_session_id')) {
    $pic = get_pic_by_id('profile_pic', 'register_all_users', session()->get('bldbnk_session_id'));
  } ?>

  <?php
  $name[0] = new \stdClass; //stdClass object
  $name[0]->username = ''; //Just for addressing notices
  if(session()->has('bldbnk_session_id')) {
    $name = get_pic_by_id('username', 'register_all_users', session()->get('bldbnk_session_id'));
  } ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <?= view('Blood_bank/Admin/top_bar_css_file.php'); ?>
</head>

<div class="navbar-fixed">
  <nav class="">
    <div class="container-fluid">
      <div class="nav-wrapper">
        <a href="<?= base_url('Blood_bank/index'); ?>" class="brand-logo">  
          <img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" class="brand-logo1">
        </a>
        <a href="#" data-target="side_menu" class="sidenav-trigger"><span class="fa fa-bars"></span>  Menu</a>
        <ul class="right hide-on-med-and-down">
          <li>
            <a href="#!" class="dropdown-trigger" data-target="blood_bank_dropdown"><span class="fas fa-dna"></span>   Blood Bank</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="transition_dropdown"><span class="fas fa-dna"></span>   Blood Transition</a>
          </li>
          <li>
            <a href="<?= base_url('Blood_bank/donor_details'); ?>" class="dropdown-trigger" data-target="donor_dropdown"><span class="fas fa-hand-holding-heart"></span>   Donor Details</a>
          </li>
          <li>
            <a href="#!" class="dropdown-trigger" data-target="enquiry_dropdown"><span class="fa fa-question-circle"></span>   View Enquiry</a>
          </li>
          <li>
            <a href="<?= base_url('Blood_bank/index'); ?>" class="dropdown-trigger bg_hver" data-target="settings_dropdown">
            <!-- <//img src="<//?= base_url('public/assets/images/dr.default_pic.svg'); ?>" class="prfle_pic_div"> -->
            <?php
              if(isset($pic[0]->profile_pic) && $pic[0]->profile_pic != '') { ?>
              <img src="<?= base_url('public/uploads/accounts/bloodbank/' . $pic[0]->profile_pic); ?>" class="prfle_pic_div">
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
  <li><a href="<?= base_url('Home/index'); ?>" target="_blank"><i class="fa fa-home"></i> Home</a></li>
  <li>
    <a class="text_colour" href="<?= base_url('Blood_bank/index'); ?>"><span class="fa fa-tachometer"></span>  Dashboard</a>
  </li>
  <li>
    <div class="collapsible-header"><i class="fas fa-dna"></i>Blood Bank</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/add_blood'); ?>"><span class="fa fa-plus"></span> Add Blood</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_blood'); ?>"><span class="fas fa-burn"></span> Manage Blood</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/total_blood_stock'); ?>"><span class="fas fa-burn"></span> Total Blood Bank Stock</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fas fa-dna"></i>Blood Transition</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/blood_transition'); ?>"><span class="fa fa-exchange"></span>   Blood Transition </a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_blood_transition'); ?>"><span class="fas fa-burn"></span>   Manage Transition </a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fas fa-hand-holding-heart"></i> Donor Details</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/donor_details'); ?>"><span class="fa fa-info-circle"></span> Donor Details </a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/buy_donor_blood'); ?>"><span class="fas fa-rupee"></span> Buy Donor Blood </a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_donor_blood'); ?>"><span class="fas fa-burn"></span> Manage Donor Blood Transition </a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/donor_blood_trans'); ?>"><span class="fa fa-exchange"></span> Donor Blood Transition</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_donor_blood_trans'); ?>"><span class="fa fa-exchange"></span> Manage Donor Blood Transition</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-question-circle"></i> View Enquiry</div>
    <div class="collapsible-body">
      <ul>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/view_enquiry'); ?>"><span class="fa fa-question-circle"></span> View Enquiry </a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/view_enquirygoogleusers'); ?>"><span class="fas fa-user-alt"></span> View Enquiry Google User</a>
        </li>
      </ul>
    </div>
  </li>
  <li>
    <div class="collapsible-header"><i class="fa fa-gear"></i>Settings</div>
    <div class="collapsible-body">
      <ul>
      <li>
        <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/view_profile'); ?>"><span class="fas fa-user-edit"></span> Edit Profile</a>
      </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/change_Blood_bank_password'); ?>"><span class="fas fa-unlock-alt"></span> Change Password</a>
        </li>
        <li>
          <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/Logout_blood_bank'); ?>"><span class="fa fa-sign-out"></span> Logout</a>
        </li>
      </ul>
    </div>
  </li>
</ul>
<!---Side menu Section End --->

<!---Blood Bank Dropdown   ----->
<ul class="dropdown-content" id="blood_bank_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/add_blood'); ?>"><span class="fas fa-plus col_blu"></span> Add Blood</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_blood'); ?>"><span class="fas fa-burn col_blu"></span> Manage Blood</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/total_blood_stock'); ?>"><span class="fa fa-trophy col_blu"></span> Total Blood Bank Stock</a>
  </li>
</ul>
<!---Blood Bank Dropdown END----->

<!----Transition Dropdown ----->
<ul class="dropdown-content" id="transition_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/blood_transition'); ?>"><span class="fa fa-exchange col_blu"></span> Blood Transition</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_blood_transition'); ?>"><span class="fas fa-burn col_blu"></span> Manage Transition</a>
  </li>
</ul>
<!----Transition Dropdown END----->

<!-----Enquiry Dropdown ----->
<ul class="dropdown-content" id="enquiry_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/view_enquiry'); ?>"><span class="fa fa-question-circle col_blu"></span> View Enquiry</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/view_enquirygoogleusers'); ?>"><span class="fas fa-user-alt col_blu"></span> View Enquiry Google User</a>
  </li>
</ul>
<!-----Enquiry Dropdown END----->

<!-----Donors Dropdown ---->
<ul class="dropdown-content" id="donor_dropdown">
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/donor_details'); ?>"><span class="fa fa-info-circle col_blu"></span> Donor Details</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/buy_donor_blood'); ?>"><span class="fas fa-rupee col_blu"></span> Buy Donor Blood</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_donor_blood'); ?>"><span class="fas fa-burn col_blu"></span> Manage Donor Blood</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/donor_blood_trans'); ?>"><span class="fa fa-exchange col_blu"></span> Donor Blood Transition</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash" href="<?= base_url('Blood_bank/manage_donor_blood_trans'); ?>"><span class="fa fa-exchange col_blu"></span> Manage Donor Blood Transition</a>
  </li>
<!-----Donors Dropdown END---->

<!-----User Name Dropdown ---->
</ul>
<ul class="dropdown-content" id="settings_dropdown">
  <li class="user_name col_gry"><span class="fas fa-users fa_icon col_blu"></span>
  <?php
    if(isset($name[0]->username) && $name[0]->username != '') { 
      echo $name[0]->username;
  }else{
    echo "Blood Bank";
  }
   ?>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Blood_bank/view_profile'); ?>"><span class="fas fa-user-edit col_blu"></span> Edit Profile</a>
  </li>
  <li>
    <a class="fcus_hver brdr_dash col_gry" href="<?= base_url('Blood_bank/change_Blood_bank_password'); ?>"><span class="fas fa-unlock-alt col_blu"></span> Change Password</a>
  </li>
  <li>
    <a class="brdr_dash col_gry" href="<?= base_url('Blood_bank/Logout_blood_bank'); ?>"><span class="fa fa-sign-out col_blu"></span> Logout</a>
  </li>

</ul>
<!-----User Name Dropdown END---->

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


<!---Php Meassge Show END--->