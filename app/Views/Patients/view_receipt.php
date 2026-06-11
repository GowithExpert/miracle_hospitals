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
	<title>Patient Receipt</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Patients/patient_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Patients/patient_css_file.php'); ?>
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
				<h5 class="h5_align"><span class="fa fa-rupee col_blu"></span> Patient Receipt  </h5>
			</div>
			<div class="card-content" id="div_pad">
				<?php if ($payments) : ?>
                    <?php //echo "<pre>"; print_r($payments);die;?>
					<!--Search Bar & Filter Bar Section Start -->
					
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Patients Name</th>
						<th class="txt_align" title="Patient's unique ID">#Puid</th>
                        <th class="txt_align" title="Patient mobile number">Mobile</th>
						<th class="txt_align" title="Patient's Registration Fee"> Registration Fee (₹)</th>
						<th class="txt_align" title="Patient's Room Charge"> Room Charge (₹)</th>
						<th class="txt_align" title="Patient's Doc Fee "> Doc Fee (₹)</th>
						<th class="txt_align" title="Patient's Pay Date "> Pay Date (₹)</th>
						<th class="txt_align" title="Patient's Total Paid"> Total Paid (₹)</th>
						<th class="txt_align" title="Actions allowed to perform">Action</th>
					</tr>
					<?php if ($payments) :
						count($payments);
						foreach ($payments as $payment) :
							//echo "<pre>";print_r($payments);die; 
							if (!isset($payment['puid']) || ($payment['puid'] == '')) {
								$payment['puid'] = 0;
							}
					        ?>

							<tbody>
								<tr>
									<td class="txt_break-at300"><?= $payment['patient_name']; ?></td>

									<td class="txt_break-at300 txt_align"><?php if (isset($payment['puid']) && $payment['puid'] != 0) { echo $payment['puid'];
										} else if (!isset($payment['puid']) || $payment['puid'] == 0) { ?> <span class="col_gren"> <?php echo "New"; } ?></span></td>
																	
                                     <td class="txt_break-at300 txt_align"><?= $payment['patient_phone']; ?></td>
										
                                     <td class="txt_break-at300 txt_align">	<a class="colour_hver" href="tel:<?= $payment['registration_fee']; ?>">	<?= $payment['registration_fee']; ?></a></td>
					
                                     <td class="txt_break-at300 txt_align"><?= $payment['room_charge']; ?></td>
                                     
                                     <td class="txt_break-at300 txt_align"><?= $payment['doc_fee']; ?></td>
                                     
                                     <td class="txt_break-at300 txt_align" class="col_gren"><?= $payment['pay_date']; ?></td>
                                     
                                     <td class="txt_break-at300 txt_align"><?= $payment['paid_amount']; ?></td>

									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $payment['id']; ?>"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $payment['id']; ?>" id="dotted_border">
											<?php if ($payment['patients_id'] != 0) { ?>
											<li><a href="<?= base_url('Patients/print_slip/' . $payment['patients_id'] . '/' . $payment['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print col_blu"></span> Print Slip</a></li>
											<?php } ?>

											<!-- <li><a href="<//?= base_url('Patients/print_slip/' . $payment['patients_id'] . '/' . $payment['pid']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print" style="color: #005197;"></span> Print Slip</a></li> -->

                                            <li><a href="<?= base_url('Patients/del_pay_receipt/' . $payment['id']); ?>" style="color:red !important" id="dotted_border" class="permanent-delete-patient-appointments-link"><span class="fa fa-trash col_red"></span> Delete </a></li>
													
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
		<!---Body Section End --->
		<?= view('Admin/clear_text_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>
