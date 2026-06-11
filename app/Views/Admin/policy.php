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
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<title>Manage Policy</title>
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
				<h5 class="font_weght"><span class="fa fa-image col_blu"></span> Manage Policy</h5>
			</div>
			<?php if ($gallery) : ?>
				<div class="card-content" id="brdr_botm_silvr">
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12"> </div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter Gallery Image
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Admin/filter_gallery/new_gallery'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-image col_blu"></span> New Gallery </a></li>
								<li><a href="<?= base_url('Admin/filter_gallery/old_gallery'); ?>" class="waves-effect">
										<span class="fa fa-image col_blu"></span> Old Gallery </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
				</div>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Image</th>
							<th>Title</th>
							<th>Created Date</th>
							<th>Status</th>
							<th class="txt_align">Action</th>
						</tr>
						<?php if ($gallery) :
							count($gallery);
							foreach ($gallery as $gal_img) : 
								//$gallery_val = $gallery;
								if(is_object($gal_img)){ //echo 'a'; exit;
									$gal_img = (array) $gal_img;
								} 
							?>

								<tbody>
									<tr>
										<td>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $gal_img['title']; ?>">
												<img src="<?= base_url() . 'public/uploads/frontend/image_gallery/' . $gal_img['image']; ?>" class="responsive-img" id="profile_pic" height="50">
											</a>
										</td>
										<td class="text-container">
											<span class="break-word">
												<?//= $gal_img['image_title']; ?>
												<?= $gal_img['title']; ?>
											</span>
										</td>
										<td class="txt_break-at300">
											<span class="col_gren">
												<?= date('d M, Y', strtotime($gal_img['created_at'])); ?>
											</span>

										</td>
										<td class="txt_break-at300">
											<?php 
												/*
												if (isset($gal_img)) { ?>
												<?php if ($gal_img['status'] == "Active") :
													echo '<span class="col_gren">Active</span>';
												elseif ($gal_img['status'] == "InActive") :
													echo '<span class="col_red">InActive</span>';
												elseif ($gal_img['status'] == "Deleted") :
													echo '<span class="col_red">Deleted</span>';
												?>
												<?php endif; ?>
											<?php } 
											*/
											?>
											<?php  //0: Drafted, 1: Published,  2:Unpublished, 3: Deleted, 4: Other
												if (isset($gal_img)) { ?>
													<?php 
														if ($gal_img['status'] == 0) :
															echo '<span class="col_blue">Drafted</span>';
														elseif ($gal_img['status'] ==1) :
															echo '<span class="col_gren">Published</span>';
														elseif ($gal_img['status'] == 2) :
															echo '<span class="col_blue">Unpublished </span>';
														elseif ($gal_img['status'] == 3) :
															echo '<span class="col_red">Deleted</span>';
														elseif ($gal_img['status'] == 4) :
															echo '<span class="col_blue">Other</span>';
													?>
												<?php endif; ?>
											<?php } ?>
										</td>
										<td>
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $gal_img['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
											</center>
											<!---Action Dropdown --->
											<!--
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $gal_img['id']; ?>">

												<?php
													/*
													if ($gal_img['status'] == "Active") :  ?>
														<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img['id'] . '/InActive'); ?>" id="dotted_border">
																<span class="fa  fa-eye-slash"></span> 
																InActive</a></li>
														<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this Image Details?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>

													<?php elseif ($gal_img['status'] == "InActive") : ?>
														<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
														<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this Image Details?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>

													<?php elseif ($gal_img['status'] == "Deleted") : ?>
														<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
														<li><a href="<?= base_url('Admin/permanent_del_gallery_image/' . $gal_img['id']); ?>" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>


													<?php else : ?>
														<li><a href="<?= base_url('Admin/change_gallery_img_status/' . $gal_img['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
													<?php endif; 
													
													*/
												?>
											</ul>-->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $gal_img['id']; ?>">

												<?php
												//0: Drafted, 1: Published,  2:Unpublished, 3: Deleted, 4: Other
												//for Drafted
												if ($gal_img['status'] == 0) :  ?>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/1'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															Published</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/2'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Unpublished</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this record?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/4'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Other</a></li>
											
												<?php 
												//for Published 
												elseif ($gal_img['status'] == 1) :  ?>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/0'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															Drafted</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/2'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Unpublished</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this record?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/4'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Other</a></li>
											
												<?php  
												//for Unpublished  
												elseif ($gal_img['status'] == 2) : ?>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/0'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															Drafted</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/1'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Published</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this record?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/4'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Other</a></li>
											
												<?php 
												//for Deleted  
												elseif ($gal_img['status'] == 3) : ?>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/0'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															Drafted</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/1'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Published</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/2'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Published</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/4'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Other</a></li>
											
												<?php 
												//for Others 
												elseif	 ($gal_img['status'] == 4) : ?>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/0'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span> 
															Drafted</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/1'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Published</a></li>
													<li><a href="<?= base_url('Admin/change_privacy_policy_status/' . $gal_img['id'] . '/2'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Unpublished</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $gal_img['id']; ?>);" id="dotted_border" style="color:red;" onclick="return confirm('Are you sure you want to  delete this record?..');"><span class="fa fa-trash col_red"></span> Delete</a></li>
													
												<?php endif; ?>
											</ul>
											<!---Action Dropdown --->
											<!-- Hidden delete confirmation modal -->
											<div id="deleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
													<p>Are you sure you want to delete this record?</p>
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
							<h6 class="h6_record col_red">Record Not Found</h6>
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
					<h6 class="h6_record h6_red_colr pdng">Record Not Found</h6>
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
			window.location.href = "<?= base_url('Admin/change_privacy_policy_status'); ?>/" + itemId + "/3";
		}
	</script>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>