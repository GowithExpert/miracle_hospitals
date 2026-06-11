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
    <title>Patient Login</title>
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
</head>

<body>
    <!---Nav Bar Section Include  --->
    <?= view('Home/nav_bar'); ?>
    <!---Nav Bar Section Include  --->

    <!---Include Patient Login template--->
    <?= view('Patients/login'); ?>
    <!---Doctor Section Include --->

    <!--=========== Start Footer SECTION ================-->
    <?= view('Home/footer_section'); ?>
    <!--=========== End Footer SECTION ================-->

    <!----Js  file Include --->
    <?= view('Home/js_file'); ?>
    <!----Js  file Include --->

</body>
</html>