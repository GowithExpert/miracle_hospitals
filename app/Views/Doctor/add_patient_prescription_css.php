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
<style>
    body {
        background: rgb(224, 227, 231)
    }

    #input_box {
        border: 1px solid silver;
        box-shadow: none;
        box-sizing: border-box;
        padding-left: 10px;
        padding-right: 10px;
        height: 40px;
        border-radius: 3px;
    }

    [type="checkbox"]:not(:checked),
    [type="checkbox"]:checked {
        opacity: 1 !important;
        pointer-events: painted !important;
        position: relative !important;
    }

    /* #input_file {
			border-radius: 3px;
			height: 40px;
			border: 1px solid silver;
			padding: 8px;
			width: 100%;
			margin-bottom: 15px;
			font-size: 14px;
			font-weight: 500
		} */
    #select {
        border: 1px solid #9e9e9e;
        box-shadow: none;
        box-sizing: border-box;
        padding-left: 10px;
        padding-right: 10px;
        height: 40px;
        border-radius: 3px;
        display: block;
    }

    #prescription,
    #advice,
    #message{
        border: 1px solid silver;
        padding: 10px;
        outline: none;

        resize: none;
        border-radius: 10px !important;
        background-color: #eeefefd6 !important;
        height: 200px !important;
        width: 100%;
    }
    #selctarea{
        border: 1px solid silver;
        outline: none;
        resize: none;
        border-radius: 10px !important;
        background-color: #eeefefd6 !important;
        width: 100%;
    }

    #prescription,
    #advice::placeholder {
        color: #333333;
        font-weight: 100;
        font-family: serif;
    }

    #summary {
        border: 1px solid silver;
        padding: 10px;
        outline: none;

        resize: none;
        border-radius: 10px !important;
        background-color: #eeefefd6 !important;
        height: 250px !important;
        width: 100%;
    }

    #summary::placeholder {
        color: #333333;
        font-weight: 100;
        font-family: serif;
    }

    span {
        cursor: pointer;
    }

    h6 {
        font-weight: 600 !important;
        font-size: 14px;
    }

    h5 {
        font-weight: 600;
        margin-top: 5px;
        font-size: 20px;
    }

    .input-container {
        position: relative;
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

    h1 {
        text-align: center;
    }

    h2 {
        margin: 0;
    }

    #multi-step-form-container {
        margin-top: 4rem;
    }

    .text-center {
        text-align: center;
    }

    .mx-auto {
        margin-left: auto;
        margin-right: auto;
    }

    .pl-0 {
        padding-left: 0;
    }

    .button {
        padding: 0.7rem 1.5rem;
        border: 1px solid #005197;
        background-color: #005197;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    .submit-btn {
        border: 1px solid #005197;
        background-color: #005197;
    }

    .mt-3 {
        margin-top: -1rem;
    }

    .d-none {
        display: none;
    }

    #step-3 {
        margin-top: -2rem;
    }

    .form-step {
        /* border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 20px; */
        /* padding: -5rem; */
        /* margin-left: 50px;
            margin-right: 50px; */
        background: #f4feff00 !important;
    }

    .font-normal {
        font-weight: 600;
        font-size: 20px;
        color: #005197;
        font-family: system-ui;
    }

    ul.form-stepper {
        counter-reset: section;
        margin-bottom: 3rem;
        margin-left: 90px;
        margin-right: 90px;
    }

    ul.form-stepper .form-stepper-circle {
        position: relative;
    }

    ul.form-stepper .form-stepper-circle span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
    }

    .form-stepper-horizontal {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }

    ul.form-stepper>li:not(:last-of-type) {
        margin-bottom: 0.625rem;
        -webkit-transition: margin-bottom 0.4s;
        -o-transition: margin-bottom 0.4s;
        transition: margin-bottom 0.4s;
    }

    .form-stepper-horizontal>li:not(:last-of-type) {
        margin-bottom: 0 !important;
    }

    .form-stepper-horizontal li {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: start;
        -webkit-transition: 0.5s;
        transition: 0.5s;
    }

    .form-stepper-horizontal li:not(:last-child):after {
        position: relative;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height: 2px;
        content: "";
        top: 42%;
    }

    .form-stepper-horizontal li:after {
        background-color: #fff;
    }

    .form-stepper-horizontal li.form-stepper-completed:after {
        background-color: #005197;
    }

    .form-stepper-horizontal li:last-child {
        flex: unset;
    }

    ul.form-stepper li a .form-stepper-circle {
        display: inline-block;
        width: 40px;
        height: 40px;
        margin-right: 0;
        line-height: 1.7rem;
        text-align: center;
        background: #fff;
        border-radius: 50%;
    }

    .form-stepper .form-stepper-active .form-stepper-circle {
        background-color: #005197 !important;
        color: #fff;
    }

    .form-stepper .form-stepper-active .label {
        color: #4361ee;
    }

    .form-stepper .form-stepper-active .form-stepper-circle:hover {
        background-color: #4361ee !important;
        color: #fff !important;
    }

    .form-stepper .form-stepper-unfinished .form-stepper-circle {
        background-color: #f8f7ff;
    }

    .form-stepper .form-stepper-completed .form-stepper-circle {
        background-color: #005197 !important;
        color: #fff;
    }

    .form-stepper .form-stepper-completed .label {
        color: #0e9594;
    }

    .btnalign {
        background: none !important;
        border: none !important;
        color: #005197;
        padding: 0px 24px !important;
    }

    .btnalign:hover {
        color: #005197;
    }

    textarea::placeholder {
        color: black;
    }

    .txt_flx {
        color: #005197 !important;
        display: flex;
        font-size: 10px !important;
    }
    .txt_flx2 {
        color: #005197 !important;
        display: flex;
        font-size: 10px !important;
    }
    .txt_flx3 {
        color: #005197 !important;
        display: flex;
        font-size: 10px !important;
    }

    .txt_flx:focus {
        color: #005197 !important;
    }
    .txt_flx2:focus {
        color: #005197 !important;
    }
    .txt_flx3:focus {
        color: #005197 !important;
    }

    input[type=checkbox] {
        margin: 4px 50px 0 !important;
    }

    @media (max-width: 991px) {
        .mrg_rgt {
            margin-right: 30px;
        }
    }

    #reportToggle {
        display: flex;
        /* justify-content: space-between; */
        align-items: center;
        cursor: pointer;
        padding: 10px;
        border-top: 1px solid #ccc;
    }

    .form-stepper .form-stepper-completed .form-stepper-circle:hover {
        background-color: #4361ee !important;
        color: #fff !important;
    }

    .form-stepper .form-stepper-active span.text-muted {
        color: #fff !important;
    }

    .form-stepper .form-stepper-completed span.text-muted {
        color: #fff !important;
    }

    .form-stepper .label {
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .form-stepper a {
        cursor: default;
    }

    /**********add on css*************** */
    .topcontainer {
        background: #ffffff99;
        border-radius: 20px;
        height: auto;
    }

    .rowhead {
        margin-left: 80px;
        margin-right: 80px;
        margin-bottom: 12px;
        margin-top: 12px;
        font-family: serif
    }

    /*
    .left-section,
    .right-section {
      flex: 1;
      padding: 10px;
    }

    .left-section {
      text-align: left;
    }

    .right-section {
      text-align: right;
    }

    .row {
      display: block;
      margin-bottom: 10px;
      font-size: 12px;
    }

    @media screen and (max-width: 600px) {
      .container {
        flex-wrap: wrap;
      }
      .left-section,
      .right-section {
        width: 100%;
      }
    } */
    h2 {
        margin-top: 12px;
    }

    .row {
        margin-bottom: 3px;
    }

    /********************************** */
    /* Add your preferred styles here */
    .part {
        font-family: Arial, sans-serif;
        /* background-color: #f2f2f2; */
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    #reportGrid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        max-width: 1200px;
        margin: 0px -28px;
    }

    .report {
        display: flex;
        align-items: center;
        background-color: #fff;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .report .serial-number {
        flex-basis: 30px;
        text-align: center;
        color: #888;
        margin-right: 10px;
    }

    .report .report-name {
        flex-grow: 1;
        margin: 0;
        color: #333;
        font-weight: bold;
    }

    /* Style the custom checkbox */
    .report .custom-checkbox {
        width: 20px;
        height: 20px;
        background-color: #fff;
        border: 2px solid #ccc;
        border-radius: 4px;
        margin-right: 10px;
        cursor: pointer;
    }

    /* Style the custom checkbox when checked */
    .report .custom-checkbox input[type="checkbox"]:checked+.custom-checkbox::before {
        content: "\2713";
        font-size: 16px;
        color: #007bff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Background color change when report is checked */
    .report .custom-checkbox input[type="checkbox"]:checked~.report-name {
        text-decoration: line-through;
        color: #888;
    }

    /* Hover effect on the reports */
    .report:hover {
        background-color: #f2f2f2;
    }

    label {
        font-size: 14px;
        color: #333333;
    }

    .report:hover {
        box-shadow: 1px 1px 3px;
    }
</style>