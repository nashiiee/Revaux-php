<?php 
  // Start session to access user data
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../authentication/login.html");
    exit();
}

// Include database connection
require_once '../../database/database.php';
?>


<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
  <title>Revaux</title>
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
          <a href="../../pages/products/mens-baseball-hat.html" class="product-card">
            <div class="product-image"><img src="../../images/facebook-icon.png" alt="Hat"></div>
            <div class="product-info">
              <p class="product-name">Men's Baseball Hat</p>
              <div class="product-meta">
                <p class="price">₱260.00</p>
                <p class="product-sold">100k sold</p>
              </div>
            </div>
          </a>
          <a href="../../pages/products/running-shoes.html" class="product-card">
            <div class="product-image"><img src="images/product2.jpg" alt="Shoes"></div>
            <div class="product-info">
              <p class="product-name">Running Shoes</p>
              <div class="product-meta">
                <p class="price">₱1,200.00</p>
                <p class="product-sold">50k sold</p>
              </div>
            </div>
          </a>
          <a href="../../pages/products/casual-shirt.html" class="product-card">
            <div class="product-image"><img src="images/product3.jpg" alt="Shirt"></div>
            <div class="product-info">
              <p class="product-name">Casual Shirt</p>
              <div class="product-meta">
                <p class="price">₱450.00</p>
                <p class="product-sold">30k sold</p>
              </div>
            </div>
          </a>
          <a href="../../pages/products/mens-fedora-hat.html" class="product-card">
            <div class="product-image"><img src="images/product4.jpg" alt="Hat"></div>
            <div class="product-info">
              <p class="product-name">Men's Fedora Hat</p>
              <div class="product-meta">
                <p class="price">₱350.00</p>
                <p class="product-sold">80k sold</p>
              </div>
            </div>
          </a>
        </div>
    </section>

    <p class="line text">──────────────────────────────────────────────────────────────────────────────</p>

    <section class="section discover-new">
        <h2 class="section-title">Discover Something New</h2>
        <div class="products-grid">
            <a href="../pages/products/streetwear-fit-hoodie.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass1.png" alt="New Look"></div>
                <div class="product-info">
                    <p class="product-name">Streetwear Fit Hoodie</p>
                    <div class="product-meta">
                        <p class="price">₱1,895.00</p>
                        <p class="product-sold">12k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/modern-sneakers.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass2.png" alt="Sneakers"></div>
                <div class="product-info">
                    <p class="product-name">Modern Sneakers</p>
                    <div class="product-meta">
                        <p class="price">₱2,200.00</p>
                        <p class="product-sold">15k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/graphic-tshirt.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass3.png" alt="T-shirt"></div>
                <div class="product-info">
                    <p class="product-name">Graphic T-shirt</p>
                    <div class="product-meta">
                        <p class="price">₱799.00</p>
                        <p class="product-sold">20k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/denim-jacket.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass4.png" alt="Jacket"></div>
                <div class="product-info">
                    <p class="product-name">Denim Jacket</p>
                    <div class="product-meta">
                        <p class="price">₱1,250.00</p>
                        <p class="product-sold">25k sold</p>
                    </div>
                </div>
            </a>

            <a href="../pages/products/streetwear-fit-hoodie.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass1.png" alt="New Look"></div>
                <div class="product-info">
                    <p class="product-name">Streetwear Fit Hoodie</p>
                    <div class="product-meta">
                        <p class="price">₱1,895.00</p>
                        <p class="product-sold">12k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/modern-sneakers.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass2.png" alt="Sneakers"></div>
                <div class="product-info">
                    <p class="product-name">Modern Sneakers</p>
                    <div class="product-meta">
                        <p class="price">₱2,200.00</p>
                        <p class="product-sold">15k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/graphic-tshirt.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass3.png" alt="T-shirt"></div>
                <div class="product-info">
                    <p class="product-name">Graphic T-shirt</p>
                    <div class="product-meta">
                        <p class="price">₱799.00</p>
                        <p class="product-sold">20k sold</p>
                    </div>
                </div>
            </a>
            <a href="../pages/products/denim-jacket.html" class="product-card">
                <div class="product-image"><img src="../images/products/eyeglass4.png" alt="Jacket"></div>
                <div class="product-info">
                    <p class="product-name">Denim Jacket</p>
                    <div class="product-meta">
                        <p class="price">₱1,250.00</p>
                        <p class="product-sold">25k sold</p>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h4>Company Info</h4>
                <ul>
                    <li><a href="../../pages/user/about_company.html">About Revaux</a></li>
                    <li><a href="../../pages/user/about_company.html">Our Story</a></li>
                    <li><a href="../../pages/user/about_company.html">The Revaux Aesthetic</a></li>
                    <li><a href="../../pages/user/about_company.html">Press & Media</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Help & Support</h4>
                <ul>
                    <li><a href="../../pages/user/faqs.html">Frequently Asked Questions</a></li>
                    <li><a href="../../pages/user/faqs.html">Order Assistance</a></li>
                    <li><a href="../../pages/user/faqs.html">Returns & Exchanges</a></li>
                    <li><a href="../../pages/user/faqs.html">Product Information</a></li>
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
                <a href="../../pages/user/terms_conditions.html">Terms & Conditions</a>
                <span class="separator">|</span>
                <a href="../../pages/user/privacy_policy.html">Privacy Policy</a>
            </div> 
        </div>
    </footer>
    <script type="module" src="../../scripts/main.js"></script>
  </body>
</html>
