<script setup>
import Button from "@/Components/Buttons/Button.vue";
import DialogModal from "@/Components/Dialog/DialogModal.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import InputSelect from "@/Components/Forms/InputSelect.vue";
import { DialogPanel, DialogTitle } from "@headlessui/vue";
import { MinusIcon, PlusIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  options: { type: Object, default: {} },
  data: { type: Object, default: {} },
});

const emits = defineEmits(["close"]);

const form = useForm({
  number: 0,
  source_url: "",
  description: "",
  is_pinned: false,
  series_id: null,
});

const save = () => {
  form
    .transform((data) => ({
      ...data,
      series_id: data.series_id.id,
    }))
    .put(route("episode.update", props.data.id), {
      onSuccess: () => {
        emits("close");
        form.reset();
      },
    });
};

watchEffect(() => {
  if (props.data) {
    form.number = String(props.data.number);
    form.source_url = props.data.source_url ?? "";
    form.description = props.data.description;
    form.series_id = {
      id: props.data.series_id,
      name: props.options.find((eps) => eps.id == props.data.series_id)?.title,
    };
  }
});

const closeModal = () => {
  form.errors = {};
  emits("close");
};
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
          Edit {{ props.data.series.media === "video" ? "Episode" : "Chapter" }}
        </DialogTitle>

        <Button class="!size-9 !p-0" @click="closeModal()">
          <XMarkIcon class="size-5 opacity-50" />
        </Button>
      </div>

      <div class="mt-5 space-y-4">
        <InputGroup label="Series" class="w-full">
          <InputSelect
            :disabled="true"
            :options="props.options"
            v-model="form.series_id"
            placeholder="Choose one series"
            placement="bottom"
            :error="form.errors.series_id"
          />
          <small v-if="form.errors.series_id" class="text-xs text-red-500">
            {{ form.errors.series_id }}
          </small>
        </InputGroup>

        <InputGroup
          label="Source Url"
          placeholder="Valid url"
          :error="form.errors.source_url"
          v-model="form.source_url"
          class="flex-1"
          type="url"
        />

        <InputGroup
          :label="`Current ${
            props.data.series.media === 'video' ? 'Episode' : 'Chapter'
          }`"
          class="w-full flex"
        >
          <div class="w-full flex gap-2 items-center">
            <Button
              variant="primary"
              class="!size-7 !p-1 flex-none"
              @click="form.number--"
            >
              <MinusIcon class="size-full" />
            </Button>
            <input
              type="number"
              class="input max-w-full !w-full flex-1"
              v-model.number="form.number"
              :class="form.errors.number ? 'input-error' : ''"
            />
            <Button
              variant="primary"
              class="!size-7 !p-1 flex-none"
              @click="form.number++"
            >
              <PlusIcon class="size-full" />
            </Button>
          </div>
          <small v-if="form.errors.number" class="text-xs text-red-500">
            {{ form.errors.number }}
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
          @click="closeModal()"
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
