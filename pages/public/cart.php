<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Protection : l'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

// Header
$header      = 'customer-nav';
$headerTitle = 'Shopping Cart';
$pathCSS     = ['assets/css/pages/cart.css'];
require_once __DIR__ . '/../../includes/header.php';
?>

<?php foreach (($pathCSS ?? []) as $css): ?>
  <link rel="stylesheet" href="<?= BASE_URL . $css ?>">
<?php endforeach; ?>

<header class="header container u-margin-top-med">
  <h1 class="heading-primary">
    Your Cart
    <span class="sub-heading">Curated for your collection</span>
  </h1>
</header>

<main class="cart-container">
  <div class="cart-items" id="cart-items-container">
    <!-- Rempli par cart.js depuis localStorage -->
    <div style="padding: 40px; text-align: center; font-size: 1.6rem;">
      <p>Loading your cart…</p>
    </div>
  </div>

  <aside class="order-summary">
    <h2 class="summary-title">Order Summary</h2>
    <div class="summary-row">
      <span>Subtotal</span>
      <span class="summary-value" id="subtotal">$0.00</span>
    </div>
    <div class="summary-row">
      <span>Shipping Estimate</span>
      <span class="summary-value">$45.00</span>
    </div>
    <div class="summary-row">
      <span>Tax Calculation</span>
      <span class="summary-value" id="tax">$0.00</span>
    </div>
    <hr class="summary-divider">
    <div class="summary-row total-row">
      <span>TOTAL</span>
      <span class="total-price" id="total">$0.00</span>
    </div>

    <a href="<?= BASE_URL ?>pages/public/shipping.php" id="checkout-btn" style="text-decoration: none;">
      <button class="btn-checkout">PROCEED TO CHECKOUT</button>
    </a>

    <div class="summary-features">
      <p>🛡️ SECURE CHECKOUT GUARANTEED</p>
      <p>🚚 COMPLIMENTARY WHITE-GLOVE DELIVERY</p>
    </div>
    <div class="summary-footer">
      Need assistance? Contact our <a href="#">Concierge Service</a>
      or view our <a href="#">Shipping Policy</a>.
    </div>
  </aside>
</main>

<footer class="footer">
  <div class="footer__content container">
    <div class="footer__brand">
      <h2 class="footer__logo">GAAM Shop</h2>
      <p class="footer__description">
        Elevating living spaces through a meticulously curated collection of modernist
        furniture and fine architectural details in the GAAM shop.
      </p>
    </div>
    <ul class="footer__links">
      <li><a href="<?= BASE_URL ?>pages/public/home.php" class="footer__link">Home</a></li>
      <li><a href="<?= BASE_URL ?>pages/public/product-catalog.php" class="footer__link">Catalog</a></li>
      <li><a href="<?= BASE_URL ?>pages/public/category-page.php" class="footer__link">Categories</a></li>
      <li><a href="<?= BASE_URL ?>pages/public/cart.php" class="footer__link">Cart</a></li>
    </ul>
  </div>
  <div class="footer__bottom container">
    <p class="footer__copyright">&copy; 2026 THE GAAM Shop</p>
  </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/pages/client/cart.js"></script>
</body>
</html>