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
	<title>Edit Slider Image</title>
	<!---CSS File Include  -->
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<!---CSS File Include  -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="container">

		<?= form_open_multipart("Admin/update_slider_image/" . $slider[0]->id); ?>
		<div class="row">
			<div class="col l7 m7 s12">
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
					<div class="card-content">
						<div class="card-content" id="brdr_botm_silvr">
							<h5 class="dr_detil fnt_sze"><span class="fa fa-edit col_blu"></span>  Edit Front page Slider Image </h5>
							<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
						</div>
						<h6><span class="fa fa-image col_blu"></span> Image Title</h6>
						<div class="input-container">
							<input type="text" name="image_title" class="titleError" id="input_box" placeholder="Enter the image title" value="<?= $slider[0]->image_title; ?>">
							<span class="asterisk-symbol">*</span>
							<span id="titleError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'image_title'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>

						<h6><span class="fa fa-image col_blu"></span> Select Image</h6>
						<div class="input-container">
							<input type="file"class="imageError" name="image_gallery" id="input_file">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'image_gallery'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
						<!--
						<h6><span class="fa fa-link col_blu"></span> Website Link</h6>
						<input type="text" class="readonly_bg" name="website_link" id="input_box" value="<//?= $slider[0]->website_link; ?>">
						-->
						<h6><span class="fa fa-image col_blu"></span> Image Discription</h6>
						<textarea name="image_discription"><?= $slider[0]->image_discription; ?></textarea>
						<div class="row row12">
							<div class="col">
								<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn">Update Slider Image</button>
							</div>
						</div>

						<?= form_close(); ?>
					</div>
				</div>

			</div>
			<div class="col l5 m5 s12">
				<div class="card">
					<div class="card-content">
						<h5 class="h6_gray_colr">Upload Image Guideline</h5>
						<h6 class="col_gry"><span class="fa fa-image col_ornge"></span> File Types <span class="right col_red">JPG-PNG-JPEG</span></h6>
						<h6 class="col_gry"><span class="fa fa-text-width col_ornge"></span> Image Size<span class="right col_red">500px(W) X 250px(H)</span></h6>
						<img src="<?= base_url() . 'public/uploads/frontend/slider/' . $slider[0]->image_gallery; ?>" class="custom_img" id="slider_image" height="50">
					</div>
				</div>
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
				const titleInput = $(".titleError");
				const titleError = $("#titleError");
				if (titleInput.val().trim() === "") {
					titleError.text("Please Enter The Image Title");
					valid = false;
				} else {
					titleError.text("");
				}
				// //UPLOAD IMAGE 

				//image validation
				// const imageInput = $(".imageError");
				// const imageError = $("#imageError");
				// const MAX_IMAGE_SIZE_KB = 500; 
				// if (imageInput.val().trim() === "") {
				// 	imageError.text("Please Upload Slider Image");
				// 	valid = false;
				// } else {
				// 	const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
				// 	if (fileSize > MAX_IMAGE_SIZE_KB) {
				// 		imageError.text("Image size should be less than 500 KB");
				// 		valid = false;
				// 	} else {
				// 		imageError.text("");
				// 	}
				// }
				// const uploadInput = $(".uploadError");
				// const uploadError = $("#uploadError");
				// if (uploadInput.val().trim() === "") {
				// 	uploadError.text("Please Upload The Image");
				// 	valid = false;
				// } else {
				// 	uploadError.text("");
				// }

				

				if (!valid) {
					e.preventDefault();
				}
			});
		});
	</script>

	<!---Body Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
