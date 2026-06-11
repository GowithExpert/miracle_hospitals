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
<!-- Views/Home/section/show_dr_available_slots.php -->
<!DOCTYPE html>
<html> 

<head>
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- <script src="script.js"></script> -->

    <?= view('Doctor/date_calendar_js.php'); 
    ?>
    <!----Css File Include --->
    <?= view('Home/css_file'); ?>
    <!----Css File Include --->
    <style>
        body{
            background: #fff !important;
        }
        #slotGrid{
            border: none !important;
        }
        #slotGridAjax{
            border: none !important;
        }
        #slotGridAjax_{
             border: none !important;
        }
        /*.top_margin{
            margin-top: 100px;
        }*/
        .form-container {
/*            padding: 15px;*/
            /* border-bottom: 1px solid #005197; */
            /* margin-top: -23px;*/
        }
        #form-container {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
           /* padding-top: 23px;*/
            font-size: 14px;
            font-family: serif;
            color: #616161;
        }
        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
            margin-left: 4px;
        }

        .profile_photo {
            /* width: 80px; */
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
            margin-left: 4px;
        }
        .doctor-name {
            font-size: 20px;
            font-weight: bold;
        }

        /* Media query for responsive design */
        @media (max-width: 600px) {
        .form-row {
            flex-direction: row;
            justify-content: flex-start;
        }
        .profile-photo {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }
        .doctor-name {
            font-size: 16px;
        }
        }
        .degre_div{
            width: 176px;
            padding: 10px;
            margin-left: 14px;
            margin-top: -45px;
            text-align: center;
            color: black;
            font-weight: 500;
            margin-bottom: 12px;
        }
        .head {
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        
        .content-container {
            background-color: #ffffff !important;
            padding: 34px !important;
            border-radius: 10px !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
            max-width: 1000px !important;
            width: 100% !important;
            margin: 20px auto !important;
        }
        /*.dr_container{
            background-color: #ffffff !important;
            padding: 11px !important;
            border-radius: 10px !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
            max-width: 1000px !important;
            width: 100% !important;
            margin: -3px auto !important;
        }*/
        .dr_container_1{
            background-color: #ffffff !important;
            padding: 11px !important;
            border-radius: 10px !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
            max-width: 1000px !important;
            width: 100% !important;
            margin: 20px auto !important;
        }
        .upr_header {
            height: 68px !important;
            text-indent: 25px !important;
        }
        #dr_div{
            width: 197px !important;
        }
        @media (max-width: 576px) {
            .content-container .dr_container {
                width: 95%; /* Adjust the width for small screens */
            }

            .content-container .upr_header .dr_container {
                height: 30px !important; /* Set smaller height for mobile view */
            }
        }
        @media (max-width: 820px) {
            .content-container .dr_container {
                max-width: 100%; /* Adjust to take full width on smaller screens */
            }
        }
        @media (max-width: 400px) {
            .content-container .dr_container {
                margin: 5px auto; /* Further adjust margin for small mobile screens */
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
        *::-webkit-scrollbar {
            width: 0.6em;
        }

        *::-webkit-scrollbar-thumb {
            background-color: #0DB69F;
        }
        select{
            display: block;
            padding: 21px !important;
        }
        /*.row .col{
            margin-left: -15px !important;
        }*/
        .row {
            margin-left: auto;
            margin-right: auto;
            /* margin-bottom: -25px !important; */
        }
        .slot-grid{
            margin: 20px !important;
        }
        
        @media (max-width: 480px){
            .readmore_area a {
                font-size: 0.9em !important;
            }
            #dr_div{
                margin-left: 31px;
            }
            .top_margin {
                margin-top: 77px !important;
            }
        }
        @media (max-width: 1190px){
            .top_margin {
                margin-top: 150px !important;
            }
        }
        @media (max-width: 667px){
            .top_margin {
                margin-top: 77px !important;
            }
        }
        @media (max-width: 653px){
            .top_margin {
                margin-top: 0px !important;
            }
        }
        @media (max-width: 768px){
            .centered-button{
                font-size: 12px !important;
            }
            .dr_dgre{
                margin-left: 136px !important;
                margin-top: -70px;
            }
            .dr_spec{
                margin-left: 136px !important;
                margin-top: -15px;
            }
        }
        @media (max-width: 512px){
            .top_margin {
                margin-top: 90px !important;
            }
        }
        .centered-button{
            /* margin-bottom: 46px !important; */
            padding: 0px 0px !important;
            margin-bottom: 23px !important;
            margin-top: -23px !important;
        }
        .readmore_area a{
            /* margin: -3px 0px !important; */
        }
        /* .readmore_area a span{
            background: #005197 !important;
        } */
        .slot.selected{
            background: #0DB69F !important;
        }

    /*************************************************************************/
    /*.cntr {
      text-align: center;
      width: 50%;
    }

    #rspns_errtxt_msg {
        color: #fb6c6c;
        margin-bottom: 3px;
    }

    #rspns_successtxt_msg {
        color: #0DB69F;
        margin-bottom: 3px;
    }*/

    /************************************************************************ */
    .day-number {
        text-align: center;
    }
  /* body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
  } */
  .calendar-container {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    padding: 20px !important;
    background-color: #ffffff !important;
    border-radius: 10px !important;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2) !important;
  }
  .month-header {
    font-size: 24px !important;
    margin-bottom: 10px !important;
    cursor: pointer !important;
    color: #333 !important;
  }
  .calendar-header {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    /* margin-bottom: 10px !important; */
    margin-top: -17px;
    background-color: #0DB69F;
    margin-top: 0px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    height: 37px;
  }
  .calendar-days {
    display: flex !important;
    overflow-x: hidden !important;
    scroll-behavior: smooth !important;
    width: 100% !important;
    white-space: nowrap !important;
    text-align: center !important;
    background: #ededed !important;
  }
  .calendar-day {
    width: 46px !important;
    height: 56px !important;
    flex: 0 0 56px !important;
    padding: 10px !important;
    transition: background-color 0.2s !important;
    color: #616161;
    font-size: 14px;
    font-family: serif;
    border-right: 1px solid #53ddca;
  }
  .calendar-day:hover {
    background-color: #53ddca !important;
    cursor: pointer !important;
    color: #fff;
  }
  #prevDate, #nextDate {
    background-color: #ededed  !important;
    color: #0DB69F !important;
    border: none !important;
    padding: 5px 10px !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    transition: background-color 0.2s !important;
    /* flex: 0 0 30% !important; */
  }
  #prevDate:hover, #nextDate:hover {
    color: gray !important;
  }
  .dr_container{
    background-color: #ffffff !important;
    padding: 11px !important;
    border-radius: 10px !important;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
    max-width: 1000px !important;
    width: 100% !important;
    margin: 5px auto !important;
    }
  .custom-select {
    position: relative !important;
    /* display: inline-block !important; */
    width: 103px !important;
    text-align: center !important;
    color: #005197;
    justify-content: center;
    display: flex;
  }
  .custom-select .select-header {
    cursor: pointer !important;
    padding: 5px 10px !important;
    /* border: 1px solid #ccc; */
    background-color: #0DB69F !important;
    font-size: 16px;
    color: #fff;
    border: 1px solid #fff;
    border-radius: 18px !important;
    height: 30px;
    margin-top: 1px;
  }
  .custom-select .select-list {
    position: absolute !important;
    top: 100% !important;
    left: 0;
    width: auto !important;
    background-color: #0DB69F !important;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2) !important;
    /* border-radius: 5px !important; */
    display: none;
    z-index: 1 !important;
    max-height: auto !important;
    overflow-y: auto !important;
    color: #fff;
  }
  .custom-select .select-year-list{
    position: absolute !important;
    top: 100% !important;
    left: 27px;
    width: auto !important;
    background-color: #0DB69F !important;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2) !important;
    /* border-radius: 5px !important; */
    display: none;
    z-index: 1 !important;
    max-height: auto !important;
    overflow-y: auto !important;
    color: #fff;
  }
  .custom-select .select-item {
    padding: 5px 10px !important;
    cursor: pointer !important;
    transition: background-color 0.2s !important;
  }
  /* .custom-select .select-year-list:hover {
    background-color: #f0f0f0 !important;
    color: #005197;
  } */
  .custom-select .select-item:hover {
    background-color: #f0f0f0 !important;
    color: #0DB69F;
  }
  .calendar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: #ededed;
    }
    /* .day-name{
        font-size: 14px;
        font-family: serif;
        color: #616161;
    }*/
    .day-number{
        font-size: 14px;
    }
    .calendar-days .date:not(:first-child)::before {
        content: "";
        position: absolute;
        top: 5px;
        bottom: 5px;
        left: 0;
        width: 1px;
        background: #0082a8;
    }
    .calendar-day.selected {
        background-color: #0DB69F;
        color: #fff;
    }

    /***************************************************************************************** */
    /* .custom-select-container {
                position: relative;
                display: inline-block;
            } */
    
            .custom-select {
                position: absolute;
                width: 100%; /* Set to 100% for full width */
                cursor: pointer;
                z-index: 2; /* Ensure the dropdown is above other content */
            }
    
            .select-selected {
                /* padding: 10px; */
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
                color: #fff;
                cursor: pointer;
                font-size: 18px;
            }
    
            .select-items {
                display: none;
                position: absolute;
                width: 200px;
                max-height: auto; /* Adjust the max-height as needed */
                overflow-y: auto;
                z-index: 3; /* Set a higher z-index to display above other content */
                background-color: #0DB69F;
                color: #fff;
            }
    
            .select-item {
                padding: 2px 2px 2px 10px;
                cursor: pointer;
            }
    
            .select-item:hover {
                background-color: #f0f0f0; /* Optional: Hover background color */
                color: #0DB69F;
            }
            .disabled {
                color: #c9c9c9; /* Change the color to visually indicate that the year is disabled */
                cursor: not-allowed; /* Change the cursor to indicate it's not clickable */
            }
            .disabled-day {
                color: #ccc; /* Change the color to visually indicate that the day is disabled */
                pointer-events: none; /* Disable click events on disabled days */
            }
            .disabled:hover{
                color: darkgray !important;
            }
            /* Add this style for the default dropdown indicator */
            .select-header,.select-selected {
            display: flex;
            align-items: center;
            position: relative;
            justify-content: center;
            }
            .select-selected{
                display: flex;
                align-items: center;
                position: relative;
            }

            .select-header::after {
            content: '\2304'; /* Unicode character for a down arrow */
            position: relative;
            right: -11px; /* Adjust the position as needed */
            font-size: 22px;
            top: -23%;
            }
            .select-selected:after{
                content: '\2304'; /* Unicode character for a down arrow */
                position: relative;
                right: 0px; /* Adjust the position as needed */
                font-size: 22px;
                top: -23%;
            }
            .mrgn_lft_rght{
                margin-left: 0px;
                margin-right: 0px;
            }
            .brdr_bottom{
                border-bottom: 1px solid #53ddca;
                margin-left: -8px;
                margin-right: -8px;
                margin-bottom: 20px;
            }
            /* Add this style to adjust the position of the dropdown indicator */
            .txtlgn{
                display: flex;
                justify-content: center;
                /*color: #616161;*/ /* gray colour */
                color: #fb6c6c;
            }
            .disp-none{
                display:none;
            }
            .disp-block{
                display:block;
            }
            .brder_bottm{
                border-bottom: 1px solid #53ddca !important;
                margin-left: -8px;
                margin-right: -8px;
            }

            #succss_msg{
                padding: 10px;
                background: green;
                color: white;
                font-weight: 500;
                display: flex;
                position: absolute;
                width: 100%;
                z-index: 999;
             }

             #error_msg {
                padding: 10px;
                background: red;
                color: white;
                font-weight: 500;
                display: flex;
                position: absolute;
                width: 100%;
                z-index: 999;
             }
            

             

            #cnf_msg{
                position: absolute !important;
                margin-top: 91px !important;
                width: 100% !important;
                z-index: 999 !important;
            }
            .hidden{
                display: none;
            }
            .select_content{
                padding: 4px !important;
                border-radius: 18px;
                background: #0DB69F;
                border: 1px solid #fff;
                color: #fff;
                font-size: 16px;
            }
            
            #customMessageContainer {.dr_dgre{
                margin-left: 123px;
                margin-top: -70px;
            }

            .dr_spec{
                margin-left: 123px;   /****123 */
                margin-top: -15px;
            }
                width: 100% !important;
                box-sizing: border-box;
                display: inline-block; /* Ensure it behaves as a block element */
            }

            .dr_container.calendar-container {
                position: relative; /* Ensure relative positioning for absolutely positioned children */
            }

            #customMessage {
                background-color: green;
                color: #fff;
                padding: 10px;
                margin: -11px;
                position: absolute;
                z-index: 999;
                width: 100%; /* Ensure the width matches its parent */
            }
            .dr_dgre{
                margin-left: 123px;
                margin-top: -70px;
            }
            .dr_spec{
                margin-left: 123px;
                margin-top: -15px;
            }
            .dr_slots_avail{
                margin-top: 15px;
                margin-bottom: -8px;
            }
            #dr_div_ajax {
                width: 176px;
                /* height: 37px; */
                padding: 10px;
                /* background: #1FB89C; */
                margin-left: 14px;
                /*margin-top: -45px;*/
                text-align: left;
                color: black;
                font-weight: 500;
                margin-bottom: 12px;
                font-size: 19px;
            }
            /*#ajx_eror_msg{
                background-color: red;
                color: #fff;
                padding: 10px;
                margin: -11px;
                position: absolute;
                z-index: 999;
                width: 100
            }*/


            .custm_err_mssg_styl{
                position: absolute;
                background: red;
                color: #fff;
                z-index: 999;
                padding: 5px;
                margin-top: 62px;
                width: 72%;
            }

    </style>
</head>

<body> <!---Body Section Start --->
    <!---Top Bar Section Include -->
    <?= view('Home/nav_bar'); ?>
    <!---Top Bar Section Include -->

    <div class="top_margin">
        <div class="head">
            <!-- <div class="content-container upr_div"> -->
                    <!---Php Meassge Show -START --->
                    <div style="position: relative;">
                        <?php if (session()->getTempdata('success')) : ?>
                            <div class="card success-message cutom_messge_styl">
                                <div class="card-content" id="succss_msg"><?= session()->getTempdata('success'); ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getTempdata('error')) : ?>
                            <div class="card error-message cutom_messge_styl">
                                <div class="card-content" id="error_msg"><?= session()->getTempdata('error'); ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!---Php Meassge Show -END --->
                </div>
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
                                    if ($doc->id == $slots->doctor_id) {
                                        $slots->slot_start = substr($slots->slot_start, 0, -3);
                                        $slots->slot_end = substr($slots->slot_end, 0, -3);
                                        $newSlotsArr[$doc->id][] = $slots;
                                    } 
                                endforeach; //Arrage Doctor Slots - END ?>
                                <option value="<?= $doc->id ?>"><?= $doc->doctor_name ?></option>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <option value="">No doctor available</option>
                        <?php endif; //Doctor Drop-down - START ?>
                    </select> <!-- rap -->
            </div>
            <div class="calendar-content">
                <button id="prevDate"><i class="fa fa-angle-double-left" style="font-size:24px"></i></button>
                <div class="calendar-days custom-select-container" id="calendarDays">
                <!-- Days will be added here -->
                </div>
                <button id="nextDate"><i class="fa fa-angle-double-right" style="font-size:24px"></i></button>
            </div>
        </div>
        <div class="row cntr">
            <div id="rspns_errtxt_msg"></div>      <!-- Show slot's error message --> 
            <div id="rspns_successtxt_msg"></div>  <!-- Show slot's success message -->
        </div>
    <div class="dr_container" id='slotGrid'>
        <div class="form-container doctor-{doctor_id}">
        <?php 
        if(!empty($doctors)) {
            //echo "<pre>";print_r($doctors);die;
            foreach($doctors as $doc) { ?>
                <div class="row <?php echo (empty($newSlotsArr[$doc->id])) ? 'brdr_bottom' : 'dr_slots_avail'; ?>" id="slotGridRow<?php echo $doc->id;?>">
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
                                <div id='dr_div'><?= $doc->doctor_name ;?></div>
                            <?php } ?>
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
                                <div class="readmore_area">
                                    <button class="centered-button pickslot" name="pickslot" data-dr_id="<?php echo $doc->id; ?>" data-dr_name="<?php echo $doc->doctor_name; ?>" id="pickslot<?php echo $doc->id; ?>" type="submit"><a
                                            data-hover="Book Appointment"><span id="bookapt<?php echo $doc->id; ?>">Book Appointment</span></a></button>
                                    <div class="slot-grid mrgn_lft_rght txtlgn disp-none" id="doc-slot-msg-<?php echo $doc->id; ?>">
                                            Doctor slots are not available! 
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Draw Slots - START -->
                <?php if(!empty($newSlotsArr[$doc->id])) {  ?>
                <form id="slotfrm<?php echo $doc->id; ?>" class="brder_bottm" name="slotfrm" action="" method="post">
                    <div class="slot-grid mrgn_lft_rght">
                        <!--Selection /De-Selection Slots - START -->
                        <?php  foreach($newSlotsArr[$doc->id] as $slots){ ?> 
                            <div id="shwgrid" onclick="toggleSlotSelectionNew($(this),'<?php echo $doc->id;?>','<?php echo $doc->id;?>');" class="slot"><?php echo $slots->slot_start.'--'.$slots->slot_end; ?> <span hidden=""><?php echo $slots->id; ?></span></div>
                        <?php } ?> <!--Selection /De-Selection Slots - END -->
                    </div>
                </form>
                <?php } else { ?>
                    <script>
                        var doc_id='<?php echo $doc->id;?>';
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

    <!-------   Ajax Based : Slots On Doctor Selection - START -->
    <?php 
    // if(isset($dr_slots)) { 
    //     //foreach ($doctors as $key => $doc) {
    //     for($r = 0; $r < count($dr_slots); $r++ ) {
    //         if($r == 0) { $dr_id = $dr_slots[$r]->doctor_id; }
    //         else if( $r > 0){ i
    //             if($)
    //         }
    //          // echo $slots->slot_start .'--'. $slots->slot_end;
    //          // echo "<br/><br/>";
    //          // echo $slots->doctor_id;
    //          // echo "<br/><br/>";
        ?>
    
    <?php
        // // Initialize an empty array to hold grouped results
        // $groupedSlots = [];

        // // Loop through each item in the data
        // foreach ($dr_slots as $doc) {
        //     //echo "<pre>"; print_r($doc);die;
        //     $doctorId = $doc->doctor_id;
        //     $slotStart = $doc->slot_start;
        //     $slotEnd = $doc->slot_end;

        //     // If doctor_id doesn't exist in the grouped array, create an entry
        //     if (!isset($groupedSlots[$doctorId])) {
        //         $groupedSlots[$doctorId] = [
        //             'doctor_name' => $doc->doctor_name,
        //             'education' => $doc->education,
        //             'dr_specialization' => $doc->dr_specialization,
        //             // 'education' => $doc->education,
        //             // 'education' => $doc->education,
        //             // 'education' => $doc->education,
        //             'slots' => []
        //         ];

        //         //////////////////
        //         // $groupedSlots[$doctorId] = new \stdClass; //stdClass object
        //         // //$groupedSlots[$doctorId]->id = $doc->doctor_id;
        //         // $groupedSlots[$doctorId]->doctor_name = $doc->doctor_name;
        //         // $groupedSlots[$doctorId]->education = $doc->education;
        //         // $groupedSlots[$doctorId]->dr_specialization = $doc->dr_specialization;
        //         // $groupedSlots[$doctorId]->slots = [];
        //         /////////////////
        //     }

        //     // Add the current slot to the doctor group
        //     $groupedSlots[$doctorId]['slots'][] = [
        //         'slot_start' => $slotStart,
        //         'slot_end' => $slotEnd
        //     ];
        //     // $groupedSlots[$doctorId]->slots =[
        //     //     'slot_start'    => $doc->slot_start,
        //     //     'slot_start'    => $doc->slot_end,
        //     // ];
        // }

        // // Output the result
        // echo '<pre>';
        // print_r($groupedSlots);
        // echo '</pre>';
        // //}?>

    <div class="dr_container empytslot" id='slotGridAjax'> <!-- Populate Slot Grid - START -->
        <div class="form-container doctor-{doctor_id}">
            <!-- <div class="row 'brdr_bottom' : 'dr_slots_avail'; ?>" id="slotGridRow"> -->
            <div class="row brdr_bottom dr_slots_avail" id="slotGridRow">
                <div class="col lg-6 col-md-6 col-sm-12">
                    <div class="form-row" id="profile_pic_ajax">
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

                        <div class="doctorInfo"> <!--Pupulate Dr. Details - START -->
                                <div id='dr_div_ajax'></div> 
                        </div> <!--Pupulate Dr. Details - START -->
                    </div>
                    <div class="form-row dr_dgre" id="education_ajax">
                    <?= $doc->education ;?>
                    </div>
                    <div class="form-row dr_spec" id="dr_specialization_ajax">
                    <?= $doc->dr_specialization ;?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div id="bts" class='container'> <!-- Appointment - Button - START -->
                        <div class='row'>
                            <div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
                                <div class="readmore_area">
                                    <button class="centered-button" name="pickslot" id="pickslot_ajax" type="submit"><a
                                            data-hover="Book Appointment"><span>Book Appointment</span></a></button>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Appointment - Button - END -->

                </div>
            </div>
        </div>
    </div> <!-- Populate Slot Grid - END -->
    <?php// } ?>
    <!-------   Ajax Based : Selected Doctor Slots - END -->

    <!-- Repeated div, need to remove once show dynamic data div -->        
    <script>
        $('#slotGridAjax').hide();
        var selectedDate = "<?= date('Y-m-d'); ?>";
        var newSlotElem;
        var newpick_slt='';
        var newpick_slt_id='';
        let valid = true;
        $(document).ready(function() {//To get the success message form the appointment page
           //Sucess Failure message - START
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            var success_msg = urlParams.get('success_msg');

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
            //else { } ////Sucess Failure message - END

           
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
                                        slotData.forEach(function (slot) {
                                            slot.slot_start = slot.slot_start.slice(0, -3);
                                            slot.slot_end = slot.slot_end.slice(0, -3);
                                            from_to_slot.push(slot.slot_start + '--' + slot.slot_end);
                                            dr_name = slot.doctor_name;
                                        });
                                        $('#rspns_errtxt_msg').hide();
                                        $('#rspns_successtxt_msg').html(jsonResponse.message).show();
                                        $('#dr_div_ajax').text(dr_name);
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

                selectDate(); //called - date selection function
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
            function populateSlotGridNeo(sltArr) {
                var frm = $("<form></form>");
                frm.attr('id', 'slotfrm');
                frm.attr('name', 'slotfrm');
                frm.attr('action', '');
                frm.attr('method', 'post')
                
                var slotGrid = $("#slotGridAjax").append(frm);//.appendTo(frm);

                // Replace with your logic to retrieve available slots from the server/database
                var availableSlots = sltArr; //getAvailableSlots();
                
                console.log("Json Stringfy data" + JSON.stringify(availableSlots, null, 2));
                if (availableSlots.length > 0) {
                    var slotGridElement = $("<div>").addClass("slot-grid").appendTo(frm);
                    availableSlots.forEach(function (slot) {
                       // console.log(slot);
                        var slotElement = $("<div>").addClass("slot");
                        var from_to_slots = slot.slot_start + '--' + slot.slot_end + ' ';
                        var hidespan = $("<span>", { "hidden": true, text: slot.id });
                        slotElement.append(from_to_slots, hidespan); // Append both content and hidden span
                        slotElement.appendTo(slotGridElement);
                        slotElement.on("click", function () {
                            toggleSlotSelectionNew($(this));
                        });
                    });
                    $('#slotGridAjax').show();
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_errtxt_msg').hide();
                    $('#rspns_successtxt_msg').html(jsonResponse.message).show();
                }
                else {
                    console.log("You are here");
                    $('#rspns_errtxt_msg').html(jsonResponse.message).show(); 
                    $('#slotGrid').hide();
                    $('.slot-grid').hide();
                    $('#slotGridAjax').hide();
                }
            } //function - Closed



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
        

           /* @params: Required parameter is booking_slot_arr with value 
            * select "Slot", "Doctor ID", "Doctor Name" & "Date"
            * @desc: The function execute when click on the Book Appointment 
            * Now button by opting desired Dr. available slot
            * @use: By patient for booking Doctor available slots
            * @author: Neoarks Team
            * @date: June 15th,  2023
            * @modify:
            */    
            $(".pickslot").on("click", function() { //Pickup Slot
                var selected_dr_id = $(this).data("dr_id");
                var doc_name = $(this).data("dr_name");
                newpick_slt_id=$.trim(newpick_slt_id,'\n');
                //if(typeof newpick_slt=='undefined' || typeof newpick_slt_id==''){
                if (typeof newpick_slt === 'undefined' || newpick_slt_id.length === 0) {  
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
        function toggleSlotSelectionNew(slotElement) {
            console.log(slotElement);
            newSlotElem = slotElement;
            //slotElement_neo = slotElement; //So that slotElement may not match with the same variable in the above loop
            slotElement.toggleClass('active'); //Also work if replace it with //slotElement_neo.toggleClass('slotElement_neo');
            slotElement.toggleClass("selected");
            $('.slot').click(function() {
                if (slotElement) {
                    slotElement.removeClass('selected'); //Remove previous selection of slots, if any
                }
                $(this).addClass('selected'); //Mark slot as selected
                slotElement = $(this);
            });
            var doc_name = $('#dr_appoint option:selected').text(); //Get selected Dr. name
            var pick_slt = slotElement.text();
            if (typeof(pick_slt) !== 'undefined' && pick_slt !== null) { //Likewise isset in php
                var delimiter = / /; //Space is must for Blank delemeter, equivalent to " "
                //Split the string at the match
                var split_pick_slt_arr = pick_slt.split(delimiter);
                if (typeof(split_pick_slt_arr[1]) !== 'undefined' && split_pick_slt_arr[1] !== null) { //Likewise isset in php
                    newpick_slt=split_pick_slt_arr[0];
                    newpick_slt_id=split_pick_slt_arr[1];
                    console.log(newpick_slt);      // Slot
                    console.log(newpick_slt_id);  //Slot ID
                    
                } 
                else {
                    newpick_slt='';
                    newpick_slt_id='';
                    console.log("Picked slot ID is missing");
                    valid = false;
                }
            } 
            else {
                newpick_slt='';
                newpick_slt_id='';
                console.log("Picked slot may not blank");
                valid = false;
            }
            if (!valid) {
                e.preventDefault();
            }
        } //document.read - Closed
    
   
    </script>
        <!---Body Section Start --->
        <?= view('Home/footer_section'); ?>
        <!---Js file Include -->
        <?= view('Admin/js_file.php'); ?>
        <!---Js file Include -->
        <?//= view('Doctor/date_calendar_js.php'); ?>
</body>
</html>