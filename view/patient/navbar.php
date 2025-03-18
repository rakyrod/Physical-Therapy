<?php include ("navbar_controller.php") ; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta tags and other head content remain the same -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Professional physical therapy services to help you move better and feel better.">
  <!-- Other meta tags... -->

  <!-- Title -->
  <title>TheraCare - Professional Therapy Services</title>

  <!-- Favicon - Consider adding a real favicon -->
  <link rel="shortcut icon" href="https://preline.co/favicon.ico">

  <!-- Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="navbar_style.css">

  <!-- Enhanced Custom CSS -->
 
</head>

<body class="bg-gray-50">
  <!-- Scroll progress indicator -->
  <div class="scroll-indicator" id="scrollIndicator"></div>

  <!-- Enhanced Navbar with Fixed Glow -->
  <header class="fixed top-0 left-0 right-0 z-50 py-3 px-4 sm:px-6 lg:px-8">
    <!-- Updated navbar container with layered structure for proper glow alignment -->
    <div class="navbar-container mx-auto max-w-7xl">
      <!-- Solid background layer -->
      <div class="navbar-bg"></div>
      
      <!-- Glow effect layer -->
      <div class="navbar-glow"></div>
      
      <!-- Content layer -->
      <div class="navbar-glass px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo on the left - enhanced with animation -->
          <div class="flex-shrink-0 logo-container">
            <a href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; ?>" 
               class="flex items-center logo-hover" 
               title="TheraCare"
               aria-label="TheraCare Home">
              <svg class="w-auto h-14 logo-svg" viewBox="0 0 240 60" xmlns="http://www.w3.org/2000/svg">
                <!-- Animated Bone Shape -->
                <g transform="translate(12, 10)" class="bone-icon">
                  <path d="M20,10 Q14,6 15,0 Q25,2 30,8 L45,23 Q51,28 55,25 Q57,15 50,10 Q56,5 60,15 Q58,25 50,27 Q45,30 40,25 L25,10 Q19,4 15,5 Q13,15 20,10 Z" 
                        fill="#ffffff" stroke="#ffffff" stroke-width="1.5" class="bone-path"/>
                </g>
                
                <!-- Animated TheraCare Text -->
                <text x="75" y="35" font-family="Inter, sans-serif" font-weight="700" font-size="30" fill="#ffffff" class="logo-text">
                  TheraCare
                </text>
                
                <!-- Subtitle with gradient -->
                <text x="75" y="48" font-family="Inter, sans-serif" font-weight="500" font-size="12" fill="#ffffff" class="logo-subtitle">
                  PHYSICAL THERAPY
                </text>
              </svg>
            </a>
          </div>
          
          <!-- Navigation items in the middle - changed from w-3/5 to flex-grow and added justify-center -->
          <div id="navbar-collapse" class="hidden md:flex md:flex-grow md:items-center md:justify-center">
            <div class="flex flex-col gap-y-4 md:flex-row md:items-center md:gap-x-7 md:mt-0">
              <!-- Home link with active state -->
              <a class="nav-item text-sm font-medium text-white hover:text-accent-color md:py-4 focus:outline-none focus:text-accent-color flex items-center <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                 href="../index.php"
                 aria-label="Home">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home
              </a>

              <?php
              if (!isset($_SESSION['user_id'])) {
                  // Show "About Us" when user is not logged in
              ?>
              <a class="nav-item text-sm font-medium text-white hover:text-accent-color md:py-4 focus:outline-none focus:text-accent-color flex items-center <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" 
                 href="#"
                 aria-label="About Us">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M12 16v-4"></path>
                  <path d="M12 8h.01"></path>
                </svg>
                About Us
              </a>
              <?php
              } else {
                  // Show "My Bookings" when user is logged in
              ?>
              <a class="nav-item text-sm font-medium text-white hover:text-accent-color md:py-4 focus:outline-none focus:text-accent-color flex items-center <?php echo ($current_page == 'booking.php') ? 'active' : ''; ?>" 
                 href="../patients/booking.php"
                 aria-label="My Bookings">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                My Bookings
                <?php if (isset($_SESSION['pending_bookings']) && $_SESSION['pending_bookings'] > 0): ?>
                <span class="inline-flex items-center justify-center ml-2 w-5 h-5 text-xs font-semibold rounded-full bg-red-500 text-white"><?php echo $_SESSION['pending_bookings']; ?></span>
                <?php endif; ?>
              </a>
              <?php
              }
              ?>
              
              <?php
              // Get directory depth for treatments link
              $current_path = $_SERVER['PHP_SELF'];
              $path_parts = explode('/', $current_path);
              $depth = count($path_parts) - 2;
              
              // Build path to pages directory
              $pages_path = "";
              for ($i = 0; $i < $depth; $i++) {
                $pages_path .= "../";
              }
              $pages_path .= "pages/treatments.php";
              ?>
              <a class="nav-item text-sm font-medium text-white hover:text-accent-color md:py-4 focus:outline-none focus:text-accent-color flex items-center <?php echo ($current_page == 'treatments.php') ? 'active' : ''; ?>" 
                 href="<?php echo $pages_path; ?>"
                 aria-label="Treatments">
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Treatments
              </a>

              <a class="nav-item text-sm font-medium text-white hover:text-accent-color md:py-4 focus:outline-none focus:text-accent-color flex items-center <?php echo ($current_page == 'therapists.php') ? 'active' : ''; ?>" 
                 href="../therapists/therapists.php"
                 aria-label="Therapists"> 
                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> 
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path> 
                  <circle cx="9" cy="7" r="4"></circle> 
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path> 
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path> 
                </svg> 
                Therapists 
              </a>
              
              <!-- Enhanced dropdown menu -->
              <div class="hs-dropdown [--strategy:static] md:[--strategy:fixed] [--adaptive:none] md:py-4">
                <button type="button" 
                        class="flex items-center w-full text-sm font-medium text-white hover:text-accent-color focus:outline-none focus:text-accent-color group"
                        aria-expanded="false"
                        aria-haspopup="true"
                        id="services-dropdown-button"
                        aria-controls="services-dropdown">
                  <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                    <path d="M9 9h6v6H9z"></path>
                    <path d="m9 1 6 6"></path>
                    <path d="m9 23 6-6"></path>
                    <path d="m1 9 6 6"></path>
                    <path d="m23 9-6 6"></path>
                  </svg>
                  Services
                  <svg class="flex-shrink-0 ms-1 size-4 transition-transform duration-300 ease-in-out group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6" />
                  </svg>
                </button>

                <div id="services-dropdown" class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 dropdown-menu bg-blue-900 shadow-xl rounded-xl p-2 before:absolute top-full before:-top-5 before:start-0 before:w-full before:h-5">
                 
                  <div class="hs-dropdown relative [--strategy:static] md:[--strategy:absolute] [--adaptive:none] md:[--trigger:hover]">
                 

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration-[0.1ms] md:duration-[150ms] hs-dropdown-open:opacity-100 opacity-0 md:w-48 hidden z-10 md:mt-2 dropdown-menu bg-blue-900 shadow-xl rounded-xl p-2 before:absolute before:-end-5 before:top-0 before:h-full before:w-5 top-0 end-full !mx-[10px]">
                      
                    </div>
                  </div>

                  
                 
                </div>
              </div>
            </div>
          </div>
          
          <!-- Right side buttons - modified to use flex-shrink-0 and default to align right -->
          <div class="flex flex-shrink-0 items-center space-x-3">
            <!-- Search button -->
            <button type="button" 
                    class="group inline-flex items-center gap-x-2 py-2.5 px-4 border border-transparent text-sm font-semibold bg-blue-800 text-white rounded-full hover:bg-blue-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transform hover:scale-105" 
                    id="searchToggle" 
                    aria-label="Search">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <span class="hidden sm:inline">Search</span>
            </button>

            <!-- Authentication Button -->
            <?php
            if (!isset($_SESSION['user_id'])) {
                // Show login button if user is not logged in
            ?>
            <a class="group inline-flex items-center gap-x-2 py-2.5 px-4 border border-transparent text-sm font-semibold bg-blue-800 text-white rounded-full hover:bg-blue-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transform hover:scale-105" 
               href="<?php 
                // Get current directory depth
                $current_path = $_SERVER['PHP_SELF'];
                $path_parts = explode('/', $current_path);
                $depth = count($path_parts) - 2;
                
                // Build path to authentication directory
                $auth_path = "";
                for ($i = 0; $i < $depth; $i++) {
                  $auth_path .= "../";
                }
                echo $auth_path . "authentication/signup.php"; 
                ?>"
               aria-label="Sign Up or Login">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
              </svg>
              <span class="hidden sm:inline">Sign Up / Login</span>
            </a>
            <?php
            } else {
                // Show logout option if user is logged in
                // Get current directory depth for logout path
                $current_path = $_SERVER['PHP_SELF'];
                $path_parts = explode('/', $current_path);
                $depth = count($path_parts) - 2;
                
                // Build path to authentication directory
                $auth_path = "";
                for ($i = 0; $i < $depth; $i++) {
                  $auth_path .= "../";
                }
                $logout_path = $auth_path . "authentication/logout.php";
            ?>
            <a href="<?php echo $logout_path; ?>" 
               class="group inline-flex items-center gap-x-2 py-2.5 px-4 border border-transparent text-sm font-semibold bg-blue-800 text-white rounded-full hover:bg-blue-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transform hover:scale-105">
              <span class="hidden sm:inline">Logout</span>
              <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
            </a>
            <?php
            }
            ?>
            
            <!-- Mobile menu toggle button (only visible on small screens) -->
            <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:text-accent-color focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" id="mobile-menu-toggle">
              <span class="sr-only">Open main menu</span>
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Search overlay (hidden by default) -->
    <div id="searchOverlay" class="fixed inset-0 bg-blue-900 bg-opacity-90 z-50 flex items-center justify-center transform -translate-y-full transition-transform duration-300 ease-in-out">
      <button type="button" class="absolute top-4 right-4 text-white hover:text-gray-300" id="closeSearch" aria-label="Close search">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6 6 18" />
          <path d="m6 6 12 12" />
        </svg>
      </button>
      <div class="w-full max-w-3xl px-4">
        <div class="relative">
          <input type="text" class="w-full bg-transparent border-b-2 border-white text-white text-xl py-3 px-4 outline-none focus:border-accent-color placeholder-gray-300" placeholder="Search for treatments, therapists, etc." aria-label="Search input">
          <button type="submit" class="absolute right-4 top-3 text-white hover:text-accent-color" aria-label="Submit search">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </div>
        <div class="mt-8 flex flex-wrap gap-4">
          <p class="text-gray-300 mr-4">Popular searches:</p>
          <a href="#" class="px-3 py-1 bg-blue-800 text-white rounded-full hover:bg-blue-700 transition">Physical Therapy</a>
          <a href="#" class="px-3 py-1 bg-blue-800 text-white rounded-full hover:bg-blue-700 transition">Back Pain</a>
          <a href="#" class="px-3 py-1 bg-blue-800 text-white rounded-full hover:bg-blue-700 transition">Rehabilitation</a>
          <a href="#" class="px-3 py-1 bg-blue-800 text-white rounded-full hover:bg-blue-700 transition">Sports Injury</a>
        </div>
      </div>
    </div>
  </header>

  <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>

  <!-- Enhanced JS INITIALIZATIONS -->
   
   <script src  = "navbar_script.js"></script>


</body>
</html>