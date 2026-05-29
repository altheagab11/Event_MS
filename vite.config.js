import { copyFileSync, readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const projectRoot = dirname(fileURLToPath(import.meta.url));

function copyAdminCssFallback() {
    return {
        name: 'copy-admin-css-fallback',
        closeBundle() {
            const manifestPath = join(projectRoot, 'public/build/manifest.json');
            const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
            const cssEntry = manifest['resources/css/app.css'];

            if (! cssEntry?.file) {
                throw new Error('Vite manifest is missing resources/css/app.css');
            }

            copyFileSync(
                join(projectRoot, 'public/build', cssEntry.file),
                join(projectRoot, 'public/css/admin-app.css'),
            );
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        copyAdminCssFallback(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
