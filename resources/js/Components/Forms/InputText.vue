<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
  type: String,
  required: true,
  error: String,
});

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });

const props = defineProps({
  disabled: { type: Boolean, default: false },
});
</script>

<template>
  <input
    class="size-full bg-transparent placeholder-shown:text-ellipsis"
    :class="{
      '!border-red-500 focus-within:border-indigo-500': props.error,
    }"
    v-model="model"
    ref="input"
    :disabled="props.disabled"
  />
</template>

<style lang="postcss">
.input {
  @apply rounded-md bg-slate-100 border border-slate-200 shadow-sm focus-within:ring-1 duration-100 focus-within:border-indigo-500 focus-within:outline-none focus-within:ring-indigo-500 h-8;
}
</style>
