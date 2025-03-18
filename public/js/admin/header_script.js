
  document.addEventListener("DOMContentLoaded", function () {
    // Existing section handling
    const sections = {
      "dashboard": { title: "Dashboard", description: "Overview of system statistics" },
      "appointments": { title: "Appointments", description: "Manage appointment schedules" },
      "therapists": { title: "Therapists", description: "View and manage therapists" },
      "patients": { title: "Patients", description: "Manage patient records" },
      "reports": { title: "Reports", description: "View system reports" },
      "settings": { title: "Settings", description: "Manage system settings" },
    };

    function updateHeader(section) {
      const pageTitle = document.getElementById("page-title");
      const pageDescription = document.getElementById("page-description");
      if (sections[section]) {
        pageTitle.textContent = sections[section].title;
        pageDescription.textContent = sections[section].description;
      }
    }

    document.querySelectorAll("[data-section]").forEach(link => {
      link.addEventListener("click", function () {
        const section = this.getAttribute("data-section");
        updateHeader(section);
      });
    });

    // Set initial section based on active link
    const activeLink = document.querySelector("[data-section].active");
    if (activeLink) {
      updateHeader(activeLink.getAttribute("data-section"));
    }
    
    // Notification dropdown functionality
    const notificationButton = document.getElementById("notification-button");
    const notificationDropdown = document.getElementById("notification-dropdown");
    
    if (notificationButton && notificationDropdown) {
      // Toggle dropdown when notification button is clicked
      notificationButton.addEventListener("click", function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle("hidden");
        
        // If dropdown is visible, mark notifications as read
        if (!notificationDropdown.classList.contains("hidden")) {
          // Create form data
          const formData = new FormData();
          formData.append('mark_read', 'true');
          
          // Send AJAX request
          fetch(window.location.href, {
            method: "POST",
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Hide notification indicator
              const notificationIndicator = notificationButton.querySelector("span.bg-red-500");
              if (notificationIndicator) {
                notificationIndicator.classList.add("hidden");
              }
            }
          })
          .catch(error => console.error("Error marking notifications as read:", error));
        }
      });
      
      // Close dropdown when clicking elsewhere
      document.addEventListener("click", function(e) {
        if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.add("hidden");
        }
      });
    }
  });
