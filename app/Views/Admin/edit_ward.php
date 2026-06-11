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
<?php //echo "<pre>";print_r($ward[0]);die;?>
<!-- <?//php echo "<pre>";print_r($dept_name);die;?> -->
<!DOCTYPE html>
<html>

<head>
	<title>Edit Ward</title>
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
			<div class="card-content" id="brdr_botm_silvr">
				<h5 class="h5_align"><span class="fa fa-tasks col_blu"></span> Edit Ward</h5>
				<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
			
			<!-- Select Department - START -->
			<h6><span class="fa fa-tasks col_blu"></span> Select Department</h6>
			<div class="select-wrapper">
				<select name="department_id" id="department_id" class="asterisk departmentSelect" >
					<option selected disabled>Select Department</option>
					<?php if (isset($departments) && is_array($departments)) {
							//echo($departments);die;
							foreach ($departments as $dep){ ?>
							<option value="<?= $dep->id . '#' . $dep->department_name; ?>" 
								<?php if ($ward[0]->dept_name == $dep->department_name){ echo "selected"; } else { echo ""; }?> ><?= $dep->department_name; ?></option>

							<?php 
								/*if (isset($ward[0]->dept_name)):
									if ($ward[0]->dept_name == $dep->department_name) : ?>
									<option value="<?= $dep->id . '#' . $dep->department_name; ?>" selected><?= $dep->department_name; ?></option>
									<?php endif; ?>
								<?php endif; */?>
						<?php } }
						?>
				</select>
				<span class="mandatory redStar">*</span>
			</div>
				<span id="departmentError col_red"></span>
				<?php if (isset($validation)) { ?>
					<span class="col_red">
						<?= display_error($validation, 'department_name'); ?>
					</span>
					<?= $validation->listErrors(); ?>
				<?php } ?> <!-- Select Department - END-->
				<h6><span class="fas fa-sitemap col_blu"></span>Ward Name</h6>
				<div class="input-container">
					<input type="text" name="ward_name"  class="asterisk nameError" value="<?= $ward[0]->ward_name; ?>" id="input_box" placeholder="Enter Ward Name">
					<span class="asterisk-symbol">*</span>
					<span id="nameError" class="col_red valid_err"></span>
				</div>
				<?php if (isset($validation)) { ?>
					<span class="col_red"><?= display_error($validation, 'ward_name'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>
				<h6><span class="fas fa-sitemap col_blu"></span>Ward Description</h6>
				<div class="input-container">
					<textarea name="ward_desc" id="ward_desc" class="asterisk" placeholder="Ward Description"><?= $ward[0]->ward_desc; ?></textarea>
				</div>
				<?php if (isset($validation)) { ?>
					<span class="col_red"><?= display_error($validation, 'ward_desc'); ?></span>
					<?= $validation->listErrors(); ?>
				<?php } ?>

				<div class="row row12">
					<div class="col">
						<button type="submit" id="btn_register_now" class="btn btn-waves-effect waves-light btn_hver sub_btn"><span class="fa fa-tasks"></span> Update Ward</button>
					</div>
				</div>
				<!-- <?//= form_close(); ?> -->
			</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<script>
	$(document).ready(function() {
		$('.departmentSelect').change(function() {
      		$('.redStar').hide();
    	});

		$("#btn_register_now").on("click", function () {
			var dept_id_and_name = $('#department_id option:selected' ).val();
			var dept_id_and_name_val = dept_id_and_name.split('#');
			var dept_id = dept_id_and_name_val[0];
			var dept_name = dept_id_and_name_val[1]; 

			var ward_desc = $("#ward_desc").val();
			var ward_name = $("#input_box").val();

			function getLastUrlSegment() {
				var pathArray = window.location.pathname.split('/');
				return pathArray[pathArray.length - 1];// get last slashed id form the url 
			}
			// Get the last segment (id) from the URL path
			var ward_id = getLastUrlSegment();

			$.ajax({
				type: 'POST',
				 url: "<?= base_url('Admin/update_ward/'); ?>",
				//url: "</?= base_url('Admin/update_ward/' . $selected_ward[0]->id); ?>",
				data: {
					ward_id:ward_id,
					dept_id: dept_id,
					dept_name: dept_name,
					ward_name: ward_name,
					ward_desc: ward_desc,
				},
				dataType: 'json',
				success: function(response) {
				if (response.status) {
					// Encode message and redirect to another page with the success message
					var customsucesMsg = encodeURIComponent(response.message);
					window.location.href = "<?= base_url('/Admin/manage_ward?success=') ?>" + customsucesMsg;
				} else {
					//alert("Failed to add Ward. Please try again.");
					$('#nameError').html(response.data.ward_name).show();   
				}
			},

			error: function(xhr, textStatus, errorThrown) {
				console.log("Something went wrong");
				console.log(xhr);
				console.log(textStatus);
				console.log(errorThrown);
				// Handle the error
			}

			});
		});
		
		// $("#btn_register_now").click(function(e) {
		// 	e.preventDefault();
		// 	$(".error").text("");
		// 	let valid = true;

		// 	// Name validation
		// 	const nameInput = $(".nameError");
		// 	const nameError = $("#nameError");
		// 	if (nameInput.val().trim() === "") {
		// 		nameError.text("Please enter ward name");
		// 		valid = false;
		// 	} else {
		// 		nameError.text("");
		// 	}
		// 	if (valid) {
		// 		$("form").submit();
		// 	}
		// });
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