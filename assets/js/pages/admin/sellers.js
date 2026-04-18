const tbody = document.querySelector(".sellers-table__body");
const sortBtn = document.getElementById("sortBtn");
const sortBtnTxt = document.querySelector(".sort-btn__txt");
const tableExport = document.getElementById("tableExport");
const userStatus = document.querySelectorAll(".user--status");
const totalSellersCard = document.getElementById("totalSellers");
const activeSellersCard = document.getElementById("activeSellers");
const suspendedSellersCard = document.getElementById("suspendedSellers");
const storeSearchInput = document.getElementById("storeSearch");

let isSorted = false;
let suspendedSellers = 0;
let activeSellers = 0;
const totalSellers = tbody.querySelectorAll("tr").length;

const storeAvatar = document.querySelectorAll(".store-avatar");
const originalRows = Array.from(tbody.querySelectorAll("tr"));

const populateAvatars = function () {
  const rows = tbody.querySelectorAll("tr");

  rows.forEach((row) => {
    const storeTag = row.querySelector(".store__tag");
    const avatar = row.querySelector(".store-avatar");

    if (storeTag && avatar) {
      avatar.textContent = storeTag.textContent.trim().charAt(0).toUpperCase();
    }
  });
};

// Sorting The Table
const sortByName = function () {
  const rows = Array.from(tbody.querySelectorAll("tr"));

  if (!isSorted) {
    rows.sort((a, b) => {
      const name1 = a.querySelector(".store__tag").textContent.toLowerCase();
      const name2 = b.querySelector(".store__tag").textContent.toLowerCase();

      return name1.localeCompare(name2);
    });

    rows.forEach((row) => tbody.appendChild(row));
    isSorted = true;
    sortBtnTxt.textContent = "Reset";
  } else {
    originalRows.forEach((row) => tbody.appendChild(row));
    isSorted = false;
    sortBtnTxt.textContent = "Sort";
  }
};

// Export The Table
const exportTableToCSV = function () {
  const rows = document.querySelectorAll(".sellers-table tr");
  let csv = [];

  rows.forEach((row) => {
    const cols = row.querySelectorAll("th, td");
    let rowData = [];

    cols.forEach((col, index) => {
      // Skip avatar (0) and actions (last) columns
      if (index !== 0 && index !== cols.length - 1) {
        rowData.push(col.innerText.trim());
      }
    });

    if (rowData.length > 0) {
      csv.push(rowData.join(","));
    }
  });

  const csvString = csv.join("\n");
  const blob = new Blob([csvString], { type: "text/csv" });
  const url = window.URL.createObjectURL(blob);

  const a = document.createElement("a");
  a.href = url;
  a.download = `sellers.csv`;
  a.click();

  window.URL.revokeObjectURL(url);
};

// Getting The Total Number of Users by Category
const getUsersNumber = function (userStatus) {
  userStatus.forEach((stats) => {
    stats.textContent === "Active" ? activeSellers++ : suspendedSellers++;
  });
};

// Update Admin Sellers Statistics
const updateStatistics = function () {
  totalSellersCard.textContent = totalSellers;
  activeSellersCard.textContent = activeSellers;
  suspendedSellersCard.textContent = suspendedSellers;
};

// Toggle Sellers Status
const toggleSellerStatus = function (row, newStatus, newColor) {
  const sellerStatus = row.querySelector(".user--status");
  const suspendBtn = row.querySelector(".btn-icon--suspend");
  const reactivateBtn = row.querySelector(".btn-approve");

  // Change status text and color
  sellerStatus.textContent = newStatus;
  sellerStatus.classList.remove(
    "status-indicator--green",
    "status-indicator--red",
  );
  sellerStatus.classList.add(newColor);

  if (newStatus === "Suspended") {
    suspendBtn.classList.add("hide");
    reactivateBtn.classList.remove("hide");
    suspendedSellers++;
    activeSellers--;
  } else {
    suspendBtn.classList.remove("hide");
    reactivateBtn.classList.add("hide");
    activeSellers++;
    suspendedSellers--;
  }

  updateStatistics();
};

const suspendSeller = function (e) {
  const suspendBtn = e.target.closest(".btn-icon--suspend");
  if (suspendBtn) {
    const row = suspendBtn.closest("tr");
    toggleSellerStatus(row, "Suspended", "status-indicator--red");
  }
};

const reactivateSeller = function (e) {
  const reactivateBtn = e.target.closest(".btn-approve");
  if (reactivateBtn) {
    const row = reactivateBtn.closest("tr");
    toggleSellerStatus(row, "Active", "status-indicator--green");
  }
};

// Search By Store Name
const searchByStoreName = function (userInput) {
  const searchItem = userInput.toLowerCase().trim();
  const rows = tbody.querySelectorAll("tr");

  rows.forEach((row) => {
    const storeName = row
      .querySelector(".store__tag")
      .textContent.toLowerCase();
    if (storeName.includes(searchItem)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
};

// Makes The Avatar The First Letter Of Store Name
populateAvatars();

sortBtn.addEventListener("click", sortByName);

tableExport.addEventListener("click", exportTableToCSV);

getUsersNumber(userStatus);

updateStatistics();

storeSearchInput.addEventListener("keyup", (e) =>
  searchByStoreName(e.target.value),
);

tbody.addEventListener("click", (e) => {
  suspendSeller(e);
  reactivateSeller(e);
});
