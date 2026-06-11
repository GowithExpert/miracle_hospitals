<!DOCTYPE html>
<html lang="en">

<head>
  <!----Css File Include --->
  <?= view('Home/css_file'); ?>
  <?//= view('Home/custom_style'); ?>
  <?= view('Home/header_css_file'); ?>
  <!----Css File Include --->
</head>

<body>
  <!---Nav Bar Section Include  --->
  <?= view('Home/nav_bar'); ?>
  <!---Nav Bar Section Include  --->

  <!---Php Meassge Show -START --->
    <div id="cnfrm_msg">
      <?php if (session()->getTempdata('success')) : ?>
          <div class="card">
              <div class="card-content" id="succss_msg"><?= session()->getTempdata('success'); ?></div>
          </div>
      <?php endif; ?>
      <?php if (session()->getTempdata('error')) : ?>
          <div class="card">
              <div class="card-content" id="error_msg"><?= session()->getTempdata('error'); ?></div>
          </div>
      <?php endif; ?>
    </div>
    <!---Php Meassge Show -START --->
</body>