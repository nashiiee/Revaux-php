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
    header("Location: ../../pages/authentication/signup.html?error=config_error");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $account_type = $_POST['account_type'] ?? 'customer';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
        header("Location: ../../pages/authentication/signup.html?error=empty_fields");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../pages/authentication/signup.html?error=invalid_email");
        exit();
    }

    // Validate password strength
    if (strlen($password) < 8) {
        header("Location: ../../pages/authentication/signup.html?error=password_weak");
        exit();
    }

    try {    
        $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Determine which table to use
        $table = ($account_type === 'admin') ? 'admins' : 'customers';
        
        // Check if username already exists in the target table
        $existingUser = $conn->prepare("SELECT * FROM $table WHERE username = :username");
        $existingUser->bindParam(':username', $username);
        $existingUser->execute();

        if ($existingUser->rowCount() > 0) {
            header("Location: ../../pages/authentication/signup.html?error=username_exists");
            exit();
        }

        // Check if email already exists in the target table
        $existingEmail = $conn->prepare("SELECT * FROM $table WHERE email = :email");
        $existingEmail->bindParam(':email', $email);
        $existingEmail->execute();

        if ($existingEmail->rowCount() > 0) {
            header("Location: ../../pages/authentication/signup.html?error=email_exists");
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Create full name
        $fullname = $first_name . ' ' . $last_name;

        // Insert new user into appropriate table
        if ($account_type === 'admin') {
            $insertUser = $conn->prepare("INSERT INTO admins (username, email, password, fullname) VALUES (:username, :email, :password, :fullname)");
            $insertUser->bindParam(':username', $username);
            $insertUser->bindParam(':email', $email);
            $insertUser->bindParam(':password', $hashedPassword);
            $insertUser->bindParam(':fullname', $fullname);
        } else {
            // For customers, insert into first_name, last_name, and fullname columns
            $insertUser = $conn->prepare("INSERT INTO customers (username, email, password, first_name, last_name, fullname) VALUES (:username, :email, :password, :first_name, :last_name, :fullname)");
            $insertUser->bindParam(':username', $username);
            $insertUser->bindParam(':email', $email);
            $insertUser->bindParam(':password', $hashedPassword);
            $insertUser->bindParam(':first_name', $first_name);
            $insertUser->bindParam(':last_name', $last_name);
            $insertUser->bindParam(':fullname', $fullname);
        }
        
        $insertUser->execute();

        // Redirect with success message
        $successType = ($account_type === 'admin') ? 'admin_created' : 'account_created';
        header("Location: ../../pages/authentication/signup.html?success=$successType");
        exit();

    } catch(PDOException $e) {
        // Log error for debugging (in production, log to file instead)
        error_log("Signup error: " . $e->getMessage());
        
        // More specific error messages for debugging
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            if (strpos($e->getMessage(), 'username') !== false) {
                header("Location: ../../pages/authentication/signup.html?error=username_exists");
            } elseif (strpos($e->getMessage(), 'email') !== false) {
                header("Location: ../../pages/authentication/signup.html?error=email_exists");
            } else {
                header("Location: ../../pages/authentication/signup.html?error=duplicate_entry");
            }
        } else {
            header("Location: ../../pages/authentication/signup.html?error=database_error&details=" . urlencode($e->getMessage()));
        }
        exit();
    }
} else {
    // Invalid request method or direct access
    header("Location: ../../pages/authentication/signup.html");
    exit();
}
?>