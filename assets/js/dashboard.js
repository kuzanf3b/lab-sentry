(() => {
  const el = document.getElementById('inventoryChart');
  if (!el) return;
  if (typeof Chart === 'undefined') return;

  const labels = JSON.parse(el.getAttribute('data-labels') || '[]');
  const values = JSON.parse(el.getAttribute('data-values') || '[]');

  const ctx = el.getContext('2d');
  if (!ctx) return;

  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [
        {
          label: 'Total Stok',
          data: values,
          borderColor: '#47B8CF',
          backgroundColor: '#47B8CF',
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
            color: '#E6E8F2',
          },
        },
      },
      scales: {
        x: {
          ticks: { color: '#8F92A8' },
          grid: { color: '#1E1F28' },
        },
        y: {
          ticks: { color: '#8F92A8' },
          grid: { color: '#1E1F28' },
        },
      },
    },
  });
})();
