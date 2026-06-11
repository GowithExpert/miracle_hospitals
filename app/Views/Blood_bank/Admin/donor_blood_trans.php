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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
	<link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script> -->
	<?= helper('Form'); ?>
	<!----Css file Include --->
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!----Css file Include --->
	<?= view('Home/css_file'); ?>
	<?= view('Blood_bank/Admin/blood_bank_css_file.php'); ?>
</head>

<body>
	<!----Top Bar Section Start ---->
	<?= view('Blood_bank/Admin/top_bar'); ?>
	<!----Top Bar Section Start ---->

	<!-----Donor Blood Transition ------>
	<div class="container-fluid">
		<div class="card">
			<!--- Success/Failure Msg -START --->
			<div style="position: relative;">
				<?php if (session()->getTempdata('success')) : ?>
					<div class="card success-message cutom_messge_styl">
						<div class="card-content" id="succss_msg"><?= session()->getTempdata('success'); ?></div>
					</div>
				<?php endif; ?>
				<?php if (session()->getTempdata('error')) : ?>
					<div class="card error-message cutom_messge_styl">
						<div class="card-content" id="error_msg"><?= session()->getTempdata('error'); ?></div>
					</div>
				<?php endif; ?>
			</div>
       		<!--- Success/Failure Msg END--->
			
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-exchange col_blu"></span>  Donor Blood Transition</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<!-- <//?= form_open_multipart('Blood_bank/donor_blood_transition'); ?> -->
			<?= form_open_multipart('Blood_bank/donor_blood_transition', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt col_blu"></span> Select Blood Group</h6>
						<div class="select-wrapper">
							<select name="blood_group" id="blood_group" class="nameSelect genderSelect">
							<!--	<option selected="" disabled="">Select Blood Transition</option>
								<?php //if ($donor_blood) :
									//count($donor_blood);
									//foreach ($donor_blood as $blood) : ?>
										<option value="<//?= $blood->id; ?>"><//?= $blood->blood_group; ?></option>
									<?php //endforeach; ?>
								<?php //else : ?>
									<h6 class="span_red_colr">Blood Group Not Found</h6>
								<?php //endif; ?>-->

								<!-- -->
								<option selected="" disabled="">Select Blood Transition</option>
								<?php if ($blood_groups) :
									foreach ($blood_groups as $blood_group) : ?>
										<option value="<?= $blood_group->id; ?>"><?= $blood_group->blood_group; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="span_red_colr">Blood Group is not found</h6>
								<?php endif; ?>
								</select>
								<!-- -->


							</select>
							<span class="mandatory redStargenderSelect">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'blood_group'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<!--<h6>Blood Price</h6>
					<select id="blood_price" name="blood_price">
					<option value="" ></option>
					</select>-->
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-rupee col_blu"></span> Blood Price</h6>
						<div class="select-wrapper">
							<select name="blood_price" id="blood_price" class="bloodpriceSelect">
								<option selected="" disabled="">Blood Price</option>
								<?php if ($donor_blood) :
									count($donor_blood);
									foreach ($donor_blood as $blood) : ?>
										<option value="<?= $blood->id; ?>"><?= $blood->blood_price; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="span_red_colr">Blood price not found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStarbloodpriceSelect">*</span>
							<span id="bloodpriceError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'blood_price'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt col_blu"></span> Blood Unit</h6>
						<div class="select-wrapper">
							<select name="blood_unit" id="blood_unit" class="unitSelect">
								<option selected="" disabled="">Blood Unit</option>
								<?php if ($donor_blood) :
									count($donor_blood);
									foreach ($donor_blood as $blood) : ?>
										<option value="<?= $blood->id; ?>"><?= $blood->blood_unit; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="span_red_colr">Blood unit not found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStarunitSelect">*</span>
							<span id="unitError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'blood_unit'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-rupee col_blu"></span> Selling Blood Group</h6>
						<div class="select-wrapper">
							<select id="blood_group_sale" name="blood_group_sale" class="sellingSelect">
								<option selected="" disabled="">Select Selling Blood Group</option>
								<?php if ($blood_groups) :
									foreach ($blood_groups as $blood_group) : ?>
										<option value="<?= $blood_group->blood_group; ?>"><?= $blood_group->blood_group; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="span_red_colr">Blood Group is not found</h6>
								<?php endif; ?>
								</select>
							<span class="mandatory redStarsellingSelect">*</span>
							<span id="sellingError" class="col_red valid_err"></span>
						</div>
					</div>
					<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'blood_group_sale'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-user col_blu"></span> User Name</h6>
						<div class="input-container">
							<input type="text" name="username" class="asterisk usernameError" value="<?= set_value('username'); ?>" id="input_box" placeholder="Enter Username">
							<span class="asterisk-symbol">*</span>
							<span id="usernameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'username'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-mobile-alt col_blu"></span> Mobile</h6>
						<div class="input-container">
							<input type="tel" name="mobile" maxlength="10" class="phone-input asterisk phoneError phone_mandatory" value="<?= set_value('mobile'); ?>" id="input_box" placeholder="Enter Mobile Number">
							<span class="asterisk_phone">*</span>
							<span id="phoneError" class="col_red valid_err_phn"></span>
						</div>
						<!-- Container for the country code dropdown -->
						<div id="country_selector" class="margn_top"></div>
						<input type="hidden" id="country_code" name="country_code" value="">
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'mobile'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Email</h6>
						<div class="input-container">
							<input type="text" name="email" maxlength="40" class="asterisk" value="<?= set_value('email'); ?>" id="input_box" placeholder="Enter Email">
							<!-- <span class="asterisk-symbol">*</span> -->
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-camera col_blu"></span> Upload Donor Image</h6>
						<div class="input-container">
							<input type="file" name="photo" class="imageError" id="input_file">
							<span id="imageError" class="col_red valid_err_upl"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'photo'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fas fa-map-marker-alt col_blu"></span> Address</h6>
						<div class="input-container">
							<textarea placeholder="Enter Full Address" class="asterisk addressError" name="address" value="<?= set_value('address'); ?>"></textarea>
							<span class="asterisk-symbol asterisk-symbol1">*</span>
							<span id="addressError" class="col_red txt_area_valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'address'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_sign_in" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-exchange"></span> Blood Transition</button>
					</div>
				</div>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
	<script>
		function updateCountryCode() {
			var countrySelector = document.getElementById('country_selector');
			var selectedCountryCode = countrySelector.value;
			document.getElementById('country_code').value = selectedCountryCode;
		}
		</script>
	<!-----Donor Blood Transition ------>

	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->

	<script type="text/javascript">
		//Blood Donor Group Depedencies
		$(document).ready(function() {
			$('#blood_group').change(function() {
				var id = $(this).val();
				$.ajax({
					url: '<?php echo site_url('Blood_bank/get_blood_price/'); ?>' + id,
					method: "POST",
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var uhtml = '';
						var bhtml = '';
						var i;
						var j;
						var k;
						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].selling_price + '>' + data[i].selling_price + '</option>';
							$('#blood_price').html(html);
						}
						for (j = 0; j < data.length; j++) {
							uhtml += '<option value=' + data[j].blood_unit + '>' + data[j].blood_unit + '</option>';
							$('#blood_unit').html(uhtml);
						}
						for (k = 0; k < data.length; k++) {
							bhtml += '<option value=' + data[k].blood_group + '>' + data[k].blood_group + '</option>';
							$('#blood_group_sale').html(bhtml);
						}
					}
				});
				return false;
			});
			$("#btn_sign_in").click(function(e) {
				//e.preventDefault();
				$(".error").text("");
				let valid = true;


				//Choose Blood Group  validation
				const nameSelect = $(".nameSelect");
				const nameError = $("#nameError");
				if (nameSelect.val() === null || nameSelect.val() === "") {
					nameError.text("Please Select The Blood Group");
					valid = false;
				} else {
					nameError.text("");
				}

				// Blood Price (1 Unit)  validation
				const bloodpriceSelect = $(".bloodpriceSelect");
				const bloodpriceError = $("#bloodpriceError");
				if (bloodpriceSelect.val() === null || bloodpriceSelect.val() === "") {
					bloodpriceError.text("Please select Blood Price (1 Unit)");
					valid = false;
				} else {
					drnameError.text("");
				}


				// selling blood  validation
				const unitSelect = $(".unitSelect");
				const unitError = $("#unitError");
				if (unitSelect.val() === null || unitSelect.val() === "") {
					unitError.text("Please select The Blood Unit");
					valid = false;
				} else {
					unitError.text("");
				}

				// // unit validation
				// const unitInput = $(".unitError");
				// const unitError = $("#unitError");
				// if (unitInput.val().trim() === "") {
				// 	unitError.text("Please enter Blood Unit");
				// 	valid = false;
				// } else {
				// 	unitError.text("");
				// }

				// selling blood  validation
				const sellingSelect = $(".sellingSelect");
				const sellingError = $("#sellingError");
				if (sellingSelect.val() === null || sellingSelect.val() === "") {
					sellingError.text("Please select The Selling Blood Group");
					valid = false;
				} else {
					sellingError.text("");
				}

				// unit validation
				const nameInput = $(".usernameError");
				const usernameError = $("#usernameError");
				if (nameInput.val().trim() === "") {
					usernameError.text("Please enter The Username");
					valid = false;
				} else {
					usernameError.text("");
				}

				// Mobile validation
				const phoneInput = $(".phoneError");
				const phoneError = $("#phoneError");
				if (!/^\d{10}$/.test(phoneInput.val())) {
					phoneError.text("Mobile Number Must Be 10 Digits Numeric");
					valid = false;
				} else {
					phoneError.text("");
				}
				//image validation
				const imageInput = $(".imageError");
				const imageError = $("#imageError");
				const MAX_IMAGE_SIZE_KB = 500; 
				if (imageInput.val().trim() === "") {
					imageError.text("Please Upload Donor Image");
					valid = false;
				} else {
					const fileSize = imageInput[0].files[0].size / 1024; // Convert file size to kilobytes
					if (fileSize > MAX_IMAGE_SIZE_KB) {
						imageError.text("Image size should be less than 500 KB");
						valid = false;
					} else {
						imageError.text("");
					}
				}
				// unit validation
				const addressInput = $(".addressError");
				const addressError = $("#addressError");
				if (addressInput.val().trim() === "") {
					addressError.text("Please enter The Address");
					valid = false;
				} else {
					addressError.text("");
				}
				if (!valid) {
					e.preventDefault();
				}
			});
		});

		//Blood Donor Group Depedencies
	</script>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>
