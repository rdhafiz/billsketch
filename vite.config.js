import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/scss/website/app.scss',
                'resources/js/website/app.js',
            ],
            refresh: true,
        }),
    ],
});
