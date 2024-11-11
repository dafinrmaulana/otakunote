import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import headlessui from "@headlessui/tailwindcss";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.vue",
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Inter", "Figtree", ...defaultTheme.fontFamily.sans],
      },
      animation: {
        icon: "scale-rotate 1s ease-in-out forwards",
        shake: "shake 0.5s ease-in-out forwards",
      },
      keyframes: {
        "scale-rotate": {
          "0%, 100%": { transform: "rotate(0deg) scale(1)" },
          "33%": { transform: "rotate(10deg) scale(0.9)" },
          "66%": { transform: "rotate(-10deg) scale(1.1)" },
        },
        shake: {
          "20%": { transform: "translate3d(-1px, 0, 0)" },
          "40%": { transform: "translate3d(2px, 0, 0)" },
          "60%": { transform: "translate3d(-1px, 0, 0)" },
          "80%": { transform: "translate3d(2px, 0, 0)" },
          "100%": { transform: "translate3d(0, 0, 0)" },
        },
      },
    },
  },

  plugins: [forms, headlessui],
};
