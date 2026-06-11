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
	<title>Manage Patients</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!---CSS File Include -->
	<style type="text/css">
		.tooltip .tooltiptext {
			width: 90px !important;
			margin-left: -45px !important;
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!--Top Bar Section Include --->

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
			<div id="sucesMsg" class="hidden"></div>
			<div id="customMessageContainer"></div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-wheelchair col_blu"></span>  Manage Patients</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Frontdesk/search_patient'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
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

								<li><a href="<?= base_url('Frontdesk/filter_patients/new_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Patients </a>
								</li>
								<li><a href="<?= base_url('Frontdesk/filter_patients/old_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  Old Patients </a>
								</li>
								<li><a href="<?= base_url('Frontdesk/filter_patients_dis_pat/discharged_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  Discharged Patients </a>
								</li>
								<li><a href="<?= base_url('Frontdesk/filter_deleted_pat/deleted_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  Deleted Patients </a>
								</li>
								<li><a href="<?= base_url('Frontdesk/filter_admitted_pat/admitted_patients'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Admitted Patients </a>
								</li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="event backgrnd_colr_gray">
						<th>Image</th>
						<th class="txt_align">Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Mobile</th>
						
						<th class="txt_align">Symptoms</th>
						<th class="txt_align">Room </th>
						<th class="txt_align">Dr. Name</th>
						
						<th class="txt_align"> Email</th>
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
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['patient_name']; ?>
								</td>
								<td class="text-container txt_align">
									<span class="break-word">
										<?= $pat_rec['puid']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="link_hver" href="tel:<?= $pat_rec['patient_phone']; ?>"><?= $pat_rec['patient_phone']; ?></a>
								</td>

								<td class="txt_break-at300 txt_align col_ornge">
									<?= $pat_rec['patient_issue']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['patient_room']; ?>
								</td>
								<td class="text-container txt_align">
									<span class="break-word">
										<?= $pat_rec['doctor_name']; ?>
									</span>
								</td>
								
								<td class="text-container txt_align">
									<span class="break-word">
										<a class="colour_hver" href="mailto:<?= $pat_rec['patient_email']; ?>"><?= $pat_rec['patient_email']; ?></a>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php if ($pat_rec['status'] == "Admit") :
										echo '<span class="col_gren">Admitted</span>';
									// elseif ($pat_rec['status'] == "Dues Cleared") :
									// 	echo '<span class="col_red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == 1) ://1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
										echo '<span class="col_red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == 2) ://1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient
										echo '<span class="col_red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == "Prescribed") :
										echo '<span class="col_red">Prescribed</span>';
									elseif ($pat_rec['status'] == "Admission Processed") :
										echo '<span class="col_gren">Admission Process</span>';
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
									else :
										// echo '<span class="h6_red_colr"> . $pat_rec['status']== "Unknown" </span>';
										echo '<span class="span_red_colr">Unknown</span>';
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
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>
									
									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>">

										<?php if ($pat_rec['status'] == "Admit") :  ?>

											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
											<!-- <li><a href="<//?= base_url('Frontdesk/discharge_summary/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharge Summary'); ?>" id="dotted_border"><span class=" fa fa-money "></span>   Add Discharge Summary</a></li> -->
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<//?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete</a></li> -->
														
										<?php elseif ($pat_rec['status'] == "Discharge Processed") : ?>

											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete</a></li>

											
										<?php elseif ($pat_rec['status'] == "Discharged") : ?>

											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<//?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete</a></li> -->


										<?php elseif ($pat_rec['status'] == "Prescribed") : ?>

											<li><a href="<?= base_url('Frontdesk/get_dept_for_admission/'. $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span> Admission Process</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>		
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<//?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete</a></li> -->



										<?php elseif ($pat_rec['status'] == "Discharge Summary") : ?>

											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/Discharge Processed'); ?>" id="dotted_border col_red"><span class=" fa fa-wheelchair col_red"></span>   Discharge Processed</a></li>		
											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<//?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete</a></li> -->
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Prescribed'); ?>" id="dotted_border"><span class="fa fa-plus-square"></span> Prescribed</a></li> 
													
										<?php elseif ($pat_rec['status'] == "Deleted") : ?>

											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li> 
											<!-- <li><a href="javascript:void(0);" id="dotted_border" class="permanent-delete-link" onclick="showPermanentDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>)"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li> -->
											<li><a href="javascript:void(0);" onclick="showPermanentDeleteConfirmationModal('<?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Permanent Delete</a></li>


										<?php elseif ($pat_rec['status'] == "Attended") : ?>

											<li><a href="<?= base_url('Frontdesk/get_dept_for_admission/'. $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span> Admission Process</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>		
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
										
										<?php elseif ($pat_rec['status'] == "Admission Processed") : ?>

											<li><a href="<?= base_url('Frontdesk/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span>  Admit Patient</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
													
										<?php elseif ($pat_rec['status'] == 1) : //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient ?> 

											<li><a href="<?= base_url('Frontdesk/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span>  Re-Admit Patient</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Update Prescription</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>	
											<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->
											<!-- <li>
											<a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<//?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red">
												<span class="fa fa-trash col_red"></span> Delete
											</a>
											</li> -->
											<li><a href="<?= base_url('Frontdesk/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li>  
										
										<?php elseif ($pat_rec['status'] == 2) : //1: `Dues Cleared` Admitted, 2: `Dues Cleared` Non-Admit Patient ?>

											<li><a href="<?= base_url('Frontdesk/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span>  Admit Patient</a></li>
											<li><a href="<?= base_url('Frontdesk/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Frontdesk/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
											<li><a href="<?= base_url('Frontdesk/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>	
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal('<?= htmlspecialchars($pat_rec['id'] . '/' . $pat_rec['pid'], ENT_QUOTES, 'UTF-8'); ?>');" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span> Delete</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Frontdesk/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span>  Re-Admit Patient</a></li>
										<?php endif; ?>

									</ul>
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
									<!-- HTML for the permanent delete confirmation modal -->
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
						<?php endforeach; ?>

					<?php else : ?>
						<h6 class="col_red h6_record">Record Not Found</h6>
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
				<h6 class="h6_red_colr h6_record">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<script>

		function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, '\\$&');
			var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}
		$(document).ready(function() {
			var inputBox = $('#input_box');
			var clearInput = $('#clear-input');
			var basePath = 'manage_patients';

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

    // Show the permanent delete confirmation modal
    function showPermanentDeleteConfirmationModal(itemId) {
        const modal = document.getElementById("permanentDeleteConfirmationModal");
        modal.style.display = "block";

        const confirmPermanentDeleteButton = document.getElementById("confirmPermanentDelete");
        const cancelPermanentDeleteButton = document.getElementById("cancelPermanentDelete");

        // Add event listeners to the buttons
        confirmPermanentDeleteButton.onclick = function() {
            permanentDeleteItem(itemId);
        };

        cancelPermanentDeleteButton.onclick = function() {
            modal.style.display = "none";
        };
    }

    // Function to delete the item
    function deleteItem(itemId) {
        // Close the modal
        const modal = document.getElementById("deleteConfirmationModal");
        modal.style.display = "none";

        // Redirect to the delete URL
        window.location.href = "<?= base_url('Frontdesk/delete_patients'); ?>/" + itemId + "/Deleted";
    }

    // Function to permanently delete the item
    function permanentDeleteItem(itemId) {
        // Close the modal
        const modal = document.getElementById("permanentDeleteConfirmationModal");
        modal.style.display = "none";

        // Redirect to the permanent delete URL
        window.location.href = "<?= base_url('Frontdesk/permanent_del_patients'); ?>/" + itemId + "/Permanent Deleted";
    }




		$(document).ready(function() {

			var urlParams = new URLSearchParams(window.location.search);
			var sucesMsg = urlParams.get('sucesMsg');

			var sucesMsgDiv = document.getElementById('sucesMsg');

			if (sucesMsgDiv) {
				// Check if a success message exists and is not null
				if (sucesMsg !== null && sucesMsg !== '') {
					sucesMsgDiv.innerHTML = decodeURIComponent(sucesMsg);

					// Display the success message
					sucesMsgDiv.style.display = 'block'; // or 'inline' or 'inline-block'

					// Hide the success message after 5 seconds
					setTimeout(function () {
						sucesMsgDiv.style.display = 'none';
					}, 5000);
				} else {
					// If sucesMsg is null or empty, hide the div
					sucesMsgDiv.style.display = 'none';
				}
			}


        // Function to get URL parameter by name
        function getUrlParameter(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        // Get success and error messages from URL parameters
        var sucesMsg = getUrlParameter('success');
        var errorMessage = getUrlParameter('error');

        // Get the message container
        var customMessageContainer = $('#customMessageContainer');

        // Display messages if available
        if (sucesMsg) {
            customMessageContainer.text(sucesMsg).addClass('success');
            // Display and hide the success message using fadeIn and fadeOut
            customMessageContainer.fadeIn();

            setTimeout(function () {
                customMessageContainer.fadeOut();
            }, 5000);
        } else if (errorMessage) {
            customMessageContainer.text(errorMessage).addClass('error');
            // Display and hide the error message using fadeIn and fadeOut
            customMessageContainer.fadeIn();

            setTimeout(function () {
                customMessageContainer.fadeOut();
            }, 5000);
        }///SUCCESS MESSAGE JS /////
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


    });
	</script>
	<?= view('Admin/text_wrap_js_file.php'); ?>
	<?= view('Admin/clear_text_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>