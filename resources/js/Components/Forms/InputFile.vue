<script setup>
const emits = defineEmits(["update:file"]);

const props = defineProps({
  error: { type: String, default: "" },
  id: { type: String, default: "" },
  placeholder: { type: String, default: "" },
});
</script>

<template>
  <div class="input relative">
    <input
      type="file"
      ref="fileInput"
      class="absolute w-full inset-0 h-full opacity-0 z-10 cursor-pointer peer"
      @change="emits('update:file', $event.target.files[0])"
    />

    <label
      for="file-upload"
      class="absolute size-full flex items-center gap-2 pr-1 peer-focus-within:ring-1 duration-100 peer-focus-within:border-indigo-500 peer-focus-within:outline-none peer-focus-within:ring-indigo-500 rounded-md"
      :class="{
        'input-error': props.error,
      }"
    >
      <span
        class="bg-indigo-500 text-white px-3 h-full rounded-l-md flex items-center text-sm text-nowrap"
      >
        Choose File
      </span>
      <span class="truncate text-slate-600">
        {{ props.placeholder || "Browse from device" }}
      </span>
    </label>
  </div>
</template>
