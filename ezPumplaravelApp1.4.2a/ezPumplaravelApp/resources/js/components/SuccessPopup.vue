<!--<template>-->
<!--    <div v-if="show" class="modal-container">-->
<!--        <div class="modal-content">-->
<!--            &lt;!&ndash; Header &ndash;&gt;-->
<!--            <div class="modal-header">-->
<!--                <h5 class="modal-title">{{ title || 'Success' }}</h5>-->
<!--                <button type="button" class="btn-close" @click="close" aria-label="Close"></button>-->
<!--            </div>-->

<!--            &lt;!&ndash; Body &ndash;&gt;-->
<!--            <div class="modal-body text-center">-->
<!--                <p>{{ message || 'Shift closed successfully.' }}</p>-->
<!--            </div>-->

<!--            &lt;!&ndash; Footer &ndash;&gt;-->
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-secondary" @click="close">-->
<!--                    Close-->
<!--                </button>-->
<!--                <button type="button" class="btn btn-primary" @click="primaryAction">-->
<!--                    {{ primaryButtonText || 'View Shift Logs' }}-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script setup>-->
<!--const props = defineProps({-->
<!--    show: Boolean,-->
<!--    title: String,-->
<!--    message: String,-->
<!--    primaryButtonText: String-->
<!--})-->
<!--const emit = defineEmits(["update:show", "primaryAction"])-->

<!--const close = () => {-->
<!--    emit("update:show", false)-->
<!--}-->
<!--const primaryAction = () => {-->
<!--    emit("primaryAction")-->
<!--}-->
<!--</script>-->

<!--<style scoped>-->
<!--.modal-container {-->
<!--    position: fixed;-->
<!--    top: 0;-->
<!--    left: 0;-->
<!--    width: 100%;-->
<!--    height: 100%;-->
<!--    background-color: rgba(0, 0, 0, 0.6);-->
<!--    display: flex;-->
<!--    justify-content: center;-->
<!--    align-items: center;-->
<!--    z-index: 1050;-->
<!--}-->

<!--.modal-content {-->
<!--    background-color: white;-->
<!--    padding: 0;-->
<!--    border-radius: 8px;-->
<!--    width: 90%;-->
<!--    max-width: 500px;-->
<!--    box-shadow: 0 5px 15px rgba(0,0,0,0.3);-->
<!--}-->
<!--.modal-header {-->
<!--    padding: 1rem 1.5rem;-->
<!--    border-bottom: 1px solid #dee2e6;-->
<!--    display: flex;-->
<!--    justify-content: space-between;-->
<!--    align-items: center;-->
<!--}-->
<!--.modal-body {-->
<!--    padding: 1.5rem;-->
<!--}-->
<!--.modal-footer {-->
<!--    padding: 1rem 1.5rem;-->
<!--    border-top: 1px solid #dee2e6;-->
<!--    display: flex;-->
<!--    justify-content: flex-end;-->
<!--    gap: 0.5rem;-->
<!--}-->
<!--</style>-->
<template>
    <div v-if="show" class="modal-container">
        <div class="modal-content">
            <!-- Header -->
            <div
                class="modal-header"
                :class="type === 'error' ? 'bg-danger text-white' : 'bg-success text-white'"
            >
                <h5 class="modal-title">
                    {{ title || (type === 'error' ? 'Error' : 'Success') }}
                </h5>
                <button type="button" class="btn-close" @click="close" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body text-center">
                <p>{{ message || (type === 'error' ? 'Something went wrong!' : 'Operation successful.') }}</p>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close">
                    Close
                </button>
                <button
                    v-if="type !== 'error'"
                    type="button"
                    class="btn btn-primary"
                    @click="primaryAction"
                >
                    {{ primaryButtonText || 'View Shift Logs' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    primaryButtonText: String,
    type: {
        type: String,
        default: "success" // "success" or "error"
    }
})

const emit = defineEmits(["update:show", "primaryAction"])

const close = () => {
    emit("update:show", false)
}
const primaryAction = () => {
    emit("primaryAction")
}
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
