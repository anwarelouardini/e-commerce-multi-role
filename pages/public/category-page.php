<?php
// ─── pages/public/category-page.php ─────────────────────────────────────────
// Page catégories — utilise la DB du projet principal

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$headerTitle = 'GAAM — Categories';
$header      = 'customer-nav';

// ── Catégories depuis DB ──────────────────────────────────────────────────────
$categories_raw = getCategories($pdo);

// Compter les produits par catégorie
$cat_counts = [];
$count_stmt = $pdo->query("
    SELECT id_categorie, COUNT(*) as cnt
    FROM products
    GROUP BY id_categorie
");
foreach ($count_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $cat_counts[$row['id_categorie']] = $row['cnt'];
}

// Images par défaut pour les catégories (par nom)
$cat_images = [
    'Electronics'  => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&q=80',
    'Watches'      => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
    'Audio'        => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80',
    'Photography'  => 'https://images.unsplash.com/photo-1564466809058-bf4114d55352?w=600&q=80',
    'Clothing'     => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80',
    'Accessories'  => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80',
    'Furniture'    => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&q=80',
    'Lighting'     => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600&q=80',
    'Ceramics'     => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600&q=80',
];

include __DIR__ . '/../../includes/header-public.php';
?>

<main class="pub-category-page">

    <!-- Hero Banner -->
    <section class="pub-cat-hero">
        <div class="pub-cat-hero__overlay"></div>
        <img class="pub-cat-hero__img"
             src="https://images.unsplash.com/photo-1724582586529-62622e50c0b3?w=1400&q=80"
             alt="GAAM Collections" />
        <div class="pub-cat-hero__content">
            <p class="pub-cat-hero__tag">Curated Collections <?= date('Y') ?></p>
            <h1 class="pub-cat-hero__title">Our<br><em>Categories</em></h1>
            <p class="pub-cat-hero__desc">
                Discover our full range of curated categories,
                from precision electronics to timeless fashion.
            </p>
        </div>
        <!-- Breadcrumb -->
        <nav class="pub-cat-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= BASE_URL ?>pages/public/home.php">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span>Categories</span>
        </nav>
    </section>

    <!-- Categories Grid -->
    <section class="pub-section pub-section--categories-list">
        <div class="pub-container">
            <div class="pub-section__header">
                <h2 class="pub-section__title">All Categories</h2>
                <p class="pub-section__sub">Browse by department</p>
            </div>

            <div class="pub-categories-grid">
                <?php foreach ($categories_raw as $cat):
                    $catName = $cat['name_categorie'];
                    $catImg  = $cat_images[$catName] ?? 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&q=80';
                    $count   = $cat_counts[$cat['id_categorie']] ?? 0;
                ?>
                <a href="<?= BASE_URL ?>pages/public/product-catalog.php?category=<?= (int)$cat['id_categorie'] ?>"
                   class="pub-cat-item">
                    <div class="pub-cat-item__img-wrap">
                        <img src="<?= e($catImg) ?>" alt="<?= e($catName) ?>" loading="lazy" />
                        <div class="pub-cat-item__overlay"></div>
                    </div>
                    <div class="pub-cat-item__body">
                        <h3 class="pub-cat-item__name"><?= e($catName) ?></h3>
                        <p class="pub-cat-item__count"><?= $count ?> product<?= $count !== 1 ? 's' : '' ?></p>
                        <span class="pub-cat-item__cta">Shop Now <i class="fa-solid fa-arrow-right"></i></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Collection Banner -->
    <section class="pub-collection-banner">
        <div class="pub-container">
            <div class="pub-collection-banner__inner">
                <div class="pub-collection-banner__text">
                    <p class="pub-collection-banner__tag">Featured</p>
                    <h2>Architectural Minimalists</h2>
                    <p>Curated structures for the modern space. Pieces that define form through shadow and precision.</p>
                    <a href="<?= BASE_URL ?>pages/public/product-catalog.php"
                       class="pub-btn pub-btn--primary">Shop the Collection</a>
                </div>
                <div class="pub-collection-banner__img">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&q=80"
                         alt="Featured Collection" loading="lazy" />
                </div>
            </div>
        </div>
    </section>

</main>

<!-- ── Footer public ─────────────────────────────────────────────────────────── -->
<footer class="pub-footer">
    <div class="pub-container pub-footer__inner">
        <div class="pub-footer__brand">
            <h2 class="pub-footer__logo">GAAM</h2>
            <p class="pub-footer__tagline">Architectural Integrity. Timeless Modernity.</p>
        </div>
        <div class="pub-footer__cols">
            <div class="pub-footer__col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>pages/public/home.php">Home</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/product-catalog.php">Catalog</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/cart.php">Cart</a></li>
                </ul>
            </div>
            <div class="pub-footer__col">
                <h4>Account</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>pages/customer/user-profil.php">My Profile</a></li>
                    <li><a href="<?= BASE_URL ?>pages/customer/orders.php">My Orders</a></li>
                </ul>
            </div>
            <div class="pub-footer__col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Shipping &amp; Returns</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="pub-footer__bottom">
        <p>&copy; <?= date('Y') ?> GAAM. All rights reserved.</p>
    </div>
</footer>
