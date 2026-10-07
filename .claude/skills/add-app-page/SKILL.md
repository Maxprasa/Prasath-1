---
name: add-app-page
description: Add a new Kuvadoo app to apps.kuvadoo.fi — facts file, app page, privacy/terms/delete-account pages, home-page card, sitemap and footer links — using the existing FinnSana pages as the template. Use when the owner announces a new app.
arguments: [slug, app-name]
---

# Add a new app: $1 (`/$0/`)

1. **Facts first.** Ask the owner for (or read from their message) the app's approved facts and write
   `docs/facts/$0.md` using `docs/facts/finnsana.md` as the template: name, platform, price, one line,
   status, store URL, features, honest notes, privacy/terms/deletion text, forbidden claims.
   Do not build pages until the facts file exists.
2. **Pages.** Copy `finnsana/` to `$0/` and replace content using only the facts file:
   `$0/index.html`, `$0/privacy/`, `$0/terms/`, `$0/delete-account/` (if the app has accounts; otherwise a
   "Your data" page explaining how local data is removed). Set the app accent with
   `style="--app-accent: …"` on `<body>` (check AA contrast in both themes).
3. **Icon.** `assets/$0-icon.png` (512×512). Placeholder if none supplied.
4. **Wire it up.** Add the app card to the home page grid, an entry in the support hub
   (`/support/`) and in the website privacy page's list of app policies, and the new URLs to `sitemap.xml`.
   **Do not** add a header button or footer links for the app (CLAUDE.md rule 12).
5. **SEO.** Ask the seo-specialist agent for title, meta, Open Graph and `MobileApplication` JSON-LD.
6. **Review.** Run the copywriter (claims), accessibility-auditor, code-reviewer and
   play-store-compliance agents; fix findings.
7. **Test.** Run the `site-check` skill. Then the `release` skill.
