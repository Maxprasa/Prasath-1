---
name: release
description: Build and verify the upload zip for apps.kuvadoo.fi and give the owner Hostinger upload steps. Use when changes are ready to go live.
---

# Release

1. Run the `site-check` skill; do not release with failures.
2. Build from the repo root:
   `rm -f apps.kuvadoo.fi.zip && zip -r apps.kuvadoo.fi.zip . -x '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' '.gitignore' 'apps.kuvadoo.fi.zip'`
3. Verify: `unzip -l apps.kuvadoo.fi.zip` — `index.html` at the top level, no repo-only files inside.
4. Commit (including the zip) and push.
5. Send the owner the zip and these steps: hPanel → Websites → apps.kuvadoo.fi → File Manager →
   `public_html` → Upload the zip → right-click → Extract → overwrite → delete the zip.
6. After they upload, run the live part of `site-check`.
