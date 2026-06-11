<!-- <head> -->
  
<!-- </head> -->
 
<!-- BEGAIN PRELOADER -->
<div id="preloader1"> <!-- id="preloader" is not allowing show php output, so renamed as preloader1 -->
  <div id="status" style="background-image:url(<?= base_url('public/assets/home_image/images/status.gif') ?>);"></div>
</div>
<!-- END PRELOADER -->

<!-- SCROLL TOP BUTTON -->
<a class="scrollToTop" href="#"><i class="fa fa-heartbeat"></i></a>
<!--SCROLL TOP BUTTON END-->

<!--=========== BEGIN HEADER SECTION ================-->
<header id="header">
 
  <!-- BEGIN MENU -->
  <div class="menu_area">
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">

          <!-- FOR MOBILE VIEW COLLAPSED BUTTON -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- IMG BASED LOGO  -->
          <!-- <a class="navbar-brand" href="<//?= base_url('Home/index'); ?>"><img src="<//?= base_url('public/assets/home_image/images/logo2.png'); ?>" alt="logo"></a> -->
          <a class="navbar-brand" href="<?= base_url('/'); ?>"><img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" alt="logo"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul id="top-menu" class="nav navbar-nav navbar-right main-nav">
            <li>
              <!-- <a href="<//?= base_url('/'); ?>">Home</a> -->
              <a href="<?= base_url('/mgt/'); ?>">Home</a>
            </li>
            <li>
              <a href="<?= base_url('Login'); ?>">Admin</a>
            </li>
            <li>
              <a href="<?= base_url('Doctor_login/doctor_login'); ?>" >Doctors</a>
            </li>
            <li>
              <a href="<?= base_url('Patients_login/login'); ?>" >Patients</a>
            </li>
            <li>
              <a href="<?= base_url('Accountant_login/accountant_login'); ?>" >Accountants</a>
            </li>
            <li>
              <a href="<?= base_url('Frontdesk_login/login_account'); ?>" >Front Desk</a>
            </li>
            <li>
              <a href="<?= base_url('Blood_bank/blood_bank_login'); ?>" >Blood Bank</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
  </div>
  <!-- END MENU -->

</header>
<!--=========== END HEADER SECTION ================-->

<!-- <div class="reliv">
  <?php //if (session()->getTempdata('success')) : ?>
    <div class="card success-message cutom_messge_styl">
      <div class="card-content" id="popup_message"><?//= session()->getTempdata('success'); ?></div>
    </div>
  <?php //endif; ?>
  <?php // if (session()->getTempdata('error')) : ?>
    <div class="card error-message cutom_messge_styl">
      <div class="card-content" id="popup_message"><?//= session()->getTempdata('error'); ?></div>
    </div>
  <?php //endif; ?>
</div> -->

<script>
  // Add JavaScript to hide messages with the specified classes after 5 seconds
  $(document).ready(function() {
  setTimeout(function() {
    $('.success-message').hide();
    $('.error-message').hide();
  }, 5000);
  var preloader = $("#preloader1");
    preloader.hide();
});
</script>
<!-- Include jQuery -->