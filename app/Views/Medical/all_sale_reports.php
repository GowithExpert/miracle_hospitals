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
	<title>Manage Sale Records</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<!---CSS File Include  -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
	<!----Body Section Start --->
	<div class="card" style="box-shadow: none;margin-right: 15px;margin-left: 15px;">
		<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
			<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-flask" style="color: #ff3d00"></span>  Manage All Sales Report <span class="right"><a href="#!" class="modal-trigger btn_hver" data-target="customize_sale_modal" style="font-size: 15px;font-weight: 500;">
		</div>
		<?php if ($sales) : ?>
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 5px;">
				<span class="fa fa-filter"></span>  Customize Sales</a></span></h5>
				<h6 style="color: grey;font-size: 15px; font-weight: 500;"> Date : <?= date('d-M-Y'); ?>
					<span class="right">
						<a href="<?= base_url('Medical_Accountant/all_sale_reports'); ?>" style="font-size: 15px;color: red;">
							Reset</a>
					</span>
				</h6>

				<!--Customize Sale Modal Section Start --->
				<div class="modal" id="customize_sale_modal">
					<div class="modal-content" style="padding: 10px;border-bottom: 1px solid silver;">
						<h6 style="font-size: 20px; color: #333; font-weight: 500;">
							<span class="fa fa-filter"></span>  Customize Sales Report
							<span class="close-button" onclick="closeModal()">&times;</span>
						</h6>
					</div>
					<div class="modal-content" style="padding: 10px;">
						<?= form_open('Medical_Accountant/search_sales'); ?>
						<div class="row" style="margin-bottom: 0px;margin-top: 10px;">
							<div class="col l6 m6 s12">
								<input type="date" name="start_date" id="input_box" required>
							</div>
							<div class="col l6 m6 s12">
								<input type="date" name="last_date" id="input_box" required>
							</div>
							<div class="col l12 m12 s12">
								<button type="submit" class="btn waves-effect waves-light" style="background: #005197; text-transform: capitalize;font-weight: 500;font-size: 14px;  margin-top: 10px;">
									Search Reports
								</button>
							</div>
						</div>
					</div>
					<?= form_close(); ?>
				</div>
				<!--Customize Sale Modal Section End --->
			</div>
			<div class="scroll-container card-content">
				<table class="tbale">
					<tr style="background: #f2f2f2;">
						<th style="text-align: center;">DATE</th>
						<th>CUSTOMER</th>
						<th style="text-align: right;">UNIT SALES</th>
						<th style="text-align: right;">TOTAL AMOUNT</th>
					</tr>
					<?php if ($sales) :
						count($sales);
						foreach ($sales as $all_sale) :
					?>
							<tr>
								<td class="txt_break-at200" style="text-align: center; font-size: 14px;color: black;font-weight: 500;">
									<span style="color: green">  <?= date('D, M. d Y', strtotime($all_sale->order_date)); ?></span>
								</td>
								<td class="txt_break-at200" style="font-size: 14px;font-weight: 500;color: black;">(<?= date('D, M. d Y', strtotime($all_sale->order_date)); ?>) Customers <br />
									<?php
									$total_customer  = get_all_customer('order_product', $all_sale->order_date);
									?>
									<?php
									$i = 0;
									if ($total_customer) :
										count($total_customer);
										foreach ($total_customer as $total_cus) :
											$i++;
									?>
											<i><span style="color: grey;">Sold By : <?= $total_cus->customer_name; ?></span></i> <br />
										<?php endforeach;
									else : ?>
										<i>Customer Not Found's</i>
									<?php endif; ?>
								</td>
								<td class="txt_break-at200" style="text-align: right;">

									<?php
									$i = 0;
									if ($total_customer) :
										count($total_customer);
										foreach ($total_customer as $total_cus) :
											$i++;
									?>
											<i><span style="color: grey;">Unit : <?= $total_cus->total_quantity; ?></span></i> <br />
										<?php endforeach;
									else : ?>
										<i style="color: red;">Quantity Not Found's</i>
									<?php endif; ?>
								</td>
								<td class="txt_break-at200" style="text-align: right;">

									<?php
									$i = 0;
									if ($total_customer) :
										count($total_customer);
										foreach ($total_customer as $total_cus) :
											$i++;
									?>
											<i><span style="color: grey;"><span class="fa fa-rupee-sign"></span>  
													<?= number_format($total_cus->total_amount, 2, '.', ','); ?> /-</span></i> <br />
										<?php endforeach;
									else : ?>
										<i style="color: red;">Amount Not Found's</i>
									<?php endif; ?>
								</td>
							</tr>

						<?php endforeach; ?>
					<?php else : ?>
						<h6>Sales Not Found</h6>
					<?php endif; ?>
					
				</table>
			<?php else : ?>
				<h6 style="color: red;padding:10px">No Record Found</h6>
			<?php endif; ?>
			</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$(function() {
			// Disable upcoming dates in calendar
			var dtToday = new Date();
			var month = dtToday.getMonth() + 1;
			var day = dtToday.getDate();
			var year = dtToday.getFullYear();

			if (month < 10)
				month = '0' + month.toString();
			if (day < 10)
				day = '0' + day.toString();

			var minDate = year + '-' + month + '-' + day;
			$('#input_box').attr('max', minDate);
		});

		function closeModal() {
			var modal = document.getElementById("customize_sale_modal");
			modal.style.display = "block";
		}
	</script>
	<!----Body Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>