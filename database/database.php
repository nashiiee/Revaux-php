<?php
  $db_host = 'localhost';
  $db_name = 'revaux_db';
  $db_username = 'root';
  $db_password = '';

  $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
  $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
  ];

  try {
    $conn = new PDO ($dsn, $db_username, $db_password, $options);
  } catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
  }

?>