<?php 
  // Include secure authentication check
  require_once 'auth_check.php';

  // Include database connection
  require_once '../../database/database.php';
?>


<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
  <title>Revaux</title>
  <link rel="icon" type="image/png" href="../../images/revaux-light.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
  <link rel="stylesheet" href="../../css/header-user.css">
  <link rel="stylesheet" href="../../css/main.css">
  <link rel="stylesheet" href="../../css/footer.css">
</head>
  <body>
    <!-- Header -->
    <?php include '../../includes/header-user.php'; ?>

    <!-- Main -->
    <section class="hero-slider">
      <div class="slider-container">
        <a href="https://example.com/page1" class="slide active">
          <img src="../../images/auth.png" alt="Headwear Model">
        </a>
        <a href="https://example.com/page2" class="slide">
          <img src="../../images/auth2.png" alt="Top Model">
        </a>
        <a href="https://example.com/page3" class="slide">
          <img src="../../images/auth3.png" alt="Bottom Model">
        </a>
        <a href="https://example.com/page4" class="slide">
          <img src="../../images/auth4.png" alt="Footwear Model">
        </a>
      </div>

      <button class="slider-button prev" id="prevBtn">&#10094;</button>
      <button class="slider-button next" id="nextBtn">&#10095;</button>
    </section>

    <section class="section categories">
    <h2 class="section-title">Categories</h2>
        <div class="categories-grid">
          <a href="../category.php?category=Headwear" class="category-card" style="background-image: url('../../images/headwear.png');">
            <span>Headwear</span>
          </a>
          <a href="../category.php?category=Tops" class="category-card" style="background-image: url('../../images/top.png');">
            <span>Top</span>
          </a>
          <a href="../category.php?category=Bottoms" class="category-card" style="background-image: url('../../images/bottom.png');">
            <span>Bottom</span>
          </a>
          <a href="../category.php?category=Footwear  " class="category-card" style="background-image: url('../../images/footwear.png');">
            <span>Footwear</span>
          </a>
        </div>
    </section>

    <p class="line text">─────────────────────────────────────────────────────────────────────────────────</p>

    <section class="section top-products">
      <h2 class="section-title">Top Products</h2>
      <div class="products-grid">
        <?php
          // Check if user is logged in to route them to the right product page
          $productPage = isset($_SESSION['username']) 
            ? '../../pages/user/product_info.php' 
            : '../../pages/guest/product_info.php';

          // Get top 4 selling products
          $stmt = $conn->query("SELECT 
              p.id, p.name, p.price, p.image_url,
              IFNULL(psc.sold_count, 0) AS sold_count
            FROM products p
            LEFT JOIN (
              SELECT
                  oi.product_id,
                  IFNULL(SUM(oi.quantity), 0) AS sold_count
              FROM
                  order_items oi
              JOIN
                  orders o ON oi.order_id = o.id
              WHERE
                  o.payment_status = 'Paid' AND o.status = 'Delivered'
              GROUP BY
                  oi.product_id
            ) psc ON p.id = psc.product_id
            ORDER BY sold_count DESC
            LIMIT 4
          ");

          while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $product['id'];
            $name = htmlspecialchars($product['name']);
            $price = number_format($product['price'], 2);
            $sold = $product['sold_count'];
            $image = '../../admin/' . ltrim($product['image_url'], './');

            echo '
              <a href="' . $productPage . '?id=' . $id . '" class="product-card">
                <div class="product-image">
                  <img src="' . $image . '" alt="' . $name . '">
                </div>
                <div class="product-info">
                  <p class="product-name">' . $name . '</p>
                  <div class="product-meta">
                    <p class="price">₱' . $price . '</p>
                    <p class="product-sold">' . $sold . ' sold</p>
                  </div>
                </div>
              </a>
            ';
          }
        ?>
      </div>
    </section>


    <p class="line text">──────────────────────────────────────────────────────────────────────────────</p>

    <section class="section discover-new">
          <h2 class="section-title">Discover Something New</h2>
          <div class="products-grid">
              <?php
                  // Check login state to decide product page destination
                  $isLoggedIn = isset($_SESSION['username']);
                  $productPage = $isLoggedIn ? '../../pages/user/product_info.php' : '../../pages/guest/product_info.php';

                  // Fetch 8 random products
                  $stmt = $conn->query("SELECT 
                          p.id, p.name, p.price, p.image_url,
                          IFNULL(psc.sold_count, 0) AS sold_count
                      FROM products p
                      LEFT JOIN (
                          SELECT
                              oi.product_id,
                              IFNULL(SUM(oi.quantity), 0) AS sold_count
                          FROM
                              order_items oi
                          JOIN
                              orders o ON oi.order_id = o.id
                          WHERE
                              o.payment_status = 'Paid' AND o.status = 'Delivered'
                          GROUP BY
                              oi.product_id
                      ) psc ON p.id = psc.product_id
                      ORDER BY RAND()
                      LIMIT 8
                  ");

                  while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $id = $product['id'];
                      $name = htmlspecialchars($product['name']);
                      $price = number_format($product['price'], 2);
                      $sold = $product['sold_count'];
                      $image = '../../admin/' . ltrim($product['image_url'], './');

                      echo '
                          <a href="' . $productPage . '?id=' . $id . '" class="product-card">
                              <div class="product-image">
                                  <img src="' . $image . '" alt="' . $name . '">
                              </div>
                              <div class="product-info">
                                  <p class="product-name">' . $name . '</p>
                                  <div class="product-meta">
                                      <p class="price">₱' . $price . '</p>
                                      <p class="product-sold">' . $sold . ' sold</p>
                                  </div>
                              </div>
                          </a>
                      ';
                  }
              ?>
          </div>
      </section>
    <?php include '../../includes/footer.php'; ?>
  <script type="module" src="../../scripts/main.js"></script>
  </body>
</html>
