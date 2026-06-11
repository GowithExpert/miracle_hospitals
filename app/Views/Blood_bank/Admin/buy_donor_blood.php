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
	<title>Donor Blood Transition</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
	<!----Css file Include --->
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!-----Body Section Start ----->
	<div class="container-fluid">
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-user col_blu"></span> Buy Donor Blood</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<?= form_open('Blood_bank/buy_blood_donor'); ?>
				<div class="card-content">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6><span class="fas fa-user col_blu"></span> Blood Donor Name</h6>
							<div class="select-wrapper">
								<select id='blood_donors' name="blood_donors" class="nameSelect">
									<option selected="" disabled="">Select Blood Donors Name</option>
									<?php if ($blood_donor):
										count($blood_donor);
										
										foreach ($blood_donor as $donors):
											?>
									<option value='<?= $donors->id; ?>'>
										<?= $donors->donor_name; ?>
									</option>
									<?php endforeach; ?>
								</select>
								<span id="nameError" class="col_red valid_err"></span>
							</div>
							<?php else: ?>
							<h6 class="col_red">Blood Donors Not Found</h6>
							<?php endif; ?>
							<?php if (isset($validation)) { ?>
							<span class="span_red_colr">
								<?= display_error($validation, 'blood_donors'); ?>
							</span>
							<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6><span class="fas fa-fire-alt col_blu"></span> Blood Unit</h6>
							<div class="input-container">
								<input type="text" name="blood_unit" class="asterisk unitError" id="input_box" placeholder="Enter Blood Unit">
								<span class="asterisk-symbol">*</span>
								<span id="unitError" class="col_red valid_err"></span>
							</div>
							<?php if (isset($validation)) { ?>
							<span class="span_red_colr">
								<?= display_error($validation, 'blood_unit'); ?>
							</span>
							<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6><span class="fas fa-fire-alt col_blu"></span> Blood Group</h6>
							<div class="select-wrapper">
								<select name="blood_group" id="blood_group" class="groupSelect">
									<option selected="" disabled="">Select Blood Group</option>
									<?php if ($blood_donor):
										count($blood_donor);
										
										foreach ($blood_donor as $donors):
											?>
									<option value='<?= $donors->id; ?>'>
										<?= $donors->blood_group; ?>
									</option>
									<?php endforeach; ?>
								</select>
								<span id="nameError" class="col_red valid_err"></span>
							</div>
							<?php else: ?>
							<h6 class="col_red">Blood Group Not Found</h6>
							<?php endif; ?>
							<?php if (isset($validation)) { ?>
							<span class="span_red_colr">
								<?= display_error($validation, 'blood_donors'); ?>
							</span>
							<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h6><span class="fas fa-rupee col_blu"></span> Blood Price</h6>
							<div class="input-container">
								<input type="text" name="blood_price" class="asterisk priceError" id="input_box" placeholder="Enter Blood Price">
								<span class="asterisk-symbol">*</span>
								<span id="priceError" class="col_red valid_err"></span>
							</div>
							<?php if (isset($validation)) { ?>
							<span class="span_red_colr">
								<?= display_error($validation, 'blood_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
							<?php } ?>
						</div>
					</div>
					<center>
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span> Buy Donor Blood</button>
					</center>
				</div>
			<?= form_close(); ?>
		</div>
	</div>
	<!-----Body Section End   ----->

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<script>
		//Blood Donor Group Depedencies
		$(document).ready(function () {

			$('#blood_donors').change(function () {
				var id = $(this).val();
				$.ajax({
					url: '<?php echo site_url('Blood_bank/get_donor_blood_group/'); ?>' + id,
					method: "POST",
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function (data) {
						var html = '';
						var i;
						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].blood_group + '>' + data[i].blood_group + '</option>';
							$('#blood_group').html(html);
						}
					}
				});
				return false;
			});
			$("#btn_register_now").click(function (e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;


				//Choose Blood Group  validation
				const nameSelect = $(".nameSelect");
				const nameError = $("#nameError");
				if (nameSelect.val() === null || nameSelect.val() === "") {
					nameError.text("Please Select The Blood Donor Name");
					valid = false;
				} else {
					nameError.text("");
				}

				// // Blood Price (1 Unit)  validation
				// const bloodpriceSelect = $(".bloodpriceSelect");
				// const bloodpriceError = $("#bloodpriceError");
				// if (bloodpriceSelect.val() === null || bloodpriceSelect.val() === "") {
				// 	bloodpriceError.text("Please select Blood Price (1 Unit)");
				// valid = false;
				// } else {
				// 	drnameError.text("");
				// }

				// unit validation
				const unitInput = $(".unitError");
				const unitError = $("#unitError");
				if (unitInput.val().trim() === "") {
					unitError.text("Please enter Blood Unit");
					valid = false;
				} else {
					unitError.text("");
				}

				// blood group validation
				const groupSelect = $(".groupSelect");
				const groupError = $("#groupError");
				if (groupSelect.val() === null || groupSelect.val() === "") {
					groupError.text("Please select The Blood Group");
					valid = false;
				} else {
					groupError.text("");
				}

				// // unit validation
				// const nameInput = $(".usernameError");
				// const usernameError = $("#usernameError");
				// if (nameInput.val().trim() === "") {
				// 	usernameError.text("Please enter The Username");
				// 	valid = false;
				// } else {
				// 	usernameError.text("");
				// }

				// price validation
				const priceInput = $(".priceError");
				const priceError = $("#priceError");
				if (priceInput.val().trim() === "") {
					priceError.text("Please enter The Blood Price");
					valid = false;
				} else {
					priceError.text("");
				}
				if (!valid) {
					e.preventDefault();
				}
			});
		});
		//Blood Donor Group Depedencies
	</script>
</body>

</html>
