<script setup>
import Button from "@/Components/Buttons/Button.vue";
import DialogModal from "@/Components/Dialog/DialogModal.vue";
import InputFile from "@/Components/Forms/InputFile.vue";
import InputGroup from "@/Components/Forms/InputGroup.vue";
import InputSelect from "@/Components/Forms/InputSelect.vue";
import {
  DialogPanel,
  DialogTitle,
  Popover,
  PopoverButton,
  PopoverPanel,
} from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";
import { TrashIcon, XMarkIcon } from "@heroicons/vue/20/solid";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { computed, ref, watch } from "vue";
import RecomendationBtn from "./RecomendationBtn.vue";
import SearchLoading from "./SearchLoading.vue";
import InputText from "@/Components/Forms/InputText.vue";

const props = defineProps({
  isOpen: { type: Boolean, default: false },
  categoryOptions: { type: Array, default: () => [] },
  statusOptions: { type: Array, default: () => [] },
});
const form = useForm({
  title: "",
  rating: "",
  slug: "",
  thumbnail: "",
  category_id: "",
  status_id: "",
  author: "",
  studio: "",
  media: "",
  source_url: "",
});

const emits = defineEmits(["close"]);
const isAdvanceOpen = ref(false);
const toggleHideClass = ref("hidden");
const fileName = ref("");
const handleFileSelected = (e) => {
  fileName.value = e.name;
  form.thumbnail = e;
};

const removeThumbnail = () => {
  fileName.value = "";
  form.thumbnail = "";
};

const slug = computed(() =>
  form.title
    .toLowerCase()
    .replace(/ /g, "-")
    .replace(/[^\w-]+/g, "")
);

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

const save = () => {
  form
    .transform((data) => ({
      ...data,
      category_id: parseInt(data.category_id.id),
      status_id: parseInt(data.status_id.id),
      media: data.media.id,
      slug: data.title
        .toLowerCase()
        .replace(/ /g, "-")
        .replace(/[^\w-]+/g, ""),
    }))
    .post(route("series.store"), {
      onSuccess: () => {
        emits("close");
        form.reset();
      },
    });
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

const closeModal = () => {
  form.errors = {};
  emits("close");
};

const animeRecomendations = ref([]);
const mangaRecomendations = ref([]);
const isRecomendationLoading = ref(false);
const debounceTimeout = ref(null);
const isRecomendationShow = ref(false);

const getRecomendations = async (query) => {
  isRecomendationLoading.value = true;
  try {
    const animeRes = await axios.get("/proxy/recomendations/manga", {
      params: {
        q: query,
      },
    });
    const mangaRes = await axios.get("/proxy/recomendations/anime", {
      params: {
        q: query,
      },
    });

    animeRecomendations.value = animeRes.data.data;
    mangaRecomendations.value = mangaRes.data.data;
  } catch (error) {
    throw new Error(error);
  } finally {
    isRecomendationLoading.value = false;
  }
};

const handleSearch = (query) => {
  if (debounceTimeout.value) {
    clearTimeout(debounceTimeout.value);
  }

  debounceTimeout.value = setTimeout(() => {
    if (form.title !== "") {
      getRecomendations(form.title);
      isRecomendationShow.value = true;
    } else {
      mangaRecomendations.value = [];
      animeRecomendations.value = [];
      isRecomendationShow.value = false;
    }
  }, 300);
};

const handleFillForm = (data) => {
  form.title = data.title;
  form.thumbnail = data.images.jpg.image_url;
  form.rating = String(data.score);

  animeRecomendations.value = [];
  mangaRecomendations.value = [];
  isRecomendationShow.value = false;
};
</script>

<template>
  <DialogModal :is-open="props.isOpen">
    <DialogPanel as="form" @submit.prevent="save" class="panel">
      <div class="flex justify-between w-full items-center">
        <DialogTitle as="h3" class="text-lg font-bold leading-6 text-gray-900">
          Add Series
        </DialogTitle>

        <Button class="!size-9 !p-0" @click="closeModal()">
          <XMarkIcon class="size-5 opacity-50" />
        </Button>
      </div>

      <div class="mt-5 space-y-4">
        <div class="relative flex gap-2">
          <!-- title -->
          <div class="flex-1">
            <InputGroup label="Title">
              <input
                type="text"
                class="input w-full truncate"
                @click="isRecomendationShow = !isRecomendationShow"
                @input="handleSearch(form.title)"
                v-model="form.title"
                placeholder="Konosuba, Infinite Mage etc ..."
              />
            </InputGroup>

            <div
              v-if="isRecomendationShow"
              class="absolute top-full space-y-2 p-2 mt-2 inset-x-0 bg-white border rounded-md shadow-lg z-30 max-h-72 overflow-y-auto"
            >
              <template v-if="!isRecomendationLoading">
                <template v-for="anime in animeRecomendations">
                  <RecomendationBtn
                    :title="anime.title"
                    :thumbnail="anime.images.jpg.image_url"
                    :type="anime.type"
                    @click="handleFillForm(anime)"
                  />
                </template>
                <template v-for="manga in mangaRecomendations">
                  <RecomendationBtn
                    :title="manga.title"
                    :thumbnail="manga.images.jpg.image_url"
                    :type="manga.type"
                    @click="handleFillForm(manga)"
                    :class="
                      !manga.title
                        .toLowerCase()
                        .includes(form.title.toLowerCase())
                        ? 'hidden'
                        : ''
                    "
                  />
                </template>
              </template>
              <SearchLoading v-else-if="isRecomendationLoading" />
              <h1
                v-if="
                  !isRecomendationLoading &&
                  !mangaRecomendations.length &&
                  !animeRecomendations.length
                "
                class="text-center text-sm font-medium opacity-50"
              >
                No recomendation
              </h1>
            </div>
          </div>

          <!-- rating -->
          <InputGroup
            label="Rating"
            class="max-w-20"
            placeholder="9.5"
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
          v-model="slug"
          :disabled="true"
        />

        <!-- thumbnail -->
        <InputGroup label="Thumbnail">
          <div class="flex gap-2 w-full">
            <InputFile
              class="flex-1"
              :error="form.errors.thumbnail"
              :placeholder="fileName"
              @update:file="(e) => handleFileSelected(e)"
            />

            <Button
              v-if="form.thumbnail === Object(form.thumbnail)"
              variant="outline-danger"
              class="size-8 !p-0 flex-none"
              title="Remove thumbnail"
              @click="removeThumbnail"
            >
              <TrashIcon class="size-4" />
            </Button>
          </div>
          <InputText
            class="w-full input mt-2 truncate"
            v-if="form.thumbnail !== Object(form.thumbnail)"
            v-model="form.thumbnail"
            type="text"
            placeholder="With url"
            :error="form.errors.thumbnail"
          />
          <small v-if="form.errors.thumbnail" class="text-xs text-red-500">
            {{ form.errors.thumbnail }}
          </small>
        </InputGroup>

        <div class="grid grid-cols-2 gap-2">
          <!-- category -->
          <InputGroup label="Category" class="w-full">
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

          <!-- status -->
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
            <!-- source url -->
            <InputGroup
              label="Source Url"
              placeholder="Valid url"
              :error="form.errors.source_url"
              v-model="form.source_url"
              class="flex-1"
              type="url"
            />
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
          </div>
        </div>
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

<style lang="postcss" scoped>
.advance-btn {
  @apply w-full flex items-center gap-2 cursor-pointer justify-between text-sm hover:opacity-100 duration-150;
}

.panel {
  @apply w-full sm:max-w-lg rounded-t-2xl sm:rounded-b-xl sm:rounded-t-xl bg-white px-5 pb-5 pt-3 text-left align-middle shadow-xl transition-all flex flex-col max-h-full overflow-y-auto;
}
</style>
