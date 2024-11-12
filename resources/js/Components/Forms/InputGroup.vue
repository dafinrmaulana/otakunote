<script setup>
import InputText from "./InputText.vue";
const props = defineProps({
  placeholder: { type: String, default: "" },
  label: { type: String, default: "" },
  error: { type: String, default: "" },
  autofocus: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
  type: { type: String, default: "text" },
});

const model = defineModel({
  default: "",
});
</script>

<template>
  <div class="flex flex-col items-start">
    <label :for="props.label" class="text-sm font-medium capitalize">
      {{ props.label }}
    </label>
    <slot />
    <InputText
      v-if="!$slots.default"
      class="input disabled:opacity-70 truncate"
      :class="{
        'input-error': props.error,
      }"
      v-model="model"
      :placeholder="props.placeholder"
      :id="props.label"
      :autofocus="props.autofocus"
      :disabled="props.disabled"
      :type="props.type"
    />
    <small v-if="props.error" class="text-xs text-red-500">
      {{ props.error }}
    </small>
  </div>
</template>
