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
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Frontdesk/frontdesk_css_file.php'); ?>
	<!----Css file Include --->
	<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/select2/dist/css/select2.min.css'); ?>">
	<style type="text/css">
		tr td {
			font-weight: 500;
			font-size: 16px;
		}

		#search_donors {
			display: flex;
		}

		#search_donors li:first-child {
			width: 300px
		}

		select {
			display: block;
		}

		h6 {
			font-size: 14px;
			font-weight: 600 !important;
		}

		@media (max-width: 768px) {
			.scroll-container {
				overflow-x: auto;
				white-space: nowrap;
			}
		}

		select {
			height: 40px !important;
		}
	</style>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<div style="margin-left: 15px;margin-right: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 600 !important"><span class="fa fa-users" style="color: #005197"></span>  Blood Bank</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12">
						<?= form_open('Blood_bank_donor/search_hos_bld_user'); ?>
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
										<h6 style="color: red;font-weight: 500;font-size: 16px;">User Not Found</h6>
									<?php endif; ?>
								</select>
							</li>
							<li>
								<button type="submit" class="btn waves-effect waves-light" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 40px; margin-left: 9px">Search Now</button>
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
					<tr class="event" style="background: #f2f2f2;">
						<th style="text-align: center;">Blood Group</th>
						<th>Blood Unit</th>
						<th>Blood Price 1 Unit</th>

					</tr>
					<?php if ($blood_bank) :
						count($blood_bank);
						foreach ($blood_bank as $blood) : ?>
							<tr>
								<td style="text-align: center;color: red"><?= $blood->blood_group; ?></td>
								<td style="color: orange"><?= $blood->blood_unit; ?></td>
								<td style="color: green">
									<span class="fa fa-rupee-sign">  <?= number_format($blood->blood_price); ?></span>
								</td>

							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red;font-weight: 500;font-size: 16px;">Blood Not Available</h6>
					<?php endif; ?>
				</table>
			</div>
		</div>
	</div>

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<script type="text/javascript" src="<?= base_url('public/assets/select2/dist/js/select2.min.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			// Initialize select2
			$("#username").select2();

		});
	</script>
</body>

</html>