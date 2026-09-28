<template>
  <div>
    <ItemLoader
      ref="itemLoader"
      :api-endpoint="apiEndpoint"
      title="Sales List"
      :extra-args="queryParams"
    >
      <template #item-detail="{ item }">
        <div class="card mb-3 saleRow">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <div class="p-2">
                <strong>Date:</strong> {{ new Date(item.pdate).getFullYear() }}-{{
                  new Date(item.pdate).getMonth() + 1
                }}-{{ new Date(item.pdate).getDate() }}
              </div>
              <div class="p-2">
                <strong>Time:</strong> {{ new Date(item.pdate).getHours() }}:{{
                  new Date(item.pdate).getMinutes()
                }}:{{ new Date(item.pdate).getSeconds() }}
              </div>
              <div class="p-2"><strong>POS ID:</strong> {{ item.pos_id }}</div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="p-2"><strong>Nozel ID:</strong> {{ item.FC_NZNo }}</div>
              <div class="p-2"><strong>Product:</strong> {{ item.product.ITMNAME }}</div>
              <div class="p-2">
                <strong>Price Rate:</strong> {{ item.product.PRATE }} /
                {{ item.product.UOM }}
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="p-2">
                <strong>Quantity:</strong> {{ item.qty / 100 }} {{ item.product.UOM }}
              </div>
              <div class="p-2"><strong>Amount:</strong> {{ item.amt / 100 }}</div>
              <div class="p-2">
                <strong>Payment Method:</strong> {{ item.payment_method.Des }}
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="p-2"><strong>Customer:</strong> {{ item.customer.Des }}</div>
              <div class="p-2"><strong>Vehicle:</strong> {{ item.RegNO }}</div>
              <div class="p-2"><strong>Shift ID:</strong> {{ item.shift_id }}</div>
            </div>
            <div class="d-flex justify-content-end">
              <button class="btn btn-primary" @click="openPrintModal(item)">
                <i class="lni lni-printer"></i> Print
              </button>
            </div>
          </div>
        </div>
      </template>
      <template #actionButtons="{ items, refresh }">
        <div>
          <button class="btn btn-primary" @click="openFilterModal">Filter</button>
          <pre>{{ isFilterModalOpen }}</pre>
          <div class="row" v-if="isFilterModalOpen">
            <nav class="navbar navbar-expand-lg rounded navbar-light bg-white">
              <div class="container-fluid">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li>
                    <label for="nozzles" class="filter-label me-2">Nozzles:</label>
                    <select
                      id="nozzles"
                      name="nozzles[]"
                      multiple
                      class="form-control me-2"
                      v-model="selectedNozzles"
                      style="width: 150px"
                    >
                      <option
                        v-for="nozzle in nozzles"
                        :key="nozzle"
                        :value="nozzle.FC_NZNo"
                      >
                        {{ nozzle.FC_NZNo }}
                      </option>
                    </select>
                  </li>
                  <li class="ms-2">
                    <label for="startDate" class="filter-label me-2">Start Date:</label>
                    <input
                      type="datetime-local"
                      id="startDate"
                      name="startDate"
                      class="form-control me-2"
                      v-model="startDate"
                      style="width: 150px"
                    />
                  </li>
                  <li class="ms-2">
                    <label for="endDate" class="filter-label me-2">End Date:</label>
                    <input
                      type="datetime-local"
                      id="endDate"
                      name="endDate"
                      class="form-control me-2"
                      v-model="endDate"
                      style="width: 150px"
                    />
                  </li>
                  <li class="ms-2">
                    <label for="qtyMin" class="filter-label me-2">Qty Min:</label>
                    <input
                      type="text"
                      id="qtyMin"
                      name="qtyMin"
                      class="form-control me-2"
                      v-model="qtyMin"
                      style="width: 100px"
                    />
                  </li>
                  <li class="ms-2">
                    <label for="qtyMax" class="filter-label me-2">Qty Max:</label>
                    <input
                      type="text"
                      id="qtyMax"
                      name="qtyMax"
                      class="form-control me-2"
                      v-model="qtyMax"
                      style="width: 100px"
                    />
                  </li>
                  <li class="ms-2">
                    <button class="cstm_btn btn-primary me-3" @click="applyFilter">
                      <i class="bx bx-navigation"></i> Apply Filter
                    </button>
                    <button class="cstm_btn btn-danger" @click="clearFilters">
                      <i class="bx bx-x"></i> Clear
                    </button>
                  </li>
                </ul>
              </div>
            </nav>
          </div>
        </div>
      </template>
    </ItemLoader>
    <div title="Print Sale">
      <Modal :show="showPrintModal" @close="() => (showPrintModal = false)">
        <div class="modal-header">
          <h5 class="modal-title">Sale Details</h5>
          <button
            type="button"
            class="btn-close"
            @click="() => (showPrintModal = false)"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="sale-date" class="form-label"
                  ><strong>Date/Time</strong></label
                >
                <input
                  id="sale-date"
                  type="text"
                  class="form-control"
                  :value="selectedSale.pdate"
                  readonly
                />
              </div>
              <div class="col-md-6">
                <label for="sale-qty" class="form-label"><strong>Quantity</strong></label>
                <input
                  id="sale-qty"
                  type="text"
                  class="form-control"
                  :value="(selectedSale.qty / 100).toFixed(2)"
                  readonly
                />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="sale-amount" class="form-label"
                  ><strong>Amount</strong></label
                >
                <input
                  id="sale-amount"
                  type="text"
                  class="form-control"
                  :value="(selectedSale.amt / 100).toFixed(2)"
                  readonly
                />
              </div>
              <div class="col-md-6">
                <label for="nozzle-id" class="form-label"
                  ><strong>Nozzle ID</strong></label
                >
                <input
                  id="nozzle-id"
                  type="text"
                  class="form-control"
                  :value="selectedSale.FC_NZNo"
                  readonly
                />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="payment-method" class="form-label"
                  ><strong>Total Price</strong></label
                >
                <input
                  id="payment-method"
                  type="text"
                  class="form-control"
                  :value="selectedSale.total_price"
                  readonly
                />
              </div>
              <div class="col-md-6">
                <label for="customer-name" class="form-label"
                  ><strong>Customer</strong></label
                >
                <select
                  id="customer-name"
                  class="form-select"
                  v-model="selectedSale.customer"
                >
                  <option value="" disabled>Please select a customer</option>
                  <option
                    v-for="customer in pageData.customers"
                    :key="customer.id"
                    :value="customer.id"
                  >
                    {{ customer.Des }}
                  </option>
                </select>
                <!-- <pre>{{JSON.stringify(pageData.customers)}}</pre> -->
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="vehicle-reg-no" class="form-label"
                  ><strong>Vehicle</strong></label
                >
                <!-- <pre>{{ JSON.stringify(vehicles) }}</pre> -->
                <select
                  id="vehicle-reg-no"
                  class="form-select"
                  v-model="selectedSale.vehicle_reg_no"
                >
                  <option value="" disabled>Select Vehicle</option>
                  <option
                    v-for="vehicle in vehicles"
                    :key="vehicle.id"
                    :value="vehicle.reg_no"
                  >
                    {{ vehicle.RegNO }}
                  </option>
                </select>
              </div>
            </div>
            <div class="d-none d-sm-block">
              <div class="row">
                <!-- Payment method cards -->
                <div class="col-md-2 col-lg-2 col-3">
                  <div class="card payment-card" data-value="1" data-customer-required="">
                    <img class="card-img-top" src="/assets/images/cash.png" alt="Cash" />
                    <div class="card-body text-center">
                      <p class="card-text">Cash</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-3">
                  <div
                    class="card payment-card"
                    data-value="2"
                    data-customer-required="1"
                  >
                    <img
                      class="card-img-top"
                      src="/assets/images/bank_image.png"
                      alt="Credit Customer"
                    />
                    <div class="card-body text-center">
                      <p class="card-text">Credit Customer</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-3">
                  <div class="card payment-card" data-value="3" data-customer-required="">
                    <img
                      class="card-img-top"
                      src="/assets/images/PSO_Logo.png"
                      alt="PSO Card"
                    />
                    <div class="card-body text-center">
                      <p class="card-text">PSO Card</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-3">
                  <div class="card payment-card" data-value="4" data-customer-required="">
                    <img
                      class="card-img-top"
                      src="/assets/images/UBL.png"
                      alt="UBL Bank"
                    />
                    <div class="card-body text-center">
                      <p class="card-text">UBL Bank</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-3">
                  <div class="card payment-card" data-value="5" data-customer-required="">
                    <img
                      class="card-img-top"
                      src="/assets/images/
mcb.png"
                      alt="MCB Bank"
                    />
                    <div class="card-body text-center">
                      <p class="card-text">MCB Bank</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            @click="() => (showPrintModal = false)"
          >
            Close
          </button>
          <button type="button" class="btn btn-primary" @click="printSale">Print</button>
        </div>
      </Modal>
    </div>

    <Modal ref="filterModal" title="Filter Sales">
      <template #body>
        <div class="card-body">
          <form @submit.prevent="applyFilter">
            <div class="form-group">
              <label for="date">Date</label>
              <input type="date" id="date" v-model="filter.date" class="form-control" />
            </div>
            <div class="form-group">
              <label for="payment_method">Payment Method</label>
              <select
                id="payment_method"
                v-model="filter.payment_method"
                class="form-control"
              >
                <option value="">All</option>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
              </select>
            </div>
            <div class="form-group">
              <label for="customer_name">Customer Name</label>
              <input
                type="text"
                id="customer_name"
                v-model="filter.customer_name"
                class="form-control"
              />
            </div>
            <button type="submit" class="btn btn-primary">Apply Filter</button>
          </form>
        </div>
      </template>
    </Modal>
  </div>
</template>
<script setup>
import { ref, reactive, onMounted, watch, watchEffect } from "vue";
import ItemLoader from "./ItemLoader.vue";
import Modal from "./Modal.vue";

const selectedSale = ref({});
const filter = reactive({
  date: "",
  payment_method: "",
  customer_name: "",
  // vehicle_reg_no:'',
});
const itemLoader = ref(null);
const printModal = ref(null);
const filterModal = ref(null);
const showPrintModal = ref(false);
const pageData = reactive({});
const apiEndpoint = ref("/api/sales");
const queryParams = ref("");
const vehicles = ref([]); // To store the vehicle data
const isFilterModalOpen = ref(false);
const nozzles = ref([]);
const selectedNozzles = ref([]);
const startDate = ref("");
const endDate = ref("");
const qtyMin = ref("");
const qtyMax = ref("");
const amountMin = ref("");
const amountMax = ref("");

function clearFilters() {
  // Clear the filter fields
  selectedNozzles.value = [];
  startDate.value = "";
  endDate.value = "";
  qtyMin.value = "";
  qtyMax.value = "";
}
onMounted(async () => {
  const responseProducts = await fetch("/api/products");
  const responsePaymentMethods = await fetch("/api/payment-methods");
  const responseCustomers = await fetch("/api/customers");
  const responseNozzles = await fetch("/api/nozzles");

  const products = await responseProducts.json();
  const paymentMethods = await responsePaymentMethods.json();
  const customers = await responseCustomers.json();
  nozzles.value = await responseNozzles.json();
  pageData.products = products;
  pageData.paymentMethods = paymentMethods;
  pageData.customers = customers;
});

function fetchVehiclesByCustomer(customerId) {
  fetch(`/getVehicles?customer_id=${customerId}`)
    .then((response) => response.json())
    .then((data) => {
      console.log("Fetched Vehicles:", data); // Debug response
      vehicles.value = data;
    })
    .catch((error) => {
      console.error("Error fetching vehicles:", error);
      vehicles.value = [];
    });
}

function openPrintModal(sale) {
  selectedSale.value = sale;
  showPrintModal.value = true;
}

function openFilterModal() {
  isFilterModalOpen.value = true;
}

function applyFilter() {
  const queryParamss = [];
  if (selectedNozzles.value.length > 0) {
    queryParamss.push(`nozzles=${selectedNozzles.value.join(",")}`);
  }
  if (startDate) {
    queryParamss.push(`startDate=${startDate.value}`);
  }
  if (endDate) {
    queryParamss.push(`endDate=${endDate.value}`);
  }
  if (qtyMin) {
    queryParamss.push(`qtyMin=${qtyMin.value}`);
  }
  if (qtyMax) {
    queryParamss.push(`qtyMax=${qtyMax.value}`);
  }
  if (amountMin) {
    queryParamss.push(`amountMin=${amountMin.value}`);
  }
  if (amountMax) {
    queryParamss.push(`amountMax=${amountMax.value}`);
  }

  const query = queryParamss.join("&");
  queryParams.value = query;
  console.log(queryParams.value);
  itemLoader.value.search();
}

watch(queryParams, (newVal) => {
  console.log("QueryParams updated:", newVal);
});

// Watch for customer selection to fetch the corresponding vehicles
watch(
  () => selectedSale.value.customer,
  (newCustomerName) => {
    if (newCustomerName) {
      fetchVehiclesByCustomer(newCustomerName); // Fetch vehicles when a customer is selected
    }
  }
);
</script>

<style setup>
.payment-card img {
  width: 100%;
  height: auto;
  max-height: 50px; /* Adjust based on your design requirements */
  object-fit: contain;
}
</style>
