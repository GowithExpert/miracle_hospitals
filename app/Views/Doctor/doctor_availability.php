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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Doctor's Availability Slots</title>
    <!---Include Css File --->
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Doctor/doctor_availability_css_file.php'); ?>
    <!---Include Css File --->
    <?= view('Home/css_file'); ?>
</head>
<body class="back_bdy">
   
    <div id="preloader1"> <!-- id="preloader" is not allowing show php output, so renamed as preloader1 -->
        <div id="status" style="background-image:url(<?= base_url('public/assets/home_image/images/status.gif') ?>);"> </div>
    </div>
    <!---Topbar Section Include --->
    <?= view('Doctor/top_bar'); ?>
    <!---Topbar Section Include --->
    <div class="upr_div">
        <?php //echo "<pre>"; print_r($dr_slots); die;?>
        <div class="dr_container calendar-container bck_blu">
            <div class="calendar-header">
                <div class="custom-select" name="monthSelect" id="monthSelect">
                    <div class="select-header" id="monthHeader">
                        <!-- Month will be added here -->
                    </div>
                    <div class="select-list" id="monthList">
                        <!-- Month options will be added here -->
                    </div>
                </div>
                <div class="custom-select" name="yearSelect" id="yearSelect">
                    <div class="select-header" id="yearHeader">
                        <!-- Year will be added here -->
                    </div>
                    <div class="select-year-list" id="yearList">
                        <!-- Year options will be added here -->
                    </div>
                </div>
            </div>
            <div class="calendar-content">
                <button id="prevDate"><i class="fa fa-angle-double-left fnt_sze_24"></i></button>
                <div class="calendar-days" name="calendarDays" id="calendarDays">
                    <!-- Calendar days content goes here -->
                </div>
                <button id="nextDate"><i class="fa fa-angle-double-right fnt_sze_24"></i></button>
            </div>
        </div>
    </div>
    <!-- <div id='result'>type here</div> -->
    <div class="dr_container" id='slotGrid'></div>
   <!--  <center>
        <button class='slide_from_left mrgn_tp_bt_13' name='saveSlots' id='saveSlots'>Save slot</button>
        <p id="errorMessage"></p>
        <p id="pgmsg"></p>

        </div>
    </center> -->

<script>
    $(document).ready(function() {
        let valid = true;
        $("#preloader1").hide();
        var currentDate = "<?= date('Y-m-d'); ?>";
        var selectedSlots = [];
        var selectedDate  = '';

        var jsonResponse = ''; // for addressing notices for CALENDAR DAY AJAX CALL
        var currentDateYYYYMMDD; //Initialize currentDateYYYYMMDD with the current date
        var currentDate  = new Date();
        var currentYear  = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth() + 1; //Months are zero-indexed, so add 1
        var currentDay = currentDate.getDate();
        currentDateYYYYMMDD  = currentYear + '-' + ("0" + currentMonth).slice(-2) + '-' + ("0" + currentDay).slice(-2);
        
        populateSlotGrid(); // Called Initial population of slot grid

       /* @param:
        * @desc: Populate doctor slots, dynamically
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        function populateSlotGrid() {
            // Draw and populate time slots
            var frm = $("<form></form>");
            frm.attr('id', 'slotfrm');
            frm.attr('name', 'slotfrm');
            frm.attr('method', 'post'); 
            var availableSlots = 0;
            var slotGrid = $("#slotGrid").append(frm);
            
            availableSlots = getAvailableSlots();
            if (availableSlots.length > 0) {
                var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
                var slotnum = 0;
                
                availableSlots.forEach(function(slot) {
                    var slotElement = $("<div>");
                    if (slotnum === 0) {
                        var eveningShift = $("<div>")
                            .text("Morning Shift [ 06:00 am To 02:00 pm ]")
                            .addClass("shiftlabel evening-shift") // Apply class for styling
                            .attr("colspan", 8); // Span across 8 slots
                        eveningShift.appendTo(slotGridElement); // Add to the grid
                    }
                    // Handling selected and unselected slots
                    if (slot.startsWith('1')) {
                        slot = slot.slice(1, ); // Sliced FIRST (dr_available: 1 or 0) to END
                        slotElement = slotElement.addClass("slot selected"); // Mark selected slot
                        selectedSlots.push(slot);
                    } 
                    else {
                        slot = slot.slice(1, ); // Sliced prefixed (1 or 0) to END
                        slotElement = slotElement.addClass("slot"); // NOT selected slots
                    }
                    slotElement = slotElement.text(slot).appendTo(slotGridElement);
                    
                    // Add "Evening Shift" after every 32 slots, spanning 8 slots
                    if (slotnum % 32 === 31) { // Check for the 33rd slot (index 32)
                        if (slotnum === 31) {
                            var eveningShift = $("<div>")
                                .text("Evening Shift [ 02:00 pm To 10:00 pm ]")
                                .addClass("shiftlabel evening-shift") // Apply class for styling
                                .attr("colspan", 8); // Span across 8 slots
                            eveningShift.appendTo(slotGridElement); // Add to the grid
                        }
                        // Add Night Shift after 64 slots (for the next set)
                        if (slotnum === 63) {
                            var nightShift = $("<div>")
                                .text("Night Shift [ 10:00 pm To 06:00 pm ]")
                                .addClass("shiftlabel night-shift") // Apply class for styling
                                .attr("colspan", 8); // Span across 8 slots
                            nightShift.appendTo(slotGridElement); // Add to the grid
                        }
                    }
                    slotnum += 1;
                    
                    slotElement.on("click", function() { // Slot selection toggle
                        toggleSlotSelection($(this));
                    });
                });
                
                var optiondv = $("<div>");
                optiondv = optiondv.addClass("option-cls").appendTo(".slot");
            } 
            else { slotGrid.text("No slots available."); }
            //********Called current date function **************
            selectedDate = getTodayDateYyyyMmDd();
        } //function - Closed

        //**************************************
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

                //$('.form-container.doctor-').hide();
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
            $("#pickslot_ajax").on("click", function() { //Pickup Slot
               var selected_dr_id = $(this).data("dr_id");
               console.log("Selected doctor id " + selected_dr_id);
                var doc_name = $(this).data("dr_name");
                newpick_slt_id=$.trim(newpick_slt_id,'\n');
                if (typeof newpick_slt === 'undefined' || newpick_slt_id.length === 0){
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
                    //var booking_slot_arr = toggleSlotSelection(newSlotElem); // MUST have "Slot", "Doctor ID", "Doctor Name" & "Date"
                    console.log(new_booking_slot_arr);
                    //return false;
                    var queryString = $.param(new_booking_slot_arr);
                    window.location.href = "<?= base_url('Home/pick_slots/') ?>?" + queryString;
                }
            }); 

        //*************************************

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
        //$("#monthList").on('change', '.select-item', function() {
            console.log("Event delegation: Handle clicks on .select-item inside #monthList");
            $('#slotGrid').hide();
            $('.slot-grid').hide();
            $('#slotGridAjax').hide();

            selectDate(); //Mark Current Date get SELECTED
            var monthName = $(this).text(); // Get the name of the clicked month
            //console.log("Month name is " + monthName);

            selected_dr_id = $('#selected_dr_appoint').val();
            console.log("Selected doc id " + selected_dr_id);

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
                            var slotData = responseData.dr_slots;

                            var from_to_slot = [];
                            var dr_name = '';
                            
                            //console.table(slotData);
                            //console.log(JSON.stringify(slotData, null, 2));
                            //for(loop = 0; loop < drData.length; loop++ ) {
                            if(slotData && slotData != 'undefined') {
                                console.log("Available dr. slots are " + slotData.length);
                                if (Array.isArray(slotData) && slotData.length > 0) { 
                                    console.log("Array format response received");
                                    $('#rspns_errtxt_msg').hide();
                                    $('#rspns_successtxt_msg').html(jsonResponse.message).show();
                                    $('#dr_div_ajax').text(slotData[0].doctor_name);
                                    populateSlotGridNeo(slotData);
                                } 
                                else { //Handle the situation where slotData is not as expected
                                    $('#rspns_successtxt_msg').hide();
                                    $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                                    console.error("Empty responseData.dr_slots in response");
                                }
                            }
                            else { // Handle the situation where slotData is not as expected
                                $('#rspns_successtxt_msg').hide();
                                $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                            }
                        }
                        //}
                        else { // Handle the situation where slotData is not as expected
                            $('#rspns_successtxt_msg').hide();
                            $('#rspns_errtxt_msg').html(jsonResponse.message).show();
                        }
                    }
                    catch (error) {
                        $('#rspns_successtxt_msg').hide();
                        $('#rspns_errtxt_msg').html(error).show();
                        $('#rspns_successtxt_msg').html("Received slots are").show();
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log("Something went wrong");
                    console.log(xhr);
                    console.log(textStatus);
                    $('#rspns_successtxt_msg').hide();
                    $('#rspns_errtxt_msg').html("Occured ajax response error").show();
                }
            });
        } //function - Closed


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


       /* @param:
        * @desc: Get current date in YYYY-MM-DD format
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        function getTodayDateYyyyMmDd() {
            var todayDt = new Date();
            var date_yyyy_mm_dd = todayDt.getFullYear() + "-" + ((todayDt.getMonth() + 1).length != 2 ? "0" + (todayDt.getMonth() + 1) : (todayDt.getMonth() + 1)) + "-" + ((todayDt.getDate().toString()).length != 2 ? "0" + todayDt.getDate() : todayDt.getDate());
            return date_yyyy_mm_dd;
        } //function - Closed



       /* @param:
        * @desc: toggle function to select and de-select slots
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */

        // $('div.slot').click(function() { // Check if the clicked div has class 'selected'
        //     if ($(this).hasClass('selected')) { //true: if class is 'selected' already
        //         $(this).removeClass("selected");
        //         DelSlotAjax($(this)); //Delete slots - through Ajax 
        //     } 
        //     else { console.log("Clicked unselected");
        //         $(this).toggleClass("selected");
        //         SaveSlotAjax($(this)); //Save slots - through Ajax 
        //     }
        // });

       /* @param:
        * @desc: toggle function to select and de-select slots
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        function toggleSlotSelection(slotElement) {
            slotElement.toggleClass("selected");
            var slot = slotElement.text();
            if (slotElement.hasClass("selected")) { // Select slot
                selectedSlots.push(slot);
                SaveSlotAjax(slotElement); //Save slots - through Ajax 
            } 
            else { // Deselect slot
                $(this).removeClass("selected");
                DelSlotAjax(slotElement); //Delete slots - through Ajax 
            }
        } //funciton - Closed


       /* @param:
        * @desc: On Day/date click: for getting doctor available slots on that date
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        $('#calendarDays').on('click', '.calendar-day', function() {
            var day = $(this).text().trim();
            var selectedMonth = $('#monthHeader').text();
            var selectedYear = $('#yearHeader').text();
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + day).slice(-2);

            console.log(currentDateYYYYMMDD);
            var data = { date: currentDateYYYYMMDD, };
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Doctor/getset_dr_slots_ajax/') ?>",
                data: data,
                success: function(response) {
                    //console.log(response);
                    jsonResponse = $.parseJSON(response);
                    var currentMonth = jsonResponse.data.month;
                    var currentYear = jsonResponse.data.year;

                    selectedSlots = [];
                    populateSlotGrid();
                    //window.location.reload(true);
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("Something went wrong");
                    console.log(xhr);
                    console.log(textStatus);
                }
            }); //ajax - Closed
        }); //function - closed


       /* @param:
        * @desc: Save selected doctor available slots
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified: WORKING - JUST REPLACED WITH THE ON SELECTION BASED AJAX SAVE SLOT
        */
        // $("#saveSlots").on("click", function() {
        //     // Show the progress bar
        //     $("#preloader1").show();
        //     // Save into DB
        //     event.preventDefault(); // Prevent the form from submitting normally

        //     var selDtStr = JSON.stringify(currentDateYYYYMMDD);
        //     console.log(currentDateYYYYMMDD)
        //     var formData = JSON.stringify(selectedSlots);

        //     $.ajax({
        //         type: 'POST',
        //         url: "<?= base_url('/Doctor/save_slots/') ?>" + currentDateYYYYMMDD,
        //         data: formData,
        //         dataType: 'text',
        //         success: function(response) {
        //             var responseObj = JSON.parse(response);
        //             if (responseObj.status) {
        //                 $('#pgmsg').text(responseObj.message);
        //                 setTimeout(function() {
        //                     // Clear the success message
        //                     $('#pgmsg').text("");
        //                 }, 3000);
        //                 $("#preloader1").hide();
        //             }
        //         },
        //         error: function(xhr, textStatus, errorThrown) {
        //             // Handle the error
        //             $('#pgmsg').text(textStatus);
        //             // Hide the progress bar
        //             $("#preloader1").hide();
        //         }
        //     });
        // });


       /* @param:
        * @desc: On Selection: Ajax call to Save selected doctor vailablity slot
        * @use:
        * @date: February 8th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function SaveSlotAjax(slotElement) {
            var slot = slotElement.text();
            selectedSlots.push(slot);
            
            // Save into DB
            event.preventDefault(); // Prevent the form from submitting normally

            var selDtStr = JSON.stringify(currentDateYYYYMMDD);
            var formData = JSON.stringify(selectedSlots); //selected and de-selected slots array
            $("#preloader1").show();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Doctor/SaveSlotAjax/') ?>" + currentDateYYYYMMDD,
                data: formData,
                dataType: 'text',
                success: function(response) {
                    var responseObj = JSON.parse(response);
                    if (responseObj.status) {
                        $("#preloader1").hide();
                    }
                    else {
                        $(this).removeClass("selected"); //Removed selection on failure
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#pgmsg').text(textStatus);
                    $("#preloader1").hide();
                }
            });
        } //function - Closed

        setTimeout(function() {
            // Clear the success message
            $('#pgmsg').text("");
        }, 3000);

       /* @param:
        * @desc: On De-Selection: Ajax call to Delete selected doctor vailablity slot
        * @use:
        * @date: February 8th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function DelSlotAjax(slotElement) {
            event.preventDefault(); // Prevent the form from submitting normally
            $("#preloader1").show();
            var clickslot = slotElement.text();

            var frmdata = {
                'date': currentDateYYYYMMDD,
                'deselected_slot': clickslot
            }
            
            var selDtStr = JSON.stringify(frmdata);
            //console.log(selDtStr);
            //var formData = JSON.stringify(selectedSlots);
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Doctor/DelSlotAjax/') ?>" + selDtStr,
                data: selDtStr,
                dataType: 'text',
                success: function(response) {
                    var slot = slotElement.text();
                    var responseObj = JSON.parse(response);
                    if (responseObj.status) {
                        var slotIndex = selectedSlots.indexOf(slot);
                        if (slotIndex !== -1) { selectedSlots.splice(slotIndex, 1); }
                        $("#preloader1").hide();
                    }
                    else {$(this).toggleClass("selected");} //Selected slot again
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#pgmsg').text(textStatus);
                    $("#preloader1").hide();
                }
            });
        } //function - Closed


       /* @param:
        * @desc: Get available slots of the doctor
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        function getAvailableSlots() { 
            //Format:  return ["9:00:00 - 9:15:00", "9:15 - 9:30", "9:30 - 9:45"] - START
            var slots_arr = [];
            console.log('jsonResponse:');
            console.log(jsonResponse);
            if (jsonResponse.status) {  //status true (Ajax call)
                console.log("true ajax call case");
                $('.slot-grid').hide();
              
                slots_arr = [];
                $.each(jsonResponse.data, function(index, element) {
                    //console.log(element);
                    var slt = '';
                    // var set = '';
                    // var sst = '';
                    // var st = '';
                    // var et = '';

                    // st = element.slot_start;
                    // sst = st.slice(0, -3); //sliced start slots
                    // et = element.slot_end;
                    // set = et.slice(0, -3); //sliced start slots
                    // slt = sst + '--' + set;
                    slt = element.start_end_slot;
                    slots_arr.push(slt);
                });
               
                $('.slot-grid').hide();
                <?php drow_merge_set_unset_slots(); ?>
            }
            else if (jsonResponse.status == false) { //status false (Ajax call)
                console.log("false ajax call case");
                $('.slot-grid').hide();
                slots_arr = [];
                <?php drow_merge_set_unset_slots(); ?>
            }
            else { 
                console.log("else ajax call case");
                var slots_arr = []; //console.log('slots check else 1');
                <?php
                if (isset($dr_slots)) { ?>
                    //console.log('slots check else 2');
                    <?php
                    if (is_array($dr_slots)) { ?>
                        //console.log('slots check else 3');
                        <?php
                        if (count($dr_slots) > 0) { ?>
                            //console.log('slots check else 4');
                            <?php foreach ($dr_slots as $slots) : ?>
                                var slt = '';
                                // var set = '';
                                // var sst = '';
                                // var st = '';
                                // var et = '';

                                // st = "<?= $slots->slot_start ?>";
                                // sst = st.slice(0, -3); //sliced start slots
                                // et = "<?= $slots->slot_end ?>";
                                // set = et.slice(0, -3); //sliced start slots
                                // slt = sst + '--' + set;
                                slt = "<?= $slots->start_end_slot; ?>";
                                slots_arr.push(slt);
                            <?php endforeach; ?>
                           // console.log('slots check db');
                           // console.log(slots_arr);
                         <?php 
                            drow_merge_set_unset_slots();
                        } else {
                            drow_merge_set_unset_slots();
                        }
                    } else {
                        drow_merge_set_unset_slots();
                    }
                } else {
                    drow_merge_set_unset_slots();
                } ?> //outer else - Closed
                //console.log(slots_arr);
                //return slots_arr; //Format:  return ["9:00 - 9:15", "9:15 - 9:30", "9:30 - 9:45"] - END
            }
            
            //return slots_arr; //Format:  return ["9:00 - 9:15", "9:15 - 9:30", "9:30 - 9:45"] - END
        } //Function - Closed
        if (!valid) {
            e.preventDefault();
        }
    }); //document.ready - Closed

    <?php
    /* @param: Check is, json string or not?
     * @description:  
     * @use:
     * @date: 
     * @modify:
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
    function isJSON($str) {
        //Return true for JSON else return `false`
        return is_string($str) && is_array(json_decode($str, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /* @param: Draw/Populate freely available doctor slots
     * @description:  
     * @use:
     * @date: 
     * @modify:
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
    function drow_merge_set_unset_slots() { ?>
        //console.log("Free & available slots merging - START");
        var slots_arr_neo = [];
        <?php $start = strtotime('06:00');
        $end = strtotime('06:00', strtotime('tomorrow')); // End at 6:00 AM the next day
        $r = 0;
        for ($i = $start; $i <= $end; $i = $i + 15 * 60) {
            $r += 1;
            if ($r == 1) { //Start slot time
                $odd_val = '';
                $odd_val = date('H:i', $i); //Start slot time
            } 
            else if ($r % 2 == 0) {
                $even_val = '';
                $even_val = date('H:i', $i);
                $from_to_slot = $odd_val . '--' . $even_val; ?>
                
                slots_arr_neo.push("<?= $from_to_slot ?>");
                
            <?php } 
            else {
                $odd_val = '';
                $odd_val = date('H:i', $i);
                $from_to_slot = $even_val . '--' . $odd_val;
                ?>
                slots_arr_neo.push("<?= $from_to_slot ?>");
                <?php
            }
        } ?> //for - loop Closed

      
        
       // console.log(slots_arr_neo);
        //var slots_arr_neo_mrg = [];
        $.each(slots_arr_neo, function(index, element) {
            if ($.inArray(element, slots_arr) !== -1) {
                slots_arr_neo[index] = '1' + element; //Set available slots
            } else {
                slots_arr_neo[index] = '0' + element; //Set NOT available slots
            }
        });
        return slots_arr_neo;
        <?php 
    } ?> //function - Closed


    /* @param: Convert String to Month number
     * @description:  
     * @use:
     * @date: 
     * @modify:
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
    function convertStrMonthToNum(monthName) {
        var mnthNm = monthName;
        switch (mnthNm) {
            case 'January':
                return '01';
                break;
            case 'February':
                return '02';
                break;
            case 'March':
                return '03';
                break;
            case 'April':
                return '04';
                break;
            case 'May':
                return '05';
                break;
            case 'June':
                return '06';
                break;
            case 'July':
                return '07';
                break;
            case 'August':
                return '08';
                break;
            case 'September':
                return '09';
                break;
            case 'October':
                return '10';
                break;
            case 'November':
                return '11';
                break;
            case 'December':
                return '12';
                break;
            default:
                return '0';
        }  //switch loop - Closed
    }
</script>
    <!---Js file Include -->
    <?= view('Admin/js_file.php'); ?>
    <!---Js file Include -->
    <?= view('Doctor/date_calendar_js.php'); ?>
</body>

</html>