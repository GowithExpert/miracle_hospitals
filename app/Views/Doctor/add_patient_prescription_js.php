<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
  $(document).ready(function() {
    const timeSlotsContainer = $('#time-slots');
    const bookButton = $('#book-btn');
    const appointmentPopup = $('#appointment-popup');
    const closeButton = $('.close');
    const timeSlots = [
      '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM',
      '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM',
      '05:00 PM', '06:00 PM', '07:00 PM', '08:00 PM'
    ];

    function generateTimeSlots() {
      timeSlotsContainer.empty();
      $.each(timeSlots, function(index, slot) {
        const timeSlotElement = $('<div>').addClass('time-slot').text(slot);
        timeSlotElement.on('click', function() {
          clearSelection();
          timeSlotElement.addClass('selected');
          bookButton.prop('disabled', false);
        });
        timeSlotsContainer.append(timeSlotElement);
      });
    }

    function clearSelection() {
      const selectedSlot = $('.time-slot.selected');
      if (selectedSlot.length) {
        selectedSlot.removeClass('selected');
      }
    }

    function showAppointmentPopup(doctor, date, time) {
      const appointmentDoctor = $('#appointment-doctor');
      const appointmentDate = $('#appointment-date');
      const appointmentTime = $('#appointment-time');

      appointmentDoctor.text('Doctor: ' + doctor);
      appointmentDate.text('Date: ' + date);
      appointmentTime.text('Time: ' + time);

      appointmentPopup.show();
    }

    $('#date').on('input', function() {
      clearSelection();
      bookButton.prop('disabled', true);
      generateTimeSlots();
    });

    bookButton.on('click', function() {
      const selectedSlot = $('.time-slot.selected');
      const selectedDoctor = $('#doctor').val();
      const selectedDate = $('#date').val();
      const selectedTime = selectedSlot.text();

      showAppointmentPopup(selectedDoctor, selectedDate, selectedTime);
    });

    closeButton.on('click', function() {
      appointmentPopup.hide();
    });
  });
</script>
