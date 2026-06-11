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

<section id="sliderArea">
  <!-- Start slider wrapper -->
  <div class="top-slider">
    <!-- Start First slide -->
    <?php if (!empty($slider)):
      count($slider);
      foreach ($slider as $slide) : ?>
        <div class="top-slide-inner">
          <div class="slider-img">
            <img src="<?= base_url() . 'public/uploads/frontend/slider/' . $slide->image_gallery; ?>" alt="<?= DEV_AUTHOR ?> ">
            
          </div>
          <div class="slider-text">
            <h2><!-- An <strong>Excellent</strong> --><?= $slide->image_title; ?></h2>
            <p><!--<strong>Miracle Heart</strong> --><?= $slide->image_discription; ?></p>
            <div class="readmore_area">
              
              <a data-hover="Read More" href="<?= $slide->website_link; ?>"><span>Read More</span></a>
            </div>
          </div>
        </div>
        <!-- End First slide -->
      <?php endforeach; ?>
      <?php else: ?>
      <!-- Start 2nd slide -->
      <div class="top-slide-inner">
        <div class="slider-img">
          <img src="<?= base_url('public/assets/home_image/images/15.jpg'); ?>" alt="">
        </div>
        <div class="slider-text">
          <h2><strong>Miracle</strong> Heart Super Speciality Hospital </h2>
          <p><strong>Miracle</strong> Heart Super Speciality Hospital Experience Quality Care</p>
          <div class="readmore_area">
            <a data-hover="Read More" href="<?= base_url() . 'public'?>"><span>Read More</span></a>
          </div>
        </div>
      </div>
      <?php endif; ?>
      <!-- End 2nd slide -->
    
  </div><!-- /top-slider -->
</section>
