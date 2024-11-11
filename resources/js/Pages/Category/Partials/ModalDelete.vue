<script setup>
import Button from "@/Components/Buttons/Button.vue";
import DialogModal from "@/Components/Dialog/DialogModal.vue";
import { DialogDescription, DialogPanel, DialogTitle } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
const props = defineProps({
  isOpen: { type: Boolean, default: false },
  data: { type: Object, default: {} },
});

const emits = defineEmits(["close"]);
const disabled = ref(false);

const deleteCategory = () => {
  router.delete(route("category.destroy", props.data.id), {
    onStart: () => {
      disabled.value = true;
    },
    onSuccess: () => {
      emits("close");
    },
    onFinish: () => {
      disabled.value = false;
    },
  });
};
</script>

<template>
  <DialogModal :is-open="props.isOpen">
    <DialogPanel
      class="w-full sm:max-w-lg transform overflow-hidden rounded-t-2xl sm:rounded-xl bg-white px-5 pb-5 pt-3 text-left align-middle shadow-xl transition-all"
    >
      <div class="flex justify-between w-full items-center">
        <DialogTitle as="h3" class="text-lg font-bold leading-6 text-gray-900">
          Delete Category "{{ props.data.name }}"
        </DialogTitle>

        <Button class="!size-9 !p-0" @click="emits('close')">
          <XMarkIcon class="size-5 opacity-50" />
        </Button>
      </div>

      <DialogDescription>
        <p>Are you sure want to delete this Category?</p>
      </DialogDescription>

      <div class="mt-5 flex w-full gap-2 min-[400px]:justify-end">
        <Button
          variant="outline-danger"
          @click="emits('close')"
          class="flex-1 min-[400px]:flex-none"
          :disabled="disabled"
        >
          Cancel
        </Button>
        <Button
          variant="primary"
          class="flex-1 min-[400px]:flex-none"
          @click="deleteCategory"
          :disabled="disabled"
        >
          Delete
        </Button>
      </div>
    </DialogPanel>
  </DialogModal>
</template>
