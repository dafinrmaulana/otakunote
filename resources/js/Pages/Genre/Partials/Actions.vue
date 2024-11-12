<script setup>
import Dropdown from "@/Components/Dropdown/Dropdown.vue";
import { MenuItem } from "@headlessui/vue";
import { EllipsisVerticalIcon } from "@heroicons/vue/16/solid";
import {
  PencilSquareIcon,
  StarIcon as StarIconSolid,
  TrashIcon,
} from "@heroicons/vue/20/solid";
import { StarIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
  contentClass: { type: String, default: "" },
  triggerClass: { type: String, default: "" },
  isPinned: { type: Number, default: 0 },
});

const emits = defineEmits(["click:edit", "click:delete", "click:pin"]);
</script>

<template>
  <Dropdown
    placement="bottom"
    :content-class="`w-full z-10 !mt-1 ${props.contentClass}`"
  >
    <template #trigger>
      <button
        class="hover:bg-slate-100 p-1 rounded"
        :class="props.triggerClass"
      >
        <EllipsisVerticalIcon class="size-4 text-black/40" />
      </button>
    </template>
    <template #content>
      <MenuItem>
        <button
          @click="emits('click:pin')"
          class="w-full ui-active:bg-slate-100 flex items-center justify-start gap-1 h-9"
        >
          <component
            :is="props.isPinned === 0 ? StarIcon : StarIconSolid"
            class="size-5 flex-none"
            :class="props.isPinned === 1 ? 'text-amber-400' : ''"
          />
          <span class="translate-y-[1px] text-start text-xs">
            {{ props.isPinned === 0 ? "Pin Genre" : "Remove Pin" }}
          </span>
        </button>
      </MenuItem>
      <MenuItem>
        <button
          @click="emits('click:edit')"
          class="w-full ui-active:bg-slate-100 flex items-center justify-start gap-1 h-9"
        >
          <PencilSquareIcon class="size-5" />
          <span class="translate-y-[1px] text-start text-xs">Edit</span>
        </button>
      </MenuItem>
      <MenuItem>
        <button
          @click="emits('click:delete')"
          class="w-full text-red-500 ui-active:bg-red-100 flex items-center justify-start gap-1 h-9"
        >
          <TrashIcon class="size-5" />
          <span class="translate-y-[1px] text-start text-xs">Delete</span>
        </button>
      </MenuItem>
    </template>
  </Dropdown>
</template>
