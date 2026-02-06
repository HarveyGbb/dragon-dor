import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    // AJOUTEZ CETTE SECTION
    server: {
        host: 'localhost',
        port: 5175, // le port que Vite utilise actuellement
        hmr: {
            host: 'localhost',
        },
    },
});
