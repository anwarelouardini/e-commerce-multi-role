<?php
$headerTitle = 'Admin Dashboard';
require_once __DIR__ . '/../../includes/header.php';
?>
  <main>
    <section class="section-statistics container">
      <h1 class="heading-primary">
        <span class="heading-secondary">Perfomance hub</span>
        Overview
      </h1>

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
            <div class="status-indicator status-indicator--green">+12.5%</div>
          </div>
          <h2 class="heading-secondary">Total Revenue</h2>
          <p class="heading-primary">$4.2M</p>
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
                class="bi bi-person-fill-add"
                viewBox="0 0 16 16"
              >
                <path
                  d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"
                />
                <path
                  d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"
                />
              </svg>
            </div>
            <div class="status-indicator status-indicator--green">+842</div>
          </div>
          <h2 class="heading-secondary">New Users</h2>
          <p class="heading-primary">24,892</p>
        </div>
        <div class="cards-container cards-container--purple">
          <div class="cards-logo">
            <div class="cards-logo__icon">
              <svg
                class="cards__icons cards__icons--purple"
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shop-window"
                viewBox="0 0 16 16"
              >
                <path
                  d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"
                />
              </svg>
            </div>
            <div class="status-indicator status-indicator--grey">Stable</div>
          </div>
          <h2 class="heading-secondary">Active Sellers</h2>
          <p class="heading-primary">1402</p>
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
                class="bi bi-hourglass-top"
                viewBox="0 0 16 16"
              >
                <path
                  d="M2 14.5a.5.5 0 0 0 .5.5h11a.5.5 0 1 0 0-1h-1v-1a4.5 4.5 0 0 0-2.557-4.06c-.29-.139-.443-.377-.443-.59v-.7c0-.213.154-.451.443-.59A4.5 4.5 0 0 0 12.5 3V2h1a.5.5 0 0 0 0-1h-11a.5.5 0 0 0 0 1h1v1a4.5 4.5 0 0 0 2.557 4.06c.29.139.443.377.443.59v.7c0 .213-.154.451-.443.59A4.5 4.5 0 0 0 3.5 13v1h-1a.5.5 0 0 0-.5.5m2.5-.5v-1a3.5 3.5 0 0 1 1.989-3.158c.533-.256 1.011-.79 1.011-1.491v-.702s.18.101.5.101.5-.1.5-.1v.7c0 .701.478 1.236 1.011 1.492A3.5 3.5 0 0 1 11.5 13v1z"
                />
              </svg>
            </div>
            <div class="status-indicator status-indicator--red">High</div>
          </div>
          <h2 class="heading-secondary">Pending Approvals</h2>
          <p class="heading-primary">42</p>
        </div>
      </div>
    </section>

    <div class="group-container container">
      <section class="section-chart">
        <div class="chart-container">
          <div class="chart-content-container">
            <div class="chart-content">
              <h3 class="heading-primary">Platform Health</h3>
              <p class="paragraph">
                Real-time traffic and conversion anlalysis
              </p>
            </div>
            <div class="chart-buttons">
              <a class="btn-white btn--active" href="#">Week</a>
              <a class="btn-white" href="#">Month</a>
            </div>
          </div>

          <div class="bars-container">
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Mon</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Tue</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Tue</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Tue</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar chart__bar--active">&nbsp;</div>
              <p class="chart__label">Fri</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Fri</p>
            </div>
            <div class="chart-bar-container">
              <div class="chart__bar">&nbsp;</div>
              <p class="chart__label">Fri</p>
            </div>
          </div>
        </div>
      </section>

      <section class="section-verification">
        <div class="users-items-container">
          <div class="users-items-content">
            <h3 class="heading-primary">Verification Queue</h3>
            <div class="status-indicator status-indicator--primary">
              8 New
            </div>
          </div>

          <div class="users-cards-wrapper">
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users1-icon.jpg"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Anwar EL OUARDINI</h6>
                <p class="paragraph">Applied: Today, 10.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users2-icon.avif"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Ghita EL MIR</h6>
                <p class="paragraph">Applied: Today, 12.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users2-icon.avif"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Ghita EL MIR</h6>
                <p class="paragraph">Applied: Today, 12.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users2-icon.avif"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Ghita EL MIR</h6>
                <p class="paragraph">Applied: Today, 12.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users2-icon.avif"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Ghita EL MIR</h6>
                <p class="paragraph">Applied: Today, 12.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
            <div class="user-card">
              <div class="user-card-profile">
                <img
                  class="user-card__icon"
                  src="../../assets/images/avatars/users2-icon.avif"
                  alt="User Profile"
                />
              </div>
              <div class="user-profile-content">
                <h6 class="heading-small">Ghita EL MIR</h6>
                <p class="paragraph">Applied: Today, 12.45AM</p>
              </div>

              <div class="user-profile-btns">
                <div class="btn-close">&nbsp;</div>
                <div class="btn-white btn--active">Review</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
