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
	<title>Today Appointment</title>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<!---Top Bar Section Include -->
	<?= view('Patients/top_bar'); ?>
	<!---Top Bar Section Include -->
	<style type="text/css">
		table tr td {
			font-weight: 500;
			font-size: 15px;
		}
	</style>
</head>

<body>
	<!---Body Section Start --->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 10px;">
				<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-users" style="color: green"></span>  Today Appointment</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12" style="text-align: center;">
						<?php if ($today_apmt) :
							$today_dis_patient = count($today_apmt);
						?>
							<h6 style="color: red"><span class="fa fa-eye"></span>  <?= $today_dis_patient; ?></h6>
						<?php endif; ?>
					</div>
					<div class="col l6 m6 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter">  Filter Patients</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">
							<li><a href="<?= base_url('Patients/filter_appointment_patients/new_patient'); ?>" class="waves-effect" style="border-bottom: 1px dashed silver">
									<span class="fa fa-trophy" style="color: #005197"></span>  New Patients </a></li>
							<li><a href="<?= base_url('Patients/filter_appointment_patients/old_patient'); ?>" class="waves-effect">
									<span class="fa fa-trophy" style="color: #005197"></span>  Old Patients </a></li>
						</ul>
					</div>
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th>Patients Name</th>
						<th>#Puid</th>
						<th>Symptomps</th>
						<th>Phone</th>
						<th>Appointemnt Date</th>
						<th>Appointemnt Time</th>
						<th>Status</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($today_apmt) :
						count($today_apmt);
						foreach ($today_apmt as $t_patient) : ?>

							<tbody>
								<tr>

									<td><?= $t_patient->patient_name; ?></td>
									<td>
										<?php if (isset($t_appointment->puid) && $t_appointment->puid != '') {
											echo $t_appointment->puid;
										} else { ?>
											<span style="color: green"> <?php echo "New";
																	} ?></span>
									</td>
									<td style="color:orange"><?= $t_patient->patient_issue; ?></td>
									<td>
										<a href="tel:<?= $t_patient->patient_mobile; ?>"><?= $t_patient->patient_mobile; ?></a>
									</td>
									<td>
										<h6>
											<span class="fa fa-clock" style="color: green">  <?= date('D, M d Y', strtotime($t_patient->booking_date)); ?></span>
										</h6>
									</td>
									<td>
										<h6>
											
											<span class="fa fa-clock" style="color: green">  <?= $t_patient->booking_time; ?></span>
										</h6>
									</td>
									<td>
										<?php if ($t_patient->status == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:red">Cancelled</span>';
										elseif ($t_patient->status == 1) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:orange">Appointment</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient->id; ?>" style="text-transform: capitalize;font-weight: 500"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient->id; ?>">
											<?php if ($t_patient->status == 1) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Patients/change_appointment_status/' . $t_patient->id . '/0'); ?>" style="color:red">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
											<?php endif; ?>

										</ul>
										<!---Action Dropdown --->
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red;font-weight: 500">Patient Not Found</h6>
					<?php endif; ?>

				</table>
			</div>
		</div>
		<!---Body Section End --->

		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>