
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
    // Set the therapist ID in the hidden field
    if (document.getElementById('therapist_id')) {
        document.getElementById('therapist_id').value = therapistId;
    }
    
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
    }
    
    // Show the modal
    document.getElementById('doctorModal').classList.remove('hidden');
}
