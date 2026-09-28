<template>
 <div v-if="isVisible">
    <transition name="modal">
      <div class="modal fade" tabindex="-1" aria-modal="true" role="dialog" style="display: block;" @click="hide">
        <div class="modal-dialog" @click.stop>
          <div class="modal-content">
            <div class="modal-header">
              <slot name="title"></slot>
              <button type="button" class="btn-close" aria-label="Close" @click="hide"></button>
            </div>
            <div class="modal-body">
              <slot name="body"></slot>
            </div>
            <div class="modal-footer">
              <slot name="actions"></slot>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition name="fade">
      <div class="modal-backdrop fade show"></div>
    </transition>
  </div>
</template>

<script setup>
import {ref, watch} from 'vue';
defineExpose({ show, hide });

const isVisible = ref(false);

function show() {
  isVisible.value = true;
}

function hide() {
  isVisible.value = false;
}
watch(isVisible, (newValue) => {
  if (newValue) {
    document.body.classList.add('modal-open');
  } else {
    document.body.classList.remove('modal-open');
  }
});
</script>

<style scoped>
.modal {
  display: block; /* Override Bootstrap's display none */
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}


/* Add transition effects */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>