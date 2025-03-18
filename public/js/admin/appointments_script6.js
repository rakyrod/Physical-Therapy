function refreshAppointmentStatuses() {
    // Get all appointment elements
    const appointmentCards = document.querySelectorAll('[data-appointment-id]');
    
    if (appointmentCards.length === 0) return;
    
    // Collect all appointment IDs
    const appointmentIds = Array.from(appointmentCards)
        .map(card => card.getAttribute('data-appointment-id'))
        .filter(id => id && !id.toString().startsWith('new-'))
        .join(',');
    
    if (!appointmentIds) return;
    
    // Create form data for AJAX request
    const formData = new FormData();
    formData.append('ajax_action', 'get_statuses');
    formData.append('appointment_ids', appointmentIds);
    
    // Make AJAX request to get current statuses
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.appointments) {
            // Update each appointment card with the current status
            data.appointments.forEach(appointment => {
                const card = document.querySelector(`[data-appointment-id="${appointment.id}"]`);
                if (card) {
                    const currentStatus = card.getAttribute('data-status');
                    
                    // Only update if status has changed
                    if (currentStatus !== appointment.status) {
                        // Update the status attribute
                        card.setAttribute('data-status', appointment.status);
                        
                        // Update card appearance based on new status
                        let statusColor = '';
                        let statusIcon = '';
                        
                        switch(appointment.status) {
                            case 'Pending':
                                statusColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 border-l-4 border-yellow-500';
                                statusIcon = '<i class="fa-solid fa-clock size-3"></i>';
                                break;
                            case 'Scheduled':
                                statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400 border-l-4 border-blue-500';
                                statusIcon = '<i class="fa-solid fa-calendar-check size-3"></i>';
                                break;
                            case 'Completed':
                                statusColor = 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 border-l-4 border-green-500';
                                statusIcon = '<i class="fa-solid fa-check size-3"></i>';
                                break;
                            case 'Cancelled':
                                statusColor = 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 border-l-4 border-red-500';
                                statusIcon = '<i class="fa-solid fa-times size-3"></i>';
                                break;
                            case 'Rescheduled':
                                statusColor = 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-400 border-l-4 border-purple-500';
                                statusIcon = '<i class="fa-solid fa-rotate size-3"></i>';
                                break;
                        }
                        
                        // Remove all status-related classes and add the new ones
                        card.className = card.className.replace(/bg-\w+-\d+ text-\w+-\d+ dark:bg-\w+-\d+\/\d+ dark:text-\w+-\d+ border-l-\d+ border-\w+-\d+/g, '').trim();
                        card.className += ' appointment-card p-1 text-xs rounded ' + statusColor + ' truncate shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer';
                        
                        // Update the icon and time
                        const timeValue = card.getAttribute('data-time');
                        card.innerHTML = `
                            <div class="flex items-center gap-1">
                                ${statusIcon}
                                <span class="font-semibold">${timeValue}</span>
                            </div>
                        `;
                    }
                }
            });
        }
    })
    .catch(error => console.error('Error refreshing appointment statuses:', error));
}

/**
 * Check appointment availability before form submission
 * @param {Function} callback - Callback function with boolean result
 */
function checkAppointmentAvailability(callback) {
    const therapistId = document.getElementById('therapist').value;
    const appointmentDate = document.getElementById('appointment_date').value;
    const appointmentTime = document.getElementById('appointment_time').value;
    const isEdit = document.getElementById('editMode').value === 'true';
    const appointmentId = isEdit ? document.getElementById('editAppointmentId').value : 0;
    
    // Validate required fields
    if (!therapistId || !appointmentDate || !appointmentTime) {
        showNotification('Please fill in all required fields', 'error');
        callback(false);
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('bookAppointmentBtn');
    const originalBtnText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking availability...';
    
    // Create form data for checking availability
    const formData = new FormData();
    formData.append('ajax_action', 'check_availability');
    formData.append('therapist_id', therapistId);
    formData.append('appointment_date', appointmentDate);
    formData.append('appointment_time', appointmentTime);
    formData.append('appointment_id', appointmentId);
    
    // Make AJAX request to check availability
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        if (data.success) {
            // Slot is available, proceed with form submission
            callback(true);
        } else {
            // Slot is not available, show error message
            showNotification(data.message, 'error');
            callback(false);
        }
    })
    .catch(error => {
        console.error('Error checking availability:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        showNotification('Error checking availability. Please try again.', 'error');
        callback(false);
    });
}

// Helper function to add/update appointment to calendar
function addAppointmentToCalendar() {
    // Check if we're in edit mode
    const isEditMode = document.getElementById('editMode').value === "true";
    const appointmentId = document.getElementById('editAppointmentId').value;
    
    // Get form values
    const firstName = document.getElementById('first_name').value;
    const lastName = document.getElementById('last_name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value || '';
    const specialization = document.getElementById('specialization').value;
    const therapistId = document.getElementById('therapist').value;
    const therapistText = document.getElementById('therapist').options[document.getElementById('therapist').selectedIndex].text;
    const therapistName = therapistText.split(' - ')[0];
    const therapistSpecialization = document.getElementById('therapist').options[document.getElementById('therapist').selectedIndex].getAttribute('data-specialization') || '';
    const date = document.getElementById('appointment_date').value;
    const time = document.getElementById('appointment_time').value;
    const visitType = document.getElementById('visit_type').value;
    const status = document.getElementById('status').value;
    const notes = document.getElementById('notes').value || '';
    
    // Create form data for saving to database
    const formData = new FormData();
    formData.append('ajax_action', 'save_appointment');
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('specialization', specialization);
    formData.append('therapist_id', therapistId);
    formData.append('appointment_date', date);
    formData.append('appointment_time', time);
    formData.append('visit_type', visitType);
    formData.append('status', status);
    formData.append('notes', notes);
    
    // Add edit mode info if necessary
    if (isEditMode) {
        formData.append('edit_mode', 'true');
        formData.append('edit_appointment_id', appointmentId);
    }
    
    // Show loading state
    const submitBtn = document.getElementById('bookAppointmentBtn');
    const originalBtnText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    
    // Save to database first
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const patientName = `${firstName} ${lastName}`;
            
            if (isEditMode) {
                // Find the existing appointment card
                const appointmentCard = document.querySelector(`[data-appointment-id="${appointmentId}"]`);
                
                if (!appointmentCard) {
                    showNotification('Could not find the appointment to update.', 'error');
                    return;
                }
                
                const oldDate = appointmentCard.getAttribute('data-date');
                
                // Check if the date has changed
                if (oldDate !== date) {
                    // Remove from the old day's container
                    const oldContainer = appointmentCard.parentElement;
                    oldContainer.removeChild(appointmentCard);
                    
                    // Find the new day cell
                    const newDate = new Date(date);
                    const dayNumber = newDate.getDate();
                    
                    const calendarCells = document.querySelectorAll('.grid-cols-7 > div');
                    let targetCell = null;
                    
                    for (const cell of calendarCells) {
                        const dayDiv = cell.querySelector('div.font-medium');
                        if (dayDiv && dayDiv.textContent.trim() == dayNumber) {
                            targetCell = cell;
                            break;
                        }
                    }
                    
                    if (targetCell) {
                        // Look for or create container for appointments
                        let appointmentsContainer = targetCell.querySelector('.space-y-1');
                        
                        if (!appointmentsContainer) {
                            appointmentsContainer = document.createElement('div');
                            appointmentsContainer.className = 'space-y-1 overflow-y-auto max-h-16 custom-scrollbar';
                            targetCell.appendChild(appointmentsContainer);
                        }
                        
                        // Update the appointment card's attributes
                        updateAppointmentCardAttributes(appointmentCard, {
                            patientName,
                            therapistName,
                            therapistId,
                            therapistSpecialization,
                            date,
                            time,
                            status,
                            visitType,
                            notes
                        });
                        
                        // Add to the new day's container
                        appointmentsContainer.appendChild(appointmentCard);
                        
                        // Clean up empty containers
                        if (oldContainer.children.length === 0) {
                            oldContainer.parentElement.removeChild(oldContainer);
                        }
                    } else {
                        showNotification('Could not find the target date on the calendar.', 'error');
                        return;
                    }
                } else {
                    // Just update the existing card attributes
                    updateAppointmentCardAttributes(appointmentCard, {
                        patientName,
                        therapistName,
                        therapistId,
                        therapistSpecialization,
                        date,
                        time,
                        status,
                        visitType,
                        notes
                    });
                }
                
                showNotification('Appointment successfully updated!', 'success');
            } else {
                // For new appointments, use the real ID from the server
                const newAppointmentId = data.appointment_id;
                
                // Format date to get day number
                const appointmentDate = new Date(date);
                const dayNumber = appointmentDate.getDate();
                
                // Find the correct calendar cell for this day
                const calendarCells = document.querySelectorAll('.grid-cols-7 > div');
                let targetCell = null;
                
                for (const cell of calendarCells) {
                    // Look for the div containing just the day number
                    const dayDiv = cell.querySelector('div.font-medium');
                    if (dayDiv && dayDiv.textContent.trim() == dayNumber) {
                        targetCell = cell;
                        break;
                    }
                }
                
                if (targetCell) {
                    // Check if there's already a container for appointments
                    let appointmentsContainer = targetCell.querySelector('.space-y-1');
                    
                    if (!appointmentsContainer) {
                        // Create container for appointments if it doesn't exist
                        appointmentsContainer = document.createElement('div');
                        appointmentsContainer.className = 'space-y-1 overflow-y-auto max-h-16 custom-scrollbar';
                        targetCell.appendChild(appointmentsContainer);
                    }
                    
                    // Determine status color and icon
                    let statusColor = '';
                    let statusIcon = '';
                    
                    switch(status) {
                        case 'Pending':
                            statusColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 border-l-4 border-yellow-500';
                            statusIcon = '<i class="fa-solid fa-clock size-3"></i>';
                            break;
                        case 'Scheduled':
                            statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400 border-l-4 border-blue-500';
                            statusIcon = '<i class="fa-solid fa-calendar-check size-3"></i>';
                            break;
                        case 'Completed':
                            statusColor = 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 border-l-4 border-green-500';
                            statusIcon = '<i class="fa-solid fa-check size-3"></i>';
                            break;
                        case 'Cancelled':
                            statusColor = 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 border-l-4 border-red-500';
                            statusIcon = '<i class="fa-solid fa-times size-3"></i>';
                            break;
                        case 'Rescheduled':
                            statusColor = 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-400 border-l-4 border-purple-500';
                            statusIcon = '<i class="fa-solid fa-rotate size-3"></i>';
                            break;
                    }
                    
                    // Create a new appointment card
                    const appointmentCard = document.createElement('div');
                    appointmentCard.className = `appointment-card p-1 text-xs rounded ${statusColor} truncate shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer`;
                    
                    // Add data attributes
                    appointmentCard.setAttribute('data-appointment-id', newAppointmentId);
                    appointmentCard.setAttribute('data-patient-name', patientName);
                    appointmentCard.setAttribute('data-therapist-name', therapistName);
                    appointmentCard.setAttribute('data-therapist-id', therapistId);
                    appointmentCard.setAttribute('data-therapist-specialization', therapistSpecialization);
                    appointmentCard.setAttribute('data-status', status);
                    appointmentCard.setAttribute('data-time', time);
                    appointmentCard.setAttribute('data-date', date);
                    appointmentCard.setAttribute('data-visit-type', visitType);
                    appointmentCard.setAttribute('data-notes', notes);
                    
                    // Add event listeners
                    appointmentCard.onclick = function() { showAppointmentDetails(this); };
                    appointmentCard.onmouseover = function() { showAppointmentTooltip(this); };
                    appointmentCard.onmouseout = hideAppointmentTooltip;
                    
                    // Add content to appointment card
                    appointmentCard.innerHTML = `
                        <div class="flex items-center gap-1">
                            ${statusIcon}
                            <span class="font-semibold">${time}</span>
                        </div>
                    `;
                    
                    // Check if this is an emergency and add indicator
                    if (visitType === 'Emergency') {
                        appointmentCard.classList.add('border-r-4', 'border-red-500', 'animate-pulse');
                        
                        // Add emergency indicator icon
                        const timeContainer = appointmentCard.querySelector('div.flex');
                        const emergencyIcon = document.createElement('span');
                        emergencyIcon.className = 'inline-flex items-center justify-center w-4 h-4 ml-auto bg-red-600 text-white rounded-full';
                        emergencyIcon.title = 'Emergency';
                        emergencyIcon.innerHTML = '<i class="fa-solid fa-exclamation text-[8px]"></i>';
                        timeContainer.appendChild(emergencyIcon);
                    }
                    
                    // Add the new appointment to the container
                    appointmentsContainer.appendChild(appointmentCard);
                    
                    // Show success message
                    showNotification('Appointment successfully booked!', 'success');
                } else {
                    showNotification('Could not find the selected date on the calendar.', 'error');
                }
            }
        } else {
            showNotification(data.message || 'Error saving appointment', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving appointment:', error);
        showNotification('Error saving appointment: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.textContent = originalBtnText;
        
        // Close the modal
        closeAppointmentModal();
    });
    
    // Reset edit mode
    document.getElementById('editMode').value = "false";
    document.getElementById('editAppointmentId').value = "";
}

// Helper function to update appointment card attributes
function updateAppointmentCardAttributes(card, data) {
    card.setAttribute('data-patient-name', data.patientName);
    card.setAttribute('data-therapist-name', data.therapistName);
    card.setAttribute('data-therapist-id', data.therapistId);
    card.setAttribute('data-therapist-specialization', data.therapistSpecialization);
    card.setAttribute('data-date', data.date);
    card.setAttribute('data-time', data.time);
    card.setAttribute('data-status', data.status);
    card.setAttribute('data-visit-type', data.visitType);
    card.setAttribute('data-notes', data.notes);
    
    // Update status appearance
    let statusColor = '';
    let statusIcon = '';
    
    switch(data.status) {
        case 'Pending':
            statusColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 border-l-4 border-yellow-500';
            statusIcon = '<i class="fa-solid fa-clock size-3"></i>';
            break;
        case 'Scheduled':
            statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400 border-l-4 border-blue-500';
            statusIcon = '<i class="fa-solid fa-calendar-check size-3"></i>';
            break;
        case 'Completed':
            statusColor = 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 border-l-4 border-green-500';
            statusIcon = '<i class="fa-solid fa-check size-3"></i>';
            break;
        case 'Cancelled':
            statusColor = 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 border-l-4 border-red-500';
            statusIcon = '<i class="fa-solid fa-times size-3"></i>';
            break;
        case 'Rescheduled':
            statusColor = 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-400 border-l-4 border-purple-500';
            statusIcon = '<i class="fa-solid fa-rotate size-3"></i>';
            break;
    }
    
    // Remove all status-related classes and add the new ones
    card.className = card.className.replace(/bg-\w+-\d+ text-\w+-\d+ dark:bg-\w+-\d+\/\d+ dark:text-\w+-\d+ border-l-\d+ border-\w+-\d+/g, '').trim();
    card.className += ' appointment-card p-1 text-xs rounded ' + statusColor + ' truncate shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer';
    
    // Update the icon and time
    card.innerHTML = `
        <div class="flex items-center gap-1">
            ${statusIcon}
            <span class="font-semibold">${data.time}</span>
        </div>
    `;
}

// Helper function to format date
function formatDateForDisplay(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
}

// Open add appointment modal with pre-selected date
function openAppointmentModal(event, selectedDate = null) {
    // Prevent event from propagating
    if (event) {
        event.stopPropagation();
    }
    
    // Reset the form and edit mode
    document.getElementById('appointmentForm').reset();
    document.getElementById('editMode').value = "false";
    document.getElementById('editAppointmentId').value = "";
    document.getElementById('modalTitle').textContent = 'Book New Appointment';
    document.getElementById('bookAppointmentBtn').textContent = 'Book Appointment';
    
    // Default status to Scheduled for new appointments
    document.getElementById('status').value = 'Scheduled';
    
    const modal = document.getElementById('appointmentModal');
    const selectedDateInput = document.getElementById('selectedDate');
    const appointmentDateInput = document.getElementById('appointment_date');
    
    // If a date is passed, set it in both inputs
    if (selectedDate) {
        selectedDateInput.value = selectedDate;
        appointmentDateInput.value = selectedDate;
        document.getElementById('displaySelectedDate').textContent = formatDateForDisplay(selectedDate);
    } else {
        // If no specific date, use today's date
        const today = new Date().toISOString().split('T')[0];
        selectedDateInput.value = today;
        appointmentDateInput.value = today;
        document.getElementById('displaySelectedDate').textContent = formatDateForDisplay(today);
    }
    
    // Show the modal with animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-enter').classList.add('modal-enter-active');
    }, 10);
}

function closeAppointmentModal() {
    const modal = document.getElementById('appointmentModal');
    const modalContent = modal.querySelector('.modal-enter');
    
    // Add exit animation
    modalContent.style.transition = 'opacity 300ms, transform 300ms';
    modalContent.style.opacity = '0';
    modalContent.style.transform = 'translateY(20px)';
    
    // Hide modal after animation completes
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reset animation
        modalContent.style.transition = '';
        modalContent.style.opacity = '';
        modalContent.style.transform = '';
        modalContent.classList.remove('modal-enter-active');
        
        // Reset form and edit mode
        document.getElementById('appointmentForm').reset();
        document.getElementById('consultationFee').textContent = '₱0.00';
        document.getElementById('editMode').value = "false";
        document.getElementById('editAppointmentId').value = "";
        document.getElementById('modalTitle').textContent = 'Book New Appointment';
        document.getElementById('bookAppointmentBtn').textContent = 'Book Appointment';
    }, 300);
}

// Update therapist options based on selected specialization
function updateTherapistOptions() {
    const specialization = document.getElementById('specialization').value;
    const therapistSelect = document.getElementById('therapist');
    
    // Clear any current selection
    therapistSelect.value = '';
    
    // Reset consultation fee display
    document.getElementById('consultationFee').textContent = '₱0.00';
    
    if (!specialization) {
        // If no specialization selected, show only the default option
        Array.from(therapistSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        return;
    }
    
    // Show only matching therapists
    let matchFound = false;
    Array.from(therapistSelect.options).forEach(option => {
        if (option.value === '') {
            option.style.display = 'block'; // Always show the default option
        } else if (option.getAttribute('data-specialization') === specialization) {
            option.style.display = 'block';
            matchFound = true;
        } else {
            option.style.display = 'none';
        }
    });
    
    // Show notification if no therapists with selected specialization
    if (!matchFound) {
        showNotification('No therapists available for this specialization. Please select a different specialization.', 'error');
    }
}

// Update consultation fee when therapist is selected
function updateConsultationFee() {
    const therapistSelect = document.getElementById('therapist');
    const feeDisplay = document.getElementById('consultationFee');
    
    if (therapistSelect.value) {
        const selectedOption = therapistSelect.options[therapistSelect.selectedIndex];
        const fee = selectedOption.getAttribute('data-fee') || 0;
        feeDisplay.textContent = `₱${parseFloat(fee).toFixed(2)}`;
    } else {
        feeDisplay.textContent = '₱0.00';
    }
}

// Form validation
function validateAppointmentForm() {
    const form = document.getElementById('appointmentForm');
    let isValid = true;
    const validationMessages = document.getElementById('validationMessages');
    validationMessages.innerHTML = '';
    
    // Check required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
            
            // Add validation message
            const fieldName = field.previousElementSibling ? field.previousElementSibling.textContent.replace('*', '') : 'Field';
            const message = document.createElement('div');
            message.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2';
            message.innerHTML = `
                <div class="flex items-center">
                    <i class="fa-solid fa-exclamation-circle mr-2"></i>
                    <span>${fieldName} is required</span>
                </div>
            `;
            validationMessages.appendChild(message);
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    // Check email format
    const emailField = document.getElementById('email');
    if (emailField.value && !isValidEmail(emailField.value)) {
        isValid = false;
        emailField.classList.add('border-red-500');
        
        const message = document.createElement('div');
        message.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2';
        message.innerHTML = `
            <div class="flex items-center">
                <i class="fa-solid fa-exclamation-circle mr-2"></i>
                <span>Please enter a valid email address</span>
            </div>
        `;
        validationMessages.appendChild(message);
    }
    
    return isValid;
}

// Helper function to validate email format
function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}