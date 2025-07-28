<?php
  include '../../revauxDatabase/database.php';

  // Check if product ID is provided
  if (!isset($_GET['id']) || empty($_GET['id'])) {
      header("Location: ../products.php?error=no_product_id");
      exit();
  }

  $productId = intval($_GET['id']);

  try {
      // Connect to database
      $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      // Fetch product details
      $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
      $stmt->bindParam(':id', $productId);
      $stmt->execute();
      
      $product = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$product) {
          header("Location: ../products.php?error=product_not_found");
          exit();
      }
      
  } catch (PDOException $e) {
      header("Location: ../products.php?error=database_error");
      exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  <link rel="stylesheet" href="../../dist/addProducts.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <aside>
    <div class="logo">
      <img src="../images/logo.png" alt="Revaux Logo">
      <span>Revaux</span>
    </div>
    <div class="links">
      <ul class="nav-links">
        <li>
          <a href="../dashboard.html">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="../transactions.html">
            <i class="fa-solid fa-credit-card"></i>
            <span>Transactions</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-chart-line"></i>
            <span>Sales</span>
          </a>
        </li>
        <li>
          <a href="../products.php">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
          </a>
        </li>
      </ul>
      <span class="tools-description">Tools</span>
      <ul class="nav-links">
        <li>
          <a href="">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-user-gear"></i>
            <span>Manage User</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="logout-container">
      <a href="" class="logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  
  <main class="main">
    <header>
      <h1>Edit Product</h1>
    </header>
    <div class="main-section-container">
      <form action="update_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($product['id']); ?>">
        
        <div class="form-group">
          <label for="productName">Product Name</label>
          <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="productPrice">Product Price</label>
          <input type="number" id="productPrice" name="productPrice" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="productImage">Product Image (Optional - leave empty to keep current image)</label>
          <input type="file" id="productImage" name="productImage" accept="image/*">
          <?php if ($product['image_url']): ?>
            <p class="current-image">Current image: <?php echo htmlspecialchars($product['image_url']); ?></p>
          <?php endif; ?>
        </div>
        
        <div class="form-group">
          <label for="productCategory">Product Category</label>
          <select id="productCategory" name="productCategory" required>
            <option value="">Select Category</option>
            <option value="1" <?php echo $product['category_id'] == 1 ? 'selected' : ''; ?>>Headwear</option>
            <option value="2" <?php echo $product['category_id'] == 2 ? 'selected' : ''; ?>>Tops</option>
            <option value="3" <?php echo $product['category_id'] == 3 ? 'selected' : ''; ?>>Bottoms</option>
            <option value="4" <?php echo $product['category_id'] == 4 ? 'selected' : ''; ?>>Footwear</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="productSubcategory">Product Subcategory</label>
          <select id="productSubcategory" name="productSubcategory">
            <option value="">Select Subcategory (Optional)</option>
            <option value="1" <?php echo $product['subcategory_id'] == 1 ? 'selected' : ''; ?>>Caps</option>
            <option value="2" <?php echo $product['subcategory_id'] == 2 ? 'selected' : ''; ?>>Eyewear</option>
            <option value="3" <?php echo $product['subcategory_id'] == 3 ? 'selected' : ''; ?>>Hats</option>
            <option value="4" <?php echo $product['subcategory_id'] == 4 ? 'selected' : ''; ?>>Bandanas</option>
            <option value="5" <?php echo $product['subcategory_id'] == 5 ? 'selected' : ''; ?>>T-shirts</option>
            <option value="6" <?php echo $product['subcategory_id'] == 6 ? 'selected' : ''; ?>>Polo Shirts</option>
            <option value="7" <?php echo $product['subcategory_id'] == 7 ? 'selected' : ''; ?>>Sweaters</option>
            <option value="8" <?php echo $product['subcategory_id'] == 8 ? 'selected' : ''; ?>>Hoodies</option>
            <option value="9" <?php echo $product['subcategory_id'] == 9 ? 'selected' : ''; ?>>Jeans</option>
            <option value="10" <?php echo $product['subcategory_id'] == 10 ? 'selected' : ''; ?>>Shorts</option>
            <option value="11" <?php echo $product['subcategory_id'] == 11 ? 'selected' : ''; ?>>Trousers</option>
            <option value="12" <?php echo $product['subcategory_id'] == 12 ? 'selected' : ''; ?>>Cargo Pants</option>
            <option value="13" <?php echo $product['subcategory_id'] == 13 ? 'selected' : ''; ?>>Sneakers</option>
            <option value="14" <?php echo $product['subcategory_id'] == 14 ? 'selected' : ''; ?>>Sandalsort</option>
            <option value="15" <?php echo $product['subcategory_id'] == 15 ? 'selected' : ''; ?>>Boots</option>
            <option value="16" <?php echo $product['subcategory_id'] == 16 ? 'selected' : ''; ?>>Loafers</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="productStock">Product Stock</label>
          <input type="number" id="productStock" name="productStock" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="productDetailsId">Product Details ID</label>
          <input type="text" id="productDetailsId" name="productDetailsId" value="<?php echo htmlspecialchars($product['product_details_id']); ?>" required>
        </div>
        
        <div class="form-actions">
          <input type="submit" value="Update Product" class="submit-btn">
          <a href="../products.php" class="cancel-btn">Cancel</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Handle URL parameters for success/error messages
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const msg = urlParams.get('msg');
    const fields = urlParams.get('fields');
    
    if (error) {
      let message = '';
      switch(error) {
        case 'no_product_id':
          message = 'No product ID provided.';
          break;
        case 'product_not_found':
          message = 'Product not found.';
          break;
        case 'missing_fields':
          message = 'Missing required fields: ' + (fields || 'Unknown fields');
          break;
        case 'invalid_file_type':
          message = 'Invalid file type. Please upload JPG, PNG, GIF, or SVG files only.';
          break;
        case 'upload_failed':
          message = 'File upload failed. Please try again.';
          break;
        case 'database_error':
          message = 'Database error occurred' + (msg ? ': ' + msg : '.');
          break;
        default:
          message = 'An error occurred.';
      }
      alert('Error: ' + message);
      
      // Clear URL parameters after showing error
      if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('error');
        url.searchParams.delete('msg');
        url.searchParams.delete('fields');
        window.history.replaceState({}, document.title, url);
      }
    }
  </script>
</body>
</html>