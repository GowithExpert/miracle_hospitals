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
	<title>Donor Registration</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script> -->
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<style>
		*::-webkit-scrollbar-thumb {
			background-color: #005197;
		}
		.select-wrapper {
			position: relative;
			display: inline-block;
			width: 100%;
		}

		.asterisk {
			margin-right: 20px; /* Adjust the margin as needed for spacing */
		}

		.mandatory {
			position: absolute;
			left: 10px; /* Adjust the left position as needed */
			top: 40%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}
		.mandatory_phone {
			position: absolute;
			left: 78px; /* Adjust the left position as needed */
			top: 40%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}
		.mandatory_gender{
			position: absolute;
			left: 8px; /* Adjust the left position as needed */
			top: 40%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}
		a:focus{
			color: inherit !important;
		}
	</style>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!------Body Section Start ----->
	<div class="container">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 600"><span class="fa fa-users" style="color: #005197"></span>  Blood Donor Registration</h5>
			</div>
			<!-- <//?= form_open_multipart('Blood_bank_donor/donor_registered'); ?> -->
			<?= form_open_multipart('Blood_bank_donor/donor_registered', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user" style="color: #005197"></span> Donor Name</h6>
						<div class="input-container">
							<input type="text" name="donor_name" class="asterisk nameError" id="input_box" value="<?= set_value('donor_name'); ?>" placeholder="Enter Donor Name">
							<span class="asterisk-symbol">*</span>
						</div>
						<span id="nameError" style="color:red"></span>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'donor_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt" style="color: #005197"></span> Blood Group</h6>
						<div class="select-wrapper">
							<select name="blood_group" class="asterisk selectblood">
								<option selected="" disabled="">Select Blood Group</option>
								<option value="A+">A+</option>
								<option value="A-">A-</option>
								<option value="B+">B+</option>
								<option value="B-">B-</option>
								<option value="AB+">AB+</option>
								<option value="AB-">AB-</option>
								<option value="O+">O+</option>
								<option value="O-">O-</option>
							</select>
							<span class="mandatory redStarselectblood">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'blood_group'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-mobile-alt" style="color: #005197"></span> Contact Number</h6>
						<div class="input-container">
							<input type="tel" maxlength="10" class="phone-input phoneError" class="asterisk" value="<?= set_value('number'); ?>" name="contact_number" id="input_box" placeholder="Enter Contact Number">
						</div>
						<span id="phoneError" style="color:red"></span>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" style="margin-top: 10px;"></div>
						<input type="hidden" id="country_code" name="country_code" value="">
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'contact_number'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-camera" style="color: #005197"></span> Donor Photo</h6>
						<input type="file" name="donor_photo" id="input_file">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-6 col-sm-12">
						<h6><span class="fas fa-map-marker-alt" style="color: #005197"></span> Donor Address</h6>
						<div class="input-container">
							<textarea name="address" class="asterisk addressError" name="<?= set_value('address'); ?>" placeholder="Enter Donor Address"></textarea>
							<span style="top: 19%" class="asterisk-symbol">*</span>
						</div>
						<span id="addressError" style="color:red"></span>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'address'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<center>
					<button type="submit" class="btn btn-waves-effect waves-light btn_hver btn_register_now" style="text-transform: capitalize;font-weight: 500;font-size: 16px;background: #005197;margin-top: 30px;"><span class="fa fa-user"></span>  Add Donor Details</button>
				</center>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
	<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
		$(document).ready(function() {
			$('.selectblood').on('input', function() {
      			$('.redStarselectblood').hide();
    		});
			$(".btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;

				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please enter the donor name");
					valid = false;
				} else {
					nameError.text("");
				}

				// Mobile validation
				const phoneInput = $(".phoneError");
				const phoneError = $("#phoneError");
				if (!/^\d{10}$/.test(phoneInput.val())) {
					phoneError.text("Mobile number must be 10 digits numeric");
					valid = false;
				} else {
					phoneError.text("");
				}

				//Address validation 
				const addressInput = $(".addressError");
				const addressError = $("#addressError");
				if (addressInput.val().trim() === "") {
					addressError.text("Please enter address");
					valid = false;
				} else {
					addressError.text("");
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
				// //image validation
				// const imageInput = $(".imageError");
				//   const imageError = $("#imageError");
				//   if (imageInput.val().trim() === "") {
				//     imageError.text("Please upload doctor image");
				//     valid = false;
				//   }
				//   else {
				// 	imageError.text(""); 
				// }			
				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>
	<!------Body Section End   ----->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/country_code_js_file.php'); ?>

</body>

</html>
