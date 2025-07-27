// Search functionality with enhanced UX
document.querySelector('.search').addEventListener('input', function() {
  const searchIcon = document.querySelector('.search-icon');
  const searchInput = this;
  
  // Add visual feedback
  if (searchInput.value.trim()) {
    searchIcon.classList.add('active');
    searchInput.classList.add('has-value');
  } else {
    searchIcon.classList.remove('active');
    searchInput.classList.remove('has-value');
  }
  
  // Debounce search for better performance
  clearTimeout(searchInput.searchTimeout);
  searchInput.searchTimeout = setTimeout(() => {
    filterProducts();
  }, 300);
});

// Clear search functionality
document.querySelector('.search').addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    this.value = '';
    this.classList.remove('has-value');
    document.querySelector('.search-icon').classList.remove('active');
    filterProducts();
  }
});

// Category filtering functionality with smooth transitions
document.getElementById('category').addEventListener('change', function() {
  filterProducts();
});

// Status filtering functionality
document.getElementById('status').addEventListener('change', function() {
  filterProducts();
});

// Price filtering functionality
document.getElementById('price').addEventListener('change', function() {
  filterProducts();
});

// Combined filter function
function filterProducts() {
  const selectedCategory = document.getElementById('category').value;
  const selectedStatus = document.getElementById('status').value;
  const selectedPrice = document.getElementById('price').value;
  const searchQuery = document.querySelector('.search').value.toLowerCase().trim();
  const tableRows = document.querySelectorAll('tbody tr');
  
  // Add loading state
  document.getElementById('category').classList.add('loading');
  document.getElementById('status').classList.add('loading');
  document.getElementById('price').classList.add('loading');
  
  // First phase: Fade out all rows
  tableRows.forEach(row => {
    row.classList.add('fade-out');
  });
  
  // Wait for fade-out animation to complete
  setTimeout(() => {
    let visibleCount = 0;
    
    tableRows.forEach(row => {
      const productNameCell = row.cells[0]; // Product name is the 1st column (index 0)
      const categoryCell = row.cells[2]; // Category is the 3rd column (index 2)
      const priceCell = row.cells[1]; // Price is the 2nd column (index 1)
      const statusCell = row.cells[5]; // Status is the 6th column (index 5)
      
      // Check search filter - search in product name
      const productName = productNameCell.querySelector('.product-name') ? 
        productNameCell.querySelector('.product-name').textContent.toLowerCase() : 
        productNameCell.textContent.toLowerCase();
      const searchMatch = searchQuery === '' || productName.includes(searchQuery);
      
      // Check category filter - compare with category_id from database
      const categoryMatch = selectedCategory === 'all' || categoryCell.textContent.trim() === selectedCategory;
      
      // Check price filter
      let priceMatch = true;
      if (selectedPrice !== 'all') {
        const priceText = priceCell.textContent.trim();
        // Remove ₱ symbol and convert to number
        const price = parseFloat(priceText.replace('₱', '').replace(',', ''));
        
        switch (selectedPrice) {
          case '0-500':
            priceMatch = price >= 0 && price <= 500;
            break;
          case '501-1000':
            priceMatch = price >= 501 && price <= 1000;
            break;
          case '1001-2000':
            priceMatch = price >= 1001 && price <= 2000;
            break;
          case '2001+':
            priceMatch = price >= 2001;
            break;
          default:
            priceMatch = true;
        }
      }
      
      // Check status filter - look inside the span element
      let statusMatch = true;
      if (selectedStatus === 'active') {
        const statusSpan = statusCell.querySelector('.status-badge');
        statusMatch = statusSpan && statusSpan.textContent.trim().toLowerCase() === 'active';
      } else if (selectedStatus === 'inactive') {
        const statusSpan = statusCell.querySelector('.status-badge');
        statusMatch = statusSpan && statusSpan.textContent.trim().toLowerCase() === 'inactive';
      }
      
      if (searchMatch && categoryMatch && priceMatch && statusMatch) {
        // Show matching rows
        row.classList.remove('fade-out', 'hidden');
        row.classList.add('fade-in');
        row.style.display = '';
        visibleCount++;
      } else {
        // Hide non-matching rows
        row.classList.remove('fade-out', 'fade-in');
        row.classList.add('hidden');
        row.style.display = 'none';
      }
    });
    
    // Show success message with count
    const searchQuery = document.querySelector('.search').value.trim();
    showFilterMessage(selectedCategory, selectedStatus, selectedPrice, visibleCount, searchQuery);
    
    // Remove loading state
    document.getElementById('category').classList.remove('loading');
    document.getElementById('status').classList.remove('loading');
    document.getElementById('price').classList.remove('loading');
  }, 150);
}

// Function to show filter success message
function showFilterMessage(category, status, price, count, searchQuery = '', customMessage = '') {
  // Remove existing message
  const existingMessage = document.querySelector('.filter-success');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // Create new message
  const message = document.createElement('div');
  message.className = 'filter-success';
  
  let filterText = '';
  
  // Handle custom messages (like success notifications)
  if (customMessage) {
    filterText = customMessage;
  } else {
    const categoryNames = {
      'all': 'All Categories',
      '1': 'Headwear',
      '2': 'Tops', 
      '3': 'Bottoms',
      '4': 'Footwear'
    };

    const statusNames = {
      'status': 'All Status',
      'active': 'Active',
      'inactive': 'Inactive'
    };

    const priceNames = {
      'all': 'All Prices',
      '0-500': '₱0 - ₱500',
      '501-1000': '₱501 - ₱1,000',
      '1001-2000': '₱1,001 - ₱2,000',
      '2001+': '₱2,001+'
    };
    
    const hasFilters = category !== 'all' || status !== 'status' || price !== 'all' || searchQuery !== '';
    
    if (searchQuery && count === 0) {
      filterText = `No products found for "${searchQuery}"`;
    } else if (searchQuery) {
      filterText = `Found ${count} product(s) matching "${searchQuery}"`;
      if (category !== 'all' || status !== 'status' || price !== 'all') {
        filterText += ' with applied filters';
      }
    } else if (hasFilters) {
      filterText = `Showing ${count} products`;
      if (category !== 'all') {
        filterText += ` in ${categoryNames[category]}`;
      }
      if (price && price !== 'all') {
        filterText += ` priced ${priceNames[price]}`;
      }
      if (status && status !== 'status') {
        filterText += ` with ${statusNames[status]} status`;
      }
    } else {
      return; // Don't show message if no filters are applied
    }
  }
  
  message.textContent = filterText;
  document.body.appendChild(message);
  
  // Animate in
  setTimeout(() => message.classList.add('show'), 100);
  
  // Animate out after 3 seconds
  setTimeout(() => {
    message.classList.remove('show');
    setTimeout(() => message.remove(), 300);
  }, 3000);
}

// Add staggered animation on page load and handle success messages
window.addEventListener('DOMContentLoaded', function() {
  // Check for success messages on page load
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  
  if (success === 'product_added') {
    setTimeout(() => {
      showFilterMessage('all', 'status', 'all', 0, '', 'Product added successfully!');
    }, 500);
    // Clear the URL parameter
    window.history.replaceState({}, document.title, window.location.pathname);
  }
  
  // Existing animation code
  const tableRows = document.querySelectorAll('tbody tr');
  
  tableRows.forEach((row, index) => {
    row.style.opacity = '0';
    row.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      row.style.transition = 'all 0.4s ease-out';
      row.style.opacity = '1';
      row.style.transform = 'translateY(0)';
    }, index * 50);
  });
});

// Add smooth transitions to filter buttons
document.querySelector('.filter-container').addEventListener('click', function() {
  this.style.transform = 'scale(0.95)';
  setTimeout(() => {
    this.style.transform = 'scale(1)';
  }, 150);
});

document.querySelector('.add-product-btn').addEventListener('click', function() {
  this.style.transform = 'scale(0.95)';
  setTimeout(() => {
    this.style.transform = 'scale(1)';
  }, 150);
});