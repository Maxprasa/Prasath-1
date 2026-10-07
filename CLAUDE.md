# kuvadoo.fi — project rules

Static website for **kuvadoo.fi**: the photography and video business **Kuvadoo** (a sole trader,
toiminimi, in Finland; contact kuvadoo@gmail.com). Video work is marketed as **Hämeen Films**, always
shown as **"Hämeen Films – a video service by Kuvadoo"** (a marketing name, not a separate company).

Apps live on a separate site, https://apps.kuvadoo.fi, in another branch of this repository. **Never change
the apps site from this project.** kuvadoo.fi may link to it from the footer, nothing more.

Status (2026-10-07): **planning.** Do not build pages until the owner has approved the site map and design
direction in `docs/PLAN.md`. Open questions for the owner are listed there.

The owner is not a native English speaker: keep messages, plans and docs in short, simple English.

## Hard rules (every agent, every change)

1. **Plain static files only.** HTML + one CSS file (+ inline SVG). No build step, no framework, no
   trackers, no analytics, no cookies, no external fonts, scripts or CDNs. Fonts are self-hosted in
   `assets/fonts/` with their licence. Small inline JS only if a feature cannot work without it, and the page
   must work fully with JS off.
2. **Video: click-to-load only.** Never load a YouTube/Vimeo player (or any third-party request) on page
   load. Show a local poster image + play button; load the embed (youtube-nocookie.com / Vimeo `dnt=1`)
   only after the visitor clicks, with a short note that the video comes from YouTube/Vimeo. Without JS the
   button is a normal link to the video.
3. **Never invent facts.** Only state what the owner has written down in `docs/facts/kuvadoo.md`. No invented
   reviews, testimonials, client names, numbers ("500 weddings"), awards, "best in Finland", or press quotes.
   Client names and photos of people appear only with the owner's confirmation that they have permission.
   If a claim is not in the facts file, leave it out and ask the owner.
4. **Prices:** only prices the owner confirms in the facts file. A crossed-out "was" price may be shown only
   if it was the lowest price in the 30 days before the discount (EU price-reduction rule) — by default,
   don't show "was" prices at all. Say whether prices include VAT (owner to confirm).
5. **Business identity:** the footer shows the business name Kuvadoo, the Y-tunnus and the email once the
   owner gives them (exact line: to be confirmed, see `docs/PLAN.md`). Every page with a contact form or
   personal data must link the privacy notice (`/tietosuoja/`).
6. **Location:** the owner has not yet decided whether the town may be named on kuvadoo.fi (on
   apps.kuvadoo.fi it must not be). Until the owner decides, write only "Finland" / "Suomi".
7. **Language:** decided by the owner (see `docs/PLAN.md`). Each page is in one language only, with the
   correct `<html lang>`; words in the other language get a `lang` span. No half-translated pages.
8. **Accessibility:** WCAG 2.2 AA — landmarks, one `h1`, logical headings, real alt text for every portfolio
   image (describe the photo, no camera file names), contrast AA in light *and* dark mode, visible focus,
   works at 360 px wide, respects `prefers-reduced-motion`.
9. **Images:** WebP, responsive `srcset` sizes, `width`/`height` set, `loading="lazy"` below the fold, hero
   image preloaded. Strip EXIF/GPS data. File names describe the photo
   (`haat-puistossa-1600-v1.webp`), never `DSC0001`. **When an image changes, bump the version in the file
   name** — Hostinger's CDN caches images by file name for 7 days.
10. **Motion:** pure CSS micro-animations only. Content is fully visible without animation support, and all
    motion switches off under `prefers-reduced-motion: reduce`. No autoplaying video with sound.
11. **Links:** root-absolute (`/hameen-films/`), every page is a trailing-slash folder with `index.html`.

## Structure (planned — confirm in docs/PLAN.md)

```
index.html                 home
<section>/index.html       one folder per page (trailing-slash URLs)
assets/style.css           the one stylesheet (design tokens at the top)
assets/fonts/              self-hosted fonts + licence
assets/img/                optimised WebP images
404.html, robots.txt, sitemap.xml, .htaccess, favicon.ico
docs/facts/kuvadoo.md      the ONLY source of truth for claims, prices and identity
docs/                      brief, research, plan, design notes (not uploaded)
tools/                     check scripts (not uploaded)
```

## Deploying (Hostinger)

kuvadoo.fi is currently a Hostinger **Website Builder** site. The static site needs a **PHP/HTML website**
for the domain; the switch must be planned so the site is never down (see `docs/PLAN.md`, "Hosting switch").

Build the upload zip from the repo root (excludes repo-only files):

```
zip -r kuvadoo.fi.zip . -x '.git' '.git/*' '.claude/*' 'docs/*' 'tools/*' 'CLAUDE.md' 'DEPLOY.md' '.gitignore' '*.zip'
```

Upload in hPanel → Websites → kuvadoo.fi → File Manager → `public_html` → upload → extract (overwrite).

## Before calling any change done

- Every page loads at 360 px and 1280 px, light and dark, with no horizontal scroll (`site-check` skill).
- No broken internal links, no 404 assets, no third-party request before a click.
- Every claim, price and name is traceable to `docs/facts/kuvadoo.md`.
- `sitemap.xml` lists every public page.

## Team

Specialist agents live in `.claude/agents/`; repeatable workflows in `.claude/skills/` (`site-check`,
`release`).
