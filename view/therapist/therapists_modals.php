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
                        <div class="flex items-center gap-1.5">
                            <span class="text-amber-300">★★★★★</span>
                            <span id="modalDoctorRating" class="font-semibold">0.0</span>
                            <span id="modalDoctorReviews" class="text-sm text-white/80">(0 reviews)</span>
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
                    
                    <!-- Right column - Dynamic content based on login status -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Booking section for logged-in users - Simplified to just a button -->
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-fit">
                        <h3 class="font-semibold text-center text-slate-900 mb-4">Schedule an Appointment</h3>
                        
                        <!-- Hidden input for therapist_id to be used in redirection -->
                        <input type="hidden" id="therapist_id" value="">
                        
                        <div class="text-center py-6">
                            <p class="text-slate-600 mb-6">Click below to book an appointment with this therapist</p>
                            
                            <a href="javascript:redirectToBooking();" class="w-full inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20 text-center">
                                Book Appointment
                            </a>
                        </div>
                        
                        <p class="text-xs text-slate-500 mt-3 text-center">By booking, you agree to our appointment policies</p>
                    </div>
                    
                    <?php else: ?>
                    <!-- Login prompt for non-logged in users - Kept unchanged -->
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-fit">
                        <div class="text-center py-6">
                            <div class="mb-4 flex justify-center">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-lock text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="font-semibold text-slate-900 mb-2">Login Required</h3>
                            <p class="text-slate-600 mb-6">Please sign in to schedule an appointment with this doctor</p>
                            <a href="../authentication/login.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20">
                                Sign In
                            </a>
                            <p class="mt-4 text-sm text-slate-500">
                                Don't have an account? <a href="../authentication/signup.php" class="text-blue-600 hover:underline">Register</a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for modal functionality -->
   <script src = "therapists_modals_script2.js"></script>

</body>
</html> <!-- Doctor Modal -->
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
                        <div class="flex items-center gap-1.5">
                            <span class="text-amber-300">★★★★★</span>
                            <span id="modalDoctorRating" class="font-semibold">0.0</span>
                            <span id="modalDoctorReviews" class="text-sm text-white/80">(0 reviews)</span>
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
                    
                    <!-- Right column - Dynamic content based on login status -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Booking section for logged-in users - Simplified to just a button -->
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-fit">
                        <h3 class="font-semibold text-center text-slate-900 mb-4">Schedule an Appointment</h3>
                        
                        <!-- Hidden input for therapist_id to be used in redirection -->
                        <input type="hidden" id="therapist_id" value="">
                        
                        <div class="text-center py-6">
                            <p class="text-slate-600 mb-6">Click below to book an appointment with this therapist</p>
                            
                            <a href="javascript:redirectToBooking();" class="w-full inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20 text-center">
                                Book Appointment
                            </a>
                        </div>
                        
                        <p class="text-xs text-slate-500 mt-3 text-center">By booking, you agree to our appointment policies</p>
                    </div>
                    
                    <?php else: ?>
                    <!-- Login prompt for non-logged in users - Kept unchanged -->
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 h-fit">
                        <div class="text-center py-6">
                            <div class="mb-4 flex justify-center">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="fas fa-lock text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <h3 class="font-semibold text-slate-900 mb-2">Login Required</h3>
                            <p class="text-slate-600 mb-6">Please sign in to schedule an appointment with this doctor</p>
                            <a href="../authentication/login.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20">
                                Sign In
                            </a>
                            <p class="mt-4 text-sm text-slate-500">
                                Don't have an account? <a href="../authentication/signup.php" class="text-blue-600 hover:underline">Register</a>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for modal functionality -->
   <script src="therapists_modals_script.js"></script>

</body>
</html>