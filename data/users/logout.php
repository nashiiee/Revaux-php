<?php
  // Prevent caching FIRST (before any session operations)
  header("Cache-Control: no-cache, no-store, must-revalidate"); 
  header("Pragma: no-cache");
  header("Expires: 0");

  session_start();
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

  header("Location: ../../pages/authentication/login.html");
  exit();
  // session_start();
  // $_SESSION = array();

  // session_destroy();

  // header("Location: ../../pages/authentication/login.html");
  // exit();
?>  