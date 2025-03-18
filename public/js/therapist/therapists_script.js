
// Show/hide scroll to top button
window.addEventListener('scroll', function() {
    const scrollButton = document.getElementById('scrollToTop');
    if (window.scrollY > 300) {
        scrollButton.classList.add('opacity-100');
        scrollButton.classList.add('visible');
        scrollButton.classList.remove('opacity-0');
        scrollButton.classList.remove('invisible');
    } else {
        scrollButton.classList.remove('opacity-100');
        scrollButton.classList.remove('visible');
        scrollButton.classList.add('opacity-0');
        scrollButton.classList.add('invisible');
    }
});

// Scroll to top when button is clicked
document.getElementById('scrollToTop').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Simple filter functionality
document.querySelectorAll('.filter-section button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all filters
        document.querySelectorAll('.filter-section button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('bg-white', 'text-slate-800');
        });
        
        // Add active class to clicked filter
        this.classList.remove('bg-white', 'text-slate-800');
        this.classList.add('bg-blue-600', 'text-white');
    });
});
// Define therapist data with their specialties
const therapist = [
{
id: 1,
name: "Dr. Sarah Johnson",
specialty: "Cardiopulmonary",
rating: 4.8,
reviews: 124,
available: true,
element: null // Will store DOM reference
},
{
id: 2,
name: "Dr. Michael Chen",
specialty: "Orthopedic",
rating: 4.9,
reviews: 187,
available: true,
element: null
},
{
id: 3,
name: "Dr. Jennifer Williams",
specialty: "Neurological",
rating: 4.7,
reviews: 93,
available: false,
element: null
},
{
id: 9,
name: "Dr. Margaret Thompson",
specialty: "Geriatric",
rating: 4.6,
reviews: 108,
available: true,
element: null
}
];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
// Store references to DOM elements
const therapistCards = document.querySelectorAll('.therapist-card');
const filterButtons = document.querySelectorAll('.container .bg-white button');
const sortSelect = document.querySelector('select');
const therapistContainer = document.querySelector('main .grid');

// Map DOM elements to therapist data for easy reference
therapistCards.forEach((card, index) => {
if (index < therapist.length) {
    therapist[index].element = card;
}
});

// Add click event to all specialty filter buttons
filterButtons.forEach(button => {
button.addEventListener('click', function() {
    // Update button styles
    filterButtons.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-600/20');
        btn.classList.add('bg-white', 'text-slate-800');
    });
    
    this.classList.remove('bg-white', 'text-slate-800');
    this.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-600/20');
    
    // Get selected specialty
    const specialty = this.textContent.trim();
    
    // Filter the doctors
    filterTherapists(specialty);
});
});

// Add change event to sort dropdown
sortSelect.addEventListener('change', function() {
const sortValue = this.value;
sortTherapists(sortValue);
});

// Function to filter doctors by specialty
function filterTherapists(specialty) {
// If "All Specialties" is selected, show all doctors
if (specialty === "All Specialties") {
    therapists.forEach(therapist => {
        therapist.element.classList.remove('hidden');
        // Restart animation effect
        therapist.element.style.animation = 'none';
        setTimeout(() => {
            therapist.element.style.animation = '';
        }, 10);
    });
    return;
}

// Otherwise filter by specialty
therapist.forEach(therapist => {
    if (therapist.specialty === specialty) {
        therapist.element.classList.remove('hidden');
        // Restart animation effect
        therapist.element.style.animation = 'none';
        setTimeout(() => {
            therapist.element.style.animation = '';
        }, 10);
    } else {
        therapist.element.classList.add('hidden');
    }
});

// If no doctors match, show a message
const visibleTherapists = therapists.filter(d => d.specialty === specialty);
if (visibleTherapists.length === 0) {
    // Create "no results" element if not exists
    let noResults = document.getElementById('no-results');
    if (!noResults) {
        noResults = document.createElement('div');
        noResults.id = 'no-results';
        noResults.className = 'col-span-full text-center py-12';
        noResults.innerHTML = `
            <div class="mx-auto w-24 h-24 text-slate-300 mb-4">
                <i class="fas fa-search text-5xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-800 mb-2">No Therapists found</h3>
            <p class="text-slate-500">We couldn't find any doctors with the selected specialty.</p>
        `;
        therapistContainer.appendChild(noResults);
    } else {
        noResults.classList.remove('hidden');
    }
} else {
    // Hide "no results" message if it exists
    const noResults = document.getElementById('no-results');
    if (noResults) {
        noResults.classList.add('hidden');
    }
}
}

// Function to sort therapist
function sortTherapist(sortBy) {
// Remove all therapist cards from the container
therapist.forEach(therapist => {
    if (therapist.element.parentNode) {
        therapist.element.remove();
    }
});

// Sort the therapists array
let sortedTherapists = [...therapists];

switch(sortBy) {
    case 'rating':
        sortedTherapists.sort((a, b) => b.rating - a.rating);
        break;
    case 'name-asc':
        sortedTherapists.sort((a, b) => a.name.localeCompare(b.name));
        break;
    case 'name-desc':
        sortedTherapists.sort((a, b) => b.name.localeCompare(a.name));
        break;
    case 'relevance':
    default:
        // For relevance, prioritize available therapist
        sortedTherapists.sort((a, b) => {
            if (a.available === b.available) return 0;
            return a.available ? -1 : 1;
        });
        break;
}

// Add sorted doctor cards back to the container
sortedTherapists.forEach(therapist => {
    if (!therapist.element.classList.contains('hidden')) {
        therapistContainer.appendChild(therapist.element);
    }
});
}

// Initialize the page with default sorting
sortTherapist('relevance');
});
// Function to initialize dynamic filters based on therapist data
function initializeDynamicFilters() {
// Reference to filter buttons container
const filterContainer = document.querySelector('.container .flex-wrap:first-child');
const secondFilterContainer = document.querySelector('.container .flex-wrap:nth-child(2)');

if (!filterContainer || !secondFilterContainer) {
console.error("Filter containers not found");
return;
}

// Clear existing filter buttons except "All Specialties"
const allSpecialtiesButton = filterContainer.querySelector("button");
if (allSpecialtiesButton) {
filterContainer.innerHTML = '';
filterContainer.appendChild(allSpecialtiesButton);
}
secondFilterContainer.innerHTML = '';

// Create a set to store unique specializations
const specializations = new Set();

// Collect all specializations from therapist data
if (typeof therapistsData !== 'undefined') {
Object.values(therapistsData).forEach(therapist => {
    if (therapist.specialization) {
        // Extract simple name (without "Physical Therapy")
        let simpleName = therapist.specialization.replace('Physical Therapy', '').trim();
        if (!simpleName) simpleName = therapist.specialization;
        specializations.add(simpleName);
    }
});
} else if (typeof therapist !== 'undefined') {
// If using the therapist array from the original code
therapist.forEach(doc => {
    if (doc.specialty) {
        specializations.add(doc.specialty);
    }
});
}

// Convert set to array and sort alphabetically
const sortedSpecializations = Array.from(specializations).sort();

// Split specializations between the two containers
const halfLength = Math.ceil(sortedSpecializations.length / 2);

// Add first half to first container
sortedSpecializations.slice(0, halfLength).forEach(specialty => {
const button = document.createElement('button');
button.className = 'px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1';
button.textContent = specialty;
button.addEventListener('click', function() {
    filterTherapists(this.textContent.trim());
});
filterContainer.appendChild(button);
});

// Add second half to second container
sortedSpecializations.slice(halfLength).forEach(specialty => {
const button = document.createElement('button');
button.className = 'px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1';
button.textContent = specialty;
button.addEventListener('click', function() {
    filterTherapists(this.textContent.trim());
});
secondFilterContainer.appendChild(button);
});

// Make sure the "All Specialties" button has the correct event listener
if (allSpecialtiesButton) {
allSpecialtiesButton.addEventListener('click', function() {
    filterTherapists("All Specialties");
});
}
}

// Function to filter therapists by specialty
function filterTherapists(specialty) {
// Update button styles
const filterButtons = document.querySelectorAll('.container button');
filterButtons.forEach(btn => {
btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-600/20');
btn.classList.add('bg-white', 'text-slate-800');
});

// Find and style the selected button
const selectedButton = Array.from(filterButtons).find(btn => btn.textContent.trim() === specialty);
if (selectedButton) {
selectedButton.classList.remove('bg-white', 'text-slate-800');
selectedButton.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-600/20');
}

// Get all therapist cards
const therapistCards = document.querySelectorAll('.therapist-card, .doctor-card');
if (!therapistCards.length) {
console.warn("No therapist cards found with class 'therapist-card' or 'doctor-card'");
return;
}

// If "All Specialties" is selected, show all therapists
if (specialty === "All Specialties") {
therapistCards.forEach(card => {
    card.classList.remove('hidden');
    // Restart animation effect
    card.style.animation = 'none';
    setTimeout(() => {
        card.style.animation = '';
    }, 10);
});

// Hide "no results" message if it exists
const noResults = document.getElementById('no-results');
if (noResults) {
    noResults.classList.add('hidden');
}
return;
}

// Keep track of whether any therapists match the filter
let foundMatch = false;

// Filter cards based on the specialty text in their content
therapistCards.forEach(card => {
// Look for specialty text in the card
const cardSpecialty = card.querySelector('.bg-purple-100, .bg-cyan-100, .bg-pink-100, .bg-orange-100, .bg-green-100, .bg-blue-100, .bg-indigo-100, .bg-rose-100, .bg-amber-100, .bg-teal-100');
const hasMatch = cardSpecialty && cardSpecialty.textContent.trim() === specialty;

if (hasMatch) {
    card.classList.remove('hidden');
    // Restart animation
    card.style.animation = 'none';
    setTimeout(() => {
        card.style.animation = '';
    }, 10);
    foundMatch = true;
} else {
    card.classList.add('hidden');
}
});

// Get the container for therapist cards
const therapistContainer = therapistCards.length > 0 ? therapistCards[0].parentElement : null;

// Show "no results" message if no matches found
if (!foundMatch && therapistContainer) {
// Check if "no results" element exists
let noResults = document.getElementById('no-results');
if (!noResults) {
    // Create "no results" element
    noResults = document.createElement('div');
    noResults.id = 'no-results';
    noResults.className = 'col-span-full text-center py-12';
    noResults.innerHTML = `
        <div class="mx-auto w-24 h-24 text-slate-300 mb-4">
            <i class="fas fa-search text-5xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-slate-800 mb-2">No Therapists found</h3>
        <p class="text-slate-500">We couldn't find any therapists with the selected specialty.</p>
    `;
    therapistContainer.appendChild(noResults);
} else {
    noResults.classList.remove('hidden');
}
} else {
// Hide "no results" message if it exists
const noResults = document.getElementById('no-results');
if (noResults) {
    noResults.classList.add('hidden');
}
}
}

// Initialize the dynamic filters when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
// Call the initialization function
initializeDynamicFilters();

// Call filterTherapists with "All Specialties" to ensure everything is visible by default
setTimeout(() => {
filterTherapists("All Specialties");
}, 100);
});
