"use strict";

import { searchInput } from "/e-commerce-multi-role/assets/js/components/search-input.js";

const tRows = document.querySelectorAll(".table-content tr");
const tableContent = document.querySelector(".table-content");
const totalItemsCard = document.querySelectorAll(".total__items");
const totalInventorySeller = document.querySelector(".inventory__price");
const rows = document.querySelectorAll(".table-content tr");
const selectCategoryBtn = document.querySelector(".filter-bar__select");
const filterStockBtnsContainer = document.querySelector(".filter-tabs");
const searchProductInput = document.getElementById("search-product");
const searchBtn = document.getElementById("search-btn");
const deleteProductBtns = document.querySelectorAll(".delete-product-btn");
const editProductBtns = document.querySelectorAll(".edit-product-btn");

const totalItems = tableContent.querySelectorAll("tr").length;

// Update The Total Items Available
const updateTotal = function () {
  totalItemsCard.forEach((item) => {
    item.textContent = totalItems;
  });
};

const getTotalInventoryPrice = function () {
  let totalInventory = 0;
  rows.forEach((row) => {
    totalInventory += Number(
      row.querySelector(".product__price").textContent.trim(),
    );
  });

  return totalInventory;
};

const checkStock = function () {
  rows.forEach((row) => {
    console.log(row.querySelector(".product__qte"));
  });
};

// Categorize the product qty [IN STOCK, LOW STOCK, OUT OF STOCK]
const hasStock = function () {
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

const filterByStock = function (clickedBtn = "all", filter = "all-stock") {
  const btnLabels = filterStockBtnsContainer.querySelectorAll("label");

  const clickedBtnLabel = document.querySelector(
    `label[for="${clickedBtn.id}"]`,
  );

  btnLabels.forEach((btn) => {
    btn.classList.remove("filter-tab--active");
  });

  tRows.forEach((row, i) => {
    if (filter === "all-stock") {
      clickedBtnLabel.classList.add("filter-tab--active");
      row.style.display = "";
    } else if (row.dataset.stock === filter) {
      clickedBtnLabel.classList.add("filter-tab--active");
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
};

// Filter Results by Categories (filter = "": means that all by default)
const filterResults = function (filter = "") {
  tRows.forEach((tRow) => {
    if (filter === "") {
      tRow.style.display = "";
    } else if (tRow.dataset.category === filter) {
      tRow.style.display = "";
    } else {
      tRow.style.display = "none";
    }
  });
};

// Function that will data to the URL
const passDataToURL = function (btns, targetPage) {
  btns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const row = btn.closest("tr");
      const { id, name, image, category, stock, price, qty } = row.dataset;
      window.location.href = `${targetPage}?id=${id}&name=${encodeURIComponent(name)}&image=${encodeURIComponent(image)}&category=${encodeURIComponent(category)}&stock=${encodeURIComponent(stock)}&price=${encodeURIComponent(price)}&qty=${encodeURIComponent(qty)}`;
    });
  });
};

// Retreiving The Total Value of Products
const totalInventory = getTotalInventoryPrice();
totalInventorySeller.textContent = `$${totalInventory.toFixed(2)}`;

selectCategoryBtn.addEventListener("change", (e) => {
  const filter = e.target.value;
  filterResults(filter);
});

updateTotal();

hasStock();

filterStockBtnsContainer.addEventListener("change", (e) => {
  filterByStock(e.target, e.target.dataset.filter);
});

searchBtn.addEventListener(
  "click",
  searchInput(tRows, searchProductInput, "product-name"),
);

// If the use clicks on "Enter" it will trigger the searchProduct()
searchProductInput.addEventListener("keypress", (e) => {
  e.key === "Enter" && searchInput(tRows, searchProductInput, "product-name");
});

// Passing data in the URL for delete-product page
passDataToURL(deleteProductBtns, `${BASE_URL}pages/vendor/delete-product.php`);

// Passing data in the URL for edit-product page
passDataToURL(editProductBtns, `${BASE_URL}pages/vendor/edit-product.php`);
