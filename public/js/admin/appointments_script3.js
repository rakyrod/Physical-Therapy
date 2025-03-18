function editAppointment(element) {
    // Close the details modal
    closeAppointmentDetails();

    // Set edit mode
    document.getElementById('editMode').value = "true";
    document.getElementById('editAppointmentId').value = element.getAttribute('data-appointment-id');

    // Get appointment details from data attributes
    const patientName = element.getAttribute('data-patient-name').split(' ');
    const firstName = patientName[0] || '';
    const lastName = patientName.slice(1).join(' ') || '';
    const therapistId = element.getAttribute('data-therapist-id');
    const therapistSpecialization = element.getAttribute('data-therapist-specialization');
    const status = element.getAttribute('data-status');
    const time = element.getAttribute('data-time');
    const date = element.getAttribute('data-date');
    const visitType = element.getAttribute('data-visit-type');
    const notes = element.getAttribute('data-notes');

    // Get additional details via AJAX
    const formData = new FormData();
    formData.append('ajax_action', 'get_appointment_details');
    formData.append('appointment_id', element.getAttribute('data-appointment-id'));

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const appointment = data.appointment;

            // Set email and phone if available
            if (appointment.patient_email) {
                document.getElementById('email').value = appointment.patient_email;
            }
            if (appointment.patient_phone) {
                document.getElementById('phone').value = appointment.patient_phone;
            }

            // Set therapy specialization
               // Set therapy specialization
    if (appointment.therapy_specialization) {
        const specialization = appointment.therapy_specialization;
        const specializationSelect = document.getElementById('specialization');
        
        // Check if the option exists
        const optionExists = Array.from(specializationSelect.options).some(option => 
            option.value === specialization
        );
        
        // Add the option if it doesn't exist
        if (!optionExists && specialization) {
            const newOption = new Option(specialization, specialization);
            specializationSelect.add(newOption);
        }
        
        // Set the value
        document.getElementById('specialization').value = specialization;
    }
        }
    })
    .catch(error => {
        console.error('Error fetching appointment details:', error);
    });

    // Populate form fields
    document.getElementById('first_name').value = firstName;
    document.getElementById('last_name').value = lastName;

    // Set specialization (this should be done before setting therapist)
    if (therapistSpecialization) {
        document.getElementById('specialization').value = therapistSpecialization;
    }

    // Set therapist (this needs to be after setting specialization)
    setTimeout(() => {
        updateTherapistOptions();
        document.getElementById('therapist').value = therapistId;
        updateConsultationFee();
    }, 100);

    // Set date, time, and status
    document.getElementById('appointment_date').value = date;
    document.getElementById('appointment_time').value = time;
    document.getElementById('selectedDate').value = date;
    document.getElementById('status').value = status;

    // Set visit type and notes
    document.getElementById('visit_type').value = visitType;
    document.getElementById('notes').value = notes;

    // Update the displayed date
    document.getElementById('displaySelectedDate').textContent = formatDateForDisplay(date);

    // Change modal title and button text
    document.getElementById('modalTitle').textContent = 'Edit Appointment';
    document.getElementById('bookAppointmentBtn').textContent = 'Update Appointment';

    // Show the appointment modal
    const modal = document.getElementById('appointmentModal');
    modal.classList.remove('hidden');

    // Add animation
    setTimeout(() => {
        modal.querySelector('.modal-enter').classList.add('modal-enter-active');
    }, 10);
}