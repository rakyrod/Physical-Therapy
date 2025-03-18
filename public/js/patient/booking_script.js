
document.addEventListener('DOMContentLoaded', function() {
    // Automatically update specialization selection without needing to click Apply Filter
    const specializationSelect = document.getElementById('specialization');
    if (specializationSelect) {
        specializationSelect.addEventListener('change', function() {
            const selectedSpecialization = this.value;
            
            // Create a form and submit it to reload the page with the filter
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = window.location.pathname;
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'specialization';
            input.value = selectedSpecialization;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    }

    // Auto-refresh appointment statuses every 30 seconds
    function refreshAppointmentStatuses() {
        const appointmentElements = document.querySelectorAll('[data-appointment-id]');
        if (appointmentElements.length > 0) {
            // Collect all appointment IDs
            const appointmentIds = Array.from(appointmentElements).map(el => el.dataset.appointmentId).join(',');
            
            // Create form data
            const formData = new FormData();
            formData.append('ajax_action', 'get_statuses');
            formData.append('appointment_ids', appointmentIds);
            
            // Send AJAX request
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.appointments) {
                    data.appointments.forEach(appointment => {
                        // Find all elements with this appointment ID
                        const elements = document.querySelectorAll(`[data-appointment-id="${appointment.id}"]`);
                        elements.forEach(element => {
                            // Update status badge
                            const statusBadge = element.querySelector('.status-badge');
                            if (statusBadge) {
                                statusBadge.textContent = appointment.status;
                                // Remove all existing status classes
                                statusBadge.className = 'status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
                                
                                // Add new status class based on the status
                                switch(appointment.status.trim()) {
                                    case 'Confirmed':
                                    case 'Completed':
                                        statusBadge.classList.add('bg-teal-100', 'text-teal-800');
                                        break;
                                    case 'Pending':
                                    case 'Scheduled':
                                        statusBadge.classList.add('bg-blue-100', 'text-blue-800');
                                        break;
                                    case 'Cancelled':
                                        statusBadge.classList.add('bg-red-100', 'text-red-800');
                                        break;
                                    case 'Rescheduled':
                                        statusBadge.classList.add('bg-indigo-100', 'text-indigo-800');
                                        break;
                                    default:
                                        statusBadge.classList.add('bg-slate-100', 'text-slate-800');
                                }
                            }
                            
                            // Also handle tab changes if status has been updated
                            if (appointment.status === 'Cancelled' || appointment.status === 'Rescheduled') {
                                // If we're on the upcoming tab, might need to move this to another tab
                                const alpineData = Alpine.getElementData(document.querySelector('[x-data]'));
                                if (alpineData && alpineData.activeTab === 'upcoming') {
                                    // Remove element with animation
                                    element.style.transition = 'all 0.5s ease';
                                    element.style.opacity = '0';
                                    setTimeout(() => {
                                        element.style.height = '0';
                                        element.style.overflow = 'hidden';
                                        element.style.marginBottom = '0';
                                        element.style.padding = '0';
                                    }, 500);
                                }
                            }
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Error refreshing appointment statuses:', error);
            });
        }
    }
    
    // Initial refresh and set interval
    refreshAppointmentStatuses();
    setInterval(refreshAppointmentStatuses, 30000); // 30 seconds
    
    // Initialize date fields with current date
    const dateInputs = document.querySelectorAll('input[type="date"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (!input.value) {
            input.value = today;
        }
    });
    
    // Add event listener for therapist selection
    const therapistSelect = document.getElementById('therapist_id');
    if (therapistSelect) {
        therapistSelect.addEventListener('change', function() {
            // Fetch therapist data and update available slots display
            const selectedOption = this.options[this.selectedIndex];
            const therapistId = this.value;
            
            if (therapistId) {
                // You could add AJAX here to get more details about therapist availability
                // For now, we'll just show a simple message
                const therapistName = selectedOption.textContent.split('(')[0].trim();
                console.log(`Selected ${therapistName}`);
                
                // You could display available slots for the selected date
                // This would require another AJAX call to check the therapist's schedule
            }
        });
    }
    
    // Add event listener for date selection
    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            const therapistId = document.getElementById('therapist_id').value;
            
            if (therapistId && selectedDate) {
                // Here you could make an AJAX call to check for available time slots
                // on the selected date for the selected therapist
                console.log(`Checking availability for date: ${selectedDate}`);
                
                // You could update the time dropdown based on available slots
                // This would be more dynamic than static time options
            }
        });
    }
});
