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

// Récupérer l'id_cart lié au customer connecté
$userId = (int)$_SESSION['user_id'];

$cart_sql = "SELECT c.id_cart FROM cart c
             JOIN customers cu ON cu.id_customer = c.id_customer
             WHERE cu.id_user = ? LIMIT 1";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $userId);
$cart_stmt->execute();
$cart_row = $cart_stmt->get_result()->fetch_assoc();
$current_cart_id = $cart_row ? (int)$cart_row['id_cart'] : 0;

// Fetch des articles du panier
$subtotal    = 0;
$total_items = 0;
$result      = null;

if ($current_cart_id > 0) {
    $sql  = "SELECT p.id_product, p.name_product, p.description_product,
                    p.price, p.product_image, ci.quantity_cart_items
             FROM cart_items ci
             JOIN products p ON ci.id_product = p.id_product
             WHERE ci.id_cart = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $current_cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
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
  <div class="cart-items">

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()):
        $item_total   = $row['price'] * $row['quantity_cart_items'];
        $subtotal    += $item_total;
        $total_items += $row['quantity_cart_items'];
      ?>
      <section class="card-shop">
        <div class="img-design">
          <img src="<?= BASE_URL ?>assets/images/products/<?= e($row['product_image']) ?>"
               alt="<?= e($row['name_product']) ?>">
        </div>
        <div class="contenu-card">
          <div class="top-content">
            <div class="text-content">
              <h2><?= e($row['name_product']) ?></h2>
              <span class="sub-heading"><?= e($row['description_product']) ?></span>
            </div>
            <div class="price" data-price="<?= $row['price'] ?>">
              $<?= number_format($row['price'], 2) ?>
            </div>
          </div>
          <div class="bottom-content">
            <div class="quantity"
                 data-product-id="<?= (int)$row['id_product'] ?>"
                 data-cart-id="<?= $current_cart_id ?>">
              <button class="btn-minus">-</button>
              <span class="qty-display"><?= (int)$row['quantity_cart_items'] ?></span>
              <button class="btn-plus">+</button>
            </div>
            <button class="btn-remove"
                    data-product-id="<?= (int)$row['id_product'] ?>"
                    data-cart-id="<?= $current_cart_id ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor"
                   stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
      <div style="padding: 40px; text-align: center; font-size: 1.6rem;">
        <p>Your cart is currently empty.</p>
      </div>
    <?php endif; ?>
  </div>

  <aside class="order-summary">
    <h2 class="summary-title">Order Summary</h2>
    <?php
      $shipping_estimate = 45.00;
      $tax_rate          = 0.08;
      $tax_amount        = $subtotal * $tax_rate;
      $final_total       = $subtotal + $shipping_estimate + $tax_amount;
    ?>
    <div class="summary-row">
      <span>Subtotal</span>
      <span class="summary-value" id="subtotal">$<?= number_format($subtotal, 2) ?></span>
    </div>
    <div class="summary-row">
      <span>Shipping Estimate</span>
      <span class="summary-value">$<?= number_format($shipping_estimate, 2) ?></span>
    </div>
    <div class="summary-row">
      <span>Tax Calculation</span>
      <span class="summary-value" id="tax">$<?= number_format($tax_amount, 2) ?></span>
    </div>
    <hr class="summary-divider">
    <div class="summary-row total-row">
      <span>TOTAL</span>
      <span class="total-price" id="total">$<?= number_format($final_total, 2) ?></span>
    </div>

    <a href="<?= BASE_URL ?>pages/public/shipping.php" style="text-decoration: none;">
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

<script src="<?= BASE_URL ?>assets/js/pages/client/cart.js"></script>
</body>
</html>
