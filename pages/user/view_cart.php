<?php
  // Include secure authentication check for customer
  require_once 'auth_check.php';
  
  require_once __DIR__ . '/../../database/database.php';

  // Get customer id
  $stmt = $conn->prepare('SELECT id FROM customers WHERE username = ?');
  $stmt->execute([$_SESSION['username']]);
  $customer = $stmt->fetch();
  if (!$customer) {
      die('Customer not found.');
  }
  $customer_id = $customer['id'];

  // Get cart id
  $stmt = $conn->prepare('SELECT id FROM carts WHERE customer_id = ?');
  $stmt->execute([$customer_id]);
  $cart = $stmt->fetch();
  $cart_id = $cart ? $cart['id'] : null;

  $cart_items = [];
  if ($cart_id) {
      // Fetch cart items with product, color, size info
      $stmt = $conn->prepare('SELECT ci.*, p.name AS product_name, p.price, p.image_url, p.quantity AS stock,
                c.name AS color_name, s.label AS size_label
          FROM cart_items ci
          JOIN products p ON ci.product_id = p.id
          LEFT JOIN colors c ON ci.color_id = c.id
          LEFT JOIN sizes s ON ci.size_id = s.id
          WHERE ci.cart_id = ?
      ');
      $stmt->execute([$cart_id]);
      $cart_items = $stmt->fetchAll();
  }


  $cart_total = 0;
  $cart_count = 0;
  foreach ($cart_items as $item) {
      $cart_total += $item['price'] * $item['quantity'];
      $cart_count += $item['quantity'];
  }
  $shipping_fee = $cart_total > 0 ? 138.00 : 0.00;


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Cart | Revaux</title>
    <link rel="stylesheet" href="../../css/view_cart.css" />
    <link rel="stylesheet" href="../../css/header-user.css" />
    <link rel="stylesheet" href="../../css/footer.css" />

    <link rel="icon" type="image/png" href="../../images/revaux-light.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
  </head>
  <body>

    <?php include '../../includes/header-user.php'; ?>

    <!-- Cart Section -->
    <main class="cart-container">
      <div class="cart-left">
        <div class="cart-header">
          <div class="select-all-controls"> 
            <div class="checkbox-wrapper">
              <input type="checkbox" class="custom-checkbox" id="select-all">
            </div>
            <label for="select-all">Select All (<?= $cart_count ?> items)</label>
          </div>  
          <div class="remove-selected-controls">
            <button id="remove-selected-btn" class="remove-selected-header-btn" style="display: none;">
              <span class="material-icons-outlined">delete_outline</span>
            </button>
          </div>          
        </div>

        <div class="cart-category-box">
            <?php if (empty($cart_items)): ?>
                <p class="cart-empty-message" style="padding:2rem; text-align: center; color: #555;">Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item" data-cart-item-id="<?= $item['id'] ?>" data-stock="<?= $item['stock'] ?>">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" class="custom-checkbox cart-item-checkbox" data-cart-item-id="<?= $item['id'] ?>" />
                    </div>
                    <img src="../../admin/<?= ltrim($item['image_url'], './') ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="item-img" />
                    <div class="item-details"> <div class="item-name">
                            <?= htmlspecialchars($item['product_name']) ?><br>
                            <?php if ($item['color_name']): ?>
                                <span style="font-size:0.9em;">Color: <?= htmlspecialchars($item['color_name']) ?></span><br>
                            <?php endif; ?>
                            <?php if ($item['size_label']): ?>
                                <span style="font-size:0.9em;">Size: <?= htmlspecialchars($item['size_label']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="item-price">₱<?= number_format($item['price'], 2) ?></div>
                    </div>
                    <div class="quantity-container">
                        <div class="quantity-selector" data-cart-item-id="<?= $item['id'] ?>">
                            <button class="qty-btn-minus" data-cart-item-id="<?= $item['id'] ?>">-</button>
                            <input type="text" class="qty-input" value="<?= $item['quantity'] ?>" min="1" readonly data-cart-item-id="<?= $item['id'] ?>" />
                            <button class="qty-btn-plus" data-cart-item-id="<?= $item['id'] ?>">+</button>
                        </div>
                        <span class="stock-warning-message" style="display: none;"></span>
                        </div>
                    <div class="total-price" data-cart-item-id="<?= $item['id'] ?>">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                    <button class="delete-btn" type="button" data-cart-item-id="<?= $item['id'] ?>">
                      <span class="material-icons-outlined">delete_outline</span>
                    </button> </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>

      <!-- Order Summary -->
      <aside class="order-summary">
        <h3 class="order-title">Order Summary</h3>
        <hr class="summary-line">
        <div class="summary-item">
          <span>Order total (<?= $cart_count ?> Items):</span>
          <span>₱<?= number_format($cart_total, 2) ?></span>
        </div>
        <div class="summary-item">
          <span>Shipping Fee:</span>
          <span>₱<?= number_format($shipping_fee, 2) ?></span>
        </div>
        <div class="summary-item total">
          <span>Order total:</span>
          <span>₱<?= number_format($cart_total + $shipping_fee, 2) ?></span>
        </div>
        <hr class="summary-line">
        <button class="checkout-btn" <?= $cart_count == 0 ? 'disabled' : '' ?>>Proceed to Check Out</button>
      </aside>
    </main>
    <?php include '../../includes/footer.php'; ?>
    <script type="module" src="../../scripts/main.js"></script>
  </body>
</html>
