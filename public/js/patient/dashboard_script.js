

    // ============ Scroll Detection for Showing/Hiding the Scroll-Up Section ============
    let lastScrollTop = 0;
    const scrollUpPage = document.getElementById("scrollUpPage");

    window.addEventListener('scroll', function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        if (currentScroll < lastScrollTop) {
            // Scrolling UP - Show the hidden section
            scrollUpPage.classList.remove("hidden");
        } else {
            // Scrolling DOWN - Hide the section
            scrollUpPage.classList.add("hidden");
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });

    // ============ Dropdown Menu Toggle for Courses ============
    document.getElementById("coursesBtn").addEventListener("click", function() {
        let dropdown = document.getElementById("coursesDropdown");
        dropdown.classList.toggle("hidden");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
        let btn = document.getElementById("coursesBtn");
        let dropdown = document.getElementById("coursesDropdown");
        if (!btn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add("hidden");
        }
    });
