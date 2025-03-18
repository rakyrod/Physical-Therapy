
<?php include('login_controller.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TheraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0e4991',
                            light: '#1565c0',
                            dark: '#0d3c77',
                            50: '#e3f2fd',
                            100: '#bbdefb',
                            200: '#90caf9',
                            300: '#64b5f6',
                            400: '#42a5f5',
                            500: '#1565c0',
                            600: '#0d47a1',
                            700: '#0c3d88',
                            800: '#0a326e',
                            900: '#072654',
                        },
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
                    },
                    boxShadow: {
                        'inner-lg': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
                        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(66, 153, 225, 0.5)',
                    },
                    animation: {
                        'pulse-light': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter bg-gradient-to-b from-gray-100 to-gray-200 min-h-screen">
    <!-- Background design -->
    <div class="fixed inset-0 z-0 opacity-70">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-700 via-primary-500 to-primary-900"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-10"></div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            <!-- Healthcare branding element -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white shadow-soft mb-4">
                    <i class="fas fa-heartbeat text-3xl text-primary-500"></i>
                </div>
                <h1 class="text-white text-3xl font-bold tracking-tight">TheraCare</h1>
                <p class="text-primary-100 text-sm mt-1">Healthcare at your fingertips</p>
            </div>
            
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all hover:shadow-glow">
                <!-- Blue accent line at top -->
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                
                <div class="px-6 sm:px-8 pt-8 pb-8">
                    <h2 class="text-center text-2xl font-semibold text-gray-800 mb-8">Welcome Back</h2>
                    
                    <!-- Error message placeholder -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm mb-6 flex items-center border-l-4 border-red-500 animate-pulse-light">
                            <i class="fas fa-exclamation-circle text-lg mr-3"></i>
                            <span>
                                <?php 
                                    echo htmlspecialchars($_SESSION['error']);
                                    unset($_SESSION['error']);
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <!-- Success message placeholder -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="bg-green-50 text-green-600 p-4 rounded-lg text-sm mb-6 flex items-center border-l-4 border-green-500">
                            <i class="fas fa-check-circle text-lg mr-3"></i>
                            <span>
                                <?php 
                                    echo htmlspecialchars($_SESSION['success']);
                                    unset($_SESSION['success']);
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="login-form" novalidate class="space-y-6">
                        <!-- CSRF token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" id="email" name="email" required
                                       class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-base transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                       placeholder="Enter your email">
                            </div>
                            <div id="email-error" class="mt-1.5 text-sm text-red-500 hidden">
                                <i class="fas fa-info-circle mr-1"></i>
                                Please enter a valid email address
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <a href="forgot_password.php" class="text-xs text-primary-500 hover:text-primary-700 hover:underline transition-colors duration-200">
                                    Forgot Password?
                                </a>
                            </div>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" required
                                       class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-base transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                       placeholder="Enter your password">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div id="password-error" class="mt-1.5 text-sm text-red-500 hidden">
                                <i class="fas fa-info-circle mr-1"></i>
                                Please enter your password
                            </div>
                        </div>

                        <!-- Remember me checkbox -->
                        <div class="flex items-center">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="remember" name="remember"
                                           class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 transition-colors duration-200">
                                </div>
                                <div class="ml-2 text-sm">
                                    <label for="remember" class="font-medium text-gray-700">
                                        Remember me
                                    </label>
                                    <p class="text-gray-400 text-xs">Keep me signed in for 30 days</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" id="login-btn" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i> 
                            Sign In
                        </button>

                        <!-- Sign up link -->
                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-600">
                                Don't have an account? 
                                <a href="signup.php" class="text-primary-600 font-medium hover:text-primary-800 hover:underline transition-colors duration-200">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Footer links -->
            <div class="text-center mt-8">
                <p class="text-sm text-white flex items-center justify-center space-x-4">
                    <a href="help.php" class="hover:underline flex items-center">
                        <i class="fas fa-question-circle mr-1 opacity-70"></i>
                        <span>Help Center</span>
                    </a>
                    <span class="text-primary-200">•</span>
                    <a href="contact.php" class="hover:underline flex items-center">
                        <i class="fas fa-headset mr-1 opacity-70"></i>
                        <span>Support</span>
                    </a>
                    <span class="text-primary-200">•</span>
                    <a href="terms.php" class="hover:underline flex items-center">
                        <i class="fas fa-shield-alt mr-1 opacity-70"></i>
                        <span>Privacy</span>
                    </a>
                </p>
                <p class="text-xs text-primary-100 mt-4 opacity-70">
                    © <?php echo date('Y'); ?> TheraCare. All rights reserved.
                </p>
            </div>
        </div>
    </div>

   <script src = "login_script.js"></script>
   
</body>
</html>