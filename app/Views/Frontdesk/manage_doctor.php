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
	<title>Manage Doctor</title>
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<style type="text/css">
		
		h6 {
			font-weight: 500
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
		}

		#search_doctor {
			display: flex;
		}

		#search_doctor li:first-child {
			width: 250px
		}

		#doctor_filter {
			width: 180px !important;
			padding-top: 8px;
			padding-bottom: 8px;
		}

		#doctor_filter li a {
			color: grey;
			font-size: 14px;
			font-weight: 500;
		}

		#profile_pic {
			width: 40px;
			height: 40px;
			border-radius: 100%;
			border: 1px solid silver
		}

		td {
			font-size: 13px !important;
			font-weight: 400 !important;
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

		.colour_hver:hover {
			color: blue;
		}

	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div style="margin-left: 15px; margin-right: 15px">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-user-md" style="color: #005197"></span>  Manage Doctor</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l8 m8 s12">
						<?= form_open('Frontdesk/search_doctor'); ?>
						<ul id="search_doctor">
							<li>
								<input type="text" name="doctor_name" id="input_box" value="<?= set_value('doctor_name'); ?>" placeholder="Enter Doctor Name" required="">
							</li>
							<li>
								<button type="submit" class="btn waves-effect waves-light" style="background: #005197;text-transform: capitalize;height: 38px; margin-left: 9px">Search Now</button>
							</li>
						</ul>
						<?= form_close(); ?>
					</div>
					
						
						<li><a href="<?= base_url('Frontdesk/filter_doctor/new_doctor'); ?>" class="waves-effect">
							<span class="fa fa-users" style="color: #005197"></span>  New  Doctor </a></li>
						<li><a href="<?= base_url('Frontdesk/filter_doctor/old_doctor'); ?>" class="waves-effect">
							<span class="fa fa-users" style="color: #005197"></span>  Old Doctor </a></li>
					</ul>
				</div>	 
					<!--Search Bar & Filter Bar Section End -->
				</div>

			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th style="text-align: center;"></th> <!-- photo -->
						<th>Dr. Name</th>
						<th>Degree</th>
						<th>Specialization</th>
						<th>Department</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Address</th>
						<th>Fee</th>
						<th>Gender</th>
						
						<th>Status</th>
						
					</tr>
					<?php if ($doctors) :
						count($doctors);
						//echo "<pre>";print_r($doctors);die;
						foreach ($doctors as $doc) : ?>
							<tbody>
								<tr>
									<td>
										<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $doc->doctor_name; ?>">
												<img src="<?= base_url() . 'public/uploads/doctor/' . $doc->profile_pic; ?>" class="responsive-img" id="profile_pic" height="50">
											</a>
										</center>
									</td>
									<td class="txt_break-at200">
										<?= $doc->doctor_name; ?>
									</td>
									<td class="txt_break-at300">
										<?= $doc->education; ?>
									</td>
									<td class="txt_break-at300">
										<?= $doc->dr_specialization; ?>
									</td>
									<td class="txt_break-at300" style="color: orange">
										<?= $doc->department_name; ?>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="mailto:<?= $doc->doctor_email; ?>"><?= $doc->doctor_email; ?></a>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="tel:<?= $doc->doctor_phone; ?>"> <?= $doc->doctor_phone; ?></a>
									</td>
									<!-- <td class="tooltip" title="<?= $doc->doctor_address; ?>">
						<?= $doc->doctor_address; ?>
					</td> -->
									<td class="txt_break-at300">
										<?= $doc->doctor_address; ?>
									</td>
									<td class="txt_break-at300">
										<?= $doc->doctor_fee; ?>
									</td>
									<td class="txt_break-at300">
										<?= $doc->gender; ?>
									</td>
									<!-- <td>
						<? //= word_limiter($doc->doctor_other_info, 4); 
						?>
					</td> -->
									<td class="txt_break-at300">
										<?php if ($doc->status == "Active") :
											echo '<span style="color:green">Active</span>';
										elseif ($doc->status == "InActive") :
											echo '<span style="color:red">InActive</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<!-- <a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_</?= $doc->id; ?>"><span class="fa fa-ellipsis-v"></span></a> -->
										</center>

										<!---Action Dropdown --->

									</td>

								</tr>

							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red;">Doctor Not Found</h6>
					<?php endif; ?>
				</table>
			</div>
		</div>

		<!---Body Section End--->


		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>