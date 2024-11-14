<script setup>
import BadgeStatus from "@/Components/BadgeStatus.vue";
import Button from "@/Components/Buttons/Button.vue";
import Paginations from "@/Components/Paginations.vue";
import { getImage } from "@/Composables/getImage";
import { useOpenModal } from "@/Composables/openModal";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useLayout, useSidebar } from "@/Stores";
import {
  PencilSquareIcon,
  PlusIcon,
  StarIcon as StarIconSolid,
} from "@heroicons/vue/20/solid";
import {
  QueueListIcon,
  Squares2X2Icon,
  StarIcon,
  TrashIcon,
} from "@heroicons/vue/24/outline";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import Actions from "./Partials/Actions.vue";
import ModalCreate from "./Partials/ModalCreate.vue";
import ModalDelete from "./Partials/ModalDelete.vue";
import ModalEdit from "./Partials/ModalEdit.vue";

const { isOpen } = useSidebar();
const { isGrid, toggleGrid } = useLayout("seriesLayout");
const props = defineProps({
  series: { type: Object },
  categories: { type: Array },
  statuses: { type: Array },
});
const {
  isModalCreateOpen,
  isModalEditOpen,
  isModalDeleteOpen,
  dataModal: seriesData,
  modalTrigger: openModal,
} = useOpenModal();

const form = useForm({
  title: "",
  rating: "",
  slug: "",
  thumbnail: "",
  media: "",
  category_id: "",
  status_id: "",
  author: "",
  studio: "",
  source_url: "",
});

const updatePin = (series) => {
  seriesData.value = series;

  router.post(
    route("series.update", seriesData.value.id),
    {
      _method: "put",
      title: seriesData.value.title,
      rating: String(seriesData.value.rating),
      slug: seriesData.value.slug,
      thumbnail: seriesData.value.thumbnail,
      category_id: seriesData.value.category.id,
      status_id: seriesData.value.status.id,
      author: seriesData.value.author ?? "",
      media: seriesData.value.media,
      studio: seriesData.value.studio ?? "",
      source_url: seriesData.value.source_url ?? "",
      is_pinned: !seriesData.value.is_pinned,
    },
    {
      onSuccess: () => {
        isModalEditOpen.value = false;
      },
    }
  );
};

const closeModal = (modal) => {
  form.errors = {};
  if (modal == "edit") {
    isModalEditOpen.value = false;
  } else {
    isModalCreateOpen.value = false;
  }
};
</script>

<template>
  <Head title="Series" />
  <AuthenticatedLayout
    main-class="space-y-5"
    @click:add-btn="isModalCreateOpen = true"
  >
    <section class="flex w-full justify-between items-center">
      <h1 class="font-bold text-2xl">Series</h1>

      <div class="flex gap-2 items-center">
        <Button
          @click="toggleGrid"
          variant="outline-base"
          class="size-8 !p-0 text-slate-500 hover:text-black"
        >
          <component
            :is="!isGrid ? QueueListIcon : Squares2X2Icon"
            class="size-5"
          />
        </Button>
        <Button
          variant="primary"
          class="h-8 pr-4 pl-3 hidden sm:flex"
          @click="isModalCreateOpen = true"
        >
          <PlusIcon class="size-5" />
          <span class="ml-2">Add Series</span>
        </Button>
      </div>
    </section>

    <section class="w-full">
      <div
        v-if="!isGrid"
        class="w-full *:grid *:grid-cols-[3.5rem_1fr_1fr] min-[500px]:*:grid-cols-[3.5rem_1fr_1fr_1fr] sm:*:grid-cols-[3.5rem_1fr_1fr_1fr_1fr] md:*:grid-cols-[3.5rem_1fr_1fr_1fr_1fr_1fr] min-[500px]:*:gap-2 [&>div]:*:flex [&>div]:*:items-center"
      >
        <div
          class="bg-slate-200 px-3 py-2 rounded-md font-medium *:text-nowrap"
        >
          <div>#</div>
          <div>Title</div>
          <div class="!hidden sm:!flex">Status</div>
          <div class="!hidden md:!flex">Type</div>
          <div class="!hidden min-[500px]:!flex">Last Episode</div>
          <div class="justify-center">Actions</div>
        </div>

        <TransitionGroup name="list">
          <div
            v-for="series in props.series.data"
            class="px-3 py-2 border-b"
            :key="series.id"
          >
            <img
              :src="getImage(series.thumbnail)"
              :alt="series.title"
              class="rounded-full border size-10 object-cover"
            />
            <div class="overflow-hidden" :title="series.title">
              <Link
                :href="route('series.show', series.slug)"
                class="block w-full hover:text-indigo-500 duration-200"
                >{{ series.title }}</Link
              >
            </div>
            <div class="!hidden sm:!flex">
              <BadgeStatus
                :title="series.status.name"
                :color="series.status.color"
              />
            </div>
            <div class="!hidden md:!flex">
              {{ series.category?.name }}
            </div>
            <div class="!hidden min-[500px]:!flex">20</div>
            <div class="justify-center relative gap-2">
              <div class="sm:hidden">
                <Actions
                  content-class="!max-w-40"
                  @click:edit="openModal(series, 'edit')"
                  @click:delete="openModal(series, 'delete')"
                  @click:pin="updatePin(series)"
                  :is-pinned="series.is_pinned"
                />
              </div>
              <Button
                @click="updatePin(series)"
                class="hidden sm:flex size-8 !p-0"
              >
                <component
                  :is="series.is_pinned == true ? StarIconSolid : StarIcon"
                  class="size-4"
                  :class="
                    series.is_pinned == true
                      ? 'text-amber-400'
                      : 'text-gray-400'
                  "
                />
              </Button>
              <Button
                @click="openModal(series, 'edit')"
                variant="warning"
                class="hidden sm:flex size-8 !p-0"
              >
                <PencilSquareIcon class="size-4" />
              </Button>
              <Button
                @click="openModal(series, 'delete')"
                variant="danger"
                class="hidden sm:flex size-8 !p-0"
              >
                <TrashIcon class="size-4" />
              </Button>
            </div>
          </div>
        </TransitionGroup>
      </div>
      <div
        v-else
        class="grid grid-cols-2 min-[450px]:grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2"
        :class="
          isOpen
            ? 'lg:grid-cols-5 xl:grid-cols-6'
            : 'lg:grid-cols-7 xl:grid-cols-8'
        "
      >
        <TransitionGroup name="list">
          <template v-for="series in props.series.data" :key="series.id">
            <div class="w-full flex flex-col gap-1">
              <div class="w-full relative flex justify-end">
                <Actions
                  trigger-class="translate-y-1"
                  @click:edit="openModal(series, 'edit')"
                  @click:delete="openModal(series, 'delete')"
                  @click:pin="updatePin(series)"
                  :is-pinned="series.is_pinned"
                />
              </div>
              <div class="relative w-full h-56 overflow-hidden rounded-lg">
                <Link :href="route('series.show', series.slug)">
                  <img
                    :src="getImage(series.thumbnail)"
                    :alt="series.title"
                    class="object-cover border size-full duration-300 hover:scale-105 rounded-lg"
                  />
                </Link>
                <BadgeStatus
                  :title="series.status.name"
                  :color="series.status.color"
                  class="absolute top-2 right-2 px-5 sm:px-0"
                />
                <div
                  class="flex gap-2 items-center py-1 px-3 absolute bottom-2 left-2 text-white bg-amber-500 shadow text-xs rounded-md font-medium"
                >
                  <component
                    :is="series.is_pinned == true ? StarIconSolid : StarIcon"
                    class="size-4"
                  />
                  {{ series.rating }}
                </div>
              </div>
              <div class="flex flex-col">
                <h2
                  class="font-medium leading-4 line-clamp-2"
                  :title="series.title"
                >
                  {{ series.title }}
                </h2>
              </div>
            </div>
          </template>
        </TransitionGroup>
      </div>
    </section>
    <Paginations :pagination="props.series" />
    <ModalCreate
      :is-open="isModalCreateOpen"
      @close="closeModal('create')"
      :category-options="props.categories"
      :status-options="props.statuses"
    />
    <ModalEdit
      :is-open="isModalEditOpen"
      @close="closeModal('edit')"
      :category-options="props.categories"
      :status-options="props.statuses"
      :data="seriesData"
    />
    <ModalDelete
      :is-open="isModalDeleteOpen"
      @close="isModalDeleteOpen = false"
      :data="seriesData"
    />
  </AuthenticatedLayout>
</template>

<style lang="postcss">
th {
  @apply overflow-hidden text-ellipsis;
}
.grid-layout {
  @apply grid grid-cols-2 min-[500px]:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 2xl:grid-cols-8 gap-2;
}
</style>
