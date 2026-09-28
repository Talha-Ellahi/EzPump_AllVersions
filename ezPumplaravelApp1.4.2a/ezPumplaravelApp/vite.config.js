import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
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
            vue: 'vue/dist/vue.esm-bundler.js',
        },

    },
    // server: {
    //     host: "10.10.16.103:8000", // <-- your LAN IP
    //     port: 8000,           // <-- custom port
    //     hmr: {
    //         host: "10.10.16.103:8000", // Hot Module Replacement host
    //     }
    // }
});
