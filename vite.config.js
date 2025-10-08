import tailwindcss from "@tailwindcss/vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: [
                ...refreshPaths,
                "app/Livewire/**",
                "app/Filament/**",
                "app/Filament/Resources/**",
                "app/Providers/**",
            ],
        }),
        tailwindcss(),
    ],
});
