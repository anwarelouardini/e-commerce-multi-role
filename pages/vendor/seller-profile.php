<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

session_start();

define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);
// Protéger la page
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}
if ((int)$_SESSION['role'] !== ROLE_SELLER) {
    header('Location: ' . BASE_URL . 'pages/customer/user-profil.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// ── Récupérer les infos du seller ────────────────────────────
$stmt = $pdo->prepare("
    SELECT u.*, s.id_seller, s.store_name, s.seller_rating
    FROM users u
    JOIN sellers s ON s.id_user = u.id_user
    WHERE u.id_user = ?
");
$stmt->execute([$userId]);
$seller = $stmt->fetch();

if (!$seller) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

$sellerId = (int)$seller['id_seller'];

// ── Récupérer les produits du seller ─────────────────────────
$stmt = $pdo->prepare("
    SELECT p.*, c.name_categorie
    FROM products p
    JOIN categories c ON c.id_categorie = p.id_categorie
    WHERE p.id_seller = ?
    ORDER BY p.id_product DESC
");
$stmt->execute([$sellerId]);
$products = $stmt->fetchAll();
$totalProducts = count($products);

// ── Stats ventes ─────────────────────────────────────────────
$stmt = $pdo->prepare("
    SELECT COUNT(oi.id_order_items) as total_sales
    FROM orders_items oi
    JOIN products p ON p.id_product = oi.id_product
    WHERE p.id_seller = ?
");
$stmt->execute([$sellerId]);
$salesData = $stmt->fetch();
$totalSales = (int)($salesData['total_sales'] ?? 0);

// ── Traitement mise à jour profil seller ─────────────────────
$updateMsg = '';
$updateErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'update_seller') {
              if ($_FILES['sellerImg']['error'] === 0 && $_FILES['sellerImg']['size'] !== 0) {
            $extension = pathinfo($_FILES['sellerImg']['name'], PATHINFO_EXTENSION);
            $filename = 'seller_' . $userId . '.' . $extension;
            move_uploaded_file(
                $_FILES['sellerImg']['tmp_name'], 
                __DIR__ . '/../../assets/images/users/vendor/' . $filename
            );

            $pdo->prepare("UPDATE users SET profile_image = ? WHERE id_user = ?")
                ->execute(['users/vendor/' . $filename, $userId]);
        }

        $newUsername   = trim($_POST['username']   ?? '');
        $newLastname   = trim($_POST['lastname']   ?? '');
        $newPhone      = trim($_POST['phone']      ?? '');
        $newBio        = trim($_POST['bio']        ?? '');
        $newStoreName  = trim($_POST['store_name'] ?? '');

        if (empty($newUsername) || empty($newStoreName)) {
            $updateErr = 'Le prénom et le nom de la boutique sont requis.';
        } else {
            try {
                $pdo->prepare("UPDATE users SET username=?, lastname=?, phone_number=?, bio=? WHERE id_user=?")
                    ->execute([$newUsername, $newLastname, $newPhone, $newBio, $userId]);

                $pdo->prepare("UPDATE sellers SET store_name=? WHERE id_seller=?")
                    ->execute([$newStoreName, $sellerId]);

                $_SESSION['username']  = $newUsername;
                $_SESSION['store_name'] = $newStoreName;
                $updateMsg = 'Profil mis à jour !';

                // Rafraîchir
                $stmt = $pdo->prepare("SELECT u.*, s.id_seller, s.store_name, s.seller_rating FROM users u JOIN sellers s ON s.id_user = u.id_user WHERE u.id_user = ?");
                $stmt->execute([$userId]);
                $seller = $stmt->fetch();

            } catch (PDOException $e) {
                $updateErr = 'Erreur lors de la mise à jour.';
            }
        }
    }
}


$featured   = $products[0]  ?? null;
$gridItems  = array_slice($products, 1, 4);

$header = 'vendor-nav';
$headerTitle = 'GAAM Seller';
require_once __DIR__ . '/../../includes/header.php';
?>

  <!-- Cover hero -->
  <div class="desktop-cover">
    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1400&h=320&fit=crop&auto=format"
         alt="Store Cover" class="desktop-cover-img" />
    <div class="desktop-cover-overlay"></div>
  </div>

  <!-- Page layout -->
  <div class="desktop-layout">

    <!-- LEFT COLUMN -->
    <aside class="desktop-sidebar">

     <?php if ($updateMsg): ?>
      <div class="msg msg--success"><?= htmlspecialchars($updateMsg) ?></div>
      <script>
          window.addEventListener('DOMContentLoaded', function() {
              const modal = document.getElementById('editModal');
              if (modal) modal.classList.remove('modal--open');
          });
      </script>
      <?php endif; ?>
      <?php if ($updateErr): ?>
        <div class="msg msg--error"><?= htmlspecialchars($updateErr) ?></div>
      <?php endif; ?>

      <div class="card seller-card">
      <div class="desktop-avatar-wrap">
        <?php if (!empty($seller['profile_image'])): ?>
          <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($seller['profile_image']) ?>" alt="Seller" class="desktop-avatar" id="sellerAvatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
        <?php else: ?>
          <img src="<?= BASE_URL ?>assets/images/avatars/emptyUserImg.png" alt="Seller" class="desktop-avatar" id="sellerAvatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
        <?php endif; ?>
      </div>
        <div class="seller-name-row">
          <h1 class="seller-name"><?= htmlspecialchars($seller['store_name']) ?></h1>
          <span class="verified-badge"><i class="fa-solid fa-circle-check"></i></span>
        </div>
        <p class="seller-subtitle">
          <?= htmlspecialchars($seller['username'] . ' ' . $seller['lastname']) ?>
          — PARTNER
        </p>

        <div class="desktop-stats">
          <div class="dstat">
            <span class="dstat-value"><?= number_format((float)$seller['seller_rating'], 1) ?><span class="dstat-sub">/5.0</span></span>
            <span class="dstat-label">Rating</span>
          </div>
          <div class="dstat-divider"></div>
          <div class="dstat">
            <span class="dstat-value"><?= $totalSales ?></span>
            <span class="dstat-label">Sales</span>
          </div>
          <div class="dstat-divider"></div>
          <div class="dstat">
            <span class="dstat-value"><?= $totalProducts ?></span>
            <span class="dstat-label">Items</span>
          </div>
        </div>

        <div class="seller-cta">
          <button class="btn-follow full" onclick="toggleEditModal()">
            <i class="fa-solid fa-pen"></i> Edit Profile
          </button>
          <a href="logout.php">
            <button class="btn-message" title="Logout">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
          </a>
        </div>
      </div>

      <!-- Bio / Curator note -->
      <div class="card curator-card">
        <p class="curator-label">ABOUT THE STORE</p>
        <p class="curator-text">
          <?= !empty($seller['bio'])
              ? htmlspecialchars($seller['bio'])
              : 'Welcome to ' . htmlspecialchars($seller['store_name']) . '. Discover our curated selection of premium products.' ?>
        </p>
      </div>

    </aside>

    <!-- RIGHT COLUMN -->
    <section class="desktop-main">

      <div class="section-header">
        <h2 class="section-title">Curated Stock</h2>
        <span class="stock-count"><?= $totalProducts ?> ITEMS</span>
      </div>

      <?php if ($featured): ?>
      <!-- Featured card -->
      <div class="stock-card desktop-featured">
        <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($featured['product_image'] ?? 'placeholder.jpg') ?>"
             alt="<?= htmlspecialchars($featured['name_product']) ?>"
             class="stock-img"
             onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=900&h=380&fit=crop'" />
        <div class="stock-overlay">
          <span class="vault-tag"><?= htmlspecialchars($featured['name_categorie']) ?></span>
          <div class="stock-info">
            <div>
              <p class="stock-name"><?= htmlspecialchars($featured['name_product']) ?></p>
              <p class="stock-series"><?= htmlspecialchars($featured['description_product'] ?? '') ?></p>
            </div>
            <span class="stock-price">$<?= number_format((float)$featured['price'], 2) ?></span>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Product grid -->
      <?php if (!empty($gridItems)): ?>
      <div class="desktop-stock-grid">
        <?php foreach ($gridItems as $product): ?>
        <div class="stock-card desktop-mini">
          <img src="<?= BASE_URL ?>assets/images/products/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.jpg') ?>"
               alt="<?= htmlspecialchars($product['name_product']) ?>"
               class="stock-img"
               onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=340&fit=crop'" />
          <div class="stock-overlay mini-overlay">
            <?php if ($product['quantity_product'] == 0): ?>
              <span class="vault-tag small">OUT OF STOCK</span>
            <?php elseif ($product['quantity_product'] < 5): ?>
              <span class="vault-tag small">LOW STOCK</span>
            <?php endif; ?>
            <div class="stock-mini-info">
              <p class="stock-name-mini"><?= htmlspecialchars($product['name_product']) ?></p>
              <p class="stock-price-mini">$<?= number_format((float)$product['price'], 2) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php elseif (empty($products)): ?>
        <p style="text-align:center; color:var(--neutral); padding: 40px 0;">No products yet. <a href="add-product.php">Add your first product</a></p>
      <?php endif; ?>

      <div class="load-more-wrap">
        <a href="product-overview.php">
          <button class="btn-load-more">Manage All Products <i class="fa-solid fa-chevron-right"></i></button>
        </a>
      </div>

    </section>
  </div>

<!-- ── MODAL EDIT PROFIL SELLER ── -->
<div id="editModal" class="modal-overlay">
  <div class="modal-box card">
    <div class="modal-header">
      <h3>Edit Seller Profile</h3>
      <button class="modal-close" onclick="toggleEditModal()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="seller-profile.php" method="POST" class="modal-form" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_seller">
  <input type="file" id="sellerImg" name="sellerImg" accept="image/*" style="display: none;" /> 
      <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
        <div class="desktop-avatar-wrap" style="margin-top: 0;">
          <img src="<?= !empty($seller['profile_image']) ? BASE_URL . 'assets/images/' . htmlspecialchars($seller['profile_image']) : 'https://i.pravatar.cc/160?u=seller' . $sellerId ?>" 
               alt="Seller" id="sellerAvatar" 
               style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
          <div class="user-profile-edit" id="editAvatarBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16">
              <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
            </svg>
          </div>
        </div>
      </div>

      <label class="info-label">FIRST NAME</label>
      <input class="entrer" type="text" name="username" required value="<?= htmlspecialchars($seller['username']) ?>">

      <label class="info-label">LAST NAME</label>
      <input class="entrer" type="text" name="lastname" value="<?= htmlspecialchars($seller['lastname']) ?>">

      <label class="info-label">STORE NAME</label>
      <input class="entrer" type="text" name="store_name" required value="<?= htmlspecialchars($seller['store_name']) ?>">

      <label class="info-label">PHONE</label>
      <input class="entrer" type="tel" name="phone" value="<?= htmlspecialchars($seller['phone_number'] ?? '') ?>">

      <label class="info-label">BIO / STORE DESCRIPTION</label>
      <textarea class="entrer" name="bio" rows="4"><?= htmlspecialchars($seller['bio'] ?? '') ?></textarea>

      <button class="boutton" type="submit">Save Changes</button>
    </form>
  </div>
</div>

  <!-- Mobile bottom nav -->
  <nav class="bottom-nav">
    <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-grip"></i><span>HOME</span></a>
    <a href="seller-profile.php" class="nav-item active"><i class="fa-solid fa-store"></i><span></span></a>
    <a href="orders.php" class="nav-item"><i class="fa-solid fa-box"></i><span>ORDERS</span></a>
    <a href="product-overview.php" class="nav-item"><i class="fa-solid fa-chart-line"></i><span>STATS</span></a>
  </nav>
</body>
<script src="<?= BASE_URL ?>assets/js/pages/authentification/auth.js"></script>