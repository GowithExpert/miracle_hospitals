<!--=========== Start Footer SECTION ================-->
  <?= view('Home/footer_section'); ?>
  <!--=========== End Footer SECTION ================-->
  <script>
    $(document).ready(function () {
      // Add a timeout function to hide the messages after 5 seconds
      setTimeout(function () {
          $("#succss_msg, #error_msg").fadeOut();
      }, 5000);
    });
  </script>


  <!--js file Include for asterisk symbol-->
  <?= view('Admin/asterisk_symbol_js_file.php'); ?>
  <!--js file Include for asterisk symbol-->
  <?= view('Admin/country_code_js_file.php'); ?>


  <?= view('Admin/show_pass_js_file.php'); ?>
  <?= view('Admin/js_file.php'); ?>
</body>
</html>


 
  
