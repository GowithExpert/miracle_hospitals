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
	<title>View All Doctor</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Patients/patient_css_file.php'); ?>
	<?= view('Frontdesk/img_css_file.php'); ?>
	<!---Include Css File --->
</head>

<body>
	<!---Top Bar Section Include -->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!---Top Bar Section Include -->

	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h6 class="h6_style"><span class="fa fa-user-md dc_pic"></span>  Best Doctor Can Helps</h6>
			</div>
			<div class="card-content back_wite">
				<div class="row">
					<?php if ($view_doctor) :
						count($view_doctor);
						foreach ($view_doctor as $doctor) : ?>
							<div class="col l2 m4 s12">
								<div class="card">
									<div class="card-content">										
										<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $doctor->doctor_name; ?>">
												<?php
												if (isset($doctor->profile_pic) && !empty($doctor->profile_pic)) {
													if (file_exists(FCPATH . 'uploads/doctor/' . $doctor->profile_pic)) { ?>
														<img src="<?= base_url() . 'public/uploads/doctor/' . $doctor->profile_pic; ?>" class="responsive-img" id="profile_pic" height="50">
													<?php  } //Inner if - Closed
													else {  ?>
														<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
													<?php } //Inner else - Closed

												} //Outer if - Closed

												else { ?>
													<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Outer else - Closed  
												?>
											</a>
										</center>

										<h6 class="h6_orange fnt_wight" data-tooltip="<?= $doctor->doctor_name; ?>"><?php if (!isset($doctor->doctor_name) || $doctor->doctor_name == '') {
																												echo 'Unknown';
																											} else {
																												echo word_limiter($doctor->doctor_name, 3);
																											} ?></h6>

										<h6 class="h6_green fnt_wight_lit"><?php if(!isset($doctor->dr_specialization) || $doctor->dr_specialization == ''){
											echo 'Specialization.. NA';
										} else{
											echo word_limiter($doctor->dr_specialization, 10);
										} ?></h6>

										<h6 class="h6_orange fnt_wight_lit"><?php if (!isset($doctor->education) || $doctor->education == '') {
																	echo 'Degree.. NA';
																} else {
																	echo word_limiter($doctor->education, 5);
																} ?></h6>

										<?php if (!isset($doctor->doctor_other_info) || $doctor->doctor_other_info == '') { ?>
											<h6 class="h6_orange fnt_wight_lit" title="<?= 'More Info not available' ?>">
											<?php
											echo 'More Info.. NA';
										} else { ?>
												<h6 class="h6_blue fnt_wight_lit" title="<?php echo $doctor->doctor_other_info; ?>">
													<?php echo word_limiter($doctor->doctor_other_info, 3); ?></h6>
											<?php } ?>
											</h6>
											<center>
												<a href="<?= base_url('Frontdesk/doctors_available_slots/' . $doctor->id); ?>" class="btn btn-waves-effect waves-light fnt_size bok_appoint btn_hver">Book Appointment</a>
											</center>
										
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red">Record's Not Found</h6>
					<?php endif; ?>
					<div class="col l2 m4 s12"></div>
					<div class="col l2 m4 s12"></div>
					<div class="col l2 m4 s12"></div>
					<div class="col l2 m4 s12"></div>
					<div class="col l2 m4 s12"></div>
				</div>
			</div>
		</div>
	</div>


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>