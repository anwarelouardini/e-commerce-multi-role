<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/db.php'; // $pdo (PDO)

// Protection : l'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// 1. Récupérer id_customer
$meta_stmt = $pdo->prepare("SELECT cu.id_customer
              FROM customers cu
              WHERE cu.id_user = ? LIMIT 1");
$meta_stmt->execute([$userId]);
$meta = $meta_stmt->fetch();

$current_customer_id = $meta ? (int)$meta['id_customer'] : 0;
$orderPlaced = false;

// 2. Traitement du formulaire de commande (POST depuis shipping.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_customer_id > 0) {
    $firstName      = trim($_POST['first_name']      ?? '');
    $lastName       = trim($_POST['last_name']       ?? '');
    $address        = trim($_POST['address']         ?? '');
    $city           = trim($_POST['city']            ?? '');
    $postalCode     = trim($_POST['postal_code']     ?? '');
    $deliveryMethod = trim($_POST['delivery_method'] ?? '');

    // Les items viennent du POST (hidden input injecté par shipping.js)
    $cartJson = $_POST['cart_items'] ?? '[]';
    $cartItems = json_decode($cartJson, true) ?: [];

    if (!empty($cartItems)) {
        try {
            $pdo->beginTransaction();

            // Insérer la commande
            $pdo->prepare("INSERT INTO orders
                              (id_customer, first_name, last_name, address, city,
                               postal_code, delivery_method, order_status, date_order)
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())")
                ->execute([$current_customer_id, $firstName, $lastName,
                           $address, $city, $postalCode, $deliveryMethod]);

            $new_order_id = (int)$pdo->lastInsertId();

            // Insérer les items depuis le panier localStorage
            $item_stmt = $pdo->prepare(
                "INSERT INTO orders_items (id_order, id_product, quantity_order_items)
                 VALUES (?, ?, ?)"
            );
            foreach ($cartItems as $item) {
                $item_stmt->execute([
                    $new_order_id,
                    (int)$item['id'],
                    (int)$item['quantity']
                ]);
            }

            $pdo->commit();
            $orderPlaced = true;

        } catch (PDOException $e) {
            $pdo->rollBack();
        }
    }
}

// 3. Récupérer toutes les commandes du client
$orders = [];
if ($current_customer_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id_customer = ? ORDER BY date_order DESC");
    $stmt->execute([$current_customer_id]);
    $orders = $stmt->fetchAll();
}

// Header
$header      = 'customer-nav';
$headerTitle = 'Order History';
require_once __DIR__ . '/../../includes/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/pages/history.css">
<style>
  details > summary { list-style: none; }
  details > summary::-webkit-details-marker { display: none; }
  .details-box {
    margin-top: 15px; padding: 15px;
    background: #f8f9fa; border-radius: 8px;
    font-size: 1.4rem; text-align: left;
  }
  .order-card__content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
  }
  .order-items-preview { display: flex; gap: 15px; }
  .order-actions { margin-left: auto; }
</style>

<?php if ($orderPlaced): ?>
<script>
  // Vider le localStorage après confirmation de commande
  localStorage.removeItem('gaamCart');
  localStorage.removeItem('gaamShipping');
</script>
<?php endif; ?>

<main class="container u-margin-top-med">
  <header class="history-header">
    <h1 class="heading-primary">Order History</h1>
    <p class="sub-heading">View and manage your previous collections.</p>
  </header>

  <section class="orders-list">

    <?php if (!empty($orders)): ?>
      <?php foreach ($orders as $order): ?>

        <div class="order-card">
          <div class="order-card__header">
            <div class="order-info">
              <span class="order-label">ORDER PLACED</span>
              <span class="order-value"><?= date("F j, Y", strtotime($order['date_order'])) ?></span>
            </div>
            <div class="order-info">
              <span class="order-label">ORDER #</span>
              <span class="order-value">GS-<?= (int)$order['id_order'] ?></span>
            </div>
            <div class="order-info">
              <span class="order-label">TOTAL</span>
              <span class="order-value">--</span>
            </div>
            <div class="order-status status--<?= strtolower(e($order['order_status'])) ?>">
              <?= ucfirst(e($order['order_status'])) ?>
            </div>
          </div>

          <div class="order-card__content">
            <div class="order-items-preview">
              <?php
                $item_stmt = $pdo->prepare("SELECT p.product_image, p.name_product
                              FROM orders_items oi
                              JOIN products p ON oi.id_product = p.id_product
                              WHERE oi.id_order = ?");
                $item_stmt->execute([$order['id_order']]);
                $items = $item_stmt->fetchAll();
                if (!empty($items)) {
                    foreach ($items as $item) {
                        echo '<img src="' . BASE_URL . 'assets/images/products/'
                           . e($item['product_image'])
                           . '" alt="' . e($item['name_product'])
                           . '" class="order-thumb"'
                           . ' onerror="if(!this.dataset.e){this.dataset.e=1;this.style.display=\'none\';}"'
                           . '>';
                    }
                } else {
                    echo '<span style="font-size:1.2rem;color:#777;">No images available</span>';
                }
              ?>
            </div>

            <div class="order-actions">
              <details>
                <summary class="btn-secondary" style="cursor:pointer;">View Details</summary>
                <div class="details-box">
                  <h4 style="margin-bottom:8px;color:#1a237e;">Shipping Information</h4>
                  <p><strong>Recipient:</strong> <?= e($order['first_name'] . ' ' . $order['last_name']) ?></p>
                  <p><strong>Address:</strong> <?= e($order['address'] . ', ' . $order['city'] . ' ' . $order['postal_code']) ?></p>
                  <hr style="margin:12px 0;border:0;border-top:1px solid #ddd;">
                  <p><strong>Delivery Method:</strong> <?= e($order['delivery_method']) ?></p>
                </div>
              </details>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    <?php else: ?>
      <div style="padding:40px;text-align:center;font-size:1.6rem;">
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
</body>
</html>