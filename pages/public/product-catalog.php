<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$headerTitle = 'GAAM — Catalog';
$header      = 'customer-nav';

// Catégories 
$categories = getCategories($pdo);

// Filtres GET
$selected_cat = isset($_GET['category']) ? (int)$_GET['category'] : null;
$sort         = $_GET['sort'] ?? 'featured';
$min_price    = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price    = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 9999;

// Pagination 
$per_page   = 8;
$page_num   = max(1, (int)($_GET['page'] ?? 1));
$offset     = ($page_num - 1) * $per_page;

// Requête produits
$where_parts = ['p.price BETWEEN :min AND :max'];
$params      = [':min' => $min_price, ':max' => $max_price];

if ($selected_cat) {
    $where_parts[] = 'p.id_categorie = :cat';
    $params[':cat'] = $selected_cat;
}

$where_sql = 'WHERE ' . implode(' AND ', $where_parts);

$order_sql = match($sort) {
    'price_asc'  => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'newest'     => 'p.id_product DESC',
    default      => 'p.id_product ASC',
};

// Count total
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM products p $where_sql");
$count_stmt->execute($params);
$total_products = (int) $count_stmt->fetchColumn();
$total_pages    = max(1, ceil($total_products / $per_page));

// Fetch products
$stmt = $pdo->prepare("
    SELECT p.id_product, p.name_product, p.description_product,
           p.price, p.product_image, c.name_categorie, c.id_categorie
    FROM products p
    JOIN categories c ON c.id_categorie = p.id_categorie
    $where_sql
    ORDER BY $order_sql
    LIMIT :limit OFFSET :offset
");
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit',  $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,   PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build query string helper
function build_qs(array $overrides = []): string {
    $base = array_merge($_GET, $overrides);
    return http_build_query($base);
}

include __DIR__ . '/../../includes/header-public.php';
?>

<main class="pub-catalog-page">

    <!-- Page Header -->
    <div class="pub-page-header">
        <p class="pub-page-header__tag">Curated Selection</p>
        <h1 class="pub-page-header__title">Living Essentials</h1>
    </div>

    <div class="pub-catalog-layout">

        <!-- Sidebar Filters -->
        <aside class="pub-sidebar" id="pub-sidebar">
            <div class="pub-sidebar__inner">

                <!-- Mobile close -->
                <button class="pub-sidebar__close" id="pub-sidebar-close" aria-label="Close filters">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <h3 class="pub-sidebar__heading">Category</h3>
                <div class="pub-filter-tabs">
                    <a href="?<?= build_qs(['category' => null, 'page' => 1]) ?>"
                       class="pub-filter-tab <?= !$selected_cat ? 'pub-filter-tab--active' : '' ?>">
                        All
                    </a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="?<?= build_qs(['category' => $cat['id_categorie'], 'page' => 1]) ?>"
                       class="pub-filter-tab <?= $selected_cat === (int)$cat['id_categorie'] ? 'pub-filter-tab--active' : '' ?>">
                        <?= e($cat['name_categorie']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Price range -->
                <div class="pub-filter-section">
                    <h3 class="pub-sidebar__heading">Price Range</h3>
                    <form method="GET" id="price-form">
                        <?php if ($selected_cat): ?>
                        <input type="hidden" name="category" value="<?= $selected_cat ?>">
                        <?php endif; ?>
                        <input type="hidden" name="sort" value="<?= e($sort) ?>">

                        <div class="pub-price-range">
                            <div class="pub-price-labels">
                                <span>$<span id="labelMin"><?= (int)$min_price ?></span></span>
                                <span>$<span id="labelMax"><?= (int)$max_price ?></span></span>
                            </div>
                            <div class="pub-range-wrap">
                                <div class="pub-range-track">
                                    <div class="pub-range-fill" id="rangeFill"></div>
                                </div>
                                <input type="range" class="pub-range" id="rangeMin"
                                       name="min_price" min="0" max="2000"
                                       value="<?= (int)$min_price ?>" step="10">
                                <input type="range" class="pub-range" id="rangeMax"
                                       name="max_price" min="0" max="2000"
                                       value="<?= (int)($max_price >= 9999 ? 2000 : $max_price) ?>" step="10">
                            </div>
                        </div>
                        <button type="submit" class="pub-btn pub-btn--sm pub-btn--primary" style="width:100%;margin-top:1rem;">
                            Apply Filter
                        </button>
                    </form>
                </div>

            </div>
        </aside>

        <!-- Sidebar overlay (mobile) -->
        <div class="pub-sidebar-overlay" id="pub-sidebar-overlay"></div>

        <!-- ── Products Area ── -->
        <div class="pub-catalog-main">

            <!-- Sort / Filter bar -->
            <div class="pub-filter-bar">
                <button class="pub-btn pub-btn--sm pub-btn--ghost" id="pub-filter-toggle">
                    <i class="fa-solid fa-sliders"></i> Filters
                </button>
                <p class="pub-filter-bar__count">
                    Showing <?= count($products) ?> of <?= $total_products ?> products
                </p>
                <div class="pub-filter-bar__sort">
                    <label for="sort-select">Sort:</label>
                    <select id="sort-select" class="pub-select"
                            onchange="location.href='?<?= build_qs(['sort' => '__SORT__', 'page' => 1]) ?>'.replace('__SORT__', this.value)">
                        <option value="featured"   <?= $sort === 'featured'   ? 'selected' : '' ?>>Featured</option>
                        <option value="price_asc"  <?= $sort === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="newest"     <?= $sort === 'newest'     ? 'selected' : '' ?>>Newest</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="pub-product-grid">
                <?php if (empty($products)): ?>
                    <div class="pub-empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <p>No products found for the selected filters.</p>
                        <a href="?<?= build_qs(['category' => null, 'min_price' => 0, 'max_price' => 9999, 'page' => 1]) ?>"
                           class="pub-btn pub-btn--primary">Clear Filters</a>
                    </div>
                <?php endif; ?>

                <?php foreach ($products as $p):
                    $isUrl = str_starts_with($p['product_image'] ?? '', 'http');
                    $imgSrc = $isUrl
                        ? $p['product_image']
                        : BASE_URL . 'assets/images/products/' . $p['product_image'];
                ?>
                <div class="pub-product-card">
                    <a href="<?= BASE_URL ?>pages/public/product-detail.php?id=<?= (int)$p['id_product'] ?>"
                       class="pub-product-card__img-wrap">
                        <img src="<?= e($imgSrc) ?>"
                             alt="<?= e($p['name_product']) ?>"
                             loading="lazy" />
                        <div class="pub-product-card__overlay">
                            <span class="pub-product-card__view">View Product</span>
                        </div>
                    </a>
                    <div class="pub-product-card__body">
                        <p class="pub-product-card__category"><?= e($p['name_categorie']) ?></p>
                        <div class="pub-product-card__row">
                            <h3 class="pub-product-card__name"><?= e($p['name_product']) ?></h3>
                            <span class="pub-product-card__price">$<?= number_format($p['price'], 0) ?></span>
                        </div>
                        <button class="pub-product-card__cta btn-add-to-cart"
                                data-product-id="<?= (int)$p['id_product'] ?>"
                                data-name="<?= e($p['name_product']) ?>"
                                data-desc="<?= e($p['description_product']) ?>"
                                data-image="<?= e($imgSrc) ?>"
                                data-price="<?= $p['price'] ?>">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span class="btn-add-label">Add to Cart</span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="pub-pagination" aria-label="Products pagination">
                <?php if ($page_num > 1): ?>
                <a href="?<?= build_qs(['page' => $page_num - 1]) ?>"
                   class="pub-pagination__btn" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?<?= build_qs(['page' => $i]) ?>"
                   class="pub-pagination__btn <?= $i === $page_num ? 'pub-pagination__btn--active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($page_num < $total_pages): ?>
                <a href="?<?= build_qs(['page' => $page_num + 1]) ?>"
                   class="pub-pagination__btn" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

        </div>
    </div>

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
        </div>
    </div>
    <div class="pub-footer__bottom">
        <p>&copy; <?= date('Y') ?> GAAM. All rights reserved.</p>
    </div>
</footer>

<script>
// Sidebar filter toggle (mobile)
(function () {
    const btn     = document.getElementById('pub-filter-toggle');
    const sidebar = document.getElementById('pub-sidebar');
    const overlay = document.getElementById('pub-sidebar-overlay');
    const close   = document.getElementById('pub-sidebar-close');

    function openSidebar()  { sidebar.classList.add('pub-sidebar--open'); overlay.classList.add('pub-sidebar-overlay--show'); }
    function closeSidebar() { sidebar.classList.remove('pub-sidebar--open'); overlay.classList.remove('pub-sidebar-overlay--show'); }

    btn?.addEventListener('click', openSidebar);
    close?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
})();

// Dual range slider
(function () {
    const minR  = document.getElementById('rangeMin');
    const maxR  = document.getElementById('rangeMax');
    const fill  = document.getElementById('rangeFill');
    const lMin  = document.getElementById('labelMin');
    const lMax  = document.getElementById('labelMax');

    if (!minR || !maxR) return;

    function updateRange() {
        const min = parseInt(minR.value);
        const max = parseInt(maxR.value);
        const range = parseInt(minR.max) - parseInt(minR.min);

        if (min > max - 50) {
            minR.value = max - 50;
        }

        lMin.textContent = minR.value;
        lMax.textContent = maxR.value;

        const pctL = ((parseInt(minR.value) - parseInt(minR.min)) / range) * 100;
        const pctR = ((parseInt(maxR.value) - parseInt(minR.min)) / range) * 100;
        fill.style.left  = pctL + '%';
        fill.style.width = (pctR - pctL) + '%';
    }

    minR.addEventListener('input', updateRange);
    maxR.addEventListener('input', updateRange);
    updateRange();
})();
// Add to Cart (localStorage) 
(function () {
    const BASE        = '<?= BASE_URL ?>';
    const STORAGE_KEY = 'gaamCart';
    const isLoggedIn  = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

    function loadCart() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
        catch { return []; }
    }

    function saveCart(cart) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    }

    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function () {

            if (!isLoggedIn) {
                window.location.href = BASE + 'pages/authentification/login.php';
                return;
            }

            const productId   = this.dataset.productId;
            const productName = this.dataset.name;
            const productDesc = this.dataset.desc;
            const productImg  = this.dataset.image;
            const productPrice = parseFloat(this.dataset.price);

            // Charger le panier, ajouter ou incrémenter
            const cart = loadCart();
            const existing = cart.find(i => i.id === productId);
            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    id       : productId,
                    title    : productName,
                    subtitle : productDesc,
                    image    : productImg,
                    price    : productPrice,
                    quantity : 1
                });
            }
            saveCart(cart);

            // Feedback visuel
            const label = this.querySelector('.btn-add-label');
            const icon  = this.querySelector('i');
            this.disabled = true;
            icon.className = 'fa-solid fa-check';
            label.textContent = 'Added!';
            this.style.background = '#2e7d32';

            setTimeout(() => {
                icon.className = 'fa-solid fa-bag-shopping';
                label.textContent = 'Add to Cart';
                this.style.background = '';
                this.disabled = false;
            }, 1500);
        });
    });
})();
</script>