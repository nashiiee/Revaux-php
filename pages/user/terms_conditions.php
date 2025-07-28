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
  <title>Terms & Conditions</title>
  <link rel="icon" type="image/png" href="../../images/revaux-light.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
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
        <h1>Terms & Conditions</h1>
        <p><strong>Last updated on 07/25/2025</strong></p>

        <p class="line text">───────────────────────────────────────────────────────────────────────────────────────────────────────────</p>

        <h2>I. Introduction</h2>
        <p>Welcome to <strong>Revaux</strong>! These Terms and Conditions ("Terms") govern your use of our website, products, and services. By accessing or using our website, you agree to comply with these Terms. Please read them carefully.</p>

        <h2>II. Acceptance of Terms</h2>
        <p>By visiting our website at <a href="https://www.revaux.com" target="_blank">www.revaux.com</a> ("Site"), making purchases, or using our services, you agree to these Terms and Conditions. If you do not agree with any part of these Terms, you should not use our Site.</p>

        <h2>III. Changes to Terms</h2>
        <p>Revaux reserves the right to update, modify, or change these Terms at any time without notice. Any changes will be reflected on this page, and the "Last Updated" date will be revised. We encourage you to review these Terms periodically.</p>

        <h2>IV. Privacy Policy</h2>
        <p>Your use of our Site is also governed by our <a href="../../pages/user/privacy_policy.html" target="_blank">Privacy Policy</a>, which outlines how we collect, use, and protect your personal information. By using our Site, you consent to the practices described in our Privacy Policy.</p>

        <h2>V. Use of the Website</h2>
        <p>You agree to use the Site only for lawful purposes. You may not use the Site in any manner that could disable, overburden, or damage the functionality of the website or interfere with other users' access.</p>

        <h2>VI. Products and Orders</h2>
        <ul>
            <li><strong>Product Availability:</strong> All products are subject to availability. We reserve the right to limit the quantity of items available for sale, especially for limited edition or high-demand products like those in our headwear, top, bottom, and footwear categories.</li>
            <li><strong>Pricing:</strong> Prices are listed in your local currency and may be subject to change without notice. We will provide accurate and updated pricing on the Site.</li>
            <li><strong>Order Confirmation:</strong> After placing an order, you will receive an email confirming the details. This email is not an acceptance of your order but an acknowledgment of receipt.</li>
            <li><strong>Payment:</strong> We accept various payment methods, including credit cards and online payment systems. You agree to provide accurate payment information and authorize Revaux to charge the applicable amounts for your purchases.</li>
        </ul>

        <h2>VII. Shipping & Delivery</h2>
        <ul>
            <li><strong>Shipping Times:</strong> We aim to ship all orders within 2-3 business days. Delivery times may vary depending on your location.</li>
            <li><strong>Shipping Costs:</strong> Shipping charges will be added to your order at checkout based on the shipping method selected.</li>
            <li><strong>International Shipping:</strong> We offer international shipping to select countries. Please check our shipping page for availability and restrictions.</li>
        </ul>

        <h2>VIII. Returns and Exchanges</h2>
        <p>We want you to love your Revaux purchase, but if you're not satisfied, we offer returns and exchanges within 30 days of receipt. To be eligible for a return, the item must be unused, in the original packaging, and in the condition you received it.</p>

        <h2>IX. Intellectual Property</h2>
        <p>All content, designs, logos, trademarks, and other intellectual property on the Site are owned by <strong>Revaux</strong> or our partners and are protected by copyright, trademark, and other laws. You may not use any content from the Site without our prior written permission.</p>

        <h2>X. User Content</h2>
        <p>By submitting content (such as reviews, comments, or photos) to our Site, you grant <strong>Revaux</strong> a worldwide, royalty-free, and transferable license to use, display, and modify the content for marketing, promotional, and other purposes related to the business.</p>

        <h2>XI. Limitation of Liability</h2>
        <p>Revaux is not liable for any damages, including direct, indirect, incidental, punitive, or consequential damages arising from your use of the Site or any products purchased through the Site. We do not guarantee the accuracy, completeness, or timeliness of information on the Site.</p>

        <h2>XII. Indemnification</h2>
        <p>You agree to indemnify and hold harmless <strong>Revaux</strong>, its employees, agents, and affiliates from any claims, damages, or liabilities arising from your use of the Site or violation of these Terms.</p>

        <h2>XIII. Governing Law</h2>
        <p>These Terms and Conditions are governed by and construed in accordance with the laws of the Philippines, without regard to its conflict of law principles. Any disputes will be resolved in the courts of the Philippines.</p>

        <h2>XIV. Contact Us</h2>
        <p>If you have any questions about these Terms and Conditions or our services, please contact us at:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:support@revaux.ph">support@revaux.ph</a></li>
            <li><strong>Phone:</strong> 09082465481</li>
            <li><strong>Address:</strong> Revaux Fashion PH, Inc., 1234 Makati Ave, Suite 567, Ayala Tower, Makati City, 1226 Metro Manila, Philippines</li>
        </ul>

        <h2>XV. Severability</h2>
        <p>If any provision of these Terms is found to be unlawful, void, or unenforceable, the remaining provisions will remain in full force and effect.</p>
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
