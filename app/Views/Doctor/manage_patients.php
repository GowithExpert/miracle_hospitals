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
	<title>Manage Patient</title>
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<style type="text/css">
		h6 {
			font-weight: 500;
			font-size: 16px;
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		#input_file {
			border: 1px solid silver;
			padding: 8px;
			width: 100%;
			margin-bottom: 15px;
			font-size: 14px;
			font-weight: 500
		}

		select {
			display: block;
		}

		select {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		textarea {
			border: 1px solid silver;
			padding: 10px;
			outline: none;
			height: 100px;
			resize: none;
			border-radius: 3px;
		}

		/*#profile_pic {
			width: 40px;
			height: 40px;
			border-radius: 100%;
			border: 1px solid silver;
		}*/

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

		#search_doctor li:first-child {
			width: 250px;
		}

		#search_doctor {
			display: flex;
		}

		.tooltip {
			position: relative;
			display: inline-block;
		}

		.tooltip .tooltiptext {
			visibility: hidden;
			width: 90px;
			background-color: #005197;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 0;
			position: absolute;
			z-index: 1;
			top: 100%;
			left: 50%;
			margin-left: -45px;

			/* Fade in tooltip - takes 1 second to go from 0% to 100% opac: */
			opacity: 0;
			transition: opacity 1s;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
			opacity: 1;
		}

		body {
			background: none !important;
		}

		.colour_hver:hover {
			color: blue;
		}
	</style>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Doctor/top_bar'); ?>
	<!-- <//?= view('Admin/top_bar'); ?> -->
	<!----Top Bar Section Start ---->

	<!-- <div> Before that it was margin-left: 15px and margin-right: 15px -->
	<div class="card" style="box-shadow: none;">
		<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
			<h5 style="font-weight: 500"><span class="fa fa-wheelchair" style="color: #005197"></span>  Manage Patients</h5>
		</div>
		<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
			<?php if ($patients) : ?>
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l8 m8 s12">
						<?= form_open('Doctor/search_patient'); ?>
						<ul id="search_doctor">
							<li>
								<input type="text" name="patient_name" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
							</li>
							<li>
								<button type="submit" class="btn waves-effect waves-light" style="background: #005197;text-transform: capitalize;height: 40px; margin-left: 9px">Search Now</button>
							</li>
						</ul>
						<?= form_close(); ?>
					</div>

					<div class="col l4 m4 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter">  Filter Patients</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">

							<li><a href="<?= base_url('Doctor/filter_patients/new_patients'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span>  New Patients </a></li>
							<li><a href="<?= base_url('Doctor/filter_patients/old_patients'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span>  Old Patients </a></li>
						</ul>
					</div>
				</div>
				<!--Search Bar & Filter Bar Section End -->
		</div>
	</div>
	<div class="card-content">
		<table class="table">
			<tr>
				<th>Image</th>
				<th>Name</th>
				<th>#Puid</th>
				<th>Mobile</th>
				<th>Address</th>
				<!-- <th>Zip</th> -->
				<th>Symptoms</th>
				<th>Dr. Room</th>
				<th>Dr. Name</th>
				<th>Dr. Fee</th>
				<th>Entry Fee</th>
				<th>Other Fee</th>
				<th>Patients Email</th>
				<th>Status</th>
				<th style="text-align: center;">Action</th>
			</tr>
			<?php if (count($patients)) :
					foreach ($patients as $pat_rec) : ?>
					<tr>
						<td>
							<center>
								<a class="tooltipped" data-position="top" data-tooltip="<?= $pat_rec['patient_name']; ?>">
									<img src="<?= base_url() . 'public/uploads/patients/' . $pat_rec['patient_image']; ?>" class="responsive-img" id="profile_pic" height="50">
								</a>
							</center>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['patient_name']; ?>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['puid']; ?>
						</td>
						<td class="txt_break-at300">
							<a class="colour_hver" href="tel:<?= $pat_rec['patient_phone']; ?>"><?= $pat_rec['patient_phone']; ?></a>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['patient_address']; ?>
						</td>
						
						<td class="txt_break-at300" style="color: orange;">
							<?= $pat_rec['patient_issue']; ?>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['patient_room']; ?>
						</td>
						<td class="txt_break-at300">
							<?php 
							$get_doctor =  get_doctor_name('doctor', $pat_rec['doctor_id']);
							if (isset($get_doctor[0]->doctor_name)) {
								echo $get_doctor[0]->doctor_name;
							}
							?>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['doctor_fee']; ?>
						</td>
						<td class="txt_break-at300">
							<?= $pat_rec['entry_fee']; ?>
						</td>

						<td class="txt_break-at300">
							<?= $pat_rec['other_fee']; ?>
						</td>
						<td class="txt_break-at300">
							<a class="colour_hver" href="mailto:<?= $pat_rec['patient_email']; ?>"><?= $pat_rec['patient_email']; ?></a>
						</td>
						<td class="txt_break-at300">
							<?php if ($pat_rec['status'] == "Active") :
								echo '<span style="color:green">Active</span>';
							else :
								echo '<span style="color:red;font-weight:500;font-size:14px;">InActive </span>';
							?>
							<?php endif; ?>
						</td>

						<td>
							<center>
								<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
							</center>

							<!---Action Dropdown --->
							<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id']; ?>" id="dotted_border">
								<li><a href="<?= base_url('Doctor/edit_patients/' . $pat_rec['id']); ?>" id="dotted_border"><span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>

								<li><a href="<?= base_url('Doctor/delete_patients/' . $pat_rec['id']); ?>" style="color:red;" onclick="return confirm('Are you sure you want to  delete this Patients Details?..');" id="dotted_border"><span class="fa fa-trash" style="color: red;"></span>  Delete</a></li>

								<?php if ($pat_rec['status'] == "Active") :  ?>
									<li><a href="<?= base_url('Doctor/change_patients_status/' . $pat_rec['id'] . '/InActive'); ?>" style="color:red;" id="dotted_border">
											<span class="fa  fa-eye-slash" style="color: red"></span>  
											InActive</a></li>
								<?php else : ?>
									<li><a href="<?= base_url('Doctor/change_patients_status/' . $pat_rec['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye" style="color: #005197"></span>  Active</a></li>
								<?php endif; ?>

								<li><a href="<?= base_url('Doctor/print_slip/' . $pat_rec['id']); ?>" target="_blank"><span class="fa fa-print" style="color: #005197;"></span>  Print Slip</a></li>

							</ul>
							<!---Action Dropdown --->
						</td>

					</tr>
				<?php endforeach; ?>

			<?php else : ?>
				<h6 style="color: red">Record Not Found</h6>
			<?php endif; ?>
			<tr>
				<td colspan="7">
					<div id="pagination" style="color: white">
						<?= $pager->links() ?>
					</div>
				</td>
			</tr>
		</table>
	<?php else : ?>
		<h6 style="color: red">No Record Found</h6>
	<?php endif; ?>
	</div>
	</div>
	<!-- </div> -->


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>