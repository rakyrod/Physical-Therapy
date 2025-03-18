
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const specialtiesGrid = document.getElementById('specialtiesGrid');
    const noResultsContainer = document.getElementById('noResultsContainer');
    
    // Map specialty names to their respective pages
    const pageMap = {
      <?php foreach($availableSpecializations as $specialization): ?>
      "<?php echo $specialization; ?>": "/treatments/<?php echo strtolower(explode(' ', $specialization)[0]); ?>.php"
      <?php endforeach; ?>
    };
    
    // Enhanced search functionality
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.specialty-item');
        let matchCount = 0;
        
        // Show/hide clear button
        if (filter.length > 0) {
          clearSearchBtn.classList.remove('hidden');
        } else {
          clearSearchBtn.classList.add('hidden');
        }
        
        items.forEach(item => {
          let text = item.innerText.toLowerCase();
          if (text.includes(filter)) {
            item.style.display = "block";
            item.style.opacity = "1";
            matchCount++;
          } else {
            item.style.opacity = "0";
            setTimeout(() => {
              item.style.display = "none";
            }, 300);
          }
        });
        
        // Show/hide no results message with animation
        if (matchCount === 0 && filter.length > 0) {
          noResultsContainer.innerHTML = '<div class="text-center py-10 px-6 text-gray-600 bg-white rounded-lg shadow-md border border-gray-100"><svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><p class="text-lg font-medium">No specialties match your search</p><p class="mt-2">Try different keywords or check our complete list of services.</p></div>';
          noResultsContainer.style.opacity = "1";
          noResultsContainer.style.maxHeight = "300px";
        } else {
          noResultsContainer.style.opacity = "0";
          setTimeout(() => {
            noResultsContainer.innerHTML = '';
            noResultsContainer.style.maxHeight = "0";
          }, 300);
        }
      });
      
      // Clear search function
      if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
          searchInput.value = '';
          searchInput.dispatchEvent(new Event('input'));
          searchInput.focus();
        });
      }
    }
    
    // Make cards clickable
    document.querySelectorAll('.specialty-card').forEach(card => {
      card.addEventListener('click', function() {
        const specialtyHeading = this.querySelector('h3');
        const specialtyName = specialtyHeading ? specialtyHeading.textContent.trim() : '';
        const targetPage = pageMap[specialtyName] || `/treatments/general.php?treatment=${encodeURIComponent(specialtyName)}`;
        window.location.href = targetPage;
      });
      
      // Style cursor to indicate clickable
      card.style.cursor = 'pointer';
      
      // Handle Learn More button click
      const learnMoreBtn = card.querySelector('.learn-more-btn');
      if (learnMoreBtn) {
        learnMoreBtn.addEventListener('click', function(e) {
          e.stopPropagation(); // Prevent the card's click event
          const specialtyHeading = card.querySelector('h3');
          const specialtyName = specialtyHeading ? specialtyHeading.textContent.trim() : '';
          const targetPage = pageMap[specialtyName] || `/treatments/general.php?treatment=${encodeURIComponent(specialtyName)}`;
          window.location.href = targetPage;
        });
      }
    });
  });
