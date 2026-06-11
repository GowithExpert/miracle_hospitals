<!-- 
Copyright © 2023-2024 Neoark Software Pvt Ltd. All rights reserved.
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
Date: 21st August, 2023 
-->

<!DOCTYPE html>
<html>

<head>
	<title>Proceed Admission</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<!-- Include the CSS file -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

	<!-- Include the JavaScript files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<style>
		.btn_hver:hover {
			color: #fff;
		}

		#doctor_name {
			border: 1px solid silver;
		}
		*::-webkit-scrollbar-thumb {
			background-color: #005197;
		}
		.select-wrapper {
			position: relative;
			display: inline-block;
			width: 100%;
		}

		.asterisk {
			margin-right: 20px; /* Adjust the margin as needed for spacing */
		}

		.mandatory {
			position: absolute;
			left: 10px; /* Adjust the left position as needed */
			top: 40%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}

		#status {
			width: 200px;
			height: 200px;
			position: absolute !important;
			left: 50%;
			top: 50%;
			background-image: url("<?= base_url('public/assets/home_image/images/status.gif') ?>");
			background-repeat: no-repeat;
			background-position: center;
			margin: -216px 0 0 -100px !important;
			z-index: 999 !important;
		}
		#messageContainer{
			background-color: red;
			color: #fff;
			padding: 10px;
			position: absolute;
			z-index: 999;
			width: 100%;
			display: none;
		}


	</style>

</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Doctor/top_bar'); ?>
	<!--Top Bar Section Include --->
	<div id="preloader1"> <!-- id="preloader" is not allowing show php output, so renamed as preloader1 -->
        <div id="status" style="background-image:url(<?= base_url('public/assets/home_image/images/status.gif') ?>);"> </div>
    </div>
	<!---Body Section Start -->
	<div class="container">
		<div class="card" style="box-shadow: none;">
		<div id="messageContainer"></div>
			<div class="card-content" style="font-size: 20px;border-bottom: 1px solid silver;padding: 10px;">
				<h5><span class="fa fa-user-md" style="color: #005197"></span> Admission Process</h5>
				<p style="text-align: center;font-size: 14px">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks" style="color: #005197"></span> Select Department</h6>
						<div class="select-wrapper">
							<select name="department_name" id="department_name" class="asterisk departmentSelect" >
								<option selected disabled>Select department</option>
								<?php if (isset($departments) && is_array($departments)) :
									foreach ($departments as $dep) : ?>
										<option value="<?= $dep->id; ?>"><?= $dep->department_name; ?></option>
								<?php endforeach;
								endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
						</div>
						<span id="departmentError" style="color:red"></span>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'department_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks" style="color: #005197"></span> Select Ward</h6>
						<div class="select-wrapper">
							<select name="ward_name" id="ward_name" class="asterisk wardSelect">
								<option selected disabled>Select ward</option>
							</select>
							<span class="mandatory redStarWard">*</span>
						</div>
						<span id="wardError" style="color:red"></span>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'ward_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks" style="color: #005197"></span> Select Bed</h6>
						<div class="select-wrapper">
							<select name="bed_lable" id="bed_lable" class="asterisk bedSelect">
								<option selected disabled>Select bed</option>
							</select>
							<span class="mandatory redStarbedSelect">*</span>
						</div>
						<span id="bedError" style="color:red"></span>
						<?php if (isset($validation)) { ?>
							<span style="color: red">
								<?= display_error($validation, 'bed_lable'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-tasks" style="color: #005197"></span> Other Info</h6>
						<textarea name="other_info" class="asterisk" placeholder="Enter Other Information"id="input_box"></textarea>
					</div>
				</div>
					<div class="row row12">
						<div class="col">
							<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-user"></span>  Addmission Process</button>
						</div>
					</div>		
				<!-- <//?= form_close(); ?> -->
			</div>
		</div>
	</div>
	<script src="your_script.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#preloader1").hide();
			$('.departmentSelect').change(function() {
				$("#preloader1").show();
      			$('.redStar').hide();
    		});
			$('.wardSelect').change(function() {
				$('.redStarWard').hide();
			});
			$('.bedSelect').change(function() {
      			$('.redStarbedSelect').hide();
    		});
			 /// UPDATED CODE TO SELECT THE WARD FROM THE DROPDWN ///
		$('#department_name').change(function() {
			var dept_id = $(this).val();
			$.ajax({
				url: "<?= base_url('/Doctor/get_wards_for_admission/') ?>"+dept_id,
				method: "POST",
				data: {
					dept_id: dept_id,
				},
				async: true,
				dataType: 'json',
				success: function (response) {
					// Check if 'status' property exists in the response
					if (response.hasOwnProperty('status')) {
						// Convert response.status to boolean
						var status = response.status === true || response.status === 'true';
						if (status) {
							// Wards found
							var html = '';
							for (var i = 0; i < response.data.length; i++) {
								html += '<option value="' + response.data[i].id + '">' + response.data[i].ward_name + '</option>';
							}
							// Remove the "Select ward" option and update the dropdown
							$('#ward_name').find('option').not(':first').remove();
							$('#ward_name').append(html);

							// Set green background color for success
							// var messageContainer = $('#messageContainer');
							// messageContainer.text(response.message).css('background-color', 'green').show();

							// // Hide the message container after 5 seconds
							// setTimeout(function () {
							//     messageContainer.hide();
							// }, 5000);

							$("#preloader1").hide();
						} else {
							// No beds found, show the message in red
							var messageContainer = $('#messageContainer');
							messageContainer.text(response.message).css('background-color', 'red').show();

							// Hide the message container after 5 seconds
							setTimeout(function () {
								messageContainer.hide();
							}, 5000);

							$("#preloader1").hide();
						}
					} else {
						console.error('Invalid data format in the response:', response);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);

					// Display the error message in the message container with red background
					var messageContainer = $('#messageContainer');
					messageContainer.text('AJAX request failed: ' + textStatus + ' ' + errorThrown).css('background-color', 'red').show();

					// Hide the message container after 5 seconds
					setTimeout(function () {
						messageContainer.hide();
					}, 5000);

					$("#preloader1").hide();
				}
			});

			return false;
		});


		//// WARD DROPDOWN CODE CLOSE ///

	$('#ward_name').change(function() {
		$("#preloader1").show();
			var ward_id = $(this).val();
			console.log(ward_id);
			$.ajax({
				url: "<?= base_url('/Doctor/get_beds_for_admission/') ?>"+ward_id,
				method: "POST",
				data: {
					ward_id: ward_id,
				},
				async: true,
				dataType: 'json',
				success: function (response) {
					// Check if 'status' property exists in the response
					if (response.status !== undefined) {
						if (response.status) {
							// Beds found
							var html = '';
							for (var i = 0; i < response.data.length; i++) {
								html += '<option value="' + response.data[i].id + '#' + response.data[i].bed_lable + '">' + response.data[i].bed_lable + '</option>';
							}
							// Remove the "Select ward" option and update the dropdown
							$('#bed_lable').find('option').not(':first').remove();
							$('#bed_lable').append(html);

							// // Set green background color for success
							// var messageContainer = $('#messageContainer');
							// messageContainer.text(response.message).css('background-color', 'green').show();

							// // Hide the message container after 5 seconds
							// setTimeout(function () {
							// 	messageContainer.hide();
							// }, 5000);

							$("#preloader1").hide();
						} else {
							// No beds found, show the message
							var messageContainer = $('#messageContainer');
							messageContainer.text(response.message).css('background-color', 'red').show();

							// Hide the message container after 5 seconds
							setTimeout(function () {
								messageContainer.hide();
							}, 5000);

							$("#preloader1").hide();
						}
					} else {
						console.error('Invalid data format in the response:', response);
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);

					// Display the error message in the message container with red background
					var messageContainer = $('#messageContainer');
					messageContainer.text('AJAX request failed: ' + textStatus + ' ' + errorThrown).css('background-color', 'red').show();

					// Hide the message container after 5 seconds
					setTimeout(function () {
						messageContainer.hide();
					}, 5000);

					$("#preloader1").hide();
				}
			});

			return false;
		});

		function createMessageContainer() {
			// Check if the message container already exists
			var messageContainer = $('#messageContainer');
			if (messageContainer.length === 0) {
				// Create a message container element and append it to the body using jQuery
				$('<div/>', {
					id: 'messageContainer',
					css: {
						display: 'block',
						padding: '10px',
						position: 'absolute',
						top: '0',
						left: '0',
						width: '100%',
						textAlign: 'center',
						fontWeight: 'bold',
						backgroundColor: 'yellow', // Adjust as needed
						zIndex: '9999'
					}
				}).appendTo('body');
			}
		}

		
		$("#btn_register_now").on("click", function () {
			var dept_id = $(this).val();
			var department_name = $("#department_name option:selected").text();

			var ward_id =$(this).val();
			var ward_name = $("#ward_name option:selected").text();
		
			
			var bed_id_and_lable = $("#bed_lable option:selected").val();
			var bed_id_and_lable_splt = bed_id_and_lable.split('#');
			
			var bed_id = bed_id_and_lable_splt[0];
			var bed_lable = bed_id_and_lable_splt[1];
			var otherInfo = $("#input_box").val();
			$.ajax({ //Ajax call - START
				type: 'POST',
				url: "<?= base_url('/Doctor/admission_process/') ?>",
				data: {
					department_name: department_name,
					ward_name: ward_name,
					bed_lable: bed_lable,
					otherInfo: otherInfo,
					patient_id:"<?php echo $patient_id; ?>",
					pid:"<?php echo $pid; ?>",
					puid:"<?php echo $puid; ?>",
					apmt_id:"<?php echo $apmt_id; ?>",
					dr_id:"<?php echo $dr_id; ?>",
					bed_id: bed_id,
				},
				dataType: 'text', //for 'string'
				success: function (response) {
				var responseData = JSON.parse(response);
				var message = responseData.message;
				console.log('Message:', message);
				if (responseData.message) {
					// Encode the success message and redirect to another page
					var encodedsucesMsg = encodeURIComponent(responseData.message);
					window.location.href = "<?= base_url('/Doctor/today_patients?success=') ?>" + encodedsucesMsg;
				} else {
					// Encode the error message and redirect to another page
					var encodedErrorMessage = encodeURIComponent(responseData.error);
					window.location.href = "<?= base_url('/Doctor/today_patients?error=') ?>" + encodedErrorMessage;
					}
				},
			});
		});
		
	});
	</script>
	<!---Body Section Start -->
	<?= view('Admin/country_code_js_file.php'); ?>

	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>