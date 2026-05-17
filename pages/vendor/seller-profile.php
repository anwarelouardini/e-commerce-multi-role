<?php
// ============================================================
//  seller-profile.php — Profil vendeur
// ============================================================
session_start();
require_once '../../includes/db.php';


define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);
// Protéger la page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentification/login.php');
    exit;
}
if ((int)$_SESSION['role'] !== ROLE_SELLER) {
    header('Location: ../customer/user-profil.php');
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
    header('Location: login.php');
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

// Première et dernière ligne des produits pour l'affichage
$featured   = $products[0]  ?? null;
$gridItems  = array_slice($products, 1, 4);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seller Profile — <?= htmlspecialchars($seller['store_name']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
  #editModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
  }
  #editModal.modal--open {
    display: flex !important;
  }
  .modal-box {
  padding: 32px !important;
  width: min(90%, 500px) !important;
  border-radius: 16px !important;
}

.modal-header {
  display: flex !important;
  justify-content: space-between !important;
  align-items: center !important;
  margin-bottom: 24px !important;
}

.modal-form {
  display: flex !important;
  flex-direction: column !important;
  gap: 8px !important;
}
</style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navigation">
    <div class="navigation-left">
      <div class="navigation__icon">&nbsp;</div>
      <div class="navigation__logo">
        <h1 id="navigation__logo">GAAM Admin</h1>
      </div>
    </div>
    <ul class="navigation__links">
      <li class="navigation__item"><a class="navigation__link" href="./dashboard.php">Home</a></li>
      <li class="navigation__item navigation__link--active"><a class="navigation__link" href="./product-overview.php">Products</a></li>
      <li class="navigation__item"><a class="navigation__link" href="./add-product.php">Add Products</a></li>
      <li class="navigation__item"><a class="navigation__link" href="./orders.php">Orders</a></li>
      <li class="navigation__item"><a class="navigation__link" href="./seller-profile.php">Profile</a></li>
    </ul>
    <div class="navigation-profile">
      <?php if (!empty($seller['profile_image'])): ?>
        <img class="navigation-profile__icon" src="../../assets/images/<?= htmlspecialchars($seller['profile_image']) ?>" alt="Profile" />
      <?php else: ?>
        <img class="navigation-profile__icon" src="https://i.pravatar.cc/80?u=<?= $userId ?>" alt="Profile" />
      <?php endif; ?>
    </div>
  </nav>

  <!-- Mobile nav -->
  <div class="navigation-card container">
    <nav class="navigation-mobile">
      <a href="./dashboard.php" class="nav-item nav-item--small">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
        </svg>
        <span class="navigation-mobile__title">Home</span>
      </a>
      <a href="./product-overview.php" class="nav-item nav-item--small">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z"/>
        </svg>
        <span class="navigation-mobile__title">Products</span>
      </a>
      <a href="./add-product.php" class="nav-item nav-item--small">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
        </svg>
        <span class="navigation-mobile__title">Add Product</span>
      </a>
      <a href="./orders.php" class="nav-item nav-item--small">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.468 15.426.943 9h14.114l-1.525 6.426a.75.75 0 0 1-.729.574H3.197a.75.75 0 0 1-.73-.574z"/>
        </svg>
        <span class="navigation-mobile__title">Orders</span>
      </a>
      <a href="#" class="nav-item nav-item--small nav-item--active">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg>
        <span class="navigation-mobile__title navigation-mobile__title--active">Profile</span>
      </a>
    </nav>
  </div>

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
            <img src="../../assets/images/<?= htmlspecialchars($seller['profile_image']) ?>" alt="Seller" class="desktop-avatar" />
          <?php else: ?>
            <img src="https://i.pravatar.cc/160?u=seller<?= $sellerId ?>" alt="Seller" class="desktop-avatar" />
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
        <img src="../../assets/images/<?= htmlspecialchars($featured['product_image'] ?? 'placeholder.jpg') ?>"
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
          <img src="../../assets/images/<?= htmlspecialchars($product['product_image'] ?? 'placeholder.jpg') ?>"
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
      <form action="seller-profile.php" method="POST" class="modal-form">
        <input type="hidden" name="action" value="update_seller">

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

  <script src="../authentification/auth.js"></script>
</body>
</html>