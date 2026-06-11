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
	<title>Terms And Condition</title>
	<!---CSS File Include ---->
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!----Body Section Start --->
	<div class="container">
		<div class="row">
			<div class="col l3 m3"></div>
			<div class="col l6 m6 s12">
				<!---Php Meassge Show --->
				<div class="mess_stus_msg">
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message"><?= session()->getTempdata('error'); ?></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="card">
					<div class="row">
						<div class="col l12 m12 s12">
							<div class="card-content" id="brdr_botm_silvr">
								<h5 class="col_blck"><span class="fa fa-upload col_blu"></span> Terms & Condition</h5>
								<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
							</div>
						</div>
					</div>
					<?= form_open_multipart(); ?>
					<div class="card-content">
						<div class="row">
							<div class="col l12 m12 s12">
							<h6><span class="fa fa-image col_blu"></span> Title</h6>
								<div class="input-container">
									<input type="text" name="title" class="asterisk titleInput" id="input_box" placeholder="Enter Title">
									<span class="asterisk-symbol">*</span>
									<span id="titleError" class="col_red valid_err"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'title'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h6><span class="fa fa-image col_blu"></span> Select Image</h6>
								<div class="input-container">
									<input type="file" name="image" class="imageError" id="input_file" multiple="multiple" >
									<span id="imageError" class="col_red valid_err_upl"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'image[]'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
							<h6><span class="fa fa-image col_blu"></span> Description</h6>
								<div class="input-container">
									<!-- <input type="text" name="title" class="asterisk titleError" id="input_box" placeholder="Enter Title"> -->
									<textarea name="description" id="description" placeholder="Enter description"></textarea>
									<span class="asterisk-symbol" style="top:14px">*</span>
								</div>
								<span id="descriptionError" class="col_red"></span>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'title'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row row12">
							<div class="col">
								<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-image"></span> Terms & Condition</button>
							</div>
						</div>
					</div>
					<?= form_close(); ?>
				</div>
			</div>
			<div class="col l3 m3"></div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			//alert("a");
			$("#btn_register_now").click(function(e) {
				//alert("a");
				//e.preventDefault();
				$(".error").text("");

				//let valid = true;
				// Name validation
				const titleInput = $(".titleInput");
				const titleError = $("#titleError");
				if (titleInput.val().trim() === "") {
					titleError.text("Please Enter Title");
					//valid = false;
					return false;
				} else {
					titleError.text("");
				}
				/*
				//image validation
				const imageInput = $(".imageError");
				const imageError = $("#imageError");
				const MAX_IMAGE_SIZE_KB = 500; 
				if (imageInput.val().trim() === "") {
					imageError.text("Please Upload Slider Image");
					valid = false;
				} else {
					const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
					if (fileSize > MAX_IMAGE_SIZE_KB) {
						imageError.text("Image size should be less than 500 KB");
						valid = false;
					} else {
						imageError.text("");
					}
				}*/

				//image validation
				const imageInput = $(".imageError");
				const imageError = $("#imageError");
				const MAX_IMAGE_SIZE_KB = 500; 
				if (imageInput.val().trim() != "") {
					//imageError.text("Please Upload Slider Image");
					//valid = false;
					const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
					if (fileSize > MAX_IMAGE_SIZE_KB) {
						imageError.text("Image size should be less than 500 KB");
						//valid = false;
						return false;
					} else {
						imageError.text("");
					}
				}

				// Name Description
				const description = $(".description");
				const descriptionError = $("#descriptionError");
				if(!$("#description").val()) {
					$('#descriptionError').text("Please enter desription");
					//valid = false;
					return false;
				}
				//if (description.val().trim() === "") {
					//descriptionError.text("Please Enter The Image Title");
					//valid = false;
				//}
				else {
					descriptionError.text("");
				}
				return true

				//if (!valid) {
				//	e.preventDefault();
				//}
			});
		});
	</script>
	<!----Body Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
