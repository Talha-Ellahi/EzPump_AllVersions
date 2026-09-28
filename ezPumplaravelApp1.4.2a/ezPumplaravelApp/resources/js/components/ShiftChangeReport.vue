<template>
  <div style="display: flex; flex-direction: row; gap: 5px; flex-wrap: wrap; justify-content: center;">
    <div
      style="display: flex;position: relative; box-shadow: 0px 0px 8px -3px gray; width: fit-content; height: fit-content; background-color: white; gap: 7px; border-radius: 6px; padding: 20px 7px; border: 1px solid #b8b8b8;"
      v-for="(item, index) in modelValue" :key="index" class="nozze_div">
      <div
        style="position: absolute; top: 0px; left: -1px; background: #404040; color: white; border-radius: 50%; width: 23px; height: 23px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
        {{ item.display_name || index + 1 }}
      </div>
      <div style="display: flex; flex-direction: column; gap: 8px 0px; width: 180px;">
        <input type="text" v-model="item.opening" placeholder="Opening" class="form-control">
        <input type="text" v-model="item.closing" placeholder="Closing" class="form-control">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span style="font-weight: 300;">Total: <span>{{ calculateItemTotal(item) }}</span></span>
          <button @click="RemoveData(index)" class="btn btn-danger"
            style="padding: 2px 8px; background: red !important; border: none;">Remove</button>
        </div>
      </div>
    </div>
  </div>
  <div style="display: flex; justify-content: end;">
    <button @click="AddData" class="btn btn-dark" style="padding: 3px 10px; margin-top: 2px;">Add New Item</button>
  </div>
  <!-- {{ Form1 }} -->
</template>

<script setup>
import { computed } from "vue";
const props = defineProps({
  modelValue: {
    type: Array,
    required: true
  }
});
const emit = defineEmits(['update:modelValue']);


const calculateItemTotal = (item) => {
  const opening = parseFloat(item.opening) || 0;
  const closing = parseFloat(item.closing) || 0;
  return  closing- opening ;
};

const grandTotal = computed(() => {
  return props.modelValue.reduce((acc, item) => {
    return acc + calculateItemTotal(item);
  }, 0);
});

const AddData = () => {
  const newData = [...props.modelValue];
  newData.push({ opening: '', closing: '', total: '', image: '' });
  emit('update:modelValue', newData);
};

const RemoveData = (index) => {
  if (!confirm("Are You Sure")) return;
  const newData = [...props.modelValue];
  newData.splice(index, 1);
  emit('update:modelValue', newData);
};

</script>
