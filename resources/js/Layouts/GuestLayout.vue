<template>
  <main
    class="bg-slate-100 flex justify-center items-center size-full fixed overflow-auto"
  >
    <div
      class="size-full bg-white absolute flex flex-col items-center justify-center gap-10 sm:gap-3 sm:max-w-sm sm:max-h-min p-3 *:-translate-y-5 sm:*:translate-y-0 sm:rounded-xl sm:p-5 overflow-x-hidden overflow-y-auto"
    >
      <slot />
    </div>
  </main>
</template>
