<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Time Slots</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            /*margin: 0;*/
            padding: 0;
            color: #333;
            /*display: flex;*/
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            flex-direction: column;
        }

        #timeSlotsList {
            /*width: 95%;*/ /* Adjusted for full width */
            width: 100%;
            max-width: 1240px;
            padding: 0px 20px 15px 14px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            /*margin: 20px;*/
            overflow-y: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #2a7b6d;
        }

        /* Container for the shifts, all shifts should be side by side */
        .shift-container {
            display: flex; /* Use flexbox to display shifts in a row */
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 40px;
        }

        /* Individual shift containers, each one will have its own grid */
        .shift-column {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            width: 30%; /* Adjust width of each shift to be in one row */
        }

        .shift-column h3 {
            text-align: center;
            color: #2a7b6d;
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .shift-column label {
            font-size: 1.1em;
            cursor: pointer;
        }

        /* Grid for the time slots in three columns */
        .time-slots-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Three columns */
            gap: 5px;
        }

        .time-slot {
            font-size: 1.1em;
            padding: 10px;
            margin: 5px 0;
            background-color: #dbe7f3;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .time-slot:hover {
            background-color: #2a86d5; /*#337ab7;*/
            color: #fff;
        }

        .selected {
            background-color: #0071d3; /*#337ab7;*/
            color: white;
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .shift-container {
                flex-direction: column; /* Stack shifts in one column on small screens */
                gap: 20px;
            }

            .shift-column {
                width: 100%; /* Each shift takes full width in mobile view */
            }

            .time-slots-container {
                grid-template-columns: 1fr; /* Stack time slots in a single column on mobile */
            }
        }
    </style>
</head>
<body>

    <div id="timeSlotsList">
        <h1>Shift Time Slots</h1> 
    </div>

<script>
$(document).ready(function() {

    function generateShiftSlots(startHour, endHour) {
            var slots = [];
            var currentHour = startHour;
            var currentMinute = 0;
            
            // Create 32 time slots (15-minute intervals)
            for (var i = 0; i < 32; i++) {
                var startHourFormatted = currentHour < 10 ? "0" + currentHour : currentHour;
                var startMinuteFormatted = currentMinute < 10 ? "0" + currentMinute : currentMinute;

                var endMinute = currentMinute + 15;
                var endHour = currentHour;
                if (endMinute === 60) {
                    endMinute = 0;
                    endHour = (currentHour + 1) % 24;  // Handle hour overflow
                }

                var endHourFormatted = endHour < 10 ? "0" + endHour : endHour;
                var endMinuteFormatted = endMinute < 10 ? "0" + endMinute : endMinute;

                var startTime = startHourFormatted + ":" + startMinuteFormatted;
                var endTime = endHourFormatted + ":" + endMinuteFormatted;
                slots.push(startTime + "--" + endTime);

                currentMinute = (currentMinute + 15) % 60;  // Increment minute
                if (currentMinute === 0) {
                    currentHour = (currentHour + 1) % 24; // Increment hour
                }
            }
            return slots;
        }

        // Function to display time slots for the shift
        function displayShift(shiftName, shiftSlots) {
            var shiftDiv = $('<div class="shift-column"></div>');
            
            // Shift title with checkbox
            var shiftHeader = $('<h3><input type="checkbox" class="select-all"> ' + shiftName + '</h3>');
            shiftDiv.append(shiftHeader);

            // Add event listener to checkbox for selecting/deselecting all slots
            shiftHeader.find('.select-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                shiftDiv.find('.time-slot').each(function() {
                    $(this).toggleClass('selected', isChecked);
                });
            });

            // Create time slots and display in three columns
            var slotsContainer = $('<div class="time-slots-container"></div>');
            for (var i = 0; i < shiftSlots.length; i++) {
                var slotDiv = $('<div class="time-slot">' + shiftSlots[i] + '</div>');
                // Toggle selected state on slot click
                slotDiv.on('click', function() {
                    $(this).toggleClass('selected');
                });
                slotsContainer.append(slotDiv);
            }

            shiftDiv.append(slotsContainer);
            return shiftDiv;
        }

    function generateTimeSlots() {
        // Generate and display the slots for each shift
        var shiftContainer = $('<div class="shift-container"></div>');

        // Morning Shift: 06:00 AM - 02:00 PM (32 slots)
        var morningShiftSlots = generateShiftSlots(6, 14);
        shiftContainer.append(displayShift("Morning Shift", morningShiftSlots));

        // Evening Shift: 02:00 PM - 10:00 PM (32 slots)
        var eveningShiftSlots = generateShiftSlots(14, 22);
        shiftContainer.append(displayShift("Evening Shift", eveningShiftSlots));

        // Night Shift: 10:00 PM - 06:00 AM (next day) (32 slots)
        var nightShiftSlots = generateShiftSlots(22, 6);
        shiftContainer.append(displayShift("Night Shift", nightShiftSlots));

        // Append the shifts to the main container
        $('#timeSlotsList').append(shiftContainer);
    }

    // Generate and display the slots for all shifts on page load
    generateTimeSlots();

    //**********************************

});
</script>
</body>
</html>
