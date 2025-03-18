<?php
// update_appointment_status.php
header('Content-Type: application/json');

// Include database connection
include_once '../authentication/config.php';

// Start session if needed for authentication
session_start();

// Database connection fallback in case include fails
if (!isset($conn)) {
    try {
        $conn = new mysqli("localhost", "root", "", "theracare");
        if ($conn->connect_error) {
            die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
        }
    } catch(Exception $e) {
        die(json_encode(['success' => false, 'message' => "Connection failed: " . $e->getMessage()]));
    }
}

// Check if request is POST and contains required data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Validate status value against allowed enum values from database
    $valid_statuses = [];
    $status_query = "SHOW COLUMNS FROM appointments LIKE 'status'";
    $status_result = $conn->query($status_query);
    if ($status_result && $status_result->num_rows > 0) {
        $row = $status_result->fetch_assoc();
        if (isset($row['Type'])) {
            // If it's an enum, parse the values
            if (preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
                $valid_statuses = explode("','", $matches[1]);
            }
        }
    }
    
    // If we couldn't get valid statuses from DB, use hardcoded values
    if (empty($valid_statuses)) {
        $valid_statuses = ['Pending', 'Scheduled', 'Completed', 'Cancelled', 'Rescheduled'];
    }
    
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit;
    }
    
    // Get current appointment information before update (for logging or notifications)
    $current_info = [];
    $info_query = $conn->prepare("SELECT status, patient_id, therapist_id FROM appointments WHERE id = ?");
    $info_query->bind_param("i", $appointment_id);
    $info_query->execute();
    $info_result = $info_query->get_result();
    if ($info_result->num_rows > 0) {
        $current_info = $info_result->fetch_assoc();
    } else {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
        exit;
    }
    $info_query->close();
    
    // Update appointment status in database
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $appointment_id);
    
    if ($stmt->execute()) {
        // If appointment was cancelled, update therapist status if needed
        if ($status === 'Cancelled' && $current_info['status'] !== 'Cancelled') {
            // Check appointment count for the therapist for this date
            $therapist_id = $current_info['therapist_id'];
            $appt_query = $conn->prepare("SELECT date FROM appointments WHERE id = ?");
            $appt_query->bind_param("i", $appointment_id);
            $appt_query->execute();
            $appt_result = $appt_query->get_result();
            $appt_row = $appt_result->fetch_assoc();
            $appt_date = $appt_row['date'];
            $appt_query->close();
            
            // Update therapist status to Available if their appointment count dropped
            $update_therapist = $conn->prepare("
                UPDATE therapists SET status = 'Available' 
                WHERE id = ? AND status = 'Busy' AND 
                (SELECT COUNT(*) FROM appointments 
                 WHERE therapist_id = ? AND date = ? AND status NOT IN ('Cancelled', 'Rescheduled')) 
                < available_slots
            ");
            $update_therapist->bind_param("iis", $therapist_id, $therapist_id, $appt_date);
            $update_therapist->execute();
            $update_therapist->close();
        }
        
        echo json_encode([
            'success' => true, 
            'message' => "Appointment status updated to {$status}",
            'appointment_id' => $appointment_id,
            'status' => $status
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "Failed to update status: " . $conn->error
        ]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>