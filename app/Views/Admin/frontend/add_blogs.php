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
	<title>Upload Your thinking</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---Include Css File --->
	<?= view('Home/css_file'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!---Topbar Section Include --->

	<!----Body Section Start ---->
	<div class="container">
		<div class="card">
			<!---Php Meassge Show --->
			<div class="mess_stus_msg">
				<?php if (session()->getTempdata('success')) : ?>
					<div class="card success-message cutom_messge_styl">
					<div class="card-content" id="popup_message">
						<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
					</div>
					</div>
				<?php endif; ?>
				<?php if (session()->getTempdata('error')) : ?>
					<div class="card error-message cutom_messge_styl">
					<div class="card-content" id="popup_message">
						<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
					</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 10px;">
				<h5 style="font-weight: 600;margin-top: 5px; font-size: 20px;"><span class="fa fa-upload" style="color: #005197"></span>  Upload Your Thinking </h5>
				<p style="text-align: center;font-size: 14px">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<?= form_open_multipart('Doctor/save_blogs'); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blog Title</h6>
						<div class="input-container">
							<input type="text" name="blog_title" class="asterisk" id="input_box" placeholder="Enter Blog Title">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'blog_title'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blog Image</h6>
						<input type="file" name="blog_image" id="input_file" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6>Blog Content</h6>
						<div class="input-container">
							<textarea name="blog_content" class="asterisk" placeholder="Enter Blog Content"></textarea>
							<span class="asterisk-symbol starr">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'blog_content'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<center>
					<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver" style="text-transform: capitalize;font-weight: 500;font-size: 16px;background: #005197; margin-top: 20px;"><span class="fa fa-share"></span>  Upload Blog</button>
				</center>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<!----Body Section End ---->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>