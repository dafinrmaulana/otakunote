<script setup>
import { getImage } from "@/Composables/getImage";
import { useSidebar } from "@/Stores";
import {
  Bars3Icon,
  MagnifyingGlassIcon,
  StarIcon,
  XMarkIcon,
} from "@heroicons/vue/20/solid";
import { Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { ref, watchEffect } from "vue";
import Button from "../Buttons/Button.vue";
import DialogModal from "../Dialog/DialogModal.vue";
import DropdpownProfile from "../Dropdown/DropdpownProfile.vue";
import SearchLoading from "../SearchLoading.vue";

const { toggleOpen } = useSidebar();
const isModalSearchOpen = ref(false);
const form = useForm({
  query: "",
  by: "all",
});
const result = ref({
  series: [],
  categories: [],
  statuses: [],
});
const isSearching = ref(false);

const handleSearch = async () => {
  isSearching.value = true;
  if (form.query === "") {
    isSearching.value = false;
    return (result.value = {
      series: [],
      categories: [],
      statuses: [],
    });
  }
  try {
    const res = await axios.get("/search", {
      params: form,
    });
    result.value = res.data;
  } catch (error) {
    throw new Error(error);
  } finally {
    isSearching.value = false;
  }
};

const closeModalSearch = () => {
  isModalSearchOpen.value = false;
  form.reset();
  result.value = null;
};

const handleType = (type) => {
  form.by = type;
  handleSearch();
};
</script>

<template>
  <nav
    class="border-b w-full py-3 px-3 sm:px-5 flex items-center justify-between gap-3 flex-none"
  >
    <Button class="!size-9 !p-2" @click="toggleOpen">
      <Bars3Icon class="size-full" />
    </Button>

    <div class="flex-1 flex justify-center items-center">
      <button
        class="flex input !rounded-full text-slate-400 bg-slate-100 border border-slate-200 font-medium items-center w-full sm:w-8/12 lg:w-5/12 justify-center gap-1 h-8"
        @click="isModalSearchOpen = true"
      >
        <MagnifyingGlassIcon class="size-5" />
        <p>Search...</p>
      </button>
    </div>

    <DropdpownProfile />
  </nav>

  <DialogModal :is-open="isModalSearchOpen" @close="isModalSearchOpen = false">
    <div
      class="flex flex-col h-[98%] bg-white w-full md:max-w-xl rounded-t-lg sm:rounded-b-lg overflow-y-auto sm:pb-5"
    >
      <form
        @submit.prevent="handleSearch"
        class="flex gap-1 items-center border-b pt-2 px-3 pb-1 sm:pb-2 relative"
      >
        <MagnifyingGlassIcon class="size-5 text-slate-500" />
        <input
          type="text"
          name="search"
          id="search"
          class="outline-none border-none h-full focus-within:border-none focus-within:outline-none focus-within:ring-0 flex-1"
          placeholder="Search..."
          v-model="form.query"
        />
        <Button class="!size-8 !p-0" @click="isModalSearchOpen = false">
          <XMarkIcon class="size-5 text-slate-500" />
        </Button>
        <button type="submit" class="hidden"></button>
      </form>
      <div class="flex gap-2 items-center mt-2 px-2 *:text-sm *:flex-1">
        <Button
          variant="outline-base"
          :disabled="form.by === 'all'"
          @click="handleType('all')"
        >
          All
        </Button>
        <Button
          variant="outline-base"
          :disabled="form.by === 'series'"
          @click="handleType('series')"
        >
          Series
        </Button>
        <Button
          variant="outline-base"
          :disabled="form.by === 'type'"
          @click="handleType('type')"
        >
          Type
        </Button>
        <Button
          variant="outline-base"
          :disabled="form.by === 'statuses'"
          @click="handleType('statuses')"
        >
          Status
        </Button>
      </div>
      <div class="flex-1 flex h-full flex-col gap-5 px-2 mt-3">
        <div
          class="flex flex-col gap-1 items-start"
          v-if="result?.series?.length > 0 && !isSearching"
        >
          <h5 class="text-slate-400">Series</h5>
          <Link
            v-for="series in result.series"
            :href="route('series.show', series.slug)"
            @click="closeModalSearch"
            class="flex gap-2 items-center hover:bg-slate-100 p-2 rounded-md border w-full"
          >
            <img
              :src="getImage(series.thumbnail)"
              :alt="series.title"
              class="size-10 rounded-full object-cover flex-none"
            />
            <div class="flex flex-col items-start text-start">
              <h1 class="font-medium capitalize">{{ series.title }}</h1>
              <span class="text-sm flex items-center gap-1">
                <StarIcon class="size-5 text-amber-400" />
                {{ series.rating }}
              </span>
            </div>
          </Link>
        </div>
        <div
          class="flex flex-col gap-1 items-start"
          v-if="result?.statuses?.length > 0 && !isSearching"
        >
          <h5 class="text-slate-400">Status</h5>
          <Link
            v-for="status in result.statuses"
            :href="route('status.show', status.slug)"
            @click="closeModalSearch"
            class="flex gap-2 items-center hover:bg-slate-100 p-2 rounded-md border w-full"
          >
            <div
              class="size-5 rounded-full object-cover flex-none"
              :style="`background-color: ${status.color}`"
            />
            <div class="flex flex-col items-start text-start">
              <h1 class="font-medium">{{ status.name }}</h1>
              <small>Total Series {{ status.series.length }}</small>
            </div>
          </Link>
        </div>
        <div
          class="flex flex-col gap-1 items-start"
          v-if="result?.categories?.length > 0 && !isSearching"
        >
          <h5 class="text-slate-400">Type</h5>
          <Link
            v-for="category in result.categories"
            :href="route('category.show', category.slug)"
            @click="closeModalSearch"
            class="flex gap-2 items-center hover:bg-slate-100 p-2 rounded-md border w-full"
          >
            <div
              class="size-5 rounded-full object-cover flex-none"
              :style="`background-color: ${category.color}`"
            />
            <div class="flex flex-col items-start text-start">
              <h1 class="font-medium">{{ category.name }}</h1>
              <small>Total Series {{ category.series.length }}</small>
            </div>
          </Link>
        </div>
        <div
          v-else-if="
            !isSearching &&
            (!result?.series || result.series.length === 0) &&
            (!result?.categories || result.categories.length === 0) &&
            (!result?.statuses || result.statuses.length === 0)
          "
          class="text-center size-full grid place-items-center"
        >
          <h1 class="text-slate-500 font-medium">
            Oops, Nothing in here. Try another keyword!
          </h1>
        </div>
        <div
          v-if="isSearching"
          class="text-center size-full grid place-items-center"
        >
          <SearchLoading class="!text-base" />
        </div>
      </div>
    </div>
  </DialogModal>
</template>
