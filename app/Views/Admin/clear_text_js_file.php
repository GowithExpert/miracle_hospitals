<!-- <script>
    //////////////////// Removing the text by clicking (x) button/////////////////////
    $(document).ready(function() {
        // Initially hide the clear icon
        $('#clear-input').hide();

        // Function to perform search
        function performSearch() {
            var inputValue = $('#input_box').val().trim();

            // You can customize this part to perform the actual search with the inputValue
            // For now, let's just log the value to the console
            console.log("Performing search for: " + inputValue);
        }

        // Function to show or hide the clear icon based on input
        function toggleClearIcon() {
            var inputValue = $('#input_box').val().trim();
            $('#clear-input').toggle(inputValue !== '');
        }

        // When there's input, show or hide the clear icon
        $('#input_box').on('input', function() {
            toggleClearIcon();
        });

        // Clicking on the clear icon clears the input, performs a search, and shows all results
        $('#clear-input').click(function() {
            $('#input_box').val('');
            performSearch(); // Perform search with empty input (show all results)
            toggleClearIcon(); // Toggle the clear icon based on the cleared input
        });

        // When the form is submitted, either perform search or show all results
        $('#search_doctor').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var inputValue = $('#input_box').val().trim();
            if (inputValue !== '') {
                performSearch(); // Perform search if there's input
            } else {
                showAllResults(); // Show all results if input is empty
            }
            toggleClearIcon(); // Toggle the clear icon based on the input after form submission
        });

        // Call toggleClearIcon when the page is refreshed
        toggleClearIcon();
    });
</script> -->
<script>$(document).ready(function() {
    // Initially hide the clear icon
    $('#clear-input').hide();

    // Function to perform search
    function performSearch() {
        var inputValue = $('#input_box').val();
        
        // Check if inputValue is not undefined or null before calling trim
        if (inputValue !== undefined && inputValue !== null) {
            inputValue = inputValue.trim();
            // You can customize this part to perform the actual search with the inputValue
            // For now, let's just log the value to the console
            console.log("Performing search for: " + inputValue);
        }
    }

    // Function to show or hide the clear icon based on input
    function toggleClearIcon() {
        var inputValue = $('#input_box').val();
        
        // Check if inputValue is not undefined or null before calling trim
        if (inputValue !== undefined && inputValue !== null) {
            inputValue = inputValue.trim();
            $('#clear-input').toggle(inputValue !== '');
        }
    }

    // When there's input, show or hide the clear icon
    $('#input_box').on('input', function() {
        toggleClearIcon();
    });

    // Clicking on the clear icon clears the input, performs a search, and shows all results
    $('#clear-input').click(function() {
        $('#input_box').val('');
        performSearch(); // Perform search with empty input (show all results)
        toggleClearIcon(); // Toggle the clear icon based on the cleared input
    });

    // When the form is submitted, either perform search or show all results
    $('#search_doctor').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var inputValue = $('#input_box').val();
        
        // Check if inputValue is not undefined or null before calling trim
        if (inputValue !== undefined && inputValue !== null) {
            inputValue = inputValue.trim();
            if (inputValue !== '') {
                performSearch(); // Perform search if there's input
            } else {
                showAllResults(); // Show all results if input is empty
            }
            toggleClearIcon(); // Toggle the clear icon based on the input after form submission
        }
    });

    // Call toggleClearIcon when the page is refreshed
    toggleClearIcon();
});
</script>