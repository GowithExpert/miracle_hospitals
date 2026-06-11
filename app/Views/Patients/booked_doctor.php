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
	<title>Booked Doctor Appointment</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<style type="text/css">
		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
			color: black
		}

		h6 {
			font-weight: 500;
			font-size: 15px;
		}
	</style>
</head>

<body>
	<!---Top Bar Section Include -->
	<?= view('Patients/top_bar'); ?>
	<!---Top Bar Section Include -->
	<div class="container">
		<div class="card" style="background: green">
			<div class="card-content" style="background: green;padding: 10px;border-bottom: 1px dashed silver">
				<h6 style="color: white;font-weight: 500;text-align: center;"><span class="fa fa-users"></span>  Booked Doctor Appointment</h6>
			</div>
			<div class="card-content" style="background: white">
				<div class="container">
					<?= form_open('Patients/booked_doctor_appointment'); ?>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Patient Name</h6>
							<input type="text" name="patient_name" value="<?= set_value('patient_name') ?>" id="input_box" placeholder="Enter Patient Name">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'patient_name'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Patient Symptoms</h6>
							<input type="text" name="patient_issue" value="<?= set_value('patient_issue') ?>" id="input_box" placeholder="Enter Patients Symptoms">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'patient_issue'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Email</h6>
							<input type="text" name="patient_email" value="<?= set_value('patient_email') ?>" id="input_box" placeholder="Enter Patients Email">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'patient_email'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Mobile Number</h6>
							<input type="text" name="patient_mobile" value="<?= set_value('patient_mobile') ?>" id="input_box" placeholder="Enter Patients Mobile">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'patient_mobile'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Select Booking Date</h6>
							<input type="date" name="booking_date" value="<?= set_value('booking_date') ?>" id="input_box">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'booking_date'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6>Type Booking Time</h6>
							<input type="time" name="booking_time" value="<?= set_value('booking_time') ?>" id="input_box" style="color: black">
							<?php if (isset($validation)) { ?>
								<span style="color: red"><?= display_error($validation, 'booking_time'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<input type="hidden" name="doctor_id" id="input_box" value="<?= $doctor_id[0]->id; ?>">
					<input type="hidden" name="doctor_name" id="input_box" value="<?= $doctor_id[0]->doctor_name; ?>">
					<center>
						<button type="submit" class="btn btn-waves-effect waves-light" style="background: green;font-weight: 500;text-transform: capitalize;">Booked Now</button>
					</center>
					<?= form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>