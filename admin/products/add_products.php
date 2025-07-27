<?php
include '../../revauxDatabase/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Validate required fields
        if (empty($_POST['productName']) || empty($_POST['productPrice']) || empty($_POST['productStock'])) {
            header("Location: ../insertProducts.html?error=missing_fields");
            exit();
        }
        
        // Handle file upload
        $imageUrl = null;
        if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === 0) {
            $uploadDir = '../images/';
            $fileExtension = strtolower(pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                header("Location: ../insertProducts.html?error=invalid_file_type");
                exit();
            }
            
            // Generate unique filename
            $fileName = uniqid() . '_' . $_FILES['productImage']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadPath)) {
                $imageUrl = './images/' . $fileName;
            } else {
                header("Location: ../insertProducts.html?error=upload_failed");
                exit();
            }
        }
        
        // Sanitize input data
        $name = htmlspecialchars(trim($_POST['productName']));
        $price = floatval($_POST['productPrice']);
        $quantity = intval($_POST['productStock']);
        $categoryId = htmlspecialchars(trim($_POST['productCategory']));
        
        // Insert into database - matching exact schema
        $sql = "INSERT INTO products (name, price, image_url, quantity, category_id) 
                VALUES (:name, :price, :image_url, :quantity, :category_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':category_id', $categoryId);

        if ($stmt->execute()) {
            // Redirect back to products page with success message
            header("Location: ../products.php?success=product_added");
            exit();
        } else {
            header("Location: ../insertProducts.html?error=database_error");
            exit();
        }
        
    } catch (PDOException $e) {
        // Log error (in production, don't show sensitive info)
        error_log("Database error: " . $e->getMessage());
        header("Location: ../insertProducts.html?error=database_error");
        exit();
    }
} else {
    // Redirect if not POST request
    header("Location: ../insertProducts.html");
    exit();
}
?>