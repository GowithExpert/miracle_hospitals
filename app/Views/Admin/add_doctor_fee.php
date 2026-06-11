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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<title>Add Doctor Fee</title>
	<?= helper('Form'); ?>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<!---CSS File Include  -->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}

		h6 {
			font-weight: 600 !important;
			font-size: 14px;
		}

		select {
			display: block;
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		select {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		.input-container {
			position: relative;
		}

		.asterisk-symbol {
			position: absolute;
			top: 36%;
			left: 6px;
			transform: translateY(-50%);
			color: red;
			visibility: visible;
			opacity: 1;
			transition: visibility 0s, opacity 0.2s;
		}

		.asterisk-hidden .asterisk-symbol {
			visibility: hidden;
			opacity: 0;
		}

		.btn_hver:hover {
			color: #fff;
		}
		*::-webkit-scrollbar-thumb {
			background-color: #005197;
		}

	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start -->
	<div class="container">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<h5 style="font-weight: 600;margin-top: 5px; font-size: 20px;"><span class="fa fa-user-md col_blu"></span> Add Doctor's Fee</h5>
			</div>
			<div class="card-content">
				<? //= form_open('Admin/upload_doctor_fee');?>	
				<?//php echo "<pre>"; print_r($doctors);die;
				if (!isset($doctors[0]->id) || $doctors[0]->id == '') {
					echo form_open('Admin/manage_doctor_fee');
				} else {
					$url = 'Admin/update_doctor_fee/' . $doctors[0]->id;
					echo form_open($url);
				} ?>

				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user-md col_blu"></span> Doctor Name</h6>
						<select name="doctor_name" id="doctor_name">
							<option selected="" disabled="">Select Doctor Name</option>
							<?php if (isset($doctors)) :
								count($doctors);
								foreach ($doctors as $doc) : ?>
									<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
								<?php endforeach;
							else : ?>
								<h6 class="col_red">Doctor Not Found</h6>
							<?php endif; ?>
						</select>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'doctor_name'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user-md col_blu"></span> Doctor Fee</h6>
						<div class="input-container">
							<input type="number" name="doctor_fee" class="asterisk" id="input_box" placeholder="Add Doctor Fee" min="0" max="100000000" required="required">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'doctor_fee'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>

				</div>

				<br>
				<center>
					<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver" style="text-transform: capitalize;font-weight: 500;font-size: 16px;background: #005197"><span class="fa fa-user"></span> Add Doctor Fee</button>
				</center>
				<?= form_close(); ?>

			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#input_box').change(function() { //Call on Doctor selection from dropdown
				var doctor_fee = '';
				selected_dr_id = $(this).val(); //Selected Doctor
				if (selected_dr_id) {
					doctor_fee = $('#input_box option:selected').text(); //Get selected Dr. name
					console.log(doctor_fee);
					return doctor_fee;
				}
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