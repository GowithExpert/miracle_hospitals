
<script>
  const hiddenCountryCodeInput = document.getElementById('country_code');

  class PhoneNumberInput {
    constructor(inputElement) {
      this.phoneNumberInput = inputElement;
      this.iti = window.intlTelInput(this.phoneNumberInput, {
        preferredCountries: ['in', 'gb', 'ca', 'au'], // Set your preferred country codes here
        separateDialCode: true,
      });

      // Initialize the inputmask library for the phone number input
      // Inputmask().mask(this.phoneNumberInput);

      // Event listener to update the placeholder and set the hidden input value
      this.phoneNumberInput.addEventListener('countrychange', () => {
        this.updatePlaceholderAndSetCountryCode();
      });

      // Set the initial placeholder and set the hidden input value
      this.updatePlaceholderAndSetCountryCode();
    }
    updatePlaceholderAndSetCountryCode() {
      const selectedCountryCode = this.iti.getSelectedCountryData().dialCode;

      // Set the hidden input value to the selected country code
      hiddenCountryCodeInput.value = selectedCountryCode;

      // Update the placeholder
      const countryCodePlaceholder = `Enter Mobile Number (+${selectedCountryCode})`;
      this.phoneNumberInput.placeholder = countryCodePlaceholder;

      // Log the selected country code to the console
      //console.log('Selected Country Code:', selectedCountryCode);
    }
  }

  // Wait for the page to load before initializing the PhoneNumberInput class
  document.addEventListener('DOMContentLoaded', function() {
    const phoneInputElements = document.querySelectorAll('.phone-input');
    phoneInputElements.forEach((phoneInputElement) => {
      const phoneNumberInputInstance = new PhoneNumberInput(phoneInputElement);
    });
  });

  function updateCountryCode() {
    // Introduce a slight delay (e.g., 100 milliseconds) before searching for the element
    setTimeout(() => {
        const selectedFlagElement = document.querySelector('.iti__selected-flag');

        if (selectedFlagElement) {
            const selectedCountryCode = selectedFlagElement.getAttribute('title');

            // Set the hidden input value to the selected country code
            hiddenCountryCodeInput.value = selectedCountryCode;

            // Log the selected country code to the console
            console.log('Selected Country Code:', selectedCountryCode);

            // Submit the form after updating the country code
            document.getElementById('registration_form').submit();
        } else {
            console.error('.iti__selected-flag element not found.');
        }
    }, 100);

    // Return false to prevent the default form submission
    return false;
}

  function validateMobile(input) {
    input.value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
  }
</script>