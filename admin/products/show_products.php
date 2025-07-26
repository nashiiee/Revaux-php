<?php
  include dirname(__DIR__, 2) . '/revauxDatabase/database.php';
  // Connect to the database
  try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if category filter is applied
    $categoryFilter = isset($_GET['category']) && $_GET['category'] !== 'all' ? $_GET['category'] : null;
    
    if ($categoryFilter) {
      $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :category ORDER BY id");
      $stmt->bindParam(':category', $categoryFilter);
    } else {
      $stmt = $conn->prepare("SELECT * FROM products ORDER BY id");
    }
    
    $stmt->execute();

    $products = $stmt->fetchAll();

    foreach($products as $product) {
      echo "<tr>";
      echo "<td class='product-cell'>";
      echo "<div class='product-info'>";
      
      // Smart image assignment - no duplicates, ignore database image_url
      $productName = strtolower($product['name']);
      $productId = $product['id'];
      
      // Define available images for each category
      $imageCategories = [
        'cap' => ['caps1.svg', 'caps2.svg', 'caps3.svg', 'caps4.svg'],
        'hat' => ['hat1.svg', 'hat2.svg', 'hat3.svg', 'hat4.svg'], 
        'eyewear' => ['eyeglass1.svg', 'eyeglass2.svg', 'eyeglass3.svg', 'eyeglass4.svg'],
        'bandana' => ['bandana1.svg', 'bandana2.svg', 'bandana3.svg', 'bandana4.svg'],
        'shirt' => ['shirt1.svg', 'shirt2.svg', 'shirt3.svg', 'shirt4.svg'],
        't-shirt' => ['shirt1.svg', 'shirt2.svg', 'shirt3.svg', 'shirt4.svg'],
        'polo' => ['polo1.svg', 'polo2.svg', 'polo3.svg', 'polo4.svg'],
        'sweater' => ['sweater1.svg', 'sweater2.svg', 'sweater3.svg', 'sweater4.svg'],
        'hoodie' => ['hoodie1.svg', 'hoodie2.svg', 'hoodie3.svg', 'hoodie4.svg'],
        'jean' => ['jeans1.svg', 'jeans2.svg', 'jeans3.svg', 'jeans4.svg'],
        'short' => ['shorts1.svg', 'shorts2.svg', 'shorts3.svg', 'shorts4.svg'],
        'trouser' => ['trouser1.svg', 'trouser2.svg', 'trouser3.svg', 'trouser4.svg'],
        'cargo' => ['pants1.svg', 'pants2.svg', 'pants3.svg', 'pants4.svg'],
        'pant' => ['pants1.svg', 'pants2.svg', 'pants3.svg', 'pants4.svg'],
        'sneaker' => ['sneakers1.svg', 'sneakers2.svg', 'sneakers3.svg', 'sneakers4.svg'],
        'sandal' => ['sandals1.svg', 'sandals2.svg', 'sandals3.svg', 'sandals4.svg'],
        'boot' => ['boots1.svg', 'boots2.svg', 'boots3.svg', 'boots4.svg'],
        'loafer' => ['loafer1.svg', 'loafer2.svg', 'loafer3.svg', 'loafer4.svg']
      ];
      
      // Find matching category and assign unique image
      $selectedImage = 'shirt1.svg'; // default fallback
      foreach ($imageCategories as $category => $images) {
        if (strpos($productName, $category) !== false) {
          // Use last 4 digits of product ID to select image variant
          $lastDigits = intval(substr($productId, -4));
          $imageIndex = $lastDigits % count($images);
          $selectedImage = $images[$imageIndex];
          break;
        }
      }
      
      $imagePath = "/Revaux-php/admin/images/" . $selectedImage;
      echo "<img src='" . htmlspecialchars($imagePath) . "' alt='Product Image' class='product-image'>";
      echo "<div class='product-details'>";
      echo "<div class='product-name'>" . htmlspecialchars($product['name']) . "</div>";
      echo "<div class='product-id'>ID: " . htmlspecialchars($product['id']) . "</div>";
      echo "</div>";
      echo "</div>";
      echo "</td>";
      echo "<td>₱" . htmlspecialchars($product['price']) . "</td>";
      echo "<td>" . htmlspecialchars($product['category_id']) . "</td>";
      
      // Status based purely on quantity
      $quantity = (int)$product['quantity'];
      if ($quantity == 0) {
        $statusText = "Out of Stock";
        $statusClass = "out-of-stock";
      } elseif ($quantity < 35) {
        $statusText = "Low on Stock";
        $statusClass = "low-stock";
      } else {
        $statusText = "In Stock";
        $statusClass = "in-stock";
      }

      // Active/Inactive Status with better logic
      if ($quantity > 0) {
        $activeStatusText = "Active";
        $activeStatusClass = "active";
      } else {
        $activeStatusText = "Inactive";
        $activeStatusClass = "inactive";
      }
      
      echo "<td><span class='status-badge $statusClass'>$statusText</span></td>";
      echo "<td>" . htmlspecialchars($product['quantity']) . "</td>";
      echo "<td><span class='status-badge $activeStatusClass'>$activeStatusText</span></td>";
      echo "<td class='action-cell'>";
      echo "<button class='edit-btn'>Edit</button>";
      echo "<button class='delete-btn'>Delete</button>";
      echo "</td>";
      echo "</tr>";
    }
  } catch (PDOException $e) {
      echo "<tr><td colspan='7'>Database error: " . $e->getMessage() . "</td></tr>";
  }
?>


