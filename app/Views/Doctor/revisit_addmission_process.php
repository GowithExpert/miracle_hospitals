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
		<div class="card">
			<div class="card-content" id="brdr_botm_silvr">
				<h5><span class="fa fa-user-md col_blu"></span> Admission Process</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks col_blu"></span> Select Department</h6>
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
						<span id="departmentError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'department_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks col_blu"></span> Select Ward</h6>
						<div class="select-wrapper">
							<select name="ward_name" id="ward_name" class="asterisk wardSelect">
								<option selected disabled>Select ward</option>
							</select>
							<span class="mandatory redStarWard">*</span>
						</div>
						<span id="wardError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'ward_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12">
						<h6><span class="fa fa-tasks col_blu"></span> Select Bed</h6>
						<div class="select-wrapper">
							<select name="bed_lable" id="bed_lable" class="asterisk bedSelect">
								<option selected disabled>Select bed</option>
								
							</select>
							<span class="mandatory redStarbedSelect">*</span>
						</div>
						<span id="bedError" class="col_red"></span>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'bed_lable'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12">
					<h6><span class="fa fa-tasks col_blu"></span> Other Info</h6>
						
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
			
			//console.log(dept_id);
			$.ajax({
				url: "<?= base_url('/Doctor/get_wards_for_admission/') ?>"+dept_id,
				method: "POST",
				data: {
					dept_id: dept_id,
				},
				async: true,
				dataType: 'json',
				success: function(response) {
					// Check if 'data' property exists in the response
					if (response.data && Array.isArray(response.data)) {
						var html = '';
						for (var i = 0; i < response.data.length; i++) {
							html += '<option value="' + response.data[i].id + '">' + response.data[i].ward_name + '</option>';
						}
						// Remove the "Select ward" option and update the dropdown
						$('#ward_name').find('option').not(':first').remove();
						$('#ward_name').append(html);
						$("#preloader1").hide();

						// Check if the response contains a success message
						// if (response.message) {
						// 	// Display the success message in an alert box
						// 	alert('Success: ' + response.message);
						// }
					} else {
						console.error('Invalid data format in the response:', response);

						
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);

					// Display the error message in an alert box
					alert('AJAX request failed: ' + textStatus + ' ' + errorThrown);
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
				success: function(response) {
					// Check if 'data' property exists in the response
					if (response.data && Array.isArray(response.data)) {
						var html = '';
						for (var i = 0; i < response.data.length; i++) {
							html += '<option value="' + response.data[i].id + '#' + response.data[i].bed_lable + '">' + response.data[i].bed_lable + '</option>';
						}
						// Remove the "Select ward" option and update the dropdown
						$('#bed_lable').find('option').not(':first').remove();
						$('#bed_lable').append(html);
						$("#preloader1").hide();

						
					} else {
						console.error('Invalid data format in the response:', response);

						
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);

					// Display the error message in an alert box
					alert('AJAX request failed: ' + textStatus + ' ' + errorThrown);
				}
			});

			return false;
		});


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
				url: "<?= base_url('/Doctor/revisit_admission_process/') ?>",
				data: {
					department_name: department_name,
					ward_name: ward_name,
					bed_lable: bed_lable,
					otherInfo: otherInfo,
					patient_id:"<?php echo $patient_id; ?>",
					pid:"<?php echo $pid; ?>",
					puid:"<?php echo $puid; ?>",
					apmt_id:"<?php echo $apmt_id; ?>",
					//dr_id:"<//?php echo $dr_id; ?>",
					bed_id: bed_id,
				},
				dataType: 'text', //for 'string'
				success: function (response) {
				// Handle the success response
				var jsonResponse = $.parseJSON(response);

				if (jsonResponse.status) {
					// Encode the success message and redirect to another page
					var encodedsucesMsg = encodeURIComponent(jsonResponse.data.message);
					window.location.href = "<?= base_url('/Doctor/manage_revisit_patient') ?>?success=" + encodedsucesMsg;
				} else {
					// Encode the error message and redirect to another page
					console.error("Error:", jsonResponse.error);
					// var encodedErrorMessage = encodeURIComponent(jsonResponse.error);
					// window.location.href = "<//?= base_url('/Admin/manage_revisited_patients') ?>?error=" + encodedErrorMessage;
				}
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log("Something went wrong");
				console.log(xhr);
				console.log(textStatus);
				console.log(errorThrown);

				// Encode the error message and redirect to another page
				// var encodedErrorMessage = encodeURIComponent("Error: Something went wrong. Please try again.");
				// window.location.href = "<//?= base_url('/Admin/manage_revisited_patients') ?>?error=" + encodedErrorMessage;
				}
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