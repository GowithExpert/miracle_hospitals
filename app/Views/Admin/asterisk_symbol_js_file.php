<script>
  $(document).ready(function() {
      checkAndHideRedStar('.departmentSelect', '.redStar');
			checkAndHideRedStar('.phone_mandatory', '.redStarphone');
    // Check if there's already a value on page load
    $('.asterisk').each(function() {
      if ($(this).val() !== '') {
        $(this).parent('.input-container').addClass('asterisk-hidden');
      }
    });

    // Add focus and blur event listeners
    $('.asterisk').on('focus', function() {
      $(this).parent('.input-container').addClass('asterisk-hidden');
    });

    $('.asterisk').on('blur', function() {
      if ($(this).val() === '') {
        $(this).parent('.input-container').removeClass('asterisk-hidden');
      }
    });


    // Event listener for phone_mandatory input
    $('.phone_mandatory').on('input', function() {
        checkAndHideRedStar('.phone_mandatory', '.redStarphone');
    });

    // Function to check the input value and hide red star
    function checkAndHideRedStar(inputClass, redStarClass) {
        var inputValue = $(inputClass).val().trim(); // Trim to handle white spaces
        if (inputValue) {
            $(redStarClass).hide();
        } else {
            $(redStarClass).show();
        }
    }
    // Check on page load for bloodpriceSelect
    $(document).ready(function() {
        checkAndHideRedStar('.bloodpriceSelect', '.redStarbloodpriceSelect');
    });
    // Check on page load for unitSelect
    $(document).ready(function() {
        checkAndHideRedStar('.unitSelect', '.redStarunitSelect');
    });
    // Check on page load for unitSelect
    $(document).ready(function() {
        checkAndHideRedStar('.sellingSelect', '.redStarsellingSelect');
    });

    // Event listener for bloodpriceSelect
    $('.bloodpriceSelect').change(function() {
        checkAndHideRedStar('.bloodpriceSelect', '.redStarbloodpriceSelect');
    });
    // Event listener for bloodpriceSelect
    $('.unitSelect').change(function() {
        checkAndHideRedStar('.unitSelect', '.redStarunitSelect');
    });
    // Event listener for bloodpriceSelect
    $('.unitSelect').change(function() {
        checkAndHideRedStar('.sellingSelect', '.redStarsellingSelect');
    });

    // Check on page load for genderSelect
    $(document).ready(function() {
        checkAndHideRedStar('.genderSelect', '.redStargenderSelect');
    });

    // Function to check the selected value and hide red star
    function checkAndHideRedStar(selectClass, redStarClass) {
        var selectedValue = $(selectClass).val();
        if (selectedValue) {
            $(redStarClass).hide();
        } else {
            $(redStarClass).show();
        }
    }
    // Event listener for departmentSelect
    $('.departmentSelect').change(function() {
        checkAndHideRedStar('.departmentSelect', '.redStar');
    });

    // Event listener for genderSelect
    $('.genderSelect').change(function() {
        checkAndHideRedStar('.genderSelect', '.redStargenderSelect');
    });
    // Initially hide the asterisk if there is text present
    checkInput();

    // Bind an input event to the input field with class 'input_box'
    $('.phone_mandatory').on('input', function() {
      checkInput();
    });

    function checkInput() {
      // Show or hide the asterisk based on whether there is text present
      if ($('.phone_mandatory').val().trim() !== '') {
        $('.asterisk_phone').hide();
      } else {
        $('.asterisk_phone').show();
      }
    }
  });
</script>