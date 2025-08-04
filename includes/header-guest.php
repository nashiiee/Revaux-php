<header>
    <div class="logo-container">
        <a href="/revaux/index.php" class="logo">
            <img src="/revaux/images/revaux-light.png" alt="Revaux">
        </a>
        <a href="/revaux/index.php" class="brand-name">Revaux</a>
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
        <button class="download-btn">Download</button>
        <a href="/revaux/pages/authentication/login.html" class="auth-btn">LOG IN</a>
        <span class="separator">|</span>
        <a href="/revaux/pages/authentication/signup.html" class="auth-btn">SIGN UP</a>
    </div>
</header>
