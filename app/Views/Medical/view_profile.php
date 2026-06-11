<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?//= view('Admin/css_file.php'); ?>
    <!---CSS File Include -->
    <?= view('Admin/custom_css_file.php'); ?>
    <title>View Profile Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?= view('Admin/custom_css_file.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <!-- Include the JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
    <!--Top Bar Section Include --->
	<?= view('Medical/topbar'); ?>
	<!--Top Bar Section Include --->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- <div id="success-message" class="hidden">
                    <p class="success-text"></p>
                </div> -->
                <div id="error-message" class="hidden">
                    <p class="error-text"></p>
                </div>
            </div>
        </div>
        <form name="upld_pic_frm" method="post" enctype="multipart/form-data">
            <div class="row gutters">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="account-settings">
                                <div class="user-profile">
                                    <div class="user-avatar" id="avatar-container">
                                        
                                        <?php
											if (isset($profile_record->profile_pic) && !empty($profile_record->profile_pic)) {
												if (file_exists(FCPATH . 'uploads/accounts/accountants/' . $profile_record->profile_pic)) { ?>
													<img src="<?= base_url() . 'public/uploads/accounts/accountants/' . $profile_record->profile_pic; ?>" class="responsive-img" name="profile_pic" id="profile_pic">

												<?php  } //Inner if - Closed
												else {  ?>
													<img src="<?= base_url() . 'public/uploads/accounts/profile_pic.png'; ?>" class="user-avatar" id="avatar-container">
												<?php } //Inner else - Closed

											} //Outer if - Closed

											else { ?>
												<img src="<?= base_url() . 'public/uploads/accounts/profile_pic.png'; ?>" class="user-avatar" id="avatar-container">
											<?php } //Outer else - Closed  
											?>
										</a>
                                        <div id="camera-container-1">
                                            <div id="camera-icon"><i class="fa fa-camera cam_icon"></i></div>
                                        </div>
                                        <input type="file" id="browser_file" name="browser_file" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" style="display: none !important;">
                                    </div>
                                    <h5 class="user-name" id="display_name" name="display_name"><?=$profile_record->username?></h5>
                                    <h6 class="user-email" id="display_email" name="display_email"><?=$profile_record->email?></h6>
                                </div>
                                <div class="about">
                                    <h5>About</h5>
                                    <p id="about-text"><?=$profile_record->about_me?></p>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                    <div class="card h-100">
                        <div class="card-content" id="brdr_botm_silvr">
                            <p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
                        </div>
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">Personal Details</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <label for="fullname">Full Name</label>
                                    <div class="input-container">
                                        <input type="text" name="username" class="asterisk form-control user-email emailError fullName input_box" id="username" value="<?=$profile_record->username?>"placeholder="Enter Full Name">
                                        <span class="asterisk-symbol">*</span>
						            </div>
                                    <span id="nameError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="eMail">Email</label>
                                        <div class="input-container">
                                            <input type="email" class="asterisk form-control user-email emailError eMail input_box" name="email" id="email"value="<?=$profile_record->email?>" placeholder="Enter email ID">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="emailError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <div class="input-container">
                                            <input type="tel" class="asterisk form-control phone-input phoneError phone input_box mobile phone_mandatory" name="mobile" maxlength="10" id="mobile" value="<?=$profile_record->mobile?>" placeholder="Enter phone number">
                                            <span class="asterisk_phone">*</span>
						                </div>
                                        <!-- Container for the country code dropdown -->
                                        <div id="country_selector" class="margn_top"></div>
                                    </div>
                                    <span id="phoneError" class="col_red"></span>
                                </div>
                                <input type="hidden" id="country_code" name="country_code" value="">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="age">Gender</label>
                                        <div class="input-container">
                                            <input type="text" class="asterisk form-control ageError age input_box" name="gender" id="gender" value="<?=$profile_record->gender?>" placeholder="Enter Gender">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="ageError" class="col_red"></span>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mt-3 mb-2 text-primary">Address</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="Street">Country</label>
                                        <div class="input-container">
                                            <input type="name" class="asterisk form-control streetError Street input_box" name="country" id="country" value="<?=$profile_record->country?>" placeholder="Enter Country">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="streetError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="sTate">State</label>
                                        <div class="input-container">
                                            <input type="text" class="asterisk form-control stateError sTate input_box" name="state" id="state" value="<?=$profile_record->state?>" placeholder="Enter State">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="stateError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="ciTy">City</label>
                                        <div class="input-container">
                                            <input type="name" class="asterisk form-control cityError ciTy input_box" name="city" id="city" value="<?=$profile_record->city?>" placeholder="Enter City">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="cityError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="sTate">Address</label>
                                        <div class="input-container">
                                            <input type="text" class="asterisk form-control stateError sTate input_box" name="address" id="address" value="<?=$profile_record->address?>" placeholder="Enter Address">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="stateError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="pin">PIN / ZIP</label>
                                        <div class="input-container">
                                            <input type="number" class="asterisk form-control pinError zIp input_box" name="pinzip" id="pinzip"value="<?=$profile_record->pinzip; ?>" placeholder="Enter Pin / Zip Code">
                                            <span class="asterisk-symbol">*</span>
						                </div>
                                    </div>
                                    <span id="pinError" class="col_red"></span>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="About">About</label>
                                        <input type="text" class="form-control pinError zIp input_box" maxlength="120" name="about_me" id="about_me"value="<?=$profile_record->about_me?>" placeholder="Enter About Yourself" >
                                    </div>
                                    <span id="aboutError" class="col_red"></span>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                       
                                        <button type="button" id="update-button" name="submit" class="btn btn-primary btn_hver">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
            $('#camera-icon').on('click', function() { //Open Browser option
                $('#browser_file').click();
            });

            $("#browser_file").on("change", function () { //Upload pic on browse file
                var fileInput = $(this);
                var uploaded_file = fileInput[0].files[0];
                if (uploaded_file) {
                if (uploaded_file.size > 500 * 1024) { // 300 KB in bytes
                    // Show error message
                    showErrorMessage('Image size should be less than 500 KB');
                    fileInput.val('');
                    return; 
                }
                var formData = new FormData();
                formData.append("uploaded_file", uploaded_file);
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('/Medical_Accountant/upload_profile_pic')?>",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        location.reload(true);
                        var jsonResponse = $.parseJSON(response);
                        //window.location.reload();
                        if (jsonResponse && jsonResponse.status === false) {
                        showErrorMessage(jsonResponse.message);
                    } else if (jsonResponse && jsonResponse.success) {
                        showsucesMsg(jsonResponse.success);
                    } else if (jsonResponse && jsonResponse.warning) {
                        showWarningMessage(jsonResponse.warning);
                    } else {
                        showsucesMsg('Photo Uploaded Successfully');
                    }
                },
                    error: function (xhr, status, error) {
                        // You can customize the error message based on the status or error information
                        if (xhr.status === 404) {
                            showErrorMessage('Error 404: Resource not found');
                        } else if (xhr.status === 500) {
                            showErrorMessage('Error 500: Internal Server Error');
                        } else {
                            showErrorMessage('Error: ' + error);
                        }
                        console.error(error);
                         }
                    });
                }
            });

            function showsucesMsg(message) {
                $('#success-message .success-text').text(message);
                $('#success-message').fadeIn().delay(5000).fadeOut(); // Display for 3 seconds
                //location.reload(true);
            }

            function showWarningMessage(message) {
                $('#success-message .success-text').text(message);
                $('#success-message').fadeIn().delay(5000).fadeOut(); // Display for 3 seconds
            }

            function showErrorMessage(message) {
                $('#error-message .error-text').text(message);
                $('#error-message').fadeIn().delay(500000).fadeOut(); // Display for 3 seconds
            }

            $('#country_selector').on('change', function () {
                var selectedCountryCode = $(this).val();
                $('#country_code').val(selectedCountryCode);
                console.log("Selected Country Code: " + selectedCountryCode);
            });

            $("#update-button").on("click", function (e) {
                e.preventDefault();
                updateCountryCode();

                setTimeout(function () {
                var username = $("#username").val();
                var email = $("#email").val();
                var mobile = $("#mobile").val();
                var gender = $("#gender").val();
                var country = $("#country").val();
                var selectedCountryCode = $("#country_code").val();
                var state = $("#state").val();
                var city = $("#city").val();
                var address = $("#address").val();
                var pinzip = $("#pinzip").val();
                var about_me = $("#about_me").val();
                
                
                $.ajax({ //Ajax call - START
                    type: 'POST',
                    url: "<?= base_url('/Medical_Accountant/update_profile/') ?>",
                    data: {
                        username:username,
                        email:email,
                        mobile:mobile,
                        gender:gender,
                        country:country,
                        selectedCountryCode:selectedCountryCode,
                        state:state,
                        city:city,
                        address:address,
                        pinzip:pinzip,
                        about_me:about_me,
                    },
                    dataType: 'json', // Assuming the response is in JSON format
                        success: function (response) {
                            window.location.reload();
                            if (response.status) {
                                var sucesMsg = response.message || "Operation successful";
                                showsucesMsg(sucesMsg);
                            } else {
                                var errorMessage = response.error || "Unknown error occurred";
                                showErrorMessage("Error: " + errorMessage);
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            console.log("Something went wrong");
                            console.log(xhr);
                            console.log(textStatus);
                            console.log(errorThrown);

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                showErrorMessage("Error: " + xhr.responseJSON.message);
                            } else {
                                showErrorMessage("Error: Something went wrong. Please try again.");
                            }
                        }
                    });
                }, 100); // Adjust the delay time as needed
            });
        }); //document.ready - loop Closed
        });
    </script>

    <?= view('Admin/country_code_js_file.php'); ?>
    <!---Js file Include -->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
    <!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>

</html>