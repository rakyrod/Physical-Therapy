
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for reschedule to today
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('date')) {
        document.getElementById('date').min = today;
    }
    
    // Initialize dynamic updates
    initDynamicUpdates();
});

// Global variable to track the last known appointment ID
let lastKnownAppointmentId = <?php echo $max_appointment_id; ?>;

// Function to check for new appointments
function initDynamicUpdates() {
    // Poll for updates every 15 seconds
    setInterval(checkForNewAppointments, 15000);
}

// Function to check for new appointments
function checkForNewAppointments() {
    // Create fetch request to check for new appointments
    fetch('?api=appointment-check&last_id=' + lastKnownAppointmentId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_updates) {
                // Update the last known appointment ID
                if (data.last_id && data.last_id > lastKnownAppointmentId) {
                    lastKnownAppointmentId = data.last_id;
                }
                
                // Update appointment counts
                if (data.counts) {
                    updateAppointmentCounts(data.counts);
                }
                
                // Update today's appointments if needed
                if (data.today_appointments && data.today_appointments.length > 0) {
                    updateTodayAppointments(data.today_appointments);
                }
                
                // Update all appointments if needed
                if (data.all_appointments && data.all_appointments.length > 0) {
                    updateAllAppointments(data.all_appointments);
                }
                
                // Show notification for new appointments
                if (data.new_appointment_count > 0) {
                    showNotification(
                        'New Appointments', 
                        `You have ${data.new_appointment_count} new appointment request(s)`, 
                        'success'
                    );
                }
            }
        })
        .catch(error => {
            console.error('Error checking for new appointments:', error);
        });
}

// Function to update appointment counts
function updateAppointmentCounts(counts) {
    // Update stat cards
    document.getElementById('total-count').textContent = counts.total;
    document.getElementById('pending-count').textContent = counts.pending;
    document.getElementById('scheduled-count').textContent = counts.scheduled;
    document.getElementById('completed-count').textContent = counts.completed;
    
    // Update progress bars
    if (counts.total > 0) {
        document.getElementById('pending-bar').style.width = ((counts.pending / counts.total) * 100) + '%';
        document.getElementById('scheduled-bar').style.width = ((counts.scheduled / counts.total) * 100) + '%';
        document.getElementById('completed-bar').style.width = ((counts.completed / counts.total) * 100) + '%';
    } else {
        document.getElementById('pending-bar').style.width = '0%';
        document.getElementById('scheduled-bar').style.width = '0%';
        document.getElementById('completed-bar').style.width = '0%';
    }
    
    // Update metrics
    document.getElementById('metrics-total').textContent = counts.total;
    document.getElementById('metrics-cancelled').textContent = counts.cancelled;
    document.getElementById('metrics-completed').textContent = counts.completed;
    
    // Calculate and update rates
    const completionRate = counts.total > 0 ? Math.round((counts.completed / counts.total) * 100) : 0;
    const cancellationRate = counts.total > 0 ? Math.round((counts.cancelled / counts.total) * 100) : 0;
    
    document.getElementById('completion-rate').textContent = completionRate + '%';
    document.getElementById('completion-bar').style.width = completionRate + '%';
    
    document.getElementById('cancellation-rate').textContent = cancellationRate + '%';
    document.getElementById('cancellation-bar').style.width = cancellationRate + '%';
}

// Function to update today's appointments
function updateTodayAppointments(appointments) {
    const container = document.getElementById('today-appointments-container');
    const noAppointmentsMessage = document.getElementById('no-appointments-message');
    let grid = document.getElementById('today-appointments-grid');
    
    // If there's a "no appointments" message and we have appointments, remove it
    if (noAppointmentsMessage && appointments.length > 0) {
        noAppointmentsMessage.remove();
        
        // Create a grid if it doesn't exist
        if (!grid) {
            grid = document.createElement('div');
            grid.id = 'today-appointments-grid';
            grid.className = 'grid grid-cols-1 md:grid-cols-2 gap-4';
            container.appendChild(grid);
        }
    }
    
    // Process each appointment
    appointments.forEach(appointment => {
        // Check if this appointment already exists in today's list
        const existingCard = document.querySelector(`.appointment-card[data-appointment-id="${appointment.id}"]`);
        
        if (existingCard) {
            // Update the existing card (status badge, actions, etc.)
            updateAppointmentCard(existingCard, appointment);
        } else {
            // Create a new card and add it to the grid
            const newCard = createAppointmentCard(appointment);
            
            // Add highlight animation for new cards
            newCard.classList.add('highlight-new');
            
            // Add to grid
            if (grid) {
                grid.appendChild(newCard);
            }
        }
    });
}

// Function to create an appointment card
function createAppointmentCard(appointment) {
    const card = document.createElement('div');
    card.className = 'bg-gray-800 rounded-lg p-4 appointment-card';
    card.setAttribute('data-appointment-id', appointment.id);
    
    // Format time for display
    const time = new Date(`2000-01-01T${appointment.time}`);
    const timeFormatted = time.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
    
    // Build card HTML
    let html = `
        <div class="flex justify-between items-start mb-3">
            <div>
                <h3 class="font-semibold">${appointment.patient_first_name} ${appointment.patient_last_name}</h3>
                <p class="text-sm text-gray-400">${timeFormatted}</p>
            </div>
            <div>
                ${getStatusBadgeHTML(appointment.status)}
            </div>
        </div>
        <p class="text-sm text-gray-300"><span class="font-medium">Type:</span> ${appointment.visit_type}</p>
        <p class="text-sm text-gray-300"><span class="font-medium">Treatment:</span> ${appointment.treatment_needed}</p>
        
        <div class="mt-3 space-x-2 flex">`;
    
    // Add action buttons based on status
    if (appointment.status === 'Pending') {
        html += `
            <form method="post" class="inline">
                <input type="hidden" name="appointment_id" value="${appointment.id}">
                <input type="hidden" name="action" value="accept">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                    <i class="fas fa-check mr-1"></i> Accept
                </button>
            </form>`;
    }
    
    if (['Pending', 'Scheduled', 'Rescheduled'].includes(appointment.status)) {
        html += `
            <button onclick="openRescheduleModal(${appointment.id})" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                <i class="fas fa-calendar-alt mr-1"></i> Reschedule
            </button>
            
            <button onclick="openCancelModal(${appointment.id})" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium">
                <i class="fas fa-times mr-1"></i> Cancel
            </button>
            
            <button onclick="openNotesModal(${appointment.id}, '${appointment.notes || ''}')" class="bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs font-medium">
                <i class="fas fa-edit mr-1"></i> Notes
            </button>`;
    }
    
    if (['Scheduled', 'Rescheduled'].includes(appointment.status)) {
        html += `
            <form method="post" class="inline">
                <input type="hidden" name="appointment_id" value="${appointment.id}">
                <input type="hidden" name="action" value="complete">
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 rounded text-xs font-medium">
                    <i class="fas fa-check-double mr-1"></i> Complete
                </button>
            </form>`;
    }
    
    html += `</div>`;
    
    card.innerHTML = html;
    return card;
}

// Function to update an existing appointment card
function updateAppointmentCard(card, appointment) {
    // Update status badge
    const statusBadgeContainer = card.querySelector('.flex.justify-between.items-start div:last-child');
    if (statusBadgeContainer) {
        statusBadgeContainer.innerHTML = getStatusBadgeHTML(appointment.status);
    }
    
    // Update action buttons based on new status
    const actionsContainer = card.querySelector('.mt-3.space-x-2.flex');
    if (actionsContainer) {
        actionsContainer.innerHTML = '';
        
        // Add action buttons based on status
        if (appointment.status === 'Pending') {
            const acceptForm = document.createElement('form');
            acceptForm.method = 'post';
            acceptForm.className = 'inline';
            acceptForm.innerHTML = `
                <input type="hidden" name="appointment_id" value="${appointment.id}">
                <input type="hidden" name="action" value="accept">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                    <i class="fas fa-check mr-1"></i> Accept
                </button>
            `;
            actionsContainer.appendChild(acceptForm);
        }
        
        if (['Pending', 'Scheduled', 'Rescheduled'].includes(appointment.status)) {
            // Reschedule button
            const rescheduleBtn = document.createElement('button');
            rescheduleBtn.className = 'bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium';
            rescheduleBtn.innerHTML = '<i class="fas fa-calendar-alt mr-1"></i> Reschedule';
            rescheduleBtn.setAttribute('onclick', `openRescheduleModal(${appointment.id})`);
            actionsContainer.appendChild(rescheduleBtn);
            
            // Cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.className = 'bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium';
            cancelBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Cancel';
            cancelBtn.setAttribute('onclick', `openCancelModal(${appointment.id})`);
            actionsContainer.appendChild(cancelBtn);
            
            // Notes button
            const notesBtn = document.createElement('button');
            notesBtn.className = 'bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs font-medium';
            notesBtn.innerHTML = '<i class="fas fa-edit mr-1"></i> Notes';
            notesBtn.setAttribute('onclick', `openNotesModal(${appointment.id}, '${appointment.notes || ''}')`);
            actionsContainer.appendChild(notesBtn);
        }
        
        if (['Scheduled', 'Rescheduled'].includes(appointment.status)) {
            const completeForm = document.createElement('form');
            completeForm.method = 'post';
            completeForm.className = 'inline';
            completeForm.innerHTML = `
                <input type="hidden" name="appointment_id" value="${appointment.id}">
                <input type="hidden" name="action" value="complete">
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 rounded text-xs font-medium">
                    <i class="fas fa-check-double mr-1"></i> Complete
                </button>
            `;
            actionsContainer.appendChild(completeForm);
        }
    }
}

// Function to get status badge HTML
function getStatusBadgeHTML(status) {
    let color = '';
    switch(status) {
        case 'Pending':
            color = 'bg-yellow-500/20 text-yellow-500';
            break;
        case 'Scheduled':
            color = 'bg-green-500/20 text-green-500';
            break;
        case 'Completed':
            color = 'bg-blue-500/20 text-blue-500';
            break;
        case 'Cancelled':
            color = 'bg-red-500/20 text-red-500';
            break;
        case 'Rescheduled':
            color = 'bg-purple-500/20 text-purple-500';
            break;
    }
    return `<span class="px-2 py-1 rounded text-xs font-medium ${color}">${status}</span>`;
}

// Function to update all appointments table
function updateAllAppointments(appointments) {
    const tbody = document.getElementById('all-appointments-tbody');
    const noAppointmentsRow = document.getElementById('no-appointments-row');
    
    // If there's a "no appointments" message and we have appointments, remove it
    if (noAppointmentsRow && appointments.length > 0) {
        noAppointmentsRow.remove();
    }
    
    // Process each appointment
    appointments.forEach(appointment => {
        // Check if this appointment already exists in the table
        const existingRow = tbody.querySelector(`tr[data-appointment-id="${appointment.id}"]`);
        
        if (existingRow) {
            // Update the existing row
            // (For simplicity, we'll just update the status badge - you could update more)
            const statusCell = existingRow.querySelector('td:nth-child(4)');
            if (statusCell) {
                statusCell.innerHTML = getStatusBadgeHTML(appointment.status);
            }
        } else {
            // Create a new row for this appointment
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-800 highlight-new';
            newRow.setAttribute('data-appointment-id', appointment.id);
            
            // Format date and time
            const date = new Date(appointment.date);
            const dateFormatted = date.toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
            const time = new Date(`2000-01-01T${appointment.time}`);
            const timeFormatted = time.toLocaleTimeString([], {hour: 'numeric', minute:'2-digit'});
            
            // Build row HTML
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium">
                        ${appointment.patient_first_name} ${appointment.patient_last_name}
                    </div>
                    <div class="text-sm text-gray-400">
                        ${appointment.patient_email}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        ${dateFormatted}
                    </div>
                    <div class="text-sm text-gray-400">
                        ${timeFormatted}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        ${appointment.visit_type}
                    </div>
                    <div class="text-sm text-gray-400">
                        ${appointment.treatment_needed}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${getStatusBadgeHTML(appointment.status)}
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm truncate max-w-xs">
                        ${appointment.notes ? appointment.notes : '<span class="text-gray-500 italic">No notes</span>'}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-3">
            `;
            
            // Add action buttons based on status
            if (appointment.status === 'Pending') {
                newRow.innerHTML += `
                    <form method="post" class="inline">
                        <input type="hidden" name="appointment_id" value="${appointment.id}">
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="text-green-500 hover:text-green-400">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                `;
            }
            
            if (['Pending', 'Scheduled', 'Rescheduled'].includes(appointment.status)) {
                newRow.innerHTML += `
                    <button onclick="openRescheduleModal(${appointment.id})" class="text-blue-500 hover:text-blue-400">
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                    
                    <button onclick="openCancelModal(${appointment.id})" class="text-red-500 hover:text-red-400">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            }
            
            newRow.innerHTML += `
                <button onclick="openNotesModal(${appointment.id}, '${appointment.notes || ''}')" class="text-purple-500 hover:text-purple-400">
                    <i class="fas fa-edit"></i>
                </button>
                
                <button onclick="openPatientDetailsModal('${appointment.patient_first_name} ${appointment.patient_last_name}', '${appointment.patient_email}', '${appointment.treatment_needed}', '${appointment.medical_history || ''}')" class="text-gray-400 hover:text-white">
                    <i class="fas fa-user"></i>
                </button>
            `;
            
            if (['Scheduled', 'Rescheduled'].includes(appointment.status)) {
                newRow.innerHTML += `
                    <form method="post" class="inline">
                        <input type="hidden" name="appointment_id" value="${appointment.id}">
                        <input type="hidden" name="action" value="complete">
                        <button type="submit" class="text-blue-500 hover:text-blue-400">
                            <i class="fas fa-check-double"></i>
                        </button>
                    </form>
                `;
            }
            
            newRow.innerHTML += `
                    </div>
                </td>
            `;
            
            // Add the row to the table at the appropriate position
            if (appointment.status === 'Pending') {
                // For pending appointments, add to the top
                if (tbody.firstChild) {
                    tbody.insertBefore(newRow, tbody.firstChild);
                } else {
                    tbody.appendChild(newRow);
                }
            } else {
                // For other statuses, add to the bottom
                tbody.appendChild(newRow);
            }
        }
    });
}

// Modal functions
function openRescheduleModal(appointmentId) {
    document.getElementById('reschedule_appointment_id').value = appointmentId;
    document.getElementById('rescheduleModal').classList.remove('hidden');
}

function openNotesModal(appointmentId, notes) {
    document.getElementById('notes_appointment_id').value = appointmentId;
    document.getElementById('notes').value = notes || '';
    document.getElementById('notesModal').classList.remove('hidden');
}

function openCancelModal(appointmentId) {
    document.getElementById('cancel_appointment_id').value = appointmentId;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function openPatientDetailsModal(name, email, treatment, history) {
    document.getElementById('patient_name').textContent = name;
    document.getElementById('patient_email').textContent = email;
    document.getElementById('patient_treatment').textContent = treatment;
    document.getElementById('patient_history').textContent = history || 'No medical history available';
    document.getElementById('patientDetailsModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Toggle notifications dropdown
function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    dropdown.classList.toggle('hidden');
}

// Close notifications when clicking outside
window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationsDropdown');
    const bell = document.getElementById('notificationBell');
    
    if (!dropdown.contains(event.target) && event.target !== bell && !bell.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target === modals[i]) {
            modals[i].classList.add('hidden');
        }
    }
};

// Function to show notifications
function showNotification(title, message, type) {
    const notification = document.createElement('div');
    notification.classList.add(
        'fixed', 'top-4', 'right-4', 'p-4', 'rounded-lg', 'shadow-lg',
        'max-w-md', 'z-50', 'flex', 'items-center', 'font-inter'
    );
    
    // Set background color based on type
    if (type === 'success') {
        notification.classList.add('bg-green-900', 'border-l-4', 'border-green-500');
    } else if (type === 'error') {
        notification.classList.add('bg-red-900', 'border-l-4', 'border-red-500');
    } else {
        notification.classList.add('bg-blue-900', 'border-l-4', 'border-blue-500');
    }
    
    notification.innerHTML = `
        <div class="mr-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'}
            </svg>
        </div>
        <div>
            <p class="font-bold text-white">${title}</p>
            <p class="text-gray-200">${message}</p>
        </div>
        <button class="ml-auto text-white hover:text-gray-300" onclick="this.parentElement.remove()">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (document.body.contains(notification)) {
            notification.remove();
        }
    }, 5000);
}
