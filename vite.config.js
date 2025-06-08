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
  // Disable the default behavior of serving an HTML page
  // (only needed if you run dev server, build is fine with rollupOptions input)
  // server: {
  //   open: false,
  // },
});
