<template>
    <div class="net-container">

        <!-- HEADER -->
        <header class="net-header">
            <h2>Network Source Status</h2>
            <p>Live overview of active & available network interfaces</p>
        </header>

        <!-- CURRENT SOURCE -->
        <div class="current-source">
            <span class="label">Current Active Source</span>
            <span class="badge" :class="sourceClass">
                {{ currentSourceLabel }}
            </span>
        </div>

        <!-- INTERFACE CARDS -->
        <div class="net-grid">
            <div
                class="net-card"
                v-for="item in interfaces"
                :key="item.key"
                :class="{ active: item.active }"
            >
                <div
                    class="net-icon"
                    :class="item.active ? 'on' : 'off'"
                    v-html="item.icon"
                ></div>

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

        <!-- ACTIVE SOURCE IP -->
        <div class="ip-box single" :class="sourceClass">
            <div>
                <span>{{ currentSourceLabel }} IP</span>
                <strong>{{ activeIp }}</strong>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
import { ref, computed, onMounted } from "vue";

export default {
    name: "NetSourceTab",
    setup() {
        const data = ref({});

        const fetchNetSource = async () => {
            try {
                const res = await axios.get("/api/sysparams");
                if (res.data.status === "success") {
                    data.value = res.data.data;
                }
            } catch (e) {
                console.error("Network source fetch failed", e);
            }
        };

        /* SVG ICONS */
        const interfaces = computed(() => [
            {
                key: "4g",
                label: "4G / LTE",
                active: data.value["4g_data_available"] == 1,
                icon: `
                <svg viewBox="0 0 24 24">
                    <path d="M12 2v20"/>
                    <path d="M5 15l7-7 7 7"/>
                </svg>`
            },
            {
                key: "fixed",
                label: "Fixed Broadband",
                active: data.value.fixed_interface_available == 1,
                icon: `
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="7" width="18" height="10" rx="2"/>
                    <path d="M3 12h18"/>
                </svg>`
            },
            {
                key: "wireless",
                label: "Wireless",
                active: data.value.wireless_interface_available == 1,
                icon: `
                <svg viewBox="0 0 24 24">
                    <path d="M5 12.5a11 11 0 0 1 14 0"/>
                    <path d="M8.5 16.5a6 6 0 0 1 7 0"/>
                    <circle cx="12" cy="20" r="1"/>
                </svg>`
            },
            {
                key: "modem",
                label: "Modem",
                active: data.value.modem_interface_available == 1,
                icon: `
                <svg viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                    <path d="M12 8v8"/>
                </svg>`
            }
        ]);

        /* CURRENT SOURCE NAME */
        const currentSourceLabel = computed(() => {
            switch (String(data.value.current_source)) {
                case "1": return "4G / LTE";
                case "2": return "Fixed Broadband";
                case "3": return "Wireless";
                case "4": return "Modem";
                default: return "Unknown Source";
            }
        });

        const sourceClass = computed(() => `src-${data.value.current_source}`);

        /* ACTIVE IP */
        const activeIp = computed(() => {
            switch (String(data.value.current_source)) {
                case "1": return data.value.wan_ip || "N/A";
                case "2": return data.value.lan_ip || "N/A";
                case "3": return data.value.wireless_ip || "N/A";
                case "4": return data.value.modem_ip || "N/A";
                default: return "N/A";
            }
        });

        onMounted(() => {
            fetchNetSource();
            setInterval(fetchNetSource, 5000);
        });

        return {
            data,
            interfaces,
            currentSourceLabel,
            sourceClass,
            activeIp
        };
    }
};
</script>

<style scoped>
.net-container {
    background:#f8fafc;
    padding:30px;
    border-radius:18px;
}

/* HEADER */
.net-header h2 {
    font-size:1.8rem;
    color:#0f172a;
}
.net-header p {
    color:#64748b;
    margin-top:6px;
}

/* CURRENT SOURCE */
.current-source {
    margin:25px 0;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.badge {
    padding:8px 18px;
    border-radius:999px;
    font-weight:700;
}

/* SOURCE COLORS */
.src-1 { background:#dcfce7; color:#166534; }
.src-2 { background:#e0f2fe; color:#075985; }
.src-3 { background:#fef9c3; color:#854d0e; }
.src-4 { background:#fee2e2; color:#7f1d1d; }

/* GRID */
.net-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:22px;
}

/* CARD */
.net-card {
    background:#fff;
    padding:22px;
    border-radius:16px;
    display:flex;
    gap:18px;
    align-items:center;
    box-shadow:0 6px 16px rgba(0,0,0,.08);
    transition:.3s;
}
.net-card:hover { transform:translateY(-6px); }

.net-card.active {
    border:2px solid #22c55e;
    box-shadow:0 14px 30px rgba(34,197,94,.3);
}

/* ICON */
.net-icon {
    width:52px;
    height:52px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.net-icon svg {
    width:26px;
    height:26px;
    stroke-width:2;
    stroke:currentColor;
    fill:none;
}
.net-icon.on {
    background:#dcfce7;
    color:#16a34a;
}
.net-icon.off {
    background:#fee2e2;
    color:#dc2626;
}
.net-icon.on svg {
    animation:pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform:scale(1); }
    50% { transform:scale(1.15); }
    100% { transform:scale(1); }
}

/* TEXT */
.text-on { color:#16a34a; }
.text-off { color:#dc2626; }

/* IP BOX */
.ip-box.single {
    margin-top:35px;
}
.ip-box.single div {
    background:#fff;
    padding:26px;
    border-radius:16px;
    text-align:center;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
}
.ip-box.single strong {
    display:block;
    margin-top:10px;
    font-size:1.5rem;
}
</style>
