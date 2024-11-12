<script setup>
import { CheckIcon, XMarkIcon } from "@heroicons/vue/16/solid";
import { usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import Button from "./Buttons/Button.vue";

const isShow = ref(false);
const page = computed(() => usePage());
let notifCooldown;
const iconKey = ref(Date.now());

watch(
  () => page.value.props.session,
  () => {
    if (
      page.value.props.session.success ||
      page.value.props.session.failed ||
      page.value.props.session.info ||
      page.value.props.session.warning
    ) {
      if (notifCooldown) {
        clearTimeout(notifCooldown);
      }
      isShow.value = true;
      iconKey.value = Date.now();
      notifCooldown = setTimeout(() => {
        isShow.value = false;
      }, 3500);
    }
  }
);

const stopTimeOut = () => clearTimeout(notifCooldown);
const restartTimeOut = () => {
  notifCooldown = setTimeout(() => {
    isShow.value = false;
  }, 3500);
};
</script>

<template>
  <transition name="fade-responsive">
    <div
      v-if="isShow"
      class="fixed top-5 left-1/2 -translate-x-1/2 w-11/12 min-[450px]:w-8/12 sm:w-1/2 max-w-sm sm:top-auto sm:bottom-5 sm:left-auto sm:translate-x-0 sm:right-5 sm:shadow-lg flex gap-2 items-center bg-white rounded-lg border px-3 py-4 z-50 justify-between shadow-sm"
      @mouseenter="stopTimeOut"
      @mouseleave="restartTimeOut"
    >
      <div class="flex gap-3 items-center">
        <div
          class="icon"
          :class="{
            'animate-icon': page.props.session?.success,
            'animate-shake':
              page.props.session?.info ||
              page.props.session?.warning ||
              page.props.session?.failed,
            'icon-success': page.props.session?.success,
            'icon-failed': page.props.session?.failed,
            'icon-warning': page.props.session?.warning,
            'icon-info': page.props.session?.info,
          }"
          :key="iconKey"
        >
          <component
            :is="page.props.session?.success ? CheckIcon : XMarkIcon"
            class="size-full"
          />
        </div>
        <h3 class="capitalize font-medium line-clamp-2">
          {{
            page.props.session?.success ||
            page.props.session?.info ||
            page.props.session?.warning ||
            page.props.session?.failed
          }}
        </h3>
      </div>
      <Button class="!size-6 !p-1 flex-none" @click="isShow = false">
        <XMarkIcon class="size-full opacity-40" />
      </Button>
    </div>
  </transition>
</template>

<style lang="postcss" scoped>
.icon {
  @apply size-6 text-white rounded-full grid place-items-center p-1 !flex-none;
}
.icon-success {
  @apply bg-green-500;
}
.icon-failed {
  @apply bg-red-600;
}
.icon-warning {
  @apply bg-amber-400;
}
.icon-info {
  @apply bg-sky-400;
}
</style>
