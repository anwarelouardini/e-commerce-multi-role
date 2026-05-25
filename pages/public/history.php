<?php
// 1. Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Pull in the central connection file
require_once '../../db.php'; 

// Temporary test variables
$current_customer_id = 1;
$current_cart_id = 1; 

// 3. CATCH AND SAVE NEW ORDERS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postalCode = $_POST['postal_code'] ?? '';
    $deliveryMethod = $_POST['delivery_method'] ?? '';

    // A. Save the main order details
    $insert_sql = "INSERT INTO orders (id_customer, first_name, last_name, address, city, postal_code, delivery_method, order_status, date_order) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
                   
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("issssss", $current_customer_id, $firstName, $lastName, $address, $city, $postalCode, $deliveryMethod);
    $insert_stmt->execute();
    
    // B. Get the ID of the brand new order we just created
    $new_order_id = $conn->insert_id;
    
    // C. Get all items currently sitting in the user's cart
    $cart_sql = "SELECT id_product, quantity_cart_items FROM cart_items WHERE id_cart = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $current_cart_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    
    // D. Loop through the cart items and permanently attach them to the new order!
    while ($cart_item = $cart_result->fetch_assoc()) {
        $item_sql = "INSERT INTO order_items (id_order, id_product, quantity) VALUES (?, ?, ?)";
        $item_stmt = $conn->prepare($item_sql);
        $item_stmt->bind_param("iii", $new_order_id, $cart_item['id_product'], $cart_item['quantity_cart_items']);
        $item_stmt->execute();
    }
}

// 4. FETCH ALL ORDERS FOR HISTORY DISPLAY
$sql = "SELECT * FROM orders WHERE id_customer = ? ORDER BY date_order DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_customer_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="../../assets/css/main.css" />
      <link rel="stylesheet" href="../../assets/css/pages/cart.css">
      <link rel="stylesheet" href="../../assets/css/pages/shipping.css">
      <link rel="stylesheet" href="../../assets/css/pages/history.css">
    <title>Order History</title>
    <style>
        /* Details styling */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        .details-box { 
            margin-top: 15px; 
            padding: 15px; 
            background: #f8f9fa; 
            border-radius: 8px; 
            font-size: 1.4rem; 
            text-align: left; 
        }
        
        /* Restoring the Flexbox Layout for the Content Area */
        .order-card__content {
            display: flex;
            justify-content: space-between; /* Pushes children to opposite ends */
            align-items: center; /* Vertically center items */
            padding: 20px;
        }

        .order-items-preview {
            display: flex;
            gap: 15px; /* Space between images */
        }
        
        /* Ensure the button stays on the right */
        .order-actions {
            margin-left: auto; 
        }
    </style>
  </head>
  <body>
    <nav class="navigation">
        <div class="navigation-left">
          <div class="navigation__icon">&nbsp;</div>
          <div class="navigation__logo">
            <h1 id="navigation__logo">GAAM SHOP</h1>
          </div>
        </div>

        <ul class="navigation__links">
          <li class="navigation__item">
            <a class="navigation__link navigation__link--active" href="./shop.html">SHOP</a>
          </li>
          <li class="navigation__item">
            <a class="navigation__link" href="./collections.html">COLLECTIONS</a>
          </li>
        </ul>
        <div class="navigation__icons-wrapper">
          <a href="#" class="nav-icon-link">
            <img src="../../assets/images/avatars/icons8-search-50.png" alt="Search" class="nav-icon">
          </a>
          <a href="#" class="nav-icon-link nav-icon-link--active">
            <img src="../../assets/images/avatars/bag-shopping-solid.png" alt="Cart" class="nav-icon">
          </a>
          <a href="#" class="nav-icon-link">
            <img src="../../assets/images/avatars/icons8-profile-48.png" alt="Profile" class="nav-icon">
          </a>
        </div>
    </nav>
    
    <main class="container u-margin-top-med">
      <header class="history-header">
        <h1 class="heading-primary">Order History</h1>
        <p class="sub-heading">View and manage your previous collections.</p>
      </header>

      <section class="orders-list">
        
        <?php if ($orders_result->num_rows > 0): ?>
            <?php while($order = $orders_result->fetch_assoc()): ?>
                
                <div class="order-card">
                  <div class="order-card__header">
                    <div class="order-info">
                      <span class="order-label">ORDER PLACED</span>
                      <span class="order-value"><?php echo date("F j, Y", strtotime($order['date_order'])); ?></span>
                    </div>
                    <div class="order-info">
                      <span class="order-label">ORDER #</span>
                      <span class="order-value">GS-<?php echo $order['id_order']; ?></span>
                    </div>
                    <div class="order-info">
                        <span class="order-label">TOTAL</span>
                        <span class="order-value">--</span>
                    </div>
                    <div class="order-status status--<?php echo strtolower($order['order_status']); ?>">
                        <?php echo ucfirst($order['order_status']); ?>
                    </div>
                  </div>

                  <div class="order-card__content">
                    
                    <div class="order-items-preview">
                        <?php
                            // Fetch products associated with THIS specific order
                            // Adjust the table name 'order_items' to match your actual database schema
                            $item_sql = "SELECT p.product_image, p.name_product 
                                         FROM order_items oi 
                                         JOIN products p ON oi.id_product = p.id_product 
                                         WHERE oi.id_order = ?";
                            
                            $item_stmt = $conn->prepare($item_sql);
                            
                            if ($item_stmt) {
                                $item_stmt->bind_param("i", $order['id_order']);
                                $item_stmt->execute();
                                $items_result = $item_stmt->get_result();
                                
                                if ($items_result->num_rows > 0) {
                                    while($item = $items_result->fetch_assoc()) {
                                        echo '<img src="../../assets/images/avatars/' . htmlspecialchars($item['product_image']) . '" alt="' . htmlspecialchars($item['name_product']) . '" class="order-thumb">';
                                    }
                                } else {
                                    echo '<span style="font-size: 1.2rem; color: #777;">No images available</span>';
                                }
                            } else {
                                echo '<span style="font-size: 1.2rem; color: red;">Database structure needs update to show items.</span>';
                            }
                        ?>
                    </div>

                    <div class="order-actions">
                      <details>
                        <summary class="btn-secondary" style="cursor: pointer;">View Details</summary>
                        
                        <div class="details-box">
                          <h4 style="margin-bottom: 8px; color: #1a237e;">Shipping Information</h4>
                          <p><strong>Recipient:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                          <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address'] . ', ' . $order['city'] . ' ' . $order['postal_code']); ?></p>
                          <hr style="margin: 12px 0; border: 0; border-top: 1px solid #ddd;">
                          <p><strong>Delivery Method:</strong> <?php echo htmlspecialchars($order['delivery_method']); ?></p>
                        </div>
                      </details>
                    </div>

                  </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div style="padding: 40px; text-align: center; font-size: 1.6rem;">
                <p>You haven't placed any orders yet.</p>
            </div>
        <?php endif; ?>

      </section>
    </main>

    <footer class="footer">
      <div class="footer__content container">
        <div class="footer__brand">
          <h2 class="footer__logo">GAAM Shop</h2>
          <p class="footer__description">
            Elevating living spaces through a meticulously curated collection of modernist furniture and fine architectural details in the GAAM shop.
          </p>
        </div>
        <ul class="footer__links">
          <li><a href="#" class="footer__link">Shipping</a></li>
          <li><a href="#" class="footer__link">Returns</a></li>
          <li><a href="#" class="footer__link">Privacy</a></li>
          <li><a href="#" class="footer__link">Bespoke Service</a></li>
        </ul>
      </div>
      <div class="footer__bottom container">
        <p class="footer__copyright">&copy; 2026 THE GAAM Shop</p>
      </div>
    </footer>
  </body>
</html>