# Brief: new kuvadoo.fi (photography & video) — for a new session

Owner: Kuvadoo, a sole trader (toiminimi) in Finland. Contact: kuvadoo@gmail.com.
Written 2026-10-07 from the apps.kuvadoo.fi session. Research with sources: `docs/research/` in this repo
(especially `2026-10-kuvadoo-site-and-brands.md` and `2026-10-business-names-fi.md`).

## Goal

Rebuild **https://kuvadoo.fi** (currently a Hostinger Website Builder site, not yet shared publicly) as a
brand-new, modern, professional photography & video website, with a **Hämeen Films** tab/section for video.
Plan first, research best photographer/videographer sites, agree the design with the owner, then build.

## Brand structure (owner decisions so far)

- **Kuvadoo** = the registered business (one Y-tunnus) and the photography brand. kuvadoo.fi = photography.
- **Hämeen Films** = video brand, used as a *marketing name* of Kuvadoo (not registered; owner may register it
  later as an aputoiminimi, ~75 €). Always shown as **"Hämeen Films – a video service by Kuvadoo"** with the
  Y-tunnus in the site footer / about / contact. Locally known from the owner's Facebook page.
- **Apps** live separately at https://apps.kuvadoo.fi (do not change that site from the new session; link to it
  from the kuvadoo.fi footer at most).
- Domains: **hameenfilms.fi** and **hameenfilms.com** looked unregistered on 2026-10-07 (RDAP 404) — owner should
  buy hameenfilms.fi; it can redirect to the Hämeen Films section. **kuvadoo.com** also looked free.

## Open questions to ask the owner first

1. Language: Finnish first with English version? (current site has both, but Finnish pages are half English).
2. Location wording: on the apps site the owner chose "Finland" only. For a **local** photography site, local
   search works best with the town in page titles ("Valokuvaaja Hämeenlinna"). Ask whether the town may be
   used on kuvadoo.fi.
3. Services and prices: confirm the real list. Current site shows packages Starter 100 € (was 150 €), Popular
   160 € (was 200 €), Signature Event 250 € (was 350 €) — confirm what they include. **EU price-reduction rule:**
   a crossed-out "was" price must be the lowest price in the 30 days before the discount; otherwise don't show it.
4. Y-tunnus to show in the footer/contact (required to identify the business).
5. Photos and videos: owner should send a zip of best photos (and video links, e.g. YouTube/Vimeo) per category:
   weddings, events (e.g. festival photos already on the site), portraits, families, confirmations (rippikuvat),
   business content. The current site's own gallery photos may be reused with the owner's permission.
6. About text: the owner's name/photo? (builds trust; competitors use personal names and faces).
7. Booking/contact: email + form? WhatsApp? Phone? A contact form needs a privacy notice and a way to send mail
   on static hosting (or use a mailto link to stay static).
8. Hosting: kuvadoo.fi is a Website Builder site. A static site needs a "PHP/HTML website" in Hostinger
   (owner did this for apps.kuvadoo.fi via Websites → Create website → PHP/HTML → Upload files). Moving the main
   domain means replacing the builder site — plan the switch so the site is never down.

## Problems on the current kuvadoo.fi (from the review)

1. Price list page (/hinnasto) is empty; packages exist only as shop products with crossed-out prices.
2. No business identity (Y-tunnus), no privacy notice (contact form collects name/email/phone), no terms.
3. Loads **Google Tag Manager** and Google Fonts → under GDPR needs consent; the new site should have **no
   trackers or external fonts** (self-host fonts), like apps.kuvadoo.fi.
4. Template filler text ("Frictionless Digital Delivery", stock image), empty Videos page, empty areas on mobile.
5. Weak SEO: generic H1 "KUVAUS JA VIDEO", gallery images without alt text and with camera file names.

## Technical approach (same as apps.kuvadoo.fi, proven to work on Hostinger)

- Plain static HTML + one CSS file, self-hosted fonts, no trackers/cookies, pure-CSS micro-animations with
  `prefers-reduced-motion` off-switch, WCAG 2.2 AA, mobile-first, light/dark.
- Optimised images (WebP, responsive sizes, lazy loading) — photography sites live or die on image speed.
- Hostinger CDN caches images by file name for 7 days: version image names (e.g. `-v2`) when they change.
- Deliver as an upload zip + DEPLOY.md; owner uploads via File Manager → public_html → Extract.
- Reuse the specialist agents/skills pattern from this repo (`.claude/agents/`, `.claude/skills/`):
  ui-ux-designer, copywriter, seo-specialist, accessibility-auditor, code-reviewer, qa-tester.

## Suggested site map (to confirm with the owner)

```
/                    hero (best photo), services, featured work, packages, about, contact
/valokuvaus/         photography: weddings, events, portraits, families, rippikuvat, business
/hameen-films/       Hämeen Films video: showreel, video packages, "a video service by Kuvadoo"
/hinnasto/           prices & packages
/galleria/<name>/    galleries (e.g. a wedding, a confirmation shoot, a festival)
/meista/             about the photographer
/yhteystiedot/       contact (mailto / form), Y-tunnus
/tietosuoja/         privacy notice
/en/ ...             English versions (if chosen)
```

## Rules to carry over

- Never invent facts, reviews, client names, awards or numbers. Only owner-supplied facts.
- Footer on kuvadoo.fi: business name Kuvadoo + Y-tunnus + email (owner to confirm exact line).
- Keep the apps site separate; do not move or edit apps.kuvadoo.fi from the new project.
