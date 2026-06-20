"use strict";

const settingsForm = document.querySelector(".form-settings");
if (settingsForm) {
  const inputs = settingsForm.querySelectorAll(".form-input");
  const editBtn = document.querySelector(".user-profile-edit");
  const fileInput = document.getElementById("adminImg");
  const profileImg = document.querySelectorAll(".user-profile__icon")[1];

  editBtn.addEventListener("click", () => fileInput.click());

  fileInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      console.log("new src:", e.target.result.substring(0, 50)); // 👈
      profileImg.src = e.target.result;
      console.log("after update:", profileImg.src.substring(0, 50)); // 👈
    };
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
    const username = settingsForm.querySelector("#username");
    const lastname = settingsForm.querySelector("#lastname");
    const email = settingsForm.querySelector("#email");
    const password = settingsForm.querySelector("#password");

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

  settingsForm.addEventListener("submit", (e) => {
    console.log("submit fired");
    const valid = validateForm();
    console.log("valid:", valid);
    if (!valid) e.preventDefault();
  });

  inputs.forEach((input) => {
    input.addEventListener("input", () => clearError(input));
  });
}
