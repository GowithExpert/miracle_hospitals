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

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/blood2.png') ?>)no-repeat fixed;
			background-size: 100% 100%
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
			color: black
		}

		#input_file {
			border: 1px solid silver;
			color: silver;
			padding: 8px !important;
			margin-top: 8px;
			width: 100%;
			border-radius: 3px;
			font-size: 14px;
			font-weight: 500
		}

		#login_id_with_image {
			background-color: #f9f9f9;
			box-shadow: 3px 2px 8px;
		}

		select {
			display: block;
			border: 1px solid silver;
			color: black;
			border-radius: 3px;
			box-shadow: none;
			height: 40px;
		}

		.password-field {
			padding-right: 30px;
			/* Adjust the value to make room for the eye icon */
		}

		.toggle-password {
			display: flex;
			padding-right: 10px;
			padding-top: 53px;
			right: 5px;
			transform: translateY(-50%);
			cursor: pointer;
			align-items: center;
			position: absolute;
			height: 100%;
			top: 0;
			right: 0;
			cursor: pointer;
		}

		.image {
			width: 100%;
			position: relative;
		}

		.iti {
			width: 100%;
		}

		#input_file {
			margin-bottom: 0px !important;
		}
		h4{
			margin: 0rem 0 0 0 !important;
		}
		h5{
			margin: 0.093333rem 0 1.656rem 0 !important;
		}
	</style>
</head>

<body>
	<!----Body Section Start --->
	<!-- DEBUG-VIEW ENDED 12 APPPATH/Views/Frontdesk/index_login.php -->
	<!---Login Form --->
	<div class="row" style="margin-top: 1%;">
		<div class="col l4 m12 s12"></div>
		<div style="background: transparent" class="col l4 m12 s12">
			<!---card Section --->
			<?= form_open_multipart('Blood_bank_registration/registration'); ?>
			<div class="card brdr_rdius" style="box-shadow: none;">

				<!---Php Meassge Show --->
				<div style="margin-left: 20px;margin-right: 10px">
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card" style="background-color: green">
							<div class="card-content" style="margin-left: 20px;margin-right: 20;padding: 10px; background: green;color: white;font-weight: 500">
								<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card" style="background-color: red">
							<div class="card-content" style="margin-left: 10px;margin-right: 10;padding: 10px; background: red;color: white;font-weight: 500">
								<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<!---Php Meassge Show --->
				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align" style="margin-bottom: 0px;"><span class="fa fa-heartbeat" style="color: #057CF4; font-size: 35px;"></span></h4>
					<h5 class="center-align" style="margin-top: 0px; color: black;font-weight: 500">Front Desk Registration</h5>
					
					<div class="input-container">
						<input type="text" name="username" class="asterisk nameError inpt_area" id="input_box" placeholder="Username" value="<?= set_value('username'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="nameError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'username'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="input-container">
						<input type="email" name="email" id="input_box" class="asterisk emailInput inpt_area" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="emailError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="input-container">
						<input type="tel" name="mobile" id="input_box" maxlength="10" class="phone-input phoneError inpt_area" oninput="validateMobile(this)" placeholder="Mobile number" required="" value="<?= set_value('mobile'); ?>">
					</div>
					<!-- Container for the country code dropdown -->
					<div id="country_selector" style="margin-top: 10px;"></div>
					<span id="phoneError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'mobile'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="input-container">
						<select style="font-size: 16px;" name="gender" class="asterisk genderSelect inpt_area">
							<option selected="" disabled="">Select Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							<option value="Custom">Other</option>
						</select>
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="genderError" style="color:red"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'gender'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<input type="file" name="donor_photo" id="input_file" class="imageError inpt_area" placeholder="Photo">
					<span id="imageError" style="color: red;"></span>
					
					<div class="password-container">
						<div class="image">
							<div class="input-container">
								<input type="password" name="password" class="asterisk passwordInput inpt_area" id="input_box" minlength="6" maxlength="20" placeholder="Password" required="" style="margin-top: 8px;" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
							</div>
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
						</div>
					</div>
					<span id="passwordError" style="color:red"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="input-container">
						<input type="password" name="confirm_password" class="asterisk conf_password inpt_area" id="input_box" minlength="6" maxlength="20" placeholder="Confirm Password" required="" value="<?= set_value('confirm_password'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="confirmPasswordError" style="color:red"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'confirm_password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					<button type="submit" class="btn waves-effect waves-light sigup_btn" id="btn_sign_in">Sign Up <span class="fa fa-sign-in-alt"></span> </button>
					<br>
					
					<a href="<?= base_url('Frontdesk_login/login_account'); ?>" class="btn waves-effect waves-light ihve_alrdy_acc">Already Have An Account?</a>
				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#btn_sign_in").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;


				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter The Username");
					valid = false;
				} else {
					nameError.text("");
				}

				// Email validation
				const emailInput = $(".emailInput");
				const emailError = $("#emailError");

				if (!emailInput.val()) {
					emailError.text("Please enter the email.");
					valid = false;
				} else if (!/^\S+@\S+\.\S+$/.test(emailInput.val())) {
					emailError.text("Please enter a valid email.");
					valid = false;
				} else {
					emailError.text("");
				}

				// Mobile validation
				const phoneInput = $(".phoneError");
				const phoneError = $("#phoneError");
				if (!/^\d{10}$/.test(phoneInput.val())) {
					phoneError.text("Mobile number must be 10 digits numeric");
					valid = false;
				} else {
					phoneError.text("");
				}


				// Gender validation
				const genderSelect = $(".genderSelect");
				const genderError = $("#genderError");
				if (genderSelect.val() === null || genderSelect.val() === "") {
					genderError.text("Please select the gender");
					valid = false;
				} else {
					genderError.text("");
				}


				// image validation
				const imageInput = $(".imageError");
				const imageError = $("#imageError");
				if (nameInput.val().trim() === "") {
					imageError.text("Please Upload The Image");
					valid = false;
				} else {
					imageError.text("");
				}
				// Password validation
				const passwordInput = $(".passwordInput");
				const passwordError = $("#passwordError");
				if (passwordInput.val().trim() === "") {
					passwordError.text("Please enter a password");
					valid = false;
				} else {
					passwordError.text("");
				}

				// Confirm Password validation
				const confirmPasswordInput = $(".conf_password");
				const confirmPasswordError = $("#confirmPasswordError");
				if (confirmPasswordInput.val().trim() === "") {
					confirmPasswordError.text("Please confirm the password");
					valid = false;
				} else if (confirmPasswordInput.val() !== passwordInput.val()) {
					confirmPasswordError.text("Passwords do not match");
					valid = false;
				} else {
					confirmPasswordError.text("");
				}


				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>