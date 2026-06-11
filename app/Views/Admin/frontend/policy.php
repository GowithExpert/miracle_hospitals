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
	<title>Manage Policy</title>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!---CSS File Include -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start -->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-image" style="color: #005197"></span> Manage Policy</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12"> </div>
					<div class="col l6 m6 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter"> Filter Policy Image</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">

							<li><a href="<?= base_url('Admin/filter_gallery/new_gallery'); ?>" class="waves-effect" style="border-bottom: 1px dashed silver">
									<span class="fa fa-image" style="color: #005197"></span> New Policy </a></li>
							<li><a href="<?= base_url('Admin/filter_gallery/old_gallery'); ?>" class="waves-effect">
									<span class="fa fa-image" style="color: #005197"></span> Old Policy </a></li>
						</ul>
					</div>
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th>Image</th>
						<th>Title</th>
						<th>Created Date</th>
						<th>Status</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($gallery) :
						count($gallery);
						foreach ($gallery as $gal_img) : ?>

							<tbody>
								<tr>
									<td>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $gal_img->image_title; ?>">
											<img src="<?= base_url() . 'public/uploads/frontend/image_gallery/' . $gal_img->gallery_image; ?>" class="responsive-img" id="profile_pic" height="50">
										</a>
									</td>
									<td class="txt_break-at200">
										<?= $gal_img->image_title; ?>
									</td>
									<td class="txt_break-at300">
										<span style="color: green">
											<?= date('d M, Y', strtotime($gal_img->created_at)); ?>
										</span>

									</td>
									<td class="txt_break-at300">
										<?php if (isset($gal_img)) { ?>
											<?php if ($gal_img->status == "Active") :
												echo '<span style="color:green">Active</span>';
											elseif ($gal_img->status == "InActive") :
												echo '<span style="color:red">InActive</span>';
											elseif ($gal_img->status == "Deleted") :
												echo '<span style="color:red">Deleted</span>';
											?>
											<?php endif; ?>
										<?php } ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $gal_img->id; ?>" style="text-transform: capitalize;font-weight: 500"><span class="fa fa-ellipsis-v"></span></a>
										</center>
										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $gal_img->id; ?>">
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img->id; ?>);" id="dotted_border" style="color:red;" style="border-bottom: 1px dashed silver"><span class="fa fa-trash" style="color: red;"></span> Delete</a></li>

											<?php if ($gal_img->status == "Active") :  ?>
												<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img->id . '/InActive'); ?>" id="dotted_border">
														<span class="fa  fa-eye-slash"></span> 
														InActive</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img->id . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye" style="color: #005197"></span> Active</a></li>
											<?php endif; ?>
										</ul>
										<!---Action Dropdown --->
										<!-- Hidden delete confirmation modal -->
										<div id="deleteConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
												<p>Are you sure you want to delete this Image?</p>
												<div class="modal-buttons">
													<button id="confirmDelete" class="modal-button delete">Delete</button>
												</div>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 style="color: red"> Gallery Record Not Found</h6>
					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" style="color: white">
								<?php if (isset($pager)) {
									echo $pager->links();
								} ?>
							</div>
						</td>
					</tr>
				</table>
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
			window.location.href = "<?= base_url('Admin/delete_gallery_image'); ?>/" + itemId + "/Deleted";
		}
	</script>
	<!---Body Section End -->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>