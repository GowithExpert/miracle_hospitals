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
	<title>Patients Review</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
	<?= view('Admin/customdelete_popup_css_file.php'); ?>
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Ff4svGQa7k4FThV0v8FkUqH7lCmMB5Jww5mLxCoX+5jcTIj5dHaC6DzCkVZKME3z" crossorigin="anonymous"> -->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!-- Include jQuery library -->
	<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
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
				<h5 class="font_weght"><span class="fa fa-wheelchair col_blu"></span> Manage Patients Review</h5>
			</div>
			<?php if ($review_patient) : ?>
				<div class="card-content" id="brdr_botm_silvr">
					<!--Search Bar & Filter Bar Section Start -->
					<div class="row mrgn_botm">
						<div class="col l6 m6 s12"> </div>
						<div class="col l6 m6 s12">
							<span class="right">
								<button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
									<span class="fa fa-filter"></span> Filter Feedback
								</button>
							</span>
							<!---Student filter -->
							<ul class="dropdown-content" id="doctor_filter">

								<li><a href="<?= base_url('Admin/filter_feedback/new_feedback'); ?>" class="waves-effect" id="dotted_border">
										<span class="fa fa-tasks col_blu"></span> New Feedback </a></li>
								<li><a href="<?= base_url('Admin/filter_feedback/old_feedback'); ?>" class="waves-effect">
										<span class="fa fa-tasks col_blu"></span> Old Feedback </a></li>
							</ul>
						</div>
						<!--Search Bar & Filter Bar Section End -->
					</div>
				</div>
				<div class="scroll-container card-content">
					<table class="table">
						<tr class="backgrnd_colr_gray">
							<th>Image</th>
							<th class="txt_align">Title</th>
							<th class="txt_align">Description</th>
							<th class="txt_align">Rating</th>
							<th class="txt_align">Date</th>
							<th class="txt_align">Status</th>
							<th class="txt_align">Action</th>
						</tr>
						<?php if (isset($review_patient) && is_array($review_patient)) :
							$reviewCount = count($review_patient); // Count the number of reviews
							foreach ($review_patient as $review) : ?>
								<tbody>
									<tr>
										<td>
											<center>
										<a class="tooltipped" data-position="top" data-tooltip="<?= $review['review_title']; ?>">
											<?php
											if (isset($review['review_image']) && !empty($review['review_image'])) {
												if (file_exists(WRITEPATH . 'public/uploads/frontend/review_image/' . $review['review_image'])) { ?>
													<img src="<?= base_url() .  'public/uploads/frontend/review_image/' . $review['review_image']; ?>" class="responsive-img" id="profile_pic" height="50">

												<?php  } //Inner if - Closed
												else {  ?>
													<img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
												<?php } //Inner else - Closed

											} //Outer if - Closed

											else { ?>
												<img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img" id="profile_pic" height="50">
											<?php } //Outer else - Closed  
											?>
										</a>

									</center>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="break-word">
												<?= $review['review_title']; ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<span class="break-word" title="<?= $review['review_content']; ?>">
												<?= word_limiter($review['review_content'], 4); ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
										<?php
											$rating = $review['star_rating'];
											for ($i = 1; $i <= 5; $i++) {
												$starColor = ($i <= $rating) ? 'style="color: yellow;"' : 'style="color: grey;"';
												echo '<i class="fas fa-star" ' . $starColor . '></i>';
											}
										?>
										</td>
										<td class="txt_break-at300 txt_align">
											<span>
												<?= date('d M, Y', strtotime($review['created_at'])); ?>
											</span>
										</td>
										<td class="txt_break-at300 txt_align">
											<?php
											
											if ($review['status'] == "Verified") :
												echo '<span class="col_gren">Verified</span>';
											elseif ($review['status'] == "UnVerified") :
												echo '<span class="col_red">UnVerified</span>';
											elseif ($review['status'] == "Deleted") :
												echo '<span class="col_red">Deleted</span>';
											else :
												echo '<span class="col_gren">Feedback</span>';
											?>
											<?php endif; ?>
										</td>
										<td>
											<center>
												<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $review['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
											</center>
											<!---Action Dropdown --->
											<ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $review['id']; ?>">


												<?php if ($review['status'] == "Verified") :  ?>
													<li><a href="<?= base_url('Admin/change_feedback_status/' . $review['id'] . '/UnVerified'); ?>" id="dotted_border">
															<span class="fa  fa-eye-slash"></span>  Un-Verified</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $review['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>

												<?php elseif ($review['status'] == "UnVerified") : ?>
													<li><a href="<?= base_url('Admin/change_feedback_status/' . $review['id'] . '/Verified'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Verify</a></li>
													<li><a href="javascript:void(0);" onclick="showDeleteConfirmationModal(<?= $review['id']; ?>);" id="dotted_border" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
												<!-- 	
												<?php //elseif ($review['status'] == "Deleted") : ?>
													<li><a href="<//?= base_url('Admin/change_feedback_status/' . $review['id'] . '/Verified'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Verify</a></li>
													<li><a href="<//?= base_url('Admin/permanent_del_patients_review/' . $review['id']); ?>" style="color:red;" class="permanent-delete-patient-link"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li> -->
												<?php else : ?>
													<li><a href="<?= base_url('Admin/change_feedback_status/' . $review['id'] . '/Verified'); ?>" id="dotted_border"><span class="fa fa-eye col_blu"></span> Verify</a></li>
												<?php endif; ?>
											</ul>
											<!-- Hidden delete confirmation modal -->
											<div id="deleteConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelDelete" class="modal-icon cancel" onclick="hideDeleteConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to delete this Review?</p>
													<div class="modal-buttons align_del_btn">
														<button id="confirmDelete" class="modal-button delete">Delete</button>
													</div>
												</div>
											</div>
											<!-- Hidden permanent delete appointment confirmation modal -->
											<div id="permanentDeleteAppointmentConfirmationModal" class="modal">
												<div class="modal-content">
													<span id="cancelPermanentDeleteAppointment" class="modal-icon cancel" onclick="hidePermanentDeleteAppointmentConfirmationModal();">&#x2716;</span>
													<p class="del_pop_text">Are you sure you want to permanently delete this appointment?</p>
													<div class="modal-buttons align_del_btn">
														<button id="confirmPermanentDeleteAppointment" class="modal-button delete">Permanent Delete</button>
													</div>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							<?php endforeach; ?>
						<?php else : ?>
							<h6 class="col_red h6_record">Patients Review Not Found</h6>
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
					<h6 class="col_red padng h6_record">No Record Found</h6>
				<?php endif; ?>
				</div>
		</div>

	</div>
	<script>
		$(document).ready(function() {
			/////////////////Permanent Delete Popup/////////////
			var permanentDeleteAppointmentUrl;

			$('.permanent-delete-patient-link').on('click', function(e) {
				e.preventDefault();
				permanentDeleteAppointmentUrl = $(this).attr('href');

				// Show the custom permanent delete appointment confirmation modal
				$('#permanentDeleteAppointmentConfirmationModal').show();
			});

			$('#cancelPermanentDeleteAppointment').on('click', function() {
				// Hide the custom permanent delete appointment confirmation modal when cancel is clicked
				$('#permanentDeleteAppointmentConfirmationModal').hide();
			});

			$('#confirmPermanentDeleteAppointment').on('click', function() {
				// Perform the permanent deletion of the appointment and redirect
				window.location.href = permanentDeleteAppointmentUrl;
			});
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
			window.location.href = "<?= base_url('Admin/delete_review'); ?>/" + itemId + "/Deleted";
		}
		function hidePermanentDeleteAppointmentConfirmationModal() {
			// Hide the custom permanent delete appointment confirmation modal when the modal close icon is clicked
			$('#permanentDeleteAppointmentConfirmationModal').hide();
		}
	</script>
	<?= view('Admin/text_wrap_js_file.php'); ?>
	<!---Body Section End -->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>