<template>
  <div v-if="show" class="modal-container tank-closing-modal">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">Enter Closing Totalizers for Tank Shifts</h5>
        <button type="button" class="btn-close" @click="cancel" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>Tank</th>
              <th>Opening dip(mm)</th>
              <th>Closing dip(mm)</th>
              <th>Closing Stock</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="shift in shifts" :key="shift.id" :class="{ 'table-success': shift.closedSuccess, 'table-danger': shift.closedError }">
              <td>{{ shift.tank.tank_name || `Tank ID: ${shift.tank_id}` }}</td>
              <td>{{ (shift.opening_mm / 100).toFixed(2) }}</td>
              <td>
                <input
                  type="number"
                  v-model="mmValues[shift.id]"
                  @input="calculateTotalizer(shift.id, shift.tank_id, mmValues[shift.id])"
                  class="form-control closing-input"
                  step="0.01"
                  required
                  placeholder="Enter MM"
                  :disabled="shift.closedSuccess"
                />
                <div v-if="shift.closedError" class="text-danger small mt-1">{{ shift.errorMessage }}</div>
              </td>
              <td>
                <input
                  type="number"
                  v-model="calculatedTotalizers[shift.id]"
                  class="form-control"
                  step="0.01"
                  disabled
                  placeholder="Closing Stock"
                />
              </td>
            </tr>
            <tr v-if="shifts.length === 0">
              <td colspan="4" class="text-center">No active tank shifts found.</td>
            </tr>
          </tbody>
        </table>
        <div v-if="error" class="alert alert-danger mt-3">{{ error }}</div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="cancel">Cancel</button>
        <button
          type="button"
          class="btn btn-primary"
          @click="submit"
          :disabled="isSubmitting || shifts.length === 0"
        >
          <span v-if="isSubmitting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          {{ isSubmitting ? 'Processing...' : 'Submit & Close All' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

/* -------------------------------
   Props & Emits
--------------------------------*/
const props = defineProps({
    show: { type: Boolean, required: true },
    shifts: { type: Array, default: () => [] } // each shift should have tank { tank_name } & tank_id
})

const emit = defineEmits(['submit', 'cancel', 'update:show'])


/* -------------------------------
   Reactive State
--------------------------------*/
const mmValues = ref({})
const calculatedTotalizers = ref({})
const isSubmitting = ref(false)
const error = ref(null)


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
   Convert MM → Totalizer
--------------------------------*/
const calculateTotalizer = async (shiftId, tankId, millimeterValue) => {
    if (!millimeterValue || isNaN(parseFloat(millimeterValue))) {
        calculatedTotalizers.value[shiftId] = null
        return
    }
    try {
        const res = await axios.post('/api/tanks/convert-mm-to-totalizer', {
            tank_id: tankId,
            millimeter_value: parseFloat(millimeterValue)
        })
        calculatedTotalizers.value[shiftId] = parseFloat(res.data.totalizer_value).toFixed(2)
    } catch (err) {
        console.error(`Error converting MM to totalizer for tank ${tankId}:`, err)
        calculatedTotalizers.value[shiftId] = null
        const shift = props.shifts.find(s => s.id === shiftId)
        if (shift) { shift.closedError=true; shift.errorMessage='Conversion failed.' }
    }
}


/* -------------------------------
   Check Stock & Ask to Add if Needed
--------------------------------*/
const checkStock = async (tankId, tankName, closingLiters, closingMM) => {
    try {
        await axios.post('/api/tanks/check-stock', {
            tank_id: tankId,
            dip: closingLiters,
            dip_mm: closingMM
        })
        return true
    } catch (err) {
        const msg = err.response?.data?.message || err.message
        const confirmAdd = await Swal.fire({
            icon: "warning",
            title: "Stock Issue",
            html: `<b>${tankName}</b>: ${msg}`,
            showCancelButton: true,
            confirmButtonText: "Yes, Add Stock",
            cancelButtonText: "Cancel"
        })

        if (confirmAdd.isConfirmed) {
            await addStock(tankId, tankName)
            try {
                await axios.post('/api/tanks/check-stock', {
                    tank_id: tankId,
                    dip: closingLiters,
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
   Submit Shifts
--------------------------------*/
const submit = async () => {
    isSubmitting.value = true
    error.value = null
    let allSuccessful = true
    let firstError = null

    props.shifts.forEach(shift => { shift.closedSuccess=false; shift.closedError=false; shift.errorMessage=null })

    for (const shift of props.shifts) {
        const tankLabel = shift.tank?.tank_name || `Tank ${shift.tank_id}`
        const closingMMValue = mmValues.value[shift.id]
        const calculatedTotalizerValue = parseFloat(calculatedTotalizers.value[shift.id])

        if (!closingMMValue || isNaN(parseFloat(closingMMValue)) || parseFloat(closingMMValue)<0) {
            shift.closedError = true
            shift.errorMessage = 'Invalid or missing MM value.'
            if(!firstError) firstError=`Invalid closing MM for ${tankLabel}`
            allSuccessful = false
            continue
        }

        if (isNaN(calculatedTotalizerValue) || calculatedTotalizerValue<0) {
            shift.closedError = true
            shift.errorMessage = 'Calculated totalizer is invalid.'
            if(!firstError) firstError=`Calculated totalizer is invalid for ${tankLabel}`
            allSuccessful = false
            continue
        }

        const stockOk = await checkStock(
            shift.tank_id,
            tankLabel,
            calculatedTotalizerValue,
            parseFloat(closingMMValue)
        )
        if (!stockOk) {
            shift.closedError = true;
            allSuccessful=false;
            if(!firstError) firstError=`Tank ${tankLabel} has stock issue.`;
            continue
        }

        try {
            await axios.post('/api/tanks/tank-shifts/update', {
                id: shift.id,
                manual_closing_mm: Math.round(parseFloat(closingMMValue)*100),
                manual_closing_totalizer: Math.round(calculatedTotalizerValue*100),
                dip_mm: parseFloat(closingMMValue)
            })
            shift.closedSuccess = true
        } catch (err) {
            console.error(`Error updating tank shift ${shift.id}:`, err)
            shift.closedError = true
            shift.errorMessage = err.response?.data?.message || err.message || 'Update API Error'
            if(!firstError) firstError = `Failed to update shift for ${tankLabel}: ${shift.errorMessage}`
            allSuccessful = false
            break
        }
    }

    if(allSuccessful){
        try {
            await axios.post('/api/tanks/tank-shifts/close-all')
            emit('submit')
            close()
        } catch(err) {
            const apiError = err.response?.data?.error || err.message
            error.value = `Failed to call close-all: ${apiError}`
            console.error('Error calling close-all:', apiError)
        }
    } else {
        error.value = firstError || 'Some tank shifts failed. Please review the errors.'
    }

    isSubmitting.value = false
}


/* -------------------------------
   Cancel & Close
--------------------------------*/
const cancel = () => { if(confirm('Are you sure you want to cancel? Any entered data will be lost.')) close() }
const close = () => { emit('update:show', false); emit('cancel') }

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
  padding: 0;
  border-radius: 8px;
  width: 90%;
  max-width: 900px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.modal-header {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-body {
  padding: 1.5rem;
  overflow-y: auto;
  flex-grow: 1;
}

.modal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.table {
  margin-bottom: 0;
}

.closing-input {
  min-width: 120px;
}

.table-success {
  background-color: #d1e7dd !important;
}

.table-danger {
  background-color: #f8d7da !important;
}
</style>
