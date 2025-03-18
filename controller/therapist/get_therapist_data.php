<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root'; // Change if your DB username is different
$db_pass = ''; // Change if your DB has a password
$db_name = 'theracare';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get therapist ID from request
$therapist_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($therapist_id <= 0) {
    // Return error if invalid ID
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid therapist ID']);
    exit;
}

// Fetch therapist from database
$sql = "SELECT id, first_name, last_name, specialization, status, consultation_fee, available_slots, notes 
        FROM therapists 
        WHERE id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Return error if therapist not found
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Therapist not found']);
    exit;
}

$therapist = $result->fetch_assoc();

// Function to get specialization color classes
function getSpecializationColor($specialization) {
    $colors = [
        'Orthopedic Physical Therapy' => 'purple',
        'Neurological Physical Therapy' => 'cyan',
        'Pediatric Physical Therapy' => 'pink',
        'Geriatric Physical Therapy' => 'orange',
        'Sports Physical Therapy' => 'green',
        'Cardiopulmonary Physical Therapy' => 'blue',
        'Vestibular Rehabilitation' => 'indigo',
        'Pelvic Floor Physical Therapy' => 'rose',
        'Oncologic Physical Therapy' => 'amber',
        'Hand Therapy' => 'teal'
    ];
    
    return $colors[$specialization] ?? 'blue';
}

// Function to get therapist description based on specialization
function getTherapistDescription($specialization) {
    $descriptions = [
        'Orthopedic Physical Therapy' => 'Expert in sports injuries, joint replacements, and post-surgical rehabilitation with advanced certifications in manual therapy.',
        'Neurological Physical Therapy' => 'Specializes in stroke recovery, balance disorders, and neuro rehabilitation with comprehensive approach to patient care.',
        'Pediatric Physical Therapy' => 'Dedicated to helping children with developmental delays, genetic disorders, and injuries achieve their full potential.',
        'Geriatric Physical Therapy' => 'Dedicated to improving mobility and quality of life in older adults, with expertise in fall prevention and aging-related conditions.',
        'Sports Physical Therapy' => 'Specializes in treating athletes of all levels, focusing on injury prevention, performance enhancement, and return-to-sport rehab.',
        'Cardiopulmonary Physical Therapy' => 'Specialized in pulmonary rehabilitation and cardiac care with over 15 years of experience treating heart and lung conditions.',
        'Vestibular Rehabilitation' => 'Expert in treating vertigo, dizziness, and balance disorders with evidence-based vestibular rehabilitation techniques.',
        'Pelvic Floor Physical Therapy' => 'Specializes in pelvic health conditions, providing compassionate care for both men and women with pelvic dysfunction.',
        'Oncologic Physical Therapy' => 'Dedicated to helping cancer patients improve function, reduce pain, and enhance quality of life during and after treatment.',
        'Hand Therapy' => 'Certified hand specialist with extensive experience treating complex hand injuries, arthritis, and post-surgical rehabilitation.'
    ];
    
    return $descriptions[$specialization] ?? 'Experienced physical therapist dedicated to patient-centered care with a focus on evidence-based treatment approaches.';
}

// Get therapist reviews data (placeholder)
function getTherapistReviews($therapist_id) {
    // In a real implementation, you would query a reviews table
    // For now, we'll return sample data based on the therapist ID
    $ratings = [
        1 => ['rating' => 4.8, 'count' => 124],
        2 => ['rating' => 4.9, 'count' => 187],
        3 => ['rating' => 4.7, 'count' => 93],
        4 => ['rating' => 4.6, 'count' => 72],
        5 => ['rating' => 4.5, 'count' => 105],
        6 => ['rating' => 4.8, 'count' => 89],
        7 => ['rating' => 4.9, 'count' => 112],
        8 => ['rating' => 4.6, 'count' => 108],
        9 => ['rating' => 4.7, 'count' => 95],
        10 => ['rating' => 4.8, 'count' => 134],
    ];
    
    return $ratings[$therapist_id] ?? ['rating' => 4.5, 'count' => 100];
}

// Function to get image path
function getTherapistImagePath($therapist_id, $first_name, $last_name) {
    // Check if image exists in your images directory
    $imagePath = "../images/" . strtolower($first_name) . ".png";
    
    // Use placeholder if image doesn't exist
    if (!file_exists($imagePath)) {
        return "/api/placeholder/400/230";
    }
    
    return $imagePath;
}

// Prepare therapist data for response
$first_name = $therapist['first_name'] ?? 'Dr.';
$last_name = $therapist['last_name'] ?? 'Therapist';
$full_name = $first_name . ' ' . $last_name;
$specialization = $therapist['specialization'];
$status = $therapist['status'] ?? 'Available';
$status_color = ($status == 'Available') ? 'green' : 'red';
$spec_color = getSpecializationColor($specialization);
$reviews = getTherapistReviews($therapist_id);
$description = getTherapistDescription($specialization);
$image_path = getTherapistImagePath($therapist_id, $first_name, $last_name);
$fee = $therapist['consultation_fee'] ?? '500';
$available_slots = $therapist['available_slots'] ?? '10';

// Build response
$response = [
    'id' => $therapist_id,
    'full_name' => $full_name,
    'specialization' => $specialization,
    'status' => $status,
    'status_color' => $status_color,
    'spec_color' => $spec_color,
    'rating' => $reviews['rating'],
    'review_count' => $reviews['count'],
    'description' => $description,
    'image_path' => $image_path,
    'fee' => $fee,
    'available_slots' => $available_slots
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close database connection
$conn->close();
?>