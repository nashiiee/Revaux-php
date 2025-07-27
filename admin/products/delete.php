<?php
include '../../revauxDatabase/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    try {
        // Connect to database
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $productId = intval($_POST['product_id']);
        
        // Check if product exists
        $checkStmt = $conn->prepare("SELECT id, image_url FROM products WHERE id = :id");
        $checkStmt->bindParam(':id', $productId);
        $checkStmt->execute();
        
        $product = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            // Check if this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            } else {
                header("Location: ../products.php?error=product_not_found");
            }
            exit();
        }
        
        // Delete the product
        $deleteStmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $deleteStmt->bindParam(':id', $productId);
        
        if ($deleteStmt->execute()) {
            // Optionally delete the image file
            if ($product['image_url'] && file_exists('../admin/images/' . $product['image_url'])) {
                unlink('../admin/images/' . $product['image_url']);
            }
            
            // Check if this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
            } else {
                header("Location: ../products.php?success=product_deleted");
            }
        } else {
            // Check if this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
            } else {
                header("Location: ../products.php?error=delete_failed");
            }
        }
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        // Check if this is an AJAX request
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        } else {
            header("Location: ../products.php?error=database_error");
        }
    }
} else {
    // Check if this is an AJAX request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    } else {
        header("Location: ../products.php?error=invalid_request");
    }
}
?>
