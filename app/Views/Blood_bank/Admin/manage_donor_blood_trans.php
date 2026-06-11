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
	<title>Manage Donor's Blood Transition</title>
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

	<!------Body Section Start  ------->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-tasks col_blu"></span>  Manage Donor's Blood Sale Records</h5>
			</div>
			<?php if ($blood_sale_rec) : ?>
				<div class="card-content">
					<div class="row">
						<div class="col l6 m6 s6"></div>
						<div class="col l6 m6 s6">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn" data-target="donor_filters">
									<span class="fa fa-filter"></span>  Filter Sales
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="donor_filters">

								<li><a href="<?= base_url('Blood_bank/filter_sale_records/new_sale'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-bug col_blu"></span>  New Sale Records</a></li>
								<li><a href="<?= base_url('Blood_bank/filter_sale_records/old_sale'); ?>" class="waves-effect">
										<span class="fa fa-bug col_blu"></span>  Old Sale Records </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
				</div>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Image</th>
							<th>Username</th>
							<th>Mobile</th>
							<th>Address</th>
							<th>Email</th>
							<th>Blood Group</th>
							<th>Blood Unit</th>
							<th>Blood Price</th>
							<th>Selling date</th>
							<th>Donors</th>
						</tr>
						<?php if ($blood_sale_rec) :
							count($blood_sale_rec);
							foreach ($blood_sale_rec as $sale_rec1) : $sale_rec = (object)$sale_rec1 ?>
								<tr>
									<td>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $sale_rec->username; ?>">
											<img src="<?= base_url() . 'public/uploads/blood_buy_cus/' . $sale_rec->photo; ?>" class="responsive-img" id="donor_image" height="50">
										</a>
									</td>
									<td class="txt_break-at200">
										<?= $sale_rec->username; ?>
									</td>
									<td class="txt_break-at300">
										<a class="link_hver" href="tel:<?= $sale_rec->mobile; ?>"><?= $sale_rec->mobile; ?></a>
									</td>
									<td class="txt_break-at300">
										<?= $sale_rec->address; ?>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="mailto:<?= $sale_rec->email; ?>"><?= $sale_rec->email; ?></a>
									</td>
									<td class="txt_break-at300 col_red">
										<?= $sale_rec->blood_group_sale; ?>
									</td>
									<td class="txt_break-at300 col_ornge">
										<?= $sale_rec->blood_unit; ?>
									</td>
									<td class="txt_break-at300 col_gren">
										<span class="fa fa-rupee-sign">  <?= number_format($sale_rec->blood_price); ?></span>
									</td>
									<td class="txt_break-at300">
										<span class="fa fa-clock col_gren">  <?= date('d M, Y, h:i:s', strtotime($sale_rec->created_at)); ?></span>
									</td>
									<td class="txt_break-at300">
										<?php
										$get_donors_details = get_doctor_name('buy_donor_blood', $sale_rec->blood_id);
										if ($get_donors_details) :
											count($get_donors_details);
											foreach ($get_donors_details as $donor_detials) :
												$get_donors = get_doctor_name('blood_donor', $donor_detials->blood_donor_id);
										?>

												<center>
													<a class="tooltipped" data-position="top" data-tooltip="<?= $get_donors[0]->donor_name; ?>">
														<img src="<?= base_url() . 'public/uploads/donar_users/' . $get_donors[0]->donor_image; ?>" class="responsive-img" id="donor_image" height="50">
													</a>
												</center>
											<?php endforeach; ?>
										<?php else : ?>
											<h6 class="h6_record h6_red_colr">Donor Details Not Found</h6>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="h6_record h6_red_colr">Records Not Found</h6>
						<?php endif; ?>
					</table>
				<?php else : ?>
					<h6 class="h6_record pdng col_red">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>
	</div>
	<!------Body Section End   ------->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>