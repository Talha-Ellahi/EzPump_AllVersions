<template>
  <div class="container">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <h1 style="font-size: 28px; font-weight: 800; margin: 0; padding: 0;">Integrated Dashboard</h1>
      <div style="display: flex; gap: 10px;">
        <!-- <button class="btn btn-primary" @click="Fueltank">Fuel Tank</button> -->
        <button class="btn btn-primary" @click="Sales">Sales Page</button>
        <button class="btn btn-primary" @click="ShowTanks">Fuel Tank Sales</button>
      </div>
    </div>

    <!-- Page 1 -->
    <!-- 
    <div v-if="showPage === 'FuelTank'" style="margin-top: 15px !important;">
      <ShiftChange2 />
    </div> -->

    <!-- Fuel Tank Sales -->

    <div v-if="showPage === 'ShowTanks'" style="margin-top: 15px !important;">
      <div class="row" style="max-height: 500px; overflow-y: hidden">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Key Statistics</div>
            <div class="card-body">
              <div v-if="loading.sales || loading.stock" class="text-center">
                Loading stats...
              </div>
              <div v-else class="row text-center">
                <div class="col">
                  <div class="stat-value">{{ totalSalesAmount || "N/A" }}</div>
                  <div class="stat-label">Total Sales (PKR)</div>
                </div>
                <div class="col">
                  <div class="stat-value">{{ totalCurrentStock || "N/A" }}</div>
                  <div class="stat-label">Total Stock (L)</div>
                </div>
                <div class="col">
                  <div class="stat-value">{{ stockStats ? stockStats.length : "N/A" }}</div>
                  <div class="stat-label">Active Tanks</div>
                </div>
              </div>

              <!-- Product Combined Sales -->
              <div v-if="salesStats && salesStats.productWiseSales && salesStats.productWiseSales.length > 0"
                class="mt-3">
                <h6 class="text-center mb-2">Product Combined Sales</h6>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th class="text-end">Qty (Ltr)</th>
                        <!-- <th class="text-end">Amount (Rs.)</th> -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(product, index) in productStatsRows" :key="index">
                        <td>{{ product.name }}</td>
                        <td class="text-end">{{ product.qty }}</td>
                        <!-- <td class="text-end">{{ product.amount }}</td> -->
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Tank Stock -->
              <div v-if="stockStats && stockStats.length > 0" class="mt-3">
                <h6 class="text-center mb-2">Tank Stock</h6>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th>Tank</th>
                        <th class="text-end">Dip (mm)</th>
                        <th class="text-end">Qty (Ltr)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="tank in stockStats" :key="tank.TID">
                        <td>{{ tank.TankName || `Tank ${tank.TID}` }}</td>
                        <td class="text-end">{{ tank.Level / 100 }}</td>
                        <td class="text-end">{{ (tank.Qty / 100).toFixed(2) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div v-if="salesStats && salesStats.error" class="alert alert-warning mt-2">
                {{ salesStats.error }}
              </div>
              <div v-if="stockStats && stockStats.error" class="alert alert-warning mt-2">
                {{ stockStats.error }}
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">Alerts</div>
            <div class="card-body" style="max-height: 450px; overflow-y: auto">
              <div v-if="loading.alerts" class="text-center">Loading alerts...</div>
              <div v-else-if="alerts && alerts.length > 0">
                <div v-for="alert in alerts" :key="alert.id" class="alert-item"
                  :class="`alert-${alert.type?.toLowerCase()}`">
                  <strong>{{ alert.type }}:</strong> {{ alert.message }}
                  <small class="d-block text-muted">{{
                    new Date(alert.created_at || alert.timestamp).toLocaleString()
                  }}</small>
                </div>
              </div>
              <div v-else-if="alerts && alerts.error" class="alert alert-warning">
                {{ alerts.error }}
              </div>
              <p v-else>No active alerts.</p>
            </div>
          </div>
        </div>

      </div>
      <TankList2 />
    </div>






    <!-- Nozzle wise sales -->
    <div style="display: flex; justify-content: space-between; gap: 10px;" v-if="showPage === 'ShowSalesPage'">
      <div style="width: 100%;">
        <div class="card mt-4" style="max-height: 450px; height: 450px">
          <div class="card-header">Nozzle-wise Sales</div>
          <div class="card-body">
            <div v-if="loading.nozzleSales" class="text-center">Loading nozzle sales data... </div>
            <div v-else-if="nozzleSales.nozzleSales && nozzleSales.nozzleSales.length > 0">
              <div class="mb-3">
                <label for="nozzleSalesDatePicker" class="form-label">Select Date:</label>
                <input type="date" id="nozzleSalesDatePicker" class="form-control" v-model="nozzleSalesDate"
                  @change="fetchNozzleSales">
              </div>
              <apexchart type="bar" height="350" :options="nozzleSalesChartOptions" :series="nozzleSalesChartSeries">
              </apexchart>
            </div>
            <div v-else class="text-center">
              <p class="custom_p">No nozzle sales data available for the selected date. {{ showPage }}</p>
            </div>
          </div>
        </div>

        <div class="card mt-4" style="max-height: 500px; height: 500px; margin-top: 12px !important;">
          <!-- Hourly Sales -->
          <div class="card-header">Hourly Sales</div>
          <div class="card-body">
            <div v-if="loading.hourlySales" class="text-center">Loading hourly sales data...</div>
            <div v-else-if="hourlySales.hours && hourlySales.hours.length > 0">
              <div class="mb-3">
                <label for="salesDatePicker" class="form-label custom_p">Select Date:</label>
                <input type="date" id="salesDatePicker" class="form-control" style="font-size: 19px;"
                  v-model="salesDate" @change="fetchHourlySales">
              </div>
              <apexchart type="bar" height="350" :options="hourlySalesChartOptions" :series="hourlySalesChartSeries">
              </apexchart>
            </div>
            <div v-else class="text-center">
              <p>No hourly sales data available for the selected date.</p>
            </div>
          </div>
        </div>
      </div>
      <!-- Product Sales Distribution -->
      <div class="card mt-4" style="width: 30%;">
        <div class="card-header">Product Sales Distribution</div>
        <div class="card-body" style="padding: 0px;">
          <div v-if="loading.sales" class="text-center">Loading sales stats...</div>
          <div v-else-if="salesStats && salesStats.error" class="alert alert-warning">
            {{ salesStats.error }}
          </div>
          <div class="custom_div1" v-else-if="productStatsRows.length > 0">
            <!-- ApexCharts Placeholders -->
            <div class="row mt-4" style="display: flex; flex-direction: column; gap: 40px; height: 100%;">
              <div class="col-md-6" style="width: 100%;">
                <apexchart type="pie" height="300" :options="productChartOptions" :series="productChartSeries">
                </apexchart>
              </div>
              <div class="col-md-6" style="width: 100%;">
                <apexchart type="pie" height="300" :options="paymentChartOptions" :series="paymentChartSeries">
                </apexchart>
              </div>
            </div>
          </div>
          <p v-else>No sales statistics available.</p>
        </div>
      </div>
    </div>
  </div>
q
</template>

<style>
/* html{
  overflow-y: hidden!important;
} */
/* body {
  min-width: 2304px !important;
  min-height: 1134px !important;
} */
.py-4 {
  margin: 0px !important;
  padding: 10px 0px !important;
}
</style>

<style scoped>


::v-deep(.apexcharts-canvas) {
  height: 300px !important;
}

.custom_div1 {
  height: 100% !important;
}

.vue-apexcharts {
  width: 100% !important;
  height: auto !important;
  font-size: 20px !important;
}

.custom_p {
  font-size: 17px;
}

.alert-item {
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s, box-shadow 0.3s;
}

.alert-item:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.alert-success {
  background-color: #c6efce;
  border-left: 5px solid #00ff00;
}

.alert-warning {
  background-color: #ffffcc;
  border-left: 5px solid #ffff00;
}

.alert-error {
  background-color: #ffcccc;
  border-left: 5px solid #ff0000;
}

.alert-info {
  background-color: #ccf2ff;
  border-left: 5px solid #00bfff;
}

.card-header {
  text-align: center;
  font-size: 17px;
  font-weight: 800;
}

::v-deep(.apexcharts-text),
::v-deep(.apexcharts-legend-text) {
  font-weight: 600 !important;
}

::v-deep(.apexcharts-legend) {
  right: auto !important;
  top: 40px !important;
}
</style>

<script>
import TankList from "./TankList.vue";
import TankList2 from "./TankList2.vue";
import axios from "axios";
import VueApexCharts from "vue3-apexcharts";
import ShiftChange from "./ShiftChange.vue";
import ShiftChange2 from "./ShiftChange2.vue";

export default {
  name: 'example-dashboard',// Import ApexCharts
  components: {
    TankList,
    TankList2,
    ShiftChange,
    ShiftChange2,
    apexchart: VueApexCharts, // Register ApexCharts locally
  },
  data() {
    return {
      tankStockHistory: [],
      salesStats: null, // To store overall sales statistics
      stockStats: null, // To store overall stock statistics
      alerts: [], // To store alerts
      showPage: 'ShowTanks', // Default page to show
      // Tank history data
      selectedTankId: null,
      selectedTank: null,
      tankHistory: [],

      // Hourly sales data
      salesDate: new Date().toISOString().split('T')[0], // Today's date in YYYY-MM-DD format
      hourlySales: { hours: [], quantities: [], amounts: [], transactions: [] },

      // Nozzle sales data
      nozzleSalesDate: new Date().toISOString().split('T')[0], // Today's date in YYYY-MM-DD format
      nozzleSales: { nozzleSales: [] },

      loading: {
        sales: false,
        stock: false,
        alerts: false,
        history: false,
        tankHistory: false,
        hourlySales: false,
        nozzleSales: false,
      },

      // Processed data from fetchSalesStats
      productStatsRows: [],

      // Tank History Chart Options
      tankHistoryChartOptions: {
        chart: {
          id: "tank-history-chart",
          type: "line",
          zoom: {
            enabled: true
          }
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        title: {
          text: "Tank Stock History",
          align: "left"
        },
        xaxis: {
          type: 'datetime',
          labels: {
            datetimeUTC: false,
            format: 'dd MMM HH:mm'
          }
        },
        yaxis: [
          {
            title: {
              text: "Quantity (Liters)"
            }
          },
          {
            opposite: true,
            title: {
              text: "Level (mm)"
            }
          }
        ],
        tooltip: {
          x: {
            format: 'dd MMM yyyy HH:mm'
          }
        },
        legend: {
          position: 'top'
        }
      },
      tankHistoryChartSeries: [
        {
          name: "Quantity (L)",
          data: []
        },
        {
          name: "Level (mm)",
          data: []
        }
      ],
      hourlySalesChartOptions: {
        chart: {
          id: "hourly-sales-chart",
          type: "bar",

          stacked: false
        },

        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '70%',
            dataLabels: {
              enabled: false // Disable data labels on each bar
            }
          }
        },
        title: {
          text: "Hourly Sales",
          align: "left"
        },
        xaxis: {
          categories: [], // Will be populated with hours
          title: {
            text: "Hour of Day"
          }
        },
        yaxis: [
          {
            title: {
              text: "Quantity (Liters)"
            }
          },
          {
            opposite: true,
            title: {
              text: "Amount (PKR)"
            }
          }
        ],
        tooltip: {
          shared: true,
          intersect: false
        },
        legend: {
          position: 'top'
        },
        hourlySalesChartSeries: [
          {
            name: "Quantity (L)",
            data: []
          },
          {
            name: "Amount (PKR)",
            data: []
          }
        ],
      },
      // Nozzle Sales Chart Options
      nozzleSalesChartOptions: {
        chart: {
          id: "nozzle-sales-chart",
          type: "bar",
          stacked: false
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '70%'
          }
        },
        title: {
          text: "Nozzle-wise Sales",
          align: "left"
        },
        xaxis: {
          categories: [], // Will be populated with nozzle names
          title: {
            text: "Nozzle"
          }
        },
        yaxis: [
          {
            title: {
              text: "Quantity (Liters)"
            }
          },
          {
            opposite: true,
            title: {
              text: "Amount (PKR)"
            }
          }
        ],
        tooltip: {
          shared: true,
          intersect: false
        },
        legend: {
          position: 'top'
        }
      },
      nozzleSalesChartSeries: [
        {
          name: "Quantity (L)",
          data: []
        },
        {
          name: "Amount (PKR)",
          data: []
        }
      ],

      // ApexCharts data for product distribution
      productChartOptions: {
        chart: {
          id: "product-pie-chart",
          type: "pie",
        },
        labels: [], // Will be populated dynamically
        title: {
          text: "Product Sales Distribution",
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
      productChartSeries: [], // Will be populated dynamically (array of values)

      paymentChartOptions: {
        chart: {
          id: "payment-pie-chart",
          type: "pie",
        },
        labels: [], // Will be populated dynamically
        title: {
          text: "Payment Method Distribution",
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
              },
              legend: {
                position: "bottom",
              },
            },
          },
        ],
      },
      paymentChartSeries: [] // Will be populated dynamically (array of values)
    };
  },
  computed: {
    // Example computed property for total current stock
    totalCurrentStock() {
      if (!this.stockStats) return 0;
      // Assuming stockStats is an array of tank statuses like from /api/tanks/lastfeulstatus
      return this.stockStats
        .reduce((sum, tank) => sum + (tank.Qty / 100 || 0), 0)
        .toFixed(2);
    },
    // Updated computed property for total sales amount
    totalSalesAmount() {
      // Use productWiseSales from the fetched salesStats data
      if (!this.salesStats || !this.salesStats.productWiseSales) return "0.00";

      const total = this.salesStats.productWiseSales.reduce(
        (sum, stat) => sum + parseFloat(stat.total_amount || 0),
        0
      );
      // Format as currency (assuming amount is in cents/smallest unit)
      return (total / 100).toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
  },
  mounted() {
    // this.fetchTankStockHistory();
    this.fetchSalesStats();
    this.fetchStockStats();
    this.fetchAlerts();
    this.fetchHourlySales();
    this.fetchNozzleSales();
    document.getElementsByTagName('html')[0].style.setProperty('overflow-y', 'hidden')
    document.getElementsByTagName('body')[0].style.setProperty('overflow-y', 'hidden')
    
    const updateZoom = () => {
      const width = window.innerWidth
      let zoom = '100%'
      if (width < 1440) zoom = '90%'
      if (width < 1200) zoom = '80%'
      if (width < 1024) zoom = '70%'
      if (width < 768) zoom = '60%'
      document.getElementsByTagName('html')[0].style.setProperty('zoom', zoom)
    }
    
    updateZoom()
    window.addEventListener('resize', updateZoom)


    // keep loading alerts after 5 seconds
    setTimeout(() => {
      this.fetchSalesStats();
      this.fetchStockStats();
      this.fetchAlerts();
      this.fetchHourlySales();
      this.fetchNozzleSales();
    }, 5000);
  },
  methods: {
    async fetchTankStockHistory() {
      this.loading.history = true;
      try {
        // Using /api/tanks/lastfeulstatus as it seems more relevant for current stock display elsewhere
        // Keeping /api/fuel-status for history example, adjust if needed
        const response = await axios.get("/api/fuel-status"); // Or another endpoint for historical data
        this.tankStockHistory = response.data.slice(0, 10); // Limit history display for brevity
      } catch (error) {
        console.error("Error fetching tank stock history:", error);
        // Optionally display error to user
      } finally {
        this.loading.history = false;
      }
    },
    async Fueltank() {
      localStorage.setItem('Fueltank', this.showPage);
      this.showPage = 'FuelTank';
    },
    async Sales() {
      localStorage.setItem('ShowSalesPage', this.showPage);
      this.showPage = 'ShowSalesPage';
    },
    async ShowTanks() {
      localStorage.setItem('ShowTanks', this.showPage);
      this.showPage = 'ShowTanks';
    },
    async fetchSalesStats() {
      this.loading.sales = true;
      this.productStatsRows = []; // Clear previous data
      // Clear previous ApexCharts data
      this.productChartSeries = [];
      this.productChartOptions = { ...this.productChartOptions, labels: [] };
      this.paymentChartSeries = [];
      this.paymentChartOptions = { ...this.paymentChartOptions, labels: [] };
      try {
        const response = await axios.get("/api/stats");
        this.salesStats = response.data; // Store the raw response

        // --- Start Processing Logic (from jQuery example) ---
        const stats = this.salesStats.productWiseSales;
        const products = this.salesStats.products;
        const productPieChartDataRaw = this.salesStats.productPieChart;
        const paymentPieChartDataRaw = this.salesStats.paymentPieChart;

        if (!stats || !products || !productPieChartDataRaw || !paymentPieChartDataRaw) {
          throw new Error("API response missing expected data fields.");
        }

        // Map product details by product code
        const productDetails = {};
        products.forEach((product) => {
          productDetails[product.ICODE] = product;
        });

        // Calculate total sales amount (used for percentage)
        const totalSalesAmountRaw = stats.reduce(
          (total, stat) => total + parseFloat(stat.total_amount || 0),
          0
        );

        // Generate table rows for product stats
        this.productStatsRows = stats
          .map((stat) => {
            const product = productDetails[stat.ICODE];
            if (!product) return null; // Handle case where product might be missing

            const totalAmount = parseFloat(stat.total_amount || 0);
            const percentage =
              totalSalesAmountRaw > 0
                ? ((totalAmount / totalSalesAmountRaw) * 100).toFixed(2) + "%"
                : "0.00%";

            return {
              name: product.ITMNAME,
              rate: (product.SRATE / 100).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              }),
              qty: (parseFloat(stat.total_qty || 0) / 100).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              }),
              count: parseFloat(stat.sale_count || 0).toLocaleString("en-US", {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
              }),
              percentage: percentage,
              amount: (totalAmount / 100).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              }),
            };
          })
          .filter((row) => row !== null); // Filter out any null rows if product was missing

        // --- Process data for ApexCharts ---

        // Product Pie Chart
        const productLabels = [];
        const productSeries = [];
        productPieChartDataRaw.forEach((item) => {
          const product = productDetails[item.label];
          productLabels.push(product ? product.ITMNAME : item.label); // Use name or fallback to code
          productSeries.push(parseFloat(item.value || 0) / 100); // Assuming value is in cents
        });
        // Update chart options and series reactively
        this.productChartOptions = { ...this.productChartOptions, labels: productLabels };
        this.productChartSeries = productSeries;

        // Payment Pie Chart
        const paymentLabels = [];
        const paymentSeries = [];
        paymentPieChartDataRaw.forEach((item) => {
          paymentLabels.push(item.label);
          // Assuming value is already correct scale (e.g., count or amount)
          // If it was amount in cents, divide by 100: parseFloat(item.value || 0) / 100
          paymentSeries.push(parseFloat(item.value || 0));
        });
        // Update chart options and series reactively
        this.paymentChartOptions = { ...this.paymentChartOptions, labels: paymentLabels };
        this.paymentChartSeries = paymentSeries;

        // --- End Processing Logic ---
      } catch (error) {
        console.error("Error fetching or processing sales stats:", error);
        // Keep raw salesStats potentially null or set an error object
        this.salesStats = {
          error: `Could not load or process sales data. ${error.message}`,
        };
        this.productStatsRows = []; // Ensure arrays are empty on error
        // Clear ApexCharts data on error as well
        this.productChartSeries = [];
        this.productChartOptions = { ...this.productChartOptions, labels: [] };
        this.paymentChartSeries = [];
        this.paymentChartOptions = { ...this.paymentChartOptions, labels: [] };
      } finally {
        this.loading.sales = false;
      }
    },
    async fetchStockStats() {
      this.loading.stock = true;
      try {
        // Endpoint identified from TankList.vue
        const response = await axios.get("/api/tanks/lastfeulstatus");
        this.stockStats = response.data; // Assuming this returns an array of tank statuses
      } catch (error) {
        console.error("Error fetching stock stats:", error);
        this.stockStats = { error: "Could not load stock data." }; // Provide feedback
      } finally {
        this.loading.stock = false;
      }
    },
    async fetchAlerts() {
      try {
        // Assuming an endpoint /api/alerts exists
        const response = await axios.get("/api/alerts");
        this.alerts = response.data; // Assuming this returns an array of alerts
      } catch (error) {
        console.error("Error fetching alerts:", error);
        // Add some dummy data for now if endpoint doesn't exist
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
        // this.alerts = { error: 'Could not load alerts.' }; // Provide feedback
      } finally {
        this.loading.alerts = false;
      }
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
        this.selectedTank = this.stockStats.find(tank => tank.TID == this.selectedTankId);

        // Update chart title
        this.tankHistoryChartOptions = {
          ...this.tankHistoryChartOptions,
          title: {
            text: `Tank Stock History - ${this.selectedTank?.TankName || `Tank ${this.selectedTankId}`}`,
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

    // Fetch hourly sales data
    async fetchHourlySales() {
      this.loading.hourlySales = true;

      try {
        const response = await axios.get(`/api/stats/hourly?date=${this.salesDate}`);
        this.hourlySales = response.data;

        // Update chart options with hours
        this.hourlySalesChartOptions = {
          ...this.hourlySalesChartOptions,
          title: {
            text: `Hourly Sales - ${new Date(this.salesDate).toLocaleDateString()}`,
            align: "left"
          },
          xaxis: {
            ...this.hourlySalesChartOptions.xaxis,
            categories: this.hourlySales.hours.map(hour => `${hour}:00`)
          }
        };

        // Update chart series
        this.hourlySalesChartSeries = [
          {
            name: "Quantity (L)",
            data: this.hourlySales.quantities
          },
          {
            name: "Amount (PKR)",
            data: this.hourlySales.amounts
          }
        ];
      } catch (error) {
        console.error("Error fetching hourly sales:", error);
        this.hourlySales = { hours: [] };
      } finally {
        this.loading.hourlySales = false;
      }
    },

    // Fetch nozzle-wise sales data
    async fetchNozzleSales() {
      this.loading.nozzleSales = true;

      try {
        const response = await axios.get(`/api/stats/nozzle?date=${this.nozzleSalesDate}`);
        this.nozzleSales = response.data;

        if (this.nozzleSales.nozzleSales && this.nozzleSales.nozzleSales.length > 0) {
          // Extract nozzle names and data for chart
          const nozzleNames = this.nozzleSales.nozzleSales.map(sale =>
            `${sale.nozzle_name || `Nozzle ${sale.nozzle_id}`} (${sale.product_name})`
          );

          const quantities = this.nozzleSales.nozzleSales.map(sale => sale.total_qty / 100);
          const amounts = this.nozzleSales.nozzleSales.map(sale => sale.total_amount / 100);

          // Update chart options
          this.nozzleSalesChartOptions = {
            ...this.nozzleSalesChartOptions,
            title: {
              text: `Nozzle-wise Sales - ${new Date(this.nozzleSalesDate).toLocaleDateString()}`,
              align: "left"
            },
            xaxis: {
              ...this.nozzleSalesChartOptions.xaxis,
              categories: nozzleNames
            }
          };

          // Update chart series
          this.nozzleSalesChartSeries = [
            {
              name: "Quantity (L)",
              data: quantities
            },
            {
              name: "Amount (PKR)",
              data: amounts
            }
          ];
        }
      } catch (error) {
        console.error("Error fetching nozzle sales:", error);
        this.nozzleSales = { nozzleSales: [] };
      } finally {
        this.loading.nozzleSales = false;
      }
    }
  },
};
</script>
