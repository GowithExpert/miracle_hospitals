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
	<title>Add Patients</title>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	
	<style>
		.btn_hver:hover {
			color: #fff;
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card" style="box-shadow: none;">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg">
						<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
					</div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg">
						<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
					</div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<h5><span class="fa fa-wheelchair" style="color: #005197"></span>  Add Patients</h5>
				<p style="text-align: center;">Fill out all required Field (<span class="star"><sub>*</sub></span>) to add a Patient. Please don't spam,Thank you!</p>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Medical_Accountant/upload_patients'); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user" style="color: #005197"></span>  Patient Name</h6>
						<div class="input-container">
							<input type="text" name="patient_name" class="asterisk" id="input_box" placeholder="Enter Patient Name">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-phone-square" style="color: #005197"></span>   Mobile Number</h6>
						<div class="input-container">
							<input type="tel" name="patient_phone" class="asterisk phone-input" id="input_box" maxlength="10" placeholder="Enter Mobile Number" oninput="validateMobile(this)" value="<?= set_value('patient_phone'); ?>">

						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" style="margin-top: 10px;"></div>

						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_phone'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe" style="color: #005197"></span>  Patient Age</h6>
						<div class="input-container">
							<input type="text" name="patient_age" class="asterisk" id="input_box" placeholder="Enter Patient Age" maxlength="40" value="<?= set_value('patient_age'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_age'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-users" style="color: #005197"></span>  Select Gender</h6>
						<select name="patient_gender" id="patient_gender">
							<option selected="" disabled="">Select Patient Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
							<option value="Other">Other</option>
						</select>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_gender'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe" style="color: #005197"></span>  Disease Symptoms</h6>
						<div class="input-container">
							<input type="text" name="patient_issue" class="asterisk" id="input_box" placeholder="Enter Patient Disease Symptoms" value="<?= set_value('patient_issue'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_issue'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-home" style="color: #005197"></span>  Disease Detail</h6>
						<input type="text" name="other_info" id="input_box" placeholder="Enter Disease Detail" maxlength="" value="<?= set_value('other_info'); ?>">
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'other_info'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6">
						<h6><span class="fa fa-envelope" style="color: #005197"></span>  Patient Email</h6>
						<input type="email" name="patient_email" maxlength="40" id="input_box" placeholder="Enter Patient Email ID" value="<?= set_value('patient_email'); ?>">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-camera" style="color: #005197"></span>  Upload Patient Photo</h6>
						<input type="file" name="patient_image" class="asterisk" required="" id="input_file" value="<?= set_value('patient_image'); ?>">
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_image'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe" style="color: #005197"></span>  Ward Name</h6>
						<div class="input-container">
							<input type="text" name="ward_name" class="asterisk" id="input_box" placeholder="Enter Patient Ward Name" value="<?= set_value('ward_name'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'ward_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-home" style="color: #005197"></span>  Patient Room Number</h6>
						<div class="input-container">
							<input type="text" name="patient_room" class="asterisk" id="input_box" placeholder="Enter Patient Room Number" maxlength="" value="<?= set_value('patient_room'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_room'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe" style="color: #005197"></span>  Patient Address</h6>
						<div class="input-container">
							<input type="text" name="patient_address" class="asterisk" id="input_box" placeholder="Enter Patient Address" value="<?= set_value('patient_address'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_address'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe" style="color: #005197"></span>  Pincode</h6>
						<div class="input-container">
							<input type="text" name="patient_zip" class="asterisk" id="input_box" placeholder="Enter Pincode" maxlength="10" size="10" value="<?= set_value('patient_zip'); ?>">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'patient_zip'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">

					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-users" style="color: #005197"></span>  Select Doctor</h6>
						<select name="doctor_name" id="doctor">
							<option selected="" disabled="">Select Doctor</option>
							<?php if ($doctors) :
								count($doctors);
								foreach ($doctors as $doc) : ?>
									<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
								<?php endforeach; ?>
							<?php else : ?>
								<h6 style="color: red">Doctor Not Found</h6>
							<?php endif; ?>
						</select>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'doctor_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span>  Doctor Fee</h6>
						<select name="doctor_fee" id="doctor_fee">
							<option selected="" disabled="">Select Doctor Fee</option>
							<option value=""></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span>  Entry Fee</h6>
						<div class="input-container">
							<input type="text" name="entry_fee" class="asterisk" id="input_box" placeholder="Entry Fee">
							<span class="asterisk-symbol">*</span>
						</div>
						
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span>  Other Fee</h6>
						<input type="text" name="other_fee" id="input_box" placeholder="Other Fee">
					</div>

				</div>
			</div>

			
			<input type="hidden" name="doc_name" id="doc_name" value="">
			<center>
				<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver" style="text-transform: capitalize;font-weight: 500;font-size: 16px;bottom: 15px;background: #005197;margin: 1rem;"><span class="fa fa-user"></span>  Add Patient Details</button>
			</center>
			<?= form_close(); ?>
		</div>
	</div>
	</div>
	<script>
		$.noConflict(); // Relinquish control of the $ symbol to other libraries
		//jQuery.noConflict();
		jQuery(function($) {
			$(document).ready(function() {
				console.log("This is testing phase");
				var dr_name = '';
				$('#doctor').change(function() { //Call on Doctor selection from dropdown
					var selected_dr_id = $(this).val(); //Selected Doctor
					if (selected_dr_id) {
						var dr_name = $('#doctor option:selected').text(); //Get selected Dr. name
						$('#doc_name').val(dr_name);
						console.log(dr_name);
						return dr_name;
					}
				}); //Closed - Function 

					///SUCCESS MESSAGE JS /////
	setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('#suces_msg');
			var errMsg = document.querySelectorAll('#eror_msg');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds

		///SUCCESS MESSAGE JS /////
			}); //Closed ready function 
		});
	</script>
	<!---Body Section Start -->


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/country_code_js_file.php'); ?>

</body>

</html>