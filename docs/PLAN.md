# kuvadoo.fi — plan v2 (waiting for owner approval)

Updated 2026-10-07 after the owner's answers. Research: `docs/research/` (photographer sites, prices and
rules, company and owner, brand names). Facts: `docs/facts/kuvadoo.md`.

## 1. What we know now

- **Kuvadoo**, Y-tunnus **3635801-2**, sole trader, home town Hämeenlinna, registered 28.6.2026 (YTJ).
  **Not VAT-registered**, so prices are final prices with no VAT added.
- **Owner:** Prasath Sivakathiramalei, photographer in Hämeenlinna. Director and cinematographer of music
  videos and weddings in Sri Lanka (DOOFILMS). YouTube **@SPrasath** has about 20 own videos.
- **Hämeen Films** = only a name for the video work: "Hämeen Films – Kuvadoon videopalvelu".
- **Photos:** about 400 real photos from the old site (Wanaja, Mellakka, Drift Masters, a couple shoot,
  rippikuvat). The AI or stock pictures from the old Videos and Photography pages are **not** used.
- **Videos:** start with the owner's own YouTube videos (travel film, sci-fi short, music video making-of,
  car shoot). The owner replaces them later in the admin panel. No other people's videos.
- **No online shop.** Customers contact by **WhatsApp or email**.
- **Admin panel** to change photos, videos and prices at any time.

## 2. Technical change: a small admin panel (needs PHP)

A pure static site cannot have an admin panel. Hostinger's "PHP/HTML website" runs PHP, so:

- **No database.** Content is in a few JSON files (`/data/`, blocked from the web). Photos go in `/media/`.
- **Public pages** are small PHP templates that read the JSON. They are still fast, with no trackers, no
  cookies for visitors, and no external scripts or fonts.
- **Admin at `/hallinta/`** with password login. It works on a phone.
  - **Photos:** upload many at once. The server resizes them to WebP in 3 sizes and removes GPS/EXIF data.
    You can choose the category, write a short description (alt text), drag to change the order, set the
    cover and hero photo, and delete.
  - **Videos:** paste a YouTube link. The cover image is saved on our server, and you set the title,
    category and order. Visitors load YouTube only when they press Play.
  - **Prices:** edit, add, hide or reorder packages (name, price, what is included).
  - **Texts:** home heading, about text, contact details (WhatsApp number, email).
  - **Backup:** download all content as one zip.
- **Security:** strong password hash, login-attempt limit, CSRF protection, HTTPS only, uploads checked
  (images only, size limit). Only the admin gets a login cookie, which is a necessary cookie, so no
  cookie banner is needed.
- An empty category is hidden automatically. For example, "Häät" appears when you add wedding photos.

## 3. Site map

```
/                         Etusivu: hero photo, photo + video services, best work, prices teaser,
                          about teaser, "how booking works", WhatsApp / email buttons
/valokuvaus/              Valokuvaus: all photo services, galleries by category:
  /valokuvaus/tapahtumat/   events and festivals (Wanaja, Mellakka, Drift Masters)
  /valokuvaus/muotokuvat/   portraits, couples, families
  /valokuvaus/rippikuvat/   confirmation photos
  /valokuvaus/haat/         weddings (shown when photos exist)
  /valokuvaus/yrityksille/  business (shown when photos exist)
/hameen-films/            Hämeen Films – Kuvadoon videopalvelu: dark "cinema" page, showreel,
                          video list, video prices
/hinnasto/                Hinnasto: all prices (photo / video), travel, booking terms in short
/tietoa/                  Tietoa: Prasath, the story (Sri Lanka → Hämeenlinna), "kuva" = picture
/yhteystiedot/            Yhteystiedot: WhatsApp, email, Y-tunnus
/tietosuoja/              Tietosuoja: privacy notice (email, WhatsApp, admin)
/varausehdot/             Varausehdot: booking, cancellation, 14-day right, photo delivery
/hallinta/                admin (noindex, password)
```

Old Website Builder addresses (`/videos`, `/hinnasto`, `/wanaja-2026`, `/en/...` and others) get 301
redirects to the new pages.

## 4. Design: modern, image-first, with micro-animations

- **Look:** big full-screen hero photo with a short, strong heading. Clean grids, a lot of space, and
  Kuvadoo's black-and-white K logo. Warm off-white or black, with one accent colour.
- **Hämeen Films:** dark cinema look, wide 16:9 covers and a film-strip feel, with the same menu and footer.
- **Micro-animations (CSS):** photos fade and rise in on scroll; soft zoom on hover; gallery images
  appear one after another; buttons react on press; smooth page-section reveals. A lightbox to view
  photos big (small JS, but the page also works without JS). Everything is off when the visitor's device
  asks for "reduce motion".
- **Fonts:** one modern display font for headings (self-hosted, open licence) and system fonts for text.
- **Speed:** WebP in several sizes and lazy loading. The home page should be under ~1.5 MB on a phone.
- **Contact:** a sticky "WhatsApp" + "Sähköposti" button on phones.

## 5. Price proposal (from research; you can change everything in the admin)

The prices are final, with no VAT added, because Kuvadoo is not VAT-registered. They are below the local
leaders but above the old 100 € packages.

| Kuvadoo — photography | Price | Includes |
|---|---|---|
| Minikuvaus / Rippikuvat | alk. 140 € | 30–45 min, 1 place, 20 edited photos, online gallery |
| Muotokuva tai perhekuvaus | alk. 220 € | 60–90 min, up to 2 places, 40 photos |
| Tapahtumakuvaus | alk. 320 € | 2 h, 100+ photos; extra hour 120 € |
| Hääkuvaus: vihkiminen + parikuvat | alk. 690 € | up to 2.5 h, 120+ photos |
| Hääkuvaus: puoli päivää | alk. 1190 € | up to 5 h, 300+ photos |
| Hääkuvaus: koko päivä | alk. 1790 € | up to 9 h, 500+ photos; extra hour 140 € |
| Yrityskuvat | alk. 190 € | 1 h on site, up to 4 people, 2 photos each |

| Hämeen Films — video | Price | Includes |
|---|---|---|
| Tapahtuma- / yritysvideo | alk. 890 € | up to 4 h filming, 1–2 min film + 2 vertical clips |
| Häävideo: vihkiminen + parikuvaus | alk. 990 € | up to 3 h, 2–3 min film |
| Häävideo: koko päivä | alk. 1790 € | up to 8 h, 4–6 min film + 1 min trailer |
| Musiikkivideo | tarjouksen mukaan | by offer (no public market prices found) |

- **Travel:** included up to 30 km; after that 0.50 €/km both ways. The exact price is in the written
  offer before booking.
- **Page text:** "Kuvadoo ei ole arvonlisäverovelvollinen, joten hintoihin ei lisätä arvonlisäveroa.
  Hinnat ovat lopullisia." No crossed-out "was" prices.
- **Booking by WhatsApp or email** counts as a distance contract, so customers have a 14-day right to
  cancel. This is explained on `/varausehdot/`. No non-refundable deposit until an accountant or lawyer
  confirms.
- **Important: ennakkoperintärekisteri.** Kuvadoo is not in it. Until it is, private customers must report
  the payment to the tulorekisteri, and business customers must withhold tax (60 % without a tax card).
  This scares customers. **Recommendation: join the ennakkoperintärekisteri now** (free, at ytj.fi; it is
  separate from VAT). Prices do not change. (Vero.fi: ennakkoperintärekisteri, kotitalous ostaa palvelun.)
- **VAT limit 20 000 €/year:** above it, 25.5 % VAT starts and prices must be raised or include VAT.
- This is research, not legal advice. An accountant should check the VAT and terms text once.

## 6. Questions left (short)

1. **Language:** Finnish only, or Finnish + English? (English doubles the text work in the admin.)
2. **Town:** may we write "Valokuvaaja Hämeenlinna" in headings? It helps Google a lot.
3. **WhatsApp:** is **041 791 1831** the number to show?
4. **Address:** EU law asks a business site to show a street address. Show the full address from YTJ, or only
   "Hämeenlinna"? (Your choice; we will not show it without your OK.)
5. **Prices:** are the prices above OK as a start?
6. **About:** please send one photo of you. Should we write about DOOFILMS and Sri Lanka?
7. **Couple shoot:** may we show the names "Samantha & Teemu"? (Otherwise "Pariskuvaus".)
8. **Ennakkoperintärekisteri:** will you join it? (Recommended before the site goes live.)
9. **Hosting:** OK to build first on a test address (e.g. `uusi.kuvadoo.fi`), then switch kuvadoo.fi?

## 7. Build order (after approval)

1. Design system + home page + admin login → screenshots to the owner.
2. Galleries, Hämeen Films, prices, about, contact, privacy, terms → all real content.
3. Admin: photos, videos, prices, texts, backup.
4. SEO, redirects, checks (site-check, accessibility, code and security review) → test address → switch.
