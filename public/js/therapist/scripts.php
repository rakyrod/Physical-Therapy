<!-- Add this script before the closing </body> tag -->
<script>
    // Doctor details data
    const doctorDetails = {
        1: {
            name: "Dr. Nicole Ednilan",
            specialty: "Cardiopulmonary",
            rating: 4.8,
            reviews: 124,
            image: "../images/nicole.png",
            bio: "Dr. Nicole Ednilan is a highly skilled cardiopulmonary specialist with over 15 years of experience. She specializes in the diagnosis and treatment of heart and lung conditions, with particular expertise in pulmonary rehabilitation, cardiac care, and respiratory disorders. Her comprehensive approach to treatment ensures patients receive personalized care for optimal recovery and long-term health management.",
            tags: ["Pulmonary Rehab", "Cardiac Care", "COPD Management", "Respiratory Disorders", "Post-Surgery Rehabilitation"],
            education: [
                "Doctor of Physical Therapy, University of Southern California",
                "Board Certified Cardiovascular and Pulmonary Clinical Specialist",
                "Advanced Cardiac Life Support (ACLS) Certification",
                "Fellowship in Cardiopulmonary Rehabilitation"
            ],
            patientReviews: [
                {
                    name: "Robert J.",
                    rating: 5,
                    date: "February 15, 2025",
                    comment: "Dr. Ednilan's treatment plan after my heart surgery was exceptional. Her expertise in cardiac rehabilitation made a tremendous difference in my recovery."
                },
                {
                    name: "Maria S.",
                    rating: 5,
                    date: "January 22, 2025",
                    comment: "As someone with chronic COPD, finding Dr. Ednilan was life-changing. Her pulmonary rehabilitation program has significantly improved my breathing and quality of life."
                },
                {
                    name: "David L.",
                    rating: 4,
                    date: "December 7, 2024",
                    comment: "Very knowledgeable and attentive to detail. Takes time to explain everything clearly and answers all questions thoroughly."
                }
            ]
        },
        2: {
            name: "Dr. Michael Chen",
            specialty: "Orthopedic",
            rating: 4.9,
            reviews: 187,
            image: "../images/cj.png",
            bio: "Dr. Michael Chen is a leading orthopedic specialist with extensive training in sports medicine and joint rehabilitation. His approach combines advanced manual therapy techniques with evidence-based treatment protocols to help patients recover from injuries, surgeries, and chronic conditions. Dr. Chen is particularly known for his expertise in treating athletic injuries and post-surgical rehabilitation.",
            tags: ["Sports Medicine", "Joint Rehabilitation", "Post-Surgical Care", "Manual Therapy", "Injury Prevention"],
            education: [
                "Doctor of Physical Therapy, Northwestern University",
                "Orthopedic Certified Specialist (OCS)",
                "Certified in Functional Dry Needling",
                "Sports Certified Specialist (SCS)",
                "Master's in Human Movement Science"
            ],
            patientReviews: [
                {
                    name: "Sarah T.",
                    rating: 5,
                    date: "February 28, 2025",
                    comment: "After my ACL reconstruction, Dr. Chen's rehabilitation program got me back to playing soccer faster than I expected. His knowledge of sports injuries is remarkable."
                },
                {
                    name: "James M.",
                    rating: 5,
                    date: "January 14, 2025",
                    comment: "Dr. Chen's manual therapy techniques provided immediate relief for my chronic shoulder pain. His treatment approach is both thorough and effective."
                },
                {
                    name: "Lisa K.",
                    rating: 5,
                    date: "December 20, 2024",
                    comment: "As a marathon runner, I've seen many specialists for various injuries, but Dr. Chen stands out. His comprehensive care and preventative strategies have kept me running pain-free."
                }
            ]
        },
        3: {
            name: "Dr. Jennifer Williams",
            specialty: "Neurological",
            rating: 4.7,
            reviews: 93,
            image: "/api/placeholder/400/230",
            bio: "Dr. Jennifer Williams specializes in neurological rehabilitation with a focus on stroke recovery, balance disorders, and vestibular rehabilitation. With over 12 years of experience, she has developed expertise in treating patients with complex neurological conditions. Her patient-centered approach emphasizes functional recovery and independence, combining traditional therapy with innovative neurological rehabilitation techniques.",
            tags: ["Stroke Rehabilitation", "Balance Disorders", "Vestibular Therapy", "Neurological Conditions", "Parkinson's Treatment"],
            education: [
                "Doctor of Physical Therapy, Emory University",
                "Neurologic Certified Specialist (NCS)",
                "Certified in Vestibular Rehabilitation",
                "Advanced training in Stroke Rehabilitation",
                "Certified in LSVT BIG for Parkinson's Disease"
            ],
            patientReviews: [
                {
                    name: "Michael P.",
                    rating: 5,
                    date: "February 5, 2025",
                    comment: "After my stroke, Dr. Williams was instrumental in helping me regain my independence. Her expertise in neurological rehabilitation is exceptional."
                },
                {
                    name: "Eleanor R.",
                    rating: 4,
                    date: "January 10, 2025",
                    comment: "My chronic vertigo has significantly improved thanks to Dr. Williams' vestibular therapy program. She's very knowledgeable and supportive."
                },
                {
                    name: "Thomas H.",
                    rating: 5,
                    date: "November 15, 2024",
                    comment: "Living with Parkinson's became much more manageable after starting treatment with Dr. Williams. Her specialized approach has improved my mobility and confidence."
                }
            ]
        },
        9: {
            name: "Dr. Margaret Thompson",
            specialty: "Geriatric",
            rating: 4.6,
            reviews: 108,
            image: "/api/placeholder/400/230",
            bio: "Dr. Margaret Thompson is a dedicated geriatric specialist with over 20 years of experience in improving mobility and quality of life for older adults. Her practice focuses on fall prevention, management of age-related conditions, and maintaining independence in daily activities. Dr. Thompson takes a holistic approach to care, addressing not just physical limitations but also considering environmental factors and overall wellbeing.",
            tags: ["Fall Prevention", "Aging-Related Conditions", "Balance Training", "Mobility Enhancement", "Arthritis Management"],
            education: [
                "Doctor of Physical Therapy, Boston University",
                "Geriatric Certified Specialist (GCS)",
                "Certified Exercise Expert for Aging Adults (CEEAA)",
                "Advanced training in Fall Prevention",
                "Certification in Dementia Care"
            ],
            patientReviews: [
                {
                    name: "Richard D.",
                    rating: 5,
                    date: "February 20, 2025",
                    comment: "At 82, I was worried about losing my independence after several falls. Dr. Thompson's fall prevention program has given me confidence and improved my balance significantly."
                },
                {
                    name: "Helen G.",
                    rating: 4,
                    date: "January 18, 2025",
                    comment: "Dr. Thompson's approach to my arthritis management has been life-changing. Her treatments are effective and her compassion is genuine."
                },
                {
                    name: "William J.",
                    rating: 5,
                    date: "December 3, 2024",
                    comment: "What sets Dr. Thompson apart is her understanding of the unique challenges facing older adults. Her treatment plan for my mobility issues was perfectly tailored to my needs and lifestyle."
                }
            ]
        }
    };

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('doctorModal');
        const closeBtn = document.getElementById('closeModal');
        const doctorLinks = document.querySelectorAll('.doctor-card a');
        
        // Open modal when View Details is clicked
        document.querySelectorAll('.doctor-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Get doctor ID from the onclick attribute or data attribute
                const href = this.querySelector('a').getAttribute('href');
                const doctorId = href.split('=')[1];
                openDoctorModal(doctorId);
            });
        });
        
        // Also handle clicks directly on the View Details buttons
        doctorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const doctorId = this.getAttribute('href').split('=')[1];
                openDoctorModal(doctorId);
            });
        });
        
        // Close modal when close button is clicked
        closeBtn.addEventListener('click', function() {
            closeModal();
        });
        
        // Close modal when clicking outside the modal
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
        
        // Function to open modal and populate doctor details
        function openDoctorModal(doctorId) {
            const doctor = doctorDetails[doctorId];
            
            if (!doctor) {
                console.error('Doctor not found:', doctorId);
                return;
            }
            
            // Set doctor details in modal
            document.getElementById('modalDoctorName').textContent = doctor.name;
            document.getElementById('modalDoctorSpecialty').textContent = doctor.specialty;
            document.getElementById('modalDoctorRating').textContent = doctor.rating.toFixed(1);
            document.getElementById('modalDoctorReviews').textContent = `(${doctor.reviews} reviews)`;
            document.getElementById('modalDoctorImage').src = doctor.image;
            document.getElementById('modalDoctorBio').textContent = doctor.bio;
            
            // Add specialization tags
            const tagsContainer = document.getElementById('modalDoctorTags');
            tagsContainer.innerHTML = '';
            doctor.tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium';
                tagElement.textContent = tag;
                tagsContainer.appendChild(tagElement);
            });
            
            // Add education & certifications
            const educationList = document.getElementById('modalDoctorEducation');
            educationList.innerHTML = '';
            doctor.education.forEach(item => {
                const listItem = document.createElement('li');
                listItem.className = 'flex items-start gap-2';
                listItem.innerHTML = `
                    <i class="fas fa-check-circle text-blue-600 mt-1"></i>
                    <span>${item}</span>
                `;
                educationList.appendChild(listItem);
            });
            
            // Add patient reviews
            const reviewsList = document.getElementById('modalDoctorReviewsList');
            reviewsList.innerHTML = '';
            doctor.patientReviews.forEach(review => {
                const reviewElement = document.createElement('div');
                reviewElement.className = 'border-b border-slate-200 pb-4';
                
                let stars = '';
                for (let i = 0; i < 5; i++) {
                    stars += i < review.rating ? '★' : '☆';
                }
                
                reviewElement.innerHTML = `
                    <div class="flex justify-between items-center mb-2">
                        <div class="font-medium text-slate-900">${review.name}</div>
                        <div class="text-sm text-slate-500">${review.date}</div>
                    </div>
                    <div class="text-amber-400 mb-2">${stars}</div>
                    <p class="text-slate-600 text-sm">${review.comment}</p>
                `;
                reviewsList.appendChild(reviewElement);
            });
            
            // Show modal with animation
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
        
        // Function to close modal
        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Re-enable scrolling
        }
    });

    // Add to your JavaScript file
document.querySelector('#doctorModal button.bg-blue-600').addEventListener('click', function() {
    const dateInput = doctorModal.querySelector('input[type="date"]').value;
    const selectedTime = doctorModal.querySelector('button.bg-blue-600.text-white').textContent;
    const visitType = doctorModal.querySelector('select').value;
    
    // Send this data to your server via an AJAX call
    // Example:
    fetch('book_appointment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `date=${dateInput}&time=${selectedTime}&visit_type=${visitType}&doctor_id=${currentDoctorId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Appointment booked successfully!');
            // Close modal and refresh calendar
            closeTheDoctorModal();
            window.location.reload();
        } else {
            // Show error message
            alert('Failed to book appointment: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while booking your appointment.');
    });
});

// Add this to your scripts.js file or before the </body> tag

document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const doctorModal = document.getElementById('doctorModal');
    const closeModal = document.getElementById('closeModal');
    
    // Function to open the modal with doctor information
    function openDoctorModal(doctorId, date) {
        // Fetch doctor data (in a real application, this would come from an AJAX call)
        // For now, we'll use sample data
        const doctorData = {
            id: doctorId || 1,
            name: "Dr. Sarah Johnson",
            specialty: "Psychotherapist",
            image: "/api/placeholder/800/400",
            rating: 4.8,
            reviews: 124,
            bio: "Dr. Sarah Johnson is a licensed psychotherapist with over 10 years of experience specializing in anxiety disorders, depression, and relationship issues. She takes a holistic approach to therapy, combining cognitive-behavioral techniques with mindfulness practices.",
            tags: ["Anxiety", "Depression", "Relationships", "CBT", "Mindfulness"],
            education: [
                "Ph.D. in Clinical Psychology, Stanford University",
                "M.A. in Counseling Psychology, Columbia University",
                "Licensed Psychotherapist (License #12345)"
            ],
            reviewsList: [
                {name: "Michael R.", rating: 5, comment: "Dr. Johnson helped me overcome my anxiety. Her approach is compassionate and effective."},
                {name: "Jennifer L.", rating: 4, comment: "Very professional and knowledgeable. I've made great progress in our sessions."}
            ]
        };
        
        // Update modal content with doctor information
        document.getElementById('modalDoctorName').textContent = doctorData.name;
        document.getElementById('modalDoctorSpecialty').textContent = doctorData.specialty;
        document.getElementById('modalDoctorImage').src = doctorData.image;
        document.getElementById('modalDoctorRating').textContent = doctorData.rating;
        document.getElementById('modalDoctorReviews').textContent = `(${doctorData.reviews} reviews)`;
        document.getElementById('modalDoctorBio').textContent = doctorData.bio;
        
        // Clear and populate tags
        const tagsContainer = document.getElementById('modalDoctorTags');
        tagsContainer.innerHTML = '';
        doctorData.tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm';
            tagElement.textContent = tag;
            tagsContainer.appendChild(tagElement);
        });
        
        // Clear and populate education
        const educationList = document.getElementById('modalDoctorEducation');
        educationList.innerHTML = '';
        doctorData.education.forEach(item => {
            const listItem = document.createElement('li');
            listItem.className = 'flex items-start';
            listItem.innerHTML = `
                <span class="mr-2 mt-0.5 text-blue-500">•</span>
                <span>${item}</span>
            `;
            educationList.appendChild(listItem);
        });
        
        // Clear and populate reviews
        const reviewsList = document.getElementById('modalDoctorReviewsList');
        reviewsList.innerHTML = '';
        doctorData.reviewsList.forEach(review => {
            const reviewElement = document.createElement('div');
            reviewElement.className = 'border-b border-slate-200 pb-4';
            
            // Create stars based on rating
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += i <= review.rating ? '★' : '☆';
            }
            
            reviewElement.innerHTML = `
                <div class="flex items-center mb-1">
                    <span class="font-medium text-slate-900">${review.name}</span>
                    <span class="ml-2 text-amber-400 text-sm">${stars}</span>
                </div>
                <p class="text-slate-600 text-sm">${review.comment}</p>
            `;
            reviewsList.appendChild(reviewElement);
        });
        
        // If a date was passed, set it in the date input
        if (date) {
            const dateInput = doctorModal.querySelector('input[type="date"]');
            if (dateInput) {
                dateInput.value = date;
                
                // You might want to fetch available time slots for this date
                // This would typically be an AJAX call to your backend
                updateTimeSlots(date);
            }
        }
        
        // Show the modal
        doctorModal.classList.remove('hidden');
    }
    
    // Function to update time slots based on selected date
    function updateTimeSlots(date) {
        // In a real application, this would be an AJAX call to check availability
        // For now, we'll just show some sample time slots
        console.log("Fetching time slots for:", date);
        
        // This could be replaced with real data from your server
        const availableSlots = ["09:00 AM", "10:00 AM", "11:30 AM", "02:00 PM", "04:15 PM"];
        const bookedSlots = ["03:30 PM"]; // Example of already booked slots
        
        // Update the time slots in the modal
        const timeSlotsContainer = doctorModal.querySelector('.grid.grid-cols-2.gap-2');
        if (timeSlotsContainer) {
            timeSlotsContainer.innerHTML = '';
            
            // Add available slots
            availableSlots.forEach(slot => {
                const button = document.createElement('button');
                button.className = 'px-3 py-2 bg-white hover:bg-blue-600 hover:text-white text-slate-700 text-sm border border-slate-300 rounded-lg transition-colors';
                button.textContent = slot;
                button.onclick = function() {
                    // Deselect all other time slots
                    timeSlotsContainer.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-white', 'text-slate-700');
                    });
                    
                    // Select this time slot
                    this.classList.remove('bg-white', 'text-slate-700');
                    this.classList.add('bg-blue-600', 'text-white');
                };
                timeSlotsContainer.appendChild(button);
            });
            
            // Add booked slots (disabled)
            bookedSlots.forEach(slot => {
                const button = document.createElement('button');
                button.className = 'px-3 py-2 bg-slate-200 text-slate-400 text-sm border border-slate-300 rounded-lg cursor-not-allowed';
                button.textContent = slot;
                timeSlotsContainer.appendChild(button);
            });
        }
    }
    
    // Function to close the modal
    function closeTheDoctorModal() {
        doctorModal.classList.add('hidden');
    }
    
    // Event listener for closing the modal
    if (closeModal) {
        closeModal.addEventListener('click', closeTheDoctorModal);
    }
    
    // Add event listener to close modal when clicking outside the content
    doctorModal.addEventListener('click', function(event) {
        if (event.target === doctorModal) {
            closeTheDoctorModal();
        }
    });
    
    // Expose these functions globally
    window.openDoctorModal = openDoctorModal;
    window.closeTheDoctorModal = closeTheDoctorModal;
});


// When opening the modal, set therapist ID and time slots
function openDoctorModal(therapistId, availableTimes) {
    document.getElementById('therapist_id').value = therapistId;
    
    // Populate time slots
    const timeSlotsContainer = document.getElementById('timeSlots');
    timeSlotsContainer.innerHTML = '';
    
    availableTimes.forEach(time => {
        timeSlotsContainer.innerHTML += `
            <label class="time-slot-button">
                <input type="radio" name="appointment_time" value="${time}" required class="hidden">
                <div class="px-3 py-2 bg-white hover:bg-blue-600 text-center text-slate-700 text-sm border border-slate-300 rounded-lg cursor-pointer transition-colors">
                    ${time}
                </div>
            </label>
        `;
    });
}

// Style selected time slot
document.addEventListener('click', function(e) {
    if (e.target.closest('.time-slot-button')) {
        document.querySelectorAll('.time-slot-button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
        });
        e.target.closest('.time-slot-button').classList.add('bg-blue-600', 'text-white');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle opening the doctor modal and setting the therapist_id
    const openModalButtons = document.querySelectorAll('.open-doctor-modal');
    if (openModalButtons) {
        openModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const therapistId = this.getAttribute('data-therapist-id');
                document.getElementById('therapist_id').value = therapistId;
                
                // Fetch and display therapist details (you'd need to implement this)
                fetchTherapistDetails(therapistId);
                
                // Show the modal
                document.getElementById('doctorModal').classList.remove('hidden');
            });
        });
    }
    
    // Handle closing the modal
    const closeModal = document.getElementById('closeModal');
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            document.getElementById('doctorModal').classList.add('hidden');
        });
    }
    
    // Handle time slot selection
    const timeSlots = document.querySelectorAll('.time-slot');
    if (timeSlots) {
        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                // Remove selected class from all time slots
                timeSlots.forEach(s => {
                    s.classList.remove('bg-blue-600', 'text-white');
                    s.classList.add('bg-white', 'text-slate-700');
                });
                
                // Add selected class to clicked time slot
                this.classList.remove('bg-white', 'text-slate-700');
                this.classList.add('bg-blue-600', 'text-white');
                
                // Set the value in the hidden input
                document.getElementById('appointment_time').value = this.getAttribute('data-time');
            });
        });
    }
    
    // Form validation before submit
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            const date = document.getElementById('appointment_date').value;
            const time = document.getElementById('appointment_time').value;
            
            if (!date) {
                e.preventDefault();
                alert('Please select a date for your appointment.');
                return false;
            }
            
            if (!time) {
                e.preventDefault();
                alert('Please select a time slot for your appointment.');
                return false;
            }
            
            // Additional validation can be added here
            
            return true;
        });
    }
    
    // Add date picker min date (prevent past dates)
    const datePicker = document.getElementById('appointment_date');
    if (datePicker) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayString = `${yyyy}-${mm}-${dd}`;
        datePicker.setAttribute('min', todayString);
    }
});

// Function to fetch therapist details (implement this)
function fetchTherapistDetails(therapistId) {
    // You can use AJAX to fetch details from a PHP endpoint
    // For example:
    /*
    fetch('get_therapist_details.php?id=' + therapistId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalDoctorName').textContent = data.full_name;
            document.getElementById('modalDoctorSpecialty').textContent = data.specialization;
            // Update other elements as needed
        });
    */
    
    // For now, you can use placeholder data from your database
    const therapists = {
        1: { name: "Dr. Jane Smith", specialty: "Cognitive Behavioral Therapy" },
        2: { name: "Dr. Michael Johnson", specialty: "Family Therapy" },
        3: { name: "Dr. Sarah Wilson", specialty: "Trauma Therapy" }
    };
    
    const therapist = therapists[therapistId] || { name: "Unknown", specialty: "Therapist" };
    
    document.getElementById('modalDoctorName').textContent = therapist.name;
    document.getElementById('modalDoctorSpecialty').textContent = therapist.specialty;
}