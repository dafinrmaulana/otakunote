<script setup>
import { ref, computed, watchEffect } from "vue";
import {
  Combobox,
  ComboboxInput,
  ComboboxButton,
  ComboboxOptions,
  ComboboxOption,
} from "@headlessui/vue";
import { ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
  options: { type: Object, default: [{}] },
  placeholder: { type: String, default: "" },
  placement: { type: String, default: "bottom" },
  error: { type: String, default: "" },
  disabled: { type: Boolean, default: false },
});
const model = defineModel();
let query = ref("");

let filteredOptions = computed(() =>
  query.value === ""
    ? props.options
    : props.options.filter(
        (option) =>
          option.name
            ?.toLowerCase()
            .replace(/\s+/g, "")
            .includes(query.value.toLowerCase().replace(/\s+/g, "")) ||
          option.title
            ?.toLowerCase()
            .replace(/\s+/g, "")
            .includes(query.value.toLowerCase().replace(/\s+/g, ""))
      )
);

const emits = defineEmits(["click:option"]);
</script>

<template>
  <div class="relative w-full">
    <Combobox v-model="model">
      <ComboboxButton
        :disabled="props.disabled"
        class="flex input pl-2 pr-1 w-full items-center disabled:opacity-70"
        :class="{
          'input-error': props.error,
        }"
      >
        <ComboboxInput
          :disabled="props.disabled"
          @change="query = $event.target.value"
          :displayValue="(option) => option?.name ?? option?.title"
          class="bg-transparent !border-none !outline-none !ring-0 flex-1 w-full pl-0 placeholder-shown:text-ellipsis text-ellipsis"
          :placeholder="props.placeholder"
        />
        <ComboboxButton class="flex-none">
          <ChevronUpDownIcon class="size-4 text-slate-500" />
        </ComboboxButton>
      </ComboboxButton>
      <ComboboxOptions
        class="absolute w-full"
        :class="{
          'top-full': props.placement === 'bottom',
          'bottom-full': props.placement === 'top',
        }"
      >
        <div
          class="relative z-10 rounded-md border shadow w-full p-2 flex flex-col gap-2 bg-white max-h-40 overflow-y-auto"
          :class="{
            'mb-5 mt-2': props.placement === 'bottom',
            'mt-5 mb-2': props.placement === 'top',
          }"
        >
          <ComboboxOption
            v-if="props.options.length > 0 && filteredOptions.length > 0"
            v-for="option in filteredOptions"
            :key="option"
            :value="option"
            @click="emits('click:option', option)"
            class="ui-active:bg-slate-100 ui-active:outline ui-selected:bg-slate-100 ui-selected:border ui-active:outline-1 ui-active:outline-slate-300 px-2 py-1 rounded duration-200 outline-transparent cursor-pointer"
          >
            {{ option.name ?? option.title }}
          </ComboboxOption>
          <ComboboxOption v-else disabled class="text-sm">
            No results
          </ComboboxOption>
        </div>
      </ComboboxOptions>
    </Combobox>
  </div>
</template>
