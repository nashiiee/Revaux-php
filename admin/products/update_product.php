<?php
include '../../revauxDatabase/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connect to database
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Validate required fields
        $missingFields = [];
        if (empty($_POST['productId'])) $missingFields[] = 'productId';
        if (empty($_POST['productName'])) $missingFields[] = 'productName';
        if (empty($_POST['productPrice'])) $missingFields[] = 'productPrice';
        if (empty($_POST['productCategory'])) $missingFields[] = 'productCategory';
        if (empty($_POST['productStock'])) $missingFields[] = 'productStock';
        if (empty($_POST['productDetailsId'])) $missingFields[] = 'productDetailsId';
        
        if (!empty($missingFields)) {
            error_log("Missing fields in update_product.php: " . implode(', ', $missingFields));
            error_log("POST data received: " . print_r($_POST, true));
            header("Location: edit_product.php?id=" . $_POST['productId'] . "&error=missing_fields&fields=" . urlencode(implode(',', $missingFields)));
            exit();
        }
        
        $productId = intval($_POST['productId']);
        
        // Handle file upload (optional for updates)
        $imageUrl = null;
        $updateImage = false;
        
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
            $uploadDir = '../images/';
            $fileExtension = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                header("Location: edit_product.php?id=" . $productId . "&error=invalid_file_type");
                exit();
            }
            
            // Generate unique filename
            $fileName = uniqid() . '_' . $_FILES['productImage']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadPath)) {
                $imageUrl = $fileName;
                $updateImage = true;
            } else {
                header("Location: edit_product.php?id=" . $productId . "&error=upload_failed");
                exit();
            }
        }
        
        // Sanitize input data
        $name = htmlspecialchars(trim($_POST['productName']));
        $price = floatval($_POST['productPrice']);
        $quantity = intval($_POST['productStock']);
        $categoryId = intval($_POST['productCategory']);
        $subcategoryId = !empty($_POST['productSubcategory']) ? intval($_POST['productSubcategory']) : null;
        $productDetailsId = htmlspecialchars(trim($_POST['productDetailsId']));
        
        // Update database - with or without image
        if ($updateImage) {
            $sql = "UPDATE products SET name = :name, price = :price, image_url = :image_url, quantity = :quantity, 
                    category_id = :category_id, subcategory_id = :subcategory_id, 
                    product_details_id = :product_details_id, updated_at = NOW() WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':image_url', $imageUrl);
        } else {
            $sql = "UPDATE products SET name = :name, price = :price, quantity = :quantity, 
                    category_id = :category_id, subcategory_id = :subcategory_id, 
                    product_details_id = :product_details_id, updated_at = NOW() WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
        }
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':subcategory_id', $subcategoryId);
        $stmt->bindParam(':product_details_id', $productDetailsId);
        $stmt->bindParam(':id', $productId);
        
        if ($stmt->execute()) {
            // Redirect back to products page with success message
            header("Location: ../products.php?success=product_updated");
            exit();
        } else {
            header("Location: edit_product.php?id=" . $productId . "&error=database_error");
            exit();
        }
        
    } catch (PDOException $e) {
        // Log error with more details for debugging
        error_log("Database error in update_product.php: " . $e->getMessage());
        error_log("POST data: " . print_r($_POST, true));
        header("Location: edit_product.php?id=" . $_POST['productId'] . "&error=database_error&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect if not POST request
    header("Location: ../products.php");
    exit();
}
?>
