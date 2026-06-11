<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Header</title>
    <!----Css File Include --->
    <?= view('Home/css_file'); ?>
    <!----Css File Include --->
</head>
<body>

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
          <a class="navbar-brand" href="<?= base_url('Home/index'); ?>"><img src="<?= base_url('public/assets/home_image/images/logo2.png'); ?>" alt="logo"></a>

        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul id="top-menu" class="nav navbar-nav navbar-right main-nav">
            <li>
              <a href="<?= base_url('Home/index'); ?>">Home</a>
            </li>
            <li>
              <a href="<?= base_url('Login'); ?>" target="_blank">Admin</a>
            </li>
            <li>
              <a href="<?= base_url('Doctor_login/doctor_login'); ?>" target="_blank">Doctors</a>
            </li>
            <li>
              <a href="<?= base_url('Patients_login/login'); ?>" target="_blank">Patients</a>
            </li>
            <li>
              <a href="<?= base_url('Accountant_login/accountant_login'); ?>" target="_blank">Accountants</a>
            </li>
            <li>
              <a href="<?= base_url('Frontdesk_login/login_account'); ?>" target="_blank">Front Desk</a>
            </li>
            <li>
              <a href="<?= base_url('Blood_bank/blood_bank_login'); ?>" target="_blank">Blood Bank</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
  </div>
  <!-- END MENU -->
</header>
<!--=========== END HEADER SECTION ================-->
</body>
</html>

 

  