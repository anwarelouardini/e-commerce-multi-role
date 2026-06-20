document.addEventListener("DOMContentLoaded", () => {
  // --- 1. LOAD DATA FROM LOCALSTORAGE ---
  // We grab the string from memory, and JSON.parse turns it back into a JavaScript array
  const savedItems = JSON.parse(localStorage.getItem("gaamCart")) || [];

  // We grab the saved math totals (or use 0 if nothing is there)
  const subtotalValue = parseFloat(localStorage.getItem("gaamSubtotal")) || 0;
  const taxValue = parseFloat(localStorage.getItem("gaamTax")) || 0;

  // --- 2. SELECT HTML ELEMENTS ---
  const deliveryCards = document.querySelectorAll(".delivery-card");
  const shippingDisplay = document.getElementById("shipping-display");
  const totalDisplay = document.getElementById("shipping-total");
  const subtotalDisplay = document.getElementById("shipping-subtotal");
  const taxDisplay = document.getElementById("shipping-tax");
  const itemsContainer = document.querySelector(".summary-items-list");

  function formatMoney(amount) {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD",
    }).format(amount);
  }

  // --- 3. GENERATE THE HTML FOR THE SAVED ITEMS ---
  // We clear the container just in case, then loop through our saved items
  itemsContainer.innerHTML = "";

  savedItems.forEach((item) => {
    // Create the HTML for one mini-item using the variables from localStorage
    const itemHTML = `
      <div class="item-mini">
        <div class="item-mini__img">
          <img src="${item.image}" alt="${item.title}">
        </div>
        <div class="item-mini__content">
          <h4>${item.title}</h4>
          <p class="sub-heading">${item.subtitle}</p>
          <div class="item-mini__footer">
            <span>Qty: ${item.quantity}</span>
            <span class="price-small">${formatMoney(item.price)}</span>
          </div>
        </div>
      </div>
    `;
    // Inject it into the container
    itemsContainer.insertAdjacentHTML("beforeend", itemHTML);
  });

  // --- 4. PRINT INITIAL TOTALS TO THE SCREEN ---
  subtotalDisplay.textContent = formatMoney(subtotalValue);
  taxDisplay.textContent = formatMoney(taxValue);

  // We set a default shipping cost of $24.00 because the "Express Courier" card is checked by default in your HTML
  let currentShippingCost = 24.0;
  totalDisplay.textContent = formatMoney(
    subtotalValue + taxValue + currentShippingCost,
  );

  // --- 5. INJECT CART INTO FORM ON SUBMIT ---
  const form = document.querySelector('form[action="history.php"]');
  form.addEventListener("submit", () => {
    const cart = JSON.parse(localStorage.getItem("gaamCart")) || [];
    const hidden = document.createElement("input");
    hidden.type = "hidden";
    hidden.name = "cart_items";
    hidden.value = JSON.stringify(cart);
    form.appendChild(hidden);
  });

  // --- 6. HANDLE DELIVERY CLICKS ---
  deliveryCards.forEach((card) => {
    card.addEventListener("click", () => {
      deliveryCards.forEach((c) => c.classList.remove("delivery-card--active"));
      card.classList.add("delivery-card--active");

      const clickedShippingCost = parseFloat(card.getAttribute("data-cost"));

      if (clickedShippingCost === 0) {
        shippingDisplay.textContent = "Free";
      } else {
        shippingDisplay.textContent = formatMoney(clickedShippingCost);
      }

      // Calculate the new total using the saved Subtotal and Tax from localStorage
      const newTotal = subtotalValue + taxValue + clickedShippingCost;
      totalDisplay.textContent = formatMoney(newTotal);
    });
  });
});
