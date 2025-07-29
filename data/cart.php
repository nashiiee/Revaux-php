<?php
session_start();
require_once __DIR__ . '/../database/database.php';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Check login
if (!isset($_SESSION['username'])) {
    header('Location: ../pages/authentication/login.html');
    exit;
}

// Get customer id from username
$stmt = $conn->prepare('SELECT id FROM customers WHERE username = ?');
$stmt->execute([$_SESSION['username']]);
$customer = $stmt->fetch();
if (!$customer) {
    exit('Customer not found.');
}
$customer_id = $customer['id'];

// Find or create cart for this customer
$stmt = $conn->prepare('SELECT id FROM carts WHERE customer_id = ?');
$stmt->execute([$customer_id]);
$cart = $stmt->fetch();
if ($cart) {
    $cart_id = $cart['id'];
} else {
    $conn->prepare('INSERT INTO carts (customer_id) VALUES (?)')->execute([$customer_id]);
    $cart_id = $conn->lastInsertId();
}

// Get product info from POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;
$selected_size = !empty($_POST['selected_size']) ? $_POST['selected_size'] : null;
$selected_color = !empty($_POST['selected_color']) ? $_POST['selected_color'] : null;

// Get color_id and size_id from names (if provided)
$color_id = null;
if ($selected_color) {
    $stmt = $conn->prepare('SELECT id FROM colors WHERE name = ?');
    $stmt->execute([$selected_color]);
    $row = $stmt->fetch();
    if ($row) $color_id = $row['id'];
}
$size_id = null;
if ($selected_size) {
    $stmt = $conn->prepare('SELECT id FROM sizes WHERE label = ?');
    $stmt->execute([$selected_size]);
    $row = $stmt->fetch();
    if ($row) $size_id = $row['id'];
}

// Check if this product/color/size is already in cart_items
$stmt = $conn->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ? AND (color_id <=> ?) AND (size_id <=> ?)');
$stmt->execute([$cart_id, $product_id, $color_id, $size_id]);
$item = $stmt->fetch();
if ($item) {
    // Update quantity
    $new_qty = $item['quantity'] + $quantity;
    $conn->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?')->execute([$new_qty, $item['id']]);
    $msg = 'Cart updated!';
} else {
    // Insert new item
    $conn->prepare('INSERT INTO cart_items (cart_id, product_id, color_id, size_id, quantity) VALUES (?, ?, ?, ?, ?)')
        ->execute([$cart_id, $product_id, $color_id, $size_id, $quantity]);
    $msg = 'Added to cart!';
}

// Redirect back with feedback (could use session or GET param)
header('Location: ' . $_SERVER['HTTP_REFERER'] . '?cart_msg=' . urlencode($msg));
exit;
?>


