<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Modern Payment Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-methods label {
    display: flex;
    align-items: center;
}

.payment-methods input {
    margin-right: 5px;
}

.payment-form {
    background-color: #f2f2f2; /* Add a background color for the payment form */
    padding: 10px; /* Add some padding for better visual appeal */
    border-radius: 8px; /* Add rounded corners */
    margin-left: auto; /* Shift the payment form to the right */
}

#cardDetailsForm {
    text-align: right; /* Align card details to the right */
}

#cardDetailsForm label {
    display: block;
    margin-bottom: 10px;
    color: #555;
    text-align: left; /* Align label text to the left */
}

#cardDetailsForm input {
    width: 70%; /* Adjust the width as needed */
    box-sizing: border-box;
    padding: 8px;
    margin-bottom: 10px;
}





    </style>
</head>
<body>
    <div class="payment-container">
        <div id="step1">
            <h2>Select Payment Mode</h2>
            <label for="payOnlineRadio">
                <input type="radio" name="paymentMode" id="payOnlineRadio"> Pay Online
            </label>
            <label for="payOfflineRadio">
                <input type="radio" name="paymentMode" id="payOfflineRadio"> Pay Offline
            </label>
            <button id="nextBtnStep1" style="display: none;">Next</button>
            <button id="submitBtn" style="display: none;">Submit</button>
        </div>
        <div id="step2" style="display: none;">
            <h2>Select Payment Method</h2>
            <div id="onlinePaymentMethods" class="payment-methods">
                <label for="cardBtn">
                    <input type="radio" name="paymentMethod" id="cardBtn"> Debit/Credit Card
                </label>
                <label for="upiBtn">
                    <input type="radio" name="paymentMethod" id="upiBtn"> UPI
                </label>
                <label for="netBankingBtn">
                    <input type="radio" name="paymentMethod" id="netBankingBtn"> Net Banking
                </label>
                <div id="cardDetailsForm" class="payment-form">
                    <!-- Card details form will go here -->
                </div>
                <!-- Add forms for UPI and Net Banking as needed -->
            </div>
            <button id="prevBtnStep2">Previous</button>
            <button id="nextBtnStep2" style="display: none;">Next</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="script.js"></script>
    <script>
        $(document).ready(function() {
            $("#prevBtnStep2").click(function() {
                $("#step2").hide();
                $("#step1").show();
                $("input[name='paymentMode']").prop('disabled', false);
            });

            $("#nextBtnStep1").click(function() {
                $("#step1").hide();
                $("#step2").show();
                $("input[name='paymentMode']").prop('disabled', true);
            });

            $("#nextBtnStep1").hide();
            $("#submitBtn").hide();

            $("input[name='paymentMode']").change(function() {
                if ($("#payOnlineRadio").prop('checked')) {
                    $("#nextBtnStep1").show();
                    $("#submitBtn").hide();
                } else if ($("#payOfflineRadio").prop('checked')) {
                    $("#nextBtnStep1").hide();
                    $("#submitBtn").show();
                }
            });

            $("#cardBtn").click(function() {
                $(".payment-form").html("<label for='cardNumber'>Card Number:</label><input type='text' id='cardNumber' placeholder='Enter card number'><br><label for='expiryDate'>Expiry Date:</label><input type='text' id='expiryDate' placeholder='MM/YYYY'><br><label for='cvv'>CVV:</label><input type='text' id='cvv' placeholder='Enter CVV'>");
                $("#nextBtnStep2").show();
                $("#submitBtn").hide();
            });

            $("#upiBtn").click(function() {
                $(".payment-form").html("<label for='upiId'>UPI ID:</label><input type='text' id='upiId' placeholder='Enter UPI ID'>");
                $("#nextBtnStep2").show();
                $("#submitBtn").hide();
            });

            $("#netBankingBtn").click(function() {
                $(".payment-form").html("<label for='bankName'>Bank Name:</label><input type='text' id='bankName' placeholder='Enter bank name'><br><label for='accountNumber'>Account Number:</label><input type='text' id='accountNumber' placeholder='Enter account number'>");
                $("#nextBtnStep2").show();
                $("#submitBtn").hide();
            });
        });
    </script>
</body>
</html>
