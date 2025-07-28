<?php
    $categoryName = $categoryName ?? null;
    $subcategoryId = $subcategoryId ?? null;
    $sortOption = $sortOption ?? 'default';
    $isSearch = $isSearch ?? false;  // For search results
?>

<aside class="sidebar">
    <?php if (!$isSearch): ?>
        <!-- Show CATEGORY & SUBCATEGORY lists only if NOT a search -->
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
    <?php endif; ?>

    <div class="c-divider"></div>

    <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <?php if (!$isSearch): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName) ?>">
            <?php if ($subcategoryId): ?>
                <input type="hidden" name="sub" value="<?= $subcategoryId ?>">
            <?php endif; ?>
        <?php else: ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <?php endif; ?>
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sortOption) ?>">

        <div class="filters-section">
            <h2>FILTERS</h2>

            <!-- Sizes -->
            <div class="filter-group">
                <label>Size:</label>
                <?php
                $sizeStmt = $conn->query("SELECT id, label FROM sizes");
                $sizes = $sizeStmt->fetchAll();
                foreach ($sizes as $size):
                ?>
                    <div class="sidebar-filters">
                        <input type="checkbox" class="custom-checkbox" name="size[]" value="<?= $size['id'] ?>" <?= in_array($size['id'], $_GET['size'] ?? []) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($size['label']) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Colors -->
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

            <!-- Price -->
            <div class="filter-group">
                <label>Price Range:</label>
                <input type="number" class="filter_price" name="min_price" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>"> -
                <input type="number" class="filter_price" name="max_price" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
            </div>

            <!-- Stock -->
            <div class="filter-group">
                <label>In Stock Only</label>
                <input type="checkbox" class="custom-checkbox" name="in_stock" value="1" <?= isset($_GET['in_stock']) ? 'checked' : '' ?>>
            </div>

            <div class="filter-group">
                <button class="btn-filter" type="submit">Apply</button>
            </div>
        </div>
    </form>
</aside>