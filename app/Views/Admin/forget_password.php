  <!--=========== Start Footer SECTION ================-->
  <?= view('Admin/header_admin'); ?>
  <!--=========== End Footer SECTION ==================-->
  
  <?= helper('Form'); ?>
  <!---Nav Bar Section Include  --->
  <?= view('Admin/nav_barmgt'); ?>
  <!---Nav Bar Section Include  --->
  <body class="bdy_backgrd">

  	<div class="container">
		<div class="row" id="mrgn_top">
			<div class="col l4 m12 s12"></div>
			<div class="col l4 m12 s12">
				<!---Php Meassge Show --->
					<div class="reltiv">
					<?php if (session()->getTempdata('success')) : ?>
						<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message"><?= session()->getTempdata('success'); ?></div>
						</div>
					<?php endif; ?>
					<?php if (session()->getTempdata('error')) : ?>
						<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message"><?= session()->getTempdata('error'); ?></div>
						</div>
					<?php endif; ?>
					</div>
					<!---Php Meassge Show --->


				<?= form_open(); ?>
				<div class="card brdr_rdius forgt_card_cntnt">
					<div class="card-content" id="div_pad">
						<i class="fa fa-exclamation-circle icon_styl"></i>
						<h6 class="for_got_align">  Forget Password</h6>
						<p class="para_styl">Enter your email and we'll send you a link to reset your password</p>
						<p class="para_sty2">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
					</div>
					<div class="card-content">
						<div class="input-container">
							<input type="text" name="email" maxlength="40" id="input_box" class="asterisk inpt_area margn_tp" value="<?= set_value('email'); ?>" placeholder="Enter Email Id">
							<span class="asterisk-symbol">*</span>
						</div>
						<?php if (isset($validation)) { ?>
							<span class="col_red"><?= display_error($validation, 'email'); ?></span>
							<?= $validation->listErrors(); ?>
						<?php } ?>
						<center>
							<button type="submit" class="btn btn-waves-effect waves-light frgt_pass_submit">Submit</button>
						</center>
						<br>
						<center>
							<p class="bck_to_logn"><a class="flex" href="<?= base_url('/Login'); ?>"><i class="fa fa-angle-left"></i> Back to Login</a></p>
						</center>
					</div>
				</div>
				<?= form_close(); ?>
			</div>
			<div class="col l4 m12 s12"></div>
		</div>
	</div>
<script>
  // Add JavaScript to hide messages with the specified classes after 5 seconds
  setTimeout(function() {
    var sucesMsgs = document.querySelectorAll('.success-message');
    var errMsg = document.querySelectorAll('.error-message');

    sucesMsgs.forEach(function(message) {
      message.style.display = 'none';
    });

    errMsg.forEach(function(message) {
      message.style.display = 'none';
    });
  }, 5000); // 5000 milliseconds = 5 seconds
</script>


	<?= view('Admin/js_file.php'); ?>
	<!--js file Include for asterisk symbol-->
	<?= view('Admin/asterisk_symbol_js_file.php'); ?>
	<!--js file Include for asterisk symbol-->

  <!--=========== Start Footer SECTION ================-->
  <?= view('Admin/footer_admin'); ?>
  <!--=========== End Footer SECTION ==================-->