import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';


export default defineConfig({
    server: {
        host: '0.0.0.0', // Важно для работы в контейнере
        port: 5173, 
        strictPort: true,
        hmr: {
            host: 'localhost', // Или ваш внешний IP
            port: 5173, 
        },
        watch: {
            usePolling: true, // Необходимо для работы в Docker на Windows
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});