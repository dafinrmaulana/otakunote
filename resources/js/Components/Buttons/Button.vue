<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
  variant: { type: String, default: "base" },
  type: { type: String, default: "button" },
  href: { type: String, default: null },
  disabled: { type: Boolean, default: false },
});

const attrs = computed(() => {
  if (props.type === "submit" || props.type === "button") {
    return {
      type: props.type,
    };
  } else {
    return {
      href: props.href,
    };
  }
});

const tag = computed(() => {
  if (props.type === "submit" || props.type === "button") {
    return "button";
  } else if (props.type === "link") {
    return Link;
  } else {
    return "a";
  }
});
</script>

<template>
  <component
    :is="tag"
    v-bind="attrs"
    class="h-8 px-4 rounded-md duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed focus:ring ring-offset-1 outline-none"
    :class="{
      'bg-transparent disabled:hover:bg-transparent':
        props.variant === 'base' || props.variant.includes('outline'),
      'hover:text-white':
        props.variant.includes('outline') && !props.variant.includes('base'),
      'text-white':
        !props.variant.includes('outline') && props.variant !== 'base',

      'hover:bg-slate-100 text-black ring-slate-300': props.variant === 'base',
      'bg-indigo-500 focus:ring-indigo-500 hover:bg-indigo-600 disabled:bg-indigo-500':
        props.variant === 'primary',
      'bg-red-600 focus:ring-red-600 hover:bg-red-700 disabled:bg-red-600':
        props.variant === 'danger',
      'bg-emerald-500 focus:ring-emerald-500 hover:bg-emerald-600 disabled:bg-emerald-500':
        props.variant === 'success',
      'bg-amber-400 focus:ring-amber-400 hover:bg-amber-500 disabled:bg-amber-400':
        props.variant === 'warning',
      'bg-sky-500 focus:ring-sky-500 hover:bg-sky-600 disabled:bg-sky-500':
        props.variant === 'info',

      'border border-slate-200 hover:bg-slate-100 text-black ring-slate-300':
        props.variant === 'outline-base',
      'focus:ring-indigo-500 disabled:hover:!text-indigo-500 text-indigo-500 border border-indigo-500 hover:bg-indigo-500':
        props.variant === 'outline-primary',
      'focus:ring-red-600 disabled:hover:!text-red-600 text-red-600 border border-red-600 hover:bg-red-600':
        props.variant === 'outline-danger',
      'focus:ring-emerald-500 disabled:hover:!text-emerald-500 text-emerald-500 border border-emerald-500 hover:bg-emerald-500':
        props.variant === 'success',
      'focus:ring-amber-400 disabled:hover:!text-amber-400 text-amber-400 border border-amber-400 hover:bg-amber-400':
        props.variant === 'outline-warning',
      'focus:ring-sky-500 disabled:hover:!text-sky-500 text-sky-500 border border-sky-500 hover:bg-sky-500':
        props.variant === 'outline-info',
    }"
    :disabled="props.disabled"
  >
    <slot />
  </component>
</template>
