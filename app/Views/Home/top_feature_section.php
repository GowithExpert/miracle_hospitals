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

<?= helper('Form') ?>
<section id="topFeature">
  <div class="row">
    <!-- Start Single Top Feature -->
    <div class="col-lg-4 col-md-4">
      <div class="row">
        <div class="single-top-feature">
          <span class="fa fa-flask"></span>
          <h3>Emergency Care</h3>
          <p class="paratext">Emergency and Trauma Care is primarily intended to provide emergency services to accident and emergency victims <span class="fnt_fmly"> 24<sub class="fnt_sze">*</sub>7 all around the year.
          </p>
          <div class="readmore_area">
            <a href="<?= base_url('public/#'); ?> " data-hover="Read More"><span>Read More</span></a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Single Top Feature -->

    <!-- Start Single Top Feature -->
    <div class="col-lg-4 col-md-4">
      <div class="row">
        <div class="single-top-feature opening-hours">
          <span class="fa fa-clock-o"></span>
          <h3>Opening Hours</h3>
          <div class="line"></div>
          <ul class="opening-table">
            <li>
              <span>Monday - Friday</span>
              <div class="value">8.00 - 16.00</div>
            </li>
            <li>
              <span>Saturday</span>
              <div class="value">9.30 - 15.30</div>
            </li>
            <li>
              <span>Sunday</span>
              <div class="value">9.30 - 17.00</div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Single Top Feature -->
   
    <!-- Start Single Top Feature -->
    <div class="col-lg-4 col-md-4">
      <div class="row">
        <div class="single-top-feature">
          <span class="fa fa-hospital-o"></span>
          <h3>Quick Appointment</h3>
          
          <p class="paratext">An online booking doctor appointment form is <span class="fnt_fmly"> 24<sub class="fnt_sze">*</sub>7 </span> available for patient. Book your Appointment by clicking on <b>Book Appointment</b> Button.</p>
          <div class="readmore_area">
           
            <?php
            $this->patient_session_id = session()->get('patient_session_id');
            if (!isset($this->patient_session_id) || $this->patient_session_id == '') {
              $this->patient_session_id = 0;
            }
            ?>
            <a href="<?= base_url('Doctor/doctors_available_slots/' . $this->patient_session_id); ?> " data-hover="Book Appointment"><span>Book Appointment</span></a>
          </div>
          <!-- start modal window -->
          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="myModalLabel">Appoinment Details</h4>
                </div>
                <div class="modal-body">
                  <div class="appointment-area">

                    <?= form_open('Home/book_appointment_section'); ?>
                    <div class="row">
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="text" name="name" class="wp-form-control wpcf7-text" placeholder="Your name" required="required">
                        <span class="req_placeholder5"><span>*</span></span>


                        <?php if (isset($validation)) { ?>
                          <!-- <div class="alert alert-danger" role="alert"> -->
                          <span class="col_red"><?= display_error($validation, 'name'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>

                      </div>
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="mail" name="email" class="wp-form-control wpcf7-email" placeholder="Email address" required="required">
                        <span class="req_placeholder6"><span>*</span></span>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'email'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="text" name="Symptoms" class="wp-form-control wpcf7-text" placeholder="Enter Issue Symptoms" required="required">
                        <span class="req_placeholder7"><span>*</span></span>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'Symptoms'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="text" name="mobile" class="wp-form-control wpcf7-text" placeholder="Phone No" required="required">
                        <span class="req_placeholder8"><span>*</span></span>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'mobile'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="date" name="appointment_date" class="wp-form-control wpcf7-text" placeholder="Appointment Date" required="required">
                        <span class="req_placeholder9"><span>*</span></span>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'appointment_date'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                      <div class="col-md-6 col-sm-6">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <input type="time" name="appointment_time" class="wp-form-control wpcf7-text" placeholder="Appointment Time" required="required">
                        <span class="req_placeholder10"><span>*</span></span>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'appointment_time'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-sm-12">
                        <label class="control-label"><span class="required"></span>
                        </label><!--margin-bottom: 60px was present here-->
                        <?php if(isset($department)):
                            count($department);
                            foreach ($department as $dep): 
                          ?>
                        <select class="wp-form-control wpcf7-select" name="department">
                          <option selected="" disabled="">Select Department</option>
                          
                              <option value="<?= $dep->department_name ?>"><?= $dep->department_name ?></option>
                        </select>
                        <?php endforeach; ?>
                          <?php else : ?>
                            <h6 class="col_red">Department Not Found</h6>
                          <?php endif; ?>
                        <?php if (isset($validation)) { ?>
                          <span class="col_red"><?= display_error($validation, 'department'); ?></span>
                          <?= $validation->listErrors(); ?>
                        <?php } ?>
                      </div>
                      <?php if (isset($doctors[0]->doctor_name) && isset($doctors[0]->doctor_name)) { ?>
                        <input type="hidden" name="doctor_id" value="<?= $doctors[0]->id; ?>">
                        <input type="hidden" name="doctor_name" value="<?= $doctors[0]->doctor_name; ?>">
                      <?php } ?>
                    </div>
                    <textarea class="wp-form-control wpcf7-textarea" name="description" cols="30" rows="10" placeholder="What would you like to tell us"></textarea>
                    <button class="wpcf7-submit button--itzel" type="submit"><i class="button__icon fa fa-share"></i><span><a href=""></a>Book Appointment</span></button>
                    <?= form_close(); ?>
                  </div>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        </div>
      </div>
    </div>
    <!-- End Single Top Feature -->
  </div>
</section>