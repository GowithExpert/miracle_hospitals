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
	<title>Add Blood Stock</title>
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<!---Include Css File --->
	<?//= View('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Home/css_file'); ?>
	<style type="text/css">
		h6 {
			font-weight: 600;
			font-size: 14px;
		}

		.input-container {
			position: relative;
		}

		.asterisk-symbol {
			position: absolute;
			top: 36%;
			left: 6px;
			transform: translateY(-50%);
			color: red;
			visibility: visible;
			opacity: 1;
			transition: visibility 0s, opacity 0.2s;
		}

		.asterisk-hidden .asterisk-symbol {
			visibility: hidden;
			opacity: 0;
		}

		a {
			font-size: 1.4rem !important;
		}

		a:hover {
			color: #fff;
		}

		.btn_hver:hover {
			color: #fff;
		}

		h5 {
			font-size: 22px !important;
			font-weight: 600 !important;
		}
	</style>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!------Body Section Start ---->
	<div class="container">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 600"><span class="fa fa-bug" style="color: #005197"></span>  Add Blood Group
				</h5>
			</div>
			<?= form_open('Blood_bank/add__total_blood_stock'); ?>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blood Group Name</h6>
						<div class="input-container">
							<input type="text" name="blood_group" class="asterisk" id="input_box" value="<?= set_value('blood_group'); ?>" placeholder="Enter Blood Group">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'blood_group'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blood Unit</h6>
						<div class="input-container">
							<input type="text" name="total_blood_unit" class="asterisk" value="<?= set_value('total_blood_unit'); ?>" id="input_box" placeholder="Enter Total Blood Unit">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'total_blood_unit'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Blood Price 1 Unit</h6>
						<div class="input-container">
							<input type="number" name="blood_price" class="asterisk" value="<?= set_value('blood_price'); ?>" id="input_box" placeholder="Enter Blood Price">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'blood_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6>Total Blood Price</h6>
						<div class="input-container">
							<input type="number" name="total_blood_price" class="asterisk" value="<?= set_value('total_blood_price'); ?>" id="input_box" placeholder="Enter Total Blood Price">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'total_blood_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<center>
					<button type="submit" class="btn btn-waves-effect waves-light btn_hver" style="background: #005197;font-weight: 500;text-transform: capitalize;">Add Blood Group</button>
				</center>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
	<!------Body Section End ---->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>

</html>