(() => {
  if (typeof Chart === "undefined") return;

  const trendEl = document.getElementById("inventoryChart");
  if (trendEl) {
    const labels = JSON.parse(trendEl.getAttribute("data-labels") || "[]");
    const values = JSON.parse(trendEl.getAttribute("data-values") || "[]");
    const ctx = trendEl.getContext("2d");

    if (ctx) {
      new Chart(ctx, {
        type: "line",
        data: {
          labels,
          datasets: [
            {
              label: "Total Stok",
              data: values,
              borderColor: "#47B8CF",
              backgroundColor: "rgba(71,184,207,.15)",
              fill: true,
              tension: 0.35,
              pointRadius: 3,
              pointHoverRadius: 5,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              labels: {
                color: "#E6E8F2",
              },
            },
          },
          scales: {
            x: {
              ticks: { color: "#8F92A8" },
              grid: { color: "#1E1F28" },
            },
            y: {
              ticks: { color: "#8F92A8" },
              grid: { color: "#1E1F28" },
            },
          },
        },
      });
    }
  }

  const pieEl = document.getElementById("conditionPie");
  if (pieEl) {
    const labels = JSON.parse(pieEl.getAttribute("data-labels") || "[]");
    const values = JSON.parse(pieEl.getAttribute("data-values") || "[]");
    const ctx = pieEl.getContext("2d");
    if (!ctx) return;

    new Chart(ctx, {
      type: "pie",
      data: {
        labels,
        datasets: [
          {
            data: values,
            backgroundColor: ["#A6E3A1", "#F4D07E", "#F16C93"],
            borderColor: "#1E1F28",
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
            labels: { color: "#E6E8F2" },
          },
        },
      },
    });
  }
})();
