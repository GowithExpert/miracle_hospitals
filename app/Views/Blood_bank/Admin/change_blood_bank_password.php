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
	<title>Change Password</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Blood_bank/Admin/color_interchange_css_file.php'); ?>
	<!---CSS File Include  -->
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="container-fluid">
		<div class="card">
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
			<div class="card-content" id="brdr_botm_silvr">
				<h6 class="h5_align"><span class="fa fa-key col_ornge"></span>  Change Password</h6>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				
				<div class="row">
					<?= form_open(); ?>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6>Old Password</h6>
						<div class="input-container">
							<input type="password" name="old_password" minlength="6" class="asterisk input_box" maxlength="20" id="old_password" value="<?= set_value('old_password'); ?>" placeholder="Old Password">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red chng_pss_valid"><?= display_error($validation, 'old_password'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6>New Password</h6>
						<div class="password-container">
							<div class="image">
							<div class="input-container">
								<input type="password" name="new_password" minlength="6" maxlength="20" class="asterisk passwordInput input_box" value="<?= set_value('new_password'); ?>" id="new_password" placeholder="Enter New Password">
								<span class="asterisk-symbol">*</span>
							</div>
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
							</div>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red chng_pss_valid"><?= display_error($validation, 'new_password'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6>Confirm Password</h6>
						<div class="input-container">
							<input type="password" name="confirm_password" class="asterisk input_box" minlength="6" maxlength="20" value="<?= set_value('confirm_password'); ?>" id="confirm_password" placeholder="Enter Confirm Password">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red chng_pss_valid"><?= display_error($validation, 'confirm_password'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-key"></span>  Update Password</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>	
	
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
	</script>
	<!---Body Section End --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/show_pass_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>