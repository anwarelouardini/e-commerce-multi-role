export const initCategoryManager = function (
  categoryContainer,
  newBtnCategory,
) {
  const deactivateBtns = function () {
    categoryContainer
      .querySelectorAll(".btn-categorization")
      .forEach((btn) => btn.classList.remove("btn--active"));
  };

  const createCategory = function () {
    const input = document.createElement("input");

    input.type = "text";
    input.placeholder = "Category name...";
    input.classList.add("form-input", "form-input--small");

    categoryContainer.insertBefore(input, newBtnCategory);
    input.focus();

    input.addEventListener("keydown", function (e) {
      if (e.key === "Enter" && input.value.trim()) {
        const btn = document.createElement("a");

        btn.href = "#";
        btn.textContent = input.value.trim().toLowerCase();

        btn.classList.add("btn", "btn-secondary", "btn-categorization");

        categoryContainer.replaceChild(btn, input);

        deactivateBtns();
        btn.classList.add("btn--active");
      }
    });
  };

  // listeners OUTSIDE createCategory
  newBtnCategory.addEventListener("click", function (e) {
    e.preventDefault();
    createCategory();
  });

  categoryContainer.addEventListener("click", function (e) {
    const clickedBtn = e.target.closest(".btn-categorization");

    if (!clickedBtn) return;

    e.preventDefault();

    deactivateBtns();
    clickedBtn.classList.add("btn--active");
  });
};

export const initImageUpload = function (
  uploadPrompt,
  mainImageInput,
  mainImageEl,
  deleteImgBtn,
  mediaImgContainer,
) {
  // Main image
  uploadPrompt.addEventListener("click", () => mainImageInput.click());

  mainImageInput.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      mainImageEl.src = e.target.result;
      mainImageEl.style.display = "block";
      deleteImgBtn.style.display = "block";
      uploadPrompt.style.display = "none";

      mediaImgContainer.classList.add("media-img-container--product");
    };
    reader.readAsDataURL(file);
  });

  deleteImgBtn.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    mainImageEl.src = "";
    mainImageEl.style.display = "none";
    deleteImgBtn.style.display = "none";
    uploadPrompt.style.display = "block";
    mainImageInput.value = "";
    mediaImgContainer.classList.remove("media-img-container--product");
  });

  // Secondary images
  const secondaryContainers = document.querySelectorAll(
    ".media-img-container:not(.media-img-container--1)",
  );

  secondaryContainers.forEach((container) => {
    const fileInput = container.querySelector('input[type="file"]');
    const addBtn = container.querySelector(".add-img");
    const localDeleteBtn = container.querySelector(".media-img__delete");

    container.addEventListener("click", (e) => {
      if (e.target.closest(".media-img__delete")) return;
      fileInput.click();
    });

    fileInput.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          if (addBtn) addBtn.style.opacity = "0";
          container.style.backgroundImage = `url(${e.target.result})`;
          container.style.backgroundSize = "cover";
          container.style.backgroundPosition = "center";
          container.classList.add("has-image");

          if (localDeleteBtn) localDeleteBtn.style.display = "flex";
        };
        reader.readAsDataURL(file);
      }
    });

    if (localDeleteBtn) {
      localDeleteBtn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        container.style.backgroundImage = "";
        container.classList.remove("has-image");
        if (addBtn) addBtn.style.opacity = "1";
        localDeleteBtn.style.display = "none";
        fileInput.value = "";
      });
    }
  });
};
