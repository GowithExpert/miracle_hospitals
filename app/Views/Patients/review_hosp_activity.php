<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
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
Date: 21st August, 2023 
-->

<!DOCTYPE html>
<html>

<head>
	<title>Feedback With Ratings & Reviews</title>
	<?= helper('Form'); ?>
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<?= view('Home/css_file'); ?>
	<?= view('Patients/patient_css_file.php'); ?>
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
</head>

<body>
	<!---Top Bar Section Include -->
	<?= view('Patients/top_bar'); ?>
	<!---Top Bar Section Include -->

	<!---Body Section Start --->
    <!-- <script src="your-script.js"></script> -->
	<div class="container">
	<div id="ajx_eror_msg"></div>
		<div class="row">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="col-lg-2 col-md-2"></div>
			<div class="col-lg-8 col-md-8 col-sm-12">
				<div class="card">
					<!-- Add these elements for success and error messages -->
					<div id="success-message" class="alert-success cutom_messge_styl disp_none" role="alert"></div>
					<div id="error-message" class="alert alert-danger cutom_messge_styl disp_none" role="alert"></div>
					<!-- <//?= form_open_multipart('Patients/review_hosp_activity'); ?> -->
					<div class="card-content bck_blu" id="brdr_botm_silvr">
						<h6 class="al_patient"><span class="fas fa-tasks"></span>  Feedback With Ratings & Reviews</h6>
					</div>

					<div class="card-content">
					<p class="mandatry_title">Red star (<span><sub>*</sub></span>) represents mandatory fields</p>
					
					<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								
								<div class="outer_body">
									<div class="rating">
										<input type="radio" name="star_rating" id="star_1" value="1">
										<label for="star_1" class="star2" data-rating="1"></label>

										<input type="radio" name="star_rating" id="star_2" value="2">
										<label for="star_2" class="star2" data-rating="2"></label>

										<input type="radio" name="star_rating" id="star_3" value="3">
										<label for="star_3" class="star2" data-rating="3"></label>

										<input type="radio" name="star_rating" id="star_4" value="4">
										<label for="star_4" class="star2" data-rating="4"></label>

										<input type="radio" name="star_rating" id="star_5" value="5">
										<label for="star_5" class="star2" data-rating="5"></label>
									</div>
								</div>
							
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="mandatory_cntr" class="col_red valid_err" id="starError"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<h6>Review Title</h6>
								<div class="input-container">
									<input type="text" class="asterisk col_blck titleError" name="review_title" id="review_title" placeholder="Enter Review Title">
									<span class="asterisk-symbol">*</span>
									<span id="titleError" class="col_red valid_err"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'review_title'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<h6>Review Image</h6>
								<div class="input-container">
									<input type="file" name="review_image" class="imageError" id="review_image" required="">
									<span id="imageError" class="col_red valid_err_upl"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'review_image'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<h6>Review Description</h6><span id="reviewError" class="col_red"></span>
								<textarea name="review_content" id="review_content" placeholder="Enter Description"></textarea>
								
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'review_content'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<center>
							<button type="submit" id="review" name="review" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-star checked"></span>  Review</button>
						</center>
						<!-- <//?= form_close(); ?> -->
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-2"></div>
		</div>
	</div>
	<!---Body Section End --->

	<!-- Review Star JavaScript Start-->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			const stars = $('.star2');
			let selectedRating = 0;
			//let valid = true;
			
			var clickedRating = '';
			stars.click(function(event) {
				clickedRating = parseInt($(this).data('rating'));

				if (selectedRating === clickedRating) {
					// Deselect the last selected star
					selectedRating = 0;
					deselectAllStars();
					console.log('Stars deselected.');
				} 
				else {
					selectedRating = clickedRating;
					highlightStars(clickedRating);
					console.log(`You've rated with ${clickedRating} stars.`);
				}
			});
			
			function highlightStars(rating) {
				stars.each(function() {
					const starRating = parseInt($(this).data('rating'));
					if (starRating <= rating) {
						$(this).addClass('active');
					} else {
						$(this).removeClass('active');
					}
				});
			}

			function deselectAllStars() { stars.removeClass('active'); }

			$('#review').on("click", function(e) {
				var star_rating = $("input[name='star_rating']:checked").val();
				var review_title = $("#review_title").val();
				var review_image = $("#review_image").val();
				console.log(clickedRating);
				if(!clickedRating) {
					$('#starError').text("Please select start ratings");
					//valid = false;
					return false;
				}
				
				// Name validation
				const titleInput = $(".titleError");
				const titleError = $("#titleError");
				if (titleInput.val().trim() === "") {
					titleError.text("Please enter title");
					//valid = false;
					return false;
				} 
				else { titleError.text(""); }
				if(!$("#review_content").val()) {
					$('#reviewError').text("Please enter desription");
					//valid = false;
					return false;
				}
				if (review_title) { 
					var formData = new FormData();
					if (review_image) {  
						//image validation
						const imageInput = $(".imageError");
						const imageError = $("#imageError");
						//const MAX_IMAGE_SIZE_KB = 500 KB; 
						if (imageInput.val().trim() != "") {
							const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
							if (fileSize > "<?=ALLOW_MAX_UPLOAD;?>") {
								imageError.text("Image size should be less than " + "<?=ALLOW_MAX_UPLOAD;?>" + "KB");
								//valid = false;
								return false;
							} 
							else { 
								imageError.text(""); 
							}
						}
						//console.log(review_image);
						var fileInput = $("#review_image");
						//console.log(fileInput[0].files[0]);
						var review_image = fileInput[0].files[0];
						console.log(review_image);
						formData.append("review_image", review_image);
					}
					
					var review_content = $("#review_content").val();
					formData.append("star_rating", star_rating);
					formData.append("review_title", review_title);
					formData.append("review_content", review_content);
			
					$.ajax({
						type: 'POST',
						url: "<?= base_url('/Patients/review_hosp_activity/') ?>",
						//data: queryString,
						data: formData,
						//dataType: 'json', // Change to 'json' for handling JSON response
						contentType: false,
						processData: false,
						success: function (response) {
							var jsonResponse = $.parseJSON(response);
							console.log(jsonResponse.status);
							if (jsonResponse.status) { // Display success message
								$('#success-message').html(jsonResponse.message);
								$('#success-message').show();
								//$('#error-message').hide();
							} 
							else { // Check if the response has validation errors
								if (jsonResponse.data) {
									console.log(jsonResponse.data);
									$('#contentError').html(jsonResponse.data.review_content).show();  
									$('#imageError').html(jsonResponse.data.review_image).show();  
									$('#titleError').html(jsonResponse.data.review_title).show();  
								} else {
									// Display other error message
									$('#error-message').html(jsonResponse.message);
									$('#error-message').show();
									$('#success-message').hide();
								}
							}

							// Hide messages after 5 seconds
							setTimeout(function () {
								$('#success-message').hide();
								$('#error-message').hide();
							}, 5000);
						},

					
						error: function (xhr, textStatus, errorThrown) {
							console.log("Error occurred in the AJAX request:");
							console.log(xhr);
							console.log(textStatus);
							console.log(errorThrown);

							// Display a generic error message
							$('#error-message').html("An error occurred. Please try again.");
							$('#error-message').show();
							$('#success-message').hide();

							// Hide the error message after 5 seconds
							setTimeout(function () {
								$('#error-message').hide();
							}, 5000);
						}
            		});
				}	
			});

			// $("#review").click(function(e) {
				
				
			// 	if (!valid) {
			// 		e.preventDefault();
			// 	}
			// });
		
		});
	</script>
	<!-- Review Star JavaScript End-->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
