<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'theracare';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submissions with AJAX responses
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $response = array('success' => false, 'message' => 'Unknown action'); // Initialize response array for AJAX
    
    switch ($action) {
        case 'add_therapist':
            // Extract form data
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $specialization = $conn->real_escape_string($_POST['specialization']);
            $status = $conn->real_escape_string($_POST['status']);
            $consultation_fee = floatval($_POST['consultation_fee']);
            $available_slots = intval($_POST['available_slots']);
            $password = $conn->real_escape_string($_POST['password']);
            $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Check if user/email already exists
                $checkQuery = "SELECT id FROM users WHERE email = '$email'";
                $checkResult = $conn->query($checkQuery);
                
                if ($checkResult->num_rows > 0) {
                    throw new Exception("Email already exists in the system");
                }
                
                // Insert user account
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $userQuery = "INSERT INTO users (email, password, role, first_name, last_name) 
                             VALUES ('$email', '$hashedPassword', 'therapist', '$first_name', '$last_name')";
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error creating user account: " . $conn->error);
                }
                
                $userId = $conn->insert_id;
                
                // Insert therapist profile
                $therapistQuery = "INSERT INTO therapists (id, email, first_name, last_name, specialization, phone_number, 
                                  consultation_fee, status, available_slots, notes) 
                                  VALUES ($userId, '$email', '$first_name', '$last_name', '$specialization', '$phone', 
                                  $consultation_fee, '$status', $available_slots, '$notes')";
                
                if (!$conn->query($therapistQuery)) {
                    throw new Exception("Error creating therapist profile: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                $response['success'] = true;
                $response['message'] = "Therapist added successfully";
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
            
            // Return JSON response for AJAX
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            
            
            
        case 'update_therapist':
            // Extract form data
            $id = intval($_POST['id']);
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
            $specialization = $conn->real_escape_string($_POST['specialization']);
            $status = $conn->real_escape_string($_POST['status']);
            $consultation_fee = floatval($_POST['consultation_fee']);
            $available_slots = intval($_POST['available_slots']);
            $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : '';
            $password = isset($_POST['password']) && !empty($_POST['password']) ? $conn->real_escape_string($_POST['password']) : null;
            
            // Debug information
            error_log("Update therapist - ID: $id");
            error_log("Update therapist - Data: " . json_encode($_POST));
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Check if updated email already exists for another user
                $checkQuery = "SELECT id FROM users WHERE email = '$email' AND id != $id";
                $checkResult = $conn->query($checkQuery);
                
                if ($checkResult->num_rows > 0) {
                    throw new Exception("Email already exists for another user");
                }
                
                // Update therapist profile
                $therapistQuery = "UPDATE therapists SET 
                                  first_name = '$first_name',
                                  last_name = '$last_name',
                                  email = '$email',
                                  phone_number = '$phone',
                                  specialization = '$specialization',
                                  status = '$status',
                                  consultation_fee = $consultation_fee,
                                  available_slots = $available_slots,
                                  notes = '$notes'
                                  WHERE id = $id";
                
                error_log("Update therapist - Query 1: $therapistQuery");
                
                if (!$conn->query($therapistQuery)) {
                    throw new Exception("Error updating therapist profile: " . $conn->error);
                }
                
                // Update user account
                $userQueryBase = "UPDATE users SET 
                                first_name = '$first_name',
                                last_name = '$last_name',
                                email = '$email'";
                
                // Add password update if provided
                if ($password) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $userQueryBase .= ", password = '$hashedPassword'";
                }
                
                $userQuery = $userQueryBase . " WHERE id = $id";
                
                error_log("Update therapist - Query 2: $userQuery");
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error updating user account: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                $response['success'] = true;
                $response['message'] = "Therapist updated successfully";
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                error_log("Update therapist - Exception: " . $e->getMessage());
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
            
            // Return JSON response for AJAX
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            
            
            
        case 'delete_therapist':
            $id = intval($_POST['id']);
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Check if therapist exists
                $checkQuery = "SELECT id FROM therapists WHERE id = $id";
                $checkResult = $conn->query($checkQuery);
                
                if ($checkResult->num_rows === 0) {
                    throw new Exception("Therapist not found");
                }
                
                // Delete therapist profile (should cascade to appointments based on your DB structure)
                $therapistQuery = "DELETE FROM therapists WHERE id = $id";
                
                if (!$conn->query($therapistQuery)) {
                    throw new Exception("Error deleting therapist profile: " . $conn->error);
                }
                
                // Delete user account
                $userQuery = "DELETE FROM users WHERE id = $id";
                
                if (!$conn->query($userQuery)) {
                    throw new Exception("Error deleting user account: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                $response['success'] = true;
                $response['message'] = "Therapist deleted successfully";
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $response['success'] = false;
                $response['message'] = $e->getMessage();
            }
            
            // Return JSON response for AJAX
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            
            
            
        // ADD THIS NEW CASE FOR LIVE SEARCH
        case 'live_search':
            $search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';
            
            // Define the WHERE clause
            $whereClause = '';
            if (!empty($search)) {
                $whereClause = " WHERE 
                    t.first_name LIKE '%$search%' OR 
                    t.last_name LIKE '%$search%' OR 
                    t.email LIKE '%$search%' OR 
                    t.specialization LIKE '%$search%'";
            }
            
            // Get therapists
            $query = "SELECT 
                t.id, 
                t.first_name, 
                t.last_name, 
                t.email, 
                t.phone_number,
                t.specialization, 
                t.consultation_fee,
                t.available_slots,
                t.status,
                t.created_at,
                COUNT(DISTINCT a.id) AS total_appointments,
                COUNT(DISTINCT CASE WHEN a.status = 'Completed' THEN a.id END) AS completed_appointments
                FROM therapists t
                LEFT JOIN appointments a ON t.id = a.therapist_id
                $whereClause
                GROUP BY t.id
                ORDER BY t.created_at DESC";
            
            $result = $conn->query($query);
            $therapists = array();
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $therapists[] = $row;
                }
            }
            
            // Count total for pagination
            $countQuery = "SELECT COUNT(*) as total FROM therapists t $whereClause";
            $countResult = $conn->query($countQuery);
            $totalCount = $countResult->fetch_assoc()['total'];
            
            // Return JSON response
            $response = array(
                'success' => true,
                'therapists' => $therapists,
                'totalCount' => $totalCount
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            
    }
}

// Process GET requests for AJAX data loading
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'get_therapists':
            // Get all therapists for DataTable
            $query = "SELECT 
                t.id, 
                t.first_name, 
                t.last_name, 
                t.email, 
                t.phone_number,
                t.specialization, 
                t.consultation_fee,
                t.available_slots,
                t.status,
                t.notes,
                t.created_at,
                COUNT(DISTINCT a.id) AS total_appointments,
                COUNT(DISTINCT CASE WHEN a.status = 'Completed' THEN a.id END) AS completed_appointments
                FROM therapists t
                LEFT JOIN appointments a ON t.id = a.therapist_id
                GROUP BY t.id
                ORDER BY t.created_at DESC";
                
            $result = $conn->query($query);
            $data = array();
            
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // Calculate completion rate for progress bars
                    $total = (int)$row['total_appointments'];
                    $completed = (int)$row['completed_appointments'];
                    $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                    
                    $row['progress'] = $progress;
                    $data[] = $row;
                }
            }
            
            $response = array('data' => $data);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
            
        case 'get_therapist':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                
                $query = "SELECT * FROM therapists WHERE id = $id";
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0) {
                    $therapist = $result->fetch_assoc();
                    header('Content-Type: application/json');
                    echo json_encode($therapist);
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => "Therapist not found"]);
                }
                exit;
            }
            break;
    }
}


// Get dashboard stats
$statsQuery = "SELECT 
    (SELECT COUNT(*) FROM therapists) AS total_therapists,
    (SELECT COUNT(*) FROM therapists WHERE status = 'Available') AS available_therapists,
    (SELECT COUNT(*) FROM appointments) AS total_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Cancelled') AS cancelled_appointments,
    (SELECT COUNT(*) FROM appointments WHERE status = 'Completed') AS completed_appointments";

$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();

// Calculate cancellation rate
$cancellationRate = ($stats['total_appointments'] > 0) ? 
    round(($stats['cancelled_appointments'] / $stats['total_appointments']) * 100) : 0;
    
// Calculate completion rate
$completionRate = ($stats['total_appointments'] > 0) ? 
    round(($stats['completed_appointments'] / $stats['total_appointments']) * 100) : 0;

// Fetch therapists for the table
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Debug pagination parameters
// error_log("Page: $page, Limit: $limit, Offset: $offset, Search: '$search'");

$whereClause = '';
if (!empty($search)) {
    $whereClause = " WHERE 
        t.first_name LIKE '%$search%' OR 
        t.last_name LIKE '%$search%' OR 
        t.email LIKE '%$search%' OR 
        t.specialization LIKE '%$search%'";
}

$therapistsQuery = "SELECT 
    t.id, 
    t.first_name, 
    t.last_name, 
    t.email, 
    t.phone_number,
    t.specialization, 
    t.consultation_fee,
    t.available_slots,
    t.status,
    t.created_at,
    COUNT(DISTINCT a.id) AS total_appointments,
    COUNT(DISTINCT CASE WHEN a.status = 'Completed' THEN a.id END) AS completed_appointments
    FROM therapists t
    LEFT JOIN appointments a ON t.id = a.therapist_id
    $whereClause
    GROUP BY t.id
    ORDER BY t.created_at DESC
    LIMIT $offset, $limit";
$therapistsResult = $conn->query($therapistsQuery);

// Count total therapists for pagination
$countQuery = "SELECT COUNT(*) as total FROM therapists t $whereClause";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Get specializations for dropdown
$specializationsQuery = "SHOW COLUMNS FROM therapists LIKE 'specialization'";
$specializationsResult = $conn->query($specializationsQuery);
$specializationsRow = $specializationsResult->fetch_assoc();
$enum = $specializationsRow['Type'];
preg_match_all("/'(.*?)'/", $enum, $specializations);
$specializations = $specializations[1];
?>