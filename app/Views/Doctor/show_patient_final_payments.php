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

				<!---Php Meassge Show --->
				<div class="stus_msg_bill">
				<?php if (session()->getTempdata('success')) : ?>
					<div class="card success-message cutom_messge_styl">
					<div class="card-content" id="popup_message">
						<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
					</div>
					</div>
				<?php endif; ?>
				<?php if (session()->getTempdata('error')) : ?>
					<div class="card error-message cutom_messge_styl">
					<div class="card-content" id="popup_message">
						<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
					</div>
					</div>
				<?php endif; ?>
				</div>
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
							<div class="col l9 m9 s6">
							<?php if (is_array($patients)) {
								if (isset($patients[0]->{'patient_phone'}) && isset($patients[0]->{'patient_phone'})) { ?>
									<h6 class="sty">Patient Name : <?= $patients[0]->{'patient_name'}; ?></h6>
									<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"><?= $patients[0]->{'patient_phone'}; ?></a></h6>
									<h6 class="sty">Puid : <?= $patients[0]->{'puid'}; ?></h6>
						  <?php } else { ?>
							<h6 class="sty">Patient Name : </h6>
							<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"></a></h6>
							<h6 class="sty">Puid : <?= $patients[0]->{'puid'}; ?></h6>
							<?php } } ?>
							
							</div>
							<div class="col l3 m3 s6">
								<h6 class="sty">Admit Date : <?= date('d M, Y', strtotime($patients[0]->created_at)); ?></h6>
								<h6 class="sty">Release Date : <?= date('d M, Y'); ?></h6>
								<h6 class="sty">Days Spent :
								<?php
									$admint_day = $get_patient[0]->booking_date;
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
						<h5 class="h5_heding">Disease and Symptoms : <span class="h5_inside"><?= $patients[0]->patient_issue; ?></span></h5>
					</div>
					<div class="card-content">
						<?= form_open('Doctor/clear_final_dues/' . $patients[0]->id .'/'. $patients[0]->pid .'/'. $patients[0]->apmt_id .'/'. $patients[0]->puid .'/'. $patients[0]->status); ?>
						<!-- Start-Custom -->
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="total_paid_amount">Total Hospital Expenses (INR)</label>
							</div>
							<div class="col l8 m8 s11">									
								<?php
								//$total_expenses_value = $total_expnc_amt[0]->total_price;
								$total_expenses_value = $total_expnc_amt['total_expnc_amt'];
								$formatted_value = number_format($total_expenses_value, 2, '.', ''); // Format the value as a decimal with 2 decimal places
								?>
								<input type="number" name="total_hospital_expenses" class="readonly_bg plcehldr_lft higt" id="total_hospital_expenses" placeholder="Total Expense" readonly value="<?= $total_expenses_value === 0 ? '0.00' : $formatted_value; ?>" readonly>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'total_expenses'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>

							<div class="col l1 m1 s1">
								<td>
									<center>
									<a class="btn dropdown-trigger v_ellip" href="#" data-target="dropdown1"><span class="fa fa-ellipsis-v"></span></a>

									</center>
									<!---Action Dropdown --->
									<ul id="dropdown1" class="dropdown-content">
										<li><a href="#!">View More</a></li>
									</ul>
								</td>
							</div>
						</div>
						<!-- End-Custom -->
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="room_charge">Total Patient Paid Amount (INR)</label>
							</div>
							<div class="col l8 m8 s11">
								<?php
								$patient_paid_amount = number_format($total_payable, 2, '.', ''); // Format the value as a decimal with 2 decimal places
								?>
								<div class="input-container">
									<input type="number" name="total_paid_amount" class="asterisk over_error readonly_bg plcehldr_lft higt" id="total_paid_amount" placeholder="Enter Total Patient Paid Amount" value="<?= $total_payable === 0 ? '0.00' : $patient_paid_amount; ?>" readonly>
									<span class="asterisk-symbol">*</span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'patient_paid'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
							<div class="col l1 m1 s1">
								<td>
									<center>
									<a class="btn dropdown-trigger v_ellip" href="#" data-target="dropdown1"><span class="fa fa-ellipsis-v"></span></a>

									</center>
									<!---Action Dropdown --->
									<ul id="dropdown1" class="dropdown-content">
										<li><a href="#!">View More</a></li>
									</ul>
								</td>
							</div>
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" id="total_dues_label">Total Pending Dues Amount (INR)</label>
							</div>
							<div class="col l8 m8 s11">
							<div class="input-container">
								<input type="text" name="dues_amount" class="asterisk over_error text_align readonly_bg plcehldr_lft higt" id="dues_amount" placeholder="0.00" value="<?= $total_pay_amt - $total_expnc_amt['total_expnc_amt']; ?>" readonly>
								
								<span class="asterisk-symbol">*</span>
							</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'dues_amount'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="payment_note">Payment Note</label>
							</div>
							<div class="col l8 m8 s11">
								<textarea name="payment_note" id="payment_note" class="plcehldr_lft" placeholder="Enter Payment Note"></textarea>
							</div>
							<div class="col l1 m1 s1">

							</div>
						</div>
						<div class='row'>
							<div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
								<div class="readmore_area">
									<button class="centered-button" name="pickslot" id="pickslot" type="submit"><a data-hover="Generate Bill"><span>Generate Bill</span></a></button>
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
 $(document).ready(function () {
	////////////////////placeholder hide////////////////////////////
	$('#total_paid_amount').focus(function() {
      $(this).removeAttr('placeholder');
    }).blur(function() {
      if ($(this).val() === '') {
        $(this).attr('placeholder', 'Enter Total Patient Paid Amount');
      }
    });

	$('#dues_amount').focus(function() {
      $(this).removeAttr('placeholder');
    }).blur(function() {
      if ($(this).val() === '') {
        $(this).attr('placeholder', '0.00');
      }
    });

	//////////////////////placeholder hide end////////////////////////////////



    // Add event listeners to the input fields using jQuery
    $("#total_hospital_expenses, #total_paid_amount").on("blur", updateDuesAmount);

	$("#total_hospital_expenses, #total_paid_amount,#dues_amount").on({
				// focus: function() {
				// 	// Remove '0.00' placeholder when the input box is clicked
				// 	if ($(this).val() === '0.00') {
				// 		$(this).val('');
				// 	}
				// },
				blur: updateDuesAmount
			});

    function updateDuesAmount() {
    // Get the values from the input fields
    var hospitalExpenses = $("#total_hospital_expenses").val();
    var patientPaidAmount = $("#total_paid_amount").val();
    var duesLabel = $("#total_dues_label");

    // Check if the input values are not empty
    if (hospitalExpenses === '' || patientPaidAmount === '') {
        // If either value is blank, set the dues amount field to blank
        $("#dues_amount").val('');
        duesLabel.text("Total Pending Dues Amount (INR)");
    } else {
        // Both values are present, proceed with the calculation
        hospitalExpenses = parseFloat(hospitalExpenses).toFixed(2);
        patientPaidAmount = parseFloat(patientPaidAmount).toFixed(2);

        // Calculate the dues or advanced payment
        var difference = hospitalExpenses - patientPaidAmount;

        // Update the label text based on the calculation
        if (difference > 0) {
            duesLabel.text("Patient Pending Amount");
        } else if (difference < 0) {
            duesLabel.text("Patient Advanced Payment (INR)");
        } else {
            duesLabel.text("Total Pending Dues Amount (INR)");
        }

        // Format the value to two decimal places and update the input fields
        $("#total_hospital_expenses").val(hospitalExpenses);
        $("#total_paid_amount").val(patientPaidAmount);
        $("#dues_amount").val(difference.toFixed(2));
    }
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