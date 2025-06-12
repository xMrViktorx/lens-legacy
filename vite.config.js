import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    plugins: [
        vue(), // Vue.js plugin for Vite
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"], // Your main assets
            refresh: true, // Live refresh during development
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'Modules/Frontend/resources/assets/images/**/', // Copy images folder
                    dest: 'images', // Copy into public/build-frontend/images
                }
            ]
        })
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js", // Ensure proper Vue.js aliasing
        },
    },
    build: {
        outDir: "public/build-frontend", // Output directory for build
        emptyOutDir: true,  // Empty the output directory before building
        manifest: true,  // Create manifest for asset versioning
    },
});