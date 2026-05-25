<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../db.php'; // Ta connexion MySQLi ($conn)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Protection : l'utilisateur doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// 1. Récupérer id_customer et id_cart
$meta_sql  = "SELECT cu.id_customer, ca.id_cart
              FROM customers cu
              LEFT JOIN cart ca ON ca.id_customer = cu.id_customer
              WHERE cu.id_user = ? LIMIT 1";
$meta_stmt = $conn->prepare($meta_sql);
if (!$meta_stmt) { die("Erreur SQL Meta : " . $conn->error); }

$meta_stmt->bind_param("i", $userId);
$meta_stmt->execute();
$meta = $meta_stmt->get_result()->fetch_assoc();

$current_customer_id = $meta ? (int)$meta['id_customer'] : 0;
$current_cart_id = ($meta && !empty($meta['id_cart'])) ? (int)$meta['id_cart'] : 1; 

// 2. Traitement du formulaire de commande
if ($_SERVER["REQUEST_METHOD"] === "POST" && $current_customer_id > 0) {
    $firstName      = $_POST['first_name']      ?? '';
    $lastName       = $_POST['last_name']       ?? '';
    $address        = $_POST['address']         ?? '';
    $city           = $_POST['city']            ?? '';
    $postalCode     = $_POST['postal_code']     ?? '';
    $deliveryMethod = $_POST['delivery_method'] ?? '';

    // Insérer la commande
    $insert_sql  = "INSERT INTO orders
                      (id_customer, first_name, last_name, address, city,
                       postal_code, delivery_method, order_status, date_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $insert_stmt = $conn->prepare($insert_sql);
    if (!$insert_stmt) { die("Erreur SQL Insert Order : " . $conn->error); }
    
    $insert_stmt->bind_param("issssss",
        $current_customer_id, $firstName, $lastName,
        $address, $city, $postalCode, $deliveryMethod);
    $insert_stmt->execute();

    $new_order_id = $conn->insert_id;

    // Transférer les produits du panier vers orders_items
    if ($current_cart_id > 0) {
        $cart_sql  = "SELECT id_product, quantity_cart_items FROM cart_items WHERE id_cart = ?";
        $cart_stmt = $conn->prepare($cart_sql);
        $cart_stmt->bind_param("i", $current_cart_id);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();

        while ($cart_item = $cart_result->fetch_assoc()) {
            // CORRECTION ICI : orders_items et quantity_order_items
            $item_sql  = "INSERT INTO orders_items (id_order, id_product, quantity_order_items) VALUES (?, ?, ?)";
            $item_stmt = $conn->prepare($item_sql);
            $item_stmt->bind_param("iii",
                $new_order_id, $cart_item['id_product'], $cart_item['quantity_cart_items']);
            $item_stmt->execute();
        }
    }
}

// 3. Récupérer toutes les commandes du client
$orders_result = null;
if ($current_customer_id > 0) {
    $sql  = "SELECT * FROM orders WHERE id_customer = ? ORDER BY date_order DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { die("Erreur SQL Select Orders : " . $conn->error); }
    
    $stmt->bind_param("i", $current_customer_id);
    $stmt->execute();
    $orders_result = $stmt->get_result();
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

<main class="container u-margin-top-med">
  <header class="history-header">
    <h1 class="heading-primary">Order History</h1>
    <p class="sub-heading">View and manage your previous collections.</p>
  </header>

  <section class="orders-list">

    <?php if ($orders_result && $orders_result->num_rows > 0): ?>
      <?php while ($order = $orders_result->fetch_assoc()): ?>

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
                // CORRECTION ICI : orders_items
                $item_sql  = "SELECT p.product_image, p.name_product
                              FROM orders_items oi
                              JOIN products p ON oi.id_product = p.id_product
                              WHERE oi.id_order = ?";
                $item_stmt = $conn->prepare($item_sql);
                if ($item_stmt) {
                    $item_stmt->bind_param("i", $order['id_order']);
                    $item_stmt->execute();
                    $items_result = $item_stmt->get_result();
                    if ($items_result->num_rows > 0) {
                        while ($item = $items_result->fetch_assoc()) {
                            // Les images pointent vers ton dossier avatars
                            echo '<img src="' . BASE_URL . 'assets/images/avatars/'
                               . e($item['product_image'])
                               . '" alt="' . e($item['name_product'])
                               . '" class="order-thumb">';
                        }
                    } else {
                        echo '<span style="font-size:1.2rem;color:#777;">No images available</span>';
                    }
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

      <?php endwhile; ?>
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