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
	<?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/top_bar'); ?> 
	<?= view('Admin/custom_css_file.php'); ?>
	<?= view('Doctor/doctor_css_file.php'); ?>
</head>

<body class="trm_condi_bdy">
    <div class="container">
        <h1 class="trm_condi_txt">Terms and Conditions</h1>

        <div class="section-title">Introduction</div>
        <div class="section-content">
            <p>Welcome to Miracle Hospital. These terms and conditions outline the rules and regulations for the use of our hospital's services. By accessing and using our services, you accept and agree to be bound by these terms. If you do not agree with any part of these terms, please do not use our services.</p>
        </div>

        <div class="section-title">Service Availability</div>
        <div class="section-content">
            <p>Our hospital operates 24/7 to provide medical care to patients in need. However, services may be limited during emergencies or unforeseen circumstances. We strive to maintain uninterrupted access to our services, but we do not guarantee continuous availability.</p>
        </div>

        <div class="section-title">Patient Responsibilities</div>
        <div class="section-content">
            <p>Patients are expected to provide accurate and complete medical history information to ensure proper diagnosis and treatment. It is also essential to follow the prescribed treatment plan and cooperate with medical staff. Any intentional misrepresentation of medical history may result in adverse health effects, and the hospital will not be held responsible.</p>
            <p>Patients must follow the hospital's rules and policies during their stay. This includes respecting the rights and privacy of other patients and medical personnel. Any violent or disruptive behavior will not be tolerated and may result in immediate discharge from the hospital.</p>
        </div>

        <div class="section-title">Medical Advice and Information</div>
        <div class="section-content">
            <p class>The information provided on our website and by our medical staff is for general informational purposes only and should not be considered as medical advice. Patients should always consult with a qualified healthcare professional before making any medical decisions or starting a new treatment.</p>
        </div>

        <div class="highlight">
            <div class="section-title">Privacy and Data Security</div>
            <div class="section-content">
                <p>We take your privacy seriously and have implemented security measures to protect your personal information. Our full privacy policy is available on our website, outlining the types of data we collect, how we use it, and your rights regarding your data.</p>
                <p>By using our services, you consent to the collection and use of your personal information as outlined in our privacy policy.</p>
            </div>
        </div>

        <div class="section-title">Payment and Billing</div>
        <div class="section-content">
            <p>Patients are responsible for payment of medical services provided by the hospital. We accept various payment methods, including credit cards and health insurance. Payment is expected at the time of service unless other arrangements have been made.</p>
            <p>If you have health insurance, please provide accurate and up-to-date information to facilitate the billing process. The hospital will bill your insurance company directly, but you may still be responsible for copayments, deductibles, or non-covered services.</p>
        </div>

        <div class="section-title">
            <p>Last updated: [Dec/25/2023]</p>
             <p>Contact:<a href="<?= base_url('Home/contact_us') ?>" class="col_blu">  <span class="fas fa-comment col_blu btn_hver"></span>  Click Here To Contact Us</a></p>
        </div>
    </div>
    <!--=========== Start Footer SECTION ================-->
    <!-- <//?= view('Home/footer_section'); ?> -->
    <!--=========== End Footer SECTION ================-->
	<?= view('Admin/js_file.php'); ?>
	<!---Js file Include -->
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
    <!----Js  file Include --->
    
    <!----Js  file Include --->
</body>

</html>