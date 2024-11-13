<script setup>
import { MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import InputText from "./InputText.vue";
import { computed } from "vue";

const props = defineProps({
  icon: { type: Function, default: MagnifyingGlassIcon },
  for: { type: String, default: "search" },
  placeholder: { type: String, default: "Search..." },
  autofocus: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
});

const model = defineModel({
  default: "",
});

const attrs = computed(() => {
  props.autofocus === true ? "autofocus" : null;
});
</script>

<template>
  <label :for="props.for" class="flex input items-center px-3">
    <component :is="props.icon" class="size-5 text-slate-400" />
    <InputText
      :id="props.for"
      type="text"
      :placeholder="props.placeholder"
      v-model="model"
      v-bind="attrs"
      class="focus-within:ring-0 focus-within:border-none border-none !px-2"
      :disabled="props.disabled"
    />
  </label>
</template>
