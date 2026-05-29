<?php 
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

session_start();
$sellerId = (int)$_SESSION['id_seller'];

$categories = getCategories($pdo);

if(!empty($_POST)) {
  $errs = [];

  if(empty($_POST['productName'])) $errs[] = 'Product name is required';
  if(empty($_POST['price'])) $errs[] = 'Price is required';
  if(empty($_POST['stockQuantity'])) $errs[] = 'Stock quantity is required';
  if(empty($_POST['category'])) $errs[] = 'Category is required';
  if(empty($_FILES['mainImage'])) $errs[] = 'Main image is required';

  if(empty($errs)) {

    if($_FILES['mainImage']['error'] === 0 && $_FILES['mainImage']['size'] !== 0) {

      $nameWithoutExtension = pathinfo($_FILES['mainImage']['name'], PATHINFO_FILENAME);
      $name = preg_replace('/[^a-zA-Z0-9]/', '', $nameWithoutExtension);

      $extension = pathinfo($_FILES['mainImage']['name'], PATHINFO_EXTENSION);
      $filename = $name . '-' . time() . '.' . $extension;

      move_uploaded_file($_FILES['mainImage']['tmp_name'], __DIR__ . '/../../assets/images/products/' . $filename);
    } 

    $data = [
      'name' => $_POST['productName'],
      'description' => $_POST['productDescription'],
      'price' => $_POST['price'],
      'quantity' => $_POST['stockQuantity'],
      'id_categorie' => $_POST['category'],
      'image' => $filename,
      'seller_id' => $sellerId,
    ];

    addProduct($pdo, $data);
    header("Location: " . BASE_URL . 'pages/vendor/product-overview.php');
    die();
  }

}

$header = 'vendor-nav';
$headerTitle = 'GAAM Seller';
$pathJSModule = 'assets/js/pages/vendor/add-product.js';
require_once __DIR__ . '/../../includes/header.php';
?>

    <header class="header container u-margin-top-med">
      <h1 class="heading-primary">
        Add Product
        <span class="sub-heading"
          >Create a new listing in your curated collection. All entries are
          review for quality assurance before going live.</span
        >
      </h1>
    </header>

    <main class="container">
      <form method="post" enctype="multipart/form-data" action="add-product.php">
        <div class="grid-container--product">
        <!-- Core information container -->
        <div class="core-information-container u-margin-top-small">
          <div class="core-information-header">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-file-earmark-text-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"
              />
            </svg>
            <h2 class="heading-primary heading-primary--sm">
              Core information
            </h2>
          </div>

          <div class="core-information-main">
            <div class="form u-margin-top-med">
              <label
                class="heading-secondary heading-secondary--orange"
                for="productTitle"
                >Product title</label
              >
              <input
                class="form-input"
                type="text"
                placeholder="e.g Silk Meridian Evening Gown"
                name="productName"
              />

              <label
                class="heading-secondary heading-secondary--orange"
                for="productDescription"
                >Description</label
              >
              <textarea
                class="form-input"
                name="productDescription"
                id="productDescription"
                placeholder="Describe the craftsmanship materials and fit..."
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Price Container -->
        <div class="logistics-container u-margin-top-small">
          <div class="core-information-header">
            <svg
              class="logistics__icon"
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-tag-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"
              />
            </svg>
            <h2 class="heading-primary heading-primary--sm">Logistics</h2>
          </div>

          <div class="form u-margin-top-med">
            <label class="heading-secondary heading-secondary--blue" for="price"
              >Base price</label
            >
            <div class="input-wrap">
              <svg
                class="form-input__icon"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-currency-dollar"
                viewBox="0 0 16 16"
              >
                <path
                  d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"
                />
              </svg>
              <input
                class="form-input--2 form-input--logo"
                type="number"
                id="price"
                placeholder="0.00"
                name="price"
              />
            </div>

            <label
              class="heading-secondary heading-secondary--blue"
              for="stockQuantity"
              >Stock Quantity</label
            >
            <input
              class="form-input--2"
              type="number"
              id="stockQuantity"
              placeholder="0"
              name="stockQuantity"
            />
          </div>
        </div>

        <!-- Gallery Container -->
        <div class="media-gallery-container u-margin-top-small">
          <div class="core-information-header">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              class="bi bi-image-fill"
              viewBox="0 0 16 16"
            >
              <path
                d="M.002 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2zm1 9v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062zm5-6.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"
              />
            </svg>
            <h2 class="heading-primary heading-primary--sm">
              Core information
            </h2>
          </div>

          <div class="media-container">
            <div
              class="media-img-container media-img-container--1 u-margin-top-med"
            >
              <!-- State 1: Image exists -->
              <img
                class="main-product-img"
                src=""
                alt=""
                style="display: none"
              />

              <button class="media-img__delete" style="display: none">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                  />
                </svg>
              </button>

              <!-- State 2: No imtage exists -->
              <div class="upload-prompt">
                <!-- input field -->
                <input
                  type="file"
                  id="mainImage"
                  name="mainImage"
                  accept="image/png, image/jpeg"
                  style="display: none"
                />

                <!-- upload icon and text -->
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="30"
                  height="30"
                  fill="currentColor"
                  class="bi bi-cloud-arrow-up-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2m2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0z"
                  />
                </svg>
                <h3 class="heading-secondary u-margin-top-small">
                  Upload Main Image
                </h3>
                <p class="paragraph">PNG, JPG up to 10MB</p>
              </div>
            </div>

            <div class="media-img-container upload-prompt">
              <input
                type="file"
                accept="image/png, image/jpeg"
                style="display: none"
                name="productImg2"
              />

              <div class="add-img">+</div>
              <button class="media-img__delete" style="display: none">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                  />
                </svg>
              </button>
            </div>
            <div class="media-img-container upload-prompt">
              <input
                type="file"
                accept="image/png, image/jpeg"
                style="display: none"
                name="productImg3"
              />

              <div class="add-img">+</div>
              <button class="media-img__delete" style="display: none">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                  />
                </svg>
              </button>
            </div>
            <div class="media-img-container upload-prompt">
              <input
                type="file"
                accept="image/png, image/jpeg"
                style="display: none"
                name="productImg4"
              />

              <div class="add-img">+</div>
              <button class="media-img__delete" style="display: none">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                  />
                </svg>
              </button>
            </div>
            <div class="media-img-container upload-prompt">
              <input
                type="file"
                accept="image/png, image/jpeg"
                style="display: none"
                name="productImg5"
              />

              <div class="add-img">+</div>
              <button class="media-img__delete" style="display: none">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  fill="currentColor"
                  class="bi bi-trash-fill"
                  viewBox="0 0 16 16"
                >
                  <path
                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div class="right-container">
          <!-- Categorization -->
          <div class="categorization-container u-margin-top-small">
            <div class="core-information-header">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-tag-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"
                />
              </svg>
              <h2 class="heading-primary heading-primary--sm">
                Categorization
              </h2>
            </div>
            <div class="btn-container btn-container--2 u-margin-top-small">
              <select name="category" class="btn btn-secondary btn-categorization btn--active">
                <?php foreach($categories as $category): ?>
                  <option value="<?= e($category['id_categorie']) ?>">
                    <?= e($category['name_categorie']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <a href="#" class="btn btn-secondary btn-add">+ New</a>
            </div>
          </div>

          <!-- Submit button -->

          <div class="btn-container btn-container--3">
            <button id="confirmEditBtn" type="submit" class="btn btn-primary"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-floppy-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"
                />
                <path
                  d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"
                /></svg
              >Save product</button
            >
          </div>
          <div class="submission-err">
            <?php if(!empty($errs)): ?>
              <?php foreach($errs as $err): ?>
                <p class="error"><?= e($err) ?></p>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      </form>
    </main>
  </body>
</html>
