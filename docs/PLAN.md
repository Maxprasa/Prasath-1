# kuvadoo.fi — plan (DRAFT, waiting for owner approval)

Date: 2026-10-07. Nothing is built until the owner approves sections 2 and 3 and answers section 1.
Research behind this plan: `docs/research/2026-10-photographer-sites.md` (8 sites),
`docs/research/2026-10-kuvadoo-site-and-brands.md`, `docs/research/2026-10-business-names-fi.md`.

## 1. Questions for the owner

1. **Language.** Proposal: Finnish first (all pages), plus a few English pages in `/en/` (home, services,
   prices, contact). Most good Finnish photographer sites are Finnish only. OK? Or English only / both full?
2. **Town.** May kuvadoo.fi name your town (e.g. "Valokuvaaja Hämeenlinna" in titles and headings)? Local
   search works much better with the town. Finnish competitors put the town in the main heading. (The apps
   site will still say only "Finland".)
3. **Services and prices.** Please confirm the real list and prices:
   - Photo: weddings, events, portraits, families, rippikuvat, business — which ones do you sell?
   - The three old packages (Starter 100 €, Popular 160 €, Signature Event 250 €): still valid? What do they
     include? We will **not** show the crossed-out "was" prices (EU rule).
   - Video (Hämeen Films): what do you film, and what does it cost (or "from" price)?
   - Photo + video together: a package?
   - Is VAT included? Travel costs (per km, or free inside an area)? Delivery time and how (online gallery)?
4. **Y-tunnus** for the footer and contact page.
5. **Photos and videos.** Please send one zip with your best photos, in folders per category (weddings,
   events, portraits, families, rippikuvat, business) — 10–20 per category is enough, full size. For video:
   YouTube or Vimeo links (a showreel is great). Tell us which client photos we may use (people must have
   agreed), and if client names may be shown.
6. **About you.** Your name, one photo of you, and a few lines: why you do this, how long, what you like
   to shoot. (Real faces and names build trust; competitors all do this.) Or keep it anonymous?
7. **Contact.** Which ways: email (yes), phone, WhatsApp? Proposal: email + phone/WhatsApp buttons, and a
   simple contact form that sends email through Hostinger (with a short privacy notice). Or no form, just
   email/phone (simplest)?
8. **Hosting switch.** kuvadoo.fi is now a Website Builder site. Proposal: we build and test the new site
   at a test address first (e.g. a subdomain `uusi.kuvadoo.fi`), then switch the main domain in one step and
   add redirects from old addresses. Are you OK to change the Hostinger site type when ready?
9. **Hämeen Films domain.** Do you want to buy `hameenfilms.fi` (it looked free on 2026-10-07)? It can point
   to the Hämeen Films page. Also: the Hämeen Films Facebook page address.
10. **Footer line.** Proposal: `© 2026 Kuvadoo · Y-tunnus 1234567-8 · kuvadoo@gmail.com` (+ "Hämeen Films –
    Kuvadoon videopalvelu"). OK?

## 2. Site map (proposal)

Finnish URLs (Finnish first). English copies under `/en/` if you say yes in question 1.

```
/                         Etusivu — hero photo, what we do (photo / video), selected work,
                          prices teaser, about teaser, how booking works, contact
/haat/                    Häät — wedding photo (+ video), gallery, prices, FAQ
/perhe-ja-muotokuvat/     Perhe- ja muotokuvat — families + portraits
/rippikuvat/              Rippikuvat — confirmation photos (season, place, price, booking)
/tapahtumat/              Tapahtumat — events and festivals (Wanaja, Drift Masters, Mellakka…)
/yrityksille/             Yrityksille — business photos and video
/hameen-films/            Hämeen Films – Kuvadoon videopalvelu — showreel, video types, video prices
/hinnasto/                Hinnasto — all prices in one table: photo / video / photo + video
/tietoa/                  Tietoa — about the photographer, the name "Kuvadoo" (kuva = picture)
/yhteystiedot/            Yhteystiedot — email, phone, form, Y-tunnus, area served
/tietosuoja/              Tietosuoja — privacy notice (form, email)
/varausehdot/             Varausehdot — booking & cancellation terms (if you want them; text from you)
/404.html
```

Menu (one menu for the whole site): **Kuvaus ▾ (Häät, Perhe, Rippikuvat, Tapahtumat, Yrityksille) ·
Hämeen Films · Hinnasto · Tietoa · Yhteystiedot**. On phones: a menu that works without JavaScript.

Footer: Kuvadoo + Y-tunnus + email, Hämeen Films line, Instagram/Facebook text links, privacy notice,
link to apps.kuvadoo.fi ("Sovellukset").

Old addresses get 301 redirects (e.g. `/videos` → `/hameen-films/`, `/samantha-and-teemu` → `/haat/`,
`/rippikuvaus-hameenlinna` → `/rippikuvat/`, `/en` → `/en/`), so old links keep working.

## 3. Design direction (proposal)

**Idea: "a quiet gallery".** The photos are the product, so the design steps back.

- **Layout:** big full-width hero photo with a short heading on it; lots of white space; galleries as clean
  grids (mixed portrait/landscape, nothing cropped badly). Each service page = intro text, 9–15 photos,
  price, "Kysy vapaita päiviä" (ask for free dates) button.
- **Colours:** warm off-white and near-black for light mode; deep charcoal for dark mode; one accent colour
  for buttons. **Hämeen Films** gets a darker, cinema feel on its page (dark background, wide 16:9 posters)
  but the same menu, fonts and footer, so it clearly belongs to Kuvadoo.
- **Type:** one elegant self-hosted font for headings (a serif or soft display font, open licence) + the
  system font for body text. Calm and readable.
- **Video:** poster image + play button. The YouTube/Vimeo player loads only when the visitor clicks (no
  cookies before that). No autoplaying background video.
- **Motion:** gentle fade-in of photos while scrolling, soft hover zoom — pure CSS, off for people who turn
  motion off.
- **Speed:** WebP photos in several sizes, lazy loading, target under ~1.5 MB for the home page on a phone.
- **Trust:** real name and face, real prices, clear "how booking works" steps (1. message → 2. plan →
  3. shoot → 4. photos in an online gallery — owner to confirm the steps), Y-tunnus. No fake reviews.

## 4. After approval: build order

1. Design system + home page with placeholder boxes → owner reviews screenshots.
2. Service pages + Hämeen Films + prices, with the owner's photos and facts.
3. About, contact, privacy, terms, 404, SEO (titles, schema, sitemap, redirects).
4. Checks (site-check, accessibility, code review) → test address → owner approves → switch domain.
