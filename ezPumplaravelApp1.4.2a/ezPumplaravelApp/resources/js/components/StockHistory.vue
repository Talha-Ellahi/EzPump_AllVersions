<template>
    <div class="container mt-4">

        <!-- ================= HEADER + ADD BUTTON ================= -->

        <div class="d-flex justify-content-between mb-3">

            <input type="text"
                   class="form-control w-25"
                   placeholder="Search..."
                   v-model="searchQuery">

<!--            <button class="btn btn-success"-->
<!--                    @click="openModal">-->
<!--                ➕ Add Adjustment-->
<!--            </button>-->

           
        </div>

        <!-- ================= HISTORY TABLE ================= -->

        <div class="card">
            <div class="card-header">
                <h2>Stock History && Purchase && Adjustment Report</h2>
            </div>

            <div class="card-body">

                <div v-if="loading" class="text-center">
                    <div class="spinner-border"></div>
                </div>

                <div v-else class="table-responsive">

                    <table class="table table-hover">

                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Tank</th>
                            <th>Type</th>
                            <th>Vendor</th>
                            <th>Stock</th>
                            <th>Millimeter</th>
                            <th>Comments</th>
                            <th  >Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="entry in filteredHistory"
                            :key="entry.id">

                            <td>{{ formatDate(entry.created_at) }}</td>
                            <td>{{ entry.tank?.tank_name }}</td>
                            <td>{{ entry.transaction_type }}</td>
                            <td>{{ entry.vendor?.VENDOR_NAME || '—' }}</td>

                            <td :class="entry.stock_change > 0 ? 'text-success' : 'text-danger'">
                                {{ entry.stock_change / 100 }}
                            </td>

                            <td>{{ entry.millimeter }}</td>
                            <td>{{ entry.comments }}</td>

                            <td>

                                <a v-if="sysMode == 3" :href="`/purchaseReport/${entry.id}`"
                                   class="btn btn-primary btn-sm">
                                    Report
                                </a>&nbsp;
                                <a v-if="entry.adjustment == 1  && sysMode == 3"
                                   :href="`/adjustment_report/${entry.id}`"
                                   class="btn btn-info btn-xs"> Adjustment </a>

                                <!-- 🔥 EDIT BUTTON -->
                                <button  v-if="entry.transaction_type === 'stock_adjustment' && sysMode == 3"
                                        class="btn btn-warning btn-sm ms-2"
                                        @click="editAdjustment(entry)">
                                    Edit
                                </button>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>
                <nav v-if="totalPages > 1" aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item" :class="{disabled: currentPage === 1}">
                            <button class="page-link" @click="changePage(currentPage - 1)">Previous</button>
                        </li>
                        <li
                            v-for="page in pagesArray"
                            :key="page"
                            class="page-item"
                            :class="{active: page === currentPage}"
                        >
                            <button class="page-link" @click="changePage(page)">{{ page }}</button>
                        </li>
                        <li class="page-item" :class="{disabled: currentPage === totalPages}">
                            <button class="page-link" @click="changePage(currentPage + 1)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

        <!-- ================= PAGINATION ================= -->



        <!-- ================= MODAL ================= -->

        <div v-if="showModal">

            <div class="modal fade show"
                 style="display:block;">

                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold">
                                {{ isEditMode ? 'Edit Stock Adjustment' : 'Add Stock Adjustment' }}
                            </h5>
                            <button type="button" class="btn-close" @click="closeModal"></button>
                        </div>

                        <div class="modal-body p-4">

                            <form @submit.prevent="submitAdjustment">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Stock Change <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" v-model="adjustForm.stock_change" required placeholder="e.g. 1000">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Millimeter</label>
                                        <input type="number" class="form-control" v-model="adjustForm.millimeter" placeholder="Fuel height">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Vendor <span class="text-danger">*</span></label>
                                        <select class="form-select" v-model="adjustForm.vendor_id" required>
                                            <option value="">Select a Vendor</option>
                                            <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">
                                                {{ vendor.VNAME }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Invoice No</label>
                                        <input type="text" class="form-control" v-model="adjustForm.invoice_no" placeholder="Optional">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Invoice Date</label>
                                        <input type="date" class="form-control" v-model="adjustForm.invoice_date">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Comments</label>
                                        <textarea class="form-control" rows="3" v-model="adjustForm.comments" placeholder="Additional details..."></textarea>
                                    </div>

                                    <div class="col-12 mt-4 text-end">
                                        <button type="button" class="btn btn-secondary me-2 px-4" @click="closeModal">Cancel</button>
                                        <button type="submit" class="btn btn-primary px-4">
                                            {{ isEditMode ? '💾 Update Adjustment' : '➕ Save Adjustment' }}
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>

                    </div>
                </div>

            </div>

            <div class="modal-backdrop fade show"></div>

        </div>

    </div>
</template>

<script setup>

import axios from "axios";
import Swal from "sweetalert2";
import {ref, onMounted, computed} from "vue";
const sysMode = ref(null);
/* ================= PROPS ================= */

const props = defineProps({
    tankId: {
        type: Number,
        required: true
    }
});

/* ================= STATE ================= */

const history = ref([]);
const loading = ref(false);
const searchQuery = ref("");
const transactionTypeFilter = ref('stock_adjustment');
const tankFilter = ref('');
const tanks = ref([]);
const showModal = ref(false);
const isEditMode = ref(false);
const selectedEntryId = ref(null);
const vendors = ref([]);

/* Pagination */
const currentPage = ref(1);
const itemsPerPage = ref(10);
const totalItems = ref(0);

/* ================= FORM ================= */

const adjustForm = ref({
    stock_change: "",
    millimeter: "",
    vendor_id: "",
    invoice_no: "",
    invoice_date: "",
    comments: "",
    net_amount: 0
});

/* ================= LOAD ================= */
async function loadSysMode() {
    try {

        const res = await axios.get("/api/sys-mode");

        sysMode.value = res.data.mode;

    } catch (err) {

        console.error(err);

    }
}
onMounted(() => {
    fetchHistory();
    loadSysMode();
    fetchVendors();
});

/* ================= FETCH VENDORS ================= */
async function fetchVendors() {
    try {
        const res = await axios.get("/api/atg/vendors");

        console.log(res.data); // 👈 check response

        vendors.value = res.data; // direct assign

    } catch (err) {
        console.error("Failed to load vendors:", err);
    }
}

/* ================= FETCH HISTORY ================= */

async function fetchHistory() {

    loading.value = true;

    try {

        const res = await axios.get("/api/tanks/history", {
            params: {
                tank_id: props.tankId,
                page: currentPage.value,
                per_page: itemsPerPage.value
            }
        });

        history.value = res.data.data;
        totalItems.value = res.data.total;

    } catch (err) {

        Swal.fire("Error", "Failed to load history", "error");

    } finally {

        loading.value = false;
    }
}

/* ================= SUBMIT ================= */

async function submitAdjustment() {

    try {

        if (isEditMode.value) {

            await axios.put(
                `/api/tank/update-stock/${selectedEntryId.value}`,
                adjustForm.value
            );

            Swal.fire("Updated", "Adjustment Updated", "success");

        } else {

            await axios.post(
                `/api/tank/add-stock/${props.tankId}`,
                adjustForm.value
            );

            Swal.fire("Success", "Stock Added", "success");

        }

        closeModal();
        resetForm();
        fetchHistory();

    } catch (err) {

        Swal.fire("Error",
            err.response?.data?.message || "Operation Failed",
            "error");

    }
}

/* ================= EDIT ================= */

function editAdjustment(entry) {

    isEditMode.value = true;
    selectedEntryId.value = entry.id;

    adjustForm.value = {
        stock_change: entry.stock_change / 100,
        millimeter: entry.millimeter,
        vendor_id: entry.vendor_id,
        invoice_no: entry.invoice_no,
        invoice_date: entry.invoice_date,
        comments: entry.comments,
        net_amount: entry.net_amount
    };

    openModal();
}

/* ================= MODAL ================= */

function openModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}
function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchHistory();
    }
}
function resetForm() {

    isEditMode.value = false;
    selectedEntryId.value = null;

    adjustForm.value = {
        stock_change: "",
        millimeter: "",
        vendor_id: "",
        invoice_no: "",
        invoice_date: "",
        comments: "",
        net_amount: 0
    };
}

/* ================= PAGINATION ================= */



/* ================= FILTER ================= */

const filteredHistory = computed(() => {
    const searchLower = searchQuery.value.toLowerCase();
    return history.value.filter(entry => {
        const matchesSearch = (
            entry.transaction_type.toLowerCase().includes(searchLower) ||
            entry.comments?.toLowerCase().includes(searchLower) ||
            (entry.tank && entry.tank.tank_name.toLowerCase().includes(searchLower)) ||
            (entry.vendor && entry.vendor.VENDOR_NAME?.toLowerCase().includes(searchLower))
        );
        const matchesTransactionType = transactionTypeFilter.value === '' || entry.transaction_type === transactionTypeFilter.value;
        const matchesTank = tankFilter.value === '' || entry.tank_id == tankFilter.value; // Filter by tank_id
        return matchesSearch && matchesTransactionType && matchesTank;
    });
});

const totalPages = computed(() => {
    return Math.ceil(totalItems.value / itemsPerPage.value);
});

const pagesArray = computed(() => {
    const total = totalPages.value;
    const current = currentPage.value;
    const delta = 2; // how many pages to show before/after current
    let range = [];
    let start = Math.max(2, current - delta);
    let end = Math.min(total - 1, current + delta);

    // Always show first, last, and a window around current
    if (current - delta <= 2) {
        end = Math.min(total - 1, end + (2 - (current - delta)));
    }
    if (current + delta >= total - 1) {
        start = Math.max(2, start - ((current + delta) - (total - 1)));
    }

    for (let i = start; i <= end; i++) {
        range.push(i);
    }

    // Add first page, ellipsis if needed, range, ellipsis if needed, last page
    let pages = [];
    if (total > 1) pages.push(1);
    if (start > 2) pages.push('...');
    pages = pages.concat(range);
    if (end < total - 1) pages.push('...');
    if (total > 1) pages.push(total);

    return pages;
});

/* ================= HELPERS ================= */

function formatDate(date) {

    if (!date) return "";

    return new Date(date).toLocaleString();
}

</script>
