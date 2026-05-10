const categoryBtn = document.querySelectorAll(".users-list-btns a");
const userBox = document.querySelectorAll(".user-box");
const searchUserInput = document.getElementById("searchUser");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");
const pagesContainer = document.querySelector(".users-pages-links");

let currentPage = 1;
const itemPerPage = 6;

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

  prevBtn.style.visibility = currentPage === 1 ? "hidden" : "visible";
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

prevBtn.addEventListener("click", (e) => {
  e.preventDefault();
  if (currentPage > 1) {
    currentPage--;
    paginate();
  }
});

nextBtn.addEventListener("click", (e) => {
  e.preventDefault();
  currentPage++;
  paginate();
});

// By default all is active
click(categoryBtn[0]);
