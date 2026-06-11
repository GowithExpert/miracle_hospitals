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
	<title>All Discharge Patients</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
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
				<h5 class="h5_align"><span class="fa fa-users col_blu"></span>  All Discharge Patients </h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($all_patients) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">

						<div class="col l8 m8 s12">
							<?= form_open('Doctor/search_all_dis_patient'); ?>
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
									<span class="fa fa-filter"></span>  Filter Patients
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">
								<li><a href="<?= base_url('Doctor/filter_all_discharge_patient/new_patient'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-trophy col_blu"></span>  New Patients </a></li>
								<li><a href="<?= base_url('Doctor/filter_all_discharge_patient/old_patient'); ?>" class="waves-effect">
										<span class="fa fa-trophy col_blu"></span>  Old Patients </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Image</th>
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
					<?php if ($all_patients) :
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
									<td class="txt_break-at300 txt_align"><?= $t_patient['patient_name']; ?></td>
									<td class="txt_break-at300 txt_align">
										<?php if (!isset($t_patient['puid']) || $t_patient['puid'] != '') {
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
									<td class="txt_break-at300 txt_align col_red"><?= $t_patient['status']; ?></td>
									<td class="txt_break-at300 txt_align">
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
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>">
											<?php if ($t_patient['status'] == "Discharged") :  ?>
												<li><a href="<?= base_url('Doctor/delete_all_dis_patients/' . $t_patient['id'] . '/Delete'); ?>" style="color:red"  id="dotted_border">
														<span class="fa fa-trash col_red"></span>  
														Delete</a></li>
														<li><a href="<?= base_url('Doctor/change_today_patients_status/' . $t_patient['id'] . '/Admit'); ?>" id="dotted_border"><span class="fas fa-procedures col_blu"></span>  Re-Admit</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Doctor/change_all_patients_status/' . $t_patient['id'] . '/Active'); ?>"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<?php endif; ?>

										</ul>
									</td>

								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red">Record Not Found</h6>
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
				<h6 class="h5_inside">No Record Found</h6>
			<?php endif; ?>
			</table>
			</div>
		</div>
	<script>
		$(document).ready(function() {
			var inputBox = $('#input_box');
			var clearInput = $('#clear-input');
			var basePath = 'total_discharge_patient';

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