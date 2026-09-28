
let button = document.getElementById('alert_btn');
let div = document.getElementById('alert_div');

alert_div.style.display = 'none';

document.addEventListener('click', function(e) {
  if (button.contains(e.target)) {
    div.style.display = (div.style.display === 'none' ? 'block' : 'none');
  } else if (!div.contains(e.target)) {
    div.style.display = 'none';
  }
});

// alerts.js

const alertsModule = {
  alerts: [],
  loading: { alerts: true },

  async fetchAlerts() {
    try {
      const response = await fetch("/api/alerts");
      if (!response.ok) throw new Error("Network error");
      this.alerts = await response.json();
    } catch (error) {
      console.error("Error fetching alerts:", error);
      this.alerts = [
        {
          id: 1,
          type: "Warning",
          message: "Tank 1 fuel level low.",
          timestamp: new Date().toISOString(),
        },
        {
          id: 2,
          type: "Error",
          message: "Pump 3 communication failure.",
          timestamp: new Date().toISOString(),
        },
      ];
    } finally {
      this.loading.alerts = false;
      this.renderAlerts();
    }
  },

  renderAlerts() {
    const container = document.getElementById("alert_div");
    if (!container) return;

    if (this.loading.alerts) {
      container.innerHTML = `<div class="text-center text-white">Loading alerts...</div>`;
      return;
    }

    if (this.alerts && this.alerts.length > 0) {
      container.innerHTML = this.alerts
        .map(alert => {
  let bgColor = "";
  switch ((alert.type || "").toLowerCase()) {
    case "warning":
      bgColor = "#ffffcc";
      break;
    case "error":
      bgColor = "#ffcccc";
      break;
    case "info":
      bgColor = "#ccf2ff";
      break;
    case "success":
      bgColor = "#c6efce";
      break;
    // default:
    //   bgColor = "rgba(255,255,255,0.1)";
  }
  return `
    <div class="alert-item text-dark p-2 mb-2 rounded" style="background-color: ${bgColor};">
      <strong>${alert.type}:</strong> ${alert.message}
      <br>
      <small class="text-muted">${new Date(alert.created_at || alert.timestamp).toLocaleString()}</small>
    </div>`;
})

        .join("");
    } else if (this.alerts && this.alerts.error) {
      container.innerHTML = `<div class="alert alert-warning">${this.alerts.error}</div>`;
    } else {
      container.innerHTML = `<p class="text-white">No active alerts.</p>`;
    }
  },
};

// Usage example:
window.onload = () => {
  alertsModule.fetchAlerts();
};
