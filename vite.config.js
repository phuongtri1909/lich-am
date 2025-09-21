import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/frontend/css/styles.css',
                'resources/assets/frontend/css/header.css',
                'resources/assets/frontend/css/home.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'licham.local',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'licham.local',
            protocol: 'http',
            port: 5173,
        },
    },
    
    
});
