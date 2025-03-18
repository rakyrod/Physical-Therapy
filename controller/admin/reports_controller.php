<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "theracare";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Analytics queries

// 1. Overall stats
$overallStatsQuery = "SELECT 
    (SELECT COUNT(*) FROM patients) AS total_patients,
    (SELECT COUNT(*) FROM therapists) AS total_therapists,
    (SELECT COUNT(*) FROM appointments) AS total_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Completed') AS completed_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Cancelled') AS cancelled_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Scheduled') AS scheduled_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Pending') AS pending_appointments,
    (SELECT SUM(t.consultation_fee) 
        FROM appointments a 
        JOIN therapists t ON a.therapist_id = t.id 
        WHERE a.status = 'Completed' AND t.consultation_fee IS NOT NULL) AS total_revenue";

$overallResult = $conn->query($overallStatsQuery);
$stats = $overallResult->fetch_assoc();

// Calculate completion rate
$completionRate = ($stats['total_appointments'] > 0) ? 
    round(($stats['completed_appointments'] / $stats['total_appointments']) * 100) : 0;

// 2. Patients by specialization - FIX: Using treatment_needed instead of specialization
$patientsBySpecQuery = "SELECT 
    treatment_needed as specialization, 
    COUNT(*) as count 
    FROM patients 
    WHERE treatment_needed IS NOT NULL 
    GROUP BY treatment_needed 
    ORDER BY count DESC";

$patientsBySpecResult = $conn->query($patientsBySpecQuery);
$patientsBySpec = [];

if ($patientsBySpecResult && $patientsBySpecResult->num_rows > 0) {
    while ($row = $patientsBySpecResult->fetch_assoc()) {
        $patientsBySpec[] = $row;
    }
}

// 3. Appointment status distribution
$appointmentStatusChart = [
    ['label' => 'Completed', 'value' => (int)$stats['completed_appointments']],
    ['label' => 'Scheduled', 'value' => (int)$stats['scheduled_appointments']],
    ['label' => 'Pending', 'value' => (int)$stats['pending_appointments']],
    ['label' => 'Cancelled', 'value' => (int)$stats['cancelled_appointments']]
];

// 4. Monthly growth - Compare current month with previous month
$monthlyGrowthQuery = "SELECT
    (SELECT COUNT(*) FROM patients WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) as current_month_patients,
    (SELECT COUNT(*) FROM patients WHERE MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) as prev_month_patients,
    (SELECT COUNT(*) FROM appointments WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())) as current_month_appointments,
    (SELECT COUNT(*) FROM appointments WHERE MONTH(date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) as prev_month_appointments";
$monthlyGrowthResult = $conn->query($monthlyGrowthQuery);
$monthlyGrowth = $monthlyGrowthResult->fetch_assoc();

// Calculate growth percentages
$patientGrowth = ($monthlyGrowth['prev_month_patients'] > 0) ?
    round((($monthlyGrowth['current_month_patients'] - $monthlyGrowth['prev_month_patients']) / $monthlyGrowth['prev_month_patients']) * 100) : 0;
$appointmentGrowth = ($monthlyGrowth['prev_month_appointments'] > 0) ?
    round((($monthlyGrowth['current_month_appointments'] - $monthlyGrowth['prev_month_appointments']) / $monthlyGrowth['prev_month_appointments']) * 100) : 0;

// 5. Get all therapists with their assigned patients
$therapistsQuery = "SELECT 
    t.id,
    t.first_name,
    t.last_name,
    t.specialization,
    t.email,
    t.phone_number,
    t.consultation_fee,
    COUNT(DISTINCT a.patient_id) as patient_count,
    COUNT(a.id) as appointment_count
    FROM therapists t
    LEFT JOIN appointments a ON t.id = a.therapist_id
    GROUP BY t.id
    ORDER BY t.first_name, t.last_name";

$therapistsResult = $conn->query($therapistsQuery);
$therapists = [];

if ($therapistsResult && $therapistsResult->num_rows > 0) {
    while ($row = $therapistsResult->fetch_assoc()) {
        $therapists[] = $row;
    }
}

// Format monetary values
$totalRevenue = $stats['total_revenue'] ? '₱' . number_format((float)$stats['total_revenue'], 2) : '₱0.00';

?>