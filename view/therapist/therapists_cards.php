<?php include('therapists_cards_model.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheraCare - Physical Therapists</title>
    <!-- You might need to include your CSS files here -->
</head>
<body>
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while($therapist = $result->fetch_assoc()): 
                    // Get therapist details
                    $therapist_id = $therapist['id'];
                    $first_name = $therapist['first_name'] ?? 'Dr.';
                    $last_name = $therapist['last_name'] ?? 'Therapist';
                    $full_name = $first_name . ' ' . $last_name;
                    $specialization = $therapist['specialization'];
                    $status = $therapist['status'] ?? 'Available';
                    $fee = $therapist['consultation_fee'] ?? '1000';
                    $notes = $therapist['notes'] ?? '';
                    $email = $therapist['email'] ?? '';
                    $phone = $therapist['phone_number'] ?? '';
                    
                    // Get color for status
                    $status_color = ($status == 'Available') ? 'green' : 'red';
                    
                    // Get specialization color
                    $spec_color = getSpecializationColor($specialization);
                    
                    // Get reviews data
                    $reviews = getTherapistReviews($therapist_id);
                    $rating = $reviews['rating'];
                    $review_count = $reviews['count'];
                    
                    // Get description
                    $description = getTherapistDescription($specialization);
                    
                    // Get image
                    $image_path = getTherapistImagePath($therapist_id, $first_name, $last_name);
                    
                    // Get education and certifications
                    $education = getTherapistEducation($specialization);
                    
                    // Get review samples
                    $review_samples = getTherapistReviewSamples();
                    
                    // Get specialization tags
                    $tags = getSpecializationTags($specialization);
                    
                    // Store therapist data for JavaScript
                    $therapistsData[$therapist_id] = [
                        'id' => $therapist_id,
                        'full_name' => $full_name,
                        'specialization' => $specialization,
                        'status' => $status,
                        'status_color' => $status_color,
                        'spec_color' => $spec_color,
                        'rating' => $rating,
                        'review_count' => $review_count,
                        'description' => $description,
                        'image_path' => $image_path,
                        'fee' => $fee,
                        'email' => $email,
                        'phone' => $phone,
                        'education' => $education,
                        'reviews' => $review_samples,
                        'tags' => $tags
                    ];
                ?>
                <!-- Doctor Card -->
                <div class="doctor-card bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer hover:-translate-y-2 animate-fade-in border border-slate-100 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?php echo $image_path; ?>" alt="<?php echo $full_name; ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-blue-600/70 opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute top-4 right-4 bg-<?php echo $status_color; ?>-500/10 text-<?php echo $status_color; ?>-600 px-3 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-sm border border-<?php echo $status_color; ?>-500/20">
                            <div class="w-2 h-2 rounded-full bg-<?php echo $status_color; ?>-500 shadow-sm shadow-<?php echo $status_color; ?>-500/50"></div>
                            <?php echo $status; ?>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-xl font-semibold text-slate-900 mb-1.5"><?php echo $full_name; ?></h3>
                        <p class="inline-block bg-<?php echo $spec_color; ?>-100 text-<?php echo $spec_color; ?>-700 px-3.5 py-1 rounded-full text-sm font-semibold mb-3 w-fit">
                            <?php 
                            // Display a shortened version of the specialization
                            $short_spec = str_replace('Physical Therapy', '', $specialization);
                            $short_spec = trim($short_spec) ?: $specialization;
                            echo $short_spec; 
                            ?>
                        </p>
                        <div class="flex items-center gap-1.5 mb-3">
                            <span class="text-amber-400">★★★★★</span>
                            <span class="font-semibold text-slate-900"><?php echo $rating; ?></span>
                            <span class="text-xs text-slate-500">(<?php echo $review_count; ?> reviews)</span>
                        </div>
                        <p class="text-slate-600 mb-5 text-sm leading-relaxed"><?php echo $description; ?></p>
                        <a href="javascript:void(0);" class="open-doctor-modal mt-auto block bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white font-semibold px-5 py-3 rounded-lg text-center text-sm transition-all duration-300 border border-blue-100 hover:shadow-md hover:shadow-blue-600/20 hover:-translate-y-1" data-therapist-id="<?php echo $therapist_id; ?>">View Details</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center p-8">
                    <h3 class="text-xl font-semibold text-slate-900">No therapists found</h3>
                    <p class="text-slate-600 mt-2">Please check back later or contact us for assistance.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Doctor Modal -->
    <div id="doctorModal" class="fixed inset-0 bg-slate-800/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden relative animate-fade-in">
            <!-- Close button -->
            <button id="closeModal" class="absolute top-4 right-4 bg-white/90 hover:bg-slate-100 p-2 rounded-full text-slate-600 hover:text-slate-900 transition-all z-10">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Doctor profile header - Redesigned for better image proportion -->
            <div class="flex flex-col md:flex-row h-auto md:h-64">
                <!-- Doctor image container - More proportional space -->
                <div class="relative w-full md:w-1/3 h-64 overflow-hidden">
                    <img id="modalDoctorImage" src="/api/placeholder/800/400" alt="Doctor profile" class="w-full h-full object-cover object-center">
                </div>
                
                <!-- Doctor info header -->
                <div class="relative w-full md:w-2/3 bg-blue-600 h-auto md:h-64 p-6 text-white flex flex-col justify-end">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-blue-600"></div>
                    <div class="relative z-10">
                        <span id="modalDoctorSpecialty" class="inline-block bg-white/20 px-4 py-2 rounded-full text-sm font-medium mb-3 backdrop-blur-sm border border-white/10">Specialty</span>
                        <h2 id="modalDoctorName" class="text-3xl font-bold mb-1">Doctor Name</h2>
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="text-amber-300">★★★★★</span>
                            <span id="modalDoctorRating" class="font-semibold">0.0</span>
                            <span id="modalDoctorReviews" class="text-sm text-white/80">(0 reviews)</span>
                        </div>
                        <!-- Added status badge to header -->
                        <div id="modalDoctorStatus" class="inline-flex items-center gap-1.5 bg-white/20 px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm border border-white/10">
                            <div class="w-2 h-2 rounded-full"></div>
                            <span>Status</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Doctor details -->
            <div class="p-6 md:p-8 overflow-y-auto max-h-[calc(90vh-16rem)]">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Left column - Info -->
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-semibold text-lg text-slate-900 mb-3">About</h3>
                            <p id="modalDoctorBio" class="text-slate-600 leading-relaxed">Loading doctor information...</p>
                        </div>
                        
                        <!-- Contact Information Section -->
                        <div>
                            <h3 class="font-semibold text-lg text-slate-900 mb-3">Contact Information</h3>
                            <div class="space-y-2">
                                <div id="modalDoctorEmail" class="flex items-center gap-2 text-slate-600">
                                    <i class="fas fa-envelope text-blue-500"></i>
                                    <span>email@example.com</span>
                                </div>
                                <div id="modalDoctorPhone" class="flex items-center gap-2 text-slate-600">
                                    <i class="fas fa-phone text-blue-500"></i>
                                    <span>+1234567890</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-lg text-slate-900 mb-3">Specialization</h3>
                            <div id="modalDoctorTags" class="flex flex-wrap gap-2">
                                <!-- Tags will be added dynamically -->
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-lg text-slate-900 mb-3">Education & Certifications</h3>
                            <ul id="modalDoctorEducation" class="space-y-3 text-slate-600">
                                <!-- Education items will be added dynamically -->
                            </ul>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-200">
                            <h3 class="font-semibold text-lg text-slate-900 mb-4">Patient Reviews</h3>
                            <div id="modalDoctorReviewsList" class="space-y-4">
                                <!-- Reviews will be added dynamically -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right column - Dynamic content based on login status and therapist availability -->
                    <div id="bookingSection" class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-fit">
                        <!-- This content will be dynamically populated based on therapist availability -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for modal functionality -->
   
    <script src="therapists_cards_script.js"></script>
    
    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>