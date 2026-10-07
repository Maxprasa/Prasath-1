# apps.kuvadoo.fi — redesign plan (v2)

Status: **approved 2026-10-07 (calm & warm, English, no About page yet)** · Research: `docs/research/` · Rules: `CLAUDE.md`

## Goal

Turn the simple v1 site into a professional studio site that looks like the best indie app makers
(Flexibits, Things, Sindre Sorhus, Fossify) and scales to 5–20 apps — while staying static, fast, free of
trackers, and 100 % honest (no invented ratings, users, awards or reviews).

## What the research says professional app sites have (and v1 lacks)

| Pattern (seen on) | v1 | v2 |
|---|---|---|
| Strong hero with a phone mockup (Things, Bear, Drops) | text only | CSS phone frame + screenshot slot |
| Same skeleton on every app page (all) | partly | fixed component set reused per app |
| Benefit grid with icons under the hero (Babbel, Busuu) | none | 4 icon tiles (inline SVG) |
| Alternating feature rows with screenshots (Memrise, Bear) | bullet lists | 5 feature rows, left/right |
| Privacy as a selling point (Overcast, Fossify) | none | "Privacy at a glance" + site-wide "no trackers" |
| FAQ (Babbel, Sindre Sorhus) | none | 8–10 questions answered only from facts |
| Footer that routes: Apps / Support / Company / Legal (Flexibits, Bear) | one line | 4 columns + publisher line |
| Support hub (Things) | none | `/support/` listing every app |
| "More from <developer>" cross-links (Sindre Sorhus) | none | added when app #2 exists |
| Brand mark / logo | text | inline-SVG Kuvadoo wordmark |

## Site map (URLs Play Console already uses stay unchanged)

```
/                          Home: hero, app grid, principles, about strip, contact CTA
/finnsana/                 App page (redesigned)
/finnsana/privacy/         (unchanged URL) privacy — still links to the live Google Sites policy
/finnsana/terms/           (unchanged URL)
/finnsana/delete-account/  (unchanged URL) redesigned as a clear step-by-step page
/support/                  NEW: help hub — per-app FAQ links, deletion links, contact
/privacy/                  NEW: website privacy — no cookies, no analytics, no trackers
/about/                    LATER (owner chose "not now")
/press/                    LATER: icons, screenshots, fact sheet per app (once real assets exist)
```

## Home page sections
1. **Hero** — "Kuvadoo apps" + one-line studio promise, two CTAs (See our apps · Contact).
2. **App grid** — card per app: icon, name, tagline, platform · price chips, status badge
   ("In testing" / "Available"). Grid auto-fills, so app #2…#20 just add a card.
3. **Our promise** — "No ads in any Kuvadoo app" (owner-confirmed). Other principles not confirmed.
4. **About strip** — "Made by Kuvadoo in Hämeenlinna, Finland", link to www.kuvadoo.fi.
5. **Contact band** — email CTA.

## FinnSana page sections (all text from `docs/facts/finnsana.md`)
1. **Hero** — icon, "FinnSana: Learn Finnish", one-line, chips (Android · Free · No ads · No account),
   status "In testing on Google Play", text button "Coming soon to Google Play" (no fake badge — Google
   has no "coming soon" badge), CSS phone mockup with screenshot slot 1.
2. **Benefit tiles** — 5,000 words · 100+ grammar lessons · games · smart review.
3. **Five feature rows** (alternating, screenshot slots 2–5): Words · Grammar · Practice · Smart review ·
   Made for real learners. Inline SVG icons.
4. **Levels strip** — A1 · A2 · B1 · B2 (CEFR).
5. **Privacy & data at a glance** — no account needed; optional sign-in for backup; how to delete;
   links to policy and deletion page.
6. **FAQ** (`<details>`) — Is it free? Do I need an account? Which levels? Is there an iPhone version?*
   Does it work offline? Are the voices real people? Where do the pictures come from? How do I delete my
   data? How do I give feedback? (*only "Android" is stated — answer: "FinnSana is for Android".)
7. **Honest notes** — independent app, computer-generated voices and pictures, Tatoeba credit.
8. **CTA band** + per-app links row (Privacy · Terms · Delete account · Contact).

## Design system (documented in `docs/design-system.md`)
- Tokens: forest green `#1F6F4A`, warm off-white, dark theme; per-app accent `--app-accent`.
- Type: system font stack, fluid scale with `clamp()` (body 18 px, h1 40–56 px), 65-char measure.
- Spacing: 8 px grid; generous section padding (64 px mobile / 96 px desktop).
- Components: header (sticky, compact), app card, chip, status badge, phone frame, benefit tile,
  feature row, levels strip, FAQ, CTA band, legal page layout, 4-column footer.
- Motion: none required; any hover transitions disabled under `prefers-reduced-motion`.
- Zero JS. Zero external requests. Page weight target < 60 KB per page excluding screenshots.

## SEO
Unique titles/meta (e.g. "FinnSana: Learn Finnish – Free Android App (A1–B2)"), canonical URLs,
Open Graph + 1200×630 share image, JSON-LD (`Organization` on home, `MobileApplication` on app page —
price 0, no ratings — `FAQPage`, `BreadcrumbList`), sitemap updated, `.htaccess` blocks repo-only files.

## Agent team (`.claude/agents/`) and workflows (`.claude/skills/`)

| Agent | Role | Edits files? |
|---|---|---|
| ui-ux-designer | layout, design system, CSS, components | yes |
| copywriter | all user-facing text, claims traced to facts | yes |
| seo-specialist | titles, meta, JSON-LD, sitemap, keywords | yes |
| aso-strategist | Google Play listing text, screenshot captions, launch plan → `docs/store/` | docs only |
| accessibility-auditor | WCAG 2.2 AA audit, contrast in both themes | no (reports) |
| code-reviewer | HTML/CSS quality, links, rule violations | no (reports) |
| qa-tester | browser tests at 360/1280 px, light/dark, live smoke test | no (reports) |
| play-store-compliance | Play policy: privacy, deletion page, badge rules, GDPR basics | no (reports) |
| research-analyst | sourced web research → `docs/research/` | docs only |

Skills: `site-check` (render + test everything), `add-app-page` (new app in one go), `release`
(build/verify zip + upload steps). Every change flows: **design → copy → SEO → audits → site-check →
release**.

## Build phases
1. Design system + shared components (CSS, header, footer, logo).
2. Home page.
3. FinnSana page + delete-account/privacy/terms restyled.
4. New `/support/` and `/privacy/` pages (and `/about/` if owner supplies text).
5. SEO pass (meta, OG image, JSON-LD, sitemap).
6. Audits by accessibility, code-review, compliance agents → fixes.
7. site-check → release zip → owner uploads → live check.

## What the owner needs to provide (some optional)
- Real FinnSana icon (512×512 PNG) and 5 phone screenshots (9:16). Placeholders until then.
- Confirmation of studio-wide principles for the home page (or keep them FinnSana-only).
- Optional: About text (your name? a photo? how Kuvadoo started), business ID (Y-tunnus) if wanted.
- Optional later: Finnish-language version of the site.
