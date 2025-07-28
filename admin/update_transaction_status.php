<?php
header('Content-Type: application/json');
include '../revauxDatabase/database.php';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $product_id = $_POST['product_id'] ?? null;
    $status_type = $_POST['status_type'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    if (!$product_id || !$status_type || !$new_status) {
        throw new Exception('Missing required parameters');
    }

    // Validate status type
    if (!in_array($status_type, ['order_status', 'payment_status'])) {
        throw new Exception('Invalid status type');
    }

    // Validate status values
    $valid_order_statuses = ['Processing', 'Delivered', 'Cancelled', 'Pending'];
    $valid_payment_statuses = ['Paid', 'Pending', 'Failed'];

    if ($status_type === 'order_status' && !in_array($new_status, $valid_order_statuses)) {
        throw new Exception('Invalid order status');
    }

    if ($status_type === 'payment_status' && !in_array($new_status, $valid_payment_statuses)) {
        throw new Exception('Invalid payment status');
    }

    // Find the order ID based on product_id from order_items
    $findOrderQuery = "SELECT DISTINCT o.id as order_id FROM orders o 
                       JOIN order_items oi ON o.id = oi.order_id 
                       WHERE oi.product_id = ?";
    $findOrderStmt = $conn->prepare($findOrderQuery);
    $findOrderStmt->execute([$product_id]);
    $orders = $findOrderStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($orders)) {
        throw new Exception('No orders found for this product');
    }

    // Update all orders containing this product
    $updated_count = 0;
    foreach ($orders as $order) {
        if ($status_type === 'order_status') {
            $updateQuery = "UPDATE orders SET status = ? WHERE id = ?";
        } else {
            $updateQuery = "UPDATE orders SET payment_status = ? WHERE id = ?";
        }

        $updateStmt = $conn->prepare($updateQuery);
        $result = $updateStmt->execute([$new_status, $order['order_id']]);
        
        if ($result) {
            $updated_count++;
        }
    }

    if ($updated_count > 0) {
        echo json_encode([
            'success' => true,
            'message' => "Successfully updated $updated_count order(s)",
            'updated_count' => $updated_count
        ]);
    } else {
        throw new Exception('No orders were updated');
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
