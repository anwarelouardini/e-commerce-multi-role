document.addEventListener("DOMContentLoaded", () => {
  const STORAGE_KEY = "gaamCart";
  const TAX_RATE = 0.08;
  const SHIPPING = 45.0;

  const container = document.getElementById("cart-items-container");
  const subtotalEl = document.getElementById("subtotal");
  const taxEl = document.getElementById("tax");
  const totalEl = document.getElementById("total");

  // ── Helpers ────────────────────────────────────────────────────────────────
  function fmt(n) {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",
    }).format(n);
  }

  function loadCart() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
  }

  // ── Recalculer et afficher le résumé ───────────────────────────────────────
  function updateSummary(cart) {
    let subtotal = 0;
    cart.forEach((item) => {
      subtotal += item.price * item.quantity;
    });

    const tax = subtotal * TAX_RATE;
    const ship = subtotal > 0 ? SHIPPING : 0;
    const total = subtotal + tax + ship;

    subtotalEl.textContent = fmt(subtotal);
    taxEl.textContent = fmt(tax);
    totalEl.textContent = fmt(total);

    // ← AJOUTER CES 3 LIGNES
    localStorage.setItem("gaamCartItems", JSON.stringify(cart));
    localStorage.setItem("gaamSubtotal", subtotal);
    localStorage.setItem("gaamTax", tax);
  }

  // ── Générer le HTML d'une card produit ────────────────────────────────────
  function buildCard(item) {
    const div = document.createElement("section");
    div.className = "card-shop";
    div.dataset.productId = item.id;
    div.innerHTML = `
      <div class="img-design">
        <img src="${item.image}" alt="${item.title}"
             onerror="if(!this.dataset.e){this.dataset.e=1;this.style.opacity='0.3';}">
      </div>
      <div class="contenu-card">
        <div class="top-content">
          <div class="text-content">
            <h2>${item.title}</h2>
            <span class="sub-heading">${item.subtitle}</span>
          </div>
          <div class="price" data-price="${item.price}">
            ${fmt(item.price)}
          </div>
        </div>
        <div class="bottom-content">
          <div class="quantity">
            <button class="btn-minus">-</button>
            <span class="qty-display">${item.quantity}</span>
            <button class="btn-plus">+</button>
          </div>
          <button class="btn-remove">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
              <line x1="10" y1="11" x2="10" y2="17"></line>
              <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
            REMOVE
          </button>
        </div>
      </div>
    `;

    // ── Events sur la card ──────────────────────────────────────────────────
    const qtyEl = div.querySelector(".qty-display");

    div.querySelector(".btn-plus").addEventListener("click", () => {
      const cart = loadCart();
      const found = cart.find((i) => i.id === item.id);
      if (found) {
        found.quantity++;
        qtyEl.textContent = found.quantity;
        saveCart(cart);
        updateSummary(cart);
      }
    });

    div.querySelector(".btn-minus").addEventListener("click", () => {
      const cart = loadCart();
      const found = cart.find((i) => i.id === item.id);
      if (found && found.quantity > 1) {
        found.quantity--;
        qtyEl.textContent = found.quantity;
        saveCart(cart);
        updateSummary(cart);
      }
    });

    div.querySelector(".btn-remove").addEventListener("click", () => {
      const cart = loadCart().filter((i) => i.id !== item.id);
      saveCart(cart);
      div.remove();
      updateSummary(cart);
      if (cart.length === 0) showEmpty();
    });

    return div;
  }

  // ── Afficher panier vide ───────────────────────────────────────────────────
  function showEmpty() {
    container.innerHTML =
      '<div style="padding:40px;text-align:center;font-size:1.6rem;"><p>Your cart is currently empty.</p></div>';
  }

  // ── Render initial ─────────────────────────────────────────────────────────
  const cart = loadCart();

  if (cart.length === 0) {
    showEmpty();
  } else {
    container.innerHTML = "";
    cart.forEach((item) => container.appendChild(buildCard(item)));
  }

  updateSummary(cart);
});
