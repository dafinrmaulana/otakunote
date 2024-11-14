<script setup>
import {
  BookOpenIcon as BookOpenIconSolid,
  GlobeAltIcon as GlobeAltIconSolid,
  HomeIcon as HomeIconSolid,
  NewspaperIcon as NewspaperIconSolid,
  PlusIcon,
} from "@heroicons/vue/20/solid";
import {
  BookOpenIcon,
  GlobeAltIcon,
  HomeIcon,
  NewspaperIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

const emits = defineEmits(["click:add-btn"]);
</script>

<template>
  <nav
    class="flex sm:hidden fixed bottom-0 inset-x-0 bg-slate-100 border-t rounded-t-2xl items-center justify-center flex-none h-14 gap-7 max-[320px]:gap-2 max-[320px]:justify-between max-[320px]:px-3"
  >
    <Link
      :href="route('episode.index')"
      class="sidebar-link"
      :class="$page.url === '/dashboard' ? 'link-active' : ''"
    >
      <component
        :is="$page.url === '/dashboard' ? HomeIconSolid : HomeIcon"
        class="size-6 flex-none"
      />
      <p>Home</p>
    </Link>
    <Link
      :href="route('series.index')"
      class="sidebar-link"
      :class="$page.url === '/series' ? 'link-active' : ''"
    >
      <component
        :is="$page.url === '/series' ? BookOpenIconSolid : BookOpenIcon"
        class="size-6 flex-none"
      />
      <p>Series</p>
    </Link>

    <button
      class="bg-indigo-500 rounded-full flex-none size-9 p-2 grid place-items-center -translate-y-1"
      @click="emits('click:add-btn')"
    >
      <PlusIcon class="text-white size-full" />
    </button>
    <Link
      :href="route('category.index')"
      class="sidebar-link"
      :class="$page.url === '/category' ? 'link-active' : ''"
    >
      <component
        :is="$page.url === '/category' ? GlobeAltIconSolid : GlobeAltIcon"
        class="size-6 flex-none"
      />
      <p>Type</p>
    </Link>
    <Link
      :href="route('status.index')"
      class="sidebar-link"
      :class="$page.url === '/status' ? 'link-active' : ''"
    >
      <component
        :is="$page.url === '/status' ? NewspaperIconSolid : NewspaperIcon"
        class="size-6 flex-none"
      />
      <p>Status</p>
    </Link>
  </nav>
</template>
