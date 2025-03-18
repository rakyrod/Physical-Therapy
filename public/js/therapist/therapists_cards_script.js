
// Store all therapist data in JavaScript
const therapistsData = <?php echo json_encode($therapistsData); ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Handle opening the doctor modal and setting the therapist_id
    const openModalButtons = document.querySelectorAll('.open-doctor-modal');
    if (openModalButtons) {
        openModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const therapistId = this.getAttribute('data-therapist-id');
                openDoctorModal(therapistId);
            });
        });
    }
    
    // Close modal handler
    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            document.getElementById('doctorModal').classList.add('hidden');
        });
    }
});

// Function to redirect to booking page with therapist ID
function redirectToBooking() {
    const therapistId = document.getElementById('therapist_id').value;
    if (therapistId) {
        window.location.href = '../patients/booking.php?therapist_id=' + therapistId;
    } else {
        alert('Therapist information is missing. Please try again.');
    }
}

// Function to open doctor modal
function openDoctorModal(therapistId) {
    // Get therapist data from our JavaScript object
    const data = therapistsData[therapistId];
    
    if (data) {
        // Update modal with doctor information
        document.getElementById('modalDoctorName').textContent = data.full_name;
        document.getElementById('modalDoctorSpecialty').textContent = data.specialization;
        document.getElementById('modalDoctorImage').src = data.image_path;
        document.getElementById('modalDoctorRating').textContent = data.rating;
        document.getElementById('modalDoctorReviews').textContent = `(${data.review_count} reviews)`;
        document.getElementById('modalDoctorBio').textContent = data.description;
        
        // Update email and phone
        document.getElementById('modalDoctorEmail').querySelector('span').textContent = data.email || 'Not provided';
        document.getElementById('modalDoctorPhone').querySelector('span').textContent = data.phone || 'Not provided';
        
        // Update status badge in header
        const statusBadge = document.getElementById('modalDoctorStatus');
        const statusDot = statusBadge.querySelector('div');
        statusDot.className = `w-2 h-2 rounded-full bg-${data.status_color}-500`;
        statusBadge.querySelector('span').textContent = data.status;
        
        // Update tags
        const tagsContainer = document.getElementById('modalDoctorTags');
        tagsContainer.innerHTML = '';
        
        if (data.tags && data.tags.length > 0) {
            data.tags.forEach(tag => {
                const tagElem = document.createElement('span');
                tagElem.className = `inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm`;
                tagElem.textContent = tag;
                tagsContainer.appendChild(tagElem);
            });
        }
        
        // Update education
        const educationList = document.getElementById('modalDoctorEducation');
        educationList.innerHTML = '';
        
        if (data.education && data.education.length > 0) {
            data.education.forEach(edu => {
                const eduItem = document.createElement('li');
                eduItem.className = 'flex items-start gap-2';
                eduItem.innerHTML = `
                    <div class="text-blue-500 mt-1">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span>${edu}</span>
                `;
                educationList.appendChild(eduItem);
            });
        }
        
        // Update reviews
        const reviewsList = document.getElementById('modalDoctorReviewsList');
        reviewsList.innerHTML = '';
        
        if (data.reviews && data.reviews.length > 0) {
            data.reviews.forEach(review => {
                const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
                const reviewItem = document.createElement('div');
                reviewItem.className = 'bg-slate-50 rounded-lg p-4 border border-slate-100';
                reviewItem.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <div class="font-medium text-slate-900">${review.name}</div>
                        <div class="text-xs text-slate-500">${review.date}</div>
                    </div>
                    <div class="text-amber-400 mb-2">${stars}</div>
                    <p class="text-slate-600 text-sm">${review.content}</p>
                `;
                reviewsList.appendChild(reviewItem);
            });
        }
        
        // Update booking section based on therapist status
        const bookingSection = document.getElementById('bookingSection');
        bookingSection.innerHTML = '';
        
        <?php if(isset($_SESSION['user_id'])): ?>
        // For logged-in users, show booking or unavailable message based on status
        if (data.status === 'Available') {
            bookingSection.innerHTML = `
                <h3 class="font-semibold text-center text-slate-900 mb-4">Schedule an Appointment</h3>
                
                <!-- Fee information -->
                <div class="mb-4 text-center">
                    <span class="text-2xl font-bold text-blue-600">₱${parseFloat(data.fee).toLocaleString()}</span>
                    <span class="text-slate-500 text-sm"> / session</span>
                </div>
                
                <!-- Hidden input for therapist_id to be used in redirection -->
                <input type="hidden" id="therapist_id" value="${therapistId}">
                
                <div class="text-center py-4">
                    <p class="text-slate-600 mb-6">Click below to book an appointment with this therapist</p>
                    
                    <a href="javascript:redirectToBooking();" class="w-full inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20 text-center">
                        Book Appointment
                    </a>
                </div>
                
                <p class="text-xs text-slate-500 mt-3 text-center">By booking, you agree to our appointment policies</p>
            `;
        } else {
            // Therapist is busy, show unavailable message
            bookingSection.innerHTML = `
                <div class="text-center py-6">
                    <div class="mb-4 flex justify-center">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-calendar-times text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <h3 class="font-semibold text-slate-900 mb-2">Currently Unavailable</h3>
                    <p class="text-slate-600 mb-6">This therapist is currently busy and not accepting new appointments.</p>
                    <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-100">
                        <p class="text-sm text-amber-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            You can check back later or explore other available therapists.
                        </p>
                    </div>
                </div>
            `;
        }
        <?php else: ?>
        // For non-logged in users, show login prompt
        bookingSection.innerHTML = `
            <div class="text-center py-6">
                <div class="mb-4 flex justify-center">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-lock text-blue-600 text-xl"></i>
                    </div>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">Login Required</h3>
                <p class="text-slate-600 mb-6">Please sign in to schedule an appointment with this therapist</p>
                <a href="../authentication/login.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all hover:-translate-y-1 shadow-md hover:shadow-lg shadow-blue-600/20">
                    Sign In
                </a>
                <p class="mt-4 text-sm text-slate-500">
                    Don't have an account? <a href="../authentication/signup.php" class="text-blue-600 hover:underline">Register</a>
                </p>
            </div>
        `;
        <?php endif; ?>
    }
    
    // Show the modal
    document.getElementById('doctorModal').classList.remove('hidden');
}
