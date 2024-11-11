<script setup>
import BadgeStatus from "@/Components/BadgeStatus.vue";
import { getImage } from "@/Composables/getImage";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { StarIcon } from "@heroicons/vue/16/solid";
import { ArrowLeftIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import ModalEpisodeCreate from "./Partials/ModalEpisodeCreate.vue";
import ActionsEpisode from "./Partials/ActionsEpisode.vue";
import ModalDelete from "../Episode/Partials/ModalDelete.vue";
import { useOpenModal } from "@/Composables/openModal";
import ModalEpisodeEdit from "./Partials/ModalEpisodeEdit.vue";
import Button from "@/Components/Buttons/Button.vue";
import { PlusIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
  series: { type: Object },
  listSeries: { type: Array },
});
const isDescShow = ref(false);
const {
  isModalCreateOpen,
  isModalEditOpen,
  isModalDeleteOpen,
  dataModal: episodeData,
  modalTrigger: openModal,
} = useOpenModal();

const back = () => {
  window.history.back();
};
</script>

<template>
  <Head :title="props.series.title" />
  <AuthenticatedLayout
    main-class="relative"
    @click:add-btn="isModalCreateOpen = true"
  >
    <button
      class="bg-white absolute top-2 left-2 z-20 px-2 py-1 rounded"
      @click="back()"
    >
      <ArrowLeftIcon class="size-3" />
    </button>
    <img
      :src="getImage(series.thumbnail)"
      :alt="props.series.title"
      class="w-full h-64 object-cover absolute left-0 top-0"
    />
    <div
      class="flex items-center h-64 gap-2 justify-center backdrop-blur-sm absolute top-0 left-0 w-full bg-gradient-to-t from-white/100 to-transparent"
    >
      <div class="w-36 h-48 relative">
        <img
          :src="getImage(series.thumbnail)"
          :alt="props.series.title"
          class="w-36 h-48 object-cover rounded-lg shadow"
        />
        <BadgeStatus
          class="absolute !text-xs top-2 left-2 shadow px-2"
          :title="props.series.status.name"
          :color="props.series.status.color"
        />
      </div>
    </div>

    <div
      class="flex justify-center sm:justify-between items-start mt-56 z-30 relative translate-y-1"
    >
      <div class="flex flex-col gap-1 text-center sm:text-start">
        <h1 class="text-lg font-medium leading-6 tracking-[0.3px]">
          {{ props.series.title }}
        </h1>
        <div
          class="flex justify-center sm:justify-start items-center gap-2 text-center *:text-sm"
        >
          <small class="font-medium opacity-80">
            {{ props.series.category.name }}
          </small>
          <span class="opacity-80">•</span>
          <div class="flex">
            <StarIcon class="size-4 text-yellow-400" />
            <span class="font-medium opacity-80">
              {{ series.rating }}
            </span>
          </div>
        </div>
      </div>
      <Button
        variant="primary"
        class="h-8 pr-4 pl-3 hidden sm:flex"
        @click="isModalCreateOpen = true"
      >
        <PlusIcon class="size-5" />
        <span class="ml-2">Add Episode</span>
      </Button>
    </div>

    <div
      class="mt-5 grid max-[300px]:grid-cols-1 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:space-y-0"
    >
      <template
        v-for="episode in series.episodes"
        v-if="series.episodes.length > 0"
      >
        <div
          class="font-medium bg-slate-100 p-3 w-full flex justify-between gap-2 rounded-md border border-slate-200 relative"
        >
          <div>
            {{ props.series.media == "video" ? "Episode" : "Chapter" }}
            {{ episode.number }}
          </div>

          <ActionsEpisode
            content-class="!max-w-52"
            @click:edit="openModal(episode, 'edit')"
            @click:delete="openModal(episode, 'delete')"
          />
        </div>
      </template>
      <div
        v-else
        class="block col-span-2 sm:col-span-3 md:col-span-4 lg:col-span-5 xl:col-span-6"
      >
        <p class="font-medium text-center opacity-50">
          No {{ props.series.media == "video" ? "Episode" : "Chapter" }} yet,
          create one !
        </p>
      </div>
    </div>

    <ModalEpisodeCreate
      :is-open="isModalCreateOpen"
      @close="isModalCreateOpen = false"
      :options="props.series"
      :data="props.series"
    />
    <ModalEpisodeEdit
      :is-open="isModalEditOpen"
      @close="isModalEditOpen = false"
      :data="episodeData"
      :options="props.series"
    />
    <ModalDelete
      :is-open="isModalDeleteOpen"
      @close="isModalDeleteOpen = false"
      :data="episodeData"
    />
  </AuthenticatedLayout>
</template>
