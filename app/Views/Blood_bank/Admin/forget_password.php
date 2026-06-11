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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<title>Forget Password</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/back.png') ?>)no-repeat fixed;
			background-size: 100% 100%
		}

		#input_box {
			border: 1px solid silver;
			box-shadow: none;
			box-sizing: border-box;
			padding-left: 10px;
			padding-right: 10px;
			height: 40px;
			border-radius: 3px;
			color: black
		}
		.icon_styl{
			font-size: 50px;
			color: #057CF4;
    		display: block;
    		margin-top: 25px;
			text-align: center;
		}
		.for_got_align{
			font-weight: 500;
    		color: black;
    		text-align: center;
    		margin-top: 25px;
		}
		.para_styl{
			font-size: 10px;
    		/* margin: 0px 40px 0px !important; */
    		text-align: center;
    		color: gray;
		}
		p a {
            font-size: 0.8em;
            text-decoration: none;
            display: flex;
            align-items: center;
			justify-content: center;
			color: gray;
        }

        /* Custom styling for the icon */
        p a i {
            font-size: 1.5em;
            margin-right: 5px;
			color: #333;
        }

	</style>
</head>

<body>

	<div class="container">
		<div class="row" style="margin-top: 10%">
			<div class="col l4 m12 s12"></div>
			<div class="col l4 m12 s12">
				<!---Php Meassge Show --->
				<div>
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card" style="background-color: green">
							<div class="card-content" style="padding: 10px; background: green;color: white;font-weight: 500">
								<span class="fa fa-check"></span>    <?= session()->getTempdata('success'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card" style="background-color: red">
							<div class="card-content" style="padding: 10px; background: red;color: white;font-weight: 500">
								<span class="fa fa-times"></span>    <?= session()->getTempdata('error'); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<!---Php Meassge Show --->
				<?= form_open(); ?>
				<div class="card brdr_rdius" style="background: #f9f9f9;box-shadow: none; box-shadow: 3px 2px 8px;">
					<div class="card-content" style="padding: 5px;">
						<i class="fa fa-exclamation-circle icon_styl"></i>
						<h6 class="for_got_align">  Forget Password</h6>
						<p class="para_styl">Enter your email and we'll send you a link to reset your password</p>
					</div>
					<div class="card-content">
						<input type="text" name="email" maxlength="40" id="input_box" class="inpt_area" value="<?= set_value('email'); ?>" placeholder="Enter Email Id">
						<?php if (isset($validation)) { ?>
							<span style="color: red"><?= display_error($validation, 'email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
						<center>
							<button type="submit" class="btn btn-waves-effect waves-light frgt_pass_submit">Submit</button>
						</center>
						<br>
						<center>
							<p><a href="<?= base_url('/Patients_login/login'); ?>"><i class="fa fa-angle-left"></i> Back to Login</a></p>
						</center>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
			<div class="col l4 m12 s12"></div>
		</div>
	</div>


	<?= view('Admin/js_file.php'); ?>
</body>

</html>