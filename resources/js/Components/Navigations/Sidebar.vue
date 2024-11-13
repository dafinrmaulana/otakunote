<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Button from "@/Components/Buttons/Button.vue";
import { useLayout, useSidebar } from "@/Stores";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";
import {
  BookOpenIcon as BookOpenIconSolid,
  GlobeAltIcon as GlobeAltIconSolid,
  HomeIcon as HomeIconSolid,
  NewspaperIcon as NewspaperIconSolid,
  XMarkIcon,
} from "@heroicons/vue/20/solid";
import {
  BookOpenIcon,
  GlobeAltIcon,
  HomeIcon,
  NewspaperIcon,
} from "@heroicons/vue/24/outline";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const { isOpen } = useSidebar();
const { isGrid: isSeriesOpen, toggleGrid: toggleSeries } =
  useLayout("linkSeries");
const { isGrid: isCategoriesOpen, toggleGrid: toggleCategoriesOpen } =
  useLayout("linkCategories");
const { isGrid: isStatusesOpen, toggleGrid: toggleStatusesOpen } =
  useLayout("linkCategories");
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
    title: "Type",
    icon: GlobeAltIcon,
    activeIcon: GlobeAltIconSolid,
    href: route("category.index"),
    url: "/category",
  },
];
const series = computed(() => page.props.links.pinned_series);
const categories = computed(() => page.props.links.pinned_categories);
const statuses = computed(() => page.props.links.pinned_statuses);

if (window.innerWidth < 1024) {
  router.on("finish", () => {
    isOpen.value = false;
  });
}
</script>

<template>
  <aside
    class="flex flex-col gap-2 w-64 fixed bg-white lg:bg-slate-100 inset-y-0 max-h-dvh lg:static overflow-x-hidden overflow-y-auto py-3 border-none duration-300 z-50"
    :class="{
      'left-0 lg:max-w-64 px-3 shadow lg:shadow-none': isOpen,
      '-left-64 lg:max-w-0 px-0': !isOpen,
    }"
  >
    <div class="flex items-center justify-between lg:justify-center sm:mb-5">
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
      <span class="hidden text-slate-400 sm:flex items-center">
        <small>Features</small>
        <ChevronDownIcon class="size-4" />
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
          <p class="flex-1">{{ link.title }}</p>
        </Link>
      </template>
      <template v-if="series.length > 0">
        <Disclosure :default-open="isSeriesOpen">
          <DisclosureButton
            @click="toggleSeries"
            class="text-slate-400 flex items-center mt-3"
          >
            <small>Series</small>
            <ChevronDownIcon
              class="size-4 duration-150"
              :class="!isSeriesOpen ? '-rotate-90' : ''"
            />
          </DisclosureButton>
          <transition name="list">
            <div v-show="isSeriesOpen">
              <DisclosurePanel static>
                <Link
                  v-for="link in series"
                  :key="link.title"
                  :href="route('series.show', link.slug)"
                  class="sidebar-link-no-res mt-1"
                  :class="
                    $page.url === `/series/${link.slug}` ? 'link-active' : ''
                  "
                >
                  <div class="size-4 bg-violet-600 rounded-full flex-none" />
                  <p class="line-clamp-1 flex-1">{{ link.title }}</p>
                </Link>
              </DisclosurePanel>
            </div>
          </transition>
        </Disclosure>
      </template>
      <template v-if="statuses.length > 0">
        <Disclosure>
          <DisclosureButton
            @click="toggleStatusesOpen"
            class="flex items-center mt-3 text-slate-400"
          >
            <small>Statuses</small>
            <ChevronDownIcon
              class="size-4 duration-150"
              :class="!isStatusesOpen ? '-rotate-90' : ''"
            />
          </DisclosureButton>
          <transition name="list">
            <div v-show="isStatusesOpen">
              <DisclosurePanel static>
                <Link
                  v-for="link in statuses"
                  :key="link.name"
                  :href="route('category.show', link.slug)"
                  class="sidebar-link-no-res mt-1"
                  :class="
                    $page.url === `/status/${link.slug}` ? 'link-active' : ''
                  "
                >
                  <div
                    class="size-4 rounded-full flex-none"
                    :style="{ backgroundColor: link.color }"
                  />
                  <p class="line-clamp-1 flex-1">{{ link.name }}</p>
                </Link>
              </DisclosurePanel>
            </div>
          </transition>
        </Disclosure>
      </template>
      <template v-if="categories.length > 0">
        <Disclosure>
          <DisclosureButton
            @click="toggleCategoriesOpen"
            class="flex items-center mt-3 text-slate-400"
          >
            <small>Categories</small>
            <ChevronDownIcon
              class="size-4 duration-150"
              :class="!isCategoriesOpen ? '-rotate-90' : ''"
            />
          </DisclosureButton>
          <transition name="list">
            <div v-show="isCategoriesOpen">
              <DisclosurePanel static>
                <Link
                  v-for="link in categories"
                  :key="link.name"
                  :href="route('category.show', link.slug)"
                  class="sidebar-link-no-res mt-1"
                  :class="
                    $page.url === `/category/${link.slug}` ? 'link-active' : ''
                  "
                >
                  <div
                    class="size-4 rounded-full flex-none"
                    :style="{ backgroundColor: link.color }"
                  />
                  <p class="line-clamp-1 flex-1">{{ link.name }}</p>
                </Link>
              </DisclosurePanel>
            </div>
          </transition>
        </Disclosure>
      </template>
    </div>
  </aside>
</template>
