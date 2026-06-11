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
    <title>Add Prescription</title>
    <?= helper('Form'); ?>
    <?//= view('Admin/css_file.php'); ?>
    <?= view('Admin/custom_css_file.php'); ?>
    <?= view('Home/css_file'); ?>
    <?= view('Doctor/add_patient_prescription_css.php'); ?>

    <style type="text/css">
    </style>
    <style>
        body {
            background: url(<?= base_url('public/assets/images/blood5.jpg') ?>)no-repeat fixed !important;
            background-size: 100% 100% !important;
        }

        /* Add your custom CSS styles here */
        .time-slot {
            display: inline-block;
            width: 80px;
            height: 50px;
            border: 1px solid #ccc;
            margin: 5px;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
        }

        .selected {
            background-color: #005197;
            color: #fff;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            max-height: 100% !important;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .header_tbl {
            border: 1px solid #9d9d9d;
            text-indent: 4px;
            width: 50%;
            font-size: 12px;
            height: 25px;
            border-radius: 5px;
            background: #fff;
        }

        .upr_header {
            background: #fff !important;
            height: 29px !important;
        }

        input[type=date]:not(.browser-default) {
            margin: 0 0 0px 0;
        }

        input[type=date]:not(.browser-default) {
            height: 38px !important;
            border: 1px solid #9e9e9e !important;
            border-radius: 3px !important;
            text-indent: 7px !important;
        }

        button {
            background: #005197 !important;
            border: 2px solid #005197 !important;
        }

        .row1 {
            margin-right: 15px !important;
            margin-left: 15px !important;
            margin-top: 15px !important;
        }

        .sidesift {
            margin-left: 15px !important;
            margin-right: 15px !important;
        }

        .struct {
            border: 1px solid transparent;
            box-shadow: 0px 0px 5px;
        }

        .form-stepper-horizontal li:after {
            background-color: lightgray !important;
        }
    </style>

</head>

<body>
    <!---Topbar Section Include --->
    <?= view('Admin/top_bar'); ?>
    <!---Topbar Section Include --->

    <!---Body Section Start -->
    <div style="margin-top: 40px;" id="multi-step-form-container">
        <!-- Form Steps / Progress Bar -->
        <div style="background: #ffffff99; border-radius: 20px;" class="container">
            <div style="margin-left: 80px; margin-right: 80px; margin-bottom: 12px; margin-top: 12px;font-family: serif" class="row">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="left-section">
                        <div class="row">Dr. Name - Raj Khurana</div>
                        <div class="row">Degree - MBBS</div>
                        <div class="row">Specialization - Ortho</div>
                        <div class="row">Gender - Male</div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-3"></div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div style="float:right;" class="right-section">
                        <div class="row">Name - Kannu Singh</div>
                        <div class="row">Age - 22</div>
                        <div class="row">Gender - Male</div>
                        <div class="row">Row 4 - Right</div>
                    </div>
                </div>
            </div>
            <ul style="margin-top: 33px;" class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
                <!-- Step 1 -->
                <li class="form-stepper-active text-center form-stepper-list" step="1">
                    <a class="mx-2">
                        <span class="form-stepper-circle">
                            <span>1</span>
                        </span>
                        <div class="label"></div>
                    </a>
                </li>
                <!-- Step 2 -->
                <li class="form-stepper-unfinished text-center form-stepper-list" step="2">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>2</span>
                        </span>
                        <div class="label text-muted"></div>
                    </a>
                </li>
                <!-- Step 3 -->
                <li class="form-stepper-unfinished text-center form-stepper-list" step="3">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>3</span>
                        </span>
                        <div class="label text-muted"></div>
                    </a>
                </li>
                <!-- Step 4 -->
                <li class="form-stepper-unfinished text-center form-stepper-list" step="4">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>4</span>
                        </span>
                        <div class="label text-muted"></div>
                    </a>
                </li>
                <!-- Step 5 -->
                <li class="form-stepper-unfinished text-center form-stepper-list" step="5">
                    <a class="mx-2">
                        <span class="form-stepper-circle text-muted">
                            <span>5</span>
                        </span>
                        <div class="label text-muted"></div>
                    </a>
                </li>
            </ul>
            <!-- Step Wise Form Content -->
            <form id="userAccountSetupForm" name="userAccountSetupForm" enctype="multipart/form-data" method="POST">
                <!-- Step 1 Content -->
                <section id="step-1" class="form-step">
                    <!-- Step 1 input fields -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="mt-3">
                                <textarea style="background-color: #eeefefd6;height: 180px;" id="textarea" placeholder="Prescription" name="textarea"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="button btn-navigate-form-step" type="button" step_number="2">Next</button>
                    </div>
                </section>
                <!-- Step 2 Content, default hidden on page load. -->
                <section id="step-2" class="form-step d-none">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h2 class="font-normal">Report</h2>
                        </div>
                    </div>
                    <!-- Step 2 input fields -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="part" class="mt-3">
                                <div id="reportGrid">
                                    <!-- The submitted reports will be displayed here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="button btn-navigate-form-step" type="button" step_number="1">Prev</button>
                        <button class="button btn-navigate-form-step" type="button" step_number="3">Next</button>
                    </div>
                </section>
                <!-- Step 3 Content, default hidden on page load. -->
                <section id="step-3" class="form-step d-none">
                    <h2 class="font-normal">Appointment</h2>
                    <!-- Step 3 input fields -->
                    <div class="mt-3">
                        <div class="struct">
                            <div class="row1" class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="doctor">Select Doctor:</label>
                                    <select id="doctor" required>
                                        <option value="">Select a doctor</option>
                                        <option value="doctor1">Doctor 1</option>
                                        <option value="doctor2">Doctor 2</option>
                                        <!-- Add more doctor options here -->
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="date">Select Date:</label>
                                    <input type="date" id="date" required>
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h2 style="text-align: center;font-size: 16px;" class="sidesift">Select Time Slot</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="sidesift" id="time-slots">
                                        <!-- Time slots will be dynamically generated here -->
                                    </div>
                                </div>
                            </div>
                            <button style="margin-left: 15px; margin-right: 15px;margin-bottom: 15px;" id="book-btn" disabled>Book Appointment</button>
                        </div>

                        <div id="appointment-popup" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2>Appointment Details</h2>
                                <p id="appointment-doctor">Doctor: </p>
                                <p id="appointment-date">Date: </p>
                                <p id="appointment-time">Time: </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="button btn-navigate-form-step" type="button" step_number="2">Prev</button>
                        <button class="button btn-navigate-form-step" type="button" step_number="4">Next</button>
                    </div>
                </section>
                <!-- Step 4 Content, default hidden on page load. -->
                <section id="step-4" class="form-step d-none">
                    <h2 class="font-normal">Summary</h2>
                    <!-- Step 4 input fields -->
                    <div class="mt-3">
                        Step 4 input fields go here...
                    </div>
                    <div class="mt-3">
                        <button class="button btn-navigate-form-step" type="button" step_number="3">Prev</button>
                        <button class="button btn-navigate-form-step" type="button" step_number="5">Next</button>
                    </div>
                </section>
                <!-- Step 5 Content, default hidden on page load. -->
                <section id="step-5" class="form-step d-none">
                    <h2 class="font-normal">Preview</h2>
                    <!-- Step 5 input fields -->
                    <div class="mt-3">
                        Step 5 input fields go here...
                    </div>
                    <div class="mt-3">
                        <button class="button btn-navigate-form-step" type="button" step_number="4">Prev</button>
                        <button class="button submit-btn" type="submit">Save</button>
                    </div>
                </section>
            </form>
        </div>
    </div>
    </div>
    <!---Body Section Start -->

    <!---Js file Include -->
    <?= view('Admin/js_file.php'); ?>
    <!---Js file Include -->
    <?= view('Doctor/multiple_steps_js_file.php'); ?>
    <script>
        // Sample data: Replace this with your actual data from the server or any other source
        const reports = Array.from({
            length: 20
        }, (_, index) => ({
            id: index + 1,
            title: `Report ${index + 1}`,
            submitted: false,
        }));

        // Function to display the reports on the page
        function displayReports() {
            const reportGrid = document.getElementById("reportGrid");

            reports.forEach((report, index) => {
                const reportDiv = document.createElement("div");
                reportDiv.classList.add("report");

                // Create the serial number element for the report
                const serialNumber = document.createElement("div");
                // Create the label element for the report name
                const label = document.createElement("label");
                label.classList.add("report-name");
                label.innerHTML = report.title;

                // Create the checkbox element for the report submission status
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.checked = report.submitted;
                checkbox.classList.add("custom-checkbox");

                // Add event listener to update report status when checkbox is clicked
                checkbox.addEventListener("click", () => {
                    report.submitted = checkbox.checked;
                    updateReportStyle(reportDiv, report.submitted);
                });

                // Append the serial number, label, and checkbox to the report div
                reportDiv.appendChild(serialNumber);
                reportDiv.appendChild(label);
                reportDiv.appendChild(checkbox);

                // Update report style based on initial checked status
                updateReportStyle(reportDiv, report.submitted);

                reportGrid.appendChild(reportDiv);
            });
        }

        // Function to update report style based on submitted status
        function updateReportStyle(reportDiv, submitted) {
            const label = reportDiv.querySelector(".report-name");
            if (submitted) {
                reportDiv.style.backgroundColor = "#e6f7ff";
                label.style.color = "#888";
            } else {
                reportDiv.style.backgroundColor = "#fff";
                label.style.textDecoration = "none";
                label.style.color = "#333";
            }
        }

        // Call the function to display reports when the page loads
        window.onload = function() {
            displayReports();
        };
    </script>
    <?= view('Frontdesk/date_picker_js_file.php'); ?>
    <?= view('Doctor/add_patient_prescription_js.php'); ?>

</body>

</html>