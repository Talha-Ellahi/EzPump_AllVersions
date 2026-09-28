<template>
  <div>
    <!-- Header for Current Date -->
    <div id="current-date">{{ currentDate }}</div>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="pe-3">
        <button
          type="button"
          @click="closeAllShiftsButtonClicked"
          :disabled="isCloseAllShiftsButtonDisabled"
          class="btn btn-warning shift-close-all"
        >
          Close All Shifts
        </button>
        <!-- <h5 class="mb-0 text-uppercase"><b>Shift Start Date & Time: </b>8 July 2024 07:42:17 AM
                                    </h5> -->
      </div>
    </div>

    <!-- Pump Cards -->
    <div id="pump-cards" class="row" style="justify-content: center;">
      <template v-if="showAllResults">
        <div
          v-for="pump in pumpCards"
          :key="pump.id"
          class="col-xl-2 col-lg-3 col-md-4 col-sm-6"
          style="width: 230px !important; padding-right: 2px !important; padding-left: 0px !important;"
        >
          <div class="card pump-card" style="margin-bottom: 7px;">
            <img :src="getPumpImage(pump)" class="card-img-top" alt="Nozzle" />
            <div>
              <div class="position-absolute top-0 end-0 m-3 product-discount">
                <b>{{ pump.POS_ID }}</b>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="last_sale">
                  <p class="mb-0 ms-auto" style="font-size: 17px;">Last Sale:</p>
                  <b class="mb-0 ms-auto qty">{{ pump.QtyD }}</b>
                  <span>Ltrs</span> -- Rs.
                  <b class="mb-0 ms-auto amt">{{ pump.AmtD }}</b>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <div class="total_sale">
                  <p class="mb-0 ms-auto">Total Sale:</p>
                  <b class="mb-0 ms-auto qty">{{ pump.shift.total_qty }}</b>
                  <span>Ltrs</span> -- Rs.
                  <b class="mb-0 ms-auto amt">{{ pump.shift.total_amount.toFixed(2) }}</b>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <div class="cursor-pointer rate">
                  <p class="mb-0 ms-auto">
                    <strong>Rate.</strong>
                    <span class="rate" style="font-size: 17px;">{{ pump.shift.rate }}</span>
                  </p>
                </div>
              </div>
              <div class="d-flex align-items-center">
                <button
                  class="shiftButton btn btn-primary ms-1 me-1"
                  @click="openSaleHistory(pump)"
                >
                  Sales
                </button>
                <button
                  class="shiftButton btn btn-info ms-1 me-1"
                  @click.stop="openShiftInfo(pump)"
                >
                  Shift
                </button>
              </div>
            </div>
          </div>
        </div>
        
        </template>
      <template v-else>
        <div
          v-for="pump in pumpCards"
          :key="pump.id"
          class="col-xl-1 col-lg-1 col-md-2 col-sm-3"
        >
          <div class="card pump-card" @click="openShiftInfo(pump)">
            <img :src="getPumpImage(pump)" class="card-img-top" alt="Nozzle" />
            <div class="card-body">
              <span
                ><b>{{ pump.POS_ID }}</b></span
              >
            </div>
          </div>
        </div>
      </template>
    </div>

    <!-- Placeholder for shift and sale history -->
    <div class="sidebar_new">
      <!-- <button @click="fetchInitialData">Refresh Pumps</button> -->

      <div v-if="activeShiftData" class="shift_details">
        <div class="overlay_bg"></div>
        <!-- <pre>{{ JSON.stringify(shiftResponse) }}</pre> -->
        <div class="new_side_inner" style="z-index: 1000">
          <div class="d-flex justify-content-between align-items-center">
            <h4>Shift Details</h4>
            <a href="" @click.prevent="closeShiftClose" class="close">X</a>
          </div>
          <!-- Shift Details form with v-model -->
          <form id="shiftForm" @submit.prevent="saveShift">
            <input type="hidden" v-model="shiftForm.id" name="id" id="id" />
            <input
              type="hidden"
              v-model="shiftForm.end_date"
              name="end_date"
              id="end_date"
            />
            <input
              type="hidden"
              v-model="shiftForm.status"
              class="form-control"
              id="status"
              name="status"
            />
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="start_date">Start Date</label>
                <input
                  type="text"
                  v-model="shiftForm.start_date"
                  class="form-control"
                  id="start_date"
                  name="start_date"
                  disabled
                />
              </div>
              <div class="form-group col-md-6">
                <label for="pump_id">Nozzle ID</label>
                <input
                  type="number"
                  v-model="shiftForm.pump_id"
                  class="form-control"
                  id="pump_id"
                  name="pump_id"
                  disabled
                />
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="opening_fuel">System Opening Totalizer</label>
                <input
                  type="number"
                  v-model="shiftForm.opening_fuel"
                  class="form-control"
                  id="opening_fuel"
                  name="opening_fuel"
                  disabled
                />
              </div>
              <div class="form-group col-md-6">
                <label for="opening_fuel_manual">Mannual Opening Totalizer</label>
                <input
                  :disabled="shiftForm.bIsUpdated"
                  type="number"
                  v-model="shiftForm.opening_fuel_manual"
                  class="form-control"
                  id="opening_fuel_manual"
                  name="opening_fuel_manual"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="closing_fuel">System Closing Totalizer</label>
                <input
                  type="number"
                  v-model="shiftForm.closing_fuel"
                  class="form-control"
                  id="closing_fuel"
                  name="closing_fuel"
                  disabled
                />
              </div>
              <div class="form-group col-md-6">
                <label for="closing_fuel_manual">Mannual Closing Totalizer</label>
                <input
                  type="number"
                  v-model="shiftForm.closing_fuel_manual"
                  class="form-control"
                  id="closing_fuel_manual"
                  name="closing_fuel_manual"
                />
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="opening_balance">Opening Balance</label>
                <input
                  :disabled="shiftForm.bIsUpdated"
                  type="number"
                  v-model="shiftForm.opening_balance"
                  class="form-control"
                  id="opening_balance"
                  name="opening_balance"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="closing_balance">Closing Balance</label>
                <input
                  type="number"
                  v-model="shiftForm.closing_balance"
                  class="form-control"
                  id="closing_balance"
                  name="closing_balance"
                />
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="total_qty">Total Quantity</label>
                <input
                  type="number"
                  v-model="shiftForm.total_qty"
                  class="form-control"
                  id="total_qty"
                  name="total_qty"
                  readonly
                />
              </div>
              <div class="form-group col-md-6">
                <label for="rate">Rate</label>
                <input
                  type="number"
                  v-model="shiftForm.rate"
                  class="form-control"
                  id="rate"
                  name="rate"
                  readonly
                />
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="adjustments">Adjustments</label>
                <input
                  type="number"
                  v-model="shiftForm.adjustments"
                  class="form-control"
                  id="adjustments"
                  name="adjustments"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="is_changed">Rate Changed during shift?</label>
                <span class="form-control">
                  <b for="is_changed">Yes</b>
                </span>
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="new_rate">New Rate</label>
                <input
                  type="number"
                  v-model="shiftForm.new_rate"
                  class="form-control"
                  id="new_rate"
                  name="new_rate"
                  readonly
                />
              </div>
              <div class="form-group col-md-6">
                <label for="changed_fuel_balance">Changed Fuel Balance</label>
                <input
                  type="number"
                  v-model="shiftForm.changed_fuel_balance"
                  class="form-control"
                  id="changed_fuel_balance"
                  name="changed_fuel_balance"
                  readonly
                />
              </div>
            </div>
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="cashier_name">Cashier Name</label>
                <select
                  :disabled="shiftForm.bIsUpdated"
                  v-model="shiftForm.cashier_id"
                  class="form-select"
                  id="cashier_name"
                  name="cashier_name"
                >
                  <template v-for="(employee, index) in employees">
                    <option :key="index" v-if="employee.des_id == 4" :value="employee.id">
                      {{ employee.emp_name }}
                    </option>
                  </template>
                </select>
              </div>
              <div class="form-group col-md-6 d-none">
                <label for="last_sale_id">Last Sale ID</label>
                <input
                  type="number"
                  v-model="shiftForm.last_sale_id"
                  class="form-control"
                  id="last_sale_id"
                  name="last_sale_id"
                />
              </div>
            </div>
            <div
              class="mt-4 cs-hide_print mt-2 px-3"
              style="display: flex; justify-content: space-between; align-items: center"
            >
              <!-- Centered Buttons -->
              <div style="display: flex; gap: 10px">
                <!-- <button type="button" class="btn btn-primary d-none me-2">Previous</button> -->
                <button type="submit" class="btn btn-warning me-2" id="submitButton">
                  Save
                </button>
              </div>

              <!-- Shift Close Button aligned to the right -->
              <button
                type="button"
                @click="closeShift"
                class="btn btn-danger shift-close"
              >
                Shift Close
              </button>
            </div>
          </form>
          <template
            v-if="shiftResponse.paymentMethods && shiftResponse.paymentMethods.length > 0"
          >
            <h2>Payments</h2>
            <table class="table new_style_table table-striped">
              <thead>
                <tr>
                  <th>Payment Mode</th>
                  <th>Total Amount (PKR)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in shiftResponse.paymentMethods" :key="index">
                  <td>{{ row.pMethod }}</td>
                  <td>Rs {{ row.total_amount / 100 }}</td>
                </tr>
              </tbody>
            </table>
          </template>

          <div
            v-if="shiftResponse.customers && shiftResponse.customers.length > 0"
            class="mt-3"
          >
            <h1>Customers</h1>
            <table class="table new_style_table table-striped">
              <thead>
                <!-- Table headers -->
              </thead>
              <tbody>
                <!-- Use v-for to loop through data -->
                <tr v-for="(row, index) in shiftResponse.customers" :key="index">
                  <td>{{ row.Des }}</td>
                  <td>{{ row.RegNo }}</td>
                  <td>Rs {{ row.total_amt / 100 }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Add more details as needed -->
        </div>
      </div>
    </div>

    <!-- Sale History Popup -->
    <div v-if="showSaleHistoryPopup" class="sale-history-popup">
      <div class="overlay_bg" @click="closeSaleHistory"></div>
      <div class="new_side_inner sale-history-content" style="z-index: 1001; box-shadow: 0px 0px 2000px #00000061;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4>Sales History (Pump: {{ activeSaleHistoryPump.id }})</h4>
          <a href="#" @click.prevent="closeSaleHistory" class="close">X</a>
        </div>

        <div class="table-responsive">
          <table class="table new_style_table table-striped table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Rate</th>
                <th>Qty (Ltr)</th>
                <th>Amount (Rs)</th>
                <th>Method</th>
                <th>Customer</th>
                <th>Print</th>
              </tr>
            </thead>
            <tbody id="shift_sale_data">
              <tr v-if="saleHistoryData.length === 0 && !saleHistoryLoading">
                <td colspan="8" class="text-center">No sales data found.</td>
              </tr>
              <tr v-for="sale in saleHistoryData" :key="sale.id">
                <td>{{ sale.id }}</td>
                <td>{{ sale.pdate }}</td>
                <td>{{ (sale.rate / 100).toFixed(2) }}</td>
                <td>{{ (sale.qty / 100).toFixed(2) }}</td>
                <td>{{ (sale.amt / 100).toFixed(2) }}</td>
                <td>{{ sale.pMethod }}</td>
                <td>{{ sale.customer }}</td>
                <td>
                  <button
                    @click="printReceipt(sale.id)"
                    class="btn btn-sm btn-outline-secondary lni lni-printer"
                  ></button>
                </td>
              </tr>
              <tr v-if="saleHistoryLoading">
                <td colspan="8" class="text-center">
                  <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="text-center mt-3" v-if="saleHistoryHasMore && !saleHistoryLoading">
          <button @click="fetchSaleHistory" class="btn btn-primary">Load More</button>
        </div>
        <div
          class="text-center mt-3"
          v-if="!saleHistoryHasMore && saleHistoryData.length > 0"
        >
          <p>No more items to load.</p>
        </div>
      </div>
    </div>
    <!-- End Sale History Popup -->

    <!-- Tank Closing Popup (Vue Template Approach) -->
    <div v-if="showTankClosingPopup" class="modal-container tank-closing-modal">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">Enter Closing Totalizers for Tank Shifts</h5>
                <button type="button" class="btn-close" @click="cancelTankClosing" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tank</th>
                            <th>Opening Totalizer</th>
                            <th>Manual Closing Totalizer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="shift in activeTankShiftsForPopup" :key="shift.id" :class="{ 'table-success': shift.closedSuccess, 'table-danger': shift.closedError }">
                            <td>{{ shift.tank_name || `Tank ID: ${shift.tank_id}` }}</td>
                            <td>{{ (shift.opening_fuel / 100).toFixed(2) }}</td>
                            <td>
                                <input
                                    type="number"
                                    v-model="tankClosingTotalizers[shift.id]"
                                    class="form-control closing-input"
                                    step="0.01"
                                    required
                                    placeholder="Enter value"
                                    :disabled="shift.closedSuccess"
                                />
                                <div v-if="shift.closedError" class="text-danger small mt-1">{{ shift.errorMessage }}</div>
                            </td>
                        </tr>
                         <tr v-if="activeTankShiftsForPopup.length === 0">
                             <td colspan="3" class="text-center">No active tank shifts found.</td>
                         </tr>
                    </tbody>
                </table>
                 <div v-if="tankClosingError" class="alert alert-danger mt-3">{{ tankClosingError }}</div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cancelTankClosing">Cancel</button>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="submitTankClosing"
                    :disabled="isSubmittingTankClosing || activeTankShiftsForPopup.length === 0"
                >
                    <span v-if="isSubmittingTankClosing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ isSubmittingTankClosing ? 'Processing...' : 'Submit & Close All' }}
                </button>
            </div>
        </div>
    </div>
    <!-- End Tank Closing Popup -->

  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from "vue";
const isCloseAllShiftsButtonDisabled = ref(false);
import axios from "axios";

// --- Tank Closing Popup State ---
const showTankClosingPopup = ref(false);
const activeTankShiftsForPopup = ref([]);
const tankClosingTotalizers = ref({});
const isSubmittingTankClosing = ref(false);
const tankClosingError = ref(null); // For general errors during submission

// --- Sales History State ---
const showSaleHistoryPopup = ref(false);
const saleHistoryData = ref([]);
const activeSaleHistoryPump = ref(null);
const saleHistoryPageNo = ref(1);
const saleHistoryLoading = ref(false);
const saleHistoryHasMore = ref(true);
// --- End Sales History State ---

const props = defineProps({
  showAllResults: {
    type: Boolean,
    default: false,
  },
});

const shiftForm = reactive({
  id: "",
  end_date: "",
  status: "",
  start_date: "",
  pump_id: "",
  opening_fuel: "",
  opening_fuel_manual: "",
  closing_fuel: "",
  closing_fuel_manual: "",
  opening_balance: "",
  closing_balance: "",
  manual_opening_dip: "",
  manual_closing_dip: "",
  tank_id: "",
  total_qty: "",
  rate: "",
  adjustments: "",
  is_changed: "",
  new_rate: "",
  changed_fuel_balance: "",
  cashier_name: "",
  cashier_id: "",
  last_sale_id: "",
  bIsUpdated: false,
});
// Reactive State Variables
const pumpCards = ref([]);
const loading = ref(false);
const activeShiftData = ref(null);
const currentDate = ref("");
const paymentMethods = reactive({});
const employees = ref([]);
const shiftResponse = reactive({
  customers: [],
  paymentMethods: [],
});
const publicSettings = reactive({});
const tanks = ref([]);
const getPublicSettings = async () => {
  const response = await axios.get("/api/settings/public");
  publicSettings.value = {};
  response.data.forEach((setting) => {
    publicSettings.value[setting.key] = setting.value;
  });
  return publicSettings;
};
// Image Mappings
const pumpImages = {
  1: "assets/images/Nozzle_one.png",
  2: "assets/images/Nozzle_three.png",
};

const pumpICodeImages = {
  1: "assets/images/Nozzle_green.png",
  2: "assets/images/Nozzle_Blue.png",
  3: "assets/images/Nozzle_yellow.png",
  4: "assets/images/Nozzle_black.png",
  // Add more ICODE images if needed
};

// Fetch Pump Data
async function fetchPumpData() {
  try {
    const response = await fetch("/api/pumps");
    pumpCards.value = await response.json();
  } catch (error) {
    console.error("Error fetching initial data:", error);
  }
}

const fetchTanksData = async () => {
  try {
    const response = await fetch("/api/tanks");
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    tanks.value = data;
  } catch (error) {
    console.error("Error fetching tank data:", error);
  }
};
// Get Image for Pump
function getPumpImage(pump) {
  return (
    pumpImages[pump?.STS] || pumpICodeImages[pump?.ICODE] || "assets/images/default.png"
  );
}

// Open Shift Information
async function openShiftInfo(pump) {
  try {
    const response = await fetch(`/api/shift/check/${pump.id}`);
    const data = await response.json();

    if (data.shift) {
      // Populate the form with the existing shift data
      activeShiftData.value = data.shift;
      shiftResponse.shift = data.shift;
      shiftResponse.customers = data.customers;
      shiftResponse.paymentMethods = data.paymentMethods;

      Object.keys(shiftForm).forEach((key) => {
        shiftForm[key] = data.shift[key] !== undefined ? data.shift[key] : "";
      });

      shiftForm.rate = data.shift.rate / 100;
      shiftForm.total_qty = data.shift.total_qty / 100;
      shiftForm.opening_fuel = data.shift.opening_fuel / 100;
      shiftForm.closing_fuel = data.shift.closing_fuel / 100;

      shiftForm.id = data.shift.id;
      shiftForm.pump_id = data.shift.pump_id;
      shiftForm.start_date = data.shift.start_date;
      shiftForm.status = data.shift.status;
      shiftForm.end_date = data.shift.end_date;

      // Update form button text and visibility
      document.getElementById("submitButton").textContent = "Update Shift";
      document.querySelector(".shift-close").style.display = "block";
    } else {
      // Clear the form for a new shift
      Object.keys(shiftForm).forEach((key) => {
        shiftForm[key] = "";
      });

      shiftForm.id = "";
      shiftForm.pump_id = pump.id;
      shiftForm.bIsUpdated = false;

      // Update form button text and visibility
      document.getElementById("submitButton").textContent = "Create Shift";
      document.querySelector(".shift-close").style.display = "none";
    }
  } catch (error) {
    console.error("Error fetching shift info:", error);
  }
}

// Update Current Date
function updateCurrentDate() {
  const now = new Date();
  now.setHours(now.getUTCHours() + 5); // Adjust to Karachi timezone
  currentDate.value = now.toLocaleString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
    second: "numeric",
    hour12: true,
  });
}
const saveShift = async (event) => {
  event.preventDefault();

  const url = shiftForm.id ? `/api/shift/update` : `/api/shift/create`;
  const method = shiftForm.id ? "POST" : "POST";
  let selectedEmployee;
  try {
    if (shiftForm.cashier_id) {
      selectedEmployee = employees.value.find(
        (employee) => employee.emp_id === shiftForm.cashier_id
      );
      selectedEmployee = selectedEmployee ? selectedEmployee.emp_name : "";
    }
    const response = await fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "x-requested-with": "XMLHttpRequest",
      },
      body: JSON.stringify({ ...shiftForm, cashier_name: selectedEmployee }),
    });

    const data = await response.json();
    shiftForm.bIsUpdated = true;
    alert("Shift saved successfully!");
    activeShiftData.value = null;
  } catch (error) {
    console.error("Error saving shift:", error);
  }
};
// Fetch data from the API

// Function to fetch stats data
async function fetchStatsData() {
  try {
    const response = await axios.get("/api/stats");
    const stats = response.data.stats;
    const products = response.data.products;
    console.log(pumpCards.value);
    // Create an object of product details by icode
    const productDetails = {};
    products.forEach((product) => {
      productDetails[product.ICODE] = product;
    });

    // Calculate total sales amount to determine percentages
    const totalSalesAmount = stats.reduce(
      (total, stat) => total + parseFloat(stat.total_amount),
      0
    );

    // Generate table rows
    const rows = stats
      .map((stat) => {
        const product = productDetails[stat.icode];
        const percentage =
          ((parseFloat(stat.total_amount) / totalSalesAmount) * 100).toFixed(2) + "%";
        return `
                    <tr>
                        <td class="cs-width_2">${product.ITMNAME}</td>
                        <td class="cs-width_2">Rs. ${product.SRATE / 100}</td>
                        <td class="cs-width_2">Ltrs. ${parseFloat(stat.total_qty).toFixed(
                          2
                        )}</td>
                        <td class="cs-width_2">${percentage}</td>
                        <td class="cs-width_3 cs-text_right cs-primary_color cs-semi_bold">${parseFloat(
                          stat.total_amount
                        ).toFixed(2)}</td>
                    </tr>
                `;
      })
      .join("");

    // Update the total sale amount and product stats table
    document.getElementById("summary_total_sale").textContent = totalSalesAmount.toFixed(
      2
    );
    document.getElementById("product-stats").innerHTML = rows;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

// On Component Mount
onMounted(() => {
  fetchPumpData();
  updateCurrentDate();
  fetchTanksData();
  getPublicSettings();
  // fetchStatsData();
  setInterval(fetchPumpData, 5000); // Auto-refresh pump states
  // setInterval(fetchStatsData, 3000); // Auto-refresh pump states
  setInterval(updateCurrentDate, 1000); // Update the clock every second

  // set employess.value from /api/employees
  fetch("/api/employees")
    .then((response) => response.json())
    .then((data) => {
      employees.value = data;
    })
    .catch((error) => {
      console.error("Error fetching employees:", error);
    });
});

defineExpose({
  pumpCards,
});

const closeShift = async () => {
  loading.value = true;
  const id = shiftForm.id;
  const url = id ? "/api/shift/update" : "/api/shift/create";
  const method = id ? "POST" : "POST";

  try {
    const response = await fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "x-requested-with": "XMLHttpRequest",
      },
      body: JSON.stringify(shiftForm),
    });

    const data = await response.json();

    const closeShiftResponse = await fetch(`/api/shift/close/${id}`);
    //  activeShiftData.value = null;
    if (!closeShiftResponse.ok) {
      const errorData = await closeShiftResponse.json();
      throw new Error(errorData.error || "Something went wrong.");
    }

    const closeShiftData = await closeShiftResponse.json();

    if (closeShiftData.status === "success" && closeShiftData.shift) {
      alert("Shift Closed Successfully.");
      activeShiftData.value = null;
      // openShiftInfo({id:shiftForm.pump_id});
    } else {
      //Handle error appropriately, e.g., display an error message
      console.error("Error closing shift:", closeShiftData);
      alert("Error closing shift. Please check the console for details.");
    }
    // activeShift -= 1;
    // document.querySelector('.next-shift').click();

    document.querySelector(".shift-close").disabled = false;
  } catch (error) {
    alert(`Error: ${error.message}`);
    document.querySelector(".shift-close").disabled = false;
  }
};
const closeShiftClose = () => {
  // Close the shift or modal, and reset the state if needed
  console.log("Closing shift or modal...");

  // Example: You can reset activeShiftData to null or perform any other cleanup
  activeShiftData.value = null; // For example, resetting active shift data

  // You can also perform other actions like hiding the modal
  // Example: document.querySelector('.shift-details').style.display = 'none'; // or use Vue's v-if/v-show to hide
};

// --- Sales History Methods ---
const openSaleHistory = async (pump) => {
  activeSaleHistoryPump.value = pump;
  saleHistoryPageNo.value = 1;
  saleHistoryData.value = [];
  saleHistoryHasMore.value = true;
  showSaleHistoryPopup.value = true;
  await fetchSaleHistory(pump);
};

const fetchSaleHistory = async () => {
  if (saleHistoryLoading.value || !saleHistoryHasMore.value) return;

  saleHistoryLoading.value = true;
  try {
    const response = await axios.get(
      `/api/shiftSaleData?pump_id=${activeSaleHistoryPump.value.id}&page=${saleHistoryPageNo.value}&shift_id=${activeSaleHistoryPump.value.id}`,
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    const newData = response.data.data; // Assuming Laravel pagination structure

    if (newData && newData.length > 0) {
      saleHistoryData.value = [...saleHistoryData.value, ...newData];
      saleHistoryPageNo.value++;
    } else {
      saleHistoryHasMore.value = false; // No more data
    }

    // Check if there's a next page URL to determine if there's more data
    if (!response.data.next_page_url) {
      saleHistoryHasMore.value = false;
    }
  } catch (error) {
    console.error("Error fetching sale history:", error);
    alert("Failed to load sales history.");
    saleHistoryHasMore.value = false; // Stop trying on error
  } finally {
    saleHistoryLoading.value = false;
  }
};

const closeSaleHistory = () => {
  showSaleHistoryPopup.value = false;
  saleHistoryData.value = [];
  activeSaleHistoryPump.value = null;
  saleHistoryPageNo.value = 1;
  saleHistoryHasMore.value = true;
};

const printReceipt = (saleId) => {
  console.log("Attempting to print receipt for sale ID:", saleId);
  // TODO: Implement actual receipt printing logic here.
  // This might involve:
  // - Fetching detailed sale data if needed.
  // - Opening a new window with a printable receipt format.
  // - Calling a specific API endpoint that generates and returns a printable document.
  fetch(`/api/printDuplicate/${shift_table_id}/${saleId}`).then((res) => {
    alert(`Printing receipt for Sale ID: ${saleId}`);
  });
};
// --- End Sales History Methods ---

const closeAllShiftsButtonClicked = async () => {
  isCloseAllShiftsButtonDisabled.value = true;
  // First, attempt to close tank shifts based on ATG setting
  await closeAllTankShifts();
  if (publicSettings.require_manual_totalizer_during_shift_close == 1) {
    try {
      const disableResponse = await axios.post("/api/disablePumpProcessing", {
        headers: {
          "Content-Type": "application/json",
        },
      });

      // Fetch pump data
      const pumpsResponse = await axios.get("/api/shifts");
      const pumpsData = pumpsResponse.data;

      // Validate pump data
      const validPumps = pumpsData.filter((pump) => {
        return pump.pump_id; // Only include active shifts
      });

      if (validPumps.length === 0) {
        throw new Error("No active shifts found to close");
      }
      // Display tabular popup for pumps
      displayPumpsPopup(validPumps);
    } catch (error) {
      console.error(`Error: ${error}`);
      // Attempt to re-enable pump processing if something went wrong
      try {
        await axios.post("/api/enablePumpProcessing", {
          headers: {
            "Content-Type": "application/json",
          },
        });
      } catch (err) {
        console.error("Failed to re-enable pump processing:", err);
      }
    }
  } else {
    try {
      const response = await fetch("/api/shift/closeAllShifts");
      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || "Something went wrong.");
      }

      const data = await response.json();
      alert(data.message);
    } catch (error) {
      alert(`Error: ${error.message}`);
    } finally {
      isCloseAllShiftsButtonDisabled.value = false;
    }
  }
};

// New function to fetch active tank shifts (assuming endpoint /api/tanks/tank-shifts/active)
const fetchActiveTankShifts = async () => {
  try {
    const response = await axios.get("/api/tanks/tank-shifts/active");
    if (!response.data || !Array.isArray(response.data)) {
        console.error("Invalid response format for active tank shifts:", response.data);
        alert("Could not fetch active tank shifts. Invalid data format.");
        return [];
    }
    return response.data; // Expecting [{ id: shift_id, tank_id: ..., tank_name: ..., opening_fuel: ... }]
  } catch (error) {
    console.error("Error fetching active tank shifts:", error);
    alert(`Error fetching active tank shifts: ${error.message}`);
    return [];
  }
};

// Function to handle manual closing when ATG is not installed (Refactored)
const handleManualTankShiftClosing = async () => {
    const fetchedShifts = await fetchActiveTankShifts();
    if (fetchedShifts.length > 0) {
        activeTankShiftsForPopup.value = fetchedShifts.map(shift => ({ ...shift, closedSuccess: false, closedError: false, errorMessage: null })); // Add status flags
        // Initialize totalizers object
        tankClosingTotalizers.value = fetchedShifts.reduce((acc, shift) => {
            acc[shift.id] = null; // Initialize with null or empty string
            return acc;
        }, {});
        tankClosingError.value = null; // Reset general error
        isSubmittingTankClosing.value = false; // Reset submission state
        showTankClosingPopup.value = true; // Show the Vue-based popup
    } else {
        alert("No active tank shifts found to close manually.");
        isCloseAllShiftsButtonDisabled.value = false; // Re-enable main button if nothing to do
    }
};

// Modified function to decide how to close tank shifts
const closeAllTankShifts = async () => {
  // Check the ATG setting
  // Use a default value (e.g., 1) if the setting is not yet loaded or missing
  const isAtgInstalled = publicSettings.value?.atg_installed ?? 1;

  if (isAtgInstalled == 0) {
    // ATG NOT installed - Trigger manual popup flow
    await handleManualTankShiftClosing();
  } else {
    // ATG installed - Call the direct tank close-all endpoint
    console.log("ATG installed, calling direct close-all for tanks...");
    try {
        const response = await axios.post("/api/tanks/tank-shifts/close-all");
        console.log("Direct tank close-all response:", response.data);
        alert("Tank shifts closed successfully (ATG mode)."); // Provide feedback
    } catch (error) {
        console.error("Error calling direct tank close-all:", error);
        alert(`Error closing tank shifts (ATG mode): ${error.message}`);
    }
    // The actual tank closing for ATG=1 happens implicitly if needed or via a separate mechanism.
    // For now, we just proceed to the pump closing logic if ATG is installed.
  }
};

// --- Tank Closing Popup Methods ---
const submitTankClosing = async () => {
    isSubmittingTankClosing.value = true;
    tankClosingError.value = null; // Reset general error
    let allSuccessful = true;
    let firstError = null;

    // Reset error/success states before submission
    activeTankShiftsForPopup.value.forEach(shift => {
        shift.closedSuccess = false;
        shift.closedError = false;
        shift.errorMessage = null;
    });

    for (const shift of activeTankShiftsForPopup.value) {
        const closingValue = tankClosingTotalizers.value[shift.id];

        if (closingValue === null || closingValue === '' || isNaN(parseFloat(closingValue)) || parseFloat(closingValue) < 0) {
            shift.closedError = true;
            shift.errorMessage = 'Invalid or missing value.';
            if (!firstError) firstError = `Invalid closing totalizer for ${shift.tank_name || `Tank ID: ${shift.tank_id}`}.`;
            allSuccessful = false;
            continue; // Skip API call for this invalid entry, but check others
        }

        // Step 1: Update the shift with the manual closing totalizer
        try {
            await axios.post(`/api/tanks/tank-shifts/update`, {
                id: shift.id,
                manual_closing_totalizer: Math.round(parseFloat(closingValue) * 100), // Send as integer/cents
            });
            // Mark as updated successfully (intermediate step)
            // We won't mark closedSuccess yet, only after the final close-all call
        } catch (error) {
            console.error(`Error updating tank shift ${shift.id}:`, error);
            shift.closedError = true; // Mark row with error
            shift.errorMessage = error.response?.data?.message || error.message || 'Update API Error';
            if (!firstError) firstError = `Failed to update shift for ${shift.tank_name || `Tank ID: ${shift.tank_id}`}: ${shift.errorMessage}`;
            allSuccessful = false;
            // Decide if you want to stop or continue on error - let's stop on first update error
             break; // Stop processing further updates if one fails
        }
    }

    // Step 2: If all updates were successful, call close-all
    if (allSuccessful) {
        try {
            console.log("All updates successful, calling close-all...");
            const closeAllResponse = await axios.post("/api/tanks/tank-shifts/close-all");
            console.log("Close-all response:", closeAllResponse.data);

            // Mark all shifts in the popup as successfully closed
             activeTankShiftsForPopup.value.forEach(shift => {
                 // Only mark those that were part of this batch, though technically close-all closes everything active
                 if (tankClosingTotalizers.value.hasOwnProperty(shift.id)) {
                     shift.closedSuccess = true;
                 }
             });

            alert("All tank shifts updated and closed successfully.");
            showTankClosingPopup.value = false; // Close popup on final success

        } catch (error) {
            console.error("Error calling tank close-all after updates:", error);
            tankClosingError.value = `Updates succeeded, but failed to call close-all: ${error.response?.data?.message || error.message}`;
            allSuccessful = false; // Mark overall process as failed
        }
    } else {
         // Updates failed, keep popup open
        tankClosingError.value = firstError || "Some tank shifts failed to update. Please review the errors and try again.";
    }

    isSubmittingTankClosing.value = false;

     // Re-enable the main button only if the popup is closed or the process fully completes (even with errors)
    if (!showTankClosingPopup.value) {
        isCloseAllShiftsButtonDisabled.value = false;
    }
};

const cancelTankClosing = () => {
    if (confirm("Are you sure you want to cancel closing tank shifts? Any entered data will be lost.")) {
        showTankClosingPopup.value = false;
        activeTankShiftsForPopup.value = [];
        tankClosingTotalizers.value = {};
        isCloseAllShiftsButtonDisabled.value = false; // Re-enable main button
    }
};
// --- End Tank Closing Popup Methods ---


const displayPumpsPopup = (pumps) => {
  // Create modal container
  const modalContainer = document.createElement("div");
  modalContainer.className = "modal-container";
  modalContainer.style.position = "fixed";
  modalContainer.style.top = "0";
  modalContainer.style.left = "0";
  modalContainer.style.width = "100%";
  modalContainer.style.height = "100%";
  modalContainer.style.backgroundColor = "rgba(0,0,0,0.5)";
  modalContainer.style.display = "flex";
  modalContainer.style.justifyContent = "center";
  modalContainer.style.alignItems = "center";
  modalContainer.style.zIndex = "1000";

  // Create modal content
  const modalContent = document.createElement("div");
  modalContent.className = "modal-content";
  modalContent.style.backgroundColor = "white";
  modalContent.style.padding = "20px";
  modalContent.style.borderRadius = "5px";
  modalContent.style.maxWidth = "800px";
  modalContent.style.width = "90%";
  modalContent.style.maxHeight = "80vh";
  modalContent.style.overflowY = "auto";

  // Create header
  const header = document.createElement("div");
  header.style.display = "flex";
  header.style.justifyContent = "space-between";
  header.style.marginBottom = "20px";

  const title = document.createElement("h3");
  title.textContent = "Close Shifts";

  const closeButton = document.createElement("button");
  closeButton.textContent = "X";
  closeButton.style.background = "none";
  closeButton.style.border = "none";
  closeButton.style.fontSize = "20px";
  closeButton.style.cursor = "pointer";
  closeButton.onclick = () => {
    // Re-enable pump processing when closing the modal
    axios
      .post("/api/enablePumpProcessing", {
        headers: {
          "Content-Type": "application/json",
        },
      })
      .catch((err) => console.error("Failed to re-enable pump processing:", err));

    document.body.removeChild(modalContainer);
  };

  // Add close tank shift button
  const closeTankShiftButton = document.createElement("button");
  closeTankShiftButton.textContent = "Close Tank Shift";
  closeTankShiftButton.className = "btn btn-danger";
  closeTankShiftButton.style.marginLeft = "10px";
  closeTankShiftButton.onclick = () => {
    closeAllTankShifts();
    document.body.removeChild(modalContainer);
  };

  header.appendChild(title);
  header.appendChild(closeButton);
  header.appendChild(closeTankShiftButton);

  // Create table
  const table = document.createElement("table");
  table.className = "table table-bordered";

  // Create table header
  const thead = document.createElement("thead");
  thead.innerHTML = `
        <tr>
            <th>Pump ID</th>
            <th>Opening Totalizer</th>
            <th>Closing Totalizer</th>
            <th>Action</th>
        </tr>
    `;

  // Create table body
  const tbody = document.createElement("tbody");

  // Add rows for each pump
  pumps.forEach((pump) => {
    const tr = document.createElement("tr");
    tr.id = `pump-row-${pump.id}`;

    // Format opening totalizer to display properly
    const openingTotalizer = pump.opening_fuel / 100;

    tr.innerHTML = `
            <td>${pump.pump_id}</td>
            <td>${openingTotalizer}</td>
            <td><input type="number" id="closingTotalizer_${pump.id}" class="form-control" step="0.01" /></td>
            <td><button id="closeButton_${pump.id}" class="btn btn-danger">Close</button></td>
        `;

    tbody.appendChild(tr);
  });

  // Assemble the table
  table.appendChild(thead);
  table.appendChild(tbody);

  // Assemble the modal
  modalContent.appendChild(header);
  modalContent.appendChild(table);
  modalContainer.appendChild(modalContent);

  // Add to document
  document.body.appendChild(modalContainer);

  // Add event listeners for close buttons
  pumps.forEach((pump) => {
    const closeButton = document.getElementById(`closeButton_${pump.id}`);
    closeButton.addEventListener("click", async () => {
      const closingTotalizerInput = document.getElementById(
        `closingTotalizer_${pump.id}`
      );
      const closingTotalizer = parseFloat(closingTotalizerInput.value);

      if (!closingTotalizer || isNaN(closingTotalizer)) {
        alert("Please enter a valid closing totalizer value");
        return;
      }

      // Disable the button to prevent multiple clicks
      closeButton.disabled = true;
      closeButton.textContent = "Processing...";

      try {
        // Close the shift
        const closeResponse = await axios.post(`/api/shift/close/${pump.id}`, {
          closing_fuel_manual: Math.round(closingTotalizer * 100), // Convert to cents
        });

        // Update the row to show it's been processed
        const row = document.getElementById(`pump-row-${pump.id}`);
        row.style.backgroundColor = "#d4edda";
        closeButton.textContent = "Closed";
        closeButton.className = "btn btn-success";
        closeButton.disabled = true;
        closingTotalizerInput.disabled = true;

        // Check if all pumps are closed
        const activeButtons = document.querySelectorAll(
          ".modal-content button:not(:disabled)"
        );
        if (activeButtons.length === 0) {
          // All shifts closed, re-enable pump processing
          await axios.post("/api/enablePumpProcessing", {
            headers: {
              "Content-Type": "application/json",
            },
          });

          // Show success message
          alert("All shifts have been closed successfully");

          // Refresh pump data
          fetchPumpData();

          // Close the modal
          document.body.removeChild(modalContainer);
        }
      } catch (error) {
        alert(`Error closing shift: ${error.message}`);
        closeButton.disabled = false;
        closeButton.textContent = "Close";
      }
    });
  });
};

// REMOVED the displayTankClosingPopup function as it's replaced by the Vue template

// Usage
// document.querySelector('.shift-close').addEventListener('click', closeShift);
// document.querySelector('.shift-close-all').addEventListener('click', closeAllShifts);
</script>

<style scoped>
.pump-card {
  cursor: pointer;
  border: 1px solid #ddd;
  margin-bottom: 1rem;
}

.card-img-top {
  max-height: 100px;
  object-fit: contain;
  width: 40%;
}

/* Styles for the Sale History Popup */
.sale-history-popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000; /* Ensure it's above other content */
}

.sale-history-popup .overlay_bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black */
}

.sale-history-popup .sale-history-content {
  position: relative; /* To stay above overlay */
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  width: 90%;
  max-width: 1000px; /* Adjust max-width as needed */
  max-height: 85vh; /* Limit height */
  overflow-y: auto; /* Enable scrolling for content */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.sale-history-popup .close {
  font-size: 1.5rem;
  font-weight: bold;
  color: #6c757d;
  text-decoration: none;
}
.sale-history-popup .close:hover {
  color: #343a40;
}

.sale-history-popup .table-responsive {
  max-height: 60vh; /* Adjust based on overall popup height */
  overflow-y: auto;
}

/* Styles for the Tank Closing Popup */
.tank-closing-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

.tank-closing-modal .modal-content {
  background-color: white;
  padding: 0; /* Remove padding, handled by header/body/footer */
  border-radius: 8px;
  width: 90%;
  max-width: 900px;
  max-height: 90vh;
  display: flex;
  flex-direction: column; /* Stack header, body, footer */
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.tank-closing-modal .modal-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tank-closing-modal .modal-body {
    padding: 1.5rem;
    overflow-y: auto; /* Scroll only the body */
    flex-grow: 1; /* Allow body to take available space */
}

.tank-closing-modal .modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end; /* Align buttons to the right */
    gap: 0.5rem; /* Space between buttons */
}

.tank-closing-modal .table {
    margin-bottom: 0; /* Remove default bottom margin */
}

.tank-closing-modal .closing-input {
    min-width: 120px; /* Ensure input is not too small */
}

/* Add styles for success/error states if needed */
.table-success {
    background-color: #d1e7dd !important; /* Use !important if needed to override */
}
.table-danger {
     background-color: #f8d7da !important;
}


</style>
