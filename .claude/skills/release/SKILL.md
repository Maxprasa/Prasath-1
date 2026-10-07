---
name: release
description: Build and verify the upload zip for kuvadoo.fi and give the owner Hostinger upload steps. Use when changes are ready to go live.
---

# Release

1. Run the `site-check` skill; do not release with failures.
2. Build from the repo root:
   `rm -f kuvadoo.fi.zip && zip -r kuvadoo.fi.zip . -x '.git' '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' '.gitignore' '*.zip'`
3. Verify: `unzip -l kuvadoo.fi.zip` — `index.html` and `.htaccess` at the top level, no repo-only files, no
   original (unoptimised) photos inside; note the zip size.
4. Commit (including the zip) and push.
5. Send the owner the zip and these steps (simple English): hPanel → Websites → kuvadoo.fi → File Manager →
   `public_html` → Upload the zip → right-click → Extract → overwrite → delete the zip.
   For the **first** release, follow the "Hosting switch" plan in `docs/PLAN.md` / `DEPLOY.md` instead, so
   the site is never down.
6. After they upload, run the live part of `site-check`.
