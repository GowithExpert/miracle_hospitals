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
<!DOCTYPE html>
<html>

<head>
	<title>Request Activation For Access Control</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}

		.nav-wrapper {
			background: #005197
		}

		h6 {
			font-weight: 500;
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		.heding {
			color: #333;
		}

		.iti {
			width: 100%;
		}
		.cutom_messge_styl{
			position: absolute; 
			top: -53px; 
			left: 0; 
			z-index: 1; 
			width: 100%
		}
		#popup_message {
			padding: 10px;
			background: green;
			color: white;
			font-weight: 500;
		}
		#popup_error_message{
			padding: 10px;
			background: red;
			color: white;
			font-weight: 500;
		}
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	
</head>

<body>
	<!---Navbar section start --->
	<nav>
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">
				<?php if (isset($userdata['username'])) { ?>
					<span class="fa fa-user"></span>   <?= $userdata['username']; ?>
				<?php } ?>
			</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="<?= base_url('Doctor_login/doctor_login'); ?>"><span class="fa fa-key"></span>  Logout</a></li>
				<li><a href="#!"></li>
			</ul>
		</div>
	</nav>
	<!---Navbar section start --->
	<!---Body Section Start --->
	
	<div class="container">
		<div class="card" style="background: green">
			<div class="card-content" style="padding: 10px;">
				<?php if (isset($userdata['username'])) { ?>
					<h6 style="margin-left: 10px;color: white;font-weight: 500"><span class="fa fa-hourglass-start" style="color: white;font-weight: bold;"></span>
						Welcome: <?= $userdata['username']; ?>
					</h6>
				<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-content" style="padding: 10px;">
				<h6><span style="color: red">Notes:</span></h6>
				<p>Your Email Doest Not Register by Admin Please Add Email to Use Your Patient details & All Activity in Hospital. <span class="col_gren">Thanks & Regards Access to hospital</span></p>
			</div>
		</div>
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 10px;">
				<h6 class="h5_align"><span class="fa fa-envelope col_blu"></span>
					Activate to Your Email Address Request
				</h6>
			</div>
			<!---Php Meassge Show --->
			
			<div style="margin-left: 15px;margin-right: 15px; position: relative;">
				<?php if (session()->getTempdata('success')) : ?>
					<div class="card">
						<div class="card-content success-message cutom_messge_styl">
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
			<!---Php Meassge Show --->
			<div class="card-content" style="background: white">
				<?= form_open('Doctor/request_activate_email'); ?>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6 class="heding">Name<span style="color: red;">*</span></h6>
						
						<input type="text" name="doctor name" value="<?= $userdata['username']; ?>" id="input_box" placeholder="Enter Name">
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'doctor_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col l6 m6 s12">
						<h6 class="heding">Email Id<span style="color: red;">*</span></h6>
						<input type="text" name="doctor_email" value="<?= $userdata['email']; ?>" id="input_box" placeholder="Enter Email Id">
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'doctor_email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6 class="heding">Phone Number<span style="color: red;">*</span></h6>
						<div class="input-container">
							<input type="tel" name="doctor_phone" class="phone-input" value="<?= $userdata['mobile']; ?>" maxlength="10" id="input_box" placeholder="Enter Phone Number">
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" style="margin-top: 10px;"></div>

						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'doctor_phone'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col l6 m6 s12">
						<h6 class="heding">Other Info</h6>
						<input type="text" name="other_info" value="<?= $userdata['status'] ?>" id="input_box" placeholder="Other Info">
					</div>
				</div>
				<br>
				<center>
					<button type="submit" class="btn btn-waves-effect waves-light btn_hver" style="background: #005197;;font-weight: 500;text-transform: capitalize;font-weight: 500"> Verification</button>
				</center>
				<?= form_close(); ?>
			</div>
		</div>
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
	<!---Body Section Start --->
	<?= view('Admin/country_code_js_file.php'); ?>


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

</body>

</html>