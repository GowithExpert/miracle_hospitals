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
	<title>Print Patient Slip</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->
</head>

<body onload="window.print();">
	<!---Body Section --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<a href="<?= base_url('Admin/home'); ?>" class="brand-logo">  
					<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="img_tag">
				</a>
			</div>
			<div class="card-content">
				<div class="row">
					<!-- <div class="col l2 m2 s12">
						<img src="<//?= base_url() . 'public/uploads/patients/' . $patient_slip[0]->patient_image; ?>" class="responsive-img" id="patient_image" height="50">
					</div> -->
					<div class="col l12 m12 s12">
						<div class="row">
							<div class="col l8 m8 s8">
								<h6>Patient Name : <span class="col_gren"><?= $patient_slip[0]->patient_name; ?></span></h6>
								<h6>Date : <span class="col_gren"><?= date('d M, Y', strtotime($patient_slip[0]->created_at)); ?></span></h6>
								<h6>Patient Phone: <?= $patient_slip[0]->patient_phone; ?></h6>
								<h6>Patient Address: <?= $patient_slip[0]->patient_address; ?></h6>
								<h6>Patient Zip : <span><?= $patient_slip[0]->patient_zip; ?></span></h6>
							</div>
							<div class="col l4 m4 s4">
								<h6>Patient ID : <span class="col_gren"><?= $patient_slip[0]->puid; ?></span></h6>
								<h6>Doctor Name : <span>
										<?php
										$get_doctor =  get_doctor_name('doctor', $patient_slip[0]->doctor_id);
										if (!empty($get_doctor)) {
											echo $get_doctor[0]->doctor_name;
										}
										?>
									</span>

								</h6>
								<h6>
									Doctor Fee : <?= $patient_slip[0]->doctor_fee; ?>
								</h6>
								<h6>
									Other Fee : <?= $patient_slip[0]->other_fee; ?>
								</h6>
								<h6>
									Entry Fee : <?= $patient_slip[0]->entry_fee; ?>
								</h6>
							</div>
						</div>
					</div>
				</div>
				<div class="card-content" id="brdr_botm_silvr">
					<h6>Patient Issue : <?= $patient_slip[0]->patient_issue; ?> </h6>
					<h6>Patient Room Number : <?= $patient_slip[0]->patient_room; ?> </h6>
				</div>
				<div class="row">
					<div class="col l6 m6 s6">
						<h6 class="h6_sty">Accountant Signature</h6>
					</div>
					<div class="col l6 m6 s6">
						<h6></h6>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!---Body Section --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>