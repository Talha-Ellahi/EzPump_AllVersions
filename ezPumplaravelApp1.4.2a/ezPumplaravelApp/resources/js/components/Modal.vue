<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  maxWidth: {
    type: String,
    default: '2xl',
    validator: (value) => ['sm', 'md', 'lg', 'xl', '2xl'].includes(value)
  },
  closeable: {
    type: Boolean,
    default: true
  }
});
const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'visible';
        }
    }
);

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = 'visible';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'modal-dialog modal-sm',
        md: 'modal-dialog modal-md',
        lg: 'modal-dialog modal-lg',
        xl: 'modal-dialog modal-xl',
        '2xl': 'modal-dialog modal-xl', // Bootstrap does not have a '2xl' size, using 'xl' as a fallback
    }[props.maxWidth];
});
// Assuming you have a close function defined elsewhere, you can call it here
function close() {
  emit('close');
}
</script>
<template>
    <Teleport to="body">
        <div v-show="show" class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog" :class="maxWidthClass">
                <div class="modal-content">
                        <slot v-if="show" />
                    </div>
            </div>
            <!-- <div class="modal-backdrop  show" @click="close"></div> -->
        </div>
    </Teleport>
</template>