import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                // Tipografía del cliente (CLAUDE.md regla 15, DESIGN.md): se descargan al compilar y se sirven
                // desde el propio sitio. Nunca se pide nada a un CDN de fuentes en tiempo de ejecución.
                // Se precargan sólo las dos que se ven arriba del pliegue (títulos 700 y cuerpo 400).
                bunny('Barlow Condensed', {
                    alias: 'condensada',
                    weights: [500, 600, 700],
                    preload: [{weight: 700}],
                }),
                bunny('IBM Plex Sans', {
                    alias: 'texto',
                    weights: [400, 600],
                    preload: [{weight: 400}],
                }),
                bunny('IBM Plex Mono', {
                    alias: 'mono',
                    weights: [500],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
