const categoryBtn = document.querySelectorAll(".users-list-btns a");
const userBox = document.querySelectorAll(".user-box");
const searchUserInput = document.getElementById("searchUser");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const pagesContainer = document.querySelector(".users-pages-links");
const userBoxContainer = document.querySelector(".user-box-container");
const approveBtns = document.querySelectorAll(".approve-btn");

let currentPage = 1;
const itemPerPage = 6;

approveBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const card = btn.closest(".user-box");
    const id = card.querySelector("a.delete-btn").href.split("id=")[1];

    fetch(`${BASE_URL}pages/admin/update-seller-status.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id, status: "active" }),
    }).then(() => {
      card.remove();
      paginate();
    });
  });
});

const checkEmptyResults = function () {
  const existingMsg = userBoxContainer.querySelector(".no-results-msg");

  if (existingMsg) existingMsg.remove();

  const visibleCards = [...userBox].filter(
    (box) => !box.classList.contains("filtered-out"),
  );

  if (visibleCards.length === 0) {
    const msg = document.createElement("p");
    msg.classList.add("no-results-msg");
    msg.classList.add("no-results-msg");
    msg.textContent = "No users match your filter";
    userBoxContainer.appendChild(msg);
  }
};

const renderPageBtns = function (totalPages) {
  pagesContainer.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("a");
    btn.href = "#";
    btn.classList.add("btn-pages");
    btn.textContent = i;

    if (i === currentPage) btn.classList.add("btn--active");

    btn.addEventListener("click", (e) => {
      e.preventDefault();
      currentPage = i;
      paginate();
    });

    pagesContainer.appendChild(btn);
  }
};

const paginate = function () {
  // Checking the cards that contains hide
  const eligibleCards = [...userBox].filter(
    (box) => !box.classList.contains("filtered-out"),
  );

  // Checking the total pages
  const totalPages = Math.ceil(eligibleCards.length / itemPerPage);
  const start = (currentPage - 1) * itemPerPage;
  const end = currentPage * itemPerPage;

  userBox.forEach((box) => box.classList.add("hide"));
  eligibleCards
    .slice(start, end)
    .forEach((box) => box.classList.remove("hide"));

  if (prevBtn)
    prevBtn.style.visibility = currentPage === 1 ? "hidden" : "visible";
  if (nextBtn)
    nextBtn.style.visibility =
      currentPage === totalPages || totalPages === 0 ? "hidden" : "visible";

  renderPageBtns(totalPages);
};

const click = function (clickedBtn, filter = "all") {
  categoryBtn.forEach((btn) => {
    btn.classList.remove("btn--active");
  });

  clickedBtn.classList.add("btn--active");

  userBox.forEach((box) => {
    if (filter === "all" || box.dataset.role === filter) {
      box.classList.remove("filtered-out");
    } else {
      box.classList.add("filtered-out");
    }
  });

  currentPage = 1;
  paginate();
  checkEmptyResults();
};

const searchUser = function (userInput) {
  userBox.forEach((box) => {
    const username = box.querySelector(".username").textContent.toLowerCase();

    if (username.includes(userInput)) {
      box.classList.remove("filtered-out");
    } else {
      box.classList.add("filtered-out");
    }
  });

  currentPage = 1;
  paginate();
  checkEmptyResults();
};

categoryBtn.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const filter = btn.dataset.filter;
    click(btn, filter);
  });
});

searchUserInput.addEventListener("keyup", (e) =>
  searchUser(e.target.value.toLowerCase().trim()),
);

if (prevBtn) {
  prevBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentPage > 1) {
      currentPage--;
      paginate();
    }
  });
}

if (nextBtn) {
  nextBtn.addEventListener("click", (e) => {
    e.preventDefault();
    currentPage++;
    paginate();
  });
}

// By default all is active
click(categoryBtn[0]);
