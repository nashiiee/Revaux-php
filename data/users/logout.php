<?php
// Enhanced logout with security measures
session_start();

// Security headers
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

// Get user role before destroying session (for redirect)
$userRole = $_SESSION['role'] ?? null;

// Destroy all session data
session_unset();

// Delete the session cookie from browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect based on where they came from or default to login
$redirectUrl = "../../pages/authentication/login.html?message=logged_out";

// If accessed directly without proper referrer, go to main page
if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'])) {
    $redirectUrl = "../../index.php";
}

header("Location: " . $redirectUrl);
exit();
?>  