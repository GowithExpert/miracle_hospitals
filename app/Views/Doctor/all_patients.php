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
	<title>All Patients</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!---Include Css File --->
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
			<div id="sucesMsg" class="hidden"></div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-wheelchair col_blu"></span>  All Patients </h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				
				<?php if ($all_patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Doctor/search_all_patient'); ?>
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
						<div class="col l4 m4 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">
								<li><a href="<?= base_url('Doctor/filter_all_patients/new_patient'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-wheelchair col_blu"></span>  New Patients </a></li>
								<li><a href="<?= base_url('Doctor/filter_all_patients/old_patient'); ?>" class="waves-effect">
										<span class="fa fa-wheelchair col_blu"></span>  Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Image</th>
						<th class="txt_align">Patients Name</th>
						<th class="txt_align">#Puid</th>
						<th class="txt_align">Symptoms</th>
						<th class="txt_align">Phone</th>
						<th class="txt_align">Address</th>
						<th class="txt_align">Date</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Registered</th>
						<th>Action</th>
					</tr>
					<?php if(is_array($all_patients) || is_object($all_patients)) :
						count($all_patients);
						foreach ($all_patients as $t_patient) : ?>

							<tbody>
								<tr>
									<td>
										<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $t_patient['patient_name']; ?>">
												<?php
												if (isset($t_patient['patient_image']) && !empty($t_patient['patient_image'])) {
													if (file_exists(WRITEPATH . 'public/uploads/patients/' . $t_patient['patient_image'])) { ?>
														<img src="<?= base_url() . 'public/uploads/patients/' . $t_patient['patient_image']; ?>" class="responsive-img" id="profile_pic" height="50">

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
									<td class="txt_break-at200 txt_align"><?= $t_patient['patient_name']; ?></td>
									<td class="txt_break-at300 txt_align"><?php if (isset($t_patient['puid']) && $t_patient['puid'] != '') {
																	echo $t_patient['puid'];
																} else { ?>
											<span class="col_gren"> <?php echo "New";
																	} ?></span>
									</td>
									<td class="txt_break-at300 txt_align col_ornge"><?= $t_patient['patient_issue']; ?></td>
									<td class="txt_break-at300 txt_align">
										<a class="link_hver" href="tel:<?= $t_patient['patient_phone']; ?>"><?= $t_patient['patient_phone']; ?></a>
									</td>
									<td class="txt_break-at300 txt_align"><?= $t_patient['patient_address']; ?></td>

									<td class="txt_break-at300 txt_align">

										<span class="col_gren">  <?= date('D, M d Y', strtotime($t_patient['created_at'])); ?></span>

									</td>
									<td class="txt_break-at300 txt_align">
										<?php if ($t_patient['status'] == "Admit") :
											echo '<span class="col_gren">Admit</span>';
										elseif ($t_patient['status'] == 1) :
											echo '<span class="col_red">Dues Cleared</span>';
										elseif ($t_patient['status'] == 2) :
											echo '<span class="col_red">Dues Cleared</span>';
										elseif ($t_patient['status'] == "Admission Processed") :
											echo '<span class="col_gren">Admission Process</span>';
										elseif ($t_patient['status'] == "Discharged") :
											echo '<span class="col_red">Discharged</span>';
										elseif ($t_patient['status'] == "Prescribed") :
											echo '<span class="col_ornge">Prescribed</span>';
										elseif ($t_patient['status'] == "Deleted") :
											echo '<span class="col_red">Deleted</span>';
										elseif ($t_patient['status'] == "Payment Clear") :
											echo '<span class="col_red">Payment Clear</span>';
										elseif ($t_patient['status'] == "Discharge Summary") :
											echo '<span class="col_red">Discharge Summary</span>';
										elseif ($t_patient['status'] == "Attended") :
											echo '<span class="col_gren">Fee Paid</span>';
										else :
											echo '<span class="h6_red_colr">Unknown</span>';
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

											<?php if ($t_patient['status'] == "Admit") :  ?>

												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/discharge_summary/' . $t_patient['id'] . '/Discharge Summary'); ?>" id="dotted_border"><span class=" fa fa-money "></span>   Discharge Summary</a></li> -->
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li>	 -->
												
											<?php elseif ($t_patient['status'] == "Payment Clear") : ?>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" styele="color:red;" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li>
												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa fa-eye-slash col_red"></span>  Discharge</a></li>

											<?php elseif ($t_patient['status'] == "Discharged") : ?>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li> -->
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li> -->
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->

											<?php elseif ($t_patient['status'] == "Discharge Summary") : ?>
												
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->

											<?php elseif ($t_patient['status'] == "Deleted") : ?>

												<!-- <li><a href="<//?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li> -->
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<//?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<//?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa fa-eye-slash col_red"></span>  Discharge</a></li> -->
												<li><a href="<?= base_url('Doctor/permanent_del_today_patients/' . $t_patient['id']); ?>"id="dotted_border" class="permanent-delete-patient-link" style="color:red;"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>
												
											<?php elseif ($t_patient['status'] == "Attended") : ?>
												
												<li><a href="<?= base_url('Doctor/get_dept_for_admission/'. $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span> Admission Process</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												
											<?php elseif ($t_patient['status'] == 1) : ?>
												
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li>
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<///?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->


												<?php elseif ($t_patient['status'] == 2) : ?>
												
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Admit Patient</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/change_patients_status/' . $t_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li>
												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->



											<?php elseif ($t_patient['status'] == "Admission Processed") : ?>
												<li><a href="<?= base_url('Doctor/admit_patient/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Admit Patient</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $t_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li> -->
												<!-- <li><a href="<//?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li> -->
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li> -->

											<?php elseif ($t_patient['status'] == "Prescribed") : ?>
												<li><a href="<?= base_url('Doctor/admit_patient/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Admit Patient</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $t_patient['id'] . '/' . $t_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $t_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $t_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->

												
											<?php else : ?>
												<li><a href="<?= base_url('Doctor/admit_patient/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['apmt_id'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/' . $t_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span>  Admit Patient</a></li>
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
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="span_red_colr">Patient Not Found</h6>
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
		<script>
			$(document).ready(function() {
				var inputBox = $('#input_box');
				var clearInput = $('#clear-input');
				var basePath = 'all_patients';

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
				window.location.href = "<?= base_url('Doctor/delete_patients'); ?>/" + itemId + "/Deleted";
			}
		</script>
		<script>
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

		function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, '\\$&');
			var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}

		
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
            // Perform the permanent patient deletion and redirect
            window.location.href = permanentDeletePatientUrl;
        });
    });

    function hidePermanentDeletePatientConfirmationModal() {
        // Hide the custom permanent delete patient confirmation modal when the modal close icon is clicked
        $('#permanentDeletePatientConfirmationModal').hide();
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