
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const searchInput = document.getElementById('searchInput');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const clearSearchBtn = document.getElementById('clearSearch');
    const userCountElement = document.getElementById('userCount');
    
    // Initially hide the loading indicator
    loadingIndicator.style.display = 'none';
    
    // Function to perform the search
    function performSearch(query) {
        // Show loading indicator
        loadingIndicator.style.display = 'block';
        
        // Convert query to lowercase for case-insensitive search
        query = query.toLowerCase();
        
        // Get all table rows (getting them here ensures we have the latest rows)
        const tableBody = document.querySelector('tbody');
        const tableRows = tableBody.querySelectorAll('tr');
        
        let visibleCount = 0;
        
        // Loop through all table rows
        tableRows.forEach(row => {
            // Get the text content we want to search in
            // We need to be careful with the cell selection because of the structure
            const nameCell = row.querySelector('td:nth-child(1)');
            const emailCell = row.querySelector('td:nth-child(2)');
            const roleCell = row.querySelector('td:nth-child(3)');
            
            // Skip the "No users found" row
            if (!nameCell || !emailCell || !roleCell) {
                row.style.display = query ? 'none' : '';
                return;
            }
            
            // Extract text content, being careful to get the actual text, not just the first element
            const name = nameCell.textContent.toLowerCase();
            const email = emailCell.textContent.toLowerCase();
            const role = roleCell.textContent.toLowerCase();
            
            // Check if any cell contains the search query
            if (name.includes(query) || email.includes(query) || role.includes(query)) {
                row.style.display = ''; // Show the row
                visibleCount++;
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
        
        // Show the "No results" row if needed
        if (visibleCount === 0 && query !== '') {
            // If no matching rows and there isn't already a "no results" row
            const noResultsRow = tableBody.querySelector('.no-results-row');
            if (!noResultsRow) {
                const newRow = document.createElement('tr');
                newRow.className = 'no-results-row';
                newRow.innerHTML = `
                    <td colspan="4" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-users-slash text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                            <h3 class="text-base font-medium text-slate-700 dark:text-slate-300">No users found</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Try adjusting your search criteria</p>
                        </div>
                    </td>
                `;
                tableBody.appendChild(newRow);
            } else {
                noResultsRow.style.display = '';
            }
        } else {
            // Hide the "no results" row if it exists
            const noResultsRow = tableBody.querySelector('.no-results-row');
            if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        }
        
        // Update the count
        if (userCountElement) {
            userCountElement.textContent = visibleCount;
        }
        
        // Hide loading indicator after a slight delay to make it visible
        setTimeout(function() {
            loadingIndicator.style.display = 'none';
        }, 300);
    }
    
    // Add event listener for input changes
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            performSearch(query);
        });
    } else {
        console.error("Search input element not found!");
    }
    
    // Handle clear button click
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                performSearch(''); // Search with empty string to show all rows
                searchInput.focus();
            }
        });
    } else {
        console.error("Clear button element not found!");
    }
    
    // Set initial count
    const initialRows = document.querySelectorAll('tbody tr:not(.no-results-row)');
    if (userCountElement && initialRows.length > 0) {
        userCountElement.textContent = initialRows.length;
    }
    
    console.log("Search functionality initialized");
});

// Toast notification function
function showToast(message, type = 'success') {
    // Toast container
    const toastContainer = document.getElementById('toast-container');
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'fa-check-circle';
    let title = 'Success';
    
    if (type === 'error') {
        icon = 'fa-exclamation-circle';
        title = 'Error';
    } else if (type === 'info') {
        icon = 'fa-info-circle';
        title = 'Information';
    }
    
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fa-solid ${icon}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
    `;
    
    // Add to container
    toastContainer.appendChild(toast);
    
    // Remove after animation completes
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// No modal functions needed as user management is removed

// Refresh User Table
function refreshUserTable() {
    // Show refresh animation
    const refreshIcon = document.getElementById('refreshIcon');
    refreshIcon.classList.add('refresh-pulse');
    
    // Reload the page data
    fetch(window.location.pathname + '?refresh=1')
        .then(response => response.text())
        .then(html => {
            // Create a temporary DOM element to parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            // Extract the table body content
            const newTableBody = tempDiv.querySelector('#userTableBody');
            if (newTableBody) {
                // Replace the existing table body with the new one
                const currentTableBody = document.getElementById('userTableBody');
                currentTableBody.innerHTML = newTableBody.innerHTML;
                
                // Update the user count
                const userCount = tempDiv.querySelector('#userCount');
                if (userCount) {
                    document.getElementById('userCount').textContent = userCount.textContent;
                }
                
                // Show success toast
                showToast('User list refreshed successfully');
            }
            
            // Remove refresh animation
            refreshIcon.classList.remove('refresh-pulse');
        })
        .catch(error => {
            console.error('Error refreshing user table:', error);
            showToast('Failed to refresh user list', 'error');
            
            // Remove refresh animation
            refreshIcon.classList.remove('refresh-pulse');
        });
}

// No form submission handlers needed as user management is removed

// Auto-refresh on load
window.addEventListener('load', function() {
    // Set up auto-refresh every 2 minutes (120000 ms)
    setInterval(function() {
        refreshUserTable();
    }, 120000);
});
