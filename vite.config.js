// vite.config.js
import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        tailwind: path.resolve(__dirname, 'src/sass/tailwind.scss'),
        main_scss: path.resolve(__dirname, 'src/sass/main.scss'),
        main_js: path.resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        entryFileNames: (chunkInfo) => {
          // Output JS files for JS entries, CSS files for CSS entries
          if (chunkInfo.name === 'main_js') {
            return 'main.js';
          }
          return '[name].js';
        },
        assetFileNames: (assetInfo) => {
          // Output CSS files named after their keys
          if (assetInfo.name === 'main_scss.css') return 'main.css';
          if (assetInfo.name === 'tailwind.css') return 'tailwind.css';
          return '[name][extname]';
        },
      },
    },
  },
  resolve: {
    alias: {
      '@sass': path.resolve(__dirname, 'src/sass'),
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
