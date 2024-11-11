<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link, router } from "@inertiajs/vue3";
import {
  XMarkIcon,
  BookOpenIcon as BookOpenIconSolid,
  GlobeAltIcon as GlobeAltIconSolid,
  HomeIcon as HomeIconSolid,
  NewspaperIcon as NewspaperIconSolid,
} from "@heroicons/vue/20/solid";
import {
  BookOpenIcon,
  GlobeAltIcon,
  HomeIcon,
  NewspaperIcon,
} from "@heroicons/vue/24/outline";
import Button from "@/Components/Buttons/Button.vue";
import { useSidebar } from "@/Stores";
import { onMounted } from "vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";

const { isOpen } = useSidebar();
const featureLinks = [
  {
    title: "Home",
    icon: HomeIcon,
    activeIcon: HomeIconSolid,
    href: route("episode.index"),
    url: "/dashboard",
  },
  {
    title: "Series",
    icon: BookOpenIcon,
    activeIcon: BookOpenIconSolid,
    href: route("series.index"),
    url: "/series",
  },
  {
    title: "Status",
    icon: NewspaperIcon,
    activeIcon: NewspaperIconSolid,
    href: route("status.index"),
    url: "/status",
  },
  {
    title: "Category",
    icon: GlobeAltIcon,
    activeIcon: GlobeAltIconSolid,
    href: route("category.index"),
    url: "/category",
  },
];

if (window.innerWidth < 1024) {
  router.on("finish", () => {
    isOpen.value = false;
  });
} else if (window.innerWidth > 1024) {
  isOpen.value = true;
}
</script>

<template>
  <aside
    class="flex flex-col gap-2 w-64 fixed bg-white lg:bg-slate-100 inset-y-0 h-full lg:static overflow-x-hidden py-3 border-none duration-300 z-50"
    :class="{
      'left-0 lg:max-w-64 px-3 shadow lg:shadow-none': isOpen,
      '-left-64 lg:max-w-0 px-0': !isOpen,
    }"
  >
    <div class="flex items-center justify-between lg:justify-center mb-5">
      <div
        class="flex items-center lg:justify-center font-bold text-xl text-violet-600 gap-2"
      >
        <ApplicationLogo class="block size-8 w-auto flex-none" />
        <h3>OtakuNote</h3>
      </div>

      <Button class="lg:hidden !size-9 !p-0" @click="isOpen = false">
        <XMarkIcon class="size-5 opacity-50" />
      </Button>
    </div>

    <div class="flex flex-col gap-1">
      <span class="text-sm text-slate-400 flex items-center">
        <small>Features</small>
        <ChevronDownIcon class="size-3" />
      </span>
      <template v-for="link in featureLinks" :key="link.title">
        <Link
          :href="link.href"
          class="sidebar-link !hidden sm:!flex"
          :class="$page.url === link.url ? 'link-active' : ''"
        >
          <component
            :is="$page.url === link.url ? link.activeIcon : link.icon"
            class="size-6 flex-none"
          />
          <p>{{ link.title }}</p>
        </Link>
      </template>
    </div>
  </aside>
</template>
