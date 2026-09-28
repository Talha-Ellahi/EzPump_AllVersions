<template>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="header-left">
                <h1>System Performance Dashboard</h1>
                <p>Real-time monitoring of system resources and performance metrics</p>
            </div>
            <div class="header-right">
                <!--                <div class="time-display" >-->
                <!--                    <i class="fas fa-clock"></i>-->
                <!--                    <span style="color: #FFFFFF">{{ currentTime }}</span>-->
                <!--                </div>-->
                <div class="status-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>All Systems Operational</span>
                </div>
            </div>
        </header>

        <!-- Alert -->
        <div class="alert-banner" v-if="showAlert">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="alert-content">
                <h3>Storage Usage Alert</h3>
                <p>Root partition is at {{ performance.disk }}% capacity. Consider cleaning up unnecessary files.</p>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="dashboard">
            <!-- CPU -->
            <div class="card cpu"  style="background-color: #FFFFFF">
                <div class="card-header" style="background-color: #FFFFFF">
                    <h2 class="card-title" style="background-color: #FFFFFF">CPU Utilization</h2>
                    <div class="card-icon">
                        <!-- CPU / Microchip SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                            <line x1="9" y1="4" x2="9" y2="2"></line>
                            <line x1="15" y1="4" x2="15" y2="2"></line>
                            <line x1="9" y1="22" x2="9" y2="20"></line>
                            <line x1="15" y1="22" x2="15" y2="20"></line>
                            <line x1="4" y1="9" x2="2" y2="9"></line>
                            <line x1="4" y1="15" x2="2" y2="15"></line>
                            <line x1="22" y1="9" x2="20" y2="9"></line>
                            <line x1="22" y1="15" x2="20" y2="15"></line>
                        </svg>
                    </div>
                </div>

                <div class="metric-value">30%</div>
                <div class="progress-container">
                    <div class="progress-bar" :style="{ width: performance.load + '%' }"></div>
                </div>
                <div class="metric-details">
                    <span>Temperature: {{ performance.cpuTemp }}°C</span>
                    <span>Load: 2.1</span>
                </div>
            </div>

            <!-- Memory -->
            <div class="card memory" style="background-color: #FFFFFF">
                <div class="card-header" style="background-color: #FFFFFF">
                    <h2 class="card-title" style="background-color: #FFFFFF">Memory Usage</h2>
                    <div class="card-icon">
                        <!-- Memory / RAM SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="7" width="18" height="10" rx="2" ry="2"></rect>
                            <line x1="7" y1="7" x2="7" y2="17"></line>
                            <line x1="10" y1="7" x2="10" y2="17"></line>
                            <line x1="14" y1="7" x2="14" y2="17"></line>
                            <line x1="17" y1="7" x2="17" y2="17"></line>
                        </svg>
                    </div>
                </div>

                <div class="metric-value">{{ performance.memory }}%</div>
                <div class="progress-container">
                    <div class="progress-bar" :style="{ width: performance.memory + '%' }"></div>
                </div>
                <div class="metric-details">
                    <span>Total: {{ performance.memoryTotal }}</span>
                    <span>Used: {{ Math.round(parseInt(performance.memoryTotal) * performance.memory / 100) }}MB</span>
                </div>
            </div>

            <!-- Storage -->
            <div class="card storage" style="background-color: #FFFFFF">
                <div class="card-header" style="background-color: #FFFFFF">
                    <h2 class="card-title" style="background-color: #FFFFFF">Storage Usage</h2>
                    <div class="card-icon" style="background-color: #FFFFFF">
                        <!-- Storage / HDD SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="7" width="18" height="10" rx="2" ry="2"></rect>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <circle cx="6" cy="12" r="1"></circle>
                            <circle cx="18" cy="12" r="1"></circle>
                        </svg>
                    </div>
                </div>

                <div class="metric-value">{{ performance.disk }}%</div>
                <div class="progress-container">
                    <div class="progress-bar" :style="{ width: performance.disk + '%' }"></div>
                </div>
                <div class="metric-details">
                    <span>Total: {{ performance.diskTotal }}</span>
                    <span>Used: {{ Math.round(parseFloat(performance.diskTotal) * performance.disk / 100) }}GB</span>
                </div>
            </div>

            <!-- Uptime -->
            <div class="card uptime" style="background-color: #FFFFFF">
                <div class="card-header" style="background-color: #FFFFFF">
                    <h2 class="card-title" style="background-color: #FFFFFF">System Uptime</h2>
                    <div class="card-icon">
                        <!-- Clock / System Uptime SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="6" x2="12" y2="12"></line>
                            <line x1="12" y1="12" x2="16" y2="14"></line>
                        </svg>
                    </div>
                </div>

                <div class="metric-value">{{ performance.uptimeDays }}</div>
                <div class="progress-container">
                    <div class="progress-bar" :style="{ width: performance.uptimeStability + '%' }"></div>
                </div>
                <div class="metric-details">
                    <span>{{ performance.uptimeHours }} hours {{ performance.uptimeMinutes }} minutes</span>
                    <span>Stability: {{ performance.uptimeStability }}%</span>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <!--        <div class="charts-container">-->
        <!--            <div class="chart-card" style="background-color: #FFFFFF">-->
        <!--                <div class="chart-header">-->
        <!--                    <h2 class="chart-title">Resource Usage Trends</h2>-->
        <!--                    <div class="chart-actions">-->
        <!--                        <button-->
        <!--                            v-for="range in timeRanges"-->
        <!--                            :key="range"-->
        <!--                            :class="{ active: selectedRange === range }"-->
        <!--                            @click="selectRange(range)"-->
        <!--                        >-->
        <!--                            {{ range }}-->
        <!--                        </button>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="chart-container">-->
        <!--                    <canvas ref="trendChart"></canvas>-->
        <!--                </div>-->
        <!--            </div>-->

        <!--            <div class="chart-card" style="background-color: #FFFFFF">-->
        <!--                <div class="chart-header">-->
        <!--                    <h2 class="chart-title">Memory Distribution</h2>-->
        <!--                    <div class="chart-actions">-->
        <!--                        <button-->
        <!--                            v-for="view in memoryViews"-->
        <!--                            :key="view"-->
        <!--                            :class="{ active: selectedMemoryView === view }"-->
        <!--                            @click="selectMemoryView(view)"-->
        <!--                        >-->
        <!--                            {{ view }}-->
        <!--                        </button>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="chart-container">-->
        <!--                    <canvas ref="memoryChart"></canvas>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- System Details -->
        <div class="system-details">
            <div class="detail-card" style="background-color: #FFFFFF">
                <div class="detail-title">LAN IP</div>
                <div class="detail-value">{{ performance.lanIp || 'N/A' }}</div>
            </div>
            <div class="detail-card" style="background-color: #FFFFFF">
                <div class="detail-title">WAN IP</div>
                <div class="detail-value">{{ performance.wanIp || 'N/A' }}</div>
            </div>
            <div class="detail-card" style="background-color: #FFFFFF">
                <div class="detail-title">ZRAM Usage</div>
                <div class="detail-value">{{ performance.zram }}% of {{ performance.zramTotal }}</div>
            </div>
            <!--            <div class="detail-card" style="background-color: #FFFFFF">-->
            <!--                <div class="detail-title">CPU Model</div>-->
            <!--                <div class="detail-value">{{ systemInfo.cpuModel }}</div>-->
            <!--            </div>-->
            <div class="detail-card" style="background-color: #FFFFFF">
                <div class="detail-title">Kernel Version</div>
                <div class="detail-value">{{ systemInfo.kernelVersion }}</div>
            </div>
            <!--            <div class="detail-card" style="background-color: #FFFFFF">-->
            <!--                <div class="detail-title">Last Updated</div>-->
            <!--                <div class="detail-value">{{ lastUpdated }}</div>-->
            <!--            </div>-->
        </div>
        <!-- ================= 🌐 Network Source Status ================= -->
        <div class="net-container">

            <header class="net-header">
                <h2>Network Source Status</h2>
                <p>Live overview of active & available network interfaces</p>
            </header>

            <div class="current-source">
                <span class="label">Current Active Source</span>
                <span class="badge" :class="sourceClass">
    {{ currentSourceLabel }}
</span>

            </div>

            <div class="net-grid">
                <div
                    class="net-card"
                    v-for="item in interfaces"
                    :key="item.key"
                    :class="{ active: item.active }"
                >
                    <div class="net-icon" v-html="item.icon"></div>

                    <div class="net-info">
                        <h3>{{ item.label }}</h3>
                        <p>
                            Status:
                            <strong :class="item.active ? 'text-on' : 'text-off'">
                                {{ item.active ? 'Available' : 'Unavailable' }}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>

<!--            <div class="ip-box" :class="sourceClass">-->
<!--                <span>{{ currentSourceLabel }} IP</span>-->
<!--                <strong>{{ activeIp }}</strong>-->
<!--            </div>-->
        </div>

        <div class="tag-container">
            <h3 class="section-title">
                <i class="fas fa-satellite-dish"></i>
                Tag Status
            </h3>

            <!-- If records exist -->
            <div v-if="tagStatusData.length > 0" class="tag-grid">
                <div v-for="tag in tagStatusData" :key="tag.id" class="tag-card">

                    <!-- Card Header -->
                    <div class="tag-header">
<!--                        <span class="uuid">ID: {{ tag.UUID }}</span>-->
                        <span class="status" :class="tag.RSSI > 50 ? 'online' : 'offline'">
                    <i class="fas" :class="tag.RSSI > 50 ? 'fa-signal' : 'fa-exclamation-triangle'"></i>
                    {{ tag.RSSI > 50 ? "Online" : "Weak Signal" }}
                </span>
                    </div>

                    <!-- RSSI -->
                    <div class="rssi">
                        <p>Signal Strength:</p>
                        <div class="rssi-bar">
                            <div class="rssi-fill" :style="{ width: Math.min(tag.RSSI, 100) + '%' }"></div>
                        </div>
                        <small class="rssi-value">Value: {{ tag.RSSI }}</small>
                    </div>

                    <!-- Nozzles -->
                    <div class="nozzles">
                        <p>Nozzles</p>
                        <div class="nozzle-grid">
                            <div
                                v-for="i in 6"
                                :key="i"
                                class="nozzle"
                                :class="tag[`Nozzel${i}`] ? 'active' : 'inactive disabled'"
                            >
                                <span>N{{ i }}</span>
                                <small>{{ tag[`Nozzel${i}`] ? "Active" : "Inactive" }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="tag-footer">
                        <i class="far fa-clock"></i> Last Ping: {{ formatDate(tag.last_updated) }}
                    </div>
                </div>
            </div>

            <!-- If no records -->
            <div v-else class="no-data">
                <i class="fas fa-info-circle"></i>
                <p>No tag status records found</p>
            </div>
        </div>

        <div class="action-card">
            <h2 class="action-title">System Controls</h2>
            <div class="action-buttons">
                <button
                    @click="openPopup('reset')"
                    class="action-btn reset"
                    :disabled="loading"
                >
                    🔄 Reset
                </button>

                <button
                    @click="openPopup('reboot')"
                    class="action-btn reboot"
                    :disabled="loading"
                >
                    🔁 Reboot
                </button>

                <button
                    @click="openPopup('serviceRestart')"
                    class="action-btn service-restart"
                    :disabled="loading"
                >
                    ♻ Service Restart
                </button>
            </div>

            <!-- ✅ Success & Error Message -->
            <div v-if="message" :class="['alert-box', messageType]">
                {{ message }}
            </div>
        </div>

        <!-- ✅ Password Popup -->
        <div v-if="showPopup" class="popup-overlay">
            <div class="popup-box">
                <h3>Enter Password</h3>
                <input
                    type="password"
                    v-model="popupPassword"
                    placeholder="Enter password"
                    class="popup-input"
                />

                <div class="popup-actions">
                    <button @click="confirmAction" class="popup-btn confirm">Confirm</button>
                    <button @click="closePopup" class="popup-btn cancel">Cancel</button>
                </div>
                <p v-if="popupError" class="popup-error">{{ popupError }}</p>
            </div>
        </div>
        <footer>
            <p>System Performance Dashboard • Monitoring active • Updated every 5 seconds</p>
            <p>© 2023 Enterprise Monitoring System v3.2</p>
        </footer>
    </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted,computed } from "vue";

export default {
    name: "PerformanceDashboard",
    setup() {
        const performance = ref({
            load: 0,
            memory: 0,
            memoryTotal: "0M",
            zram: 0,
            zramTotal: "0M",
            cpuTemp: 0,
            uptimeDays: 0,
            uptimeHours: 0,
            uptimeMinutes: 0,
            uptimeStability: 0,
            disk: 0,
            diskTotal: "0G",
            lanIp: "",
            wanIp: "",
        });

        const systemInfo = ref({
            cpuModel: "",
            kernelVersion: "",
            osVersion: "",
        });

        // ✅ State for buttons + popup
        const loading = ref(false);
        const message = ref("");
        const messageType = ref(""); // "success" | "error"
        const showPopup = ref(false);
        const popupPassword = ref("");
        const popupError = ref("");
        const currentAction = ref("");
        const tagStatusData = ref([]);
        const netSource = ref({});

        // ✅ Fetch system performance
        const fetchSysParams = async () => {
            try {
                const res = await axios.get("/api/sysparams");
                if (res.data.status === "success") {
                    const d = res.data.data;

                    performance.value = {
                        load: parseInt(d.cpuload),
                        memory: parseInt(d.ram_usage),
                        memoryTotal: "484M",
                        zram: 48,
                        zramTotal: "242M",
                        cpuTemp: parseInt(d.temp),
                        uptimeDays: d.uptime,
                        uptimeHours: 0,
                        uptimeMinutes: 0,
                        uptimeStability: 99,
                        disk: parseInt(d.rom_usage),
                        diskTotal: "7.0G",
                        lanIp: d.lan_ip,
                        wanIp: d.wan_ip,
                    };

                    systemInfo.value = {
                        cpuModel: "ARMv8 Processor",
                        kernelVersion: "5.15.0-91-generic",
                        osVersion: "Ubuntu 22.04 LTS",
                    };
                    netSource.value = d;
                }
            } catch (err) {
                console.error("API error:", err);
            }
        };
        const fetchTagStatus = async () => {
            try {
                const response = await axios.get("/tag_status");
                if (response.data.status === "success") {
                    tagStatusData.value = response.data.data;
                    // console.log("Tag Status Array:", tagStatusData.value);
                } else {
                    tagStatusData.value = [];
                }
            } catch (err) {
                console.error("Error fetching tag_status:", err);
                tagStatusData.value = [];
            }
        };
        const interfaces = computed(() => [
            {
                key: "4g",
                label: "4G / LTE",
                active: netSource.value["4g_data_available"] == 1,
                icon: `<svg viewBox="0 0 24 24"><path d="M12 2v20"/><path d="M5 15l7-7 7 7"/></svg>`
            },
            {
                key: "fixed",
                label: "Fixed Broadband",
                active: netSource.value.fixed_interface_available == 1,
                icon: `<svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="10" rx="2"/><path d="M3 12h18"/></svg>`
            },
            {
                key: "wireless",
                label: "Wireless",
                active: netSource.value.wireless_interface_available == 1,
                icon: `<svg viewBox="0 0 24 24"><path d="M5 12.5a11 11 0 0 1 14 0"/><path d="M8.5 16.5a6 6 0 0 1 7 0"/><circle cx="12" cy="20" r="1"/></svg>`
            },

        ]);

        const currentSourceLabel = computed(() => {
            switch (String(netSource.value.current_source)) {
                case "1": return "Lan";
                case "2": return "Wired";
                case "3": return "4G";
                default: return "Unknown";
            }
        });

        const sourceClass = computed(() => `src-${netSource.value.current_source}`);


        const activeIp = computed(() => {
            switch (String(netSource.value.current_source)) {
                case "1": return netSource.value.wan_ip || "N/A";
                case "2": return netSource.value.lan_ip || "N/A";
                case "3": return netSource.value.wireless_ip || "N/A";
                // case "4": return netSource.value.modem_ip || "N/A";
                default: return "N/A";
            }
        });

        const formatDate = (dateStr) => {
            if (!dateStr) return "-";
            return new Date(dateStr).toLocaleString();
        };

        // rest of your code (popup, confirmAction, fetchSysParams...)

        onMounted(() => {
            fetchSysParams();
            setInterval(fetchSysParams, 5000);

            fetchTagStatus();
            setInterval(fetchTagStatus, 10000); // auto refresh every 10s
        });

        // ✅ Open popup with action type
        const openPopup = (type) => {
            currentAction.value = type;
            popupPassword.value = "";
            popupError.value = "";
            showPopup.value = true;
        };

        // ✅ Close Popup
        const closePopup = () => {
            showPopup.value = false;
            popupPassword.value = "";
            popupError.value = "";
        };

        // ✅ Confirm password & call API
        const confirmAction = async () => {
            loading.value = true;
            popupError.value = ""; // reset error

            try {
                const actionUrlMap = {
                    reset: "/api/setup/reset",
                    reboot: "/api/setup/reboot",
                    serviceRestart: "/api/setup/service-restart",
                };
                const url = actionUrlMap[currentAction.value] || "/api/setup/reboot";

                const res = await axios.post(url, {
                    password: popupPassword.value
                });

                // ✅ Backend success case
                if (res.data.status === "success") {
                    message.value = res.data.message;
                    messageType.value = "success";
                    closePopup();
                } else {
                    // ❌ Password mismatch or failed case
                    popupError.value = res.data.message || "⚠️ Incorrect password";
                }
            } catch (err) {
                // ❌ API error (network/backend)
                popupError.value = err.response?.data?.message || "❌ Invalid password or server error";
            } finally {
                loading.value = false;
            }
        };


        // onMounted(() => {
        //     fetchSysParams();
        //     setInterval(fetchSysParams, 5000);
        // });

        // ✅ return sab kuch expose karna zaroori hai
        return {
            performance,
            systemInfo,
            loading,
            message,
            messageType,
            showPopup,
            popupPassword,
            popupError,
            currentAction,
            openPopup,
            closePopup,
            confirmAction,
            tagStatusData,
            fetchTagStatus,
            formatDate,
            netSource,               // ✅ ADD
            interfaces,
            currentSourceLabel,
            sourceClass,
            activeIp
        };
    },
};

</script>

<style scoped>
/* ✅ Buttons */
.action-card { background: #fff; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); margin-top: 30px;}
.action-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 15px; color: var(--primary);}
.action-buttons { display: flex; gap: 15px;justify-content: center;}
.action-btn { padding: 10px 20px; font-size: 1rem; font-weight: 600; border-radius: 8px; border: none; cursor: pointer; transition: all 0.3s ease;}
.action-btn.reset { background: #fbbf24; color: white;}
.action-btn.reset:hover { background: #f59e0b;}
.action-btn.reboot { background: #ef4444; color: white;}
.action-btn.reboot:hover { background: #dc2626;}
.action-btn.service-restart { background: #2563eb; color: white;}
.action-btn.service-restart:hover { background: #1d4ed8;}
.action-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.popup-overlay {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    z-index: 999;
}
.popup-box {
    background: #fff; padding: 20px;
    border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    width: 300px; text-align: center;
}
.popup-input {
    width: 100%; padding: 10px;
    margin: 15px 0; border: 1px solid #ccc;
    border-radius: 6px;
}
.popup-actions {
    display: flex; justify-content: space-between;
}
.popup-btn {
    flex: 1; margin: 5px; padding: 10px;
    border: none; border-radius: 6px;
    cursor: pointer; font-weight: bold;
}
.popup-btn.confirm { background: #28a745; color: white; }
.popup-btn.cancel { background: #dc3545; color: white; }
.popup-error {
    color: red; font-size: 0.9rem; margin-top: 10px;
}
.tag-container {
    padding: 30px;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 24px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 5px solid #3b82f6;
    padding-left: 12px;
}

.tag-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
}

.tag-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 16px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
}
.tag-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.08);
}

/* Header */
.tag-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    font-size: 14px;
}
.tag-header .uuid {
    font-weight: 600;
    color: #334155;
}
.tag-header .status {
    font-size: 13px;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}
.status.online {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #34d399;
}
.status.offline {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #f87171;
}

/* RSSI */
.rssi {
    margin-bottom: 18px;
    font-size: 14px;
    color: #374151;
}
.rssi-bar {
    background: #f1f5f9;
    border-radius: 10px;
    height: 10px;
    margin: 8px 0;
    overflow: hidden;
}
.rssi-fill {
    background: linear-gradient(90deg, #22c55e, #3b82f6);
    height: 100%;
    transition: width 0.5s ease-in-out;
    border-radius: 10px;
}
.rssi-value {
    font-size: 12px;
    color: #6b7280;
}

/* Nozzles */
.nozzles p {
    font-weight: 700;
    font-size: 15px;
    margin-bottom: 10px;
    color: #0f172a;
}
.nozzle-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.nozzle {
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    font-size: 13px;
    border: 1px solid #e2e8f0;
    background: #f9fafb;
    transition: 0.3s;
}
.nozzle span {
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}
.nozzle.active {
    background: #ecfdf5;
    border-color: #34d399;
    color: #065f46;
    box-shadow: 0 2px 6px rgba(16,185,129,0.15);
}
.nozzle.inactive {
    background: #fef2f2;
    border-color: #f87171;
    color: #7f1d1d;
}

/* Footer */
.tag-footer {
    margin-top: 16px;
    font-size: 13px;
    color: #475569;
    border-top: 1px dashed #e5e7eb;
    padding-top: 10px;
    text-align: right;
    font-style: italic;
}

/* Empty state */
.no-data {
    text-align: center;
    color: #6b7280;
    padding: 50px 0;
}
.no-data i {
    font-size: 26px;
    color: #9ca3af;
    margin-bottom: 10px;
}

.disabled {
    display: none;
}

/* ✅ Alerts */
.alert-box {
    margin-top: 15px;
    padding: 10px 15px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.95rem;
}
.success { background: #d1fae5; color: #065f46; border: 1px solid #34d399; }
.error { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
:root {
    --primary: #2a3f5f;
    --secondary: #4a6583;
    --accent: #4cc9f0;
    --success: #00b894;
    --warning: #fdcb6e;
    --danger: #ff7675;
    --dark: #1a2a3a;
    --light: #f8f9fa;
    --text: #333;
    --text-light: #6c757d;
    --border: #e0e0e0;
    --card-bg: #ffffff;
    --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
    color: var(--text);
    min-height: 100vh;
    padding: 20px;
    line-height: 1.6;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--card-bg);
    border-radius: 12px;
    box-shadow: var(--shadow);
}

.header-left h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 5px;
}

.header-left p {
    color: var(--text-light);
    font-size: 0.95rem;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.time-display {
    background: var(--primary);
    color: white;
    padding: 8px 15px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 184, 148, 0.1);
    color: var(--success);
    padding: 8px 15px;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
}

.status-badge i {
    font-size: 0.8rem;
}

.dashboard {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow);
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid var(--accent);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
}

.card.cpu {
    border-left:3px solid #ff6b6b !important;
}

.card.memory {
    border-left:3px solid #4ecdc4;
}

.card.storage {
    border-left:3px solid #a29bfe;
}

.card.uptime {
    border-left:3px solid #fd79a8;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.cpu .card-icon {
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
}

.memory .card-icon {
    background: linear-gradient(135deg, #4ecdc4, #00b894);
}

.storage .card-icon {
    background: linear-gradient(135deg, #a29bfe, #6c5ce7);
}

.uptime .card-icon {
    background: linear-gradient(135deg, #fd79a8, #e84393);
}

.metric-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 10px 0;
    color: var(--primary);
}

.metric-details {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 5px;
}

.progress-container {
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    margin: 15px 0;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease-in-out;
}

.cpu .progress-bar {
    background: linear-gradient(90deg, #ff6b6b, #ee5a24);
}

.memory .progress-bar {
    background: linear-gradient(90deg, #4ecdc4, #00b894);
}

.storage .progress-bar {
    background: linear-gradient(90deg, #a29bfe, #6c5ce7);
}

.uptime .progress-bar {
    background: linear-gradient(90deg, #fd79a8, #e84393);
}

.charts-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.chart-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary);
}

.chart-actions {
    display: flex;
    gap: 10px;
}

.chart-actions button {
    background: #f0f2f5;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.8rem;
    color: var(--text-light);
    cursor: pointer;
    transition: all 0.2s;
}

.chart-actions button.active {
    background: var(--primary);
    color: white;
}

.chart-actions button:hover {
    background: var(--secondary);
    color: white;
}

.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
}

.system-details {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.detail-card {
    background: var(--card-bg);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow);
}

.detail-title {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary);
}

.alert-banner {
    background: #fff9e6;
    border-left: 4px solid var(--warning);
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.alert-banner i {
    color: var(--warning);
    font-size: 1.2rem;
}

.alert-content h3 {
    font-size: 1rem;
    margin-bottom: 5px;
    color: #856404;
}

.alert-content p {
    font-size: 0.9rem;
    color: #856404;
}

footer {
    text-align: center;
    margin-top: 40px;
    padding: 20px;
    color: var(--text-light);
    font-size: 0.9rem;
    border-top: 1px solid var(--border);
}

@media (max-width: 1200px) {
    .dashboard, .system-details {
        grid-template-columns: repeat(2, 1fr);
    }

    .charts-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard, .system-details {
        grid-template-columns: 1fr;
    }

    header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .header-right {
        width: 100%;
        justify-content: space-between;
    }
}
/* ================= 🌐 Network Source ================= */

.net-container {
    margin-top: 40px;
    padding: 20px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
}

.net-header {
    margin-bottom: 15px;
}

.net-header h2 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #111827;
}

.net-header p {
    font-size: 0.9rem;
    color: #6b7280;
}

/* Current Source */
.current-source {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    border-radius: 10px;
    background: #f8fafc;
    margin-bottom: 18px;
}

.current-source .label {
    font-weight: 500;
    color: #374151;
}

.badge {
    padding: 6px 16px;
    font-size: 0.85rem;
    border-radius: 999px;
    color: #ffffff;
    font-weight: 500;
    background: #3b82f6;
}

/* Network Grid */
.net-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.net-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    transition: all 0.25s ease;
}

.net-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.net-card.active {
    border-color: #22c55e;
    background: #ecfdf5;
}

/* Icon */
.net-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.net-icon svg {
    width: 24px;
    height: 24px;
    stroke: #334155;
    stroke-width: 2;
    fill: none;
}

/* Info */
.net-info h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
}

.net-info p {
    margin: 4px 0 0;
    font-size: 0.85rem;
    color: #6b7280;
}

/* Status Colors */
.text-on {
    color: #16a34a;
}

.text-off {
    color: #dc2626;
}

/* IP Box */
.ip-box {
    margin-top: 18px;
    padding: 14px 16px;
    border-radius: 12px;
    background: #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ip-box span {
    font-size: 0.85rem;
    color: #475569;
}

.ip-box strong {
    font-size: 0.95rem;
    font-weight: 600;
    color: #0f172a;
}

/* Source Color States */
.src-1 .badge { background: #2563eb; } /* 4G */
.src-2 .badge { background: #0d9488; } /* Fixed */
.src-3 .badge { background: #7c3aed; } /* Wireless */
.src-4 .badge { background: #ea580c; } /* Modem */

/* Mobile */
@media (max-width: 640px) {
    .current-source {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .ip-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
}

</style>
