<?php 
                            if ($therapistsResult && $therapistsResult->num_rows > 0) {
                                while ($therapist = $therapistsResult->fetch_assoc()) {
                                    // Generate avatar with therapist's initials
                                    $initials = strtoupper(substr($therapist['first_name'] ?? 'T', 0, 1) . substr($therapist['last_name'] ?? 'H', 0, 1));
                                    $fullName = $therapist['first_name'] . ' ' . $therapist['last_name'];
                                    
                                    // Format specialization
                                    $specialization = $therapist['specialization'] ?: 'General';
                                    $shortSpecialization = str_replace('Physical Therapy', 'PT', $specialization);
                                    
                                    // Format contact info
                                    $email = $therapist['email'];
                                    $phone = $therapist['phone_number'] ?: 'Not provided';
                                    
                                    // Format fee
                                    $fee = $therapist['consultation_fee'] ? '₱' . number_format($therapist['consultation_fee'], 2) : 'Not set';
                                    
                                    // Format slots
                                    $slots = $therapist['available_slots'] ?: 'Not set';
                                    
                                    // Status and progress
                                    $status = $therapist['status'] ?: 'Available';
                                    $statusClass = $status === 'Available' ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400';
                                    $statusIcon = $status === 'Available' ? 'fa-check' : 'fa-times';
                                    
                                    // Calculate progress percentage
                                    $total = (int)$therapist['total_appointments'];
                                    $completed = (int)$therapist['completed_appointments'];
                                    $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
                            ?>
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="bg-blue-500 text-white rounded-full h-10 w-10 flex items-center justify-center text-sm font-medium">
                                                <?php echo $initials; ?>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white"><?php echo htmlspecialchars($fullName); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/30 text-blue-400">
                                        <?php echo htmlspecialchars($shortSpecialization); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300"><?php echo htmlspecialchars($email); ?></div>
                                    <?php if ($phone !== 'Not provided'): ?>
                                    <div class="text-sm text-slate-400"><?php echo htmlspecialchars($phone); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300"><?php echo $fee; ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300"><?php echo $slots; ?></div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                        <i class="fa-solid <?php echo $statusIcon; ?> mr-1"></i>
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="w-24 bg-[#1e293b] rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                                        </div>
                                        <span class="text-xs text-slate-400 mt-1"><?php echo $completed; ?>/<?php echo $total; ?> completed</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button class="view-therapist p-1 rounded-md bg-[#1e293b] text-slate-400 hover:text-white" data-id="<?php echo $therapist['id']; ?>">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button class="edit-therapist p-1 rounded-md bg-[#1e293b] text-blue-400 hover:text-blue-300" data-id="<?php echo $therapist['id']; ?>">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="delete-therapist p-1 rounded-md bg-[#1e293b] text-red-400 hover:text-red-300" data-id="<?php echo $therapist['id']; ?>" data-name="<?php echo htmlspecialchars($fullName); ?>">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center">
                                    <div class="flex flex-col items-center py-6">
                                        <svg class="h-12 w-12 text-slate-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-white">No therapists found</h3>
                                        <p class="text-sm text-slate-400 mt-1">
                                            <?php echo empty($search) ? 'No therapists have been added yet' : 'No therapists match your search criteria'; ?>
                                        </p>
                                        <?php if (!empty($search)): ?>
                                        <a href="?" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Clear Search
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>