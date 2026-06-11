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
    <title>Book Doctor Appointment</title>
    <?= helper('Form'); ?>
    <!----Css File Include --->
    <?= view('Home/css_file'); ?>
    <!----Css File Include --->

    <!-- Include jQuery UI library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

    <!-- Include the JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  
    <style>
        .input-container {
            position: relative;
        }

        .asterisk-symbol {
            position: absolute;
            top: 30%;
            left: 8px;
            transform: translateY(-50%);
            color: red;
            visibility: visible;
            opacity: 1;
            transition: visibility 0s, opacity 0.2s;
        }

        .asterisk-hidden .asterisk-symbol {
            visibility: hidden;
            opacity: 0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            width: 60%;
            /* Adjust the width as per your preference */
            max-width: 400px;
            /* Set a maximum width for the modal */
            margin: 15% auto;
            /* Center the modal on the screen */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .close {
            color: #000;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        /* Styling for the "Go Back" button */
        .modal-content button {
            background-color: #0DB69F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid #0DB69F;
        }

        .modal-content button:hover {
            background-color: #fff;
            color: #0DB69F;
        }

        #myModal .modal-content {
            float: none !important;
        }

        .iti {
            width: 100%;
        }

        input:focus {
            outline: none !important;
        }

        select:focus {
            outline: none !important;
        }

        textarea:focus {
            outline: none !important;
        }
        .appoint_inner{
            margin-top: 25px !important;
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
		.mandatory_phone {
			position: absolute;
			left: 78px; /* Adjust the left position as needed */
			top: 35%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}
		.mandatory_gender{
			position: absolute;
			left: 8px; /* Adjust the left position as needed */
			top: 30%; /* Adjust the top position as needed */
			color: red;
			transform: translateY(-50%);
		}
		.genderSelect{
			padding: 11px 7px !important;
		}
        .txt_fnt{
            font-family: inherit !important;
        }
        .readonly_bg{
            cursor: pointer;
        }
        .centered-button:focus{
            background: none !important;
        }
        .modal {
            z-index: 1000; /* Adjust the value based on your needs */
        }
        .wp-form-control{
            float: none !important;
        }
        .mandatory_phone {
            position: absolute;
            left: 78px; /* Adjust the left position as needed */
            top: 35%; /* Adjust the top position as needed */
            color: red;
            transform: translateY(-50%);
        }
        .col_red{
            color: red;
        }
        .col_drk_gry{
            color: #939393;
            font-weight: 600;
        }
        .margn_top{
            margin-top: 10px;
        }
    </style>
</head>

<!----Body Section Start---->
<?php ?>

<body>
    <script>
    function openModal() {
        // Get the modal element and make it visible
        const modal = document.getElementById("myModal");
        modal.style.display = "block";
    }

    function goBack() {
        // Replace the current state with a new state
        history.replaceState({}, document.title, window.location.href);

        // Go back in the browser history
        window.history.back();
    }

    function cancelModal() {
        // Hide the modal when the user clicks "Cancel"
        const modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>


    <!---Nav Bar Section Include  --->
    <?= view('Home/nav_bar'); ?>
    <!---Nav Bar Section Include  --->


    <div class="container appoint">
        <div class="appoint_inner">
            <div class="patient_heading">
                <h2>Patient Detail Form</h2>
                <div class="line"></div>
            </div>
            <p style="text-align: center;font-size: 14px">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
            
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="input-container">
                        <input type="text" name="name" id="name" class="wp-form-control wpcf7-text asterisk nameError" placeholder="Enter Patient Name" class="asterisk" required>
                        <span class="asterisk-symbol">*</span>
                    </div>
                    <span id="nameError" class="col_red err_inpt"></span>
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'name'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="select-wrapper">
                        <select class="wp-form-control wpcf7-select genderSelect asterisk" name='gender' id='gender' required>
                            <option selected disabled hidden>Select Patient Gender</option>
                            <option value='Male'>Male</option>
                            <option value='Female'>Female</option>
                            <option value='Other'>Other</option>
                        </select>
                        <span class="mandatory_gender redStargenderSelect">*</span>
					</div>
                    <span id="genderError" class="col_red err_inpt"></span>
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'patient_gender'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>

            </div>
            <div class="row">
                <!-- Patient Age - START  -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="input-container">
                        <input type="text" name="age" id="age" class="wp-form-control wpcf7-text asterisk ageError" placeholder="Enter Patient Age" required>
                        <span class="asterisk-symbol">*</span>
                    </div>
                    <span id="ageError" class="col_red err_inpt"></span>
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'age'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
                <!-- Patient Age - END  -->
                <!-- Mobile input - START  -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="input-container">
                        <input type="tel" name="mobile" id="mobile" maxlength="10" class="asterisk wp-form-control wpcf7-text phone-input phoneError phone_mandatory" maxlength="10" placeholder="Enter Mobile Number">
                        <span class="mandatory_phone redStarphone">*</span>
                    </div>
                    <span id="phoneError" class="col_red err_inpt_phn"></span>
                    <!-- Container for the country code dropdown -->
                    <div id="country_selector" name="country_selector" class="margn_top"></div>
                    <input type="hidden" id="country_code" name="country_code" value="">
					
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'mobile'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
            </div>
            <!-- Mobile input - END  -->
            <!--PATIENT ADDRESS - START  -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="input-container">
                        <input type="text" name="address" id="address" class="wp-form-control wpcf7-text asterisk addressError" placeholder="Enter Your Address">
                    </div>
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'Address'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <input type="email" name="email" maxlength="40" id="email" class="wp-form-control wpcf7-email" placeholder="Enter Patient Email">
                </div>
                <!-- Patient email - END  -->
            </div>

            <div class="row">
                <!-- Patient Unique ID - START  -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="input-container">
                        <input type="text" name="national_id" id="national_id" class="wp-form-control wpcf7-text asterisk nationalError" placeholder="Enter Patient National ID (Aadhar/PAN/Votter card etc)">
                    </div>
                </div>
                <!-- Patient Unique ID - END  -->

                <!-- Patient PUID - START -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php if (!isset($dt) || $dt == '') { ?>
                        <input type="text" name="puid" id="puid" class="wp-form-control wpcf7-text readonly_bg txt_fnt" onblur="(this.type='text')" placeholder="Search Patient ID Above (For Old Patients Only)" autocomplete="off">
                    <?php } else { ?>
                        <input type="text" name="puid" id="puid" id="puid" value="" class="wp-form-control wpcf7-text readonly_bg txt_fnt" onblur="(this.type='text')" placeholder="Search Patient ID Above (For Old Patients Only)" readonly autocomplete="off">
                    <?php } ?>
                </div>
            </div>
            <!-- Patient PUID - END  -->

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="container1">
                        <div class="date-input" onclick="openModal();">
                            <?php if (!isset($dt) || $dt == '') { ?>
                                <input type="date" name="appointment_date" id="appointment_date" class="wp-form-control wpcf7-text readonly_bg txt_fnt" placeholder="Appointment Date" autocomplete="off">
                            <?php } else { ?>
                                <input type="date" name="appointment_date" id="appointment_date" value="<?= $dt ?>" class="wp-form-control wpcf7-text readonly_bg txt_fnt" placeholder="Appointment Date" readonly autocomplete="off">
                            <?php } ?>
                        </div>
                    </div>
                    <!-- The pop-up modal -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="cancelModal();">&times;</span>
                            <p class="col_drk_gry">Do you want to update it?
                                <br>
                                If yes, then please press the Go Back button.
                            </p>
                            <button onclick="goBack();">Go Back</button>
                        </div>
                    </div>
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'appointment_date'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="container1">
                        <div class="date-input" onclick="openModal();">
                            <?php if (!isset($pick_slt) || $pick_slt == '') { ?>
                                <input type="text" name="appointment_time" id="appointment_time" class="wp-form-control wpcf7-text readonly_bg txt_fnt" placeholder="Appointment Time" autocomplete="off">
                            <?php } else { ?>
                                <input type="text" name="appointment_time" id="appointment_time" id="appointment_time" value="<?= $pick_slt ?>" class="wp-form-control wpcf7-text readonly_bg txt_fnt" placeholder="Appointment Time" readonly autocomplete="off">
                            <?php } ?>
                        </div>
                    </div>
                   
                    <?php if (isset($validation)) { ?>
                        <span class="col_red">
                            <?= display_error($validation, 'appointment_time'); ?>
                        </span>
                        <?= $validation->listErrors(); ?>
                    <?php } ?>
                </div>
            </div>
            <?php if (isset($doctors[0]->ref_id)) { ?>
                <input type="hidden" name="doctor_id" id="doctor_id" value="<?= $doctors[0]->ref_id; ?>">
            <?php } else if (isset($dr_id)) { ?>
                <input type="hidden" name="doctor_id" id="doctor_id" value="<?= $dr_id; ?>">
            <?php } ?>
            <?php if (isset($doctors[0]->doctor_name)) { ?>
                <input type="hidden" name="doctor_name" id="doctor_name" value="<?= $doctors[0]->doctor_name; ?>">
            <?php } else if (isset($dr_name)) { ?>
                <input type="hidden" name="doctor_name" id="doctor_name" value="<?= $dr_name; ?>">
            <?php } ?>
            <?php if (isset($slot_id)) { ?>
                <input type="hidden" name="slot_id" id="slot_id" value="<?= $slot_id; ?>">
            <?php } ?>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <textarea class="wp-form-control wpcf7-textarea" name="symptoms" id="symptoms" cols="30" rows="10" placeholder="Enter Diseases Symptoms"></textarea>
                </div>
            </div>
           
            <div class="row">
                <div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
                    <div class="readmore_area">
                        <button class="centered-button" id="btn_register_now" type="submit"><a data-hover="Book Appointment"><span>Book Appointment</span></a></button>
                    </div>
                </div>
            </div>
        </div> <!-- appoint_inner div - Closed -->
    </div> <!-- container div - Closed -->
<script>
    function updateCountryCode() {
        var countrySelector = document.getElementById('country_selector');
        var selectedCountryCode = countrySelector.value;
        document.getElementById('country_code').value = selectedCountryCode;
    }
</script>

  
    <script>
         // Relinquish control of the $ symbol to other libraries    
            $(document).ready(function() {
                $('.phone_mandatory').on('input', function() {
                    $('.redStarphone').hide();
                });
                console.log("Hello world !!!!!!");
                $('.genderSelect').change(function() {
      			    $('.redStargenderSelect').hide();
    		    });

                let valid = true;
                $("#btn_register_now").click(function(e) {
                    e.preventDefault();
                    $(".error").text("");
                    valid = true;

                    // Name validation
                    const nameInput = $(".nameError");
                    const nameError = $("#nameError");
                    if (nameInput.val().trim() === "") {
                        nameError.text("Please enter the patient name");
                        valid = false;
                    } 
                    else { nameError.text(""); }

                    // Gender validation
                    const genderSelect = $(".genderSelect");
                    const genderError = $("#genderError");

                    if (genderSelect.val() === null || genderSelect.val() === "") {
                        genderError.text("Please select the gender");
                        valid = false;
                    } 
                    else { genderError.text(""); }

                    //Age validation
                    const ageInput = $(".ageError");
                    const ageError = $("#ageError");
                    if (ageInput.val().trim() === "") {
                        ageError.text("Please enter the patient age")
                    } 
                    else { ageError.text(""); }

                    // Mobile validation
                    const phoneInput = $(".phoneError");
                    const phoneError = $("#phoneError");
                    if (!/^\d{10}$/.test(phoneInput.val())) {
                        phoneError.text("Mobile number must be 10 digits");
                        valid = false;
                    } 
                    else { phoneError.text(""); }

                    // //Address validation 
                    // const addressInput = $(".addressError");
                    // const addressError = $("#addressError");
                    // if (addressInput.val().trim() === "") {
                    //     addressError.text("Please enter patient address");
                    //     valid = false;
                    // } 
                    // else { addressError.text(""); }
                });

                $('#country_selector').on('change', function () {
                    var country_code = $(this).val();
                    $('#country_code').val(country_code);
                    console.log("Selected Country Code: " + country_code);
                });

                
                $("#btn_register_now").on("click", function (e) {
                    console.log("doctor id is = " + $("#doctor_id").val());
                    e.preventDefault();
                    updateCountryCode();
                    if (valid) {
                        //setTimeout(function () {
                        var full_name = $("#name").val();
                        var gender    = $("#gender").val();
                        var age       = $("#age").val();
                        var mobile    = $("#mobile").val();
                        var country_code = $("#country_code").val();
                        //console.log(country_code);
                        var address = $("#address").val();
                        var email = $("#email").val();
                        var symptoms = $("#symptoms").val();
                        var appointment_date = $("#appointment_date").val();
                        var appointment_time = $("#appointment_time").val();
                        var national_id = $("#national_id").val();
                        var slot_id   =  $("#slot_id").val();
                        var doctor_id = $("#doctor_id").val();
                        var doctor_name = $("#doctor_name").val();

                        console.log("doctor id is = " + doctor_id);
                       
                        $.ajax({
                            type: 'POST',
                            url: "<?= base_url('Home/book_appointment/') ?>",
                            data: {
                                full_name: full_name,
                                gender: gender,
                                age: age,
                                country_code: country_code,
                                mobile: mobile,
                                address: address,
                                email: email,
                                symptoms: symptoms,
                                appointment_date: appointment_date,
                                appointment_time: appointment_time,
                                national_id:national_id,
                                slot_id:slot_id,
                                doctor_id:doctor_id,
                                doctor_name:doctor_name,
                            },
                            dataType: 'json', // Assuming the response is in JSON format
                            success: function (response) {
                                if (response.status) {
                                    var responseData = response.data;
                                    // window.location.href = "<//?= base_url('Home/book_appointment/') ?>";
                                     var url = "<?= base_url('Home/run_appointment_fee_form/') ?>"+
                                     '?last_insrt_apmt_id=' + encodeURIComponent(responseData.last_insrt_apmt_id) +
                                    '&appointment_date='    + encodeURIComponent(appointment_date) +
                                    '&appointment_time='    + encodeURIComponent(appointment_time) +
                                    '&patient_email='       + encodeURIComponent(email) +
                                    '&serial='              + encodeURIComponent(responseData.serial) +
                                    '&patient_name='        + encodeURIComponent(responseData.patient_name) +
                                    '&doctor_name='         + encodeURIComponent(responseData.doctor_name) +
                                    '&doctor_id='           + encodeURIComponent(responseData.doctor_id) +
                                    '&slot_id='             + encodeURIComponent(slot_id)+
                                    '&country_code='        + encodeURIComponent(country_code)+
                                    '&mobile='              + encodeURIComponent(mobile);

                                    console.log("Constructed URL:", url);

                                // Redirecting to the constructed URL
                                window.location.href = url;
                                    console.log("Success: " + responseData.message);
                                } 
                                else { console.log("Error: " + response.error); }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                console.log("Something went wrong");
                                console.log(xhr);
                                console.log(textStatus);
                                console.log(errorThrown);

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    console.log("Error: " + xhr.responseJSON.message);
                                } 
                                else { 
                                    console.log("Error: Something went wrong. Please try again."); 
                                }
                            }
                        }); //Ajax - Closed
                        //}, 100); // Adjust the delay time as needed
                    } //validatation - Closed
                }); //Function - Closed


                // var searchData = [];
                // $("#lookup").keyup(function() {
                //     var srchval = $(this).val().toLowerCase();
                //     var srchkey = '';
                //     //Ajax request call - START
                //     $.ajax({
                //         url: "<//?= base_url('/Patients/patients_search_lookup') ?>",
                //         type: "POST",
                //         dataType: "json",
                //         data: {
                //             srchkey: srchval
                //         },
                //         success: function(response) {
                //             var matches = response.data;
                //             $("#lookup-result").empty(); // Clear previous search results
                //             matches.forEach(function(item) { // Add matched items to search results
                //                 var lookup_arr = [{
                //                     name: item.patient_name,
                //                     puid: item.puid,
                //                     mobile: item.patient_phone,
                //                     email: item.patient_email,
                //                     gender: item.gender,
                //                     age: item.age,
                //                     address: item.patient_address,
                //                 }, ];
                //                 $.merge(searchData, lookup_arr)
                //             }); //foreach closed
                //         }, //Ajax-Response sucess - closed

                //         error: function(xhr, status, error) {
                //             console.log('The response error is ' + error);
                //         } //Ajax-Response error - Closed
                //     }); //Ajax - Closed

                //     $("#lookup").autocomplete({ // Initialize Autocomplete
                //         source: function(request, response) { // Filter the searchData based on the input value
                //             var filteredData = searchData.filter(function(item) {
                //                 var searchValue = request.term.toLowerCase();

                //                 var idMatch = item.puid.toString().includes(searchValue);
                //                 var nameMatch = item.name.toLowerCase().includes(searchValue);
                //                 var mobileMatch = item.mobile.includes(searchValue);
                //                 var mobileEmail = item.email.includes(searchValue);
                //                 return idMatch || nameMatch || mobileMatch || mobileEmail;
                //             });

                //             // Map the filtered data to display label and value
                //             var mappedData = filteredData.map(function(item) {
                //                 return {
                //                     name: item.name,
                //                     puid: item.puid,
                //                     mobile: item.mobile,
                //                     email: item.email,
                //                     gender: item.gender,
                //                     age: item.age,
                //                     address: item.address,
                //                     label: "Name: " + item.name + ", Mobile: " + item.mobile + ", PID: " + item.puid,
                //                     value: item.puid //Show patient ID when selected any patient
                //                 };
                //             });
                //             response(mappedData); // Invoke the response callback with the mapped data
                //         },

                //         select: function(event, ui) { // Prevent the default behavior of Autocomplete
                //             event.preventDefault(); // Set the selected value in the input field
                //             $(this).val(ui.item.value); // Perform further actions based on the selected item

                //             $("#name").val(ui.item.name);
                //             $("#age").val(ui.item.age);
                //             $("#email").val(ui.item.email);
                //             $("#mobile").val(ui.item.mobile);
                //             $("#gender").val(ui.item.gender);
                //             $("#address").val(ui.item.address);
                //             $("#puid").val(ui.item.puid);
                //         }
                //     }); //Autocomplete - Closed
                // }); //Keyup function - closed
        });
       
    </script>
    

    <!----Body Section End---->
    <!--=========== Start Footer SECTION ================-->
    <?= view('Home/footer_section'); ?>
    <!--=========== End Footer SECTION ================-->
    <!----Js  file Include --->
    <?= view('Home/js_file'); ?>
    <!----Js  file Include --->
    <!--js file Include for asterisk symbol-->
    <?= view('Admin/asterisk_symbol_js_file.php'); ?>
    <!--js file Include for asterisk symbol-->
    <?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>