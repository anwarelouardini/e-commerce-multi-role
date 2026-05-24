const editAvatarBtn = document.getElementById("editAvatarBtn");
const sellerImg = document.getElementById("sellerImg");
const sellerAvatar = document.getElementById("sellerAvatar");

if (editAvatarBtn && sellerImg && sellerAvatar) {
  editAvatarBtn.addEventListener("click", () => sellerImg.click());

  sellerImg.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => (sellerAvatar.src = e.target.result);
    reader.readAsDataURL(file);
  });
}

function showError(fieldId, message) {
  const el = document.getElementById(fieldId);
  if (el) {
    el.textContent = message;
    el.style.display = "block";
  }
}
function clearError(fieldId) {
  const el = document.getElementById(fieldId);
  if (el) {
    el.textContent = "";
    el.style.display = "none";
  }
}
function clearAllErrors() {
  document.querySelectorAll(".field-error").forEach((el) => {
    el.textContent = "";
    el.style.display = "none";
  });
}
function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function toggleEditModal() {
  const modal = document.getElementById("editModal");
  if (!modal) return;
  modal.classList.toggle("modal--open");
}

document.addEventListener("click", function (e) {
  const modal = document.getElementById("editModal");
  if (modal && e.target === modal) {
    modal.classList.remove("modal--open");
  }
});

// Fermer le modal après succès
window.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("editModal");
  const successMsg = document.querySelector(".msg--success");
  if (modal && successMsg) {
    modal.classList.remove("modal--open");
  }
});
// ── VALIDATION LOGIN ─────────────────────────────────────────
const loginForm = document.getElementById("loginForm");
if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    clearAllErrors();
    let valid = true;

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    if (!email) {
      showError("emailError", "Email requis.");
      valid = false;
    } else if (!isValidEmail(email)) {
      showError("emailError", "Email invalide.");
      valid = false;
    }

    if (!password) {
      showError("passwordError", "Mot de passe requis.");
      valid = false;
    }

    if (!valid) e.preventDefault();
    else {
      const btn = document.getElementById("submitBtn");
      if (btn) {
        btn.textContent = "Signing in…";
        btn.disabled = true;
      }
    }
  });
}

// ── VALIDATION SIGNUP ────────────────────────────────────────
const signupBtn = document.getElementById("signupBtn");
const signupForm = document.getElementById("signupForm");

if (signupBtn && signupForm) {
  // Afficher/masquer le champ Store Name selon le rôle
  const roleSelect = document.getElementById("role");
  const storeNameWrap = document.getElementById("storeNameWrap");
  const storeNameInput = document.getElementById("store_name");

  function toggleStoreField() {
    if (!roleSelect || !storeNameWrap) return;
    const isSeller = roleSelect.value === "2";
    storeNameWrap.style.display = isSeller ? "block" : "none";
    if (storeNameInput) storeNameInput.required = isSeller;
  }
  if (roleSelect) {
    roleSelect.addEventListener("change", toggleStoreField);
    toggleStoreField(); // init
  }

  // Soumettre le formulaire via le bouton extérieur
  signupBtn.addEventListener("click", function () {
    clearAllErrors();
    let valid = true;

    const firstname = document.getElementById("firstname").value.trim();
    const lastname = document.getElementById("lastname").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const role = roleSelect ? roleSelect.value : "3";
    const storeName = storeNameInput ? storeNameInput.value.trim() : "";

    if (!firstname) {
      showError("firstnameError", "Le prénom est requis.");
      valid = false;
    }
    if (!lastname) {
      showError("lastnameError", "Le nom est requis.");
      valid = false;
    }
    if (!email) {
      showError("emailError", "Email requis.");
      valid = false;
    } else if (!isValidEmail(email)) {
      showError("emailError", "Email invalide.");
      valid = false;
    }
    if (!password) {
      showError("passwordError", "Mot de passe requis.");
      valid = false;
    } else if (password.length < 6) {
      showError("passwordError", "Minimum 6 caractères.");
      valid = false;
    }
    if (role === "2" && !storeName) {
      showError("storeNameError", "Le nom de la boutique est requis.");
      valid = false;
    }

    if (valid) {
      signupBtn.textContent = "Creating account…";
      signupBtn.disabled = true;
      signupForm.submit();
    }
  });
}
