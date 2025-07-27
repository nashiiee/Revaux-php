<?php
session_start();
include "../../revauxDatabase/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {    
        $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $existingUser = $conn->prepare("SELECT * FROM customers WHERE username = :username");
        $existingUser->bindParam(':username', $username);
        $existingUser->execute();

        if ($existingUser->rowCount() > 0) {
            $error_message = "Username already exists. Please choose a different username.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertUser = $conn->prepare("INSERT INTO customers (username, email, password) VALUES (:username, :email, :password)");
            $insertUser->bindParam(':username', $username);
            $insertUser->bindParam(':email', $email);
            $insertUser->bindParam(':password', $hashedPassword);
            $insertUser->execute();

            $_SESSION['username'] = $username;
            header("Location: ../../pages/user/homepage.html");
            exit();
        }
    } catch(PDOException $e) {
        $error_message = "Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revaux - Sign Up Processing</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/authentication.css">
</head>

<body>
    <main>
        <section>
            <div class="logo-container">
                <a href="../../index.html" class="logo">
                    <img src="../../images/revaux-dark.png" alt="Revaux">
                </a>
                <a href="../../index.html" class="brand-name">Revaux</a> 
            </div>

            <?php if (isset($error_message)): ?>
                <h2>Error</h2>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <p><a href="../../pages/authentication/signup.html">Try Again</a></p>
            <?php elseif ($_SERVER["REQUEST_METHOD"] !== "POST"): ?>
                <h2>Access Denied</h2>
                <p>This page can only be accessed through the signup form.</p>
                <p><a href="../../pages/authentication/signup.html">Go to Sign Up</a></p>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>