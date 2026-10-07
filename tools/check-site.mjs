// Renders every page in sitemap.xml (plus the 404 page) at phone and desktop widths, light and dark.
// Fails on HTTP errors, missing assets, console errors, horizontal scroll, or missing h1.
// Usage: python3 -m http.server 8765 &  then  node tools/check-site.mjs [baseUrl] [outDir]
// Playwright must resolve from the script's folder (ESM ignores NODE_PATH): if it is installed elsewhere,
// copy this file next to that node_modules and run it with the repo root as the working directory.
import { chromium } from 'playwright';
import { readFileSync, mkdirSync } from 'node:fs';

const base = process.argv[2] || 'http://localhost:8765';
const out = process.argv[3] || 'tools/screenshots';
mkdirSync(out, { recursive: true });

const paths = [...readFileSync('sitemap.xml', 'utf8').matchAll(/<loc>https:\/\/apps\.kuvadoo\.fi([^<]*)<\/loc>/g)]
  .map(m => m[1] || '/');
paths.push('/404.html');  // python's http.server can't serve the custom 404, so test the page itself

// Use the bundled browser if it matches; otherwise fall back to a system Chromium (CHROMIUM_PATH).
const browser = await chromium.launch().catch(() =>
  chromium.launch({ executablePath: process.env.CHROMIUM_PATH || '/opt/pw-browsers/chromium' }));
const sizes = [{ w: 360, h: 780 }, { w: 1280, h: 800 }];
let failures = 0;

for (const scheme of ['light', 'dark']) {
  for (const s of sizes) {
    const ctx = await browser.newContext({ viewport: { width: s.w, height: s.h }, colorScheme: scheme });
    const page = await ctx.newPage();
    const problems = [];
    page.on('console', m => { if (m.type() === 'error') problems.push('console: ' + m.text()); });
    page.on('response', r => {
      if (r.status() >= 400) problems.push(`${r.status()} ${r.url()}`);
    });
    for (const p of paths) {
      problems.length = 0;
      await page.goto(base + p, { waitUntil: 'networkidle' });
      const info = await page.evaluate(() => ({
        overflow: document.documentElement.scrollWidth > window.innerWidth,
        h1: document.querySelectorAll('h1').length,
        links: [...document.querySelectorAll('a[href^="/"]')].map(a => a.getAttribute('href')),
        imgsNoAlt: [...document.querySelectorAll('img:not([alt])')].length,
      }));
      for (const href of new Set(info.links)) {
        const r = await ctx.request.get(base + href.split('#')[0]);
        if (r.status() >= 400) problems.push(`broken link ${href}`);
      }
      if (info.overflow) problems.push('horizontal scroll');
      if (info.h1 !== 1) problems.push(`${info.h1} h1 elements`);
      if (info.imgsNoAlt) problems.push(`${info.imgsNoAlt} img without alt`);
      const name = `${scheme}-${s.w}${p.replaceAll('/', '_') || '_'}.png`;
      await page.screenshot({ path: `${out}/${name}`, fullPage: true });
      const status = problems.length ? 'FAIL' : 'ok  ';
      if (problems.length) failures++;
      console.log(`${status} ${scheme} ${s.w}px ${p}${problems.length ? '  ← ' + problems.join('; ') : ''}`);
    }
    await ctx.close();
  }
}
await browser.close();
console.log(failures ? `\n${failures} page checks failed` : '\nAll page checks passed');
process.exit(failures ? 1 : 0);
