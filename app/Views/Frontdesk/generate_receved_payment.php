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
		
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
	</style>
</head>

<body onload="window.print();">

	<div style="margin-right: 15px; margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px dashed silver;">
				<div class="row">
					<div class="col l12 m12 s12">
						<div class="row">
							<div class="col l3 m3 s3">
								<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="responsive-img" style="height: 55px;width: 200px;margin-top: 2px;">
							</div>
							<div class="col l9 m9 s9">
								<h5 style="font-weight: 500;font-size: 18px;"> <?php echo HOSPITAL_NAME;  ?></h5>
							</div>
						</div>
						<div class="row">
							<div class="col l6 m6 s6">
								<h6 style="font-size: 15px;font-weight: 500">Patient Name :
									<?php echo $get_patient[0]->patient_name; ?>
								</h6>
								<h6>Patient Mobile :
								<?php echo $get_patient[0]->patient_mobile; ?>
								</h6>
								<h6>Puid :
								<?php echo $get_patient[0]->puid; ?>
								</h6>
							</div>
							<div class="col l2 m2 s2">
							</div>
							<div class="col l4 m4 s4">
								<!--
								<h6>Admit Date :
									<?//= date('d M, Y', strtotime($get_patient[0]->booking_date)); ?>
								</h6>
								-->
								<h6>Date :
									<?= date('d-M-Y'); ?> at:
									<?= date('h:i:s'); ?>
									
								</h6>
								<h6>Doctor Name :
								<?php echo $get_patient[0]->doctor_name; ?>
								</h6>
								<!--
								<h6>Release Date : <?//= date('d M, Y'); ?></h6>
								-->
								<!--
								<h6>Days Spent :
									<?php 
									$admint_day = $get_patient[0]->booking_date;
									$today = date("Y-m-d");
									//$previous_day = date("Y-m-d", strtotime(' - 1 day'));
									$diff = date_diff(date_create($admint_day), date_create($today));
									$day_diff_num = $diff->format('%d');
									$get_spent_days = $day_diff_num + 1;
									//echo $get_spent_days; 
									?>
								</h6>
								-->
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="card-content" >
				<h5 style="font-weight: 500;font-size: 18px;">Disease and Symptoms : <span style="color: red"> <?= $get_patient[0]->disease_symptoms; ?></span></h5>

			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div class="row">
					<div class="col l6 m6  s6">
						<h5 style="font-weight: 500;font-size: 18px;">Items</h5>
                        <!--
						<h6 style="margin-bottom:  20px;">Room Charge
							<span class="fa fa-rupee-sign" style="color: green;"></span>
							<//?= $payment_bill[0]->room_charge ?> (Per Day)
						</h6>-->
						<h6 style="margin-bottom:  30px;">Receved Payment</h6>
						<h6 style="margin-bottom:  30px;">Pay Mode</h6>
						<h6 style="margin-bottom:  30px;">Transaction ID</h6>
                        <h6 style="margin-bottom:  30px;"> Payment Date</h6>
					</div>
					<div class="col l6 m6 s6">
						<h5 style="font-weight: 500;font-size: 18px;">Charges</h5>
                        <!--
						<h6 style="margin-bottom:  20px;">
							<?php
							$count_room_bill = ($payment_bill[0]->room_charge * $get_spent_days);
							//echo number_format($count_room_bill);
							?>
						</h6>-->
						<!--
						<h6 style="margin-bottom:  30px;"><?//= $payment_bill[0]->other_cost; ?>
						--></h6>
						<h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->paid_amount; ?>
                        <h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->pay_mode; ?></h6>
						<h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->transaction_id; ?></h6>
                        <h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->pay_date; ?></h6>
                        <!--
						<h6 style="margin-bottom:  30px;"><//?= number_format($payment_bill[0]->med_cost); ?></h6>
						<h6 style="margin-bottom:  30px;"><//?= $payment_bill[0]->other_cost; ?></h6>-->
					</div>
				</div>
			</div>
			<div class="card-content">
				<div class="row">
					<div class="col l6 m6 s6">
						<h6>Total Amount</h6>
					</div>
					<div class="col l6 m6 s6">
						<?php
						$count_total_amount = ($count_room_bill + $payment_bill[0]->doc_fee + $payment_bill[0]->med_cost + $payment_bill[0]->other_cost+ $payment_bill[0]->paid_amount);

						?>
						<h6><span class="fa fa-rupee-sign" style="color: green"></span> <?= number_format($count_total_amount); ?></h6>
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