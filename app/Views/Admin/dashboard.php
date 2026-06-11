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
    <title>Admin Dashboard</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
    <link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
    <?= helper('Form'); ?>
    <?//= view('Admin/css_file.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
    <?= view('Admin/customdelete_popup_css_file.php'); ?>
</head>

<body>
    <?= view('Admin/top_bar'); ?>
    <!---Body Section Start --->

    <!---4Cards Section Start -->
    <div class="equl_mrgn">
    <div class="row" id="dshbrd_mgn">
        <div class="col l3 m12 s12">
            
            <!-- Doctors - Card Section START -->
            <div class="card bckgrnd_gren">
                <div class="card-content">
                    <a href="<?= base_url('Admin/manage_doctor'); ?>" class="dshbrd_sty">
                        <div class="row mrg_botm">
                            <div class="col l4 m4 s4">
                                <h4><span class="fa fa-user-md col_wite"></span></h4>
                            </div>
                            <div class="col l8 m8 s8">
                                <h6 class="right-align" id="info_box">Total Doctors</h6>
                                <h4 class="right-align info_count">
                                <?php if (is_array($doctors)) :
                                        echo count($doctors);
                                        ?>
                                    <?php else : ?>
                                        <span class="right-align col_wite">0</span>
                                    <?php endif; ?>

                                </h4>

                            </div>
                        </div>
                </div>
                <div class="card-action" id="info_box">
                    View Doctors</a>
                </div>
            </div>
            <!-- Doctors - Card Section END -->
        </div>
        <div class="col l3 m12 s12">
            <!--Patient - Card Section START -->
            <div class="card bckgrnd_red">
                <div class="card-content">
                    <div class="row mrg_botm">
                        <div class="col l4 m4 s4">
                            <a href="<?= base_url('Admin/manage_patients'); ?>" class="dshbrd_sty">
                                <h4><span class="fa fa-wheelchair col_wite"></span></h4>
                        </div>
                        <div class="col l8 m8 s8">
                            <h6 class="right-align" id="info_box">Total Patients</h6>
                            <h4 class="right-align info_count">
                                <?php if (is_array($patients)) : ?>
                                    <?php echo count($patients); ?>
                                <?php else : ?>
                                    <span class="right-align col_wite">0</span>
                                <?php endif; ?>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-action" id="info_box">
                    View Patients</a>
                </div>
            </div>
            <!--Patient - Card Section END -->

        </div>
        <div class="col l3 m12 s12">
            <!-- Medical Income - Card Section START -->
            <div class="card bckgrnd_orng">
                <div class="card-content">
                    <div class="row mrg_botm">
                        <div class="col l4 m4 s4">
                            <a target="_blank" href="<?= base_url('Admin/all_sale_reports'); ?>" class="dshbrd_sty">
                                <h4><span class="fa fa-rupee col_wite"></span></h4>
                        </div>
                        <div class="col l8 m8 s8">
                            <h6 class="right-align" id="info_box">Medical Income</h6>
                            <h4 class="right-align info_count">
                                <?php if (is_array($chart_data) && isset($chart_data['ch_medical_earning'])) :
                                    echo $chart_data['ch_medical_earning'];
                                ?>
                                <?php else : ?>
                                    <span class="col_wite">0</span>
                                <?php endif; ?>
                            </h4>

                        </div>
                    </div>
                </div>
                <div class="card-action" id="info_box">
                    View Medical Income</a>
                </div>
            </div>
            <!-- Medical Income - Card Section END -->
        </div>
        <div class="col l3 m12 s12">
            <!-- Patients Income - Card Section START -->
            <div class="card bckgrnd_lg_blu">
                <div class="card-content">
                    <a href="<?= base_url('Admin/all_sale_reports'); ?>" class="dshbrd_sty">
                        <div class="row mrg_botm">
                            <div class="col l1 m1 s1">
                                <h4><span class="fa fa-rupee col_wite"></span></h4>
                            </div>
                            <div class="col l11 m11 s11">
                                <h6 class="right-align" id="info_box">Patient's Treatment Income</h6>
                                <h4 class="right-align info_count">
                                    <?php if (isset($chart_data['ch_patient_earning'])) :
                                        echo $chart_data['ch_patient_earning'];
                                    ?>
                                    <?php else : ?>
                                        <span class="col_wite">0</span>
                                    <?php endif; ?>
                                </h4>

                            </div>
                        </div>
                </div>
                <div class="card-action" id="info_box">
                    View Patient's Treatment Income</a>
                </div>
            </div>
            <!-- Patients Income - Card Section END -->
        </div>
    </div>



    <!----Chart Section Script Start ---->
    <div class="row">
        <div class="col l3 m3 s12">
            <div class="card">
                <div class="card-content">
                    <div id="chartContainer" class="div_higt"></div>
                </div>
            </div>
        </div>
        <div class="col l9 m9 s12">
            <div class="card">
                <div class="card-content" id="brdr_botm_silvr">
                    <h6 class="h6_sty">
                        <a class="colour_hver" href="<?= base_url('Admin/all_appointments'); ?>">
                            <?php if ($all_appointments) : ?>
                            <span class="span_sty">All Appointment (
                                <?php if (is_array($all_appointments)) :
                                    echo count($all_appointments); ?>
                                <?php endif; ?>
                                )
                            </span>
                        </a>
                    </h6>
                </div>      
                <div class="scroll-container card-content">
                    <table class="table ovrflw_content">
                        <tr class="backgrnd_colr_gray">
                            <th>Serial</th>
                            <th>Patient</th>
                            <th>#Puid</th>
                            <th>Doctor</th>
                            <th>Mobile</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php if (is_array($all_appointments)) :
                            count($all_appointments); ?>
                            <?php foreach ($all_appointments as $t_app) :
                                if (!isset($t_app->puid) || ($t_app->puid == '')) {
                                    $t_app->puid = 0;
                                }
                            ?>
                                <tr>
                                    <td class="txt_break-at100">
                                        <?= $t_app->serial; ?>
                                    </td>
                                    <td class="text-container">
                                        <?= $t_app->patient_name; ?>
                                    </td>
                                    <td class="text-container"><?php if (isset($t_app->puid) && $t_app->puid != 0) {
                                                                    echo $t_app->puid;
                                                                } else if (!isset($t_app->puid) || $t_app->puid == 0) { ?>
                                            <span class="col_gren"> <?php echo "New";
                                                                    } ?></span>
                                    </td>

                                    <td class="txt_break-at300">
                                        <?php
                                        $get_doctor = get_doctor_name('doctor', $t_app->doctor_id);
                                        if (is_array($get_doctor) && isset($get_doctor[0]->doctor_name)) {
                                            echo $get_doctor[0]->doctor_name;
                                        } else {
                                            echo "Unknown Dr.";
                                        }
                                        ?>
                                    </td>
                                    <td class="txt_break-at300">
                                        <a class="colour_hver" href="tel:<?= $t_app->patient_mobile; ?>"><?= $t_app->patient_mobile; ?></a>
                                    </td>
                                    <td class="txt_break-at300">
                                        <span class="col_ornge"> <?= date('d M, Y', strtotime($t_app->booking_date)); ?></span>
                                    </td>
                                    <td class="txt_break-at300">
                                        <span class="col_gren"> <?= $t_app->booking_time; ?></span>
                                    </td>
                                    
                                    <td class="txt_break-at300">
                                        <?php if ($t_app->status == 0) : //0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: Other
                                            echo '<span class="col_red">Cancelled</span>';
                                        elseif ($t_app->status == 1) :
                                            echo '<span class="col_gren">Appointment</span>';
                                        elseif ($t_app->status == 2) :
                                            echo '<span class="col_red">Deleted</span>';
                                        elseif ($t_app->status == 3) :
                                            echo '<span class="col_ornge">Waiting</span>';
                                        elseif ($t_app->status == 4) :
                                            echo '<span class="col_gren">Fee Paid</span>';
                                        elseif ($t_app->status == 5) :
                                            echo '<span class="col_ornge">Absent</span>';
                                        elseif ($t_app->status == 6) :
                                            echo '<span class="col_red">Unknown</span>';
                                        else :
                                            echo '<span class="span_red_colr">InActive </span>';
                                        ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <center>
                                            <a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_app->id; ?>"><span class="fa fa-ellipsis-v"></span></a>
                                        </center>

                                        <!---Action Dropdown --->
                                        <ul class="dropdown-content action_dropdown" class="dropdown_style" id="action_dropdown_<?= $t_app->id; ?>" class="down">

                                        <?php if ($t_app->status == 0) :  ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span>  Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
														
										<?php elseif ($t_app->status == 1) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											
											<li><a href="<?= base_url('Admin/add_fee/' . $t_app->id . '/4' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial) . '/' . $t_app->doctor_id; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>						
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color:red;"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
								
										<?php elseif ($t_app->status == 2) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban"></span> Cancel</a></li>
										    <li><a href="<?= base_url('Admin/permanent_del_all_apmnt/' . $t_app->id); ?>"id="dotted_border" class="permanent-delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
										
												
										<?php elseif ($t_app->status == 3) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color: red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
										<?php elseif ($t_app->status == 4) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color: red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>"id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
									
										<?php elseif ($t_app->status == 5) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color: red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
																
										<?php elseif ($t_app->status == 6) : ?>
											<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/1' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/0' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" style="color:red;" id="dotted_border"><span class="fa  fa-ban" style="color: red"></span> Cancel</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/3' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/5' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/2' . '/' . $t_app->pid . '/' . $t_app->puid . '/' . $t_app->serial); ?>" id="dotted_border" class="delete-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
																
										<?php else : ?>
											<li><a href="<?= base_url('Admin/change_appointment_status_dsbrd/' . $t_app->id . '/Appointment'); ?>" id="dotted_border">
													<span class="fa fa-eye col_blu"></span> Appointment</a>
											</li><!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<?php endif; ?>
                                        </ul>
                                        <!---Action Dropdown --->
                                        <!-- Hidden delete appointment confirmation modal -->
                                        <div id="deleteAppointmentConfirmationModal" class="modal">
                                            <div class="modal-content">
                                                <span id="cancelDeleteAppointment" class="modal-icon cancel" onclick="hideDeleteAppointmentConfirmationModal();">&#x2716;</span>
                                                <p class="del_pop_text">Are you sure you want to delete this appointment?</p>
                                                <div class="modal-buttons align_del_btn">
                                                    <button id="confirmDeleteAppointment" class="modal-button delete">Delete</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden permanent delete all appointments confirmation modal -->
                                        <div id="permanentDeleteAllAppointmentsConfirmationModal" class="modal">
                                            <div class="modal-content">
                                                <span id="cancelPermanentDeleteAllAppointments" class="modal-icon cancel" onclick="hidePermanentDeleteAllAppointmentsConfirmationModal();">&#x2716;</span>
                                                <p class="del_pop_text">Are you sure you want to permanently delete all appointments?</p>
                                                <div class="modal-buttons align_del_btn">
                                                    <button id="confirmPermanentDeleteAllAppointments" class="modal-button delete">Permanent Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <h6 class="h6_red_colr">Not any Appointment</h6>
                        <?php endif; ?>
                        <!-- <tr>
					<td colspan="7">
						<div id="pagination" style="color: white">
							<//?= $pager->links(); 
                            ?>
						</div>
					</td>
				</tr> -->
                    </table>
                    <?php else : ?>
				<h6 class="col_red">No Record Found</h6>
			<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!----Chart Section Script End ---->


    <!---Dropdown Section Start --->
    <ul class="dropdown-content" id="income_dropdown">
        <li id="dotted_border"><a href="#!" onclick="count_income('today')">Today Income</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_income('yesterday')">Previous Day Income</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_income('last_30_days')">Last 30 Days Income</a></li>
        <div class="divider"></div>
        <li id="dotted_border"><a href="#!" onclick="count_income('all')">All Income</a></li>
    </ul>
    <ul class="dropdown-content" id="medical_income">
        <li id="dotted_border"><a href="#!" onclick="count_medical_income('today')">Today Income</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_medical_income('yesterday')">Previous Day Income</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_medical_income('last_30_days')">Last 30 Days Income</a></li>
        <div class="divider"></div>
        <li id="dotted_border"><a href="#!" onclick="count_medical_income('all')">All Day Income</a></li>
    </ul>
    <ul class="dropdown-content" id="patients_dropdown">
        <li id="dotted_border"><a href="#!" onclick="count_patients('today')">Today Patients</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_patients('yesterday')">Previous Day Patients</a></li>
        <li id="dotted_border"><a href="#!" onclick="count_patients('last_30_days')">Last 30 Days Patients</a></li>
        <div class="divider"></div>
        <li id="dotted_border"><a href="#!" onclick="count_patients('all')">All Patients</a></li>
    </ul>
    </div>
    <!---Dropdown Section End --->

    <!---Body Section End --->
    <?= view('Admin/js_file.php'); ?>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
   
    <script>
    $(document).ready(function() {
        ////////////Delete Popup///////////////////
        var deleteAppointmentUrl;

        $('.delete-appointment-link').on('click', function(e) {
            e.preventDefault();
            deleteAppointmentUrl = $(this).attr('href');

            // Show the custom delete appointment confirmation modal
            $('#deleteAppointmentConfirmationModal').show();
        });

        $('#cancelDeleteAppointment').on('click', function() {
            // Hide the custom delete appointment confirmation modal when cancel is clicked
            $('#deleteAppointmentConfirmationModal').hide();
        });

        $('#confirmDeleteAppointment').on('click', function() {
            // Perform the deletion of the appointment and redirect
            window.location.href = deleteAppointmentUrl;
        });


        /////////////Permanent Delete Popup////////////////////////

        var permanentDeleteAllAppointmentsUrl;

        $('.permanent-delete-appointment-link').on('click', function(e) {
            e.preventDefault();
            permanentDeleteAllAppointmentsUrl = $(this).attr('href');

            // Show the custom permanent delete all appointments confirmation modal
            $('#permanentDeleteAllAppointmentsConfirmationModal').show();
        });

        $('#cancelPermanentDeleteAllAppointments').on('click', function() {
            // Hide the custom permanent delete all appointments confirmation modal when cancel is clicked
            $('#permanentDeleteAllAppointmentsConfirmationModal').hide();
        });

        $('#confirmPermanentDeleteAllAppointments').on('click', function() {
            // Perform the permanent deletion of all appointments and redirect
            window.location.href = permanentDeleteAllAppointmentsUrl;
        });
    });

    function hideDeleteAppointmentConfirmationModal() {
        // Hide the custom delete appointment confirmation modal when the modal close icon is clicked
        $('#deleteAppointmentConfirmationModal').hide();
    }

    function hidePermanentDeleteAllAppointmentsConfirmationModal() {
        // Hide the custom permanent delete all appointments confirmation modal when the modal close icon is clicked
        $('#permanentDeleteAllAppointmentsConfirmationModal').hide();
    }
    ///SUCCESS MESSAGE JS /////
	    setTimeout(function() {
			var sucesMsgs = document.querySelectorAll('#suces_msg');
			var errMsg = document.querySelectorAll('#eror_msg');

			sucesMsgs.forEach(function(message) {
			message.style.display = 'none';
			});

			errMsg.forEach(function(message) {
			message.style.display = 'none';
			});
		}, 5000); // 5000 milliseconds = 5 seconds

		///SUCCESS MESSAGE JS /////
</script>
    <!---Custome Js file Include --->
    <?= view('Admin/custom_js.php'); ?>
    <!---Custome Js file Include --->


</body>

</html>