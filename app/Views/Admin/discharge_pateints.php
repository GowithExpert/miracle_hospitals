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
	<title>Discharge Patients</title>
	<?= helper('Form'); ?>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}

		h6 {
			font-size: 15px;
			font-weight: 500
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div style="margin-right: 15px; margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<?php
			if (is_array($patients) && count($patients) > 0) { ?>
				<div class="card-content" style="border-bottom: 1px solid silver;">
					<!-- Rest of the code -->
					<h6 style="font-size: 15px; font-weight: 500">Patient Name : <?= $patients[0]->patient_name; ?></h6>
					<h6>Patient Mobile : <a href="tel:<?= $patients[0]->patient_phone; ?>"><?= $patients[0]->patient_phone; ?></a></h6>
					<h6>Patient Address : <?= $patients[0]->patient_address; ?></h6>
					<!-- Rest of the code -->
				</div>
			<?php
			} else {
				echo "blank else loop";
				die;
				// Handle the case when no patients are found or $patients is not an array
				// For example, you can display an error message or take appropriate action.
			} ?>

			<h6>Release Date : <?= date('d M, Y'); ?></h6>
			<h6>Days Spent :
				<?php
				if (is_array($patients) && count($patients) > 0) {
					$admint_day = $patients[0]->created_at;
					$today = date("Y-m-d");
					$diff = date_diff(date_create($admint_day), date_create($today));
					echo ' ' . $diff->format('%d');
				} else {
					echo ' N/A';
				} // Provide a default value or an error message when $patients is not an array or empty.
				?>
			</h6>
		</div>
	</div>
	</div>
	</div>
	</div>
	<div class="card-content" style="border-bottom: 1px dashed silver">
		<h5 style="font-weight: 500;font-size: 18px;">Disease and Symptoms</h5>
		<?php if (!empty($patients) && is_array($patients)) : ?>
			<h6><?= $patients[0]->patient_issue; ?></h6>
		<?php endif; ?>
	</div>
	<div class="card-content">
		<?php
		if (is_array($patients) && count($patients) > 0) {
			echo form_open('Admin/add_patient_charge/' . $patients[0]->id);
		} else {
			// Handle the case when $patients is not an array or is an empty array
			// Display an error message or take appropriate action.
		}
		?>
		<div class="row">
			<div class="col l6 m12  s12">
				<h5 style="font-weight: 500;font-size: 18px;">Charges</h5>
				<h6 style="margin-bottom:  20px;">Room Charge (Per Day) </h6>
				<h6 style="margin-bottom:  30px;">Doctor Fee</h6>
				<h6 style="margin-bottom:  30px;">Medicine Cost</h6>
				<h6 style="margin-bottom:  30px;">Other Cost</h6>
			</div>
			<div class="col l6 m12  s12">
				<h5 style="font-weight: 500;font-size: 18px;">Price</h5>
				<input type="number" name="room_charge" id="input_box" placeholder="Enter  Room Charge">
				<?php if (isset($validation)) { ?>
					<span style="color: red"><?= display_error($validation, 'room_charge'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>
				<input type="number" name="doc_fee" id="input_box" placeholder="Enter  Doctor Fee">
				<?php if (isset($validation)) { ?>
					<span style="color: red"><?= display_error($validation, 'doc_fee'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>
				<input type="number" name="med_cost" id="input_box" placeholder="Enter Medicine Cost">
				<?php if (isset($validation)) { ?>
					<span style="color: red"><?= display_error($validation, 'med_cost'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>
				<input type="number" name="other_cost" id="input_box" placeholder="Other Cost">
				<?php if (isset($validation)) { ?>
					<span style="color: red"><?= display_error($validation, 'other_cost'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>

			</div>
			<center>
				<button class="btn btn-wavevs-effect waves-light" style="background: black;text-transform: capitalize;font-weight: 500"><span class="fa fa-sign-in-alt"></span> Generate Bill</button>
			</center>
		</div>
		<?= form_close(); ?>
	</div>
	</div>
	</div>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>