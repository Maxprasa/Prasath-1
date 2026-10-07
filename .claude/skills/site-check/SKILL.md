---
name: site-check
description: Render and test every page of apps.kuvadoo.fi locally (phone + desktop, light + dark), check links, assets, headings and horizontal scroll, and optionally smoke-test the live site. Use before every commit that changes HTML/CSS and before making a release.
---

# Site check

1. Start a local server from the repo root (background): `python3 -m http.server 8765`
2. Make sure Playwright is available: `node -e "require.resolve('playwright')"`; if not, install it outside
   the repo (e.g. in a scratch dir with `npm i playwright`) and run with `NODE_PATH` pointing there.
   Never commit `node_modules`.
3. Run `node tools/check-site.mjs` (screenshots go to `tools/screenshots/`, which is git-ignored).
4. Open the screenshots for every changed page (at least light-360 and dark-1280) and look at them.
5. Fact check: every claim in changed copy must appear in `docs/facts/<app>.md`.
6. Live check (after upload), for each URL in sitemap.xml:
   `curl -s -o /dev/null -w '%{http_code}' https://apps.kuvadoo.fi<path>` → 200; `/DEPLOY.md`,
   `/CLAUDE.md`, `/docs/`, `/.claude/` → not 200.
7. Stop the server. Report pass/fail per page.
