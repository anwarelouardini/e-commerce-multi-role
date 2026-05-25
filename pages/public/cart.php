<?php
// 1. Pull in the central connection file
require_once '../../db.php'; 

// Temporary test variable: Assume we are looking at Cart ID 1
$current_cart_id = 1; 

// 2. Fetch cart items by joining 'cart_items' and 'products' tables
$sql = "SELECT p.id_product, p.name_product, p.description_product, p.price, p.product_image, ci.quantity_cart_items 
        FROM cart_items ci 
        JOIN products p ON ci.id_product = p.id_product 
        WHERE ci.id_cart = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_cart_id);
$stmt->execute();
$result = $stmt->get_result();

// Variables to keep track of our mathematical totals
$subtotal = 0;
$total_items = 0;
?>

<!DOCTYPE html>
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
  <title>Shopping cart</title>
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

  <header class="header container u-margin-top-med">
        <h1 class="heading-primary">
          Your Cart
          <span class="sub-heading">Curated for your collection</span>
        </h1>
  </header>

<main class="cart-container">
  <div class="cart-items">
    
    <!-- 4. Start the PHP Loop for Dynamic Products -->
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): 
            // Calculate math for this specific item row
            $item_total = $row['price'] * $row['quantity_cart_items'];
            $subtotal += $item_total;
            $total_items += $row['quantity_cart_items'];
        ?>
        
        <section class="card-shop">
          <div class="img-design">
            <!-- Dynamically load the image path saved in the database -->
            <img src="../../assets/images/avatars/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['name_product']); ?>">
          </div>

          <div class="contenu-card">
            <div class="top-content">
              <div class="text-content">
                <!-- Dynamically load the product name and description -->
                <h2><?php echo htmlspecialchars($row['name_product']); ?></h2>
                <span class="sub-heading"><?php echo htmlspecialchars($row['description_product']); ?></span>
              </div>
              <!-- Dynamically load the exact price -->
              <div class="price" data-price="<?php echo $row['price']; ?>">$<?php echo number_format($row['price'], 2); ?></div>
            </div>

            <div class="bottom-content">
              <div class="quantity">
                <button class="btn-minus">-</button>
                <!-- Dynamically load the quantity chosen by the user -->
                <span class="qty-display"><?php echo $row['quantity_cart_items']; ?></span>
                <button class="btn-plus">+</button>
              </div>
              
              <button class="btn-remove">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
                REMOVE
              </button>
            </div>
          </div>
        </section>
        
        <?php endwhile; ?>
    <?php else: ?>
        <!-- Display this clean message if the cart is completely empty -->
        <div style="padding: 40px; text-align: center; font-size: 1.6rem;">
            <p>Your cart is currently empty.</p>
        </div>
    <?php endif; ?>
    <!-- End the PHP Loop -->

  </div>

  <aside class="order-summary">
    <h2 class="summary-title">Order Summary</h2>
    
    <?php 
        // 5. Calculate final dynamic totals for the summary block
        $shipping_estimate = 45.00;
        $tax_rate = 0.08; // 8% tax calculation
        $tax_amount = $subtotal * $tax_rate;
        $final_total = $subtotal + $shipping_estimate + $tax_amount;
    ?>

    <div class="summary-row">
      <span>Subtotal</span>
      <!-- Inject the calculated subtotal -->
      <span class="summary-value" id="subtotal">$<?php echo number_format($subtotal, 2); ?></span>
    </div>
    <div class="summary-row">
      <span>Shipping Estimate</span>
      <span class="summary-value">$<?php echo number_format($shipping_estimate, 2); ?></span>
    </div>
    <div class="summary-row">
      <span>Tax Calculation</span>
      <!-- Inject the calculated tax -->
      <span class="summary-value" id="tax">$<?php echo number_format($tax_amount, 2); ?></span>
    </div>

    <hr class="summary-divider">

    <div class="summary-row total-row">
      <span>TOTAL</span>
      <!-- Inject the final calculated total -->
      <span class="total-price" id="total">$<?php echo number_format($final_total, 2); ?></span>
    </div>
    
    <a href="shipping.php" style="text-decoration: none;">
      <button class="btn-checkout">PROCEED TO CHECKOUT</button>
    </a>
  
    <div class="summary-features">
      <p>🛡️ SECURE CHECKOUT GUARANTEED</p>
      <p>🚚 COMPLIMENTARY WHITE-GLOVE DELIVERY</p>
    </div>

    <div class="summary-footer">
      Need assistance? Contact our <a href="#">Concierge Service</a> or view our <a href="#">Shipping Policy</a>.
    </div>
  </aside>
</main>

<footer class="footer">
  <!-- Footer content remains perfectly unchanged -->
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

<script src="../../assets/js/pages/client/cart.js"></script>
</body>
</html>