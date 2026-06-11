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
	<title>Medical Accountant</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<!---Include Css File --->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
	<!----Body Section Start --->
	<div class="row">
		<div class="col l3 m3 s12">
			<a class="txt_hver" href="<?= base_url('Medical_Accountant/manage_company'); ?>">
				<div class="card bck_blu">
					<div class="card-content">
						<div class="row">
							<div class="col l4 m4 s4">
								<h4><span class="fa fa-tasks col_wite"></span></h4>
							</div>
							<div class="col l8 m8 s8">
								<h6 class="right-align txt_hver">Total Company </h6>
								<h4 class="right-align txt_hver custm_mrgn">
									<?php if ($total_company) :
										echo count($total_company);
									?>
									<?php else : ?>
										<h6 class="txt_hver txt_rgt">0</h6>
									<?php endif; ?>
									
								</h4>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col l3 m3 s12">
			<a class="txt_hver" href="<?= base_url('Medical_Accountant/manage_medicine'); ?>">
				<div class="card bckgrnd_orng">
					<div class="card-content">
						<div class="row">
							<div class="col l4 m4 s4">
								<h4><span class="fa fa-hourglass-start col_wite"></span></h4>
							</div>
							<div class="col l8 m8 s8">
								<h6 class="right-align txt_hver">Total Products </h6>
								<h4 class="right-align txt_hver custm_mrgn">
									<?php if ($total_products) :
										echo count($total_products);
									?>
									<?php else : ?>
										<h6 class="txt_hver txt_rgt">0</h6>
									<?php endif; ?>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col l3 m3 s12">
			<a class="txt_hver" href="<?= base_url('/Medical_Accountant/all_sale_reports'); ?>">
				<div class="card bckgrnd_red">
					<div class="card-content">
						<div class="row">
							<div class="col l8 m8 s8">
								<h6 class="txt_hver"><span class="fa fa-users col_wite"></span>  Customer's</h6>

								<h6 class="txt_hver"><span class="txt_hver" id="show_customers"></span></h6>

								<span id="show_customer_heading" class="txt_hver">No Data</span>
							</div>
							<div class="col l4 m4 s4">
								<span class="right">
									<h6 class="txt_hver col_wite">
										<span class="fa fa-ellipsis-v dropdown-trigger col_wite elipses" data-target="customer_dropdown"></span>
									</h6>
								</span>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col l3 m3 s12">
			<div class="card bckgrnd_gren">
				<div class="card-content">
					<div class="row">
						<div class="col l8 m8 s8">
							<h6 class="txt_hver col_wite"><span class="fa fa-rupee-sign col_wite"></span>  Total Income</h6>
							<h6 class="txt_hver col_wite"><span id="show_income"></span></h6>
							<span id="show_income_heading" class="txt_hver col_wite">No Data</span>
						</div>
						<div class="col l4 m4 s4">
							<span class="right">
								<h6 class="txt_hver col_wite">
									<span class="fa fa-ellipsis-v dropdown-trigger col_wite" data-target="income_dropdown"></span>
								</h6>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col l12 m12 s12">
				<div class="card">
					<div class="card-content">
						<div id="chartContainer" class="chartContainer"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!----Body Section End --->

	<!-----Customer Dropdown Section Start --->
	<ul class="dropdown-content" id="customer_dropdown">
		<li><a href="#!" onclick="count_customers('today')">Today Customer</a></li>
		<li><a href="#!" onclick="count_customers('yesterday')">Previous Day Customer</a></li>
		<li><a href="#!" onclick="count_customers('last_30_days')">Last 30 Days Customer</a></li>
		<div class="divider"></div>
		<li><a href="#!" onclick="count_customers('all')">All Customer</a></li>
	</ul>
	<!-----Customer Dropdown Section End --->

	<!---Income Dropdown Section Start --->
	<ul class="dropdown-content" id="income_dropdown">
		<li><a href="#!" onclick="count_income('today')">Today Income</a></li>
		<li><a href="#!" onclick="count_income('yesterday')">Previous Day Income</a></li>
		<li><a href="#!" onclick="count_income('last_30_days')">Last 30 Days Income</a></li>
		<div class="divider"></div>
		<li><a href="#!" onclick="count_income('all')">All Income</a></li>
	</ul>
	<!---Income Dropdown Section End --->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"> </script>
	<!---Custom js File Include ---->
	<?= view('Medical/custom_js.php'); ?>
	<!---Custom js File Include ---->
	</script>
</body>

</html>