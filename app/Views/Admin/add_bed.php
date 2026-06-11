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
	<title>Add Bed</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
	<!--Top Bar Section Include --->
	<?= view('Admin/top_bar'); ?>
	<!--Top Bar Section Include --->

	<!---Body Section Start -->
	<div class="container">
		<div class="card">
		<div id="ajx_eror_msg"></div>
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
			<h5 class="h5_align"><span class="fa fa-bed col_blu"></span> Add Bed</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<!-- Select Department - START -->
				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-tasks col_blu"></span> Select Department</h6>
						<div class="select-wrapper">
							<select name="department_name" id="department_name" class="asterisk departmentSelect" >
								<option selected disabled>Select Department</option>
								<?php if (isset($departments) && is_array($departments)) :
									foreach ($departments as $dep) : ?>
										<option value="<?= $dep->id . '#' . $dep->department_name; ?>"><?= $dep->department_name; ?></option>
								<?php endforeach;
								endif; ?>
							</select>
							<span class="mandatory redStar">*</span>
							<span id="departmentError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'department_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> <!-- Select Department - END-->
					</div>

					<!-- Select Ward - START -->
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-tasks col_blu"></span> Select Ward</h6>
						<div class="select-wrapper">
							<select name="ward_name" id="ward_name" class="asterisk wardSelect">
								<option selected disabled>Select Ward</option>
							</select>
							<span class="mandatory redStarWard">*</span>
							<span id="ward_error" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red">
								<?= display_error($validation, 'ward_name'); ?>
							</span>
							<?= $validation->listErrors(); ?>
						<?php } ?> <!-- Select Ward - END-->
					</div>
				</div>

				<div class="row">
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-bed col_blu"></span> Bed Lable</h6>
						<div class="input-container">
							<input type="text" name="bed_lable" class="asterisk nameError input_box" value="<?= set_value('bed_lable'); ?>" id="bed_lable" placeholder="Enter Bed Lable">
							<span class="asterisk-symbol">*</span>
							<span id="nameError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'bed_lable'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
					<div class="col l6 m6 s12">
						<h6><span class="fa fa-bed col_blu"></span> Bed Number</h6>
						<div class="input-container">
							<input type="text" name="bed_number" class="asterisk nameError input_box" value="<?= set_value('bed_number'); ?>" id="bed_number" placeholder="Enter Bed Number">
							<span class="asterisk-symbol">*</span>
							<span id="bednumError" class="col_red valid_err"></span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'bed_number'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<div class="row">
					<div class="col l12 m12 s12">
						<h6><span class="fa fa-bed col_blu"></span> Bed Description</h6>
						<div class="input-container">
							<textarea name="bed_desc" id="bed_desc" class="asterisk" placeholder="Bed Description"></textarea>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'bed_desc'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
					</div>
				</div>

				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-bed"></span> Add bed</button>
					</div>
				</div>
				<!-- <//?= form_close(); ?> -->
			</div>
		</div>
	</div>

	<script>		
			$(document).ready(function() {
			$('.departmentSelect').change(function() {
      			$('.redStar').hide();
    		});
			$('.wardSelect').change(function() {
				$('.redStarWard').hide();
			});
	/// UPDATED CODE TO SELECT THE WARD FROM THE DROPDWN ///

		$('#department_name').change(function() {
			var dept_id = $(this).val();
			$.ajax({
				url: "<?= base_url('/Admin/get_wards_for_admission/') ?>"+dept_id,
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
							html += '<option value="' + response.data[i].id+ '#' + response.data[i].ward_name +'">' + response.data[i].ward_name + '</option>';
						}
						// Remove the "Select ward" option and update the dropdown
						$('#ward_name').find('option').not(':first').remove();
						$('#ward_name').append(html);
					} else {
						console.error('Invalid data format in the response:', response);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('AJAX request failed:', textStatus, errorThrown);
				}
			});
			return false;
		});

		$("#btn_register_now").on("click", function () {
			var dept_id_n_name = $('#department_name option:selected' ).val();
			var dept_id_n_name_val = dept_id_n_name.split('#');

			var dept_id = dept_id_n_name_val[0];
			var dept_name = dept_id_n_name_val[1];

			var ward_id_n_name = $('#ward_name option:selected' ).val();
			var ward_id_n_name_val = ward_id_n_name.split('#');

			var ward_id = ward_id_n_name_val[0];
			var ward_name = ward_id_n_name_val[1];

			var bed_lable = $('#bed_lable').val();
			var bed_number = $('#bed_number').val();
			var bed_desc = $('#bed_desc').val();
			

			$.ajax({
			type: 'POST',
			url: "<?= base_url('/Admin/add_bed') ?>",
			data: {
				dept_id: dept_id,
				dept_name: dept_name,
				ward_id: ward_id,
				ward_name: ward_name,
				bed_lable: bed_lable,
				bed_number: bed_number,
				bed_desc: bed_desc,
			},
			dataType: 'json', // Change to 'json' for parsing the response as JSON
			// success: function(response) {
			// 	if (response.status) {
			// 		// Redirect to another page with success message
			// 		window.location.href = "<//?= base_url('/Admin/manage_bed') ?>?sucesMsg=" + encodeURIComponent(response.message);
			// 	} else {
			// 		$('#departmentError').html(response.data.dept_name).show();
			// 		$('#ward_error').html(response.data.ward_name).show();
			// 		$('#nameError').html(response.data.bed_lable).show();
			// 		$('#bednumError').html(response.data.bed_number).show();
			// 		// Redirect to another page with error message
			// 		//window.location.href = "<//?= base_url('/Admin/manage_bed') ?>?errorMessage=Failed to add bed. Please try again.";
			// 	}
			// },

			success: function(response) { 
				if (response.status) {//status: true - Redirect to another page with success message
					window.location.href = "<?= base_url('/Admin/manage_bed') ?>?sucesMsg=" + encodeURIComponent(response.message);
				} 
				else if (!response.status) { //status: false
					if(response.error) {
						console.log(response.message);
						$('#ajx_eror_msg').html(response.message).show() 
						.addClass('div_pad cutom_messge_styl bckgrnd_red col_wite');

						// Hide the message after 5 seconds
						setTimeout(function() {
							$('#ajx_eror_msg').hide();
						}, 5000);
					}
					else { //Validation failure 
						$('#departmentError').html(response.data.dept_name).show();
						$('#ward_error').html(response.data.ward_name).show();
						$('#nameError').html(response.data.bed_lable).show();
						$('#bednumError').html(response.data.bed_number).show(); 
					}
				}
				else {
					$('#ajx_eror_msg').html('Unexpected use case.').show();	
					}
				},
			});
		});
		
		setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('#suces_msg');
			var errMsg = document.querySelectorAll('#ajx_eror_msg');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds

		$("#btn_register_now").click(function(e) {
			//e.preventDefault();
			$(".error").text("");
			let valid = true;

			// Name validation
			const nameInput = $(".nameError");
			const nameError = $("#nameError");
			if (nameInput.val().trim() === "") {
				nameError.text("Please enter bed name");
				valid = false;
			} else {
				nameError.text("");
			}
			if (!valid) {
				e.preventDefault(); 
				
			}
		});
	});
	</script>
	<!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>
