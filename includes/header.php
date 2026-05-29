<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';

$defaultImg = BASE_URL . 'assets/images/avatars/emptyUserImg.png';

if (!empty($_SESSION['profile_image'])) {
    $role = (int)$_SESSION['role'];
    // Admin
    if ($role === 1) { 
        $profileImg = BASE_URL . 'assets/images/users/admin/' . e($_SESSION['profile_image']);
    
    // Seller
    } elseif ($role === 2) { 
        $profileImg = BASE_URL . 'assets/images/users/vendor/' . e($_SESSION['profile_image']);
    // Customer
    } else { 
        $profileImg = BASE_URL . 'assets/images/users/' . e($_SESSION['profile_image']);
    }
} else {
    $profileImg = $defaultImg;
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <?php if($header === 'customer-nav'): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <?php endif; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
    <title><?php echo isset($headerTitle) ? e($headerTitle) : "GAAM"; ?></title>
    <script defer src="<?= BASE_URL ?>assets/js/components/navbar.js"></script>
    <?php if(!isset($disableFormJS)): ?>
    <script defer src="<?= BASE_URL ?>assets/js/components/form.js"></script>
    <?php endif; ?>
    <?php if(isset($pathJS)): ?>
    <script defer src="<?= BASE_URL . $pathJS ?>"></script>
    <?php endif; ?>

    <?php if(isset($pathJSModule)): ?>
    <script type="module" defer src="<?= BASE_URL . $pathJSModule ?>"></script>
    <?php endif; ?>

    <?php if($headerTitle === 'Admin Dashboard' || $headerTitle === 'GAAM Seller'): ?>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>

    <?php if($headerTitle === 'Admin Dashboard'): ?>
      <script defer src="<?= BASE_URL . 'assets/js/pages/admin/dashboard.js' ?>"></script>
    <?php endif; ?>
  </head>
  <body>
  <?php if(isset($header) && $header === 'standard-nav'): ?>
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
            class="navigation__link"
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
          src="<?= e($profileImg) ?>"
          alt="Admin Profile"
        />
      </div>

      <div class="navigation-profile-card">
        <div class="user-profile-container user-profile-container--navigation">
          <div class="user-profile-img">
            <img
              class="user-profile__icon"
              src="<?= e($profileImg) ?>"
              alt="Admin profile"
            />
          </div>
          <h2 class="username"><?= e($_SESSION['username'] ?? '') ?> <?= e($_SESSION['lastname'] ?? '') ?></h2>
          <span class="sub-heading txt-center"><?= e(ucfirst($_SESSION['role_name']) ?? '') ?></span>
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

        <a class="profile-card__logout" href="<?= BASE_URL ?>pages/authentification/logout.php">
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
  
  <div class="navigation-card container">
    <nav class="navigation-mobile">
      <a href="<?= BASE_URL ?>pages/admin/dashboard.php" class="nav-item nav-item--active">
        <!-- Home Icon -->
        <svg
          class="navigation-mobile__icon"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-house"
          viewBox="0 0 16 16"
        >
          <path
            d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"
          />
        </svg>
        <span class="navigation-mobile__title">Home</span>
      </a>
      <a href="<?= BASE_URL ?>pages/admin/users.php" class="nav-item">
        <!-- Users Icon -->
        <svg
          class="navigation-mobile__icon"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-people"
          viewBox="0 0 16 16"
        >
          <path
            d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"
          />
        </svg>
        <span class="navigation-mobile__title">Users</span>
      </a>
      <a href="<?= BASE_URL ?>pages/admin/sellers.php" class="nav-item">
        <!-- Sellers Icon -->
        <svg
          class="navigation-mobile__icon"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-shop-window"
          viewBox="0 0 16 16"
        >
          <path
            d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"
          />
        </svg>
        <span class="navigation-mobile__title">Sellers</span>
      </a>
      <a href="<?= BASE_URL ?>pages/admin/settings.php" class="nav-item">
        <!-- Settings Icon -->
        <svg
          class="navigation-mobile__icon"
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-gear"
          viewBox="0 0 16 16"
        >
          <path
            d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"
          />
          <path
            d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"
          />
        </svg>
        <span class="navigation-mobile__title">Settings</span>
      </a>
    </nav>
  </div>
  <script>
    const BASE_URL = "<?= BASE_URL ?>";
  </script>
<?php elseif(isset($header) && $header === 'custom-nav'): ?>
    <nav class="navigation">
      <div class="navigation-left">
        <a class="navigation__backwards" href="<?= $backLink ?? '#' ?>">&lsaquo;</a>
        <div class="navigation__logo">
          <h1 id="navigation__logo"><?= e($headerTitle) ?></h1>
        </div>
      </div>

      <div class="navigation-profile">
        <img
          class="navigation-profile__icon"
          src="<?= e($profileImg) ?>"
          alt="Admin Profile"
        />
      </div>
    </nav>
    <script>const BASE_URL = "<?= BASE_URL ?>"</script>


    <?php elseif(isset($header) && $header === 'vendor-nav'): ?>
    <nav class="navigation">
      <div class="navigation-left">
        <div class="navigation__icon">&nbsp;</div>
        <div class="navigation__logo">
          <h1 id="navigation__logo"><?php echo isset($headerTitle) ? e($headerTitle) : "GAAM"; ?></h1>
        </div>
      </div>

      <ul class="navigation__links">
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/vendor/dashboard.php">Home</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/vendor/product-overview.php">Products</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/vendor/add-product.php">Add Products</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/vendor/orders.php">Orders</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/vendor/seller-profile.php">Profile</a>
        </li>
      </ul>

      <div class="navigation-profile">
        <img class="navigation-profile__icon" src="<?= e($profileImg) ?>" alt="Vendor Profile"/>
      </div>

      <div class="navigation-profile-card">
        <div class="user-profile-container user-profile-container--navigation">
          <div class="user-profile-img">
            <img class="user-profile__icon" src="<?= e($profileImg) ?>"/>
          </div>
          <h2 class="username"><?= e($_SESSION['username'] ?? '') ?> <?= e($_SESSION['lastname'] ?? '') ?></h2>
          <span class="sub-heading txt-center"><?= e(ucfirst($_SESSION['role_name']) ?? '') ?></span>
        </div>
        <hr class="navigation-profile-card__separator" />
        <ul class="profile-card__links">
          <li class="profile-card__item">
            <a class="profile-card__link" href="<?= BASE_URL ?>pages/vendor/seller-profile.php">
              <span class="profile-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/></svg></span>
              Account Settings
            </a>
          </li>
        </ul>
        <hr class="navigation-profile-card__separator" />
        <a class="profile-card__logout" href="<?= BASE_URL ?>pages/vendor/logout.php"><span>&#8594;</span> Log Out</a>
      </div>

      <div class="navigation-mobile-menu">
        <ul class="navigation-mobile__list">
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/vendor/dashboard.php">Dashboard</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/vendor/product-overview.php">Products</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/vendor/add-product.php">Add Products</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/vendor/orders.php">Orders</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="navigation-card container">
      <nav class="navigation-mobile">
        <a href="<?= BASE_URL ?>pages/vendor/dashboard.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
          </svg>
          <span class="navigation-mobile__title">Home</span>
        </a>
        <a href="<?= BASE_URL ?>pages/vendor/product-overview.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z"/>
          </svg>
          <span class="navigation-mobile__title">Products</span>
        </a>
        <a href="<?= BASE_URL ?>pages/vendor/add-product.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
          </svg>
          <span class="navigation-mobile__title">Add Product</span>
        </a>
        <a href="<?= BASE_URL ?>pages/vendor/orders.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.468 15.426.943 9h14.114l-1.525 6.426a.75.75 0 0 1-.729.574H3.197a.75.75 0 0 1-.73-.574z"/>
          </svg>
          <span class="navigation-mobile__title">Orders</span>
        </a>
        <a href="<?= BASE_URL ?>pages/vendor/seller-profile.php" class="nav-item nav-item--small nav-item--active">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg>
        <span class="navigation-mobile__title navigation-mobile__title--active">Profile</span>
      </a>
      </nav>
    </div>
    <script>const BASE_URL = "<?= BASE_URL ?>";</script>

  <?php elseif(isset($header) && $header === 'customer-nav'): ?>
        <nav class="navigation">
      <div class="navigation-left">
        <div class="navigation__icon">&nbsp;</div>
        <div class="navigation__logo">
          <h1 id="navigation__logo">GAAM</h1>
        </div>
      </div>

      <ul class="navigation__links">
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/public/home.php">Home</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/customer/orders.php">Orders</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/public/cart.php">Cart</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/public/history.php">History</a>
        </li>
        <li class="navigation__item">
          <a class="navigation__link" href="<?= BASE_URL ?>pages/customer/user-profil.php">Profil</a>
        </li>
      </ul>

      <div class="navigation-profile">
        <img class="navigation-profile__icon" src="<?= e($profileImg) ?>" alt="Vendor Profile"/>
      </div>

      <div class="navigation-profile-card">
        <div class="user-profile-container user-profile-container--navigation">
          <div class="user-profile-img">
            <img class="user-profile__icon" src="<?= e($profileImg) ?>"/>
          </div>
          <h2 class="username"><?= e($_SESSION['username'] ?? '') ?> <?= e($_SESSION['lastname'] ?? '') ?></h2>
          <span class="sub-heading txt-center"><?= e(ucfirst($_SESSION['role_name']) ?? '') ?></span>
        </div>
        <hr class="navigation-profile-card__separator" />
        <ul class="profile-card__links">
          <li class="profile-card__item">
            <a class="profile-card__link" href="<?= BASE_URL ?>pages/vendor/seller-profile.php">
              <span class="profile-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/></svg></span>
              Account Settings
            </a>
          </li>
        </ul>
        <hr class="navigation-profile-card__separator" />
        <a class="profile-card__logout" href="<?= BASE_URL ?>pages/vendor/logout.php"><span>&#8594;</span> Log Out</a>
      </div>

      <div class="navigation-mobile-menu">
        <ul class="navigation-mobile__list">
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/public/home.php">Home</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/customer/orders.php">Orders</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/public/cart.php">Cart</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/public/history.php">History</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/customer/user-profil.php">Profile</a>
          </li>
        </ul>
      </div>
      <div class="navigation-mobile-menu">
        <ul class="navigation-mobile__list">
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/public/home.php">Home</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/customer/orders.php">Orders</a>
          </li>
          <li class="navigation-mobile__item">
            <a class="navigation-mobile__link" href="<?= BASE_URL ?>pages/customer/user-profil.php">Profile</a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="navigation-card container">
      <nav class="navigation-mobile">
        <a href="<?= BASE_URL ?>pages/public/home.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
          </svg>
          <span class="navigation-mobile__title">Home</span>
        </a>
        <a href="<?= BASE_URL ?>pages/customer/orders.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.468 15.426.943 9h14.114l-1.525 6.426a.75.75 0 0 1-.729.574H3.197a.75.75 0 0 1-.73-.574z"/>
          </svg>
          <span class="navigation-mobile__title">Orders</span>
        </a>
        <a href="<?= BASE_URL ?>pages/public/cart.php" class="nav-item nav-item--small">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-fill" viewBox="0 0 16 16">
          <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4z"/>
        </svg>
          <span class="navigation-mobile__title">Cart</span>
        <a href="<?= BASE_URL ?>pages/public/history.php" class="nav-item nav-item--small">
          <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
          </svg>
          <span class="navigation-mobile__title">History</span>
        </a>
        <a href="<?= BASE_URL ?>pages/customer/user-profil.php" class="nav-item nav-item--small nav-item--active">
        <svg class="navigation-mobile__icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg>
        <span class="navigation-mobile__title navigation-mobile__title--active">Profile</span>
      </a>
      </nav>
    </div>
    <script>const BASE_URL = "<?= BASE_URL ?>";</script>

<?php endif; ?>
