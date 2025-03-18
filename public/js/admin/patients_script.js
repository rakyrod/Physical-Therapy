const addPatientModal = document.getElementById('addPatientModal');
const editPatientModal = document.getElementById('editPatientModal');
const deletePatientModal = document.getElementById('deletePatientModal');

// Modal control event listeners
document.getElementById('addPatientBtn').addEventListener('click', () => addPatientModal.classList.add('show'));
document.getElementById('closeAddModalBtn').addEventListener('click', () => addPatientModal.classList.remove('show'));
document.getElementById('cancelAddBtn').addEventListener('click', () => addPatientModal.classList.remove('show'));

document.getElementById('closeEditModalBtn').addEventListener('click', () => editPatientModal.classList.remove('show'));
document.getElementById('cancelEditBtn').addEventListener('click', () => editPatientModal.classList.remove('show'));

document.getElementById('closeDeleteModalBtn')?.addEventListener('click', () => deletePatientModal.classList.remove('show'));
document.getElementById('cancelDeleteBtn').addEventListener('click', () => deletePatientModal.classList.remove('show'));

// Toast close buttons
document.querySelectorAll('.toast-close').forEach(button => {
    button.addEventListener('click', function() {
        this.closest('.toast').classList.remove('show');
    });
});

// Connect search input to DataTable
$('#searchInput').on('keyup', function() {
    patientsTable.search($(this).val()).draw();
});

// Initialize DataTable with dark theme
const patientsTable = $('#patientsTable').DataTable({
    ajax: {
        url: '?action=get_patients',
        dataSrc: function(json) {
            // Update stats
            totalPatients = json.data.length;
            orthopedicPatients = json.data.filter(p => p.treatment_needed === 'Orthopedic Physical Therapy').length;
            totalAppointments = json.data.reduce((sum, p) => sum + parseInt(p.appointment_count || 0), 0);
            completedAppointments = json.data.reduce((sum, p) => sum + parseInt(p.completed_appointments || 0), 0);
            
            // Update counters in UI
            document.getElementById('totalPatients').textContent = totalPatients;
            document.getElementById('orthopedicPatients').textContent = orthopedicPatients;
            document.getElementById('totalAppointments').textContent = totalAppointments;
            document.getElementById('patientCount').textContent = totalPatients;
            
            // Calculate and update completion rate
            completionRate = totalAppointments > 0 ? 
                Math.round((completedAppointments / totalAppointments) * 100) : 0;
            document.getElementById('completionRate').textContent = completionRate + '%';
            
            return json.data;
        }
    },
    columns: [
        { data: 'id' },
        { 
            data: null,
            render: function(data, type, row) {
                const initials = (data.first_name.charAt(0) + data.last_name.charAt(0)).toUpperCase();
                return `
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-semibold">
                            ${initials}
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-white">${data.first_name} ${data.last_name}</div>
                            <div class="text-xs text-slate-400">Patient #${data.id}</div>
                        </div>
                    </div>
                `;
            }
        },
        { 
            data: 'email',
            render: function(data, type, row) {
                return `<span class="text-sm text-slate-300">${data || 'Not provided'}</span>`;
            }
        },
        { 
            data: 'phone',
            render: function(data, type, row) {
                return `<span class="text-sm text-slate-300">${data || 'Not provided'}</span>`;
            }
        },
        { 
            data: 'therapist_name',
            render: function(data, type, row) {
                return `<span class="text-sm text-slate-300">${data || 'Not assigned'}</span>`;
            }
        },
        { 
            data: null,
            render: function(data, type, row) {
                // Only show specialization if a therapist is assigned
                if (!data.therapist_name) {
                    return '<span class="text-slate-400">Not assigned yet</span>';
                }
                
                // Show therapist's specialization
                if (!data.therapist_specialization) {
                    return '<span class="text-slate-400">No specialization</span>';
                }
                
                return `<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/30 text-blue-400">${data.therapist_specialization.replace('Physical Therapy', 'PT')}</span>`;
            }
        },
        { 
            data: 'completion_rate',
            render: function(data, type, row) {
                const totalAppointments = parseInt(row.appointment_count || 0);
                const completedAppointments = parseInt(row.completed_appointments || 0);
                
                if (totalAppointments === 0) {
                    return '<span class="text-xs text-slate-400">No appointments</span>';
                }
                
                return `
                    <div>
                        <div class="w-24 bg-[#1e293b] rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: ${data}%"></div>
                        </div>
                        <span class="text-xs text-slate-400 mt-1">${completedAppointments}/${totalAppointments} sessions</span>
                    </div>
                `;
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                // Determine status based on appointment data
                const hasAppointments = parseInt(row.appointment_count || 0) > 0;
                const hasTherapist = data.therapist_name ? true : false;
                
                let status, statusClass, statusIcon;
                
                if (hasTherapist && hasAppointments) {
                    status = 'Active';
                    statusClass = 'bg-green-900/30 text-green-400';
                    statusIcon = 'fa-check';
                } else {
                    status = 'New';
                    statusClass = 'bg-blue-900/30 text-blue-400';
                    statusIcon = 'fa-user-plus';
                }
                
                return `
                    <span class="px-2 py-1 inline-flex items-center text-xs rounded-full ${statusClass}">
                        <i class="fa-solid ${statusIcon} mr-1"></i>
                        ${status}
                    </span>
                `;
            }
        },
        { 
            data: null,
            render: function(data, type, row) {
                return `
                    <div class="flex items-center justify-end gap-2">
                        <button class="edit-patient p-1 rounded-md bg-[#1e293b] text-blue-400 hover:text-blue-300" data-id="${data.id}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button class="delete-patient p-1 rounded-md bg-[#1e293b] text-red-400 hover:text-red-300" data-id="${data.id}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
            }
        }
    ],
    responsive: true,
    order: [[0, 'desc']],
    language: {
        paginate: {
            previous: '<i class="fas fa-chevron-left"></i>',
            next: '<i class="fas fa-chevron-right"></i>'
        },
        search: '',
        searchPlaceholder: 'Search...',
        emptyTable: `
        <div class="flex flex-col items-center py-6">
            <svg class="h-12 w-12 text-slate-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-white">No patients found</h3>
            <p class="text-sm text-slate-400 mt-1">No patients have been added yet</p>
        </div>
        `
    },
    pagingType: 'simple',
    lengthChange: false,
    dom: '<"flex justify-between items-center mb-4"l>rt<"flex justify-between items-center mt-4"ip>',
    drawCallback: function(settings) {
        // After table is drawn, update our custom pagination
        updateCustomPagination(this.api());
    }
});

// Custom Pagination Function
function updateCustomPagination(api) {
    const info = api.page.info();
    const totalPages = info.pages;
    const currentPage = info.page + 1; // DataTables uses 0-based indexing
    
    // If no pages, hide pagination
    if (totalPages === 0) {
        $('#paginationContainer').hide();
        return;
    }
    
    // Show pagination container
    $('#paginationContainer').show();
    
    // Update info text
    const start = info.start + 1;
    const end = info.end;
    const total = info.recordsDisplay;
    $('#paginationInfo').text(`Showing ${start} to ${end} of ${total} patients`);
    
    // Create pagination links
    let links = '';
    
    // Previous link
    links += `
        <a href="#" class="pagination-link px-3 py-1 rounded-md bg-[#1e293b] text-slate-400 hover:bg-[#334155] flex items-center ${currentPage <= 1 ? 'opacity-50 pointer-events-none' : ''}" data-page="${currentPage - 1}">
            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Prev
        </a>
    `;
    
    // Page number links
    // Show 5 page numbers at most
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, startPage + 4);
    
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        links += `
            <a href="#" class="pagination-link size-8 flex justify-center items-center rounded-md ${isActive ? 'bg-blue-600 text-white' : 'bg-[#1e293b] text-slate-400 hover:bg-[#334155]'}" data-page="${i}">
                ${i}
            </a>
        `;
    }
    
    // Next link
    links += `
        <a href="#" class="pagination-link px-3 py-1 rounded-md bg-[#1e293b] text-slate-400 hover:bg-[#334155] flex items-center ${currentPage >= totalPages ? 'opacity-50 pointer-events-none' : ''}" data-page="${currentPage + 1}">
            Next
            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    `;
    
    // Update links container
    $('#paginationLinks').html(links);
    
    // Add event listeners to pagination links
    $('.pagination-link').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        api.page(page - 1).draw('page'); // Convert to 0-based index for DataTables
    });
}

// Add Patient Form Submission
document.getElementById('savePatientBtn').addEventListener('click', function() {
    const form = document.getElementById('addPatientForm');
    
    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Check if passwords match
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        showErrorToast('Passwords do not match');
        return;
    }
    
    // Submit form via AJAX
    const formData = new FormData(form);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message);
            form.reset();
            addPatientModal.classList.remove('show');
            patientsTable.ajax.reload();
        } else {
            showErrorToast(data.message);
        }
    })
    .catch(error => {
        showErrorToast('An error occurred. Please try again.');
        console.error('Error:', error);
    });
});

// Edit Patient - Load Data
$(document).on('click', '.edit-patient', function() {
    const patientId = $(this).data('id');
    
    fetch(`?action=get_patient&id=${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showErrorToast(data.error);
                return;
            }
            
            // Populate form
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_first_name').value = data.first_name;
            document.getElementById('edit_last_name').value = data.last_name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_phone').value = data.phone;
            document.getElementById('edit_treatment_needed').value = data.treatment_needed;
            document.getElementById('edit_medical_history').value = data.medical_history;
            
            editPatientModal.classList.add('show');
        })
        .catch(error => {
            showErrorToast('An error occurred. Please try again.');
            console.error('Error:', error);
        });
});

// Update Patient Form Submission
document.getElementById('updatePatientBtn').addEventListener('click', function() {
    const form = document.getElementById('editPatientForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message);
            editPatientModal.classList.remove('show');
            patientsTable.ajax.reload();
        } else {
            showErrorToast(data.message);
        }
    })
    .catch(error => {
        showErrorToast('An error occurred. Please try again.');
        console.error('Error:', error);
    });
});

// Delete Patient - Confirmation
let deletePatientId = null;

$(document).on('click', '.delete-patient', function() {
    deletePatientId = $(this).data('id');
    document.getElementById('delete_id').value = deletePatientId;
    deletePatientModal.classList.add('show');
});

// Confirm Delete Patient
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!deletePatientId) return;
    
    const formData = new FormData(document.getElementById('deletePatientForm'));
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message);
            deletePatientModal.classList.remove('show');
            patientsTable.ajax.reload();
        } else {
            showErrorToast(data.message);
        }
    })
    .catch(error => {
        showErrorToast('An error occurred. Please try again.');
        console.error('Error:', error);
    });
});

// Export functionality
document.getElementById('exportBtn').addEventListener('click', function() {
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "ID,Name,Email,Phone,Therapist,Specialization,Appointments,Completed\n";
    
    const data = patientsTable.data().toArray();
    
    data.forEach(function(row) {
        const specialization = row.therapist_name ? 
            (row.therapist_specialization || '') : 
            '';
        
        csvContent += `${row.id},"${row.first_name} ${row.last_name}","${row.email}","${row.phone || ''}","${row.therapist_name || 'Not assigned'}","${specialization}",${row.appointment_count || 0},${row.completed_appointments || 0}\n`;
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "patients_export_" + new Date().toISOString().slice(0, 10) + ".csv");
    document.body.appendChild(link);
    
    link.click();
    document.body.removeChild(link);
});

// Filter functionality
document.getElementById('filterBtn').addEventListener('click', function() {
    const dropdown = document.createElement('div');
    dropdown.className = 'absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-[#1e293b] text-white ring-1 ring-black ring-opacity-5 z-20';
    dropdown.innerHTML = `
        <div class="py-1">
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="all">All Patients</button>
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="Orthopedic">Orthopedic PT</button>
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="Neurological">Neurological PT</button>
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="Pediatric">Pediatric PT</button>
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="Geriatric">Geriatric PT</button>
            <button class="filter-option block w-full text-left px-4 py-2 text-sm text-slate-300 hover:bg-[#334155]" data-filter="Sports">Sports PT</button>
        </div>
    `;
    
    this.parentNode.style.position = 'relative';
    this.parentNode.appendChild(dropdown);
    
    document.querySelectorAll('.filter-option').forEach(option => {
        option.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            if (filter === 'all') {
                patientsTable.search('').draw();
            } else {
                patientsTable.search(filter).draw();
            }
            
            dropdown.remove();
        });
    });
    
    document.addEventListener('click', function closeDropdown(e) {
        if (!e.target.closest('#filterBtn') && !e.target.closest('.filter-option')) {
            if (dropdown.parentNode) dropdown.remove();
            document.removeEventListener('click', closeDropdown);
        }
    });
});

// Toast helper functions
function showSuccessToast(message) {
    const toast = document.getElementById('successToast');
    document.getElementById('successToastMessage').textContent = message;
    
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 5000);
}

function showErrorToast(message) {
    const toast = document.getElementById('errorToast');
    document.getElementById('errorToastMessage').textContent = message;
    
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 5000);
}

// Auto-refresh data every 30 seconds
setInterval(() => patientsTable.ajax.reload(null, false), 30000);
