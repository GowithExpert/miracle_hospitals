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
	<title>Manage Slider Image</title>
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
	<!---Body Section Start -->
	<div class="equl_mrgn">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg">
						<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
					</div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg">
						<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
					</div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-image col_blu"></span> Manage Slider Image</h5>
			</div>
			<?php if ($slider) : ?>
				<div class="card-content" id="brdr_botm_silvr">
					<div class="row">
						<div class="col l8 m8 s4"></div>
						<div class="col l3 m3 s5 mrgn_botm"> 
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Admin/add_slider_image') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext">Add slider image</span>
								</div>
							</span>
						</div>
						<div class="col l1 m1 s3"></div>
					</div>
				</div>
				<div class="scroll-container card-content">
					<table width="200" class="table">
						<tr class="backgrnd_colr_gray">
							<th>Slider Image</th>
							<th class="txt_align">Image Title</th>
							<th class="txt_align">Website Link</th>
							<th class="txt_align">Image Description</th>
							<th class="txt_align">Status</th>
							<th class="txt_align">Action</th>
						</tr>
						<?php if ($slider) :
							count($slider);
							foreach ($slider as $doc) : ?>
								<tbody>
									<tr>
										<td>
												<a class="tooltipped" data-position="top" data-tooltip="<?= $doc['image_title']; ?>">
													<img src="<?= base_url() . 'public/uploads/frontend/slider/' . $doc['image_gallery']; ?>" class="responsive-img" id="profile_pic" height="50">
												</a>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="break-word">
												<?= substr($doc['image_title'], 0, 30); /*Fails for contineous (without space) long string */ ?>
											</span>
										</td>

										<td class="txt_break-at300 txt_align"> <!-- substr() works for words with space & contineous long string too  -->
											<span class="break-word">
												<a class="colour_hver" href="<?= $doc['website_link']; ?>" target="_blank"><?= substr($doc['website_link'], 0, 50); ?></a>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="break-word">
												<?= substr($doc['image_discription'], 0, 50); ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<?php if ($doc['status'] == "Active") :
												echo '<span class="col_gren">Active</span>';
											elseif ($doc['status'] == "InActive") :
												echo '<span class="col_red">InActive</span>';
											elseif ($doc['status'] == "Deleted") :
												echo '<span class="col_red">Deleted</span>';
											?>
											<?php endif; ?>
										</td>
										<td>
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $doc['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
											</center>

											<!---Action Dropdown --->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $doc['id']; ?>">
												<li><a href="<?= base_url('Admin/edit_slider_image/' . $doc['id']); ?>" id="dotted_border"><span class="fa fa-edit col_gren"></span> Edit</a></li>


												<?php if ($doc['status'] == "Active") :  ?>
													<li><a href="<?= base_url('Admin/change_slider_image_status/' . $doc['id'] . '/InActive'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															InActive</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $doc['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

												<?php elseif ($doc['status'] == "InActive") : ?>
													<li><a href="<?= base_url('Admin/change_slider_image_status/' . $doc['id'] . '/Active'); ?>"><span class="fa fa-eye col_blu"></span> Active</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $doc['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

												<?php elseif ($doc['status'] == "Deleted") : ?>
													<li><a href="<?= base_url('Admin/change_slider_image_status/' . $doc['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
													<li><a href="<?= base_url('Admin/permanent_del_manage_slider/' . $doc['id']); ?>" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>

												<?php else : ?>
													<li><a href="<?= base_url('Admin/change_slider_image_status/' . $doc['id'] . '/Active'); ?>"><span class="fa fa-eye col_blu"></span> Active</a></li>
												<?php endif; ?>
											</ul>
											<!---Action Dropdown --->
											<!-- Hidden delete confirmation modal -->
											<div id="deleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to delete this Slider?</p>
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
							<h6 class="h6_record col_red">Slider Image Not Found</h6>
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
					<h6 class="h6_record col_red pdng">No Record Found</h6>
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
				window.location.href = "<?= base_url('Admin/delete_slider'); ?>/" + itemId + "/Deleted";
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
		<?//= view('Admin/clear_text_js_file.php'); ?>
		<!---Body Section End--->
		<!---Body Section End -->
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>