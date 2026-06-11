<body class="petnt_backg">
	<?= helper('Form'); ?>
	<!----Body Section Start --->
	<!---Login Form --->
	<div class="row" id="mrg_top1">
		<div class="col l4 m12 s12"></div>
		<div class="col l4 m12 s12">
			<!---card Section --->
			<!-- <//?= form_open('Patients_login/create_patients_account'); ?> -->
			<?= form_open('Patients_login/create_patients_account', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
			<div class="card">
				<!---Php Meassge Show --->
				<div class="reltiv">
					<?php
					if (session()->getTempdata('success')) {
						// Display the success message
						?>
						<div class="card success cutom_messge_styl bckgrnd_gren">
							<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
						</div>
						<?php // Remove the success message from session
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
				<!---Php Meassge Show --->
				<div class="card-content brdr_rdius" id="login_id_with_image">
					<h4 class="center-align h4_margn"><span class="fa fa-wheelchair fa_icon"></span></h4>
					<h5 class="center-align h5_margn font_weght">Patients Registration</h5>

					<div class="input-container">
						<input type="text" name="username" id="username" class="asterisk nameError inpt_area input_box" placeholder="Username" value="<?= set_value('username'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="nameError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'username'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="email" name="email" id="email" class="asterisk emailInput inpt_area input_box" maxlength="40" placeholder="Email address" value="<?= set_value('email'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="emailError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'email'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="tel" name="mobile" id="mobile" maxlength="10" class="phone-input phoneError inpt_area phone_mandatory" oninput="validateMobile(this)" placeholder="Mobile number" value="<?= set_value('mobile'); ?>">
					</div>
					<!-- Container for the country code dropdown -->
					<div id="country_selector" name="country_selector" class="margn_top"></div>

					<span id="phoneError" class="col_red"></span>
					<!-- Hidden input to store the selected country code -->
					<input type="hidden" id="country_code" name="country_code" value="">
					<select id="country_selector" name="country_selector" class="margn_top" onchange="updateCountryCode()">
						<!-- Your country options here -->
					</select>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'mobile'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<select name="gender" class="genderSelect selct_gndr dis_blk">
						<option selected="" disabled="">Select Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
						<option value="Other">Other</option>
					</select>
					<span id="genderError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'gender'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="password-container">
						<div class="image">
							<div class="input-container">
								<input type="password" name="password" class="asterisk passwordInput inpt_area input_box" id="password" minlength="6" maxlength="20" placeholder="Password" value="<?= set_value('password'); ?>">
								<span class="asterisk-symbol">*</span>
							</div>
							<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
						</div>
					</div>
					<span id="passwordError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>

					<div class="input-container">
						<input type="password" name="confirm_password" class="asterisk conf_password inpt_area input_box" id="confirm_password" minlength="6" maxlength="20" placeholder="Confirm Password" value="<?= set_value('confirm_password'); ?>">
						<span class="asterisk-symbol">*</span>
					</div>
					<span id="confirmPasswordError" class="col_red"></span>
					<?php if (isset($validation)) { ?>
						<span class="col_red"><?= display_error($validation, 'confirm_password'); ?></span>
						<?= $validation->listErrors(); ?>
					<?php } ?>
					<button type="submit" class="btn waves-effect waves-light sigup_btn" name="" id="btn_sign_in">Sign Up <span class="fa fa-sign-in-alt"></span> </button>

					<a href="<?= base_url('Patients_login/login'); ?>" class="btn waves-effect waves-light ihve_alrdy_acc">Already Have An Account?</a>
				</div>
			</div>
			<?= form_close(); ?>
			<!---card Section --->
		</div>
		<div class="col l4 m12 s12"></div>
	</div>
<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>
	<!---Login Form --->
	<!----Body Section Start --->
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script>
		$.noConflict(); // Relinquish control of the $ symbol to other libraries
		jQuery(function($) {
			$(document).ready(function() {
				$("#signup-form").submit(function(event) { //Redirect to login form
					event.preventDefault();
					window.location.href = "<?= base_url('Patients_login/register') ?>";
				});

				$("#btn_sign_in").click(function(e) {
					//e.preventDefault();

					$("#nameError").text("");
					$("#emailError").text("");
					$("#phoneError").text("");
					$("#genderError").text("");
					$("#passwordError").text("");
					$("#confirmPasswordError").text("");
					$(".error").text("");

					let valid = true;

					// Name validation
					const nameInput = $(".nameError");
					const nameError = $("#nameError");
					if (nameInput.val().trim() === "") {
						nameError.text("Please Enter The Username");
						valid = false;
					} else {
						nameError.text("");
					}

					// Email validation
					const emailInput = $(".emailInput");
					const emailError = $("#emailError");

					if (!emailInput.val()) {
						emailError.text("Please enter the email.");
						valid = false;
					} else if (!/^\S+@\S+\.\S+$/.test(emailInput.val())) {
						emailError.text("Please enter a valid email.");
						valid = false;
					} else {
						emailError.text("");
					}

					// Mobile validation
					const phoneInput = $(".phoneError");
					const phoneError = $("#phoneError");
					if (!/^\d{10}$/.test(phoneInput.val())) {
						phoneError.text("Mobile number must be 10 digits numeric");
						valid = false;
					} else {
						phoneError.text("");
					}


					// Gender validation
					const genderSelect = $(".genderSelect");
					const genderError = $("#genderError");
					if (genderSelect.val() === null || genderSelect.val() === "") {
						genderError.text("Please select the gender");
						valid = false;
					} else {
						genderError.text("");
					}


					// image validation
					// const imageInput = $(".imageError");
					// const imageError = $("#imageError");
					// if (nameInput.val().trim() === "") {
					// 	imageError.text("Please Upload The Image");
					// 	valid = false;
					// } else {
					// 	imageError.text("");
					// }

					// Password validation
					const passwordInput = $(".passwordInput");
					const passwordError = $("#passwordError");
					if (passwordInput.val().trim() === "") {
						passwordError.text("Please enter a password");
						valid = false;
					} else if (passwordInput.val().length < 6) {
						passwordError.text("Password must be at least 6 characters");
						valid = false;
					}

					// Confirm Password validation
					const confirmPasswordInput = $(".conf_password");
					const confirmPasswordError = $("#confirmPasswordError");
					if (confirmPasswordInput.val().trim() === "") {
						confirmPasswordError.text("Please confirm the password");
						valid = false;
					} 
					else if (confirmPasswordInput.val() !== passwordInput.val()) {
						confirmPasswordError.text("Passwords do not match");
						valid = false;
    				}
					if (!valid) { e.preventDefault(); }
				});

				// 	//$('.iti__selected-dial-code').on('change', function () {
				// 	//$('.iti__flag-container').on('change', function () {
				// 	$('.iti__selected-flag').on('change', function () {


				// 		//$(".myInput").val("New Input Value");
				// 		var newValue = $(this).val();
				// 		console.log("This is here");
				// 		console.log(newValue);
				// 		// var selectedCountryCode = $(this).find('option:selected').text();
				// 		// console.log(selectedCountryCode);
				// 		// $('#country_code').val(selectedCountryCode);
				// 	});
				// });
				// 	$("#btn_sign_in").on("click", function(e) {
				// 		e.preventDefault();
				// 		var countryCode = $("#country_code").val();
				// 		var mobileNumber = $("#mobile").val();
				// 		var country_selector = $("#country_selector").val();

				// 		$.ajax({
				// 		type: 'GET',
				// 		url: "<//?= base_url('Patients_login/register') ?>",
				// 		data: {
				// 			countryCode: countryCode,
				// 			mobileNumber: mobileNumber,
				// 			country_selector: country_selector,
				// 		},
				// 		dataType: 'text',
				// 		success: function(response) {
				// 			// Handle the success response
				// 			console.log(response);
				// 			var jsonResponse = $.parseJSON(response);
				// 			if (jsonResponse.status) {
				// 			var responseData = jsonResponse.data;
				// 			// You can use responseData as needed
				// 			}
				// 			// Call the populateSlotGrid function if it's defined
				// 			if (typeof populateSlotGrid === 'function') {
				// 			populateSlotGrid();
				// 			}
				// 		},
				// 		error: function(xhr, textStatus, errorThrown) {
				// 			console.log("Something went wrong");
				// 			console.log(xhr);
				// 			console.log(textStatus);
				// 			// Handle the error
				// 		}
				// 	})
				// });
				// });
			})
		});
	</script>

	<?= view('Admin/show_pass_js_file.php'); ?>
	<?= view('Admin/js_file.php'); ?>
	<?= view('Admin/country_code_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
</body>