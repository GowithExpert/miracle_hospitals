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
	<title>Contact us</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Included ---->
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
				<h5 class="font_weght"><span class="fa fa-share col_blu"></span> Manage Messages</h5>
			</div>
			<?php if ($contact_us) : ?>
				<div class="scroll-container card-content brd_botm">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Name</th>
							<th class="txt_align">Email</th>
							<th class="txt_align">Mobile</th>
							<th class="txt_align">Subject</th>
							<th class="txt_align">Message</th>
						</tr>
						<?php if ($contact_us) :
							count($contact_us);
							foreach ($contact_us as $contact) : ?>
								<tr>
									<td class="txt_break-at200">
										<?= $contact->name; ?>
									</td>
									<td class="txt_break-at300 txt_align">
										<span class="break-word">
											<a class="colour_hver" href="mailto:<?= $contact->email; ?>"><?= $contact->email; ?></a>
										</span>
									</td>
									<td class="txt_break-at300 txt_align">
										<a class="colour_hver" href="tel:<?= $contact->mobile; ?>"><?= $contact->mobile; ?></a>
									</td>
									<td class="txt_break-at300 txt_align">
										<span class="break-word">
											<?= $contact->subject; ?>
										</span>
									</td>
									<td class="txt_break-at300 txt_align">
										<span class="break-word">
											<?= $contact->message; ?>
										</span>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
						<?php endif; ?>
						<tr>
							<td colspan="7">
								<div id="pagination" class="col_wite">
									<?php if (isset($pager)) {
										echo $pager->links();
									} ?>
								</div>
							</td>
						</tr>
					</table>
				<?php else : ?>
					<h6 class="h6_record col_red pdng">No record Found</h6>
				<?php endif; ?>
				</div>
		</div>

		<?= view('Admin/text_wrap_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>