<template>
  <div>
    <!-- Header for Current Date -->
    <div id="current-date">{{ currentDate }}</div>
    <div class="page-breadcrumb  d-sm-flex align-items-center mb-3">
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
    <div id="pump-cards" class="row">
      <template v-if="showAllResults">
        <div
          v-for="pump in pumpCards"
          :key="pump.id"
          class="col-xl-2 col-lg-3 col-md-4 col-sm-6"
        >
          <div class="card pump-card">
            <img :src="getPumpImage(pump)" class="card-img-top" alt="Nozzle" />
            <div>
                <div class="position-absolute top-0 end-0 m-3 product-discount">
                  <b>{{ pump.display_name }}</b>
                </div>
            </div>
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="last_sale">
                  <p class="mb-0 ms-auto">Last Sale:</p>
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
                    <span class="rate">{{ pump.shift.rate }}</span>
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
                ><b>{{ pump.FC_NZNo }}</b></span
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
      <div class="new_side_inner sale-history-content" style="z-index: 1001">
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

<!-- Tank Closing Popup Component -->
    <TankClosingPopup
      v-model:show="showTankClosingPopup"
      :shifts="activeTankShiftsForPopup"
      @submit="onTankClosingSuccess"
      @cancel="onTankClosingCancel"
    />

<!--      <div v-if="shiftCloseMessage" class="alert alert-info mt-3">-->
<!--          {{ shiftCloseMessage }}-->
<!--      </div>-->
      <SuccessPopup
          v-model:show="showShiftClosedPopup"
          :title="popupType === 'error' ? 'Error' : 'Shift Closed'"
          :message="popupMessage"
          :type="popupType"
          primaryButtonText="View Shift Logs"
          @primaryAction="goToShiftLogs"
      />
<!--      <button-->
<!--          @click="openPopup('success', 'Shift closed successfully (test).')"-->
<!--          class="bg-green-600 text-white px-4 py-2 rounded-lg">-->
<!--          Test Success Popup-->
<!--      </button>-->
<!--      <NewManualClosing-->
<!--          ref="newManualClosingRef"-->
<!--          :tanks="manualPopupTanks"-->
<!--      />-->
      <NewManualClosing
          ref="newManualClosingRef"
          :tanks="manualPopupTanks"
          @manualClosingCompleted="handleAfterManualClosing"
      />
  </div>


</template>

<script setup>
import { ref, onMounted, reactive, computed } from "vue";
import TankClosingPopup from './TankClosingPopup.vue';
import SuccessPopup from "./SuccessPopup.vue"
import NewManualClosing from "./NewManualClosing.vue"
const isCloseAllShiftsButtonDisabled = ref(false);
import axios from "axios";

// --- Tank Closing Popup State ---
const showTankClosingPopup = ref(false);
const activeTankShiftsForPopup = ref([]);
const tankClosingMMValues = ref({}); // New: Stores MM values entered by user
const calculatedClosingTotalizers = ref({}); // New: Stores calculated totalizer values
const isSubmittingTankClosing = ref(false);
const tankClosingError = ref(null); // For general errors during submission

// --- Sales History State ---
const showSaleHistoryPopup = ref(false);
const saleHistoryData = ref([]);
const activeSaleHistoryPump = ref(null);
const saleHistoryPageNo = ref(1);
const saleHistoryLoading = ref(false);
const saleHistoryHasMore = ref(true);
// const showSuccessPopup = ref(false);
// --- End Sales History State ---

const props = defineProps({
  showAllResults: {
    type: Boolean,
    default: false,
  },
});
const onTankClosingCancel = () => {
    // Optional: popup band karne ya koi aur action ke liye
    showTankClosingPopup.value = false;
    // console.log("Tank closing canceled");
};
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
const sysConfig = reactive({});
const tanks = ref([]);
const showShiftClosedPopup = ref(false);
const popupMessage = ref("");
const popupType = ref("success");
// const shiftCloseMessage = ref("");
const getPublicSettings = async () => {
  const response = await axios.get("/api/settings/public");
  publicSettings.value = {};
  response.data.forEach((setting) => {
    publicSettings.value[setting.key] = setting.value;
  });
  return publicSettings;
};
const getSysConfig = async () => {
  const response = await axios.get("/api/sysconfig");
  sysConfig.value = response.data;
  return sysConfig;
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
    const response = await fetch(`/api/shift/check/${pump.FC_NZNo}`);
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
  getSysConfig();
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
// new code sanat
// Fetch Active Tank Shifts from API
const fetchActiveTankShifts = async () => {
    try {
        const response = await axios.get("/api/tanks/tank-shifts");
        if (!response.data || !Array.isArray(response.data)) {
            console.error("Invalid response format for active tank shifts:", response.data);
            alert("Could not fetch active tank shifts. Invalid data format.");
            return [];
        }
        return response.data;
    } catch (error) {
        console.error("Error fetching active tank shifts:", error);
        alert(`Error fetching active tank shifts: ${error.message}`);
        return [];
    }
};

// Handle Manual Tank Shift Closing (Only when ATG is NOT installed)
// const handleManualTankShiftClosing = async () => {
//     const fetchedShifts = await fetchActiveTankShifts();
//     if (fetchedShifts.length > 0) {
//         activeTankShiftsForPopup.value = fetchedShifts.map(shift => ({
//             ...shift,
//             closedSuccess: false,
//             closedError: false,
//             errorMessage: null
//         }));
//
//         // Initialize MM Values and Calculated Totalizers
//         tankClosingMMValues.value = fetchedShifts.reduce((acc, shift) => {
//             acc[shift.id] = null;
//             return acc;
//         }, {});
//
//         calculatedClosingTotalizers.value = fetchedShifts.reduce((acc, shift) => {
//             acc[shift.id] = null;
//             return acc;
//         }, {});
//
//         tankClosingError.value = null; // Reset error
//         isSubmittingTankClosing.value = false; // Reset submitting state
//         // console.log(publicSettings.value.atg_installed == 1 || publicSettings.value.atg_installed == "1" ,'sanat');
//         if (publicSettings.value.atg_installed == 1 || publicSettings.value.atg_installed == "1"){
//             showTankClosingPopup.value = false; // Open Manual Tank Popup
//             await onTankClosingSuccess();
//
//         }else {
//             showTankClosingPopup.value = true; // Open Manual Tank Popup
//
//         }
//     } else {
//         alert("No active tank shifts found to close manually.");
//         isCloseAllShiftsButtonDisabled.value = false;
//     }
// };
const handleManualTankShiftClosing = async () => {
    const fetchedShifts = await fetchActiveTankShifts();
    if (fetchedShifts.length > 0) {
        activeTankShiftsForPopup.value = fetchedShifts.map(shift => ({
            ...shift,
            closedSuccess: false,
            closedError: false,
            errorMessage: null
        }));

        // Initialize MM Values and Calculated Totalizers
        tankClosingMMValues.value = fetchedShifts.reduce((acc, shift) => {
            acc[shift.id] = null;
            return acc;
        }, {});

        calculatedClosingTotalizers.value = fetchedShifts.reduce((acc, shift) => {
            acc[shift.id] = null;
            return acc;
        }, {});

        tankClosingError.value = null;
        isSubmittingTankClosing.value = false;
        const isAtgInstalled = Number(sysConfig.value?.ATGEnable ?? 0);

        if (isAtgInstalled === 0) {
            // 🔹 Always open popup here (manual only)
            showTankClosingPopup.value = true;
        }

    } else {
        alert("No active tank shifts found to close manually.");
        isCloseAllShiftsButtonDisabled.value = false;
    }
};

// Handle Pump Shift Flow when ATG is enabled
const handlePumpShiftFlow = async () => {
    try {
        // Disable Pump Processing first
        await axios.post("/api/disablePumpProcessing");

        // Fetch Active Pump Shifts
        const pumpsResponse = await axios.get("/api/shifts");
        const pumpsData = pumpsResponse.data;

        const validPumps = pumpsData.filter(pump => pump.pump_id);
        if (validPumps.length === 0) {
            throw new Error("No active shifts found to close");
        }

        // Display Pumps Popup
        displayPumpsPopup(validPumps);

    } catch (error) {
        console.error("Error in Pump Processing:", error);

        // Re-enable Pump Processing on failure
        try {
            await axios.post("/api/enablePumpProcessing");
        } catch (err) {
            console.error("Failed to re-enable pump processing:", err);
        }

        alert(`Error during pump processing: ${error.message}`);
    }
};

// Main Button Click Handler
const closeAllShiftsButtonClicked = async () => {
    isCloseAllShiftsButtonDisabled.value = true;

    // First attempt to close Tank Shifts (ATG automatic shifts if applicable)
    await closeAllTankShifts();
    // console.log(publicSettings.value.atg_installed == 1 || publicSettings.value.atg_installed == "1" ,'sanat');

    // if (publicSettings.value.atg_installed == 1 || publicSettings.value.atg_installed == "1") {
    if (publicSettings.require_manual_totalizer_during_shift_close == 1) {
        // ATG is enabled -> Show Pump Popup Flow only
        await handlePumpShiftFlow();
    } else {
        // ATG is NOT enabled -> Show Manual Tank Popup Flow
        await handleManualTankShiftClosing();
    }
};

const openPopup = (type, message) => {
    popupType.value = type;
    popupMessage.value = message || (type === "error" ? "Something went wrong!" : "Operation successful.");
    showShiftClosedPopup.value = true;
};

const goToShiftLogs = () => {

    window.location.href = window.shiftLogsUrl;

};
// On Successful Tank Closing Submission
const onTankClosingSuccess = async () => {
    showTankClosingPopup.value = false;
    isCloseAllShiftsButtonDisabled.value = true;
    try {

        const response = await fetch("/api/shift/closeAllShifts");
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || "Something went wrong.");
        }
            const data = await response.json();
            // ✅ Call common popup function
        openPopup("success", data.message);

        // const data = await response.json();
        // shiftCloseMessage.value = data.message;
    } catch (error) {
        // alert()
        openPopup("error", error.message);
        // shiftCloseMessage.value = `Error: ${error.message}`;
    }
};

// Handle Cancel in Tank Popup
const handleTankModalCancel = () => {
    showTankClosingPopup.value = false;
};
const manualPopupTanks = ref([]);        // yahan non-ATG tanks ka data aayega
const newManualClosingRef = ref([]);  // popup component ka reference
const closeAllTankShifts = async () => {
    const isAtgInstalled = Number(sysConfig.value?.ATGEnable ?? 0);
    const installationMode = Number(sysConfig.value?.Sys_Mode ?? 1);

    if (isAtgInstalled === 0) {
        await handleManualTankShiftClosing();
        return;
    }

    // 🔹 MODE 2: Dispenser + ATG
    if (installationMode === 2) {
        try {
            // 1️⃣ Tanks fetch karo
            const tanksResponse = await axios.get("/api/atg-config");
            const tanks = tanksResponse.data || [];

            const withAtgTanks = tanks.filter(t => Number(t.bMode) === 1);
            const withoutAtgTanks = tanks.filter(t => !t.bMode || Number(t.bMode) === 0);

            // 2️⃣ Manual popup agar koi non-ATG tank hai
            if (withoutAtgTanks.length > 0) {
                manualPopupTanks.value = withoutAtgTanks;

                if (newManualClosingRef.value?.openManualClosing) {
                    newManualClosingRef.value.openManualClosing(withoutAtgTanks);
                } else {
                    alert("Manual Closing popup not ready yet");
                }
                return; // Wait for manual dips
            }

            // 3️⃣ No manual tanks → directly handle ATG + pumps
            await handleAfterManualClosing();
        } catch (err) {
            console.error("❌ Error in mode 2 closing:", err);
            openPopup("error", err.message || "Error closing shifts.");
        }
    }
};

// 🔹 Called after manual popup completes
const handleAfterManualClosing = async () => {
    try {

        const tanksResponse = await axios.get("/api/atg-config");
        const tanks = tanksResponse.data || [];
        // const withAtgTanks = tanks.filter(t => Number(t.bMode) === 1);
        const atgTankIds = tanks
            .filter(t => Number(t.bMode) === 1)
            .map(t => t.id);

        // 1️⃣ Close ATG tanks
        if (atgTankIds.length > 0) {
            const res = await axios.post("/api/tanks/tank-shifts/close-atg-all-only", {
                tank_ids: atgTankIds,
            });
            console.log("✅ ATG tanks closed:", res.data);
        }

        // 2️⃣ Close pump shifts
        const pumpResponse = await fetch("/api/shift/closeAllShifts");
        if (!pumpResponse.ok) {
            const errorData = await pumpResponse.json();
            throw new Error(errorData.error || "Pump shift close failed.");
        }

        const pumpData = await pumpResponse.json();
        console.log("✅ Pump shifts closed:", pumpData);
        openPopup("success", pumpData.message || "All tank & pump shifts closed successfully.");
    } catch (err) {
        console.error("❌ Error after manual closing:", err);
        openPopup("error", err.message || "Failed to close ATG or pump shifts.");
    }
};




//end code
//start code  old gulbaz

// end gulbaz
// New function to calculate totalizer from MM
const calculateTotalizerForTank = async (shiftId, tankId, millimeterValue) => {
    if (millimeterValue === null || millimeterValue === '' || isNaN(parseFloat(millimeterValue))) {
        calculatedClosingTotalizers.value[shiftId] = null;
        return;
    }

    try {
        const response = await axios.post('/api/tanks/convert-mm-to-totalizer', {
            tank_id: tankId,
            millimeter_value: parseFloat(millimeterValue)
        });
        calculatedClosingTotalizers.value[shiftId] = (response.data.totalizer_value).toFixed(2); // Assuming API returns cents
    } catch (error) {
        console.error(`Error converting MM to totalizer for tank ${tankId}:`, error);
        calculatedClosingTotalizers.value[shiftId] = 'Error';
        // Optionally, display an error message to the user for this specific tank
        const shift = activeTankShiftsForPopup.value.find(s => s.id === shiftId);
        if (shift) {
            shift.closedError = true;
            shift.errorMessage = 'Conversion failed.';
        }
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
        const closingMMValue = tankClosingMMValues.value[shift.id];
        const calculatedTotalizer = calculatedClosingTotalizers.value[shift.id];

        if (closingMMValue === null || closingMMValue === '' || isNaN(parseFloat(closingMMValue)) || parseFloat(closingMMValue) < 0) {
            shift.closedError = true;
            shift.errorMessage = 'Invalid or missing MM value.';
            if (!firstError) firstError = `Invalid closing MM for ${shift.tank_name || `Tank ID: ${shift.tank_id}`}.`;
            allSuccessful = false;
            continue; // Skip API call for this invalid entry, but check others
        }
        if (calculatedTotalizer === null || calculatedTotalizer === '' || isNaN(parseFloat(calculatedTotalizer)) || parseFloat(calculatedTotalizer) < 0) {
            shift.closedError = true;
            shift.errorMessage = 'Calculated totalizer is invalid.';
            if (!firstError) firstError = `Calculated totalizer is invalid for ${shift.tank_name || `Tank ID: ${shift.tank_id}`}.`;
            allSuccessful = false;
            continue; // Skip API call for this invalid entry, but check others
        }

        // Step 1: Update the shift with the manual closing MM and calculated totalizer
        try {
            await axios.post(`/api/tanks/tank-shifts/update`, {
                id: shift.id,
                manual_closing_mm: Math.round(parseFloat(closingMMValue) * 100), // Send as integer/cents
                manual_closing_totalizer: Math.round(parseFloat(calculatedTotalizer) * 100), // Send as integer/cents
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
                 if (tankClosingMMValues.value.hasOwnProperty(shift.id)) {
                     shift.closedSuccess = true;
                 }
             });

            // alert("All tank shifts updated and closed successfully.");
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
        tankClosingMMValues.value = {}; // Reset MM values
        calculatedClosingTotalizers.value = {}; // Reset calculated totalizers
        isCloseAllShiftsButtonDisabled.value = false; // Re-enable main button
    }
};
// --- End Tank Closing Popup Methods ---


const displayPumpsPopup = (pumps) => {
    // alert(1);
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

/* Fade animation */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fadeIn 0.2s ease-out;
}
</style>
