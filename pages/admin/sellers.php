<?php 
$pathJS = 'assets/js/pages/admin/sellers.js';
$headerTitle = 'Admin Sellers';
$header = 'standard-nav';
require_once __DIR__ . '/../../includes/header.php';
?>

  <header class="header container u-margin-top-med">
    <h1 class="heading-primary">
      <span class="heading-secondary">Sellers</span>
      Overview
    </h1>
  </header>

  <main>
    <section class="section-sellers-stats-bar container">
      <div class="grid-container u-margin-top-med">
        <div class="cards-container">
          <div class="cards-logo">
            <div class="cards-logo__icon">
              <svg
                class="cards__icons"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-people-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"
                />
              </svg>
            </div>
          </div>
          <h2 class="heading-secondary">Total Sellers</h2>
          <p id="totalSellers" class="heading-primary">1,284</p>
        </div>
        <div class="cards-container cards-container--brown">
          <div class="cards-logo">
            <div class="cards-logo__icon cards-logo__icon--brown">
              <svg
                class="cards__icons cards__icons--brown"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-hourglass-top"
                viewBox="0 0 16 16"
              >
                <path
                  d="M2 14.5a.5.5 0 0 0 .5.5h11a.5.5 0 1 0 0-1h-1v-1a4.5 4.5 0 0 0-2.557-4.06c-.29-.139-.443-.377-.443-.59v-.7c0-.213.154-.451.443-.59A4.5 4.5 0 0 0 12.5 3V2h1a.5.5 0 0 0 0-1h-11a.5.5 0 0 0 0 1h1v1a4.5 4.5 0 0 0 2.557 4.06c.29.139.443.377.443.59v.7c0 .213-.154.451-.443.59A4.5 4.5 0 0 0 3.5 13v1h-1a.5.5 0 0 0-.5.5m2.5-.5v-1a3.5 3.5 0 0 1 1.989-3.158c.533-.256 1.011-.79 1.011-1.491v-.702s.18.101.5.101.5-.1.5-.1v.7c0 .701.478 1.236 1.011 1.492A3.5 3.5 0 0 1 11.5 13v1z"
                />
              </svg>
            </div>
          </div>
          <h2 class="heading-secondary">Pending Approvals</h2>
          <p class="heading-primary heading-primary--brown">42</p>
        </div>
        <div class="cards-container cards-container--green">
          <div class="cards-logo">
            <div class="cards-logo__icon cards-logo__icon--green">
              <svg
                class="cards__icons cards__icons--green"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shield-fill-check"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793z"
                />
              </svg>
            </div>
          </div>
          <h2 class="heading-secondary">Active sellers</h2>
          <p
            id="activeSellers"
            class="heading-primary heading-primary--green"
          >
            1,210
          </p>
        </div>
        <div class="cards-container cards-container--red">
          <div class="cards-logo">
            <div class="cards-logo__icon cards-logo__icon--red">
              <svg
                class="cards__icons--red"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-slash-circle-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.646-2.646a.5.5 0 0 0-.708-.708l-6 6a.5.5 0 0 0 .708.708z"
                />
              </svg>
            </div>
          </div>
          <h2 class="heading-secondary">Suspended sellers</h2>
          <p
            id="suspendedSellers"
            class="heading-primary heading-primary--red"
          >
            1402
          </p>
        </div>
      </div>
    </section>

    <section class="section-sellers-table container">
      <div id="sellersTable" class="sellers-table-container">
        <div class="sellers-table-header">
          <h3 class="heading-primary">Active Sellers</h3>
          <div class="sellers-table-actions">
            <input
              id="storeSearch"
              class="form-input form-input--grey form-input--small"
              type="text"
              placeholder="Search "
            />
            <a id="sortBtn" class="btn btn-secondary"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-filter"
                viewBox="0 0 16 16"
              >
                <path
                  d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"
                /></svg
              ><span class="sort-btn__txt">Sort</span></a
            >
            <a id="tableExport" href="#" class="btn btn-secondary"
              ><svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-download"
                viewBox="0 0 16 16"
              >
                <path
                  d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"
                />
                <path
                  d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"
                /></svg
              >Export</a
            >
          </div>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="sellers-table">
          <thead>
            <tr>
              <th>Store Name</th>
              <th>Owner</th>
              <th>Rating</th>
              <th>Products</th>
              <th>Orders</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="sellers-table__body">
            <tr>
              <td>
                <div class="store-name">
                  <div class="store-avatar">V</div>
                  <span class="store__tag">Electronics</span>
                </div>
              </td>
              <td class="owner__name">EL MIR Ghita</td>
              <td>
                <div class="rating"><span class="stars">★★★★★</span>4.2</div>
              </td>
              <td>1,204</td>
              <td>8,432</td>
              <td>
                <span
                  class="status-indicator status-indicator--green user--status"
                  >Active</span
                >
              </td>
              <td>
                <div class="table-btns">
                  <button class="btn-icon btn-icon--primary btn-approve hide">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-check-circle-fill"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                      />
                    </svg>
                  </button>
                  <button class="btn-icon btn-icon--suspend">
                    <svg
                      class="cards__icons--red"
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-slash-circle-fill"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.646-2.646a.5.5 0 0 0-.708-.708l-6 6a.5.5 0 0 0 .708.708z"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div class="store-name">
                  <div class="store-avatar">V</div>
                  <span class="store__tag">Aelvet & Vine</span>
                </div>
              </td>
              <td class="owner__name">EL OUARDINI Anwar</td>
              <td>
                <div class="rating"><span class="stars">★★★★★</span>4.2</div>
              </td>
              <td>1,204</td>
              <td>8,432</td>
              <td>
                <span
                  class="status-indicator status-indicator--green user--status"
                  >Active</span
                >
              </td>
              <td>
                <div class="table-btns">
                  <button class="btn-icon btn-icon--primary btn-approve hide">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-check-circle-fill"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
                      />
                    </svg>
                  </button>
                  <button class="btn-icon btn-icon--suspend">
                    <svg
                      class="cards__icons--red"
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      class="bi bi-slash-circle-fill"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.646-2.646a.5.5 0 0 0-.708-.708l-6 6a.5.5 0 0 0 .708.708z"
                      />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
  </body>
</html>
