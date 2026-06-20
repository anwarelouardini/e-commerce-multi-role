<?php 
  require_once __DIR__ . '/../../includes/config.php';
  require_once __DIR__ . '/../../includes/db.php';
  require_once __DIR__ . '/../../includes/functions.php';

  
  $id = isset($_GET['id']) ? e($_GET['id']) : '';

  if(empty($id)) {
    header("Location: " . BASE_URL . 'pages/admin/users.php');
    die();
  }

  $user = getUserById($pdo, $_GET['id']);
  $timestamp = strtotime($user['created_at']);

  if(!empty($_POST)) {
    $userId = e($_POST['id']);
    deleteUserById($pdo, $userId);

    header("Location: " . BASE_URL . 'pages/admin/users.php');
    die();
  }

  $headerTitle = 'Admin Delete User';
  $header = 'custom-nav';
  require_once __DIR__ . '/../../includes/header.php';

?>

  <header class="header container u-margin-top-med">
    <h1 class="heading-primary">
      Delete User Account
      <span class="sub-heading"
        >Review the user details before finalizing deletion</span
      >
    </h1>
  </header>

  <main class="main container">
    <div class="delete-card u-margin-top-small">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="60"
        height="60"
        fill="currentColor"
        class="bi bi-exclamation-diamond alert__icon"
        viewBox="0 0 16 16"
      >
        <path
          d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"
        />
        <path
          d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"
        />
      </svg>
      <div class="delete-card-content">
        <h3 class="heading-secondary heading-secondary--red">
          This action is permanent
        </h3>
        <p class="paragraph">
          Deleting this user will revoke all access immediately. All
          associated logs will be archived but the account connot be
          reoverred.
        </p>
      </div>
    </div>
    <div
      class="status-indicator status-indicator--red status-indicator--sm u-margin-top-med"
    >
      Target Identity
    </div>

    <div class="user-box u-margin-top-small">
      <div class="user-profile">
        <img
          class="user__img"
          src="<?= BASE_URL ?>assets/images/avatars/users1-icon.jpg"
          alt="Profile 1"
        />
        <h1 class="username username--big">
          <?= e(strtoupper($user['lastname'])) . ' ' . e(ucfirst($user['username'])) ?>
          <span id="user-email" class="sub-heading"><?= e($user['email']) ?></span>
        </h1>
      </div>
    </div>

    <div class="user-details-container">
      <div class="user-details-content">
        <h4 class="sub-heading">Assigned Role</h4>
        <p class="user-details__content"><?= e(ucfirst($user['role_name'])) ?></p>
      </div>
      <div class="user-details-content">
        <h4 class="sub-heading">Active Since</h4>
        <p class="user-details__content"><?= e(date('d/m/Y', $timestamp)) ?></p>
      </div>
    </div>
      <form class="form-btn-box" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button class="btn-secondary btn-secondary--big btn-secondary--red u-margin-top-med" type="submit">Delete Account</button>
        <button class="btn btn--txt" href="<?= BASE_URL ?>">Cancel and Go Back</button>
      </form>
  </main>
  </body>
</html>
