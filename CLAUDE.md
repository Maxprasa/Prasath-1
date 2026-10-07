# kuvadoo.fi — project rules

Static website for **kuvadoo.fi**: the photography and video business **Kuvadoo** (a sole trader,
toiminimi, in Finland; contact kuvadoo@gmail.com). Video work is marketed as **Hämeen Films**, always
shown as **"Hämeen Films – a video service by Kuvadoo"** (a marketing name, not a separate company).

Apps live on a separate site, https://apps.kuvadoo.fi, in another branch of this repository. **Never change
the apps site from this project.** kuvadoo.fi may link to it from the footer, nothing more.

Status (2026-10-07): **built, not yet live.** Plan approved by the owner (`docs/PLAN.md`, owner answers in
`docs/facts/kuvadoo.md`). Next: owner uploads to a test address (`DEPLOY.md`), checks, then kuvadoo.fi switches.

The owner is not a native English speaker: keep messages, plans and docs in short, simple English.

## Hard rules (every agent, every change)

1. **Small PHP site, no database (owner wants an admin panel, 2026-10-07).** PHP 8 templates read JSON files
   in `data/`; the owner edits them in the admin panel at `/hallinta/`. No framework, no Composer, no build
   step, no trackers, no analytics, no visitor cookies (only the admin session cookie on `/hallinta/`), no
   external fonts, scripts or CDNs. Fonts are self-hosted in `assets/fonts/` with their licence.
   `assets/site.js` only enhances (lightbox, click-to-load video); every page works with JS off.
   Admin security is not optional: CSRF token on every POST, escape every output with `e()`, re-encode
   uploads with GD, never trust file names, keep `data/`, `app/` private (.htaccess).
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
9. **Images:** WebP in widths 480/960/1600/2200 (`img()` writes `srcset`, `width`/`height`, lazy loading;
   hero preloaded). EXIF/GPS removed by re-encoding. File names = album slug + random id, so a new upload
   always gets a new name (Hostinger's CDN caches by file name). CSS/JS: bump `ASSET_VER` in bootstrap.php.
10. **Motion:** pure CSS micro-animations only. Content is fully visible without animation support, and all
    motion switches off under `prefers-reduced-motion: reduce`. No autoplaying video with sound.
11. **Links:** always build URLs with `url()`; every page URL ends with a slash.

## Structure

```
index.php                  front controller (all page URLs go here via .htaccess)
router.php                 local dev only: php -S 127.0.0.1:8765 router.php (not uploaded)
.htaccess, .user.ini       rewrite + private folders; PHP upload limits
app/bootstrap.php          helpers: data_get/data_put (JSON), e(), tr(), txt(), t(), img(), categories()
app/routes.php             FI/EN URL map, url(), old Website Builder 301 redirects
app/i18n.php               fixed interface strings FI/EN
app/images.php             GD: resize to WebP widths, EXIF/GPS stripped; YouTube poster download
app/views/*.php            public pages (layout.php = head, header, footer)
app/admin/*.php            admin panel (/hallinta/): auth.php, admin.php (actions), views.php (forms)
data/*.json                content.json (texts FI/EN + contact settings), albums, photos, videos, prices
                           auth.json / setup-code.txt / login-attempts.json are created on the server, never committed
media/photos, media/videos uploaded and generated images
assets/style.css           public stylesheet (tokens at the top); assets/admin.css; assets/site.js
docs/facts/kuvadoo.md      the ONLY source of truth for claims, prices and identity
tools/                     seed_content.py, import-old-site.php, check-site.mjs (not uploaded)
```

URLs: Finnish `/valokuvaus/`, `/valokuvaus/<kategoria>/<albumi>/`, `/hameen-films/`, `/hinnasto/`, `/tietoa/`,
`/yhteystiedot/`, `/tietosuoja/`, `/varausehdot/`; English under `/en/` (`/en/photography/…`, `/en/prices/`, …).
Content changes made by the owner in the admin live only on the server: **never overwrite the server's
`data/` and `media/` with the repo copy after launch** (the release zip for updates excludes them).

## Deploying (Hostinger)

kuvadoo.fi is currently a Hostinger **Website Builder** site. The static site needs a **PHP/HTML website**
for the domain; the switch must be planned so the site is never down (see `DEPLOY.md`).

See `DEPLOY.md` and the `release` skill. First release = full zip (with `data/` and `media/`); later code
updates = zip **without** `data/` and `media/`, so the owner's admin changes are kept.

## Before calling any change done

- `php -l` clean; every page loads at 360 px and 1280 px, light and dark, no horizontal scroll (`site-check`).
- Admin flow still works (`tools/admin-test.mjs` on a throwaway copy of the site).
- No broken internal links, no 404 assets, no third-party request before a click.
- Every claim, price and name is traceable to `docs/facts/kuvadoo.md`.
- `/sitemap.xml` (generated) lists every public page.

## Team

Specialist agents live in `.claude/agents/`; repeatable workflows in `.claude/skills/` (`site-check`,
`release`).
