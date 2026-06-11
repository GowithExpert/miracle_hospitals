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
	<title>Update Bed</title>
	<?= helper('Form'); ?>
	<?//= view('Admin/css_file.php'); ?>
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Home/css_file'); ?>
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
			<h5 class="h5_align"><span class="fa fa-bed col_blu"></span> Update Bed</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
				<!-- Select Department - START -->
			<h6><span class="fa fa-tasks col_blu"></span> Select Department</h6>
				<div class="select-wrapper">
					<select name="department_name" id="department_name" class="asterisk departmentSelect" >
						<option selected disabled>Select Department</option>
						<?php if (isset($departments) && is_array($departments)) :
							//echo "<pre>";print_r($departments);die;
							foreach ($departments as $dep) : ?>
							<option value="<?= $dep->id . '#' . $dep->department_name; ?>"><?= $dep->department_name; ?></option>
							
							<?php if (isset($selected_bed[0]->department_name)):
								if ($selected_bed[0]->department_name == $dep->department_name) : ?>
									<option value="<?= $dep->id . '#' . $dep->department_name; ?>" selected><?= $dep->department_name; ?></option>
								<!-- <//?php else : ?>
									<option value="<//?= $dep->id . '#' . $dep->department_name; ?>"><//?= $dep->department_name; ?></option> -->
								<?php endif; ?>
								<?php endif; ?>
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
				<?php } ?> <!-- Select Department - END-->

			
				<!-- Select Ward - START -->
				<h6><span class="fa fa-tasks col_blu"></span> Select Ward</h6>
				<div class="select-wrapper">
					<select name="ward_name" id="ward_name" class="asterisk departmentSelect">
					<option selected disabled>Select Ward</option>
					<?php if (isset($selected_bed[0]->ward_name)):?>
						<option value="<?= $selected_bed[0]->ward_id . '#' . $selected_bed[0]->ward_name; ?>" selected><?= $selected_bed[0]->ward_name; ?></option>
					<?php endif; ?>		
					</select>
					<span class="mandatory redStar">*</span>
				</div>
				<span id="ward_error" class="col_red"></span>
				<?php if (isset($validation)) { ?>
					<span class="col_red">
						<?= display_error($validation, 'ward_name'); ?>
					</span>
					<?= $validation->listErrors(); ?>
				<?php } ?> <!-- Select Ward - END-->
				

				<h6><span class="fa fa-bed col_blu"></span> Bed Lable</h6>
				<div class="input-container">
					<input type="text" name="bed_lable" class="asterisk nameError input_box" value="<?= $selected_bed[0]->bed_lable; ?>" id="bed_lable" placeholder="Enter Bed Lable">
					<span class="asterisk-symbol">*</span>
					<span id="nameError" class="col_red valid_err"></span>
				</div>
				<?php if (isset($validation)) { ?>
					<span class="col_red"><?= display_error($validation, 'bed_lable'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>

				<h6><span class="fa fa-bed col_blu"></span> Bed Number</h6>
				<div class="input-container">
					<input type="text" name="bed_number" class="asterisk nameError input_box" value="<?= $selected_bed[0]->bed_number; ?>"id="bed_number"  placeholder="Enter Bed Number">
					<span class="asterisk-symbol">*</span>
					<span id="bednumError" class="col_red valid_err"></span>
				</div>
				<?php if (isset($validation)) { ?>
					<span class="col_red"><?= display_error($validation, 'bed_number'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php }// echo "<pre>"; print_r($selected_bed[0]);die;?>

				<h6><span class="fa fa-bed col_blu"></span> Bed Description</h6>
				<div class="input-container">
					<textarea name="bed_desc" id="bed_desc" class="asterisk" placeholder="Bed Description"><?= $selected_bed[0]->bed_desc; ?></textarea>
				</div>
				<?php if (isset($validation)) { ?>
					<span class="col_red"><?= display_error($validation, 'bed_desc'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>

				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-bed"></span> Update Bed</button>
					</div>
				</div>
				<!-- <//?= form_close(); ?> -->
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>		
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

			function getLastUrlSegment() {
				var pathArray = window.location.pathname.split('/');
				return pathArray[pathArray.length - 1];// get last slashed id form the url 
			}
			// Get the last segment (id) from the URL path
			var bed_id = getLastUrlSegment();

			$.ajax({
			type: 'POST',
			url: "<?= base_url('/Admin/update_bed') ?>" ,
			data: {
				bed_id: bed_id,
				dept_id: dept_id,
				dept_name: dept_name,
				ward_id: ward_id,
				ward_name: ward_name,
				bed_lable: bed_lable,
				bed_number: bed_number,
				bed_desc: bed_desc,
			},
			dataType: 'json', // Change to 'json' for parsing the response as JSON
			success: function(response) {
				if (response.status) {//status: true - Redirect to another page with success message
					 window.location.href = "<?= base_url('/Admin/manage_bed') ?>?sucesMsg=" + encodeURIComponent(response.message);
				} 
				else if (!response.status) { //status: false
					if(response.error) {
						console.log(response.data.message);
						$('#eror_msg').html(response.data.message).show();	
						
					}
					else { //Validation failure 
						$('#departmentError').html(response.data.dept_name).show();
						$('#ward_error').html(response.data.ward_name).show();
						$('#nameError').html(response.data.bed_lable).show();
						$('#bednumError').html(response.data.bed_number).show();
					}
				}
				else {
					$('#eror_msg').html('Unexpected use case.').show();	
				}
			},
		});
	});
		

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
		//});
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
