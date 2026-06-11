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
	<title>Blood Donor Login</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
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
			background: #f9f9f9;
			box-shadow: 3px 2px 8px;
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
			<?php // form_open('Accountant_login/accountant_login'); 
			?>
			<?php // echo form_open('Blood_bank/blood_bank_login/'); 
			?>
			<?= form_open('Blood_bank_registration/login_account'); ?>
			<div class="card">
				<!---Php Meassge Show --->
				<div>
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card" style="background-color: black">
							<div class="card-content" style="margin-left: 20px;margin-right: 20;padding: 10px; background: green;color: white;font-weight: 500">
								<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card" style="background-color: black">
							<div class="card-content" style="margin-left: 10px;margin-right: 10;padding: 10px; background: red;color: white;font-weight: 500">
								<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>



				<!---Php Meassge Show --->

				<div class="card-content" id="login_id_with_image">
					<h4 class="center-align" style="margin-bottom: 0px;"><span class="fa fa-user-md" style="color: #057CF4"></span></h4>
					<h5 class="center-align" style="margin-top: 0px; color: black;font-weight: 500; margin-bottom: 49px">Blood Donor Login</h5>


					<?php if (isset($error)) : ?>
						<div style="color: red"><?= $error; ?></div>
					<?php endif; ?>


					<!--<h6 style="font-size: 14px; font-weight: 500;color: orange;"></h6>-->
					<input type="text" name="email" id="input_box" placeholder="Email address" value="<?= set_value('email'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<!--<h6 style="font-size: 14px; font-weight: 500;color: orange;"></h6>-->
					<input type="password" name="password" id="input_box" placeholder="Password" value="<?= set_value('password'); ?>">
					<?php if (isset($validation)) { ?>
						<span style="color: red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<button type="submit" class="btn waves-effect waves-light" id="btn_sign_in" style="background: #057CF4;text-transform: capitalize;width: 100%;font-weight: 500;height: 40px;border-radius: 3px;margin-top: 10px">Sign In <span class="fa fa-sign-in-alt"></span> </button>
					<a href="<?= base_url('Accountant_login/forget_password'); ?>" style="color: #005A87;float: right;font-size: 11px;margin-top: 3px; font-weight: 500">Forget Password ?</a>

					<p style="font-size: 11px;margin-top: 3px">New User? <a href="<?= base_url('Blood_bank_donor/donor_registration'); ?>" style="font-size: 11px;color: #005A87;font-weight: 500">Create Account</a></p>

					<br>
					<h6 style="margin-top: 40px;font-size: 14px; font-weight: 500;color: grey;">Notes:</h6>
					<p style="font-size: 14px; font-weight: 500;color: grey;">Hospital Management Software Login Panel </p>
					<p style="font-size: 14px; font-weight: 500;color: grey;">Here ! Medical Accountant Login ! <br> Doctor Login & Blood Bank Accountant Login </p>


				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>
	<!---Login Form --->
	<!----Body Section Start --->



	<?= view('Admin/js_file.php'); ?>
</body>

</html>