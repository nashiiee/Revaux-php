<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products</title>

  <!-- Poppins Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Playfair Display Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <!-- Stylesheet -->
  <link rel="stylesheet" href="../dist/products.css">
  <!-- Font Awesome Free CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body style="background-color: #F4F8FA">
  <aside>
    <div class="logo">
      <img src="./images/logo.png" alt="Revaux Logo">
      <span>Revaux</span>
    </div>
    <div class="links">
      <ul class="nav-links">
        <li class="">
          <a href="dashboard.html">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li>
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
        <li class="active">
          <a href="products.html">
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
      <a href="" class="logout">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </div>
  </aside>
  <header>
    <input class="search" type="search" placeholder="Search">
    <div class="admin-header-side">
      <div class="image-container"></div>
      <span>Admin</span>
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </header>
  <main>
    <div class="main-section-container">
      <div class="header-filter-products">
        <div class="option-container">
          <div class="category-container options">
            <label for="category">Category</label>
            <select name="category" id="category" class="select-options">
              <option value="all" disabled selected>All</option>
              <option value="sunglasses">Headwear</option>
              <option value="eyeglasses">Tops</option>
              <option value="accessories">Bottoms</option>
              <option value="accessories">Footwear</option>
            </select>
          </div>
          <div class="status-container options">
            <label for="status">Status</label>
            <select name="status" id="status" class="select-options">
              <option value="status" disabled selected>Select Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="price-container options">
            <label for="" id="price">Price</label>
            <select name="category" id="category" class="select-options">
              <option value="all">Select</option>
              <option value="sunglasses">Headwear</option>
              <option value="eyeglasses">Tops</option>
              <option value="accessories">Bottoms</option>
              <option value="accessories">Footwear</option>
            </select>
          </div>
        </div>
        <div class="add-product-container-filter">
          <div class="filter-container">
            <i class="fa-solid fa-filter"></i>
            <span>Filter</span>
          </div>
          <button class="add-product-btn">
            <span>Add Product</span>
          </button>
        </div>
      </div>
      
      <table>
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Denim Jacket</div>
                  <div class="product-id">ID: SKUP12S2N</div>
                </div>
              </div>
            </td>
            <td>$29.99</td>
            <td>Tops</td>
            <td><span class="status-badge in-stock">In Stock</span></td>
            <td>50</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Another Product</div>
                  <div class="product-id">ID: SKUP12S3M</div>
                </div>
              </div>
            </td>
            <td>$19.99</td>
            <td>Headwear</td>
            <td><span class="status-badge low-stock">Low Stock</span></td>
            <td>5</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Another Product</div>
                  <div class="product-id">ID: SKUP12S3M</div>
                </div>
              </div>
            </td>
            <td>$19.99</td>
            <td>Headwear</td>
            <td><span class="status-badge low-stock">Low Stock</span></td>
            <td>5</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Another Product</div>
                  <div class="product-id">ID: SKUP12S3M</div>
                </div>
              </div>
            </td>
            <td>$19.99</td>
            <td>Headwear</td>
            <td><span class="status-badge low-stock">Low Stock</span></td>
            <td>5</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Another Product</div>
                  <div class="product-id">ID: SKUP12S3M</div>
                </div>
              </div>
            </td>
            <td>$19.99</td>
            <td>Headwear</td>
            <td><span class="status-badge low-stock">Low Stock</span></td>
            <td>5</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="product-cell">
              <div class="product-info">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRh8AhBxUguFTRvQER723Aqkyi9wx_TzxJKYYR2EZVOJy-t53Hc08Dn-EfUytNacmXpA&usqp=CAU" alt="Product Image" class="product-image">
                <div class="product-details">
                  <div class="product-name">Another Product</div>
                  <div class="product-id">ID: SKUP12S3M</div>
                </div>
              </div>
            </td>
            <td>$19.99</td>
            <td>Headwear</td>
            <td><span class="status-badge low-stock">Low Stock</span></td>
            <td>5</td>
            <td><span class="status-badge active">Active</span></td>
            <td class="action-cell">
              <button class="edit-btn">Edit</button>
              <button class="delete-btn">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>