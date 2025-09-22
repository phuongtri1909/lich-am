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
                'resources/assets/frontend/css/footer.css',
                'resources/assets/frontend/css/auth.css',
                'resources/assets/admin/css/styles_admin.css',
                'resources/assets/frontend/css/components/breadcrumb.css',
                'resources/assets/frontend/css/components/date-card.css',
                'resources/assets/frontend/css/components/month-events.css',
                'resources/assets/frontend/css/components/month-fortune.css',
                'resources/assets/frontend/css/components/monthly-calendar.css',
                'resources/assets/frontend/css/components/other-months.css',
                'resources/assets/frontend/css/components/other-years.css',
                'resources/assets/frontend/css/components/year-calendar.css',
                'resources/assets/frontend/css/components/year-events.css',
                'resources/assets/frontend/css/components/year-info.css',
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
