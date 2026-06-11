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
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
	<!---Include Css File --->
	<style>
		#profile_pic {
			width: 40px;
			height: 40px;
			border-radius: 100%;
			border: 1px solid silver;
		}

		#pagination nav {
			background: none;
			box-shadow: none;
		}

		.pagination li.active {
			background: none;
		}

		.pagination li.active a {
			color: white !important;
			background: #ccc !important;
		}

		.pagination a {
			color: black;
			font-weight: 500;
			border: 1px solid black;
			padding: 2px 5px;
			margin-left: 2px;
			border-radius: 3px;
		}

		#profile_pic {
			width: 40px;
			height: 40px;
			border-radius: 100%;
		}
	</style>
</head>

<body>
	<!---Topbar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!---Topbar Section Include --->

	<!---Body Section Start --->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 10px;">
				<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-users" style="color: green"></span>  All Discharge Patients </h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12" style="text-align: center;">
						<?php if ($all_patients) :
							$total_dis_patient = count($all_patients);
						?>
							<h6 style="color: red"><span class="fa fa-eye"></span>  <?= $total_dis_patient; ?></h6>
						<?php endif; ?>

					</div>
					<div class="col l6 m6 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter">  Filter Patients</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">
							<li><a href="<?= base_url('Frontdesk/filter_all_discharge_patient/new_patient'); ?>" class="waves-effect">
									<span class="fa fa-trophy" style="color: #005197"></span>  New Patients </a></li>
							<li><a href="<?= base_url('Frontdesk/filter_all_discharge_patient/old_patient'); ?>" class="waves-effect">
									<span class="fa fa-trophy" style="color: #005197"></span>  Old Patients </a></li>
						</ul>
					</div>
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th>Image</th>
						<th>Patients Name</th>
						<th>#Puid</th>
						<th>Symptoms</th>
						<th>Phone</th>
						<th>Address</th>
						<th>Date</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					<?php if (isset($all_patients)) :
						count($all_patients);
						foreach ($all_patients as $t_patient) : ?>
							<tbody>
								<tr>
									<td class="txt_break-at300">

										

										<a class="tooltipped" data-position="top" data-tooltip="<?= $t_patient['patient_name']; ?>">
											<?php
											if (isset($t_patient['patient_image']) && !empty($t_patient['patient_image'])) {
												if (file_exists(WRITEPATH . 'public/uploads/patients/' . $t_patient['patient_image'])) { ?>
													<img src="<?= base_url() . 'public/uploads/patients/' . $t_patient['patient_image']; ?>" class="responsive-img" id="profile_pic" height="50">

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
									</td>
									<td class="txt_break-at300"><?= $t_patient['patient_name']; ?></td>
									<td class="txt_break-at300">
										<?php if (!isset($t_patient['puid']) || $t_patient['puid'] != '') {
											echo $t_patient['puid'];
										} else { ?>
											<span style="color: green"> <?php echo "New";
																	} ?></span>
									</td>
									<td class="txt_break-at300" style="color: orange"><?= $t_patient['patient_issue']; ?></td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="tel:<?= $t_patient['patient_phone']; ?>"><?= $t_patient['patient_phone']; ?></a>
									</td>
									<td class="txt_break-at300"><?= $t_patient['patient_address']; ?></td>
									<td class="txt_break-at300">

										<span class="" style="color: green">  <?= date('D, M d Y', strtotime($t_patient['created_at'])); ?></span>

									</td>
									<td class="txt_break-at300" style="color: red"><?= $t_patient['status']; ?></td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient['id']; ?>" style="text-transform: capitalize;font-weight: 500"> <span class="fa fa-ellipsis-v"></span></a>
										</center>
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>">
											<?php if ($t_patient['status'] == "Discharged") :  ?>
												<li><a href="<?= base_url('Frontdesk/delete_all_dis_patients/' . $t_patient['id'] . '/Delete'); ?>" style="color:red">
														<span class="fa  fa-trash" style="color: red"></span>  
														Delete</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Frontdesk/change_all_patients_status/' . $t_patient['id'] . '/Active'); ?>"><span class="fa fa-eye" style="color: #005197"></span>  Active</a></li>
											<?php endif; ?>

										</ul>
									</td>

								</tr>
							</tbody>
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
				</table>
			</div>
		</div>
		<!---Body Section End --->

		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>