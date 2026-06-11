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
	<title>Add Payment</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<?= helper('Form'); ?>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="equl_mrgn">
		<div class="row">
			<div class="col l2 m2"></div>
			<div class="col l8 m8 s12">
				<div class="card">
					<div class="card-content brder">
						<h6 class="ttle"><i class='fas fa-rupee-sign colr'></i> Add Payment</h6>
					</div>
					<div class="card-content">
						<div class="row">
							<div class="col l12 m12 s12">
								<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="img_tag">
							</div>
						</div>
						<div class="row">
							<div class="col l9 m9 s12">
								<?php if (is_array($get_patient)) {
									if (isset($get_patient[0]->{'patient_mobile'}) && isset($get_patient[0]->{'patient_mobile'})) { ?>
										<h6 class="sty">Patient Name : <?= $get_patient[0]->{'patient_name'}; ?></h6>
										<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"><?= $get_patient[0]->{'patient_mobile'}; ?></a></h6>
										<h6 class="sty">Puid : <?= $get_patient[0]->{'puid'}; ?></h6>
									<?php } else { ?>
										<h6 class="sty">Patient Name : </h6>
										<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"></a></h6>
										<h6 class="sty">Puid : <?= $get_patient[0]->{'puid'}; ?></h6>
								<?php }
								} ?>
							</div>
							<div class="col l3 m3 s12">
								<h6 class="sty">Admit Date :
									<?= date('d M, Y', strtotime($get_patient[0]->booking_date)); ?>
								</h6>
								<h6 class="sty">Release Date : <?= date('d M, Y'); ?></h6>
								<h6 class="sty">Days Spent :
									<?php
									$admint_day = $get_patient[0]->booking_date;
									$today = date("Y-m-d ");
									$diff = date_diff(date_create($admint_day), date_create($today));
									$tot_without_admit_days = $diff->format('%d'); //Excluding admission day
									$tot_with_admit_days = $tot_without_admit_days + 1; //Including admission day
									echo $tot_with_admit_days;
									?>
								</h6>
							</div>
						</div>
					</div>
					<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
					<div class="card-content">
						<h5 class="h5_heding">Disease and Symptoms : <span class="h5_inside"><?= $get_patient[0]->disease_symptoms; ?></span></h5>
					</div>
					<div class="card-content mrgn_top">
						
						<?= form_open('Doctor/add_today_patient_payment/' . $get_patient[0]->patients_id . '/' . $get_patient[0]->pid . '/' . $get_patient[0]->id  . '/' . $get_patient[0]->puid); ?>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Room Charge</h5>
								<div class="input-container">
									<input type="number" name="room_charge" class="wp-form-control asterisk txt_rgt input-field room_charge plcehldr" id="input_box" placeholder="Enter Room Charge" required data-validation="Please enter room charges">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'room_charge'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Doctor Fee</h5>
								<div class="input-container">
									<input type="number" name="doc_fee" class="wp-form-control txt_rgt asterisk input-field doctor_fee1 plcehldr" id="input_box" placeholder="Enter Doctor Fee" required data-validation="Please enter the valid doctor fees">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'doc_fee'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Medicine Cost</h5>
								<div class="input-container">
									<input type="number" name="med_cost" class="wp-form-control asterisk txt_rgt input-field medicine_cost plcehldr" id="input_box" placeholder="Enter Medicine Cost" required data-validation="Please enter the medical cost">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'med_cost'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Other Cost</h5>
								<input type="number" name="other_cost" class="wp-form-control txt_rgt input-field other_cost plcehldr" id="input_box" placeholder="Other Cost" data-validation="Please enter the other cost">
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'other_cost'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Total Amount</h5>
								<input type="number" name="total" class="wp-form-control txt_rgt readonly_bg total-field plcehldr" id="input_box" placeholder="Total Amount" readonly>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'total'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-sticky-note-o col_blu"></span> Payment Note</h5>
								<textarea name="payment_note" id="payment_note" placeholder="Payment Note" class="wp-form-control"></textarea>
							</div>
						</div>
						<div class='row'>
							<div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
								<div class="readmore_area">
									<button class="centered-button" name="pickslot" id="pickslot" type="submit"><a data-hover="Generate Bill"><span>Generate Bill</span></a></button>
								</div>
							</div>
						</div>
					</div>
					<?= form_close(); ?>
				</div>
			</div>
		</div>
		<div class="col l2 m2"></div>
	</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
		$(document).ready(function() {
			////////////////////placeholder hide////////////////////////////
			$('.room_charge').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Enter Room Charge');
				}
			});

			$('.doctor_fee1').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Enter Doctor Fee');
				}
			});

			$('.medicine_cost').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Enter Medicine Cost');
				}
			});

			$('.other_cost').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Other Cost');
				}
			});

			//////////////////////placeholder hide end////////////////////////////////
			// Add event listener to handle formatting when the user leaves the input field
			$(".input-field").on("blur", function() {
				const input_boxInput = $(this);
				const price = parseFloat(input_boxInput.val());

				if (!isNaN(price)) {
					// Format the value with two decimal places and update the input field
					input_boxInput.val(price.toFixed(2));
				}
			});

			// Whenever an input field changes
			$('.input-field').on('input', function() {
				// Calculate the sum of all input fields with class 'input-field'
				var total = 0;
				$('.input-field').each(function() {
					total += parseFloat($(this).val()) || 0;
				});

				// Set the calculated total to all input fields with class 'total-field' formatted as a decimal
				$('.total-field').val(total.toFixed(2));
			});

			// Form submission event handler for client-side validation
			$("#pickslot").on("click", function(e) {
				// Prevent the default form submission
				//e.preventDefault();

				// Initialize a variable to track whether the form is valid
				let isFormValid = true;

				// Clear any previous error messages
				$(".validation").text("");

				// Validate each input field
				$(".input-field").each(function() {
					const input = $(this);
					const value = parseFloat(input.val());

					if (isNaN(value) || value < 0) {
						isFormValid = false;
						const errorMessage = input.data("validation");
						input.siblings(".validation").text(errorMessage);
					}
				});

				// If the form is not valid, prevent submission
				if (!isFormValid) {
					return;
				}

				// If the form is valid, you can proceed with form submission or other actions
				// For now, let's just submit the form
				
				return true;
			});
		});
	</script>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>
