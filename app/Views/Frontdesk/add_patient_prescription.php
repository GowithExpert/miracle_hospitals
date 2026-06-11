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
<html>

<head>
    <title>Add Prescription</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
    <?= helper('Form'); ?>
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Home/css_file'); ?>
    <?= view('Doctor/add_patient_prescription_css.php'); ?>
    <?= view('Frontdesk/frontdesk_css_file.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include jQuery UI library -->
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body class="pres_bdy_back">
    <!---Topbar Section Include --->
    <?= view('Blood_bank/Donor/top_bar'); ?>
    <!---Topbar Section Include --->

    <!---Body Section Start -->
    <div id="multi-step-form-container">
        <!-- Form Steps / Progress Bar -->
        <div class="container topcontainer">
            <div class="row rowhead">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <div class="left-section">
                        <div class="row dr_name" name="div_doctor_name" id="div_doctor_name"><span class="dr_detil">Dr. Name</span>  <span id="doctor_name"><?php echo $res->doctor_name; ?></span></div>
                        <div class="row" name="education" id="div_education"><span class="dr_detil">Degree</span>  <span id="education"><?php echo $res->education; ?></span></div>
                        <div class="row" name="dr_specialization" id="div_dr_specialization"><span class="dr_detil">Specialization</span>  <span id="dr_specialization"><?php echo $res->dr_specialization; ?></span></div>
                        <div class="row" name="doc_gender" id="div_doc_gender"><span class="dr_detil">Gender</span>  <span id="doc_gender"><?php echo $res->doc_gender; ?></span></div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="right-section">
                        <div hidden class="row" name="id" id="id">Patient id - <span id="patient_id"><?php echo $res->id; ?></span></div>
                        <div class="row" name="patient_name" id="div_patient_name"><span class="dr_detil">Name</span>  <span id="patient_name"><?php echo $res->patient_name; ?></span></div>
                        <div class="row" name="patient_age" id="div_age"><span class="dr_detil">Age</span>  <span id="patient_age"><?php echo $res->age; ?></span></div>
                        <div class="row" name="patient_gender" id="div_gender"><span class="dr_detil">Gender</span>  <span id="patient_gender"><?php echo $res->gender; ?></span></div>
                        <div class="row" name="patient_puid" id="div_puid"><span class="dr_detil">Puid</span>  <span id="patient_puid"><?php echo $res->puid; ?></span></div>
                    </div>
                </div>
            </div>
            <ul class="presc_top_mrgn form-stepper form-stepper-horizontal text-center mx-auto pl-0">
                <!-- Step 1 -->
                <li id="step-list-1" class="form-stepper-active text-center form-stepper-list" step="1">
                    <a class="mx-2">
                        <span class="form-stepper-circle">
                            <span>1</span>
                        </span>
                    </a>
                    <div class="label txt_flx">Prescription</div>
                </li>

                <!-- Step 2 -->
                <li id="step-list-2" class="form-stepper-unfinished text-center form-stepper-list" step="2">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>2</span>
                        </span>
                    </a>
                    <div class="label text-muted txt_flx2">Report</div>
                </li>
                <!-- Step 3 -->
                <li id="step-list-3" class="form-stepper-unfinished text-center form-stepper-list" step="3">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>3</span>
                        </span>
                    </a>
                    <div class="label text-muted txt_flx2">Advise</div>
                </li>
                <!-- Step 4 -->
                <li id="step-list-4" class="form-stepper-unfinished text-center form-stepper-list" step="4">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>4</span>
                        </span>
                    </a>
                    <div class="label text-muted txt_flx3">Recommendation</div>
                </li>
            </ul>
            <!-- Step Wise Form Content -->
            <?= form_open_multipart('Doctor/upload_prescription', array('id' => 'prescription_form', 'name' => 'prescription_form')) ?>
            <!-- Step 1 Content -->
            <section id="step-1" class="form-step presc-dummy mrgn_lft_rght">
                <!-- Step 1 input fields -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mt-3">
                            <textarea id="prescription" placeholder="Enter Prescription" class="placehldr" name="prescription"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="skip_step1_btn" id="skip_step1_btn" skip_step="1">Skip</button>
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step2_btn" id="goto_step2_btn" goto_step="2">Next</button>
                </div>
            </section>
            <!-- Step 2 Content, default hidden on page load. -->
            <section id="step-2" class="form-step d-none presc-dummy mrgn_lft_rght">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="font-normal">Reports</h2>
                    </div>
                </div>

                <div id="custom_popup" style="display:none;">
                    <div class="popup_message"></div>
                </div>
                <?php if (!empty($report)) {
                    $fliped_rep = array_flip($report);
                } else {
                    $fliped_rep = [];
                } ?>
                <div class="row lft_rgt">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" <?php if (in_array('Blood Report', $report)) {
                                         echo 'checked disabled';
                                          } ?> id="checkbox1" value="Blood Report">
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>Blood Report</span>
                                    </div>
                                    <?php
                                    if (!isset($fliped_rep['Blood Report'])) {
                                    } else {

                                        if (isset($fliped_rep['Blood Report']) && empty($reporatach[$fliped_rep['Blood Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3  <?php if (!in_array('Blood Report', $report)) {
                                                  echo 'd-none';
                                                } ?>">
                                                <input type="file" class="file-input" id="bldrpt" name="bldrpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['Blood Report'])) {
                                                     echo $fliped_rep['Blood Report'];
                                                 } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                
                                                <a class="btn-download" id="downloadButton1" target="_blank" href="<?php echo base_url('public/uploads/patient_reports/' . $reporatach[$fliped_rep['Blood Report']]); ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['Blood Report']]; ?></i></a>
                                               
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" id="checkbox2" value="ECG Report" <?php if (in_array('ECG Report', $report)) {
                                                                                                                        echo 'checked disabled';
                                                                                                                    } ?>>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>ECG Report</span>
                                    </div>
                                    <?php
                                    if (!isset($fliped_rep['ECG Report'])) {
                                    } else {
                                        if (isset($fliped_rep['ECG Report']) && empty($reporatach[$fliped_rep['ECG Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3  <?php if (!in_array('ECG Report', $report)) {
                                                                                        echo 'd-none';
                                                                                    } ?>">
                                                <input type="file" class="file-input" id="ecgrpt" name="ecgrpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['ECG Report'])) {
                                                                                                                                echo $fliped_rep['ECG Report'];
                                                                                                                            } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                
                                                <a class="btn-download" id="downloadButton2" target="_blank" href="<?php echo base_url('public/uploads/patient_reports/' . $reporatach[$fliped_rep['ECG Report']]); ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['ECG Report']]; ?></i></a>
                                                
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" id="checkbox3" value="Diabetes Tests Report" <?php if (in_array('Diabetes Tests Report', $report)) {
                                                                                                                                echo 'checked disabled';
                                                                                                                            } ?>>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>Diabetes Tests Report</span>
                                    </div>
                                    <?php
                                    if (!isset($fliped_rep['Diabetes Tests Report'])) {
                                    } else {
                                        if (isset($fliped_rep['Diabetes Tests Report']) && empty($reporatach[$fliped_rep['Diabetes Tests Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3  <?php if (!in_array('Diabetes Tests Report', $report)) {
                                                                                        echo 'd-none';
                                                                                    } ?>">
                                                <input type="file" class="file-input" id="diarpt" name="diarpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['Diabetes Tests Report'])) {
                                                                                                                                echo $fliped_rep['Diabetes Tests Report'];
                                                                                                                            } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                
                                                <a class="btn-download" id="downloadButton3" target="_blank" href="<?php echo base_url() . 'public/uploads/patient_reports/' . $reporatach[$fliped_rep['Diabetes Tests Report']]; ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['Diabetes Tests Report']]; ?></i></a>
                                                
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" id="checkbox4" value="HIV Test Report" <?php if (in_array('HIV Test Report', $report)) {
                                                                                                                            echo 'checked disabled';
                                                                                                                        } ?>>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>HIV Test Report</span>
                                    </div>
                                    <?php
                                    if (!isset($fliped_rep['HIV Test Report'])) {
                                    } else {
                                        if (isset($fliped_rep['HIV Test Report']) && empty($reporatach[$fliped_rep['HIV Test Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3  <?php if (!in_array('HIV Test Report', $report)) {
                                                                                        echo 'd-none';
                                                                                    } ?>">
                                                <input type="file" class="file-input" id="hivrpt" name="hivrpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['HIV Test Report'])) {
                                                                                                                                echo $fliped_rep['HIV Test Report'];
                                                                                                                            } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                
                                                <a class="btn-download" id="downloadButton4" target="_blank" href="<?php echo base_url('public/uploads/patient_reports/' . $reporatach[$fliped_rep['HIV Test Report']]); ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['HIV Test Report']]; ?></i></a>
                                                
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" id="checkbox5" value="X-Ray Report" <?php if (in_array('X-Ray Report', $report)) {
                                                                                                                        echo 'checked disabled';
                                                                                                                    } ?>>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>X-Ray Report</span>
                                    </div>
                                    <?php
                                    if (!isset($fliped_rep['X-Ray Report'])) {
                                    } else {
                                        if (isset($fliped_rep['X-Ray Report']) && empty($reporatach[$fliped_rep['X-Ray Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3  <?php if (!in_array('X-Ray Report', $report)) {
                                                                                        echo 'd-none';
                                                                                    } ?>">
                                                <input type="file" class="file-input" id="xrayrpt" name="xrayrpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['X-Ray Report'])) {
                                                                                                                                echo $fliped_rep['X-Ray Report'];
                                                                                                                            } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                               
                                                <a class="btn-download" id="downloadButton5" target="_blank" href="<?php echo base_url('public/uploads/patient_reports/' . $reporatach[$fliped_rep['X-Ray Report']]); ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['X-Ray Report']]; ?></i></a>
                                                
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                                        <input type="checkbox" name="report[]" id="checkbox6" value="MRI Report" <?php if (in_array('MRI Report', $report)) {
                                                                                                                        echo 'checked disabled';
                                                                                                                    } ?>>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5">
                                        <span>MRI Report</span>
                                    </div>
                                    <?php
                                    
                                    if (!isset($fliped_rep['MRI Report'])) {
                                    } else {
                                        if (isset($fliped_rep['MRI Report']) && empty($reporatach[$fliped_rep['MRI Report']])) {  ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3 <?php if (!in_array('MRI Report', $report)) {
                                                                                        echo 'd-none';
                                                                                    } ?>">
                                                <input type="file" class="file-input" id="mrirpt" name="mrirpt" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" data-id="<?php if (isset($fliped_rep['MRI Report'])) {
                                                                                                                                echo $fliped_rep['MRI Report'];
                                                                                                                            } ?>">
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                
                                                <a class="btn-download" id="downloadButton6" target="_blank" href="<?php echo base_url('public/uploads/patient_reports/' . $reporatach[$fliped_rep['MRI Report']]); ?>" download><i class="fa fa-download"><?php echo $reporatach[$fliped_rep['MRI Report']]; ?></i></a>
                                                
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="reportToggle">
                    <button class="btnalign" id="addReportFieldButton"><i class='fas fa-plus-circle'></i></button><span>Other Reports</span>
                    
                </div>
                <div id="reportSection">
                    
                    <div class="row lft_rgt">
                        <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                            <input type="checkbox" name="checkbox7" id="checkbox7">
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <span>Other Report</span>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <input type="file" class="file-input" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif" id="fileInput7">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <a class="btn-download" id="downloadButton7" target="_blank" href="" download><i class="fa fa-download"></i></a>
                        </div>
                    </div>
                    <!-- "Add Report Field" button -->
                    <div id="reportFieldsContainer">
                        <!-- Your existing report fields go here -->
                    </div>
                </div>
                <div class="mt-3">
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="skip_step2_btn" id="skip_step2_btn" skip_step="2">Skip</button>
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step1_btn" id="goto_step1_btn" goto_step="1">Prev</button>
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step3_btn" id="goto_step3_btn" goto_step="3">Next</button>
                </div>
            </section>
            <!-- Step 3 Content, default hidden on page load. -->
            <section id="step-3" class="form-step presc-dummy d-none mrgn_lft_rght">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mt-3 mt4">
                            <textarea id="advice" placeholder="Enter Advise For Patient" class="placehldr" name="advice"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Step 3 input fields -->
                <div class="mt-3">
                </div>
                <div class="mt-3">
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="skip_step3_btn" id="skip_step3_btn" skip_step="3">Skip</button>
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step2_btn" id="goto_step2_btn" goto_step="2">Prev</button>
                    
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step4_btn" id="goto_step4_btn" goto_step="4">Next</button>

                </div>
            </section>
            <!-- Step 4 Content, default hidden on page load. -->
            <section id="step-4" class="form-step presc-dummy d-none mrgn_lft_rght">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <select required id="selctarea" name="selctarea">
                                        <option class="placehldr" selected disabled value="">Select User</option>
                                        <option>Admin</option>
                                        <option>Doctor</option>
                                        <option>Accountant</option>
                                        <option>Blood Bank</option>
                                        <option>Laboratory</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <textarea id="message" placeholder="Enter Message For Patient" class="placehldr" name="message"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="button btn-navigate-form-step btn_hver" type="button" name="goto_step3_btn" id="goto_step3_btn" goto_step="3">Prev</button>
                    
                    <button class="button submit-btn btn_hver" name="save" id="save" type="submit">Save</button>
                    
                </div>
            </section>
            <?= form_close(); ?>
            
        </div>
    </div>

    <!---Js file Include -->
    <?= view('Admin/js_file.php'); ?>
    <!---Js file Include -->
    <?= view('Doctor/multiple_steps_js_file.php'); ?>
    <script>
        $(document).ready(function() {
            $('#goto_step2_btn').click(function(event) {
                console.log("goto_step2_btn_clicked");
                var doctor_name = $('#doctor_name').text();
                var education = $('#education').text();
                var dr_specialization = $('#dr_specialization').text();
                var doc_gender = $('#doc_gender').text();
                var id = $('#patient_id').text();
                var patient_name = $('#patient_name').text();
                var age = $('#patient_age').text();
                var gender = $('#patient_gender').text();
                var puid = $('#patient_puid').text();
                var prescription = $('#prescription').val();
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('/Frontdesk/upload_prescription/') ?>",
                    data: {
                        doctor_name: doctor_name,
                        education: education,
                        dr_specialization: dr_specialization,
                        doc_gender: doc_gender,
                        patient_id: id,
                        patient_name: patient_name,
                        patient_age: age,
                        patient_gender: gender,
                        patient_puid: puid,
                        prescription: prescription,
                        apmt_id:"<?php echo $res->apmt_id; ?>",
                        pid:"<?php echo $res->pid; ?>",
                        ref_by:"<?php echo $res->ref_by; ?>",
                        patient_phone:"<?php echo $res->patient_phone; ?>",
                        patient_email:"<?php echo $res->patient_email; ?>",
                        disease_symptoms:"<?php echo $res->patient_issue; ?>",
                        status:"<?php echo $res->status; ?>",
                        patient_type:""
                    },
                    dataType: 'text', //for 'string'
                    success: function(response) {
                        var jsonResponse = JSON.parse(response);
                        var popup = $('#custom_popup');
                        var overlay = $('#overlay');
                        var popupMessage = popup.find('.popup_message');

                        popupMessage.text(jsonResponse.message);

                        if (jsonResponse.status) {
                            popup.find('.popup_title').text('Success');
                            popup.css('background-color', 'green'); // Set background color to green for success
                        } else {
                            popup.find('.popup_title').text('Error');
                            popup.css('background-color', 'red'); // Set background color to red for error
                        }

                        // Show the overlay and popup
                        overlay.show();
                        popup.show();

                        // Close icon click event
                        popup.find('.popup_close_icon').on('click', function() {
                            // Hide the overlay and popup
                            overlay.hide();
                            popup.hide();

                            // If it's a success, you can do additional actions here
                            if (jsonResponse.status) {
                                // For example, update some content on the page
                                $('#some_element').text('Success!');
                            }
                        });

                        // Automatically hide after 3 seconds
                        setTimeout(function() {
                            overlay.hide();
                            popup.hide();
                        }, 5000);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("Something went wrong");
                        console.log(xhr);
                        console.log(textStatus);
                        console.log(errorThrown);
                        // Handle the error
                    }
                });
            });

            /********** Upload Report - START ********/

            $(".file-input").change(function() {
                var browse_rpt_id = $(this).attr("id");
                var fileInput = document.getElementById(browse_rpt_id);
                var file = fileInput.files[0];
                var formData = new FormData();
                var id = $(this).data('id');

                formData.append(browse_rpt_id, file);
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('/Frontdesk/upload_prescription_report/' . $res->id); ?>/" + id + '/' + browse_rpt_id,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                    console.log(response);

                    try {
                        jsonResponse = $.parseJSON(response);
                        var sucesMsg = jsonResponse.message;
                        if (sucesMsg) {
                            showCustomPopup('Success', sucesMsg, true);
                        } else {
                            console.error('Success message not found in the JSON response');
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response: ' + e);
                    }
                },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error(errorThrown);
                        showCustomPopup('Error', 'An error occurred', false);
                        // Additional error handling if needed
                    }
                });
            });

            function showCustomPopup(type, message, isSuccess) {
                var popup = $('#custom_popup');
                var overlay = $('#overlay');
                var popupMessage = popup.find('.popup_message');

                popupMessage.text(message);

                if (isSuccess) {
                    popup.addClass('success');
                    // Set background color for success
                    popup.css('background-color', 'green');
                } else {
                    popup.addClass('error');
                    // Set background color for error
                    popup.css('background-color', 'red');
                }

                // Show the overlay and popup
                overlay.show();
                popup.show();

                setTimeout(function() {
                    overlay.hide();
                    popup.hide();

                    // If it's a success, you can do additional actions here
                    if (isSuccess) {
                        // For example, update some content on the page
                        $('#some_element').text('Success!');
                    }
                }, 5000);
            }

            /********** Upload Report - END ********/



            $('#goto_step4_btn').click(function(event) {
                var advice = $('#advice').val();
                var data = {
                    'advice': advice,
                    'patient_id': '<?php echo $res->id; ?>',
                };
                if ($.trim(advice) != '') {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('/Frontdesk/save_advice') ?>",
                        data: data,
                        dataType: 'text', //for 'string'
                        success: function(response) { // Handle the success response
                            var jsonResponse = $.parseJSON(response);
                            console.log(jsonResponse.message);
                            showCustomPopup(jsonResponse.status ? 'Success' : 'Error', jsonResponse.message);
                           
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log("Something went wrong");
                            console.log(xhr);
                            console.log(textStatus);
                            console.log(errorThrown);
                            showCustomPopup('Error', 'Something went wrong');
                        }
                    });
                } else {
                    // Show error popup if advice is empty
                    showCustomPopup('Error', 'Please Enter Advice');
                }
            });


            $('#save').click(function(event) {
                event.preventDefault();
                var slct_refr_usr = $('#selctarea').find('option:selected').text();
                var msg_frm_doc = $('#message').val();

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('/Frontdesk/save_message/') ?>",
                    data:{
                        slct_refr_usr:slct_refr_usr,
                        msg_frm_doc:msg_frm_doc,
                    },
                    dataType: 'text', //for 'string'
                    success: function(response) {
                        try {
                            var jsonResponse = $.parseJSON(response);

                            if (jsonResponse.message) {
                                console.log(jsonResponse.message);

                                // Use encodeURIComponent only if needed
                                var encodedMessage = encodeURIComponent(jsonResponse.message);

                                // Redirect to the new page with the success message as a query parameter
                                window.location.href = "<?= base_url('/Frontdesk/manage_patients')?>?sucesMsg=" + encodedMessage;
                            } else {
                                console.error("Missing success message in the JSON response.");
                                // Handle the case when there's no success message in the response
                            }
                        } catch (error) {
                            console.error("Error parsing JSON response:", error);
                            // Handle the case when the response is not valid JSON
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("Something went wrong");
                        console.log(xhr);
                        console.log(textStatus);
                        console.log(errorThrown);
                        showCustomPopup('Error', 'Something went wrong');
                    }

                });
            });

            function showCustomPopup(type, message) {
                // Create a popup container
                var popupContainer = document.createElement('div');
                popupContainer.classList.add('my-custom-popup');

                // Create close button
                var closeButton = document.createElement('span');
                closeButton.innerHTML = '&times;'; // HTML entity for the "times" symbol (cross)

                // Create popup content
                var popupContent = document.createElement('div');
                popupContent.classList.add('popup-inner-content');

                // Create combined title and message element
                var titleAndMessageElement = document.createElement('p');
                titleAndMessageElement.textContent = type + ': ' + message;

                // Set background color based on type
                if (type === 'Success') {
                    popupContainer.style.backgroundColor = 'green';
                } else if (type === 'Error') {
                    popupContainer.style.backgroundColor = 'red';
                }

                // Append combined title and message to content
                popupContent.appendChild(titleAndMessageElement);

                // // Append close button to popup container
                // popupContainer.appendChild(closeButton);

                // Append content to container
                popupContainer.appendChild(popupContent);

                // Append container to the body
                document.body.appendChild(popupContainer);

                // Close the popup after 2 seconds
                setTimeout(function() {
                    document.body.removeChild(popupContainer);
                }, 5000);
            }


            $('#doctor').change(function(event) {
                event.preventDefault();
                var selectedDoctorName = $(this).val();
                console.log(selectedDoctorName);

                var selectedDoctorObject = {
                    selectedDoctor: selectedDoctorName
                };
                console.log(selectedDoctorObject);
                var queryString = $.param(selectedDoctorObject);
                console.log(queryString);
            });

            console.log("This is testing phase");
            $('#date').change(function(event) {
                event.preventDefault(); // Prevents the default behavior of the change event
                var selectedDateValue = $(this).val(); // Get the value of the input element with id "datefrom"
                console.log(selectedDateValue);

                var selectedDateObject = {
                    selectedDate: selectedDateValue
                };
                console.log(selectedDateObject);
                var queryString = $.param(selectedDateObject);
                console.log(queryString); // Output: selectedDate=YYYY-MM-DD
            });


            $('#time-slots').change(function(event) {
                event.preventDefault();
                var selectedTimeSlot = $(this).val();
                console.log(selectedTimeSlot);

                var selectedTimeObject = {
                    selectedTime: selectedTimeSlot
                };
                console.log(selectedTimeObject);

                var queryString = $.param(selectedTimeObject);
                console.log(queryString);
            });
        })


        ///////////////////Download button will appear when clicking on the browse button///////////////////////////
        // Add event listeners to the file input elements
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach((fileInput, index) => {
            fileInput.addEventListener('change', (event) => {
                const selectedFile = event.target.files[0];
                const selectedFileName = selectedFile ? selectedFile.name : '';
                const downloadButton = document.getElementById(`downloadButton${index + 1}`);
                const selectedFileNameElement = document.getElementById(`selectedFileName${index + 1}`);

                if (selectedFileName) {
                    downloadButton.style.display = 'inline-block';
                    downloadButton.style.position = 'absolute';
                    downloadButton.style.left = '50%';

                    selectedFileNameElement.textContent = selectedFileName;
                } else {
                    downloadButton.style.display = 'none';
                    selectedFileNameElement.textContent = '';
                }
            });
        });

       
        ///////////////////Download button will appear when clicking on the browse button///////////////////////////
        ///////////////////Drop down/////////////////////////////////////////
        const reportToggle = document.getElementById('reportToggle');
        const reportSection = document.getElementById('reportSection');
        const reportToggleIcon = document.getElementById('reportToggleIcon');
        const addReportFieldButton = document.getElementById('addReportFieldButton');
        const reportFieldsContainer = document.getElementById('reportFieldsContainer');

        reportToggle.addEventListener('click', () => {
            if (reportSection.style.maxHeight) {
                // Section is open, so close it
                reportSection.style.maxHeight = null;
                reportToggleIcon.textContent = '⮞'; // Right arrow
            } else {
                // Section is closed, so open it
                reportSection.style.maxHeight = reportSection.scrollHeight + 'px';
                reportToggleIcon.textContent = '⮟'; // Down arrow
            }
        });

        // Add a new report field when the plus button is clicked
        addReportFieldButton.addEventListener('click', () => {
            const newReportField = document.createElement('div');
            newReportField.className = 'report-field';
            newReportField.innerHTML = `
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1 mrg_rgt">
                        <input type="checkbox">
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5">
                        <span>Other Report</span>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <input type="file" class="file-input" accept=".png, .PNG, .jpg, .JPG, .jpeg, .JPEG, .svg, .gif">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a class="btn-download" href="" download><i class="fa fa-download"></i></a>
                    </div>
                </div>
            `;
            reportFieldsContainer.appendChild(newReportField);
        });

        
        $(document).ready(function() {
            // Function to change line and circle colors for the currently active step
            function changeColors(stepNumber) {
                $('.form-stepper-list').each(function() {
                    if ($(this).attr('step') <= stepNumber) {
                        $(this).removeClass('form-stepper-unfinished text-muted').addClass('form-stepper-completed');
                        $(this).find('.form-stepper-circle').css('background-color', '#005197').css('color', '#fff');
                    } else {
                        // Reset line and circle colors for other steps
                        $(this).removeClass('form-stepper-completed').addClass('form-stepper-unfinished text-muted');
                        $(this).find('.form-stepper-circle').css('background-color', '').css('color', '');
                    }
                });
            }

            // Initialize the current step to 1
            var currentStep = 1;

            // Step 1 skip
            $('#skip_step1_btn').on('click', function() {
                $('.presc-dummy').hide();
                $('#step-2').show();
                currentStep = 2;
                // Change line and circle colors for Step 1 and 2
                changeColors(currentStep);
            });

            // Step 2 skip
            $('#skip_step2_btn').on('click', function() {
                $('.presc-dummy').hide();
                $('#step-3').show();
                currentStep = 3;
                // Change line and circle colors for Step 3
                changeColors(currentStep);
            });

            // Step 3 skip
            $('#skip_step3_btn').on('click', function() {
                $('.presc-dummy').hide();
                $('#step-4').show();
                currentStep = 4;
                // Change line and circle colors for Step 4
                changeColors(currentStep);
            });

            // Step 4 skip
            $('#skip_step4_btn').on('click', function() {
                $('.presc-dummy').hide();
                $('#step-5').show();
                currentStep = 5;
                // Change line and circle colors for Step 5
                changeColors(currentStep);
            });
        });

        //$('#goto_step3_btn').click
        $("#goto_step3_btn").on("click", function() {
            var selected_report = '';
            var checked = []
            $("input[name='report[]']:checked").each(function() {
                if (!$(this).attr('disabled')) {

                    checked.push($(this).val());
                }
            });

            if (checked.length != 0) {
                selected_report = checked.join();
                var dataArr = {
                    'selected_report': selected_report,
                    'patient_id': '<?php echo $res->id; ?>',
                }
                var formData = $.param(dataArr);
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('/Frontdesk/add_prescription_report/' . $res->id); ?>",
                    data: dataArr,
                    dataType: 'text', //for 'string'
                    success: function(response) { // Handle the success response
                        var jsonResponse = $.parseJSON(response);
                        console.log(jsonResponse.message);
                        showCustomPopup(jsonResponse.status ? 'Success' : 'Error', jsonResponse.message);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("Something went wrong");
                        console.log(xhr);
                        console.log(textStatus);
                        console.log(errorThrown);
                        showCustomPopup('Error', 'An error occurred');
                    }
                });
            }
        });

        function showCustomPopup(type, message) {
            // Create a popup container
            var popupContainer = document.createElement('div');
            popupContainer.classList.add('my-custom-popup');

            // Create close button
            var closeButton = document.createElement('span');
            closeButton.innerHTML = '&times;'; // HTML entity for the "times" symbol (cross)

            // Create popup content
            var popupContent = document.createElement('div');
            popupContent.classList.add('popup-inner-content');

            // Create combined title and message element
            var titleAndMessageElement = document.createElement('p');
            titleAndMessageElement.textContent = type + ': ' + message;

            // Set background color based on type
            if (type === 'Success') {
                popupContainer.style.backgroundColor = 'green';
            } else if (type === 'Error') {
                popupContainer.style.backgroundColor = 'red';
            }

            // Append combined title and message to content
            popupContent.appendChild(titleAndMessageElement);

            // // Append close button to popup container
            // popupContainer.appendChild(closeButton);

            // Append content to container
            popupContainer.appendChild(popupContent);

            // Append container to the body
            document.body.appendChild(popupContainer);

            // Close the popup after 2 seconds
            setTimeout(function() {
                document.body.removeChild(popupContainer);
            }, 5000);
        }



        ////////////////////////////////////////////////////////////////////////


        // JavaScript to handle step navigation and styling
        document.addEventListener('DOMContentLoaded', function() {
            const formStepper = document.querySelectorAll('.form-stepper-list');
            const formStepContent = document.querySelectorAll('.form-step');

            formStepper.forEach(step => {
                step.addEventListener('click', function() {
                    const stepNumber = step.getAttribute('step');

                    // Remove active and completed classes from all steps
                    formStepper.forEach(item => {
                        item.classList.remove('form-stepper-active', 'form-stepper-completed');
                    });

                    // Add active class to the clicked step
                    step.classList.add('form-stepper-active');

                    // Mark previous steps as completed
                    for (let i = 1; i < stepNumber; i++) {
                        formStepper[i - 1].classList.add('form-stepper-completed');
                    }

                    // Show the corresponding step content
                    formStepContent.forEach(content => {
                        content.classList.add('d-none');
                    });

                    const activeStepContent = document.getElementById(`step-${stepNumber}`);
                    activeStepContent.classList.remove('d-none');
                });
            });
        });
    </script>

    <?= view('Frontdesk/date_picker_js_file.php'); ?>
    <?= view('Doctor/add_patient_prescription_js.php'); ?>

</body>

</html>