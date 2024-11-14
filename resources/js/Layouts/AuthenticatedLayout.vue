<script setup>
import BottomNav from "@/Components/Navigations/BottomNav.vue";
import Sidebar from "@/Components/Navigations/Sidebar.vue";
import TopNav from "@/Components/Navigations/TopNav.vue";
import ToastNotification from "@/Components/ToastNotification.vue";
import { useSidebar } from "@/Stores";

const { isOpen } = useSidebar();

const props = defineProps({
  mainClass: { type: String, default: "" },
});

const emits = defineEmits(["click:add-btn"]);
</script>

<template>
  <ToastNotification />
  <div class="min-h-dvh bg-slate-100 flex">
    <Sidebar />

    <!-- backdrop sidebar -->
    <transition name="fade-on-sm">
      <div
        v-if="isOpen"
        class="bg-black/40 backdrop-blur-sm lg:hidden fixed inset-0 size-full z-40"
        @click="isOpen = false"
      />
    </transition>

    <div
      class="bg-white lg:rounded-l-3xl lg:border-l border-slate-300 flex-1 h-dvh pb-14 overflow-y-auto flex flex-col"
    >
      <!-- navbar top -->
      <TopNav />

      <!-- main content -->
      <main class="p-3 sm:p-5 flex-1 w-full" :class="props.mainClass">
        <slot />
      </main>

      <!-- navbar bottom -->
      <BottomNav @click:add-btn="emits('click:add-btn')" />
    </div>
  </div>
</template>
