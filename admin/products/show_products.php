<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <tr>
    <td class="product-cell">
      <div class="product-info">
        <img src="/Revaux-php/admin/images/logo.png" alt="Product Image" class="product-image">
        <div class="product-details">
          <div class="product-name">Denim Jacket</div>
          <div class="product-id">ID: SKUP12S2N</div>
        </div>
      </div>
    </td>==
    <td>$29.99</td>
    <td>Tops</td>
    <td><span class="status-badge in-stock">In Stock</span></td>
    <td>50</td>
    <td><span class="status-badge active">Active</span></td>
    <td class="action-cell">
      <button class="edit-btn">Edit</button>
      <button class="delete-btn">Delete</button>
    </td>
  </tr>
  <?php
    include dirname(__DIR__, 2) . '/database/database.php';
    // Connect to the database
    try {
      $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare("SELECT * FROM products ORDER BY id");
      $stmt->execute();

      $products = $stmt->fetchAll();

      foreach($products as $product) {
        echo "<tr>";
        echo "<td class='product-cell'>";
        echo "<div class='product-info'>";
        // Use absolute path from web root
        $imagePath = "/Revaux-php/admin/images/" . basename($product['image_url']);
        echo "<img src='" . htmlspecialchars($imagePath) . "' alt='Product Image' class='product-image'>";
        echo "<div class='product-details'>";
        echo "<div class='product-name'>" . htmlspecialchars($product['name']) . "</div>";
        echo "<div class='product-id'>ID: " . htmlspecialchars($product['id']) . "</div>";
        echo "</div>";
        echo "</div>";
        echo "</td>";
        echo "<td>$" . htmlspecialchars($product['price']) . "</td>";
        echo "<td>" . htmlspecialchars($product['category_id']) . "</td>";
        
        // Status based purely on quantity
        $quantity = (int)$product['quantity'];
        if ($quantity == 0) {
          $statusText = "Out of Stock";
          $statusClass = "out-of-stock";
        } elseif ($quantity < 50) {
          $statusText = "Low on Stock";
          $statusClass = "low-stock";
        } else {
          $statusText = "In Stock";
          $statusClass = "in-stock";
        }
        
        echo "<td><span class='status-badge $statusClass'>$statusText</span></td>";
        echo "<td>" . htmlspecialchars($product['quantity']) . "</td>";
        echo "<td><span class='status-badge active'>Active</span></td>";
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
</body>
</html>

