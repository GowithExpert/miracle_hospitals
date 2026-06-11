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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= View('Admin/css_file.php'); ?>
	<?= View('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
</head>
<style>
	.plus_icon_mgt {
  		margin-top: -4px !important;
	}
</style>
<body>
	<!---Topbar Section Include --->
	<?= View('Admin/top_bar'); ?>
	<!---Topbar Section Include --->

	<!---Body Section Start --->
	<div class="equl_mrgn">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-calendar col_blu"></span> Today's Patients Appointments</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($today_appointment) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Admin/search_today_appointments'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area col_blck" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						
						<div class="col l4 m4 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">
								<li><a href="<?= base_url('Admin/filter_today_appointment/new_patient'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-trophy col_blu"></span> New Patients </a></li>
								<li><a href="<?= base_url('Admin/filter_today_appointment/old_patient'); ?>" class="waves-effect">
										<span class="fa fa-trophy col_blu"></span> Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Serial</th>
						<th class="txt_align">Patients Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Symptomps</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align">Appointemnt Date</th>
						<th class="txt_align">Time Slot</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Registered</th>
						<th class="txt_align">Action</th>
					</tr>
					 <?php if (isset($today_apmnt) && (is_array($today_apmnt) || is_object($today_apmnt))) :
						if (count($today_apmnt) == 0) { ?> <h6 class="h5_inside">No record found

							<span class="right">
								<div class="tooltip"><a href="http://healthcare.neoarks/miracle_hospitals/Admin/doctors_available_slots/0"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="ftooltiptext tooltip_widh">Book Appointments</span>
								</div>
							</span>
							</h6>
				<?php }
				foreach ($today_apmnt as $t_patient) :
					if (!isset($t_patient['puid']) || ($t_patient['puid'] == '')) {
						$t_patient['puid'] = 0;
					}
				?>
					<tbody>
						<tr>
							<td class="txt_break-at100 txt_align"><?= $t_patient['serial']; ?></td>
							<td class="txt_break-at300 txt_align"><?= $t_patient['patient_name']; ?></td>
							<td class="txt_break-at300 txt_align"><?php if (isset($t_patient['puid']) && $t_patient['puid'] != 0) {
								echo $t_patient['puid'];
							} else if (!isset($t_patient['puid']) || $t_patient['puid'] == 0) { ?>
								<span class="col_gren"> <?php echo "New";
								} ?></span>
							</td>
							<td class="txt_break-at300 txt_align" class="col_ornge"><?= $t_patient['disease_symptoms']; ?></td>
							<td class="txt_break-at300 txt_align">
								<a class="link_hver" href="tel:<?= $t_patient['patient_mobile']; ?>"><?= $t_patient['patient_mobile']; ?></a>
							</td>
							<td class="txt_break-at300 txt_align">

								<span class="col_gren"> <?= date('D, M d Y', strtotime($t_patient['booking_date'])); ?></span>

							</td>
							<td class="txt_break-at300 txt_align">

								
								<span class="col_gren"> <?= $t_patient['booking_time']; ?></span>

							</td>
							<td class="txt_break-at300 txt_align">
								<?php if ($t_patient['status'] == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_red">Cancelled</span>';
							elseif ($t_patient['status'] == 1) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_ornge">Appointment</span>';
							elseif ($t_patient['status'] == 2) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_red">Deleted</span>';
							elseif ($t_patient['status'] == 3) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_ornge">Awaited</span>';
							elseif ($t_patient['status'] == 4) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_gren">Fee Paid</span>';
							elseif ($t_patient['status'] == 5) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View
								echo '<span class="col_ornge">Not Available</span>';
						
							else :
								echo '<span class="span_red_colr">Unknown </span>';
								?>
								<?php endif; ?>
							</td>
							<td class="text-container_mange_patient txt_align">
								<?php 
									if($t_patient['pid']!=0){ 
										echo "<span style='color:green' class='col_gren'>Yes</span>"; 
									}
									else{
										echo "<span style='color:red' class='col_red'>No</span>"; 
									}
								?>
							</td>
							<td>

								<center>
									<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
								</center>

								<!---Action Dropdown --->
								<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>">

									<?php if ($t_patient['status'] == 0) :  ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar col_ornge"></span>  Appointment</a></li>																					
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> 																						
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" 	id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
										
									<?php elseif ($t_patient['status'] == 1) : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/add_fee/' . $t_patient['id'] . '/4' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']) . '/' . $t_patient['doctor_id']; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>						
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>																
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>										
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "2") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>									
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>								
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>										
										<li><a href="<?= base_url('Admin/permanent_del_today_apmnt/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="permanent-delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
									
									<?php elseif ($t_patient['status'] == "3") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "4") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color: red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "5") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete	</a></li>
												
									<?php elseif ($t_patient['status'] == "6") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
										
									<?php else : ?>
										<li><a href="<?= base_url('Admin/change_today_appointments_status/' . $t_patient['id'] . '/Appointment'); ?>" id="dotted_border"><span class="fa fa-calendar col_blu"></span> Appointment</a></li>
									<?php endif; ?>
								</ul>
								<!---Action Dropdown --->

								<!-- Hidden delete today appointment confirmation modal -->
								<div id="deleteTodayAppointmentConfirmationModal" class="modal">
									<div class="modal-content">
										<span id="cancelDeleteTodayAppointment" class="modal-icon cancel" onclick="hideDeleteTodayAppointmentConfirmationModal();">&#x2716;</span>
										<p class="del_pop_text">Are you sure you want to delete today's appointment?</p>
										<div class="modal-buttons align_del_btn">
											<button id="confirmDeleteTodayAppointment" class="modal-button delete">Delete</button>
										</div>
									</div>
								</div>

								<!-- Hidden permanent delete today appointment confirmation modal -->
								<div id="permanentDeleteTodayAppointmentConfirmationModal" class="modal">
									<div class="modal-content">
										<span id="cancelPermanentDeleteTodayAppointment" class="modal-icon cancel" onclick="hidePermanentDeleteTodayAppointmentConfirmationModal();">&#x2716;</span>
										<p class="del_pop_text">Are you sure you want to permanently delete this today's appointment?</p>
										<div class="modal-buttons align_del_btn">
											<button id="confirmPermanentDeleteTodayAppointment" class="modal-button delete">Permanent Delete</button>
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				<?php endforeach; ?>
			<?php else : ?>
				<h6 class="h5_inside">Patient Not Found</h6>
			<?php endif; ?>
			<tr>
				<td colspan="7">
					<div id="pagination" class="col_wite">
						<?php if (isset($pager)) {
							echo $pager->links();
						} ?>
					</div>
				</td>
			</tr>
				</table>
			<?php else : ?>
				<h6 class="col_red">No Record Found
							<span class="right">
								<div class="tooltip"><a href="http://healthcare.neoarks/miracle_hospitals/Admin/doctors_available_slots/0"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext tooltip_widh">Book Appointments</span>
								</div>
							</span>
			</h6>
			<?php endif; ?>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
		<script>
			$(document).ready(function() {
				var inputBox = $('#input_box');
				var clearInput = $('#clear-input');
				var basePath = 'today_appointments';

				clearInput.on('click', function () {
					inputBox.val('');
					clearInput.hide();
					if (!containsBasePath(window.location.href, basePath)) {
						window.history.back();
					}
				});

				inputBox.on('input', function () {
					if (inputBox.val().trim() !== '') {
						clearInput.show();
					} else {
						clearInput.hide();
					}

					if (!containsBasePath(window.location.href, basePath) && inputBox.val().trim() === '') {
						window.history.back();
					}
				});

				// Initial check to hide the clear-input if the input is empty
				if (inputBox.val().trim() === '') {
					clearInput.hide();
				}

				function containsBasePath(url, basePath) {
					// Check if the base path is exactly equal to the URL
					return url.endsWith('/' + basePath) || url === basePath;
				}

				////////Delete Popup//////////////////////
				var deleteTodayAppointmentUrl;

				$('.delete-today-appointment-link').on('click', function(e) {
					e.preventDefault();
					deleteTodayAppointmentUrl = $(this).attr('href');

					// Show the custom delete today appointment confirmation modal
					$('#deleteTodayAppointmentConfirmationModal').show();
				});

				$('#cancelDeleteTodayAppointment').on('click', function() {
					// Hide the custom delete today appointment confirmation modal when cancel is clicked
					$('#deleteTodayAppointmentConfirmationModal').hide();
				});

				$('#confirmDeleteTodayAppointment').on('click', function() {
					// Perform the deletion of today's appointment and redirect
					window.location.href = deleteTodayAppointmentUrl;
				});

				///////Permanent Delete Popup/////////////////////

						var permanentDeleteTodayAppointmentUrl;

				$('.permanent-delete-today-appointment-link').on('click', function(e) {
					e.preventDefault();
					permanentDeleteTodayAppointmentUrl = $(this).attr('href');

					// Show the custom permanent delete today appointment confirmation modal
					$('#permanentDeleteTodayAppointmentConfirmationModal').show();
				});

				$('#cancelPermanentDeleteTodayAppointment').on('click', function() {
					// Hide the custom permanent delete today appointment confirmation modal when cancel is clicked
					$('#permanentDeleteTodayAppointmentConfirmationModal').hide();
				});

				$('#confirmPermanentDeleteTodayAppointment').on('click', function() {
					// Perform the permanent deletion of today's appointment and redirect
					window.location.href = permanentDeleteTodayAppointmentUrl;
				});
			});

			function hideDeleteTodayAppointmentConfirmationModal() {
				// Hide the custom delete today appointment confirmation modal when the modal close icon is clicked
				$('#deleteTodayAppointmentConfirmationModal').hide();
			}

			function hidePermanentDeleteTodayAppointmentConfirmationModal() {
				// Hide the custom permanent delete today appointment confirmation modal when the modal close icon is clicked
				$('#permanentDeleteTodayAppointmentConfirmationModal').hide();
			}


				///SUCCESS MESSAGE JS /////
		setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('#suces_msg');
			var errMsg = document.querySelectorAll('#eror_msg');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds

		///SUCCESS MESSAGE JS /////
		</script>
		<!---Body Section End --->

		<!---Js file Include -->
		<?= View('Admin/js_file.php'); ?>
		<?= view('Admin/clear_text_js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>