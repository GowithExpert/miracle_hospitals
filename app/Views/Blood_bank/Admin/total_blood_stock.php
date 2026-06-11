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
	<title>Total Blood Stock</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fas fa-fire-alt col_blu"></span>  Manage Total Blood Stock Records</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($blood_stock) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12">
							<?= form_open('Blood_bank/search_sale_bld_stock'); ?>
							<ul id="search_donors">
								<li>
									<select required="" name="blood_group" class="dis_blk" id="blood_group" value="<?= set_value('blood_group'); ?>">
										<option selected="" disabled="">---Select Blood Group ---</option>
										<?php if ($blood_stock) :
											count($blood_stock);
											foreach ($blood_stock as $blood_group) : ?>
												<option value="<?= $blood_group->blood_group; ?>"><?= $blood_group->blood_group; ?></option>
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
						<div class="col l6 m6 s12"></div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Blood Group</th>
						<th class="txt_align">Total Blood Unit</th>
						<th class="txt_align">Total Blood Sale</th>
						<th class="txt_align">Blood Unit In Stock</th>
						<th class="txt_align">Blood Sale Price</th>
						<th class="txt_align">Total Blood Price In Stock</th>
					</tr>
					<?php
					if ($blood_stock) :
						count($blood_stock);
						$total_bld_prc = 0; //For addressing notices
						foreach ($blood_stock as $bld_stock) :
							if (isset($bld_stock->blood_id)) {
								$get_blood_name = get_doctor_name('blood_group', $bld_stock->blood_id);
							}
					?>
							<tr>
								<td class="txt_break-at200 txt_align">
									<?php if (isset($bld_stock->blood_group)) {
										echo $bld_stock->blood_group;
									} ?>
								</td>
								<td class="txt_break-at300 txt_align col_ornge">
									<?php if (is_array($get_blood_name) && isset($get_blood_name[0]->blood_unit)) {
										echo  $get_blood_name[0]->blood_unit;
									} ?>
								</td>
								<td class="txt_break-at300 txt_align col_gren">
									<?php if (is_array($get_blood_name) && isset($get_blood_name[0]->blood_unit)) {
										echo  $get_blood_name[0]->blood_unit;
									} ?>
									<?= $bld_stock->blood_unit; ?>
								</td>
								<td class="txt_break-at300 txt_align col_red">
									<?php
									if (is_array($get_blood_name) && isset($get_blood_name[0]->blood_unit) && isset($bld_stock->blood_unit)) {
										$blood_in_stock = (((int)($get_blood_name[0]->blood_unit)) * ((int)$bld_stock->blood_unit));
										echo $blood_in_stock;
									}
									?>
								</td>
								<td class="txt_break-at300 txt_align col_gren">
									<?php
									if (isset($bld_stock->blood_price) && isset($bld_stock->blood_unit)) {
										if (is_numeric($bld_stock->blood_price) && is_numeric($bld_stock->blood_unit)) {
											$total_bld_prc += $bld_stock->blood_price * $bld_stock->blood_unit;
										}
									}
									?>
									<span class="fa fa-rupee-sign"></span><?= number_format($total_bld_prc); ?>
								</td>
								<td class="txt_break-at300 txt_align col_red">
									<?php
									if (is_array($total_bld_prc)) { ?>
									<?php
										$bld_prc_in_stock = ($get_blood_name[0]->total_blood_price - $total_bld_prc);
									}
									?>
									<?php
									if (isset($bld_prc_in_stock)) { ?>

										<span class="fa fa-rupee-sign"></span><?= number_format($bld_prc_in_stock);
																			} ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="h6_record h6_red_colr">Records Not Found</h6>
					<?php endif; ?>
				</table>
			<?php else : ?>
				<h6 class="h6_record h6_red_colr">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	
</body>

</html>