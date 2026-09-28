<template>
    <div class="container mt-4" ref="rootRef">

        <div class="card">
            <div class="card-header">
                <h2>{{ isEditMode ? 'Edit Tank' : 'Add New Tank' }}</h2>
            </div>

            <div class="card-body">

                <form @submit.prevent="submitForm">

                    <div class="row">

                        <!-- Tank Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tank Name</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.tank_name"
                                   required>
                        </div>

                        <!-- Fuel Type -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fuel Type</label>

                            <select class="form-select"
                                    v-model="form.product_id"
                                    required>

                                <option v-for="product in products"
                                        :key="product.ICODE"
                                        :value="product.ICODE">

                                    {{ product.ITMNAME }}

                                </option>

                            </select>

                            <input type="hidden" v-model="form.fuel_type" />

                        </div>

                    </div>

                    <!-- ================= NOZZLE SECTION ================= -->
                    <!-- ✅ HIDE IF SYS_MODE == 3 -->

                    <div class="row" v-if="sysMode !== 3">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nozzles</label>

                            <select class="form-select"
                                    v-model="form.nozzle_ids"
                                    multiple>

                                <template v-for="pump in pumps" :key="pump.id">

                                    <option v-if="pump.ICODE == form.product_id"
                                            :value="pump.id">

                                        {{ pump.FC_NZNo }}

                                    </option>

                                </template>

                            </select>

                        </div>

                    </div>

                    <!-- ================================================= -->

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity (Liters)</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.capacity_liters"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Temperature (°C)</label>
                            <input type="number"
                                   step="0.1"
                                   class="form-control"
                                   v-model="form.temperature">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Water Height (mm)</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.water_height_mm">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Active Tank</label>

                            <select class="form-control"
                                    v-model="form.is_active">

                                <option :value="1">On</option>
                                <option :value="0">Off</option>

                            </select>

                        </div>

                    </div>
                    <!-- ================= ALARM FIELDS (ONLY SYS MODE 3) ================= -->
                    <div v-if="sysMode == 3" class="row mt-3">

                        <div class="col-md-3">
                            <label>Low Oil Alarm</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.low_level_alarm_mm">
                        </div>

                        <div class="col-md-3">
                            <label>Low Low Oil Alarm</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.low_low_level_alarm_mm">
                        </div>

                        <div class="col-md-3">
                            <label>High Oil Alarm</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.high_level_alarm_mm">
                        </div>

                        <div class="col-md-3">
                            <label>High High Oil Alarm</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.high_high_level_alarm_mm">
                        </div>

                    </div>
                    <!-- Submit -->

                    <div class="col-md-12 mb-3">
                        <button type="submit"
                                class="btn btn-primary mt-3">

                            {{ isEditMode ? 'Update Tank' : 'Add Tank' }}

                        </button>

                        <button type="button"
                                class="btn btn-secondary ms-2 mt-3"
                                @click="resetForm">

                            Reset

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</template>

<script setup>

import axios from 'axios'
import { ref, onMounted, computed, watch } from 'vue'
import Swal from 'sweetalert2'

/* ================= PROPS ================= */

const props = defineProps({
    tankId: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['tank-saved'])

/* ================= REFS ================= */

const isEditMode = ref(false)
const rootRef = ref(null)

const products = ref([])
const pumps = ref([])
const sysMode = ref(null)

const form = ref({
    tank_name: '',
    fuel_type: '',
    capacity_liters: 0,
    temperature: 0,
    water_height_mm: 0,
    product_id: '',
    nozzle_ids: [],
    is_active: 0,
    // ✅ NEW FIELDS
    low_level_alarm_mm: null,
    low_low_level_alarm_mm: null,
    high_level_alarm_mm: null,
    high_high_level_alarm_mm: null,

})

/* ================= COMPUTED ================= */

const tankId = computed(() =>
    rootRef.value?.attributes?.tankid?.value
)

/* ================= SYSTEM MODE API ================= */

const fetchSystem = async () => {
    try {
        const res = await axios.get('/api/atg/system')
        sysMode.value = res.data.Sys_Mode
    } catch (error) {
        console.log('System API Error', error)
    }
}

/* ================= FETCH DATA ================= */

const fetchProducts = async () => {
    try {
        const res = await axios.get('/api/products')
        products.value = res.data
    } catch (error) {
        Swal.fire('Error', error.message, 'error')
    }
}

const fetchPumps = async () => {
    try {
        const res = await axios.get('/api/pumps')
        pumps.value = res.data
    } catch (error) {
        Swal.fire('Error', error.message, 'error')
    }
}

const fetchTank = async () => {
    try {
        const res = await axios.get(`/api/tanks/${tankId.value}`)
        form.value = res.data
        form.value.nozzle_ids =
            form.value.nozzle_ids?.split(',') || []
    } catch (error) {
        Swal.fire('Error', error.message, 'error')
    }
}

/* ================= SUBMIT ================= */

const submitForm = async () => {

    const url = isEditMode.value
        ? `/api/tanks/${tankId.value}`
        : '/api/tanks'

    const method = isEditMode.value ? 'put' : 'post'

    form.value.fuel_type =
        products.value.find(
            p => p.ICODE === form.value.product_id
        )?.ITMNAME

    try {

        // Convert array to string
        if (Array.isArray(form.value.nozzle_ids)) {
            form.value.nozzle_ids = form.value.nozzle_ids.join(',')
        }

        await axios[method](url, form.value)

        Swal.fire(
            'Success',
            `Tank ${isEditMode.value ? 'Updated' : 'Added'} Successfully`,
            'success'
        )

        emit('tank-saved')

    } catch (error) {
        Swal.fire('Error', error.message, 'error')
    }
}

/* ================= RESET ================= */

const resetForm = () => {

    form.value = {
        tank_name: '',
        fuel_type: 'Petrol',
        capacity_liters: 0,
        temperature: 0,
        water_height_mm: 0,
        product_id: null,
        nozzle_ids: [],
        is_active: 0
    }

}

/* ================= AUTO HIDE NOZZLE IF SYS_MODE = 3 ================= */

watch(sysMode, (newVal) => {

    if (newVal === 3) {
        form.value.nozzle_ids = []
    }

})

/* ================= MOUNTED ================= */

onMounted(() => {

    fetchProducts()
    fetchPumps()
    fetchSystem()   // ✅ Added System Mode

    if (rootRef.value?.attributes?.tankid?.value) {
        isEditMode.value = true
        fetchTank()
    }

})

</script>

<style scoped>
.form-control {
    max-width: 400px;
}
</style>
