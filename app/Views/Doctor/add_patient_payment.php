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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<style>
	input{
		text-align:left !important;
		text-indent:12px;
	}
	#dues_amount {
   	 	text-indent: 12px !important;
   	}
	#total_paid_amount {
   	 	text-indent: 12px !important;
   	}
	.pay_type{
		padding-bottom: 8px;
	}
	.asterisk-symbol { padding-top: 6px; }



</style>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div class="equl_mrgn">
		<div class="row">
			<div class="col l2 m2"></div>
			<div class="col l8 m8 s12">
				<!---Php Meassge Show --->
				<div class="stus_msg_bill">
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message"><?= session()->getTempdata('error'); ?></div>
						</div>
					<?php endif; ?>
				</div>

					
				<div class="card">
					<div class="card-content brder">
						
						<div class="row">
							<div class="col l12 m12 s12">
								<img src="<?= base_url('public/assets/images/logo3.png'); ?>" class="img_tag">
							</div>
						</div>
						<div class="row">
							<div class="col l9 m9 s12">
								<?php if (is_array($get_patient)) { //echo "<pre>";print_r($get_patient);die;
									if (isset($get_patient[0]->{'patient_mobile'}) && isset($get_patient[0]->{'patient_mobile'})) { ?>
										<h6 class="sty">Patient Name : <?= $get_patient[0]->{'patient_name'}; ?></h6>
										<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"><?= $get_patient[0]->{'patient_mobile'}; ?></a></h6>
										<h6 class="sty">PUID : <?= $get_patient[0]->{'puid'}; ?></h6>
									<?php } else { ?>
										<h6 class="sty">Patient Name : </h6>
										<h6 class="sty">Patient Mobile : <a class="btn_algin" href="tel:"></a></h6>
										<h6 class="sty">PUID : <?= $get_patient[0]->{'puid'}; ?></h6>
								<?php }
								} ?>
							</div>
							<div class="col l3 m3 s12">
								<h6 class="sty">Date
									<!-- <//?= date('d M, Y', strtotime($get_patient[0]->booking_date));?> -->
									<?= date('d-M-Y');?> at: <?= date('h:i:s');?>
								</h6>
								<h6 class="sty">Doctor:  <?= $get_patient[0]->{'doctor_name'} ?></h6>
								<!-- <h6 class="sty">Days Spent :
									<?php
									/* $admint_day = $get_patient[0]->booking_date;
									$today = date("Y-m-d ");
									$diff = date_diff(date_create($admint_day), date_create($today));
									$tot_without_admit_days = $diff->format('%d'); 
									$tot_with_admit_days = $tot_without_admit_days + 1; 
									echo $tot_with_admit_days;
									*/
									?>
								</h6> -->
							</div>
						</div>
						<!--
						</div>
						-->
					</div>
					<br>
					<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
					<div class="midsec">
						<h5 class="h5_heding">Disease and Symptoms : <span class="h5_inside"><?= $get_patient[0]->disease_symptoms; ?></span></h5>
					</div>
					<div class="midsec mrgn_top">
						<?php //echo "<pre>";print_r($patients);die; ?>																									
						<?= form_open('Doctor/add_patient_payment/' . $get_patient[0]->patients_id . '/' . $get_patient[0]->pid . '/' . $get_patient[0]->id  . '/' . $get_patient[0]->puid,array('name' => 'add_patient_payment','onsubmit' => 'return(validate());')); ?>
						<!-- new code -->
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="total_paid_amount">  Hospital Expenses (INR)</label>
							</div>
							<div class="col l8 m8 s11">									
								
								<input type="number" name="total_hospital_expenses" class="readonly_bg plcehldr_lft" id="total_hospital_expenses" placeholder="Total Expense" readonly value="<?= $payments['xpnc_sums']['total_expnc_amt'] ?? '0.00'; ?>">
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
								<label for="room_charge"> Total Paid (INR)</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<input type="number" name="total_paid_amount" class="asterisk over_error readonly_bg plcehldr_lft" id="total_paid_amount" placeholder="Enter Total Patient Paid Amount" value="<?= $payments['pay_sums']['total_payable'] ?? '0.00'; ?>" readonly>
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
								<label for="medicine_cost" id="total_dues_label">  Pending Dues (INR)</label>
							</div> 
							<div class="col l8 m8 s11">
								
								<div class="input-container">
									<input type="text" name="dues_amount" class="asterisk over_error inpt_pad readonly_bg plcehldr_lft" id="dues_amount" placeholder="0.00" value="<?= $payments['pay_sums']['pending_dues'] ?? '0.00'; ?>" readonly>
									<span class="asterisk-symbol">*</span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'dues_amount'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
					
						<!-- old  code -->
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" id="receive_payment"> Receive Payment (INR)</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<input type="number" name="receive_payment" class="wp-form-control asterisk txt_rgt input-field receive_payment plcehldr" id="receive_payment" placeholder="Enter Receve Payment" value = "<?= $payments['pay_sums']['pending_dues'] ?? '0.00'; ?>" data-validation="Please enter receive payment">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="col_red valid_err1"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'receive_payment'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" id="pay_type"> Payment Type</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<select name="pay_type" style="text-align:left" class="wp-form-control txt_rgt asterisk input-field pay_type plcehldr" id="pay_type" placeholder="Select payment type"  onchange="validatePayMode()" >
										<option value="paid_amount">Dues Receiving</option>
										<option value="room_charge">Room Charges</option>
										<option value="doc_fee">Doctor Fee</option>
										<option value="med_cost">Medicine Cost</option>
										<option value="admission_fee">Admission Fee</option>
										<option value="registration_fee">Appointment Fee</option>
										<option value="other_cost">Other Cost</option>
									</select>
									<div class="row">
										<span class="asterisk-symbol asterisk-symbol1">*</span>
										<span class="col_red valid_err2" style="top:74%;left: 0%;"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" id="pay_mode1"> Pay Mode</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<select name="pay_mode" style="text-align:left" class="wp-form-control txt_rgt asterisk input-field pay_mode plcehldr" id="pay_mode" placeholder="Select payment mode"  onchange="validatePayMode()" >
										<!-- <option value="">Select Pay Mode</option> -->
										<option value="Cash">Cash</option>
										<option value="Online">Online</option>
										<option value="Other">Other</option>
									</select>
									<div class="row">
										<span class="asterisk-symbol asterisk-symbol1">*</span>
										<span class="col_red valid_err2" style="top:74%;left: 0%;"></span>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" > Transaction ID</label>
							</div>
							<div class="col l8 m8 s11" style="margin-bottom: 4px;">
								<div class="input-container">
									<input type="text" name="transaction_id" class="wp-form-control  txt_rgt  transaction_id plcehldr" id="transaction_id" placeholder="Enter Transaction ID" data-validation="Please enter the transaction ID">
									<span class="transaction_id_error col_red valid_err3"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'transaction_id'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="payment_date" id="payment_date">Payment Date</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<input type="text" value="<?php echo date('d-m-Y'); ?>" name="payment_date" class="wp-form-control txt_rgt input-field payment_date plcehldr" id="payment_date" placeholder="payment_date" data-validation="Please enter the payment_date">
									<span class="col_red valid_err4"></span>
								</div>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'payment_date'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col l3 m3 s12">
								<label for="medicine_cost" id="total_dues_label">Payment Note</label>
							</div>
							<div class="col l8 m8 s11">
								<div class="input-container">
									<textarea name="payment_note" id="payment_note" placeholder="Payment Note" class="wp-form-control plcehldr"></textarea>
								</div>
							</div>
						</div>
						<!--  old end-->
						<!--
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Room Charge</h5>
								<div class="input-container">
									<input type="number" name="room_charge" class="wp-form-control asterisk txt_rgt input-field room_charge plcehldr" id="input_box" placeholder="Enter Room Charge" required data-validation="Please enter room charges">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red valid_err1"></span>
								</div>
								<?php //if (isset($validation)) { ?>
									<span class="col_red"><//?= display_error($validation, 'room_charge'); ?></span>
									<//?= $validation->listErrors(); ?>
								<?php // } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Doctor Fee</h5>
								<div class="input-container">
									<input type="number" name="doc_fee" class="wp-form-control txt_rgt asterisk input-field doctor_fee1 plcehldr" id="input_box" placeholder="Enter Doctor Fee" required data-validation="Please enter the valid doctor fees">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red valid_err1"></span>
								</div>
								<?php //if (isset($validation)) { ?>
									<span class="col_red"><//?= display_error($validation, 'doc_fee'); ?></span>
									<//?= $validation->listErrors(); ?>
								<?php //} ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Medicine Cost</h5>
								<div class="input-container">
									<input type="number" name="med_cost" class="wp-form-control asterisk txt_rgt input-field medicine_cost plcehldr" id="input_box" placeholder="Enter Medicine Cost" required data-validation="Please enter the medical cost">
									<span class="asterisk-symbol asterisk-symbol1">*</span>
									<span class="validation col_red valid_err1"></span>
								</div>
								<?php //if (isset($validation)) { ?>
									<span class="col_red"><//?= display_error($validation, 'med_cost'); ?></span>
									<//?= $validation->listErrors(); ?>
								<?php// } ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Other Cost</h5>
								<input type="number" name="other_cost" class="wp-form-control txt_rgt input-field other_cost plcehldr" id="input_box" placeholder="Other Cost" data-validation="Please enter the other cost">
								<?php //if (isset($validation)) { ?>
									<span class="col_red"><//?= display_error($validation, 'other_cost'); ?></span>
									<//?= $validation->listErrors(); ?>
								<?php //} ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-rupee col_blu"></span> Total Amount</h5>
								<input type="number" name="total" class="wp-form-control txt_rgt readonly_bg total-field plcehldr" id="input_box" placeholder="Total Amount" readonly>
								<?php //if (isset($validation)) { ?>
									<span class="col_red"><//?= display_error($validation, 'total'); ?></span>
									<//?= $validation->listErrors(); ?>
								<?php //} ?>
							</div>
						</div>
						<div class="row">
							<div class="col l12 m12 s12">
								<h5 class="h5_align1"><span class="fa fa-sticky-note-o col_blu"></span> Payment Note</h5>
								<textarea name="payment_note" id="payment_note" placeholder="Payment Note" class="wp-form-control plcehldr"></textarea>
							</div>
						</div>-->
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
	<script type = "text/javascript">
	// Form validation code will come here.
	//validatePayMode
	function validatePayMode(){ 
		let pay_mode = document.getElementById('pay_mode').value;
		//var second = document.getElementById('second').value;
		//pay_mode transaction_id 
		//alert(pay_mode);
		if(pay_mode=="Offline"){
			document.getElementById('transaction_id').value='' ; 
		}
	}
	
	function validate() {
		//receive_payment pay_mode transaction_id payment_date
		let flag = true;	
		if( document.add_patient_payment.receive_payment.value == "" ) {
			$(".valid_err1").text("Please enter receive payment!");
			flag =  false;
			//return false;
		}
		else{
			$(".valid_err1").text("");
		}

		if( document.add_patient_payment.pay_mode.value === "" ) {
			$(".valid_err2").text("Please select pay mode!");
			flag =  false;
			//return false;
		}
		else if( document.add_patient_payment.pay_mode.value == "Online" ) {
			//alert(document.add_patient_payment.pay_mode.value );
			//var transaction_id = document.getElementById('transaction_id').value;
			//alert(transaction_id);
			if(document.add_patient_payment.transaction_id.value ==""){
				$(".valid_err3").text("Please enter transaction id!");
				flag =  false;
				//return false;
			}
			else{
				$(".valid_err2").text("");
				$(".valid_err3").text("");
			}
		}
		else{
			$(".valid_err2").text("");
			$(".valid_err3").text("");
		}

		if( document.add_patient_payment.payment_date.value == "" ) {
			$(".valid_err4").text("Please enter payment date!");
			flag =  false;
			//return false;
		}
		else{
			$(".valid_err4").text("");
		}

		if(!flag){
			return false;
		}

		return( true );
	}
	</script>
	<script>
		$(document).ready(function() {

			
			/*

			////////////////////placeholder hide////////////////////////////
			$('.receive_payment').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Enter Receive Payment');
				}
			});

			$('.pay_mode').focus(function() {
				$(this).removeAttr('placeholder');
			}).blur(function() {
				if ($(this).val() === '') {
					$(this).attr('placeholder', 'Enter Pay Mode');
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
			});*/

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