// @ts-check
import { defineConfig } from 'astro/config';

// https://astro.build/config
export default defineConfig({
  site: 'https://cristianngonzalez.com',
  vite: {
    server: {
      // En local, /api va a Laravel (npm run dev:api). En producción comparten dominio.
      proxy: {
        '/api': 'http://localhost:8000',
      },
    },
  },
});
