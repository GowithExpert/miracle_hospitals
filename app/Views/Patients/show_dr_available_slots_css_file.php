<style>
        body{
            background: #fff !important;
        }
        #slotGrid{
            border: none !important;
        }
        .top_margin{
            margin-top: 0px;
        }
        .form-container {
            padding: 20px;
            /* border-bottom: 1px solid #005197; */
            margin-top: -23px;
        }
        .back_blu{
            background: #005197;
        }
        .doc_edu{
            margin-left: 123px;
            margin-top: -70px;
        }
        .doc_spec{
            margin-left: 123px;
            margin-top: -15px;
        }
        .fnt_24{
            font-size: 24px !important;
        }
        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
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
            /*margin-left: 4px;*/
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
        .dr_container{
            background-color: #ffffff !important;
            padding: 11px !important;
            border-radius: 10px !important;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
            max-width: 1000px !important;
            width: 100% !important;
            margin: -3px auto !important;
        }
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
        /*#dr_div{
            width: 197px !important;
        }*/
        #dr_div {
            width: 176px;
            /* height: 37px; */
            padding: 10px;
            /* background: #1FB89C; */
            margin-left: 14px;
            margin-top: -45px;
            text-align: left;
            color: black;
            font-weight: 500;
            margin-bottom: 12px;
            font-size: 19px;
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
            background-color: #005197;
        }
        select{
            display: block;
            padding: 21px !important;
        }
        .row .col{
            margin-left: -15px !important;
        }
        .row {
            margin-left: auto;
            margin-right: auto;
            /* margin-bottom: -25px !important; */
        }
        .slot-grid{
            margin-top: 20px !important;
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
                /* margin-top: 150px !important; */
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
        @media (max-width: 512px){
            .top_margin {
                margin-top: 0px !important;
            }
        }
        /*.reltiv{
            position: relative;
        }*/
        .readmore_area a::before{
            color: #005197 !important
        }
        .centered-button{
            margin-bottom: 46px !important;
            padding: 0px 0px !important;
        }
        .readmore_area a{
            border: 2px solid #005197 !important;
        }
        .readmore_area a{
            margin: -3px 0px !important;
        }
        .readmore_area a span{
            background: #005197 !important;
        }
        .slot.selected{
            background: #005197 !important;
        }



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

  .profile_photo {
            /* width: 80px; */
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 20px;
            margin-left: 4px;
        }
  .calendar-header {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    /* margin-bottom: 10px !important; */
    margin-top: -17px;
    background-color: #005197;
    margin-top: 0px;
    border-top-right-radius: 10px;
    border-top-left-radius: 10px;
    height: 40px !important;
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
    /* background-color: #f4f4f4 !important;
    border: 1px solid #ccc !important; */
    /* margin-right: 5px; */
    /* border-radius: 5px !important; */
    transition: background-color 0.2s !important;
    color: #616161;
    font-size: 14px;
    font-family: serif;
    border-right: 1px solid #005197;
  }
  .calendar-day:hover {
    background-color: #005197 !important;
    cursor: pointer !important;
    color: #fff;
  }
  #prevDate, #nextDate {
    background-color: #ededed  !important;
    color: #005197 !important;
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
    height: 30px !important;
    border: 1px solid #fff !important;
    cursor: pointer !important;
    padding: 5px 10px !important;
    /* border: 1px solid #ccc; */
    border-radius: 18px !important;
    background-color: #005197 !important;
    font-size: 16px;
    color: #fff;
  }
  .custom-select .select-list {
    position: absolute !important;
    top: 100% !important;
    left: 0;
    width: auto !important;
    background-color: #005197 !important;
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
    background-color: #005197 !important;
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
    color: #005197;
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
        background-color: #005197;
        color: #fff;
    }

    /***************************************************************************************** */
    .custom-select-container {
                position: relative;
                display: inline-block;
            }
    
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
                background-color: #005197;
                color: #fff;
            }
    
            .select-item {
                padding: 2px 2px 2px 10px;
                cursor: pointer;
            }
    
            .select-item:hover {
                background-color: #f0f0f0; /* Optional: Hover background color */
                color: #005197;
            }
            .disabled {
                color: darkgray; /* Change the color to visually indicate that the year is disabled */
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
            top: -23% !important;
            }
            .select-selected:after{
                content: '\2304'; /* Unicode character for a down arrow */
                position: relative;
                right: 0px; /* Adjust the position as needed */
                font-size: 13px;
            }
            .mrgn_lft_rght{
                margin-left: 0px;
                margin-right: 0px;
            }
            .brdr_bottom{
                border-bottom: 1px solid #005197;
                margin-left: -8px;
                margin-right: -8px;
                margin-top: 12px;
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
            /* Add this style to adjust the position of the dropdown indicator */
            .txtlgn{
                display: flex;
                justify-content: center;
                color: #fb6c6c;
            }
            .dr_dgre{
                margin-left: 123px;
                margin-top: -70px;
            }
            .dr_spec{
                margin-left: 123px;
                margin-top: -15px;
            }
            .disp-none{
                display:none;
            }
            .disp-block{
                display:block;
            }
            .brder_bottm{
                border-bottom: 1px solid #005197 !important;
                margin-left: -8px;
                margin-right: -8px;
            }
            .select_content{
                width: 17% !important;
                height: 32px !important;
                padding: 4px !important;
                border-radius: 18px;
                background: #005197;
                border: 1px solid #fff;
                color: #fff;
                font-size: 16px;
            }
            @media (max-width: 767px){
                .select_content{
                    width: 40% !important;
                }
            }
            img{
                display: block;
            }
            #customMessageContainer {
                width: 100% !important;
                box-sizing: border-box;
                display: inline-block; /* Ensure it behaves as a block element */
            }

            .dr_container.calendar-container {
                position: relative; /* Ensure relative positioning for absolutely positioned children */
            }
            .dr_slots_avail {
                margin-top: 15px;
                margin-bottom: -15px;
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

            #dr_div_ajax {
                width: 176px;
                /* height: 37px; */
                padding: 10px;
                /* background: #1FB89C; */
                margin-left: 14px;
                margin-top: -45px;
                text-align: left;
                color: black;
                font-weight: 500;
                margin-bottom: 12px;
                font-size: 19px;
            }
    </style>