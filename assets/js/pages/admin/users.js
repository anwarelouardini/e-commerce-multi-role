const categoryBtn = document.querySelectorAll(".users-list-btns a");
const userBox = document.querySelectorAll(".user-box");
const searchUserInput = document.getElementById("searchUser");

const click = function (clickedBtn, filter = "all") {
  categoryBtn.forEach((btn) => {
    btn.classList.remove("btn--active");
  });

  clickedBtn.classList.add("btn--active");

  userBox.forEach((box) => {
    if (filter === "all") {
      box.classList.remove("hide");
    } else if (box.dataset.role === filter) {
      box.classList.remove("hide");
    } else {
      box.classList.add("hide");
    }
  });
};

const searchUser = function (userInput) {
  userBox.forEach((box) => {
    const username = box.querySelector(".username").textContent.toLowerCase();

    if (username.includes(userInput)) {
      box.style.display = "";
    } else {
      box.style.display = "none";
    }
  });
};

categoryBtn.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const filter = btn.dataset.filter;
    click(btn, filter);
  });
});

// By default all is active
click(categoryBtn[0]);

searchUserInput.addEventListener("keyup", (e) =>
  searchUser(e.target.value.toLowerCase().trim()),
);
