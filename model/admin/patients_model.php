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

// Handle AJAX requests
if (isset($_GET['action']) || (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
    $action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'];
    
    header('Content-Type: application/json');
    
    switch ($action) {
        case 'get_patients':
            // Get all patients with therapist information
            $query = "SELECT 
                p.id, 
                p.first_name, 
                p.last_name, 
                p.email, 
                p.phone, 
                p.treatment_needed,
                p.medical_history, 
                (SELECT CONCAT(t.first_name, ' ', t.last_name) 
                    FROM therapists t 
                    JOIN appointments a ON t.id = a.therapist_id 
                    WHERE a.patient_id = p.id 
                    ORDER BY a.date DESC LIMIT 1) AS therapist_name,
                (SELECT t.specialization 
                    FROM therapists t 
                    JOIN appointments a ON t.id = a.therapist_id 
                    WHERE a.patient_id = p.id 
                    ORDER BY a.date DESC LIMIT 1) AS therapist_specialization,
                (SELECT COUNT(*) FROM appointments WHERE patient_id = p.id) AS appointment_count,
                (SELECT COUNT(*) FROM appointments WHERE patient_id = p.id AND status = 'Completed') AS completed_appointments
            FROM patients p
            ORDER BY p.id DESC";
            
            $result = $conn->query($query);
            $patients = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Calculate completion rate
                    $total = intval($row['appointment_count']);
                    $completed = intval($row['completed_appointments']);
                    $row['completion_rate'] = $total > 0 ? round(($completed / $total) * 100) : 0;
                    
                    $patients[] = $row;
                }
            }
            
            echo json_encode(['data' => $patients]);
            break;
            
        case 'get_patient':
            $id = intval($_GET['id']);
            $query = "SELECT * FROM patients WHERE id = $id";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                $patient = $result->fetch_assoc();
                echo json_encode($patient);
            } else {
                echo json_encode(['error' => 'Patient not found']);
            }
            break;
            
        case 'add_patient':
            // Extract form data
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $treatment_needed = $conn->real_escape_string($_POST['treatment_needed']);
            $medical_history = $conn->real_escape_string($_POST['medical_history']);
            $password = $conn->real_escape_string($_POST['password']);
            
            // Check if email already exists
            $checkQuery = "SELECT id FROM users WHERE email = '$email'";
            $checkResult = $conn->query($checkQuery);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                break;
            }
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Insert user account
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $userQuery = "INSERT INTO users (email, password, role, first_name, last_name) 
                             VALUES ('$email', '$hashedPassword', 'patient', '$first_name', '$last_name')";
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error creating user account: " . $conn->error);
                }
                
                $userId = $conn->insert_id;
                
                // Insert patient profile
                $patientQuery = "INSERT INTO patients (id, email, phone, medical_history, treatment_needed, first_name, last_name) 
                               VALUES ($userId, '$email', '$phone', '$medical_history', '$treatment_needed', '$first_name', '$last_name')";
                
                if (!$conn->query($patientQuery)) {
                    throw new Exception("Error creating patient profile: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                echo json_encode(['success' => true, 'message' => 'Patient added successfully']);
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'update_patient':
            // Extract form data
            $id = intval($_POST['id']);
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $treatment_needed = $conn->real_escape_string($_POST['treatment_needed']);
            $medical_history = $conn->real_escape_string($_POST['medical_history']);
            
            // Check if email is being changed and already exists
            $checkQuery = "SELECT id FROM users WHERE email = '$email' AND id != $id";
            $checkResult = $conn->query($checkQuery);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                break;
            }
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Update patient profile
                $patientQuery = "UPDATE patients SET 
                               first_name = '$first_name',
                               last_name = '$last_name',
                               email = '$email',
                               phone = '$phone',
                               treatment_needed = '$treatment_needed',
                               medical_history = '$medical_history'
                               WHERE id = $id";
                
                if (!$conn->query($patientQuery)) {
                    throw new Exception("Error updating patient profile: " . $conn->error);
                }
                
                // Update user account
                $userQuery = "UPDATE users SET 
                             first_name = '$first_name',
                             last_name = '$last_name',
                             email = '$email'
                             WHERE id = $id";
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error updating user account: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                echo json_encode(['success' => true, 'message' => 'Patient updated successfully']);
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        case 'delete_patient':
            $id = intval($_POST['id']);
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Delete patient profile - should cascade to appointments
                $patientQuery = "DELETE FROM patients WHERE id = $id";
                
                if (!$conn->query($patientQuery)) {
                    throw new Exception("Error deleting patient profile: " . $conn->error);
                }
                
                // Delete user account
                $userQuery = "DELETE FROM users WHERE id = $id";
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error deleting user account: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                echo json_encode(['success' => true, 'message' => 'Patient deleted successfully']);
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
    
    exit;
}

// Get dashboard stats for initial page load
$statsQuery = "SELECT 
    (SELECT COUNT(*) FROM patients) AS total_patients,
    (SELECT COUNT(*) FROM patients WHERE treatment_needed = 'Orthopedic Physical Therapy') AS orthopedic_patients,
    (SELECT COUNT(*) FROM appointments) AS total_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Completed') AS completed_appointments";

$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Calculate completion rate
$completionRate = ($stats['total_appointments'] > 0) ? 
    round(($stats['completed_appointments'] / $stats['total_appointments']) * 100) : 0;

// Fetch therapists for dropdown
$therapistsQuery = "SELECT id, first_name, last_name, specialization FROM therapists ORDER BY first_name, last_name";
$therapistsResult = $conn->query($therapistsQuery);
$therapists = [];

if ($therapistsResult && $therapistsResult->num_rows > 0) {
    while ($row = $therapistsResult->fetch_assoc()) {
        $therapists[] = $row;
    }
}
?>