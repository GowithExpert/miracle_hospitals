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

<section id="meetDoctors">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <div class="meetDoctors-area">
          <!-- Start Service Title -->
          <div class="section-heading pdng_tp">
            <h2>Meet Our Doctors</h2>
            <div class="line"></div>
          </div>
          <div class="doctors-area">
            <ul class="doctors-nav">
              <?php if ($doctors) :
                count($doctors);
                foreach ($doctors as $doc) : ?>
                  <li>
                    <div class="single-doctor">
                      <a class="tdoctor" href="<?= base_url('Doctor/doctors_available_slots/' . $doc->id); ?>" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                        <figure>
                          <?php
                          if (isset($doc->profile_pic) && $doc->profile_pic != "") {
                            if (file_exists(FCPATH . 'uploads/doctor/' . $doc->profile_pic)) { ?>
                              <img src="<?= base_url() . 'public/uploads/doctor/' . $doc->profile_pic; ?>" class="dr_profile_pic">
                            <?php
                            } else { ?>
                              <img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="dr_profile_pic">
                            <?php
                            }
                          } else { ?>
                            <img src="<?= base_url() . 'public/assets/images/dr.default_pic.svg'; ?>" class="dr_profile_pic">
                          <?php                         
                          }
                          ?>
                          <svg viewBox="0 320 180 320" preserveAspectRatio="none">
                            <path d="M 180,160 0,218 0,0 180,0 z" />
                          </svg>
                          
                          <div class="doctor_section_home">
                            <p class="doctor_specialization" style="color:white !important; padding:5px; font-size:17px">
                              <?php if (!isset($doc->doctor_name) || $doc->doctor_name == null) {
                                  echo "Dr. No Name";
                                } else {
                                  echo $doc->doctor_name;
                                } ?>
                            </p>
                            <h2 class="doctor_specialization_name">
                                <?php 
                                  if (!isset($doc->dr_specialization) || $doc->dr_specialization == null) {
                                    echo "General";
                                  } 
                                  else {
                                    $dr_specialization_value = strlen($doc->dr_specialization) > 20 ? substr($doc->dr_specialization, 0, 20) . '...' : $doc->dr_specialization;
                                    echo $dr_specialization_value;
                                  }
                                ?>
                            </h2>
                            <div class="doctor_appointment"><span>
                              Book Appointment
                            </div>
                          </div>
                          
                        </figure>
                      </a>
                      
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
                <li>
                  <div class="single-doctor">
                    <div class="single-doctor">
                      <a class="tdoctor" href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                        <figure>
                          <img src="<?= base_url('public/assets/home_image/images/doctor-2.jpg'); ?>" class="dr_profile_pic">
                          <svg viewBox="0 320 180 320" preserveAspectRatio="none">
                            <path d="M 180,160 0,218 0,0 180,0 z" />
                          </svg>
                          <figcaption>
                            <h2>DR. Jack Johnson</h2>
                            <p>Rehabilitation Therapy</p>
                            <button>View</button>
                          </figcaption>
                        </figure>
                      </a>
                      <div class="single-doctor-social">
                        <a href="#"><span class="fa fa-facebook"></span></a>
                        <a href="#"><span class="fa fa-twitter"></span></a>
                        <a href="#"><span class="fa fa-google-plus"></span></a>
                        <a href="#"><span class="fa fa-linkedin"></span></a>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>