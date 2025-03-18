<?php 
if (isset($_GET['action']) || (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
    $action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'];
    
    header('Content-Type: application/json');
    
    switch ($action) {
        case 'get_admins':
            // Get all admin users
            $query = "SELECT id, email, first_name, last_name, created_at FROM users WHERE role = 'admin'";
            $result = $conn->query($query);
            $admins = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $admins[] = $row;
                }
            }
            
            echo json_encode(['data' => $admins]);
            break;
            
        case 'add_admin':
            // Extract form data
            $first_name = $conn->real_escape_string($_POST['first_name']);
            $last_name = $conn->real_escape_string($_POST['last_name']);
            $email = $conn->real_escape_string($_POST['email']);
            $password = $conn->real_escape_string($_POST['password']);
            
            // Check if email already exists
            $checkQuery = "SELECT id FROM users WHERE email = '$email'";
            $checkResult = $conn->query($checkQuery);
            
            if ($checkResult && $checkResult->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                break;
            }
            
            // Hash password and insert admin user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO users (email, password, role, first_name, last_name) 
                          VALUES ('$email', '$hashedPassword', 'admin', '$first_name', '$last_name')";
            
            if ($conn->query($insertQuery)) {
                echo json_encode(['success' => true, 'message' => 'Admin user added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error adding admin user: ' . $conn->error]);
            }
            break;
            
        case 'delete_admin':
            $id = intval($_POST['id']);
            
            // Only allow deletion if there's more than one admin
            $countQuery = "SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'";
            $countResult = $conn->query($countQuery);
            $adminCount = $countResult->fetch_assoc()['admin_count'];
            
            if ($adminCount <= 1) {
                echo json_encode(['success' => false, 'message' => 'Cannot delete the last admin account']);
                break;
            }
            
            $deleteQuery = "DELETE FROM users WHERE id = $id AND role = 'admin'";
            
            if ($conn->query($deleteQuery)) {
                echo json_encode(['success' => true, 'message' => 'Admin user deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error deleting admin user: ' . $conn->error]);
            }
            break;
            
        case 'update_settings':
            // Extract form data
            $site_name = $conn->real_escape_string($_POST['site_name']);
            $contact_email = $conn->real_escape_string($_POST['contact_email']);
            $appointment_limit = intval($_POST['appointment_limit']);
            $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
            $theme_color = $conn->real_escape_string($_POST['theme_color']);
            
            // Check if settings table exists, create if not
            $checkTableQuery = "SHOW TABLES LIKE 'system_settings'";
            $tableExists = $conn->query($checkTableQuery)->num_rows > 0;
            
            if (!$tableExists) {
                $createTableQuery = "CREATE TABLE system_settings (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    setting_key VARCHAR(100) NOT NULL,
                    setting_value TEXT NOT NULL,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    UNIQUE KEY (setting_key)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
                
                $conn->query($createTableQuery);
            }
            
            // Update or insert settings
            $settings = [
                'site_name' => $site_name,
                'contact_email' => $contact_email,
                'appointment_limit' => $appointment_limit,
                'maintenance_mode' => $maintenance_mode,
                'theme_color' => $theme_color
            ];
            
            foreach ($settings as $key => $value) {
                $insertQuery = "INSERT INTO system_settings (setting_key, setting_value) 
                              VALUES ('$key', '$value')
                              ON DUPLICATE KEY UPDATE setting_value = '$value'";
                
                $conn->query($insertQuery);
            }
            
            echo json_encode(['success' => true, 'message' => 'Settings updated successfully']);
            break;
            
        case 'get_settings':
            // Get all system settings
            $query = "SELECT setting_key, setting_value FROM system_settings";
            $result = $conn->query($query);
            $settings = [];
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
            }
            
            // Set defaults if not yet in database
            $defaults = [
                'site_name' => 'Thera Care',
                'contact_email' => 'admin@theracare.com',
                'appointment_limit' => '15',
                'maintenance_mode' => '0',
                'theme_color' => '#0082cd'
            ];
            
            foreach ($defaults as $key => $value) {
                if (!isset($settings[$key])) {
                    $settings[$key] = $value;
                }
            }
            
            echo json_encode($settings);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
    
    exit;
}
?>