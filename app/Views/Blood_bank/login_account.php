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
	<title>Front Desk Support</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= View('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/blood5.jpg') ?>)no-repeat fixed;
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
			background: # f9f9f9;
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
	</style>
</head>

<body>
	<!----Body Section Start --->
	<!---Login Form --->
	<div class="row" style="margin-top: 5%;">
		<div class="col l4 m12 s12"></div>
		<div class="col l4 m12 s12">

			<!---card Section --->
			<?= form_open('Blood_bank_registration/login_account'); ?>
			<div class="card">
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

				<div class="card-content" id="login_id_with_image">
					<h4 class="center-align" style="margin-bottom: 0px;"><span class="fa fa-users" style="color: #057CF4"></span></h4>
					<h5 class="center-align" style="margin-top: 0px; color: black;font-weight: 500;margin-bottom: 39px;">Front Desk Login</h5>


					<?php if (isset($error)) : ?>
						<div style="color: red"><?= $error; ?></div>
					<?php endif; ?>


				
					<input type="text" name="email" id="input_box" placeholder="Email address" value="<?= set_value('email'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'email'); ?></span>
					<?php } ?>
					
					<div class="password-container">
						<div class="image">
							<input type="password" name="password" class="passwordInput" id="input_box" placeholder="Password" value="<?= set_value('password'); ?>">
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
						</div>
					</div>
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'password'); ?></span>
					<?php } ?>
					<button type="submit" class="btn waves-effect waves-light" id="btn_sign_in" style="background: #057CF4;text-transform: capitalize;width: 100%;font-weight: 500;margin-top: 10px;height: 40px;border-radius: 3px;">Sign In <span class="fa fa-sign-in-alt"></span> </button>
					<br><br>
					<!-----Gooogle Login Image Section --->

					<?php if (isset($loginButton)) : ?>
						<div>
							<a href="<?= $loginButton; ?>">
								<img src="<?= base_url('public/assets/images/logingoogle.png') ?>" style="width: 100%;height: 50px;">
							</a>
						</div>
					<?php endif; ?>
					<!-----Gooogle Login Image Section --->

					<a href="<?= base_url('Blood_bank_registration/forget_password'); ?>" style="color: #005A87;float: right;font-size: 11px;margin-top: -10px;font-weight: 500">Forget Password ?</a>
					<p style="font-size: 11px; margin-top: -10px">New User?<a href="<?= base_url('Blood_bank_registration/index'); ?>" style="font-size: 11px;color: #005A87;font-weight: 500">Create Account</a></p>

					<br>

					<h6 style="margin-top: 29px;font-size: 14px; font-weight: 500;color: grey;">Notes:</h6>
					<p style="font-size: 14px; font-weight: 500;color: grey;">Hospital Management Software Login Panel </p>
					<p style="font-size: 14px; font-weight: 500;color: grey;">Front Desk Is The First Contact Person For Patients Help</p>



				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>
	<!---Login Form --->
	<!----Body Section Start --->

	<?= view('Admin/show_pass_js_file.php'); ?>

	<?= view('Admin/js_file.php'); ?>
</body>

</html>