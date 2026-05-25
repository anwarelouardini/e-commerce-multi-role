document.addEventListener('DOMContentLoaded', () => {

  const TAX_RATE = 0.08; 
  const SHIPPING_COST = 45.00;

  const subtotalElement = document.getElementById('subtotal');
  const taxElement = document.getElementById('tax');
  const totalElement = document.getElementById('total');

  function formatMoney(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
  }

  function updateOrderSummary() {
    const currentCards = document.querySelectorAll('.card-shop');
    let newSubtotal = 0;
    
    // NEW: Create an empty array to hold our cart items for localStorage
    let cartMemory = []; 

    currentCards.forEach(card => {
      const priceString = card.querySelector('.price').getAttribute('data-price');
      const itemPrice = parseFloat(priceString);
      const currentQty = parseInt(card.querySelector('.qty-display').textContent);
      
      newSubtotal += (itemPrice * currentQty);

      // NEW: Gather the details of this specific product
      const itemTitle = card.querySelector('h2').textContent;
      const itemSubtitle = card.querySelector('.sub-heading').textContent;
      const itemImage = card.querySelector('img').getAttribute('src');

      // NEW: Push those details into our memory array
      cartMemory.push({
        title: itemTitle,
        subtitle: itemSubtitle,
        image: itemImage,
        price: itemPrice,
        quantity: currentQty
      });
    });

    const taxAmount = newSubtotal * TAX_RATE;
    const currentShipping = newSubtotal > 0 ? SHIPPING_COST : 0; 
    const finalTotal = newSubtotal + taxAmount + currentShipping;

    subtotalElement.textContent = formatMoney(newSubtotal);
    taxElement.textContent = formatMoney(taxAmount);
    totalElement.textContent = formatMoney(finalTotal);

    // NEW: Save everything into the browser's localStorage!
    // JSON.stringify turns our array into a text string the browser can store.
    localStorage.setItem('gaamCartItems', JSON.stringify(cartMemory));
    localStorage.setItem('gaamSubtotal', newSubtotal);
    localStorage.setItem('gaamTax', taxAmount);
  }

  const productCards = document.querySelectorAll('.card-shop');

  productCards.forEach((card) => {
    const btnMinus = card.querySelector('.btn-minus');
    const btnPlus = card.querySelector('.btn-plus');
    const btnRemove = card.querySelector('.btn-remove');
    const qtyDisplay = card.querySelector('.qty-display');
    
    btnPlus.addEventListener('click', () => {
      let quantity = parseInt(qtyDisplay.textContent);
      quantity++;
      qtyDisplay.textContent = quantity;
      updateOrderSummary();
    });

    btnMinus.addEventListener('click', () => {
      let quantity = parseInt(qtyDisplay.textContent);
      if (quantity > 1) {
        quantity--;
        qtyDisplay.textContent = quantity;
        updateOrderSummary();
      }
    });

    btnRemove.addEventListener('click', () => {
      card.remove();
      updateOrderSummary();
    });
  });

  updateOrderSummary();

});