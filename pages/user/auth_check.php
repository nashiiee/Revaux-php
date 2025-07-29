<?php
// Customer session protection with enhanced security
session_start();

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Check if user is logged in and is a customer
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    // Redirect to login page if not logged in or not a customer
    header("Location: ../../pages/authentication/login.html?error=access_denied");
    exit();
}

// Session timeout check (2 hours)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 7200)) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: ../../pages/authentication/login.html?error=session_expired");
    exit();
}

// Update last activity
// $_SESSION['last_activity'] = time();

// // Regenerate session ID periodically for security (every 30 minutes)
// if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration']) > 1800) {
//     session_regenerate_id(true);
//     $_SESSION['last_regeneration'] = time();
// }
?>
