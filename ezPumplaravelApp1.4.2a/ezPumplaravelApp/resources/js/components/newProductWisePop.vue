<template>
    <div v-if="show" class="modal-container">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header" style="border-bottom:1px solid #111">
                <h5 class="modal-title">
                    Manual Tank Closing - {{ productName }}
                </h5>
                <button class="btn-close" @click="close"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <label class="form-label">
                    Tank ID: {{ tank?.id }}
                </label>

                <input
                    type="number"
                    v-model="manualDip"
                    class="form-control mb-2"
                    placeholder="Enter Dip (mm)"
                    @input="calculateTotalizer"
                />

                <div v-if="totalizer">
                    <small class="text-success">
                        Litres: {{ totalizer }}
                    </small>
                </div>

                <div v-if="error" class="alert alert-danger mt-2">
                    {{ error }}
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" @click="close">
                    Cancel
                </button>

                <button
                    class="btn btn-primary"
                    @click="submit"
                    :disabled="loading"
                >
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    {{ loading ? "Closing Shift..." : "Submit" }}
                </button>

            </div>

        </div>
    </div>
    <SuccessPopup
        v-model:show="showShiftClosedPopup"
        :title="popupType === 'error' ? 'Error' : 'Shift Closed'"
        :message="popupMessage"
        :type="popupType"
        primaryButtonText="View Shift Logs"
        @primaryAction="goToShiftLogs"
    />
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"
import Swal from "sweetalert2"
import SuccessPopup from "@/components/SuccessPopup.vue";

/* ---------------- STATES ---------------- */
const show = ref(false)
const tank = ref(null)
const productName = ref("")
const manualDip = ref("")
const totalizer = ref(null)
const error = ref(null)
const loading = ref(false)

const popupMessage = ref("")
const popupType = ref("success")
const showShiftClosedPopup = ref(false)
const goToShiftLogs = () => {

    window.location.href = window.shiftLogsUrl;

};
const emit = defineEmits(["manualClosingCompleted"])

/* ---------------- OPEN FROM PARENT ---------------- */
const openManualClosing = (tankData, product) => {
    tank.value = Array.isArray(tankData) ? tankData[0] : tankData
    productName.value = product
    manualDip.value = ""
    totalizer.value = null
    error.value = null
    show.value = true
}

/* ---------------- CLOSE ---------------- */
const close = () => {
    show.value = false
}

/* ---------------- MM → TOTALIZER ---------------- */
const calculateTotalizer = async () => {
    if (!manualDip.value || !tank.value?.id) {
        totalizer.value = null
        return
    }

    try {
        const res = await axios.post("/api/tanks/convert-mm-to-totalizer", {
            tank_id: tank.value.id,
            millimeter_value: manualDip.value
        })
        totalizer.value = Number(res.data.totalizer_value).toFixed(2)
        error.value = null
    } catch (err) {
        error.value = "Conversion failed"
        totalizer.value = null
    }
}

/* ---------------- ADD STOCK MODAL ---------------- */
const addStock = async (tankId, tankName) => {
    const swalHtml = `
    <style>.req{color:#dc2626;font-weight:700;margin-left:2px}</style>
    <div class="swal-grid">
      <div><label>Tank:</label><input type="text" value="${tankName}" disabled class="swal2-input" /></div>
      <div><label>Invoice Date:</label><input type="date" id="invoice_date" class="swal2-input" /></div>
      <div><label>Vendor: <span class="req">*</span></label><select id="vendor_id" class="form-select swal2-input"><option value="">Select Vendor</option></select></div>
      <div><label>Vehicle Number:</label><input type="text" id="vehicle_no" class="swal2-input" /></div>
      <div><label>Invoice Number:</label><input type="text" id="invoice_no" class="swal2-input" /></div>
      <div><label>Delivery / SAP No:</label><input type="text" id="delivery_or_sap_no" class="swal2-input" /></div>
      <div><label>Stock Change (Liters): <span class="req">*</span></label><input type="number" id="stock_change" class="swal2-input" /></div>
      <div><label>Driver Name:</label><input type="text" id="driver_name" class="swal2-input" /></div>
      <div><label>Driver Cell:</label><input type="text" id="driver_cell" class="swal2-input" /></div>
      <div><label>Chmb:</label><select id="chemb" class="form-select swal2-input">
        <option value="">Select</option><option value="1">Chmb1</option><option value="2">Chmb2</option>
        <option value="3">Chmb3</option><option value="4">Chmb4</option>
        <option value="5">Chmb5</option><option value="6">Chmb6</option></select></div>
      <div><label>Chemb Filling Dip:</label><input type="number" id="chemb_filling_dip" class="swal2-input" /></div>
      <div><label>Chemb Decanting Dip:</label><input type="number" id="chemb_decanting_dip" class="swal2-input" /></div>
      <div><label>Seal No:</label><input type="text" id="seal_no" class="swal2-input" /></div>
      <div><label>Filling Temp:</label><input type="text" id="filling_tmp" class="swal2-input" /></div>
      <div><label>Decanting Temp:</label><input type="text" id="decanting_tmp" class="swal2-input" /></div>
      <div><label>Purchase Price: <span class="req">*</span></label><input type="number" id="net_amount" class="swal2-input" /></div>
      <div style="grid-column: span 2;"><label>Comments:</label><textarea id="comments" class="swal2-textarea"></textarea></div>
    </div>
    `
    return Swal.fire({
        title: `Add Stock - ${tankName}`,
        html: swalHtml,
        showCancelButton: true,
        confirmButtonText: "Submit",
        width: "700px",
        didOpen: async () => {
            const grid = document.querySelector(".swal-grid")
            grid.style.display = "grid"
            grid.style.gridTemplateColumns = "1fr 1fr"
            grid.style.gap = "15px"

            try {
                const res = await axios.get("/api/tanks")
                const vendorSelect = document.getElementById("vendor_id")
                res.data.vendors.forEach(v => {
                    const opt = document.createElement("option")
                    opt.value = v.VENDOR_ID
                    opt.textContent = v.VNAME
                    vendorSelect.appendChild(opt)
                })
            } catch (e) {
                Swal.fire("Error", "Vendor list load nahi hui", "error")
            }
        },
        preConfirm: () => {
            const payload = {
                invoice_date: document.getElementById("invoice_date").value,
                vendor_id: document.getElementById("vendor_id").value,
                vehicle_no: document.getElementById("vehicle_no").value,
                invoice_no: document.getElementById("invoice_no").value,
                delivery_or_sap_no: document.getElementById("delivery_or_sap_no").value,
                stock_change: document.getElementById("stock_change").value,
                driver_name: document.getElementById("driver_name").value,
                driver_cell: document.getElementById("driver_cell").value,
                chemb: document.getElementById("chemb").value,
                chemb_filling_dip: document.getElementById("chemb_filling_dip").value,
                chemb_decanting_dip: document.getElementById("chemb_decanting_dip").value,
                seal_no: document.getElementById("seal_no").value,
                filling_tmp: document.getElementById("filling_tmp").value,
                decanting_tmp: document.getElementById("decanting_tmp").value,
                net_amount: document.getElementById("net_amount").value,
                comments: document.getElementById("comments").value,
                millimeter: 0,
            }

            if (!payload.vendor_id) { Swal.showValidationMessage("Vendor required"); return false }
            if (!payload.stock_change) { Swal.showValidationMessage("Stock change required"); return false }
            if (!payload.net_amount) { Swal.showValidationMessage("Net Amount required"); return false }

            return axios.post(`/api/tanks/${tankId}/add-stock`, payload)
                .then(() => Swal.fire("Success","Stock added successfully","success"))
                .catch(err => Swal.fire("Error", err.response?.data?.message || "Something went wrong","error"))
        }
    })
}

/* ---------------- CHECK STOCK ---------------- */
const checkStock = async () => {
    if (!tank.value?.id || !totalizer.value) return false
    try {
        await axios.post("/api/tanks/check-stock", {
            tank_id: tank.value.id,
            dip: totalizer.value,
            dip_mm: manualDip.value
        })
        return true
    } catch (err) {
        const msg = err.response?.data?.message || err.message
        const confirmAdd = await Swal.fire({
            icon: "warning",
            title: "Stock Issue",
            html: `<b>${productName.value}</b>: ${msg}`,
            showCancelButton: true,
            confirmButtonText: "Yes, Add Stock",
            cancelButtonText: "Cancel"
        })

        if (confirmAdd.isConfirmed) {
            await addStock(tank.value.id, productName.value)
            try {
                await axios.post("/api/tanks/check-stock", {
                    tank_id: tank.value.id,
                    dip: totalizer.value,
                    dip_mm: manualDip.value
                })
                return true
            } catch {
                // alert(`${productName.value} still has stock issues.`)
                return false
            }
        } else {
            return false
        }
    }
}

/* ---------------- SUBMIT ---------------- */
const submit = async () => {
    if (!manualDip.value || !totalizer.value) {
        error.value = "Please enter valid dip"
        return
    }

    loading.value = true
    error.value = null

    // ✅ Check stock before updating
    const stockOk = await checkStock()
    if (!stockOk) {
        loading.value = false
        return
    }

    try {
        // UPDATE ATG
        await axios.post("/api/tanks/tank-shifts/update_atg", {
            id: tank.value.id,
            manual_closing_mm: Math.round(manualDip.value * 100),
            manual_closing_totalizer: Math.round(totalizer.value * 100)
        })

        // CLOSE MANUAL SHIFT
        await axios.post("/api/tanks/tank-shifts/close-manual", {
            tank_ids: [tank.value.id],
            manual_mm: { [tank.value.id]: Math.round(manualDip.value * 100) },
            manual_totalizers: { [tank.value.id]: Math.round(totalizer.value * 100) }
        })

        // CLOSE TANK SHIFTS
        await axios.post("/api/shifts/close-by-tank", {
            tank_id: tank.value.id
        });

        popupMessage.value = "All tank & pump shifts closed successfully."
        popupType.value = "success"
        showShiftClosedPopup.value = true
        // emit("manualClosingCompleted")
        close()

    } catch (e) {
        error.value = "Manual close failed"
    }

    loading.value = false
}

/* ---------------- EXPOSE ---------------- */
defineExpose({ openManualClosing })
</script>


<style scoped>
.modal-container {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-content {
    background: #fff;
    width: 420px;
    border-radius: 8px;
}

.modal-header,
.modal-body,
.modal-footer {
    padding: 1rem;
}

.modal-footer {
    display: flex;
    justify-content: end;
    gap: .5rem;
}
</style>
