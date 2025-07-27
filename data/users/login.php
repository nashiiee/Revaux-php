<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <?php
            session_start();
            include "../../revauxDatabase/database.php";

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
            }

            try{    
                $conn = new PDO("mysql:host=$db_host; dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $existingUser = $conn->prepare("SELECT * FROM customers WHERE username = :username");
                $existingUser->bindParam(':username', $username);
                $existingUser->execute();

                if ($existingUser->rowCount() > 0) {
                  $user = $existingUser->fetch(PDO::FETCH_ASSOC);
                  if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $username;
                    header("Location: ../../pages/user/homepage.html");
                    exit(); 
                } else {
                  echo "Invalid Credentials";
                }
              } else {
                echo "No account for this user!";
              }
            } catch(PDOException $e) {
                echo "ERROR: " . $e->getMessage();
            }
        ?>
        <form action="../../pages/user/homepage.html">
            <button type="submit">Back</button>
        </form>
    </div>
</body>
</html>