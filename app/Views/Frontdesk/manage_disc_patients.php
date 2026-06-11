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
	<title>Discharged Patients</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!---Body Section Start --->

	<div class="equl_mrgn">
		<div class="card"><div>
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
				<h5 class="font_weght"><span class="fa fa-flask col_blu"></span>  Manage Discharged Patients</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Frontdesk/search_discharge_patient'); ?>
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
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Frontdesk/add_patients') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext">Add Appointment</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Frontdesk/filter_dischrage_patient/new_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Patients </a></li>
								<li><a href="<?= base_url('Frontdesk/filter_dischrage_patient/old_patients'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content scrl_align">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Image</th>
						<th class="txt_align">Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align">Address</th>
						<th class="txt_align">Symptomps</th>
						<th class="txt_align">Room </th>
						<th class="txt_align">Dr. Name </th>
						<th class="txt_align"> Fee</th>
						<th class="txt_align">Entry Fee</th>
						<th class="txt_align">Other Fee</th>
						<th class="txt_align">Patients Email</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Registered</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if (count($patients)) :
						foreach ($patients as $pat_rec) : ?>
							<tr>
								<td>
									<center>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $pat_rec['patient_name']; ?>">
											<?php
											if (isset($pat_rec['med_image']) && !empty($pat_rec['med_image'])) {
												if (file_exists(FCPATH . 'uploads/medicine_image/' . $pat_rec['med_image'])) { ?>
													<img src="<?= base_url() . 'public/uploads/medicine_image/' . $pat_rec['med_image']; ?>" class="responsive-img" id="profile_pic" height="50">

												<?php  } //Inner if - Closed
												else {  ?>
													<img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Inner else - Closed

											} //Outer if - Closed

											else { ?>
												<img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Outer else - Closed  
											?>
										</a>
									</center>
								</td>
								<td class="text-container txt_align">
									<?= $pat_rec['patient_name']; ?>
								</td>
								<td class="text-container txt_align">
									<span class="break-word">
										<?= $pat_rec['puid']; ?>
									</span>
								</td>
								<td class="text-container txt_align">
									<a class="link_hver" href="tel:<?= $pat_rec['patient_phone']; ?>"><?= $pat_rec['patient_phone']; ?></a>
								</td>
								<td class="text-container txt_align">
									<?= $pat_rec['patient_address']; ?>
								</td>
								<td class="text-container txt_align col_ornge">
									<?= $pat_rec['patient_issue']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['patient_room']; ?>
								</td>
								<td class="text-container txt_align">
									<?= $pat_rec['doctor_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['doctor_fee']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['entry_fee']; ?>
								</td>

								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['other_fee']; ?>
								</td>
								<td class="text-container txt_align">
									<a class="colour_hver" href="mailto:<?= $pat_rec['patient_email']; ?>"><?= $pat_rec['patient_email']; ?></a>
								</td>
								<td class="text-container txt_align">
									<?php if ($pat_rec['status'] == "Discharged") :
										echo '<span class="col_red">Discharged</span>';
									elseif ($pat_rec['status'] == "Delete") :
										echo '<span class="col_red">Deleted</span>';
									else :
										echo '<span class="span_red_colr">Discharge </span>';
									?>
									<?php endif; ?>
								</td>
								<td class="text-container_mange_patient txt_align">
									<?php 
										if($pat_rec['pid']!=0){ 
											echo "<span style='color:green' class='col_gren'>Yes</span>"; 
										}
										else{
											echo "<span style='color:red' class='col_red'>No</span>"; 
										}
									?>
								</td>
							
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id']; ?>">
										<li><a href="<?= base_url('Frontdesk/payment_dischrge_patient/' . $pat_rec['id']); ?>" target="_blank" id="dotted_border"><span class="fa fa-eye col_gren"></span>  View Payment</a></li>
										<li><a href="<?= base_url('Frontdesk/add_appointment_pat_charge/' . $pat_rec['id']); ?>" target="_blank" id="dotted_border"><span class="fa fa-eye col_gren"></span>  Receive Payment</a></li>


										<?php if ($pat_rec['status'] == "Discharged") : ?>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $pat_rec['id']; ?>);" style="color: red"><span class="fa fa-trash col_red"></span>  Delete</a></li>
										<?php elseif ($pat_rec['status'] == "Delete") : ?>
											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Admit</a></li>
											<li><a href="<?= base_url('Frontdesk/permanent_del_manage_disc/' . $pat_rec['id']); ?>" target="_blank" id="dotted_border" style="color: red;"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>
											else:
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $pat_rec['id']; ?>);" style="color: red"><span class="fa fa-trash col_red"></span>  Delete</a></li>
										<?php endif ?>
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
								</td>

							</tr>
						<?php endforeach; ?>

					<?php else : ?>
						<h6 class="h6_record col_red">Record Not Found</h6>
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
				<h6 class="h6_record col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			var inputBox = $('#input_box');
			var clearInput = $('#clear-input');
			var basePath = 'manage_discharge_patients';

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
		});
		// Show the delete confirmation modal
		function showDeleteConfirmationModal(itemId) {
			const modal = document.getElementById("deleteConfirmationModal");
			modal.style.display = "block";

			const confirmDeleteButton = document.getElementById("confirmDelete");
			const cancelDeleteButton = document.getElementById("cancelDelete");

			// Add event listeners to the buttons
			confirmDeleteButton.onclick = function() {
				deleteItem(itemId);
			};

			cancelDeleteButton.onclick = function() {
				modal.style.display = "none";
			};
		}

		// Function to delete the item
		function deleteItem(itemId) {
			window.location.href = "<?= base_url('Frontdesk/ delete_dis_patients'); ?>/" + itemId + "/Deleted";
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


	</script>

	<!---Js file Include -->
	<?= view('Admin/clear_text_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>