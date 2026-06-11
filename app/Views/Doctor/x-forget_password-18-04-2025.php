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
	<title>Forget Password</title>
	<?= helper('Form'); ?>
	<?= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body class="bdy_backgrd">

	<div class="container">
		<div class="row" id="mrgn_top">
			<div class="col l4 m12 s12"></div>
			<div class="col l4 m12 s12">
				<!---Php Meassge Show --->
				<div class="reltiv">
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message"><?= session()->getTempdata('error'); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<!---Php Meassge Show --->
				<?= form_open(); ?>
				<div class="card brdr_rdius forgt_card_cntnt">
					<div class="card-content" id="div_pad">
						<i class="fa fa-exclamation-circle icon_styl"></i>
						<h6 class="for_got_align">  Forget Password</h6>
						<p class="para_styl">Enter your email and we'll send you a link to reset your password</p>
						<p class="para_sty2">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
					</div>
					<div class="card-content">
						<div class="input-container">
							<input type="text" name="email" maxlength="40" id="input_box" class="asterisk inpt_area margn_tp" value="<?= set_value('email'); ?>" placeholder="Enter Email Id">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
						<center>
							<button type="submit" class="btn btn-waves-effect waves-light frgt_pass_submit">Submit</button>
						</center>
						<br>
						<center>
							<p class="bck_to_logn"><a class="flex" href="<?= base_url('Doctor_login/doctor_login'); ?>"><i class="fa fa-angle-left"></i> Back to Login</a></p>
						</center>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
			<div class="col l4 m12 s12"></div>
		</div>
	</div>
	<script>
		// Add JavaScript to hide messages with the specified classes after 5 seconds
		setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('.success-message');
			var errMsg = document.querySelectorAll('.error-message');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds
	</script>
	<?= view('Admin/js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>