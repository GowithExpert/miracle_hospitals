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
	<title>Manage Blood Transition</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!------Body Section Start ----->
	<div class="equl_mrgn">
		<div class="card">
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
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-bug col_blu"></span>  Manage Blood Transition</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($blood_rec) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12">
							<?= form_open('Blood_bank/search_hos_bld_user'); ?>
							<ul id="search_donors">
								<li>
									<select required="" name="username" id="username" value="<?= set_value('username'); ?>">
										<option selected="" disabled="">Search Name</option>
										<?php if ($blood_rec) :
											count($blood_rec);
											foreach ($blood_rec as $blood_group) : ?>
												<option value="<?= $blood_group['username']; ?>"><?= $blood_group['username']; ?></option>
											<?php endforeach; ?>
										<?php else : ?>
											<h6 class="h6_record h6_red_colr">User Not Found</h6>
										<?php endif; ?>
									</select>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn" data-target="donor_filters">
									<span class="fa fa-filter"></span>  Filter Blood
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="donor_filters">

								<li><a href="<?= base_url('Blood_bank/filter_hos_sale_bld/new_blood'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Customer </a></li>
								<li><a href="<?= base_url('Blood_bank/filter_hos_sale_bld/old_blood'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Customer </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Photo</th>
						<th class="txt_align">Name</th>
						<th class="txt_align">Mobile</th>
						<th class="txt_align">Email</th>
						<th class="txt_align">Blood Group</th>
						<th class="txt_align">Blood Unit</th>
						<th class="txt_align">Blood 1Unit Price</th>
						<th class="txt_align">Total Blood Price</th>
						<th class="txt_align">Buy date</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if ($blood_rec) :
						count($blood_rec);
						foreach ($blood_rec as $bld_rec) : ?>
							<tr>
								<td>
									<center>
									<a class="tooltipped" data-position="top" data-tooltip="<?= $bld_rec['username']; ?>">
										<?php
										if (isset($bld_rec['photo']) && !empty($bld_rec['photo'])) {
											if (file_exists(WRITEPATH . '..public/uploads/hospitalblood_cus/' .$bld_rec['photo'])) { ?>
												<img src="<?= base_url() . 'public/uploads/hospitalblood_cus/' . $bld_rec['photo']; ?>" class="responsive-img" id="profile_pic" height="50">
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
									<?= $bld_rec['username']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<a class="link_hver" href="tel:<?= $bld_rec['mobile']; ?>"><?= $bld_rec['mobile']; ?></a>
								</td>
								<td class="text-container txt_align">
									<a class="colour_hver" href="mailto:<?= $bld_rec['email']; ?>"><?= $bld_rec['email']; ?></a>
								</td>
								<td class="txt_break-at300 txt_align col_red">

									<?php
									if (is_array($blood_group)) { ?>
									<?php
										$get_blood_name = get_doctor_name('blood_group', $bld_rec ['blood_id']);
										echo $get_blood_name[0]->blood_group;
									} ?>

								</td>
								<td class="txt_break-at300 txt_align">
									<?= $bld_rec['blood_unit']; ?>
								</td>
								<td class="txt_break-at300 txt_align col_red">
									<span class="fa fa-rupee-sign"></span><?= number_format($bld_rec['blood_price']); ?>
								</td>
								<td class="txt_break-at300 txt_align col_gren">
									<?php
									$total_bld_prc = ((int)$bld_rec['blood_price'] * (int)$bld_rec['blood_unit']);
									?>
									<span class="fa fa-rupee-sign"></span><?= number_format($total_bld_prc); ?>
								</td>
								<td class="text-container txt_align">
									<span class="col_gren">  <?= date('d M, Y, h:i:s', strtotime($bld_rec['created_at'])); ?></span>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $bld_rec['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $bld_rec['id']; ?>">
										<li><a href="<?= base_url('Blood_bank/blood_selling_slip/' . $bld_rec['id']); ?>" id="dotted_border" target="_blank"><span class="fa fa-print"></span>  Print Slip</a></li>
									</ul>
									<!---Action Dropdown --->
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="h6_record col_red">Records Not Found</h6>
					<?php endif; ?>

				</table>
			<?php else : ?>
				<h6 class="h6_record col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>
	<script>
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
	</script>
	<!------Body Section End ----->
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	
</body>

</html>