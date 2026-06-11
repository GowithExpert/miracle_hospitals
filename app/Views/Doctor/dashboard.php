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
	<title>Doctor Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->

<body>
	<!---Topbar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!---Topbar Section Include --->

	<!---dashboard Card Section Start -->
	<div class="row" id="dshbrd_mgn">
		
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
				//session()->removeTempdata('error');
			}
			?>
		</div>
		<!-- Rough-Start -->
		<div class="col l4 m12 s12">
			<div class="card" id="lght_red">
				<div class="card-content">
					<div class="row mrg_botm">
						<div class="col l8 m8 s8">
							<a href="<?= base_url('Doctor/all_appointments'); ?>">
								<h6 class="dsh_crd_red">
									<?php if (isset($chart_data['tot_appointments'])) :
										echo $chart_data['tot_appointments'];
									?>
									<?php else : ?>
										<h6 class="emty_value_align h6_produ_nt_fon">0</h6>
									<?php endif; ?>
								</h6>
								<h6 class="dsh_crd_red">All Appointments</h6>
						</div>
						<div class="col l4 m4 s4">
							<div class="icon-box txt_align">
								<img src="<?= base_url('public/assets/images/calander.jpg') ?>" class="img_icon">
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Rough-End -->
		<div class="col l4 m12 s12">
			<div class="card col_blu bck_blu">
				<div class="card-content">
					<div class="row mrg_botm">
						<div class="col l8 m8 s8">
							<a href="<?= base_url('Doctor/all_patients'); ?>">
								<h6 class="h6_produ_nt_fon">
									<?php if ($p_under_u) :
										$patient_u = count($p_under_u);
										echo $patient_u;
									?>
									<?php else : ?>
										<h6 class="emty_value_align h6_produ_nt_fon">0</h6>
									<?php endif; ?>
								</h6>
								<h6 class="h6_produ_nt_fon">All Patients</h6>
						</div>
						<div class="col l4 m4 s4">
							<div class="icon-box txt_align">
								<img src="<?= base_url('public/assets/images/patient_default.svg') ?>" class="img_icon">
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col l4 m12 s12">
			<div class="card lght_gren">
				<div class="card-content">
					<div class="row mrg_botm">
						<div class="col l8 m8 s8">
							<a href="<?= base_url('Doctor/total_discharge_patient'); ?>">
								<h6 class="h6_produ_nt_fon">
									<?php if ($t_d_patient) :
										$patient_u = count($t_d_patient);
										echo $patient_u;
									?>
									<?php else : ?>
										<h6 class="emty_value_align h6_produ_nt_fon">0</h6>
									<?php endif; ?>
								</h6>
								<h6 class="h6_produ_nt_fon">Discharged Patients</h6>
						</div>
						<div class="col l4 m4 s4">
							<div class="icon-box txt_align">
								<img src="<?= base_url('public/assets/images/patient_default.svg') ?>" class="img_icon">
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!---dashboard Card Section End -->
	<!---Resent Patient Appointment for You Section Start --->
	<div class="row">
		<div class="col l4 m4 s12">
			<div class="card">
				<div class="card-content">
					<div id="chartContainer" class="div_higt"></div>
				</div>
			</div>
		</div>
		<div class="col l8 m8 s12">
			<div class="card">
				<a href="<?= base_url('Doctor/all_patients'); ?>">
					<div class="doc_heder">
						<h6 class="al_patient1">All Patients</h6>
				</a>
			</div>

			<div class="scroll-container card-content">
				<?php if ($p_under_u) : ?>
			<div class="pdng_zero">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Images</th>
						<th>Name</th>
						<th>#Puid</th>
						<th>Symptoms</th>
						<th>Mobile</th>
						
						<th>Date</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					<?php if ($p_under_u) :
						count($p_under_u);
						foreach ($p_under_u as $recent_patient) :
							
					?>
							<tbody>
								<tr>
									<td>
										

										<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $recent_patient['patient_name']; ?>">
												<?php
												if (isset($recent_patient['patient_image']) && !empty($recent_patient['patient_image'])) {
													if (file_exists(WRITEPATH . 'public/uploads/patients/' . $recent_patient['patient_image'])) { ?>
														<img src="<?= base_url() . 'public/uploads/patients/' . $recent_patient['patient_image']; ?>" class="responsive-img" id="profile_pic" height="50">

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
									<td class="text-container">
										<?= $recent_patient['patient_name']; ?>
									</td>

									<td class="text-container">
										<?php if (isset($recent_patient['puid']) && $recent_patient['puid'] != '') {
											echo $recent_patient['puid'];
										} else { ?>
											<span class="col_gren"> <?php echo "New";
																	} ?></span>
									</td>

									<td class="text-container col_orange">
										<?= $recent_patient['patient_issue']; ?>
									</td>
									<td class="text-container">
										<a class="colour_hver" href="tel:<?= $recent_patient['patient_phone']; ?>"><?= $recent_patient['patient_phone']; ?></a>
									</td>
									
									<td>
										<h6 class="txt_break-at200">
											<span class="col_gren">  <?= date('D, M d Y', strtotime($recent_patient['created_at'])); ?></span>
										</h6>
									</td>
									
									<td class="txt_break-at300">
										<?php if ($recent_patient['status'] == "Admit") :
											echo '<span class="col_gren">Admit</span>';
										elseif ($recent_patient['status'] == "Discharged") :
											echo '<span class="col_red">Discharged</span>';
										elseif ($recent_patient['status'] == 1) :
											echo '<span class="col_red">Dues Cleared</span>';
										elseif ($recent_patient['status'] == 2) :
											echo '<span class="col_red">Dues Cleared</span>';
										elseif ($recent_patient['status'] == "Admission Processed") :
											echo '<span class="col_gren">Admission Process</span>';
										elseif ($recent_patient['status'] == "Prescribed") :
											echo '<span class="col_ornge">Prescribed</span>';
										elseif ($recent_patient['status'] == "Payment Clear") :
											echo '<span class="col_red">Payment Clear</span>';
										elseif ($recent_patient['status'] == "Discharge Summary") :
											echo '<span class="col_ornge">Discharge Summary</span>';
										elseif ($recent_patient['status'] == "Deleted") :
											echo '<span class="col_red">Deleted</span>';
										elseif ($recent_patient['status'] == "Attended") :
											echo '<span class="col_gren">Fee Paid</span>';
										else :
											echo '<span class="span_gren_colr">Appointment </span>';
										?>
										<?php endif; ?>
									</td>
									<td lass="txt_break-at300">
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $recent_patient['id']; ?>"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $recent_patient['id']; ?>" id="dotted_border">
										
											<?php if ($recent_patient['status'] == "Admit") :  ?>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												


											<?php elseif ($recent_patient['status'] == "Payment Clear") : ?>
												<li><a href="<?= base_url('Doctor/change_dshbrd_patients_status/' . $recent_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
												<li><a href="<?= base_url('Doctor/delete_dsbd_patients/' . $recent_patient['id'] . '/Deleted'); ?>" . id="dotted_border" style="color:red;"> <span class="fa fa-trash col_red"></span>   Delete</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>

											<?php elseif ($recent_patient['status'] == "Discharged") : ?>

												<li><a href="<?= base_url('Doctor/change_patients_status/' . $recent_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/delete_dsbd_patients/' . $recent_patient['id'] . '/Deleted'); ?>" . id="dotted_border" style="color:red;"> <span class="fa fa-trash col_red"></span>   Delete</a></li> -->


											<?php elseif ($recent_patient['status'] == "Discharge Summary") : ?>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $recent_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Re-Admit</a></li>
												<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
												
										<?php elseif ($recent_patient['status'] == "Attended") : ?>

											<li><a href="<?= base_url('Doctor/get_dept_for_admission/'. $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span> Admission Process</a></li>
											<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
											<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
											<li><a href="<?= base_url('Doctor/add_patient_payment/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
											<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
											<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>


											<?php elseif ($recent_patient['status'] == "Admission Processed") : ?>

												<li><a href="<?= base_url('Doctor/admit_patient/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['serial'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Admit Patient</a></li> 
												<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'] . '/' . $recent_patient['pid'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>



												<?php elseif ($recent_patient['status'] == "Prescribed") : ?>

													<li><a href="<?= base_url('Doctor/get_dept_for_admission/'. $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span> Admission Process</a></li>
													<li><a href="<?= base_url('Doctor/admit_patient/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['serial'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Admit Patient</a></li> 
													<li><a href="<?= base_url('Doctor/add_patient_payment/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
													<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
													<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
													<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'] . '/' . $recent_patient['pid'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
													<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>

												<?php elseif ($recent_patient['status'] == "Deleted") : ?>

													<li><a href="<?= base_url('Doctor/edit_patients/' . $recent_patient['id']); ?>" id="dotted_border"> <span class="fa fa-edit col_blu"></span>   Edit</a></li>
													<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>   Print Slip</a></li>
													<li><a href="<?= base_url('Doctor/permanent_del_all_dsbd_patients/' . $recent_patient['id']); ?>" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>


											<?php elseif ($recent_patient['status'] == 1) : ?>
												
												<li><a href="<?= base_url('Doctor/admit_patient/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['serial'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Re-Admit Patient</a></li> 
												<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'] . '/' . $recent_patient['pid'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<li><a href="<?= base_url('Doctor/change_patients_status/' . $recent_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li>
												<!-- <li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<///?= $recent_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->


												<?php elseif ($recent_patient['status'] == 2) : ?>
												
													<li><a href="<?= base_url('Doctor/admit_patient/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid'] . '/' . $recent_patient['serial'] . '/' . $recent_patient['doctor_id']); ?>" id="dotted_border" target="_blank"><span class="fas fa-procedures col_blu"></span> Admit Patient</a></li> 
												<li><a href="<?= base_url('Doctor/add_prescription/' . $recent_patient['id']); ?>" id="dotted_border"><span class="fa fa-plus-square col_gren"></span>  Add Prescription</a></li>
												<li><a href="<?= base_url('Doctor/print_slip/' . $recent_patient['id'] . '/' . $recent_patient['pid'].'/'.$recent_patient['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span>  Print Slip</a></li>
												<li><a href="<?= base_url('Doctor/add_patient_payment/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-rupee col_blu"></span>  Receive Payment</a></li>
												<li><a href="<?= base_url('Doctor/show_patient_final_payments/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" target="_blank" id="dotted_border"><span class=" fa fa-money "></span>   Clear Final Payment</a></li>
												<li><a href="<?= base_url('Doctor/add_hospital_expenses/' . $recent_patient['id'] . '/' . $recent_patient['pid'] . '/' . $recent_patient['apmt_id'] . '/' . $recent_patient['puid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-h-square col_blu"></span>  Add Hospital Expenses</a></li>
												<!-- <li><a href="<//?= base_url('Doctor/change_patients_status/' . $recent_patient['id'] . '/Discharged'); ?>" id="dotted_border" style="color:red"><span class="fa  fa-eye-slash col_red"></span>  Discharge</a></li>
												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $recent_patient['id']; ?>);" id="dotted_border" target="" style="color: red"><span class="fa fa-trash col_red"></span>   Delete</a></li> -->


											<?php else : ?>

												<li><a href="<?= base_url('Doctor/change_patients_status/' . $recent_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Admit</a></li>
											<?php endif; ?>

										</ul>
										<!---Action Dropdown --->
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red">Not any Patient</h6>
					<?php endif; ?>
					<tr>
						
				</table>
				<?php else : ?>
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>
	</div>

	<!---Resent Patient Appointment for You Section End --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
	<!---Js file Include -->
	<script type="text/javascript">
		//Chart Dashboard
		window.onload = function() {

			var options = {
				animationEnabled: true,
				title: {
					text: "Doctor Records by Status"
				},
				data: [{
					type: "doughnut",
					innerRadius: "40%",
					showInLegend: true,
					legendText: "{label}",
					indexLabel: "{label}:",
					dataPoints: [{
							label: 'Total Appointment',
							y: <?= $chart_data['tot_appointments']; ?>
						},
						{
							label: 'Total Patients',
							y: <?= $chart_data['total_patients']; ?>
						},
						{
							label: 'Total Discharge Patients',
							y: <?= $chart_data['all_discharge_pat']; ?>
						}
					]
				}]
			};
			$("#chartContainer").CanvasJSChart(options);

		}
		//Chart Dashboard 
	</script>
</body>

</html>