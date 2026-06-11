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
	<title>Blood Bank Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!------Body Section Start ----->
	<div class="row">
		<div class="col l4 m12 s12 equl_padng">
			<div class="card bckgrnd_red">
				<a href="<?= base_url('Blood_bank/manage_blood'); ?>" class="h6_sty col_wite">
					<div class="card-content hgth_129">
						<div class="row mrg_botm">
							<div class="col l4 m4 s4">
								<h4><span class="fa fa-tasks col_wite"></span></h4>
							</div>
							<div class="col l8 m8 s8">
								<h6 class="right-align h6_sty col_wite">All Blood Group</h6>
								<h4 class="right-align h6_sty col_wite">
									<?php if ($blood_available) :
										echo count($blood_available);
									?>
									<?php else : ?>
										<h6 class="h6_sty col_wite txt_rgt">0</h6>
									<?php endif; ?>
								</h4>

							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="col l4 m12 s12 equl_padng">
			<div class="card bck_blu">
				<a href="<?= base_url('Blood_bank/donor_details'); ?>" class="h6_sty col_wite">
					<div class="card-content hgth_129">
						<div class="row mrg_botm">
							<div class="col l4 m4 s4">
								<h4><span class="fa fa-users col_wite"></span></h4>
							</div>
							<div class="col l8 m8 s8">
								<h6 class="right-align h6_sty col_wite">Total Blood Donors</h6>
								<h4 class="right-align h6_sty col_wite">
									<?php if ($donor_details) :
										echo count($donor_details); ?>
									<?php else : echo "0"; ?>
									<?php endif; ?>

								</h4>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="col l4 m12 s12 equl_padng">
			<div class="card bckgrnd_gren">
				<a href="<?= base_url('Blood_bank/manage_blood_transition'); ?>" class="h6_sty col_wite">
					<div class="card-content hgth_129">
						<div class="row mrg_botm">
							<div class="col l4 m4 s4">
								<h4><span class="fa fa-users col_wite"></span></h4>
							</div>
							<div class="col l8 m8 s8">
								<h6 class="right-align h6_sty col_wite">Total Blood Buyers</h6>
								<h4 class="right-align h6_sty col_wite">
									<?php
										if ($donor_blood_sale) :
											$count = count($donor_blood_sale);
										else :
											$count = 0;
											echo "0";
										endif;
									?>
										<?php if ($has_blood_sale) :
											$count_two = count($has_blood_sale);
											$total_sale = $count + $count_two;
											echo number_format($total_sale);
										?>
										<?php else : echo "0"; ?>
										<?php endif; ?>
								</h4>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div class="equl_mrgn">
		<div class="card">
			<div class="bld_dnr_hedr">
				<h5 class="col_wite"><span class="fa fa-bug col_wite"></span>  Blood Donor's Details</h5>
			</div>
			<div class="card-content" id="div_pad">
				<?php if ($donor_details) : ?>
			</div>
					<div class="scroll-container card-content">
						<table class="table">
							<tr class="backgrnd_colr_gray">
								<th class="txt_align">Image</th>
								<th class="txt_align">Donor Name</th>
								<th class="txt_align">Blood Group</th>
								<th class="txt_align">Contact Number</th>
								<th class="txt_align">Address</th>
								<th class="txt_align">Status</th>
								<th class="txt_align">Created Date</th>
								<th class="txt_align">Request</th>
							</tr>
							<?php if ($donor_details) :
								count($donor_details);
								foreach ($donor_details as $search_donors) : ?>
									<tr>
										<td>
											<!-- <a class="tooltipped" data-position="top" data-tooltip="<? //= $search_donors->donor_name; 
																											?>">
								 <img src="<? //= base_url().'public/uploads/donar_users/'.$search_donors->donor_image; 
											?>" class="responsive-img" id="donor_image" height="50">
							 	</a> -->

											<center>
												<a class="tooltipped" data-position="top" data-tooltip="<?= $search_donors->donor_name; ?>">
													<?php
													if (isset($search_donors->patient_image) && !empty($search_donors->donor_image)) {
														if (file_exists(WRITEPATH . 'public/uploads/donar_users/' . $search_donors->donor_image)) { ?>
															<img src="<?= base_url() . 'public/uploads/donar_users/' . $search_donors->donor_image; ?>" class="responsive-img" id="profile_pic" height="50">
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
											<?= $search_donors->donor_name; ?>
										</td>
										<td class="txt_break-at300 txt_align col_red">
											<?= $search_donors->blood_group; ?>
										</td>
										<td class="txt_break-at300 txt_align">
											<a class="colour_hver" href="tel:<?= $search_donors->contact_number; ?>"><?= $search_donors->contact_number; ?></a>
										</td>
										<td class="txt_break-at300 txt_align">
											<?= $search_donors->address; ?>
										</td>
										<td class="txt_break-at300 txt_align">
											<?php if ($search_donors->status == "Active") :
												echo '<span class="col_gren">Active</span>';
											elseif ($search_donors->status == "InActive") :
												echo '<span class="col_red">InActive</span>';
											?>
											<?php endif; ?>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="col_gren">  <?= date('d M, Y, h:i:s', strtotime($search_donors->created_at)); ?></span>
										</td>
										<td>
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $search_donors->id; ?>"> <span class="fa fa-ellipsis-v"></span></a>
											</center>

											<!---Action Dropdown --->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $search_donors->id; ?>">
												<li><a href="<?= base_url('Blood_bank/blood_response_data/' . $search_donors->id); ?>"><span class="fa fa-eye col_blu"></span>  Response</a></li>
											</ul>
											<!---Action Dropdown --->
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<h6 class="col_red">Not Any Donors</h6>
							<?php endif; ?>
						</table>
					<?php else : ?>
						<h6 class="col_red">No Record Found</h6>
					<?php endif; ?>
					</div>
		</div>
	</div>


	<!------Body Section Start ----->
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>