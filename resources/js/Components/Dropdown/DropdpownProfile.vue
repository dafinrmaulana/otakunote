<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import {
  ArrowRightStartOnRectangleIcon,
  UserIcon,
} from "@heroicons/vue/20/solid";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div class="relative translate-y-[2px] z-10">
    <Menu>
      <MenuButton>
        <img
          class="h-10 w-10 rounded-full"
          src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
          :alt="$page.props.auth.user.name"
        />
      </MenuButton>
      <transition name="dropdown">
        <MenuItems
          class="absolute top-full right-0 bg-white shadow border rounded-lg p-2 flex gap-1 flex-col w-56 *:py-1 *:px-3 *:rounded focus-visible:outline-none"
        >
          <MenuItem>
            <Link
              class="ui-active:bg-slate-100 flex items-center gap-2 duration-150"
              :href="route('profile.edit')"
            >
              <UserIcon class="size-4" />
              Profile
            </Link>
          </MenuItem>
          <MenuItem>
            <Link
              as="button"
              method="post"
              :href="route('logout')"
              class="ui-active:bg-red-100 text-red-600 flex items-center gap-2 duration-150"
            >
              <ArrowRightStartOnRectangleIcon class="size-4" />
              Log Out
            </Link>
          </MenuItem>
        </MenuItems>
      </transition>
    </Menu>
  </div>
</template>
