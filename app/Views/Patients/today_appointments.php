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
	<!---Include Css File --->
	<?//= View('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<!---Include Css File --->
	<style type="text/css">
		table tr td {
			font-weight: 500;
			font-size: 15px;
		}

		.colour_hver:hover {
			color: blue;
		}
	</style>
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Patients/top_bar'); ?>
	<!---Topbar Section Include --->

	<!---Body Section Start --->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 10px;">
				<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-users" style="color: green"></span>  Today Appointment Patients </h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12" style="text-align: center;">
						<?php if ($today_appointments) :
							$today_dis_patient = count($today_appointments);
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
							<li><a href="<?= base_url('Patients/filter_today_appointments/new_patient'); ?>" class="waves-effect" style="border-bottom: 1px dashed silver">
									<span class="fa fa-trophy" style="color: #005197"></span>  New Patients </a></li>
							<li><a href="<?= base_url('Patients/filter_today_appointments/old_patient'); ?>" class="waves-effect">
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
						<th>Mobile</th>
						<th>Appointemnt Date</th>
						<th>Time Slot</th>
						<th>Status</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($today_appointments) :
						count($today_appointments);
						foreach ($today_appointments as $t_patient) :
							if (!isset($t_patient->puid) || ($t_patient->puid == '')) {
								$t_patient->puid = 0;
							}
					?>

							<tbody>
								<tr>

									<td class="txt_break-at200"><?= $t_patient->patient_name; ?></td>
									<td class="txt_break-at300"><?php if (isset($t_patient->puid) && $t_patient->puid != 0) {
																	echo $t_patient->puid;
																} else if (!isset($t_patient->puid) || $t_patient->puid == 0) { ?>
											<span style="color: green"> <?php echo "New";
																	} ?></span>
									</td>
									<td class="txt_break-at300" style="color: orange"><?= $t_patient->disease_symptoms; ?></td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="tel:<?= $t_patient->patient_mobile; ?>"><?= $t_patient->patient_mobile; ?></a>
									</td>
									<td>
										<h6 class="txt_break-at300">
											<span class="" style="color: green">  <?= date('D, M d Y', strtotime($t_patient->booking_date)); ?></span>
										</h6>
									</td>
									<td>
										<h6 class="txt_break-at300">
											<!-- <span class="fa fa-clock" style="color: green">  <? //= date('h:i:s', strtotime($t_patient->booking_time)); 
																										?></span> -->
											<span class="fa fa-clock" style="color: green">  <?= $t_patient->booking_time; ?></span>
										</h6>
									</td>
									<td class="txt_break-at300">
										<?php if ($t_patient->status == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:red">Cancelled</span>';
										elseif ($t_patient->status == 1) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:orange">Appointment</span>';
										elseif ($t_patient->status == 2) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:red">Deleted</span>';
										elseif ($t_patient->status == 3) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:orange">Awaited</span>';
										elseif ($t_patient->status == 4) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:green">Attended</span>';
										elseif ($t_patient->status == 5) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:orange">Not Available</span>';
										elseif ($t_patient->status == 6) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											echo '<span style="color:red">Unknown</span>';
										else :
											echo '<span style="color:red;font-weight:500;font-size:14px;">Unknown </span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient->id; ?>" id="dotted_border" style="text-transform: capitalize;font-weight: 500"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient->id; ?>" id="dotted_border">
											<?php if ($t_patient->status == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Re-Appoint</a></li>

											<?php elseif ($t_patient->status == 1) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/4' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Attend</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/3' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Await</a></li>

												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/5' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Not Available</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/6' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Other</a></li>

											<?php elseif ($t_patient->status == 2) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Appoint</a></li>

												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/3' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Await</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/4' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Attend</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/5' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Not Available</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/6' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Other</a></li>
											<?php elseif ($t_patient->status == 3) :  ?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Appoint</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>

												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/4' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Attend</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/5' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Not Available</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/6' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Other</a></li>
											<?php elseif ($t_patient->status == 4) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" id="dotted_border" style="color:#039be5;">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Appoint</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/3' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Await</a></li>

												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/5' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Not Available</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/6' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Other</a></li>
											<?php elseif ($t_patient->status == 5) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Appoint</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/3' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Await</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/4' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Attend</a></li>

												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/6' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Other</a></li>
											<?php elseif ($t_patient->status == 6) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
											?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Cancel</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/1' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Appoint</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/2' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:red" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/3' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Await</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/4' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Attend</a></li>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/5' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" style="color:#039be5;" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														Not Available</a></li>

											<?php else : ?>
												<li><a href="<?= base_url('Doctor/change_today_appointment_status/' . $t_patient->id . '/0' . '/' . $t_patient->pid . '/' . $t_patient->puid); ?>" id="dotted_border">
														<span class="fa fa-eye" style="color: #005197"></span>  Unknown</a></li>
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