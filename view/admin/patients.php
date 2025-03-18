<?php include('patients_model.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management | Thera Care</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="patients_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        blue: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd',
                            700: '#0082cd'
                        },
                        indigo: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
   
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body class="font-inter bg-gray-50 min-h-screen">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0f172a] text-gray-800 flex-shrink-0 border-r border-gray-200">
        <?php include('sidebar.php'); ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 overflow-y-auto">
        <!-- Header with Bottom Border Line -->
        <div class="px-6 py-4 border-b border-gray-200 w-full">
          <div class="flex items-center justify-between">
            <!-- Left Side -->
            <div class="flex-1 min-w-0">
              <h1 class="text-xl font-semibold text-slate-800 truncate">
                Patient Management
              </h1>
              <p class="text-sm text-slate-500 mt-1">
                Manage patients, appointments, and medical records
              </p>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center gap-2 sm:gap-3">
              <!-- Notification Button -->
              <div class="relative">
                <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors relative">
                  <i class="fa-solid fa-bell text-sm"></i>
                  <!-- Notification Indicator -->
                  <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
              </div>
              
              <!-- User Avatar -->
              <div class="relative inline-flex">
                <button id="user-dropdown" type="button" class="flex items-center gap-x-2 rounded-full border-2 border-gray-200 p-1 text-slate-700 hover:border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                  <img class="size-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="User avatar">
                  <span class="hidden md:inline-flex font-medium text-sm">Admin</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Cards with Dark Navy Theme -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Patients Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-900/40 text-blue-400">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Patients</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalPatients"><?php echo $stats['total_patients']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(5, 15); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Orthopedic Patients Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-emerald-900/40 text-emerald-400">
                            <i class="fas fa-walking"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Orthopedic Patients</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="orthopedicPatients"><?php echo $stats['orthopedic_patients']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(2, 10); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Appointments Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-purple-900/40 text-purple-400">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Appointments</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalAppointments"><?php echo $stats['total_appointments']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(10, 20); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Completion Rate Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-slate-400">Completion Rate</p>
                        <span class="text-white font-medium" id="completionRate"><?php echo $completionRate; ?>%</span>
                    </div>
                    <div class="w-full bg-[#1e293b] rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo $completionRate; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Management Table -->
        <div class="p-4 sm:p-6 md:p-8">
            <div class="bg-[#0f172a] rounded-xl overflow-hidden border border-[#1e293b] mb-8">
                <!-- Header -->
                <div class="p-4">
                    <div class="flex flex-wrap items-center justify-between">
                        <!-- Left side - Title and subtitle -->
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-semibold text-white">
                                    Patients
                                </h2>
                                <span class="inline-flex items-center justify-center size-6 rounded-md bg-blue-900/50 text-blue-400 text-sm font-medium" id="patientCount">
                                    <?php echo $stats['total_patients']; ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-400 mt-0.5">
                                Manage patient records and appointments
                            </p>
                        </div>
                        
                        <!-- Right side - Actions -->
                        <div class="flex items-center gap-2">
                            <!-- Search input -->
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3">
                                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" id="searchInput" class="py-2 px-3 ps-10 block w-full md:w-64 text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search patients..." autocomplete="off">
                            </div>
                            
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="animate-spin hidden">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            
                            <button id="filterBtn" class="px-3 py-2 bg-[#1e293b] text-white rounded-lg hover:bg-slate-700 transition flex items-center">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                            <button id="exportBtn" class="px-3 py-2 bg-[#1e293b] text-white rounded-lg hover:bg-slate-700 transition flex items-center">
                                <i class="fas fa-download mr-2"></i> Export
                            </button>
                            <button id="addPatientBtn" class="py-2 px-4 inline-flex items-center gap-x-1.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Patient
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#1e293b]" id="patientsTable">
                        <thead>
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    <div class="flex items-center">
                                        ID
                                        <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    <div class="flex items-center">
                                        Name
                                        <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Email
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Contact
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Therapist
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Treatment Needed
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Progress
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b]">
                            <!-- Table will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Custom Pagination - Will be populated by JavaScript -->
                <div id="paginationContainer">
                    <div class="px-4 py-3 flex items-center justify-between border-t border-[#1e293b]">
                        <div class="text-sm text-slate-400" id="paginationInfo">
                            Loading...
                        </div>
                        <div class="flex items-center space-x-2" id="paginationLinks">
                            <!-- Pagination links will be added here by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Patient Modal -->
<div id="addPatientModal" class="modal-backdrop">
    <div class="modal max-w-2xl rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Add New Patient
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Create a new patient record in the system
                    </p>
                </div>
                <button type="button" id="closeAddModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a] overflow-y-auto max-h-[60vh]">
            <form id="addPatientForm">
                <input type="hidden" name="action" value="add_patient">
                
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                    <!-- Basic Details -->
                    <div class="sm:col-span-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Basic Information</h4>
                    </div>
                    
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-slate-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-slate-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <!-- Contact Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Contact Information</h4>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Medical Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Medical Information</h4>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="treatment_needed" class="block text-sm font-medium text-slate-300 mb-1">Treatment Needed</label>
                        <select name="treatment_needed" id="treatment_needed" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Treatment (Optional)</option>
                            <option value="Orthopedic Physical Therapy">Orthopedic Physical Therapy</option>
                            <option value="Neurological Physical Therapy">Neurological Physical Therapy</option>
                            <option value="Pediatric Physical Therapy">Pediatric Physical Therapy</option>
                            <option value="Geriatric Physical Therapy">Geriatric Physical Therapy</option>
                            <option value="Sports Physical Therapy">Sports Physical Therapy</option>
                            <option value="Cardiopulmonary Physical Therapy">Cardiopulmonary Physical Therapy</option>
                            <option value="Vestibular Rehabilitation">Vestibular Rehabilitation</option>
                            <option value="Pelvic Floor Physical Therapy">Pelvic Floor Physical Therapy</option>
                            <option value="Oncologic Physical Therapy">Oncologic Physical Therapy</option>
                            <option value="Hand Therapy">Hand Therapy</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="medical_history" class="block text-sm font-medium text-slate-300 mb-1">Medical History</label>
                        <textarea name="medical_history" id="medical_history" rows="3" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                    </div>

                    <!-- Account Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Account Information</h4>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                        <input type="password" name="password" id="password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="6">
                        <p class="mt-1 text-xs text-slate-400">Minimum 6 characters</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-slate-300 mb-1">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="6">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="cancelAddBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Cancel
            </button>
            <button type="button" id="savePatientBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Add Patient
            </button>
        </div>
    </div>
</div>

<!-- Edit Patient Modal -->
<div id="editPatientModal" class="modal-backdrop">
    <div class="modal max-w-2xl rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Edit Patient
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Modify patient details and medical information
                    </p>
                </div>
                <button type="button" id="closeEditModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a] overflow-y-auto max-h-[60vh]">
            <form id="editPatientForm">
                <input type="hidden" name="action" value="update_patient">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                    <!-- Basic Details -->
                    <div class="sm:col-span-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Basic Information</h4>
                    </div>
                    
                    <div>
                        <label for="edit_first_name" class="block text-sm font-medium text-slate-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="edit_first_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_last_name" class="block text-sm font-medium text-slate-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="edit_last_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <!-- Contact Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Contact Information</h4>
                    </div>

                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-slate-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="edit_phone" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Medical Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Medical Information</h4>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="edit_treatment_needed" class="block text-sm font-medium text-slate-300 mb-1">Treatment Needed</label>
                        <select name="treatment_needed" id="edit_treatment_needed" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Select Treatment (Optional)</option>
                            <option value="Orthopedic Physical Therapy">Orthopedic Physical Therapy</option>
                            <option value="Neurological Physical Therapy">Neurological Physical Therapy</option>
                            <option value="Pediatric Physical Therapy">Pediatric Physical Therapy</option>
                            <option value="Geriatric Physical Therapy">Geriatric Physical Therapy</option>
                            <option value="Sports Physical Therapy">Sports Physical Therapy</option>
                            <option value="Cardiopulmonary Physical Therapy">Cardiopulmonary Physical Therapy</option>
                            <option value="Vestibular Rehabilitation">Vestibular Rehabilitation</option>
                            <option value="Pelvic Floor Physical Therapy">Pelvic Floor Physical Therapy</option>
                            <option value="Oncologic Physical Therapy">Oncologic Physical Therapy</option>
                            <option value="Hand Therapy">Hand Therapy</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="edit_medical_history" class="block text-sm font-medium text-slate-300 mb-1">Medical History</label>
                        <textarea name="medical_history" id="edit_medical_history" rows="3" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="cancelEditBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Cancel
            </button>
            <button type="button" id="updatePatientBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Update Patient
            </button>
        </div>
    </div>
</div>

<!-- Delete Patient Modal -->
<div id="deletePatientModal" class="modal-backdrop">
    <div class="modal max-w-md rounded-xl">
        <div class="p-6 bg-[#0f172a]">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-red-100/10 rounded-full p-4">
                        <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-white mb-2">Delete Patient</h3>
                <p class="text-sm text-slate-400">
                    Are you sure you want to delete this patient? This action cannot be undone and will delete all related appointments and medical records.
                </p>
            </div>
            
            <div class="flex justify-center gap-3 mt-6">
                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    Delete
                </button>
            </div>
            
            <form id="deletePatientForm">
                <input type="hidden" name="action" value="delete_patient">
                <input type="hidden" name="id" id="delete_id">
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="successToast" class="toast success">
    <div class="toast-header px-4 py-3 flex items-center">
        <svg class="h-5 w-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="font-medium">Success</span>
        <button type="button" class="ml-auto toast-close">
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="toast-body px-4 py-3 text-sm" id="successToastMessage">Operation completed successfully.</div>
</div>

<div id="errorToast" class="toast error">
    <div class="toast-header px-4 py-3 flex items-center">
        <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <span class="font-medium">Error</span>
        <button type="button" class="ml-auto toast-close">
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="toast-body px-4 py-3 text-sm" id="errorToastMessage">An error occurred. Please try again.</div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize counters
        let totalPatients = <?php echo $stats['total_patients']; ?>;
        let orthopedicPatients = <?php echo $stats['orthopedic_patients']; ?>;
        let totalAppointments = <?php echo $stats['total_appointments']; ?>;
        let completedAppointments = <?php echo $stats['completed_appointments']; ?>;
        let completionRate = <?php echo $completionRate; ?>;
        
        // Modal elements
    src="patients_script.js"
    });
</script>

<?php
// Close connection
$conn->close();
?>
</body>
</html>