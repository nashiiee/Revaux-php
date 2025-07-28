<?php
  // Include secure authentication check for admin
  require_once 'auth_check.php';
  
  include '../revauxDatabase/database.php';

  try {
      $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sqlTransactions = $conn->prepare("SELECT COUNT(id) AS total_transactions FROM orders;");
      $sqlTransactions->execute();
      $totalTransactions = $sqlTransactions->fetch(PDO::FETCH_ASSOC)['total_transactions'];

      $sqlTotalRefunds = $conn->prepare("SELECT count(total_amount) AS total_refunds FROM orders WHERE 
        status = 'Cancelled' AND payment_status='Paid';");
      $sqlTotalRefunds->execute();
      $totalRefunds = $sqlTotalRefunds->fetch(PDO::FETCH_ASSOC)['total_refunds'] ?? 0;



      $sqlSuccessful = $conn->prepare("SELECT COUNT(total_amount) AS total_success FROM orders 
      WHERE status = 'Delivered' AND payment_status='Paid';");
      $sqlSuccessful->execute();
      $totalSuccessful = $sqlSuccessful->fetch(PDO::FETCH_ASSOC)['total_success'] ?? 0;



      $sqlPending = $conn->prepare("SELECT COUNT(total_amount) AS total_pending FROM orders 
      WHERE payment_status = 'Pending' OR status = 'Processing';");
      $sqlPending->execute();
      $totalPending = $sqlPending->fetch(PDO::FETCH_ASSOC)['total_pending'] ?? 0;


      $customers = $conn->prepare("SELECT oi.product_id, CONCAT(c.first_name, ' ', c.last_name) AS customer_name, o.total_amount AS order_total_price, oi.quantity,
      o.status AS order_status, o.order_date AS order_date, o.payment_status FROM order_items AS oi 
      JOIN orders AS o ON oi.order_id = o.id 
      JOIN customers AS c ON o.customer_id = c.id 
      ORDER BY o.order_date DESC;");
      $customers->execute();
      $customers = $customers->fetchAll(PDO::FETCH_ASSOC);

  } catch (PDOException $e) {
    header("Location: index.php?error=database_connection_failed");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions</title>
  <!-- Poppins Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Playfair Display Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <!-- Stylesheet -->
  <link rel="stylesheet" href="../dist/transactions.css">
  <!-- Font Awesome Free CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../dist/transac.css">
  

</head>
<body class="transactions-page">
  <aside>
    <div class="logo">
      <img src="./images/logo.png" alt="Revaux Logo">
      <span>Revaux</span>
    </div>
    <div class="links">
      <ul class="nav-links">
        <li>
          <a href="index.php">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="active">
          <a href="transactions.html">
            <i class="fa-solid fa-credit-card"></i>
            <span>Transactions</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-chart-line"></i>
            <span>Sales</span>
          </a>
        </li>
        <li>
          <a href="products.php">
            <i class="fa-solid fa-box"></i>
            <span>Products</span>
          </a>
        </li>
      </ul>
      <span class="tools-description">Tools</span>
      <ul class="nav-links">
        <li>
          <a href="">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-circle-question"></i>
            <span>Help</span>
          </a>
        </li>
        <li>
          <a href="">
            <i class="fa-solid fa-user-gear"></i>
            <span>Manage User</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="logout-container">
      <a href="../data/users/logout.php" class="logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  <header>
    <input class="search" type="search" placeholder="Search">
    <div class="admin-header-side">
      <div class="image-container"></div>
      <span><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?></span>
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </header>
  <main>
    <div class="main-section-container">
      <h2>Transactions History</h2>
      <div class="transactions-card">
        <div class="card">
          <div class="contents">
            <div class="icon-container">
              <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="text-container">
              <h5>Total Transactions</h5>
              <p><?= $totalTransactions ?></p>
            </div>
          </div>
        </div>
        <div class="card--successful">
          <div class="contents">
            <div class="icon-container">
              <i class="fa-solid fa-check"></i>
            </div>
            <div class="text-container">
              <h5>Successful</h5>
              <p><?= $totalSuccessful ?></p>
            </div>
          </div>
        </div>
        <div class="card--refunds">
          <div class="contents">
            <div class="icon-container">
              <i class="fa-solid fa-rotate-left"></i>
            </div>
            <div class="text-container">
              <h5>Refunds Issued</h5>
              <p><?= $totalRefunds ?></p>
            </div>
          </div>
        </div>
        <div class="card--pending">
          <div class="contents">
            <div class="icon-container">
              <i class="fa-solid fa-clock"></i>
            </div>
            <div class="text-container">
              <h5>Pending</h5>
              <p><?= $totalPending ?></p>
            </div>
          </div>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Product Id</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Payment Status</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach($customers as $customer) {
              // Determine status classes
              $statusClass = '';
              $paymentClass = '';
              
              switch(strtolower($customer['order_status'])) {
                case 'delivered': $statusClass = 'status-delivered'; break;
                case 'processing': $statusClass = 'status-processing'; break;
                case 'cancelled': $statusClass = 'status-cancelled'; break;
                default: $statusClass = 'status-pending';
              }
              
              switch(strtolower($customer['payment_status'])) {
                case 'paid': $paymentClass = 'payment-paid'; break;
                case 'failed': $paymentClass = 'payment-failed'; break;
                default: $paymentClass = 'payment-pending';
              }
              
              echo "<tr>";
              echo "<td>" . htmlspecialchars($customer['product_id']) . "</td>";
              echo "<td>" . (!empty($customer['customer_name']) ? htmlspecialchars($customer['customer_name']) : 'Anonymous') . "</td>";
              echo "<td>₱" . htmlspecialchars($customer['order_total_price']) . "</td>";
              echo "<td>" . htmlspecialchars($customer['quantity']) . "</td>";
              echo "<td><span class='status-badge $statusClass'>" . htmlspecialchars($customer['order_status']) . "</span></td>";
              echo "<td><span class='status-badge $paymentClass'>" . htmlspecialchars($customer['payment_status']) . "</span></td>";
              echo "<td>" . date("d, F, Y", strtotime($customer['order_date'])) . "</td>";
              echo "<td>";
              echo "<div class='action-dropdown'>";
              echo "<button class='action-btn' onclick='toggleDropdown(event, this)'>";
              echo "<i class='fa-solid fa-ellipsis-vertical'></i>";
              echo "</button>";
              echo "<div class='dropdown-content'>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"order_status\", \"Processing\")'>";
              echo "<i class='fa-solid fa-clock'></i> Set to Processing";
              echo "</div>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"order_status\", \"Delivered\")'>";
              echo "<i class='fa-solid fa-check'></i> Set to Delivered";
              echo "</div>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"order_status\", \"Cancelled\")'>";
              echo "<i class='fa-solid fa-times'></i> Set to Cancelled";
              echo "</div>";
              echo "<hr style='margin: 8px 0; border: none; border-top: 1px solid #eee;'>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"payment_status\", \"Paid\")'>";
              echo "<i class='fa-solid fa-credit-card'></i> Mark as Paid";
              echo "</div>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"payment_status\", \"Pending\")'>";
              echo "<i class='fa-solid fa-hourglass-half'></i> Mark as Pending";
              echo "</div>";
              echo "<div class='dropdown-item' onclick='updateStatus(" . $customer['product_id'] . ", \"payment_status\", \"Failed\")'>";
              echo "<i class='fa-solid fa-exclamation-triangle'></i> Mark as Failed";
              echo "</div>";
              echo "</div>";
              echo "</div>";
              echo "</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Status Update Modal -->
  <div id="statusModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modalTitle">
          <i class="fa-solid fa-question-circle"></i>
          <span id="modalTitleText">Confirm Status Update</span>
        </h3>
      </div>
      <div class="modal-body">
        <p class="modal-text" id="modalText">
          Are you sure you want to update this status?
        </p>
        <div class="status-preview" id="statusPreview">
          <p class="current-status" id="currentStatus">Current: <span id="currentStatusValue"></span></p>
          <p class="new-status" id="newStatus">New: <span id="newStatusValue"></span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="modal-btn modal-btn-cancel" onclick="closeModal()">
          Cancel
        </button>
        <button type="button" class="modal-btn modal-btn-confirm" id="confirmBtn" onclick="confirmStatusUpdate()">
          Update Status
        </button>
      </div>
    </div>
  </div>

  <script>
    // Modal functionality
    let currentUpdateData = null;

    // Toggle dropdown menu
    function toggleDropdown(event, button) {
      event.stopPropagation();
      const dropdown = button.nextElementSibling;
      
      // Close all other dropdowns
      document.querySelectorAll('.dropdown-content').forEach(d => {
        if (d !== dropdown) d.classList.remove('show');
      });
      
      // Toggle current dropdown
      dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
      document.querySelectorAll('.dropdown-content').forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    });

    // Show modal for status update
    function updateStatus(productId, statusType, newStatus) {
      // Store the update data
      currentUpdateData = {
        productId: productId,
        statusType: statusType,
        newStatus: newStatus
      };

      // Get current status from the table row
      const currentStatus = getCurrentStatus(productId, statusType);
      
      // Configure modal content
      const modal = document.getElementById('statusModal');
      const modalTitle = document.getElementById('modalTitleText');
      const modalText = document.getElementById('modalText');
      const currentStatusValue = document.getElementById('currentStatusValue');
      const newStatusValue = document.getElementById('newStatusValue');
      const modalContent = document.querySelector('.modal-content');
      
      // Set title and icon based on status type
      if (statusType === 'order_status') {
        modalTitle.textContent = 'Update Order Status';
        modalText.textContent = 'Are you sure you want to change the order status?';
        
        // Add appropriate class for icon styling
        modalContent.className = 'modal-content';
        switch(newStatus.toLowerCase()) {
          case 'processing':
            modalContent.classList.add('status-processing');
            break;
          case 'delivered':
            modalContent.classList.add('status-delivered');
            break;
          case 'cancelled':
            modalContent.classList.add('status-cancelled');
            break;
        }
      } else {
        modalTitle.textContent = 'Update Payment Status';
        modalText.textContent = 'Are you sure you want to change the payment status?';
        modalContent.className = 'modal-content payment-status';
      }
      
      // Set status values
      currentStatusValue.textContent = currentStatus;
      newStatusValue.textContent = newStatus;
      
      // Show modal
      modal.style.display = 'block';
      
      // Close dropdown
      document.querySelectorAll('.dropdown-content').forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    }

    // Get current status from table
    function getCurrentStatus(productId, statusType) {
      const rows = document.querySelectorAll('tbody tr');
      for (let row of rows) {
        const firstCell = row.cells[0].textContent.trim();
        if (firstCell == productId) {
          if (statusType === 'order_status') {
            return row.cells[4].textContent.trim();
          } else {
            return row.cells[5].textContent.trim();
          }
        }
      }
      return 'Unknown';
    }

    // Close modal
    function closeModal() {
      document.getElementById('statusModal').style.display = 'none';
      currentUpdateData = null;
    }

    // Confirm status update
    function confirmStatusUpdate() {
      if (!currentUpdateData) return;
      
      const confirmBtn = document.getElementById('confirmBtn');
      confirmBtn.disabled = true;
      confirmBtn.textContent = 'Updating...';
      
      // Create form data
      const formData = new FormData();
      formData.append('product_id', currentUpdateData.productId);
      formData.append('status_type', currentUpdateData.statusType);
      formData.append('new_status', currentUpdateData.newStatus);

      // Send AJAX request
      fetch('update_transaction_status.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success and reload
          showSuccessMessage('Status updated successfully!');
          setTimeout(() => {
            location.reload();
          }, 1000);
        } else {
          showErrorMessage('Error updating status: ' + (data.message || 'Unknown error'));
          confirmBtn.disabled = false;
          confirmBtn.textContent = 'Update Status';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error updating status. Please try again.');
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Update Status';
      });
    }

    // Show success message
    function showSuccessMessage(message) {
      const modalText = document.getElementById('modalText');
      const statusPreview = document.getElementById('statusPreview');
      const modalFooter = document.querySelector('.modal-footer');
      
      modalText.innerHTML = `<i class="fa-solid fa-check-circle" style="color: #28a745; margin-right: 8px;"></i>${message}`;
      statusPreview.style.display = 'none';
      modalFooter.style.display = 'none';
    }

    // Show error message
    function showErrorMessage(message) {
      const modalText = document.getElementById('modalText');
      modalText.innerHTML = `<i class="fa-solid fa-exclamation-triangle" style="color: #dc3545; margin-right: 8px;"></i>${message}`;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('statusModal');
      if (event.target === modal) {
        closeModal();
      }
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closeModal();
      }
    });
  </script>
</body>
</html>