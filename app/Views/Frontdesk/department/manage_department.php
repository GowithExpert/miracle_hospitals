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
	<title>Manage Department</title>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
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
			font-size: 15px;
			font-weight: 500
		}

		.tooltip {
			position: relative;
			display: inline-block;
		}

		.tooltip .tooltiptext {
			visibility: hidden;
			width: 140px;
			background-color: #005197;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 5px 0;
			position: absolute;
			z-index: 1;
			top: 100%;
			left: 50%;
			margin-left: -70px;

			/* Fade in tooltip - takes 1 second to go from 0% to 100% opac: */
			opacity: 0;
			transition: opacity 1s;
		}

		.tooltip:hover .tooltiptext {
			visibility: visible;
			opacity: 1;
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start -->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-tasks" style="color: #005197"></span>  Manage Department</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l8 m8 s12">
						<?= form_open('Frontdesk/search_department'); ?>
						<ul id="search_doctor">
							<li>
								<input type="text" name="department_name" id="input_box" value="<?= set_value('department_name'); ?>" placeholder="Enter Department Name" required="">
							</li>
							<li>
								<button type="submit" class="btn waves-effect waves-light" style="background: #005197;text-transform: capitalize;height: 40px; margin-left: 9px">Search Now</button>
							</li>
						</ul>
						<?= form_close(); ?>
					</div>
					
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th>Department Name</th>
						<th>Description</th>
						<th>Status</th>
						<th>Created Date</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($department) :
						count($department);
						foreach ($department as $doc_fee) : ?>

							<tbody>
								<tr>
									<td class="txt_break-at200">
										<?= $doc_fee->department_name; ?>
									</td>
									<td class="txt_break-at300">
										<?= $doc_fee->dep_desc; ?>
									</td>
									<td class="txt_break-at300">
										<!-- <?= $doc_fee->status; ?> -->
										<?php if ($doc_fee->status == "Active") :
											echo '<span style="color:green">Active</span>';
										elseif ($doc_fee->status == "InActive") :
											echo '<span style="color:red">InActive</span>';
										?>
										<?php endif; ?>
									</td>
									<td class="txt_break-at300">
										<span class="" style="color: green">
											<?= date('d M, Y', strtotime($doc_fee->created_at)); ?>
										</span>

									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $doc_fee->id; ?>"><span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $doc_fee->id; ?>">
											<li><a href="<?= base_url('Frontdesk/edit_department/' . $doc_fee->id); ?>" style="border-bottom: 1px dashed silver"><span class="fa fa-edit" style="color: #005197;"></span>  Edit</a></li>

											<li><a href="<?= base_url('Frontdesk/delete_department/' . $doc_fee->id); ?>" onclick="return confirm('Are you sure you want to  delete this Department Details?..');" style="border-bottom: 1px dashed silver"><span class="fa fa-trash" style="color: red;"></span>  Delete</a></li>

											<?php if ($doc_fee->status == "Active") :  ?>
												<li><a href="<?= base_url('Frontdesk/change_department_status/' . $doc_fee->id . '/InActive'); ?>">
														<span class="fa  fa-eye-slash" style="color: red"></span>  
														InActive</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Frontdesk/change_department_status/' . $doc_fee->id . '/Active'); ?>"><span class="fa fa-eye" style="color: #005197"></span>  Active</a></li>
											<?php endif; ?>
										</ul>
										<!---Action Dropdown --->
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red">Department Record Not Found</h6>
					<?php endif; ?>
				</table>
			</div>
		</div>

	</div>
	<!---Body Section End -->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>