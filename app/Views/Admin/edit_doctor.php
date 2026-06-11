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
	<title>Update Doctor Details </title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="container">
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
				<h5 class="dr_detil"><span class="fa fa-user-md fa_icon1"></span> Update Doctor Details</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Admin/update_doctor/' . $update_doctor[0]->id, array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user col_blu"></span> Name</h6>
						<div class="input-container">
							<input type="text" name="doctor_name" class="asterisk nameError" id="input_box" placeholder="Enter Doctor Name" value="<?= $update_doctor[0]->doctor_name; ?>" >
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-mobile-alt col_blu"></span>  Mobile</h6>
						<div class="input-container">
							<input type="tel" name="doctor_phone" id="input_box" class="asterisk phone-input phoneError phone_mandatory" maxlength="10" oninput="validateMobile(this)" value="<?= $update_doctor[0]->doctor_phone; ?>" placeholder="Enter Mobile Number" >
							<span class="asterisk_phone">*</span>
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
					</div>
					<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_phone'); ?>
							</span>
							<?= $validation->listErrors(); ?>
					<?php } ?>
				</div>
				<input type="hidden" id="country_code" name="country_code" value="">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Doctor Email</h6>
						<div class="input-container">
							<input type="email" name="doctor_email" id="input_box" placeholder="Enter Email Address" class="asterisk emailInput emailError" value="<?= $update_doctor[0]->doctor_email; ?>" >
							<span class="asterisk-symbol">*</span>
							<span id="emailError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_email'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> 
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks col_blu"></span> Update Department</h6>
						<div class="select-wrapper">
							<select name="department_name" class="asterisk departmentSelect">
								<?php if ($department) :
									count($department);
									foreach ($department as $pro) :
								?>
										<?php if ($update_doctor[0]->department_name == $pro->department_name) : ?>
											<option value="<?= $pro->department_name; ?>" selected><?= $pro->department_name; ?></option>
										<?php else : ?>
											<option value="<?= $pro->department_name; ?>"><?= $pro->department_name; ?></option>
										<?php endif; ?>
									<?php endforeach;
								else : ?>
									<option value="">Department Not Found</option>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="departmentError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'department_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> 
					</div>
				</div>
				<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-map-marker-alt col_blu"></span> Doctor Address</h6>
						<div class="input-container">
							<input type="text" name="doctor_address" id="input_box" placeholder="Enter Address" class="asterisk addressError" value="<?= $update_doctor[0]->doctor_address; ?>" >
							<span class="asterisk-symbol">*</span>
							<span id="addressError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_address'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> 
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-book col_blu"></span> Doctor Specility</h6>
						<div class="input-container">
							<input type="text" name="dr_specialization" class="asterisk SpecilityError" id="input_box" placeholder="Enter Specility" value="<?= $update_doctor[0]->dr_specialization; ?>">
							<span class="asterisk-symbol">*</span>
							<span id="SpecilityError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'dr_specialization'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> 
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-graduation-cap col_blu"></span>Doctor Degree</h6>
						
						<div class="input-container">
							<input type="text" name="education" class="asterisk degreeError" id="input_box" placeholder="Enter Degree" value="<?= $update_doctor[0]->education; ?>">
							<span class="asterisk-symbol">*</span>
							<span id="degreeError" class="col_red valid_err"></span>
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mars col_blu"></span> Gender</h6>
						<div class="select-wrapper">
							<select class="genderSelect asterisk" name='gender'  id=''>
							<option <?php echo ($update_doctor[0]->gender == 'Male') ? 'selected' : ''; ?> value='Male'>Male</option>
							<option <?php echo ($update_doctor[0]->gender == 'Female') ? 'selected' : ''; ?> value='Female'>Female</option>
							<option <?php echo ($update_doctor[0]->gender == 'Other') ? 'selected' : ''; ?> value='Other'>Other</option>
							</select>
							<span class="mandatory_gender redStargenderSelect">*</span>
							<span id="genderError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'gender'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<!-- <h6><span class="fa fa-camera col_blu"></span> Update Doctor Image</h6>
						<input type="file" name="profile_pic" class="imageError" id="input_file" ="" value="<//?= $update_doctor[0]->profile_pic; ?>"> -->
						<h6><span class="fa fa-camera col_blu"></span> Update Doctor Image</h6>
						<input type="file" name="profile_pic" class="imageError" id="input_file"  value="<?= set_value('profile_pic'); ?>">
						<span id="imageError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'profile_pic'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<span id="imageError" class="col_red"></span>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Update Doctor Fee</h6>
						<div class="input-container">
							<input type="text" name="doctor_fee" class="asterisk feesError" placeholder="Enter Doctor Fee" id="input_box" value="<?= $update_doctor[0]->doctor_fee; ?>">
							<span class="asterisk-symbol">*</span>
							<span id="feesError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_fee'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fa fa-info-circle col_blu"></span> Doctor Other Information</h6>
						<textarea name="doctor_other_info" placeholder="Enter Other Information" value="<?= $update_doctor[0]->doctor_other_info; ?>"></textarea>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Update Doctor Details</button>
					</div>
				</div>
				<?= form_close(); ?>

			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		function updateCountryCode() {
			var countrySelector = document.getElementById('country_selector');
			var selectedCountryCode = countrySelector.value;
			document.getElementById('country_code').value = selectedCountryCode;
		}
		$(document).ready(function() {
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");

				let valid = true;

				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter The Doctor Name");
					valid = false;
				} else {
					nameError.text("");
				}

				// Mobile validation
				const phoneInput = $(".phoneError");
				const phoneError = $("#phoneError");
				if (!/^\d{10}$/.test(phoneInput.val())) {
					phoneError.text("Mobile Number Must Be 10 Digits Numeric");
					valid = false;
				} else {
					phoneError.text("");
				}

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

				//Department validation
				const departmentSelect = $(".departmentSelect");
				const departmentError = $("#departmentError");
				if (departmentSelect.val() === null || departmentSelect.val() === "") {
					departmentError.text("Please Select The Department");
					valid = false;
				} else {
					departmentError.text("");
				}

				//Address validation 
				const addressInput = $(".addressError");
				const addressError = $("#addressError");
				if (addressInput.val().trim() === "") {
					addressError.text("Please Enter Address");
					valid = false;
				} else {
					addressError.text("");
				}

				//Specility validation
				const SpecilityInput = $(".SpecilityError");
				const SpecilityError = $("#SpecilityError");
				if (SpecilityInput.val().trim() === "") {
					SpecilityError.text("Please Enter Doctor Specility");
					valid = false;
				} else {
					SpecilityError.text("");
				}

				//Degree validation
				const degreeInput = $(".degreeError");
				const degreeError = $("#degreeError");
				if (degreeInput.val().trim() === "") {
					degreeError.text("Please Enter Doctor Degree");
					valid = false;
				} else {
					degreeError.text("");
				}


				// Gender validation
				const genderSelect = $(".genderSelect");
				const genderError = $("#genderError");
				if (genderSelect.val() === null || genderSelect.val() === "") {
					genderError.text("Please Select The Gender");
					valid = false;
				} else {
					genderError.text("");
				}


				//image validation
				/*const imageInput = $(".imageError");
				const imageError = $("#imageError");
				const MAX_IMAGE_SIZE_KB = 500; 
				if (imageInput.val().trim() === "") {
					imageError.text("Please Upload Doctor Image");
					valid = false;
				} else {
					const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
					if (fileSize > MAX_IMAGE_SIZE_KB) {
						imageError.text("Image size should be less than 500Kb");
						valid = false;
					} else {
						imageError.text("");
					}
				} */
				
				//fees validation
				const feesInput = $(".feesError");
				const feesError = $("#feesError");
				if (feesInput.val().trim() === "") {
					feesError.text("Please Enter Doctor Fees");
					valid = false;
				} else {
					feesError.text("");
				}
				if (!valid) {
					e.preventDefault(); 
				}
			});

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
		});
	</script>


	<!---Body Section Start -->
	<?= view('Admin/country_code_js_file.php'); ?>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
