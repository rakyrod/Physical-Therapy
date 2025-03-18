<?php
// header.php - Start session and include database connection

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'theracare';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Get count of unread notifications for current user
 * @return int Number of notifications
 */
function getNotificationCount() {
    global $conn;
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        return 0;
    }
    
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];
    
    if ($userRole === 'admin') {
        // Admins see all new appointments and status changes
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE (status = 'Pending' OR status = 'Cancelled' OR status = 'Rescheduled')
                AND DATE(created_at) >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    } 
    elseif ($userRole === 'therapist') {
        // Get therapist ID from email
        $email = $_SESSION['email'] ?? '';
        $sql = "SELECT id FROM therapists WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $therapistId = $row['id'];
            
            // Get upcoming appointments for this therapist
            $sql = "SELECT COUNT(*) as count FROM appointments 
                    WHERE therapist_id = ? 
                    AND (status = 'Pending' OR status = 'Scheduled')
                    AND (DATE(date) = CURRENT_DATE OR DATE(date) = DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY))";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $therapistId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'];
        }
    } 
    elseif ($userRole === 'patient') {
        // Get patient ID from email
        $email = $_SESSION['email'] ?? '';
        $sql = "SELECT id FROM patients WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $patientId = $row['id'];
            
            // Get upcoming appointments for this patient
            $sql = "SELECT COUNT(*) as count FROM appointments 
                    WHERE patient_id = ? 
                    AND (status = 'Scheduled' OR status = 'Rescheduled')
                    AND date >= CURRENT_DATE";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $patientId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'];
        }
    }
    
    return 0;
}

/**
 * Get notifications for current user
 * @return array Notification data
 */
function getNotifications() {
    global $conn;
    $notifications = [];
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        return $notifications;
    }
    
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];
    $limit = 5; // Maximum notifications to show
    
    if ($userRole === 'admin') {
        // Admins see all new appointments, cancellations, etc.
        $sql = "SELECT a.id, a.date, a.time, a.status, a.visit_type, 
                p.first_name as patient_first_name, p.last_name as patient_last_name,
                t.first_name as therapist_first_name, t.last_name as therapist_last_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN therapists t ON a.therapist_id = t.id
                WHERE (a.status = 'Pending' OR a.status = 'Cancelled' OR a.status = 'Rescheduled')
                AND DATE(a.created_at) >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                ORDER BY a.created_at DESC
                LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $text = "";
            if ($row['status'] === 'Pending') {
                $text = "New appointment: {$row['patient_first_name']} {$row['patient_last_name']} with {$row['therapist_first_name']} {$row['therapist_last_name']}";
            } elseif ($row['status'] === 'Cancelled') {
                $text = "Cancelled: {$row['patient_first_name']} {$row['patient_last_name']} with {$row['therapist_first_name']} {$row['therapist_last_name']}";
            } else {
                $text = "Rescheduled: {$row['patient_first_name']} {$row['patient_last_name']} with {$row['therapist_first_name']} {$row['therapist_last_name']}";
            }
            
            $notifications[] = [
                'id' => $row['id'],
                'text' => $text,
                'date' => $row['date'],
                'time' => $row['time']
            ];
        }
    } 
    elseif ($userRole === 'therapist') {
        // Get therapist ID from email
        $email = $_SESSION['email'] ?? '';
        $sql = "SELECT id FROM therapists WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $therapistId = $row['id'];
            
            $sql = "SELECT a.id, a.date, a.time, a.status, a.visit_type,
                    p.first_name as patient_first_name, p.last_name as patient_last_name
                    FROM appointments a
                    JOIN patients p ON a.patient_id = p.id
                    WHERE a.therapist_id = ?
                    AND (a.status = 'Pending' OR a.status = 'Scheduled')
                    AND (DATE(a.date) = CURRENT_DATE OR DATE(a.date) = DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY))
                    ORDER BY a.date, a.time
                    LIMIT ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $therapistId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $text = "";
                if ($row['status'] === 'Pending') {
                    $text = "New request: {$row['patient_first_name']} {$row['patient_last_name']}";
                } else {
                    $text = "Upcoming: {$row['patient_first_name']} {$row['patient_last_name']} on " . date('M d', strtotime($row['date']));
                }
                
                $notifications[] = [
                    'id' => $row['id'],
                    'text' => $text,
                    'date' => $row['date'],
                    'time' => $row['time']
                ];
            }
        }
    } 
    elseif ($userRole === 'patient') {
        // Get patient ID from email
        $email = $_SESSION['email'] ?? '';
        $sql = "SELECT id FROM patients WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $patientId = $row['id'];
            
            $sql = "SELECT a.id, a.date, a.time, a.status, a.visit_type,
                    t.first_name as therapist_first_name, t.last_name as therapist_last_name
                    FROM appointments a
                    JOIN therapists t ON a.therapist_id = t.id
                    WHERE a.patient_id = ?
                    AND (a.status = 'Scheduled' OR a.status = 'Rescheduled')
                    AND a.date >= CURRENT_DATE
                    ORDER BY a.date, a.time
                    LIMIT ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $patientId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $text = "";
                if ($row['status'] === 'Scheduled') {
                    $text = "Confirmed: {$row['therapist_first_name']} {$row['therapist_last_name']} on " . date('M d', strtotime($row['date']));
                } else {
                    $text = "Rescheduled: {$row['therapist_first_name']} {$row['therapist_last_name']} to " . date('M d', strtotime($row['date']));
                }
                
                $notifications[] = [
                    'id' => $row['id'],
                    'text' => $text,
                    'date' => $row['date'],
                    'time' => $row['time']
                ];
            }
        }
    }
    
    return $notifications;
}

// Get notification count
$notificationCount = getNotificationCount();

// Handle marking notifications as read via AJAX
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_read']) && $_POST['mark_read'] === 'true') {
        // In a real implementation, you would update a database table
        // For now, we'll just use a session variable
        $_SESSION['notifications_read_time'] = time();
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}
?>