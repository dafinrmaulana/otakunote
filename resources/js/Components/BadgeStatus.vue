<script setup>
import { ref, onMounted, watchEffect } from "vue";

const props = defineProps({
  color: { type: String, default: "#000000" },
  title: { type: String, default: "On Going" },
});

const textColorClass = ref("");

// Fungsi untuk menghitung kecerahan dan mengatur warna teks
const adjustTextColor = () => {
  const bgColor = props.color || "#000000";
  let r, g, b;
  if (bgColor?.startsWith("#")) {
    // Konversi warna heksadesimal ke RGB
    const hex = bgColor.replace("#", "");
    r = parseInt(hex.substring(0, 2), 16);
    g = parseInt(hex.substring(2, 4), 16);
    b = parseInt(hex.substring(4, 6), 16);
  } else if (bgColor.startsWith("rgb")) {
    [r, g, b] = bgColor.match(/\d+/g).map(Number);
  }

  // Menghitung luminance untuk menentukan warna teks
  const luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;
  textColorClass.value = luminance < 148 ? "text-white" : "text-black";
};

// Panggil saat komponen di-mount dan saat `backgroundColor` berubah
onMounted(adjustTextColor);
watchEffect(() => props.color, adjustTextColor);
</script>

<template>
  <div
    :style="{ backgroundColor: props.color }"
    class="min-w-10 !px-3 h-6 text-center rounded-md overflow-hidden text-ellipsis grid place-items-center"
  >
    <p
      :class="textColorClass"
      class="text-xs text-nowrap font-semibold text-ellipsis uppercase"
    >
      {{ props.title }}
    </p>
  </div>
</template>
