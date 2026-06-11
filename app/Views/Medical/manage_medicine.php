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
	<title>Manage Medicine</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<!---CSS File Include  -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
	<!---Body Section Start --->
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
				<h5 class="font_weght"><span class="fa fa-medkit col_blu"></span>  Manage Medicines</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($medicines) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l8 m8 s12">
							<?= form_open('Medical_Accountant/search_medicine'); ?>
							<ul id="search_doctor">
								<li>
									<div class="input-container">
										<input type="text" name="medicine_name" class="serch_area" id="input_box" value="<?= set_value('medicine_name'); ?>" placeholder="Enter Medicine Name" required="">
										<span class="clear-input" id="clear-input">&times;</span>
									</div>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l2 m2 s2">
							<span class="right">
								<div class="tooltip"><a href="<?= base_url('Medical_Accountant/add_medicine') ?>"><i class="fas fa-capsules med_icon" aria-hidden="true"></i></a>
									<span class="tooltiptext">Add Medicine</span>
								</div>
							</span>
						</div>
						<div class="col l2 m2 s10">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger filter_btn" data-target="medicine_filter">
									<span class="fa fa-filter">  Filter Medicine</span>
								</button>
							</span>
							<!---Medicine filter -->
							<ul class="dropdown-content" id="medicine_filter">
								<li>
									<a href="<?= base_url('Medical_Accountant/filter_medicine/new_medicine'); ?>" class="waves-effect" id="dotted_border"> <span class="fa fa-users col_blu"></span>  New Medicine</a>
								</li>
								<li>
									<a href="<?= base_url('Medical_Accountant/filter_medicine/old_medicine'); ?>" class="waves-effect"> <span class="fa fa-users col_blu"></span>  Old Medicine</a>
								</li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th>Image</th>
						<th class="txt_align">Name</th>
						<th class="txt_align">Category Name</th>
						<th class="txt_align">Price</th>
						<th class="txt_align">Discount Price</th>
						<th class="txt_align">Sale Stock</th>
						<th class="txt_align">In Stock</th>
						<th class="txt_align">Total Stock</th>
						<th class="txt_align">Expiry Date</th>
						<th class="txt_align">Status</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if (count($medicines)) :
						foreach ($medicines as $pat_rec) : ?>
						<?php //echo "<pre>"; print_r($pat_rec);die;?>
							<tr>
								<td>
									<center>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $pat_rec['med_name']; ?>">
											<?php
											if (isset($pat_rec['med_image']) && !empty($pat_rec['med_image'])) {
												if (file_exists(FCPATH . 'uploads/medicine_image/' . $pat_rec['med_image'])) { ?>
													<img src="<?= base_url() . 'public/uploads/medicine_image/' . $pat_rec['med_image']; ?>" class="responsive-img" id="profile_pic" height="50">

												<?php  } //Inner if - Closed
												else {  ?>
													<img src="<?= base_url() . 'public/assets/images/dr.default-pic.jpg'; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Inner else - Closed

											} //Outer if - Closed

											else { ?>
												<img src="<?= base_url() . 'public/assets/images/dr.default-pic.jpg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Outer else - Closed  
											?>
										</a>
									</center>
								</td>
								<td class="txt_break-at200 txt_align">
									<?= $pat_rec['med_name']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
								<?= $pat_rec['med_company']; ?>
									<!-- <//?php
									//$get_company_name =  get_doctor_name('medicine_category', $pat_rec['med_company']);
									if (isset($get_company_name[0]->{'company_name'})) {
										if (is_array($get_company_name)) echo $get_company_name[0]->{'company_name'};
									} ?> -->
								</td>
								<td class="txt_break-at300 txt_align">

									<?= $pat_rec['med_price']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?= $pat_rec['med_d_price']; ?>
								</td>
								<td class="txt_break-at300 txt_align">
									<?php
									$get_stock =  get_stock('sale_products', $pat_rec['id']);

									$total_stock = 0;
									if ($get_stock) :
										count($get_stock);
										foreach ($get_stock as $stock) :
											$total_stock += $stock->quantity;
									?>

										<?php endforeach; ?>
										<h6 class="h6_gren_colr"><?= number_format($total_stock); ?></h6>
									<?php else : ?>
										<h6 class="span_red_colr">Product Not Sale</h6>
									<?php endif; ?>

								</td>
								<td class="txt_break-at300 txt_align">
									<?php
									$in_stock      = $pat_rec['med_stock'] - $total_stock;
									?>
									<h6 class="h6_orng_colr1"><?= number_format($in_stock); ?></h6>

								</td>
								<td class="txt_break-at300 txt_align">
									<h6 class="span_red_colr"><?= number_format($pat_rec['med_stock']); ?></h6>
								</td>
								<td class="txt_break-at300 txt_align">
									<span class="col_gren">  <?= date('D, M. d Y', strtotime($pat_rec['expiry_date'])); ?></span>
								</td>

								<td class="txt_break-at300 txt_align">
									<?php if ($pat_rec['status'] == "Active") :
										echo '<span class="col_gren">Active</span>';
									elseif ($pat_rec['status'] == "InActive") :
										echo '<span class="col_red">InActive</span>';
									elseif ($pat_rec['status'] == "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									?>
									<?php endif; ?>
								</td>

								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $pat_rec['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $pat_rec['id']; ?>" id="dotted_border">
										<li><a href="<?= base_url('Medical_Accountant/add_medicine_stock/' . $pat_rec['id']); ?>" id="dotted_border"><span class="fa fa-edit col_blu"></span>  Add Stock</a></li>
										<li><a href="<?= base_url('Medical_Accountant/edit_medicine/' . $pat_rec['id']); ?>" id="dotted_border"><span class="fa fa-edit col_blu"></span>  Edit</a></li>



										<?php if ($pat_rec['status'] == "Active") :  ?>
											<li><a href="<?= base_url('Medical_Accountant/change_medicine_status/' . $pat_rec['id'] . '/InActive'); ?>" id="dotted_border">
													<span class="fa  fa-eye-slash"></span>  
													InActive</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $pat_rec['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Delete</a></li>

										<?php elseif ($pat_rec['status'] == "InActive") : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_medicine_status/' . $pat_rec['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $pat_rec['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span>  Delete</a></li>

										<?php elseif ($pat_rec['status'] == "Deleted") : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_medicine_status/' . $pat_rec['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<li><a href="<?= base_url('Medical_Accountant/permanent_del_medicine/' . $pat_rec['id']); ?>" style="color:red;"><span class="fa fa-trash col_red"></span>  Permanent Delete</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Medical_Accountant/change_medicine_status/' . $pat_rec['id'] . '/Active'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span>  Active</a></li>
										<?php endif; ?>
									</ul>
									<!---Action Dropdown --->
									<!-- Hidden delete confirmation modal -->
									<div id="deleteConfirmationModal" class="modal">
										<div class="modal-content">
											<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
											<p class="del_pop_text">Are you sure you want to delete this Medicine?</p>
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
									<span class="fa fa-times"></span>  Products Not Found
								</h6>
							</div>
						</div>
					<?php endif; ?>
					<tr>
						<td colspan="7">
							<div id="pagination" class="col_wite">
								<?= $pager->links() ?>
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

	<script>
		$(document).ready(function() {
			var inputBox = $('#input_box');
			var clearInput = $('#clear-input');
			var basePath = 'manage_medicine';

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
			window.location.href = "<?= base_url('Medical_Accountant/delete_medicine'); ?>/" + itemId + "/Deleted";
		}

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

	<!---Body Section End --->
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/clear_text_js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>