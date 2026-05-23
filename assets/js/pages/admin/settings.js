"use strict";

const form = document.querySelector(".form-settings");
const inputs = form.querySelectorAll(".form-input");
const editBtn = document.querySelector(".user-profile-edit");
const fileInput = document.getElementById("adminImg");
const profileImg = document.querySelector(".user-profile__icon");

editBtn.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => (profileImg.src = e.target.result);
  reader.readAsDataURL(file);
});

const showError = function (input, message) {
  const formBox = input.closest(".form-box");
  const existing = formBox.querySelector(".submission-err");
  if (existing) existing.remove();

  input.style.outline = "2px solid var(--red)";

  const err = document.createElement("span");
  err.classList.add("submission-err");
  err.textContent = message;
  err.style.color = "var(--red)";
  formBox.appendChild(err);
};

const clearError = function (input) {
  const formBox = input.closest(".form-box");
  const existing = formBox.querySelector(".submission-err");
  if (existing) existing.remove();
  input.style.outline = "";
};

const validateForm = function () {
  let isValid = true;

  inputs.forEach((input) => clearError(input));

  const username = form.querySelector("#username");
  const lastname = form.querySelector("#lastname");
  const email = form.querySelector("#email");
  const password = form.querySelector("#password");

  if (!username.value.trim()) {
    showError(username, "Username is required.");
    isValid = false;
  }

  if (!lastname.value.trim()) {
    showError(lastname, "Last name is required.");
    isValid = false;
  }

  if (!email.value.trim()) {
    showError(email, "Email is required.");
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
    showError(email, "Invalid email format.");
    isValid = false;
  }

  if (!password.value.trim()) {
    showError(password, "Please confirm your password.");
    isValid = false;
  }

  return isValid;
};

form.addEventListener("submit", (e) => {
  if (!validateForm()) e.preventDefault();
});

// Clear error on input
inputs.forEach((input) => {
  input.addEventListener("input", () => clearError(input));
});
