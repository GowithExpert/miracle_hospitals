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
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Doctor's Availability Slots</title>
    <!---Include Css File --->
    <?= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Doctor/doctor_availability_css_file.php'); ?>
    <!---Include Css File --->
    <?= view('Home/css_file'); ?>
</head>
<style>
    .disabled-div {
            pointer-events: none;
            /*opacity: 0.5;*/
            /* Optional: Add additional styles to convey disabled state */
            background-color: #f2f2f2;
            color: #999;
            cursor: not-allowed;
        }
</style>
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
    <div class="row cntr">
        <div id="rspns_errtxt_msg"></div>      <!-- Show slot's error message --> 
        <div id="rspns_successtxt_msg"></div>  <!-- Show slot's success message -->
    </div>
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
        //var currentDate = "<?= date('Y-m-d'); ?>";
        var selectedDate = "<?= date('Y-m-d'); ?>";
        var selectedSlots = [];
        var todayDate  = '';


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
            console.log(JSON.stringify(availableSlots, null, 2));
            if(!Array.isArray(availableSlots)) {
                $('#rspns_errtxt_msg').hide();
                $('#rspns_successtxt_msg').hide();
                $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                
                var noSlotAvail = $('#rspns_errtxt_msg').html("Unexpected slot result format").show();
                noSlotAvail.appendTo(slotGrid);
                return;
            }
            else if (availableSlots.length > 0) {
                var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
                var slotnum = 0;
                var order = 25; //06:00--06:15 ie 25th slot (1st slot is 00:00--00:15)
                availableSlots.forEach(function(slot) {
                    //isCurrent date & upcoming slots
                    const is_past_slot = getCurrentTime(slot);
                    if(is_past_slot) {
                        if(slotnum < 72) { //(1st slot is 00:00--00:15. 72th slot is 23:45--00:00. 72th to 96th slots are NEXT DATE slots.
                            var slotElement = $("<div>").addClass("disabled-div");//Disabled
                        }
                        else { var slotElement =  $("<div>"); } //Enable Slot div
                    }
                    else { 
                        var slotElement =  $("<div>"); 
                        //var slotElement = $("<div>").addClass("slot");
                    }
                    //========== code end here ==========

                    // old code
                    //console.log(JSON.stringify(slot, null, 2));
                    //var slotElement = $("<div>");
                    
                    var slotOrder = $("<span hidden='hidden'>");
                    
                    if (slotnum === 0) {
                        var eveningShift = $("<div>")
                            .text("Morning Shift [ 06:00 am To 02:00 pm ]")
                            .addClass("shiftlabel evening-shift") // Apply class for styling
                            .attr("colspan", 8); // Span across 8 slots
                        eveningShift.appendTo(slotGridElement); // Add to the grid
                    } 
                    // Handling selected and unselected slots
                    if (slot.startsWith('1')) { //106:00--06:15 will be: 06:00--06:15
                        console.log("Available Slots are: >>");
                        slot = slot.slice(1, ); //Sliced EXCEPT FIRST (2nd to END)
                        slotElement = slotElement.addClass("slot selected");//Selected
                        selectedSlots.push(slot);
                    } 
                    else { console.log("Un-Available Slots are: >>");
                        slot = slot.slice(1, ); //Sliced EXCEPT FIRST (2nd to END)
                        slotElement = slotElement.addClass("slot"); // NOT selected slots
                    }

                    if(slotnum === 72){ order = 1; }
                    
                    slotElement = slotElement.text(slot).appendTo(slotGridElement);
                    //==
                    slotOrder = slotOrder.addClass("slotOrder");//Selected
                    slotOrder = slotOrder.text(order).appendTo(slotGridElement);
                    //==
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
                                .text("Night Shift [ 10:00 pm To 06:00 am ]")
                                .addClass("shiftlabel night-shift") // Apply class for styling
                                .attr("colspan", 8); // Span across 8 slots
                            nightShift.appendTo(slotGridElement); // Add to the grid
                        }
                    }
                    slotnum += 1;
                    order += 1;
                    
                    slotElement.on("click", function() { // Slot selection toggle
                        toggleSlotSelection($(this));
                    });
                });
                var optiondv = $("<div>");
                optiondv = optiondv.addClass("option-cls").appendTo(".slot");
            } 
            else { 
                console.log("This is reaching here");
                slotGrid.text("No slots available."); 
            }
           
            //********Called current date function **************
            todayDate = getTodayDateYyyyMmDd();
        } //function - Closed

        //*************************************
        $('#dr_appoint').val('');
        var selectedSlots = [];
        var slotElement = '';
        var slotElement_neo = '';

        $(function() { //Disable previous date in calendar
            var dtToday = new Date();
            var month = dtToday.getMonth() + 1; // Note the correction here
            var day   = dtToday.getDate();
            var year  = dtToday.getFullYear();
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
        function selectCurrentDate() {
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
            //console.log(JSON.stringify(data, null, 2));
            $("#preloader1").show();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('Doctor/getset_dr_slots_ajax_neo') ?>",
                data: data,
                success: function(response) {
                    $("#preloader1").hide();
                    jsonResponse = $.parseJSON(response);
                    if(!jsonResponse.status) {
                         console.log("Status is: " + jsonResponse.status);
                        // var currentMonth = jsonResponse.data.month;
                        // var currentYear = jsonResponse.data.year;
                        selectedSlots = [];
                        populateSlotGrid();
                        //window.location.reload(true);
                    }
                    else if(jsonResponse.status) {
                        console.log("Status is: " + jsonResponse.status);
                        var currentMonth = jsonResponse.data.month;
                        var currentYear = jsonResponse.data.year;
                        selectedSlots = jsonResponse.data;
                        populateSlotGrid();
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("Something went wrong");
                    console.log(xhr);
                    console.log(textStatus);
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

            selectCurrentDate(); //Mark Current Date get SELECTED
            var monthName = $(this).text(); // Get the name of the clicked month

            //selected_dr_id = $('#selected_dr_appoint').val();
            //console.log("Selected doc id " + selected_dr_id);

            //$("#pickslot_ajax").data("dr_id", selected_dr_id); //??
            //doc_name = $('#selected_dr_appoint').find('option:selected').text();
            if (!todayDate) {
                console.log("Please select a date.");
                $('#rspns_errtxt_msg').hide();
                $('#rspns_successtxt_msg').hide();
                $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                return;
            }
            
            // var selectedMonth = $('#monthHeader').text();
            // var selectedYear = $('#yearHeader').text();
            // var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            // var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0"+todayDate).slice(-2);

            selectedDate = getSelectedDate();
            var data = { selected_date: selectedDate, };
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
           
            selectCurrentDate(); //called date selection function

            var monthName = $(this).text(); // Get the name of the clicked month
            //console.log("Month name is " + monthName);

            //selected_dr_id = $('#selected_dr_appoint').val();
            //console.log("Selected doc id " + selected_dr_id);

            //$("#pickslot_ajax").data("dr_id", selected_dr_id); //??
            //doc_name = $('#selected_dr_appoint').find('option:selected').text();
            if (!todayDate) {
                console.log("Please select a date.");
                $('#rspns_errtxt_msg').hide();
                $('#rspns_successtxt_msg').hide();
                $('#rspns_errtxt_msg').html("Please select an appointment Day").show();
                return;
            }
            
            // var selectedMonth = $('#monthHeader').text();
            // var selectedYear = $('#yearHeader').text();
            // var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            // var currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + todayDate).slice(-2);

            selectedDate = getSelectedDate();
            var data = {
                //dr_id: selected_dr_id,
                selected_date: selectedDate,
            };

            DoctorSlotsAjaxCall(data); //Doctor Slots Ajax function Called
        }); //function - Closed


        //*************************************

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
        * @desc: check today date or not
        * @use:
        * @date: Apr, 12, 2025
        * @author: Nearks Team
        * @modified:
        */
        function isToday (date) {  
            const now = new Date()
            if(date.getDate() === now.getDate() &&
                date.getMonth() === now.getMonth() &&
                date.getFullYear() === now.getFullYear()){
                    return true;    
            }
            else{
                return false;
            }
        }

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
        //         SaveSlotAjaxNeo($(this)); //Save slots - through Ajax 
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
            //selectedDate = getSelectedDate(); //Selected Date
            
            slotElement.toggleClass("selected"); //Select Slot
            //var a = $("slotElement").next().text();//css({"color": "red", "border": "2px solid red"});
            var slot_number = $(slotElement).nextAll('.slotOrder:first').text();
            slot_number = JSON.stringify(slot_number);
            slot_number = slot_number.replace(/"/g, "");
            //alert(a);
            var slot = slotElement.text();
            if (slotElement.hasClass("selected")) { // Select slot
                //selectedSlots.push(slot);
                SaveSlotAjaxNeo(slotElement,slot_number); //Save slots - through Ajax 
            } 
            else { // Deselect slot
                console.log("DelSlotAjaxNeo call")
                $(this).removeClass("selected");
                DelSlotAjaxNeo(slotElement); //Delete slots - through Ajax 
            }
        } //funciton - Closed


        function getSelectedDate() {
            var selectedDay = $('#calendarDays .calendar-day.selected .day-number').text();
            var selectedMonth = $('#monthHeader').text();
            var selectedYear = $('#yearHeader').text();
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            var selectedDate = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0"+selectedDay).slice(-2);
            if(selectedDate !== 'undefined' || selectedDate !== null) { 
                console.log("Selected Day is:  "+'"'+selectedDate + '"');
                return selectedDate; 
            }
            else { return currentDateYYYYMMDD; }
        } //function - Closed

       /* @param:
        * @desc: On Day/date click: for getting doctor available slots on that date
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        $('#calendarDays').on('click', '.calendar-day', function() {
            // var day = $(this).text().trim();
            // var selectedMonth = $('#monthHeader').text();
            // var selectedYear = $('#yearHeader').text();
            // var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            // currentDateYYYYMMDD = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0" + day).slice(-2);
            

            selectedSlots = [];
            selectedDate = getSelectedDate();
            var data = { selected_date: selectedDate, };
            DoctorSlotsAjaxCall(data);
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
        * @date: February 13th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function SaveSlotAjaxNeo(slotElement,slot_number) {
            var selectedSlot = slotElement.text();
            //selectedSlots.push(slot);
            
            // Save into DB
            event.preventDefault(); // Prevent the form from submitting normally

            selectedDate = getSelectedDate(); //Selected Date
            var formData = {
                'apmt_date': selectedDate,
                'selected_slot': selectedSlot,
                'slot_order':slot_number
            }
            //var frmData = JSON.stringify(slot);
            var strngfyFrmData = JSON.stringify(formData); 
            //console.log(formData);
            $("#preloader1").show();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Doctor/SaveSlotAjaxNeo/') ?>",
                data: strngfyFrmData,
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
        * @date: February 13th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function DelSlotAjaxNeo(slotElement) {
            event.preventDefault(); // Prevent the form from submitting normally
            $("#preloader1").show();
            var selectedSlot = slotElement.text();

            // var frmdata = {
            //     'date': currentDateYYYYMMDD,
            //     'deselected_slot': clickslot
            // }
            
            // var selDtStr = JSON.stringify(frmdata);
            // //console.log(selDtStr);
            // //var formData = JSON.stringify(selectedSlots);


            selectedDate = getSelectedDate(); //Selected Date
            var formData = {
                'apmt_date': selectedDate,
                'selected_slot': selectedSlot,
            }
            //var frmData = JSON.stringify(slot);
            var strngfyFrmData = JSON.stringify(formData); 
            //console.log(formData);
            $("#preloader1").show();
            $.ajax({
                type: 'POST',
                //url: "<?= base_url('/Doctor/DelSlotAjaxNeo/') ?>" + selDtStr,
                url: "<?= base_url('/Doctor/DelSlotAjaxNeo/') ?>",
                data: strngfyFrmData,
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
                'date': currentDateYYYYMMDD, //Current Date
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
            //Format:  return ["9:00--9:15", "9:15--9:30"] - START
            var slots_arr = [];
            var slt = '';
            //console.log('jsonResponse is: ' + JSON.stringify(jsonResponse, null, 2));
            if (jsonResponse.status) {  //status true (Ajax call)
                console.log("true ajax call case");
                $('.slot-grid').hide();
              
                slots_arr = [];
                 $.each(jsonResponse.data, function(index, element) {
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
            else { //Non - Ajax get slots from doctor_slots table 
                console.log("else ajax call case");
                var slots_arr = []; //console.log('slots check else 1');
                <?php
                if (isset($dr_slots)) { ?>
                    console.log('$dr_slots has set');
                    <?php
                    if (is_array($dr_slots)) { ?>
                        console.log('$dr_slots is an array');
                        <?php
                        if (count($dr_slots) > 0) { ?> console.log('count($dr_slots) id > 0');
                            <?php foreach ($dr_slots as $slots): 
                                if(isset($slots->start_end_slot)) {  ?>
                                    slt = '<?= (string)$slots->start_end_slot; ?>'
                                    <?php
                                } 
                                else { ?>
                                    slt = '00:00--00:00'; //For addressing notices
                                    <?php 
                                } ?>
                                slots_arr.push(slt);
                            <?php endforeach; ?>
                               console.log('Why drow_merge_set_unset_slots();');
                         <?php drow_merge_set_unset_slots();
                        } 
                        else { ?> console.log('count($dr_slots) is not > 0');
                            <?php drow_merge_set_unset_slots();
                        }
                    } 
                    else { ?> console.log('$dr_slots is not an array');
                        <?php  drow_merge_set_unset_slots();
                    }
                } 
                else { ?> console.log('isset($dr_slots) fails');
                    <?php drow_merge_set_unset_slots();
                } ?> //outer else - Closed
                //console.log(slots_arr);//output format: ["9:00 - 9:15", "9:15 - 9:30"] - END
            } //else - loop Closed
        } //Function - Closed

        /* @param: 
    * @desc: Select the current date on month selection etc
    * @date: march 9, 2025 
    * @author: Neoark Software Pvt Ltd Team
    */
   
    function getCurrentTime(slot) {
        //Is selected date === today's date - START
        var selectedDay = $('#calendarDays .calendar-day.selected .day-number').text();
        var selectedMonth = $('#monthHeader').text();
        var selectedYear = $('#yearHeader').text();
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var selectedDate = selectedYear + '-' + ("0" + (months.indexOf(selectedMonth) + 1)).slice(-2) + '-' + ("0"+selectedDay).slice(-2);
        var date="";
        
        
        if(selectedDay.length == 0 ||selectedMonth.length == 0||selectedYear.length == 0){
            date = true; //default page - render
        }
        else if(selectedDate.length >= 10){
            console.log("Mytodays date is " +selectedDate );

            var todayDate = isToday(new Date(selectedDate));
            if(todayDate ){
                date = true; 
            }
            else {
                date = false; 
            }
            
        }
        else {
            date = false;
        }  //Is selected date === today's date - END
        
        const currentTime = new Date();
        // Get the current time in India (IST) in 24-hour format
        const indiaTime = currentTime.toLocaleTimeString("<?= LOCALE_TM_STR ?>", {
            timeZone: "<?= APP_TIMEZONE ?>", //Refer Constants.php
            hour: "<?= HRS_DIGITS ?>", //Refer Constants.php
            minute: "<?= MIN_DIGITS ?>", //Refer Constants.php
            hour12: false, // 24-hour format
        });
        
        const [cu_hours, cu_minutes] = indiaTime.split(':');        
        cu_hours_val = Number(cu_hours);
        cu_minutes_val = Number(cu_minutes);
        let hour_min = [cu_hours, cu_minutes];
        
        //==
        //const [cu_hours_val, cu_minutes_val] = getCurrentTime();
        slot_val = slot.slice(1, );
        slot_val1 = slot_val.slice(0, 5); //06:00--06:15
        const [st_hours, st_minutes] = slot_val1.split(':');

        st_hours_val  = Number(st_hours);
        st_minutes_val  = Number(st_minutes);

        //var slotElement = "";
        if(date) {
            if(st_hours_val < cu_hours_val || (st_hours_val == cu_hours_val && st_minutes_val <= cu_minutes_val)) {
                return true;
            }
            else { return false;}
        }
        return false;
    } //function closed

    

        if (!valid) { e.preventDefault(); }
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
        //console.log("Free & Set slots merging - START");
        var slots_arr_neo = [];
        <?php $start = strtotime('06:00');
        $end = strtotime('06:00', strtotime('tomorrow')); // End at 6:00 AM the next day
        $slot_interval = 15; //15 minutes Dr. slots interval
        $r = 0;
        for ($i = $start; $i <= $end; $i = $i + $slot_interval * 60) {
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
                <?php 
            } 
            else {
                $odd_val = '';
                $odd_val = date('H:i', $i);
                $from_to_slot = $even_val . '--' . $odd_val;
                ?>
                slots_arr_neo.push("<?= $from_to_slot ?>");
                <?php
            }
        } ?> //for - loop Closed

        $.each(slots_arr_neo, function(index, element) {
            if ($.inArray(element, slots_arr) !== -1) {
                slots_arr_neo[index] = '1' + element; //Prefix 1 - to show NOT set slot 
            } else {
                slots_arr_neo[index] = '0' + element; //Prefix 0 - to show slot set already
            }
        });
        //console.log("slots_arr_neo is: "+slots_arr_neo);
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