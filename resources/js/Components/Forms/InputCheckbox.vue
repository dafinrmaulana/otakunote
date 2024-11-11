<script setup>
import { CheckIcon } from "@heroicons/vue/16/solid";

const isChecked = defineModel();
</script>

<template>
  <label
    class="relative bg-slate-100 border rounded size-5 overflow-hidden cursor-pointer"
  >
    <input type="checkbox" v-model="isChecked" class="sr-only peer" />

    <div
      class="absolute size-full bg-violet-600 top-0 p-[2px] text-white opacity-0 peer-checked:opacity-100 duration-150"
    >
      <CheckIcon />
    </div>
  </label>
</template>
