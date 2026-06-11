<!-- Header Section Include - START  -->
<?= view('Home/header_home'); ?>
<?= view('Patients/header_patients'); ?>
<!-- Header Section Include - END  -->

<body>
  	<!-- Nav Bar Section Include - START  -->
  	<?= view('Home/nav_bar'); ?>
  	<!-- Nav Bar Section Include  - END -->

  	<!-- Nav Bar Section Include  - START -->
  	<?= view('Home/success_fail_page_msg'); ?>
  	<!-- Nav Bar Section Include  - END -->


  	
  	<!-- Center Section Include  - START -->
	<!-- <div class = "cntc"> This is working here</div> -->
	<!-- Center Section Include  - START -->


	<!-- Nav Bar Section Include  - START -->
  	<?= view('Patients/all_doctors'); ?>
  	<!-- Nav Bar Section Include  - END -->


	<?= view('Home/footer_home'); ?>
</body>
</html>