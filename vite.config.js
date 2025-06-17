// vite.config.js
import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
  build: {
    outDir: "dist",
    emptyOutDir: true,
    rollupOptions: {
      input: {
        tailwind: path.resolve(__dirname, "src/sass/tailwind.scss"),
        main: path.resolve(__dirname, "src/sass/main.scss"),
        main: path.resolve(__dirname, "src/js/main.js"), // renamed to "main"
      },
      output: {
        entryFileNames: "[name].js", // ✅ will output main.js
        assetFileNames: "[name].css",
      },
    },
  },
  resolve: {
    alias: {
      "@sass": path.resolve(__dirname, "src/sass"),
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@use '@sass/abstracts/variables' as *;\n`,
      },
    },
  },
});
