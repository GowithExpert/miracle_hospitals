
<!--=========== Start Header SECTION ================-->
  <?= view('Admin/header_admin'); ?>
<!--=========== End Header SECTION ==================-->


<!--=========== START Nav Bar Section Include ================-->
  <!-- <//?= view('Admin/nav_barmgt'); ?> --> <!-- Non-Logged-in Admin -->
  <?= view('Admin/top_bar'); ?> <!-- Loogged-in Admin Navigation bar -->
<!--=========== End Nav Bar Section Include ==================-->


<!-- ********************* Center section - START *********************-->
<?= view('Admin/ManageAssets/upload_wards_csv'); ?>

<!-- ********************* Center section - END ***********************-->


<!--=========== Start Footer SECTION ================-->
  <?= view('Home/footer_section'); ?>
<!--=========== End Footer SECTION ==================-->
