<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
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
Date: 21st August, 2023 
-->
<!-- <//?php echo "<pre>"; print_r($doctor);die;?> -->
<!DOCTYPE html>
<html>

<head>
	<title>Create Doctor Login Account</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-key col_blu"></span> Create Doctor Login Account</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			
			<?= form_open_multipart('Login/Create_doctor_account'); ?>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-12  col-md-12 col-sm-12">
						<h6><span class="fa fa-user-md col_blu"></span> Select Doctor Name</h6>
						<div class="select-wrapper">
							<select name="selected_name" id="selected_name" class="nameSelect"> value="
								<?= set_value('doctor_name'); ?>">
								<option selected="" disabled="">Select Doctor Name</option>
								<?php
								if (count($doctor)) :
									foreach ($doctor as $doc) : ?>
									
										<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Select Doctor Name</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_name'); ?>
							</span>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user-md col_blu"></span> Doctor Name</h6>
						<div class="select-wrapper">
							<select name="doctor_name" id="doctor_name" class="drnameSelect">
								<?= set_value('doctor_name'); ?>">
								<option selected="" disabled="">Doctor Name</option>
								<?php if (count($doctor)) :
									foreach ($doctor as $doc) : ?>
										<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col-red">Select Doctor Name</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="drnameError" class="col_red valid_err"></span>
						</div>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mobile-phone col_blu"></span> Doctor Mobile</h6>
						<div class="select-wrapper">
							<select name="mobile" id="mobile" class="phoneSelect"> value="
								<?= set_value('mobile'); ?>">
								<option selected="" disabled="">Doctor Mobile</option>
								<?php if (count($doctor)) :
									foreach ($doctor as $doc) : ?>
										<option value="<?= $doc->id; ?>"><?= $doc->doctor_phone; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Select Doctor Mobile</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="phoneError" class="col_red valid_err"></span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mars col_blu"></span> Gender</h6>
						<div class="select-wrapper">
							<select name="gender" id="gender" class="genderSelect">
								<?= set_value('gender'); ?>">
								<option selected="" disabled="">Select Gender</option>
								<?php if (count($doctor)) :
									foreach ($doctor as $doc) : ?>
										<option value="<?= $doc->id; ?>"><?= $doc->gender; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Select Doctor Gender</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory_gender redStar">*</span>
							<span id="genderError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'gender'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Doctor Email</h6>
						<div class="select-wrapper">
							<select name="doctor_email" id="doctor_email" class="emailSelect">
								<option selected="" disabled="">Doctor Email</option>
								<?php if (count($doctor)) :
									foreach ($doctor as $doc) : ?>
										<option value="<?= $doc->doctor_email; ?>"><?= $doc->doctor_email; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Select Doctor Email'</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="emailError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'doctor_email'); ?>
								<?= $validation->listErrors(); ?></span>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-lock col_blu"></span> Password</h6>
						<div class="password-container">
							<div class="image">
								<div class="input-container">
									<input type="password" name="password" class="asterisk passwordInput" maxlength="20" id="input_box" placeholder="Password" value="<?= set_value('password'); ?>">
									<span class="asterisk-symbol">*</span>
									<span id="passwordError" class="col_red valid_err"></span>
								</div>
								<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
							</div>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'password'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-lock col_blu"></span> Confirm Password</h6>
						<div class="input-container">
							<input type="password" name="conf_password" id="cnf_pswrd" class="asterisk conf_password" placeholder="Confirm Password" value="<?= set_value('conf_password'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="confirmPasswordError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'conf_password'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn">Create Account</button>
					</div>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
	<!---Body Section End --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->

	<script type="text/javascript">
		$(document).ready(function() {
			$('.nameSelect').change(function() {
      			$('.redStar').hide();
    		});
			$('.drnameSelect').change(function() {
				$('.redStar').hide();
			});
			$('.phoneSelect').change(function() {
      			$('.redStar').hide();
    		});
			$('.genderSelect').change(function() {
				$('.redStar').hide();
			});
			$('.emailSelect').change(function() {
				$('.redStar').hide();
			});

			$('#selected_name').change(function() {
				var id = $(this).val();
				$.ajax({
					url: '<?php echo site_url('Login/get_doctor_data/'); ?>' + id,
					method: "POST",
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var ehtml = '';
						var mhtml = '';
						var nhtml = '';
						var i;
						var j;
						var m;
						var k;
						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].doctor_email + '>' + data[i].doctor_email + '</option>';
							$('#doctor_email').html(html);
						}
						console.log(data[0].doctor_email);
						html = '';
						for (j = 0; j < data.length; j++) {
							ehtml += '<option value=' + "'" + data[j].doctor_name + "'" + '>' + data[j].doctor_name + '</option>';
							$('#doctor_name').html(ehtml);
						}
						console.log(data[0].doctor_name);
						for (m = 0; m < data.length; m++) {
							mhtml += '<option value=' + data[m].doctor_phone + '>' + data[m].doctor_phone + '</option>';
							$('#mobile').html(mhtml);
						}
						for (k = 0; k < data.length; k++) {
							html += '<option value=' + data[k].gender + '>' + data[k].gender + '</option>';
							$('#gender').html(html);
						}
					}
				});
				return false;
			});
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;


				// name  validation
				const nameSelect = $(".nameSelect");
				const nameError = $("#nameError");
				if (nameSelect.val() === null || nameSelect.val() === "") {
					nameError.text("Please select the Dr.Name");
					valid = false;
				} else {
					nameError.text("");
				}

				// name  validation
				const drnameSelect = $(".drnameSelect");
				const drnameError = $("#drnameError");
				if (drnameSelect.val() === null || drnameSelect.val() === "") {
					drnameError.text("Please select the Dr.Name");
					valid = false;
				} else {
					drnameError.text("");
				}

				// mobile number  validation
				const phoneSelect = $(".phoneSelect");
				const phoneError = $("#phoneError");
				if (phoneSelect.val() === null || phoneSelect.val() === "") {
					phoneError.text("Please select the mobile number");
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


				// email validation
				const emailSelect = $(".emailSelect");
				const emailError = $("#emailError");
				if (emailSelect.val() === null || emailSelect.val() === "") {
					emailError.text("Please select the Dr email");
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
		});
	</script>
	<?= view('Admin/show_pass_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
