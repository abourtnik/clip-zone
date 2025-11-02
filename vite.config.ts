import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import preact from '@preact/preset-vite'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        outDir: 'public/build',
        manifest: "manifest.json",
        rollupOptions: {
            input: {
                main: 'resources/js/app.ts',
            },
        },
        chunkSizeWarningLimit: 1600
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: true,
        }),
        preact()
    ],
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls']
            }
        }
    }
});
