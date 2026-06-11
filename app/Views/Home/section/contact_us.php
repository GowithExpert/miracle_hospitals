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
<!DOCTYPE html>
<html>

<head>
  <title>Contact us</title>
  <?= helper('Form'); ?>
  <!----Css File Include --->
  <?= view('Home/css_file'); ?>
  <!----Css File Include --->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <!---Nav Bar Section Include  --->
  <?= view('Home/nav_bar'); ?>
  <!---Nav Bar Section Include  --->

  <section id="contact">
    <div class="container cntc">
      <!-- <div class="container"> -->
      <div class="row ">
        <div class="col-lg-8 col-md-8 col-sm-12 cntc-inner" id="contact_us_back">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="contact-form">
                <div class="section-heading">
                  <h2>Contact Us</h2>
                  <div class="line"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <p class="p_contnt">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
            </div>
          </div>
            <?= form_open('Home/contact_us', array('id' => 'registration_form', 'onsubmit' => 'return updateCountryCode();')); ?>
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="input-container">
                  <input type="text" name="name" id="nameInput" class="asterisk wp-form-control wpcf7-text nameError" placeholder="Enter Name">
                  <span class="asterisk-symbol asterisk_symbol_contac">*</span>
                  <span id="nameError" class="col_red clnt_valid"></span>
                </div>
                <?php if (isset($validation)) { ?>
                  <span class="col_red">
                    <?= display_error($validation, 'name'); ?>
                  </span><br>
                  <?= $validation->listErrors(); ?>
                <?php } ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="input-container">
                  <input type="text" name="email" id="email" maxlength="40" class="asterisk wp-form-control wpcf7-email emailError" placeholder="Enter Email Address">
                  <span class="asterisk-symbol asterisk_symbol_contac">*</span>
                  <span id="emailError" class="col_red clnt_valid"></span>
                </div>
                <?php if (isset($validation)) { ?>
                  <span class="col_red">
                    <?= display_error($validation, 'email'); ?>
                  </span> <br>
                  <?= $validation->listErrors(); ?>
                <?php } ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="input-container">
                  <input type="tel" name="mobile" class="wp-form-control wpcf7-mobile phone-input phoneError phone_mandatory" maxlength="10" placeholder="Enter Mobile Number" required="required" oninput="validateMobile(this)">
                  <span class="asterisk_phone mandatory_phone_fild">*</span>
                  <span id="phoneError" class="col_red clnt_valid"></span>
                </div>

                <!-- Container for the country code dropdown -->
                <div id="country_selector" name="country_selector"></div>
                <input type="hidden" id="country_code" name="country_code" value="">
                <?php if (isset($validation)) { ?>
                  <span class="col_red">
                    <?= display_error($validation, 'mobile'); ?>
                  </span> <br>
                  <?= $validation->listErrors(); ?>
                <?php } ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="input-container">
                  <input type="text" name="subject" id="subject" class="asterisk wp-form-control wpcf7-text addressError" placeholder="Enter Subject" required="required">
                  <span class="asterisk-symbol asterisk_symbol_contac">*</span>
                  <span id="addressError" class="col_red clnt_valid"></span>
                </div>
                <?php if (isset($validation)) { ?>
                  <span class="col_red">
                    <?= display_error($validation, 'subject'); ?>
                  </span>
                  <?= $validation->listErrors(); ?>
                <?php } ?>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <textarea name="message" class="wp-form-control wpcf7-textarea" cols="30" rows="10" placeholder="What Would You Like To Tell Us?"></textarea>
              </div>
            </div>
          
            <div class="row">
              <div id="form-container" class="col-lg-12 col-md-12 col-sm-12">
                <div class="readmore_area">
                  <button class="centered-button" id="btn_register_now" type="submit"><a data-hover="Send Message"><span>Send Message</span></a></button>
                </div>
              </div>
            </div>
            <?= form_close(); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="contact-address">
            <div class="section-heading">
              <h2>Contact Information</h2>
              <div class="line"></div>
            </div>
            <p>Contact details</p>
            <address class="contact-info contact-pg">
              <p><span class="fa fa-home"></span>
                <?= CONTACT_ADDRS ?>
              </p>
              <p><span class="fa fa-phone"></span><a href="tel: <?= CONTACT_AT ?>"><?= CONTACT_AT ?></a></p>
              <p><span class="fa fa-envelope"></span> <a href="mailto:<?= ADMIN_EMAIL ?>"><?= ADMIN_EMAIL ?></a></p>
            </address>
           
            <h3>Social Media</h3>
            <div class="social-share">
              <ul>
                <li><a href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-facebook"></span></a></li>
                <li><a href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-whatsapp"></span></a></li>
                <li><a href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-instagram"></span></a></li>
                <li>
                  <a href="<?= TWITTER_LINK ?>" target="_blank"><span class="fa fa-twitter"></span></a>
                </li>

              </ul>
            </div>
          </div>
        </div>
      </div><!-- Container and appoint closed-->
      <!--</div> --> <!-- Container div closed-->
  </section>

  <script>
    $(document).ready(function() {
      $("#btn_register_now").click(function(e) {
        //e.preventDefault();
        $(".error").text("");
        let valid = true;

        // Name validation
        const nameInput = $(".nameError");
        const nameError = $("#nameError");
        if (nameInput.val().trim() === "") {
          nameError.text("Please enter the name");
          valid = false;
        } else {
          nameError.text("");
        }

        // Email validation
        const emailInput = $(".emailError");
        const emailError = $("#emailError");
        if (!/^\S+@\S+\.\S+$/.test(emailInput.val())) {
          emailError.text("Please enter email address");
          valid = false;
        } else {
          emailError.text("");
        }

        // Mobile validation
        const phoneInput = $(".phoneError");
        const phoneError = $("#phoneError");
        if (!/^\d{10}$/.test(phoneInput.val())) {
          phoneError.text("Mobile number must be in 10 digits numeric");
          valid = false;
        } else {
          phoneError.text("");
        }

        //Address validation 
        const addressInput = $(".addressError");
        const addressError = $("#addressError");
        if (addressInput.val().trim() === "") {
          addressError.text("Please enter the subject");
          valid = false;
        } else {
          addressError.text("");
        }
        
        if (!valid) { e.preventDefault(); }
      });
    });
    function updateCountryCode() {
      var countrySelector = document.getElementById('country_selector');
      var selectedCountryCode = countrySelector.value;
      document.getElementById('country_code').value = selectedCountryCode;
    }
  </script>
  <!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

  <!--=========== Start Footer SECTION ================-->
  <?= view('Home/footer_section'); ?>
  <!--=========== End Footer SECTION ================-->

  <!----Js  file Include --->
  <?= view('Home/js_file'); ?>
  <!----Js  file Include --->
  <?= view('Admin/country_code_js_file.php'); ?>
</body>

</html>
