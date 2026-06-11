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
	<title>Add Department</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="h5_align"><span class="fa fa-edit col_blu"></span>  Edit Department</h5>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Admin/update_department/' . $department[0]->id); ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fas fa-sitemap col_blu"></span> Department Name</h6>
						<div class="input-container">
							<input type="text" name="department_name" class="asterisk nameError" value="<?= $department[0]->department_name; ?>" id="input_box" placeholder="Enter Department Name" >
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'department_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fas fa-sitemap col_blu"></span> Department Description</h6>
						<textarea name="dep_desc" placeholder="Department Description"><?= $department[0]->dep_desc; ?></textarea>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-edit"></span>  Update Department</button>
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
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please enter department name");
					valid = false;
				} else {
					nameError.text("");
				}
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
