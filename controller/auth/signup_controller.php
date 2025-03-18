<?php
include_once "config.php"; // Make sure the path is correct
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Security validation failed. Please try again.";
    } else {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address.";
        } elseif (strlen($password) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters long.";
        } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $_SESSION['error'] = "Password must include at least one uppercase letter, one lowercase letter, and one number.";
        } elseif ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
        } elseif (!isset($_POST['terms'])) {
            $_SESSION['error'] = "You must agree to the Terms and Conditions.";
        } else {
            // Proceed with registration
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            try {
                // Check if email already exists
                $check_email = "SELECT id FROM users WHERE email = ?";
                $stmt = $conn->prepare($check_email);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $_SESSION['error'] = "Email already registered.";
                    $stmt->close();
                    // Don't continue with insertion, just redirect back
                } else {
                    $stmt->close();

                    // Default role is patient
                    $role = "patient";

                    // Begin transaction for related database operations
                    $conn->begin_transaction();

                    try {
                        // Insert user into database
                        $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);
                        $stmt->execute();
                        
                        // Get the user's ID to create a patient record
                        $user_id = $conn->insert_id;
                        
                        // FIXED: Changed 'specialization' to 'treatment_needed'
                        $patient_sql = "INSERT INTO patients (id, email, first_name, last_name, treatment_needed) VALUES (?, ?, ?, ?, ?)";
                        $patient_stmt = $conn->prepare($patient_sql);
                        
                        // Default treatment needed
                        $treatment_needed = "Orthopedic Physical Therapy"; 
                        
                        $patient_stmt->bind_param("issss", $user_id, $email, $first_name, $last_name, $treatment_needed);
                        $patient_stmt->execute();
                        $patient_stmt->close();
                        
                        // Commit the transaction
                        $conn->commit();
                        
                        $_SESSION['success'] = "Registration successful! Please login.";
                        header("Location: login.php");
                        exit();
                    } catch (mysqli_sql_exception $e) {
                        // Rollback the transaction if any query fails
                        $conn->rollback();
                        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
                    }

                    if (isset($stmt) && $stmt) {
                        $stmt->close();
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
            }
        }
    }
}
// The rest of your HTML code remains the same...
?>