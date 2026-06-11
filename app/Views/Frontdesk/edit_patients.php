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
	<title>Update Patient Detail</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!---Body Section Start --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="h5_align"><span class="fa fa-wheelchair col_blu"></span>  Update Patient Details</h5>
			</div>
			<div class="card-content"> 
				<?= form_open('Frontdesk/update_patients/' . $patients[0]->id); ?>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-user col_blu"></span>  Name</h6>
						<input type="text" name="patient_name" id="input_box" value="<?= $patients[0]->patient_name; ?>" />
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fas fa-mobile-alt col_blu"></span>   Phone</h6>
						<input type="number" name="patient_phone" id="input_box" value="<?= $patients[0]->patient_phone; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-globe col_blu"></span>  Patients Zip Code</h6>
						<input type="text" name="patient_zip" id="input_box" value="<?= $patients[0]->patient_zip; ?>">
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-users col_blu"></span>  Select Doctors</h6>
						<select name="doctor_name" id="doctor" class="selct_gendr">
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
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-rupee-sign col_blu"></span>  Entry Fee</h6>
						<input type="text" name="entry_fee" id="input_box" value="<?= $patients[0]->entry_fee; ?>">
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-globe col_blu"></span>  Patients Address</h6>
						<input type="text" name="patient_address" id="input_box" value="<?= $patients[0]->patient_address; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-globe col_blu"></span>  Patients Issue</h6>
						<input type="text" name="patient_issue" id="input_box" value="<?= $patients[0]->patient_issue; ?>">
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-home col_blu"></span>  Patients Room Number</h6>
						<input type="text" name="patient_room" id="input_box" value="<?= $patients[0]->patient_room; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-rupee-sign col_blu"></span>  Doctor Fee</h6>
						<select name="doctor_fee" id="doctor_fee" class="dis_blk">
							<option value="<?= $patients[0]->doctor_fee; ?>" selected><?= $patients[0]->doctor_fee; ?></option>
						</select>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-rupee-sign col_blu"></span>  Other Fee</h6>
						<input type="text" name="other_fee" id="input_box" value="<?= $patients[0]->other_fee; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-envelope col_blu"></span>  Patient Email</h6>
						<input type="email" name="patient_email" id="input_box" value="<?= $patients[0]->patient_email; ?>">
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-envelope col_blu"></span>  Other Info</h6>
						<input type="text" name="Other_info" id="input_box">
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span>  Edit Patients Details</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	</div>
	<!---Body Section End --->
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>