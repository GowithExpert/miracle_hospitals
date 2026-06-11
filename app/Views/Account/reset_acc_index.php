<head>
	<style type="text/css">
		body {
			background: url(<?= base_url('public/assets/images/hos2.jpg') ?>)no-repeat fixed;
			background-size: 100% 100%
		}
		.iti {
			width: 100%;
		}
        #mrg_top{
            padding-top:25px;
        }
	</style>
</head>

<body>

	<div class="container-fluid">
		<div class="row" id="mrg_top">
			<div class="col l4 m12 s12"></div>
			<div class="col l4 m12 s12">
				<!---Php Meassge Show --->
				<div class="card">
					<div class="crd_positi">
						<?php if (session()->getTempdata('success')) : ?>
							<div class="card success-message cutom_messge_styl">
							<div class="card-content" id="popup_message">
								<span class="fa fa-check"></span>  <?= session()->getTempdata('success'); ?>
							</div>
							</div>
						<?php endif; ?>
						<?php if (session()->getTempdata('error')) : ?>
							<div class="card error-message cutom_messge_styl">
							<div class="card-content" id="popup_error_message">
								<span class="fa fa-times"></span>  <?= session()->getTempdata('error'); ?>
							</div>
							</div>
						<?php endif; ?>
					</div>


				<?php if (isset($error)) : ?>
					<div class="card" id="eror_msg">
						<div class="card-content" style="color: white;font-weight: 500;padding: 10px;">
							<span class="fa fa-exclamation-triangle"></span>  <?= $error; ?>
						</div>
					</div>
				<?php else : ?>

					<!---Php Meassge Show --->
					<?= form_open(); ?>
					<div class="card-content brdr_rdius" id="login_id_with_image">
						<h4 class="center-align mrg_botm"><span class="fa fa-unlock-alt fa_icon"></span></h4>
						<h5 class="center-align log_pge_hedng">Reset account password</h5>
						<p class="p_content">Red star (<span class="star"><sub>*</sub></span>) represents mandatory fields</p>
						<div class="card-content">
							<!-- <h6 style="color: orange">New Password</h6> -->
							<div class="password-container">
								<div class="image">
									<div class="input-container">
										<input type="password" name="new_password" class="asterisk passwordInput col_blck input_box" id="new_password" value="<?= set_value('new_password'); ?>" placeholder="Enter New Password">
										<span class="asterisk-symbol">*</span>
									</div>
									<span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
								</div>
							</div>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'new_password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
							<!-- <h6 style="color: orange">Confirm Password</h6> -->
							<div class="input-container">
								<input type="password" name="confirm_password" class="asterisk col_blck input_box" id="confirm_password" value="<?= set_value('confirm_password'); ?>" placeholder="Enter Confirm Password">
								<span class="asterisk-symbol">*</span>
							</div>
							<?php if (isset($validation)) { ?>
								<span class="col_red"><?= display_error($validation, 'confirm_password'); ?></span>
								<?= $validation->listErrors(); ?>
							<?php } ?>
							<center>
								<button type="submit" class="btn btn-waves-effect waves-light login_btn" id="btn_sign_in">Reset Password</button>
							</center>
						</div>
					</div>
					<?= form_close(); ?>
				<?php endif; ?>
			</div>
			<div class="col l4 m12 s12"></div>
		</div>
	</div>

