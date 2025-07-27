<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: pages/authentication/login.html");
    exit();
}

// Prevent page caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Revaux - Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></title>
</head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
  <p>You are successfully logged in.</p>
  
  <form action="data/users/logout.php">
    <input type="submit" value="Logout">
  </form>

  <script>
   
  </script>
</body>
</html>
