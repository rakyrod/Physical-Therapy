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
$first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$account_type = mysqli_real_escape_string($conn, $_POST['account_type']); // Changed from role to account_type

// Validate inputs
if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($account_type)) {
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit;
}
// Start a transaction to ensure all operations succeed or fail together
mysqli_begin_transaction($conn);
try {
    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($check_result) > 0) {
        throw new Exception('Email already exists');
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into users table with the correct role based on account_type
    $insert_sql = "INSERT INTO users (first_name, last_name, email, password, role, created_at) 
                   VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $hashed_password, $account_type);
    mysqli_stmt_execute($stmt);
    
    $user_id = mysqli_insert_id($conn);
    
    // If account_type is patient, also insert into patients table
    if ($account_type == 'patient') {
        // Get additional patient fields if they exist
        $treatment_needed = isset($_POST['treatment_needed']) ? mysqli_real_escape_string($conn, $_POST['treatment_needed']) : NULL;
        $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : NULL;
        
        $patient_sql = "INSERT INTO patients (id, user_id, email, first_name, last_name, phone, treatment_needed, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $patient_sql);
        mysqli_stmt_bind_param($stmt, "iisssss", $user_id, $user_id, $email, $first_name, $last_name, $phone, $treatment_needed);
        mysqli_stmt_execute($stmt);
    }
    
    // If account_type is therapist, also insert into therapists table
    if ($account_type == 'therapist') {
        // Get additional therapist fields if they exist
        $specialization = isset($_POST['specialization']) ? mysqli_real_escape_string($conn, $_POST['specialization']) : 'Orthopedic Physical Therapy';
        $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : NULL;
        $consultation_fee = isset($_POST['consultation_fee']) ? mysqli_real_escape_string($conn, $_POST['consultation_fee']) : NULL;
        
        $therapist_sql = "INSERT INTO therapists (id, user_id, email, first_name, last_name, specialization, phone_number, consultation_fee, status, created_at, available_slots) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Available', NOW(), 5)";
        $stmt = mysqli_prepare($conn, $therapist_sql);
        mysqli_stmt_bind_param($stmt, "iisssssd", $user_id, $user_id, $email, $first_name, $last_name, $specialization, $phone_number, $consultation_fee);
        mysqli_stmt_execute($stmt);
    }
    
    // If we get here, all operations succeeded
    mysqli_commit($conn);
    
    $response['success'] = true;
    $response['message'] = 'Account added successfully';
    $response['user_id'] = $user_id;
    
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