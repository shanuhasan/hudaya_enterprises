/**
 * html-include — tiny build-time templating for the static site.
 *
 * Directives (all resolved at build/dev time, nothing runs in the browser):
 *
 *   {{-- developer note --}}
 *       Template comment, removed from output (same syntax as Blade).
 *
 *   <!-- @include components/header.html -->
 *   <!-- @include components/head.html {"title": "About", "description": "..."} -->
 *       Inlines a file (path relative to src/). Optional flat JSON object = local variables.
 *
 *   {{ title }}  {{ site.phone }}  {{ year }}  {{ site.whatsappMessage | url }}
 *   {{ loading ?? 'lazy' }}
 *       Variable output (HTML-escaped, or URL-encoded with `| url`).
 *       `?? 'value'` supplies a default when the variable is not set; `?? otherVar` falls
 *       back to another variable.
 *       `site.*` comes from src/site.config.json.
 *
 *   <!-- @if site.instagram --> ... <!-- @endif -->
 *   <!-- @if !hideWhatsapp --> ... <!-- @endif -->
 *   <!-- @if site.whatsappNumber && !hideWhatsapp --> ... <!-- @endif -->
 *       Conditional block (not nestable; terms can be combined with &&).
 *
 *   {{ site.name | json }}
 *       JSON-string-safe output (for <script type="application/ld+json">).
 *   Empty strings in site.config.json count as "not set" for @if and ?? fallbacks.
 *
 *   <img data-slot src="images/…">
 *       Removed at build time when the file is missing from public/ (placeholder shows instead).
 *
 *   <body data-nav-active="products">
 *       Adds aria-current="page" to every element with data-nav="products".
 *
 * Every directive maps 1:1 to Blade in Phase 2 (@include, {{ }}, @if, request()->routeIs()).
 */
import fs from 'node:fs';
import path from 'node:path';

const COMMENT_RE = /\{\{--[\s\S]*?--\}\}\s*/g;
const INCLUDE_RE = /<!--\s*@include\s+(\S+?)(?:\s+(\{[\s\S]*?\}))?\s*-->/g;
const IF_RE = /<!--\s*@if\s+([!\w.&\s]+?)\s*-->([\s\S]*?)<!--\s*@endif\s*-->/g;
const VAR_RE = /\{\{\s*([\w.]+)\s*(?:\?\?\s*(?:'([^']*)'|([\w.]+))\s*)?(?:\|\s*(url|json)\s*)?\}\}/g;
const MAX_DEPTH = 10;

const escapeHtml = (value) =>
  String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');

// Values that count as "not set": undefined, null and empty strings.
const isUnset = (value) => value === undefined || value === null || value === '';

const lookup = (ctx, key) =>
  key.split('.').reduce((obj, k) => (obj == null ? undefined : obj[k]), ctx);

function render(html, ctx, srcRoot, file, depth = 0) {
  if (depth > MAX_DEPTH) {
    throw new Error(`[html-include] Include depth exceeded in ${file} (circular include?)`);
  }

  // 0. Template comments — stripped, never shipped
  html = html.replace(COMMENT_RE, '');

  // 1. Conditionals
  html = html.replace(IF_RE, (_, expr, body) => {
    const pass = expr.split('&&').every((term) => {
      const t = term.trim();
      const negate = t.startsWith('!');
      const value = lookup(ctx, negate ? t.slice(1) : t);
      const truthy = !isUnset(value) && Boolean(value);
      return negate ? !truthy : truthy;
    });
    return pass ? body : '';
  });

  // 2. Variables
  html = html.replace(VAR_RE, (_, key, fallback, fallbackKey, filter) => {
    let value = lookup(ctx, key);
    if (isUnset(value) && fallback !== undefined) value = fallback;
    if (isUnset(value) && fallbackKey !== undefined) value = lookup(ctx, fallbackKey);
    if (value === undefined) {
      console.warn(`[html-include] Undefined variable "${key}" in ${file}`);
      return '';
    }
    if (filter === 'url') return encodeURIComponent(value);
    if (filter === 'json') return JSON.stringify(String(value)).slice(1, -1).replace(/</g, '\\u003c');
    return escapeHtml(value);
  });

  // 3. Includes (rendered recursively with merged variables)
  html = html.replace(INCLUDE_RE, (_, relPath, json) => {
    const target = path.resolve(srcRoot, relPath);
    if (!fs.existsSync(target)) {
      throw new Error(`[html-include] File not found: ${relPath} (included from ${file})`);
    }
    let params = {};
    if (json) {
      try {
        params = JSON.parse(json);
      } catch (err) {
        throw new Error(`[html-include] Invalid JSON for ${relPath} in ${file}: ${err.message}`);
      }
    }
    const content = fs.readFileSync(target, 'utf8');
    return render(content, { ...ctx, ...params }, srcRoot, relPath, depth + 1);
  });

  return html;
}

// Image slots: drop <img data-slot> whose file does not exist yet, so the page shows the
// placeholder without making a request that would 404. Drop the photo into public/ and it appears.
/** Pixel width of a JPEG (reads the SOF marker); 0 if it can't be found. */
function jpegWidth(file) {
  const b = fs.readFileSync(file);
  let i = 2;
  while (i + 9 < b.length) {
    if (b[i] !== 0xff) { i++; continue; }
    const m = b[i + 1];
    if (m >= 0xc0 && m <= 0xcf && m !== 0xc4 && m !== 0xc8 && m !== 0xcc) return b.readUInt16BE(i + 7);
    i += 2 + b.readUInt16BE(i + 2);
  }
  return 0;
}

/**
 * Image slots:
 * - the <img> is removed when its file is missing (the placeholder shows, no 404);
 * - when a smaller copy "name@800.jpg" exists next to "name.jpg" (and is not older than it),
 *   srcset/sizes are added so phones download the small file. Replacing a photo makes the
 *   old copy stale, so it is ignored until regenerated.
 */
function resolveImageSlots(html, publicDir) {
  return html.replace(/<img [^>]*data-slot[^>]*>/g, (tag) => {
    const src = (tag.match(/ src="([^"]+)"/) || [])[1];
    const file = src && path.join(publicDir, src);
    if (!file || !fs.existsSync(file)) return '';
    const small = src.replace(/\.jpg$/, '@800.jpg');
    const smallFile = path.join(publicDir, small);
    if (small === src || !fs.existsSync(smallFile) || fs.statSync(smallFile).mtimeMs < fs.statSync(file).mtimeMs) return tag;
    const w = jpegWidth(file);
    if (w <= 800) return tag;
    const sizes = src.includes('/hero/') ? '100vw' : '(min-width: 64rem) 50vw, 100vw';
    return tag.replace(/ src="/, ` srcset="${small} 800w, ${src} ${w}w" sizes="${sizes}" src="`);
  });
}

function markActiveNav(html) {
  const match = html.match(/<body[^>]*\sdata-nav-active="([\w-]+)"/);
  if (!match) return html;
  const key = match[1];
  return html.replace(
    new RegExp(`data-nav="${key}"(?![^>]*aria-current)`, 'g'),
    `data-nav="${key}" aria-current="page"`,
  );
}

// Company details that should be filled in before launch.
const REQUIRED_SITE_KEYS = ['phone', 'email', 'whatsappNumber', 'address', 'hours', 'siteUrl'];
let warnedMissing = false;

export default function htmlInclude({ srcRoot, configFile, publicDir }) {
  const loadSite = () => JSON.parse(fs.readFileSync(configFile, 'utf8'));

  return {
    name: 'hudaya-html-include',

    transformIndexHtml: {
      order: 'pre',
      handler(html, { filename }) {
        const site = loadSite();
        if (!warnedMissing) {
          const missing = REQUIRED_SITE_KEYS.filter((k) => isUnset(site[k]));
          if (missing.length) console.warn(`
[html-include] site.config.json — not set yet (hidden or shown as placeholders): ${missing.join(', ')}
`);
          warnedMissing = true;
        }
        const pageFile = path.basename(filename);
        const ctx = {
          site,
          year: new Date().getFullYear(),
          currentPath: pageFile === 'index.html' ? '' : pageFile,   // for canonical / og:url
        };
        const out = render(html, ctx, srcRoot, pageFile);
        return markActiveNav(resolveImageSlots(out, publicDir));
      },
    },

    // Components and config are not part of Vite's module graph — reload the page when they change.
    configureServer(server) {
      const watched = [path.join(srcRoot, 'components'), path.join(srcRoot, 'icons'), configFile, path.join(publicDir, 'images')];
      server.watcher.add(watched);
      const reload = (file) => {
        const normalized = path.resolve(file);
        if (watched.some((w) => normalized.startsWith(path.resolve(w)))) {
          server.ws.send({ type: 'full-reload' });
        }
      };
      // 'add' / 'unlink' so a newly dropped photo appears without restarting the server.
      server.watcher.on('change', reload);
      server.watcher.on('add', reload);
      server.watcher.on('unlink', reload);
    },
  };
}
