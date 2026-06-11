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
    <?= view('Admin/css_file.php'); ?>
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
        
        //populateSlotGrid(); // Called Initial population of slot grid
        drow_merge_set_unset_slots();


    /* @param: Draw/Populate freely available doctor slots
     * @description:  
     * @use:
     * @date: 
     * @modify:
     * @author: Neoark's Team
     * @copyrights: Neoark Software Pvt Ltd
     */
    function drow_merge_set_unset_slots() {
        var slots_arr = getAvailableSlots();
        var slots_arr_neo = [];

        var start = new Date();
        start.setHours(6, 0, 0, 0);     //Slot Start time (06:00 AM current/Today Day)

        var end = new Date(start);
        end.setDate(end.getDate() + 1); //Slot END time (06:00 AM Next Day/tomorrow)
        
        var slot_interval = 15; // 15 minutes Dr. slots time interval
       
       //******* Create slot form ************
        var frm = $("<form></form>");
        frm.attr('id', 'slotfrm');
        frm.attr('name', 'slotfrm');
        frm.attr('method', 'post'); 

        var availableSlots = 0;
        var slotGrid = $("#slotGrid").append(frm);

        var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
        // Handling selected and unselected slots
        var slotnum = 0;
        var r = 0;
        var odd_val, even_val, from_to_slot;

        // Loop from start time to end time, in 15-minute intervals
        for (var i = start.getTime(); i <= end.getTime(); i += slot_interval * 60000) {
            var slotElement = $("<div>");
            console.log(JSON.stringify('-->'+start.getTime() +'-->'+end.getTime() +'-->'+slot_interval * 60000, null, 2))
            if (slotnum === 0) { //Morning Shift, Title Strip - START
                var eveningShift = $("<div>")
                    .text("Morning Shift [ 06:00 am To 02:00 pm ]")
                    .addClass("shiftlabel evening-shift") // Apply class for styling
                    .attr("colspan", 8); // Span across 8 slots
                eveningShift.appendTo(slotGridElement); // Add to the grid
            }  //Morning Shift, Title Strip - END

            r += 1;
            slotnum += 1;
            var currentTime = new Date(i);
            var timeString = currentTime.getHours().toString().padStart(2, '0') + ':' + currentTime.getMinutes().toString().padStart(2, '0');

            if (r == 1) {  odd_val = timeString; } // Start slot time
            else if (r % 2 == 0) {  //r = even (2, 4, 6 etc)
                even_val = timeString;
                from_to_slot = odd_val + '--' + even_val;
                if ($.inArray(from_to_slot, slots_arr) !== -1) { //Match Selected Slots
                     //console.log('%2 == 0 Selected slots r ' + r );
                    slotElement = slotElement.addClass("slot selected");//Mark selected
                } 
                else {
                    //console.log('%2 == 0 Non-Selected slots r ' + r );
                    slotElement = slotElement.addClass("slot"); //Mark Non-selected slots
                }
                //slots_arr_neo.push(from_to_slot);
            } 
            else {  //Odd Slots 
                odd_val = timeString;
                from_to_slot = even_val + '--' + odd_val;
                if ($.inArray(from_to_slot, slots_arr) !== -1) { //Match selected slots 
                    //console.log('Odd - Selected slots r ' + r); 
                    slotElement = slotElement.addClass("slot selected"); // Mark selected
                } 
                else { //Odd Slots 
                    //console.log('Odd - Non-selected slots r ' + r); 
                    slotElement = slotElement.addClass("slot"); // Mark Non-selected slots
                    //selectedSlots.push(from_to_slot); //????????????
                }
                //slots_arr_neo.push(from_to_slot);
                
                //console.log('slotnum % 32 is ' + slotnum % 32)
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
                slotElement = slotElement.text(from_to_slot).appendTo(slotGridElement);
                //slotnum += 1;
                
                slotElement.on("click", function() { // Slot selection toggle
                    toggleSlotSelection($(this));
                });
            } //else loop - Closed
        } //for - closed
        var optiondv = $("<div>");
        optiondv = optiondv.addClass("option-cls").appendTo(".slot");       
        //********Called current date function **************
        //selectedDate = getTodayDateYyyyMmDd();
    }

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
        $('.slot-grid').hide();
        if (jsonResponse.status) {  //status true (Ajax call)
            console.log("true ajax call case");
            //$('.slot-grid').hide();
          
            //slots_arr = [];
            $.each(jsonResponse.data, function(index, element) {
                var slt = element.start_end_slot;
                slots_arr.push(slt);
            });
            //$('.slot-grid').hide();
            <?php // drow_merge_set_unset_slots(); ?>
            //drow_merge_set_unset_slots(slots_arr);
            return slots_arr;
        }
        else if (jsonResponse.status == false) { //status false (Ajax call)
            console.log("false ajax call case");
            //$('.slot-grid').hide();
            //slots_arr = [];
            <?php //drow_merge_set_unset_slots(); ?>
            //drow_merge_set_unset_slots(slots_arr);
            return slots_arr;
        }
        else { //Non - Ajax get slots from doctor_slots table 
            console.log("else ajax call case");
            //var slots_arr = []; //console.log('slots check else 1');
            <?php
            if (isset($dr_slots)) { ?>
                console.log('$dr_slots has set');
                <?php
                if (is_array($dr_slots)) { ?>
                    console.log('$dr_slots is an array');
                    <?php
                    if (count($dr_slots) > 0) { ?> console.log('count($dr_slots) id > 0');
                        <?php foreach ($dr_slots as $slots): ?>
                            var slt = "<?= $slots->start_end_slot ?>";
                            slots_arr.push(slt);
                        <?php endforeach; ?>
                           console.log('Why drow_merge_set_unset_slots();');
                           //drow_merge_set_unset_slots(slots_arr);
                           return slots_arr;
                     <?php //drow_merge_set_unset_slots();
                    } 
                    else { ?> console.log('count($dr_slots) is not > 0');
                        //drow_merge_set_unset_slots(slots_arr);
                        return slots_arr;
                        <?php //drow_merge_set_unset_slots();
                    }
                } 
                else { ?> console.log('$dr_slots is not an array ');
                    //slots_arr = ['06:00--06:15', '06:30--06:45', '07:00--07:15'];
                    //console.log(JSON.stringify(slots_arr, null, 2));
                    //drow_merge_set_unset_slots(slots_arr);
                    return slots_arr;
                    <?php  //drow_merge_set_unset_slots();
                }
            } 
            else { ?> console.log('isset($dr_slots) fails');
                //drow_merge_set_unset_slots(slots_arr);
                return slots_arr;
                <?php //drow_merge_set_unset_slots();
            } ?> //outer else - Closed
            //console.log(slots_arr);//output format: ["9:00 - 9:15", "9:15 - 9:30"] - END
        } //else - loop Closed
    } //Function - Closed

       /* @param:
        * @desc: Populate doctor slots, dynamically
        * @use:
        * @date: August, 2029, 2023
        * @author: Nearks Team
        * @modified:
        */
        // function populateSlotGrid() {
        //     // Draw and populate time slots
        //     var frm = $("<form></form>");
        //     frm.attr('id', 'slotfrm');
        //     frm.attr('name', 'slotfrm');
        //     frm.attr('method', 'post'); 
        //     var availableSlots = 0;
        //     var slotGrid = $("#slotGrid").append(frm);
        
        //     availableSlots = getAvailableSlots();
        //     if (availableSlots.length > 0) {
        //         var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
        //         var slotnum = 0;
                
        //         availableSlots.forEach(function(slot) {
        //             var slotElement = $("<div>");
        //             if (slotnum === 0) {
        //                 var eveningShift = $("<div>")
        //                     .text("Morning Shift [ 06:00 am To 02:00 pm ]")
        //                     .addClass("shiftlabel evening-shift") // Apply class for styling
        //                     .attr("colspan", 8); // Span across 8 slots
        //                 eveningShift.appendTo(slotGridElement); // Add to the grid
        //             } 
        //             // Handling selected and unselected slots
        //             if (slot.startsWith('1')) { //106:00--06:15
        //                 slot = slot.slice(1, ); // Sliced FIRST (dr_available: 1 or 0) to END
        //                 slotElement = slotElement.addClass("slot selected");// Mark selected slot
        //                 selectedSlots.push(slot);
        //             } 
        //             else {
        //                 slot = slot.slice(1, ); // Sliced prefixed (1 or 0) to END
        //                 slotElement = slotElement.addClass("slot"); // NOT selected slots
        //             }
        //             slotElement = slotElement.text(slot).appendTo(slotGridElement);
                    
        //             // Add "Evening Shift" after every 32 slots, spanning 8 slots
        //             if (slotnum % 32 === 31) { // Check for the 33rd slot (index 32)
        //                 if (slotnum === 31) {
        //                     var eveningShift = $("<div>")
        //                         .text("Evening Shift [ 02:00 pm To 10:00 pm ]")
        //                         .addClass("shiftlabel evening-shift") // Apply class for styling
        //                         .attr("colspan", 8); // Span across 8 slots
        //                     eveningShift.appendTo(slotGridElement); // Add to the grid
        //                 }
        //                 // Add Night Shift after 64 slots (for the next set)
        //                 if (slotnum === 63) {
        //                     var nightShift = $("<div>")
        //                         .text("Night Shift [ 10:00 pm To 06:00 pm ]")
        //                         .addClass("shiftlabel night-shift") // Apply class for styling
        //                         .attr("colspan", 8); // Span across 8 slots
        //                     nightShift.appendTo(slotGridElement); // Add to the grid
        //                 }
        //             }
        //             slotnum += 1;
                    
        //             slotElement.on("click", function() { // Slot selection toggle
        //                 toggleSlotSelection($(this));
        //             });
        //         });
                
        //         var optiondv = $("<div>");
        //         optiondv = optiondv.addClass("option-cls").appendTo(".slot");
        //     } 
        //     else { slotGrid.text("No slots available."); }
        //     //********Called current date function **************
        //     selectedDate = getTodayDateYyyyMmDd();
        // } //function - Closed


        // function populateSlotGrid() {
        //     // Draw and populate time slots
        //     var frm = $("<form></form>");
        //     frm.attr('id', 'slotfrm');
        //     frm.attr('name', 'slotfrm');
        //     frm.attr('method', 'post'); 
        //     var availableSlots = 0;
        //     var slotGrid = $("#slotGrid").append(frm);
        //     //*****************************
        //     // if (jsonResponse.status) {  //status true (Ajax call)
        //     //     console.log("true ajax call case");
        //     //     $('.slot-grid').hide();
        //     //     var slt = jsonResponse.start_end_slot;
        //     // }
        //     //*****************************
        //     availableSlots = getAvailableSlots();
        //     if(availableSlots = 'undefined') {
        //         slotGrid.text("availableSlots is undefined");
        //     }
        //     else if(availableSlots.length > 0) {
        //         var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
        //         var slotnum = 0;
                
        //         availableSlots.forEach(function(slot) {
        //             var slotElement = $("<div>");
        //             if (slotnum === 0) {
        //                 var eveningShift = $("<div>")
        //                     .text("Morning Shift [ 06:00 am To 02:00 pm ]")
        //                     .addClass("shiftlabel evening-shift") // Apply class for styling
        //                     .attr("colspan", 8); // Span across 8 slots
        //                 eveningShift.appendTo(slotGridElement); // Add to the grid
        //             } 
        //             // Handling selected and unselected slots
        //             if (slot.startsWith('1')) { //106:00--06:15
        //                 slot = slot.slice(1, ); // Sliced FIRST (dr_available: 1 or 0) to END
        //                 slotElement = slotElement.addClass("slot selected"); // Mark selected slot
        //                 selectedSlots.push(slot);
        //             } 
        //             else {
        //                 slot = slot.slice(1, ); // Sliced prefixed (1 or 0) to END
        //                 slotElement = slotElement.addClass("slot"); // NOT selected slots
        //             }
        //             slotElement = slotElement.text(slot).appendTo(slotGridElement);
                    
        //             // Add "Evening Shift" after every 32 slots, spanning 8 slots
        //             if (slotnum % 32 === 31) { // Check for the 33rd slot (index 32)
        //                 if (slotnum === 31) {
        //                     var eveningShift = $("<div>")
        //                         .text("Evening Shift [ 02:00 pm To 10:00 pm ]")
        //                         .addClass("shiftlabel evening-shift") // Apply class for styling
        //                         .attr("colspan", 8); // Span across 8 slots
        //                     eveningShift.appendTo(slotGridElement); // Add to the grid
        //                 }
        //                 // Add Night Shift after 64 slots (for the next set)
        //                 if (slotnum === 63) {
        //                     var nightShift = $("<div>")
        //                         .text("Night Shift [ 10:00 pm To 06:00 pm ]")
        //                         .addClass("shiftlabel night-shift") // Apply class for styling
        //                         .attr("colspan", 8); // Span across 8 slots
        //                     nightShift.appendTo(slotGridElement); // Add to the grid
        //                 }
        //             }
        //             slotnum += 1;
                    
        //             slotElement.on("click", function() { // Slot selection toggle
        //                 toggleSlotSelection($(this));
        //             });
        //         });
                
        //         var optiondv = $("<div>");
        //         optiondv = optiondv.addClass("option-cls").appendTo(".slot");
        //     } 
        //     else { slotGrid.text("Unexpected use case!"); }
        //     //********Called current date function **************
        //     selectedDate = getTodayDateYyyyMmDd();
        // } //function - Closed

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
            slotElement.toggleClass("selected");
            var slot = slotElement.text();
            console.log("newly selected slot is " + slot);
            if (slotElement.hasClass("selected")) { // Select slot
                //selectedSlots.push(slot);
                SaveSlotAjaxNeo(slotElement); //Save slots - through Ajax 
            } 
            else { // Deselect slot
                console.log("DelSlotAjaxNeo call")
                $(this).removeClass("selected");
                DelSlotAjaxNeo(slotElement); //Delete slots - through Ajax 
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
                url: "<?= base_url('/Doctor/getset_dr_slots_ajax_neo/') ?>",
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
        * @date: February 13th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function SaveSlotAjaxNeo(slotElement) {
            var slot = slotElement.text();
            //selectedSlots.push(slot);
            
            // Save into DB
            event.preventDefault(); // Prevent the form from submitting normally

            var selDtStr = JSON.stringify(currentDateYYYYMMDD);
            var formData = JSON.stringify(slot); //selected and de-selected slots array
            $("#preloader1").show();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Doctor/SaveSlotAjaxNeo/') ?>" + currentDateYYYYMMDD,
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
        * @date: February 13th, 2025
        * @author: Nearks Team
        * @modified:
        */
        function DelSlotAjaxNeo(slotElement) {
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
                url: "<?= base_url('/Doctor/DelSlotAjaxNeo/') ?>" + selDtStr,
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

        if (!valid) { e.preventDefault(); }
    }); //document.ready - Closed

    <?php //*************** PHP Code - START **************
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
    //*************** PHP Code - END **************
    ?>
   
</script>
    <!---Js file Include -->
    <?= view('Admin/js_file.php'); ?>
    <!---Js file Include -->
    <?= view('Doctor/date_calendar_js.php'); ?>
</body>

</html>