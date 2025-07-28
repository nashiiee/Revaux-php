<header>
    <div class="logo-container">
        <a href="/revaux/pages/user/homepage.php" class="logo">
            <img src="/revaux/images/revaux-light.png" alt="Logo">
        </a>
        <a href="/revaux/pages/user/homepage.php" class="brand-name">Revaux</a>
    </div>
    <?php
        $searchValue = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    ?>
    <form action="/revaux/pages/search_results.php" method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search Here" value="<?= $searchValue ?>" required>
        <button type="submit" class="search-btn">
            <span class="material-icons-outlined">search</span>
        </button>
    </form>
    <div class="cta-buttons">
        <div class="top-buttons">
            <button class="notification-btn">
                <a href="/revaux/pages/user/notifications.html" class="cta-link">
                    <span class="material-icons-outlined">notifications</span>
                    <span>Notifications</span>
                </a>
            </button>
            <button class="faq-btn">
                <a href="/revaux/pages/user/faqs.html" class="cta-link">
                    <span class="material-icons-outlined">help_outline</span>
                    <span>FAQs</span>
                </a>
            </button>
            <button class="settings-btn">
                <a href="/revaux/pages/user/account_management.html" class="cta-link">
                    <span class="material-icons-outlined">settings</span>
                    <span>Settings</span>
                </a>
            </button>
            <form action="/revaux/data/users/logout.php" method="POST">
                <button class="logout-btn">
                    <span class="material-icons-outlined">exit_to_app</span>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
        <div class="bottom-buttons">
            <button class="wishlist-btn">
                <a href="#" class="cta-link">
                    <span class="material-icons-outlined">favorite_border</span>
                    <span>Wishlist</span>
                </a>
            </button>
            <button class="cart-btn">
                <a href="/revaux/pages/user/view_cart.html" class="cta-link">
                    <span class="material-icons-outlined">shopping_cart</span>
                    <span>Cart</span>
                </a>
            </button>
            <button class="profile-btn">
                <a href="#" class="cta-link">
                    <span class="material-icons-outlined">person</span>
                    <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                </a>
            </button>
        </div>
    </div>
</header>
