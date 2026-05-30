<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/config.php';

http_response_code(404);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 — Page Not Found | GAAM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/pages/public.css" />

    <style>
      .err-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-page);
      }

      .err-number {
        font-size: clamp(10rem, 22vw, 18rem);
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.04em;
        background: linear-gradient(135deg, var(--primary) 0%, #5c6bc0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        user-select: none;
        margin-bottom: 1.6rem;
      }

      .err-divider {
        width: 6rem;
        height: 3px;
        background: var(--primary);
        border-radius: 10rem;
        margin: 0 auto 3.2rem;
        opacity: 0.3;
      }

      .err-desc {
        max-width: 44rem;
        margin: 0 auto 4rem;
      }

      .err-actions {
        display: flex;
        gap: 1.2rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 6rem;
      }

      .err-links {
        display: flex;
        gap: 2.4rem;
        justify-content: center;
        flex-wrap: wrap;
      }

      .err-links a {
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--neutral);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        transition: color 0.2s;
      }

      .err-links a:hover { color: var(--primary); }

      .err-logo {
        position: fixed;
        top: 2.8rem;
        left: 50%;
        transform: translateX(-50%);
        font-size: 2rem;
        font-weight: 900;
        color: var(--primary);
        text-decoration: none;
        letter-spacing: 0.05em;
      }
    </style>
  </head>

  <body>
    <a href="<?= BASE_URL ?>" class="err-logo">GAAM</a>

    <div class="err-page">
      <p class="err-number">404</p>
      <div class="err-divider"></div>

      <h1 class="heading-primary">Page Not Found</h1>
      <p class="sub-heading err-desc">
        The page you're looking for has moved, been removed, or never existed.
        Let's get you back on track.
      </p>

      <div class="err-actions">
        <a href="<?= BASE_URL ?>pages/public/home.php" class="pub-btn pub-btn--primary">
          <i class="fa-solid fa-house"></i> Back to Home
        </a>
        <a href="<?= BASE_URL ?>pages/public/product-catalog.php" class="pub-btn pub-btn--ghost">
          <i class="fa-solid fa-bag-shopping"></i> Browse Catalog
        </a>
      </div>

      <nav class="err-links">
        <a href="<?= BASE_URL ?>pages/public/category-page.php">
          <i class="fa-solid fa-grid-2"></i> Categories
        </a>
        <a href="<?= BASE_URL ?>pages/authentification/login.php">
          <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
        </a>
        <a href="<?= BASE_URL ?>pages/public/cart.php">
          <i class="fa-solid fa-cart-shopping"></i> Cart
        </a>
      </nav>
    </div>
  </body>
</html>