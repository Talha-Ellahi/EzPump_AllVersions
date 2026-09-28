<template>
  <div class="container mt-4">
   <div class="d-flex justify-content-end align-items-center gap-3">

     <h2 class="h5 mb-0">Upload and View Dip Chart for Tank {{ tankId }}</h2>

     <div class="w-25">
       <select v-model="selectedTankId" class="form-select" @change="handleTankChange()">
       <option value="" selected disabled hidden>Choose a tank...</option>
       <option v-for="tank in tanks" :key="tank.id" :value="tank.id">{{ tank.tank_name }}</option>
     </select>
     </div>
   </div>
 <template v-if="selectedTankId">

    <div class="card mt-3">
      <div class="card-header">
        <h2>Upload and View Dip Chart for Tank {{ tankId }}</h2>
      </div>
      <div class="card-body">
        <form @submit.prevent="uploadFile">
          <div class="mb-3">
            <label for="dipChartFile" class="form-label">Select Excel File</label>
            <input
              type="file"
              class="form-control"
              id="dipChartFile"
              accept=".xlsx,.xls"
              @change="handleFileChange"
              :disabled="uploading"
              required
            >
            <div class="form-text">
              Please upload an Excel file (.xlsx or .xls) with dip chart data
            </div>
          </div>

          <button
            type="submit"
            class="btn btn-primary"
            :disabled="uploading || !selectedFile"
          >
            <span v-if="uploading" class="spinner-border spinner-border-sm" role="status"></span>
            {{ uploading ? 'Uploading...' : 'Upload' }}
          </button>
        </form>

        <div v-if="errorMessage" class="alert alert-danger mt-3">
          {{ errorMessage }}
        </div>

        <div v-if="successMessage" class="alert alert-success mt-3">
          {{ successMessage }}
        </div>

        <table v-if="dipChartData" class="table table-hover mt-4">
          <thead>
            <tr>
              <th>Millimeter 1</th>
              <th>Liter 1</th>
              <th class="w-25 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in dipChartData" :key="item.id">
      <td v-if="editingItem !== item" @dblclick="startEditing(item)">{{ item.millimeter }}</td>
      <td v-else>
        <input type="number" class="form-control" v-model="editedItem.millimeter" />
      </td>
      <td v-if="editingItem !== item" @dblclick="startEditing(item)">{{ item.liter_value }}</td>
      <td v-else>
        <input type="number" class="form-control" v-model="editedItem.liter_value" />
      </td>

      <td v-if="editingItem !== item" lass="text-end">
         <button class="icon" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
         </button>
        <button class="icon" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </td>

      <td v-if="editingItem === item" lass="text-end">
        <button class="icon bg-primary" @click="updateItem(item)">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </button>
        <button class="icon bg-danger" @click="cancelEditing(item)">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </td>
    </tr>
          </tbody>
        </table>
      </div>
    </div>
 </template>

  </div>
</template>

<script setup>
import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

const props = defineProps({
  tankId: {
    type: Number,
    required: false
  }
});
const editingItem = ref(null)
const editedItem = ref({})

const startEditing = (item) => {
  editingItem.value = item
  editedItem.value = { ...item }
}

const cancelEditing = () => {
  editingItem.value = null
  editedItem.value = {}
}
const updateItem = async (item) => {
  try {
    const response = await axios.put(`https://example.com/api/items/${item.id}`, {
      millimeter: editedItem.value.millimeter,
      liter_value: editedItem.value.liter_value
    })

    const index = dipChartData.value.findIndex(i => i.id === item.id)
    dipChartData.value.splice(index, 1, editedItem.value)
    editingItem.value = null
    editedItem.value = {}
  } catch (error) {
    console.error(error)
  }
}

const selectedTankId = ref(null);
const tanks = ref([]);

function getTanks() {
  axios.get('/api/tanks')
  .then(response => {
    // tanks.value = response.data;
     tanks.value = response.data.tanks;
  })
  .catch(error => {
    Swal.fire('Error!', error.message, 'error');
  });
}

onMounted(async () => {
  await getTanks();
  if (props.tankId) {
    selectedTankId.value = props.tankId;
    await getDipChartData();

  }
});



// Then, add this method to your script:
function handleTankChange() {
  getDipChartData();
}

const selectedFile = ref(null);
const uploading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const dipChartData = ref(null);

function handleFileChange(event) {
  selectedFile.value = event.target.files[0];
  errorMessage.value = '';
  successMessage.value = '';
}

async function uploadFile() {
  if (!selectedFile.value) {
    errorMessage.value = 'Please select a file to upload';
    return;
  }

  const formData = new FormData();
  formData.append('file', selectedFile.value);

  uploading.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const response = await axios.post(
      `/api/tanks/${selectedTankId.value}/upload-dip-chart`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    );

    successMessage.value = 'Dip chart uploaded successfully!';
    await getDipChartData();
    // const emit = defineEmits(['upload-success']);
    // emit('upload-success');
  } catch (error) {
    console.log(error);
    errorMessage.value = error.response?.data?.message || 'Failed to upload dip chart';
  } finally {
    uploading.value = false;
  }
}

async function getDipChartData() {
  try {
    const response = await axios.get(`/api/tanks/${selectedTankId.value}/dip-chart`);
    dipChartData.value = response.data;
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Failed to retrieve dip chart data';
  }
}

onMounted(async () => {
  await getDipChartData();
});
</script>

<!-- <style scoped>
.card {
  max-width: 600px;
  margin: 0 auto;
}

.alert {
  margin-top: 20px;
}

table {
  margin-top: 20px;
}
</style> -->
