const btnClose = document.querySelectorAll(".user-profile-btns .btn-close");

btnClose.forEach((btn) => {
  btn.addEventListener("click", () => {
    const card = btn.closest(".user-card");
    const id = card.dataset.id;
    fetch(BASE_URL + "pages/admin/update-seller-status.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: id, status: "inactive" }),
    })
      .then((response) => response.json())
      .then(() => {
        card.remove();
        const badge = document.querySelector(
          ".users-items-content .status-indicator",
        );
        const current = parseInt(badge.textContent);
        badge.textContent = current - 1 + " New";
      });
  });
});

const chartCanvas = document.getElementById("usersChart");

// Initialisation du graph
if (chartCanvas) {
  const ctx = document.getElementById("usersChart").getContext("2d");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: chartLabels,
      datasets: [
        {
          label: "New Users",
          data: chartData,
          backgroundColor: "rgb(26, 35, 126)",
          borderColor: "rgb(149, 163, 238)",
          borderWidth: 2,
          borderRadius: 8,
        },
      ],
    },
  });
}
