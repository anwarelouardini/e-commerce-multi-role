<?php 
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

session_start();
$sellerId = (int)$_SESSION['id_seller'];

$ordersBySellers = getOrdersBySeller($pdo, $sellerId);

$ordersPerDay = getOrdersPerDay($pdo, $sellerId);
$ordersGrowth = getOrdersGrowth($ordersPerDay);

$day = array_column($ordersPerDay, 'day');
$data = array_column($ordersPerDay, 'count');


$header = 'vendor-nav';
$headerTitle = 'GAAM Seller';
$pathJSModule = 'assets/js/pages/vendor/orders.js';
$pathJs = 'assets/js/components/navbar.js';

require_once __DIR__ . '/../../includes/header.php';
?>

  <script>
    const chartLabels = <?= json_encode($day) ?>;
    const chartData = <?= json_encode($data) ?>;
  </script>

    <main>
      <section class="section-statistics container">
        <h1 class="heading-primary">
          Order Management
          <span class="sub-heading"
            >Review and process your daily high-volume architectural
            fulfillment</span
          >
        </h1>
        <div class="grid-container u-margin-top-med">
          <div class="cards-container card-total-orders">
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icon"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-clipboard2-check"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"
                  />
                  <path
                    d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"
                  />
                  <path
                    d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z"
                  />
                </svg>
              </div>
              <div class="status-indicator status-indicator--brown">+12%</div>
            </div>
            <h2 class="heading-secondary">Total Orders</h2>
            <p class="heading-primary"></p>
          </div>
          <div
            class="cards-container cards-container--grey card-pending-fulfillment"
          >
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-clock-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"
                  />
                </svg>
              </div>
            </div>
            <h2 class="heading-secondary">Pending Fulfillment</h2>
            <p class="heading-primary"></p>
          </div>
          <div
            class="cards-container cards-container--purple card-out-delivery"
          >
            <div class="cards-logo">
              <div class="cards-logo__icon">
                <svg
                  class="cards__icons"
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-truck"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"
                  />
                </svg>
              </div>
            </div>
            <h2 class="heading-secondary">Out for delivery</h2>
            <p class="heading-primary"></p>
          </div>
          <div
            class="cards-container cards-container--primary cards-container--bg-primary card-price-order"
          >
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
                  ></path>
                  <path
                    d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z"
                  ></path>
                </svg>
              </div>
            </div>
            <h2 class="heading-secondary">Monthly Revenue</h2>
            <p class="heading-primary"></p>
          </div>
        </div>
      </section>

      <section class="section-sellers-table container">
        <div class="filter-bar">
          <div class="flex">
            <h2 class="heading-secondary">Filter By:</h2>
            <div class="filter-tabs orders-status">
              <input
                type="radio"
                name="orders-status"
                id="allOrders"
                data-filter="all-orders"
                checked
              />
              <input
                type="radio"
                name="orders-status"
                id="processing"
                data-filter="pending"
              />
              <input
                type="radio"
                name="orders-status"
                id="shipped"
                data-filter="shipped"
              />
              <input
                type="radio"
                name="orders-status"
                id="cancelled"
                data-filter="refunded"
              />

              <label for="allOrders" class="filter-tab filter-tab--active"
                >All Orders</label
              >
              <label for="processing" class="filter-tab">Processing</label>
              <label for="shipped" class="filter-tab">Shipped</label>
              <label for="cancelled" class="filter-tab">Cancelled</label>
            </div>
          </div>

          <div class="filter-search">
            <input
              id="searchCustomer"
              class="form-input form-input--small"
              type="text"
              placeholder="Search..."
            />
            <button id="searchOrderBtn" class="form__btn">
              <svg
                class="form-btn__icon"
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                fill="currentColor"
                class="bi bi-filter-circle-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16M3.5 5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1 0-1M5 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"
                />
              </svg>
            </button>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="sellers-table">
            <thead class="table-head--radius">
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Fulfillment</th>
              </tr>
            </thead>
            <tbody class="tbody-orders">
              <?php if(!empty($ordersBySellers)): ?>
                <?php foreach($ordersBySellers AS $orderBySeller): ?>
                <tr data-orders="">
                  <td><h3 class="heading-primary--sm">#<?= e($orderBySeller['id_order'])  ?></h3></td>
                  <td>
                    <div class="product-table-img">
                      <div class="store-avatar">AE</div>
                      <span class="heading-small order-customer"
                        ><?= e(ucfirst($orderBySeller['username'])) . ' ' . e(strtoupper($orderBySeller['lastname'])) ?></span
                      >
                    </div>
                  </td>
                  <td class="paragraph"><?= e($orderBySeller['date_order']) ?></td>
                  <td class="heading-small heading-small--bold">
                    $<span class="order-price"><?= e($orderBySeller['price'] * $orderBySeller['quantity_order_items']) ?></span>
                  </td>
                  <td>
                    <span
                      class="status-indicator status-indicator--<?= e(getOrdersStatusClass($orderBySeller['order_status'])) ?> order-status"
                      ><?= e($orderBySeller['order_status']) ?></span
                    >
                  </td>
                  <td>
                    <select
                      name="category"
                      class="filter-bar__select filter-bar__select--grey select-order__status"
                      data-id="<?= e($orderBySeller['id_order']) ?>"
                    >
                    <option value="" disabled selected>Status</option>
                    <option value="pending" <?= $orderBySeller['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="processing" <?= $orderBySeller['order_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                    <option value="shipped" <?= $orderBySeller['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $orderBySeller['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= $orderBySeller['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                  <td colspan="6" class="t-data--empty">No orders yet.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <section class="section-chart">
          <div class="chart-container">
            <div class="chart-content-container">
              <div class="chart-content">
                <h3 class="heading-primary">Warehouse Performance</h3>
                <p class="paragraph">
                  Real-time throughput metrics across fulfillment center
                </p>
              </div>
              <?php if(!empty($data)): ?>
              <div class="chart-buttons">
                <a class="btn-white btn--active" href="#">Day</a>
              </div>
              <?php endif; ?>

          <div class="bars-container">
            <?php if(!empty($data)): ?>
              <canvas id="orderChart"></canvas>
            <?php else: ?>
              <p class="t-data--empty">No orders data available yet.</p>
            <?php endif; ?>
          </div>

          </div>
        </section>
      </section>
    </main>
  </body>
</html>
