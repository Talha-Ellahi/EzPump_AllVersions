<template>
    <div class="router-wrapper">

        <!-- HEADER -->
        <div class="router-header">
            <h2>Router Settings</h2>
            <p>Wireless configuration & VPN information</p>
        </div>

        <!-- CARD -->
        <div class="router-card">
            <table class="router-table">
                <thead>
                <tr>
                    <th>Interface</th>
                    <th>SSID</th>
                    <th>VPN Server</th>
                    <th>VPN Gateway</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <span class="badge">{{ config.wireless_interface }}</span>
                    </td>
                    <td>{{ config.SSID || '—' }}</td>
                    <td>{{ config.VPN_Server || '—' }}</td>
                    <td>{{ config.VPN_GW || '—' }}</td>
                    <td style="float: left">
                        <button
                            v-if="canEdit"
                            class="btn-edit"
                            @click="openModal"
                        >
                            ✏ Edit
                        </button>
                        <span v-else class="disabled-text">Locked</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- MODAL -->
        <transition name="fade-scale">
            <div v-if="showModal" class="modal-backdrop">
                <div class="modal-box">
                    <h3>Wireless Configuration</h3>

                    <div class="form-group">
                        <label>SSID</label>
                        <input v-model="form.SSID" type="text" placeholder="Enter SSID" />
                    </div>

                    <div class="form-group">
                        <label>Security Key</label>
                        <input v-model="form.key" type="text" placeholder="Enter Key" />
                    </div>

                    <div class="modal-actions">
                        <button class="btn-cancel" @click="showModal=false">Cancel</button>
                        <button class="btn-save" @click="save">Save Changes</button>
                    </div>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted, computed } from "vue";

export default {
    name: "RouterSettingTab",
    setup() {
        const config = ref({});
        const sysparams = ref({});
        const showModal = ref(false);

        const form = ref({
            SSID: "",
            key: ""
        });

        const fetchData = async () => {
            const [cfg, sys] = await Promise.all([
                axios.get("/api/nwk-config"),
                axios.get("/api/sysparams")
            ]);

            if (cfg.data.status === "success") config.value = cfg.data.data;
            if (sys.data.status === "success") sysparams.value = sys.data.data;
        };

        const canEdit = computed(() =>
            sysparams.value.wireless_interface_available == 1
        );

        const openModal = () => {
            form.value.SSID = config.value.SSID;
            form.value.key = config.value.key;
            showModal.value = true;
        };

        const save = async () => {
            await axios.post("/api/nwk-config/update", form.value);
            showModal.value = false;
            fetchData();
        };

        onMounted(fetchData);

        return { config, form, showModal, canEdit, openModal, save };
    }
};
</script>

<style scoped>
/* LAYOUT */
.router-wrapper {
    background:#f1f5f9;
    padding:28px;
    border-radius:18px;
}

.router-header h2 {
    font-size:1.7rem;
    color:#0f172a;
}
.router-header p {
    color:#64748b;
    margin-top:4px;
}

/* CARD */
.router-card {
    margin-top:20px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    overflow:hidden;
}

/* TABLE */
.router-table {
    width:100%;
    border-collapse:collapse;
}
.router-table th {
    background:#f8fafc;
    padding:16px;
    font-size:.85rem;
    text-transform:uppercase;
    color: #ffffff;
}
.router-table td {
    padding:16px;
    border-top:1px solid #e5e7eb;
}
.router-table tbody tr:hover {
    background:#f1f5f9;
}

/* BADGE */
.badge {
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 10px;
    border-radius:999px;
    font-weight:600;
    font-size:.85rem;
}

/* BUTTONS */
.btn-edit {
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    padding:7px 16px;
    border-radius:999px;
    border:none;
    cursor:pointer;
    font-weight:600;
}
.btn-edit:hover {
    opacity:.9;
}

.disabled-text {
    color:#9ca3af;
    font-size:.9rem;
}

/* MODAL */
.modal-backdrop {
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.55);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:50;
}

.modal-box {
    background:#fff;
    padding:28px;
    width:380px;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.25);
}
.modal-box h3 {
    margin-bottom:18px;
    color:#0f172a;
}

.form-group {
    margin-bottom:16px;
}
.form-group label {
    font-size:.85rem;
    color:#475569;
}
.form-group input {
    width:100%;
    margin-top:6px;
    padding:11px;
    border-radius:10px;
    border:1px solid #cbd5f5;
}
.form-group input:focus {
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.15);
}

/* MODAL ACTIONS */
.modal-actions {
    display:flex;
    justify-content:flex-end;
    gap:12px;
}
.btn-save {
    background:#16a34a;
    color:#fff;
    padding:9px 20px;
    border-radius:10px;
    border:none;
}
.btn-cancel {
    background:#e5e7eb;
    padding:9px 20px;
    border-radius:10px;
    border:none;
}

/* ANIMATION */
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition:all .25s ease;
}
.fade-scale-enter-from,
.fade-scale-leave-to {
    opacity:0;
    transform:scale(.9);
}
</style>
