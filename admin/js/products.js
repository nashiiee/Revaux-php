// Check for success/error messages on page load
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const error = urlParams.get('error');
  
  if (success) {
    let message = '';
    switch(success) {
      case 'product_deleted':
        message = 'Product deleted successfully!';
        break;
      case 'product_added':
        message = 'Product added successfully!';
        break;
      default:
        message = 'Operation completed successfully!';
    }
    showFilterSuccess(message);
    // Clear the URL parameter
    window.history.replaceState({}, document.title, window.location.pathname);
  }
  
  if (error) {
    let message = '';
    switch(error) {
      case 'product_not_found':
        message = 'Product not found!';
        break;
      case 'delete_failed':
        message = 'Failed to delete product!';
        break;
      case 'database_error':
        message = 'Database error occurred!';
        break;
      case 'invalid_request':
        message = 'Invalid request!';
        break;
      default:
        message = 'An error occurred!';
    }
    alert('Error: ' + message);
    // Clear the URL parameter
    window.history.replaceState({}, document.title, window.location.pathname);
  }

  // Search functionality
  const searchInput = document.querySelector('.search');
  
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();
      const tableRows = document.querySelectorAll('tbody tr');
      
      tableRows.forEach(row => {
        const productNameCell = row.cells[0]; // Product name is in the first column
        const productName = productNameCell.textContent.toLowerCase();
        
        if (productName.includes(searchTerm)) {
          row.style.display = '';
          row.classList.remove('fade-out');
        } else {
          row.style.display = 'none';
          row.classList.add('fade-out');
        }
      });
      
      // Show filter success message if search is active
      if (searchTerm.length > 0) {
        showFilterSuccess(`Searching for: "${this.value}"`);
      }
    });
  }
});

// Delete product function
function deleteProduct(productId) {
  if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
    fetch('../../data/delete_product.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'product_id=' + productId
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showFilterSuccess('Product deleted successfully!');
        // Reload the page to refresh the product list
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while deleting the product.');
    });
  }
}

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
      const categoryCell = row.cells[2]; // Category is the 3rd column (index 2)
      const priceCell = row.cells[1]; // Price is the 2nd column (index 1)
      const statusCell = row.cells[5]; // Status is the 6th column (index 5)
      
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
      
      if (categoryMatch && priceMatch && statusMatch) {
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
    showFilterMessage(selectedCategory, selectedStatus, selectedPrice, visibleCount);
    
    // Remove loading state
    document.getElementById('category').classList.remove('loading');
    document.getElementById('status').classList.remove('loading');
    document.getElementById('price').classList.remove('loading');
  }, 150);
}

// Function to show filter success message
function showFilterMessage(category, status, price, count) {
  // Remove existing message
  const existingMessage = document.querySelector('.filter-success');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  // Create new message
  const message = document.createElement('div');
  message.className = 'filter-success';
  
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
  
  let filterText = `Showing ${count} products`;
  if (category !== 'all') {
    filterText += ` in ${categoryNames[category]}`;
  }
  if (price && price !== 'all') {
    filterText += ` priced ${priceNames[price]}`;
  }
  if (status && status !== 'status') {
    filterText += ` with ${statusNames[status]} status`;
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

// Add staggered animation on page load
window.addEventListener('DOMContentLoaded', function() {
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