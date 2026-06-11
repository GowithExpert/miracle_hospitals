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

<section id="whychooseSection">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="service-area">
          <!-- Start Service Title -->
          <div class="section-heading pdng_tp">
            <h2>Why Choose Us</h2>
            <div class="line"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12">
        <div class="row">
          <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
            <div class="whyChoose-left">
              <div class="whychoose-slider">
                <!---Why Choose Image Script --->
                <?php if (isset($why_choose)) {
                  if ($why_choose) :
                    count($why_choose);
                    foreach ($why_choose as $choose) : ?>
                      <div class="whychoose-singleslide">
                        <img src="<?= base_url() . 'public/uploads/frontend/image_gallery/' . $choose->gallery_image; ?>" alt="" class="widh">
                      </div>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <div class="whychoose-singleslide">
                      <img src="<?= base_url('public/assets/home_image/images/choose-us-img2.jpg') ?>" alt="">
                    </div>
                  <?php endif;
                } else { ?>
                  <div class="whychoose-singleslide">
                    <img src="<?= base_url('public/assets/home_image/images/choose-us-img2.jpg') ?>" alt="">
                  </div>
                <?php } ?>
                <!---Why Choose Image Script --->
              </div>
            </div>
          </div>
          <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
            <div class="whyChoose-right">
              <div class="media">
                <div class="media-left">
                  <!--
                  <a href="#" class="media-icon">
                    <span class="fa fa-hospital-o "></span>
                  </a>-->
                  <span class="fa fa-hospital-o media-icon"></span>
                 </div>
                <div class="media-body">
                  <h4 class="media-heading">Great Infrastructure</h4>
                  <p class="paratext">Miracle heart hospital is an Ultra-Modern Hospital, multi and Super-Specialty hospital designed to meet international standards.
                    It is well equipped to provide a full range of in-patient and out-patient services, i.e., consultation, diagnostics, pharmacy, indoor treatment,
                    including surgeries to aftercare.</p>
                </div>
              </div>
              <div class="media">
                <div class="media-left">
                <!--
                  <a href="#" class="media-icon">
                    <span class="fa fa-user-md"></span>
                  </a>
                -->
                <span class="fa fa-user-md media-icon"></span>
                </div>
                <div class="media-body">
                  <h4 class="media-heading">Experienced Doctors</h4>
                  <p class="paratext">Our team is highly qualified,empathetic and dedicated to the well-being of their patients. We always adopt the best practices, techniques, and procedures of the healthcare industry.
                    Our hospital is specialized in Cardiology, General Medicine, Neurosurgery, General Surgery, Laparoscopic Surgery, Orthopedics, Obstetrics, Gynecology and Pediatrics.</p>
                </div>
              </div>
              <div class="media">
                <div class="media-left">
                  <!--
                  <a href="#" class="media-icon">
                    <span class="fa fa-ambulance"></span>
                  </a>
                  -->
                  <span class="fa fa-ambulance media-icon"></span>
                </div>
                <div class="media-body">
                  <h4 class="media-heading">Emergency Support</h4>
                  <p class="paratext">At Miracle heart hospital, the Department of Emergency and Trauma Care addresses injuries like cuts and fractures with the same level of care as life-threatening
                    crises like heart attacks and strokes. The emergency department (ED), which is open around the clock, provides emergency care for everyone who needs it,
                    including infants, kids, teenagers, and adults. </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>