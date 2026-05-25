<?php
// Pull in the central connection file
require_once '../../db.php'; 

// Temporary test variable: Assume User ID 64 (Test1 User) is currently logged in
$current_user_id = 64;

// Fetch user info and their saved address from the database
$sql = "SELECT u.username, u.lastname, c.address_customer 
        FROM users u 
        LEFT JOIN customers c ON u.id_user = c.id_user 
        WHERE u.id_user = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Set default empty variables
$firstName = "";
$lastName = "";
$address = "";

// If we find the user, overwrite the empty variables with their real data
if ($row = $result->fetch_assoc()) {
    $firstName = $row['username'];
    $lastName = $row['lastname'];
    $address = $row['address_customer'] ?? ''; // Fallback to empty if NULL
}
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
  <title>Checkout cart</title>
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
  <main class="checkout-container container">
  <div class="stepper">
    <div class="step step--completed">
      <span class="step__icon">✓</span>
      <span class="step__label">CART</span>
    </div>
    <div class="step-line"></div>
    <div class="step step--active">
      <span class="step__number">2</span>
      <span class="step__label">SHIPPING</span>
    </div>
    <div class="step-line"></div>
    <div class="step">
      <span class="step__number">3</span>
      <span class="step__label">HISTORY</span>
    </div>
  </div>

  <div class="checkout-content">
    <section class="checkout-form">
      <h2 class="section-title">Shipping Address <span class="step-hint">Step 2 of 3</span></h2>
      
      <form action="history.php" method="POST"> 
        
        <div class="form-grid">
          <div class="input-group">
            <label>FIRST NAME</label>
            <input type="text" name="first_name" placeholder="Julian" value="<?php echo htmlspecialchars($firstName); ?>" required>
          </div>
          <div class="input-group">
            <label>LAST NAME</label>
            <input type="text" name="last_name" placeholder="Meridian" value="<?php echo htmlspecialchars($lastName); ?>" required>
          </div>
          <div class="input-group full-width">
            <label>ADDRESS</label>
            <input type="text" name="address" placeholder="1248 Editorial Way, Suite 400" value="<?php echo htmlspecialchars($address); ?>" required>
          </div>
          <div class="input-group">
            <label>CITY</label>
            <input type="text" name="city" placeholder="New York" required>
          </div>
          <div class="input-group">
            <label>POSTAL CODE</label>
            <input type="text" name="postal_code" placeholder="10001" required>
          </div>
        </div>

        <h2 class="section-title u-margin-top-med">Delivery Method</h2>
        <div class="delivery-options">
          
          <label class="delivery-card delivery-card--active" data-cost="24.00">
            <input type="radio" name="delivery_method" value="Express Courier" checked>
            <div class="card-content">
              <div class="delivery-info">
                <h3>Express Courier</h3>
                <p>Delivery in 1-2 business days. Fully tracked.</p>
                <span class="delivery-price">$24.00</span>
              </div>
            </div>
          </label>

          <label class="delivery-card" data-cost="0.00">
            <input type="radio" name="delivery_method" value="Standard Editorial">
            <div class="card-content">
              <div class="delivery-info">
                <h3>Standard Editorial</h3>
                <p>Delivery in 4-7 business days. Sustainably packed.</p>
                <span class="delivery-price">Free</span>
              </div>
            </div>
          </label>
        </div>

        <div class="form-footer">
          <a href="cart.php" class="back-link">← Back to Cart</a>
          <button type="submit" class="btn-primary">Proceed to history →</button>
        </div>
      </form>
      </section>

    <aside class="order-summary order-summary--mini">
      <h3 class="summary-title">Order Summary</h3>
     <div class="summary-items-list"></div>
      <hr class="summary-divider">

      <div class="summary-row">
        <span>Subtotal</span>
        <span class="summary-value" id="shipping-subtotal">$450.00</span>
      </div>
      <div class="summary-row">
        <span>Shipping</span>
        <span class="summary-value" id="shipping-display">$24.00</span>
      </div>
      <div class="summary-row">
        <span>Tax</span>
        <span class="summary-value" id="shipping-tax">$38.00</span>
      </div>

      <hr class="summary-divider">

      <div class="summary-row total-row">
        <span>TOTAL</span>
        <span class="total-price" id="shipping-total">$512.00</span>
      </div>
    </aside>
  </div>
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

<script src="../../assets/js/pages/client/shipping.js"></script>
</body>
</html>