<script setup>
import Button from "@/Components/Buttons/Button.vue";
import DialogModal from "@/Components/Dialog/DialogModal.vue";
import Dropdown from "@/Components/Dropdown/Dropdown.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import { DialogPanel, DialogTitle, MenuItem } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
import { watch, watchEffect } from "vue";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  data: { type: Object, default: {} },
});

const emits = defineEmits(["close"]);

const form = useForm({
  name: "",
  slug: "",
  color: "#7c3aed",
  description: "",
});

watch(
  () => props.data,
  () => {
    form.name = props.data.name;
    form.slug = props.data.slug;
    form.color = props.data.color;
    form.description = props.data.description;
  }
);

const save = () => {
  form.put(route("genre.update", props.data.id), {
    onSuccess: () => {
      emits("close");
      form.reset();
    },
  });
};

const defaultColors = ["#7c3aed", "#dc2626", "#fbbf24", "#10b981", "#0ea5e9"];
</script>

<template>
  <DialogModal :is-open="props.isOpen">
    <DialogPanel
      as="form"
      @submit.prevent="save"
      class="w-full sm:max-w-lg transform overflow-hidden rounded-t-2xl sm:rounded-xl bg-white px-5 pb-5 pt-3 text-left align-middle shadow-xl transition-all"
    >
      <div class="flex justify-between w-full items-center">
        <DialogTitle as="h3" class="text-lg font-bold leading-6 text-gray-900">
          Edit Genre
        </DialogTitle>

        <Button class="!size-9 !p-0" @click="emits('close')">
          <XMarkIcon class="size-5 opacity-50" />
        </Button>
      </div>

      <div class="mt-5 space-y-4">
        <InputGroup
          label="Name"
          placeholder="Action, Fantasy etc ..."
          :error="form.errors.name"
          v-model="form.name"
        />
        <InputGroup
          label="Slug"
          placeholder="action, fantasy etc ..."
          :error="form.errors.slug"
          v-model="form.slug"
        />
        <InputGroup label="Accent Color">
          <div class="w-full [&>button]:w-full relative">
            <Dropdown placement="top">
              <template #trigger>
                <button
                  type="button"
                  class="input !w-full px-3 flex gap-2 items-center"
                  :class="{
                    'input-error': form.errors.color,
                  }"
                >
                  <div
                    class="size-3 rounded-full"
                    :style="{ backgroundColor: form.color }"
                  />
                  <span class="text-slate-600 uppercase">{{ form.color }}</span>
                </button>
              </template>
              <template #content>
                <MenuItem>
                  <div class="flex flex-col gap-2">
                    <p class="text-slate-600 font-medium text-sm">
                      Choose Default Color
                    </p>
                    <div class="flex w-full gap-1">
                      <button
                        type="button"
                        v-for="color in defaultColors"
                        class="flex-1 rounded h-5"
                        :style="{ backgroundColor: color }"
                        @click="form.color = color"
                      />
                    </div>
                  </div>
                </MenuItem>
                <div class="flex flex-col gap-1">
                  <p class="text-slate-600 font-medium text-sm">
                    Create Custom Color
                  </p>
                  <input
                    type="color"
                    v-model="form.color"
                    class="w-full rounded-md"
                  />
                </div>
              </template>
            </Dropdown>
          </div>
          <small v-if="form.errors.color" class="text-xs text-red-500 mt-1">
            {{ form.errors.color }}
          </small>
        </InputGroup>
        <InputGroup label="Description">
          <textarea
            id="Description"
            class="input w-full !h-auto"
            :class="{
              'input-error': form.errors.description,
            }"
            rows="3"
            placeholder="Description"
            v-model="form.description"
          />
          <small
            v-if="form.errors.description"
            class="text-xs text-red-500 mt-1"
          >
            {{ form.errors.description }}
          </small>
        </InputGroup>
      </div>

      <div class="mt-5 flex w-full gap-2 min-[400px]:justify-end">
        <Button
          variant="outline-danger"
          @click="emits('close')"
          class="flex-1 min-[400px]:flex-none"
          :disabled="form.processing"
        >
          Cancel
        </Button>
        <Button
          type="submit"
          variant="primary"
          class="flex-1 min-[400px]:flex-none"
          :disabled="form.processing"
        >
          Save
        </Button>
      </div>
    </DialogPanel>
  </DialogModal>
</template>
