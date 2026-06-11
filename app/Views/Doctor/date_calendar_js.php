<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function () {
    // Select Doctor Dropdown
    var $doctorSelect = $("#doctorSelect");
    var $doctorItems = $doctorSelect.find(".select-items");
    var $doctorSelected = $doctorSelect.find(".select-selected");
    var $doctorItemElements = $doctorSelect.find(".select-item");
    var $doctorInputHidden = $doctorSelect.find("#dr_appoint");

    $doctorSelected.click(function (e) {
        e.stopPropagation();
        hideSelectLists();
        toggleSelectItems($doctorItems);
    });

    $doctorItemElements.click(function () {
        var selectedValue = $(this).data("value");
        var selectedText = $(this).text();
        $doctorSelected.text(selectedText);
        $doctorInputHidden.val(selectedValue);
        toggleSelectItems($doctorItems);
    });

    $(document).click(function (e) {
        if (!($(e.target).is('#monthHeader, #yearHeader') || $(e.target).parents('#monthList, #yearList').length > 0)) {
            $doctorItems.css("display", "none");
            hideSelectLists();
        }
    });

    function toggleSelectItems(items) {
        if (items.css("display") === "block") {
            items.css("display", "none");
        } else {
            items.css("display", "block");
        }
    }

    function hideSelectLists() {
        $('#monthList').css('display', 'none');
        $('#yearList').css('display', 'none');
    }

    // Calendar Dropdown
    const monthHeader = $('#monthHeader');
    const monthList = $('#monthList');
    const yearHeader = $('#yearHeader');
    const yearList = $('#yearList');
    const calendarDays = $('#calendarDays');
    const prevDateButton = $('#prevDate');
    const nextDateButton = $('#nextDate');
    const yearSelect = $('#yearSelect');
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

        // const startYear = 2020; // Change this to your desired start year
        // const endYear = 2030; // Change this to your desired end year

    let currentDate = new Date();
    var selectedDayNum = ''; // Just for addressing notices
    var selectedMonth = ''; // Just for addressing notices
    var selectedYear = ''; // Just for addressing notices

    function generateCalendar() {
        const currentMonth = currentDate.toLocaleString('default', {
            month: 'long'
        });
        const currentYear = currentDate.getFullYear();
        const daysInMonth = new Date(currentYear, currentDate.getMonth() + 1, 0).getDate();
        const currentDay = new Date().getDate(); // Get the current day dynamically

        selectedMonth = currentMonth;
        selectedYear = currentYear;

        $('#monthHeader').text(currentMonth);
        $('#yearHeader').text(currentYear);

        $('#calendarDays').empty();

        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = $('<div class="calendar-day"></div>');
            const date = new Date(currentYear, currentDate.getMonth(), i);

            if (date <= new Date() && i < currentDay) {
                dayElement.addClass('disabled-day');
            } else if (date < new Date(currentYear, currentDate.getMonth(), 1)) {
                dayElement.addClass('disabled-day');
            }

            if (i === currentDay && currentDate.getMonth() === new Date().getMonth()) {
                dayElement.addClass('selected');
            }

            const dayName = date.toLocaleString('default', {
                weekday: 'short'
            });
            const monthDays = i.toString().padStart(2, '0');

            dayElement.html(`<div class="day-name">${dayName}</div><div class="day-number">${monthDays}</div>`);
            $('#calendarDays').append(dayElement);

            dayElement.on('click', function () {
                if (!dayElement.hasClass('disabled-day')) {
                    clearSelectedDate();
                    dayElement.addClass('selected');

                    const calendarDayDiv = $('.calendar-day.selected');
                    const internalDayDiv = calendarDayDiv.find('.day-number');
                    selectedDayNum = internalDayDiv.text();
                    return selectedDayNum;
                }
            });
        }

        // After rendering, scroll to the position of the current date
        const currentDayElement = $('.selected');
        if (currentDayElement.length > 0) {
            const scrollLeftPosition = currentDayElement.position().left;
            $('#calendarDays').scrollLeft(scrollLeftPosition - ($('#calendarDays').width() / 3));   //Before that it was 3 in place of 1.5
        }
    }

    function scrollDate(direction) {
        const scrollAmount = direction * ($('#calendarDays').width() / 3);
        $('#calendarDays').scrollLeft($('#calendarDays').scrollLeft() + scrollAmount);
    }

    $('#prevDate').on('click', function () {
        scrollDate(-1);
        hideSelectLists();
    });

    $('#nextDate').on('click', function () {
        scrollDate(1);
        hideSelectLists();
    });

    $('#monthHeader').on('click', function (event) {
        if ($('#monthList').css('display') === 'none') {
            hideSelectLists(); // Hide other dropdowns
            generateMonthOptions();
            $('#monthList').css('display', 'block');
        } else {
            $('#monthList').css('display', 'none');
        }
        clearSelectedDate(event);
    });

    $('#yearHeader').on('click', function (event) {
        if ($('#yearList').css('display') === 'none') {
            hideSelectLists(); // Hide other dropdowns
            $('#yearList').css('display', 'block');
        } else {
            $('#yearList').css('display', 'none');
        }
        clearSelectedDate(event);
    });

    function generateMonthOptions() {
        $('#monthList').empty();

        const currentMonth = currentDate.getMonth();
        const currentYear = currentDate.getFullYear();

        for (let i = 0; i < 12; i++) {
            const monthIndex = i;
            const monthItem = $('<div class="select-item"></div>');

            if (currentYear === new Date().getFullYear() && monthIndex < new Date().getMonth()) {
                monthItem.addClass('disabled');
            }

            monthItem.text(months[monthIndex]);
            monthItem.on('click', () => {
                if (!monthItem.hasClass('disabled')) {
                    currentDate.setMonth(monthIndex);
                    currentDate.setDate(1); // Set to the 1st day when changing month
                    generateCalendar();
                    monthHeader.text(months[monthIndex]);
                    hideSelectLists();
                }
            });

            $('#monthList').append(monthItem);
        }
    }


    function generateYearOptions() {
        $('#yearList').empty();
        const currentYearInt = new Date().getFullYear();
        const currentMonthInt = new Date().getMonth();

        const startRange = currentYearInt;
        const endRange = startRange + 9; // Set the initial 10-year range

        for (let year = startRange; year <= endRange; year++) {
            const yearItem = $('<div class="select-item"></div>');
            yearItem.text(year);

            if (year < currentYearInt || (year === currentYearInt && currentMonthInt === 0)) {
                yearItem.addClass('disabled');
            } else {
                yearItem.on('click', function () {
                    currentDate = new Date(year, currentDate.getMonth(), 1);
                    generateCalendar();
                    $('#yearHeader').text(year);
                    hideSelectLists();
                });
            }
            $('#yearList').append(yearItem);
        }
    }


    generateYearOptions();
    generateCalendar();

    $(window).on('resize', function () {
        scrollDate(0);
    });

    function clearSelectedDate(event) {
        // Check if the click event was triggered by the month or year header
        const isMonthOrYearHeader = event && (
            event.target.id === 'monthHeader' ||
            event.target.id === 'yearHeader'
        );

        if (!isMonthOrYearHeader) {
            const selectedDay = $('.calendar-day.selected');
            if (selectedDay) {
                selectedDay.removeClass('selected');
            }
        }
    }
});
</script>