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
	<title>Product Sale</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include  -->
	
	<?= view('Medical/medical_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start --->
	<div class="equl_mrgn">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="font_weght"><span class="fa fa-flask col_blu"></span>  Product Sale</h5>
			</div>
			<div class="card-content">
				<?= form_open('Medical_Accountant/add_to_Cart'); ?>
				<div class="row content">
					<!-- <div class="inner_content"> -->
						<div class="col l3 m3 s12">
							<!-- Dropdown -->
							<select id='product_id' name="product_id" class="productSelect smll_scren_mrgn">
								<option selected="" disabled="">Select Product</option>
								<?php if ($product_name) :
									count($product_name);
									foreach ($product_name as $pro_name) :
								?>
										<option value='<?= $pro_name->id; ?>'><?= $pro_name->med_name; ?></option>
									<?php endforeach; ?>
							</select>
							<span id="productError" class="col_red"></span>
							<?php else : ?>
								<h6 class="col_red">Product Not Found</h6>
							<?php endif; ?>
							<div id='result'></div>

						</div>

						<div class="col l3 m3 s12">
							<input type="number" name="quantity" class="nameError input_box back_wite" id="quantity" placeholder="Enter Quantity" required="">
							<span id="nameError" class="col_red"></span>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'quantity'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>

						<div class="col l2 m2 s12">
							<div class="custom-date-range-picker">
								<input type="date" name="expiry_date" class="startDateInput dateError input_box back_wite smll_scren_mrgn" id="expiry_date" placeholder="Select Expiry Date" required="" autocomplete="off">
								<span id="dateError" class="col_red"></span>
								<?php if (isset($validation)) { ?>
									<span class="col_red"><?= display_error($validation, 'expiry_date'); ?></span>
									<?= $validation->listErrors(); ?>
								<?php } ?>
							</div>
						</div>
						<div class="col l3 m3 s12">
							<input type="text" name="batch_number" id="batch_number" class="batchError input_box back_wite smll_scren_mrgn" placeholder="Enter Batch Number" required="">
							<span id="batchError" class="col_red"></span>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'batch_number'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col l1 m1 s12">
							<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light sub_btn btn_wdth_hgt smll_scren_mrgn">Add</button>
						</div>
					<!-- </div> -->
				</div>
				<?= form_close(); ?>

				<div class="row mrgn_50">
					<div class="col l12 m12 s12">
						<h5 class="font_weght">Your Cart</h5>
						<table class="table">
							<?php
							$customer_session_id = session()->get('customer_session_id');
							if (!isset(($customer_session_id)) || $customer_session_id == '') {
								return redirect()->to(base_url() . "/Accountant_login/accountant_login");
							}
							?>
							
							
							<div class="scroll-container">
								<tr class="bck_blu col_wite">
									<th>Product Name</th>
									<th>Unit Price</th>
									<th>Quantity</th>
									<th>Total Price</th>
									<th>Action</th>
								</tr>
								<?php if ($carts) :
									count($carts);
									$t_amount = 0;
									foreach ($carts as $cart) :
										$t_amount += ($cart->rate * $cart->quantity);
								?>
										<tbody>
											<!-- <td>
											<?php $get_company_name =  get_doctor_name('medicines', $cart->product_id); ?>
											<//?= $get_company_name[0]->id; ?>
										</td> -->
											<td>
												<?= $get_company_name[0]->med_name; ?>
											</td>
											<td>
												<?= $cart->rate; ?>
											</td>
											<td>
												<?= $cart->quantity; ?>
											</td>
											<td>
												<?php
												$sum_amount  = $cart->rate * $cart->quantity;
												echo number_format($sum_amount);
												
												?>
											</td>
											<td>
												<?php if (isset($cart->id)) : ?>
													<a href="<?= base_url('Medical_Accountant/delete_cart_product/' . $cart->id); ?>" onclick="return confirm('Are you sure you want to  Remove this Product ?..');"><span id="trash" class="fa fa-trash-o"></span></a>
												<?php endif; ?>
											</td>

										</tbody>
									<?php endforeach; ?>
									<tr class="backgrnd_colr_gray">
										<td class="font_weght">
											Total Amount
										</td>
										<td></td>
										<td></td>
										<td>
											<?= number_format($t_amount); ?>
										</td>
										<td>

										</td>
									</tr>
								<?php else : ?>
									<h6 class="col_red">Products Not Available in your Carts</h6>
								<?php endif; ?>
						</table>
						<br>
						<?php if (isset($cart) && ($cart->id == null)) : ?>
							<center>
								<a onclick="M.toast({html: 'Please Add Product Fisrt '})" class="btn btn-waves-effect waves-light sub_btn_blck"><span class="fa fa-print"></span>  Generate Payment Bill</a>
							</center>
							<?php if (isset($cart->session_id)) { ?>
								<center>
									<a href="<?= base_url('Medical_Accountant/check_out_products/' . $cart->session_id); ?>" class="btn btn-waves-effect waves-light sub_btn_blck"><span class="fa fa-print"></span>  Generate Payment Bill</a>
								</center>
							<?php } ?>
						<?php endif; ?>
						<?php if (isset($cart->session_id)) { ?>
							<center>
								<a href="<?= base_url('Medical_Accountant/check_out_products/' . $cart->session_id); ?>" class="btn btn-waves-effect waves-light sub_btn_blck"><span class="fa fa-shopping-cart"></span>  Checkout</a>
							</center>
						<?php } ?>
						<?= form_close(); ?>

					</div>
				</div>

			</div>
		</div>
	</div>
	</div>
	<!---Body Section End --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(document).ready(function() {
			// Initialize select2
			// $("#product_id").select2();
			$("#btn_register_now").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;

				//Department validation
				const productSelect = $(".productSelect");
				const productError = $("#productError");
				if (productSelect.val() === null || productSelect.val() === "") {
					productError.text("Please Select The Product");
					valid = false;
				} else {
					productError.text("");
				}


				// Name validation
				const nameInput = $(".nameError");
				const nameError = $("#nameError");
				if (nameInput.val().trim() === "") {
					nameError.text("Please Enter Quantity ");
					valid = false;
				} else {
					nameError.text("");
				}


				//Address validation 
				const dateInput = $(".dateError");
				const dateError = $("#dateError");
				if (dateInput.val().trim() === "") {
					dateError.text("Please Select The Expiry Date");
					valid = false;
				} else {
					dateError.text("");
				}

				//Address validation 
				const batchInput = $(".batchError");
				const batchError = $("#batchError");
				if (batchInput.val().trim() === "") {
					batchError.text("Please Enter Batch Number");
					valid = false;
				} else {
					batchError.text("");
				}
				if (!valid) { e.preventDefault(); }
			});
		});
	</script>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<?= view('Medical/date_pick_js_file.php'); ?>
	
	<!---Js file Include -->
</body>

</html>
