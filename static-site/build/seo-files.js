/**
 * seo-files — emits robots.txt and sitemap.xml into the build.
 * - robots.txt is always written (styleguide.html is disallowed).
 * - sitemap.xml needs absolute URLs, so it is written only once site.siteUrl is set
 *   in src/site.config.json; robots.txt then points to it.
 * Phase 2: replaced by Laravel routes (/robots.txt, /sitemap.xml) built from the database.
 */
import fs from 'node:fs';

const EXCLUDE = new Set(['styleguide']);

export default function seoFiles({ configFile, pages }) {
  return {
    name: 'hudaya-seo-files',
    apply: 'build',
    generateBundle() {
      const site = JSON.parse(fs.readFileSync(configFile, 'utf8'));
      const base = (site.siteUrl || '').replace(/\/+$/, '');
      const names = Object.keys(pages).filter((name) => !EXCLUDE.has(name));

      const robots = ['User-agent: *', 'Allow: /', 'Disallow: /styleguide.html'];
      if (base) robots.push('', `Sitemap: ${base}/sitemap.xml`);
      this.emitFile({ type: 'asset', fileName: 'robots.txt', source: `${robots.join('\n')}\n` });

      if (!base) {
        this.warn('site.siteUrl is not set — sitemap.xml, canonical and og:image tags were skipped.');
        return;
      }

      const today = new Date().toISOString().slice(0, 10);
      const urls = names
        .sort((a, b) => (a === 'index' ? -1 : b === 'index' ? 1 : a.localeCompare(b)))
        .map((name) => {
          const loc = name === 'index' ? `${base}/` : `${base}/${name}.html`;
          return `  <url><loc>${loc}</loc><lastmod>${today}</lastmod></url>`;
        });
      const xml = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls.join('\n')}\n</urlset>\n`;
      this.emitFile({ type: 'asset', fileName: 'sitemap.xml', source: xml });
    },
  };
}
