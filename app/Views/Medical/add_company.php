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
	<title>Add Company</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<?= view('Home/css_file'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start --->
	<!-- <div class="container"> -->
	<div class="card">
		<div class="card-content" id="brdr_botm_silvr">
			<h5 class="hedng2"><span class="far fa-building col_blu"></span>  Add Medicine Company Name</h5>
			<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
		</div>
		<div class="card-content">
			<div class="container">
				
				<?= form_open('Medical_Accountant/upload_company', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="far fa-building col_blu"></span> Company Name</h6>
						<div class="input-container">
							<input type="text" name="company_name" class="asterisk nameError txt_placehldr" id="input_box" value="<?= set_value('company_name'); ?>" placeholder="Enter Company Name">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'company_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="far fa-building col_blu"></span> Company Contact Number</h6>
						<div class="input-container">
							<input type="tel" maxlength="10" class="phone-input phoneError txt_placehldr" name="company_c_num" id="input_box" class="asterisk" placeholder="Enter Company Contact Number" oninput="validateMobile(this)">
							<span class="mandatory_phone redStarphone">*</span>
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
						<input type="hidden" id="country_code" name="country_code" value="">
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'company_c_num'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="far fa-building col_blu"></span> Company Address</h6>
						<div class="input-container">
							<textarea name="com_address" class="asterisk addressError txt_placehldr" placeholder="Enter Company Address"></textarea>
							<span class="asterisk-symbol">*</span>
							<span id="addressError" class="col_red txt_area_valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'com_address'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
			</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn">Add Company</button>
					</div>
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
	<script>
		$(document).ready(function() {
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;

				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter Company Name");
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

				//Address validation 
				const addressInput = $(".addressError");
				const addressError = $("#addressError");
				if (addressInput.val().trim() === "") {
					addressError.text("Please Enter Company Address");
					valid = false;
				} else {
					addressError.text("");
				}
				if (!valid) { e.preventDefault(); }
			});
		});
	</script>
	<!-- </div> -->
	<!---Body Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>
