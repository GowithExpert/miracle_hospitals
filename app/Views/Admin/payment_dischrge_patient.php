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
<?php
$get_patient =  get_doctor_name('patients', $payment_bill[0]->id);

?>
<!DOCTYPE html>
<html>

<head>
	<title>Discharge Patient Report </title>
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
	<!---Top Bar Section Include -->
	<?= view('Admin/top_bar'); ?>
	<!---Top Bar Section Include -->
	<div class="container">
		<div class="card" style="background: green;padding: 0px;">
			<div class="card-content" style="padding: 0px;margin-left: 10px;border-bottom: 1px dashed silver">
				<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="responsive-img" style="height: 55px;width: 200px;margin-top: 2px;">
			</div>
		</div>
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div class="row">
				
				<?php if ($get_patient !== false && isset($get_patient[0])): ?>
					<div class="col l6 m6 s6">
						<h6>Patient Name : <?= $get_patient[0]->patient_name; ?></h6>
						<h6>Patient Mobile : <a href="tel:<?= $get_patient[0]->patient_phone; ?>"><?= $get_patient[0]->patient_phone; ?></a> </h6>
						<h6>Patient Email : <a href="mailto:<?= $get_patient[0]->patient_email; ?>">
								<?= $get_patient[0]->patient_email; ?></a>
						</h6>
						<h6>Patient Address : <?= $get_patient[0]->patient_address; ?></h6>

					</div>
					<div class="col l6 m6 s6">
						<h6>Admit Date : <?= date('d M, Y', strtotime($get_patient[0]->created_at)); ?></h6>
						<h6>Release Date : <?= date('d M, Y'); ?></h6>
						<h6>Days Spent :
							<?php
							$admint_day = $get_patient[0]->created_at;
							$today = date("Y-m-d");
							$diff = date_diff(date_create($admint_day), date_create($today));
							$get_spent_days = $diff->format('%d');
							echo $get_spent_days;
							?>
						</h6>
					</div>
				</div>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<h6 style="color: red;font-weight: 500;font-size: 18px;">Disease and Symptoms :
					<?= $get_patient[0]->patient_issue; ?> </h6>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div class="row">
					<div class="col l6 m6  s6">
						<h5 style="font-weight: 500;font-size: 18px;">Charges</h5>
						<h6 style="margin-bottom:  20px;">Room Charge
							<span class="fa fa-rupee-sign" style="color: green;"></span>
							<?= $payment_bill[0]->room_charge ?> (Per Day)
						</h6>
						<h6 style="margin-bottom:  30px;">Doctor Fee</h6>
						<h6 style="margin-bottom:  30px;">Medicine Cost</h6>
						<h6 style="margin-bottom:  30px;">Other Cost</h6>

					</div>
					<div class="col l6 m6 s6">
						<h5 style="font-weight: 500;font-size: 18px;">Charges</h5>
						<h6 style="margin-bottom:  20px;">
							<?php
							$count_room_bill = ($payment_bill[0]->room_charge * $get_spent_days);
							echo number_format($count_room_bill);
							?>
						</h6>
						<h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->doc_fee; ?></h6>
						<h6 style="margin-bottom:  30px;"><?= number_format($payment_bill[0]->med_cost); ?></h6>
						<h6 style="margin-bottom:  30px;"><?= $payment_bill[0]->other_cost; ?></h6>
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
						$count_total_amount = ($count_room_bill + $payment_bill[0]->doc_fee + $payment_bill[0]->med_cost + $payment_bill[0]->other_cost);

						?>
						<h6><span class="fa fa-rupee-sign" style="color: green"></span> 
							<?= number_format($count_total_amount); ?></h6>
						<?php else: ?>
							<p>Error: Patient data not found</p>
					<?php endif; ?>
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