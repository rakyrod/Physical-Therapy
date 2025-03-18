function updateAppointmentStatus(appointmentId, newStatus) {
    // Find the appointment card in the calendar
    const appointmentCard = document.querySelector(`[data-appointment-id="${appointmentId}"]`);
    
    if (!appointmentCard) {
        showNotification('Could not find the appointment to update.', 'error');
        return false;
    }
    
    // Create form data for AJAX request
    const formData = new FormData();
    formData.append('ajax_action', 'update_status');
    formData.append('appointment_id', appointmentId);
    formData.append('status', newStatus);
    
    // Show loading indicator
    const originalHtml = appointmentCard.innerHTML;
    appointmentCard.innerHTML = '<div class="flex items-center justify-center w-full"><i class="fas fa-spinner fa-spin"></i></div>';
    
    // Make AJAX request to update status in database
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update the status attribute
            appointmentCard.setAttribute('data-status', newStatus);
            
            // Update card appearance based on new status
            let statusColor = '';
            let statusIcon = '';
            
            switch(newStatus) {
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
            appointmentCard.className = appointmentCard.className.replace(/bg-\w+-\d+ text-\w+-\d+ dark:bg-\w+-\d+\/\d+ dark:text-\w+-\d+ border-l-\d+ border-\w+-\d+/g, '').trim();
            appointmentCard.className += ' appointment-card p-1 text-xs rounded ' + statusColor + ' truncate shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer';
            
            // Update the icon
            const timeValue = appointmentCard.getAttribute('data-time');
            appointmentCard.innerHTML = `
                <div class="flex items-center gap-1">
                    ${statusIcon}
                    <span class="font-semibold">${timeValue}</span>
                </div>
            `;
            
            // Show success notification
            showNotification(data.message, 'success');
        } else {
            // Restore original content
            appointmentCard.innerHTML = originalHtml;
            showNotification('Error: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Restore original content
        appointmentCard.innerHTML = originalHtml;
        showNotification('Error updating appointment status: ' + error, 'error');
        return false;
    });
    
    return true;
}