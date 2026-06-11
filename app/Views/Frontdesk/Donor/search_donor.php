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
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<!----Css file Include --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!-----Body Section Start ---->
	<div class="equl_mrgn">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) { ?> <!-- Display the success message -->
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php // Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) { ?> <!-- Display the error message -->
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php // Remove the error message from session
					session()->removeTempdata('error');
					}
				?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fas fa-fire-alt col_blu"></span>  Manage Blood Donors</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				
					<div class="row">
						<div class="col l6 m6 s12">
							<?= form_open('Frontdesk/search_donor_details'); ?>
							<ul id="search_donors"> 
								<li>  	
									<!-- new hardcoded value -->
									<select name="blood_group" vale="<?= set_value('blood_group'); ?>">
										<option value="">All</option>
										<option value="A+">A+</option>
										<option value="A-">A-</option>
										<option value="B+">B+</option> 
										<option value="B-">B-</option>
										<option value="AB+">AB+</option>
										<option value="AB-">AB-</option>
										<option value="O+">O+</option>
										<option value="O-">O-</option>
									</select>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
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
						foreach ($all_donors as $donors) : ?>
							<tr>
								<td>
									<!-- <a class="tooltipped" data-position="top" data-tooltip="<? //= $donors['donor_name']; 
																									?>">
								 <img src="<? //= base_url().'public/uploads/donar_users/'.$donors['donor_image']; 
											?>" class="responsive-img" id="donor_image" height="50">
							 	</a> -->
									<center>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $donors['donor_name']; ?>">
											<?php
											if (isset($donors['donor_image']) && !empty($donors['donor_image'])) {
												if (file_exists(WRITEPATH . '.public/uploads/donar_users/' . $donors['donor_image'])) { ?>
													<img src="<?= base_url() . 'public/uploads/donar_users/' . $donors['donor_image']; ?>" class="responsive-img" id="profile_pic" height="50">

												<?php  } //Inner if - Closed
												else {  ?>
													<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Inner else - Closed

											} //Outer if - Closed

											else { ?>
												<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Outer else - Closed  
											?>
										</a>
									</center>
								</td>
								<td class="txt_break-at200 txt_align">
									<?= $donors['donor_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $donors['blood_group']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="link_hver" href="tel:<?= $donors['contact_number']; ?>"><?= $donors['contact_number']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $donors['address']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="col_gren">  <?= date('d M, Y, h:i:s', strtotime($donors['created_at'])); ?></span>
								</td>
								<td class="txt_break-at200 txt_align">
									<?php if ($donors['status'] == "Active") :
										echo '<span class="col_gren">Active</span>';
									elseif ($donors['status'] == "InActive") :
										echo '<span class="col_red">InActive</span>';
									?>
									<?php endif; ?>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $donors['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $donors['id']; ?>">
										<?php if (session()->has('google_user')) :

										?>
											<li><a href="<?= base_url('Blood_bank_donor/blood_req_google_users/' . $donors['id']); ?>"><span class="fa fa-eye col_blu"></span>  Request</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Blood_bank_donor/blood_request/' . $donors['id']); ?>"><span class="fa fa-eye col_blu"></span>  Request Blood</a></li>
										<?php endif; ?>

									</ul>
									<!---Action Dropdown --->
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red h6_record">No Record Found</h6>
					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" class="col_wite">
								<?= $pager->links(); ?>
							</div>
						</td>
					</tr>
				</table>
			
			</div>
		</div>
	</div>


	<script>
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
	<!-----Body Section End   ---->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>