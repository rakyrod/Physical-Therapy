<?php include("therapist_dashboard_controller.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Dashboard - TheraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="therapist_dashboard_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
    <!-- Auto refresh every 60 seconds -->
    <meta http-equiv="refresh" content="60">
</head>
<body class="font-inter">
    <!-- Header -->
    <header class="bg-[#111827] border-b border-gray-700 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">TheraCare</h1>
                <p class="text-sm font-medium text-gray-400"><?php echo htmlspecialchars($specialization); ?> Specialist</p>
            </div>
            <div class="flex items-center">
                <!-- Notification Bell -->
                <div class="relative mr-4">
                    <button id="notificationBell" onclick="toggleNotifications()" class="text-white hover:text-blue-300 focus:outline-none">
                        <i class="fas fa-bell text-xl"></i>
                        <?php if ($notification_count > 0): ?>
                            <span id="notificationBadge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $notification_count; ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-gray-800 rounded-md shadow-lg z-20">
                        <div class="p-3 border-b border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Notifications</h3>
                            <?php if ($notification_count > 0 && $table_exists): ?>
                                <form method="post" class="inline">
                                    <input type="hidden" name="action" value="mark_all_read">
                                    <button type="submit" class="text-sm text-blue-400 hover:text-blue-300">
                                        Mark all as read
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        
                        <div class="notifications-dropdown">
                            <?php if (empty($notifications)): ?>
                                <div class="p-4 text-center text-gray-400">
                                    <p>No notifications</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="notification-item p-3 border-b border-gray-700 <?php echo $notification['is_read'] ? '' : 'notification-unread'; ?>">
                                        <div class="flex items-start">
                                            <div class="h-9 w-9 rounded-full bg-blue-500/20 flex items-center justify-center mr-3 flex-shrink-0">
                                                <?php if ($notification['type'] === 'appointment'): ?>
                                                    <i class="fas fa-calendar-plus text-blue-500"></i>
                                                <?php elseif ($notification['type'] === 'system'): ?>
                                                    <i class="fas fa-bell text-yellow-500"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-envelope text-green-500"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow">
                                                <p class="text-sm"><?php echo htmlspecialchars($notification['message']); ?></p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    <?php echo date('M d, Y g:i A', strtotime($notification['created_at'])); ?>
                                                </p>
                                                <?php if (!$notification['is_read']): ?>
                                                    <form method="post" class="mt-2">
                                                        <input type="hidden" name="action" value="mark_read">
                                                        <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                                                        <button type="submit" class="text-xs text-blue-400 hover:text-blue-300">
                                                            Mark as read
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="mr-6 text-right">
                    <p class="font-bold"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-sm text-gray-400">Fee: ₱<?php echo htmlspecialchars($consultation_fee); ?> pesos</p>
                </div>
                <div class="relative">
                    <a href="../index.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md shadow-sm flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto py-6 px-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
            <!-- Total Appointments Card -->
            <div class="card p-5">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold" id="total-count"><?php echo $counts['total']; ?></h3>
                        <p class="text-sm text-gray-400">Total Appointments</p>
                    </div>
                </div>
                <div class="w-full progress-bar bg-gray-700">
                    <div class="h-full bg-blue-bar" style="width: 100%"></div>
                </div>
            </div>
            
            <!-- Pending Appointments Card -->
            <div class="card p-5">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center mr-3">
                        <i class="fas fa-clock text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold" id="pending-count"><?php echo $counts['pending']; ?></h3>
                        <p class="text-sm text-gray-400">Pending</p>
                    </div>
                </div>
                <div class="w-full progress-bar bg-gray-700">
                    <div class="h-full bg-yellow-bar" id="pending-bar" style="width: <?php echo ($counts['total'] > 0) ? (($counts['pending'] / $counts['total']) * 100) : 0; ?>%"></div>
                </div>
            </div>
            
            <!-- Scheduled Appointments Card -->
            <div class="card p-5">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-alt text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold" id="scheduled-count"><?php echo $counts['scheduled']; ?></h3>
                        <p class="text-sm text-gray-400">Scheduled</p>
                    </div>
                </div>
                <div class="w-full progress-bar bg-gray-700">
                    <div class="h-full bg-green-bar" id="scheduled-bar" style="width: <?php echo ($counts['total'] > 0) ? (($counts['scheduled'] / $counts['total']) * 100) : 0; ?>%"></div>
                </div>
            </div>
            
            <!-- Completed Appointments Card -->
            <div class="card p-5">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mr-3">
                        <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold" id="completed-count"><?php echo $counts['completed']; ?></h3>
                        <p class="text-sm text-gray-400">Completed</p>
                    </div>
                </div>
                <div class="w-full progress-bar bg-gray-700">
                    <div class="h-full bg-blue-bar" id="completed-bar" style="width: <?php echo ($counts['total'] > 0) ? (($counts['completed'] / $counts['total']) * 100) : 0; ?>%"></div>
                </div>
            </div>
        </div>
        
        <!-- Two-column layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column - Today's Appointments and Metrics -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Today's Appointments -->
                <div class="card">
                    <div class="flex justify-between items-center p-5 border-b border-gray-700">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-day text-blue-500"></i>
                            </div>
                            <h2 class="text-xl font-bold">Today's Schedule</h2>
                        </div>
                        <p class="text-gray-400 text-sm"><?php echo date('F d, Y'); ?></p>
                    </div>
                    <div class="p-5" id="today-appointments-container">
                        <?php if ($today_appointments->num_rows > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="today-appointments-grid">
                                <?php while ($appointment = $today_appointments->fetch_assoc()): ?>
                                    <div class="bg-gray-800 rounded-lg p-4 appointment-card" data-appointment-id="<?php echo $appointment['id']; ?>">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h3 class="font-semibold"><?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?></h3>
                                                <p class="text-sm text-gray-400"><?php echo date('h:i A', strtotime($appointment['time'])); ?></p>
                                            </div>
                                            <div>
                                                <?php echo getStatusBadge($appointment['status']); ?>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-300"><span class="font-medium">Type:</span> <?php echo htmlspecialchars($appointment['visit_type']); ?></p>
                                        <p class="text-sm text-gray-300"><span class="font-medium">Treatment:</span> <?php echo htmlspecialchars($appointment['treatment_needed']); ?></p>
                                        
                                        <div class="mt-3 space-x-2 flex">
                                            <?php if ($appointment['status'] === 'Pending'): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                        <i class="fas fa-check mr-1"></i> Accept
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($appointment['status'], ['Pending', 'Scheduled', 'Rescheduled'])): ?>
                                                <button onclick="openRescheduleModal(<?php echo $appointment['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                    <i class="fas fa-calendar-alt mr-1"></i> Reschedule
                                                </button>
                                                
                                                <button onclick="openCancelModal(<?php echo $appointment['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                    <i class="fas fa-times mr-1"></i> Cancel
                                                </button>
                                                
                                                <button onclick="openNotesModal(<?php echo $appointment['id']; ?>, '<?php echo addslashes($appointment['notes']); ?>')" class="bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                    <i class="fas fa-edit mr-1"></i> Notes
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($appointment['status'], ['Scheduled', 'Rescheduled'])): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <input type="hidden" name="action" value="complete">
                                                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-2 py-1 rounded text-xs font-medium">
                                                        <i class="fas fa-check-double mr-1"></i> Complete
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center py-10 text-center" id="no-appointments-message">
                                <div class="w-20 h-20 rounded-full bg-blue-500/10 flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar text-blue-500 text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-medium mb-2">No appointments today</h3>
                                <p class="text-gray-400">Enjoy your free day!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Appointment Metrics -->
                <div class="card">
                    <div class="flex items-center p-5 border-b border-gray-700">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-blue-500"></i>
                        </div>
                        <h2 class="text-xl font-bold">Appointment Metrics</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <!-- Total -->
                            <div class="bg-blue-500/10 rounded-lg p-4">
                                <h3 class="text-gray-400 mb-1 text-sm">Total Appointments</h3>
                                <p class="text-3xl font-bold text-blue-500" id="metrics-total"><?php echo $counts['total']; ?></p>
                            </div>
                            <!-- Cancelled -->
                            <div class="bg-red-500/10 rounded-lg p-4">
                                <h3 class="text-gray-400 mb-1 text-sm">Cancelled</h3>
                                <p class="text-3xl font-bold text-red-500" id="metrics-cancelled"><?php echo $counts['cancelled']; ?></p>
                            </div>
                            <!-- Completed -->
                            <div class="bg-green-500/10 rounded-lg p-4">
                                <h3 class="text-gray-400 mb-1 text-sm">Completed</h3>
                                <p class="text-3xl font-bold text-green-500" id="metrics-completed"><?php echo $counts['completed']; ?></p>
                            </div>
                        </div>
                        
                        <!-- Completion Rate -->
                        <div class="mb-5">
                            <div class="flex justify-between mb-2">
                                <p class="text-sm font-medium">Completion Rate</p>
                                <p class="text-sm font-medium" id="completion-rate"><?php echo $completion_rate; ?>%</p>
                            </div>
                            <div class="w-full h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500" id="completion-bar" style="width: <?php echo $completion_rate; ?>%"></div>
                            </div>
                        </div>
                        
                        <!-- Cancellation Rate -->
                        <div>
                            <div class="flex justify-between mb-2">
                                <p class="text-sm font-medium">Cancellation Rate</p>
                                <p class="text-sm font-medium" id="cancellation-rate"><?php echo $cancellation_rate; ?>%</p>
                            </div>
                            <div class="w-full h-2 bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500" id="cancellation-bar" style="width: <?php echo $cancellation_rate; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right column - Recent Activity -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="flex items-center p-5 border-b border-gray-700">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-history text-purple-500"></i>
                        </div>
                        <h2 class="text-xl font-bold">Recent Activities</h2>
                    </div>
                    <div class="p-5" id="recent-activity-container">
                        <?php if ($recent_activity->num_rows > 0): ?>
                            <div class="space-y-6 relative" id="recent-activity-list">
                                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-700"></div>
                                
                                <?php while ($activity = $recent_activity->fetch_assoc()): ?>
                                    <div class="flex" data-activity-id="<?php echo $activity['id']; ?>">
                                        <div class="relative flex items-center justify-center flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center z-10">
                                                <i class="fas fa-calendar-plus text-white text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-semibold mb-1">
                                                <?php 
                                                    switch($activity['status']) {
                                                        case 'Pending':
                                                            echo 'New appointment created';
                                                            break;
                                                        case 'Scheduled':
                                                            echo 'Appointment scheduled';
                                                            break;
                                                        case 'Completed':
                                                            echo 'Appointment completed';
                                                            break;
                                                        case 'Cancelled':
                                                            echo 'Appointment cancelled';
                                                            break;
                                                        case 'Rescheduled':
                                                            echo 'Appointment rescheduled';
                                                            break;
                                                    }
                                                ?>
                                            </p>
                                            <p class="text-gray-400 text-xs mb-1"><?php echo $activity['formatted_date']; ?></p>
                                            <p class="text-sm text-gray-300">
                                                Patient: <?php echo htmlspecialchars($activity['patient_first_name'] . ' ' . $activity['patient_last_name']); ?>
                                            </p>
                                            <div class="mt-1">
                                                <?php echo getStatusBadge($activity['status']); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center py-10 text-center" id="no-activity-message">
                                <div class="w-16 h-16 rounded-full bg-purple-500/10 flex items-center justify-center mb-4">
                                    <i class="fas fa-history text-purple-500 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium mb-2">No recent activity</h3>
                                <p class="text-gray-400 text-sm">Activities will appear here once you start managing appointments</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Treatment Specialization -->
                <div class="card">
                    <div class="flex items-center p-5 border-b border-gray-700">
                        <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center mr-3">
                            <i class="fas fa-stethoscope text-green-500"></i>
                        </div>
                        <h2 class="text-xl font-bold">Your Specialization</h2>
                    </div>
                    <div class="p-5">
                        <div class="bg-gray-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center mr-3">
                                    <i class="fas fa-user-md text-green-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold"><?php echo htmlspecialchars($specialization); ?></h3>
                                    <p class="text-sm text-gray-400">Specialist</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-300 mb-2">Consultation Fee</p>
                                <div class="bg-gray-700 rounded-lg p-3">
                                    <p class="text-lg font-bold text-green-500">₱<?php echo htmlspecialchars($consultation_fee); ?> pesos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- All Appointments -->
        <div class="card mt-6">
            <div class="flex items-center p-5 border-b border-gray-700">
                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-blue-500"></i>
                </div>
                <h2 class="text-xl font-bold">All Appointments</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700" id="all-appointments-tbody">
                        <?php if ($all_appointments->num_rows > 0): ?>
                            <?php while ($appointment = $all_appointments->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-800" data-appointment-id="<?php echo $appointment['id']; ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium">
                                            <?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            <?php echo htmlspecialchars($appointment['patient_email']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <?php echo date('M d, Y', strtotime($appointment['date'])); ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            <?php echo date('h:i A', strtotime($appointment['time'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <?php echo htmlspecialchars($appointment['visit_type']); ?>
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            <?php echo htmlspecialchars($appointment['treatment_needed']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo getStatusBadge($appointment['status']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm truncate max-w-xs">
                                            <?php echo $appointment['notes'] ? htmlspecialchars($appointment['notes']) : '<span class="text-gray-500 italic">No notes</span>'; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <?php if ($appointment['status'] === 'Pending'): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="text-green-500 hover:text-green-400">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($appointment['status'], ['Pending', 'Scheduled', 'Rescheduled'])): ?>
                                                <button onclick="openRescheduleModal(<?php echo $appointment['id']; ?>)" class="text-blue-500 hover:text-blue-400">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                                
                                                <button onclick="openCancelModal(<?php echo $appointment['id']; ?>)" class="text-red-500 hover:text-red-400">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button onclick="openNotesModal(<?php echo $appointment['id']; ?>, '<?php echo addslashes($appointment['notes']); ?>')" class="text-purple-500 hover:text-purple-400">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <button onclick="openPatientDetailsModal('<?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>', '<?php echo htmlspecialchars($appointment['patient_email']); ?>', '<?php echo htmlspecialchars($appointment['treatment_needed']); ?>', '<?php echo addslashes($appointment['medical_history']); ?>')" class="text-gray-400 hover:text-white">
                                                <i class="fas fa-user"></i>
                                            </button>
                                            
                                            <?php if (in_array($appointment['status'], ['Scheduled', 'Rescheduled'])): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                    <input type="hidden" name="action" value="complete">
                                                    <button type="submit" class="text-blue-500 hover:text-blue-400">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr id="no-appointments-row">
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-times text-4xl mb-4 text-gray-600"></i>
                                        <p>No appointments found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="modal fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4 text-white flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                Reschedule Appointment
            </h3>
            <form method="post" id="rescheduleForm">
                <input type="hidden" name="action" value="reschedule">
                <input type="hidden" id="reschedule_appointment_id" name="appointment_id" value="">
                
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-300 mb-1">New Date</label>
                    <input type="date" id="date" name="date" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="mb-6">
                    <label for="time" class="block text-sm font-medium text-gray-300 mb-1">New Time</label>
                    <input type="time" id="time" name="time" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('rescheduleModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        Reschedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notes Modal -->
    <div id="notesModal" class="modal fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4 text-white flex items-center">
                <i class="fas fa-edit mr-2 text-purple-500"></i>
                Update Patient Notes
            </h3>
            <form method="post" id="notesForm">
                <input type="hidden" name="action" value="update_notes">
                <input type="hidden" id="notes_appointment_id" name="appointment_id" value="">
                
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-1">Notes</label>
                    <textarea id="notes" name="notes" rows="5" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-purple-500 focus:border-purple-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('notesModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-medium">
                        Save Notes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="modal fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4 text-white flex items-center">
                <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                Cancel Appointment
            </h3>
            <p class="mb-6 text-gray-300">Are you sure you want to cancel this appointment? This action cannot be undone.</p>
            
            <form method="post" id="cancelForm">
                <input type="hidden" name="action" value="cancel">
                <input type="hidden" id="cancel_appointment_id" name="appointment_id" value="">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('cancelModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 font-medium">
                        No, Keep Appointment
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">
                        Yes, Cancel Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Patient Details Modal -->
    <div id="patientDetailsModal" class="modal fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4 text-white flex items-center">
                <i class="fas fa-user mr-2 text-blue-500"></i>
                Patient Details
            </h3>
            
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-400">Name</p>
                <p id="patient_name" class="text-lg"></p>
            </div>
            
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-400">Email</p>
                <p id="patient_email" class="text-lg"></p>
            </div>
            
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-400">Treatment Needed</p>
                <p id="patient_treatment" class="text-lg"></p>
            </div>
            
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-400">Medical History</p>
                <div id="patient_history" class="text-md bg-gray-700 p-3 rounded-md mt-1 max-h-32 overflow-y-auto"></div>
            </div>
            
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('patientDetailsModal')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>

   <script src="therapist_dashboard_script.js"></script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>