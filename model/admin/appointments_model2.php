<?php
                        // Database connection
                        $conn = mysqli_connect("localhost", "root", "", "theracare");
                        
                        // Check connection
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        
                        // Get the first day of the month
                        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
                        $numberDays = date('t', $firstDayOfMonth);
                        $dateComponents = getdate($firstDayOfMonth);
                        $monthName = $dateComponents['month'];
                        $dayOfWeek = $dateComponents['wday'];
                        
                        // Create calendar cells for days before the start of month
                        for ($i = 0; $i < $dayOfWeek; $i++) {
                            echo '<div class="h-24 p-1 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-lg opacity-50"></div>';
                        }
                        
                        // Fetch appointments for this month
                        $startDate = "$year-$month-01";
                        $endDate = "$year-$month-$numberDays";

                        // Using your actual table structure
                        $sql = "SELECT a.id, a.date, a.time, a.status, a.patient_id, a.therapist_id, a.visit_type, a.notes,
                              CONCAT(p.first_name, ' ', p.last_name) AS patient_name, 
                              CONCAT(t.first_name, ' ', t.last_name) AS therapist_name,
                              t.specialization AS therapist_specialization
                              FROM appointments a
                              LEFT JOIN patients p ON a.patient_id = p.id
                              LEFT JOIN therapists t ON a.therapist_id = t.id
                              WHERE a.date BETWEEN '$startDate' AND '$endDate'";

                        $result = mysqli_query($conn, $sql);

                        // Create array of appointments indexed by day
                        $appointments = [];
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $day = date('j', strtotime($row['date']));
                                if (!isset($appointments[$day])) {
                                    $appointments[$day] = [];
                                }
                                $appointments[$day][] = $row;
                            }
                        }
                        
                        // Create calendar cells for days of the month
                        for ($i = 1; $i <= $numberDays; $i++) {
                            $isToday = ($i == date('j') && $month == date('n') && $year == date('Y'));
                            $todayClass = $isToday ? 'ring-2 ring-blue-500 dark:ring-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-200';
                            $dayNumberClass = $isToday ? 'bg-blue-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200';
                            
                            echo '<div class="h-24 p-1 border border-slate-200 dark:border-slate-700 rounded-lg ' . $todayClass . ' overflow-hidden group">';
                            echo '<div class="font-medium text-sm mb-1 w-6 h-6 flex items-center justify-center rounded-full ' . $dayNumberClass . '">' . $i . '</div>';
                            
                            // Display appointments for this day
                            if (isset($appointments[$i])) {
                                echo '<div class="space-y-1 overflow-y-auto max-h-16 custom-scrollbar">';
                                foreach ($appointments[$i] as $appointment) {
                                    // Determine status color
                                    $statusColor = '';
                                    $statusIcon = '';
                                    switch($appointment['status'] ?? 'Scheduled') {
                                        case 'Pending':
                                            $statusColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400 border-l-4 border-yellow-500';
                                            $statusIcon = '<i class="fa-solid fa-clock size-3"></i>';
                                            break;
                                        case 'Scheduled':
                                            $statusColor = 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400 border-l-4 border-blue-500';
                                            $statusIcon = '<i class="fa-solid fa-calendar-check size-3"></i>';
                                            break;
                                        case 'Completed':
                                            $statusColor = 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400 border-l-4 border-green-500';
                                            $statusIcon = '<i class="fa-solid fa-check size-3"></i>';
                                            break;
                                        case 'Cancelled':
                                            $statusColor = 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400 border-l-4 border-red-500';
                                            $statusIcon = '<i class="fa-solid fa-times size-3"></i>';
                                            break;
                                        case 'Rescheduled':
                                            $statusColor = 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-400 border-l-4 border-purple-500';
                                            $statusIcon = '<i class="fa-solid fa-rotate size-3"></i>';
                                            break;
                                    }
                                
                                    $time = isset($appointment['time']) ? date('H:i', strtotime($appointment['time'])) : '';
                                    $patientName = $appointment['patient_name'] ?? 'Unknown Patient';
                                    $therapistName = $appointment['therapist_name'] ?? 'Unassigned';
                                    $appointmentId = $appointment['id'] ?? '';
                                    $visitType = $appointment['visit_type'] ?? '';
                                    $notes = $appointment['notes'] ?? '';
                                    
                                    // Check if this is an emergency appointment
                                    $isEmergency = ($visitType === 'Emergency');
                                    $emergencyClass = $isEmergency ? 'border-r-4 border-red-500 animate-pulse' : '';
                                    
                                    // Create data attributes for all the appointment details
                                    echo '<div class="appointment-card p-1 text-xs rounded ' . $statusColor . ' ' . $emergencyClass . ' truncate shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer relative" 
                                        data-appointment-id="' . $appointmentId . '"
                                        data-patient-name="' . htmlspecialchars($patientName) . '"
                                        data-therapist-name="' . htmlspecialchars($therapistName) . '"
                                        data-therapist-id="' . $appointment['therapist_id'] . '"
                                        data-therapist-specialization="' . htmlspecialchars($appointment['therapist_specialization']) . '"
                                        data-status="' . ($appointment['status'] ?? 'Scheduled') . '"
                                        data-time="' . $time . '"
                                        data-date="' . ($appointment['date'] ?? '') . '"
                                        data-visit-type="' . htmlspecialchars($visitType) . '"
                                        data-notes="' . htmlspecialchars($notes) . '"
                                        onclick="showAppointmentDetails(this)"
                                        onmouseover="showAppointmentTooltip(this)"
                                        onmouseout="hideAppointmentTooltip()">';
                                    
                                    echo '<div class="flex items-center gap-1">';
                                    echo $statusIcon;
                                    echo '<span class="font-semibold">' . $time . '</span>';
                                    
                                    // Add emergency indicator
                                    if ($isEmergency) {
                                        echo '<span class="inline-flex items-center justify-center w-4 h-4 ml-auto bg-red-600 text-white rounded-full" title="Emergency">
                                                <i class="fa-solid fa-exclamation text-[8px]"></i>
                                             </span>';
                                    }
                                    
                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                // Add a subtle "add" button that appears on hover for empty days
                                echo '<div class="hidden group-hover:flex justify-center items-center h-12 opacity-30 hover:opacity-100 transition-opacity">';
                                echo '<button class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center ios-btn" onclick="openAppointmentModal(event, \''. $year .'-'. sprintf('%02d', $month) .'-'. sprintf('%02d', $i) .'\')">';
                                echo '<i class="fa-solid fa-plus text-slate-600 dark:text-slate-300"></i>';
                                echo '</button>';
                                echo '</div>';
                            }
                            
                            echo '</div>';
                            
                            // Start a new row if it's the end of the week
                            if (($i + $dayOfWeek) % 7 == 0 && $i < $numberDays) {
                                echo '</div><div class="grid grid-cols-7 gap-1">';
                            }
                        }
                        
                        // Fill in empty cells at the end
                        $totalCells = $dayOfWeek + $numberDays;
                        $remainingCells = 7 - ($totalCells % 7);
                        if ($remainingCells < 7) {
                            for ($i = 0; $i < $remainingCells; $i++) {
                                echo '<div class="h-24 p-1 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-lg opacity-50"></div>';
                            }
                        }
                        
                        mysqli_close($conn);
                        ?>