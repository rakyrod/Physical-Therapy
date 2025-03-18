<?php
// Create database connection
$conn = mysqli_connect("localhost", "root", "", "theracare");

// Check connection
if (!$conn) {
    error_log("Database Connection Error: " . mysqli_connect_error());
    die("Sorry, there was a problem connecting to the database. Please try again later.");
}

try {
    // Get key metrics from database
    // Users statistics
    $usersQuery = "SELECT 
        COUNT(*) as total_users,
        SUM(CASE WHEN role = 'patient' THEN 1 ELSE 0 END) as total_patients,
        SUM(CASE WHEN role = 'therapist' THEN 1 ELSE 0 END) as total_therapists,
        SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as total_admins,
        SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_users_30d
    FROM users";
    $usersResult = mysqli_query($conn, $usersQuery);
    $userData = mysqli_fetch_assoc($usersResult);
    
    // Calculate user growth percentage (comparing to previous 30 days)
    $previousUsersQuery = "SELECT COUNT(*) as prev_period_users FROM users 
                           WHERE created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $previousUsersResult = mysqli_query($conn, $previousUsersQuery);
    $previousUsersData = mysqli_fetch_assoc($previousUsersResult);
    
    $userGrowth = 0;
    if ($previousUsersData['prev_period_users'] > 0) {
        $userGrowth = round(($userData['new_users_30d'] - $previousUsersData['prev_period_users']) / $previousUsersData['prev_period_users'] * 100, 1);
    }
    
    // Calculate patient growth percentage (comparing to previous 30 days)
    $newPatientsQuery = "SELECT COUNT(*) as new_patients_30d FROM users 
                         WHERE role = 'patient' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $newPatientsResult = mysqli_query($conn, $newPatientsQuery);
    $newPatientsData = mysqli_fetch_assoc($newPatientsResult);
    
    $previousPatientsQuery = "SELECT COUNT(*) as prev_period_patients FROM users 
                             WHERE role = 'patient' AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $previousPatientsResult = mysqli_query($conn, $previousPatientsQuery);
    $previousPatientsData = mysqli_fetch_assoc($previousPatientsResult);
    
    $patientGrowth = 0;
    if ($previousPatientsData['prev_period_patients'] > 0) {
        $patientGrowth = round(($newPatientsData['new_patients_30d'] - $previousPatientsData['prev_period_patients']) / $previousPatientsData['prev_period_patients'] * 100, 1);
    }
    
    // Calculate therapist growth percentage (comparing to previous 30 days)
    $newTherapistsQuery = "SELECT COUNT(*) as new_therapists_30d FROM users 
                          WHERE role = 'therapist' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $newTherapistsResult = mysqli_query($conn, $newTherapistsQuery);
    $newTherapistsData = mysqli_fetch_assoc($newTherapistsResult);
    
    $previousTherapistsQuery = "SELECT COUNT(*) as prev_period_therapists FROM users 
                               WHERE role = 'therapist' AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $previousTherapistsResult = mysqli_query($conn, $previousTherapistsQuery);
    $previousTherapistsData = mysqli_fetch_assoc($previousTherapistsResult);
    
    $therapistGrowth = 0;
    if ($previousTherapistsData['prev_period_therapists'] > 0) {
        $therapistGrowth = round(($newTherapistsData['new_therapists_30d'] - $previousTherapistsData['prev_period_therapists']) / $previousTherapistsData['prev_period_therapists'] * 100, 1);
    }
    
    // Appointments statistics
    $appointmentsQuery = "SELECT 
        COUNT(*) as total_appointments,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed_appointments,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled_appointments,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_appointments,
        SUM(CASE WHEN date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as upcoming_week
    FROM appointments";
    $appointmentsResult = mysqli_query($conn, $appointmentsQuery);
    $appointmentsData = mysqli_fetch_assoc($appointmentsResult);
    
    // Therapist specialization distribution
    $specializationQuery = "SELECT specialization, COUNT(*) as count FROM therapists GROUP BY specialization ORDER BY count DESC LIMIT 5";
    $specializationResult = mysqli_query($conn, $specializationQuery);
    $specializationData = [];
    while ($row = mysqli_fetch_assoc($specializationResult)) {
        $specializationData[] = $row;
    }
    
    // Calculate appointment percentage changes
    $previousApptsQuery = "SELECT COUNT(*) as prev_appts FROM appointments 
                           WHERE created_at BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $previousApptsResult = mysqli_query($conn, $previousApptsQuery);
    $previousApptsData = mysqli_fetch_assoc($previousApptsResult);
    
    $apptsGrowth = 0;
    if ($previousApptsData['prev_appts'] > 0) {
        $apptsGrowth = round(($appointmentsData['upcoming_week'] - $previousApptsData['prev_appts']) / $previousApptsData['prev_appts'] * 100, 1);
    }
    
} catch (Exception $e) {
    error_log("Query Error: " . $e->getMessage());
    die("Sorry, there was a problem retrieving data. Please try again later.");
} finally {
    mysqli_close($conn);
}

// Calculate completion rate
$completionRate = 0;
if ($appointmentsData['total_appointments'] > 0) {
    $completionRate = round(($appointmentsData['completed_appointments'] / $appointmentsData['total_appointments']) * 100);
}

// Calculate cancellation rate
$cancellationRate = 0;
if ($appointmentsData['total_appointments'] > 0) {
    $cancellationRate = round(($appointmentsData['cancelled_appointments'] / $appointmentsData['total_appointments']) * 100);
}
?>