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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<title>Admin Access</title>
	<?= helper('Form'); ?>
	<?= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body class="admin_login_back">
	<!----Body Section Start --->
	<!---Login Form --->
	<div class="row" id="mrg_top">
		<div class="col l4 m4 s12"></div>
		<div class="col l4 m4 s12">

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

			<!---card Section --->
			
			
			<?= form_open('/Login', ['method' => 'post']) ?>
			<div class="card brdr_rdius">
				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align h4_mrhn"><span class="fas fa-user-tie fa_icon"></span></h4>
					<h5 class="center-align log_pge_hedng">Admin Login</h5>

					<?php if (isset($error)) : ?>
						<div class="col_red"><?= $error; ?></div>
					<?php endif; ?>

					<div class="input-container">
						<input type="text" name="email" id="email_error" class="emailInput inpt_area" placeholder="Email address" maxlength="40" value="<?= set_value('email'); ?>">
						<span id="emailError" class="col_red"></span>
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<div class="password-container">
							<div class="image">
								<input type="password" name="password" class="passwordInput inpt_area" id="input_box" maxlength="20" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
							</div>
						</div>
						<span id="passwordError" class="col_red"></span>
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					<button type="submit" id="btn_register_now" class="btn waves-effect waves-light login_btn">Sign In <span class="fa fa-sign-in-alt"></span></button>
					<a href="<?= base_url('Admin/forget_password'); ?>" class="forgot_pass_btn">Forget Password ?</a>
					<br>

					<?php if (isset($loginButton)) : ?>
						<a href="<?= $loginButton; ?>">
							<img src="<?= base_url('public/assets/images/google.png') ?>" class="login_btn_img">
						</a>
					<?php endif; ?>

					<h6 class="h6_notes">Notes:</h6>
					<p class="login_para_1">Hospital Management Software Login Panel </p>
					<p class="login_para_2">Admin Login will Manage All Activity in that Hospital </p>


				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m4 s12"></div>
	</div>

	<script>
		$(document).ready(function() {
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("Something went wrong");
				let valid = true;

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
				} 
				else { passwordError.text(""); }
				if (!valid) e.preventDefault();
			});
		});
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
	<?= view('Admin/show_pass_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
</body>
</html>
