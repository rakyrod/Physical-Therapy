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

// Fetch therapists from database
$sql = "SELECT t.id, t.first_name, t.last_name, t.specialization, t.status, 
               t.consultation_fee, t.notes, t.phone_number, t.email
        FROM therapists t 
        ORDER BY t.id";
$result = $conn->query($sql);

// Store therapist data in an array for JavaScript access
$therapistsData = [];

// Function to get image path (use initials if not available)
function getTherapistImagePath($therapist_id, $first_name, $last_name) {    
    // First, normalize names for case-insensitive comparison
    $normalized_first = strtolower(trim($first_name));
    $normalized_last = strtolower(trim($last_name));
    
    // Direct paths for specific people - now using case-insensitive comparison
    if ($normalized_first === 'nicole' && $normalized_last === 'ednilan') {
        return "../images/nicole.png";
    }
    
    if ($normalized_first === 'cj' && $normalized_last === 'juerba') {
        return "../images/cj.png";
    }
    
    // Web path for other images
    $web_path = "../images/";
    
    // For other therapists, check if image exists
    $image_filename = strtolower($first_name) . ".png";
    $full_path = $web_path . $image_filename;
    
    // Check if file exists on server (comment out this check if causing issues)
    // If image doesn't exist, generate SVG with initials
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/theracare/images/' . $image_filename)) {
        // Get first letters of first and last name
        $firstInitial = substr($first_name, 0, 1);
        $lastInitial = substr($last_name, 0, 1);
        $initials = strtoupper($firstInitial . $lastInitial);
        
        // Generate a consistent background color based on name
        $colorIndex = (ord($firstInitial) + ord($lastInitial)) % 5;
        $bgColors = ['6366f1', '8b5cf6', 'ec4899', '14b8a6', 'f97316']; // indigo, purple, pink, teal, orange
        $bgColor = $bgColors[$colorIndex];
        
        // Create data URI for SVG
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 230" width="400" height="230">';
        $svg .= '<rect width="400" height="230" fill="#'.$bgColor.'" />';
        $svg .= '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="80" font-weight="bold" fill="white">'.$initials.'</text>';
        $svg .= '</svg>';
        
        // Return data URI
        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
    
    // Return the web path for the image
    return $full_path;
}
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

// Function to fetch actual reviews and rating from the database
// Note: Currently a placeholder function since your DB doesn't have a reviews table yet
function getTherapistReviews($therapist_id) {
    // Here you would normally query a reviews table
    // For now, we'll return sample data based on the therapist ID
    $ratings = [
        1 => ['rating' => 4.8, 'count' => 124],
        2 => ['rating' => 4.9, 'count' => 187],
        3 => ['rating' => 4.7, 'count' => 93]
    ];
    
    return $ratings[$therapist_id] ?? ['rating' => 4.7, 'count' => rand(50, 200)];
}

// Generate education and certifications for demo purposes
function getTherapistEducation($specialization) {
    $degrees = [
        'Orthopedic Physical Therapy' => ['Doctor of Physical Therapy (DPT), University of Health Sciences', 'Board Certified Orthopedic Clinical Specialist (OCS)', 'Certified in Manual Therapy (CMPT)'],
        'Neurological Physical Therapy' => ['Doctor of Physical Therapy (DPT), State Medical University', 'Neurologic Clinical Specialist (NCS)', 'Certified in Vestibular Rehabilitation'],
        'Pediatric Physical Therapy' => ['Master of Physical Therapy, Children\'s Specialty Institute', 'Pediatric Clinical Specialist (PCS)', 'Certified in Pediatric Developmental Therapy'],
        'Geriatric Physical Therapy' => ['Doctor of Physical Therapy, Elder Care University', 'Geriatric Clinical Specialist (GCS)', 'Certified in Fall Prevention and Balance Training'],
        'Sports Physical Therapy' => ['Doctor of Physical Therapy, Sports Medicine Institute', 'Sports Clinical Specialist (SCS)', 'Certified Strength and Conditioning Specialist (CSCS)'],
        'Cardiopulmonary Physical Therapy' => ['Doctor of Physical Therapy, Cardiac Care University', 'Cardiovascular and Pulmonary Clinical Specialist (CCS)', 'Advanced Cardiac Life Support (ACLS) Certified'],
        'Vestibular Rehabilitation' => ['Doctor of Physical Therapy, Balance Disorders Institute', 'Certified Vestibular Rehabilitation Therapist', 'Advanced Certification in Dizziness Management'],
        'Pelvic Floor Physical Therapy' => ['Doctor of Physical Therapy, Women\'s Health University', 'Pelvic Rehabilitation Practitioner Certification (PRPC)', 'Women\'s Health Clinical Specialist (WCS)'],
        'Oncologic Physical Therapy' => ['Doctor of Physical Therapy, Oncology Center', 'Certified Lymphedema Therapist (CLT)', 'Oncology Clinical Specialist'],
        'Hand Therapy' => ['Master of Physical Therapy, Hand Rehabilitation Institute', 'Certified Hand Therapist (CHT)', 'Advanced Upper Extremity Certification']
    ];
    
    return $degrees[$specialization] ?? ['Doctor of Physical Therapy (DPT)', 'Licensed Physical Therapist', 'Continuing Education in Evidence-Based Practice'];
}

// Generate sample reviews for demo purposes
function getTherapistReviewSamples() {
    $reviews = [
        ['name' => 'John M.', 'rating' => 5, 'date' => '2 months ago', 'content' => 'Excellent therapist! Helped me recover from my knee surgery much faster than expected. Very knowledgeable and supportive throughout the whole process.'],
        ['name' => 'Sarah L.', 'rating' => 5, 'date' => '3 weeks ago', 'content' => 'I was suffering from chronic back pain for years. After just a few sessions, I noticed significant improvement. The therapist really listens and develops a personalized plan.'],
        ['name' => 'Robert K.', 'rating' => 4, 'date' => '1 month ago', 'content' => 'Professional, punctual, and effective. My shoulder mobility has improved dramatically. Highly recommended for sports injuries.']
    ];
    
    return $reviews;
}

// Generate tags for specializations
function getSpecializationTags($specialization) {
    $tags = [
        'Orthopedic Physical Therapy' => ['Joint Pain', 'Post-Surgery Rehab', 'Sports Injuries', 'Arthritis Management'],
        'Neurological Physical Therapy' => ['Stroke Recovery', 'Balance Disorders', 'Parkinson\'s', 'Multiple Sclerosis'],
        'Pediatric Physical Therapy' => ['Developmental Delays', 'Cerebral Palsy', 'Sensory Processing', 'Genetic Disorders'],
        'Geriatric Physical Therapy' => ['Fall Prevention', 'Balance Training', 'Arthritis', 'Osteoporosis'],
        'Sports Physical Therapy' => ['Athletic Injuries', 'Performance Enhancement', 'ACL Rehab', 'Rotator Cuff'],
        'Cardiopulmonary Physical Therapy' => ['Cardiac Rehab', 'COPD', 'Pulmonary Rehabilitation', 'Heart Failure'],
        'Vestibular Rehabilitation' => ['Vertigo', 'BPPV', 'Dizziness', 'Balance Disorders'],
        'Pelvic Floor Physical Therapy' => ['Incontinence', 'Pelvic Pain', 'Pre/Post Natal', 'Core Strengthening'],
        'Oncologic Physical Therapy' => ['Cancer Rehab', 'Lymphedema', 'Fatigue Management', 'Scar Tissue'],
        'Hand Therapy' => ['Carpal Tunnel', 'Hand Injuries', 'Arthritis', 'Post-Surgical Rehab']
    ];
    
    return $tags[$specialization] ?? ['Physical Therapy', 'Rehabilitation', 'Pain Management', 'Mobility'];
}
?>