<style type="text/css">
    table tr td {
        font-weight: 500;
        font-size: 15px;
    }
    h6 {
        font-weight: 500
    }

    #slotGrid {
        width: auto;
        margin: auto;
        border: 1px solid #fff !important;
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
        background-color: #337ab7 !important;
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
        
        text-align: center;
    }

    button {
        font-size: 14px;
        background: #005197;
        color: #fff;
        border: 2px solid #0b72cb;
        padding: 10px 20px;
        margin: 1rem;
        position: relative;
        z-index: 1;
        overflow: hidden;
        text-transform: uppercase;
        font-family: 'Habibi', serif;
        cursor: pointer;
    }
    .back_bdy{
        background: #fff;
    }
    button:hover {
        color: #005197 !important;
    }

    button:focus {
        background: #005197 !important;
    }

    button::after {
        content: "";
        background: #ededed;
        position: absolute;
        z-index: -1;
        padding: 0.85em 0.75em;
        display: block;

    }

    *::-webkit-scrollbar-thumb {
        background-color: #005197 !important;
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

    .upr_header {
        background: #fff !important;
        height: 29px !important;
    }

    input[type=date]:not(.browser-default) {
        margin: 0 0 0px 0;
    }

    .upr_div {
        margin: auto;
        width: auto;
        
        background-color: #fff !important;
        border: none !important;
    }

    #pgmsg {
        color: green;
        background-color: #fff;
        
        font-weight: 600;
        font-size: x-large;
        
    }

    #errorMessage {
        color: red;
        background-color: #fff;
        font-weight: 600;
        font-size: x-large;

    }

    h3 {
        font-size: 36px;
        font-weight: 600;
    }

    .dr_container {
        background-color: #ffffff !important;
        padding: 11px !important;
        border-radius: 10px !important;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1) !important;
        max-width: 1000px !important;
        width: 100% !important;
        margin: -3px auto !important;
    }


    /************************************************************************ */
    .day-number {
        text-align: center;
    }

    
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
        height: 37px;
        margin-top: -17px;
        background-color: #337ab7;
        margin-top: 0px;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
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
        flex: 0 0 56px;
        padding: 10px !important;
        
        transition: background-color 0.2s !important;
        color: #616161;
        font-size: 14px;
        font-family: serif;
        border-right: 1px solid #0082a8;
    }

    .calendar-day:hover {
        background-color: #337ab7 !important;
        cursor: pointer !important;
        color: #fff;
    }

    #prevDate,
    #nextDate {
        background-color: #ededed !important;
        color: #005197 !important;
        border: none !important;
        padding: 5px 10px !important;
        border-radius: 5px !important;
        cursor: pointer !important;
        transition: background-color 0.2s !important;
        
    }

    #prevDate:hover,
    #nextDate:hover {
        color: gray !important;
    }

    .dr_container {
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
        display: flex !important;
        width: 103px !important;
        text-align: center !important;
        color: #005197;
        justify-content: center;
    }

    .custom-select .select-header {
        cursor: pointer !important;
        padding: 5px 10px !important;
        border-radius: 18px !important;
        background-color: #337ab7 !important;
        font-size: 16px;
        color: #fff;
        border: 1px solid #fff !important;
        height: 30px !important;
        margin-top: 1px !important;
    }

    .custom-select .select-list {
        position: absolute !important;
        top: 100% !important;
        left: 0;
        width: auto !important;
        background-color: #337ab7 !important;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2) !important;
        
        display: none;
        z-index: 1 !important;
        max-height: auto !important;
        overflow-y: auto !important;
        color: #fff;
    }

    .custom-select .select-year-list {
        position: absolute !important;
        top: 100% !important;
        left: 27px;
        width: auto !important;
        background-color: #337ab7 !important;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2) !important;
        
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

    
    .day-number {
        font-size: 18px;
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
        background-color: #337ab7;
        color: #fff;
    }

    .select-header,
    .select-selected {
        display: flex;
        align-items: center;
        position: relative;
        justify-content: center;
    }

    .select-selected {
        display: flex;
        align-items: center;
        position: relative;
    }

    .select-header::after {
        content: '\2304';
        position: relative;
        right: -4px;
        font-size: 22px;
        top: -23%;
    }

    .select-selected:after {
        content: '\25BC';
        /* Unicode character for a down arrow */
        position: relative;
        right: 0px;
        /* Adjust the position as needed */
        font-size: 13px;
    }

    .disabled {
        color: darkgray;
        /* Change the color to visually indicate that the year is disabled */
        cursor: not-allowed;
        /* Change the cursor to indicate it's not clickable */
    }

    .disabled-day {
        color: #ccc;
        /* Change the color to visually indicate that the day is disabled */
        pointer-events: none;
        /* Disable click events on disabled days */
    }

    .disabled:hover {
        color: darkgray !important;
    }

    @media (max-width: 768px) {
        .calendar-day {
            flex: 0 0 58px !important;
        }
    }

    @media (max-width: 2134px) {
        .calendar-day {
            flex: 0 0 54px !important;
        }
    }

    #preloader1,
    #pgmsg,
    #errorMessage {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    </style>