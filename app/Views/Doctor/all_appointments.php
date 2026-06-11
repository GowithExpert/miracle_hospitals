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
	<title>All Appointments</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!---Include Css File --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Doctor/top_bar'); ?>
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
				<h5 class="h5_align"><span class="fa fa-calendar col_blu"></span>  All Appointments</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($all_appointments) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Doctor/search_all_appointments'); ?>
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
						<!-- <div class="col l1 m1 s1">
							<//?php if ($all_appointments) :
								$today_dis_patient = count($all_appointments);
							?>
								<h6 class="fa_eye"><span class="fa fa-eye"></span>  <//?= $today_dis_patient; ?></h6>
							<//?php endif; ?>
						</div> -->
						<div class="col l4 m4 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">
								<li><a href="<?= base_url('Doctor/filter_all_appointments/new_patient'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-trophy col_blu"></span>  New Patients </a></li>
								<li><a href="<?= base_url('Doctor/filter_all_appointments/old_patient'); ?>" class="waves-effect">
										<span class="fa fa-trophy col_blu"></span>  Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Booking</th>
						<th class="txt_align">Patients Name</th>
						<th class="txt_align" title="Patient's unique ID">#Puid</th>
						<th class="txt_align" title="Patient's diseases symptomps">Symptomps</th>
						<th class="txt_align" title="Doctor's name">Doctor</th>
						<th class="txt_align" class="txt_align" title="Patient mobile number">Mobile</th>
						<th class="txt_align" title="Patient's booked appointment date">Appointment Date</th>
						<th class="txt_align" title="Patient's appointment time slot">Time Slot</th>
						<th class="txt_align" title="Patient current status">Status</th>
						<th class="txt_align">Registered</th>
						<th title="Actions allowed to perform">Action</th>
					</tr>
					<?php if ($all_appointments) :
						count($all_appointments);
						foreach ($all_appointments as $t_patient) :
							if (!isset($t_patient['puid']) || ($t_patient['puid'] == '')) {
								$t_patient['puid'] = 0;
							}
					?>

							<tbody>
								<tr>
									<td class="txt_break-at100"><?= $t_patient['serial']; ?></td>
									<td class="txt_break-at300 txt_align"><?= $t_patient['patient_name']; ?></td>
									<td class="txt_break-at300 txt_align"><?php if (isset($t_patient['puid']) && $t_patient['puid'] != 0) {
																	echo $t_patient['puid'];
																} else if (!isset($t_patient['puid']) || $t_patient['puid'] == 0) { ?>
											<span class="col_gren"> <?php echo "New";
																	} ?></span>
									</td>
									<td class="txt_break-at300 txt_align col_ornge"><?= $t_patient['disease_symptoms']; ?></td>
									<td class="txt_break-at300 txt_align">
										<a class="colour_hver" href="tel:<?= $t_patient['doctor_name']; ?>"><?= $t_patient['doctor_name']; ?></a>
									</td>
									<td class="txt_break-at300 txt_align">
										<a class="link_hver" href="tel:<?= $t_patient['patient_mobile']; ?>"><?= $t_patient['patient_mobile']; ?></a>
									</td>


									<td class="txt_break-at300 txt_align">

										<span>  <?= date('D, M d Y', strtotime($t_patient['booking_date'])); ?></span>

									</td>
									<td class="txt_break-at300 txt_align">

										<!-- <span class="fa fa-clock" style="color: green">  <? //= date('h:i:s', strtotime($t_patient->booking_time)); 
																									?></span> -->
										<span>  <?= $t_patient['booking_time']; ?></span>

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
										// elseif($t_patient['status'] == 6): 
										// 	echo '<span style="color:red">NA</span>';
										else :
											echo '<span class="span_red_colr">Unknown</span>';
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
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient['id']; ?>"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
				
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>" id="dotted_border">
										<?php if ($t_patient['status'] == 0) :  ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar col_blu"></span>  Appointment</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> 
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border">	<span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
												
									<?php elseif ($t_patient['status'] == 1) : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/add_fee/' . $t_patient['id'] . '/4' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']) . '/' . $t_patient['doctor_id']; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']) ; ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
												
									<?php elseif ($t_patient['status'] == "2") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>									
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']) ; ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>								
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>									
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>										
										<li><a href="<?= base_url('Doctor/permanent_del_today_apmnt/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="permanent-delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
							
									<?php elseif ($t_patient['status'] == "3") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>			
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>						
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>									
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
																						
									<?php elseif ($t_patient['status'] == "4") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>				
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>		
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "5") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>								
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>		
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete	</a></li>
												
									<?php elseif ($t_patient['status'] == "6") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

									<?php else : ?>
										<li><a href="<?= base_url('Doctor/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar col_blu"></span> Appointment</a></li>
									<?php endif; ?>

										</ul>
										<!---Action Dropdown --->
										<!-- Hidden delete confirmation modal -->
										<div id="deleteConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to delete this Patient?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmDelete" class="modal-button delete">Delete</button>
												</div>
											</div>
										</div>

										<!-- Hidden permanent delete confirmation modal -->
										<div id="permanentDeleteConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelPermanentDelete" class="modal-icon cancel" onclick="hidePermanentDeleteConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to permanently delete this item?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmPermanentDelete" class="modal-button delete">Permanent Delete</button>
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
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>

	<!-- Javascript /Jquery - START -->
	<script>
    $(document).ready(function() {
		var inputBox = $('#input_box');
		var clearInput = $('#clear-input');
		var basePath = 'all_appointments';
		let isFormValid = true;
		
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


        var deleteUrl;

        $('.delete-today-appointment-link').on('click', function(e) {
            e.preventDefault();
			 deleteUrl = $(this).attr('href');
            //deleteUrl = $(this).data('delete-url');

            // Show the custom delete confirmation modal
            $('#deleteConfirmationModal').show();
        });

        $('#cancelDelete').on('click', function() {
            // Hide the custom delete confirmation modal when cancel is clicked
            $('#deleteConfirmationModal').hide();
        }); 
		
        $('#confirmDelete').on('click', function() {
            // Perform the deletion and redirect
            window.location.href = deleteUrl;
        });

		///////for permanent delete//////
		var permanentDeleteUrl;

        $('.permanent-delete-today-appointment-link').on('click', function(e) {
            e.preventDefault();
            permanentDeleteUrl = $(this).attr('href');

            // Show the custom permanent delete confirmation modal
            $('#permanentDeleteConfirmationModal').show();
        });

        $('#cancelPermanentDelete').on('click', function() {
            // Hide the custom permanent delete confirmation modal when cancel is clicked
            $('#permanentDeleteConfirmationModal').hide();
        });

        $('#confirmPermanentDelete').on('click', function() {
            // Perform the permanent deletion and redirect
            window.location.href = permanentDeleteUrl;
        });
		if (!isFormValid) {
			return;
		}
    });

    function hideDeleteConfirmationModal() {
        // Hide the custom delete confirmation modal when the modal close icon is clicked
        $('#deleteConfirmationModal').hide();
    }
	function hidePermanentDeleteConfirmationModal() {
        // Hide the custom permanent delete confirmation modal when the modal close icon is clicked
        $('#permanentDeleteConfirmationModal').hide();
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
		<?= view('Admin/clear_text_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>