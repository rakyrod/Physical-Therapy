<?php
// logout.php
include_once "config.php";
session_start();

// Initialize database connection if not already done in config.php
if (!isset($conn) && file_exists("config.php")) {
    include_once "config.php";
}

// Delete auth tokens from database
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    try {
        $sql = "DELETE FROM auth_tokens WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Error deleting auth tokens: " . $e->getMessage());
    }
}

// Clear session
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Clear remember me cookies
setcookie('remember_token', '', time() - 3600, '/', '', true, true);
setcookie('user_id', '', time() - 3600, '/', '', true, true);

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>