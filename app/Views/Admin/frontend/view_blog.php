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
	<title>View Blogs</title>
	<!---Include Css File --->
	<!---Include Css File --->
	<?//= View('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---Include Css File --->
	<style type="text/css">
		body {
			background: rgb(224, 227, 231)
		}
	</style>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start ---->
	<div style="margin-right: 15px;margin-left: 15px;">
		<div class="card">
			<div class="card-content" style="border-bottom: 1px dashed silver;padding: 5px;">
				<h5 style="font-weight: 500"><span class="fa fa-eye" style="color: #005197"></span> Publish Blogs Post Content</h5>
			</div>
			<div class="card-content">
				<div class="row">
					<div class="col l2 m2 s2">
						<h6 style="text-align: center;color: green;">
							<span style="border-bottom:  1px solid silver"><?php if (is_array($blogs) && isset($blogs[0]->created_date)) {
																				echo $blogs[0]->created_date;
																			} ?></span>
						</h6>
						<h6 style="text-align: center;color: green">
							<span style="border-bottom: 1px solid silver;"><?php if (is_array($blogs) && isset($blogs[0]->created_month)) {
																				echo $blogs[0]->created_month;
																			} ?></span>
						</h6>
						<h6 style="text-align: center;color: green">
							<span style="border-bottom: 1px solid silver;"><?php if (is_array($blogs) && isset($blogs[0]->created_year)) {
																				echo $blogs[0]->created_year;
																			} ?></span>
						</h6>
					</div>
					<div class="col l10 m10 s10">
						<?php if (is_array($blogs) && isset($blogs[0]->blog_image)) { ?>
							<img src="<?= base_url('public/uploads/frontend/blog_image/' . $blogs[0]->blog_image); ?>" style="width: 100%;height: 300px;">
						<?php } ?>
						<div class="row">
							<div class="col l6 m6 s6">
								<?php if (is_array($blogs) && isset($blogs[0]->doctor_name)) { ?>
									<h6 style="font-weight: 500;font-size: 14px;">By : <?= $blogs[0]->doctor_name; ?></h6>
								<?php } ?>
							</div>
							<div class="col l6 m6 s6">
								<?php if (is_array($blogs) && isset($blogs[0]->department_name)) { ?>
									<h6 style="font-weight: 500;font-size: 14px;">In : <?= $blogs[0]->department_name; ?></h6>
								<?php } ?>
							</div>
						</div>
						<h6 style="font-weight: 500;font-size: 18px;"><?php if (is_array($blogs) && isset($blogs[0]->blog_title)) {
																			echo $blogs[0]->blog_title;
																		} ?></h6>
						<h6 style="font-weight: 500;font-size: 16px;"><?php if (is_array($blogs) && isset($blogs[0]->blog_content)) {
																			$blogs[0]->blog_content;
																		} ?></h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!---Body Section End ---->


	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
</body>

</html>