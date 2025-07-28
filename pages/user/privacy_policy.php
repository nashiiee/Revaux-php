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
  <title>Privacy Policy</title>
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
        <h1>Privacy Policy</h1>
        <p><strong>Last updated on 07/25/2025</strong></p>
        <hr>
        <h2>I. Introduction</h2>
        <p>Welcome to <strong>Revaux</strong>! This Privacy Policy ("Policy") governs how we collect, use, and protect your personal information when you access or use our website, products, and services. By using our website, you agree to the terms described in this Policy. Please read it carefully.</p>

        <h2>II. Information We Collect</h2>
        <p>We collect various types of information to provide and improve our services, including:</p>
        <ul>
            <li><strong>Personal Information:</strong> Information you provide us directly, such as your name, email address, phone number, and payment details.</li>
            <li><strong>Usage Data:</strong> Information we collect automatically when you use our website, such as IP address, browser type, and usage data.</li>
            <li><strong>Cookies:</strong> We use cookies and similar tracking technologies to enhance your experience on our Site. You can manage cookie preferences through your browser settings.</li>
        </ul>

        <h2>III. How We Use Your Information</h2>
        <p>We use your personal information for various purposes, including:</p>
        <ul>
            <li>To process your orders and provide customer support.</li>
            <li>To improve and personalize your experience on our Site.</li>
            <li>To send marketing communications, promotions, or offers related to our products.</li>
            <li>To comply with legal obligations and enforce our Terms and Conditions.</li>
        </ul>

        <h2>IV. Sharing Your Information</h2>
        <p>We do not sell, trade, or rent your personal information to third parties. However, we may share your information with trusted third-party service providers for the following purposes:</p>
        <ul>
            <li><strong>Payment Processing:</strong> We may share your payment details with trusted payment providers to process transactions securely.</li>
            <li><strong>Shipping and Delivery:</strong> We may share your address and contact information with shipping providers to fulfill your orders.</li>
            <li><strong>Legal Requirements:</strong> We may disclose your personal information if required by law or to protect our rights.</li>
        </ul>

        <h2>V. Data Security</h2>
        <p>We take the security of your personal information seriously. We use industry-standard encryption and security measures to protect your data from unauthorized access, alteration, or disclosure. However, no method of transmission over the internet is 100% secure, and we cannot guarantee the absolute security of your information.</p>

        <h2>VI. Your Rights</h2>
        <p>You have the right to access, correct, or delete your personal information. If you wish to exercise any of these rights, please contact us using the information provided below. We may require you to verify your identity before processing your request.</p>

        <h2>VII. Third-Party Links</h2>
        <p>Our website may contain links to third-party websites. Please note that we are not responsible for the privacy practices or content of these external sites. We encourage you to review their privacy policies before providing any personal information to them.</p>

        <h2>VIII. Children's Privacy</h2>
        <p>Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children under 13. If we discover that we have inadvertently collected information from a child under 13, we will take steps to delete that information.</p>

        <h2>IX. Changes to this Privacy Policy</h2>
        <p>Revaux reserves the right to update or modify this Privacy Policy at any time. Any changes will be posted on this page, and the "Last Updated" date will be revised. We encourage you to review this Policy periodically to stay informed about how we protect your information.</p>

        <h2>X. Contact Us</h2>
        <p>If you have any questions or concerns about this Privacy Policy or how we handle your personal information, please contact us at:</p>
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
