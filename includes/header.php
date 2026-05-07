<?php 
require_once __DIR__ . '/functions.php';
define('BASE_URL', '/e-commerce-platform/');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
    <title><?php echo isset($headerTitle) ? e($headerTitle) : "GAAM"; ?></title>
    <script defer src="<?= BASE_URL ?>assets/js/components/navbar.js"></script>
    <?php if(isset($pathJS)): ?>
    <script defer src="<?= BASE_URL . $pathJS ?>"></script>
    <?php endif; ?>
  </head>
  <body>
    <nav class="navigation">
      <div class="navigation-left">
        <div class="navigation__icon">&nbsp;</div>
        <div class="navigation__logo">
          <h1 id="navigation__logo">GAAM Admin</h1>
        </div>
      </div>

      <ul class="navigation__links">
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/admin/dashboard.php">Dashboard</a>
        </li>
        <li class="navigation__item">
          <a
            class="navigation__link navigation__link--active"
            href="<?= BASE_URL ?>pages/admin/sellers.php"
            >Sellers</a
          >
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/admin/users.php">Users</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/admin/settings.php">Settings</a>
        </li>
      </ul>

      <!-- Profile Card -->
      <div class="navigation-profile">
        <img
          class="navigation-profile__icon"
          src="<?= BASE_URL ?>assets/images/avatars/admin-icon.jpg"
          alt="Admin Profile"
        />
      </div>

      <div class="navigation-profile-card">
        <div class="user-profile-container user-profile-container--navigation">
          <div class="user-profile-img">
            <img
              class="user-profile__icon"
              src="<?= BASE_URL ?>assets/images/avatars/admin-icon.jpg"
              alt="Admin profile"
            />
          </div>
          <h2 class="username">Julianne Sterling</h2>
          <span class="sub-heading txt-center">Lead Platform Architect</span>
        </div>

        <hr class="navigation-profile-card__separator" />

        <ul class="profile-card__links">
          <li class="profile-card__item">
            <a class="profile-card__link" href="<?= BASE_URL ?>pages/admin/settings.php">
              <span class="profile-card__icon"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-people-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"
                  /></svg
              ></span>
              Account Settings
            </a>
          </li>
        </ul>

        <hr class="navigation-profile-card__separator" />

        <a class="profile-card__logout" href="#">
          <span>&#8594;</span>
          Log Out
        </a>
      </div>

      <!-- Navigation Mobile Menu -->
      <div class="navigation-mobile-menu">
        <ul class="navigation-mobile__list">
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/admin/dashboard.php"
              >Dashboard</a
            >
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/admin/sellers.php">Sellers</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/admin/users.php">Users</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/admin/settings.php"
              >Settings</a
            >
          </li>
        </ul>
      </div>
    </nav>