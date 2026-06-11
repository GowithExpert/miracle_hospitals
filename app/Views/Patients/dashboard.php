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
<!--Frontdesk Dashboard  -->

<!DOCTYPE html>
<html>

<head>
	<title>Patient dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<!-- Font awesome css file-->
	<?= view('Patients/patient_css_file.php'); ?>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
	<!---Top Bar Section Include -->
	<?= view('Patients/top_bar'); ?>
	<!---Top Bar Section Include -->
	<!---Body Section Start --->
	<div class="row">
		<div class="col l4 m4 s12">
			<div class="card">
				<!---Php Meassge Show --->
				<div>
					<?php
					if (session()->getTempdata('success')) {
						?>
						<div class="card success cutom_messge_styl bckgrnd_gren">
							<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
						</div>
						<?php
						session()->removeTempdata('success');
					}
					
					if (session()->getTempdata('error')) {
						?>
						<div class="card error cutom_messge_styl bckgrnd_red">
							<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
						</div>
						<?php
						session()->removeTempdata('error');
					}
					?>
				</div>
				<!---Php Meassge Show --->
				<div class="card-content" id="patnt_heder">
					<h6 class="al_patient"><span class="fa fa-user-md"></span>  Best Doctor Can Helps</h6>
				</div>
				<div class="card-content">
					<center>
						<img src="<?= base_url('public/assets/images/patients_dashboard.jpg') ?>" id="dashboard_default">
					</center>
				</div>
				<div class="card-content">
					<p class="paratext">Medical professionals, commonly referred to as doctors, often possess specialized expertise in specific fields of medicine. The healthcare industry comprises hundreds of medical specialties and sub-specialties, each dedicated to diagnosing, treating, and managing various health conditions. Below are some of the most common types of medical specialists you may encounter</p>
				</div>

			</div>
		</div>
		<div class="col l4 m4 s12">
			<div class="card">
				<div class="card-content" id="dr_heder">
					<h6 class="al_patient">
						<span class="fa fa-calendar"></span>
						  
						<a href="<?= base_url('Patients/doctors_available_slots/') . session()->get('patient_session_id') ?>" class="col_wite">
							Your Doctor Appointments
						</a>
					</h6>
				</div>
				<div class="card-content">
					<center>
						<?php if (isset($doctor_details[0]->doctor_name)) { ?>
							<a class="tooltipped" data-position="top" data-tooltip="<?= $doctor_details[0]->doctor_name; ?>">
							<?php } ?>
							<?php if (isset($doctor_details[0]->profile_pic)) { ?>
								<img src="<?= base_url() . 'public/uploads/doctor/' . $doctor_details[0]->profile_pic; ?>" class="responsive-img" id="doc_img">
							<?php } ?>
							</a>
					</center>
					<?php if (isset($doctor_details[0]->doctor_name)) { ?>
						<h6 class="p_content col_blu"><span class="fa fa-user-md col_blu"></span>  <?= $doctor_details[0]->doctor_name; ?>
						</h6>
					<?php } ?>
					<hr>
					<div class="row">
						<div class="col l6 m6 s6">
							<h6 class="col_blu"><span class="fa fa-user-md"></span> Speciality:
							<?php if (isset($doctor_details[0]->dr_specialization)) { ?>
								<span class="h6_gren_colr"> <?= $doctor_details[0]->dr_specialization; ?></span>
							<?php } ?>
							</h6>
						</div>
						<div class="col l6 m6 s6">
							<h6 class="col_blu"><span class="fa fa-user-md"></span> Degree:
							<?php if (isset($doctor_details[0]->education)) { ?>
								<span class="h6_gren_colr"><?= $doctor_details[0]->education; ?></span>
							<?php } ?>
							</h6>
						</div>
					</div>
					<div class="flot_red">
						<a class="btn_sty" href="<?= base_url('Patients/doctors_available_slots/') . session()->get('patient_session_id') ?>"><span class="fa fa-calendar"></span> Book Appointment</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col l4 m4 s12">
			<div class="row">
				<div class="col l6 m6 s12">
					<div class="row">
						<div class="card">
							<div class="card-content" id="dr_heder">
								<h6 class="al_patient"><span class="fa fa-user"></span>  Doctor Specialist</h6>
							</div>
							<div class="card-content">
								<?php if (isset($doctor_details[0]->dr_specialization)) { ?>
									<h6 class="col_lgt_blu"><span class="fa fa-check"></span>   <?= $doctor_details[0]->dr_specialization; ?>
									</h6>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col l6 m6 s12">
					<div class="card">
						<div class="card-content" id="dr_heder">
							<h6 class="al_patient"><span class="fa fa-phone"></span>  Doctor Contact Number</h6>
						</div>
						<div class="card-content">
							<?php if (isset($doctor_details[0]->doctor_phone)) { ?>
								<a href="tel:<?= $doctor_details[0]->doctor_phone; ?>" class="font_weght link_hver">
									<span class="fa fa-phone"></span>  <?= $doctor_details[0]->doctor_phone; ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col l12 m12 s12">
					<div class="card">
						<div class="card-content" id="dr_heder">
							<h6 class="al_patient"><span class="fa fa-calendar-check-o"></span>  Doctor Event</h6>
						</div>
						<div class="card-content">
							<img src="<?= base_url('public/assets/images/doctor_days_calander.png') ?>" class="wdth">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$("#btn_sign_in").click(function(e) {
				$(".error").text("");
				let valid = true;

				if (!valid) {
					e.preventDefault();
				}
			});
			setTimeout(function () {
	            $(".success, .error").fadeOut(500, function () {
	                $(this).remove();
	            });
	        }, 5000);
		});
		// Add JavaScript to hide messages with the specified classes after 5 seconds
		setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('.success-message');
			var errMsg = document.querySelectorAll('.error-message');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds
	</script>
	<!---Body Section Start --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->

</body>
</html>