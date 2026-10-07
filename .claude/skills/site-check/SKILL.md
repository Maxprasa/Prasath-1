---
name: site-check
description: Render and test every page of kuvadoo.fi locally (phone + desktop, light + dark), check links, assets, headings, horizontal scroll, image weight and that no third-party request happens before a click; optionally smoke-test the live site. Use before every commit that changes HTML/CSS and before making a release.
---

# Site check

1. Start a local server from the repo root (background): `python3 -m http.server 8765`
2. Make sure Playwright is available: `node -e "require.resolve('playwright')"`; if not, install it outside
   the repo (e.g. in a scratch dir with `npm i playwright`) and copy `tools/check-site.mjs` next to that
   `node_modules` (ESM ignores NODE_PATH). Never commit `node_modules`.
3. Run `node tools/check-site.mjs` (screenshots go to `tools/screenshots/`, which is git-ignored).
4. Open the screenshots for every changed page (at least light-360 and dark-1280) and look at them:
   photos sharp, not stretched, faces not cut off; text readable on photos.
5. Third-party check: on page load, no request may go to any host other than localhost (video embeds load
   only after clicking play). Click one play button and confirm the video loads.
6. Fact check: every claim, price and name in changed copy appears in `docs/facts/kuvadoo.md`.
7. Live check (after upload), for each URL in sitemap.xml:
   `curl -s -o /dev/null -w '%{http_code}' https://kuvadoo.fi<path>` → 200; old builder URLs → 301;
   `/DEPLOY.md`, `/CLAUDE.md`, `/docs/`, `/.claude/` → not 200.
8. Stop the server. Report pass/fail per page.
