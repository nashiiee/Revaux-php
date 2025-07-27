<?php 
    //connect to the database
    require_once __DIR__ . '/../../database/database.php';

    // 2. Make sure $conn was created successfully
    if (!isset($conn) || !$conn instanceof PDO) {
        die('Database connection failed.');
    }

    if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
        die('Invalid product ID.');
    }

    $product_id = (int) $_GET['id'];
    
    $stmt = $conn->prepare("SELECT 
            p.*,
            pd.origin, pd.material, pd.care_instructions, pd.closure_type, pd.extra_description,
            c.id AS category_id, c.name AS category_name,
            sc.id AS subcategory_id, sc.name AS subcategory_name
        FROM products p
        JOIN product_details pd ON p.product_details_id = pd.id
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
        WHERE p.id = :idz
    ");

    $stmt->execute([':idz' => $product_id]);
    $product = $stmt->fetch();

    if(!$product) {
        die('Product not found.');
    }

    $sizestmt = $conn->prepare("SELECT s.id, s.label 
        FROM sizes s
        JOIN product_sizes ps ON s.id = ps.size_id
        WHERE ps.product_id = :product_idz
        ORDER BY FIELD(s.label, 'S', 'M', 'L') 
    ");

    $sizestmt->execute(['product_idz' => $product_id]);
    $sizes = $sizestmt->fetchAll();

    $colorstmt = $conn->prepare("SELECT c.name
        FROM colors c
        JOIN product_colors pc ON c.id = pc.color_id
        WHERE pc.product_id = :product_idz
    ");
    $colorstmt->execute(['product_idz' => $product_id]);
    $colors = $colorstmt->fetchAll();

    // pick the first color name or empty string
    $defaultColor = count($colors) ? $colors[0]['name'] : '';

    $variantstmt = $conn->prepare("SELECT 
            pcv.image_url,
            pcv.stock,
            c.name AS color_name
        FROM product_color_variants pcv
        JOIN colors c ON pcv.color_id = c.id
        WHERE pcv.product_id = :product_idz
    ");
    $variantstmt->execute(['product_idz' => $product_id]);
    $variants = $variantstmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> | Revaux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../images/revaux-light.png">
    <link rel="stylesheet" href="../../css/categories.css">
    <link rel="stylesheet" href="../../css/product_info.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <!-- to make sure customers can't order more than the stock of products -->
    <script>
        const MAX_QUANTITY = <?= (int) $product['quantity'] ?>;
    </script>
</head>
<body>
    <!-- temporary header(pretty bad)-->
    <header class="navbar">
        <div class="logo">
            <!-- change the path if the logo isnt working -->
            <a href="../../index.html" class="logo-link">
                <img src="../../images/revaux-light.png" class="logo-img" alt="Revaux Logo">
                Revaux
            </a>
        </div>
        <input type="text" placeholder="Search Here..." class="search-bar">
        <div class="nav-icons">
            <span>🔔 Notifications</span>
            <span>❓ FAQs</span>
            <span>🤍 Wishlist</span>
            <a href="../user/view_cart.html" id="cart">🛒 Cart</a>
            <span>👤 Russo</span>
        </div>
    </header>

    <!-- Breadcrumb navigation -->
    <div class="breadcrumb-bar">
        <a href="../../../index.html" class="breadcrumb-link">Home</a>
        <span class="breadcrumb-separator">&gt;</span>

        <!-- dynamic category link to category.php -->
        <a href="../categories/category.php?category=<?= urlencode($product['category_name']) ?>" class="breadcrumb-link">
            <?= htmlspecialchars($product['category_name']) ?>
        </a>

        <!-- dynamic subcategory link (if it exists) -->
        <?php if ($product['subcategory_name']): ?>
            <span class="breadcrumb-separator">&gt;</span>   
            <a href="../categories/category.php?category=<?= urlencode($product['category_name']) ?>&sub=<?= $product['subcategory_id'] ?>" class="breadcrumb-link">
                <?= htmlspecialchars($product['subcategory_name']) ?>
            </a>
        <?php endif; ?>

        <!-- current product name -->
        <span class="breadcrumb-separator">&gt;</span>
        <span class="breadcrumb-current"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="container product-info-container">
        <main class="product-info-page">
            <section class="product-details">
                <!-- LEFT COLUMN: Main image + thumbnails -->
                <div class="pd-image-column">
                    <div class="pd-main-image">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($defaultColor) ?>">
                    </div>
                    <div class="pd-thumbnails">
                        <button class="thumb active" data-color="<?= htmlspecialchars($defaultColor) ?>">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name'])?>">
                        </button>

                        <!-- Doesn't go through the loop if the product_id doesn't have any color_variants/not in the product_color_variants table -->
                        <?php
                            $variantCount = count($variants);
                            $maxVisible = 6;
                            $remaining = max(0, $variantCount - $maxVisible);
                        ?>

                        <?php foreach (array_slice($variants, 0, $maxVisible) as $variant): ?>
                            <button class="thumb" data-color="<?= htmlspecialchars($variant['color_name']) ?>">
                                <img src="<?= htmlspecialchars($variant['image_url']) ?>" alt="<?= htmlspecialchars($variant['color_name']) ?>">
                            </button>
                        <?php endforeach; ?>

                        <?php if ($remaining > 0): ?>
                            <button class="thumb more">+<?= $remaining ?></button>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- RIGHT COLUMN: Info block -->
                <div class="pd-info-column">
                    <h1 class="pd-title">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>
                    <div class="pd-divider"></div>

                    <div class="pd-pricing">
                        <p class="pd-price">₱<?= number_format($product['price'], 2) ?></p>
                        <!-- uncomment when discount feature is implemented
                        <p class="pd-discount">–50% <span class="pd-original-price">₱500.00</span></p>
                        -->
                    </div>

                    <form action="../user/view_cart.html" method="post" class="pd-actions-form">
                        <?php $isOutOfStock = $product['quantity'] <= 0; ?>
                        <div class="pd-quantity">
                            <label for="quantity">Quantity:</label>
                            <span>
                                <button type="button" class="qty-btn-minus" <?= $isOutOfStock ? 'disabled' : '' ?>>–</button>
                                <input 
                                    type="text" 
                                    class="qty-input" 
                                    id="quantity"
                                    value="<?= $isOutOfStock ? '0' : '1' ?>" 
                                    min="1" 
                                    <?= $isOutOfStock ? 'disabled' : '' ?>
                                >
                                <button type="button" class="qty-btn-plus" <?= $isOutOfStock ? 'disabled' : '' ?>>+</button>
                            </span>

                            <?php if ($isOutOfStock): ?>
                                <p class="stock-warning">Out of stock</p>
                            <?php elseif ($product['quantity'] < 20): ?>
                                <p class="stock-warning">Only <?= $product['quantity'] ?> left in stock!</p>
                            <?php endif; ?>
                        </div>


                        <div class="pd-sizes">
                            <label for="selected-size">Size:</label>
                            <?php if (!empty($sizes)): ?>
                                <div class="size-options">
                                    <?php foreach ($sizes as $size): ?>
                                        <button class="size-btn" type="button"><?= htmlspecialchars($size['label']) ?></button>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="selected_size" id="selected-size" required>
                            <?php else: ?>
                                <!-- This pops up when the product doesnt have any sizes(insert in product_size table if you want to) -->
                                <p class="no-sizes">One Size Fits All</p>
                            <?php endif; ?>
                        </div>

                        <div class="pd-actions">
                            <button class="btn buy-now guest-only" type="submit">Buy Now</button>
                            <button class="btn add-cart guest-only" type="submit">Add to Cart</button>
                        </div>
                    </form>
                    
                    <div class="pd-divider"></div>

                    <div class="pd-details-section">
                        <h2>Product Details</h2>
                        <ul>
                            <li><strong>Origin:</strong><?= htmlspecialchars($product['origin']) ?></li>
                            <li>
                                <strong>Colour:</strong>
                                <span class="pd-color"><?= htmlspecialchars($defaultColor) ?></span>
                            </li>
                            <li><strong>Material:</strong><?= htmlspecialchars($product['material']) ?></li>
                            <li><strong>Care Instructions:</strong><?= htmlspecialchars($product['care_instructions']) ?></li>
                            <li><strong>Closure Type:</strong><?= htmlspecialchars($product['closure_type']) ?></li>
                        </ul>
                    </div>

                    <div class="pd-divider"></div> 

                    <div class="pd-about-section">
                        <h2>About this Product</h2>
                        <div class="pd-about-wrapper">
                            <p class="pd-about-text">
                                <?= nl2br(htmlspecialchars($product['extra_description'])) ?>
                            </p>
                        </div>
                        <button class="pd-about-toggle">▼ Read More</button>
                    </div>
                </div>
                <div class="pd-zoom-window"></div>
            </section>
        </main>
    </div>
    <!-- JS -->
    <script type="module" src="../../scripts/main.js"></script>
                
</body>
</html>