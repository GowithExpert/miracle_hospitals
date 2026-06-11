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
	<title>Edit revisit_patients Details</title>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include  -->
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-wheelchair col_blu"></span> Edit revisit_patients Details</h5>
			</div>
			<div class="card-content"> 
				<?= form_open('Doctor/update_revisit_patients/'. $revisit_patients[0]->pid, array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-user col_blu"></span> Name</h6>
						<input type="text" name="patient_name" id="input_box" value="<?= $revisit_patients[0]->patient_name; ?>" />
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<div class="input-container">
							<h6><span class="fa fa-phone-square col_blu"></span> Mobile</h6>
							<input type="tel" name="patient_phone" class="phone-input phone_mandatory" id="input_box" maxlength="10" value="<?= $revisit_patients[0]->patient_phone; ?>">
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
					</div>
				</div>
				<input type="hidden" id="country_code" name="country_code" value="">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> revisit_patients Zip Code</h6>
						<input type="text" name="pin_zip_code" id="input_box" value="<?= $revisit_patients[0]->pin_zip_code; ?>">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-users col_blu"></span> Select Doctors</h6>
						<select name="doctor_name" id="doctor">
							<?php if ($doctors) :
								foreach ($doctors as $doc) :
							?>
									<?php if ($revisit_patients[0]->doctor_name == $doc->id) : ?>
										<option value="<?= $doc->id; ?>" selected><?= $doc->doctor_name; ?></option>
									<?php else : ?>
										<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
									<?php endif; ?>
								<?php endforeach;
							else : ?>
								<option value="">Doctor Not Found's</option>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Entry Fee</h6>
						<input type="text" name="entry_fee" id="input_box" value="<?= $revisit_patients[0]->entry_fee; ?>">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> revisit_patients Address</h6>
						<input type="text" name="patient_address" id="input_box" value="<?= $revisit_patients[0]->patient_address; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-globe col_blu"></span> revisit_patients Issue</h6>
						<input type="text" name="disease_symptoms" id="input_box" value="<?= $revisit_patients[0]->disease_symptoms; ?>">
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-home col_blu"></span> revisit_patients Room Number</h6>
						<input type="text" name="patient_room" id="input_box" value="<?= $revisit_patients[0]->patient_room; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Doctor Fee</h6>
						<select name="doctor_fee" id="doctor_fee">
							<option value="<?= $revisit_patients[0]->doctor_fee; ?>" selected><?= $revisit_patients[0]->doctor_fee; ?></option>
						</select>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee-sign col_blu"></span> Other Fee</h6>
						<input type="text" name="other_fee" id="input_box" value="<?= $revisit_patients[0]->other_fee; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Patient Email</h6>
						<input type="email" name="patient_email" id="input_box" value="<?= $revisit_patients[0]->patient_email; ?>">
					</div>
				</div>
				<center>
					<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Edit revisit_patients Details</button>
				</center>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	</div>
	<!---Body Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/country_code_js_file.php'); ?>
<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>
</body>

</html>