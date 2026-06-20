import { hasStock } from "../../utils/stock.js";

hasStock();

const ctx = document.getElementById("salesChart").getContext("2d");
const volumeBtn = document.getElementById("volumeBtn");
const revenueBtn = document.getElementById("revenueBtn");

const salesChart = new Chart(ctx, {
  type: "bar",
  data: {
    labels: volumeLabels,
    datasets: [
      {
        label: "Volume",
        data: volumeData,
        backgroundColor: "rgba(67, 97, 238, 0.3)",
        borderColor: "rgba(67, 97, 238, 1)",
        borderWidth: 2,
        borderRadius: 8,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
  },
});

volumeBtn.addEventListener("click", (e) => {
  e.preventDefault();
  salesChart.data.labels = volumeLabels;
  salesChart.data.datasets[0].data = volumeData;
  salesChart.data.datasets[0].label = "Volume";
  salesChart.update();
  volumeBtn.classList.add("btn--active-2");
  revenueBtn.classList.remove("btn--active-2");
});

revenueBtn.addEventListener("click", (e) => {
  e.preventDefault();
  salesChart.data.labels = revenueLabels;
  salesChart.data.datasets[0].data = revenueData;
  salesChart.data.datasets[0].label = "Revenue ($)";
  salesChart.update();
  revenueBtn.classList.add("btn--active-2");
  volumeBtn.classList.remove("btn--active-2");
});
