<?php
    // delete_cart_items.php

    session_start();
    header('Content-Type: application/json');

    function isLoggedIn() {
        return isset($_SESSION['username']);
    }

    require_once __DIR__ . '/../database/database.php';

    $response = ['success' => false, 'msg' => ''];

    // key change: check if the $conn object was created.
    if (!isset($conn)) {
        $response['msg'] = 'Database connection could not be established.';
        echo json_encode($response);
        exit();
    }
    
    if (!isLoggedIn()) {
        http_response_code(401);
        $response['msg'] = 'Unauthorized: Please log in to modify your cart.';
        echo json_encode($response);
        exit();
    }
    
    $username = $_SESSION['username'];

    // --- The rest of your code remains the same ---
    try {
        $stmt_customer = $conn->prepare('SELECT id FROM customers WHERE username = ?');
        $stmt_customer->execute([$username]);
        $customer = $stmt_customer->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            $response['msg'] = 'Customer not found.';
            echo json_encode($response);
            exit();
        }
        $customer_id = $customer['id'];
    } catch (PDOException $e) {
        $response['msg'] = 'Database error: Could not retrieve customer information.';
        error_log("Customer lookup PDO error: " . $e->getMessage());
        echo json_encode($response);
        exit();
    }


    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!isset($data['cart_item_ids']) || !is_array($data['cart_item_ids']) || empty($data['cart_item_ids'])) {
        $response['msg'] = 'No cart items selected for removal or invalid data format.';
        echo json_encode($response);
        exit();
    }

    $cart_item_ids_to_delete = $data['cart_item_ids'];

    $cleaned_cart_item_ids = array_filter(
        array_map('intval', $cart_item_ids_to_delete),
        function($id) {
            return $id > 0;
        }
    );

    if (empty($cleaned_cart_item_ids)) {
        $response['msg'] = 'Invalid cart item IDs provided after sanitization.';
        echo json_encode($response);
        exit();
    }

    $stmt_cart = $conn->prepare('SELECT id FROM carts WHERE customer_id = ?');
    $stmt_cart->execute([$customer_id]);
    $user_cart = $stmt_cart->fetch(PDO::FETCH_ASSOC);

    if (!$user_cart) {
        $response['msg'] = 'Your cart is empty or does not exist.';
        echo json_encode($response);
        exit();
    }

    $user_cart_id = $user_cart['id'];

    $conn->beginTransaction();

    try {
        $placeholders = implode(',', array_fill(0, count($cleaned_cart_item_ids), '?'));
        
        $sql = "DELETE FROM cart_items WHERE id IN ($placeholders) AND cart_id = ?";
        $stmt = $conn->prepare($sql);

        $params = array_merge($cleaned_cart_item_ids, [$user_cart_id]);
        $stmt->execute($params);

        $rows_affected = $stmt->rowCount();

        if ($rows_affected > 0) {
            $conn->commit();
            $response['success'] = true;
            $response['msg'] = "Successfully removed {$rows_affected} item(s) from cart.";
        } else {
            $conn->rollBack();
            $response['msg'] = 'No items found matching your selection or permission criteria in your cart.';
        }

    } catch (PDOException $e) {
        $conn->rollBack();
        $response['msg'] = 'Database error: An unexpected error occurred while trying to remove items.';
        error_log("Cart item deletion PDO error: " . $e->getMessage() . " - SQL: " . $sql);
    }

    echo json_encode($response);

?>