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

<?php
if(isset($patient_feeback[0])) {
  if(!isset($patient_feeback[0]->review_image)) {
    if(file_exists(FCPATH . 'uploads/frontend/review_image/' . $patient_feeback[0]->review_image)) {
      $patient_feeback[0]->review_image = FCPATH . 'uploads/frontend/review_image/' . $patient_feeback[0]->review_image;
    } 
  }
?>
<section id="testimonial">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="testimonial-area">
          <!-- Start Service Title -->
          <div class="section-heading pdng_tp">
            <h2>Patients Feedback</h2>
            <div class="line"></div>
          </div>
          <div class="testimonial-area">
            <ul class="testimonial-nav">
              <?php if ($patient_feeback) :
                count($patient_feeback);
                foreach ($patient_feeback as $feedback): ?>
                  <li>
                    <div class="single-testimonial">
                      <div class="testimonial-img1 testimonial-img">
                        <!-- <img src="<//?= base_url('public/uploads/frontend/review_image/' . $feedback->review_image); ?>" alt="img"> -->
                        <a class="tooltipped" data-position="top" data-tooltip="<?= $feedback->review_image; ?>">
                          <?php
                          if (isset($feedback->review_image) && !empty($feedback->review_image)) {
                            if (file_exists(FCPATH . 'uploads/frontend/review_image/' . $feedback->review_image)) { ?>
                              <img src="<?= base_url() . 'public/uploads/frontend/review_image/' . $feedback->review_image; ?>" class="responsive-img patient_img" id="profile_pic">

                            <?php  } //Inner if - Closed
                            else {  ?>
                              <img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img patient_img" id="profile_pic">
                            <?php } //Inner else - Closed

                          } //Outer if - Closed
                          else { ?>
                            <img src="<?= base_url() . 'public/assets/images/patient_default.svg'; ?>" class="responsive-img patient_img" id="profile_pic" >
                          <?php } //Outer else - Closed  
                          ?>
										    </a>
                      </div>
                      <div class="testimonial-cotent">
                        <p class="star-rating"><?php
                                $rating = $feedback->star_rating;
                                for ($i = 1; $i <= 5; $i++) {
                                    $starClass = ($i <= $rating) ? 'yellow-star' : 'grey-star';
                                    echo '<span class="' . $starClass . '">&#9733;</span>';
                                }
                                ?></p>
                        <p class="testimonial-parg"><?= $feedback->review_content; ?></p>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php } ?>