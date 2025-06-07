import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
  build: {
    rollupOptions: {
      input: path.resolve(__dirname, "assets/js/main.js"), // your JS entry point
    },
    outDir: "dist", // or wherever you want your built files
    emptyOutDir: true,
  },
});
