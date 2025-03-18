 <!-- Add Therapist Modal -->
 <div id="addTherapistModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">

        <!-- Modal panel with dark header -->
        <div class="inline-block w-full max-w-lg overflow-hidden text-left align-bottom transition-all transform bg-white rounded-md shadow-xl">
            <!-- Modal header with title -->
            <div class="px-6 py-4 text-white bg-gray-800">
                <h3 class="text-lg font-medium">Add New Therapist</h3>
            </div>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <!-- Form content with proper spacing -->
                <div class="px-6 py-4">
                    <!-- Form fields with consistent spacing -->
                    <div class="space-y-3">
                        <!-- Name row -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        
                        <!-- Specialization -->
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                            <select name="specialization" id="specialization" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="Orthopedic Physical Therapy">Orthopedic Physical Therapy</option>
                                <option value="Neurological Physical Therapy">Neurological Physical Therapy</option>
                                <option value="Pediatric Physical Therapy">Pediatric Physical Therapy</option>
                                <option value="Geriatric Physical Therapy">Geriatric Physical Therapy</option>
                                <option value="Sports Physical Therapy">Sports Physical Therapy</option>
                                <option value="Cardiopulmonary Physical Therapy">Cardiopulmonary Physical Therapy</option>
                                <option value="Vestibular Rehabilitation">Vestibular Rehabilitation</option>
                                <option value="Pelvic Floor Physical Therapy">Pelvic Floor Physical Therapy</option>
                                <option value="Oncologic Physical Therapy">Oncologic Physical Therapy</option>
                                <option value="Hand Therapy">Hand Therapy</option>
                            </select>
                        </div>
                        
                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        
                        <!-- Two-column row -->
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <!-- Consultation Fee -->
                            <div>
                                <label for="consultation_fee" class="block text-sm font-medium text-gray-700">Consultation Fee</label>
                                <div class="relative mt-1 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-10 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm"></span>
                                    </div>
                                    <input type="number" name="consultation_fee" id="consultation_fee" step="0.01" class="block w-full pl-7 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <!-- Available Slots -->
                            <div>
                                <label for="available_slots" class="block text-sm font-medium text-gray-700">Available Slots</label>
                                <input type="number" name="available_slots" id="available_slots" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form buttons with centered layout -->
                <div class="px-6 py-4 bg-gray-100">
                    <div class="flex justify-center space-x-4">
                    <button type="button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="closeAddTherapistModal()">
    Cancel
</button>

                        <button type="submit" name="add_therapist" value="1" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Therapist
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for modal functionality -->
<script>
    function openAddTherapistModal() {
        document.getElementById('addTherapistModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden'); // Prevent background scrolling
    }
    
    function closeAddTherapistModal() {
        document.getElementById('addTherapistModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden'); // Re-enable scrolling
    }
</script>
</body>
</html>
