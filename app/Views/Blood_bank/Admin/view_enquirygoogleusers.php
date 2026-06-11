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
	<title>View Enquiry Google Users</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!----Body Section Start  ----->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-users col_blu"></span>  View Enquiry Google Users </h5>
			</div>
			<?php if ($req_users) : ?>
				<div class="card-content" id="brdr_botm_silvr">
					<div class="row">
						<div class="col l6 m6 s12"></div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn btn_hver" data-target="donor_filters">
									<span class="fa fa-filter">  Filter Blood Need users</span>
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="donor_filters">

								<li><a href="<?= base_url('Blood_bank/filter_google_user_nedded/new_user'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Users </a></li>
								<li><a href="<?= base_url('Blood_bank/filter_google_user_nedded/old_user'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Users </a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Profile Pic</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>
							<th>Donor Image</th>
							<th>Donor Name</th>
							<th>Donor Mobile</th>
							<th>Blood Group</th>
							<th>Donor Address</th>
							<th>Requested date</th>
						</tr>
						<?php if ($req_users) :
							count($req_users);
							foreach ($req_users as $needed_users) : ?>
								<tr>
									<td>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $needed_users->request_user; ?>">
											<img src="<?= $needed_users->profile_pic; ?>" class="responsive-img" id="donor_image" height="50">
										</a>
									</td>
									<td class="txt_break-at200">
										<?= $needed_users->request_user; ?>
									</td>
									<td class="txt_break-at200">
										<?= $needed_users->last_name; ?>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="mailto:<?= $needed_users->request_user_email; ?>"><?= $needed_users->request_user_email; ?></a>
									</td>
									<td class="txt_break-at300">
										<?php
										$get_donors = get_doctor_name('blood_donor', $needed_users->blood_donor_id);
										?>
										<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $get_donors[0]->donor_name; ?>">
												<img src="<?= base_url() . 'public/uploads/donar_users/' . $get_donors[0]->donor_image; ?>" class="responsive-img" id="donor_image" height="50">
											</a>
										</center>
									</td>
									<td class="txt_break-at300">
										<?= $get_donors[0]->donor_name; ?>
									</td>
									<td class="txt_break-at300">
										<a class="link_hver" href="tel:<?= $get_donors[0]->contact_number; ?>"><?= $get_donors[0]->contact_number; ?></a>
									</td>
									<td class="txt_break-at300 col_red">
										<?= $get_donors[0]->blood_group; ?>
									</td>
									<td class="txt_break-at300">
										<?= word_limiter($get_donors[0]->address, 4); ?>
									</td>
									<td class="txt_break-at300">
										<span class="col_gren">  <?= date('d M, Y', strtotime($needed_users->request_date)); ?></span>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="h6_red_colr">Not any Blood Inquiry</h6>
						<?php endif; ?>
					</table>
				<?php else : ?>
					<h6 class="pdng col_red">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>
	</div>
	<!----Body Section End  ----->
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>