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
	<title>Manage Blood Group</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fas fa-fire-alt col_blu"></span>  Manage Blood Group</h5>
			</div>
			<?php if ($blood_group) : ?>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th class="txt_align">Blood Group</th>
							<th class="txt_align">Blood Unit</th>
							<th class="txt_align">Blood Price 1 Unit</th>
							<th class="txt_align">Total Blood Price </th>
							<th class="txt_align">Created Date</th>
							<th class="txt_align">Status</th>
							<th class="txt_align">Action</th>
						</tr>
						<?php if ($blood_group) :
							count($blood_group);
							foreach ($blood_group as $blood) : ?>
								<tr>
									<td class="txt_break-at200 txt_align col_red"><?= $blood['blood_group']; ?></td>
									<td class="txt_break-at300 txt_align col_ornge"><?= $blood['blood_unit']; ?></td>
									<td class="txt_break-at300 txt_align col_gren">
										<span class="fa fa-rupee-sign">  <?= number_format($blood['blood_price']); ?></span>
									</td>
									<td class="txt_break-at300 txt_align col_gren">
										<span class="fa fa-rupee-sign">  <?= number_format($blood['total_blood_price']); ?></span>
									</td>

									<td class="txt_break-at300 txt_align">
										<span class="col_blu">  <?= date('d M, Y', strtotime($blood['created_at'])); ?></span>
									</td>
									<td class="txt_break-at300 txt_align">
										<?php if ($blood['status'] == "Active") :
											echo '<span class="col_gren">Active</span>';
										elseif ($blood['status'] == "InActive") :
											echo '<span class="col_red">InActive</span>';
										elseif ($blood['status'] == "Deleted") :
											echo '<span class="col_red">Deleted</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $blood['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
										</center>

										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $blood['id']; ?>">


											<?php if ($blood['status'] == "Active") :  ?>
												<li><a href="<?= base_url('Blood_bank/change_blood_status/' . $blood['id'] . '/InActive'); ?>" id="dotted_border">
														<span class="fa  fa-eye-slash"></span>  
														InActive</a></li>
												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blood['id']; ?>);" style="color:red;" id="dotted_border"><span class="fa fa-trash col_red"></span>  Delete</a></li>

											<?php elseif ($blood['status'] == "InActive") : ?>
												<li><a href="<?= base_url('Blood_bank/change_blood_status/' . $blood['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
												<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blood['id']; ?>);" style="color:red;" id="dotted_border"><span class="fa fa-trash col_red"></span>  Delete</a></li>

											<?php elseif ($blood['status'] == "Deleted") : ?>
												<li><a href="<?= base_url('Blood_bank/change_blood_status/' . $blood['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>

												<li><a href="<?= base_url('Blood_bank/permanent_del_bld_group/' . $blood['id']); ?>" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Blood_bank/change_blood_status/' . $blood['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>

											<?php endif; ?>
										</ul>
										<!---Action Dropdown --->
										<!-- Hidden delete confirmation modal -->
										<div id="deleteConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
												<p class="del_pop_text">Are you sure you want to delete this Blood Group?</p>
												<div class="modal-buttons align_del_btn">
													<button id="confirmDelete" class="modal-button delete">Delete</button>
												</div>
											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="h6_record h6_red_colr">Blood Not Available</h6>
						<?php endif; ?>
					</table>
				<?php else : ?>
					<h6 class="h6_record h6_red_colr">No Record Found</h6>
				<?php endif; ?>
				</div>
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
			window.location.href = "<?= base_url('Blood_bank/delete_blood_group'); ?>/" + itemId + "/Deleted";
		}
	</script>
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>