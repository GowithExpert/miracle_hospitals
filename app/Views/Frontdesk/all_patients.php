<!DOCTYPE html>
<html>

<head>
	<title>All Patients</title>
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<!----Css file Include --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	
	<!----Top Bar Section Start ---->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-wheelchair col_blu"></span>  Manage Patients</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Frontdesk/search_all_patient'); ?>
							<ul id="search_doctor">
								<li>
									<input type="text" name="patient_name" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light buton_blu btn_hver">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Frontdesk/add_patients') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext">Add Appointment</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s10">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn btn_hver" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Frontdesk/filter_patients/new_patients'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  New Patients </a></li>
								<li><a href="<?= base_url('Frontdesk/filter_patients/old_patients'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Patients </a></li>
							</ul>
						</div>
					</div>
					<!--Search Bar & Filter Bar Section End -->
			</div>
		<div class="scroll-container card-content">
			<table class="table">
				<tr class="event backgrnd_colr_gray">
					<th>Image</th>
					<th class="txt_align">Name</th>
					<th class="txt_align">#Puid</th>
					<th class="txt_align">Mobile</th>
					<th class="txt_align">Symptoms</th>
					<th class="txt_align">Room</th>
					<th class="txt_align">Dr. Name</th>
					<th class="txt_align">Email</th>
					<th class="txt_align">Status</th>
					<th class="txt_align">Action</th>
				</tr>
				<?php if (count($patients)) :
						foreach ($patients as $pat_rec) : ?>
						<tr>
							<td>
								<center>
						
									<a class="tooltipped" data-position="top" data-tooltip="<?= $pat_rec['patient_name']; ?>">
										<?php
										if (isset($pat_rec['patient_image']) && !empty($pat_rec['patient_image'])) {
											if (file_exists(WRITEPATH . 'public/uploads/patients/' . $pat_rec['patient_image'])) { ?>
												<img src="<?= base_url() . 'public/uploads/patients/' . $pat_rec['patient_image']; ?>" class="responsive-img" id="profile_pic" height="50">

											<?php  } //Inner if - Closed
											else {  ?>
												<img src="<?= base_url() . 'public/assets/images/dr.default-pic.jpg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Inner else - Closed

										} //Outer if - Closed

										else { ?>
											<img src="<?= base_url() . 'public/assets/images/dr.default-pic.jpg'; ?>" class="responsive-img" id="profile_pic" height="50">
										<?php } //Outer else - Closed  
										?>
									</a>
								</center>
							</td>
							<td class="txt_break-at300 txt_align">
								<?= $pat_rec['patient_name']; ?>
							</td>
							<td class="text-container txt_align">
								<span class="break-word">
									<?= $pat_rec['puid']; ?>
								</span>
							</td>
							<td class="txt_break-at300 txt_align">
								<a class="colour_hver" href="tel:<?= $pat_rec['patient_phone']; ?>"><?= $pat_rec['patient_phone']; ?></a>
							</td>
							<td class="txt_break-at300 txt_align col_ornge">
								<?= $pat_rec['patient_issue']; ?>
							</td>
							<td class="txt_break-at300 txt_align">
								<?= $pat_rec['patient_room']; ?>
							</td>
							<td class="text-container txt_align">
								<span class="break-word">
									<?php 
									$get_doctor =  get_doctor_name('doctor', $pat_rec['doctor_id']);
									if (isset($get_doctor[0]->doctor_name)) {
										echo $get_doctor[0]->doctor_name;
									}
									?>
								</span>
							</td>
							<td class="text-container txt_align">
								<span class="break-word">
									<a class="colour_hver" href="mailto:<?= $pat_rec['patient_email']; ?>"><?= $pat_rec['patient_email']; ?></a>
								</span>
							</td>
							<td>
							<?php if ($pat_rec['status'] == "Admit") :
										echo '<span class="col_gren">Admitted</span>';
									elseif ($pat_rec['status'] == 1) ://1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
										echo '<span class="col_red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == 2) ://1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
										echo '<span class="col_red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == "Admission Processed") :
										echo '<span class="col_gren">Admission Process</span>';
									elseif ($pat_rec['status'] == "Prescribed") :
										echo '<span class="col_gren">Prescribed</span>';
									elseif ($pat_rec['status'] == "Discharged") :
										echo '<span class="col_red">Discharged</span>';
									elseif ($pat_rec['status'] == "Attended") :
										echo '<span class="col_gren">Fee Paid</span>';
									elseif ($pat_rec['status'] == "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									elseif ($pat_rec['status'] == "Discharge Processed") :
										echo '<span class="col_red">Discharge Processed</span>';
									elseif ($pat_rec['status'] == "Discharge Summary") :
										echo '<span class="col_ornge">Added Discharge Summary</span>';
									elseif ($pat_rec['status'] == "Permanent Delete") :
										echo '<span class="col_ornge">Permanent Delete</span>';
									elseif ($pat_rec['status'] == "Unknown") :
										echo '<span class="col_red">Unknown</span>';
									else :
				
										echo '<span class="span_red_colr">Unknown</span>';
									?>
									<?php endif; ?>
							</td>

							<td>
								<center>
									<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
								</center>

								<!---Action Dropdown --->
								<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id']; ?>" id="dotted_border">
									<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id']); ?>" id="dotted_border"><span class="fa fa-edit col_blu"></span>  Edit</a></li>

									<li><a href="<?= base_url('Frontdesk/delete_patients/' . $pat_rec['id']); ?>" style="color:red;" class="delete-today-appointment-link" id="dotted_border"><span class="fa fa-trash col_red"></span>  Delete</a></li>
									<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
									<?php if ($pat_rec['status'] == "Active") :  ?>
										<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/InActive'); ?>" id="dotted_border">
												<span class="fa fa-eye-slash col_red"></span>  
												InActive</a></li>
									<?php else : ?>
										<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
									<?php endif; ?>

									<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id']); ?>" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>

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
					<h6 class="col_red">Record Not Found</h6>
				<?php endif; ?>
				<tr>
					<td colspan="7">
						<div id="pagination" class="col_wite">
							<?= $pager->links() ?>
						</div>
					</td>
				</tr>
			</table>
		<?php else : ?>
			<h6 class="col_red">No Record Found</h6>
		<?php endif; ?>
		</div>
		</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
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
	</script>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>