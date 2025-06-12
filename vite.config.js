import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
  build: {
    outDir: "dist",
    emptyOutDir: true,
    rollupOptions: {
      input: {
        style: path.resolve(__dirname, "src/sass/main.scss"),
        main: path.resolve(__dirname, "src/js/main.js"),
      },
      output: {
        entryFileNames: "[name].js",
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
        // Automatically include variables file in every scss file
        additionalData: `@use '@sass/abstracts/variables' as *;\n`,
      },
    },
  },
});
