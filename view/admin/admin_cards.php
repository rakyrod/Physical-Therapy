<?php include('admin_cards_model.php'); ?>

<!-- Main Cards Section -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Users Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-x-4">
            <div class="inline-flex justify-center items-center size-12 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                        <?php echo number_format($userData['total_users']); ?>
                    </h3>
                    <span class="<?php echo $userGrowth >= 0 ? 'text-emerald-500' : 'text-red-500'; ?> text-xs font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-<?php echo $userGrowth >= 0 ? 'up' : 'down'; ?>"></i><?php echo abs($userGrowth); ?>%
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-200 rounded-full dark:bg-slate-700">
            <div class="h-1 bg-blue-500 rounded-full" style="width: <?php echo min(100, $userData['total_users'] / 5); ?>%"></div>
        </div>
    </div>
    
    <!-- Patients Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-x-4">
            <div class="inline-flex justify-center items-center size-12 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                <i class="fa-solid fa-user-plus text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                        <?php echo number_format($userData['total_patients']); ?>
                    </h3>
                    <span class="<?php echo $patientGrowth >= 0 ? 'text-emerald-500' : 'text-red-500'; ?> text-xs font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-<?php echo $patientGrowth >= 0 ? 'up' : 'down'; ?>"></i><?php echo abs($patientGrowth); ?>%
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Active Patients</p>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-200 rounded-full dark:bg-slate-700">
            <div class="h-1 bg-emerald-500 rounded-full" style="width: <?php echo min(100, $userData['total_patients'] / 3); ?>%"></div>
        </div>
    </div>
    
    <!-- Therapists Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-x-4">
            <div class="inline-flex justify-center items-center size-12 rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <i class="fa-solid fa-stethoscope text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                        <?php echo number_format($userData['total_therapists']); ?>
                    </h3>
                    <span class="<?php echo $therapistGrowth >= 0 ? 'text-emerald-500' : 'text-red-500'; ?> text-xs font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-<?php echo $therapistGrowth >= 0 ? 'up' : 'down'; ?>"></i><?php echo abs($therapistGrowth); ?>%
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Therapists</p>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-200 rounded-full dark:bg-slate-700">
            <div class="h-1 bg-indigo-500 rounded-full" style="width: <?php echo min(100, $userData['total_therapists'] / 1); ?>%"></div>
        </div>
    </div>
    
    <!-- Appointments Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-x-4">
            <div class="inline-flex justify-center items-center size-12 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                        <?php echo number_format($appointmentsData['upcoming_week']); ?>
                    </h3>
                    <span class="<?php echo $apptsGrowth >= 0 ? 'text-emerald-500' : 'text-red-500'; ?> text-xs font-medium flex items-center gap-1">
                        <i class="fa-solid fa-arrow-<?php echo $apptsGrowth >= 0 ? 'up' : 'down'; ?>"></i><?php echo abs($apptsGrowth); ?>%
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Weekly Sessions</p>
            </div>
        </div>
        <div class="mt-4 h-1 w-full bg-slate-200 rounded-full dark:bg-slate-700">
            <div class="h-1 bg-amber-500 rounded-full" style="width: <?php echo min(100, $appointmentsData['upcoming_week'] * 10); ?>%"></div>
        </div>
    </div>
</div>

<!-- Sales & Performance Cards Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
    <!-- Appointment Status Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 lg:col-span-2">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Appointment Metrics</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 dark:bg-blue-900/20 dark:border-blue-900/50">
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Total Appointments</h3>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    <?php echo number_format($appointmentsData['total_appointments']); ?>
                </p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-100 dark:bg-red-900/20 dark:border-red-900/50">
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Cancelled</h3>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                    <?php echo number_format($appointmentsData['cancelled_appointments']); ?>
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100 dark:bg-green-900/20 dark:border-green-900/50">
                <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Completed</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                    <?php echo number_format($appointmentsData['completed_appointments']); ?>
                </p>
            </div>
        </div>
        
        <!-- Progress Bars -->
        <div class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Completion Rate</span>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?php echo $completionRate; ?>%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2 dark:bg-slate-700">
                    <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo $completionRate; ?>%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Cancellation Rate</span>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300"><?php echo $cancellationRate; ?>%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2 dark:bg-slate-700">
                    <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo $cancellationRate; ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Specialization Card -->
    <div class="bg-white ios-card p-6 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Popular Specializations</h2>
        <div class="space-y-4">
            <?php 
            if (!empty($specializationData)):
                foreach ($specializationData as $index => $spec): 
                    // Different colors for different specializations
                    $colors = [
                        'bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 
                        'bg-amber-500', 'bg-pink-500'
                    ];
                    $color = $colors[$index % count($colors)];
            ?>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            <?php echo str_replace('Physical Therapy', 'PT', $spec['specialization'] ?: 'General'); ?>
                        </span>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            <?php echo $spec['count']; ?> therapists
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 dark:bg-slate-700">
                        <div class="<?php echo $color; ?> h-2 rounded-full" style="width: <?php echo min(100, $spec['count'] * 10); ?>%"></div>
                    </div>
                </div>
            <?php 
                endforeach;
            else:
            ?>
                <div class="text-center text-slate-500 dark:text-slate-400 py-6">
                    <i class="fa-solid fa-chart-pie text-3xl mb-2"></i>
                    <p>No specialization data available</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>