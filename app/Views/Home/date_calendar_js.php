<script>
    const monthHeader = document.getElementById('monthHeader');
    const monthList = document.getElementById('monthList');
    const yearHeader = document.getElementById('yearHeader');
    const yearList = document.getElementById('yearList');
    const calendarDays = document.getElementById('calendarDays');
    const prevDateButton = document.getElementById('prevDate');
    const nextDateButton = document.getElementById('nextDate');
    const yearSelect = document.getElementById('yearSelect');

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const startYear = 2020; // Change this to your desired start year
    const endYear = 2030; // Change this to your desired end year

    let currentDate = new Date();
    var selectedDayNum = ''; //Just for addressing notices
    var selectedMonth = ''; //Just for addressing notices
    var selectedYear = ''; //Just for addressing notices

    function generateCalendar() {
        const currentMonth = currentDate.toLocaleString('default', {
            month: 'long'
        });
        const currentYear = currentDate.getFullYear();
        const daysInMonth = new Date(currentYear, currentDate.getMonth() + 1, 0).getDate();
        const current_day = '<?php echo date('j'); ?>';
        selectedMonth = currentMonth;
        selectedYear = currentYear;

        monthHeader.textContent = currentMonth;
        yearHeader.textContent = currentYear;

        calendarDays.innerHTML = '';

        for (let i = 1; i <= daysInMonth; i++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('calendar-day');
            if (current_day == i) {
                dayElement.classList.add('selected');
            }
            const date = new Date(currentYear, currentDate.getMonth(), i);
            const dayName = date.toLocaleString('default', {
                weekday: 'short'
            });
            const monthDays = i.toString().padStart(2, '0');

            dayElement.innerHTML = `<div class="day-name">${dayName}</div><div class="day-number">${monthDays}</div>`;
            calendarDays.appendChild(dayElement);

            // Disable the previous month's days
            if (currentYear === new Date().getFullYear() && currentDate.getMonth() === new Date().getMonth() - 1 && i < new Date().getDate()) {
                dayElement.classList.add('disabled-day');
            }

            dayElement.addEventListener('click', () => {
                if (!dayElement.classList.contains('disabled-day')) {
                    clearSelectedDate();
                    dayElement.classList.add('selected');

                    // Select the parent element with class "calendar-day selected"
                    const calendarDayDiv = document.querySelector(".calendar-day.selected");
                    const internalDayDiv = calendarDayDiv.querySelector(".day-number");
                    selectedDayNum = internalDayDiv.textContent;
                    console.log(selectedDayNum);
                    return selectedDayNum;
                }
            });
        }
    }

    function scrollDate(direction) {
        const scrollAmount = direction * (window.innerWidth / 3);
        calendarDays.scrollLeft += scrollAmount;
    }

    prevDateButton.addEventListener('click', () => {
        scrollDate(-1);
        hideSelectLists();
        clearSelectedDate();
    });

    nextDateButton.addEventListener('click', () => {
        scrollDate(1);
        hideSelectLists();
        clearSelectedDate();
    });

    monthHeader.addEventListener('click', () => {
        hideSelectLists();
        monthList.style.display = 'block';
        clearSelectedDate();
    });

    yearHeader.addEventListener('click', () => {
        hideSelectLists();
        yearList.style.display = 'block';
        clearSelectedDate();
    });

    months.forEach((month, index) => {
        const monthItem = document.createElement('div');
        monthItem.classList.add('select-item');
        monthItem.textContent = month;
        monthItem.addEventListener('click', () => {
            currentDate = new Date(currentDate.getFullYear(), index, 1);
            generateCalendar();
            monthHeader.textContent = month;
            hideSelectLists();
            clearSelectedDate();
        });
        monthList.appendChild(monthItem);
    });

    function generateYearOptions() {
        yearList.innerHTML = '';
        const currentYearInt = new Date().getFullYear(); // Get the current year
        const currentMonthInt = new Date().getMonth(); // Get the current month index (0-11)

        for (let year = startYear; year <= endYear; year++) {
            const yearItem = document.createElement('div');
            yearItem.classList.add('select-item');
            yearItem.textContent = year;

            if (year < currentYearInt || (year === currentYearInt && currentMonthInt === 0)) {
                // Disable years that are in the past or the current month is January (index 0)
                yearItem.classList.add('disabled'); // Add a CSS class for disabled years
            } else {
                yearItem.addEventListener('click', () => {
                    currentDate = new Date(year, currentDate.getMonth(), 1);
                    generateCalendar();
                    yearHeader.textContent = year;
                    hideSelectLists();
                    clearSelectedDate();
                });
            }
            yearList.appendChild(yearItem);
        }
    }

    generateYearOptions();
    generateCalendar();

    window.addEventListener('resize', () => {
        scrollDate(0);
    });

    function hideSelectLists() {
        monthList.style.display = 'none';
        yearList.style.display = 'none';
    }

    function clearSelectedDate() {
        const selectedDay = document.querySelector('.calendar-day.selected');
        if (selectedDay) {
            selectedDay.classList.remove('selected');
        }
    }
</script>