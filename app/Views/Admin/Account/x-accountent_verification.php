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
	<title>Accountant Verification</title>
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!---CSS File Include  -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<!----Style Body Section Start --->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fas fa-user-alt" style="color: #005197"></span> Verify Accountant Account</h5>
			</div>
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<!--Search Bar & Filter Bar Section Start -->
				<div class="row">
					<div class="col l6 m6 s12">

					</div>
					<div class="col l6 m6 s12">
						<span class="right">
							<button type="button" class="btn waves-effect waves-light dropdown-trigger" data-target="doctor_filter" style="background: #005197;box-shadow: none;text-transform: capitalize;height: 38px;margin-top: 15px;">
								<span class="fa fa-filter"> Filter Accountant</span>
							</button>
						</span>
						<!---Student filter -->
						<ul class="dropdown-content" id="doctor_filter">

							<li><a href="<?= base_url('Admin/filter_accountant_verification/new_accountant'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span> New Accountant </a></li>
							<li><a href="<?= base_url('Admin/filter_accountant_verification/old_accountant'); ?>" class="waves-effect">
									<span class="fa fa-users" style="color: #005197"></span> Old Accountant</a></li>
						</ul>
					</div>
					<!--Search Bar & Filter Bar Section End -->
				</div>
			</div>
			<div class="card-content">
				<table class="table">
					<tr>
						<th style="text-align: center;">Name</th>
						<th>UID</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Gender</th>
						<th>Level</th>
						<th>Created Date</th>
						<th>Status</th>
						<th style="text-align: center;">Action</th>
					</tr>
					<?php if ($accountant) :
						count($accountant);
						foreach ($accountant as $acc_account) : ?>
							<tbody>
								<tr>
									<td class="txt_break-at200">
										<center>
											<?= $acc_account['username']; ?>
										</center>
									</td>
									<td class="txt_break-at300">
										<?= $acc_account['uid']; ?>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="mailto:<?= $acc_account['email']; ?>"><?= $acc_account['email']; ?></a>
									</td>
									<td class="txt_break-at300">
										<a class="colour_hver" href="tel:<?= $acc_account['mobile']; ?>"><?= $acc_account['mobile']; ?></a>
									</td>
									<td class="txt_break-at300" style="color: orange">
										<?= $acc_account['gender']; ?>
									</td>
									<td class="txt_break-at300">
										<?= $acc_account['level']; ?>
									</td>
									<td class="txt_break-at300">
										<span> <?= date('D, M. d Y', strtotime($acc_account['created_date'])); ?></span>
									</td>
									<td class="txt_break-at300">
										<?php if ($acc_account['status']   == "Active") :
											echo '<span style="color:green">Active</span>';
										//elseif($acc_account['status'] == "InActive"):
										elseif ($acc_account['status'] == "InActive") :
											echo '<span style="color:red">InActive</span>';
										elseif ($acc_account['status'] == "Deleted") :
											echo '<span style="color:red">Deleted</span>';
										?>
										<?php endif; ?>
									</td>
									<td>
										<center>
											<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $acc_account['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
										</center>

										<!---Action Dropdown --->
										<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $acc_account['id']; ?>">

											<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $acc_account['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash" style="color: red;"></span> Delete</a></li>

											<?php if ($acc_account['status'] == "InActive") :  ?>
												<li><a href="<?= base_url('Admin/change_accountant_status/' . $acc_account['id'] . '/Active'); ?>" id="dotted_border">
														<span class="fa  fa-eye-slash" style="color: green"></span> 
														Active</a></li>
											<?php else : ?>
												<li><a href="<?= base_url('Admin/change_accountant_status/' . $acc_account['id'] . '/InActive'); ?>" id="dotted_border"><span class="fa fa-eye"></span> InActive</a></li>
											<?php endif; ?>
										</ul>
										<!-- Hidden delete confirmation modal -->
										<div id="deleteConfirmationModal" class="modal">
											<div class="modal-content">
												<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
												<p>Are you sure you want to delete this Account?</p>
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
						<div class="card" style="padding: 5px">
							<div class="card-content" style="background: red;padding: 3px;">
								<h6 style="color: white;font-weight: 500;margin-left: 20px;">
									<span class="fa fa-times"></span> Not any Verification for Accountant
								</h6>
							</div>
						</div>
					<?php endif; ?>
				</table>
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
				window.location.href = "<?= base_url('Admin/delete_accountant_account'); ?>/" + itemId + "/Deleted";
			}
		</script>
		<!----Style Body Section End --->

		<!---Js file Include -->
		<?= view('Admin/js_file.php'); ?>
		<!---Js file Include -->
</body>

</html>