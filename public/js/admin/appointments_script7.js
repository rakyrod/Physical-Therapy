function showNotification(message, type = 'success') {
    const notifications = document.getElementById('validationMessages');
    const notification = document.createElement('div');
    
    notification.className = type === 'success' 
        ? 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2 shadow-md' 
        : 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2 shadow-md';
        
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    notifications.appendChild(notification);
    
    // Remove the notification after 4 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-10px)';
        notification.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            notifications.removeChild(notification);
        }, 500);
    }, 4000);
}

// Function to refresh appointment statuses periodically
src="appointments_script6.js"

// Close when clicking outside modals
window.addEventListener('click', function(event) {
    const appointmentDetailsModal = document.getElementById('appointmentDetailsModal');
    const appointmentModal = document.getElementById('appointmentModal');
    
    // Only close modals if clicking directly on the modal background, not just anywhere on the document
    if (event.target === appointmentDetailsModal) {
        closeAppointmentDetails();
    }
    
    if (event.target === appointmentModal) {
        closeAppointmentModal();
    }
});

// Keyboard accessibility - close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAppointmentDetails();
        closeAppointmentModal();
    }
});

// Initialize dark mode toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            // Check if document has dark class
            if (document.documentElement.classList.contains('dark')) {
                // Switch to light mode
                document.documentElement.classList.remove('dark');
                localStorage.setItem('hs_theme', 'light');
            } else {
                // Switch to dark mode
                document.documentElement.classList.add('dark');
                localStorage.setItem('hs_theme', 'dark');
            }
        });
    }
    
    // Set up event listeners
    document.getElementById('therapist').addEventListener('change', updateConsultationFee);
    
    // Form submission
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateAppointmentForm()) {
            // Check availability before proceeding
            checkAppointmentAvailability(function(isAvailable) {
                if (isAvailable) {
                    // If available, proceed with adding appointment
                    addAppointmentToCalendar();
                }
            });
        }
    });
    
    // Initialize periodic status updates
    refreshAppointmentStatuses();
    setInterval(refreshAppointmentStatuses, 30000);  // Check every 30 seconds
});