---
name: site-check
description: Render and test every page of kuvadoo.fi locally (phone + desktop, light + dark), check links, assets, headings, horizontal scroll and that no third-party request happens before a click; run the admin end-to-end test; optionally smoke-test the live site. Use before every commit that changes PHP/CSS/JS and before making a release.
---

# Site check

1. Lint: `for f in index.php app/*.php app/views/*.php app/admin/*.php; do php -l $f; done`
2. Start the site from the repo root (background): `php -S 127.0.0.1:8765 router.php`
3. Playwright: `node -e "require.resolve('playwright')"`; if missing, install it in a scratch folder
   (`npm i playwright`, browser at /opt/pw-browsers/chromium) and copy the `tools/*.mjs` scripts next to that
   `node_modules` (ESM ignores NODE_PATH). Never commit `node_modules`.
4. `node tools/check-site.mjs http://127.0.0.1:8765 tools/screenshots` — every sitemap page (FI + EN) and the
   404 at 360/1280 px, light/dark: HTTP errors, console errors, horizontal scroll, one h1, img alt,
   **any third-party request on load**. Screenshots use reduced motion (scroll reveals can't run in
   full-page shots).
5. Look at the screenshots of every changed page (at least light-360 and dark-1280): photos sharp, faces not
   cut, text readable over photos.
6. Admin: copy the site to a throwaway folder (never test on the real `data/`), run
   `php -S 127.0.0.1:8766 router.php` there and `node tools/admin-test.mjs <copy> <photo.jpg>`.
7. Facts: every claim, price and name in changed copy is in `docs/facts/kuvadoo.md`.
8. Live (after upload): each sitemap URL → 200; `/videos`, `/hinnasto` (no slash) → 301; `/data/content.json`,
   `/app/bootstrap.php`, `/CLAUDE.md`, `/.user.ini`, `/docs/` → not 200; `https://www.` → `https://kuvadoo.fi`.
9. Stop the servers. Report pass/fail per page.
