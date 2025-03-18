<?php include('therapists_controller.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Management | Thera Care</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="therapist_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        blue: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd',
                            700: '#0082cd'
                        },
                        indigo: {
                            100: '#b5e4ff',
                            500: '#0082cd',
                            600: '#0082cd'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body class="font-inter bg-gray-50 min-h-screen">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white text-gray-800 flex-shrink-0 border-r border-gray-200">
        <?php include('sidebar.php'); ?>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50 overflow-y-auto">
        <!-- Header with Bottom Border Line -->
        <div class="px-6 py-4 border-b border-gray-200 w-full">
          <div class="flex items-center justify-between">
            <!-- Left Side -->
            <div class="flex-1 min-w-0">
              <h1 class="text-xl font-semibold text-slate-800 truncate">
                Therapist Management
              </h1>
              <p class="text-sm text-slate-500 mt-1">
                Manage therapists, specializations, and appointments
              </p>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center gap-2 sm:gap-3">
              <!-- Notification Button -->
              <div class="relative">
                <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors relative">
                  <i class="fa-solid fa-bell text-sm"></i>
                  <!-- Notification Indicator -->
                  <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                </button>
              </div>
              
              <!-- User Avatar -->
              <div class="relative inline-flex">
                <button id="user-dropdown" type="button" class="flex items-center gap-x-2 rounded-full border-2 border-gray-200 p-1 text-slate-700 hover:border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                  <img class="size-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="User avatar">
                  <span class="hidden md:inline-flex font-medium text-sm">Admin</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats Cards with Dark Navy Theme -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Therapists Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-900/40 text-blue-400">
                            <i class="fa-solid fa-user-md"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Therapists</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalTherapists"><?php echo $stats['total_therapists']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(5, 15); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Available Therapists Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-emerald-900/40 text-emerald-400">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Available Therapists</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="availableTherapists"><?php echo $stats['available_therapists']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(2, 10); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Appointments Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-purple-900/40 text-purple-400">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Total Appointments</p>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-bold text-white" id="totalAppointments"><?php echo $stats['total_appointments']; ?></h3>
                                <span class="text-green-400 text-sm ml-2">
                                    <i class="fas fa-arrow-up"></i> 
                                    <?php echo rand(10, 20); ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cancellation Rate Card -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm text-slate-400">Cancellation Rate</p>
                        <span class="text-white font-medium" id="cancellationRate"><?php echo $cancellationRate; ?>%</span>
                    </div>
                    <div class="w-full bg-[#1e293b] rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: <?php echo $cancellationRate; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Therapist Management Table -->
        <div class="p-4 sm:p-6 md:p-8">
            <div class="bg-[#0f172a] rounded-xl overflow-hidden border border-[#1e293b] mb-8">
                <!-- Header -->
                <div class="p-4">
                    <div class="flex flex-wrap items-center justify-between">
                        <!-- Left side - Title and subtitle -->
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-semibold text-white">
                                    Therapists
                                </h2>
                                <span class="inline-flex items-center justify-center size-6 rounded-md bg-blue-900/50 text-blue-400 text-sm font-medium" id="therapistCount">
                                    <?php echo $stats['total_therapists']; ?>
                                </span>
                            </div>
                            <p class="text-sm text-slate-400 mt-0.5">
                                Manage therapist accounts and specializations
                            </p>
                        </div>
                        
                        <!-- Right side - Actions -->
                        <div class="flex items-center gap-2">
                            <!-- Search input -->
                            <form id="searchForm" method="get" action="" class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3">
                                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" id="searchInput" name="search" value="<?php echo htmlspecialchars($search); ?>" class="py-2 px-3 ps-10 pe-10 block w-full md:w-64 text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search therapists..." autocomplete="off">
                                <div class="absolute inset-y-0 end-0 flex items-center pe-3">
                                    <?php if (!empty($search)): ?>
                                    <a href="?" id="clearSearch" class="text-slate-400 hover:text-white">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="hidden">Search</button>
                            </form>
                            
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="animate-spin hidden">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            
                            <!-- Add Therapist Button -->
                            <button type="button" id="addTherapistBtn" class="py-2 px-4 inline-flex items-center gap-x-1.5 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Therapist
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#1e293b]" id="therapistsTable">
                        <thead>
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    <div class="flex items-center">
                                        Name
                                        <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Specialization
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Contact
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Fee
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Slots
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Progress
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b]">

                          <?php include('therapists_controller2.php'); ?>

                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer">
                    <?php if ($totalPages > 1): ?>
                    <div class="px-4 py-3 flex items-center justify-between border-t border-[#1e293b]">
                        <div class="text-sm text-slate-400">
                            Showing <?php echo min(($page-1)*$limit+1, $totalRecords); ?> to <?php echo min($page*$limit, $totalRecords); ?> of <?php echo $totalRecords; ?> therapists
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="?page=<?php echo max(1, $page - 1); ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>" class="pagination-link px-3 py-1 rounded-md bg-[#1e293b] text-slate-400 hover:bg-[#334155] flex items-center <?php echo ($page <= 1) ? 'opacity-50 pointer-events-none' : ''; ?>">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Prev
                            </a>
                            
                            <?php
                            // Display pagination links
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $startPage + 4);
                            
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                $isActive = $i == $page;
                                $searchParam = !empty($search) ? '&search='.urlencode($search) : '';
                            ?>
                            <a href="?page=<?php echo $i; ?><?php echo $searchParam; ?>" class="pagination-link size-8 flex justify-center items-center rounded-md <?php echo $isActive ? 'bg-blue-600 text-white' : 'bg-[#1e293b] text-slate-400 hover:bg-[#334155]'; ?>">
                                <?php echo $i; ?>
                            </a>
                            <?php } ?>
                            
                            <a href="?page=<?php echo min($totalPages, $page + 1); ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>" class="pagination-link px-3 py-1 rounded-md bg-[#1e293b] text-slate-400 hover:bg-[#334155] flex items-center <?php echo ($page >= $totalPages) ? 'opacity-50 pointer-events-none' : ''; ?>">
                                Next
                                <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Always show pagination container for consistency, even with only one page -->
                    <div class="px-4 py-3 flex items-center justify-between border-t border-[#1e293b]">
                        <div class="text-sm text-slate-400">
                            Showing <?php echo $totalRecords; ?> therapist<?php echo $totalRecords != 1 ? 's' : ''; ?>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 rounded-md bg-[#1e293b] text-slate-500 opacity-50 pointer-events-none flex items-center">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Prev
                            </span>
                            
                            <span class="size-8 flex justify-center items-center rounded-md bg-blue-600 text-white">
                                1
                            </span>
                            
                            <span class="px-3 py-1 rounded-md bg-[#1e293b] text-slate-500 opacity-50 pointer-events-none flex items-center">
                                Next
                                <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Therapist Modal - Updated design to match the images provided -->
<div id="addTherapistModal" class="modal-backdrop">
    <div class="modal max-w-2xl rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Add Therapist
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Create a new therapist account in the system
                    </p>
                </div>
                <button type="button" id="closeAddModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a] overflow-y-auto max-h-[60vh]">
            <form id="addTherapistForm">
                <input type="hidden" name="action" value="add_therapist">
                
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                    <!-- Basic Details -->
                    <div class="sm:col-span-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Basic Information</h4>
                    </div>
                    
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-slate-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-slate-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <!-- Credentials -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Credentials & Contact</h4>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                        <input type="password" name="password" id="password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="6">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-slate-300 mb-1">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="6">
                    </div>

                    <!-- Professional Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Professional Details</h4>
                    </div>

                    <div>
                        <label for="specialization" class="block text-sm font-medium text-slate-300 mb-1">Specialization</label>
                        <select name="specialization" id="specialization" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            <option value="">Select Specialization</option>
                            <?php foreach($specializations as $spec): ?>
                            <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-300 mb-1">Status</label>
                        <select name="status" id="status" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            <option value="Available">Available</option>
                            <option value="Busy">Busy</option>
                        </select>
                    </div>

                    <div>
                        <label for="consultation_fee" class="block text-sm font-medium text-slate-300 mb-1">Consultation Fee (₱)</label>
                        <input type="number" name="consultation_fee" id="consultation_fee" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required min="0" step="0.01">
                    </div>

                    <div>
                        <label for="available_slots" class="block text-sm font-medium text-slate-300 mb-1">Available Slots</label>
                        <input type="number" name="available_slots" id="available_slots" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required min="0">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-1">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="cancelAddBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Cancel
            </button>
            <button type="button" id="saveTherapistBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Add Therapist
            </button>
        </div>
    </div>
</div>

<!-- Edit Therapist Modal - Updated to match the design in image #1 -->
<div id="editTherapistModal" class="modal-backdrop">
    <div class="modal max-w-2xl rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Edit Therapist
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Modify therapist account details
                    </p>
                </div>
                <button type="button" id="closeEditModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a] overflow-y-auto max-h-[60vh]">
            <form id="editTherapistForm">
                <input type="hidden" name="action" value="update_therapist">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                    <!-- Basic Details -->
                    <div class="sm:col-span-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Basic Information</h4>
                    </div>
                    
                    <div>
                        <label for="edit_first_name" class="block text-sm font-medium text-slate-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="edit_first_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_last_name" class="block text-sm font-medium text-slate-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="edit_last_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <!-- Credentials -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Credentials & Contact</h4>
                    </div>

                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-slate-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="edit_phone" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_password" class="block text-sm font-medium text-slate-300 mb-1">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" id="edit_password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    <!-- Professional Details -->
                    <div class="sm:col-span-2 mt-2">
                        <h4 class="text-sm font-medium text-slate-300 mb-3 border-b border-slate-700 pb-1">Professional Details</h4>
                    </div>

                    <div>
                        <label for="edit_specialization" class="block text-sm font-medium text-slate-300 mb-1">Specialization</label>
                        <select name="specialization" id="edit_specialization" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            <option value="">Select Specialization</option>
                            <?php foreach($specializations as $spec): ?>
                            <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-slate-300 mb-1">Status</label>
                        <select name="status" id="edit_status" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                            <option value="Available">Available</option>
                            <option value="Busy">Busy</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_consultation_fee" class="block text-sm font-medium text-slate-300 mb-1">Consultation Fee (₱)</label>
                        <input type="number" name="consultation_fee" id="edit_consultation_fee" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required min="0" step="0.01">
                    </div>

                    <div>
                        <label for="edit_available_slots" class="block text-sm font-medium text-slate-300 mb-1">Available Slots</label>
                        <input type="number" name="available_slots" id="edit_available_slots" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required min="0">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="edit_notes" class="block text-sm font-medium text-slate-300 mb-1">Additional Notes</label>
                        <textarea name="notes" id="edit_notes" rows="3" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="cancelEditBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Cancel
            </button>
            <button type="button" id="updateTherapistBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Update Therapist
            </button>
        </div>
    </div>
</div>

<!-- Delete Therapist Modal - Updated to match the design in image #2 -->
<div id="deleteTherapistModal" class="modal-backdrop">
    <div class="modal max-w-md rounded-xl">
        <div class="p-6 bg-[#0f172a]">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-red-100/10 rounded-full p-4">
                        <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-white mb-2">Delete Therapist</h3>
                <p class="text-sm text-slate-400" id="deleteConfirmText">
                    Are you sure you want to delete this therapist? This action cannot be undone.
                </p>
            </div>
            
            <div class="flex justify-center gap-3 mt-6">
                <button type="button" id="cancelDeleteBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    Delete
                </button>
            </div>
            
            <form id="deleteTherapistForm">
                <input type="hidden" name="action" value="delete_therapist">
                <input type="hidden" name="id" id="delete_id">
            </form>
        </div>
    </div>
</div>

<!-- View Therapist Modal -->
<div id="viewTherapistModal" class="modal-backdrop">
    <div class="modal max-w-2xl rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Therapist Details
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        View complete information about this therapist
                    </p>
                </div>
                <button type="button" id="closeViewModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a] overflow-y-auto max-h-[60vh]">
            <div class="flex items-center mb-6">
                <div id="view_avatar" class="size-20 flex-shrink-0 bg-blue-500 text-white rounded-full flex items-center justify-center text-xl font-medium mr-4">
                    TH
                </div>
                <div>
                    <h4 id="view_name" class="text-xl font-semibold text-white">Therapist Name</h4>
                    <p id="view_specialization" class="text-sm text-slate-400 mt-1">Specialization</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Contact Information -->
                <div class="bg-[#1e293b] rounded-xl p-4">
                    <h5 class="text-sm font-medium text-white mb-3 pb-2 border-b border-slate-700">Contact Information</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400">Email Address</p>
                            <p id="view_email" class="text-sm text-white">email@example.com</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Phone Number</p>
                            <p id="view_phone" class="text-sm text-white">+1 234 567 890</p>
                        </div>
                    </div>
                </div>
                
                <!-- Professional Details -->
                <div class="bg-[#1e293b] rounded-xl p-4">
                    <h5 class="text-sm font-medium text-white mb-3 pb-2 border-b border-slate-700">Professional Details</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400">Consultation Fee</p>
                            <p id="view_fee" class="text-sm text-white">₱120.00</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Available Slots</p>
                            <p id="view_slots" class="text-sm text-white">10</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400">Status</p>
                            <p id="view_status" class="text-sm">
                                <span class="px-2 py-1 inline-flex items-center text-xs rounded-full bg-green-900/30 text-green-400">
                                    <i class="fa-solid fa-check mr-1"></i> Available
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="sm:col-span-2 bg-[#1e293b] rounded-xl p-4">
                    <h5 class="text-sm font-medium text-white mb-3 pb-2 border-b border-slate-700">Additional Information</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400">Notes</p>
                            <p id="view_notes" class="text-sm text-white whitespace-pre-line">No additional information provided.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="closeViewBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Close
            </button>
            <button type="button" id="viewEditBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Edit
            </button>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="toast" class="toast">
    <div class="p-4">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0 mr-3">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1 pr-3">
                <h4 id="toastTitle" class="text-sm font-medium"></h4>
                <p id="toastMessage" class="text-sm text-gray-600 mt-1"></p>
            </div>
            <div class="flex-shrink-0">
                <button type="button" id="closeToastBtn" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="therapists_script.js"></script>

</body>
</html>