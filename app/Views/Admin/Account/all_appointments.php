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
	<title>All Appointment</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include  -->
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<title>Auto Complete</title>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!-- Include jQuery library -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<!-- Include jQuery UI library -->
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

	<!-- Include jQuery UI CSS -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
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
				<h5 class="font_weght"><span class="fa fa-calendar col_blu"></span> All Appointment</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($all_appointments) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Admin/search_all_appointments'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patient Name" required="">
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
								<div class="tooltip"><a href="http://healthcare.neoarks/miracle_hospitals/Admin/doctors_available_slots/0"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext tooltip_widh">Book Appointments</span>
								</div>
							
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter" style="margin-top: -28px; margin-left: 10px;">
									<span class="fa fa-filter"></span> Filter Appointment
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Admin/filter_appointment/new_appointment'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span> New Appointment </a></li>
								<li><a href="<?= base_url('Admin/filter_appointment/old_appointment'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span> Old Appointment </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Serial</th>
						<th class="txt_align">Patient Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Email</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align">Doctor </th>
						<th class="txt_align">Date</th>
						<th class="txt_align">Time-Slot</th>
						<th class="txt_align">Symptoms</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Registered</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if ($all_appointments) :
						count($all_appointments); ?>
						<?php foreach ($all_appointments as $t_appointment) :
							if (!isset($t_appointment['puid']) || ($t_appointment['puid'] == '')) {
								$t_appointment['puid'] = 0;
							}
						?>
							<tr>
								<td class="txt_break-at100 txt_align">
									<?= $t_appointment['serial']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="break-word">
										<?= $t_appointment['patient_name']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align"><?php if (isset($t_appointment['puid']) && $t_appointment['puid'] != 0) {echo $t_appointment['puid'];
									} else if (!isset($t_appointment['puid']) || $t_appointment['puid'] == 0) { ?>
									<span class="col_gren"> <?php echo "New";} ?></span>
							</td>
								<td class="text-container_appoint txt_align">
									<span class="break-word">
										<a class="colour_hver" href="mailto:<?= $t_appointment['patient_email']; ?>"><?= $t_appointment['patient_email']; ?></a>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="link_hver" href="tel:<?= $t_appointment['patient_mobile']; ?>"><?= $t_appointment['patient_mobile']; ?></a>
								</td>
								<td class="text-container_appoint txt_align">
									<span class="break-word">
										<?= $t_appointment['doctor_name']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="col_gren"> <?= date('d M, Y', strtotime($t_appointment['booking_date'])); ?></span>
								</td>
								<td class="txt_break-at300 txt_align">
									<span> <?= $t_appointment['booking_time']; ?></span>
								</td>
								<td class="txt_break-at300 txt_align col_ornge">
									<span class="break-word">
										<?= $t_appointment['disease_symptoms']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php
									if ($t_appointment['status'] == 0) :
										echo '<span class="col_red">Cancelled</span>';
									elseif ($t_appointment['status'] == 1) :
										echo '<span class="col_gren">Appointment</span>';
									elseif ($t_appointment['status'] == 2) :
										echo '<span class="col_red">Deleted</span>';
									elseif ($t_appointment['status'] == 3) :
										echo '<span class="col_ornge">Awaited</span>';
									elseif ($t_appointment['status'] == 4) :
										echo '<span class="col_gren">Fee Paid</span>';
									elseif ($t_appointment['status'] == 5) :
										echo '<span class="col_ornge">Absent</span>';
									else : echo '<span class="col_red">Unknown</span>';
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
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/'  . $t_appointment['pid'] . '/' . $t_appointment['puid']. '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span>  Appointment</a></li>
											
											<!-- <li><a href="<//?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

										<?php elseif ($t_appointment['status'] == 1) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->											
											<li><a href="<?= base_url('Admin/add_fee/' . $t_appointment['id'] . '/4' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']) . '/' . $t_appointment['doctor_id']; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>																						
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
												
										<?php elseif ($t_appointment['status'] == 2) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>																							
											<!-- <li><a href="<//?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>																								 -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban col_red"></span> Cancel</a></li>																					
											<li><a href="<?= base_url('Admin/permanent_del_all_apmnt/' . $t_appointment['id']); ?>"id="dotted_border" class="permanent-delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
																					
											<?php elseif ($t_appointment['status'] == 3) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>																								
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban col_red"></span> Cancel</a></li>																
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
																																	
										<?php elseif ($t_appointment['status'] == 4) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<!-- <li><a href="<//?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li> -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>"id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
										<?php elseif ($t_appointment['status'] == 5) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<!-- <li><a href="<//?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li> -->
											<!-- <li><a href="<//?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
													
													
										<?php elseif ($t_appointment['status'] == 6) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>		
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/0' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/3' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>										
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/5' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/2' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
														
										<?php else : ?>
											<li><a href="<?= base_url('Admin/change_all_appointments_status/' . $t_appointment['id'] . '/1' . '/' . $t_appointment['pid'] . '/' . $t_appointment['puid'] . '/' . $t_appointment['serial']. '/' .$t_appointment['doctor_id']); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Appointment</a></li>		
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<?php endif; ?>
									</ul>
									<!---Action Dropdown --->
									<!-- Hidden delete appointment confirmation modal -->
										<div id="deleteAppointmentConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDeleteAppointment" class="modal-icon cancel" onclick="hideDeleteAppointmentConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to delete this appointment?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmDeleteAppointment" class="modal-button delete">Delete</button>
												</div>
											</div>
										</div>

										<!-- Hidden permanent delete appointment confirmation modal -->
										<div id="permanentDeleteAppointmentConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelPermanentDeleteAppointment" class="modal-icon cancel" onclick="hidePermanentDeleteAppointmentConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to permanently delete this appointment?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmPermanentDeleteAppointment" class="modal-button delete">Permanent Delete</button>
												</div>
											</div>
										</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red">Not any Appointment</h6>
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
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
			<script>
				$(document).ready(function() {
					var inputBox = $('#input_box');
					var clearInput = $('#clear-input');
					var basePath = 'all_appointments';

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


					/////////////Delete Popup////////////////
					var deleteAppointmentUrl;

					$('.delete-appointment-link').on('click', function(e) {
						e.preventDefault();
						deleteAppointmentUrl = $(this).attr('href');

						// Show the custom delete appointment confirmation modal
						$('#deleteAppointmentConfirmationModal').show();
					});

					$('#cancelDeleteAppointment').on('click', function() {
						// Hide the custom delete appointment confirmation modal when cancel is clicked
						$('#deleteAppointmentConfirmationModal').hide();
					});

					$('#confirmDeleteAppointment').on('click', function() {
						// Perform the deletion of the appointment and redirect
						window.location.href = deleteAppointmentUrl;
					});

					/////////////////Permanent Delete Popup/////////////
					var permanentDeleteAppointmentUrl;

					$('.permanent-delete-appointment-link').on('click', function(e) {
						e.preventDefault();
						permanentDeleteAppointmentUrl = $(this).attr('href');

						// Show the custom permanent delete appointment confirmation modal
						$('#permanentDeleteAppointmentConfirmationModal').show();
					});

					$('#cancelPermanentDeleteAppointment').on('click', function() {
						// Hide the custom permanent delete appointment confirmation modal when cancel is clicked
						$('#permanentDeleteAppointmentConfirmationModal').hide();
					});

					$('#confirmPermanentDeleteAppointment').on('click', function() {
						// Perform the permanent deletion of the appointment and redirect
						window.location.href = permanentDeleteAppointmentUrl;
					});
				});

				function hideDeleteAppointmentConfirmationModal() {
					// Hide the custom delete appointment confirmation modal when the modal close icon is clicked
					$('#deleteAppointmentConfirmationModal').hide();
				}
				
				function hidePermanentDeleteAppointmentConfirmationModal() {
					// Hide the custom permanent delete appointment confirmation modal when the modal close icon is clicked
					$('#permanentDeleteAppointmentConfirmationModal').hide();
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
			</div>
		</div>
		<!---Body Section End --->
		<?= view('Admin/text_wrap_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/clear_text_js_file.php'); ?>
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>