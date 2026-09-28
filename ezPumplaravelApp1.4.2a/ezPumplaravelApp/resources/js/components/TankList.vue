<template>
    <div class="tank-dashboard">



        <!-- ── DASHBOARD LAYOUT ── -->
        <div class="dashboard-layout">

            <!-- ── TANK LIST SECTION ── -->
            <div class="tank-list-section">
                <!-- ── TANK GRID ── -->

                <div class="tank-row"
                     :style="{ gridTemplateColumns: gridColumnsStyle }">
                    <div v-for="tank in tanks" :key="tank.id"
                         class="tank-card new_tanks_style"
                         :class="[
                            { 'tank-card--alarm': tank.last_alarm },
                            getCardAlarmClass(tank.last_alarm),
                            { 'tank-card--active': activeTankId === tank.id }
                         ]"
                         @click="selectTank(tank.id)">

                <!-- 🔔 Alarm Severity Label -->
                <div v-if="tank.last_alarm"
                     class="alarm-corner"
                     :class="getAlarmClass(tank.last_alarm)">
                    {{ tank.last_alarm }}
                </div>
                <!-- Top accent bar -->
                <div class="tank-card__bar" :class="getBarClass(tank.fuel_level_percent)"></div>

                <!-- Card Header -->
                <div class="tank-card__header ">
                    <div class="tank-name">
                        <span class="tank-icon">🛢</span>
                        <span>{{ tank.tank_name }}</span>
                    </div>
                    <div class="badge-row">
                        <span v-if="tank.alarms?.length" class="tbadge tbadge--alarm">
                            🚨 {{ tank.alarms.length }} Alarm{{ tank.alarms.length > 1 ? 's' : '' }}
                        </span>
                        <!--                        <span v-else class="tbadge tbadge&#45;&#45;ok">✓ Normal</span>-->
                        <span class="tbadge tbadge--type">{{ tank.fuel_type || '—' }}</span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="tank-card__body">
                    <div class="col-md-5 d-flex align-items-center justify-content-center">
                        <div class="rounded-circle position-relative overflow-hidden">
                  <span
                      class="fuel"
                      :class="getFuelLevelClass(tank.fuel_level_percent)"
                      :style="{ height: (tank.fuel_level_percent || 0) + '%' }"
                  ></span>
                            <span
                                class="fuel water"
                                :style="{ height: (tank.water_height_percentage || 0) + '%' }"
                            ></span>
                        </div>
                    </div>
                    <!-- SVG Circular Gauge -->
                    <!--                    <div class="gauge-wrap">-->
                    <!--                        <svg viewBox="0 0 120 120" class="gauge-svg">-->
                    <!--                            <circle class="g-track"  cx="60" cy="60" r="50" />-->
                    <!--                            <circle class="g-water"  cx="60" cy="60" r="50"-->
                    <!--                                    :stroke-dasharray="getArc(tank.water_height_percentage || 0)"-->
                    <!--                                    stroke-dashoffset="0"-->
                    <!--                                    transform="rotate(-90 60 60)" />-->
                    <!--                            <circle class="g-fuel"   cx="60" cy="60" r="50"-->
                    <!--                                    :class="getArcClass(tank.fuel_level_percent)"-->
                    <!--                                    :stroke-dasharray="getArc(tank.fuel_level_percent || 0)"-->
                    <!--                                    stroke-dashoffset="0"-->
                    <!--                                    transform="rotate(-90 60 60)" />-->
                    <!--                        </svg>-->
                    <!--                        <div class="gauge-center">-->
                    <!--                            <span class="gauge-pct" :class="getTextClass(tank.fuel_level_percent)">-->
                    <!--                                {{ Math.round(tank.fuel_level_percent || 0) }}<sup>%</sup>-->
                    <!--                            </span>-->
                    <!--                            <span class="gauge-lbl">Fuel</span>-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat">
                            <span class="stat-lbl">Stock</span>
                            <span class="stat-val">{{ tank.stock?.stock_value || '—' }}</span>
                        </div>
                        <div class="stat">
                            <span class="stat-lbl">Capacity</span>
                            <span class="stat-val">{{ formatNum(tank.capacity_liters) }} <em>L</em></span>
                        </div>

                        <div class="stat">
                            <span class="stat-lbl">Temperature</span>
                            <span class="stat-val">{{ tank.temperature || '—' }} <em>°C</em></span>
                        </div>
                        <div class="stat">
                            <span class="stat-lbl">Fuel mm</span>
                            <span class="stat-val">{{ tank.fuel_height_mm || 0 }} <em>mm</em></span>
                        </div>
                        <div class="stat">
                            <span class="stat-lbl">Water mm</span>
                            <span class="stat-val">{{ ((tank.water_height_mm || 0) / 100).toFixed(2) }} <em>mm</em></span>
                        </div>
                        <div class="stat stat--wide">
                            <span class="stat-lbl">Fuel Level</span>
                            <div class="mini-bar">
                                <div class="mini-bar__fill"
                                     :class="getBarClass(tank.fuel_level_percent)"
                                     :style="{ width: (tank.fuel_level_percent || 0) + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="tank-card__actions">
                    <button v-if="canAddStock" class="act-btn act-btn--success" @click.stop="addStock(tank.id)">
                        ＋ Add Stock
                    </button>
                    <button
                        v-if="showSwapButton(tank)"
                        class="act-btn act-btn--primary"
                        :class="{ 'btn-loading': !!tank.swapLoading }"
                        :disabled="!isSwapActionEnabled(tank) || !!tank.swapLoading"
                        @click.stop="handleSwapAction(tank)"
                    >
                        <span v-if="tank.swapLoading" class="loader"></span>
                        Swap
                    </button>
<!--                    <a v-if="canAddTank" :href="`/tank/edit-tank/${tank.id}`" class="act-btn act-btn&#45;&#45;primary" @click.stop>-->
<!--                        Edit-->
<!--                    </a>-->
                    <!-- START -->
                    <button v-if="sysMode === 3 && !tank.leakage_running"
                            class="act-btn act-btn--warning"
                            :class="{ 'btn-loading': tank.leakageLoading }"
                            :disabled="tank.leakageLoading"
                            @click.stop="startLeakage(tank)">
                        <span v-if="tank.leakageLoading" class="loader"></span>
                        Start Leakage
                    </button>

                    <!-- STOP -->
                    <button v-if="sysMode === 3 && tank.leakage_running"
                            class="act-btn act-btn--danger" style="background-color: blanchedalmond"
                            :class="{ 'btn-loading': tank.leakageLoading }"
                            :disabled="tank.leakageLoading"
                            @click.stop="stopLeakage(tank)">
                        <span v-if="tank.leakageLoading" class="loader"></span>
                        Stop Leakage
                    </button>
                </div>

                    </div>
                </div>
            </div> <!-- ── END TANK LIST SECTION ── -->

            <!-- ── GRAPH SECTION (Bottom) ── -->
<!--            <div class="graph-section"  style="margin-top: -28px;height: 60%" v-if="sysMode === 3">-->
            <div class="graph-section"  style="margin-top: -28px;height: 60%" v-if="sysMode === 3">
                <div v-for="tank in tanks" :key="'graph-'+tank.id"
                     v-show="activeTankId === tank.id" class="chart-section graph-panel">
                    <div class="chart-header">
                        <span class="chart-title">📈 {{ tank.tank_name }} — Fuel History</span>
                        <div class="chart-filter">

                            <select
                                v-model="tankFilters[tank.id].filterRange"
                                @change="loadChart(tank.id)"
                                class="filter-select"
                            >
                                <option value="today">Today</option>
                                <option value="7">Last 7 Days</option>
                                <option value="30">Last 30 Days</option>
                                <option value="custom">Custom</option>
                            </select>

                            <div v-if="tankFilters[tank.id].filterRange === 'custom'" class="date-inputs">
                                <div class="date-field">
                                    <label>Start</label>
                                    <input
                                        type="date"
                                        v-model="tankFilters[tank.id].startDate"
                                        class="filter-date"
                                        @change="loadChart(tank.id)"
                                    >
                                </div>
                                <div class="date-field">
                                    <label>End</label>
                                    <input
                                        type="date"
                                        v-model="tankFilters[tank.id].endDate"
                                        class="filter-date"
                                        @change="loadChart(tank.id)"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div :id="'chart-' + tank.id" class="chart-container"></div>
                </div>

                <div v-if="tanks.length === 0" class="no-tanks-msg">
                    No tanks available to display graph.
                </div>
            </div>
        </div>

        <!-- ── ALARM OVERLAY ── -->
        <!--        <transition name="fade">-->
        <!--            <div v-if="alarmActive && !alarmMuted" class="alarm-overlay" @click.self="muteAlarm">-->
        <!--                <div class="alarm-modal">-->
        <!--                    <div class="alarm-icon">🚨</div>-->
        <!--                    <h2 class="alarm-title">Fuel Level Alarm</h2>-->
        <!--                    <div class="alarm-body">-->
        <!--                        <div v-for="t in alarmTanks" :key="t.id" class="alarm-item">-->
        <!--                            <strong>{{ t.tank_name }}</strong>-->
        <!--                            <span>Level: {{ t.current_level }} &nbsp;|&nbsp; Threshold: {{ t.threshold }}</span>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <button class="alarm-btn" @click="muteAlarm">🔇 Silence Alarm</button>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </transition>-->


    </div>
</template>

<script>
import axios from "axios";
import ApexCharts from "apexcharts";

export default {
    data() {
        return {
            tanks: [],
            sysMode: null,
            charts: {},
            showGraph: {},
            liveInterval: null,
            alarmInterval: null,
            alarmActive: false,
            alarmMuted: false,
            alarmAudio: null,
            alarmTanks: [],
            tankFilters: {},
            chartData: [],
            activeTankId: null,
            alarmSettings: null,
            alarmTimer: null,
            beepTimer: null,
            isLeakageRunning: false,
            leakage_running: false,
        };
    },

    computed: {
        canAddTank()  { return window.user?.role === 0; },
        canAddStock() { return window.user?.role != null && window.user.role < 7; },
        gridColumnsStyle() {
            if (!this.tanks || this.tanks.length === 0) return 'repeat(1, 1fr)';
            if (this.sysMode === 3) {
                return `repeat(${Math.min(this.tanks.length, 4)}, 1fr)`;
            }
            return `repeat(${Math.min(this.tanks.length, 3)}, 1fr)`;
        },
    },

    mounted() {
        // ✅ KEY FIX: loadSysMode first → then getTanks inside it (sequential, not parallel)
        this.loadSysMode();
        this.startTimer();
        this.alarmAudio = new Audio("https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg");
        this.alarmAudio.loop = true;
        this.loadAlarmSettings();
    },

    beforeUnmount() {
        clearInterval(this.liveInterval);
        clearInterval(this.alarmInterval);
        Object.values(this.charts).forEach(c => c?.destroy());
    },

    methods: {

        getFuelLevelClass(level) {
            if (level === null || level === undefined) return "fuel-empty";
            if (level <= 20) return "fuel-critical";
            if (level <= 40) return "fuel-low";
            if (level <= 60) return "fuel-medium";
            if (level <= 80) return "fuel-good";
            return "fuel-full";
        },

        startTimer() {
            setInterval(() => {
                this.updateFuelStatus();
            }, 3000);
        },

        // ✅ FIX 1: sysMode loads FIRST, then getTanks so chart renders with correct mode
        async loadSysMode() {
            try {
                const res = await axios.get("/api/sys-mode");
                this.sysMode = res.data.mode;
            } catch (err) {
                console.error("sysMode load failed:", err);
                this.sysMode = null;
            } finally {
                // Always run getTanks after sysMode — success or fail
                await this.getTanks();
                await this.getLeakageStatus();

                if (this.sysMode === 2 && this.sysMode === 3 && this.sysMode === 1) {
                    this.startLiveUpdate();
                    this.startAlarmTimer();
                }
            }
        },

        async getTanks() {
            try {
                const res = await axios.get("/api/tanks");
                this.tanks = res.data.tanks || [];

                this.tanks.forEach(t => {
                    this.tankFilters[t.id] = {
                        filterRange: "today",
                        startDate: "",
                        endDate: ""
                    };
                });

                // Auto-detect first Premier tank, else first tank
                const firstPetrolTank = this.tanks.find(t => t.fuel_type === "Premier");
                this.activeTankId = firstPetrolTank
                    ? firstPetrolTank.id
                    : (this.tanks[0]?.id || null);

                // Mark which tank graph is visible
                this.tanks.forEach(t => {
                    this.showGraph[t.id] = (t.id === this.activeTankId);
                });

                // ✅ FIX 2: loadChart directly — no sysMode guard blocking it
                if (this.activeTankId) {
                    this.$nextTick(() => {
                        this.loadChart(this.activeTankId);
                    });
                }

                this.updateFuelStatus();

            } catch (err) {
                console.error("Failed to load tanks:", err);
            }
        },

        updateFuelStatus() {
            axios.get("/api/tanks/lastfeulstatus")
                .then((response) => {
                    const fuelStatus = response.data;
                    this.tanks.forEach((tank) => {
                        const status = fuelStatus.find((s) => s.TID === tank.id);
                        if (status) {
                            const fuelInLiters  = (status.Qty ?? 0) / 100;
                            const waterInLiters = (status.water_qty ?? 0) / 100;

                            if (tank.stock) {
                                tank.stock.stock_value = fuelInLiters;
                            } else {
                                tank.stock = { stock_value: fuelInLiters };
                            }

                            tank.temperature             = status.TEMP / 100;
                            tank.fuel_height_mm          = Number(status.level);
                            tank.fuel_level_percent      = tank.capacity_liters > 0
                                ? (fuelInLiters / tank.capacity_liters) * 100 : 0;
                            tank.water_height_mm         = Number(status.water_level ?? 0);
                            tank.water_height_percentage = (waterInLiters / tank.capacity_liters) * 100;
                        }
                    });
                })
                .catch(err => console.error("Error fetching fuel status:", err));
        },

        // ✅ FIX 3: Removed `if (this.sysMode !== 3) return` — chart works on ALL modes
        selectTank(tankId) {
            this.activeTankId = tankId;

            this.tanks.forEach(t => {
                this.showGraph[t.id] = (t.id === tankId);
            });

            this.$nextTick(() => {
                if (!this.charts[tankId]) {
                    this.loadChart(tankId);
                } else {
                    this.charts[tankId].resize?.();
                }
            });
        },

        toggleGraph(tankId) { this.selectTank(tankId); },
        openGraph(tankId)   { this.selectTank(tankId); },

        async loadChart(tankId) {
            // Wait for Vue to render the container DOM element
            await this.$nextTick();

            const container = document.querySelector(`#chart-${tankId}`);
            if (!container) {
                console.warn(`#chart-${tankId} container not found in DOM`);
                return;
            }

            try {
                if (this.charts[tankId]) {
                    this.charts[tankId].destroy();
                    delete this.charts[tankId];
                }

                const tankFilter = this.tankFilters[tankId] || {};
                const range = tankFilter.filterRange || "today";
                const start = tankFilter.startDate   || "";
                const end   = tankFilter.endDate     || "";

                console.log(`📊 loadChart → tank:${tankId}`, { range, start, end });

                const res = await axios.get(
                    `/api/atg/tanks/${tankId}/chart-data`,
                    { params: { range, start, end } }
                );

                const {
                    timestamps   = [],
                    fuelLevels   = [],
                    waterLevels  = [],
                    refuelEvents = []
                } = res.data;

                const toMs     = t => Number(t) < 1e12 ? Number(t) * 1000 : Number(t);
                const tank     = this.tanks.find(t => t.id === tankId);
                const tankName = tank?.tank_name || `Tank ${tankId}`;

                const options = {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: { show: false },
                        animations: { enabled: false },
                    },
                    title: {
                        text: tankName,
                        align: 'left',
                        style: { fontSize: '14px', fontWeight: '600', color: '#374151' },
                    },
                    series: [
                        {
                            name: 'Fuel Level',
                            data: timestamps.map((t, i) => ({ x: toMs(t), y: fuelLevels[i] ?? 0 })),
                        },
                        // {
                        //     name: 'dip',
                        //     data: timestamps.map((t, i) => ({ x: toMs(t), y: waterLevels[i] ?? 0 })),
                        // },
                    ],
                    colors: ['#3B82F6', '#10B981'],
                    stroke: { curve: 'smooth', width: 3 },
                    xaxis: { type: 'datetime' },
                    yaxis: { title: { text: 'Level (mm)' } },
                    annotations: {
                        points: refuelEvents.map(event => ({
                            x: toMs(event.timestamp),
                            y: event.fuelLevel,
                            marker: { size: 6, fillColor: '#FF0000' },
                            label: { text: '⛽', offsetY: -10, style: { color: '#FF0000', fontSize: '11px' } },
                        })),
                    },
                    tooltip: { shared: true },
                    legend: { show: true },
                };

                const chart = new ApexCharts(container, options);
                await chart.render();
                this.charts[tankId] = chart;

                console.log(`✅ Chart rendered: ${tankName} (id:${tankId})`);

            } catch (error) {
                if (container) container.innerHTML = "<div class='chart-error'>Graph Failed</div>";
                console.error("Chart Load Error:", error);
            }
        },

        async updateChartData(tankId) {
            const chart = this.charts[tankId];
            if (!chart) return;

            try {
                const res = await axios.get(
                    `/api/atg/tanks/${tankId}/latest-chart-data`,
                    {
                        params: {
                            range: this.tankFilters[tankId]?.filterRange,
                            start: this.tankFilters[tankId]?.startDate,
                            end:   this.tankFilters[tankId]?.endDate,
                        },
                    }
                );

                const { timestamps = [], fuelLevels = [], waterLevels = [], refuelEvents = [] } = res.data;
                const toMs = t => Number(t) < 1e12 ? Number(t) * 1000 : Number(t);

                // ✅ Series names match loadChart exactly
                chart.updateSeries([
                    {
                        name: 'Fuel Level',
                        data: timestamps.map((t, i) => ({ x: toMs(t), y: fuelLevels[i] ?? 0 })),
                    },
                    // {
                    //     name: 'dip',
                    //     data: timestamps.map((t, i) => ({ x: toMs(t), y: waterLevels?.[i] ?? 0 })),
                    // },
                ], false);

                chart.clearAnnotations();
                refuelEvents.forEach(event => {
                    chart.addPointAnnotation({
                        x: toMs(event.timestamp),
                        y: event.fuelLevel,
                        toolbar: { show: false },
                        marker: { size: 6, fillColor: '#FF6B6B' },
                        label: { text: '⛽', offsetY: -10, style: { color: '#FF6B6B', fontSize: '11px' } },
                    });
                });

            } catch (error) {
                console.warn("Live chart update failed:", error);
            }
        },

        startLiveUpdate() {
            if (this.liveInterval) return;
            this.liveInterval = setInterval(() => {
                if (this.activeTankId && this.charts[this.activeTankId]) {
                    this.updateChartData(this.activeTankId);
                }
            }, 7000);
        },

        startAlarmTimer() {
            if (this.alarmInterval) return;
            this.alarmInterval = setInterval(() => {
                if (this.sysMode === 2&& this.sysMode=== 3 && this.sysMode === 1) this.checkTankAlarm();
            }, 4000);
        },

        async loadAlarmSettings() {
            try {
                const res = await axios.get("/api/atg/alarm-settings");
                this.alarmSettings = res.data;
            } catch (e) {
                console.error("Failed to load alarm settings");
            }
        },

        async startLeakage(tank) {
            tank.leakageLoading = true;
            try {
                await axios.post('/api/atg/leakage/start', { tank_id: tank.id });
                tank.leakage_running = true;
                Swal.fire({ icon: 'success', title: 'Leakage Started', text: `Leakage test for ${tank.tank_name} has started.`, timer: 2000, showConfirmButton: false });
            } catch (error) {
                console.error('Failed to start leakage:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: `Failed to start leakage test for ${tank.tank_name}.` });
            } finally {
                tank.leakageLoading = false;
            }
        },

        async stopLeakage(tank) {
            tank.leakageLoading = true;
            try {
                await axios.post('/api/atg/leakage/stop', { tank_id: tank.id });
                tank.leakage_running = false;
                Swal.fire({ icon: 'success', title: 'Leakage Stopped', text: `Leakage test for ${tank.tank_name} has stopped.`, timer: 2000, showConfirmButton: false });
            } catch (error) {
                console.error('Failed to stop leakage:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: `Failed to stop leakage test for ${tank.tank_name}.` });
            } finally {
                tank.leakageLoading = false;
            }
        },

        async getLeakageStatus() {
            try {
                const res = await axios.get('/api/atg/leakage/status');
                const runningTanks = res.data;
                this.tanks.forEach(tank => {
                    tank.leakage_running = runningTanks.includes(tank.id);
                });
            } catch (err) {
                console.error('getLeakageStatus failed:', err);
            }
        },

        async checkTankAlarm() {
            try {
                const res = await axios.get("/api/tanks-with-alarm");
                if (res.data.length > 0) {
                    this.alarmTanks  = res.data;
                    this.alarmActive = true;
                    this.tanks.forEach(tank => {
                        const found = res.data.find(a => a.id === tank.id);
                        tank.last_alarm = found ? found.last_alarm : null;
                    });
                    if (this.alarmSettings?.alarm_display_time) {
                        clearTimeout(this.alarmTimer);
                        this.alarmTimer = setTimeout(() => { this.alarmActive = false; }, this.alarmSettings.alarm_display_time * 1000);
                    }
                    if (!this.alarmMuted) this.alarmAudio?.play().catch(() => {});
                } else {
                    this.alarmActive = false;
                    this.alarmMuted  = false;
                    this.alarmTanks  = [];
                    this.tanks.forEach(t => t.last_alarm = null);
                    if (this.alarmAudio) { this.alarmAudio.pause(); this.alarmAudio.currentTime = 0; }
                }
            } catch (err) {
                console.error("Alarm check failed:", err);
            }
        },

        getAlarmClass(type) {
            if (!type) return '';
            const t = type.toLowerCase();
            if (t === 'low' || t === 'high') return 'alarm-warning';
            if (t === 'low low' || t === 'critical_high') return 'alarm-danger';
            return '';
        },

        getCardAlarmClass(type) {
            if (!type) return '';
            if (type.toLowerCase() === 'low low' || type.toLowerCase() === 'critical_high') return 'tank-critical';
            return '';
        },

        muteAlarm() {
            this.alarmMuted = true;
            if (this.alarmAudio) { this.alarmAudio.pause(); this.alarmAudio.currentTime = 0; }
        },

        getArc(pct) {
            const circ = 2 * Math.PI * 50;
            return `${(Math.min(Math.max(pct || 0, 0), 100) / 100) * circ} ${circ}`;
        },

        getBarClass(level) {
            if (!level || level <= 0) return 'lv-empty';
            if (level <= 20) return 'lv-danger';
            if (level <= 40) return 'lv-warning';
            if (level <= 60) return 'lv-info';
            if (level <= 80) return 'lv-primary';
            return 'lv-success';
        },

        getArcClass(level) {
            if (!level || level <= 0) return 'arc-empty';
            if (level <= 20) return 'arc-danger';
            if (level <= 40) return 'arc-warning';
            if (level <= 60) return 'arc-info';
            if (level <= 80) return 'arc-primary';
            return 'arc-success';
        },

        getTextClass(level) {
            if (!level || level <= 0) return 'tx-empty';
            if (level <= 20) return 'tx-danger';
            if (level <= 40) return 'tx-warning';
            if (level <= 60) return 'tx-info';
            if (level <= 80) return 'tx-primary';
            return 'tx-success';
        },

        formatNum(n) { return n ? Number(n).toLocaleString() : '0'; },

        showSwapButton(tank) {
            return Number(tank?.is_active) === 1 && Number(tank?.is_swapped) === 1;
        },

        isSwapActionEnabled(tank) {
            return Number(tank?.is_active) === 1 && Number(tank?.is_swapped) === 1;
        },

        async handleSwapAction(tank) {
            if (!this.isSwapActionEnabled(tank) || tank.swapLoading) return;

            const result = await Swal.fire({
                title: "Confirm Swap",
                text: `Swap ${tank.tank_name}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Swap",
                cancelButtonText: "Cancel",
            });

            if (!result.isConfirmed) return;

            tank.swapLoading = true;
            try {
                await axios.post("/api/tanks/swap", { tank_id: tank.id });
                Swal.fire({
                    icon: "success",
                    title: "Swapped",
                    text: `${tank.tank_name} swapped successfully.`,
                    timer: 1800,
                    showConfirmButton: false,
                });
                await this.getTanks();
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error?.response?.data?.message || "Swap action failed.",
                });
            } finally {
                tank.swapLoading = false;
            }
        },

        addStock(tankId) {
            const swalHtml = `
    <style>.req { color: #dc2626; font-weight: 700; margin-left: 2px; }</style>
    <div class="swal-grid">
      <div><label>Invoice Date:</label><input type="date" id="invoice_date" class="swal2-input" /></div>
      <div><label>Vendor: <span class="req">*</span></label>
        <select id="vendor_id" class="form-select swal2-input"><option value="">Select Vendor</option></select>
      </div>
      <div><label>Vehicle Number:</label><input type="text" id="vehicle_no" class="swal2-input" /></div>
      <div><label>Invoice Number:</label><input type="text" id="invoice_no" class="swal2-input" /></div>
      <div><label>Delivery / SAP No:</label><input type="text" id="delivery_or_sap_no" class="swal2-input" /></div>
      <div><label>Stock Change (Liters): <span class="req">*</span></label><input type="number" id="stock_change" class="swal2-input" /></div>
      <div><label>Driver Name:</label><input type="text" id="driver_name" class="swal2-input" /></div>
      <div><label>Driver Cell:</label><input type="text" id="driver_cell" class="swal2-input" /></div>
      <div><label>Chmb:</label>
        <select id="chemb" class="form-select swal2-input">
          <option value="">Select</option>
          <option value="1">Chmb1</option><option value="2">Chmb2</option>
          <option value="3">Chmb3</option><option value="4">Chmb4</option>
          <option value="5">Chmb5</option><option value="6">Chmb6</option>
        </select>
      </div>
      <div><label>Chemb Filling Dip:</label><input type="number" id="chemb_filling_dip" class="swal2-input" /></div>
      <div><label>Chemb Decanting Dip:</label><input type="number" id="chemb_decanting_dip" class="swal2-input" /></div>
      <div><label>Seal No:</label><input type="text" id="seal_no" class="swal2-input" /></div>
      <div><label>Filling Temp:</label><input type="text" id="filling_tmp" class="swal2-input" /></div>
      <div><label>Decanting Temp:</label><input type="text" id="decanting_tmp" class="swal2-input" /></div>
      <div><label>Purchase Price: <span class="req">*</span></label><input type="number" id="net_amount" class="swal2-input" /></div>
      <div style="grid-column: span 2;"><label>Comments:</label><textarea id="comments" class="swal2-textarea"></textarea></div>
    </div>`;

            Swal.fire({
                title: "Add Stock", html: swalHtml, showCancelButton: true,
                confirmButtonText: "Submit", width: "700px",
                didOpen: async () => {
                    const grid = document.querySelector(".swal-grid");
                    grid.style.cssText = "display:grid; grid-template-columns:1fr 1fr; gap:15px;";
                    try {
                        const res = await axios.get("/api/tanks");
                        const vendorSelect = document.getElementById("vendor_id");
                        res.data.vendors.forEach(v => {
                            const opt = document.createElement("option");
                            opt.value = v.VENDOR_ID; opt.textContent = v.VNAME;
                            vendorSelect.appendChild(opt);
                        });
                    } catch (e) { Swal.fire("Error", "Vendor list load nahi hui", "error"); }
                },
                preConfirm: () => {
                    ["vendor_id", "stock_change", "net_amount"].forEach(id => {
                        document.getElementById(id).style.border = "";
                    });
                    const g = id => document.getElementById(id).value;
                    const payload = {
                        invoice_date: g("invoice_date"), vendor_id: g("vendor_id"),
                        vehicle_no: g("vehicle_no"), invoice_no: g("invoice_no"),
                        delivery_or_sap_no: g("delivery_or_sap_no"), stock_change: g("stock_change"),
                        driver_name: g("driver_name"), driver_cell: g("driver_cell"),
                        chemb: g("chemb"), chemb_filling_dip: g("chemb_filling_dip"),
                        chemb_decanting_dip: g("chemb_decanting_dip"), seal_no: g("seal_no"),
                        filling_tmp: g("filling_tmp"), decanting_tmp: g("decanting_tmp"),
                        net_amount: g("net_amount"), comments: g("comments"), millimeter: 0,
                    };
                    if (!payload.vendor_id) { document.getElementById("vendor_id").style.border = "1px solid #dc2626"; Swal.showValidationMessage("Vendor required"); return false; }
                    if (!payload.stock_change) { document.getElementById("stock_change").style.border = "1px solid #dc2626"; Swal.showValidationMessage("Stock change required"); return false; }
                    if (!payload.net_amount) { document.getElementById("net_amount").style.border = "1px solid #dc2626"; Swal.showValidationMessage("Net Amount required"); return false; }
                    return axios.post(`/api/tanks/${tankId}/add-stock`, payload)
                        .then(() => Swal.fire("Success", "Stock added successfully", "success"))
                        .catch(err => Swal.fire("Error", err.response?.data?.message || "Something went wrong", "error"));
                },
            });
        },
    },
};
</script>

<style scoped>
/* ================================
   FONT
================================ */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

*, *::before, *::after {
    box-sizing: border-box;
}

/* ================================
   ROOT
================================ */
.tank-dashboard {
    font-family: 'Inter', system-ui, sans-serif;
    background: #f4f6fa;
    min-height: 100vh;
    padding: 2px;
    color: #1e293b;
}

/* ================================
   HEADER (Clean Modern)
================================ */
.dash-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    border-radius: 18px;
    padding: 18px 24px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.04);
    border: 1px solid #edf2f7;
    margin-bottom: 24px;
}

/* ================================
   LAYOUT
================================ */
.dashboard-layout {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

.tank-list-section {
    width: 100%;
}

.graph-section {
    width: 100%;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    border: 1px solid #edf2f7;
    margin-bottom: 24px;
}

.chart-section {
    border-top: none;
}

.no-tanks-msg {
    padding: 40px;
    text-align: center;
    color: #64748b;
    font-size: 14px;
}

/* ================================
   TANK ROW
================================ */
.tank-row {
    display: grid;
    gap: 4px;
    overflow-x: hidden;
    padding-bottom: 10px;
}
.tank-row.mode3{
    grid-template-columns: repeat(4, 1fr);
}

/* sysMode = 1 or 2 → 3 columns */

.tank-row.modeOther{
    grid-template-columns: repeat(3, 1fr);
}

.tank-row::-webkit-scrollbar {
    height: 6px;
}
.tank-row::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
@media (max-width:1200px){

    .tank-row.mode3{
        grid-template-columns: repeat(3,1fr);
    }

    .tank-row.modeOther{
        grid-template-columns: repeat(2,1fr);
    }

}

@media (max-width:768px){

    .tank-row{
        grid-template-columns: 1fr;
    }

}
/* ================================
   TANK CARD (Modern Upgrade)
================================ */
.tank-card {
    background: #ffffff;
    border-radius: 20px;
    width: 100%; /* Important for grid auto sizing */
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    border: 1px solid #edf2f7;
    margin-bottom: 5px;
    gap: 4px;
    cursor: pointer;
    height: 95%;
}

.tank-card.tank-card--active {
    border: 2px solid #3b82f6;
    box-shadow: 0 12px 32px rgba(59, 130, 246, 0.15);
}

.tank-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 32px rgba(0,0,0,0.08);
}

.tank-card--alarm {
    border: 2px solid #f87171;
}

/* Top Color Bar */
.tank-card__bar {
    height: 6px;
    width: 100%;
}

/* Level Colors */
.lv-empty { background: #94a3b8; }
.lv-danger { background: #ef4444; }
.lv-warning { background: #facc15; }
.lv-info { background: #0ea5e9; }
.lv-primary { background: #3b82f6; }
.lv-success { background: #22c55e; }

/* ================================
   CARD HEADER
================================ */
.tank-card__header {
    padding: 8px 18px 8px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.tank-name {
    font-weight: 700;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.badge-row {
    display: flex;
    gap: 6px;
}

/* Badges */
.tbadge {
    font-size: 10px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.tbadge--alarm {
    background: #fee2e2;
    color: #b91c1c;
}

.tbadge--ok {
    background: #dcfce7;
    color: #166534;
}

.tbadge--type {
    background: #dbeafe;
    color: #1e40af;
}

/* ================================
   CARD BODY
================================ */
.tank-card__body {
    padding: 5px 18px;
    display: flex;
    gap: 18px;
}

/* ================================
   GAUGE
================================ */
.gauge-wrap {
    position: relative;
    width: 110px;
    height: 110px;
}

.gauge-svg {
    width: 110px;
    height: 110px;
    transform: scaleX(-1);
}

.g-track {
    fill: none;
    stroke: #e2e8f0;
    stroke-width: 10;
}

.g-fuel {
    fill: none;
    stroke-width: 10;
    stroke-linecap: round;
    transition: stroke-dasharray 0.6s ease;
}

.arc-danger { stroke: #ef4444; }
.arc-warning { stroke: #facc15; }
.arc-success { stroke: #22c55e; }
.arc-primary { stroke: #3b82f6; }

.gauge-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.gauge-pct {
    font-size: 22px;
    font-weight: 700;
}

.gauge-lbl {
    font-size: 10px;
    color: #64748b;
}

/* ================================
   STATS GRID
================================ */
.stats-grid {
    flex: 1;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 12px;
}

.stat {
    display: flex;
    flex-direction: column;
}

.stat-lbl {
    font-size: 9px;
    text-transform: uppercase;
    color: #94a3b8;
}

.stat-val {
    font-size: 12.5px;
    font-weight: 600;
}

/* ================================
   MINI BAR
================================ */
.mini-bar {
    height: 6px;
    background: #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}

.mini-bar__fill {
    height: 100%;
    transition: width 0.6s ease;
}

/* ================================
   CARD ACTIONS
================================ */
.tank-card__actions {
    display: flex;
    gap: 8px;
    padding: 5px 18px 18px;
    margin-top: auto;
}

.act-btn {
    flex: 1;
    text-align: center;
    font-size: 11px;
    font-weight: 600;
    padding: 7px 0;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.act-btn--success {
    background: #dcfce7;
    color: #166534;
}

.act-btn--primary {
    background: #dbeafe;
    color: #1e40af;
}

.act-btn--success:hover {
    background: #bbf7d0;
}

.act-btn--primary:hover {
    background: #bfdbfe;
}

/* ================================
   CHART SECTION
================================ */
.chart-section {
    border-top: 1px solid #e2e8f0;
}

.chart-header {
    padding: 4px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.chart-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 6px;
}

.chart-filter {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.filter-select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    background: #ffffff;
    cursor: pointer;
    outline: none;
}
.filter-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.date-inputs {
    display: flex;
    align-items: center;
    gap: 10px;
}

.date-field {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 2px 8px;
}

.date-field label {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
    margin: 0;
}

.filter-date {
    border: none;
    font-size: 12px;
    color: #475569;
    padding: 4px 0;
    outline: none;
    background: transparent;
}

.chart-container {
    padding: 4px;
}

.chart-error {
    color: red;
    font-size: 12px;
    text-align: center;
}

/* ================================
   ANIMATIONS
================================ */
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.3s ease;
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

/* Tank Circle */
.tank-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    background: #f1f1f1;
    border: 3px solid #ddd;
}

/* Common Liquid Style */
.tank-liquid {
    position: absolute;
    bottom: 0;
    width: 100%;
    transition: height 0.5s ease;
}

/* Fuel Colors */
.fuel.fuel-critical {
    background: #f44;
}

.fuel.fuel-low {
    background: #ffa726;
}

.fuel.fuel-medium {
    background: #ffeb3b;
}

.fuel.fuel-good {
    background: #66bb6a;
}

.fuel.fuel-full {
    background: #00c853;
}

/* Water */
.water {
    background: #2196f3;
    opacity: 0.6;
}
/* ================================
   ALARM CORNER LABEL
================================ */
.tank-card {
    position: relative;
}

.alarm-corner {
    position: absolute;
    top: 15px;
    right: 92px;
    padding: 4px 10px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 20px;
    /*background: rgba(0, 0, 0, 0.86);*/
    background: #facc15;
    backdrop-filter: blur(6px);
    color: #ffffff;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Yellow - LOW / HIGH */
.alarm-warning {
    color: #ffffff;
}

/* Red - LOW LOW / HIGH HIGH */
.alarm-danger {
    color: #ef4444;
    animation: blink 1s infinite;
}

/* Critical Tank Border */
.tank-critical {
    border: 2px solid #ef4444 !important;
    box-shadow: 0 0 14px rgba(239, 68, 68, 0.5);
}

/* Blink Animation */
@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
}
@keyframes alarmFloat {
    0%   { transform: translateY(0px); }
    25%  { transform: translateY(-4px); }
    50%  { transform: translateY(0px); }
    75%  { transform: translateY(4px); }
    100% { transform: translateY(0px); }
}
/* ================================
   RESPONSIVE LAYOUT
================================ */

/* Tablet */
@media (max-width: 1100px){

    .tank-row{
        display:grid;
        grid-template-columns: repeat(2,1fr);
        overflow-x: hidden;
    }

    .tank-card{
        width:100%;
    }

}


/* Mobile */
@media (max-width: 650px){

    .tank-dashboard{
        padding:14px;
    }

    .dash-header{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .tank-row{
        display:grid;
        grid-template-columns: 1fr;
        gap:16px;
        overflow-x:hidden;
    }

    .tank-card{
        width:100%;
    }

    .tank-card__body{
        flex-direction:column;
        align-items:center;
    }

    .stats-grid{
        width:100%;
    }

    .gauge-wrap{
        width:95px;
        height:95px;
    }

    .gauge-svg{
        width:95px;
        height:95px;
    }

}


/* Small Mobile */
@media (max-width:420px){

    .tank-dashboard{
        padding:10px;
    }

    .tank-card{
        border-radius:16px;
    }

    .tank-card__header{
        padding:12px;
    }

    .tank-card__body{
        padding:10px;
        gap:12px;
    }

    .tank-card__actions{
        padding:10px;
    }

    .stat-val{
        font-size:11px;
    }

}
/* Button with loading state */
.act-btn.btn-loading {
    opacity: 0.7;
    cursor: not-allowed;
    pointer-events: none;
    position: relative;
}

/* Loader animation */
.loader {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    border-top-color: currentColor;
    animation: spin 0.6s linear infinite;
    margin-right: 6px;
    vertical-align: middle;
}

/* Color-specific loaders */
.act-btn--success .loader {
    border: 2px solid rgb(18, 18, 18);
    border-top-color: #166534;
}

.act-btn--info .loader {
    border: 2px solid rgba(30, 64, 175, 0.2);
    border-top-color: #1e40af;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Optional: Add a pulse effect while loading */
.act-btn.btn-loading {
    position: relative;
    overflow: hidden;
}

.act-btn.btn-loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(0, 0, 0, 0.93),
        transparent
    );
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
</style>
