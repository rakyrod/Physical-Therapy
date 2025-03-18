<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Explore specialized physical therapy services designed to enhance mobility, recovery, and overall quality of life.">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="Physical Therapy Specialties">
  <meta property="og:description" content="Discover expert physical therapy services tailored for orthopedic, neurological, and sports rehabilitation.">
  <meta property="og:type" content="website">
  <meta property="og:image" content="URL_TO_IMAGE">
  <meta property="og:url" content="URL_TO_WEBSITE">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Physical Therapy Specialties">
  <meta name="twitter:description" content="Enhance mobility and recovery with specialized therapy services.">
  <meta name="twitter:image" content="URL_TO_IMAGE">
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Added Google Fonts -->
  <link rel="stylesheet" href="treatments_style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  
</head>
<body>
<?php include '../pages/navbar.php'; ?>

<!-- Database Connection and Query -->
<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$dbname = "theracare";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get specializations that have at least one therapist
$query = "SELECT DISTINCT specialization FROM therapists";
$result = $conn->query($query);

$availableSpecializations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $availableSpecializations[] = $row["specialization"];
    }
}

// Array of specialty information (descriptions and images)
$specialtyInfo = [
    "Orthopedic Physical Therapy" => [
        "description" => "Treats musculoskeletal injuries, including fractures, sprains, post-surgical rehabilitation, and conditions like arthritis.",
        "image" => "/images/orthopedic.jpg"
    ],
    "Neurological Physical Therapy" => [
        "description" => "Helps patients with neurological disorders such as stroke, Parkinson's disease, multiple sclerosis, spinal cord injuries, and traumatic brain injuries.",
        "image" => "/images/neurological.jpg"
    ],
    "Pediatric Physical Therapy" => [
        "description" => "Focuses on infants, children, and adolescents with conditions like cerebral palsy, developmental delays, muscular dystrophy, and congenital disorders.",
        "image" => "/images/pediatric.avif"
    ],
    "Geriatric Physical Therapy" => [
        "description" => "Aims to improve mobility, strength, and balance in older adults dealing with conditions like osteoporosis, arthritis, and post-joint replacement rehabilitation.",
        "image" => "/images/geriatric.jpg"
    ],
    "Sports Physical Therapy" => [
        "description" => "Specializes in injury prevention, treatment, and performance enhancement for athletes of all levels, helping them return to peak condition after injuries.",
        "image" => "/images/sports.jpg"
    ],
    "Cardiopulmonary Physical Therapy" => [
        "description" => "Helps patients with heart and lung conditions such as chronic obstructive pulmonary disease (COPD), heart attacks, or post-cardiac surgery recovery.",
        "image" => "/images/cardiopulmonary.jpg"
    ],
    "Vestibular Rehabilitation" => [
        "description" => "Treats balance disorders, dizziness, and vertigo caused by inner ear issues, helping patients regain stability and confidence in daily activities.",
        "image" => "/images/vesti.jpg"
    ],
    "Pelvic Floor Physical Therapy" => [
        "description" => "Addresses disorders of the pelvic floor, including incontinence, pelvic pain, and pelvic organ prolapse in both men and women.",
        "image" => "/images/pelvic.jpg"
    ],
    "Oncologic Physical Therapy" => [
        "description" => "Supports cancer patients in managing pain, fatigue, and mobility issues due to cancer treatments, improving quality of life during and after treatment.",
        "image" => "/images/oncologic.jpg"
    ],
    "Hand Therapy" => [
        "description" => "Specializes in rehabilitation of the hand, wrist, and arm due to injuries or post-surgical recovery, restoring function and dexterity.",
        "image" => "/images/hand.jpg"
    ]
];

// Count available specializations
$availableSpecializationsCount = count($availableSpecializations);
?>

<!-- Our Specialties Header -->
<div class="pt-28 pb-32 px-4 header-gradient">
  <div class="max-w-6xl mx-auto">
    <div class="text-left">
      <!-- Heading with animation -->
      <h2 class="text-white font-bold text-3xl md:text-5xl md:leading-tight tracking-tight">Our Treatments</h2>

      <p class="mt-4 text-neutral-100 max-w-2xl text-lg">
        Explore our specialized therapy services designed to enhance mobility, recovery, and overall quality of life.
      </p>
      
      <?php if ($availableSpecializationsCount > 0): ?>
      <!-- Search Bar --> 
      <div class="relative mt-8 w-full max-w-md">
        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
          <svg class="shrink-0 size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
          </svg>
        </div>
        <input type="text" id="searchInput" placeholder="Search specialties..." class="search-input py-3 ps-12 pe-4 block w-full bg-white border-0 rounded-lg text-sm ring-2 ring-transparent focus:outline-none focus:ring-blue-500 transition-all disabled:opacity-50 disabled:pointer-events-none text-gray-800 shadow-md">
        <button id="clearSearch" class="hidden absolute inset-y-0 end-0 flex items-center z-20 pe-4">
          <svg class="shrink-0 size-5 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="m15 9-6 6" />
            <path d="m9 9 6 6" />
          </svg>
        </button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Specialties Grid Section -->
<div class="px-4 -mt-20 pb-24">
  <div class="max-w-6xl mx-auto">
    <?php if ($availableSpecializationsCount > 0): ?>
    <!-- Grid for specialties -->
    <div id="specialtiesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
      <?php foreach ($availableSpecializations as $specialization): ?>
        <?php if (isset($specialtyInfo[$specialization])): ?>
        <div class="specialty-item">
          <div class="specialty-card bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="h-52 overflow-hidden relative">
              <img src="<?php echo $specialtyInfo[$specialization]['image']; ?>" alt="<?php echo $specialization; ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105" />
              <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <div class="flex-grow">
                <h3 class="font-semibold text-xl mb-3 text-gray-800"><?php echo $specialization; ?></h3>
                <p class="text-gray-600"><?php echo $specialtyInfo[$specialization]['description']; ?></p>
              </div>
              <button class="learn-more-btn font-semibold mt-5 flex items-center text-blue-800 transition-colors">Learn More <span class="ml-2 transition-transform duration-300 group-hover:translate-x-1">→</span></button>
            </div>
          </div>
        </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    
    <!-- No results message with animation -->
    <div id="noResultsContainer" class="mt-8 transition-all duration-300 opacity-0 max-h-0 overflow-hidden"></div>
    
    <?php else: ?>
    <!-- No Specialties Available Message -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col mt-0 md:mt-10">
      <div class="p-8 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="font-bold text-2xl text-gray-700 mb-2">No Treatments Available</h3>
        <p class="text-gray-600 mb-6">Currently, we do not have any therapists registered for specific treatments. Please check back soon or contact us for more information.</p>
        <a href="/contact.php" class="inline-block bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-lg transition-colors">Contact Us</a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Generate JavaScript with dynamic page mappings -->

<script src  = "treatments_script.js"></script>


<?php $conn->close(); ?>
</body>
</html>