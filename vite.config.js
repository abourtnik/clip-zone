import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import preact from '@preact/preset-vite'

export default defineConfig({
    server: {
        host: '0.0.0.0'
    },
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        preact()
    ]
});
