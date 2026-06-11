<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Payment Options</title>
    <?= view('Patients/appointment_payment_css_file.php'); ?>
    <link rel="shortcut icon" type="image/icon" href="<?= base_url('public/assets/home_image/images/favicon.ico') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>



<body class="appoint_bdy">

   
        <div class="container d-flex justify-content-center mt-5 mb-5">
            <div id="customMessageContainer" class="msg_conten">
                <span id="customMessage"></span>
            </div>
            <div class="row g-3">
               
                <div class="col-md-6 marg_tp">
                    <button id="backButton"><i class="fa fa-arrow-circle-left"></i></button>
                    <div class="txt_dec">Payment Method</div>
                    <div class="card">
                        <div class="accordion" id="accordionExample">
                            <!-- Pay Offline option -->
                            <div class="card brd_botm">
                                <div class="card-header p-0" id="headingOffline">
                                    <h2 class="mb-0">
                                        <button class="btn btn-light btn-block text-left p-3 rounded-0 border-bottom-custom payment-option-btn" type="button" data-target="#collapseOffline" aria-expanded="false" aria-controls="collapseOffline">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Pay Offline</span>
                                                <input type="radio" name="paymentOption" value="offline" id="paymentOption" checked>
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOffline" class="collapse" aria-labelledby="headingOffline" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <!-- Add any additional fields for Pay Offline if needed -->
                                    </div>
                                </div>
                            </div>

                            <!-- PayPal option -->
                            <div class="card brd_botm">
                                <div class="card-header p-0" id="headingTwo">
                                    <h2 class="mb-0" id="collapsepaypal">
                                        <button class="btn btn-light btn-block text-left collapsed p-3 rounded-0 border-bottom-custom payment-option-btn" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Paypal</span>
                                                <img class="img_posit" src="https://i.imgur.com/7kQEsHU.png" width="30">
                                                <input type="radio" name="paymentOption" value="paypal" id="paymentOption">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <input type="text" class="form-control" placeholder="Paypal email">
                                    </div>
                                </div>
                            </div>

                            <!-- Debit Card option -->
                            <div class="card brd_botm">
                                <div class="card-header p-0">
                                    <h2 class="mb-0" id="collapseDebit">
                                        <button class="btn btn-light btn-block text-left p-3 rounded-0 payment-option-btn" data-toggle="collapse" data-target="#collapseDebitCard" aria-expanded="true" aria-controls="collapseDebitCard">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Debit card</span>
                                                <div class="icons img_posit_1">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                    <img src="https://i.imgur.com/W1vtnOV.png" width="30">
                                                    <img src="https://i.imgur.com/35tC99g.png" width="30">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                </div>
                                                <input type="radio" name="paymentOption" value="debitCard" id="paymentOption">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseDebitCard" class="collapse" aria-labelledby="headingDebitCard" data-parent="#accordionExample">
                                    <div class="card-body payment-card-body">
                                        <span class="font-weight-normal card-text">Card Number</span>
                                        <div class="input">
                                            <i class="fa fa-credit-card"></i>
                                            <div class="input-container">
                                                <input type="text" class="form-control asterisk" placeholder="0000 0000 0000 0000">
                                                <span class="asterisk-symbol">*</span>
						                    </div>
                                        </div> 

                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">Expiry Date</span>
                                                <div class="input">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="input-container">
                                                        <input type="text" class="form-control asterisk" placeholder="MM/YY">
                                                        <span class="asterisk-symbol">*</span>
						                            </div>
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">CVC/CVV</span>
                                                <div class="input">
                                                    <i class="fa fa-lock"></i>
                                                    <div class="input-container">
                                                        <input type="text" class="form-control asterisk" placeholder="000">
                                                        <span class="asterisk-symbol">*</span>
						                            </div>
                                                </div> 
                                            </div>
                                        </div>
                                        <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                                    </div>
                                </div>
                            </div>


                            <!-- Credit Card option -->
                            <div class="card brd_botm">
                                <div class="card-header p-0">
                                    <h2 class="mb-0" id="collapseCredit">
                                        <button class="btn btn-light btn-block text-left p-3 rounded-0 payment-option-btn" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>Credit card</span>
                                                <div class="icons img_posit_1">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                    <img src="https://i.imgur.com/W1vtnOV.png" width="30">
                                                    <img src="https://i.imgur.com/35tC99g.png" width="30">
                                                    <img src="https://i.imgur.com/2ISgYja.png" width="30">
                                                </div>
                                                <input type="radio" name="paymentOption" value="creditCard" id="paymentOption">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body payment-card-body">
                                        <span class="font-weight-normal card-text">Card Number</span>
                                        <div class="input">
                                            <i class="fa fa-credit-card"></i>
                                            <div class="input-container">
                                                <input type="text" class="form-control asterisk" placeholder="0000 0000 0000 0000">
                                                <span class="asterisk-symbol">*</span>
						                    </div>
                                        </div> 

                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">Expiry Date</span>
                                                <div class="input">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="input-container">
                                                        <input type="text" class="form-control asterisk" placeholder="MM/YY">
                                                        <span class="asterisk-symbol">*</span>
						                            </div>
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">CVC/CVV</span>
                                                <div class="input">
                                                    <i class="fa fa-lock"></i>
                                                    <div class="input-container">
                                                        <input type="text" class="form-control asterisk" placeholder="000">
                                                        <span class="asterisk-symbol">*</span>
						                            </div>
                                                </div> 
                                            </div>
                                        </div>
                                        <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                                    </div>
                                </div>
                            </div>

                            <!-- UPI option -->
                            <div class="card">
                                <div class="card-header p-0">
                                    <h2 class="mb-0" id="collapseupipay">
                                        <button class="btn btn-light btn-block text-left p-3 rounded-0 payment-option-btn" data-toggle="collapse" data-target="#collapseUPI" aria-expanded="true" aria-controls="collapseUPI">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span>UPI</span>
                                                <img class="img_posit" src="<?= base_url('public/assets/images/upi-icon-black.png'); ?>" width="30">
                                                <input type="radio" name="paymentOption" value="upi" id="paymentOption">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseUPI" class="collapse" aria-labelledby="headingUPI" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <input type="text" class="form-control" placeholder="UPI ID">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 summry_div">
                    <div class="txt_dec">Summary</div>
                    <div class="card">
                        <div class="d-flex justify-content-between p-3 brd_btm">
                            <div class="d-flex flex-column">
                                <span>Appointment Fee<span class="small_txt"> (Non-refundable)</span></span>
                                
                            </div>
                            <div class="mt-1">
                                <span class="super-price"><span>₹<?= APMT_REGIS_FEE ?></span></span>
                            </div>
                        </div>


                        <div class="p-3 brd_btm">
                            
                            <div class="d-flex justify-content-between">
                                <span>Tax Amount<span class="small_txt"> (Tax - <span id="taxRate"><?= APMT_FEE_TAX ?></span>%)</span></span>
                                <span id="taxAmount">₹<?= number_format((APMT_REGIS_FEE * APMT_FEE_TAX) / 100, 2) ?></span>
                            </div>
                        </div>

                        

                        <div class="p-3 d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <span>Total Amount</span>
                                
                            </div>
                            <span id="totalAmount">₹<?= number_format(APMT_REGIS_FEE + (APMT_REGIS_FEE * APMT_FEE_TAX) / 100, 2) ?></span>
                        </div>

                        <div class="p-3">
                        <button id="payNowButton" class="btn btn-primary btn-block free-button">Pay Offline</button> 
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- <//?= form_close(); ?> -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function () {

        // Add a click event listener to the back button
        $('#backButton').on('click', function () {
        // Use window.history to navigate back
        window.history.back();
        });


         // Update tax amount and total amount when the page loads
        var appointmentFee = <?= APMT_REGIS_FEE ?>;
        var taxRate = <?= APMT_FEE_TAX ?>;

        var taxAmount = (appointmentFee * taxRate) / 100;
        var totalAmount = appointmentFee + taxAmount;
        console.log(totalAmount);

        // Format and display tax amount and total amount
        $('#taxAmount').text('₹' + formatDecimal(taxAmount));
        $('#totalAmount').text('₹' + formatDecimal(totalAmount));


        // Retrieve query parameters from the URL
        var queryString     = window.location.search;
        var urlParams       = new URLSearchParams(queryString);
        // Get the values of param1 and param2
        var last_insrt_apmt_id  = urlParams.get('last_insrt_apmt_id');
        var appointment_date    = urlParams.get('appointment_date');
        var appointment_time    = urlParams.get('appointment_time');
        var patient_email       = urlParams.get('patient_email');
        var serial              = urlParams.get('serial');
        var patient_name        = urlParams.get('patient_name');
        var doctor_name         = urlParams.get('doctor_name');
        var slot_id             = urlParams.get('slot_id');
        var country_code        = urlParams.get('country_code');
        var mobile              = urlParams.get('mobile');
        var puid                = urlParams.get('puid');
        var doctor_id           = urlParams.get('doctor_id');
        var pid                 = urlParams.get('pid');
                   
        
        //console.log(doctor_id);
      
        var tot_amount = $('#totalAmount').val();
        //console.log(tot_amount);
        
        // Use the values as needed
        console.log("last_insrt_apmt_id: " + last_insrt_apmt_id);
        //console.log("slot_id: " + slot_id);

          $("#payNowButton").on("click", function (e) {
            e.preventDefault();

            var paymentOption = $("input[name='paymentOption']:checked").val();
            if (!paymentOption) {
                alert("Please select a payment option");
                return;
            }          
            $.ajax({
                type: 'POST',
                url: "<?= base_url('/Patients/add_appointment_fee/') ?>",
                data: {
                    doctor_name: doctor_name,
                    appointment_date: appointment_date,
                    last_insrt_apmt_id: last_insrt_apmt_id,
                    appointment_time: appointment_time,
                    patient_email: patient_email,
                    serial: serial,
                    slot_id: slot_id,
                    country_code: country_code,
                    mobile: mobile,
                    puid: puid,
                    doctor_id: doctor_id,
                    patient_name: patient_name,
                    apmt_regis_fee: totalAmount,
                    pay_option: paymentOption,
                },
                dataType: 'json',
                success: function (response) {
                if (response.status) {
                    console.log(response.message);
                    var success_msg = response.message;

                    var url =  "<?= base_url('Patients/doctors_available_slots/') ?>" + pid;
                    url += "?success_msg=" + encodeURIComponent(success_msg);
                    window.location.href = url;
                     } else {
                    alert(response.message);
                     }
                },

                error: function (xhr, textStatus, errorThrown) {
                    console.log("Something went wrong");
                    console.log(xhr);
                    console.log(textStatus);
                    console.log(errorThrown);

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        //alert("Error: " + xhr.responseJSON.message);
                        $('#customMessage').text(xhr.responseJSON.message);
                        return;
                    } 
                    else {
                        //alert("Error: Something went wrong. Please try again.");
                        $('#customMessage').text("Error: Something went wrong. Please try again.");
                        return;
                    }
                }
            });
        });
        // Add this line to set the background color of the default selected Pay Offline button
        $('#headingOffline button').addClass('selected-option');

        $('.payment-option-btn').on('click', function () {
            // Remove the selected-option class from all buttons
            $('.payment-option-btn').removeClass('selected-option');

            // Add the selected-option class to the clicked button
            $(this).addClass('selected-option');

            // Collapse all other options
            $('.payment-option-btn').not(this).addClass('collapsed');
            $('.collapse').not($(this).attr('data-target')).removeClass('show');

            // Deselect all radio buttons
            $('input[name="paymentOption"]').prop('checked', false);

            // Select the radio button associated with the clicked payment option
            $(this).find('input[name="paymentOption"]').prop('checked', true);

            // Get the value of the selected payment option
            var selectedOption = $(this).find('input[name="paymentOption"]').val();

            // Get the pay now button element
            var payNowButton = $('#payNowButton');

            // Update the button text based on the selected option
            if (selectedOption === 'offline') {
                payNowButton.text('Pay Offline');
            } else {
                payNowButton.text('Pay Now');
            }
        });

        // Format the tax amount and total amount to display in decimal format
        function formatDecimal(number) {
            return parseFloat(number).toFixed(2);
        }


        ///////////////////////////////////////////////////////////////////////////////////////////
        ////////////Underdevelopment Message////////////////////////////////////////////////////////
        // Function to show a custom message for online payment
        function showOnlinePaymentMessage(message) {
            // Replace the alert with custom message display logic
            $('#customMessage').text(message);
            $('#customMessageContainer').show(); // Show the container

            setTimeout(function() {
                $('#customMessageContainer').hide();
            }, 5000);
        }

        // Add click event listeners to the tab buttons for online payment using jQuery
        $('#collapsepaypal button').on('click', function() {
            showOnlinePaymentMessage('PayPal: Online payment is under development. Please go through with offline payment.');
        });
        $('#collapseDebit button').on('click', function() {
            showOnlinePaymentMessage('Debit Card: Online payment is under development. Please go through with offline payment.');
        });
        $('#collapseCredit button').on('click', function() {
            showOnlinePaymentMessage('Credit Card: Online payment is under development. Please go through with offline payment.');
        });
        $('#collapseupipay button').on('click', function() {
            showOnlinePaymentMessage('UPI: Online payment is under development. Please go through with offline payment.');
        });

    });
</script>
    <!--js file Include for asterisk symbol-->
    <?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

</body>
</html>
