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
	<title>Blood Bank Admin Verification</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="equl_mrgn">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-users col_blu"></span> Verify Blood Bank Admin Account</h5>
			</div>
			<div class="card-content" id="div_pad">
				<?php if ($bld_bnk_admin) : ?>
			</div>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Name</th>
							<th class="txt_align">UID</th>
							<th class="txt_align">Email</th>
							<th class="txt_align">Mobile</th>
							<th class="txt_align">Gender</th>
							<th class="txt_align">Level</th>
							<th class="txt_align">Date</th>
							<th class="txt_align">Status</th>
							<th class="txt_align">Action</th>
						</tr>
						<?php if ($bld_bnk_admin) :
							count($bld_bnk_admin);
							foreach ($bld_bnk_admin as $acc_account) : ?>
								<tbody>
									<tr>
										<td class="txt_break-at300">
											<span class="break-word">
												<?= $acc_account['username']; ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="break-word">
												<?= $acc_account['uid']; ?>
											</span>
										</td>
										<td class="text-container txt_align">
											<span class="break-word">
												<a class="colour_hver" href="mailto:<?= $acc_account['email']; ?>"><?= $acc_account['email']; ?></a>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<a class="colour_hver" href="tel:<?= $acc_account['mobile']; ?>"><?= $acc_account['mobile']; ?></a>
										</td>

										<td class="txt_break-at300 txt_align col_ornge">
											<?= $acc_account['gender']; ?>
										</td>
										<td class="txt_break-at300 txt_align">
											<?= $acc_account['level']; ?>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="col_gren"> <?= date('D, M. d Y', strtotime($acc_account['created_date'])); ?></span>
										</td>
										<td class="txt_break-at300 txt_align">
											<?php if ($acc_account['status']   == "Active") :
												echo '<span class="col_gren">Active</span>';
											elseif ($acc_account['status'] == "InActive") :
												echo '<span class="col_red">InActive</span>';
											elseif ($acc_account['status'] == "Deleted") :
												echo '<span class="col_red">Deleted</span>';
											?>
											<?php endif; ?>
										</td>
										<td>
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $acc_account['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
											</center>

											<!---Action Dropdown --->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $acc_account['id']; ?>">

												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $acc_account['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

												<?php if ($acc_account['status'] == "InActive") :  ?>
													<li><a href="<?= base_url('Admin/change_bld_admin_acc/' . $acc_account['id'] . '/Active'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash col_gren"></span> 
															Active</a></li>
												<?php else : ?>
													<li><a href="<?= base_url('Admin/change_bld_admin_acc/' . $acc_account['id'] . '/InActive'); ?>" id="dotted_border"><span class="fa fa-eye"></span> Inactive</a></li>
												<?php endif; ?>
											</ul>
											<div id="deleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to delete this Account?</p>
													<div class="modal-buttons align_del_btn">
														<button id="confirmDelete" class="modal-button delete">Delete</button>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							<?php endforeach; ?>
						<?php else : ?>
							<div class="card" id="div_pad">
								<div class="card-content div_red_back">
									<h6 class="h6_produ_nt_fon h6_record">
										<span class="fa fa-times"></span> Not any Verification for Blood Bank Admin
									</h6>
								</div>
							</div>
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
					<h6 class="col_red h6_record">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>
		<script>
			// Show the delete confirmation modal
			function showDeleteConfirmationModal(itemId) {
				const modal = document.getElementById("deleteConfirmationModal");
				modal.style.display = "block";

				const confirmDeleteButton = document.getElementById("confirmDelete");
				const cancelDeleteButton = document.getElementById("cancelDelete");

				// Add event listeners to the buttons
				confirmDeleteButton.onclick = function() {
					deleteItem(itemId);
				};

				cancelDeleteButton.onclick = function() {
					modal.style.display = "none";
				};
			}

			// Function to delete the item
			function deleteItem(itemId) {
				window.location.href = "<?= base_url('Admin/delete_bld_admin_account'); ?>/" + itemId + "/Deleted";
			}

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
		<?= view('Admin/text_wrap_js_file.php'); ?>
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>