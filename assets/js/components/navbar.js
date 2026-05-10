const hamburgerIcon = document.querySelector(".navigation__icon");
const hamburgerMenu = document.querySelector(".navigation-mobile-menu");
const logo = document.getElementById("navigation__logo");

const navigationProfile = document.querySelector(".navigation-profile");
const navigationProfileCard = document.querySelector(
  ".navigation-profile-card",
);

const showProfileCard = function () {
  navigationProfileCard.classList.toggle("show");
  navigationProfile.classList.toggle("navigation-profile--active");
};

const closeProfileCard = function () {
  if (navigationProfileCard.classList.contains("show")) {
    navigationProfileCard.classList.remove("show");
    navigationProfile.classList.remove("navigation-profile--active");
  }
};

const showMobileMenu = function () {
  closeProfileCard();

  logo.classList.toggle("navigation__logo--hide");
  hamburgerMenu.classList.toggle("navigation-mobile-menu--active");
  hamburgerIcon.classList.toggle("navigation__icon--menu");
};

// Make the Mobile Menu Active
hamburgerIcon.addEventListener("click", showMobileMenu);

// Show the Navigation Profile Card
navigationProfile.addEventListener("click", showProfileCard);

// Hide Profile Card when we click to the ESC
document.addEventListener("keydown", (e) => {
  e.key === "Escape" && closeProfileCard();
});

// Hide Navigation Profile Card if we click outside of the element
document.addEventListener("click", (e) => {
  if (
    !navigationProfile.contains(e.target) &&
    !navigationProfileCard.contains(e.target)
  ) {
    closeProfileCard();
  }
});
