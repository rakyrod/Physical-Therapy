<?php include('header_model.php'); ?>

<header class="sticky top-0 inset-x-0 z-50 w-full bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm transition-all duration-300 lg:ps-64">
  <nav class="mx-auto w-full px-4 sm:px-6 md:px-8 py-3">
    <div class="flex items-center justify-between">
      <!-- Page Title and Description -->
      <div class="flex-1 min-w-0">
        <h1 id="page-title" class="text-xl font-semibold text-slate-800 truncate">
          Dashboard
        </h1>
        <p id="page-description" class="text-sm text-slate-500 mt-1 hidden sm:block">
          Overview of system statistics
        </p>
      </div>

      <!-- Right Side Actions -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Search Toggle (Mobile) -->
        <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <span class="sr-only">Search</span>
        </button>

        <!-- Notification Button -->
        <div class="relative">
          <button id="notification-button" type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-full transition-colors relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            
            <!-- Notification Indicator -->
            <?php if ($notificationCount > 0): ?>
            <span class="absolute top-1.5 right-1.5 flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-xs"><?php echo $notificationCount; ?></span>
            <?php else: ?>
            <span class="absolute top-1.5 right-1.5 hidden h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            <?php endif; ?>
            
            <span class="sr-only">Notifications</span>
          </button>
          
          <!-- Notification Dropdown -->
          <div id="notification-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 hidden z-50">
            <div class="px-4 py-2 border-b border-gray-100">
              <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            </div>
            <div class="max-h-60 overflow-y-auto">
              <?php $notifications = getNotifications(); ?>
              <?php if (count($notifications) > 0): ?>
                <?php foreach ($notifications as $notification): ?>
                  <a href="appointment-details.php?id=<?php echo $notification['id']; ?>" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                    <p class="text-sm text-gray-700"><?php echo htmlspecialchars($notification['text']); ?></p>
                    <p class="text-xs text-gray-500 mt-1">
                      <?php echo date('M d, Y', strtotime($notification['date'])); ?> at 
                      <?php echo date('h:i A', strtotime($notification['time'])); ?>
                    </p>
                  </a>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="px-4 py-3 text-sm text-gray-500">
                  No new notifications
                </div>
              <?php endif; ?>
            </div>
            <?php if (count($notifications) > 0): ?>
            <div class="px-4 py-2 border-t border-gray-100">
              <a href="all-notifications.php" class="text-xs text-blue-500 hover:text-blue-700">View all notifications</a>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative inline-flex">
          <button id="user-dropdown" type="button" class="flex items-center gap-x-2 rounded-full border-2 border-gray-200 p-1 text-slate-700 hover:border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
            <img class="size-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=320&h=320&q=80" alt="User avatar">
            <span class="hidden md:inline-flex font-medium text-sm">
              <?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : (isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'); ?>
            </span>
          </button>
        </div>
      </div>
    </div>
  </nav>
</header>

<script src="header_script.js"></script>