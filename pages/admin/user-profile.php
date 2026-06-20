<?php
session_start();
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['user_id']) || (int)$_SESSION['role'] !== 1) {
    header('Location: ' . BASE_URL . 'pages/authentification/login.php');
    exit;
}

$userId = (int)($_GET['id'] ?? 0);
if (!$userId) {
    header('Location: ' . BASE_URL . 'pages/admin/users.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT u.*, r.role_name, c.id_customer, c.address_customer
    FROM users u
    JOIN roles r ON r.id_role = u.id_role
    LEFT JOIN customers c ON c.id_user = u.id_user
    WHERE u.id_user = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: ' . BASE_URL . 'pages/admin/users.php');
    exit;
}


$header = 'custom-nav';
$headerTitle = 'Admin Users';
$backLink = BASE_URL . 'pages/admin/users.php';
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="page-layout container u-margin-top-med">

  <aside class="sidebar">
    <div class="sidebar-identity">
      <div class="avatar-wrap">
        <?php if (!empty($user['profile_image'])): ?>
          <img src="<?= BASE_URL ?>assets/images/users/<?= strtolower($user['role_name']) ?>/<?= e($user['profile_image']) ?>" alt="Avatar" class="avatar-img" />
        <?php else: ?>
          <img src="<?= BASE_URL ?>assets/images/avatars/emptyUserImg.png" alt="Avatar" class="avatar-img" />
        <?php endif; ?>
      </div>
      <h1 class="user-name"><?= htmlspecialchars($user['username'] . ' ' . $user['lastname']) ?></h1>
      <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
      <div class="wrap">
          <div class="status-indicator status-indicator--<?= e(getRoleClass($user['role_name'])) ?> u-margin-top-small">
            <?= e($user['role_name']) ?>
          </div>
          <div class="status status--<?= e(getStatusClass($user['status'])) ?> u-margin-top-small">
            <div class="status__cercle">&nbsp;</div>
            <p class="sub-heading"><?= e(ucfirst($user['status'])) ?></p>
          </div>
      </div>
    </div>
  </aside>

  <section class="main-content">
    <div class="content-header">
      <h2 class="content-title"><?= htmlspecialchars($user['username'] . ' ' . $user['lastname']) ?></h2>
      <span class="content-subtitle">Member since <?= date('F Y', strtotime($user['created_at'])) ?></span>
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
        <label class="info-label">Role</label>
        <p class="info-value"><?= htmlspecialchars($user['role_name']) ?></p>
      </div>
      <div class="info-field">
        <label class="info-label">Status</label>
        <p class="info-value"><?= htmlspecialchars(ucfirst($user['status'])) ?></p>
      </div>
      <div class="info-field">
        <label class="info-label">Address</label>
        <p class="info-value"><?= htmlspecialchars($user['address_customer'] ?? '—') ?></p>
      </div>
      <div class="info-field">
        <label class="info-label">Bio</label>
        <p class="info-value"><?= htmlspecialchars($user['bio'] ?? '—') ?></p>
      </div>
    </div>
  </section>

</main>
</body>
</html>