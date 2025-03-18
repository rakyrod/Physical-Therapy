
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabLinks = document.querySelectorAll('.nav-link');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all links and content
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });
        
        // Modal elements
        const addAdminModal = document.getElementById('addAdminModal');
        const deleteAdminModal = document.getElementById('deleteAdminModal');
        
        // Modal control event listeners
        document.getElementById('addAdminBtn').addEventListener('click', () => addAdminModal.classList.add('show'));
        document.getElementById('closeAddAdminModalBtn').addEventListener('click', () => addAdminModal.classList.remove('show'));
        document.getElementById('cancelAddAdminBtn').addEventListener('click', () => addAdminModal.classList.remove('show'));
        
        document.getElementById('cancelDeleteAdminBtn').addEventListener('click', () => deleteAdminModal.classList.remove('show'));
        
        // Toast close buttons
        document.querySelectorAll('.toast-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.toast').classList.remove('show');
            });
        });
        
        // Toggle for maintenance mode
        const maintenanceToggle = document.getElementById('maintenance_mode');
        const maintenanceText = document.getElementById('maintenance_mode_text');
        
        maintenanceToggle.addEventListener('change', function() {
            maintenanceText.textContent = this.checked ? 'Enabled' : 'Disabled';
        });
        
        // Color picker preview
        const themeColor = document.getElementById('theme_color');
        const previewButton = document.getElementById('preview-button');
        const previewAccent = document.getElementById('preview-accent');
        const previewBadge = document.getElementById('preview-badge');
        
        themeColor.addEventListener('input', function() {
            const color = this.value;
            previewButton.style.backgroundColor = color;
            previewAccent.style.backgroundColor = color;
            previewBadge.style.backgroundColor = `${color}4D`; // 30% opacity version
            document.querySelector('#theme_color + span').textContent = color;
        });
        
        // Load Admin Users
        function loadAdmins() {
            fetch('?action=get_admins')
                .then(response => response.json())
                .then(data => {
                    const adminTableBody = document.getElementById('adminTableBody');
                    
                    if (data.data.length === 0) {
                        adminTableBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-400">
                                    <div class="flex flex-col items-center py-6">
                                        <svg class="h-12 w-12 text-slate-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-white">No admin users found</h3>
                                        <p class="text-sm text-slate-400 mt-1">Create a new admin using the 'Add Admin' button</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    } else {
                        let rows = '';
                        data.data.forEach(admin => {
                            const formattedDate = new Date(admin.created_at).toLocaleDateString();
                            
                            rows += `
                                <tr>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-300">
                                        ${admin.id}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-semibold">
                                                ${admin.first_name.charAt(0)}${admin.last_name.charAt(0)}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-white">${admin.first_name} ${admin.last_name}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-300">
                                        ${admin.email}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-300">
                                        ${formattedDate}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="delete-admin p-1 rounded-md bg-[#1e293b] text-red-400 hover:text-red-300" data-id="${admin.id}">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        
                        adminTableBody.innerHTML = rows;
                        
                        // Add event listeners to delete buttons
                        document.querySelectorAll('.delete-admin').forEach(button => {
                            button.addEventListener('click', function() {
                                const adminId = this.getAttribute('data-id');
                                document.getElementById('delete_admin_id').value = adminId;
                                deleteAdminModal.classList.add('show');
                            });
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading admins:', error);
                    showErrorToast('Failed to load admin users');
                });
        }
        
        // Load admins on page load
        loadAdmins();
        
        // Add Admin Form Submission
        document.getElementById('saveAdminBtn').addEventListener('click', function() {
            const form = document.getElementById('addAdminForm');
            
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
                    addAdminModal.classList.remove('show');
                    loadAdmins(); // Reload admin list
                } else {
                    showErrorToast(data.message);
                }
            })
            .catch(error => {
                showErrorToast('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
        
        // Delete Admin Confirmation
        document.getElementById('confirmDeleteAdminBtn').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('deleteAdminForm'));
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    deleteAdminModal.classList.remove('show');
                    loadAdmins(); // Reload admin list
                } else {
                    showErrorToast(data.message);
                    deleteAdminModal.classList.remove('show');
                }
            })
            .catch(error => {
                showErrorToast('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
        
        // Settings Form Submission
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            // Add maintenance mode value (checkbox handling)
            formData.set('maintenance_mode', document.getElementById('maintenance_mode').checked ? '1' : '0');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                } else {
                    showErrorToast(data.message || 'Failed to update settings');
                }
            })
            .catch(error => {
                showErrorToast('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
        
        // Appearance Form Submission
        document.getElementById('appearanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                } else {
                    showErrorToast(data.message || 'Failed to update appearance settings');
                }
            })
            .catch(error => {
                showErrorToast('An error occurred. Please try again.');
                console.error('Error:', error);
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
    });

