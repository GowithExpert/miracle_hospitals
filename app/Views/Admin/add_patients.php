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
	<title>Add Appointments</title>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-wheelchair col_blu"></span> Add Appointments</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				
				<?= form_open('Admin/upload_patients', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();', 'enctype' => 'multipart/form-data')); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-wheelchair col_blu"></span> Patient Name</h6>
						<div class="input-container">
							<input type="text" name="patient_name" class="asterisk nameError" id="input_box" placeholder="Enter Patient Name">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-phone-square col_blu"></span>  Mobile Number</h6>
						<div class="input-container">
							<input type="tel" name="patient_phone" class="asterisk phone-input phoneError" id="input_box" maxlength="10" placeholder="Enter Mobile Number" oninput="validateMobile(this)" value="<?= set_value('patient_phone'); ?>">
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
						<input type="hidden" id="country_code" name="country_code" value="">

						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_phone'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-wheelchair col_blu"></span> Patient Age</h6>
						<div class="input-container">
							<input type="text" name="patient_age" class="asterisk ageError" id="input_box" placeholder="Enter Patient Age" maxlength="40" value="<?= set_value('patient_age'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="ageError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_age'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-mars col_blu"></span> Select Gender</h6>
						<div class="select-wrapper">
							<select name="patient_gender" class="genderSelect" id="patient_gender" required>
								<option selected="" disabled="">Select Patient Gender</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
								<option value="Other">Other</option>
							</select>
							<span class="mandatory redStargenderSelect">*</span>
							<span id="genderError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_gender'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-disease col_blu"></span> Disease Symptoms</h6>
						<div class="input-container">
							<input type="text" name="patient_issue" class="asterisk diseaseError" id="input_box" placeholder="Enter Patient Disease Symptoms" value="<?= set_value('patient_issue'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="diseaseError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_issue'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-disease col_blu"></span> Disease Detail</h6>
						<input type="text" name="other_info" id="input_box" placeholder="Enter Disease Detail" maxlength="" value="<?= set_value('other_info'); ?>">
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'other_info'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h6><span class="fa fa-envelope col_blu"></span> Patient Email</h6>
						<input type="email" name="patient_email" maxlength="40" id="input_box" placeholder="Enter Patient Email ID" value="<?= set_value('patient_email'); ?>">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-camera col_blu"></span> Upload Patient Photo</h6>
						<div class="input-container">
							<input type="file" name="patient_image" class="asterisk imageError" id="input_file" value="<?= set_value('patient_image'); ?>">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_image'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> Ward Name</h6>
						<div class="input-container">
							<input type="text" name="ward_name" class="asterisk wardError" id="input_box" placeholder="Enter Patient Ward Name" value="<?= set_value('ward_name'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="wardError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'ward_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-home col_blu"></span> Patient Room Number</h6>
						<div class="input-container">
							<input type="text" name="patient_room" class="asterisk roomError" id="input_box" placeholder="Enter Patient Room Number" maxlength="" value="<?= set_value('patient_room'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="roomError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_room'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> Patient Address</h6>
						<div class="input-container">
							<input type="text" name="patient_address" class="asterisk addressError" id="input_box" placeholder="Enter Patient Address" value="<?= set_value('patient_address'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="addressError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_address'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> Pincode</h6>
						<div class="input-container">
							<input type="text" name="patient_zip" class="asterisk pincodeError" id="input_box" placeholder="Enter Pincode" maxlength="10" size="10" value="<?= set_value('patient_zip'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="pincodeError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'patient_zip'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">

					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user-md col_blu"></span> Select Doctor</h6>
						<select name="doctor_name" class="doctorSelect" id="doctor">
							<option selected="" disabled="">Select Doctor</option>
							<?php if ($doctors) :
								count($doctors);
								foreach ($doctors as $doc) : ?>
									<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
								<?php endforeach; ?>
							<?php else : ?>
								<h6 class="col_red">Doctor Not Found</h6>
							<?php endif; ?>
							<span id="doctorError" class="col_red valid_err"></span>
						</select>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'doctor_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Doctor Fee</h6>
						<select name="doctor_fee" id="doctor_fee" class="doctor_fee">
							<option selected="" disabled="">Select Doctor Fee</option>
							<option value=""></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Entry Fee</h6>
						<div class="input-container">
							<input type="text" name="entry_fee" class="asterisk entryfeeError" id="input_box" placeholder="Entry Fee">
							<span class="asterisk-symbol">*</span>
							<span id="entryfeeError" class="col_red valid_err"></span>
						</div>

					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Other Fee</h6>
						<input type="text" name="other_fee" id="input_box" placeholder="Other Fee">
					</div>

				</div>
				<input type="hidden" name="doc_name" id="doc_name" value="">
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Add Patient Details</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>
	<script>
		// $.noConflict(); // Relinquish control of the $ symbol to other libraries
		// jQuery.noConflict();
		// jQuery(function($) {
			$(document).ready(function() {
				$('.genderSelect').change(function() {
      				$('.redStargenderSelect').hide();
    			});
				console.log("This is testing phase");
				var dr_name = '';
				$('#doctor').change(function() { //Call on Doctor selection from dropdown
					var selected_dr_id = $(this).val(); //Selected Doctor
					if (selected_dr_id) {
						var dr_name = $('#doctor option:selected').text(); //Get selected Dr. name
						$('#doc_name').val(dr_name);
						console.log(dr_name);
						return dr_name;
					}
				}); //Closed - Function

				$("#btn_register_now").click(function(e) {
					//e.preventDefault();
					$(".error").text("");

					let valid = true;

					// Name validation
					const nameInput = $(".nameError");
					const nameError = $("#nameError");
					if (nameInput.val().trim() === "") {
						nameError.text("Please Enter The Patient Name");
						valid = false;
					} else {
						nameError.text("");
					}

					// // Mobile validation
					const phoneInput = $(".phoneError");
					const phoneError = $("#phoneError");
					if (!/^\d{10}$/.test(phoneInput.val())) {
						phoneError.text("Mobile Number Must Be 10 Digits Numeric");
						valid = false;
					} else {
						phoneError.text("");
					}

					const ageInput = $(".ageError");
					const ageError = $("#ageError");
					if (ageInput.val().trim() === "") {
						ageError.text("Please Enter Patient Age");
						valid = false;
					} else {
						ageError.text("");
					}



					// // Gender validation
					const genderSelect = $(".genderSelect");
					const genderError = $("#genderError");
					if (genderSelect.val() === null || genderSelect.val() === "") {
						genderError.text("Please Select The Gender");
						valid = false;
					} else {
						genderError.text("");
					}

					// //Address validation 
					const diseaseInput = $(".diseaseError");
					const diseaseError = $("#diseaseError");
					if (diseaseInput.val().trim() === "") {
						diseaseError.text("Please Enter Disease Symptoms");
						valid = false;
					} else {
						diseaseError.text("");
					}

					// //image validation
					const imageInput = $(".imageError");
					const imageError = $("#imageError");
					const MAX_IMAGE_SIZE_KB = 500; 
					if (imageInput.val().trim() === "") {
						imageError.text("Please Upload Patient Image");
						valid = false;
					} else {
						const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
						if (fileSize > MAX_IMAGE_SIZE_KB) {
							imageError.text("Image size should be less than 500 KB");
							valid = false;
						} else {
							imageError.text("");
						}
					}

					
					// //Address validation 
					const wardInput = $(".wardError");
					const wardError = $("#wardError");
					if (wardInput.val().trim() === "") {
						wardError.text("Please Enter Ward Name");
						valid = false;
					} else {
						wardError.text("");
					}

					// //Address validation 
					const roomInput = $(".roomError");
					const roomError = $("#roomError");
					if (roomInput.val().trim() === "") {
						roomError.text("Please Enter Room Number");
						valid = false;
					} else {
						roomError.text("");
					}

					// //Specility validation
					const addressInput = $(".addressError");
					const addressError = $("#addressError");
					if (addressInput.val().trim() === "") {
						addressError.text("Please Enter Patient Address");
						valid = false;
					} else {
						addressError.text("");
					}

					// //Degree validation
					const pincodeInput = $(".pincodeError");
					const pincodeError = $("#pincodeError");
					if (pincodeInput.val().trim() === "") {
						pincodeError.text("Please Enter Pin Code");
						valid = false;
					} else {
						pincodeError.text("");
					}

			
					// //fees validation
					const entryfeeInput = $(".entryfeeError");
					const entryfeeError = $("#entryfeeError");
					if (entryfeeInput.val().trim() === "") {
						entryfeeError.text("Please Enter Entry Fees");
						valid = false;
					} else {
						entryfeeError.text("");
					}
					if (!valid) {
						e.preventDefault();
					}
				});
			});
	</script>
	<!---Body Section Start -->


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/country_code_js_file.php'); ?>

</body>

</html>
