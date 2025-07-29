<?php 
  session_start();

  // Redirect if not logged in
  if (!isset($_SESSION['username'])) {
      header("Location: ../authentication/login.html");
      exit;
  }

  // Include database connection
  require_once '../../database/database.php';
  $user_data = []; // To store current user's profile data
  $message = ''; // To store success/error messages

  // Fetch Current User Data
  try {
      // Get customer ID based on username from session (assuming username is unique)
      $stmt = $conn->prepare('SELECT id, first_name, last_name, username, email, address, mobile FROM customers WHERE username = ?');
      $stmt->execute([$_SESSION['username']]);
      $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user_data) {
          // User data not found, possibly account deleted or session issue
          session_destroy(); // Clear session
          header("Location: ../authentication/login.html?error=user_not_found");
          exit;
      }

  } catch (PDOException $e) {
      // Log the error (do not expose to user in production)
      error_log("Account management fetch error: " . $e->getMessage());
      $message = '<div class="status-message error">Error loading user data. Please try again later.</div>';
      $user_data = []; // Clear data to prevent form pre-filling with invalid state
  }


  //  Handle Form Submission (POST Request)
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get submitted data
      $new_first_name = $_POST['first_name'] ?? '';
      $new_last_name = $_POST['last_name'] ?? '';
      $new_username = $_POST['username'] ?? '';
      $new_email = $_POST['email'] ?? '';
      $new_mobile = $_POST['mobile'] ?? '';
      $new_address = $_POST['address'] ?? '';

      // Sanitize input (basic sanitation, improve with more robust filters/validation)
      $new_first_name = htmlspecialchars(trim($new_first_name));
      $new_last_name = htmlspecialchars(trim($new_last_name));
      $new_username = htmlspecialchars(trim($new_username));
      $new_email = htmlspecialchars(trim($new_email));
      $new_mobile = htmlspecialchars(trim($new_mobile));
      $new_address = htmlspecialchars(trim($new_address));

      // Basic Validation (add more as needed, e.g., email format, phone regex)
      if (empty($new_username) || empty($new_email) || empty($new_first_name) || empty($new_last_name)) {
          $message = '<div class="status-message error">Username, Email, First Name, and Last Name cannot be empty.</div>';
      } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
          $message = '<div class="status-message error">Invalid email format.</div>';
      } else {
          try {
              $update_sql = "UPDATE customers
                  SET
                      first_name = ?,
                      last_name = ?,
                      username = ?,
                      email = ?,
                      address = ?,
                      mobile = ?
                  WHERE id = ?
              ";
              $update_stmt = $conn->prepare($update_sql);
              $update_stmt->execute([
                  $new_first_name,
                  $new_last_name,
                  $new_username,
                  $new_email,
                  $new_address,
                  $new_mobile,
                  $user_data['id'] // Use the user's ID from the fetched data
              ]);

              if ($update_stmt->rowCount() > 0) {
                  // Update session username if it was changed
                  if ($new_username !== $_SESSION['username']) {
                      $_SESSION['username'] = $new_username;
                  }
                  // Re-fetch data to update the form with new values (and potential trimming from DB)
                  $stmt = $conn->prepare('SELECT id, first_name, last_name, username, email, address, mobile FROM customers WHERE id = ?');
                  $stmt->execute([$user_data['id']]);
                  $user_data = $stmt->fetch(PDO::FETCH_ASSOC); // Update $user_data with fresh data

                  $message = '<div class="status-message success">Profile updated successfully!</div>';
              } else {
                  // No rows affected might mean no change or an issue
                  $message = '<div class="status-message info">No changes were made or data is identical.</div>';
              }

          } catch (PDOException $e) {
              // Check for duplicate entry error (e.g., duplicate username or email)
              if ($e->getCode() === '23000') { // SQLSTATE for Integrity Constraint Violation
                  if (strpos($e->getMessage(), 'username') !== false) {
                      $message = '<div class="status-message error">This username is already taken.</div>';
                  } elseif (strpos($e->getMessage(), 'email') !== false) {
                      $message = '<div class="status-message error">This email is already registered.</div>';
                  } else {
                      $message = '<div class="status-message error">A database constraint error occurred.</div>';
                  }
              } else {
                  $message = '<div class="status-message error">Error updating profile: ' . $e->getMessage() . '</div>';
              }
              error_log("Account management update error: " . $e->getMessage());
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account Management | Revaux</title>
    <link rel="icon" type="image/png" href="../../images/revaux-light.png" /> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <link rel="stylesheet" href="../../css/header-user.css">
    <link rel="stylesheet" href="../../css/account_management.css" />
    <link rel="stylesheet" href="../../css/footer.css" />
  </head>
  <body>
    <?php include '../../includes/header-user.php'; ?>
    <!-- Status Message Area -->
    <?php 
      // Only display the wrapper div if there's actually a message
      if (!empty($message)) {

          $status_type = ''; // Default
          if (strpos($message, 'status-message success') !== false) {
              $status_type = 'success';
          } elseif (strpos($message, 'status-message error') !== false) {
              $status_type = 'error';
          } elseif (strpos($message, 'status-message info') !== false) {
              $status_type = 'info';
          }

          // Now, strip the inner div from $message to get just the text content
          $message_content = strip_tags($message); // This will remove the inner <div> tags, leaving just the text

          if (!empty($message_content)) {
              echo '<div id="statusMessage" class="status-message-wrapper ' . htmlspecialchars($status_type) . '">'; 
              echo $message_content; // Only echo the text content here
              echo '</div>';
          }
      }
    ?>
    <!-- Main Content will be updated once customer profile image feature is implemented in the database -->
    
    <main class="main-container">
      <div class="profile-section">
        <div class="profile-circle"></div>
        <div class="profile-actions">
          <button class="btn-upload">Upload New</button>
          <button class="btn-delete">Delete Avatar</button>
        </div>
      </div>

      <div class="spacing"></div>

      <form class="profile-form" method="POST" action="">
        <div class="form-row">
          <div class="form-group">
            <label for="Username">Username: </label>
            <!-- Added name attribute and PHP value -->
            <input type="text" id="Username" name="username" class="form-input" value="<?= htmlspecialchars($user_data['username'] ?? '') ?>" required />
          </div>
          <div class="form-group">
            <label for="FirstName">First Name: </label>
            <!-- Added name attribute and PHP value -->
            <input type="text" id="FirstName" name="first_name" class="form-input" value="<?= htmlspecialchars($user_data['first_name'] ?? '') ?>" />
          </div>
          <div class="form-group">
            <label for="LastName">Last Name: </label>
            <!-- Added name attribute and PHP value -->
            <input type="text" id="LastName" name="last_name" class="form-input" value="<?= htmlspecialchars($user_data['last_name'] ?? '') ?>" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email">Email: </label>
            <!-- Added name attribute and PHP value -->
            <input type="email" id="email" name="email" class="form-input" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number: </label>
            <!-- Added name attribute and PHP value -->
            <input type="tel" id="phone" name="mobile" class="form-input" value="<?= htmlspecialchars($user_data['mobile'] ?? '') ?>" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="Address">Address: </label>
            <!-- Added name attribute and PHP value -->
            <input type="text" id="Address" name="address" class="form-input" value="<?= htmlspecialchars($user_data['address'] ?? '') ?>" />
          </div>
        </div>
        
        <div class="save-container">
          <button type="submit" class="save-btn">Save Changes</button>
        </div>

      </form>
    </main>
    <?php include '../../includes/footer.php'; ?>
    <script type="module" src="../../scripts/main.js"></script>
  </body>
</html>