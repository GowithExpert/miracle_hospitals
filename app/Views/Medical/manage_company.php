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
	<title>Manage Company</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<!---Include Css File --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
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
				<h5 class="font_weght"><span class="fa fa-flask col_blu"></span>  Manage Medicines Company</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($medicine_company) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12">
							
							<?= form_open('Medical_Accountant/search_company'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="company_name" class="serch_area" id="input_box" value="<?= set_value('company_name'); ?>" placeholder="Enter Company Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span>  Filter Company
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Medical_Accountant/filter_medicine_cpmpany/new_medicine'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-trophy col_blu"></span>  New Company </a></li>
								<li><a href="<?= base_url('Medical_Accountant/filter_medicine_cpmpany/old_medicine'); ?>" class="waves-effect">
										<span class="fa fa-trophy col_blu"></span>  Old Company </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>

			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Company Name</th>
						<th class="txt_align">Company Address</th>
						<th class="txt_align">Company Mobile Number</th>
						<th class="txt_align">Show Products</th>
						<th class="txt_align">Date</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if ($medicine_company) :
						count($medicine_company);
						
						foreach ($medicine_company as $md_c) : ?>
							<tr>
								<td class="txt_break-at200">
									<?= $md_c['company_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $md_c['com_address']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $md_c['company_c_num']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="fa fa-eye col_gren"></span>
									<a class="colour_hver" href="<?= base_url('Medical_Accountant/show_products/' . $md_c['id']); ?>">Show Products</a>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= date('d M, Y', strtotime($md_c['created_at'])); ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php if ($md_c['status'] == "Active") :
										echo '<span class="col_gren">Active</span>';
									elseif ($md_c['status'] == "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									elseif ($md_c['status'] == "InActive") :
										echo '<span class="col_red">InActive</span>';
									?>
									<?php endif; ?>
								</td>

								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $md_c['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>
									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $md_c['id']; ?>">
										<li><a href="<?= base_url('Medical_Accountant/edit_company_name/' . $md_c['id']); ?>" id="dotted_border"><span class="fa fa-edit col_blu"></span>  Edit</a></li>

										<?php if ($md_c['status'] == "Active") :  ?>
											<li><a href="<?= base_url('Medical_Accountant/change_company_status/' . $md_c['id'] . '/InActive'); ?>" id="dotted_border">
													<span class="fa  fa-eye-slash"></span>  
													InActive</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $md_c['id']; ?>);" id="dotted_border" style="color:red"><span class="fa fa-trash col_red"></span>  Delete</a></li>
										<?php elseif ($md_c['status'] == "InActive") :  ?>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $md_c['id']; ?>);" id="dotted_border" style="color:red"><span class="fa fa-trash col_red"></span>  Delete</a></li>
											<li><a href="<?= base_url('Medical_Accountant/change_company_status/' . $md_c['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>

										<?php elseif ($md_c['status'] == "Deleted") :  ?>
											<li><a href="<?= base_url('Medical_Accountant/change_company_status/' . $md_c['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<li><a href="<?= base_url('Medical_Accountant/permanent_del_company/' . $md_c['id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>

										<?php else : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_company_status/' . $md_c['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
										<?php endif; ?>
									</ul>
									<!---Action Dropdown --->
									<!-- Hidden delete confirmation modal -->
									<div id="deleteConfirmationModal" class="modal">
										<div class="modal-content">
											<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
											<p class="del_pop_text">Are you sure you want to delete this Company name?</p>
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
									<span class="fa fa-times"></span>  Company Not Found
								</h6>
							</div>
						</div>

					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" class="col_wite">
								<?= $pager->links(); ?>
							</div>
						</td>
					</tr>
				</table>
			<?php else : ?>
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
			</div>
		</div>
		<!---Body Section End  ---->

		<script>
			$(document).ready(function() {
				var inputBox = $('#input_box');
				var clearInput = $('#clear-input');
				var basePath = 'manage_company';

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
				window.location.href = "<?= base_url('Medical_Accountant/delete_company'); ?>/" + itemId + "/Deleted";
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

		<!---Js file Include -->
		<?= view('Admin/clear_text_js_file.php'); ?>
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>