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

<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
    <!----Css File Include --->
    <?= view('Home/css_file'); ?>
    <!----Css File Include --->
    <style>
        *::-webkit-scrollbar {
            width: 0.6em;
        }

        *::-webkit-scrollbar-thumb {
            background-color: #0DB69F;
        }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Registration </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />


    <!-- Include the JavaScript files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.6/inputmask.min.js"></script> -->
    <?= helper('Form'); ?>
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
</head>

<body>
    <!---Nav Bar Section Include  --->
    <?= view('Home/nav_bar'); ?>
    <!---Nav Bar Section Include  --->

    <!---Include Patient Login template--->
    <?= view('Patients/patients_register'); ?>
    <!---Doctor Section Include --->

    <!--=========== Start Footer SECTION ================-->
    <?= view('Home/footer_section'); ?>
    <!--=========== End Footer SECTION ================-->

    <!----Js  file Include --->
    <?= view('Home/js_file'); ?>
    <!----Js  file Include --->

</body>
</html>