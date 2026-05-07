<?php 
$headerTitle = 'Admin Settings';
$header = 'standard-nav';

require __DIR__ . '/../../includes/header.php';
?>
    <main>
      <div class="user-profile-container u-margin-top-med">
        <div class="user-profile-container">
          <div class="user-profile-img">
            <img
              class="user-profile__icon"
              src="<?= BASE_URL ?>assets/images/avatars/users1-icon.jpg"
              alt="Admin profile"
            />
            <div class="user-profile-edit">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="11"
                height="11"
                fill="currentColor"
                class="bi bi-pencil-fill"
                viewBox="0 0 16 16"
              >
                <path
                  d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"
                />
              </svg>
            </div>
          </div>
          <h2 class="heading-primary heading-primary--med">
            Admin User
            <span class="sub-heading txt-center">Master control</span>
          </h2>
        </div>

        <form class="form form-settings u-margin-top-med" action="">
          <div class="grid-container--2">
            <div class="form-box">
              <label class="heading-secondary" for="fullName">Full Name</label>
              <input
                class="form-input"
                type="text"
                id="fullName"
                name="fullName"
                placeholder="Full Name"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="email">Email Address</label>
              <input
                class="form-input"
                type="email"
                id="email"
                name="email"
                placeholder="example@mail.com"
              />
            </div>

            <div class="form-box">
              <label class="heading-secondary" for="bio"
                >Professional Bio</label
              >
              <textarea
                class="form-input"
                name="adminBio"
                id="bio"
                placeholder="Describe yourself..."
              ></textarea>
            </div>
          </div>

          <div class="form-btn-box">
            <button class="btn btn-primary">Save Changes</button>
            <button class="btn btn-primary btn-primary--purple">
              Discard Changes
            </button>
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
