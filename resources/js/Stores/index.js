import { defineStore } from "pinia";
import { useStorage } from "@vueuse/core";

export const useSidebar = () => {
  const isOpen = useStorage("sidebar-open", true);

  const toggleOpen = () => {
    isOpen.value = !isOpen.value;
  };

  return { isOpen, toggleOpen };
};

export const useLayout = (key) => {
  const isGrid = useStorage(key, true);

  const toggleGrid = () => {
    isGrid.value = !isGrid.value;
  };

  return { isGrid, toggleGrid };
};
