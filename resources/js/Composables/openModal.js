import { ref } from "vue";

export const useOpenModal = () => {
  const isModalCreateOpen = ref(false);
  const isModalEditOpen = ref(false);
  const isModalDeleteOpen = ref(false);
  const dataModal = ref({});

  const modalTrigger = (data, modal) => {
    dataModal.value = data;
    if (modal == "edit") {
      isModalEditOpen.value = true;
    } else if (modal == "create") {
      isModalCreateOpen.value = true;
    } else {
      isModalDeleteOpen.value = true;
    }
  };

  return {
    isModalCreateOpen,
    isModalEditOpen,
    isModalDeleteOpen,
    dataModal,
    modalTrigger,
  };
};
