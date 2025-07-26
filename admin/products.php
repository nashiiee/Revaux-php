<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products</title>

  <!-- Poppins Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Playfair Display Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <!-- Stylesheet -->
  <link rel="stylesheet" href="../dist/products.css">
  <!-- Font Awesome Free CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>
<body style="background-color: #F4F8FA">
  <aside>
    <div class="logo">
      <img src="./images/logo.png" alt="Revaux Logo">
      <span>Revaux</span>
    </div>
    <div class="links">
      <ul class="nav-links">
        <li class="">
          <a href="dashboard.html">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="transactions.html">
            <i class="fa-solid fa-credit-card"></i>
            <span>Transactions</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-chart-line"></i>
            <span>Sales</span>
          </a>
        </li>
        <li class="active">
          <a href="products.html">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
          </a>
        </li>
      </ul>
      <span class="tools-description">Tools</span>
      <ul class="nav-links">
        <li>
          <a href="">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-user-gear"></i>
            <span>Manage User</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="logout-container">
      <a href="" class="logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  <header>
    <input class="search" type="search" placeholder="Search">
    <div class="admin-header-side">
      <div class="image-container"></div>
      <span>Admin</span>
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </header>
  <main>
    <div class="main-section-container">
      <div class="header-filter-products">
        <div class="option-container">
          <div class="category-container options">
            <label for="category">Category</label>
            <select name="category" id="category" class="select-options">
              <option value="all">All Categories</option>
              <option value="1">Headwear</option>
              <option value="2">Tops</option>
              <option value="3">Bottoms</option>
              <option value="4">Footwear</option>
            </select>
          </div>
          <div class="status-container options">
            <label for="status">Status</label>
            <select name="status" id="status" class="select-options">
              <option value="status" selected>All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="price-container options">
            <label for="price">Price</label>
            <select name="price" id="price" class="select-options">
              <option value="all">All Prices</option>
              <option value="0-500">₱0 - ₱500</option>
              <option value="501-1000">₱501 - ₱1,000</option>
              <option value="1001-2000">₱1,001 - ₱2,000</option>
              <option value="2001+">₱2,001+</option>
            </select>
          </div>
        </div>
        <div class="add-product-container-filter">
          <div class="filter-container">
            <i class="fa-solid fa-filter"></i>
            <span>Filter</span>
          </div>
          <button class="add-product-btn">
            <span>Add Product</span>
          </button>
        </div>
      </div>
      
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php include './products/show_products.php'; ?>
        </tbody>
      </table>
    </div>
  </main>

  <script>
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
  </script>
 
</body>
</html>