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
	<title>All Appointments</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Patients/patient_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!---Include Css File --->
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Patients/top_bar'); ?>
	<!---Topbar Section Include --->

	<!---Body Section Start --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="h5_align"><span class="fa fa-calendar col_blu"></span> All Appointment Patients </h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($all_appointments) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Patients/search_all_appointments'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patient Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light buton_blu btn_hver">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>

					
						<div class="col l4 m4 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn btn_hver" data-target="Patients_filter">
									<span class="fa fa-filter"> Filter Patients</span>
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="Patients_filter">
								<li><a href="<?= base_url('Patients/filter_all_appointments/new_appointments'); ?>" class="waves-effect brd_botm">
										<span class="fa fa-trophy col_blu"></span> New Patients </a></li>
								<li><a href="<?= base_url('Patients/filter_all_appointments/old_appointments'); ?>" class="waves-effect">
										<span class="fa fa-trophy col_blu"></span> Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Patients Name</th>
						<th class="txt_align" title="Patient's unique ID">#Puid</th>
						<th class="txt_align" title="Patient's diseases symptomps">Apmt Fee</th>
						<th class="txt_align" title="Patient mobile number">Mobile</th>
						<th class="txt_align" title="Doctor's Name">Docter</th>
						<th class="txt_align" title="Patient's booked appointment date">Appointemnt Date</th>
						<th class="txt_align" title="Patient's appointment time slot">Time Slot</th>
						<th class="txt_align" title="Patient current status">Status</th>
						<th class="txt_align" title="Actions allowed to perform">Action</th>
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
									<td class="txt_break-at300"><?= $t_patient['patient_name']; ?></td>
									<td class="txt_break-at300 txt_align">
										<?php if (isset($t_patient['puid']) && $t_patient['puid'] != 0) {
																	echo $t_patient['puid'];
																} else if (!isset($t_patient['puid']) || $t_patient['puid'] == 0) { ?>
											<span class="col_gren"> <?php echo "New";
																	} ?></span></td>
									
									<td class="txt_break-at300 txt_align"style="<?php echo ($t_patient['paid_apmt_fee'] == 0) ? 'color: red;' : 'color: green;'; ?>"><?php echo ($t_patient['paid_apmt_fee'] == 0) ? "Not Paid" : "Paid";?></td>
									
									
									<td class="txt_break-at300 txt_align">
										<a class="link_hver" href="tel:<?= $t_patient['patient_mobile']; ?>"><?= $t_patient['patient_mobile']; ?></a>
									</td>

									<td class="txt_break-at300 txt_align">
										<?= $t_patient['doctor_name']; ?>
									</td>
									<td class="txt_break-at300 txt_align">

										<span class="col_gren"> <?= date('D, M d Y', strtotime($t_patient['booking_date'])); ?></span>

									</td>
									<td class="txt_break-at300 txt_align">

									
										<span class="fa fa-clock col_gren"> <?= $t_patient['booking_time']; ?></span>

									</td>
									<td class="txt_break-at300 txt_align">
										<?php
										if ($t_patient['status'] == 0) :
											echo '<span class="col_red">Cancelled</span>';
										elseif ($t_patient['status'] == 1) :
											echo '<span class="col_gren">Appointment</span>';
										elseif ($t_patient['status'] == 2) :
											echo '<span class="col_red">Deleted</span>';
										elseif ($t_patient['status'] == 3) :
											echo '<span class="col_ornge">Awaited</span>';
										elseif ($t_patient['status'] == 4) :
											echo '<span class="col_gren">Attended</span>';
										elseif ($t_patient['status'] == 5) :
											echo '<span class="col_ornge">Absent</span>';
										elseif ($t_patient['status'] == 6) :
											echo '<span class="col_gren">Fee Received</span>';
										elseif ($t_patient['status'] == 7) :
												echo '<span class="col_red">Fee Pending</span>';
										else : echo '<span class="col_red">Permanent Delete</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient['id']; ?>"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>" id="dotted_border">
											
											
											<?php if ($t_patient['status'] == 2) : ?>

												<li>
													<a href="<?= base_url('Patients/permanent_del_all_apmnt/' . $t_patient['id']); ?>" 
														style="color:red" id="dotted_border" class="permanent-delete-patient-appointments-link">
														<span class="fa fa-trash col_red"></span> Permanent Delete
													</a>
												</li>
												<li><a href="<?= base_url('Patients/change_all_appointment_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid']); ?>" style="color:red" id="dotted_border">
														<span class=" fa  fa-ban col_red"></span> Cancel</a></li>

											<?php elseif ($t_patient['status'] == 1) : ?>

												<?php if ($t_patient['paid_apmt_fee'] == 0) : ?>
												<li>
													<a href="<?= base_url('Patients/run_appointment_fee_form/' .'?last_insrt_apmt_id='. $t_patient['id'].'&appointment_date='.$t_patient['booking_date'].'&appointment_time='.$t_patient['booking_time'].'&patient_email='.$t_patient['patient_email'] .'&serial='.$t_patient['serial'] .'&patient_name='.$t_patient['patient_name'] .'&doctor_name='.$t_patient['doctor_name'] .'&doctor_id='.$t_patient['doctor_id'] .'&slot_id='.$t_patient['slot_id'] .'&country_code='.$t_patient['country_code'] .'&mobile='.$t_patient['patient_mobile']); ?>" 
														style="color:green" id="dotted_border">
														<span class="fa fa-rupee col_gren"></span> Pay Now
													</a>
												</li>
												<?php endif; ?>
												<li><a href="<?= base_url('Patients/change_all_appointment_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid']); ?>" style="color:red" id="dotted_border">
														<span class=" fa  fa-ban col_red"></span> Cancel</a></li>
														<li>
															<a href="<?= base_url('Patients/change_all_appointment_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid']); ?>" 
																style="color:red" id="dotted_border" class="delete-patient-appointments-link">
																<span class="fa fa-trash col_red"></span> Delete
															</a>
														</li>

														

											<?php elseif ($t_patient['status'] == 4) : ?>
												
												<li>
													<a href="<?= base_url('Patients/change_all_appointment_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid']); ?>" 
														style="color:red" id="dotted_border" class="delete-patient-appointments-link">
														<span class="fa fa-trash col_red"></span> Delete
													</a>
												</li>
												
											
											<?php elseif ($t_patient['status'] == 0) : ?>
												<li>
													<a href="<?= base_url('Patients/change_all_appointment_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid']); ?>" 
														style="color:red" id="dotted_border" class="delete-patient-appointments-link">
														<span class="fa fa-trash col_red"></span> Delete
													</a>
												</li>
											<?php endif ?>
										</ul>
										<!---Action Dropdown --->
										<!-- Hidden delete patient appointments confirmation modal -->
										<div id="deletePatientAppointmentsConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDeletePatientAppointments" class="modal-icon cancel" onclick="hideDeletePatientAppointmentsConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to delete all appointments for this patient?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmDeletePatientAppointments" class="modal-button delete">Delete</button>
												</div>
											</div>
										</div>


										<!-- Hidden permanent delete patient appointments confirmation modal -->
										<div id="permanentDeletePatientAppointmentsConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelPermanentDeletePatientAppointments" class="modal-icon cancel" onclick="hidePermanentDeletePatientAppointmentsConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to permanently delete all appointments for this patient?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmPermanentDeletePatientAppointments" class="modal-button delete">Permanent Delete</button>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="h6_red_colr">Patient Not Found</h6>
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
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

		///////////////////////////
		$("#btn_register_now").on("click", function () {
                       
					   updateCountryCode();
					   setTimeout(function () {
					   var full_name = $("#name").val();
					   var gender    = $("#gender").val();
					   var age       = $("#age").val();
					   var mobile    = $("#mobile").val();
					   var country_code = $("#country_code").val();
					   console.log(country_code);
					   var address = $("#address").val();
					   var email = $("#email").val();
					   var symptoms = $("#symptoms").val();
					   var appointment_date = $("#appointment_date").val();
					   var appointment_time = $("#appointment_time").val();
					   var discription = $("#desc").val();
					   var slot_id   =  $("#slot_id").val();
					   var doctor_id = $("#doctor_id").val();
					   var doctor_name = $("#doctor_name").val();
					  
					   $.ajax({
						   type: 'POST',
						   url: "<?= base_url('Patients/book_appointment') ?>",
						   data: {
							   full_name: full_name,
							   gender: gender,
							   age: age,
							   mobile: mobile,
							   country_code: country_code,
							   address: address,
							   email: email,
							   symptoms: symptoms,
							   appointment_date: appointment_date,
							   appointment_time: appointment_time,
							   discription:discription,
							   slot_id:slot_id,
							   doctor_id:doctor_id,
							   doctor_name:doctor_name,

						   },
						   dataType: 'json', // Assuming the response is in JSON format
						   success: function (response) {
							   if (response.status) {
								   var responseData = response.data;
								   
									var url = "<?= base_url('Patients/run_appointment_fee_form/') ?>"+
									'?last_insrt_apmt_id=' + encodeURIComponent(responseData.last_insrt_apmt_id) +
								   '&appointment_date='    + encodeURIComponent(appointment_date) +
								   '&appointment_time='    + encodeURIComponent(appointment_time) +
								   '&patient_email='       + encodeURIComponent(email) +
								   '&serial='              + encodeURIComponent(responseData.serial) +
								   '&patient_name='        + encodeURIComponent(responseData.patient_name) +
								   '&pid='                 + encodeURIComponent(responseData.pid) +
								   '&uid='                 + encodeURIComponent(responseData.uid) +
								   '&doctor_name='         + encodeURIComponent(responseData.doctor_name) +
								   '&slot_id='             + encodeURIComponent(slot_id)+
								   '&country_code='        + encodeURIComponent(country_code)+
								   '&mobile='              + encodeURIComponent(mobile);

								   console.log("Constructed URL:", url);

							   // Redirecting to the constructed URL
							   window.location.href = url;
								   console.log("Success: " + responseData.message);
							   } else {
								   console.log("Error: " + response.error);
							   }
						   },
						   error: function (xhr, textStatus, errorThrown) {
							   console.log("Something went wrong");
							   console.log(xhr);
							   console.log(textStatus);
							   console.log(errorThrown);

							   if (xhr.responseJSON && xhr.responseJSON.message) {
								   console.log("Error: " + xhr.responseJSON.message);
							   } else {
								   console.log("Error: Something went wrong. Please try again.");
							   }
						   }
					   });
				   }, 100); // Adjust the delay time as needed
			   
			});
		////////////////////////
        var deletePatientAppointmentsUrl;

        $('.delete-patient-appointments-link').on('click', function(e) {
            e.preventDefault();
            deletePatientAppointmentsUrl = $(this).attr('href');

            // Show the custom delete patient appointments confirmation modal
            $('#deletePatientAppointmentsConfirmationModal').show();
        });

        $('#cancelDeletePatientAppointments').on('click', function() {
            // Hide the custom delete patient appointments confirmation modal when cancel is clicked
            $('#deletePatientAppointmentsConfirmationModal').hide();
        });

        $('#confirmDeletePatientAppointments').on('click', function() {
            // Perform the deletion of patient's appointments and redirect
            window.location.href = deletePatientAppointmentsUrl;
        });

		////////////////////Permanent Delete Popup///////////////////
		var permanentDeletePatientAppointmentsUrl;

		$('.permanent-delete-patient-appointments-link').on('click', function(e) {
			e.preventDefault();
			permanentDeletePatientAppointmentsUrl = $(this).attr('href');

			// Show the custom permanent delete patient appointments confirmation modal
			$('#permanentDeletePatientAppointmentsConfirmationModal').show();
		});

		$('#cancelPermanentDeletePatientAppointments').on('click', function() {
			// Hide the custom permanent delete patient appointments confirmation modal when cancel is clicked
			$('#permanentDeletePatientAppointmentsConfirmationModal').hide();
		});

		$('#confirmPermanentDeletePatientAppointments').on('click', function() {
			// Perform the permanent deletion of patient's appointments and redirect
			window.location.href = permanentDeletePatientAppointmentsUrl;
		});
    });

    function hideDeletePatientAppointmentsConfirmationModal() {
        // Hide the custom delete patient appointments confirmation modal when the modal close icon is clicked
        $('#deletePatientAppointmentsConfirmationModal').hide();
    }
	function hidePermanentDeletePatientAppointmentsConfirmationModal() {
        // Hide the custom permanent delete patient appointments confirmation modal when the modal close icon is clicked
        $('#permanentDeletePatientAppointmentsConfirmationModal').hide();
    }
</script>
		<!---Body Section End --->
		<?= view('Admin/clear_text_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>