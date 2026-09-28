<template>
  <div class="container" style="position: relative;">
    <h2>Tank Shift Management</h2>
    <div v-if="isLoading" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <button type="button" class="btn btn-warning" @click="closeAllShifts" :disabled="isCloseAllShiftsButtonDisabled">Close All Shifts</button>
    <div class="row">
      <div class="col-md-4">
        <h3>Tanks</h3>
        <ul class="list-group">
          <li
            v-for="tank in tanks"
            :key="tank.id"
            class="list-group-item"
            :class="{ active: selectedTank?.id === tank.id }"
            @click="selectTank(tank)"
          >
            {{ tank.tank_name }}
          </li>
        </ul>
      </div>
      <div class="col-md-8">
        <h3>Shift Details</h3>
        <div v-if="selectedTank">
          <p>Selected Tank: {{ selectedTank.tank_name }}</p>
          <form v-if="shiftDetails">
            <div class="mb-3">
              <label for="openingTotalizer" class="form-label">Opening Dip (mm)</label>
<!--              <input type="number" class="form-control" id="openingTotalizer" v-model="shiftDetails.opening_totalizer">-->
              <input type="number" class="form-control" id="openingTotalizer" v-model="shiftDetails.opening_mm">
            </div>
            <div class="mb-3">
              <label for="closingTotalizer" class="form-label">Closing Dip (mm)</label>
              <input type="number" class="form-control" id="closingTotalizer" v-model="shiftDetails.closing_mm">
            </div>
<!--             <div class="mb-3">-->
<!--              <label for="manualOpeningTotalizer" class="form-label">Manual Opening Dip (mm)</label>-->
<!--              <input type="number" class="form-control" id="manualOpeningTotalizer" v-model="shiftDetails.manual_opening_totalizer">-->
<!--            </div>-->
<!--             <div class="mb-3">-->
<!--              <label for="manualClosingTotalizer" class="form-label">Manual Closing Dip (mm)</label>-->
<!--              <input type="number" class="form-control" id="manualClosingTotalizer" v-model="shiftDetails.manual_closing_totalizer">-->
<!--            </div>-->
            <div class="mb-3">
              <label for="startTime" class="form-label">Start Time</label>
              <input type="datetime-local" class="form-control" id="startTime" v-model="shiftDetails.start_time">
            </div>
            <div class="mb-3">
              <label for="endTime" class="form-label">End Time</label>
              <input type="datetime-local" class="form-control" id="endTime" v-model="shiftDetails.end_time">
            </div>
            <button type="button" class="btn btn-primary" @click="saveShift">Save Shift</button>
            <button type="button" class="btn btn-danger" @click="closeShift">Close Shift</button>
          </form>

           <h2>Tank Status History</h2>
          <div v-if="tankStatusHistory.length > 0">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Level</th>
                  <th>Quantity</th>
                  <th>Temperature</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="status in tankStatusHistory" :key="status.id">
                  <td>{{ status.tdate }}</td>
                  <td>{{ status.Level }}</td>
                  <td>{{ status.Qty }}</td>
                  <td>{{ status.TEMP }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p>No tank status history available.</p>
          </div>
        </div>
        <div v-else>
          <p>Select a tank to view shift details.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import necessary modules
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Define data variables
const isLoading = ref(false);
const isCloseAllShiftsButtonDisabled = ref(false);
const tanks = ref([]);
const selectedTank = ref(null);
const shiftDetails = ref({
  start_time: null,
  end_time: null,
});
const tankStatusHistory = ref([]);

// Fetch tanks on component mount
onMounted(async () => {
  await fetchTanks();
});


const fetchTanks = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/tanks');
    tanks.value = response.data.tanks;
  } catch (error) {
    console.error('Error fetching tanks:', error);
    // TODO: Display error message to the user
  } finally {
    isLoading.value = false;
  }
};

// Function to handle tank selection
const selectTank = async (tank) => {
  selectedTank.value = tank;
  isLoading.value = true;
  try {
    await fetchShiftDetails(tank.id);
    await fetchTankStatusHistory(tank.id);
  } finally {
    isLoading.value = false;
  }
};

const fetchShiftDetails = async (tankId) => {
  isLoading.value = true;
  try {
    const response = await axios.get(`/api/tanks/tank-shifts/check/${tankId}`);
    if (response.data.shift) {
      shiftDetails.value = response.data.shift;
    } else {
      shiftDetails.value = {
        start_time: null,
        end_time: null,
      };
    }
  } catch (error) {
    console.error('Error fetching shift details:', error);
    // TODO: Display error message to the user
  } finally {
    isLoading.value = false;
  }
};

const fetchTankStatusHistory = async (tankId) => {
  isLoading.value = true;
  try {
    const response = await axios.get(`/api/tanks/fuel-status?tank_id=${tankId}`);
    tankStatusHistory.value = response.data;
  } catch (error) {
    console.error('Error fetching tank status history:', error);
    alert('Error fetching tank status history. Please check the console for details.');
  } finally {
    isLoading.value = false;
  }
};

// Function to save shift details
const saveShift = async () => {
  isLoading.value = true;
  try {
    const response = await axios.post('/api/tanks/tank-shifts/update', {
      id: shiftDetails.value.id,
      // opening_totalizer: shiftDetails.value.opening_totalizer,
      // closing_totalizer: shiftDetails.value.closing_totalizer,
      // manual_opening_totalizer: shiftDetails.value.manual_opening_totalizer,
      // manual_closing_totalizer: shiftDetails.value.manual_closing_totalizer,
        opening_mm: shiftDetails.value.opening_mm,
        closing_mm: shiftDetails.value.closing_mm,
        // manual_opening_totalizer: shiftDetails.value.manual_opening_totalizer,
        // manual_closing_totalizer: shiftDetails.value.manual_closing_totalizer,
      start_time: shiftDetails.value.start_time,
      end_time: shiftDetails.value.end_time,
    });
    console.log('Shift details updated successfully:', response.data);
    // TODO: Display success message to the user
  } catch (error) {
    console.error('Error updating shift details:', error);
    // TODO: Display error message to the user
  } finally {
    isLoading.value = false;
  }
};

// Function to close shift
const closeShift = async () => {
  isLoading.value = true;
  try {
    const response = await axios.post(`/api/tanks/tank-shifts/close/${shiftDetails.value.id}`, {
      closing_totalizer: shiftDetails.value.closing_totalizer,
    });
    console.log('Shift closed successfully:', response.data);
    // TODO: Display success message to the user
    // Refresh shift details
    await fetchShiftDetails(selectedTank.value.id);
  } catch (error) {
    console.error('Error closing shift:', error);
    // TODO: Display error message to the user
  } finally {
    isLoading.value = false;
  }
};

// Function to close all shifts
const closeAllShifts = async () => {
  isCloseAllShiftsButtonDisabled.value = true;
  isLoading.value = true;
  try {
    const response = await axios.post('/api/tanks/tank-shifts/close-all');
    console.log('All shifts closed successfully:', response.data);
    // TODO: Display success message to the user
    // Refresh shift details for selected tank if any
    if (selectedTank.value) {
      await fetchShiftDetails(selectedTank.value.id);
    }
  } catch (error) {
    console.error('Error closing all shifts:', error);
    // TODO: Display error message to the user
  } finally {
    isLoading.value = false;
    isCloseAllShiftsButtonDisabled.value = false;
  }
};
</script>

<style scoped>
/* Add component styles here */
</style>
