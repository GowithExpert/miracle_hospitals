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
	<title>Add Blood Group</title>
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Home/css_file'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
</head>
<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!------Body Section Start ---->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fas fa-fire-alt col_blu"></span>  Add Blood Group
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
				</h5>
			</div>
			<?= form_open('Blood_bank/add_blood_group'); ?>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<!--
						<h6><span class="fas fa-fire-alt col_blu"></span> Blood Group Name</h6>
						<div class="input-container">
							<input type="text" name="blood_group" class="asterisk bloodError" id="input_box" value="<?= set_value('blood_group'); ?>" placeholder="Enter Blood Group">
							<span class="asterisk-symbol">*</span>
							<span id="bloodError" class="col_red valid_err"></span>
						</div>-->
						<h6><span class="fa fa-mars col_blu"></span> Blood Group</h6>
						<div class="input-container">
							<select name="blood_group" class="asterisk bloodError" id="input_box" required> 
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
							<span class="mandatory redStargenderSelect">*</span>
							<span id="genderError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'blood_group'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fas fa-fire-alt col_blu"></span> Blood Unit</h6>
					<div class="input-container">
						<input type="number" name="blood_unit" class="asterisk unitError" value="<?= set_value('blood_unit'); ?>" id="blood_unit" placeholder="Enter Blood Unit">
						<span class="asterisk-symbol">*</span>
						<span id="unitError" class="col_red valid_err"></span>
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red">
							<?= display_error($validation, 'blood_unit'); ?>
						</span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
				</div>
				</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-rupee col_blu"></span> Blood Price/Unit</h6>
					<div class="input-container">
						<input type="number" name="blood_price" class="asterisk priceError" value="<?= set_value('blood_price'); ?>" id="blood_price" placeholder="Enter Blood Price">
						<span class="asterisk-symbol">*</span>
						<span id="priceError" class="col_red valid_err"></span>
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red">
							<?= display_error($validation, 'blood_price'); ?>
						</span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-rupee col_blu"></span> Total Blood Price</h6>
					<div class="input-container">
						<input type="number" name="total_blood_price" class="asterisk totalError" value="<?= set_value('total_blood_price'); ?>" id="total_blood_price" placeholder="Enter Total Blood Price" readonly>
						<!-- <span id="totalError" class="col_red valid_err"></span> -->
					</div>
					<?php if (isset($validation)) { ?>
						<span class="col_red">
							<?= display_error($validation, 'total_blood_price'); ?>
						</span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
				</div>
			</div>

				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_sign_in" class="btn btn-waves-effect waves-light btn_hver"><span class="fas fa-fire-alt"></span> Add Blood Group</button>
					</div>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
		$(document).ready(function() {
			$("#btn_sign_in").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;

				// Blood Group validation
				const bloodInput = $(".bloodError");
				const bloodError = $("#bloodError");
				if (bloodInput.val().trim() === "") {
					bloodError.text("Please Enter Blood Group Name");
					valid = false;
				} else {
					bloodError.text("");
				}


				// Blood Unit validation
				const unitInput = $(".unitError");
				const unitError = $("#unitError");
				if (unitInput.val().trim() === "") {
					unitError.text("Please Enter Blood Unit Name");
					valid = false;
				} else {
					unitError.text("");
				}

				//Blood Price 1 Unit validation
				const priceInput = $(".priceError");
				const priceError = $("#priceError");
				if (priceInput.val().trim() === "") {
					priceError.text("Please Enter Blood Price Per 1 Unit");
					valid = false;
				} else {
					priceError.text("");
				}

				//Total Blood Price validation
				const totalInput = $(".totalError");
				const totalError = $("#totalError");
				if (totalInput.val().trim() === "") {
					totalError.text("Please Enter Total Blood Price");
					valid = false;
				} else {
					totalError.text("");
				}


				
				if (!valid) {
					e.preventDefault();
				}
			});
			const $bloodUnitInput = $('#blood_unit');
			const $bloodPriceInput = $('#blood_price');
			const $totalBloodPriceInput = $('#total_blood_price');

			$bloodUnitInput.on('input', updateTotalBloodPrice);
			$bloodPriceInput.on('input', updateTotalBloodPrice);

			function updateTotalBloodPrice() {
				const bloodUnit = parseFloat($bloodUnitInput.val()) || 0;
				const bloodPrice = parseFloat($bloodPriceInput.val()) || 0;
				const totalBloodPrice = bloodUnit * bloodPrice;
				$totalBloodPriceInput.val(totalBloodPrice.toFixed(2)); // Display the result with 2 decimal places
			}
		});
	</script>
	<!------Body Section End ---->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
