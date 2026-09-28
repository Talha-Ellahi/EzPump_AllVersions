<template>
  <div class="card mt-2">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h2>Tank List</h2>
        <!-- <pre>{{userRole}}</pre> -->
        <a href="/tank/add-tank" class="btn btn-primary" v-if="canAddTank">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <path d="M4 12H20M12 4V20" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"></path>
            </g>
          </svg>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12" v-for="tank in tanks" :key="tank.id">
          <div class="card new_tanks_style mb-3">
            <div class="card-header">Tank Name: {{ tank.tank_name }}</div>
            <div class="row g-0">
              <div class="col-md-5 d-flex align-items-center justify-content-center">
                <div class="rounded-circle position-relative overflow-hidden">
                  <span class="fuel" :class="getFuelLevelClass(tank.fuel_level_percent)"
                    :style="{ height: (tank.fuel_level_percent || 0) + '%' }"></span>
                  <span class="fuel water" :style="{ height: (tank.water_height_percentage || 0) + '%' }"></span>
                </div>
              </div>
              <div class="col-md-7">
                <div class="card-body py-0">
                  <ul>
                    <li>
                      <b>Fuel Type: </b>
                      <span><b>{{ tank.fuel_type }}</b></span>
                    </li>
                    <li>
                      <b>Capacity (L): </b>
                      <span><b>{{ tank.capacity_liters }}</b></span>
                    </li>
                    <li>
                      <b>Current Stock (L): </b>
                      <span><b>{{ tank.stock.stock_value }}</b></span>
                    </li>
                    <li>
                      <b>Temperature (°C): </b>
                      <span><b>{{ tank.temperature }}</b></span>
                    </li>
                    <li>
                      <b>Fuel (mm): </b>
                      <span><b>{{ tank.fuel_height_mm / 100 }}</b></span>
                    </li>
                    <li>
                      <b>Water Height (mm): </b>
                      <span><b>{{ tank.water_height_mm / 100 }}</b></span>
                    </li>
                    <li>
                      <b>Alarms : </b>
                      <span>
                        <span v-if="tank.alarms && tank.alarms.length > 0" class="badge bg-danger">
                          <b>{{ tank.alarms.length }} Alarms</b>
                        </span>
                        <span v-else class="badge bg-success"><b>Normal</b></span></span>
                    </li>
                  </ul>
                </div>

                <div class="card-footer">
                  <div class="d-flex gap-3 justify-content-center w-100">
                    <!-- <a class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                          class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                            d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
                        </svg>
                      </a> -->

                    <a id="up_trend" class="up_trend" @click="openTankHistoryModal(tank.id)">
                      <svg width="16" height="16" viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 120 L60 70 L110 100 L170 30" stroke="black" stroke-width="8" fill="none" />
                        <path d="M170 30 L160 50 M170 30 L190 40" stroke="black" stroke-width="8" fill="none" />
                      </svg>

                    </a>

                    <a class="" @click="addStock(tank.id)" v-if="canAddStock">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#198754"
                        class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                          d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                      </svg>
                    </a>

                    <a v-if="canAddTank" class="" :href="`/tank/edit-tank/${tank.id}`">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ff9800"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                          d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                          d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                      </svg>
                    </a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tank History Modal -->
  <div class="modal fade" id="tankHistoryModal" tabindex="-1" aria-labelledby="tankHistoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl custom-modal-width">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tankHistoryModalLabel">Tank History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div v-if="loading.tankHistory" class="text-center">Loading tank history...</div>
          <div v-else-if="tankHistory.length === 0" class="text-center">No history data available for this tank.</div>
          <div v-else>
            <div id="chart">
              <apexchart type="line" height="350" :options="tankHistoryChartOptions"
                :series="tankHistoryChartSeries"></apexchart>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";
import { getDipChartData } from "../functions";
import ApexCharts from "apexcharts";
import VueApexCharts from "vue3-apexcharts";
import * as bootstrap from 'bootstrap';


export default {
  components: {
    apexchart: VueApexCharts,
  },
  data() {
    return {
      tanks: [],
      selectedTankId: null,
      selectedTank: null,
      tankHistory: [],
      loading: {
        tankHistory: false,
      },
      tankHistoryChartOptions: {
        chart: {
          height: 350,
          type: "line",
          zoom: {
            enabled: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
        },
        title: {
          text: "Tank Stock History",
          align: "left",
        },
        grid: {
          row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
          },
        },
        xaxis: {
          type: "datetime",
          labels: {
            rotate: 0, // Prevent rotation
            formatter: function (value) {
              const date = new Date(value);
              const day = date.getDate();
              const month = date.toLocaleString('default', { month: 'short' });
              const hours = date.getHours().toString().padStart(2, '0');
              const minutes = date.getMinutes().toString().padStart(2, '0');
              return `${day} ${month} ${hours}:${minutes}`;
            },
          },
        },
        yaxis: [
          {
            title: {
              text: "Quantity (L)",
            },
          },
          {
            opposite: true,
            title: {
              text: "Level (mm)",
            },
          },
        ],
      },
      tankHistoryChartSeries: [
        {
          name: "Quantity (L)",
          data: [],
        },
        {
          name: "Level (mm)",
          data: [],
        },
      ],
    };
  },
  computed: {
    userRole() {
      return window.user ? window.user.role : null;
    },
    canAddTank() {
      return window.user?.role !== null && window.user?.role < 4;
    },
    canAddStock() {
      // Check if the user role is less than 7
      // Adjust the condition based on your role system
      return window.user?.role != null && window.user?.role < 7;
    },
  },
  mounted() {
    this.getTanks();
    this.startTimer();
  },

  methods: {
    // Open the tank history modal and fetch data
    async openTankHistoryModal(tankId) {
      this.selectedTankId = tankId;
      await this.fetchTankHistory();
      const tankHistoryModal = new bootstrap.Modal(document.getElementById('tankHistoryModal'));
      tankHistoryModal.show();
    },

    // Fetch tank history data for the selected tank
    async fetchTankHistory() {
      if (!this.selectedTankId) return;

      this.loading.tankHistory = true;
      this.tankHistory = [];
      this.tankHistoryChartSeries[0].data = [];
      this.tankHistoryChartSeries[1].data = [];

      try {
        const response = await axios.get(`/api/tanks/fuel-status?tank_id=${this.selectedTankId}`);
        this.tankHistory = response.data;

        // Find the selected tank details
        this.selectedTank = this.tanks.find(tank => tank.id == this.selectedTankId);

        // Update chart title
        this.tankHistoryChartOptions = {
          ...this.tankHistoryChartOptions,
          title: {
            text: `Tank Stock History - ${this.selectedTank?.tank_name || `Tank ${this.selectedTankId}`}`,
            align: "left"
          }
        };

        // Process data for chart
        if (this.tankHistory.length > 0) {
          const qtyData = [];
          const levelData = [];

          this.tankHistory.forEach(record => {
            const timestamp = new Date(record.tdate).getTime();
            qtyData.push([timestamp, record.Qty / 100]); // Convert to actual liters
            levelData.push([timestamp, record.Level]); // Level in mm
          });

          // Update chart series
          this.tankHistoryChartSeries = [
            {
              name: "Quantity (L)",
              data: qtyData
            },
            {
              name: "Level (mm)",
              data: levelData
            }
          ];
        }
      } catch (error) {
        console.error("Error fetching tank history:", error);
      } finally {
        this.loading.tankHistory = false;
      }
    },

    getFuelLevelClass(level) {
      if (level === null || level === undefined) return "fuel-empty";
      if (level <= 20) return "fuel-critical";
      if (level <= 40) return "fuel-low";
      if (level <= 60) return "fuel-medium";
      if (level <= 80) return "fuel-good";
      return "fuel-full";
    },
    startTimer() {
      setInterval(() => {
        // this.getTanks();
        this.updateFuelStatus();
      }, 3000);
    },
    getTanks() {
      axios
        .get("/api/tanks")
        .then((response) => {
          this.tanks = response.data;
          this.tanks = this.tanks.map((tank) => {
            tank.capacity_liters = parseFloat(tank.capacity_liters);
            return tank;
          });
          this.updateFuelStatus(); // Get initial fuel status
        })
        .catch((error) => {
          Swal.fire("Error!", error.message, "error");
        });
    },

    updateFuelStatus() {
      axios
        .get("/api/tanks/lastfeulstatus")
        .then((response) => {
          const fuelStatus = response.data;
          this.tanks.forEach((tank) => {
            const status = fuelStatus.find((s) => s.TID === tank.id);
            if (status) {
              // Convert fuel Qty to liters
              const fuelInLiters = status.Qty / 100;

              // Update stock value
              if (tank.stock) {
                tank.stock.stock_value = fuelInLiters;
              } else {
                tank.stock = { stock_value: fuelInLiters };
              }

              // Convert water_qty to liters
              const waterInLiters = (status.water_qty ?? 0) / 100;

              // Update other attributes
              tank.temperature = status.TEMP / 100;
              tank.fuel_height_mm = Number(status.Level);
              tank.fuel_level_percent = (fuelInLiters / tank.capacity_liters) * 100;

              tank.water_height_mm = Number(status.water_level ?? 0);
              tank.water_height_percentage = (waterInLiters / tank.capacity_liters) * 100;
            }
          });
        })
        .catch((error) => {
          console.error("Error fetching fuel status:", error);
        });
    },

    addStock(tankId) {
      const swalHtml = `
    <div>
      <label for="stock-change">Stock Change (L):</label>
      <input type="number" id="stock-change" class="swal2-input">
    </div>
    <div>
      <label for="comments">Comments:</label>
      <textarea id="comments" class="swal2-input"></textarea>
    </div>
  `;

      Swal.fire({
        title: "Add Stock",
        html: swalHtml,
        showCancelButton: true,
        confirmButtonText: "Submit",
        focusConfirm: false,
        preConfirm: async () => {
          const stockChange = document.getElementById("stock-change").value;
          const comments = document.getElementById("comments").value;

          if (!stockChange) {
            throw new Error("Invalid stock change value");
          }

          axios
            .post(`/api/tanks/${tankId}/add-stock`, {
              stock_change: stockChange,
              millimeter: 0,
              comments: comments,
            })
            .then(() => {
              Swal.fire("Success!", "Stock added successfully", "success");
            })
            .catch((error) => {
              Swal.fire("Error!", error.message, "error");
            });
        },
      });
    },
  },
};
</script>

<style>
.custom_close_btn {
  background-color: red;
  color: white;
  border-radius: 6px;
  position: fixed;
  top: 10%;
  right: 7%;
  padding: 8px 15px;
  font-size: 18px;
  border: none;
  cursor: pointer;
}
#up_trend {
  cursor: pointer;
  color: #198754;
}
</style>

<style scoped>
.table {
  margin-top: 20px;
}

.badge {
  padding: 0.5em 1em;
}

.custom-modal-width {
  max-width: 80% !important;
}

/* Fuel level color variations */
.fuel-critical {
  background: #ff4444;
}

.fuel-low {
  background: #ffa726;
}

.fuel-medium {
  background: #ffeb3b;
}

.fuel-good {
  background: #66bb6a;
}

.fuel-full {
  background: #00c853;
}

.fuel-empty {
  background: #e0e0e0;
}

.fuel.water {
  background: #2196f3;
}
::v-deep(.apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center) {
   right: 0px !important;
    position: absolute;
    left: 0px;
    top: 317px !important;
    max-height: 175px;
}
</style>
