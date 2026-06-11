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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<title> Doctor </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />


	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/hos2.jpg') ?>)no-repeat fixed;
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

		#login_id_with_image {
			background-color: #f9f9f9;
			box-shadow: 3px 2px 8px;
		}

		select {
			display: block;
			border: 1px solid silver;
			border-radius: 3px;
			color: black;
			font-size: 16px;
			box-shadow: none;
			height: 40px;
			margin-bottom: 9px;
		}

		.password-field {
			padding-right: 30px;
			/* Adjust the value to make room for the eye icon */
		}

		.toggle-password {
			display: flex;
			padding-right: 10px;
			padding-top: 38px;
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

		.card {
			background: none !important;
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
	<!---Login Form --->
	<div class="row" style="margin-top: 5%;">
		<div class="col l4 m12 s12"></div>
		<div class="col l4 m12 s12">
			<!---card Section --->
			<?= form_open('Doctor_login/create_doc_account'); ?>
			<div class="card" style="box-shadow: none;">

				<!---Php Meassge Show --->
				<div>
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card" style="background-color: green">
							<div class="card-content" style="padding: 10px; background: green;color: white;font-weight: 500">
								<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card" style="background-color: red">
							<div class="card-content" style="padding: 10px; background: red;color: white;font-weight: 500">
								<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<!---Php Meassge Show --->
				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align" style="margin-bottom: 0px;"><span class="fa fa-address-book" style="color: #057CF4; font-size: 35px;"></span></h4>
					<h5 class="center-align" style="margin-top: 0px; color: black;font-weight: 500">Doctor Login</h5>
					
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
						<input type="email" name="email" id="input_box" class=" asterisk emailInput inpt_area" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="emailError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					
					<div class="input-container">
						<input type="tel" name="mobile" id="input_box" maxlength="10" class="phone-input phoneError inpt_area" oninput="validateMobile(this)" placeholder="Mobile number" value="<?= set_value('mobile'); ?>">

					</div>
					<!-- Container for the country code dropdown -->
					<div id="country_selector" style="margin-top: 10px;"></div>
					<span id="phoneError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'mobile'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					
					<select name="gender" class="genderSelect inpt_area">
						<option selected="" disabled="">Select Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
						<option value="Custom">Other</option>
					</select>
					<span id="genderError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'gender'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					
					<div class="password-container">
						<div class="image">
							<div class="input-container">
								<input type="password" name="password" class="asterisk passwordInput inpt_area" minlength="6" maxlength="20" id="input_box" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
							</div>
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
						</div>
					</div>
					<span id="passwordError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					
					<div class="input-container">
						<input type="password" name="confirm_password" class="asterisk conf_password inpt_area" id="input_box" minlength="6" maxlength="20" placeholder="Confirm Password" value="<?= set_value('confirm_password'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="confirmPasswordError" style="color: red;"></span>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'confirm_password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<button type="submit" class="btn waves-effect waves-light sigup_btn" id="btn_sign_in">Sign Up <span class="fa fa-sign-in-alt"></span> </button>
					<br>
					<a href="<?= base_url('Doctor_login/doctor_login'); ?>" class="btn waves-effect waves-light ihve_alrdy_acc">Already Have An Account?</a>


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
			$("#signup-form").on("submit", function(event) {
				event.preventDefault();
				window.location.href = "<?= base_url('Doctor_login/index') ?>";
			});
			$("#btn_sign_in").click(function(e) {
				//e.preventDefault();
				$("#nameError").text("");
				$("#emailError").text("");
				$("#phoneError").text("");
				$("#genderError").text("");
				$("#passwordError").text("");
				$("#confirmPasswordError").text("");

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
					} else if (passwordInput.val().length < 6) {
						passwordError.text("Password must be at least 6 characters");
						valid = false;
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
  			        }


				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>

	<!---Login Form --->
	<!----Body Section Start --->

	<?= view('Admin/show_pass_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>
