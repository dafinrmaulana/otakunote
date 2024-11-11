<script setup>
import Button from "@/Components/Buttons/Button.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  BriefcaseIcon,
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
import { Head, useForm } from "@inertiajs/vue3";
import { useDateFormat } from "@vueuse/core";
import { ref } from "vue";
import ModalCreate from "./Partials/ModalCreate.vue";
import Actions from "./Partials/Actions.vue";
import ModalEdit from "./Partials/ModalEdit.vue";
import ModalDelete from "./Partials/ModalDelete.vue";
import { useLayout } from "@/Stores";
import { useOpenModal } from "@/Composables/openModal";

const { isGrid, toggleGrid } = useLayout("genreLayout");
const props = defineProps({
  genres: { type: Object },
});
const {
  isModalCreateOpen,
  isModalEditOpen,
  isModalDeleteOpen,
  dataModal: genreData,
  modalTrigger: openModal,
} = useOpenModal();

const form = useForm({
  name: "",
  slug: "",
  color: "#7c3aed",
  description: "",
  is_pinned: false,
});

const updatePin = (genre) => {
  genreData.value = genre;

  form
    .transform((data) => ({
      name: genreData.value.name,
      slug: genreData.value.slug,
      color: genreData.value.color,
      description: genreData.value.description,
      is_pinned: !genreData.value.is_pinned,
    }))
    .put(route("genre.update", genreData.value.id), {
      onSuccess: () => {
        isModalEditOpen.value = false;
      },
    });
};
</script>

<template>
  <Head title="Genre" />
  <AuthenticatedLayout
    main-class="space-y-5"
    @click:add-btn="isModalCreateOpen = true"
  >
    <section class="flex w-full justify-between items-center">
      <h1 class="font-bold text-2xl">Genre</h1>

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
          <span class="ml-2">Add Genre</span>
        </Button>
      </div>
    </section>

    <section class="w-full">
      <table v-if="!isGrid" class="w-full table-auto">
        <thead>
          <tr
            class="*:text-start *:font-medium text-gray-700 bg-slate-100 *:p-3 *:text-nowrap"
          >
            <th class="rounded-l-lg max-w-10">#</th>
            <th>Name</th>
            <th class="hidden min-[470px]:table-cell">Total Series</th>
            <th class="hidden min-[400px]:table-cell">Last Modified</th>
            <th class="rounded-r-lg !text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <TransitionGroup name="list">
            <tr
              v-for="genre in props.genres.data"
              :key="genre.id"
              class="*:p-3 *:align-middle relative border-b"
            >
              <td>
                <div
                  class="size-4 rounded-full border"
                  :style="{ backgroundColor: genre.color }"
                />
              </td>
              <td>
                {{ genre.name }}
              </td>
              <td
                class="text-sm hidden min-[470px]:table-cell text-gray-700 font-medium"
              >
                20
              </td>
              <td
                class="text-sm hidden min-[400px]:table-cell text-gray-700 font-medium"
              >
                {{ useDateFormat(genre.updated_at, "DD MMM YYYY").value }}
              </td>
              <td class="flex justify-end md:justify-center gap-2">
                <div class="sm:hidden">
                  <Actions
                    content-class="!max-w-40"
                    @click:edit="openModal(genre, 'edit')"
                    @click:delete="openModal(genre, 'delete')"
                    @click:pin="updatePin(genre)"
                    :is-pinned="genre.is_pinned"
                  />
                </div>
                <Button
                  @click="updatePin(genre)"
                  class="hidden sm:flex size-8 !p-0"
                >
                  <component
                    :is="genre.is_pinned == true ? StarIconSolid : StarIcon"
                    class="size-4"
                    :class="
                      genre.is_pinned == true
                        ? 'text-amber-400'
                        : 'text-gray-400'
                    "
                  />
                </Button>
                <Button
                  @click="openModal(genre, 'edit')"
                  variant="warning"
                  class="hidden sm:flex size-8 !p-0"
                >
                  <PencilSquareIcon class="size-4" />
                </Button>
                <Button
                  @click="openModal(genre, 'delete')"
                  variant="danger"
                  class="hidden sm:flex size-8 !p-0"
                >
                  <TrashIcon class="size-4" />
                </Button>
              </td>
            </tr>
          </TransitionGroup>
        </tbody>
      </table>
      <div class="grid-layout" v-else>
        <TransitionGroup name="list">
          <div
            class="flex flex-col gap-2 justify-start items-center p-3 rounded-md duration-200 relative"
            v-for="genre in props.genres.data"
            :key="genre.id"
          >
            <component
              :is="genre.is_pinned == true ? StarIconSolid : StarIcon"
              class="size-4 absolute top-2 left-2"
              :class="
                genre.is_pinned == true ? 'text-amber-400' : 'text-gray-400'
              "
            />
            <BriefcaseIcon class="size-9 text-gray-500 mt-4" />
            <div class="flex justify-between items-center w-full">
              <div class="flex flex-col text-start">
                <h3 class="text-sm font-semibold">{{ genre.name }}</h3>
                <small class="text-xs opacity-50">20 Series</small>
              </div>
              <Actions
                trigger-class="translate-y-1"
                @click:edit="openModal(genre, 'edit')"
                @click:delete="openModal(genre, 'delete')"
                @click:pin="updatePin(genre)"
                :is-pinned="genre.is_pinned"
              />
            </div>
          </div>
        </TransitionGroup>
      </div>
    </section>
    <ModalCreate
      :is-open="isModalCreateOpen"
      @close="isModalCreateOpen = false"
    />
    <ModalEdit
      :is-open="isModalEditOpen"
      @close="isModalEditOpen = false"
      :data="genreData"
    />
    <ModalDelete
      :is-open="isModalDeleteOpen"
      @close="isModalDeleteOpen = false"
      :data="genreData"
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
