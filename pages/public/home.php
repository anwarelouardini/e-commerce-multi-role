<?php
// ─── pages/public/home.php ────────────────────────────────────────────────────
// Page d'accueil publique — intégrée au projet principal (e-commerce-multi-role)
// Utilise includes/header.php et le système CSS du projet principal

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$headerTitle = 'GAAM';
$header      = 'customer-nav';   // navbar customer du projet principal

// ── Featured products depuis la vraie DB ─────────────────────────────────────
try {
    $stmt = $pdo->prepare("
        SELECT p.id_product, p.name_product, p.description_product,
               p.price, p.product_image, c.name_categorie
        FROM products p
        JOIN categories c ON p.id_categorie = c.id_categorie
        ORDER BY p.id_product DESC
        LIMIT 3
    ");
    $stmt->execute();
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $featured_products = [];
}

// Gestion newsletter
$newsletter_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var(trim($_POST['newsletter_email']), FILTER_VALIDATE_EMAIL);
    $newsletter_message = $email
        ? '<p class="newsletter__success">Merci pour votre inscription !</p>'
        : '<p class="newsletter__error">Veuillez saisir un e-mail valide.</p>';
}

// CSS + JS spécifiques à cette page
$pathCSS = 'assets/css/pages/home-public.css';

include __DIR__ . '/../../includes/header-public.php';
?>

<!-- ── Hero Section ─────────────────────────────────────────────────────────── -->
<section class="pub-hero" id="hero">
    <div class="pub-hero__content">
        <p class="pub-hero__tag">Collections <?= date('Y') ?></p>
        <h1 class="pub-hero__title">The Silent<em> Architecture</em></h1>
        <p class="pub-hero__desc">
            A curated dialogue between form and fabric.
            Discover our latest drop featuring architectural silhouettes.
        </p>
        <div class="pub-hero__actions">
            <a href="<?= BASE_URL ?>pages/public/product-catalog.php" class="pub-btn pub-btn--primary">
                Shop The Edit
            </a>
            <a href="<?= BASE_URL ?>pages/public/category-page.php" class="pub-btn pub-btn--ghost">
                Browse Categories
            </a>
        </div>
    </div>
    <div class="pub-hero__scroll-hint" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
    </div>
</section>

<!-- ── Shop by Category ─────────────────────────────────────────────────────── -->
<section class="pub-section pub-section--categories" id="section-categories">
    <div class="pub-container">
        <div class="pub-section__header">
            <h2 class="pub-section__title">Shop by Category</h2>
            <p class="pub-section__sub">Refined selections for every facet of life.</p>
        </div>

        <div class="pub-cat-grid">
            <div class="pub-cat-card pub-cat-card--large" style="background-image:url('https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&q=80')">
                <div class="pub-cat-card__overlay"></div>
                <div class="pub-cat-card__label">
                    <h3>Essential Layers</h3>
                    <span>Knitwear &amp; Outerwear</span>
                </div>
            </div>

            <div class="pub-cat-col">
                <div class="pub-cat-card" style="background-image:url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80')">
                    <div class="pub-cat-card__overlay"></div>
                    <div class="pub-cat-card__label">
                        <h3>Modern Horology</h3>
                    </div>
                </div>
                <div class="pub-cat-card" style="background-image:url('https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&q=80')">
                    <div class="pub-cat-card__overlay"></div>
                    <div class="pub-cat-card__label">
                        <h3>
                            <a href="<?= BASE_URL ?>pages/public/category-page.php" class="pub-cat-card__link">
                                Skin Edit
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── The Curation (produits depuis DB) ────────────────────────────────────── -->
<section class="pub-section pub-section--curation">
    <div class="pub-container">
        <div class="pub-section__header">
            <h2 class="pub-section__title">The Curation</h2>
            <a href="<?= BASE_URL ?>pages/public/product-catalog.php" class="pub-see-all">See All &rsaquo;</a>
        </div>

        <div class="pub-product-list">
            <?php if (empty($featured_products)): ?>
                <!-- Fallback produits statiques si la DB est vide / non connectée -->
                <?php
                $fallback = [
                    ['name_product' => 'Merino Structure Coat',    'price' => 840,  'product_image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&q=80', 'badge' => 'New Arrival'],
                    ['name_product' => 'Nappa Leather Tote',       'price' => 1200, 'product_image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&q=80', 'badge' => 'Limited Edition'],
                    ['name_product' => 'Zenith Quartz Minimalist', 'price' => 129,  'product_image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80', 'badge' => null],
                ];
                foreach ($fallback as $p):
                    $p['id_product'] = 0;
                    $p['product_image_full'] = $p['product_image'];
                    include __DIR__ . '/../../includes/product-card-public.php';
                endforeach;
                ?>
            <?php else: ?>
                <?php foreach ($featured_products as $p):
                    // Construire l'URL image : si c'est une URL absolue on la garde, sinon on cherche dans assets
                    $isUrl = str_starts_with($p['product_image'], 'http');
                    $p['product_image_full'] = $isUrl
                        ? $p['product_image']
                        : BASE_URL . 'assets/images/products/' . $p['product_image'];
                ?>
                <div class="pub-product-card">
                    <a href="<?= BASE_URL ?>pages/public/product-detail.php?id=<?= (int)$p['id_product'] ?>"
                       class="pub-product-card__img-wrap">
                        <img src="<?= e($p['product_image_full']) ?>"
                             alt="<?= e($p['name_product']) ?>"
                             loading="lazy" />
                        <div class="pub-product-card__overlay">
                            <span class="pub-product-card__view">View Product</span>
                        </div>
                    </a>
                    <div class="pub-product-card__body">
                        <p class="pub-product-card__category"><?= e($p['name_categorie'] ?? '') ?></p>
                        <div class="pub-product-card__row">
                            <h3 class="pub-product-card__name"><?= e($p['name_product']) ?></h3>
                            <span class="pub-product-card__price">$<?= number_format($p['price'], 0) ?></span>
                        </div>
                        <a href="<?= BASE_URL ?>pages/public/product-catalog.php" class="pub-product-card__cta">
                            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── Newsletter ────────────────────────────────────────────────────────────── -->
<section class="pub-newsletter" id="newsletter">
    <div class="pub-container pub-newsletter__inner">
        <h2 class="pub-newsletter__title">Join The Monolith</h2>
        <p class="pub-newsletter__desc">
            Sign up for editorial insights, private collections, and the silent evolution of GAAM.
        </p>
        <?= $newsletter_message ?>
        <form method="POST" action="<?= BASE_URL ?>pages/public/home.php#newsletter"
              class="pub-newsletter__form">
            <input type="email"
                   name="newsletter_email"
                   class="pub-newsletter__input"
                   placeholder="Your email address"
                   required />
            <button type="submit" class="pub-btn pub-btn--primary">Subscribe</button>
        </form>
    </div>
</section>

<!-- ── Footer public ─────────────────────────────────────────────────────────── -->
<footer class="pub-footer">
    <div class="pub-container pub-footer__inner">
        <div class="pub-footer__brand">
            <h2 class="pub-footer__logo">GAAM</h2>
            <p class="pub-footer__tagline">Architectural Integrity. Timeless Modernity. Curated Craft.</p>
        </div>
        <div class="pub-footer__cols">
            <div class="pub-footer__col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>pages/public/home.php">Home</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/product-catalog.php">Catalog</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/category-page.php">Categories</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/cart.php">Cart</a></li>
                </ul>
            </div>
            <div class="pub-footer__col">
                <h4>Account</h4>
                <ul>
                    <li><a href="<?= BASE_URL ?>pages/customer/user-profil.php">My Profile</a></li>
                    <li><a href="<?= BASE_URL ?>pages/customer/orders.php">My Orders</a></li>
                    <li><a href="<?= BASE_URL ?>pages/public/history.php">History</a></li>
                </ul>
            </div>
            <div class="pub-footer__col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Shipping &amp; Returns</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="pub-footer__bottom">
        <p>&copy; <?= date('Y') ?> GAAM. All rights reserved.</p>
    </div>
</footer>
