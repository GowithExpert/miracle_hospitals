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
	<title>Blood Bank</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fas fa-fire-alt col_blu"></span>  Blood Bank</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($blood_bank) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12">
							
							<?= form_open('Frontdesk/search_hos_bld_user'); ?>
							<ul id="search_donors">
								<li>
									<select required="" name="blood_group" id="username" value="<?= set_value('username'); ?>">
										<option selected="" disabled="">Search Blood Group</option>
										<?php if ($blood_bank) :
											count($blood_bank);
											foreach ($blood_bank as $blood_group) : ?>
												<option value="<?= $blood_group->blood_group; ?>"><?= $blood_group->blood_group; ?></option>
											<?php endforeach; ?>
										<?php else : ?>
											<h6 class="h6_red_colr h6_record">User Not Found</h6>
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
						<th class="txt_align">Blood Unit</th>
						<th class="txt_align">Blood Price 1 Unit</th>

					</tr>
					<?php if ($blood_bank) :
						count($blood_bank);
						foreach ($blood_bank as $blood) : ?>
							<tr>
								<td class="txt_align col_red"><?= $blood->blood_group; ?></td>
								<td class="txt_align col_ornge"><?= $blood->blood_unit; ?></td>
								<td class="txt_align col_gren">
									<span class="fa fa-rupee-sign">  <?= number_format($blood->blood_price); ?></span>
								</td>

							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="h6_red_colr h6_record">Blood Not Available</h6>
					<?php endif; ?>
				</table>
			<?php else : ?>
				<h6 class="h6_red_colr h6_record">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
	</div>

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>	
	</script>
</body>

</html>