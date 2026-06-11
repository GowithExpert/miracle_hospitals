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
	<title>Number of Visiting Patients</title>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->
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
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-users" style="color: #005197"></span> Number of Visiting Patients</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12">
						<?php if ($pat_visiter) :
							$count =  count($pat_visiter);
							//echo "<pre>";print_r($pat_visiter);die; 
						?>

							<h6 style="text-align: center;font-weight: 500;">
								Number of Visiting : <span class="fa fa-eye" style="color:green"> <?= $count; ?></span>
							</h6>
						<?php else : ?>
							<h6 style="color: red">Not Visiting</h6>
						<?php endif; ?>
					</div>
					<div class="col l6 m6 s12"></div>
					<!--Search Bar & Filter Bar Section End -->
				</div>

			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th>Image</th>
						<th>Name</th>
						<th>Mobile</th>
						<th>Address</th>
						<th>Zip</th>
						<th>Issue</th>
						<th>Doctor Name</th>
						<th>Doctor Fee</th>
						<th>Patients Email</th>
					</tr>
					<?php
					
					?>
					<tr>
						<td>
							<center>
								<a class="tooltipped" data-position="top" data-tooltip="<?= $pat_visiter[0]->patient_name; ?>">
									<img src="<?  
												?>" class="responsive-img" id="profile_pic" height="50">
								</a>
							</center>
						</td>
						<td>
							<?= $pat_visiter[0]->patient_name; ?>
						</td>
						<td>
							<a href="tel:<?= $pat_visiter[0]->patient_phone; ?>"><?= $pat_visiter[0]->patient_phone; ?></a>
						</td>
						<td>
							<?= $pat_visiter[0]->patient_address; ?>
						</td>
						<td>
							<?= $pat_visiter[0]->pin_zip_code; ?>
						</td>
						<td>
							<?= $pat_visiter[0]->patient_email; ?>
						</td>
						<td style="color: green">
							<?php
							

							?>
						</td>
						<td>
							<?= $pat_visiter[0]->doctor_fee; ?>
						</td>
						<td>
							<a href="mailto:<?= $pat_visiter[0]->patient_email; ?>"><?= $pat_visiter[0]->patient_email; ?></a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>