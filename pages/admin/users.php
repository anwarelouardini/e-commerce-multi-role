<?php 
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$users = getUsers($pdo);

$pathJS = 'assets/js/pages/admin/users.js';
$headerTitle = 'Admin Users';
$header = 'standard-nav';
require_once __DIR__ . '/../../includes/header.php';
?>


    <header class="header container u-margin-top-med">
      <h1 class="heading-primary">
        Manage Users
        <span class="sub-heading"
          >Curate and oversee platform participants with precision</span
        >
      </h1>
    </header>

    <main>
      <div class="container">
        <div class="search-container">
          <div class="search-box u-margin-top-small">
            <svg
              class="search-box__icon bi bi-search"
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              fill="currentColor"
              viewBox="0 0 16 16"
            >
              <path
                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"
              />
            </svg>

            <input
              id="searchUser"
              class="search-box__input"
              type="text"
              placeholder="Search members..."
            />
          </div>
        </div>

        <div class="users-list-container">
          <div class="users-list-btns">
            <a data-filter="all" class="btn-secondary">All users</a>
            <a data-filter="admin" class="btn-secondary">Administrators</a>
            <a data-filter="seller" class="btn-secondary">Sellers</a>
            <a data-filter="customer" class="btn-secondary">Customers</a>
          </div>

          <div class="user-box-container u-margin-top-small">
            <?php foreach($users as $user): ?>
            <div class="user-box hide" data-role="<?= e($user['role_name']) ?>">
              <div class="users-container">
                <div class="user-profile">
                  <img
                    class="user__img"
                    src="<?= BASE_URL ?>assets/images/avatars/users1-icon.jpg"
                    alt="Profile 1"
                  />
                  <h1>
                    <span class="username"><?php echo e($user['username']) . ' ' . e($user['lastname']); ?></span>
                    <span class="sub-heading"><?php echo e($user['email']); ?></span>
                  </h1>
                </div>
                <div class="users-status">
                  <div
                    class="status-indicator status-indicator--<?php echo e(getRoleClass($user['role_name']));?> u-margin-bottom-sm"
                  >
                    <?php echo e($user['role_name']); ?>
                  </div>
                  <div class="status status--<?php echo e(getStatusClass($user['status'])); ?>">
                    <div class="status__cercle">&nbsp;</div>
                    <p class="sub-heading"><?= e($user['status']); ?></p>
                  </div>
                </div>
              </div>
              <hr class="line__break" />
              
              <div class="btn-container u-margin-top-small">
                <?php if($user['status'] === 'active'): ?>
                <a class="btn-secondary btn-secondary--blue txt-right" href="#"
                  >View Profile</a
                >
                <?php else: ?>
                  <a
                  class="btn-secondary btn-secondary--green txt-right approve-btn"
                  href="#"
                  ><svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-check-circle"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"
                    />
                    <path
                      d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"
                    /></svg
                  >Approve</a
                >
                <a
                  class="btn-secondary btn-secondary--red txt-right delete-btn"
                  href="<?= BASE_URL ?>pages/admin/delete-user.php?<?php echo http_build_query(['id' => $user['id_user']]); ?>"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-trash"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"
                    />
                    <path
                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"
                    /></svg
                  >Delete</a
                >
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="users-list-pages u-margin-top-small">
          <div class="users-pages__left">
              <a id="prevBtn" class="sub-heading">&lsaquo; Previous</a>
          </div>
          <div class="users-pages-links"></div>
          <div class="users-pages__right">
              <a id="nextBtn" class="sub-heading">Next &rsaquo;</a>
          </div>
        </div>
        
      </div>
    </main>

  </body>
</html>
