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
	<title>Manage Doctor Account</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
				<h5 class="font_weght"><span class="fa fa-user-md col_blu"></span> Manage Doctors Account</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($doctor) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Admin/search_doctor_account'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="search_doctor" class="serch_area" id="input_box" value="<?= set_value('doctor_name'); ?>" placeholder="Enter Doctor Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Admin/add_doctor'); ?>"><i class="fa fa-plus-circle plus_icon_mgt" aria-hidden="true"></i></a>
									<span class="tooltiptext">Add Doctor</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s10">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter Doctor
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Admin/filter_doctor_account/new_doctor'); ?>" id="dotted_border" class="waves-effect">
										<span class="fa fa-users col_blu"></span> New Doctor </a></li>
								<li><a href="<?= base_url('Admin/filter_doctor_account/old_doctor'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span> Old Doctor</a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>

			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Doctor Image</th>
						<th class="txt_align">Name</th>
						<th class="txt_align">Gender</th>
						<th class="txt_align">Level</th>
						<th class="txt_align">Status</th>
						<th class="txt_align_rgt">Action</th>
					</tr>
					<?php if ($doctor) :
						// count($doctor); 
						foreach ($doctor as $doc) : ?>
							<tr>
								<td class="txt_break-at200">
									<a class="tooltipped" data-position="top" data-tooltip="<?= $doc['username']; ?>">
										<?php
										if (isset($doc['profile_pic']) && !empty($doc['profile_pic'])) {
											if (file_exists(FCPATH . 'uploads/doctor/' . $doc['profile_pic'])) { ?>
												<img src="<?= base_url() . 'public/uploads/doctor/' . $doc['profile_pic']; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php  } //Inner if - Closed
											else {  ?>
												<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Inner else - Closed

										} //Outer if - Closed

										else { ?>
											<img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
										<?php } //Outer else - Closed  
										?>
									</a>
								</td>
								<td class="text-container_tody_appoint txt_align">
									<span class="break-word">
										<?= $doc['username']; ?>
									</span>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $doc['gender']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $doc['level']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php if ($doc['status'] == "Active") :
										echo '<span class="col_gren">Active</span>';
									elseif ($doc['status'] == "InActive") :
										echo '<span class="col_red">InActive</span>';
									elseif ($doc['status'] == "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									else :
										echo '<span class="col_red">Admin Not Verify</span>';
									?>
									<?php endif; ?>
								</td>
								<td class="text-container_tody_appoint txt_align_rgt">
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $doc['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $doc['id']; ?>" id="dotted_border">

										<?php if ($doc['status'] == "Active") :  ?>
											<li><a href="<?= base_url('Admin/change_doctor_acc_status/' . $doc['id'] . '/InActive'); ?>" id="dotted_border" style="color: red">
													<span class="fa fa-eye-slash col_red"></span> 
													InActive</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $doc['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

										<?php elseif ($doc['status'] == "InActive") : ?>
											<li><a href="<?= base_url('Admin/change_doctor_acc_status/' . $doc['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $doc['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

										<?php elseif ($doc['status'] == "Deleted") : ?>
											<li><a href="<?= base_url('Admin/change_doctor_acc_status/' . $doc['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
											<li><a href="<?= base_url('Admin/permanent_del_doc_acc/' . $doc['id']); ?>" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>

										<?php else : ?>
											<li><a href="<?= base_url('Admin/change_doctor_acc_status/' . $doc['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Active</a></li>
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

							</tbody>
						<?php endforeach; ?>
					<?php else : ?>
						<h6 class="col_red">Doctor Account Not Found</h6>
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
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
		<script>
			$(document).ready(function() {
				var inputBox = $('#input_box');
				var clearInput = $('#clear-input');
				var basePath = 'manage_doctor_account';

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
				window.location.href = "<?= base_url('Admin/delete_doctor_account'); ?>/" + itemId + "/Deleted";
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
		<!---Body Section End -->
		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>