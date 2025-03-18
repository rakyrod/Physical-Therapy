<?php include('appointments_model.php'); ?>


<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Thera Care - Appointment Management System">
    <title>Thera Care - Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="appointments_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    'inter': ['Inter', 'sans-serif']
                }
            }
        }
    }
    </script>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../favicon.ico">
    
    <!-- SF Pro Display from Apple (CDN) -->
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Theme Check -->
   
        <script src="appointments_script.js"></script>
   
    
    <!-- Custom CSS -->
    
</head>

<body class="font-inter bg-white min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- End Sidebar -->
    
    <!-- Improved Header -->
    <header class="sticky top-0 inset-x-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm transition-none lg:ps-64">
        <nav class="mx-auto w-full px-4 sm:px-6 md:px-8 py-3">
            <div class="flex items-center justify-between">
                <!-- Page Title and Description -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-semibold text-slate-800 truncate">
                        Appointments 
                    </h1>
                    <p class="text-sm text-slate-500 mt-1 hidden sm:block">
                        Manage patient appointments and schedule
                    </p>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Search Toggle (Mobile) -->
                    <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>

                    <!-- Notification Button -->
                    <div class="relative">
                        <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <!-- Notification Indicator -->
                            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            <span class="sr-only">Notifications</span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative inline-flex">
                        <button id="user-dropdown" type="button" class="flex items-center gap-x-2 rounded-full border-2 border-gray-200 p-1 text-slate-700 transition-none">
                            <img class="size-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="User avatar">
                            <span class="hidden md:inline-flex font-medium text-sm">Admin</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- End Header -->

    <!-- Main Content -->
    <div class="w-full lg:ps-64">
        <div class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto">
            
            <?php
            // Get current month and year
            $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
            $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
            ?>
            
            <!-- Calendar -->
            <div class="ios-card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-5 border-b border-slate-200 bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Left side - Title and subtitle -->
                        <div>
                            <h2 class="text-xl font-semibold text-slate-800">
                                Appointments Calendar
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">
                                View and manage therapy appointments
                            </p>
                        </div>
                        
                        <!-- Right side - Actions -->
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="?month=<?php echo $month == 1 ? 12 : $month - 1; ?>&year=<?php echo $month == 1 ? $year - 1 : $year; ?>" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm transition-none">
                                <i class="fa-solid fa-chevron-left size-4"></i>
                                <span>Previous</span>
                            </a>
                            
                            <span class="text-base font-semibold text-slate-800 bg-white py-2 px-4 rounded-lg shadow-sm border border-slate-200">
                                <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
                            </span>
                            
                            <a href="?month=<?php echo $month == 12 ? 1 : $month + 1; ?>&year=<?php echo $month == 12 ? $year + 1 : $year; ?>" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm transition-none">
                                <span>Next</span>
                                <i class="fa-solid fa-chevron-right size-4"></i>
                            </a>
                            
                            <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white shadow-md transition-none" href="javascript:void(0);" onclick="openAppointmentModal(event)">
                                <i class="fa-solid fa-plus size-4"></i>
                                <span>Add Appointment</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Header -->

                <!-- Calendar Grid - Adjusted to fit viewport better -->
                <div class="p-4 md:p-6">
                    <!-- Days of the week -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <?php
                        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        foreach ($days as $day) {
                            echo '<div class="text-center font-semibold text-sm py-2 text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded">' . $day . '</div>';
                        }
                        ?>
                    </div>
                    
                    <!-- Calendar dates -->
                    <div class="grid grid-cols-7 gap-1">
                        
                    <?php include('appointments_model2.php'); ?>

                    </div>
                </div>
                <!-- End Calendar Grid -->

                <!-- Improved Appointment Modal -->
                <div id="appointmentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[85vh] overflow-y-auto modal-enter">
                        <div class="p-3 border-b border-slate-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-slate-800 z-10">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white" id="modalTitle">Book New Appointment</h2>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Selected: <span id="displaySelectedDate" class="font-medium text-blue-600 dark:text-blue-400"></span></p>
                                </div>
                                <button type="button" onclick="closeAppointmentModal()" class="text-slate-500 hover:text-slate-700 dark:text-slate-300 dark:hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <form id="appointmentForm" class="p-4" method="POST" action="#">
                            <input type="hidden" name="selected_date" id="selectedDate">
                            <input type="hidden" name="patient_id" id="patientId" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
                            <input type="hidden" name="edit_mode" id="editMode" value="false">
                            <input type="hidden" name="edit_appointment_id" id="editAppointmentId" value="">
                            
                            <div class="space-y-3">
                                <!-- Simplified form with sections instead of tabs -->
                                <div class="space-y-3">
                                    <h3 class="text-sm font-semibold text-slate-800 dark:text-white border-b pb-2 border-slate-200 dark:border-slate-700">
                                        Patient Information
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="first_name" class="block text-xs font-medium text-slate-700 dark:text-slate-300">First Name*</label>
                                            <input type="text" name="first_name" id="first_name" required 
                                                class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                        </div>
                                        <div>
                                            <label for="last_name" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Last Name*</label>
                                            <input type="text" name="last_name" id="last_name" required 
                                                class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
    <div>
        <label for="email" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Email*</label>
        <input type="email" name="email" id="email" required
               class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
    </div>
    <div>
        <label for="phone" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Phone</label>
        <input type="tel" name="phone" id="phone"
               class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
    </div>
</div>
                                </div>
                                
                                <div class="space-y-3">
                                    <h3 class="text-sm font-semibold text-slate-800 dark:text-white border-b pb-2 border-slate-200 dark:border-slate-700">
                                        Appointment Details
                                    </h3>
                                    
                                    <div>
                                        <label for="specialization" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Therapy Specialization*</label>
                                        <select name="specialization" id="specialization" <?php echo !empty($availableSpecializations) ? 'required' : ''; ?> onchange="updateTherapistOptions()"
    class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
    <option value="">Select Specialization</option>
    <?php
    foreach ($availableSpecializations as $specialization) {
        echo '<option value="' . htmlspecialchars($specialization) . '">' . htmlspecialchars($specialization) . '</option>';
    }
    ?>
</select>
                                    </div>
                                    
                                    <div>
                                        <label for="therapist" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Select Therapist*</label>
                                        <select name="therapist_id" id="therapist" required onchange="updateConsultationFee()"
                                            class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                            <option value="">Select Therapist</option>
                                            <?php
                                            // Generate therapist options
                                            foreach ($therapists as $therapist) {
                                                echo '<option value="' . $therapist['id'] . '" data-specialization="' . htmlspecialchars($therapist['specialization']) . '" data-fee="' . htmlspecialchars($therapist['consultation_fee'] ?? 0) . '">' . 
                                                     htmlspecialchars($therapist['first_name'] . ' ' . $therapist['last_name']) . ' - ' . 
                                                     htmlspecialchars($therapist['specialization']) . 
                                                     (isset($therapist['consultation_fee']) ? ' (₱' . htmlspecialchars($therapist['consultation_fee']) . ')' : '') . 
                                                     '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="appointment_date" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Date*</label>
                                            <input type="date" name="appointment_date" id="appointment_date" required 
                                                class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                        </div>
                                        <div>
                                            <label for="appointment_time" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Time*</label>
                                            <select name="appointment_time" id="appointment_time" required 
                                                class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                                <option value="">Select Time</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="10:30">10:30 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="11:30">11:30 AM</option>
                                                <option value="12:00">12:00 PM</option>
                                                <option value="12:30">12:30 PM</option>
                                                <option value="13:00">1:00 PM</option>
                                                <option value="13:30">1:30 PM</option>
                                                <option value="14:00">2:00 PM</option>
                                                <option value="14:30">2:30 PM</option>
                                                <option value="15:00">3:00 PM</option>
                                                <option value="15:30">3:30 PM</option>
                                                <option value="16:00">4:00 PM</option>
                                                <option value="16:30">4:30 PM</option>
                                                <option value="17:00">5:00 PM</option>
                                            </select>
                                            <p class="text-xs text-slate-500 mt-1">Business hours: 10:00 AM - 5:00 PM</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="visit_type" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Visit Type*</label>
                                            <select name="visit_type" id="visit_type" required 
                                                class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                                <option value="">Select Type</option>
                                                <option value="Initial Consultation">Initial Consultation</option>
                                                <option value="Follow-up">Follow-up</option>
                                                <option value="Emergency">Emergency</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">Consultation Fee</label>
                                            <div id="consultationFee" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800 text-sm font-medium">₱0.00</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="status" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Status</label>
                                        <select name="status" id="status" 
                                            class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm">
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Rescheduled">Rescheduled</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="notes" class="block text-xs font-medium text-slate-700 dark:text-slate-300">Notes</label>
                                        <textarea name="notes" id="notes" rows="2" 
                                            class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-white text-sm"
                                            placeholder="Additional details..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Actions -->
                            <div class="mt-4 flex justify-end space-x-3 border-t border-slate-200 dark:border-slate-700 pt-3">
                                <button type="button" onclick="closeAppointmentModal()" 
                                    class="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors duration-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 text-sm">
                                    Cancel
                                </button>
                                <button type="submit" id="bookAppointmentBtn"
                                    class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                                    Book Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Appointment Details Modal -->
                <div id="appointmentDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeAppointmentDetails()"></div>
                        
                        <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all ios-card modal-enter">
                            <button type="button" onclick="closeAppointmentDetails()" class="absolute top-3 right-3 text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                <i class="fa-solid fa-times size-5"></i>
                            </button>
                            
                            <div class="flex items-center mb-4">
                                <div id="status-indicator" class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Appointment Details</h3>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Appointment Date</p>
                                    <p id="appointment-date" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100"></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Time</p>
                                    <p id="appointment-time" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100"></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Visit Type</p>
                                    <p id="appointment-visit-type" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100 flex items-center"></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</p>
                                    <p id="appointment-status" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100"></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Patient</p>
                                    <p id="patient-name" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100"></p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Therapist</p>
                                    <p id="therapist-name" class="mt-1 text-base font-semibold text-slate-900 dark:text-slate-100"></p>
                                </div>
                                
                                <div id="notes-container" class="hidden">
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Notes</p>
                                    <p id="appointment-notes" class="mt-1 text-base text-slate-900 dark:text-slate-100"></p>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex gap-3">
                                <button type="button" id="edit-button" class="ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm hover:shadow-md transition-all">
                                    <i class="fa-solid fa-pen-to-square mr-2"></i>
                                    Edit
                                </button>
                                <button type="button" id="status-button" class="ios-btn flex-1 inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-slate-200 dark:border-slate-600 dark:hover:bg-slate-600 shadow-sm hover:shadow-md transition-all">
                                    <i class="fa-solid fa-check-circle mr-2"></i>
                                    Mark Completed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Improved Appointment Tooltip with longer display time -->
                <div id="appointmentTooltip" class="absolute z-50 bg-white dark:bg-slate-800 shadow-lg rounded-xl p-3 w-72 hidden pointer-events-auto border border-slate-200 dark:border-slate-700 ios-card tooltip-fade-in">
                    <div class="flex items-center mb-2 border-b pb-2 border-slate-200 dark:border-slate-700">
                        <div id="tooltip-status-indicator" class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                        <p id="tooltip-status" class="text-sm font-medium text-slate-900 dark:text-slate-100">Scheduled</p>
                    </div>
                    
                    <div class="flex justify-between mb-2">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Date</p>
                            <p id="tooltip-date" class="text-sm font-semibold text-slate-900 dark:text-slate-100">March 15, 2025</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Time</p>
                            <p id="tooltip-time" class="text-sm font-semibold text-slate-900 dark:text-slate-100">10:00 AM</p>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Patient</p>
                        <div class="flex items-center">
                            <i class="fa-solid fa-user text-slate-400 size-4 mr-1.5"></i>
                            <p id="tooltip-patient" class="text-sm text-slate-800 dark:text-slate-200">John Doe</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Therapist</p>
                        <div class="flex items-center">
                            <i class="fa-solid fa-user-md text-slate-400 size-4 mr-1.5"></i>
                            <p id="tooltip-therapist" class="text-sm text-slate-800 dark:text-slate-200">Dr. Smith</p>
                        </div>
                    </div>
                </div>

                <!-- Add validation messages container -->
                <div id="validationMessages" class="fixed bottom-4 right-4 z-50"></div>
                
                <!-- Footer -->
                <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-2">
                            <div class="flex h-3 w-3 rounded-full bg-blue-500"></div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">Scheduled</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-3 w-3 rounded-full bg-yellow-500"></div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">Pending</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-3 w-3 rounded-full bg-green-500"></div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">Completed</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-3 w-3 rounded-full bg-red-500"></div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">Cancelled</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex h-3 w-3 rounded-full bg-purple-500"></div>
                            <p class="text-sm text-slate-700 dark:text-slate-300">Rescheduled</p>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <div class="inline-flex items-center justify-center w-4 h-4 bg-red-600 text-white rounded-full">
                                <i class="fa-solid fa-exclamation text-[8px]"></i>
                            </div>
                            <p class="text-sm text-slate-700 dark:text-slate-300 font-semibold">Emergency</p>
                        </div>
                    </div>
                </div>
                <!-- End Footer -->
            </div>
            <!-- End Calendar -->
        </div>
    </div>
    <!-- End Content -->

    <!-- JavaScript for handling appointment details -->
    <script>
        // Store therapist data for JavaScript functions
        const therapistData = <?php echo json_encode($therapists); ?>;
        
        // Function to update appointment status
       
       src="appointments_script2.js"

   // Function to handle appointment editing
    src="appointments_script3.js"

        // Show appointment details modal
     
    src="appointments_script4.js"
        
        // Close appointment details modal
        function closeAppointmentDetails() {
            const modal = document.getElementById('appointmentDetailsModal');
            const modalContent = modal.querySelector('.modal-enter');
            
            // Add exit animation
            modalContent.style.transition = 'opacity 300ms, transform 300ms';
            modalContent.style.opacity = '0';
            modalContent.style.transform = 'translateY(20px)';
            
            // Hide modal after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset animation
                modalContent.style.transition = '';
                modalContent.style.opacity = '';
                modalContent.style.transform = '';
                modalContent.classList.remove('modal-enter-active');
            }, 300);
        }
        
        // Variable to track tooltip timer
        let tooltipTimer;
        
        // Show appointment tooltip on hover
        scr ="appointments_script5.js"
        
        // Hide appointment tooltip with a delay to keep it visible longer
        function hideAppointmentTooltip() {
            const tooltip = document.getElementById('appointmentTooltip');
            
            // Add a delay before starting the hiding animation
            tooltipTimer = setTimeout(() => {
                tooltip.classList.remove('tooltip-fade-in');
                tooltip.classList.add('tooltip-fade-out');
                
                // Hide after transition completes
                setTimeout(() => {
                    tooltip.classList.add('hidden');
                }, 300);
            }, 1200); // Keep visible for 1.2 seconds before starting to hide
        }
        
        // Function to show notifications
        src = "appointments_script7.js"
    </script>
</body>
</html>