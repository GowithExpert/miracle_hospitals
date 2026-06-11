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
	<title>Add News Blog</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!---Topbar Section Include --->

	<!----Body Section Start ---->
	<div class="container">
		<div class="card">
			<!---Php Meassge Show --->
			<div class="mess_stus_msg">
				<?php if (session()->getTempdata('success')) : ?>
					<div class="card success-message cutom_messge_styl">
						<div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
					</div>
				<?php endif; ?>
				<?php if (session()->getTempdata('error')) : ?>
					<div class="card error-message cutom_messge_styl">
						<div class="card-content" id="popup_message"><?= session()->getTempdata('error'); ?></div>
					</div>
				<?php endif; ?>
			</div>

			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="h5_align"><span class="fa fa-upload col_blu"></span>  Upload Your Thinking </h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Doctor/save_blogs'); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blog Title</h6>
						<div class="input-container">
							<input type="text" name="blog_title" class="asterisk titleError" id="input_box" placeholder="Enter Blog Title">
							<span class="asterisk-symbol">*</span>
							<span id="titleError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'blog_title'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blog Image</h6>
						<div class="input-container">
							<input type="file" name="blog_image" class="imageError" id="input_file">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
					</div>
					<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'blog_image'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6>Blog Content</h6>
						<div class="input-container">
							<textarea name="blog_content" class="asterisk contentError" placeholder="Enter Blog Content"></textarea>
							<span class="asterisk-symbol starr">*</span>
							<span id="contentError" class="col_red txt_area_valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'blog_content'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-share"></span>  Upload Blog</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();

				$(".error").text("");
				let valid = true;


				// Name validation
				const titleInput = $(".titleError");
				const titleError = $("#titleError");
				if (titleInput.val().trim() === "") {
					titleError.text("Please Enter The Blog Title");
					valid = false;
				} else {
					titleError.text("");
				}
				// //UPLOAD IMAGE 

				//image validation
				const imageInput = $(".imageError");
				const imageError = $("#imageError");
				const MAX_IMAGE_SIZE_KB = 300; 
				if (imageInput.val().trim() === "") {
					imageError.text("Please Upload Blog Image");
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
				// const uploadInput = $(".uploadError");
				
				const contentInput = $(".contentError");
				const contentError = $("#contentError");
				if (contentInput.val().trim() === "") {
					contentError.text("Please Enter The Blog Content");
					valid = false;
				} else {
					contentError.text("");
				}
				

				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>
	<!----Body Section End ---->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>
