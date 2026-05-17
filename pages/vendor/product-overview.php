<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$sellerId = 27;

$products = getProductBySeller($pdo, $sellerId);

$header = 'vendor-nav';
$headerTitle = 'GAAM Seller';
$pathJSModule = 'assets/js/pages/vendor/products.js';
require_once __DIR__ . '/../../includes/header.php';
?>


    <header class="header container u-margin-top-med">
      <h1 class="heading-primary">My product</h1>
      <div class="status-indicator status-indicator--primary">
        Total Items: <span class="total__items">0</span>
      </div>
    </header>

    <main>
      <section class="section-sellers-table container">
        <div class="filter-bar filter-bar--column">
          <div class="filter-select">
            <select name="category" class="filter-bar__select">
              <option value="" data-filter="all-categories">
                All Categories
              </option>
              <option value="electronics" data-filter="electronics">
                Electronics
              </option>
              <option value="clothing" data-filter="clothing">Clothing</option>
              <option value="watches" data-filter="watches">Watches</option>
              <option value="audio" data-filter="audio">Audio</option>
              <option value="photography" data-filter="photography">
                Photography
              </option>
            </select>
          </div>

          <div class="filter-tabs">
            <input
              type="radio"
              name="stock"
              id="all"
              data-filter="all-stock"
              checked
            />
            <input
              type="radio"
              name="stock"
              id="in-stock"
              data-filter="in-stock"
            />
            <input
              type="radio"
              name="stock"
              id="low-stock"
              data-filter="low-stock"
            />
            <input
              type="radio"
              name="stock"
              id="out-stock"
              data-filter="out-stock"
            />

            <label for="all" class="filter-tab filter-tab--active">All</label>
            <label for="in-stock" class="filter-tab">In Stock</label>
            <label for="low-stock" class="filter-tab">Low Stock</label>
            <label for="out-stock" class="filter-tab">Out Of Stock</label>
          </div>

          <div class="filter-search">
            <input
              id="search-product"
              class="form-input form-input--small"
              type="text"
              placeholder="Search..."
              name="product-name"
            />
            <button id="search-btn" class="form__btn">
              <svg
                class="form-btn__icon"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-search"
                viewBox="0 0 16 16"
              >
                <path
                  d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"
                />
              </svg>
            </button>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="sellers-table">
            <thead class="table-head--radius">
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>price</th>
                <th>quantity</th>
                <th>status</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody class="table-content">
              <?php foreach($products as $product): ?>
              <tr
                data-category="<?= e($product['name_categorie']) ?>"
                data-stock=""
                data-id="<?= e($product['id_product']) ?>"
                data-name="<?= e($product['name_product']) ?>"
                data-image="<?= e($product['product_image']) ?>"
                data-price="<?= e($product['price']) ?>"
                data-qty="<?= e($product['quantity_product']) ?>"
              >
                <td>
                  <div class="product-table-img">
                    <img
                      class="product-table__icon"
                      src="<?= BASE_URL ?>assets/images/products/watche.jpg"
                      alt="Watch"
                    />
                    <h2 class="heading-small">
                      <span class="product-name"><?= e($product['name_product']) ?></span>

                      <span class="sub-heading"><?= e($product['description_product']) ?></span>
                    </h2>
                  </div>
                </td>
                <td>
                  <span
                    class="status-indicator status-indicator--grey product-category"
                    ><?= e($product['name_categorie']) ?></span
                  >
                </td>
                <td class="heading-small heading-small--bold">
                  $<span class="product__price"><?= e($product['price']) ?></span>
                </td>
                <td><span class="product__qte"><?= e($product['quantity_product']) ?></span> Units</td>
                <td>
                  <span
                    class="status-indicator status-indicator--green qte__status"
                    >In stock</span
                  >
                </td>
                <td>
                  <div class="table-btns">
                    <a
                      href="<?= BASE_URL ?>pages/vendor/edit-product.php"
                      class="btn-icon btn-icon--primary edit-product-btn"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="14"
                        height="14"
                        fill="currentColor"
                        class="bi bi-pen-fill"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"
                        />
                      </svg>
                    </a>
                    <a
                      href="<?= BASE_URL ?>pages/vendor/delete-product.php"
                      class="btn-icon btn-icon--suspend delete-product-btn"
                    >
                      <svg
                        class="cards__icon--red"
                        xmlns="http://www.w3.org/2000/svg"
                        width="14"
                        height="14"
                        fill="currentColor"
                        class="bi bi-trash-fill"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                        />
                      </svg>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <div class="flex-container container">
        <div class="cards-total cards-total--primary">
          <h2 class="heading-primary heading-primary--white">
            <span class="sub-heading">inventory value</span>
            <span class="inventory__price">$0</span>
          </h2>

          <div class="cards-total-icon-container">
            <svg
              class="cards-total__icon"
              xmlns="http://www.w3.org/2000/svg"
              width="125"
              height="125"
              fill="currentColor"
              class="bi bi-cash-stack"
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
        </div>
        <div class="cards-total">
          <h2 class="heading-primary heading-primary">
            <span class="sub-heading">inventory items</span>
            <span class="total__items">&nbsp;</span> Items
          </h2>

          <div class="cards-total-icon-container">
            <svg
              class="cards-total__icon cards-total__icon--grey"
              xmlns="http://www.w3.org/2000/svg"
              width="125"
              height="125"
              fill="currentColor"
              class="bi bi-exclamation"
              viewBox="0 0 16 16"
            >
              <path
                d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.553.553 0 0 1-1.1 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>
