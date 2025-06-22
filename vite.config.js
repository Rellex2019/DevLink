import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';


export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: '87.228.101.27',  // Для Docker используйте localhost
            port: 5173,
        },
        watch: {
            ignored: [
                '**/vendor/**', 
                '**/node_modules/**'
            ],
            usePolling: true,  // Обязательно для Docker на Linux/Windows
            interval: 1000,    // Проверять изменения каждую секунду
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
    optimizeDeps: {
        include: ['js-cookie']
    }
});