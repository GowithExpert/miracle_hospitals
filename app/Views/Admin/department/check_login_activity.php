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
	<title>Check Login Activity</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-history col_blu"></span>   Login Activity</h5>
			</div>
			<?php if ($login_activity) : ?>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Name</th>
							<th>Browser</th>
							<th>IP</th>
							<th>Doctor Image</th>
							<th>Login Time</th>
							<th>Logout Time</th>
						</tr>
						<?php if ($login_activity) :
							count($login_activity);
							foreach ($login_activity as $login) : ?>
								<tr>
									<td class="text-container_tody_appoint">
										<span class="break-word">
											<?php
											$login_data = get_login_activity('register_all_users', $login->uniid);
											if (isset($login_data['0']->{'username'})) {
												if (is_array($login_data)) echo $login_data['0']->{'username'};
											}
											?></span>
									</td>
									<td class="txt_break-at300">
										<?= $login->browser; ?>

									</td>
									<td class="txt_break-at300">
										<?= $login->ip; ?>
									</td>
									<td class="text-container_tody_appoint">
										<span class="break-word">
											<?php
											if (isset($login_data['0']->{'email'})) {
												if (is_array($login_data)) {
													$login_profile_pic = login_profile_pic('doctor', $login_data[0]->{'email'});
												}
											}

											if (isset($login_profile_pic[0]->{'profile_pic'})) {
												if (is_array($login_profile_pic)) { ?>
													<a class="tooltipped" data-position="top" data-tooltip="<?= $login_profile_pic[0]->doctor_name; ?>">
														<img src="<?= base_url() . 'public/uploads/doctor/' . $login_profile_pic[0]->{'profile_pic'}; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php }
											}
												?>
													</a></span>
									</td>
									<td class="txt_break-at300">
										<span class="col_gren"><?= date('d M, Y, h:i:s', strtotime($login->login_time)); ?>
									</td>
									<td class="txt_break-at300">
										<span class="col_gren"><?= date('d M, Y, h:i:s', strtotime($login->logout_time)); ?>
									</td>

								</tr>
							<?php endforeach; ?>
						<?php else : ?>
						<?php endif; ?>
					</table>
				<?php else : ?>
					<h6 class="h6_record col_red pdng">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>
	</div>

	<?= view('Admin/text_wrap_js_file.php'); ?>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>