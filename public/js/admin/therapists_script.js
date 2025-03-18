
    $(document).ready(function() {
        // Initialize variables
        let deleteTherapistId = null;
        let viewTherapistId = null;
        let editTherapistId = null;
        let searchTimeout = null;
        
        // Show toast notification
        function showToast(type, title, message) {
            const toast = $('#toast');
            const toastTitle = $('#toastTitle');
            const toastMessage = $('#toastMessage');
            const toastIcon = $('#toastIcon');
            
            // Set toast content
            toastTitle.text(title);
            toastMessage.text(message);
            
            // Set toast type
            toast.removeClass('success error');
            toast.addClass(type);
            
            // Set icon based on type
            if (type === 'success') {
                toastIcon.html('<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>');
            } else if (type === 'error') {
                toastIcon.html('<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>');
            }
            
            // Show toast
            toast.addClass('show');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                toast.removeClass('show');
            }, 5000);
        }
        
        // Close toast on button click
        $('#closeToastBtn').on('click', function() {
            $('#toast').removeClass('show');
        });
        
        // Live search functionality
        $('#searchInput').on('input', function() {
            const query = $(this).val().trim();
            
            // Show loading indicator
            $('#loadingIndicator').show();
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Set new timeout for delayed search
            searchTimeout = setTimeout(function() {
                // If search is empty, reload the page to reset
                if (query === '') {
                    window.location.href = window.location.pathname;
                    return;
                }
                
                // AJAX request for live search
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        action: 'live_search',
                        search: query
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update table with search results
                            updateTable(response.therapists);
                            
                            // Update count
                            $('#therapistCount').text(response.totalCount);
                            
                            // Update pagination (simplified for now)
                            $('#paginationContainer').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                        showToast('error', 'Search Error', 'An error occurred while searching. Please try again.');
                    },
                    complete: function() {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                    }
                });
            }, 500); // Delay for 500ms
        });
        
        // Clear search
        $('#clearSearch').on('click', function(e) {
            e.preventDefault();
            window.location.href = window.location.pathname;
        });
        
        // Update table with new data
        function updateTable(therapists) {
            const tableBody = $('#therapistsTable tbody');
            tableBody.empty();
            
            if (therapists.length === 0) {
                // No results
                tableBody.html(`
                <tr>
                    <td colspan="8" class="px-4 py-4 text-center">
                        <div class="flex flex-col items-center py-6">
                            <svg class="h-12 w-12 text-slate-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-white">No therapists found</h3>
                            <p class="text-sm text-slate-400 mt-1">
                                No therapists match your search criteria
                            </p>
                            <a href="?" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Clear Search
                            </a>
                        </div>
                    </td>
                </tr>
                `);
                return;
            }
            
            // Add rows for each therapist
            therapists.forEach(function(therapist) {
                // Generate initials
                const initials = (therapist.first_name.charAt(0) + therapist.last_name.charAt(0)).toUpperCase();
                const fullName = therapist.first_name + ' ' + therapist.last_name;
                
                // Format specialization
                const specialization = therapist.specialization || 'General';
                const shortSpecialization = specialization.replace('Physical Therapy', 'PT');
                
                // Format fee
                const fee = therapist.consultation_fee ? '₱' + parseFloat(therapist.consultation_fee).toFixed(2) : 'Not set';
                
                // Format slots
                const slots = therapist.available_slots || 'Not set';
                
                // Status and progress
                const status = therapist.status || 'Available';
                const statusClass = status === 'Available' ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400';
                const statusIcon = status === 'Available' ? 'fa-check' : 'fa-times';
                
                // Calculate progress percentage
                const total = parseInt(therapist.total_appointments) || 0;
                const completed = parseInt(therapist.completed_appointments) || 0;
                const progress = total > 0 ? Math.round((completed / total) * 100) : 0;
                
                // Create row
                const row = `
                <tr>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="bg-blue-500 text-white rounded-full h-10 w-10 flex items-center justify-center text-sm font-medium">
                                    ${initials}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">${fullName}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/30 text-blue-400">
                            ${shortSpecialization}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-300">${therapist.email}</div>
                        ${therapist.phone_number ? `<div class="text-sm text-slate-400">${therapist.phone_number}</div>` : ''}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-300">${fee}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-300">${slots}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            <i class="fa-solid ${statusIcon} mr-1"></i>
                            ${status}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div>
                            <div class="w-24 bg-[#1e293b] rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: ${progress}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 mt-1">${completed}/${total} completed</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <button class="view-therapist p-1 rounded-md bg-[#1e293b] text-slate-400 hover:text-white" data-id="${therapist.id}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button class="edit-therapist p-1 rounded-md bg-[#1e293b] text-blue-400 hover:text-blue-300" data-id="${therapist.id}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button class="delete-therapist p-1 rounded-md bg-[#1e293b] text-red-400 hover:text-red-300" data-id="${therapist.id}" data-name="${fullName}">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                `;
                
                tableBody.append(row);
            });
            
            // Reinitialize event handlers
            initEventHandlers();
        }
        
        // Initialize event handlers
        function initEventHandlers() {
            // View therapist button click
            $('.view-therapist').off('click').on('click', function() {
                const id = $(this).data('id');
                viewTherapistId = id;
                loadTherapistDetails(id, 'view');
            });
            
            // Edit therapist button click
            $('.edit-therapist').off('click').on('click', function() {
                const id = $(this).data('id');
                editTherapistId = id;
                loadTherapistDetails(id, 'edit');
            });
            
            // Delete therapist button click
            $('.delete-therapist').off('click').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                deleteTherapistId = id;
                
                // Update delete modal text
                $('#deleteConfirmText').text(`Are you sure you want to delete ${name}? This action cannot be undone.`);
                $('#delete_id').val(id);
                
                // Show modal
                $('#deleteTherapistModal').addClass('show');
            });
        }
        
        // Load therapist details for view or edit
        function loadTherapistDetails(id, mode) {
            $.ajax({
                url: '?action=get_therapist&id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        showToast('error', 'Error', data.error);
                        return;
                    }
                    
                    if (mode === 'view') {
                        // Set view modal data
                        const initials = (data.first_name.charAt(0) + data.last_name.charAt(0)).toUpperCase();
                        $('#view_avatar').text(initials);
                        $('#view_name').text(`${data.first_name} ${data.last_name}`);
                        $('#view_specialization').text(data.specialization || 'General');
                        $('#view_email').text(data.email);
                        $('#view_phone').text(data.phone_number || 'Not provided');
                        $('#view_fee').text(data.consultation_fee ? `$${parseFloat(data.consultation_fee).toFixed(2)}` : 'Not set');
                        $('#view_slots').text(data.available_slots || 'Not set');
                        
                        // Set status with appropriate color
                        const status = data.status || 'Available';
                        const statusClass = status === 'Available' ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400';
                        const statusIcon = status === 'Available' ? 'fa-check' : 'fa-times';
                        $('#view_status').html(`
                            <span class="px-2 py-1 inline-flex items-center text-xs rounded-full ${statusClass}">
                                <i class="fa-solid ${statusIcon} mr-1"></i> ${status}
                            </span>
                        `);
                        
                        $('#view_notes').text(data.notes || 'No additional notes provided.');
                        
                        // Show modal
                        $('#viewTherapistModal').addClass('show');
                    } else if (mode === 'edit') {
                        // Set edit form data
                        $('#edit_id').val(data.id);
                        $('#edit_first_name').val(data.first_name);
                        $('#edit_last_name').val(data.last_name);
                        $('#edit_email').val(data.email);
                        $('#edit_phone').val(data.phone_number || '');
                        $('#edit_specialization').val(data.specialization);
                        $('#edit_status').val(data.status || 'Available');
                        $('#edit_consultation_fee').val(data.consultation_fee || '');
                        $('#edit_available_slots').val(data.available_slots || '');
                        $('#edit_notes').val(data.notes || '');
                        $('#edit_password').val(''); // Clear password field
                        
                        // Show modal
                        $('#editTherapistModal').addClass('show');
                    }
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Error', 'Failed to load therapist details. Please try again.');
                    console.error('Load error:', error);
                }
            });
        }
        
        // Add new therapist - show modal
        $('#addTherapistBtn').on('click', function() {
            // Clear form
            $('#addTherapistForm')[0].reset();
            
            // Show modal
            $('#addTherapistModal').addClass('show');
        });
        
        // Save new therapist
        $('#saveTherapistBtn').on('click', function() {
            // Validate form
            const form = $('#addTherapistForm');
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }
            
            // Check if passwords match
            const password = $('#password').val();
            const confirmPassword = $('#confirm_password').val();
            if (password !== confirmPassword) {
                showToast('error', 'Password Error', 'Passwords do not match. Please try again.');
                return;
            }
            
            // Submit form via AJAX
            $.ajax({
                url: '',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success toast
                        showToast('success', 'Success', response.message);
                        
                        // Close modal
                        $('#addTherapistModal').removeClass('show');
                        
                        // Reload page after delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error toast
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
                    console.error('Save error:', error);
                }
            });
        });
        
        // Update therapist
        $('#updateTherapistBtn').on('click', function() {
            // Validate form
            const form = $('#editTherapistForm');
            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }
            
            // Submit form via AJAX
            $.ajax({
                url: '',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success toast
                        showToast('success', 'Success', response.message);
                        
                        // Close modal
                        $('#editTherapistModal').removeClass('show');
                        
                        // Reload page after delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error toast
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
                    console.error('Update error:', error);
                }
            });
        });
        
        // Delete therapist
        $('#confirmDeleteBtn').on('click', function() {
            // Submit form via AJAX
            $.ajax({
                url: '',
                type: 'POST',
                data: $('#deleteTherapistForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Show success toast
                        showToast('success', 'Success', response.message);
                        
                        // Close modal
                        $('#deleteTherapistModal').removeClass('show');
                        
                        // Reload page after delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error toast
                        showToast('error', 'Error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showToast('error', 'Error', 'An unexpected error occurred. Please try again.');
                    console.error('Delete error:', error);
                }
            });
        });
        
        // Edit from view modal
        $('#viewEditBtn').on('click', function() {
            // Close view modal
            $('#viewTherapistModal').removeClass('show');
            
            // Open edit modal with the same ID
            loadTherapistDetails(viewTherapistId, 'edit');
        });
        
        // Modal close buttons
        $('#closeAddModalBtn, #cancelAddBtn').on('click', function() {
            $('#addTherapistModal').removeClass('show');
        });
        
        $('#closeEditModalBtn, #cancelEditBtn').on('click', function() {
            $('#editTherapistModal').removeClass('show');
        });
        
        $('#cancelDeleteBtn').on('click', function() {
            $('#deleteTherapistModal').removeClass('show');
        });
        
        $('#closeViewModalBtn, #closeViewBtn').on('click', function() {
            $('#viewTherapistModal').removeClass('show');
        });
        
        // Initialize event handlers
        initEventHandlers();
    });
