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
	<title>Manage Blogs</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<!---Include Css File --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start  ---->
	<div class="equl_mrgn">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-flask col_blu"></span>  Manage Blogs Content</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($blogs_content) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12"></div>
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Doctor/add_blogs') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext">Add Blogs</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s12 mrgn_botm">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Blogs
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">
								<li><a href="<?= base_url('Doctor/filter_blogs/new_blogs'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-image col_blu"></span>  New Blogs </a></li>
								<li><a href="<?= base_url('Doctor/filter_blogs/old_blogs'); ?>" class="waves-effect">
										<span class="fa fa-image col_blu"></span>  Old Blogs </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>

			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Blog Image</th>
						<th class="txt_align">Blog Title</th>
						<th class="txt_align">Blog Content</th>
						<th class="txt_align">Doctor</th>
						<th class="txt_align">Department</th>
						<th class="txt_align">Post Date</th>
						<th class="txt_align">Post Months</th>
						<th class="txt_align">Post Year</th>
						<th class="txt_align">Status</th>
						<th>Action</th>
					</tr>
					<?php if ($blogs_content) :
						count($blogs_content);
						
						foreach ($blogs_content as $blog) : ?>
							<tr>
								<td>
									<center>
											<a class="tooltipped" data-position="top" data-tooltip="<?= $blog['blog_title']; ?>">
												<?php
												if (isset($blog['blog_image']) && !empty($blog['blog_image'])) {
													if (file_exists(WRITEPATH . 'public/uploads/frontend/blog_image/' . $blog['blog_image'])) { ?>
														<img src="<?= base_url() . 'public/uploads/frontend/blog_image/' . $blog['blog_image']; ?>" class="responsive-img" id="profile_pic" height="50">

													<?php  } //Inner if - Closed 
													else {  ?>
														<img src="<?= base_url('public/assets/home_image/images/choose-us-img3.jpg'); ?>" class="responsive-img" id="profile_pic" height="50">
													<?php } //Inner else - Closed

												} //Outer if - Closed

												else { ?>
													<img src="<?= base_url('public/assets/home_image/images/choose-us-img3.jpg'); ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Outer else - Closed  
												?>
											</a>
										</center>
								</td>
								<td class="txt_break-at200">
									<?= word_limiter($blog['blog_title'], 4); ?>
								</td>
								<td class="txt_break-at200">
									<?= word_limiter($blog['blog_content'], 4); ?>
								</td>
								<!-- <td class ="txt_break-at300"> -->
								<? //php
								//$get_doctor =  get_doctor_name('doctor',$blog['doctor_id']);
								//
								?>
								<td class="txt_break-at300 txt_align">
									<?= $blog['doctor_name']; ?>
								</td>
								
								<td class="txt_break-at300 txt_align">
									<?= $blog['department_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $blog['created_date']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $blog['created_month']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $blog['created_year']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php if ($blog['status'] == "Preview") :
										echo '<span class="col_red">Preview</span>';
									elseif ($blog['status'] == "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									else :
										echo '<span class="col_gren">Publish</span>';
									?>
									<?php endif; ?>
								</td>

								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $blog['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>
									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $blog['id']; ?>">

										<?php if ($blog['status'] == "Preview") :  ?>
											<li><a href="<?= base_url('Doctor/change_blog_status/' . $blog['id'] . '/Publish'); ?>" id="dotted_border">
													<span class="fa fa-upload col_gren"></span>  
													Publish</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blog['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Delete</a></li>

										<?php elseif ($blog['status'] == "Publish") :  ?>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blog['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Delete</a></li>
											<li><a href="<?= base_url('Doctor/change_blog_status/' . $blog['id'] . '/Preview'); ?>" id="dotted_border"><span class="fa fa-flash col_blu"></span>  Preview</a></li>										<?php elseif ($blog['status'] == "Deleted") : ?>
											<li><a href="<?= base_url('Doctor/change_blog_status/' . $blog['id'] . '/Publish'); ?>">
													<span class="fa fa-upload col_gren"></span>  
													Publish</a></li>
											<li><a href="<?= base_url('Doctor/permanent_del_blogs/' . $blog['id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-trash col_red"></span>  
													Permanent Delete</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Doctor/change_blog_status/' . $blog['id'] . '/Preview'); ?>" id="dotted_border"><span class="fa fa-flash col_blu"></span>  Preview</a></li>
										<?php endif; ?>
									</ul>
									<!---Action Dropdown --->
									<!-- Hidden delete confirmation modal -->
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
						<?php endforeach; ?>
					<?php else : ?>
						<div class="card" id="div_pad">
							<div class="card-content div_red_back">
								<h6 class="h6_produ_nt_fon">
									<span class="fa fa-times"></span>  Blogs Not Found
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
				<h6 class="h5_inside">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				var inputBox = $('#input_box');
				var clearInput = $('#clear-input');
				var basePath = 'manage_blogs';

				clearInput.on('click', function () {
					inputBox.val('');
					clearInput.hide();
					if (!containsBasePath(window.location.href, basePath)) {
						window.history.back();
					}
				});

				inputBox.on('input', function () {
					if (inputBox.val().trim() !== '') {
						clearInput.show();
					} else {
						clearInput.hide();
					}

					if (!containsBasePath(window.location.href, basePath) && inputBox.val().trim() === '') {
						window.history.back();
					}
				});

				// Initial check to hide the clear-input if the input is empty
				if (inputBox.val().trim() === '') {
					clearInput.hide();
				}

				function containsBasePath(url, basePath) {
					// Check if the base path is exactly equal to the URL
					return url.endsWith('/' + basePath) || url === basePath;
				}
			});


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
				window.location.href = "<?= base_url('Doctor/delete_blogs'); ?>/" + itemId + "/Deleted";
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
		<!---Body Section End  ---->

		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>