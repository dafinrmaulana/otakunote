<script setup>
import Button from "@/Components/Buttons/Button.vue";

const props = defineProps({
  thumbnail: { type: String, default: "" },
  title: { type: String, default: "" },
  type: { type: String, default: "" },
});
</script>

<template>
  <Button
    class="!flex w-full !justify-start !h-auto !py-1 items-start !px-2 gap-2"
    variant="outline-base"
    @click="$emit('click')"
  >
    <img
      :src="props.thumbnail"
      alt=""
      class="w-10 h-full rounded object-cover border flex-none"
    />
    <div class="flex flex-col text-start">
      <h1 class="font-medium line-clamp-2">{{ props.title }}</h1>
      <small class="text-xs font-medium opacity-50">
        {{ props.type }}
      </small>
    </div>
  </Button>
</template>
