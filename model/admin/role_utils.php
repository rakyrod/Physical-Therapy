<?php
/**
 * TheraCare Role Management Utilities
 * 
 * This file contains functions to help maintain consistency between
 * the users table and role-specific tables (therapists, patients).
 */

/**
 * Validates and potentially corrects a user's role based on their presence in role-specific tables
 * 
 * @param int $userId The user ID to check
 * @param string $currentRole The current role as stored in the users table
 * @param mysqli $conn Database connection
 * @return string The validated (potentially corrected) role
 */
function validateUserRole($userId, $currentRole, $conn) {
    // If the role is already admin, no need to check further
    if ($currentRole === 'admin') {
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
        // If current role doesn't match, update it in the database
        if ($currentRole !== 'therapist') {
            $stmt = $conn->prepare("UPDATE users SET role = 'therapist' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
        }
        return 'therapist';
    } 
    // If only in patients table or their role is patient, return patient
    else if ($isPatient || $currentRole === 'patient') {
        // If they're in patients table but role is not patient, correct it
        if ($isPatient && $currentRole !== 'patient') {
            $stmt = $conn->prepare("UPDATE users SET role = 'patient' WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
        }
        return 'patient';
    }
    
    // Default: return current role if no correction needed
    return $currentRole;
}

/**
 * Ensures proper record exists in role-specific table based on user's role
 * 
 * @param int $userId The user ID to check
 * @param string $role The user's role
 * @param array $userData Array containing 'email', 'first_name', 'last_name'
 * @param mysqli $conn Database connection
 * @return bool True if successful
 */
function ensureRoleTableConsistency($userId, $role, $userData, $conn) {
    if ($role === 'therapist') {
        // Check if therapist record exists
        $stmt = $conn->prepare("SELECT id FROM therapists WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        if (!$exists) {
            // Create therapist record if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO therapists (id, email, first_name, last_name, created_at, specialization, status, available_slots) 
                                   VALUES (?, ?, ?, ?, NOW(), 'Orthopedic Physical Therapy', 'Available', 5)");
            $stmt->bind_param("isss", $userId, $userData['email'], $userData['first_name'], $userData['last_name']);
            $stmt->execute();
            $stmt->close();
        }
    } else if ($role === 'patient') {
        // Check if patient record exists
        $stmt = $conn->prepare("SELECT id FROM patients WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        
        if (!$exists) {
            // Create patient record if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO patients (id, email, first_name, last_name, created_at) 
                                   VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $userId, $userData['email'], $userData['first_name'], $userData['last_name']);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    return true;
}

/**
 * Redirects user based on validated role
 * 
 * @param string $role User role (admin, therapist, patient)
 * @return void
 */
function redirectByRole($role) {
    switch($role) {
        case 'admin':
            header("Location: ../admin/admin.php");
            break;
        case 'therapist':
            header("Location: ../therapist/dashboard.php");
            break;
        case 'patient':
            header("Location: ../patient/bookings.php");
            break;
        default:
            // Log unexpected role for debugging
            error_log("Unexpected role encountered: " . $role);
            header("Location: ../login.php");
    }
    exit();
}
?>