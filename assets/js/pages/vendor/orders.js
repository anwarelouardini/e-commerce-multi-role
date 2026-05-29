"use strict";

import { searchInput } from "../../components/search-input.js";

const tBody = document.querySelector(".tbody-orders");
const tRows = document.querySelectorAll(".tbody-orders tr");
const searchCustomerInput = document.getElementById("searchCustomer");
const searchBtn = document.getElementById("searchOrderBtn");
const filterBtnsContainer = document.querySelector(".orders-status");
const totalOrderCard = document.querySelector(".card-total-orders p");
const pendingFulfillmentCard = document.querySelector(
  ".card-pending-fulfillment p",
);
const outDeliveryCard = document.querySelector(".card-out-delivery p");
const monthlyRevenueCard = document.querySelector(".card-price-order p");
const orderStatusSelects = document.querySelectorAll(".select-order__status");

let totalPaymentOrders = 0;
let ordersOutForDelivery = 0;
let pendingFulfillment = 0;
let totalOrders = tRows.length;

const checkEmptyResults = function () {
  const existingMsg = tBody.querySelector(".no-results-msg");
  if (existingMsg) existingMsg.remove();

  const visibleRows = [...tRows].filter((row) => row.style.display !== "none");

  if (visibleRows.length === 0) {
    const tr = document.createElement("tr");
    tr.classList.add("no-results-msg");
    tr.innerHTML = `<td colspan="6" class="t-data--empty">No orders match your filter.</td>`;
    tBody.appendChild(tr);
  }
};

orderStatusSelects.forEach((select) => {
  select.addEventListener("change", () => {
    const id = select.dataset.id;
    const status = select.value;

    updateOrdersStatus(select);

    fetch(`${BASE_URL}pages/vendor/update-order-status.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status }),
    });
  });
});

// This function will show the card data
const showCardData = function () {
  totalOrderCard.textContent = totalOrders;
  pendingFulfillmentCard.textContent = pendingFulfillment;
  outDeliveryCard.textContent = ordersOutForDelivery;
  monthlyRevenueCard.textContent = `$${totalPaymentOrders.toFixed(2)}`;
};

// This function will update the dashboard data
const calculateCardData = function () {
  // Reset values
  pendingFulfillment = 0;
  ordersOutForDelivery = 0;
  totalPaymentOrders = 0;

  tRows.forEach((row) => {
    const orderStatus = row
      .querySelector(".order-status")
      .textContent.toLowerCase()
      .trim();

    const totalPaymentPerUser = Number(
      row.querySelector(".order-price").textContent,
    );

    totalPaymentOrders += totalPaymentPerUser;

    orderStatus === "pending" && pendingFulfillment++;
    orderStatus === "delivered" && ordersOutForDelivery++;
  });
  showCardData();
};

calculateCardData();

const updateOrdersStatus = function (select) {
  const row = select.closest("tr");
  const statusBadge = row.querySelector(".order-status");
  const selectedValue = select.value;
  statusBadge.textContent = selectedValue;

  calculateCardData();

  // Remove all the existing style
  statusBadge.classList.remove(
    "status-indicator--green",
    "status-indicator--yellow",
    "status-indicator--red",
    "status-indicator--blue",
    "status-indicator--brown",
  );

  console.log(selectedValue);

  if (selectedValue === "shipped") {
    statusBadge.classList.add("status-indicator--blue");
  } else if (selectedValue === "delivered") {
    statusBadge.classList.add("status-indicator--green");
  } else if (selectedValue === "cancelled") {
    statusBadge.classList.add("status-indicator--red");
  } else if (selectedValue === "pending") {
    statusBadge.classList.add("status-indicator--yellow");
  } else if (selectedValue === "processing") {
    statusBadge.classList.add("status-indicator--brown");
  }

  row.dataset.orders = selectedValue;
};

// Update Dataset
const updateOrdersDataSet = function () {
  tRows.forEach((row) => {
    row.dataset.orders = row
      .querySelector(".order-status")
      .textContent.trim()
      .toLowerCase();
  });
};

// Filter by order status
const filterByStatus = function (clickedBtn, filter = "all-orders") {
  const btnLabels = filterBtnsContainer.querySelectorAll("label");
  const clickedBtnLabel = document.querySelector(
    `label[for="${clickedBtn.id}"]`,
  );

  btnLabels.forEach((btn) => btn.classList.remove("filter-tab--active"));
  clickedBtnLabel.classList.add("filter-tab--active");

  tRows.forEach((row) => {
    if (filter === "all-orders") {
      row.style.display = "";
    } else if (row.dataset.orders === filter) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
};

updateOrdersDataSet();
filterByStatus(document.getElementById("allOrders"), "all-orders");

filterBtnsContainer.addEventListener("change", (e) => {
  filterByStatus(e.target, e.target.dataset.filter);
  checkEmptyResults();
});

searchBtn.addEventListener("click", () => {
  searchInput(tRows, searchCustomerInput, "order-customer", checkEmptyResults);
});

searchCustomerInput.addEventListener("keypress", (e) => {
  if (e.key === "Enter")
    searchInput(
      tRows,
      searchCustomerInput,
      "order-customer",
      checkEmptyResults,
    );
});

// Listen to select order status
tBody.addEventListener("change", (e) => {
  e.target.matches(".filter-bar__select--grey") && updateOrdersStatus(e.target);
});

const chartCanvas = document.getElementById("orderChart");

// Initialisation du graph
if (chartCanvas) {
  const ctx = document.getElementById("orderChart").getContext("2d");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: chartLabels,
      datasets: [
        {
          label: "Orders",
          data: chartData,
          backgroundColor: "rgba(67, 97, 238, 0.3)",
          borderColor: "rgba(67, 97, 238, 1)",
          borderWidth: 2,
          borderRadius: 8,
        },
      ],
    },
  });
}
