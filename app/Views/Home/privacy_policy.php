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
<!-- privacy_policy.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Privacy Policy</title>
    <link rel="stylesheet" href="styles.css">
    <?= view('Home/nav_bar'); ?> <!-- HTML nav bar -->
    <?= view('Home/css_file'); ?>

</head>

<body>
    <div class="container" id="conatiner_margin_top">
        <div class="mini_title">
            <p >Last Updated: February/20/2025</p>
        </div>
        <div class="section-heading">
            <h2>Privacy And Policy</h2>
            <div class="line"></div>
        </div>
        <p class="paratext">
            Welcome to [Your Hospital Name]! At [Your Hospital Name], we value your privacy and are committed to protecting your personal information. This privacy policy explains how we collect, use, and safeguard your data when you visit our website or use our services.
        </p>
        <div class="h3 section-title">Information We Collect</div>
        <p class="paratext"> 
            When you interact with our website or hospital services, we may collect the following types of information:
        </p>
        <ul>
            <li>Personal Information: This may include your name, contact information, date of birth, and other identifiers necessary for patient registration and communication.</li>
            <li>Health Information: As a hospital, we may collect and maintain your health records, medical history, and treatment information to provide quality healthcare services.</li>
            <li>Device and Usage Information: We may collect data about your device and how you interact with our website, such as IP address, browser type, pages visited, and time spent on the site.</li>
            <li>Cookies and Similar Technologies: We may use cookies and similar technologies to enhance your browsing experience and improve our website's functionality. You can manage your cookie preferences through your browser settings.</li>
        </ul>
        <div class="h3 section-title">How We Use Your Information</div>
        
            <p class="paratext">
                We use the collected information for the following purposes:
            </p>
            <ul>
                <li>Provide Healthcare Services: Your personal and health information is essential to deliver medical care, diagnose conditions, and manage treatment plans.</li>
                <li>Communication: We may use your contact details to send appointment reminders, health updates, and important notifications related to your healthcare.</li>
                <li>Improve Services: Analyzing website usage helps us understand user needs and preferences, enabling us to enhance our services and user experience.</li>
                <li>Compliance and Legal Obligations: We may use your data to comply with legal requirements, respond to legal inquiries, or protect the rights and safety of our patients and staff.</li>
            </ul>
        
        <div class="h3 section-title">Data Security</div>
        <p class="paratext">
          We implement appropriate technical and organizational measures to protect your data from unauthorized access, disclosure, alteration, or destruction. Our security measures include encryption, access controls, and regular data backups.
        </p>
        
        <div class="h3 section-title">Third-Party Services</div>
        <p class="paratext">
            We may use third-party service providers to assist with website analytics, communication, and other hospital-related services. These providers are contractually bound to safeguard your data and only use it for the intended purposes.
        </p>
        
        <div class="h3 section-title">Retention of Information</div>
        <p class="paratext">
             We retain your personal and health information for as long as required to fulfill the purposes outlined in this privacy policy or as necessary to comply with legal obligations.
        </p>
       
        <div class="h3 section-title">Your Rights</div>
        <p class="paratext">
            As a patient or website visitor, you have certain rights regarding your data, including the right to access, correct, and delete your information. To exercise these rights or if you have any privacy-related concerns, please contact us using the information provided at the end of this policy.
        </p>
        
        <div class="h3 section-title">Changes to this Privacy Policy</div>
        <p class="paratext">
            We may update this privacy policy periodically to reflect changes in our data practices or legal requirements. The most current version of the policy will be posted on our website, and the effective date will be indicated at the top of the page.
        </p>
        <div class="h3 section-title">Contact Us</div>
        <p class="paratext">
            If you have any questions, concerns, or requests regarding this privacy policy or our data practices, please contact us at:
            <br>
            [Your Hospital Name]
            <br>
            Address: [Hospital Address]
            <br>
            Email: [Hospital Email]
            <br>
            Phone: [Hospital Phone Number]
        </p>
         
    </div>

    <!--=========== Start Footer SECTION ================-->
    <?= view('Home/footer_section'); ?>
    <!--=========== End Footer SECTION ================-->

    <!----Js  file Include --->
    <?= view('Home/js_file'); ?>
    <!----Js  file Include --->
</body>

</html>