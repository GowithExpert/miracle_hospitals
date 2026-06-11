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
	<title>Doctor Email Registerd</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Css File Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="hedng1"><span class="fa fa-user-md col_blu"></span> Register Doctor Email</h5>
			</div>
			<?php if ($request_email) : ?>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Doctor Name</th>
							<th>Doctor Email</th>
							<th>Doctor Phone</th>
							<th>Status</th>
							<th>Request Date</th>
						</tr>
						<?php if ($request_email) :
							count($request_email);
							foreach ($request_email as $req_email) : ?>
								<tr>
									<td class="text-container_tody_appoint">
										<span class="break-word">
											<?= $req_email->doctor_name; ?>
										</span>
									</td>
									<td class="text-container_tody_appoint">
										<span class="break-word">
											<a class="colour_hver" href="mailto:<?= $req_email->doctor_email; ?>"><?= $req_email->doctor_email; ?></a>
										</span>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="tel:<?= $req_email->doctor_phone; ?>"><?= $req_email->doctor_phone; ?></a>
									</td>
									<td class="txt_break-at300 col_gren">
										<?= $req_email->status; ?>
									</td>
									<td class="txt_break-at300">
										<span> <?= date('D, M. d Y', strtotime($req_email->created_at)); ?></span>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="col_red h6_record">Email Not Found</h6>
						<?php endif; ?>
					</table>
				<?php else : ?>
					<h6 class="col_red padng h6_record">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>
	</div>
	<!---Body Section End --->
	<?= view('Admin/text_wrap_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>