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
	<title>Create Doctor Account</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-key col_blu"></span> Create Medical Accountant Account</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<!-- <//?= form_open_multipart('Login/create_medical_acc_account'); ?> -->
			<?= form_open_multipart('Login/create_medical_acc_account', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card-content">
			<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user col_blu"></span> Medical Accountant Name</h6>
						<div class="input-container">
							<input type="text" name="med_acc_name" class="asterisk nameError" id="med_acc_name" placeholder="Medical Accountant Name" value="<?= set_value('med_acc_name'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'med_acc_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mobile-phone col_blu "></span> Mobile Number</h6>
						<div class="input-container">
							<input type="tel" name="mobile" class="asterisk phone-input phoneError phone_mandatory" maxlength="10" value="<?= set_value('mobile'); ?>" id="mobile" placeholder="Enter Mobile Number">
							<span class="mandatory_phone redStarphone">*</span>
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
						<input type="hidden" id="country_code" name="country_code" value="">
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'mobile'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mars col_blu"></span> Select Gender</h6>
						<div class="select-wrapper">
							<select name="gender" class="genderSelect">
								<option selected="" disabled="">Select Gender</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
							<span class="mandatory_gender redStargenderSelect">*</span>
							<span id="genderError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'gender'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Medical Accountant Email</h6>
						<div class="input-container">
							<input type="text" name="med_acc_email" class="asterisk emailInput" id="med_acc_email" placeholder="Medical Accountant Email" value="<?= set_value('med_acc_email'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="emailError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'med_acc_email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-lock col_blu"></span> Password</h6>
						<div class="input-container password-container">
							<div class="image">
								<input type="password" name="password" class="asterisk passwordInput" id="password" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
								<span id="passwordError" class="col_red valid_err"></span>
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
							</div>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-lock col_blu"></span> Confirm Password</h6>
						<div class="input-container">
							<input type="password" name="conf_password" class="asterisk conf_password" id="input_box" placeholder="Confirm Password" value="<?= set_value('conf_password'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="confirmPasswordError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'conf_password'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>

				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-key"></span> Create Account</button>
					</div>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
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
			$('.phone_mandatory').on('input', function() {
      			$('.redStarphone').hide();
    		});
			$('.genderSelect').change(function() {
      			$('.redStargenderSelect').hide();
    		});
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;


				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter The Medical Accountant Name");
					valid = false;
				} else {
					nameError.text("");
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
			setTimeout(function() {
				var sucesMsgs = document.querySelectorAll('#suces_msg');
				var errMsg = document.querySelectorAll('#eror_msg');

				sucesMsgs.forEach(function(message) {
				message.style.display = 'none';
				});

				errMsg.forEach(function(message) {
				message.style.display = 'none';
				});
			}, 5000); // 5000 milliseconds = 5 seconds
		});
	</script>

	<!---Body Section End --->
	<?= view('Admin/country_code_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/show_pass_js_file.php'); ?>

</body>

</html>
