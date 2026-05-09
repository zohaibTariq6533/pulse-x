import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Allow external connections (for mobile testing)
        port: 5173, // Default Vite port
        hmr: {
            host: 'localhost', // HMR host (change to your IP for mobile HMR)
        },
    },
});
