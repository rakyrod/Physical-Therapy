<?php
                                    // Database connection
                                    $conn = mysqli_connect("localhost", "root", "", "theracare");
                                    
                                    // Check connection
                                    if (!$conn) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }
                                    
                                    // Set timezone to ensure accurate time calculations
                                    date_default_timezone_set('Asia/Manila'); // Adjust to your timezone
                                    
                                    // Pagination setup
                                    $results_per_page = 10; // Show 10 users per page
                                    
                                    // Determine current page
                                    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                                        $page = $_GET['page'];
                                    } else {
                                        $page = 1;
                                    }
                                    
                                    $start_from = ($page - 1) * $results_per_page;
                                    
                                    // Fetch users from database with search functionality
                                    $sql = "SELECT *, CONCAT(first_name, ' ', last_name) AS full_name FROM users";

                                    // If search parameter is set, modify the query
                                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                                        $sql = "SELECT *, CONCAT(first_name, ' ', last_name) AS full_name FROM users 
                                                WHERE 
                                                CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR 
                                                email LIKE '%$search%' OR 
                                                role LIKE '%$search%'";
                                    }

                                    // Add ORDER BY created_at DESC to show newest users first
                                    $sql .= " ORDER BY created_at DESC";

                                    // Get total records for pagination
                                    $result_count = mysqli_query($conn, $sql);
                                    $userCount = mysqli_num_rows($result_count);

                                    // Add pagination limit
                                    $sql .= " LIMIT $start_from, $results_per_page";
                                    
                                    $result = mysqli_query($conn, $sql);
                                    
                                    if (mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            // Determine badge color based on role
                                            $roleBadgeClass = "";
                                            $badgeIcon = "";
                                            $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($row['first_name'] . ' ' . $row['last_name']) . "&background=random&color=fff&size=128";
                                            
                                            switch($row['role']) {
                                                case 'admin':
                                                    $roleBadgeClass = "bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500";
                                                    $badgeIcon = "fa-solid fa-shield-halved";
                                                    break;
                                                case 'patient':
                                                    $roleBadgeClass = "bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-500";
                                                    $badgeIcon = "fa-solid fa-user";
                                                    break;
                                                case 'therapist':
                                                    $roleBadgeClass = "bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500";
                                                    $badgeIcon = "fa-solid fa-stethoscope";
                                                    break;
                                            }
                                    ?>
                                    
                                    <tr class="table-row hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                        <td class="whitespace-nowrap">
                                            <div class="ps-4 pe-3 py-2">
                                                <div class="flex items-center gap-x-2">
                                                    <img class="inline-block size-8 rounded-full ring-2 ring-white dark:ring-slate-800" src="<?php echo $avatarUrl; ?>" alt="Avatar">
                                                    <div class="grow">
                                                        <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                            <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="whitespace-nowrap">
                                            <div class="px-3 py-2">
                                                <span class="block text-sm text-slate-600 dark:text-slate-400 compact-cell"><?php echo htmlspecialchars($row['email']); ?></span>
                                            </div>
                                        </td>
                                        
                                        <td class="whitespace-nowrap">
                                            <div class="px-3 py-2">
                                                <span class="ios-badge <?php echo $roleBadgeClass; ?> py-1 px-2 text-xs">
                                                    <i class="<?php echo $badgeIcon; ?> size-3"></i>
                                                    <?php echo ucfirst($row['role']); ?>
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="whitespace-nowrap">
                                            <div class="px-3 py-2">
                                                <span class="text-sm text-slate-600 dark:text-slate-400">
                                                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                                            <div class="flex flex-col items-center">
                                                <i class="fa-solid fa-users-slash text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                                <h3 class="text-base font-medium text-slate-700 dark:text-slate-300">No users found</h3>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Try adjusting your search criteria</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="px-4 py-3 grid gap-2 md:flex md:justify-between md:items-center border-t border-slate-200 dark:border-slate-700">
                            <div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <span class="font-semibold text-slate-800 dark:text-slate-200"><?php echo $userCount; ?></span> results
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-1">
                                <?php
                                $total_pages = ceil($userCount / $results_per_page);
                                
                                // Previous button
                                if ($page > 1) {
                                    echo '<a href="?page='.($page-1).'" class="ios-btn py-1.5 px-2.5 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"><i class="fa-solid fa-chevron-left size-3"></i> Prev</a>';
                                } else {
                                    echo '<button disabled class="ios-btn py-1.5 px-2.5 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300"><i class="fa-solid fa-chevron-left size-3"></i> Prev</button>';
                                }
                                
                                // Page buttons
                                echo '<div class="flex items-center gap-1">';
                                
                                // Calculate range of pages to show
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $start_page + 4);
                                
                                if ($start_page > 1) {
                                    echo '<a href="?page=1" class="size-7 flex justify-center items-center text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span class="size-7 flex justify-center items-center text-sm font-medium text-slate-500 dark:text-slate-400">...</span>';
                                    }
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    if ($i == $page) {
                                        echo '<span class="size-7 flex justify-center items-center text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-500">'.$i.'</span>';
                                    } else {
                                        echo '<a href="?page='.$i.'" class="size-7 flex justify-center items-center text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">'.$i.'</a>';
                                    }
                                }
                                
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span class="size-7 flex justify-center items-center text-sm font-medium text-slate-500 dark:text-slate-400">...</span>';
                                    }
                                    echo '<a href="?page='.$total_pages.'" class="size-7 flex justify-center items-center text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">'.$total_pages.'</a>';
                                }
                                
                                echo '</div>';
                                
                                // Next button
                                if ($page < $total_pages) {
                                    echo '<a href="?page='.($page+1).'" class="ios-btn py-1.5 px-2.5 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Next <i class="fa-solid fa-chevron-right size-3"></i></a>';
                                } else {
                                    echo '<button disabled class="ios-btn py-1.5 px-2.5 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg border border-slate-200 bg-white text-slate-800 shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-slate-700 dark:text-slate-300">Next <i class="fa-solid fa-chevron-right size-3"></i></button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Column -->
                <div class="space-y-6">
                    <!-- Today's Schedule -->
                    <div class="bg-white ios-card overflow-hidden border border-slate-200 dark:border-slate-700 dark:bg-slate-900">
                        <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Today's Schedule</h2>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                        <?php echo date('F d, Y'); ?>
                                    </p>
                                </div>
                                <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-800/30 dark:text-blue-500">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-4 py-3">
                            <?php
                            // Fetch today's appointments
                            $today = date('Y-m-d');
                            $appointments_sql = "SELECT a.id, a.date, a.time, a.visit_type, a.status, 
                                                p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
                                                t.first_name AS therapist_first_name, t.last_name AS therapist_last_name 
                                                FROM appointments a 
                                                JOIN patients p ON a.patient_id = p.id 
                                                JOIN therapists t ON a.therapist_id = t.id 
                                                WHERE a.date = '$today' 
                                                ORDER BY a.time ASC 
                                                LIMIT 5";
                            
                            $appointments_result = mysqli_query($conn, $appointments_sql);
                            
                            if (mysqli_num_rows($appointments_result) > 0) {
                                while($appointment = mysqli_fetch_assoc($appointments_result)) {
                                    // Set status color
                                    $statusClass = "";
                                    switch($appointment['status']) {
                                        case 'Pending':
                                            $statusClass = "bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500";
                                            break;
                                        case 'Scheduled':
                                            $statusClass = "bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500";
                                            break;
                                        case 'Completed':
                                            $statusClass = "bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-500";
                                            break;
                                        case 'Cancelled':
                                            $statusClass = "bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500";
                                            break;
                                        case 'Rescheduled':
                                            $statusClass = "bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-500";
                                            break;
                                    }
                            ?>
                            <div class="appointment-item flex items-center justify-between p-2.5 rounded-lg border border-slate-100 dark:border-slate-800 mb-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <div class="flex items-center gap-2">
                                    <div class="size-8 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 dark:bg-blue-800/30 dark:text-blue-500">
                                        <i class="fa-solid fa-user-clock text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 dark:text-slate-200">
                                            <?php echo date('h:i A', strtotime($appointment['time'])); ?> - <?php echo $appointment['visit_type']; ?>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Patient: <?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            Therapist: <?php echo htmlspecialchars($appointment['therapist_first_name'] . ' ' . $appointment['therapist_last_name']); ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="ios-badge <?php echo $statusClass; ?> py-1 px-2 text-xs">
                                    <?php echo $appointment['status']; ?>
                                </span>
                            </div>
                            <?php 
                                }
                            } else {
                            ?>
                            <div class="text-center py-5">
                                <div class="inline-flex items-center justify-center size-14 rounded-full bg-blue-100/50 text-blue-500 mb-3">
                                    <i class="fa-solid fa-calendar-xmark text-xl"></i>
                                </div>
                                <h3 class="text-base font-medium text-slate-700 dark:text-slate-300">No appointments today</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Enjoy your free day!</p>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="bg-white ios-card overflow-hidden border border-slate-200 dark:border-slate-700 dark:bg-slate-900">
                        <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Recent Activities</h2>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                        System activity log
                                    </p>
                                </div>
                                <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-800/30 dark:text-blue-500">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-4 py-3">
                            <div class="relative pl-8">
                                <?php
                                // Get recent user registrations
                                $recent_users_sql = "SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 3";
                                $recent_users_result = mysqli_query($conn, $recent_users_sql);
                                
                                // Get recent appointments
                                $recent_appointments_sql = "SELECT a.id, a.date, a.time, a.visit_type, a.status, a.created_at,
                                                          p.first_name AS patient_first_name, p.last_name AS patient_last_name
                                                          FROM appointments a 
                                                          JOIN patients p ON a.patient_id = p.id
                                                          ORDER BY a.created_at DESC LIMIT 3";
                                $recent_appointments_result = mysqli_query($conn, $recent_appointments_sql);
                                
                                // Combine and sort by created_at
                                $activities = array();
                                
                                while($user = mysqli_fetch_assoc($recent_users_result)) {
                                    $activities[] = array(
                                        'type' => 'user',
                                        'data' => $user,
                                        'created_at' => strtotime($user['created_at'])
                                    );
                                }
                                
                                while($appointment = mysqli_fetch_assoc($recent_appointments_result)) {
                                    $activities[] = array(
                                        'type' => 'appointment',
                                        'data' => $appointment,
                                        'created_at' => strtotime($appointment['created_at'])
                                    );
                                }
                                
                                // Sort by created_at descending
                                usort($activities, function($a, $b) {
                                    return $b['created_at'] - $a['created_at'];
                                });
                                
                                // Take top 5 activities
                                $activities = array_slice($activities, 0, 5);
                                
                                if (count($activities) > 0) {
                                    foreach($activities as $index => $activity) {
                                        // Last item doesn't need a line
                                        $has_line = $index < count($activities) - 1;
                                        
                                        if ($activity['type'] == 'user') {
                                            $user = $activity['data'];
                                            $roleBadgeClass = "";
                                            
                                            switch($user['role']) {
                                                case 'admin':
                                                    $roleBadgeClass = "bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500";
                                                    break;
                                                case 'patient':
                                                    $roleBadgeClass = "bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-500";
                                                    break;
                                                case 'therapist':
                                                    $roleBadgeClass = "bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500";
                                                    break;
                                            }
                                ?>
                                <div class="relative mb-5 activity-dot">
                                    <?php if ($has_line) { ?>
                                    <div class="activity-line"></div>
                                    <?php } ?>
                                    <h3 class="text-sm font-medium text-slate-800 dark:text-slate-200">New user registered</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> 
                                        <span class="ios-badge <?php echo $roleBadgeClass; ?> py-1 px-2 text-xs ml-1">
                                            <?php echo ucfirst($user['role']); ?>
                                        </span>
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                        <?php 
                                        // Timestamp display that shows correct time differences
                                        $timestamp = strtotime($user['created_at']);
                                        $now = time();
                                        $diff = $now - $timestamp;
                                        
                                        if ($diff < 60) {
                                            echo "Just now";
                                        } elseif ($diff < 3600) {
                                            $minutes = floor($diff / 60);
                                            echo $minutes . ($minutes == 1 ? " minute" : " minutes") . " ago";
                                        } elseif ($diff < 86400) {
                                            $hours = floor($diff / 3600);
                                            echo $hours . ($hours == 1 ? " hour" : " hours") . " ago";
                                        } elseif ($diff < 604800) { // Less than a week
                                            $days = floor($diff / 86400);
                                            echo $days . ($days == 1 ? " day" : " days") . " ago";
                                        } else {
                                            echo date('M d, Y', $timestamp);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                        } else {
                                            $appointment = $activity['data'];
                                            $statusClass = "";
                                            
                                            switch($appointment['status']) {
                                                case 'Pending':
                                                    $statusClass = "bg-amber-100 text-amber-800 dark:bg-amber-500/10 dark:text-amber-500";
                                                    break;
                                                case 'Scheduled':
                                                    $statusClass = "bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-500";
                                                    break;
                                                case 'Completed':
                                                    $statusClass = "bg-emerald-100 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-500";
                                                    break;
                                                case 'Cancelled':
                                                    $statusClass = "bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-500";
                                                    break;
                                                case 'Rescheduled':
                                                    $statusClass = "bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-500";
                                                    break;
                                            }
                                ?>
                                <div class="relative mb-5 activity-dot">
                                    <?php if ($has_line) { ?>
                                    <div class="activity-line"></div>
                                    <?php } ?>
                                    <h3 class="text-sm font-medium text-slate-800 dark:text-slate-200">New appointment created</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        <?php echo date('M d, Y', strtotime($appointment['date'])); ?> at <?php echo date('h:i A', strtotime($appointment['time'])); ?> 
                                        <span class="ios-badge <?php echo $statusClass; ?> py-1 px-2 text-xs ml-1">
                                            <?php echo $appointment['status']; ?>
                                        </span>
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Patient: <?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                        <?php 
                                        // Timestamp display for appointments
                                        $timestamp = strtotime($appointment['created_at']);
                                        $now = time();
                                        $diff = $now - $timestamp;
                                        
                                        if ($diff < 60) {
                                            echo "Just now";
                                        } elseif ($diff < 3600) {
                                            $minutes = floor($diff / 60);
                                            echo $minutes . ($minutes == 1 ? " minute" : " minutes") . " ago";
                                        } elseif ($diff < 86400) {
                                            $hours = floor($diff / 3600);
                                            echo $hours . ($hours == 1 ? " hour" : " hours") . " ago";
                                        } elseif ($diff < 604800) { // Less than a week
                                            $days = floor($diff / 86400);
                                            echo $days . ($days == 1 ? " day" : " days") . " ago";
                                        } else {
                                            echo date('M d, Y', $timestamp);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                        }
                                    }
                                } else {
                                ?>
                                <div class="text-center py-5">
                                    <div class="inline-flex items-center justify-center size-14 rounded-full bg-blue-100/50 text-blue-500 mb-3">
                                        <i class="fa-solid fa-hourglass-empty text-xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-slate-700 dark:text-slate-300">No recent activity</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">System activities will appear here</p>
                                </div>
                                <?php
                                }
                                ?>