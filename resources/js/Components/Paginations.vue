<script setup>
import {
  ChevronDoubleLeftIcon,
  ChevronDoubleRightIcon,
} from "@heroicons/vue/16/solid";
import { router } from "@inertiajs/vue3";
import { computed } from "vue";
import Button from "./Buttons/Button.vue";

const props = defineProps({
  pagination: {
    type: Object,
    required: true,
  },
});

const pagination = computed(() => {
  const { data, ...rest } = props.pagination;
  return rest;
});

const hasPreviousPage = computed(() => props.pagination.prev_page_url !== null);
const hasNextPage = computed(() => props.pagination.next_page_url !== null);
</script>

<template>
  <nav
    class="flex items-center justify-center gap-4 py-4"
    v-if="pagination.links.length > 3"
  >
    <Button
      variant="outline-base"
      class="text-sm text-black/70"
      @click="router.get(props.pagination.prev_page_url)"
      :disabled="!hasPreviousPage"
    >
      <ChevronDoubleLeftIcon class="size-3 mr-1" />
      Previous
    </Button>

    <span class="text-sm text-gray-600">
      Page {{ props.pagination.current_page }} of
      {{ props.pagination.last_page }}
    </span>

    <Button
      variant="outline-base"
      class="text-sm text-black/60"
      @click="router.get(props.pagination.next_page_url)"
      :disabled="!hasNextPage"
      :class="{ 'opacity-50 cursor-not-allowed': !hasNextPage }"
    >
      Next
      <ChevronDoubleRightIcon class="size-3 ml-1" />
    </Button>
  </nav>
</template>

<style scoped>
/* Optional styling to adjust button appearance further if needed */
</style>
