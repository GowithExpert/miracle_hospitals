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
	<title>Search Donor</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<style>
		h6 {
			position: relative;
			top: 73%;
			left: 50%;
			transform: translate(-50%, -50%);
		}
	</style>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!-----Body Section Start ---->
	<div style="margin-right: 15px;margin-left: 15px;">
		<!--- Success/Failure Msg -START --->
		<div style="position: relative;">
			<?php if (session()->getTempdata('success')) : ?>
				<div class="card success-message cutom_messge_styl">
					<div class="card-content" id="succss_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
			<?php endif; ?>
			<?php if (session()->getTempdata('error')) : ?>
				<div class="card error-message cutom_messge_styl">
					<div class="card-content" id="error_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
			<?php endif; ?>
		</div>
        <!--- Success/Failure Msg END--->
		<div class="card">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fas fa-fire-alt" style="color: #005197"></span>  Manage Blood Donors</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding:10px">
				<?php if ($all_donors) : ?>
					<div class="row">
						<div class="col l6 m6 s12">
							<?= form_open('Blood_bank_donor/search_donor_details'); ?>
							<ul id="search_donors">
								<li>
									<select required="" name="blood_group" vale="<?= set_value('blood_group'); ?>">
										<option selected="" disabled="">Select Blood Group</option>
										<?php if ($all_donors) :
											count($all_donors);
											foreach ($all_donors as $blood_group) : ?>
												<option value="<?= $blood_group['blood_group']; ?>"><?= $blood_group['blood_group']; ?></option>
											<?php endforeach; ?>
										<?php else : ?>
											<h6 style="color: red;font-weight: 500;font-size: 16px;">Blood Group Not Found</h6>
										<?php endif; ?>
									</select>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver" style="background: #005197;text-transform: capitalize;height: 40px;margin-left: 19px ">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>

					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="event" style="background: #f2f2f2;">
						<th class="txt_align">Image</th>
						<th class="txt_align">Donor Name</th>
						<th class="txt_align">Blood Group</th>
						<th class="txt_align">Contact Number</th>
						<th class="txt_align">Address</th>
						<th class="txt_align">Created Date</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Request</th>
					</tr>
					<?php if ($all_donors) :
						count($all_donors);
						foreach ($all_donors as $search_donors) : ?>
							<tr>
								<td>
									<!-- <a class="tooltipped" data-position="top" data-tooltip="<? //= $search_donors['donor_name']; 
																									?>">
								 <img src="<? //= base_url().'public/uploads/donar_users/'.$search_donors['donor_image']; 
											?>" class="responsive-img" id="donor_image" height="50">
							 	</a> -->
									<center>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $search_donors['donor_name']; ?>">
											<?php
											if (isset($search_donors['donor_image']) && !empty($search_donors['donor_image'])) {
												if (file_exists(WRITEPATH . '.public/uploads/donar_users/' . $search_donors['donor_image'])) { ?>
													<img src="<?= base_url() . 'public/uploads/donar_users/' . $search_donors['donor_image']; ?>" class="responsive-img" id="profile_pic" height="50">

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
									</center>




								</td>
								<td class="txt_break-at200 txt_align">
									<?= $search_donors['donor_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $search_donors['blood_group']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="btn_hver1" href="tel:<?= $search_donors['contact_number']; ?>"><?= $search_donors['contact_number']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $search_donors['address']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="" style="color: green">  <?= date('d M, Y, h:i:s', strtotime($search_donors['created_at'])); ?></span>
								</td>
								<td class="txt_break-at200 txt_align">
									<?php if ($search_donors['status'] == "Active") :
										echo '<span style="color:green">Active</span>';
									elseif ($search_donors['status'] == "InActive") :
										echo '<span style="color:red">InActive</span>';
									?>
									<?php endif; ?>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $search_donors['id']; ?>" style="text-transform: capitalize;font-weight: 500"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $search_donors['id']; ?>">
										<?php if (session()->has('google_user')) :

										?>
											<li><a href="<?= base_url('Blood_bank_donor/blood_req_google_users/' . $search_donors['id']); ?>"><span class="fa fa-eye" style="color: #005197;"></span>  Request</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Blood_bank_donor/blood_request/' . $search_donors['id']); ?>"><span class="fa fa-eye" style="color: #005197;"></span>  Request Blood</a></li>
										<?php endif; ?>

									</ul>
									<!---Action Dropdown --->
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red">Not Any Donors</h6>
					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" style="color: white">
								<?= $pager->links(); ?>
							</div>
						</td>
					</tr>
				</table>
			<?php else : ?>
				<h6 style="color: red;font-size:14px">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<!-----Body Section End   ---->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>