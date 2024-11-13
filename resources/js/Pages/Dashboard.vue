<script setup>
import BadgeStatus from "@/Components/BadgeStatus.vue";
import Button from "@/Components/Buttons/Button.vue";
import Paginations from "@/Components/Paginations.vue";
import { getImage } from "@/Composables/getImage";
import { useOpenModal } from "@/Composables/openModal";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useLayout, useSidebar } from "@/Stores";
import {
  ArrowPathIcon,
  PencilSquareIcon,
  PlusIcon,
  TrashIcon,
} from "@heroicons/vue/20/solid";
import {
  QueueListIcon,
  Squares2X2Icon,
  StarIcon,
} from "@heroicons/vue/24/outline";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import Actions from "./Episode/Partials/Actions.vue";
import ModalCreate from "./Episode/Partials/ModalCreate.vue";
import ModalDelete from "./Episode/Partials/ModalDelete.vue";
import ModalEdit from "./Episode/Partials/ModalEdit.vue";

const page = usePage();
const props = defineProps({
  episodes: { type: Object },
  series: { type: Object },
});

const { isGrid, toggleGrid } = useLayout("dashboardLayout");
const { isOpen } = useSidebar();
const {
  isModalCreateOpen,
  isModalEditOpen,
  isModalDeleteOpen,
  dataModal: dashboardData,
  modalTrigger: openModal,
} = useOpenModal();
</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout
    main-class="space-y-5"
    @click:add-btn="isModalCreateOpen = true"
  >
    <section class="flex w-full justify-between items-center">
      <h1 class="font-bold text-2xl">Dashboard</h1>

      <div class="flex gap-2 items-center">
        <Button
          @click="router.reload({ only: ['episodes'] })"
          variant="outline-base"
          class="size-8 !p-0 text-slate-500 hover:text-black"
          title="Reload data"
        >
          <ArrowPathIcon class="size-5" />
        </Button>
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
          <span class="ml-2">Add Episode</span>
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
            v-for="episode in props.episodes.data"
            class="px-3 py-2 border-b relative"
            :key="episode.series.id"
          >
            <div>
              <img
                :src="getImage(episode.series.thumbnail)"
                alt="Name"
                class="rounded-full border size-10 object-cover"
              />
            </div>
            <Link
              :href="route('series.show', episode.series.slug)"
              class="overflow-hidden line-clamp-2"
              :title="episode.series.title"
            >
              <span class="block w-full my-2">
                {{ episode.series.title }}
              </span>
            </Link>
            <div class="!hidden sm:!flex">
              <BadgeStatus
                :title="episode.series.status.name"
                :color="episode.series.status.color"
              />
            </div>
            <div class="!hidden md:!flex">
              {{ episode.series?.category?.name }}
            </div>
            <div class="!hidden min-[500px]:!flex">
              {{ episode.series?.latest_episode?.number || "-" }}
            </div>
            <div class="justify-center gap-2">
              <div class="sm:hidden">
                <Actions
                  content-class="!max-w-52"
                  @click:edit="openModal(episode, 'edit')"
                  @click:delete="openModal(episode, 'delete')"
                />
              </div>
              <Button
                @click="openModal(episode, 'edit')"
                variant="warning"
                class="hidden sm:flex size-8 !p-0"
              >
                <PencilSquareIcon class="size-4" />
              </Button>
              <Button
                @click="openModal(episode, 'delete')"
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
            : 'lg:grid-cols-6 xl:grid-cols-7'
        "
      >
        <TransitionGroup name="list">
          <template v-for="episode in props.episodes.data" :key="episode.id">
            <div class="w-full flex flex-col gap-1">
              <div class="w-full relative flex justify-end">
                <Actions
                  trigger-class="translate-y-1"
                  @click:edit="openModal(episode, 'edit')"
                  @click:delete="openModal(episode, 'delete')"
                />
              </div>
              <div class="relative size-full h-56 overflow-hidden rounded-lg">
                <Link :href="route('series.show', episode.series.slug)">
                  <img
                    :src="getImage(episode.series.thumbnail)"
                    :alt="episode.series.title"
                    class="object-cover border size-full duration-300 hover:scale-105 rounded-lg"
                  />
                </Link>
                <BadgeStatus
                  :title="episode.series.status.name"
                  :color="episode.series.status.color"
                  class="absolute top-2 right-2 px-5 sm:px-0"
                />
                <div
                  class="flex gap-2 items-center py-1 px-3 absolute bottom-2 left-2 text-white bg-amber-500 shadow text-xs rounded-md font-medium"
                >
                  <StarIcon class="size-4" />
                  {{ episode.series.rating }}
                </div>
              </div>
              <Link
                :href="route('series.show', episode.series.slug)"
                class="flex flex-col gap-2 mt-1"
              >
                <h2
                  class="font-semibold leading-4"
                  :title="episode.series.latest_episode.number"
                >
                  Current
                  {{ episode.series.media == "video" ? "Episode" : "Chapter" }}
                  {{ episode.series.latest_episode.number }}
                </h2>
                <h2
                  class="font-medium leading-4 text-sm opacity-50"
                  :title="episode.title"
                >
                  {{ episode.series.title }}
                </h2>
              </Link>
            </div>
          </template>
        </TransitionGroup>
      </div>
    </section>

    <Paginations :pagination="props.episodes" />
    <ModalCreate
      :is-open="isModalCreateOpen"
      @close="isModalCreateOpen = false"
      :options="props.series"
      :data="dashboardData"
    />
    <ModalEdit
      :is-open="isModalEditOpen"
      @close="isModalEditOpen = false"
      :data="dashboardData"
      :options="props.series"
    />
    <ModalDelete
      :is-open="isModalDeleteOpen"
      @close="isModalDeleteOpen = false"
      :data="dashboardData"
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
