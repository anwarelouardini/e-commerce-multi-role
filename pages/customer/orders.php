<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

if ((int)$_SESSION['role'] === ROLE_SELLER) {
    header('Location: ' . BASE_URL . 'pages/vendor/seller-profile.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// Récupérer les infos du customer
$user = getCustomerInfo($pdo, $userId);
// Récupérer toutes les commandes 
$orders = [];
$totalOrders = 0;
$inTransit   = 0;
$delivered   = 0;

if (!empty($user['id_customer'])) {
    $customerId = $user['id_customer'];

    $orders = getCustomerOrders($pdo, $customerId);

    $totalOrders = getTotalOrders($pdo, $customerId);

    $inTransit = getOrdersInTransit($pdo, $customerId);

    $delivered = getOrdersDelivered($pdo, $customerId);
}

$headerTitle = 'My Orders';
$header = 'customer-nav';
require_once __DIR__ . '/../../includes/header.php';
?>

  <main class="page-layout">

    <!-- LEFT SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-identity">
        <div class="avatar-wrap">
          <?php if (!empty($user['profile_image'])): ?>
            <img src="<?= BASE_URL ?>assets/images/<?= e($user['profile_image']) ?>" alt="Avatar" class="avatar-img" />
          <?php else: ?>
            <img src="<?= BASE_URL ?>assets/images/avatars/emptyUserImg.png" alt="Avatar" class="avatar-img" />
          <?php endif; ?>
        </div>
        <h1 class="user-name"><?= htmlspecialchars($user['username'] . ' ' . $user['lastname']) ?></h1>
        <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
      </div>

      <!-- Stats sidebar -->
      <div class="card" style="padding: 2rem;">
        <p class="settings-label">ORDER SUMMARY</p>
        <div style="display: flex; flex-direction: column; gap: 1.2rem; margin-top: 1rem;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.3rem; color: var(--secondary);">Total Orders</span>
            <span style="font-weight: 700; color: var(--text-primary);"><?= $totalOrders ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.3rem; color: var(--secondary);">In Transit</span>
            <span style="font-weight: 700; color: var(--primary);"><?= $inTransit ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 1.3rem; color: var(--secondary);">Delivered</span>
            <span style="font-weight: 700; color: var(--green);"><?= $delivered ?></span>
          </div>
        </div>
      </div>

      <div class="logout-wrap">
        <a href="<?= BASE_URL ?>pages/customer/user-profil.php">
          <button class="btn-logout">
            <i class="fa-solid fa-arrow-left"></i> Back to Profile
          </button>
        </a>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <section class="main-content" style="margin-bottom: 10rem;">

      <div class="content-header">
        <h2 class="content-title">My Orders</h2>
        <span class="content-subtitle">Track and manage all your purchases</span>
      </div>

      <?php if (empty($orders)): ?>
        <div class="card" style="text-align: center; padding: 4rem 2rem;">
          <i class="fa-solid fa-box-open" style="font-size: 4rem; color: var(--neutral); margin-bottom: 1.5rem;"></i>
          <h3 style="font-size: 1.8rem; font-weight: 700; color: var(--text-primary);">No orders yet</h3>
          <p style="color: var(--secondary); margin-top: 0.5rem;">Start shopping to see your orders here.</p>
        </div>
      <?php else: ?>

        <!-- Orders list -->
        <div class="content-section">
          <?php foreach ($orders as $order):
            $sc = statusDotClass($order['order_status']);
          ?>
          <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 1.5rem;">

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; border-bottom: 1px solid var(--light-grey); background: var(--bg-page);">
              <div style="display: flex; align-items: center; gap: 2rem;">
                <div>
                  <p style="font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--secondary);">Order ID</p>
                  <p style="font-size: 1.4rem; font-weight: 700; color: var(--text-primary);">#<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></p>
                </div>
                <div>
                  <p style="font-size: 1.1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--secondary);">Date</p>
                  <p style="font-size: 1.3rem; color: var(--neutral);"><?= date('M j, Y', strtotime($order['date_order'])) ?></p>
                </div>
              </div>
              <span class="status-indicator status-indicator--<?= statusIndicatorClass($order['order_status']) ?>">
                <?= statusLabel($order['order_status']) ?>
              </span>
            </div>

            <!-- Product detail -->
            <div style="display: flex; align-items: center; gap: 2rem; padding: 2rem;">
              <div class="order-img-wrap">
                <img
                  src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($order['product_image'] ?? 'placeholder.jpg') ?>"
                  alt="<?= htmlspecialchars($order['name_product']) ?>"
                  class="order-img"
                  onerror="this.src='<?= BASE_URL ?>assets/images/users/emptyImgUser.jpg'"
                />
              </div>
              <div style="flex: 1;">
                <p class="order-name"><?= htmlspecialchars($order['name_product']) ?></p>
                <p style="font-size: 1.2rem; color: var(--neutral); margin-top: 0.3rem;"><?= htmlspecialchars($order['description_product'] ?? '') ?></p>
                <p style="font-size: 1.2rem; color: var(--secondary); margin-top: 0.5rem;">Qty: <strong><?= (int)$order['quantity_order_items'] ?></strong></p>
              </div>
              <div style="text-align: right;">
                <p style="font-size: 1.8rem; font-weight: 800; color: var(--text-primary);">
                  $<?= number_format($order['price'] * $order['quantity_order_items'], 2) ?>
                </p>
                <p style="font-size: 1.2rem; color: var(--neutral);">$<?= number_format($order['price'], 2) ?> each</p>
              </div>
            </div>

          </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>

    </section>
  </main>

</body>
</html>