import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
export const useSidebar = () => {
  const isOpen = useStorage("sidebar-open:" + page.props.auth.user.email, true);

  const toggleOpen = () => {
    isOpen.value = !isOpen.value;
  };

  return { isOpen, toggleOpen };
};

export const useLayout = (key) => {
  const isGrid = useStorage(key + ":" + page.props.auth.user.email, true);

  const toggleGrid = () => {
    isGrid.value = !isGrid.value;
  };

  return { isGrid, toggleGrid };
};
