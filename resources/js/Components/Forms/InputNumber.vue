<script setup>
const emit = defineEmits(["update:modelValue"]);

const props = defineProps({
  modelValue: {
    type: Number,
    default: 0,
  },
});
</script>

<template>
  <input
    type="number"
    class="input"
    :value="props.modelValue"
    @input="emit('update:modelValue', $event.target.value)"
  />
</template>
