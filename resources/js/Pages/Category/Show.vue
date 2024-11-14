<script setup>
import BadgeStatus from "@/Components/BadgeStatus.vue";
import Button from "@/Components/Buttons/Button.vue";
import { getImage } from "@/Composables/getImage";
import { useOpenModal } from "@/Composables/openModal";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useLayout, useSidebar } from "@/Stores";
import {
  ArrowLeftIcon,
  QueueListIcon,
  Squares2X2Icon,
  StarIcon as StarIconSolid,
} from "@heroicons/vue/20/solid";
import { StarIcon } from "@heroicons/vue/24/outline";
import { Head, Link } from "@inertiajs/vue3";
import ModalCreate from "./Partials/ModalCreate.vue";

const props = defineProps({
  category: { type: Object, default: {} },
});
const { isModalCreateOpen } = useOpenModal();
const { isGrid, toggleGrid } = useLayout("seriesLayout");
const { isOpen } = useSidebar();

const back = () => {
  window.history.back();
};
</script>

<template>
  <Head :title="props.category.name" />
  <AuthenticatedLayout @click:add-btn="isModalCreateOpen = true">
    <section class="flex w-full justify-between items-center">
      <div class="flex gap-3">
        <button class="bg-white" @click="back()">
          <ArrowLeftIcon class="size-4" />
        </button>
        <h1 class="font-bold text-2xl">{{ props.category.name }}</h1>
      </div>

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
      </div>
    </section>

    <p
      class="opacity-50 text-sm mt-3"
      v-if="props.category.description !== null"
    >
      {{ props.category.description }}
    </p>

    <section class="w-full mt-3">
      <div
        v-if="!isGrid"
        class="w-full *:grid *:grid-cols-[3.5rem_1fr_1fr] min-[500px]:*:grid-cols-[3.5rem_1fr_1fr] sm:*:grid-cols-[3.5rem_1fr_1fr_1fr] min-[500px]:*:gap-2 [&>div]:*:flex [&>div]:*:items-center"
      >
        <div
          class="bg-slate-200 px-3 py-2 rounded-md font-medium *:text-nowrap"
        >
          <div>#</div>
          <div>Title</div>
          <div class="!hidden sm:!flex">Status</div>
          <div class="!justify-center">Last Episode</div>
        </div>

        <TransitionGroup name="list">
          <div
            v-for="series in props.category.series"
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
            <div class="!justify-center">
              {{ series.latest_episode?.number || "-" }}
            </div>
          </div>
        </TransitionGroup>
      </div>
      <div
        v-else
        class="grid max-[200px]:grid-cols-1 grid-cols-2 min-[450px]:grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2"
        :class="
          isOpen
            ? 'lg:grid-cols-5 xl:grid-cols-6'
            : 'lg:grid-cols-7 xl:grid-cols-8'
        "
      >
        <TransitionGroup name="list">
          <template v-for="series in props.category.series" :key="series.id">
            <div class="w-full flex flex-col gap-1">
              <div class="relative w-full h-56 overflow-hidden rounded-md">
                <Link :href="route('series.show', series.slug)">
                  <img
                    :src="getImage(series.thumbnail)"
                    :alt="series.title"
                    class="object-cover border size-full duration-300 hover:scale-105"
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
    <section
      v-if="props.category.series.length === 0"
      class="w-full text-center opacity-50 font-medium mt-10"
    >
      <p>No series yet, creat one!</p>
    </section>
  </AuthenticatedLayout>
  <ModalCreate
    :is-open="isModalCreateOpen"
    @close="isModalCreateOpen = false"
  />
</template>
