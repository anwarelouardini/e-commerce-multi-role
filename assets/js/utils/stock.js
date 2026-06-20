const tRows = document.querySelectorAll(".table-content tr");

export const hasStock = function () {
  tRows.forEach((row) => {
    const productQty = Number(row.querySelector(".product__qte").textContent);
    const qtyStatus = row.querySelector(".qte__status");
    qtyStatus.classList.remove(
      "status-indicator--green",
      "status-indicator--red",
      "status-indicator--yellow",
    );

    if (productQty >= 10) {
      row.dataset.stock = "in-stock";
      qtyStatus.classList.add("status-indicator--green");
      qtyStatus.textContent = "In Stock";
    } else if (productQty >= 1) {
      row.dataset.stock = "low-stock";
      qtyStatus.classList.add("status-indicator--yellow");
      qtyStatus.textContent = "Low Stock";
    } else {
      row.dataset.stock = "out-stock";
      qtyStatus.classList.add("status-indicator--red");
      qtyStatus.textContent = "Out of stock";
    }
  });
};
