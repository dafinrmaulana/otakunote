<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
  type: String,
  required: true,
});
const props = defineProps({
  error: String,
});

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input
    class="rounded-md border-gray-300 shadow-sm focus:border-violet-600 focus:ring-violet-600 bg-slate-100"
    :class="props.error ? 'border-red-500' : ''"
    v-model="model"
    ref="input"
  />
</template>
