<template>
    <div class="sales-dashboard">
        <!-- Header Section -->


        <!-- Filter Section -->
        <div class="filter-card border-top border-0 border-4 border-danger">
            <h2 class="filter-title">Filter Records</h2>
            <div class="filter-container">
                <div class="filter-group">
                    <label class="filter-label">Start Date</label>
                    <input
                        type="date"
                        v-model="startDate"
                        class="filter-input"
                    />
                </div>
                <div class="filter-group">
                    <label class="filter-label">End Date</label>
                    <input
                        type="date"
                        v-model="endDate"
                        class="filter-input"
                    />
                </div>
                <div class="filter-actions">
                    <button
                        @click="fetchSalesData"
                        class="btn-primary"
                    >
                        <span class="btn-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Apply Filter
                    </button>
                    <button
                        @click="clearFilters"
                        class="btn-secondary"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="summary-card">
            <div class="summary-content">
                <div>
                    <h2 class="summary-title">Total Records</h2>
                    <p class="summary-count">{{ salesData.length }}</p>
                </div>
                <div class="summary-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-card">
            <div class="table-container">
                <table class="data-table">
                    <thead class="table-header">
                    <tr>
                        <th class="table-head">ID</th>
                        <th class="table-head">PDate</th>
                        <th class="table-head">TDate</th>
                        <th class="table-head">ForeCourt Nozzle</th>
                        <th class="table-head">POS ID</th>
                        <th class="table-head">Item Code</th>
                        <th class="table-head">Qty</th>
                        <th class="table-head">Rate</th>
                        <th class="table-head">Amount</th>
                    </tr>
                    </thead>
                    <tbody class="table-body">
                    <tr
                        v-for="row in salesData"
                        :key="row.id"
                        class="table-row"
                    >
                        <td class="table-cell table-cell-id">{{ row.id }}</td>
                        <td class="table-cell">{{ formatDate(row.pdate) }}</td>
                        <td class="table-cell">{{ formatDate(row.tdate) }}</td>
                        <td class="table-cell">{{ row.FC_NZNo }}</td>
                        <td class="table-cell">{{ row.pos_id }}</td>
                        <td class="table-cell">{{ row.icode }}</td>
                        <td class="table-cell">{{ row.qty }}</td>
                        <td class="table-cell">{{ row.rate }}</td>
                        <td class="table-cell table-cell-amount">{{ row.amt }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-if="salesData.length === 0" class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="empty-title">No records found</h3>
                <p class="empty-message">Try adjusting your filters or check back later.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const salesData = ref([]);
const startDate = ref("");
const endDate = ref("");

// Fetch data with optional date filter
const fetchSalesData = async () => {
    try {
        let url = "/api/salesend_master";

        // Agar date filter apply ho
        if (startDate.value && endDate.value) {
            url += `?start_date=${startDate.value}&end_date=${endDate.value}`;
        }

        const response = await axios.get(url);
        salesData.value = response.data;
    } catch (error) {
        console.error("Error fetching sales data:", error);
    }
};

// Clear filters
const clearFilters = () => {
    startDate.value = "";
    endDate.value = "";
    fetchSalesData();
};

// Format date for display
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

// Format currency for display


// First time load sab data
onMounted(() => {
    fetchSalesData();
});
</script>

<style scoped>
.sales-dashboard {
    padding: 1.5rem;
    background-color: #f9fafb;
    min-height: 100vh;
}

/* Header Styles */
.header {
    margin-bottom: 2rem;
}

.title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #6b7280;
}

/* Filter Card Styles */
.filter-card {
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

.filter-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 768px) {
    .filter-container {
        flex-direction: row;
        align-items: flex-end;
    }
}

.filter-group {
    flex: 1;
}

.filter-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.filter-input {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.625rem 1rem;
    transition: all 0.2s;
}

.filter-input:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    border-color: #3b82f6;
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
}

/* Button Styles */
.btn-primary {
    background-color: #2563eb;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    border: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.2s;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #1d4ed8;
}

.btn-primary:focus {
    outline: none;
    ring: 2px;
    ring-color: #3b82f6;
    ring-offset: 2px;
}

.btn-secondary {
    background-color: #e5e7eb;
    color: #374151;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    border: none;
    font-weight: 500;
    transition: background-color 0.2s;
    cursor: pointer;
}

.btn-secondary:hover {
    background-color: #d1d5db;
}

.btn-secondary:focus {
    outline: none;
    ring: 2px;
    ring-color: #9ca3af;
    ring-offset: 2px;
}

.btn-icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* Summary Card Styles */
.summary-card {
    background: linear-gradient(to right, #3b82f6, #6366f1);
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    color: white;
}

.summary-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.summary-count {
    font-size: 1.875rem;
    font-weight: 700;
    margin-top: 0.25rem;
}

.summary-icon {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 0.75rem;
    border-radius: 9999px;
}

.summary-icon svg {
    width: 2rem;
    height: 2rem;
}

/* Table Styles */
.table-card {
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.table-header {
    background-color: #f9fafb;
}

.table-head {
    padding: 0.75rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
}

.table-body {
    background-color: white;
}

.table-row {
    transition: background-color 0.2s;
}

.table-row:hover {
    background-color: #f9fafb;
}

.table-cell {
    padding: 1rem 1.5rem;
    font-size: 0.875rem;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
}

.table-cell-id {
    font-weight: 500;
    color: #1f2937;
}

.table-cell-amount {
    font-weight: 500;
    color: #2563eb;
}

/* Empty State Styles */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    color: #9ca3af;
    margin: 0 auto 1rem;
}

.empty-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.empty-message {
    color: #6b7280;
}
</style>
