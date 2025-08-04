<?php 
  // Include database connection
  require_once './database/database.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Revaux</title>
    <link rel="icon" type="image/png" href="../../images/revaux-light.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <link rel="stylesheet" href="css/header-guest.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/footer.css" />
    </head>
  <body>

    <!-- Header -->
    <?php
      include './includes/header-guest.php';
    ?>

    <!-- Main -->
    <section class="hero-slider">
      <div class="slider-container">
        <a href="https://example.com/page1" class="slide active">
          <img src="images/auth.png" alt="Headwear Model">
        </a>
        <a href="https://example.com/page2" class="slide">
          <img src="images/auth2.png" alt="Top Model">
        </a>
        <a href="https://example.com/page3" class="slide">
          <img src="images/auth3.png" alt="Bottom Model">
        </a>
        <a href="https://example.com/page4" class="slide">
          <img src="images/auth4.png" alt="Footwear Model">
        </a>
      </div>

      <button class="slider-button prev" id="prevBtn">&#10094;</button>
      <button class="slider-button next" id="nextBtn">&#10095;</button>
    </section>

    <section class="section categories">
      <h2 class="section-title">Categories</h2>
      <div class="categories-grid">
        <a href="./pages/category.php?category=headwear" class="category-card" style="background-image: url('images/headwear.png');">
          <span>Headwear</span>
        </a>
        <a href="./pages/category.php?category=tops" class="category-card" style="background-image: url('images/top.png');">
          <span>Top</span>
        </a>
        <a href="./pages/category.php?category=bottoms" class="category-card" style="background-image: url('images/bottom.png');">
          <span>Bottom</span>
        </a>
        <a href="./pages/category.php?category=footwear" class="category-card" style="background-image: url('images/footwear.png');">
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
            ? './pages/user/product_info.php' 
            : './pages/guest/product_info.php';

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
            $image = './admin/' . ltrim($product['image_url'], './');

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
                $productPage = $isLoggedIn ? './pages/user/product_info.php' : './pages/guest/product_info.php';

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
                    $image = './admin/' . ltrim($product['image_url'], './');

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

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Company Info</h4>
                <ul>
                    <li><a href="pages/guest/about_company.html">About Revaux</a></li>
                    <li><a href="pages/guest/about_company.html">Our Story</a></li>
                    <li><a href="pages/guest/about_company.html">The Revaux Aesthetic</a></li>
                    <li><a href="pages/guest/about_company.html">Press & Media</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Help & Support</h4>
                <ul>
                    <li><a href="pages/guest/faqs.html">Frequently Asked Questions</a></li>
                    <li><a href="pages/guest/faqs.html">Order Assistance</a></li>
                    <li><a href="pages/guest/faqs.html">Returns & Exchanges</a></li>
                    <li><a href="pages/guest/faqs.html">Product Information</a></li>
                </ul>
            </div>
            <div class="footer-business">
                <h4>Business Information</h4>
                <ul>
                    <li>Entity Name: Revaux Fashion PH, Inc.</li>
                    <li>Address: 1234 Makati Ave, Suite 567, Ayala Tower, <br> Makati City, 1226 Metro Manila, Philippines</li>
                    <li>Email: <a href="mailto:support@revaux.ph">support@revaux.ph</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>©2024-2025 Revaux All Rights Reserved</p> 
            <div class="footer-links">
                <a href="pages/guest/terms_conditions.html">Terms & Conditions</a>
                <span class="separator">|</span>
                <a href="pages/guest/privacy_policy.html">Privacy Policy</a>
            </div> 
        </div>
    </footer>
    <script type="module" src="./scripts/main.js"></script>
  </body>
</html>
