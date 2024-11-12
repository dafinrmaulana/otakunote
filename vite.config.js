import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import removeConsole from "vite-plugin-remove-console";

export default defineConfig({
  plugins: [
    laravel({
      input: "resources/js/app.js",
      ssr: "resources/js/ssr.js",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
});
