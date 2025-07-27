<?php
  include '../revauxDatabase/database.php';

  try {
      $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare("SELECT * FROM views");
      $stmt->execute();
  } catch (PDOException $e) {
    header("Location: index.php?error=database_connection_failed");
    exit();
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Poppins Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Playfair Display Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <!-- Stylesheet -->
  <link rel="stylesheet" href="../dist/dashboard.css">
  <!-- Font Awesome Free CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <aside>
      <div class="logo">
        <img src="./images/logo.png" alt="Revaux Logo">
        <span>Revaux</span>
      </div>
      <div class="links">
        <ul class="nav-links">
          <li class="active">
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
          <li>
            <a href="products.php">
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

    <main>
      <div class="dashboard-header">
        <h1>Welcome Back, Admin!</h1>
        <p>Here&#39;s what happening with your store today</p>
      </div>
      <div class="dashboard-cards">
        <div class="card">
          <div class="contents">
            <h2>$3.32K</h2>
            <p>New Orders</p>
            <h4>+20%</h4>
            <p>This month</p>
          </div>
        </div>
        <div class="card--revenue">
          <div class="contents">
            <h2>$30.22K</h2>
            <p>Total Revenue</p>
            <h4>-12%</h4>
            <p>This month</p>
          </div>
        </div>
        <div class="card--totalExpense">
          <div class="contents">
            <h2>$20.21K</h2>
            <p>Total Expense</p>
            <h4>+23%</h4>
            <p>This month</p>
          </div>
        </div>
      </div>
      <div class="chart-container">
      </div>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Total Sales</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
          <tr>
            <td><div class="table-image"></div></td>
            <td>Denim Jacket</td>
            <td>Men's Top</td>
            <td>In Stock</td>
            <td>1.22k</td>
          </tr>
        </tbody>
      </table>
    </main>

    <div class="right-section-sidebar">
      <div class="profile">
        <div class="img-container">
          <div id="round"></div>
          <span>Admin</span>
          <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div class="icon-container">
          <i><i class="fa-solid fa-bell"></i></i>
        </div>
      </div>
      <div class="top-customers">
        <div class="header-container">
          <div class="top-customer-container">
            <h2>Top Customers</h2>
          </div>
          <div class="see-all">
            <span>See all</span>
          </div>
        </div>
        <div class="customers-list">
          <div class="customer">
            <div class="customer-image-details">
              <div class="customer-image"></div>
              <div class="customer-info">
                <p class="name">John Doe</p>
                <p class="purchase">20 Purchase</p>
              </div>
            </div>
            <div class="customer-spent">$6.24k</div>
          </div>
          <div class="customer">
            <div class="customer-image-details">
              <div class="customer-image"></div>
              <div class="customer-info">
                <p class="name">John Doe</p>
                <p class="purchase">20 Purchase</p>
              </div>
            </div>
            <div class="customer-spent">$6.24k</div>
          </div>
          <div class="customer">
            <div class="customer-image-details">
              <div class="customer-image"></div>
              <div class="customer-info">
                <p class="name">John Doe</p>
                <p class="purchase">20 Purchase</p>
              </div>
            </div>
            <div class="customer-spent">$6.24k</div>
          </div>
          <div class="customer">
            <div class="customer-image-details">
              <div class="customer-image"></div>
              <div class="customer-info">
                <p class="name">John Doe</p>
                <p class="purchase">20 Purchase</p>
              </div>
            </div>
            <div class="customer-spent">$6.24k</div>
          </div>
          <div class="customer">
            <div class="customer-image-details">
              <div class="customer-image"></div>
              <div class="customer-info">
                <p class="name">John Doe</p>
                <p class="purchase">20 Purchase</p>
              </div>
            </div>
            <div class="customer-spent">$6.24k</div>
          </div>
        </div>
      </div>
      <div class="recent-orders">
        <div class="orders-header-container">
          <div class="h2-container">
            <h2>Recent Orders</h2>
          </div>
          <div class="see-all">
            <span>See all</span>
          </div>
        </div>
        <div class="recent-orders-list">
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Denim Jacket</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$6.24k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Kobe 10</p>
                <p class="category">Shoes</p>
              </div>
            </div>
            <div class="price">$4.32k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Air Jordan</p>
                <p class="category">Shoes</p>
              </div>
            </div>
            <div class="price">$4.32k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Nike Air Force 1</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$2.34k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Denim Jacket</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$6.24k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Denim Jacket</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$6.24k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Kobe 10</p>
                <p class="category">Shoes</p>
              </div>
            </div>
            <div class="price">$4.32k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Air Jordan</p>
                <p class="category">Shoes</p>
              </div>
            </div>
            <div class="price">$4.32k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Nike Air Force 1</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$2.34k</div>
          </div>
          <div class="customer-orders-container">
            <div class="customer-order-image-details">
              <div class="order-image"></div>
              <div class="order-info">
                <p class="product-name">Denim Jacket</p>
                <p class="category">Men's Top</p>
              </div>
            </div>
            <div class="price">$6.24k</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
  
</body> 
</html>