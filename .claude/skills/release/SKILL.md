---
name: release
description: Build and verify the upload zip for kuvadoo.fi (full first release, or a code-only update that keeps the owner's admin content) and give the owner Hostinger upload steps. Use when changes are ready to go live.
---

# Release

1. Run the `site-check` skill; do not release with failures.
2. Build from the repo root.
   - **First release (full):**
     `rm -f kuvadoo.fi.zip && zip -qr kuvadoo.fi.zip . -x '.git' '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' 'router.php' '.gitignore' '*.zip' 'data/auth.json' 'data/setup-code.txt' 'data/login-attempts.json' 'data/.lock'`
   - **Update after launch (keeps the owner's photos, prices and texts on the server):**
     `rm -f kuvadoo.fi-update.zip && zip -qr kuvadoo.fi-update.zip . -x '.git' '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' 'router.php' '.gitignore' '*.zip' 'data/*' 'media/*'`
     If an update needs a new data field, make the PHP code tolerate its absence (defaults) instead of
     shipping data files.
   The owner's file transfer limit is 30 MB: split the full release into parts under ~27 MB
   (part 1 = everything except `media/photos/*` plus the first photos; further parts = more photos), named
   `kuvadoo.fi-partN-of-M.zip`, and check that extracting all parts gives the same file list as the full zip.
3. Verify: `unzip -l <zip>` — `index.php`, `.htaccess`, `.user.ini` at the top level; no `.git`, `docs`,
   `tools`, `router.php`, `auth.json` or `setup-code.txt` inside. Note the size.
4. Commit and push (zips are git-ignored; send them to the owner as files).
5. Give the owner the matching part of `DEPLOY.md` in simple English.
6. After they upload, run the live part of `site-check`.
