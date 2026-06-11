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
	<title>Add Medicine</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-medkit col_blu"></span> Add Medicines </h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
			<?= form_open_multipart('Medical_Accountant/upload_medicine'); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h6><span class="far fa-building col_blu"></span> Medicine Category Name</h6>
						<div class="select-wrapper">
							<select name="med_category" class="medicineSelect">
								<option selected="" disabled="">Select Medicine Category</option>
								<?php
								if (is_array($medicine_category) || is_countable($medicine_category)) :
									
									foreach ($medicine_category as $med) : ?>
										<option value="<?=$med->category_name; ?>"><?= $med->category_name; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Medicine Category Not Found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="medicineError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_category'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-ad col_blu"></span> Batch Number</h6>
						<div class="input-container">
							<input type="text" name="batch_number" class="asterisk price batch_error" id="input_box" placeholder="Enter Batch Number" value="<?= set_value('batch_number'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="batch_error" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'batch_number'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h6><span class="far fa-building col_blu"></span> Medicine Company Name</h6>
						<div class="select-wrapper">
							<select name="med_company" class="medicineSelect">
								<option selected="" disabled="">Select Medicine Company</option>
								<?php
								if (is_array($medicine_company) || is_countable($medicine_company)) :
									
									foreach ($medicine_company as $comp) : ?>
										<option value="<?=$comp->company_name; ?>"><?= $comp->company_name; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="col_red">Medicine Category Not Found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="medicineError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_company'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6">
					<h6><span class="fas fa-capsules col_blu"></span> Medicine Name</h6>
						<div class="input-container">
							<input type="text" name="med_name" class="asterisk nameError" id="input_box" placeholder="Enter Medicine Name" value="<?= set_value('med_name'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-capsules col_blu"></span> Medicines Image</h6>
						<div class="input-container">
							<input type="file" name="med_image" class="imageError" id="input_file" value="<?= set_value('med_image'); ?>">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_image'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="far fa-calendar-alt col_blu"></span> Expiry Date</h6>
						<div class="input-container">
							<input type="date" name="med_exp_date" class="asterisk dateError" id="input_box"  value="<?= set_value('med_exp_date'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="dateError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_exp_date'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee col_blu"></span> Medicine Price</h6>
						<div class="input-container">
							<input type="text" name="med_price" class="asterisk price" id="input_box" placeholder="Enter Medicine Price" value="<?= set_value('med_price'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="price_error" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-rupee col_blu"></span> Medicine Discount Price</h6>
						<div class="input-container">
							<input type="text" name="med_d_price" class="asterisk " id="input_box" placeholder="Enter Discount Price" value="<?= set_value('med_d_price'); ?>">
						</div>

						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_d_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-coins col_blu"></span> Medical Stock</h6>
						<div class="input-container">
							<input type="text" name="med_stock" class="asterisk " id="input_box" placeholder="Enter Medical Stock" value="<?= set_value('med_stock'); ?>">
						</div>
						<?php if (isset($validation)) { ?> 
							<span class="col_red">
								<?= display_error($validation, 'med_stock'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-coins col_blu"></span> Other details</h6>
						<div class="input-container">
							<input type="text" name="other_details" class="asterisk " id="input_box" placeholder="Enter Other Details" value="<?= set_value('other_details'); ?>">
						</div>
						<?php if (isset($validation)) { ?> 
							<span class="col_red">
								<?= display_error($validation, 'other_details'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
					<h6><span class="fa fa-rupee col_blu"></span> Medicine Description</h6>
						<div class="input-container">
							<textarea name="med_dis" class="asterisk" placeholder="Enter Medicine Description"></textarea>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'med_dis'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Upload Medicine</button>
					</div>
				</div>
			<?= form_close(); ?>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				$('.medicineSelect').change(function() {
      				$('.redStar').hide();
    			});
				$("#btn_register_now").click(function(e) {
					//e.preventDefault();
					$(".error").text("");
					let valid = true;

					//Medicine validation
					const medicineSelect = $(".medicineSelect");
					const medicineError = $("#medicineError");
					if (medicineSelect.val() === null || medicineSelect.val() === "") {
						medicineError.text("Please Enter The Medicines Company Name");
						valid = false;
					} else {
						medicineError.text("");
					}


					// Name validation
					const nameInput = $(".nameError");
					const nameError = $("#nameError");
					if (nameInput.val().trim() === "") {
						nameError.text("Please Enter The Medicines Name");
						valid = false;
					} else {
						nameError.text("");
					}

					//image validation
					const imageInput = $(".imageError");
					const imageError = $("#imageError");
					const MAX_IMAGE_SIZE_KB = 500; 
					if (imageInput.val().trim() === "") {
						imageError.text("Please Upload Medicines Image");
						valid = false;
					} else {
						const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
						if (fileSize > MAX_IMAGE_SIZE_KB) {
							imageError.text("Image size should be less than 300 KB");
							valid = false;
						} else {
							imageError.text("");
						}
					}

					// Date validation
					const dateInput = $(".dateError");
					const dateError = $("#dateError");
					if (dateInput.val().trim() === "") {
						dateError.text("Please Select The Date");
						valid = false;
					} else {
						dateError.text("");
					}

					const priceInput = $(".price");
					const price = priceInput.val();
					// Use a regular expression to check if the input is numeric
					if (!/^\d+(\.\d+)?$/.test(price)) {
						$("#price_error").text("Please enter a numeric value for Price.");
						isValid = false;
					} else {
						$("#price_error").text("");
					}

					const batchInput = $(".batch_error");
					const batch_error = $("#batch_error");
					if (batchInput.val().trim() === "") {
						batch_error.text("Please enter a batch number.");
						valid = false;
					} else {
						batch_error.text("");
					}

					if (!valid) { e.preventDefault(); }
				});
			});
		</script>

		<!---Body Section End -->


		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
		<!--js file Include for asterisk symbol-->
		<?= view('Admin/asterisk_symbol_js_file.php'); ?>
		<!--js file Include for asterisk symbol-->

</body>

</html>
