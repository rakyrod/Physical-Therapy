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

include('admin_settings_controller.php');

// Check if settings table exists and has data
$settingsQuery = "SELECT setting_key, setting_value FROM system_settings";
$settingsExists = false;
$siteSettings = [
    'site_name' => 'Thera Care',
    'contact_email' => 'admin@theracare.com',
    'appointment_limit' => '15',
    'maintenance_mode' => '0',
    'theme_color' => '#0082cd'
];

if ($conn->query("SHOW TABLES LIKE 'system_settings'")->num_rows > 0) {
    $settingsResult = $conn->query($settingsQuery);
    if ($settingsResult && $settingsResult->num_rows > 0) {
        $settingsExists = true;
        while ($row = $settingsResult->fetch_assoc()) {
            $siteSettings[$row['setting_key']] = $row['setting_value'];
        }
    }
}

// Get admin count
$adminCountQuery = "SELECT COUNT(*) AS admin_count FROM users WHERE role = 'admin'";
$adminCountResult = $conn->query($adminCountQuery);
$adminCount = $adminCountResult->fetch_assoc()['admin_count'];
?>