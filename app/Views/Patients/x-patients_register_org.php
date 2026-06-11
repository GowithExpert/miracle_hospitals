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
	<title>Patients Registration </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	
	<?= helper('Form'); ?>
	<?= view('Admin/css_file.php'); ?>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/patients.jpg') ?>)no-repeat fixed;
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

		select {
			background-color: transparent !important;
			width: 100% !important;
			padding: 5px !important;
			border: 1px solid silver !important;
			border-radius: 2px !important;
			height: 40px !important;
			margin-bottom: 9px !important;
		}

		.card {
			background: none;
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
			<?= form_open('Patients_login/create_patients_account'); ?>
			<div class="card" style="box-shadow: none;">

				<!---Php Meassge Show --->
				<div>
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
				<div class="card-content" id="login_id_with_image">
					<h4 class="center-align" style="margin-bottom: 0px;"><span class="fa fa-wheelchair" style="color: #057CF4; font-size: 35px;"></span></h4>
					<h5 class="center-align" style="margin-top: 0px; color: black;font-weight: 500">Patients Registration</h5>
					<input type="text" name="username" id="input_box" placeholder="Username" value="<?= set_value('username'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'username'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<input type="email" name="email" id="input_box" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="input-container">
						<input type="tel" name="mobile" id="input_box" maxlength="10" class="phone-input" oninput="validateMobile(this)" placeholder="Mobile number" value="<?= set_value('mobile'); ?>">
					</div>
					<!-- Container for the country code dropdown -->
					<div id="country_selector" style="margin-top: 10px;"></div>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'mobile'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
				
					<select style="display: block;" name="gender">
						<option selected="" disabled="">Select Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
						<option value="Other">Other</option>
					</select>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'gender'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					
					<div class="password-container">
						<div class="image">
							<input type="password" name="password" class="passwordInput" id="input_box" minlength="6" maxlength="20" placeholder="Password" value="<?= set_value('password'); ?>">
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
						</div>
					</div>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					<!--<h6 style="font-size: 14px; font-weight: 500;color: orange;"></h6>-->
					<input type="password" name="confirm_password" id="input_box" minlength="6" maxlength="20" placeholder="Confirm Password" value="<?= set_value('confirm_password'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'confirm_password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					<button type="submit" class="btn waves-effect waves-light" id="btn_sign_in" style="background: #057CF4;text-transform: capitalize;width: 100%;font-weight: 500;margin-top: 10px;height: 40px;border-radius: 3px;">Sign Up <span class="fa fa-sign-in-alt"></span> </button>
					
					</button>

					<br>
					<a href="<?= base_url('Patients_login/login'); ?>" class="btn waves-effect waves-light" style="background: #005A87;text-transform: capitalize;width: 100%;font-weight: 500;margin-top: 10px;height: 40px;border-radius: 3px;">Already Have An Account?</a>
				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>
	<!---Login Form --->
	<!----Body Section Start --->
	<script>
		document.getElementById("signup-form").addEventListener("submit", function(event) {
			event.preventDefault();
			window.location.href = "<?= base_url('Patients_login/register') ?>";
		});
	</script>

	<?= view('Admin/show_pass_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>