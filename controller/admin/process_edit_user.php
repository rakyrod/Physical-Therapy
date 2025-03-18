<?php
// Set header to respond with JSON
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "theracare");

// Check connection
if (!$conn) {
    $response['message'] = 'Database connection failed: ' . mysqli_connect_error();
    echo json_encode($response);
    exit;
}

// Enable better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Get form data and sanitize inputs
$account_id = mysqli_real_escape_string($conn, $_POST['account_id']); // Changed from user_id
$first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
$account_type = mysqli_real_escape_string($conn, $_POST['account_type']); // Changed from role

// Validate inputs
if (empty($account_id) || empty($first_name) || empty($last_name) || empty($email) || empty($account_type)) {
    $response['message'] = 'Required fields are missing';
    echo json_encode($response);
    exit;
}

// Start a transaction to ensure all operations succeed or fail together
mysqli_begin_transaction($conn);

try {
    // Get current user role
    $current_role_sql = "SELECT role FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $current_role_sql);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);
    $current_role_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($current_role_result) == 0) {
        throw new Exception('User not found');
    }

    $current_role_row = mysqli_fetch_assoc($current_role_result);
    $current_role = $current_role_row['role'];

    // Check if email already exists for a different user
    $check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "si", $email, $account_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        throw new Exception('Email already exists for another user');
    }

    // Start updating user
    $update_fields = "first_name = ?, last_name = ?, email = ?, role = ?";
    $param_types = "ssss";
    $params = array($first_name, $last_name, $email, $account_type);

    // Only update password if it's provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_fields .= ", password = ?";
        $param_types .= "s";
        $params[] = $hashed_password;
    }

    // Add account_id to params
    $param_types .= "i";
    $params[] = $account_id;

    // Update users table
    $update_sql = "UPDATE users SET $update_fields WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    mysqli_stmt_execute($stmt);

    // Now handle role-specific tables

    // 1. Handle therapist table
    if ($account_type == 'therapist' || $current_role == 'therapist') {
        // Check if therapist record exists
        $check_therapist_sql = "SELECT id FROM therapists WHERE id = ?";
        $stmt = mysqli_prepare($conn, $check_therapist_sql);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);
        $check_therapist_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($check_therapist_result) > 0) {
            // Update existing therapist record if they're still a therapist
            if ($account_type == 'therapist') {
                // Get additional therapist fields if they exist
                $specialization = isset($_POST['specialization']) ? mysqli_real_escape_string($conn, $_POST['specialization']) : NULL;
                $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : NULL;
                $consultation_fee = isset($_POST['consultation_fee']) ? mysqli_real_escape_string($conn, $_POST['consultation_fee']) : NULL;
                $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : 'Available';
                
                $therapist_update_sql = "UPDATE therapists SET email = ?, first_name = ?, last_name = ?, 
                                        specialization = COALESCE(?, specialization), 
                                        phone_number = COALESCE(?, phone_number), 
                                        consultation_fee = COALESCE(?, consultation_fee),
                                        status = COALESCE(?, status) 
                                        WHERE id = ?";
                $stmt = mysqli_prepare($conn, $therapist_update_sql);
                mysqli_stmt_bind_param($stmt, "sssssssi", $email, $first_name, $last_name, $specialization, $phone_number, $consultation_fee, $status, $account_id);
                mysqli_stmt_execute($stmt);
            } else {
                // User is no longer a therapist, but we keep the record
                // We could also choose to delete it or mark it inactive
            }
        } else if ($account_type == 'therapist') {
            // Create new therapist record
            $specialization = isset($_POST['specialization']) ? mysqli_real_escape_string($conn, $_POST['specialization']) : 'Orthopedic Physical Therapy';
            $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : NULL;
            $consultation_fee = isset($_POST['consultation_fee']) ? mysqli_real_escape_string($conn, $_POST['consultation_fee']) : NULL;
            
            $therapist_insert_sql = "INSERT INTO therapists (id, user_id, email, first_name, last_name, created_at, specialization, status, available_slots) 
                                    VALUES (?, ?, ?, ?, ?, NOW(), ?, 'Available', 5)";
            $stmt = mysqli_prepare($conn, $therapist_insert_sql);
            mysqli_stmt_bind_param($stmt, "iissss", $account_id, $account_id, $email, $first_name, $last_name, $specialization);
            mysqli_stmt_execute($stmt);
        }
    }
    
    // 2. Handle patient table
    if ($account_type == 'patient' || $current_role == 'patient') {
        // Check if patient record exists
        $check_patient_sql = "SELECT id FROM patients WHERE id = ?";
        $stmt = mysqli_prepare($conn, $check_patient_sql);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);
        $check_patient_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($check_patient_result) > 0) {
            // Update existing patient record if they're still a patient
            if ($account_type == 'patient') {
                // Get additional patient fields if they exist
                $treatment_needed = isset($_POST['treatment_needed']) ? mysqli_real_escape_string($conn, $_POST['treatment_needed']) : NULL;
                $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : NULL;
                
                $patient_update_sql = "UPDATE patients SET email = ?, first_name = ?, last_name = ?,
                                      treatment_needed = COALESCE(?, treatment_needed),
                                      phone = COALESCE(?, phone)
                                      WHERE id = ?";
                $stmt = mysqli_prepare($conn, $patient_update_sql);
                mysqli_stmt_bind_param($stmt, "sssssi", $email, $first_name, $last_name, $treatment_needed, $phone, $account_id);
                mysqli_stmt_execute($stmt);
            } else {
                // User is no longer a patient, but we keep the record
                // We could also choose to delete it or mark it inactive
            }
        } else if ($account_type == 'patient') {
            // Create new patient record
            $treatment_needed = isset($_POST['treatment_needed']) ? mysqli_real_escape_string($conn, $_POST['treatment_needed']) : NULL;
            $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : NULL;
            
            $patient_insert_sql = "INSERT INTO patients (id, user_id, email, first_name, last_name, phone, treatment_needed, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conn, $patient_insert_sql);
            mysqli_stmt_bind_param($stmt, "iisssss", $account_id, $account_id, $email, $first_name, $last_name, $phone, $treatment_needed);
            mysqli_stmt_execute($stmt);
        }
    }

    // If we get here, all operations succeeded
    mysqli_commit($conn);
    $response['success'] = true;
    $response['message'] = 'Account updated successfully';
    
} catch (Exception $e) {
    // An error occurred, rollback the transaction
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    // Close the database connection
    mysqli_close($conn);
}

// Return the JSON response
echo json_encode($response);
?>