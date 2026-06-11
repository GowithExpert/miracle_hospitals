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
	<title>Add Medicine Stock</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<!---Include Css File --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Medical/medical_css_file.php'); ?>
	<!---Include Css File --->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
	<div class="container">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="hedng2 dr_detil"><span class="fas fa-capsules col_blu"></span>  Add Medicine Stock</h5>
			</div>
			<div class="card-content">
				<?= form_open('Medical_Accountant/update_stock/' . $medicines[0]->id); ?>
				
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fas fa-capsules col_blu"></span> Product Name</h6>
						<input type="text" name="pro_name" id="input_box" class="readonly_bg" value="<?php if (is_array($medicines) && isset($medicines[0]->med_name)) {
																											echo $medicines[0]->med_name;
																										} ?>" readonly>
						<?php
						if (is_array($medicines) && isset($medicines[0]->med_company)) {
							$get_company_name =  get_doctor_name('medicine_category', $medicines[0]->med_company);
						}
						?>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="far fa-building col_blu"></span> Product Company</h6>
						<input type="text" name="pro_name" id="input_box" class="readonly_bg" value="<?php if (is_array($medicines) && isset($medicines[0]->med_name)) {
																											echo $medicines[0]->med_company;
																										} ?>" readonly>
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-line-chart col_blu"></span> All Stock</h6>
						<input type="text" name="pro_name" id="input_box" class="readonly_bg" value="<?php if (is_array($medicines) && isset($medicines[0]->med_name)) {
																											echo $medicines[0]->med_stock;
																										} ?>" readonly>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-line-chart col_blu"></span> Add Stock</h6>
						<input type="text" name="med_stock" id="input_box" placeholder="Add Stock" required="">
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-line-chart"></span> Add Stock</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>