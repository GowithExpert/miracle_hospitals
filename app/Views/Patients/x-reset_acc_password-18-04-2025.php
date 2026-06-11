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
	<title>Reset Password</title>
	<?= helper('Form'); ?>
	<?= view('Admin/css_file.php'); ?>
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
			color: white
		}
		.col_blck{
			color: #333 !important;
		}
		.card {
			background: none;
			box-shadow: none !important;
		}

		#login_id_with_image {
			background: #f9f9f9;
			box-shadow: 3px 2px 8px;
		}
		.cutom_messge_styl{
			position: absolute; 
			top: 0; 
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
		.star{
			color: red;
			font-size: 20px;
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
	</style>
</head>

<body>

	<div class="container-fluid">
		<div class="row" id="mrg_top">
			<div class="col l4 m12 s12"></div>
			<div class="col l4 m12 s12">
				<!---Php Meassge Show --->
				<div class="card">
					<div class="crd_positi">
						<?php if (session()->getTempdata('success')) : ?>
							<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message">
								<span class="fa fa-check"></span>  <?= session()->getTempdata('success'); ?>
							</div>
							</div>
						<?php endif; ?>
						<?php if (session()->getTempdata('error')) : ?>
							<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message">
								<span class="fa fa-times"></span>  <?= session()->getTempdata('error'); ?>
							</div>
							</div>
						<?php endif; ?>
					</div>


				<?php if (isset($error)) : ?>
					<div class="card" id="eror_msg">
						<div class="card-content" style="color: white;font-weight: 500;padding: 10px;">
							<span class="fa fa-exclamation-triangle"></span>  <?= $error; ?>
						</div>
					</div>
				<?php else : ?>

					<!---Php Meassge Show --->
					<?= form_open(); ?>
					<div class="card-content brdr_rdius" id="login_id_with_image">
						<h4 class="center-align mrg_botm"><span class="fas fa-unlock-alt fa_icon"></span></h4>
						<h5 class="center-align log_pge_hedng">Reset account password</h5>
						<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
						<div class="card-content">
						
							<div class="password-container">
								<div class="image">
									<div class="input-container">
										<input type="password" name="new_password" id="new_password" class="asterisk passwordInput col_blck input_box" value="<?= set_value('new_password'); ?>" placeholder="Enter New Password">
										<span class="asterisk-symbol">*</span>
									</div>
									<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
								</div>
							</div>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'new_password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
							
							<div class="input-container">
								<input type="password" name="confirm_password" class="asterisk col_blck input_box" id="confirm_password" value="<?= set_value('confirm_password'); ?>" placeholder="Enter Confirm Password">
								<span class="asterisk-symbol">*</span>
							</div>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'confirm_password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
							<center>
								<button type="submit" class="btn btn-waves-effect waves-light login_btn" id="btn_sign_in">Reset Password</button>
							</center>
						</div>
					</div>
					<?= form_close(); ?>
				<?php endif; ?>
			</div>
			<div class="col l4 m12 s12"></div>
		</div>
	</div>

	<?= view('Admin/show_pass_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
</body>

</html>