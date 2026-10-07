# apps.kuvadoo.fi — project rules

Static website for **apps.kuvadoo.fi**: the home of all apps made by Kuvadoo (a sole trader in
Hämeenlinna, Finland; main site https://www.kuvadoo.fi, a photography business). One page per app.
First app: **FinnSana: Learn Finnish** (Android). More apps will be added the same way.

Live site: https://apps.kuvadoo.fi/ (Hostinger, "PHP/HTML website", files in `public_html`).

## Hard rules (apply to every agent and every change)

1. **Plain static files only.** HTML + CSS (+ inline SVG). No build step, no framework, no trackers,
   no analytics, no cookies, no external fonts, scripts or CDNs. Fonts must be self-hosted in
   `assets/fonts/` with their licence (currently Bricolage Grotesque, OFL, headings only). Small inline JS
   only if a feature cannot be done without it, and the page must work fully with JS off.
2. **Never invent facts.** Only state what the owner has written down (see `docs/facts/`). No
   "official", no test-preparation claims, no YKI mention, no user numbers, ratings, reviews, awards,
   press quotes or "human teacher". No fake testimonials. If a claim is not in the facts file, leave it out
   and ask the owner.
3. **Honest AI disclosure.** FinnSana's voices and pictures are computer-generated; say so wherever
   voices/pictures are described.
4. **Footer publisher line, exactly:**
   `© 2026 Kuvadoo, Hämeenlinna, Finland · kuvadoo@gmail.com`
   No postal address, no business ID unless the owner adds them.
5. **Language:** English, `<html lang="en">`. Finnish words in text get `lang="fi"` spans.
6. **Accessibility:** WCAG 2.2 AA — landmarks, one `h1`, logical headings, alt text, contrast AA in
   light *and* dark mode, visible focus, works at 360 px wide, respects `prefers-reduced-motion`.
7. **Links:** root-absolute (`/finnsana/`), every page has a trailing-slash folder URL with `index.html`.
8. **Google Play status:** FinnSana is in testing. The CTA says "Coming soon to Google Play"; the real
   link `https://play.google.com/store/apps/details?id=app.finnsana` stays in an HTML comment until the
   owner says the listing is public.
9. **Required per-app pages:** `/<app>/`, `/<app>/privacy/`, `/<app>/terms/`, `/<app>/delete-account/`
   (Google Play needs the privacy and account-deletion URLs). Never remove or move these URLs.
10. **Motion (v3 design):** micro-animations are pure CSS (entrance, scroll reveal via
   `animation-timeline`, hover, marquee). Content must be fully visible without animation support, and
   everything must switch off under `prefers-reduced-motion: reduce`.
11. **Screenshots:** phone frames use `assets/finnsana-screen-<name>.webp` (540×963, app screen only, no
   store captions). Names in use: home, path, word, flashcard, grammar, match, my-words. A missing file
   shows a branded placeholder automatically.

## Structure

```
index.html                 all apps + about + contact
<app>/index.html           app page
<app>/privacy|terms|delete-account/index.html
assets/style.css           the one stylesheet (design tokens at the top)
assets/<app>-icon.png      512×512 app icon
404.html, robots.txt, sitemap.xml, .htaccess, favicon.ico
docs/facts/<app>.md        the ONLY source of truth for claims about each app
docs/                      research, plan, design notes (not uploaded)
```

## Deploying

Build the upload zip from the repo root (excludes repo-only files):

```
zip -r apps.kuvadoo.fi.zip . -x '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' '.gitignore' 'apps.kuvadoo.fi.zip'
```

Upload in hPanel → Websites → apps.kuvadoo.fi → File Manager → `public_html` → upload → extract
(overwrite). See `DEPLOY.md`.

## Before calling any change done

- Every page loads at 360 px and 1280 px, light and dark, with no horizontal scroll.
- No broken internal links; no 404 assets.
- Every new claim is traceable to `docs/facts/`.
- `sitemap.xml` lists every public page.

## Team

Specialist agents live in `.claude/agents/`. Repeatable workflows live in `.claude/skills/`.
