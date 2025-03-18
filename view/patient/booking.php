<?php include('booking_controller.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - TheraCare</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="booking_style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
            colors: {
              primary: {
                50: '#f0f7ff',
                100: '#e0efff',
                200: '#bae0ff',
                300: '#7cc5ff',
                400: '#36a4ff',
                500: '#0087ff',
                600: '#0062d6',
                700: '#0050b0',
                800: '#004492',
                900: '#003a78',
              },
            }
          }
        }
      }
    </script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
</head>
<body class="bg-slate-50 font-sans">
    <div class="container mx-auto px-20 py-20 max-w-7xl">
        <!-- Header Section with dynamic content -->
        <div class="relative custom-gradient rounded-2xl shadow-2xl mb-8 overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            
            <div class="relative px-10 py-12 text-white">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="h-1 w-6 bg-primary-300 rounded"></div>
                    <span class="text-primary-200 uppercase text-sm font-semibold tracking-wider">Appointments</span>
                </div>
                
                <h1 class="text-4xl font-bold mb-3 text-white">Welcome, <?php echo isset($patient_info['first_name']) ? htmlspecialchars($patient_info['first_name']) : 'Patient'; ?></h1>
                <p class="text-primary-100 text-lg max-w-2xl">Manage your therapy appointments with our qualified professionals. Book new sessions, view or reschedule your existing appointments all in one place.</p>
            </div>
        </div>
        
        <!-- Alert Messages -->
        <?php if (!empty($message)): ?>
            <div class="mb-6 bg-teal-50 border-l-4 border-teal-500 p-4 rounded shadow-sm text-teal-700 animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium"><?php echo $message; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm text-red-700 animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium"><?php echo $error; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
             <!-- Left Column: Book Appointment -->
             <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md overflow-hidden h-full transition-all card-shadow">
                    <div class="px-6 py-4 border-b border-slate-200 custom-gradient text-white">
                        <h2 class="text-lg font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Book New Appointment
                        </h2>
                    </div>
                
                    <div class="p-5">
                       <!-- Specialization Filter -->
<div class="mb-4">
    <form method="GET" action="" class="space-y-3">
        <div>
            <label for="specialization" class="block text-sm font-medium text-slate-600 mb-1.5">Filter by Specialization</label>
            <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <select name="specialization" id="specialization" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-10 text-slate-700 focus:outline-none appearance-none">
                    <option value="">All Specializations</option>
                    <?php foreach ($specializations as $spec): ?>
                        <option value="<?php echo $spec; ?>" <?php echo ($specialization_filter === $spec) ? 'selected' : ''; ?>>
                            <?php echo $spec; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                </div>
            </div>
            <div class="flex justify-end mt-2">
                <button type="submit" class="py-1.5 px-3 bg-primary-100 text-primary-600 rounded-md hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 text-xs font-medium transition-colors">
                    Apply Filter
                </button>
            </div>
        </div>
    </form>
</div>
<div class="border-b border-slate-200 mb-4"></div>

<form method="POST" action="" class="space-y-4">
    <div>
        <label for="therapist_id" class="block text-sm font-medium text-slate-600 mb-1.5">Select Therapist</label>
        <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <select name="therapist_id" id="therapist_id" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-10 text-slate-700 focus:outline-none appearance-none" required>
                <option value="">-- Select Therapist --</option>
                <?php foreach ($therapists as $therapist): ?>
                    <option value="<?php echo $therapist['id']; ?>">
                        <?php echo $therapist['first_name'] . ' ' . $therapist['last_name']; ?>
                        (<?php echo $therapist['specialization']; ?>)
                        <?php if ($therapist['consultation_fee']): ?>
                            - ₱<?php echo number_format($therapist['consultation_fee'], 2); ?>
                        <?php endif; ?>
                        <?php if ($therapist['available_slots']): ?>
                            - <?php echo $therapist['available_slots']; ?> slots available
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Phone Number Field - ADDED HERE -->
    <div>
        <label for="phone" class="block text-sm font-medium text-slate-600 mb-1.5">Phone Number</label>
        <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <input type="tel" name="phone" id="phone" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-4 text-slate-700 focus:outline-none" placeholder="Enter phone number" 
                <?php if (isset($patient) && $patient['phone']): ?>
                    value="<?php echo htmlspecialchars($patient['phone']); ?>"
                <?php endif; ?>
                required>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <div>
            <label for="date" class="block text-sm font-medium text-slate-600 mb-1.5">Date</label>
            <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <input type="date" name="date" id="date" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-4 text-slate-700 focus:outline-none appearance-none" required min="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>
        
        <div>
            <label for="time" class="block text-sm font-medium text-slate-600 mb-1.5">Time</label>
            <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <select name="time" id="time" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-10 text-slate-700 focus:outline-none appearance-none" required>
                    <option value="">-- Select Time --</option>
                    <?php 
                    // Generate time slots from 10am to 5pm every 30 minutes
                    $start_time = strtotime('10:00');
                    $end_time = strtotime('17:00');
                    $interval = 30 * 60; // 30 minutes in seconds
                    
                    for ($time = $start_time; $time <= $end_time; $time += $interval) {
                        $formatted_time = date('H:i', $time);
                        $display_time = date('g:i A', $time);
                        echo "<option value=\"$formatted_time\">$display_time</option>";
                    }
                    ?>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <p class="text-xs text-slate-500 -mt-2 mb-4">Hours: 10:00 AM - 5:00 PM</p>
    
    <div>
        <label for="visit_type" class="block text-sm font-medium text-slate-600 mb-1.5">Visit Type</label>
        <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <select name="visit_type" id="visit_type" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-10 text-slate-700 focus:outline-none appearance-none" required>
                <?php foreach ($visit_types as $type): ?>
                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div>
        <label for="notes" class="block text-sm font-medium text-slate-600 mb-1.5">Notes (Optional)</label>
        <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
            <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <textarea name="notes" id="notes" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-4 text-slate-700 focus:outline-none" rows="2" placeholder="Add any specific concerns or questions..."></textarea>
        </div>
    </div>
    
    <button type="submit" name="book_appointment" class="w-full py-3 px-4 border-0 rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors mt-5 flex items-center justify-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Book Appointment
    </button>
</form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Appointment List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden h-full transition-all card-shadow">
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                        <h2 class="text-xl font-semibold text-slate-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Your Appointments
                        </h2>
                    </div>
                    <div class="p-6">
                        <?php if (count($appointments) > 0): ?>
                            <div x-data="{ 
                                activeTab: 'upcoming', 
                                showReschedule: false, 
                                appointmentToReschedule: null,
                                appointmentDetails: {},
                                setAppointment(id, date, time) {
                                    this.appointmentToReschedule = id;
                                    this.appointmentDetails = {
                                        id: id,
                                        date: date,
                                        time: time
                                    };
                                    this.showReschedule = true;
                                }
                            }" class="mb-4">
                                <div class="flex border-b border-slate-200 mb-6 overflow-x-auto">
                                    <button 
                                        @click="activeTab = 'upcoming'" 
                                        :class="activeTab === 'upcoming' ? 'tab-active' : 'tab-inactive'"
                                        class="py-3 px-6 text-sm focus:outline-none whitespace-nowrap transition-all">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Upcoming
                                    </button>
                                    <button 
                                        @click="activeTab = 'past'" 
                                        :class="activeTab === 'past' ? 'tab-active' : 'tab-inactive'"
                                        class="py-3 px-6 text-sm focus:outline-none whitespace-nowrap transition-all">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Past
                                    </button>
                                    <button 
                                        @click="activeTab = 'cancelled'" 
                                        :class="activeTab === 'cancelled' ? 'tab-active' : 'tab-inactive'"
                                        class="py-3 px-6 text-sm focus:outline-none whitespace-nowrap transition-all">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelled
                                    </button>
                                    <button 
                                        @click="activeTab = 'rescheduled'" 
                                        :class="activeTab === 'rescheduled' ? 'tab-active' : 'tab-inactive'"
                                        class="py-3 px-6 text-sm focus:outline-none whitespace-nowrap transition-all">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Rescheduled
                                    </button>
                                </div>
                                
                                <!-- Reschedule Modal -->
                                <div x-show="showReschedule" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl" @click.away="showReschedule = false">
                                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Reschedule Appointment</h3>
                                        <form method="POST" action="">
                                            <input type="hidden" name="appointment_id" x-bind:value="appointmentToReschedule">
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="new_date" class="block text-sm font-medium text-slate-700 mb-1">New Date</label>
                                                    <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <input type="date" name="new_date" id="new_date" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-4 text-slate-700 focus:outline-none appearance-none" required min="<?php echo date('Y-m-d'); ?>">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="new_time" class="block text-sm font-medium text-slate-700 mb-1">New Time</label>
                                                    <div class="relative rounded-lg border border-slate-200 bg-slate-50 hover:border-slate-300 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <select name="new_time" id="new_time" class="block w-full rounded-lg bg-transparent border-0 py-2.5 pl-10 pr-10 text-slate-700 focus:outline-none appearance-none" required>
                                                            <option value="">-- Select Time --</option>
                                                            <?php 
                                                            // Generate time slots from 10am to 5pm every 30 minutes
                                                            $start_time = strtotime('10:00');
                                                            $end_time = strtotime('17:00');
                                                            $interval = 30 * 60; // 30 minutes in seconds
                                                            
                                                            for ($time = $start_time; $time <= $end_time; $time += $interval) {
                                                                $formatted_time = date('H:i', $time);
                                                                $display_time = date('g:i A', $time);
                                                                echo "<option value=\"$formatted_time\">$display_time</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mt-1">Hours: 10:00 AM - 5:00 PM</p>
                                                </div>
                                                <div class="flex justify-end space-x-3 mt-6">
                                                    <button type="button" @click="showReschedule = false" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" name="reschedule_appointment" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                        Reschedule
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Upcoming Appointments -->
                                <div x-show="activeTab === 'upcoming'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    $today = date('Y-m-d');
                                    $hasUpcoming = false;
                                    foreach ($appointments as $appointment): 
                                        if ($appointment['date'] >= $today && $appointment['status'] !== 'Cancelled'):
                                            $hasUpcoming = true;
                                    ?>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow transition-all" data-appointment-id="<?php echo $appointment['id']; ?>">
                                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-2">
                                                        <svg class="h-5 w-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-medium text-slate-700">
                                                        <?php echo date('D, M d, Y', strtotime($appointment['date'])); ?>
                                                    </span>
                                                </div>
                                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo getStatusClasses($appointment['status']); ?>">
                                                    <?php echo $appointment['status']; ?>
                                                </span>
                                            </div>
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <svg class="h-5 w-5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-slate-600">
                                                        <?php echo date('h:i A', strtotime($appointment['time'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <h3 class="text-lg font-medium text-slate-900 mb-2">
                                                    Dr. <?php echo $appointment['therapist_first_name'] . ' ' . $appointment['therapist_last_name']; ?>
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['specialization']; ?>
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['visit_type']; ?>
                                                    </div>
                                                    
                                                    <?php if ($appointment['consultation_fee']): ?>
                                                        <div class="flex items-center text-sm text-slate-600">
                                                            <svg class="h-4 w-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            ₱<?php echo number_format($appointment['consultation_fee'], 2); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if (!empty($appointment['notes'])): ?>
                                                <div class="mt-3 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                    <p class="font-medium mb-1 text-slate-700">Notes:</p>
                                                    <p><?php echo nl2br(htmlspecialchars($appointment['notes'])); ?></p>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <div class="mt-4 flex justify-end space-x-2">
                                                    <?php if ($appointment['status'] !== 'Completed'): ?>
                                                    <button 
                                                        type="button" 
                                                        @click="setAppointment(<?php echo $appointment['id']; ?>, '<?php echo $appointment['date']; ?>', '<?php echo $appointment['time']; ?>')" 
                                                        class="inline-flex items-center px-3 py-2 border border-primary-300 text-sm font-medium rounded-lg text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Reschedule
                                                    </button>
                                                    <form method="POST" action="" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                        <button type="submit" name="cancel_appointment" class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Cancel
                                                        </button>
                                                    </form>
                                                    <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-2 border border-teal-300 text-sm font-medium rounded-lg text-teal-700 bg-white">
                                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Completed
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if (!$hasUpcoming):
                                    ?>
                                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                                            <div class="inline-block p-4 rounded-full bg-primary-100 text-primary-500 mb-4">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-slate-800">No upcoming appointments</h3>
                                            <p class="mt-1 text-sm text-slate-500">Use the form to schedule your next session</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Past Appointments -->
                                <div x-show="activeTab === 'past'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    $hasPast = false;
                                    foreach ($appointments as $appointment): 
                                        if ($appointment['date'] < $today && $appointment['status'] !== 'Cancelled' && $appointment['status'] !== 'Rescheduled'):
                                            $hasPast = true;
                                    ?>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm" data-appointment-id="<?php echo $appointment['id']; ?>">
                                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-2">
                                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-medium text-slate-700">
                                                        <?php echo date('D, M d, Y', strtotime($appointment['date'])); ?>
                                                    </span>
                                                </div>
                                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo getStatusClasses($appointment['status']); ?>">
                                                    <?php echo $appointment['status']; ?>
                                                </span>
                                            </div>
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <svg class="h-5 w-5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-slate-600">
                                                        <?php echo date('h:i A', strtotime($appointment['time'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <h3 class="text-lg font-medium text-slate-900 mb-2">
                                                    Dr. <?php echo $appointment['therapist_first_name'] . ' ' . $appointment['therapist_last_name']; ?>
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['specialization']; ?>
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['visit_type']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if (!$hasPast):
                                    ?>
                                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                                            <div class="inline-block p-4 rounded-full bg-slate-200 text-slate-500 mb-4">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-slate-800">No past appointments</h3>
                                            <p class="mt-1 text-sm text-slate-500">Your appointment history will appear here</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Cancelled Appointments -->
                                <div x-show="activeTab === 'cancelled'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    $hasCancelled = false;
                                    foreach ($appointments as $appointment): 
                                        if ($appointment['status'] === 'Cancelled'):
                                            $hasCancelled = true;
                                    ?>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm opacity-80" data-appointment-id="<?php echo $appointment['id']; ?>">
                                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-2">
                                                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-medium text-slate-700">
                                                        <?php echo date('D, M d, Y', strtotime($appointment['date'])); ?>
                                                    </span>
                                                </div>
                                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Cancelled
                                                </span>
                                            </div>
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <svg class="h-5 w-5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-slate-600">
                                                        <?php echo date('h:i A', strtotime($appointment['time'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <h3 class="text-lg font-medium text-slate-700 mb-2">
                                                    Dr. <?php echo $appointment['therapist_first_name'] . ' ' . $appointment['therapist_last_name']; ?>
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center text-sm text-slate-500">
                                                        <svg class="h-4 w-4 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['specialization']; ?>
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm text-slate-500">
                                                        <svg class="h-4 w-4 text-slate-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['visit_type']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if (!$hasCancelled):
                                    ?>
                                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                                            <div class="inline-block p-4 rounded-full bg-slate-200 text-slate-500 mb-4">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-slate-800">No cancelled appointments</h3>
                                            <p class="mt-1 text-sm text-slate-500">Cancelled appointments will appear here</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Rescheduled Appointments -->
                                <div x-show="activeTab === 'rescheduled'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php 
                                    $hasRescheduled = false;
                                    foreach ($appointments as $appointment): 
                                        if ($appointment['status'] === 'Rescheduled'):
                                            $hasRescheduled = true;
                                    ?>
                                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm" data-appointment-id="<?php echo $appointment['id']; ?>">
                                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 mr-2">
                                                        <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg></div>
                                                    <span class="text-sm font-medium text-slate-700">
                                                        <?php echo date('D, M d, Y', strtotime($appointment['date'])); ?>
                                                    </span>
                                                </div>
                                                <span class="status-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo getStatusClasses($appointment['status']); ?>">
                                                    <?php echo $appointment['status']; ?>
                                                </span>
                                            </div>
                                            <div class="p-5">
                                                <div class="flex items-center mb-3">
                                                    <svg class="h-5 w-5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-slate-600">
                                                        <?php echo date('h:i A', strtotime($appointment['time'])); ?>
                                                    </span>
                                                </div>
                                                
                                                <h3 class="text-lg font-medium text-slate-900 mb-2">
                                                    Dr. <?php echo $appointment['therapist_first_name'] . ' ' . $appointment['therapist_last_name']; ?>
                                                </h3>
                                                
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['specialization']; ?>
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm text-slate-600">
                                                        <svg class="h-4 w-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                        </svg>
                                                        <?php echo $appointment['visit_type']; ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if (!empty($appointment['notes'])): ?>
                                                <div class="mt-3 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                    <p class="font-medium mb-1 text-slate-700">Notes:</p>
                                                    <p><?php echo nl2br(htmlspecialchars($appointment['notes'])); ?></p>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if (!$hasRescheduled):
                                    ?>
                                        <div class="col-span-full text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                                            <div class="inline-block p-4 rounded-full bg-slate-200 text-slate-500 mb-4">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-slate-800">No rescheduled appointments</h3>
                                            <p class="mt-1 text-sm text-slate-500">Rescheduled appointments will appear here</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-16">
                                <div class="inline-block p-6 rounded-full bg-primary-100 text-primary-500 mb-6">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-medium text-slate-900 mb-2">No appointments yet</h3>
                                <p class="mt-2 text-base text-slate-500 max-w-md mx-auto">
                                    Book your first appointment with one of our therapists to get started on your wellness journey.
                                </p>
                                <a href="#" class="mt-6 inline-flex items-center px-5 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" onclick="document.getElementById('therapist_id').focus(); return false;">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Book Now
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script src = "booking_script.js"></script>
   
</body>
</html>