export const initCategoryManager = function (
  categoryContainer,
  newBtnCategory,
) {
  const categorySelect = categoryContainer.querySelector(
    'select[name="category"]',
  );

  const createCategory = function () {
    if (categoryContainer.querySelector(".form-input--small")) return;

    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = "Category name...";
    input.classList.add("form-input", "form-input--small");
    categoryContainer.insertBefore(input, newBtnCategory);
    input.focus();

    input.addEventListener("keydown", async function (e) {
      if (e.key === "Escape") {
        input.remove();
        return;
      }
      if (e.key !== "Enter") return;
      e.preventDefault();

      const name = input.value.trim();
      if (!name) {
        input.remove();
        return;
      }

      input.disabled = true;

      try {
        const res = await fetch(
          "/e-commerce-multi-role/pages/vendor/add-category.php",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ name }),
          },
        );

        if (!res.ok) throw new Error("Server error " + res.status);

        const data = await res.json();

        // Ajouter dans le select et sélectionner
        const option = document.createElement("option");
        option.value = data.id;
        option.textContent = data.name;
        categorySelect.appendChild(option);
        categorySelect.value = data.id;

        input.remove();
      } catch (err) {
        console.error("Failed to save category:", err);
        input.disabled = false;
        input.style.borderColor = "red";
      }
    });
  };

  newBtnCategory.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    createCategory();
  });
};

export const initImageUpload = function (
  uploadPrompt,
  mainImageInput,
  mainImageEl,
  deleteImgBtn,
  mediaImgContainer,
) {
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
