<?php 
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$adminId = 49; 

$admin = getUserById($pdo, $adminId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $password = $_POST['password'];

    // 1. gérer l'image d'abord
    if($_FILES['adminImg']['error'] === 0 && $_FILES['adminImg']['size'] !== 0) {
        $extension = pathinfo($_FILES['adminImg']['name'], PATHINFO_EXTENSION);
        $filename = 'admin_' . $adminId . '.' . $extension;
        move_uploaded_file($_FILES['adminImg']['tmp_name'], __DIR__ . '/../../assets/images/users/admin/' . $filename);
    } else {
        $filename = null;
    }

    // 2. vérifier le mot de passe puis update
    if (verifyAdminPassword($pdo, $adminId, $password)) {
        updateAdminSettings($pdo, $adminId, $username, $lastname, $email, $bio, $filename);
        $success = "Settings updated successfully.";
        $admin = getUserById($pdo, $adminId);
    } else {
        $error = "Incorrect password.";
    }
}

$pathJSModule = 'assets/js/pages/admin/settings.js';
$headerTitle = 'Admin Settings';
$header = 'standard-nav';
require __DIR__ . '/../../includes/header.php';
?>
    <main>
      <div class="user-profile-container u-margin-top-med">
        <div class="user-profile-container">
        <div class="user-profile-img">
          <img
            class="user-profile__icon"
            src="<?= BASE_URL ?>assets/images/users/admin/<?= e($admin['profile_image']) ?>"
            alt="Admin profile"
          />
          <div class="user-profile-edit">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="11"
              height="11"
              fill="currentColor"
              class="bi bi-pencil-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"
              />
            </svg>
          </div>
        </div>
          <h2 class="heading-primary heading-primary--med">
            <?= e($admin['username']) ?> <?= e($admin['lastname']) ?>
            <span class="sub-heading txt-center">Master control</span>
          </h2>
        </div>

        <?php if (!empty($success)): ?>
          <div class="alert alert--success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
          <div class="alert alert--error"><?= $error ?></div>
        <?php endif; ?>

        <form class="form form-settings u-margin-top-med" action="settings.php" method="POST" enctype="multipart/form-data">
          <div class="grid-container--2">
            <div class="form-box">
              <label class="heading-secondary" for="username">Username</label>
              <input
                class="form-input"
                type="text"
                id="username"
                name="username"
                value="<?= e($admin['username']) ?>"
                placeholder="Username"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="lastname">Last Name</label>
              <input
                class="form-input"
                type="text"
                id="lastname"
                name="lastname"
                value="<?= e($admin['lastname']) ?>"
                placeholder="Last Name"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="email">Email Address</label>
              <input
                class="form-input"
                type="email"
                id="email"
                name="email"
                value="<?= e($admin['email']) ?>"
                placeholder="example@mail.com"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="password">Confirm Password</label>
              <input
                class="form-input"
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password to confirm"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="bio">Professional Bio</label>
              <textarea
                class="form-input"
                name="bio"
                id="bio"
                placeholder="Describe yourself..."
              ><?= e($admin['bio']) ?></textarea>
            </div>
          </div>

          <div class="form-btn-box">
            <button class="btn btn-primary" type="submit">Save Changes</button>
            <button class="btn btn-primary btn-primary--purple" type="reset">
              Discard Changes
            </button>
          </div>
          <input type="file" id="adminImg" name="adminImg" accept="image/*" style="display: none;" />
        </form>
      </div>
    </main>
  </body>
</html>