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
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->

</head>

<body onload="window.print();">
	<!---Body Section --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<a href="<?= base_url('Blood_bank/index'); ?>" class="brand-logo">  
					<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="img_tag">
				</a>
			</div>
			<div class="card-content">
				<div class="row" id="brdr_botm_silvr">
					<div class="col l12 m12 s12">
						<h6>Name : <span class="col_gren"><?= $blood_slip[0]->username; ?></span>
						</h6>
						<h6>Date : <span class="col_gren"><?= date('d M, Y, h:i:s', strtotime($blood_slip[0]->created_at)); ?></span></h6>
						<h6> Phone: <?= $blood_slip[0]->mobile; ?></h6>
						<h6> Address: <?= $blood_slip[0]->address; ?></h6>
						<h6> Email : <?= $blood_slip[0]->email; ?></h6>
					</div>
					<!-- <div class="col l4 m4 s4">
						<img src="<//?= base_url() . 'public/uploads/hospitalblood_cus/' . $blood_slip[0]->photo; ?>" class="responsive-img" id="patient_image" height="50">
					</div> -->
				</div>
				<table class="table">
					<tr>
						<th>Blood Group</th>
						<th>Blood Unit</th>
						<th>Blood 1 Unit Price</th>
						<th>Total Blood Price</th>
					</tr>
					<?php
					$get_blood_name = get_doctor_name('blood_group', $blood_slip[0]->blood_id);
					?>
					<tr>
						<td>
							<span class="col_red">  <?= $get_blood_name[0]->blood_group; ?></span>
						</td>
						<td>
							<?= $blood_slip[0]->blood_unit; ?>
						</td>
						<td class="col_red">
							<span class="fa fa-rupee-sign"></span><?= number_format($blood_slip[0]->blood_price); ?>
						</td>
						<td class="col_gren">
							<?php
							$total_bld_prc = ((int)$blood_slip[0]->blood_price * (int) $blood_slip[0]->blood_unit);
							?>
							<span class="fa fa-rupee-sign"></span><?= number_format($total_bld_prc); ?>
						</td>
					</tr>
				</table>

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