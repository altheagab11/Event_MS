import { copyFileSync, readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const manifestPath = join(root, 'public', 'build', 'manifest.json');
const manifest = JSON.parse(readFileSync(manifestPath, 'utf8'));
const cssEntry = manifest['resources/css/app.css'];

if (! cssEntry?.file) {
  throw new Error('Vite manifest is missing resources/css/app.css');
}

const source = join(root, 'public', 'build', cssEntry.file);
const target = join(root, 'public', 'css', 'admin-app.css');

copyFileSync(source, target);
console.log(`Copied ${cssEntry.file} -> public/css/admin-app.css`);
