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
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include  -->
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!--Top Bar Section Include --->
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
				<h5 class="font_weght"><span class="fa fa-calendar col_blu"></span>  Today Appointment</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($today_apmnt) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Frontdesk/search_today_appointments'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
					
						<div class="col 14 m4 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Frontdesk/filter_today_appointment/new_appointment'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Appointment </a></li>
								<li><a href="<?= base_url('Frontdesk/filter_today_appointment/old_appointment'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Appointment </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>serial</th>
						<th class="txt_align">Patient Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Email</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align">Doctor</th>
						<th class="txt_align">Date</th>
						<th class="txt_align">Time-Slot</th>
						<th class="txt_align">Symptomps</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Registered</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if (isset($today_apmnt) && (is_array($today_apmnt) || is_object($today_apmnt))) :
						if (count($today_apmnt) == 0) { ?> <h6 class="h6_record col_red">No record found</h6>
						<?php }
						foreach ($today_apmnt as $t_appointment) :
							if (!isset($t_appointment['puid']) || ($t_appointment['puid'] == '')) {
								$t_appointment['puid'] = 0;
							}
						?>
							<tr>
								<td class="txt_break-at100">
									<?= $t_appointment['serial']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $t_appointment['patient_name']; ?>
								</td>
								<td class="text-container txt_align"><?php if (isset($t_appointment['puid']) && $t_appointment['puid'] != 0) {
										echo $t_appointment['puid'];
									} else if (!isset($t_appointment['puid']) || $t_appointment['puid'] == 0) { ?>
								<span class="col_gren"> <?php echo "New";
														} ?></span>
								</td>
								<td class="text-container txt_align">
									<a class="colour_hver" href="mailto:<?= $t_appointment['patient_email']; ?>"><?= $t_appointment['patient_email']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="link_hver" href="tel:<?= $t_appointment['patient_mobile']; ?>"><?= $t_appointment['patient_mobile']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $t_appointment['doctor_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="col_ornge">  <?= date('d M, Y', strtotime($t_appointment['booking_date'])); ?></span>
								</td>
								<td class="txt_break-at300 txt_align">
									<span>  <?= ($t_appointment['booking_time']); ?></span>
								</td>
								<td class="txt_break-at300 txt_align col_ornge">
									<?= $t_appointment['disease_symptoms']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php if ($t_appointment['status'] == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
										echo '<span class="col_red">Cancelled</span>';
									elseif ($t_appointment['status'] == 1) :
										echo '<span class="col_gren">Appointment</span>';
									elseif ($t_appointment['status'] == 2) :
										echo '<span class="col_red">Deleted</span>';
									elseif ($t_appointment['status'] == 3) :
										echo '<span class="col_red">Waiting</span>';
									elseif ($t_appointment['status'] == 4) :
										echo '<span class="col_gren">Fee Paid</span>';
									elseif ($t_appointment['status'] == 5) :
										echo '<span class="col_ornge">Absent</span>';
									elseif ($t_appointment['status'] == 6) :
										echo '<span class="col_red">Unknown</span>';
									else :
										echo '<span class="span_red_colr">InActive </span>';
									?>
									<?php endif; ?>
								</td>
								
								<td class="text-container_mange_patient txt_align">
									<?php 
										if($t_appointment['pid']!=0){ 
											echo "<span style='color:green' class='col_gren'>Yes</span>"; 
										}
										else{
											echo "<span style='color:red' class='col_red'>No</span>"; 
										}
									?>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_appointment['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_appointment['id']; ?>">

										<?php if ($t_appointment['status'] == 0) :  ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar col_ornge"></span>  Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> 
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" 	id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
									
									<?php elseif ($t_appointment['status'] == 1) : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/add_fee/' . $t_appointment['id'] . '/4' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']) . '/' . $t_appointment['doctor_id']; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_appointment['status'] == "2") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/permanent_del_today_apmnt/' . $t_appointment['id'] . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="permanent-delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
										
									<?php elseif ($t_appointment['status'] == "3") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_appointment['status'] == "4") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>		
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_appointment['status'] == "5") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete	</a></li>
												
									<?php elseif ($t_appointment['status'] == "6") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php else : ?>
										<li><a href="<?= base_url('Frontdesk/change_today_appointments_status/' . $t_appointment['id'] . '/Appointment'); ?>" id="dotted_border"><span class="fa fa-calendar col_blu"></span> Appointment</a></li>
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
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="h6_record col_red">Unexpected record format</h6>
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
				<h6 class="h6_record col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>

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

				/////Delete Popup/////////////////////////////////
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

				/////Permanent Delete Popup/////////////////////////////
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
		<?= view('Admin/clear_text_js_file.php'); ?>
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>