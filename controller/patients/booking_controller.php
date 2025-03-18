<?php
/**
 * TheraCare Patient Appointment Booking System
 * 
 * This file handles appointment booking functionality for patients including:
 * - Booking new appointments
 * - Viewing existing appointments
 * - Canceling appointments
 * - Rescheduling appointments
 * - AJAX functionality for real-time status updates
 * 
 * @version 1.3
 * @author TheraCare Development Team
 */

// Start output buffering to prevent "headers already sent" errors
ob_start();

// ======================================================
// AJAX REQUEST HANDLING
// ======================================================
// Process AJAX requests separately before any HTML output
if (isset($_POST['ajax_action'])) {
    // Modified session handling - only access existing session for AJAX
    if (session_id() === '') {
        // Only start a session if one isn't already active
        session_start();
    }
    
    // Include database connection
    include_once '../authentication/config.php';
    
    // Database connection fallback for AJAX
    if (!isset($conn)) {
        try {
            $conn = new mysqli("localhost", "root", "", "theracare");
            if ($conn->connect_error) {
                ob_end_clean(); // Clear any buffered output
                header('Content-Type: application/json');
                die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
            }
        } catch(Exception $e) {
            ob_end_clean(); // Clear any buffered output
            header('Content-Type: application/json');
            die(json_encode(['success' => false, 'message' => "Connection failed: " . $e->getMessage()]));
        }
    }
    
    // Clear any output buffer to ensure headers can be sent
    ob_end_clean();
    
    // Set content type for all AJAX responses
    header('Content-Type: application/json');
    
    // Verify user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Authentication required']);
        exit;
    }
    
    // Handle different AJAX actions
    $action = $_POST['ajax_action'];
    
    if ($action === 'update_status' && isset($_POST['appointment_id']) && isset($_POST['status'])) {
        // Get valid statuses from database
        $status_query = "SHOW COLUMNS FROM appointments LIKE 'status'";
        $status_result = $conn->query($status_query);
        $valid_statuses = [];
        
        if ($status_result && $status_result->num_rows > 0) {
            $row = $status_result->fetch_assoc();
            if (isset($row['Type'])) {
                // If it's an enum, parse the values
                if (preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
                    $valid_statuses = explode("','", $matches[1]);
                }
            }
        }
        
        // Update appointment status
        $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
        $status = $conn->real_escape_string($_POST['status']);
        
        // Validate status value against database-defined values
        if (!in_array($status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status value']);
            exit;
        }
        
        // Update the appointment status
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Appointment status updated successfully',
                'appointment_id' => $appointment_id,
                'status' => $status
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update appointment status: ' . $conn->error]);
        }
        $stmt->close();
        exit;
    } 
    else if ($action === 'get_statuses' && isset($_POST['appointment_ids'])) {
        // Get current status for multiple appointments
        $ids = $_POST['appointment_ids'];
        
        // Validate and sanitize IDs
        if (!preg_match('/^[0-9,]+$/', $ids)) {
            echo json_encode(['success' => false, 'message' => 'Invalid appointment IDs']);
            exit;
        }
        
        // Convert comma-separated string to array
        $id_array = explode(',', $ids);
        $id_array = array_filter($id_array, 'is_numeric');
        
        if (empty($id_array)) {
            echo json_encode(['success' => false, 'message' => 'No valid appointment IDs provided']);
            exit;
        }
        
        // Build placeholders for prepared statement
        $placeholders = implode(',', array_fill(0, count($id_array), '?'));
        
        // Create the prepared statement
        $query = "SELECT id, status FROM appointments WHERE id IN ($placeholders)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        
        // Create the types string and bind parameters
        $types = str_repeat('i', count($id_array));
        $stmt->bind_param($types, ...$id_array);
        
        // Execute the query
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to execute query: ' . $stmt->error]);
            exit;
        }
        
        // Get the result
        $result = $stmt->get_result();
        
        // Fetch all appointments
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        
        // Return the appointments
        echo json_encode([
            'success' => true,
            'appointments' => $appointments
        ]);
        
        $stmt->close();
        exit;
    }
    else if ($action === 'get_therapists_by_specialization' && isset($_POST['specialization'])) {
        // Get therapists by specialization
        $specialization = $conn->real_escape_string($_POST['specialization']);
        
        // Query to get available therapists with the selected specialization
        $query = "SELECT id, first_name, last_name, specialization, consultation_fee, available_slots 
                  FROM therapists 
                  WHERE status = 'Available' " . 
                  (!empty($specialization) ? "AND specialization = ?" : "") . 
                  " ORDER BY first_name, last_name";
        
        $stmt = $conn->prepare($query);
        
        if (!empty($specialization)) {
            $stmt->bind_param("s", $specialization);
        }
        
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to execute query: ' . $stmt->error]);
            exit;
        }
        
        $result = $stmt->get_result();
        
        // Fetch all therapists
        $therapists = [];
        while ($row = $result->fetch_assoc()) {
            $therapists[] = $row;
        }
        
        // Return the therapists
        echo json_encode([
            'success' => true,
            'therapists' => $therapists
        ]);
        
        $stmt->close();
        exit;
    }
    
    // If we get here, the action was not recognized
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// NORMAL PAGE PROCESSING STARTS HERE

// Modified session handling to avoid duplicate session_start calls
$session_already_started = false;
if (function_exists('session_status')) {
    // For PHP 5.4.0 and above
    if (session_status() === PHP_SESSION_ACTIVE) {
        $session_already_started = true;
    }
} else {
    // For older PHP versions
    if (session_id() !== '') {
        $session_already_started = true;
    }
}

// Only start session if not already started
if (!$session_already_started) {
    session_start();
}

// Include database connection file
include_once '../authentication/config.php';

// Database connection fallback in case include fails
if (!isset($conn)) {
    try {
        $conn = new mysqli("localhost", "root", "", "theracare");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    } catch(Exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Get patient information from database
$patient_info = null;
$patient_query = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$patient_query->bind_param("i", $patient_id);
$patient_query->execute();
$patient_result = $patient_query->get_result();
if ($patient_result->num_rows > 0) {
    $patient_info = $patient_result->fetch_assoc();
}
$patient_query->close();

// Get system settings if available
$system_settings = [];
$settings_query = "SELECT setting_key, setting_value FROM system_settings";
$settings_result = $conn->query($settings_query);
if ($settings_result && $settings_result->num_rows > 0) {
    while ($row = $settings_result->fetch_assoc()) {
        $system_settings[$row['setting_key']] = $row['setting_value'];
    }
}

// Initialize message variables
$message = "";
$error = "";

// Only get specializations that have available therapists
$specializations = [];
$spec_query = "SELECT DISTINCT specialization FROM therapists WHERE status = 'Available' ORDER BY specialization";
$spec_result = $conn->query($spec_query);
if ($spec_result) {
    while ($row = $spec_result->fetch_assoc()) {
        $specializations[] = $row['specialization'];
    }
}

// If no specializations found from active therapists, fall back to the database definition
if (empty($specializations)) {
    $spec_query = "SHOW COLUMNS FROM therapists LIKE 'specialization'";
    $spec_result = $conn->query($spec_query);
    if ($spec_result && $spec_result->num_rows > 0) {
        $row = $spec_result->fetch_assoc();
        if (isset($row['Type'])) {
            // If it's an enum, parse the values
            if (preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
                $specializations = explode("','", $matches[1]);
            }
        }
    }
}

// Get available visit types from the database definition
$visit_types = [];
$visit_query = "SHOW COLUMNS FROM appointments LIKE 'visit_type'";
$visit_result = $conn->query($visit_query);
if ($visit_result && $visit_result->num_rows > 0) {
    $row = $visit_result->fetch_assoc();
    if (isset($row['Type'])) {
        // If it's an enum, parse the values
        if (preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
            $visit_types = explode("','", $matches[1]);
        }
    }
}

// If no visit types found, use default ones
if (empty($visit_types)) {
    $visit_types = ['Initial Consultation', 'Follow-up', 'Emergency', 'Telehealth'];
}

// Get available appointment status values
$status_types = [];
$status_query = "SHOW COLUMNS FROM appointments LIKE 'status'";
$status_result = $conn->query($status_query);
if ($status_result && $status_result->num_rows > 0) {
    $row = $status_result->fetch_assoc();
    if (isset($row['Type'])) {
        // If it's an enum, parse the values
        if (preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches)) {
            $status_types = explode("','", $matches[1]);
        }
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['ajax_action'])) {
    if (isset($_POST['book_appointment'])) {
        // Validate required fields are present
        if (!isset($_POST['therapist_id']) || !isset($_POST['date']) || !isset($_POST['time']) || !isset($_POST['visit_type'])) {
            $error = "All required fields must be completed.";
        } else {
            // Sanitize input data
            $therapist_id = $conn->real_escape_string($_POST['therapist_id']);
            $date = $conn->real_escape_string($_POST['date']);
            $time = $conn->real_escape_string($_POST['time']);
            $visit_type = $conn->real_escape_string($_POST['visit_type']);
            $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
            
            // Validate therapist ID is numeric
            if (!is_numeric($therapist_id)) {
                $error = "Invalid therapist selection.";
            } else {
                // Validate date format
                $date_obj = DateTime::createFromFormat('Y-m-d', $date);
                $time_obj = DateTime::createFromFormat('H:i', $time);
                
                // Check if therapist exists and is available
                $therapist_check = $conn->prepare("SELECT id, status, available_slots FROM therapists WHERE id = ?");
                if (!$therapist_check) {
                    $error = "Database error: " . $conn->error;
                } else {
                    $therapist_check->bind_param("i", $therapist_id);
                    $therapist_check->execute();
                    $therapist_result = $therapist_check->get_result();
                    
                    if ($therapist_result->num_rows === 0) {
                        $error = "The selected therapist does not exist.";
                    } else {
                        $therapist = $therapist_result->fetch_assoc();
                        $therapist_check->close();
                        
                        // Continue with validation logic
                        // Validate date and time
                        if (!$date_obj || !$time_obj || $date_obj->format('Y-m-d') !== $date) {
                            $error = "Invalid date or time format.";
                        } 
                        // Validate that date is in the future
                        elseif ($date < date('Y-m-d') || ($date == date('Y-m-d') && $time <= date('H:i'))) {
                            $error = "Appointment must be scheduled for a future date and time.";
                        }
                        // Validate business hours (10 AM to 5 PM)
                        elseif ($time_obj->format('H') < 10 || $time_obj->format('H') >= 17) {
                            $error = "Appointments must be scheduled between 10:00 AM and 5:00 PM.";
                        }
                        // Validate therapist availability
                        elseif ($therapist['status'] !== 'Available') {
                            $error = "The selected therapist is not available.";
                        }
                        else {
                            // Check if therapist has another appointment at the same time
                            $check_overlap = $conn->prepare("
                                SELECT id FROM appointments 
                                WHERE therapist_id = ? AND date = ? AND time = ? 
                                AND status NOT IN ('Cancelled', 'Rescheduled')
                            ");
                            $check_overlap->bind_param("iss", $therapist_id, $date, $time);
                            $check_overlap->execute();
                            $check_overlap->store_result();
                            
                            if ($check_overlap->num_rows > 0) {
                                $error = "The therapist already has an appointment at this time. Please select a different time.";
                                $check_overlap->close();
                            } else {
                                $check_overlap->close();
                                
                                // Book new appointment
                                $stmt = $conn->prepare("INSERT INTO appointments (patient_id, therapist_id, date, time, visit_type, notes, status) 
                                                        VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
                                $stmt->bind_param("iissss", $patient_id, $therapist_id, $date, $time, $visit_type, $notes);
                                
                                if ($stmt->execute()) {
                                    $message = "Appointment booked successfully! The therapist will confirm your appointment soon.";
                                    
                                    // Update therapist status if necessary
                                    $check_slots = $conn->prepare("
                                        SELECT COUNT(*) as appointment_count 
                                        FROM appointments a 
                                        WHERE a.therapist_id = ? AND a.date = ? 
                                        AND a.status NOT IN ('Cancelled', 'Rescheduled')
                                    ");
                                    $check_slots->bind_param("is", $therapist_id, $date);
                                    $check_slots->execute();
                                    $slots_result = $check_slots->get_result();
                                    
                                    if ($slots_result->num_rows > 0) {
                                        $slots_row = $slots_result->fetch_assoc();
                                        $appointment_count = $slots_row['appointment_count'];
                                        
                                        // Check if therapist is at capacity based on available slots
                                        if ($therapist['available_slots'] !== null && $appointment_count >= $therapist['available_slots']) {
                                            // Update therapist status to Busy if all slots are filled
                                            $update_status = $conn->prepare("UPDATE therapists SET status = 'Busy' WHERE id = ?");
                                            $update_status->bind_param("i", $therapist_id);
                                            $update_status->execute();
                                            $update_status->close();
                                        }
                                    }
                                    $check_slots->close();
                                } else {
                                    $error = "Failed to book appointment: " . $conn->error;
                                }
                                $stmt->close();
                            }
                        }
                    }
                }
            }
        }
    } elseif (isset($_POST['cancel_appointment'])) {
        // Cancel appointment
        $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
        
        // Check if appointment exists and belongs to this patient
        $check_stmt = $conn->prepare("SELECT id, therapist_id, date FROM appointments WHERE id = ? AND patient_id = ?");
        $check_stmt->bind_param("ii", $appointment_id, $patient_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $appointment = $check_result->fetch_assoc();
            $check_stmt->close();
            
            $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE id = ? AND patient_id = ?");
            $stmt->bind_param("ii", $appointment_id, $patient_id);
            
            if ($stmt->execute()) {
                $message = "Appointment cancelled successfully!";
                
                // Update therapist status back to Available if necessary
                $therapist_id = $appointment['therapist_id'];
                $appointment_date = $appointment['date'];
                
                // Check if there are remaining appointments for this therapist on this date
                $check_remaining = $conn->prepare("
                    SELECT COUNT(*) as remaining 
                    FROM appointments 
                    WHERE therapist_id = ? AND date = ? 
                    AND status NOT IN ('Cancelled', 'Rescheduled')
                ");
                $check_remaining->bind_param("is", $therapist_id, $appointment_date);
                $check_remaining->execute();
                $remaining_result = $check_remaining->get_result();
                $remaining_row = $remaining_result->fetch_assoc();
                $check_remaining->close();
                
                // If no more appointments for this therapist on this date, update status to Available
                if ($remaining_row['remaining'] == 0) {
                    $update_status = $conn->prepare("UPDATE therapists SET status = 'Available' WHERE id = ? AND status = 'Busy'");
                    $update_status->bind_param("i", $therapist_id);
                    $update_status->execute();
                    $update_status->close();
                }
            } else {
                $error = "Failed to cancel appointment: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Invalid appointment or permission denied.";
            $check_stmt->close();
        }
    } elseif (isset($_POST['reschedule_appointment'])) {
        // Reschedule appointment
        $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
        $new_date = $conn->real_escape_string($_POST['new_date']);
        $new_time = $conn->real_escape_string($_POST['new_time']);
        
        // Validate date format
        $date_obj = DateTime::createFromFormat('Y-m-d', $new_date);
        $time_obj = DateTime::createFromFormat('H:i', $new_time);
        
        if (!$date_obj || !$time_obj || $date_obj->format('Y-m-d') !== $new_date) {
            $error = "Invalid date or time format for rescheduling.";
        } 
        // Validate that date is in the future
        elseif ($new_date < date('Y-m-d') || ($new_date == date('Y-m-d') && $new_time <= date('H:i'))) {
            $error = "Reschedule must be for a future date and time.";
        }
        // Validate business hours (10 AM to 5 PM)
        elseif ($time_obj->format('H') < 10 || $time_obj->format('H') >= 17) {
            $error = "Appointments must be scheduled between 10:00 AM and 5:00 PM.";
        }
        else {
            // Get appointment details
            $check_stmt = $conn->prepare("SELECT id, therapist_id FROM appointments WHERE id = ? AND patient_id = ?");
            $check_stmt->bind_param("ii", $appointment_id, $patient_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $appointment = $check_result->fetch_assoc();
                $therapist_id = $appointment['therapist_id'];
                $check_stmt->close();
                
                // Check if therapist has another appointment at the same time
                $check_overlap = $conn->prepare("
                    SELECT id FROM appointments 
                    WHERE therapist_id = ? AND date = ? AND time = ? 
                    AND status NOT IN ('Cancelled', 'Rescheduled')
                    AND id != ?
                ");
                $check_overlap->bind_param("issi", $therapist_id, $new_date, $new_time, $appointment_id);
                $check_overlap->execute();
                $check_overlap->store_result();
                
                if ($check_overlap->num_rows > 0) {
                    $error = "The therapist already has an appointment at this time. Please select a different time.";
                    $check_overlap->close();
                } else {
                    $check_overlap->close();
                    
                    // Update appointment with new date and time
                    $stmt = $conn->prepare("UPDATE appointments SET date = ?, time = ?, status = 'Rescheduled' WHERE id = ? AND patient_id = ?");
                    $stmt->bind_param("ssii", $new_date, $new_time, $appointment_id, $patient_id);
                    
                    if ($stmt->execute()) {
                        $message = "Appointment rescheduled successfully!";
                    } else {
                        $error = "Failed to reschedule appointment: " . $conn->error;
                    }
                    $stmt->close();
                }
            } else {
                $error = "Invalid appointment or permission denied.";
                $check_stmt->close();
            }
        }
    }
}

// Get available therapists with optional specialization filter
$specialization_filter = isset($_GET['specialization']) ? $_GET['specialization'] : '';

$therapists = [];
if (!empty($specialization_filter)) {
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, specialization, consultation_fee, available_slots, status
        FROM therapists 
        WHERE status = 'Available' AND specialization = ?
        ORDER BY first_name, last_name
    ");
    $stmt->bind_param("s", $specialization_filter);
} else {
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, specialization, consultation_fee, available_slots, status
        FROM therapists 
        WHERE status = 'Available'
        ORDER BY first_name, last_name
    ");
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $therapists[] = $row;
}
$stmt->close();

// Get patient's appointments
$appointments = [];
$stmt = $conn->prepare("
    SELECT a.*, t.first_name as therapist_first_name, t.last_name as therapist_last_name,
           t.specialization, t.consultation_fee, t.available_slots
    FROM appointments a 
    JOIN therapists t ON a.therapist_id = t.id 
    WHERE a.patient_id = ? 
    ORDER BY a.date DESC, a.time DESC
");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();

// Create a variable to prevent navbar.php from starting a session again
$_SESSION['session_already_started'] = true;

// Clear any output buffering before including navbar
ob_end_flush();

// Try to include navbar with more fallback options
$navbar_locations = [
    '../pages/navbar.php',
    'pages/navbar.php',
    'navbar.php',
    '../includes/navbar.php',
    'includes/navbar.php'
];

foreach ($navbar_locations as $location) {
    if (file_exists($location)) {
        include_once $location;
        break;
    }
}

// Function to get status color classes
function getStatusClasses($status) {
    $status_colors = [
        'Pending' => 'bg-blue-100 text-blue-800',
        'Scheduled' => 'bg-blue-100 text-blue-800',
        'Completed' => 'bg-teal-100 text-teal-800',
        'Confirmed' => 'bg-teal-100 text-teal-800',
        'Cancelled' => 'bg-red-100 text-red-800',
        'Rescheduled' => 'bg-indigo-100 text-indigo-800'
    ];
    
    return isset($status_colors[trim($status)]) ? $status_colors[trim($status)] : 'bg-slate-100 text-slate-800';
}
?>