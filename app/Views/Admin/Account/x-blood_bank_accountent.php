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
	<title>Create Blood Bank Account</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script> -->
	<!---CSS File Include  -->
	<style>
		.btn_hver:hover {
			color: #fff;
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="container">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 600;"><span class="fa fa-key" style="color: #005197"></span> Create Blood Bank Accountant </h5>
			</div>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<?= form_open_multipart('Login/create_blood_acc'); ?>
						<h6>Accountant Name</h6>
						<div class="input-container">
							<input type="text" name="accountant_name" class="asterisk" id="input_box" placeholder="Blood Bank Accountant Name" value="<?= set_value('accountant_name'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'accountant_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Mobile Number</h6>
						<div class="input-container">
							<input type="tel" name="mobile" class="asterisk phone-input" maxlength="10" <?= set_value('mobile'); ?>" id="input_box" placeholder="Enter Mobile Number">

						</div>

						<!-- Container for the country code dropdown -->
						<div id="country_selector" style="margin-top: 10px;"></div>


						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'mobile'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Select Gender</h6>
						<select name="gender">
							<option selected="" disabled="">Select Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							<option value="other">Other</option>
						</select>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'gender'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6> Accountant Email</h6>
						<div class="input-container">
							<input type="text" name="email" class="asterisk" id="input_box" placeholder=" Accountant Email" value="<?= set_value('email'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Password</h6>
						<div class="input-container password-container">
							<div class="image">
								<input type="password" name="password" class="asterisk passwordInput" id="input_box" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
							</div>
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Confirm Password</h6>
						<div class="input-container">
							<input type="password" name="confirm_password" class="asterisk" id="input_box" placeholder="Confirm Password" value="<?= set_value('confirm_password'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'confirm_password'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<br>

				<center>
					<button type="submit" class="btn btn-waves-effect waves-light btn_hver" style="background: #005197;text-transform: capitalize;font-weight: 500"><span class="fa fa-key"></span> Create Account</button>
				</center>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	</div>
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