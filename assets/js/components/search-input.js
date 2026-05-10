export const searchInput = function (tRows, searchInput, elementSearchFor) {
  const userInput = searchInput.value.trim().toLowerCase();
  tRows.forEach((row) => {
    const productName = row
      .querySelector(`.${elementSearchFor}`)
      .textContent.toLowerCase()
      .trim();

    if (productName.includes(userInput)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
};
