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
	<title>Activate Account</title>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}
	</style>
</head>

<body>
	<!---Body Section Start --->
	<!---Php Meassge Show --->

	<div class="container">
		<div class="card" style="background: black;margin-top: 5%;">
			<div class="card-content" style="border-bottom: 1px dashed silver">
				<div style="margin-left: 20px;margin-right: 10px">
					<?php if (isset($success)) : ?>
						<div class="card" style="background-color: black">
							<div class="card-content" style="margin-left: 10px;margin-right: 10;padding: 10px; background: green;color: white;font-weight: 500">
								<span class="fa fa-check"></span>  <?= $success; ?>
							</div>
						</div>

					<?php endif; ?>
					<?php if (isset($error)) : ?>
						<div class="card" style="background-color: black">
							<div class="card-content" style="margin-left: 10px;margin-right: 10;padding: 10px; background: red;color: white;font-weight: 500">
								<span class="fa fa-times"></span>  <?= $error; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="card-content">
				<center>
					<a href="<?= base_url('Accountant_login/accountant_login'); ?>" class="btn btn-waves-effect waves-light" style="background: #005197;text-transform: capitalize;font-weight: 500;border-radius: 5px;"><span class="fa  fa-key"></span> Go to Login Account</a>
				</center>
			</div>
		</div>
	</div>




	<!---Php Meassge Show --->
	<!---Body Section End --->

	<?= view('Admin/js_file.php'); ?>
</body>

</html>