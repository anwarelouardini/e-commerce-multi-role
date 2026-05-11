const form = document.querySelector(".form-settings");
const inputs = form.querySelectorAll("input, textarea");

form.addEventListener("submit", (e) => {
  inputs.forEach((input) => {
    if (input.value.trim() === "") {
      e.preventDefault();
      input.classList.add("input--error");
    } else {
      input.classList.remove("input--error");
    }
  });
});

inputs.forEach((input) => {
  input.addEventListener("input", () => {
    input.classList.remove("input--error");
  });
});
