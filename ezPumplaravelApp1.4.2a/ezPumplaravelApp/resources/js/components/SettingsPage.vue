<template>
    <div class="container py-4 align-items-center">

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item" v-for="tab in tabs" :key="tab.key">
                <a
                    class="nav-link"
                    :class="{ active: activeTab === tab.key }"
                    href="#"
                    @click.prevent="activeTab = tab.key"
                >
                    {{ tab.label }}
                </a>
            </li>
        </ul>

        <!-- Basic Settings -->
        <form v-if="activeTab === 'basic'" @submit.prevent="updateSettings">
            <div class="row">
                <div
                    v-for="(setting, index) in settings"
                    :key="index"
                    class="col-md-6 col-lg-4 mb-4"
                >
                    <div class="card shadow-sm p-3 rounded-3">
                        <label :for="'key-' + index" class="form-label fw-bold">
                            {{ setting.key }}
                        </label>

                        <!-- Text & Number Input -->
                        <input
                            v-if="setting.type === 'text' || setting.type === 'number'"
                            v-model="setting.value"
                            :type="setting.type"
                            :id="'key-' + index"
                            class="form-control mb-2"
                        />

                        <!-- Boolean Toggle -->
                        <div v-if="setting.type === 'bool'" class="form-check form-switch">
                            <input
                                v-model="setting.value"
                                type="checkbox"
                                :id="'key-' + index"
                                class="form-check-input"
                                :true-value="1"
                                :false-value="0"
                            />
                            <label :for="'key-' + index" class="form-check-label">
                                {{ setting.description }}
                            </label>
                        </div>

                        <!-- File Input -->
                        <div v-if="setting.type === 'file'">
                            <input
                                @change="handleFileChange($event, index)"
                                type="file"
                                :id="'key-' + index"
                                class="form-control mb-2"
                            />
                            <img
                                v-if="setting.value"
                                :src="'/storage/' + setting.value"
                                width="80"
                                class="rounded shadow-sm"
                                alt="settings"
                            />
                        </div>

                        <small class="text-muted ">{{ setting.description }}</small>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">💾 Update Settings</button>
        </form>

        <!-- Email Settings -->
        <form v-if="activeTab === 'email'" @submit.prevent="updateAll('email')" class="card shadow-sm p-4">
            <div class="form-check form-switch mb-3">
                <input
                    v-model="email.enable"
                    type="checkbox"
                    class="form-check-input"
                    :true-value="1"
                    :false-value="0"
                    id="emailSwitch"
                />
                <label for="emailSwitch" class="form-check-label">Enable Email Notifications</label>
            </div>
            <button class="btn custom-btn btn-email">📧 Update Email</button>
        </form>

        <!-- Screen Lock -->
        <form v-if="activeTab === 'screen'" @submit.prevent="updateAll('screen')" class="card shadow-sm p-4">
            <div class="form-check form-switch mb-3">
                <input
                    v-model="screen.lock"
                    type="checkbox"
                    class="form-check-input"
                    :true-value="1"
                    :false-value="0"
                    id="lockSwitch"
                />
                <label for="lockSwitch" class="form-check-label">Enable Screen Lock</label>
            </div>
            <button class="btn custom-btn btn-screen">🔒 Update Screen Lock</button>
        </form>

        <!-- Shift Settings -->
        <form v-if="activeTab === 'Shift'" @submit.prevent="updateAll('shift')" class="card shadow-sm p-4">
            <div class="mb-3">
                <label class="form-label fw-bold">Select Shift</label>
                <select v-model="shift.selected" class="form-select w-25">
                    <option disabled value="">-- Select Shift --</option>
                    <option value="1">No of Shifts 1 (24Hours)</option>
                    <option value="2">No of Shifts 2 (12Hours)</option>
                    <option value="3">No of Shifts 3(8Hours)</option>
                </select>
            </div>
            <button class="btn custom-btn btn-shift">⏱ Update Shift</button>
        </form>

        <!-- Dispensers Tab -->
        <div v-if="activeTab === 'Dispensers'" class="card shadow-sm p-4">

            <!-- GLOBAL DETECT BUTTONS -->
            <div class="mb-3">
                <button
                    class="action-btn btn-danger btn-detect"
                    @click="detectDevice('dispenser')"
                    :disabled="detecting"
                >
                    <i class="fas fa-broadcast-tower"></i> {{ detecting ? 'Detecting...' : 'Detect Device' }}
                </button>

                <!-- Detect Result Section -->
                <div v-if="detectionResult && detectionResult.uuid" class="detect-box mt-3 p-3 border rounded">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label>UUID</label>
                            <input type="text" class="form-control" :value="detectionResult.uuid" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Select Dispenser</label>
                            <select v-model="selectedDispenserForConfig" class="form-control">
                                <option disabled value="">-- Select Dispenser --</option>
                                <option v-for="dispenser in dispensers" :key="dispenser.DevID" :value="dispenser.DevID">
                                    Dispenser #{{ dispenser.DevID }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button class="btn btn-success w-100" @click="handleConfigWrite" :disabled="!selectedDispenserForConfig || loadingWrite">
                                <i v-if="loadingWrite" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-upload"></i>
                                {{ loadingWrite ? 'Writing...' : 'Config Write' }}
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary w-100" @click="handleConfigRead" :disabled="!selectedDispenserForConfig || loadingRead">
                                <i v-if="loadingRead" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-download"></i>
                                {{ loadingRead ? 'Reading...' : 'Config Read' }}
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="detectionError" class="alert alert-danger mt-3">{{ detectionError }}</div>
            </div>

            <!-- DISPENSER TABLE -->
            <div class="mb-3">
                <table class="dispenser-table">
                    <thead>
                    <tr>
                        <th>Dispenser</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="dispenser in dispensers" :key="dispenser.DevID">
                        <td class="dispenser-info">
                            <span class="dispenser-id">#{{ dispenser.DevID }}</span>
                            <img :src="getDispenerImage()" class="dispenser-img" alt="Nozzle" />
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit-btn" @click="fetchStatsWithLoading('/api/dispenser/config-read', dispenser.DevID, 'read')" :disabled="loadingStates[dispenser.DevID]?.read">
                                    <i v-if="loadingStates[dispenser.DevID]?.read" class="fas fa-spinner fa-spin"></i>
                                    <i v-else class="fas fa-download"></i>
                                    {{ loadingStates[dispenser.DevID]?.read ? 'Reading...' : 'Config Read' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="dispensers.length === 0">
                        <td colspan="2" class="text-center">No dispensers found</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popup Modal -->
        <div v-if="showPopup" class="modal-container" @click.self="closePopup">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>
                        <i v-if="popupLoading" class="fas fa-spinner fa-spin"></i>
                        Dispenser {{ currentDispenserId || '-' }} Status
                    </h5>
                    <button @click="closePopup" class="btn-close">×</button>
                </div>
                <div class="modal-body">
                    <div v-if="popupLoading" class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Waiting for device response...</p>
                        <small class="text-muted">This may take a few seconds</small>
                    </div>
                    <div v-else-if="statsError" class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> {{ statsError }}
                    </div>
                    <div v-else-if="statsData">
                        <div v-if="statsData.status" class="mb-3">
                            <div :class="{
                                'status-badge success': statsData.status === 'success',
                                'status-badge error': statsData.status === 'error',
                                'status-badge processing': statsData.status === 'processing'
                            }">
                                <i v-if="statsData.status === 'success'" class="fas fa-check-circle"></i>
                                <i v-else-if="statsData.status === 'processing'" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-times-circle"></i>
                                {{ statsData.status === 'success' ? 'Success' : statsData.status === 'processing' ? 'Processing...' : 'Failed' }}
                            </div>
                        </div>
                        <div v-if="statsData.command" class="alert alert-info">
                            <strong>Command:</strong> {{ statsData.command }}
                        </div>
                        <div v-if="parsedData?.rf">
                            <h6 class="mt-3">RF Configuration</h6>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th>SID</th>
                                    <th>Frequency</th>
                                    <th>Node Type</th>
                                    <th>Device Address</th>
                                    <th>Device Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ parsedData.rf.sid || '-' }}</td>
                                    <td>{{ parsedData.rf.frequency || '-' }}</td>
                                    <td>{{ parsedData.rf.nodeType || '-' }}</td>
                                    <td>{{ parsedData.rf.deviceAddress || '-' }}</td>
                                    <td>{{ parsedData.rf.deviceType || '-' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="parsedData?.pos && parsedData.pos.length">
                            <h6 class="mt-3">POS Configuration</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th>System Mode</th>
                                        <th>Manufacturer</th>
                                        <th>Model</th>
                                        <th>EPPN</th>
                                        <th>POS Channels</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(p, idx) in parsedData.pos" :key="idx">
                                        <td>{{ p.systemMode || '-' }}</td>
                                        <td>{{ p.manufacturer || '-' }}</td>
                                        <td>{{ p.model || '-' }}</td>
                                        <td>{{ p.eppn || '-' }}</td>
                                        <td>{{ p.posChannels || '-' }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-if="parsedData?.nozzles && parsedData.nozzles.length">
                            <h6 class="mt-3">Nozzle Configuration</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th>Nozzle ID</th>
                                        <th>MPD</th>
                                        <th>POS Addr</th>
                                        <th>FC Addr</th>
                                        <th>POS Channel</th>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>Price</th>
                                        <th>Shutoff Timer</th>
                                        <th>Lock Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(noz, idx) in parsedData.nozzles" :key="idx">
                                        <td>{{ noz.id || '-' }}</td>
                                        <td>{{ noz.mpd || '-' }}</td>
                                        <td>{{ noz.posAddr || '-' }}</td>
                                        <td>{{ noz.fcAddr || '-' }}</td>
                                        <td>{{ noz.posChannel || '-' }}</td>
                                        <td>{{ noz.productName || '-' }}</td>
                                        <td>{{ noz.productCode || '-' }}</td>
                                        <td>{{ noz.price || '-' }}</td>
                                        <td>{{ noz.shutoffTimer || '-' }}s</td>
                                        <td>
                                                <span :class="noz.lockStatus === 'Locked' ? 'badge bg-danger' : 'badge bg-success'">
                                                    {{ noz.lockStatus || '-' }}
                                                </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-if="parsedData?.successMessage" class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ parsedData.successMessage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ATG Config Tab -->
        <div v-if="activeTab === 'atg_Status'" class="tag-container">
            <h3 class="section-title">
                <i class="fas fa-satellite-dish"></i> ATG Configuration
            </h3>

            <!-- Detect Device Button for ATG -->
            <div class="mb-3">
                <button
                    class="action-btn btn-danger btn-detect"
                    @click="detectDeviceAtg('atg')"
                    :disabled="detectingAtg"
                >
                    <i class="fas fa-broadcast-tower"></i>
                    {{ detectingAtg ? 'Detecting...' : 'Detect ATG Device' }}
                </button>

                <!-- ATG Detect Result Section -->
                <div v-if="atgDetectionResult && atgDetectionResult.uuid" class="detect-box mt-3 p-3 border rounded">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label>UUID</label>
                            <input type="text" class="form-control" :value="atgDetectionResult.uuid" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Select Tank</label>
                            <select v-model="selectedTankForConfig" class="form-control">
                                <option disabled value="">-- Select Tank --</option>
                                <option v-for="atg in atgStatus" :key="atg.TankID" :value="atg.TankID">
                                    Tank ID: {{ atg.TankID }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button class="btn btn-success w-100" @click="handleATGConfigWrite" :disabled="!selectedTankForConfig || loadingATGWrite">
                                <i v-if="loadingATGWrite" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-upload"></i>
                                {{ loadingATGWrite ? 'Writing...' : 'Config Write' }}
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary w-100" @click="handleATGConfigRead" :disabled="!selectedTankForConfig || loadingATGRead">
                                <i v-if="loadingATGRead" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-download"></i>
                                {{ loadingATGRead ? 'Reading...' : 'Config Read' }}
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="atgDetectionError" class="alert alert-danger mt-3">{{ atgDetectionError }}</div>
            </div>

            <!-- ATG Status Cards -->
            <div v-if="atgStatus.length > 0" class="atg-grid">
                <div v-for="atg in atgStatus" :key="atg.TankID" class="atg-card">
                    <div class="atg-card-header">
                        <span class="tank-id">Tank ID: {{ atg.TankID }}</span>
                        <span :class="atg.is_atg ? 'status active' : 'status inactive'">
                            {{ atg.is_atg ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <ul class="atg-details">
                        <li><strong>Node Type:</strong> {{ atg.bNodeType || '-' }}</li>
                        <li><strong>ICode:</strong> {{ atg.ICode || '-' }}</li>
                    </ul>
                    <div class="action-buttons">
                        <button class="action-btn btn-write" @click="fetchATGWithLoading('/api/atg/config-write', atg.TankID, 'write')" :disabled="atgLoadingStates[atg.TankID]?.write">
                            <i v-if="atgLoadingStates[atg.TankID]?.write" class="fas fa-spinner fa-spin"></i>
                            <i v-else class="fas fa-upload"></i>
                            {{ atgLoadingStates[atg.TankID]?.write ? 'Writing...' : 'Config Write' }}
                        </button>
                        <button class="action-btn edit-btn" @click="fetchATGWithLoading('/api/atg/config-read', atg.TankID, 'read')" :disabled="atgLoadingStates[atg.TankID]?.read">
                            <i v-if="atgLoadingStates[atg.TankID]?.read" class="fas fa-spinner fa-spin"></i>
                            <i v-else class="fas fa-download"></i>
                            {{ atgLoadingStates[atg.TankID]?.read ? 'Reading...' : 'Config Read' }}
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="no-data">No ATG status available.</div>
        </div>

        <!-- ATG Popup -->
        <div v-if="showATGPopup" class="modal-container" @click.self="closePopup">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>
                        <i v-if="atgPopupLoading" class="fas fa-spinner fa-spin"></i>
                        ATG Tank {{ selectedTankForConfig || '-' }} Status
                    </h5>
                    <button @click="closePopup" class="btn-close">×</button>
                </div>
                <div class="modal-body">
                    <div v-if="atgPopupLoading" class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Waiting for ATG device response...</p>
                        <small class="text-muted">This may take a few seconds</small>
                    </div>
                    <div v-else-if="atgStatsError" class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> {{ atgStatsError }}
                    </div>
                    <div v-else-if="atgStatsData">
                        <div v-if="atgStatsData.status" class="mb-3">
                            <div :class="{
                                'status-badge success': atgStatsData.status === 'success',
                                'status-badge error': atgStatsData.status === 'error',
                                'status-badge processing': atgStatsData.status === 'processing'
                            }">
                                <i v-if="atgStatsData.status === 'success'" class="fas fa-check-circle"></i>
                                <i v-else-if="atgStatsData.status === 'processing'" class="fas fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-times-circle"></i>
                                {{ atgStatsData.status === 'success' ? 'Success' : atgStatsData.status === 'processing' ? 'Processing...' : 'Failed' }}
                            </div>
                        </div>
                        <div v-if="atgStatsData.command" class="alert alert-info">
                            <strong>Command:</strong> {{ atgStatsData.command }}
                        </div>
                        <div v-if="atgStatsData.parsed?.rf">
                            <h6 class="mt-3">RF Configuration</h6>
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th>SID</th>
                                    <th>Frequency</th>
                                    <th>Node Type</th>
                                    <th>Device Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ atgStatsData.parsed.rf.sid || '-' }}</td>
                                    <td>{{ atgStatsData.parsed.rf.frequency || '-' }}</td>
                                    <td>{{ atgStatsData.parsed.rf.nodeType || '-' }}</td>
                                    <td>{{ atgStatsData.parsed.rf.deviceAddress || '-' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="atgStatsData.parsed?.atgMode" class="alert alert-info">
                            <strong>ATG Mode:</strong> {{ atgStatsData.parsed.atgMode }}
                        </div>
                        <div v-if="atgStatsData.parsed?.atg && atgStatsData.parsed.atg.length">
                            <h6 class="mt-3">ATG Tank Configuration</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr style="background-color: #f8f9fa;">
                                        <th>ATG Model</th>
                                        <th>POS Channel</th>
                                        <th>Status</th>
                                        <th>Mode</th>
                                        <th>V1</th>
                                        <th>V2</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(tank, idx) in atgStatsData.parsed.atg" :key="idx">
                                        <td>{{ tank.id || '-' }}</td>
                                        <td>{{ tank.posChannel || '-' }}</td>
                                        <td>{{ tank.status || '-' }}</td>
                                        <td>{{ tank.mode || '-' }}</td>
                                        <td>{{ tank.v1 || '-' }}</td>
                                        <td>{{ tank.v2 || '-' }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-if="!atgStatsData.parsed?.rf && !atgStatsData.parsed?.atg?.length" class="alert alert-warning mt-3">
                            <strong>Raw Response:</strong>
                            <pre class="mt-2 mb-0" style="font-size: 12px; max-height: 300px; overflow-y: auto;">{{ atgStatsData.output || atgStatsData.response || 'No data' }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Tabs -->
        <div v-if="activeTab === 'tag_Status_dis'" class="tag-container">
            <h3 class="section-title">Tag Status</h3>
            <div v-if="tagStatus.length > 0" class="tag-grid">
                <div v-for="tag in tagStatus" :key="tag.id" class="tag-card">
                    <div class="rssi">
                        <p>Signal Strength (RSSI):</p>
                        <div class="rssi-bar">
                            <div class="rssi-fill" :style="{ width: Math.min(tag.RSSI, 100) + '%' }"></div>
                        </div>
                        <small>Value: {{ tag.RSSI }}</small>
                    </div>
                    <div class="nozzles">
                        <p>Nozzles</p>
                        <div class="nozzle-grid">
                            <div v-for="i in 6" :key="i" class="nozzle" :class="tag[`Nozzel${i}`] ? 'active' : 'inactive'">
                                <span>N{{ i }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="tag-footer">Last Ping: {{ formatDate(tag.last_updated) }}</div>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'performance'"><PerformanceTab /></div>
        <div v-if="activeTab === 'sale_send'"><SaleSendTab /></div>
        <div v-if="activeTab === 'router_setting'"><RouterSettingTab /></div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import PerformanceTab from "./PerformanceTab.vue"
import SaleSendTab from "./SaleSendTab.vue"
import RouterSettingTab from "./RouterSettingTab.vue";

// Tabs
const tabs = ref([
    { key: "basic", label: "Basic" },
    { key: "email", label: "Email" },
    { key: "screen", label: "Screen Lock" },
    { key: "Shift", label: "Shift Change" },
    { key: "Dispensers", label: "Dispensers" },
    { key: "performance", label: "Performance" },
    { key: "sale_send", label: "Sales Data" },
    { key: "router_setting", label: "Router Setting" },
]);

const activeTab = ref("basic");
const settings = ref([]);
const email = ref({ enable: 0 });
const screen = ref({ lock: 0 });
const shift = ref({ selected: "" });
const dispensers = ref([]);
const tagStatus = ref([]);
const atgStatus = ref([]);

// Popup states
const showPopup = ref(false);
const showATGPopup = ref(false);
const currentDispenserId = ref(null);
const statsData = ref(null);
const statsError = ref(null);
const popupLoading = ref(false);

// Detection states
const detectionResult = ref(null);
const detectionError = ref(null);
const detecting = ref(false);
const selectedDispenserForConfig = ref("");

// Loading states for each dispenser
const loadingStates = ref({});
const loadingWrite = ref(false);
const loadingRead = ref(false);

// ATG Detection states
const atgDetectionResult = ref(null);
const atgDetectionError = ref(null);
const detectingAtg = ref(false);
const selectedTankForConfig = ref("");

// ATG Loading states
const atgLoadingStates = ref({});
const loadingATGWrite = ref(false);
const loadingATGRead = ref(false);

// ATG Popup states
const atgStatsData = ref(null);
const atgStatsError = ref(null);
const atgPopupLoading = ref(false);

// Parsed data
const parsedData = ref({ rf: null, pos: [], nozzles: [], successMessage: null });
const parsedATG = ref(null);
const loadingATG = ref(false);
const atgError = ref(null);
const atgData = ref(null);
const productMap = ref({});

let pollingIntervals = {};

const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    return new Date(dateStr).toLocaleString();
};

function getDispenerImage() {
    return "assets/images/Nozzle_one.png";
}

// ==================================================
// CSV Parsing for Stats - Handles database response format
// ==================================================
function parseCsvData(rawCsv) {
    let parsed = { rf: null, pos: [], nozzles: [], successMessage: null };

    if (!rawCsv) return parsed;

    if (typeof rawCsv === 'string') {
        const sections = rawCsv.trim().split(/\s+/);

        for (let i = 0; i < sections.length; i++) {
            const section = sections[i].trim();
            if (!section) continue;

            if (section.toUpperCase().startsWith("RF:")) {
                const rfParts = section.split(',');
                const freq = parseFloat(rfParts[1]?.trim()) || 0;
                parsed.rf = {
                    sid: rfParts[0]?.replace("RF:", "").trim() || "-",
                    frequency: (freq / 10.0).toFixed(1) + " MHz",
                    nodeType: rfParts[2]?.trim() === "3" ? "End Device" : rfParts[2]?.trim() === "2" ? "Routing Device" : "Unknown",
                    deviceAddress: rfParts[3]?.trim() || "-",
                    deviceType: rfParts[4]?.trim() === "1" ? "DispenserUnit": "Unknown" ,
                };
            }
            else if (section.toUpperCase().startsWith("POS:")) {
                const posParts = section.split(',');
                const sysMode = posParts[0]?.replace("POS:", "").trim();
                const mk = posParts[1]?.trim();
                const mdl = posParts[2]?.trim();
                const eppn = posParts[3]?.trim() || "0";
                const posChannels = posParts[4]?.trim() || "0";

                let manufacturer = "Unknown";
                if (mk === "1") manufacturer = "Tatsuno";
                else if (mk === "2") manufacturer = "Wayne";
                else if (mk === "3") manufacturer = "Korea ENE";
                else if (mk === "4") manufacturer = "LOCAL";

                let modelName = "Unknown";
                if (mk === "1") {
                    if (mdl === "2") modelName = "SUNNY EX";
                    else if (mdl === "1") modelName = "GDA";
                } else if (mk === "2") {
                    if (mdl === "3") modelName = "WayneDart9600";
                    else if (mdl === "4") modelName = "WayneDart19200";
                    else if (mdl === "5") modelName = "WayneCL";

                } else if (mk === "3" || mk === "4") {
                    if (mdl === "6") modelName = "KoreaENEZicMode";

                    // modelName = `Model ${mdl}`;
                }

                parsed.pos.push({
                    systemMode: sysMode === "1" ? "Master" : "Slave",
                    manufacturer: manufacturer,
                    model: modelName,
                    eppn: eppn,
                    posChannels: posChannels
                });
            }
            else if (/^\d+\.\d+:/i.test(section)) {
                const nozzleParts = section.split(',');
                const [nozId, mpd] = nozzleParts[0].split(':');
                const posAddr = nozzleParts[1]?.trim() || "-";
                const fcAddr = nozzleParts[2]?.trim() || "-";
                const posChannel = nozzleParts[3]?.trim() || "-";
                const pIcode = nozzleParts[4]?.trim() || "-";
                const price = parseFloat(nozzleParts[5]?.trim()) || 0;
                const shutoffTimer = nozzleParts[6]?.trim() || "0";
                const nozzleLocked = nozzleParts[7]?.trim() === "1" ? "Locked" : "Unlocked";

                const productName = productMap.value[pIcode] || (pIcode !== "-" ? `Product ${pIcode}` : "-");

                parsed.nozzles.push({
                    id: nozId || "-",
                    mpd: mpd || "-",
                    posAddr: posAddr,
                    fcAddr: fcAddr,
                    posChannel: posChannel,
                    productCode: pIcode,
                    productName: productName,
                    price: price.toFixed(2),
                    shutoffTimer: shutoffTimer,
                    lockStatus: nozzleLocked
                });
            }
        }
    }
    else if (Array.isArray(rawCsv)) {
        for (let i = 0; i < rawCsv.length; i++) {
            const row = rawCsv[i];
            if (!row) continue;

            let section = Array.isArray(row) ? row[0] : row;
            if (!section) continue;

            section = section.toString().trim();

            if (section.includes(' ') && (section.includes('RF:') || section.includes('POS:'))) {
                const subSections = section.split(/\s+/);
                for (let j = 0; j < subSections.length; j++) {
                    processSection(subSections[j].trim(), parsed);
                }
            } else {
                processSection(section, parsed);
            }
        }
    }

    return parsed;
}

function processSection(section, parsed) {
    if (!section) return;

    if (section.toUpperCase().startsWith("RF:")) {
        const rfParts = section.split(',');
        const freq = parseFloat(rfParts[1]?.trim()) || 0;
        parsed.rf = {
            sid: rfParts[0]?.replace("RF:", "").trim() || "-",
            frequency: (freq / 10.0).toFixed(1) + " MHz",
            nodeType: rfParts[2]?.trim() === "3" ? "End Device" : rfParts[2]?.trim() === "2" ? "Routing Device" : "Unknown",
            deviceAddress: rfParts[3]?.trim() || "-",
            deviceType: rfParts[4]?.trim() === "1" ? "Primary" : rfParts[4]?.trim() === "2" ? "Secondary" : "Unknown"
        };
    }
    else if (section.toUpperCase().startsWith("POS:")) {
        const posParts = section.split(',');
        const sysMode = posParts[0]?.replace("POS:", "").trim();
        const mk = posParts[1]?.trim();
        const mdl = posParts[2]?.trim();
        const eppn = posParts[3]?.trim() || "0";
        const posChannels = posParts[4]?.trim() || "0";

        let manufacturer = "Unknown";
        if (mk === "1") manufacturer = "Tatsuno";
        else if (mk === "2") manufacturer = "Wayne";
        else if (mk === "3") manufacturer = "Korea ENE";
        else if (mk === "4") manufacturer = "LOCAL";

        let modelName = "Unknown";
        if (mk === "1") {
            if (mdl === "2") modelName = "SUNNY EX";
            else if (mdl === "3") modelName = "GDA";
        } else if (mk === "2") {
            if (mdl === "4") modelName = "DART";
        } else if (mk === "3" || mk === "4") {
            modelName = `Model ${mdl}`;
        }

        parsed.pos.push({
            systemMode: sysMode === "0" ? "Master" : "Slave",
            manufacturer: manufacturer,
            model: modelName,
            eppn: eppn,
            posChannels: posChannels
        });
    }
    else if (/^\d+\.\d+:/i.test(section)) {
        const nozzleParts = section.split(',');
        const [nozId, mpd] = nozzleParts[0].split(':');
        const posAddr = nozzleParts[1]?.trim() || "-";
        const fcAddr = nozzleParts[2]?.trim() || "-";
        const posChannel = nozzleParts[3]?.trim() || "-";
        const pIcode = nozzleParts[4]?.trim() || "-";
        const price = parseFloat(nozzleParts[5]?.trim()) || 0;
        const shutoffTimer = nozzleParts[6]?.trim() || "0";
        const nozzleLocked = nozzleParts[7]?.trim() === "1" ? "Locked" : "Unlocked";

        const productName = productMap.value[pIcode] || (pIcode !== "-" ? `Product ${pIcode}` : "-");

        parsed.nozzles.push({
            id: nozId || "-",
            mpd: mpd || "-",
            posAddr: posAddr,
            fcAddr: fcAddr,
            posChannel: posChannel,
            productCode: pIcode,
            productName: productName,
            price: price.toFixed(2),
            shutoffTimer: shutoffTimer,
            lockStatus: nozzleLocked
        });
    }
}

// Parse stats data from various formats
const parseStatsData = (data) => {
    if (!data) return { rf: null, pos: [], nozzles: [], successMessage: null };

    if (data.csv_data && Array.isArray(data.csv_data)) {
        return parseCsvData(data.csv_data);
    }
    if (data.output && typeof data.output === 'string') {
        return parseCsvData(data.output);
    }
    if (typeof data === 'string') {
        return parseCsvData(data);
    }
    if (data.response && typeof data.response === 'string') {
        return parseCsvData(data.response);
    }

    return { rf: null, pos: [], nozzles: [], successMessage: null };
};

// ==================================================
// Settings update functions
// ==================================================
const handleFileChange = (event, index) => {
    const file = event.target.files[0];
    settings.value[index].file = file;
};

const updateSettings = async () => {
    const formData = new FormData();
    settings.value.forEach((setting, index) => {
        formData.append(`data[${index}][value]`, setting.type === "file" ? setting.file : setting.value);
        formData.append(`data[${index}][type]`, setting.type);
        formData.append(`data[${index}][description]`, setting.description);
        formData.append(`data[${index}][key]`, setting.key);
        formData.append(`data[${index}][id]`, setting.id ?? "");
    });

    try {
        await axios.post("/api/settings/update", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        alert("✅ Settings updated successfully");
    } catch (error) {
        alert("❌ Failed to update settings");
    }
};

const updateAll = async (type) => {
    let payload = { type };
    if (type === "email") payload.data = email.value;
    if (type === "screen") payload.data = screen.value;
    if (type === "shift") payload.data = shift.value;
    try {
        await axios.post("/api/settings/update-all", payload);
        alert(`✅ ${type} settings updated successfully`);
    } catch (error) {
        alert(`❌ Failed to update ${type} settings`);
    }
};

// ==================================================
// Detection functions for Dispenser
// ==================================================
const detectDevice = async (type = "dispenser") => {
    detecting.value = true;
    detectionError.value = null;
    detectionResult.value = null;

    try {
        const response = await axios.post("/api/dispenser/detect", { type });

        if (response.data.status === "success" || response.data.status === "processing") {
            // let uuid = response.data.response || response.data.response || "UUID received";
            // console.log(response.data);
            // console.log(response.data);
            let uuid = response.data.csv_data?.[0]?.[0] || "UUID received";

            // console.log("UUID:", uuid);
            detectionResult.value = {
                uuid: uuid,
                command_id: response.data.command_id,
                message: response.data.message
            };
        } else {
            detectionError.value = response.data.message || "Device detection failed";
        }
    } catch (error) {
        detectionError.value = error.response?.data?.message || error.message;
    } finally {
        detecting.value = false;
    }
};

// ==================================================
// Detection functions for ATG
// ==================================================
const detectDeviceAtg = async (type = "atg") => {
    detectingAtg.value = true;
    atgDetectionError.value = null;
    atgDetectionResult.value = null;
    selectedTankForConfig.value = "";

    try {
        const response = await axios.post("/api/dispenser/detect_atg", { type });

        if (response.data.status === "success" || response.data.status === "processing") {
            // let uuid = response.data.uuid || response.data.response || "ATG Device detected";
            let uuid = response.data.csv_data?.[0]?.[0] || "ATG Device detected";

            atgDetectionResult.value = {
                uuid: uuid,
                command_id: response.data.command_id,
                message: response.data.message
            };
        } else {
            atgDetectionError.value = response.data.message || "ATG Device detection failed";
        }
    } catch (error) {
        atgDetectionError.value = error.response?.data?.message || error.message;
    } finally {
        detectingAtg.value = false;
    }
};

// ==================================================
// Polling function for command status (Dispenser)
// ==================================================
const startPolling = (commandId, dispenserId, actionType) => {
    if (pollingIntervals[commandId]) {
        clearInterval(pollingIntervals[commandId]);
    }

    let attempts = 0;
    const maxAttempts = 10;
    pollingIntervals[commandId] = setInterval(async () => {
        attempts++;

        try {
            const response = await axios.get(`/api/commands/${commandId}`);
            const command = response.data;

            if (command.exec === 2) {
                clearInterval(pollingIntervals[commandId]);
                delete pollingIntervals[commandId];

                if (command.response) {
                    statsData.value = {
                        status: "success",
                        command: command.type,
                        output: command.response,
                        response: command.response
                    };
                } else {
                    statsError.value = "Command completed but no response data";
                }
                popupLoading.value = false;
                if (loadingStates.value[dispenserId]) {
                    loadingStates.value[dispenserId][actionType] = false;
                }
            }
            else if (command.exec === 1 || command.exec === 0) {
                if (attempts >= maxAttempts) {
                    clearInterval(pollingIntervals[commandId]);
                    delete pollingIntervals[commandId];
                    statsError.value = "Timeout: No response after 30 seconds";
                    popupLoading.value = false;
                    if (loadingStates.value[dispenserId]) {
                        loadingStates.value[dispenserId][actionType] = false;
                    }
                }
            }
            else if (command.exec === -1) {
                clearInterval(pollingIntervals[commandId]);
                delete pollingIntervals[commandId];
                statsError.value = command.error_message || "Command failed";
                popupLoading.value = false;
                if (loadingStates.value[dispenserId]) {
                    loadingStates.value[dispenserId][actionType] = false;
                }
            }
        } catch (error) {
            clearInterval(pollingIntervals[commandId]);
            delete pollingIntervals[commandId];
            statsError.value = "Error checking command status";
            popupLoading.value = false;
            if (loadingStates.value[dispenserId]) {
                loadingStates.value[dispenserId][actionType] = false;
            }
        }
    }, 3000);
};

// ==================================================
// Polling function for ATG command status
// ==================================================
const startATGPolling = (commandId, tankId, actionType) => {
    if (pollingIntervals[`atg_${commandId}`]) {
        clearInterval(pollingIntervals[`atg_${commandId}`]);
    }

    let attempts = 0;
    const maxAttempts = 10;

    pollingIntervals[`atg_${commandId}`] = setInterval(async () => {
        attempts++;

        try {
            const response = await axios.get(`/api/commands/${commandId}`);
            const command = response.data;

            if (command.exec === 2) {
                clearInterval(pollingIntervals[`atg_${commandId}`]);
                delete pollingIntervals[`atg_${commandId}`];

                if (command.response) {
                    const parsedATGData = parseATGData(command.response);
                    atgStatsData.value = {
                        status: "success",
                        command: command.type,
                        output: command.response,
                        response: command.response,
                        parsed: parsedATGData
                    };
                    showATGPopup.value = true;
                } else {
                    atgStatsError.value = "Command completed but no response data";
                    showATGPopup.value = true;
                }
                atgPopupLoading.value = false;
                if (atgLoadingStates.value[tankId]) {
                    atgLoadingStates.value[tankId][actionType] = false;
                }
            }
            else if (command.exec === 1 || command.exec === 0) {
                if (attempts >= maxAttempts) {
                    clearInterval(pollingIntervals[`atg_${commandId}`]);
                    delete pollingIntervals[`atg_${commandId}`];
                    atgStatsError.value = "Timeout: No response after 30 seconds";
                    atgPopupLoading.value = false;
                    showATGPopup.value = true;
                    if (atgLoadingStates.value[tankId]) {
                        atgLoadingStates.value[tankId][actionType] = false;
                    }
                }
            }
            else if (command.exec === -1) {
                clearInterval(pollingIntervals[`atg_${commandId}`]);
                delete pollingIntervals[`atg_${commandId}`];
                atgStatsError.value = command.error_message || "Command failed";
                atgPopupLoading.value = false;
                showATGPopup.value = true;
                if (atgLoadingStates.value[tankId]) {
                    atgLoadingStates.value[tankId][actionType] = false;
                }
            }
        } catch (error) {
            clearInterval(pollingIntervals[`atg_${commandId}`]);
            delete pollingIntervals[`atg_${commandId}`];
            atgStatsError.value = "Error checking command status";
            atgPopupLoading.value = false;
            showATGPopup.value = true;
            if (atgLoadingStates.value[tankId]) {
                atgLoadingStates.value[tankId][actionType] = false;
            }
        }
    }, 3000);
};

// ==================================================
// Main fetch function for dispenser operations
// ==================================================
const fetchStatsWithLoading = async (url, dispenserId, actionType) => {
    if (!dispenserId) return;

    if (!loadingStates.value[dispenserId]) {
        loadingStates.value[dispenserId] = {};
    }
    loadingStates.value[dispenserId][actionType] = true;

    currentDispenserId.value = dispenserId;
    popupLoading.value = true;
    statsError.value = null;
    statsData.value = null;
    showPopup.value = true;

    try {
        const response = await axios.post(url, { dispenserId });

        if (response.data.status === "processing" && response.data.command_id) {
            startPolling(response.data.command_id, dispenserId, actionType);
        }
        else if (response.data.status === "success") {
            statsData.value = {
                ...response.data,
                output: response.data.output,
                response: response.data.output
            };
            popupLoading.value = false;
            loadingStates.value[dispenserId][actionType] = false;
        }
        else {
            statsError.value = response.data.message || "Command failed";
            popupLoading.value = false;
            loadingStates.value[dispenserId][actionType] = false;
        }
    } catch (error) {
        statsError.value = error.response?.data?.message || error.message;
        popupLoading.value = false;
        loadingStates.value[dispenserId][actionType] = false;
    }
};

// ==================================================
// Main fetch function for ATG operations
// ==================================================
const fetchATGWithLoading = async (url, tankId, actionType) => {
    if (!tankId) return;

    if (!atgLoadingStates.value[tankId]) {
        atgLoadingStates.value[tankId] = {};
    }
    atgLoadingStates.value[tankId][actionType] = true;

    atgPopupLoading.value = true;
    atgStatsError.value = null;
    atgStatsData.value = null;
    showATGPopup.value = true;

    try {
        const response = await axios.post(url, { TankID: tankId });

        if (response.data.status === "processing" && response.data.command_id) {
            startATGPolling(response.data.command_id, tankId, actionType);
        }
        else if (response.data.status === "success") {
            const parsedATGData = parseATGData(response.data.output || response.data.response);
            atgStatsData.value = {
                ...response.data,
                output: response.data.output,
                response: response.data.response,
                parsed: parsedATGData
            };
            atgPopupLoading.value = false;
            atgLoadingStates.value[tankId][actionType] = false;
        }
        else {
            atgStatsError.value = response.data.message || "Command failed";
            atgPopupLoading.value = false;
            atgLoadingStates.value[tankId][actionType] = false;
            showATGPopup.value = true;
        }
    } catch (error) {
        atgStatsError.value = error.response?.data?.message || error.message;
        atgPopupLoading.value = false;
        atgLoadingStates.value[tankId][actionType] = false;
        showATGPopup.value = true;
    }
};

// ==================================================
// Parse ATG Data
// ==================================================
function parseATGData(rawData) {
    let parsed = { rf: null, atgMode: null, atg: [] };
    if (!rawData) return parsed;

    if (typeof rawData === 'string') {
        const lines = rawData.trim().split(/\r?\n/);
        for (let i = 0; i < lines.length; i++) {
            const line = lines[i].trim();
            if (!line) continue;

            if (line.toUpperCase().startsWith("RF:")) {
                const parts = line.split(',');
                const freq = parseFloat(parts[1]?.trim()) || 0;
                parsed.rf = {
                    sid: parts[0]?.replace("RF:", "").trim() || "-",
                    frequency: (freq / 10.0).toFixed(1) + " MHz",
                    nodeType: parts[2]?.trim() === "3" ? "End Device" : parts[2]?.trim() === "2" ? "Routing Device" : "Unknown",
                    deviceAddress: parts[3]?.trim() || "-"
                };
            } else if (line.toUpperCase().startsWith("ATG_MODE")) {
                const parts = line.split(',');
                parsed.atgMode = parts[1]?.trim() || "-";
            } else if (line.toUpperCase().startsWith("ATG")) {
                const parts = line.split(',');
                parsed.atg.push({
                    id: parts[0] || "-",
                    posChannel: parts[1]?.trim() || "-",
                    status: parts[2]?.trim() || "-",
                    mode: parts[3]?.trim() || "-",
                    v1: parts[4]?.trim() || "-",
                    v2: parts[5]?.trim() || "-"
                });
            }
        }
    }
    return parsed;
}

// ==================================================
// Handle config operations from detection box
// ==================================================
const handleConfigWrite = () => {
    if (selectedDispenserForConfig.value) {
        loadingWrite.value = true;
        fetchStatsWithLoading('/api/dispenser/config-write', selectedDispenserForConfig.value, 'write')
            .finally(() => {
                loadingWrite.value = false;
            });
    }
};

const handleConfigRead = () => {
    if (selectedDispenserForConfig.value) {
        loadingRead.value = true;
        fetchStatsWithLoading('/api/dispenser/config-read', selectedDispenserForConfig.value, 'read')
            .finally(() => {
                loadingRead.value = false;
            });
    }
};

const handleATGConfigWrite = () => {
    if (selectedTankForConfig.value) {
        loadingATGWrite.value = true;
        fetchATGWithLoading('/api/atg/config-write', selectedTankForConfig.value, 'write')
            .finally(() => {
                loadingATGWrite.value = false;
            });
    }
};

const handleATGConfigRead = () => {
    if (selectedTankForConfig.value) {
        loadingATGRead.value = true;
        fetchATGWithLoading('/api/atg/config-read', selectedTankForConfig.value, 'read')
            .finally(() => {
                loadingATGRead.value = false;
            });
    }
};

// ==================================================
// Close popup and cleanup
// ==================================================
const closePopup = () => {
    showPopup.value = false;
    showATGPopup.value = false;
    popupLoading.value = false;
    atgPopupLoading.value = false;
    statsData.value = null;
    statsError.value = null;
    atgStatsData.value = null;
    atgStatsError.value = null;

    Object.values(pollingIntervals).forEach(interval => clearInterval(interval));
    pollingIntervals = {};
};

// ==================================================
// Watch for statsData changes to parse CSV
// ==================================================
watch(
    statsData,
    (newVal) => {
        if (!newVal) {
            parsedData.value = { rf: null, pos: [], nozzles: [] };
            return;
        }

        let raw = newVal.response || newVal.output || newVal.csv_data || newVal;
        parsedData.value = parseCsvData(raw);
    },
    { immediate: true }
);

// ==================================================
// On Mount - Load initial data
// ==================================================
onMounted(async () => {
    try {
        const response = await axios.get("/api/settings");
        settings.value = response.data.settings ?? [];
        const sysConfig = response.data.sysConfig ?? {};
        email.value.enable = sysConfig.email_enable;
        screen.value.lock = sysConfig.is_screen_lock;
        shift.value.selected = sysConfig.NoofShifts;
    } catch (error) {
        console.error("Error fetching settings:", error);
    }

    try {
        const response = await axios.get("/dispensers");
        dispensers.value = response.data.datas.map(d => ({ ...d }));
    } catch (error) {
        console.error("Error fetching dispensers:", error);
    }

    try {
        const response = await axios.get("/tag_status");
        if (response.data.status === "success") tagStatus.value = response.data.data;
    } catch (error) {
        console.error("Error fetching tag_status:", error);
    }

    try {
        const response = await axios.get("/atg_config");
        if (response.data.status === "success") atgStatus.value = response.data.data;
    } catch (error) {
        console.error("Error fetching atg_config:", error);
    }

    try {
        const res = await axios.get("/api/products");
        productMap.value = res.data.reduce((map, product) => {
            map[product.ICODE] = product.ITMNAME;
            return map;
        }, {});
    } catch (error) {
        console.error("Error fetching products:", error);
    }

    try {
        const res = await axios.get("/api/sysconfig");
        const sysConfig = res.data;
        if (sysConfig.Sys_Mode === 3) {
            tabs.value = tabs.value.filter(tab => tab.key === "basic");
            if (sysConfig.ATGEnable === 1) {
                tabs.value.push({
                    key: "atg_Status",
                    label: "ATG Config"
                });
            }
        }
        if (sysConfig.ATGEnable === 1) {
            tabs.value.push({ key: "atg_Status", label: "ATG Config" });
        }
    } catch (err) {
        console.error("Failed to load sysconfig:", err);
    }
});
</script>

<style scoped>
/* Your existing styles remain the same */
.nav-tabs .nav-link { cursor: pointer; }
.card { border-radius: 12px; border: 1px solid #eee; }

.custom-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 15%;
    min-width: 160px;
    padding: 12px 22px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 14px;
    color: #fff;
    letter-spacing: 0.6px;
    backdrop-filter: blur(6px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.custom-btn::before {
    content: "";
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: rgba(255,255,255,0.25);
    transition: all 0.4s ease;
}
.custom-btn:hover::before {
    left: 100%;
}
.custom-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
}
.custom-btn:active {
    transform: scale(0.95);
}

.btn-email { background: linear-gradient(135deg, #1e90ff, #0057d9); }
.btn-screen { background: linear-gradient(135deg, #ff416c, #ff4b2b); }
.btn-shift { background: linear-gradient(135deg, #00c853, #009624); }

.detect-box {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.modal-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    width: 90%;
    max-width: 1000px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.btn-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.btn-close:hover { color: #000; }

.status-badge {
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: bold;
    display: inline-block;
}

.status-badge.success { background: #d4edda; color: #155724; }
.status-badge.error { background: #f8d7da; color: #721c24; }
.status-badge.processing { background: #fff3cd; color: #856404; }

.action-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.action-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-write { background: #28a745; color: white; }
.edit-btn { background: #007bff; color: white; }
.btn-danger { background: #dc3545; color: white; }

.action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }

.table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
.table th, .table td { padding: 8px; border: 1px solid #dee2e6; text-align: left; }
.table th { background: #f8f9fa; font-weight: 600; }

.table-responsive {
    overflow-x: auto;
}

.spinner-border { display: inline-block; width: 2rem; height: 2rem; }

.badge {
    display: inline-block;
    padding: 0.25em 0.5em;
    font-size: 0.75em;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.bg-success { background-color: #28a745; color: white; }
.bg-danger { background-color: #dc3545; color: white; }

pre {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
    overflow-x: auto;
    font-size: 12px;
}

.tag-container, .atg-grid { padding: 20px; }
.tag-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
.tag-card, .atg-card { background: #fff; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

.rssi-bar { background: #eee; border-radius: 8px; height: 8px; margin: 6px 0; }
.rssi-fill { background: #3b82f6; height: 8px; border-radius: 8px; }

.nozzle-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.nozzle { padding: 8px; border-radius: 8px; text-align: center; border: 1px solid #ddd; }
.nozzle.active { background: #e6f9f0; border-color: #10b981; color: #065f46; }
.nozzle.inactive { background: #fde8e8; border-color: #f87171; color: #7f1d1d; }

.status.active { background: #28a745; color: white; padding: 4px 12px; border-radius: 20px; }
.status.inactive { background: #dc3545; color: white; padding: 4px 12px; border-radius: 20px; }

.atg-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.tank-id { font-weight: 700; font-size: 18px; }

.atg-details {
    list-style: none;
    padding: 0;
    margin: 0 0 12px 0;
}

.atg-details li {
    margin-bottom: 6px;
}

.dispenser-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.dispenser-table thead {
    background: linear-gradient(135deg, #7b2ff7, #4c00ff);
    color: #fff;
}

.dispenser-table th, .dispenser-table td {
    padding: 12px 15px;
    text-align: left;
}

.dispenser-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dispenser-img {
    width: 50px;
    height: auto;
}

.dispenser-id {
    font-weight: bold;
    font-size: 16px;
}

@media (max-width: 768px) {
    .custom-btn { width: 100%; }
    .modal-content { width: 95%; margin: 10px; }
    .table { display: block; overflow-x: auto; }
    .action-buttons { flex-direction: column; }
}
</style>
