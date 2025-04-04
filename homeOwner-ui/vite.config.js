import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    hmr: true,
  },
  proxy: {
    '/api': 'http://localhost:8000', // Proxy API requests to Laravel
  },
  root: 'src',
  build: {
    outDir: '../dist',
  },
});
