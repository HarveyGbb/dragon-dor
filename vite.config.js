import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',      // CSS géré par Vite
                'resources/js/app.js',        // JS principal
                ,
            ],
            refresh: true,
        }),
    ],
});
