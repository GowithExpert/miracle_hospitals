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
	<title>Final Payments</title>
	<?= helper('Form'); ?>
	<!---CSS File Include -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
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
						<h6 class="ttle"><i class='fas fa-rupee-sign colr'></i> Final Payments</h6>
					</div>
					<div class="card-content">
						<div class="row">
							<div class="col l12 m12 s12">
								<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="img_tag">
							</div>
						</div>
						<div class="row">
							<div class="col l9 m9 s9">
								<h6 class="sty">Patient Name :
									<?php echo $apmt_patient[0]->patient_name; ?>
								</h6>
								<h6 class="sty">Patient Mobile :
								<?php echo $apmt_patient[0]->patient_mobile; ?>
								</h6>
								<h6 class="sty">Puid :
								<?php echo $apmt_patient[0]->puid; ?>
								</h6>
								<h6 class="sty">Doctor Name :
								<?php echo $apmt_patient[0]->doctor_name; ?>
								</h6>
								
							</div>
							
							
							<div class="col l3 m3 s6">
								<h6 class="sty">Admit Date : <?= date('d M, Y', strtotime($apmt_patient[0]->created_at)); ?></h6>
								<h6 class="sty">Release Date : <?= date('d M, Y'); ?></h6>
								<h6 class="sty">Days Spent :
								<?php
									$admint_day = $apmt_patient[0]->booking_date;
									$today = date("Y-m-d");
									$diff = date_diff(date_create($admint_day), date_create($today));
									$day_diff_num = $diff->format('%d');
									$get_spent_days = $day_diff_num + 1;
									echo $get_spent_days;
									?>
								</h6>
							</div>
						</div>
					</div>
					<div class="card-content">
						<h5 class="h5_heding">Disease and Symptoms : <span class="h5_inside"><?= $apmt_patient[0]->disease_symptoms; ?></span></h5>
					</div>
					<div class="card-content">
						
						<?= form_open('Doctor/save_fee/' . $apmt_patient[0]->patients_id . '/' . $apmt_patient[0]->pid . '/' . $apmt_patient[0]->id . '/' . $apmt_patient[0]->puid . '/' . $apmt_patient[0]->serial . '/' . $apmt_paymnt_payid) ?>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="doctor_fee">Appointment (Non-Refundable) Fee (INR)</label>
							</div>
							<div class="col l8 m8 s11">									
								<?php if(isset($appointment_fee)) { ?>
									<input type="number" name="appointment_fee" class="readonly_bg plcehldr_lft higt" id="appointment_fee" placeholder="Appointment Fee" readonly value="<?= $appointment_fee; ?>">
								<?php } else { ?>
								<input type="number" name="appointment_fee" class="readonly_bg plcehldr_lft higt" id="appointment_fee" placeholder="Appointment Fee" readonly value="<?= '0.00'; ?>">
								<?php } if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'appointment_fee'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>

							<div class="col l1 m1 s1">
								<div class="options-container" id="options-container-1">
									<button class="options-btn" onclick="toggleOptions(event, 'options-container-1')"><i class="fa fa-ellipsis-v"></i></button>
									<div class="options-content">
										<a href="#" onclick="hideOptionsContent('options-container-1')" id="dotted_border">Edit</a>
										<a href="#" onclick="hideOptionsContent('options-container-1')">View</a>
									</div>
								</div>
							</div>
						</div>
						<!-- End-Custom -->
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="doctor_fee">Doctor Fee (INR)</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<input type="number" name="doctor_fee" class="asterisk over_error plcehldr_lft higt" id="doctor_fee" placeholder="Enter Doctor Fee" value="<?= $doctor_info[0]->doctor_fee; ?>">
									<span class="asterisk-symbol">*</span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'doctor_fee'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
							<div class="col l1 m1 s1">
								<div class="options-container" id="options-container-2">
									<button class="options-btn" onclick="toggleOptions(event, 'options-container-2')"><i class="fa fa-ellipsis-v"></i></button>
									<div class="options-content">
										<a href="#" onclick="hideOptionsContent('options-container-2')" id="dotted_border">Edit</a>
										<a href="#" onclick="hideOptionsContent('options-container-2')">View</a>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="total_payable" id="total_payable_lable">Total Payable (INR)</label>
							</div>
							<div class="col l8 m8 s11">
							<div class="input-container">
								<input type="text" name="total_payable" class="asterisk over_error text_align readonly_bg plcehldr_lft higt" id="total_payable" placeholder="0.00" value="" readonly>
								<span class="asterisk-symbol">*</span>
							</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'total_payable'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="payment_note">Payment Note</label>
							</div>
							<div class="col l8 m8 s11">
								<textarea name="payment_note" id="payment_note" placeholder="Enter Payment Note"></textarea>
							</div>
							<div class="col l1 m1 s1">

							</div>
						</div>
						<div class='row'>
							<div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
								<div class="readmore_area">
									<button class="centered-button" name="genbill" id="genbill" type="submit"><a data-hover="Generate Bill"><span>Generate Bill</span></a></button>
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
	 	// Add an event listener to listen for changes in the input fields
	 	$(document).ready(function () {////////////////////placeholder hide////////////////////////////
			$('#doctor_fee').focus(function() {
	      		$(this).removeAttr('placeholder');
	    	}).blur(function() {
	      		if ($(this).val() === '') {
	        		$(this).attr('placeholder', 'Enter Doctor Fee');
	      		}
	    	});

			$('#total_payable').focus(function() {
	      		$(this).removeAttr('placeholder');
	    	}).blur(function() {
	     		if ($(this).val() === '') {
	        		$(this).attr('placeholder', '0.00');
	      		}
	    	});

			$('#payment_note').focus(function() {
	      		$(this).removeAttr('placeholder');
	    	}).blur(function() {
	     		if ($(this).val() === '') {
	        		$(this).attr('placeholder', 'Enter Payment Note');
	      		}
	    	});
			//////////////////////placeholder hide end////////////////////////////////

	    // Add event listeners to the input fields using jQuery
	    $("#appointment_fee, #doctor_fee").on("blur", updateDuesAmount);

		$("#appointment_fee, #doctor_fee").on({
			focus: function() {
				// Remove '0.00' placeholder when the input box is clicked
				if ($(this).val() === '0.00') {
					$(this).val('');
				}
			},
			blur: updateDuesAmount
		});

	     function updateDuesAmount() {
			// Get the values from the input fields
			var appointment_fee = $("#appointment_fee").val();
			var doctor_fee = $("#doctor_fee").val();
			
			if(doctor_fee == '' || doctor_fee == 'NaN') {
				$("#doctor_fee").val('0.00');
			}

			// Both values are present, proceed with the calculation
			appointment_fee = parseFloat(appointment_fee).toFixed(2);
			doctor_fee = parseFloat(doctor_fee).toFixed(2);

			var total_payable = parseFloat(appointment_fee) + parseFloat(doctor_fee)|| 0;

			// Calculate the dues or advanced payment
			total_payable = total_payable.toFixed(2);
			//$("#total_payable").val() = total_payable;
			// Format the value to two decimal places and update the input fields
			$("#appointment_fee").val(appointment_fee);
			$("#doctor_fee").val(doctor_fee);
			$("#total_payable").val(total_payable);

			//console.log(total_payable);
			//console.log(appointment_fee);
		}

		// Initial call to set label and value
		updateDuesAmount();
	});
</script>
<script>
    function toggleOptions(event, containerId) {
        event.preventDefault();
        event.stopPropagation();
        var optionsContainer = document.getElementById(containerId);
        optionsContainer.classList.toggle('active');
    }
	function hideOptionsContent(containerId) {
        var optionsContainer = document.getElementById(containerId);
        optionsContainer.classList.remove('active');
    }
</script>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?=view('Admin/asterisk_symbol_js_file.php');?>
	<!--js file Include for asterisk symbol-->
</body>

</html>