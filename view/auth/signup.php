<?php include('signup_controller.php') ; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TheraCare</title>
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
                    <h2 class="text-center text-2xl font-semibold text-gray-800 mb-6">Create Your Account</h2>
                    
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
                    
                    <!-- Form -->
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="signup-form" class="space-y-5" novalidate>
                        <!-- CSRF token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <!-- Name fields in two columns -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="first_name" name="first_name" required
                                           class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-sm transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                           placeholder="Enter first name">
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" id="last_name" name="last_name" required
                                           class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-sm transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                           placeholder="Enter last name">
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" id="email" name="email" required
                                       class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-sm transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                       placeholder="Enter your email">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="password" name="password" required
                                       minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                       class="pl-10 pr-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-sm transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                       placeholder="Minimum 8 characters">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password strength meter -->
                            <div class="flex items-center space-x-2 mt-2">
                                <div class="h-1.5 flex-grow bg-gray-200 rounded-full overflow-hidden">
                                    <div id="password-meter" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                                </div>
                                <span id="password-strength-text" class="text-xs text-gray-500 whitespace-nowrap">Strength</span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" id="confirm_password" name="confirm_password" required
                                       class="pl-10 pr-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 text-gray-700 text-sm transition-all duration-200 bg-gray-50 focus:bg-white shadow-inner-lg"
                                       placeholder="Re-enter your password">
                                <button type="button" id="toggle-confirm-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Terms checkbox -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="terms" name="terms" required
                                       class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 transition-colors duration-200">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-gray-700">
                                    I agree to the <a href="#" id="terms-modal-trigger" class="text-primary-500 hover:text-primary-700 hover:underline transition-colors duration-200">Terms and Conditions</a>
                                </label>
                                <p class="text-gray-500 text-xs mt-1">By creating an account, you agree to our privacy policy</p>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" id="signup-btn" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-user-plus mr-2"></i> 
                            Create Account
                        </button>

                        <!-- Login link -->
<!-- Login link with proper onclick behavior -->
<div class="text-center">
    <p class="text-sm text-gray-600">
        Already have an account? 
        <a href="login.php?force_login=1" class="text-primary-600 font-medium hover:text-primary-800 hover:underline transition-colors duration-200">
    Sign In
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

    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="terms-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div id="terms-modal-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <!-- Modal positioning -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Blue accent line at top -->
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                
                <!-- Modal header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-contract text-primary-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="terms-modal-title">
                                Terms and Conditions
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Please read these terms carefully before using our service.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal body -->
                <div class="bg-gray-50 px-4 py-5 sm:p-6 max-h-96 overflow-y-auto">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <h4 class="font-bold text-gray-900">1. Introduction</h4>
                        <p>Welcome to TheraCare. By using our service, you agree to these Terms and Conditions, which constitute a legally binding agreement.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">2. Definitions</h4>
                        <p>"Service" refers to the TheraCare platform, accessible via our website.</p>
                        <p>"User" refers to individuals who create an account on our platform.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">3. Account Registration</h4>
                        <p>To use our services, you must create an account with accurate information. You are responsible for maintaining the confidentiality of your account credentials.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">4. Privacy Policy</h4>
                        <p>Your use of the Service is subject to our Privacy Policy, which describes how we collect, use, and protect your personal information.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">5. User Content</h4>
                        <p>Any content you submit to the Service remains your intellectual property, but you grant us a license to use it for the purpose of providing our services.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">6. Acceptable Use</h4>
                        <p>You agree not to use the Service for any illegal, harmful, or unauthorized purposes. We reserve the right to terminate accounts that violate these terms.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">7. Healthcare Disclaimer</h4>
                        <p>The TheraCare platform is not a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician with any questions regarding a medical condition.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">8. Limitation of Liability</h4>
                        <p>To the maximum extent permitted by law, TheraCare shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of the Service.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">9. Changes to Terms</h4>
                        <p>We may modify these Terms at any time. It's your responsibility to review the Terms periodically. Your continued use of the Service after any changes constitutes acceptance of those changes.</p>
                        
                        <h4 class="font-bold text-gray-900 mt-4">10. Governing Law</h4>
                        <p>These Terms shall be governed by and construed in accordance with the laws of the jurisdiction in which TheraCare operates, without regard to its conflict of law provisions.</p>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="terms-modal-close" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

  <script src = "signup_script.js"></script>
  
</body>
</html>