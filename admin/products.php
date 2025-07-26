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
<<<<<<< HEAD

  <style>
    .filter-success {
      position: fixed;
      top: u.rem(20);
      right: u.rem(20);
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
      padding: u.rem(12) u.rem(24);
      border-radius: u.rem(8);
      font-size: u.rem(14);
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      transform: translateX(u.rem(300));
      transition: transform 0.3s ease-in-out;
      z-index: 1000;

      &.show {
        transform: translateX(0);
      }
    }
  </style>
=======
>>>>>>> e95170b6eb6aed1a4e7b5043e3327fe6b8f16eda
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
<<<<<<< HEAD
              <option value="all">All Categories</option>
              <option value="1">Headwear</option>
              <option value="2">Tops</option>
              <option value="3">Bottoms</option>
              <option value="4">Footwear</option>
=======
              <option value="all" disabled selected>All</option>
              <option value="sunglasses">Headwear</option>
              <option value="eyeglasses">Tops</option>
              <option value="accessories">Bottoms</option>
              <option value="accessories">Footwear</option>
>>>>>>> e95170b6eb6aed1a4e7b5043e3327fe6b8f16eda
            </select>
          </div>
          <div class="status-container options">
            <label for="status">Status</label>
            <select name="status" id="status" class="select-options">
<<<<<<< HEAD
              <option value="status" selected>All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
=======
              <option value="status" disabled selected>Select Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
>>>>>>> e95170b6eb6aed1a4e7b5043e3327fe6b8f16eda
            </select>
          </div>
          <div class="price-container options">
            <label for="" id="price">Price</label>
            <select name="category" id="category" class="select-options">
              <option value="all">Select</option>
              <option value="sunglasses">Headwear</option>
              <option value="eyeglasses">Tops</option>
              <option value="accessories">Bottoms</option>
              <option value="accessories">Footwear</option>
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
<<<<<<< HEAD


    

  <script>
    // Category filtering functionality with smooth transitions
    document.getElementById('category').addEventListener('change', function() {
      filterProducts();
    });

    // Status filtering functionality
    document.getElementById('status').addEventListener('change', function() {
      filterProducts();
    });

    // Combined filter function
    function filterProducts() {
      const selectedCategory = document.getElementById('category').value;
      const selectedStatus = document.getElementById('status').value;
      const tableRows = document.querySelectorAll('tbody tr');
      
      // Add loading state
      document.getElementById('category').classList.add('loading');
      document.getElementById('status').classList.add('loading');
      
      // First phase: Fade out all rows
      tableRows.forEach(row => {
        row.classList.add('fade-out');
      });
      
      // Wait for fade-out animation to complete
      setTimeout(() => {
        let visibleCount = 0;
        
        tableRows.forEach(row => {
          const categoryCell = row.cells[2]; // Category is the 3rd column (index 2)
          const statusCell = row.cells[5]; // Status is the 6th column (index 5)
          
          // Check category filter
          const categoryMatch = selectedCategory === 'all' || categoryCell.textContent.trim() === selectedCategory;
          
          // Check status filter
          let statusMatch = true;
          if (selectedStatus === 'active') {
            statusMatch = statusCell.textContent.trim().toLowerCase() === 'active';
          } else if (selectedStatus === 'inactive') {
            statusMatch = statusCell.textContent.trim().toLowerCase() === 'inactive';
          }
          
          if (categoryMatch && statusMatch) {
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
        showFilterMessage(selectedCategory, selectedStatus, visibleCount);
        
        // Remove loading state
        document.getElementById('category').classList.remove('loading');
        document.getElementById('status').classList.remove('loading');
      }, 150);
    }
    
    // Function to show filter success message
    function showFilterMessage(category, status, count) {
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
      
      let filterText = `Showing ${count} products`;
      if (category !== 'all') {
        filterText += ` in ${categoryNames[category]}`;
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
=======
>>>>>>> e95170b6eb6aed1a4e7b5043e3327fe6b8f16eda
</body>
</html>