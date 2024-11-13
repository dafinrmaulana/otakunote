<script setup>
import Button from "@/Components/Buttons/Button.vue";
import DialogModal from "@/Components/Dialog/DialogModal.vue";
import InputFile from "@/Components/Forms/InputFile.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import InputSelect from "@/Components/Forms/InputSelect.vue";
import InputText from "@/Components/Forms/InputText.vue";
import { DialogPanel, DialogTitle } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";
import {
  ArrowUturnLeftIcon,
  TrashIcon,
  XMarkIcon,
} from "@heroicons/vue/20/solid";
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  categoryOptions: { type: Array, default: () => [] },
  statusOptions: { type: Array, default: () => [] },
  data: { type: Object, default: () => {} },
});
const form = useForm({
  title: "",
  rating: "",
  slug: "",
  thumbnail: "",
  category_id: "",
  status_id: "",
  media: "",
  author: "",
  studio: "",
  source_url: "",
});
const fileName = ref("");
const emits = defineEmits(["close"]);
const disabled = ref(false);
const isAdvanceOpen = ref(false);
const toggleHideClass = ref("hidden");

const mediaOptions = [
  {
    name: "Video",
    id: "video",
  },
  {
    name: "Book",
    id: "book",
  },
];

watch(
  () => props.data,
  () => {
    form.title = props.data.title;
    form.rating = String(props.data.rating);
    form.slug = props.data.slug;
    form.thumbnail = props.data.thumbnail ?? "";
    form.category_id = props.data.category;
    form.status_id = props.data.status;
    form.author = props.data.author ?? "";
    form.studio = props.data.studio ?? "";
    form.source_url = props.data.source_url ?? "";
    form.media = {
      name: mediaOptions.find((media) => media.id == props.data.media).name,
      id: props.data.media,
    };
  }
);

const handleFileSelected = (e) => {
  fileName.value = e.name;
  form.thumbnail = e;
};

watch(
  () => isAdvanceOpen.value,
  () => {
    if (!isAdvanceOpen.value) {
      toggleHideClass.value = "hidden";
    } else {
      setTimeout(() => {
        toggleHideClass.value = "";
      }, 350);
    }
  }
);

const save = () => {
  router.post(
    route("series.update", props.data.id),
    {
      _method: "put",
      ...form.data(),
      category_id: form.category_id.id,
      status_id: form.status_id.id,
      media: form.media.id,
    },
    {
      onStart: () => (disabled.value = true),
      onFinish: () => (disabled.value = false),
      onError: (errors) => (form.errors = errors),
      onSuccess: () => {
        emits("close");
        form.reset();
      },
      preserveScroll: true,
    }
  );
};

const removeThumbnail = () => {
  router.post(
    route("series.thumbnail.destroy", props.data.id),
    {
      _method: "put",
    },
    {
      onStart: () => (disabled.value = true),
      onFinish: () => (disabled.value = false),
      onError: (errors) => {
        throw new Error(errors);
      },
      onSuccess: () => {
        emits("close");
        form.reset();
      },
      preserveScroll: true,
    }
  );
};

const undoLocalThumbnail = () => {
  form.thumbnail = props.data.thumbnail;
  fileName.value = "";
};

const closeModal = () => {
  form.errors = {};
  form.thumbnail = "";
  emits("close");
};
</script>

<template>
  <DialogModal :is-open="props.isOpen">
    <DialogPanel as="form" @submit.prevent="save" class="panel">
      <div class="flex justify-between w-full items-center">
        <DialogTitle as="h3" class="text-lg font-bold leading-6 text-gray-900">
          Edit Series
        </DialogTitle>

        <Button class="!size-9 !p-0" @click="closeModal()">
          <XMarkIcon class="size-5 opacity-50" />
        </Button>
      </div>

      <div class="mt-5 space-y-4">
        <div class="flex gap-2">
          <!-- title -->
          <InputGroup
            label="Title"
            placeholder="Konosuba, Infinite Mage etc ..."
            :error="form.errors.title"
            v-model="form.title"
            class="flex-1"
          />

          <!-- rating -->
          <InputGroup
            label="Rating"
            class="max-w-20"
            placeholder="9.0"
            type="text"
            v-model="form.rating"
            :error="form.errors.rating"
          />
        </div>

        <!-- slug -->
        <InputGroup
          label="Slug"
          placeholder="konosuba, infinite-mage etc ..."
          :error="form.errors.slug"
          v-model="form.slug"
        />

        <!-- thumbnail -->
        <InputGroup label="Thumbnail" class="w-full">
          <div class="flex gap-2 w-full">
            <InputFile
              class="flex-1"
              @update:file="handleFileSelected"
              :error="form.errors.thumbnail"
              :placeholder="fileName"
            />

            <Button
              v-if="form.thumbnail === Object(form.thumbnail)"
              @click="undoLocalThumbnail"
              variant="outline-warning"
              class="size-8 !p-0 flex-none"
              title="Undo thumbnail"
              :disabled="disabled"
            >
              <ArrowUturnLeftIcon class="size-4" />
            </Button>
            <Button
              v-if="props.data.thumbnail"
              @click="removeThumbnail"
              variant="outline-danger"
              class="size-8 !p-0 flex-none"
              title="Remove thumbnail"
              :disabled="disabled"
            >
              <TrashIcon class="size-4" />
            </Button>
          </div>
          <InputText
            v-if="form.thumbnail !== Object(form.thumbnail)"
            class="w-full input mt-2 truncate"
            v-model="form.thumbnail"
            type="text"
            placeholder="With url"
            :error="form.errors.thumbnail"
          />
          <small v-if="form.errors.thumbnail" class="text-xs text-red-500">
            {{ form.errors.thumbnail }}
          </small>
        </InputGroup>

        <!-- category and status -->
        <div class="grid grid-cols-2 gap-2">
          <InputGroup label="Type" class="w-full">
            <InputSelect
              :options="props.categoryOptions"
              v-model="form.category_id"
              placeholder="Select category"
              placement="top"
              :error="form.errors.category_id"
            />
            <small v-if="form.errors.category_id" class="text-xs text-red-500">
              {{ form.errors.category_id }}
            </small>
          </InputGroup>

          <InputGroup label="Status" class="w-full">
            <InputSelect
              :options="props.statusOptions"
              v-model="form.status_id"
              placeholder="Select status"
              placement="top"
              :error="form.errors.status_id"
            />
            <small v-if="form.errors.status_id" class="text-xs text-red-500">
              {{ form.errors.status_id }}
            </small>
          </InputGroup>
        </div>

        <!-- media -->
        <InputGroup label="Media" class="w-full">
          <InputSelect
            :options="mediaOptions"
            v-model="form.media"
            placeholder="Select media"
            placement="top"
            :error="form.errors.media"
          />
          <small v-if="form.errors.media" class="text-xs text-red-500">
            {{ form.errors.media }}
          </small>
        </InputGroup>

        <!-- advance options -->
        <button
          @click="isAdvanceOpen = !isAdvanceOpen"
          type="button"
          class="advance-btn"
          :class="{
            'opacity-100 hover:opacity-50': isAdvanceOpen,
            'opacity-50 hover:opacity-100': !isAdvanceOpen,
          }"
        >
          <h5>{{ isAdvanceOpen ? "Hide" : "Show" }} advanced options</h5>
          <ChevronDownIcon
            class="size-3 duration-200"
            :class="isAdvanceOpen ? 'rotate-180' : 'rotate-0'"
          />
        </button>
        <div
          class="grid transition-all duration-300 ease-in-out"
          :class="{
            'grid-rows-[0fr]': !isAdvanceOpen,
            'grid-rows-[1fr]': isAdvanceOpen,
          }"
        >
          <div class="space-y-4" :style="{ overflow: toggleHideClass }">
            <!-- author -->
            <InputGroup
              label="Author"
              placeholder="Natsume Akatsuki, Kim chi woo etc ..."
              :error="form.errors.author"
              v-model="form.author"
              class="flex-1"
            />

            <!-- studio -->
            <InputGroup
              label="Studio"
              placeholder="Studio Deen, REDICE studio etc ..."
              :error="form.errors.studio"
              v-model="form.studio"
              class="flex-1"
            />

            <!-- source url -->
            <InputGroup
              label="Source Url"
              placeholder="Valid url"
              :error="form.errors.source_url"
              v-model="form.source_url"
              class="flex-1"
              type="url"
            />
          </div>
        </div>
      </div>

      <div class="mt-5 flex w-full gap-2 min-[400px]:justify-end">
        <Button
          variant="outline-danger"
          @click="closeModal()"
          class="flex-1 min-[400px]:flex-none"
          :disabled="disabled"
        >
          Cancel
        </Button>
        <Button
          type="submit"
          variant="primary"
          class="flex-1 min-[400px]:flex-none"
          :disabled="disabled"
        >
          Save
        </Button>
      </div>
    </DialogPanel>
  </DialogModal>
</template>

<style lang="postcss" scoped>
.advance-btn {
  @apply w-full flex items-center gap-2 cursor-pointer justify-between text-sm hover:opacity-100 duration-150;
}

.panel {
  @apply w-full sm:max-w-lg rounded-t-2xl sm:rounded-b-xl sm:rounded-t-xl bg-white px-5 pb-5 pt-3 text-left align-middle shadow-xl transition-all flex flex-col max-h-full overflow-y-auto;
}
</style>
