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
	<title>Blood Transition</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

	<div class="container">
		<div class="card">
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
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="dr_detil"><span class="fa fa-exchange col_blu"></span>  Blood Transition</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<?php
			
			?>
			<!-- <//?= form_open_multipart('Blood_bank/upload_blood_transition'); ?> -->
			<?= form_open_multipart('Blood_bank/upload_blood_transition', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt col_blu"></span> Choose Blood Group</h6>
						<div class="select-wrapper">
							<select name="blood_group" id="blood_group" class="nameSelect">
								<option selected="" disabled="">Select Blood Group</option>
								<?php if ($blood_transition) :
									count($blood_transition);
									foreach ($blood_transition as $bld_trans) : ?>
										<option value="<?= $bld_trans->id; ?>"><?= $bld_trans->blood_group; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="font_weght col_red">Blood Not Found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStarnameSelect">*</span>
						</div>
						<span id="nameError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr chng_pss_valid1"><?= display_error($validation, 'blood_group'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-rupee col_blu"></span> Blood Price (1 Unit)</h6>
						<div class="select-wrapper">
							<select name="blood_price" id="blood_price" class="bloodpriceSelect">
								<option selected="" disabled="">Blood Price</option>
								<?php if ($blood_transition) :
									count($blood_transition);
									foreach ($blood_transition as $bld_trans) : ?>
										<option value="<?= $bld_trans->id; ?>"><?= $bld_trans->blood_price; ?></option>
									<?php endforeach; ?>
								<?php else : ?>
									<h6 class="font_weght col_red">Blood price does not found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStarbloodpriceSelect">*</span>
						</div>
						<span id="bloodpriceError" class="col_red chng_pss_valid1"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr"><?= display_error($validation, 'blood_price'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt col_blu"></span> Blood Unit</h6>
						<div class="input-container">
							<input type="text" name="blood_unit" class="asterisk unitError" id="input_box" placeholder="Enter Blood Unit">
							<span id="unitError" class="col_red"></span>
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr chng_pss_valid1"><?= display_error($validation, 'blood_unit'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>

					
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-fire-alt col_blu"></span> Selling Blood Group</h6>
						<div class="select-wrapper">
							<select name="selling_price" id="selling_price" class="sellingSelect">
								<option selected="" disabled="">Selling Blood Group</option>
								<?php if (isset($buy_donor_blood) && (is_array($buy_donor_blood) || is_object($buy_donor_blood))) :
									foreach ($buy_donor_blood as $bld_trans) : ?>
										<option value="<?= $bld_trans->id; ?>"><?= $bld_trans->selling_price; ?></option>
									<?php endforeach;
								else : ?>
									<h6 class="font_weght col_red">Selling Blood Group not found</h6>
								<?php endif; ?>
							</select>
							<span class="mandatory redStarsellingSelect">*</span>
						</div>
						<span id="sellingError" class="col_red chng_pss_valid1"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr"><?= display_error($validation, 'selling_price'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="far fa-user col_blu"></span> Username</h6>
						<div class="input-container">
							<input type="text" name="username" class="asterisk usernameError" id="input_box" placeholder="Enter Username" >
							<span class="asterisk-symbol">*</span>
						</div>
						<span id="usernameError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr chng_pss_valid1"><?= display_error($validation, 'username'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-envelope col_blu"></span> Email</h6>
						<input type="email" name="email" id="input_box" placeholder="Enter Email Address">
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fas fa-mobile-alt col_blu"></span> Mobile Number</h6>
						<div class="input-container">
							<input type="tel" name="mobile" class="asterisk phone-input phoneError phone_mandatory" id="input_box" maxlength="10" placeholder="Enter Doctor Mobile Number" oninput="validateMobile(this)" >
							<span class="asterisk_phone">*</span>
						</div>
						<div id="country_selector" name="country_selector" class="margn_top"></div>
						<!-- Container for the country code dropdown -->
						<input type="hidden" id="country_code" name="country_code" value="" onchange="updateCountryCode()">
						<span id="phoneError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr chng_pss_valid1"><?= display_error($validation, 'mobile'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-camera col_blu"></span> Upload Image</h6>
						<input type="file" name="photo" id="input_file">
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr valid_err2"><?= display_error($validation, 'photo'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<h6><span class="fas fa-map-marker-alt col_blu"></span> Address</h6>
						<div class="input-container">
							<textarea name="address" placeholder="Enter Address" class="asterisk addressError"></textarea>
							<span class="asterisk-symbol asterisk-symbol1">*</span>
						</div>
						<span id="addressError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="h6_red_colr txt_area_valid_err1"><?= display_error($validation, 'address'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_sign_in" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="far fa-building"></span> Blood Transition</button>
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
	
	<!----Js File Include ---->
	<?= view('Admin/js_file.php'); ?>
	<!----Js File Include ---->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

	<script type="text/javascript">
		//Blood Price Indipendece 
		$(document).ready(function() {
			$('.nameSelect').change(function() {
      			$('.redStarnameSelect').hide();
    		});
			$('.phone_mandatory').change(function() {
      			$('.redStarphone').hide();
    		});
			$('.bloodpriceSelect').change(function() {
      			$('.redStarbloodpriceSelect').hide();
    		});
			$('.sellingSelect').change(function() {
      			$('.redStarsellingSelect').hide();
    		});
			$('#blood_group').change(function() {
				var id = $(this).val();
				$.ajax({
					 url: '<?php echo site_url('Blood_bank/get_blood_price_one_unit/'); ?>' + id,
				
					method: "POST",
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function(data) {
						var html = '';
						var bhtml = '';
						var i;
						var j;
						for (i = 0; i < data.length; i++) {
							html += '<option value=' + data[i].blood_price + '>' + data[i].blood_price + '</option>';
							$('#blood_price').html(html);
						}
						for (j = 0; j < data.length; j++) {
							bhtml += '<option value=' + data[j].blood_group + '>' + data[j].blood_group + '</option>';
							$('#sale_blood_group').html(bhtml);
						}
					}
				});
				return false;
			});

		});

		setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('#suces_msg');
			var errMsg = document.querySelectorAll('#eror_msg');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds
	
		//Blood Price Indipendece 
	</script>
	<?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>