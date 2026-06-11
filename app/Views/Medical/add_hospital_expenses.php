<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <title>Hospital Expenses</title>
  <?//= view('Admin/css_file.php'); ?>
  <?= view('Admin/custom_css_file.php'); ?>
  <?= view('Home/css_file'); ?>

  <style>
    .col-lg-2,
    .col-md-2,
    .col-sm-12 {
      padding-right: 0px !important;
      padding-left: 6px !important;
    }
  </style>
</head>

<body>
  <!--Top Bar Section Include --->
  <?= view('Medical/topbar'); ?>
  <!--Top Bar Section Include --->

  <div class="container-fluid">
    <div class="card">
    <div id="ajx_eror_msg"></div>
    <div id="ajx_sucess_msg"></div>
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
        <h5 class="h5_align"><span class="fa fa-tasks col_blu"></span> Add Hospital Expenses</h5>
        <p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
			</div>
			<div class="card-content">
      <?= form_open_multipart('Medical/add_hospital_expenses'); ?>
        <div class="container">
          <form id="productForm">
            <div class="row row_mrgn_tp">
             
              <div class="col-lg-2 col-md-2 col-sm-12">
                <label class="fnt_size_expen" for="productName">Medical Item Name</label>
                <div class="input-container">
                  <input type="text" class="input_area asterisk medical_name plcehldr_algn_lft" name="medical_item_name" placeholder="Medical Item Name" id="medical_item_name" required>
                  <span class="asterisk-symbol">*</span>
                  <div id="medical_item_name_error" class="valid_err col_red"></div>
                </div>
              </div>
             
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="productCode">Item Code</label>
                <input type="text" class="input_area item_code plcehldr_algn_lft" name="item_code" id="item_code" placeholder="Item Code" required>
                <div id="item_code_error" class="valid_err col_red"></div>
              </div>
              
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="unit_price">Unit Price</label>
                <div class="input-container">
                  <input type="text" class="input_area txt_rgt asterisk unit_price plcehldr_algn_lft" value="0.00" name="unit_price" id="unit_price" placeholder="Unit Price" required>
                  <span class="asterisk-symbol">*</span>
                  <div id="price_error" class="valid_err col_red"></div>
                </div>
              </div>
              
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="quantity">Quantity</label>
                <div class="input-container">
                  <input type="text" class="input_area txt_rgt asterisk quantity plcehldr_algn_lft" name="quantity" id="quantity" value="1" placeholder="Quantity" required>
                  <span class="asterisk-symbol">*</span>
                </div>
              </div>
             
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="tax">Tax (%)</label>
                <div class="input-container">
                  <input type="text" class="input_area txt_rgt asterisk tax plcehldr_algn_lft" name="tax" id="tax" value="0" placeholder="Tax(%)" oninput="calculateTotal()">
                  <span class="asterisk-symbol">*</span>
                  <div id="tax_error" class="valid_err col_red"></div>
                </div>
              </div>
             
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="discount">Discount (%)</label>
                <div class="input-container">
                  <input type="text" class="input_area txt_rgt asterisk discount plcehldr_algn_lft" value="0" name="discount" id="discount" placeholder="Discount(%)" oninput="calculateTotal()">
                  <span class="asterisk-symbol">*</span>
                  <div id="discount_error" class="valid_err col_red"></div>
                </div>
              </div>
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="date">Date</label>
                <div class="input-container">
                  <input type="date" class="input_area asterisk date_inpt" name="date" id="date" placeholder="Date" required>
                  <span class="asterisk-symbol">*</span>
                  <div id="date_error" class="valid_err col_red"></div>
                </div>
              </div>
              
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="taxCalculation">Tax</label>
                <input type="text" class="input_area readonly_bg txt_rgt plcehldr_algn_lft" value="0.00" placeholder="Tax Amount" name="taxCalculation" id="taxCalculation" readonly>
              </div>
              <div class="col-lg-1 col-md-1 col-sm-12">
                <label class="fnt_size_expen" for="discountCalculation">Discount</label>
                <input type="text" class="input_area readonly_bg txt_rgt plcehldr_algn_lft" value="0.00" placeholder="Discount Amount" name="discountCalculation" id="discountCalculation" readonly>
              </div>
              
              <div class="col-lg-2 col-md-2 col-sm-12">
                <label class="fnt_size_expen" for="totalPrice">Total Price</label>
                <input type="text" class="input_area readonly_bg txt_rgt plcehldr_algn_lft" value="0.00" name="totalPrice" id="totalPrice" placeholder="Total Price" readonly>
              </div>
            </div>
            <!-- ... Your existing form ... -->
            <div class="row row_mrgn_tp">
              <div class="button-group">
                <button type="button" id="saveButton" class="btn_algn" >Save</button>
              </div>
            </div>
            <div class="row row_mrgn_tp">
              <div class="table-container scroll-container" id="tableContainer" style="display: none;">
                <table id="data-table">
                  <thead>
                    <tr>
                      <th>Medical Item Name</th>
                      <th>Item Code</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Tax (%)</th>
                      <th>Discount (%)</th>
                      <th>Tax Amount(%)</th>
                      <th>Discount Amount(%)</th>
                      <th>Date</th>
                      <th>Total Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Existing table rows will go here -->
                  </tbody>
                </table>
              </div>
            </div>

            <script>
              $(document).ready(function() {

                ////////////////////placeholder hide////////////////////////////
                $('.medical_name').focus(function() {
                  $(this).removeAttr('placeholder');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Medical Item Name');
                  }
                });

                $('.item_code').focus(function() {
                  $(this).removeAttr('placeholder');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Item Code');
                  }
                });

                $('.unit_price').focus(function() { console.log("fddss");
                  //$(this).removeAttr('placeholder'); //becoz give 0.00 default value in place of placeholder
                  $(this).removeAttr('value');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Enter Unit Price');
                  }
                });

                $('.quantity').focus(function() {
                  $(this).removeAttr('value');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Quantity');
                  }
                });

                $('.tax').focus(function() {
                  $(this).removeAttr('value');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Tax(%)');
                  }
                });

                $('.discount').focus(function() {
                  $(this).removeAttr('value');
                }).blur(function() {
                  if ($(this).val() === '') {
                    $(this).attr('placeholder', 'Discount(%)');
                  }
                });

                //////////////////////placeholder hide end////////////////////////////////
                // Function to calculate tax, discount, and total price
                // Function to calculate tax, discount, and total price
                function calculateTotal() {
                  var unit_price = parseFloat($("#unit_price").val()) || 0;
                  const quantity = parseFloat($("#quantity").val()) || 0;
                  const tax = parseFloat($("#tax").val()) || 0;
                  const discount = parseFloat($("#discount").val()) || 0;

                  const taxAmount = (unit_price * quantity * tax) / 100 || 0;
                  const discountAmount = (unit_price * quantity * discount) / 100 || 0;
                  const totalPrice = (unit_price * quantity) + taxAmount - discountAmount;

                  const dateInput = $("#date");
                  $("#taxCalculation").val(taxAmount.toFixed(2));
                  $("#discountCalculation").val(discountAmount.toFixed(2));
                  $("#totalPrice").val(totalPrice.toFixed(2));
                }

                const dateInput = $("#date");
                // Get the current date in the format "YYYY-MM-DD"
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                const currentDate = year + "-" + month + "-" + day;
                // Set the default value of the date input to the current date using jQuery
                dateInput.val(currentDate);

                function showTable() { 

                }
                // Function to save data to the table
                function saveToTable() { 
                  if (!validateForm()) {
                    return; // Don't proceed with saving if validation fails
                  }
                  const medical_item_name = $("#medical_item_name").val();
                  const item_code = $("#item_code").val();
                  
                  var unit_price = parseFloat($("#unit_price").val());
                  //console.log(init_unit_price);
                  //const unit_price = parseFloat(unit_price);
                  console.log(unit_price);
                  const quantity = parseFloat($("#quantity").val()).toFixed(2);
                  const tax = parseFloat($("#tax").val()).toFixed(2);
                  const discount = parseFloat($("#discount").val()).toFixed(2);
                  const taxCalculation = parseFloat($("#taxCalculation").val()).toFixed(2);
                  const discountCalculation = parseFloat($("#discountCalculation").val()).toFixed(2);

                  // Select the date input element using jQuery
                  const dateInput = $("#date");
                  // Get the current date in the format "YYYY-MM-DD"
                  const today = new Date();
                  const year = today.getFullYear();
                  const month = String(today.getMonth() + 1).padStart(2, '0');
                  const day = String(today.getDate()).padStart(2, '0');
                  const currentDate = year + "-" + month + "-" + day;
                  // Set the default value of the date input to the current date using jQuery
                  dateInput.val(currentDate);
                  var newRow = '';
                  const totalPrice = parseFloat($("#totalPrice").val()).toFixed(2);
                  if(isNaN(unit_price)) {unit_price = '0.00'; }
                  $.ajax({
                    type: 'POST',
                    url: "<?= base_url('/Medical_Accountant/save_hospital_expenses') ?>",
                    data: {
                      medical_item_name: medical_item_name,
                      item_code: item_code,
                      quantity: quantity,
                      tax: tax,
                      pid: '<?php echo $pid; ?>',
                      discount: discount,
                      taxCalculation: taxCalculation,
                      discountCalculation: discountCalculation,
                      date: currentDate,
                      unit_price: unit_price,
                      totalPrice: totalPrice,
                      patients_id: '<?php echo $id; ?>',
                      pid: '<?php echo $pid; ?>',
                      apmt_id: '<?php echo $apmt_id; ?>',
                      puid: '<?php echo $puid; ?>',

                    }, // data to submit
                    dataType: 'json',
                    success: function(response) { 
                      if (response.status) {//status: true - Redirect to another page with success message
                       // console.log(response.status);
                         $('#ajx_sucess_msg').html(response.message).show() 
                           .addClass('div_pad cutom_messge_styl col_wite bckgrnd_gren');
                           newRow = `
                            <tr>
                              <td>${medical_item_name}</td>
                              <td>${item_code}</td>
                              <td>${unit_price}</td>
                              <td>${quantity}</td>
                              <td>${tax}</td>
                              <td>${discount}</td>
                              <td>${taxCalculation}</td>
                              <td>${discountCalculation}</td>
                              <td>${currentDate}</td>
                              <td>${totalPrice}</td>
                            </tr>
                          `;
                          // Append the new row to the table
                          $("#data-table tbody").append(newRow);

                          $("#medical_item_name").val('');
                          $("#item_code").val('');
                          $("#unit_price").val('');
                          $("#quantity").val('');
                          $("#tax").val('');
                          $("#discount").val('');
                          $("#taxCalculation").val('');
                          $("#discountCalculation").val('');
                          $("#currentDate").val('');
                          $("#totalPrice").val('');

                        // Show the table if it was hidden
                        $("#tableContainer").css("display", "block");
                      } 
                      else if (!response.status) { //status: false
                        if(response.error) {
                         // console.log(response.message);
                          $('#ajx_eror_msg').html(response.message).show() 
                          .addClass('div_pad cutom_messge_styl col_wite bckgrnd_red');
                        }
                        else { //Validation failure 
                          console.log(response.data.medical_item_name);
                          $('#medical_item_name_error').html(response.data.medical_item_name).show(); 
                          $('#price_error').html(response.data.unit_price).show(); 
                         // console.log(response.data.medical_item_name);
                        }
                      }
                      else {
                          $('#ajx_eror_msg').html('Unexpected use case.').show();	
                      }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                      console.log("Something went wrong");
                      console.log(xhr);
                      console.log(textStatus);
                      //console.log(errorThrown);
                      //Handle the error
                    }
                  });

                  setTimeout(function() {
                      var sucesMsgs = document.querySelectorAll('#ajx_sucess_msg');
                      var errMsg = document.querySelectorAll('#ajx_eror_msg');

                      sucesMsgs.forEach(function(message) {
                      message.style.display = 'none';
                      });

                      errMsg.forEach(function(message) {
                      message.style.display = 'none';
                      });
                    }, 5000); // 5000 milliseconds = 5 seconds
                }

                function validateForm() {
                  let isValid = true;

                  // Validate Medical Item Name
                  const medicalItemName = $("#medical_item_name").val().trim();
                  if (medicalItemName === "") {
                    $("#medical_item_name_error").text("Please enter Medical Item Name.");
                    isValid = false;
                  } else {
                    $("#medical_item_name_error").text("");
                  }

                  const priceInput = $("#unit_price");
                  const unit_price = priceInput.val();

                  // Use a regular expression to check if the input is numeric
                  if (!/^\d+(\.\d+)?$/.test(unit_price)) {
                    $("#price_error").text("Please enter a numeric value for Price.");
                    isValid = false;
                  } else {
                    $("#price_error").text("");
                  }

                  // Validate Quantity
                  const quantityInput = $("#quantity");
                  const quantity = quantityInput.val();

                  // Use a regular expression to check if the input is numeric
                  if (!/^\d+(\.\d+)?$/.test(quantity)) {
                    $("#quantity_error").text("Please enter a numeric value for Quantity.");
                    isValid = false;
                  } else {
                    $("#quantity_error").text("");
                  }

                  // Validate Tax
                  const taxInput = $("#tax");
                  const tax = taxInput.val();

                  // Use a regular expression to check if the input is numeric
                  if (!/^\d+(\.\d+)?$/.test(tax)) {
                    $("#tax_error").text("Please enter a numeric value for Tax.");
                    isValid = false;
                  } else {
                    $("#tax_error").text("");
                  }

                  // Validate Discount
                  const discountInput = $("#discount");
                  const discount = discountInput.val();

                  // Use a regular expression to check if the input is numeric
                  if (!/^\d+(\.\d+)?$/.test(discount)) {
                    $("#discount_error").text("Please enter a numeric value for Discount.");
                    isValid = false;
                  } else {
                    $("#discount_error").text("");
                  }

                  // Validate Date
                  const date = $("#date").val().trim();
                  if (date === "") {
                    $("#date_error").text("Please enter a Date.");
                    isValid = false;
                  } else {
                    $("#date_error").text("");
                  }

                  // Add more validation for other fields here...

                  return isValid;
                }

                //   // Add event listeners to input fields to trigger recalculation
                
                $('#unit_price').on("input", calculateTotal);
                $("#quantity").on("input", calculateTotal);
                $("#tax").on("input", calculateTotal);
                $("#discount").on("input", calculateTotal);

                let userInput = "";

                // Add event listener to the price input field for manual input
                $("#unit_price").on("input", function() {
                  const priceInput = $(this);
                  userInput = priceInput.val();
                });

                // Add event listener to handle formatting when the user leaves the input field
                $("#unit_price").on("blur", function() {
                  const priceInput = $(this);
                  const unit_price = parseFloat(userInput);

                  if (!isNaN(unit_price)) {
                    // Format the value with two decimal places and update the input field
                    priceInput.val(unit_price.toFixed(2));
                  }
                });

                // Add event listener to the "Save" button
                $("#saveButton").on("click", function() {
                  // Call the validateForm function when the button is clicked
                  if (!validateForm()) {
                    return; // Don't proceed with saving if validation fails
                  }

                  // Call the saveToTable function when the button is clicked
                  saveToTable();
                });
              });
            </script>

            <!---Js file Include -->
            <?= view('Admin/js_file.php'); ?>
            <!---Js file Include -->
            <!--js file Include for asterisk symbol-->
            <?= view('Admin/asterisk_symbol_js_file.php'); ?>
            <!--js file Include for asterisk symbol-->
            <?= form_close(); ?>
        </div>
      </div>
    </div>
</body>

</html>