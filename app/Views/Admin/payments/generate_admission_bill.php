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
	<title>Generate Payment Bill</title>
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

<body onload="window.print();">

	<div style="margin-right: 15px; margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;">
				<div class="row">
					<div class="col l12 m12 s12">
						<div class="row">
							<div class="col l6 m6 s6">
								<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="responsive-img" style="height: 55px;width: 200px;margin-top: 2px;">

								<h6 style="font-size: 15px;font-weight: 500">Patient Name :
									<?php echo $apmt_patient[0]->patient_name; ?>
								</h6>
								<h6>Patient Mobile :
								<?php echo $apmt_patient[0]->patient_mobile; ?>
								</h6>
								<h6>Puid :
								<?php echo $apmt_patient[0]->puid; ?>
								</h6>
								<h6>Doctor Name :
								<?php echo $apmt_patient[0]->doctor_name; ?>
								</h6>
								
							</div>
							<div class="col l6 m6 s6" style="text-align: center;">
								<h6>Admit Date :
									<?= date('d M, Y', strtotime($apmt_patient[0]->booking_date)); ?>
								</h6>
								<h6>Release Date : <?= date('d M, Y'); ?></h6>
								<h6>Days Spent :
									<?php
									$admint_day = $apmt_patient[0]->booking_date;
									$today = date("Y-m-d");
									$diff = date_diff(date_create($admint_day), date_create($today));
									$day_diff_num = $diff->format('%d');
									$get_spent_days = $day_diff_num + 1;
									echo $get_spent_days;
									?>
								</h6>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<h5 style="font-weight: 500;font-size: 18px;">Disease and Symptoms : <span style="color: red"> <?= $apmt_patient[0]->disease_symptoms; ?></span></h5>

			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div class="row">
					<div class="col l6 m6  s6">
						<h5 style="font-weight: 500;font-size: 18px;">Items</h5>
						<h6 style="margin-bottom:  20px;">Admission (Non-Refundable) Fee (INR)
						</h6>
						<h6 style="margin-bottom:  30px;">Other Charges (INR)</h6>

					</div>
					<div class="col l6 m6 s6">
						<h5 style="font-weight: 500;font-size: 18px;">Charges</h5>
						<h6 style="margin-bottom:  20px;">
							
							<?= $payment_bill[0]->registration_fee ?>
						</h6>
						<h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->admission_fee; ?></h6>
						<!-- <//?= isset($payment_bill[0]->other_charges) ? $payment_bill[0]->other_charges : 'N/A'; ?> -->
					</div>
				</div>
			</div>
			<div class="card-content">
				<div class="row">
					<div class="col l6 m6 s6">
						<h6>Total Payable</h6>
					</div>
					<div class="col l6 m6 s6">
						<h6><span class="fa fa-rupee-sign" style="color: green"></span> <?= number_format($payment_bill[0]->paid_amount); ?></h6>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->

</body>

</html>