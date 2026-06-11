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
	<title>Manage Donor Blood</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->
	<!-----Body Section Start ----->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-users col_blu"></span>  Manage Donor's Blood</h5>
			</div>
			<div class="card-content" id="brdr_botm_silvr">
				<?php if ($donor_blood) : ?>
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row">
						<div class="col l6 m6 s12">
							<?= form_open('Blood_bank/search_donor_blood'); ?>
							<ul id="search_donors">
								<li>
									<select required="" name="blood_group" vale="<?= set_value('blood_group'); ?>">
										<option selected="" disabled="">Select Blood Group</option>
										<?php if ($donor_blood) :
											count($donor_blood);
											foreach ($donor_blood as $blood_group) : ?>
												<option value="<?= $blood_group->blood_group; ?>"><?= $blood_group->blood_group; ?></option>
											<?php endforeach; ?>
										<?php else : ?>
											<h6 class="h6_record h6_red_colr">Blood Group Not Found</h6>
										<?php endif; ?>
									</select>
								</li>
								<li>
									<button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
								</li>
							</ul>
							<?= form_close(); ?>
						</div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="donor_filters">
									<span class="fa fa-filter"></span>  Filter Blood
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="donor_filters">

								<li><a href="<?= base_url('Blood_bank/filter_donor_blood/new_donors'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-users col_blu"></span>  New Blood </a></li>
								<li><a href="<?= base_url('Blood_bank/filter_donor_blood/old_donors'); ?>" class="waves-effect">
										<span class="fa fa-users col_blu"></span>  Old Blood </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
			</div>
			<div class="scroll-container card-content">
				<table class="table">
					<tr class="backgrnd_colr_gray">
						<th class="txt_align">Donor Picture</th>
						<th>Donor Name</th>
						<th>Donor Mobile</th>
						<th>Blood Group</th>
						<th>Blood Unit</th>
						<th>Blood Price</th>
						<th>Blood Selling Price</th>
						<th>Blood Buy date</th>
						<th>Status</th>
						<th class="txt_align">Action</th>
					</tr>
					<?php if ($donor_blood) :
						count($donor_blood);
						foreach ($donor_blood as $blood_donor) : ?>
							<tr>
								<td>
									<?php
									$get_donors = get_doctor_name('blood_donor', $blood_donor->blood_donor_id);
									?>
									<a class="tooltipped" data-position="top" data-tooltip="<?= $get_donors[0]->donor_name; ?>">
										<img src="<?= base_url() . 'public/uploads/donar_users/' . $get_donors[0]->donor_image; ?>" class="responsive-img" id="donor_image" height="50">
									</a>
								</td>
								<td class="txt_break-at200">
									<?= $get_donors[0]->donor_name; ?>
								</td>
								<td class="txt_break-at300">
									<a class="link_hver" href="tel:<?= $get_donors[0]->contact_number; ?>"><?= $get_donors[0]->contact_number; ?></a>
								</td>
								<td class="txt_break-at300 col_red">
									<?= $blood_donor->blood_group; ?>
								</td>
								<td class="txt_break-at300 col_ornge">
									<?= $blood_donor->blood_unit; ?>
								</td>
								<td class="txt_break-at300 col_red">
									<span class="fa fa-rupee-sign"></span><?= number_format((float)$blood_donor->blood_price); ?> <!--@neoarks show error beacuse of typecasting-->
								</td>
								<td class="txt_break-at300 col_gren">
									<span class="fa fa-rupee-sign"></span><?= number_format($blood_donor->selling_price); ?>
								</td>
								<td class="txt_break-at300">
									<span class="fa fa-clock col_gren">  <?= date('d M, Y', strtotime($blood_donor->created_at)); ?></span>
								</td>
								<td class="txt_break-at300">
									<?php if ($blood_donor->status == "Active") :
										echo '<span class="col_gren">Active</span>';
									elseif ($blood_donor->status == "InActive") :
										echo '<span class="col_red">InActive</span>';
									elseif ($blood_donor->status = "Deleted") :
										echo '<span class="col_red">Deleted</span>';
									else : echo '<span class="col_red">Unknown</span>';
									?>
									<?php endif; ?>
								</td>
								<td>
									<center>
										<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $blood_donor->id; ?>"><span class="fa fa-ellipsis-v"></span></a>
									</center>

									<!---Action Dropdown --->
									<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $blood_donor->id; ?>">
										<li><a href="<?= base_url('Blood_bank/add_blood_selling_price/' . $blood_donor->id); ?>" id="dotted_border">Add Selling Price</a></li>

										<?php if ($blood_donor->status == "Active") :  ?>
											<li><a href="<?= base_url('Blood_bank/change_donor_blood/' . $blood_donor->id . '/InActive'); ?>">
													<span class="fa fa-eye-slash col_red"></span>  
													InActive</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blood_donor->id; ?>);" id="dotted_border" onclick="return confirm('Are you sure you want to  delete this Donor Details ?..');"><span class="fa fa-trash col_red"></span>  Delete</a></li>

										<?php elseif ($blood_donor->status == "InActive") : ?>
											<li><a href="<?= base_url('Blood_bank/change_donor_blood/' . $blood_donor->id . '/Active'); ?>"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $blood_donor->id; ?>);" id="dotted_border" onclick="return confirm('Are you sure you want to  delete this Donor Details ?..');"><span class="fa fa-trash col_red"></span>  Delete</a></li>

										<?php elseif ($blood_donor->status == "Deleted") : ?>
											<li><a href="<?= base_url('Blood_bank/change_donor_blood/' . $blood_donor->id . '/Active'); ?>"><span class="fa fa-eye col_blu"></span>  Active</a></li>
											<li><a href="<?= base_url('Blood_bank/permanent_del_donor_blood/' . $blood_donor->id); ?>" style="color:red;"><span class="fa fa-trash col_red"></span>  Pemanent Delete</a></li>
										<?php else : ?>
											<li><a href="<?= base_url('Blood_bank/change_donor_blood/' . $blood_donor->id . '/Active'); ?>"><span class="fa fa-eye col_blu"></span>  Active</a></li>
										<?php endif; ?>
									</ul>
									<!---Action Dropdown --->
									<!-- Hidden delete confirmation modal -->
									<div id="deleteConfirmationModal" class="modal">
										<div class="modal-content">
											<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
											<p class="del_pop_text">Are you sure you want to delete this Donor Details?</p>
											<div class="modal-buttons align_del_btn">
												<button id="confirmDelete" class="modal-button delete">Delete</button>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>

					<?php endif; ?>
				</table>
			<?php else : ?>
				<h6 class="h6_record col_red">No Record Found</h6>
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
			window.location.href = "<?= base_url('Blood_bank/delete_donor_details'); ?>/" + itemId + "/Deleted";
		}
	</script>
	<!-----Body Section End ----->
	<!----Js File Include ---->
	<?//= view('Admin/clear_text_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>