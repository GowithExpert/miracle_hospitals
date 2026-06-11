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
	<title>Revisit Patients Details</title>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include  -->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start --->
	<div class="container">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-users"></span> Revisit Patients Details</h5>
			</div>
			<div class="card-content">
				<?= form_open('Admin/update_revisit_patient/' . $patients[0]->id); ?>

				<div class="row">
					<div class="col l6 m6 s6">
						<h6><span class="fa fa-user" style="color: #005197"></span> Name</h6>
						<input type="text" name="patient_name" id="input_box" value="<?= $patients[0]->patient_name; ?>" />
						<h6><span class="fa fa-phone-square" style="color: #005197"></span>  Phone</h6>
						<input type="number" name="patient_phone" id="input_box" value="<?= $patients[0]->patient_phone; ?>">
						<h6><span class="fa fa-globe" style="color: #005197"></span> Patients Zip Code</h6>
						<input type="text" name="patient_zip" id="input_box" value="<?= $patients[0]->pin_zip_code; ?>">
						<h6><span class="fa fa-users" style="color: #005197"></span> Select Doctors</h6>
						<select name="doctor_name" id="doctor">
							<?php if (count($doctors)) :
								foreach ($doctors as $doc) :
							?>
									<?php if ($patients[0]->doctor_name == $doc->id) : ?>
										<option value="<?= $doc->id; ?>" selected><?= $doc->doctor_name; ?></option>
									<?php else : ?>
										<option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
									<?php endif; ?>
								<?php endforeach;
							else : ?>
								<option value="">Doctor Not Found's</option>
							<?php endif; ?>
						</select>
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span> Entry Fee</h6>
						<input type="text" name="entry_fee" id="input_box" value="<?= $patients[0]->entry_fee; ?>">
					</div>
					<div class="col l6 m6 s6">
						<h6><span class="fa fa-globe" style="color: #005197"></span> Patients Address</h6>
						<input type="text" name="patient_address" id="input_box" value="<?= $patients[0]->patient_address; ?>">
						<h6><span class="fa fa-globe" style="color: #005197"></span> Disease Symptoms</h6>
						<input type="text" name="patient_issue" id="input_box" value="<?= $patients[0]->disease_symptoms; ?>">
						<h6><span class="fa fa-home" style="color: #005197"></span> Patients Room Number</h6>
						<input type="text" name="patient_room" id="input_box" value="<?= $patients[0]->patient_room; ?>">
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span> Doctor Fee</h6>
						<select name="doctor_fee" id="doctor_fee">
							<option value=""></option>
						</select>
						<h6><span class="fa fa-rupee-sign" style="color: #005197"></span> Other Fee</h6>
						<input type="text" name="other_fee" id="input_box" value="<?= $patients[0]->other_fee; ?>">
					</div>
					<h6><span class="fa fa-envelope" style="color: #005197"></span> Patient Email</h6>
					<input type="email" name="patient_email" id="input_box" value="<?= $patients[0]->patient_email; ?>">
					<input type="hidden" name="patient_id" id="input_box" value="<?= $patients[0]->id; ?>">
					<center>
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light" style="text-transform: capitalize;font-weight: 500;font-size: 16px;background: #005197;margin-top: 30px;"><span class="fa fa-user"></span> Revisit Patients Details</button>
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
</body>

</html>