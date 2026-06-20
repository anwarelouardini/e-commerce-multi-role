<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../db.php';

// Protection : l'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Pré-remplir le formulaire avec les données du profil
$sql  = "SELECT u.username, u.lastname, c.address_customer
         FROM users u
         LEFT JOIN customers c ON c.id_user = u.id_user
         WHERE u.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$firstName = $row['username']          ?? '';
$lastName  = $row['lastname']          ?? '';
$address   = $row['address_customer']  ?? '';

// Header
$header      = 'customer-nav';
$headerTitle = 'Checkout';
require_once __DIR__ . '/../../includes/header.php';
?>

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