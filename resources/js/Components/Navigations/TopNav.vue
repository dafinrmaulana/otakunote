<script setup>
import { useSidebar } from "@/Stores";
import { Bars3Icon, MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import Button from "../Buttons/Button.vue";
import DropdpownProfile from "../Dropdown/DropdpownProfile.vue";

const { toggleOpen } = useSidebar();
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
        @click="toggleOpen"
      >
        <MagnifyingGlassIcon class="size-5" />
        <p>Search...</p>
      </button>
    </div>

    <DropdpownProfile />
  </nav>
</template>
