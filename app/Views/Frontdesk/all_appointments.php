<!DOCTYPE html>
<html>

<head>
    <title>All Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.min.css">
    <link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="referrer" content="no-referrer">
    <!---CSS File Include  -->
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <!---CSS File Include  -->
    <?= view('Admin/Account/dashed_bottom_border_css_file.php'); ?>
    <?= view('Frontdesk/frontdesk_css_file.php'); ?>
    <?= view('Admin/customdelete_popup_css_file.php'); ?>
    
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include jQuery UI library -->
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <!-- Include jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/smoothness/jquery-ui.css">
</head>

<body>
    <!--Top Bar Section Include --->
    <?= view('Blood_bank/Donor/top_bar'); ?>
    <!--Top Bar Section Include --->
    <!---Body Section Start --->
    <div class="equl_mrgn">
        <div class="card">
            <div id="succ_err_msg"></div>
        <div>
			<?php
			if (session()->getTempdata('success')) {
				// Display the success message
				?>
				<div class="card success cutom_messge_styl bckgrnd_gren">
					<div class="card-content" id="suces_msg"><?= session()->getTempdata('success'); ?></div>
				</div>
				<?php
				// Remove the success message from session
				session()->removeTempdata('success');
			}
			
			if (session()->getTempdata('error')) {
				// Display the error message
				?>
				<div class="card error cutom_messge_styl bckgrnd_red">
					<div class="card-content" id="eror_msg"><?= session()->getTempdata('error'); ?></div>
				</div>
				<?php
				// Remove the error message from session
				session()->removeTempdata('error');
			}
			?>
		</div>
            <div class="card-content" id="brdr_botm_silvr">
                <h5 class="font_weght"><span class="fa fa-calendar col_blu"></span>  All Appointments</h5>
            </div>
            <div class="card-content" id="brdr_botm_silvr">
                <?php if ($all_appointments) : ?>
                    <!--Search Bar & Filter Bar Section Start -->
                    <div class="row">
                        <div class="col l4 m4 s12">
                            <?= form_open('Frontdesk/search_all_appointments'); ?>
                            <ul id="search_doctor">
                                <li>
                                    <div class="input-container">
                                        <input type="text" name="patient_name" class="serch_area" id="input_box" value="<?= set_value('patient_name'); ?>" placeholder="Enter Patients Name" required="">
                                        <span class="clear-input" id="clear-input">&times;</span>
									</div>
                                </li>
                                <li>
                                    <button type="submit" class="btn waves-effect waves-light btn_hver buton_blu">Search Now</button>
                                </li>
                            </ul>
                            <?= form_close(); ?>
                        </div>
                        <div class="col l6 m12 s12 date_div_container">
                            <div class="row date_div">
                                <?= form_open('Frontdesk/search_doc_appointment'); ?>
                                <div class="custom-date-range-picker">
                                    <div class="col l3 m3 s12">
                                        <input type="date" id="datefrom" name="datefrom" class="dt_custm" placeholder="Start Date">
                                    </div>
                                    <div class="col l3 m3 s12">
                                        <input type="date" id="dateto" name="dateto" id="dateto" class="dt_custm" placeholder="End Date">
                                    </div>
                                </div>
                                <div class="col l6 m6 s12">
                                    <select class="filter_doc" name="doctor_name" id="doctor">
                                        <option selected="" disabled="">Select Doctor</option>
                               <?php if (isset($doctors) && is_array($doctors) && count($doctors) > 0) : ?>
                                        <?php foreach ($doctors as $doc) : ?>
                                            <option value="<?= $doc->id; ?>"><?= $doc->doctor_name; ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <h6 class="h6_record col_red">Doctor Not Found</h6>
                                <?php endif; ?>

                                    </select>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                        <!-- <div class="row"> -->
                        <div class="col l2 m2 s12">
                            <span class="right">
                                <button type="button" class="btn waves-effect waves-light dropdown-trigger btn_hver filter_btn" data-target="doctor_filter">
                                    <span class="fa fa-filter"></span>  Filter Appointments
                                </button>
                            </span>
                            <!---Student filter -->
                            <ul class="dropdown-content" id="doctor_filter">

                                <li><a href="<?= base_url('Frontdesk/filter_appointment/new_appointment'); ?>" class="waves-effect" id="dotted_border">
                                        <span class="fa fa-users col_blu"></span>  New Appointment </a></li>
                                <li><a href="<?= base_url('Frontdesk/filter_appointment/old_appointment'); ?>" class="waves-effect">
                                        <span class="fa fa-users col_blu"></span>  Old Appointment </a></li>
                            </ul>
                        </div>
                        <!--Search Bar & Filter Bar Section End -->
                    </div>
            </div>
            <div class="scroll-container card-content">
                <table class="table">
                 
                    <tr class="backgrnd_colr_gray">
                        <th>Serial</th>
                        <th class="txt_align">Patient Name</th>
                        <th class="txt_align">#Puid</th>
                        <th class="txt_align"> Email</th>
                        <th class="txt_align"> Mobile</th>
                        <th class="txt_align">Doctor </th>
                        <th class="txt_align">Date</th>
                        <th class="txt_align">Time-Slot</th>
                        <th class="txt_align">Symptomps</th>
                        <th class="txt_align">Status</th>
                        <th class="txt_align">Registered</th>
                        <th class="txt_align">Action</th>
                    </tr>
                    <?php if (isset($all_appointments) && (is_array($all_appointments) || is_object($all_appointments))) :
                        if (count($all_appointments) == 0) { ?> <h6 class="h6_record col_red">No record found</h6>
                        <?php }
                        foreach ($all_appointments as $t_patient) :
                            if (!isset($t_patient['puid']) || ($t_patient['puid'] == '')) {
                                $t_patient['puid'] = 0;
                            }
                        ?>
                            <tr>
                                <td class="txt_break-at100"><?= $t_patient['serial']; ?></td> 
                                <td class="txt_break-at300 txt_align"> <?= $t_patient['patient_name']; ?></td>
                                <td class="text-container txt_align">
                                    <?php 
                                        if (isset($t_patient['puid']) && $t_patient['puid'] != 0){ 
                                            echo $t_patient['puid']; 
                                        }
                                        else if (!isset($t_patient['puid']) || $t_patient['puid'] == 0) { 
                                    ?>
                                            <span class="col_gren"> 
                                        <?php echo "New"; 
                                        } 
                                        ?>  </span></td>
                                <td class="text-container txt_align"><a class="colour_hver" href="mailto:<?= $t_patient['patient_email']; ?>"><?= $t_patient['patient_email']; ?></a> </td>                              
                                <td class="txt_break-at300 txt_align"><a class="link_hver" href="tel:<?= $t_patient['patient_mobile']; ?>"><?= $t_patient['patient_mobile']; ?></a> </td>                         
                                <td class="txt_break-at300 txt_align"><?= $t_patient['doctor_name']; ?> </td>
                                <td class="txt_break-at300 txt_align"><span class="col_gren">  <?= date('d M, Y', strtotime($t_patient['booking_date'])); ?></span></td>
                                <td class="txt_break-at300 txt_align"><span>  <?= $t_patient['booking_time']; ?></span></td>                              
                                <td class="txt_break-at300 txt_align" class="col_ornge"> <?= $t_patient['disease_symptoms']; ?></td>
                                 
                                <td class="txt_break-at300 txt_align">
                                    <?php
                                        if ($t_patient['status'] == 0) :
                                            echo '<span class="col_red">Cancelled</span>';
                                        elseif ($t_patient['status'] == 1) :
                                            echo '<span class="col_gren">Appointment</span>';
                                        elseif ($t_patient['status'] == 2) :
                                            echo '<span class="col_red">Deleted</span>';
                                        elseif ($t_patient['status'] == 3) :
                                            echo '<span class="col_ornge">Awaited</span>';
                                        elseif ($t_patient['status'] == 4) :
                                            echo '<span class="col_gren">Fee Paid</span>';
                                        elseif ($t_patient['status'] == 5) :
                                            echo '<span class="col_ornge">Absent</span>';
                                        elseif ($t_patient['status'] == 6) :
                                            echo '<span class="col_ornge">Other</span>';
                                        else : 
                                            echo '<span class="col_red">Unknown</span>';
                                    ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-container_mange_patient txt_align">
                                    <?php 
                                        if($t_patient['pid']!=0){ 
                                            echo "<span style='color:green' class='col_gren'>Yes</span>"; 
                                        }
                                        else{
                                            echo "<span style='color:red' class='col_red'>No</span>"; 
                                        }
                                    ?>
                                </td>
                                <td>
                                    <center>
                                        <a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_<?= $t_patient['id']; ?>"><span class="fa fa-ellipsis-v"></span></a>
                                    </center>

                                    <!---Action Dropdown --->
                                    <ul class="dropdown-content action_dropdown" id="action_dropdown_<?= $t_patient['id']; ?>" id="dotted_border">

                                    <?php if ($t_patient['status'] == 0) :  ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span>  Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li> 
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" 	id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
										
									<?php elseif ($t_patient['status'] == 1) : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/add_fee/' . $t_patient['id'] . '/4' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']) . '/' . $t_patient['doctor_id']; ?>" id="dotted_border" target="_blank"><span class=" fa  fa-check "></span> Add Doctor Fee</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
												
									<?php elseif ($t_patient['status'] == "2") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border">	<span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/permanent_del_today_apmnt/' . $t_patient['id'] . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial']); ?>" id="dotted_border" class="permanent-delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Permanent Delete</a></li>
									
									<?php elseif ($t_patient['status'] == "3") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "4") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php elseif ($t_patient['status'] == "5") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>	
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete	</a></li>
												
									<?php elseif ($t_patient['status'] == "6") : ?>
										<!--0: Cancelled, 1: Appointment, 2: Deleted, 3: Awaited, 4: Attended, 5: Patient Not Reached, 6: View -->
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/1' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-calendar"></span> Appointment</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/0' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" style="color:red;" id="dotted_border"><span class="fa fa-ban col_red"></span> Cancel</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/3' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-hourglass-o"></span> Waiting</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/5' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border"><span class="fa fa-exclamation-triangle"></span> Absent</a></li>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/2' . '/' . $t_patient['pid'] . '/' . $t_patient['puid'] . '/' . $t_patient['serial'] . '/'.$t_patient['doctor_id']); ?>" id="dotted_border" class="delete-today-appointment-link" style="color:red;"><span class="fa fa-trash col_red"></span> Delete</a></li>
											
									<?php else : ?>
										<li><a href="<?= base_url('Frontdesk/change_all_appointments_status/' . $t_patient['id'] . '/Appointment'); ?>" id="dotted_border"><span class="fa fa-calendar col_blu"></span> Appointment</a></li>
									<?php endif; ?>

                                    </ul>
                                    <!---Action Dropdown --->
                                    <!-- Hidden delete patient appointments confirmation modal -->
                                        <div id="deletePatientAppointmentsConfirmationModal" class="modal">
                                            <div class="modal-content">
                                                <span id="cancelDeletePatientAppointments" class="modal-icon cancel" onclick="hideDeletePatientAppointmentsConfirmationModal();">&#x2716;</span>
                                                <p class="del_pop_text">Are you sure you want to delete all appointments for this patient?</p>
                                                <div class="modal-buttons align_del_btn">
                                                    <button id="confirmDeletePatientAppointments" class="modal-button delete">Delete</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden permanent delete patient appointments confirmation modal -->
                                        <div id="permanentDeletePatientAppointmentsConfirmationModal" class="modal">
                                            <div class="modal-content">
                                                <span id="cancelPermanentDeletePatientAppointments" class="modal-icon cancel" onclick="hidePermanentDeletePatientAppointmentsConfirmationModal();">&#x2716;</span>
                                                <p class="del_pop_text">Are you sure you want to permanently delete all appointments for this patient?</p>
                                                <div class="modal-buttons align_del_btn">
                                                    <button id="confirmPermanentDeletePatientAppointments" class="modal-button delete">Permanent Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h6 class="h6_record col_red">Not any Appointment</h6>
                    <?php endif; ?>
                    <tr>
                        <td colspan="7">
                            <div id="pagination" class="col_wite">
                                <?php if (isset($pager)) {
                                    echo $pager->links();
                                } ?>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php else : ?>
                <h6 class="h6_record col_red">No Record Found</h6>
            <?php endif; ?>

            <script>
                $(document).ready(function() {
                    var inputBox = $('#input_box');
                    var clearInput = $('#clear-input');
                    var basePath = 'all_appointments';

                    clearInput.on('click', function () {
                        inputBox.val('');
                        clearInput.hide();
                        if (!containsBasePath(window.location.href, basePath)) {
                            window.history.back();
                        }
                    });

                    inputBox.on('input', function () {
                        if (inputBox.val().trim() !== '') {
                            clearInput.show();
                        } else {
                            clearInput.hide();
                        }

                        if (!containsBasePath(window.location.href, basePath) && inputBox.val().trim() === '') {
                            window.history.back();
                        }
                    });

                    // Initial check to hide the clear-input if the input is empty
                    if (inputBox.val().trim() === '') {
                        clearInput.hide();
                    }

                    function containsBasePath(url, basePath) {
                        // Check if the base path is exactly equal to the URL
                        return url.endsWith('/' + basePath) || url === basePath;
                    }
                    /////////Delete Popup///////////////
                                var deletePatientAppointmentsUrl;

                    $('.delete-patient-appointments-link').on('click', function(e) {
                        e.preventDefault();
                        deletePatientAppointmentsUrl = $(this).attr('href');

                        // Show the custom delete patient appointments confirmation modal
                        $('#deletePatientAppointmentsConfirmationModal').show();
                    });

                    $('#cancelDeletePatientAppointments').on('click', function() {
                        // Hide the custom delete patient appointments confirmation modal when cancel is clicked
                        $('#deletePatientAppointmentsConfirmationModal').hide();
                    });

                    $('#confirmDeletePatientAppointments').on('click', function() {
                        // Perform the deletion of patient's appointments and redirect
                        window.location.href = deletePatientAppointmentsUrl;
                    });
                    /////////////////////////////Delete Popup//////////////////////////////

                    /////////////Permanent Delete Popup///////////////////////////////////
                    var permanentDeletePatientAppointmentsUrl;

                    $('.permanent-delete-patient-appointments-link').on('click', function(e) {
                        e.preventDefault();
                        permanentDeletePatientAppointmentsUrl = $(this).attr('href');

                        // Show the custom permanent delete patient appointments confirmation modal
                        $('#permanentDeletePatientAppointmentsConfirmationModal').show();
                    });

                    $('#cancelPermanentDeletePatientAppointments').on('click', function() {
                        // Hide the custom permanent delete patient appointments confirmation modal when cancel is clicked
                        $('#permanentDeletePatientAppointmentsConfirmationModal').hide();
                    });

                    $('#confirmPermanentDeletePatientAppointments').on('click', function() {
                        // Perform the permanent deletion of patient's appointments and redirect
                        window.location.href = permanentDeletePatientAppointmentsUrl;
                    });
                    /////////////Permanent Delete Popup///////////////////////////////////

                    $('#doctor').on('change', function () {
                        var doctorName = $(this).find('option:selected').text();
                        var doctorId = $(this).find('option:selected').val();
                        var startDate = $('#datefrom').val();
                        var endDate = $('#dateto').val();

                        $.ajax({
                            type: 'GET',
                            url: "<?= base_url('/Frontdesk/all_appointments_from_to_date/') ?>",
                            data: {
                                doctorName: doctorName,
                                doctorId: doctorId,
                                startDate: startDate,
                                endDate: endDate,
                            },
                            dataType: 'text',
                            success: function (response) {
                                try {
                                    var jsonResponse = $.parseJSON(response);
                                    if (jsonResponse.status) {
                                        var appointments = jsonResponse.data.all_appointments; 
                                        populateTable(appointments);
                                        $('#succ_err_msg').html(jsonResponse.message).show() 
					                	.addClass('div_pad cutom_messge_styl bckgrnd_gren col_wite');

                                    } else {
                                        console.log("Error in the response:", jsonResponse.message);
                                        $('#succ_err_msg').html(jsonResponse.message).show() 
					                	.addClass('div_pad cutom_messge_styl bckgrnd_red col_wite');
                                        populateTable('');
                                        //alert("Sorry! No Appointment is availble between selected dates", jsonResponse.error);

                                    }
                                    setTimeout(function() {
                                        $('#succ_err_msg').hide();
                                    }, 5000);
                                } catch (error) {
                                    console.error("Error parsing JSON:", error);
                                   
                                }
                            },
                            error: function (xhr, textStatus, errorThrown) {
                                console.log("Something went wrong");
                                console.log(xhr);
                                console.log(textStatus);
                            }
                            
                        });

                      function populateTable(appointments) {
                        console.log("Populating table with data:", appointments);
                        var tableBody = $('.table tbody');

                        // Clear existing table rows
                        tableBody.find('tr:gt(0)').remove();

                        // Iterate through each appointment and populate the table
                        for (var index = 0; index < appointments.length; index++) {
                            var appointment = appointments[index];
                            var newRow = $('<tr>');
                            newRow.append('<td>' + (index + 1) + '</td>');
                            newRow.append('<td class="txt_align">' + appointment.patient_name + '</td>');
                            var puidCell = $('<td class="txt_align"></td>');
                            var puidValue = appointment.puid !== null && appointment.puid !== '0' ? appointment.puid : 'New';
                            puidCell.html('<span style="color:' + (puidValue === 'New' ? 'green' : 'inherit') + '">' + puidValue + '</span>');
                            newRow.append(puidCell);
                            newRow.append('<td class="txt_align col_gry">' + appointment.patient_email + '</td>');
                            newRow.append('<td class="txt_align col_gry">' + appointment.patient_mobile + '</td>');
                            newRow.append('<td class="txt_align">' + appointment.doctor_name + '</td>');
                            newRow.append('<td class="txt_align col_gren">' + appointment.booking_date + '</td>');
                            newRow.append('<td class="txt_align">' + appointment.booking_time + '</td>');
                            newRow.append('<td class="txt_align col_ornge">' + appointment.disease_symptoms + '</td>');
                            var statusCell = $('<td class="txt_break-at300 txt_align"></td>');
                            switch (parseInt(appointment.status, 10)) {
                                case 0:
                                    statusCell.append('<span class="col_red">Cancelled</span>');
                                    break;
                                case 1:
                                    statusCell.append('<span class="col_gren">Appointment</span>');
                                    break;
                                case 2:
                                    statusCell.append('<span class="col_red">Deleted</span>');
                                    break;
                                case 3:
                                    statusCell.append('<span class="col_ornge">Awaited</span>');
                                    break;
                                case 4:
                                    statusCell.append('<span class="col_gren">Fee Paid</span>');
                                    break;
                                case 5:
                                    statusCell.append('<span class="col_ornge">Absent</span>');
                                    break;
                                case 6:
                                    statusCell.append('<span class="col_ornge">Other</span>');
                                    break;
                                default:
                                    statusCell.append('<span class="col_red">Unknown</span>');
                            }
                            console.log("Status for appointment id " + appointment.id + ": " + appointment.status);
                            newRow.append(statusCell);


                            // Add a new <td> for the action dropdown
                            var actionDropdownCell = $('<td class="txt_align action-dropdown-cell"></td>');
                            var actionDropdown = $('<center>' +
                                '<a href="#!" class="btn btn-flat btn-floating dropdown-trigger" data-target="action_dropdown_' + appointment.id + '">' +
                                '<span class="fa fa-ellipsis-v"></span></a>' +
                                '</center>' +
                                '<ul class="dropdown-content action_dropdown" id="action_dropdown_' + appointment.id + '" id="dotted_border">' +
                                // Add your dropdown items here
                                '</ul>'
                            );

                            actionDropdownCell.append(actionDropdown);
                            newRow.append(actionDropdownCell);

                            tableBody.append(newRow);
                        }
                    }


                    });
                function hideDeletePatientAppointmentsConfirmationModal() {
                    // Hide the custom delete patient appointments confirmation modal when the modal close icon is clicked
                    $('#deletePatientAppointmentsConfirmationModal').hide();
                }
                function hidePermanentDeletePatientAppointmentsConfirmationModal() {
                    // Hide the custom permanent delete patient appointments confirmation modal when the modal close icon is clicked
                    $('#permanentDeletePatientAppointmentsConfirmationModal').hide();
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
            });
            </script>
            </div>
        </div>
        <!---Body Section End --->

        <!---Js file Include -->
        <?= view('Admin/js_file.php'); ?>
        <?= view('Admin/clear_text_js_file.php'); ?>
        <!---Js file Include -->
        <?= view('Frontdesk/date_picker_js_file.php'); ?>
</body>

</html>