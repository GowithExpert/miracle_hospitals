<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
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
Date: 21st August, 2023 
-->
<!DOCTYPE html>
<html>
<head>
	<title>Update Doctor Fee</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-rupee fa_icon1"></span> Update Doctor Fee</h5>
			</div>
			<div class="card-content">
				<?= form_open('Admin/update_doctor_fee/' . $update_doctor_fee[0]->id); ?>
				<div class="row">
					<div class="col l12 m12 s12">
						<h6><span class="fa fa-user-md col_blu"></span> Doctor Name</h6>
						<input type="text" name="doctor_name" id="input_box" class="readonly_bg" readonly value="<?= $update_doctor_fee[0]->doctor_name; ?>" required>
					</div>
				</div>
				<div class="row">
					<div class="col l12 m12 s12">
						<h6><span class="fa fa-rupee col_blu"></span> Doctor Fee</h6>
						<input type="text" name="doctor_fee" class="remove" id="input_box" maxlength="5" value="<?= $update_doctor_fee[0]->doctor_fee; ?>" required>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Update Doctor Fee</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
	$(document).ready(function () {
		$(".remove").on({
			focus: function() {
				// Remove '0' placeholder when the input box is clicked
				if ($(this).val() === '0') {
					$(this).val('');
				}
			},
		});
	});
	</script>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>