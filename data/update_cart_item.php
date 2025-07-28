<?php
    session_start();
    require_once __DIR__ . '/../database/database.php'; // Ensure this path is correct

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'msg' => 'Method Not Allowed']);
        exit;
    }

    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'msg' => 'Not logged in']);
        exit;
    }

    $cart_item_id = isset($_POST['cart_item_id']) ? (int)$_POST['cart_item_id'] : 0;
    $new_qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default to 1 if not set

    // Basic input validation
    if ($cart_item_id <= 0) { // Quantity can be clamped later, but ID must be valid
        echo json_encode(['success' => false, 'msg' => 'Invalid cart item ID.']);
        exit;
    }

    try {
        // Get customer id
        $stmt = $conn->prepare('SELECT id FROM customers WHERE username = ?');
        $stmt->execute([$_SESSION['username']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC); // Use PDO::FETCH_ASSOC for consistency
        if (!$customer) {
            echo json_encode(['success' => false, 'msg' => 'Customer not found.']);
            exit;
        }
        $customer_id = $customer['id'];

        // Check that the cart_item belongs to this user's cart and get product stock
        $stmt = $conn->prepare('SELECT ci.id, ci.product_id, p.quantity AS stock, ci.quantity AS current_cart_quantity, ci.cart_id
            FROM cart_items ci
            JOIN carts c ON ci.cart_id = c.id
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id = ? AND c.customer_id = ?
        ');
        $stmt->execute([$cart_item_id, $customer_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            echo json_encode(['success' => false, 'msg' => 'Cart item not found or does not belong to your cart.']);
            exit;
        }

        $product_stock = $item['stock']; // Max available stock for this product
        $original_new_qty = $new_qty; // Keep track of the quantity requested by client

        if ($new_qty > $product_stock) {
            $new_qty = $product_stock; // Clamp to max available stock
        }
        if ($new_qty < 1) {
            $new_qty = 1; // Ensure minimum quantity is 1
        }

        // Only update if the quantity has actually changed after clamping
        // This prevents unnecessary database writes and allows feedback when clamped
        if ($new_qty != $item['current_cart_quantity'] || $new_qty != $original_new_qty) {
            $update_stmt = $conn->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
            $update_stmt->execute([$new_qty, $cart_item_id]);
        }

        // Prepare response
        $response = [
            'success' => true,
            'quantity' => $new_qty, // Return the actual quantity set in the database
            'msg' => 'Quantity updated.'
        ];

        // Add a specific message if the quantity was clamped due to stock
        if ($original_new_qty > $product_stock && $new_qty == $product_stock) {
            $response['msg'] = 'Quantity adjusted to maximum available stock (' . $product_stock . ').';
        } elseif ($original_new_qty < 1 && $new_qty == 1) {
            $response['msg'] = 'Quantity adjusted to minimum of 1.';
        }

        echo json_encode($response);

    } catch (PDOException $e) {
        // Log the error for debugging purposes (check your PHP error logs)
        error_log("Cart update PDO Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'msg' => 'Database error occurred. Please try again.']);
    } catch (Exception $e) {
        // Catch any other unexpected errors
        error_log("Cart update general Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'msg' => 'An unexpected error occurred.']);
    }

    exit;
?>