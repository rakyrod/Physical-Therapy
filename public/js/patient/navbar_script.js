
// Initialize components
document.addEventListener('DOMContentLoaded', function() {
  // Text area auto-height function
  function textareaAutoHeight(el, offsetTop = 0) {
    el.style.height = 'auto';
    el.style.height = `${el.scrollHeight + offsetTop}px`;
  }

  // Apply to textareas
  (function () {
    const textareas = [
      '#hs-tac-message'
    ];

    textareas.forEach((el) => {
      const textarea = document.querySelector(el);
      if (textarea) {
        const overlay = textarea.closest('.hs-overlay');

        if (overlay) {
          const HSOverlay = window.HSOverlay;
          if (HSOverlay) {
            const { element } = HSOverlay.getInstance(overlay, true);
            if (element && element.on) {
              element.on('open', () => textareaAutoHeight(textarea, 3));
            }
          }
        } else textareaAutoHeight(textarea, 3);

        textarea.addEventListener('input', () => {
          textareaAutoHeight(textarea, 3);
        });
      }
    });
  })();
  
  // Mobile menu toggle
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const navbarCollapse = document.getElementById('navbar-collapse');
  
  if (mobileMenuToggle && navbarCollapse) {
    mobileMenuToggle.addEventListener('click', function() {
      navbarCollapse.classList.toggle('hidden');
      navbarCollapse.classList.toggle('mobile-active');
    });
  }

  
  // Search overlay functionality
  const searchToggle = document.getElementById('searchToggle');
  const searchOverlay = document.getElementById('searchOverlay');
  const closeSearch = document.getElementById('closeSearch');
  
  if (searchToggle && searchOverlay && closeSearch) {
    searchToggle.addEventListener('click', function() {
      searchOverlay.classList.remove('-translate-y-full');
      setTimeout(function() {
        const searchInput = searchOverlay.querySelector('input');
        if (searchInput) searchInput.focus();
      }, 300);
    });
    
    closeSearch.addEventListener('click', function() {
      searchOverlay.classList.add('-translate-y-full');
    });
    
    // Close search on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !searchOverlay.classList.contains('-translate-y-full')) {
        searchOverlay.classList.add('-translate-y-full');
      }
    });
  }
  
  // Scroll progress indicator
  const scrollIndicator = document.getElementById('scrollIndicator');
  if (scrollIndicator) {
    window.addEventListener('scroll', function() {
      const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = (winScroll / height) * 100;
      scrollIndicator.style.width = scrolled + '%';
    });
  }
  
  // Updated for the new layered navbar structure
  window.addEventListener('scroll', function() {
    const navbarContainer = document.querySelector('.navbar-container');
    const navbarGlow = document.querySelector('.navbar-glow');
    
    if (!navbarContainer || !navbarGlow) return;
    
    const scrollY = window.scrollY;
    
    if (scrollY > 50) {
      // Enhanced glow on scroll
      navbarGlow.style.boxShadow = '0 0 20px 2px rgba(96, 165, 250, 0.5)';
      navbarGlow.style.borderColor = 'rgba(255, 255, 255, 0.15)';
      navbarContainer.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1)';
    } else {
      // Reset to original glow
      navbarGlow.style.boxShadow = '';
      navbarGlow.style.borderColor = '';
      navbarContainer.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
    }
  });
  
  // Responsive adjustments based on screen size
  function handleResponsiveLayout() {
    const width = window.innerWidth;
    const navItems = document.querySelectorAll('.nav-item');
    
    // On smaller screens, add more compact styling
    if (width < 1024 && width >= 768) {
      navItems.forEach(item => {
        const svg = item.querySelector('svg');
        const text = item.textContent.trim();
        
        // Store original content for restoration
        if (!item.dataset.originalContent) {
          item.dataset.originalContent = item.innerHTML;
        }
        
        // If we're on a medium screen, just show the icon with tooltip
        if (svg && text) {
          item.innerHTML = '';
          item.appendChild(svg);
          item.title = text;
        }
      });
    } else {
      // Restore original content
      navItems.forEach(item => {
        if (item.dataset.originalContent) {
          item.innerHTML = item.dataset.originalContent;
        }
      });
    }
  }
  
  // Run on load and resize
  handleResponsiveLayout();
  window.addEventListener('resize', handleResponsiveLayout);

  // Standard dropdown functionality
  // For standard dropdowns
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      // Find the associated dropdown menu
      const dropdownMenu = this.nextElementSibling || 
                         this.parentElement.querySelector('.dropdown-menu');
      
      // Toggle the show class
      if (dropdownMenu) {
        dropdownMenu.classList.toggle('show');
      }
    });
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    const dropdowns = document.querySelectorAll('.dropdown-menu.show, .user-dropdown-menu.show');
    dropdowns.forEach(dropdown => {
      if (!dropdown.contains(e.target) && 
          !e.target.classList.contains('dropdown-toggle') && 
          !e.target.classList.contains('user-dropdown-button')) {
        dropdown.classList.remove('show');
      }
    });
  });
  
  // Enhanced logo interactions with animated elements
  const logoHover = document.querySelector('.logo-hover');
  if (logoHover) {
    // Add enhanced hover interaction
    logoHover.addEventListener('mouseenter', () => {
      // Pause the continuous animations
      const bone = document.querySelector('.bone-icon');
      const logoText = document.querySelector('.logo-text');
      const logoSubtitle = document.querySelector('.logo-subtitle');
      
      if (bone) {
        bone.style.animationPlayState = 'paused';
        // Apply hover animation
        bone.style.transform = 'rotate(8deg) scale(1.1)';
        bone.style.transition = 'transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
      }
      
      if (logoText) {
        logoText.style.animationPlayState = 'paused';
        logoText.style.fill = '#94c6ff';
        logoText.style.transition = 'fill 0.3s ease';
      }
      
      if (logoSubtitle) {
        logoSubtitle.style.animationPlayState = 'paused';
        logoSubtitle.style.opacity = '1';
      }
    });
    
    logoHover.addEventListener('mouseleave', () => {
      // Resume the animations
      const bone = document.querySelector('.bone-icon');
      const logoText = document.querySelector('.logo-text');
      const logoSubtitle = document.querySelector('.logo-subtitle');
      
      if (bone) {
        bone.style.transform = '';
        bone.style.transition = '';
        // Need a small delay before resuming animation to avoid jumps
        setTimeout(() => {
          bone.style.animationPlayState = 'running';
        }, 50);
      }
      
      if (logoText) {
        logoText.style.fill = '';
        logoText.style.transition = '';
        setTimeout(() => {
          logoText.style.animationPlayState = 'running';
        }, 50);
      }
      
      if (logoSubtitle) {
        logoSubtitle.style.opacity = '';
        setTimeout(() => {
          logoSubtitle.style.animationPlayState = 'running';
        }, 50);
      }
    });
  }
});
