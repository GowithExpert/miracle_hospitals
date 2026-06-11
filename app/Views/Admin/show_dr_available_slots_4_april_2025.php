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
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!----Css File Include --->
    <?= view('Home/css_file'); ?> 
    
    <?//= view('Admin/css_file.php'); ?>
    <!---CSS File Include -->
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Admin/show_dr_available_slots_css_file.php'); ?>
    <!---Include Css File --->
    
    <style>
        .content-container {
            background-color: #ffffff !important;
            padding: 34px !important;
            border-radius: 10px !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
            max-width: 1000px !important;
            width: 100% !important;
            margin: 20px auto !important;
        }
        .slot-grid{
            margin: 33px !important;
            padding-bottom: 25px;
        }

        .readmore_area{
            /* margin: -3px 0px !important; */
            margin-top: -18px !important;
        }
    /*#dr_div {
      width: 176px;
      padding: 10px;
      margin-left: 14px;
      margin-top: -45px;
      text-align: left;
      color: black;
      font-weight: 500;
      margin-bottom: 12px;
      font-size: 19px;
    }*/
    
    @media (max-width: 576px) {
        .content-container .dr_container {
            width: 95%;
        }

        .content-container .upr_header .dr_container {
            height: 30px !important;
        }
    }
        
    @media (max-width: 820px) {
        .content-container .dr_container {
            max-width: 100%;
        }
    }

    @media (max-width: 400px) {
        .content-container .dr_container {
            margin: 5px auto;
        }
    }
       
    .search-form-control-date{
        background: #f7f7f7;
    }
    .search-form-control-doctor {
        background: #f7f7f7;
    }
    .upr_div{
        border: none;
    }
    .disabled-div {
            pointer-events: none;
            /*opacity: 0.5;*/
            /* Optional: Add additional styles to convey disabled state */
            background-color: #f2f2f2;
            color: #999;
            cursor: not-allowed;
        }
        
    </style>
</head>

<body>
    <!---Top Bar Section Include -->
    <?= view('Admin/top_bar'); ?>
    <!-- <//?= view('Patients/top_bar'); ?> -->
    <!---Top Bar Section Include -->
    <!---Body Section Start --->

    <div class="top_margin">
        <div class="head">
            <div id="ajx_eror_msg"></div>
        </div>
    </div>
    <div class="dr_container calendar-container">
        <div id="customMessageContainer"></div>
        <div class="calendar-header">
            <div class="custom-select" id="monthSelect">
                <div class="select-header" id="monthHeader">
                <!-- Month will be added here -->
                </div>
                <div class="select-list" id="monthList">
                <!-- Month options will be added here -->
                </div>
            </div>
            <div class="custom-select" id="yearSelect">
                <div class="select-header" id="yearHeader">
                    <!-- Year will be added here -->
                </div>
                <div class="select-year-list" id="yearList">
                    <!-- Year options will be added here -->
                </div>
            </div>
                <select name="selected_dr_appoint" id="selected_dr_appoint" class="select-item select_content">
                    <option selected disabled>Select doctor</option>
                    <?php  $newSlotsArr = [];  //for addressing notices  
                        if (isset($doctors) && isset($dr_slots) && is_array($doctors) && is_array($dr_slots)): 
                        foreach ($doctors as $doc): //Doctor Drop-down - START
                            foreach ($dr_slots as $slots): //Arrage Doctor Slots - START (used below)
                                if ($doc->ref_id == $slots->doctor_id) {
                                    $slots->start_end_slot = $slots->start_end_slot;
                                    $newSlotsArr[$doc->ref_id][] = $slots;
                                } 
                            endforeach; //Arrage Doctor Slots - START ?>
                            <option value="<?= $doc->ref_id ?>"><?= $doc->doctor_name ?></option>
                    <?php endforeach; ?>
                    <?php else : ?>
                        <option value="">Doctor Not Found</option>
                    <?php endif; //Doctor Drop-down - START ?>
                </select>
        </div>
        <div class="calendar-content">
            <button id="prevDate"><i class="fa fa-angle-double-left" style="font-size:24px"></i></button>
            <div class="calendar-days" id="calendarDays"></div>
            <!-- Days will be added here -->
            <button id="nextDate"><i class="fa fa-angle-double-right" style="font-size:24px"></i></button>
        </div>
    </div>
    <div class="row cntr">
        <div id="rspns_errtxt_msg"></div>      <!-- Show slot's error message --> 
        <div id="rspns_successtxt_msg"></div>   <!-- Show slot's error message -->
    </div>
    <!-------   Non-Ajax Based Show Slots - START -->
    <div class="dr_container" id='slotGrid'>
        <div class="form-container doctor-{doctor_id}">
            <?php 
            if(!empty($doctors)) {
                foreach($doctors as $doc) { ?>
                    <div class="row <?php echo (empty($newSlotsArr[$doc->ref_id])) ? 'brdr_bottom' : 'dr_slots_avail'; ?>" id="slotGridRow<?php echo $doc->ref_id;?>">
                        <div class="col lg-6 col-md-6 col-sm-12">
                            <div class="form-row">
                                <?php
                                if (isset($doc->profile_pic) && !empty($doc->profile_pic)) {
                                    $imagePath = FCPATH . 'uploads/doctor/' . $doc->profile_pic;
                                    if (file_exists($imagePath)) { ?>
                                        <img src="<?= base_url() . 'public/uploads/doctor/' . $doc->profile_pic; ?>" class="profile-photo" id="profile_pic" height="50">
                                    <?php } 
                                    else { ?>
                                        <img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="profile_photo" id="profile_pic" height="50">
                                    <?php }
                                } 
                                else { ?>
                                    <img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="profile_photo" id="profile_pic" height="50">
                                <?php } ?>

                                <div class="doctorInfo">
                                    <?php
                                    if (isset($doc->doctor_name)) { ?>
                                        <div id='dr_div'><?= $doc->doctor_name ;?>
                                        </div>
                                        <?php 
                                    } ?>
                                </div>
                            </div>
                            <div class="form-row dr_dgre" id="education">
                            <?= $doc->education ;?>
                            </div>
                            <div class="form-row dr_spec" id="dr_specialization">
                            <?= $doc->dr_specialization ;?>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div id="bts" class='container'>
                                <div class='row'>
                                    <div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="readmore_area" style="margin-top: 17px !important">
                                            <button class="centered-button pickslot" name="pickslot" data-dr_id="<?php echo $doc->ref_id; ?>" data-dr_name="<?php echo $doc->doctor_name; ?>" id="pickslot<?php echo $doc->ref_id; ?>" type="submit"><a
                                                    data-hover="Book Appointment"><span id="bookapt<?php echo $doc->ref_id; ?>">Book New Appointment</span></a>
                                                </button>
                                            <div class="slot-grid mrgn_lft_rght txtlgn disp-none" id="doc-slot-msg-<?php echo $doc->ref_id; ?>">
                                                    Doctor slots are not available! 
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--   -->
                    </div>
                    <!-- Draw Slots - START -->
                    <?php //echo "<pre>"; print_r($newSlotsArr); ?>
                    <?php if(!empty($newSlotsArr[$doc->ref_id])) {  ?>
                    <form id="slotfrm<?php echo $doc->ref_id; ?>" class="brder_bottm" name="slotfrm" action="" method="post">
                        <div class="slot-grid mrgn_lft_rght">
                            <!--Selection /De-Selection Slots - START -->
                            <?php 
                                foreach($newSlotsArr[$doc->ref_id] as $slots) { 
                                    $tz = APP_TIMEZONE; //Refer Constants.php  
                                    date_default_timezone_set($tz);
                                    $slot_time =  strtotime(substr($slots->start_end_slot, 0, 5));
                                    //$slot_time1 = date('h:i',$slot_time);
                                    $current_time = strtotime(date("H:i"));
                                    //echo var_dump(strtotime($slot_time1))."-";
                                    //echo var_dump($current_time); exit;
                                    if($slot_time<$current_time){
                                        //continue;         
                            ?> 
                                        <div id="shwgrid" onclick="toggleSlotSelectionNeo($(this))" class="slot disabled-div">
                                    <?= htmlspecialchars($slots->start_end_slot) . ' ';?> <!-- Appended SINGLE Space, however auto append as many spaces, '\n' etc as space are b/t above line & hidden <span> below trimmed in function toggleSlotSelectionNeo
                                    -->
                                    <span hidden="true"><?= htmlspecialchars($slots->id); ?></span>
                                </div>
                            <?php   }
                                    else{ 
                            ?>
                                <div id="shwgrid" onclick="toggleSlotSelectionNeo($(this))" class="slot">
                                    <?= htmlspecialchars($slots->start_end_slot) . ' ';?> <!-- Appended SINGLE Space, however auto append as many spaces, '\n' etc as space are b/t above line & hidden <span> below trimmed in function toggleSlotSelectionNeo
                                    -->
                                    <span hidden="true"><?= htmlspecialchars($slots->id); ?></span>
                                </div>
                            <?php } } ?> <!-- Selection / De-Selection Slots - END -->
                        </div>
                    </form>
                    <?php } else { ?>
                        <script>
                            var doc_id='<?php echo $doc->ref_id;?>';
                            $('#pickslot'+doc_id).removeClass('pickslot');
                            $('#pickslot'+doc_id).hide();
                            $('#doc-slot-msg-'+doc_id).removeClass('disp-none');
                        </script>
                    <?php } ?>
                    <!-- Draw Slots - END -->
                <?php } //foreach - Closed 
            } ?>
        </div>
    </div>
    <!-------   Non-Ajax Based Show Slots - END -->

    
    <!-------   Ajax Based Show Slots: Selected Doctor Slots - START -->
    <div class="dr_container" id='slotGridAjax'></div>
    <!-------   Ajax Based Show Slots: Selected Doctor Slots - END -->

    <!-- Repeated div, need to remove once show dynamic data div -->        
    <script>
         $('#slotGridAjax').hide();
        var selectedDate = "<?= date('Y-m-d'); ?>";
        var slotElementNeo;
        var newpick_slt = '';
        var newpick_slt_id = '';
        
        $(document).ready(function() {//To get the success message form appointment page
           //Sucess Failure message - START
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            var success_msg = urlParams.get('success_msg');
            var error_msg = urlParams.get('error_msg');

            if (success_msg) { 
                var customMessageDiv = $('<div>', {
                    id: 'customMessage',
                    html: success_msg
                });
                $('#customMessageContainer').append(customMessageDiv); // Append the custom message to the container using jQuery
                setTimeout(function() { // Hide the message after 5 seconds
                    customMessageDiv.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            } 
            else if(error_msg) { alert(error_msg); }
            //else { alert("Something went wrong"); } 

           
            $('#dr_appoint').val('');
            var selectedSlots = [];
            var slotElement = '';
            var slotElement_neo = '';

            $(function() { //Disable previous date in calendar
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1; // Note the correction here
                var day = dtToday.getDate();
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();
                var maxDate = year + '-' + month + '-' + day;
                $('#dt_input').attr('min', maxDate);
            });


            /* @param: 
            * @desc: Select the current date on month selection etc
            * @date: February 4, 2025 
            * @author: Neoark Software Pvt Ltd Team
            */
            function selectDate() {
                var currentDate = new Date();
                const currentDay = currentDate.getDate();// Get current day of the month
                // Find all the calendar day divs
                const calendarDays = document.querySelectorAll('.calendar-day');
                
                // Loop through each day and check if it matches the current day
                calendarDays.forEach(day => { // Get the day number
                    const dayNumber = parseInt(day.querySelector('.day-number').textContent);
                    // If the day number matches the current date, add the 'selected' class
                    if (dayNumber === currentDay) {
                        day.classList.add('selected');
                    }
                });
            } //function closed


           /* @param: Ajax function - for getting Doctor slots
            * @desc: Used on Book Appointment page on Month, year & doctor selection 
            * @date: February 4, 2025 
            * @author: Neoark Software Pvt Ltd Team
            */
            function DoctorSlotsAjaxCall(data) {
                $.ajax({
                    type: 'GET',
                    url: "<?= base_url('Doctor/available_selected_doctor_slots') ?>",
                    data: data,
                    success: function (response) { 
                        try {
                            var jsonResponse = $.parseJSON(response);
                            if (!jsonResponse.status) { 
                                $('#rspns_successtxt_msg').hide();
                                $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                                return;
                            }
                            else if(jsonResponse.data) {
                                var responseData = jsonResponse.data;
                                var drData = responseData.doctors;
                                var slotData = responseData.doc_slots;

                                var from_to_slot = [];
                                var dr_name = '';
                                console.log("Data type is " + JSON.stringify(typeof(slotData), null, 2));
                                if((Array.isArray(slotData) || typeof slotData === "object") && slotData !== null) {
                                    console.log("Available dr. slots are in Object format ");
                                    if (Array.isArray(slotData) ? slotData.length > 0 : Object.keys(slotData).length > 0) {
                                        console.log("Object or array length check condition");
                                        $('#rspns_errtxt_msg').hide();
                                        $('#rspns_successtxt_msg').html(jsonResponse.message).show();
                                        $('#dr_div_ajax').text(slotData.doctor_name);
                                        populateSlotGridNeo(slotData);
                                    } 
                                    else { //Handle the situation where slotData is not as expected
                                        $('#rspns_successtxt_msg').hide();
                                        $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                                        console.error("Empty responseData.doc_slots in response");
                                    }
                                }
                                else { // Handle the situation where slotData is not as expected
                                    $('#rspns_successtxt_msg').hide();
                                    $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                                    console.log("Undefined or missing response data"); 
                                }
                            }
                            else { // Handle the situation where slotData is not as expected
                                $('#rspns_successtxt_msg').hide();
                                $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                                console.log("Response data missing");
                            }
                        }
                        catch (error) {
                            $('#rspns_successtxt_msg').hide();
                            $('#rspns_errtxt_msg').html(error).show();
                            console.log("Catch exception ");
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log("Something went wrong");
                        console.log(xhr);
                        console.log(textStatus);
                        $('#rspns_successtxt_msg').hide();
                        $('#rspns_errtxt_msg').text("Occured ajax response error");
                    }
                });
            } //function - Closed

           /* @param: 
            * @desc: On Month Change: Ajax call for Getting doctor 
            * available slots of selected doctor
            * @date: February 3, 2025 
            * @author: Neoark Software Pvt Ltd Team
            */
            var jsonResponse = [];
            var doc_name =  '';
            var selected_dr_id = '';
             $("#monthList").on('click', '.select-item', function() {
                console.log("Event delegation: Handle clicks on .select-item inside #monthList");
                $('#slotGrid').hide();
                $('.slot-grid').hide();
                $('#slotGridAjax').hide();
                $('[id^=slotfrm]').hide();
                selectDate(); //called date selection function
                var monthName = $(this).text(); // Get the name of the clicked month
                //console.log("Month name is " + monthName);

                selected_dr_id = $('#selected_dr_appoint').val();
                //console.log("Selected doc id " + selected_dr_id);

                $("#pickslot_ajax").data("dr_id", selected_dr_id); //??
                doc_name = $('#selected_dr_appoint').find('option:selected').text();
                if (!selectedDate) {
                    console.log("Please select a date.");
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                    return;
                }
                
                var selectedMonth = $('#monthHeader').text();
                var selectedYear = $('#yearHeader').text();
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + selectedDate).slice(-2);

                var data = {
                    dr_id: selected_dr_id,
                    selected_date: currentDateYYYYMMDD,
                };

                DoctorSlotsAjaxCall(data); //Doctor Slots Ajax function Called
            }); //function - Closed



           /* @param: 
            * @desc: On YEAR Change: Ajax call for Getting doctor 
            * available slots of selected doctor
            * @date: February 3, 2025 
            * @author: Neoark Software Pvt Ltd Team
            */
            var jsonResponse = [];
            var doc_name =  '';
            var selected_dr_id = '';
            $("#yearList").on('click', '.select-item', function() {
                console.log("Event delegation: Handle clicks on .select-item inside #yearList");
                $('#slotGrid').hide();
                $('.slot-grid').hide();
                $('#slotGridAjax').hide();
                $('[id^=slotfrm]').hide();
                selectDate(); //called date selection function

                var monthName = $(this).text(); // Get the name of the clicked month
                //console.log("Month name is " + monthName);

                selected_dr_id = $('#selected_dr_appoint').val();
                //console.log("Selected doc id " + selected_dr_id);

                $("#pickslot_ajax").data("dr_id", selected_dr_id); //??
                doc_name = $('#selected_dr_appoint').find('option:selected').text();
                if (!selectedDate) {
                    console.log("Please select a date.");
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                    return;
                }
                
                var selectedMonth = $('#monthHeader').text();
                var selectedYear = $('#yearHeader').text();
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + selectedDate).slice(-2);

                var data = {
                    dr_id: selected_dr_id,
                    selected_date: currentDateYYYYMMDD,
                };

                DoctorSlotsAjaxCall(data); //Doctor Slots Ajax function Called
            }); //function - Closed



           /* @param: 
            * @desc: Populate Doctor set availability slots grid
            * @param: 
            */

            // function populateSlotGridNeo(sltArr) {
            //     //console.log(sltArr);
            //     var frm = $("<form></form>");
            //     frm.attr('id', 'slotfrm');
            //     frm.attr('name', 'slotfrm');
            //     frm.attr('action', '');
            //     frm.attr('method', 'post')
            
            //     var slotGrid = $("#slotGridAjax").append(frm);//.appendTo(frm);

            //     // Replace with your logic to retrieve available slots from the server/database
            //     var availableSlots = sltArr; //getAvailableSlots();
            //     console.log("Available slots are: " + JSON.stringify(availableSlots, null, 2));
            //     if (availableSlots.length > 0) {
            //         var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
            //         $('#slotGridAjax').show();
            //         $('#rspns_errtxt_msg').hide();
            //         $('#rspns_errtxt_msg').hide();
            //         $('#rspns_successtxt_msg').html(jsonResponse.message).show();
            //         $('#slotGrid').hide();
            //         $('.slot-grid').hide();
            //         availableSlots.forEach(function (slot) {
            //            // console.log(slot);
            //             var slotElement = $("<div>").addClass("slot");
            //             //var from_to_slots = slot.slot_start + '--' + slot.slot_end + ' ';
            //             var from_to_slots = slot.start_end_slot + ' '; //space
            //             var hidespan = $("<span>", { "hidden": true, text: slot.id });
            //             slotElement.append(from_to_slots, hidespan); //Append both content and hidden span
            //             slotElement.appendTo(slotGridElement);
            //             slotElement.on("click", function () {
            //                 toggleSlotSelectionNeo($(this));
            //             });
            //         });
            //     }
            //     else {
            //         console.log("You are here");
            //         $('#rspns_errtxt_msg').html(jsonResponse.message).show(); 
            //         $('#slotGrid').hide();
            //         $('.slot-grid').hide();
            //         $('#slotGridAjax').hide();
            //     }
            // } //function - Closed


            function populateSlotGridNeo(sltArr) {
                var availableSlots = sltArr;
                
                if (Object.keys(availableSlots).length > 0) {
                    $('#slotGridAjax').show();
                    $('#rspns_errtxt_msg').hide();
                    $('#slotGrid').hide();
                    console.log(JSON.stringify(availableSlots, null, 2));

                    // Iterate over each doctor's slots data
                    Object.values(availableSlots).forEach(function(doctor) {
                        var frm = $("<form></form>").attr({
                            'id': 'slotfrm' + doctor.doctor_id,
                            'name': 'slotfrm' + doctor.doctor_id,
                            'action': '',
                            'method': 'post'
                        });

                        var slotGrid = $("#slotGridAjax").append(frm); // Append form to the main container

                        var profilePicUrl = '<?= base_url()."public/assets/images/dr.default_pic.svg";?>'; // Default image URL
                        var imagePath = '<?= base_url()."public/uploads/doctor/"; ?>' + doctor.profile_pic;

                        // Asynchronously check if the profile image exists
                        checkIfImageExists(imagePath, function(exists) {
                            if (exists) {
                                profilePicUrl = imagePath;
                            } else {
                                profilePicUrl = '<?= base_url()."public/assets/images/dr.default_pic.svg";?>'; // Fallback to default image
                            }

                            // Construct HTML content for the doctor
                            var slotHtml = $('<div class="dr_container">')
                                .append('<div class="form-container doctor-' + doctor.doctor_id + '">')
                                .append('<div class="row brdr_bottom dr_slots_avail" id="slotGridRow' + doctor.doctor_id + '">')
                                .append('<div class="col lg-6 col-md-6 col-sm-12">')
                                .append('<div class="form-row">')
                                .append('<img src="' + profilePicUrl + '" class="profile-photo" id="profile_pic" height="50">')
                                .append('<div class="doctorInfo">')
                                .append('<div class="dr_div">' + doctor.doctor_name + '</div>')
                                .append('</div>')  // doctorInfo
                                .append('</div>')  // form-row
                                .append('<div class="form-row dr_dgre" id="education_ajax">' + doctor.education + '</div>')
                                .append('<div class="form-row dr_spec" id="dr_specialization_ajax">' + doctor.dr_specialization + '</div>')
                                .append('</div>')  // col-lg-6
                                .append('<div class="col-lg-6 col-md-6 col-sm-12">')
                                .append('<div id="bts" class="container">')
                                .append('<div class="row">')
                                .append('<div id="form-container" class="col-lg-12 col-md-12 col-sm-12">');

                            // Create the slot-grid element and append it before the button
                            var slotGridElement = $("<div>").addClass("slot-grid");
                            slotHtml.append(slotGridElement); // Append the slot grid element first

                            // Construct button HTML and append it after the slot grid
                            // var buttonHtml = `
                            //     <div class="readmore_area">
                            //         <button class="centered-button" name="pickslot_ajax" id="pickslot_ajax" type="submit">
                            //             <a data-hover="Book Appointment"><span>Book Appointment</span></a>
                            //         </button>
                            //     </div>`;

                            var buttonHtml = '<div class="readmore_area">' +
                                '<button class="centered-button pickslot_ajax"' +
                                ' name="pickslot_ajax"' + 
                                ' data-dr_id="' + doctor.doctor_id + '"' + 
                                ' data-dr_name="' + doctor.doctor_name + '"' +
                                ' id="pickslot_ajax_' + doctor.doctor_id + '"' +  // Ensuring unique ID
                                ' type="submit">' +
                                '<a data-hover="Book Appointment"><span>Book Appointment</span></a>' +
                                '</button>' +
                                '</div>';
                            slotHtml.append(buttonHtml); // Append the button after the slot grid

                            // Append the rest of the HTML structure
                            slotHtml.append('</div>')  // form-container
                                .append('</div>')  // row
                                .append('</div>')  // container
                                .append('</div>')  // col-lg-6
                                .append('</div>')  // row
                                .append('</div>'); // dr_container

                            // Append the constructed HTML to the form
                            $(slotHtml).appendTo(frm);
                            //console.log("ddddddd " + JSON.stringify( doctor, null, 2));
                            // Iterate over the slots of each doctor and add slot elements
                            doctor.slots.forEach(function(slot) {

                                //========== code start here ==========
                                const currentTime = new Date();
                                // Get the current time in India (IST) in 24-hour format
                                const indiaTime = currentTime.toLocaleTimeString("<?= LOCALE_TM_STR ?>", {
                                    timeZone: "<?= APP_TIMEZONE ?>",//Refer Constants.php
                                    hour: "<?= HRS_DIGITS ?>", //Refer Constants.php
                                    minute: "<?= MIN_DIGITS ?>",//Refer Constants.php
                                    hour12: false, // 24-hour format
                                });

                                //console.log(indiaTime);  // Output will be in HH:mm format (e.g., "15:30")

                                const [cu_hours, cu_minutes] = indiaTime.split(':');
                                cu_hours_val = Number(cu_hours);
                                cu_minutes_val = Number(cu_minutes);
                                //alert(cu_hours_val+"-"+cu_minutes_val);
                                var slot_start_val = slot.start_end_slot;
                                slot_start_val = slot_start_val.slice(0,5);
                                const [st_hours, st_minutes] = slot_start_val.split(':');
                                st_hours_val  = Number(st_hours);
                                st_minutes_val  = Number(st_minutes);
                                //alert(st_hours_val+"-"+st_minutes_val);
                                var appointment_date = doctor.appointment_date;
                                appointment_date= new Date(appointment_date);
                                //const inputDate = new Date('2025-03-31'); // Input date (ISO format)
                                const  appointment_date_val = appointment_date.toLocaleDateString("<?= LOCALE_TM_STR ?>"); //Format for India
                                 
                                var currentDate = new Date();
                                const currentDate_val = currentDate.toLocaleDateString("<?= LOCALE_TM_STR ?>");
                                if(appointment_date_val == currentDate_val && st_hours_val< cu_hours_val){//  <=cu_minutes_val){
                                    var slotElement = $("<div>").addClass("slot disabled-div");
                                }
                                else{
                                    var slotElement = $("<div>").addClass("slot");
                                }

                                //========== code end here ==========
                                
                                var from_to_slots = slot.start_end_slot + ' ';
                                var hidespan = $("<span>", { "hidden": true, text: slot.slot_id });
                                slotElement.append(from_to_slots, hidespan);
                                slotElement.appendTo(slotGridElement); // Append slot to the slot grid element

                                // Add click event for the slot element
                                slotElement.on("click", function () {
                                    toggleSlotSelectionNeo($(this));
                                });
                            });
                        });
                    });
                } 
                else {
                    console.log("No available slots.");
                    $('#rspns_errtxt_msg').html("No available slots at this moment.").show();
                    $('#slotGrid').hide();
                    $('.slot-grid').hide();
                    $('#slotGridAjax').hide();
                }
            }

            /**
             * Check if an image exists at the specified path (asynchronous version)
             * @param {string} path - The path to the image
             * @param {function} callback - The callback function to call with the result (true or false)
             */
            function checkIfImageExists(path, callback) {
                var img = new Image();
                img.onload = function() {
                    callback(true);  // Image exists
                };
                img.onerror = function() {
                    callback(false);  // Image does not exist
                };
                img.src = path;  // Trigger image loading
            }

           /* @param: 
            * @desc: Ajax call for Getting doctor available slots of selected doctor
            * @param: 
            */
            var jsonResponse = [];
            var doc_name =  '';
            var selected_dr_id = '';
            $('#selected_dr_appoint').on('change', function () {
                $('#slotGrid').hide();
                $('.slot-grid').hide();
                $('#slotGridAjax').hide();

                var selectedDate = $('#calendarDays .selected').text().trim();
                selected_dr_id = $(this).val();
                
                $("#pickslot_ajax").data("dr_id", selected_dr_id);
                doc_name = $(this).find('option:selected').text();
                
                $("#pickslot_ajax").data("dr_name", doc_name);
                if (!selectedDate) {
                    console.log("Please select a date.");
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                    return;
                }

                /*var currentDate = new Date();
                var currentYear = currentDate.getFullYear();
                var currentMonth = currentDate.getMonth() + 1;
                */
                var selectedMonth = $('#monthHeader').text();
                var selectedYear = $('#yearHeader').text();
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + selectedDate).slice(-2);

                var data = {
                    dr_id: selected_dr_id,
                    selected_date: currentDateYYYYMMDD,
                };

                DoctorSlotsAjaxCall(data); //Doctor Slots Ajax function Called
            }); //function - Closed

                
            /* @param: 
             * @desc: On Date click: Ajax call for Getting selected doctor 
             * available slots
             * @param: 
             */ 
            $('#calendarDays').on('click', function () {
                $('#slotGrid').hide();
                $('.slot-grid').hide();
                $('#slotGridAjax').hide();

                $('#rspns_errtxt_msg').hide();
                $('#rspns_successtxt_msg').hide();

                console.log("This is testing here");
                var selectedDate = $('#calendarDays .selected').text().trim();
                selected_dr_id = $(this).val();
                $("#pickslot_ajax").data("dr_id", selected_dr_id);

                doc_name = $(this).find('option:selected').text();
                $("#pickslot_ajax").data("dr_name", doc_name);

                if (!selectedDate) {
                    console.log("Please select a date.");
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                    return;
                }
                /*
                var currentDate = new Date();
                var currentYear = currentDate.getFullYear();
                var currentMonth = currentDate.getMonth() + 1;
                */
                var selectedMonth = $('#monthHeader').text();
                var selectedYear = $('#yearHeader').text();
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + selectedDate).slice(-2);
                
                var data = {
                    dr_id: selected_dr_id,
                    selected_date: currentDateYYYYMMDD,
                };

                DoctorSlotsAjaxCall(data); //Doctor Slots Ajax function Called
            }); //function - Closed


            /* @param: 
             * @desc: On slot click: Ajax call for Getting selected doctor 
             * available slots
             * @param: 
             */
            $(document).on("click", ".pickslot_ajax", function(event) {
                event.preventDefault(); // Prevent default form submission if needed
                var selected_dr_id = $(this).data("dr_id");
               // console.log("Selected doctor id " + selected_dr_id);
                var doc_name = $(this).data("dr_name");
                newpick_slt_id = $.trim(newpick_slt_id,'\n');
                if(typeof selected_dr_id === 'undefined' || selected_dr_id === null || typeof selectedDate === 'undefined' || selectedDate === null || typeof newpick_slt === 'undefined' || newpick_slt === null ||  typeof doc_name === 'undefined' || doc_name === null || typeof newpick_slt_id === 'undefined' || newpick_slt_id === null) { //Mandatory parameter check
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Missing Doctor or Slot details to book an appointment").show();
                    return false;
                }
                else {
                    var new_booking_slot_arr = {
                        dr_id: selected_dr_id, 
                        dt: selectedDate,
                        pick_slt: newpick_slt,
                        dr_name: doc_name,
                        slot_id: newpick_slt_id,
                    };
                    //console.log("new_booking_slot_arr is: " + JSON.stringify(new_booking_slot_arr, null, 2));
                    var queryString = $.param(new_booking_slot_arr);
                    window.location.href = "<?= base_url('Admin/pick_slots/') ?>?" + queryString;
                }
            }); 
            
           /* @params: Required parameter is booking_slot_arr with value select 
            * "Slot", "Doctor ID", "Doctor Name" & "Date"
            * @desc: The function execute when click on the Book Appointment Now button 
            * by opting desired Dr. available slot
            * @use: By patient for booking Doctor available slots
            * @author: Neoarks Team
            * @date: June 15th,  2023
            * @modify:
            */
                    
            $(".pickslot").on("click", function() { //Pickup Slot
                var selected_dr_id = $(this).data("dr_id");
                var doc_name = $(this).data("dr_name");
                newpick_slt_id = $.trim(newpick_slt_id,' \n');

            console.log("selected_dr_id is " +JSON.stringify(selected_dr_id, null, 2));
            console.log("doc_name is " +JSON.stringify(doc_name, null, 2));
            console.log("newpic_slt_id is " +JSON.stringify(newpick_slt_id, null, 2));
                
                if (typeof newpick_slt == 'undefined' || newpick_slt_id.length == 0) {
                    alert("Please select available slot to book an appointment");
                    return false;
                }
                else{ 
                    var new_booking_slot_arr = {
                        dr_id: selected_dr_id,
                        dt: selectedDate,
                        pick_slt: newpick_slt,
                        dr_name: doc_name,
                        slot_id: newpick_slt_id,
                    
                    };
                    //var booking_slot_arr = toggleSlotSelection(slotElementNeo); // MUST have "Slot", "Doctor ID", "Doctor Name" & "Date"
                    console.log(new_booking_slot_arr);
                    //return false;
                    var queryString = $.param(new_booking_slot_arr);
                    window.location.href = "<?= base_url('Admin/pick_slots/') ?>?" + queryString;
                }
            });
            

            /* @param: 
             * @desc: Set time for displaying eror or success message
             * @param: 
             */   
            setTimeout(function () {
                $("#succss_msg, #error_msg").fadeOut();
            }, 5000);
        }); //Document Ready - Closed50000
            

       /* @param: 
        * @desc: Slot selection - Deselection toggle slot function
        * @param: 
        */ 
        // function toggleSlotSelectionNew(slotElement) {
        //     console.log(slotElement);
        //     slotElementNeo = slotElement;
        //     //slotElement_neo = slotElement; //So that slotElement may not match with the same variable in the above loop
        //     console.log("slotElementNeo is " + JSON.stringify(slotElementNeo, null, 2));
        //     slotElement.toggleClass('active'); //Also work if replace it with //slotElement_neo.toggleClass('slotElement_neo');

        //     slotElement.toggleClass("selected");
        //     $('.slot').click(function() {
        //         if (slotElement) {
        //             slotElement.removeClass('selected'); //Remove previous selection of slots, if any
        //         }
        //         $(this).addClass('selected'); //Mark slot as selected
        //         slotElement = $(this);
        //     });
        //     //var doc_name = $('#dr_appoint option:selected').text(); //Selected Dr. name
        //     var doc_name = $('#selected_dr_appoint option:selected').text(); //Selected Dr. name
        //     //console.log("doc_name is " + JSON.stringify(doc_name, null, 2));
        //     var pick_slt = slotElement.text(); //????
        //     //console.log("pick_slt 1 is " + JSON.stringify(pick_slt, null, 2));
        //     var pick_slt = slotElement.text().trim();
        //     //console.log("pick_slt 2 is " + JSON.stringify(pick_slt, null, 2));

        //     if (typeof(pick_slt) !== 'undefined' && pick_slt !== null) { //Likewise isset in php
        //         var delimiter = / /; //Space is must for Blank delemeter, equivalent to " "
        //         //Split the string at the match
        //         var split_pick_slt_arr = pick_slt.split(delimiter);
        //         //console.log("split_pick_slt_arr is " + JSON.stringify(split_pick_slt_arr, null, 2));
        //         if (typeof(split_pick_slt_arr[1]) !== 'undefined' && split_pick_slt_arr[1] !== null) { //Likewise isset in php

        //             newpick_slt=split_pick_slt_arr[0];
        //             newpick_slt_id=split_pick_slt_arr[1];
        //             //console.log(newpick_slt);
        //             //console.log(newpick_slt_id);
                    
        //         } else {
        //             newpick_slt ='';
        //             newpick_slt_id ='';
        //             //console.log("Picked slot ID is missing");
        //         }
        //     } 
        //     else {
        //         newpick_slt = '';
        //         newpick_slt_id = '';
        //         console.log("Picked slot may not blank");
        //     }
        // } //function - Closed
    

       /* @param: (Param is $(this) ie click slot object)
        * @desc: On slot slick: select the slot, 
        * @param: 
        */
        /*
        function toggleSlotSelectionNeo(slotElement) {
            slotElementNeo = slotElement;//For avoiding old assigned value of slotElementNeo
            slotElementNeo.toggleClass('active');

            slotElementNeo.toggleClass("selected");
            $('.slot').click(function() {
                if (slotElementNeo) {
                    slotElementNeo.removeClass('selected'); //De-select slot
                }
                $(this).addClass('selected'); //Mark slot as selected
                slotElementNeo = $(this);
            });

            //Get Selected Dr. name, blank if doctor not selected
            var doc_name = $('#dr_appoint option:selected').text(); 
            var pick_slt = slotElementNeo.text();

            console.log("pick_slt1 val " + JSON.stringify(pick_slt, null, 2));

            var trimSplitPickSltArr = slotElementNeo.text().trim().split(/\s+/);//split(/\s+/): Cut multiple spaces and \n etc which occurs dueto hidden <span> used for slot `id`
            
            console.log("slot & id arr= " +JSON.stringify(trimSplitPickSltArr, null, 2));
            if(!Array.isArray(trimSplitPickSltArr)) {
                console.log("Non-array trimSplitPickSltArr is not supported");
                return;
            }
            else if(trimSplitPickSltArr.lenght < 2) {
                console.log("At least 2 length expected of trimSplitPickSltArr");
                return;
            }
            else if((typeof(trimSplitPickSltArr[0]) == 'undefined' || trimSplitPickSltArr[0] == null) || (typeof(trimSplitPickSltArr[1]) == 'undefined' || trimSplitPickSltArr[1] == null)) {
                
                newpick_slt = '';       //global: defined on top
                newpick_slt_id = '';    //global: defined on top
                console.log("Picked slot ID is missing");
                return;
            }
            else {
                newpick_slt = trimSplitPickSltArr[0];   //global: defined on top
                newpick_slt_id = trimSplitPickSltArr[1];//global: defined on top
            }
        } //function - Closed
        */

    var slotElementNeo = null; // Track previously selected slot globally

    function toggleSlotSelectionNeo(slotElement) {
        if (slotElementNeo) {
            slotElementNeo.removeClass('selected'); // Deselect previous slot
        }

        slotElementNeo = slotElement;
        slotElementNeo.toggleClass('active');
        slotElementNeo.addClass('selected'); // Mark new slot as selected

        // Get selected doctor name (blank if not selected)
        var doc_name = $('#dr_appoint option:selected').text();
        var pick_slt = slotElementNeo.text();

        console.log("pick_slt1 val " + JSON.stringify(pick_slt, null, 2));

        var trimSplitPickSltArr = slotElementNeo.text().trim().split(/\s+/);

        console.log("slot & id arr= " + JSON.stringify(trimSplitPickSltArr, null, 2));

        if (!Array.isArray(trimSplitPickSltArr)) {
            console.log("Non-array trimSplitPickSltArr is not supported");
            return;
        } else if (trimSplitPickSltArr.length < 2) { // Fixed typo: length
            console.log("At least 2 length expected of trimSplitPickSltArr");
            return;
        } else if ((typeof trimSplitPickSltArr[0] === 'undefined' || trimSplitPickSltArr[0] === null) ||
                (typeof trimSplitPickSltArr[1] === 'undefined' || trimSplitPickSltArr[1] === null)) {
            newpick_slt = ''; // Global: defined on top
            newpick_slt_id = ''; // Global: defined on top
            console.log("Picked slot ID is missing");
            return;
        } else {
            newpick_slt = trimSplitPickSltArr[0]; // Global: defined on top
            newpick_slt_id = trimSplitPickSltArr[1]; // Global: defined on top
        }
    }

    // Ensure only one slot can be selected at a time
    $(document).on('click', '.slot', function() {
        toggleSlotSelectionNeo($(this));
    });

    </script>
        <!---Body Section Start --->
     
        <!---Js file Include -->
        <?= view('Admin/js_file.php'); ?>
        <!---Js file Include -->
        <?= view('Doctor/date_calendar_js.php'); ?>
</body>

</html>