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
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
	<title>Add Slip Name</title>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<?= view('Home/css_file'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
	<!----Body Section Start --->
	<div class="card">
		<div class="card-content" id="brdr_botm_silvr">
			<h5 class="hedng2"><span class="fas fa-user-plus col_blu"></span>  Add Customer Name</h5>
			<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
		</div>
		<div class="card-content">
			
			<?= form_open('Medical_Accountant/add_customer_bill_slip', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="container">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fas fa-user-alt col_blu"></span> Customer Name</h6>
						<div class="input-container">
							<input type="text" name="cus_name" class="asterisk nameError" id="input_box" placeholder="Enter Customer Name">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'cus_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-address-card-o col_blu"></span> Customer Address</h6>
						<div class="input-container">
							<input type="text" name="cus_address" class="asterisk addressError" id="input_box" placeholder="Enter Customer Address">
							<span class="asterisk-symbol">*</span>
							<span id="addressError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'cus_address'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-mobile-phone col_blu"></span> Customer Mobile</h6>
						<div class="input-container">
							<input type="tel" name="cus_number" class="asterisk phone-input phoneError" id="input_box" maxlength="10" placeholder="Enter Customer Mobile Number" oninput="validateMobile(this)">
							<span class="mandatory_phone redStarphone">*</span>
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
						<input type="hidden" id="country_code" name="country_code" value="">
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'cus_number'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-user-md col_blu"></span> Doctor Name</h6>
						<div class="input-container">
							<input type="text" name="doc_name" class="asterisk drError" id="input_box" placeholder="Enter Doctor Name">
							<span class="asterisk-symbol">*</span>
							<span id="drError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'doc_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="row row12">
				<div class="col">
					<button type="submit" class="btn btn-waves-effect waves-light btn_hver btn_register_now sub_btn">Add Customer Name</button>
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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;

				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter Customer Name");
					valid = false;
				} else {
					nameError.text("");
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

				// Mobile validation
				const phoneInput = $(".phoneError");
				const phoneError = $("#phoneError");
				if (!/^\d{10}$/.test(phoneInput.val())) {
					phoneError.text("Mobile Number Must Be 10 Digits Numeric");
					valid = false;
				} else {
					phoneError.text("");
				}


				//Degree validation
				const drInput = $(".drError");
				const drError = $("#drError");
				if (drInput.val().trim() === "") {
					drError.text("Please Enter Doctor Name");
					valid = false;
				} else {
					drError.text("");
				}
				if (!valid) { e.preventDefault(); }
			});
		});
	</script>

	<!----Body Section End --->
	<?= view('Admin/country_code_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>
