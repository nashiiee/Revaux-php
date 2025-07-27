<?php
    // Connect to database
    require_once __DIR__ . '/../database/database.php';

    function buildPageUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        return 'category.php?' . http_build_query($params);
    }

    $categoryName = $_GET['category'] ?? null;
    $subcategoryId = $_GET['sub'] ?? null;
    $sortOption = $_GET['sort'] ?? 'default';

    if (!$categoryName) {
        die("Category not specified.");
    }

    // Get category ID
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = :namez");
    $stmt->execute([':namez' => $categoryName]);
    $category = $stmt->fetch();
    if (!$category) {
        die("Category not found.");
    }
    $categoryId = $category['id'];

    // Get subcategory name
    $subcategoryName = null;
    if ($subcategoryId) {
        $substmt = $conn->prepare("SELECT name FROM subcategories WHERE id = :idz");
        $substmt->execute([':idz' => $subcategoryId]);
        $sub = $substmt->fetch();
        if ($sub) {
            $subcategoryName = $sub['name'];
        }
    }

    // Pagination setup
    $perPage = 12;
    $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($currentPage - 1) * $perPage;

    $params = [':cat_idz' => $categoryId];
    $productQuery = "SELECT 
        p.*, 
        c.name AS category_name, 
        sc.name AS subcategory_name, 
        IFNULL(psc.sold_count, 0) AS sold_count
        FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
        LEFT JOIN product_sold_counts psc ON p.id = psc.product_id
        WHERE p.category_id = :cat_idz";

    $countQuery = "SELECT COUNT(*) FROM products p WHERE p.category_id = :cat_idz";
    $countParams = [':cat_idz' => $categoryId];

    if ($subcategoryId) {
        $productQuery .= " AND p.subcategory_id = :sub_idz";
        $countQuery .= " AND p.subcategory_id = :sub_idz";
        $params[':sub_idz'] = $countParams[':sub_idz'] = $subcategoryId;
    }

    if (!empty($_GET['size']) && is_array($_GET['size'])) {
        foreach ($_GET['size'] as $i => $sizeId) {
            $paramName = ":size_$i";
            $params[$paramName] = $countParams[$paramName] = $sizeId;
            $sizeParams[] = $paramName;
        }
        $inClause = implode(',', $sizeParams);
        $productQuery .= " AND p.id IN (SELECT product_id FROM product_sizes WHERE size_id IN ($inClause))";
        $countQuery .= " AND p.id IN (SELECT product_id FROM product_sizes WHERE size_id IN ($inClause))";
    }

    if (!empty($_GET['color']) && is_array($_GET['color'])) {
        foreach ($_GET['color'] as $i => $colorId) {
            $paramName = ":color_$i";
            $params[$paramName] = $countParams[$paramName] = $colorId;
            $colorParams[] = $paramName;
        }
        $inClause = implode(',', $colorParams);
        $productQuery .= " AND p.id IN (SELECT product_id FROM product_colors WHERE color_id IN ($inClause))";
        $countQuery .= " AND p.id IN (SELECT product_id FROM product_colors WHERE color_id IN ($inClause))";
    }

    if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
        $params[':min_price'] = $countParams[':min_price'] = $_GET['min_price'];
        $productQuery .= " AND p.price >= :min_price";
        $countQuery .= " AND p.price >= :min_price";
    }
    if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
        $params[':max_price'] = $countParams[':max_price'] = $_GET['max_price'];
        $productQuery .= " AND p.price <= :max_price";
        $countQuery .= " AND p.price <= :max_price";
    }

    if (isset($_GET['in_stock'])) {
        $productQuery .= " AND p.quantity > 0";
        $countQuery .= " AND p.quantity > 0";
    }

    // Sorting
    switch ($sortOption) {
        case 'price-asc':
            $productQuery .= " ORDER BY p.price ASC";
            break;
        case 'price-desc':
            $productQuery .= " ORDER BY p.price DESC";
            break;
        case 'popularity':
            $productQuery .= " ORDER BY sold_count DESC";
            break;
        case 'newest':
            $productQuery .= " ORDER BY p.created_at DESC";
            break;
        default:
            $productQuery .= " ORDER BY p.id ASC";
    }

    // Add LIMIT/OFFSET
    $productQuery .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $perPage;
    $params[':offset'] = $offset;

    // Total products
    $countStmt = $conn->prepare($countQuery);
    foreach ($countParams as $key => &$val) {
        $countStmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalProducts = $countStmt->fetchColumn();
    $totalPages = ceil($totalProducts / $perPage);

    // Fetch paginated products
    $stmt = $conn->prepare($productQuery);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();
    $products = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Headwear | Revaux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- make sure to change this path if it doesnt work -->
    <link rel="icon" type="image/png" href="../images/revaux-light.png">
    <link rel="stylesheet" href="../css/categories.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- temporary header(pretty bad)-->
    <header class="navbar">
        <div class="logo">
            <!-- change the path if the logo isnt working -->
            <a href="../index.html" class="logo-link">
                <img src="../images/revaux-light.png" class="logo-img" alt="Revaux Logo">
                Revaux
            </a>
        </div>
        <input type="text" placeholder="Search Here..." class="search-bar">
        <div class="nav-icons">
        <span>🔔 Notifications</span>
        <span>❓ FAQs</span>
        <span>🤍 Wishlist</span>
        <span>🛒 Cart</span>
        <span>👤 Russo</span>
        </div>
    </header>

    <div class="breadcrumb-bar">
        <a href="../../index.html" class="breadcrumb-link">Home</a>
        <span class="breadcrumb-separator">&gt;</span>
        <a href="category.php?category=<?= urlencode($categoryName) ?>&sort=<?= urlencode($sortOption) ?>" class="breadcrumb-link"><?= htmlspecialchars($categoryName) ?></a>

        <?php if ($subcategoryName): ?>
            <span class="breadcrumb-separator">&gt;</span>
            <a href="category.php?category=<?= urlencode($categoryName) ?>&sub=<?= $subcategoryId ?>&sort=<?= urlencode($sortOption) ?>" class="breadcrumb-link"><?= htmlspecialchars($subcategoryName) ?></a>
        <?php endif; ?>
    </div>

    
    <div class="container">        
        <aside class="sidebar">
            <h2>CATEGORY</h2>
            <div class="category-dropdown" data-category="Headwear">
                <div class="dropdown-row">
                    <a class="dropdown-link" href="category.php?category=Headwear">Headwear</a>
                    <span class="arrow" tabindex="0" aria-label="Toggle submenu">▶</span>
                </div>
                <ul class="submenu">
                    <li class="<?= ($subcategoryId == 1 ? 'active-sub' : '') ?>"><a href="category.php?category=Headwear&sub=1">Caps</a></li> <!-- Use actual subcategory ID -->
                    <li class="<?= ($subcategoryId == 2 ? 'active-sub' : '') ?>"><a href="category.php?category=Headwear&sub=2">Eyewear</a></li>
                    <li class="<?= ($subcategoryId == 3 ? 'active-sub' : '') ?>"><a href="category.php?category=Headwear&sub=3">Hats</a></li>
                    <li class="<?= ($subcategoryId == 4 ? 'active-sub' : '') ?>"><a href="category.php?category=Headwear&sub=4">Bandanas</a></li>
                </ul>
            </div>
            <div class="category-dropdown" data-category="Tops">
                <div class="dropdown-row">
                    <a class="dropdown-link" href="category.php?category=Tops">Tops</a>
                    <span class="arrow" tabindex="0" aria-label="Toggle submenu">▶</span>
                </div>
                <ul class="submenu">
                    <li class="<?= ($subcategoryId == 5 ? 'active-sub' : '') ?>"><a href="category.php?category=Tops&sub=5">T-shirts</a></li>
                    <li class="<?= ($subcategoryId == 6 ? 'active-sub' : '') ?>"><a href="category.php?category=Tops&sub=6">Polo Shirts</a></li>
                    <li class="<?= ($subcategoryId == 7 ? 'active-sub' : '') ?>"><a href="category.php?category=Tops&sub=7">Sweaters</a></li>
                    <li class="<?= ($subcategoryId == 8 ? 'active-sub' : '') ?>"><a href="category.php?category=Tops&sub=8">Hoodies</a></li>
                </ul>
            </div>
            <div class="category-dropdown" data-category="Bottoms">
                <div class="dropdown-row">
                    <a class="dropdown-link" href="category.php?category=Bottoms">Bottoms</a>
                    <span class="arrow" tabindex="0" aria-label="Toggle submenu">▶</span>
                </div>
                <ul class="submenu">
                    <li class="<?= ($subcategoryId == 9 ? 'active-sub' : '') ?>"><a href="category.php?category=Bottoms&sub=9">Jeans</a></li>
                    <li class="<?= ($subcategoryId == 10 ? 'active-sub' : '') ?>"><a href="category.php?category=Bottoms&sub=10">Shorts</a></li>
                    <li class="<?= ($subcategoryId == 11 ? 'active-sub' : '') ?>"><a href="category.php?category=Bottoms&sub=11">Trousers</a></li>
                    <li class="<?= ($subcategoryId == 12 ? 'active-sub' : '') ?>"><a href="category.php?category=Bottoms&sub=12">Cargo Pants</a></li>
                </ul>
            </div>
            <div class="category-dropdown" data-category="Footwear">
                <div class="dropdown-row">
                    <a class="dropdown-link" href="category.php?category=Footwear">Footwear</a>
                    <span class="arrow" tabindex="0" aria-label="Toggle submenu">▶</span>
                </div>
                <ul class="submenu">
                    <li class="<?= ($subcategoryId == 13 ? 'active-sub' : '') ?>"><a href="category.php?category=Footwear&sub=13">Sneakers</a></li>
                    <li class="<?= ($subcategoryId == 14 ? 'active-sub' : '') ?>"><a href="category.php?category=Footwear&sub=14">Sandals</a></li>
                    <li class="<?= ($subcategoryId == 15 ? 'active-sub' : '') ?>"><a href="category.php?category=Footwear&sub=15">Boots</a></li>
                    <li class="<?= ($subcategoryId == 16 ? 'active-sub' : '') ?>"><a href="category.php?category=Footwear&sub=16">Loafers</a></li>
                </ul>
            </div>
            <div class="c-divider"></div>

            <form method="GET" action="category.php">
                <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName) ?>">
                <?php if ($subcategoryId): ?>
                    <input type="hidden" name="sub" value="<?= $subcategoryId ?>">
                <?php endif; ?>
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sortOption) ?>">
                <div class="filters-section">
                    <h2>FILTERS</h2>

                    <!-- Size Filter -->
                    <div class="filter-group">
                        <label>Size:</label>
                        <?php
                        $sizeStmt = $conn->query("SELECT id, label FROM sizes");
                        $sizes = $sizeStmt->fetchAll();
                        foreach ($sizes as $size):
                        ?>
                        <div class="sidebar-filters">
                            <input type="checkbox" class="custom-checkbox"name="size[]" value="<?= $size['id'] ?>" <?= in_array($size['id'], $_GET['size'] ?? []) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($size['label']) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Color Filter -->
                    <div class="filter-group">
                        <label>Color:</label>
                        <?php
                        $colorStmt = $conn->query("SELECT id, name FROM colors");
                        $colors = $colorStmt->fetchAll();
                        foreach ($colors as $color):
                        ?>
                        <div class="sidebar-filters">
                            <input type="checkbox" class="custom-checkbox" name="color[]" value="<?= $color['id'] ?>" <?= in_array($color['id'], $_GET['color'] ?? []) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($color['name']) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-group">
                        <label>Price Range:</label>
                        <input type="number" class="filter_price" name="min_price" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>"> -
                        <input type="number" class="filter_price" name="max_price" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                    </div>

                    <!-- Stock Checkbox -->
                    <div class="filter-group">
                        <label>In Stock Only</label>
                        <input type="checkbox" class="custom-checkbox"name="in_stock" value="1" <?= isset($_GET['in_stock']) ? 'checked' : '' ?>>
                    </div>

                    <!-- Submit Button -->
                    <div class="filter-group">
                        <button class="btn-filter"type="submit">Apply</button>
                    </div>
                </div>
            </form>
        </aside>


        <main class="products-section">
            <div class="products-container">
                <div class="products-header">
                    <h2><?= htmlspecialchars($subcategoryName ?? $categoryName) ?></h2>
                    <!-- Custom Sort dropdown -->
                    <div class="custom-dropdown" id="sort-dropdown">
                        <div class="custom-dropdown-selected" tabindex="0"> 
                            <span class="sort-label">Sort by: </span>
                            <span id="sort-selected-text">Default</span>
                            <span class="custom-arrow">▼</span>
                        </div>
                        <ul class="custom-dropdown-list">
                            <li data-value="default" class="active">Default</li>
                            <li data-value="price-asc">Price (Low to High)</li>
                            <li data-value="price-desc">Price (High to Low)</li>
                            <li data-value="popularity">Popularity</li>
                            <li data-value="newest">Newest</li>
                        </ul>
                    </div>
                </div>

                <div class="products-grid">
                    <?php if (empty($products)): ?>
                        <p>No products found.</p>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <a href="user/product_info.php?id=<?= $product['id'] ?>" class="product-card">
                                <div class="product-image">
                                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <div class="product-info">
                                    <p class="product-name"><?= htmlspecialchars($product['name']) ?></p>
                                    <div class="product-meta">
                                        <p class="price">₱<?= number_format($product['price'], 2) ?></p>
                                        <p class="product-sold"><?= $product['sold_count'] ?> sold</p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                        // Preserve filters in pagination links
                        $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
                        $queryParams = $_GET;
                    ?>

                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <?php $queryParams['page'] = $currentPage - 1; ?>
                        <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">&laquo; Prev</a>
                    <?php endif; ?>

                    <!-- Numbered Pages -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php $queryParams['page'] = $i; ?>
                        <a class="page-link <?= $i == $currentPage ? 'active' : '' ?>" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <?php $queryParams['page'] = $currentPage + 1; ?>
                        <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">Next &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </main>

    </div>
    <!-- JS -->
    <script type="module" src="../scripts/main.js"></script>
</body>
</html>

