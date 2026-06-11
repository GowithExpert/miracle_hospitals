
<footer id="footer">

  <!-- Start Footer Top -->
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="single-footer-widget">
            <div class="section-heading">
              <h2>About Us</h2>
              <div class="line"></div>
            </div>
            <p class="paratext">We offer full-fledged treatment services that range from primary care to complicated & rare disorders. We regularly keep upgrading our hospital with the latest technology by acquiring new diagnostic and therapeutic equipments for early diagnosis and treatment. Our specialty services delivered with quality, compassion and respect have enabled us to achieve excellence in patient’s clinical outcomes. We have successfully created our own niche across the healthcare spectrum.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="single-footer-widget">
            <div class="section-heading">
              <h2>Important Links</h2>
              <div class="line"></div>
            </div>

            <ul class="footer-service">
              
              <li class="active"><a class="txt_hver" href="<?= base_url('Home/index'); ?>"><span class="fa fa-check"></span>Home</a></li>
              <li><a class="txt_hver" href="<?= base_url('Home/features'); ?>"><span class="fa fa-check"></span>Features</a></li>
             
              <li><a class="txt_hver" href="<?= base_url('Home/about_us'); ?>"><span class="fa fa-check"></span>About Us</a></li>
              
              <li class="dropdown">
                <a class="txt_hver" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-check"></span>Service <span class="fa fa-angle-down"></span></a>
                <ul class="dropdown-menu" role="menu">

                  <li class="txt_indnt"><a class="btn_hver" href="<?= base_url('Blood_bank_registration/index'); ?>" target="_blank">Blood Bank</a></li>
                </ul>
              </li>
             
              <li><a class="txt_hver" href="<?= base_url('Home/gallery'); ?>"><span class="fa fa-check"></span>Gallery</a></li>
              
              <li class="dropdown">
                <a class="txt_hver" href="<?= base_url('Home/blog_archive'); ?>"><span class="fa fa-check"></span>News</a>
              </li>
              <li><a class="txt_hver" href="<?= base_url('Home/contact_us'); ?>"><span class="fa fa-check"></span>Contact Us</a></li>
              
              <li><a class="txt_hver" href="<?= base_url('Home/privacy_policy'); ?>"><span class="fa fa-check"></span>Privacy Policy</a></li>
              <li><a class="txt_hver" href="<?= base_url('Home/terms_condition'); ?>"><span class="fa fa-check"></span>Terms & Conditions</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="single-footer-widget">
            <div class="section-heading">
              <h2>Tags</h2>
              <div class="line"></div>
            </div>
            <ul class="tag-nav">
              <li><a href="#">Dental</a></li>
              <li><a href="#">Surgery</a></li>
              <li><a href="#">Pediatric</a></li>
              <li><a href="#">Cardiac</a></li>
              <li><a href="#">Ophthalmology</a></li>
              <li><a href="#">Diabetes</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="single-footer-widget">
            <div class="section-heading">
              <h2>Contact Info</h2>
              <div class="line"></div>
            </div>
            <p>Contact details</p>
            <address class="contact-info">
              <p><span class="fa fa-home"></span><?= CONTACT_ADDRS ?></p>
              <p><span class="fa fa-phone"></span><?= CONTACT_AT ?></p>
              <p><span class="fa fa-envelope"></span>
                <a href="mailto:<?= ADMIN_EMAIL; ?>"><?= ADMIN_EMAIL; ?></a>
              </p>
            </address>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Start Footer Middle -->

  <!-- Start Footer Bottom -->
  <div class="footer-bottom" id="backgnd_dsn">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
          <div class="footer-copyright">
            <p>&copy; Copyright <span class="col_wite">  <?= date('Y'); ?></span> All right reserved <a class="lnk_hver col_wite" href="<?= WEBSITE ?>" target="_blank"><?= DEV_AUTHOR ?></a></p>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
          <div class="col footer-copyright">
            <p>Design & developed by <a class="lnk_hver col_wite" rel="nofollow" href="<?= WEBSITE ?>" target="_blank"><?= DEV_TEAM ?></a></p>
          </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
          <div class="footer-social">
            <a class="blue_lnk_hver" href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-facebook"></span></a>
            <a class="twiter_lnk_hver" href="<?= TWITTER_LINK ?>" target="_blank"><span class="fa fa-twitter"></span></a>
            <a class="whatsapp_lnk_hver" href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-whatsapp"></span></a>
            <a class="inst_lnk_hver" href="<?= FB_LINK ?>" target="_blank"><span class="fa fa-instagram"></span></a>
          </div>
         
        </div>
      </div>
    </div>
</footer>
  <!----Js  file Include --->
  <?= view('Home/js_file'); ?>
  <!----Js  file Include --->