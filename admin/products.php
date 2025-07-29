<?php
  // Include secure authentication check for admin
  require_once 'auth_check.php';
?>
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
          <a href="index.php">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="transactions.php">
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
          <a class="add-product-btn" href="insertProducts.html">
            <span>Add Product</span>
          </a>
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

  <script src="./js/products.js"></script>
</body>
</html>