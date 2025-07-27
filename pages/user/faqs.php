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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQS</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
  <link rel="stylesheet" href="../../css/header-user.css" />
    <link rel="stylesheet" href="../../css/static.css">
  <link rel="stylesheet" href="../../css/footer.css" />
</head>
<body>

<!-- Header -->
<header>
  <div class="logo-container">
    <a href="../../pages/user/homepage.html" class="logo">
      <img src="../../images/revaux-light.png" alt="Logo">
    </a>
    <a href="../../pages/user/homepage.html" class="brand-name">Revaux</a>
  </div>
  <div class="search-bar">
    <input type="text" placeholder="Search Here">
    <button type="submit" class="search-btn">
      <span class="material-icons-outlined">search</span>
    </button>
  </div>
  <div class="cta-buttons">
    <div class="top-buttons">
      <button class="notification-btn">
        <a href="../../pages/user/notifications.html" class="cta-link">
          <span class="material-icons-outlined">notifications</span>
          <span>Notifications</span>
        </a>
      </button>
      <button class="faq-btn">
        <a href="../../pages/user/faqs.html" class="cta-link">
          <span class="material-icons-outlined">help_outline</span>
          <span>FAQs</span>
        </a>
      </button>
      <button class="settings-btn">
        <a href="../../pages/user/settings.html" class="cta-link">
          <span class="material-icons-outlined">settings</span>
          <span>Settings</span>
        </a>
      </button>
      <button class="logout-btn">
        <a href="../../pages/guest/index.html" class="cta-link">
          <span class="material-icons-outlined">exit_to_app</span>
          <span>Log Out</span>
        </a>
      </button>
    </div>
    <div class="bottom-buttons">
      <button class="wishlist-btn">
        <a href="../../pages/user/wishlist.html" class="cta-link">
          <span class="material-icons-outlined">favorite_border</span>
          <span>Wishlist</span>
        </a>
      </button>
      <button class="cart-btn">
        <a href="../../pages/user/cart.html" class="cta-link">
          <span class="material-icons-outlined">shopping_cart</span>
          <span>Cart</span>
        </a>
      </button>
      <button class="profile-btn">
        <a href="../../pages/user/profile.html" class="cta-link">
          <span class="material-icons-outlined">person</span>
          <span><?= htmlspecialchars($_SESSION['username']) ?></span>
        </a>
      </button>
    </div>
  </div>
</header>

<!-- Main -->
<section>
    <div class="container">
        <h1>Frequently Asked Questions (FAQs)</h1>
        <p><strong>Last updated on 07/25/2025</strong></p>
        <hr>

        <h2>I. General Information</h2>
        
        <h3>1. What is Revaux?</h3>
        <p>Revaux is a local clothing brand from the Philippines offering high-quality, affordable fashion. Our collections combine streetwear, Y2K, old money, and minimalist styles, available in four categories: Headwear, Tops, Bottoms, and Footwear.</p>

        <h3>2. What products does Revaux offer?</h3>
        <p>We offer a wide range of clothing and accessories, including Caps, Eyewear, T-shirts, Polos, Jeans, Shorts, Sneakers, and much more. Our products are carefully designed to meet the needs of modern, stylish individuals.</p>

        <h2>II. Order Assistance</h2>

        <h3>1. How do I place an order?</h3>
        <p>To place an order, simply browse through our collections, add your desired items to your cart, and proceed to checkout. You will receive an order confirmation email once your order is placed.</p>

        <h3>2. Can I modify or cancel my order?</h3>
        <p>Once your order is confirmed, we are unable to make modifications or cancellations. If you have any issues, please contact us as soon as possible, and we'll do our best to assist you.</p>

        <h3>3. What payment methods do you accept?</h3>
        <p>We accept various payment methods, including credit cards and online payment systems. You will be prompted to select a payment option during checkout.</p>

        <h3>4. Can I apply a discount code?</h3>
        <p>If you have a valid discount code, you can apply it during the checkout process to avail of discounts on your order.</p>

        <h2>III. Shipping & Delivery</h2>

        <h3>1. How long will it take to receive my order?</h3>
        <p>We aim to ship all orders within 2-3 business days. Delivery times will depend on your location and the shipping method chosen at checkout.</p>

        <h3>2. What are the shipping charges?</h3>
        <p>Shipping charges are calculated at checkout based on your location and the shipping method you choose. We offer both local and international shipping.</p>

        <h3>3. Can I track my order?</h3>
        <p>Once your order is shipped, you will receive a tracking number that allows you to track the status of your package. Please check your email for updates.</p>

        <h2>IV. Returns & Exchanges</h2>

        <h3>1. What is your return policy?</h3>
        <p>If you're not satisfied with your purchase, we offer returns and exchanges within 30 days of receipt. The item must be unused, in original packaging, and in the same condition as when it was delivered.</p>

        <h3>2. How do I return or exchange an item?</h3>
        <p>To initiate a return or exchange, please contact our customer service team at <a href="mailto:support@revaux.ph">support@revaux.ph</a>. We'll provide you with instructions on how to proceed.</p>

        <h2>V. Product Information</h2>

        <h3>1. How do I know if an item is in stock?</h3>
        <p>We display real-time stock availability on our website. If an item is out of stock, it will be marked as unavailable.</p>

        <h3>2. How do I know which size to order?</h3>
        <p>We provide a size guide for each product to help you choose the right fit. Please check the product page for detailed sizing information.</p>

        <h3>3. Are the product images accurate?</h3>
        <p>We take great care to ensure that the images on our site accurately represent the products. However, due to lighting and screen variations, actual product colors may differ slightly.</p>

        <h2>VI. Privacy & Security</h2>

        <h3>1. How is my personal information protected?</h3>
        <p>Your privacy is important to us. We protect your personal information using encryption and follow strict security protocols. Please refer to our <a href="../../pages/guest/privacy_policy.html" target="_blank">Privacy Policy</a> for more details.</p>

        <h3>2. Do you share my information with third parties?</h3>
        <p>We respect your privacy and do not share your personal information with third parties without your consent, except as required for fulfilling orders or legal obligations.</p>

        <h2>VII. Contact Us</h2>

        <p>If you have any other questions or need assistance, please don't hesitate to contact us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:support@revaux.ph">support@revaux.ph</a></li>
            <li><strong>Phone:</strong> 09082465481</li>
            <li><strong>Address:</strong> Revaux Fashion PH, Inc., 1234 Makati Ave, Suite 567, Ayala Tower, Makati City, 1226 Metro Manila, Philippines</li>
        </ul>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>Company Info</h4>
            <ul>
                <li><a href="../../pages/user/about_company.php">About Revaux</a></li>
                <li><a href="../../pages/user/about_company.php">Our Story</a></li>
                <li><a href="../../pages/user/about_company.php">The Revaux Aesthetic</a></li>
                <li><a href="../../pages/user/about_company.php">Press & Media</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Help & Support</h4>
            <ul>
                <li><a href="../../pages/user/faqs.php">Frequently Asked Questions</a></li>
                <li><a href="../../pages/user/faqs.php">Order Assistance</a></li>
                <li><a href="../../pages/user/faqs.php">Returns & Exchanges</a></li>
                <li><a href="../../pages/user/faqs.php">Product Information</a></li>
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
            <a href="../../pages/user/terms_conditions.php">Terms & Conditions</a>
            <span class="separator">|</span>
            <a href="../../pages/user/privacy_policy.php">Privacy Policy</a>
        </div> 
    </div>
</footer>

</body>
</html>
