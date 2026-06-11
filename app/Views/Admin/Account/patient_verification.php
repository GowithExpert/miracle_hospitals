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
	<title>Doctor Verification</title>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include  -->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!----Style Body Section Start --->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-users" style="color: #005197"></span> Verify Doctor Account</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12">

					</div>
					<div class="col l6 m6 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter"> Filter Doctor Account</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">

							<li><a href="<?= base_url('Admin/filter_doctor_verification/new_doc_account'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span> New Doctor Accountant </a></li>
							<li><a href="<?= base_url('Admin/filter_doctor_verification/old_doc_account'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span> Old Doctor Accountant</a></li>
						</ul>
					</div>
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th style="text-align: center;">Name</th>
						<th>UID</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Gender</th>
						<th>Level</th>
						<th>Status</th>
						<th>Created Date</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($patients) :
						count($patients);
						//echo "<pre>"; print_r($patients);die;
						foreach ($patients as $patient_account) : ?>
							<tbody>
								<tr>
									<td>
										<center>
											<?= $patient_account['username']; ?>
										</center>
									</td>
									<td>
										<?= $patient_account['uid']; ?>
									</td>
									<td>
										<a href="mailto:<?= $patient_account['email']; ?>"><?= $patient_account['email']; ?></a>
									</td>
									<td>
										<a href="tel:<?= $patient_account['mobile']; ?>"><?= $patient_account['mobile']; ?></a>
									</td>
									<td style="color: orange">
										<?= $patient_account['gender']; ?>
									</td>
									<td>
										<?= $patient_account['level']; ?>
									</td>
									<td>
										<?php if ($patient_account['status']   == "Active") :
											echo '<span style="color:green">Active</span>';
										elseif ($patient_account['status'] == "InActive") :
											echo '<span style="color:red">Inactive</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<span class="fa fa-clock" style="color: green"> <?= date('D, M. d Y', strtotime($patient_account['created_date'])); ?></span>
									</td>
									<td>
										<center>
											<a href="#!" class="btn  btn-waves-effect waves-light dropdown-trigger" data-target="action_dropdown_<?= $patient_account['id']; ?>" style="background: #005197;text-transform: capitalize;font-weight: 500"> Action</a>
										</center>

										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $patient_account['id']; ?>">

											<li><a href="<?= base_url('Admin/delete_doc_account/' . $patient_account['id']); ?>" onclick="return confirm('Are you sure you want to  delete this Verification?..');"><span class="fa fa-trash" style="color: red;"></span> Delete</a></li>

											<?php  
											?>
											<?php if ($patient_account['status'] == "InActive") :  ?>
												<li><a href="<?= base_url('Admin/change_doc_acc_status/' . $patient_account['id'] . '/Active'); ?>">
														<span class="fa  fa-eye-slash" style="color: green"></span> Verify</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Admin/change_doc_acc_status/' . $patient_account['id'] . '/InActive'); ?>"><span class="fa fa-eye" style="color: #005197"></span> InActive</a></li>
											<?php endif; ?>
										</ul>
										<!---Action Dropdown --->
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="card" style="padding: 5px">
							<div class="card-content" style="background: red;padding: 3px;">
								<h6 style="color: white;font-weight: 500;margin-left: 20px;">
									<span class="fa fa-times"></span> Not any Verification for Doctor
								</h6>
							</div>
						</div>
					<?php endif; ?>
				</table>
			</div>
		</div>
		<!----Style Body Section End --->

		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>