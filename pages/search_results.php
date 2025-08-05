<?php
    session_start();
    if (isset($_SESSION['username'])) {
            include '../includes/header-user.php';  // Adjust path later if needed
        } else {
            include '../includes/header-guest.php';
    }
    // Connect to DB
    require_once __DIR__ . '/../database/database.php';

    $searchQuery = trim($_GET['search'] ?? '');
    $sortOption = $_GET['sort'] ?? 'default';
    $perPage = 12;
    $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($currentPage - 1) * $perPage;

    if ($searchQuery === '') {
        die("No search keyword provided.");
    }

    $params = [':search' => "%$searchQuery%"];
    $productQuery = "SELECT 
        p.*, 
        c.name AS category_name, 
        sc.name AS subcategory_name, 
        IFNULL(psc.sold_count, 0) AS sold_count
        FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
        LEFT JOIN (
            SELECT
                oi.product_id,
                IFNULL(SUM(oi.quantity), 0) AS sold_count
            FROM
                order_items oi
            JOIN
                orders o ON oi.order_id = o.id
            WHERE
                o.payment_status = 'Paid' AND o.status = 'Delivered'
            GROUP BY
                oi.product_id
        ) psc ON p.id = psc.product_id
        WHERE p.name LIKE :search";

    $countQuery = "SELECT COUNT(*) FROM products p WHERE p.name LIKE :search";
    $countParams = $params;

    // Apply filters
    if (!empty($_GET['size']) && is_array($_GET['size'])) {
        foreach ($_GET['size'] as $i => $sizeId) {
            $param = ":size_$i";
            $params[$param] = $countParams[$param] = $sizeId;
            $sizeParams[] = $param;
        }
        $in = implode(',', $sizeParams);
        $productQuery .= " AND p.id IN (SELECT product_id FROM product_sizes WHERE size_id IN ($in))";
        $countQuery .= " AND p.id IN (SELECT product_id FROM product_sizes WHERE size_id IN ($in))";
    }

    if (!empty($_GET['color']) && is_array($_GET['color'])) {
        foreach ($_GET['color'] as $i => $colorId) {
            $param = ":color_$i";
            $params[$param] = $countParams[$param] = $colorId;
            $colorParams[] = $param;
        }
        $in = implode(',', $colorParams);
        $productQuery .= " AND p.id IN (SELECT product_id FROM product_colors WHERE color_id IN ($in))";
        $countQuery .= " AND p.id IN (SELECT product_id FROM product_colors WHERE color_id IN ($in))";
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
        case 'price-asc': $productQuery .= " ORDER BY p.price ASC"; break;
        case 'price-desc': $productQuery .= " ORDER BY p.price DESC"; break;
        case 'popularity': $productQuery .= " ORDER BY sold_count DESC"; break;
        case 'newest': $productQuery .= " ORDER BY p.created_at DESC"; break;
        default: $productQuery .= " ORDER BY p.id ASC";
    }

    // Pagination
    $productQuery .= " LIMIT :limit OFFSET :offset";
    $params[':limit'] = $perPage;
    $params[':offset'] = $offset;

    // Execute count
    $countStmt = $conn->prepare($countQuery);
    foreach ($countParams as $k => &$v) {
        $countStmt->bindParam($k, $v);
    }
    $countStmt->execute();
    $totalProducts = $countStmt->fetchColumn();
    $totalPages = ceil($totalProducts / $perPage);

    // Execute products
    $stmt = $conn->prepare($productQuery);
    foreach ($params as $k => &$v) {
        $stmt->bindParam($k, $v);
    }
    $stmt->execute();
    $products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Search Results for "<?= htmlspecialchars($searchQuery) ?>" | Revaux</title>
        <link rel="icon" type="image/png" href="../images/revaux-light.png">
        <link rel="stylesheet" href="../css/categories.css">
        <?php if (isset($_SESSION['username'])): ?>
            <link rel="stylesheet" href="../css/header-user.css">
        <?php else: ?>
            <link rel="stylesheet" href="../css/header-guest.css">
        <?php endif; ?>
        <link rel="stylesheet" href="../css/footer.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="breadcrumb-bar">
            <a href="../index.html" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">&gt;</span>
            <span class="breadcrumb">Search Results</span>
            <span class="breadcrumb-separator">&gt;</span>
            <span class="breadcrumb-current">"<?= htmlspecialchars($searchQuery) ?>"</span>
        </div>

        <div class="container">
            <?php
                $isSearch = true;
                include '../includes/sidebar_filters.php';
            ?>

            <main class="products-section">
                <div class="products-container">
                    <div class="products-header">
                        <h2>Results for "<?= htmlspecialchars($searchQuery) ?>"</h2>
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
                            <?php
                                // Determine the correct product page based on login status
                                $isLoggedIn = isset($_SESSION['username']);
                                $productPage = $isLoggedIn ? './user/product_info.php' : './guest/product_info.php';
                            ?>
                            <?php foreach ($products as $product): ?>
                                <a href="<?= $productPage ?>?id=<?= $product['id'] ?>" class="product-card">
                                    <div class="product-image">
                                        <img src="../admin/<?= ltrim($product['image_url'], './') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
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
                                // preserve filters
                                $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
                                $queryParams = $_GET;
                            ?>
                            <!-- previous button -->
                            <?php if ($currentPage > 1): ?>
                                <?php $queryParams['page'] = $currentPage - 1; ?>
                                <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">&laquo; Prev</a>
                            <?php endif; ?>
                            <!-- page numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php $queryParams['page'] = $i; ?>
                                <a class="page-link <?= $i == $currentPage ? 'active' : '' ?>" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>"> <?= $i ?> </a>
                            <?php endfor; ?>
                            <!-- next button -->
                            <?php if ($currentPage < $totalPages): ?>
                                <?php $queryParams['page'] = $currentPage + 1; ?>
                                <a class="page-link" href="<?= $baseUrl . '?' . http_build_query($queryParams) ?>">Next &raquo;</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
        <?php include '../includes/footer.php'; ?>
        <script type="module" src="../scripts/main.js"></script>
    </body>
</html>
