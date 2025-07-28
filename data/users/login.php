<?php
session_start();

// Security headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Include database connection with fallback paths
$db_paths = [
    "../../revauxDatabase/database.php",
    __DIR__ . "/../../revauxDatabase/database.php",
    $_SERVER['DOCUMENT_ROOT'] . "/revaux-php/revauxDatabase/database.php"
];

$db_included = false;
foreach ($db_paths as $path) {
    if (file_exists($path)) {
        include $path;
        $db_included = true;
        break;
    }
}

if (!$db_included) {
    error_log("Database config file not found");
    header("Location: ../../pages/authentication/login.html?error=config_error");
    exit();
}

// Rate limiting (basic protection against brute force)
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = 0;
}

// Check if too many attempts
if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt']) < 900) { // 15 min lockout
    header("Location: ../../pages/authentication/login.html?error=too_many_attempts");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header("Location: ../../pages/authentication/login.html?error=empty_fields");
        exit();
    }

    try {    
        $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // First check if user is an admin
        $adminQuery = $conn->prepare("SELECT * FROM admins WHERE (username = :username OR email = :username)");
        $adminQuery->bindParam(':username', $username);
        $adminQuery->execute();

        if ($adminQuery->rowCount() > 0) {
            $admin = $adminQuery->fetch(PDO::FETCH_ASSOC);
            
            // Verify admin password
            if (password_verify($password, $admin['password'])) {
                // Reset login attempts
                $_SESSION['login_attempts'] = 0;
                
                // Set admin session variables
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['role'] = 'admin';
                $_SESSION['full_name'] = $admin['fullname'] ?? ($admin['username'] ?? 'Admin');
                $_SESSION['login_time'] = time();
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                // Redirect to admin dashboard
                header("Location: ../../admin/index.php");
                exit(); 
            } else {
                // Invalid admin password
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt'] = time();
                header("Location: ../../pages/authentication/login.html?error=invalid_credentials");
                exit();
            }
        } else {
            // Check if user is a customer
            $customerQuery = $conn->prepare("SELECT * FROM customers WHERE (username = :username OR email = :username)");
            $customerQuery->bindParam(':username', $username);
            $customerQuery->execute();

            if ($customerQuery->rowCount() > 0) {
                $customer = $customerQuery->fetch(PDO::FETCH_ASSOC);
                
                // Verify customer password
                if (password_verify($password, $customer['password'])) {
                    // Reset login attempts
                    $_SESSION['login_attempts'] = 0;
                    
                    // Set customer session variables
                    $_SESSION['user_id'] = $customer['id'];
                    $_SESSION['username'] = $customer['username'];
                    $_SESSION['email'] = $customer['email'];
                    $_SESSION['role'] = 'customer';
                    
                    // Handle fullname - try different approaches based on available data
                    if (!empty($customer['fullname'])) {
                        $_SESSION['full_name'] = $customer['fullname'];
                    } elseif (!empty($customer['first_name']) && !empty($customer['last_name'])) {
                        $_SESSION['full_name'] = $customer['first_name'] . ' ' . $customer['last_name'];
                    } elseif (!empty($customer['first_name'])) {
                        $_SESSION['full_name'] = $customer['first_name'];
                    } else {
                        $_SESSION['full_name'] = $customer['username'];
                    }
                    
                    $_SESSION['login_time'] = time();
                    
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Redirect to customer homepage
                    header("Location: ../../pages/user/homepage.php");
                    exit(); 
                } else {
                    // Invalid customer password
                    $_SESSION['login_attempts']++;
                    $_SESSION['last_attempt'] = time();
                    header("Location: ../../pages/authentication/login.html?error=invalid_credentials");
                    exit();
                }
            } else {
                // No user found
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt'] = time();
                header("Location: ../../pages/authentication/login.html?error=user_not_found");
                exit();
            }
        }
    } catch(PDOException $e) {
        // Log error for debugging (in production, log to file instead)
        error_log("Login error: " . $e->getMessage());
        header("Location: ../../pages/authentication/login.html?error=database_error&details=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Invalid request method or direct access
    header("Location: ../../pages/authentication/login.html");
    exit();
}
?>