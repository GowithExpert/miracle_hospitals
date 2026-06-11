<style type="text/css">
    #input_file {
        border: 1px solid silver;
        padding: 6px !important;
        width: 100%;
        margin-bottom: 15px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 3px;
        height: 40px;
    }
    a:focus{
        color: #fff !important;
    }

    select {
        display: block;
        border: 1px solid silver;
        box-shadow: none;
        box-sizing: border-box;
        padding-left: 10px;
        padding-right: 10px;
        height: 40px;
        border-radius: 3px;
        width: 100%
    }

    textarea {
        border: 1px solid silver;
        padding: 10px;
        outline: none;
        height: 100px;
        resize: none;
        border-radius: 3px;
    }

    .input-container {
        position: relative;
    }

    h6 {
        font-weight: 600;
        font-size: 15px;
    }

    #profile_pic {
        width: 40px;
        height: 40px !important;
        border-radius: 100%;
        border: 1px solid silver;
    }

    body {
        background: rgb(224, 227, 231)
    }

    .colour_hver:hover {
        color: blue;
    }

    #input_box {
        border: 1px solid silver;
        box-shadow: none;
        box-sizing: border-box;
        padding-left: 10px;
        padding-right: 10px;
        height: 40px;
        border-radius: 3px;
        color: white
    }

    .event {
        pointer-events: none !important;
    }

    a {
        font-size: 11px !important;
    }

    img.responsive-img,
    video.responsive-video {
        max-width: 100%;
        height: 203px !important;
    }

    .action_dropdown {
        width: 167px !important;
    }

    .down {
        min-width: 200px !important;
        left: 615.094px !important;
    }

    #search_doctor li:first-child {
        width: 250px;
    }

    #search_doctor {
        display: flex;
    }

    select {
        display: block !important;
    }

    .drsercharea {
        margin-top: 15px;
    }

    .tooltip {
        position: relative;
        display: inline-block;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 90px;
        background-color: #005197;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        top: 100%;
        left: 50%;
        margin-left: -45px;

        /* Fade in tooltip - takes 1 second to go from 0% to 100% opac: */
        opacity: 0;
        transition: opacity 1s;
    }

    #pagination nav {
        background: none;
        box-shadow: none;
    }

    .pagination li.active {
        background: none;
    }

    .pagination li.active a {
        color: white !important;
        background: #005197 !important;
    }

    .pagination a {
        color: black;
        font-weight: 500;
        border: 1px solid #005197;
        padding: 2px 5px;
        margin-left: 2px;
        border-radius: 3px;
    }

    .pagination li a {
        color: #005197;
    }

    .pagination li a:focus {
        color: #005197 !important;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    h5 {
        font-weight: 600;
        margin-top: 5px;
        font-size: 20px;
    }

    a:hover {
        color: #fff;
    }

    .iti {
        width: 100%;
    }

    .asterisk-symbol {
        position: absolute;
        top: 36%;
        left: 6px;
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

    .depart_widh {
        width: 400px !important;
        text-align: justify !important;
    }

    .break-word {
        overflow-wrap: normal;
        /* Prevent breaking of words */
    }

    .text-container {
        font-size: 13px !important;
    }

    /* Media query for mobile view */
    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
        }

        .text-container {
            white-space: normal;
            /* word-wrap: break-word !important; */
            overflow-wrap: break-word !important;
            word-break: break-all !important;

        }

        .table {
            width: 100%;
            display: block;
            white-space: nowrap;
        }

        .table th,
        .table td {
            min-width: 150px;
            /* display: inline-block; */
            padding: 8px;
        }

        .scroll-container {
            overflow-x: auto;
            white-space: nowrap;
        }

        .txt_break-at300 {
            width: 150px !important;
        }
    }

    @media (max-width: 1115px) {

        td,
        th {
            font-size: 14px !important;
        }

        button {
            font-size: 13px !important;
        }
    }

    @media (max-width: 1081px) {
        button {
            font-size: 10px !important;
        }
    }

    td,
    th {
        padding: 15px 3px !important;
    }

    .btn_hver1:hover {
        color: blue !important;
    }

    .btn_hver1:focus {
        color: #039be5 !important;
    }

    .btn-flat:hover {
        background: #fff;
        color: #333;
    }

    .btn-flat:focus {
        color: #333 !important;
        background: #fff !important;
    }

    input {
        color: black !important;
    }

    .btn_hver:hover {
        color: #fff;
    }

    .scrl_align {
        padding: 24px !important;
        background-color: #fff;
    }

    button:focus {
        border: none !important;
        color: #fff !important;
    }

    #search_donors {
        display: flex;
    }

    #search_donors li:first-child {
        width: 250px
    }

    tr td {
        font-weight: 500;
        font-size: 14px;
    }
</style>