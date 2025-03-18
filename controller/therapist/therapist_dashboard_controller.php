<?php
session_start();
require_once '../authentication/config.php'; // Using the path from your paste

// API Endpoint for Appointment Check
if (isset($_GET['api']) && $_GET['api'] === 'appointment-check') {
    // Verify that user is logged in and is a therapist
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    // Get therapist ID
    $user_id = $_SESSION['user_id'];
    $therapist_id = null;

    $stmt = $conn->prepare("SELECT id FROM therapists WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $therapist = $result->fetch_assoc();
        $therapist_id = $therapist['id'];
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Therapist record not found']);
        exit();
    }

    // Get the last known appointment ID from the request
    $last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

    // Get appointment counts for statistics
    $stmt = $conn->prepare("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) as scheduled,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN status = 'Rescheduled' THEN 1 ELSE 0 END) as rescheduled
        FROM appointments WHERE therapist_id = ?");
    $stmt->bind_param("i", $therapist_id);
    $stmt->execute();
    $counts = $stmt->get_result()->fetch_assoc();

    // Check for new appointments
    $stmt = $conn->prepare("SELECT MAX(id) as max_id FROM appointments WHERE therapist_id = ?");
    $stmt->bind_param("i", $therapist_id);
    $stmt->execute();
    $max_id_result = $stmt->get_result()->fetch_assoc();
    $max_id = $max_id_result['max_id'];

    // Count new appointments
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM appointments 
                           WHERE therapist_id = ? AND id > ? AND created_at > NOW() - INTERVAL 5 MINUTE");
    $stmt->bind_param("ii", $therapist_id, $last_id);
    $stmt->execute();
    $new_count_result = $stmt->get_result()->fetch_assoc();
    $new_appointment_count = $new_count_result['count'];

    // Get today's new appointments
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT a.*, 
        p.first_name AS patient_first_name, 
        p.last_name AS patient_last_name,
        p.email AS patient_email,
        p.treatment_needed
        FROM appointments a 
        INNER JOIN patients p ON a.patient_id = p.id 
        WHERE a.therapist_id = ? AND a.date = ? AND a.id > ?
        ORDER BY a.time ASC");
    $stmt->bind_param("isi", $therapist_id, $today, $last_id);
    $stmt->execute();
    $today_appointments = $stmt->get_result();
    $today_appointments_array = [];
    
    while ($appointment = $today_appointments->fetch_assoc()) {
        $today_appointments_array[] = $appointment;
    }

    // Get all new appointments
    $stmt = $conn->prepare("SELECT a.*, 
        p.first_name AS patient_first_name, 
        p.last_name AS patient_last_name,
        p.email AS patient_email,
        p.treatment_needed,
        p.medical_history
        FROM appointments a 
        INNER JOIN patients p ON a.patient_id = p.id 
        WHERE a.therapist_id = ? AND a.id > ?
        ORDER BY a.date ASC, a.time ASC");
    $stmt->bind_param("ii", $therapist_id, $last_id);
    $stmt->execute();
    $all_appointments = $stmt->get_result();
    $all_appointments_array = [];
    
    while ($appointment = $all_appointments->fetch_assoc()) {
        $all_appointments_array[] = $appointment;
    }

    // Prepare response
    $response = [
        'success' => true,
        'has_updates' => ($max_id > $last_id || $new_appointment_count > 0),
        'counts' => $counts,
        'last_id' => $max_id,
        'new_appointment_count' => $new_appointment_count,
        'today_appointments' => $today_appointments_array,
        'all_appointments' => $all_appointments_array
    ];

    // Send response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// API Endpoint for Appointment Updates
if (isset($_GET['api']) && $_GET['api'] === 'appointment-update') {
    // Verify that user is logged in and is a therapist
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }

    // Get therapist ID
    $user_id = $_SESSION['user_id'];
    $therapist_id = null;

    $stmt = $conn->prepare("SELECT id FROM therapists WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $therapist = $result->fetch_assoc();
        $therapist_id = $therapist['id'];
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Therapist record not found']);
        exit();
    }

    // Handle AJAX requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $response = ['success' => false];
        
        // Decode JSON data
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['action']) || !isset($data['appointment_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            exit();
        }
        
        $appointment_id = $data['appointment_id'];
        $action = $data['action'];
        
        // Verify the appointment belongs to this therapist
        $stmt = $conn->prepare("SELECT id FROM appointments WHERE id = ? AND therapist_id = ?");
        $stmt->bind_param("ii", $appointment_id, $therapist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You do not have permission to modify this appointment']);
            exit();
        }
        
        // Perform requested action
        switch ($action) {
            case 'accept':
                $stmt = $conn->prepare("UPDATE appointments SET status = 'Scheduled' WHERE id = ?");
                $stmt->bind_param("i", $appointment_id);
                break;
                
            case 'reschedule':
                if (!isset($data['date']) || !isset($data['time'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing date or time for reschedule']);
                    exit();
                }
                
                $date = $data['date'];
                $time = $data['time'];
                
                $stmt = $conn->prepare("UPDATE appointments SET date = ?, time = ?, status = 'Rescheduled' WHERE id = ?");
                $stmt->bind_param("ssi", $date, $time, $appointment_id);
                break;
                
            case 'update_notes':
                if (!isset($data['notes'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Missing notes parameter']);
                    exit();
                }
                
                $notes = $data['notes'];
                
                $stmt = $conn->prepare("UPDATE appointments SET notes = ? WHERE id = ?");
                $stmt->bind_param("si", $notes, $appointment_id);
                break;
                
            case 'cancel':
                $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ?");
                $stmt->bind_param("i", $appointment_id);
                break;
                
            case 'complete':
                $stmt = $conn->prepare("UPDATE appointments SET status = 'Completed' WHERE id = ?");
                $stmt->bind_param("i", $appointment_id);
                break;
                
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action requested']);
                exit();
        }
        
        if ($stmt->execute()) {
            // Get updated appointment data to return
            $stmt = $conn->prepare("SELECT a.*, 
                p.first_name AS patient_first_name, 
                p.last_name AS patient_last_name,
                p.email AS patient_email,
                p.treatment_needed
                FROM appointments a 
                INNER JOIN patients p ON a.patient_id = p.id 
                WHERE a.id = ?");
            $stmt->bind_param("i", $appointment_id);
            $stmt->execute();
            $appointment = $stmt->get_result()->fetch_assoc();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Appointment updated successfully',
                'appointment' => $appointment
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Database error: ' . $stmt->error
            ]);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
    exit();
}

// Regular dashboard page handling
// Check if user is logged in and is therapist
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'therapist') {
    header("Location: ../login.php?error=unauthorized");
    exit();
}

$user_id = $_SESSION['user_id'];
$therapist_id = null;

// Get the therapist ID from the therapists table
$stmt = $conn->prepare("SELECT id, first_name, last_name, specialization, consultation_fee FROM therapists WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $therapist = $result->fetch_assoc();
    $therapist_id = $therapist['id'];
    $fullName = $therapist['first_name'] . ' ' . $therapist['last_name'];
    $specialization = $therapist['specialization'];
    $consultation_fee = $therapist['consultation_fee'];
} else {
    // Handle error if therapist record not found
    $fullName = 'Therapist User';
    $specialization = 'Not specified';
    $consultation_fee = 'Not set';
    
    // Check if any name data is available in session
    if (isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
        $fullName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
    } elseif (isset($_SESSION['user_name'])) {
        $fullName = $_SESSION['user_name'];
    }
}

// Get unread notifications count
$notification_count = 0;
$notifications = [];

// Check if the notifications table exists
$table_exists = false;
$check_table = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($check_table->num_rows > 0) {
    $table_exists = true;
}

if ($table_exists && $user_id) {
    // Get unread notification count
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications 
                           WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $notification_count = $result->fetch_assoc()['count'];
    }
    
    // Get recent notifications
    $stmt = $conn->prepare("SELECT id, message, type, created_at, is_read, related_id 
                          FROM notifications 
                          WHERE user_id = ? 
                          ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $notifications_result = $stmt->get_result();
    
    while ($notification = $notifications_result->fetch_assoc()) {
        $notifications[] = $notification;
    }
}

// Get appointment counts for statistics
$stmt = $conn->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) as scheduled,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled,
    SUM(CASE WHEN status = 'Rescheduled' THEN 1 ELSE 0 END) as rescheduled
    FROM appointments WHERE therapist_id = ?");
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$counts = $stmt->get_result()->fetch_assoc();

// Get today's appointments
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT a.*, 
    p.first_name AS patient_first_name, 
    p.last_name AS patient_last_name,
    p.email AS patient_email,
    p.treatment_needed AS treatment_needed
    FROM appointments a 
    INNER JOIN patients p ON a.patient_id = p.id 
    WHERE a.therapist_id = ? AND a.date = ? 
    ORDER BY a.time ASC");
$stmt->bind_param("is", $therapist_id, $today);
$stmt->execute();
$today_appointments = $stmt->get_result();

// Get upcoming appointments (excluding today)
$stmt = $conn->prepare("SELECT a.*, 
    p.first_name AS patient_first_name, 
    p.last_name AS patient_last_name,
    p.email AS patient_email,
    p.treatment_needed AS treatment_needed
    FROM appointments a 
    INNER JOIN patients p ON a.patient_id = p.id 
    WHERE a.therapist_id = ? AND a.date > ? AND a.status IN ('Pending', 'Scheduled', 'Rescheduled')
    ORDER BY a.date ASC, a.time ASC
    LIMIT 10");
$stmt->bind_param("is", $therapist_id, $today);
$stmt->execute();
$upcoming_appointments = $stmt->get_result();

// Get all appointments for the main list
$stmt = $conn->prepare("SELECT a.*, 
    p.first_name AS patient_first_name, 
    p.last_name AS patient_last_name,
    p.email AS patient_email,
    p.treatment_needed AS treatment_needed,
    p.medical_history AS medical_history
    FROM appointments a 
    INNER JOIN patients p ON a.patient_id = p.id 
    WHERE a.therapist_id = ? 
    ORDER BY 
        CASE 
            WHEN a.status = 'Pending' THEN 1
            WHEN a.status = 'Scheduled' THEN 2
            WHEN a.status = 'Rescheduled' THEN 3
            WHEN a.status = 'Completed' THEN 4
            WHEN a.status = 'Cancelled' THEN 5
        END,
        a.date ASC, 
        a.time ASC");
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$all_appointments = $stmt->get_result();

// Find the highest appointment ID (for new appointment detection)
$max_appointment_id = 0;
$check_max_id = $conn->prepare("SELECT MAX(id) as max_id FROM appointments WHERE therapist_id = ?");
$check_max_id->bind_param("i", $therapist_id);
$check_max_id->execute();
$max_id_result = $check_max_id->get_result();
if ($max_id_result->num_rows > 0) {
    $max_appointment_id = $max_id_result->fetch_assoc()['max_id'];
}

// Get recent activity
$stmt = $conn->prepare("SELECT a.*, 
    p.first_name AS patient_first_name, 
    p.last_name AS patient_last_name,
    DATE_FORMAT(a.created_at, '%b %d, %Y at %h:%i %p') AS formatted_date
    FROM appointments a 
    INNER JOIN patients p ON a.patient_id = p.id 
    WHERE a.therapist_id = ? 
    ORDER BY a.created_at DESC
    LIMIT 5");
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$recent_activity = $stmt->get_result();

// Calculate appointment metrics percentages
$completion_rate = ($counts['total'] > 0) ? round(($counts['completed'] / $counts['total']) * 100) : 0;
$cancellation_rate = ($counts['total'] > 0) ? round(($counts['cancelled'] / $counts['total']) * 100) : 0;

// Function to get status badge HTML
function getStatusBadge($status) {
    $color = '';
    switch($status) {
        case 'Pending':
            $color = 'bg-yellow-500/20 text-yellow-500';
            break;
        case 'Scheduled':
            $color = 'bg-green-500/20 text-green-500';
            break;
        case 'Completed':
            $color = 'bg-blue-500/20 text-blue-500';
            break;
        case 'Cancelled':
            $color = 'bg-red-500/20 text-red-500';
            break;
        case 'Rescheduled':
            $color = 'bg-purple-500/20 text-purple-500';
            break;
    }
    return '<span class="px-2 py-1 rounded text-xs font-medium ' . $color . '">' . $status . '</span>';
}

// Process appointment actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];
        
        if ($_POST['action'] === 'accept') {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Scheduled' WHERE id = ? AND therapist_id = ?");
            $stmt->bind_param("ii", $appointment_id, $therapist_id);
            $stmt->execute();
        } 
        else if ($_POST['action'] === 'reschedule' && isset($_POST['date']) && isset($_POST['time'])) {
            $date = $_POST['date'];
            $time = $_POST['time'];
            $stmt = $conn->prepare("UPDATE appointments SET date = ?, time = ?, status = 'Rescheduled' WHERE id = ? AND therapist_id = ?");
            $stmt->bind_param("ssii", $date, $time, $appointment_id, $therapist_id);
            $stmt->execute();
        } 
        else if ($_POST['action'] === 'update_notes' && isset($_POST['notes'])) {
            $notes = $_POST['notes'];
            $stmt = $conn->prepare("UPDATE appointments SET notes = ? WHERE id = ? AND therapist_id = ?");
            $stmt->bind_param("sii", $notes, $appointment_id, $therapist_id);
            $stmt->execute();
        } 
        else if ($_POST['action'] === 'cancel') {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ? AND therapist_id = ?");
            $stmt->bind_param("ii", $appointment_id, $therapist_id);
            $stmt->execute();
        }
        else if ($_POST['action'] === 'complete') {
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Completed' WHERE id = ? AND therapist_id = ?");
            $stmt->bind_param("ii", $appointment_id, $therapist_id);
            $stmt->execute();
        }
        else if ($_POST['action'] === 'mark_read' && $table_exists) {
            // Mark notification as read
            $notification_id = $_POST['notification_id'];
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $notification_id, $user_id);
            $stmt->execute();
        }
        else if ($_POST['action'] === 'mark_all_read' && $table_exists) {
            // Mark all notifications as read
            $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
        
        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>