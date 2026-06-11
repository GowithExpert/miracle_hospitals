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
	<title>Add Department</title>
	<?//= view('Admin/css_file.php'); ?>
	<!---CSS File Include -->
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
		}

		textarea {
			border: 1px solid silver;
			padding: 10px;
			outline: none;
			height: 100px;
			resize: none;
			border-radius: 3px;
		}

		span {
			cursor: pointer;
		}

		h6 {
			font-weight: 500
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Blood_bank/Donor/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card" style="box-shadow: none;">
			<div class="card-content" style="border-bottom: 1px solid silver;padding: 10px;">
				<h5 style="font-weight: 500;margin-top: 5px; font-size: 20px;"><span class="fa fa-edit" style="color: green"></span>  Edit Department</h5>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Frontdesk/update_department/' . $department[0]->id); ?>
				<h6>Department Name</h6>
				<input type="text" name="department_name" value="<?= $department[0]->department_name; ?>" id="input_box" required>

				<h6>Department Discription</h6>
				<textarea name="dep_desc"><?= $department[0]->dep_desc; ?></textarea>
				<center>
					<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light" style="text-transform: capitalize;font-weight: 500;font-size: 16px;background: #005197"><span class="fa fa-edit"></span>  Edit Department</button>
				</center>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
	<!---Body Section Start -->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>