<?php include('admin_settings_model.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | <?php echo $siteSettings['site_name']; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="admin_settings_style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
  
    <script src="admin_settings_script2.js"></script>
  
</head>
<body class="font-inter bg-gray-50 min-h-screen">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0f172a] text-gray-800 flex-shrink-0 border-r border-gray-200">
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
                System Settings
              </h1>
              <p class="text-sm text-slate-500 mt-1">
                Configure TheraCare system settings and manage administrators
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

        <!-- Quick Stats -->
        <div class="px-4 sm:px-6 py-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- System Status -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-green-900/40 text-green-400">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">System Status</p>
                            <div class="flex items-center">
                                <h3 class="text-lg font-bold text-white">Active</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Users -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-blue-900/40 text-blue-400">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Admin Users</p>
                            <div class="flex items-center">
                                <h3 class="text-lg font-bold text-white"><?php echo $adminCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- PHP Version -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-purple-900/40 text-purple-400">
                            <i class="fab fa-php"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">PHP Version</p>
                            <div class="flex items-center">
                                <h3 class="text-lg font-bold text-white"><?php echo phpversion(); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Server Time -->
                <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center size-8 rounded-full bg-amber-900/40 text-amber-400">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="ml-4">
                            <p class="text-sm text-slate-400">Server Time</p>
                            <div class="flex items-center">
                                <h3 class="text-lg font-bold text-white"><?php echo date('H:i:s'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Tabs Container -->
        <div class="px-4 sm:px-6 pb-6">
            <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                <!-- Left sidebar (Tabs) -->
                <div class="w-full md:w-64 flex-shrink-0">
                    <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b]">
                            <div class="p-4 border-b border-[#1e293b]">
                                <h3 class="font-medium text-white">Settings Navigation</h3>
                            </div>
                            <div class="p-2">
                                <nav class="flex flex-col space-y-1">
                                    <a href="#" class="nav-link active px-4 py-2 rounded-md text-sm text-slate-300 hover:bg-[#1e293b]" data-tab="general">
                                        <div class="flex items-center">
                                            <i class="fas fa-cog w-5 h-5 mr-2"></i>
                                            <span>General Settings</span>
                                        </div>
                                    </a>
                                    <a href="#" class="nav-link px-4 py-2 rounded-md text-sm text-slate-300 hover:bg-[#1e293b]" data-tab="admin-users">
                                        <div class="flex items-center">
                                            <i class="fas fa-user-shield w-5 h-5 mr-2"></i>
                                            <span>Admin Users</span>
                                        </div>
                                    </a>
                                    <a href="#" class="nav-link px-4 py-2 rounded-md text-sm text-slate-300 hover:bg-[#1e293b]" data-tab="appearance">
                                        <div class="flex items-center">
                                            <i class="fas fa-paint-brush w-5 h-5 mr-2"></i>
                                            <span>Appearance</span>
                                        </div>
                                    </a>
                                    <a href="#" class="nav-link px-4 py-2 rounded-md text-sm text-slate-300 hover:bg-[#1e293b]" data-tab="security">
                                        <div class="flex items-center">
                                            <i class="fas fa-shield-alt w-5 h-5 mr-2"></i>
                                            <span>Security</span>
                                        </div>
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center size-12 rounded-full bg-blue-900/20 text-blue-400 mb-3">
                                    <i class="fas fa-info-circle text-xl"></i>
                                </div>
                                <h4 class="text-sm font-medium text-white mb-2">Need Help?</h4>
                                <p class="text-xs text-slate-400 mb-3">
                                    For system support, please contact our technical team.
                                </p>
                                <a href="mailto:support@theracare.com" class="text-xs text-blue-400 hover:text-blue-300 underline">
                                    support@theracare.com
                                </a>
                            </div>
                        </div>
                        
                        <!-- System Information -->
                        <div class="md:mt-4 bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 col-span-2 md:col-span-1">
                            <h4 class="text-sm font-medium text-white mb-3 border-b border-[#1e293b] pb-2">System Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-xs text-slate-400">PHP Memory Limit:</span>
                                    <span class="text-xs text-white"><?php echo ini_get('memory_limit'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-slate-400">MySQL Version:</span>
                                    <span class="text-xs text-white"><?php echo $conn->server_info; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-slate-400">Max Upload Size:</span>
                                    <span class="text-xs text-white"><?php echo ini_get('upload_max_filesize'); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-xs text-slate-400">Server Software:</span>
                                    <span class="text-xs text-white"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-4 col-span-2 md:col-span-1">
                            <h4 class="text-sm font-medium text-white mb-3 border-b border-[#1e293b] pb-2">Quick Actions</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button class="bg-[#1e293b] hover:bg-slate-700 text-white text-xs p-2 rounded flex items-center justify-center">
                                    <i class="fas fa-database mr-1"></i> Backup DB
                                </button>
                                <button class="bg-[#1e293b] hover:bg-slate-700 text-white text-xs p-2 rounded flex items-center justify-center">
                                    <i class="fas fa-broom mr-1"></i> Clear Cache
                                </button>
                                <button class="bg-[#1e293b] hover:bg-slate-700 text-white text-xs p-2 rounded flex items-center justify-center">
                                    <i class="fas fa-sync mr-1"></i> Check Updates
                                </button>
                                <button class="bg-[#1e293b] hover:bg-slate-700 text-white text-xs p-2 rounded flex items-center justify-center">
                                    <i class="fas fa-file-alt mr-1"></i> View Logs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right content (Tab content) -->
                <div class="flex-1">
                    <!-- General Settings Tab -->
                    <div id="general-tab" class="tab-content active">
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-white">General System Settings</h3>
                                <span class="px-2 py-1 bg-green-900/30 text-green-400 text-xs rounded-full flex items-center">
                                    <i class="fas fa-circle text-[8px] mr-1"></i> Active
                                </span>
                            </div>
                            <form id="settingsForm">
                                <input type="hidden" name="action" value="update_settings">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Site Name -->
                                    <div>
                                        <label for="site_name" class="block text-sm font-medium text-slate-300 mb-2">
                                            Site Name
                                        </label>
                                        <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($siteSettings['site_name']); ?>" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-slate-400">
                                            The name of your therapy management system
                                        </p>
                                    </div>
                                    
                                    <!-- Contact Email -->
                                    <div>
                                        <label for="contact_email" class="block text-sm font-medium text-slate-300 mb-2">
                                            Contact Email
                                        </label>
                                        <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($siteSettings['contact_email']); ?>" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-slate-400">
                                            Primary contact email for system notifications
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <!-- Appointment Limit -->
                                    <div>
                                        <label for="appointment_limit" class="block text-sm font-medium text-slate-300 mb-2">
                                            Daily Appointment Limit
                                        </label>
                                        <input type="number" id="appointment_limit" name="appointment_limit" value="<?php echo htmlspecialchars($siteSettings['appointment_limit']); ?>" min="1" max="50" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-slate-400">
                                            Maximum number of appointments per day
                                        </p>
                                    </div>
                                    
                                    <!-- Maintenance Mode -->
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">
                                            Maintenance Mode
                                        </label>
                                        <div class="flex items-center">
                                            <label class="switch mr-3">
                                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" <?php echo $siteSettings['maintenance_mode'] == '1' ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                            <span class="text-white text-sm" id="maintenance_mode_text">
                                                <?php echo $siteSettings['maintenance_mode'] == '1' ? 'Enabled' : 'Disabled'; ?>
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">
                                            When enabled, only administrators can access the system
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Additional Settings Section -->
                                <div class="mt-6 pt-6 border-t border-[#1e293b]">
                                    <h4 class="text-md font-medium text-white mb-4">Additional Settings</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-[#1e293b] p-4 rounded-lg">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-calendar text-blue-400 mr-2"></i>
                                                <h5 class="text-sm font-medium text-white">Date Format</h5>
                                            </div>
                                            <select class="py-2 px-3 block w-full text-sm rounded-lg bg-[#0f172a] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <option value="m/d/Y">MM/DD/YYYY</option>
                                                <option value="d/m/Y">DD/MM/YYYY</option>
                                                <option value="Y-m-d">YYYY-MM-DD</option>
                                            </select>
                                        </div>
                                        
                                        <div class="bg-[#1e293b] p-4 rounded-lg">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-clock text-blue-400 mr-2"></i>
                                                <h5 class="text-sm font-medium text-white">Time Format</h5>
                                            </div>
                                            <select class="py-2 px-3 block w-full text-sm rounded-lg bg-[#0f172a] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <option value="g:i a">12-hour (1:30 pm)</option>
                                                <option value="H:i">24-hour (13:30)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="bg-[#1e293b] p-4 rounded-lg">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-globe text-blue-400 mr-2"></i>
                                                <h5 class="text-sm font-medium text-white">Language</h5>
                                            </div>
                                            <select class="py-2 px-3 block w-full text-sm rounded-lg bg-[#0f172a] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <option value="en">English</option>
                                                <option value="es">Spanish</option>
                                                <option value="fr">French</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-[#1e293b] mt-6 pt-6">
                                    <div class="flex justify-end">
                                        <button type="submit" id="saveSettingsBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Admin Users Tab -->
                    <div id="admin-users-tab" class="tab-content">
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] mb-6">
                            <div class="p-6 border-b border-[#1e293b]">
                                <div class="flex flex-wrap items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">Admin User Management</h3>
                                        <p class="text-sm text-slate-400 mt-1">
                                            Manage administrator accounts for TheraCare
                                        </p>
                                    </div>
                                    <button id="addAdminBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add Admin
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6 overflow-x-auto">
                                <table id="adminsTable" class="min-w-full divide-y divide-[#1e293b]">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                ID
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Name
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Email
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Created
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#1e293b]" id="adminTableBody">
                                        <!-- Will be populated by JavaScript -->
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-400">
                                                <div class="flex flex-col items-center py-6">
                                                    <svg class="h-12 w-12 text-slate-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-white">Loading admin users</h3>
                                                    <p class="text-sm text-slate-400 mt-1">Please wait...</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Permission Levels -->
                            <div class="p-6 border-t border-[#1e293b]">
                                <h4 class="text-md font-medium text-white mb-4">Admin Permission Levels</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-[#1e293b] p-4 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center justify-center size-6 rounded-full bg-blue-900/40 text-blue-400 mr-2">
                                                <i class="fas fa-user-cog"></i>
                                            </span>
                                            <h5 class="text-sm font-medium text-white">Super Admin</h5>
                                        </div>
                                        <p class="text-xs text-slate-400">
                                            Full system access with all permissions. Can manage other admins.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-[#1e293b] p-4 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center justify-center size-6 rounded-full bg-green-900/40 text-green-400 mr-2">
                                                <i class="fas fa-user-shield"></i>
                                            </span>
                                            <h5 class="text-sm font-medium text-white">Administrator</h5>
                                        </div>
                                        <p class="text-xs text-slate-400">
                                            Can manage all aspects of the system except admin users.
                                        </p>
                                    </div>
                                    
                                    <div class="bg-[#1e293b] p-4 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center justify-center size-6 rounded-full bg-amber-900/40 text-amber-400 mr-2">
                                                <i class="fas fa-user-edit"></i>
                                            </span>
                                            <h5 class="text-sm font-medium text-white">Manager</h5>
                                        </div>
                                        <p class="text-xs text-slate-400">
                                            Limited admin access. Cannot change system settings.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appearance Tab -->
                    <div id="appearance-tab" class="tab-content">
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Appearance Settings</h3>
                            <form id="appearanceForm">
                                <input type="hidden" name="action" value="update_settings">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="theme_color" class="block text-sm font-medium text-slate-300 mb-2">
                                            Primary Theme Color
                                        </label>
                                        <div class="flex items-center">
                                            <input type="color" id="theme_color" name="theme_color" value="<?php echo htmlspecialchars($siteSettings['theme_color']); ?>" class="h-10 w-20 p-1 rounded bg-[#1e293b] border-transparent">
                                            <span class="ml-3 text-white text-sm"><?php echo htmlspecialchars($siteSettings['theme_color']); ?></span>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Choose the primary accent color for the system
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">
                                            UI Density
                                        </label>
                                        <div class="flex space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="ui_density" value="comfortable" class="h-4 w-4 text-blue-600 border-gray-500 focus:ring-blue-500 bg-[#0f172a] focus:ring-offset-[#0f172a]" checked>
                                                <span class="ml-2 text-white text-sm">Comfortable</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="ui_density" value="compact" class="h-4 w-4 text-blue-600 border-gray-500 focus:ring-blue-500 bg-[#0f172a] focus:ring-offset-[#0f172a]">
                                                <span class="ml-2 text-white text-sm">Compact</span>
                                            </label>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Control the spacing of UI elements throughout the system
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <h4 class="text-md font-medium text-white mb-3">Theme Preview</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-[#0f172a] rounded-lg border border-[#1e293b] p-4 text-center">
                                            <div id="preview-button" class="py-2 px-4 inline-flex justify-center w-full text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                                Button
                                            </div>
                                            <p class="mt-2 text-xs text-slate-400">Primary Button</p>
                                        </div>
                                        
                                        <div class="bg-[#0f172a] rounded-lg border border-[#1e293b] p-4 text-center">
                                            <div id="preview-accent" class="h-10 w-full rounded-md bg-blue-600"></div>
                                            <p class="mt-2 text-xs text-slate-400">Accent Color</p>
                                        </div>
                                        
                                        <div class="bg-[#0f172a] rounded-lg border border-[#1e293b] p-4 text-center">
                                            <div class="flex items-center justify-center">
                                                <div id="preview-badge" class="px-2 py-1 text-xs rounded-full bg-blue-600/30 text-blue-400">
                                                    Badge
                                                </div>
                                            </div>
                                            <p class="mt-2 text-xs text-slate-400">Badge Style</p>
                                        </div>
                                        
                                        <div class="bg-[#0f172a] rounded-lg border border-[#1e293b] p-4 text-center">
                                            <div class="inline-flex items-center space-x-2">
                                                <label class="switch">
                                                    <input type="checkbox" checked>
                                                    <span id="preview-toggle" class="slider"></span>
                                                </label>
                                                <span class="text-white text-xs">Toggle</span>
                                            </div>
                                            <p class="mt-2 text-xs text-slate-400">Toggle Button</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Logo and Brand Settings -->
                                <div class="mt-8 pt-6 border-t border-[#1e293b]">
                                    <h4 class="text-md font-medium text-white mb-4">Logo & Branding</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                                System Logo
                                            </label>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-16 h-16 bg-[#1e293b] rounded-md flex items-center justify-center">
                                                    <i class="fas fa-plus text-slate-400"></i>
                                                </div>
                                                <button type="button" class="px-3 py-1.5 bg-[#1e293b] hover:bg-slate-700 text-white text-sm rounded-lg">
                                                    Upload Logo
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-400">
                                                Recommended size: 200x50 pixels
                                            </p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                                Favicon
                                            </label>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-8 h-8 bg-[#1e293b] rounded-md flex items-center justify-center">
                                                    <i class="fas fa-plus text-slate-400 text-xs"></i>
                                                </div>
                                                <button type="button" class="px-3 py-1.5 bg-[#1e293b] hover:bg-slate-700 text-white text-sm rounded-lg">
                                                    Upload Favicon
                                                </button>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-400">
                                                Recommended size: 32x32 pixels
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-[#1e293b] mt-8 pt-6">
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Security Tab -->
                    <div id="security-tab" class="tab-content">
                        <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6">
                            <h3 class="text-lg font-semibold text-white mb-4">Security Settings</h3>
                            
                            <div class="mb-6">
                                <div class="bg-blue-900/20 text-blue-400 rounded-lg p-4 text-sm mb-6">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle mt-0.5 mr-3"></i>
                                        <p>
                                            These settings control the security features of your TheraCare system. Adjust them carefully as they may affect user access.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="flex items-center">
                                            <div class="bg-[#1e293b] px-5 py-4 rounded-lg">
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-500 rounded focus:ring-blue-500 bg-[#0f172a] focus:ring-offset-[#0f172a]">
                                                    <span class="ml-3 text-white text-sm font-medium">Force Strong Passwords</span>
                                                </div>
                                                <p class="mt-1 ml-7 text-xs text-slate-400">
                                                    Require all users to use strong passwords
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="flex items-center">
                                            <div class="bg-[#1e293b] px-5 py-4 rounded-lg">
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-500 rounded focus:ring-blue-500 bg-[#0f172a] focus:ring-offset-[#0f172a]">
                                                    <span class="ml-3 text-white text-sm font-medium">Session Timeout</span>
                                                </div>
                                                <p class="mt-1 ml-7 text-xs text-slate-400">
                                                    Automatically log out inactive users after 30 minutes
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <div>
                                        <label class="flex items-center">
                                            <div class="bg-[#1e293b] px-5 py-4 rounded-lg">
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-500 rounded focus:ring-blue-500 bg-[#0f172a] focus:ring-offset-[#0f172a]">
                                                    <span class="ml-3 text-white text-sm font-medium">2FA Authentication</span>
                                                </div>
                                                <p class="mt-1 ml-7 text-xs text-slate-400">
                                                    Require two-factor authentication for admin users
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">
                                            Login Attempts Limit
                                        </label>
                                        <select class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="3">3 Attempts</option>
                                            <option value="5" selected>5 Attempts</option>
                                            <option value="10">10 Attempts</option>
                                        </select>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Number of failed login attempts before temporary account lockout
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">
                                            Password Reset Policy
                                        </label>
                                        <select class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="30">Every 30 days</option>
                                            <option value="60">Every 60 days</option>
                                            <option value="90" selected>Every 90 days</option>
                                            <option value="0">Never</option>
                                        </select>
                                        <p class="mt-1 text-xs text-slate-400">
                                            How often users need to reset their passwords
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Activity Log -->
                                <div class="mt-8 pt-6 border-t border-[#1e293b]">
                                    <h4 class="text-md font-medium text-white mb-4">Recent Security Activity</h4>
                                    <div class="bg-[#1e293b] rounded-lg overflow-hidden">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-[#0f172a]">
                                                <thead class="bg-[#0f172a]">
                                                    <tr>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                            Timestamp
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                            User
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                            Action
                                                        </th>
                                                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                            IP Address
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-[#0f172a]">
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            <?php echo date('Y-m-d H:i:s'); ?>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            admin@theracare.com
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs">
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900/30 text-green-400">
                                                                Login Success
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            <?php echo $_SERVER['REMOTE_ADDR']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            <?php echo date('Y-m-d H:i:s', strtotime('-1 day')); ?>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            manager@theracare.com
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs">
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-900/30 text-amber-400">
                                                                Password Changed
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            192.168.1.105
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            <?php echo date('Y-m-d H:i:s', strtotime('-2 days')); ?>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            unknown@example.com
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs">
                                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/30 text-red-400">
                                                                Login Failed
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-slate-300">
                                                            203.0.113.245
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-[#1e293b] mt-8 pt-6">
                                <div class="flex justify-end">
                                    <button type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg flex items-center">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Security Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Add Admin Modal -->
<div id="addAdminModal" class="modal-backdrop">
    <div class="modal max-w-md rounded-xl">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#0f172a] rounded-t-xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">
                        Add New Admin
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">
                        Create a new administrator account
                    </p>
                </div>
                <button type="button" id="closeAddAdminModalBtn" class="text-slate-400 hover:text-white">
                    <span class="sr-only">Close</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        
        <!-- Body -->
        <div class="px-6 py-4 bg-[#0f172a]">
            <form id="addAdminForm">
                <input type="hidden" name="action" value="add_admin">
                
                <div class="grid grid-cols-1 gap-y-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-slate-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-slate-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                        <input type="password" name="password" id="password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="8">
                        <p class="mt-1 text-xs text-slate-400">Minimum 8 characters</p>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-slate-300 mb-1">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="py-2 px-3 block w-full text-sm rounded-lg bg-[#1e293b] border-transparent text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500" required minlength="8">
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-[#0f172a] border-t border-slate-700 rounded-b-xl flex justify-end gap-2">
            <button type="button" id="cancelAddAdminBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                Cancel
            </button>
            <button type="button" id="saveAdminBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Add Admin
            </button>
        </div>
    </div>
</div>

<!-- Delete Admin Confirmation Modal -->
<div id="deleteAdminModal" class="modal-backdrop">
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
                
                <h3 class="text-lg font-semibold text-white mb-2">Confirm Delete</h3>
                <p class="text-sm text-slate-400">
                    Are you sure you want to delete this admin user? This action cannot be undone.
                </p>
            </div>
            
            <div class="flex justify-center gap-3 mt-6">
                <button type="button" id="cancelDeleteAdminBtn" class="px-4 py-2 bg-[#1e293b] hover:bg-slate-700 text-white text-sm font-medium rounded-lg">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteAdminBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    Delete
                </button>
            </div>
            
            <form id="deleteAdminForm">
                <input type="hidden" name="action" value="delete_admin">
                <input type="hidden" name="id" id="delete_admin_id">
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div id="successToast" class="toast success">
    <div class="toast-header px-4 py-3 flex items-center">
        <svg class="h-5 w-5 mr-2 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="font-medium">Success</span>
        <button type="button" class="ml-auto toast-close">
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="toast-body px-4 py-3 text-sm" id="successToastMessage">Operation completed successfully.</div>
</div>

<div id="errorToast" class="toast error">
    <div class="toast-header px-4 py-3 flex items-center">
        <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <span class="font-medium">Error</span>
        <button type="button" class="ml-auto toast-close">
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="toast-body px-4 py-3 text-sm" id="errorToastMessage">An error occurred. Please try again.</div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="admin_settings_script.js"></script>

<?php
// Close connection
$conn->close();
?>
</body>
</html>