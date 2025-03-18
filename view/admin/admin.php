<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Thera Care</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="admin_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="admin_script.js"></script>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../../favicon.ico">
    
    <!-- SF Pro Display from Apple (CDN) -->
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Theme Check -->


    <script src="admin_script2.js"></script>



    <!-- Custom CSS -->
 
</head>

<body class="font-inter bg-gray-50 dark:bg-white-900 min-h-screen">
    <!-- Toast container for notifications -->
    <div id="toast-container"></div>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- End Sidebar -->
    
    <!-- Header -->
    <?php include 'header.php'; ?>
    <!-- End Header -->
    
    <!-- Main Content -->
    <div class="w-full lg:ps-64">
        <div class="p-4 sm:p-6 md:p-8">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Content Column -->
                <div class="xl:col-span-2">
                    <!-- Cards-->
                    <?php include 'admin_cards.php'; ?>
                    <!-- End of Cards-->
                    
                    <!-- Users Section -->
                    <div class="bg-white ios-card overflow-hidden border border-slate-200 dark:border-slate-700 dark:bg-slate-900 mb-8">
                        <!-- Header -->
                        <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <!-- Left side - Title and subtitle -->
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h2 class="text-lg font-semibold text-slate-800 dark:text-white">
                                            System Users
                                        </h2>
                                        <span id="userCount" class="inline-flex items-center justify-center size-5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-800/30 dark:text-blue-500 text-xs font-medium">
                                            --
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                    View all registered users in the system
                                    </p>
                                </div>
                                
                                <!-- Right side - Actions -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Search input -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3">
                                            <i class="fa-solid fa-search text-slate-400 dark:text-slate-500 text-sm"></i>
                                        </div>
                                        <input type="text" id="searchInput" name="search" class="ios-input ps-9 pe-9 py-2 block w-full md:w-64 text-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300" placeholder="Search users..." autocomplete="off">
                                        <div class="absolute inset-y-0 end-0 flex items-center px-3">
                                            <div id="loadingIndicator" class="animate-spin">
                                                <i class="fa-solid fa-circle-notch text-blue-500 text-sm"></i>
                                            </div>
                                            <button id="clearSearch" class="text-slate-400 hover:text-red-500 dark:hover:text-red-400">
                                                <i class="fa-solid fa-times text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Refresh Button -->
                                    <button type="button" onclick="refreshUserTable()" class="ios-btn py-2 px-3 inline-flex items-center gap-x-1.5 text-sm font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700">
                                        <i id="refreshIcon" class="fa-solid fa-arrows-rotate text-xs"></i>
                                        Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 compact-table">
                                <thead class="bg-slate-50 dark:bg-slate-800">
                                    <tr>
                                        <th scope="col" class="ps-4 pe-3 py-3 text-start">
                                            <div class="flex items-center gap-x-2">
                                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-800 dark:text-slate-200">
                                                    Name
                                                </span>
                                                <i class="fa-solid fa-arrow-up-wide-short text-slate-400 text-xs"></i>
                                            </div>
                                        </th>
                                        
                                        <th scope="col" class="px-3 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-800 dark:text-slate-200">
                                                Email
                                            </span>
                                        </th>
                                        
                                        <th scope="col" class="px-3 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-800 dark:text-slate-200">
                                                Role
                                            </span>
                                        </th>
                                        
                                        <th scope="col" class="px-3 py-3 text-start">
                                            <span class="text-xs font-semibold uppercase tracking-wide text-slate-800 dark:text-slate-200">
                                                Created
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-700" id="userTableBody">

                                <?php include('admin_controller.php'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
    
    <!-- No modal needed as user management is removed -->
    
    <!-- JS Implementing Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/preline/dist/preline.min.js"></script>
   
    <script src="admin_script3.js"></script>

</body>
</html>