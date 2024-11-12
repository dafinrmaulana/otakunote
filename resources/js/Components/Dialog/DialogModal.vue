<script setup>
import { Dialog, TransitionChild, TransitionRoot } from "@headlessui/vue";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
});

const emits = defineEmits(["close"]);

function closeModal() {
  emits("close");
}
</script>

<template>
  <TransitionRoot appear :show="props.isOpen" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-200 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" @click="closeModal" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto scrollbar-hidden">
        <div
          class="flex min-h-full h-full sm:h-auto items-end sm:items-start justify-center p-0 sm:p-5 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-200 ease-out"
            enter-from="opacity-0 translate-y-10 sm:-translate-y-10"
            enter-to="opacity-100 translate-y-0"
            leave="duration-200 ease-out"
            leave-from="opacity-100 translate-y-0"
            leave-to="opacity-0 translate-y-10 sm:-translate-y-10"
          >
            <slot />
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
