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
    <title>Doctor's Availability Slots</title>
    <!---Include Css File --->
    <?//= view('Admin/css_file.php'); ?>
    <!---CSS File Include -->
    <?= view('Admin/custom_css_file.php'); ?>
    <!---Include Css File --->
    <style type="text/css">
        h6 {
            font-weight: 500
        }
    </style>
    <!---Topbar Section Include --->
    <style type="text/css">
        table tr td {
            font-weight: 500;
            font-size: 15px;
        }

        #slotGrid {
            width: 1200px;
            margin: auto;
            border: 1px solid #005197;
            /*#6c757d*/
        }

        .slot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            grid-gap: 10px;
            margin-bottom: 12px;
            margin-left: 12px;
            margin-right: 12px;
            margin-top: 12px;
        }

        .slot {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            border: 1px solid #337ab7;
            cursor: pointer;
        }

        .slot.selected {
            background-color: #2770af;
            color: #fff;
        }

        /* Custom CSS */
        table {
            font-family: 'Times New Roman', Times, serif;
            border-collapse: collapse;
            width: 100%;
            background-color: #209983;
        }

        td,
        th {
            border: 1px solid #005197;
            text-align: center;
            font-weight: none;
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        tr:nth-child(even) {
            /*background-color: #eeeeee;*/
            text-align: center;
        }

        button {
            font-size: 14px;
            background: #005197;
            color: #fff;
            border: 2px solid #005197;
            padding: 10px 20px;
            margin: 1rem;
            position: relative;
            z-index: 1;
            overflow: hidden;
            text-transform: uppercase;
            font-family: 'Habibi', serif;
            margin-left: 45%;
            cursor: pointer;
        }

        button:hover {
            color: #0DB69F;
        }

        button::after {
            content: "";
            background: #fff;
            position: absolute;
            z-index: -1;
            padding: 0.85em 0.75em;
            display: block;

        }

        button[class^="slide"]::after {
            transition: all 0.35s;
        }

        button[class^="slide"]:hover::after {
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            transition: all 0.35s;

        }

        button.slide_from_left::after {
            top: 0;
            bottom: 0;
            left: -100%;
            right: 100%;
        }

        .btn_allign {
            margin: auto;
            width: 1200px;
        }

        .header_tbl {
            border: 1px solid #9d9d9d;
            text-indent: 4px;
            width: 50%;
            font-size: 12px;
            height: 25px;
            border-radius: 5px;
            background: #fff;
        }

        .option_div {
            border: 1px solid #9d9d9d;
            width: 100%;
            font-size: 12px;
            height: 27px;
            margin-bottom: 9px;
            border-radius: 5px;
            background: #fff;
        }

        .upr_header {
            border: 1px solid #005197 !important;
            border-radius: 5px !important;
            outline: none !important;
            height: 25px !important;
            width: 51% !important;
            font-size: 12px !important;
            margin: 0 0 -2px 0 !important;
            background-color: #C7F5F1 !important;
            text-indent: 5px !important;

        }

        .upr_div {
            margin: auto;
            width: 1200px;
            border: 1px solid #005197;
        }

        .upr_select {
            border: 1px solid #005197 !important;
            border-radius: 5px !important;
            outline: none !important;
            height: 28px !important;
            font-size: 12px !important;
            background-color: #C7F5F1 !important;
            text-indent: 5px !important;
        }

        #pgmsg {
            color: green;
            padding: 20px;
            text-align: center;
            font-weight: 500;
            font-size: x-large;
            margin-left: 344px;
            margin-right: 344px;

        }
    </style>
</head>
</head>

<body style="background: #fff !important">
    <!---Topbar Section Include --->
    <?= view('Blood_bank/Donor/top_bar'); ?>
    <!---Topbar Section Include --->

    <p id="pgmsg"></p>
    <h3 class="head_txt">Doctor's Availability Slots</h3>
    <div class="upr_div">

        <table>
            <tr>
                <th style='color: #fff;width: 25%'>Appointment Date
                    <input type='date' class="upr_header" name='dt_input' id='dt_input' class='header-tbl' value=<?php echo date('Y-m-d'); ?>>
                </th>
                <th style='color: #fff;width: 22%'>Available From   
                    <input class="upr_header" type='time'>
                </th>
                <th style='color: #fff;width: 20%'>Available To   
                    <input class="upr_header" type='time'>
                </th>
                <th style='color: #fff;width: 17%'>Slot
                    <input class="upr_header" style="width: 130px !important;" type='time'>
                </th>
                <th style='color: #fff;width: 20%'>
                    <select class="upr_select" name='dr_name' id='dr_name'>
                        <option selected disabled hidden>Select Doctor</option>
                        <option value=''>
                        </option>
                        <option value='Dr. Vaibhav'>Dr. Vaibhav</option>
                        <option value='Dr. Raj'>Dr. Raj Khurana</option>
                        <option value='Dr. Varun'>Dr. Varun</option>
                    </select>
                </th>
            </tr>
        </table>
    </div>
    <!-- <div id='result'>type here</div> -->
    <div id='slotGrid'></div>
    <div class="btn_allign" class="wrapper">
        <button class='slide_from_left' style='margin-bottom: 13px; margin-top: 13px;' name='saveSlots' id='saveSlots'>Save slot</button>
        <p id="errorMessage"></p>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    
        $(document).ready(function() {
            console.log("Jquery started here");
            var currentDate = "<?= date('Y-m-d'); ?>";
            var selectedSlots = [];

            function populateSlotGrid() { //Drow and populate time slots
                var frm = $("<form></form>");
                frm.attr('id', 'slotfrm');
                frm.attr('name', 'slotfrm');
                //frm.attr('action', "<? //= base_url('Doctor/save_slots/')?>");
                frm.attr('method', 'post');

                var slotGrid = $("#slotGrid").append(frm); //.appendTo(frm);
               

                // Replace with your logic to retrieve available slots from the server/database
                var availableSlots = getAvailableSlots();

                if (availableSlots.length > 0) {
                    var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
                    // console.log(availableSlots);
                    availableSlots.forEach(function(slot) {
                        var slotElement = $("<div>");
                        if (slot.startsWith('1')) {
                            slot = slot.slice(1, ); //Sliced FIRST (dr_available: 1 or 0) to END
                            slotElement = slotElement.addClass("slot selected");
                        } else {
                            slot = slot.slice(1, ); //Sliced FIRST (dr_available: 1 or 0) to END
                            slotElement = slotElement.addClass("slot");
                        }
                        slotElement = slotElement.text(slot)
                            .appendTo(slotGridElement);
                        slotElement.on("click", function() {
                            toggleSlotSelection($(this));
                        });
                    });
                    var optiondv = $("<div>");
                    //optiondv = optiondv.addClass("fa fa-ellipsis-v").addClass("option-cls").appendTo(".slot");
                    optiondv = optiondv.addClass("<div>").addClass("option-cls").appendTo(".slot");
                } else {
                    slotGrid.text("No slots available.");
                }
            }

            function toggleSlotSelection(slotElement) {
                slotElement.toggleClass("selected");
                var slot = slotElement.text();

                if (slotElement.hasClass("selected")) { // Select slot
                    selectedSlots.push(slot);
                    console.log(selectedSlots);
                } else { // Deselect slot
                    var slotIndex = selectedSlots.indexOf(slot);
                    if (slotIndex !== -1) {
                        selectedSlots.splice(slotIndex, 1);
                    }
                    console.log(selectedSlots);
                }
            }

            // var selectedDate = currentDate;
            $('#dt_input').change(function() {
                selectedDate = $(this).val(); //Selected Date
                if (selectedDate) {
                    var formData = JSON.stringify(selectedDate); // Serialize form data 
                    $.ajax({
                        type: 'GET',
                        //url: '/miracle_hospitals/Doctor/get_dr_slots/' + selectedDate,
                        url: "<?= base_url('/Doctor/get_dr_slots/') ?>" + selectedDate,
                        data: formData,
                        dataType: 'text', //for 'string'
                        success: function(response) { // Handle the success response
                            //console.log(response);
                            //var jsonResponse = $.parseJSON(response);
                            console.log("non parse");
                            console.log(response);
                            <?php $dr_slots[0] ?> '' + '=' + response;
                            getAvailableSlots(); // Initial population of slot grid
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log(xhr);
                            console.log(textStatus);
                            //console.log(errorThrown);
                            //Handle the error
                        }
                    });
                }
                //else { return false; }
            });

            $("#saveSlots").on("click", function() { //Save into DB                
                //event.preventDefault(); // Prevent the form from submitting normally
                //var formData = $(this).serialize(); // Serialize form data

                var formData = JSON.stringify(selectedSlots); // Serialize form data 
                //console.log(typeof(formData));
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Doctor/save_slots') ?>", //+ queryString;
                    // url: '/miracle_hospitals/Doctor/save_slots',
                    data: formData,
                    //dataType: 'object',
                    dataType: 'text',
                    success: function(response) { // Handle the success 
                        var responseObj = JSON.parse(response);
                        if (responseObj.status) {
                            console.log(responseObj.message);
                            $('#pgmsg').text(responseObj.message);
                            setTimeout(function() {
                                $('#pgmsg').text("");
                            }, 3000);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log(textStatus);
                        //console.log(xhr);
                        //Handle the error
                        $('#pgmsg').text(textStatus);
                    }
                });
            });

            function getAvailableSlots() { // Replace with your logic to retrieve available slots from the server/database
                //Format:  return ["9:00 - 9:15", "9:15 - 9:30", "9:30 - 9:45"] - START
                var slots_arr = [];
                <?php
                if (isset($dr_slots)) { ?>
                    <?php
                    // if (isJson($dr_slots)) {
                    //     $dr_slots = json_decode($dr_slots);
                    // }
                    // else 
                    if (is_array($dr_slots)) { ?>
                        <?php
                        if (count($dr_slots) > 1) { ?>
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
                            //console.log(slots_arr);
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
                return slots_arr; //Format:  return ["9:00 - 9:15", "9:15 - 9:30", "9:30 - 9:45"] - END
            } //Function - Closed
            populateSlotGrid(); // Initial population of slot grid
        });
        <?php

        /* @param: Check is, json string or not?
         * @description:  
         * @use:
         * @date: 
         * @modify:
         * @author: Neoark's Team
         * @copyrights: Neoark Software Pvt Ltd
         */
        function isJSON($str)
        { //Return true for JSON else return `false`
            return is_string($str) && is_array(json_decode($str, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }

        function drow_merge_set_unset_slots()
        { ?>
            var slots_arr_neo = [];
            <?php $start = strtotime('00:00');
            $end = strtotime('24:00');
            $r = 0;
            for ($i = $start; $i <= $end; $i = $i + 15 * 60) {
                $r += 1;
                if ($r == 1) { //Start slot time
                    $odd_val = '';
                    $odd_val = date('H:i', $i); //Start slot time
                } else if ($r % 2 == 0) {
                    $even_val = '';
                    $even_val = date('H:i', $i);
                    $from_to_slot = $odd_val . '--' . $even_val;
            ?>
                    slots_arr_neo.push("<?= $from_to_slot ?>");
                <?php
                } else {
                    $odd_val = '';
                    $odd_val = date('H:i', $i);
                    $from_to_slot = $even_val . '--' . $odd_val;
                ?>
                    slots_arr_neo.push("<?= $from_to_slot ?>");
            <?php }
            } ?> //for - loop Closed
            console.log("Free & available slots merging here");
            console.log(slots_arr);
            $.each(slots_arr_neo, function(index, element) {
                if ($.inArray(element, slots_arr) !== -1) {
                    slots_arr_neo[index] = '1' + element; //Set available slots
                } else {
                    slots_arr_neo[index] = '0' + element; //Set available slots
                }
            });
            console.log(slots_arr_neo);
            return slots_arr_neo;
        <?php } ?> //function - Closed
    </script>
</body>

</html>