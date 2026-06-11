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
    <title>Book Appointment</title>
    <?= helper('Form'); ?>
    <!----Css File Include --->
    <?= view('Home/css_file'); ?>
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <!----Css File Include --->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

    <!-- Include the JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include jQuery UI library -->
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <!-- Include jQuery UI CSS -->
     <style>
        .wpcf7-text {
            padding-left: 8px;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">
</head>

<body>
    <script>
        function openModal() {
            // Get the modal element and make it visible
            const modal = document.getElementById("myModal");
            modal.style.display = "block";
        }

        function goBack() {
            // Replace the URL with the desired destination URL for testing purposes.
            window.location.href = '<?= base_url('Frontdesk/doctors_available_slots/' . session()->get('patient_session_id'))  ?>';
        }

        function cancelModal() {
            // Hide the modal when the user clicks "Cancel"
            const modal = document.getElementById("myModal");
            modal.style.display = "none";
        }
    </script>

    <!---Top Bar Section Include -->
    <?= view('Blood_bank/Donor/top_bar'); ?>
    <!---Top Bar Section Include -->
    <!---Body Section Start --->
    <div class="container appoint">
        <div class="appoint_inner">
            <div class="section-heading">
                <h2 class="headng">Patient Detail Form</h2>
                <div class="line"></div>
            </div>
            <p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
            
            <!-- <//?= form_open('Frontdesk/book_appointment', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?> -->
            <div class="row">
                <!-- *********************search box code ********************* -->
                <div class="topdv">
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-12"></div>
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <center>
                                <div class="search-area">
                                    <input type="text" name="lookup" id="lookup" class="wp-form-control input_font_fam mrgn_botm wpcf7-text txt_indnt plcehld_clor" placeholder="Search by: Patient ID, Mobile, email or Name (For Old Patients Only)">
                                    <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
                                    <ul id="lookup-result"></ul>
                                </div>
                            </center>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="input-container">
                            <?php if (isset($patient_dtl[0]->username)) { ?>
                                <input type="text" name="patient_name" id="patient_name" class="wp-form-control input_font_fam mrgn_botm wpcf7-text asterisk patient_nameError plcehld_clor txt_indnt" placeholder="Enter patient name" required="required">
                            <?php } else { ?>
                                <input type="text" name="patient_name" id="patient_name" class="wp-form-control input_font_fam mrgn_botm wpcf7-text asterisk patient_nameError plcehld_clor txt_indnt" placeholder="Enter patient name" required="required">
                            <?php } ?>
                            <span class="asterisk-symbol asterisk-symbol1 asterisk-symbol2">*</span>
                            <span id="patient_nameError" class="col_red valid_err3"></span>
                        </div>
                        <span id="patient_nameError" class="col_red"></span>
                        <?php if (isset($validation)) { ?>
                            <span class="col_red">
                                <?= display_error($validation, 'patient_name'); ?>
                            </span>
                            <?= $validation->listErrors(); ?>
                        <?php } ?>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
    
                        <div class="select-wrapper">
                            <select class="wp-form-control mrgn_botm wpcf7-select asterisk genderselect txt_fnt" name='gender' id='gender' required='required'>
                                <option selected disabled hidden>Select Patient Gender</option>
                                <option value='Male'>Male</option>
                                <option value='Female'>Female</option>
                                <option value='Other'>Other</option>
                            </select>
                            <span class="mandatory_gender1 redStargenderSelect">*</span>
                            <span id="genderError" class="col_red valid_err1"></span>
						</div>
                        <?php if (isset($validation)) { ?>
                            <span class="col_red">
                                <?= display_error($validation, 'gender'); ?>
                            </span>
                            <?= $validation->listErrors(); ?>
                        <?php } ?>
                    </div>

                </div>
                <div class="row">
                    <!-- Patient Age - START  -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="input-container">
                            <input type="text" name="age" id="age" class="wp-form-control input_font_fam mrgn_botm wpcf7-text asterisk ageError plcehld_clor txt_indnt" placeholder="Enter patient age" required="required">
                            <span class="asterisk-symbol asterisk-symbol1 asterisk-symbol2">*</span>
                            <span id="ageError" class="col_red valid_err3"></span>
                        </div>
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
                            <input type="tel" maxlength="10" name="mobile" id="mobile" class="back_wite wp-form-control mrgn_botm wpcf7-text asterisk phone-input phoneError phone_mandatory plcehld_clor" placeholder="Enter mobile number" required="required">
                            <span class="mandatory_phone redStarphone">*</span>
                            <span id="phoneError" class="col_red chng_pss_valid"></span>
                        </div>
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
                <!-- PATIENT ID - START  -->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="input-container">
                            <input type="text" name="address" id="address" class="wp-form-control input_font_fam mrgn_botm wpcf7-text asterisk addressError plcehld_clor txt_indnt" placeholder="Enter Your Address">
                            <!-- <span class="asterisk-symbol asterisk-symbol1 asterisk-symbol2">*</span> -->
                            <span id="addressError" class="col_red valid_err3"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <?php if (isset($patient_dtl[0]->email)) { ?>
                            <input type="email" maxlength="40" name="email" id="email" class="wp-form-control mrgn_botm wpcf7-email plcehld_clor" placeholder="Enter patient email">
                        <?php } else { ?>
                            <input type="email" maxlength="40" name="email" id="email" class="wp-form-control mrgn_botm wpcf7-email plcehld_clor" placeholder="Enter patient email">
                        <?php } ?>
                    </div>
                </div>
                <!-- Patient email - END  -->

                <div class="row">
                    <!-- Patient Unique ID - START  -->
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="input-container">
                                <input type="text" name="national_id" id="national_id" class="wp-form-control input_font_fam mrgn_botm wpcf7-text txt_indnt plcehld_clor asterisk nationalError" placeholder="Enter Patient National ID (Aadhar/PAN/Votter card etc)">
                            </div>
                        </div>
                        <!-- Patient Unique ID - END  -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <?php if (!isset($dt) || $dt == '') { ?>
                            <input type="text" name="puid" id="puid" class="wp-form-control mrgn_botm wpcf7-text readonly_bg txt_fnt plcehld_clor indnt" onblur="(this.type='text')" placeholder="Search Patient ID Above (For Old Patients Only)" required="required" autocomplete="off">
                        <?php } else { ?>
                            <input type="text" name="puid" id="puid"  value="" class="wp-form-control mrgn_botm wpcf7-text readonly_bg txt_fnt plcehld_clor txt_indnt" onblur="(this.type='text')" placeholder=" Search Patient ID Above (For Old Patients Only)" readonly autocomplete="off">
                        <?php } ?>
                    </div>
                </div>
                <!-- patient appointment date ----start -->
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="container1">
                            <div class="date-input" onclick="openModal();">
                                <?php if (!isset($dt) || $dt == '') { ?>
                                    <input type="date" name="appointment_date" class="wp-form-control input_font_fam mrgn_botm wpcf7-text readonly_bg txt_fnt plcehld_clor indnt8 txt_indnt" placeholder="Appointment Date" required="required" autocomplete="off">
                                <?php } else { ?>
                                    <input type="date" id="appointment_date" name="appointment_date" value="<?= $dt ?>" class="wp-form-control input_font_fam mrgn_botm wpcf7-text readonly_bg txt_fnt plcehld_clor indnt8" placeholder="Appointment Date" required="required" readonly autocomplete="off">
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
                    <!-- patient appointment date ----end  -->

                    <!-- patient appointment time ----start -->
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="container1">
                            <div class="date-input" onclick="openModal();">
                                <?php if (!isset($pick_slt) || $pick_slt == '') { ?>
                                    <input type="text" name="appointment_time" class="wp-form-control input_font_fam mrgn_botm wpcf7-text readonly_bg txt_fnt plcehld_clor txt_indnt" placeholder="Appointment Time" required="required" autocomplete="off">
                                <?php } else { ?>
                                    <input type="text" id="appointment_time" name="appointment_time" value="<?= $pick_slt ?>" class="wp-form-control input_font_fam plcehld_clor mrgn_botm wpcf7-text readonly_bg txt_fnt txt_indnt" placeholder="Appointment Time" required="required" readonly autocomplete="off">
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

                <!-- Patient appointment  - END  -->

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

                <!-- <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <textarea class="wp-form-control mrgn_botm wpcf7-textarea plcehld_clor txtara" name="desc" id="desc" cols="30" rows="10" placeholder="What would you like to tell us"></textarea>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <textarea class="wp-form-control mrgn_botm wpcf7-textarea plcehld_clor txtara" name="symptoms" id="symptoms" cols="30" rows="10" placeholder="Enter Diseases Symptoms"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
                        <div class="readmore_area">
                            <button class="centered-button btn_hver1" id="btn_register_now" type="submit"><a data-hover="Book Appointment"><span>Book Appointment</span></a></button>
                        </div>
                    </div>
                </div>
                <!-- <//?= form_close(); ?> --><!-- form restrict to post the page -->
            </div>
        </div>
    <script>
        function updateCountryCode() {
            var countrySelector = document.getElementById('country_selector');
            var selectedCountryCode = countrySelector.value;
            document.getElementById('country_code').value = selectedCountryCode;
        }
    </script>
        <!-- **************java script code start************** -->
        <script>
            $.noConflict(); // Relinquish control of the $ symbol to other libraries
            jQuery(function($) {
                $(document).ready(function() {
                    $('.phone_mandatory').on('input', function() {
                        $('.redStarphone').hide();
                    });
                    $('.genderselect').change(function() {
                        $('.redStargenderSelect').hide();
                    });

               
                //****************************
                $("#btn_register_now").on("click", function (e) {
                    console.log("doctor id is = " + $("#doctor_id").val());
                    e.preventDefault();
                    updateCountryCode();

                    //*********** Validation STOP ********
                    $(".error").text("");
                    let valid = true;

                    // Name validation
                    const nameInput = $(".patient_nameError");
                    const patient_nameError = $("#patient_nameError");
                    if (nameInput.val().trim() === "") {
                        patient_nameError.text("Please Enter The Patient Name");
                        valid = false;
                    } 
                    else { patient_nameError.text("");}

                    // Gender validation
                    const genderSelect = $(".genderselect");
                    const genderError = $("#genderError");

                    if (genderSelect.val() === null || genderSelect.val() === "") {
                        genderError.text("Please Select The Gender");
                        valid = false;
                    } 
                    else { genderError.text("");}

                    //Age validation
                    const ageInput = $(".ageError");
                    const ageError = $("#ageError");
                    if (ageInput.val().trim() === "") {
                        ageError.text("Please Enter The Patient Age")
                    } 
                    else { ageError.text(""); }

                    // Mobile validation
                    const phoneInput = $(".phoneError");
                    const phoneError = $("#phoneError");
                    if (!/^\d{10}$/.test(phoneInput.val())) {
                        phoneError.text("Mobile number must be 10 digits numeric");
                        valid = false;
                    } 
                    else { phoneError.text(""); }
                    //*********** Validation STOP ********

                    if (valid) {
                        //setTimeout(function () {
                        var patient_name = $("#patient_name").val();
                        var gender    = $("#gender").val();
                        var age       = $("#age").val();
                        var mobile    = $("#mobile").val();
                        var country_code = $("#country_code").val();
                        //console.log(country_code);
                        var address = $("#address").val();
                        var email = $("#email").val();
                        var national_id = $("#national_id").val();
                        var puid = $("#puid").val();
                        var appointment_date = $("#appointment_date").val();
                        var appointment_time = $("#appointment_time").val();
                        var symptoms = $("#symptoms").val();
                        var slot_id   =  $("#slot_id").val();
                        var doctor_id = $("#doctor_id").val();
                        var doctor_name = $("#doctor_name").val();

                        console.log("doctor id is = " + doctor_id);
                       
                        $.ajax({
                            type: 'POST',
                            url: "<?= base_url('Frontdesk/book_appointment/') ?>",
                            data: {
                                patient_name: patient_name,
                                gender: gender,
                                age: age,
                                country_code: country_code,
                                mobile: mobile,
                                address: address,
                                email: email,
                                national_id: national_id,
                                puid:puid,
                                appointment_date: appointment_date,
                                appointment_time: appointment_time,
                                symptoms:symptoms,
                                slot_id:slot_id,
                                doctor_id:doctor_id,
                                doctor_name:doctor_name,
                            },
                            dataType: 'json', // Assuming the response is in JSON format
                            success: function (response) {
                                if (response.status) {
                                    var responseData = response.data;
                                    // window.location.href = "<//?= base_url('Home/book_appointment/') ?>";
                                     var url = "<?= base_url('Frontdesk/run_appointment_fee_form/') ?>"+
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
                                // console.log("Something went wrong");
                                // console.log(xhr);
                                // console.log(textStatus);
                                // console.log(errorThrown);
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    console.log("Error: " + xhr.responseJSON.message);
                                } 
                                else { console.log("Error: Something went wrong. Please try again."); }
                            }
                        }); //Ajax - Closed
                        //}, 100); // Adjust the delay time as needed
                    } //validatation - Closed
                }); //Function - Closed

                //*********************
                var searchData = [];
                var lastSearchLength = 0; // Track last triggered length

                $("#lookup").keyup(function () {
                    var srchval = $(this).val().toLowerCase();
                    console.log("Search value = " + srchval);

                    // Only trigger AJAX on every 3 entered search character
                    if (srchval.length % 3 === 0 && srchval.length !== 0 && srchval.length !== lastSearchLength) {
                        lastSearchLength = srchval.length; // Update last triggered length
                        $.ajax({
                            url: "<?= base_url('/Frontdesk/patients_search_lookup') ?>",
                            type: "POST",
                            dataType: "json",
                            data: { srchkey: srchval },
                            success: function (response) {
                                var matches = response.data;
                                searchData = []; // Clear previous search results
                                matches.forEach(function (item) {
                                    var lookup_arr = [{
                                        patient_name: item.patient_name,
                                        puid: item.puid,
                                        mobile: item.patient_phone,
                                        email: item.patient_email,
                                        gender: item.gender,
                                        age: item.age,
                                        address: item.patient_address,
                                    }];
                                    $.merge(searchData, lookup_arr);
                                });
                            },
                            error: function (xhr, status, error) {
                                console.log("The response error is " + error);
                            }
                        }); //Ajax - Closed

                        $("#lookup").autocomplete({
                            source: function (request, response) {
                                var filteredData = searchData.filter(function (item) {
                                    var searchValue = request.term.toLowerCase();

                                    var idMatch = item.puid.toString().includes(searchValue);
                                    var nameMatch = item.patient_name.toLowerCase().includes(searchValue);
                                    var mobileMatch = item.mobile.includes(searchValue);
                                    var emailMatch = item.email.includes(searchValue);

                                    return idMatch || nameMatch || mobileMatch || emailMatch;
                                });

                                var mappedData = filteredData.map(function (item) {
                                    return {
                                        patient_name: item.patient_name,
                                        puid: item.puid,
                                        mobile: item.mobile,
                                        email: item.email,
                                        gender: item.gender,
                                        age: item.age,
                                        address: item.address,
                                        label: "Name: " + item.patient_name + ", Mobile: " + item.mobile + ", PID: " + item.puid
                                        + ", Email: " + item.email + ", Age: " + item.age + ", Address: " + item.address
                                    };
                                });
                                response(mappedData);
                            },
                            select: function (event, ui) {
                                event.preventDefault();
                                $(this).val(ui.item.value);

                                $("#patient_name").val(ui.item.patient_name);
                                $("#age").val(ui.item.age);
                                $("#email").val(ui.item.email);
                                $("#mobile").val(ui.item.mobile);
                                $("#gender").val(ui.item.gender);
                                $("#address").val(ui.item.address);
                                $("#puid").val(ui.item.puid);
                            }
                        }); //Auto Complete Search - Closed
                    } //if - Closed
                }); //$("#lookup").keyup(function - Closed
            });
        });  
    </script>
        <!-- ************ java script end ************* -->
        <?= view('Admin/country_code_js_file.php'); ?>
        <!---Body Section Start --->
        <!---Js file Include -->
        <?= view('Admin/js_file.php'); ?>
        <!---Js file Include -->
        <?= view('Admin/asterisk_symbol_js_file.php'); ?>

</body>
</html>