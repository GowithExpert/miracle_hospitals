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
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.min.js"></script>
<script>
    // Function to format the date as "YYYY-MM-DD"
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Initialize the Pikaday date picker for Start Date
    const startDateInputs = document.querySelectorAll(".startDateInput");
    startDateInputs.forEach(startDateInput => {
        new Pikaday({
            field: startDateInput,
            format: "YYYY-MM-DD",
            placeholder: "Start Date",
            toString(date, format) {
                // Return formatted date without week name
                return formatDate(date);
            },
        });
    });
</script>