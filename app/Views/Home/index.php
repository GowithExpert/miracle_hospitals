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
<html lang="en">
<head>
  <!----Css File Include --->
  <?= view('Home/css_file'); ?>
  <!----Css File Include --->
  
  <!----Js  file Include --->
  <?= view('Home/js_file'); ?>
  <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
  <!----Js  file Include --->
</head>
<body>
  <!---Nav Bar Section Include  --->
  <?= view('Home/nav_bar'); ?>
  <!---Nav Bar Section Include  --->

  <!---Php Meassge Show START --->
    <div id="cnfrm_msg" class="cnfrm">
      <?php if (session()->getTempdata('success')) : ?>
          <div class="card">
              <div class="card-content" id="succss_msg"><?= session()->getTempdata('success'); ?></div>
          </div>
      <?php endif; ?>
      <?php if (session()->getTempdata('error')) : ?>
          <div class="card">
              <div class="card-content" id="error_msg"><?= session()->getTempdata('error'); ?></div>
          </div>
      <?php endif; ?>
    </div>
    <!---Php Meassge Show END --->

  <!--=========== BEGIN SLIDER SECTION ================-->
  <?= view('Home/slider_section'); ?>
  <!--=========== END SLIDER SECTION ================-->
    
  <!--=========== BEGIN Top Feature SECTION ================-->
  <?= view('Home/top_feature_section'); ?>
  <!--=========== END Top Feature SECTION ================-->

  <!--=========== BEGIN Service SECTION ================-->
  <?= view('Home/front_service_section'); ?>
  <!--=========== End Service SECTION ================-->

  <!--=========== BEGAIN Why Choose Us SECTION ================-->
  <?= view('Home/why_choose_us'); ?>
  <!--=========== END Why Choose Us SECTION ================-->

  <!--=========== BEGAIN Counter SECTION ================-->
  <?= view('Home/counter_section'); ?>
  <!--=========== End Counter SECTION ================-->

  <!--=========== BEGAIN Doctors SECTION ================-->
  <?= view('Home/doctor_section'); ?>
  <!--=========== End Doctors SECTION ================-->

  <!--=========== BEGAIN Testimonial SECTION ================-->
  <?= view('Home/testimonial_section'); ?>
  <!--=========== End Testimonial SECTION ================-->

  <!--=========== BEGAIN Home Blog SECTION ================-->
  <?= view('Home/home_blog_section'); ?>
  <!--=========== End Home Blog SECTION ================-->

  <!--=========== Start Footer SECTION ================-->
  <?= view('Home/footer_section'); ?>
  <!--=========== End Footer SECTION ================-->


  <script>
    $(document).ready(function () {
      // Add a timeout function to hide the messages after 5 seconds
      setTimeout(function () {
          $("#succss_msg, #error_msg").fadeOut();
      }, 5000);
    });
  </script>
</body>
</html>