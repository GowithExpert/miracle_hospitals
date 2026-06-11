<!---Materialize js file include --->

<!-- /*******************************Now Working Code is ************************************/ -->
<!---Materialize js file include --->
<script type="text/javascript" src="<?= base_url('public/assets/jquery/jquery.js'); ?>"></script>
<!--Ajax js file include ---->
<script type="text/javascript" src="<?= base_url('public/assets/materialize/js/materialize.js'); ?>"></script>
<!--Chart js file --->
<!---custome js file include -->
<script type="text/javascript">
  // Encapsulate your existing code in an IIFE
  (function ($) {
    $(document).ready(function () {
      //modal script
      $('.modal').modal();
      //sidenav script
      $('.sidenav').sidenav();
      //collapsible
      $('.collapsible').collapsible();
      //dropdown script show start
      $('.dropdown-trigger').dropdown({
        coverTrigger: false
      });

      $('.carousel.carousel-slider').carousel({
        fullWidth: true,
        indicators: true
      });
    });

    $(document).ready(function () {
      $('.tooltipped').tooltip();
    });

    //Dependence Doctor FEE Script Section Start
    $(document).ready(function () {
      $('#doctor').change(function () {
        var id = $(this).val();
        $.ajax({
          url: '<?php echo site_url('Admin/get_doctor_fee_details/'); ?>' + id,
          method: "POST",
          data: {
            id: id
          },
          async: true,
          dataType: 'json',
          success: function (data) {
            var html = '';
            var i;
            for (i = 0; i < data.length; i++) {
              html += '<option value=' + data[i].doctor_fee + '>' + data[i].doctor_fee + '</option>';
              $('#doctor_fee').html(html);
            }
          }
        });
        return false;
      });
    });
    //Dependence Doctor FEE Script Section End
  })(jQuery); // Pass jQuery as a parameter to the IIFE
</script>
