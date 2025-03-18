
document.addEventListener('DOMContentLoaded', function() {
        // Chart color scheme
        const colors = {
            blue: 'rgba(0, 130, 205, 1)',
            blueTransparent: 'rgba(0, 130, 205, 0.2)',
            green: 'rgba(16, 185, 129, 1)', 
            greenTransparent: 'rgba(16, 185, 129, 0.2)',
            red: 'rgba(239, 68, 68, 1)',
            redTransparent: 'rgba(239, 68, 68, 0.2)',
            yellow: 'rgba(245, 158, 11, 1)',
            yellowTransparent: 'rgba(245, 158, 11, 0.2)',
            purple: 'rgba(139, 92, 246, 1)',
            purpleTransparent: 'rgba(139, 92, 246, 0.2)',
            orange: 'rgba(249, 115, 22, 1)',
            orangeTransparent: 'rgba(249, 115, 22, 0.2)',
            pink: 'rgba(236, 72, 153, 1)',
            pinkTransparent: 'rgba(236, 72, 153, 0.2)',
            teal: 'rgba(20, 184, 166, 1)',
            tealTransparent: 'rgba(20, 184, 166, 0.2)',
            indigo: 'rgba(99, 102, 241, 1)',
            indigoTransparent: 'rgba(99, 102, 241, 0.2)',
            lime: 'rgba(132, 204, 22, 1)',
            limeTransparent: 'rgba(132, 204, 22, 0.2)',
        };
        
        // Chart.js global configuration
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.font.family = "'Inter', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.position = 'bottom';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.boxWidth = 6;
        Chart.defaults.plugins.legend.labels.padding = 15;
        
        // 1. Appointment Status Pie Chart
        const appointmentStatusData = <?php echo json_encode($appointmentStatusChart); ?>;
        const appointmentStatusCtx = document.getElementById('appointmentStatusChart').getContext('2d');
        
        new Chart(appointmentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: appointmentStatusData.map(item => item.label),
                datasets: [{
                    data: appointmentStatusData.map(item => item.value),
                    backgroundColor: [
                        colors.green,
                        colors.blue,
                        colors.yellow,
                        colors.red
                    ],
                    borderColor: '#0f172a',
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#1e293b',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
        
        // 2. Patient Specialization Chart
        const patientSpecData = <?php echo json_encode(array_map(function($item) {
            return [
                'label' => str_replace('Physical Therapy', 'PT', $item['specialization']), 
                'value' => (int)$item['count']
            ];
        }, $patientsBySpec)); ?>;
        
        const patientSpecCtx = document.getElementById('patientSpecializationChart').getContext('2d');
        
        new Chart(patientSpecCtx, {
            type: 'polarArea',
            data: {
                labels: patientSpecData.map(item => item.label),
                datasets: [{
                    data: patientSpecData.map(item => item.value),
                    backgroundColor: [
                        colors.blue,
                        colors.purple,
                        colors.orange,
                        colors.green,
                        colors.red,
                        colors.yellow,
                        colors.pink,
                        colors.teal,
                        colors.indigo,
                        colors.lime
                    ],
                    borderColor: '#0f172a',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        ticks: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        angleLines: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#1e293b',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12
                    }
                }
            }
        });
        
        // Function to fetch patients for a specific therapist
        async function fetchTherapistPatients(therapistId) {
            try {
                const response = await fetch(`fetch_therapist_patients.php?therapist_id=${therapistId}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return await response.json();
            } catch (error) {
                console.error('Error fetching patients:', error);
                return { success: false, message: 'Failed to load patients data', data: [] };
            }
        }
        
        // Function to generate the patient list HTML
        function generatePatientListHTML(patients, therapistName) {
            if (!patients || patients.length === 0) {
                return `
                    <div class="text-center py-8 text-slate-400">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 mb-3">
                            <i class="fas fa-user-slash text-slate-400"></i>
                        </div>
                        <p>No patients assigned to ${therapistName}</p>
                    </div>
                `;
            }
            
            let html = '';
            
            patients.forEach(patient => {
                const appointmentStatus = patient.latest_appointment_status || 'None';
                let statusClass = '';
                
                switch (appointmentStatus) {
                    case 'Completed': statusClass = 'text-green-400'; break;
                    case 'Scheduled': statusClass = 'text-blue-400'; break;
                    case 'Pending': statusClass = 'text-yellow-400'; break;
                    case 'Cancelled': statusClass = 'text-red-400'; break;
                    default: statusClass = 'text-slate-400';
                }
                
                html += `
                    <div class="bg-[#1e293b] rounded-lg p-4 hover:bg-slate-700 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-600/30 text-blue-400 flex items-center justify-center text-sm font-semibold">
                                    ${patient.first_name.charAt(0)}${patient.last_name.charAt(0)}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">${patient.first_name} ${patient.last_name}</div>
                                    <div class="text-xs text-slate-400 no-print">ID: ${patient.id}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs ${statusClass}">
                                    ${appointmentStatus}
                                </div>
                                <div class="text-xs text-slate-400">
                                    ${patient.appointment_count || 0} appointments
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-slate-400">Email:</span>
                                <span class="text-slate-300 ml-1">${patient.email || 'Not provided'}</span>
                            </div>
                            <div>
                                <span class="text-slate-400">Phone:</span>
                                <span class="text-slate-300 ml-1">${patient.phone || 'Not provided'}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-400">Specialization:</span>
                                <span class="text-slate-300 ml-1">${patient.treatment_needed || 'General'}</span>
                            </div>
                            ${patient.latest_appointment_date ? `
                                <div class="col-span-2">
                                    <span class="text-slate-400">Latest Appointment:</span>
                                    <span class="text-slate-300 ml-1">${patient.latest_appointment_date}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            });
            
            return html;
        }
        
        // Function to generate the printable patient list HTML
        function generatePrintableHTML(patients, therapistData) {
            const date = new Date().toLocaleDateString();
            
            let html = `
                <div class="print-container p-8">
                    <div class="mb-6 text-center">
                        <h1 class="text-2xl font-bold mb-1">TheraCare Patient Assignment</h1>
                        <h2 class="text-xl mb-1">Therapist: ${therapistData.name}</h2>
                        <p class="text-sm">Generated on ${date}</p>
                    </div>
                    
                    <div class="flex justify-between mb-6">
                        <div>
                            <p><strong>Therapist ID:</strong> ${therapistData.id}</p>
                            <p><strong>Specialization:</strong> ${therapistData.specialization || 'General'}</p>
                        </div>
                        <div>
                            <p><strong>Total Patients:</strong> ${patients.length}</p>
                        </div>
                    </div>
            `;
            
            if (!patients || patients.length === 0) {
                html += `
                    <div class="text-center py-8 border-t border-b">
                        <p class="text-lg">No patients currently assigned to this therapist</p>
                    </div>
                `;
            } else {
                html += `
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border p-2 text-left">Patient Name</th>
                                <th class="border p-2 text-left">Contact</th>
                                <th class="border p-2 text-left">Specialization</th>
                                <th class="border p-2 text-left">Appointments</th>
                                <th class="border p-2 text-left">Latest Status</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                patients.forEach(patient => {
                    html += `
                        <tr>
                            <td class="border p-2">${patient.first_name} ${patient.last_name}</td>
                            <td class="border p-2">
                                ${patient.email || 'N/A'}<br>
                                ${patient.phone || 'No phone'}
                            </td>
                            <td class="border p-2">${patient.treatment_needed || 'General'}</td>
                            <td class="border p-2">${patient.appointment_count || 0}</td>
                            <td class="border p-2">${patient.latest_appointment_status || 'None'}</td>
                        </tr>
                    `;
                });
                
                html += `
                        </tbody>
                    </table>
                `;
            }
            
            html += `
                <div class="mt-8">
                    <p class="text-sm"><em>This report is for internal use only. Confidential patient information.</em></p>
                </div>
            </div>
            `;
            
            return html;
        }
        
        // MOCK API for example purposes (replace with actual endpoint)
        // In a real implementation, you would have a fetch_therapist_patients.php endpoint
        // that returns this data from the database
        window.fetchTherapistPatients = async function(therapistId) {
            console.log(`Fetching patients for therapist ID: ${therapistId}`);
            
            // For demo purposes, return mock data
            // In production, this would be an actual fetch call
            return {
                success: true,
                data: [
                    {
                        id: 35,
                        first_name: "cev",
                        last_name: "Planas",
                        email: "cev@gmail.com",
                        phone: null,
                        treatment_needed: "Orthopedic Physical Therapy",
                        appointment_count: 3,
                        latest_appointment_date: "2025-03-25",
                        latest_appointment_status: "Completed"
                    },
                    {
                        id: 42,
                        first_name: "cxczxcz",
                        last_name: "zxczxczxc",
                        email: "cczxcz@gmail.com",
                        phone: "09568141099",
                        treatment_needed: "Orthopedic Physical Therapy",
                        appointment_count: 1,
                        latest_appointment_date: "2025-03-09",
                        latest_appointment_status: "Scheduled"
                    },
                    {
                        id: 43,
                        first_name: "jiiji",
                        last_name: "juju",
                        email: "jiji@gmail.com",
                        phone: "09568141099",
                        treatment_needed: "Orthopedic Physical Therapy",
                        appointment_count: 1,
                        latest_appointment_date: "2025-03-11",
                        latest_appointment_status: "Scheduled"
                    }
                ],
                therapist: {
                    id: therapistId,
                    name: "Kiarra Guradillo",
                    specialization: "Sports Physical Therapy"
                }
            };
        };

        // Modal Elements
        const patientsModal = document.getElementById('patientsModal');
        const modalTitle = document.getElementById('modalTitle');
        const patientsList = document.getElementById('patientsList');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const patientSearch = document.getElementById('patientSearch');
        const printPatientsBtn = document.getElementById('printPatientsBtn');
        const printContainer = document.getElementById('printContainer');
        
        // Current therapist data for modal
        let currentTherapistData = {
            id: null,
            name: '',
            patients: [],
            specialization: ''
        };
        
        // Show/Hide Modal Functions
        function showModal() {
            patientsModal.classList.remove('hidden');
        }
        
        function hideModal() {
            patientsModal.classList.add('hidden');
        }
        
        // Close modal with button
        closeModalBtn.addEventListener('click', hideModal);
        
        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !patientsModal.classList.contains('hidden')) {
                hideModal();
            }
        });
        
        // Click outside to close
        patientsModal.addEventListener('click', function(e) {
            if (e.target === patientsModal) {
                hideModal();
            }
        });
        
        // Patient search functionality
        patientSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredPatients = currentTherapistData.patients.filter(patient => {
                return (
                    patient.first_name.toLowerCase().includes(searchTerm) ||
                    patient.last_name.toLowerCase().includes(searchTerm) ||
                    patient.email?.toLowerCase().includes(searchTerm) ||
                    patient.phone?.toLowerCase().includes(searchTerm) ||
                    patient.treatment_needed?.toLowerCase().includes(searchTerm)
                );
            });
            
            // Update the list with filtered patients
            patientsList.innerHTML = generatePatientListHTML(filteredPatients, currentTherapistData.name);
        });
        
        // View Patients button click handlers
        document.querySelectorAll('.view-patients').forEach(button => {
            button.addEventListener('click', async function() {
                const therapistId = this.dataset.id;
                const therapistName = this.dataset.name;
                
                // Show loading state
                patientsList.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 mb-3">
                            <i class="fas fa-spinner fa-spin text-slate-400"></i>
                        </div>
                        <p>Loading patients...</p>
                    </div>
                `;
                
                modalTitle.textContent = `Patients assigned to ${therapistName}`;
                showModal();
                
                // Fetch patients data
                try {
                    const result = await window.fetchTherapistPatients(therapistId);
                    
                    if (result.success) {
                        // Store current therapist data
                        currentTherapistData = {
                            id: therapistId,
                            name: therapistName,
                            patients: result.data,
                            specialization: result.therapist?.specialization || ''
                        };
                        
                        // Update patients list
                        patientsList.innerHTML = generatePatientListHTML(result.data, therapistName);
                        
                        // Clear search
                        patientSearch.value = '';
                    } else {
                        patientsList.innerHTML = `
                            <div class="text-center py-8 text-slate-400">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 mb-3">
                                    <i class="fas fa-exclamation-triangle text-slate-400"></i>
                                </div>
                                <p>${result.message || 'Failed to load patients data'}</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    patientsList.innerHTML = `
                        <div class="text-center py-8 text-slate-400">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-800 mb-3">
                                <i class="fas fa-exclamation-triangle text-slate-400"></i>
                            </div>
                            <p>An error occurred while loading patients.</p>
                        </div>
                    `;
                }
            });
        });
        
        // Print patients list
        printPatientsBtn.addEventListener('click', function() {
            if (!currentTherapistData.id || !currentTherapistData.patients) {
                alert('No patient data available to print.');
                return;
            }
            
            // Generate and set the print content
            const therapistData = {
                id: currentTherapistData.id,
                name: currentTherapistData.name,
                specialization: currentTherapistData.specialization
            };
            
            printContainer.innerHTML = generatePrintableHTML(currentTherapistData.patients, therapistData);
            
            // Hide modal for printing
            hideModal();
            
            // Trigger print
            setTimeout(() => {
                window.print();
            }, 200);
        });
        
        // Direct print from export button
        document.querySelectorAll('.export-patients').forEach(button => {
            button.addEventListener('click', async function() {
                const therapistId = this.dataset.id;
                const therapistName = this.dataset.name;
                
                try {
                    const result = await window.fetchTherapistPatients(therapistId);
                    
                    if (result.success) {
                        // Generate and set the print content
                        const therapistData = {
                            id: therapistId,
                            name: therapistName,
                            specialization: result.therapist?.specialization || ''
                        };
                        
                        printContainer.innerHTML = generatePrintableHTML(result.data, therapistData);
                        
                        // Trigger print
                        setTimeout(() => {
                            window.print();
                        }, 200);
                    } else {
                        alert('Failed to load patients data for printing.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while loading patient data for printing.');
                }
            });
        });
        
        // Export Dashboard button
        document.getElementById('exportDashboardBtn').addEventListener('click', function() {
            // Create a CSV of basic stats
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "TheraCare Analytics Dashboard Report\n";
            csvContent += "Generated on," + new Date().toLocaleDateString() + "\n\n";
            
            csvContent += "Metric,Value\n";
            csvContent += `Total Patients,${<?php echo $stats['total_patients']; ?>}\n`;
            csvContent += `Total Therapists,${<?php echo $stats['total_therapists']; ?>}\n`;
            csvContent += `Total Appointments,${<?php echo $stats['total_appointments']; ?>}\n`;
            csvContent += `Completed Appointments,${<?php echo $stats['completed_appointments']; ?>}\n`;
            csvContent += `Scheduled Appointments,${<?php echo $stats['scheduled_appointments']; ?>}\n`;
            csvContent += `Pending Appointments,${<?php echo $stats['pending_appointments']; ?>}\n`;
            csvContent += `Cancelled Appointments,${<?php echo $stats['cancelled_appointments']; ?>}\n`;
            csvContent += `Completion Rate,${<?php echo $completionRate; ?>}%\n`;
            csvContent += `Total Revenue,${<?php echo json_encode($totalRevenue); ?>}\n\n`;
            
            // Add patient specialization breakdown
            csvContent += "Patient Specialization Breakdown\n";
            csvContent += "Specialization,Count\n";
            <?php foreach($patientsBySpec as $spec): ?>
            csvContent += `"<?php echo $spec['specialization']; ?>",<?php echo $spec['count']; ?>\n`;
            <?php endforeach; ?>
            
            csvContent += "\nReport generated by TheraCare Analytics System";
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "theracare_analytics_" + new Date().toISOString().slice(0, 10) + ".csv");
            document.body.appendChild(link);
            
            link.click();
            document.body.removeChild(link);
        });

        // Period selector
        document.getElementById('reportPeriod').addEventListener('change', function() {
            // In a real app, this would update the dashboard data for the selected period
            alert(`Reporting period changed to: ${this.options[this.selectedIndex].text}`);
        });
    });
