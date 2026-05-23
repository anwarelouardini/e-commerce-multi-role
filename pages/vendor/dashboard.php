<?php 
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$sellerId = 27;


$productsBySeller = getProductBySeller($pdo, $sellerId);
$ordersBySeller = getOrdersBySeller($pdo, $sellerId);
$sellerInfo = getSellerById($pdo, $sellerId);
$sellerStats = getSellerStats($pdo, $sellerId);

$ordersPerDay = getOrdersPerDay($pdo, $sellerId);
$revenuePerDay = getRevenuePerDay($pdo, $sellerId);

$volumeLabel = array_column($ordersPerDay, 'day');
$volumeData = array_column($ordersPerDay, 'count');

$revenueLabels = array_column($revenuePerDay, 'day');
$revenueData = array_column($revenuePerDay, 'count');

// var_dump($productsBySeller);
// var_dump($ordersBySeller);
// var_dump($sellerInfo);

$header = 'vendor-nav';
$headerTitle = 'GAAM Seller';
$pathJs = 'assets/js/components/navbar.js';
$pathJSModule = 'assets/js/pages/vendor/dashboard.js';

require_once __DIR__ . '/../../includes/header.php';


?>

    <header class="header container u-margin-top-med">
      <h1 class="heading-primary">Welcome Back, <?= e(ucfirst($sellerInfo['username'])) ?></h1>
      <span class="sub-heading"
        >Here's an overview of your store performance, recent orders, and
        inventory status. Manage your products and track your sales all in one
        place.</span
      >
    </header>

    <main class="container">
      <section>
        <div class="grid-container u-margin-top-med">
          <div class="cards-container">
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons"
                  xmlns="http://www.w3.org/2000/svg"
                  width="20"
                  height="20"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4"
                  />
                  <path
                    d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z"
                  />
                </svg>
              </div>
              <div class="status-indicator status-indicator--green">+12.5%</div>
            </div>
            <h2 class="heading-secondary">Total Revenue</h2>
            <p class="heading-primary">$<?= e($sellerStats['total_revenue']) ?></p>
          </div>

          <div class="cards-container cards-container--grey">
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons cards__icons--grey"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5"
                  />
                  <path
                    d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5m6.769 6.854-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708"
                  />
                </svg>
              </div>
              <div class="status-indicator status-indicator--green">+5.2%</div>
            </div>
            <h2 class="heading-secondary">Total orders</h2>
            <p class="heading-primary"><?= e($sellerStats['total_order']) ?></p>
          </div>

          <div class="cards-container cards-container--purple">
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons cards__icons--grey"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"
                  />
                  <path
                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"
                  />
                  <path
                    d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"
                  />
                </svg>
              </div>
              <div class="status-indicator status-indicator--grey">Stable</div>
            </div>
            <h2 class="heading-secondary">Average order value</h2>
            <p class="heading-primary">$<?= e(number_format($sellerStats['avg_order_value'], 2, '.', '')) ?></p>
          </div>

          <div class="cards-container cards-container--brown">
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons cards__icons--brown"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"
                  />
                  <path
                    d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"
                  />
                </svg>
              </div>
            </div>
            <h2 class="heading-secondary">Customer satisfaction</h2>
            <p class="heading-primary"><?= e(floatval($sellerInfo['seller_rating'])) ?>/5.0</p>
          </div>
        </div>
      </section>

      <div class="group-container">
        <section class="section-chart">
          <div class="chart-container chart-container--white">
              <div class="chart-content-container chart-content-container--row">
                  <div class="chart-content">
                      <h3 class="heading-primary">Sales Trend</h3>
                      <p class="paragraph">Last 30 days performance</p>
                  </div>
                  <?php if(!empty($volumeData) || !empty($revenueData)): ?>
                  <div class="chart-buttons">
                      <a id="volumeBtn" class="btn-white btn--active-2" href="#">Volume</a>
                      <a id="revenueBtn" class="btn-white" href="#">Revenue</a>
                  </div>
                  <?php endif; ?>
              </div>
              <div class="bars-container">
                <?php if(!empty($volumeData) || !empty($revenueData)): ?>
                  <canvas id="salesChart"></canvas>
                <?php else: ?>
                  <p class="t-data--empty">No sales data available yet.</p>
                <?php endif; ?>
              </div>
          </div>
        </section>

        <section class="section-quick-actions">
          <div class="cards-total cards-total--primary">
            <h2 class="heading-primary--med heading-primary--white">
              Quick Actions
            </h2>
            <div
              class="cards-total-icon-container cards-total-cercle-container"
            >
              <div class="cercle__icon">&nbsp;</div>
            </div>
            <div class="btn-box u-margin-top-small">
              <a class="btn btn--light-blue" href="<?= BASE_URL ?>pages/vendor/add-product.php"
                >Add New Product <span class="right-arr">&rsaquo;</span></a
              >
              <a class="btn btn--light-blue" href="<?= BASE_URL ?>pages/vendor/orders.php"
                >Check Orders
                <span
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 1a2 2 0 0 0-2 2v2H5V3a3 3 0 1 1 6 0v2h-1V3a2 2 0 0 0-2-2M5 5H3.36a1.5 1.5 0 0 0-1.483 1.277L.85 13.13A2.5 2.5 0 0 0 3.322 16h9.355a2.5 2.5 0 0 0 2.473-2.87l-1.028-6.853A1.5 1.5 0 0 0 12.64 5H11v1.5a.5.5 0 0 1-1 0V5H6v1.5a.5.5 0 0 1-1 0z"
                    /></svg
                ></span>
              </a>
              <a class="btn btn--light-blue" href="<?= BASE_URL ?>pages/vendor/product-overview.php"
                >Manage Products
                <span
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z"
                    /></svg
                ></span>
              </a>
            </div>
          </div>
        </section>
      </div>

      <section class="section-sellers-table">
        <div id="sellersTable" class="sellers-table-container">
          <div class="sellers-table-header">
            <h3 class="heading-primary">Top Products</h3>
          </div>
        </div>
        <div class="table-wrapper">
          <table class="sellers-table">
            <thead class="">
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>price</th>
                <th>quantity</th>
                <th>status</th>
              </tr>
            </thead>
            <tbody class="table-content">
              <?php if(!empty($productsBySeller)): ?>
                <?php foreach($productsBySeller AS $product): ?>
                  <tr>
                    <td>
                      <div class="product-table-img">
                        <img
                          class="product-table__icon"
                          src="<?= BASE_URL ?>assets/images/products/<?= $product['product_image'] ?>"
                          alt="Watch"
                        />
                        <h2 class="heading-small">
                          <?= e(ucfirst($product['name_product'])) ?>
                          <spa class="sub-heading"><?= e($product['description_product']) ?></spa>
                        </h2>
                      </div>
                    </td>
                    <td>
                      <span class="status-indicator status-indicator--grey"
                        ><?= e($product['name_categorie']) ?></span
                      >
                    </td>
                    <td class="heading-small heading-small--bold">$<?= e($product['price']) ?></td>
                    <td><span class="product__qte"><?= e($product['quantity_product']) ?></span> Units</td>
                    <td>
                      <span class="status-indicator status-indicator--green qte__status"
                        ></span
                      >
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="t-data--empty">No product found. <a href="<?= BASE_URL ?>pages/vendor/add-product.php">Add your first product</a></td>
                  </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </body>
</html>
<script>
  const volumeLabels = <?= json_encode($volumeLabel) ?>;
  const volumeData = <?= json_encode($volumeData) ?>;
  const revenueLabels = <?= json_encode($revenueLabels) ?>;
  const revenueData = <?= json_encode($revenueData) ?>;
</script>
