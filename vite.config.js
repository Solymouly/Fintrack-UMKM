import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true, // Bungkam warning dari file di node_modules (Bootstrap)
                silenceDeprecations: ['import'], // Bungkam khusus warning @import kuno
            },
        },
    },
});