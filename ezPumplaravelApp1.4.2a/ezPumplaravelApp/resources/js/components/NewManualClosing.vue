<template>
    <div v-if="show" class="modal-container">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header  text-dark-emphasis" style=" border-bottom: 1px solid #111">
                <h5 class="modal-title">Manual Tank Closing</h5>
                <button type="button" class="btn-close" @click="close" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <p class="mb-3">Please enter manual dips for the following tanks:</p>

                <div v-if="tanks.length > 0">
                    <div v-for="tank in tanks" :key="tank.TankID" class="mb-3">
                        <label class="form-label">
                            Tank {{ tank.TankID }}
                        </label>
                        <input
                            type="number"
                            v-model="tank.manualDip"
                            class="form-control mb-2"
                            placeholder="Enter Dip (mm)"
                            @input="calculateTotalizer(tank)"
                        />

                        <div v-if="calculatedTotalizers[tank.TankID]">
                            <small class="text-success">
                                Litres: {{ calculatedTotalizers[tank.TankID] }}
                            </small>
                        </div>
                        <div v-if="tank.errorMessage">
                            <small class="text-danger">{{ tank.errorMessage }}</small>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>No tanks available for manual closing.</p>
                </div>

                <div v-if="error" class="alert alert-danger mt-3">
                    {{ error }}
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close" :disabled="isSubmitting">Cancel</button>
                <button type="button" class="btn btn-primary" @click="submit" :disabled="isSubmitting">
                    {{ isSubmitting ? "Submitting..." : "Submit" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue"
import axios from "axios"
import Swal from "sweetalert2"

/* -------------------------------
   Reactive State
--------------------------------*/
const show = ref(false)
const tanks = ref([]) // all tanks (ATG + manual)
const isSubmitting = ref(false)
const error = ref(null)
const calculatedTotalizers = ref({})

const emit = defineEmits(["manualClosingCompleted"])
const goToShiftLogs = () => {

    window.location.href = window.shiftLogsUrl;

};
/* -------------------------------
   Open & Close Modal
--------------------------------*/
const openManualClosing = (tankList) => {
    tanks.value = tankList.map(t => ({
        ...t,
        manualDip: "",
        errorMessage: null
    }))
    show.value = true
}

const close = () => {
    show.value = false
    tanks.value = []
    error.value = null
    calculatedTotalizers.value = {}
}

/* -------------------------------
   Convert MM -> Totalizer
--------------------------------*/
const calculateTotalizer = async (tank) => {
    if (!tank.manualDip || isNaN(parseFloat(tank.manualDip))) {
        calculatedTotalizers.value[tank.TankID] = null
        return
    }
    try {
        const res = await axios.post("/api/tanks/convert-mm-to-totalizer", {
            tank_id: tank.TankID,
            millimeter_value: parseFloat(tank.manualDip)
        })
        calculatedTotalizers.value[tank.TankID] = parseFloat(res.data.totalizer_value).toFixed(2)
        tank.errorMessage = null
    } catch {
        calculatedTotalizers.value[tank.TankID] = null
        tank.errorMessage = "Conversion failed"
    }
}

/* -------------------------------
   Add Stock Modal
--------------------------------*/
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

/* -------------------------------
   Check stock & add if needed
--------------------------------*/
const checkStock = async (tankId, tankName, totalizerValue, closingMM = null) => {
    try {
        await axios.post("/api/tanks/check-stock", {
            tank_id: tankId,
            dip: totalizerValue,
            dip_mm: closingMM
        })
        return true
    } catch (err) {
        const msg = err.response?.data?.message || err.message
        const confirmAdd = await Swal.fire({
            icon: "warning",
            title: "Stock Issue",
            html: `<b>${tankName}</b>: ${msg}. `,
            showCancelButton: true,
            confirmButtonText: "Yes, Add Stock",
            cancelButtonText: "Cancel"
        })

        if (confirmAdd.isConfirmed) {
            await addStock(tankId, tankName)
            try {
                await axios.post("/api/tanks/check-stock", {
                    tank_id: tankId,
                    dip: totalizerValue,
                    dip_mm: closingMM
                })
                return true
            } catch {
                alert(`Tank ${tankName} still has stock issues.`)
                return false
            }
        } else {
            return false
        }
    }
}

/* -------------------------------
   Submit Manual + ATG Tanks
--------------------------------*/
const submit = async () => {
    isSubmitting.value = true
    error.value = null
    let allSuccessful = true
    let firstError = null

    for (const tank of tanks.value) {
        const dip = parseFloat(tank.manualDip)
        const totalizer = parseFloat(calculatedTotalizers.value[tank.TankID])
        const tankLabel = tank?.tank_name || `Tank ${tank.TankID}`

        if (isNaN(dip) || isNaN(totalizer)) {
            tank.errorMessage = "Invalid dip or totalizer"
            allSuccessful = false
            continue
        }

        // ✅ Check stock using unified function
        const stockOk = await checkStock(tank.TankID, tankLabel, totalizer, dip)
        if (!stockOk) {
            tank.errorMessage = `Tank ${tankLabel} has stock issue.`
            allSuccessful = false
            continue
        }

        try {
            await axios.post("/api/tanks/tank-shifts/update_atg", {
                id: tank.TankID,
                manual_closing_mm: Math.round(dip * 100),
                manual_closing_totalizer: Math.round(totalizer * 100)
            })
            tank.errorMessage = null
        } catch (err) {
            tank.errorMessage = err.response?.data?.message || "Update failed"
            allSuccessful = false
            if (!firstError) firstError = `Failed to update shift for ${tankLabel}: ${tank.errorMessage}`
        }
    }

    if (allSuccessful) {
        // ✅ Get all manual tank IDs (bMode = 0)
        const manualTanks = tanks.value.filter(t => Number(t.is_atg) === 0);

        if (manualTanks.length > 0) {
            // Collect IDs and dip/totalizer values
            const tankIds = manualTanks.map(t => t.TankID);
            const manual_mm = {};
            const manual_totalizers = {};

            for (const t of manualTanks) {
                const dip = parseFloat(t.manualDip);
                const totalizer = parseFloat(calculatedTotalizers.value[t.TankID]);
                manual_mm[t.TankID] = Math.round(dip * 100);
                manual_totalizers[t.TankID] = Math.round(totalizer * 100);
            }

            try {
                // ✅ Send all manual tanks in one go
                await axios.post("/api/tanks/tank-shifts/close-manual", {
                    tank_ids: tankIds,
                    manual_mm,
                    manual_totalizers,
                });

                console.log("✅ All manual tanks closed successfully:", tankIds);
                close();
                emit("manualClosingCompleted");
            } catch (err) {
                console.error("❌ Manual close failed:", err);
                error.value = err.response?.data?.message || "Manual close failed";
            }
        } else {
            console.log("⚠️ No manual tanks found (is_atg=0). Skipping manual close.");
        }
    }

    isSubmitting.value = false
}

defineExpose({ openManualClosing })
</script>




<style scoped>
.modal-container {
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
.modal-content {
    background-color: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.modal-header {
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 1.5rem;
}
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}
</style>
