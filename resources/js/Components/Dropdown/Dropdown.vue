<script setup>
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";

const props = defineProps({
  placement: { type: String, default: "bottom" },
  contentClass: { type: String, default: "" },
});
</script>

<template>
  <Menu>
    <MenuButton as="button" type="button" class="focus-within:outline-none">
      <slot name="trigger" />
    </MenuButton>
    <transition name="dropdown">
      <MenuItems
        class="absolute right-0 bg-white shadow border rounded-lg p-2 flex gap-1 flex-col w-56 *:py-1 *:px-3 *:rounded focus-visible:outline-none"
        :class="[
          {
            'top-full mt-2 origin-top-right': props.placement === 'bottom',
            'bottom-full mb-2 origin-bottom-right': props.placement === 'top',
          },
          props.contentClass,
        ]"
      >
        <slot name="content" />
      </MenuItems>
    </transition>
  </Menu>
</template>
