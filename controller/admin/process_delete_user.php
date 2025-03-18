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

// Get account ID and type
if (!isset($_POST['account_id']) || empty($_POST['account_id'])) {
    $response['message'] = 'Account ID is required';
    echo json_encode($response);
    exit;
}

$account_id = mysqli_real_escape_string($conn, $_POST['account_id']); // Changed from user_id
$account_type = isset($_POST['account_type']) ? mysqli_real_escape_string($conn, $_POST['account_type']) : '';

try {
    // Start a transaction to ensure all related deletions happen together
    mysqli_begin_transaction($conn);

    // Get user information before deletion
    $user_sql = "SELECT role, first_name, last_name FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);
    $user_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($user_result) == 0) {
        throw new Exception('Account not found');
    }

    $user_row = mysqli_fetch_assoc($user_result);
    $role = $user_row['role'];
    $user_name = $user_row['first_name'] . ' ' . $user_row['last_name'];

    // Check for appointments if user is a patient or therapist
    if ($role == 'patient' || $role == 'therapist') {
        $field = $role == 'patient' ? 'patient_id' : 'therapist_id';
        $check_appointments = "SELECT COUNT(*) as count FROM appointments WHERE $field = ?";
        $stmt = mysqli_prepare($conn, $check_appointments);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        // If there are appointments, delete them
        if ($row['count'] > 0) {
            // Delete related appointments
            $delete_appointments = "DELETE FROM appointments WHERE $field = ?";
            $stmt = mysqli_prepare($conn, $delete_appointments);
            mysqli_stmt_bind_param($stmt, "i", $account_id);
            mysqli_stmt_execute($stmt);
        }
    }

    // Delete from role-specific tables
    if ($role == 'patient') {
        // Delete from patients table
        $delete_patient = "DELETE FROM patients WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_patient);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);
    } else if ($role == 'therapist') {
        // Delete from therapists table
        $delete_therapist = "DELETE FROM therapists WHERE id = ?";
        $stmt = mysqli_prepare($conn, $delete_therapist);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);
    }

    // Delete from auth_tokens table
    $delete_tokens = "DELETE FROM auth_tokens WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_tokens);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);

    // Delete from login_attempts table
    $delete_attempts = "DELETE FROM login_attempts WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_attempts);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);

    // Finally, delete from users table
    $delete_user = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_user);
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);

    // Commit the transaction
    mysqli_commit($conn);
    
    $response['success'] = true;
    $response['message'] = 'Account "' . $user_name . '" deleted successfully';
    
} catch (Exception $e) {
    // If there's an error, roll back the transaction
    mysqli_rollback($conn);
    $response['message'] = 'Error: ' . $e->getMessage();
} finally {
    // Close the database connection
    mysqli_close($conn);
}

// Return the JSON response
echo json_encode($response);
?>