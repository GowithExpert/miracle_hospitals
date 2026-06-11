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
	<title>Edit Medicine Category</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fas fa-capsules col_blu"></span> Edit Medicine Category</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<?php
				if (is_array($med_cat) && isset($med_cat[0]->id)) { ?>
					<?= form_open_multipart('Admin/update_med_category/' . $med_cat[0]->id); ?>
				<?php } else {
					echo "Zero records found";
				} ?>

				<div class="row">
					<div class="col l6 m6 s12">
						<div class="row">
							<div class="col l12 m12 s12">
								<h6><span class="fas fa-industry col_blu"></span> Medicines Category Name</h6>
								<div class="input-container">
									<input type="text" name="category_name" class="asterisk nameError" placeholder="Enter medicine category name" id="input_box" value="<?php if (is_array($med_cat) && ($med_cat[0]->category_name)) {
																									echo $med_cat[0]->category_name;
																								} ?>">
									<span class="asterisk-symbol">*</span>
									<span id="nameError" class="col_red valid_err"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h6><span class="fa fa-image col_blu"></span> Medicines Image</h6>
								<div class="input-container">
									<input type="file" class="imageError" name="category_image" id="input_file" >
									<span id="imageError" class="col_red valid_err_upl"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'category_image'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<br>
					<div class="col l6 m6 s12">
						<div class="row">
							<div class="col l4 m4"></div>
							<div class="col l4 m4 s12">
								<?php if (is_array($med_cat) && ($med_cat[0]->category_name)) { ?>
									<img class="custom_img" src="<?= base_url('public/uploads/medicine_category/' . $med_cat[0]->category_image); ?>">
								<?php } ?>
							</div>
							<div class="col l4 m4"></div>
						</div>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-edit"></span> Edit Category</button>
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

				// //image validation
				// const imageInput = $(".imageError");
				// const imageError = $("#imageError");
				// const MAX_IMAGE_SIZE_KB = 500; 
				// if (imageInput.val().trim() === "") {
				// 	imageError.text("Please Upload Medicines Image");
				// 	valid = false;
				// } else {
				// 	const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
				// 	if (fileSize > MAX_IMAGE_SIZE_KB) {
				// 		imageError.text("Image size should be less than 500KB");
				// 		valid = false;
				// 	} else {
				// 		imageError.text("");
				// 	}
				// }

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
</body>

</html>
