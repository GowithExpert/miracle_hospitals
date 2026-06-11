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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!---CSS File Include -->
	<style type="text/css">
		.tooltip .tooltiptext {
			width: 90px !important;
			margin-left: -63px !important;
		}

		@media (max-width: 768px) {
			.scroll-container {
				overflow-x: auto;
				white-space: nowrap;
			}
		}

		@media (max-width: 1115px) {

			td,
			th {
				font-size: 14px !important;
			}
		}

		.text-container {
			width: 150px;
			/* Set a width for the container */
			font-size: 13px !important;
		}

		.break-word {
			overflow-wrap: normal;
			/* Prevent breaking of words */
		}

		.input-container {
			position: relative;
		}

		#clear-input {
			position: absolute;
			top: 44%;
			right: 10px;
			transform: translateY(-50%);
			cursor: pointer;
		}

		.colour_hver:focus {
			color: gray !important;
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->

	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-wheelchair" style="color: #005197"></span> Manage Patients</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<?php if ($patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Medical_Accountant/search_patient'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver" style="background: #005197;text-transform: capitalize;height: 40px; margin-left: 9px">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Medical_Accountant/fetch_patients') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext" style="width:134px !important;">Add Appointments</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s10">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
									<span class="fa fa-filter"> Filter Patients</span>
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Medical_Accountant/filter_patients/new_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users" style="color: #005197"></span> New Patients </a>
								</li>
								<li><a href="<?= base_url('Medical_Accountant/filter_patients/old_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users" style="color: #005197"></span> Old Patients </a>
								</li>
								<li><a href="<?= base_url('Medical_Accountant/filter_patients_dis_pat/discharged_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users" style="color: #005197"></span> Discharged Patients </a>
								</li>
								<li><a href="<?= base_url('Medical_Accountant/filter_deleted_pat/deleted_patients'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users" style="color: #005197"></span> Deleted Patients </a>
								</li>
								<li><a href="<?= base_url('Medical_Accountant/filter_admitted_pat/admitted_patients'); ?>" class="waves-effect">
										<span class="fa fa-users" style="color: #005197"></span> Admitted Patients </a>
								</li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="event" style="background: #f2f2f2;">
						<th>Image</th>
						<th class="txt_align">Patient Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align"> Email</th>
						
						<th class="txt_align">Symptoms</th>
						<th class="txt_align">Room </th>
						<th class="txt_align">Dr. Name</th>
						
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
								<td class="txt_break-at200 text-container txt_align">
									<?= $pat_rec['patient_name']; ?>
								</td>
								<td class="txt_break-at200 text-container txt_align">
									<span class="break-word">
										<?= $pat_rec['puid']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="colour_hver" href="tel:<?= $pat_rec['patient_phone']; ?>"><?= $pat_rec['patient_phone']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="break-word">
										<a class="colour_hver" href="mailto:<?= $pat_rec['patient_email']; ?>"><?= $pat_rec['patient_email']; ?></a>
									</span>
								</td>

								<td class="txt_break-at300 txt_align" style="color: orange;">
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
								<td class="txt_break-at300 txt_align">
									<?php if ($pat_rec['status'] == "Admit") :
										echo '<span style="color:green">Admitted</span>';
									elseif ($pat_rec['status'] == "Dues Cleared") :
										echo '<span style="color:red">Dues Cleared</span>';
									elseif ($pat_rec['status'] == "Admission Processed") :
										echo '<span style="color:green">Admission Process</span>';
									elseif ($pat_rec['status'] == "Prescribed") :
										echo '<span style="color:green">Prescribed</span>';
									elseif ($pat_rec['status'] == "Discharged") :
										echo '<span style="color:red">Discharged</span>';
									elseif ($pat_rec['status'] == "Attended") :
										echo '<span style="color:green">Fee Paid</span>';
									elseif ($pat_rec['status'] == "Deleted") :
										echo '<span style="color:red">Deleted</span>';
									elseif ($pat_rec['status'] == "Discharge Processed") :
										echo '<span style="color:red">Discharge Processed</span>';
									elseif ($pat_rec['status'] == "Discharge Summary") :
										echo '<span style="color:orange">Added Discharge Summary</span>';
									elseif ($pat_rec['status'] == "Permanent Delete") :
										echo '<span style="color:orange">Permanent Delete</span>';
									elseif ($pat_rec['status'] == "Unknown") :
										echo '<span style="color:red">Unknown</span>';
									else :
										 
										echo '<span style="color:red;font-weight:500;font-size:14px;">Unknown</span>';
									?>
									<?php endif; ?>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>
									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id'] . '/' . $pat_rec['pid']; ?>">

										<?php if ($pat_rec['status'] == "Admit") :  ?>
											
										
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" ><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>
										
											<li><a href="<?= base_url('Medical_Accountant/discharge_summary' ); ?>" id="dotted_border"><span class=" fa fa-money "></span>  Add Discharge Summary</a></li>
											<li><a href="<?= base_url('Medical_Accountant/show_patient_final_payments/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>  Clear Final Payment</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square" style="color: green"></span> Update Prescription</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span> Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red">
													<span class="fa  fa-eye-slash" style="color: red"></span> 
													Discharge</a></li>
													<li>
												<a href="<?= base_url('Medical_Accountant/delete_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" 
													id="dotted_border" class="delete-patient-link" style="color:red;">
													<span class="fa fa-trash" style="color:red;"></span>  Delete
												</a>
											</li>

										<?php elseif ($pat_rec['status'] == "Discharge Processed") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Re-Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square" style="color: green"></span> Update Prescription</a></li>
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/delete_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" 
													id="dotted_border" class="delete-patient-link" style="color:red;">
													<span class="fa fa-trash" style="color:red;"></span>  Delete
												</a>
											</li>
											
											<li><a href="<?= base_url('Medical_Accountant/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red">
													<span class="fa  fa-eye-slash" style="color: red"></span> 
													Discharge</a></li>

										<?php elseif ($pat_rec['status'] == "Admission Processed") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Admit Patient</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>


										<?php elseif ($pat_rec['status'] == "Discharged") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Re-Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>
											<li>
												<a href="<?= base_url('Medical_Accountant/delete_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" 
													id="dotted_border" class="delete-patient-link" style="color:red;">
													<span class="fa fa-trash" style="color:red;"></span>  Delete
												</a>
											</li>

										<?php elseif ($pat_rec['status'] == "Prescribed") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>


										<?php elseif ($pat_rec['status'] == "Discharge Summary") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Re-Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square" style="color: green"></span> Update Prescription</a></li>
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/Discharge Processed'); ?>" id="dotted_border" style="color:red"><span class=" fa fa-wheelchair " style="color: red"></span>  Discharge Processed</a></li>
											<li><a href="<?= base_url('Medical_Accountant/delete_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" class="delete-patient-link" style="color:red;">
												<span class="fa fa-trash" style="color:red;"></span>  Delete	</a></li>
														
										<?php elseif ($pat_rec['status'] == "Deleted") : ?>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Re-Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/edit_patients/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"> <span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border"><span class="fa fa-plus-square" style="color: green"></span> Update Prescription</a></li>
											<li><a href="<?= base_url('Medical_Accountant/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red">
													<span class="fa  fa-eye-slash" style="color: red"></span> 
													Discharge</a></li>
											<li><a href="<?= base_url('Medical_Accountant/permanent_del_patients/' . $pat_rec['id']); ?>" 
													id="dotted_border" class="permanent-delete-patient-link" style="color:red;">
													<span class="fa fa-trash" style="color:red;"></span> Permanent Delete
												</a>
											</li>
										<?php elseif ($pat_rec['status'] == "Attended") : ?>
											
											<li><a href="<?= base_url('Medical_Accountant/get_dept_for_admission/'. $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures" style="color: #005197"></span> Admission Process</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border">
											<span class="fa fa-plus-square" style="color: green"></span> Update Prescription</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>
											
										<?php elseif ($pat_rec['status'] == "Dues Cleared") : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash" style="color: red"></span> Discharge</a></li>
											<li><a href="<?= base_url('Medical_Accountant/admit_patient/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid'] . '/' . $pat_rec['serial'] . '/' . $pat_rec['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures" style="color: #005197"></span> Admit Patient</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_prescription/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border">
											<span class="fa fa-plus-square" style="color: green"></span> Add Prescription</a></li>
											<li><a href="<?= base_url('Medical_Accountant/print_slip/' . $pat_rec['id'] . '/' . $pat_rec['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_patient_payment/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee" style="color: #005197;"></span> Receive Payment</a></li>
											<li><a href="<?= base_url('Medical_Accountant/add_hospital_expenses/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/' . $pat_rec['apmt_id'] . '/' . $pat_rec['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square" style="color: #005197;"></span> Add Hospital Expenses</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_patients_status/' . $pat_rec['id'] . '/' . $pat_rec['pid'] . '/Admit'); ?>" id="dotted_border;"><span class="fas fa-procedures" style="color: #005197"></span> Admit</a></li>
										<?php endif; ?>

									</ul>
									<!-- Hidden delete patient confirmation modal -->
									<div id="deletePatientConfirmationModal" class="modal">
										<div class="modal-content">
											<span id="cancelDeletePatient" class="modal-icon cancel" onclick="hideDeletePatientConfirmationModal();">&#x2716;</span>
											<p class="del_pop_text">Are you sure you want to delete this patient?</p>
											<div class="modal-buttons align_del_btn">
												<button id="confirmDeletePatient" class="modal-button delete">Delete</button>
											</div>
										</div>
									</div>
									<!-- Hidden permanent delete patient confirmation modal -->
									<div id="permanentDeletePatientConfirmationModal" class="modal">
										<div class="modal-content">
											<span id="cancelPermanentDeletePatient" class="modal-icon cancel" onclick="hidePermanentDeletePatientConfirmationModal();">&#x2716;</span>
											<p class="del_pop_text">Are you sure you want to permanently delete this patient?</p>
											<div class="modal-buttons align_del_btn">
												<button id="confirmPermanentDeletePatient" class="modal-button delete">Permanent Delete</button>
											</div>
										</div>
									</div>
								</td>

							</tr>
						<?php endforeach; ?>

					<?php else : ?>
						<h6 style="color: red">Record Not Found</h6>
					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" style="color: white">
								<?php if (isset($pager)) {
									echo $pager->links();
								} ?>
							</div>
						</td>
					</tr>
				</table>
			<?php else : ?>
				<h6 style="color: red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<script>
    $(document).ready(function() {
		////////////////Delete Popup//////////////////////////////
        var deletePatientUrl;

        $('.delete-patient-link').on('click', function(e) {
            e.preventDefault();
            deletePatientUrl = $(this).attr('href');

            // Show the custom delete patient confirmation modal
            $('#deletePatientConfirmationModal').show();
        });

        $('#cancelDeletePatient').on('click', function() {
            // Hide the custom delete patient confirmation modal when cancel is clicked
            $('#deletePatientConfirmationModal').hide();
        });

        $('#confirmDeletePatient').on('click', function() {
            // Perform the deletion of the patient and redirect
            window.location.href = deletePatientUrl;
        });

		////////////////Permanent Delete Popup///////////////////////
		var permanentDeletePatientUrl;

        $('.permanent-delete-patient-link').on('click', function(e) {
            e.preventDefault();
            permanentDeletePatientUrl = $(this).attr('href');

            // Show the custom permanent delete patient confirmation modal
            $('#permanentDeletePatientConfirmationModal').show();
        });

        $('#cancelPermanentDeletePatient').on('click', function() {
            // Hide the custom permanent delete patient confirmation modal when cancel is clicked
            $('#permanentDeletePatientConfirmationModal').hide();
        });

        $('#confirmPermanentDeletePatient').on('click', function() {
            // Perform the permanent deletion of the patient and redirect
            window.location.href = permanentDeletePatientUrl;
        });
    });

    function hideDeletePatientConfirmationModal() {
        // Hide the custom delete patient confirmation modal when the modal close icon is clicked
        $('#deletePatientConfirmationModal').hide();
    }
	function hidePermanentDeletePatientConfirmationModal() {
        // Hide the custom permanent delete patient confirmation modal when the modal close icon is clicked
        $('#permanentDeletePatientConfirmationModal').hide();
    }

</script>
	<?= view('Admin/text_wrap_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/clear_text_js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>