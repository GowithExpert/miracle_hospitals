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
<!-- terms_and_conditions.php -->

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <?= view('Home/nav_bar'); ?> <!-- HTML nav bar -->
    <?= view('Home/css_file'); ?>
   
</head>

<body>
    <div class="container" id="conatiner_margin_top">
        <div class="mini_title">
            <p >Last Updated: February/20/2025</p>
        </div>
        <div class="section-heading">
            <h2>Terms and Conditions</h2>
            <div class="line"></div>
        </div>
        <!-- <h1>Terms and Conditions</h1> -->

        <div class="h3 section-title">Introduction</div>
            <p class="paratext">Welcome to Miracle Hospital. These terms and conditions outline the rules and regulations for the use of our hospital's services. By accessing and using our services, you accept and agree to be bound by these terms. If you do not agree with any part of these terms, please do not use our services.</p>
        
        <div class="h3 section-title">Service Availability</div>
            <p class="paratext">Our hospital operates 24/7 to provide medical care to patients in need. However, services may be limited during emergencies or unforeseen circumstances. We strive to maintain uninterrupted access to our services, but we do not guarantee continuous availability.</p>
        
        <div class="h3 section-title">Patient Responsibilities</div>
            <p class="paratext">Patients are expected to provide accurate and complete medical history information to ensure proper diagnosis and treatment. It is also essential to follow the prescribed treatment plan and cooperate with medical staff. Any intentional misrepresentation of medical history may result in adverse health effects, and the hospital will not be held responsible.</p>
            <p class="paratext">Patients must follow the hospital's rules and policies during their stay. This includes respecting the rights and privacy of other patients and medical personnel. Any violent or disruptive behavior will not be tolerated and may result in immediate discharge from the hospital.</p>
        
        <div class="h3 section-title">Medical Advice and Information</div>
            <p class="paratext">The information provided on our website and by our medical staff is for general informational purposes only and should not be considered as medical advice. Patients should always consult with a qualified healthcare professional before making any medical decisions or starting a new treatment.</p>
        
        <div class="highlight">
            <div class="h3 section-title">Privacy and Data Security</div>
                <p class="paratext">We take your privacy seriously and have implemented security measures to protect your personal information. Our full privacy policy is available on our website, outlining the types of data we collect, how we use it, and your rights regarding your data.</p>
                <p class="paratext">By using our services, you consent to the collection and use of your personal information as outlined in our privacy policy.</p>
        </div>

        <div class="h3 section-title">Payment and Billing</div>
            <p class="paratext">Patients are responsible for payment of medical services provided by the hospital. We accept various payment methods, including credit cards and health insurance. Payment is expected at the time of service unless other arrangements have been made.</p>
            <p class="paratext">If you have health insurance, please provide accurate and up-to-date information to facilitate the billing process. The hospital will bill your insurance company directly, but you may still be responsible for copayments, deductibles, or non-covered services.</p>
        
            <p class="paratext">Contact: <a href="<?= base_url('Home/contact_us') ?>"  target="_blank" class="hyper_link"> Click Here To Contact Us</a></p>
        </div>
    </div>
    <!--=========== Start Footer SECTION ================-->
    <?= view('Home/footer_section'); ?>
    <!--=========== End Footer SECTION ================-->

    <!----Js  file Include --->
    <?= view('Home/js_file'); ?>
    <!----Js  file Include --->
</body>

</html>