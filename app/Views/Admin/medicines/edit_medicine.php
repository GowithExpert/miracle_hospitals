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
	<title>Edit Medicine</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
	<!---CSS File Include  -->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!---CSS File Include  -->
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
		<div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fas fa-capsules col_blu"></span>  Edit Medicines </h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<?= form_open_multipart('Admin/update_medicines/' . $medicines[0]->id); ?>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="far fa-building col_blu"></span> Medicine Category Name</h6>
						<div class="select-wrapper">
							<select name="med_company" id="doctor" class="asterisk departmentSelect">
								<?php if (count($medicine)) :
									foreach ($medicine as $medi_name) :
								?>
										<?php if ($medicines[0]->med_company == $medi_name->id) : ?>
											<option value="<?= $medi_name->id; ?>" selected><?= $medi_name->category_name; ?></option>
										<?php else : ?>
											<option value="<?= $medi_name->id; ?>"><?= $medi_name->category_name; ?></option>
										<?php endif; ?>
									<?php endforeach;
								else : ?>
									<option value="">Medicine Not Found's</option>
								<?php endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
						</div>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-rupee col_blu"></span> Medicine Price</h6>
						<div class="input-container">
							<input type="text" name="med_price" id="input_box" class="asterisk" placeholder="Enter medicine price" value="<?= $medicines[0]->med_price; ?>" >
							<span class="asterisk-symbol">*</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="far fa-calendar-alt col_blu"></span> Expiry Date</h6>
						<div class="input-container">
							<input type="date" name="expiry_date" id="input_box" class="asterisk" value="<?= $medicines[0]->expiry_date; ?>" >
							<span class="asterisk-symbol">*</span>
						</div>
					</div>
					<div class="col l6 m6 s6">
						<h6><span class="fas fa-capsules col_blu"></span> Medicine Name</h6>
						<div class="input-container">
							<input type="text" name="med_name" id="input_box" class="asterisk" placeholder="Enter medicine name" value="<?= $medicines[0]->med_name; ?>" >
							<span class="asterisk-symbol">*</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-rupee col_blu"></span> Medicine Discount Price</h6>
						<input type="text" name="med_d_price" id="input_box" placeholder="Enter medicine discount price" value="<?= $medicines[0]->med_d_price; ?>" >
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-tasks col_blu"></span> Batch Number</h6>
						<div class="input-container">
							<input type="text" name="batch_number" id="input_box" class="asterisk" placeholder="Enter batch number" value="<?= $medicines[0]->batch_number; ?>" >
							<span class="asterisk-symbol">*</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-line-chart col_blu"></span> Medicine Stock</h6>
						<div class="input-container">
							<input type="text" name="med_dis" id="input_box" class="asterisk" placeholder="Enter medicine stock" value="<?= $medicines[0]->med_stock; ?>">
							<span class="asterisk-symbol">*</span>
						</div>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-info-circle col_blu" placeholder="Enter other info"></span> Other Info</h6>
						<input type="text" name="other_info" id="input_box">
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fas fa-capsules"></span>  Update Medicine</button>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
	</div>
	<!---Body Section End -->

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>
</html>