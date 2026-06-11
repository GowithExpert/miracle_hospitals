<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
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
Date: 21st August, 2023 
-->

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<title>Medical Accountant </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />


	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script> -->
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body class="admin_login_back">
	<!----Body Section Start --->
	<!---Login Form --->
	<div class="row" id="mrg_top1">
		<div class="col l4 m12 s12"></div>
		<div class="col l4 m12 s12">
			<!---card Section --->
			<!-- <//?= form_open('Accountant_login/create_acc_account'); ?> -->
			<?= form_open('Accountant_login/create_acc_account', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card">
				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align h4_margn"><span class="fa fa-address-book fa_icon"></span></h4>
					<h5 class="center-align h5_margn font_weght">Accountant Login</h5>

					<div class="input-container">
						<input type="text" name="username" class="asterisk nameError inpt_area input_box" id="username" placeholder="Username" value="<?= set_value('username'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="nameError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'username'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="email" name="email" id="email" class=" asterisk emailInput inpt_area input_box" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="emailError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="tel" name="mobile" id="mobile" maxlength="10" class="phone-input phoneError inpt_area phone_mandatory" oninput="validateMobile(this)" placeholder="Mobile number" value="<?= set_value('mobile'); ?>">
					</div>
					<!-- Container for the country code dropdown -->
					<div id="country_selector" name="country_selector" class="margn_top"></div>

					<span id="phoneError" class="col_red"></span>
					<!-- Hidden input to store the selected country code -->
					<input type="hidden" id="country_code" name="country_code" value="">

					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'mobile'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<select name="gender" class="genderSelect inpt_area selct_gendr">
						<option selected="" disabled="">Select Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
						<option value="Custom">Other</option>
					</select>
					<span id="genderError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'gender'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="password-container">
						<div class="image">
							<div class="input-container">
								<input type="password" name="password" class="asterisk passwordInput inpt_area input_box" minlength="6" maxlength="20" id="password" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
							</div>
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
						</div>
					</div>
					<span id="passwordError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="password" name="confirm_password" class="asterisk conf_password inpt_area input_box" id="confirm_password" minlength="6" maxlength="20" placeholder="Confirm Password" value="<?= set_value('confirm_password'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="confirmPasswordError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'confirm_password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<button type="submit" class="btn waves-effect waves-light sigup_btn" id="btn_sign_in">Sign Up <span class="fa fa-sign-in-alt"></span> </button>
					<br>
					<a href="<?= base_url('Accountant_login/accountant_login'); ?>" class="btn waves-effect waves-light ihve_alrdy_acc">Already Have An Account?</a>


				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>

	</div>
<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#signup-form").on("submit", function(event) {
				event.preventDefault();
				window.location.href = "<?= base_url('Accountant_login/index') ?>";
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
