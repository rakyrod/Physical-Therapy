<?php
/**
 * Thera Care - Appointment Management System
 * 
 * This file handles the display and management of appointments through a calendar interface.
 * It processes AJAX requests for appointment creation, updating, and status changes.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if a therapist has available slots
 * 
 * @param mysqli $conn Database connection
 * @param int $therapist_id Therapist ID
 * @param int $appointment_id Current appointment ID (for updates, 0 for new appointments)
 * @return array [success, message]
 */
function checkTherapistAvailability($conn, $therapist_id, $appointment_id = 0) {
    // Get therapist's total available slots
    $query = "SELECT available_slots FROM therapists WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $therapist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return [false, "Therapist not found"];
    }
    
    $therapist = $result->fetch_assoc();
    $available_slots = $therapist['available_slots'];
    
    // Get count of therapist's active appointments
    // Exclude the current appointment if we're updating an existing one
    $query = "SELECT COUNT(*) as appointment_count FROM appointments 
              WHERE therapist_id = ? 
              AND status IN ('Pending', 'Scheduled', 'Rescheduled')";
    
    if ($appointment_id > 0) {
        $query .= " AND id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $therapist_id, $appointment_id);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $therapist_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $appointment_count = $row['appointment_count'];
    
    // Check if therapist has available slots
    if ($appointment_count >= $available_slots) {
        return [false, "This therapist has no available slots. Please select another therapist or try a different date."];
    }
    
    return [true, "Therapist has available slots"];
}

/**
 * Check if a specific time slot is available for a therapist on a given date
 * 
 * @param mysqli $conn Database connection
 * @param int $therapist_id Therapist ID
 * @param string $date Appointment date (YYYY-MM-DD)
 * @param string $time Appointment time (HH:MM:SS)
 * @param int $appointment_id Current appointment ID (for updates, 0 for new appointments)
 * @return array [success, message]
 */
function checkTimeSlotAvailability($conn, $therapist_id, $date, $time, $appointment_id = 0) {
    // Check for overlapping appointments
    $query = "SELECT id FROM appointments 
              WHERE therapist_id = ? 
              AND date = ? 
              AND time = ?
              AND status IN ('Pending', 'Scheduled', 'Rescheduled')";
    
    // Exclude the current appointment if we're updating an existing one
    if ($appointment_id > 0) {
        $query .= " AND id != ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issi", $therapist_id, $date, $time, $appointment_id);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $therapist_id, $date, $time);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return [false, "This time slot is already booked. Please select another time."];
    }
    
    return [true, "Time slot is available"];
}

/**
 * Validate appointment availability
 * 
 * @param mysqli $conn Database connection
 * @param int $therapist_id Therapist ID
 * @param string $date Appointment date (YYYY-MM-DD)
 * @param string $time Appointment time (HH:MM:SS)
 * @param int $appointment_id Current appointment ID (for updates, 0 for new appointments)
 * @return array [success, message]
 */
function validateAppointment($conn, $therapist_id, $date, $time, $appointment_id = 0) {
    // First check therapist's overall availability
    list($slotsAvailable, $slotsMessage) = checkTherapistAvailability($conn, $therapist_id, $appointment_id);
    if (!$slotsAvailable) {
        return [false, $slotsMessage];
    }
    
    // Then check specific time slot availability
    list($timeAvailable, $timeMessage) = checkTimeSlotAvailability($conn, $therapist_id, $date, $time, $appointment_id);
    if (!$timeAvailable) {
        return [false, $timeMessage];
    }
    
    return [true, "Appointment slot is available"];
}

// AJAX Handler Section - Must be at the top before any output
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    // Include database connection
    $conn = mysqli_connect("localhost", "root", "", "theracare");
    
    // Check connection
    if (!$conn) {
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => "Connection failed: " . mysqli_connect_error()]));
    }
    
    // Set content type for all AJAX responses
    header('Content-Type: application/json');
    
    // Handle different AJAX actions
    $action = $_POST['ajax_action'];
    
    if ($action === 'update_status' && isset($_POST['appointment_id']) && isset($_POST['status'])) {
        // Update appointment status
        $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
        $status = $conn->real_escape_string($_POST['status']);
        
        // Validate status value
        $valid_statuses = ['Pending', 'Scheduled', 'Completed', 'Cancelled', 'Rescheduled'];
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
    else if ($action === 'check_availability') {
        // Get parameters
        $therapist_id = isset($_POST['therapist_id']) ? intval($_POST['therapist_id']) : 0;
        $appointment_date = isset($_POST['appointment_date']) ? $_POST['appointment_date'] : '';
        $appointment_time = isset($_POST['appointment_time']) ? $_POST['appointment_time'] : '';
        $appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
        
        // Validate parameters
        if (!$therapist_id || !$appointment_date || !$appointment_time) {
            echo json_encode([
                'success' => false, 
                'message' => 'Missing required parameters'
            ]);
            exit;
        }
        
        // Check availability
        list($isAvailable, $message) = validateAppointment(
            $conn, 
            $therapist_id, 
            $appointment_date, 
            $appointment_time, 
            $appointment_id
        );
        
        echo json_encode([
            'success' => $isAvailable,
            'message' => $message
        ]);
        
        exit;
    }
    else if ($action === 'get_appointment_details' && isset($_POST['appointment_id'])) {
        // Get detailed information for a specific appointment
        $appointment_id = $conn->real_escape_string($_POST['appointment_id']);
        
        $query = "SELECT a.*,
        p.first_name as patient_first_name, 
        p.last_name as patient_last_name, 
        p.email as patient_email, 
        p.phone as patient_phone, 
        p.treatment_needed as therapy_specialization,
        t.first_name as therapist_first_name, 
        t.last_name as therapist_last_name, 
        t.specialization as therapist_specialization
 FROM appointments a
 LEFT JOIN patients p ON a.patient_id = p.id
 LEFT JOIN therapists t ON a.therapist_id = t.id
 WHERE a.id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $appointment_id);
        
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch appointment details: ' . $stmt->error]);
            exit;
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Appointment not found']);
            exit;
        }
        
        $appointment = $result->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'appointment' => $appointment
        ]);
        
        $stmt->close();
        exit;
    }
    else if ($action === 'save_appointment') {
        // Get form data
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
        $specialization = $conn->real_escape_string($_POST['specialization']);
        $therapist_id = $conn->real_escape_string($_POST['therapist_id']);
        $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
        $appointment_time = $conn->real_escape_string($_POST['appointment_time']);
        $visit_type = $conn->real_escape_string($_POST['visit_type']);
        $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : null;
        $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : 'Scheduled'; // Default status for new appointments
        
        // Check if this is an edit or new appointment
        $isEdit = isset($_POST['edit_mode']) && $_POST['edit_mode'] === 'true';
        $appointmentId = isset($_POST['edit_appointment_id']) ? intval($_POST['edit_appointment_id']) : null;
        
        // Begin transaction
        $conn->begin_transaction();
        
        try {
            // Validate appointment availability
            list($isAvailable, $validationMessage) = validateAppointment(
                $conn, 
                $therapist_id, 
                $appointment_date, 
                $appointment_time, 
                $appointmentId
            );
            
            if (!$isAvailable) {
                throw new Exception($validationMessage);
            }
            
            // Check if patient exists
            $stmt = $conn->prepare("SELECT id FROM patients WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Patient exists
                $patient = $result->fetch_assoc();
                $patient_id = $patient['id'];
                
                // Update patient info
                $stmt = $conn->prepare("UPDATE patients SET first_name = ?, last_name = ?, phone = ?, treatment_needed = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $first_name, $last_name, $phone, $specialization, $patient_id);
                $stmt->execute();
            } else {
                // Create new patient
                // First, check if user exists with this email
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    // User exists
                    $user = $result->fetch_assoc();
                    $user_id = $user['id'];
                    
                    // Update user info
                    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $first_name, $last_name, $user_id);
                    $stmt->execute();
                } else {
                    // Create new user
                    $password = password_hash(uniqid(), PASSWORD_DEFAULT); // Generate random password
                    $role = 'patient';
                    
                    $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $email, $password, $first_name, $last_name, $role);
                    $stmt->execute();
                    $user_id = $conn->insert_id;
                }
                
                // Create patient record
                $stmt = $conn->prepare("INSERT INTO patients (id, email, first_name, last_name, phone, treatment_needed) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $user_id, $email, $first_name, $last_name, $phone, $specialization);
                $stmt->execute();
                $patient_id = $user_id;
            }
            
            // Now handle the appointment
            if ($isEdit && $appointmentId) {
                // Update existing appointment
                $stmt = $conn->prepare("UPDATE appointments SET patient_id = ?, therapist_id = ?, date = ?, time = ?, visit_type = ?, notes = ?, status = ? WHERE id = ?");
                $stmt->bind_param("iisssssi", $patient_id, $therapist_id, $appointment_date, $appointment_time, $visit_type, $notes, $status, $appointmentId);
                $stmt->execute();
                $appointment_id = $appointmentId;
            } else {
                // Create new appointment
                $stmt = $conn->prepare("INSERT INTO appointments (patient_id, therapist_id, date, time, visit_type, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisssss", $patient_id, $therapist_id, $appointment_date, $appointment_time, $visit_type, $notes, $status);
                $stmt->execute();
                $appointment_id = $conn->insert_id;
            }
            
            // Get therapist name
            $stmt = $conn->prepare("SELECT CONCAT(first_name, ' ', last_name) AS name, specialization FROM therapists WHERE id = ?");
            $stmt->bind_param("i", $therapist_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $therapist = $result->fetch_assoc();
            $therapist_name = $therapist['name'];
            $therapist_specialization = $therapist['specialization'];
            
            // Commit transaction
            $conn->commit();
            
            // Return success response
            echo json_encode([
                'success' => true,
                'message' => $isEdit ? 'Appointment updated successfully' : 'Appointment created successfully',
                'appointment_id' => $appointment_id,
                'patient_name' => "$first_name $last_name",
                'patient_email' => $email,
                'patient_phone' => $phone,
                'therapist_id' => $therapist_id,
                'therapist_name' => $therapist_name,
                'therapist_specialization' => $therapist_specialization,
                'specialization' => $specialization,
                'date' => $appointment_date,
                'time' => $appointment_time,
                'visit_type' => $visit_type,
                'notes' => $notes,
                'status' => $status
            ]);
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        
        exit;
    }
    
    // If we get here, the action was not recognized
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// Set timezone to ensure correct date
date_default_timezone_set('Asia/Manila'); // Philippines timezone

// Get current month and year - force to current date if viewing today's calendar
if (!isset($_GET['month']) && !isset($_GET['year'])) {
    $month = date('n');
    $year = date('Y');
} else {
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
}

// Store current day information for highlighting today
$currentDay = date('j');
$currentMonth = date('n');
$currentYear = date('Y');

// Display today's date in the subtitle
$todayFormatted = date('l, F j, Y');

// Fetch available therapists and their specializations
$conn = mysqli_connect("localhost", "root", "", "theracare");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all available therapists
$therapistQuery = "SELECT id, first_name, last_name, specialization, consultation_fee 
                  FROM therapists 
                  WHERE status = 'Available'
                  ORDER BY specialization, last_name, first_name";
$therapistResult = mysqli_query($conn, $therapistQuery);

// Array to store therapists and their specializations
$therapists = [];
$availableSpecializations = [];

if ($therapistResult && mysqli_num_rows($therapistResult) > 0) {
    while($row = mysqli_fetch_assoc($therapistResult)) {
        $therapists[] = $row;
        
        // Create array of unique specializations that have therapists
        if (!in_array($row['specialization'], $availableSpecializations) && !empty($row['specialization'])) {
            $availableSpecializations[] = $row['specialization'];
        }
    }
}

// Sort specializations alphabetically
sort($availableSpecializations);
?>