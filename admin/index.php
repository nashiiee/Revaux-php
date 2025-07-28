<?php
  include '../revauxDatabase/database.php';

  try {
      $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      // Fetch products

      $topCustomers = $conn->query("SELECT c.id AS customer_id, c.first_name, c.last_name, SUM(oi.quantity * oi.price) AS total_spent, SUM(quantity) AS total_quantity FROM customers AS c
        JOIN orders AS o ON c.id = o.customer_id
        JOIN order_items AS oi ON o.id = oi.order_id
        GROUP BY c.id, c.first_name, c.last_name
        ORDER BY total_spent DESC
        LIMIT 5;");
      
      $recentOrders = $conn->query("SELECT
    p.name AS product_name,
    SUM(oi.quantity * oi.price) AS total_price_sold,
    c.name AS category_name
FROM
    order_items AS oi
JOIN
    products AS p ON oi.product_id = p.id
JOIN
    categories AS c ON p.category_id = c.id
JOIN
    orders AS o ON oi.order_id = o.id
WHERE
    o.order_date >= NOW() - INTERVAL 24 HOUR 
GROUP BY
    p.name,
    c.name
ORDER BY
    total_price_sold DESC
LIMIT 10;");

      $topSellingProducts = $conn->query("SELECT
      p.id AS product_id,
      p.name AS product_name,
      c.name AS category_name,
      p.status AS product_status,
      SUM(oi.quantity) AS total_quantity_sold
      FROM order_items AS oi
      JOIN products AS p ON oi.product_id = p.id
      JOIN categories AS c ON p.category_id = c.id
      JOIN orders AS o ON oi.order_id = o.id
      WHERE o.status = 'Delivered' AND o.payment_status = 'Paid'
      GROUP BY p.id, p.name, c.name, p.status
      ORDER BY total_quantity_sold DESC;");

      $totalRevenueCurrent = $conn->query("SELECT
    SUM(oi.quantity * oi.price) AS total_revenue_current
FROM
    orders AS o
JOIN
    order_items AS oi ON o.id = oi.order_id
WHERE
    DATE_FORMAT(o.order_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
    AND o.status = 'Delivered' AND o.payment_status = 'Paid'; ");
      
    $totalRevenuePrevious = $conn->query("SELECT
    SUM(oi.quantity * oi.price) AS total_revenue_previous
FROM
    orders AS o
JOIN
    order_items AS oi ON o.id = oi.order_id
WHERE
    DATE_FORMAT(o.order_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m')
    AND o.status = 'Delivered' AND o.payment_status = 'Paid';");


    $monthlyRevenue = $conn->query("SELECT
    DATE_FORMAT(o.order_date, '%Y-%m') AS sales_month,
    SUM(oi.quantity * oi.price) AS monthly_revenue
FROM
    orders AS o
JOIN
    order_items AS oi ON o.id = oi.order_id
WHERE
    o.status = 'Delivered' AND o.payment_status = 'Paid'
GROUP BY
    sales_month
ORDER BY
    sales_month ASC;");


    $totalQuantityCurrent = $conn->query("SELECT
    SUM(oi.quantity) AS total_quantity_current
FROM
    orders AS o
JOIN
    order_items AS oi ON o.id = oi.order_id
WHERE
    DATE_FORMAT(o.order_date, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
    AND o.status = 'Delivered' AND o.payment_status = 'Paid';");

    $totalQuantityPrevious = $conn->query("SELECT
    SUM(oi.quantity) AS total_quantity_previous
FROM
    orders AS o
JOIN
    order_items AS oi ON o.id = oi.order_id
WHERE
    DATE_FORMAT(o.order_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m')
    AND o.status = 'Delivered' AND o.payment_status = 'Paid';");



      $totalQuantityCurrent = $totalQuantityCurrent->fetch(PDO::FETCH_ASSOC);
      $totalQuantityPrevious = $totalQuantityPrevious->fetch(PDO::FETCH_ASSOC);




      $topSellingProducts = $topSellingProducts->fetchAll(PDO::FETCH_ASSOC);
      $recentOrders = $recentOrders->fetchAll(PDO::FETCH_ASSOC);
      $topCustomers = $topCustomers->fetchAll(PDO::FETCH_ASSOC);
      $monthlyRevenue = $monthlyRevenue->fetchAll(PDO::FETCH_ASSOC);
      
      // Fetch revenue data
      $totalRevenueData = $totalRevenueCurrent->fetch(PDO::FETCH_ASSOC);
      $totalPercentageData = $totalRevenuePrevious->fetch(PDO::FETCH_ASSOC);
      
      // Extract revenue values
      $currentRevenue = $totalRevenueData['total_revenue_current'] ?? 0;
      $previousRevenue = $totalPercentageData['total_revenue_previous'] ?? 0;
      
      // Calculate percentage change for revenue
      $percentageChange = 0;
      $percentageSign = '';
      
      if ($previousRevenue > 0) {
          $percentageChange = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
          $percentageSign = $percentageChange >= 0 ? '+' : '';
      }
      
      // Format revenue for display
      $formattedCurrentRevenue = number_format($currentRevenue / 1000, 2) . 'K';
      $formattedPercentage = $percentageSign . number_format($percentageChange, 1) . '%';
      
      // Extract quantity values
      $currentQuantity = $totalQuantityCurrent['total_quantity_current'] ?? 0;
      $previousQuantity = $totalQuantityPrevious['total_quantity_previous'] ?? 0;
      
      // Calculate percentage change for quantity
      $quantityPercentageChange = 0;
      $quantityPercentageSign = '';
      
      if ($previousQuantity > 0) {
          $quantityPercentageChange = (($currentQuantity - $previousQuantity) / $previousQuantity) * 100;
          $quantityPercentageSign = $quantityPercentageChange >= 0 ? '+' : '';
      }
      
      // Format quantity for display
      $formattedCurrentQuantity = number_format($currentQuantity);
      $formattedQuantityPercentage = $quantityPercentageSign . number_format($quantityPercentageChange, 1) . '%';

      // Debug values (remove this after testing)
      // echo "Current Quantity: " . $currentQuantity . "<br>";
      // echo "Previous Quantity: " . $previousQuantity . "<br>";
      // echo "Current Revenue: " . $currentRevenue . "<br>";
      // echo "Previous Revenue: " . $previousRevenue . "<br>";


  } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
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
  <style>
    .positive {
      color: #28a745;
    }
    .negative {
      color: #dc3545;
    }
  </style>
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
            <h2><?php echo $formattedCurrentQuantity; ?></h2>
            <p>Products Sold</p>
            <h4 class="<?php echo $quantityPercentageChange >= 0 ? 'positive' : 'negative'; ?>"><?php echo $formattedQuantityPercentage; ?></h4>
            <p>This month</p>
          </div>
        </div>
        <div class="card--revenue">
          <div class="contents">
            <h2>₱<?php echo $formattedCurrentRevenue; ?></h2>
            <p>Total Revenue</p>
            <h4 class="<?php echo $percentageChange >= 0 ? 'positive' : 'negative'; ?>"><?php echo $formattedPercentage; ?></h4>
            <p>This month</p>
          </div>
        </div>
      </div>
      <div class="chart-container">
        <canvas id="barChart"></canvas>
      </div>

      <h2 class="top-selling-products">Top Selling Products</h2>
      <table>
        <thead>
          <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Total Sales</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach($topSellingProducts as $product) {
              echo '<tr>';
              echo '<td>' . htmlspecialchars($product['product_id']) . '</td>';
              echo '<td>' . htmlspecialchars($product['product_name']) . '</td>';
              echo '<td>' . htmlspecialchars($product['category_name']) . '</td>';
              echo '<td>' . (htmlspecialchars($product['product_status']) == 1 ? 'Active' : 'Inactive') . '</td>';
              echo '<td>' . htmlspecialchars($product['total_quantity_sold']) . '</td>';
              echo '</tr>';
            }
          ?>
          <!-- <tr>
            <td>12332</td>
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
          </tr> -->
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
          <?php
            foreach($topCustomers as $customer) {
              echo '<div class="customer">';
              echo '<div class="customer-image-details">';
              echo '<div class="customer-info">';
              echo '<p class="name">' . (!empty($customer['first_name']) ? htmlspecialchars($customer['first_name']). ' ' . htmlspecialchars($customer['last_name']) : 'Anonymous') . '</p>';
              echo '<p class="purchase">' . htmlspecialchars($customer['total_quantity']) . ' Purchase</p>';
              echo '</div></div>';
              echo '<div class="customer-spent">₱' . number_format($customer['total_spent'], 2) . '</div>';
              echo '</div>';
            }
          ?>
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
          <?php
            foreach($recentOrders as $order) {
              echo '<div class="customer-orders-container">';
              echo '<div class="customer-order-image-details">';
              echo '<div class="order-image">
                      <img src="images/caps1.svg" class="recent-order-image" style="width: 100%; height: 100%; border-radius: 50%;">
                    </div>';
              echo '<div class="order-info">';
              echo '<p class="product-name">' . htmlspecialchars($order['product_name']) . '</p>';
              echo '<p class="category">' . htmlspecialchars($order['category_name']) . '</p>';
              echo '</div></div>';
              echo '<div class="price">₱' . number_format($order['total_price_sold'], 2) . '</div>';
              echo '</div>';
            }
          ?>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Chart.js Bar Chart - Dynamic data from PHP
    const monthLabels = [
      <?php
        foreach($monthlyRevenue as $revenue) {
          echo "'" . date('M Y', strtotime($revenue['sales_month'] . '-01')) . "',";
        }
      ?>
    ];
    
    const monthlyRevenueData = [
      <?php
        foreach($monthlyRevenue as $revenue) {
          echo ($revenue['monthly_revenue'] / 1000) . ",";
        }
      ?>
    ];

    const data = {
      labels: monthLabels,
      datasets: [{
        label: 'Monthly Revenue (in thousands)',
        data: monthlyRevenueData,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgb(54, 162, 235)',
        borderWidth: 2
      }]
    };

    const config = {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        }
      },
    };

    // Create the chart
    const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, config);
  </script>
  
  
</body> 
</html>