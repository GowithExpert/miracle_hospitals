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
  <title></title>
  <!----Css File Include --->
  <?= view('Home/css_file'); ?>
  <!----Css File Include --->
</head>

<body>
  <!---Nav Bar Section Include  --->
  <?= view('Home/nav_bar'); ?>
  <!---Nav Bar Section Include  --->
  <br><br><br><br><br><br><br><br><br><br>
  <!---Php Meassge Show --->
  <div style="margin-left: 20px;margin-right: 10px">
    <?php if (session()->getTempdata('success')) : ?>
      <div class="toast" data-autohide="false">
        <div class="toast-header" style="margin-left: 20px;margin-right: 20;padding: 10px; background: green;color: white;font-weight: 500"><?= session()->getTempdata('success'); ?>
          <small class="text-muted"></small>
        </div>
      </div>
    <?php endif; ?>
    <?php if (session()->getTempdata('error')) : ?>
      <div class="toast" data-autohide="false">
        <div class="toast-header" style="margin-left: 20px;margin-right: 20;padding: 10px; background: red;color: white;font-weight: 500"><?= session()->getTempdata('error'); ?>
          <small class="text-muted"></small>
        </div>
      </div>

    <?php endif; ?>
  </div>
  <!---Php Meassge Show --->


  <!--=========== Start Footer SECTION ================-->
  <?= view('Home/footer_section'); ?>
  <!--=========== End Footer SECTION ================-->

  <!----Js  file Include --->
  <?= view('Home/js_file'); ?>
</body>

</html>

<script>
  $(document).ready(function() {
    $('.toast').toast('show');
  });
</script>