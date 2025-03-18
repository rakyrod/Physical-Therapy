<?php
// Import required files
include_once "config.php";

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Clear old error/success messages after 5 minutes
if (isset($_SESSION['message_time']) && (time() - $_SESSION['message_time'] > 300)) {
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    unset($_SESSION['message_time']);
}

// ==========================================
// Database Integrity Check
// ==========================================

/**
 * Ensures required tables exist in the database
 */
function ensureDatabaseTables($conn) {
    try {
        // Check if auth_tokens table exists
        $result = $conn->query("SHOW TABLES LIKE 'auth_tokens'");
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $conn->query("CREATE TABLE `auth_tokens` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `token` varchar(255) NOT NULL,
                `expires_at` datetime NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            
            error_log("Created auth_tokens table");
        }
        
        // Check if login_attempts table exists
        $result = $conn->query("SHOW TABLES LIKE 'login_attempts'");
        if ($result->num_rows == 0) {
            // Table doesn't exist, create it
            $conn->query("CREATE TABLE `login_attempts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `ip_address` varchar(50) NOT NULL,
                `success` tinyint(1) NOT NULL DEFAULT 0,
                `attempt_time` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                CONSTRAINT `login_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
            
            error_log("Created login_attempts table");
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Database integrity check failed: " . $e->getMessage());
        return false;
    }
}

// Run the database check
$dbIntegrityStatus = ensureDatabaseTables($conn);

// ==========================================
// Authentication Functions
// ==========================================
// Add this near the top of login.php, before the remember token check
$forceLogin = isset($_GET['force_login']) && $_GET['force_login'] === '1';

// Then modify the token check
if (!$forceLogin) {
    $tokenCheck = checkRememberToken($conn);
    if ($tokenCheck['success']) {
        // User is already authenticated, redirect based on role
        redirectByRole($tokenCheck['role'], $tokenCheck['user_id'], $conn);
        exit();
    }
}

/**
 * Check if a user is already authenticated through remember token
 */
function checkRememberToken($conn) {
    if (!isset($_COOKIE['remember_token']) || !isset($_COOKIE['user_id'])) {
        return ['success' => false];
    }

    $userId = filter_var($_COOKIE['user_id'], FILTER_VALIDATE_INT);
    $token = $_COOKIE['remember_token'];
    
    if (!$userId) {
        return ['success' => false];
    }
    
    try {
        // Find valid token for this user
        $sql = "SELECT * FROM auth_tokens WHERE user_id = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Verify the token
            if (password_verify($token, $row['token'])) {
                // Get user data
                $userSql = "SELECT * FROM users WHERE id = ?";
                $userStmt = $conn->prepare($userSql);
                $userStmt->bind_param("i", $userId);
                $userStmt->execute();
                $userResult = $userStmt->get_result();
                
                if ($userData = $userResult->fetch_assoc()) {
                    // Set session variables - using first_name and last_name from database
                    $_SESSION['user_id'] = $userData['id'];
                    
                    // Handle either full_name field or first_name/last_name fields
                    if (isset($userData['full_name'])) {
                        $_SESSION['user_name'] = $userData['full_name'];
                    } else {
                        $_SESSION['user_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
                    }
                    
                    $_SESSION['role'] = $userData['role'];
                    
                    // Refresh the token
                    $newToken = bin2hex(random_bytes(32));
                    $hashedNewToken = password_hash($newToken, PASSWORD_DEFAULT);
                    $tokenExpiry = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
                    
                    // Update token in database
                    $updateSql = "UPDATE auth_tokens SET token = ?, expires_at = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("ssi", $hashedNewToken, $tokenExpiry, $row['id']);
                    $updateStmt->execute();
                    
                    // Update cookie
                    setcookie('remember_token', $newToken, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                    
                    return [
                        'success' => true,
                        'role' => $userData['role'],
                        'user_id' => $userData['id']
                    ];
                }
            }
        }
        
        // If we got here, token is invalid - clear cookies
        setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        setcookie('user_id', '', time() - 3600, '/', '', true, true);
        
        return ['success' => false];
    } catch (Exception $e) {
        error_log("Remember token check failed: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Check if too many failed login attempts have been made
 */
function isAccountLocked($conn, $email) {
    try {
        // Get user ID from email
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $userId = $row['id'];
            
            // Check for failed attempts in the last 15 minutes
            $checkSql = "SELECT COUNT(*) as attempts FROM login_attempts 
                        WHERE user_id = ? AND success = 0 AND 
                        attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $userId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $attempts = $checkResult->fetch_assoc()['attempts'];
            
            // Lock account after 5 failed attempts
            return ['locked' => $attempts >= 5, 'attempts' => $attempts];
        }
    } catch (Exception $e) {
        error_log("Failed to check account lock status: " . $e->getMessage());
        return ['locked' => false, 'error' => $e->getMessage()];
    }
    
    return ['locked' => false, 'attempts' => 0];
}

/**
 * Set a message in session
 */
function setMessage($type, $message) {
    $_SESSION[$type] = $message;
    $_SESSION['message_time'] = time();
}

/**
 * Validate actual user role by checking presence in role-specific tables
 * This ensures roles are consistent between users table and role tables
 */
function validateUserRole($userId, $role, $conn) {
    // If the role is already admin, no need to check further
    if ($role === 'admin') {
        return 'admin';
    }
    
    // Check if user exists in therapists table
    $stmt = $conn->prepare("SELECT id FROM therapists WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $isTherapist = $result->num_rows > 0;
    $stmt->close();
    
    // Check if user exists in patients table
    $stmt = $conn->prepare("SELECT id FROM patients WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $isPatient = $result->num_rows > 0;
    $stmt->close();
    
    // Prioritize therapist role if user exists in therapists table
    if ($isTherapist) {
        // If role doesn't match, update it in the database
        if ($role !== 'therapist') {
            $stmt = $conn->prepare("UPDATE users SET role = 'therapist' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
            
            // Update session role
            $_SESSION['role'] = 'therapist';
        }
        return 'therapist';
    } 
    // If only in patients table or not in any role-specific table, return patient
    else if ($isPatient || $role === 'patient') {
        return 'patient';
    }
    
    // Default fallback
    return $role;
}

/**
 * Redirect user based on role
 */
function redirectByRole($role, $userId, $conn) {
    // Validate and potentially correct role based on presence in role-specific tables
    $validatedRole = validateUserRole($userId, $role, $conn);
    
    switch($validatedRole) {
        case 'admin':
            header("Location: ../admin/admin.php");
            break;
        case 'therapist':
            header("Location: ../therapist/dashboard.php");
            break;
        case 'patient':
            header("Location: ../patients/dashboard.php");
            break;
        default:
            // Log unexpected role for debugging
            error_log("Unexpected role encountered: " . $validatedRole);
            header("Location: login.php");
    }
    exit();
}

/**
 * Function to attempt login
 */
function attemptLogin($conn, $email, $password, $remember = false) {
    try {
        // Check if account is locked due to too many failed attempts
        $lockStatus = isAccountLocked($conn, $email);
        if ($lockStatus['locked']) {
            return [
                'success' => false, 
                'message' => 'Your account is temporarily locked due to too many failed login attempts. Please try again later or reset your password.',
                'attempts' => $lockStatus['attempts']
            ];
        }
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // Set session variables - using first_name and last_name from database
                $_SESSION['user_id'] = $row['id'];
                
                // Handle either full_name field or first_name/last_name fields
                if (isset($row['full_name'])) {
                    $_SESSION['user_name'] = $row['full_name'];
                } else {
                    $_SESSION['user_name'] = $row['first_name'] . ' ' . $row['last_name'];
                }
                
                $_SESSION['role'] = $row['role'];
                
                // Handle "Remember me" functionality
                if ($remember) {
                    // Generate a secure token
                    $token = bin2hex(random_bytes(32));
                    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                    
                    // Store token in database with user ID and expiration (30 days)
                    $tokenExpiry = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
                    
                    $sql = "INSERT INTO auth_tokens (user_id, token, expires_at) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iss", $row['id'], $hashedToken, $tokenExpiry);
                    $stmt->execute();
                    
                    // Set cookie with token (30 days)
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                    setcookie('user_id', $row['id'], time() + (30 * 24 * 60 * 60), '/', '', true, true);
                }
                
                // Log successful login attempt
                logLoginAttempt($conn, $row['id'], true);
                
                // Validate/correct role based on user tables
                $validatedRole = validateUserRole($row['id'], $row['role'], $conn);
                
                return [
                    'success' => true, 
                    'role' => $validatedRole, 
                    'user_id' => $row['id']
                ];
            } else {
                // Log failed login attempt
                logLoginAttempt($conn, $row['id'], false);
                return ['success' => false, 'message' => 'Invalid email or password.'];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid email or password.'];
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'An error occurred during login. Please try again later.', 'error' => $e->getMessage()];
    }
}

/**
 * Log login attempts for security
 */
function logLoginAttempt($conn, $userId, $success) {
    try {
        $sql = "INSERT INTO login_attempts (user_id, ip_address, success, attempt_time) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $ip = $_SERVER['REMOTE_ADDR'];
        $stmt->bind_param("isi", $userId, $ip, $success);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        error_log("Failed to log login attempt: " . $e->getMessage());
        return false;
    }
}

/**
 * Logout the current user
 */
function logoutUser($conn) {
    try {
        // Clear session
        $_SESSION = array();
        
        // Invalidate session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        // If remember token exists, delete it from database
        if (isset($_COOKIE['remember_token']) && isset($_COOKIE['user_id'])) {
            $userId = filter_var($_COOKIE['user_id'], FILTER_VALIDATE_INT);
            
            if ($userId) {
                $sql = "DELETE FROM auth_tokens WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
            }
            
            // Clear cookies
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
            setcookie('user_id', '', time() - 3600, '/', '', true, true);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Logout error: " . $e->getMessage());
        return false;
    }
}

/**
 * Sanitize user input
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ==========================================
// Main Logic - Form Processing
// ==========================================

// Handle database integrity errors
if (!$dbIntegrityStatus) {
    setMessage('error', 'There was a problem with the database setup. Please contact support.');
}

// Handle errors from database connection (from config.php)
if (isset($conn_error)) {
    setMessage('error', 'Database connection error. Please try again later or contact support.');
}

// Check if user has a valid remember token
$tokenCheck = checkRememberToken($conn);
if ($tokenCheck['success']) {
    // User is already authenticated, redirect based on role
    redirectByRole($tokenCheck['role'], $tokenCheck['user_id'], $conn);
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setMessage('error', 'Security validation failed. Please try again.');
    } else {
        $email = sanitizeInput($_POST['email']);
        $password = trim($_POST['password']); // Don't sanitize password to preserve special chars
        $remember = isset($_POST['remember']) ? true : false;

        // Validate inputs
        if (empty($email) || empty($password)) {
            setMessage('error', 'Please enter both email and password.');
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setMessage('error', 'Please enter a valid email address.');
        } else {
            // Attempt login
            $result = attemptLogin($conn, $email, $password, $remember);
            
            if ($result['success']) {
                // Redirect based on role
                redirectByRole($result['role'], $result['user_id'], $conn);
                exit();
            } else {
                setMessage('error', $result['message']);
            }
        }
    }
}
?>