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
	<title>Add Blood Selling Price</title>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<style type="text/css">
		h6 {
			font-weight: 500;
			font-size: 16px;
		}
	</style>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!-----Body Section Start ------>
	<div class="container">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-users" style="color: #005197"></span>Add Blood Selling Price</h5>
			</div>
			<?= form_open('Blood_bank/blood_selling_price/' . $donor_blood[0]->id); ?>
			<div class="card-content">
				<!--
				<h6>Donor Blood Group</h6>
				<input type="text" name="donor_blood" id="input_box" value="<//?= $donor_blood[0]->blood_group;  ?>" readonly>
				<h6>Blood Unit</h6>
				<input type="text" name="donor_unit" id="input_box" value="<//?= $donor_blood[0]->blood_unit;  ?>" readonly>
				<h6>Blood Price</h6>
				<input type="text" name="donor_price" id="input_box" value="<//?= $donor_blood[0]->blood_price;  ?>" readonly>
				<h6>Blood Selling Price</h6>-->
				<input type="text" name="selling_price" id="input_box" placeholder="Enter Selling Price" required="">
				<center>
					<button type="submit" class="btn btn-waves-effect waves-light" style="background: red;font-weight: 500;text-transform: capitalize;">Add Blood Price</button>
				</center>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
	<!-----Body Section End   ------>

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
</body>

</html>