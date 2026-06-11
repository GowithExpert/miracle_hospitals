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
	<title>Manage bed</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
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
			<!-- Add a div to display success message -->
			<div id="sucesMsgContainer"></div>

			<div class="card-content" id="brdr_botm_silvr">

				<h5 class="font_weght"><span class="fa fa-bed col_blu"></span> Manage Beds</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($bed) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Admin/search_bed'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="bed_lable" class="serch_area" id="input_box" value="<?= set_value('bed_lable'); ?>" placeholder="Enter bed Name" required="">
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
								<div class="tooltip"><a href="<?= base_url('Admin/add_bed') ?>"><i class="fa fa-plus-circle plus_icon_mgt"></i></a>
									<span class="tooltiptext">Add bed</span>
								</div>

							</span>
						</div>
						<div class="col l2 m2 s10">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter bed
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Admin/filter_bed/new_bed'); ?>" id="dotted_border"><span class="fa fa-tasks col_blu"></span> New bed </a></li>
								<li><a href="<?= base_url('Admin/filter_bed/old_bed'); ?>" class="waves-effect">
										<span class="fa fa-tasks col_blu"></span> Old bed </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="row">
			<div class="scroll-container card-content tble_alignment">
				<div class="table-container">
					<table class="table">
						<thead class="backgrnd_colr_gray">
							<tr>
								<th>Bed Lable </th>
								<th class="txt_align">Bed Number</th>
								<th class="txt_align">Description</th>
								<th class="txt_align">Department</th>
								<th class="txt_align">Ward</th>
								<th class="txt_align">Bed Status</th>
								<th class="txt_align">Action</th>
							</tr>
						</thead>
						<?php if ($bed) :
							count($bed);
							//echo "<pre>";print_r($bed);die;
							foreach ($bed as $beds) : ?>

								<tbody>
									<tr>
										<td class="txt_break-at300">
											<?= $beds['bed_lable']; ?>
										</td>
										<td class="text-container bed_number txt_align">
											<span class="break-word">
												<?= $beds['bed_number']; ?>
											</span>
										</td>
										<td class="text-container depart_widh">
											<span class="break-word">
												<?= $beds['bed_desc']; ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<span>
												<?= $beds['department_name']; ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<span>
												<?= $beds['ward_name']; ?>
											</span>								
										</td>
										
										
										<td class="txt_break-at300 txt_align">
											<?php if ($beds['status'] == 'Free') : //need to replace ['status'] with the [is_free]
												echo '<span class="col_gren">Free</span>';
											elseif ($beds['status'] == 'Occupied') :
												echo '<span class="col_gren">Occupied</span>';
												// elseif ($beds['status'] == "Unavailable") :
												// 	echo '<span class="col_red">Unavailable</span>';
													elseif ($beds['status'] == "Deleted") :
														echo '<span class="col_red">Deleted</span>';
												
											else :
												echo '<span class="col_red">Unknown</span>';
											?>
											<?php endif; ?>
										</td>
										<td>
										
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $beds['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
											</center>
											<!---Action Dropdown --->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $beds['id']; ?>">
												<li><a href="<?= base_url('Admin/view_bed/' . $beds['id']); ?>" id="dotted_border" style="color:#26a69a;"><span class="fa fa-edit col_blu"></span> Edit</a></li>

												<?php if ($beds['status'] == "Occupied") :  ?>
													<!-- <li><a href="<//?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Active'); ?>" id="dotted_border" style="color:#26a69a ;"><span class="fa fa-eye col_blu"></span> Occupied</a></li> -->
													<li><a href="<?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Free'); ?>" id="dotted_border" style="color:#26a69a ;"><span class="fa fa-eye col_blu"></span> Free</a></li>
													<!-- <li><a href="<//?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Unavailable'); ?>" id="dotted_border" style="color:red ;">
															<span class="fa  fa-eye-slash"></span> Unavailable</a></li> -->
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $beds['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

												<?php elseif ($beds['status'] == "Free") : ?>
													<li><a href="<?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Occupied'); ?>" id="dotted_border" style="color:#26a69a ;"><span class="fa fa-eye col_blu"></span> Occupied</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $beds['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
													<!-- <li><a href="<//?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Unavailable'); ?>" id="dotted_border" style="color:red ;"><span class="fa  fa-eye-slash"></span> Unavailable</a></li> -->
															

												<!-- <//?php elseif ($beds['status'] == "InActive") : ?>
													<li><a href="<//?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Occupied'); ?>" id="dotted_border" style="color:#26a69a;"><span class="fa fa-eye col_blu"></span> Occupied</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<//?= $beds['id']; ?>);" id="dotted_border" style="color:red ;"><span class="fa fa-trash col_red"></span> Delete</a></li> -->

												<?php elseif ($beds['status'] == "Deleted") : ?>
													<!-- Permanent Delete link -->
													<li><a href="javascript:void(0);" class="permanent-delete-link" style="color: red;" onclick="showPermanentDeleteConfirmationModal(<?= $beds['id']; ?>)" id="dotted_border"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>

													<li><a href="<?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Occupied'); ?>" id="dotted_border" style="color:#26a69a;"><span class="fa fa-eye col_blu"></span> Occupied</a></li>

												<?php else : ?>
													<li><a href="<?= base_url('Admin/change_bed_status/' . $beds['id'] . '/Occupied'); ?>" id="dotted_border" style="color:#26a69a ;"><span class="fa fa-eye col_blu"></span> Occupied</a></li>
												<?php endif; ?>
											</ul>
											<!-- Hidden delete confirmation modal -->
											<div id="deleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to delete?</p>
													<div class="modal-buttons align_del_btn">
														<button id="confirmDelete" class="modal-button delete">Delete</button>
													</div>
												</div>
											</div>
											<!-- HTML for the permanent delete confirmation modal -->
											<div id="permanentDeleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelPermanentDelete" class="modal-icon cancel" onclick="hidePermanentDeleteConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to permanently delete?</p>
													<div class="modal-buttons align_del_btn">
														<button id="confirmPermanentDelete" class="modal-button delete">Permanent Delete</button>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="col_red">Bed Record Not Found</h6>
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
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			var inputBox = $('#input_box');
			var clearInput = $('#clear-input');
			var basePath = 'manage_bed';

			clearInput.on('click', function() {
				inputBox.val('');
				clearInput.hide();
				if (!containsBasePath(document.URL, basePath)) {
					history.back();
				}
			});

			inputBox.on('input', function() {
				if (inputBox.val().trim() !== '') {
					clearInput.show();
				} else {
					clearInput.hide();
				}

				if (!containsBasePath(document.URL, basePath) && inputBox.val().trim() === '') {
					history.back();
				}
			});

			// Initial check to hide the clear-input if the input is empty
			if (inputBox.val().trim() === '') {
				clearInput.hide();
			}

			function containsBasePath(url, basePath) {
				return url.includes(basePath);
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

		// Show the permanent delete confirmation modal
		function showPermanentDeleteConfirmationModal(itemId) {
			const modal = document.getElementById("permanentDeleteConfirmationModal");
			modal.style.display = "block";

			const confirmPermanentDeleteButton = document.getElementById("confirmPermanentDelete");
			const cancelPermanentDeleteButton = document.getElementById("cancelPermanentDelete");

			// Add event listeners to the buttons
			confirmPermanentDeleteButton.onclick = function() {
				permanentDeleteItem(itemId);
			};

			cancelPermanentDeleteButton.onclick = function() {
				modal.style.display = "none";
			};
		}

		// Function to delete the item
		function deleteItem(itemId) {
			// Close the modal
			const modal = document.getElementById("deleteConfirmationModal");
			modal.style.display = "none";

			// Redirect to the delete URL
			window.location.href = "<?= base_url('Admin/delete_bed'); ?>/" + itemId + "/Deleted";
		}

		// Function to permanently delete the item
		function permanentDeleteItem(itemId) {
			// Close the modal
			const modal = document.getElementById("permanentDeleteConfirmationModal");
			modal.style.display = "none";

			// Redirect to the permanent delete URL
			window.location.href = "<?= base_url('Admin/permanent_del_bed'); ?>/" + itemId + "/Permanent Deleted";
		}




		// Function to get URL parameters
		function getUrlParameter(name, url) {
			if (!url) url = window.location.href;
			const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
			const results = regex.exec(url);
			return results ? decodeURIComponent(results[2].replace(/\+/g, " ")) : null;
		}

		const sucesMsg = getUrlParameter('sucesMsg');
		const sucesMsgContainer = document.getElementById('sucesMsgContainer');

		if (sucesMsg) {
			sucesMsgContainer.textContent = sucesMsg;
			sucesMsgContainer.style.visibility = 'visible';

			if (!window.location.hash.includes('success')) {
				window.location.hash = 'success';

				// Check if the hash is still 'success' after the timeout
				setTimeout(() => {
					if (window.location.hash === '#success') {
						sucesMsgContainer.style.visibility = 'hidden';
						window.location.hash = ''; // Reset the hash after hiding the message
					}
				}, 5000);
			} else {
				// If the hash is already 'success', hide the green strip
				sucesMsgContainer.style.visibility = 'hidden';
			}
		} else {
			// If there's no success message, hide the green strip
			sucesMsgContainer.style.visibility = 'hidden';
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
	<?= view('Admin/clear_text_js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>