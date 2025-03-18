function showAppointmentTooltip(element) {
    // Clear any existing timer to prevent hiding if user moves between appointments quickly
    if (tooltipTimer) {
        clearTimeout(tooltipTimer);
    }
    
    const tooltip = document.getElementById('appointmentTooltip');
    const patientName = element.getAttribute('data-patient-name');
    const therapistName = element.getAttribute('data-therapist-name');
    const status = element.getAttribute('data-status');
    const time = element.getAttribute('data-time');
    const date = element.getAttribute('data-date');
    const visitType = element.getAttribute('data-visit-type');
    const isEmergency = visitType === 'Emergency';
    
    // Format date for display
    const appointmentDate = new Date(date);
    const formattedDate = appointmentDate.toLocaleDateString('en-US', { 
        month: 'long', 
        day: 'numeric',
        year: 'numeric'
    });
    
    // Update tooltip content
    document.getElementById('tooltip-patient').textContent = patientName;
    document.getElementById('tooltip-therapist').textContent = therapistName;
    document.getElementById('tooltip-status').textContent = status + (isEmergency? ' (Emergency)' : '');
    document.getElementById('tooltip-time').textContent = time;
    document.getElementById('tooltip-date').textContent = formattedDate;
    
    // Update status indicator color
    const tooltipStatusIndicator = document.getElementById('tooltip-status-indicator');
    
    switch(status) {
        case 'Pending':
            tooltipStatusIndicator.className = 'w-3 h-3 rounded-full bg-yellow-500 mr-2';
            break;
        case 'Scheduled':
            tooltipStatusIndicator.className = 'w-3 h-3 rounded-full bg-blue-500 mr-2';
            break;
        case 'Completed':
            tooltipStatusIndicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
            break;
        case 'Cancelled':
            tooltipStatusIndicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
            break;
        case 'Rescheduled':
            tooltipStatusIndicator.className = 'w-3 h-3 rounded-full bg-purple-500 mr-2';
            break;
    }
    
    // If it's an emergency, add a red border or background to the tooltip
    if (isEmergency) {
        tooltip.classList.add('border-red-500', 'border-r-4');
        document.getElementById('tooltip-status').classList.add('text-red-700', 'font-bold');
    } else {
        tooltip.classList.remove('border-red-500', 'border-r-4');
        document.getElementById('tooltip-status').classList.remove('text-red-700', 'font-bold');
    }
    
    // Position tooltip near the appointment card
    const rect = element.getBoundingClientRect();
    tooltip.style.top = rect.bottom + window.scrollY + 10 + 'px';
    tooltip.style.left = rect.left + window.scrollX + 'px';
    
    // Show tooltip with animation
    tooltip.classList.remove('hidden');
    tooltip.classList.add('tooltip-fade-in');
    tooltip.classList.remove('tooltip-fade-out');
}