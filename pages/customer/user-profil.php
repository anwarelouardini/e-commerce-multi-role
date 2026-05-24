<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';


define('ROLE_ADMIN',    1);
define('ROLE_SELLER',   2);
define('ROLE_CUSTOMER', 3);

// Protéger la page : doit être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentification/login.php');
    exit;
}

// Rediriger les sellers vers leur page
if ((int)$_SESSION['role'] === ROLE_SELLER) {
    header('Location: ../vendor/seller-profile.php');
    exit;
}

$userId = (int)$_SESSION['user_id'];

// ── Récupérer les infos complètes de l'utilisateur ───────────
$stmt = $pdo->prepare("
    SELECT u.*, c.id_customer, c.address_customer
    FROM users u
    LEFT JOIN customers c ON c.id_user = u.id_user
    WHERE u.id_user = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// ── Récupérer les commandes récentes ─────────────────────────
$orders = [];
$totalOrders = 0;
$inTransit   = 0;
$savedItems  = 0;

if (!empty($user['id_customer'])) {
    $customerId = $user['id_customer'];

    $stmt = $pdo->prepare("
        SELECT o.id_order, o.date_order, o.order_status,
               oi.quantity_order_items,
               p.name_product, p.product_image, p.price
        FROM orders o
        JOIN orders_items oi ON oi.id_order = o.id_order
        JOIN products p      ON p.id_product = oi.id_product
        WHERE o.id_customer = ?
        ORDER BY o.date_order DESC
        LIMIT 5
    ");
    $stmt->execute([$customerId]);
    $orders = $stmt->fetchAll();

    // Stats
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE id_customer = ?");
    $stmt2->execute([$customerId]);
    $totalOrders = (int)$stmt2->fetchColumn();

    $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE id_customer = ? AND order_status = 'shipped'");
    $stmt3->execute([$customerId]);
    $inTransit = (int)$stmt3->fetchColumn();
}

// ── Traitement mise à jour du profil ─────────────────────────
$updateMsg = '';
$updateErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $newUsername = trim($_POST['username']  ?? '');
    $newLastname = trim($_POST['lastname']  ?? '');
    $newPhone    = trim($_POST['phone']     ?? '');
    $newBio      = trim($_POST['bio']       ?? '');
    $newAddress  = trim($_POST['address']   ?? '');

    if (empty($newUsername) || empty($newLastname)) {
        $updateErr = 'Le prénom et le nom sont requis.';
    } else {
        try {
            $pdo->prepare("
                UPDATE users SET username = ?, lastname = ?, phone_number = ?, bio = ?
                WHERE id_user = ?
            ")->execute([$newUsername, $newLastname, $newPhone, $newBio, $userId]);

            if (!empty($user['id_customer'])) {
                $pdo->prepare("UPDATE customers SET address_customer = ? WHERE id_user = ?")
                    ->execute([$newAddress, $userId]);
            }

            $_SESSION['username'] = $newUsername;
            $_SESSION['lastname'] = $newLastname;
            $updateMsg = 'Profil mis à jour avec succès !';

            // Rafraîchir les données
            $stmt = $pdo->prepare("SELECT u.*, c.id_customer, c.address_customer FROM users u LEFT JOIN customers c ON c.id_user = u.id_user WHERE u.id_user = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

        } catch (PDOException $e) {
            $updateErr = 'Erreur lors de la mise à jour.';
        }
    }
}

// Helpers
function statusClass(string $status): string {
    return match($status) {
        'shipped'   => '',
        'delivered' => 'delivered',
        'pending'   => 'pending',
        default     => '',
    };
}
function statusLabel(string $status): string {
    return match($status) {
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'shipped'    => 'In Transit',
        'delivered'  => 'Delivered',
        'cancelled'  => 'Cancelled',
        default      => ucfirst($status),
    };
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile — <?= htmlspecialchars($user['username'] . ' ' . $user['lastname']) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

  <!-- ── TOP NAVBAR ── -->
  <nav class="navbar">
    <div class="navbar-inner">
      <a href="#" class="nav-logo">
        <i class="fa-solid fa-gem"></i>
        <span>GAAM</span>
      </a>
      <div class="nav-links">
        <a href="#" class="nav-link"><i class="fa-solid fa-house"></i> Home</a>
        <a href="#" class="nav-link"><i class="fa-solid fa-magnifying-glass"></i> Search</a>
        <a href="#" class="nav-link"><i class="fa-solid fa-box"></i> Orders</a>
        <a href="user-profil.php" class="nav-link active"><i class="fa-solid fa-user"></i> Profile</a>
      </div>
    </div>
  </nav>

  <!-- ── PAGE LAYOUT ── -->
  <main class="page-layout">

    <!-- ── LEFT SIDEBAR ── -->
    <aside class="sidebar">

      <div class="sidebar-identity">
        <div class="avatar-wrap">
          <?php if (!empty($user['profile_image'])): ?>
            <img src="../../assets/images/<?= htmlspecialchars($user['profile_image']) ?>" alt="Avatar" class="avatar-img" />
          <?php else: ?>
          <img src="<?= BASE_URL ?>assets/images/avatars/emptyUserImg.png" alt="Avatar" class="avatar-img" />
          <?php endif; ?>
          <span class="badge-premium">
            <i class="fa-solid fa-crown"></i>
            <span>MEMBER</span>
          </span>
        </div>
        <h1 class="user-name"><?= htmlspecialchars($user['username'] . ' ' . $user['lastname']) ?></h1>
        <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
        <button class="btn-edit" onclick="toggleEditModal()">
          <i class="fa-solid fa-pen"></i> Edit Profile
        </button>
      </div>

      <!-- Settings nav -->
      <div class="card sidebar-settings">
        <p class="settings-label">ACCOUNT SETTINGS</p>
        <div class="settings-list">
          <div class="settings-item active-setting">
            <div class="settings-icon"><i class="fa-solid fa-user"></i></div>
            <span class="settings-text">Profile</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
          </div>
          <div class="settings-item">
            <div class="settings-icon"><i class="fa-solid fa-truck"></i></div>
            <span class="settings-text">Shipping Addresses</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
          </div>
          <div class="settings-item">
            <div class="settings-icon"><i class="fa-regular fa-credit-card"></i></div>
            <span class="settings-text">Payment Methods</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
          </div>
          <div class="settings-item">
            <div class="settings-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <span class="settings-text">Security &amp; Privacy</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
          </div>
          <div class="settings-item last">
            <div class="settings-icon"><i class="fa-regular fa-bell"></i></div>
            <span class="settings-text">Notification Prefs</span>
            <i class="fa-solid fa-chevron-right chevron"></i>
          </div>
        </div>
      </div>

      <!-- Logout -->
      <div class="logout-wrap">
        <a href="logout.php">
          <button class="btn-logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
          </button>
        </a>
      </div>

    </aside>

    <!-- ── MAIN CONTENT ── -->
    <section class="main-content">

      <div class="content-header">
        <h2 class="content-title">My Profile</h2>
        <span class="content-subtitle">Manage your account and preferences</span>
      </div>

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

      <!-- Stats row -->
      <div class="stats-row">
        <div class="stat-card card">
          <div class="stat-icon"><i class="fa-solid fa-box-open"></i></div>
          <div class="stat-info">
            <span class="stat-value"><?= $totalOrders ?></span>
            <span class="stat-label">Total Orders</span>
          </div>
        </div>
        <div class="stat-card card">
          <div class="stat-icon green"><i class="fa-solid fa-truck"></i></div>
          <div class="stat-info">
            <span class="stat-value"><?= $inTransit ?></span>
            <span class="stat-label">In Transit</span>
          </div>
        </div>
        <div class="stat-card card">
          <div class="stat-icon yellow"><i class="fa-solid fa-crown"></i></div>
          <div class="stat-info">
            <span class="stat-value">Member</span>
            <span class="stat-label">Current Tier</span>
          </div>
        </div>
        <div class="stat-card card">
          <div class="stat-icon purple"><i class="fa-solid fa-heart"></i></div>
          <div class="stat-info">
            <span class="stat-value"><?= $savedItems ?></span>
            <span class="stat-label">Saved Items</span>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="content-section">
        <div class="section-header">
          <h3 class="section-title">Recent Orders</h3>
          <a href="#" class="view-all">View All <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="orders-table card">
          <div class="orders-table-head">
            <span>Product</span>
            <span>Order ID</span>
            <span>Date</span>
            <span>Status</span>
            <span>Total</span>
          </div>

          <?php if (empty($orders)): ?>
            <div class="orders-table-row last" style="justify-content:center; color:var(--neutral); font-size:13px;">
              No orders yet.
            </div>
          <?php else: ?>
            <?php foreach ($orders as $i => $order):
                $isLast = ($i === count($orders) - 1);
                $sc = statusClass($order['order_status']);
            ?>
            <div class="orders-table-row <?= $isLast ? 'last' : '' ?>">
              <div class="order-product">
                <div class="order-img-wrap">
                  <img src="../../assets/images/<?= htmlspecialchars($order['product_image'] ?? 'placeholder.jpg') ?>"
                       alt="<?= htmlspecialchars($order['name_product']) ?>"
                       class="order-img"
                       onerror="this.src='https://via.placeholder.com/80x80?text=IMG'" />
                </div>
                <div>
                  <p class="order-name"><?= htmlspecialchars($order['name_product']) ?></p>
                  <p class="order-variant">Qty: <?= (int)$order['quantity_order_items'] ?></p>
                </div>
              </div>
              <span class="order-id">#<?= str_pad($order['id_order'], 4, '0', STR_PAD_LEFT) ?></span>
              <span class="order-date"><?= date('M j, Y', strtotime($order['date_order'])) ?></span>
              <span class="order-status <?= $sc ?>">
                <span class="status-dot <?= $sc ?>"></span>
                <?= statusLabel($order['order_status']) ?>
              </span>
              <span class="order-price">$<?= number_format($order['price'] * $order['quantity_order_items'], 2) ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- Personal Info -->
      <div class="content-section">
        <div class="section-header">
          <h3 class="section-title">Personal Information</h3>
          <button class="btn-ghost" onclick="toggleEditModal()">
            <i class="fa-solid fa-pen"></i> Edit
          </button>
        </div>

        <div class="card info-grid">
          <div class="info-field">
            <label class="info-label">First Name</label>
            <p class="info-value"><?= htmlspecialchars($user['username']) ?></p>
          </div>
          <div class="info-field">
            <label class="info-label">Last Name</label>
            <p class="info-value"><?= htmlspecialchars($user['lastname']) ?></p>
          </div>
          <div class="info-field">
            <label class="info-label">Email</label>
            <p class="info-value"><?= htmlspecialchars($user['email']) ?></p>
          </div>
          <div class="info-field">
            <label class="info-label">Phone</label>
            <p class="info-value"><?= htmlspecialchars($user['phone_number'] ?? '—') ?></p>
          </div>
          <div class="info-field">
            <label class="info-label">Address</label>
            <p class="info-value"><?= htmlspecialchars($user['address_customer'] ?? '—') ?></p>
          </div>
          <div class="info-field">
            <label class="info-label">Member Since</label>
            <p class="info-value"><?= date('F Y', strtotime($user['created_at'])) ?></p>
          </div>
        </div>
      </div>

    </section>
  </main>

  <!-- ── MODAL EDIT PROFIL ── -->
  <div id="editModal" class="modal-overlay">
    <div class="modal-box card">
      <div class="modal-header">
        <h3>Edit Profile</h3>
        <button class="modal-close" onclick="toggleEditModal()"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form action="user-profil.php" method="POST" class="modal-form">
        <input type="hidden" name="action" value="update_profile">

        <label class="info-label">FIRST NAME</label>
        <input class="entrer" type="text" name="username" required
               value="<?= htmlspecialchars($user['username']) ?>">

        <label class="info-label">LAST NAME</label>
        <input class="entrer" type="text" name="lastname" required
               value="<?= htmlspecialchars($user['lastname']) ?>">

        <label class="info-label">PHONE</label>
        <input class="entrer" type="tel" name="phone"
               value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">

        <label class="info-label">ADDRESS</label>
        <input class="entrer" type="text" name="address"
               value="<?= htmlspecialchars($user['address_customer'] ?? '') ?>">

        <label class="info-label">BIO</label>
        <textarea class="entrer" name="bio" rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

        <button class="boutton" type="submit">Save Changes</button>
      </form>
    </div>
  </div>

  <script src="<?= BASE_URL ?>assets/js/pages/authentification/auth.js"></script>
</body>
</html>