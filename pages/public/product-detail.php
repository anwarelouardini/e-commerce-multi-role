<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

// Récupérer le produit
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("
    SELECT p.id_product, p.name_product, p.description_product,
           p.price, p.product_image, p.quantity_product,
           c.name_categorie, c.id_categorie
    FROM products p
    JOIN categories c ON c.id_categorie = p.id_categorie
    WHERE p.id_product = :id
    LIMIT 1
");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: ' . BASE_URL . 'pages/public/product-catalog.php');
    exit;
}

// Produits similaires
$stmt2 = $pdo->prepare("
    SELECT id_product, name_product, price, product_image
    FROM products
    WHERE id_categorie = :cat AND id_product != :id
    LIMIT 3
");
$stmt2->execute([':cat' => $product['id_categorie'], ':id' => $id]);
$related = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$headerTitle = 'GAAM — ' . $product['name_product'];
$header      = 'customer-nav';
$backLink    = BASE_URL . 'pages/public/product-catalog.php';

// Image URL
$isUrl   = str_starts_with($product['product_image'] ?? '', 'http');
$imgMain = $isUrl ? $product['product_image']
                  : BASE_URL . 'assets/images/products/' . $product['product_image'];

include __DIR__ . '/../../includes/header-public.php';
?>

<main class="pub-detail-page">

    <!-- Breadcrumb -->
    <nav class="pub-breadcrumb" aria-label="Breadcrumb">
        <a href="<?= BASE_URL ?>pages/public/home.php">Home</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="<?= BASE_URL ?>pages/public/product-catalog.php">Catalog</a>
        <i class="fa-solid fa-chevron-right"></i>
        <span><?= e($product['name_product']) ?></span>
    </nav>

    <!-- Product Layout -->
    <div class="pub-detail-layout">

        <!-- Product Image -->
        <div class="pub-detail-gallery">
            <div class="pub-detail-gallery__main">
                <img id="mainProductImg"
                     src="<?= e($imgMain) ?>"
                     alt="<?= e($product['name_product']) ?>"
                     loading="eager" />
                <?php if ($product['quantity_product'] <= 0): ?>
                <div class="pub-detail-gallery__badge pub-detail-gallery__badge--oos">Out of Stock</div>
                <?php elseif ($product['quantity_product'] <= 5): ?>
                <div class="pub-detail-gallery__badge pub-detail-gallery__badge--low">Only <?= (int)$product['quantity_product'] ?> left</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Info -->
        <div class="pub-detail-info">
            <p class="pub-detail-info__category"><?= e($product['name_categorie']) ?></p>
            <h1 class="pub-detail-info__name"><?= e($product['name_product']) ?></h1>
            <div class="pub-detail-info__price-wrap">
                <span class="pub-detail-info__price">$<?= number_format($product['price'], 2) ?></span>
            </div>

            <?php if ($product['description_product']): ?>
            <p class="pub-detail-info__desc"><?= e($product['description_product']) ?></p>
            <?php endif; ?>

            <!-- Selectors -->
            <div class="pub-detail-selectors">
                <p class="pub-selector-label">Select Finish</p>
                <div class="pub-finish-options">
                    <button class="pub-finish-dot pub-finish-dot--navy pub-finish-dot--active" data-color="Navy" aria-label="Navy finish"></button>
                    <button class="pub-finish-dot pub-finish-dot--silver" data-color="Silver" aria-label="Silver finish"></button>
                    <button class="pub-finish-dot pub-finish-dot--gold" data-color="Gold" aria-label="Gold finish"></button>
                </div>

                <p class="pub-selector-label">Size</p>
                <div class="pub-size-options">
                    <button class="pub-size-btn pub-size-btn--active">S</button>
                    <button class="pub-size-btn">M</button>
                    <button class="pub-size-btn">L</button>
                    <button class="pub-size-btn">XL</button>
                </div>

                <p class="pub-selector-label">Quantity</p>
                <div class="pub-qty-wrap">
                    <button class="pub-qty-btn" id="qtyMinus" aria-label="Decrease">−</button>
                    <input type="number" id="qtyInput" class="pub-qty-input" value="1" min="1"
                           max="<?= max(1, (int)$product['quantity_product']) ?>">
                    <button class="pub-qty-btn" id="qtyPlus" aria-label="Increase">+</button>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="pub-detail-cta">
                <?php if ($product['quantity_product'] > 0): ?>
                <button id="addToCartBtn" class="pub-btn pub-btn--primary pub-btn--lg pub-btn--full">
                    <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                </button>
                <a id="buyNowBtn" href="<?= BASE_URL ?>pages/public/shipping.php" class="pub-btn pub-btn--dark pub-btn--lg pub-btn--full">
                    Buy Now — $<?= number_format($product['price'], 2) ?>
                </a>
                <?php else: ?>
                <button class="pub-btn pub-btn--disabled pub-btn--lg pub-btn--full" disabled>
                    Out of Stock
                </button>
                <?php endif; ?>
            </div>

            <!-- Feature cards -->
            <div class="pub-feature-row">
                <div class="pub-feature-card">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div>
                        <strong>Quality</strong>
                        <span>Certified craftsmanship</span>
                    </div>
                </div>
                <div class="pub-feature-card">
                    <i class="fa-solid fa-truck"></i>
                    <div>
                        <strong>Free Shipping</strong>
                        <span>On orders over $200</span>
                    </div>
                </div>
                <div class="pub-feature-card">
                    <i class="fa-solid fa-rotate-left"></i>
                    <div>
                        <strong>Easy Returns</strong>
                        <span>30-day return policy</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <section class="pub-related">
        <div class="pub-container">
            <div class="pub-section__header">
                <h2 class="pub-section__title">You May Also Like</h2>
                <a href="<?= BASE_URL ?>pages/public/product-catalog.php?category=<?= (int)$product['id_categorie'] ?>"
                   class="pub-see-all">See All &rsaquo;</a>
            </div>
            <div class="pub-product-list">
                <?php foreach ($related as $r):
                    $rIsUrl = str_starts_with($r['product_image'] ?? '', 'http');
                    $rImg   = $rIsUrl ? $r['product_image']
                                     : BASE_URL . 'assets/images/products/' . $r['product_image'];
                ?>
                <div class="pub-product-card">
                    <a href="<?= BASE_URL ?>pages/public/product-detail.php?id=<?= (int)$r['id_product'] ?>"
                       class="pub-product-card__img-wrap">
                        <img src="<?= e($rImg) ?>" alt="<?= e($r['name_product']) ?>" loading="lazy" />
                        <div class="pub-product-card__overlay">
                            <span class="pub-product-card__view">View Product</span>
                        </div>
                    </a>
                    <div class="pub-product-card__body">
                        <div class="pub-product-card__row">
                            <h3 class="pub-product-card__name"><?= e($r['name_product']) ?></h3>
                            <span class="pub-product-card__price">$<?= number_format($r['price'], 0) ?></span>
                        </div>
                        <a href="<?= BASE_URL ?>pages/public/product-detail.php?id=<?= (int)$r['id_product'] ?>"
                           class="pub-product-card__cta">
                            <i class="fa-solid fa-bag-shopping"></i> View
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<!-- Footer public -->
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
                </ul>
            </div>
        </div>
    </div>
    <div class="pub-footer__bottom">
        <p>&copy; <?= date('Y') ?> GAAM. All rights reserved.</p>
    </div>
</footer>

<script>
(function () {
    // Quantity selector
    const minus = document.getElementById('qtyMinus');
    const plus  = document.getElementById('qtyPlus');
    const input = document.getElementById('qtyInput');

    minus?.addEventListener('click', () => {
        const v = parseInt(input.value);
        if (v > 1) input.value = v - 1;
    });
    plus?.addEventListener('click', () => {
        const max = parseInt(input.max);
        const v   = parseInt(input.value);
        if (v < max) input.value = v + 1;
    });

    // Finish dot selector
    document.querySelectorAll('.pub-finish-dot').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.pub-finish-dot').forEach(b => b.classList.remove('pub-finish-dot--active'));
            btn.classList.add('pub-finish-dot--active');
        });
    });

    // Size selector 
    document.querySelectorAll('.pub-size-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.pub-size-btn').forEach(b => b.classList.remove('pub-size-btn--active'));
            btn.classList.add('pub-size-btn--active');
        });
    });

    // Cart helpers
    const STORAGE_KEY = 'gaamCart';

    function loadCart() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
        catch { return []; }
    }

    function saveCart(cart) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    }

    function buildItem(qty) {
        return {
            id:       <?= (int)$product['id_product'] ?>,
            title:    <?= json_encode($product['name_product']) ?>,
            subtitle: <?= json_encode($product['name_categorie']) ?>,
            price:    <?= (float)$product['price'] ?>,
            image:    <?= json_encode($imgMain) ?>,
            quantity: qty
        };
    }

    function addToCart(qty) {
        const cart  = loadCart();
        const found = cart.find(i => i.id === <?= (int)$product['id_product'] ?>);
        if (found) {
            found.quantity += qty;
        } else {
            cart.push(buildItem(qty));
        }
        saveCart(cart);
    }

    // Add to Cart button 
    document.getElementById('addToCartBtn')?.addEventListener('click', () => {
        const qty = parseInt(input.value) || 1;
        addToCart(qty);
        window.location.href = '<?= BASE_URL ?>pages/public/cart.php';
    });

    // Buy Now button
    document.getElementById('buyNowBtn')?.addEventListener('click', (e) => {
        e.preventDefault();
        const qty = parseInt(input.value) || 1;
        addToCart(qty);
        window.location.href = '<?= BASE_URL ?>pages/public/shipping.php';
    });

})();
</script>