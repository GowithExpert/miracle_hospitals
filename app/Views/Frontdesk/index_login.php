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
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<title>Frontdesk</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body class="admin_login_back">
	<!----Body Section Start --->
	<!-- DEBUG-VIEW START 12 APPPATH/Views/Frontdesk/index_login.php -->
	<!---Login Form --->
	<div class="row" id="mrg_top">
		<div class="col l4 m12 s12"></div>
		<div class="col l4 m12 s12">
			<!---card Section --->
			<?= form_open('Frontdesk_login/login_account'); ?>
			<div class="card">
				<!---Php Meassge Show --->
				<div class="reltiv">
					<?php
					if (session()->getTempdata('success')) {
						?>
						<div class="card success cutom_messge_styl bckgrnd_gren">
							<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
						</div>
						<?php
						session()->removeTempdata('success');
					}
					
					if (session()->getTempdata('error')) {
						?>
						<div class="card error cutom_messge_styl bckgrnd_red">
							<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
						</div>
						<?php
						session()->removeTempdata('error');
					}
					?>
				</div>



				<!---Php Meassge Show --->

				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align h4_mrhn"><span class="fas fa-user-alt fa_icon"></span></h4>
					<h5 class="center-align log_pge_hedng">Frontdesk Login</h5>
					<div class="col-lg-12 col-md-12 col-sm-12">
            			  <p class="p_contnt">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
            		</div>


					<?php if (isset($error)) : ?>
						<div class="col_red"><?= $error; ?></div>
					<?php endif; ?>
                    <div class="input-container">
						<span class="asterisk-symbol">*</span>
						<input type="text" name="email" id="email" class="emailInput inpt_area" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
						<span id="emailError" class="col_red"></span>
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
                    <div class="input-container">
						<span class="asterisk-symbol">*</span>
						<div class="password-container">
							<div class="image">
								<input type="password" name="password" id="input_box" minlength="6" maxlength="20" class="passwordInput inpt_area" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
							</div>
						</div>
					</div>
					<span id="passwordError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<button type="submit" class="btn waves-effect waves-light login_btn" id="btn_sign_in">Sign In <span class="fa fa-sign-in-alt"></span></button>
					<a href="<?= base_url('Frontdesk_login/forget_password'); ?>" class="forgot_pass_btn">Forget Password ?</a>

					<p class="new_user_btn">New User?<a href="<?= base_url('Frontdesk_login/index'); ?>" class="cret_acc_btn"> Create Account</a></p>

					<h6 class="h6_notes_doctor">Notes:</h6>
					<p class="login_para_1">Hospital Management Software Login Panel </p>
					<p class="login_para_2">Here ! Frontdesk Accountant Login !</p>


				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>

	<script>
		$(document).ready(function() {
			$("#btn_sign_in").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
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
				} else {
					passwordError.text("");
				}

				if (!valid) {
					e.preventDefault();
					return true;
				}
			});
			setTimeout(function () {
            $(".success, .error").fadeOut(500, function () {
                $(this).remove();
            });
        }, 5000);
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
