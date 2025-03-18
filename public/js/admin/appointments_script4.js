function showAppointmentDetails(element) {
    const modal = document.getElementById('appointmentDetailsModal');
    const patientName = element.getAttribute('data-patient-name');
    const therapistName = element.getAttribute('data-therapist-name');
    const status = element.getAttribute('data-status');
    const time = element.getAttribute('data-time');
    const date = element.getAttribute('data-date');
    const visitType = element.getAttribute('data-visit-type');
    const notes = element.getAttribute('data-notes');
    
    // Format date for display
    const appointmentDate = new Date(date);
    const formattedDate = appointmentDate.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Update modal content
    document.getElementById('patient-name').textContent = patientName;
    document.getElementById('therapist-name').textContent = therapistName;
    document.getElementById('appointment-status').textContent = status;
    document.getElementById('appointment-time').textContent = time;
    document.getElementById('appointment-date').textContent = formattedDate;
    document.getElementById('appointment-visit-type').textContent = visitType;
    
    // Show or hide notes
    const notesContainer = document.getElementById('notes-container');
    const appointmentNotes = document.getElementById('appointment-notes');
    
    if (notes && notes.trim() !== '') {
        appointmentNotes.textContent = notes;
        notesContainer.classList.remove('hidden');
    } else {
        notesContainer.classList.add('hidden');
    }
    
    // Update status indicator color and button
    const statusIndicator = document.getElementById('status-indicator');
    const statusButton = document.getElementById('status-button');
    
    switch(status) {
        case 'Pending':
            statusIndicator.className = 'w-3 h-3 rounded-full bg-yellow-500 mr-2';
            statusButton.innerHTML = '<i class="fa-solid fa-calendar-check mr-2"></i>Confirm Appointment';
            statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-sm hover:shadow-md transition-all';
            break;
        case 'Scheduled':
            statusIndicator.className = 'w-3 h-3 rounded-full bg-blue-500 mr-2';
            statusButton.innerHTML = '<i class="fa-solid fa-check-circle mr-2"></i>Mark Completed';
            statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition-all';
            break;
        case 'Completed':
            statusIndicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
            statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
            statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow-md transition-all';
            break;
        case 'Cancelled':
            statusIndicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
            statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
            statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm hover:shadow-md transition-all';
            break;
        case 'Rescheduled':
            statusIndicator.className = 'w-3 h-3 rounded-full bg-purple-500 mr-2';
            statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
            statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm hover:shadow-md transition-all';
            break;
    }
    
    // Set up edit button click handler
    const editButton = document.getElementById('edit-button');
    editButton.onclick = function() {
        editAppointment(element);
    };
    
    // Add click event for status button
    statusButton.onclick = function() {
        let newStatus = '';
        
        switch(status) {
            case 'Pending':
                newStatus = 'Scheduled';
                break;
            case 'Scheduled':
                newStatus = 'Completed';
                break;
            case 'Completed':
            case 'Cancelled':
            case 'Rescheduled':
                newStatus = 'Scheduled';
                break;
        }
        
        // Show loading state on button
        const originalButtonHtml = statusButton.innerHTML;
        statusButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
        statusButton.disabled = true;
        
        if (updateAppointmentStatus(element.getAttribute('data-appointment-id'), newStatus)) {
            // Update the details modal content to reflect the change
            document.getElementById('appointment-status').textContent = newStatus;
            
            // Update the status indicator in the modal
            switch(newStatus) {
                case 'Pending':
                    statusIndicator.className = 'w-3 h-3 rounded-full bg-yellow-500 mr-2';
                    statusButton.innerHTML = '<i class="fa-solid fa-calendar-check mr-2"></i>Confirm Appointment';
                    statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 shadow-sm hover:shadow-md transition-all';
                    break;
                case 'Scheduled':
                    statusIndicator.className = 'w-3 h-3 rounded-full bg-blue-500 mr-2';
                    statusButton.innerHTML = '<i class="fa-solid fa-check-circle mr-2"></i>Mark Completed';
                    statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition-all';
                    break;
                case 'Completed':
                    statusIndicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
                    statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
                    statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm hover:shadow-md transition-all';
                    break;
                case 'Cancelled':
                    statusIndicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
                    statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
                    statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm hover:shadow-md transition-all';
                    break;
                case 'Rescheduled':
                    statusIndicator.className = 'w-3 h-3 rounded-full bg-purple-500 mr-2';
                    statusButton.innerHTML = '<i class="fa-solid fa-calendar mr-2"></i>Mark Scheduled';
                    statusButton.className = 'ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm hover:shadow-md transition-all';
                    break;
            }
            
            // Update the element's status attribute so tooltip shows correct info
            element.setAttribute('data-status', newStatus);
            
            // Re-enable button
            statusButton.disabled = false;
        } else {
            // Restore button on error
            statusButton.innerHTML = originalButtonHtml;
            statusButton.disabled = false;
        }
    };
    
    // Show modal with animation
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.modal-enter').classList.add('modal-enter-active');
    }, 10);
}