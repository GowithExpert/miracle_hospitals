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
	<title>Add Medicines Company</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include  -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-medkit col_blu"></span> Add Medicines Company</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Admin/add_med_company'); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 -col-sm-12">
						<h6><span class="far fa-building col_blu"></span> Medicines Company Name</h6>
						
						<div class="input-container">
							<input type="text" name="company_name" class="asterisk nameError" id="input_box" placeholder="Add Medicines Company Name" value="<?= set_value('company_name'); ?>">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'company_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>

					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-capsules col_blu"></span> Medicines Image</h6>
						<div class="input-container">
							<input type="file" name="category_image" class="imageError" id="input_file">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'category_image'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="far fa-building"></span> Add Company</button>
					</div>
				</div>
				<?= form_close(); ?>

			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
					nameError.text("Please Enter The Medicines Company Name");
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
						imageError.text("Image size should be less than 500KB");
						valid = false;
					} else {
						imageError.text("");
					}
				}

				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
