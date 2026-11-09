import fs from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import htmlInclude from './build/html-include.js';
import seoFiles from './build/seo-files.js';

const projectRoot = dirname(fileURLToPath(import.meta.url));
const srcRoot = resolve(projectRoot, 'src');

// Every top-level .html file in src/ is a page. New pages are picked up automatically.
const pages = Object.fromEntries(
  fs
    .readdirSync(srcRoot)
    .filter((file) => file.endsWith('.html'))
    .map((file) => [file.replace(/\.html$/, ''), resolve(srcRoot, file)]),
);

export default defineConfig({
  root: srcRoot,
  base: './', // relative URLs so dist/ works from any folder (e.g. XAMPP htdocs sub-path)
  publicDir: resolve(projectRoot, 'public'),
  plugins: [
    htmlInclude({ srcRoot, configFile: resolve(srcRoot, 'site.config.json'), publicDir: resolve(projectRoot, 'public') }),
    tailwindcss(),
    seoFiles({ configFile: resolve(srcRoot, 'site.config.json'), pages }),
  ],
  build: {
    outDir: resolve(projectRoot, 'dist'),
    emptyOutDir: true,
    rollupOptions: { input: pages },
  },
  server: {
    port: 5173,
    open: '/styleguide.html',
  },
});
